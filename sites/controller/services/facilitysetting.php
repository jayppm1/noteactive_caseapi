<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllerservicesfacilitysetting extends Controller {
	public function index() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'facilitysettingindex', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			
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
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'facilities/facilities' );
				$facility_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				
				if ($facility_info) {
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facility_info ['timezone_id'] );
					
					if ($this->config->get ( 'config_date_picker' ) != null && $this->config->get ( 'config_date_picker' ) != "") {
						$config_date_picker = $this->config->get ( 'config_date_picker' );
					} else {
						$config_date_picker = '0';
					}
					
					if ($this->config->get ( 'config_time_picker' ) != null && $this->config->get ( 'config_time_picker' ) != "") {
						$config_time_picker = $this->config->get ( 'config_time_picker' );
					} else {
						$config_time_picker = '0';
					}
					
					/*
					 * if($this->config->get('config_task_status') != null && $this->config->get('config_task_status') != ""){
					 * $config_task_status = $this->config->get('config_task_status');
					 * }else{
					 * $config_task_status = '0';
					 * }
					 */
					
					$config_admin_limit1 = $this->config->get ( 'config_android_front_limit' );
					if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
						$config_admin_limit = $config_admin_limit1;
					} else {
						$config_admin_limit = "25";
					}
					
					$error_config_android_front_limit1 = $this->config->get ( 'config_android_front_limit' );
					if ($error_config_android_front_limit1 != null && $error_config_android_front_limit1 != "") {
						$config_android_front_limit = $error_config_android_front_limit1;
					} else {
						$config_android_front_limit = "25";
					}
					
					$mobile_db_page_limit1 = $this->config->get ( 'mobile_db_page_limit' );
					if ($mobile_db_page_limit1 != null && $mobile_db_page_limit1 != "") {
						$mobile_db_page_limit = $mobile_db_page_limit1;
					} else {
						$mobile_db_page_limit = "20";
					}
					$mobile_list_view_page_limit1 = $this->config->get ( 'mobile_list_view_page_limit' );
					if ($mobile_list_view_page_limit1 != null && $mobile_list_view_page_limit1 != "") {
						$mobile_list_view_page_limit = $mobile_list_view_page_limit1;
					} else {
						$mobile_list_view_page_limit = "25";
					}
					
					if ($this->request->post ['searchdate'] != null && $this->request->post ['searchdate'] != "") {
						$searchdate = date ( 'Y-m-d', strtotime ( $this->request->post ['searchdate'] ) );
					}
					
					$data = array (
							'facilities_id' => $facility_info ['facilities_id'],
							'searchdate' => $searchdate,
							'advance_search' => '1' 
					);
					
					$this->load->model ( 'notes/notes' );
					$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
					
					if ($this->config->get ( 'config_secure' ) == "1") {
						$configUrl = '1';
						$configUrl2 = $this->config->get ( 'config_ssl' ) . 'index.php?route=services/';
					} else {
						$configUrl = '0';
						$configUrl2 = $this->config->get ( 'config_url' ) . 'index.php?route=services/';
					}
					
					$notes_total2 = ceil ( $notes_total / $config_admin_limit );
					
					if ($this->config->get ( 'config_all_notification' ) != null && $this->config->get ( 'config_all_notification' ) != "") {
						$config_all_notification = $this->config->get ( 'config_all_notification' );
					} else {
						$config_all_notification = '0';
					}
					
					if ($this->config->get ( 'config_task_sms' ) != null && $this->config->get ( 'config_task_sms' ) != "") {
						$config_task_sms = $this->config->get ( 'config_task_sms' );
					} else {
						$config_task_sms = '0';
					}
					
					if ($this->config->get ( 'config_task_notification' ) != null && $this->config->get ( 'config_task_notification' ) != "") {
						$config_task_notification = $this->config->get ( 'config_task_notification' );
					} else {
						$config_task_notification = '0';
					}
					
					if ($this->config->get ( 'config_task_email' ) != null && $this->config->get ( 'config_task_email' ) != "") {
						$config_task_email = $this->config->get ( 'config_task_email' );
					} else {
						$config_task_email = '0';
					}
					
					if ($this->config->get ( 'config_task_deleted_time' ) != null && $this->config->get ( 'config_task_deleted_time' ) != "") {
						$config_task_deleted_time = $this->config->get ( 'config_task_deleted_time' );
					} else {
						$config_task_deleted_time = '0';
					}
					
					if ($facility_info ['config_taskform_status'] != NULL && $facility_info ['config_taskform_status'] != "") {
						$taskstatus = $facility_info ['config_taskform_status'];
					} else {
						$taskstatus = '0';
					}
					
					if ($facility_info ['config_noteform_status'] != NULL && $facility_info ['config_noteform_status'] != "") {
						$noteformtatus = $facility_info ['config_noteform_status'];
					} else {
						$noteformtatus = '0';
					}
					
					if ($facility_info ['config_rules_status'] != NULL && $facility_info ['config_rules_status'] != "") {
						$ruletatus = $facility_info ['config_rules_status'];
					} else {
						$ruletatus = '0';
					}
					
					if ($facility_info ['config_display_camera'] != NULL && $facility_info ['config_display_camera'] != "") {
						$config_display_camera = $facility_info ['config_display_camera'];
					} else {
						$config_display_camera = '0';
					}
					
					if ($this->config->get ( 'config_transcription' ) != null && $this->config->get ( 'config_transcription' ) != "") {
						$config_transcription = $this->config->get ( 'config_transcription' );
					} else {
						$config_transcription = '0';
					}
					
					if ($facility_info ['config_share_notes'] != NULL && $facility_info ['config_share_notes'] != "") {
						$config_share_notes = $facility_info ['config_share_notes'];
					} else {
						$config_share_notes = '0';
					}
					
					if ($facility_info ['config_sharepin_status'] != NULL && $facility_info ['config_sharepin_status'] != "") {
						$config_sharepin_status = $facility_info ['config_sharepin_status'];
					} else {
						$config_sharepin_status = '0';
					}
					
					if ($facility_info ['config_multiple_activenote'] != NULL && $facility_info ['config_multiple_activenote'] != "") {
						$config_multiple_activenote = $facility_info ['config_multiple_activenote'];
					} else {
						$config_multiple_activenote = '0';
					}
					
					if ($facility_info ['android_audio_file'] != NULL && $facility_info ['android_audio_file'] != "") {
						$android_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility_info ['android_audio_file'];
					} else {
						$android_audio_file = '';
					}
					
					if ($facility_info ['ios_audio_file'] != NULL && $facility_info ['ios_audio_file'] != "") {
						$ios_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility_info ['ios_audio_file'];
					} else {
						$ios_audio_file = '';
					}
					
					$this->load->model ( 'api/encrypt' );
					$edevice_username = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_username' ) );
					$edevice_token = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_token' ) );
					
					$fsql = "SELECT DISTINCT customer_key,forms_id,form_name,forms_fields FROM " . DB_PREFIX . "forms_design WHERE form_type = 'Screening' and FIND_IN_SET('" . $facility_info ['facilities_id'] . "', facilities) ";
					$queryf = $this->db->query ( $fsql );
					
					$form_info = $queryf->row;
					if ($form_info ['customer_key'] != null && $form_info ['customer_key'] != "") {
						$screening_id = $form_info ['forms_id'];
					} else {
						$screening_id = "";
					}
					
					$fsqli = "SELECT DISTINCT customer_key,forms_id,form_name,forms_fields FROM " . DB_PREFIX . "forms_design WHERE form_type = 'Intake' and FIND_IN_SET('" . $facility_info ['facilities_id'] . "', facilities) ";
					$queryfi = $this->db->query ( $fsqli );
					
					$form_intake_info = $queryfi->row;
					
					if ($form_intake_info ['customer_key'] != null && $form_intake_info ['customer_key'] != "") {
						$intake_id = $form_intake_info ['forms_id'];
						
						$add_client_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . '&addclient=1&forms_design_id=' . $intake_id . '&facilities_id=' . $facility_info ['facilities_id'], 'SSL' ) );
					} else {
						$intake_id = "";
						$add_client_url = "";
					}
					
					$this->load->model ( 'api/permision' );
					$current_permission = $this->model_api_permision->getpermision ( $facility_info ['facilities_id'] );
					
					$this->load->model ( 'customer/apiurl' );
					$apiurls = $this->model_customer_apiurl->getapiurls ( $facility_info ['customer_key'] );
					$apiurls1 = array ();
					foreach ( $apiurls as $apiurl ) {
						$apiurls1 [] = array (
								'keyname' => $apiurl ['keyname'],
								'api_full_url' => $apiurl ['api_full_url'],
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
					foreach ( $shifs as $shift ) {
						$shifts1 [] = array (
								'shift_id' => $shift ['shift_id'],
								'shift_name' => $shift ['shift_name'], 
								'shift_starttime' => $shift ['shift_starttime'], 
								'shift_endtime' => $shift ['shift_endtime'], 
								'shift_color_value' => $shift ['shift_color_value'], 
								'date_added' => $shift ['date_added'], 
						);
					}
					
					if($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != ""){
						$all_sync_pagination = $this->config->get ( 'all_sync_pagination' );
					}else{
						$all_sync_pagination = "50";
					}
					
					$subfacilities = array();
					if($facility_info ['is_master_facility'] == 1){
						
						$subfacilities [] = array (
							'facilities_id' => $facility_info ['facilities_id'],
							'is_master_facility' => $facility_info ['is_master_facility'],
							'notes_facilities_ids' => $facility_info ['notes_facilities_ids'],
							'client_facilities_ids' => $facility_info ['client_facilities_ids'],
							'enable_facilityinout' => $facility_info ['enable_facilityinout'],
						);
						
						$cids = array();
						if($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != ""){
							$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
							$abdcg = array_unique($sssssddsg);
							foreach($abdcg as $fid){
								$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid );
								$subfacilities [] = array (
									'facilities_id' => $facilityinfo ['facilities_id'],
									'is_master_facility' => $facilityinfo ['is_master_facility'],
									'notes_facilities_ids' => $facilityinfo ['notes_facilities_ids'],
									'client_facilities_ids' => $facilityinfo ['client_facilities_ids'],
									'enable_facilityinout' => $facilityinfo ['enable_facilityinout'],
								);
							}
						}
						
						if($facility_info ['notes_facilities_ids'] != null && $facility_info ['notes_facilities_ids'] != ""){
							$sssssddsg2 = explode(",",$facility_info ['notes_facilities_ids']);
							$abdcgs = array_unique($sssssddsg2);
							foreach($abdcgs as $fidd){
								$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fidd );
								$subfacilities [] = array (
									'facilities_id' => $facilityinfo ['facilities_id'],
									'is_master_facility' => $facilityinfo ['is_master_facility'],
									'notes_facilities_ids' => $facilityinfo ['notes_facilities_ids'],
									'client_facilities_ids' => $facilityinfo ['client_facilities_ids'],
									'enable_facilityinout' => $facilityinfo ['enable_facilityinout'],
								);
							}
						}
						
						
					}
					
					$this->data ['facilitiess'] [] = array (
							'facility' => $facility_info ['facility'],
							'timezone_value' => $timezone_info ['timezone_value'],
							'facilities_id' => $facility_info ['facilities_id'],
							'config_display_camera' => $config_display_camera,
							'config_share_notes' => $config_share_notes,
							'config_sharepin_status' => $config_sharepin_status,
							'config_multiple_activenote' => $config_multiple_activenote,
							'config_date_picker' => $config_date_picker,
							'config_time_picker' => $config_time_picker,
							'config_task_status' => $facility_info ['config_task_status'],
							'config_tag_status' => $facility_info ['config_tag_status'],
							'config_admin_limit' => $config_admin_limit,
							'notes_total' => $notes_total2,
							'notes_total1' => $notes_total,
							'http_check' => $configUrl,
							'http_url' => $configUrl2,
							'config_all_notification' => $config_all_notification,
							'config_task_sms' => $config_task_sms,
							'config_task_notification' => $config_task_notification,
							'config_task_email' => $config_task_email,
							'config_task_deleted_time' => $config_task_deleted_time,
							'config_taskform_status' => $taskstatus,
							'config_noteform_status' => $noteformtatus,
							'config_rules_status' => $ruletatus,
							'config_android_front_limit' => $config_android_front_limit,
							'config_transcription' => $config_transcription,
							'mobile_db_page_limit' => $mobile_db_page_limit,
							'mobile_list_view_page_limit' => $mobile_list_view_page_limit,
							
							'face_similar_percent' => $facility_info ['face_similar_percent'],
							'allow_face_without_verified' => $facility_info ['allow_face_without_verified'],
							'allow_quick_save' => $facility_info ['allow_quick_save'],
							'display_attchament' => $facility_info ['display_attchament'],
							'config_face_recognition' => $this->config->get ( 'config_face_recognition' ),
							'active_notification' => $this->config->get ( 'active_notification' ),
							
							'is_android_notification' => $facility_info ['is_android_notification'],
							'android_audio_file' => $android_audio_file,
							'is_android_snooze' => $facility_info ['is_android_snooze'],
							'is_android_dismiss' => $facility_info ['is_android_dismiss'],
							'is_ios_notification' => $facility_info ['is_ios_notification'],
							'ios_audio_file' => $ios_audio_file,
							'is_ios_snooze' => $facility_info ['is_ios_snooze'],
							'is_ios_dismiss' => $facility_info ['is_ios_dismiss'],
							
							'is_enable_beacon' => $facility_info ['is_enable_beacon'],
							'beacon_range' => $facility_info ['beacon_range'],
							'beacon_data_type_range' => $facility_info ['beacon_data_type_range'],
							
							'device_username' => $edevice_username,
							'device_token' => $edevice_token,
							'screening_id' => $screening_id,
							
							'is_discharge_form_enable' => $facility_info ['is_discharge_form_enable'],
							'discharge_form_id' => $facility_info ['discharge_form_id'],
							'is_fingerprint_enable' => $facility_info ['is_fingerprint_enable'],
							
							'is_pin_enable' => $facility_info ['is_pin_enable'],
							'is_sms_enable' => $facility_info ['is_sms_enable'],
							'is_enable_add_notes_by' => $facility_info ['is_enable_add_notes_by'],
							
							'is_client_facial' => $facility_info ['is_client_facial'],
							'is_master_facility' => $facility_info ['is_master_facility'],
							'notes_facilities_ids' => $facility_info ['notes_facilities_ids'],
							'client_facilities_ids' => $facility_info ['client_facilities_ids'],
							'add_client_url' => $add_client_url,
							'intake_id' => $intake_id,
							'current_permission' => $current_permission,
							'apiurls' => $apiurls1,
							'face_similar_percent' => $facility_info ['face_similar_percent'],
							'user_collection' => $customer_info ['user_collection'],
							'client_collection' => $customer_info ['client_collection'],
							'data_stream_name' => $customer_info ['data_stream_name'],
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
							'all_sync_pagination' => $all_sync_pagination,
							'subfacilities' => $subfacilities,
							'facility_setting' =>unserialize($facility_info['setting_data']),
							'allhouroutreport' =>  str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allhouroutreport', '' , 'SSL' ) ),
							
					);
					
					$error = true;
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Please Send Facilty id" 
					);
					$error = false;
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Please Send facility id" 
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
					'data' => 'Error in wearservice facilitysetting ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'facilitysetting', $activity_data2 );
		}
	}
	public function getuserbypin() {
		try {
			$this->data ['facilitiess'] = array ();
			
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
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				
				$this->load->model ( 'facilities/facilities' );
				$facility_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				
				if ($facility_info) {
					
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUserdetailuserpin ( $this->request->post ['user_pin'] );
					// var_dump($user_info['user_pin']);
					// var_dump($user_info['facilities']);
					
					if ($user_info ['facilities'] != null && $user_info ['facilities'] != "") {
						
						$facilities_info = explode ( ",", $user_info ['facilities'] );
						
						if (in_array ( $facility_info ['facilities_id'], $facilities_info )) {
							
							$this->data ['facilitiess'] [] = array (
									'facilities_id' => $facility_info ['facilities_id'],
									'username' => $user_info ['username'],
									'user_id' => $user_info ['user_id'],
									'user_pin' => $user_info ['user_pin'] 
							);
							
							$error = true;
						} else {
							$this->data ['facilitiess'] [] = array (
									'warning' => "This user is not authorized to access the facility" 
							);
							$error = false;
						}
					} else {
						$this->data ['facilitiess'] [] = array (
								'warning' => "Please enter valid pin" 
						);
						$error = false;
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Please Send facility id" 
					);
					$error = false;
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Please Send facility id" 
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
					'data' => 'Error in wearservice getuserbypin ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'getuserbypin', $activity_data2 );
		}
	}
	public function uploadbitmapImg() {
		try {
			
			if ($this->request->post ['outputFolder'] != null && $this->request->post ['outputFolder'] != "") {
				
				$outputFolder = $this->request->post ['outputFolder'];
				
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				
				$this->data ['facilitiess'] [] = array (
						's3file' => $s3file,
						'outputFolder' => $this->request->post ['outputFolder'] 
				);
				
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Please Send outputFolder file" 
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
					'data' => 'Error in wearservice uploadbitmapImg ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'uploadbitmapImg', $activity_data2 );
		}
	}
}


