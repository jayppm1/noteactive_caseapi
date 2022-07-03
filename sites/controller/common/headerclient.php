<?php
class ControllerCommonheaderclient extends Controller {
	protected function index() {
		try {
			
			/*
			 * if (!$this->customer->isLogged()) {
			 * $this->redirect($this->url->link('common/login', '', 'SSL'));
			 * }
			 */
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'setting/tags' );
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->data ['facilityname'] = $this->customer->getfacility ();
			
			if ($facility ['notes_facilities_ids'] != null && $facility ['notes_facilities_ids'] != "") {
				$this->data ['is_master_facility'] = '1';
			} else {
				$this->data ['is_master_facility'] = '2';
			}
			
			if ($this->request->get ['route'] == "resident/resident/hourout") {
				$this->data ['masterUlr'] = $this->url->link ( 'resident/resident/masterfacility&redirectpage=hourout', '', 'SSL' );
			} else {
				$this->data ['masterUlr'] = $this->url->link ( 'resident/resident/masterfacility', '', 'SSL' );
			}
			
			if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != "") {
				if ($this->session->data ['search_facilities_id'] != $this->customer->getId ()) {
					$this->data ['search_facilities_id'] = $this->session->data ['search_facilities_id'];
					
					$searchf_name = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
					$this->data ['searchf_name'] = $searchf_name ['facility'];
				}
			}
			
			if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data ['customers'] = array ();
			if (! empty ( $customer_info ['setting_data'] )) {
				$customers = unserialize ( $customer_info ['setting_data'] );
				$this->data ['customerinfo'] = $customers;
			}
			
			$this->data ['red_color'] = $customers ['red_color'];
			$this->data ['orange_color'] = $customers ['orange_color'];
			$this->data ['green_color'] = $customers ['green_color'];
			
			$this->data ['inPercent_reminder'] = 0;
			$out_the_sell = 0;
			if (isset ( $customers ['duration_type'] ) && $customers ['duration_type'] == 1) {
				$out_the_sell = $customers ['out_the_sell'];
			} else if (isset ( $customers ['duration_type'] ) && $customers ['duration_type'] == 2) {
				$out_the_sell = $customers ['out_the_sell'] * 60;
			} else if (isset ( $customers ['duration_type'] ) && $customers ['duration_type'] == 3) {
				$out_the_sell = $customers ['out_the_sell'] * 60 * 24;
			}
			
			$out_the_sell_reminder = 0;
			if (isset ( $customers ['reminder_duration_type'] ) && $customers ['reminder_duration_type'] == 1) {
				$out_the_sell_reminder = $customers ['out_the_sell_reminder'];
			} else if (isset ( $customers ['reminder_duration_type'] ) && $customers ['reminder_duration_type'] == 2) {
				$out_the_sell_reminder = $customers ['out_the_sell_reminder'] * 60;
			} else if (isset ( $customers ['reminder_duration_type'] ) && $customers ['reminder_duration_type'] == 3) {
				$out_the_sell_reminder = $customers ['out_the_sell_reminder'] * 60 * 24;
			}
			
			$inPercent_reminder = floor ( ($out_the_sell_reminder * 100) / $out_the_sell );
			
			/**
			 * *************** Hourout Reminder code end **************************
			 */
			
			if ($customers ['red_progress_percentage'] != '') {
				$red_progress_percentage = $customers ['red_progress_percentage'];
			} else {
				$red_progress_percentage = 30;
			}
			
			if ($customers ['orange_progress_percentage'] != '') {
				$orange_progress_percentage = $customers ['orange_progress_percentage'];
			} else {
				$orange_progress_percentage = 60;
			}
			
			if ($customers ['green_progress_percentage'] != '') {
				$green_progress_percentage = $customers ['green_progress_percentage'];
			} else {
				$green_progress_percentage = 90;
			}
			
			$this->load->model ( 'notes/clientstatus' );
			$data3 = array ();
			$data3 ['facilities_id'] = $facilities_id;
			$data3 ['role_call'] = $this->request->get ['role_call'];
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
			foreach ( $customforms as $customform ) {
				$rule_action_content = unserialize ( $customform ['rule_action_content'] );
				if ($rule_action_content ['out_from_cell'] == "1") {
					$hourout_arr [] = $customform ['tag_status_id'];
				}
			}
			
			$rolecalls = implode ( ",", $hourout_arr );
			
			if ($rolecalls != null && $rolecalls != "") {
				$roleData = array ();
				$roleData ['rolecalls'] = $rolecalls;
				$roleData ['facilities_id'] = $facilities_id;
				$roleData ['is_master'] = $is_master_facility;
				$roleData ['is_client_screen'] = $is_client_screen;
				$tagrecs = $this->model_setting_tags->getHourOutCount ( $roleData );
				$hourout_count = 0;
				$red_progress_percentage_count = 0;
				$orange_progress_percentage_count = 0;
				$green_progress_percentage_count = 0;
				
				foreach ( $tagrecs as $rec ) {
					$hourout_count = $hourout_count + 1;
					$houroutdata = array ();
					$houroutdata ['tags_id'] = $rec ['tags_id'];
					$houroutdata ['currentdate'] = date ( 'Y-m-d' );
					// $houroutdata['rules_operation'] = $customers ['rules_operation'];
					// $houroutdata['rules_start_time'] = $customers ['rules_start_time'];
					// $houroutdata['rules_end_time'] = $customers ['rules_end_time'];
					
					$outcelltime = $this->model_setting_tags->getOutToCellTime ( $houroutdata );
					$totaltime = $outcelltime ['totaltime'];
					$hourout = 0;
					$percent = 0;
					if ($rec ['notes_id'] > 0) {
						$noesData = $this->model_notes_notes->getnotes ( $rec ['notes_id'] );
						if (! empty ( $noesData )) {
							
							$dataprogress = array ();
							$dataprogress ['date_a'] = date ( 'Y-m-d H:i:s' );
							$dataprogress ['date_added'] = $noesData ['date_added'];
							$dataprogress ['duration_type'] = $customers ['duration_type'];
							$dataprogress ['out_the_sell'] = $customers ['out_the_sell'];
							$dataprogress ['totaltime'] = $totaltime;
							$response = $this->model_setting_tags->getHourOutProgress ( $dataprogress );
							$hourout = $response ['hourout'];
							$percent = $response ['inPercent'];
							// echo '<pre>'; print_r($hourout); echo '</pre>';
							if ($percent > 0 && $percent <= $red_progress_percentage) {
								$red_progress_percentage_count = $red_progress_percentage_count + 1;
							} else if ($percent > $red_progress_percentage && $percent <= $orange_progress_percentage) {
								$orange_progress_percentage_count = $orange_progress_percentage_count + 1;
							} else if ($percent > $orange_progress_percentage) {
								$green_progress_percentage_count = $green_progress_percentage_count + 1;
							}
							
							if ($inPercent_reminder <= $percent) {
								$this->data ['inPercent_reminder'] = 1;
							} else {
								$this->data ['inPercent_reminder'] = 0;
							}
						}
					}
				}
			}
			
			$this->data ['hourout_count'] = $hourout_count;
			$this->data ['red_hourout_count'] = $red_progress_percentage_count;
			$this->data ['orang_hourout_count'] = $orange_progress_percentage_count;
			$this->data ['green_hourout_count'] = $green_progress_percentage_count;
			$this->data ['red_color'] = $customers ['red_color'];
			$this->data ['orange_color'] = $customers ['orange_color'];
			$this->data ['green_color'] = $customers ['green_color'];
			$this->data ['case_url1'] = $this->url->link ( 'resident/formcase/cases&addcase=1', '', 'SSL' );
			$this->data ['resident_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
			$this->data ['hour_out_url'] = $this->url->link ( 'resident/resident/hourout', '' . $url1, 'SSL' );
			
			if ($this->request->get ['route'] == 'resident/resident' || $this->request->get ['route'] == 'resident/resident/hourout') {
				$this->data ['check_resident_url'] = 1;
				$this->document->setTitle ( 'Clients' );
				$this->data ['heading_title'] = 'Clients';
				$this->data ['is_client'] = '1';
				
				$this->data ['total_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
				
				
				
				$this->load->model ( 'setting/tags' );
				
				$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				if ($facilities_is_master ['is_master_facility'] == 0) {
					$is_master_facility = 1;
				} else {
					$is_master_facility = $facilities_is_master ['is_master_facility'];
				}
				
				if ($facilities_is_master ['enable_facilityinout'] != '1') {
					$is_client_screen = '0';
				} else {
					$is_client_screen = '1';
					$facility_inout = '2';
				}
				
				$this->load->model ( 'notes/clientstatus' );
				
				if ($this->request->get ['client_status'] != "1") {
					$inclint = array (
							0 
					);
				} else {
					$inclint = array ();
				}
				$outcount = array ();
				
				$movecount = array ();
				$hourout_arr = array ();
				
				if ($this->request->get ['client_status'] != "" && $this->request->get ['client_status'] != null) {
					
					if ($this->request->get ['role_call'] != "" && $this->request->get ['role_call'] != null) {
						
						$data3 = array ();
						$data3 ['facilities_id'] = $facilities_id;
						$data3 ['role_call'] = $this->request->get ['role_call'];
						$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
						foreach ( $customforms as $customform ) {
							
							$this->data ['clientstatuss'] [] = array (
									'tag_status_id' => $customform ['tag_status_id'],
									'name' => $customform ['name'],
									'facilities_id' => $customform ['facilities_id'],
									'display_client' => $customform ['display_client'] 
							);
							
							if ($customform ['type'] == "0" || $customform ['type'] == "2") {
								$inclint [] = $customform ['tag_status_id'];
							}
							
							if ($facilities_is_master ['enable_facilityinout'] == '1') {
								if ($customform ['type'] == "3") {
									$inclint [] = $customform ['tag_status_id'];
								}
							} else {
								if ($customform ['type'] == "3") {
									$outcount [] = $customform ['tag_status_id'];
								}
							}
							
							if ($customform ['type'] == "4") {
								$movecount [] = $customform ['tag_status_id'];
							}
							
							$rule_action_content = unserialize ( $customform ['rule_action_content'] );
							if ($rule_action_content ['out_from_cell'] == "1") {
								$hourout_arr [] = $customform ['tag_status_id'];
							}
						}
					} else {
						if ($this->request->get ['client_status'] == "3") {
							$data3 = array ();
							$data3 ['facilities_id'] = $facilities_id;
							$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
							foreach ( $customforms as $customform ) {
								
								$this->data ['clientstatuss'] [] = array (
										'tag_status_id' => $customform ['tag_status_id'],
										'name' => $customform ['name'],
										'facilities_id' => $customform ['facilities_id'],
										'display_client' => $customform ['display_client'] 
								);
								
								if ($customform ['type'] == "4") {
									$movecount [] = $customform ['tag_status_id'];
								}
								
								$rule_action_content = unserialize ( $customform ['rule_action_content'] );
								if ($rule_action_content ['out_from_cell'] == "1") {
									$hourout_arr [] = $customform ['tag_status_id'];
								}
							}
						}
						if ($this->request->get ['client_status'] == "2") {
							$data3 = array ();
							$data3 ['facilities_id'] = $facilities_id;
							$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
							foreach ( $customforms as $customform ) {
								
								$this->data ['clientstatuss'] [] = array (
										'tag_status_id' => $customform ['tag_status_id'],
										'name' => $customform ['name'],
										'facilities_id' => $customform ['facilities_id'],
										'display_client' => $customform ['display_client'] 
								);
								
								if ($customform ['type'] == "0" || $customform ['type'] == "2") {
									$inclint [] = $customform ['tag_status_id'];
								}
								if ($facilities_is_master ['enable_facilityinout'] == '1') {
									if ($customform ['type'] == "3") {
										$inclint [] = $customform ['tag_status_id'];
									}
								}
								
								if ($customform ['type'] == "4") {
									$movecount [] = $customform ['tag_status_id'];
								}
								
								$rule_action_content = unserialize ( $customform ['rule_action_content'] );
								if ($rule_action_content ['out_from_cell'] == "1") {
									$hourout_arr [] = $customform ['tag_status_id'];
								}
							}
						}
						if ($this->request->get ['client_status'] == "1") {
							$data3 = array ();
							$data3 ['facilities_id'] = $facilities_id;
							$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
							
							foreach ( $customforms as $customform ) {
								
								$this->data ['clientstatuss'] [] = array (
										'tag_status_id' => $customform ['tag_status_id'],
										'name' => $customform ['name'],
										'facilities_id' => $customform ['facilities_id'],
										'display_client' => $customform ['display_client'] 
								);
								
								if ($customform ['type'] == "3") {
									$outcount [] = $customform ['tag_status_id'];
								}
								
								if ($customform ['type'] == "4") {
									$movecount [] = $customform ['tag_status_id'];
								}
								
								$rule_action_content = unserialize ( $customform ['rule_action_content'] );
								if ($rule_action_content ['out_from_cell'] == "1") {
									$hourout_arr [] = $customform ['tag_status_id'];
								}
							}
						}
					}
				} elseif ($this->request->get ['role_call'] != "" && $this->request->get ['role_call'] != null) {
					
					$data3 = array ();
					$data3 ['facilities_id'] = $facilities_id;
					$data3 ['role_call'] = $this->request->get ['role_call'];
					$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
					foreach ( $customforms as $customform ) {
						
						$this->data ['clientstatuss'] [] = array (
								'tag_status_id' => $customform ['tag_status_id'],
								'name' => $customform ['name'],
								'facilities_id' => $customform ['facilities_id'],
								'display_client' => $customform ['display_client'] 
						);
						
						if ($customform ['type'] == "0" || $customform ['type'] == "2") {
							$inclint [] = $customform ['tag_status_id'];
						}
						
						if ($facilities_is_master ['enable_facilityinout'] == '1') {
							if ($customform ['type'] == "3") {
								$inclint [] = $customform ['tag_status_id'];
							}
						} else {
							if ($customform ['type'] == "3") {
								$outcount [] = $customform ['tag_status_id'];
							}
						}
						
						if ($customform ['type'] == "4") {
							$movecount [] = $customform ['tag_status_id'];
						}
						
						$rule_action_content = unserialize ( $customform ['rule_action_content'] );
						if ($rule_action_content ['out_from_cell'] == "1") {
							$hourout_arr [] = $customform ['tag_status_id'];
						}
					}
				} else {
					$data3 = array ();
					$data3 ['facilities_id'] = $facilities_id;
					$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
					
					// var_dump($customforms);die;
					foreach ( $customforms as $customform ) {
						
						$this->data ['clientstatuss'] [] = array (
								'tag_status_id' => $customform ['tag_status_id'],
								'name' => $customform ['name'],
								'facilities_id' => $customform ['facilities_id'],
								'display_client' => $customform ['display_client'] 
						);
						
						if ($customform ['type'] == "0" || $customform ['type'] == "2") {
							$inclint [] = $customform ['tag_status_id'];
						}
						
						if ($facilities_is_master ['enable_facilityinout'] == '1') {
							if ($customform ['type'] == "3") {
								$inclint [] = $customform ['tag_status_id'];
							}
						} else {
							if ($customform ['type'] == "3") {
								$outcount [] = $customform ['tag_status_id'];
							}
						}
						
						if ($customform ['type'] == "4") {
							$movecount [] = $customform ['tag_status_id'];
						}
						
						$rule_action_content = unserialize ( $customform ['rule_action_content'] );
						if ($rule_action_content ['out_from_cell'] == "1") {
							$hourout_arr [] = $customform ['tag_status_id'];
						}
					}
				}
				
				if ($inclint != null && $inclint != "") {
					$inclient = implode ( ",", $inclint );
				}
				
				if ($outcount != null && $outcount != "") {
					$outclient = implode ( ",", $outcount );
				}
				
				if ($movecount != null && $movecount != "") {
					$movecount1 = implode ( ",", $movecount );
				}
				
				// var_dump($movecount1);
				
				if ($inclient != null && $inclient != "") {
					$data3 = array ();
					$data3 = array (
							'status' => 1,
							'discharge' => 1,
							'facility_inout' => '1',
							'gender2' => $this->request->get ['gender'],
							'sort' => 'emp_first_name',
							'is_master' => $is_master_facility,
							'facilities_id' => $facilities_id,
							'rolecalls' => $inclient,
							'is_client_screen' => $is_client_screen,
							'emp_tag_id_all' => $this->request->get ['search_tags'],
							'wait_list' => $this->request->get ['wait_list'],
							'room_id' => $this->request->get ['room_id'],
							'all_record' => '1' 
					);
					
					$this->data ['tags_total'] = $this->model_setting_tags->getTotalTags ( $data3 );
				} else {
					$this->data ['tags_total'] = 0;
				}
				
				$data31 = array (
						'status' => 1,
						'discharge' => 1,
						// 'role_call' =>$this->request->get['role_call'],
						'is_master' => $is_master_facility,
						'gender2' => $this->request->get ['gender'],
						'sort' => 'emp_last_name',
						'facilities_id' => $facilities_id,
						// 'emp_tag_id_all' => $search_tags,
						'is_client_screen' => $is_client_screen,
						'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
						'emp_tag_id_all' => $this->request->get ['search_tags'],
						'wait_list' => $this->request->get ['wait_list'],
						'room_id' => $this->request->get ['room_id'],
						'all_record' => '1' 
				)
				;
				
				$this->data ['tags_total_2'] = $this->model_setting_tags->getTotalTags ( $data31 );
				if ($facility_inout == '2') {
					$data10 = array ();
					$data10 = array (
							'status' => 1,
							'discharge' => 1,
							'facility_inout' => $facility_inout,
							// 'role_call' => $tag_status_id,
							'facilities_id' => $facilities_id,
							'is_master' => $is_master_facility,
							'rolecalls' => $outclient,
							'is_client_screen' => $is_client_screen,
							'emp_tag_id_all' => $this->request->get ['search_tags'],
							'wait_list' => $this->request->get ['wait_list'],
							'room_id' => $this->request->get ['room_id'],
							'all_record' => '1' 
					)
					;
					
					$this->data ['total_out_tags'] = $this->model_setting_tags->getTotalTags ( $data10 );
				} else if ($outclient != null && $outclient != "") {
					$data10 = array ();
					$data10 = array (
							'status' => 1,
							'discharge' => 1,
							'facility_inout' => $facility_inout,
							// 'role_call' => $tag_status_id,
							'facilities_id' => $facilities_id,
							'is_master' => $is_master_facility,
							'rolecalls' => $outclient,
							'is_client_screen' => $is_client_screen,
							'emp_tag_id_all' => $this->request->get ['search_tags'],
							'wait_list' => $this->request->get ['wait_list'],
							'room_id' => $this->request->get ['room_id'],
							'all_record' => '1' 
					)
					;
					
					$this->data ['total_out_tags'] = $this->model_setting_tags->getTotalTags ( $data10 );
				} else {
					$this->data ['total_out_tags'] = 0;
				}
				// if($movecount1 != null && $movecount1 != ""){
				$data312 = array (
						'status' => 1,
						'discharge' => 1,
						'is_movement' => 1,
						'movecount' => $movecount1,
						// 'role_call' =>$this->request->get['role_call'],
						'is_master' => $is_master_facility,
						'gender2' => $this->request->get ['gender'],
						'sort' => 'emp_last_name',
						'facilities_id' => $facilities_id,
						// 'emp_tag_id_all' => $search_tags,
						'is_client_screen' => $is_client_screen,
						'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
						'emp_tag_id_all' => $this->request->get ['search_tags'],
						'wait_list' => $this->request->get ['wait_list'],
						'room_id' => $this->request->get ['room_id'],
						'all_record' => '1' 
				)
				;
				
				$this->data ['movetotal_out_tags'] = $this->model_setting_tags->getTotalTags ( $data312 );
				// }
				
				if ($hourout_arr != null && $hourout_arr != "") {
					$hourout_arr = implode ( ",", $hourout_arr );
					$rolecalls = $hourout_arr;
				}
				
				if ($rolecalls != null && $rolecalls != "") {
					$data3 = array ();
					$data3 = array (
							'status' => 1,
							'discharge' => 1,
							'facility_inout' => '1',
							'gender2' => $this->request->get ['gender'],
							'sort' => 'emp_first_name',
							'is_master' => $is_master_facility,
							'facilities_id' => $facilities_id,
							'rolecalls' => $rolecalls,
							'is_client_screen' => $is_client_screen,
							'emp_tag_id_all' => $this->request->get ['search_tags'],
							'wait_list' => $this->request->get ['wait_list'],
							'room_id' => $this->request->get ['room_id'],
							'all_record' => '1' 
					);
					
					$this->data ['tags_hour_out_total'] = $this->model_setting_tags->getTotalTags ( $data3 );
					$this->data ['hour_out_urlx'] = 'resident';
				} else {
					$this->data ['tags_hour_out_total'] = 0;
				}
			} elseif ($this->request->get ['route'] == 'resident/dailycensus') {
				$this->document->setTitle ( 'Census' );
				$this->data ['heading_title'] = 'Census';
				$this->data ['is_client'] = '2';
				
				$this->data ['total_url'] = $this->url->link ( 'resident/dailycensus', '', 'SSL' );
			} elseif ($this->request->get ['route'] == 'resident/resident/hourout') {
				$this->document->setTitle ( 'Out of the Cell' );
				$this->data ['heading_title'] = 'Out of the Cell';
				$this->data ['is_client'] = '1';
				$this->data ['hourout_url'] = '1';
				$this->data ['total_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
			}
			
			$this->data ['title'] = $this->document->getTitle ();
			
			if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
				$server = $this->config->get ( 'config_ssl' );
			} else {
				$server = $this->config->get ( 'config_url' );
			}
			
			if (isset ( $this->session->data ['error'] ) && ! empty ( $this->session->data ['error'] )) {
				$this->data ['error'] = $this->session->data ['error'];
				
				unset ( $this->session->data ['error'] );
			} else {
				$this->data ['error'] = '';
			}
			
			$this->data ['base'] = $server;
			
			$this->data ['role_call'] = $this->request->get ['role_call'];
			$url1 = "";
			if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
				$url1 .= '&role_call=' . $this->request->get ['role_call'];
			}
			
			$this->data ['movetotal_out_url'] = $this->url->link ( 'resident/resident&client_status=3', '' . $url1, 'SSL' );
			
			if ($this->request->get ['route'] == 'resident/resident/hourout') {
				$this->data ['total_out_url'] = $this->url->link ( 'resident/resident/hourout&client_status=1&type=2', '' . $url1, 'SSL' );
				$this->data ['total_in_url'] = $this->url->link ( 'resident/resident/hourout&client_status=2&type=2', '' . $url1, 'SSL' );
				$this->data ['status_wise_url'] = $this->url->link ( 'resident/resident/StatusCount&type=2', '', 'SSL' );
			}else{
				$this->data ['total_out_url'] = $this->url->link ( 'resident/resident&client_status=1&type=1', '' . $url1, 'SSL' );
				$this->data ['total_in_url'] = $this->url->link ( 'resident/resident&client_status=2&type=1', '' . $url1, 'SSL' );
				$this->data ['status_wise_url'] = $this->url->link ( 'resident/resident/StatusCount&type=1', '', 'SSL' );
			}
			
			/*
			 * if($this->data['data_tags']=="2"){
			 *
			 * $this->data['total_in_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call']."&data_tags=".$this->data['data_tags'], '' . $url1, 'SSL');
			 *
			 * $this->data['total_out_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call'], '' . $url1, 'SSL');
			 *
			 * } else if($this->data['data_tags']=="1"){
			 *
			 * $this->data['total_out_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call']."&data_tags=".$this->data['data_tags'], '' . $url1, 'SSL');
			 *
			 * $this->data['total_in_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call'], '' . $url1, 'SSL');
			 *
			 *
			 * }else{
			 *
			 * $this->data['total_out_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call'], '' . $url1, 'SSL');
			 *
			 * $this->data['total_in_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call'], '' . $url1, 'SSL');
			 *
			 *
			 * }
			 */
			
			// //$this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
			// $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');
			
			// $this->data['total_in_url'] = $this->url->link('resident/resident&role_call='.$this->data['role_call'], '' . $url1, 'SSL');
			
			$this->data ['non_url'] = $this->url->link ( 'resident/resident&gender=3', '' . $url1, 'SSL' );
			
			$this->data ['total_url2'] = $this->url->link ( 'resident/resident', '', 'SSL' );
			
			$this->data ['notes_url'] = $this->url->link ( 'notes/notes/insert', '', 'SSL' );
			
			$this->data ['sticky_note'] = $this->url->link ( 'resident/resident/getstickynote&close=1', '', 'SSL' );
			
			$this->data ['dailycensus'] = $this->url->link ( 'resident/dailycensus', '', 'SSL' );
			$this->data ['logout'] = $this->url->link ( 'common/logout', '', 'SSL' );
			
			$this->data ['task_lists'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/headertasklist', '' . $url2, 'SSL' ) );
			
			$this->data ['task_lists2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatus', '' . $url2, 'SSL' ) );
			
			$this->data ['case_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/cases/dashboard', '', 'SSL' ) );
			
			$this->data ['multiple_action_url'] = $this->url->link ( 'resident/resident/multipleaction', '' . $url2, 'SSL' );
			$this->data ['report_url'] = $this->url->link ( 'resident/resident/allhouroutpopup', '' . $url2, 'SSL' );
			$this->data ['standard_url'] = $this->url->link ( 'notes/acarules', '' . $url2, 'SSL' );
			
			$this->data ['inamte_status_url'] = $this->url->link ( 'resident/inmatestatus', '' . $url2, 'SSL' );
			$this->data ['add_client_url1'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
			// $this->data['add_client_url3'] = str_replace('&amp;', '&',$this->url->link('form/form', '' . '&forms_design_id='.CUSTOME_INTAKEID, 'SSL'));
			
			$this->data ['assignteam'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/assignteam', '', 'SSL' ) );
			
			if ($this->request->get ['route'] == 'resident/formcase/cases') {
				$this->data ['total_url'] = $this->url->link ( 'resident/formcase/cases', '', 'SSL' );
				$this->data ['clienttitle'] = "Case File";
			} else if ($this->request->get ['route'] == 'resident/formcase/viewcase') {
				$this->data ['total_url'] = $this->url->link ( 'resident/formcase/cases', '', 'SSL' );
				$this->data ['clienttitle'] = "Case Detail";
			} else if ($this->request->get ['route'] == 'resident/formcase/addcase') {
				$this->data ['total_url'] = $this->url->link ( 'resident/formcase/addcase', '', 'SSL' );
				$this->data ['clienttitle'] = "Add Case";
			} else if ($this->request->get ['route'] == 'resident/resident/hourout') {
				$this->data ['total_url'] = $this->url->link ( 'resident/resident/hourout', '', 'SSL' );
				// $this->data['clienttitle'] = "Out of the Cell";
			} else if ($this->request->get ['route'] == 'resident/resident/tagforms') {
				$url1 = "";
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$url1 .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				
				$this->data ['total_url'] = $this->url->link ( 'resident/resident/tagforms', '' . $url1, 'SSL' );
				$this->data ['clienttitle'] = "Form Detail";
			} 

			else if ($this->request->get ['route'] == 'resident/resident/tagsmedication') {
				$url1 = "";
				$url1 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url1 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
				$url1 .= '&facilities_id=' . $this->request->get ['facilities_id'];
				$this->data ['total_url'] = $this->url->link ( 'resident/resident/tagsmedication', '' . $url1, 'SSL' );
				$this->data ['clienttitle'] = "Medication";
			} else if ($this->request->get ['route'] == 'notes/addInventory/addInventory') {
				
				$this->data ['clienttitle'] = "Inventory";
				
				$this->data ['total_url'] = $this->url->link ( 'notes/addInventory/addInventory', '' . $url1, 'SSL' );
			} else if ($this->request->get ['route'] == 'notes/addInventory/CheckOutInventory') {
				
				$this->data ['clienttitle'] = "Checkout Inventory";
				
				$this->data ['total_url'] = $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url1, 'SSL' );
			} else if ($this->request->get ['route'] == 'notes/addInventory/CheckInInventory') {
				
				$this->data ['clienttitle'] = "Checkin Inventory";
				
				$this->data ['total_url'] = $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url1, 'SSL' );
			} else if ($this->request->get ['route'] == 'notes/acarules') {
				
				$this->data ['clienttitle'] = "Standards";
				
				$this->data ['total_url'] = $this->url->link ( 'notes/acarules', '' . $url1, 'SSL' );
			}
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/common/headerclient.php';
			
			$this->render ();
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			
			$activity_data2 = array (
					'data' => 'Error in Sites Common headerclient ' .$e->getMessage() 
			);
			$this->model_activity_activity->addActivity ( 'sitesheaderclient', $activity_data2 );
		}
	}
}
?>
