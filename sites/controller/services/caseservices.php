<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllerservicescaseservices extends Controller {
	
	public function jsonuserLogin() {
		try {
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonuserLogin', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
			$data = json_decode(file_get_contents("php://input"));
			
			//$json = array ();
			//$jsonData2 = stripslashes ( html_entity_decode ( $this->request->post ) );
			//$username = json_decode ( $this->request->post , true );
			//$jsonData2 = stripslashes ( html_entity_decode ( $this->request->post['password'] ) );
			//$password = json_decode ( $jsonData2, true );
			
			 $username = $data->username; 				 
			 $password = $data->password; 				
			
			
			if ($username == null && $username == "") {
				
				$json ['warning'] = 'Please enter username';
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
			
			if ($password == null && $password == "") {
				
				$json ['warning'] = 'Please enter password';
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
				
				$this->load->model('user/user');
				$this->load->model('user/user_group');
				$user_info = $this->model_user_user->getuserbynamenpass($username,$password);
				
				
				
				if(!empty($user_info)){
					$user_role_info = $this->model_user_user_group->getUserGroup($user_info['user_group_id']);
					
					$this->data ['facilitiess'] [] = array (
						'user_id' => $user_info ['user_id'],
						'username' => $user_info ['username'],
						
						'firstname' => $user_info ['firstname'],
						'lastname' => $user_info ['lastname'],
						'email' => $user_info ['email'],
						'phone_number' => $user_info ['phone_number'],
						'facilities' => $user_info ['facilities'],
						'user_pin' => $user_info ['user_pin'],
						'activationKey' => $user_info ['activationKey'],
						'default_facilities_id' => $user_info ['default_facilities_id'],
						'default_highlighter_id' => $user_info ['default_highlighter_id'],
						'default_color' => $user_info ['default_color'],
						'customer_key' => $user_info ['customer_key'],
						'user_group_id' => $user_info ['user_group_id'],
						'name' => $user_role_info ['name'],
						'enable_requires_approval' => $user_role_info ['enable_requires_approval'],
						'inventory_permission' => $user_role_info ['inventory_permission'],
						'is_private' => $user_role_info ['is_private'],
						'share_notes' => $user_role_info ['share_notes'],
						'perpetual_task' => $user_role_info ['perpetual_task'],
						
					);
					
					$error = true;
				}else{
					$this->data ['facilitiess'] [] = array (
						'warning' => "Incorrect username or password please try again"
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
					'data' => 'Error in appservices jsonuserLogin ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonuserLogin', $activity_data2 );
			
			
		}
	}
	
	
	public function jsonClassification() {
		
		try {
			
			$tags_id = $this->request->post['tags_id'];
			
			$this->load->model('notes/notes');
			$Categories = $this->model_notes_notes->getcaseCategories();
			
			$this->data['caseCategories'] = array();
			
			$this->data['caseCategories'][] = array(
				'label' => 'NoteActive',
				'imageIcon' => 'http://case.noteactive.com/assets/admin/dist/img/active_note.png',
				'link' =>  'http://case.noteactive.com/assets/admin/dist/img/active_note.png',
				'externalRedirect' => true,
				'hrefTargetType' => '_blank' // _blank|_self|_parent|_top|framename
			);
			
			foreach($Categories as $category){
				$cases1 = array();
				$cases = $this->model_notes_notes->getcases($category['case_category_id']);
				
				foreach($cases as $case){
					
					$casekeys = array();	
					$caseforms = array();
					$casetasks = array();
					$nitems = array();
					
					
					$datatask = array(
						'tasks_ids' => $case['tasks'],
						'tags_id' => $tags_id, 		
					);
					
					$this->load->model('task/tasktype');
					$casetask1s = $this->model_task_tasktype->gettasktype2($datatask);
					
					if(!empty($casetask1s)){
						foreach($casetask1s as $casetask){
							$casetask1s[] = array(
							  'label' => $casetask['tasktype_name'],
							  'faIcon' => $casetask['icon'],
							  //'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $casetask['task_id']. $url2, 'SSL'),
							);
						}
						
						$nitems[] = array(
							'label' => "TASK TYPE",
							'faIcon' => 'fas fa-allergies',
							//'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $case['case_id']. $url2, 'SSL'),
							'items' => $casetask1s,
						);
					}
					
					$datakey = array(
						'keyword_ids' => $case['keywords'],
						'tags_id' => $tags_id, 		
					);
					$this->load->model('setting/keywords');
					$casekey2s = $this->model_setting_keywords->getkeywords2($datakey);
					
					if(!empty($casekey2s)){
						foreach($casekey2s as $casekey){
							$casekeys[] = array(
							  'label' => $casekey['keyword_name'],
							  'faIcon' => 'fab fa-accusoft',
							 // 'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $casekey['keyword_id']. $url2, 'SSL'),
							);
						}
						
						$nitems[] = array(
							'label' => "KEYWORDS",
							'faIcon' => 'fas fa-allergies',
							//'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $case['case_id']. $url2, 'SSL'),
							'items' => $casekeys,
						);
					}
				
					$fdata = array(
						'forms_ids' => $case['forms'],
						'tags_id' => $tags_id, 
					);
					
					$this->load->model('form/form');
					$caseform2s = $this->model_form_form->getforms2($fdata);
					
					if(!empty($caseform2s)){
						foreach($caseform2s as $caseform){
							$caseforms[] = array(
							  'label' => $caseform['form_name'],
							  'faIcon' => 'fab fa-accusoft',
							 // 'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $caseform['forms_id']. $url2, 'SSL'),
							);
						}
						
						$nitems[] = array(
							'label' => "FORMS",
							'faIcon' => 'fas fa-allergies',
							//'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $case['case_id']. $url2, 'SSL'),
							'items' => $caseforms,
						);
					}
					
					$cases1[] = array(
					 // 'case_id' => $case['case_id'],
					  'label' => $case['name'],
					  'faIcon' => $case['icon'],
					 // 'link' => $this->url->link('case/clients/detail', '' . '&case_id=' . $case['case_id']. $url2, 'SSL'),
					  'items' => $nitems,
					);
				
				}
				
				
				$this->data['caseCategories'][] = array(
				  //'case_category_id' => $category['case_category_id'],
				  'label' => $category['name'],
				  'faIcon' => $category['icon'],
				  //'link' => $this->url->link('case/clients/detail', '' . '&case_category_id=' . $category['case_category_id']. $url2, 'SSL'),
				  'items' => $cases1,
				);
				
				
			}
			
			$this->response->setOutput ( json_encode ( $this->data['caseCategories'] ) );
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}
	
	public function jsonclienttagform(){
		try{
			
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('form/form');
		$this->load->model('notes/notes'); 
		
		
		
		$tags_id = $this->request->post['tags_id'];
		
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		$name = $tag_info['emp_tag_id'].': '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		
		
		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		
		
		
		
		//$config_admin_limit1 = '5';
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
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
	
	}
	
}
 
 