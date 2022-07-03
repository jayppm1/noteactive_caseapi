<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllercaseservicescasecoverpage extends Controller {
	
	public function jsoncasecoverlist(){
		try {
			$json = array ();
			$this->load->model ( 'notes/tags' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'customer/customer' );
			$this->load->model('notes/notes');
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
			
			$facilities_id = $this->request->post ['facilities_id'];
			
			
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
					'limit' => 5
			);
			
			$tags = $this->model_setting_tags->getTags ( $data );
			
			$this->load->model ( 'setting/locations' );
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'notes/clientstatus' );
			$this->load->model ( 'form/form' );
			
			foreach ( $tags as $result ) {
				
				$client_data [] = array (
					'tags_id' => $result ['tags_id'],
					'emp_first_name' => $result ['emp_first_name'],
					'emp_last_name' => $result ['emp_last_name']
				);
			}
			
			/************************************dsdsdsdsd*********************************/
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			//echo '<pre>'; print_r($unique_id); echo '</pre>'; //die;
			
			$casetype_data = array();
			$incidenttype_data = array();
			$codetype_data = array();
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data['customers'] = array();
			if (! empty ( $customer_info ['setting_data'])) {
				$customers = unserialize($customer_info ['setting_data']);
				$this->data['customerinfo'] = $customers;
				
				
				//echo '<pre>'; print_r($facility); echo '</pre>'; //die;
				
				
				if(!empty($customers['case_type_values'])){
					foreach($customers['case_type_values'] as $customlist_id){
						$d2 = array();
						$d2['customlist_id'] = $customlist_id;
						
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						foreach($customlistvalues AS $row){
							$casetype_data [] = array(
								'customlistvalues_id' => $row['customlistvalues_id'],
								'customlistvalues_name'  => $row['customlistvalues_name']
							);
						}
					}
			
				}
				
				
				if(!empty($customers['incident_type_values'])){
					foreach($customers['incident_type_values'] as $customlist_id){
						$d2 = array();
						$d2['customlist_id'] = $customlist_id;
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						foreach($customlistvalues AS $row){
							$incidenttype_data [] = array(
								'customlistvalues_id' => $row['customlistvalues_id'],
								'customlistvalues_name'  => $row['customlistvalues_name']
							);
						}
					}
			
				}
				
				
				if(!empty($customers['code_values'])){
					foreach($customers['code_values'] as $customlist_id){
						$d2 = array();
						$d2['customlist_id'] = $customlist_id;
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						foreach($customlistvalues AS $row){
							$codetype_data [] = array(
								'customlistvalues_id' => $row['customlistvalues_id'],
								'customlistvalues_name'  => $row['customlistvalues_name']
							);
						}
					}
				}
			}
			
			$json = array (
				'client_data' => $client_data,
				'case_type' => $casetype_data,
				'incident_type' => $incidenttype_data,
				'code' => $codetype_data
			);
			
			$value = array('results'=>$json,'status'=>true);
		
			$this->response->setOutput(json_encode($value));
	
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsoncases', $activity_data2);
		}
	}

	public function jsoncasedatabycaseid(){
		try {
			$json = array();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/casefile' );
			$facilities_id ='';
			if($this->request->post ['facilities_id']!=""){
				$facilities_id = $this->request->post ['facilities_id'];
			}
			
			$case_number ='';
			if($this->request->post ['case_number']!=""){
				$case_number = $this->request->post ['case_number'];
			}
			
			$case_file_id ='';
			if($this->request->post ['case_file_id']!=""){
				$case_file_id = $this->request->post ['case_file_id'];
			}
			
			$tags_id ='';
			if($this->request->post ['tags_id']!=""){
				$tags_id = $this->request->post ['tags_id'];
			}
			
			
			
			$case_type_name_arr = array();
			$incident_type_name_arr = array();
			$code_name_arr = array();
			$case_type_arrs = array();
			//$incident_type_arrs = array();
			$code_type_arrs = array();
			$user_of_force_names = '';
			$criminal_charges_fileds = '';
			$meaasge='';
			if ($this->request->post ['case_file_id'] != null && $this->request->post ['case_file_id'] != "") {
				
				$data = array (
					'case_file_id' => $this->request->post ['case_file_id'],
					'facilities_id' => $facilities_id 
				);
				
				$casecover_info = $this->model_resident_casefile->getcasefileforviewcase ( $data );
				
				//echo '<pre>ff'; print_r($casecover_info); echo '</pre>';
				
				
				
				//[case_type] => 285
    //[incident_type] => 253
    //[code] => 280,282
				
				
				if($casecover_info){
					
					$inmate_arr = array();
					if($casecover_info['tags_ids']!=''){
						$client_name_id_arr = explode(',',$casecover_info['tags_ids']);
						
						foreach($client_name_id_arr AS $row){
							$tags = $this->model_setting_tags->getTag ( $row );	
							$inmate_data_arr = array();
							if(!empty($tags)){
								//$inmate_data_arr['tags_id'] = $tags['tags_id'];
								//$inmate_data_arr['name'] = $tags['emp_last_name'].' '.$tags['emp_first_name'];
								$inmate_arr[] = $tags['emp_last_name'].' '.$tags['emp_first_name'];
							}
						}	
					}
					
					
					
					
					$case_type_arrs = array();
					if($casecover_info['case_type']!=''){
						$case_type_arr = explode(',',$casecover_info['case_type']);
						foreach($case_type_arr AS $case_type){
							$res = $this->model_notes_notes->getcustomlistvalue($case_type);
							$case_type_name_arr['case_type_id'] = $res['customlistvalues_id'];
							$case_type_name_arr['case_type_name'] = $res['customlistvalues_name'];
							$case_type_arrs[] = $case_type_name_arr;
						}
					}
					
					$incident_type_arrs =array();
					if($casecover_info['incident_type']!=''){
						$incident_type_arr = explode(',',$casecover_info['incident_type']);
						foreach($incident_type_arr AS $incident_type){
							$res = $this->model_notes_notes->getcustomlistvalue($incident_type);
							$incident_type_name_arr['incident_type_id'] = $res['customlistvalues_id'];
							$incident_type_name_arr['incident_type_name'] = $res['customlistvalues_name'];
							$incident_type_arrs[] = $incident_type_name_arr;
						}
						
					}
					
				
					
					$code_type_arrs = array();
					if($casecover_info['code']!=''){
						$code_arr = explode(',',$casecover_info['code']);
						foreach($code_arr AS $code){
							$res = $this->model_notes_notes->getcustomlistvalue($code);
							$code_name_arr['code_type_id'] = $res['customlistvalues_id'];
							$code_name_arr['code_type_name'] = $res['customlistvalues_name'];
							$code_type_arrs[] = $code_name_arr;
						}
						
					}
					
					if($casecover_info['user_of_force_name']!=''){
						$user_of_force_names = $casecover_info['user_of_force_name'];
					}else{
						$user_of_force_names = 'No use of force name';
					}
					
					
					if($casecover_info['criminal_charges_filed']!=''){
						$criminal_charges_fileds = $casecover_info['criminal_charges_filed'];
					}else{
						$criminal_charges_fileds = 'No criminal charges filed';
					}
					
					if($casecover_info['case_status']==0){
						$case_status = 'Open';
					}else if($casecover_info['case_status']==1){
						$case_status = 'Closed';
					}else if($casecover_info['case_status']==2){
						$case_status = 'Marked Final';
					}
					
					
					
					$meaasge = 'Success';
					$status = true;
				}
			}else{
				$meaasge = 'Credential not set';
				$status = false;
			}
			
			$case_data = array(
				'case_type' => $case_type_arrs,
				'incident_type' => $incident_type_arrs,
				'code' => $code_type_arrs,
				
				'casetypeIds' => $casecover_info['case_type'],
				'incidenttypeIds' => $casecover_info['incident_type'],
				'codeIds' => $casecover_info['code'],
				
				
				'user_of_force_names' => $user_of_force_names,
				'criminal_charges_fileds' => $criminal_charges_fileds,
				'tags_names' => implode(',',$inmate_arr),
				'tags_ids' => $casecover_info['tags_ids'],
				'case_status' => $case_status,
			);
			
			$value = array('results'=>$case_data,'status'=>$status,'message'=>$meaasge);
		
			$this->response->setOutput(json_encode($value));
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in apptask jsonviewcase '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsoncases', $activity_data);
		}	
			
			
			
	}
	
	public function jsonviewformlistbycase_number_and_case_id(){
		try {
			
			$json = array();
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/casefile' );
			
			$facilities_id ='';
			if($this->request->post ['facilities_id']!=""){
				$facilities_id = $this->request->post ['facilities_id'];
			}
			
			$case_number ='';
			if($this->request->post ['case_number']!=""){
				$case_number = $this->request->post ['case_number'];
			}
			
			$case_file_id ='';
			if($this->request->post ['case_file_id']!=""){
				$case_file_id = $this->request->post ['case_file_id'];
			}
			
			$tags_id ='';
			if($this->request->post ['tags_id']!=""){
				$tags_id = $this->request->post ['tags_id'];
			}
			
			if ($case_number != null && $case_number != "") {
				
				$cidata = array (
					'case_number' => $case_number,
					'facilities_id' => $facilities_id ,
					'case_file_id' => $case_file_id,
					'tags_id' => $tags_id
				);
				//echo '<pre>'; print_r($cidata); echo '</pre>'; //die;
				$case_info = $this->model_resident_casefile->getcasefileByCasenumber ( $cidata );
			} else {
				$case_file_id = '';
			}
			
			$adata = array (
				'tags_ids' => $case_info ['tags_ids'],
				'case_number' => $case_number,
			);
			
			//echo '<pre>'; print_r($adata); echo '</pre>'; //die;
			
			$aallattas = $this->model_setting_tags->gettagsattachmets ( $adata );
			
			//echo '<pre>'; print_r($aallattas); echo '</pre>'; //die;
						
			$attachments = array ();
			
			$flag_status = false;
			
			foreach ( $aallattas as $aallatta ) {
				$flag_status = true;
				$tag_info = $this->model_setting_tags->getTag ( $aallatta ['tags_id'] );
				$client_name = $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
				if ($aallatta ['notes_file'] != null && $aallatta ['notes_file'] != "") {
					//$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $aallatta ['notes_media_id'], 'SSL' );
					$hrurl = $aallatta ['notes_file'];	
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
			
				$attachments [] = array (
						'notes_media_id' => $aallatta ['notes_media_id'],
						'name' => "Attachment",
						'form_href' => $hrurl,
						'forms_id'=> $aallatta['forms_id'],
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
			
			//echo '<pre>'; print_r($attachments); echo '</pre>'; 
			
			$data = array (
				'sort' => '',
				'order' => '',
				'is_case' => 0,
				'page_name' => 'viewcase',
				// 'status' => $status,
				'case_number' => $case_number,
				'case_file_id' => $case_file_id,
				'tags_id' => $tags_id,
				'add_case' => '1',
				//'start' => ($page - 1) * $config_admin_limit,
				//'limit' => $config_admin_limit 
			);
			
			$this->load->model ( 'resident/casefile' );
			
			$allform1 = $this->model_resident_casefile->getcasefileforviewcase2 ( $data );
			//echo '<pre>'; print_r($allform1); echo '</pre>'; //die;
			$json = array ();
			
			foreach ( $allform1 as $allform ) {
				
				$flag_status = true;
				
				//echo '<pre>'; print_r($allform['tags_ids']); echo '</pre>'; //die;
				
				$this->data ['case_status'] = $allform ['case_status'];
				
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				
				$tag_name_arr = array();
				
				$atags_ids = $this->model_notes_notes->getNotesTagsmultiple ( $allform ['notes_id'] );
				
				foreach ($atags_ids as $atags_id){
					$tag_id_arr[] = $atags_id['tags_id'];
					$tag_info = $this->model_setting_tags->getTag ( $atags_id['tags_id'] );
					$tag_name_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
				}
				
				
				if($note_info['tags_id']!=0){
					
					$tag_info = $this->model_setting_tags->getTag ( $note_info ['tags_id'] );
					$tag_name_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
					
				}
				
				$flag=0;
				if(in_array($tags_id,$tag_id_arr)){
					$flag=1;	
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
				
				if($tags_id!="" && $flag==1){
				
				$json[] = array (
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
				
				}else if($tags_id=='' && $flag==0){
					
				$json[] = array (
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
			}
			
			$value = array('results'=>$json,'attachments'=>$attachments, 'status'=>$flag_status);
		
			$this->response->setOutput(json_encode($value));
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in apptask jsonviewcase '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsoncases', $activity_data);
		}	
			
			
			
	}
	
	public function jsonaddcasecover(){
		try {
			
			
			$json = array();
			
			$facilities_id="";
			if($this->request->post['facilities_id']!=""){
				$facilities_id = $this->request->post['facilities_id'];
			}
			
			
			
			$is_update="";
			if($this->request->post['is_update']!=""){
				$is_update = $this->request->post['is_update'];
			}
			
			$case_number="";
			if($this->request->post['case_number']!=""){
				$case_number = $this->request->post['case_number'];
			}
			
			$status="";
			if($this->request->post['status']!=""){
				$status = $this->request->post['status'];
			}
			
			
			//echo '<pre>'; print_r($this->request->post); echo '</pre>'; 
			
			date_default_timezone_set($this->request->post['facilitytimezone']);
		
			//$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'customer/customer' );
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'resident/casefile' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'facilities/facilities' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$this->data['customers'] = array();
			
			$this->data ['action'] = $this->url->link ( 'resident/formcase/addcasecovepage', '', true );
			
			
			if (! empty ( $customer_info ['setting_data'])) {
				
				$customers = unserialize($customer_info ['setting_data']);
				
				
				if(!empty($customers['user_of_force_value'])){
					$user_of_force_value = explode(';',$customers['user_of_force_value']);
					$this->data['user_of_force_value'] = $user_of_force_value;
				}
			}
				
			//echo '<pre>'; print_r($customers); echo '</pre>'; die;	
			$notes_description ='';
			
			if($this->request->post['is_update']==1){
				$case_status = $status;
				$notes_description .='Case Cover Page update with '.$case_number; 
				$case_number = $case_number;
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
				
				
			$client_name_id_str="";
			$client_name_arr = array();
			if($this->request->post['client_name']!=""){
				$client_name_arr = explode(',',$this->request->post['client_name']);
				$client_name_id_str = $this->request->post['client_name'];
			}
			
			$case_type_id_str="";
			$case_type_arr = array();
			if($this->request->post['case_type']!=""){
				$case_type_arr = explode(',',$this->request->post['case_type']);
				$case_type_id_str = $this->request->post['case_type'];
			}
			
			$incident_type_id_str="";
			$incident_type_arr = array();
			if($this->request->post['incident_type']!=""){
				$incident_type_arr = explode(',',$this->request->post['incident_type']);
				$incident_type_id_str = $this->request->post['incident_type'];
			}
			
			$code_arr = array();
			$code_id_str="";
			if($this->request->post['code']!=""){
				$code_arr = explode(',',$this->request->post['code']);
				$code_id_str = $this->request->post['code'];
			}
			
			$user_of_force_name="";
			if($this->request->post['user_of_force_name']!=""){
				$user_of_force_name = $this->request->post['user_of_force_name'];
			}
			
			$criminal_charges_filed="";
			if($this->request->post['criminal_charges_filed']!=""){
				$criminal_charges_filed = $this->request->post['criminal_charges_filed'];
			}
			
			$client_name="";
			if($this->request->post['client_name']!=""){
				$client_name = $this->request->post['client_name'];
			}
			
			$case_type="";
			if($this->request->post['case_type']!=""){
				$case_type = $this->request->post['case_type'];
			}
			
			$incident_type="";
			if($this->request->post['incident_type']!=""){
				$incident_type = $this->request->post['incident_type'];
			}
			
			$code="";
			if($this->request->post['code']!=""){
				$code = $this->request->post['code'];
			}
			
			$user_of_force_name="";
			if($this->request->post['user_of_force_name']!=""){
				$user_of_force_name = $this->request->post['user_of_force_name'];
			}
			
			$criminal_charges_filed="";
			if($this->request->post['criminal_charges_filed']!=""){
				$criminal_charges_filed = $this->request->post['criminal_charges_filed'];
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
			
			
			//echo $notes_description;  //die;
			
			$this->load->model ( 'notes/notes' );
			$data = array ();
			
			$facilities_id = $facilities_id;
			date_default_timezone_set($this->request->post['facilitytimezone']);
			
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
			
			$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			if ($this->request->post ['imgOutput']) {
				$data ['imgOutput'] = $this->request->post ['imgOutput'];
			} else {
				$data ['imgOutput'] = $this->request->post ['signature'];
			}
			
			
			
			$data ['notes_pin'] ="";
			if($this->request->post['notes_pin']!=""){
				$data ['notes_pin'] = $this->request->post['notes_pin'];
			}
			
			
			$data ['user_id'] ="";
			if($this->request->post['user_id']!=""){
				$data ['user_id'] = $this->request->post['user_id'];
			}
			
			
			$emp_tag_id="";
			if($this->request->post['emp_tag_id']!=""){
				$emp_tag_id = $this->request->post['emp_tag_id'];
			}
			
			$tags_id="";
			if($this->request->post['tags_id']!=""){
				$tags_id = $this->request->post['tags_id'];
			}
			
			if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
				$comments = ' | ' . $this->request->post ['comments'];
				
				$notes_description .= $comments ;
			}
				
				
			$data ['notes_description'] = $notes_description;
			$data ['date_added'] = $date_added;
			$data ['note_date'] = $date_added;
			$data ['notetime'] = $notetime;
			$data ['case_number'] = $case_number;
			$data ['emp_tag_id'] = $emp_tag_id;
			$data ['tags_id'] = $tags_id;
			
			
			//echo '<pre>jsonaddnotes'; print_r($data); echo '</pre>'; //die;
			
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
			$casedata['facilities_id'] = $facilities_id;
			$casedata['notes_pin'] = $data['notes_pin'];
			$casedata['user_id'] = $user_info['username'];
			$casedata['signature'] = $data ['imgOutput'];
			$casedata['is_update'] = $is_update;	
			$this->load->model ( 'resident/casefile' );
			
			if($this->model_resident_casefile->insertCasefile ( $casedata )){
				$status = true;
			}else{
				$status = false;
			}
			
			//echo '<br>'.$notes_description;
			
			//echo '<pre>'; print_r($data); echo '</pre>'; 
			
			//echo '<pre>'; print_r($casedata); echo '</pre>';
			
			//echo '<pre>'; print_r($tempdata); echo '</pre>';  
			//die;
			
			
				
				
				
			$value = array('status'=>true);
		
			$this->response->setOutput(json_encode($value));
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in apptask jsonviewcase '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsoncases', $activity_data);
		}	
	}
	
	public function jsondeletecaseform(){
		
		date_default_timezone_set($this->request->post['facilitytimezone']);
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/casefile' );
		$data = array ();
		
		$facilities_id="";
		if($this->request->post['facilities_id']!=""){
			$facilities_id = $this->request->post['facilities_id'];
		}
		
		$case_number="";
		if($this->request->post['case_number']!=""){
			$case_number = $this->request->post['case_number'];
		}
		
		if ($this->request->post ['imgOutput']) {
			$data ['imgOutput'] = $this->request->post ['imgOutput'];
		} else {
			$data ['imgOutput'] = $this->request->post ['signature'];
		}
		
		$data ['notes_pin'] ="";
		if($this->request->post['notes_pin']!=""){
			$data ['notes_pin'] = $this->request->post['notes_pin'];
		}
		
		$data ['user_id'] ="";
		if($this->request->post['user_id']!=""){
			$data ['user_id'] = $this->request->post['user_id'];
		}
		
		$emp_tag_id="";
		if($this->request->post['emp_tag_id']!=""){
			$emp_tag_id = $this->request->post['emp_tag_id'];
		}
		
		$tags_id="";
		if($this->request->post['tags_id']!=""){
			$tags_id = $this->request->post['tags_id'];
		}
		
		$comments='';
		if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
			$comments = $this->request->post ['comments'];
		}
			
		
		$data ['comments'] = $comments;
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		$data ['case_number'] = $case_number;
		$data ['emp_tag_id'] = $emp_tag_id;
		$data ['tags_id'] = $tags_id;
		$data ['facilities_id'] = $facilities_id;
		$data ['facilitytimezone'] = $this->request->post['facilitytimezone'];
		$data ['notes_media_id'] = $this->request->post['notes_media_id'];
		$data ['forms_id'] = $this->request->post['forms_id'];
		$data ['case_file_id'] = $this->request->post['case_file_id'];
		
		//echo '<pre>'; print_r($data); echo '</pre>'; die;
		
		if($this->model_resident_casefile->deletecasecover ( $data )){
			$status=true;
			$message = 'Delete success';
		}else{
			$status=false;
			$message = 'Delete fail';
		}
	
		$value = array('status'=>$status,'message'=>$message);
	
		$this->response->setOutput(json_encode($value));
	}

	
	public function jsontaggedformlist(){
		
		try{
			$this->load->model ( 'facilities/online' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'resident/casefile' );
			$this->language->load ( 'notes/notes' );
			
			$tags_id="";
			
			if($this->request->post['tags_id']!=""){
				$tags_id = $this->request->post['tags_id'];
			}
			
			
			
			$tagsids="";
			if($this->request->post['tagsids']!=""){
				$tagsids = $this->request->post['tagsids'];
			}
			
			
			
			$case_file_id="";
			if($this->request->post['case_file_id']!=""){
				$case_file_id = $this->request->post['case_file_id'];
			}
			
			$facilitytimezone="";
			if($this->request->post['facilitytimezone']!=""){
				$facilitytimezone = $this->request->post['facilitytimezone'];
			}
			
			
			$data = array (
				//'sort' => $sort,
				//'order' => $order,
				'is_case' => 1,
				'page_name' => 'viewcase',
				'page_name2' => 'addcase',
				'case_file_id' => $case_file_id,
				//'case_number' => $case_number,
				'add_case' => '1',
				//'start' => ($page - 1) * $config_admin_limit,
				//'limit' => $config_admin_limit 
			);
			
			$data2 = array();
			if($tags_id!=""){
				$data2 = array ('tags_id' => $tags_id);
			}else{
				$data2 = array ('tagsids' => $tagsids);
			}
			
			$data = array_merge($data,$data2);
			
			$this->load->model ( 'form/form' );
			$allforms = $this->model_form_form->gettagsformsforcase ( $data );
			//echo '<pre>'; print_r($data); echo '</pre>'; die;
			
			foreach ( $allforms as $allform ) {
				$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
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
				
				foreach($notetg_info AS $tagrow){
					$tag_info = $this->model_setting_tags->getTagbyEMPID ( $tagrow ['emp_tag_id'] );
					$tag_namex[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
				}
				
				
				$results[] = array (
					'form_name' => $form_name,
					'inmate_name' => implode(',',$tag_namex),
					'notes_description' => $notes_description,
					'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
					'action'=> $allform ['forms_id']
				);
			}
			
			if(count($allforms)>0){
				$message = 'success';
				$status = true;
			}else{
				$message = 'fail';
				$status = false;
			}
			
			
			//$tagsforms2[] = $results;
			
			$value = array('results'=>$results,'formresults'=>$results,'message'=> $message,'status'=>$status);
			$this->response->setOutput(json_encode($value));
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in apptask jsonviewcase '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsoncases', $activity_data);
		}	
			
	}
	
	
	public function jsonaddforms() {
    	try {
    		
    		$this->load->model ( 'activity/activity' );
    		$this->model_activity_activity->addActivitySave ( 'jsonaddforms', $this->request->post, 'request' );
			
			$this->load->model ( 'form/form' );
			$formdata_i = $this->model_form_form->getFormDatadesign ( $this->request->post ['forms_design_id'] );
						
			$data2 = array ();
			$data2 ['forms_design_id'] = $this->request->post ['forms_design_id'];
			$data2 ['form_design_parent_id'] = $formdata_i ['parent_id'];
			$data2 ['page_number'] = $formdata_i ['page_number'];
			$data2 ['form_parent_id'] = $this->request->post ['formreturn_id'];
			
			$data2 ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$forms_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
			
			$this->data ['facilitiess'] [] = array (
				'forms_id' => $forms_id,
				
			);
			
			$error = true;
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
			
			
    	} catch ( Exception $e ) {
    			
    			
    		$this->load->model ( 'activity/activity' );
    		$activity_data2 = array (
    				'data' => 'Error in appservices jsonaddforms ' . $e->getMessage ()
    		);
    		$this->model_activity_activity->addActivity ( 'jsonaddforms', $activity_data2 );
    	}
	}	
	
	
	
	public function newformsign() {
		try {
		$this->language->load ( 'notes/notes' );
		
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/facilities' );
		
		
		$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
		$timezone_name = $timezone_info ['timezone_value'];
		
		$tdata = array ();
		$tdata ['tags_id'] = $this->request->post ['tags_id'];
		$tdata ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		$tdata ['formreturn_id'] = $this->request->post ['formreturn_id'];
		$tdata ['forms_design_id'] = $this->request->post ['forms_design_id'];
		$tdata ['searchdate'] = $this->request->post ['searchdate'];
		$tdata ['facilityids'] = $this->request->post ['facilityids'];
		$tdata ['locationids'] = $this->request->post ['locationids'];
		$tdata ['tagsids'] = $this->request->post ['tagsids'];
		$tdata ['task_id'] = $this->request->post ['task_id'];
		$tdata ['tag_status_id'] = $this->request->post ['tag_status_id'];
		$tdata ['facilities_id'] = $this->request->post ['facilities_id'];
		$tdata ['facilitytimezone'] = $timezone_name;
		
		if ($this->request->post ['task_id'] != null && $this->request->post ['task_id'] != "") {
			$notes_id = $this->model_form_form->taskforminsertsign ( $this->request->post, $tdata );
		} else {
			$notes_id = $this->model_form_form->newformsign ( $this->request->post, $tdata );
		}
		
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . (int)$notes_id . "' WHERE forms_id = '" . ( int ) $this->request->post ['formreturn_id'] . "'" );
		
		$this->data ['facilitiess'] [] = array (
			'notes_id' => $notes_id,
			
		);
		$error = true;
			
		$value = array (
				'results' => $this->data ['facilitiess'],
				'status' => $error 
		);
		$this->response->setOutput ( json_encode ( $value ) );
		
		
		} catch ( Exception $e ) {
    			
    			
    		$this->load->model ( 'activity/activity' );
    		$activity_data2 = array (
    				'data' => 'Error in appservices newformsign ' . $e->getMessage ()
    		);
    		$this->model_activity_activity->addActivity ( 'newformsign', $activity_data2 );
    	}
		
	}
	
}
 
 