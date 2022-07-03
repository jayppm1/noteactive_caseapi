<?php
class Controllernotescreatetask extends Controller {
	private $error = array ();
	public function index() {
		try {
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			if (! $this->customer->isLogged ()) {
				
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			
			$this->load->model ( 'createtask/createtask' );
			
			$url = "";
			$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
			if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
				$config_admin_limit = $config_admin_limit1;
			} else {
				$config_admin_limit = "50";
			}
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			date_default_timezone_set ( $timezone_name );
			
			$data = array (
					'searchdate' => date ( 'm-d-Y' ),
					'searchdate_app' => '1',
					'facilities_id' => $facilities_id 
			);
			
			$this->load->model ( 'notes/notes' );
			$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
			
			$this->load->model ( 'setting/bedchecktaskform' );
			
			$data2 = array (
					'status' => '1',
					'facilities_id' => $facilities_id 
			);
			
			$this->data ['bedcheck'] = $this->model_setting_bedchecktaskform->getBCTFs ( $data2 );
			
			$this->load->model ( 'facilities/facilities' );
			$this->data ['facilities'] = $this->model_facilities_facilities->getfacilitiess ();
			
			/*
			 * $this->load->model('setting/tags');
			 * $this->data['transport_tags'] =
			 * $this->model_settiing_tags->getTags();
			 */
			
			$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
			
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if ($pagenumber_all > 1) {
					$url .= '&page=' . $pagenumber_all;
				}
			}
			
			$this->data ['redirecturl'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url, 'SSL' ) );
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
				
				$this->load->model ( 'facilities/facilities' );
				$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				if ($resulsst ['is_master_facility'] == '1') {
					if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
						$facilities_id = $this->session->data ['search_facilities_id'];
						$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						$this->load->model ( 'setting/timezone' );
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
						$timezone_name = $timezone_info ['timezone_value'];
					} else {
						$facilities_id = $this->customer->getId ();
						$timezone_name = $this->customer->isTimezone ();
					}
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
				
				if($this->request->post['facilities']!=null && $this->request->post['facilities']!=""){

				 	foreach ($this->request->post['facilities'] as $facilities_id) {
				
						$this->model_createtask_createtask->addcreatetask ( $this->request->post, $facilities_id );
						
						$this->data ['taskcreated'] = '1';
					}
				}else{

					$this->model_createtask_createtask->addcreatetask ( $this->request->post, $facilities_id );
					$this->data ['taskcreated'] = '1';

                }
			}
			
			/*
			 * if($this->request->post['recurnce_hrly'] !="" &&
			 * $this->request->post['recurnce_hrly'] !=NULL ){
			 *
			 * $interval = abs(strtotime($this->request->post['endtime']) -
			 * strtotime($this->request->post['taskTime']));
			 * $recData = round($interval / 60);
			 * $this->data['taskinterval'] = round($recData /
			 * $this->request->post['recurnce_hrly']);
			 *
			 * var_dump($this->data['taskinterval']);
			 * }
			 */
			
			$this->data ['tasktypes'] = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
			$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
			
			$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
			$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
			$this->data ['config_rules_status'] = $this->customer->isRule ();
			
			$this->data ['username'] = $this->customer->getfacility ();
			$this->data ['facilities_id'] = $facilities_id;
			
			if (isset ( $this->error ['description'] )) {
				$this->data ['error_description'] = $this->error ['description'];
			} else {
				$this->data ['error_description'] = '';
			}
			
			if (isset ( $this->error ['warning'] )) {
				$this->data ['error_warning'] = $this->error ['warning'];
			} else {
				$this->data ['error_warning'] = '';
			}
			
			if (isset ( $this->error ['endtime'] )) {
				$this->data ['error_time_error'] = $this->error ['endtime'];
			} else {
				$this->data ['error_time_error'] = '';
			}
			
			if (isset ( $this->error ['assignto_sms'] )) {
				$this->data ['error_assignto_sms'] = $this->error ['assignto_sms'];
			} else {
				$this->data ['error_assignto_sms'] = '';
			}
			
			if (isset ( $this->error ['assignto_email'] )) {
				$this->data ['error_assignto_email'] = $this->error ['assignto_email'];
			} else {
				$this->data ['error_assignto_email'] = '';
			}
			
			if (isset ( $this->error ['assignto'] )) {
				$this->data ['error_assignto'] = $this->error ['assignto'];
			} else {
				$this->data ['error_assignto'] = '';
			}
			
			// date_default_timezone_set($this->session->data['time_zone_1']);
			
			date_default_timezone_set ( $timezone_name );
			
			if (isset ( $this->request->post ['required_approval'] )) {
				$this->data ['required_approval'] = $this->request->post ['required_approval'];
			} else {
				$this->data ['required_approval'] = '';
			}
			
			if (isset ( $this->request->post ['taskDate'] )) {
				
				$task_date = strtotime ( $this->request->post ['taskDate'] );
				
				if ($task_date == false) {
					$newData = $this->request->post ['taskDate'];
				} else {
					
					$newData = date ( 'm-d-Y', $task_date );
				}
				
				// $res = explode("-", $this->request->post['taskDate']);
				// $createdate1 = $res[1]."-".$res[0]."-".$res[2];
				
				$this->data ['taskDate'] = $this->request->post ['taskDate']; // $newData;//date('j
					                                                            // F
					                                                            // Y',
					                                                            // strtotime($newData));
			} else {
				$this->data ['taskDate'] = date ( 'm-d-Y' );
			}
			
			if (isset ( $this->request->post ['recurrence'] )) {
				$this->data ['recurrence'] = $this->request->post ['recurrence'];
			} else {
				$this->data ['recurrence'] = '';
			}
			
			if (isset ( $this->request->post ['numChecklist'] )) {
				$this->data ['numChecklist'] = $this->request->post ['numChecklist'];
			} else {
				$this->data ['numChecklist'] = '';
			}
			
			if (isset ( $this->request->post ['taskTime'] )) {
				$this->data ['taskTime'] = $this->request->post ['taskTime'];
			} else {
				$this->data ['taskTime'] = date ( 'h:i A', strtotime ( ' +3 minutes', strtotime ( 'now' ) ) );
			}
			if (isset ( $this->request->post ['endtime'] )) {
				$this->data ['endtime'] = $this->request->post ['endtime'];
			} else {
				$this->data ['endtime'] = date ( 'h:i A', strtotime ( ' +60 minutes', strtotime ( 'now' ) ) );
			}
			if (isset ( $this->request->post ['tasktype'] )) {
				$this->data ['tasktype'] = $this->request->post ['tasktype'];
			} else {
				$this->data ['tasktype'] = '';
			}
			
			if (isset ( $this->request->post ['recurnce_hrly'] )) {
				$this->data ['recurnce_hrly'] = $this->request->post ['recurnce_hrly'];
			} else {
				$this->data ['recurnce_hrly'] = '';
			}
			
			if (isset ( $this->request->post ['end_recurrence_date'] )) {
				$end_recurrence_date1 = strtotime ( $this->request->post ['end_recurrence_date'] );
				
				if ($end_recurrence_date1 == false) {
					$newData2 = $data ['end_recurrence_date'];
				} else {
					
					$newData2 = date ( 'm-d-Y', $end_recurrence_date1 );
				}
				
				$this->data ['end_recurrence_date'] = $this->request->post ['end_recurrence_date']; // $newData2;//date('j
					                                                                                  // F
					                                                                                  // Y',
					                                                                                  // strtotime($newData2));
			} else {
				$this->data ['end_recurrence_date'] = date ( 'm-d-Y' );
			}
			
			if (isset ( $this->request->post ['required_assign'] )) {
				$this->data ['required_assign'] = $this->request->post ['required_assign'];
			} else {
				$this->data ['required_assign'] = '';
			}
			
			if (isset ( $this->request->post ['recurnce_week'] )) {
				$this->data ['recurnce_week'] = $this->request->post ['recurnce_week'];
			} else {
				$this->data ['recurnce_week'] = '';
			}
			
			if (isset ( $this->request->post ['recurnce_month'] )) {
				$this->data ['recurnce_month'] = $this->request->post ['recurnce_month'];
			} else {
				$this->data ['recurnce_month'] = '';
			}
			
			if (isset ( $this->request->post ['recurnce_day'] )) {
				$this->data ['recurnce_day'] = $this->request->post ['recurnce_day'];
			} else {
				$this->data ['recurnce_day'] = '';
			}
			
			if (isset ( $this->request->post ['assignto'] )) {
				$this->data ['assignto'] = $this->request->post ['assignto'];
			} else {
				$this->data ['assignto'] = '';
			}
			
			if (isset ( $this->request->post ['description'] )) {
				$this->data ['description'] = $this->request->post ['description'];
			} else {
				$this->data ['description'] = '';
			}
			
			if (isset ( $this->request->post ['task_alert'] )) {
				$this->data ['task_alert'] = $this->request->post ['task_alert'];
			} else {
				$this->data ['task_alert'] = '1';
			}
			
			if (isset ( $this->request->post ['alert_type_none'] )) {
				$this->data ['alert_type_none'] = $this->request->post ['alert_type_none'];
			} else {
				$this->data ['alert_type_none'] = '';
			}
			
			if (isset ( $this->request->post ['alert_type_sms'] )) {
				$this->data ['alert_type_sms'] = $this->request->post ['alert_type_sms'];
			} else {
				$this->data ['alert_type_sms'] = '';
			}
			
			if (isset ( $this->request->post ['alert_type_notification'] )) {
				$this->data ['alert_type_notification'] = $this->request->post ['alert_type_notification'];
			} else {
				$this->data ['alert_type_notification'] = '1';
			}
			
			if (isset ( $this->request->post ['alert_type_email'] )) {
				$this->data ['alert_type_email'] = $this->request->post ['alert_type_email'];
			} else {
				$this->data ['alert_type_email'] = '';
			}
			
			if (isset ( $this->request->post ['assignto_sms'] )) {
				$this->data ['assignto_sms'] = $this->request->post ['assignto_sms'];
			} else {
				$this->data ['assignto_sms'] = '';
			}
			
			if (isset ( $this->request->post ['assignto_email'] )) {
				$this->data ['assignto_email'] = $this->request->post ['assignto_email'];
			} else {
				$this->data ['assignto_email'] = '';
			}
			
			if (isset ( $this->request->post ['task_form_id'] )) {
				$this->data ['task_form_id'] = $this->request->post ['task_form_id'];
			} else {
				$this->data ['task_form_id'] = '';
			}
			
			if (isset ( $this->request->post ['form_task_creation'] )) {
				$this->data ['form_task_creation'] = $this->request->post ['form_task_creation'];
			} elseif (! empty ( $task_info )) {
				$this->data ['form_task_creation'] = $task_info ['form_task_creation'];
			} else {
				$this->data ['form_task_creation'] = '';
			}
			
			if (isset ( $this->request->post ['transport_tags'] )) {
				$transport_tags1 = $this->request->post ['transport_tags'];
			} elseif (! empty ( $task_info )) {
				$transport_tags1 = explode ( ',', $task_info ['transport_tags'] );
			} else {
				$transport_tags1 = array ();
			}
			
			$this->data ['transport_tags'] = array ();
			$this->load->model ( 'setting/tags' );
			
			foreach ( $transport_tags1 as $tag1 ) {
				
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info) {
					$this->data ['transport_tags'] [] = array (
							'tags_id' => $tags_info ['tags_id'],
							'emp_tag_id' => $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'] 
					);
				}
			}
			
			if (isset ( $this->request->post ['locations'] )) {
				$this->data ['locations'] = $this->request->post ['locations'];
			} else {
				$this->data ['locations'] = array ();
			}
			
			if (isset ( $this->request->post ['iswaypoint'] )) {
				$this->data ['iswaypoint'] = $this->request->post ['iswaypoint'];
			} elseif (! empty ( $task_info )) {
				$this->data ['iswaypoint'] = $task_info ['iswaypoint'];
			} else {
				$this->data ['iswaypoint'] = '';
			}
			
			
			if (isset ( $this->request->post ['pickup_locations_address'] )) {
				$this->data ['pickup_locations_address'] = $this->request->post ['pickup_locations_address'];
			} elseif (! empty ( $task_info )) {
				$this->data ['pickup_locations_address'] = $task_info ['pickup_locations_address'];
			} else {
				$this->data ['pickup_locations_address'] = '';
			}
			
			if (isset ( $this->request->post ['pickup_locations_latitude'] )) {
				$this->data ['pickup_locations_latitude'] = $this->request->post ['pickup_locations_latitude'];
			} elseif (! empty ( $task_info )) {
				$this->data ['pickup_locations_latitude'] = $task_info ['pickup_locations_latitude'];
			} else {
				$this->data ['pickup_locations_latitude'] = '';
			}
			
			if (isset ( $this->request->post ['pickup_locations_longitude'] )) {
				$this->data ['pickup_locations_longitude'] = $this->request->post ['pickup_locations_longitude'];
			} elseif (! empty ( $task_info )) {
				$this->data ['pickup_locations_longitude'] = $task_info ['pickup_locations_longitude'];
			} else {
				$this->data ['pickup_locations_longitude'] = '';
			}
			
			if (isset ( $this->request->post ['pickup_locations_time'] )) {
				$this->data ['pickup_locations_time'] = $this->request->post ['pickup_locations_time'];
			} elseif (! empty ( $task_info )) {
				$this->data ['pickup_locations_time'] = date ( "h:i A", strtotime ( $task_info ['pickup_locations_time'] ) );
			} else {
				$this->data ['pickup_locations_time'] = '';
			}
			
			if (isset ( $this->request->post ['dropoff_facilities_id'] )) {
				$this->data ['dropoff_facilities_id'] = $this->request->post ['dropoff_facilities_id'];
			} elseif (! empty ( $task_info )) {
				$this->data ['dropoff_facilities_id'] = $task_info ['dropoff_facilities_id'];
			} else {
				$this->data ['dropoff_facilities_id'] = '';
			}
			
			if (isset ( $this->request->post ['dropoff_locations_address'] )) {
				$this->data ['dropoff_locations_address'] = $this->request->post ['dropoff_locations_address'];
			} elseif (! empty ( $task_info )) {
				$this->data ['dropoff_locations_address'] = $task_info ['dropoff_locations_address'];
			} else {
				$this->data ['dropoff_locations_address'] = '';
			}
			
			if (isset ( $this->request->post ['dropoff_locations_latitude'] )) {
				$this->data ['dropoff_locations_latitude'] = $this->request->post ['dropoff_locations_latitude'];
			} elseif (! empty ( $task_info )) {
				$this->data ['dropoff_locations_latitude'] = $task_info ['dropoff_locations_latitude'];
			} else {
				$this->data ['dropoff_locations_latitude'] = '';
			}
			
			if (isset ( $this->request->post ['dropoff_locations_longitude'] )) {
				$this->data ['dropoff_locations_longitude'] = $this->request->post ['dropoff_locations_longitude'];
			} elseif (! empty ( $task_info )) {
				$this->data ['dropoff_locations_longitude'] = $task_info ['dropoff_locations_longitude'];
			} else {
				$this->data ['dropoff_locations_longitude'] = '';
			}
			
			if (isset ( $this->request->post ['dropoff_locations_time'] )) {
				$this->data ['dropoff_locations_time'] = $this->request->post ['dropoff_locations_time'];
			} elseif (! empty ( $task_info )) {
				$this->data ['dropoff_locations_time'] = date ( "h:i A", strtotime ( $task_info ['dropoff_locations_time'] ) );
			} else {
				$this->data ['dropoff_locations_time'] = '';
			}
			
			if (isset ( $this->request->post ['recurnce_hrly_recurnce'] )) {
				$this->data ['recurnce_hrly_recurnce'] = $this->request->post ['recurnce_hrly_recurnce'];
			} else {
				$this->data ['recurnce_hrly_recurnce'] = '';
			}
			
			if (isset ( $this->request->post ['medication_tags'] )) {
				$medication_tags1 = $this->request->post ['medication_tags'];
			} elseif (! empty ( $task_info )) {
				$medication_tags1 = explode ( ',', $task_info ['medication_tags'] );
			} else {
				$medication_tags1 = array ();
			}
			
			$this->data ['medication_tags'] = array ();
			$this->load->model ( 'setting/tags' );
			
			foreach ( $medication_tags1 as $key => $tag12 ) {
				if (is_array ( $tag12 )) {
					$tags_medication_detailsids = $tag12 ["tags_medication_details_id"];
				} else {
					$tags_info1 = $this->model_setting_tags->getTag ( $tag12 );
				}
				
				if ($tags_info1) {
					$this->data ['medication_tags'] [] = array (
							'tags_id' => $tags_info1 ['tags_id'],
							'emp_tag_id' => $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'],
							'tagsmedications' => $this->model_setting_tags->getTagsMedicationdetails ( $tags_info1 ['tags_id'] ),
							'tags_medicatiuons' => $tags_medication_detailsids 
					);
				}
			}
			
			if (isset ( $this->request->post ['tags_medication_details_ids'] )) {
				$this->data ['tags_medication_details_ids'] = $this->request->post ['tags_medication_details_ids'];
			} elseif (! empty ( $task_info )) {
				$this->data ['tags_medication_details_ids'] = explode ( ',', $task_info ['tags_medication_details_ids'] );
			} else {
				$this->data ['tags_medication_details_ids'] = array ();
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$this->load->model ( 'notes/tags' );
				$tag_info = $this->model_notes_tags->getTag ( $this->request->get ['tags_id'] );
			}
			
			if (isset ( $this->request->post ['emp_tag_id1'] )) {
				$this->data ['emp_tag_id1'] = $this->request->post ['emp_tag_id1'];
			} elseif (! empty ( $tag_info )) {
				$this->data ['emp_tag_id1'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
			} else {
				$this->data ['emp_tag_id1'] = '';
			}
			
			if (isset ( $this->request->post ['emp_tag_id'] )) {
				$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
			} elseif (! empty ( $tag_info )) {
				$this->data ['emp_tag_id'] = $tag_info ['tags_id'];
			} else {
				$this->data ['emp_tag_id'] = '';
			}
			
			if (isset ( $this->request->post ['recurnce_hrly_perpetual'] )) {
				$this->data ['recurnce_hrly_perpetual'] = $this->request->post ['recurnce_hrly_perpetual'];
			} elseif (! empty ( $task_info )) {
				$this->data ['recurnce_hrly_perpetual'] = $task_info ['recurnce_hrly_perpetual'];
			} else {
				$this->data ['recurnce_hrly_perpetual'] = '';
			}
			
			if (isset ( $this->request->post ['completion_alert'] )) {
				$this->data ['completion_alert'] = $this->request->post ['completion_alert'];
			} elseif (! empty ( $task_info )) {
				$this->data ['completion_alert'] = $task_info ['completion_alert'];
			} else {
				$this->data ['completion_alert'] = '';
			}
			if (isset ( $this->request->post ['completion_alert_type_sms'] )) {
				$this->data ['completion_alert_type_sms'] = $this->request->post ['completion_alert_type_sms'];
			} elseif (! empty ( $task_info )) {
				$this->data ['completion_alert_type_sms'] = $task_info ['completion_alert_type_sms'];
			} else {
				$this->data ['completion_alert_type_sms'] = '';
			}
			if (isset ( $this->request->post ['completion_alert_type_email'] )) {
				$this->data ['completion_alert_type_email'] = $this->request->post ['completion_alert_type_email'];
			} elseif (! empty ( $task_info )) {
				$this->data ['completion_alert_type_email'] = $task_info ['completion_alert_type_email'];
			} else {
				$this->data ['completion_alert_type_email'] = '';
			}
			
			if (isset ( $this->request->post ['user_roles'] )) {
				$user_roles1 = $this->request->post ['user_roles'];
			} elseif (! empty ( $task_info )) {
				$user_roles1 = explode ( ',', $task_info ['user_roles'] );
			} else {
				$user_roles1 = array ();
			}
			
			// var_dump($this->request->post['user_roles']);
			if (isset ( $this->request->post ['due_date_time'] )) {
				$this->data ['due_date_time'] = $this->request->post ['due_date_time'];
			} else {
				$this->data ['due_date_time'] = date ( 'm-d-Y h:i A', strtotime ( ' +60 minutes', strtotime ( 'now' ) ) );
			}
			
			$this->data ['user_roles'] = array ();
			$this->load->model ( 'user/user_group' );
			
			foreach ( $user_roles1 as $user_role ) {
				
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_role );
				
				if ($user_role_info) {
					$this->data ['user_roles'] [] = array (
							'user_group_id' => $user_role,
							'name' => $user_role_info ['name'] 
					);
				}
			}
			
			// var_dump($this->request->post['userids']);
			
			if (isset ( $this->request->post ['userids'] )) {
				$userids1 = $this->request->post ['userids'];
			} elseif (! empty ( $task_info )) {
				$userids1 = explode ( ',', $task_info ['userids'] );
			} else {
				$userids1 = array ();
			}
			
			$this->data ['userids'] = array ();
			$this->load->model ( 'user/user' );
			
			foreach ( $userids1 as $userid ) {
				
				$user_info = $this->model_user_user->getUserbyupdate ( $userid );
				
				if ($user_info) {
					$this->data ['userids'] [] = array (
							'user_id' => $userid,
							'username' => $user_info ['username'] 
					);
				}
			}
			
			if (isset ( $this->request->post ['task_status'] )) {
				$this->data ['task_status'] = $this->request->post ['task_status'];
			} elseif (! empty ( $task_info )) {
				$this->data ['task_status'] = $task_info ['task_status'];
			} else {
				$this->data ['task_status'] = '';
			}
			
			if (isset ( $this->request->post ['visitation_tag_id'] )) {
				$this->data ['visitation_tag_id'] = $this->request->post ['visitation_tag_id'];
			} else {
				$this->data ['visitation_tag_id'] = '';
			}
			
			if (isset ( $this->request->post ['visitation_category_title'] )) {
				$this->data ['visitation_category_title'] = $this->request->post ['visitation_category_title'];
			} else {
				$this->data ['visitation_category_title'] = '';
			}
			
			if (isset ( $this->request->post ['visitation_start_address'] )) {
				$this->data ['visitation_start_address'] = $this->request->post ['visitation_start_address'];
			} else {
				$this->data ['visitation_start_address'] = '';
			}
			
			if (isset ( $this->request->post ['visitation_start_time'] )) {
				$this->data ['visitation_start_time'] = $this->request->post ['visitation_start_time'];
			} else {
				$this->data ['visitation_start_time'] = '';
			}
			
			if (isset ( $this->request->post ['visitation_appoitment_address'] )) {
				$this->data ['visitation_appoitment_address'] = $this->request->post ['visitation_appoitment_address'];
			} else {
				$this->data ['visitation_appoitment_address'] = '';
			}
			
			if (isset ( $this->request->post ['visitation_appoitment_time'] )) {
				$this->data ['visitation_appoitment_time'] = $this->request->post ['visitation_appoitment_time'];
			} else {
				$this->data ['visitation_appoitment_time'] = '';
			}
			
			date_default_timezone_set ( $timezone_name );
			
			if (isset ( $this->request->post ['daily_endtime'] )) {
				$this->data ['daily_endtime'] = $this->request->post ['daily_endtime'];
			} else {
				$this->data ['daily_endtime'] = date ( "h:i A" );
			}
			
			if (isset ( $this->request->post ['daily_times'] )) {
				$this->data ['daily_times'] = $this->request->post ['daily_times'];
			} else {
				$this->data ['daily_times'] = array ();
			}
			if (isset ( $this->request->post ['complete_endtime'] )) {
				$this->data ['complete_endtime'] = $this->request->post ['complete_endtime'];
			} else {
				$this->data ['complete_endtime'] = date ( "h:i A" );
			}
			
			if (isset ( $this->request->post ['completed_times'] )) {
				$this->data ['completed_times'] = $this->request->post ['completed_times'];
			} else {
				$this->data ['completed_times'] = array ();
			}
			
			if (isset ( $this->request->post ['completed_alert'] )) {
				$this->data ['completed_alert'] = $this->request->post ['completed_alert'];
			} else {
				$this->data ['completed_alert'] = '';
			}
			
			if (isset ( $this->request->post ['completed_late_alert'] )) {
				$this->data ['completed_late_alert'] = $this->request->post ['completed_late_alert'];
			} else {
				$this->data ['completed_late_alert'] = '';
			}
			
			if (isset ( $this->request->post ['incomplete_alert'] )) {
				$this->data ['incomplete_alert'] = $this->request->post ['incomplete_alert'];
			} else {
				$this->data ['incomplete_alert'] = '';
			}
			
			if (isset ( $this->request->post ['attachement_form'] )) {
				$this->data ['attachement_form'] = $this->request->post ['attachement_form'];
			} else {
				$this->data ['attachement_form'] = '';
			}
			
			if (isset ( $this->request->post ['tasktype_form_id'] )) {
				$this->data ['tasktype_form_id'] = $this->request->post ['tasktype_form_id'];
			} else {
				$this->data ['tasktype_form_id'] = '';
			}
			if (isset ( $this->request->post ['reminder_alert'] )) {
				$this->data ['reminder_alert'] = $this->request->post ['reminder_alert'];
			} else {
				$this->data ['reminder_alert'] = '';
			}
			
			if (isset ( $this->request->post ['bed_check_facilities_ids'] )) {
				$this->data ['bed_check_facilities_ids'] = $this->request->post ['bed_check_facilities_ids'];
			} else {
				$this->data ['bed_check_facilities_ids'] = array ();
			}
			
			if (isset ( $this->request->post ['reminderplus'] )) {
				$reminders1 = $this->request->post ['reminderplus'];
			}  /*
			   * elseif (! empty($task_info)) {
			   * $reminders1 = explode(',', $task_info['reminders']);
			   * }
			   */
else {
				$reminders1 = array ();
			}
			
			$this->data ['reminders1'] = array ();
			foreach ( $reminders1 as $reminder ) {
				$this->data ['reminders1'] [] = array (
						'minute' => $reminder ['minute'],
						'action' => 'plus' 
				);
			}
			
			if (isset ( $this->request->post ['reminderminus'] )) {
				$reminders2 = $this->request->post ['reminderminus'];
			}  /*
			   * elseif (! empty($task_info)) {
			   * $reminders1 = explode(',', $task_info['reminders']);
			   * }
			   */
else {
				$reminders2 = array ();
			}
			
			foreach ( $reminders2 as $reminder2 ) {
				$this->data ['reminders1'] [] = array (
						'minute' => $reminder2 ['minute'],
						'action' => 'minus' 
				);
			}
			// var_dump($this->data['reminders1']);
			
			if (isset ( $this->request->post ['bed_check_location_ids'] )) {
				$this->data ['bed_check_location_ids'] = $this->request->post ['bed_check_location_ids'];
			} else {
				$this->data ['bed_check_location_ids'] = array ();
			}
			
			if (isset ( $this->request->post ['weekly_interval'] )) {
				$this->data ['weekly_interval'] = $this->request->post ['weekly_interval'];
			} else {
				$this->data ['weekly_interval'] = array ();
			}
			
			if (isset ( $this->request->post ['bed_check_location_ids'] )) {
				$bed_check_location_ids1 = $this->request->post ['bed_check_location_ids'];
			} elseif (! empty ( $task_info )) {
				$bed_check_location_ids1 = explode ( ',', $task_info ['bed_check_location_ids'] );
			} else {
				$bed_check_location_ids1 = array ();
			}
			
			$this->data ['bed_check_location_ids12'] = array ();
			$this->load->model ( 'setting/tags' );
			
			foreach ( $bed_check_location_ids1 as $task_form_location_id ) {
				
				$task_formloc = $this->model_setting_bedchecktaskform->getbedchecktasklocation ( $task_form_location_id );
				
				if ($task_formloc) {
					$this->data ['bed_check_location_ids12'] [] = array (
							'task_form_location_id' => $task_form_location_id,
							'location_name' => $task_formloc ['location_name'] 
					);
				}
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
						'form_href' => $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
				);
			}
			
			$this->load->model ( 'createtask/createtask' );
			$data2 = array ();
			
			$data2 ['status'] = '1';
			$data2 ['order'] = 'sort_order';
			
			$this->data ['taskstatuss'] = $this->model_createtask_createtask->gettaskstatuss ( $data2 );
			
			if (isset ( $this->request->post ['assign_to'] )) {
				$userids1222 = $this->request->post ['assign_to'];
			} elseif (! empty ( $task_info )) {
				$userids1222 = explode ( ',', $task_info ['assign_to'] );
			} else {
				$userids1222 = array ();
			}
			
			$this->data ['ausers'] = array ();
			$this->load->model ( 'user/user' );
			
			foreach ( $userids1222 as $auserid ) {
				
				$auser_info = $this->model_user_user->getUser ( $auserid );
				
				if ($auser_info) {
					$this->data ['ausers'] [] = array (
							'user_id' => $auserid,
							'username' => $auser_info ['username'] 
					);
				}
			}
			
			if (isset ( $this->request->post ['assign_to_type'] )) {
				$this->data ['assign_to_type'] = $this->request->post ['assign_to_type'];
			} else {
				$this->data ['assign_to_type'] = '';
			}
			
			if (isset ( $this->request->post ['user_role_assign_ids'] )) {
				$user_roles12 = $this->request->post ['user_role_assign_ids'];
			} elseif (! empty ( $task_info )) {
				$user_roles12 = explode ( ',', $task_info ['user_role_assign_ids'] );
			} else {
				$user_roles12 = array ();
			}
			
			$this->data ['auser_roles'] = array ();
			$this->load->model ( 'user/user_group' );
			
			foreach ( $user_roles12 as $auser_role ) {
				
				$auser_role_info = $this->model_user_user_group->getUserGroup ( $auser_role );
				
				if ($auser_role_info) {
					$this->data ['auser_roles'] [] = array (
							'user_group_id' => $auser_role,
							'name' => $auser_role_info ['name'] 
					);
				}
			}
			
			$url2 = "";
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
				
				$this->data ['tags_id_url'] = '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->data ['action'] = $this->url->link ( 'notes/createtask', '' . $url2, 'SSL' );
			$this->load->model ( 'user/user' );
			$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/createtask_form.php';
			$this->children = array (
					'common/headerpopup' 
			);
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Createtask' 
			);
			$this->model_activity_activity->addActivity ( 'sitescreatetask', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function validateForm() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['recurrence'] == "none" || $this->request->post ['recurrence'] == "hourly" || $this->request->post ['daily'] == "hourly") {
			if ($this->request->post ['taskTime'] != null && $this->request->post ['taskTime'] != "") {
				
				$date1 = str_replace ( '-', '/', $this->request->post ['taskDate'] );
				$res1 = explode ( "/", $date1 );
				$changedDate1 = $res1 [2] . "-" . $res1 [0] . "-" . $res1 [1];
				
				$recurnce_hrly = 3;
				$taskTime1 = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $this->request->post ['taskTime'] ) ) );
				
				$taskTime = strtotime ( $taskTime1 );
				
				// var_dump($taskTime);
				
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				
				$current_time = date ( "h:i A" );
				$current_date = date ( "Y-m-d" );
				$current_time1 = strtotime ( $current_time );
				// var_dump($current_time1);
				
				if ($current_date == $changedDate1) {
					// if($this->request->post['tasktype'] != '10'){
					if ($this->request->post ['recurrence'] == 'daily') {
						
						if ($this->request->post ['daily_times'] != null && $this->request->post ['daily_times'] != "") {
							/*
							 * foreach($this->request->post['daily_times'] as
							 * $daily_time){
							 * $daily_time1 = strtotime($daily_time);
							 *
							 * if($current_time1 > $daily_time1){
							 * $this->error['warning'] = "The Task Time must be
							 * greater than the Current Time";
							 * }
							 * }
							 */
						} else {
							
							$daily_endtime1 = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $this->request->post ['daily_endtime'] ) ) );
							
							$daily_endtime = strtotime ( $daily_endtime1 );
							
							if ($current_time1 > $daily_endtime) {
								$this->error ['warning'] = "The Task Time must be greater than the Current Time";
							}
						}
					} else {
						if ($current_time1 > $taskTime) {
							$this->error ['warning'] = "The Task Time must be greater than the Current Time";
						}
					}
					/*
					 * }else{
					 *
					 * $pickup_locations_time1 = date('H:i:s', strtotime('
					 * +'.$recurnce_hrly.'
					 * minutes',strtotime($this->request->post['pickup_locations_time'])));
					 *
					 * $pickup_locations_time =
					 * strtotime($pickup_locations_time1);
					 *
					 * if($current_time1 > $pickup_locations_time){
					 * $this->error['warning'] = "The Task Time must be greater
					 * than the Current Time";
					 * }
					 * }
					 */
				}
			}
		}
		
		// var_dump($this->error);
		
		// die;
		if (trim($this->request->post ['description']) == null && trim($this->request->post ['description']) == "") {
			$this->error ['description'] = 'This is required field';
		}
		
		if ($this->request->post ['recurrence'] == "hourly") {
			if ($this->request->post ['endtime'] != null && $this->request->post ['endtime'] != "") {
				
				if ($this->request->post ['recurrence'] == 'hourly') {
					$recurnce_hrly = $this->request->post ['recurnce_hrly'];
					
					$taskDate = $this->request->post ['taskDate'];
					$date = str_replace ( '-', '/', $taskDate );
					$res = explode ( "/", $date );
					$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
					
					$newdate1 = date ( 'H:i:s', strtotime ( $this->request->post ['taskTime'] ) );
					$endnewdate1 = date ( 'H:i:s', strtotime ( $this->request->post ['endtime'] ) );
					
					$time1 = strtotime ( $newdate1 );
					$time2 = strtotime ( $endnewdate1 );
					$difference = round ( abs ( $time2 - $time1 ) / 3600, 2 );
					
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					
					$current_time = date ( "H:i:s" );
					
					if ($current_time > $endnewdate1) {
						$total_hour = 24 - $difference;
					} else {
						$total_hour = $difference;
					}
					
					$newdate = $this->request->post ['taskTime'];
					$endnewdate = $this->request->post ['endtime'];
					
					$taskTime = date ( 'm-d-Y H:i:s', strtotime ( $newdate ) );
					$endtime = date ( 'm-d-Y H:i:s', strtotime ( $endnewdate ) );
				} else {
					$taskTime = strtotime ( $this->request->post ['taskTime'] );
					$endtime = strtotime ( $this->request->post ['endtime'] );
					
					if ($taskTime > $endtime) {
						$this->error ['endtime'] = "Please select correct end time";
					}
				}
			}
		}
		
		if ($this->request->post ['recurrence'] != "none") {
			if ($this->request->post ['end_recurrence_date'] != null && $this->request->post ['end_recurrence_date'] != "") {
				
				$date = str_replace ( '-', '/', $this->request->post ['taskDate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$date2 = str_replace ( '-', '/', $this->request->post ['end_recurrence_date'] );
				$res2 = explode ( "/", $date2 );
				$changedDate2 = $res2 [2] . "-" . $res2 [0] . "-" . $res2 [1];
				
				$task_date = strtotime ( $changedDate );
				$end_recurrence_date = strtotime ( $changedDate2 );
				
				/*
				 * $timezone_name = $this->customer->isTimezone();
				 * date_default_timezone_set($timezone_name);
				 * $current_date = date('m-d-Y', strtotime('now'));
				 */
				
				if ($task_date > $end_recurrence_date) {
					$this->error ['warning'] = "Please select correct end date";
				}
			}
		}
		
		/*
		 * if ($this->request->post['assignto'] == '' &&
		 * $this->request->post['assignto'] == null) {
		 * $this->error['assignto'] = 'This is required field';
		 *
		 * }
		 */
		
		/*
		 * if ($this->request->post['assignto'] != '') {
		 * $this->load->model('user/user');
		 * $user_info = $this->model_user_user->getUser($this->request->post['assignto']);
		 *
		 * if (empty($user_info)) {
		 * $this->error['assignto'] = 'incorrect username';
		 * }
		 *
		 * $this->load->model('facilities/facilities');
		 * $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		 * $unique_id = $facility['customer_key'];
		 *
		 *
		 * $this->load->model('customer/customer');
		 * $customer_info = $this->model_customer_customer->getcustomerid($unique_id);
		 *
		 * if($user_info['customer_key'] != $customer_info['activecustomer_id']){
		 * $this->error['user_id'] = $this->language->get('error_customer');
		 * }
		 *
		 * if ($user_info['phone_number'] == '0') {
		 *
		 * if ($this->request->post['alert_type_sms'] == '1') {
		 * if ($this->request->post['assignto_sms'] == null && $this->request->post['assignto_sms'] == "") {
		 * $this->error['assignto_sms'] = "Please enter phone number";
		 * }
		 * }
		 * }
		 *
		 * if ($user_info['email'] == null && $user_info['email'] == '') {
		 *
		 * if ($this->request->post['alert_type_email'] == '1') {
		 * if ($this->request->post['assignto_email'] == null && $this->request->post['assignto_email'] == "") {
		 * $this->error['assignto_email'] = "Please enter email";
		 * }
		 * }
		 * }
		 * }
		 */
		
		if ($this->request->post ['assignto_email'] != null && $this->request->post ['assignto_email'] != "") {
			
			// $this->load->model('user/user');
			// $useremail = $this->model_user_user->getTotalUsersByEmail($this->request->post['assignto_email']);
			
			if ($useremail > '0') {
				// $this->error['assignto_email'] = "Email already exists";
			}
		}
		
		if (($this->request->post ['alert_type_email'] == '1') || ($this->request->post ['assignto_sms'] == '1')) {
			
			if ($this->request->post ['assignto'] == '') {
				// $this->error['assignto'] = 'Required username';
			}
		}
		
		if ($this->request->post ['assignto_sms'] != null && $this->request->post ['assignto_sms'] != "") {
			
			// $this->load->model('user/user');
			// $userphone = $this->model_user_user->getUserByPhone($this->request->post['assignto_sms']);
			
			if ($userphone != "0" && $userphone != NULL) {
				// $this->error['assignto_sms'] = "Phone number already exists";
			}
		}
		
		if ($this->request->post ['recurrence'] == 'weekly') {
			if (empty ( $this->request->post ['recurnce_week'] )) {
				$this->error ['warning'] = 'Please check the weekly day';
			}
		}
		
		if ($this->request->post ['tasktype'] == "11") {
			if ($this->request->post ['task_form_id'] == null && $this->request->post ['task_form_id'] == "") {
				$this->error ['warning'] = 'Warning: Select Bed Check Form';
			}
		}
		
		if ($this->request->post ['tasktype'] == "8") {
			if ($this->request->post ['numChecklist'] == null && $this->request->post ['numChecklist'] == "") {
				$this->error ['warning'] = 'Warning: Select Form';
			}
		}
		
		if ($this->request->post ['tasktype'] == "10") {
			if ($this->request->post ['pickup_locations_address'] == null && $this->request->post ['pickup_locations_address'] == "") {
				$this->error ['warning'] = 'Warning: Enter Location Pickup Address';
			}
			
			/*
			 * if($this->request->post['pickup_locations_time'] == null &&
			 * $this->request->post['pickup_locations_time'] == ""){
			 * $this->error['warning'] = 'Warning: Enter Location Pickup Time';
			 * }
			 */
			
			if ($this->request->post ['dropoff_locations_address'] == null && $this->request->post ['dropoff_locations_address'] == "") {
				$this->error ['warning'] = 'Warning: Enter Location Dropoff Address';
			}
			/*
			 * if($this->request->post['dropoff_locations_time'] == null &&
			 * $this->request->post['dropoff_locations_time'] == ""){
			 * $this->error['warning'] = 'Warning: Enter Location Dropoff Time';
			 * }
			 */
		}
		
		if ($this->request->post ['completion_alert'] == '1') {
			if (($this->request->post ['completion_alert_type_sms'] == '') && ($this->request->post ['completion_alert_type_email'] == '')) {
				$this->error ['warning'] = 'Completion Alert Type is required field';
			}
			
			if (($this->request->post ['completion_alert_type_sms'] != '') || ($this->request->post ['completion_alert_type_email'] != '')) {
				if (($this->request->post ['user_roles'] == '') && ($this->request->post ['userids'] == '')) {
					$this->error ['warning'] = 'Please select a Role or a User for Completion Notification';
				}
			}
		}
		
		$this->load->model ( 'createtask/createtask' );
		$tasktype_info = $this->model_createtask_createtask->gettasktyperow ( $this->request->post ['tasktype'] );
		
		if ($tasktype_info ['client_required'] == '1') {
			if (($this->request->post ['emp_tag_id'] == null) && ($this->request->post ['emp_tag_id'] == '')) {
				$this->error ['warning'] = 'Client is required field';
			}
		}
		
		if ($tasktype_info ['field_required'] == '1') {
			if (($this->request->post ['emp_tag_id1'] == null) && ($this->request->post ['emp_tag_id1'] == '')) {
				$this->error ['warning'] = 'Client field is required';
			}
		}
		
		/*
		 * if ($this->request->post['recurrence'] == 'Perpetual') {
		 * if ($this->request->post['tasktype'] == '25') {
		 *
		 *
		 * }
		 * }
		 */
		
		if ($this->request->post ['attachement_form'] == '1') {
			if (empty ( $this->request->post ['tasktype_form_id'] )) {
				$this->error ['warning'] = 'Warning: Select Form';
			}
		}
		
		if ($this->request->post ['iswaypoint'] == "1") {
			
			if (empty ( $this->request->post ['locations'] )) {
				$this->error ['warning'] = 'Please add way point';
			}
			
			if (! empty ( $this->request->post ['locations'] )) {
				foreach ( $this->request->post ['locations'] as $location ) {
					if ($location ['locations_address'] == '' && $location ['locations_address'] == null) {
						$this->error ['warning'] = 'Please enter way point';
					}
				}
			}
		}
		
		if ($this->request->post ['form_task_creation'] == "1") {
			if ($this->request->post ['attachement_form'] == '') {
				$this->error ['warning'] = 'Warning: Select Form';
			}
			
			if (empty ( $this->request->post ['tasktype_form_id'] )) {
				$this->error ['warning'] = 'Warning: Select Form';
			}
		}
		
		
		
		if ($this->request->post ['tasktype'] == "2") {
			if ($this->request->post ['medication_tags'] == '' && $this->request->post ['medication_tags'] == null) {
				$this->error ['warning'] = 'You must select a medication to administer.';
			}
			
			if ($this->request->post ['tags_medication_details_ids'] == '' && $this->request->post ['tags_medication_details_ids'] == null) {
				$this->error ['warning'] = 'You must select a medication to administer.';
			}
		}
		
		/*
		 * if ($this->request->post['recurrence'] == 'hourly') {
		 * if ($this->request->post['recurnce_hrly_recurnce'] == 'Daily') {
		 *
		 * $taskDate = $this->request->post['taskDate'];
		 * $date = str_replace('-', '/', $taskDate);
		 * $res = explode("/", $date);
		 * $task_date = $res[2]."-".$res[0]."-".$res[1];
		 *
		 * //var_dump($task_date);
		 *
		 * $end_recurrence_date = $this->request->post['end_recurrence_date'];
		 * $date1 = str_replace('-', '/', $end_recurrence_date);
		 * $res1 = explode("/", $date1);
		 * $end_date = $res1[2]."-".$res1[0]."-".$res1[1];
		 * //var_dump($end_date);
		 *
		 * if($task_date < $end_date){
		 * if(empty($this->request->post['weekly_interval'])){
		 * $this->error['warning'] = 'Warning: Select day';
		 * }
		 * }
		 * }
		 * }
		 */
		
		/*
		 * if($this->request->post['taskTime'] >
		 * $this->request->post['endtime']){
		 * $this->error['time_error'] = 'Please select correct time';
		 * }
		 */
		// var_dump($this->error);
		// die;
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function tasklist() {
		try {
			$this->load->model ( 'createtask/createtask' );
			$this->data ['listtask'] = $this->model_createtask_createtask->getTasklist ( $this->customer->getId () );
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form.php';
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Createtask List' 
			);
			$this->model_activity_activity->addActivity ( 'sitescreatetasklist', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function updateStriketask() {
		try {
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'createtask/createtask' );
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {
				
				if ($this->request->get ['task_id'] != Null && $this->request->get ['task_id'] != "") {
					
					$this->load->model ( 'facilities/facilities' );
					$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
					
					if ($resulsst ['is_master_facility'] == '1') {
						if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
							$facilities_id = $this->session->data ['search_facilities_id'];
							$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							$this->load->model ( 'setting/timezone' );
							$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
							$timezone_name = $timezone_info ['timezone_value'];
						} else {
							$facilities_id = $this->customer->getId ();
							$timezone_name = $this->customer->isTimezone ();
						}
					} else {
						$facilities_id = $this->customer->getId ();
						$timezone_name = $this->customer->isTimezone ();
					}
					
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->get ['task_id'] );
					
					if ($this->request->post ['current_locations_address'] != null && $this->request->post ['current_locations_address'] != "") {
						$current_locations_address = $this->request->post ['current_locations_address'];
					} else {
						if ($this->request->post ['current_lat'] != null && $this->request->post ['current_lat'] != "") {
							$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $this->request->post ['current_lat'] ) . ',' . trim ( $this->request->post ['current_log'] ) . '&sensor=false';
							$json = @file_get_contents ( $url );
							$ldata = json_decode ( $json );
							$status = $ldata->status;
							if ($status == "OK") {
								$current_locations_address = $ldata->results [0]->formatted_address;
							}
						}
					}
					
					$this->request->post ['current_locations_address'] = $current_locations_address;
					
					$notes_id = $this->model_createtask_createtask->insertDatadetails ( $result, $this->request->post, $facilities_id, $this->request->get ['requires_approval'] );
					
					if ($this->request->get ['requires_approval'] == 'decline') {
						$this->model_createtask_createtask->deleteApprovaltasklist ( $this->request->get ['task_id'] );
					}
					
					// die;
					
					if ($this->request->post ['perpetual_checkbox'] == '1') {
						
						$this->load->model ( 'notes/notes' );
						
						$timeZone = date_default_timezone_set ( $timezone_name );
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = ( string ) $noteDate;
						
						$data = array ();
						
						$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
						$data ['imgOutput'] = $this->request->post ['imgOutput'];
						
						$data ['notes_pin'] = $this->request->post ['notes_pin'];
						$data ['user_id'] = $this->request->post ['user_id'];
						
						$data ['notetime'] = $notetime;
						$data ['note_date'] = $date_added;
						
						/*
						 * if($this->request->post['comments'] != null &&
						 * $this->request->post['comments']){
						 * $comments = ' | '.$this->request->post['comments'];
						 * }
						 */
						
						$this->load->model ( 'createtask/createtask' );
						
						$this->load->model ( 'setting/keywords' );
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'] ,$result['facilityId']);
						
						$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
						$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
						
						$data ['keyword_file'] = $keywordData13 ['keyword_image'];
						
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
						
						$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
						
						$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
						
						$data ['date_added'] = $date_added;
						$data ['linked_id'] = $result ['linked_id'];
						
						$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
						
						$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
						
						if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
							$this->load->model ( 'notes/notes' );
							
							date_default_timezone_set ( $timezone_name );
							$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							$this->load->model ( 'notes/tags' );
							$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
							
							$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date );
						}
					}
					
					if ($result ['medication_tags']) {
						$this->load->model ( 'setting/tags' );
						
						$medication_tags1 = explode ( ',', $result ['medication_tags'] );
						
						date_default_timezone_set ( $timezone_name );
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						foreach ( $medication_tags1 as $medicationtag ) {
							$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
							
							if ($tags_info1 ['emp_first_name']) {
								$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
							} else {
								$emp_tag_id = $tags_info1 ['emp_tag_id'];
							}
							
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
									$tdata1 ['facilities_id'] = $facilities_id;
									
									$medication_info = $this->model_createtask_createtask->gettaskmedicationdetail ( $result ['id'], $tasklocation ['tags_medication_details_id'] );
									
									$tdata1 ['complete_status'] = $medication_info ['complete_status'];
									
									$this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
								}
								
								$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
								
								if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
									$this->load->model ( 'notes/notes' );
									
									$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tag_id'], $update_date );
								}
							}
						}
					}
					
					$this->load->model ( 'createtask/createtask' );
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
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
					
					$this->model_createtask_createtask->updatetaskStrike ( $this->request->get ['task_id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );
				}
				
				$this->session->data ['success'] = $this->language->get ( 'text_success' );
				
				$urln = "";
				
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$urln .= '&searchdate=' . $this->request->get ['searchdate'];
				}
				/*
				 * $config_admin_limit1 =
				 * $this->config->get('config_front_limit');
				 * if($config_admin_limit1 != null && $config_admin_limit1 !=
				 * ""){
				 * $config_admin_limit = $config_admin_limit1;
				 * }else{
				 * $config_admin_limit = "50";
				 * }
				 *
				 * $timezone_name = $this->customer->isTimezone();
				 * date_default_timezone_set($timezone_name);
				 *
				 * $data = array(
				 * 'searchdate' => date('m-d-Y'),
				 * 'searchdate_app' => '1',
				 * 'facilities_id' => $this->customer->getId(),
				 * );
				 *
				 * $this->load->model('notes/notes');
				 * $notes_total =
				 * $this->model_notes_notes->getTotalnotess($data);
				 * $pagenumber_all = ceil($notes_total/$config_admin_limit);
				 *
				 *
				 * if ($pagenumber_all != null && $pagenumber_all != "") {
				 * if($pagenumber_all > 1){
				 * $urln .= '&page=' . $pagenumber_all;
				 * }
				 * }
				 */
				
				if ($notes_id != null && $notes_id != "") {
					$urln .= '&notes_id=' . $notes_id;
				}
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$urln .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '' . $urln, 'SSL' ) );
			}
			
			$this->data ['createtask'] = 1;
			
			$this->load->model ( 'createtask/createtask' );
			$result = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
			
			$assign_to = $result ['assign_to'];
			
			$this->data ['recurrence_save_1'] = $result ['recurrence'];
			$this->data ['task_info'] = $result;
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			
			$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
			$tasktype_id = $tasktype_info ['task_id'];
			
			if ($tasktype_info ['enable_location'] == '1') {
				$this->data ['enable_location'] = '1';
			} else {
				$this->data ['enable_location'] = '2';
			}
			
			if ($result ['enable_requires_approval'] == '2') {
				$this->data ['enable_requires_approval'] = '1';
			} else {
				$this->data ['enable_requires_approval'] = '2';
			}
			
			$this->load->model ( 'notes/notes' );
			
			/*
			 * if($tasktype_info['customlistvalueids']){
			 *
			 * $d = array();
			 * $d['customlistvalueids'] = $tasktype_info['customlistvalueids'];
			 * $customlistvalues =
			 * $this->model_notes_notes->getcustomlistvalues($d);
			 *
			 * if($customlistvalues){
			 * foreach($customlistvalues as $customlistvalue){
			 * $this->data['customlistvalues'][] = array(
			 * 'customlistvalues_id' => $customlistvalue['customlistvalues_id'],
			 * 'customlistvalues_name' =>
			 * $customlistvalue['customlistvalues_name'],
			 * 'relation_keyword_id' => $customlistvalue['relation_keyword_id']
			 * );
			 * }
			 * }
			 *
			 * }
			 */
			
			if ($tasktype_info ['customlist_id']) {
				
				$d = array ();
				$d ['customlist_id'] = $tasktype_info ['customlist_id'];
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
				
				if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
					$this->data ['id_url'] .= '&task_id=' . $this->request->get ['task_id'];
				}
			}
			
			if ($assign_to) {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUserByUsername ( $assign_to );
				
				if ($user_info != null && $user_info != "") {
					
					$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task == '1') {
						$this->data ['recurrence_save'] = $result ['recurrence'];
					} else {
						$this->data ['recurrence_save'] = '1';
					}
				}
			}
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			
			$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
			$this->data ['button_save'] = $this->language->get ( 'button_save' );
			$this->data ['text_select'] = $this->language->get ( 'text_select' );
			
			$this->load->model ( 'user/user' );
			$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
			
			if (isset ( $this->error ['warning'] )) {
				$this->data ['error_warning'] = $this->error ['warning'];
			} else {
				$this->data ['error_warning'] = '';
			}
			
			if (isset ( $this->error ['select_one'] )) {
				$this->data ['error_select_one'] = $this->error ['select_one'];
			} else {
				$this->data ['error_select_one'] = '';
			}
			
			if (isset ( $this->session->data ['success'] )) {
				$this->data ['success'] = $this->session->data ['success'];
				
				unset ( $this->session->data ['success'] );
			} else {
				$this->data ['success'] = '';
			}
			
			if (isset ( $this->error ['notes_pin'] )) {
				$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
			} else {
				$this->data ['error_notes_pin'] = '';
			}
			
			if (isset ( $this->error ['highlighter_id'] )) {
				$this->data ['error_highlighter_id'] = $this->error ['highlighter_id'];
			} else {
				$this->data ['error_highlighter_id'] = '';
			}
			
			if (isset ( $this->error ['user_id'] )) {
				$this->data ['error_user_id'] = $this->error ['user_id'];
			} else {
				$this->data ['error_user_id'] = '';
			}
			
			if (isset ( $this->error ['perpetual_checkbox_notes_pin'] )) {
				$this->data ['error_perpetual_checkbox_notes_pin'] = $this->error ['perpetual_checkbox_notes_pin'];
			} else {
				$this->data ['error_perpetual_checkbox_notes_pin'] = '';
			}
			
			if (isset ( $this->error ['enable_requires_approval_value'] )) {
				$this->data ['error_enable_requires_approval_value'] = $this->error ['enable_requires_approval_value'];
			} else {
				$this->data ['error_enable_requires_approval_value'] = '';
			}
			
			if (isset ( $this->request->post ['is_pause'] )) {
				$this->data ['is_pause'] = $this->request->post ['is_pause'];
			} else {
				$this->data ['is_pause'] = '';
			}
			
			if (isset ( $this->request->post ['pause_date'] )) {
				$this->data ['pause_date'] = $this->request->post ['pause_date'];
			} else {
				$this->data ['pause_date'] = '';
			}
			
			if (isset ( $this->request->post ['pause_time'] )) {
				$this->data ['pause_time'] = $this->request->post ['pause_time'];
			} else {
				$this->data ['pause_time'] = '';
			}
			
			if (isset ( $this->request->post ['enable_requires_approval_c1'] )) {
				$this->data ['enable_requires_approval_c1'] = $this->request->post ['enable_requires_approval_c1'];
			} else {
				$this->data ['enable_requires_approval_c1'] = '';
			}
			
			if (isset ( $this->request->post ['enable_requires_approval_value'] )) {
				$this->data ['enable_requires_approval_value'] = $this->request->post ['enable_requires_approval_value'];
			} else {
				$this->data ['enable_requires_approval_value'] = '';
			}
			
			if (isset ( $this->request->post ['current_locations_address'] )) {
				$this->data ['current_locations_address'] = $this->request->post ['current_locations_address'];
			} else {
				$this->data ['current_locations_address'] = '';
			}
			
			if (isset ( $this->request->post ['current_lat'] )) {
				$this->data ['current_lat'] = $this->request->post ['current_lat'];
			} else {
				$this->data ['current_lat'] = '';
			}
			if (isset ( $this->request->post ['current_log'] )) {
				$this->data ['current_log'] = $this->request->post ['current_log'];
			} else {
				$this->data ['current_log'] = '';
			}
			
			if (isset ( $this->request->post ['perpetual_checkbox'] )) {
				$this->data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
			} else {
				$this->data ['perpetual_checkbox'] = '';
			}
			if (isset ( $this->request->post ['perpetual_checkbox_notes_pin'] )) {
				$this->data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
			} else {
				$this->data ['perpetual_checkbox_notes_pin'] = '';
			}
			if (isset ( $this->request->post ['customlistvalues_id'] )) {
				$this->data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
			} else {
				$this->data ['customlistvalues_id'] = '';
			}
			
			if (isset ( $this->request->post ['select_one'] )) {
				$this->data ['select_one'] = $this->request->post ['select_one'];
			} else {
				if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
					$config_default_sign = '1'; // $this->config->get('config_default_sign');
				} else {
					$config_default_sign = '2';
				}
				$this->data ['select_one'] = $config_default_sign;
			}
			if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
				$this->data ['default_sign'] = '1'; // $this->config->get('config_default_sign');
			} else {
				$this->data ['default_sign'] = '2';
			}
			
			if (isset ( $this->request->post ['notes_pin'] )) {
				$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
			} elseif (! empty ( $notes_info )) {
				$this->data ['notes_pin'] = $notes_info ['notes_pin'];
			} else {
				$this->data ['notes_pin'] = '';
			}
			
			$notes_info = $this->model_createtask_createtask->getnotesInfo ( $this->request->get ['task_id'] );
			
			$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
			
			if (isset ( $this->request->post ['user_id'] )) {
				$this->data ['user_id'] = $this->request->post ['user_id'];
			} elseif (! empty ( $this->session->data ['username_confirm'] )) {
				$this->data ['user_id'] = $this->session->data ['username_confirm'];
			} elseif (! empty ( $notes_info )) {
				$this->data ['user_id'] = $notes_info ['assign_to'];
			} else {
				$this->data ['user_id'] = '';
			}
			
			if (isset ( $this->request->post ['customlistvalues_ids'] )) {
				$customlistvalues_ids1 = $this->request->post ['customlistvalues_ids'];
			} else {
				$customlistvalues_ids1 = array ();
			}
			
			$this->data ['customlistvalues_ids'] = array ();
			$this->load->model ( 'notes/notes' );
			
			foreach ( $customlistvalues_ids1 as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				if ($custom_info) {
					$this->data ['customlistvalues_ids'] [] = array (
							'user_id' => $customlistvalues_id,
							'customlistvalues_name' => $custom_info ['customlistvalues_name'] 
					);
				}
			}
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['requires_approval'] != null && $this->request->get ['requires_approval'] != "") {
				$url2 .= '&requires_approval=' . $this->request->get ['requires_approval'];
			}
			
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/updateStriketask', '' . $url2, 'SSL' ) );
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
			
			$this->children = array (
					'common/headerpopup' 
			);
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Update Strike Task ' 
			);
			$this->model_activity_activity->addActivity ( 'sitesupdatestriketask', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function addtasknote() {
		try {
			$this->load->model ( 'createtask/createtask' );
			$task_id = $this->request->get ['task_id'];
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$json = array ();
			
			$taskDetail = $this->model_createtask_createtask->getStrikedatadetails ( $task_id );
			
			$json ['description'] = $taskDetail ['description'];
			
			$timezone_name = $this->customer->isTimezone ();
			
			date_default_timezone_set ( $timezone_name );
			
			$json ['time'] = date ( 'h:i A' );
			
			$json ['taskadded'] = '2';
			$json ['task_id'] = $task_id;
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Add Task Notes ' 
			);
			$this->model_activity_activity->addActivity ( 'siteaddtasknotes', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	protected function validateForm2() 

	{
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->get ['task_id'] != '') {
			// var_dump($this->request->get['task_id']);
			$this->load->model ( 'createtask/createtask' );
			$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->get ['task_id'] );
			$task_date = date ( 'm-d-Y', strtotime ( $result ['task_date'] ) );
			
			/*
			 * if ($result['medication_tags']) {
			 *
			 * $this->load->model('setting/tags');
			 *
			 * $medication_tags1 = explode(',', $result['medication_tags']);
			 *
			 * date_default_timezone_set($timezone_name);
			 * $date_added = date('Y-m-d H:i:s', strtotime('now'));
			 *
			 * foreach ($medication_tags1 as $medicationtag) {
			 * $tags_info1 = $this->model_setting_tags->getTag($medicationtag);
			 *
			 * if ($tags_info1) {
			 *
			 * $drugs = array();
			 *
			 * $mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID($result['id'], $medicationtag);
			 *
			 * foreach ($mdrugs as $tasklocation) {
			 *
			 * $mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID($tasklocation['tags_medication_details_id']);
			 *
			 * $pre_quantity=$mdrug_info['drug_mg'];
			 * $dosage =$mdrug_info['drug_alertnate'];
			 * $final=$pre_quantity-$dosage;
			 * $drug_quantity=$final;
			 *
			 * if ($dosage>$pre_quantity) {
			 * $this->error['warning'] = 'Inventory is showing that the required QTY has been exhausted.Please update the Inventory.';
			 * }
			 *
			 * }
			 *
			 * }
			 * }
			 * }
			 */
			
			$timezone_name = $this->customer->isTimezone ();
			
			date_default_timezone_set ( $timezone_name );
			
			$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
			
			if ($task_date > $current_date) {
				$this->error ['warning'] = "Task cannot be completed before designated time";
			}
			
			if (empty ( $result )) {
				$this->error ['warning'] = "This task has been already completed. Please cancel and refresh the notes to review the task.";
			}
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
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
				$this->error ['user_id'] = $this->language->get ( 'error_customer' );
			}
		}
		
		if ($this->request->post ['select_one'] == '') {
			$this->error ['select_one'] = $this->language->get ( 'error_required' );
		}
		
		/*
		 * if ((utf8_strlen($this->request->post['notes_pin']) < 3) ||
		 * (utf8_strlen($this->request->post['notes_pin']) > 20)) {
		 * $this->error['notes_pin'] = $this->language->get('error_required');
		 * }
		 */
		
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
		
		if ($this->request->post ['perpetual_checkbox'] == '1') {
			if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
				$this->error ['perpetual_checkbox_notes_pin'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
				
				$this->load->model ( 'user/user_group' );
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
				
				$perpetual_task = $user_role_info ['perpetual_task'];
				
				if ($perpetual_task != '1') {
					$this->error ['warning'] = "You are not authorized to end the task!";
				}
			}
		}
		
		if ($this->request->post ['enable_requires_approval_c11'] == '1') {
			
			if ($this->request->post ['enable_requires_approval_value'] == '') {
				$this->error ['enable_requires_approval_value'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
				
				$this->load->model ( 'user/user_group' );
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
				
				$enable_requires_approval = $user_role_info ['enable_requires_approval'];
				
				if ($enable_requires_approval != '1') {
					$this->error ['warning'] = "You are not authorized to Complete the task!";
				}
			}
		}
		
		if (($this->request->post ['perpetual_checkbox'] == "3")) {
			if (($this->request->post ['facilitydrop'] == null && $this->request->post ['facilitydrop'] == "")) {
				$this->error ['facilitydrop'] = 'Please select facility!';
			}
		}
		
		if (($this->request->post ['perpetual_checkbox'] == "4")) {
			if (($this->request->post ['acttion_interval_id'] == null && $this->request->post ['acttion_interval_id'] == "")) {
				$this->error ['warning'] = 'Please select Interval!';
			}
		}
		/*
		 * if(($this->request->post['notes_pin'] == null &&
		 * $this->request->post['notes_pin'] == "") &&
		 * ($this->request->post['imgOutput'] == null &&
		 * $this->request->post['imgOutput'] == "")){
		 * $this->error['warning'] = 'Please insert at least one required!';
		 *
		 * }
		 */
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function checkTask() {
		try {
			$json = array ();
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			if ($this->request->post ['recurrence2'] == "hourly") {
				
				$taskDate = $this->request->post ['taskDate'];
				$date = str_replace ( '-', '/', $taskDate );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$taskTime = date ( 'H:i:s', strtotime ( $this->request->post ['taskTime'] ) );
				$endtime = date ( 'H:i:s', strtotime ( $this->request->post ['endtime'] ) );
				
				$time1 = strtotime ( $taskTime );
				$time2 = strtotime ( $endtime );
				$difference = round ( abs ( $time2 - $time1 ) / 3600, 2 );
				// echo $difference;
				// echo "<hr>";
				
				$this->load->model ( 'facilities/facilities' );
				$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				if ($resulsst ['is_master_facility'] == '1') {
					if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
						$facilities_id = $this->session->data ['search_facilities_id'];
						$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						$this->load->model ( 'setting/timezone' );
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
						$timezone_name = $timezone_info ['timezone_value'];
					} else {
						$facilities_id = $this->customer->getId ();
						$timezone_name = $this->customer->isTimezone ();
					}
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
				
				date_default_timezone_set ( $timezone_name );
				
				$current_time = date ( "H:i:s" );
				
				if ($current_time > $endtime) {
					$total_hour = 24 - $difference;
					$recData = $total_hour * 60;
					$taskinterval = round ( $recData / $this->request->post ['recurnce_hrly'] ) + 1;
				} else {
					
					$interval = abs ( strtotime ( $endtime ) - strtotime ( $taskTime ) );
					$recData = round ( $interval / 60 );
					$taskinterval = round ( $recData / $this->request->post ['recurnce_hrly'] ) + 1;
				}
				
				$json ['success'] = $taskinterval;
			} else {
				$json ['error'] = "1";
			}
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Checktask' 
			);
			$this->model_activity_activity->addActivity ( 'siteschecktask', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	
	public function inserttask() {       


		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'createtask/createtask' );
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {				
				
			if ($this->request->get ['task_id'] != Null && $this->request->get ['task_id'] != "") {
					
					$this->load->model ( 'facilities/facilities' );
					$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
					
					if ($resulsst ['is_master_facility'] == '1') {
						if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
							$facilities_id = $this->session->data ['search_facilities_id'];
							$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							$this->load->model ( 'setting/timezone' );
							$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
							$timezone_name = $timezone_info ['timezone_value'];
						} else {
							$facilities_id = $this->customer->getId ();
							$timezone_name = $this->customer->isTimezone ();
						}
					} else {
						$facilities_id = $this->customer->getId ();
						$timezone_name = $this->customer->isTimezone ();
					}
					
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->get ['task_id'] ); 



					
					if ($this->request->post ['current_locations_address'] != null && $this->request->post ['current_locations_address'] != "") {
						$current_locations_address = $this->request->post ['current_locations_address'];
					} else {
						
						if ($this->request->post ['current_lat'] != null && $this->request->post ['current_lat'] != "") {
							$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $this->request->post ['current_lat'] ) . ',' . trim ( $this->request->post ['current_log'] ) . '&sensor=false';
							$json = @file_get_contents ( $url );
							$ldata = json_decode ( $json );
							$status = $ldata->status;
							if ($status == "OK") {
								$current_locations_address = $ldata->results [0]->formatted_address;
							}
						}
					}

					
					$this->request->post ['current_locations_address'] = $current_locations_address;

					if($result['bed_check_facilities_ids']!=null || $result['bed_check_facilities_ids']!=""){

					$array_facility = explode(',',$result['bed_check_facilities_ids']);	


					foreach($array_facility as $facilities_id){	


					$notes_id = $this->model_createtask_createtask->inserttask ( $result, $this->request->post, $facilities_id, $this->request->get ['requires_approval'] );

					if ($this->request->post ['perpetual_checkbox'] == '1') {
						
						$this->load->model ( 'notes/notes' );
						
						$timeZone = date_default_timezone_set ( $timezone_name );
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = ( string ) $noteDate;
						
						$data = array ();
						
						$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
						$data ['imgOutput'] = $this->request->post ['imgOutput'];
						
						$data ['notes_pin'] = $this->request->post ['notes_pin'];
						$data ['user_id'] = $this->request->post ['user_id'];
						
						$data ['notetime'] = $notetime;
						$data ['note_date'] = $date_added;
						
						/*
						 * if($this->request->post['comments'] != null &&
						 * $this->request->post['comments']){
						 * $comments = ' | '.$this->request->post['comments'];
						 * }
						 */
						
						$this->load->model ( 'createtask/createtask' );
						
						$this->load->model ( 'setting/keywords' );
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
						
						$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
						$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
						
						$data ['keyword_file'] = $keywordData13 ['keyword_image'];
						
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
						
						$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
						
						$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
						
						$data ['date_added'] = $date_added;
						$data ['linked_id'] = $result ['linked_id'];						
							

						
						
						$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
						
						$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
						
						if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
							$this->load->model ( 'notes/notes' );
							
							date_default_timezone_set ( $timezone_name );
							$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							$this->load->model ( 'notes/tags' );
							$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
							
							$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date );
						}
					}

					if ($result ['medication_tags']) {
						$this->load->model ( 'setting/tags' );
						
						$medication_tags1 = explode ( ',', $result ['medication_tags'] );
						
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
									$tdata1 ['facilities_id'] = $facilities_id;
									
									$medication_info = $this->model_createtask_createtask->gettaskmedicationdetail ( $result ['id'], $tasklocation ['tags_medication_details_id'] );
									
									$tdata1 ['complete_status'] = $medication_info ['complete_status'];
									
									$pre_quantity = $mdrug_info ['drug_mg'];
									$dosage = $mdrug_info ['drug_alertnate'];
									$final = $pre_quantity - $dosage;
									$drug_quantity = $final;
									
									$this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
									
									$this->load->model ( 'setting/tags' );
									$this->model_setting_tags->updateQuantityMedication ( $tasklocation ['tags_medication_details_id'], $drug_quantity );
								}
								
								$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
								
								if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
									$this->load->model ( 'notes/notes' );
									
									$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date );
								}
							}
						}
					}

					$this->load->model ( 'createtask/createtask' );
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
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
					
					$this->model_createtask_createtask->updatetaskNote ( $this->request->get ['task_id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );

				}
						

					

					}else{

						$notes_id = $this->model_createtask_createtask->inserttask ( $result, $this->request->post, $facilities_id, $this->request->get ['requires_approval'] );


						if ($this->request->post ['perpetual_checkbox'] == '1') {
						
						$this->load->model ( 'notes/notes' );
						
						$timeZone = date_default_timezone_set ( $timezone_name );
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = ( string ) $noteDate;
						
						$data = array ();
						
						$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
						$data ['imgOutput'] = $this->request->post ['imgOutput'];
						
						$data ['notes_pin'] = $this->request->post ['notes_pin'];
						$data ['user_id'] = $this->request->post ['user_id'];
						
						$data ['notetime'] = $notetime;
						$data ['note_date'] = $date_added;
						
						/*
						 * if($this->request->post['comments'] != null &&
						 * $this->request->post['comments']){
						 * $comments = ' | '.$this->request->post['comments'];
						 * }
						 */
						
						$this->load->model ( 'createtask/createtask' );
						
						$this->load->model ( 'setting/keywords' );
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
						
						$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
						$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
						
						$data ['keyword_file'] = $keywordData13 ['keyword_image'];
						
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
						
						$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
						
						$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
						
						$data ['date_added'] = $date_added;
						$data ['linked_id'] = $result ['linked_id'];						
							

						
						
						$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
						
						$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
						
						if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
							$this->load->model ( 'notes/notes' );
							
							date_default_timezone_set ( $timezone_name );
							$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							$this->load->model ( 'notes/tags' );
							$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
							
							$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date );
						}
					}

					if ($result ['medication_tags']) {
						$this->load->model ( 'setting/tags' );
						
						$medication_tags1 = explode ( ',', $result ['medication_tags'] );
						
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
									$tdata1 ['facilities_id'] = $facilities_id;
									
									$medication_info = $this->model_createtask_createtask->gettaskmedicationdetail ( $result ['id'], $tasklocation ['tags_medication_details_id'] );
									
									$tdata1 ['complete_status'] = $medication_info ['complete_status'];
									
									$pre_quantity = $mdrug_info ['drug_mg'];
									$dosage = $mdrug_info ['drug_alertnate'];
									$final = $pre_quantity - $dosage;
									$drug_quantity = $final;
									
									$this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
									
									$this->load->model ( 'setting/tags' );
									$this->model_setting_tags->updateQuantityMedication ( $tasklocation ['tags_medication_details_id'], $drug_quantity );
								}
								
								$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
								
								if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
									$this->load->model ( 'notes/notes' );
									
									$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date );
								}
							}
						}
					}

					$this->load->model ( 'createtask/createtask' );
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
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
					
					$this->model_createtask_createtask->updatetaskNote ( $this->request->get ['task_id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );



					}				


					
					
			}

				
				$this->session->data ['success'] = $this->language->get ( 'text_success' );
				
				$url2 = "";
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
				}
				
				
				if ($notes_id != null && $notes_id != "") {
					$url2 .= '&notes_id=' . $notes_id;
				}
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) ) );
			}
			
			$this->data ['createtask'] = 1;
			
			$result = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
					$this->data ['enable_facility'] = '1';
					$this->load->model ( 'facilities/facilities' );
					
					$s = array ();
					$s ['facilities_id'] = $this->customer->getId ();
					$this->data ['facilities'] = $this->model_facilities_facilities->getfacilitiess ( $s );
				}
			}
			
			$assign_to = $result ['assign_to'];
			
			$this->data ['recurrence_save_1'] = $result ['recurrence'];
			$this->data ['task_info'] = $result;
			
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			
			$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
			$tasktype_id = $tasktype_info ['task_id'];
			
			if ($tasktype_info ['enable_location'] == '1') {
				$this->data ['enable_location'] = '1';
			} else {
				$this->data ['enable_location'] = '2';
			}
			
			if ($result ['enable_requires_approval'] == '2') {
				$this->data ['enable_requires_approval'] = '1';
			} else {
				$this->data ['enable_requires_approval'] = '2';
			}
			
			$this->load->model ( 'notes/notes' );
			
			
			
			if ($tasktype_info ['customlist_id']) {
				
				$d = array ();
				$d ['customlist_id'] = $tasktype_info ['customlist_id'];
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
				
				if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
					$this->data ['id_url'] .= '&task_id=' . $this->request->get ['task_id'];
				}
			}
			
			if ($assign_to) {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUserByUsername ( $assign_to );
				
				if ($user_info != null && $user_info != "") {
					
					$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task == '1') {
						$this->data ['recurrence_save'] = $result ['recurrence'];
					} else {
						$this->data ['recurrence_save'] = '1';
					}
				}
			}
			
			$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
			$this->data ['button_save'] = $this->language->get ( 'button_save' );
			$this->data ['text_select'] = $this->language->get ( 'text_select' );
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			
			$this->load->model ( 'user/user' );
			$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
			
			if (isset ( $this->error ['warning'] )) {
				$this->data ['error_warning'] = $this->error ['warning'];
			} else {
				$this->data ['error_warning'] = '';
			}
			
			if (isset ( $this->error ['select_one'] )) {
				$this->data ['error_select_one'] = $this->error ['select_one'];
			} else {
				$this->data ['error_select_one'] = '';
			}
			
			if (isset ( $this->session->data ['success'] )) {
				$this->data ['success'] = $this->session->data ['success'];
				
				unset ( $this->session->data ['success'] );
			} else {
				$this->data ['success'] = '';
			}
			
			if (isset ( $this->error ['notes_pin'] )) {
				$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
			} else {
				$this->data ['error_notes_pin'] = '';
			}
			
			if (isset ( $this->error ['highlighter_id'] )) {
				$this->data ['error_highlighter_id'] = $this->error ['highlighter_id'];
			} else {
				$this->data ['error_highlighter_id'] = '';
			}
			
			if (isset ( $this->error ['user_id'] )) {
				$this->data ['error_user_id'] = $this->error ['user_id'];
			} else {
				$this->data ['error_user_id'] = '';
			}
			
			if (isset ( $this->error ['facilitydrop'] )) {
				$this->data ['error_facilitydrop'] = $this->error ['facilitydrop'];
			} else {
				$this->data ['error_facilitydrop'] = '';
			}
			
			if (isset ( $this->error ['perpetual_checkbox_notes_pin'] )) {
				$this->data ['error_perpetual_checkbox_notes_pin'] = $this->error ['perpetual_checkbox_notes_pin'];
			} else {
				$this->data ['error_perpetual_checkbox_notes_pin'] = '';
			}
			if (isset ( $this->error ['enable_requires_approval_value'] )) {
				$this->data ['error_enable_requires_approval_value'] = $this->error ['enable_requires_approval_value'];
			} else {
				$this->data ['error_enable_requires_approval_value'] = '';
			}
			
			if (isset ( $this->request->post ['reassign_id'] )) {
				$this->data ['reassign_id'] = $this->request->post ['reassign_id'];
			} else {
				$this->data ['reassign_id'] = '';
			}
			if (isset ( $this->request->post ['reassign_uname'] )) {
				$this->data ['reassign_uname'] = $this->request->post ['reassign_uname'];
			} else {
				$this->data ['reassign_uname'] = '';
			}
			
			if (isset ( $this->request->post ['is_move'] )) {
				$this->data ['is_move'] = $this->request->post ['is_move'];
			} else {
				$this->data ['is_move'] = '';
			}
			if (isset ( $this->request->post ['facilitydrop'] )) {
				$this->data ['facilitydrop'] = $this->request->post ['facilitydrop'];
			} else {
				$this->data ['facilitydrop'] = '';
			}
			if (isset ( $this->request->post ['is_pause'] )) {
				$this->data ['is_pause'] = $this->request->post ['is_pause'];
			} else {
				$this->data ['is_pause'] = '';
			}
			
			if (isset ( $this->request->post ['pause_date'] )) {
				$this->data ['pause_date'] = $this->request->post ['pause_date'];
			} else {
				$this->data ['pause_date'] = '';
			}
			
			if (isset ( $this->request->post ['pause_time'] )) {
				$this->data ['pause_time'] = $this->request->post ['pause_time'];
			} else {
				$this->data ['pause_time'] = '';
			}
			
			if (isset ( $this->request->post ['enable_requires_approval_c1'] )) {
				$this->data ['enable_requires_approval_c1'] = $this->request->post ['enable_requires_approval_c1'];
			} else {
				$this->data ['enable_requires_approval_c1'] = '';
			}
			
			if (isset ( $this->request->post ['enable_requires_approval_value'] )) {
				$this->data ['enable_requires_approval_value'] = $this->request->post ['enable_requires_approval_value'];
			} else {
				$this->data ['enable_requires_approval_value'] = '';
			}
			
			if (isset ( $this->request->post ['perpetual_checkbox'] )) {
				$this->data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
			} else {
				$this->data ['perpetual_checkbox'] = '';
			}
			
			if (isset ( $this->request->post ['current_locations_address'] )) {
				$this->data ['current_locations_address'] = $this->request->post ['current_locations_address'];
			} else {
				$this->data ['current_locations_address'] = '';
			}
			
			if (isset ( $this->request->post ['current_lat'] )) {
				$this->data ['current_lat'] = $this->request->post ['current_lat'];
			} else {
				$this->data ['current_lat'] = '';
			}
			if (isset ( $this->request->post ['current_log'] )) {
				$this->data ['current_log'] = $this->request->post ['current_log'];
			} else {
				$this->data ['current_log'] = '';
			}
			
			if (isset ( $this->request->post ['perpetual_checkbox_notes_pin'] )) {
				$this->data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
			} else {
				$this->data ['perpetual_checkbox_notes_pin'] = '';
			}
			
			if (isset ( $this->request->post ['customlistvalues_id'] )) {
				$this->data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
			} else {
				$this->data ['customlistvalues_id'] = '';
			}
			
			if (isset ( $this->request->post ['select_one'] )) {
				$this->data ['select_one'] = $this->request->post ['select_one'];
			} else {
				if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
					$config_default_sign = '1'; // $this->config->get('config_default_sign');
				} else {
					$config_default_sign = '2';
				}
				$this->data ['select_one'] = $config_default_sign;
			}
			if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
				$this->data ['default_sign'] = '1'; // $this->config->get('config_default_sign');
			} else {
				$this->data ['default_sign'] = '2';
			}
			
			if (isset ( $this->request->post ['notes_pin'] )) {
				$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
			} elseif (! empty ( $notes_info )) {
				$this->data ['notes_pin'] = $notes_info ['notes_pin'];
			} else {
				$this->data ['notes_pin'] = '';
			}
			
			$notes_info = $this->model_createtask_createtask->getnotesInfo ( $this->request->get ['task_id'] );
			$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
			
			if (isset ( $this->request->post ['user_id'] )) {
				$this->data ['user_id'] = $this->request->post ['user_id'];
			} elseif (! empty ( $this->session->data ['username_confirm'] )) {
				$this->data ['user_id'] = $this->session->data ['username_confirm'];
			} elseif (! empty ( $notes_info )) {
				$this->data ['user_id'] = $notes_info ['assign_to'];
			} else {
				$this->data ['user_id'] = '';
			}
			
			if (isset ( $this->request->post ['comments'] )) {
				$this->data ['comments'] = $this->request->post ['comments'];
			} else {
				$this->data ['comments'] = '';
			}
			
			if (isset ( $this->request->post ['customlistvalues_ids'] )) {
				$customlistvalues_ids1 = $this->request->post ['customlistvalues_ids'];
			} else {
				$customlistvalues_ids1 = array ();
			}
			
			$this->data ['customlistvalues_ids'] = array ();
			$this->load->model ( 'notes/notes' );
			
			foreach ( $customlistvalues_ids1 as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				if ($custom_info) {
					$this->data ['customlistvalues_ids'] [] = array (
							'user_id' => $customlistvalues_id,
							'customlistvalues_name' => $custom_info ['customlistvalues_name'] 
					);
				}
			}
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
			}
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['requires_approval'] != null && $this->request->get ['requires_approval'] != "") {
				$url2 .= '&requires_approval=' . $this->request->get ['requires_approval'];
			}
			
			$this->data ['completetask'] = '1';
			
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/inserttask', '' . $url2, 'SSL' ) );
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
			
			$this->children = array (
					'common/headerpopup' 
			);
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Inserttask' 
			);
			$this->model_activity_activity->addActivity ( 'sitesinserttask', $activity_data2 );
		}
	}
	
	
		
	public function updateInCompleteTask() {
		try {
			$json = array ();
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			if ($this->request->get ['update_task'] == "1") {
				
				/*
				 * if($config_task_deleted_time == '6min'){
				 * $deleteTime = '6';
				 * }else
				 * if($config_task_deleted_time == '10min'){
				 * $deleteTime = '10';
				 * }
				 * else
				 * if($config_task_deleted_time == '15min'){
				 * $deleteTime = '15';
				 * }else
				 * if($config_task_deleted_time == '20min'){
				 * $deleteTime = '20';
				 * }else
				 * if($config_task_deleted_time == '25min'){
				 * $deleteTime = '25';
				 * }else
				 * if($config_task_deleted_time == '30min'){
				 * $deleteTime = '30';
				 * }else
				 * if($config_task_deleted_time == '45min'){
				 * $deleteTime = '45';
				 * }
				 */
				
				// var_dump($deleteTime);
				
				// date_default_timezone_set($this->session->data['time_zone_1']);
				
				$this->load->model ( 'facilities/facilities' );
				$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				if ($resulsst ['is_master_facility'] == '1') {
					if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
						$facilities_id = $this->session->data ['search_facilities_id'];
						$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						$this->load->model ( 'setting/timezone' );
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
						$timezone_name = $timezone_info ['timezone_value'];
					} else {
						$facilities_id = $this->customer->getId ();
						$timezone_name = $this->customer->isTimezone ();
					}
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
				
				$timeZone = date_default_timezone_set ( $timezone_name );
				
				$this->load->model ( 'createtask/createtask' );
				$currentdate = date ( 'd-m-Y' );
				
				// var_dump($currentdate);
				$complteteTaskLists = $this->model_createtask_createtask->gettaskLists ( $currentdate, $facilities_id );
				
				// var_dump($complteteTaskLists);
				
				if ($complteteTaskLists != null && $complteteTaskLists != "") {
					
					foreach ( $complteteTaskLists as $complteteTaskList ) {
						$result = array ();
						/*
						 * echo $deleteTime;
						 * echo "<hr>";
						 */
						
						// echo date('H:i:s',
						// strtotime($complteteTaskList['task_time']));
						// echo "<hr>";
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $complteteTaskList ['tasktype'],$complteteTaskList['facilityId'] );
						
						if ($tasktype_info ['auto_extend'] == '0') {
							
							// var_dump($tasktype_info['custom_completion_rule']);
							if ($tasktype_info ['custom_completion_rule'] == '1') {
								$config_task_after_complete = $tasktype_info ['config_task_after_complete'];
								$config_task_deleted_time = $tasktype_info ['config_task_deleted_time'];
								$deleteTime = $config_task_deleted_time;
							} else {
								$config_task_after_complete = $this->config->get ( 'config_task_after_complete' );
								$config_task_deleted_time = $this->config->get ( 'config_task_deleted_time' );
								$deleteTime = $config_task_deleted_time;
							}
							
							// var_dump($deleteTime);
							
							$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $deleteTime . ' minutes', strtotime ( $complteteTaskList ['task_time'] ) ) );
							
							// var_dump($taskstarttime);
							// echo "<hr>";
							
							$currenttime = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							/*
							 * echo "TASK TIME ". $taskstarttime . " ===========
							 * CURRENT TIME ".$currenttime;
							 * echo "<hr>";
							 */
							$result ['facilityId'] = $complteteTaskList ['facilityId'];
							$result ['description'] = $complteteTaskList ['description'];
							$result ['date_added'] = $complteteTaskList ['date_added'];
							$result ['task_time'] = $complteteTaskList ['task_time'];
							$result ['id'] = $complteteTaskList ['id'];
							$result ['assign_to'] = $complteteTaskList ['assign_to'];
							
							$result ['tasktype'] = $complteteTaskList ['tasktype'];
							$result ['is_pause'] = $complteteTaskList ['is_pause'];
							$result ['parent_id'] = $complteteTaskList ['parent_id'];
							$result ['task_form_id'] = $complteteTaskList ['task_form_id'];
							$result ['task_action'] = $complteteTaskList ['task_action'];
							
							$result ['facilitytimezone'] = $timezone_name;
							
							// var_dump($result);
							
							if ($currenttime > $taskstarttime) {
								// var_dump($complteteTaskLists);
								// echo "TRUE ";
								
								if ($complteteTaskList ['enable_requires_approval'] == '2') {
									
									$declineTaskLists = $this->model_createtask_createtask->getdeclinetasksLists ( $complteteTaskList ['id'] );
									
									$approvaltaskdate = date ( 'Y-m-d H:i', strtotime ( $complteteTaskList ['date_added'] ) );
									$declinetaskdate = date ( 'Y-m-d H:i', strtotime ( $declineTaskLists ['date_added'] ) );
									
									if ($approvaltaskdate == $declinetaskdate) {
										
										$notes_id = $this->model_createtask_createtask->insertTaskLists ( $result, $this->customer->getId (), '0' );
										
										if ($complteteTaskList ['medication_tags']) {
											$this->load->model ( 'setting/tags' );
											
											$medication_tags1 = explode ( ',', $complteteTaskList ['medication_tags'] );
											
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
														$tdata1 ['facilities_id'] = $facilities_id;
														
														$medication_info = $this->model_createtask_createtask->gettaskmedicationdetail ( $result ['id'], $tasklocation ['tags_medication_details_id'] );
														
														$tdata1 ['complete_status'] = $medication_info ['complete_status'];
														
														$this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
													}
													
													$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
													
													if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
														$this->load->model ( 'notes/notes' );
														
														$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date );
													}
												}
											}
										}
										
										$this->load->model ( 'createtask/createtask' );
										$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
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
										
										$this->model_createtask_createtask->updateIncomtaskNote ( $complteteTaskList ['id'], $this->customer->getId () );
										
										$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );
									}
								} else {
									
									
									$notes_id = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_id, '0' );
									
									if ($complteteTaskList ['medication_tags']) {
										$this->load->model ( 'setting/tags' );
										
										$medication_tags1 = explode ( ',', $complteteTaskList ['medication_tags'] );
										
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
													$tdata1 ['facilities_id'] = $facilities_id;
													
													$medication_info = $this->model_createtask_createtask->gettaskmedicationdetail ( $result ['id'], $tasklocation ['tags_medication_details_id'] );
													
													$tdata1 ['complete_status'] = $medication_info ['complete_status'];
													
													$this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
												}
												
												$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
												
												if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
													$this->load->model ( 'notes/notes' );
													
													$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date );
												}
											}
										}
									}
									
									$this->load->model ( 'createtask/createtask' );
									$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
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
									
									$this->model_createtask_createtask->updateIncomtaskNote ( $complteteTaskList ['id'], $this->customer->getId () );
									
									$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );
								}
							}
						}
					}
				}
				
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$sqlbedinfo = "SELECT max(id) as id FROM `" . DB_PREFIX . "createtask` WHERE ";
				// $sqlbedinfo .= " `end_recurrence_date` BETWEEN '".$noteDate."
				// 00:00:00' AND '".$noteDate." 23:59:59' and facilityId =
				// '".$this->customer->getId()."' group by task_group_by ";
				$sqlbedinfo .= " `task_date` BETWEEN  '" . $noteDate . " 00:00:00' AND  '" . $noteDate . " 23:59:59' and facilityId = '" . $facilities_id . "' group by task_group_by ";
				$sqlbedinfo .= " ORDER BY `task_time` DESC ";
				
				$bed = $this->db->query ( $sqlbedinfo );
				
				if ($bed->num_rows > 0) {
					foreach ( $bed->rows as $row ) {
						
						$sqlt = "SELECT * from " . DB_PREFIX . "createtask WHERE id = '" . $row ['id'] . "' ";
						$qts = $this->db->query ( $sqlt );
						
						$sqltn = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes WHERE task_group_by = '" . $qts->row ['task_group_by'] . "' and end_task = '1' ";
						$qtsn = $this->db->query ( $sqltn );
						
						if ($qtsn->row ['total'] == '0') {
							//$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
							//$query = $this->db->query ( $sql4 );
						}
					}
				}
				
				/*
				 * $timezone_name = $this->customer->isTimezone();
				 * $timeZone = date_default_timezone_set($timezone_name);
				 * $this->load->model('createtask/createtask');
				 * $tasksql = "SELECT * from " . DB_PREFIX . "tasktype where status = 1 and generate_report = 1 and forms_id != 0";
				 * $alltasks = $this->db->query($tasksql);
				 *
				 * $date = date("Y-m-d");
				 * $start = $date . " 00:00:00";
				 * $end = $date . " 23:59:59";
				 *
				 * if($alltasks->num_rows > 0){
				 *
				 * foreach($alltasks->rows as $rtask){
				 * //var_dump($rtask['tasktype_name']);
				 *
				 * $notestask = "SELECT * from " . DB_PREFIX . "notes where tasktype = ".$rtask['task_id']." and generate_report = '0' and end_task = '1' and facilities_id = ".$this->customer->getId()." and date_added BETWEEN '".$start."' AND '".$end."' and user_id != '".SYSTEM_GENERATED."' ";
				 * $allnotes = $this->db->query($notestask);
				 *
				 * //var_dump($allnotes->num_rows);
				 *
				 * if($allnotes->num_rows > 0){
				 * foreach($allnotes->rows as $row){
				 * //var_dump($row['parent_id']);
				 * $data = array();
				 *
				 * $noteDate = date('Y-m-d H:i:s', strtotime('now'));
				 * $date_added = (string) $noteDate;
				 *
				 * $noteDate1 = date('m-d-Y', strtotime('now'));
				 * $notetime = date('H:i:s', strtotime('now'));
				 * $time1 = date('h:i A', strtotime('now'));
				 *
				 * $data['imgOutput'] = '';
				 *
				 * $data['notes_pin'] = SYSTEM_GENERATED_PIN;
				 * $data['user_id'] = SYSTEM_GENERATED;
				 *
				 * $data['notetime'] = $notetime;
				 * $data['note_date'] = $date_added;
				 * $data['facilitytimezone'] = $timezone_name;
				 *
				 *
				 * $data['date_added'] = $date_added;
				 *
				 * $sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '".$row['notes_id']."'";
				 * $q2 = $this->db->query($sql2);
				 *
				 * $tags_id = $q2->row['tags_id'];
				 *
				 * $this->load->model('setting/tags');
				 * $tags_info = $this->model_setting_tags->getTag($tags_id);
				 *
				 * $data['emp_tag_id'] = $tags_info['emp_tag_id'];
				 * $data['tags_id'] = $tags_info['tags_id'];
				 *
				 *
				 *
				 * $data['notes_description'] = ' REPORT Auto Generated | '.$rtask['tasktype_name'];
				 *
				 * //var_dump($data);
				 *
				 * $notes_id = $this->model_notes_notes->jsonaddnotes($data, $row['facilities_id']);
				 *
				 * $facilities_info = $this->model_facilities_facilities->getfacilities($row['facilities_id']);
				 *
				 *
				 * if($rtask['task_id'] == '11'){
				 * $slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '".$row['notes_id']."'";
				 * $this->db->query($slq1);
				 *
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '3', task_group_by = '".$row['task_group_by']."', parent_id = '".$row['parent_id']."', task_type = '".$row['task_type']."' where notes_id = '".$notes_id."'";
				 * $this->db->query($slq1);
				 * }else
				 * if($rtask['task_id'] == '25'){
				 *
				 * if($tnote['end_perpetual_task'] == '2'){
				 *
				 * $data2 = array();
				 * $data2['design_forms'][0][0]['date_93638826'] = $noteDate1;
				 * $data2['design_forms'][0][0]['time_33135211'] = $time1;
				 * $data2['design_forms'][0][0]['select_35510589'] = 'Yes';
				 * $data2['design_forms'][0][0]['select_93830432'] = 'Yes';
				 * $data2['design_forms'][0][0]['text_61453229'] = $tags_info['emp_first_name'] .' '.$tags_info['emp_last_name'];
				 * $data2['design_forms'][0][0]['text_61453229_1_tags_id'] = $tags_info['tags_id'];
				 *
				 * $data2['design_forms'][0][0]['date_82208178'] = date('m-d-Y',strtotime($tags_info['dob']));
				 *
				 * $data2['design_forms'][0][0]['text8'] = '';
				 * $data2['design_forms'][0][0]['text9'] = '';
				 *
				 * if($tags_info['select_82298274'] == '1'){
				 * $select10 = 'Male';
				 * }else{
				 * $select10 = 'Female';
				 * }
				 *
				 * $data2['design_forms'][0][0]['select_82298274'] = $select10;
				 * $data2['design_forms'][0][0]['text_64107947'] = $facilities_info['facility'];
				 * $data2['design_forms'][0][0]['text12'] = '';
				 *
				 * $data2['design_forms'][0][0]['0']['checkbox_45658071'] = 'Sucide Risk';
				 * $data2['design_forms'][0][0]['1']['checkbox_45658071'] = '';
				 * $data2['design_forms'][0][0]['2']['checkbox_45658071'] = '';
				 * $data2['design_forms'][0][0]['3']['checkbox_45658071'] = '';
				 * $data2['design_forms'][0][0]['4']['checkbox_45658071'] = '';
				 *
				 *
				 *
				 * $data2['design_forms'][0][0]['date_48860525'] = $noteDate1;
				 * $data2['design_forms'][0][0]['time_41789102'] = $time1;
				 *
				 *
				 * $data2['signature'][0][0]['signature14'] = '';
				 * $data2['signature'][0][0]['signature18'] = '';
				 *
				 * $data2['design_forms'][0][0]['date_31171166'] = $noteDate1;
				 * $data2['design_forms'][0][0]['time_88128841'] = $time1;
				 *
				 * $data2['design_forms'][0][0]['text21'] = $tags_info['emp_first_name'] .' '.$tags_info['emp_last_name'];
				 * $data2['design_forms'][0][0]['text22'] = '';//$tags_info['ssn'];
				 *
				 * $data23 = array();
				 * $data23['forms_design_id'] = '13';
				 * $data23['notes_id'] = $notes_id;
				 * $data23['tags_id'] = $tags_id;
				 * $data23['facilities_id'] = $row['facilities_id'];
				 *
				 *
				 * $this->load->model('form/form');
				 * $formreturn_id = $this->model_form_form->addFormdata($data2, $data23);
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "forms SET tags_id = '".$tags_id."',parent_id = '".$row['parent_id']."' where forms_id = '".$formreturn_id."'";
				 * $this->db->query($slq1);
				 *
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '".$row['notes_id']."'";
				 * $this->db->query($slq1);
				 *
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '".$notes_id."'";
				 * $this->db->query($slq1);
				 *
				 * }
				 * }else{
				 *
				 * $data23 = array();
				 * $data23['forms_design_id'] = $rtask['forms_id'];
				 * $data23['notes_id'] = $notes_id;
				 * $data23['facilities_id'] = $row['facilities_id'];
				 *
				 * //var_dump($data23);
				 * //echo "<hr>";
				 * $this->load->model('form/form');
				 * $formreturn_id = $this->model_form_form->addFormdata($data2, $data23);
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "forms SET tags_id = '".$tags_id."',parent_id = '".$row['parent_id']."' where forms_id = '".$formreturn_id."'";
				 * $this->db->query($slq1);
				 *
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '".$row['notes_id']."'";
				 * $this->db->query($slq1);
				 *
				 *
				 * $slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '".$notes_id."'";
				 * $this->db->query($slq1);
				 *
				 * }
				 * }
				 *
				 *
				 * }
				 * }
				 * }
				 */
				
				$json ['success'] = '2';
			} else {
				$json ['error'] = "1";
			}
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Updateincompletetask' 
			);
			$this->model_activity_activity->addActivity ( 'Updateincompletetask', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function getNotification() {
		try {
			
			$json = array ();
			
			$timezone_name = $this->customer->isTimezone ();
			
			$timeZone = date_default_timezone_set ( $timezone_name );
			
			$this->load->model ( 'createtask/createtask' );
			
			$data1 = array ();
			
			$currentdate = date ( 'd-m-Y' );
			$data1 ['currentdate'] = $currentdate;
			$data1 ['notification'] = '1';
			$data1 ['top'] = '1';
			$data1 ['facilities_id'] = $this->customer->getId ();
			
			$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists ( $data1 ); /*
			                                                                                              * total
			                                                                                              */
			
			$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists ( $data1 ); /*
			                                                                                    * task
			                                                                                    */
			
			if ($compltetecountTaskLists > 0) {
				foreach ( $complteteTaskLists as $list ) {
					$json ['listtask'] [] = array (
							'assign_to' => $list ['assign_to'],
							'tasktype' => $list ['tasktype'],
							'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
							'id' => $list ['id'],
							'description' => $list ['description'],
							'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ) 
					);
				}
				
				$json ['total'] = $compltetecountTaskLists;
			} else {
				$json ['total'] = '0';
			}
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Createtask getNotification' 
			);
			$this->model_activity_activity->addActivity ( 'getNotification', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function getnotificationPopup() {
		try {
			
			if ($this->request->get ['check_notification'] == "1") {
				$timezone_name = $this->customer->isTimezone ();
				
				$timeZone = date_default_timezone_set ( $timezone_name );
				
				$this->load->model ( 'createtask/createtask' );
				
				$data1 = array ();
				
				$currentdate = date ( 'd-m-Y' );
				$data1 ['currentdate'] = $currentdate;
				$data1 ['notification'] = '1';
				$data1 ['top'] = '1';
				$data1 ['facilities_id'] = $this->customer->getId ();
				
				$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists ( $data1 );
				
				if ($complteteTaskLists != null && $complteteTaskLists != "") {
					
					foreach ( $complteteTaskLists as $list ) {
						$this->data ['listtask'] [] = array (
								'assign_to' => $list ['assign_to'],
								'tasktype' => $list ['tasktype'],
								'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
								'id' => $list ['id'],
								'description' => $list ['description'],
								'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ) 
						);
					}
				}
			}
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Createtask getNotificationPopup' 
			);
			$this->model_activity_activity->addActivity ( 'getNotificationPopup', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function checkuseremail() {
		$json = array ();
		$assignto = $this->request->get ['assignto'];
		if ($assignto != NULL && $assignto != "") {
			
			$this->load->model ( 'user/user' );
			$userEmail = $this->model_user_user->getUser ( $assignto );
			
			if ($userEmail ['email'] != NULL && $userEmail ['email'] != "") {
				$json ['success'] = '1';
			} else {
				$json ['success'] = '2';
			}
		} else {
			$json ['success'] = '1';
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function checkusersms() {
		$json = array ();
		$assignto = $this->request->get ['assignto'];
		if ($assignto != NULL && $assignto != "") {
			
			$this->load->model ( 'user/user' );
			$usersms = $this->model_user_user->getUser ( $assignto );
			
			if ($usersms ['phone_number'] != NULL && $usersms ['phone_number'] != "0") {
				$json ['success'] = '1';
			} else {
				$json ['success'] = '2';
			}
		} else {
			$json ['success'] = '1';
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function saveclient() {
		try {
			$json = array ();
			
			$this->load->model ( 'createtask/createtask' );
			$client_info = $this->model_createtask_createtask->getClient ( $this->request->get ['client_name'] );
			
			if ($client_info) {
				$json ['client'] = array (
						'client_name' => $client_info ['client_name'],
						'client_id' => $client_info ['client_id'] 
				);
			} else {
				$client_id = $this->model_createtask_createtask->createClient ( $this->request->get ['client_name'] );
				$json ['client'] = array (
						'client_name' => $this->request->get ['client_name'],
						'client_id' => $client_id 
				);
			}
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Task create client' 
			);
			$this->model_activity_activity->addActivity ( 'createclient', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function headertasklist() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['route'] != "resident/cases/dashboard2" && $this->request->get ['route'] != "notes/notes/insert") {
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
				
				$this->load->model ( 'setting/tags' );
				
				if ($this->request->get ['tags_id'] != NULL && $this->request->get ['tags_id'] != "") {
					
					$taginfo = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
					
					$this->data ['tag_info'] = $taginfo ['emp_tag_id'] . ' : ' . $taginfo ['emp_first_name'] . ' ' . $taginfo ['emp_last_name'];
				}
				
				if (($this->request->post ['note_date_search'] == '1')) {
					$url = "";
					if ($this->request->post ['searchdate'] != null && $this->request->post ['searchdate'] != "") {
						$url .= '&searchdate=' . $this->request->post ['searchdate'];
					}
					$url .= '&tags_id=' . $this->request->get ['tags_id'];
					
					$this->redirect ( $this->url->link ( 'notes/createtask/headertasklist', '' . $url, 'SSL' ) );
				}
			}
		}
		
		$this->data ['notes_url'] = $this->url->link ( 'notes/notes/insert', '' . '&reset=1' . $url2, 'SSL' );
		$this->data ['notes_url_close'] = $this->url->link ( 'notes/notes/insert', '' . '&reset=1' . $url2, 'SSL' );
		$this->data ['support_url'] = $this->url->link ( 'notes/support', '' . $url2, 'SSL' );
		$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/search', '' . $url2, 'SSL' );
		
		$this->data ['taskrefress'] = $this->request->get ['taskrefress'];
		
		$this->data ['createtask_url'] = $this->url->link ( 'notes/createtask', '' . $url2, 'SSL' );
		
		$this->data ['addtasktask_url'] = $this->url->link ( 'notes/notes/index', '' . $url2, 'SSL' );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$this->data ['updatestriketask_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=1', '' . $url2, 'SSL' ) );
			$this->data ['inserttask_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=2', '' . $url2, 'SSL' ) );
		} else {
			
			$this->data ['updatestriketask_url'] = $this->url->link ( 'notes/createtask/updateStriketask', '' . $url2, 'SSL' );
			$this->data ['inserttask_url'] = $this->url->link ( 'notes/createtask/inserttask', '' . $url2, 'SSL' );
		}
		
		$this->data ['approval_url'] = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
		
		$this->data ['checklist_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/checklistform', '' . $url2, 'SSL' ) );
		$this->data ['incident_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/noteform/taskforminsert', '' . $url2, 'SSL' ) );
		
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/headertasklist', '' . $url2, 'SSL' ) );
		
		$this->data ['reviewnoted_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/reviewNotes', '' . $url2, 'SSL' ) );
		
		$this->data ['custom_form_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$this->data ['update_strike_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateStrike', '' . $url2, 'SSL' ) );
		$this->data ['update_strike_url_private'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateStrikeprivate', '' . $url2, 'SSL' ) );
		$this->data ['alarm_url'] = $this->url->link ( 'notes/notes/setAlarm', '', 'SSL' );
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($resulsst ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
		}
		
		date_default_timezone_set ( $timezone_name );
		
		if (isset ( $this->request->get ['searchdate'] )) {
			$res = explode ( "-", $this->request->get ['searchdate'] );
			$createdate1 = $res [1] . "-" . $res [0] . "-" . $res [2];
			
			$this->data ['note_date'] = date ( 'D F j, Y', strtotime ( $createdate1 ) );
			$currentdate = $createdate1;
			
			$this->data ['searchdate'] = $this->request->get ['searchdate'];
		} else {
			$this->data ['note_date'] = date ( 'D F j, Y' ); // date('m-d-Y');
			
			$currentdate = date ( 'd-m-Y' );
			$this->data ['searchdate'] = date ( 'm-d-Y' );
		}
		
		// $addTime = $this->config->get('config_task_complete');
		
		/*
		 * if($config_task_complete == '5min'){
		 * $addTime = '5';
		 * }else
		 * if($config_task_complete == '10min'){
		 * $addTime = '10';
		 * }
		 * else
		 * if($config_task_complete == '15min'){
		 * $addTime = '15';
		 * }else
		 * if($config_task_complete == '20min'){
		 * $addTime = '20';
		 * }else
		 * if($config_task_complete == '25min'){
		 * $addTime = '25';
		 * }else
		 * if($config_task_complete == '30min'){
		 * $addTime = '30';
		 * }else
		 * if($config_task_complete == '45min'){
		 * $addTime = '45';
		 * }else
		 * if($config_task_complete == '45min'){
		 * $addTime = '45';
		 * }
		 */
		
		$this->data ['deleteTime'] = $deleteTime;
		
		$this->load->model ( 'createtask/createtask' );
		$top = '1';
		$listtasks = $this->model_createtask_createtask->getTasklist ( $facilities_id, $currentdate, $top, $this->request->get ['tags_id'] );
		
		// $this->data['taskTotal'] =
		// $this->model_createtask_createtask->getCountTasklist($this->customer->getId(),
		// $currentdate, $top, '', $this->request->get['tags_id']);
		
		// var_dump($this->data['taskTotal']);
		
		// date_default_timezone_set($this->session->data['time_zone_1']);
		
		$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		$currentdate = date ( 'Y-m-d', strtotime ( 'now' ) );
		/*
		 * var_dump($currenttime);
		 * echo "<hr>";
		 * var_dump($currenttimePlus);
		 * echo "<hr>";
		 */
		
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'setting/tags' );
		
		foreach ( $listtasks as $list ) {
			
			$taskstarttime1111 = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
			$taskstarttime11 = date ( 'Y-m-d', strtotime ( $list ['task_date'] ) );
			$taskstarttime = $taskstarttime11 . ' ' . $taskstarttime1111;
			// var_dump($taskstarttime);
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'],$list['facilityId'] );
			
			$tasktypetype = $tasktype_info ['type'];
			$is_task_rule = $tasktype_info ['is_task_rule'];
			
			//var_dump($is_task_rule);
			
			if ($tasktype_info ['custom_completion_rule'] == '1') {
				$addTime = $tasktype_info ['config_task_complete'];
			} else {
				$addTime = $this->config->get ( 'config_task_complete' );
			}
			// var_dump($addTime);
			// var_dump(date('H:i:s'));
			$currenttimePlus = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
			
			// var_dump($currenttimePlus);
			// echo "<hr>=====";
			
			// echo $currenttimePlus .' >= '. $taskstarttime ;
			if($is_task_rule != '1'){
				if ($tasktypetype != '5') {
					if ($currenttimePlus >= $taskstarttime) {
						$taskDuration = '1';
					} else {
						if ($list ['is_pause'] == '1') {
							$taskDuration = '1';
						} else {
							$taskDuration = '2';
						}
					}
				} else {
					$taskDuration = '1';
				}
			}else{
				$taskDuration = '1';
			}
			
			// var_dump( $taskDuration);
			
			$bedcheckdata = array ();
			
			if ($list ['task_form_id'] != 0 && $list ['task_form_id'] != NULL) {
				
				if ($list ['bed_check_location_ids'] != null && $list ['bed_check_location_ids'] != "") {
					$formDatas = $this->model_setting_locations->getformid2 ( $list ['bed_check_location_ids'] );
				} else {
					$formDatas = $this->model_setting_locations->getformid ( $list ['task_form_id'] );
				}
				
				foreach ( $formDatas as $formData ) {
					
					$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
					
					$locationDatab = array ();
					$location_type = "";
					
					$location_typea = $locData ['location_type'];
					if ($location_typea == '1') {
						$location_type .= "Boys";
					}
					
					if ($location_typea == '2') {
						$location_type .= "Girls";
					}
					
					if ($location_typea == '3') {
						$location_type .= "Inmates";
					}
					
					if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
						$upload_file = $locData ['upload_file'];
					} else {
						$upload_file = "";
					}
					$locationDatab [] = array (
							'locations_id' => $locData ['locations_id'],
							'location_name' => $locData ['location_name'],
							'location_address' => $locData ['location_address'],
							'location_detail' => $locData ['location_detail'],
							'capacity' => $locData ['capacity'],
							'location_type' => $location_type,
							'upload_file' => $upload_file,
							'nfc_location_tag' => $locData ['nfc_location_tag'],
							'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
							'gps_location_tag' => $locData ['gps_location_tag'],
							'gps_location_tag_required' => $locData ['gps_location_tag_required'],
							'latitude' => $locData ['latitude'],
							'longitude' => $locData ['longitude'],
							'other_location_tag' => $locData ['other_location_tag'],
							'other_location_tag_required' => $locData ['other_location_tag_required'],
							'other_type_id' => $locData ['other_type_id'],
							'facilities_id' => $locData ['facilities_id'] 
					);
					
					$bedcheckdata [] = array (
							'task_form_location_id' => $formData ['task_form_location_id'],
							'location_name' => $formData ['location_name'],
							'location_detail' => $formData ['location_detail'],
							'current_occupency' => $formData ['current_occupency'],
							'bedcheck_locations' => $locationDatab 
					);
				}
				
				/*
				 * $this->load->model('setting/bedchecktaskform');
				 * $taskformData =
				 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
				 *
				 * foreach($taskformData as $frmData){
				 * $taskformsData[] = array(
				 * 'task_form_name' =>$frmData['task_form_name'],
				 * 'facilities_id' =>$frmData['facilities_id'],
				 * 'form_type' =>$frmData['form_type']
				 * );
				 * }
				 */
			}
			
			$medications = array ();
			
			/*
			 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
			 * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
			 * $locationData = array();
			 * $locData =
			 * $this->model_setting_locations->getlocation($tags_info['locations_id']);
			 *
			 * $locationData[] = array(
			 * 'locations_id' =>$locData['locations_id'],
			 * 'location_name' =>$locData['location_name'],
			 * 'location_address' =>$locData['location_address'],
			 * 'location_detail' =>$locData['location_detail'],
			 * 'capacity' =>$locData['capacity'],
			 * 'location_type' =>$locData['location_type'],
			 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
			 * 'nfc_location_tag_required'
			 * =>$locData['nfc_location_tag_required'],
			 * 'gps_location_tag' =>$locData['gps_location_tag'],
			 * 'gps_location_tag_required'
			 * =>$locData['gps_location_tag_required'],
			 * 'latitude' =>$locData['latitude'],
			 * 'longitude' =>$locData['longitude'],
			 * 'other_location_tag' =>$locData['other_location_tag'],
			 * 'other_location_tag_required'
			 * =>$locData['other_location_tag_required'],
			 * 'other_type_id' =>$locData['other_type_id'],
			 * 'facilities_id' =>$locData['facilities_id']
			 *
			 * );
			 *
			 *
			 * if($tags_info['upload_file'] != null && $tags_info['upload_file']
			 * != ""){
			 * $upload_file2 = $tags_info['upload_file'];
			 * }else{
			 * $upload_file2 = "";
			 * }
			 *
			 *
			 *
			 * $drugDatas = $this->model_setting_tags->getDrugs($list['id']);
			 * $drugaData = array();
			 * foreach($drugDatas as $drugData){
			 * $drugaData[] = array(
			 * 'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
			 * 'facilities_id' =>$drugData['facilities_id'],
			 * 'locations_id' =>$drugData['locations_id'],
			 * 'tags_id' =>$drugData['tags_id'],
			 * 'medication_id' =>$drugData['medication_id'],
			 * 'drug_name' =>$drugData['drug_name'],
			 * 'dose' =>$drugData['dose'],
			 * 'drug_type' =>$drugData['drug_type'],
			 * 'quantity' =>$drugData['quantity'],
			 * 'frequency' =>$drugData['frequency'],
			 * 'start_time' =>$drugData['start_time'],
			 * 'instructions' =>$drugData['instructions'],
			 * 'count' =>$drugData['count'],
			 * 'complete_status' =>$drugData['complete_status'],
			 * 'upload_file' =>$upload_file2,
			 * );
			 * }
			 *
			 *
			 * $medications[] = array(
			 * 'tags_id' =>$tags_info['tags_id'],
			 * 'upload_file' =>$upload_file2,
			 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
			 * 'emp_first_name' =>$tags_info['emp_first_name'],
			 * 'emp_last_name' =>$tags_info['emp_last_name'],
			 * 'doctor_name' =>$tags_info['doctor_name'],
			 * 'emergency_contact' =>$tags_info['emergency_contact'],
			 * 'dob' =>$tags_info['dob'],
			 * 'medications_locations' =>$locationData,
			 * 'medications_drugs' =>$drugaData
			 * );
			 *
			 *
			 *
			 *
			 * }
			 */
			
			$this->data ['transport_tags'] = array ();
			$this->load->model ( 'setting/tags' );
			
			if (! empty ( $list ['transport_tags'] )) {
				$transport_tags1 = explode ( ',', $list ['transport_tags'] );
			} else {
				$transport_tags1 = array ();
			}
			$transport_tags = array ();
			foreach ( $transport_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$transport_tags [] = array (
							'tags_id' => $tags_info ['tags_id'],
							'emp_tag_id' => $emp_tag_id 
					);
				}
			}
			
			/*
			 * if($list['iswaypoint'] == '1'){
			 * $transport_tags[] = array(
			 * 'tags_id' => 'Yes',
			 * 'emp_tag_id' => 'Round Trip'
			 * );
			 * }
			 */
			
			$medication_tags = array ();
			$this->data ['medication_tags'] = array ();
			$this->load->model ( 'setting/tags' );
			
			if (! empty ( $list ['medication_tags'] )) {
				$medication_tags1 = explode ( ',', $list ['medication_tags'] );
			} else {
				$medication_tags1 = array ();
			}
			
			foreach ( $medication_tags1 as $medicationtag ) {
				$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
				
				if ($tags_info1 ['emp_first_name']) {
					$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
				} else {
					$emp_tag_id = $tags_info1 ['emp_tag_id'];
				}
				
				if ($tags_info1) {
					
					$drugs = array ();
					
					$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $list ['id'], $medicationtag );
					
					foreach ( $mdrugs as $mdrug ) {
						
						$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
						if($mdrug_info ['drug_name'] != null && $mdrug_info ['drug_name'] != ""){
							$drugs [] = array (
									'drug_name' => $mdrug_info ['drug_name'] 
							);
						}
					}
					
					$medication_tags [] = array (
							'tags_id' => $tags_info1 ['tags_id'],
							'emp_tag_id' => $emp_tag_id,
							'tagsmedications' => $drugs 
					);
				}
			}
			
			if ($list ['visitation_tag_id']) {
				$visitation_tag = $this->model_setting_tags->getTag ( $list ['visitation_tag_id'] );
				
				if ($visitation_tag ['emp_first_name']) {
					$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'] . ' ' . $visitation_tag ['emp_last_name'];
				} else {
					$visitation_tag_id = $visitation_tag ['emp_tag_id'];
				}
			} else {
				$visitation_tag_id = "";
			}
			
			if ($list ['formreturn_id'] > 0) {
				$this->load->model ( 'form/form' );
				$result_info = $this->model_form_form->getFormDatas ( $list ['formreturn_id'] );
			}
			
			$this->data ['listtask'] [] = array (
					'assign_to' => $list ['assign_to'],
					'formreturn_id' => $list ['formreturn_id'],
					'notes_id' => $result_info ['notes_id'],
					'enable_requires_approval' => $list ['enable_requires_approval'],
					'attachement_form' => $list ['attachement_form'],
					'tasktype_form_id' => $list ['tasktype_form_id'],
					'tasktype' => $list ['tasktype'],
					'send_notification' => $list ['send_notification'],
					'checklist' => $list ['checklist'],
					'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
					'end_recurrence_date' => date ( 'j, M Y', strtotime ( $list ['end_recurrence_date'] ) ),
					'id' => $list ['id'],
					'description' => $list ['description'],
					'taskDuration' => $taskDuration,
					'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ),
					'task_form_id' => $list ['task_form_id'],
					'tags_id' => $list ['tags_id'],
					'pickup_facilities_id' => $list ['pickup_facilities_id'],
					'pickup_locations_address' => $list ['pickup_locations_address'],
					'pickup_locations_time' => date ( 'h:i A', strtotime ( $list ['pickup_locations_time'] ) ),
					'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
					'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
					'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
					'dropoff_locations_address' => $list ['dropoff_locations_address'],
					'dropoff_locations_time' => date ( 'h:i A', strtotime ( $list ['dropoff_locations_time'] ) ),
					'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
					'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
					'transport_tags' => $transport_tags,
					'medications' => $medications,
					'bedchecks' => $bedcheckdata,
					'medication_tags' => $medication_tags,
					
					'visitation_tags' => $list ['visitation_tags'],
					'visitation_tag_id' => $visitation_tag_id,
					'visitation_start_facilities_id' => $list ['visitation_start_facilities_id'],
					'visitation_start_address' => $list ['visitation_start_address'],
					'visitation_start_time' => date ( 'h:i A', strtotime ( $list ['visitation_start_time'] ) ),
					'visitation_start_address_latitude' => $list ['visitation_start_address_latitude'],
					'visitation_start_address_longitude' => $list ['visitation_start_address_longitude'],
					'visitation_appoitment_facilities_id' => $list ['visitation_appoitment_facilities_id'],
					'visitation_appoitment_address' => $list ['visitation_appoitment_address'],
					'visitation_appoitment_time' => date ( 'h:i A', strtotime ( $list ['visitation_appoitment_time'] ) ),
					'visitation_appoitment_address_latitude' => $list ['visitation_appoitment_address_latitude'],
					'visitation_appoitment_address_longitude' => $list ['visitation_appoitment_address_longitude'] 
			);
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/tasklist.php';
		$this->children = array (
				'common/headertask' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function headertasklisttotal() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->data ['notes_url'] = $this->url->link ( 'notes/notes/insert', '' . '&reset=1' . $url, 'SSL' );
		$this->data ['notes_url_close'] = $this->url->link ( 'notes/notes/insert', '' . '&reset=1', 'SSL' );
		$this->data ['support_url'] = $this->url->link ( 'notes/support', '', 'SSL' );
		$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/search', '', 'SSL' );
		
		$this->data ['taskrefress'] = $this->request->get ['taskrefress'];
		
		$this->data ['createtask_url'] = $this->url->link ( 'notes/createtask', '', 'SSL' );
		$this->data ['updatestriketask_url'] = $this->url->link ( 'notes/createtask/updateStriketask', '', 'SSL' );
		
		$this->data ['updatestriketask_url'] = $this->url->link ( 'notes/createtask/updateStriketask', '', 'SSL' );
		$this->data ['addtasktask_url'] = $this->url->link ( 'notes/notes/index', '', 'SSL' );
		$this->data ['inserttask_url'] = $this->url->link ( 'notes/createtask/inserttask', '', 'SSL' );
		
		$this->data ['approval_url'] = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
		
		$this->data ['checklist_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/checklistform', '' . $url2, 'SSL' ) );
		$this->data ['incident_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/noteform/taskforminsert', '' . $url2, 'SSL' ) );
		
		$this->data ['reviewnoted_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/reviewNotes', '' . $url2, 'SSL' ) );
		
		$this->data ['custom_form_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$this->data ['update_strike_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateStrike', '' . $url2, 'SSL' ) );
		$this->data ['update_strike_url_private'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateStrikeprivate', '' . $url2, 'SSL' ) );
		$this->data ['alarm_url'] = $this->url->link ( 'notes/notes/setAlarm', '', 'SSL' );
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($resulsst ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
		}
		date_default_timezone_set ( $timezone_name );
		
		if (isset ( $this->request->get ['searchdate'] )) {
			$res = explode ( "-", $this->request->get ['searchdate'] );
			$createdate1 = $res [1] . "-" . $res [0] . "-" . $res [2];
			
			$this->data ['note_date'] = date ( 'D F j, Y', strtotime ( $createdate1 ) );
			$currentdate = $createdate1;
		} else {
			$this->data ['note_date'] = date ( 'D F j, Y' ); // date('m-d-Y');
			
			$currentdate = date ( 'd-m-Y' );
		}
		
		$addTime = $this->config->get ( 'config_task_complete' );
		
		/*
		 * if($config_task_complete == '5min'){
		 * $addTime = '5';
		 * }else
		 * if($config_task_complete == '10min'){
		 * $addTime = '10';
		 * }
		 * else
		 * if($config_task_complete == '15min'){
		 * $addTime = '15';
		 * }else
		 * if($config_task_complete == '20min'){
		 * $addTime = '20';
		 * }else
		 * if($config_task_complete == '25min'){
		 * $addTime = '25';
		 * }else
		 * if($config_task_complete == '30min'){
		 * $addTime = '30';
		 * }else
		 * if($config_task_complete == '45min'){
		 * $addTime = '45';
		 * }else
		 * if($config_task_complete == '45min'){
		 * $addTime = '45';
		 * }
		 */
		
		$this->data ['deleteTime'] = $deleteTime;
		
		$this->load->model ( 'createtask/createtask' );
		$top = '1';
		// $listtasks =
		// $this->model_createtask_createtask->getTasklist($this->customer->getId(),
		// $currentdate, $top);
		
		$tasktypes = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
		
		// var_dump($tasktypes);
		
		foreach ( $tasktypes as $tasktype ) {
			
			$taskTotal = $this->model_createtask_createtask->getCountTasklist ( $facilities_id, $currentdate, $top, '', '', $tasktype ['task_id'] );
			
			$taskTotal1 = $taskTotal1 + $taskTotal;
		}
		
		$this->data ['taskTotal'] = $taskTotal1;
		
		// var_dump($this->data['taskTotal']);
		
		// $json = array();
		
		// $json['taskTotal'] = $taskTotal;
		
		// $this->response->setOutput(json_encode($taskTotal));
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/tasklisttotal.php';
		$this->response->setOutput ( $this->render () );
	}
	public function approvalurl() {
		$this->load->model ( 'createtask/createtask' );
		
		if ($this->request->get ['task_id'] != NULL && $this->request->get ['task_id'] != "") {
			$url2 = '&task_id=' . $this->request->get ['task_id'];
		}
		
		if ($this->request->get ['notes_id'] != NULL && $this->request->get ['notes_id'] != "") {
			$url2 = '&notes_id=' . $this->request->get ['notes_id'];
			$this->data ['notes_id'] = $this->request->get ['notes_id'];
		}
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' ) );
		
		if ($this->request->get ['notes_id'] == NULL && $this->request->get ['notes_id'] == "") {
			if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateFormapprove ()) {
				
				// var_dump($this->request->get['task_id']);
				
				$approvaltask_info = $this->model_createtask_createtask->getNBYTaksLists ( $this->request->get ['task_id'] );
				
				if ($approvaltask_info != null && $approvaltask_info != "") {
					$this->model_createtask_createtask->deleteNBYTaksLists ( $this->request->get ['task_id'] );
				}
				
				$approvaltasklists = $this->model_createtask_createtask->getApprovaltasklist ( $this->request->get ['task_id'] );
				
				if ($approvaltasklists != null && $approvaltasklists != "") {
					foreach ( $approvaltasklists as $approvaltasklist ) {
						$this->model_createtask_createtask->deleteNBYTaksLists ( $approvaltasklist ['id'] );
					}
					
					foreach ( $approvaltasklists as $approvaltasklist ) {
						$this->model_createtask_createtask->addapproveTask ( $approvaltasklist );
					}
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				if ($this->request->post ['requires_approval'] == "approve") {
					$url2 .= '&requires_approval=' . $this->request->post ['requires_approval'];
					if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=2', '' . $url2, 'SSL' ) ) );
					} else {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/inserttask', '' . $url2, 'SSL' ) ) );
					}
				} else {
					$url2 .= '&requires_approval=' . $this->request->post ['requires_approval'];
					
					if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=1', '' . $url2, 'SSL' ) ) );
					} else {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/updateStriketask', '' . $url2, 'SSL' ) ) );
					}
				}
			}
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		$this->data ['deleteTime'] = $deleteTime;
		
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );
		
		$task_ino = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
		
		if ($task_ino ['is_approval_required_forms_id'] > 0) {
			$this->load->model ( 'form/form' );
			$form_info = $this->model_form_form->getFormDatas ( $task_ino ['is_approval_required_forms_id'] );
			
			$note_info = $this->model_notes_notes->getNote ( $form_info ['notes_id'] );
			
			if ($form_info ['user_id'] != null && $form_info ['user_id'] != "") {
				$user_id = $form_info ['user_id'];
				$signature = $form_info ['signature'];
				$notes_pin = $form_info ['notes_pin'];
				$notes_type = $form_info ['notes_type'];
				
				if ($form_info ['form_date_added'] != null && $form_info ['form_date_added'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $form_info ['form_date_added'] ) );
				} else {
					$form_date_added = '';
				}
			} else {
				$user_id = $note_info ['user_id'];
				$signature = $note_info ['signature'];
				$notes_pin = $note_info ['notes_pin'];
				$notes_type = $note_info ['notes_type'];
				
				if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
				} else {
					$form_date_added = '';
				}
			}
			
			$this->data ['user_id'] = $user_id;
			$this->data ['signature'] = $signature;
			$this->data ['notes_pin'] = $notes_pin;
			$this->data ['notes_type'] = $notes_type;
			$this->data ['form_date_added'] = $form_date_added;
			
			$this->data ['notes_description'] = $note_info ['notes_description'];
			$this->data ['incident_number'] = $form_info ['incident_number'];
			$user_is_approval_required_forms_id = '1';
		} else {
			
			if ($this->request->get ['notes_id'] != NULL && $this->request->get ['notes_id'] != "") {
				
				$note_info1 = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
				// var_dump($note_info);
				
				if ($note_info1 ['is_approval_required_forms_id'] > 0) {
					$this->load->model ( 'form/form' );
					$form_info = $this->model_form_form->getFormDatas ( $note_info1 ['is_approval_required_forms_id'] );
					// var_dump($form_info);
					$note_info = $this->model_notes_notes->getNote ( $form_info ['notes_id'] );
					if ($form_info ['user_id'] != null && $form_info ['user_id'] != "") {
						$user_id = $form_info ['user_id'];
						$signature = $form_info ['signature'];
						$notes_pin = $form_info ['notes_pin'];
						$notes_type = $form_info ['notes_type'];
						
						if ($form_info ['form_date_added'] != null && $form_info ['form_date_added'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $form_info ['form_date_added'] ) );
						} else {
							$form_date_added = '';
						}
					} else {
						$user_id = $note_info ['user_id'];
						$signature = $note_info ['signature'];
						$notes_pin = $note_info ['notes_pin'];
						$notes_type = $note_info ['notes_type'];
						
						if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
						} else {
							$form_date_added = '';
						}
					}
					
					$this->data ['user_id'] = $user_id;
					$this->data ['signature'] = $signature;
					$this->data ['notes_pin'] = $notes_pin;
					$this->data ['notes_type'] = $notes_type;
					$this->data ['form_date_added'] = $form_date_added;
					
					$this->data ['notes_description'] = $note_info ['notes_description'];
					$this->data ['incident_number'] = $form_info ['incident_number'];
					
					$user_is_approval_required_forms_id = '1';
				} else {
					
					$approvaltasklists = $this->model_notes_notes->getApprovaltasklist ( $this->request->get ['task_id'] );
				}
			} else {
				$approvaltasklists = $this->model_createtask_createtask->getApprovaltasklist ( $this->request->get ['task_id'] );
			}
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		$currentdate = date ( 'Y-m-d', strtotime ( 'now' ) );
		/*
		 * var_dump($currenttime);
		 * echo "<hr>";
		 * var_dump($currenttimePlus);
		 * echo "<hr>";
		 */
		
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'setting/tags' );
		
		foreach ( $approvaltasklists as $list ) {
			
			$taskstarttime = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'],$list['facilityId'] );
			
			if ($tasktype_info ['custom_completion_rule'] == '1') {
				$addTime = $tasktype_info ['config_task_complete'];
			} else {
				$addTime = $this->config->get ( 'config_task_complete' );
			}
			
			$currenttimePlus = date ( 'H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
			
			// var_dump($currenttimePlus);
			// echo "<hr>=====";
			
			// echo $currenttimePlus .' >= '. $taskstarttime ;
			
			$tasktypetype = $tasktype_info ['type'];
			$is_task_rule = $tasktype_info ['is_task_rule'];
			
			if($is_task_rule != '1'){
				if ($currenttimePlus >= $taskstarttime) {
					$taskDuration = '1';
				} else {
					$taskDuration = '2';
				}
			}else{
				$taskDuration = '1';
			}
			
			$bedcheckdata = array ();
			
			if ($list ['task_form_id'] != 0 && $list ['task_form_id'] != NULL) {
				
				if ($list ['bed_check_location_ids'] != null && $list ['bed_check_location_ids'] != "") {
					$formDatas = $this->model_setting_locations->getformid2 ( $list ['bed_check_location_ids'] );
				} else {
					$formDatas = $this->model_setting_locations->getformid ( $list ['task_form_id'] );
				}
				
				foreach ( $formDatas as $formData ) {
					
					$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
					
					$locationDatab = array ();
					$location_type = "";
					
					$location_typea = $locData ['location_type'];
					if ($location_typea == '1') {
						$location_type .= "Boys";
					}
					
					if ($location_typea == '2') {
						$location_type .= "Girls";
					}
					
					if ($location_typea == '3') {
						$location_type .= "Inmates";
					}
					
					if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
						$upload_file = $locData ['upload_file'];
					} else {
						$upload_file = "";
					}
					$locationDatab [] = array (
							'locations_id' => $locData ['locations_id'],
							'location_name' => $locData ['location_name'],
							'location_address' => $locData ['location_address'],
							'location_detail' => $locData ['location_detail'],
							'capacity' => $locData ['capacity'],
							'location_type' => $location_type,
							'upload_file' => $upload_file,
							'nfc_location_tag' => $locData ['nfc_location_tag'],
							'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
							'gps_location_tag' => $locData ['gps_location_tag'],
							'gps_location_tag_required' => $locData ['gps_location_tag_required'],
							'latitude' => $locData ['latitude'],
							'longitude' => $locData ['longitude'],
							'other_location_tag' => $locData ['other_location_tag'],
							'other_location_tag_required' => $locData ['other_location_tag_required'],
							'other_type_id' => $locData ['other_type_id'],
							'facilities_id' => $locData ['facilities_id'] 
					);
					
					$bedcheckdata [] = array (
							'task_form_location_id' => $formData ['task_form_location_id'],
							'location_name' => $formData ['location_name'],
							'location_detail' => $formData ['location_detail'],
							'current_occupency' => $formData ['current_occupency'],
							'bedcheck_locations' => $locationDatab 
					);
				}
				
				/*
				 * $this->load->model('setting/bedchecktaskform');
				 * $taskformData =
				 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
				 *
				 * foreach($taskformData as $frmData){
				 * $taskformsData[] = array(
				 * 'task_form_name' =>$frmData['task_form_name'],
				 * 'facilities_id' =>$frmData['facilities_id'],
				 * 'form_type' =>$frmData['form_type']
				 * );
				 * }
				 */
			}
			
			$medications = array ();
			
			/*
			 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
			 * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
			 * $locationData = array();
			 * $locData =
			 * $this->model_setting_locations->getlocation($tags_info['locations_id']);
			 *
			 * $locationData[] = array(
			 * 'locations_id' =>$locData['locations_id'],
			 * 'location_name' =>$locData['location_name'],
			 * 'location_address' =>$locData['location_address'],
			 * 'location_detail' =>$locData['location_detail'],
			 * 'capacity' =>$locData['capacity'],
			 * 'location_type' =>$locData['location_type'],
			 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
			 * 'nfc_location_tag_required'
			 * =>$locData['nfc_location_tag_required'],
			 * 'gps_location_tag' =>$locData['gps_location_tag'],
			 * 'gps_location_tag_required'
			 * =>$locData['gps_location_tag_required'],
			 * 'latitude' =>$locData['latitude'],
			 * 'longitude' =>$locData['longitude'],
			 * 'other_location_tag' =>$locData['other_location_tag'],
			 * 'other_location_tag_required'
			 * =>$locData['other_location_tag_required'],
			 * 'other_type_id' =>$locData['other_type_id'],
			 * 'facilities_id' =>$locData['facilities_id']
			 *
			 * );
			 *
			 *
			 * if($tags_info['upload_file'] != null && $tags_info['upload_file']
			 * != ""){
			 * $upload_file2 = $tags_info['upload_file'];
			 * }else{
			 * $upload_file2 = "";
			 * }
			 *
			 *
			 *
			 * $drugDatas = $this->model_setting_tags->getDrugs($list['id']);
			 * $drugaData = array();
			 * foreach($drugDatas as $drugData){
			 * $drugaData[] = array(
			 * 'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
			 * 'facilities_id' =>$drugData['facilities_id'],
			 * 'locations_id' =>$drugData['locations_id'],
			 * 'tags_id' =>$drugData['tags_id'],
			 * 'medication_id' =>$drugData['medication_id'],
			 * 'drug_name' =>$drugData['drug_name'],
			 * 'dose' =>$drugData['dose'],
			 * 'drug_type' =>$drugData['drug_type'],
			 * 'quantity' =>$drugData['quantity'],
			 * 'frequency' =>$drugData['frequency'],
			 * 'start_time' =>$drugData['start_time'],
			 * 'instructions' =>$drugData['instructions'],
			 * 'count' =>$drugData['count'],
			 * 'complete_status' =>$drugData['complete_status'],
			 * 'upload_file' =>$upload_file2,
			 * );
			 * }
			 *
			 *
			 * $medications[] = array(
			 * 'tags_id' =>$tags_info['tags_id'],
			 * 'upload_file' =>$upload_file2,
			 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
			 * 'emp_first_name' =>$tags_info['emp_first_name'],
			 * 'emp_last_name' =>$tags_info['emp_last_name'],
			 * 'doctor_name' =>$tags_info['doctor_name'],
			 * 'emergency_contact' =>$tags_info['emergency_contact'],
			 * 'dob' =>$tags_info['dob'],
			 * 'medications_locations' =>$locationData,
			 * 'medications_drugs' =>$drugaData
			 * );
			 *
			 *
			 *
			 *
			 * }
			 */
			
			$this->data ['transport_tags'] = array ();
			$this->load->model ( 'setting/tags' );
			
			if (! empty ( $list ['transport_tags'] )) {
				$transport_tags1 = explode ( ',', $list ['transport_tags'] );
			} else {
				$transport_tags1 = array ();
			}
			$transport_tags = array ();
			foreach ( $transport_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$transport_tags [] = array (
							'tags_id' => $tags_info ['tags_id'],
							'emp_tag_id' => $emp_tag_id 
					);
				}
			}
			
			$medication_tags = array ();
			$this->data ['medication_tags'] = array ();
			$this->load->model ( 'setting/tags' );
			
			if (! empty ( $list ['medication_tags'] )) {
				$medication_tags1 = explode ( ',', $list ['medication_tags'] );
			} else {
				$medication_tags1 = array ();
			}
			
			foreach ( $medication_tags1 as $medicationtag ) {
				$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
				
				if ($tags_info1 ['emp_first_name']) {
					$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
				} else {
					$emp_tag_id = $tags_info1 ['emp_tag_id'];
				}
				
				if ($tags_info1) {
					
					$drugs = array ();
					
					$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $list ['id'], $medicationtag );
					
					foreach ( $mdrugs as $mdrug ) {
						
						$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
						
						$drugs [] = array (
								'drug_name' => $mdrug_info ['drug_name'] 
						);
					}
					
					$medication_tags [] = array (
							'tags_id' => $tags_info1 ['tags_id'],
							'emp_tag_id' => $emp_tag_id,
							'tagsmedications' => $drugs 
					);
				}
			}
			
			if ($list ['visitation_tag_id']) {
				$visitation_tag = $this->model_setting_tags->getTag ( $list ['visitation_tag_id'] );
				
				if ($visitation_tag ['emp_first_name']) {
					$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'] . ' ' . $visitation_tag ['emp_last_name'];
				} else {
					$visitation_tag_id = $visitation_tag ['emp_tag_id'];
				}
			} else {
				$visitation_tag_id = "";
			}
			
			$this->data ['listtask'] [] = array (
					'assign_to' => $list ['assign_to'],
					'enable_requires_approval' => $list ['enable_requires_approval'],
					'attachement_form' => $list ['attachement_form'],
					'tasktype_form_id' => $list ['tasktype_form_id'],
					'tasktype' => $list ['tasktype'],
					'send_notification' => $list ['send_notification'],
					'checklist' => $list ['checklist'],
					'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
					'end_recurrence_date' => date ( 'j, M Y', strtotime ( $list ['end_recurrence_date'] ) ),
					'id' => $list ['id'],
					'description' => $list ['description'],
					'taskDuration' => $taskDuration,
					'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ),
					'task_form_id' => $list ['task_form_id'],
					'tags_id' => $list ['tags_id'],
					'pickup_facilities_id' => $list ['pickup_facilities_id'],
					'pickup_locations_address' => $list ['pickup_locations_address'],
					'pickup_locations_time' => date ( 'h:i A', strtotime ( $list ['pickup_locations_time'] ) ),
					'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
					'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
					'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
					'dropoff_locations_address' => $list ['dropoff_locations_address'],
					'dropoff_locations_time' => date ( 'h:i A', strtotime ( $list ['dropoff_locations_time'] ) ),
					'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
					'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
					'transport_tags' => $transport_tags,
					'medications' => $medications,
					'bedchecks' => $bedcheckdata,
					'medication_tags' => $medication_tags,
					
					'visitation_tags' => $list ['visitation_tags'],
					'visitation_tag_id' => $visitation_tag_id,
					'visitation_start_facilities_id' => $list ['visitation_start_facilities_id'],
					'visitation_start_address' => $list ['visitation_start_address'],
					'visitation_start_time' => date ( 'h:i A', strtotime ( $list ['visitation_start_time'] ) ),
					'visitation_start_address_latitude' => $list ['visitation_start_address_latitude'],
					'visitation_start_address_longitude' => $list ['visitation_start_address_longitude'],
					'visitation_appoitment_facilities_id' => $list ['visitation_appoitment_facilities_id'],
					'visitation_appoitment_address' => $list ['visitation_appoitment_address'],
					'visitation_appoitment_time' => date ( 'h:i A', strtotime ( $list ['visitation_appoitment_time'] ) ),
					'visitation_appoitment_address_latitude' => $list ['visitation_appoitment_address_latitude'],
					'visitation_appoitment_address_longitude' => $list ['visitation_appoitment_address_longitude'] 
			);
		}
		
		if ($user_is_approval_required_forms_id == '1') {
			$this->template = $this->config->get ( 'config_template' ) . '/template/form/requireapprovalform.php';
		} else {
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/requireapproval.php';
		}
		
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	protected function validateFormapprove() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['requires_approval'] == null && $this->request->post ['requires_approval'] == "") {
			$this->error ['warning'] = 'Warning: please select Status';
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function approvalAutocomplete() {
		$json = array ();
		$this->load->model ( 'createtask/createtask' );
		$alltasktype = $this->model_createtask_createtask->gettasktyperow ( $this->request->get ['tasktype_id'] );
		
		$json = array (
				'enable_requires_approval' => $alltasktype ['enable_requires_approval'] 
		);
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	/*public function gettasktypeid() {
		$json = array ();
		$this->load->model ( 'createtask/createtask' );
		$alltasktype = $this->model_createtask_createtask->gettasktyperow ( $this->request->get ['tasktype_id'] );
		
		$json = array (
				'type' => $alltasktype ['type'],
				'enable_requires_approval' => $alltasktype ['enable_requires_approval'] 
		);
		
		$this->response->setOutput ( json_encode ( $json ) );
	}*/

	public function gettasktypeid() {
		$json = array ();
		$this->load->model ( 'createtask/createtask' );
		$alltasktype = $this->model_createtask_createtask->gettasktyperow ( $this->request->get ['tasktype_id'] );
		
		$json = array (
				'type' => $alltasktype ['type'],
				'enable_requires_approval' => $alltasktype ['enable_requires_approval'],
				'is_facility' => $alltasktype ['is_facility'],
                'facility_type' => $alltasktype ['facility_type'], 
		);
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
} 