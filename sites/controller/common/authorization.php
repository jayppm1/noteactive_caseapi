<?php
class Controllercommonauthorization extends Controller {
	private $error = array ();
	public function index() {
		try {
			
			$url2 = "";
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			if ($this->request->get ['delete_case'] != null && $this->request->get ['delete_case'] != "") {
				$url2 .= '&delete_case=' . $this->request->get ['delete_case'];
			}
			if ($this->request->get ['is_form_open'] != null && $this->request->get ['is_form_open'] != "") {
				$url2 .= '&is_form_open=' . $this->request->get ['is_form_open'];
			}
			
			if ($this->request->get ['parent_id'] != null && $this->request->get ['parent_id'] != "") {
				$url2 .= '&parent_id=' . $this->request->get ['parent_id'];
			}
			
			if ($this->request->get ['substatus_ids'] != null && $this->request->get ['substatus_ids'] != "") {
				$url2 .= '&substatus_ids=' . $this->request->get ['substatus_ids'];
			}

			if ($this->request->get ['substatus'] != null && $this->request->get ['substatus'] != "") {
				$url2 .= '&substatus=' . $this->request->get ['substatus'];
			}

			if ($this->request->get ['generatereport'] != null && $this->request->get ['generatereport'] != "") {
				$url2 .= '&generatereport=' . $this->request->get ['generatereport'];
			}
			
			if ($this->request->get ['acarule'] != null && $this->request->get ['acarule'] != "") {
				$url2 .= '&acarule=' . $this->request->get ['acarule'];
			}

			if ($this->request->get ['activenoteids'] != null && $this->request->get ['activenoteids'] != "") {
				$url2 .= '&activenoteids=' . $this->request->get ['activenoteids'];
			}

			if ($this->request->get ['action'] != null && $this->request->get ['action'] != "") {
				$url2 .= '&action=' . $this->request->get ['action'];
			}

			if ($this->request->get ['addmedication'] != null && $this->request->get ['addmedication'] != "") {
				$url2 .= '&addmedication=' . $this->request->get ['addmedication'];
			}
			
			if ($this->request->get ['status'] != null && $this->request->get ['status'] != "") {
				$url2 .= '&status=' . $this->request->get ['status'];
			}
			if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
				$url2 .= '&case_status=' . $this->request->get ['case_status'];
			}
			
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			
			if ($this->request->get ['redirection_type'] != null && $this->request->get ['redirection_type'] != "") {
				$url2 .= '&redirection_type=' . $this->request->get ['redirection_type'];
			}
			if ($this->request->get ['client_name_id'] != null && $this->request->get ['client_name_id'] != "") {
				$url2 .= '&client_name_id=' . $this->request->get ['client_name_id'];
			}
			if ($this->request->get ['is_mark_final'] != null && $this->request->get ['is_mark_final'] != "") {
				$url2 .= '&is_mark_final=' . $this->request->get ['is_mark_final'];
			}
			if ($this->request->get ['addcase'] != null && $this->request->get ['addcase'] != "") {
				$url2 .= '&addcase=' . $this->request->get ['addcase'];
			}
			if ($this->request->get ['is_formsecurity'] != null && $this->request->get ['is_formsecurity'] != "") {
				$url2 .= '&is_formsecurity=' . $this->request->get ['is_formsecurity'];
			}
			if ($this->request->get ['staticform'] != null && $this->request->get ['staticform'] != "") {
				$url2 .= '&staticform=' . $this->request->get ['staticform'];
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['facility_inout'] != null && $this->request->get ['facility_inout'] != "") {
				$url2 .= '&facility_inout=' . $this->request->get ['facility_inout'];
			}
			if ($this->request->get ['default_facility_id'] != null && $this->request->get ['default_facility_id'] != "") {
				$url2 .= '&default_facility_id=' . $this->request->get ['default_facility_id'];
			}
			if ($this->request->get ['facility_outs'] != null && $this->request->get ['facility_outs'] != "") {
				$url2 .= '&facility_outs=' . $this->request->get ['facility_outs'];
			}
			if ($this->request->get ['ext_facility'] != null && $this->request->get ['ext_facility'] != "") {
				$url2 .= '&ext_facility=' . $this->request->get ['ext_facility'];
			}
			if ($this->request->get ['default_facility_id'] != null && $this->request->get ['default_facility_id'] != "") {
				$url2 .= '&default_facility_id=' . $this->request->get ['default_facility_id'];
			}
			if ($this->request->get ['clienttype'] != null && $this->request->get ['clienttype'] != "") {
				$url2 .= '&clienttype=' . $this->request->get ['clienttype'];
			}
			if ($this->request->get ['comment_id'] != null && $this->request->get ['comment_id'] != "") {
				$url2 .= '&comment_id=' . $this->request->get ['comment_id'];
			}
			if ($this->request->get ['update_notetime'] != null && $this->request->get ['update_notetime'] != "") {
				$url2 .= '&update_notetime=' . $this->request->get ['update_notetime'];
			}
			if ($this->request->get ['notetime'] != null && $this->request->get ['notetime'] != "") {
				$url2 .= '&notetime=' . $this->request->get ['notetime'];
			}
			
			if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
				$url2 .= '&userids=' . $this->request->get ['userids'];
			}
			if ($this->request->get ['notes_ids'] != null && $this->request->get ['notes_ids'] != "") {
				$url2 .= '&notes_ids=' . $this->request->get ['notes_ids'];
			}
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
				$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
			}
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$url2 .= '&locationids=' . $this->request->get ['locationids'];
			}
			
			if ($this->request->get ['CheckInInventory'] != null && $this->request->get ['CheckInInventory'] != "") {
				$url2 .= '&CheckInInventory=' . $this->request->get ['CheckInInventory'];
			}
			if ($this->request->get ['CheckOutInventory'] != null && $this->request->get ['CheckOutInventory'] != "") {
				$url2 .= '&CheckOutInventory=' . $this->request->get ['CheckOutInventory'];
			}
			if ($this->request->get ['addinventory'] != null && $this->request->get ['addinventory'] != "") {
				$url2 .= '&addinventory=' . $this->request->get ['addinventory'];
			}
			if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
				$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
				$url2 .= '&notesids=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
			}
			if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
				$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['parent_id'] != null && $this->request->get ['parent_id'] != "") {
				$url2 .= '&parent_id=' . $this->request->get ['parent_id'];
			}
			if ($this->request->get ['page_number'] != null && $this->request->get ['page_number'] != "") {
				$url2 .= '&page_number=' . $this->request->get ['page_number'];
			}
			
			if ($this->request->get ['archive_medication_id'] != null && $this->request->get ['archive_medication_id'] != "") {
				$url2 .= '&archive_medication_id=' . $this->request->get ['archive_medication_id'];
			}
			
			if ($this->request->get ['updateMedication'] != null && $this->request->get ['updateMedication'] != "") {
				$url2 .= '&updateMedication=' . $this->request->get ['updateMedication'];
			}
			
			if ($this->request->get ['rolecallsign'] != null && $this->request->get ['rolecallsign'] != "") {
				$url2 .= '&rolecallsign=' . $this->request->get ['rolecallsign'];
			}
			
			if ($this->request->get ['in_out_input'] != null && $this->request->get ['in_out_input'] != "") {
				$url2 .= '&in_out_input=' . $this->request->get ['in_out_input'];
			}
			
			if ($this->request->get ['default_facility_id'] != null && $this->request->get ['default_facility_id'] != "") {
				$url2 .= '&default_facility_id=' . $this->request->get ['default_facility_id'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$url2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$url2 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
				$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
			}
			
			if ($this->request->get ['archive_tags_id'] != null && $this->request->get ['archive_tags_id'] != "") {
				$url2 .= '&archive_tags_id=' . $this->request->get ['archive_tags_id'];
			}
			
			if ($this->request->get ['user_roles'] != null && $this->request->get ['user_roles'] != "") {
				$url2 .= '&user_roles=' . $this->request->get ['user_roles'];
			}
			
			if ($this->request->get ['userids'] != null && $this->request->get ['userids'] != "") {
				$url2 .= '&userids=' . $this->request->get ['userids'];
			}
			
			if ($this->request->get ['discharge'] != null && $this->request->get ['discharge'] != "") {
				$url2 .= '&discharge=' . $this->request->get ['discharge'];
			}
			
			if ($this->request->get ['rolecall2'] != null && $this->request->get ['rolecall2'] != "") {
				$url2 .= '&rolecall2=' . $this->request->get ['rolecall2'];
			}
			
			if ($this->request->get ['role_call'] != null && $this->request->get ['role_call'] != "") {
				$url2 .= '&role_call=' . $this->request->get ['role_call'];
			}
			
			if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
				$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
			}
			if ($this->request->get ['archive_tags_medication_id'] != null && $this->request->get ['archive_tags_medication_id'] != "") {
				$url2 .= '&archive_tags_medication_id=' . $this->request->get ['archive_tags_medication_id'];
			}
			
			if ($this->request->get ['all_roll_call'] != null && $this->request->get ['all_roll_call'] != "") {
				$url2 .= '&all_roll_call=' . $this->request->get ['all_roll_call'];
			}
			
			if ($this->request->get ['all_roll_call1'] != null && $this->request->get ['all_roll_call1'] != "") {
				$url2 .= '&all_roll_call1=' . $this->request->get ['all_roll_call1'];
			}
			
			if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
				$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
			}
			
			if (isset ( $this->request->get ['taskid'] )) {
				$url2 .= '&taskids=' . $this->request->get ['taskid'];
			}
			
			if (isset ( $this->request->get ['formid'] )) {
				$url2 .= '&formids=' . $this->request->get ['formid'];
			}
			// if (isset($this->request->get['notes_id'])) {
			// $url2 .= '&notesids=' . $this->request->get['notes_id'];
			// }
			
			if (isset ( $this->request->get ['childstatus'] )) {
				$url2 .= '&childstatus=' . $this->request->get ['childstatus'];
			}
			if (isset ( $this->request->get ['tags_id'] )) {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}

			if ($this->request->get ['openinventory'] != null && $this->request->get ['openinventory'] != "") {
				$url2 .= '&openinventory=' . $this->request->get ['openinventory'];
			}
			
			if (isset ( $this->request->get ['keyword_id'] )) {
				$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
			}
			if ($this->request->get ['requires_approval'] != null && $this->request->get ['requires_approval'] != "") {
				$url2 .= '&requires_approval=' . $this->request->get ['requires_approval'];
			}
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['allclientstatus'] != null && $this->request->get ['allclientstatus'] != "") {
				$url2 .= '&allclientstatus=' . $this->request->get ['allclientstatus'];
			}
			if ($this->request->get ['allclientstatuses'] != null && $this->request->get ['allclientstatuses'] != "") {
				$url2 .= '&allclientstatuses=' . $this->request->get ['allclientstatuses'];
			}
			
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
				$url2 .= '&name=' . $this->request->get ['name'];
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($this->request->get ['is_formsecurity'] != null && $this->request->get ['is_formsecurity'] != "") {
				if ($this->request->get ['is_formsecurity'] == '1') {
					$this->data ['auth_by_face'] = '1';
				}
			} else {
				if ($facility ['is_enable_add_notes_by'] == '1') {
					$this->data ['auth_by_face'] = '1';
				}
			}
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateUserpin ()) {
				
				if (! isset ( $this->request->get ['is_mark_final'] ) && $this->request->get ['is_formsecurity'] != "" && $this->request->get ['is_formsecurity'] != null) {
					if ($this->request->get ['staticform'] == "" && $this->request->get ['staticform'] == null) {
						
						if ($this->request->get ['is_formsecurity'] == '4') {
							
							$this->load->model ( 'facilities/facilities' );
							$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
							$facilities_id = $facility ['facilities_id'];
							
							$userpin = $this->request->post ['userpin'];
							$this->load->model ( 'user/user' );
							$userdetail = $this->model_user_user->getUserByUserPin ( $userpin );
							
							$phone_number = $userdetail ['phone_number'];
							$randomNum = mt_rand ( 100000, 999999 );
							$message = 'Your OTP for validate is ' . $randomNum;
							$this->load->model ( 'api/smsapi' );
							$sdata = array ();
							$sdata ['message'] = $message;
							$sdata ['phone_number'] = $phone_number;
							$sdata ['facilities_id'] = $facilities_id;
							$response = $this->model_api_smsapi->sendsms ( $sdata );
							$timezone_name = $this->customer->isTimezone ();
							$timeZone = date_default_timezone_set ( $timezone_name );
							$date_added11 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							$share_note_otp = rand ( 0, 100000 );
							
							$dataotp = array (
									'user_id' => $userdetail ['user_id'],
									'otp' => $randomNum,
									'date_added' => $date_added11,
									'response' => $response,
									'facilities_id' => $facilities_id,
									'notes_id' => '',
									'alternate_email' => $userdetail ['email'],
									'share_note_otp' => $share_note_otp,
									'status' => '1',
									'otp_type' => 'dual_authentication' 
							);
							
							$url = '';
							$url .= '&user_id=' . $userdetail ['user_id'];
							$url .= '&otp_type=dual_authentication';
							$url .= '&is_formsecurity=' . $this->request->get ['is_formsecurity'];
							$url .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
							
							$this->model_user_user->insertUserOTP ( $dataotp );
							
							$edata = array ();
							if ($userdetail ['email'] != null && $userdetail ['email'] != "") {
								$this->load->model ( 'api/emailapi' );
								// $edata['message'] = $message;
								$edata ['subject'] = $facility ['facility'] . ' | ' . 'Your verification code';
								$edata ['facility'] = $facility ['facility'];
								$edata ['user_email'] = $userdetail ['email'];
								$edata ['when_date'] = date ( "l" );
								$edata ['who_user'] = $userdetail ['username'];
								$edata ['type'] = "10";
								$edata ['notes_description'] = $message;
								$edata ['message'] = $message;
								//$email_status = $this->model_api_emailapi->createMails ( $edata );
								
								$email_status = $this->model_api_emailapi->sendmail($edata);
							}
							$this->redirect ( $this->url->link ( 'common/verifyotp/verifyotp' . $url ) );
						} else {
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
						}
					} else {
						if ($this->request->get ['is_formsecurity'] == "4") {
							$this->load->model ( 'facilities/facilities' );
							$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
							$facilities_id = $facility ['facilities_id'];
							
							$userpin = $this->request->post ['userpin'];
							$this->load->model ( 'user/user' );
							$userdetail = $this->model_user_user->getUserByUserPin ( $userpin );
							
							$phone_number = $userdetail ['phone_number'];
							$randomNum = mt_rand ( 100000, 999999 );
							$message = 'Your OTP for validate is ' . $randomNum;
							$this->load->model ( 'api/smsapi' );
							$sdata = array ();
							$sdata ['message'] = $message;
							$sdata ['phone_number'] = $phone_number;
							$sdata ['facilities_id'] = $facilities_id;
							$response = $this->model_api_smsapi->sendsms ( $sdata );
							$timezone_name = $this->customer->isTimezone ();
							$timeZone = date_default_timezone_set ( $timezone_name );
							$date_added11 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							$share_note_otp = rand ( 0, 100000 );
							
							$dataotp = array (
									'user_id' => $userdetail ['user_id'],
									'otp' => $randomNum,
									'date_added' => $date_added11,
									'response' => $response,
									'facilities_id' => $facilities_id,
									'notes_id' => '',
									'alternate_email' => $userdetail ['email'],
									'share_note_otp' => $share_note_otp,
									'status' => '1',
									'otp_type' => 'dual_authentication' 
							);
							
							$url = '';
							$url .= '&user_id=' . $userdetail ['user_id'];
							$url .= '&otp_type=dual_authentication';
							$url .= '&is_formsecurity=' . $this->request->get ['is_formsecurity'];
							$url .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
							
							$this->model_user_user->insertUserOTP ( $dataotp );
							
							$edata = array ();
							if ($userdetail ['email'] != null && $userdetail ['email'] != "") {
								$this->load->model ( 'api/emailapi' );
								// $edata['message'] = $message;
								$edata ['facility'] = $facility ['facility'];
								$edata ['user_email'] = $userdetail ['email'];
								$edata ['when_date'] = date ( "l" );
								$edata ['who_user'] = $userdetail ['username'];
								$edata ['type'] = "10";
								$edata ['message'] = $message;
								$email_status = $this->model_api_emailapi->sendmail ( $edata );
							}
							$this->redirect ( $this->url->link ( 'common/verifyotp/verifyotp' . $url ) );
						}
					}
				}
				
				if ($this->request->get ['generatereport'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/generatereport', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['acarule'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/acarules/acastandarsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['change_status'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/change_status', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['staticform'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['staticform'] == "3") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '' . $url2, 'SSL' ) ) );
				}

				if ($this->request->get ['addmedication'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateMedication', '' . $url2, 'SSL' ) ) );
				}
				
				
				if (! isset ( $this->request->get ['is_mark_final'] ) && $this->request->get ['is_formsecurity'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['allclientstatus'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussign', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['is_formsecurity'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['allclientstatuses'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussigns', '' . $url2, 'SSL' ) ) );
					
					if ($this->request->get ['clienttype'] == "3" && $this->request->get ['page'] != "resident") {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsinsignature', '' . $url2, 'SSL' ) ) );
					}
				}
				
				if ($this->request->get ['addcase'] == "1") {
					if ($this->request->get ['delete_case'] == "1") {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/deletecase', '' . $url2, 'SSL' ) ) );
					} else {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/caseFormListSign', '' . $url2, 'SSL' ) ) );
					}
				}
				
				if ($this->request->get ['clienttype'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsinsignature', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['clienttype'] == "2") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsinsignature', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['clienttype'] == "3") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsinsignature', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['update_notetime'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updatenotetime', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['comment'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/comment/insert2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['savenotes'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
					if ($this->request->get ['forms'] == "1") {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form/activeformsign', '' . $url2, 'SSL' ) ) );
					}
				} else {
					if ($this->request->get ['forms'] == "1") {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form/newformsign', '' . $url2, 'SSL' ) ) );
					}
				}
				
				if ($this->request->get ['forms'] == "2") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['forms'] == "3") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert3', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['forms'] == "4") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form/taskforminsertsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['client'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclientsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['updateclient'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/updateclientsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['census'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/dailycensus/insert2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['strike'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateStrike', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['residentstatussign'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/residentstatussign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['allrolecallsign'] == "2") {
					
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/dischargeclients', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['allrolecallsign'] == "3") {
					
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatuses', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['allrolecallsign'] == "1") {
					
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allrolecallsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['savetask'] == "2") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/inserttask', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['savetask'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/updateStriketask', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['savetask'] == "3") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['updateTags'] == "1") {
					
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateTags', '' . $url2, 'SSL' ) ) );
				}
				if ($this->request->get ['assignteam'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/assignteam', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['rolecallsign'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/rolecallsign2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['tagmedicine'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['tagmedicine'] == "2") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['updateMedication'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['updateMedication'] == "2") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedicationsign2', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['clientactivenote'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/activenote', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['notesactivenote'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['update_strike'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updateStrike', '' . $url2, 'SSL' ) ) );
				}
				
				if ($this->request->get ['attachmentSign'] == "1") {
					$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/attachmentSign', '' . $url2, 'SSL' ) ) );
				}

				if ($this->request->get ['openinventory'] == "1") {
					

					$addinventory_url=$this->url->link ( 'notes/addInventory/addInventory', '' . $url2, 'SSL' );
					$this->session->data['inventory_username']=$this->session->data['username_confirm'];


					echo "<script type='text/javascript'>

					var main_url=\"$addinventory_url\";				
					var encoded = encodeURI(main_url.replace('&amp;','&'));
					window.top.location.href =encoded;
					parent.$.fn.colorbox.close();
					</script>";


				}
				if ($this->request->get ['openinventory'] == "2") {
					
					
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser($this->session->data['username_confirm']);
					
					 if($user_info){
						 
						 $url2.= '&user_id=' .$user_info['username'];
						 $url2.= '&user_id1=' .$user_info['user_id'];
						 
					 }
				

					$CheckOutInventory_url=$this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url2, 'SSL' );
      
					
					
					echo "<script type='text/javascript'>

					var main_url=\"$CheckOutInventory_url\";				
					 var urlString = main_url.replace(/&amp;/g, '&');			
					window.top.location.href =urlString;
					parent.$.fn.colorbox.close();
					</script>";
				}
				if ($this->request->get ['openinventory'] == "3") {
					
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser($this->session->data['username_confirm']);
					
					 if($user_info){
						 
						 $url2.= '&user_id=' .$user_info['username'];
						 $url2.= '&user_id1=' .$user_info['user_id'];
						 
					 }
					

					$CheckInInventory_url=$this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' );
                    
					

					echo "<script type='text/javascript'>

					var main_url=\"$CheckInInventory_url\";				
                    var urlString = main_url.replace(/&amp;/g, '&');			
					window.top.location.href =urlString;
					parent.$.fn.colorbox.close();
					</script>";
				}
				
				
				
			}
			
			if (isset ( $this->error ['warning'] )) {
				$this->data ['error_warning'] = $this->error ['warning'];
			} else {
				$this->data ['error_warning'] = '';
			}
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/common/authorization.php';
			
			$this->children = array (
					'common/headerpopup' 
			);
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Pin verification' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNotesverifypin', $activity_data2 );
		}
	}
	protected function validateUserpin() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if($this->request->get['generatereport']=='1'){	
		
			if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser($this->session->data['username_confirm']);
				if ($user_info ['user_group_id'] != null && $user_info ['user_group_id'] != "") {
					$this->load->model ( 'user/user_group' );
					
					
					$unique_id = $facility ['customer_key'];

					$this->load->model ( 'customer/customer' );
					$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				   
					$inventory_data=unserialize($customer_info['setting_data']);
					$activecustomer_id = $customer_info ['activecustomer_id'];
					$generate_report_user_role_array = $inventory_data['generate_user_role'];
			 
				   
				}

				if($generate_report_user_role_array!=null && $generate_report_user_role_array!=""){			
			 
					if(in_array($user_info ['user_group_id'], $generate_report_user_role_array)){
					
						$generate_check='1';			
						
					}else{
						
						$generate_check='0';

					}	
					
					if($generate_check=='0'){

						$this->error ['warning'] = "Your role is not authorized to generate report. Please contact your supervisor.";

					}
				
				}
				
			}
		}
		
		if ($this->request->get ['is_formsecurity'] != null && $this->request->get ['is_formsecurity'] != "") {
			$this->load->model ( 'form/form' );
			$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
			if ($this->request->get ['is_formsecurity'] == '4') {
				if ($this->request->post ['userpin'] == null && $this->request->post ['userpin'] == "") {
					$this->error ['warning'] = "Pin cannot be empty";
				}
				
				if ($this->request->post ['userpin'] != null && $this->request->post ['userpin'] != "") {
					$this->load->model ( 'user/user' );
					$userdetail = $this->model_user_user->getUserdetailuserpin ( $this->request->post ['userpin'] );
					
					if (($userdetail ['email'] == '' && $userdetail ['email'] == NULL) && ($userdetail ['phone_number'] == '' && $userdetail ['phone_number'] == NULL)) {
						$this->error ['warning'] = 'Phone Number or Email must be exist in the record';
					}
					
					if($this->request->get['is_mark_final']==1){
						if(!empty($fromdatas['mark_final_user_roles'])){
							if (!in_array ( $userdetail['user_group_id'], $fromdatas['mark_final_user_roles'] )) {
								$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
							
							}
						}
						
						/*$this->load->model ( 'user/user_group' );
						$user_role_info = $this->model_user_user_group->getUserGroup ( $userdetail ['user_group_id'] );
						
						if ($user_role_info ['enable_mark_final'] == 0) {
							$this->error ['warning'] = "Your role is not authorized to mark this form as final. Please contact your supervisor.";
						}*/
					}
					if($this->request->get['is_form_open']==1){
						
						
						if(!empty($fromdatas['approval_user_roles'])){
							if (!in_array ( $userdetail['user_group_id'], $fromdatas['approval_user_roles'] )) {
								$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
							
							}
						}
						
						/*$this->load->model ( 'user/user_group' );
						$user_role_info = $this->model_user_user_group->getUserGroup ( $userdetail ['user_group_id'] );
						
						if ($user_role_info ['enable_form_open'] == 0) {
							$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
						}*/
					}
					
					
					
					if (empty ( $userdetail )) {
						$this->error ['warning'] = "User not recognized, please try again ";
					} else {
						$this->session->data ['username_confirm'] = $userdetail ['user_id'];
					}
				}
			}
			
			
			if ($this->request->get ['is_formsecurity'] == '3') {
				if ($this->request->post ['userpin'] == null && $this->request->post ['userpin'] == "") {
					$this->error ['warning'] = "Pin cannot be empty";
				}
				
				if ($this->request->post ['userpin'] != null && $this->request->post ['userpin'] != "") {
					$this->load->model ( 'user/user' );
					$userdetail = $this->model_user_user->getUserdetailuserpin ( $this->request->post ['userpin'] );
					
					if($this->request->get['is_mark_final']==1){
						if(!empty($fromdatas['mark_final_user_roles'])){
							if (!in_array ( $userdetail['user_group_id'], $fromdatas['mark_final_user_roles'] )) {
								$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
							
							}
						}
						/*$this->load->model ( 'user/user_group' );
						$user_role_info = $this->model_user_user_group->getUserGroup ( $userdetail ['user_group_id'] );
						
						if ($user_role_info ['enable_mark_final'] == 0) {
							$this->error ['warning'] = "Your role is not authorized to mark this form as final. Please contact your supervisor.";
						}*/
					}
					if($this->request->get['is_form_open']==1){
						
						if(!empty($fromdatas['approval_user_roles'])){
							if (!in_array ( $userdetail['user_group_id'], $fromdatas['approval_user_roles'] )) {
								$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
							
							}
						}
						
						/*$this->load->model ( 'user/user_group' );
						$user_role_info = $this->model_user_user_group->getUserGroup ( $userdetail ['user_group_id'] );
						
						if ($user_role_info ['enable_form_open'] == 0) {
							$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
						}*/
					}
					
					if (empty ( $userdetail )) {
						$this->error ['warning'] = "Pin not recognized, please try again ";
					} else {
						$this->session->data ['username_confirm'] = $userdetail ['user_id'];
					}
				}
			}
			
			
			
			if ($this->request->get ['is_formsecurity'] == '1') {
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->error ['warning'] = "Sorry i am having trouble recognizing you. Lets try again!";
				}
				
				$this->load->model ( 'user/user' );
				$userdetail = $this->model_user_user->getUserbyupdate2 ( $this->session->data ['username_confirm'] );
				
				if($this->request->get['is_mark_final']==1){
					if(!empty($fromdatas['mark_final_user_roles'])){
						if (!in_array ( $userdetail['user_group_id'], $fromdatas['mark_final_user_roles'] )) {
							$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
						
						}
					}
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $userdetail ['user_group_id'] );
					
					if ($user_role_info ['enable_mark_final'] == 0) {
						$this->error ['warning'] = "Your role is not authorized to mark this form as final. Please contact your supervisor.";
					}*/
				}
				if($this->request->get['is_form_open']==1){
					if(!empty($fromdatas['approval_user_roles'])){
						if (!in_array ( $userdetail['user_group_id'], $fromdatas['approval_user_roles'] )) {
							$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
						
						}
					}
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $userdetail ['user_group_id'] );
					
					if ($user_role_info ['enable_form_open'] == 0) {
						$this->error ['warning'] = "Your role is not authorized to open this form. Please contact your supervisor.";
					}*/
				}
			}
		} else {
			
			if ($facility ['is_enable_add_notes_by'] == '3') {
				
				if ($this->request->post ['userpin'] == null && $this->request->post ['userpin'] == "") {
					$this->error ['warning'] = "Pin cannot be empty";
				}
				
				if ($this->request->post ['userpin'] != null && $this->request->post ['userpin'] != "") {
					$this->load->model ( 'user/user' );
					$userdetail = $this->model_user_user->getUserdetailuserpin ( $this->request->post ['userpin'] );
					
					
					
					
					if (empty ( $userdetail )) {
						$this->error ['warning'] = "Pin not recognized, please try again ";
					} else {
						$this->session->data ['username_confirm'] = $userdetail ['user_id'];
						
						
					}
				}
			}
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$this->data ['auth_by_face'] = '1';
				
				$outputFolder = $this->session->data ['local_image_dir'];
				$outputFolderUrl = $this->session->data ['local_image_url'];
				$notes_file = $this->session->data ['local_notes_file'];
				
				$facilities_id = $this->customer->getId ();
				
				if ($this->request->get ['update_strike'] == "1") {
					if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
						require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						$this->load->model ( 'notes/notes' );
						$this->model_notes_notes->updateuserpicturestrick ( $s3file, $this->request->get ['notes_id'] );
						// unlink($file);
					}
				}
				
				if ($this->request->get ['savenotes'] == "1") {
					if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
						
						require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						$this->load->model ( 'notes/notes' );
						$this->model_notes_notes->updateuserpicture ( $s3file, $this->request->get ['notes_id'] );
						// unlink($file);
					}
				}
				
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					if ($this->request->get ['update_strike'] == "1") {
						if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
							$this->model_notes_notes->updateuserverifiedstrick ( '2', $this->request->get ['notes_id'] );
						}
					}
					
					if ($this->request->get ['savenotes'] == "1") {
						if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
							$this->model_notes_notes->updateuserverified ( '2', $this->request->get ['notes_id'] );
						}
					}
				} else {
					if ($this->request->get ['update_strike'] == "1") {
						if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
							$this->model_notes_notes->updateuserverifiedstrick ( '1', $this->request->get ['notes_id'] );
						}
					}
					
					if ($this->request->get ['savenotes'] == "1") {
						if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
							$this->model_notes_notes->updateuserverified ( '1', $this->request->get ['notes_id'] );
						}
					}
				}
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function checkuser() {
		$json = array ();
		// $imagesrc = $_POST["image"];
		if ($this->request->post ['current_enroll_image'] != "" && $this->request->post ['current_enroll_image'] != NULL) {
			/*
			 * $img = $this->request->post['current_enroll_image'];
			 * $img = str_replace('data:image/jpeg;base64,', '', $img);
			 * $img = str_replace(' ', '+', $img);
			 * $Imgdata = base64_decode($img);
			 * $notes_file = uniqid() . '.jpeg';
			 * $file = DIR_IMAGE . '/facerecognition/' . $notes_file;
			 * $outputFolder = $file;
			 * $success = file_put_contents($file, $Imgdata);
			 * $imageUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
			 * $outputFolderUrl = $imageUrl;
			 */
			// require_once (DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config.php');
			
			$apiurl = "https://p4kbd8jj6a.execute-api.us-east-1.amazonaws.com/facialrekognition/facialrekognition";
			// $result_inser_user_img22 = $this->awsimageconfig->apigateway($apiurl, $this->request->post['current_enroll_image']);
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser ( $this->request->post ['current_enroll_image'], $facility ['facilities_id'] );
			
			foreach ( $result_inser_user_img22 ['FaceMatches'] as $c ) {
				$similarity = $c ['Similarity'];
				$FaceId [] = $c ['Face'] ['FaceId'];
				$ImageId [] = $c ['Face'] ['ImageId'];
				$ExternalImageId = $c ['Face'] ['ExternalImageId'];
			}
			
			if ($facility ['face_similar_percent'] != null && $facility ['face_similar_percent'] != "0") {
				$face_similar_percent = $facility ['face_similar_percent'];
			} else {
				$face_similar_percent = '90';
			}
			
			$this->session->data ['local_image_dir'] = $this->request->post ['current_enroll_image'];
			$this->session->data ['local_image_url'] = $outputFolderUrl;
			$this->session->data ['local_notes_file'] = $notes_file;
			
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserbyupdate2 ( $ExternalImageId );
			
			if ($similarity > $face_similar_percent) {
				
				if ($this->session->data ['isPrivate'] == '1') {
					if ($this->session->data ['webuser_id'] == $ExternalImageId) {
						$this->session->data ['username_confirm'] = $ExternalImageId;
						$json ['success'] = '1';
						$json ['username_confirm'] = $ExternalImageId;
					} else {
						$json ['success'] = '0';
					}
				} else {
					$this->session->data ['username_confirm'] = $ExternalImageId;
					$json ['success'] = '1';
					$json ['username_confirm'] = $ExternalImageId;
				}
			} else {
				
				if ($facility ['allow_face_without_verified'] == '1') {
					$json ['success'] = '1';
				} else {
					$json ['success'] = '0';
				}
			}
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
}
