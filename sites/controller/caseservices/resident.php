<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
header ( "Content-type: bitmap; charset=utf-8" );
class Controllercaseservicesresident extends Controller {
	private $error = array ();
	private $additional_enc = 'noteactive';
	
	public function jsonClientList() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$facilities_id = "63";
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $facilities_id;
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			/*
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$data = array ();
			$data ['facilities_id'] = $facilities_id;
			
			$data ['status'] = '1';
			$this->load->model ( 'setting/shift' );
			$this->data ['shifts'] = $this->model_setting_shift->getshifts ( $data );
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'setting/image' );
			
			$this->load->model ( 'notes/clientstatus' );
			
			$currentdate = $this->request->post ['date_added'];
			
			$date = str_replace ( '-', '/', $currentdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			$this->load->model ( 'setting/timezone' );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
			$facilitytimezone = $timezone_info ['timezone_value'];
			
			$datat3 = array ();
			$datat3 = array (
					'status' => 1,
					'discharge' => '1',
					// 'role_call' => '1',
					// 'searchdate' => $currentDate,
					'gender2' => $this->request->post ['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $facilities_id,
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1',
					'start' => 0,
					'limit' => 20 
			);
			
			$tags = $this->model_setting_tags->getTags ( $datat3 );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/locations' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			$unique_id = $facility ['customer_key'];
			
			// var_dump($unique_id); die;
			
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize ( $customer_info ['client_info_notes'] );
			
			$client_view_options2 = $client_info ["client_view_options"];
			
			// echo '<pre>'; print_r($client_info); echo '</pre>'; die;
			
			// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
			$this->data ['show_client_image'] = $client_info ["show_client_image"];
			$this->data ['show_form_tag'] = $client_info ["show_form_tag"];
			$this->data ['show_task'] = $client_info ["show_task"];
			$this->data ['show_case'] = $client_info ["show_case"];
			
			$this->data ['tags'] = array ();
			if (! empty ( $tags )) {
				foreach ( $tags as $tag ) {
					
					$allform_info = $this->model_form_form->gettagsforma ( $tag ['tags_id'] );
					
					if ($allform_info != null && $allform_info != "") {
						$screenig_url = $this->url->link ( 'services/form', '' . '&tags_forms_id=' . $allform_info ['tags_forms_id'] . '&tags_id=' . $allform_info ['tags_id'] . '&notes_id=' . $allform_info ['notes_id'] . '&forms_design_id=' . $allform_info ['custom_form_type'] . '&forms_id=' . $allform_info ['forms_id'] . '&facilities_id=' . $allform_info ['facilities_id'], 'SSL' );
					} else {
						$screenig_url = '';
					}
					
					$tagcolors = array ();
					/*
					 * $alltagcolors = $this->model_resident_resident->getagsColors($tag['tags_id']);
					 *
					 * foreach ($alltagcolors as $alltagcolor) {
					 *
					 * $tagcolors[] = array(
					 * 'color_id' => $alltagcolor['color_id'],
					 * 'text_highliter_div_cl' => $alltagcolor['text_highliter_div_cl'],
					 * );
					 * }
					 */
					
					if ($tag ['privacy'] == '2') {
						$upload_file_thumb_1 = '';
						$image_url1 = '';
						$emp_last_name = mb_substr ( $tag ['emp_last_name'], 0, 1 );
					} else {
						if ($tag ['upload_file_thumb'] != null && $tag ['upload_file_thumb'] != "") {
							$upload_file_thumb_1 = $tag ['upload_file_thumb'];
						} else {
							$upload_file_thumb_1 = $tag ['upload_file'];
						}
						
						$emp_last_name = $tag ['emp_last_name'];
						
						// $image_url = file_get_contents($upload_file);
						$image_url1 = ''; // 'data:image/jpg;base64,'.base64_encode($image_url);
						
						$check_img = $this->model_setting_image->checkresize ( $tag ['upload_file'] );
					}
					
					$tasksinfo = $this->model_createtask_createtask->getTaskas ( $tag ['tags_id'], $changedDate );
					
					if ($tasksinfo) {
						$tasksinfo1 = $tasksinfo * 100;
					} else {
						$tasksinfo1 = '';
					}
					// var_dump($tasksinfo1);
					if ($tag ['privacy'] == '2') {
						$upload_file_thumb_1 = '';
						$image_url1 = '';
						$enroll_image = '';
						$emp_last_name = mb_substr ( $tag ['emp_last_name'], 0, 1 );
					} else {
						
						$get_img = $this->model_setting_tags->getImage ( $tag ['tags_id'] );
						
						if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
							$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
						} else {
							$upload_file_thumb_1 = $get_img ['enroll_image'];
						}
						
						$enroll_image = $get_img ['enroll_image'];
						// $upload_file = $tag['upload_file_thumb'];
						$emp_last_name = $tag ['emp_last_name'];
						
						// $image_url = file_get_contents($upload_file);
						$image_url1 = ''; // 'data:image/jpg;base64,'.base64_encode($image_url);
						
						$check_img = $this->model_setting_image->checkresize ( $get_img ['enroll_image'] );
					}
					
					$addTime = $this->config->get ( 'config_task_complete' );
					
					$top = '2';
					
					$tasktypes = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
					
					foreach ( $tasktypes as $tasktype ) {
						$taskTotal1 = 0;
						$taskTotal = 0;
						
						$taskTotal1 = $this->model_createtask_createtask->getCountTasklist ( $facilities_id, $changedDate, $top, $facilitytimezone, $tag ['tags_id'], $tasktype ['task_id'] );
						
						$taskTotal = $taskTotal + $taskTotal1;
					}
					
					if ($taskTotal) {
						$tttaskTotal = $taskTotal;
					} else {
						$tttaskTotal = '';
					}
					
					// var_dump($taskTotal);
					
					/*
					 * $d = array();
					 * $d['emp_tag_id'] = $tag['tags_id'];
					 * $d['searchdate'] = $currentdate;
					 * $d['start'] = 0;
					 * $d['limit'] = 1;
					 * $d['advance_search'] = 1;
					 * $d['advance_date_desc'] = 1;
					 * $d['facilities_id'] = $this->request->post['facilities_id'];
					 *
					 * $lastnotesinfo = $this->model_notes_notes->getnotess($d);
					 *
					 * if($lastnotesinfo[0]['notes_description']){
					 * $nnotes_description = $lastnotesinfo[0]['notes_description'];
					 * }else{
					 * $nnotes_description = '';
					 * }
					 */
					
					$nnotes_description = '';
					
					/*
					 * $recenttasksinfos = $this->model_createtask_createtask->getrecentTaskdetails($d);
					 *
					 * if($recenttasksinfos['description']){
					 * $tdescription = $recenttasksinfos['description'];
					 * }else{
					 * $tdescription = '';
					 * }
					 */
					
					$tdescription = '';
					
					/*
					 * $form_info = $this->model_form_form->gettagsformav($tag['tags_id']);
					 * if($form_info){
					 * $ndate_added = date('D F j, Y', strtotime($form_info['date_added'] .' +90 day'));
					 *
					 * }else{
					 * $ndate_added = '';
					 * }
					 */
					$ndate_added = '';
					
					$client_medicine = $this->model_resident_resident->gettagModule ( $tag ['tags_id'], 0, 0 );
					
					if ($client_medicine != null && $client_medicine != "") {
						$tagmed = '1';
					} else {
						$tagmed = '2';
					}
					
					$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );
					
					if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
						
						$status = $tagstatusinfo ['status'];
					} else {
						$status = '';
					}
					
					$alldata = $this->model_createtask_createtask->getalltaskbyid ( $tag ['tags_id'] );
					if ($alldata != NULL && $alldata != "") {
						$confirm_alert = "1";
					} else {
						$confirm_alert = "2";
					}
					
					if ($facilities_info ['client_facilities_ids'] != NULL && $facilities_info ['client_facilities_ids'] != "") {
						$facilitynames = $this->model_facilities_facilities->getfacilities ( $tag ['facilities_id'] );
						$facilityname = $facilitynames ['facility'];
					} else {
						$facilityname = '';
					}
					
					$role_call = $tag ['role_call'];
					$role_callname = "";
					$color_code = "";
					$role_type = "0";
					$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $role_call );
					if ($clientstatus_info ['name'] != null && $clientstatus_info ['name'] != "") {
						$role_callname = $clientstatus_info ['name'];
						$color_code = $clientstatus_info ['color_code'];
						$role_type = $clientstatus_info ['type'];
					}
					
					$client_view_options = $client_view_options2;
					
					if (isset ( $tag ['emp_first_name'] ) && $tag ['emp_first_name'] != '') {
						$client_view_options = str_replace ( '[emp_first_name]', $tag ['emp_first_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_first_name]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_middle_name'] ) && $tag ['emp_middle_name'] != '') {
						$client_view_options = str_replace ( '[emp_middle_name]', $tag ['emp_middle_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_middle_name]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_last_name'] ) && $tag ['emp_last_name'] != '') {
						$client_view_options = str_replace ( '[emp_last_name]', $tag ['emp_last_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_last_name]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emergency_contact'] ) && $tag ['emergency_contact'] != '') {
						$client_view_options = str_replace ( '[emergency_contact]', $tag ['emergency_contact'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emergency_contact]', '', $client_view_options );
					}
					
					if (isset ( $tag ['facilities_id'] ) && $tag ['facilities_id'] != '') {
						$result_info = $this->model_facilities_facilities->getfacilities ( $tag ['facilities_id'] );
						$client_view_options = str_replace ( '[facilities_id]', $result_info ['facility'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[facilities_id]', '', $client_view_options );
					}
					
					if (isset ( $tag ['room'] ) && $tag ['room'] != '') {
						$rresults = $this->model_setting_locations->getlocation ( $tag ['room'] );
						$client_view_options = str_replace ( '[room]', $rresults ['location_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[room]', '', $client_view_options );
					}
					
					if (isset ( $tag ['dob'] ) && $tag ['dob'] != '') {
						$client_view_options = str_replace ( '[dob]', $tag ['dob'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[dob]', '', $client_view_options );
					}
					
					if (isset ( $tag ['gender'] ) && $tag ['gender'] != '') {
						$client_view_options = str_replace ( '[gender]', $tag ['gender'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[gender]', '', $client_view_options );
					}
					
					if (isset ( $tag ['age'] ) && $tag ['age'] != '') {
						$client_view_options = str_replace ( '[age]', $tag ['age'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[age]', '', $client_view_options );
					}
					
					if (isset ( $tag ['ssn'] ) && $tag ['ssn'] != NULL) {
						$client_view_options = str_replace ( '[ssn]', $tag ['ssn'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[ssn]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_tag_id'] ) && $tag ['emp_tag_id'] != '') {
						$client_view_options = str_replace ( '[emp_tag_id]', $tag ['emp_tag_id'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_tag_id]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_extid'] ) && $tag ['emp_extid'] != '') {
						$client_view_options = str_replace ( '[emp_extid]', $tag ['emp_extid'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_extid]', '', $client_view_options );
					}
					
					if ($client_view_options != "" && $client_view_options != null) {
						$client_view_options_flag = nl2br ( $client_view_options );
					} else {
						$client_view_options_flag = $tag ['emp_first_name'] . ' ' . $tag ['emp_last_name'];
					}
					
					$this->load->model ( 'form/form' );
					$customlistvalues_info = $this->model_form_form->getcustomlistvalues ( $tag ['customlistvalues_id'] );
					
					if ($customlistvalues_info ['customlistvalues_name'] != null && $customlistvalues_info ['customlistvalues_name'] != "") {
						$customlistvalues_name = $customlistvalues_info ['customlistvalues_name'];
					} else {
						$customlistvalues_name = "";
					}
					
					$this->data ['tags'] [] = array (
							'name' => $tag ['emp_first_name'] . ' ' . $tag ['emp_last_name'],
							'facilityname' => $facilityname,
							'facilities_id' => $tag ['facilities_id'],
							'name2' => $client_view_options_flag,
							// 'client_view_flag'=> $client_view_options_flag,
							
							'emp_first_name' => $tag ['emp_first_name'],
							'medication_inout' => $tag ['medication_inout'],
							'emp_middle_name' => $tag ['emp_middle_name'],
							'ssn' => $tag ['ssn'],
							'emp_last_name' => $tag ['emp_last_name'],
							'emp_tag_id' => $tag ['emp_tag_id'],
							'age' => $tag ['age'],
							'tags_id' => $tag ['tags_id'],
							'emp_extid' => $tag ['emp_extid'],
							'gender' => $customlistvalues_name,
							'discharge' => $tag ['discharge'],
							'upload_file' => $enroll_image,
							'upload_file_thumb' => $get_img ['upload_file_thumb'],
							'upload_file_thumb_1' => $upload_file_thumb_1,
							'check_img' => $check_img,
							// 'upload_file' => $upload_file,
							'image_url1' => $image_url1,
							'privacy' => $tag ['privacy'],
							'stickynote' => $tag ['stickynote'],
							'role_call' => $role_call,
							'role_callname' => $role_callname,
							'color_code' => $color_code,
							'role_type' => $role_type,
							'tagallforms' => $forms,
							'tagcolors' => $tagcolors,
							'age' => $tag ['age'],
							'tagstatus' => $tag ['tagstatus'],
							'tags_status_in' => $tag ['tags_status_in'],
							'med_mental_health' => $tag ['med_mental_health'],
							'alert_info' => $tag ['alert_info'],
							'prescription' => $tag ['prescription'],
							'restriction_notes' => $tag ['restriction_notes'],
							'room' => $tag ['room'],
							'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
							'sticky_href' => $this->url->link ( 'resident/resident/getstickynote', '' . '&tags_id=' . $tag ['tags_id'], 'SSL' ),
							'tasksinfo' => $tasksinfo1,
							'tagstatus_info' => $status,
							'taskTotal' => $tttaskTotal,
							'recentnote' => $nnotes_description,
							'recenttasks' => $tdescription,
							'ndate_added' => $ndate_added,
							'client_medicine' => $tagmed,
							'screenig_url' => $screenig_url,
							'confirm_alert' => $confirm_alert,
							'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . '&facilities_id=' . $tag ['facilities_id'], 'SSL' ) 
					);
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "records not found" 
				);
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
				
				$this->response->setOutput ( json_encode ( $value ) );
				return;
			}
			
			$this->load->model ( 'form/form' );
			
			$data3 = array ();
			$data3 ['status'] = '1';
			// $data3['order'] = 'sort_order';
			$data3 ['is_parent'] = '1';
			$data3 ['facilities_id'] = $facilities_id;
			$custom_forms = $this->model_form_form->getforms ( $data3 );
			
			$this->data ['custom_forms'] = array ();
			foreach ( $custom_forms as $custom_form ) {
				
				$this->data ['custom_forms'] [] = array (
						'forms_id' => $custom_form ['forms_id'],
						'form_name' => $custom_form ['form_name'],
						'form_href' => $this->url->link ( 'resident/resident/tagform', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
				);
			}
			
			$this->data ['highlighters'] = array ();
			/*
			 * $this->load->model('setting/highlighter');
			 * $this->load->model('notes/image');
			 *
			 * $highlighters = $this->model_setting_highlighter->gethighlighters();
			 *
			 *
			 * foreach ($highlighters as $highlighter) {
			 *
			 * if ($highlighter['highlighter_icon'] && file_exists(DIR_IMAGE . 'highlighter/'.$highlighter['highlighter_icon'])) {
			 * $image = $this->model_notes_image->resize('highlighter/'.$highlighter['highlighter_icon'], 50, 50);
			 * }
			 *
			 * $this->data['highlighters'][] = array(
			 * 'highlighter_id' => $highlighter['highlighter_id'],
			 * 'highlighter_icon' => $image,
			 * 'highlighter_name' => $highlighter['highlighter_name'],
			 * 'highlighter_value' => $highlighter['highlighter_value'],
			 * );
			 * }
			 */
			
			$this->data ['keywords'] = array ();
			
			/*
			 * $this->load->model('setting/keywords');
			 *
			 *
			 *
			 * $data3 = array(
			 * 'facilities_id' => $this->request->post['facilities_id'],
			 * );
			 *
			 * $keywords = $this->model_setting_keywords->getkeywords($data3);
			 *
			 *
			 * foreach ($keywords as $keyword) {
			 * if ($keyword['keyword_image'] && file_exists(DIR_IMAGE . 'icon/'.$keyword['keyword_image'])) {
			 * $image = $this->model_notes_image->resize('icon/'.$keyword['keyword_image'], 35, 35);
			 * }
			 * $this->data['keywords'][] = array(
			 * 'keyword_id' => $keyword['keyword_id'],
			 * 'keyword_name' => $keyword['keyword_name'],
			 * 'keyword_name2' => str_replace(array("\r", "\n"), '', $keyword['keyword_name']),
			 * 'keyword_image' => $keyword['keyword_image'],
			 * 'img_icon' => $image,
			 * );
			 * }
			 */
			
			$datai = array ();
			$datai = array (
					'status' => 1,
					'discharge' => 1,
					// 'role_call' => '1',
					'searchdate' => $currentdate,
					// 'gender2' => $this->request->post['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $facilities_id,
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$intakes_total = $this->model_setting_tags->getTotalTags ( $datai );
			
			$data7 = array ();
			$data7 = array (
					'status' => 1,
					'discharge' => 2,
					'searchdate_2' => $currentdate,
					// 'role_call' => '2',
					'facilities_id' => $facilities_id,
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$dischargetags_total = $this->model_setting_tags->getTotalTags ( $data7 );
			
			$data6 = array ();
			$data6 = array (
					'status' => 1,
					// 'searchdate' => $currentDate,
					'discharge' => 1,
					'role_call' => '2',
					'facilities_id' => $facilities_id,
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$offsitetags_total = $this->model_setting_tags->getTotalTags ( $data6 );
			
			$data3 = array ();
			$data3 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					// 'searchdate' => $currentDate,
					// 'gender2' => $this->request->get['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $facilities_id,
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$inhouse_total = $this->model_setting_tags->getTotalTags ( $data3 );
			
			$data4 = array ();
			$data4 = array (
					'status' => 1,
					'discharge' => 1,
					'date_added' => $currentdate,
					'gender' => '1',
					// 'role_call' => '1',
					'facilities_id' => $facilities_id,
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$males_total = $this->model_setting_tags->getTotalTags ( $data4 );
			
			$data5 = array ();
			$data5 = array (
					'status' => 1,
					'date_added' => $currentdate,
					'discharge' => 1,
					'gender' => '2',
					// 'role_call' => '1',
					'facilities_id' => $facilities_id,
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$females_total = $this->model_setting_tags->getTotalTags ( $data5 );
			
			$data8 = array ();
			$data8 = array (
					'status' => 1,
					'discharge' => 1,
					'date_added' => $currentdate,
					'facilities_id' => $facilities_id,
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$all_total = $this->model_setting_tags->getTotalTags ( $data8 );
			
			$data9 = array ();
			$data9 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'gender' => '1',
					'date_added' => $currentdate,
					'facilities_id' => $facilities_id,
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ihouse_male = $this->model_setting_tags->getTotalTags ( $data9 );
			
			$data10 = array ();
			$data10 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'gender' => '2',
					'date_added' => $currentdate,
					'facilities_id' => $facilities_id,
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ihouse_female = $this->model_setting_tags->getTotalTags ( $data10 );
			
			$data11 = array ();
			$data11 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'date_added' => $currentdate,
					'facilities_id' => $facilities_id,
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ihouse_total = $this->model_setting_tags->getTotalTags ( $data11 );
			
			$data51 = array ();
			$data51 = array (
					'status' => 1,
					'date_added' => $currentDate,
					'discharge' => 1,
					'gender' => '3',
					'role_call' => '1',
					'facilities_id' => $facilities_id,
					'is_master' => '1' 
			);
			
			$non_specific_total = $this->model_setting_tags->getTotalTags ( $data51 );
			
			$datais = array ();
			$datais = array (
					'status' => 1,
					'discharge' => 1,
					'form_type' => CUSTOME_INTAKEID,
					'currentdate' => $currentdate,
					// 'gender2' => $this->request->get['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $facilities_id,
					'is_master' => '1',
					'role_call' => '1' 
			);
			
			$screenings_total = $this->model_form_form->gettotalformstatussc ( $datais );
			
			$data121 = array ();
			$data121 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '2',
					'date_added' => $currentdate,
					'facilities_id' => $facilities_id,
					// 'emp_tag_id_2' => $this->request->post['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ohouse_total = $this->model_setting_tags->getTotalTags ( $data121 );
			
			$this->load->model ( 'notes/clientstatus' );
			$data3s = array ();
			$data3s ['facilities_id'] = $facilities_id;
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3s );
			
			$this->data ['clientstatuss'] = array ();
			foreach ( $customforms as $customform ) {
				
				$this->data ['clientstatuss'] [] = array (
						'tag_status_id' => $customform ['tag_status_id'],
						'name' => $customform ['name'],
						'facilities_id' => $customform ['facilities_id'],
						'display_client' => $customform ['display_client'],
						'disabled_escorted' => $customform ['disabled_escorted'],
						'image' => $customform ['image'],
						'type' => $customform ['type'],
						'status_type' => $customform ['status_type'],
						'is_facility' => $customform ['is_facility'],
						'facility_type' => $customform ['facility_type'] 
				);
			}
			
			$data3sd = array ();
			$data3sd ['facilities_id'] = $facilities_id;
			$customformss = $this->model_notes_clientstatus->getclassifications ( $data3sd );
			
			$this->data ['clientclassifications'] = array ();
			foreach ( $customformss as $customform1 ) {
				
				$this->data ['clientclassifications'] [] = array (
						'tag_classification_id' => $customform1 ['tag_classification_id'],
						'classification_name' => $customform1 ['classification_name'],
						'facilities_id' => $customform1 ['facilities_id'],
						'color_code' => $customform1 ['color_code'] 
				);
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$this->load->model ( 'notes/notes' );
			
			if ($facilityinfo ['config_tags_customlist_id'] != NULL && $facilityinfo ['config_tags_customlist_id'] != "") {
				
				$d = array ();
				$d ['customlist_id'] = $facilityinfo ['config_tags_customlist_id'];
				$customlists = $this->model_notes_notes->getcustomlists ( $d );
				
				if ($customlists) {
					foreach ( $customlists as $customlist ) {
						$d2 = array ();
						$d2 ['customlist_id'] = $customlist ['customlist_id'];
						
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
						
						$this->data ['customlists'] [] = array (
								'customlist_id' => $customlist ['customlist_id'],
								'customlist_name' => $customlist ['customlist_name'],
								'customlistvalues' => $customlistvalues 
						);
					}
				}
			}
			
			$this->data ['facilitiess'] [] = array (
					
					'tags' => $this->data ['tags'],
					'custom_forms' => $this->data ['custom_forms'],
					'highlighters' => $this->data ['highlighters'],
					'keywords' => $this->data ['keywords'],
					
					'shifts' => $this->data ['shifts'],
					
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
					'inclient' => $ihouse_total,
					'ohouse_total' => $ohouse_total,
					'outclient' => $ohouse_total,
					'screenings_total' => $screenings_total,
					'clientstatuss' => $this->data ['clientstatuss'],
					'clientclassifications' => $this->data ['clientclassifications'],
					'customlists' => $this->data ['customlists'],
					'show_client_image' => $this->data ['show_client_image'],
					'show_form_tag' => $this->data ['show_form_tag'],
					'show_task' => $this->data ['show_task'],
					'show_case' => $this->data ['show_case'] 
			);
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask ClientList ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClientList', $activity_data2 );
		}
	}
	public function jsonClientListCommon() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			/*
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$data = array ();
			$data ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$data ['status'] = '1';
			$this->load->model ( 'setting/shift' );
			$this->data ['shifts'] = $this->model_setting_shift->getshifts ( $data );
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'setting/image' );
			
			$this->load->model ( 'notes/clientstatus' );
			
			$currentdate = $this->request->post ['date_added'];
			
			$date = str_replace ( '-', '/', $currentdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			$this->load->model ( 'setting/timezone' );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
			$facilitytimezone = $timezone_info ['timezone_value'];
			
			$datat3 = array ();
			$datat3 = array (
					'status' => 1,
					'discharge' => '1',
					// 'role_call' => '1',
					// 'searchdate' => $currentDate,
					'gender2' => $this->request->post ['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $this->request->post ['facilities_id'],
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$tags = $this->model_setting_tags->getTags ( $datat3 );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/locations' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			$unique_id = $facility ['customer_key'];
			
			// var_dump($unique_id); die;
			
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize ( $customer_info ['client_info_notes'] );
			
			$client_view_options2 = $client_info ["client_view_options"];
			
			// echo '<pre>'; print_r($client_info); echo '</pre>'; die;
			
			// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
			$this->data ['show_client_image'] = $client_info ["show_client_image"];
			$this->data ['show_form_tag'] = $client_info ["show_form_tag"];
			$this->data ['show_task'] = $client_info ["show_task"];
			$this->data ['show_case'] = $client_info ["show_case"];
			
			$this->data ['tags'] = array ();
			
			$this->load->model ( 'form/form' );
			
			$data3 = array ();
			$data3 ['status'] = '1';
			// $data3['order'] = 'sort_order';
			$data3 ['is_parent'] = '1';
			$data3 ['facilities_id'] = $this->request->post ['facilities_id'];
			$custom_forms = $this->model_form_form->getforms ( $data3 );
			
			$this->data ['custom_forms'] = array ();
			foreach ( $custom_forms as $custom_form ) {
				
				$this->data ['custom_forms'] [] = array (
						'forms_id' => $custom_form ['forms_id'],
						'form_name' => $custom_form ['form_name'],
						'form_href' => $this->url->link ( 'resident/resident/tagform', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
				);
			}
			
			$this->data ['highlighters'] = array ();
			/*
			 * $this->load->model('setting/highlighter');
			 * $this->load->model('notes/image');
			 *
			 * $highlighters = $this->model_setting_highlighter->gethighlighters();
			 *
			 *
			 * foreach ($highlighters as $highlighter) {
			 *
			 * if ($highlighter['highlighter_icon'] && file_exists(DIR_IMAGE . 'highlighter/'.$highlighter['highlighter_icon'])) {
			 * $image = $this->model_notes_image->resize('highlighter/'.$highlighter['highlighter_icon'], 50, 50);
			 * }
			 *
			 * $this->data['highlighters'][] = array(
			 * 'highlighter_id' => $highlighter['highlighter_id'],
			 * 'highlighter_icon' => $image,
			 * 'highlighter_name' => $highlighter['highlighter_name'],
			 * 'highlighter_value' => $highlighter['highlighter_value'],
			 * );
			 * }
			 */
			
			$this->data ['keywords'] = array ();
			
			/*
			 * $this->load->model('setting/keywords');
			 *
			 *
			 *
			 * $data3 = array(
			 * 'facilities_id' => $this->request->post['facilities_id'],
			 * );
			 *
			 * $keywords = $this->model_setting_keywords->getkeywords($data3);
			 *
			 *
			 * foreach ($keywords as $keyword) {
			 * if ($keyword['keyword_image'] && file_exists(DIR_IMAGE . 'icon/'.$keyword['keyword_image'])) {
			 * $image = $this->model_notes_image->resize('icon/'.$keyword['keyword_image'], 35, 35);
			 * }
			 * $this->data['keywords'][] = array(
			 * 'keyword_id' => $keyword['keyword_id'],
			 * 'keyword_name' => $keyword['keyword_name'],
			 * 'keyword_name2' => str_replace(array("\r", "\n"), '', $keyword['keyword_name']),
			 * 'keyword_image' => $keyword['keyword_image'],
			 * 'img_icon' => $image,
			 * );
			 * }
			 */
			
			$datai = array ();
			$datai = array (
					'status' => 1,
					'discharge' => 1,
					// 'role_call' => '1',
					'searchdate' => $currentdate,
					// 'gender2' => $this->request->post['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $this->request->post ['facilities_id'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$intakes_total = $this->model_setting_tags->getTotalTags ( $datai );
			
			$data7 = array ();
			$data7 = array (
					'status' => 1,
					'discharge' => 2,
					'searchdate_2' => $currentdate,
					// 'role_call' => '2',
					'facilities_id' => $this->request->post ['facilities_id'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$dischargetags_total = $this->model_setting_tags->getTotalTags ( $data7 );
			
			$data6 = array ();
			$data6 = array (
					'status' => 1,
					// 'searchdate' => $currentDate,
					'discharge' => 1,
					'role_call' => '2',
					'facilities_id' => $this->request->post ['facilities_id'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$offsitetags_total = $this->model_setting_tags->getTotalTags ( $data6 );
			
			$data3 = array ();
			$data3 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					// 'searchdate' => $currentDate,
					// 'gender2' => $this->request->get['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $this->request->post ['facilities_id'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$inhouse_total = $this->model_setting_tags->getTotalTags ( $data3 );
			
			$data4 = array ();
			$data4 = array (
					'status' => 1,
					'discharge' => 1,
					'date_added' => $currentdate,
					'gender' => '1',
					// 'role_call' => '1',
					'facilities_id' => $this->request->post ['facilities_id'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$males_total = $this->model_setting_tags->getTotalTags ( $data4 );
			
			$data5 = array ();
			$data5 = array (
					'status' => 1,
					'date_added' => $currentdate,
					'discharge' => 1,
					'gender' => '2',
					// 'role_call' => '1',
					'facilities_id' => $this->request->post ['facilities_id'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$females_total = $this->model_setting_tags->getTotalTags ( $data5 );
			
			$data8 = array ();
			$data8 = array (
					'status' => 1,
					'discharge' => 1,
					'date_added' => $currentdate,
					'facilities_id' => $this->request->post ['facilities_id'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$all_total = $this->model_setting_tags->getTotalTags ( $data8 );
			
			$data9 = array ();
			$data9 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'gender' => '1',
					'date_added' => $currentdate,
					'facilities_id' => $this->request->post ['facilities_id'],
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ihouse_male = $this->model_setting_tags->getTotalTags ( $data9 );
			
			$data10 = array ();
			$data10 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'gender' => '2',
					'date_added' => $currentdate,
					'facilities_id' => $this->request->post ['facilities_id'],
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ihouse_female = $this->model_setting_tags->getTotalTags ( $data10 );
			
			$data11 = array ();
			$data11 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'date_added' => $currentdate,
					'facilities_id' => $this->request->post ['facilities_id'],
					'emp_tag_id_2' => $this->request->post ['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ihouse_total = $this->model_setting_tags->getTotalTags ( $data11 );
			
			$data51 = array ();
			$data51 = array (
					'status' => 1,
					'date_added' => $currentDate,
					'discharge' => 1,
					'gender' => '3',
					'role_call' => '1',
					'facilities_id' => $this->request->post ['facilities_id'],
					'is_master' => '1' 
			);
			
			$non_specific_total = $this->model_setting_tags->getTotalTags ( $data51 );
			
			$datais = array ();
			$datais = array (
					'status' => 1,
					'discharge' => 1,
					'form_type' => CUSTOME_INTAKEID,
					'currentdate' => $currentdate,
					// 'gender2' => $this->request->get['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $this->request->post ['facilities_id'],
					'is_master' => '1',
					'role_call' => '1' 
			);
			
			$screenings_total = $this->model_form_form->gettotalformstatussc ( $datais );
			
			$data121 = array ();
			$data121 = array (
					'status' => 1,
					'discharge' => 1,
					'role_call' => '2',
					'date_added' => $currentdate,
					'facilities_id' => $this->request->post ['facilities_id'],
					// 'emp_tag_id_2' => $this->request->post['search_tags'],
					// 'wait_list' => $this->request->post['wait_list'],
					'all_record' => '1',
					'is_master' => '1' 
			);
			
			$ohouse_total = $this->model_setting_tags->getTotalTags ( $data121 );
			
			$this->load->model ( 'notes/clientstatus' );
			$data3s = array ();
			$data3s ['facilities_id'] = $this->request->post ['facilities_id'];
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3s );
			
			$this->data ['clientstatuss'] = array ();
			foreach ( $customforms as $customform ) {
				
				$rule_action_content = unserialize($customform['rule_action_content']);
				$out_from_cell = 0;
				if ($rule_action_content['out_from_cell'] == "1") {
					$out_from_cell = $rule_action_content['out_from_cell'];
				}
				
				$parent_ids = array();
				$parentids = unserialize($customform['parent_ids']);
				foreach($parentids as $parentid){
					$parent_ids[]['name'] = $parentid['name'];
				}
				
				
				if ($rule_action_content ['forms_id'] != '' && $rule_action_content ['forms_id'] != null) {
					$href = $this->url->link ( 'services/form', '' . '&forms_design_id=' . $rule_action_content ['forms_id'] . '&tag_status_id=' . $sclient_status ['tag_status_id'] . $url2, 'SSL' );
				} else {
					$href = "";
				}
				
					
				$this->data ['clientstatuss'] [] = array (
						'tag_status_id' => $customform ['tag_status_id'],
						'name' => $customform ['name'],
						'facilities_id' => $customform ['facilities_id'],
						'display_client' => $customform ['display_client'],
						'disabled_escorted' => $customform ['disabled_escorted'],
						'image' => $customform ['image'],
						'type' => $customform ['type'],
						'status_type' => $customform ['status_type'],
						'is_facility' => $customform ['is_facility'],
						'out_from_cell' => $out_from_cell,
						'facility_type' => $customform ['facility_type'] ,
						
						'parent_ids' =>  $parent_ids,
						'rule_action_content' =>  $rule_action_content,
						'formhref' => $href 
						
				);
			}
			
			$data3sd = array ();
			$data3sd ['facilities_id'] = $this->request->post ['facilities_id'];
			$customformss = $this->model_notes_clientstatus->getclassifications ( $data3sd );
			
			$this->data ['clientclassifications'] = array ();
			foreach ( $customformss as $customform1 ) {
				
				$this->data ['clientclassifications'] [] = array (
						'tag_classification_id' => $customform1 ['tag_classification_id'],
						'classification_name' => $customform1 ['classification_name'],
						'facilities_id' => $customform1 ['facilities_id'],
						'color_code' => $customform1 ['color_code'] 
				);
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			$this->load->model ( 'notes/notes' );
			
			if ($facilityinfo ['config_tags_customlist_id'] != NULL && $facilityinfo ['config_tags_customlist_id'] != "") {
				
				$d = array ();
				$d ['customlist_id'] = $facilityinfo ['config_tags_customlist_id'];
				$customlists = $this->model_notes_notes->getcustomlists ( $d );
				
				if ($customlists) {
					foreach ( $customlists as $customlist ) {
						$d2 = array ();
						$d2 ['customlist_id'] = $customlist ['customlist_id'];
						
						$customlistvalues = $this->model_notes_notes->getcustomlistvaluesReplica ( $d2 );
						
						$this->data ['customlists'] [] = array (
								'customlist_name' => $customlist ['customlist_name'],
								'customlistvalues' => $customlistvalues 
						);
					}
				}
			}
			
			$this->data ['facilitiess'] [] = array (
					'custom_forms' => $this->data ['custom_forms'],
					'highlighters' => $this->data ['highlighters'],
					'keywords' => $this->data ['keywords'],
					'shifts' => $this->data ['shifts'],
					'clientstatuss' => $this->data ['clientstatuss'],
					'clientclassifications' => $this->data ['clientclassifications'],
					'customlists' => $this->data ['customlists'] 
			);
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask ClientList ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClientList', $activity_data2 );
		}
	}
	
	public function jsonClientListTags() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			/*
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$data = array ();
			$data ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$data ['status'] = '1';
			$this->load->model ( 'setting/shift' );
			$this->data ['shifts'] = $this->model_setting_shift->getshifts ( $data );
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'setting/image' );
			
			$this->load->model ( 'notes/clientstatus' );
			
			$currentdate = $this->request->post ['date_added'];
			
			$date = str_replace ( '-', '/', $currentdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			$this->load->model ( 'setting/timezone' );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
			$facilitytimezone = $timezone_info ['timezone_value'];
			
			date_default_timezone_set ( $facilitytimezone );
			$current_date_user = date ( 'Y-m-d' );
			
			//$config_admin_limit = $this->config->get ( 'all_sync_pagination' );
			
			
			if ($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != "") {
				$config_admin_limit = $this->config->get ( 'all_sync_pagination' );
			} else {
				$config_admin_limit = "50";
			}
			
			if (isset ( $this->request->post ['page'] )) {
				$page = $this->request->post ['page'];
			} else {
				$page = 1;
			}
			
			if ($this->request->post ['date_added'] != null && $this->request->post ['date_added'] != "") {
				$discharge = '0';
			} else {
				$discharge = '1';
			}
			if ($this->request->post ['is_client_screen'] == "1") {
				$is_client_screen = '1';
			} else {
				$is_client_screen = '';
			}
			if ($facilities_info ['enable_facilityinout'] == 1) {
				$enable_facilityinout = 1;
			} else {
				$enable_facilityinout = 0;
			}
			
				
				
			
			
			$currentdate2 = date ( 'd-m-Y' );
			$top = '0';
			$this->load->model ( 'createtask/createtask' );
			//$listtasks = $this->model_createtask_createtask->getTasklist ( $this->request->post['facilities_id'] , $currentdate2, $top, '');
			
			
		
			$tagids = array();
			foreach($listtasks as $listtask){
				if($listtask['emp_tag_id'] != ""){
					$tagids[] = $listtask['emp_tag_id'];
				}
				if($listtask['tags_id'] > 0){
					$tagids[] = $listtask['tags_id'];
				}
				if($listtask['transport_tags'] != ""){
					$tagids[] = $listtask['transport_tags'];
				}
				if($listtask['medication_tags'] != ""){
					$tagids[] = $listtask['medication_tags'];
				}
			}
			
			if($this->request->post['date_added'] != null && $this->request->post['date_added'] != ""){
				$currentdate = $this->request->post['date_added'];
				
				$date = str_replace('-', '/', $currentdate);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[1]."-".$res[0];
			}else{
				$currentdate = date('Y-m-d 00:00:00');
			}
			
		 //var_dump(config_case_limit); 
		 
			if($this->request->post['sort'] != null && $this->request->post['sort'] != ""){
				$sort = $this->request->post['sort'];
			} else {
				$sort = 'emp_first_name';
			}
			
			
			$config_case_limit = $this->config->get('config_case_limit');
			
			$datat3 = array();
			$datat3 = array(
				'status' => 1,
				'discharge' => $discharge,
				'tagids' => $tagids,
				'app_user_date' => $currentdate,
				'current_date_user' => $current_date_user,
				'is_client_screen' => $is_client_screen,
				'gender2' => $this->request->post['gender'],
				'sort' => $sort,
				'facilities_id' => $this->request->post['facilities_id'],
				'emp_tag_id_2' => $this->request->post['search_tags'],
				'updatedtagsids' => $this->request->post['updatedtagsids'],
				'enable_facilityinout' => $enable_facilityinout,
				//'wait_list' => $this->request->post['wait_list'],
				//'all_record' => '1',
				'is_master' => '1',
				'is_submaster' => '1',
				'start' => ($page - 1) * $config_case_limit,
               'limit' => $config_case_limit
               //'limit' => 9
			);
			
			
			$all_total = $this->model_setting_tags->getTotalTags ( $datat3 );
			$tags = $this->model_setting_tags->getTags ( $datat3 );
			
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/locations' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			$unique_id = $facility ['customer_key'];
			
			 //var_dump($unique_id); die;
			
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize ( $customer_info ['client_info_notes'] );
			
			if (! empty ( $customer_info ['setting_data'] )) {
				$customers = unserialize ( $customer_info ['setting_data'] );
			}
			
			$client_view_options2 = $client_info ["client_view_options"];
			$client_view_options_details = $client_info ["client_details_view_options"];
			
			// echo '<pre>'; print_r($client_info); echo '</pre>'; die;
			
			// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
			$this->data ['show_client_image'] = $client_info ["show_client_image"];
			$this->data ['show_form_tag'] = $client_info ["show_form_tag"];
			$this->data ['show_task'] = $client_info ["show_task"];
			$this->data ['show_case'] = $client_info ["show_case"];
			
			
			if($customers['date_format'] != null && $customers['date_format'] != ""){
				$date_format = $customers['date_format'];
			}else{
				$date_format = $this->language->get ( 'date_format_short_2' );
			}
			
			if($customers['time_format'] != null && $customers['time_format'] != ""){
				$time_format = $customers['time_format'];
			}else{
				$time_format = 'h:i A';
			}
			
			$this->data ['tags'] = array ();
			if (! empty ( $tags )) {
				foreach ( $tags as $tag ) {
					
					$client_view_options = $client_view_options2;
					$client_view_options_details2 = $client_view_options_details;
					
					if (isset ( $tag ['emp_first_name'] ) && $tag ['emp_first_name'] != '') {
						$client_view_options = str_replace ( '[emp_first_name]', $tag ['emp_first_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_first_name]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_first_name'] ) && $tag ['emp_first_name'] != '') {
						$client_view_options_details2 = str_replace ( '[emp_first_name]', $tag ['emp_first_name'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[emp_first_name]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['emp_middle_name'] ) && $tag ['emp_middle_name'] != '') {
						$client_view_options = str_replace ( '[emp_middle_name]', $tag ['emp_middle_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_middle_name]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_middle_name'] ) && $tag ['emp_middle_name'] != '') {
						$client_view_options_details2 = str_replace ( '[emp_middle_name]', $tag ['emp_middle_name'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[emp_middle_name]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['emp_last_name'] ) && $tag ['emp_last_name'] != '') {
						$client_view_options = str_replace ( '[emp_last_name]', $tag ['emp_last_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_last_name]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_last_name'] ) && $tag ['emp_last_name'] != '') {
						$client_view_options_details2 = str_replace ( '[emp_last_name]', $tag ['emp_last_name'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[emp_last_name]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['emergency_contact'] ) && $tag ['emergency_contact'] != '') {
						$client_view_options = str_replace ( '[emergency_contact]', $tag ['emergency_contact'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emergency_contact]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emergency_contact'] ) && $tag ['emergency_contact'] != '') {
						$client_view_options_details2 = str_replace ( '[emergency_contact]', $tag ['emergency_contact'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[emergency_contact]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['facilities_id'] ) && $tag ['facilities_id'] != '') {
						
						$client_view_options = str_replace ( '[facilities_id]', $tag ['facility'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[facilities_id]', '', $client_view_options );
					}
					
					if (isset ( $tag ['facilities_id'] ) && $tag ['facilities_id'] != '') {
						
						$client_view_options_details2 = str_replace ( '[facilities_id]', $tag ['facility'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[facilities_id]', '', $client_view_options_details2 );
					}
					
					
					
					if (isset ( $tag ['room'] ) && $tag ['room'] != '') {
						
						$client_view_options = str_replace ( '[room]', $tag ['location_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[room]', '', $client_view_options );
					}
					
					if (isset ( $tag ['room'] ) && $tag ['room'] != '') {
						
						$client_view_options_details2 = str_replace ( '[room]', $tag ['location_name'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[room]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['dob'] ) && $tag ['dob'] != '') {
						$client_view_options = str_replace ( '[dob]', $tag ['dob'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[dob]', '', $client_view_options );
					}
					
					if (isset ( $tag ['dob'] ) && $tag ['dob'] != '') {
						$client_view_options_details2 = str_replace ( '[dob]', $tag ['dob'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[dob]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['gender'] ) && $tag ['gender'] != '') {
						$client_view_options = str_replace ( '[gender]', $tag ['customlistvalues_name'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[gender]', '', $client_view_options );
					}
					
					if (isset ( $tag ['gender'] ) && $tag ['gender'] != '') {
						$client_view_options_details2 = str_replace ( '[gender]', $tag ['customlistvalues_name'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[gender]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['age'] ) && $tag ['age'] != '') {
						$client_view_options = str_replace ( '[age]', $tag ['age'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[age]', '', $client_view_options );
					}
					
					if (isset ( $tag ['age'] ) && $tag ['age'] != '') {
						$client_view_options_details2 = str_replace ( '[age]', $tag ['age'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[age]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['ssn'] ) && $tag ['ssn'] != NULL) {
						$client_view_options = str_replace ( '[ssn]', $tag ['ssn'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[ssn]', '', $client_view_options );
					}
					
					if (isset ( $tag ['ssn'] ) && $tag ['ssn'] != NULL) {
						$client_view_options_details2 = str_replace ( '[ssn]', $tag ['ssn'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[ssn]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['emp_tag_id'] ) && $tag ['emp_tag_id'] != '') {
						$client_view_options = str_replace ( '[emp_tag_id]', $tag ['emp_tag_id'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_tag_id]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_tag_id'] ) && $tag ['emp_tag_id'] != '') {
						$client_view_options_details2 = str_replace ( '[emp_tag_id]', $tag ['emp_tag_id'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[emp_tag_id]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['emp_extid'] ) && $tag ['emp_extid'] != '') {
						$client_view_options = str_replace ( '[emp_extid]', $tag ['emp_extid'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[emp_extid]', '', $client_view_options );
					}
					
					if (isset ( $tag ['emp_extid'] ) && $tag ['emp_extid'] != '') {
						$client_view_options_details2 = str_replace ( '[emp_extid]', $tag ['emp_extid'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[emp_extid]', '', $client_view_options_details2 );
					}
					
					if (isset ( $tag ['ccn'] ) && $tag ['ccn'] != '') {
						$client_view_options = str_replace ( '[ccn]', $tag ['ccn'], $client_view_options );
					} else {
						$client_view_options = str_replace ( '[ccn]', '', $client_view_options );
					}
					
					if (isset ( $tag ['ccn'] ) && $tag ['ccn'] != '') {
						$client_view_options_details2 = str_replace ( '[ccn]', $tag ['ccn'], $client_view_options_details2 );
					} else {
						$client_view_options_details2 = str_replace ( '[ccn]', '', $client_view_options_details2 );
					}
					
					if ($client_view_options != "" && $client_view_options != null) {
						$client_view_options_flag = nl2br ( $client_view_options );
					} else {
						$client_view_options_flag = "";
					}
					
					if ($client_view_options_details2 != "" && $client_view_options_details2 != null) {
						$client_view_options_details2_flag = nl2br ( $client_view_options_details2 );
					} else {
						$client_view_options_details2_flag = "";
					}
					
					$tagmedicationData = array();
					$tagmedicationData = $this->model_setting_tags->getTagsMedicationdetails ( $tag ['tags_id'] );
					
					$classification_names1 = '';
					$classification_names2 = array();
					$classification_ids = array();		
					if ($tag ['tags_id'] != '0' && $tag ['tags_id'] != null) {
				
						// $status_value = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );

						if($tag ['classification_id']!="" && $tag ['classification_id']!=null){

							$tag_classification_id=$tag ['classification_id'];
						
							$tag_classification_ids=explode(",",$tag_classification_id);

							foreach($tag_classification_ids as $classification_id){

								$classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
								if($classification_value['classification_name'] != null && $classification_value['classification_name'] != ""){
									$classification_ids [] = $classification_value['tag_classification_id'];
								
									$classification_names2[] = $classification_value['classification_name'];
								}
							}
							
							
							$classification_names1 = implode ( ',', $classification_names2);

						}
						
					}
					
					$status_name = array();
					if($tag['tag_status_ids']!=''){
						$substatus_ids_arr = explode(',',$tag['tag_status_ids']);
						foreach($substatus_ids_arr AS $val){
							$status_name[] = $val;
						}
						
						$substatus= implode(' | ',$status_name);
					}else{
						$substatus='';
					}
					
					$ruleaction_content = unserialize ( $tag ['rule_action_content'] );
					
					$notesmedicationtimes = array ();
			
					$statusimages = array ();
					
					$hourout1 = "";
					$ercent1 = "";
					
					
					$out_from_cell = $tag['out_from_cell'];
					if ($ruleaction_content ['out_from_cell'] == 1) {
						if ($ruleaction_content ['out_the_sell'] != null && $ruleaction_content ['out_the_sell'] != "") {
							$rules_operation = $ruleaction_content ['rules_operation'];
							$rules_start_time = $ruleaction_content ['rules_start_time'];
							$rules_end_time = $ruleaction_content ['rules_end_time'];
							$out_the_sell = $ruleaction_content ['out_the_sell'];
							$duration_type = $ruleaction_content ['duration_type'];
							
							$out_the_sell_reminder = $ruleaction_content ['out_the_sell_reminder'];
							$reminder_duration_type = $ruleaction_content ['reminder_duration_type'];
							
							$red_progress_percentage = $ruleaction_content ['red_progress_percentage'];
							$red_color = $ruleaction_content ['red_color'];
							$orange_color = $ruleaction_content ['orange_color'];
							$green_color = $ruleaction_content ['green_color'];
							$orange_progress_percentage = $ruleaction_content ['orange_progress_percentage'];
							$green_progress_percentage = $ruleaction_content ['green_progress_percentage'];
						} else {
							$rules_operation = $customers ['rules_operation'];
							$rules_start_time = $customers ['rules_start_time'];
							$rules_end_time = $customers ['rules_end_time'];
							$out_the_sell = $customers ['out_the_sell'];
							$duration_type = $customers ['duration_type'];
							
							$out_the_sell_reminder = $customers ['out_the_sell_reminder'];
							$reminder_duration_type = $customers ['reminder_duration_type'];
							
							$red_progress_percentage = $customers ['red_progress_percentage'];
							$red_color = $customers ['red_color'];
							$orange_color = $customers ['orange_color'];
							$green_color = $customers ['green_color'];
							$orange_progress_percentage = $customers ['orange_progress_percentage'];
							$green_progress_percentage = $customers ['green_progress_percentage'];
						}
						
						$houroutdata = array ();
						$houroutdata ['tags_id'] = $tag ['tags_id'];
						$houroutdata ['tags_type'] = $tag ['type'];
						$houroutdata ['role_call'] = $tag ['role_call'];
						$houroutdata ['facilities_id'] = $tag ['facilities_id'];
						$houroutdata ['date_a'] = date ( 'Y-m-d H:i:s' );
						$houroutdata ['currentdate'] = date ( 'Y-m-d' );
						$houroutdata ['rules_operation'] = $rules_operation;
						$houroutdata ['rules_start_time'] = $rules_start_time;
						$houroutdata ['rules_end_time'] = $rules_end_time;
						$houroutdata ['out_the_sell'] = $out_the_sell;
						$houroutdata ['duration_type'] = $duration_type;
						$houroutdata ['out_the_sell_reminder'] = $out_the_sell_reminder;
						$houroutdata ['reminder_duration_type'] = $reminder_duration_type;
						$notesmedicationtimes1 = $this->model_setting_tags->gettagsTimes ( $houroutdata );
						
						foreach ( $notesmedicationtimes1 as $notesmedicationtime ) {
							
							$hourout1 = $hourout1 + $notesmedicationtime ['status_total_time'];
							//$ercent1 = $ercent1 + $notesmedicationtime ['inPercent'];
							if($notesmedicationtime ['image'] != null && $notesmedicationtime ['image'] != ""){
								$statusimages [] = array('image'=>$notesmedicationtime ['image']);
							}
							$notesmedicationtimes [] = array (
									'name' => $notesmedicationtime ['name'],
									'outcelltimtime' => $notesmedicationtime ['outcelltimtime'],
									//'inPercent' => $notesmedicationtime ['inPercent'],
									'status_total_time' => $notesmedicationtime ['status_total_time'],
									
									'red_progress_percentage' => $red_progress_percentage,
									'red_color' => $red_color,
									'orange_color' => $orange_color,
									'orange_progress_percentage' => $orange_progress_percentage,
									'green_color' => $green_color,
									'green_progress_percentage' => $green_progress_percentage 
							);
						}
					}else{
						$houroutdata = array ();
						$houroutdata ['tags_id'] = $tag ['tags_id'];
						$houroutdata ['tags_type'] = $tag ['type'];
						$houroutdata ['role_call'] = $tag ['role_call'];
						$houroutdata ['type'] = 1;
						$houroutdata ['facilities_id'] = $tag ['facilities_id'];
						$houroutdata ['date_a'] = date ( 'Y-m-d H:i:s' );
						$houroutdata ['currentdate'] = date ( 'Y-m-d' );
						$houroutdata ['rules_operation'] = $rules_operation;
						$houroutdata ['rules_start_time'] = $rules_start_time;
						$houroutdata ['rules_end_time'] = $rules_end_time;
						$houroutdata ['out_the_sell'] = $out_the_sell;
						$houroutdata ['duration_type'] = $duration_type;
						$houroutdata ['out_the_sell_reminder'] = $out_the_sell_reminder;
						$houroutdata ['reminder_duration_type'] = $reminder_duration_type;
						$notesmedicationtimes1 = $this->model_setting_tags->gettagsTimes ( $houroutdata );
						
						
						foreach ( $notesmedicationtimes1 as $notesmedicationtime ) {
							
							$hourout1 = $hourout1 + $notesmedicationtime ['status_total_time'];
							//$ercent1 = $ercent1 + $notesmedicationtime ['inPercent'];
							
							if($notesmedicationtime ['image'] != null && $notesmedicationtime ['image'] != ""){
								$statusimages [] = array('image'=>$notesmedicationtime ['image']);
							}
							
							$notesmedicationtimes [] = array (
									'name' => $notesmedicationtime ['name'],
									'outcelltimtime' => $notesmedicationtime ['outcelltimtime'],
									//'inPercent' => $notesmedicationtime ['inPercent'],
									'status_total_time' => $notesmedicationtime ['status_total_time'],
									
									'red_progress_percentage' => $red_progress_percentage,
									'red_color' => $red_color,
									'orange_color' => $orange_color,
									'orange_progress_percentage' => $orange_progress_percentage,
									'green_color' => $green_color,
									'green_progress_percentage' => $green_progress_percentage 
							);
						}
					}
					
					$hourout = $this->secondsToTime ( $hourout1 * 60 );
					$percent = $ercent1;
					
					$fixstatusname = "";
					$fixstatusform = "";
					$fixstatusformhref = "";
					$fixstatusformdate = "";
					if ($tag ['fixed_status_id'] > 0) {
						$fixclientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag ['fixed_status_id'] );
						$ruleaction_ontent = unserialize ( $fixclientstatus_info ['rule_action_content'] );
						$fixstatusname = $fixclientstatus_info ['name'];
						$statusimages [] = $fixclientstatus_info ['image'];
						if ($ruleaction_ontent ['forms_id'] > 0) {
							$fixstatusform = $ruleaction_ontent ['forms_id'];
							
							$houroutdata1 = array ();
							$houroutdata1 ['tags_id'] = $tag ['tags_id'];
							$houroutdata1 ['facilities_id'] = $tag ['facilities_id'];
							$houroutdata1 ['fixed_status_id'] = $tag ['fixed_status_id'];
							$houroutdata1 ['date_a'] = date ( 'Y-m-d H:i:s' );
							$houroutdata1 ['currentdate'] = date ( 'Y-m-d' );
							$fixstatusinfo = $this->model_setting_tags->getOutToTimebyid ( $houroutdata1 );
							
							
							$forminfo = $this->model_form_form->getFormDatabynotesid ($ruleaction_ontent ['forms_id'], $fixstatusinfo['notes_id']);
							$fixstatusformdate = date ( $date_format, strtotime ( $forminfo['date_added'] ) );
							
							$fixstatusformhref = $this->url->link ( 'form/form', '' . '&forms_design_id=' . $ruleaction_ontent ['forms_id'] . '&forms_id=' . $forminfo['forms_id'] . '&notes_id=' . $fixstatusinfo['notes_id'] . $url2, 'SSL' );
						}
					}
					
					if($tag ['enroll_image'] != null && $tag ['enroll_image'] != ""){
						$enroll_image = $tag ['enroll_image'];
					}else{
						$enroll_image = "";
					}
					
					
					
					$this->data ['tags'] [] = array (
							'facilityname' => $tag ['facility'],
							'fixed_status_id' => $tag ['fixed_status_id'],
							'fixstatusformdate' => $fixstatusformdate,
							'fixstatusname' => $fixstatusname,
							'fixstatusform' => $fixstatusform,
							'fixstatusformhref' => $fixstatusformhref,
							'statusimages' => $statusimages,
							'comments' => $tag ['comments'],
							
							'notesmedicationtimes' => $notesmedicationtimes,
							'substatus' => $substatus,
							'percent' => $percent,
							'hourout' => $hourout,
							
							
							'out_from_cell' => $out_from_cell,
							'hourout_in_words' => $hourout,
							'hourout_in_percent' => $percent,
							
							'client_view_options' => $client_view_options_flag,
							'client_view_options_details' => $client_view_options_details2_flag,
							
							'tag_classification_id' => $tag ['classification_id'],
							'classification_name' => $classification_names1,
							'client_clssification_color' => '',
							
							'facilities_id' => $tag ['facilities_id'],
							'is_movement' => $tag ['is_movement'],
							'emp_first_name' => $tag ['emp_first_name'],
							'emp_middle_name' => $tag ['emp_middle_name'] ? $tag ['emp_middle_name'] : "",
							'emp_first_name' => $tag ['emp_first_name'] ? $tag ['emp_first_name'] : "",
							'emp_last_name' => $tag ['emp_last_name'] ? $tag ['emp_last_name'] : "",
							'name' => $tag ['emp_last_name'] .' '. $tag ['emp_first_name'],
							'ssn' => $tag ['ssn'],
							'ccn' => $tag ['ccn'],
							'dob' => $tag ['dob'],
							'age' => $tag ['age'],
							'facility_inout' => $tag ['facility_inout'],
							'facility_move_id' => $tag ['facility_move_id'],
							'emp_tag_id' => $tag ['emp_tag_id'] ? $tag ['emp_tag_id'] : "",
							'tags_id' => $tag ['tags_id'],
							// 'is_recent' => $tag['is_recent'],
							'medication_inout' => $tag ['medication_inout'],
							'emp_extid' => $tag ['emp_extid'] ? $tag ['emp_extid'] : "",
							'gender' => $tag['customlistvalues_name'],
							'tags_status_in' => $tag ['tags_status_in'] ? $tag ['tags_status_in'] : "",
							'discharge' => $tag ['discharge'],
							'customlistvalues_id' => $tag ['customlistvalues_id'],
							'upload_file_thumb_1' => $enroll_image,
							'upload_file' => $enroll_image,
							'privacy' => $tag ['privacy'],
							'stickynote' => $tag ['stickynote'] ? $tag ['stickynote'] : "",
							'role_call' => $tag ['role_call'],
							'color_code' => $tag['color_code'],
							'role_type' => $tag['type'],
							'role_callname' => $tag ['name'],
							'role_image' => $tag ['image'],
							
							'room' => $tag ['room'] ? $tag ['room'] : "",
							'location_name' => $tag ['location_name'],
							'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
							'modify_date' => date ( 'm-d-Y', strtotime ( $tag ['modify_date'] ) ),
							'sticky_href' => $this->url->link ( 'resident/resident/getstickynote', '' . '&tags_id=' . $tag ['tags_id'], 'SSL' ),
							'tagstatus_info' => $tag ['classification_id'],
							'client_medicine' => 1,
							'tagmedicationData' => $tagmedicationData,
							
							'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . '&facilities_id=' . $tag ['facilities_id'], 'SSL' ) 
					);
				}
				
			
			} else {
				$this->data ['facilitiess'] [] = array ();
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
				
				$this->response->setOutput ( json_encode ( $value ) );
				return;
			}
			
			
			$this->data ['keywords'] = array ();
			
			
			$this->data ['facilitiess'] [] = array (
					'tags' => $this->data ['tags'],
					'intakes_total' => 0,
					'discharge_total' => 0,
					'offsite_total' => 0,
					'inhouse_total' => 0,
					'maletags_total' => 0,
					'femaletags_total' => 0,
					'non_specific_total' => 0,
					'all_total' => $all_total,
					'ihouse_male' => 0,
					'ihouse_female' => 0,
					'ihouse_total' => 0,
					'inclient' => 0,
					'ohouse_total' => 0,
					'outclient' => 0,
					'screenings_total' => 0,
					'show_client_image' => $this->data ['show_client_image'],
					'show_form_tag' => $this->data ['show_form_tag'],
					'show_task' => $this->data ['show_task'],
					'show_case' => $this->data ['show_case'] 
			);
			
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			
			
			$activity_data2 = array (
					'data' => 'Error in apptask ClientList ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClientList', $activity_data2 );
		}
	}
	public function jsonAddCensus() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonAddCensus', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$json = array ();
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				$data = array ();
				
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'form/form' );
				
				$this->load->model ( 'notes/notes' );
				
				$timezone_name = $this->request->post ['facilitytimezone'];
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
					$data ['imgOutput'] = $this->request->post ['signature'];
				}
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				
				$data ['notetime'] = $notetime;
				$data ['note_date'] = $date_added;
				$data ['facilitytimezone'] = $timezone_name;
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$comments = ' | ' . $this->request->post ['comments'];
				}
				
				$data ['notes_description'] = 'Daily Census has been added' . $comments;
				
				$data ['date_added'] = $date_added;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
					$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
					
					if (! empty ( $exist_note_info )) {
						$notes_id = $exist_note_info ['notes_id'];
						$device_unique_id = $exist_note_info ['device_unique_id'];
					} else {
						
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
				} else {
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
					$device_unique_id = $this->request->post ['device_unique_id'];
				}
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->load->model ( 'setting/tags' );
				$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$this->model_setting_tags->addCensus ( $this->request->post, $notes_id, $date_added, $this->request->post ['facilities_id'], $timezone_name );
				
				$this->model_notes_notes->updatetagscences ( $notes_id );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addcencus jsonAddCensus ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_addcencus', $activity_data2 );
		}
	}
	public function addclient() {
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm ()) {
			
			$this->load->model ( 'setting/tags' );
			
			$emp_extid = preg_replace ( '/\s+/', '', $this->request->post ['emp_extid'] );
			$ssn = preg_replace ( '/\s+/', '', $this->request->post ['ssn'] );
			
			$month_1 = $this->request->post ['month_1'];
			$day_1 = $this->request->post ['day_1'];
			$year_1 = $this->request->post ['year_1'];
			
			$dob111 = $month_1 . '-' . $day_1 . '-' . $year_1;
			$date = str_replace ( '-', '/', $dob111 );
			$res = explode ( "/", $date );
			$createdatess1 = $res [2] . "-" . $res [0] . "-" . $res [1];
			$dob = date ( 'Y-m-d', strtotime ( $createdatess1 ) );
			
			$existclient = array ();
			$existclient ['emp_extid'] = $emp_extid;
			$existclient ['ssn'] = $ssn;
			$existclient ['dob'] = $dob;
			$existclient ['emp_first_name'] = $this->request->post ['emp_first_name'];
			$existclient ['emp_last_name'] = $this->request->post ['emp_last_name'];
			
			$tag_exist_info = $this->model_setting_tags->getTagsbyAllNamedischage ( $existclient );
			
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				
				$this->model_setting_tags->updatexittag ( $this->request->post, $this->request->get ['facilities_id'] );
				
				$this->model_setting_tags->editTags ( $this->request->post ['emp_tag_id'], $this->request->post, $this->request->get ['facilities_id'] );
				
				$tags_id = $this->request->post ['emp_tag_id'];
			} else {
				if ($tag_exist_info ['tags_id'] != null && $tag_exist_info ['tags_id'] != "") {
					$this->model_setting_tags->updatexittag ( $this->request->post, $this->request->get ['facilities_id'] );
					
					$this->model_setting_tags->editTags ( $tag_exist_info ['tags_id'], $this->request->post, $this->request->get ['facilities_id'] );
					
					$tags_id = $tag_exist_info ['tags_id'];
				} else {
					$tags_id = $this->model_setting_tags->addTags ( $this->request->post, $this->request->get ['facilities_id'] );
				}
			}
			
			$url2 = "";
			$url2 .= '&tags_id=' . $tags_id;
			$url2 .= '&new_form=2';
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
			}
			
			$this->redirect ( $this->url->link ( 'services/resident/jsoncustomsForm', '' . $url2, 'SSL' ) );
		}
		
		$this->getForm ();
	}
	public function updateclient() {
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm ()) {
			
			$this->load->model ( 'setting/tags' );
			
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			
			if ($tag_info ['tags_status_in'] == $this->request->post ['tags_status_in']) {
				$tags_status_in_change = '1';
			} else {
				$tags_status_in_change = '2';
			}
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $this->request->get ['tags_id'];
			$tdata ['facilities_id'] = $this->request->get ['facilities_id'];
			$tdata ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$tdata ['type'] = 'updateclient';
			$archive_tags_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			/*
			 * $archive_tags_id = $this->model_setting_tags->editTags($this->request->get['tags_id'], $this->request->post, $this->request->get['facilities_id']);
			 */
			
			$url2 = "";
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$url2 .= '&new_form=1';
			
			$url2 .= '&tags_status_in_change=' . $tags_status_in_change;
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
			}
			
			$url2 .= '&archive_tags_id=' . $archive_tags_id;
			
			$this->redirect ( $this->url->link ( 'services/resident/jsoncustomsForm', '' . $url2, 'SSL' ) );
		}
		
		$this->getForm ();
	}
	protected function validateForm() {
		$emp_first_name = preg_replace ( '/\s+/', '', $this->request->post ['emp_first_name'] );
		
		if ($emp_first_name == "" && $emp_first_name == null) {
			$this->error ['emp_first_name'] = "This is required field!";
		}
		
		$emp_last_name = preg_replace ( '/\s+/', '', $this->request->post ['emp_last_name'] );
		
		if ($emp_last_name == "" && $emp_last_name == null) {
			$this->error ['emp_last_name'] = "This is required field!";
		}
		
		/*
		 * if ($this->request->post['location_address'] == "") {
		 * $this->error['location_address'] = "This is required field!";
		 * }
		 */
		
		$emp_extid = preg_replace ( '/\s+/', '', $this->request->post ['emp_extid'] );
		$ssn = preg_replace ( '/\s+/', '', $this->request->post ['ssn'] );
		
		$month_1 = $this->request->post ['month_1'];
		$day_1 = $this->request->post ['day_1'];
		$year_1 = $this->request->post ['year_1'];
		
		$dob111 = $month_1 . '-' . $day_1 . '-' . $year_1;
		$date = str_replace ( '-', '/', $dob111 );
		$res = explode ( "/", $date );
		$createdatess1 = $res [2] . "-" . $res [0] . "-" . $res [1];
		$dob = date ( 'Y-m-d', strtotime ( $createdatess1 ) );
		
		$existclient = array ();
		$existclient ['emp_extid'] = $emp_extid;
		$existclient ['ssn'] = $ssn;
		$existclient ['dob'] = $dob;
		$existclient ['emp_first_name'] = $this->request->post ['emp_first_name'];
		$existclient ['emp_last_name'] = $this->request->post ['emp_last_name'];
		
		$this->load->model ( 'setting/tags' );
		$tag_exist_info = $this->model_setting_tags->getTagsbyAllName ( $existclient );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		$current_date = date ( 'Y-m-d' );
		if ($current_date < $dob) {
			$this->error ['dob'] = "You cannot enter a date in the future!";
		}
		
		// var_dump($tag_exist_info);
		// die;
		
		$url2 .= '&tags_id=' . $tag_exist_info ['tags_id'];
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		$action2 = $this->url->link ( 'services/resident/updateclient', '' . $url2, 'SSL' );
		
		if (! isset ( $this->request->get ['tags_id'] )) {
			if ($tag_exist_info) {
				$this->error ['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="' . $action2 . '">Yes </a> ';
			}
		} else {
			if ($tag_exist_info && ($this->request->get ['tags_id'] != $tag_exist_info ['tags_id'])) {
				$this->error ['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="' . $action2 . '">Yes </a> ';
			}
		}
		
		/*
		 * if ($this->request->post['ssn'] == "") {
		 * $this->error['ssn'] = "This is required field!";
		 * }
		 */
		
		/*
		 * if ($this->request->post['dob'] == "") {
		 * $this->error['dob'] = "This is required field!";
		 * }
		 */
		
		if ($this->request->post ['gender'] == "") {
			$this->error ['gender'] = "This is required field!";
		}
		
		if (($this->request->post ['month_1'] == "") || ($this->request->post ['day_1'] == "") || ($this->request->post ['year_1'] == "")) {
			$this->error ['dob'] = "This is required field!";
		}
		
		if ($this->request->post ['zipcode'] != null && $this->request->post ['zipcode'] != "") {
			if ((utf8_strlen ( trim ( $this->request->post ['zipcode'] ) ) < 2 || utf8_strlen ( trim ( $this->request->post ['zipcode'] ) ) > 10)) {
				$this->error ['postal_code'] = "Please enter valid ZIP";
			}
		}
		
		if (($this->request->post ['emp_first_name'] != null && $this->request->post ['emp_first_name'] != "") && ($this->request->post ['emp_last_name'] != null && $this->request->post ['emp_last_name'] != "")) {
			if ($this->request->get ['tags_id'] == null && $this->request->get ['tags_id'] == "") {
				if ($this->request->post ['client_add_new'] == null && $this->request->post ['client_add_new'] == "") {
					if ($this->request->post ['forms_id'] == null && $this->request->post ['forms_id'] == "") {
						
						$this->load->model ( 'form/form' );
						
						$fdata = array ();
						
						$dob111 = $this->request->post ['month_1'] . '-' . $this->request->post ['day_1'] . '-' . $this->request->post ['year_1'];
						
						$fdata ['forms_fields_values'] = array (
								'' . TAG_EXTID . '' => $this->request->post ['emp_extid'],
								'' . TAG_SSN . '' => $this->request->post ['ssn'],
								'' . TAG_FNAME . '' => $this->request->post ['emp_first_name'],
								'' . TAG_LNAME . '' => $this->request->post ['emp_last_name'] 
						);
						// 'date_70767270' => $dob111,
						
						
						
						$client_form_info = $this->model_form_form->getscrnneningFormdata ( $fdata, $this->request->get ['facilities_id'] );
						
						if (! empty ( $client_form_info )) {
							$this->error ['warning'] = "Screening list";
							$this->error ['exit_error'] = '1';
						}
					}
				}
			}
		}
		
		/*
		 * if ($this->request->post['room'] != "" && $this->request->post['room'] != null) {
		 * if ($this->request->post['room_id'] == "0") {
		 * $this->error['warning'] = "Select valid Room!";
		 * }
		 * }
		 */
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	protected function getForm() {
		
		/*
		 * $this->load->model('api/encrypt');
		 * $cre_array = array();
		 * $cre_array['phone_device_id'] = $this->request->get['phone_device_id'];
		 * $cre_array['facilities_id'] = $this->request->get['facilities_id'];
		 *
		 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		 *
		 * if($api_device_info == false){
		 * $errorMessage = $this->model_api_encrypt->errorMessage();
		 * return $errorMessage;
		 * }
		 *
		 * $api_header_value = $this->model_api_encrypt->getallheaders1();
		 *
		 * if($api_header_value == false){
		 * $errorMessage = $this->model_api_encrypt->errorMessage();
		 * return $errorMessage;
		 * }
		 */
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->data ['serviceforms_id'] = '1';
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->data ['facilities_id_url'] = '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			// $this->load->model('setting/tags');
			// $taginfo = $this->model_setting_tags->getTag($this->request->get['tags_id']);
			
			$this->load->model ( 'setting/tags' );
			$taginfo = $this->model_setting_tags->getTaga ( $this->request->get ['tags_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['archive_is_archive'] = $taginfo ['is_archive'];
		}
		
		if ($this->request->get ['tags_id'] == null && $this->request->get ['tags_id'] == "") {
			$url2 = "";
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
			}
			
			$this->data ['action'] = $this->url->link ( 'services/resident/addclient', '' . $url2, 'SSL' );
		} else {
			
			$url2 = "";
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
			}
			
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
				$this->data ['is_archive'] = $this->request->get ['is_archive'];
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$this->load->model ( 'notes/notes' );
				$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
				
				$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
			}
			
			$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/updateclient', '' . $url3, 'SSL' ) );
			
			$this->data ['action'] = $this->url->link ( 'services/resident/updateclient', '' . $url2, 'SSL' );
			
			$this->data ['unlock_client'] = $this->url->link ( 'notes/notes/unlockUser&client=1', '' . $url2, 'SSL' );
			$this->data ['unlock_client_android'] = '1';
			
			$this->data ['viewallpicture'] = $this->url->link ( 'notes/tags/viewallpicture', '' . $url2, 'SSL' );
		}
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		// $this->data['notes_id'] = $this->request->get['notes_id'];
		
		if ($this->request->get ['saveclient'] != '1') {
			$this->data ['updatenotes_id'] = $notes_id;
		}
		
		$this->data ['tags_id'] = $this->request->get ['tags_id'];
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		if (isset ( $this->error ['ssn'] )) {
			$this->data ['error_ssn'] = $this->error ['ssn'];
		} else {
			$this->data ['error_ssn'] = '';
		}
		if (isset ( $this->error ['postal_code'] )) {
			$this->data ['error_postal_code'] = $this->error ['postal_code'];
		} else {
			$this->data ['error_postal_code'] = '';
		}
		
		if (isset ( $this->error ['dob'] )) {
			$this->data ['error_dob'] = $this->error ['dob'];
		} else {
			$this->data ['error_dob'] = '';
		}
		
		if (isset ( $this->error ['emp_first_name'] )) {
			$this->data ['error_emp_first_name'] = $this->error ['emp_first_name'];
		} else {
			$this->data ['error_emp_first_name'] = '';
		}
		
		// var_dump($this->data['error_emp_first_name']);
		
		if (isset ( $this->error ['emp_last_name'] )) {
			$this->data ['error_emp_last_name'] = $this->error ['emp_last_name'];
		} else {
			$this->data ['error_emp_last_name'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['location_address'] )) {
			$this->data ['error_location_address'] = $this->error ['location_address'];
		} else {
			$this->data ['error_location_address'] = '';
		}
		if (isset ( $this->error ['gender'] )) {
			$this->data ['error_gender'] = $this->error ['gender'];
		} else {
			$this->data ['error_gender'] = '';
		}
		
		if (isset ( $this->request->post ['imageName_url'] )) {
			$this->data ['imageName_url'] = $this->request->post ['imageName_url'];
		} elseif (! empty ( $taginfo )) {
			
			$this->load->model ( 'setting/tags' );
			$get_img = $this->model_setting_tags->getImage ( $this->request->get ['tags_id'] );
			
			$this->data ['upload_file'] = $get_img ['enroll_image'];
			$this->data ['upload_file_thumb'] = $get_img ['upload_file_thumb'];
			
			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
				$this->data ['imageName_url'] = $get_img ['upload_file_thumb'];
			} else {
				$this->data ['imageName_url'] = $get_img ['enroll_image'];
			}
			$this->load->model ( 'setting/image' );
			$this->data ['check_img'] = $this->model_setting_image->checkresize ( $get_img ['enroll_image'] );
		} else {
			$this->data ['imageName_url'] = '';
		}
		
		if (isset ( $this->request->post ['imageName_path'] )) {
			$this->data ['imageName_path'] = $this->request->post ['imageName_path'];
		} else {
			$this->data ['imageName_path'] = '';
		}
		
		if (isset ( $this->request->post ['imageName'] )) {
			$this->data ['imageName'] = $this->request->post ['imageName'];
		} else {
			$this->data ['imageName'] = '';
		}
		
		if (isset ( $this->request->post ['upload_file'] )) {
			$this->data ['upload_file'] = $this->request->post ['upload_file'];
		} elseif (! empty ( $taginfo )) {
			
			$this->load->model ( 'setting/tags' );
			$get_img = $this->model_setting_tags->getImage ( $this->request->get ['tags_id'] );
			
			$this->data ['upload_file'] = $get_img ['enroll_image'];
			$this->data ['upload_file_thumb'] = $get_img ['upload_file_thumb'];
			
			$this->load->model ( 'setting/image' );
			$this->data ['check_img'] = $this->model_setting_image->checkresize ( $get_img ['enroll_image'] );
		} else {
			$this->data ['upload_file'] = '';
		}
		
		if ($this->data ['upload_file'] != null && $this->data ['upload_file'] != "") {
			
			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
				$image_url = file_get_contents ( $get_img ['upload_file_thumb'] );
			} else {
				$image_url = file_get_contents ( $this->data ['upload_file'] );
			}
			
			$this->data ['image_url1'] = 'data:image/jpg;base64,' . base64_encode ( $image_url );
		}
		
		if (isset ( $this->request->post ['tags_status_in'] )) {
			$this->data ['tags_status_in'] = $this->request->post ['tags_status_in'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['tags_status_in'] = $taginfo ['tags_status_in'];
			
			$this->load->model ( 'setting/tags' );
			$taginfo_a = $this->model_setting_tags->gettotalarchivetags ( $this->request->get ['tags_id'] );
			
			$this->data ['taginfo_a'] = $taginfo_a;
		} else {
			$this->data ['tags_status_in'] = '';
		}
		if (isset ( $this->request->post ['referred_facility'] )) {
			$this->data ['referred_facility'] = $this->request->post ['referred_facility'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['referred_facility'] = $taginfo ['referred_facility'];
		} else {
			$this->data ['referred_facility'] = '';
		}
		
		if (isset ( $this->request->post ['facilities_id'] )) {
			$this->data ['facilities_id'] = $this->request->post ['facilities_id'];
		} elseif (! empty ( $taginfo )) {
			
			$this->data ['facilities_id'] = $taginfo ['facilities_id'];
		} else {
			$this->data ['facilities_id'] = $this->request->get ['facilities_id'];
		}
		
		$this->load->model ( 'setting/tags' );
		$masked_fields = $this->model_setting_tags->getHiddenMaskedFields ();
		$hidden_fields = $this->model_setting_tags->getHiddenFields ();
		
		if (isset ( $this->request->post ['emp_extid'] )) {
			$this->data ['emp_extid'] = $this->request->post ['emp_extid'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emp_extid'] = $taginfo ['emp_extid'];
		} else {
			$this->data ['emp_extid'] = '';
		}
		
		if (isset ( $this->request->post ['location_address'] )) {
			$this->data ['location_address'] = $this->request->post ['location_address'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['location_address'] = $taginfo ['location_address'];
		} else {
			$this->data ['location_address'] = '';
		}
		
		if (isset ( $this->request->post ['latitude'] )) {
			$this->data ['latitude'] = $this->request->post ['latitude'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['latitude'] = $taginfo ['latitude'];
		} else {
			$this->data ['latitude'] = '';
		}
		if (isset ( $this->request->post ['longitude'] )) {
			$this->data ['longitude'] = $this->request->post ['longitude'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['longitude'] = $taginfo ['longitude'];
		} else {
			$this->data ['longitude'] = '';
		}
		
		if (isset ( $this->request->post ['address_street2'] )) {
			$this->data ['address_street2'] = $this->request->post ['address_street2'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['address_street2'] = $taginfo ['address_street2'];
		} else {
			$this->data ['address_street2'] = '';
		}
		
		if (isset ( $this->request->post ['state'] )) {
			$this->data ['state'] = $this->request->post ['state'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['state'] = $taginfo ['state'];
		} else {
			$this->data ['state'] = '';
		}
		
		if (isset ( $this->request->post ['city'] )) {
			$this->data ['city'] = $this->request->post ['city'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['city'] = $taginfo ['city'];
		} else {
			$this->data ['city'] = '';
		}
		
		if (isset ( $this->request->post ['zipcode'] )) {
			$this->data ['zipcode'] = $this->request->post ['zipcode'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['zipcode'] = $taginfo ['zipcode'];
		} else {
			$this->data ['zipcode'] = '';
		}
		
		if (isset ( $this->request->post ['ssn'] )) {
			$this->data ['ssn'] = $this->request->post ['ssn'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['ssn'] = $taginfo ['ssn'];
		} else {
			$this->data ['ssn'] = '';
		}
		
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/timezone' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilityinfo ['timezone_id'] );
		
		date_default_timezone_set ( $timezone_info ['timezone_value'] );
		
		if (isset ( $this->request->post ['date_of_screening'] )) {
			$this->data ['date_of_screening'] = $this->request->post ['date_of_screening'];
		} elseif (! empty ( $taginfo )) {
			if ($taginfo ['date_of_screening'] != "0000-00-00") {
				$this->data ['date_of_screening'] = date ( 'm-d-Y', strtotime ( $taginfo ['date_of_screening'] ) );
			} else {
				$this->data ['date_of_screening'] = date ( 'm-d-Y' );
			}
		} else {
			$this->data ['date_of_screening'] = date ( 'm-d-Y' );
		}
		
		if (isset ( $this->request->post ['person_screening'] )) {
			$this->data ['person_screening'] = $this->request->post ['person_screening'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['person_screening'] = $taginfo ['person_screening'];
		} else {
			$this->data ['person_screening'] = '';
		}
		
		if (isset ( $this->request->post ['gender'] )) {
			$this->data ['gender'] = $this->request->post ['gender'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['gender'] = $taginfo ['customlistvalues_id'];
		} else {
			$this->data ['gender'] = '';
		}
		
		if (isset ( $this->request->post ['dob'] )) {
			$this->data ['dob'] = $this->request->post ['dob'];
		} elseif (! empty ( $taginfo )) {
			if ($taginfo ['dob'] != "0000-00-00") {
				$this->data ['dob'] = date ( 'm-d-Y', strtotime ( $taginfo ['dob'] ) );
			} else {
				$this->data ['dob'] = '';
			}
		} else {
			$this->data ['dob'] = '';
		}
		
		if (isset ( $this->request->post ['month_1'] )) {
			$this->data ['month_1'] = $this->request->post ['month_1'];
		} elseif (! empty ( $taginfo )) {
			if ($taginfo ['dob'] != "0000-00-00") {
				$this->data ['month_1'] = date ( 'm', strtotime ( $taginfo ['dob'] ) );
			} else {
				$this->data ['month_1'] = date ( 'm' );
			}
		} else {
			$this->data ['month_1'] = date ( 'm' );
		}
		
		if (isset ( $this->request->post ['day_1'] )) {
			$this->data ['day_1'] = $this->request->post ['day_1'];
		} elseif (! empty ( $taginfo )) {
			if ($taginfo ['dob'] != "0000-00-00") {
				$this->data ['day_1'] = date ( 'd', strtotime ( $taginfo ['dob'] ) );
			} else {
				$this->data ['day_1'] = date ( 'd' );
			}
		} else {
			$this->data ['day_1'] = date ( 'd' );
		}
		
		if (isset ( $this->request->post ['year_1'] )) {
			$this->data ['year_1'] = $this->request->post ['year_1'];
		} elseif (! empty ( $taginfo )) {
			if ($taginfo ['dob'] != "0000-00-00") {
				$this->data ['year_1'] = date ( 'Y', strtotime ( $taginfo ['dob'] ) );
			} else {
				$this->data ['year_1'] = date ( 'Y' );
			}
		} else {
			$this->data ['year_1'] = date ( 'Y' );
		}
		
		$this->data ['current_date'] = date ( 'm-d-Y' );
		$this->data ['current_y'] = date ( "Y" );
		
		if (isset ( $this->request->post ['emp_first_name'] )) {
			$this->data ['emp_first_name'] = $this->request->post ['emp_first_name'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emp_first_name'] = $taginfo ['emp_first_name'];
		} else {
			$this->data ['emp_first_name'] = '';
		}
		if (isset ( $this->request->post ['emp_middle_name'] )) {
			$this->data ['emp_middle_name'] = $this->request->post ['emp_middle_name'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emp_middle_name'] = $taginfo ['emp_middle_name'];
		} else {
			$this->data ['emp_middle_name'] = '';
		}
		
		if (isset ( $this->request->post ['emp_last_name'] )) {
			$this->data ['emp_last_name'] = $this->request->post ['emp_last_name'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emp_last_name'] = $taginfo ['emp_last_name'];
		} else {
			$this->data ['emp_last_name'] = '';
		}
		
		if (isset ( $this->request->post ['emergency_contact'] )) {
			$this->data ['emergency_contact'] = $this->request->post ['emergency_contact'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emergency_contact'] = $taginfo ['emergency_contact'];
		} else {
			$this->data ['emergency_contact'] = '';
		}
		
		if (isset ( $this->request->post ['room_id'] )) {
			$this->data ['room_id'] = $this->request->post ['room_id'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['room_id'] = $taginfo ['room'];
		} else {
			$this->data ['room_id'] = '';
		}
		
		/*
		 * if (isset($this->request->post['room'])) {
		 * $this->data['room'] = $this->request->post['room'];
		 * }elseif (!empty($taginfo)) {
		 * $this->load->model('setting/locations');
		 * $tags_info12 = $this->model_setting_locations->getlocation($taginfo['room']);
		 *
		 * $this->data['room'] = $tags_info12['location_name'];
		 * } else {
		 * $this->data['room'] = '';
		 * }
		 */
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
			
			$this->load->model ( 'setting/locations' );
			$data = array (
					'location_name' => $this->request->get ['filter_name'],
					'facilities_id' => $facilities_id,
					'status' => '1',
					'sort' => 'task_form_name',
					'order' => 'ASC' 
			);
			
			$rresults = $this->model_setting_locations->getlocations ( $data );
			
			foreach ( $rresults as $result ) {
				
				$this->data ['rooms'] [] = array (
						'locations_id' => $result ['locations_id'],
						'location_name' => $result ['location_name'],
						'date_added' => $result ['date_added'] 
				);
			}
		}
		
		if (isset ( $this->request->post ['tagstatus'] )) {
			$this->data ['tagstatus'] = $this->request->post ['tagstatus'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['tagstatus'] = $taginfo ['tagstatus'];
		} else {
			$this->data ['tagstatus'] = '';
		}
		
		if (isset ( $this->request->post ['med_mental_health'] )) {
			$this->data ['med_mental_health'] = $this->request->post ['med_mental_health'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['med_mental_health'] = $taginfo ['med_mental_health'];
		} else {
			$this->data ['med_mental_health'] = '';
		}
		
		if (isset ( $this->request->post ['constant_sight'] )) {
			$this->data ['constant_sight'] = $this->request->post ['constant_sight'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['constant_sight'] = $taginfo ['constant_sight'];
		} else {
			$this->data ['constant_sight'] = '';
		}
		
		if (isset ( $this->request->post ['alert_info'] )) {
			$this->data ['alert_info'] = $this->request->post ['alert_info'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['alert_info'] = $taginfo ['alert_info'];
		} else {
			$this->data ['alert_info'] = '';
		}
		
		if (isset ( $this->request->post ['prescription'] )) {
			$this->data ['prescription'] = $this->request->post ['prescription'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['prescription'] = $taginfo ['prescription'];
		} else {
			$this->data ['prescription'] = '';
		}
		
		if (isset ( $this->request->post ['restriction_notes'] )) {
			$this->data ['restriction_notes'] = $this->request->post ['restriction_notes'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['restriction_notes'] = $taginfo ['restriction_notes'];
		} else {
			$this->data ['restriction_notes'] = '';
		}
		
		if (isset ( $this->request->post ['allclients'] )) {
			$this->data ['allclients'] = $this->request->post ['allclients'];
		} else {
			$this->data ['allclients'] = '1';
		}
		
		if (isset ( $this->request->post ['is_discharge'] )) {
			$this->data ['is_discharge'] = $this->request->post ['is_discharge'];
		} else {
			$this->data ['is_discharge'] = '';
		}
		
		if (isset ( $this->request->post ['reminder_time'] )) {
			$this->data ['reminder_time'] = $this->request->post ['reminder_time'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['reminder_time'] = date ( 'h:i A', strtotime ( $taginfo ['reminder_time'] ) );
		} else {
			$this->data ['reminder_time'] = '';
		}
		
		if (isset ( $this->request->post ['reminder_date'] )) {
			$this->data ['reminder_date'] = $this->request->post ['reminder_date'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['reminder_date'] = date ( 'm-d-Y', strtotime ( $taginfo ['reminder_date'] ) );
		} else {
			$this->data ['reminder_date'] = '';
		}
		
		$this->load->model ( 'resident/resident' );
		
		if (isset ( $this->request->post ['medication_fields'] )) {
			$this->data ['medication_fields'] = $this->request->post ['medication_fields'];
		} elseif ($this->request->get ['tags_id']) {
			
			if ($this->request->get ['notes_id']) {
				$notes_id = $this->request->get ['notes_id'];
			} else {
				$notes_id = $this->request->get ['updatenotes_id'];
			}
			
			$medicine_info = $this->model_resident_resident->gettagmedicine ( $this->request->get ['tags_id'], $this->request->get ['is_archive'], $notes_id );
			
			$this->data ['medication_fields'] = unserialize ( $medicine_info ['medication_fields'] );
		} else {
			$this->data ['medication_fields'] = array ();
		}
		
		if (isset ( $this->request->post ['forms_id'] )) {
			$this->data ['forms_id'] = $this->request->post ['forms_id'];
		} elseif (! empty ( $taginfo )) {
			
			$this->load->model ( 'form/form' );
			
			if ($taginfo ['is_archive'] == '3') {
				$tags_info121 = $this->model_form_form->gettagsforma3 ( $this->request->get ['tags_id'] );
			} else {
				$tags_info121 = $this->model_form_form->gettagsforma ( $this->request->get ['tags_id'] );
			}
			
			// $tags_info121 = $this->model_form_form->gettagsforma($this->request->get['tags_id']);
			
			$this->data ['forms_id'] = $tags_info121 ['forms_id'];
		} else {
			$this->data ['forms_id'] = '';
		}
		
		if (isset ( $this->request->post ['link_screening'] )) {
			$this->data ['link_screening'] = $this->request->post ['link_screening'];
		} elseif (! empty ( $tags_info121 )) {
			$this->load->model ( 'form/form' );
			$tags_info12 = $this->model_form_form->getFormDatas ( $tags_info121 ['forms_id'] );
			
			$design_forms = unserialize ( $tags_info12 ['design_forms'] );
			
			$clientname = "";
			if ($design_forms [0] [0] ['' . TAG_FNAME . ''] != null && $design_forms [0] [0] ['' . TAG_FNAME . ''] != "") {
				$clientname = $design_forms [0] [0] ['' . TAG_FNAME . ''] . ' ' . $design_forms [0] [0] ['' . TAG_MNAME . ''] . ' ' . $design_forms [0] [0] ['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms [0] [0] ['' . TAG_DOB . ''] . ' | Screening ' . $design_forms [0] [0] ['' . TAG_SCREENING . ''];
			} else {
				$clientname = $tags_info12 ['incident_number'] . ' ' . date ( 'm-d-Y', strtotime ( $tags_info12 ['date_added'] ) );
			}
			
			$this->data ['link_screening'] = $clientname;
		} else {
			$this->data ['link_screening'] = '';
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		$this->load->model ( 'notes/notes' );
		
		if ($facilityinfo ['config_tags_customlist_id'] != NULL && $facilityinfo ['config_tags_customlist_id'] != "") {
			
			$d = array ();
			$d ['customlist_id'] = $facilityinfo ['config_tags_customlist_id'];
			$customlists = $this->model_notes_notes->getcustomlists ( $d );
			
			if ($customlists) {
				foreach ( $customlists as $customlist ) {
					$d2 = array ();
					$d2 ['customlist_id'] = $customlist ['customlist_id'];
					
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
					
					$this->data ['customlists'] [] = array (
							'customlist_id' => $customlist ['customlist_id'],
							'customlist_name' => $customlist ['customlist_name'],
							'customlistvalues' => $customlistvalues 
					);
				}
			}
		}
		
		$this->load->model ( 'facilities/facilities' );
		$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
		$this->data ['sfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $data2 );
		
		$url31 = "";
		
		if ($this->request->post ['emp_extid'] != null && $this->request->post ['emp_extid'] != "") {
			$url31 .= '&emp_extid=' . $this->request->post ['emp_extid'];
		}
		
		if ($this->request->post ['ssn'] != null && $this->request->post ['ssn'] != "") {
			$url31 .= '&ssn=' . $this->request->post ['ssn'];
		}
		
		if ($this->request->post ['emp_first_name'] != null && $this->request->post ['emp_first_name'] != "") {
			$url31 .= '&emp_first_name=' . $this->request->post ['emp_first_name'];
		}
		
		if ($this->request->post ['emp_last_name'] != null && $this->request->post ['emp_last_name'] != "") {
			$url31 .= '&emp_last_name=' . $this->request->post ['emp_last_name'];
		}
		
		if ($this->request->post ['month_1'] != null && $this->request->post ['month_1'] != "") {
			
			$dob111 = $this->request->post ['month_1'] . '-' . $this->request->post ['day_1'] . '-' . $this->request->post ['year_1'];
			
			$url31 .= '&dob=' . $dob111;
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url31 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		$this->data ['redirect_url_2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/exitscreening', '' . $url31, 'SSL' ) );
		
		if (isset ( $this->error ['exit_error'] )) {
			$this->data ['exit_error'] = $this->error ['exit_error'];
		} else {
			$this->data ['exit_error'] = '';
		}
		
		if (isset ( $this->request->post ['client_add_new'] )) {
			$this->data ['client_add_new'] = $this->request->post ['client_add_new'];
		} else {
			$this->data ['client_add_new'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/tagform.php';
		$this->children = array (
				'common/headerpopup',
				'common/headerform' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function jsoncustomsForm() {
		$url2 = "";
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$new_form = $this->request->get ['new_form'];
			$tags_id = $this->request->get ['tags_id'];
			$url2 .= '&new_form=' . $this->request->get ['new_form'];
		} else {
			$new_form = $this->request->get ['new_form'];
			$tags_id = '';
			$url2 .= '&new_form=' . $this->request->get ['new_form'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = '';
		}
		
		if ($this->request->get ['archive_tags_id'] != null && $this->request->get ['archive_tags_id'] != "") {
			$url2 .= '&archive_tags_id=' . $this->request->get ['archive_tags_id'];
			$archive_tags_id = $this->request->get ['archive_tags_id'];
		} else {
			$archive_tags_id = '';
		}
		
		if ($this->request->get ['tags_status_in_change'] != null && $this->request->get ['tags_status_in_change'] != "") {
			$url2 .= '&tags_status_in_change=' . $this->request->get ['tags_status_in_change'];
			$tags_status_in_change = $this->request->get ['tags_status_in_change'];
		} else {
			$tags_status_in_change = '';
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$notes_id = $this->request->get ['notes_id'];
			
			$signature_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/insert2', '' . $url2, 'SSL' ) );
		} else {
			$notes_id = '';
			$signature_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/insert2', '' . $url2, 'SSL' ) );
		}
		
		if ($this->request->get ['tags_id']) {
			$tags_id = $this->request->get ['tags_id'];
		} elseif ($this->request->get ['emp_tag_id']) {
			$tags_id = $this->request->get ['emp_tag_id'];
		}
		if ($tags_id != null && $tags_id != "") {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			$name = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$name = '';
		}
		
		if ($new_form == '1') {
			$cancel_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/updateclient', '' . $url2, 'SSL' ) );
		}
		
		if ($new_form == '2') {
			$cancel_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/addclient', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['facilitiess'] [] = array (
				'task_form' => '',
				'medication_tags' => '',
				'archive_tags_medication_id' => '',
				'tags_id' => $tags_id,
				'emp_tag_id' => '',
				'archive_tags_id' => $archive_tags_id,
				'name' => $name,
				'new_form' => $new_form,
				'notes_id' => $notes_id,
				'facilities_id' => $facilities_id,
				'signature_url' => $signature_url,
				'cancel_url' => $cancel_url 
		);
		
		if ($this->request->get ['is_html'] == '1') {
			$this->data ['signature_url'] = $signature_url;
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/form/jsoncustom.php';
			
			$this->response->setOutput ( $this->render () );
		} else {
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		}
	}
	public function insert2() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residentinsert2', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				if ($this->request->get ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				if ($this->request->get ['new_form'] == '1') {
					$this->load->model ( 'setting/tags' );
					
					$this->load->model ( 'api/temporary' );
					$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_tags_id'] );
					
					$tempdata = array ();
					$tempdata = unserialize ( $temporary_info ['data'] );
					
					$archive_tags_id = $this->model_setting_tags->editTags ( $this->request->get ['tags_id'], $tempdata, $this->request->get ['facilities_id'] );
					
					$data2 = array ();
					$data2 ['tags_id'] = $this->request->get ['tags_id'];
					$data2 ['notes_id'] = $this->request->get ['notes_id'];
					$data2 ['archive_tags_id'] = $archive_tags_id;
					$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
					$data2 ['facilitytimezone'] = $facilitytimezone;
					
					$data2 ['phone_device_id'] = $this->request->post ['phone_device_id'];
					
					$data2 ['tags_status_in_change'] = $this->request->get ['tags_status_in_change'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data2 ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data2 ['is_android'] = '1';
					}
					
					$notes_id = $this->model_setting_tags->updateclientsign ( $this->request->post, $data2 );
					
					$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_tags_id'] );
				} else {
					
					$this->load->model ( 'setting/tags' );
					
					$data2 = array ();
					$data2 ['tags_id'] = $this->request->get ['tags_id'];
					$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
					$data2 ['facilitytimezone'] = $facilitytimezone;
					
					$data2 ['phone_device_id'] = $this->request->post ['phone_device_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data2 ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data2 ['is_android'] = '1';
					}
					
					$notes_id = $this->model_setting_tags->addclientsign ( $this->request->post, $data2 );
				}
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addclient jsonAddNotes ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_addclient', $activity_data2 );
		}
	}
	public function tagsmedication() {
		
		/*
		 * $this->load->model('api/encrypt');
		 * $cre_array = array();
		 * $cre_array['phone_device_id'] = $this->request->get['phone_device_id'];
		 * $cre_array['facilities_id'] = $this->request->get['facilities_id'];
		 *
		 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		 *
		 * if($api_device_info == false){
		 * $errorMessage = $this->model_api_encrypt->errorMessage();
		 * return $errorMessage;
		 * }
		 *
		 * $api_header_value = $this->model_api_encrypt->getallheaders1();
		 *
		 * if($api_header_value == false){
		 * $errorMessage = $this->model_api_encrypt->errorMessage();
		 * return $errorMessage;
		 * }
		 */
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->data ['serviceforms_id'] = '1';
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->data ['facilities_id_url'] = '&facilities_id=' . $this->request->get ['facilities_id'];
			
			$this->load->model ( 'setting/timezone' );
			$this->load->model ( 'facilities/facilities' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			$this->data ['current_time'] = date ( 'h:i A' );
		}
		if ($this->request->get ['search_tags_id']) {
			$tags_id = $this->request->get ['search_tags_id'];
			$this->data ['search_tags_id'] = $this->request->get ['search_tags_id'];
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTagbyEMPID ( $tags_id );
			$this->data ['tagdetails'] = $tag_info;
		} else {
			if ($this->request->get ['tags_id']) {
				$tags_id = $this->request->get ['tags_id'];
			} elseif ($this->request->post ['emp_tag_id']) {
				$tags_id = $this->request->post ['emp_tag_id'];
			}
			
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		}
		
		if ($tags_id) {
			$this->data ['name'] = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		}
		
		$this->load->model ( 'createtask/createtask' );
		$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $this->request->get ['facilities_id'] );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForms ()) {
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $tags_id;
			$tdata ['facilities_id'] = $this->request->get ['facilities_id'];
			$tdata ['phone_device_id'] = $this->request->get ['phone_device_id'];
			$tdata ['type'] = 'updatehealthform';
			$archive_tags_medication_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			
			/*
			 * $archive_tags_medication_id = $this->model_resident_resident->addTagsMedication($this->request->post, $tags_id);
			 */
			
			$url2 = "";
			
			// var_dump($this->request->post['medication']);
			
			if ($this->request->post ['medication'] != null && $this->request->post ['medication'] != "") {
				// $this->session->data['medication'] = $this->request->post['medication'];
				
				$medication_tags = implode ( ',', $this->request->post ['medication'] );
				
				if ($medication_tags != null && $medication_tags != "") {
					$url2 .= '&medication_tags=' . $medication_tags;
				}
				$url2 .= '&addmedicine=1';
				
				// $this->session->data['success2'] = 'Medication added successfully!';
			} else {
				// $this->session->data['success_add_form'] = 'Medication added successfully!';
				
				$url2 .= '&addmedicine=2';
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
			}
			$url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			
			$this->redirect ( $this->url->link ( 'services/resident/jsoncustomsForm2', '' . $url2, 'SSL' ) );
		}
		
		$facilities_id = $this->request->get ['facilities_id'];
		$this->load->model ( 'setting/locations' );
		$data = array (
				'location_name' => $this->request->get ['filter_name'],
				'facilities_id' => $facilities_id,
				'status' => '1',
				'type' => 'bedcheck',
				'sort' => 'task_form_name',
				'order' => 'ASC' 
		);
		
		$rresults = $this->model_setting_locations->getlocations ( $data );
		foreach ( $rresults as $result ) {
			$this->data ['rooms'] [] = array (
					'locations_id' => $result ['locations_id'],
					'location_name' => $result ['location_name'] 
			);
		}
		$data2 = array (
				'location_name' => $this->request->get ['filter_name'],
				'facilities_id' => $facilities_id,
				'status' => '1',
				'type' => 'medication',
				'sort' => 'task_form_name',
				'order' => 'ASC' 
		);
		
		$rresult6s = $this->model_setting_locations->getlocations ( $data2 );
		
		foreach ( $rresult6s as $result1 ) {
			
			$this->data ['medications'] [] = array (
					'locations_id' => $result1 ['locations_id'],
					'location_name' => $result1 ['location_name'] 
			);
		}
		
		$data3 = array (
				'location_name' => $this->request->get ['filter_name'],
				'facilities_id' => $facilities_id,
				'status' => '1',
				'type' => 'medication',
				'sort' => 'task_form_name',
				'order' => 'ASC' 
		);
		
		$this->load->model ( 'medicationtype/medicationtype' );
		$results = $this->model_medicationtype_medicationtype->getmedicationtypes ( $data3 );
		
		foreach ( $results as $result ) {
			
			$this->data ['medication_types'] [] = array (
					'medicationtype_id' => $result ['medicationtype_id'],
					'type_name' => $result ['type_name'],
					'type' => $result ['type'],
					'measurement_type' => $result ['measurement_type'],
					'status' => $result ['status'] 
			);
		}
		
		if (isset ( $this->request->post ['room_id'] )) {
			$this->data ['room_id'] = $this->request->post ['room_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['room_id'] = $tag_info ['room'];
		} else {
			$this->data ['room_id'] = '';
		}
		
		// var_dump($this->data['room_id']);
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id1'] )) {
			$this->data ['emp_tag_id1'] = $this->request->post ['emp_tag_id1'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id1'] = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id1'] = '';
		}
		
		if (isset ( $this->request->post ['new_module'] )) {
			$this->data ['modules'] = $this->request->post ['new_module'];
		} elseif ($this->request->get ['tags_id']) {
			
			$muduled = $this->model_resident_resident->gettagModule ( $this->request->get ['tags_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['modules'] = $muduled ['new_module'];
		} elseif ($this->request->post ['emp_tag_id']) {
			
			$muduled = $this->model_resident_resident->gettagModule ( $this->request->post ['emp_tag_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['modules'] = $muduled ['new_module'];
		} else {
			$this->data ['modules'] = array ();
		}
		
		// var_dump($this->data['modules']);
		
		if (isset ( $this->request->post ['medication_fields'] )) {
			$this->data ['medication_fields'] = $this->request->post ['medication_fields'];
		} elseif ($this->request->get ['tags_id']) {
			
			$medicine_info = $this->model_resident_resident->gettagmedicine ( $this->request->get ['tags_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['medication_fields'] = unserialize ( $medicine_info ['medication_fields'] );
		} elseif ($this->request->post ['emp_tag_id']) {
			
			$medicine_info = $this->model_resident_resident->gettagmedicine ( $this->request->post ['emp_tag_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['medication_fields'] = unserialize ( $medicine_info ['medication_fields'] );
		} else {
			$this->data ['medication_fields'] = array ();
		}
		
		if (isset ( $this->request->post ['is_schedule'] )) {
			$this->data ['is_schedule'] = $this->request->post ['is_schedule'];
		} elseif ($medicine_info) {
			$this->data ['is_schedule'] = $medicine_info ['is_schedule'];
		} else {
			$this->data ['is_schedule'] = '0';
		}
		
		if (isset ( $this->request->post ['drug_name'] )) {
			$this->data ['drug_name'] = $this->request->post ['drug_name'];
		} else {
			$this->data ['drug_name'] = '';
		}
		
		if (isset ( $this->request->post ['drug_mg'] )) {
			$this->data ['drug_mg'] = $this->request->post ['drug_mg'];
		} else {
			$this->data ['drug_mg'] = '';
		}
		
		if (isset ( $this->request->post ['drug_am'] )) {
			$this->data ['drug_am'] = $this->request->post ['drug_am'];
		} else {
			$this->data ['drug_am'] = date ( 'h:i A' );
		}
		
		if (isset ( $this->request->post ['drug_pm'] )) {
			$this->data ['drug_pm'] = $this->request->post ['drug_pm'];
		} else {
			$this->data ['drug_pm'] = '';
		}
		
		if (isset ( $this->request->post ['drug_alertnate'] )) {
			$this->data ['drug_alertnate'] = $this->request->post ['drug_alertnate'];
		} else {
			$this->data ['drug_alertnate'] = '';
		}
		
		if (isset ( $this->request->post ['drug_prn'] )) {
			$this->data ['drug_prn'] = $this->request->post ['drug_prn'];
		} else {
			$this->data ['drug_prn'] = '';
		}
		
		if (isset ( $this->request->post ['instructions'] )) {
			$this->data ['instructions'] = $this->request->post ['instructions'];
		} else {
			$this->data ['instructions'] = '';
		}
		
		if (isset ( $this->request->post ['medication'] )) {
			$this->data ['medication'] = $this->request->post ['medication'];
		} else {
			$this->data ['medication'] = array ();
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		if (isset ( $this->session->data ['success_add_form1'] )) {
			$this->data ['success_add_form1'] = $this->session->data ['success_add_form1'];
			
			unset ( $this->session->data ['success_add_form1'] );
		} else {
			$this->data ['success_add_form1'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['drug_name'] )) {
			$this->data ['error_drug_name'] = $this->error ['drug_name'];
		} else {
			$this->data ['error_drug_name'] = array ();
			;
		}
		
		if (isset ( $this->error ['date_from'] )) {
			$this->data ['error_date_from'] = $this->error ['date_from'];
		} else {
			$this->data ['error_date_from'] = array ();
			;
		}
		if (isset ( $this->error ['date_to'] )) {
			$this->data ['error_date_to'] = $this->error ['date_to'];
		} else {
			$this->data ['error_date_to'] = array ();
		}
		if (isset ( $this->error ['daily_times'] )) {
			$this->data ['error_daily_times'] = $this->error ['daily_times'];
		} else {
			$this->data ['error_daily_times'] = array ();
		}
		
		if (isset ( $this->error ['drug_mg'] )) {
			$this->data ['error_drug_mg'] = $this->error ['drug_mg'];
		} else {
			$this->data ['error_drug_mg'] = array ();
		}
		
		if (isset ( $this->error ['drug_alertnate'] )) {
			$this->data ['error_drug_alternate'] = $this->error ['drug_alertnate'];
		} else {
			$this->data ['error_drug_alternate'] = array ();
		}
		
		if (isset ( $this->error ['drug_pm'] )) {
			$this->data ['error_drug_pm'] = $this->error ['drug_pm'];
		} else {
			$this->data ['error_drug_pm'] = array ();
		}
		
		$url2 = "";
		$url3 = "";
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$url3 .= '&tags_id=' . $this->request->get ['tags_id'];
			
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['emp_tag_id'];
			$url3 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$url3 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
			$url2 .= '&is_html=' . $this->request->get ['is_html'];
			$url3 .= '&is_html=' . $this->request->get ['is_html'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['archive_tags_medication_id'] != null && $this->request->get ['archive_tags_medication_id'] != "") {
			$url2 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
		}
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			$this->data ['is_archive'] = $this->request->get ['is_archive'];
		}
		$this->data ['addinventorys_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/verifyInventory', '' . $url2, 'SSL' ) );
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/tagsmedication', $url2, true ) );
		$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/tagsmedication', '' . $url3, 'SSL' ) );
		
		$this->data ['autosearch'] = $this->request->get ['autosearch'];
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/medication.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForms() {
		
		/*
		 * if($this->request->post['new_module'] == null && $this->request->post['new_module'] == ""){
		 * $this->error['warning'] = 'Warning: Medication is required';
		 * }
		 */
		/*
		 * if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
		 * $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
		 * }
		 */
		if ($this->request->post ['new_module'] != null && $this->request->post ['new_module'] != "") {
			foreach ( $this->request->post ['new_module'] as $new_module ) {
				if ($new_module ['drug_name'] == "" && $new_module ['drug_name'] == null) {
					$this->error ['warning'] = 'Warning: Medication is required';
				}
				
				if ($new_module ['is_schedule_medication'] == '1') {
					if ($new_module ['date_from'] == "" && $new_module ['date_from'] == null) {
						$this->error ['date_from'] [$key] = 'Date From is required';
					}
					if ($new_module ['date_to'] == "" && $new_module ['date_to'] == null) {
						$this->error ['date_to'] [$key] = 'Date To is required';
					}
					if ($new_module ['daily_times'] == "" && $new_module ['daily_times'] == null) {
						$this->error ['daily_times'] [$key] = 'Time is required';
					}
				}
				
				/*
				 * if ($new_module['drug_pm'] == "" && $new_module['drug_pm'] == null) {
				 * $this->error['drug_pm'][$key] = 'Type is required';
				 * }
				 *
				 * if ($new_module['drug_mg'] == "" && $new_module['drug_mg'] == null) {
				 * $this->error['drug_mg'][$key] = 'Quantity is required';
				 * }
				 * if ($new_module['drug_alertnate'] == "" && $new_module['drug_alertnate'] == null) {
				 * $this->error['drug_alertnate'][$key] = 'Dosage is required';
				 * }
				 */
			}
		}
		
		if ($this->request->post ['emp_tag_id1'] == "" && $this->request->post ['emp_tag_id1'] == null) {
			$this->error ['warning'] = 'Warning: Client is required';
		}
		
		if ($this->request->post ['drug_name'] != "" && $this->request->post ['drug_name'] != null) {
			$medication_info = $this->model_resident_resident->get_medicationyname ( $this->request->post ['drug_name'], $this->request->get ['tags_id'] );
			
			if ($medication_info) {
				$this->error ['warning'] = 'Warning: Medication is already in enter!';
			}
		}
		
		/*
		 * if ($this->request->post['room'] != "" && $this->request->post['room'] != null) {
		 * if ($this->request->post['room_id'] == "0") {
		 * $this->error['warning'] = "Room does not exit!";
		 * }
		 * }
		 */
		
		/*
		 * if ($this->request->post['emp_tag_id'] != "" && $this->request->post['emp_tag_id'] != null) {
		 * $medication_task = $this->model_resident_resident->get_medicationyname22($this->request->post['emp_tag_id']);
		 *
		 * if (!empty($medication_task)) {
		 * $this->error['warning'] = 'Warning: Please complete Medication task!';
		 * }
		 * }
		 */
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function tagsmedicationsign2() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residenttagsmedicationsign2', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'form/form' );
				
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'resident/resident' );
				
				if ($this->request->get ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				// $data['keyword_file'] = MEDICATION_ICON;
				
				// $this->load->model('setting/keywords');
				
				// $keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
				
				/*
				 * $medicationf = "";
				 * foreach($this->session->data['medication'] as $key=>$medication){
				 *
				 * $medication_info = $this->model_resident_resident->get_medication($medication);
				 * $medicationf .= $medication_info['drug_name'].', ';
				 *
				 * }
				 */
				
				/*
				 * if($this->request->post['comments'] != null && $this->request->post['comments']){
				 * $comments = ' | '.$this->request->post['comments'];
				 * }
				 */
				
				if ($tag_info ['emp_first_name']) {
					// $emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
				} else {
					$emp_tag_id = $tag_info ['emp_tag_id'];
				}
				
				if ($tag_info) {
					$medication_tags .= $emp_tag_id . ' ';
				}
				
				$this->load->model ( 'api/permision' );
				$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
				$cname = $clientinfo ['name'];
				
				$description = '';
				// $description .= $keywordData2['keyword_name'];
				// $description .= ' | ';
				// $description .= ' Completed for | '.date('h:i A', strtotime($notetime)) .' ';
				$description .= ' Health Form updated | ';
				$description .= ' ' . $cname;
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
				}
				
				// $description .= ' | ';
				
				// $data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$medicationf . $comments;
				
				$data ['notes_description'] = $description;
				
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				$this->load->model ( 'api/temporary' );
				$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_tags_medication_id'] );
				
				$tempdata = array ();
				$tempdata = unserialize ( $temporary_info ['data'] );
				
				if ($tempdata ['room_id'] > 0) {
					$this->load->model ( 'setting/tags' );
					$this->model_setting_tags->updatetagroom ( $tempdata ['room_id'], $this->request->get ['tags_id'] );
				}
				
				$archive_tags_medication_id = $this->model_resident_resident->addTagsMedication ( $tempdata, $this->request->get ['tags_id'] );
				
				$this->model_notes_notes->updatetagsmedicinearchive1 ( $this->request->get ['tags_id'] );
				
				if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
					$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->get ['facilities_id'] );
					
					if (! empty ( $exist_note_info )) {
						$notes_id = $exist_note_info ['notes_id'];
						$device_unique_id = $exist_note_info ['device_unique_id'];
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->get ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
				} else {
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->get ['facilities_id'] );
					$device_unique_id = $this->request->post ['device_unique_id'];
				}
				/*
				 * $archive_tags_medication_id = $this->request->get['archive_tags_medication_id'];
				 */
				$mdata2 = array ();
				$mdata2 ['notes_id'] = $notes_id;
				$mdata2 ['tags_id'] = $this->request->get ['tags_id'];
				$mdata2 ['archive_tags_medication_id'] = $archive_tags_medication_id;
				
				$this->model_notes_notes->updatetagsmedicinearchive2 ( $mdata2 );
				
				$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_tags_medication_id'] );
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addmedform jsonAddNotes ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_addmedform', $activity_data2 );
		}
	}
	public function tagsmedicationsign() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residenttagsmedicationsign', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'resident/resident' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				if ($this->request->get ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				$timezone_name = $facilitytimezone;
				
				date_default_timezone_set ( $timezone_name );
				
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				$data ['keyword_file'] = MEDICATION_ICON;
				
				$this->load->model ( 'setting/keywords' );
				
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $this->request->get ['facilities_id'] );
				
				if ($tag_info ['emp_first_name']) {
					// $emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					// $emp_tag_id = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
					
					$this->load->model ( 'api/permision' );
					$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id, $tag_info );
					$cname = $clientinfo ['name'];
					
					$emp_tag_id = $cname;
				} else {
					$emp_tag_id = $tag_info ['emp_tag_id'];
				}
				
				if ($tag_info) {
					$medication_tags .= $emp_tag_id . ', ';
				}
				
				$this->load->model ( 'api/permision' );
				$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
				$cname = $clientinfo ['name'];
				
				$description = '';
				$description .= $keywordData2 ['keyword_name'];
				$description .= ' | ';
				$description .= ' Completed | ' . date ( 'h:i A', strtotime ( $notetime ) ) . ' ';
				$description .= ' Medication given to | ';
				$description .= ' ' . $cname;
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
				}
				// $description .= ' | ';
				
				// $data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$medicationf . $comments;
				
				$data ['notes_description'] = $description;
				
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				// var_dump($data);
				
				// die;
				if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
					$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->get ['facilities_id'] );
					
					if (! empty ( $exist_note_info )) {
						$notes_id = $exist_note_info ['notes_id'];
						$device_unique_id = $exist_note_info ['device_unique_id'];
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->get ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
				} else {
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->get ['facilities_id'] );
					$device_unique_id = $this->request->post ['device_unique_id'];
				}
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				if ($this->request->get ['medication_tags']) {
					$this->load->model ( 'setting/tags' );
					
					// var_dump($this->request->get['medication_tags']);
					
					$medication_tags1 = explode ( ',', $this->request->get ['medication_tags'] );
					
					if ($this->request->get ['facilities_id']) {
						$this->load->model ( 'facilities/facilities' );
						
						$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
						
						$this->load->model ( 'setting/timezone' );
						
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
						$facilitytimezone = $timezone_info ['timezone_value'];
					}
					$timezone_name = $facilitytimezone;
					
					date_default_timezone_set ( $timezone_name );
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					foreach ( $medication_tags1 as $medicationtag ) {
						$drugs = array ();
						$mdrug_info = $this->model_resident_resident->get_medication ( $medicationtag );
						
						if ($mdrug_info) {
							
							// $task_content = 'Resident '.$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$task_content = 'Resident ' . $cname;
							
							$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET notes_id = '" . $notes_id . "', locations_id ='" . $mdrug_info ['locations_id'] . "', task_type= '2', task_content = '" . $this->db->escape ( $task_content ) . "', signature= '" . $mdrug_info ['medication_signature'] . "', user_id= '" . $this->db->escape ( $mdrug_info ['medication_user_id'] ) . "', date_added = '" . $date_added . "', notes_pin = '" . $this->db->escape ( $mdrug_info ['medication_notes_pin'] ) . "', notes_type = '" . $this->request->post ['notes_type'] . "', task_time = '" . $mdrug_info ['task_time'] . "' , media_url = '" . $mdrug_info ['media_url'] . "', capacity = '" . $mdrug_info ['capacity'] . "', location_name = '" . $this->db->escape ( $mdrug_info ['location_name'] ) . "', location_type = '" . $this->db->escape ( $mdrug_info ['location_type'] ) . "', notes_task_type = '2', tags_id = '" . $tag_info ['tags_id'] . "', drug_name = '" . $this->db->escape ( $mdrug_info ['drug_name'] ) . "', dose = '" . $this->db->escape ( $mdrug_info ['dose'] ) . "', drug_type = '" . $mdrug_info ['drug_type'] . "', quantity = '" . $mdrug_info ['quantity'] . "', frequency = '" . $mdrug_info ['frequency'] . "', instructions = '" . $this->db->escape ( $mdrug_info ['instructions'] ) . "', count = '" . $mdrug_info ['count'] . "', createtask_by_group_id = '" . $mdrug_info ['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape ( $mdrug_info ['comments'] ) . "', medication_attach_url = '" . $mdrug_info ['medication_attach_url'] . "',medication_file_upload='1' , tags_medication_details_id = '" . $mdrug_info ['tags_medication_details_id'] . "' , tags_medication_id = '" . $mdrug_info ['tags_medication_id'] . "'  ";
							
							$this->db->query ( $sql2 );
							$notes_by_task_id = $this->db->getLastId ();
						}
					}
					
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					if ($tag_info ['emp_tag_id'] != null && $tag_info ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
						$tadata = array ();
						$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
					}
				}
				
				$sql = "update `" . DB_PREFIX . "notes` set task_type = '2',notes_conut='0' where notes_id='" . $notes_id . "'";
				$this->db->query ( $sql );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addmedform jsonAddNotes ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_addmedform', $activity_data2 );
		}
	}
	protected function validateForm23() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		if ($this->request->post ['user_id'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = $this->language->get ( 'error_required' );
			}
		}
		
		if ($this->request->post ['select_one'] == '') {
			$this->error ['select_one'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['select_one'] == '1') {
			if ($this->request->post ['notes_pin'] == '') {
				$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function jsoncustomsForm2() {
		$url2 = "";
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$new_form = '2';
			$tags_id = $this->request->get ['tags_id'];
			$url2 .= '&new_form=2';
		} else {
			$new_form = '1';
			$tags_id = '';
			$url2 .= '&new_form=1';
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = '';
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
			$medication_tags = $this->request->get ['medication_tags'];
		} else {
			$medication_tags = '';
		}
		if ($this->request->get ['archive_tags_medication_id'] != null && $this->request->get ['archive_tags_medication_id'] != "") {
			$url2 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
			$archive_tags_medication_id = $this->request->get ['archive_tags_medication_id'];
		} else {
			$archive_tags_medication_id = '';
		}
		
		if ($this->request->get ['addmedicine'] == "2") {
			$medform_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/tagsmedicationsign2', '' . $url2, 'SSL' ) );
		} else {
			$medform_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/tagsmedicationsign', '' . $url2, 'SSL' ) );
		}
		
		if ($this->request->get ['tags_id']) {
			$tags_id = $this->request->get ['tags_id'];
		} elseif ($this->request->get ['emp_tag_id']) {
			$tags_id = $this->request->get ['emp_tag_id'];
		}
		if ($tags_id != null && $tags_id != "") {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			$name = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$name = '';
		}
		
		if ($new_form == '1') {
			$cancel_url = ''; // str_replace('&amp;', '&',$this->url->link('services/resident/tagsmedication', '' . $url2, 'SSL'));
		}
		
		if ($new_form == '2') {
			$cancel_url = ''; // str_replace('&amp;', '&',$this->url->link('services/resident/tagsmedication', '' . $url2, 'SSL'));
		}
		
		$this->data ['facilitiess'] [] = array (
				'task_form' => '',
				'emp_tag_id' => '',
				'archive_tags_id' => '',
				'tags_id' => $tags_id,
				'name' => $name,
				'new_form' => $new_form,
				'facilities_id' => $facilities_id,
				'medication_tags' => $medication_tags,
				'archive_tags_medication_id' => $archive_tags_medication_id,
				'signature_url' => $medform_url,
				'cancel_url' => $cancel_url 
		);
		
		if ($this->request->get ['is_html'] == '1') {
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/form/jsoncustom.php';
			$this->response->setOutput ( $this->render () );
		} else {
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		}
	}
	
	
	public function jsonrolecall() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residentjsonrolecall', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			/*
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'resident/resident' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				if ($this->request->post ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->load->model ( 'setting/tags' );
				
				if ($this->request->post ['keyword_id']) {
					$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
					
					$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
					$data ['tags_id'] = $tag_info ['tags_id'];
					
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->post ['keyword_id'] );
					
					$data ['keyword_file'] = $keywordData2 ['keyword_image'];
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					if ($this->request->post ['customlistvalues_id']) {
						
						$this->load->model ( 'notes/notes' );
						$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
						
						$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
						
						$description1 = ' | ' . $customlistvalues_name;
					}
					
					if ($this->request->post ['customlistvalues_ids']) {
						
						$this->load->model ( 'notes/notes' );
						
						foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
							
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
							
							$customlistvalues_name = $custom_info ['customlistvalues_name'];
							
							$description1 .= ' | ' . $customlistvalues_name;
						}
						
						$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
					}
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					// $taskcontent = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
					
					$this->load->model ( 'api/permision' );
					$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
					$taskcontent = $clientinfo ['name'];
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $taskcontent . '' . $description1 . $comments;
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					$data ['facilitytimezone'] = $facilitytimezone;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					
					if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
						$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
						
						if (! empty ( $exist_note_info )) {
							$notes_id = $exist_note_info ['notes_id'];
							$device_unique_id = $exist_note_info ['device_unique_id'];
						} else {
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
							$device_unique_id = $this->request->post ['device_unique_id'];
						}
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
					
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 = array ();
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				}
				
				if ($this->request->post ['discharge'] == "1") {
				if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {	
					$this->load->model ( 'createtask/createtask' );
					$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $this->request->post ['tags_id'] );
					
					if ($alldatas != NULL && $alldatas != "") {
						foreach ( $alldatas as $alldata ) {
							$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
							$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
							$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
							$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
						}
					}
					
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
					
					$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
					$data ['tags_id'] = $tag_info ['tags_id'];
					
					$data ['keyword_file'] = DISCHARGE_ICON;
					
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $this->request->post ['facilities_id'] );
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					if ($this->request->post ['customlistvalues_id']) {
						
						$this->load->model ( 'notes/notes' );
						$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
						
						$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
						
						$description1 = ' | ' . $customlistvalues_name;
					}
					
					if ($this->request->post ['customlistvalues_ids']) {
						
						$this->load->model ( 'notes/notes' );
						
						foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
							
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
							
							$customlistvalues_name = $custom_info ['customlistvalues_name'];
							
							$description1 .= ' | ' . $customlistvalues_name;
						}
						
						$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
					}
					
					// $roleCall = "Discharged to";
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$this->load->model ( 'api/permision' );
					$clientinfo = $this->model_api_permision->getclientinfo ( $this->request->post ['facilities_id'], $tag_info );
					$cname = $clientinfo ['name'];
					
					$taskcontent = $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $taskcontent . '' . $description1 . $comments;
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					$data ['facilitytimezone'] = $facilitytimezone;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					
					if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
						$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
						
						if (! empty ( $exist_note_info )) {
							$notes_id = $exist_note_info ['notes_id'];
							$device_unique_id = $exist_note_info ['device_unique_id'];
						} else {
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
							$device_unique_id = $this->request->post ['device_unique_id'];
						}
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 = array ();
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
					
					$this->load->model ( 'setting/tags' );
					$this->model_setting_tags->addcurrentTagarchive ( $this->request->post ['tags_id'] );
					$this->model_setting_tags->updatecurrentTagarchive ( $this->request->post ['tags_id'], $notes_id );
					
					$this->model_resident_resident->updateDischargeTag ( $this->request->post ['tags_id'], $date_added );
				}
				}
				
				if ($this->request->post ['is_switch'] == "1") {
					if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
					
					$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
					$data ['tags_id'] = $tag_info ['tags_id'];
					
					$this->load->model ( 'facilities/facilities' );
					$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					$unique_id = $facility ['customer_key'];
					
					$this->load->model ( 'customer/customer' );
					$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
					
					if (! empty ( $customer_info ['setting_data'] )) {
						$customers = unserialize ( $customer_info ['setting_data'] );
						
						if ($this->request->post ['facility_inout'] == '0') {
							if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
								$roleCall2 = $customers ['in_name'];
							} else {
								$roleCall2 = ' returned to the Cell ';
							}
						}
						
						if ($this->request->post ['facility_inout'] == '1') {
							if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
								$roleCall2 = $customers ['out_name'];
							} else {
								$roleCall2 = ' left the Cell ';
							}
						}
					} else {
						if ($this->request->post ['facility_inout'] == '0') {
							$roleCall2 = ' returned to the Cell ';
						}
						
						if ($this->request->post ['facility_inout'] == '1') {
							$roleCall2 = ' left the Cell ';
						}
					}
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					if ($this->request->post ['customlistvalues_id']) {
						
						$this->load->model ( 'notes/notes' );
						$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
						
						$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
						
						$description1 = ' | ' . $customlistvalues_name;
					}
					
					if ($this->request->post ['customlistvalues_ids']) {
						
						$this->load->model ( 'notes/notes' );
						
						foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
							
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
							$description1 .= ' | ' . $custom_info ['customlistvalues_name'];
						}
						
						$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
					}
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$this->load->model ( 'api/permision' );
					$clientinfo = $this->model_api_permision->getclientinfo ( $this->request->post ['facilities_id'], $tag_info );
					$cname = $clientinfo ['name'];
					
					$taskcontent = $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'];
					
					
					$status_name = array();
					$statusname = "";
					$substatus_ids_arr = explode(',',$this->request->post['substatus_ids']);
						
					foreach($substatus_ids_arr AS $val){
						//$sdata = $this->model_setting_tags->getTagStatus($val);
						$status_name[] = $val;
					}
					
					if(!empty($status_name)){
						$statusname = ' | '.implode(' | ',$status_name);
					}
					
					$data ['notes_description'] = $taskcontent . ' | ' . $form_name . ' status changed to | ' . $roleCall2 . $description1 . $comments. $statusname;
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					$data ['facilitytimezone'] = $facilitytimezone;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					$data ['substatus_ids'] = $this->request->post ['substatus_ids'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
						$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
						
						if (! empty ( $exist_note_info )) {
							$notes_id = $exist_note_info ['notes_id'];
							$device_unique_id = $exist_note_info ['device_unique_id'];
						} else {
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
							$device_unique_id = $this->request->post ['device_unique_id'];
						}
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
					
					if ($this->request->post ['facilities_id'] != $tag_info ['facilities_id']) {
						$notes_id1 = $this->model_notes_notes->jsonaddnotes ( $data, $tag_info ['facilities_id'] );
					}
					
					$cdata2 = array ();
					$cdata2 ['modify_date'] = $date_added;
					$cdata2 ['notes_id'] = $notes_id;
					$cdata2 ['notes_id1'] = $notes_id1;
					$cdata2 ['tags_id'] = $this->request->post ['tags_id'];
					$cdata2 ['facility_inout'] = $this->request->post ['facility_inout'];
					$cdata2 ['substatus_ids'] = $this->request->post ['substatus_ids'];
					
					$this->model_resident_resident->updatetagrolecall2 ( $this->request->post ['tags_id'], $cdata2 );
					
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 = array ();
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				}
				}
				
				
				
				if ($this->request->post ['role_call']) {
				if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
					
					$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
					$data ['tags_id'] = $tag_info ['tags_id'];
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					if ($this->request->post ['customlistvalues_id']) {
						
						$this->load->model ( 'notes/notes' );
						$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
						
						$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
						
						$description1 = ' | ' . $customlistvalues_name;
					}
					
					
					if($this->request->post ['is_web']== '1'){
						
						if($this->request->post ['customlistvalues_ids']){
						
						$this->load->model ( 'notes/notes' );
						$cids = explode(",",$this->request->post ['customlistvalues_ids']);
						
						foreach ( $cids as $customlistvalues_id ) {
							
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
							$description1 .= ' | ' . $custom_info ['customlistvalues_name'];
						}
						
						$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
						}
						
					
					}else {
						
						if($this->request->post ['customlistvalues_ids']){
						$this->load->model ( 'notes/notes' );
						
						foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
							
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
							$description1 .= ' | ' . $custom_info ['customlistvalues_name'];
						}
						
						$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
						}
					}
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$this->load->model ( 'api/permision' );
					$clientinfo = $this->model_api_permision->getclientinfo ( $this->request->post ['facilities_id'], $tag_info );
					$cname = $clientinfo ['name'];
					
					$taskcontent = $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'];
					
					$this->load->model ( 'notes/clientstatus' );
					// $clientstatus_info = $this->model_notes_clientstatus->getclientstatus($tag_info['role_call']);
					$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag_info ['role_call'] );
					$roleCall66 = $clientstatus_info ['name'];
					
					
				
					if($this->request->post ['role_call'] != $tag_info ['role_call']){
					
					$caltime = " | ";
					$caltime1 = "";
					$status_total_time = 0;
					// echo '<pre>'; print_r($clientstatus_info); echo '</pre>';
					
					if ($clientstatus_info ['track_time'] == 1) {
						$this->load->model ( 'notes/notes' );
						$notes_data = $this->model_notes_notes->getnotes ( $tag_info ['notes_id'] );
						// echo '<pre>'; print_r($notes_data); echo '</pre>';
						$current_date = date ( 'Y-m-d H:i:s' );
						$start_date = new DateTime ( $notes_data ['date_added'] );
						$since_start = $start_date->diff ( new DateTime ( $current_date ) );
						
						if ($since_start->y > 0) {
							$caltime .= $since_start->y . ' years ';
							$status_total_time = 60 * 24 * 365 * $since_start->y;
						}
						
						if ($since_start->m > 0) {
							$caltime .= $since_start->m . ' months ';
							$status_total_time += 60 * 24 * 30 * $since_start->m;
						}
						
						if ($since_start->d > 0) {
							$caltime .= $since_start->d . ' days ';
							$status_total_time += 60 * 24 * $since_start->d;
						}
						
						if ($since_start->h > 0) {
							$caltime .= $since_start->h . ' hours ';
							$status_total_time += 60 * $since_start->h;
						}
						
						if ($since_start->i > 0) {
							$caltime .= $since_start->i . ' minutes ';
							$status_total_time += $since_start->i;
						}
						
						// $caltime.= ' in '.$roleCall66 . ' | ';
						$caltime1 .= $roleCall66;
					} else {
						$caltime1 .= $roleCall66;
					}
					
					$clientstatus_info2 = $this->model_notes_clientstatus->getclientstatus ( $this->request->post ['role_call'] );
					
					$rule_action_content = unserialize($clientstatus_info2['rule_action_content']);
		
					if($rule_action_content['custom_description'] != null && $rule_action_content['custom_description'] != ""){
						$comments .= ' | ' .nl2br($rule_action_content['custom_description']);
					}
					
					if ($this->request->post ['facility_move_id'] != null && $this->request->post ['facility_move_id'] != "") {
						$mfacility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facility_move_id'] );
						$facilitym = $mfacility ['facility'];
					} else {
						$facilitym = "";
					}
					
					// $roleCall2 = $clientstatus_info2['name'].' '.$facilitym;
					$roleCall2 = $clientstatus_info2 ['name'];
					
					$status_name = array();
					$statusname = "";
					$substatus_ids_arr = explode(',',$this->request->post['substatus_ids']);
						
					foreach($substatus_ids_arr AS $val){
						//$sdata = $this->model_setting_tags->getTagStatus($val);
						$status_name[] = $val;
					}
					
					if(!empty($status_name)){
						$statusname = ' | '.implode(' | ',$status_name);
					}
					
					if ($this->request->post['escort_name'] != null && $this->request->post['escort_name'] != "") {
						
						$escorted = ' | escorted by ' . $this->request->post ['escort_name'];
					}
					
					if ($clientstatus_info2 ['type'] == '4') {
						
						if ($this->request->post ['movement_room'] != null && $this->request->post ['movement_room'] != "") {
							
							$this->load->model ( 'setting/locations' );
							
							$roominfo = $this->model_setting_locations->getlocation ( $this->request->post ['movement_room'] );
						} else {
							$this->load->model ( 'setting/locations' );
							
							$roominfo = $this->model_setting_locations->getlocation ( $tag_info ['movement_room'] );
						}
						
						if ($this->request->post ['mfacilities_id'] != null && $this->request->post ['mfacilities_id'] != "") {
							$this->load->model ( 'facilities/facilities' );
							
							$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
							$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->request->post ['mfacilities_id'] );
							
							$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
						} else {
							$this->load->model ( 'facilities/facilities' );
							
							$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
							$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facility_move_id'] );
							
							$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
						}
						
						$data ['notes_description'] = $roleCall2 . " completed | " . $taskcontent . ' ' . $fname . ' | ' . $roominfo ['location_name'] . ' ' . $caltime . $escorted. $description1 . $comments.$statusname;
					} else {
						$data ['notes_description'] = $taskcontent . ' status changed ' . $caltime1 . ' to | ' . $roleCall2 . $caltime . $escorted. $description1 . $comments.$statusname;
					}
					
					if ($clientstatus_info2 ['type'] == '2') {
						$data ['status_total_time'] = $status_total_time;
					}else{
						$data ['status_total_time'] = 0;
					}
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					$data ['facilitytimezone'] = $facilitytimezone;
					
					$data ['tag_status_id'] = $this->request->post ['role_call'];
					$data ['substatus_ids'] = $this->request->post ['substatus_ids'];
					$data ['move_notes_id'] = $tag_info ['notes_id'];
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					
					if ($this->request->post ['movement_room'] != null && $this->request->post ['movement_room'] != "") {
						$movement_room1 = $this->request->post ['movement_room'];
					} else {
						if ($tag_info ['movement_room'] != 0) {
							$movement_room1 = $tag_info ['movement_room'];
						}
					}
					if($movement_room1 != $tag_info ['movement_room']){
						$data['manual_movement'] = 1;
					}
					
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
						$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
						
						if (! empty ( $exist_note_info )) {
							$notes_id = $exist_note_info ['notes_id'];
							$device_unique_id = $exist_note_info ['device_unique_id'];
						} else {
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
							$device_unique_id = $this->request->post ['device_unique_id'];
						}
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
					
					
					if($tag_info ['notes_id'] > 0){
						$cdatam = array ();
						$cdatam ['notes_id'] = $tag_info ['notes_id'];
						$cdatam ['move_notes_id'] = $notes_id;
						$cdatam ['tags_id'] = $tag_info ['tags_id'];
						
						//if ($clientstatus_info2 ['type'] == '2') {
							$cdatam ['status_total_time'] = $status_total_time;
						//}else{
						//	$cdatam ['status_total_time'] = 0;
						//}
						
						
						$this->model_resident_resident->updateclientStatusnotes ( $cdatam );
					}
					
					if ($clientstatus_info2 ['type'] == '4') {
						
						$movement_room = "";
						$facility_move_id = "";
						if ($this->request->post ['movement_room'] != null && $this->request->post ['movement_room'] != "") {
							
							$movement_room = $this->request->post ['movement_room'];
						} else {
							
							if ($tag_info ['movement_room'] != 0) {
								$movement_room = $tag_info ['movement_room'];
							}
						}
						
						if ($this->request->post ['mfacilities_id'] != null && $this->request->post ['mfacilities_id'] != "") {
							
							$facility_move_id = $this->request->post ['mfacilities_id'];
						} else {
							if ($tag_info ['facility_move_id'] != 0) {
								$facility_move_id = $tag_info ['facility_move_id'];
							}
							// $facility_move_id = $tag_info ['facility_move_id'];
						}
						
						$scdata = array ();
						$scdata ['tags_id'] = $tag_info ['tags_id'];
						$scdata ['facilities_id'] = $this->request->post ['facilities_id'];
						$scdata ['modify_date'] = $date_added;
						$scdata ['notes_id'] = $notes_id;
						$scdata ['facility_move_id'] = $facility_move_id;
						$scdata ['movement_room'] = $movement_room;
						$this->model_resident_resident->updateclientmovement ( $scdata );
					}
					
					$cdata2 = array ();
					$cdata2 ['modify_date'] = $date_added;
					$cdata2 ['notes_id'] = $notes_id;
					$cdata2 ['tags_id'] = $tag_info ['tags_id'];
					$cdata2 ['facility_inout'] = $this->request->post ['facility_inout'];
					$cdata2 ['substatus_ids'] = $this->request->post ['substatus_ids'];
					
					$this->model_resident_resident->updatetagrolecall2 ( $tag_info ['tags_id'], $cdata2 );
					
					$cdata = array ();
					$cdata ['modify_date'] = $date_added;
					$cdata ['notes_id'] = $notes_id;
					$cdata ['tags_id'] = $tag_info ['tags_id'];
					$cdata ['facility_move_id'] = $this->request->post ['facility_move_id'];
					$cdata ['tag_status_id'] = $this->request->post ['role_call'];
					
					$this->model_resident_resident->updateclientnotes ( $cdata );
					
					if($tag_info ['notes_id'] > 0){
						$move_notes_id = $tag_info ['notes_id'];
					}else{
						$move_notes_id = $notes_id;
					}
					//if ($clientstatus_info ['track_time'] == 1) {
						$tmdata = array ();
						$tmdata ['notes_id'] = $notes_id;
						$tmdata ['facilities_id'] = $tag_info ['facilities_id'];
						$tmdata ['unique_id'] = $facilities_info ['customer_key'];
						$tmdata ['tags_id'] = $tag_info ['tags_id'];
						$tmdata ['tag_status_id'] = $clientstatus_info ['tag_status_id'];
						$tmdata ['new_tag_status_id'] = $clientstatus_info2 ['tag_status_id'];
						$tmdata ['move_notes_id'] = $move_notes_id;
						$tmdata ['keyword_id'] = '';
						$tmdata ['types'] = 1;
						
						$tmdata ['years'] = $since_start->y;
						$tmdata ['months'] = $since_start->m;
						$tmdata ['days'] = $since_start->d;
						$tmdata ['hours'] = $since_start->h;
						$tmdata ['minutes'] = $since_start->i;
						
						$tmdata ['date_added'] = date ( 'Y-m-d H:i:s' );
						$this->model_resident_resident->addtracktime ( $tmdata );
					//}
					
					$this->model_resident_resident->updatetagrolecall ( $this->request->post ['tags_id'], $this->request->post ['role_call'] );
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 = array ();
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
					
					
					}
				}
				}
				
				$jsonData = stripslashes ( html_entity_decode ( $_REQUEST ['role_calltagsids'] ) );
				$arr = json_decode ( $jsonData, true );
				
				$role_calltagsids = $this->groupArray ( $arr, "facilities_id", false, true );
				
				// var_dump($role_calltagsids);
				
				if (! empty ( $role_calltagsids )) {
					$this->load->model ( 'facilities/facilities' );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$tagnamesss = "";
					$tagnamesss_out = "";
					$tags_id_list = array ();
					foreach ( $role_calltagsids as $facilities_id => $rolecalls ) {
						if($facilities_id != null && $facilities_id != ""){
							$tagname = "";
							$tagname2 = "";
							
							$girl1 = 0;
							$boy1 = 0;
							$total1 = '';
							
							$girl12 = 0;
							$boy12 = 0;
							$tagname111 = array ();
							$tags_id_list = array ();
							// var_dump($facilities_id);
							
							foreach ( $rolecalls as $rolecall ) {
								if($rolecall['role_call'] != $tag_info ['role_call']){
								// var_dump($rolecall['role_call']);
								$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
								
								// if($rolecall['role_call'] == '1'){
								
								if ($rolecall ['role_call'] == $tag_info ['role_call']) {
									$tagnamesss = 1;
								} else {
									$tagnamesss = 2;
								}
								
								$emp_tag_id = $tag_info ['emp_tag_id'];
								$tags_id = $tag_info ['tags_id'];
								// $tags_id_list[] = $tag_info['tags_id'];
								$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
								$data ['tags_id'] = $tag_info ['tags_id'];
								
								$data ['tag_status_id'] = $rolecall ['role_call'];
								
								if ($rolecall ['role_call'] != null && $rolecall ['role_call'] != "") {
									$data ['move_notes_id'] = $tag_info ['notes_id'];
								}
								
								$this->load->model ( 'setting/locations' );
								$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
								
								$this->load->model ( 'api/permision' );
								$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id, $tag_info );
								$cname = $clientinfo ['name'];
								
								$tagname = $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
												   // $tagname .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].' | ';
								if ($rolecall ['role_call'] != null && $rolecall ['role_call'] != "") {
									$this->model_resident_resident->updatetagrolecall ( $rolecall ['tags_id'], $rolecall ['role_call'] );
								}
								
								if ($rolecall ['discharge'] == '1') {
									$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '1', $date_added );
									$this->load->model ( 'createtask/createtask' );
									$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tag_info ['tags_id'] );
									
									if ($alldatas != NULL && $alldatas != "") {
										foreach ( $alldatas as $alldata ) {
											$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
											$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
											$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
											$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
										}
									}
									
									$this->model_setting_tags->addcurrentTagarchive ( $tag_info ['tags_id'] );
									$this->model_setting_tags->updatecurrentTagarchive ( $tag_info ['tags_id'], $notes_id );
									
									$this->model_resident_resident->updateDischargeTag ( $tag_info ['tags_id'], $date_added );
								}
								// }
								
								$description1 = "";
								if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
									$comments = ' | ' . $this->request->post ['comments'];
								}
								
								if ($this->request->post ['customlistvalues_id']) {
									
									$this->load->model ( 'notes/notes' );
									$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
									
									$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
									
									$description1 = ' | ' . $customlistvalues_name;
								}
								
								if ($this->request->post ['customlistvalues_ids']) {
									
									$this->load->model ( 'notes/notes' );
									
									foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
										
										$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
										
										$description1 .= ' | ' . $custom_info ['customlistvalues_name'];
									}
									
									$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
								}
								
								$this->load->model ( 'notes/clientstatus' );
								// $clientstatus_info = $this->model_notes_clientstatus->getclientstatus($tag_info['role_call']);
								$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag_info ['role_call'] );
								$roleCall1 = $clientstatus_info ['name'];
								
								$caltime = " | ";
								$caltime1 = "";
								
								// echo '<pre>'; print_r($clientstatus_info); echo '</pre>';
								$status_total_time = 0;
								if ($clientstatus_info ['track_time'] == 1) {
									$this->load->model ( 'notes/notes' );
									$notes_data = $this->model_notes_notes->getnotes ( $tag_info ['notes_id'] );
									// echo '<pre>'; print_r($notes_data); echo '</pre>';
									$current_date = date ( 'Y-m-d H:i:s' );
									$start_date = new DateTime ( $notes_data ['date_added'] );
									$since_start = $start_date->diff ( new DateTime ( $current_date ) );
									
									if ($since_start->y > 0) {
										$caltime .= $since_start->y . ' years ';
										$status_total_time = 60 * 24 * 365 * $since_start->y;
									}
									
									if ($since_start->m > 0) {
										$caltime .= $since_start->m . ' months ';
										$status_total_time += 60 * 24 * 30 * $since_start->m;
									}
									
									if ($since_start->d > 0) {
										$caltime .= $since_start->d . ' days ';
										$status_total_time += 60 * 24 * $since_start->d;
									}
									
									if ($since_start->h > 0) {
										$caltime .= $since_start->h . ' hours ';
										$status_total_time += 60 * $since_start->h;
									}
									
									if ($since_start->i > 0) {
										$caltime .= $since_start->i . ' minutes ';
										$status_total_time += $since_start->i;
									}
									
									// $caltime.= ' in '.$roleCall1 . ' to ';
									$caltime1 .= $roleCall1 . ' to ';
								} else {
									if ($rolecall ['role_call'] != null && $rolecall ['role_call'] != "") {
										$caltime1 .= $roleCall1 . ' to ';
									}
								}
								
								$roleCalldi = "";
								if ($rolecall ['facility_inout'] != '' && $rolecall ['facility_inout'] != null) {
									$this->load->model ( 'facilities/facilities' );
									$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
									$unique_id = $facility ['customer_key'];
									
									$this->load->model ( 'customer/customer' );
									$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
									
									if (! empty ( $customer_info ['setting_data'] )) {
										$customers = unserialize ( $customer_info ['setting_data'] );
										
										if ($rolecall ['facility_inout'] == '0') {
											if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
												$roleCalldi = $customers ['in_name'];
											} else {
												$roleCalldi = ' returned to the Cell ';
											}
										}
										
										if ($rolecall ['facility_inout'] == '1') {
											if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
												$roleCalldi = $customers ['out_name'];
											} else {
												$roleCalldi = ' left the Cell ';
											}
										}
									} else {
										if ($rolecall ['facility_inout'] == '0') {
											$roleCalldi = ' returned to the Cell ';
										}
										
										if ($rolecall ['facility_inout'] == '1') {
											$roleCalldi = ' left the Cell ';
										}
									}
								}
								
								if ($rolecall ['role_call'] == null && $rolecall ['role_call'] == "") {
									$caltime1 .= ' to | ' . $roleCalldi;
								}
								
								$this->load->model ( 'notes/clientstatus' );
								$clientstatus_info2 = $this->model_notes_clientstatus->getclientstatus ( $rolecall ['role_call'] );
								
								$rule_action_content = unserialize($clientstatus_info2['rule_action_content']);
								$comments1 = "";
								if($rule_action_content['custom_description'] != null && $rule_action_content['custom_description'] != ""){
									$comments1 .= ' | ' .nl2br($rule_action_content['custom_description']);
								}
								
								if ($this->request->post ['facility_move_id'] != null && $this->request->post ['facility_move_id'] != "") {
									$this->load->model ( 'facilities/facilities' );
									$mfacility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facility_move_id'] );
									$facilitym = $mfacility ['facility'];
								} else {
									$facilitym = "";
								}
								
								// $roleCallname = $clientstatus_info2['name'] . ' ' .$facilitym;
								$roleCallname = $clientstatus_info2 ['name'];
								
								$status_name = array();
								$statusname = "";
								$substatus_ids_arr = explode(',',$this->request->post['substatus_ids']);
									
								foreach($substatus_ids_arr AS $val){
									//$sdata = $this->model_setting_tags->getTagStatus($val);
									$status_name[] = $val;
								}
								
								if(!empty($status_name)){
									$statusname = ' | '.implode(' | ',$status_name);
								}
								
								if ($rolecall ['discharge'] == '1') {
									$data ['keyword_file'] = DISCHARGE_ICON;
									
									$this->load->model ( 'setting/keywords' );
									$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
									
									$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $tagname . '' . $description1 . $comments .$comments1.$statusname;
								} else {
									
									if ($clientstatus_info2 ['type'] == '4') {
										
										if ($this->request->post ['movement_room'] != null && $this->request->post ['movement_room'] != "") {
											
											$this->load->model ( 'setting/locations' );
											
											$roominfo = $this->model_setting_locations->getlocation ( $this->request->post ['movement_room'] );
										} else {
											$this->load->model ( 'setting/locations' );
											
											$roominfo = $this->model_setting_locations->getlocation ( $tag_info ['movement_room'] );
										}
										
										if ($this->request->post ['mfacilities_id'] != null && $this->request->post ['mfacilities_id'] != "") {
											$this->load->model ( 'facilities/facilities' );
											
											$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
											$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->request->post ['mfacilities_id'] );
											
											$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
										} else {
											$this->load->model ( 'facilities/facilities' );
											
											$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
											$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facility_move_id'] );
											
											$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
										}
										
										$data ['notes_description'] = $roleCallname . " completed | " . $taskcontent . ' ' . $fname . ' | ' . $roominfo ['location_name'] . ' ' . $caltime . $escorted . $description1 . $comments.$comments1.$statusname;
									} else {
										$data ['notes_description'] = $tagname . ' status changed ' . $caltime1 . $roleCallname . $caltime . $description1 . $comments.$comments1.$statusname;
									}
								}
								
								// $data ['tags_id_list'] = $tags_id_list;
								if ($clientstatus_info2 ['type'] == '2') {
									$data ['status_total_time'] = $status_total_time;
								}else{
									$data ['status_total_time'] = 0;
								}
								$data ['date_added'] = $date_added;
								$data ['note_date'] = $date_added;
								$data ['notetime'] = $notetime;
								$data ['facilitytimezone'] = $facilitytimezone;
								
								$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
								$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
								$data ['substatus_ids'] = $this->request->post ['substatus_ids'];
								
								if ($this->request->post ['movement_room'] != null && $this->request->post ['movement_room'] != "") {
									$movement_room1 = $this->request->post ['movement_room'];
								} else {
									if ($tag_info ['movement_room'] != 0) {
										$movement_room1 = $tag_info ['movement_room'];
									}
								}
								if($movement_room1 != $tag_info ['movement_room']){
									$data['manual_movement'] = 1;
								}
								
								if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
									$data ['is_android'] = $this->request->post ['is_android'];
								} else {
									$data ['is_android'] = '1';
								}
								
								$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
								
								
								if($tag_info ['notes_id'] > 0){
									$cdatam = array ();
									$cdatam ['notes_id'] = $tag_info ['notes_id'];
									$cdatam ['move_notes_id'] = $notes_id;
									$cdatam ['tags_id'] = $tag_info ['tags_id'];
									//if ($clientstatus_info2 ['type'] == '2') {
										$cdatam ['status_total_time'] = $status_total_time;
									//}else{
									//	$cdatam ['status_total_time'] = 0;
									//}
									
									$this->model_resident_resident->updateclientStatusnotes ( $cdatam );
								}
								
								if ($facilities_id != $rolecall ['facility_move_id']) {
									if($rolecall ['facility_move_id'] > 0){
										$notes_id1 = $this->model_notes_notes->jsonaddnotes ( $data, $rolecall ['facility_move_id'] );
									}
								}
								
								$device_unique_id = $this->request->post ['device_unique_id'];
								
								if ($clientstatus_info2 ['type'] == '4') {
									
									$movement_room = "";
									$facility_move_id = "";
									if ($this->request->post ['movement_room'] != null && $this->request->post ['movement_room'] != "") {
										
										$movement_room = $this->request->post ['movement_room'];
									} else {
										
										if ($tag_info ['movement_room'] != 0) {
											$movement_room = $tag_info ['movement_room'];
										}
									}
									
									if ($this->request->post ['mfacilities_id'] != null && $this->request->post ['mfacilities_id'] != "") {
										
										$facility_move_id = $this->request->post ['mfacilities_id'];
									} else {
										if ($tag_info ['facility_move_id'] != 0) {
											$facility_move_id = $tag_info ['facility_move_id'];
										}
										// $facility_move_id = $tag_info ['facility_move_id'];
									}
									
									$scdata = array ();
									$scdata ['tags_id'] = $tag_info ['tags_id'];
									$scdata ['facilities_id'] = $facilities_id;
									$scdata ['modify_date'] = $date_added;
									$scdata ['notes_id'] = $notes_id;
									$scdata ['facility_move_id'] = $facility_move_id;
									$scdata ['movement_room'] = $movement_room;
									
									$this->model_resident_resident->updateclientmovement ( $scdata );
								}
								
								$cdata2 = array ();
								$cdata2 ['modify_date'] = $date_added;
								$cdata2 ['notes_id'] = $notes_id;
								$cdata2 ['notes_id1'] = $notes_id1;
								$cdata2 ['tags_id'] = $tag_info ['tags_id'];
								$cdata2 ['facility_inout'] = $rolecall ['facility_inout'];
								$cdata2 ['substatus_ids'] = $this->request->post ['substatus_ids'];
								
								$this->model_resident_resident->updatetagrolecall2 ( $tag_info ['tags_id'], $cdata2 );
								
								if ($rolecall ['role_call'] != null && $rolecall ['role_call'] != "") {
									$cdata = array ();
									$cdata ['modify_date'] = $date_added;
									$cdata ['notes_id'] = $notes_id;
									$cdata ['tags_id'] = $tag_info ['tags_id'];
									$cdata ['facility_move_id'] = $rolecall ['facility_move_id'];
									$cdata ['tag_status_id'] = $rolecall ['role_call'];
									$this->model_resident_resident->updateclientnotes ( $cdata );
								}
								
								
								if($tag_info ['notes_id'] > 0){
									$move_notes_id = $tag_info ['notes_id'];
								}else{
									$move_notes_id = $notes_id;
								}
								
								//if ($clientstatus_info ['track_time'] == 1) {
									$tmdata = array ();
									$tmdata ['notes_id'] = $notes_id;
									$tmdata ['facilities_id'] = $tag_info ['facilities_id'];
									$tmdata ['unique_id'] = $facilities_info ['customer_key'];
									$tmdata ['tags_id'] = $tag_info ['tags_id'];
									$tmdata ['tag_status_id'] = $clientstatus_info ['tag_status_id'];
									$tmdata ['new_tag_status_id'] = $clientstatus_info2 ['tag_status_id'];
									$tmdata ['move_notes_id'] = $move_notes_id;
									$tmdata ['keyword_id'] = '';
									$tmdata ['types'] = 1;
									
									$tmdata ['years'] = $since_start->y;
									$tmdata ['months'] = $since_start->m;
									$tmdata ['days'] = $since_start->d;
									$tmdata ['hours'] = $since_start->h;
									$tmdata ['minutes'] = $since_start->i;
									
									$tmdata ['date_added'] = date ( 'Y-m-d H:i:s' );
									$this->model_resident_resident->addtracktime ( $tmdata );
								//}
								
								$this->load->model ( 'api/facerekognition' );
								$fre_array2 = array ();
								$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
								$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
								$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
								$fre_array2 ['facilities_id'] = $facilities_id;
								$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
								$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
								$fre_array2 ['notes_id'] = $notes_id;
								$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
							}
							
							}
						}
					}
				}
				
				if ($this->request->post ['role_calls']) {
					$tagname = "";
					
					$girl1 = 0;
					$boy1 = 0;
					$total1 = '';
					
					$girl12 = 0;
					$boy12 = 0;
					$tagname111 = array ();
					foreach ( $this->request->post ['role_calls'] as $key => $rolecall ) {
						
						$tag_info = $this->model_setting_tags->getTag ( $key );
						
						if ($rolecall ['role_call'] == '1') {
							$this->model_resident_resident->updatetagrolecall ( $key, $rolecall ['role_call'] );
							
							$emp_tag_id = $tag_info ['emp_tag_id'];
							$tags_id = $tag_info ['tags_id'];
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$this->load->model ( 'api/permision' );
							$clientinfo = $this->model_api_permision->getclientinfo ( $this->request->post ['facilities_id'], $tag_info );
							$cname = $clientinfo ['name'];
							
							$tagname .= $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].',';
								                    
							// $tagname .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
								                    
							// var_dump($tag_info['gender']);
							
							/*
							 * if($tag_info['gender'] == '1'){
							 * $boy1 = $boy1+1 ;
							 * }
							 *
							 * if($tag_info['gender'] == '2'){
							 * $girl1 = $girl1+1 ;
							 * }
							 */
						}
						
						// $tagname .= implode(", ",$tagname111);
						$tagname211 = array ();
						if ($rolecall ['role_call'] == '2') {
							$this->model_resident_resident->updatetagrolecall ( $key, $rolecall ['role_call'] );
							
							$emp_tag_id = $tag_info ['emp_tag_id'];
							$tags_id = $tag_info ['tags_id'];
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$this->load->model ( 'api/permision' );
							$clientinfo = $this->model_api_permision->getclientinfo ( $this->request->post ['facilities_id'], $tag_info );
							$cname = $clientinfo ['name'];
							
							$tagname2 .= $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].',';
								                     
							// $tagname2 .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
								                     
							// var_dump($tag_info['gender']);
							
							/*
							 * if($tag_info['gender'] == '1'){
							 * $girl12 = $girl12+1 ;
							 * }
							 *
							 * if($tag_info['gender'] == '2'){
							 * $boy12 = $boy12+1 ;
							 * }
							 */
						}
						// $tagname2 .= implode(", ",$tagname211);
					}
					
					$this->load->model ( 'facilities/facilities' );
					$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					$this->load->model ( 'notes/notes' );
					
					if ($facilityinfo ['config_tags_customlist_id'] != NULL && $facilityinfo ['config_tags_customlist_id'] != "") {
						$d2 = array ();
						$d2 ['customlistvalueids'] = $facilityinfo ['config_tags_customlist_id'];
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
						if ($customlistvalues) {
							
							foreach ( $customlistvalues as $customlistvalue ) {
								
								$customlistvalues_total = $this->model_setting_tags->gettotalcustomlistvaluebyid ( $customlistvalue ['customlistvalues_id'], $customlistvalue ['gender'], '1', $this->request->post ['facilities_id'] );
								
								if ($customlistvalues_total > 0) {
									$total1 = $total1 + $customlistvalues_total;
									$boygirl .= $customlistvalues_total . ' ' . $customlistvalue ['customlistvalues_name'] . ' ';
									
									$boygirl .= 'and ';
								}
							}
						}
					}
					
					$boygirl .= $total1 . ' Total ';
					
					/*
					 * if(($boy1 != null && $boy1 != "") && ($girl1 != null && $girl1 != "")){
					 * $boygirl = $boy1.' Boys and '. $girl1 .' Girls | '. ($boy1 + $girl1) . ' Total';
					 * }
					 *
					 *
					 * if(($boy1 != null && $boy1 != "") && ($girl1 == null && $girl1 == "") ){
					 * $boygirl = $boy1.' Boys';
					 * }
					 *
					 * if(($boy1 == null && $boy1 == "") && ($girl1 != null && $girl1 != "")){
					 * $boygirl = $girl1.' Girls';
					 * }
					 */
					
					/*
					 * if(($boy12 != null && $boy12 != "") && ($girl12 != null && $girl12 != "")){
					 * $boygirl2 = $boy12.' Boys and '. $girl12 .' Girls | '. ($boy12 + $girl12) . ' Total';
					 * }
					 *
					 *
					 * if(($boy12 != null && $boy12 != "") && ($girl12 == null && $girl12 == "") ){
					 * $boygirl2 = $boy12.' Boys';
					 * }
					 *
					 * if(($boy12 == null && $boy12 == "") && ($girl12 != null && $girl12 != "")){
					 * $boygirl2 = $girl12.' Girls';
					 * }
					 */
					
					$total12 = 0;
					if ($facilityinfo ['config_tags_customlist_id'] != NULL && $facilityinfo ['config_tags_customlist_id'] != "") {
						$d2 = array ();
						$d2 ['customlistvalueids'] = $facilityinfo ['config_tags_customlist_id'];
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
						if ($customlistvalues) {
							
							foreach ( $customlistvalues as $customlistvalue ) {
								
								$customlistvalues_total = $this->model_setting_tags->gettotalcustomlistvaluebyid ( $customlistvalue ['customlistvalues_id'], $customlistvalue ['gender'], '2', $this->request->post ['facilities_id'] );
								
								if ($customlistvalues_total > 0) {
									$total12 = $total12 + $customlistvalues_total;
									$boygirl2 .= $customlistvalues_total . ' ' . $customlistvalue ['customlistvalues_name'] . ' ';
									
									$boygirl2 .= 'and ';
								}
							}
						}
					}
					
					$boygirl2 .= $total12 . ' Total ';
					
					$outtag = ' | ' . $tagname2 . $boygirl2 . ' Clients are OUT of the facility';
					
					// $data['emp_tag_id'] = $emp_tag_id;
					// $data['tags_id'] = $tags_id;
					
					$data ['keyword_file'] = HEADCOUNT_ICON;
					
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $this->request->post ['facilities_id'] );
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					if ($this->request->post ['customlistvalues_id']) {
						
						$this->load->model ( 'notes/notes' );
						$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
						
						$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
						
						$description1 = ' | ' . $customlistvalues_name;
					}
					
					if ($this->request->post ['customlistvalues_ids']) {
						
						$this->load->model ( 'notes/notes' );
						
						foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
							
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
							
							$customlistvalues_name = $custom_info ['customlistvalues_name'];
							
							// $description1 .= ' | '.$customlistvalues_name;
						}
						
						$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
					}
					
					$fdataa = array ();
					$fdataa ['is_monitor_time'] = '1';
					$fdataa ['facilities_id'] = $this->request->post ['facilities_id'];
					$fdataa ['date_added'] = date ( 'Y-m-d', strtotime ( 'now' ) );
					
					$signnotes_infos = $this->model_notes_notes->getNotebyactivenotes ( $fdataa );
					
					$sign_users = "";
					$sign_users1 = array ();
					if ($signnotes_infos != null && $signnotes_infos != "") {
						$sign_users .= " | STAFF ";
						foreach ( $signnotes_infos as $signnotes_info ) {
							$sign_users .= $signnotes_info ['user_id'] . ',';
						}
						// $sign_users .= implode(", ",$sign_users1);
					}
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $tagname . ' | ' . $boygirl . ' Clients are in the Facility ' . $outtag . $description1 . $comments . $sign_users;
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					$data ['facilitytimezone'] = $facilitytimezone;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
						$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
						
						if (! empty ( $exist_note_info )) {
							$notes_id = $exist_note_info ['notes_id'];
							$device_unique_id = $exist_note_info ['device_unique_id'];
						} else {
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
							$device_unique_id = $this->request->post ['device_unique_id'];
						}
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
					
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 = array ();
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				}
				
				$jsonData = stripslashes ( html_entity_decode ( $_REQUEST ['facility_inouts'] ) );
				$arr = json_decode ( $jsonData, true );
				
				$facility_inouts = $this->groupArray ( $arr, "facilities_id", false, true );
				
				// var_dump($facility_inouts);
				
				if (! empty ( $facility_inouts )) {
					$this->load->model ( 'facilities/facilities' );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$tagnamesss = "";
					$tagnamesss_out = "";
					$tags_id_list = array ();
					foreach ( $facility_inouts as $facilities_id => $facility_inout1 ) {
						$tagname = "";
						$tagname2 = "";
						
						$girl1 = 0;
						$boy1 = 0;
						$total1 = '';
						
						$girl12 = 0;
						$boy12 = 0;
						$tagname111 = array ();
						$tags_id_list = array ();
						// var_dump($facilities_id);
						foreach ( $facility_inout1 as $facility_inout2 ) {
							
							// var_dump($rolecall['role_call']);
							$tag_info = $this->model_setting_tags->getTag ( $facility_inout2 ['tags_id'] );
							
							// if($rolecall['role_call'] == '1'){
							
							if ($facility_inout2 ['facility_inout'] == $tag_info ['role_call']) {
								$tagnamesss = 1;
							} else {
								$tagnamesss = 2;
							}
							
							$emp_tag_id = $tag_info ['emp_tag_id'];
							$tags_id = $tag_info ['tags_id'];
							// $tags_id_list[] = $tag_info['tags_id'];
							$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
							$data ['tags_id'] = $tag_info ['tags_id'];
							
							$data ['tag_status_id'] = $facility_inout2 ['role_call'];
							$data ['move_notes_id'] = $tag_info ['notes_id'];
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$this->load->model ( 'api/permision' );
							$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id, $tag_info );
							$cname = $clientinfo ['name'];
							
							$tagname = $cname; // $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
							                   // $tagname .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].' | ';
							                   
							// }
							
							$description1 = "";
							if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
								$comments = ' | ' . $this->request->post ['comments'];
							}
							
							if ($this->request->post ['customlistvalues_id']) {
								
								$this->load->model ( 'notes/notes' );
								$custom_info = $this->model_notes_notes->getcustomlistvalue ( $this->request->post ['customlistvalues_id'] );
								
								$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
								
								$description1 = ' | ' . $customlistvalues_name;
							}
							
							if ($this->request->post ['customlistvalues_ids']) {
								
								$this->load->model ( 'notes/notes' );
								
								foreach ( $this->request->post ['customlistvalues_ids'] as $customlistvalues_id ) {
									
									$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
									
									$description1 .= ' | ' . $custom_info ['customlistvalues_name'];
								}
								
								$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
							}
							
							$this->load->model ( 'facilities/facilities' );
							$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							$unique_id = $facility ['customer_key'];
							
							$this->load->model ( 'customer/customer' );
							$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
							
							if (! empty ( $customer_info ['setting_data'] )) {
								$customers = unserialize ( $customer_info ['setting_data'] );
								
								if ($facility_inout2 ['facility_inout'] == '0') {
									if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
										$roleCall = $customers ['in_name'];
									} else {
										$roleCall = ' returned to the Cell ';
									}
								}
								
								if ($facility_inout2 ['facility_inout'] == '1') {
									if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
										$roleCall = $customers ['out_name'];
									} else {
										$roleCall = ' left the Cell ';
									}
								}
							} else {
								if ($facility_inout2 ['facility_inout'] == '0') {
									$roleCall = ' returned to the Cell ';
								}
								
								if ($facility_inout2 ['facility_inout'] == '1') {
									$roleCall = ' left the Cell ';
								}
							}
							
							$data ['notes_description'] = $tagname . ' | ' . $form_name . ' status changed to ' . $roleCall . $description1 . $comments;
							
							// $data ['tags_id_list'] = $tags_id_list;
							$data ['date_added'] = $date_added;
							$data ['note_date'] = $date_added;
							$data ['notetime'] = $notetime;
							$data ['facilitytimezone'] = $facilitytimezone;
							
							$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
							$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
							
							if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
								$data ['is_android'] = $this->request->post ['is_android'];
							} else {
								$data ['is_android'] = '1';
							}
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
							
							if($tag_info ['notes_id'] > 0){
								$cdatam = array ();
								$cdatam ['notes_id'] = $tag_info ['notes_id'];
								$cdatam ['move_notes_id'] = $notes_id;
								$cdatam ['tags_id'] = $tag_info ['tags_id'];
								//if ($clientstatus_info2 ['type'] == '2') {
									$cdatam ['status_total_time'] = $status_total_time;
								//}else{
								//	$cdatam ['status_total_time'] = 0;
								//}
								
								$this->model_resident_resident->updateclientStatusnotes ( $cdatam );
							}
							
							
							$cdata2 = array ();
							$cdata2 ['modify_date'] = $date_added;
							$cdata2 ['notes_id'] = $notes_id;
							$cdata2 ['tags_id'] = $facility_inout2 ['tags_id'];
							$cdata2 ['facility_inout'] = $facility_inout2 ['facility_inout'];
							
							$this->model_resident_resident->updatetagrolecall2 ( $facility_inout2 ['tags_id'], $cdata2 );
							
							$device_unique_id = $this->request->post ['device_unique_id'];
							
							$cdata = array ();
							$cdata ['modify_date'] = $date_added;
							$cdata ['notes_id'] = $notes_id;
							$cdata ['tags_id'] = $tag_info ['tags_id'];
							$cdata ['facility_move_id'] = $this->request->post ['facility_move_id'];
							$cdata ['tag_status_id'] = $facility_inout2 ['role_call'];
							$this->model_resident_resident->updateclientnotes ( $cdata );
							
							
							if($tag_info ['notes_id'] > 0){
								$move_notes_id = $tag_info ['notes_id'];
							}else{
								$move_notes_id = $notes_id;
							}
							
							//if ($clientstatus_info ['track_time'] == 1) {
								$tmdata = array ();
								$tmdata ['notes_id'] = $notes_id;
								$tmdata ['facilities_id'] = $tag_info ['facilities_id'];
								$tmdata ['unique_id'] = $facilities_info ['customer_key'];
								$tmdata ['tags_id'] = $tag_info ['tags_id'];
								$tmdata ['tag_status_id'] = $clientstatus_info ['tag_status_id'];
								$tmdata ['new_tag_status_id'] = $clientstatus_info2 ['tag_status_id'];
								
								$tmdata ['move_notes_id'] = $move_notes_id;
								
								$tmdata ['keyword_id'] = '';
								$tmdata ['types'] = 1;
								
								$tmdata ['years'] = $since_start->y;
								$tmdata ['months'] = $since_start->m;
								$tmdata ['days'] = $since_start->d;
								$tmdata ['hours'] = $since_start->h;
								$tmdata ['minutes'] = $since_start->i;
								
								$tmdata ['date_added'] = date ( 'Y-m-d H:i:s' );
								$this->model_resident_resident->addtracktime ( $tmdata );
							//}
							
							$this->load->model ( 'api/facerekognition' );
							$fre_array2 = array ();
							$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
							$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
							$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
							$fre_array2 ['facilities_id'] = $facilities_id;
							$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
							$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
							$fre_array2 ['notes_id'] = $notes_id;
							$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
						}
					}
				}
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addclient jsonrolecall ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonrolecall', $activity_data2 );
		}
	}
	public function jsonclienttagform() {
		try {
			
			$this->data ['facilitiess'] = array ();
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			/*
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			*/
			
			
			
			
			$tags_id = $this->request->post ['tags_id'];
			
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			
			$name = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
			
			if (isset ( $this->request->post ['page'] )) {
				$page = $this->request->post ['page'];
			} else {
				$page = 1;
			}
			
			// $config_admin_limit1 = '5';
			// $this->config->get('config_front_limit');
			
			$config_admin_limit1 = $this->config->get ( 'config_android_front_limit' );
			
			if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
				$config_admin_limit = $config_admin_limit1;
			} else {
				$config_admin_limit = "25";
			}
			
			/*$data = array (
				'group' => '1',
				'groupby' => '1',
				'tags_id' => $tags_id 
			);
			
			$aallforms = $this->model_form_form->gettagsforms ( $data );
			$this->data ['displayforms'] = array ();
			foreach ( $aallforms as $allform ) {
				
				$this->data ['displayforms'] [] = array (
					'forms_design_id' => $allform ['custom_form_type'],
					'form_name' => $allform ['incident_number'],
					'form_href' => $this->url->link ( 'resident/resident/tagforms', '' . '&forms_id=' . $allform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $this->request->get ['facilities_id'], 'SSL' ) 
				);
			}*/
			
			$this->load->model ( 'notes/notes' );
			$d12 = array ();
			$d12 ['tags_id'] = $tags_id;
			$d12 ['form_type'] = '5';
			$checkout_form_sign = $this->model_notes_notes->getInventoryNoteform ( $d12 );
					
			
			$d12 = array ();
			$d12 ['tags_id'] = $tags_id;
			$d12 ['form_type'] = '6';
			$checkin_form_sign = $this->model_notes_notes->getInventoryNoteform ( $d12 );
			
			
			foreach($checkout_form_sign as $checkout_form){				
				$checkout_url = $this->url->link ( 'notes/addInventory/CheckOutInventoryForm', '' . '&notes_id=' . $checkout_form['notes_id'] , 'SSL' );

				if ($checkout_form ['note_date'] != null && $checkout_form ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $checkout_form ['note_date'] ) );
				} else {
					$form_date_added = '';
				}


				$checkoutforms [] = array (
					'forms_id' => '',
					'image_url' => 'checkout',
					'form_name' => 'Checkout Inventory',
					'notes_type' => $checkout_form['notes_type'],
					'notes_description' => $checkout_form['notes_description'],
					'user_id' => $checkout_form['user_id'],
					'signature' => $checkout_form['signature'],
					'notes_pin' => $checkout_form['notes_pin'],
					'form_date_added' => $form_date_added,
					'date_added2' => date ( 'D F j, Y', strtotime ( $checkout_form ['date_added'] ) ),
					'archivedforms' => '',
					'form_href' => $checkout_url 
				);	
			}




			foreach($checkin_form_sign as $checkin_form){			
			
				$checkin_url = $this->url->link ( 'notes/addInventory/CheckInInventoryForm', '' . '&notes_id=' . $checkin_form['notes_id'] , 'SSL' );

				if ($checkin_form ['note_date'] != null && $checkin_form ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $checkin_form ['note_date'] ) );
				} else {
					$form_date_added = '';
				}
		
			
				$checkinforms [] = array (
						'forms_id' => '',
						'image_url' => 'checkin',
						'form_name' => 'Checkin Inventory',
						'notes_type' => $checkin_form['notes_type'],
						'notes_description' => $checkin_form['notes_description'],
						'user_id' => $checkin_form['user_id'],
						'signature' => $checkin_form['signature'],
						'notes_pin' => $checkin_form['notes_pin'],
						'form_date_added' => $form_date_added,
						'date_added2' => date ( 'D F j, Y', strtotime ( $checkin_form ['date_added'] ) ),
						'archivedforms' => '',
						'form_href' => $checkin_url 
				);

			}
			
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'tags_id' => $tags_id 
			);
			
			$aallattas = $this->model_setting_tags->gettagsattachmets ( $data );
			
		
			
			$attachments = array ();
			foreach ( $aallattas as $aallatta ) {
				if ($aallatta ['notes_file'] != null && $aallatta ['notes_file'] != "") {
					$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $aallatta ['notes_media_id'] , 'SSL' );
					$form_name = $aallatta ['image_name'];
				}
				$note_info = $this->model_notes_notes->getNote ( $aallatta ['notes_id'] );
				
				$user_id = $note_info ['user_id'];
				$signature = $note_info ['signature'];
				$notes_pin = $note_info ['notes_pin'];
				$notes_type = $note_info ['notes_type'];
				$notes_description = $note_info ['notes_description'];
				
				/*if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
				} else {
					$form_date_added = '';
				}*/
				$attachments [] = array (
					'notes_media_id' => $aallatta ['notes_media_id'],
					'form_name' => "Attachment",
					'form_href' => $hrurl,
					'notes_type' => $notes_type,
					'notes_description' => $notes_description,
					'user_id' => $user_id,
					'signature' => $signature,
					'notes_pin' => $notes_pin,
					//'form_date_added' => $form_date_added,
					'form_date_added' =>$note_info ['date_added'],
				);
			}
			
			
			if (isset ( $this->request->post ['forms_id'] )) {
				$forms_id = $this->request->post ['forms_id'];
			} else {
				$forms_id = '';
			}		
			
		
			
			if (isset ( $this->request->post ['sort'] )) {
				$sort = $this->request->post ['sort'];
			} else {
				$sort = 'DESC';
			}
			
			$data = array (
					'tags_id' => $tags_id,
					//'start' => ($page - 1) * $config_admin_limit,
					//'limit' => $config_admin_limit 
					'sort' => $sort,
					'custom_form_type' => $forms_id,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			
		
			
			$results = $this->model_form_form->gettagsforms ( $data );
			
			//var_dump($results);
			$form_total = $this->model_form_form->getTotalforms2 ( $data );
			
			// $results = $this->model_form_form->getTotalforms2($tags_id);
			
			foreach ( $results as $allform ) {
				
				$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
				
				if ($allform ['notes_id'] > 0) {
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				}
				
				if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
					$user_id = $allform ['user_id'];
					$signature = $allform ['signature'];
					$notes_pin = $allform ['notes_pin'];
					$notes_type = $allform ['notes_type'];
					$notes_description = $note_info ['notes_description'];
					
					if ($allform ['form_date_added'] != null && $allform ['form_date_added'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) );
					} else {
						$form_date_added = '';
					}
				} else {
					$user_id = $note_info ['user_id'];
					$signature = $note_info ['signature'];
					$notes_pin = $note_info ['notes_pin'];
					$notes_type = $note_info ['notes_type'];
					$notes_description = $note_info ['notes_description'];
					
					if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
					} else {
						$form_date_added = '';
					}
				}
				
				$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&tags_id=' . $allform ['tags_id'] ) );
				
				if ($allform ['custom_form_type'] == '13') {
					$print_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/printformfldjj', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], true ) );
				} elseif ($allform ['custom_form_type'] == '9') {
					// $print_url = str_replace('&amp;', '&', $this->url->link('form/form/printmonthly_firredrill', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					$print_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/printform', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], true ) );
				} elseif ($allform ['custom_form_type'] == '10') {
					// $print_url = str_replace('&amp;', '&', $this->url->link('form/form/printincidentform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					$print_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/printform', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], true ) );
				} elseif ($allform ['custom_form_type'] == '2') {
					// $print_url = str_replace('&amp;', '&', $this->url->link('form/form/printintakeform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					$print_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/printform', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], true ) );
				} elseif ($allform ['custom_form_type'] == '12') {
					// $print_url = str_replace('&amp;', '&', $this->url->link('form/form/printintakeform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					$print_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/printform', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], true ) );
				} else {
					$print_url = '';
				}
				
				//var_dump($allform);
				
				$resultsforms = $this->model_form_form->getArcheiveFormDatas ( $allform ['forms_id'] );
				
				$archivedforms = array ();
				foreach ( $resultsforms as $resultsform ) {
					$nnote = $this->model_notes_notes->getnotes ( $resultsform ['notes_id'] );
					
					$archivedforms [] = array (
							'forms_id' => $resultsform ['forms_id'],
							'form_name' => $resultsform ['incident_number'],
							'forms_design_id' => $resultsform ['custom_form_type'],
							'notes_type' => $nnote ['notes_type'],
							'notes_description' => $nnote ['notes_description'],
							'user_id' => $nnote ['user_id'],
							'signature' => $nnote ['signature'],
							'notes_pin' => $nnote ['notes_pin'],
							'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['note_date'] ) ),
							'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
							'form_href' => $this->url->link ( 'form/form&is_archive=4', '' . '&forms_id=' . $resultsform ['forms_id'] . '&tags_id=' . $resultsform ['tags_id'] . '&notes_id=' . $resultsform ['notes_id'] . '&forms_design_id=' . $resultsform ['custom_form_type'] . '&forms_id=' . $resultsform ['forms_id'] . '&facilities_id=' . $resultsform ['facilities_id'], 'SSL' ) 
					);
				}
				
				$this->data ['facilitiess'] [] = array (
						'form_type_id' => $allform ['form_type_id'],
						'notes_id' => $allform ['notes_id'],
						'form_type' => $allform ['form_type'],
						'forms_design_id' => $allform ['custom_form_type'],
						'notes_type' => $notes_type,
						'user_id' => $user_id,
						'signature' => $signature,
						'notes_pin' => $notes_pin,
						'incident_number' => $allform ['incident_number'],
						'form_date_added' => $form_date_added,
						'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
						'href' => $form_url,
						'print_url' => $print_url,
						'archivedforms' => $archivedforms,
						'notes_description' => $notes_description,
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
						'quantity' => '',
						'frequency' => '',
						'instructions' => '',
						'count' => '',
						'createtask_by_group_id' => '',
						'task_comments' => '',
						'medication_file_upload' => '',
						'date_added' => '',
						'is_tag_url' => '',
						'is_census_url' => '' 
				);
			}
			
			
			
			  $data2 = array(
        		'sort' => 'ASC',
        		'order' => 'ASC',
         	   	'archivedform' => '1',
        		'tags_id' => $tags_id,
        		
        );
		
		$aallforms = $this->model_form_form->gettagsforms($data2);
        $this->data['atagsforms'] = array();
        
        foreach ($aallforms as $aallform) {
        
        	$form_info = $this->model_form_form->getFormdata($aallform['custom_form_type']);
        
        	if ($aallform['user_id'] != null && $aallform['user_id'] != "") {
        		$user_id = $aallform['user_id'];
        		$signature = $aallform['signature'];
        		$notes_pin = $aallform['notes_pin'];
        		$notes_type = $aallform['notes_type'];
        
        		if ($aallform['form_date_added'] != null && $aallform['form_date_added'] != "0000-00-00 00:00:00") {
        			$form_date_added = date($this->language->get('date_format_short_2'), strtotime($aallform['form_date_added']));
        		} else {
        			$form_date_added = '';
        		}
        	} else {
        
        		$note_info = $this->model_notes_notes->getNote($aallform['notes_id']);
        
        		// var_dump($note_info);
        		$user_id = $note_info['user_id'];
        		$signature = $note_info['signature'];
        		$notes_pin = $note_info['notes_pin'];
        		$notes_type = $note_info['notes_type'];
        		$notes_description = $note_info['notes_description'];
        
        		if ($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00") {
        			$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
        		} else {
        			$form_date_added = '';
        		}
        	}
			
			
			
			
        	if($aallform ['image_url'] != null && $aallform ['image_url'] != ""){
				$hrurl = $aallform ['image_url'];
				$form_name = $aallform ['image_name'];
			}else{
				$hrurl = $this->url->link('form/form&is_archive=4', '' . '&forms_id=' . $aallform['forms_id'] . '&tags_id=' . $aallform['tags_id'] . '&notes_id=' . $aallform['notes_id'] . '&forms_design_id=' . $aallform['custom_form_type'] . '&forms_id=' . $aallform['forms_id'], 'SSL');
				
				$form_name = $aallform['incident_number'];
				
				
			}
        	
        	$this->data['atagsforms'][] = array(
        			'forms_id' => $aallform['forms_id'],
        			'image_url' => $aallform ['image_url'],
					'image_name' => $aallform ['image_name'],
        			'form_name' => $form_name,
        			'notes_type' => $notes_type,
        			'notes_description' => $notes_description,
        			'user_id' => $user_id,
        			'signature' => $signature,
        			'notes_pin' => $notes_pin,
        			'form_date_added' => $form_date_added,
        			'date_added2' => date('D F j, Y', strtotime($aallform['date_added'])),
        			'form_href' => $hrurl
        	);
        }
			
			
			$data23 = array(
                'sort' => $sort,
                'order' => $order,
                'group' => '1',
                'groupby' => '1',
                'tags_id' => $tags_id,
                'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);
			
			
			$Aallforms = $this->model_form_form->gettagsforms($data23);
			$url = '';
			
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			  $url .= '&tags_id=' . $this->request->get['tags_id'];
			}
			
			
			$this->data['displayforms'] = array();
			foreach ($Aallforms as $allform) {
										
				$this->data['displayforms'][] = array(
				'form_name' => $allform['incident_number'],
				'forms_id' => $allform['forms_id'],
				'custom_form_type' => $allform['custom_form_type'],
				'notes_id' => $allform['notes_id'],
				'form_href' => $this->url->link('resident/resident/tagforms', '' . '&forms_id=' . $allform['forms_id'] . '&tags_id=' . $allform['tags_id'] . '&notes_id=' . $allform['notes_id'] . '&forms_design_id=' . $allform['custom_form_type'] . '&forms_id=' . $allform['forms_id']. '&facilities_id=' . $this->request->get['facilities_id'], 'SSL')
				);
			 }
			 
		 $last_page = ceil($form_total/$config_admin_limit);
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'form_total' => $form_total,
					'last_page' => $last_page,
					'status' => true,
					'client_name' => $name,
					'displayforms' => $this->data ['displayforms'],
					'attachments' => $attachments,
					'checkoutforms' => $checkoutforms,
					'checkinforms' => $checkinforms,
					'atagsforms' => $this->data['atagsforms']
			)
			;
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonclienttagform ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonclienttagform', $activity_data2 );
		}
	}
	public function jsonAddSticky() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residentjsonAddSticky', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			
			/*
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			*/
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				
				$data = array ();
				
				$data ['stickynote'] = $this->request->post ['stickynote'];
				$data ['tags_id'] = $this->request->post ['tags_id'];
				
				$this->load->model ( 'setting/tags' );
				$this->model_setting_tags->updateSticky ( $data );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1' 
				);
				$error = true;
			} else {
				
				echo 14441;
				
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonAddSticky ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonAddSticky', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonGetSticky() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			*/
			
			$this->load->model ( 'setting/tags' );
			$stickyinfo = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
			
			if ($stickyinfo ['stickynote'] != null && $stickyinfo ['stickynote'] != "") {
				$this->data ['facilitiess'] ['stickyinfo'] = $stickyinfo ['stickynote'];
			} else {
				$this->data ['facilitiess'] ['stickyinfo'] = '';
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonGetSticky ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonGetSticky', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function getcustomfieldbyFID() {
		try {
			
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			*/
			
			$json = array ();
			
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			$this->load->model ( 'notes/notes' );
			
			if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
				
				$d = array ();
				
				$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
				
				$customlists = $this->model_notes_notes->getcustomlists ( $d );
				if (! empty ( $customlists )) {
					foreach ( $customlists as $customlist ) {
						$d2 = array ();
						$d2 ['customlist_id'] = $customlist ['customlist_id'];
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
						
						
						foreach($customlistvalues as $customlistvalue){
							
							$this->data ['facilitiess'] [] = array (
								'customlist_name' => $customlist ['customlist_name'],
								'customlistvalues_name' => $customlistvalue ['customlistvalues_name'],
								'customlistvalues_id' => $customlistvalue ['customlistvalues_id'],
								
							);
							
						}
						
						/*
						$this->data ['facilitiess'] [] = array (
								'customlist_id' => $customlist ['customlist_id'],
								'customlist_name' => $customlist ['customlist_name'],
								'customlistvalues' => $customlistvalues 
						);
						*/
					}
					
					$error = true;
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Custom list not found" 
					);
					$error = false;
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addclient getcustomfieldbyFID ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_getcustomfieldbyFID', $activity_data2 );
		}
	}
	public function jsonassignteam() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residentjsonassignteam', $this->request->post, 'request' );
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$data3 = array ();
			$data3 ['tags_id'] = $this->request->post ['tags_id'];
			$data3 ['is_archive'] = $this->request->post ['is_archive'];
			$data3 ['notes_id'] = $this->request->post ['notes_id'];
			$data3 ['facilities_id'] = $this->request->post ['facilities_id'];
			$team_infos = $this->model_resident_resident->getassignteam ( $data3 );
			
			$this->data ['user_roles'] = array ();
			$this->load->model ( 'user/user_group' );
			$this->load->model ( 'user/user' );
			
			$this->data ['facilitiess'] = array ();
			
			if ($team_infos != null && $team_infos != "") {
				foreach ( $team_infos as $team_info ) {
					// var_dump($team_info);
					$user_role_info = $this->model_user_user_group->getUserGroup ( $team_info ['user_roles'] );
					
					if ($user_role_info) {
						$users = array ();
						
						$data3u = array ();
						$data3u ['tags_id'] = $this->request->post ['tags_id'];
						$data3u ['is_archive'] = $this->request->post ['is_archive'];
						$data3u ['notes_id'] = $this->request->post ['notes_id'];
						$data3u ['user_roles'] = $team_info ['user_roles'];
						$data3u ['facilities_id'] = $this->request->post ['facilities_id'];
						
						$uresults = $this->model_resident_resident->getassignteamUsers ( $data3u );
						
						if ($uresults != null && $uresults != "") {
							foreach ( $uresults as $user ) {
								$user_info = $this->model_user_user->getUserbyupdate ( $user ['userids'] );
								
								if ($user_info ['user_id']) {
									$users [] = array (
											'user_id' => $user_info ['user_id'],
											'username' => strip_tags ( html_entity_decode ( $user_info ['username'], ENT_QUOTES, 'UTF-8' ) ) 
									);
								}
							}
						}
						
						$this->data ['facilitiess'] [] = array (
								'user_group_id' => $team_info ['user_roles'],
								'name' => $user_role_info ['name'],
								'users' => $users 
						);
					}
				}
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonassignteam ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonassignteam', $activity_data2 );
		}
	}
	public function jsonaddassignteam() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'form/form' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				if ($this->request->post ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				if ($tag_info ['emp_first_name']) {
					// $emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
				} else {
					$emp_tag_id = $tag_info ['emp_tag_id'];
				}
				
				if ($tag_info) {
					$medication_tags .= $emp_tag_id . ' ';
				}
				
				$description = '';
				
				$description .= ' Team Assignment Updated. | ';
				$description .= ' ' . $medication_tags;
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
				}
				
				$data ['notes_description'] = $description;
				
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				$this->model_notes_notes->updatetagsassign1 ( $this->request->get ['tags_id'] );
				
				if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
					
					$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
					
					if (! empty ( $exist_note_info )) {
						$notes_id = $exist_note_info ['notes_id'];
						$device_unique_id = $exist_note_info ['device_unique_id'];
					} else {
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
				} else {
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
					$device_unique_id = $this->request->post ['device_unique_id'];
				}
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$data2 = array ();
				$data2 ['tags_id'] = $this->request->post ['tags_id'];
				$data2 ['date_added'] = $date_added;
				$data2 ['facilities_id'] = $this->request->post ['facilities_id'];
				
				$data3 = array ();
				// $data3['user_roles'] = explode(',',$this->request->post['user_roles']);
				// $data3['userids'] = explode(',',$this->request->post['userids']);
				
				$data3 ['user_roles'] = array_unique ( $this->request->post ['user_roles'] );
				$data3 ['userids'] = array_unique ( $this->request->post ['userids'] );
				$data3 ['notes_id'] = $notes_id;
				
				$this->model_resident_resident->addassignteam ( $data2, $data3 );
				
				$this->model_notes_notes->updatetagsassign23 ( $this->request->get ['tags_id'], $notes_id );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addclient jsonaddassignteam ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonaddassignteam', $activity_data2 );
		}
	}
	public function jsonresidentstatus() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residentjsonresidentstatus', $this->request->post, 'request' );
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			if ($this->request->post ['tags_id'] != null && $this->request->post ['tags_id'] != "") {
				$timezone_name = $this->request->post ['facilitytimezone'];
				$timeZone = date_default_timezone_set ( $timezone_name );
				date_default_timezone_set ( $timezone_name );
				$currentdate = date ( 'Y-m-d' );
				$data = array (
						'currentdate' => $currentdate,
						'tags_id' => $this->request->post ['tags_id'] 
				);
				
				$this->load->model ( 'resident/resident' );
				$task_infos = $this->model_resident_resident->getResidentstatus ( $data );
				$totaltask_infos = $this->model_resident_resident->getTotalResidentstatus ( $data );
				
				$task_info = array ();
				$form_info = array ();
				$notes = array ();
				foreach ( $task_infos as $taskinfo ) {
					
					$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $taskinfo ['tagstatus_id'] );
					$task_info [] = array (
							'tasktype' => $taskinfo ['tasktype'],
							'date_added' => date ( 'm-d-Y', strtotime ( $taskinfo ['date_added'] ) ),
							'description' => $taskinfo ['description'],
							'assign_to' => $taskinfo ['assign_to'],
							'task_time' => date ( 'h:i A', strtotime ( $taskinfo ['task_time'] ) ),
							'task_date' => date ( 'm-d-Y', strtotime ( $taskinfo ['task_date'] ) ),
							'count' => $totaltask_infos,
							'taskid' => $taskinfo ['id'],
							'tagstatus_id' => $tagstatus_info ['status'] 
					);
				}
				
				$this->load->model ( 'form/form' );
				$form_infos = $this->model_form_form->getformstatus ( $data );
				$totalform_infos = $this->model_form_form->gettotalformstatus ( $data );
				
				foreach ( $form_infos as $formdata ) {
					$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $formdata ['tagstatus_id'] );
					
					$form_info [] = array (
							'form_description' => $formdata ['form_description'],
							'date_added' => date ( 'm-d-Y', strtotime ( $formdata ['date_added'] ) ),
							'count' => $totalform_infos,
							'forms_id' => $formdata ['forms_id'],
							'tagstatus_id' => $tagstatus_info ['status'] 
					);
				}
				
				$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $this->request->post ['tags_id'] );
				$tagstatus_info1 = $tagstatusinfo ['status'];
				
				$currentdate2 = date ( 'd-m-Y' );
				
				$this->load->model ( 'createtask/createtask' );
				$tasksinfo = $this->model_createtask_createtask->getTaskas ( $this->request->post ['tags_id'], $currentdate2 );
				$tasksinfo1 = $tasksinfo * 100;
				
				$this->load->model ( 'setting/tags' );
				$taginfo = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
				
				$data = array (
						'sort' => $sort,
						'order' => $order,
						'searchdate' => $searchdate,
						'searchdate_app' => '1',
						'tagstatus_id' => '1',
						'emp_tag_id' => $this->request->post ['tags_id'],
						'facilities_id' => $this->request->post ['facilities_id'],
						'start' => 0,
						'limit' => 500 
				);
				
				$this->load->model ( 'notes/image' );
				$this->load->model ( 'setting/highlighter' );
				$this->load->model ( 'user/user' );
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'facilities/facilities' );
				
				$this->load->model ( 'notes/tags' );
				$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
				
				$last_notesID = $this->model_notes_notes->getLastNotesID ( $this->request->post ['facilities_id'], $searchdate );
				
				$this->data ['last_notesID'] = $last_notesID ['notes_id'];
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$results = $this->model_notes_notes->getnotess ( $data );
				
				// var_dump($results);
				
				$this->load->model ( 'notes/tags' );
				
				$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				// var_dump($facilityinfo);
				
				// var_dump($results);
				
				foreach ( $results as $result ) {
					
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$userPin = $result ['notes_pin'];
					} else {
						$userPin = '';
					}
					
					if ($result ['signature'] != null && $result ['signature'] != "") {
						$signaturesrc = $result ['signature'];
					} else {
						$signaturesrc = '';
					}
					
					$notes [] = array (
							'notes_id' => $result ['notes_id'],
							'notes_type' => $result ['notes_type'],
							'notes_description' => $result ['notes_description'],
							'notetime' => date ( 'h:i A', strtotime ( $result ['notetime'] ) ),
							'username' => $result ['user_id'],
							'signature' => $signaturesrc,
							'notes_pin' => $notesPin,
							'note_date' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['note_date'] ) ) 
					);
				}
				
				$this->data ['facilitiess'] [] = array (
						'notes' => $notes,
						'task_info' => $task_info,
						'form_info' => $form_info,
						'tagstatus_info1' => $tagstatus_info1,
						'tasksinfo1' => $tasksinfo1,
						'taginfo' => $taginfo 
				);
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please Enter Tags id' 
				);
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonresidentstatus ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonresidentstatus', $activity_data2 );
		}
	}
	
	public function jsonaddresidentstatus() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'form/form' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				if ($this->request->post ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$data ['childstatus'] = $this->request->post ['childstatus'];
				$data ['facilitytimezone'] = $facilitytimezone;
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				if ($tag_info ['emp_first_name']) {
					// $emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
				} else {
					$emp_tag_id = $tag_info ['emp_tag_id'];
				}
				
				if ($tag_info) {
					$medication_tags .= $emp_tag_id . ' ';
				}
				
				$currentdate = date ( 'Y-m-d' );
				
				$tagstatus = array ();
				$data2 = array (
						'currentdate' => $currentdate,
						'tags_id' => $this->request->post ['tags_id'] 
				);
				
				$this->load->model ( 'resident/resident' );
				$task_infos = $this->model_resident_resident->getResidentstatus ( $data2 );
				
				$totaltask_infos = $this->model_resident_resident->getTotalResidentstatus ( $data2 );
				
				foreach ( $task_infos as $taskinfo ) {
					$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $taskinfo ['tagstatus_id'] );
					$tagstatus [] = array (
							'task_id' => $taskinfo ['id'] 
					);
				}
				
				$this->load->model ( 'form/form' );
				$form_infos = $this->model_form_form->getformstatus ( $data2 );
				$totalform_infos = $this->model_form_form->gettotalformstatus ( $data2s );
				
				foreach ( $form_infos as $formdata ) {
					$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $formdata ['tagstatus_id'] );
					
					$tagstatus [] = array (
							'forms_id' => $formdata ['forms_id'] 
					);
				}
				
				$description = '';
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
				}
				
				if ($this->request->post ['childstatus'] == 'high') {
					$childstatus = 'High';
				}
				
				if ($this->request->post ['childstatus'] == 'moderate') {
					$childstatus = 'Moderate';
				}
				if ($this->request->post ['childstatus'] == 'normal') {
					$childstatus = 'Normal';
				}
				
				$data ['notes_description'] = ' Client Status turned to ' . $childstatus . ' ' . $description;
				
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
					$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
					
					if (! empty ( $exist_note_info )) {
						$notes_id = $exist_note_info ['notes_id'];
						$device_unique_id = $exist_note_info ['device_unique_id'];
					} else {
						
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
				} else {
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
					$device_unique_id = $this->request->post ['device_unique_id'];
				}
				if ($tagstatus != NULL && $tagstatus != "") {
					$this->load->model ( 'resident/resident' );
					$tagstatus_id = $this->model_resident_resident->addTagstatus ( $tagstatus, $this->request->post ['childstatus'], $this->request->post ['tags_id'], $notes_id );
				} else {
					$this->load->model ( 'resident/resident' );
					$tagstatus_id = $this->model_resident_resident->addTagstatus2 ( $this->request->post ['childstatus'], $this->request->post ['tags_id'], $notes_id );
				}
				
				$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '" . $date_added . "' WHERE tags_id = '" . $this->request->post ['tags_id'] . "'" );
				
				$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '1',notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "'" );
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addclient jsonaddresidentstatus ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonaddresidentstatus', $activity_data2 );
		}
	}
	public function jsonupdateFile() {
		try {
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'setting/tags' );
			
			if ($this->request->files ["upload_file"] != null && $this->request->files ["upload_file"] != "") {
				
				if ($this->request->post ['tags_id'] != null && $this->request->post ['tags_id'] != "") {
					$extension = end ( explode ( ".", $this->request->files ["upload_file"] ["name"] ) );
					
					if ($this->request->files ["upload_file"] ["size"] < 42214400) {
						$neextension = strtolower ( $extension );
						// if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
						
						/*
						 * $notes_file = uniqid( ) . "." . $extension;
						 * $outputFolder = DIR_IMAGE.'files/' . $notes_file;
						 * move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
						 *
						 */
						
						$notes_file = 'devbolb' . rand () . '.' . $extension;
						$outputFolder = $this->request->files ["upload_file"] ["tmp_name"];
						
						// require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
						
						// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						
						if ($this->config->get ( 'enable_storage' ) == '1') {
							/* AWS */
							
							// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->request->post ['facilities_id'] );
						}
						
						if ($this->config->get ( 'enable_storage' ) == '2') {
							/* AZURE */
							
							require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
							// uploadBlobSample($blobClient, $outputFolder, $notes_file);
							$s3file = AZURE_URL . $notes_file;
						}
						
						if ($this->config->get ( 'enable_storage' ) == '3') {
							/* LOCAL */
							$outputFolder = DIR_IMAGE . 'storage/' . $notes_file;
							move_uploaded_file ( $this->request->files ["upload_file"] ["tmp_name"], $outputFolder );
							$s3file = HTTPS_SERVER . 'image/storage/' . $notes_file;
						}
						
						$notes_media_extention = $extension;
						$notes_file_url = $s3file;
						
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$formData ['noteDate'] = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$outputFolder11 = DIR_IMAGE . 'files/' . $notes_file;
						move_uploaded_file ( $this->request->files ["upload_file"] ["tmp_name"], $outputFolder11 );
						
						$this->model_setting_tags->updateTagimage ( $this->request->post ['tags_id'], $s3file );
						
						$error = true;
						
						$this->data ['facilitiess'] [] = array (
								'success' => '1',
								'file' => $s3file 
						);
						
						/*
						 * }else{
						 * $this->data['facilitiess'][] = array(
						 * 'warning' => 'video or audio file not valid!',
						 * );
						 * $error = false;
						 * }
						 */
					} else {
						$this->data ['facilitiess'] [] = array (
								'warning' => 'Maximum size file upload!' 
						);
						$error = false;
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Note not update please update again' 
					);
					$error = false;
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please select file!' 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonupdateFile ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonupdateFile', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonaddclientfile() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'residentjsonaddclientfile', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'form/form' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				if ($this->request->post ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$data ['facilitytimezone'] = $facilitytimezone;
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				$description = '';
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
				}
				
				$data ['notes_description'] = ' New File upload ' . $description;
				
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				if ($this->request->post ['device_unique_id'] != null && $this->request->post ['device_unique_id'] != "") {
					$exist_note_info = $this->model_notes_notes->getexistnotes ( $data, $this->request->post ['facilities_id'] );
					
					if (! empty ( $exist_note_info )) {
						$notes_id = $exist_note_info ['notes_id'];
						$device_unique_id = $exist_note_info ['device_unique_id'];
					} else {
						
						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						$device_unique_id = $this->request->post ['device_unique_id'];
					}
				} else {
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
					$device_unique_id = $this->request->post ['device_unique_id'];
				}
				
				$formData = array ();
				$formData ['media_user_id'] = $this->request->post ['user_id'];
				
				$formData ['media_signature'] = $this->request->post ['signature'];
				
				$formData ['media_pin'] = $this->request->post ['notes_pin'];
				$formData ['facilities_id'] = $this->request->post ['facilities_id'];
				$formData ['notes_type'] = $this->request->post ['notes_type'];
				
				$formData ['noteDate'] = $date_added;
				
				$this->model_notes_notes->updateNoteFile ( $notes_id, $this->request->post ['notes_file'], $this->request->post ['extention'], $formData );
				
				$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '" . $date_added . "' WHERE tags_id = '" . $this->db->escape ( $tag_info ['tags_id'] ) . "'" );
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						'device_unique_id' => $device_unique_id 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in addclient jsonaddresidentstatus ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonaddresidentstatus', $activity_data2 );
		}
	}
	public function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
		$temp = array ();
		foreach ( $arr as $key => $value ) {
			$groupValue = $value [$group];
			if (! $preserveGroupKey) {
				unset ( $arr [$key] [$group] );
			}
			if (! array_key_exists ( $groupValue, $temp )) {
				$temp [$groupValue] = array ();
			}
			
			if (! $preserveSubArrays) {
				$data = count ( $arr [$key] ) == 1 ? array_pop ( $arr [$key] ) : $arr [$key];
			} else {
				$data = $arr [$key];
			}
			$temp [$groupValue] [] = $data;
			}
			return $temp;
		}
		
		
		public function jsonKeywordBytag() {
		try {
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			
			$this->load->model ( 'setting/keywords' );
			$this->load->model ( 'notes/image' );
			
			$tags_id = $this->request->post ['tags_id'];
			
			$data3 = array (
					'tags_id' => $this->request->post ['tags_id'] 
			);
			$keyss = $this->model_setting_keywords->getkeywordinNotes ( $data3 );
			$datakeys = array ();
			foreach ( $keyss as $k ) {
				if ($k ['keyword_id'] != NULL && $k ['keyword_id'] != "") {
					$datakeys [] = $k ['keyword_id'];
				}
			}
			
			$Kkeys = array_unique ( $datakeys );
			foreach ( $Kkeys as $keyword_id ) {
				$keyinfo = $this->model_setting_keywords->getkeywordDetail ( $keyword_id );
				if ($keyinfo ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $keyinfo ['keyword_image'] )) {
					$image = $this->model_notes_image->resize ( 'icon/' . $keyinfo ['keyword_image'], 35, 35 );
				}
				
				$this->data ['filterkeywords'] [] = array (
						'keyword_id' => $keyinfo ['keyword_id'],
						'keyword_name' => $keyinfo ['keyword_name'],
						'keyword_image' => $keyinfo ['keyword_image'],
						'href' => $this->url->link ( 'case/clients/detail', '' . $url2 . '&keyword_id=' . $keyinfo ['keyword_id'], 'SSL' ) 
				);
			}
			
			$value = array (
					'results' => $this->data ['filterkeywords'],
					'status' => true 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonclienttagform ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonclienttagform', $activity_data2 );
		}
	}
	public function jsonGetmovement() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			 *
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$this->load->model ( 'setting/tags' );
			$stickyinfo = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $stickyinfo ['facility_move_id'] );
			
			$this->load->model ( 'setting/locations' );
			$roominfo = $this->model_setting_locations->getlocation ( $stickyinfo ['movement_room'] );
			
			$this->data ['facilitiess'] ['is_movement'] = $stickyinfo ['is_movement'];
			$this->data ['facilitiess'] ['movement_room'] = $stickyinfo ['movement_room'];
			$this->data ['facilitiess'] ['location_name'] = $roominfo ['location_name'];
			$this->data ['facilitiess'] ['facility_move_id'] = $stickyinfo ['facility_move_id'];
			$this->data ['facilitiess'] ['facility'] = $facilities_info2 ['facility'];
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonGetmovement ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonGetmovement', $activity_data2 );
		}
	}
	
	
	
	public function jsonhoutClientLists() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			
			 if($api_device_info == false){
			 $errorMessage = $this->model_api_encrypt->errorMessage();
			 return $errorMessage;
			  }
			 
			  $api_header_value = $this->model_api_encrypt->getallheaders1();
			 
			  if($api_header_value == false){
			  $errorMessage = $this->model_api_encrypt->errorMessage();
			  return $errorMessage;
			  }*/
			
			
			$data = array ();
			$data ['facilities_id'] = $this->request->post ['facilities_id'];
			
			
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'setting/image' );
			
			$this->load->model ( 'notes/clientstatus' );
			
			$currentdate = $this->request->post ['date_added'];
			
			$date = str_replace ( '-', '/', $currentdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			$this->load->model ( 'setting/timezone' );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
			$facilitytimezone = $timezone_info ['timezone_value'];
			
			date_default_timezone_set ( $facilitytimezone );
			$current_date_user = date ( 'Y-m-d' );
			
			// $config_admin_limit = $this->config->get ( 'all_sync_pagination' );
			
			if ($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != "") {
				$config_admin_limit = $this->config->get ( 'all_sync_pagination' );
			} else {
				$config_admin_limit = "50";
			}
			
			if (isset ( $this->request->post ['page'] )) {
				$page = $this->request->post ['page'];
			} else {
				$page = 1;
			}
			
			if ($this->request->post ['date_added'] != null && $this->request->post ['date_added'] != "") {
				$discharge = '0';
			} else {
				$discharge = '1';
			}
			if ($this->request->post ['is_client_screen'] == "1") {
				$is_client_screen = '1';
			} else {
				$is_client_screen = '';
			}
			if ($facilities_info ['enable_facilityinout'] == 1) {
				$enable_facilityinout = 1;
			} else {
				$enable_facilityinout = 0;
			}
			
			$hourout_arr = array();

			$data3 = array ();
			$data3 ['facilities_id'] = $this->request->post ['facilities_id'];
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
			foreach ( $customforms as $customform ) {
				
				$this->data ['clientstatuss'] [] = array (
					'tag_status_id' => $customform ['tag_status_id'],
					'name' => $customform ['name'],
					'facilities_id' => $customform ['facilities_id'],
					'disabled_escorted' => $customform ['disabled_escorted'],
					'display_client' => $customform ['display_client'] 
				);

				$rule_action_content = unserialize($customform['rule_action_content']);

				if ($rule_action_content['out_from_cell'] == "1") {
					$hourout_arr [] = $customform ['tag_status_id'];
				}
				
			}
			
			$facility_inout = '1';	

			if ($hourout_arr != null && $hourout_arr != "") {
				$hourout_arr = implode ( ",", $hourout_arr );
				$rolecalls = $hourout_arr;
			}
			
			
			$datat3 = array ();
			$datat3 = array (
					'status' => 1,
					'discharge' => $discharge,
					'rolecalls' => $rolecalls,
					//'app_user_date' => $currentdate,
					//'current_date_user' => $current_date_user,
					'is_client_screen' => $is_client_screen,
					//'gender2' => $this->request->post ['gender'],
					'sort' => 'emp_first_name',
					'facilities_id' => $this->request->post ['facilities_id'],
					//'emp_tag_id_2' => $this->request->post ['search_tags'],
					//'updatedtagsids' => $this->request->post ['updatedtagsids'],
					'enable_facilityinout' => $enable_facilityinout,
					// 'wait_list' => $this->request->post['wait_list'],
					// 'all_record' => '1',
					'is_master' => '1',
					'is_submaster' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			
			
			$tags = $this->model_setting_tags->getTags ( $datat3 );
			
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/locations' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			$unique_id = $facility ['customer_key'];
			
			// var_dump($unique_id); die;
			
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize ( $customer_info ['client_info_notes'] );
			
			if (! empty ( $customer_info ['setting_data'] )) {
				$customers = unserialize ( $customer_info ['setting_data'] );
			}
			
			$client_view_options2 = $client_info ["client_view_options"];
			$client_view_options_details = $client_info ["client_details_view_options"];
			
			
			
			$this->data ['tags'] = array ();
			if (! empty ( $tags )) {
				foreach ( $tags as $tag ) {
					
					$rule_action_content = unserialize($tag['rule_action_content']);

					$percent = 0;
					$hourout = 0;
					
					
					$out_from_cell = $rule_action_content['out_from_cell'];
					
					if($rule_action_content['out_from_cell']){

						$houroutdata = array();
						$houroutdata['tags_id'] = $tag ['tags_id'];
						$houroutdata['currentdate'] = date('Y-m-d');
						$houroutdata['rules_operation'] = $customers ['rules_operation'];
						$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
						$houroutdata['rules_end_time'] = $customers ['rules_end_time'];
						$outcelltime = $this->model_setting_tags->getOutToCellTime ( $houroutdata );

						$totaltime = '';
						
						if($outcelltime['totaltime']!=NULL || $outcelltime['totaltime']!=""){
							$totaltime = $outcelltime['totaltime'];
						}else{
							$totaltime = 0;
						}

						if($tag ['notes_id'] > 0){

							$noesData = $this->model_notes_notes->getnotes($tag ['notes_id']);
							
							if(!empty($noesData)){
								$dataprogress = array();
								$dataprogress['date_a'] = date('Y-m-d H:i:s'); 
								$dataprogress['date_added'] = $noesData ['date_added'];
								$dataprogress['duration_type'] = $customers ['duration_type'];
								$dataprogress['out_the_sell'] = $customers ['out_the_sell'];
								$dataprogress['totaltime'] = $totaltime;
								$response = $this->model_setting_tags->getHourOutProgress ( $dataprogress );
								$hourout = $response['hourout'];
								$percent = $response['inPercent'];



								
							}
						}
					}
					
					
					$this->data ['tags'] [] = array (
							'tags_id' => $tag ['tags_id'],
							'out_from_cell' => $out_from_cell,
							'hourout_in_words' => $hourout,
							'hourout_in_percent' => $percent,
							
					);
				}
			} else {
				$this->data ['facilitiess'] [] = array ();
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
				
				$this->response->setOutput ( json_encode ( $value ) );
				return;
			}
			
			
			$this->data ['keywords'] = array ();
			
			
			$this->data ['facilitiess'] [] = array (
					'tags' => $this->data ['tags'],
					
			);
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonhoutClientLists ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonhoutClientLists', $activity_data2 );
		}
	}
	
	
	public function secondsToTime($seconds) {
		$dtF = new \DateTime ( '@0' );
		$dtT = new \DateTime ( "@$seconds" );
		
		$since_start = $dtF->diff ( $dtT );
		$caltime = "";
		if ($since_start->y > 0) {
			$caltime .= $since_start->y . ' Years ';
		}
		
		if ($since_start->m > 0) {
			$caltime .= $since_start->m . ' Months ';
		}
		
		if ($since_start->d > 0) {
			$caltime .= $since_start->d . ' Days ';
		}
		
		if ($since_start->h > 0) {
			$caltime .= $since_start->h . ' Hour(s) ';
		}
		
		if ($since_start->i > 0) {
			$caltime .= $since_start->i . ' Minutes ';
		}
		
		return $caltime;
	}
}

