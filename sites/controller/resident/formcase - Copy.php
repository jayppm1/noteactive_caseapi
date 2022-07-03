<?php
class Controllerresidentformcase extends Controller {
	private $error = array ();
	public function cases() {
		$url = "";
		try {
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->language->load ( 'notes/notes' );
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
			$this->document->setTitle ( 'Case File' );
			
			if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
				
				$tags_id = $this->request->get ['tags_id'];
				
				$url .= '&tags_id=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
			}
			
			$tag_data = array ();
			$tag_data ['tags_id'] = $tags_id;
			$form_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
			
			if (! empty ( $form_info )) {
				$this->data ['client_name'] = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
			} else {
				$this->data ['client_name'] = '';
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$status .= $this->request->get ['status'];
				
				$this->data ['status'] = $this->request->get ['status'];
			} else {
				$this->data ['status'] = 0;
			}
			
			
			$data = array (
					'sort' => $sort,
					'order' => $order,
					'is_case' => '1',
					'status' => $status,
					'case_number' => 1,
					'tags_id' => $tags_id,
					'add_case' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			// echo '<pre>fff'; print_r($data); echo '</pre>'; //die;
			
			// $form_total = $this->model_form_form->getTotalforms2 ( $data );
			
			// $allforms = $this->model_form_form->gettagsforms ( $data );
			
			$this->load->model ( 'resident/casefile' );
			$allforms = $this->model_resident_casefile->getcasefiles ( $data );
			
			// echo '<pre>fff'; print_r($allforms); echo '</pre>'; //die;
			
			$this->data ['tagsforms'] = array ();
			
			foreach ( $allforms as $allform ) {
				
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
					$user_id = $note_info ['user_id'];
					$signature = $allform ['signature'];
					$notes_pin = $allform ['notes_pin'];
					$notes_type = $allform ['notes_type'];
					
					if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['date_added'] ) );
					} else {
						$form_date_added = '';
					}
					// echo 'ssss';
				}
				
				if ($allform ['case_status'] == '0') {
					$client_status = 'Open';
				} else if ($allform ['case_status'] == '1') {
					$client_status = 'Closed';
				} else if ($allform ['case_status'] == '2') {
					$client_status = 'Marked Final';
				}
				
				$this->data ['tagsforms'] [] = array (
						'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
						'forms_id' => $allform ['forms_id'],
						'notes_type' => $notes_type,
						'user_id' => $user_id,
						'tags_id' => $allform ['tags_id'],
						'signature' => $signature,
						'forms_design_id' => $allform ['custom_form_type'],
						'notes_id' => $allform ['notes_id'],
						'case_number' => $allform ['case_number'],
						'case_status' => $client_status,
						'notes_pin' => $notes_pin,
						'form_date_added' => $form_date_added 
				);
			}
			

			
			$this->data ['add_case_url'] = $this->url->link ( 'resident/formcase/addcase', $url, 'SSL' );
			
			$this->data ['case_url'] = $this->url->link ( 'resident/formcase/cases', $url, 'SSL' );
			
			$this->data ['view_case_url'] = $this->url->link ( 'resident/formcase/viewcase', '' . $url, 'SSL' );
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/resident/case.php';
			$this->children = array (
					'common/headerclient',
					'common/footerclient' 
			);
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites resident formcases ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'cases', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function viewcase() {
		try {
			$this->load->model ( 'facilities/online' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'resident/casefile' );
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( 'Case Detail' );
			
			if (! $this->customer->isLogged ()) {
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			
			$url = "";
			
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

			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				
				$data = array (
						'case_number' => $this->request->get ['case_number'],
						'facilities_id' => $this->customer->getId () 
				);
				
				$case_info = $this->model_resident_casefile->getcasefileByCasenumber ( $data );
				
				//echo '<pre>'; print_r($case_info); echo '</pre>'; //die;

				$this->request->get ['case_file_id'] = $case_info ['notes_by_case_file_id'];
				$this->request->get ['tags_id'] = $case_info ['tags_ids'];

			} else {

				$case_file_id = '';

			}
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$this->data ['case_file_id'] = $this->request->get ['case_file_id'];
				$case_file_id = $this->request->get ['case_file_id'];
				$url .= '&case_file_id=' . $this->request->get ['case_file_id'];
			} else {
				$case_file_id = '';
			}
			
			if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
				
				$tags_id = $this->request->get ['tags_id'];
				
				$url .= '&tags_id=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
			}
			if ($this->request->get ['case_number'] != "" && $this->request->get ['case_number'] != null) {
			
				//$tags_id = $this->request->get ['case_number'];
			
				$url .= '&case_number=' . $this->request->get ['case_number'];
			
				$this->data ['case_number'] = $this->request->get ['case_number'];
			}
			
			$tag_data = array ();
			$tag_data ['tags_id'] = $tags_id;
			$form_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
			//echo '<pre>fff'; print_r($form_info); echo '</pre>'; //die;
			if (! empty ( $form_info )) {
				$this->data ['client_name'] = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
				
				$client_name = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
			} else {
				$this->data ['client_name'] = '';
				$client_name = '';
			}

			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				
				$data = array (
						'case_file_id' => $this->request->get ['case_file_id'],
						'facilities_id' => $this->customer->getId () 
				);
				
				//$case_info = $this->model_resident_casefile->getCaseNumber ( $data );
				
				//echo '<pre>'; print_r($case_info); echo '</pre>'; //die;
				
				//$this->data ['case_number'] = $this->data ['case_number'];
				
			} else {
				
				$case_number_prefix = '';
				if ($client_name != '') {
					foreach ( preg_split ( '#[^a-z]+#i', $client_name, - 1, PREG_SPLIT_NO_EMPTY ) as $word ) {
						$case_number_prefix .= $word [0];
					}
				} else {
					$case_number_prefix = '';
				}

				$this->session->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
				$this->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );

			}

			if($this->request->get ['status'] != null && $this->request->get ['status'] != null){
				$this->data ['status'] = $this->request->get ['status'];
				$this->data ['status2'] = $this->request->get ['status'];
			}else{

				if(isset($case_info['case_status']) && $case_info['case_status']!=''){
					$this->data ['status'] = $case_info['case_status'];
				}else{
					$this->data ['status'] = '';
				}
			}

			
			
			$data = array (
					'sort' => $sort,
					'order' => $order,
					'is_case' => 0,
					'page_name' => 'viewcase',
					// 'status' => $status,
					'case_number' => 1,
					'case_file_id' => $case_file_id,
					//'tags_id' => $tags_id,
					'add_case' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			//echo '<pre>'; print_r($data); echo '</pre>'; die;
			// $form_total = $this->model_form_form->getTotalforms2 ( $data );
			
			$this->load->model ( 'resident/casefile' );
			
			$allform1 = $this->model_resident_casefile->getcasefile ( $data );

			//echo '<pre>'; print_r($allform); echo '</pre>'; die;
			
			$this->data ['case_status'] = $allform1 ['case_status'];
			
			if($allform1 ['forms_ids']!=''){
				$forms_ids_arr = explode ( ',', $allform1 ['forms_ids'] );
			}else{
				$forms_ids_arr =array();
			}

			//echo '<pre>'; print_r($forms_ids_arr); echo '</pre>'; //die;
			
			$this->data ['tagsforms'] = array ();
			
			foreach ( $forms_ids_arr as $form_id ) {
				
				$allform = $this->model_form_form->getFormDatas ( $form_id );
				
				
				//echo '<pre>'; print_r($form_info); echo '</pre>'; //die;
				
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				
				// echo '<pre>'; print_r($note_info); echo '</pre>'; //die;
				
				
				if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
					$user_id = $note_info ['user_id'];
					$signature = $allform ['signature'];
					$notes_pin = $allform ['notes_pin'];
					$notes_type = $allform ['notes_type'];
					$notes_description = $note_info ['notes_description'];
					
					if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['date_added'] ) );
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
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
					} else {
						$form_date_added = '';
					}
				}
				
				if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
					$hrurl = $allform ['image_url'];
					$form_name = $allform ['image_name'];
					$fileOpen = $this->url->link('notes/notes/displayFile', '' . '&notes_media_id='.$hrurl . $url, 'SSL');
				} else {
					$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $form_id . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], 'SSL' );
					$form_name = $allform ['incident_number'];
				}
				
				if ($allform ['case_status'] == '0') {
					$client_status = 'Open';
				} else if ($allform ['case_status'] == '1') {
					$client_status = 'Closed';
				} else if ($allform ['case_status'] == '2') {
					$client_status = 'Marked Final';
				}
				
				$this->data ['tagsforms'] [] = array (
						'case_number' => $allform ['case_number'],
						'forms_id' => $form_id,
						'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
						'image_url' => $allform ['image_url'],
						'notes_id' => $allform ['notes_id'],
						'assign_case' => $allform ['assign_case'],
						'forms_design_id' => $allform ['custom_form_type'],
						'form_name' => $form_name,
						'notes_type' => $notes_type,
						'notes_description' => $notes_description,
						'user_id' => $user_id,
						'case_status' => $client_status,
						'signature' => $signature,
						'notes_pin' => $notes_pin,
						'form_date_added' => $form_date_added,
						'note_date' => $note_info ['note_date'],
						'date_added' => date ( 'm-d-Y', strtotime ( $allform ['date_added'] ) ),
						'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
						'archivedforms' => $archivedforms,
						'form_href' => $hrurl, //fileOpen,
						'case_status2' => $allform ['case_status']
				);
				
				
			}

			
			//echo '<pre>'; print_r($this->data['tagsforms']); echo '</pre>'; die;
			
			
			$this->data ['add_case_url'] = $this->url->link ( 'resident/formcase/addcase', $url, true );
			
			$this->data ['form_list_url'] = $this->url->link ( 'notes/notes/allforms&update_notetime=1&hidecaseurl=1', '', $url, true );
			
			$this->data ['attachment_url'] = $this->url->link ( 'notes/notes/attachment', $url, true );
			
			$this->data ['form_open_url'] = $this->url->link ( 'form/form', $url, true );
			
			$this->data ['case_delete_url'] = $this->url->link ( 'resident/formcase/deletecase', $url, true );

			$this->data ['change_status_url'] = $this->url->link ( 'resident/formcase/viewcase', $url, true );
			
			$this->data ['breadcrum_url'] = $this->url->link ( 'resident/formcase/cases', $url, true );
			
			$this->data ['notes_page_url'] = $this->url->link ( 'notes/notes/insert', $url, true );
			
			$this->data ['view_case_url'] = $this->url->link ( 'resident/formcase/viewcase&casetype=1', '' . $url, 'SSL' );
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			// echo '<pre>'; print_r($facility); echo '</pre>'; die;
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&caseformlist=1', '' . $url, 'SSL' ) );
				$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&caseformlist=2', '' . $url, 'SSL' ) );
				$this->data ['case_delete_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&delete_case=1', '' . $url, 'SSL' ) );

				$this->data ['change_status_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&change_status=1', '' . $url, 'SSL' ) );

			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign', '' . $url, 'SSL' ) );
				$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign2', '' . $url, 'SSL' ) );
				$this->data ['case_delete_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/deletecase', $url, true ) );
				$this->data ['change_status_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/change_status', $url, true ) );
			}
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/resident/viewcase.php';
			$this->children = array (
					'common/headerclient',
					'common/footerclient' 
			);
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites resident viewcase ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'viewcase', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}

	public function addcase() {
		try {
			$this->load->model ( 'facilities/online' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'resident/casefile' );
			$this->language->load ( 'notes/notes' );
			$this->document->setTitle ( 'Case' );
			
			if (! $this->customer->isLogged ()) {
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			
			$url = "";
			
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
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
				
				// echo '<pre>'; print_r($this->request->post); echo '</pre>'; die;
				
				$this->load->model ( 'api/temporary' );
				$tdata = array ();
				$tdata ['facilities_id'] = $facilities_id;
				$tdata ['type'] = 'updatecaseformList';
				
				$archive_forms_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
				
				if ($archive_forms_id != null && $archive_forms_id != "") {
					$url2 .= '&archive_forms_id=' . $archive_forms_id;
				}
				
				if ($this->request->post ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->post ['notes_id'];
				}
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				
				if ($this->request->post ['case_number'] != null && $this->request->post ['case_number'] != "") {
					$url2 .= '&case_number=' . $this->request->post ['case_number'];
				}
				
				if ($this->request->post ['status'] != null && $this->request->post ['status'] != "") {
					$url2 .= '&status=' . $this->request->post ['status'];
				}
				
				if (isset ( $this->request->post ['new_module'] )) {
					$this->data ['new_module'] = $this->request->post ['new_module'];
				} else {
					$this->data ['new_module'] = '';
				}
				
				if ($this->request->post ['tags_id'] != null && $this->request->post ['tags_id'] != "") {
					$url2 .= '&tags_id=' . trim($this->request->post ['tags_id']);
				}
				
				$this->session->data ['success2'] = 'Case added successfully!';
				
				$this->redirect ( $this->url->link ( 'resident/formcase/addcase', '' . $url2, 'SSL' ) );
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
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				
				$data = array (
					'case_file_id' => $this->request->get ['case_file_id'],
					'facilities_id' => $this->customer->getId () 
				);
				
				$results = $this->model_resident_casefile->getCaseNumber ( $data );
				
				//echo '<pre>fff'; print_r($results); echo '</pre>'; //die;

				
				$this->data ['case_number'] = $results ['case_number'];
				
			} else {
				
				$case_number_prefix = '';
				if ($client_name != '') {
					foreach ( preg_split ( '#[^a-z]+#i', $client_name, - 1, PREG_SPLIT_NO_EMPTY ) as $word ) {
						$case_number_prefix .= $word [0];
					}
				} else {
					$case_number_prefix = '';
				}
				$this->session->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
				$this->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
			}
			
			if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
				
				$tags_id = $this->request->get ['tags_id'];
				
				$url .= '&tags_id=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
			}else{
				$this->data ['tags_id'] = $results ['tags_ids'];
				
				$tags_id = $results ['tags_ids'];
				
				$url .= '&tags_id=' . $results ['tags_ids'];
			}
			
			$tag_data = array ();
			$tag_data ['tags_id'] = $tags_id;
			$form_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
			
			if (! empty ( $form_info )) {
				$this->data ['client_name'] = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
				
				$client_name = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
			} else {
				$this->data ['client_name'] = '';
				$client_name = '';
			}
			
			
			
			$data = array (
					'sort' => $sort,
					'order' => $order,
					'is_case' => 1,
					'page_name' => 'viewcase',
					'case_file_id' => $case_file_id,
					'tags_id' => $tags_id,
					'add_case' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			//$this->load->model ( 'resident/casefile' );
			
			//$allforms = $this->model_resident_casefile->getUnsignedFormByTagsId ( $data );
			
			
			$allforms = $this->model_form_form->gettagsforms ( $data );

			//echo '<pre>'; print_r($allforms); echo '</pre>'; //die;

			$this->data ['tagsforms'] = array ();
			
			foreach ( $allforms as $allform ) {
				
				$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );

				//echo '<pre>fff'; print_r($note_info); echo '</pre>'; //die;


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
				
				if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
					$hrurl = $allform ['image_url'];
					$form_name = $allform ['image_name'];
				} else {
					$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $allform ['forms_id'] . '&tags_id=' . $allform ['tags_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&forms_id=' . $allform ['forms_id'], 'SSL' );
					$form_name = $allform ['incident_number'];
				}
				
				if ($allform ['case_status'] == '0') {
					
					$client_status = 'Open';
				} else if ($allform ['case_status'] == '1') {
					
					$client_status = 'Closed';
				} else if ($allform ['case_status'] == '2') {
					
					$client_status = 'Marked Final';
				}
				
				$this->data ['tagsforms'] [] = array (
						'forms_id' => $allform ['forms_id'],
						'notes_id' => $allform ['notes_id'],
						'assign_case' => $allform ['assign_case'],
						'forms_design_id' => $allform ['custom_form_type'],
						'form_name' => $form_name,
						'notes_type' => $notes_type,
						'notes_description' => $notes_description,
						'user_id' => $user_id,
						'case_status' => $client_status,
						'signature' => $signature,
						'notes_pin' => $notes_pin,
						'form_date_added' => $form_date_added,
						'note_date' => $note_info ['note_date'],
						'date_added' => date ( 'm-d-Y', strtotime ( $allform ['date_added'] ) ),
						'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
						'archivedforms' => $archivedforms,
						'form_href' => $hrurl 
				);
			}
			
			// echo '<pre>'; print_r($this->data['tagsforms']); echo '</pre>'; //die;
			
			$url2 = "";
			
			$url2 .= '&action=1';
			$url2 .= '&addcase=1';
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($this->request->get ['client_name_id'] != null && $this->request->get ['client_name_id'] != "") {
				$url2 .= '&client_name_id=' . $this->request->get ['client_name_id'];
				$url2 .= '&tags_id=' . $this->request->get ['client_name_id'];
				$url2 .= '&tagsids=' . $this->request->get ['client_name_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}else{
				$url2 .= '&tags_id=' . $this->data ['tags_id'];
			}
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				$url2 .= '&case_number=' . $this->request->get ['case_number'];
			} else {
				$url2 .= '&case_number=' . $this->data ['case_number'];
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$url2 .= '&status=' . $this->request->get ['status'];
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			$this->data ['redirecturl'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/cases', '' . $url2, 'SSL' ) );
			$this->data ['action'] = $this->url->link ( 'resident/formcase/addcase', $url2, true );
			
			$this->data ['form_list_url'] = $this->url->link ( 'notes/notes/allforms&update_notetime=1&hidecaseurl=1', '' . $url2, 'SSL' );
			
			$this->data ['attachment_url'] = $this->url->link ( 'notes/notes/attachment', $url2, true );
			
			$this->data ['form_open_url'] = $this->url->link ( 'form/form', '' . $url2, true );
			
			$this->data ['breadcrum_url'] = $this->url->link ( 'resident/formcase/cases', $url2, true );
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			// echo '<pre>'; print_r($facility); echo '</pre>'; die;
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&caseformlist=1', '' . $url2, 'SSL' ) );
				// $this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&caseformlist=2', '' . $url2, 'SSL' ) );
				// $this->data ['case_delete_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&delete_case=1', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign', '' . $url2, 'SSL' ) );
				// $this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign2', '' . $url2, 'SSL' ) );
				// $this->data ['case_delete_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/deletecase', $url2, true ) );
			}
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/resident/addCase.php';
			$this->children = array ();
			// 'common/headerpopup',
			// 'common/footerclient'
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites resident addcase ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'addcase', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}

	public function caseFormListSign() {
		try {
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			;
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'resident/casefile' );
			$this->load->model ( 'setting/tags' );
			
			if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
				
				$this->load->model ( 'api/temporary' );
				$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_forms_id'] );
				$tempdata = array ();
				$tempdata = unserialize ( $temporary_info ['data'] );
				
				if ($tempdata ['case_number'] != "" && $tempdata ['case_number'] != null) {
					$case_number = $tempdata ['case_number'];
				}
				
				if ($tempdata ['status'] != "" && $tempdata ['status'] != null) {
					$case_status = $tempdata ['status'];
				}
				
				if ($tempdata ['tags_id'] != "" && $tempdata ['tags_id'] != null) {
					
					$tags_id = $tempdata ['tags_id'];
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
				
				foreach ( $tempdata ['new_module'] as $module ) {
					if ($module ['checkin'] == '1') {
						$form_names [] = $module ['form_name'];
						$forms_id [] = $module ['forms_id'];
						// echo '<pre>'; print_r($module); echo '</pre>';
					}
				}
				
				$form_name_list = @implode ( ',', $form_names );
				if (! empty ( $forms_id )) {
					$form_id_list = @implode ( ',', $forms_id );
				}
				
				// echo '<pre>'; print_r($_REQUEST); echo 'ttttt</pre>';
				
				$fdata = array ();
				$fdata ['facilities_id'] = $facilities_id;
				$fdata ['facilitytimezone'] = $facilitytimezone;
				$fdata ['form_name_list'] = $form_name_list;
				$fdata ['case_number'] = $case_number;
				$fdata ['tags_id'] = $tags_id;
				$fdata ['form_names'] = $form_name_list;
				$fdata ['forms_id'] = $form_id_list;
				
				$fdata ['case_status'] = $case_status;
				
				$notes_id = $this->model_form_form->caseFormListSign ( $this->request->post, $fdata );
				
				// $this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
				
				$url2 = "";
				
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
				}
				
				if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
					$url2 .= '&status=' . $this->request->get ['status'];
				}
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				
				if ($this->request->get ['client_name_id'] != null && $this->request->get ['client_name_id'] != "") {
					$url2 .= '&client_name_id=' . $this->request->get ['client_name_id'];
				}
				if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
					$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
				}
				
				if ($this->request->get ['redirection_type'] != null && $this->request->get ['redirection_type'] != "") {
					$url2 .= '&redirection_type=' . $this->request->get ['redirection_type'];
				}
				
				if ($notes_id != null && $notes_id != "") {
					$url2 .= '&notes_id=' . $notes_id;
				}
				
				if ($case_number != null && $case_number != "") {
					$url2 .= '&case_number=' . $case_number;
				}
				
				$url2 .= '&addcase=1';
				
				$this->session->data ['success_add_form1'] = '1';
				
				$this->data ['success_add_form1'] = '1';
				
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/addcase', '' . $url2, 'SSL' ) ) );
				
				// echo 'yyyy'; die;
			}
			
			// echo 'xxxx'; die;
			
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
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$url2 .= '&status=' . $this->request->get ['status'];
			}
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				$url2 .= '&case_number=' . $this->request->get ['case_number'];
			}
			
			if ($this->request->get ['client_name_id'] != null && $this->request->get ['client_name_id'] != "") {
				$url2 .= '&client_name_id=' . $this->request->get ['client_name_id'];
			}
			
			if ($this->request->get ['redirection_type'] != null && $this->request->get ['redirection_type'] != "") {
				$url2 .= '&redirection_type=' . $this->request->get ['redirection_type'];
			}
			
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign', '' . $url2, 'SSL' ) );
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase', '' . $url2, 'SSL' ) );
			
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
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['client_name_id'] );
			
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
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites resident viewcase ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'viewcase', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}

	

	public function change_status() {
		try {
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			;
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'setting/tags' );

			$url2='';

			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			
			if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
				$url2 .= '&case_status=' . $this->request->get ['case_status'];
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$url2 .= '&status=' . $this->request->get ['status'];
			}

			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}


			
			if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
				
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
				
				$fdata = array ();
				$fdata ['facilities_id'] = $facilities_id;
				$fdata ['facilitytimezone'] = $timezone_name;
				$fdata ['case_status'] = $this->request->get ['status'];
				$fdata ['case_file_id'] = $this->request->get ['case_file_id'];
				$fdata ['tags_id'] = $this->request->get ['tags_id'];

				$notes_id = $this->model_form_form->change_case_status ( $this->request->post, $fdata );
				
				$this->session->data ['success_add_form1'] = '1';
				
				$this->data ['success_add_form1'] = '1';
			
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/viewcase', '' . $url2, 'SSL' ) ) );
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
			
			if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
				$url2 .= '&case_status=' . $this->request->get ['case_status'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}

			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
				$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$url2 .= '&status=' . $this->request->get ['status'];
			}
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				$url2 .= '&case_number=' . $this->request->get ['case_number'];
			}
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			
			
			
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
				$data ['forms_id'] = $this->request->get ['forms_id'];
			}
			
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/change_status', '' . $url2, 'SSL' ) );
			
			//echo $$url2; //die;
			
	
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/viewcase', '' . $url2, 'SSL' ) );
			
			
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
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['client_name_id'] );
			
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
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites resident deletecase ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'deletecase', $activity_data2 );
		}
	}

	public function deletecase() {
		try {
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			;
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'setting/tags' );
			
			
			$url2='';
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
				
			if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
				$url2 .= '&case_status=' . $this->request->get ['case_status'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
			}
			
			if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
				
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
				
				$fdata = array ();
				$fdata ['facilities_id'] = $facilities_id;
				$fdata ['facilitytimezone'] = $facilitytimezone;
				$fdata ['forms_id'] = $this->request->get ['forms_id'];
				$fdata ['case_file_id'] = $this->request->get ['case_file_id'];
				$fdata ['tags_id'] = $this->request->get ['tags_id'];
				
				// echo '<pre>bbbb'; print_r($fdata); echo '</pre>'; die;
				
				$notes_id = $this->model_form_form->deletecase ( $this->request->post, $fdata );
				
				$this->session->data ['success_add_form1'] = '1';
				
				$this->data ['success_add_form1'] = '1';
				
				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/viewcase', '' . $url2, 'SSL' ) ) );
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
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$url2 .= '&status=' . $this->request->get ['status'];
			}
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				$url2 .= '&case_number=' . $this->request->get ['case_number'];
			}
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			
			if ($this->request->get ['redirection_type'] != null && $this->request->get ['redirection_type'] != "") {
				$url2 .= '&redirection_type=' . $this->request->get ['redirection_type'];
			}
			
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
				$data ['forms_id'] = $this->request->get ['forms_id'];
			}
			
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/deletecase', '' . $url2, 'SSL' ) );
			
			// echo $$url2; //die;
			
			// if(isset($this->request->get['redirection_type']) && $this->request->get['redirection_type']==1){
			// $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/formcase/case', '' . $url2, 'SSL'));
			
			// }else{
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase', '' . $url2, 'SSL' ) );
			// }
			
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
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['client_name_id'] );
			
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
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites resident deletecase ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'deletecase', $activity_data2 );
		}
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
					if ($new_module ['required'] == "1") {
						$this->error ['comments'] = $this->language->get ( 'error_required' );
					}
				}
			}
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
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

	public function getCaseNumber() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$data = array (
				'case_number' => $this->request->get ['case_number'],
				'facilities_id' => $this->customer->getId (),
				'limit' => CONFIG_LIMIT 
		);

		//echo '<pre>'; print_r($data); echo '</pre>'; die;


		$this->load->model ( 'resident/casefile' );
			
		$results = $this->model_resident_casefile->getCaseNumber ( $data );

		
		//$results = $this->model_form_form->getCaseNumber ( $data );
		
		foreach ( $results as $result ) {
			
			$tagDetail = $this->model_setting_tags->getTag ( $result ['tags_id'] );
			
			if ($result ['case_number'] != "" && $result ['case_number'] != null) {
				
				$json [] = array (
						'case_number' => $result ['case_number'] 
				);
			}
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function caseform2() {
		$url2 = "";
		
		if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
			$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
		}
		
		if ($this->request->get ['client_name_id'] != null && $this->request->get ['client_name_id'] != "") {
			$url2 .= '&client_name_id=' . $this->request->get ['client_name_id'];
		}
		
		if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
			$url2 .= '&case_number=' . $this->request->get ['case_number'];
		} /*
		   * else if ($this->request->get['case_number'] != null && $this->request->get['case_number'] != ""){
		   *
		   * $url2 .= '&case_number=' . $this->request->get['case_number'];
		   *
		   *
		   * }
		   */
		
		if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
			$url2 .= '&status=' . $this->request->get ['case_status'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		// echo $url2; //die;
		
		$this->data ['success2'] = '1';
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		// echo '<pre>'; print_r($facility); echo '</pre>'; die;
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&caseformlist=1', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&caseformlist=2', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign2', '' . $url2, 'SSL' ) );
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/case_success.php';
		$this->children = array (
				'common/headerclient',
				'common/footerclient' 
		);
		$this->response->setOutput ( $this->render () );
		
		// }
	}
} 