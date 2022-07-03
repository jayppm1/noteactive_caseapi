<?php
class Controllerresidentresident extends Controller {
	private $error = array ();
	public function index() {
		if (! $this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		
		
		unset ( $this->session->data ['show_hidden_info'] );
		unset ( $this->session->data ['case_number'] );
		
		$this->language->load ( 'notes/notes' );
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( 'Clients' );
		
		if ($this->request->get ['search_facilities_id'] > 0) {
			$this->session->data ['search_facilities_id'] = $this->request->get ['search_facilities_id'];
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		if ($this->request->get ['searchall'] == '1') {
			unset ( $this->session->data ['search_facilities_id'] );
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['facilityname'] = $this->customer->getfacility ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facilities_info ['is_discharge_form_enable'] == '1') {
			$this->data ['dis_form'] = '1';
		} else {
			$this->data ['dis_form'] = '2';
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$this->data ['add_role_call_check'] = '1';
		}
		$this->data ['add_role_call'] = $this->request->get ['add_role_call'];
		
		if (($this->request->get ['searchtag'] == '1')) {
			$url = "";
			
			if ($this->request->post ['search_tags'] != null && $this->request->post ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->post ['search_tags'];
			}
			
			if ($this->request->post ['room_id'] != null && $this->request->post ['room_id'] != "") {
				$url .= '&room_id=' . $this->request->post ['room_id'];
			}
			
			if ($this->request->post ['wait_list'] != null && $this->request->post ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->post ['wait_list'];
			}
			if ($this->request->post ['search_tags_tag_id'] != null && $this->request->post ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->post ['search_tags_tag_id'];
			}
			if ($this->request->post ['all_client'] != null && $this->request->post ['all_client'] != "") {
				$url .= '&all_client=' . $this->request->post ['all_client'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if (($this->request->get ['searchtag'] == '2')) {
			$url = "";
			if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->get ['search_tags'];
			}
			if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->get ['wait_list'];
			}
			if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$this->data ['search_tags'] = $this->request->get ['search_tags'];
		}
		
		if ($this->request->get ['room_id'] != null && $this->request->get ['room_id'] != "") {
			$this->data ['room_id'] = $this->request->get ['room_id'];
		}
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$this->data ['wait_list'] = $this->request->get ['wait_list'];
		}
		if ($this->request->get ['all_client'] != null && $this->request->get ['all_client'] != "") {
			$this->data ['all_client'] = $this->request->get ['all_client'];
		}
		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$this->data ['search_tags_tag_id'] = $this->request->get ['search_tags_tag_id'];
			$search_tags = '';
		} else {
			$search_tags = $this->request->get ['search_tags'];
		}
		
		$url4 = "";
		$url3 = "";
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$url4 .= '&search_tags=' . $this->request->get ['search_tags'];
			$url3 .= '&search_tags=' . $this->request->get ['search_tags'];
		}
		if ($this->request->get ['room_id'] != null && $this->request->get ['room_id'] != "") {
			$url4 .= '&room_id=' . $this->request->get ['room_id'];
			$url3 .= '&room_id=' . $this->request->get ['room_id'];
		}
		if ($this->request->get ['all_client'] != null && $this->request->get ['all_client'] != "") {
			$url4 .= '&all_client=' . $this->request->get ['all_client'];
			$url3 .= '&all_client=' . $this->request->get ['all_client'];
		}
		
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url4 .= '&wait_list=' . $this->request->get ['wait_list'];
			$url3 .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url4 .= '&wait_list=' . $this->request->get ['wait_list'];
			$url3 .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$url4 .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
			$url3 .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$url4 .= '&gender=' . $this->request->get ['gender'];
			$url3 .= '&gender=' . $this->request->get ['gender'];
		}
		
		if ($this->request->get ['add_role_call'] != null && $this->request->get ['add_role_call'] != "") {
			$url4 .= '&add_role_call=' . $this->request->get ['add_role_call'];
			$url3 .= '&add_role_call=' . $this->request->get ['add_role_call'];
		}
		if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
			$url4 .= '&role_call=' . $this->request->get ['role_call'];
			$url3 .= '&role_call=' . $this->request->get ['role_call'];
		}
		if ($this->request->get ['client_status'] != null && $this->request->get ['client_status'] != "") {
			$url4 .= '&client_status=' . $this->request->get ['client_status'];
			$url3 .= '&client_status=' . $this->request->get ['client_status'];
		}
		if ($this->request->get ['sort'] != null && $this->request->get ['sort'] != "") {
			$url4 .= '&sort=' . $this->request->get ['sort'];
		}
		if ($this->request->get ['order'] != null && $this->request->get ['order'] != "") {
			$url4 .= '&order=' . $this->request->get ['order'];
		}
		
		// // $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
		// $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');
		
		$this->data ['total_in_url'] = $this->url->link ( 'resident/resident&in=1', '' . $url1, 'SSL' );
		$this->data ['total_out_url'] = $this->url->link ( 'resident/resident&out=1', '' . $url1, 'SSL' );
		$this->data ['non_url'] = $this->url->link ( 'resident/resident&gender=3', '' . $url1, 'SSL' );
		
		
		$this->data ['allclient_url'] = $this->url->link ( 'resident/resident/allClientSelect', '', 'SSL' );
		
		$this->data ['total_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
		
		$this->data ['notes_url'] = $this->url->link ( 'notes/notes/insert', '', 'SSL' );
		
		$this->data ['sticky_note'] = $this->url->link ( 'resident/resident/getstickynote&close=1', '', 'SSL' );
		
		$this->data ['dailycensus'] = $this->url->link ( 'resident/dailycensus', '', 'SSL' );
		
		$this->data ['clientfile'] = $this->url->link ( 'resident/resident/clientfile', '', 'SSL' );
		
		$this->data ['logout'] = $this->url->link ( 'common/logout', '', 'SSL' );
		
		$this->data ['resident_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
		
		$this->data ['task_lists'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/headertasklist', '' . $url1, 'SSL' ) );
		
		$this->data ['task_lists2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatus', '' . $url1, 'SSL' ) );
		
		$this->data ['ajaxresidenturl'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/ajaxresident', '' . $url4, 'SSL' ) );
		
		$this->data ['case_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/cases/dashboard', '', 'SSL' ) );
		
		$this->data ['add_client_url1'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
		
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		// $this->data['add_client_url3'] = str_replace('&amp;',
		// '&',$this->url->link('form/form', '' .
		// '&forms_design_id='.CUSTOME_INTAKEID, 'SSL'));
		
		$default_facility_id = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
		
		$this->data ['roll_call_sign_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		
		$this->data ['assignteam'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/assignteam', '', 'SSL' ) );
		
		if ($this->request->get ['sort'] != null && $this->request->get ['sort'] != "") {
			$this->data ['sort'] = $this->request->get ['sort'];
			$sort = $this->request->get ['sort'];
			
		} else {
			$sort = 'emp_last_name';
		}
		if ($this->request->get ['order'] != null && $this->request->get ['order'] != "") {
			$this->data ['order'] = $this->request->get ['order'];
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		$this->data['sorts'] = array();

		$this->data['sorts'][] = array(
			'text'  => 'Default/Lastname',
			'value' => 'emp_last_name',
			'href'  => $this->url->link('resident/resident', '' . '&sort=emp_last_name&order=ASC' . $url3)
		);

		$this->data['sorts'][] = array(
			'text'  => 'Firstname',
			'value' => 'emp_first_name',
			'href'  => $this->url->link('resident/resident', '' . '&sort=emp_first_name&order=ASC' . $url3)
		);


		$this->data['sorts'][] = array(
			'text'  => 'Location',
			'value' => 'l.location_name',
			'href'  => $this->url->link('resident/resident', '' . '&sort=l.location_name&order=ASC' . $url3)
		);

		$this->data['sorts'][] = array(
			'text'  => 'Updated Date',
			'value' => 'modify_date',
			'href'  => $this->url->link('resident/resident', '' . '&sort=modify_date&order=ASC' . $url3)
		);

		$this->data['sorts'][] = array(
			'text'  => 'Intake Date',
			'value' => 'date_added',
			'href'  => $this->url->link('resident/resident', '' . '&sort=date_added&order=ASC' . $url3)
		);

		
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'setting/image' );
		
		$this->load->model ( 'notes/clientstatus' );
		
		$ddss = array ();
		if ($facilities_info ['client_facilities_ids'] != null && $facilities_info ['client_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $facilities_info ['client_facilities_ids'];
			
			$ddss [] = $this->customer->getId ();
			$sssssdd = implode ( ",", $ddss );
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
			$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$config_admin_limit = "39";
		
		// var_dump($config_admin_limit);
		$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$this->data ['is_external_status_facility'] = $facilities_is_master ['enable_facilityinout'];
		
		if ($facilities_is_master ['enable_facilityinout'] != '1') {
			$is_client_screen = '0';
		} else {
			$is_client_screen = '1';
			$facility_inout = '2';
		}
		
		if ($facilities_is_master ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facilities_is_master ['is_master_facility'];
		}
		
		if ($this->request->get ['all_client'] != null && $this->request->get ['all_client'] != "") {
			$discharge = '';
		}else{
			$discharge = '1';
		}
		
		$data3 = array ();
		$data3 = array (
				'sort' => $sort,
				'order' => $order,
				'status' => 1,
				'discharge' => $discharge,
				// 'role_call' => '1',
				'is_master' => $is_master_facility,
				// 'gender2' => $this->request->get['gender'],
				//'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_client_screen' => $is_client_screen,
				// 'emp_tag_id_2' => $this->request->get['search_tags'],
				'wait_list' => $this->request->get ['wait_list'],
				'client_status' => $this->request->get ['client_status'],
				'all_record' => '1' 
		);
		
		$this->data ['tags_total'] = $this->model_setting_tags->getTotalTags ( $data3 );
		
		$data31333 = array ();
		$data31333 = array (
				'sort' => $sort,
				'order' => $order,
				'status' => 1,
				'discharge' => $discharge,
				// 'role_call' => '1',
				'gender2' => $this->request->get ['gender'],
				//'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_master' => $is_master_facility,
				'is_client_screen' => $is_client_screen,
				'emp_tag_id_all' => $search_tags,
				'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
				'wait_list' => $this->request->get ['wait_list'],
				'client_status' => $this->request->get ['client_status'],
				'all_record' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
		);
		
		$tags_total_2 = $this->model_setting_tags->getTotalTags ( $data31333 );
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$data31 = array ();
		
		/*
		 * $rolecall="";
		 *
		 * if($this->request->get['role_call']!=""||$this->request->get['role_call']!=null){
		 *
		 * $rolecall=$this->request->get['role_call'];
		 *
		 * if($this->request->get['data_tags']=='1' || $this->request->get['data_tags']=='2'){
		 *
		 * $data_tags="1";
		 * }else{
		 * $data_tags="";
		 * }
		 *
		 * }else{
		 *
		 * $rolecall='';
		 *
		 * }
		 */
		$facility_inout = "";
		if ($this->request->get ['client_status'] == "1" || $this->request->get ['client_status'] == "2") {
			$inclint = array (
					0 
			);
		} else {
			$inclint = array ();
		}
		$outcount = array ();
		$movecount = array ();
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
					}
					
					$facility_inout = '1';
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
					}
					
					$facility_inout = '1';
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
						
						if ($facilities_is_master ['enable_facilityinout'] == '1') {
							if ($customform ['type'] == "3") {
								$inclint [] = $customform ['tag_status_id'];
							}
						} else {
							if ($customform ['type'] == "3") {
								$outcount [] = $customform ['tag_status_id'];
							}
						}
					}
					
					if ($facilities_is_master ['enable_facilityinout'] == '1') {
						$facility_inout = '2';
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
				
				
			}
		}
		
		if ($inclint != null && $inclint != "") {
			$inclient = implode ( ",", $inclint );
			$rolecalls = $inclient;
		}
		
		if ($outcount != null && $outcount != "") {
			$outclient = implode ( ",", $outcount );
			$rolecalls = $outclient;
		}
		
		$is_movement = 0;
		if ($movecount != null && $movecount != "") {
			
			$movecount = implode ( ",", $movecount );
			$is_movement = 1;
			//$movecount = $movecount;
		}
		
		
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
		
		$this->load->model ( 'setting/tags' );
		
		$tag_statuses = $this->model_setting_tags->getAllStatus ();
		
		foreach ( $tag_statuses as $tag_staus ) {
			
			$tags_ids [] = $tag_staus ['facility_type'];
		}
		
		$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if (in_array ( $facilities_id, $tags_ids )) {
			
			$is_external_status_facility = '1';
		} else {
			
			$is_external_status_facility = '0';
		}
		
		if ($facility_info ['enable_facilityinout'] != '1') {
			
			$is_client_screen = '0';
		} else {
			
			$is_client_screen = '1';
		}
		
		if (($this->request->get ['search_tags'] != "" && $this->request->get ['search_tags'] != null) || ($this->request->get ['room_id'] != "" && $this->request->get ['room_id'] != null)) {
			
			$data31 = array (
					'sort' => $sort,
					'order' => $order,
					'status' => 1,
					'discharge' => $discharge,
					'emp_tag_id_all' => $this->request->get ['search_tags'],
					// 'role_call' => $rolecall,
					'rolecalls' => $rolecalls,
					'is_movement' => $is_movement,
					'movecount' => $movecount,
					'facility_inout' => $facility_inout,
					'gender2' => $this->request->get ['gender'],
					//'sort' => 'emp_last_name',
					'is_master' => $is_master_facility,
					'room_id' => $this->request->get ['room_id'],
					'facilities_id' => $facilities_id,
					'is_client_screen' => $is_client_screen,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit,
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1' 
			);
		} else if ($this->request->get ['add_role_call'] == '1') {
			$data31 = array (
					'sort' => $sort,
					'order' => $order,
					'status' => 1,
					'discharge' => $discharge,
					'data_tags' => $data_tags,
					// 'role_call' => $rolecall,
					'rolecalls' => $rolecalls,
					'is_movement' => $is_movement,
					'movecount' => $movecount,
					'facility_inout' => $facility_inout,
					'gender2' => $this->request->get ['gender'],
					//'sort' => 'emp_last_name',
					'is_master' => $is_master_facility,
					'facilities_id' => $facilities_id,
					'is_client_screen' => $is_client_screen,
					'emp_tag_id_all' => $search_tags,
					'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1' 
			);
		} else {
			$data31 = array (
					'sort' => $sort,
					'order' => $order,
					'status' => 1,
					'discharge' => $discharge,
					// 'role_call' =>$rolecall,
					'rolecalls' => $rolecalls,
					'is_movement' => $is_movement,
					'movecount' => $movecount,
					'data_tags' => $data_tags,
					'is_master' => $is_master_facility,
					'facility_inout' => $facility_inout,
					'gender2' => $this->request->get ['gender'],
					//'sort' => 'emp_last_name',
					'is_client_screen' => $is_client_screen,
					'facilities_id' => $facilities_id,
					'emp_tag_id_all' => $search_tags,
					'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
		}
		
		//var_dump($data31);
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		
		// var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		$customers = unserialize ( $customer_info ['setting_data'] );
		
		$client_view_options2 = $client_info ["client_view_options"];
		$client_view_options_details = $client_info ["client_details_view_options"];
		
		$this->data ['customers'] = $customers;
		
		//echo '<pre>'; print_r($customers['discharge']); echo '</pre>'; die;
		
		// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
		$this->data ['show_client_image'] = $client_info ["show_client_image"];
		$this->data ['show_form_tag'] = $client_info ["show_form_tag"];
		$this->data ['show_task'] = $client_info ["show_task"];
		$this->data ['show_case'] = $client_info ["show_case"];
		
		
		$tags = $this->model_setting_tags->getTags ( $data31 );
		
		
		
		if ($is_external_status_facility == '1' && $facility_info ['enable_facilityinout'] == '1') {
			
			$this->data ['is_external_status_facility'] = $is_external_status_facility;
			
			$this->data ['default_facility_id'] = $facilities_id;
		}
		
		$this->data ['total_tagsco'] = count ( $tags );
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$currentdate = date ( 'd-m-Y' );
		
		$this->load->model ( 'facilities/facilities' );
		
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
				$client_view_options_flag = 1;
			} else {
				$client_view_options_flag = 0;
			}
			
			if ($client_view_options_details2 != "" && $client_view_options_details2 != null) {
				$client_details_view_flag = 1;
			} else {
				$client_details_view_flag = 0;
			}


			$classification_names = array();
			$classification_ids = array();
			if ($tag ['tags_id'] != '0' && $tag ['tags_id'] != null) {
				
				// $status_value = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );


				if($tag ['classification_id']!="" && $tag ['classification_id']!=null){

					$tag_classification_id=$tag ['classification_id'];
				
					$tag_classification_ids=explode(",",$tag_classification_id);

					foreach($tag_classification_ids as $classification_id){

						$classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
						if($classification_value['classification_name'] != null && $classification_value['classification_name'] != ""){
						$classification_ids [] =$classification_value['tag_classification_id'];

						$classification_names [] =$classification_value['classification_name'];
						}
					}

					$classification_names = array_unique($classification_names);
				}
				
			}

			$hourout = 0;
			$inPercent = 0;
			$outfromsell = 0;
			
			$rule_action_content = unserialize($tag['rule_action_content']);
			if($rule_action_content['out_from_cell']){
				$houroutdata = array();

				$houroutdata['tags_id'] = $tag ['tags_id'];
				$houroutdata['currentdate'] = date('Y-m-d');
				$houroutdata['rules_operation'] = $customers ['rules_operation'];
				$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
				$houroutdata['rules_end_time'] = $customers ['rules_end_time'];

				$outcelltime = $this->model_setting_tags->getOutToCellTime ( $houroutdata);

				$totaltime = '';
				$totaltime = $outcelltime['totaltime'];

				if($tag ['notes_id'] > 0){
					$noesData = $this->model_notes_notes->getnotes($tag ['notes_id']);
					$timezone_name = $this->customer->isTimezone ();
					$timeZone = date_default_timezone_set ( $timezone_name );
					$dataprogress = array();
					$dataprogress['date_a'] = date('Y-m-d H:i:s'); 
					$dataprogress['date_added'] = $noesData ['date_added'];
					$dataprogress['duration_type'] = $customers ['duration_type'];
					$dataprogress['out_the_sell'] = $customers ['out_the_sell'];
					$dataprogress['totaltime'] = $totaltime;
					$response = $this->model_setting_tags->getHourOutProgress ( $dataprogress );
					$hourout = $response['hourout'];
					$percent = $response['inPercent'];
					if($customers['orange_progress_percentage'] !='' && ($percent>=$customers['orange_progress_percentage'])){
							$outfromsell=1;
					}
					
				}
			}
			
			$this->data ['tags'] [] = array (
					'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
					'facility' => $tag ['facility'],
					'discharge' => $tag ['discharge'],
					
					'out_the_sell' => $customers['out_the_sell'],
					'outfromsell' => $outfromsell,
					'hourout' => $hourout,
					'percent' => $percent,
					'red_color' => $customers['red_color'],
					'red_progress_percentage' => $customers['red_progress_percentage'],
					'orange_color' => $customers['orange_color'],
					'orange_progress_percentage' => $customers['orange_progress_percentage'],
					'green_color' => $customers['green_color'],
					'green_progress_percentage' => $customers['green_progress_percentage'],
					
					'name2' => nl2br ( $client_view_options ),
					'client_details_view_flag' => $client_details_view_flag,
					'client_details' => nl2br ( $client_view_options_details2 ),
					'client_view_flag' => $client_view_options_flag,
					'facilities_id' => $tag ['facilities_id'],
					'is_movement' => $tag ['is_movement'],
					'emp_first_name' => $tag ['emp_first_name'],
					'medication_inout' => $tag ['medication_inout'],
					'status_type' => $tag ['status_type'],
					'is_facility' => $tag ['is_facility'],
					'facility_type' => $tag ['facility_type'],
					'facility_move_id' => $tag ['facility_move_id'],
					'facility_inout' => $tag ['facility_inout'],
					
					'tag_status_id' => $tag ['tag_status_id'],
					
					'client_status_type' => $tag ['type'],
					
					'room' => $tag ['location_name'],
					'emp_extid' => $tag ['emp_extid'],
					'ssn' => $tag ['ssn'],
					'ccn' => $tag ['ccn'],
					'is_medical' => $is_medical,
					'client_status_image' => $tag ['image'],
					'client_status_name' => $tag ['name'],
					'tag_classification_id' => $tag ['classification_id'],
					'classification_name' => $classification_names,
					'client_status_color' => $tag ['color_code'],
					'client_clssification_color' => $tag ['color_code'],
					'location_address' => $tag ['location_address'],
					'first_initial' => $tag ['emp_last_name'][0],
					'emp_last_name' => $tag ['emp_last_name'],
					'emp_tag_id' => $tag ['emp_tag_id'],
					'tags_id' => $tag ['tags_id'],
					'age' => $tag ['age'],
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'gender' => $tag['customlistvalues_name'],
					'upload_file' => $tag['enroll_image'],
					'upload_file_thumb' => $tag ['enroll_image'],
					'upload_file_thumb_1' => $tag ['enroll_image'],
					'check_img' => $check_img,
					'privacy' => $tag ['privacy'],
					'role_call' => $tag ['role_call'],
					'role_callname' => $tag['name'],
					'stickynote' => $tag ['stickynote'],
					// 'role_call' => $role_call,
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'tasksinfo' => $tasksinfo1,
					'taskTotal' => $taskTotal,
					'recentnote' => $lastnotesinfo [0] ['notes_description'],
					'recenttasks' => $recenttasksinfos ['description'],
					'ndate_added' => $ndate_added,
					'client_medicine' => $client_medicine,
					'tagstatus_info' => $status,
					'screenig_url' => $screenig_url,
					'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
			);
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
					// 'form_href' =>
					// $this->url->link('resident/resident/tagform', '' .
					// '&forms_design_id='.$custom_form['forms_id'], 'SSL'),
					'form_href' => $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
			);
		}
		
		$this->load->model ( 'notes/clientstatus' );
		$data3 = array ();
		$data3 ['facilities_id'] = $facilities_id;
		$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
		
		$this->data ['clientstatuss'] = array ();
		foreach ( $customforms as $customform ) {
			
			$this->data ['clientstatuss'] [] = array (
					'tag_status_id' => $customform ['tag_status_id'],
					'name' => $customform ['name'],
					'facilities_id' => $customform ['facilities_id'],
					'display_client' => $customform ['display_client'] 
			);
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		$this->data ['close'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		$this->data ['tag_forms'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url2, 'SSL' ) );
		
		$this->data ['clientstatus_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatus', '' . $url2, 'SSL' ) );
		
		$this->data ['multiple_action_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/multipleaction', '' . $url2, 'SSL' ) );
		
		$this->data ['add_client_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '' . '&addclient=1', 'SSL' ) );
		// $this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . '&addclient=1&forms_design_id=' . CUSTOME_I_INTAKEID, 'SSL'));
		
		// var_dump($this->data['add_client_url']);
		
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '', 'SSL' ) );
		
		$this->data ['activenote_url'] = $this->url->link ( 'resident/resident/activenote', '', 'SSL' );
		
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if (($this->request->post ['all_roll_call'] == '1')) {
			
			$url2 = "";
			if ($this->request->post ['all_roll_call'] != null && $this->request->post ['all_roll_call'] != "") {
				$url2 .= '&all_roll_call=' . $this->request->post ['all_roll_call'];
			}
			
			$this->session->data ['role_calls'] = $this->request->post ['role_call'];
			
			$this->session->data ['success2'] = 'Head Ciount updated Successfully! ';
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&allrolecallsign=1';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
			}
		}
		
		if (($this->request->post ['all_roll_call1'] == '1')) {
			
			$url2 = "";
			
			if ($this->request->post ['all_roll_call1'] != null && $this->request->post ['all_roll_call1'] != "") {
				$url2 .= '&all_roll_call1=' . $this->request->post ['all_roll_call1'];
			}
			
			$this->session->data ['tagsids'] = $this->request->post ['tagsids'];
			$this->session->data ['role_calls'] = $this->request->post ['role_call'];
			
			$this->session->data ['success2'] = 'Head Count updated Successfully! ';
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&allrolecallsign=1';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
			}
		}
		
		if (isset ( $this->request->post ['all_roll_call'] )) {
			$this->data ['all_roll_call'] = $this->request->post ['all_roll_call'];
		} else {
			$this->data ['all_roll_call'] = '';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		$url = "";
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$url .= '&search_tags=' . $this->request->get ['search_tags'];
		}
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$url .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$url .= '&gender=' . $this->request->get ['gender'];
		}
		
		if ($this->request->get ['add_role_call'] != null && $this->request->get ['add_role_call'] != "") {
			$url .= '&add_role_call=' . $this->request->get ['add_role_call'];
		}
		if ($this->request->get ['all_client'] != null && $this->request->get ['all_client'] != "") {
			$url .= '&all_client=' . $this->request->get ['all_client'];
		}
		
		$this->data ['tags_total_2'] = $tags_total_2;
		// var_dump($url);
		
		// var_dump($tags_total_2);
		if ($this->request->get ['add_role_call'] != '1') {
			$pagination = new Pagination ();
			$pagination->total = $tags_total_2;
			$pagination->page = $page;
			$pagination->limit = $config_admin_limit;
			$pagination->text = $this->language->get ( 'text_pagination' );
			$pagination->url = $this->url->link ( 'resident/resident', 'page={page}' . $url, 'SSL' );
			
			$this->data ['pagination'] = $pagination->render ();
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/resident.php';
		$this->children = array (
				'common/headerclient',
				'common/footerclient' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function dischargeclients() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			$this->session->data ['success2'] = 'Clients released successfully!';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		$url2 = "";
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'resident/resident/dischargeclients', '' . $url2, 'SSL' );
		
		$tags_array = explode ( ",", $this->request->get ['tags_ids'] );
		
		foreach ( $tags_array as $tags_id ) {
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'facilities/facilities' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			
			$facility_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
			
			$this->data ['tags'] [] = array (
					'name' => $tag_info ['emp_last_name'] . ' ' . $tag_info ['emp_first_name'],
					'tags_id' => $tag_info ['tags_id'],
					'emp_tag_id2' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'],
					'emp_tag_id' => $tag_info ['emp_tag_id'],
					'emp_first_name' => $tag_info ['emp_first_name'],
					'emp_middle_name' => $tag_info ['emp_middle_name'],
					'emp_last_name' => $tag_info ['emp_last_name'],
					'facility' => $facility_info ['facility'] 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&clienttype=3&page=resident';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			$url2 .= '&clienttype=3';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/clientsinsignature', '' . $url2, 'SSL' ) );
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/dischargeclients.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function ajaxresident() {
		
		if (! $this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		unset ( $this->session->data ['show_hidden_info'] );
		
		$this->language->load ( 'notes/notes' );
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( 'Clients' );
		
		if ($this->request->get ['search_facilities_id'] > 0) {
			$this->session->data ['search_facilities_id'] = $this->request->get ['search_facilities_id'];
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		if ($this->request->get ['searchall'] == '1') {
			unset ( $this->session->data ['search_facilities_id'] );
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['facilityname'] = $this->customer->getfacility ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facilities_info ['is_discharge_form_enable'] == '1') {
			$this->data ['dis_form'] = '1';
		} else {
			$this->data ['dis_form'] = '2';
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$this->data ['add_role_call_check'] = '1';
		}
		$this->data ['add_role_call'] = $this->request->get ['add_role_call'];
		
		if (($this->request->get ['searchtag'] == '1')) {
			$url = "";
			if ($this->request->post ['search_tags'] != null && $this->request->post ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->post ['search_tags'];
			}
			if ($this->request->post ['wait_list'] != null && $this->request->post ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->post ['wait_list'];
			}
			if ($this->request->post ['search_tags_tag_id'] != null && $this->request->post ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->post ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if (($this->request->get ['searchtag'] == '2')) {
			$url = "";
			if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->get ['search_tags'];
			}
			if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->get ['wait_list'];
			}
			if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$this->data ['search_tags'] = $this->request->get ['search_tags'];
		}
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$this->data ['wait_list'] = $this->request->get ['wait_list'];
		}
		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$this->data ['search_tags_tag_id'] = $this->request->get ['search_tags_tag_id'];
			$search_tags = '';
		} else {
			$search_tags = $this->request->get ['search_tags'];
		}
		
		// // $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
		// $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'setting/image' );
		
		$this->load->model ( 'notes/clientstatus' );
		
		$ddss = array ();
		if ($facilities_info ['client_facilities_ids'] != null && $facilities_info ['client_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $facilities_info ['client_facilities_ids'];
			
			$ddss [] = $this->customer->getId ();
			$sssssdd = implode ( ",", $ddss );
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
			$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$config_admin_limit = "39";
		
		// var_dump($config_admin_limit);
		$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_is_master ['enable_facilityinout'] != '1') {
			$is_client_screen = '0';
		} else {
			$is_client_screen = '1';
			$facility_inout = '2';
		}
		
		if ($facilities_is_master ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facilities_is_master ['is_master_facility'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$data31 = array ();
		
		if ($this->request->get ['client_status'] == "1" || $this->request->get ['client_status'] == "2") {
			$inclint = array (
					0 
			);
		} else {
			$inclint = array ();
		}
		$outcount = array ();
		$movecount = array ();
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
					}
					
					$facility_inout = '1';
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
						
						if ($facilities_is_master ['enable_facilityinout'] == '1') {
							if ($customform ['type'] == "3") {
								$inclint [] = $customform ['tag_status_id'];
							}
						} else {
							if ($customform ['type'] == "3") {
								$outcount [] = $customform ['tag_status_id'];
							}
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
			}
		}
		
		if ($inclint != null && $inclint != "") {
			$inclient = implode ( ",", $inclint );
			$rolecalls = $inclient;
		}
		
		if ($outcount != null && $outcount != "") {
			$outclient = implode ( ",", $outcount );
			$rolecalls = $outclient;
		}
		
		if ($movecount != null && $movecount != "") {
			$movecount = implode ( ",", $movecount );
			$rolecalls = $movecount;
		}
		
		if ($this->request->get ['sort'] != null && $this->request->get ['sort'] != "") {
			$this->data ['sort'] = $this->request->get ['sort'];
			$sort = $this->request->get ['sort'];
			
		} else {
			$sort = 'emp_last_name';
		}
		if ($this->request->get ['order'] != null && $this->request->get ['order'] != "") {
			$this->data ['order'] = $this->request->get ['order'];
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		/*
		 * $data31 = array(
		 * 'status' => 1,
		 * 'discharge' => 1,
		 * //'role_call' =>$rolecall,
		 * 'rolecalls' => $rolecalls,
		 * 'data_tags' => $data_tags,
		 * 'is_master'=> $is_master_facility,
		 * 'gender2' => $this->request->get['gender'],
		 * 'sort' => 'emp_last_name',
		 * 'facilities_id' => $facilities_id,
		 * 'emp_tag_id_all' => $search_tags,
		 * 'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
		 * 'wait_list' => $this->request->get['wait_list'],
		 * 'all_record' => '1',
		 * 'start' => ($page - 1) * $config_admin_limit,
		 * 'limit' => $config_admin_limit
		 * );
		*/
		
		if ($this->request->get ['all_client'] != null && $this->request->get ['all_client'] != "") {
			$discharge = '';
		}else{
			$discharge = '1';
		}
		
		if ($this->request->get ['search_tags'] != "" && $this->request->get ['search_tags'] != null) {
			
			$data31 = array (
					'sort' => $sort,
					'order' => $order,
					'status' => 1,
					'discharge' => $discharge,
					'emp_tag_id_all' => $this->request->get ['search_tags'],
					// 'role_call' => $rolecall,
					'rolecalls' => $rolecalls,
					'gender2' => $this->request->get ['gender'],
					//'sort' => 'emp_last_name',
					'is_master' => $is_master_facility,
					'is_client_screen' => $is_client_screen,
					'facilities_id' => $facilities_id,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit,
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1' 
			);
		} else {
			
			$data31 = array (
					'sort' => $sort,
					'order' => $order,
					'status' => 1,
					'discharge' => $discharge,
					// 'role_call' =>$rolecall,
					'rolecalls' => $rolecalls,
					'emp_tag_id_all' => $data_tags,
					'is_master' => $is_master_facility,
					'gender2' => $this->request->get ['gender'],
					'client_status' => $this->request->get ['client_status'],
					//'sort' => 'emp_last_name',
					'facilities_id' => $facilities_id,
					'is_client_screen' => $is_client_screen,
					'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
					'wait_list' => $this->request->get ['wait_list'],
					'all_record' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		
		// var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		$customers = unserialize ( $customer_info ['setting_data'] );
		
		$this->data ['customers'] = $customers;
		
		$client_view_options2 = $client_info ["client_view_options"];
		$client_view_options_details = $client_info ["client_details_view_options"];
		
		// echo '<pre>'; print_r($client_info); echo '</pre>'; die;
		
		// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
		
		// $tags = $this->model_setting_tags->getTags($data31);
		
		/*
		 * if($this->request->get['search_tags']!=null && $this->request->get['search_tags']!=""){
		 *
		 * $tags = $this->model_setting_tags->getSearchTags($data31);
		 *
		 * }else{
		 */
		$tags = $this->model_setting_tags->getTags ( $data31 );
		
		/* } */
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$currentdate = date ( 'd-m-Y' );
		
		$this->load->model ( 'facilities/facilities' );
		
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
				$client_view_options_flag = 1;
			} else {
				$client_view_options_flag = 0;
			}
			
			if ($client_view_options_details2 != "" && $client_view_options_details2 != null) {
				$client_details_view_flag = 1;
			} else {
				$client_details_view_flag = 0;
			}

			$classification_names = array();
			$classification_ids = array();
			if ($tag ['tags_id'] != '0' && $tag ['tags_id'] != null) {
				
				// $status_value = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );


				if($tag ['classification_id']!="" && $tag ['classification_id']!=null){

					$tag_classification_id=$tag ['classification_id'];
				
					$tag_classification_ids=explode(",",$tag_classification_id);

					foreach($tag_classification_ids as $classification_id){

						$classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
						if($classification_value['classification_name'] != null && $classification_value['classification_name'] != ""){
						$classification_ids [] =$classification_value['tag_classification_id'];

						$classification_names [] =$classification_value['classification_name'];
						}
					}

					$classification_names = array_unique($classification_names);
				}
				
			}
			
			$rule_action_content = unserialize($tag['rule_action_content']);
			if($rule_action_content['out_from_cell']){
				$houroutdata = array();

				$houroutdata['tags_id'] = $tag ['tags_id'];
				$houroutdata['currentdate'] = date('Y-m-d');
				$houroutdata['rules_operation'] = $customers ['rules_operation'];
				$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
				$houroutdata['rules_end_time'] = $customers ['rules_end_time'];

				$outcelltime = $this->model_setting_tags->getOutToCellTime ( $houroutdata);

				$hourout='';

				$totaltime = '';
				$totaltime = $outcelltime['totaltime'];

				if($tag ['notes_id'] > 0){
					$noesData = $this->model_notes_notes->getnotes($tag ['notes_id']);
					$timezone_name = $this->customer->isTimezone ();
					$timeZone = date_default_timezone_set ( $timezone_name );
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
			
			$json [] = array (
					'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
					'facility' => $tag ['facility'],
					'discharge' => $tag ['discharge'],
					
					'out_the_sell' => $customers['out_the_sell'],
					'outfromsell' => $outfromsell,
					'hourout' => $hourout,
					'percent' => $percent,
					'red_color' => $customers['red_color'],
					'red_progress_percentage' => $customers['red_progress_percentage'],
					'orange_color' => $customers['orange_color'],
					'orange_progress_percentage' => $customers['orange_progress_percentage'],
					'green_color' => $customers['green_color'],
					'green_progress_percentage' => $customers['green_progress_percentage'],
					
					'name2' => nl2br ( $client_view_options ),
					'client_details_view_flag' => $client_details_view_flag,
					'client_details' => nl2br ( $client_view_options_details2 ),
					'client_view_flag' => $client_view_options_flag,
					'facilities_id' => $tag ['facilities_id'],
					'is_movement' => $tag ['is_movement'],
					'emp_first_name' => $tag ['emp_first_name'],
					'medication_inout' => $tag ['medication_inout'],
					'status_type' => $tag ['status_type'],
					'is_facility' => $tag ['is_facility'],
					'facility_type' => $tag ['facility_type'],
					'facility_move_id' => $tag ['facility_move_id'],
					'facility_inout' => $tag ['facility_inout'],
					
					'tag_status_id' => $tag ['tag_status_id'],
					
					'client_status_type' => $tag ['type'],
					
					'room' => $tag ['location_name'],
					'emp_extid' => $tag ['emp_extid'],
					'ssn' => $tag ['ssn'],
					'ccn' => $tag ['ccn'],
					'is_medical' => $is_medical,
					'client_status_image' => $tag ['image'],
					'client_status_name' => $tag ['name'],
					'tag_classification_id' => $tag ['classification_id'],
					'classification_name' => $classification_names,
					'client_status_color' => $tag ['color_code'],
					//'client_clssification_color' => $tag ['color_code'],
					'location_address' => $tag ['location_address'],
					'first_initial' => $tag ['emp_last_name'][0],
					'emp_last_name' => $tag ['emp_last_name'],
					'emp_tag_id' => $tag ['emp_tag_id'],
					'tags_id' => $tag ['tags_id'],
					'age' => $tag ['age'],
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'gender' => $tag['customlistvalues_name'],
					'upload_file' => $tag['enroll_image'],
					'upload_file_thumb' => $tag ['enroll_image'],
					'upload_file_thumb_1' => $tag ['enroll_image'],
					'check_img' => $check_img,
					'privacy' => $tag ['privacy'],
					'role_call' => $tag ['role_call'],
					'role_callname' => $tag['name'],
					'stickynote' => $tag ['stickynote'],
					// 'role_call' => $role_call,
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'tasksinfo' => $tasksinfo1,
					'taskTotal' => $taskTotal,
					'recentnote' => $lastnotesinfo [0] ['notes_description'],
					'recenttasks' => $recenttasksinfos ['description'],
					'ndate_added' => $ndate_added,
					'client_medicine' => $client_medicine,
					'tagstatus_info' => $status,
					'screenig_url' => $screenig_url,
					'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
			);
		}
		
		$show_client_image = $client_info ["show_client_image"];
		$show_form_tag = $client_info ["show_form_tag"];
		$show_task = $client_info ["show_task"];
		$show_case = $client_info ["show_case"];
		
		$total_in_url = $this->url->link ( 'resident/resident&in=1', '' . $url1, 'SSL' );
		$total_out_url = $this->url->link ( 'resident/resident&out=1', '' . $url1, 'SSL' );
		$non_url = $this->url->link ( 'resident/resident&gender=3', '' . $url1, 'SSL' );
		
		$total_url = $this->url->link ( 'resident/resident', '', 'SSL' );
		
		$notes_url = $this->url->link ( 'notes/notes/insert', '', 'SSL' );
		
		$sticky_note = $this->url->link ( 'resident/resident/getstickynote&close=1', '', 'SSL' );
		
		$dailycensus = $this->url->link ( 'resident/dailycensus', '', 'SSL' );
		
		$clientfile = $this->url->link ( 'resident/resident/clientfile', '', 'SSL' );
		
		$logout = $this->url->link ( 'common/logout', '', 'SSL' );
		
		$task_lists = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/headertasklist', '' . $url1, 'SSL' ) );
		
		$task_lists2 = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatus', '' . $url1, 'SSL' ) );
		
		$case_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/cases/dashboard', '', 'SSL' ) );
		
		$add_client_url1 = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
		$assignteam = str_replace ( '&amp;', '&', $this->url->link ( 'resident/assignteam', '', 'SSL' ) );
		
		$close = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		$tag_forms = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url2, 'SSL' ) );
		
		$clientstatus_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatus', '' . $url2, 'SSL' ) );
		
		$multiple_action_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/multipleaction', '' . $url2, 'SSL' ) );
		
		$add_tag_medication_url = $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' );
		
		$add_client_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '' . '&addclient=1', 'SSL' ) );
		
		$action = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '', 'SSL' ) );
		
		$activenote_url = $this->url->link ( 'resident/resident/activenote', '', 'SSL' );
		
		// $this->response->setOutput(json_encode($json));
		$template = new Template ();
		$template->data ['tags'] = $json;
		$template->data ['show_client_image'] = $show_client_image;
		$template->data ['show_form_tag'] = $show_form_tag;
		$template->data ['show_task'] = $show_task;
		$template->data ['show_case'] = $show_case;
		$template->data ['total_in_url'] = $total_in_url;
		$template->data ['total_out_url'] = $total_out_url;
		$template->data ['non_url'] = $non_url;
		$template->data ['total_url'] = $total_url;
		$template->data ['notes_url'] = $notes_url;
		$template->data ['default_facility_id'] = $facilities_id;
		
		$template->data ['sticky_note'] = $sticky_note;
		$template->data ['dailycensus'] = $dailycensus;
		$template->data ['clientfile'] = $clientfile;
		$template->data ['logout'] = $logout;
		$template->data ['task_lists'] = $task_lists;
		$template->data ['task_lists2'] = $task_lists2;
		$template->data ['case_url'] = $case_url;
		$template->data ['add_client_url1'] = $add_client_url1;
		$template->data ['assignteam'] = $assignteam;
		$template->data ['close'] = $close;
		$template->data ['tag_forms'] = $tag_forms;
		$template->data ['clientstatus_url'] = $clientstatus_url;
		$template->data ['multiple_action_url'] = $multiple_action_url;
		$template->data ['add_client_url'] = $add_client_url;
		$template->data ['add_tag_medication_url'] = $add_tag_medication_url;
		$template->data ['action'] = $action;
		$template->data ['activenote_url'] = $activenote_url;
		$template->data ['is_external_status_facility'] = $facilities_is_master ['enable_facilityinout'];
		$template->data ['customers'] = $customers;
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/notification/ajaxresident.php' )) {
			$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/notification/ajaxresident.php' );
		}
		
		// var_dump($html);
		$ajax_status = 1;
		if (empty ( $tags )) {
			$ajax_status = 2;
		}
		
		$json1 = array ();
		$json1 ['ajax_status'] = $ajax_status;
		$json1 ['html'] = $html;
		
		$this->response->setOutput ( json_encode ( $json1 ) );
	}
	public function allclientstatus() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			$url2 = "";
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->post ['facilities_id'];
			}
			
			if ($this->request->post ['tags_id'] != null && $this->request->post ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->post ['tags_id'];
			}
			
			if ($this->request->post ['tag_status_name'] != null && $this->request->post ['tag_status_name'] != "") {
				$url2 .= '&name=' . $this->request->post ['tag_status_name'];
			}
			
			if ($this->request->post ['tag_status_id'] != null && $this->request->post ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->post ['tag_status_id'];
			}
			
			$this->session->data ['movement_room'] = $this->request->post ['movement_room'];
			$this->session->data ['mfacilities_id'] = $this->request->post ['mfacilities_id'];
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&allclientstatus=1';
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) ) );
			} else {
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '' . $url2, 'SSL' ) ) );
			}
		}
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['display_client'] = "";
		
		$client_statuses = $this->model_resident_resident->getClientStatus ( $data3 );
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
			
			$this->load->model ( 'setting/tags' );
			$stickyinfo = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $stickyinfo ['facility_move_id'] );
			
			$this->load->model ( 'setting/locations' );
			
			$roominfo = $this->model_setting_locations->getlocation ( $stickyinfo ['movement_room'] );
			
			$this->data ['is_movement'] = $stickyinfo ['is_movement'];
			$this->data ['movement_room'] = $stickyinfo ['movement_room'];
			$this->data ['location_name'] = $roominfo ['location_name'];
			$this->data ['facilities_id'] = $stickyinfo ['facilities_id'];
			$this->data ['facility_move_id'] = $stickyinfo ['facility_move_id'];
			$this->data ['facility'] = $facilities_info2 ['facility'];
		}
		
		if ($this->request->get ['facility_inout'] != null && $this->request->get ['facility_inout'] != "") {
			$this->data ['facility_inout'] = $this->request->get ['facility_inout'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		$this->data ['custom_forms'] = array ();
		foreach ( $client_statuses as $custom_form ) {
			
			$this->data ['custom_forms'] [] = array (
					'tag_status_id' => $custom_form ['tag_status_id'],
					'name' => $custom_form ['name'],
					'type' => $custom_form ['type'],
					'image' => $custom_form ['image'] 
			);
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
			// $facilities_id = $this->customer->getId();
		}
		
		$this->load->model ( 'facilities/facilities' );
		$dataaaa ['facilities_id'] = $facilities_id;
		
		$this->data ['sfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );
		
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
		
		
		
		$this->load->model ( 'setting/locations' );
		$data = array (
				'location_name' => $this->request->get ['filter_name'],
				'facilities_id' => $stickyinfo ['facility_move_id'],
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
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&allclientstatus=1';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '', 'SSL' ) );
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/allclientstatus.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function alltagstatus() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['display_client'] = "";
		
		$client_statuses = $this->model_resident_resident->getClientStatus ( $data3 );
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		$this->data ['custom_forms'] = array ();
		foreach ( $client_statuses as $custom_form ) {
			
			$this->data ['custom_forms'] [] = array (
					'tag_status_id' => $custom_form ['tag_status_id'],
					'name' => $custom_form ['name'],
					'color_code' => $custom_form ['color_code'],
					'image' => $custom_form ['image'] 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&allclientstatus=1';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '', 'SSL' ) );
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/alltagstatus.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function allclientclassification() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'customer/customer' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['customer_key'] = $customer_info ['activecustomer_id'];
		$data3 ['display_client'] = "";
		
		$client_classifications = $this->model_resident_resident->getClientClassification ( $data3 );
		
		if ($this->request->get ['tag_classification_id'] != '0' && $this->request->get ['tag_classification_id'] != null) {
			
			$classification_value = $this->model_resident_resident->getClassificationValue ( $this->request->get ['tag_classification_id'] );
			
			$this->data ['client_classification_color'] = $classification_value ['color_code'];
			$this->data ['client_classification_name'] = $classification_value ['name'];
			$this->data ['tag_classification_id'] = $this->request->get ['tag_classification_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		$this->data ['custom_forms'] = array ();
		foreach ( $client_classifications as $client_classification ) {
			
			$this->data ['custom_forms'] [] = array (
					'tag_classification_id' => $client_classification ['tag_classification_id'],
					'classification_name' => $client_classification ['classification_name'],
					'color_code' => $client_classification ['color_code'] 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&allclientstatus=1';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '', 'SSL' ) );
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/allclientclassification.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}


   public function multipleclassification() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'customer/customer' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['customer_key'] = $customer_info ['activecustomer_id'];
		$data3 ['display_client'] = "";
		
		$client_classifications = $this->model_resident_resident->getClientClassification ( $data3 );
		
		if ($this->request->get ['tag_classification_id'] != '0' && $this->request->get ['tag_classification_id'] != null) {
			
			$classification_value = $this->model_resident_resident->getClassificationValue ( $this->request->get ['tag_classification_id'] );
			
			$this->data ['client_classification_color'] = $classification_value ['color_code'];
			$this->data ['client_classification_name'] = $classification_value ['name'];
			$this->data ['tag_classification_id'] = $this->request->get ['tag_classification_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}

		 if ($this->request->get ['tag_classification_id_array'] ){



		 

        	$this->data ['tag_classification_ids']=$this->request->get ['tag_classification_id_array']; 

          $this->data ['classificationidsarray'] =  $this->request->get ['tag_classification_id_array'];      

        $facilityarray = $this->request->get ['tag_classification_id_array'];

         $facilities_array = explode (",", $facilityarray); 


       
         foreach($facilities_array as $facility){

         	$selectedFacilities =  $this->model_resident_resident->getClassificationValue ( $facility );


         	$this->data ['classificationArray'] [] = array (
					'tag_classification_id' => $selectedFacilities ['tag_classification_id'],
					'classification_name' => $selectedFacilities ['classification_name'],
					'color_code' => $selectedFacilities ['color_code'] 
			);

         }
		}

		//adam

		$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ($this->request->get ['tags_id']);

		if($tag_info){

			$this->data['tag_classification_ids']=$tag_info['classification_id'];

		}

		 // var_dump($this->data['tag_classification_ids']);die;		

       
						
		$this->data ['custom_forms'] = array ();
		foreach ( $client_classifications as $client_classification ) {
			
			$this->data ['custom_forms'] [] = array (
					'tag_classification_id' => $client_classification ['tag_classification_id'],
					'classification_name' => $client_classification ['classification_name'],
					'color_code' => $client_classification ['color_code'] 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&allclientstatus=1';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '', 'SSL' ) );
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/classification_lists.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}

	public function StatusCount() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['display_client'] = "";
		
		$client_statuses = $this->model_resident_resident->getClientStatus ( $data3 );
		
		$this->data ['status_wise_url'] = $this->url->link ( 'resident/resident&role_call=' . $this->request->get ['tag_status_id'], '', 'SSL' );
		
		// $this->data['status_wise_url_header'] = $this->url->link('common/headerclient&role_call='.$this->request->get['tag_status_id'], '', 'SSL');
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		$this->data ['custom_forms'] = array ();
		foreach ( $client_statuses as $custom_form ) {
			
			$this->data ['custom_forms'] [] = array (
					'tag_status_id' => $custom_form ['tag_status_id'],
					'name' => $custom_form ['name'],
					'image' => $custom_form ['image'] 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/StatusCount.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function allclientstatuses() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			$url2 = "";
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->post ['facilities_id'];
			}
			
			if ($this->request->post ['tagsids'] != null && $this->request->post ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->post ['tagsids'];
			}
			if ($this->request->post ['tags_ids'] != null && $this->request->post ['tags_ids'] != "") {
				$url2 .= '&tags_ids=' . $this->request->post ['tags_ids'];
			}
			
			if ($this->request->post ['tag_status_name'] != null && $this->request->post ['tag_status_name'] != "") {
				$url2 .= '&name=' . $this->request->post ['tag_status_name'];
			}
			
			if ($this->request->post ['tag_status_id'] != null && $this->request->post ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->post ['tag_status_id'];
			}
			
			$this->session->data ['movement_room'] = $this->request->post ['movement_room'];
			$this->session->data ['mfacilities_id'] = $this->request->post ['mfacilities_id'];
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&allclientstatuses=1';
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) ) );
			} else {
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussigns', '' . $url2, 'SSL' ) ) );
			}
		}
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['display_client'] = "";
		
		$client_statuses = $this->model_resident_resident->getClientStatus ( $data3 );
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_ids'];
			
			$this->load->model ( 'setting/tags' );
			$stickyinfo = $this->model_setting_tags->getTag ( $this->request->get ['tags_ids'] );
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $stickyinfo ['facility_move_id'] );
			
			$this->load->model ( 'setting/locations' );
			
			$roominfo = $this->model_setting_locations->getlocation ( $stickyinfo ['movement_room'] );
			
			$this->data ['is_movement'] = $stickyinfo ['is_movement'];
			$this->data ['movement_room'] = $stickyinfo ['movement_room'];
			$this->data ['location_name'] = $roominfo ['location_name'];
			$this->data ['facility_move_id'] = $stickyinfo ['facility_move_id'];
			$this->data ['facility'] = $facilities_info2 ['facility'];
		}
		
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$this->data ['keyword_id'] = $this->request->get ['keyword_id'];
		}
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$this->data ['tagsids'] = $this->request->get ['tagsids'];
		}
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$this->data ['tags_ids'] = $this->request->get ['tags_ids'];
			
			$this->load->model ( 'setting/tags' );
			$stickyinfo = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $stickyinfo ['facility_move_id'] );
			
			$this->load->model ( 'setting/locations' );
			
			$roominfo = $this->model_setting_locations->getlocation ( $stickyinfo ['movement_room'] );
			
			$this->data ['is_movement'] = $stickyinfo ['is_movement'];
			$this->data ['movement_room'] = $stickyinfo ['movement_room'];
			$this->data ['location_name'] = $roominfo ['location_name'];
			$this->data ['facility_move_id'] = $stickyinfo ['facility_move_id'];
			$this->data ['facility'] = $facilities_info2 ['facility'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		$this->data ['custom_forms'] = array ();
		foreach ( $client_statuses as $custom_form ) {
			
			$this->data ['custom_forms'] [] = array (
					'tag_status_id' => $custom_form ['tag_status_id'],
					'name' => $custom_form ['name'],
					'type' => $custom_form ['type'],
					'image' => $custom_form ['image'] 
			);
		}
		
		$url2 = "";
		
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= "&keyword_id=" . $this->request->get ['keyword_id'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$dataaaa ['facilities_id'] = $this->customer->getId ();
		
		$this->data ['sfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&allclientstatuses=1';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			$url2 .= '&allclientstatuses=1';
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussigns', '' . $url2, 'SSL' ) );
		}
		
		/*
		 * if(($this->request->get['tags_ids']!=null && $this->request->get['tags_ids']!="") && ($facilities_id!=null && $facilities_id!="")){
		 * $url2.="&tags_ids=".$this->request->get['tags_ids'];
		 *
		 *
		 * $this->data['role_callsign'] = str_replace('&amp;', '&', $this->url->link('resident/resident/updateclientstatussigns', ''.$url2, 'SSL'));
		 *
		 * }
		 */
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/allclientstatuses.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function multipleaction() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		if ($this->request->get ['ext_facility'] == '1' && $this->request->get ['default_facility_id'] != "" && $this->request->get ['default_facility_id'] != null && $this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			
			$tag_ids = preg_split ( "/\,/", $this->request->get ['tags_ids'] );
			
			foreach ( $tag_ids as $tag_id ) {
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $tag_id );
				$tags_ids_facilities [] = $tag_info ['facilities_id'];
				$facility_inouts [] = $tag_info ['facility_inout'];
			}
			
			if (in_array ( $this->request->get ['default_facility_id'], $tags_ids_facilities )) {
				
				if (array_unique ( $tags_ids_facilities ) === array (
						$this->request->get ['default_facility_id'] 
				)) {
					
					$this->data ['show_status'] = '0';
				} else {
					
					$this->data ['show_status'] = '1';
				}
			} else {
				
				$this->data ['show_status'] = '1';
			}
		} else {
			
			$this->data ['show_status'] = '1';
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		
		$this->load->model ( 'facilities/facilities' );
        $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
        $unique_id = $facility ['customer_key'];
        
        $this->load->model ( 'customer/customer' );
        $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
        $this->data['customers'] = array();
        if (! empty ( $customer_info ['setting_data'])) {
            $customers = unserialize($customer_info ['setting_data']);
            $this->data['customerinfo'] = $customers;
        }
		
		$url2 = "";
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['ext_facility'] != null && $this->request->get ['ext_facility'] != "") {
			$this->data ['ext_facility'] = $this->request->get ['ext_facility'];
			
			$this->data ['switch_value'] = $facility_inouts ['0'];
		}
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$this->data ['tags_ids'] = $this->request->get ['tags_ids'];
			$url2 .= "&tags_ids=" . $this->request->get ['tags_ids'];
			$url2 .= "&tagsids=" . $this->request->get ['tags_ids'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->data ['facilities_id'] = $this->request->get ['facilities_id'];
			$url2 .= "&facilities_id=" . $facilities_id;
		}
		
		if ($this->request->get ['facility_outs'] != null && $this->request->get ['facility_outs'] != "") {
			$this->data ['facility_outs'] = $this->request->get ['facility_outs'];
			$url2 .= "&facility_outs=" . $this->request->get ['facility_outs'];
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$this->data ['facilities_id'] = $facilities_id;
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		// if (($this->request->post['all_roll_call'] == '1')) {
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$this->data ['dischargeclients_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&allrolecallsign=2', '' . $url2, 'SSL' ) );
			$this->data ['facility_in_out_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&allrolecallsign=1', '' . $url2, 'SSL' ) );
			
			// $this->data['clientstatuses_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&allrolecallsign=3', '' . $url2, 'SSL'));
		} else {
			$this->data ['dischargeclients_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/dischargeclients', '' . $url2, 'SSL' ) );
			$this->data ['facility_in_out_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
		}
		// }
		
		$this->data ['clientstatuses_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatuses', '' . $url2, 'SSL' ) );
		
		$this->data ['notes_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/multipleaction.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function updateclient() {
		$facilities_id = "";
		
		if (!$this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			if ($this->request->get ['facilities_id'] == $this->customer->getId ()) {
				
				$facilities_id = $this->customer->getId ();
			} else {
				
				$facilities_id = $this->request->get ['facilities_id'];
			}
		}
		
		$json = array ();
		$this->load->model ( 'resident/resident' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $facilities_id;
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
			/*
			 * if ($this->request->get['discharge'] == '1') {
			 * // $this->model_resident_resident->updateDischargeTag($this->request->get['tags_id']);
			 *
			 * $url2 = "";
			 *
			 * if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			 * $url2 .= '&tags_id=' . $this->request->get['tags_id'];
			 * }
			 * if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
			 * $url2 .= '&discharge=' . $this->request->get['discharge'];
			 * }
			 *
			 * if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			 * $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			 * }
			 * // $url2.='&facilities_id='. $facilities_id;
			 *
			 * $this->load->model('facilities/facilities');
			 * $facilities_info = $this->model_facilities_facilities->getfacilities($datafa['facilities_id']);
			 *
			 * if ($facilities_info['is_discharge_form_enable'] == '1') {
			 * $url2 .= '&forms_design_id=' . $facilities_info['discharge_form_id'];
			 * $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
			 * } else {
			 *
			 * if ($facilities_info['is_enable_add_notes_by'] == '1' || $facilities_info['is_enable_add_notes_by'] == '3') {
			 * $url2 .= '&rolecallsign=1';
			 * $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
			 * } else {
			 * $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/rolecallsign', '' . $url2, 'SSL'));
			 * }
			 * }
			 *
			 * $json['success'] = '1';
			 * }
			 */
			
			/*
			 * if ($this->request->get['viewnotes'] == '1') {
			 * $this->load->model('setting/tags');
			 * $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
			 *
			 * $this->session->data['keyword'] = $tag_info['emp_first_name'];
			 * // $this->session->data['search_emp_tag_id'] =
			 * // $this->request->get['tags_id'];
			 * $this->session->data['advance_search'] = '1';
			 *
			 * $this->session->data['group'] = '1';
			 *
			 * $json['success'] = '1';
			 * }
			 */
			
			/*
			 * if ($this->request->get['highliter'] == '1') {
			 * $this->model_resident_resident->updatetagcolor($this->request->get['tags_id'], $this->request->get['highliter_id'], $this->request->get['text_highliter_div_cl']);
			 * $json['success'] = '1';
			 * }
			 */
			
			if ($this->request->get ['rolecall2'] == '1') {
				
				$url2 = "";
				
				if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
					$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
				}
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
					$url2 .= '&role_call=' . $this->request->get ['role_call'];
				}
				
				if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
					$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
				}
				
				// $url2.='&facilities_id='.$this->request->get['facilities_id'];
				
				// var_dump($facilities_info);
				// die;
				
				if ($facilities_info ['is_enable_add_notes_by'] == '1' || $facilities_info ['is_enable_add_notes_by'] == '3') {
					$url2 .= '&rolecallsign=1';
					$json ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
				} else {
					$json ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/rolecallsign', '' . $url2, 'SSL' ) );
				}
				
				$json ['success'] = '1';
			}
		}
		
		// var_dump($url2);
		// die;
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	public function tagforms() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		
		if (!$this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		
		$tags_id = $this->request->get ['tags_id'];
		$this->data ['facilities_id'] = $this->request->get ['facilities_id'];
		$this->data ['tags_id'] = $this->request->get ['tags_id'];
		
		$this->data ['add_client_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->data ['name'] = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
			// $facilities_id = $this->customer->getId();
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
		$this->data ['date_format'] = $date_format;
		$this->data ['time_format'] = $time_format;
		
		$d1 = array ();
		$d1 ['tags_id'] = $tags_id;
		$d1 ['form_type'] = '2';
		$client_info_sign = $this->model_notes_notes->getNoteform ( $d1 );
		// var_dump($client_info_sign);
		
		$this->data ['client_user_id'] = $client_info_sign ['user_id'];
		$this->data ['client_signature'] = $client_info_sign ['signature'];
		$this->data ['client_notes_pin'] = $client_info_sign ['notes_pin'];
		$this->data ['client_notes_type'] = $client_info_sign ['notes_type'];
		
		if ($client_info_sign ['note_date'] != null && $client_info_sign ['note_date'] != "0000-00-00 00:00:00") {
			$this->data ['client_form_date_added'] = date ( $date_format, strtotime ( $client_info_sign ['note_date'] ) );
		} else {
			$this->data ['client_form_date_added'] = '';
		}
		
		$d12 = array ();
		$d12 ['tags_id'] = $tags_id;
		$d12 ['form_type'] = '1';
		$healthforn_info_sign = $this->model_notes_notes->getNoteform ( $d12 );
		
		$this->data ['health_user_id'] = $healthforn_info_sign ['user_id'];
		$this->data ['health_signature'] = $healthforn_info_sign ['signature'];
		$this->data ['health_notes_pin'] = $healthforn_info_sign ['notes_pin'];
		$this->data ['health_notes_type'] = $healthforn_info_sign ['notes_type'];
		
		if ($healthforn_info_sign ['note_date'] != null && $healthforn_info_sign ['note_date'] != "0000-00-00 00:00:00") {
			$this->data ['health_form_date_added'] = date ( $date_format, strtotime ( $healthforn_info_sign ['note_date'] ) );
		} else {
			$this->data ['health_form_date_added'] = '';
		}
		
		$this->load->model ( 'resident/resident' );
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		if (isset ( $this->request->get ['forms_design_id'] )) {
			$forms_design_id = $this->request->get ['forms_design_id'];
			$this->data ['forms_design_id'] = $forms_design_id;
		} else {
			$forms_design_id = '';
		}
		
		if (isset ( $this->request->get ['attachment'] )) {
			$attachment = $this->request->get ['attachment'];
			$this->data ['attachment'] = $attachment;
		} else {
			$attachment = '';
		}
		
		
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		
		if($this->request->get ['forms_design_id'] == "" && $this->request->get ['forms_design_id'] == null){
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'tags_id' => $tags_id 
			);
			
			$aallattas = $this->model_setting_tags->gettagsattachmets ( $data );
			
			
			$this->data ['attachments'] = array ();
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
				
				if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
				} else {
					$form_date_added = '';
				}
				$this->data ['attachments'] [] = array (
					'notes_media_id' => $aallatta ['notes_media_id'],
					'name' => "Attachment",
					'form_href' => $hrurl,
					'notes_type' => $notes_type,
					'notes_description' => $notes_description,
					'user_id' => $user_id,
					'signature' => $signature,
					'notes_pin' => $notes_pin,
					'form_date_added' => $form_date_added,
					'date_added2' => date ( 'D F j, Y', strtotime ( $form_date_added ) ),
				);
			}
		}
		
		//var_dump($this->data ['attachments']);
		
		
		$data = array (
				'sort' => $sort,
				'order' => $order,
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
		}
		
		if($this->request->get ['attachment'] == "" && $this->request->get ['attachment'] == null){
		$data = array (
				'sort' => $sort,
				'order' => $order,
				'group' => '1',
				'tags_id' => $tags_id,
				'custom_form_type' => $forms_design_id,
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
		);
		
		$form_total = $this->model_form_form->getTotalforms2 ( $data );
		
		$allforms = $this->model_form_form->gettagsforms ( $data );
		$this->data ['tagsforms'] = array ();
		
		foreach ( $allforms as $allform ) {
			
			$resultsforms = $this->model_form_form->getArcheiveFormDatas ( $allform ['forms_id'] );
			
			$archivedforms = array ();
			foreach ( $resultsforms as $resultsform ) {
				$nnote = $this->model_notes_notes->getnotes ( $resultsform ['notes_id'] );
				
				$archivedforms [] = array (
						'forms_id' => $resultsform ['forms_id'],
						'form_name' => $resultsform ['incident_number'],
						'notes_type' => $nnote ['notes_type'],
						'notes_description' => $nnote ['notes_description'],
						'user_id' => $nnote ['user_id'],
						'signature' => $nnote ['signature'],
						'notes_pin' => $nnote ['notes_pin'],
						'form_date_added' => date ( $date_format, strtotime ( $nnote ['note_date'] ) ),
						'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
						'form_href' => $this->url->link ( 'form/form&is_archive=4', '' . '&forms_id=' . $resultsform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $resultsform ['notes_id'] . '&forms_design_id=' . $resultsform ['custom_form_type'] . '&forms_id=' . $resultsform ['forms_id'], 'SSL' ) 
				);
			}
			
			$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
			
			$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
			
			if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
				$user_id = $allform ['user_id'];
				$signature = $allform ['signature'];
				$notes_pin = $allform ['notes_pin'];
				$notes_type = $allform ['notes_type'];
				$notes_description = $note_info ['notes_description'];
				
				if ($allform ['form_date_added'] != null && $allform ['form_date_added'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $allform ['form_date_added'] ) );
				} else {
					$form_date_added = '';
				}
			} else {
				
				// var_dump($note_info);
				$user_id = $note_info ['user_id'];
				$signature = $note_info ['signature'];
				$notes_pin = $note_info ['notes_pin'];
				$notes_type = $note_info ['notes_type'];
				$notes_description = $note_info ['notes_description'];
				
				if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
				} else {
					$form_date_added = '';
				}
			}
			
			if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
				
				$mediainfo = $this->model_form_form->getformmediabyid ($allform ['notes_id'], $allform ['custom_form_type']);
				
				$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $mediainfo ['notes_media_id'] , 'SSL' );
				//$hrurl = $aallform ['image_url'];
				$form_name = $allform ['image_name'];
			} else {
				$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $allform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&forms_id=' . $allform ['forms_id'], 'SSL' );
				
				$form_name = $allform ['incident_number'];
			}
			
			$this->data ['tagsforms'] [] = array (
					'forms_id' => $allform ['forms_id'],
					'image_url' => $allform ['image_url'],
					'form_name' => $form_name,
					'notes_type' => $notes_type,
					'notes_description' => $notes_description,
					'user_id' => $user_id,
					'signature' => $signature,
					'notes_pin' => $notes_pin,
					'form_date_added' => $form_date_added,
					'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
					'archivedforms' => $archivedforms,
					'form_href' => $hrurl 
			);
		}
		// var_dump($this->data['tagsforms']);
		
		$data2 = array (
				'sort' => $sort,
				'order' => $order,
				'group' => '1',
				'archivedform' => '1',
				'tags_id' => $tags_id 
		);
		
		$aallforms = $this->model_form_form->gettagsforms ( $data2 );
		$this->data ['atagsforms'] = array ();
		
		foreach ( $aallforms as $aallform ) {
			
			$form_info = $this->model_form_form->getFormdata ( $aallform ['custom_form_type'] );
			
			if ($aallform ['user_id'] != null && $aallform ['user_id'] != "") {
				$user_id = $aallform ['user_id'];
				$signature = $aallform ['signature'];
				$notes_pin = $aallform ['notes_pin'];
				$notes_type = $aallform ['notes_type'];
				
				if ($aallform ['form_date_added'] != null && $aallform ['form_date_added'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $aallform ['form_date_added'] ) );
				} else {
					$form_date_added = '';
				}
			} else {
				
				$note_info = $this->model_notes_notes->getNote ( $aallform ['notes_id'] );
				
				// var_dump($note_info);
				$user_id = $note_info ['user_id'];
				$signature = $note_info ['signature'];
				$notes_pin = $note_info ['notes_pin'];
				$notes_type = $note_info ['notes_type'];
				$notes_description = $note_info ['notes_description'];
				
				if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
				} else {
					$form_date_added = '';
				}
			}
			
			if ($aallform ['image_url'] != null && $aallform ['image_url'] != "") {
				$mediainfo = $this->model_form_form->getformmediabyid ($allform ['notes_id'], $allform ['custom_form_type']);
				
				$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $mediainfo ['notes_media_id'] , 'SSL' );
				//$hrurl = $aallform ['image_url'];
				$form_name = $aallform ['image_name'];
			} else {
				$hrurl = $this->url->link ( 'form/form&is_archive=4', '' . '&forms_id=' . $aallform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $aallform ['notes_id'] . '&forms_design_id=' . $aallform ['custom_form_type'] . '&forms_id=' . $aallform ['forms_id'], 'SSL' );
				
				$form_name = $aallform ['incident_number'];
			}
			
			$this->data ['atagsforms'] [] = array (
					'forms_id' => $aallform ['forms_id'],
					'image_url' => $aallform ['image_url'],
					'image_name' => $aallform ['image_name'],
					'form_name' => $form_name,
					'notes_type' => $notes_type,
					'notes_description' => $notes_description,
					'user_id' => $user_id,
					'signature' => $signature,
					'notes_pin' => $notes_pin,
					'form_date_added' => $form_date_added,
					'date_added2' => date ( 'D F j, Y', strtotime ( $aallform ['date_added'] ) ),
					'form_href' => $hrurl 
			);
		}
		}
		
		$url2 = "";
		$url3 = "";
		if ($tags_id != "" && $tags_id != NULL) {
			$url = '&tags_id=' . $tags_id;
			$url2 .= '&tags_id=' . $tags_id;
			$url3 .= '&tags_id=' . $tags_id;
		}
		if (isset ( $this->request->get ['attachment'] )) {
			$url .= '&attachment=' . $this->request->get ['attachment'];
		}
		
		$this->data ['frm_add_link'] = $this->url->link ( 'notes/notes/allforms', '' . $url, 'SSL' );
		
		$this->data ['newtagurl'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '', 'SSL' ) );
		$this->data ['newtagur2l'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '', 'SSL' ) );
		
		$pagination = new Pagination ();
		$pagination->total = $form_total;
		$pagination->page = $page;
		$pagination->limit = $config_admin_limit;
		
		$pagination->text = ''; // $this->language->get('text_pagination');
		$pagination->url = $this->url->link ( 'resident/resident/tagforms', '' . $url . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		
		$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url3, 'SSL' ) );
		$this->data ['attachmenturl'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms&attachment=1', '' . $url2, 'SSL' ) );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/tags_form.php';
		
		$this->children = array (
				// 'common/headerpopup'
				'common/headerclient',
				'common/footerclient' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function tagsmedication() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$this->data ['current_time'] = date ( 'h:i A' );
		
		if ($this->request->get ['tags_id']) {
			$tags_id = $this->request->get ['tags_id'];
		} elseif ($this->request->post ['emp_tag_id']) {
			$tags_id = $this->request->post ['emp_tag_id'];
		}
		
		$this->data ['tag'] = $this->model_setting_tags->getTag ( $tags_id );
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			
			$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		
		if ($resulsst ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $this->request->get ['facilities_id'];
				$timezone_name = $this->customer->isTimezone ();
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
		}
		
		$this->load->model ( 'createtask/createtask' );
		$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
		
		$this->load->model ( 'resident/resident' );
		
		$this->document->setTitle ( 'Medication' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $tags_id;
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['type'] = 'updatehealthform';
			
			$archive_tags_medication_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			
			$url2 = "";
			
			if (! empty ( $this->request->post ['medication'] )) {
				// $this->session->data['medication'] =
				// $this->request->post['medication'];
				
				$medication_tags = implode ( ',', $this->request->post ['medication'] );
				
				if ($medication_tags != null && $medication_tags != "") {
					$url2 .= '&medication_tags=' . $medication_tags;
				}
				
				$this->session->data ['success2'] = 'Medication added successfully!';
			} else {
				$this->session->data ['success_add_form'] = 'Medication added successfully!';
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$url2 .= '&locationids=' . $this->request->get ['locationids'];
			}
			
			if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
				$url2 .= '&userids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['tags_medicine'] != null && $this->request->get ['tags_medicine'] != "") {
				$url2 .= '&tags_medicine=' . $this->request->get ['tags_medicine'];
				
				$this->data ['tags_medicine'] = $this->request->get ['tags_medicine'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
			}
			
			$url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			
			$this->redirect ( $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' ) );
		}
		
		$url2 = "";
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
		}
		
		if ($this->request->get ['tags_medicine'] != null && $this->request->get ['tags_medicine'] != "") {
			$url2 .= '&tags_medicine=' . $this->request->get ['tags_medicine'];
			$this->data ['tags_medicine'] = $this->request->get ['tags_medicine'];
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		if ($this->request->get ['archive_tags_medication_id'] != null && $this->request->get ['archive_tags_medication_id'] != "") {
			$url2 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
		}
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get ['userids'];
		}
		
		// $this->data ['addinventorys_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/verifyInventory', '' . $url2, 'SSL' ) );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&tagmedicine=1', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&tagmedicine=2', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign2', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['printaction'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/printmedicationform', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->request->post ['room_id'] )) {
			$this->data ['room_id'] = $this->request->post ['room_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['room_id'] = $tag_info ['room'];
		} else {
			$this->data ['room_id'] = '';
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
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
		
		$this->load->model ( 'medicationtype/medicationtype' );
		$results = $this->model_medicationtype_medicationtype->getmedicationtypes ( $data );
		
		foreach ( $results as $result ) {
			
			$this->data ['medication_types'] [] = array (
					'medicationtype_id' => $result ['medicationtype_id'],
					'type_name' => $result ['type_name'],
					'type' => $result ['type'],
					'measurement_type' => $result ['measurement_type'],
					'status' => $result ['status'] 
			);
		}
		
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
			
			$muduled = $this->model_resident_resident->gettagModule ( $this->request->get ['tags_id'], "0", $this->request->get ['notes_id'] );
			
			$this->data ['modules'] = $muduled ['new_module'];
		} elseif ($this->request->post ['emp_tag_id']) {
			
			$muduled = $this->model_resident_resident->gettagModule ( $this->request->post ['emp_tag_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['modules'] = $muduled ['new_module'];
		} else {
			$this->data ['modules'] = array ();
		}
		
		/* echo '<pre>'; print_r( $this->data['modules']); echo '</pre>';die; */
		
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
		
		if (isset ( $this->request->post ['route'] )) {
			$this->data ['route'] = $this->request->post ['route'];
		} else {
			$this->data ['route'] = '';
		}
		
		if (isset ( $this->request->post ['doctors'] )) {
			$this->data ['doctors'] = $this->request->post ['doctors'];
		} else {
			$this->data ['doctors'] = '';
		}
		
		if (isset ( $this->request->post ['reasons'] )) {
			$this->data ['reasons'] = $this->request->post ['reasons'];
		} else {
			$this->data ['reasons'] = '';
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
			;
		}
		if (isset ( $this->error ['daily_times'] )) {
			$this->data ['error_daily_times'] = $this->error ['daily_times'];
		} else {
			$this->data ['error_daily_times'] = array ();
			;
		}
		if (isset ( $this->error ['drug_mg'] )) {
			$this->data ['error_drug_mg'] = $this->error ['drug_mg'];
		} else {
			$this->data ['error_drug_mg'] = array ();
		}
		
		if (isset ( $this->error ['drug_pm'] )) {
			$this->data ['error_drug_pm'] = $this->error ['drug_pm'];
		} else {
			$this->data ['error_drug_pm'] = array ();
		}
		
		if (isset ( $this->error ['drug_alertnate'] )) {
			$this->data ['error_drug_alternate'] = $this->error ['drug_alertnate'];
		} else {
			$this->data ['error_drug_alternate'] = array ();
			;
		}
		
		$url2 = "";
		$url3 = "";
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
			$url3 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
			$url3 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
			$url3 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get ['userids'];
			$url3 .= '&userids=' . $this->request->get ['userids'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$url3 .= '&tags_id=' . $this->request->get ['tags_id'];
			$url3 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			$this->data ['is_archive'] = $this->request->get ['is_archive'];
		}
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$this->data ['addinventorys_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&addmedication=1', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['addinventorys_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateMedication&addmedication=2', '' . $url2, 'SSL' ) );
		}
		
		// $this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['updatenotes_id'] = $notes_id;
		
		$this->data ['action'] = $this->url->link ( 'resident/resident/tagsmedication', $url2, true );
		
		// var_dump( $this->data['action'] );die;
		
		if ($this->request->get ['notes_id']) {
			
			$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '', 'SSL' ) );
		} else {
			
			$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '', 'SSL' ) );
		}
		
		$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '' . $url3, 'SSL' ) );
		
		$this->data ['autosearch'] = $this->request->get ['autosearch'];
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/medication.php';
		
		$this->children = array (
				'common/headerclient' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm() {
		$this->load->model ( 'resident/resident' );
		
		/*
		 * if($this->request->post['new_module'] == null &&
		 * $this->request->post['new_module'] == ""){
		 * $this->error['warning'] = 'Warning: Medication is required';
		 * }
		 */
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['new_module'] != null && $this->request->post ['new_module'] != "") {
			foreach ( $this->request->post ['new_module'] as $key => $new_module ) {
				/*
				 * if ($new_module['drug_name'] == "" && $new_module['drug_name'] == null) {
				 * $this->error['drug_name'][$key] = ' Medication is required';
				 * }
				 */
				
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
			$this->error ['warning'] = 'Client is required';
		}
		
		/*
		 * if ($this->request->post['drug_name'] != "" && $this->request->post['drug_name'] != null) {
		 * $medication_info = $this->model_resident_resident->get_medicationyname($this->request->post['drug_name'], $this->request->get['tags_id']);
		 *
		 * if ($medication_info) {
		 * $this->error['warning'] = 'Medication is already in enter!';
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
	public function tagsmedicationsign() {		
		 
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'setting/tags' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['medication_tags'] = $this->request->get ['medication_tags'];
			
			$taginfo = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			$result = $this->model_facilities_facilities->getfacilities ( $taginfo ['facilities_id'] );
			if ($result ['is_master_facility'] == '1') {
				
				$tdata ['facilities_id'] = $result ['facilities_id'];
			} else {
				$tdata ['facilities_id'] = $this->customer->getId ();
			}
			
			// var_dump($tdata);
			// die;
			// $tdata['facilities_id'] = $this->customer->getId();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();				


			$this->model_resident_resident->tagmedication ( $this->request->post, $tdata );
			
			unset ( $this->session->data ['medication'] );
			
			$this->session->data ['success_add_form1'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}


			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
			}

			if($this->request->get['notes_id']!=null && $this->request->get['notes_id']!=""){

				$tags_medicine='';

			}else{

				$tags_medicine='2';

			}		
			
			$url2 .= '&tags_medicine='.$tags_medicine;
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->request->get ['facilities_id'] );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
		}		
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get ['userids'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}

		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		

		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function tagsmedicationsign2() {
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $facilities_id;
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$this->load->model ( 'api/temporary' );
			$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_tags_medication_id'] );
			
			$tempdata = array ();
			$tempdata = unserialize ( $temporary_info ['data'] );
			
			if ($tempdata ['room_id'] > 0) {
				$this->load->model ( 'setting/tags' );
				$this->model_setting_tags->updatetagroom ( $tempdata ['room_id'], $this->request->get ['tags_id'] );
			}
			
			$this->data ['tags_medicine'] = '2';
			
			$archive_tags_medication_id = $this->model_resident_resident->addTagsMedication ( $tempdata, $this->request->get ['tags_id'], $this->request->get ['updateMedication'], $facilities_id, $this->request->get ['addmedication'] );
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['archive_tags_medication_id'] = $archive_tags_medication_id;
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilityids'] = $this->request->get ['facilityids'];
			$tdata ['locationids'] = $this->request->get ['locationids'];
			$tdata ['tagsids'] = $this->request->get ['tagsids'];
			$tdata ['userids'] = $this->request->get ['userids'];
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			// $notes_id = $this->model_resident_resident->tagmedication2($this->request->post, $tdata);
			
			$this->load->model ( 'notes/notes' );
			$data = array ();
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $tdata ['facilities_id'] );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $tdata ['facilities_id'];
					$timezone_name = $tdata ['facilitytimezone'];
				}
			} else {
				$facilities_id = $tdata ['facilities_id'];
				$timezone_name = $tdata ['facilitytimezone'];
			}
			
			$timeZone = date_default_timezone_set ( $timezone_name );
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
			
			$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			if ($this->request->post ['imgOutput']) {
				$data ['imgOutput'] = $this->request->post ['imgOutput'];
			} else {
				$data ['imgOutput'] = $this->request->post ['signature'];
			}
			
			$data ['notes_pin'] = $this->request->post ['notes_pin'];
			$data ['user_id'] = $this->request->post ['user_id'];
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tdata ['tags_id'] );
			
			$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
			$data ['tags_id'] = $tag_info ['tags_id'];
			
			if ($tag_info ['emp_first_name']) {
				$emp_tag_id = $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'];
				
				$this->load->model ( 'setting/locations' );
				$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
				
				$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
			} else {
				$emp_tag_id = $tag_info ['emp_tag_id'];
			}
			
			if ($tag_info) {
				$medication_tags .= $emp_tag_id . ' ';
			}
			
			$notes_description = '';
			// $description .= $keywordData2['keyword_name'];
			// $description .= ' | ';
			// $description .= ' Completed for | '.date('h:i A',
			// strtotime($notetime)) .' ';
			
			if ($this->request->get ['addmedication'] != null && $this->request->get ['addmedication'] != "") {
				
				$notes_description .= ' Medication Form updated | ';
				// $data ['addmedication'] = $this->request->get ['addmedication'];
			} else {
				
				$notes_description .= ' Health Form updated | ';
			}
			
			$notes_description .= ' ' . $medication_tags;
			
			if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
				$notes_description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
			}
			
			// $description .= ' | ';
			
			// $data['notes_description'] = $keywordData2['keyword_name'].' | '.
			// $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' |
			// '.$medicationf . $comments;
			
			if ($this->request->get ['addmedication'] == "1") {
				
				$is_forms = '1';
			} else {
				
				$is_forms = '0';
			}
			
			$data ['notes_description'] = $notes_description;
			$data ['date_added'] = $date_added;
			$data ['note_date'] = $date_added;
			$data ['notetime'] = $notetime;
			$data ['addmedication'] = $is_forms;
			
			$this->model_notes_notes->updatetagsmedicinearchive1 ( $tdata ['tags_id'] );
			
			$this->load->model ( 'notes/notes' );
			
			$aids = array ();
			
			$alocationids = array ();
			
			$notes_description = $notes_description;
			
			if ($tdata ['locationids'] != null && $tdata ['locationids'] != "") {
				$sssssdds2 = explode ( ",", $tdata ['locationids'] );
				$abdcds = array_unique ( $sssssdds2 );
				$this->load->model ( 'setting/locations' );
				
				foreach ( $abdcds as $locationid ) {
					$location_info12 = $this->model_setting_locations->getlocation ( $locationid );
					$locationname = '|' . $location_info12 ['location_name'];
					$notes_description = str_ireplace ( $locationname, "", $notes_description );
					
					$locationname = '| ' . $location_info12 ['location_name'];
					$notes_description = str_ireplace ( $locationname, "", $notes_description );
					
					$aids [$location_info12 ['facilities_id']] ['locations'] [] = array (
							'valueId' => $locationid 
					);
				}
			}
			
			$atagsids = array ();
			if ($tdata ['tagsids'] != null && $tdata ['tagsids'] != "") {
				$this->load->model ( 'setting/tags' );
				$sssssddsd = explode ( ",", $tdata ['tagsids'] );
				$abdca = array_unique ( $sssssddsd );
				
				foreach ( $abdca as $tagsid ) {
					$tag_info = $this->model_setting_tags->getTag ( $tagsid );
					$empfirst_name = '|' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
					$notes_description = str_ireplace ( $empfirst_name, "", $notes_description );
					
					$empfirst_name = '| ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
					$notes_description = str_ireplace ( $empfirst_name, "", $notes_description );
					/*
					 * $atagsids[] = array(
					 * 'tags_id'=>$tagsid,
					 * 'facilities_id'=>$tag_info['facilities_id'],
					 * );
					 */
					
					$aids [$tag_info ['facilities_id']] ['clients'] [] = array (
							'valueId' => $tagsid 
					);
				}
			}
			
			if ($tdata ['facilityids'] != null && $tdata ['facilityids'] != "") {
				$this->load->model ( 'facilities/facilities' );
				$sssssddsg = explode ( ",", $tdata ['facilityids'] );
				$abdcg = array_unique ( $sssssddsg );
				foreach ( $abdcg as $fid ) {
					
					$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid );
					
					$notes_description = str_ireplace ( '|' . $facilityinfo ['facility'], "", $notes_description );
					$notes_description = str_ireplace ( '| ' . $facilityinfo ['facility'], "", $notes_description );
					
					$aids [$facilityinfo ['facilities_id']] ['facilitiesids'] [] = array (
							'valueId' => $fid 
					);
				}
			}
			
			if ($tdata ['userids'] != null && $tdata ['userids'] != "") {
				$this->load->model ( 'user/user' );
				$ssssssuser = explode ( ",", $tdata ['userids'] );
				$ssabdcg = array_unique ( $ssssssuser );
				
				foreach ( $ssabdcg as $usid ) {
					
					$userinfo = $this->model_user_user->getUser ( $usid );
					$notes_description = str_ireplace ( '|' . $userinfo ['username'], "", $notes_description );
					$notes_description = str_ireplace ( '| ' . $userinfo ['username'], "", $notes_description );
					$aids [$facilities_id] ['usersids'] [] = array (
							'valueId' => $usid 
					);
				}
			}
			
			$notesids = array ();
			
			if (! empty ( $aids )) {
				foreach ( $aids as $facilities_id => $aid ) {
					$data ['keyword_file1'] = array ();
					$data ['tags_id_list1'] = array ();
					$data ['locationsid'] = array ();
					$aidsss = array ();
					$aidsss1 = '';
					$locationname1 = "";
					if ($aid ['clients'] != null && $aid ['clients'] != "") {
						$tags_id_list = array ();
						foreach ( $aid ['clients'] as $clid ) {
							$tags_id_list [] = $clid ['valueId'];
						}
						
						$data ['tags_id_list1'] = $tags_id_list;
						
						$data ['notes_description'] = $notes_description;
					}
					
					if ($aid ['locations'] != null && $aid ['locations'] != "") {
						$locationsid = array ();
						foreach ( $aid ['locations'] as $locid ) {
							
							$location_info12 = $this->model_setting_locations->getlocation ( $locid ['valueId'] );
							$locationname1 .= $location_info12 ['location_name'] . ' | ';
							
							$locationsid [] = $locid ['valueId'];
						}
						$data ['locationsid'] = $locationsid;
						
						$data ['notes_description'] = $locationname1 . ' ' . $notes_description . ' ' . $comments;
					}
					
					if ($aid ['usersids'] != null && $aid ['usersids'] != "") {
						$usid = array ();
						foreach ( $aid ['usersids'] as $usercid ) {
							
							$user_info12 = $this->model_user_user->getUser ( $usercid ['valueId'] );
							$username1 .= $user_info12 ['username'] . ' | ';
							
							$usid [] = $usercid ['valueId'];
						}
						$data ['usid'] = $usid;
						
						$data ['notes_description'] = $username1 . ' ' . $notes_description . ' ' . $comments;
					}
					
					if ($this->request->get ['addmedication'] == "1") {
						
						$is_forms = '1';
					} else {
						
						$is_forms = '0';
					}
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					$data ['keyword_file'] = "";
					$data ['addmedication'] = $is_forms;
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
					
					$location_array [] = $notes_id;
					
					$this->load->model ( 'facilities/facilities' );
					$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					
					if ($facility ['is_enable_add_notes_by'] == '1') {
						$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql122 );
					}
					if ($facility ['is_enable_add_notes_by'] == '3') {
						$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql13 );
					}
					
					if ($facility ['is_enable_add_notes_by'] == '1') {
						if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
							
							$notes_file = $this->session->data ['local_notes_file'];
							$outputFolder = $this->session->data ['local_image_dir'];
							
							require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							$this->load->model ( 'notes/notes' );
							$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
							if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
								$this->model_notes_notes->updateuserverified ( '2', $notes_id );
							}
							
							if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
								$this->model_notes_notes->updateuserverified ( '1', $notes_id );
							}
							
							unlink ( $this->session->data ['local_image_dir'] );
							unset ( $this->session->data ['username_confirm'] );
							unset ( $this->session->data ['local_image_dir'] );
							unset ( $this->session->data ['local_image_url'] );
							unset ( $this->session->data ['local_notes_file'] );
						}
					}
					
					$archive_tags_medication_id = $tdata ['archive_tags_medication_id'];
					
					$mdata2 = array ();
					$mdata2 ['notes_id'] = $notes_id;
					$mdata2 ['tags_id'] = $tdata ['tags_id'];
					$mdata2 ['archive_tags_medication_id'] = $archive_tags_medication_id;
					
					$this->model_notes_notes->updatetagsmedicinearchive2 ( $mdata2 );
				}
			} else 

			if ($tdata ['facilityids'] != null && $tdata ['facilityids'] != "") {
				
				$sssssdds = explode ( ",", $tdata ['facilityids'] );
				
				$abdc = array_unique ( $sssssdds );
				
				if ($this->request->get ['addmedication'] == "1") {
					
					$is_forms = '1';
				} else {
					
					$is_forms = '0';
				}
				
				$data ['notes_description'] = $notes_description . " | " . $comments;
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				$data ['keyword_file'] = "";
				$data ['addmedication'] = $is_forms;
				foreach ( $abdc as $sssssd ) {
					
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $sssssd );
					$user_array [] = $notes_id;
					
					$this->load->model ( 'facilities/facilities' );
					$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					
					if ($facility ['is_enable_add_notes_by'] == '1') {
						$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql122 );
					}
					if ($facility ['is_enable_add_notes_by'] == '3') {
						$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql13 );
					}
					
					if ($facility ['is_enable_add_notes_by'] == '1') {
						if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
							
							$notes_file = $this->session->data ['local_notes_file'];
							$outputFolder = $this->session->data ['local_image_dir'];
							
							require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							$this->load->model ( 'notes/notes' );
							$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
							if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
								$this->model_notes_notes->updateuserverified ( '2', $notes_id );
							}
							
							if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
								$this->model_notes_notes->updateuserverified ( '1', $notes_id );
							}
							
							unlink ( $this->session->data ['local_image_dir'] );
							unset ( $this->session->data ['username_confirm'] );
							unset ( $this->session->data ['local_image_dir'] );
							unset ( $this->session->data ['local_image_url'] );
							unset ( $this->session->data ['local_notes_file'] );
						}
					}
					
					$archive_tags_medication_id = $tdata ['archive_tags_medication_id'];
					
					$mdata2 = array ();
					$mdata2 ['notes_id'] = $notes_id;
					$mdata2 ['tags_id'] = $tdata ['tags_id'];
					$mdata2 ['archive_tags_medication_id'] = $archive_tags_medication_id;
					
					$this->model_notes_notes->updatetagsmedicinearchive2 ( $mdata2 );
				}
				
				$notesids1 = implode ( ",", $notesids );
				$url2 = '&notes_ids=' . $notesids1;
			} else {
				
				if ($this->request->get ['addmedication'] == "1") {
					
					$is_forms = '1';
				} else {
					
					$is_forms = '0';
				}
				
				$data ['notes_description'] = $notes_description . " | " . $comments;
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				$data ['keyword_file'] = "";
				$data ['addmedication'] = $is_forms;
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->customer->getId () );
				$facility_array [] = $notes_id;
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				if ($facility ['is_enable_add_notes_by'] == '1') {
					$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql122 );
				}
				if ($facility ['is_enable_add_notes_by'] == '3') {
					$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql13 );
				}
				
				if ($facility ['is_enable_add_notes_by'] == '1') {
					if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
						
						$notes_file = $this->session->data ['local_notes_file'];
						$outputFolder = $this->session->data ['local_image_dir'];
						
						require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						$this->load->model ( 'notes/notes' );
						$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
						if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
							$this->model_notes_notes->updateuserverified ( '2', $notes_id );
						}
						
						if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
							$this->model_notes_notes->updateuserverified ( '1', $notes_id );
						}
						
						unlink ( $this->session->data ['local_image_dir'] );
						unset ( $this->session->data ['username_confirm'] );
						unset ( $this->session->data ['local_image_dir'] );
						unset ( $this->session->data ['local_image_url'] );
						unset ( $this->session->data ['local_notes_file'] );
					}
				}
				
				$archive_tags_medication_id = $fdata ['archive_tags_medication_id'];
				
				$mdata2 = array ();
				$mdata2 ['notes_id'] = $notes_id;
				$mdata2 ['tags_id'] = $tdata ['tags_id'];
				$mdata2 ['archive_tags_medication_id'] = $archive_tags_medication_id;
				$this->model_notes_notes->updatetagsmedicinearchive2 ( $mdata2 );
			}
			
			if ($facility_array != "" && $facility_array != "") {
				
				$result = array_merge ( $facility_array );
			}
			
			if ($location_array != "" && $location_array != "") {
				
				$result = array_merge ( $location_array );
			}
			
			if ($user_array != "" && $user_array != "") {
				
				$result = array_merge ( $user_array );
			}
			foreach ( $result as $notes_id ) {
				
				$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $result );
			}
			
			$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_tags_medication_id'] );
			
			$this->session->data ['success_add_form1'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['addmedication'] != null && $this->request->get ['addmedication'] != "") {
				$url2 .= '&addmedication=' . $this->request->get ['addmedication'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
			}
			
			/*
			 * if($this->request->get['updateMedication']!=null && $this->request->get['updateMedication']!=""){
			 * $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/updateMedication', '' . $url2, 'SSL')));
			 *
			 * }else{
			 * $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL')));
			 *
			 * }
			 */
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				
				$tags_medicine = '';
			} else {
				
				$tags_medicine = '2';
			}
			
			$url2 .= '&tags_medicine=' . $tags_medicine;
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if ($this->request->get ['updateMedication'] != null && $this->request->get ['updateMedication'] != "") {
			$url2 .= '&updateMedication=' . $this->request->get ['updateMedication'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get ['userids'];
		}
		
		if ($this->request->get ['addmedication'] != null && $this->request->get ['addmedication'] != "") {
			$url2 .= '&addmedication=' . $this->request->get ['addmedication'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['archive_tags_medication_id'] != null && $this->request->get ['archive_tags_medication_id'] != "") {
			$url2 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign2', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		/*
		 * if (isset($this->request->post['comments'])) {
		 * $this->data['comments'] = $this->request->post['comments'];
		 * } else {
		 * $this->data['comments'] = '';
		 * }
		 */
		
		if (isset ( $this->session->data ['session_notes_description'] )) {
			$this->data ['comments'] = $this->session->data ['session_notes_description'];
			
			unset ( $this->session->data ['session_notes_description'] );
		} else if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function medicineautocomplete() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if (utf8_strlen ( $this->request->get ['medicine_filter_name'] ) > 3) {
			
			// $medicineUrl =
			// 'https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search='.$this->request->get['medicine_filter_name'].'&limit=1';Albuterol%20Sulfate
			// $json_url =
			// "https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search=brand_name:".$this->request->get['medicine_filter_name'];
			$json_url = "https://dailymed.nlm.nih.gov/dailymed/autocomplete.cfm?key=search&returntype=json&term=" . $this->request->get ['medicine_filter_name'];
			$json = file_get_contents ( $json_url );
			$data = json_decode ( $json, TRUE );
			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			
			$json = array ();
			foreach ( $data as $obj ) {
				foreach ( $obj as $a ) {
					$json [] = array (
							'generic_name' => '',
							'brand_name' => $a 
					);
				}
			}
			
			/*
			 * foreach($data as $obj){
			 * foreach($obj[0]['patient']['drug'] as $a){
			 * $json[] = array(
			 * 'generic_name' =>'',
			 * 'brand_name' => implode(",",$a['openfda']['brand_name']),
			 * );
			 * }
			 * }
			 */
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function activenote() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['keyword_id'] = $this->request->get ['keyword_id'];
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			$this->model_resident_resident->activenote ( $this->request->post, $tdata );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
				$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
			}
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
				$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
			$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			$url2 .= '&clientactivenote=1';
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/activenote', '' . $url2, 'SSL' ) );
		}
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function allrolecallsign() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			
			$tdata ['tagsids'] = $this->session->data ['tagsids'];
			$tdata ['role_calls'] = $this->session->data ['role_calls'];
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			$tdata ['facility_inout'] = $this->request->get ['facility_inout'];
			$tdata ['tags_ids'] = $this->request->get ['tags_ids'];
			// $tdata['escort_user_ids'] = $this->request->get['escort_user_ids'];
			
			$this->model_resident_resident->allrolecallsign ( $this->request->post, $tdata );
			
			unset ( $this->session->data ['role_calls'] );
			unset ( $this->session->data ['tagsids'] );
			
			$this->session->data ['success_update_form_2'] = 'Role call updated';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['facility_inout'] != null && $this->request->get ['facility_inout'] != "") {
				
				$url2 .= "&facility_inout=" . $this->request->get ['facility_inout'];
			}
			
			if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
				
				$url2 .= "&tags_ids=" . $this->request->get ['tags_ids'];
			}
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				
				$url2 .= "&tagsids=" . $this->request->get ['tagsids'];
			}
			/*
			 * if ($this->request->get['all_roll_call'] != null &&
			 * $this->request->get['all_roll_call'] != "") {
			 * $url2 .= '&all_roll_call=' .
			 * $this->request->get['all_roll_call'];
			 * }
			 */
			// $this->redirect(str_replace('&amp;', '&',
			// $this->url->link('resident/resident', '', 'SSL')));
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['all_roll_call'] != null && $this->request->get ['all_roll_call'] != "") {
			$url2 .= '&all_roll_call=' . $this->request->get ['all_roll_call'];
		}
		
		if ($this->request->get ['facility_inout'] != null && $this->request->get ['facility_inout'] != "") {
			
			$url2 .= "&facility_inout=" . $this->request->get ['facility_inout'];
		}
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			
			$url2 .= "&tags_ids=" . $this->request->get ['tags_ids'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			
			$url2 .= "&tagsids=" . $this->request->get ['tagsids'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
		// $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if ($this->request->get ['tags_ids'] != "" && $this->request->get ['tags_ids'] != null) {
			$this->data ['show_escort'] = "1";
		} else {
			$this->data ['show_escort'] = "0";
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->session->data ['success_update_form_2'] )) {
			$this->data ['success_update_form_2'] = $this->session->data ['success_update_form_2'];
			
			unset ( $this->session->data ['success_update_form_2'] );
		} else {
			$this->data ['success_update_form_2'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['escort_user_id'] )) {
			$this->data ['error_escort_user_id'] = $this->error ['escort_user_id'];
		} else {
			$this->data ['error_escort_user_id'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		// $this->load->model('setting/tags');
		// $tag_info =
		// $this->model_setting_tags->getTag($this->request->get['tags_id']);
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			$escort_user_ids = $this->request->post ['escort_user_ids'];
		} else {
			$escort_user_ids = array ();
		}
		
		$this->data ['totalusers'] = array ();
		$this->load->model ( 'user/user' );
		
		foreach ( $escort_user_ids as $user_id ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $user_id );
			
			if ($user_info) {
				$this->data ['totalusers'] [] = array (
						'username' => $user_info ['username'],
						'user_id' => $user_info ['user_id'] 
				);
			}
		}
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			
			$this->data ['escort_user_ids'] = $this->request->post ['escort_user_ids'];
		} else {
			
			$this->data ['escort_user_ids'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		$this->load->model ( 'notes/notes' );
		
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
						'customlistvalues_name' => $custom_info ['customlistvalues_name'],
						'required' => $custom_info ['required'] 
				);
			}
		}
		
		if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
			
			$d = array ();
			
			$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
			
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
					
					foreach ( $customlistvalues as $value ) {
						
						$this->data ['customlistvalues_ids'] [] = array (
								'user_id' => $value ['customlistvalues_id'],
								'customlistvalues_name' => $value ['customlistvalues_name'],
								'required' => $value ['required'] 
						);
					}
				}
			}
			
			$this->data ['id_url'] .= '&facilities_id=' . $this->customer->getId ();
		}
		
		if (isset ( $this->request->post ['tags_ids'] )) {
			$tagides1 = $this->request->post ['tags_ids'];
		} elseif (! empty ( $this->request->get ['tags_ids'] )) {
			$tagides1 = $this->request->get ['tags_ids'];
			$this->data ['is_multiple_tags_count'] = '1';
		} else {
			$tagides1 = array ();
		}
		
		$sssssdd = explode ( ",", $tagides1 );
		
		$this->data ['tags_ids'] = array ();
		$this->load->model ( 'setting/tags' );
		
		foreach ( $sssssdd as $tagsid ) {
			
			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
						'tags_id' => $tagsid,
						'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'] 
				);
			}
		}
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function rolecallsign2() {
		$facilities_id = "";
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
			;
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		// // var_dump($this->$request->get['role_call']);
		// dir;
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		// // var_dump($datafa['facilities_id']);
		// die;
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['discharge'] = $this->request->get ['discharge'];
			$tdata ['in_out_input'] = $this->request->get ['in_out_input'];
			
			if ($this->request->get ['default_facility_id'] != null && $this->request->get ['default_facility_id'] != "") {
				$tdata ['facilities_id'] = $this->request->get ['default_facility_id'];
			} else {
				$tdata ['facilities_id'] = $this->request->get ['facilities_id'];
			}
			
			$tdata ['default_facility_id'] = $this->request->get ['default_facility_id'];
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			
			$this->model_resident_resident->rolecallsign ( $this->request->post, $tdata );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
				$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
			}
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
				$url2 .= '&role_call=' . $this->request->get ['role_call'];
			}
			if ($this->request->get ['discharge'] != null && $this->request->get ['discharge'] != "") {
				$url2 .= '&discharge=' . $this->request->get ['discharge'];
			}
			if ($this->request->get ['in_out_input'] != null && $this->request->get ['in_out_input'] != "") {
				$url2 .= '&in_out_input=' . $this->request->get ['in_out_input'];
			}
			
			if ($this->request->get ['default_facility_id'] != null && $this->request->get ['default_facility_id'] != "") {
				$url2 .= '&default_facility_id=' . $this->request->get ['default_facility_id'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $facilities_id 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
			$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
			$url2 .= '&role_call=' . $this->request->get ['role_call'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->get ['discharge'] != null && $this->request->get ['discharge'] != "") {
			$url2 .= '&discharge=' . $this->request->get ['discharge'];
		}
		if ($this->request->get ['in_out_input'] != null && $this->request->get ['in_out_input'] != "") {
			$url2 .= '&in_out_input=' . $this->request->get ['in_out_input'];
		}
		
		if ($this->request->get ['default_facility_id'] != null && $this->request->get ['default_facility_id'] != "") {
			$url2 .= '&default_facility_id=' . $this->request->get ['default_facility_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/rolecallsign2', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->error ['escort_user_id'] )) {
			$this->data ['error_escort_user_id'] = $this->error ['escort_user_id'];
		} else {
			$this->data ['error_escort_user_id'] = '';
		}
		
		if (isset ( $this->request->get ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->get ['in_out_input'] )) {
			$this->data ['in_out_input'] = $this->request->get ['in_out_input'];
		} else {
			$this->data ['in_out_input'] = '';
		}
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			$escort_user_ids = $this->request->post ['escort_user_ids'];
		} else {
			$escort_user_ids = array ();
		}
		
		$this->data ['totalusers'] = array ();
		$this->load->model ( 'user/user' );
		
		foreach ( $escort_user_ids as $user_id ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $user_id );
			
			if ($user_info) {
				$this->data ['totalusers'] [] = array (
						'username' => $user_info ['username'],
						'user_id' => $user_info ['user_id'] 
				);
			}
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
		
		if (isset ( $this->error ['comments'] )) {
			$this->data ['error_comments'] = $this->error ['comments'];
		} else {
			$this->data ['error_comments'] = '';
		}
		
		if ($this->request->get ['in_out_input'] == 0 || $this->request->get ['in_out_input'] == 1) {
			$this->data ['show_escort'] = "1";
		} else {
			$this->data ['show_escort'] = "0";
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
		
		if (isset ( $this->request->post ['new_module'] )) {
			$this->data ['new_module'] = $this->request->post ['new_module'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['new_module'] = $notes_info ['new_module'];
		} else {
			$this->data ['new_module'] = '';
		}
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		/*
		 * if (isset($this->request->post['customlistvalues_ids'])) {
		 * $customlistvalues_ids1 = $this->request->post['customlistvalues_ids'];
		 * } else {
		 * $customlistvalues_ids1 = array();
		 * }
		 */
		if (isset ( $this->request->post ['new_module'] )) {
			$customlistvalues_ids1 = $this->request->post ['new_module'];
		} else {
			$customlistvalues_ids1 = array ();
		}
		
		/*
		 * $this->data['customlistvalues_ids'] = array();
		 * $this->load->model('notes/notes');
		 *
		 * foreach ($customlistvalues_ids1 as $customlistvalues_id) {
		 *
		 * $custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
		 *
		 * if ($custom_info) {
		 * $this->data['customlistvalues_ids'][] = array(
		 * 'user_id' => $customlistvalues_id,
		 * 'customlistvalues_name' => $custom_info['customlistvalues_name'],
		 * 'required' => $custom_info['required']
		 * );
		 * }
		 * }
		 */
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$this->load->model ( 'notes/notes' );
		
		if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
			
			$d = array ();
			
			$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
			
			$customlists = $this->model_notes_notes->getcustomlists ( $d );
			
			// var_dump($customlists);
			// die;
			
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
					
					foreach ( $customlistvalues as $value ) {
						
						$this->data ['customlistvalues_ids'] [] = array (
								'user_id' => $value ['customlistvalues_id'],
								'customlistvalues_name' => $value ['customlistvalues_name'],
								'required' => $value ['required'] 
						);
					}
				}
			}
			
			// var_dump($this->data['customlists']);
			// die;
			
			$this->data ['id_url'] .= '&facilities_id=' . $facilities_id;
		}
		
		if ($this->request->get ['discharge'] == "1") {
			
			$this->load->model ( 'createtask/createtask' );
			$alldata = $this->model_createtask_createtask->getalltaskbyid ( $this->request->get ['tags_id'] );
			if ($alldata != NULL && $alldata != "") {
				$this->data ['error_message'] = "All data related to this client will be deleted! Are you sure you want to discharge this client?";
				$this->data ['confirm_alert'] = "1";
				$this->data ['confirm_alert2'] = "1";
			}
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function getstickynote() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		$this->data ['tags_id'] = $this->request->get ['tags_id'];
		$this->data ['close'] = $this->request->get ['close'];
		
		if ($this->request->post ['tags_id'] != NULL && $this->request->post ['tags_id'] != "") {
			
			$this->load->model ( 'setting/tags' );
			$this->model_setting_tags->updateSticky ( $this->request->post );
		}
		
		if ($this->request->get ['clear'] == "1") {
			$this->load->model ( 'setting/tags' );
			$this->model_setting_tags->updateStickyclear ( $this->request->get ['tags_id'] );
		}
		
		$this->load->model ( 'setting/tags' );
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$stickyinfo = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			$this->data ['stickyinfo'] = $stickyinfo ['stickynote'];
		}
		
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/getstickynote', '' . $url2, 'SSL' ) );
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/stickynote.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function residentstatus() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForms ()) {
			
			$url2 = "";
			if (isset ( $this->request->post ['taskid'] )) {
				$url2 .= '&taskids=' . implode ( ",", $this->request->post ['taskid'] );
			}
			
			if (isset ( $this->request->post ['formid'] )) {
				$url2 .= '&formids=' . implode ( ",", $this->request->post ['formid'] );
			}
			if (isset ( $this->request->post ['notes_id'] )) {
				$url2 .= '&notesids=' . implode ( ",", $this->request->post ['notes_id'] );
			}
			
			if (isset ( $this->request->post ['childstatus'] )) {
				$url2 .= '&childstatus=' . $this->request->post ['childstatus'];
			}
			if (isset ( $this->request->get ['tags_id'] )) {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->session->data ['success2'] = 'Status updated Successfully! ';
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&residentstatussign=1';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatussign', '' . $url2, 'SSL' ) );
			}
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		if (isset ( $this->request->post ['taskid'] )) {
			$this->data ['taskid'] = $this->request->post ['taskid'];
		} else {
			$this->data ['taskid'] = array ();
		}
		
		if (isset ( $this->request->post ['formid'] )) {
			$this->data ['formid'] = $this->request->post ['formid'];
		} else {
			$this->data ['formid'] = array ();
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$currentdate = date ( 'Y-m-d' );
		
		$data = array (
				'currentdate' => $currentdate,
				'tags_id' => $this->request->get ['tags_id'] 
		);
		
		$this->load->model ( 'resident/resident' );
		$task_infos = $this->model_resident_resident->getResidentstatus ( $data );
		
		$totaltask_infos = $this->model_resident_resident->getTotalResidentstatus ( $data );
		
		
		$this->load->model('api/permision');
		$timeinfo = $this->model_api_permision->getcustomerdatetime($this->customer->getId ());
		
		foreach ( $task_infos as $taskinfo ) {
			
			$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $taskinfo ['tagstatus_id'] );
			$this->data ['task_info'] [] = array (
					'tasktype' => $taskinfo ['tasktype'],
					'date_added' => date ( $timeinfo['date_format'], strtotime ( $taskinfo ['date_added'] ) ),
					'description' => $taskinfo ['description'],
					'assign_to' => $taskinfo ['assign_to'],
					'task_time' => date ( $timeinfo['time_format'], strtotime ( $taskinfo ['task_time'] ) ),
					'task_date' => date ( $timeinfo['date_format'], strtotime ( $taskinfo ['task_date'] ) ),
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
			
			$this->data ['form_info'] [] = array (
					'form_description' => $formdata ['form_description'],
					'date_added' => date ( $timeinfo['date_format'], strtotime ( $formdata ['date_added'] ) ),
					'count' => $totalform_infos,
					'forms_id' => $formdata ['forms_id'],
					'tagstatus_id' => $tagstatus_info ['status'] 
			);
		}
		
		$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $this->request->get ['tags_id'] );
		
		// var_dump($tagstatusinfo);
		
		$this->data ['tagstatus_info'] = $this->request->get ['classification_name'];
		
		// var_dump($this->data['tagstatus_info']);
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$currentdate2 = date ( 'd-m-Y' );
		
		$this->load->model ( 'createtask/createtask' );
		$tasksinfo = $this->model_createtask_createtask->getTaskas ( $this->request->get ['tags_id'], $currentdate2 );
		
		$this->data ['tasksinfo1'] = $tasksinfo * 100;
		
		// var_dump($tasksinfo1);
		
		$this->load->model ( 'setting/tags' );
		$this->data ['taginfo'] = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		$data = array (
				'sort' => $sort,
				'order' => $order,
				'searchdate' => $searchdate,
				'searchdate_app' => '1',
				'tagstatus_id' => '1',
				'emp_tag_id' => $this->request->get ['tags_id'],
				'facilities_id' => $this->customer->getId (),
				'customer_key' => $this->session->data ['webcustomer_key'],
				'start' => 0,
				'limit' => 500 
		);
		
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'user/user' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'notes/tags' );
		
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		
		// var_dump($notes_total);
		
		$this->load->model ( 'notes/notes' );
		$last_notesID = $this->model_notes_notes->getLastNotesID ( $this->customer->getId (), $searchdate );
		
		$this->data ['last_notesID'] = $last_notesID ['notes_id'];
		
		// var_dump($data);
		
		$results = $this->model_notes_notes->getnotess ( $data );
		
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		// var_dump($facilityinfo);
		
		foreach ( $results as $result ) {
			
			if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
				$userPin = $result ['notes_pin'];
			} else {
				$userPin = '';
			}
			
			$this->data ['notess'] [] = array (
					'notes_id' => $result ['notes_id'],
					'notes_description' => $result ['notes_description'],
					'notetime' => date ( $timeinfo['time_format'], strtotime ( $result ['notetime'] ) ),
					'username' => $result ['user_id'],
					'notes_pin' => $userPin,
					'signature' => $result ['signature'],
					'note_date' => date ( $timeinfo['date_format'], strtotime ( $result ['note_date'] ) ) 
			);
		}
		
		$this->data ['redirect_url2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatussign', '' . $url2, 'SSL' ) );
		
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatus', '' . $url2, 'SSL' ) );
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/residentstatus.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm23() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
		
		if ($this->request->post ['new_module'] != null && $this->request->post ['new_module'] != "" && $this->request->post ['comments'] == "") {
			
			foreach ( $this->request->post ['new_module'] as $key => $new_module ) {
				
				if ($new_module ['checkin'] == '1') {
					// var_dump($new_module['required']);
					
					if ($new_module ['required'] == "1") {
						$this->error ['comments'] = $this->language->get ( 'error_required' );
					}
				}
			}
			
			// die;
		}
		
		
		if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
			$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ($facilities_id);
		if ($facility ['enable_escorted'] == '1') {
			if (isset ( $this->request->get ['in_out_input'] ) || isset ( $this->request->get ['tags_ids'] ) || isset ( $this->request->get ['tag_status_id'] )) {
				if (empty ( $this->request->post ['escort_user_ids'] )) {
					$this->error ['escort_user_id'] = "Please enter escort name.";
				}
			}
		}
		 
		
		if ($this->request->get ['tag_status_id'] != '' && $this->request->get ['tag_status_id'] != null) {
			if ($this->request->get ['tags_id'] != '' && $this->request->get ['tags_id'] != null) {
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
				if ($tag_info ['role_call'] == $this->request->get ['tag_status_id']) {
					$this->error ['warning'] = $tag_info ['emp_tag_id'] . " already in the " . $this->request->get ['name'];
				}
			}
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
		
		if ($this->request->post ['select_one'] == '1') {
			if ($this->request->post ['notes_pin'] == '') {
				$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				
				if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
					$user_info = $this->model_user_user->getUserByUsername ( $this->request->post ['user_id'] );
				} else {
					$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $this->customer->getId () );
				}
				
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
	protected function validateForms() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if (($this->request->post ['formid'] == "" && $this->request->post ['formid'] == "") && ($this->request->post ['taskid'] == "" && $this->request->post ['taskid'] == "")) {
			$this->error ['warning'] = "This is required field!";
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function residentstatussign() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['childstatus'] = $this->request->get ['childstatus'];
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			$this->model_resident_resident->residentstatussign ( $this->request->post, $tdata );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['tags_forms_id'] != null && $this->request->get ['tags_forms_id'] != "") {
				$url2 .= '&tags_forms_id=' . $this->request->get ['tags_forms_id'];
			}
			if ($this->request->get ['notesids'] != null && $this->request->get ['notesids'] != "") {
				$url2 .= '&notesids=' . $this->request->get ['notesids'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
			$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['tags_forms_id'] != null && $this->request->get ['tags_forms_id'] != "") {
			$url2 .= '&tags_forms_id=' . $this->request->get ['tags_forms_id'];
		}
		
		if ($this->request->get ['taskids'] != null && $this->request->get ['taskids'] != "") {
			$url2 .= '&taskids=' . $this->request->get ['taskids'];
		}
		
		if ($this->request->get ['formids'] != null && $this->request->get ['formids'] != "") {
			$url2 .= '&formids=' . $this->request->get ['formids'];
		}
		
		if ($this->request->get ['childstatus'] != null && $this->request->get ['childstatus'] != "") {
			$url2 .= '&childstatus=' . $this->request->get ['childstatus'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatussign', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function masterfacility() {
		$this->load->model ( 'facilities/facilities' );
		$result = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$ddss = array ();
		if ($result ['client_facilities_ids'] != null && $result ['client_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $result ['client_facilities_ids'];
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		$ddss [] = $this->customer->getId ();
		$sssssdd = implode ( ",", $ddss );
		
		$dataaaa = array ();
		$dataaaa ['facilities'] = $sssssdd;
		$mfacilities = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );
		
		$masterfacilities = array ();
		foreach ( $mfacilities as $mfacility ) {
			$masterfacilities [] = array (
					'name' => $mfacility ['facility'],
					'facilities_id' => $mfacility ['facilities_id'],
					'href' => str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident&search_facilities_id=' . $mfacility ['facilities_id'], '', 'SSL' ) ) 
			);
		}
		
		$this->data ['masterfacilities'] = $masterfacilities;
		
		$this->data ['reseturl'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident&searchall=1', '', 'SSL' ) );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/master.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function clientfile() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['notes_file'] = $this->request->get ['notes_file'];
			$tdata ['extention'] = $this->request->get ['extention'];
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			
			$this->model_resident_resident->clientfile ( $this->request->post, $tdata );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['tags_forms_id'] != null && $this->request->get ['tags_forms_id'] != "") {
				$url2 .= '&tags_forms_id=' . $this->request->get ['tags_forms_id'];
			}
			if ($this->request->get ['notesids'] != null && $this->request->get ['notesids'] != "") {
				$url2 .= '&notesids=' . $this->request->get ['notesids'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
			$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['tags_forms_id'] != null && $this->request->get ['tags_forms_id'] != "") {
			$url2 .= '&tags_forms_id=' . $this->request->get ['tags_forms_id'];
		}
		
		if ($this->request->get ['taskids'] != null && $this->request->get ['taskids'] != "") {
			$url2 .= '&taskids=' . $this->request->get ['taskids'];
		}
		
		if ($this->request->get ['formids'] != null && $this->request->get ['formids'] != "") {
			$url2 .= '&formids=' . $this->request->get ['formids'];
		}
		
		if ($this->request->get ['notes_file'] != null && $this->request->get ['notes_file'] != "") {
			$url2 .= '&notes_file=' . $this->request->get ['notes_file'];
		}
		if ($this->request->get ['extention'] != null && $this->request->get ['extention'] != "") {
			$url2 .= '&extention=' . $this->request->get ['extention'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/clientfile', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function getMedicationtype() {
		$json = array ();
		$this->load->model ( 'medicationtype/medicationtype' );
		$results = $this->model_medicationtype_medicationtype->getmedicationtype ( $this->request->get ['medicationtype_id'] );
		if ($results ['measurement_type'] != null && $results ['measurement_type'] != '') {
			$measurement_type = explode ( ',', $results ['measurement_type'] );
		} else {
			$measurement_type = array ();
		}
		$json = array (
				
				'measurement_type' => $measurement_type,
				'type' => $results ['type'] 
		);
		
		$this->response->setOutput ( json_encode ( $json ) );
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
	public function updateclientstatussign() {
		$facilities_id = "";
		$tag_status_id = "";
		$name = "";
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
			;
		}
		
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			
			$tag_status_id = $this->request->get ['tag_status_id'];
		}
		
		if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
			
			$name = $this->request->get ['name'];
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		// // var_dump($this->$request->get['role_call']);
		// dir;
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		
		// // var_dump($datafa['facilities_id']);
		// die;
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			
			$tdata = array ();
			$tdata ['tag_status_id'] = $tag_status_id;
			$tdata ['name'] = $name;
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['tagsids'] = $this->request->get ['tagsids'];
			$tdata ['discharge'] = $this->request->get ['discharge'];
			if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
				$tdata ['role_call'] = $this->request->get ['role_call'];
			} else if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$tdata ['role_call'] = $this->request->get ['tag_status_id'];
			}
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			
			$this->model_resident_resident->updateclientstatussign ( $this->request->post, $tdata );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			/*
			 * if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
			 * $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
			 * }
			 */
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
				$url2 .= '&name=' . $this->request->get ['name'];
			}
			/*
			 * if ($this->request->get['role_call'] != null && $this->request->get['role_call'] != "") {
			 * $url2 .= '&role_call=' . $this->request->get['role_call'];
			 * }
			 * if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
			 * $url2 .= '&discharge=' . $this->request->get['discharge'];
			 * }
			 */
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $facilities_id 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
			$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
			$url2 .= '&name=' . $this->request->get ['name'];
		}
		/*
		 * if ($this->request->get['role_call'] != null && $this->request->get['role_call'] != "") {
		 * $url2 .= '&role_call=' . $this->request->get['role_call'];
		 * }
		 */
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		
		/*
		 * if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
		 * $url2 .= '&discharge=' . $this->request->get['discharge'];
		 * }
		 */
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if ($this->request->get ['tag_status_id'] != "" && $this->request->get ['tag_status_id'] != null) {
			$this->data ['show_escort'] = "1";
		} else {
			$this->data ['show_escort'] = "0";
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		if (isset ( $this->error ['escort_user_id'] )) {
			$this->data ['error_escort_user_id'] = $this->error ['escort_user_id'];
		} else {
			$this->data ['error_escort_user_id'] = '';
		}
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			$escort_user_ids = $this->request->post ['escort_user_ids'];
		} else {
			$escort_user_ids = array ();
		}
		
		$this->data ['totalusers'] = array ();
		$this->load->model ( 'user/user' );
		
		foreach ( $escort_user_ids as $user_id ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $user_id );
			
			if ($user_info) {
				$this->data ['totalusers'] [] = array (
						'username' => $user_info ['username'],
						'user_id' => $user_info ['user_id'] 
				);
			}
		}
		
		if (isset ( $this->error ['comments'] )) {
			$this->data ['error_comments'] = $this->error ['comments'];
		} else {
			$this->data ['error_comments'] = '';
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
		
		if (isset ( $this->request->post ['new_module'] )) {
			$this->data ['new_module'] = $this->request->post ['new_module'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['new_module'] = $notes_info ['new_module'];
		} else {
			$this->data ['new_module'] = '';
		}
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		if (isset ( $this->request->post ['new_module'] )) {
			$customlistvalues_ids1 = $this->request->post ['new_module'];
		} else {
			$customlistvalues_ids1 = array ();
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$this->load->model ( 'notes/notes' );
		
		if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
			
			$d = array ();
			
			$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
			
			$customlists = $this->model_notes_notes->getcustomlists ( $d );
			
			// var_dump($customlists);
			// die;
			
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
					
					foreach ( $customlistvalues as $value ) {
						
						$this->data ['customlistvalues_ids'] [] = array (
								'user_id' => $value ['customlistvalues_id'],
								'customlistvalues_name' => $value ['customlistvalues_name'],
								'required' => $value ['required'] 
						);
					}
				}
			}
			
			// var_dump($this->data['customlists']);
			// die;
			
			$this->data ['id_url'] .= '&facilities_id=' . $facilities_id;
		}
		
		if ($this->request->get ['discharge'] == "1") {
			
			$this->load->model ( 'createtask/createtask' );
			$alldata = $this->model_createtask_createtask->getalltaskbyid ( $this->request->get ['tags_id'] );
			if ($alldata != NULL && $alldata != "") {
				$this->data ['error_message'] = "All data related to this client will be deleted! Are you sure you want to discharge this client?";
				$this->data ['confirm_alert'] = "1";
				$this->data ['confirm_alert2'] = "1";
			}
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function updateclientstatussigns() {
		
		// var_dump($this->session->data);die;
		if ($this->request->get ['facilities_id'] != "" && $this->request->get ['facilities_id'] != null) {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		if ($this->request->get ['keyword_id'] != "" && $this->request->get ['keyword_id'] != null) {
			
			$keyword_id = $this->request->get ['keyword_id'];
		} else {
			
			$keyword_id = "";
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			
			$sssssdd = explode ( ",", $this->request->get ['tags_ids'] );
			
			foreach ( $sssssdd as $tags_id ) {
				
				$tdata ['tagsids'] = $this->request->get ['tags_ids'];
				$tdata ['tags_id'] = $tags_id;
				$tdata ['role_calls'] = "";
				$tdata ['tag_status_id'] = $this->request->get ['tag_status_id'];
				$tdata ['name'] = $this->request->get ['name'];
				$tdata ['facilities_id'] = $facilities_id;
				$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
				
				$notes_id = $this->model_resident_resident->allclientstatussigns ( $this->request->post, $tdata );
				
				// var_dump($notes_id);
				
				$notes_ids [] = $notes_id;
				
			}
			
			
			unset($this->session->data ['mfacilities_id']);
			unset($this->session->data ['movement_room']);
			
			$this->session->data ['success_update_form_2'] = 'Status updated';
			
			foreach ( $notes_ids as $notes_id ) {
				
				if ($keyword_id != null || $keyword_id != "") {
					
					$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_ids );
				}
			}
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			/*
			 * if ($this->request->get['all_roll_call'] != null &&
			 * $this->request->get['all_roll_call'] != "") {
			 * $url2 .= '&all_roll_call=' .
			 * $this->request->get['all_roll_call'];
			 * }
			 */
			// $this->redirect(str_replace('&amp;', '&',
			// $this->url->link('resident/resident', '', 'SSL')));
		}
		
		// die;
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['all_roll_call'] != null && $this->request->get ['all_roll_call'] != "") {
			$url2 .= '&all_roll_call=' . $this->request->get ['all_roll_call'];
		}
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
			$url2 .= '&name=' . $this->request->get ['name'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussigns', '' . $url2, 'SSL' ) );
		
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if ($keyword_id != null || $keyword_id != "") {
			$this->data ['keyword_id'] = $keyword_id;
		} else {
			$this->data ['keyword_id'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->session->data ['session_notes_description'] )) {
			$this->data ['comments'] = $this->session->data ['session_notes_description'];
			
			unset ( $this->session->data ['session_notes_description'] );
		} else if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		if (isset ( $this->session->data ['success_update_form_2'] )) {
			$this->data ['success_update_form_2'] = $this->session->data ['success_update_form_2'];
			
			unset ( $this->session->data ['success_update_form_2'] );
		} else {
			$this->data ['success_update_form_2'] = '';
		}
		
		if ($this->request->get ['tags_ids'] != "" && $this->request->get ['tags_ids'] != null) {
			$this->data ['show_escort'] = "1";
		} else {
			$this->data ['show_escort'] = "0";
		}
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			$escort_user_ids = $this->request->post ['escort_user_ids'];
		} else {
			$escort_user_ids = array ();
		}
		
		$this->data ['totalusers'] = array ();
		$this->load->model ( 'user/user' );
		
		foreach ( $escort_user_ids as $user_id ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $user_id );
			
			if ($user_info) {
				$this->data ['totalusers'] [] = array (
						'username' => $user_info ['username'],
						'user_id' => $user_info ['user_id'] 
				);
			}
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['escort_user_id'] )) {
			$this->data ['error_escort_user_id'] = $this->error ['escort_user_id'];
		} else {
			$this->data ['error_escort_user_id'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		// $this->load->model('setting/tags');
		// $tag_info =
		// $this->model_setting_tags->getTag($this->request->get['tags_id']);
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		$this->load->model ( 'notes/notes' );
		
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
						'customlistvalues_name' => $custom_info ['customlistvalues_name'],
						'required' => $custom_info ['required'] 
				);
			}
		}
		
		if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
			
			$d = array ();
			
			$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
			
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
					
					foreach ( $customlistvalues as $value ) {
						
						$this->data ['customlistvalues_ids'] [] = array (
								'user_id' => $value ['customlistvalues_id'],
								'customlistvalues_name' => $value ['customlistvalues_name'],
								'required' => $value ['required'] 
						);
					}
				}
			}
			
			$this->data ['id_url'] .= '&facilities_id=' . $this->customer->getId ();
		}
		
		if (isset ( $this->request->post ['tags_ids'] )) {
			$tagides1 = $this->request->post ['tags_ids'];
		} elseif (! empty ( $this->request->get ['tags_ids'] )) {
			$tagides1 = $this->request->get ['tags_ids'];
			$this->data ['is_multiple_tags_count'] = '1';
		} else {
			$tagides1 = array ();
		}
		
		$sssssdd = explode ( ",", $tagides1 );
		
		$this->data ['tags_ids'] = array ();
		$this->load->model ( 'setting/tags' );
		
		foreach ( $sssssdd as $tagsid ) {
			
			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
						'tags_id' => $tagsid,
						'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'] 
				);
			}
		}
		
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function clientsinsignature() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm2 ()) {
			
			$tdata = array ();
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($facilities_info ['is_master_facility'] == '1') {
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
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
			
			$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			if ($this->request->post ['imgOutput']) {
				$data ['imgOutput'] = $this->request->post ['imgOutput'];
			} else {
				$data ['imgOutput'] = $this->request->post ['signature'];
			}
			
			$data ['notes_pin'] = $this->request->post ['notes_pin'];
			$data ['user_id'] = $this->request->post ['user_id'];
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'resident/resident' );
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetailbyid ( $this->request->get ['keyword_id'], $facilities_id );
			
			$data ['keyword_file'] = $keywordData2 ['keyword_image'];
			$keyword_name = $keywordData2 ['keyword_name'];
			
			if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
				$comments = ' | ' . $this->request->post ['comments'];
			}
			
			if ($this->request->post ['new_module']) {
				
				$this->load->model ( 'notes/notes' );
				
				foreach ( $this->request->post ['new_module'] as $customlistvalues_id ) {
					
					if ($customlistvalues_id ['checkin'] == "1") {
						
						$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
					}
				}
				
				$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
			}
			
			$afacilities = array ();
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$sssssdds2 = explode ( ",", $this->request->get ['tagsids'] );
				$abdcds = array_unique ( $sssssdds2 );
				
				foreach ( $abdcds as $key1 => $tagsid ) {
					$tag_info = $this->model_setting_tags->getTag ( $tagsid );
					$afacilities [] = array (
							'tags_id' => $tagsid,
							'facilities_id' => $tag_info ['facilities_id'] 
					);
				}
			}
			
			$role_calltagsids = $this->groupArray ( $afacilities, "facilities_id", false, true );
			$abc = array ();
			$tagnamesss = "";
			
			$data ['date_added'] = $date_added;
			$data ['note_date'] = $date_added;
			$data ['notetime'] = $notetime;
			
			if ($facilities_info ['no_distribution'] == '1') {
				foreach ( $role_calltagsids as $rolecalls ) {
					
					$tagname = "";
					$tagname2 = "";
					$tagnamesss_out = "";
					if ($this->request->get ['clienttype'] == '1') {
						foreach ( $rolecalls as $rolecall ) {
							
							$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
							$emp_tag_id = $tag_info ['emp_tag_id'];
							$tags_id = $tag_info ['tags_id'];
							// $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$tagname = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
							
							$this->model_setting_tags->updatetagmed ( $rolecall ['tags_id'], '1', $date_added );
							
							$data ['notes_description'] = $keyword_name . ' ' . $tagname . ' | ' . $description1 . $comments;
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
							
							$this->load->model ( 'facilities/facilities' );
							$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							
							if ($facility ['is_enable_add_notes_by'] == '1') {
								$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
								$this->db->query ( $sql122 );
							}
							if ($facility ['is_enable_add_notes_by'] == '3') {
								$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
								$this->db->query ( $sql13 );
							}
							
							if ($facility ['is_enable_add_notes_by'] == '1') {
								if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
									
									$notes_file = $this->session->data ['local_notes_file'];
									$outputFolder = $this->session->data ['local_image_dir'];
									require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
									$this->load->model ( 'notes/notes' );
									$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
									if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
										$this->model_notes_notes->updateuserverified ( '2', $notes_id );
									}
									
									if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
										$this->model_notes_notes->updateuserverified ( '1', $notes_id );
									}
								}
							}
						}
					}
					
					if ($this->request->get ['clienttype'] == '2') {
						foreach ( $rolecalls as $rolecall ) {
							
							$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
							$emp_tag_id = $tag_info ['emp_tag_id'];
							$tags_id = $tag_info ['tags_id'];
							// $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$tagname = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
							
							$this->model_setting_tags->updatetagmed ( $rolecall ['tags_id'], '0', $date_added );
							
							$data ['notes_description'] = ' Discharged | ' . $tagname . ' | ' . $description1 . $comments;
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
							
							$this->load->model ( 'facilities/facilities' );
							$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							
							if ($facility ['is_enable_add_notes_by'] == '1') {
								$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
								$this->db->query ( $sql122 );
							}
							if ($facility ['is_enable_add_notes_by'] == '3') {
								$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
								$this->db->query ( $sql13 );
							}
							
							if ($facility ['is_enable_add_notes_by'] == '1') {
								if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
									
									$notes_file = $this->session->data ['local_notes_file'];
									$outputFolder = $this->session->data ['local_image_dir'];
									require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
									$this->load->model ( 'notes/notes' );
									$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
									if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
										$this->model_notes_notes->updateuserverified ( '2', $notes_id );
									}
									
									if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
										$this->model_notes_notes->updateuserverified ( '1', $notes_id );
									}
								}
							}
						}
					}
					
					if ($this->request->get ['clienttype'] == '3') {
						foreach ( $rolecalls as $rolecall ) {
							
							$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
							$emp_tag_id = $tag_info ['emp_tag_id'];
							$tags_id = $tag_info ['tags_id'];
							// $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model ( 'setting/locations' );
							$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
							
							$tagname = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
							
							$this->model_setting_tags->updatetagmed ( $rolecall ['tags_id'], '1', $date_added );
							
							$this->load->model ( 'createtask/createtask' );
							$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $rolecall ['tags_id'] );
							
							if ($alldatas != NULL && $alldatas != "") {
								foreach ( $alldatas as $alldata ) {
									$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
									
									$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
									$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
									$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
								}
							}
							
							$data ['notes_description'] = ' Discharged | ' . $tagname . ' | ' . $description1 . $comments;
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
							
							$this->load->model ( 'facilities/facilities' );
							$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							
							if ($facility ['is_enable_add_notes_by'] == '1') {
								$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
								$this->db->query ( $sql122 );
							}
							if ($facility ['is_enable_add_notes_by'] == '3') {
								$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
								$this->db->query ( $sql13 );
							}
							
							if ($facility ['is_enable_add_notes_by'] == '1') {
								if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
									
									$notes_file = $this->session->data ['local_notes_file'];
									$outputFolder = $this->session->data ['local_image_dir'];
									require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
									$this->load->model ( 'notes/notes' );
									$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
									if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
										$this->model_notes_notes->updateuserverified ( '2', $notes_id );
									}
									
									if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
										$this->model_notes_notes->updateuserverified ( '1', $notes_id );
									}
								}
							}
							
							$this->model_setting_tags->addcurrentTagarchive ( $rolecall ['tags_id'] );
							$this->model_setting_tags->updatecurrentTagarchive ( $rolecall ['tags_id'], $notes_id );
							
							$this->model_resident_resident->updateDischargeTag ( $rolecall ['tags_id'], $date_added );
						}
					}
				}
			}
			
			foreach ( $role_calltagsids as $facilities_id1 => $rolecalls ) {
				
				$tagname = "";
				$tagname2 = "";
				$tagnamesss_out = "";
				if ($this->request->get ['clienttype'] == '1') {
					foreach ( $rolecalls as $rolecall ) {
						
						$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
						$emp_tag_id = $tag_info ['emp_tag_id'];
						$tags_id = $tag_info ['tags_id'];
						// $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						
						$this->load->model ( 'setting/locations' );
						$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
						
						$tagname .= $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
						
						$this->model_setting_tags->updatetagmed ( $rolecall ['tags_id'], '1', $date_added );
					}
					
					$data ['notes_description'] = ' Discharged | ' . $tagname . ' | ' . $description1 . $comments;
				}
				
				if ($this->request->get ['clienttype'] == '2') {
					foreach ( $rolecalls as $rolecall ) {
						
						$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
						$emp_tag_id = $tag_info ['emp_tag_id'];
						$tags_id = $tag_info ['tags_id'];
						// $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						
						$this->load->model ( 'setting/locations' );
						$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
						
						$tagname .= $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
						
						$this->model_setting_tags->updatetagmed ( $rolecall ['tags_id'], '0', $date_added );
					}
					
					$data ['notes_description'] = ' Discharged | ' . $tagname . ' | ' . $description1 . $comments;
				}
				
				if ($this->request->get ['clienttype'] == '3') {
					foreach ( $rolecalls as $rolecall ) {
						
						$tag_info = $this->model_setting_tags->getTag ( $rolecall ['tags_id'] );
						$emp_tag_id = $tag_info ['emp_tag_id'];
						$tags_id = $tag_info ['tags_id'];
						// $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						
						$this->load->model ( 'setting/locations' );
						$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
						
						$tagname .= $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
						
						$this->model_setting_tags->updatetagmed ( $rolecall ['tags_id'], '1', $date_added );
						
						$this->load->model ( 'createtask/createtask' );
						$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $rolecall ['tags_id'] );
						
						if ($alldatas != NULL && $alldatas != "") {
							foreach ( $alldatas as $alldata ) {
								$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
								$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
								$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
								$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
							}
						}
					}
					
					$data ['notes_description'] = ' Discharged | ' . $tagname . ' | ' . $description1 . $comments;
				}
				
				if ($facilities_id1 != null && $facilities_id1 != "") {
					$facilities_id2 = $facilities_id1;
				} else {
					$facilities_id2 = $facilities_id;
				}
				
				// var_dump($data);
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id2 );
				
				if ($this->request->get ['clienttype'] == '3') {
					foreach ( $role_calltagsids as $facilities_id1 => $rolecalls ) {
						foreach ( $rolecalls as $rolecall ) {
							
							$this->model_setting_tags->addcurrentTagarchive ( $rolecall ['tags_id'] );
							$this->model_setting_tags->updatecurrentTagarchive ( $rolecall ['tags_id'], $notes_id );
							
							$this->model_resident_resident->updateDischargeTag ( $rolecall ['tags_id'], $date_added );
						}
					}
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id2 );
				
				if ($facility ['is_enable_add_notes_by'] == '1') {
					$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql122 );
				}
				if ($facility ['is_enable_add_notes_by'] == '3') {
					$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql13 );
				}
				
				if ($facility ['is_enable_add_notes_by'] == '1') {
					if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
						
						$notes_file = $this->session->data ['local_notes_file'];
						$outputFolder = $this->session->data ['local_image_dir'];
						require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						$this->load->model ( 'notes/notes' );
						$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
						if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
							$this->model_notes_notes->updateuserverified ( '2', $notes_id );
						}
						
						if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
							$this->model_notes_notes->updateuserverified ( '1', $notes_id );
						}
						
						unlink ( $this->session->data ['local_image_dir'] );
						unset ( $this->session->data ['username_confirm'] );
						unset ( $this->session->data ['local_image_dir'] );
						unset ( $this->session->data ['local_image_url'] );
						unset ( $this->session->data ['local_notes_file'] );
					}
				}
			}
			
			$this->session->data ['success_update_form_2'] = 'Note Added ';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
				$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
			}
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['update_notetime'] != null && $this->request->get ['update_notetime'] != "") {
			$url2 .= '&update_notetime=' . $this->request->get ['update_notetime'];
		}
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		if ($this->request->get ['clienttype'] != null && $this->request->get ['clienttype'] != "") {
			$url2 .= '&clienttype=' . $this->request->get ['clienttype'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/clientsinsignature', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->session->data ['success_update_form_2'] )) {
			$this->data ['success_update_form_2'] = $this->session->data ['success_update_form_2'];
			
			unset ( $this->session->data ['success_update_form_2'] );
		} else {
			$this->data ['success_update_form_2'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		// $this->load->model('setting/tags');
		// $tag_info =
		// $this->model_setting_tags->getTag($this->request->get['tags_id']);
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['tagides'] )) {
			$tagides1 = $this->request->post ['tagides'];
		} elseif (! empty ( $this->request->get ['tags_id'] )) {
			$tagides1 = explode ( ',', $this->request->get ['tags_id'] );
		} elseif ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$tagides1 = explode ( ',', $this->request->get ['tagsids'] );
			$this->data ['tagsids'] = $this->request->get ['tagsids'];
		} else {
			$tagides1 = array ();
		}
		
		$this->data ['tagides'] = array ();
		$this->load->model ( 'setting/tags' );
		
		foreach ( $tagides1 as $tagsid ) {
			
			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
						'tags_id' => $tagsid,
						'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'] 
				);
			}
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm2() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
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
		
		if ($this->request->post ['select_one'] == '1') {
			if ($this->request->post ['notes_pin'] == '') {
				$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				
				if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
					$user_info = $this->model_user_user->getUserByUsername ( $this->request->post ['user_id'] );
				} else {
					$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $this->customer->getId () );
				}
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
			}
		}
		
		if ($this->request->post ['override_monitor_time_user_id_checkbox'] == '1') {
			if ($this->request->post ['override_monitor_time_user_id'] == '') {
				$this->error ['override_monitor_time_user_id'] = $this->language->get ( 'error_required' );
			}
		}
		
		if ($this->request->post ['override_monitor_time_user_id'] != null && $this->request->post ['override_monitor_time_user_id'] != '') {
			if ($this->request->post ['override_monitor_time_user_id_checkbox'] == '') {
				$this->error ['override_monitor_time_user_id_checkbox'] = $this->language->get ( 'error_required' );
			}
		}
		
		$this->load->model ( 'setting/keywords' );
		$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
		
		if ($keywordData2 ['monitor_time'] == '1') {
			if ($this->request->post ['override_monitor_time_user_id_checkbox'] != '1') {
				if ($keywordData2 ['end_relation_keyword'] == '1') {
					$a3 = array ();
					$a3 ['keyword_id'] = $keywordData2 ['relation_keyword_id'];
					$a3 ['user_id'] = $this->request->post ['user_id'];
					$a3 ['facilities_id'] = $this->customer->getId ();
					$a3 ['is_monitor_time'] = '1';
					
					$active_note_info2 = $this->model_notes_notes->getNotebyactivenote ( $a3 );
					
					// var_dump($active_note_info2);
					
					if (empty ( $active_note_info2 )) {
						$this->error ['warning'] = 'End ActiveNote does not exit!';
					}
				}
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
	public function updateMedication() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$this->data ['current_time'] = date ( 'h:i A' );
		
		if ($this->request->get ['tags_id']) {
			$tags_id = $this->request->get ['tags_id'];
		} elseif ($this->request->post ['emp_tag_id']) {
			$tags_id = $this->request->post ['emp_tag_id'];
		}
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		// var_dump($tag_info);die;
		
		if ($tags_id) {
			$this->data ['name'] = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			
			$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		
		if ($resulsst ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $this->request->get ['facilities_id'];
				$timezone_name = $this->customer->isTimezone ();
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
		}
		
		$this->load->model ( 'createtask/createtask' );
		$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $tags_id;
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['type'] = 'updatehealthform';
			
			$archive_tags_medication_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			
			$url2 = "";
			
			if (! empty ( $this->request->post ['medication'] )) {
				// $this->session->data['medication'] =
				// $this->request->post['medication'];
				
				$medication_tags = implode ( ',', $this->request->post ['medication'] );
				
				if ($medication_tags != null && $medication_tags != "") {
					$url2 .= '&medication_tags=' . $medication_tags;
				}
				
				$this->session->data ['success2'] = 'Medication added successfully!';
			} else {
				$this->session->data ['success_add_form'] = 'Medication added successfully!';
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$url2 .= '&locationids=' . $this->request->get ['locationids'];
			}
			
			if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
				$url2 .= '&userids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
			}
			
			$url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			
			$this->redirect ( $this->url->link ( 'resident/resident/updateMedication', '' . $url2, 'SSL' ) );
		}
		
		$url2 = "";
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
		}
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		if ($this->request->get ['archive_tags_medication_id'] != null && $this->request->get ['archive_tags_medication_id'] != "") {
			$url2 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
		}
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get ['userids'];
		}
		
		// $this->data ['addinventorys_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/verifyInventory', '' . $url2, 'SSL' ) );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&updateMedication=1', '' . $url2, 'SSL' ) );
			/* $this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&updateMedication=2', '' . $url2, 'SSL' ) ); */
			
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign2&addmedication=1', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign2', '' . $url2, 'SSL' ) );
		}
		
		// var_dump($this->data['redirect_url']);
		// die;
		
		$this->data ['printaction'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/printmedicationform', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->request->post ['room_id'] )) {
			$this->data ['room_id'] = $this->request->post ['room_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['room_id'] = $tag_info ['room'];
		} else {
			$this->data ['room_id'] = '';
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$this->load->model ( 'setting/locations' );
		/*$data = array (
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
		}*/
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
		
		$this->load->model ( 'medicationtype/medicationtype' );
		$results = $this->model_medicationtype_medicationtype->getmedicationtypes ( $data );
		
		foreach ( $results as $result ) {
			
			$this->data ['medication_types'] [] = array (
					'medicationtype_id' => $result ['medicationtype_id'],
					'type_name' => $result ['type_name'],
					'type' => $result ['type'],
					'measurement_type' => $result ['measurement_type'],
					'status' => $result ['status'] 
			);
		}
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['refill_percentage'] )) {
			$this->data ['refill_percentage'] = $this->request->post ['refill_percentage'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['refill_percentage'] = $tag_info ['refill_percentage'];
		} else {
			$this->data ['refill_percentage'] = '';
		}
		
		if (isset ( $this->request->post ['assign_to'] )) {
			$userids1222 = $this->request->post ['assign_to'];
		} elseif (! empty ( $tag_info )) {
			
			$userids1222 = explode ( ',', $tag_info ['assign_to'] );
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
		
		if (! empty ( $tag_info )) {
			$this->data ['assign_to_type'] = $tag_info ['assign_to_type'];
		} else if (isset ( $this->request->post ['assign_to_type'] )) {
			$this->data ['assign_to_type'] = $this->request->post ['assign_to_type'];
		} else {
			$this->data ['assign_to_type'] = '';
		}
		
		if (isset ( $this->request->post ['user_role_assign_ids'] )) {
			$user_roles12 = $this->request->post ['user_role_assign_ids'];
		} elseif (! empty ( $tag_info )) {
			if ($tag_info ['user_role_assign_ids'] != "") {
				$user_roles12 = explode ( ',', $tag_info ['user_role_assign_ids'] );
			} else {
				
				$user_roles12 = "";
			}
		} else {
			$user_roles12 = array ();
		}
		
		$this->data ['auser_roles'] = array ();
		$this->load->model ( 'user/user_group' );
		
		if ($user_roles12 != "" && $user_roles12 != 0) {
			foreach ( $user_roles12 as $auser_role ) {
				
				$auser_role_info = $this->model_user_user_group->getUserGroup ( $auser_role );
				
				if ($auser_role_info) {
					$this->data ['auser_roles'] [] = array (
							'user_group_id' => $auser_role,
							'name' => $auser_role_info ['name'] 
					);
				}
			}
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
			
			$muduled = $this->model_resident_resident->gettagModule ( $this->request->get ['tags_id'], "0", $this->request->get ['notes_id'] );
			
			$this->data ['modules'] = $muduled ['new_module'];
		} elseif ($this->request->post ['emp_tag_id']) {
			
			$muduled = $this->model_resident_resident->gettagModule ( $this->request->post ['emp_tag_id'], $this->request->get ['is_archive'], $this->request->get ['notes_id'] );
			
			$this->data ['modules'] = $muduled ['new_module'];
		} else {
			$this->data ['modules'] = array ();
		}
		
		// var_dump( $this->data['modules']);die;
		
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
		
		if (isset ( $this->request->post ['reasons'] )) {
			$this->data ['reasons'] = $this->request->post ['reasons'];
		} else {
			$this->data ['reasons'] = '';
		}
		
		if (isset ( $this->request->post ['doctors'] )) {
			$this->data ['doctors'] = $this->request->post ['doctors'];
		} else {
			$this->data ['doctors'] = '';
		}
		
		if (isset ( $this->request->post ['route'] )) {
			$this->data ['route'] = $this->request->post ['route'];
		} else {
			$this->data ['route'] = '';
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
			;
		}
		if (isset ( $this->error ['daily_times'] )) {
			$this->data ['error_daily_times'] = $this->error ['daily_times'];
		} else {
			$this->data ['error_daily_times'] = array ();
			;
		}
		if (isset ( $this->error ['drug_mg'] )) {
			$this->data ['error_drug_mg'] = $this->error ['drug_mg'];
		} else {
			$this->data ['error_drug_mg'] = array ();
		}
		
		if (isset ( $this->error ['drug_pm'] )) {
			$this->data ['error_drug_pm'] = $this->error ['drug_pm'];
		} else {
			$this->data ['error_drug_pm'] = array ();
		}
		
		if (isset ( $this->error ['drug_alertnate'] )) {
			$this->data ['error_drug_alternate'] = $this->error ['drug_alertnate'];
		} else {
			$this->data ['error_drug_alternate'] = array ();
			;
		}
		
		$url2 = "";
		$url3 = "";
		
		if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
			$url3 .= '&facilityids=' . $this->request->get ['facilityids'];
		}
		if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
			$url3 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
			$url3 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get ['userids'];
			$url3 .= '&userids=' . $this->request->get ['userids'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$url3 .= '&tags_id=' . $this->request->get ['tags_id'];
			$url3 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			$this->data ['is_archive'] = $this->request->get ['is_archive'];
		}
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['updatenotes_id'] = $notes_id;
		
		$this->data ['action'] = $this->url->link ( 'resident/resident/updateMedication', $url2, true );
		
		// var_dump( $this->data['action'] );die;
		
		$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateMedication', '' . $url3, 'SSL' ) );
		
		$this->data ['autosearch'] = $this->request->get ['autosearch'];
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/update_medication.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function updateMedicationSign() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$this->load->model ( 'setting/tags' );
			
			$this->load->model ( 'api/temporary' );
			$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_medication_id'] );
			
			// var_dump($this->request->get['facilities_id']);
			// die;
			
			$tempdata = array ();
			$tempdata = unserialize ( $temporary_info ['data'] );
			
			// var_dump($tempdata);die;
			
			if ($tempdata ['facilities_id'] != null && $tempdata ['facilities_id'] != "") {
				$facilities_id = $tempdata ['facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
			
			$archive_medication_id = $this->model_resident_resident->updateMedication ( $this->request->get ['tags_id'], $this->request->get ['tags_medication_details_id'], $tempdata, $facilities_id );
			
			$data2 = array ();
			$data2 ['tags_id'] = $this->request->get ['tags_id'];
			$data2 ['notes_id'] = $this->request->get ['notes_id'];
			$data2 ['archive_medication_id'] = $archive_medication_id;
			$data2 ['facilities_id'] = $facilities_id;
			$data2 ['facilitytimezone'] = $this->customer->isTimezone ();
			
			$data2 ['tags_status_in_change'] = $this->request->get ['tags_status_in_change'];
			
			$notes_id = $this->model_setting_tags->updateclientsign ( $this->request->post, $data2 );
			
			$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_medication_id'] );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
				$url2 .= '&page=' . $this->request->get ['page'];
			}
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			if ($this->request->get ['tags_status_in_change'] != null && $this->request->get ['tags_status_in_change'] != "") {
				$url2 .= '&tags_status_in_change=' . $this->request->get ['tags_status_in_change'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateMedication', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
			$url2 .= '&page=' . $this->request->get ['page'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		if ($this->request->get ['archive_medication_id'] != null && $this->request->get ['archive_medication_id'] != "") {
			$url2 .= '&archive_medication_id=' . $this->request->get ['archive_medication_id'];
		}
		if ($this->request->get ['tags_status_in_change'] != null && $this->request->get ['tags_status_in_change'] != "") {
			$url2 .= '&tags_status_in_change=' . $this->request->get ['tags_status_in_change'];
		}
		
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['notes_id'] = $this->request->get['notes_id'];
		$this->data ['updatenotes_id'] = $notes_id;
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/updateclientsign', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
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
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		/*
		 * if ($this->request->get['tags_id']) {
		 * $this->load->model('setting/tags');
		 * $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
		 * }
		 */
		
		/*
		 * if (isset($this->request->post['emp_tag_id'])) {
		 * $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		 * } elseif (! empty($tag_info)) {
		 * $this->data['emp_tag_id'] = $tag_info['emp_tag_id'];
		 * } else {
		 * $this->data['emp_tag_id'] = '';
		 * }
		 *
		 * if (isset($this->request->post['tags_id'])) {
		 * $this->data['tags_id'] = $this->request->post['tags_id'];
		 * } elseif (! empty($tag_info)) {
		 * $this->data['tags_id'] = $tag_info['tags_id'];
		 * } else {
		 * $this->data['tags_id'] = '';
		 * }
		 *
		 * if (isset($this->request->post['emp_tag_id_2'])) {
		 * $this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
		 * } elseif (! empty($tag_info)) {
		 * $this->data['emp_tag_id_2'] = $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
		 * } else {
		 * $this->data['emp_tag_id_2'] = '';
		 * }
		 * $this->data['createtask'] = 1;
		 */
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function movementreport() {
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$noteurl = $this->url->link ( 'form/form/printintakeform', '' . $url, 'SSL' );
		$printnoteurl = $this->url->link ( 'form/form/printform', '' . $url, 'SSL' );
		$firedrillnoteurl = $this->url->link ( 'form/form/printmonthly_firredrill', '' . $url, 'SSL' );
		$incidentnoteurl = $this->url->link ( 'form/form/printincidentform', '' . $url, 'SSL' );
		$innoteurl = $this->url->link ( 'form/form/printintakeform', '' . $url, 'SSL' );
		
		$this->language->load ( 'notes/notes' );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'notes/image' );
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'notes/tags' );
		
		$this->document->setTitle ( 'Generate PDF' );
		
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'facilities/facilities' );
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		$this->data ['reports'] = array ();
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$searchdate = $this->request->get ['searchdate'];
			;
		} else {
			$searchdate = date ( 'm-d-Y', strtotime ( 'now' ) );
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$notes_id = $this->request->get ['notes_id'];
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getnotes ( $notes_id );
			$notesid = $notes_info ['parent_id'];
			$notes_id = $notes_info ['notes_id'];
		} else {
			$notesid = '';
		}
		
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/keywords' );
		$this->load->model ( 'setting/tags' );
		
		$this->load->model ( 'notes/clientstatus' );
		$this->load->model ( 'setting/image' );
		
		$this->load->model ( 'notes/notes' );
		$result = $this->model_notes_notes->getnotes ( $notes_id );
		
		$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
		
		
		$this->load->model ( 'facilities/facilities' );
			
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		
		date_default_timezone_set ( $facilitytimezone );
		
		$unique_id = $facilities_info ['customer_key'];
			
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$this->data ['customers'] = array ();
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
		}
		
		
		if ($alltag ['move_notes_id'] > 0) {
			$result1 = $this->model_notes_notes->getnotes ( $alltag ['move_notes_id'] );
			$alltag1 = $this->model_notes_notes->getNotesTags ( $result1 ['notes_id'] );
			
			if ($alltag1 ['emp_tag_id'] != null && $alltag1 ['emp_tag_id'] != "") {
				$clientstatus_info1 = $this->model_notes_clientstatus->getclientstatus ( $alltag1 ['tag_status_id'] );
				
			}
				
		}
		
		
		$shift_name = "";
			
		$date_added = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
		$notetime2 = date ( 'h:i A', strtotime ( $result ['date_added'] ) );
		
		if ($result ['shift_id'] > 0) {
			$shift_info = $this->model_notes_notes->getshift ( $result ['shift_id'] );
			$shift_name = $shift_info ['shift_name'];
		}
		
		$rule_action_content = unserialize($clientstatus_info1['rule_action_content']);
		
		$notesmedicationtimes = array();
		if($rule_action_content['out_from_cell'] == 1){
			
			
			$tag = $this->model_setting_tags->getTag ( $alltag ['tags_id']);
			
			
			$houroutdata = array();
			$houroutdata['tags_id'] = $alltag ['tags_id'];
			$houroutdata['currentdate'] = date ( 'Y-m-d', strtotime ( $result ['date_added'] ) );
			
			$houroutdata['rules_operation'] = $customers ['rules_operation'];
			$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
			$houroutdata['rules_end_time'] = $customers ['rules_end_time'];
			$outcelltimes = $this->model_setting_tags->getOutToCellTimes ( $houroutdata );
			
			$alltotaltime = 0;
			foreach($outcelltimes as $sttime){
				
				$rule_action_content = unserialize($sttime['rule_action_content']);
				
				$status_total_time1 = 0;
				if ($sttime['years'] > 0) {
					$status_total_time1 = 60 * 24 * 365 * $sttime['years'] ;
				}

				if ($sttime['months'] > 0) {
					$status_total_time1 += 60 * 24 * 30 * $sttime['months'];
				}

				if ($sttime['days'] > 0) {
					$status_total_time1 += 60 * 24 * $sttime['days'];
				}

				if ($sttime['hours'] > 0) {
					$status_total_time1 += 60 * $sttime['hours'];
				}
				
				if ($sttime['minutes'] > 0) {
					$status_total_time1 += $sttime['minutes'];
				}
				$name = $sttime['name'];
				$alltotaltime = $alltotaltime + $status_total_time1;
				$outcelltimtime = $this->secondsToTime($status_total_time1*60);
				
				
				$noteinfo = $this->model_notes_notes->getnotes ( $sttime ['notes_id'] );
				$notesmedicationtimes [] = array (
					'name' => $name,
					'notes_description' => $noteinfo['notes_description'],
					'outcelltimtime' => $outcelltimtime,
					
				);
			}
			
			$status_total_time = $this->secondsToTime($alltotaltime*60);
		  
			require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
			$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
			
			$pdf->SetCreator ( PDF_CREATOR );
			$pdf->SetAuthor ( '' );
			$pdf->SetTitle ( 'REPORT' );
			$pdf->SetSubject ( 'REPORT' );
			$pdf->SetKeywords ( 'REPORT' );
			
			// set auto page breaks
			$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
			
			// set image scale factor
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
				require_once (dirname ( __FILE__ ) . '/lang/eng.php');
				$pdf->setLanguageArray ( $l );
			}
			
			$pdf->SetFont ( 'helvetica', '', 9 );
			$pdf->AddPage ();
			
			$html = '';
			$html .= '<style>

		td {
			padding: 10px;
			margin: 10px;
		   border: 1px solid #B8b8b8;
		   line-height:20.2px;
		   display:table-cell;
			padding:5px;
		}
		</style>
		<style>
			
			.sticky + .content {
			  padding-top: 102px;
			}
			
			</style>
			<style type="text/css" media="print">
			@page 
			{
				size:  auto;   /* auto is the initial value */
				margin: 0mm;  /* this affects the margin in the printer settings */
			}

			
			@media print {
				a[href]:after {
					content: none !important;
				}
			}
			</style>
		';
			
			$html .= '
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
				<tr>  
					
					<td width="100%" style="text-align:center;">
					<h2>Hour Out Log</h2>
					
					</td>
					
				</tr>
			</table>
			
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
				<tr>  
					<td> Shift: ' . $shift_name . '</td>
					<td> Date: ' . $date_added . '</td>
					<td> Time: ' . $notetime2 . '</td>
					<td> </td>
				</tr>
			</table>

			<table width="100%" style="border:none;background-color: #000; color: #fff;" cellpadding="2" cellspacing="0" >
				<tr>  
					<td width="100%" style="padding:20px;text-align:left;width:100%;padding: 10px;margin: 10px; border: 1px solid #B8b8b8; line-height:20.2px;display:table-cell;padding:5px;" > '.$tag['emp_last_name'].'  '.$tag['emp_first_name'].' – Total Time Out - : '.$status_total_time.'</td>
				</tr>
			</table>';
			
			foreach($notesmedicationtimes as $outcelltim){ 
				$html .= ' <table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
				$html .= '<tr> ';
				
				$html .= '<td>' . $outcelltim['name'] . '</td>';
				$html .= '<td>' . $outcelltim['notes_description'] . '</td>';
				$html .= '<td>'.$outcelltim['outcelltimtime'].'</td>';
				
				$html .= '</tr>';
				$html .= '</table>';
			}
			
		}else{
		
			$emp_tag_id22 = "";
			$roleCall = "";
			
			
			
			if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
				$this->load->model ( 'api/permision' );
				$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
				$clientinfo = $this->model_api_permision->getclientinfo ( $result ['facilities_id'], $tag_info );
				$emp_tag_id22 = $clientinfo ['name'];
				
				$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $alltag ['tag_status_id'] );
				
				if ($clientstatus_info ['type'] == '2') {
					$roleCall = $clientstatus_info ['name'];
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$outnotes_pin = $result ['notes_pin'];
					} else {
						$outnotes_pin = '';
					}
					
					$outuser_id = $result ['user_id'];
					$outsignature = $result ['signature'];
					$outnotes_type = $result ['notes_type'];
					
					$outnotetime = date ( 'h:i A', strtotime ( $result ['notetime'] ) );
					
					/*
					 * $description1 = "";
					 * if($result['customlistvalues_id'] != null && $result['customlistvalues_id'] != ""){
					 * $ids = explode ( ",",$result['customlistvalues_id']);
					 * foreach ($ids as $customlistvalues_id) {
					 * $custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
					 * $description1 .= $custom_info['customlistvalues_name'].' | ';
					 * }
					 * }
					 */
				}
				
				if ($alltag ['move_notes_id'] > 0) {
					$result1 = $this->model_notes_notes->getnotes ( $alltag ['move_notes_id'] );
					$alltag1 = $this->model_notes_notes->getNotesTags ( $result1 ['notes_id'] );
					
					if ($alltag1 ['emp_tag_id'] != null && $alltag1 ['emp_tag_id'] != "") {
						$clientstatus_info1 = $this->model_notes_clientstatus->getclientstatus ( $alltag1 ['tag_status_id'] );
						
						if ($clientstatus_info1 ['type'] == '3' || $clientstatus_info1 ['type'] == '4') {
							$roleCall = $clientstatus_info1 ['name'];
							if ($result1 ['notes_pin'] != null && $result1 ['notes_pin'] != "") {
								$notes_pin = $result1 ['notes_pin'];
							} else {
								$notes_pin = '';
							}
							
							$user_id = $result1 ['user_id'];
							$signature = $result1 ['signature'];
							$notes_type = $result1 ['notes_type'];
							
							$notetime = date ( 'h:i A', strtotime ( $result1 ['notetime'] ) );
							
							$description1 = "";
							if ($result1 ['customlistvalues_id'] != null && $result1 ['customlistvalues_id'] != "") {
								$ids = explode ( ",", $result1 ['customlistvalues_id'] );
								foreach ( $ids as $customlistvalues_id ) {
									$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
									$description1 .= $custom_info ['customlistvalues_name'] . ' | ';
								}
							}
						}
					}
				}
			}
			
			require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
			$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
			
			$pdf->SetCreator ( PDF_CREATOR );
			$pdf->SetAuthor ( '' );
			$pdf->SetTitle ( 'REPORT' );
			$pdf->SetSubject ( 'REPORT' );
			$pdf->SetKeywords ( 'REPORT' );
			
			// set auto page breaks
			$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
			
			// set image scale factor
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
				require_once (dirname ( __FILE__ ) . '/lang/eng.php');
				$pdf->setLanguageArray ( $l );
			}
			
			$pdf->SetFont ( 'helvetica', '', 9 );
			$pdf->AddPage ();
			
			$html = '';
			$html .= '<style>

		td {
			padding: 10px;
			margin: 10px;
		   border: 1px solid #B8b8b8;
		   line-height:20.2px;
		   display:table-cell;
			padding:5px;
		}
		</style>
		<style>
			
			.sticky + .content {
			  padding-top: 102px;
			}
			
			</style>
			<style type="text/css" media="print">
			@page 
			{
				size:  auto;   /* auto is the initial value */
				margin: 0mm;  /* this affects the margin in the printer settings */
			}

			
			@media print {
				a[href]:after {
					content: none !important;
				}
			}
			</style>
		';
			
			$html .= '
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
			<tr>  
				<td width="100%" style="padding:20px;text-align:left;width:100%;padding: 10px;margin: 10px; border: 1px solid #B8b8b8; line-height:20.2px;display:table-cell;padding:5px;" >Facility Destination Log </td>
			</tr>
		</table>	

			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
				<tr>  
					<td width="100%" style="padding:20px;text-align:left;width:100%;padding: 10px;margin: 10px; border: 1px solid #B8b8b8; line-height:20.2px;display:table-cell;padding:5px;" > Refenences <br> Corrections Division Policy and Procedure J-2500 </td>
				</tr>
			</table>	
			
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
				<tr>  
					<td> Shift: ' . $shift_name . '</td>
					<td> Date: ' . $date_added . '</td>
					<td> Time: ' . $notetime2 . '</td>
					<td> Deputy: </td>
				</tr>
			</table>
			
			<table width="100%" style="boder:1px solid #000" cellpadding="2" cellspacing="0" align="center">';
			
			$html .= '<thead>';
			$html .= '  <tr>';
			$html .= '    <td valign="middle" style="text-align: left;width:10%">OFFENDER NAME</td>';
			$html .= '    <td valign="middle" style="text-align: left;width:10%">DESTINATION</td>';
			$html .= '    <td valign="middle" style="text-align: left;width:20%">REQUESTING AGENCY</td>';
			
			$html .= '    <td valign="middle" style="text-align: center;width:20%">TIME <br>Out In</td>';
			$html .= '    <td valign="middle" style="text-align: center;width:20%">OUT OFFICER <br> Print Signature</td>';
			$html .= '    <td valign="middle" style="text-align: center;width:20%">RETURN OFFICER <br> Print Signature</td>';
			
			$html .= '  </tr>';
			$html .= ' </thead>';
			
			$html .= '<tr>';
			$html .= '<td style="text-align:left; line-height:20.2px;width:10%">' . $emp_tag_id22 . '</td>';
			$html .= '<td style="text-align:left; line-height:20.2px;width:10%">' . $roleCall . '</td>';
			$html .= '<td style="text-align:left; line-height:20.2px;width:20%">' . $description1 . '</td>';
			$html .= '<td style="text-align:left; line-height:20.2px;width:20%">
			<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
				<tr>  
					<td style="text-align: center;width:50%">' . $notetime . ' </td>
					<td style="text-align: center;width:50%"> ' . $outnotetime . ' </td>
				</tr>
			</table>	
			</td>';
			$html .= '<td style="text-align:left; line-height:20.2px;width:20%">
			<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
				<tr>  
					<td style="text-align: center;width:50%"> ' . $user_id . ' </td>
					<td style="text-align: center;width:50%"> ';
			if ($user_id != null && $user_id != "0") {
				
				if ($notes_type == "2") {
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} elseif ($notes_type == "1") {
					
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} elseif ($notes_pin != null && $notes_pin != "") {
					
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} else {
					
					if ($signature != null && $signature != "") {
						$html .= '<img style="text-align: center;" src="' . $signature . '" width="98px" height="29px" style="    vertical-align: bottom;">';
					} else {
						$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
					}
				}
			}
			$html .= '</td>
				</tr>
			</table>	
			</td>';
			$html .= '<td style="text-align:left; line-height:20.2px;width:20%">
			<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
				<tr>  
					<td style="text-align: center;width:50%">' . $outuser_id . '</td>
					<td style="text-align: center;width:50%">';
			if ($outuser_id != null && $outuser_id != "0") {
				
				if ($outnotes_type == "2") {
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} elseif ($outnotes_type == "1") {
					
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} elseif ($outnotes_pin != null && $outnotes_pin != "") {
					
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} else {
					if ($outsignature != null && $outsignature != "") {
						$html .= '<img style="text-align: center;" src="' . $outsignature . '" width="98px" height="29px" style="    vertical-align: bottom;">';
					} else {
						$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
					}
				}
			}
			$html .= '</td>
				</tr>
			</table>	
			</td>';
			$html .= '</tr>';
			
			$html .= '</table>';
		
		}
		
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		
		$pdf->lastPage ();
		
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
	}
	
	public function hourout() {

		if (! $this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		unset ( $this->session->data ['show_hidden_info'] );
		unset ( $this->session->data ['case_number'] );
		
		$this->language->load ( 'notes/notes' );
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( 'Clients' );
		
		if ($this->request->get ['search_facilities_id'] > 0) {
			$this->session->data ['search_facilities_id'] = $this->request->get ['search_facilities_id'];
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}

		if ($this->request->get ['searchall'] == '1') {
			unset ( $this->session->data ['search_facilities_id'] );
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['facilityname'] = $this->customer->getfacility ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facilities_info ['is_discharge_form_enable'] == '1') {
			$this->data ['dis_form'] = '1';
		} else {
			$this->data ['dis_form'] = '2';
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$this->data ['add_role_call_check'] = '1';
		}

		$this->data ['add_role_call'] = $this->request->get ['add_role_call'];
		
		if (($this->request->get ['searchtag'] == '1')) {
			$url = "";
			if ($this->request->post ['search_tags'] != null && $this->request->post ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->post ['search_tags'];
			}
			
			if ($this->request->post ['room_id'] != null && $this->request->post ['room_id'] != "") {
				$url .= '&room_id=' . $this->request->post ['room_id'];
			}
			
			if ($this->request->post ['wait_list'] != null && $this->request->post ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->post ['wait_list'];
			}
			if ($this->request->post ['search_tags_tag_id'] != null && $this->request->post ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->post ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if (($this->request->get ['searchtag'] == '2')) {
			$url = "";
			if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->get ['search_tags'];
			}
			if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->get ['wait_list'];
			}
			if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$this->data ['search_tags'] = $this->request->get ['search_tags'];
		}
		
		if ($this->request->get ['room_id'] != null && $this->request->get ['room_id'] != "") {
			$this->data ['room_id'] = $this->request->get ['room_id'];
		}

		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$this->data ['wait_list'] = $this->request->get ['wait_list'];
		}

		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$this->data ['search_tags_tag_id'] = $this->request->get ['search_tags_tag_id'];
			$search_tags = '';
		} else {
			$search_tags = $this->request->get ['search_tags'];
		}
		
		$url4 = "";
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$url4 .= '&search_tags=' . $this->request->get ['search_tags'];
		}

		if ($this->request->get ['room_id'] != null && $this->request->get ['room_id'] != "") {
			$url4 .= '&room_id=' . $this->request->get ['room_id'];
		}
		
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url4 .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url4 .= '&wait_list=' . $this->request->get ['wait_list'];
		}

		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$url4 .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$url4 .= '&gender=' . $this->request->get ['gender'];
		}
		
		if ($this->request->get ['add_role_call'] != null && $this->request->get ['add_role_call'] != "") {
			$url4 .= '&add_role_call=' . $this->request->get ['add_role_call'];
		}

		if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
			$url4 .= '&role_call=' . $this->request->get ['role_call'];
		}

		if ($this->request->get ['client_status'] != null && $this->request->get ['client_status'] != "") {
			$url4 .= '&client_status=' . $this->request->get ['client_status'];
		}
		
		// // $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
		// $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');
		
		$this->data ['total_in_url'] = $this->url->link ( 'resident/resident&in=1', '' . $url1, 'SSL' );
		$this->data ['total_out_url'] = $this->url->link ( 'resident/resident&out=1', '' . $url1, 'SSL' );
		$this->data ['non_url'] = $this->url->link ( 'resident/resident&gender=3', '' . $url1, 'SSL' );
		
		$this->data ['total_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
		
		$this->data ['notes_url'] = $this->url->link ( 'notes/notes/insert', '', 'SSL' );
		
		$this->data ['sticky_note'] = $this->url->link ( 'resident/resident/getstickynote&close=1', '', 'SSL' );
		
		$this->data ['dailycensus'] = $this->url->link ( 'resident/dailycensus', '', 'SSL' );
		
		$this->data ['clientfile'] = $this->url->link ( 'resident/resident/clientfile', '', 'SSL' );
		
		$this->data ['logout'] = $this->url->link ( 'common/logout', '', 'SSL' );
		
		$this->data ['resident_url'] = $this->url->link ( 'resident/resident', '', 'SSL' );
		
		$this->data ['task_lists'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/headertasklist', '' . $url1, 'SSL' ) );
		
		$this->data ['task_lists2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatus', '' . $url1, 'SSL' ) );
		
		$this->data ['ajaxresidenturl'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/ajaxresidenthourout', '' . $url4, 'SSL' ) );
		
		$this->data ['case_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/cases/dashboard', '', 'SSL' ) );
		
		$this->data ['add_client_url1'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
		
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		
		$default_facility_id = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
		
		$this->data ['roll_call_sign_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		
		$this->data ['assignteam'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/assignteam', '', 'SSL' ) );
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'setting/image' );
		
		$this->load->model ( 'notes/clientstatus' );
		
		$ddss = array ();
		if ($facilities_info ['client_facilities_ids'] != null && $facilities_info ['client_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $facilities_info ['client_facilities_ids'];
			
			$ddss [] = $this->customer->getId ();
			$sssssdd = implode ( ",", $ddss );
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
			$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$config_admin_limit = "2500";
		
		// var_dump($config_admin_limit);
		$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$this->data ['is_external_status_facility'] = $facilities_is_master ['enable_facilityinout'];
		
		if ($facilities_is_master ['enable_facilityinout'] != '1') {
			$is_client_screen = '0';
		} else {
			$is_client_screen = '1';
			$facility_inout = '2';
		}
		
		if ($facilities_is_master ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facilities_is_master ['is_master_facility'];
		}
		
		$data3 = array ();
		$data3 = array (
				'status' => 1,
				'discharge' => 1,
				// 'role_call' => '1',
				'is_master' => $is_master_facility,
				// 'gender2' => $this->request->get['gender'],
				'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_client_screen' => $is_client_screen,
				// 'emp_tag_id_2' => $this->request->get['search_tags'],
				'wait_list' => $this->request->get ['wait_list'],
				'client_status' => $this->request->get ['client_status'],
				'all_record' => '1' 
		);
		
		$this->data ['tags_total'] = $this->model_setting_tags->getTotalTags ( $data3 );
		
		$data31333 = array ();
		$data31333 = array (
				'status' => 1,
				'discharge' => 1,
				// 'role_call' => '1',
				'gender2' => $this->request->get ['gender'],
				'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_master' => $is_master_facility,
				'is_client_screen' => $is_client_screen,
				'emp_tag_id_all' => $search_tags,
				'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
				'wait_list' => $this->request->get ['wait_list'],
				'client_status' => $this->request->get ['client_status'],
				'all_record' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
		);
		
		$tags_total_2 = $this->model_setting_tags->getTotalTags ( $data31333 );
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$data31 = array ();
		
		
		$facility_inout = "";
		if ($this->request->get ['client_status'] == "1" || $this->request->get ['client_status'] == "2") {
			$inclint = array (
					0 
			);
		} else {
			$inclint = array ();
		}
		$outcount = array ();
		
		$hourout_arr = array();

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

			$rule_action_content = unserialize($customform['rule_action_content']);

			//echo '<pre>dddddddd'; print_r($rule_action_content['out_from_cell']); echo '</pre>'; //die;
			
			if ($rule_action_content['out_from_cell'] == "1") {
				$hourout_arr [] = $customform ['tag_status_id'];
			}
			
			/*if ($facilities_is_master ['enable_facilityinout'] == '1') {
				if ($customform ['type'] == "3") {
					$inclint [] = $customform ['tag_status_id'];
				}
			}*/
		}
		
		$facility_inout = '1';	

		if ($hourout_arr != null && $hourout_arr != "") {
			$hourout_arr = implode ( ",", $hourout_arr );
			$rolecalls = $hourout_arr;
		}
		
		if ($inclint != null && $inclint != "") {
			$inclient = implode ( ",", $inclint );
			$rolecalls = $inclient;
		}
		
		if ($outcount != null && $outcount != "") {
			$outclient = implode ( ",", $outcount );
			$rolecalls = $outclient;
		}


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
		
		$this->load->model ( 'setting/tags' );
		
		$tag_statuses = $this->model_setting_tags->getAllStatus ();
		
		foreach ( $tag_statuses as $tag_staus ) {
			
			$tags_ids [] = $tag_staus ['facility_type'];
		}
		
		$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if (in_array ( $facilities_id, $tags_ids )) {
			
			$is_external_status_facility = '1';
		} else {
			
			$is_external_status_facility = '0';
		}
		
		if ($facility_info ['enable_facilityinout'] != '1') {
			
			$is_client_screen = '0';
		} else {
			
			$is_client_screen = '1';
		}
		
		if (($this->request->get ['search_tags'] != "" && $this->request->get ['search_tags'] != null) || ($this->request->get ['room_id'] != "" && $this->request->get ['room_id'] != null)) {
			
			$data31 = array (
					
					'status' => 1,
					'discharge' => 1,
					'emp_tag_id_all' => $this->request->get ['search_tags'],
					// 'role_call' => $rolecall,
					'rolecalls' => $rolecalls,
					'facility_inout' => $facility_inout,
					'gender2' => $this->request->get ['gender'],
					'sort' => 'emp_last_name',
					'is_master' => $is_master_facility,
					'room_id' => $this->request->get ['room_id'],
					'facilities_id' => $facilities_id,
					'is_client_screen' => $is_client_screen,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit,
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1' 
			);
		} else if ($this->request->get ['add_role_call'] == '1') {
			$data31 = array (
					'status' => 1,
					'discharge' => 1,
					'data_tags' => $data_tags,
					// 'role_call' => $rolecall,
					'rolecalls' => $rolecalls,
					'facility_inout' => $facility_inout,
					'gender2' => $this->request->get ['gender'],
					'sort' => 'emp_last_name',
					'is_master' => $is_master_facility,
					'facilities_id' => $facilities_id,
					'is_client_screen' => $is_client_screen,
					'emp_tag_id_all' => $search_tags,
					'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1' 
			);
		} else {
			$data31 = array (
					'status' => 1,
					'discharge' => 1,
					// 'role_call' =>$rolecall,
					'rolecalls' => $rolecalls,
					'data_tags' => $data_tags,
					'is_master' => $is_master_facility,
					'facility_inout' => $facility_inout,
					'gender2' => $this->request->get ['gender'],
					'sort' => 'emp_last_name',
					'is_client_screen' => $is_client_screen,
					'facilities_id' => $facilities_id,
					'emp_tag_id_all' => $search_tags,
					'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
		}
	
		// var_dump($data31);
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		
		//var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		
		$client_view_options2 = $client_info ["client_view_options"];
		$client_view_options_details = $client_info ["client_details_view_options"];
		
		// echo '<pre>'; print_r($client_info); echo '</pre>'; die;
		
		// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
		$this->data ['show_client_image'] = $client_info ["show_client_image"];
		$this->data ['show_form_tag'] = $client_info ["show_form_tag"];
		$this->data ['show_task'] = $client_info ["show_task"];
		$this->data ['show_case'] = $client_info ["show_case"];
		
		//echo '<pre>'; print_r($data31); echo '</pre>'; //die;

		if($rolecalls != null && $rolecalls != ""){
		$tags = $this->model_setting_tags->getTags ( $data31 );
		}

		//echo '<pre>'; print_r($tags); echo '</pre>'; //die;
				
		if ($is_external_status_facility == '1' && $facility_info ['enable_facilityinout'] == '1') {
			
			$this->data ['is_external_status_facility'] = $is_external_status_facility;
			
			$this->data ['default_facility_id'] = $facilities_id;
		}
		
		$this->data ['total_tagsco'] = count ( $tags );
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$currentdate = date ( 'd-m-Y' );
		
		$this->load->model ( 'facilities/facilities' );
		
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
				$client_view_options_flag = 1;
			} else {
				$client_view_options_flag = 0;
			}
			
			if ($client_view_options_details2 != "" && $client_view_options_details2 != null) {
				$client_details_view_flag = 1;
			} else {
				$client_details_view_flag = 0;
			}

			$classification_names = array();
			$classification_ids = array();
			if ($tag ['tags_id'] != '0' && $tag ['tags_id'] != null) {
				
				// $status_value = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );


				if($tag ['classification_id']!="" && $tag ['classification_id']!=null){

					$tag_classification_id=$tag ['classification_id'];
				
					$tag_classification_ids=explode(",",$tag_classification_id);

					foreach($tag_classification_ids as $classification_id){

						$classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
						if($classification_value['classification_name'] != null && $classification_value['classification_name'] != ""){
						$classification_ids [] =$classification_value['tag_classification_id'];

						$classification_names [] =$classification_value['classification_name'];
						}
					}

					$classification_names = array_unique($classification_names);
				}
				
			}

			$facilities_id = $this->customer->getId ();
				
			$customers ='';
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data ['customers'] = array ();
			if (! empty ( $customer_info ['setting_data'] )) {
				$customers = unserialize ( $customer_info ['setting_data'] );
			}


			$houroutdata = array();

			$houroutdata['tags_id'] = $tag ['tags_id'];
			$houroutdata['currentdate'] = date('Y-m-d');
			
			$houroutdata['rules_operation'] = $customers ['rules_operation'];
			$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
			$houroutdata['rules_end_time'] = $customers ['rules_end_time'];

			$outcelltime = $this->model_setting_tags->getOutToCellTime ( $houroutdata );

			if($outcelltime['totaltime']!=NULL || $outcelltime['totaltime']!=""){
				$totaltime = $outcelltime['totaltime'];
			}else{
				$totaltime = 0;
			}

			//var_dump($totaltime);
			
			//$totaltime = $outcelltime['totaltime'];

			$hourout = 0;
			$percent = 0;
			
			//var_dump($totaltime);
			
			if($tag ['notes_id'] > 0){
				$noesData = $this->model_notes_notes->getnotes($tag ['notes_id']);
				if(!empty($noesData)){
					$timezone_name = $this->customer->isTimezone ();
					$timeZone = date_default_timezone_set ( $timezone_name );
					$dataprogress = array();
					$dataprogress['date_a'] = date('Y-m-d H:i:s'); 
					$dataprogress['date_added'] = $noesData ['date_added'];
					$dataprogress['duration_type'] = $customers ['duration_type'];
					$dataprogress['out_the_sell'] = $customers ['out_the_sell'];
					$dataprogress['totaltime'] = $totaltime;
					$response = $this->model_setting_tags->getHourOutProgress ( $dataprogress );
					
					//var_dump($response);
					//echo "<hr>";
					
					$hourout = $response['hourout'];
					$percent = $response['inPercent'];
				}
			}
			
			//echo '<br>percent-'.$percent;
			//echo '<br>color-'.$this->request->get ['color'];
			//echo '<br>';
			

			
			if($this->request->get ['color']!="" && $this->request->get ['color'] ==1){
				
				if($percent<=$customers['red_progress_percentage']){
					$this->data ['tags'] [] = array (
						'percent' => $percent,
						'hourout' => $hourout,
						'red_color' => $customers['red_color'],
						'red_progress_percentage' => $customers['red_progress_percentage'],
						'orange_color' => $customers['orange_color'],
						'orange_progress_percentage' => $customers['orange_progress_percentage'],
						'green_color' => $customers['green_color'],
						'green_progress_percentage' => $customers['green_progress_percentage'],
						'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
						'facility' => $tag ['facility'],
						'discharge' => $tag ['discharge'],
						'name2' => nl2br ( $client_view_options ),
						'client_details_view_flag' => $client_details_view_flag,
						'client_details' => nl2br ( $client_view_options_details2 ),
						'client_view_flag' => $client_view_options_flag,
						'facilities_id' => $tag ['facilities_id'],
						'is_movement' => $tag ['is_movement'],
						'emp_first_name' => $tag ['emp_first_name'],
						'medication_inout' => $tag ['medication_inout'],
						'status_type' => $tag ['status_type'],
						'is_facility' => $tag ['is_facility'],
						'facility_type' => $tag ['facility_type'],
						'facility_move_id' => $tag ['facility_move_id'],
						'facility_inout' => $tag ['facility_inout'],
						'tag_status_id' => $tag ['tag_status_id'],
						'client_status_type' => $tag ['type'],
						'room' => $tag ['location_name'],
						'emp_extid' => $tag ['emp_extid'],
						'ssn' => $tag ['ssn'],
						'ccn' => $tag ['ccn'],
						'is_medical' => $is_medical,
						'client_status_image' => $tag ['image'],
						'client_status_name' => $tag ['name'],
						'tag_classification_id' => $tag ['classification_id'],
						'classification_name' => $classification_names,
						'client_status_color' => $tag ['color_code'],
						'client_clssification_color' => $tag ['color_code'],
						'location_address' => $tag ['location_address'],
						'first_initial' => $tag ['emp_last_name'][0],
						'emp_last_name' => $tag ['emp_last_name'],
						'emp_tag_id' => $tag ['emp_tag_id'],
						'tags_id' => $tag ['tags_id'],
						'age' => $tag ['age'],
						'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
						'gender' => $tag['customlistvalues_name'],
						'upload_file' => $tag['enroll_image'],
						'upload_file_thumb' => $tag ['enroll_image'],
						'upload_file_thumb_1' => $tag ['enroll_image'],
						'check_img' => $check_img,
						'privacy' => $tag ['privacy'],
						'role_call' => $tag ['role_call'],
						'role_callname' => $tag['name'],
						'stickynote' => $tag ['stickynote'],
						//'role_call' => $role_call,
						'tagallforms' => $forms,
						'tagcolors' => $tagcolors,
						'tasksinfo' => $tasksinfo1,
						'taskTotal' => $taskTotal,
						'recentnote' => $lastnotesinfo [0] ['notes_description'],
						'recenttasks' => $recenttasksinfos ['description'],
						'ndate_added' => $ndate_added,
						'client_medicine' => $client_medicine,
						'tagstatus_info' => $status,
						'screenig_url' => $screenig_url,
						'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
						'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
						'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
					);
				}
				
			} else if($this->request->get ['color']!="" && $this->request->get ['color'] ==2){
				
				if($percent > $customers['red_progress_percentage'] && $percent<=$customers['orange_progress_percentage']){

					$this->data ['tags'] [] = array (
						'percent' => $percent,
						'hourout' => $hourout,
						'red_color' => $customers['red_color'],
						'red_progress_percentage' => $customers['red_progress_percentage'],
						'orange_color' => $customers['orange_color'],
						'orange_progress_percentage' => $customers['orange_progress_percentage'],
						'green_color' => $customers['green_color'],
						'green_progress_percentage' => $customers['green_progress_percentage'],
						'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
						'facility' => $tag ['facility'],
						'name2' => nl2br ( $client_view_options ),
						'client_details_view_flag' => $client_details_view_flag,
						'client_details' => nl2br ( $client_view_options_details2 ),
						'client_view_flag' => $client_view_options_flag,
						'facilities_id' => $tag ['facilities_id'],
						'is_movement' => $tag ['is_movement'],
						'emp_first_name' => $tag ['emp_first_name'],
						'medication_inout' => $tag ['medication_inout'],
						'status_type' => $tag ['status_type'],
						'is_facility' => $tag ['is_facility'],
						'facility_type' => $tag ['facility_type'],
						'facility_move_id' => $tag ['facility_move_id'],
						'facility_inout' => $tag ['facility_inout'],
						'tag_status_id' => $tag ['tag_status_id'],
						'client_status_type' => $tag ['type'],
						'room' => $tag ['location_name'],
						'emp_extid' => $tag ['emp_extid'],
						'ssn' => $tag ['ssn'],
						'ccn' => $tag ['ccn'],
						'is_medical' => $is_medical,
						'client_status_image' => $tag ['image'],
						'client_status_name' => $tag ['name'],
						'tag_classification_id' => $tag ['classification_id'],
						'classification_name' => $classification_names,
						'client_status_color' => $tag ['color_code'],
						'client_clssification_color' => $tag ['color_code'],
						'location_address' => $tag ['location_address'],
						'first_initial' => $tag ['emp_last_name'][0],
						'emp_last_name' => $tag ['emp_last_name'],
						'emp_tag_id' => $tag ['emp_tag_id'],
						'tags_id' => $tag ['tags_id'],
						'age' => $tag ['age'],
						'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
						'gender' => $tag['customlistvalues_name'],
						'upload_file' => $tag['enroll_image'],
						'upload_file_thumb' => $tag ['enroll_image'],
						'upload_file_thumb_1' => $tag ['enroll_image'],
						'check_img' => $check_img,
						'privacy' => $tag ['privacy'],
						'role_call' => $tag ['role_call'],
						'role_callname' => $tag['name'],
						'stickynote' => $tag ['stickynote'],
						//'role_call' => $role_call,
						'tagallforms' => $forms,
						'tagcolors' => $tagcolors,
						'tasksinfo' => $tasksinfo1,
						'taskTotal' => $taskTotal,
						'recentnote' => $lastnotesinfo [0] ['notes_description'],
						'recenttasks' => $recenttasksinfos ['description'],
						'ndate_added' => $ndate_added,
						'client_medicine' => $client_medicine,
						'tagstatus_info' => $status,
						'screenig_url' => $screenig_url,
						'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
						'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
						'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
					);
				}


				
				
			} else if($this->request->get ['color']!="" && $this->request->get ['color'] == 3){
				
				if($percent > $customers['orange_progress_percentage']){
				
					$this->data ['tags'] [] = array (
						'percent' => $percent,
						'hourout' => $hourout,
						'red_color' => $customers['red_color'],
						'red_progress_percentage' => $customers['red_progress_percentage'],
						'orange_color' => $customers['orange_color'],
						'orange_progress_percentage' => $customers['orange_progress_percentage'],
						'green_color' => $customers['green_color'],
						'green_progress_percentage' => $customers['green_progress_percentage'],
						'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
						'facility' => $tag ['facility'],
						'name2' => nl2br ( $client_view_options ),
						'client_details_view_flag' => $client_details_view_flag,
						'client_details' => nl2br ( $client_view_options_details2 ),
						'client_view_flag' => $client_view_options_flag,
						'facilities_id' => $tag ['facilities_id'],
						'is_movement' => $tag ['is_movement'],
						'emp_first_name' => $tag ['emp_first_name'],
						'medication_inout' => $tag ['medication_inout'],
						'status_type' => $tag ['status_type'],
						'is_facility' => $tag ['is_facility'],
						'facility_type' => $tag ['facility_type'],
						'facility_move_id' => $tag ['facility_move_id'],
						'facility_inout' => $tag ['facility_inout'],
						'tag_status_id' => $tag ['tag_status_id'],
						'client_status_type' => $tag ['type'],
						'room' => $tag ['location_name'],
						'emp_extid' => $tag ['emp_extid'],
						'ssn' => $tag ['ssn'],
						'ccn' => $tag ['ccn'],
						'is_medical' => $is_medical,
						'client_status_image' => $tag ['image'],
						'client_status_name' => $tag ['name'],
						'tag_classification_id' => $tag ['classification_id'],
						'classification_name' => $classification_names,
						'client_status_color' => $tag ['color_code'],
						'client_clssification_color' => $tag ['color_code'],
						'location_address' => $tag ['location_address'],
						'first_initial' => $tag ['emp_last_name'][0],
						'emp_last_name' => $tag ['emp_last_name'],
						'emp_tag_id' => $tag ['emp_tag_id'],
						'tags_id' => $tag ['tags_id'],
						'age' => $tag ['age'],
						'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
						'gender' => $tag['customlistvalues_name'],
						'upload_file' => $tag['enroll_image'],
						'upload_file_thumb' => $tag ['enroll_image'],
						'upload_file_thumb_1' => $tag ['enroll_image'],
						'check_img' => $check_img,
						'privacy' => $tag ['privacy'],
						'role_call' => $tag ['role_call'],
						'role_callname' => $tag['name'],
						'stickynote' => $tag ['stickynote'],
						//'role_call' => $role_call,
						'tagallforms' => $forms,
						'tagcolors' => $tagcolors,
						'tasksinfo' => $tasksinfo1,
						'taskTotal' => $taskTotal,
						'recentnote' => $lastnotesinfo [0] ['notes_description'],
						'recenttasks' => $recenttasksinfos ['description'],
						'ndate_added' => $ndate_added,
						'client_medicine' => $client_medicine,
						'tagstatus_info' => $status,
						'screenig_url' => $screenig_url,
						'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
						'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
						'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
					);

				}
			}else if($this->request->get ['color']==""){
				$this->data ['tags'] [] = array (
					'percent' => $percent,
					'hourout' => $hourout,
					'red_color' => $customers['red_color'],
					'red_progress_percentage' => $customers['red_progress_percentage'],
					'orange_color' => $customers['orange_color'],
					'orange_progress_percentage' => $customers['orange_progress_percentage'],
					'green_color' => $customers['green_color'],
					'green_progress_percentage' => $customers['green_progress_percentage'],
					'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
					'facility' => $tag ['facility'],
					'name2' => nl2br ( $client_view_options ),
					'client_details_view_flag' => $client_details_view_flag,
					'client_details' => nl2br ( $client_view_options_details2 ),
					'client_view_flag' => $client_view_options_flag,
					'facilities_id' => $tag ['facilities_id'],
					'is_movement' => $tag ['is_movement'],
					'emp_first_name' => $tag ['emp_first_name'],
					'medication_inout' => $tag ['medication_inout'],
					'status_type' => $tag ['status_type'],
					'is_facility' => $tag ['is_facility'],
					'facility_type' => $tag ['facility_type'],
					'facility_move_id' => $tag ['facility_move_id'],
					'facility_inout' => $tag ['facility_inout'],
					'tag_status_id' => $tag ['tag_status_id'],
					'client_status_type' => $tag ['type'],
					'room' => $tag ['location_name'],
					'emp_extid' => $tag ['emp_extid'],
					'ssn' => $tag ['ssn'],
					'ccn' => $tag ['ccn'],
					'is_medical' => $is_medical,
					'client_status_image' => $tag ['image'],
					'client_status_name' => $tag ['name'],
					'tag_classification_id' => $tag ['classification_id'],
					'classification_name' => $classification_names,
					'client_status_color' => $tag ['color_code'],
					'client_clssification_color' => $tag ['color_code'],
					'location_address' => $tag ['location_address'],
					'first_initial' => $tag ['emp_last_name'][0],
					'emp_last_name' => $tag ['emp_last_name'],
					'emp_tag_id' => $tag ['emp_tag_id'],
					'tags_id' => $tag ['tags_id'],
					'age' => $tag ['age'],
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'gender' => $tag['customlistvalues_name'],
					'upload_file' => $tag['enroll_image'],
					'upload_file_thumb' => $tag ['enroll_image'],
					'upload_file_thumb_1' => $tag ['enroll_image'],
					'check_img' => $check_img,
					'privacy' => $tag ['privacy'],
					'role_call' => $tag ['role_call'],
					'role_callname' => $tag['name'],
					'stickynote' => $tag ['stickynote'],
					//'role_call' => $role_call,
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'tasksinfo' => $tasksinfo1,
					'taskTotal' => $taskTotal,
					'recentnote' => $lastnotesinfo [0] ['notes_description'],
					'recenttasks' => $recenttasksinfos ['description'],
					'ndate_added' => $ndate_added,
					'client_medicine' => $client_medicine,
					'tagstatus_info' => $status,
					'screenig_url' => $screenig_url,
					'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
				);
			}


			
		}


		//echo '<pre>'; print_r($this->data ['tags']); echo '</pre>'; die;



		
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
					// 'form_href' =>
					// $this->url->link('resident/resident/tagform', '' .
					// '&forms_design_id='.$custom_form['forms_id'], 'SSL'),
					'form_href' => $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
			);
		}
		
		$this->load->model ( 'notes/clientstatus' );
		$data3 = array ();
		$data3 ['facilities_id'] = $facilities_id;
		$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
		
		$this->data ['clientstatuss'] = array ();
		foreach ( $customforms as $customform ) {
			
			$this->data ['clientstatuss'] [] = array (
					'tag_status_id' => $customform ['tag_status_id'],
					'name' => $customform ['name'],
					'facilities_id' => $customform ['facilities_id'],
					'display_client' => $customform ['display_client'] 
			);
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		$this->data ['close'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		$this->data ['tag_forms'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url2, 'SSL' ) );
		
		$this->data ['clientstatus_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatus', '' . $url2, 'SSL' ) );
		
		$this->data ['multiple_action_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/multipleaction', '' . $url2, 'SSL' ) );
		
		$this->data ['add_client_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '' . '&addclient=1', 'SSL' ) );
		// $this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . '&addclient=1&forms_design_id=' . CUSTOME_I_INTAKEID, 'SSL'));
		
		// var_dump($this->data['add_client_url']);
		
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '', 'SSL' ) );
		
		$this->data ['activenote_url'] = $this->url->link ( 'resident/resident/activenote', '', 'SSL' );
		
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if (($this->request->post ['all_roll_call'] == '1')) {
			
			$url2 = "";
			if ($this->request->post ['all_roll_call'] != null && $this->request->post ['all_roll_call'] != "") {
				$url2 .= '&all_roll_call=' . $this->request->post ['all_roll_call'];
			}
			
			$this->session->data ['role_calls'] = $this->request->post ['role_call'];
			
			$this->session->data ['success2'] = 'Head Ciount updated Successfully! ';
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&allrolecallsign=1';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
			}
		}
		
		if (($this->request->post ['all_roll_call1'] == '1')) {
			
			$url2 = "";
			
			if ($this->request->post ['all_roll_call1'] != null && $this->request->post ['all_roll_call1'] != "") {
				$url2 .= '&all_roll_call1=' . $this->request->post ['all_roll_call1'];
			}
			
			$this->session->data ['tagsids'] = $this->request->post ['tagsids'];
			$this->session->data ['role_calls'] = $this->request->post ['role_call'];
			
			$this->session->data ['success2'] = 'Head Count updated Successfully! ';
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&allrolecallsign=1';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
			}
		}
		
		if (isset ( $this->request->post ['all_roll_call'] )) {
			$this->data ['all_roll_call'] = $this->request->post ['all_roll_call'];
		} else {
			$this->data ['all_roll_call'] = '';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		$url = "";
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$url .= '&search_tags=' . $this->request->get ['search_tags'];
		}
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$url .= '&wait_list=' . $this->request->get ['wait_list'];
		}
		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$url .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$url .= '&gender=' . $this->request->get ['gender'];
		}
		
		if ($this->request->get ['add_role_call'] != null && $this->request->get ['add_role_call'] != "") {
			$url .= '&add_role_call=' . $this->request->get ['add_role_call'];
		}
		
		$this->data ['tags_total_2'] = $tags_total_2;
		// var_dump($url);
		
		// var_dump($tags_total_2);
		if ($this->request->get ['add_role_call'] != '1') {
			$pagination = new Pagination ();
			$pagination->total = $tags_total_2;
			$pagination->page = $page;
			$pagination->limit = $config_admin_limit;
			$pagination->text = $this->language->get ( 'text_pagination' );
			$pagination->url = $this->url->link ( 'resident/resident', 'page={page}' . $url, 'SSL' );
			
			$this->data ['pagination'] = $pagination->render ();
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/hourout.php';
		$this->children = array (
				'common/headerclient',
				'common/footerclient' 
		);
		$this->response->setOutput ( $this->render () );
	}
	public function ajaxresidenthourout() {

		if (! $this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		unset ( $this->session->data ['show_hidden_info'] );
		
		$this->language->load ( 'notes/notes' );
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( 'Clients' );
		
		if ($this->request->get ['search_facilities_id'] > 0) {
			$this->session->data ['search_facilities_id'] = $this->request->get ['search_facilities_id'];
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		if ($this->request->get ['searchall'] == '1') {
			unset ( $this->session->data ['search_facilities_id'] );
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['facilityname'] = $this->customer->getfacility ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facilities_info ['is_discharge_form_enable'] == '1') {
			$this->data ['dis_form'] = '1';
		} else {
			$this->data ['dis_form'] = '2';
		}
		
		if ($this->request->get ['gender'] != null && $this->request->get ['gender'] != "") {
			$this->data ['add_role_call_check'] = '1';
		}
		$this->data ['add_role_call'] = $this->request->get ['add_role_call'];
		
		if (($this->request->get ['searchtag'] == '1')) {
			$url = "";
			if ($this->request->post ['search_tags'] != null && $this->request->post ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->post ['search_tags'];
			}
			if ($this->request->post ['wait_list'] != null && $this->request->post ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->post ['wait_list'];
			}
			if ($this->request->post ['search_tags_tag_id'] != null && $this->request->post ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->post ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if (($this->request->get ['searchtag'] == '2')) {
			$url = "";
			if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
				$url .= '&search_tags=' . $this->request->get ['search_tags'];
			}
			if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
				$url .= '&wait_list=' . $this->request->get ['wait_list'];
			}
			if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
				$url .= '&search_tags_tag_id=' . $this->request->get ['search_tags_tag_id'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident', '' . $url, 'SSL' ) );
		}
		
		if ($this->request->get ['search_tags'] != null && $this->request->get ['search_tags'] != "") {
			$this->data ['search_tags'] = $this->request->get ['search_tags'];
		}
		if ($this->request->get ['wait_list'] != null && $this->request->get ['wait_list'] != "") {
			$this->data ['wait_list'] = $this->request->get ['wait_list'];
		}
		if ($this->request->get ['search_tags_tag_id'] != null && $this->request->get ['search_tags_tag_id'] != "") {
			$this->data ['search_tags_tag_id'] = $this->request->get ['search_tags_tag_id'];
			$search_tags = '';
		} else {
			$search_tags = $this->request->get ['search_tags'];
		}
		
		// // $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
		// $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'setting/image' );
		
		$this->load->model ( 'notes/clientstatus' );
		
		$ddss = array ();
		if ($facilities_info ['client_facilities_ids'] != null && $facilities_info ['client_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $facilities_info ['client_facilities_ids'];
			
			$ddss [] = $this->customer->getId ();
			$sssssdd = implode ( ",", $ddss );
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
			$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$config_admin_limit = "12";
		
		// var_dump($config_admin_limit);
		$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_is_master ['enable_facilityinout'] != '1') {
			$is_client_screen = '0';
		} else {
			$is_client_screen = '1';
			$facility_inout = '2';
		}
		
		if ($facilities_is_master ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facilities_is_master ['is_master_facility'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$data31 = array ();
		
		if ($this->request->get ['client_status'] == "1" || $this->request->get ['client_status'] == "2") {
			$inclint = array (
					0 
			);
		} else {
			$inclint = array ();
		}

		$outcount = array ();
		
		$hourout_arr = array();

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
		
		
		
		if ($this->request->get ['search_tags'] != "" && $this->request->get ['search_tags'] != null) {
			
			$data31 = array (
					'status' => 1,
					'discharge' => 1,
					'emp_tag_id_all' => $this->request->get ['search_tags'],
					// 'role_call' => $rolecall,
					'rolecalls' => $rolecalls,
					'gender2' => $this->request->get ['gender'],
					'sort' => 'emp_last_name',
					'is_master' => $is_master_facility,
					'is_client_screen' => $is_client_screen,
					'facilities_id' => $facilities_id,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit,
					'wait_list' => $this->request->get ['wait_list'],
					'client_status' => $this->request->get ['client_status'],
					'all_record' => '1' 
			);
		} else {
			
			$data31 = array (
					'status' => 1,
					'discharge' => 1,
					// 'role_call' =>$rolecall,
					'rolecalls' => $rolecalls,
					'emp_tag_id_all' => $data_tags,
					'is_master' => $is_master_facility,
					'gender2' => $this->request->get ['gender'],
					'client_status' => $this->request->get ['client_status'],
					'sort' => 'emp_last_name',
					'facilities_id' => $facilities_id,
					'is_client_screen' => $is_client_screen,
					'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
					'wait_list' => $this->request->get ['wait_list'],
					'all_record' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
		}
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		
		// var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		
		$client_view_options2 = $client_info ["client_view_options"];
		$client_view_options_details = $client_info ["client_details_view_options"];
		
		
		$tags = $this->model_setting_tags->getTags ( $data31 );
		
		
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$currentdate = date ( 'd-m-Y' );
		
		$this->load->model ( 'facilities/facilities' );
		
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
				$client_view_options_flag = 1;
			} else {
				$client_view_options_flag = 0;
			}
			
			if ($client_view_options_details2 != "" && $client_view_options_details2 != null) {
				$client_details_view_flag = 1;
			} else {
				$client_details_view_flag = 0;
			}

			$classification_names = array();
			$classification_ids = array();
			if ($tag ['tags_id'] != '0' && $tag ['tags_id'] != null) {
				
				// $status_value = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );


				if($tag ['classification_id']!="" && $tag ['classification_id']!=null){

					$tag_classification_id=$tag ['classification_id'];
				
					$tag_classification_ids=explode(",",$tag_classification_id);

					foreach($tag_classification_ids as $classification_id){

						$classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
						if($classification_value['classification_name'] != null && $classification_value['classification_name'] != ""){
						$classification_ids [] =$classification_value['tag_classification_id'];

						$classification_names [] =$classification_value['classification_name'];
						}
					}

					$classification_names = array_unique($classification_names);
				}
				
			}

			$facilities_id = $this->customer->getId ();
				
			$customers ='';
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data ['customers'] = array ();
			if (! empty ( $customer_info ['setting_data'] )) {
				$customers = unserialize ( $customer_info ['setting_data'] );
			}

			
			$houroutdata = array();

			$houroutdata['tags_id'] = $tag ['tags_id'];
			$houroutdata['currentdate'] = date('Y-m-d');
			$houroutdata['rules_operation'] = $customers ['rules_operation'];
			$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
			$houroutdata['rules_end_time'] = $customers ['rules_end_time'];

			$outcelltime = $this->model_setting_tags->getOutToCellTime ( $houroutdata);

			$hourout='';

			$totaltime = '';
			$totaltime = $outcelltime['totaltime'];

			if($tag ['notes_id'] > 0){
				$noesData = $this->model_notes_notes->getnotes($tag ['notes_id']);
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
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

			
			$json [] = array (
					'name' => $tag ['emp_first_name'] . ' ' . $emp_last_name,
					'facility' => $tag ['facility'],
					'discharge' => $tag ['discharge'],
					'percent' => $inPercent,
					'hourout' => $hourout,
					'name2' => nl2br ( $client_view_options ),
					'client_details_view_flag' => $client_details_view_flag,
					'client_details' => nl2br ( $client_view_options_details2 ),
					'client_view_flag' => $client_view_options_flag,
					'facilities_id' => $tag ['facilities_id'],
					'is_movement' => $tag ['is_movement'],
					'emp_first_name' => $tag ['emp_first_name'],
					'medication_inout' => $tag ['medication_inout'],
					'status_type' => $tag ['status_type'],
					'is_facility' => $tag ['is_facility'],
					'facility_type' => $tag ['facility_type'],
					'facility_move_id' => $tag ['facility_move_id'],
					'facility_inout' => $tag ['facility_inout'],
					
					'tag_status_id' => $tag ['tag_status_id'],
					
					'client_status_type' => $tag ['type'],
					
					'room' => $tag ['location_name'],
					'emp_extid' => $tag ['emp_extid'],
					'ssn' => $tag ['ssn'],
					'ccn' => $tag ['ccn'],
					'is_medical' => $is_medical,
					'client_status_image' => $tag ['image'],
					'client_status_name' => $tag ['name'],
					'tag_classification_id' => $tag ['classification_id'],
					'classification_name' => $classification_names,
					'client_status_color' => $tag ['color_code'],
					//'client_clssification_color' => $tag ['color_code'],
					'location_address' => $tag ['location_address'],
					'first_initial' => $tag ['emp_last_name'][0],
					'emp_last_name' => $tag ['emp_last_name'],
					'emp_tag_id' => $tag ['emp_tag_id'],
					'tags_id' => $tag ['tags_id'],
					'age' => $tag ['age'],
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'gender' => $tag['customlistvalues_name'],
					'upload_file' => $tag['enroll_image'],
					'upload_file_thumb' => $tag ['enroll_image'],
					'upload_file_thumb_1' => $tag ['enroll_image'],
					'check_img' => $check_img,
					'privacy' => $tag ['privacy'],
					'role_call' => $tag ['role_call'],
					'role_callname' => $tag['name'],
					'stickynote' => $tag ['stickynote'],
					// 'role_call' => $role_call,
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'tasksinfo' => $tasksinfo1,
					'taskTotal' => $taskTotal,
					'recentnote' => $lastnotesinfo [0] ['notes_description'],
					'recenttasks' => $recenttasksinfos ['description'],
					'ndate_added' => $ndate_added,
					'client_medicine' => $client_medicine,
					'tagstatus_info' => $status,
					'screenig_url' => $screenig_url,
					'tag_href' => $this->url->link ( 'resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'assignteam_href' => $this->url->link ( 'resident/assignteam', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ),
					'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . $url122, 'SSL' ) 
			);
		}
		
		$show_client_image = $client_info ["show_client_image"];
		$show_form_tag = $client_info ["show_form_tag"];
		$show_task = $client_info ["show_task"];
		$show_case = $client_info ["show_case"];
		
		$total_in_url = $this->url->link ( 'resident/resident&in=1', '' . $url1, 'SSL' );
		$total_out_url = $this->url->link ( 'resident/resident&out=1', '' . $url1, 'SSL' );
		$non_url = $this->url->link ( 'resident/resident&gender=3', '' . $url1, 'SSL' );
		
		$total_url = $this->url->link ( 'resident/resident', '', 'SSL' );
		
		$notes_url = $this->url->link ( 'notes/notes/insert', '', 'SSL' );
		
		$sticky_note = $this->url->link ( 'resident/resident/getstickynote&close=1', '', 'SSL' );
		
		$dailycensus = $this->url->link ( 'resident/dailycensus', '', 'SSL' );
		
		$clientfile = $this->url->link ( 'resident/resident/clientfile', '', 'SSL' );
		
		$logout = $this->url->link ( 'common/logout', '', 'SSL' );
		
		$task_lists = str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/headertasklist', '' . $url1, 'SSL' ) );
		
		$task_lists2 = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatus', '' . $url1, 'SSL' ) );
		
		$case_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/cases/dashboard', '', 'SSL' ) );
		
		$add_client_url1 = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
		$assignteam = str_replace ( '&amp;', '&', $this->url->link ( 'resident/assignteam', '', 'SSL' ) );
		
		$close = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		$tag_forms = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url2, 'SSL' ) );
		
		$clientstatus_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatus', '' . $url2, 'SSL' ) );
		
		$multiple_action_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/multipleaction', '' . $url2, 'SSL' ) );
		
		$add_tag_medication_url = $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' );
		
		$add_client_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '' . '&addclient=1', 'SSL' ) );
		
		$action = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '', 'SSL' ) );
		
		$activenote_url = $this->url->link ( 'resident/resident/activenote', '', 'SSL' );
		
		// $this->response->setOutput(json_encode($json));
		$template = new Template ();
		$template->data ['tags'] = $json;
		$template->data ['show_client_image'] = $show_client_image;
		$template->data ['show_form_tag'] = $show_form_tag;
		$template->data ['show_task'] = $show_task;
		$template->data ['show_case'] = $show_case;
		$template->data ['total_in_url'] = $total_in_url;
		$template->data ['total_out_url'] = $total_out_url;
		$template->data ['non_url'] = $non_url;
		$template->data ['total_url'] = $total_url;
		$template->data ['notes_url'] = $notes_url;
		$template->data ['default_facility_id'] = $facilities_id;
		
		$template->data ['sticky_note'] = $sticky_note;
		$template->data ['dailycensus'] = $dailycensus;
		$template->data ['clientfile'] = $clientfile;
		$template->data ['logout'] = $logout;
		$template->data ['task_lists'] = $task_lists;
		$template->data ['task_lists2'] = $task_lists2;
		$template->data ['case_url'] = $case_url;
		$template->data ['add_client_url1'] = $add_client_url1;
		$template->data ['assignteam'] = $assignteam;
		$template->data ['close'] = $close;
		$template->data ['tag_forms'] = $tag_forms;
		$template->data ['clientstatus_url'] = $clientstatus_url;
		$template->data ['multiple_action_url'] = $multiple_action_url;
		$template->data ['add_client_url'] = $add_client_url;
		$template->data ['add_tag_medication_url'] = $add_tag_medication_url;
		$template->data ['action'] = $action;
		$template->data ['activenote_url'] = $activenote_url;
		$template->data ['is_external_status_facility'] = $facilities_is_master ['enable_facilityinout'];
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/notification/ajaxresidenthourout.php' )) {
			$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/notification/ajaxresidenthourout.php' );
		}
		
		// var_dump($html);
		$ajax_status = 1;
		if (empty ( $tags )) {
			$ajax_status = 2;
		}
		
		$json1 = array ();
		$json1 ['ajax_status'] = $ajax_status;
		$json1 ['html'] = $html;
		
		$this->response->setOutput ( json_encode ( $json1 ) );
	}
	
	
	 /*Created By Adam 20-04-2021 */
	public function allClientSelect() {

		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'customer/customer' );
		$this->load->model ( 'facilities/facilities' );

	  
		
		if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
			$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}

		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		// $facilities_id = $this->customer->getId ();	
		

		/*$allclientData = array ();
		$allclientData ['status'] = '1';
		$allclientData ['discharge'] = '0';
		$allclientData ['facilities_id'] = $facilities_id;*/

		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );

		if ($facility ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facility ['is_master_facility'];
		}

		if ($facility ['enable_facilityinout'] != '1') {
			
			$is_client_screen = '0';
		} else {
			
			$is_client_screen = '1';
		}

		$sort = 'emp_last_name';

		$order = 'ASC';

		$data31333 = array (
				'sort' => $sort,
				'order' => $order,
				'status' => 1,
				'discharge' => 1,
				// 'role_call' => '1',
				'gender2' => $this->request->get ['gender'],
				//'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_master' => $is_master_facility,
				'is_client_screen' => $is_client_screen,
				'emp_tag_id_all' => $search_tags,
				'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
				'wait_list' => $this->request->get ['wait_list'],
				'client_status' => $this->request->get ['client_status'],
				'all_record' => '1',
				
		);
		

		$allclients_info = $this->model_setting_tags->getTags ( $data31333 );
		
		
		$this->data ['client_datas'] = array ();
		$this->data ['tags_ids'] = array ();
		foreach ( $allclients_info as $allclient_info ) {

			$facility = $this->model_facilities_facilities->getfacilities ( $allclient_info ['facilities_id'] );
			
			$this->data ['client_datas'] [] = array (
			'tags_id' => $allclient_info ['tags_id'],
			'discharge' => $allclient_info ['discharge'],
			'tags_name' => $allclient_info ['emp_last_name']." ".$allclient_info ['emp_first_name'],
			'facility'=>$facility['facility']					
			);

			array_push($this->data ['tags_ids'] ,$allclient_info ['tags_id']);
		}

		 $tags = implode(', ',$this->data ['tags_ids'] );

		 $this->data ['all_tags']=$tags;	


		
		$this->data ['clientstatuses_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatuses', '', 'SSL' ) );
		
		$this->data ['notes_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' , 'SSL' ) );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			

			$this->data ['dischargeclients_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&allrolecallsign=2', '' , 'SSL' ) );
			$this->data ['facility_in_out_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&allrolecallsign=1', '' . $url2, 'SSL' ) );
		} else {

			$this->data ['dischargeclients_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/dischargeclients', '' , 'SSL' ) );
			$this->data ['facility_in_out_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) );
			
			
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/allclient_lists.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function secondsToTime($seconds) {
		$dtF = new \DateTime('@0');
		$dtT = new \DateTime("@$seconds");
		
		$since_start = $dtF->diff($dtT);
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


	public function allhouroutpopup(){
		
		
		if (($this->request->post ['form_submit'] == '1')) {
			
			
			$url2 = "";
			
			$this->session->data ['success2'] = 'Report generating successfully!';
			
			if ($this->request->post ['note_date_from'] != null && $this->request->post ['note_date_from'] != "") {
				$url2 .= '&note_date_from=' . $this->request->post ['note_date_from'];
			}
			if ($this->request->post ['note_date_to'] != null && $this->request->post ['note_date_to'] != "") {
				$url2 .= '&note_date_to=' . $this->request->post ['note_date_to'];
			}
			
			$this->redirect ( $this->url->link ( 'resident/resident/allhouroutpopup', '' . $url2, 'SSL' ) );
		}
		
		$url2 = "";
			
			
		if ($this->request->get ['note_date_from'] != null && $this->request->get ['note_date_from'] != "") {
			$url2 .= '&note_date_from=' . $this->request->get ['note_date_from'];
		}
		if ($this->request->get ['note_date_to'] != null && $this->request->get ['note_date_to'] != "") {
			$url2 .= '&note_date_to=' . $this->request->get ['note_date_to'];
		}
		$this->data ['resetUrl_private'] =  str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allhouroutreport', '' . $url2, 'SSL' ) );
		
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		if (isset ( $this->request->post ['note_date_from'] )) {
			$this->data ['note_date_from'] = $this->request->post ['note_date_from'];
		} else {
			$this->data ['note_date_from'] = date('m-d-Y');
		}
		
		if (isset ( $this->request->post ['note_date_to'] )) {
			$this->data ['note_date_to'] = $this->request->post ['note_date_to'];
		} else {
			$this->data ['note_date_to'] = date('m-d-Y');
		}
		
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/allhouroutpopup.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function allhouroutreport(){
		
		
		$this->language->load ( 'notes/notes' );
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'setting/image' );
		
		$this->load->model ( 'notes/clientstatus' );
		
		$ddss = array ();
		
		
		if ($facilities_info ['client_facilities_ids'] != null && $facilities_info ['client_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $facilities_info ['client_facilities_ids'];
			
			$ddss [] = $facilities_id;
			$sssssdd = implode ( ",", $ddss );
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		
		
		$config_admin_limit = "12";
		
		// var_dump($config_admin_limit);
		$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_is_master ['enable_facilityinout'] != '1') {
			$is_client_screen = '0';
		} else {
			$is_client_screen = '1';
			$facility_inout = '2';
		}
		
		if ($facilities_is_master ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facilities_is_master ['is_master_facility'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$data31 = array ();
		
		if ($this->request->get ['client_status'] == "1" || $this->request->get ['client_status'] == "2") {
			$inclint = array (
					0 
			);
		} else {
			$inclint = array ();
		}

		$outcount = array ();
		
		$hourout_arr = array();

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

			$rule_action_content = unserialize($customform['rule_action_content']);

			//echo '<pre>dddddddd'; print_r($rule_action_content['out_from_cell']); echo '</pre>'; //die;
			
			if ($rule_action_content['out_from_cell'] == "1") {
				$hourout_arr [] = $customform ['tag_status_id'];
			}
			
			
		}
		
		$facility_inout = '1';	

		if ($hourout_arr != null && $hourout_arr != "") {
			$hourout_arr = implode ( ",", $hourout_arr );
			$rolecalls = $hourout_arr;
		}
		
		if ($this->request->get ['note_date_from'] != null && $this->request->get ['note_date_from'] != "") {
			
			$date = str_replace ( '-', '/', $this->request->get ['note_date_from']);
		
			$res = explode ( "/", $date );
			$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$note_date_from = date ( 'Y-m-d', strtotime ( $createdate1 ) ).' 00:00:00';
			
		} else {
			$note_date_from = date('Y-m-d').' 00:00:00';
		}
		
		if ($this->request->get ['note_date_to'] != null && $this->request->get ['note_date_to'] != "") {
			$date = str_replace ( '-', '/', $this->request->get ['note_date_to']);
		
			$res = explode ( "/", $date );
			$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$note_date_to = date ( 'Y-m-d', strtotime ( $createdate1 ) );
			
		} else {
			$note_date_to = date('Y-m-d');
		}
		
			
		$data31 = array (
				'status' => 1,
				'discharge' => 1,
				// 'role_call' =>$rolecall,
				'rolecalls' => $rolecalls,
				'emp_tag_id_all' => $data_tags,
				'is_master' => $is_master_facility,
				'gender2' => $this->request->get ['gender'],
				'client_status' => $this->request->get ['client_status'],
				'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_client_screen' => $is_client_screen,
				'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
				'wait_list' => $this->request->get ['wait_list'],
				'note_date_from' => $note_date_from,
				'note_date_to' => $note_date_to,
				'all_record' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
		);
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id);
		
		$unique_id = $facility ['customer_key'];
		
		// var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		
		$client_view_options2 = $client_info ["client_view_options"];
		$client_view_options_details = $client_info ["client_details_view_options"];
		
		
		$tags = $this->model_setting_tags->getOutToTimes ( $data31 );
		
		
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		
		$this->load->model ( 'setting/timezone' );
			
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		
		date_default_timezone_set ( $facilitytimezone );
		$currentdate = date ( 'd-m-Y' );
		
		$this->load->model ( 'facilities/facilities' );
		
		foreach ( $tags as $tag1 ) {
			if($tag1 ['tags_id'] > 0){
			$tag = $this->model_setting_tags->getTag ( $tag1 ['tags_id']);
			
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
				$client_view_options_flag = 1;
			} else {
				$client_view_options_flag = 0;
			}
			
			if ($client_view_options_details2 != "" && $client_view_options_details2 != null) {
				$client_details_view_flag = 1;
			} else {
				$client_details_view_flag = 0;
			}

			$classification_names = array();
			$classification_ids = array();
			if ($tag ['tags_id'] != '0' && $tag ['tags_id'] != null) {
				
				// $status_value = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );


				if($tag ['classification_id']!="" && $tag ['classification_id']!=null){

					$tag_classification_id=$tag ['classification_id'];
				
					$tag_classification_ids=explode(",",$tag_classification_id);

					foreach($tag_classification_ids as $classification_id){

						$classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
						if($classification_value['classification_name'] != null && $classification_value['classification_name'] != ""){
						$classification_ids [] =$classification_value['tag_classification_id'];

						$classification_names [] =$classification_value['classification_name'];
						}
					}

					$classification_names = array_unique($classification_names);
				}
				
			}

			
			$houroutdata = array();
			$houroutdata['tags_id'] = $tag ['tags_id'];
			$houroutdata['note_date_from'] = $note_date_from;
			$houroutdata['note_date_to'] = $note_date_to;
			$houroutdata['rules_operation'] = $customers ['rules_operation'];
			$houroutdata['rules_start_time'] = $customers ['rules_start_time'];
			$houroutdata['rules_end_time'] = $customers ['rules_end_time'];
			$outcelltimes = $this->model_setting_tags->getOutToCellTimes ( $houroutdata );
			
			$alltotaltime = 0;
			$status_total_time = 0;
			$notesmedicationtimes = array();
			foreach($outcelltimes as $sttime){
				
				$rule_action_content = unserialize($sttime['rule_action_content']);
				
				$status_total_time1 = 0;
				if ($sttime['years'] > 0) {
					$status_total_time1 = 60 * 24 * 365 * $sttime['years'] ;
				}

				if ($sttime['months'] > 0) {
					$status_total_time1 += 60 * 24 * 30 * $sttime['months'];
				}

				if ($sttime['days'] > 0) {
					$status_total_time1 += 60 * 24 * $sttime['days'];
				}

				if ($sttime['hours'] > 0) {
					$status_total_time1 += 60 * $sttime['hours'];
				}
				
				if ($sttime['minutes'] > 0) {
					$status_total_time1 += $sttime['minutes'];
				}
				
			
				$name = $sttime['name'];
				$alltotaltime = $alltotaltime + $status_total_time1;
				$outcelltimtime = $this->secondsToTime($status_total_time1*60);
				
				
				$noteinfo = $this->model_notes_notes->getnotes ( $sttime ['notes_id'] );
				$notesmedicationtimes [] = array (
					'name' => $name,
					'notes_description' => $noteinfo['notes_description'],
					'outcelltimtime' => $outcelltimtime,
					
				);
			}
			
			$status_total_time = $this->secondsToTime($alltotaltime*60);
			
			//var_dump($status_total_time);
			
			
			if(!empty($notesmedicationtimes)){
			$json [] = array (
					'name' => $tag ['emp_last_name'] . ' ' . $tag ['emp_first_name'],
					'facility' => $tag ['facility'],
					'status_total_time' => $status_total_time,
					'notesmedicationtimes' => $notesmedicationtimes,
					'name2' => nl2br ( $client_view_options ),
					'client_details_view_flag' => $client_details_view_flag,
					'client_details' => nl2br ( $client_view_options_details2 ),
					'client_view_flag' => $client_view_options_flag,
					'facilities_id' => $tag ['facilities_id'],
					'is_movement' => $tag ['is_movement'],
					'emp_first_name' => $tag ['emp_first_name'],
					'medication_inout' => $tag ['medication_inout'],
					'status_type' => $tag ['status_type'],
					'is_facility' => $tag ['is_facility'],
					'facility_type' => $tag ['facility_type'],
					'facility_move_id' => $tag ['facility_move_id'],
					'facility_inout' => $tag ['facility_inout'],
					
					'tag_status_id' => $tag ['tag_status_id'],
					
					'client_status_type' => $tag ['type'],
					
					'room' => $tag ['location_name'],
					'emp_extid' => $tag ['emp_extid'],
					'ssn' => $tag ['ssn'],
					'ccn' => $tag ['ccn'],
					'is_medical' => $is_medical,
					'client_status_image' => $tag ['image'],
					'client_status_name' => $tag ['name'],
					'tag_classification_id' => $tag ['classification_id'],
					'classification_name' => $classification_names,
					'client_status_color' => $tag ['color_code'],
					//'client_clssification_color' => $tag ['color_code'],
					'location_address' => $tag ['location_address'],
					'first_initial' => $tag ['emp_last_name'][0],
					'emp_last_name' => $tag ['emp_last_name'],
					'emp_tag_id' => $tag ['emp_tag_id'],
					'tags_id' => $tag ['tags_id'],
					'age' => $tag ['age'],
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'gender' => $tag['customlistvalues_name'],
					'upload_file' => $tag['enroll_image'],
					'upload_file_thumb' => $tag ['enroll_image'],
					'upload_file_thumb_1' => $tag ['enroll_image'],
					'check_img' => $check_img,
					'privacy' => $tag ['privacy'],
					'role_call' => $tag ['role_call'],
					'role_callname' => $tag['name'],
					'stickynote' => $tag ['stickynote'],
					// 'role_call' => $role_call,
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'tasksinfo' => $tasksinfo1,
					'taskTotal' => $taskTotal,
					'recentnote' => $lastnotesinfo [0] ['notes_description'],
					'recenttasks' => $recenttasksinfos ['description'],
					'ndate_added' => $ndate_added,
					'client_medicine' => $client_medicine,
					'tagstatus_info' => $status,
					'screenig_url' => $screenig_url,
					 
			);
			}
		}
		}
		
		
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';
		$html .= '<style>

	td {
		padding: 10px;
		margin: 10px;
	   border: 1px solid #B8b8b8;
	   line-height:20.2px;
	   display:table-cell;
		padding:5px;
	}
	</style>
	<style>
		
		.sticky + .content {
		  padding-top: 102px;
		}
		
		</style>
		<style type="text/css" media="print">
		@page 
		{
			size:  auto;   /* auto is the initial value */
			margin: 0mm;  /* this affects the margin in the printer settings */
		}

		
		@media print {
			a[href]:after {
				content: none !important;
			}
		}
		</style>
	';
		
		$html .= '
		<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
			<tr>  
				
				<td width="100%" style="text-align:center;">
				<h2>Hour Out Log</h2>
				
				</td>
				
			</tr>
		</table>
		
		<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
			<tr>  
				
				<td> Date: ' . $this->request->get ['note_date_from'] . ' to '.$this->request->get ['note_date_to'].'</td>
				
				<td> </td>
			</tr>
		</table>';
		
		
		foreach($json as $atag){
			$html .= ' <table width="100%" style="border:none;background-color: #000; color: #fff;" cellpadding="2" cellspacing="0" >
				<tr>  
					<td width="100%" style="padding:20px;text-align:left;width:100%;padding: 10px;margin: 10px; border: 1px solid #B8b8b8; line-height:20.2px;display:table-cell;padding:5px;" > '.$atag['emp_last_name'].'  '.$atag['emp_first_name'].' – Total Time Out - : '.$atag['status_total_time'].'</td>
				</tr>
			</table>';
			
			foreach($atag['notesmedicationtimes'] as $outcelltim){ 
				$html .= ' <table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
				$html .= '<tr> ';
				
				$html .= '<td>' . $outcelltim['name'] . '</td>';
				$html .= '<td>' . $outcelltim['notes_description'] . '</td>';
				$html .= '<td>'.$outcelltim['outcelltimtime'].'</td>';
				
				$html .= '</tr>';
				$html .= '</table>';
			}
		}
		
		
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		
		$pdf->lastPage ();
		
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
		
	}
}