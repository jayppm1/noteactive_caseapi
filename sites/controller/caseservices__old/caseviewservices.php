<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllercaseservicescaseviewservices extends Controller {
	private $error = array();
	
	public function jsonCaseNumber() {
		
		try {
			
			$tags_id = $this->request->post['tags_id'];
			
			$type = $this->request->post['type'];
			$value = $this->request->post['value'];
			
			
			
			$this->load->model('resident/casefile');
			$this->load->model('setting/tags');
				$data = array(
				'tags_id'=>$this->request->post['tags_id'],
				'facilities_id'=>$this->request->post['facilities_id'],
				'type'=>$type,
				'value'=>$value,
				
				);
				
			
			$Casenumbes = $this->model_resident_casefile->getCaseNumber($data);
			
		
			if($Casenumbes){
	
				foreach($Casenumbes as $Casenumber){
					if($Casenumber['case_status']==0){
						$case_status='Open';
					}elseif($Casenumber['case_status']==1){
						$case_status='Closed';
					}else{
						$case_status='Maked Final';
					}
					
					
					if($Casenumber['tags_ids']){
						
						$inmate_arr=array();
						$tags_ids_arr = explode(',',$Casenumber ['tags_ids']);
						foreach($tags_ids_arr AS $tag_id){
							$tag_info = $this->model_setting_tags->getTag ( $tag_id );
							$inmate_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
						}
						
						$inmate_name = implode(',',$inmate_arr);
					}else{
						
						$inmate_name='';
					}
					
					$this->data['facilitiess'][] = array(
					  'case_status' => $case_status,
					  'case_number' => $Casenumber['case_number'],
					  'notes_by_case_file_id' => $Casenumber['notes_by_case_file_id'],
					  'user_id' => $Casenumber['user_id'],
					  'forms_ids' => $Casenumber['forms_ids'],
					  'tags_ids' => $Casenumber['tags_ids'],
					  'inmate_name' => $inmate_name,
					  'signature' => $Casenumber['signature'],
					  'notes_pin' => $Casenumber['notes_pin'],
					  'date_added' => $Casenumber['date_added'],
					);
				}
				
					
			$error = true;
			
			}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select an existing case or add new to start a new file',
			);
			$error = false;
		}			
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}
	
	public function jsonCaseForms(){
		try{
			
			$this->data['facilitiess'] = array();
			$this->language->load('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('form/form');
			$this->load->model('notes/notes'); 
		
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			} else {
				$page = 1;
			}
		
			$config_admin_limit1 = $this->config->get('config_android_front_limit');
			
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "25";
			}
		
			$this->load->model('resident/casefile');
			$tags_id = $this->request->post['tags_id'];
			
			$data1 = array(
				//'case_file_id' => $this->request->post['case_file_id'],
				'case_file_id' => $this->request->post['case_file_id'],
			);
			
			
			$Casefiles = $this->model_resident_casefile->getcasefile($data1);
			
			$data = array(
				'tags_id'=>$tags_id ,
				'facilities_id'=>$this->request->post['facilities_id'],
				//'custom_form_type'=>$this->request->post['forms_ids'],
				'forms_ids'=>$Casefiles['forms_ids'],
				'case_number'=>$this->request->post['case_number'],
				//'is_case'=>'1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit
				);
						
			$this->load->model('form/form');
			$Caseforms = $this->model_form_form->gettagsforms($data);
		
			if($Caseforms){
	
				foreach($Caseforms as $Caseform){
									
						$note_info = $this->model_notes_notes->getNote ( $Caseform ['notes_id'] );
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
											
					$this->data['facilitiess'][] = array(
					  
					  'form_description' => $Caseform['form_description'],
					  'incident_number' => $Caseform['incident_number'],
					  'date_added' => $Caseform['date_added'],
					  'tags_id'=>$tags_id ,
					  'notes_description'=>$notes_description ,
					  'user_id'=>$user_id ,
					  'signature'=>$signature ,
					  'notes_pin'=>$notes_pin ,
					  'notes_type'=>$notes_type ,
					);
				}
			$error = true;
			
			}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'd',
			);
			$error = false;
		}			
			
				
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
	
	}
	
	
	public function jsoncaselist(){
		try {
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->language->load ( 'notes/notes' );
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->load->model ( 'facilities/online' );
			
			if ($this->request->post ['status'] != null && $this->request->post ['status'] != "") {
				$status = $this->request->post ['status'];
			} else {
				$status = 0;
			}
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$facilities_id = $this->request->post ['facilities_id'];
			} else {
				$facilities_id = '';
			}
				
			$data = array (
				'is_case' => '1',
				'status' => $status,
				'facilities_id' => $facilities_id,
				'add_case' => '1'
			);
			
			$this->load->model ( 'resident/casefile' );
			
			$allforms = $this->model_resident_casefile->getCaseNumber ( $data );
			
			$total_count = $this->model_resident_casefile->getCaseNumber2 ( $data );
			
			//echo '<pre>'; print_r($allforms); echo '</pre>'; //die;
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
				
							
				$this->data ['casedata'] [] = array (
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
			
			$value = array('results'=>$this->data['casedata'],'all_total'=>$total_count,'status'=>true);
		
			$this->response->setOutput(json_encode($value));
	
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsoncases', $activity_data2);
		}
				
				
			
			
			//echo '<pre>'; print_r($this->data ['tagsforms']); echo '</pre>';
				
			//die;
			
			
		
	}

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
			
		   
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data['customers'] = array();
			if (! empty ( $customer_info ['setting_data'])) {
				$customers = unserialize($customer_info ['setting_data']);
				$this->data['customerinfo'] = $customers;
				
				
				//echo '<pre>'; print_r($facility); echo '</pre>'; //die;
				
				$casetype_data = array();
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
				
				$incidenttype_data = array();
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
				
				$codetype_data = array();
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
			
			
			
				
			$casedata = array (
					'client_data' => $client_data,
					'case_type' => $casetype_data,
					'incident_type' => $incidenttype_data,
					'code' => $codetype_data
			
			);
			
			$value = array('results'=>$casedata,'status'=>true);
		
			$this->response->setOutput(json_encode($value));
	
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsoncases', $activity_data2);
		}
	}

	

	
		
}
 
 