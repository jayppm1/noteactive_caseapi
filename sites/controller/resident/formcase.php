<?php
class Controllerresidentformcase extends Controller {
	private $error = array ();
	
	public function cases() {
		$url = "";
		try {
			
			$this->load->model('notes/notes');
			
			if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'date_added';
            }
			
			if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = 'DESC';
            }
			
			$this->data['casetype_data'] = array();
			if($this->request->get ['case_type']!=""){
				$case_type = $this->request->get ['case_type'];
				$customlistvalues = $this->model_notes_notes->getcustomlistvalue($case_type);
				if(!empty($customlistvalues)){
					$casetype_data_arr['customlistvalues_id'] = $customlistvalues['customlistvalues_id'];
					$casetype_data_arr['customlistvalues_name'] = $customlistvalues['customlistvalues_name'];
					$casetype_arr = $casetype_data_arr;
					$this->data['casetype_data'] = $casetype_arr;
				}
			}
			
			$this->data['incident_type_data'] = array();
			if($this->request->get ['incident_type']!=""){
				$incident_type = $this->request->get ['incident_type'];
				$customlistvalues = $this->model_notes_notes->getcustomlistvalue($incident_type);
				if(!empty($customlistvalues)){
					$incident_type_data_arr['customlistvalues_id'] = $customlistvalues['customlistvalues_id'];
					$incident_type_data_arr['customlistvalues_name'] = $customlistvalues['customlistvalues_name'];
					$incident_type_arr = $incident_type_data_arr;
					$this->data['incident_type_data'] = $incident_type_arr;
				}
			}
			
			$this->data['code_data'] = array();
			if($this->request->get ['code']!=""){
				$code = $this->request->get ['code'];
				$customlistvalues = $this->model_notes_notes->getcustomlistvalue($code);
				if(!empty($customlistvalues)){
					$code_data_arr['customlistvalues_id'] = $customlistvalues['customlistvalues_id'];
					$code_data_arr['customlistvalues_name'] = $customlistvalues['customlistvalues_name'];
					$code_arr = $code_data_arr;
					$this->data['code_data'] = $code_arr;
				}
			}
			
			
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
			
			$config_admin_limit = 8;//$this->config->get('config_front_limit');
			
			if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
			
				if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
					$status = $this->request->get ['status'];
				
					$this->data ['status'] = $this->request->get ['status'];
				} else {
					$this->data ['status'] = 0;
				}
			
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
				
				
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}
				
				$data = array (
					'case_type' => $case_type,
					'incident_type' => $incident_type,
					'code' => $code,
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
				
				//echo '<pre>fff'; print_r($data); echo '</pre>'; //die;
				
				// $form_total = $this->model_form_form->getTotalforms2 ( $data );
				
				// $allforms = $this->model_form_form->gettagsforms ( $data );
				
				$this->load->model ( 'resident/casefile' );
				
				$allforms = $this->model_resident_casefile->getcasefiles ( $data );
				
				$total_count = $this->model_resident_casefile->getcasefiles2 ( $data );
				
				//echo '<pre>fff'; print_r($allforms); echo '</pre>'; die;
				
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
					
					if($allform ['tags_ids']!=''){
						$inmate_arr=array();
						$tags_ids_arr = explode(',',$allform ['tags_ids']);
						foreach($tags_ids_arr AS $tag_id){
							$tag_info = $this->model_setting_tags->getTag ( $tag_id );
							$inmate_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
						}
						
						$inmate_name = implode(',',$inmate_arr);
					}else{
						$inmate_name = '';
					}
					
					$this->data ['tagsforms'] [] = array (
							'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
							'forms_id' => $allform ['forms_id'],
							'inmate_name' => $inmate_name,
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
			
			}else{
				
				if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
					$status = $this->request->get ['status'];
					$this->data ['status'] = $this->request->get ['status'];
				} else {
					$this->data ['status'] = 0;
					$status = 0;
				}
				
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}
				
				$data = array (
						'case_type' => $case_type,
						'incident_type' => $incident_type,
						'code' => $code,
						'sort' => $sort,
						'order' => $order,
						'is_case' => '1',
						'status' => $status,
						//'case_number' => 1,
						//'tags_id' => $tags_id,
						'facilities_id' => $this->customer->getId (),
						'add_case' => '1',
						'start' => ($page - 1) * $config_admin_limit,
						'limit' => $config_admin_limit 
				);
				
				//echo '<pre>'; print_r($data); echo '</pre>'; //die;
				
				// $form_total = $this->model_form_form->getTotalforms2 ( $data );
				
				// $allforms = $this->model_form_form->gettagsforms ( $data );
				
				$this->load->model ( 'resident/casefile' );
				$allforms = $this->model_resident_casefile->getCaseNumber ( $data );
				
				$total_count = $this->model_resident_casefile->getCaseNumber2 ( $data );
				
				//echo '<pre>fff'; print_r($allforms); echo '</pre>'; //die;
				$this->data ['is_facility'] = '1';
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
					
					if($allform ['tags_ids']!=''){
						$inmate_arr=array();
						$tags_ids_arr = explode(',',$allform ['tags_ids']);
						foreach($tags_ids_arr AS $tag_id){
							$tag_info = $this->model_setting_tags->getTag ( $tag_id );
							//echo '<pre>'; print_r($tag_info['emp_first_name']); echo '</pre>';
							//echo '<pre>'; print_r($tag_info['emp_last_name']); echo '</pre>';
							$inmate_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
						}
						
						$inmate_name = implode(',',$inmate_arr);
					}else{
						$inmate_name = '';
					}
					
					$this->data ['tagsforms'] [] = array (
							'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
							'forms_id' => $allform ['forms_id'],
							'notes_type' => $notes_type,
							'user_id' => $user_id,
							'tags_id' => $allform ['tags_ids'],
							'inmate_name' => $inmate_name,
							'signature' => $signature,
							'forms_design_id' => $allform ['custom_form_type'],
							'notes_id' => $allform ['notes_id'],
							'case_number' => $allform ['case_number'],
							'case_status' => $client_status,
							'notes_pin' => $notes_pin,
							'form_date_added' => $form_date_added 
					);
				}

				
				
				
				
				
			}
			
			//echo '<pre>'; print_r($this->data ['tagsforms']); echo '</pre>';
				
			//die;
			
			
			$url3 = '';
            
            if ($order == 'ASC') {
                $url3 .= '&order=DESC';
            } else {
                $url3 .= '&order=ASC';
            }
            
            if (isset($this->request->get['page'])) {
                $url3 .= '&page=' . $this->request->get['page'];
            }
			
			
			$this->data['sort'] = $sort;
            $this->data['order'] = $order;
			
			$this->data['sort_case_number'] = $this->url->link('resident/formcase/cases', '&addcase=1&sort=case_number' . $url3, 'SSL');
			
			$this->data['sort_tags_ids'] = $this->url->link('resident/formcase/cases', '&addcase=1&sort=tags_ids' . $url3, 'SSL');
			
			$this->data['sort_status'] = $this->url->link('resident/formcase/cases', '&addcase=1&sort=case_status' . $url3, 'SSL');
			
			$this->data['sort_date_added'] = $this->url->link('resident/formcase/cases', '&addcase=1&sort=date_added' . $url3, 'SSL');

			
			$this->data ['add_case_url'] = $this->url->link ( 'resident/formcase/addcase', $url, 'SSL' );
			
			$this->data ['add_casecovepage_url'] = $this->url->link ( 'resident/formcase/addcasecovepage', $url, true );
			
			$this->data ['case_url'] = $this->url->link ( 'resident/formcase/cases', $url, 'SSL' );
			
			$this->data ['view_case_url'] = $this->url->link ( 'resident/formcase/viewcase', '' . $url, 'SSL' );
			
			$this->data ['case_url2'] = $this->url->link ( 'resident/formcase/cases', '&addcase=1', 'SSL' );
			
			$pagination = new Pagination();
			$pagination->total = $total_count;
			$pagination->page = $page;
			$pagination->limit = $config_admin_limit;
			
			$pagination->text = ''; // $this->language->get('text_pagination');
			$pagination->url = $this->url->link('resident/formcase/cases', '' . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
				
				
			
			
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
			$this->load->model ( 'customer/customer' );
			$this->load->model ( 'facilities/facilities' );
			$this->language->load ( 'notes/notes' );
			if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
				$facilities_id = $this->request->get['facilities_id'];
			} else {
				if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
					$facilities_id = $this->session->data['search_facilities_id'];
				}else{
					$facilities_id = $this->customer->getId();
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
			
			date_default_timezone_set ( $timezone_name );
			
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data['customerinfo'] = array();
			if (! empty ( $customer_info ['setting_data'])) {
				$customers = unserialize($customer_info ['setting_data']);
				$this->data['customerinfo'] = array (
					'inmate_name' => $customers['inmate_name'],
					'case_type_name' => $customers['case_type_name'].'kkkk',
					'incident_type_name' => $customers['incident_type_name'],
					'code_name' => $customers['code_name'],
					'user_of_force_name' => $customers['user_of_force_name'],
					'charges_name' => $customers['charges_name']
				);
			}

			
				
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
			
			if($this->request->get ['tags_id']!=""){
				$tags_id = $this->request->get ['tags_id'];
				$tag_info = $this->model_setting_tags->getTag ( $tags_id );
				$this->data ['inmate_namess'] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
				$this->data ['inmate_idss'] = $tags_id;
			}
			
			
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				
				$data = array (
					'case_number' => $this->request->get ['case_number'],
					'facilities_id' => $this->customer->getId () 
				);
				$case_info = $this->model_resident_casefile->getcasefileByCasenumber ( $data );
				// echo '<pre>'; print_r($case_info['tags_ids']); echo '</pre>'; //die;
				$this->request->get ['case_file_id'] = $case_info ['notes_by_case_file_id'];
				$this->request->get ['tags_ids'] = $case_info ['tags_ids'];
				
			
				$adata = array (
					'tags_ids' => $case_info ['tags_ids'],
					'case_number' => $this->request->get ['case_number'],
				);
				
				$aallattas = ''; //$this->model_setting_tags->gettagsattachmets ( $adata );
				
				$this->data ['attachments'] = array ();
				foreach ( $aallattas as $aallatta ) {
					
					$tag_info = $this->model_setting_tags->getTag ( $aallatta ['tags_id'] );
					$client_name = $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
					if ($aallatta ['notes_file'] != null && $aallatta ['notes_file'] != "") {
						$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $aallatta ['notes_media_id'], 'SSL' );
						$form_name = $aallatta ['image_name'];
					}
					$note_info = $this->model_notes_notes->getNote ( $aallatta ['notes_id'] );
				
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
				
					$this->data ['attachments'] [] = array (
							'notes_media_id' => $aallatta ['notes_media_id'],
							'name' => "Attachment",
							'form_href' => $hrurl,
							'notes_type' => $notes_type,
							'notes_description' => $notes_description,
							'user_id' => $user_id,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'inmate_name'=>$client_name,
							'form_date_added' => $form_date_added,
							'date_added2' => date ( 'D F j, Y', strtotime ( $note_info ['date_added'] ) )
					);
				}
				
				
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
				
				$url .= '&tagsids=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
			}
			if ($this->request->get ['case_number'] != "" && $this->request->get ['case_number'] != null) {
				
				$case_number = $this->request->get ['case_number'];
				
				$url .= '&case_number=' . $this->request->get ['case_number'];
				
				$this->data ['case_number'] = $this->request->get ['case_number'];	
			}
			
			$tag_data = array ();
			$tag_data ['tags_id'] = $tags_id;
			$form_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
			// echo '<pre>fff'; print_r($form_info); echo '</pre>'; //die;
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
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != null) {
				
				$this->data ['status2'] = $this->request->get ['status'];
				$this->data ['status'] = $this->request->get ['status'];
				
				if($this->request->get ['status']==0){
					$cstatus = 'Open';
				}elseif($this->request->get ['status']==1){
					$cstatus = 'Closed';
				}elseif($this->request->get ['status']==2){
					$cstatus = 'Marked Final';
				}else{
					$cstatus = 'None';
				}
				
				$this->data ['status3'] = $cstatus;
				
			} else {
				
				if (isset ( $case_info ['case_status'] ) && $case_info ['case_status'] != '') {
					$this->data ['status'] = $case_info ['case_status'];
					
					if($case_info ['case_status']==0){
						$cstatus = 'Open';
					}elseif($case_info ['case_status']==1){
						$cstatus = 'Closed';
					}elseif($case_info ['case_status']==2){
						$cstatus = 'Marked Final';
					}else{
						$cstatus = 'None';
					}
				
				$this->data ['status3'] = $cstatus;
					
					
				} else {
					$this->data ['status'] = '';
				}	
			}
			
			
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'is_case' => 0,
				'page_name' => 'viewcase',
				// 'status' => $status,
				'case_number' => $case_number,
				'case_file_id' => $case_file_id,
				'tags_id' => $tags_id,
				'add_case' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
			);
			
			//echo '<pre>'; print_r($data); echo '</pre>'; 
			
			$this->load->model ( 'resident/casefile' );
			
			//$allform1 = $this->model_resident_casefile->getcasefileforviewcase ( $data );
			
			$allform1 = $this->model_resident_casefile->getcasefileforviewcase2 ( $data );
			
			/*if ($allform1 ['forms_ids'] != '') {
				$forms_ids_arr = explode ( ',', $allform1 ['forms_ids'] );
				$forms_ids_arr = array_filter($forms_ids_arr);
			} else {
				$forms_ids_arr = array ();
			}*/
			
			
			
			
			
			$this->data ['tagsforms'] = array ();
			
			
			foreach ( $allform1 as $allform ) {
				
				//echo '<pre>'; print_r($allform['tags_ids']); echo '</pre>'; //die;
				
				$this->data ['case_status'] = $allform ['case_status'];
				
				//$allform = $this->model_form_form->getFormDatas ( $form_id );
				
				//echo '<pre>'; print_r($allform); echo '</pre>'; //die;
				
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				
				//echo '<pre>'; print_r($allform['tags_ids']); echo '</pre>'; //die;
				
				$tag_name_arr = array();
				/*if($allform['tags_ids']!=''){
					$tags_ids = explode(',',$allform['tags_ids']);
					foreach($tags_ids AS $tag_id){
						$tag_info = $this->model_setting_tags->getTag ( $tag_id );
						$tag_name_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
					}	
				}*/
				
				
				$atags_ids = $this->model_notes_notes->getNotesTagsmultiple ( $allform ['notes_id'] );
				
				foreach ($atags_ids as $atags_id){
					$tag_info = $this->model_setting_tags->getTag ( $atags_id['tags_id'] );
					$tag_name_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
				}
				
				
				if($note_info['tags_id']!=0){
					
					$tag_info = $this->model_setting_tags->getTag ( $note_info ['tags_id'] );
					$tag_name_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
					
				}
				
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
					$fileOpen = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $hrurl . $url, 'SSL' );
				} else {
					$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $allform['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], 'SSL' );
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
						'forms_id' => $allform['forms_id'],
						'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
						'inmate_name' => implode(',',array_unique($tag_name_arr)),
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
						'form_href' => $hrurl, // fileOpen,
						'case_status2' => $allform ['case_status'] 
				);
				
			}
			
			//echo '<pre>'; print_r($this->data ['tagsforms']); echo '</pre>'; //die;
			
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				
				$data = array (
					'case_file_id' => $this->request->get ['case_file_id'],
					'facilities_id' => $this->customer->getId () 
				);
				
				$casecover_info = $this->model_resident_casefile->getcasefileforviewcase ( $data );
				
				//echo '<pre>'; print_r($casecover_info); echo '</pre>';
				
				$this->data ['is_row']=0;
				if($casecover_info){
					$this->data ['is_row'] = 1;
					$this->data ['case_type'] = '';
					if($casecover_info['case_type']!=''){
						$case_type_arr = explode(',',$casecover_info['case_type']);
						foreach($case_type_arr AS $case_type){
							$res = $this->model_notes_notes->getcustomlistvalue($case_type);
							$case_type_name_arr[] = $res['customlistvalues_name'];
						}
						$this->data ['case_type'] = str_replace(',','<br>',implode(',',$case_type_name_arr));
					}
					
					
					$this->data ['incident_type'] = '';
					if($casecover_info['incident_type']!=''){
						$incident_type_arr = explode(',',$casecover_info['incident_type']);
						foreach($incident_type_arr AS $incident_type){
							$res = $this->model_notes_notes->getcustomlistvalue($incident_type);
							$incident_type_name_arr[] = $res['customlistvalues_name'];
						}
						$this->data ['incident_type'] = str_replace(',','<br>',implode(',',$incident_type_name_arr));
					}
					
					$this->data ['code'] = '';
					if($casecover_info['code']!=''){
						$code_arr = explode(',',$casecover_info['code']);
						foreach($code_arr AS $code){
							$res = $this->model_notes_notes->getcustomlistvalue($code);
							$code_name_arr[] = $res['customlistvalues_name'];
						}
						$this->data ['code'] = str_replace(',','<br>',implode(',',$code_name_arr));
					}
					
					$this->data ['options'] = '';
					$options =array();
					if($casecover_info['user_of_force_name']!=''){
						$options[] = '<span style="font-weight: 100;">Use of Force Name - <span style="font-weight: 600;">'.$casecover_info['user_of_force_name'].'</span>';
					}else{
						$options[] = 'No use of force name';
					}
					
					$this->data ['criminal_charges_filed'] = '';
					if($casecover_info['criminal_charges_filed']!=''){
						$options[] = 'Criminal Charges Filed - <span style="font-weight: 600;">'.$casecover_info['criminal_charges_filed'].'</span>';
					}else{
						$options[] = 'No criminal charges filed';
					}
					
					$this->data ['options'] = str_replace(',','<br>',implode(',',$options));
				}
			}
			
			
			
			
			
			//echo '<pre>casecover_info'; print_r($this->data['tagsforms']); echo '</pre>'; die; 
			
			
			
			$this->data ['add_casecovepage_url'] = $this->url->link ( 'resident/formcase/addcasecovepage', $url, true );
			
			$this->data ['add_case_url'] = $this->url->link ( 'resident/formcase/addcase', $url, true );
			
			$this->data ['form_list_url'] = $this->url->link ( 'notes/notes/allforms&update_notetime=1&hidecaseurl=1', '', $url, true );
			
			$this->data ['attachment_url'] = $this->url->link ( 'notes/notes/attachment', $url, true );
			
			$this->data ['form_open_url'] = $this->url->link ( 'form/form', $url, true );
			
			$this->data ['case_delete_url'] = $this->url->link ( 'resident/formcase/deletecase', $url, true );
			
			$this->data ['change_status_url'] = $this->url->link ( 'resident/formcase/viewcase', $url, true );
			
			$this->data ['breadcrum_url'] = $this->url->link ( 'resident/formcase/cases', $url, true );
			
			$this->data ['notes_page_url'] = $this->url->link ( 'notes/notes/insert', $url, true );
			
			$url2='';
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			} 
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				$url2 .= '&case_number=' . $this->request->get ['case_number'];
			} 
			
			$this->data ['view_case_url'] = $this->url->link ( 'resident/formcase/viewcase', '' . $url2, 'SSL' );
			
			$this->data ['breadcrum_url2'] = $this->url->link ( 'resident/formcase/cases&addcase=1', '', true );
			
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
	
	public function getcustomlistvalues(){
	
		$json = array ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
	
		$this->load->model ( 'customer/customer' );
		$this->load->model('notes/notes');
        $this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/tags' );
		
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
                $facilities_id = $this->session->data['search_facilities_id'];
            }else{
                $facilities_id = $this->customer->getId();
            } 
            //$facilities_id = $this->customer->getId();
        }
	
	
	
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
        
       
        $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
        $this->data['customers'] = array();
        if (! empty ( $customer_info ['setting_data'])) {
            $customers = unserialize($customer_info ['setting_data']);
            $this->data['customerinfo'] = $customers;
			
			
			//echo '<pre>'; print_r($customers); echo '</pre>'; //die;
			
        
			if($this->request->get['list_type'] !="" && $this->request->get['list_type'] =="case_type" && !empty($customers['case_type_values'])){
				foreach($customers['case_type_values'] as $customlist_id){
					$d2 = array();
					$d2['customlist_id'] = $customlist_id;
					
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
					foreach($customlistvalues AS $row){
						$json [] = array(
							'customlistvalues_id' => $row['customlistvalues_id'],
							'customlistvalues_name'  => $row['customlistvalues_name']
						);
					}
				}
		
			}
			
			
			if($this->request->get['list_type'] !="" && $this->request->get['list_type'] =="incident_type" && !empty($customers['incident_type_values'])){
				foreach($customers['incident_type_values'] as $customlist_id){
					$d2 = array();
					$d2['customlist_id'] = $customlist_id;
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
					foreach($customlistvalues AS $row){
						$json [] = array(
							'customlistvalues_id' => $row['customlistvalues_id'],
							'customlistvalues_name'  => $row['customlistvalues_name']
						);
					}
				}
		
			}
			
			if($this->request->get['list_type'] !="" && $this->request->get['list_type'] =="code" && !empty($customers['code_values'])){
				foreach($customers['code_values'] as $customlist_id){
					$d2 = array();
					$d2['customlist_id'] = $customlist_id;
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
					foreach($customlistvalues AS $row){
						$json [] = array(
							'customlistvalues_id' => $row['customlistvalues_id'],
							'customlistvalues_name'  => $row['customlistvalues_name']
						);
					}
				}
			}
			
			
			
			$this->response->setOutput ( json_encode ( $json ) );
		}
	
	
	
	
	
	}
	
	public function searchTags() {
		$json = array ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		// if($this->request->get['emp_tag_id'] != null &&
		// $this->request->get['emp_tag_id'] != "") {
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		
		if (isset ( $this->request->get ['emp_tag_id'] )) {
			$emp_tag_id = $this->request->get ['emp_tag_id'];
		} else {
			$emp_tag_id = '';
		}

		if (isset ( $this->request->get ['q'] )) {
			$q = $this->request->get ['q'];
		} else {
			$q = '';
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		$filter_name = explode ( ':', $emp_tag_id );
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
				} else {
					$facilities_id = $this->customer->getId ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
			}
			// $facilities_id = $this->customer->getId();
		}
		
		$data = array (
			'q' => $q,
			'emp_tag_id_all' => trim ( $filter_name [0] ),
			'facilities_id' => $facilities_id,
			'status' => 1,
			'discharge' => 1,
			'all_record' => 1,
			'is_master' => 1,
			'sort' => 'emp_last_name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $limit
		);
		
		$tags = $this->model_setting_tags->getTags ( $data );
		
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'notes/clientstatus' );
		$this->load->model ( 'form/form' );
		$html='';
		foreach ( $tags as $result ) {
			
			if ($result ['date_of_screening'] != "0000-00-00") {
				$date_of_screening = date ( 'm-d-Y', strtotime ( $result ['date_of_screening'] ) );
			} else {
				$date_of_screening = date ( 'm-d-Y' );
			}
			if ($result ['dob'] != "0000-00-00") {
				$dob = date ( 'm-d-Y', strtotime ( $result ['dob'] ) );
			} else {
				$dob = '';
			}
			
			if ($result ['dob'] != "0000-00-00") {
				$dobm = date ( 'm', strtotime ( $result ['dob'] ) );
			} else {
				$dobm = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$dobd = date ( 'd', strtotime ( $result ['dob'] ) );
			} else {
				$dobd = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$doby = date ( 'Y', strtotime ( $result ['dob'] ) );
			} else {
				$doby = '';
			}
			
			/*
			 * if ($result['gender'] == '1') {
			 * $gender = '33';
			 * }
			 * if ($result['gender'] == '2') {
			 * $gender = '34';
			 * }
			 */
			
			$get_img = $this->model_setting_tags->getImage ( $result ['tags_id'] );
			
			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $get_img ['enroll_image'];
			}
			
			if ($result ['ssn']) {
				$ssn = $result ['ssn'] . ' ';
			} else {
				$ssn = '';
			}
			if ($result ['emp_extid']) {
				$emp_extid = $result ['emp_extid'] . ' ';
			} else {
				$emp_extid = '';
			}
			
			$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $result ['tags_id'] );
			
			if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
				$status = $tagstatusinfo ['status'];
				
				$classification_value = $this->model_resident_resident->getClassificationValue ( $tagstatusinfo ['status'] );
				$classification_name = $classification_value ['classification_name'];
			} else {
				$classification_name = '';
			}
			
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $result ['role_call'] );
			if ($clientstatus_info ['name'] != null && $clientstatus_info ['name'] != "") {
				$role_callname = $clientstatus_info ['name'];
				$color_code = $clientstatus_info ['color_code'];
				$role_type = $clientstatus_info ['type'];
			}
			if ($result ['room'] != null && $result ['room'] != "") {
				$rresults = $this->model_setting_locations->getlocation ( $result ['room'] );
				$location_name = $rresults ['location_name'];
			} else {
				$location_name = '';
			}
			
			if ($result ['date_added'] != "0000-00-00") {
				$date_added = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
			}
			
			$datsa = array();
			$datsa['forms_design_id'] = $this->request->get ['forms_design_id'];
			$datsa['facilities_id'] = $result ['facilities_id'];
			$datsa['tags_id'] = $result ['tags_id'];
			//$cseinfo = $this->model_form_form->getFormscase ( $datsa );
			
			
			$json [] = array (
					'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
					//'fullname' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
					'tags_id' => $result ['tags_id'],
					//'case_number' => $cseinfo,
					'date_added' => $date_added,
					'classification_name' => $classification_name,
					'role_call' => $role_callname,
					'location_name' => $location_name,
					'emp_tag_id2' => $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'],
					'emp_tag_id' => $result ['emp_tag_id'],
					'emp_first_name' => $result ['emp_first_name'],
					'emp_middle_name' => $result ['emp_middle_name'],
					'emp_last_name' => $result ['emp_last_name'],
					'location_address' => $result ['location_address'],
					'discharge' => $result ['discharge'],
					'ccn' => $result ['ccn'],
					'age' => $result ['age'],
					'race' => $result ['race'],
					'bed_number' => $result ['bed_number'],
					'dob' => $dob,
					'month' => $dobm,
					'date' => $dobd,
					'year' => $doby,
					'medication' => $result ['medication'],
					// 'gender'=> $result['gender'],
					'gender' => $result ['customlistvalues_id'],
					'person_screening' => $result ['person_screening'],
					'date_of_screening' => $date_of_screening,
					'ssn' => $result ['ssn'],
					'state' => $result ['state'],
					'city' => $result ['city'],
					'zipcode' => $result ['zipcode'],
					'room' => $result ['room'],
					'restriction_notes' => $result ['restriction_notes'],
					'prescription' => $result ['prescription'],
					'constant_sight' => $result ['constant_sight'],
					'alert_info' => $result ['alert_info'],
					'med_mental_health' => $result ['med_mental_health'],
					'tagstatus' => $result ['tagstatus'],
					'emp_extid' => $result ['emp_extid'],
					'stickynote' => $result ['stickynote'],
					'referred_facility' => $result ['referred_facility'],
					'emergency_contact' => $result ['emergency_contact'],
					'upload_file' => $upload_file_thumb_1,
					'image_url1' => $upload_file_thumb_1,
					'screening_update_url' => $action211 
			);
			
			$json2 [] = array (
				'tags_id' => $result ['tags_id'],
				'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name']
			);
			
			
			
			//$html.='<option value="'.$result ['tags_id'].'">'.$result ['emp_last_name'] . ' ' . $result ['emp_first_name'].'</option>';
			
			
			
			
		}
		// }
		
		$this->response->setOutput ( json_encode ( $json2 ) );
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
				
				if ($tempdata ['tagsids'] != "" && $tempdata ['tagsids'] != null) {
					
					$tags_id = $tempdata ['tagsids'];
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
				$fdata ['facilitytimezone'] = $timezone_name;
				$fdata ['form_name_list'] = $form_name_list;
				$fdata ['case_number'] = $case_number;
				$fdata ['tagsids'] = $tags_id;
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
				
				if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
					$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
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
			
			$url2 = '';
			
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
			
			// echo $$url2; //die;
			
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
			
			$url2 = '';
			
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
			
			if ($this->request->get ['notes_media_id'] != null && $this->request->get ['notes_media_id'] != "") {
				$url2 .= '&notes_media_id=' . $this->request->get ['notes_media_id'];
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
				$fdata ['notes_media_id'] = $this->request->get ['notes_media_id'];
				
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
			if ($this->request->get ['notes_media_id'] != null && $this->request->get ['notes_media_id'] != "") {
				$url2 .= '&notes_media_id=' . $this->request->get ['notes_media_id'];
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
			'tags_id' => $this->request->get ['tags_id'],
			'limit' => CONFIG_LIMIT 
		);
		
		// echo '<pre>'; print_r($data); echo '</pre>'; die;
		
		$this->load->model ( 'resident/casefile' );
		
		$results = $this->model_resident_casefile->getCaseNumber ( $data );
		
		// $results = $this->model_form_form->getCaseNumber ( $data );
		
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

	public function addclient (){
       
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            
            $this->load->model('setting/tags');
			
			$emp_extid = preg_replace('/\s+/', '', $this->request->post['emp_extid']);
			$ssn = preg_replace('/\s+/', '', $this->request->post['ssn']);
			
			$month_1 = $this->request->post['month_1'];
			$day_1 = $this->request->post['day_1'];
			$year_1 = $this->request->post['year_1'];
			
			$dob111 = $month_1 . '-' . $day_1 . '-' . $year_1;
			$date = str_replace('-', '/', $dob111);
			$res = explode("/", $date);
			$createdatess1 = $res[2] . "-" . $res[0] . "-" . $res[1];
			$dob = date('Y-m-d', strtotime($createdatess1));
			
			$existclient = array();
			$existclient['emp_extid'] = $emp_extid;
			$existclient['ssn'] = $ssn;
			$existclient['dob'] = $dob;
			$existclient['emp_first_name'] = $this->request->post['emp_first_name'];
			$existclient['emp_last_name'] = $this->request->post['emp_last_name'];
			
			$tag_exist_info = $this->model_setting_tags->getTagsbyAllNamedischage($existclient);
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
				} else {
					if($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != ""){
						$facilities_id = $this->request->get['facilities_id'];
					}else{
						$facilities_id = $this->customer->getId ();
					}
				}
			} else {
				if($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != ""){
					$facilities_id = $this->request->get['facilities_id'];
				}else{
					$facilities_id = $this->customer->getId ();
				}
				
			}
			
            if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
                
                $this->model_setting_tags->updatexittag($this->request->post, $facilities_id);
                
                $this->model_setting_tags->editTags($this->request->post['emp_tag_id'],$this->request->post, $facilities_id);
                
                $tags_id = $this->request->post['emp_tag_id'];
            } else {
				if($tag_exist_info['tags_id'] != null && $tag_exist_info['tags_id'] != ""){
					$this->model_setting_tags->updatexittag($this->request->post, $facilities_id);
                
					$this->model_setting_tags->editTags($tag_exist_info['tags_id'], $this->request->post, $facilities_id);
					
					$tags_id = $tag_exist_info['tags_id'];
				}else{
					$tags_id = $this->model_setting_tags->addTags($this->request->post, $facilities_id);
				}
				
                
            }
            
            $url2 = "";
            $url2 .= '&tags_id=' . $tags_id;
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
            }
			
			if ($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->post['facilities_id'];
				
				$facilities_id = $this->request->post['facilities_id'];
            }

            if($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != ""){
                $url2 .= '&facilityids=' . $this->request->get ['facilityids'];
            }
            
            if($this->request->get['locationids'] != null && $this->request->get['locationids'] != ""){
                $url2 .= '&locationids=' . $this->request->get ['locationids'];
            }

            if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
            $url2 .= '&userids=' . $this->request->get['tagsids'];
        }
            
            if($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
                $url2 .= '&tagsids=' . $this->request->get ['tagsids'];
            }
			
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
                $url2 .= '&page=' . $this->request->get['page'];
            }

            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&client=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclientsign', $url2, true));
            }
            
            $this->data['clientcreated'] = '1';
        }
        
        $this->getForm();
    }

	public function coverform(){
		
		$this->load->model('facilities/online');
        $this->load->model('resident/resident'); 
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
                $facilities_id = $this->session->data['search_facilities_id'];
            }else{
                $facilities_id = $this->customer->getId();
            } 
            //$facilities_id = $this->customer->getId();
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
		
		
		//echo '<pre>'; print_r($customers); echo '</pre>'; die;
		
		
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'notes/notes' );
		
		// incident type customlist_id = 23
		
		$customlist_id = 23;
		$data = array();
		$data['customlistvalueids'] = $customlist_id;		
		$customlists = $this->model_notes_notes->getcustomlistvalues ( $customlist_id );		
		
		$url2 = "";
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
		
		
		$this->data ['tag_name'] = $tag_info['emp_first_name']." ".$tag_info['emp_last_name'];
		
		}
		
		if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
			$url2 .= '&case_number=' . $this->request->get ['case_number'];
		}	
		
		
    	$this->data ['form_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );	
	
        $this->template = $this->config->get ( 'config_template' ) . '/template/resident/coverform.php';
		$this->children = array (
				'common/headerpopup'
				
		);
		$this->response->setOutput ( $this->render () );	
		
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
		
			//echo '<pre>'; var_dump($this->request->get); echo '</pre>';
			
			if (! $this->customer->isLogged ()) {
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			
			$this->data ['minidashboard'] = 0;
			if (isset ( $this->request->get ['minidashboard'] )) {
				$this->data ['minidashboard'] = 1;
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
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$this->data['customerinfo'] = array();
			if (! empty ( $customer_info ['setting_data'])) {
				$customers = unserialize($customer_info ['setting_data']);
				$this->data['customerinfo'] = array (
					'inmate_named' => $customers['inmate_name'],
					'case_type_name' => $customers['case_type_name'],
					'incident_type_name' => $customers['incident_type_name'],
					'code_name' => $customers['code_name'],
					'user_of_force_name' => $customers['user_of_force_name'],
					'charges_name' => $customers['charges_name']
				);
			}	
			
			//echo '<pre>'; print_r($this->request->get); echo '</pre>'; //die;
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
				
				//echo '<pre>'; print_r($this->request->post); echo '</pre>'; die;
				
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
				
				if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
					$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
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
					$url2 .= '&tags_id=' . trim ( $this->request->post ['tags_id'] );
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
			
			
			if ($this->request->get ['tagsids'] != "" && $this->request->get ['tagsids'] != null) {
				
				$tags_ids = $this->request->get ['tagsids'];
				
				$url .= '&tagsids=' . $this->request->get ['tagsids'];
				
				$this->data ['tagsids'] = $this->request->get ['tagsids'];
			}
			
			
			if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
				
				$tags_id = $this->request->get ['tags_id'];
				
				$url .= '&tags_id=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
			}
			
			
			$form_info = $this->model_setting_tags->getTag ( $tags_id );
			
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
				
				$results = $this->model_resident_casefile->getCaseNumber ( $data );
				
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
				//$this->session->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
				//$this->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
			}
			
			/**
			 * ************************************
			 */
			
			/*
			 * if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
			 *
			 * $data = array (
			 * 'case_file_id' => $this->request->get ['case_file_id'],
			 * 'facilities_id' => $this->customer->getId ()
			 * );
			 *
			 * $results = $this->model_resident_casefile->getCaseNumber ( $data );
			 *
			 * //echo '<pre>fff'; print_r($results); echo '</pre>'; //die;
			 *
			 *
			 * $this->data ['case_number'] = $results ['case_number'];
			 *
			 * } else {
			 *
			 * $case_number_prefix = '';
			 * if ($client_name != '') {
			 * foreach ( preg_split ( '#[^a-z]+#i', $client_name, - 1, PREG_SPLIT_NO_EMPTY ) as $word ) {
			 * $case_number_prefix .= $word [0];
			 * }
			 * } else {
			 * $case_number_prefix = '';
			 * }
			 * $this->session->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
			 * $this->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
			 * }
			 *
			 * if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
			 *
			 * $tags_id = $this->request->get ['tags_id'];
			 *
			 * $url .= '&tags_id=' . $this->request->get ['tags_id'];
			 *
			 * $this->data ['tags_id'] = $this->request->get ['tags_id'];
			 * }else{
			 * $this->data ['tags_id'] = $results ['tags_ids'];
			 *
			 * $tags_id = $results ['tags_ids'];
			 *
			 * $url .= '&tags_id=' . $results ['tags_ids'];
			 * }
			 *
			 * $tag_data = array ();
			 * $tag_data ['tags_id'] = $tags_id;
			 * $form_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
			 *
			 * if (! empty ( $form_info )) {
			 * $this->data ['client_name'] = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
			 *
			 * $client_name = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
			 * } else {
			 * $this->data ['client_name'] = '';
			 * $client_name = '';
			 * }
			 *
			 */
			 
			
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'is_case' => 1,
				'page_name' => 'viewcase',
				'page_name2' => 'addcase',
				'case_file_id' => $case_file_id,
				//'tagsids' => $tags_id,
				'add_case' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
			);
			
			
			
			
			$data2 = array();
			if($tags_id!=""){
				$data2 = array ('tags_id' => $tags_id);
			}else{
				$data2 = array ('tagsids' => $tags_ids);
			}
			
			$data = array_merge($data,$data2);
			
			
			
			
			$allforms = $this->model_form_form->gettagsformsforcase ( $data );
			
			//echo '<pre>'; print_r($allforms); echo '</pre>'; die;
			
			$this->data ['tagsforms'] = array ();
			
			foreach ( $allforms as $allform ) {
				
				//echo '<pre>'; print_r($allform); echo '</pre>'; //die;
				
				//if($allform['tags_id']!=0){
					
					//echo '<pre>'; print_r($allform); echo '</pre>';
					$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
					//echo '<pre>'; print_r($note_info); echo '</pre>'; //die;
					
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
					
					$tag_namex = array();
					$notetg_info = $this->model_notes_notes->getNotesTags2 ( $allform ['notes_id'] );
					
					//echo '<pre>'; print_r($notetg_info); echo '</pre>';
					
					foreach($notetg_info AS $tagrow){
						$tag_info = $this->model_setting_tags->getTagbyEMPID ( $tagrow ['emp_tag_id'] );
						
						//echo '<pre>'; print_r($tag_info); echo '</pre>';
						
						$tag_namex[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
					}
					
					$this->data ['tagsforms'] [] = array (
						'forms_id' => $allform ['forms_id'],
						'notes_id' => $allform ['notes_id'],
						'assign_case' => $allform ['assign_case'],
						'forms_design_id' => $allform ['custom_form_type'],
						'form_name' => $form_name,
						'inmate_name' => implode(',',$tag_namex),
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
				
				//}
			}
			
			
			//echo '<pre>'; print_r($this->data ['tagsforms']); echo '</pre>'; //die;
			
			
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				
				$data = array (
					'case_file_id' => $this->request->get ['case_file_id'],
					'facilities_id' => $this->customer->getId () 
				);
				
				$casecover_info = $this->model_resident_casefile->getcasefileforviewcase ( $data );
				
				if($casecover_info!=""){
					
					$tag_name_arr = array();
					$this->data ['tag_name'] ='';
					if($casecover_info['tags_ids']!=''){
						$tags_ids = explode(',',$casecover_info['tags_ids']);
						foreach($tags_ids AS $tag_id){
							$tag_info = $this->model_setting_tags->getTag ( $tag_id );
							$tag_name_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
						}	
						$this->data ['tag_name'] = str_replace(',','<br>',implode(',',$tag_name_arr));
					}
					
					$this->data ['case_type'] = '';
					if($casecover_info['case_type']!=''){
						$case_type_arr = explode(',',$casecover_info['case_type']);
						foreach($case_type_arr AS $case_type){
							$res = $this->model_notes_notes->getcustomlistvalue($case_type);
							$case_type_name_arr[] = $res['customlistvalues_name'];
						}
						$this->data ['case_type'] = str_replace(',','<br>',implode(',',$case_type_name_arr));
					}
					
					$this->data ['incident_type'] = '';
					if($casecover_info['incident_type']!=''){
						$incident_type_arr = explode(',',$casecover_info['incident_type']);
						foreach($incident_type_arr AS $incident_type){
							$res = $this->model_notes_notes->getcustomlistvalue($incident_type);
							$incident_type_name_arr[] = $res['customlistvalues_name'];
						}
						$this->data ['incident_type'] = str_replace(',','<br>',implode(',',$incident_type_name_arr));
					}
					
					$this->data ['code'] = '';
					if($casecover_info['code']!=''){
						$code_arr = explode(',',$casecover_info['code']);
						foreach($code_arr AS $code){
							$res = $this->model_notes_notes->getcustomlistvalue($code);
							$code_name_arr[] = $res['customlistvalues_name'];
						}
						$this->data ['code'] = str_replace(',','<br>',implode(',',$code_name_arr));
					}
					
					$this->data ['options'] = '';
					$options =array();
					if($casecover_info['user_of_force_name']!=''){
						$options[] = '<span style="font-weight: 100;">Use of Force Name - <span style="font-weight: 600;">'.$casecover_info['user_of_force_name'].'</span></span>';
					}else{
						$options[] = 'No use of force name';
					}
					
					$this->data ['criminal_charges_filed'] = '';
					if($casecover_info['criminal_charges_filed']!=''){
						$options[] = 'Criminal Charges Filed - <span style="font-weight: 600;">'.$casecover_info['criminal_charges_filed'].'</span>';
					}else{
						$options[] = 'No criminal charges filed';
					}
					
					$this->data ['options'] = str_replace(',','<br>',implode(',',$options));
				}
			}
			
			// echo '<pre>'; print_r($this->data['tagsforms']); echo '</pre>'; //die;
			
			$url4 = '';
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url4 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url4 .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
				$url4 .= '&case_number=' . $this->request->get ['case_number'];
			}
			
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->data ['view_case_url2'] = $this->url->link ( 'resident/formcase/addcase', $url4, true );
			
			
			
			
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
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
			} else {
				$url2 .= '&tagsids=' . $this->data ['tagsids'];
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
	
	public function addcase2() {
		try {
			
			$this->load->model('facilities/online');
			$this->load->model('resident/resident'); 
			$datafa = array();
			$datafa['username'] = $this->session->data['webuser_id'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->request->get['facilities_id'];
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);
			$this->load->model ( 'customer/customer' );
			$this->load->model('notes/notes');
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
		
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
                $facilities_id = $this->session->data['search_facilities_id'];
            }else{
                $facilities_id = $this->customer->getId();
            } 
            //$facilities_id = $this->customer->getId();
        }
        
		
        $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
        $unique_id = $facility ['customer_key'];
        
       
        $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
        $this->data['customers'] = array();
		
		$this->data ['action'] = $this->url->link ( 'resident/formcase/addcase', '', true );
		
		
        if (! empty ( $customer_info ['setting_data'])) {
            $customers = unserialize($customer_info ['setting_data']);
            $this->data['customerinfo'] = $customers;
			if(!empty($customers['charges_value'])){
				$charges_value = explode(';',$customers['charges_value']);
				$this->data['charges_value'] = $charges_value;
			}
			
			if(!empty($customers['user_of_force_value'])){
				$user_of_force_value = explode(';',$customers['user_of_force_value']);
				$this->data['user_of_force_value'] = $user_of_force_value;
			}
		}
		
		//echo '<pre>'; print_r($this->data['charges_value']); echo '</pre>';
		
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
				
			echo '<pre>'; print_r($this->request->post); echo '</pre>'; die;
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['type'] = 'updatecasecoverpage';
			
			$casecoverdata_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			
			if ($casecoverdata_id != null && $casecoverdata_id != "") {
				$url2 .= '&casecoverdata_id=' . $casecoverdata_id;
			}
			
			
			
			//echo $this->url->link ( 'resident/formcase/addcase', '' . $url2, 'SSL' ); die;
			
			
			$this->session->data ['success2'] = 'Cover page added successfully!';
			
			$this->data ['success2'] = $this->session->data ['success2']; 
			
			
			
			//die;
			
			//$this->redirect ( $this->url->link ( 'resident/formcase/addcase', '' . $url2, 'SSL' ) );
			
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			//echo '<pre>'; print_r($facility); echo '</pre>'; die;
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&casecoverpage=1', '' . $url2, 'SSL' ) );
				
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/cases&addcase=1', '', 'SSL' ) );
			}
		
			
			//echo 'DDDD'.$this->data ['redirect_url'];
			
			
			
			
			
			
			
			
			
		}
		
		
		
		
		
		
		
		//echo '<pre>'; print_r($casetypecustomlistvalues); echo '</pre>'; //die;
		
		
			
			
			/*
			
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
			
			$this->data ['minidashboard'] = 0;
			if (isset ( $this->request->get ['minidashboard'] )) {
				$this->data ['minidashboard'] = 1;
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
					$url2 .= '&tags_id=' . trim ( $this->request->post ['tags_id'] );
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
				
				$results = $this->model_resident_casefile->getCaseNumber ( $data );
				
				// echo '<pre>fff'; print_r($results); echo '</pre>'; //die;
				
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
			
			// $this->load->model ( 'resident/casefile' );
			
			// $allforms = $this->model_resident_casefile->getUnsignedFormByTagsId ( $data );
			
			$allforms = $this->model_form_form->gettagsforms ( $data );
			
			// echo '<pre>'; print_r($allforms); echo '</pre>'; //die;
			
			$this->data ['tagsforms'] = array ();
			
			foreach ( $allforms as $allform ) {
				
				$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				
				// echo '<pre>fff'; print_r($note_info); echo '</pre>'; //die;
				
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
			} else {
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
			
			*/
			
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
	
	public function addcasecovepage() {
		try {
			
			$this->load->model ( 'resident/casefile' );
			$lastcasenumber = $this->model_resident_casefile->get_last_casenumber();
			$this->load->model('facilities/online');
			$this->load->model('resident/resident'); 
			$datafa = array();
			$datafa['username'] = $this->session->data['webuser_id'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->request->get['facilities_id'];
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);
			$this->load->model ( 'customer/customer' );
			$this->load->model('notes/notes');
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
		
			if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
				$facilities_id = $this->request->get['facilities_id'];
			} else {
				if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
					$facilities_id = $this->session->data['search_facilities_id'];
				}else{
					$facilities_id = $this->customer->getId();
				}
			}
			
		
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$this->data['customers'] = array();
			
			$this->data ['action'] = $this->url->link ( 'resident/formcase/addcasecovepage', '', true );
			
			//echo '<pre>'; print_r($this->request->get); echo '</pre>'; //die;	
			
			if (! empty ( $customer_info ['setting_data'])) {
				$customers = unserialize($customer_info ['setting_data']);
				$this->data['customerinfo'] = $customers;
				if(!empty($customers['charges_value'])){
					$charges_value = explode(';',$customers['charges_value']);
					$this->data['charges_value'] = $charges_value;
				}
				
				if(!empty($customers['user_of_force_value'])){
					$user_of_force_value = explode(';',$customers['user_of_force_value']);
					$this->data['user_of_force_value'] = $user_of_force_value;
				}
			}
			
			
			if($this->request->get ['case_file_id']){
				$data = array(
					'case_file_id' =>	$this->request->get ['case_file_id'],
					'facilities_id' =>	$facilities_id
				);
				
				$this->load->model('resident/casefile'); 
				$casecover_info = $this->model_resident_casefile->getcasefileforviewcase ( $data );
				//echo '<pre>'; print_r($casecover_info); echo '</pre>'; //die;	
				
				if($this->request->get['actionarea']==1){
					$this->data['is_update'] = 1;
				}
				
				$this->data['case_file_id'] = $this->request->get ['case_file_id'];
				$this->data['case_number'] = $this->request->get ['case_number'];
				$this->data['case_status'] = $casecover_info ['case_status'];;
				
				
				$this->data['minidashboard'] = '';
				if($this->request->get['minidashboard']!=''){
					$this->data['minidashboard'] = $this->request->get['minidashboard'];
				}
				
				$this->data['tags_id'] = '';
				if($this->request->get['tags_id']!=''){
					$this->data['tags_id2'] = $this->request->get['tags_id'];
				}
				
				
				if($this->request->get['form_dashboard']!=""){
					$this->data['disable_delete'] = $this->request->get['form_dashboard'];
				}
				
				$this->data['client_data'] = array();
				
				if($this->request->get['tags_id']!="" && $this->request->get['actionarea']==2){
					$tags = $this->model_setting_tags->getTag ( $this->request->get['tags_id'] );	
					$inmate_data_arr = array();
					if(!empty($tags)){
						$inmate_data_arr['tags_id'] = $tags['tags_id'];
						$inmate_data_arr['inmate_name'] = $tags['emp_last_name'].' '.$tags['emp_first_name'];
						$inmate_arr[] = $inmate_data_arr;
					}
					$this->data['client_data'] = $inmate_arr;
					
				}else{
					if($casecover_info['tags_ids']!='' && $this->request->get['actionarea']==1){
						$client_name_id_arr = explode(',',$casecover_info['tags_ids']);
						$inmate_arr = array();
						foreach($client_name_id_arr AS $row){
							$tags = $this->model_setting_tags->getTag ( $row );	
							$inmate_data_arr = array();
							if(!empty($tags)){
								$inmate_data_arr['tags_id'] = $tags['tags_id'];
								$inmate_data_arr['inmate_name'] = $tags['emp_last_name'].' '.$tags['emp_first_name'];
								$inmate_arr[] = $inmate_data_arr;
							}
						}
						
						$this->data['client_data'] = $inmate_arr;
					}
				}
				
				//var_dump($this->data['client_data']);
				
				$this->data['casetype_data'] = array();
				if($casecover_info['case_type']!='' && $this->request->get['actionarea']==1){
					$case_type_id_arr = explode(',',$casecover_info['case_type']);
					$casetype_arr = array();
					foreach($case_type_id_arr AS $row){
						$customlistvalues = $this->model_notes_notes->getcustomlistvalue($row);
						$casetype_data_arr = array();
						if(!empty($customlistvalues)){
							$casetype_data_arr['customlistvalues_id'] = $customlistvalues['customlistvalues_id'];
							$casetype_data_arr['customlistvalues_name'] = $customlistvalues['customlistvalues_name'];
							$casetype_arr[] = $casetype_data_arr;
						}
					}
					$this->data['casetype_data'] = $casetype_arr;
				}
				
				
				
				$this->data['incidenttype_data'] = array();
				if($casecover_info['incident_type']!='' && $this->request->get['actionarea']==1){
					$incident_type_id_arr = explode(',',$casecover_info['incident_type']);
					$incidenttype_arr = array();
					foreach($incident_type_id_arr AS $row){
						$customlistvalues = $this->model_notes_notes->getcustomlistvalue($row);
						$casetype_data_arr = array();
						if(!empty($customlistvalues)){
							$casetype_data_arr['customlistvalues_id'] = $customlistvalues['customlistvalues_id'];
							$casetype_data_arr['customlistvalues_name'] = $customlistvalues['customlistvalues_name'];
							$incidenttype_arr[] = $casetype_data_arr;
						}
					}
					$this->data['incidenttype_data'] = $incidenttype_arr;
				}
				
				
				$this->data['code_data'] = array();
				if($casecover_info['code']!='' && $this->request->get['actionarea']==1){
					$code_id_arr = explode(',',$casecover_info['code']);
					//var_dump($code_id_arr);
					
					$code_arr = array();
					foreach($code_id_arr AS $row){
						$customlistvalues = $this->model_notes_notes->getcustomlistvalue($row);
						$code_data_arr = array();
						if(!empty($customlistvalues)){
							$code_data_arr['customlistvalues_id'] = $customlistvalues['customlistvalues_id'];
							$code_data_arr['customlistvalues_name'] = $customlistvalues['customlistvalues_name'];
							$code_arr[] = $code_data_arr;
							
						}
						
						//var_dump($code_arr);	
					}
					$this->data['code_data'] = $code_arr;
				}
			
				
				$this->data['user_of_force_name'] = '';
				if($casecover_info['user_of_force_name']!='' && $this->request->get['actionarea']==1){
					$this->data['user_of_force_name'] = $casecover_info['user_of_force_name'];
				}
				
				$this->data['criminal_charges_filed'] = '';
				if($casecover_info['criminal_charges_filed']!='' && $this->request->get['actionarea']==1){
					$this->data['criminal_charges_filed'] = $casecover_info['criminal_charges_filed'];
				}
				
				
			}	
			
		
			if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
					
				//echo '<pre>xxxx-'; print_r($this->request->post); echo '</pre>';
				//echo '<pre>yyyy-'; print_r($this->request->get); echo '</pre>'; 
				//die;
				
				$this->load->model ( 'api/temporary' );
				$tdata = array ();
				$tdata ['facilities_id'] = $facilities_id;
				$tdata ['type'] = 'updatecasecoverpage';
				
				$casecoverdata_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
				
				if ($casecoverdata_id != null && $casecoverdata_id != "") {
					$url2 .= '&casecoverdata_id=' . $casecoverdata_id;
				}
				
				
				
				//echo $this->url->link ( 'resident/formcase/addcase', '' . $url2, 'SSL' ); die;
				
				
				$this->session->data ['success2'] = 'Cover page added successfully!';
				
				$this->data ['success2'] = $this->session->data ['success2']; 
				
				
				
				//die;
				
				//$this->redirect ( $this->url->link ( 'resident/formcase/addcase', '' . $url2, 'SSL' ) );
				
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				//echo '<pre>'; print_r($facility); echo '</pre>'; die;
				
				if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
					
					$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&casecoverpage=1', '' . $url2, 'SSL' ) );
					
				} else {
					$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/cases&addcase=1', '', 'SSL' ) );
				}
			
			}
		
		
			$this->template = $this->config->get ( 'config_template' ) . '/template/resident/addcasecoverpage.php';
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
	
	public function savecasecoverpage(){
		try {
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$facilities_id = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
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
		
			//$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'customer/customer' );
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'resident/casefile' );
			$this->load->model ( 'setting/tags' );
			
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$this->data['customers'] = array();
			
			$this->data ['action'] = $this->url->link ( 'resident/formcase/addcasecovepage', '', true );
			
			
			if (! empty ( $customer_info ['setting_data'])) {
				
				$customers = unserialize($customer_info ['setting_data']);
				
				$this->data['customerinfo'] = $customers;
				if(!empty($customers['charges_value'])){
					$charges_value = explode(';',$customers['charges_value']);
					$this->data['charges_value'] = $charges_value;
				}
				
				if(!empty($customers['user_of_force_value'])){
					$user_of_force_value = explode(';',$customers['user_of_force_value']);
					$this->data['user_of_force_value'] = $user_of_force_value;
				}
			}
			
			if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
				//echo '<pre>'; print_r($this->request->post); echo '</pre>'; die;
				//echo '<pre>'; print_r($this->request->get); echo '</pre>'; 
				
				$this->load->model ( 'api/temporary' );
				
				$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['casecoverdata_id'] );
				
				
				//echo '<pre>'; print_r($temporary_info); echo '</pre>'; die;
				
				$tempdata = array ();
	
				$tempdata = unserialize ( $temporary_info ['data'] );
				
				//echo '<pre>'; print_r($tempdata); echo '</pre>'; //die;
				
				$notes_description ='';
				
				
				if($tempdata['is_update']==1){
					$case_status = $tempdata['status'];
					$notes_description .='Case Cover Page update with '.$tempdata['case_number']; 
					$case_number = $tempdata['case_number'];
				}else{
					$this->load->model ( 'resident/casefile' );
					$lastcasenumber = $this->model_resident_casefile->get_last_casenumber();
					$case_number_prefix = date ( 'y' );
					if(empty($lastcasenumber)){
						$padded_number = sprintf("%05d", 1);
						$case_number = $case_number_prefix .'-'. $padded_number;
					}else{
						$case_arr = explode('-',$lastcasenumber['case_number']);
						$number = intval($case_arr[1]) + 1;
						$formatted_number = sprintf('%05d', $number);
						$case_number = $case_number_prefix.'-'.$formatted_number;
					}
					$notes_description .= $case_number.' has been assigned with '; 
					$case_status = 0;
				}
				
				$client_name_arr = array();
				$client_name_id_str = '';
				if(!empty(array_filter($tempdata['client_name']))){
					$client_name_arr = array_filter($tempdata['client_name']);
					$client_name_id_str = implode(',',$client_name_arr);
				}
				
				$case_type_arr = array();
				$case_type_id_str = '';
				if(!empty(array_filter($tempdata['case_type']))){
					$case_type_arr = array_filter($tempdata['case_type']);
					$case_type_id_str = implode(',',$case_type_arr);
				}
				
				$incident_type_arr = array();
				$incident_type_id_str = '';
				if(!empty(array_filter($tempdata['incident_type']))){
					$incident_type_arr = array_filter($tempdata['incident_type']);
					$incident_type_id_str = implode(',',$incident_type_arr);
				}
				
				$code_arr = array();
				$code_id_str = '';
				if(!empty(array_filter($tempdata['code']))){
					$code_arr = array_filter($tempdata['code']);
					$code_id_str = implode(',',$code_arr);
				}
				
				$user_of_force_name='';
				if($tempdata['user_of_force_name']!=''){
					$user_of_force_name = $tempdata['user_of_force_name'] ;
				}
				
				$criminal_charges_filed='';
				if($tempdata['criminal_charges_filed']!=''){
					$criminal_charges_filed = $tempdata['criminal_charges_filed'] ;
				}
				
				//if($customers['case_type_add_in_notes']){
					
				if(!empty($client_name_arr)){
					$name_arr = array();
					foreach($client_name_arr AS $row){
						$tags = $this->model_setting_tags->getTag ( $row );	
						if(!empty($tags)){
							$name_arr[] = $tags['emp_last_name'].' '.$tags['emp_first_name'];
						}
					}
					$client_name_str =implode(',',$name_arr);	
					$notes_description .= ' | '.$client_name_str;
				}
				
				if(!empty($case_type_arr) && $customers['case_type_add_in_notes']==1){
					$ct_arr = array();
					foreach($case_type_arr AS $row){
						$customlistvalues = $this->model_notes_notes->getcustomlistvalue($row);
						if(!empty($customlistvalues)){
							$ct_arr[] = $customlistvalues['customlistvalues_name'];
						}
					}
					$ct_str =implode(',',$ct_arr);	
					$notes_description .= ' | Case Type - '.$ct_str;
				}
				
				if(!empty($incident_type_arr) && $customers['incident_type_add_in_notes']==1){
					$it_arr = array();
					foreach($incident_type_arr AS $row){
						$customlistvalues = $this->model_notes_notes->getcustomlistvalue($row);
						if(!empty($customlistvalues)){
							$it_arr[] = $customlistvalues['customlistvalues_name'];
						}
					}
					$it_str =implode(',',$it_arr);	
					$notes_description .= ' | Incident Type - '.$it_str;
				}
				
				if(!empty($code_arr) && $customers['code_add_in_notes']==1){
					$c_arr = array();
					foreach($code_arr AS $row){
						$customlistvalues = $this->model_notes_notes->getcustomlistvalue($row);
						if(!empty($customlistvalues)){
							$c_arr[] = $customlistvalues['customlistvalues_name'];
						}
					}
					$c_str =implode(',',$c_arr);	
					$notes_description .= ' | Code - '.$c_str;
				}
				
				if($user_of_force_name!='' && $customers['user_of_force_add_in_notes']==1){
					$notes_description .= ' | Use of force - '.$user_of_force_name;
				}
				
				if($criminal_charges_filed!='' && $customers['charges_add_in_notes']==1){
					$notes_description .= ' | Criminal Charges Filed - '.$criminal_charges_filed;
				}
				
				$this->load->model ( 'notes/notes' );
				$data = array ();
				
				$facilities_id = $facilities_id;
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
				
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$comments = ' | ' . $this->request->post ['comments'];
					
					$notes_description .= $comments ;
				}
				
				
				$data ['notes_description'] = $notes_description;
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				$data ['case_number'] = $case_number;
				$data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
				$data ['tags_id'] = $this->request->post ['tags_id'];;
				
				
				//echo '<pre>'; print_r($data); echo '</pre>'; die;
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
				
				$sql = "Update " . DB_PREFIX . "notes SET  case_number = '" . $case_number . "' WHERE notes_id = '" . $notes_id . "' ";
				$this->db->query ( $sql );
				
				$this->load->model ( 'user/user' );
				
				if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
					$user_info = $this->model_user_user->getUserByUsername ( $this->request->post ['user_id'] );
				} else {
					$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $facilities_id );
				}
				
				$casedata = array();
				$casedata['tags_ids'] = $client_name_id_str;
				$casedata['case_type'] = $case_type_id_str;
				$casedata['incident_type'] = $incident_type_id_str;
				$casedata['code'] = $code_id_str;
				$casedata['user_of_force_name'] = $user_of_force_name;
				$casedata['criminal_charges_filed'] = $criminal_charges_filed;
				$casedata['case_number'] = $case_number;
				$casedata['case_status'] = $case_status;
				$casedata['notes_id'] = $notes_id;
				$casedata['facilities_id'] = $this->customer->getId ();
				$casedata['notes_pin'] = $data['notes_pin'];
				$casedata['user_id'] = $user_info['username'];
				$casedata['signature'] = $data ['imgOutput'];
				$casedata['is_update'] = $tempdata['is_update'];	
				$this->load->model ( 'resident/casefile' );
				$this->model_resident_casefile->insertCasefile ( $casedata );
				
				
				//echo '<br>'.$notes_description;
				
				//echo '<pre>'; print_r($data); echo '</pre>'; 
				
				//echo '<pre>'; print_r($casedata); echo '</pre>';
				
				//echo '<pre>'; print_r($tempdata); echo '</pre>';  
				//die;
				
				
				
				
				
				$url2 .= '&addcase=1&tags_id='.trim($tempdata['tags_id2']);
				
				//$this->session->data ['success_add_casecoverform'] = '1';
				
				if($tempdata['minidashboard']==1){
					$this->data ['success_add_casecoverform'] = 2;
					$this->data ['case_number'] = $tempdata['case_number'];
					$this->data ['case_file_id'] = $tempdata['case_file_id'];
					$this->data ['tags_id2'] = $tempdata['tags_id2'];
				}else{
					$this->data ['success_add_casecoverform'] = 1;
					if($tempdata['is_update']){
						$this->data ['casecoverform_redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/viewcase', '&case_file_id='.$tempdata['case_file_id'].'&case_number='.$tempdata['case_number'], 'SSL' ) );
					}else{
						$this->data ['casecoverform_redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/cases', '' . $url2, 'SSL' ) );
					}
				}
				
				$this->data ['success_add_casecoverform'];
				$this->data ['casecoverform_redirect_url']; //die;
			}
			
			// echo 'xxxx'; die;
			
			$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
			$this->data ['button_save'] = $this->language->get ( 'Submit' );
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
			
			if ($this->request->get ['casecoverdata_id'] != null && $this->request->get ['casecoverdata_id'] != "") {
				$url2 .= '&casecoverdata_id=' . $this->request->get ['casecoverdata_id'];
			}
			
			if ($this->request->get ['casecoverpage'] != null && $this->request->get ['casecoverpage'] != "") {
				$url2 .= '&casecoverpage=' . $this->request->get ['casecoverpage'];
			}
			
			$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/savecasecoverpage', '' . $url2, 'SSL' ) );
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/cases', '', 'SSL' ) );
			
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
	
	public function searchTags2() {
		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$this->load->model ( 'resident/casefile' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		// if($this->request->get['emp_tag_id'] != null &&
		// $this->request->get['emp_tag_id'] != "") {
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		
		if (isset ( $this->request->get ['emp_tag_id'] )) {
			$emp_tag_id = $this->request->get ['emp_tag_id'];
		} else {
			$emp_tag_id = '';
		}

		if (isset ( $this->request->get ['q'] )) {
			$q = $this->request->get ['q'];
		} else {
			$q = '';
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		$filter_name = explode ( ':', $emp_tag_id );
		
		
	
		
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
				} else {
					$facilities_id = $this->customer->getId ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
			}
			// $facilities_id = $this->customer->getId();
		}
		
		$data = array (
			'q' => $q,
			'emp_tag_id_all' => trim ( $filter_name [0] ),
			'facilities_id' => $facilities_id,
			'status' => 1,
			'discharge' => 1,
			'all_record' => 1,
			'is_master' => 1,
			'sort' => 'emp_last_name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $limit
		);
		
		
		
		$tags = $this->model_resident_casefile->getinmatelist ( $data );
		
		//echo '<pre>'; print_r($data); echo '</pre>'; //die;
		//echo '<pre>'; print_r($tags); echo '</pre>'; die;
		
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'notes/clientstatus' );
		$this->load->model ( 'form/form' );
		
		foreach ( $tags as $result ) {
			
			if ($result ['date_of_screening'] != "0000-00-00") {
				$date_of_screening = date ( 'm-d-Y', strtotime ( $result ['date_of_screening'] ) );
			} else {
				$date_of_screening = date ( 'm-d-Y' );
			}
			if ($result ['dob'] != "0000-00-00") {
				$dob = date ( 'm-d-Y', strtotime ( $result ['dob'] ) );
			} else {
				$dob = '';
			}
			
			if ($result ['dob'] != "0000-00-00") {
				$dobm = date ( 'm', strtotime ( $result ['dob'] ) );
			} else {
				$dobm = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$dobd = date ( 'd', strtotime ( $result ['dob'] ) );
			} else {
				$dobd = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$doby = date ( 'Y', strtotime ( $result ['dob'] ) );
			} else {
				$doby = '';
			}
			
			/*
			 * if ($result['gender'] == '1') {
			 * $gender = '33';
			 * }
			 * if ($result['gender'] == '2') {
			 * $gender = '34';
			 * }
			 */
			
			$get_img = $this->model_setting_tags->getImage ( $result ['tags_id'] );
			
			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $get_img ['enroll_image'];
			}
			
			if ($result ['ssn']) {
				$ssn = $result ['ssn'] . ' ';
			} else {
				$ssn = '';
			}
			if ($result ['emp_extid']) {
				$emp_extid = $result ['emp_extid'] . ' ';
			} else {
				$emp_extid = '';
			}
			
			$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $result ['tags_id'] );
			
			if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
				$status = $tagstatusinfo ['status'];
				
				$classification_value = $this->model_resident_resident->getClassificationValue ( $tagstatusinfo ['status'] );
				$classification_name = $classification_value ['classification_name'];
			} else {
				$classification_name = '';
			}
			
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $result ['role_call'] );
			if ($clientstatus_info ['name'] != null && $clientstatus_info ['name'] != "") {
				$role_callname = $clientstatus_info ['name'];
				$color_code = $clientstatus_info ['color_code'];
				$role_type = $clientstatus_info ['type'];
			}
			if ($result ['room'] != null && $result ['room'] != "") {
				$rresults = $this->model_setting_locations->getlocation ( $result ['room'] );
				$location_name = $rresults ['location_name'];
			} else {
				$location_name = '';
			}
			
			if ($result ['date_added'] != "0000-00-00") {
				$date_added = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
			}
			
			$datsa = array();
			$datsa['forms_design_id'] = $this->request->get ['forms_design_id'];
			$datsa['facilities_id'] = $result ['facilities_id'];
			$datsa['tags_id'] = $result ['tags_id'];
			//$cseinfo = $this->model_form_form->getFormscase ( $datsa );
			
			
			$json [] = array (
				'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
				'fullname' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
				'tags_id' => $result ['tags_id'],
				'emp_first_name' => $result ['emp_first_name'],
				'emp_last_name' => $result ['emp_last_name']
			);
		}
		// }
		
		$this->response->setOutput ( json_encode ( $json ) );
	}

} 