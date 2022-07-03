<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
header ( "Content-type: bitmap; charset=utf-8" );
class Controllerservicesdisplaynotes extends Controller {
	
	public function jsongetNotesByApp() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsongetNotesByApp', $this->request->post, 'request' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}*/
			
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'setting/image' );
			$this->load->model ( 'notes/image' );
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'notes/notescomment' );
			
			if (isset ( $this->request->post ['keyword'] )) {
				$keyword = $this->request->post ['keyword'];
			}
			
			if (isset ( $this->request->post ['facilities_id'] )) {
				$facilities_id = $this->request->post ['facilities_id'];
			}
			
			if (isset ( $this->request->post ['user_id'] )) {
				$user_id11 = $this->request->post ['user_id'];
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($user_id11);
				
				$user_id = $user_info['username'];
			}
			if (isset ( $this->request->post ['tasktype'] )) {
				$tasktype = $this->request->post ['tasktype'];
			}
			
			if ($this->request->post ['note_date_from'] != null && $this->request->post ['note_date_from'] != "") {
				$note_date_from = date ( 'Y-m-d', strtotime ( $this->request->post ['note_date_from'] ) );
			}
			if ($this->request->post ['note_date_to'] != null && $this->request->post ['note_date_to'] != "") {
				$note_date_to = date ( 'Y-m-d', strtotime ( $this->request->post ['note_date_to'] ) );
			}
			
			if ($this->request->post ['searchdate'] != null && $this->request->post ['searchdate'] != "") {
				$this->data ['note_date'] = $this->request->post ['searchdate'];
				$date = str_replace ( '-', '/', $this->request->post ['searchdate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [0] . "-" . $res [1] . "-" . $res [2];
				
				$searchdate = $changedDate; // date('Y-m-d', strtotime($this->request->post['searchdate']));
			} else {
				$this->data ['note_date'] = date ( 'd-m-Y' );
			}
			
			if (isset ( $this->request->post ['advance_search'] )) {
				$advance_search = $this->request->post ['advance_search'];
			}
			
			if (isset ( $this->request->post ['page'] )) {
				$page = $this->request->post ['page'];
			} else {
				$page = 1;
			}
			
			
			if($this->request->post ['is_web'] == '1'){
				$config_admin_limit = "50";
			}else{
				$config_admin_limit1 = $this->config->get ( 'config_android_front_limit' );
				if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
					$config_admin_limit = $config_admin_limit1;
				} else {
					$config_admin_limit = "25";
				}
			}
			
			if (isset ( $this->request->post ['sync'] )) {
				$sync_data = $this->request->post ['sync'];
			}
			if (isset ( $this->request->post ['notetime'] )) {
				$notetime = $this->request->post ['notetime'];
			}
			
			if (isset ( $this->request->post ['tags_id'] )) {
				$tags_id = $this->request->post ['tags_id'];
			}
			
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$this->data ['is_master_facility'] = $facilityinfo ['is_master_facility'];
			
			/*
			 * if($this->request->post['sync'] == '2'){
			 *
			 * date_default_timezone_set($this->request->post['facilitytimezone']);
			 *
			 * if ($this->request->post['last_notes_id'] != null && $this->request->post['last_notes_id'] != "") {
			 * $notes_infos = $this->model_notes_notes->getnotes($this->request->post['last_notes_id']);
			 * }
			 *
			 * if ($notes_infos != null && $notes_infos != "") {
			 * $notetime = date('H:i:s', strtotime("+0 minutes", strtotime($notes_infos['update_date'])));
			 * } else {
			 * $notetime = date('H:i:s', strtotime("-2 minutes", strtotime('now')));
			 * }
			 * }
			 */
			$ddss = array ();
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				$this->data ['is_master_facility'] = '1';
				$ddss [] = $facilityinfo ['notes_facilities_ids'];
				$ddss [] = $facilities_id;
				$sssssdd = implode ( ",", $ddss );
			} else {
				$this->data ['is_master_facility'] = '2';
			}
			
			
			$data = array (
					'sort' => $sort,
					'order' => $order,
					'is_app' => 1,
					'facilities_id' => $facilities_id,
					'notes_facilities_ids' => $sssssdd,
					'search_facilities_id' => $this->request->post ['search_facilities_id'],
					'facilities_timezone' => $this->request->post ['facilitytimezone'],
					'tag_classification_id' => $this->request->post ['tag_classification_id'],
					'tag_status_id' => $this->request->post ['tag_status_id'],
					'manual_movement' => $this->request->post ['manual_movement'],
					'notes_id' => $this->request->post ['notes_id'],
					
					// 'current_date' => $this->request->post['current_date'],
					'notetime' => $notetime,
					'sync_data' => $sync_data,
					'searchdate' => $searchdate,
					'note_date_from' => $note_date_from,
					'note_date_to' => $note_date_to,
					'keyword' => $keyword,
					'tasktype' => $tasktype,
					'user_id' => $user_id,
					'advance_search' => $advance_search,
					'emp_tag_id' => $tags_id,
					'is_web' => $this->request->post ['is_web'],
					
			);
			
			$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
			
			
			$notes_total2 = ceil ( $notes_total / $config_admin_limit );
			
			if($this->request->post ['is_web'] == '1'){
				if($notes_total2 > 1){
					if (isset ( $this->request->post ['page'] )) {
						$page1 = $page;
					}else{
						$page1 = $notes_total2;
					}
				}else{
					$page1 = $page;
				}
			}else{
				$page1 = $page;
			}
			
			
			$data = array (
					'sort' => $sort,
					'order' => $order,
					'is_app' => 1,
					'facilities_id' => $facilities_id,
					'notes_facilities_ids' => $sssssdd,
					'search_facilities_id' => $this->request->post ['search_facilities_id'],
					'facilities_timezone' => $this->request->post ['facilitytimezone'],
					'tag_classification_id' => $this->request->post ['tag_classification_id'],
					'tag_status_id' => $this->request->post ['tag_status_id'],
					'manual_movement' => $this->request->post ['manual_movement'],
					'notes_id' => $this->request->post ['notes_id'],
					
					// 'current_date' => $this->request->post['current_date'],
					'notetime' => $notetime,
					'sync_data' => $sync_data,
					'searchdate' => $searchdate,
					'note_date_from' => $note_date_from,
					'note_date_to' => $note_date_to,
					'keyword' => $keyword,
					'tasktype' => $tasktype,
					'user_id' => $user_id,
					'advance_search' => $advance_search,
					'emp_tag_id' => $tags_id,
					'is_web' => $this->request->post ['is_web'],
					'start' => ($page1 - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			//var_dump($data);
			
			$results = $this->model_notes_notes->getnotess ( $data );
			
			
			
			
			$this->load->model ( 'setting/highlighter' );
			$this->load->model ( 'user/user' );
			$this->load->model ( 'setting/keywords' );
			$this->load->model ( 'notes/tags' );
			
			$this->load->model ( 'setting/tags' );
			
			if ($results != null && $results != "") {
				foreach ( $results as $result ) {
					
					if ($result ['highlighter_id'] > 0) {
						$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
						$highlighter_value = $highlighterData ['highlighter_value'];
					} else {
						$highlighterData = array ();
						$highlighter_value = '';
					}
					
					if ($result ['strike_date_added'] != null && $result ['strike_date_added'] != "0000-00-00 00:00:00") {
						$strikeDate = date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['strike_date_added'] ) );
					} else {
						$strikeDate = '';
					}
					
					if ($result ['signature'] != null && $result ['signature'] != "") {
						$signaturesrc = $result ['signature'];
					} else {
						$signaturesrc = '';
					}
					
					if ($result ['strike_signature'] != null && $result ['strike_signature'] != "") {
						$strike_signature = $result ['strike_signature'];
					} else {
						$strike_signature = '';
					}
					
					if ($result ['strike_pin'] != null && $result ['strike_pin'] != "") {
						$strikePin = '1';
					} else {
						$strikePin = '';
					}
					
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$notesPin = '1';
					} else {
						$notesPin = '';
					}
					
					if ($result ['text_color'] != null && $result ['text_color'] != "") {
						$text_color = $result ['text_color'];
					} else {
						$text_color = '';
					}
					
					if ($result ['task_time'] != null && $result ['task_time'] != "00:00:00") {
						$task_time = date ( 'h:i A', strtotime ( $result ['task_time'] ) );
					} else {
						$task_time = "";
					}
					
					$notestasks = array ();
					$grandtotal = 0;
					$ograndtotal = 0;
					if ($result ['task_type'] == '1') {
						$alltasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '1' );
						foreach ( $alltasks as $alltask ) {
							$grandtotal = $grandtotal + $alltask ['capacity'];
							$tags_ids_names = '';
							if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
								$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
								
								foreach ( $tags_ids1 as $tag1 ) {
									$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$tags_ids_names .= $emp_tag_id . ', ';
									}
								}
							}
							$out_tags_ids_names = "";
							$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
							
							if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
								$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
								$i = 0;
								foreach ( $tags_ids1 as $tag1 ) {
									$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$out_tags_ids_names .= $emp_tag_id . ', ';
									}
									$i ++;
								}
								// $ograndtotal = $i;
							}
							
							if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
								$medication_attach_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' ) );
							} else {
								$medication_attach_url = "";
							}
							
							$taskTime = "";
							if ($alltask ['task_time'] != null && $alltask ['task_time'] != '00:00:00') {
								$taskTime = date ( 'h:i A', strtotime ( $alltask ['task_time'] ) );
							}
							
							$notestasks [] = array (
									'notes_by_task_id' => $alltask ['notes_by_task_id'],
									'locations_id' => $alltask ['locations_id'],
									'task_type' => $alltask ['task_type'],
									'task_content' => $alltask ['task_content'],
									'user_id' => $alltask ['user_id'],
									// 'signature' => $alltask['signature'],
									// 'notes_pin' => $alltask['notes_pin'],
									 'task_time' => $taskTime,
									// 'media_url' => $alltask['media_url'],
									'capacity' => $alltask ['capacity'],
									'location_name' => $alltask ['location_name'],
									'location_type' => $alltask ['location_type'],
									'notes_task_type' => $alltask ['notes_task_type'],
									'task_comments' => $alltask ['task_comments'],
									'role_call' => $alltask ['role_call'],
									// 'medication_file_upload' => $alltask['medication_attach_url'],
									'medication_file_upload' => $medication_attach_url,
									'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
									'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
									'tags_ids_names' => $tags_ids_names,
									'out_tags_ids_names' => $out_tags_ids_names 
							)
							;
						}
					}
					
					$notesmedicationtasks = array ();
					if ($result ['task_type'] == '2') {
						$alltmasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '2' );
						
						foreach ( $alltmasks as $alltmask ) {
							$taskTime = "";
							if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
								$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
							}
							
							if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
								$media_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' ) );
							} else {
								$media_url = "";
							}
							
							$notesmedicationtasks [] = array (
									'notes_by_task_id' => $alltmask ['notes_by_task_id'],
									'locations_id' => $alltmask ['locations_id'],
									'task_type' => $alltmask ['task_type'],
									'task_content' => $alltmask ['task_content'],
									'user_id' => $alltmask ['user_id'],
									'signature' => $alltmask ['signature'],
									'notes_pin' => $alltmask ['notes_pin'],
									'task_time' => $taskTime,
									// 'media_url' => $alltmask['media_url'],
									'media_url' => $media_url,
									'capacity' => $alltmask ['capacity'],
									'location_name' => $alltmask ['location_name'],
									'location_type' => $alltmask ['location_type'],
									'notes_task_type' => $alltmask ['notes_task_type'],
									'tags_id' => $alltmask ['tags_id'],
									'drug_name' => $alltmask ['drug_name'],
									'dose' => $alltmask ['dose'],
									'drug_type' => $alltmask ['drug_type'],
									'quantity' => $alltmask ['quantity'],
									'frequency' => $alltmask ['frequency'],
									'instructions' => $alltmask ['instructions'],
									'count' => $alltmask ['count'],
									'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
									'task_comments' => $alltmask ['task_comments'],
									'role_call' => $alltmask ['role_call'],
									'refuse' => $alltmask ['refuse'],
									'medication_file_upload' => $alltmask ['medication_file_upload'],
									'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
							)
							;
						}
					}
					
					$noteskeywords = array ();
					if ($result ['keyword_file'] == '1') {
						$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					} else {
						$allkeywords = array ();
					}
					
					if ($allkeywords) {
						$keyImageSrc12 = array ();
						$keyname = array ();
						foreach ( $allkeywords as $allkeyword ) {
							$noteskeywords [] = array (
									'notes_by_keyword_id' => $allkeyword ['notes_by_keyword_id'],
									'notes_id' => $allkeyword ['notes_id'],
									'keyword_id' => $allkeyword ['keyword_id'],
									'keyword_name' => $allkeyword ['keyword_name'],
									'keyword_file_url' => $allkeyword ['keyword_file_url'],
									'keyword_image' => $allkeyword ['keyword_image'],
									'img_icon' => $allkeyword ['keyword_file_url'] 
							);
							
							$keyImageSrc11 = $allkeyword ['keyword_file_url'];
							$keyImageSrc12 [] = $keyImageSrc11 . '&nbsp;' . $allkeyword ['keyword_name'];
							$keyname [] = $allkeyword ['keyword_name'];
						}
						$keyword_description = str_replace ( $keyname, $keyImageSrc12, $result ['notes_description'] );
						
						$notes_description2 = $keyword_description;
					} else {
						$notes_description2 = '';
					}
					
					if ($result ['is_census'] == '1') {
						$is_census_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/dailycensus/censusdetail', '' . '&notes_id=' . $result ['notes_id'] . '&facilities_id=' . $result ['facilities_id'] ) );
					} else {
						$is_census_url = '';
					}
					
					if ($result ['is_tag'] != '0') {
						$is_tag_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/addclient', '' . '&tags_id=' . $result ['is_tag'] . '&notes_id=' . $result ['notes_id'] . '&facilities_id=' . $result ['facilities_id'] . '&is_archive=' . $note_info ['is_archive'] ) );
					} else {
						$is_tag_url = '';
					}
					
					
					if ($result ['original_task_time'] != null && $result ['original_task_time'] != "00:00:00") {
						$original_task_time = date ( 'h:i A', strtotime ( $result ['original_task_time'] ) );
					} else {
						$original_task_time = "";
					}
					
					if ($result ['user_id'] == SYSTEM_GENERATED) {
						$auto_generate = '1';
					} else {
						$auto_generate = '0';
					}
					
					
					$facilitynames = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
					$facilityname = $facilitynames ['facility'];
					
					if ($result ['user_file'] != null && $result ['user_file'] != "") {
						$user_file = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result ['notes_id'], 'SSL' ) );
					} else {
						$user_file = "";
					}
					if ($result ['is_comment'] == '2') {
						$printtranscript = $this->url->link ( 'notes/transcript/printtranscript', '' . '&notes_id=' . $result ['notes_id'] . $url, 'SSL' );
					} else {
						$printtranscript = '';
					}
					
					$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'],$this->request->post ['facilities_id'] );
					
					$uptimes = array();
					$uptimes = $this->model_notes_notes->getupdatetime ($result ['notes_id']);
					
					if($result['form_type'] == '7'){
						$notetime = $uptimes['notetime'];
					}else{
						$notetime = $result['notetime'];
					}
					
					$case_file_id = '';
					$tags_id = '';
					
					$case_file_id = '';
					$tags_id = '';
					$case_number='';

					if($result ['notes_id']!=''){
						$cdata['notes_id'] = $result ['notes_id'];
						$this->load->model ( 'resident/casefile' );
						$case_info = $this->model_resident_casefile->getcasefilesbynotesid ( $cdata );
						$case_file_id = $case_info['notes_by_case_file_id'];
						$tags_id = $case_info['tags_ids'];
						$case_number = $case_info['case_number'];
					}else{
						$case_file_id = '';
						$tags_id = '';
						$case_number='';
					}
					
					
					$alltag = array ();
					$alltaga = array ();
					if ($this->request->post ['config_tag_status'] == '1') {
						if ($result ['emp_tag_id'] == '1') {
							$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
							
							$alltaga [] = array (
									'notes_tags_id' => $alltag ['notes_tags_id'],
									'tags_id' => $alltag ['tags_id'],
									'emp_tag_id' => $alltag ['emp_tag_id'],
									'user_id' => $alltag ['user_id'],
									'notes_type' => $alltag ['notes_type'],
									'notes_pin' => $alltag ['notes_pin'],
									'signature' => $alltag ['signature'],
									'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $alltag ['date_added'] ) ) 
							);
						} else {
							$alltag = array ();
							$alltaga = array ();
						}
						
						if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
							$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
							$privacy = $tagdata ['privacy'];
							
							$emp_tag_id = $alltag ['emp_tag_id'] . ': ';
						} else {
							$emp_tag_id = '';
							$privacy = '';
						}
					} else {
						$privacy = '';
					}
					
					
					$notescomments = array ();
					if ($result ['is_comment'] == '1') {
						$allcomments = $this->model_notes_notescomment->getcomments ( $result ['notes_id'] );
					} else {
						$allcomments = array ();
					}
					
					if ($allcomments) {
						foreach ( $allcomments as $allcomment ) {
							$commentskeywords = array ();
							if ($allcomment ['keyword_file'] == '1') {
								$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
							} else {
								$aallkeywords = array ();
							}
							
							if ($aallkeywords) {
								$keyImageSrc12 = array ();
								$keyname = array ();
								foreach ( $aallkeywords as $callkeyword ) {
									$commentskeywords [] = array (
											'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
											'notes_id' => $callkeyword ['notes_id'],
											'comment_id' => $callkeyword ['comment_id'],
											'keyword_id' => $callkeyword ['keyword_id'],
											'keyword_name' => $callkeyword ['keyword_name'],
											'keyword_file_url' => $callkeyword ['keyword_file_url'],
											'keyword_image' => $callkeyword ['keyword_image'],
											'img_icon' => $callkeyword ['keyword_file_url'] 
									);
								}
							}
							$notescomments [] = array (
									'comment_id' => $allcomment ['comment_id'],
									'notes_id' => $allcomment ['notes_id'],
									'facilities_id' => $allcomment ['facilities_id'],
									'comment' => $allcomment ['comment'],
									'user_id' => $allcomment ['user_id'],
									'notes_pin' => $allcomment ['notes_pin'],
									'signature' => $allcomment ['signature'],
									'user_file' => $allcomment ['user_file'],
									'is_user_face' => $allcomment ['is_user_face'],
									'date_added' => $allcomment ['date_added'],
									'comment_date' => $allcomment ['comment_date'],
									'notes_type' => $allcomment ['notes_type'],
									'commentskeywords' => $commentskeywords 
							);
						}
					}
					
					$this->data ['facilitiess'] [] = array (
							'notes_id' => $result ['notes_id'],
							
							'shift_color_value'=>$shift_time_color['shift_color_value'],
							'is_comment' => $result ['is_comment'],
							'case_file_id' => $case_file_id,
							'tags_id' => $tags_id,
							'case_number' => $case_number,
							'in_total' => $result ['in_total'],
							'out_total' => $result ['out_total'],
							'manual_total' => $result ['manual_total'],
							'facilityname' => $facilityname,
							'facilities_id' => $result ['facilities_id'],
							//'uptimes' => $uptimes,
							'printtranscript' => $printtranscript,
							'is_user_face' => $result ['is_user_face'],
							'is_approval_required_forms_id' => $result ['is_approval_required_forms_id'],
							'user_file' => $result ['user_file'],
							// 'user_file' => $user_file,
							'auto_generate' => $auto_generate,
							//'original_task_time' => $original_task_time,
							//'geolocation_info' => $geolocation_info,
							//'approvaltask' => $approvaltask,
							'notes_file' => $result ['notes_file'],
							//'keyword_file' => $result ['keyword_file'],
							'emp_tag_id' => $result ['emp_tag_id'],
							'is_forms' => $result ['is_forms'],
							'is_reminder' => $result ['is_reminder'],
							'task_type' => $result ['task_type'],
							'checklist_status' => $result ['checklist_status'],
							'visitor_log' => $result ['visitor_log'],
							'is_tag' => $result ['is_tag'],
							'is_archive' => $result ['is_archive'],
							'is_tag_url' => $is_tag_url,
							'form_type' => $result ['form_type'],
							'generate_report' => $result ['generate_report'],
							'is_census' => $result ['is_census'],
							'is_census_url' => $is_census_url,
							'is_android' => $result ['is_android'],
							'task_time' => $task_time,
							'review_notes' => $result ['review_notes'],
							'is_offline' => $result ['is_offline'],
							'noteskeywords' => $noteskeywords,
							'alltag' => $alltaga,
							//'images' => $images,
							//'incidentforms' => $forms,
							'notestasks' => $notestasks,
							//'grandtotal' => $grandtotal,
							//'ograndtotal' => $ograndtotal,
							//'boytotals' => $boytotals,
							//'girltotals' => $girltotals,
							//'generaltotals' => $generaltotals,
							//'residentstotals' => $residentstotals,
							'notesmedicationtasks' => $notesmedicationtasks,
							
							'tag_privacy' => $privacy,
							'taskadded' => $result ['taskadded'],
							'notes_type' => $result ['notes_type'],
							'highlighter_value' => $highlighter_value,
							'notes_description' => html_entity_decode ( str_replace ( '&#039;', '\'', $result ['notes_description'] ) ),
							// 'notes_description2' => $notes_description2,
							// 'attachment_icon' => $keyImageSrc,
							// 'attachment_url' => $outputFolderUrl,
							//'keyword_icon' => $keyword_icon,
							'notetime' => $notetime,
							'notetimemili' => strtotime ( $result ['notetime'] ),
							'username' => $result ['user_id'],
							'signature' => $signaturesrc,
							'notes_pin' => $notesPin,
							'text_color_cut' => $result ['text_color_cut'],
							'text_color' => $text_color,
							'note_date' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['note_date'] ) ),
							'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['date_added'] ) ),
							'dateFormated' => date ( 'D F j, Y', strtotime ( $result ['date_added'] ) ),
							'update_date_time' => date ( 'H:i:s', strtotime ( $result ['update_date'] ) ),
							'strike_user_name' => $result ['strike_user_id'],
							'strike_signature' => $strike_signature,
							'strike_date_added' => $strikeDate,
							'strike_pin' => $strikePin,
							'notescomments' => $notescomments,
							'reminder_title' => '', // $reminderTitle,
							'reminder_time' => '' 
					) // $reminderTime,
;
				}
				$error = true;
			} else {
				$this->data ['facilitiess'] = array ();
				$error = true;
				$taskTotal = 0;
			}
			
			if ($this->config->get ( 'config_task_status' ) == '1') {
				
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
				
				$this->load->model ( 'createtask/createtask' );
				$top = '2';
				
				if (isset ( $this->request->post ['facilitytimezone'] )) {
					$facilities_timezone = $this->request->post ['facilitytimezone'];
					date_default_timezone_set ( $facilities_timezone );
				}
				
				$date = str_replace ( '-', '/', $searchdate );
				$res = explode ( "/", $date );
				
				$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
				
				$currentdate = $changedDate;
				
				$taskTotal = $this->model_createtask_createtask->getCountTasklist ( $facilities_id, $currentdate, $top, '', '','' );
			} else {
				$taskTotal = 0;
			}
			
			
			
			
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'Tasktotal' => $taskTotal,
					'status' => $error,
					'total_note' => $notes_total2,
					'last_page' => $notes_total2,
					'total_note1' => $notes_total 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in displaynotes jsongetNotesByApp ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsongetNotesByApp', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	
	public function jsongetNotesByPageByApp() {
		try {
			
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsongetNotesByPageByApp', $this->request->post, 'request' );
			
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
			
			$this->load->model ( 'facilities/facilities' );
			
			if ($this->request->post ['search_time_start'] != null && $this->request->post ['search_time_start'] != "") {
				
				if ($this->request->post ['search_time_to'] == null && $this->request->post ['search_time_to'] == "") {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Please select Correct time' 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => $error 
					);
					
					$this->response->setOutput ( json_encode ( $value ) );
					
					return false;
				}
			}
			
			if ($this->request->post ['search_time_to'] != null && $this->request->post ['search_time_to'] != "") {
				
				if ($this->request->post ['search_time_start'] == null && $this->request->post ['search_time_start'] == "") {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Please select Correct time' 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => $error 
					);
					
					$this->response->setOutput ( json_encode ( $value ) );
					
					return false;
				}
			}
			
			$this->data ['facilitiess'] = array ();
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'setting/image' );
			$this->load->model ( 'notes/image' );
			$this->load->model ( 'notes/notescomment' );
			
			if (isset ( $this->request->post ['keyword'] )) {
				$keyword = $this->request->post ['keyword'];
			}
			
			if (isset ( $this->request->post ['form_search'] )) {
				$form_search = $this->request->post ['form_search'];
			}
			
			if (isset ( $this->request->post ['facilities_id'] )) {
				$facilities_id = $this->request->post ['facilities_id'];
			}
			
			if (isset ( $this->request->post ['highlighter'] )) {
				$highlighter = $this->request->post ['highlighter'];
			}
			
			if (isset ( $this->request->post ['activenote'] )) {
				$activenote = $this->request->post ['activenote'];
			}
			
			if (isset ( $this->request->post ['sync'] )) {
				$sync_data = $this->request->post ['sync'];
			}
			
			if (isset ( $this->request->post ['user_id'] )) {
				$user_id11 = $this->request->post ['user_id'];
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($user_id11);
				
				$user_id = $user_info['username'];
			}
			if (isset ( $this->request->post ['tasktype'] )) {
				$tasktype = $this->request->post ['tasktype'];
			}
			
			if ($this->request->post ['note_date_from'] != null && $this->request->post ['note_date_from'] != "") {
				$date = str_replace ( '-', '/', $this->request->post ['note_date_from'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
				
				$note_date_from = $changedDate; // date('Y-m-d', strtotime($this->request->post['note_date_from']));
			}
			if ($this->request->post ['note_date_to'] != null && $this->request->post ['note_date_to'] != "") {
				$date1 = str_replace ( '-', '/', $this->request->post ['note_date_to'] );
				$res1 = explode ( "/", $date1 );
				$changedDate1 = $res1 [2] . "-" . $res1 [1] . "-" . $res1 [0];
				
				$note_date_to = $changedDate1; // date('Y-m-d', strtotime($this->request->post['note_date_to']));
			}
			
			if ($this->request->post ['searchdate'] != null && $this->request->post ['searchdate'] != "") {
				$this->data ['note_date'] = $this->request->post ['searchdate'];
				$searchdate = date ( 'Y-m-d', strtotime ( $this->request->post ['searchdate'] ) );
			} else {
				$this->data ['note_date'] = date ( 'd-m-Y' );
			}
			
			if (isset ( $this->request->post ['advance_search'] )) {
				$advance_search = $this->request->post ['advance_search'];
				$group = '1';
			}
			
			if (isset ( $this->request->post ['emp_tag_id'] )) {
				$emp_tag_id = $this->request->post ['emp_tag_id'];
			}
			
			if (isset ( $this->request->post ['search_time_start'] )) {
				$search_time_start = $this->request->post ['search_time_start'];
			}
			
			if (isset ( $this->request->post ['search_time_to'] )) {
				$search_time_to = $this->request->post ['search_time_to'];
			}
			
			if (isset ( $this->request->post ['page'] )) {
				$page = $this->request->post ['page'];
			} else {
				$page = 1;
			}
			
			$config_admin_limit1 = $this->config->get ( 'config_android_front_limit' );
			if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
				$config_admin_limit = $config_admin_limit1;
			} else {
				$config_admin_limit = "25";
			}
			
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$this->data ['is_master_facility'] = $facilityinfo ['is_master_facility'];
			
			$ddss = array ();
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				$this->data ['is_master_facility'] = '1';
				$ddss [] = $facilityinfo ['notes_facilities_ids'];
				
				$ddss [] = $facilities_id;
				$sssssdd = implode ( ",", $ddss );
			} else {
				$this->data ['is_master_facility'] = '2';
			}
			
			$dataform = array (
					'sort' => $sort,
					'order' => $order,
					'is_app' => 1,
					'facilities_id' => $facilities_id,
					'notes_facilities_ids' => $sssssdd,
					'search_facilities_id' => $this->request->post ['search_facilities_id'],
					'facilities_timezone' => $this->request->post ['facilitytimezone'],
					'tag_classification_id' => $this->request->post ['tag_classification_id'],
					'tag_status_id' => $this->request->post ['tag_status_id'],
					'manual_movement' => $this->request->post ['manual_movement'],
					'notes_id' => $this->request->post ['notes_id'],
					'sync_data' => $sync_data,
					'searchdate' => $searchdate,
					'note_date_from' => $note_date_from,
					'note_date_to' => $note_date_to,
					'group' => $group,
					
					'search_time_start' => $search_time_start,
					'search_time_to' => $search_time_to,
					
					'keyword' => $keyword,
					'tasktype' => $tasktype,
					'highlighter' => $highlighter,
					'activenote' => $activenote,
					'form_search' => $form_search,
					'user_id' => $user_id,
					'advance_search' => $advance_search,
					'emp_tag_id' => $emp_tag_id,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			$results = $this->model_notes_notes->getnotess ( $dataform );
			
			$notes_total = $this->model_notes_notes->getTotalnotess ( $dataform );
			
			$this->load->model ( 'setting/highlighter' );
			$this->load->model ( 'user/user' );
			$this->load->model ( 'setting/keywords' );
			$this->load->model ( 'notes/tags' );
			$this->load->model ( 'setting/tags' );
			
			if ($results != null && $results != "") {
				foreach ( $results as $result ) {
					
					if ($result ['highlighter_id'] > 0) {
						$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
						$highlighter_value = $highlighterData ['highlighter_value'];
					} else {
						$highlighterData = array ();
						$highlighter_value = '';
					}
					
					if ($result ['strike_date_added'] != null && $result ['strike_date_added'] != "0000-00-00 00:00:00") {
						$strikeDate = date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['strike_date_added'] ) );
					} else {
						$strikeDate = '';
					}
					
					if ($result ['signature'] != null && $result ['signature'] != "") {
						$signaturesrc = $result ['signature'];
					} else {
						$signaturesrc = '';
					}
					
					if ($result ['strike_signature'] != null && $result ['strike_signature'] != "") {
						$strike_signature = $result ['strike_signature'];
					} else {
						$strike_signature = '';
					}
					
					if ($result ['strike_pin'] != null && $result ['strike_pin'] != "") {
						$strikePin = '1';
					} else {
						$strikePin = '';
					}
					
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$notesPin = '1';
					} else {
						$notesPin = '';
					}
					
					if ($result ['text_color'] != null && $result ['text_color'] != "") {
						$text_color = $result ['text_color'];
					} else {
						$text_color = '';
					}
					
					if ($result ['task_time'] != null && $result ['task_time'] != "00:00:00") {
						$task_time = date ( 'h:i A', strtotime ( $result ['task_time'] ) );
					} else {
						$task_time = "";
					}
					
					$notestasks = array ();
					$grandtotal = 0;
					$ograndtotal = 0;
					if ($result ['task_type'] == '1') {
						$alltasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '1' );
						foreach ( $alltasks as $alltask ) {
							$grandtotal = $grandtotal + $alltask ['capacity'];
							$tags_ids_names = '';
							if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
								$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
								
								foreach ( $tags_ids1 as $tag1 ) {
									$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$tags_ids_names .= $emp_tag_id . ', ';
									}
								}
							}
							$out_tags_ids_names = "";
							$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
							
							if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
								$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
								$i = 0;
								foreach ( $tags_ids1 as $tag1 ) {
									$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$out_tags_ids_names .= $emp_tag_id . ', ';
									}
									$i ++;
								}
								// $ograndtotal = $i;
							}
							
							if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
								$medication_attach_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' ) );
							} else {
								$medication_attach_url = "";
							}
							
							$taskTime = "";
							if ($alltask ['task_time'] != null && $alltask ['task_time'] != '00:00:00') {
								$taskTime = date ( 'h:i A', strtotime ( $alltask ['task_time'] ) );
							}
							
							$notestasks [] = array (
									'notes_by_task_id' => $alltask ['notes_by_task_id'],
									'locations_id' => $alltask ['locations_id'],
									'task_type' => $alltask ['task_type'],
									'task_content' => $alltask ['task_content'],
									'user_id' => $alltask ['user_id'],
									// 'signature' => $alltask['signature'],
									// 'notes_pin' => $alltask['notes_pin'],
									 'task_time' => $taskTime,
									// 'media_url' => $alltask['media_url'],
									'capacity' => $alltask ['capacity'],
									'location_name' => $alltask ['location_name'],
									'location_type' => $alltask ['location_type'],
									'notes_task_type' => $alltask ['notes_task_type'],
									'task_comments' => $alltask ['task_comments'],
									'role_call' => $alltask ['role_call'],
									// 'medication_file_upload' => $alltask['medication_attach_url'],
									'medication_file_upload' => $medication_attach_url,
									'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
									'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
									'tags_ids_names' => $tags_ids_names,
									'out_tags_ids_names' => $out_tags_ids_names 
							)
							;
						}
					}
					
					$notesmedicationtasks = array ();
					if ($result ['task_type'] == '2') {
						$alltmasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '2' );
						
						foreach ( $alltmasks as $alltmask ) {
							$taskTime = "";
							if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
								$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
							}
							
							if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
								$media_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' ) );
							} else {
								$media_url = "";
							}
							
							$notesmedicationtasks [] = array (
									'notes_by_task_id' => $alltmask ['notes_by_task_id'],
									'locations_id' => $alltmask ['locations_id'],
									'task_type' => $alltmask ['task_type'],
									'task_content' => $alltmask ['task_content'],
									'user_id' => $alltmask ['user_id'],
									'signature' => $alltmask ['signature'],
									'notes_pin' => $alltmask ['notes_pin'],
									'task_time' => $taskTime,
									// 'media_url' => $alltmask['media_url'],
									'media_url' => $media_url,
									'capacity' => $alltmask ['capacity'],
									'location_name' => $alltmask ['location_name'],
									'location_type' => $alltmask ['location_type'],
									'notes_task_type' => $alltmask ['notes_task_type'],
									'tags_id' => $alltmask ['tags_id'],
									'drug_name' => $alltmask ['drug_name'],
									'dose' => $alltmask ['dose'],
									'drug_type' => $alltmask ['drug_type'],
									'quantity' => $alltmask ['quantity'],
									'frequency' => $alltmask ['frequency'],
									'instructions' => $alltmask ['instructions'],
									'count' => $alltmask ['count'],
									'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
									'task_comments' => $alltmask ['task_comments'],
									'role_call' => $alltmask ['role_call'],
									'refuse' => $alltmask ['refuse'],
									'medication_file_upload' => $alltmask ['medication_file_upload'],
									'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
							)
							;
						}
					}
					
					$noteskeywords = array ();
					if ($result ['keyword_file'] == '1') {
						$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					} else {
						$allkeywords = array ();
					}
					
					if ($allkeywords) {
						$keyImageSrc12 = array ();
						$keyname = array ();
						foreach ( $allkeywords as $allkeyword ) {
							$noteskeywords [] = array (
									'notes_by_keyword_id' => $allkeyword ['notes_by_keyword_id'],
									'notes_id' => $allkeyword ['notes_id'],
									'keyword_id' => $allkeyword ['keyword_id'],
									'keyword_name' => $allkeyword ['keyword_name'],
									'keyword_file_url' => $allkeyword ['keyword_file_url'],
									'keyword_image' => $allkeyword ['keyword_image'],
									'img_icon' => $allkeyword ['keyword_file_url'] 
							);
							
							$keyImageSrc11 = $allkeyword ['keyword_file_url'];
							$keyImageSrc12 [] = $keyImageSrc11 . '&nbsp;' . $allkeyword ['keyword_name'];
							$keyname [] = $allkeyword ['keyword_name'];
						}
						$keyword_description = str_replace ( $keyname, $keyImageSrc12, $result ['notes_description'] );
						
						$notes_description2 = $keyword_description;
					} else {
						$notes_description2 = '';
					}
					
					if ($result ['is_census'] == '1') {
						$is_census_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/dailycensus/censusdetail', '' . '&notes_id=' . $result ['notes_id'] . '&facilities_id=' . $result ['facilities_id'] ) );
					} else {
						$is_census_url = '';
					}
					
					if ($result ['is_tag'] != '0') {
						$is_tag_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/resident/addclient', '' . '&tags_id=' . $result ['is_tag'] . '&notes_id=' . $result ['notes_id'] . '&facilities_id=' . $result ['facilities_id'] . '&is_archive=' . $note_info ['is_archive'] ) );
					} else {
						$is_tag_url = '';
					}
					
					
					if ($result ['original_task_time'] != null && $result ['original_task_time'] != "00:00:00") {
						$original_task_time = date ( 'h:i A', strtotime ( $result ['original_task_time'] ) );
					} else {
						$original_task_time = "";
					}
					
					if ($result ['user_id'] == SYSTEM_GENERATED) {
						$auto_generate = '1';
					} else {
						$auto_generate = '0';
					}
					
					
					$facilitynames = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
					$facilityname = $facilitynames ['facility'];
					
					if ($result ['user_file'] != null && $result ['user_file'] != "") {
						$user_file = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result ['notes_id'], 'SSL' ) );
					} else {
						$user_file = "";
					}
					if ($result ['is_comment'] == '2') {
						$printtranscript = $this->url->link ( 'notes/transcript/printtranscript', '' . '&notes_id=' . $result ['notes_id'] . $url, 'SSL' );
					} else {
						$printtranscript = '';
					}
					
					$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'],$this->request->post ['facilities_id'] );
					
					$uptimes = array();
					$uptimes = $this->model_notes_notes->getupdatetime ($result ['notes_id']);
					
					if($result['form_type'] == '7'){
						$notetime = $uptimes['notetime'];
					}else{
						$notetime = $result['notetime'];
					}
					
					$case_file_id = '';
					$tags_id = '';
					
					$tags_id = '';
					$case_number='';

					if($result ['notes_id']!=''){
						$cdata['notes_id'] = $result ['notes_id'];
						$this->load->model ( 'resident/casefile' );
						$case_info = $this->model_resident_casefile->getcasefilesbynotesid ( $cdata );
						$case_file_id = $case_info['notes_by_case_file_id'];
						$tags_id = $case_info['tags_ids'];
						$case_number = $case_info['case_number'];
					}else{
						$case_file_id = '';
						$tags_id = '';
						$case_number='';
					}
					
					
					$alltag = array ();
					$alltaga = array ();
					if ($this->request->post ['config_tag_status'] == '1') {
						if ($result ['emp_tag_id'] == '1') {
							$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
							
							$alltaga [] = array (
									'notes_tags_id' => $alltag ['notes_tags_id'],
									'tags_id' => $alltag ['tags_id'],
									'emp_tag_id' => $alltag ['emp_tag_id'],
									'user_id' => $alltag ['user_id'],
									'notes_type' => $alltag ['notes_type'],
									'notes_pin' => $alltag ['notes_pin'],
									'signature' => $alltag ['signature'],
									'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $alltag ['date_added'] ) ) 
							);
						} else {
							$alltag = array ();
							$alltaga = array ();
						}
						
						if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
							$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
							$privacy = $tagdata ['privacy'];
							
							$emp_tag_id = $alltag ['emp_tag_id'] . ': ';
						} else {
							$emp_tag_id = '';
							$privacy = '';
						}
					} else {
						$privacy = '';
					}
					
					$notescomments = array ();
					if ($result ['is_comment'] == '1') {
						$allcomments = $this->model_notes_notescomment->getcomments ( $result ['notes_id'] );
					} else {
						$allcomments = array ();
					}
					
					if ($allcomments) {
						foreach ( $allcomments as $allcomment ) {
							$commentskeywords = array ();
							if ($allcomment ['keyword_file'] == '1') {
								$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
							} else {
								$aallkeywords = array ();
							}
							
							if ($aallkeywords) {
								$keyImageSrc12 = array ();
								$keyname = array ();
								foreach ( $aallkeywords as $callkeyword ) {
									$commentskeywords [] = array (
											'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
											'notes_id' => $callkeyword ['notes_id'],
											'comment_id' => $callkeyword ['comment_id'],
											'keyword_id' => $callkeyword ['keyword_id'],
											'keyword_name' => $callkeyword ['keyword_name'],
											'keyword_file_url' => $callkeyword ['keyword_file_url'],
											'keyword_image' => $callkeyword ['keyword_image'],
											'img_icon' => $callkeyword ['keyword_file_url'] 
									);
								}
							}
							$notescomments [] = array (
									'comment_id' => $allcomment ['comment_id'],
									'notes_id' => $allcomment ['notes_id'],
									'facilities_id' => $allcomment ['facilities_id'],
									'comment' => $allcomment ['comment'],
									'user_id' => $allcomment ['user_id'],
									'notes_pin' => $allcomment ['notes_pin'],
									'signature' => $allcomment ['signature'],
									'user_file' => $allcomment ['user_file'],
									'is_user_face' => $allcomment ['is_user_face'],
									'date_added' => $allcomment ['date_added'],
									'comment_date' => $allcomment ['comment_date'],
									'notes_type' => $allcomment ['notes_type'],
									'commentskeywords' => $commentskeywords 
							);
						}
					}
					
					$this->data ['facilitiess'] [] = array (
							'notes_id' => $result ['notes_id'],
							'shift_color_value'=>$shift_time_color['shift_color_value'],
							'is_comment' => $result ['is_comment'],
							'case_file_id' => $case_file_id,
							'tags_id' => $tags_id,
							'case_number' => $case_number,
							'in_total' => $result ['in_total'],
							'out_total' => $result ['out_total'],
							'manual_total' => $result ['manual_total'],
							//'uptimes' => $uptimes,
							'printtranscript' => $printtranscript,
							'facilityname' => $facilityname,
							'facilities_id' => $result ['facilities_id'],
							'is_user_face' => $result ['is_user_face'],
							'is_approval_required_forms_id' => $result ['is_approval_required_forms_id'],
							'user_file' => $result ['user_file'],
							// 'user_file' => $user_file,
							'auto_generate' => $auto_generate,
							//'original_task_time' => $original_task_time,
							//'geolocation_info' => $geolocation_info,
							//'approvaltask' => $approvaltask,
							'notes_file' => $result ['notes_file'],
							//'keyword_file' => $result ['keyword_file'],
							'emp_tag_id' => $result ['emp_tag_id'],
							'is_forms' => $result ['is_forms'],
							'is_reminder' => $result ['is_reminder'],
							'task_type' => $result ['task_type'],
							'is_offline' => $result ['is_offline'],
							'visitor_log' => $result ['visitor_log'],
							'is_tag' => $result ['is_tag'],
							'is_archive' => $result ['is_archive'],
							'is_tag_url' => $is_tag_url,
							'generate_report' => $result ['generate_report'],
							'form_type' => $result ['form_type'],
							'is_census' => $result ['is_census'],
							'is_census_url' => $is_census_url,
							'is_android' => $result ['is_android'],
							'review_notes' => $result ['review_notes'],
							'checklist_status' => $result ['checklist_status'],
							'task_time' => $task_time,
							'alltag' => $alltaga,
							'noteskeywords' => $noteskeywords,
							//'images' => $images,
							//'incidentforms' => $forms,
							'notestasks' => $notestasks,
							//'grandtotal' => $grandtotal,
							//'ograndtotal' => $ograndtotal,
							//'boytotals' => $boytotals,
							//'girltotals' => $girltotals,
							//'generaltotals' => $generaltotals,
							//'residentstotals' => $residentstotals,
							'notesmedicationtasks' => $notesmedicationtasks,
							'tag_privacy' => $privacy,
							'taskadded' => $result ['taskadded'],
							'notes_type' => $result ['notes_type'],
							'highlighter_value' => $highlighter_value,
							'notes_description' => html_entity_decode ( str_replace ( '&#039;', '\'', $result ['notes_description'] ) ),
							// 'notes_description2' => $notes_description2,
							// 'attachment_icon' => $keyImageSrc,
							// 'attachment_url' => $outputFolderUrl,
							//'keyword_icon' => $keyword_icon,
							'notetime' => $notetime,
							'notetimemili' => strtotime ( $result ['notetime'] ),
							'username' => $result ['user_id'],
							'signature' => $signaturesrc,
							'notes_pin' => $notesPin,
							'text_color_cut' => $result ['text_color_cut'],
							'text_color' => $text_color,
							'note_date' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['note_date'] ) ),
							'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['date_added'] ) ),
							'date_added2' => date ( 'D F j, Y', strtotime ( $result ['date_added'] ) ),
							'update_date_time' => date ( 'H:i:s', strtotime ( $result ['update_date'] ) ),
							'strike_user_name' => $result ['strike_user_id'],
							'strike_signature' => $strike_signature,
							'strike_date_added' => $strikeDate,
							'strike_pin' => $strikePin,
							'notescomments' => $notescomments,
							'reminder_title' => '', // $reminderTitle,
							'reminder_time' => '' 
					) // $reminderTime,
;
				}
				$error = true;
			} else {
				$this->data ['facilitiess'] = array ();
				$error = true;
			}
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error,
					'total_note' => $notes_total 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in displaynotes jsongetNotesByPageByApp ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsongetNotesByPageByApp', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	
	

}	