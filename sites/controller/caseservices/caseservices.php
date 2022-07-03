<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllercaseservicescaseservices extends Controller {
	
	public function generate_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0C2f ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
		);

	}
	
	public function jsonuserLogin() {
		try {
				
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonuserLogin', $this->request->post, 'request' );
				
			$this->data ['facilitiess'] = array ();
				
			// $data = json_decode(file_get_contents("php://input"));
				
			// $json = array ();
			// $jsonData2 = stripslashes ( html_entity_decode ( $this->request->post ) );
			// $username = json_decode ( $this->request->post , true );
			// $jsonData2 = stripslashes ( html_entity_decode ( $this->request->post['password'] ) );
			// $password = json_decode ( $jsonData2, true );
				
			// $data->username
			// $data->password;
				
			// $username = $data->username;
			// $password = $data->password;
				
			$username = $this->request->post ['username'];
			$password = $this->request->post ['password'];
				
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
	
				$this->load->model ( 'user/user' );
				$this->load->model ( 'user/user_group' );
				$userdetail = $this->model_user_user->getuserbynamenpass ( $username, $password );
	
				if (! empty ( $userdetail )) {
					$phone_number = $userdetail ['phone_number'];
					$randomNum = mt_rand ( 100000, 999999 );
					$message = 'Your OTP for activation is ' . $randomNum;
					$this->load->model ( 'api/smsapi' );
					$sdata = array ();
					$sdata ['message'] = $message;
					$sdata ['phone_number'] = $phone_number;
						
					/*
					 * if ($userdetail['facilities'] != null && $userdetail['facilities'] != "") {
					 * $facilities = explode(',', $userdetail['facilities']);
					 *
					 *
					 * foreach ($facilities as $facility) {
					 * $facilities_info = $this->model_facilities_facilities->getfacilities($facility);
					 *
					 * if ($facilities_info['sms_number'] != null && $facilities_info['sms_number'] != "") {
					 * $facilities_id = $facilities_info['facilities_id'];
					 * break;
					 * }
					 * }
					 * }
					 */
						
					$facilities_id = $userdetail ['default_facilities_id'];
					$sdata ['facilities_id'] = $facilities_id;
						
					if ($userdetail ['phone_number'] != null && $userdetail ['phone_number'] != "") {
						$response = $this->model_api_smsapi->sendsms ( $sdata );
					}
						
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						
					$this->load->model ( 'setting/timezone' );
						
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
					$timeZone = date_default_timezone_set ( $timezone_name );
						
					$date_added11 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
					$share_note_otp = rand ( 0, 100000 );
						
					$dataotp = array (
							'user_id' => $userdetail ['user_id'],
							'otp' => $randomNum,
							'date_added' => $date_added11,
							'response' => $response,
							'facilities_id' => $facilities_id,
							'notes_id' => '0',
							'alternate_email' => $userdetail ['email'],
							'share_note_otp' => $share_note_otp,
							'status' => '1',
							'otp_type' => 'dual_note'
					);
						
					$user_otp_id = $this->model_user_user->insertUserOTP ( $dataotp );
						
					if ($userdetail ['email'] != null && $userdetail ['email'] != "") {
	
						$this->load->model ( 'api/emailapi' );
	
						$edata = array ();
						$edata ['message'] = $message;
						$edata ['facility'] = $facilities_info ['facility'];
						$edata ['user_email'] = $userdetail ['email'];
						$edata ['when_date'] = date ( "l" );
						$edata ['who_user'] = $userdetail ['username'];
						$edata ['type'] = "8";
						$edata ['notes_description'] = $message;
						$edata ['subject'] = $facilities_info ['facility'] . ' | ' . 'Your verification code';
						$email_status = $this->model_api_emailapi->sendmail ( $edata );
					}
					$this->data ['facilitiess'] [] = array (
							'user_id' => $userdetail ['user_id'],
							'user_otp_id' => $user_otp_id
					)
					;
						
					$error = true;
				} else {
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
					'status' => $error,
					'success' => 1
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
	public function resendotp() {
		try {
				
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'resendotp', $this->request->post, 'request' );
				
			$this->data ['facilitiess'] = array ();
				
			$user_id = $this->request->post ['user_id'];
			$user_otp_id = $this->request->post ['user_otp_id'];
				
			if ($user_otp_id == null && $user_otp_id == "") {
	
				$json ['warning'] = 'Please enter User';
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
				
			if ($user_id == null && $user_id == "") {
	
				$json ['warning'] = 'Please enter user';
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
	
				$this->load->model ( 'user/user' );
				$this->load->model ( 'user/user_group' );
				$userdetail = $this->model_user_user->getUser ( $user_id );
				$userotp = $this->model_user_user->getuserOPTbyid ( $user_id, $user_otp_id );
	
				if (! empty ( $userdetail )) {
					$phone_number = $userdetail ['phone_number'];
					$randomNum = $userotp ['otp'];
					$message = 'Your OTP for activation is ' . $randomNum;
					$this->load->model ( 'api/smsapi' );
					$sdata = array ();
					$sdata ['message'] = $message;
					$sdata ['phone_number'] = $phone_number;
						
					$facilities_id = $userdetail ['default_facilities_id'];
					$sdata ['facilities_id'] = $facilities_id;
						
					if ($userdetail ['phone_number'] != null && $userdetail ['phone_number'] != "") {
						$response = $this->model_api_smsapi->sendsms ( $sdata );
					}
						
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						
					if ($userdetail ['email'] != null && $userdetail ['email'] != "") {
	
						$this->load->model ( 'api/emailapi' );
	
						$edata = array ();
						$edata ['message'] = $message;
						$edata ['facility'] = $facilities_info ['facility'];
						$edata ['user_email'] = $userdetail ['email'];
						$edata ['when_date'] = date ( "l" );
						$edata ['who_user'] = $userdetail ['username'];
						$edata ['type'] = "8";
						$edata ['notes_description'] = $message;
						$edata ['subject'] = $facilities_info ['facility'] . ' | ' . 'Your verification code';
						$email_status = $this->model_api_emailapi->sendmail ( $edata );
					}
					$this->data ['facilitiess'] [] = array (
							'user_id' => $userdetail ['user_id'],
							'user_otp_id' => $user_otp_id
					)
					;
						
					$error = true;
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Incorrect user please try again"
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
					'status' => $error,
					'success' => 1
			);
				
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
				
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices resendotp ' . $e->getMessage ()
			);
			$this->model_activity_activity->addActivity ( 'resendotp', $activity_data2 );
		}
	}
	public function validateotp() {
		try {
				
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'validateotp', $this->request->post, 'request' );
				
			$this->data ['facilitiess'] = array ();
				
			$user_id = $this->request->post ['user_id'];
			$user_otp_id = $this->request->post ['user_otp_id'];
			$user_otp = $this->request->post ['user_otp'];
				
			if ($user_otp == null && $user_otp == "") {
	
				$json ['warning'] = 'Please enter User OTP';
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
			if ($user_otp_id == null && $user_otp_id == "") {
	
				$json ['warning'] = 'Please enter User';
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
				
			if ($user_id == null && $user_id == "") {
	
				$json ['warning'] = 'Please enter user';
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
	
				$this->load->model ( 'facilities/facilities' );
				$this->load->model ( 'user/user' );
				$this->load->model ( 'user/user_group' );
				$user_info = $this->model_user_user->getUser ( $user_id );
				// $userotp = $this->model_user_user->getuserOPTbyid($user_id,$user_otp_id);
	
				if (! empty ( $user_info )) {
						
					$userotpd = $this->model_user_user->checkuserOPTbyid ( $user_id, $user_otp_id, $user_otp );
					if (! empty ( $userotpd )) {
	
						$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
	
						$this->load->model ( 'api/encrypt' );
						$edevice_username = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_username' ) );
						$edevice_token = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_token' ) );
	
						$facilities_id = $user_info ['default_facilities_id'];
	
						$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						
						$this->load->model ( 'setting/timezone' );
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facility_info ['timezone_id'] );
						
						$unique_id = $facility_info ['customer_key'];
						$this->load->model ( 'customer/customer' );
						$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
						
						$client_info = unserialize ( $customer_info ['client_info_notes'] );
						$setting_data = unserialize ( $customer_info ['setting_data'] );
						
						$this->load->model ( 'notes/shift' );
						$shifs = $this->model_notes_shift->getshifts ( $facility_info ['customer_key'] );
						$shifts1 = array ();
						foreach ( $shifs as $shift ) {
							$shifts1 [] = array (
									'shift_id' => $shift ['shift_id'],
									'shift_name' => $shift ['shift_name'],
									'shift_starttime' => $shift ['shift_starttime'],
									'shift_endtime' => $shift ['shift_endtime'],
									'shift_color_value' => $shift ['shift_color_value'],
									'date_added' => $shift ['date_added']
							);
						}
						$fdata = array ();
						$authorized_key = base64_encode ( $this->generate_uuid () );
						$fdata ['facilities_id'] = $user_info ['user_id'];
						$fdata ['authorized_key'] = $authorized_key;
						$fdata ['activationKey'] = $user_info ['activationKey'];
						$fdata ['session_id'] = $user_info ['facilities'];
						// $this->model_user_user->insertUsertoken($fdata);
	
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
								'device_username' => $edevice_username,
								'device_token' => $edevice_token,
								'authorized_key' => $authorized_key,
								
								'facility' => $facility_info ['facility'],
								'timezone_value' => $timezone_info ['timezone_value'],
								'facilities_id' => $facility_info ['facilities_id'],
								'face_similar_percent' => $facility_info ['face_similar_percent'],
								'allow_face_without_verified' => $facility_info ['allow_face_without_verified'],
								'allow_quick_save' => $facility_info ['allow_quick_save'],
								'display_attchament' => $facility_info ['display_attchament'],
								'config_face_recognition' => $this->config->get ( 'config_face_recognition' ),
								'active_notification' => $this->config->get ( 'active_notification' ),
								
								'is_android_notification' => $facility_info ['is_android_notification'],
								
								'is_required_activenote' => $facility_info ['is_required_activenote'],
								'config_inventory_allow' => $facility_info ['config_inventory_allow'],
								'no_distribution' => $facility_info ['no_distribution'],
								'facility_type' => $facility_info ['facility_type'],
								'enable_facilityinout' => $facility_info ['enable_facilityinout'],
								'enable_escorted' => $facility_info ['enable_escorted'],
								'required_escorted' => $facility_info ['required_escorted'],
								'approval_required' => $facility_info ['approval_required'],
								'shifts' => $shifts1,
								'client_info' => $client_info,
								'setting_data' => $setting_data,
								'show_case' => $customer_info ['show_case'],
								'show_task' => $customer_info ['show_task'],
								'show_form_tag' => $customer_info ['show_form_tag'],
								
								'facility_setting' => unserialize ( $facility_info ['setting_data'] ),
						)
						;
	
						$error = true;
					} else {
						$this->data ['facilitiess'] [] = array (
								'warning' => "Please enter valid OTP"
						);
						$error = false;
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Incorrect user please try again"
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
					'status' => $error,
					'success' => 1
			);
				
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
				
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices validateotp ' . $e->getMessage ()
			);
			$this->model_activity_activity->addActivity ( 'validateotp', $activity_data2 );
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
							  'link' =>  $casetask['task_id'],
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
							  'parent' => $casekey['keyword_id'],
							  'link' => $casekey['keyword_id'],
							  
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
							 'link' =>  $caseform['forms_id'],
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
	
	
	
	
	public function tagcasedashboard(){
		
		
		try{
			
			$this->data['facilitiess'] = array();
			$this->load->model('notes/caseservices'); 
			$this->load->model('setting/tags');
			
			
		/*Cases*/
		
			
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		date_default_timezone_set($timezone_info['timezone_value']);
		
		
		
		
		
		$data = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 'all',
		
		);
		
		

		$totalnotes = $this->model_notes_caseservices->totalNotes($data);
		$totalforms = $this->model_notes_caseservices->totalForms($data);
		$totalkeywords = $this->model_notes_caseservices->totalKeywords($data);
		
	
		
		$data1 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 37,
		);
		$emegencyDrill = $this->model_notes_caseservices->totalKeywords($data1);
		
		
		
		
		$data2 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 77,
		);
		$bedCheck = $this->model_notes_caseservices->totalKeywords($data2);
		
		
		
		$data3 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 106,
		);
		$wellbeing = $this->model_notes_caseservices->totalKeywords($data3);
		
		
		$data3 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 111,
		);
		$firewatch = $this->model_notes_caseservices->totalKeywords($data3);
		
		$data3 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 125,
		);
		$sucidewatch = $this->model_notes_caseservices->totalKeywords($data3);
		
		
		
		
		
		$data4 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 37,
		);
		$rounds = $this->model_notes_caseservices->totalKeywords($data4);
		
		
		$data5 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 172,
		);
		$classificationrounds = $this->model_notes_caseservices->totalKeywords($data5);
		
		
		$data6 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 155,
		);
		$incident = $this->model_notes_caseservices->totalKeywords($data6);
		
		$data6 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 74,
		);
		$rounds = $this->model_notes_caseservices->totalKeywords($data6);
		
		
		$data7 = array(
		'emp_tag_id'=>$this->request->post['tags_id'],
		'facilities_id'=>$this->request->post['facilities_id'],
		'customer_key'=>$this->request->post['customer_key'],
		'note_date_from'=>$this->request->post['note_date_from'],
		'note_date_to'=>$this->request->post['note_date_to'],
		'activenote' => 66,
		);
		$bakeAct = $this->model_notes_caseservices->totalKeywords($data7);
		
		
		$this->data['facilitiess'][] = array(
			'totalnotes'=>$totalnotes,
			'totalforms'=>$totalforms,
			'totalkeywords'=>$totalkeywords,
			'emegencyDrill'=>$emegencyDrill,
			'sucidewatch'=>$sucidewatch,
			'firewatch'=>$firewatch,
			'wellbeing'=>$wellbeing,
			'bedCheck'=>$bedCheck,
			'rounds'=>$rounds,
			'classificationrounds'=>$classificationrounds,
			'incident'=>$incident,
			'rounds'=>$rounds,
			'bakeAct'=>$bakeAct,
		
		);
		
		$value = array('results'=>$this->data['facilitiess']);
		
		$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
		
		
	
	}
	
	
	
	public function activenote(){
		
		try{
			
			$this->data['facilitiess'] = array();
			$this->load->model('notes/caseservices'); 
			$this->load->model('setting/tags');
		
			$this->load->model('facilities/facilities');
			$this->load->model('setting/timezone');
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			date_default_timezone_set($timezone_info['timezone_value']);
		
		
			$unique_id = $facilities_info ['customer_key'];
		
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$activecustomer_id = $customer_info['activecustomer_id'];
			
		
			$data = array(
			'emp_tag_id'=>$this->request->post['tags_id'],
			'facilities_id'=>$this->request->post['facilities_id'],
			'note_date_from'=>$this->request->post['note_date_from'],
			'note_date_to'=>$this->request->post['note_date_to'],
			'customer_key'=> $activecustomer_id,
			);

			$allkeywords = $this->model_notes_caseservices->allKeywordsbyTotal($data);
				$keyname=array();
				$keycount=array();
				$keycolrcodes=array();
				
				foreach($allkeywords as $keyword){
					$keyname[] = $keyword['keyword_name'];
					$keycount[] = $keyword['keywordCount'];
				}
				
				$keynames21 = implode(',',$keyname);
				$keynamescount = implode(',',$keycount);

			$this->data['facilitiess'][] = array(
				'allkeywords'=>$allkeywords,
				'allkeynames'=>$keynames21,
				'allkeycounts'=>$keynamescount,
				'allcolorcode'=>['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de','#d2d6e']
				
				
			);
			
			$value = array('results'=>$this->data['facilitiess']);
			
			$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
		
		
	
	}
	
	public function barChartbyTotals(){
		
		
		try{
			
			$this->data['facilitiess'] = array();
			$this->load->model('notes/caseservices'); 
			$this->load->model('setting/tags');
		
			$this->load->model('facilities/facilities');
			$this->load->model('setting/timezone');
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			date_default_timezone_set($timezone_info['timezone_value']);
		
			$data = array(
			'emp_tag_id'=>$this->request->post['tags_id'],
			'facilities_id'=>$this->request->post['facilities_id'],
			'note_date_from'=>$this->request->post['note_date_from'],
			'note_date_to'=>$this->request->post['note_date_to'],
			'customer_key'=> $activecustomer_id,
			);
			
		

			$allnotes = $this->model_notes_caseservices->BarChartbyTotal($data);
					
			$this->data['facilitiess'] = array(
				'allnotes'=>$allnotes,
			);
			/*
			
			$this->data['facilitiess'] = array(
				[
				  'label'             => 'Digital Goods',
				  'backgroundColor'    => 'rgba(60,141,188,0.9)',
				  'backgroundColor'   =>'rgba(60,141,188,0.8)',
				  'backgroundColor'    => false,
				  'pointColor'          => '#3b8bba',
				  'pointStrokeColor'    => 'rgba(60,141,188,1)',
				  'pointStrokeColor'  => '#fff',
				  'pointHighlightStroke'=> 'rgba(60,141,188,1)',
				  'pointHighlightStroke'   => [28, 48, 40, 19, 86, 27, 90]
				],
				[
				  'label'               => 'Electronics',
				   'backgroundColor'     => 'rgba(210, 214, 222, 1)',
				  'borderColor '       => 'rgba(210, 214, 222, 1)',
				  'pointRadius'        => false,
				  'pointColor'          => 'rgba(210, 214, 222, 1)',
				  'pointStrokeColor '  => '#c1c7d1',
				  'pointHighlightFill ' => '#fff',
				  'pointHighlightStroke'=> 'rgba(220,220,220,1)',
				  'data'     => [65, 59, 80, 81, 56, 55, 40]
				],
			  );
			  */
	  
			
			//var_dump($this->data['facilitiess']);
			
			
			
			$value = array('results'=>$this->data['facilitiess']);
			
			$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
		
		}
		
	public function getClientcustomform(){
		
			try{
				
			
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'getClientcustomform', $this->request->post, 'request' );
				
			$this->data['facilitiess'] = array();
			$this->load->model('facilities/facilities');
			$this->load->model('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('api/encrypt');
			$cre_array = array();

			//$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			//$cre_array['facilities_id'] = $this->request->post['facilities_id'];
			
		
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->post['facilities_id'] );
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
			
		
			if(!empty($this->request->post['forms_design_id'])){
				
			
				$this->load->model('form/form');
				$fromdatas = $this->model_form_form->getFormdata($this->request->post['forms_design_id']);
				
				$dtnnotess = array ();
				if ($fromdatas ['db_table_name'] == 'clienttable') {
					
					$cffdata = array (
							'status' => 1,
							'discharge' => 1,
							'role_call' => '1',
							'sort' => 'emp_first_name',
							// 'searchdate' => $searchdate,
							'facilities_id' => $search_facilities_id1,
							'emp_tag_id' => '',
							'all_record' => '1' 
					);
					$tnnotes = $this->model_setting_tags->getTags ( $cffdata );
					
					// var_dump($tnnotes);
					foreach ( $tnnotes as $stag ) {
						$result_info = $this->model_facilities_facilities->getfacilities ( $stag ['facilities_id'] );
						$dtnnotess [] = array (
								'name' => $stag ['emp_first_name'] . ' ' . $stag ['emp_last_name'],
								'facilities_id' => $result_info ['facility'],
								'emp_first_name' => $stag ['emp_first_name'],
								'emp_last_name' => $stag ['emp_last_name'],
								'emp_tag_id' => $stag ['emp_tag_id'],
								'tags_id' => $stag ['tags_id'],
								'gender' => $stag ['gender'],
								'emp_extid' => $stag ['emp_extid'],
								'emergency_contact' => $stag ['emergency_contact'],
								'location_address' => $stag ['location_address'],
								'ssn' => $stag ['ssn'],
								'note_date' => date ( $timeinfo ['date_format'], strtotime ( $stag ['note_date'] ) ) 
						);
					}
				}
				
				$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
				
				$this->data['facilitiess'] = array(
					
					'fields' => $fromdatas['forms_fields'],
					'form_name' => $fromdatas['form_name'],
					'display_image' => $fromdatas['display_image'],
					'display_signature' => $fromdatas['display_signature'],
					'forms_setting' => $fromdatas['forms_setting'],
					'display_add_row' => $fromdatas['display_add_row'],
					'display_content_postion' => $fromdatas['display_content_postion'],
					'is_client_active' => $fromdatas['is_client_active'],
					'form_type' => $fromdatas['form_type'],
					'db_table_name' => $fromdatas['db_table_name'],
					'client_reqired' => $fromdatas['client_reqired'],
					'ddd' => $fromdatas['client_reqired'],
					'link_form_fieldall' => $fromdatas['link_form_fieldall'],
					'dtnnotess' => $dtnnotess,
					'customlists' => $this->data ['customlists'],
					
					
				);
				$error = true;
			
			}else{
				
				$this->data['facilitiess'][] = array(
					'warning'  => "Form not found",
				);
				$error = false;
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
				
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices getClientcustomform '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_getClientcustomform', $activity_data2);
		
		
		
		}   
		
	}
	
	public function getFormInventory(){
		
			try{
				
			
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'getFormInventory', $this->request->post, 'request' );
				
			$this->data['facilitiess'] = array();
			$this->load->model('facilities/facilities');
			$this->load->model('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('api/encrypt');
			$cre_array = array();

			//$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			//$cre_array['facilities_id'] = $this->request->post['facilities_id'];
			
			$this->load->model('inventory/inventory');
            $results = $this->model_inventory_inventory->getFormInventory(); 
				
					  
		
			if(!empty($results)){
				
				foreach($results as $result){
					
					$this->data['facilitiess'][] = array(
						
						'inventory_id' => $result['inventory_id'],
						'name' => $result['name'],
						'inventorytype_id' => $result['inventorytype_id'],
						'description' => $result['description'],
						'maintenance' => $result['maintenance'],
						'date_added' => $result['date_added'],
						'measurement_type' => $result['measurement_type'],
						'quantity' => $result['quantity'],
						
					);
				}
				$error = true;
			
			}else{
				
				$this->data['facilitiess'][] = array(
					'warning'  => "Form not found",
				);
				$error = false;
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
				
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices getClientcustomform '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_getClientcustomform', $activity_data2);
		
		
		
		}   
		
	}
		
	public function getfacilitiess(){
		
			try{
				
			
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'getfacilitiess', $this->request->post, 'request' );
				
			$this->data['facilitiess'] = array();
			$this->load->model('facilities/facilities');
			$this->load->model('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('api/encrypt');
			$cre_array = array();

			//$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			//$cre_array['facilities_id'] = $this->request->post['facilities_id'];
			
			$facilities_id = $this->request->post['facilities_id'];
		
			$this->load->model ( 'facilities/facilities' );
			
			$s = array ();
			$s ['facilities_id'] = $facilities_id;
			$s ['sbfacility'] = 1;
			
			
			$results = $this->model_facilities_facilities->getfacilitiess ( $s );
			
		
			
			if(!empty($results)){
				
				foreach($results as $result){
					
			
			
			
					
					$this->data['facilitiess'][] = array(
						
						'facilities_id' => $result['facilities_id'],
						'facility' => $result['facility'],
						'firstname' => $result['firstname'],
						'lastname' => $result['lastname'],
						'customlists' => $this->data ['customlists'],
						
						
					);
				}
				$error = true;
			
			}else{
				
				$this->data['facilitiess'][] = array(
					'warning'  => "Form not found",
				);
				$error = false;
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
				
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices getClientcustomform '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_getClientcustomform', $activity_data2);
		
		
		
		}   
		
	}
	
	public function getcustomlists(){
		
			try{
				
			
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'getcustomlists', $this->request->post, 'request' );
				
			$this->data['facilitiess'] = array();
			$this->load->model('facilities/facilities');
			$this->load->model('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('api/encrypt');
			$cre_array = array();

			//$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			//$cre_array['facilities_id'] = $this->request->post['facilities_id'];
			$this->load->model ( 'facilities/facilities' );
			$facilities_id = $this->request->post['facilities_id'];
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			
				if ($facilityinfo ['config_tags_customlist_id'] != NULL && $facilityinfo ['config_tags_customlist_id'] != "") {
					
					$d = array ();
					$d ['customlist_id'] = $facilityinfo ['config_tags_customlist_id'];
					$customlists = $this->model_notes_notes->getcustomlists ( $d );
					
					if ($customlists) {
						foreach ( $customlists as $customlist ) {
							$d2 = array ();
							$d2 ['customlist_id'] = $customlist ['customlist_id'];
							
							$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
							
							$this->data ['facilitiess'] [] = array (
									'customlist_id' => $customlist ['customlist_id'],
									'customlist_name' => $customlist ['customlist_name'],
									'customlistvalues' => $customlistvalues 
							);
						}
						$error = true;
					}
					
					
					
				}else{
				
				$this->data['facilitiess'][] = array(
					'warning'  => "Facility found",
				);
				$error = false;
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
				
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices getClientcustomform '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_getClientcustomform', $activity_data2);
		
		
		
		}   
		
	}

	public function getshift(){
		
		try{
			
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'getshift', $this->request->post, 'request' );
				
			$this->data['facilitiess'] = array();
			$this->load->model('facilities/facilities');
			$this->load->model('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('api/encrypt');
			$cre_array = array();
			
			
			
		
			$this->load->model ( 'facilities/facilities' );
			$facilities_id = $this->request->post['facilities_id'];
			
			//$facility_info = $this->model_facilities_facilities->getfacilitiesByfacility ( $facilities_id );
			
			$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			
			$this->load->model ( 'api/permision' );
				$current_permission = $this->model_api_permision->getpermision ( $facility_info ['facilities_id'] );
				
				$this->load->model ( 'customer/apiurl' );
				$apiurls = $this->model_customer_apiurl->getapiurls ( $facility_info ['customer_key'] );
				$apiurls1 = array ();
				foreach ( $apiurls as $apiurl ) {
					$apiurls1 [] = array (
							'keyname' => $apiurl ['keyname'],
							'api_full_url' => $apiurl ['api_full_url'] 
					);
				}
				
				$unique_id = $facility_info ['customer_key'];
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				$client_info = unserialize($customer_info['client_info_notes']);
				$setting_data = unserialize($customer_info['setting_data']);
				
				$this->load->model ( 'notes/shift' );
				$shifs = $this->model_notes_shift->getshifts ( $facility_info ['customer_key'] );
				$shifts1 = array ();
				
				if($shifs){
				foreach ( $shifs as $shift ) {
					$this->data['facilitiess'][] = array (
							'shift_id' => $shift ['shift_id'],
							'shift_name' => $shift ['shift_name'], 
							'shift_starttime' => $shift ['shift_starttime'], 
							'shift_endtime' => $shift ['shift_endtime'], 
							'shift_color_value' => $shift ['shift_color_value'], 
							'date_added' => $shift ['date_added'], 
					);
				}
					$error = true;
				}else{
					$error = false;
				}
				
				
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
				
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices getClientcustomform '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_getClientcustomform', $activity_data2);
		
		
		
		} 
		
	}


	public function getautocomplete3 ()
    {
        
        
			$this->data['facilitiess'] = array();
        
        
            $this->load->model('setting/tags');
            $this->load->model('form/form');
            
            // $filter_name = str_replace('___-__-____', ' ',
            // $this->request->get['filter_name']);
            
            if ($this->request->post['facilities_id'] != '' && $this->request->post['facilities_id'] != null) {
                $facilities_id = $this->request->post['facilities_id'];
            } else {
                $facilities_id = $this->customer->getId();
            }
			
			if ($this->request->post['allclients'] == '1') {
                $discharge = '2';
                $is_master = '1';
            }
            
            $data = array(
                    //'emp_tag_id_all' => $this->request->post['filter_name'],
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'discharge' => $discharge,
					'is_master' => $is_master,
                    'sort' => 'emp_tag_id',
                    'order' => 'ASC',
                   // 'start' => 0,
                   // 'limit' => CONFIG_LIMIT
            );
            
            $results = $this->model_setting_tags->getTags($data);
              
			
			$this->load->model('setting/locations');       
			$this->load->model('resident/resident');       
			$this->load->model('notes/clientstatus');    
			$this->load->model ( 'api/permision' );
			
			if($results){
			
			
            foreach ($results as $result) {
               
				$addtags_info = $this->model_form_form->gettagsforma($result['tags_id']);
				$url22 = "";
				if(!empty($addtags_info)){
					$url22 .= '&forms_id=' . $addtags_info['forms_id'];
					$url22 .= '&forms_design_id=' . $addtags_info['custom_form_type'];
					$url22 .= '&tags_id=' . $addtags_info['tags_id'];
					$url22 .= '&notes_id=' . $addtags_info['notes_id'];
					$url2 .= '&facilities_id=' . $addtags_info['facilities_id'];
					$action211 = str_replace('&amp;', '&', $this->url->link('form/form/edit', '' . $url22, 'SSL'));
				}else{
					$action211 = "";
				}
					// $tag_info =
					// $this->model_setting_tags->getTag($result['tags_id']);
					
					if ($result['date_of_screening'] != "0000-00-00") {
						$date_of_screening = date('m-d-Y', strtotime($result['date_of_screening']));
					} else {
						$date_of_screening = date('m-d-Y');
					}
					if ($result['dob'] != "0000-00-00") {
						$dob = date('m-d-Y', strtotime($result['dob']));
					} else {
						$dob = '';
					}
					
					if ($result['dob'] != "0000-00-00") {
						$dobm = date('m', strtotime($result['dob']));
					} else {
						$dobm = '';
					}
					if ($result['dob'] != "0000-00-00") {
						$dobd = date('d', strtotime($result['dob']));
					} else {
						$dobd = '';
					}
					if ($result['dob'] != "0000-00-00") {
						$doby = date('Y', strtotime($result['dob']));
					} else {
						$doby = '';
					}
					
					/*if ($result['gender'] == '1') {
						$gender = '33';
					}
					if ($result['gender'] == '2') {
						$gender = '34';
					}*/
					
					$get_img = $this->model_setting_tags->getImage($result['tags_id']);			
			
					if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
						$upload_file_thumb_1 = $get_img['upload_file_thumb'];
					} else {
						$upload_file_thumb_1 = $get_img['enroll_image'];
					}
					
					if ($result['ssn']) {
						$ssn = $result['ssn'] . ' ';
					} else {
						$ssn = '';
					}
					if ($result['emp_extid']) {
						$emp_extid = $result['emp_extid'] . ' ';
					} else {
						$emp_extid = '';
					}
					
					
					$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($result['tags_id']);
			
					if($tagstatusinfo !=NULL && $tagstatusinfo !=""){
						$status = $tagstatusinfo['status'];
						
						$classification_value = $this->model_resident_resident->getClassificationValue ($tagstatusinfo['status']); 
						$classification_name = $classification_value['classification_name'];
					}else{
						$classification_name = '';
					}
					
					$clientstatus_info = $this->model_notes_clientstatus->getclientstatus($result['role_call']);
					if($clientstatus_info['name'] != null && $clientstatus_info['name'] != ""){
						$role_callname = $clientstatus_info['name'];
						$color_code = $clientstatus_info['color_code'];
						$role_type = $clientstatus_info['type'];
					}
					if($result['room'] != null && $result['room'] != ""){
						$rresults = $this->model_setting_locations->getlocation($result['room']);
						$location_name = $rresults['location_name'];
					}else{
						$location_name = '';
					}
					
					$clientinfo = $this->model_api_permision->getclientinfo ( $result['facilities_id'], $result );
					
					$this->data['facilitiess'][] = array(
							'tags_id' => $result['tags_id'],
							//'fullname' => $result['emp_last_name'] . ' ' . $result['emp_first_name'],
							'fullname' => $clientinfo ['name'],
							'classification_name' => $classification_name,
							'role_call' => $role_callname,
							'location_name' => $location_name,
							'emp_tag_id' => $result['emp_tag_id'],
							'emp_first_name' => $result['emp_first_name'],
							'emp_middle_name' => $result['emp_middle_name'],
							'emp_last_name' => $result['emp_last_name'],
							'location_address' => $result['location_address'],
							'discharge' => $result['discharge'],
							'bed_number' => $result ['bed_number'],
							'age' => $result['age'],
							'dob' => $dob,
							'month' => $dobm,
							'date' => $dobd,
							'year' => $doby,
							'medication' => $result['medication'],
							// 'gender'=> $result['gender'],
							'gender' => $result['customlistvalues_id'],
							'person_screening' => $result['person_screening'],
							'date_of_screening' => $date_of_screening,
							'ssn' => $result['ssn'],
							'state' => $result['state'],
							'city' => $result['city'],
							'zipcode' => $result['zipcode'],
							'room' => $result['room'],
							'restriction_notes' => $result['restriction_notes'],
							'prescription' => $result['prescription'],
							'constant_sight' => $result['constant_sight'],
							'alert_info' => $result['alert_info'],
							'med_mental_health' => $result['med_mental_health'],
							'tagstatus' => $result['tagstatus'],
							'emp_extid' => $result['emp_extid'],
							'stickynote' => $result['stickynote'],
							'referred_facility' => $result['referred_facility'],
							'emergency_contact' => $result['emergency_contact'],
							'date_added' => date('m-d-Y', strtotime($result['date_added'])),
							'upload_file' => $upload_file_thumb_1,
							'image_url1' => $upload_file_thumb_1,
							'screening_update_url' => $action211
					);
				
            }
			$error = true;
		}else{
			$error = false;
			}
        
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
    }
	
	public function medicineautocomplete() {
		
		
		if (utf8_strlen ( $this->request->post ['medicine_filter_name'] ) > 3) {
			
			// $medicineUrl =
			// 'https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search='.$this->request->get['medicine_filter_name'].'&limit=1';Albuterol%20Sulfate
			// $json_url =
			// "https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search=brand_name:".$this->request->get['medicine_filter_name'];
			$json_url = "https://dailymed.nlm.nih.gov/dailymed/autocomplete.cfm?key=search&returntype=json&term=" . $this->request->post ['medicine_filter_name'];
			$json = file_get_contents ( $json_url );
			$data = json_decode ( $json, TRUE );
			// echo "<pre>";
			//print_r($data);
			// echo "</pre>";
			
			$this->data['facilitiess'] = array ();
			if($data){
				
			foreach ( $data as $obj ) {
				foreach ( $obj as $a ) {
					$this->data['facilitiess'][] = array (
							'brand_name' => $a 
					);
				}
			}
				$error=true;
			}else{
				$error=false;
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
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		return;
	}
	
	
	public function autocompleteroom ()
    {
        
        
        $this->data['facilitiess'] = array ();
        
        if ($this->request->post['facilities_id'] != '' && $this->request->post['facilities_id'] != null) {
            $facilities_id = $this->request->post['facilities_id'];
        } else {
            $facilities_id = 0;
        }
        
        //if (isset($this->request->get['filter_name'])) {
            $this->load->model('setting/locations');
            $data = array(
                   // 'location_name' => $this->request->get['filter_name'],
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'sort' => 'task_form_name',
                    'order' => 'ASC',
                   
            );
            
            $results = $this->model_setting_locations->getlocations($data);
            
			if($results){
				
            $this->data['facilitiess'][] = array(
                    'locations_id' => '0',
                    'location_name' => '-None-'
            );
            
            foreach ($results as $result) {
                
               $this->data['facilitiess'][] = array(
                        'locations_id' => $result['locations_id'],
                        'location_name' => $result['location_name'],
                        'date_added' => $result['date_added']
                );
            }
				$error=true;
			}else{
				$error=false;
				
			}
        //}
        $value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		return;
    }
	
	public function getcustomlistvalue ()
    {
        $this->data['facilitiess'] = array ();
        $this->load->model('notes/notes');
		$results = $this->model_notes_notes->getcustomlistvalue($this->request->post['customlistvalues_id']);
			if($results){
				   $this->data['facilitiess'][] = array(
							'customlistvalues_id' => $results['customlistvalues_id'],
							'customlistvalues_name' => $results['customlistvalues_name'],
							'customlist_id' => $results['customlist_id']
					);
				
				$error=true;
			}else{
				$error=false;
				
			}
        //}
        $value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		return;
    }
	
	
	public function getcustomlist_id ()
    {
        
        $this->data['facilitiess'] = array ();
        $this->load->model('notes/notes');
		
		$customlist_info = $this->model_notes_notes->getcustomlist($this->request->post['getcustomlist_id']);
											
											
		$d = array();
		$d['customlist_id'] = $customlist_info['customlist_id'];
		$results = $this->model_notes_notes->getcustomlistvalues($d);
		
		
			if($results){
				
					foreach($results as $result){
						$this->data['facilitiess'][] = array(
							'customlistvalues_id' => $results['customlistvalues_id'],
							'customlistvalues_name' => $results['customlistvalues_name'],
							'customlist_id' => $results['customlist_id'],
							'number' => $results['number']
					);
				
				}
				
				$error=true;
			}else{
				$error=false;
				
			}
        //}
        $value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		return;
    }
	
	
	
	  public function addforms() {
    	try {
    			
    		$this->load->model ( 'activity/activity' );
    		$this->model_activity_activity->addActivitySave ( 'addforms', $this->request->post, 'request' );
    			
    		$this->data ['facilitiess'] = array ();
    		$this->load->model ( 'facilities/facilities' );
    		$this->load->model ( 'notes/notes' );
    		$this->load->model ( 'setting/tags' );
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
    			
    		if ($this->request->post ['facilities_id'] == null && $this->request->post ['facilities_id'] == "") {
    			$json ['warning'] = 'Warning: Enter Facilities Id';
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
    			
    		if ($json ['warning'] == null && $json ['warning'] == "") {
    			$facilities_id = $this->request->post ['facilities_id'];
	    			$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
	    				
	    			$this->load->model ( 'setting/timezone' );
	    				
	    			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
	    				
	    			date_default_timezone_set ( $timezone_info ['timezone_value'] );
    				
    				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
    				$date_added = ( string ) $noteDate;
    					
    				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
    					
    				$this->load->model ( 'setting/tags' );
    				$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
    					
    				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
    				$data ['tags_id'] = $tag_info ['tags_id'];
    					
    				
    					
    				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
    					$comments = ' | ' . $this->request->post ['comments'];
    				}
    					
    				
    				
    				
    					
    				
    				$data ['date_added'] = $date_added;
    				$data ['note_date'] = $date_added;
    				$data ['notetime'] = $notetime;
    					
    				if ($this->request->post ['is_web'] == '1') {
    					if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
    						$data ['imgOutput'] = urldecode ( $this->request->post ['signature'] );
    					}
    				} else {
    					if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
    						$data ['imgOutput'] = $this->request->post ['signature'];
    					}
    				}
    				
    				
    				$parent_id = $this->request->post ['forms_design_id'];
    				$this->load->model ( 'form/form' );
    				$formdata_i = $this->model_form_form->getFormDatadesign ( $this->request->post ['forms_design_id'] );
    					
    				$data2 = array ();
    				$data2 ['forms_design_id'] = $this->request->post ['forms_design_id'];
    				$data2 ['iframevalue'] = $this->request->post ['iframevalue'];
    				$data2 ['form_design_parent_id'] = $formdata_i ['parent_id'];
    				$data2 ['page_number'] = $formdata_i ['page_number'];
    				$data2 ['form_parent_id'] = '0';
    					
    					
    				$data2 ['facilities_id'] = $this->request->post ['facilities_id'];
    					
    				$pformreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
    				
    				
    				$data ['notes_description'] = 'Form ' . $formdata_i ['form_name'] . ' has been added | ' . $description . '' . $comments ;
    					
    				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
    					
    					
    			
    
    			$this->data ['facilitiess'] [] = array (
    					'warning' => '1'
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
    				'data' => 'Error in appservices addforms ' . $e->getMessage ()
    		);
    		$this->model_activity_activity->addActivity ( 'addforms', $activity_data2 );
    	}
    }
	
	
	public function jsonUsers() {
		try {
			$this->data ['facilitiess'] = array ();
			
			/*
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			*/
			
			if($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != ""){
				$config_admin_limit = $this->config->get ( 'all_sync_pagination' );
			}else{
				$config_admin_limit = "50";
			}
			
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			} else {
				$page = 1;
			}
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'user/user' );
				
				$this->load->model('facilities/facilities');
			
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
						
				$this->load->model('setting/timezone');
						
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				$facilitytimezone = $timezone_info['timezone_value'];
				
				$current_date_user =  date('Y-m-d');
				
				$data2 = array();
				$data2['facilities_id'] = $this->request->post['facilities_id'];  
				$data2['type'] = '';  
				$data2['start'] = ($page - 1) * $config_admin_limit;  
				$data2['limit'] = $config_admin_limit;
				$data2['app_user_date'] = $this->request->post['date_added'];
				$data2['current_date_user'] = $current_date_user;
				$data2['is_master'] = 1;
				$data2['is_submaster'] = 1;
				
				//var_dump($data2);
				
				$all_total = $this->model_user_user->getTotalusersapp($this->request->post ['facilities_id'], $this->request->post ['allusers'], $data2);
				
				$users = $this->model_user_user->getUsersByFacilityapp ( $this->request->post ['facilities_id'], $this->request->post ['allusers'],$data2 );
				
				
				
				if (! empty ( $users )) {
					foreach ( $users as $user ) {
						
						$this->data ['facilitiess'] [] = array (
								'user_id' => $user ['user_id'],
								'username' => $user ['username'],
								'firstname' => $user ['firstname'],
								'lastname' => $user ['lastname'],
								'user_pin' => $user ['user_pin'],
								'facilities' => $user ['facilities'],
								'phone_number' => $user ['phone_number'],
								'email' => $user ['email'],
								'status' => $user ['status'],
								'user_group_id' => $user ['user_group_id'] 
						);
					}
					$value = array (
							'results' => $this->data ['facilitiess'],
							'all_total' => $all_total,
							'status' => true 
					);
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Users not found, please contact support" 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'all_total' => $all_total,
							'status' => $error 
					);
					
					$this->response->setOutput ( json_encode ( $value ) );
				}
			} else {
				$error = false;
				$value = array (
						'results' => "No User Found",
						'all_total' => "0",
						'status' => false 
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonUsers ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonUsers', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	
	
}
 
 
		 