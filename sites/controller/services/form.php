<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
header ( "Content-type: bitmap; charset=utf-8" );
class Controllerservicesform extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->data ['serviceforms_id'] = '1';
		
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['forms_id'] = $this->request->get ['forms_id'];
		
		$this->getForm ();
	}
	public function insert() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['forms_id'] = $this->request->get ['forms_id'];
		
		if ($this->request->post ['form_submit'] == '1' && $this->validateForm ()) {
			/*
			 * $data2 = array();
			 * $data2['forms_design_id'] = $this->request->get['forms_design_id'];
			 * //$data2['notes_id'] = $this->request->get['notes_id'];
			 * $data2['facilities_id'] = $this->request->get['facilities_id'];
			 * $formreturn_id = $this->model_form_form->addFormdata($this->request->post, $data2);
			 */
			if ($this->request->post ['top_submit'] != '6') {
				$editdata = array ();
				$editdata ['phone_device_id'] = $this->request->get ['phone_device_id'];
				$editdata ['device_unique_id'] = $this->request->get ['device_unique_id'];
				
				if ($this->request->get ['is_android'] != null && $this->request->get ['is_android'] != "") {
					$editdata ['is_android'] = $this->request->get ['is_android'];
				} else {
					$editdata ['is_android'] = '1';
				}
				
				$facilities_id = $this->request->get ['facilities_id'];
				
				if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
					$emp_tag_id333 = $this->request->post ['emp_tag_id'];
				} else {
					$emp_tag_id333 = $this->request->get ['tags_id'];
				}
				
				foreach ( $this->data ['formdatas'] as $key1 => $vals ) {
					
					if ($vals ['linktype'] == '1') {
						foreach ( $vals as $key2 => $v ) {
							foreach ( $v as $key3 => $v3 ) {
								
								$arrss = explode ( "_1_", $key3 );
								if ($arrss [1] == 'linktype_value') {
									
									if ($v [$arrss [0]] != null && $v [$arrss [0]] != "") {
										/*
										 * if($v[$arrss[0].'_1_linktype_value'] == 'tasktype'){
										 *
										 * }
										 */
										$linktype .= $v [$arrss [0]] . ' ';
									}
								}
							}
						}
					}
					
					if ($vals ['linktype'] == '2') {
						
						$drug_name = '';
						$drug_mg = '';
						$drug_prn = '';
						$instructions = '';
						
						foreach ( $vals as $key2 => $v ) {
							foreach ( $v as $key3 => $v3 ) {
								$arrss = explode ( "_1_", $key3 );
								if ($arrss [1] == 'linktype_value') {
									
									if ($v [$arrss [0]] != null && $v [$arrss [0]] != "") {
										
										// var_dump($v[$arrss[0].'_1_linktype_value']);
										if ($v [$arrss [0] . '_1_linktype_value'] == 'drug_name') {
											
											$drug_name = $v [$arrss [0]];
										}
										
										if ($v [$arrss [0] . '_1_linktype_value'] == 'drug_mg') {
											
											$drug_mg = $v [$arrss [0]];
										}
										
										if ($v [$arrss [0] . '_1_linktype_value'] == 'drug_prn') {
											$drug_prn = $v [$arrss [0]];
										}
										if ($v [$arrss [0] . '_1_linktype_value'] == 'instructions') {
											
											$instructions = $v [$arrss [0]];
										}
									}
								}
							}
						}
						
						$addmed = array ();
						
						$addmed ['drug_name'] = $drug_name;
						$addmed ['instructions'] = $instructions;
						$addmed ['drug_prn'] = $drug_prn;
						$addmed ['drug_mg'] = $drug_mg;
						if ($drug_name != "" && $drug_name != "") {
							$this->load->model ( 'resident/resident' );
							$this->model_resident_resident->addformmedicine ( $addmed, $emp_tag_id333 );
						}
					}
					
					$this->load->model ( 'setting/tags' );
					
					if ($vals ['linktype'] == '1') {
						
						$tags_info12 = $this->model_setting_tags->getTag ( $emp_tag_id333 );
						
						$snooze_time71 = 3;
						$thestime61 = date ( 'H:i:s' );
						$taskTime = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
						
						$current_time = date ( "H:i:s" );
						$current_date = date ( "Y-m-d" );
						
						$time1 = date ( 'H:i:s' );
						
						$addtaskw ['taskDate'] = date ( 'm-d-Y', strtotime ( $current_date ) );
						$addtaskw ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $current_date ) );
						
						$addtaskw ['recurrence'] = 'none';
						$addtaskw ['recurnce_week'] = '';
						$addtaskw ['recurnce_hrly'] = '';
						$addtaskw ['recurnce_month'] = '';
						$addtaskw ['recurnce_day'] = '';
						$addtaskw ['taskTime'] = $taskTime; // date('H:i:s');
						$addtaskw ['endtime'] = $taskTime;
						$addtaskw ['description'] = $tags_info12 ['emp_first_name'] . ' ' . $tags_info12 ['emp_last_name'] . ' ' . $linktype;
						$addtaskw ['assignto'] = '';
						$addtaskw ['tasktype'] = '1';
						$addtaskw ['numChecklist'] = '';
						$addtaskw ['task_alert'] = '1';
						$addtaskw ['alert_type_sms'] = '';
						$addtaskw ['alert_type_notification'] = '1';
						$addtaskw ['alert_type_email'] = '';
						$addtaskw ['rules_task'] = '';
						
						$addtaskw ['locations_id'] = '';
						$addtaskw ['facilities_id'] = $facilities_id;
						$addtaskw ['emp_tag_id'] = $emp_tag_id333;
						
						$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskw, $facilities_id );
					}
				}
				
				$results = $this->model_form_form->getFormDatasexit ( $this->request->get ['forms_design_id'], $this->request->get ['formreturn_id'] );
				// var_dump($results);
				// die;
				if (! empty ( $results )) {
					$this->model_form_form->editFormdata ( $this->request->post ['design_forms'], $this->request->get ['formreturn_id'], $this->request->post ['upload_file'], $this->request->post ['image'], $this->request->post ['signature'], $this->request->post ['form_signature'], $this->request->post ['is_final'], '1', $editdata );
				} else {
					
					if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
						$results = $this->model_form_form->getFormDatasparent ( $this->request->get ['forms_design_id'], $this->request->get ['formreturn_id'] );
						
						$dfforms_id = $results ['forms_id'];
					}
					
					if (! empty ( $results )) {
						$this->model_form_form->editFormdata ( $this->request->post ['design_forms'], $dfforms_id, $this->request->post ['upload_file'], $this->request->post ['image'], $this->request->post ['signature'], $this->request->post ['form_signature'], $this->request->post ['is_final'], '1', $editdata );
					} else {
						$formdata_i = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
						
						$data2 = array ();
						$data2 ['forms_design_id'] = $this->request->get ['forms_design_id'];
						$data2 ['form_design_parent_id'] = $formdata_i ['parent_id'];
						$data2 ['page_number'] = $formdata_i ['page_number'];
						$data2 ['form_parent_id'] = $this->request->get ['formreturn_id'];
						// $data2['notes_id'] = $this->request->get['updatenotes_id'];
						$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
						
						$data2 ['phone_device_id'] = $this->request->get ['phone_device_id'];
						$data2 ['device_unique_id'] = $this->request->get ['device_unique_id'];
						
						if ($this->request->get ['is_android'] != null && $this->request->get ['is_android'] != "") {
							$data2 ['is_android'] = $this->request->get ['is_android'];
						} else {
							$data2 ['is_android'] = '1';
						}
						
						$formreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
					}
				}
				
				$url2 = "";
				$url4 = "";
				
				if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
					$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
					$url4 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				} else {
					$url2 .= '&formreturn_id=' . $formreturn_id;
					$url4 .= '&formreturn_id=' . $formreturn_id;
				}
				
				if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
					$url2 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
					$url4 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
				}
				
				if ($this->request->post ['exittags_id'] != null && $this->request->post ['exittags_id'] != "") {
					$url2 .= '&exittags_id=' . $this->request->post ['exittags_id'];
					$url4 .= '&exittags_id=' . $this->request->post ['exittags_id'];
					$exittags_id = $this->request->post ['exittags_id'];
				} else {
					$exittags_id = '';
				}
				
				if ($this->request->post ['client_add_new'] != null && $this->request->post ['client_add_new'] != "") {
					$url2 .= '&client_add_new=' . $this->request->post ['client_add_new'];
					$url4 .= '&client_add_new=' . $this->request->post ['client_add_new'];
					$client_add_new = $this->request->post ['client_add_new'];
				} else {
					$client_add_new = '';
				}
				if ($this->request->post ['link_forms_id'] != null && $this->request->post ['link_forms_id'] != "") {
					$url2 .= '&link_forms_id=' . $this->request->post ['link_forms_id'];
					$url4 .= '&link_forms_id=' . $this->request->post ['link_forms_id'];
					$link_forms_id = $this->request->post ['link_forms_id'];
				} else {
					$link_forms_id = '';
				}
				
				if ($this->request->post ['is_final'] != null && $this->request->post ['is_final'] != "") {
					$url2 .= '&is_final=' . $this->request->post ['is_final'];
					$url4 .= '&is_final=' . $this->request->post ['is_final'];
					$is_final = $this->request->post ['is_final'];
				} else {
					$is_final = '';
				}
				
				if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
					$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
					$url4 .= '&forms_id=' . $this->request->get ['forms_id'];
					
					$forms_id = $this->request->get ['forms_id'];
				} else {
					$forms_id = '';
				}
				
				if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
					$url4 .= '&notes_id=' . $this->request->get ['notes_id'];
					$url2 .= '&new_form=1';
					$url4 .= '&new_form=1';
					$new_form = '1';
					$notes_id = $this->request->get ['notes_id'];
				} else {
					$new_form = '2';
					$notes_id = '';
					$url2 .= '&new_form=2';
					$url4 .= '&new_form=2';
				}
				
				if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
					$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
					$forms_design_id = $this->request->get ['forms_design_id'];
				} else {
					$forms_design_id = '';
				}
				
				if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
					$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
					$url4 .= '&facilities_id=' . $this->request->get ['facilities_id'];
					$facilities_id = $this->request->get ['facilities_id'];
				} else {
					$facilities_id = '';
				}
				
				if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
					$url2 .= '&task_id=' . $this->request->get ['task_id'];
					$url4 .= '&task_id=' . $this->request->get ['task_id'];
					$task_id = $this->request->get ['task_id'];
				} else {
					$task_id = '';
				}
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
					$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
					$tags_id = $this->request->get ['tags_id'];
				} else {
					$tags_id = '';
				}
				
				if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
					$url4 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
					$updatenotes_id = $this->request->get ['updatenotes_id'];
				} else {
					$updatenotes_id = '';
				}
				
				if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
					$url2 .= '&is_html=' . $this->request->get ['is_html'];
					$url4 .= '&is_html=' . $this->request->get ['is_html'];
					$is_html = $this->request->get ['is_html'];
				} else {
					$is_html = '';
				}
				
				if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
					$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
					$url4 .= '&activeform_id=' . $this->request->get ['activeform_id'];
				}
				if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
					$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
					$url4 .= '&case_file_id=' . $this->request->get ['case_file_id'];
				}
				if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
					$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
					$url4 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				}
				
				if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
					
					$formdata = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
					
					if ($this->request->get ['page_number'] > 0) {
						$cpage_number = $this->request->get ['page_number'];
					} else {
						$cpage_number = $formdata ['page_number'];
					}
					
					if ($this->request->get ['parent_id'] > 0) {
						$cparent_id = $this->request->get ['parent_id'];
					} else {
						$cparent_id = $this->request->get ['forms_design_id'];
					}
					
					$childform = $this->model_form_form->getFormByLimit ( $cparent_id, $cpage_number );
					if ($this->request->post ['bottom_submit'] != null && $this->request->post ['bottom_submit'] != "") {
						if (! empty ( $childform )) {
							
							if ($formdata ['parent_id'] > 0) {
								
								if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
									$url4 .= '&parent_id=' . $childform ['parent_id'];
								}
								
								if ($childform ['page_number'] != null && $childform ['page_number'] != "") {
									$url4 .= '&page_number=' . $childform ['page_number'];
								}
								
								if ($childform ['forms_id'] != null && $childform ['forms_id'] != "") {
									$url4 .= '&forms_design_id=' . $childform ['forms_id'];
								}
								
								// $this->session->data['success2'] = 'Form Created successfully!';
								$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
							} else {
								
								if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
									$url4 .= '&parent_id=' . $childform ['parent_id'];
								}
								
								if ($childform ['page_number'] != null && $childform ['page_number'] != "") {
									$url4 .= '&page_number=' . $childform ['page_number'];
								}
								
								if ($childform ['forms_id'] != null && $childform ['forms_id'] != "") {
									$url4 .= '&forms_design_id=' . $childform ['forms_id'];
								}
								
								$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
							}
						} else {
							
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form/jsoncustomsForm', '' . $url2, 'SSL' ) ) );
						}
					} else {
						
						if ($this->request->post ['jump_forms_id'] != null && $this->request->post ['jump_forms_id'] != "") {
							
							$formdata = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
							
							if ($this->request->get ['page_number'] > 0) {
								$cpage_number = $this->request->get ['page_number'];
							} else {
								$cpage_number = $formdata ['page_number'];
							}
							
							if ($this->request->get ['parent_id'] > 0) {
								$cparent_id = $this->request->get ['parent_id'];
							} else {
								$cparent_id = $this->request->get ['forms_design_id'];
							}
							
							$childform = $this->model_form_form->getFormByLimit ( $cparent_id, $cpage_number );
							
							if (! empty ( $childform )) {
								
								if ($formdata ['parent_id'] > 0) {
									
									if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
										$url4 .= '&parent_id=' . $childform ['parent_id'];
									}
									
									$url4 .= '&page_number=' . $this->request->post ['jump_page_number'];
									$url4 .= '&forms_design_id=' . $this->request->post ['jump_forms_id'];
									$url4 .= '&formreturn_id=' . $this->request->post ['jump_formreturn_id'];
									
									$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
								} else {
									
									if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
										$url4 .= '&parent_id=' . $childform ['parent_id'];
									}
									
									$url4 .= '&page_number=' . $this->request->post ['jump_page_number'];
									$url4 .= '&forms_design_id=' . $this->request->post ['jump_forms_id'];
									$url4 .= '&formreturn_id=' . $this->request->post ['jump_formreturn_id'];
									
									$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
								}
							}
						} else {
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form/jsoncustomsForm', '' . $url2, 'SSL' ) ) );
						}
					}
				}
			}
		}
		
		$this->getForm ();
	}
	protected function getForm() {
		
		/*
		 * $this->load->model('api/encrypt');
		 * $cre_array = array();
		 * $cre_array['phone_device_id'] = $this->request->get['phone_device_id'];
		 * $cre_array['facilities_id'] = $this->request->get['facilities_id'];
		 *
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
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->language->load ( 'notes/notes' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['forms_id'] = $this->request->get ['forms_id'];
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$this->data ['task_id_url'] = '&task_id=' . $this->request->get ['task_id'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->data ['facilities_id_url'] = '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
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
		}
		
		$this->data ['serviceforms_id'] = '1';
		
		if ($this->request->get ['forms_id'] == "" && $this->request->get ['forms_id'] == NULL) {
			if ($this->request->get ['formreturn_id'] == "" && $this->request->get ['formreturn_id'] == NULL) {
				$this->load->model ( 'form/form' );
				
				$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
				
				if ($fromdatas ['parent_id'] > 0) {
					$parent_id = $fromdatas ['parent_id'];
					$fromdatas2 = $this->model_form_form->getFormdata ( $parent_id );
				} else {
					$parent_id = $this->request->get ['forms_design_id'];
					
					$formdata_i = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
					
					$data2 = array ();
					$data2 ['forms_design_id'] = $this->request->get ['forms_design_id'];
					$data2 ['iframevalue'] = $this->request->get ['iframevalue'];
					$data2 ['form_design_parent_id'] = $formdata_i ['parent_id'];
					$data2 ['page_number'] = $formdata_i ['page_number'];
					$data2 ['form_parent_id'] = '0';
					
					$data2 ['phone_device_id'] = $this->request->get ['phone_device_id'];
					$data2 ['device_unique_id'] = $this->request->get ['device_unique_id'];
					
					if ($this->request->get ['is_android'] != null && $this->request->get ['is_android'] != "") {
						$data2 ['is_android'] = $this->request->get ['is_android'];
					} else {
						$data2 ['is_android'] = '1';
					}
					
					$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
					
					$pformreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
				}
				
				$data2 = array (
						'is_parent_child' => '1',
						'forms_id' => $parent_id,
						'sort' => 'page_number' 
				);
				
				$childforms = $this->model_form_form->getforms ( $data2 );
				
				if ($childforms) {
					foreach ( $childforms as $childform ) {
						
						$data2 = array ();
						$data2 ['forms_design_id'] = $childform ['forms_id'];
						$data2 ['form_design_parent_id'] = $childform ['parent_id'];
						$data2 ['page_number'] = $childform ['page_number'];
						$data2 ['form_parent_id'] = $pformreturn_id;
						
						$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
						// $data2['iframevalue'] = $this->request->get['iframevalue'];
						
						$data2 ['phone_device_id'] = $this->request->get ['phone_device_id'];
						$data2 ['device_unique_id'] = $this->request->get ['device_unique_id'];
						
						if ($this->request->get ['is_android'] != null && $this->request->get ['is_android'] != "") {
							$data2 ['is_android'] = $this->request->get ['is_android'];
						} else {
							$data2 ['is_android'] = '1';
						}
						
						$formreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
					}
				}
			}
		}
		
		$this->data ['is_form_open'] = 2;
		
		if ($pformreturn_id != null && $pformreturn_id != "") {
			$this->data ['jump_formreturn_id'] = $pformreturn_id;
		} else {
			$this->data ['jump_formreturn_id'] = $this->request->get ['formreturn_id'];
		}
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		// $this->data['fields'] = unserialize($fromdatas['forms_fields']);
		// $this->data['fields'] = $fromdatas['forms_fields'];
		
		// $form_data = $this->cache->get($this->request->get['forms_design_id']);
		
		$this->load->model ( 'api/cache' );
		$form_data = $this->model_api_cache->getcache ( $this->request->get ['forms_design_id'] );
		
		if (! $form_data) {
			
			$this->data ['fields'] = $fromdatas ['forms_fields'];
			$this->model_form_form->saveCache ( $this->request->get ['forms_design_id'] );
		} else {
			
			if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
				$this->data ['fields'] = $fromdatas ['forms_fields'];
			} else {
				$this->data ['form_data'] = $form_data;
			}
		}
		
		// if($fromdatas['display_observation'] == '1'){
		// $this->load->model('notes/notes');
		// $this->data['observationdatas'] = $this->model_notes_notes->getcustomlists();
		// }
		
		if ($fromdatas ['parent_id'] > 0) {
			$parent_id = $fromdatas ['parent_id'];
			$fromdatas2 = $this->model_form_form->getFormdata ( $parent_id );
		} else {
			$parent_id = $this->request->get ['forms_design_id'];
			$fromdatas2 = $this->model_form_form->getFormdata ( $parent_id );
		}
		
		// var_dump($parent_id);
		
		$this->data ['current_forms_parent_id'] = $parent_id;
		$this->data ['current_forms_design_id'] = $this->request->get ['forms_design_id'];
		
		if ($this->request->get ['page_number'] != null && $this->request->get ['page_number'] != "") {
			$this->data ['current_page_number'] = $this->request->get ['page_number'];
		} else {
			$this->data ['current_page_number'] = 0;
		}
		
		if ($this->request->get ['page_number'] > 0) {
			$cpage_numbersss = $this->request->get ['page_number'];
		} else {
			$cpage_numbersss = $fromdatas ['page_number'];
		}
		
		$this->data ['last_clild_form'] = $this->model_form_form->getFormByLimit ( $parent_id, $cpage_numbersss );
		
		$data2 = array (
				'is_parent_child' => '1',
				'forms_id' => $parent_id,
				'sort' => 'page_number' 
		);
		
		$childforms = $this->model_form_form->getforms ( $data2 );
		$totalchildforms = $this->model_form_form->getTotalforms ( $data2 );
		// var_dump($totalchildforms);
		
		if (! empty ( $childforms )) {
			$this->data ['totalchildforms'] = ($totalchildforms + 1);
			$this->data ['totalchildforms_step_submit'] = ($totalchildforms);
			$this->data ['totalchildforms_step'] = '2';
			
			// $this->data['current_forms_id'] = $fromdatas2['forms_id'];
			
			if ($fromdatas2 ['forms_id'] != null && $fromdatas2 ['forms_id'] != "") {
				$urlf2 .= '&forms_design_id=' . $fromdatas2 ['forms_id'];
			}
			if ($fromdatas2 ['parent_id'] != null && $fromdatas2 ['parent_id'] != "") {
				$urlf2 .= '&parent_id=' . $fromdatas2 ['parent_id'];
			}
			if ($fromdatas2 ['page_number'] != null && $fromdatas2 ['page_number'] != "") {
				$urlf2 .= '&page_number=' . $fromdatas2 ['page_number'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$urlf2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				
				if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
					$urlf2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
					$urlf2 .= '&forms_id=' . $this->request->get ['form_parent_id'];
				} else {
					$urlf2 .= '&forms_id=' . $this->request->get ['forms_id'];
					$urlf2 .= '&form_parent_id=' . $this->request->get ['forms_id'];
				}
			}
			
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$urlf2 .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$urlf2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$urlf2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$urlf2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
			}
			if ($this->request->get ['link_forms_id'] != null && $this->request->get ['link_forms_id'] != "") {
				$urlf2 .= '&link_forms_id=' . $this->request->get ['link_forms_id'];
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$urlf2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$urlf2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$urlf2 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$urlf2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$urlf2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$urlf2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$urlf2 .= '&is_html=' . $this->request->get ['is_html'];
			}
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$urlf2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$urlf2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$urlf2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$urlf2 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			$this->data ['childforms'] [] = array (
					'forms_id' => $fromdatas2 ['forms_id'],
					'form_name' => $fromdatas2 ['form_name'],
					'parent_id' => $fromdatas2 ['parent_id'],
					'page_number' => $fromdatas2 ['page_number'],
					'href' => $this->url->link ( 'services/form', $urlf2, true ) 
			);
			
			if ($this->request->get ['page_number'] > 0) {
				
				// var_dump($this->request->get['formreturn_id']);
				// var_dump($this->request->get['parent_id']);
				// var_dump($this->request->get['forms_design_id']);
				
				$formdata = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
				// var_dump($formdata);
				if ($this->request->get ['page_number'] > 0) {
					$cpage_number = $this->request->get ['page_number'];
				} else {
					$cpage_number = $formdata ['page_number'];
				}
				
				if ($this->request->get ['parent_id'] > 0) {
					$cparent_id = $this->request->get ['parent_id'];
				} else {
					$cparent_id = $this->request->get ['forms_design_id'];
				}
				
				$childform1 = $this->model_form_form->getFormByLimit2 ( $cparent_id, $cpage_number );
				// var_dump($childform1);
				$url14 = "";
				
				if (! empty ( $childform1 )) {
					if ($childform1 ['parent_id'] != null && $childform1 ['parent_id'] != "") {
						$url14 .= '&parent_id=' . $childform1 ['parent_id'];
					}
					
					if ($childform1 ['page_number'] != null && $childform1 ['page_number'] != "") {
						$url14 .= '&page_number=' . $childform1 ['page_number'];
					}
					
					if ($childform1 ['forms_id'] != null && $childform1 ['forms_id'] != "") {
						$url14 .= '&forms_design_id=' . $childform1 ['forms_id'];
					}
					
					if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
						
						if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
							
							$forms_id111 = $this->request->get ['form_parent_id'];
						} else {
							$forms_id111 = $this->request->get ['forms_id'];
						}
						
						// var_dump($forms_id111);
						// var_dump($childform['parent_id']);
						// var_dump($childform['forms_id']);
						
						$cdata = array ();
						$cdata ['form_design_parent_id'] = $childform1 ['parent_id'];
						$cdata ['form_parent_id'] = $forms_id111;
						$cdata ['custom_form_type'] = $childform1 ['forms_id'];
						$from_info_child11 = $this->model_form_form->getFormchild ( $cdata );
						
						// var_dump($from_info_child1);
						// echo "<hr>";
						
						if ($from_info_child11 != null && $from_info_child11 != "") {
							$url14 .= '&forms_id=' . $from_info_child11 ['forms_id'];
							$url14 .= '&form_parent_id=' . $from_info_child11 ['form_parent_id'];
						}
					}
					
					if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
						$url14 .= '&is_archive=' . $this->request->get ['is_archive'];
					}
					if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
						$url14 .= '&activeform_id=' . $this->request->get ['activeform_id'];
					}
					
					if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
						$url14 .= '&notes_id=' . $this->request->get ['notes_id'];
					}
					
					if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
						$url14 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
					}
					if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
						$url14 .= '&client_add_new=' . $this->request->get ['client_add_new'];
					}
					if ($this->request->get ['link_forms_id'] != null && $this->request->get ['link_forms_id'] != "") {
						$url14 .= '&link_forms_id=' . $this->request->get ['link_forms_id'];
					}
					
					if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
						$url14 .= '&searchdate=' . $this->request->get ['searchdate'];
					}
					if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
						$url14 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
					}
					
					if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
						$url14 .= '&task_id=' . $this->request->get ['task_id'];
					}
					
					if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
						$url14 .= '&tags_id=' . $this->request->get ['tags_id'];
					}
					if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
						$url14 .= '&last_notesID=' . $this->request->get ['last_notesID'];
					}
					
					if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
						$url14 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
					}
					if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
						$url14 .= '&is_html=' . $this->request->get ['is_html'];
					}
					if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
						$url14 .= '&facilities_id=' . $this->request->get ['facilities_id'];
					}
					if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
						$url14 .= '&case_file_id=' . $this->request->get ['case_file_id'];
					}
					if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
						$url14 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
					}
					
					if ($pformreturn_id != null && $pformreturn_id != "") {
						$url14 .= '&formreturn_id=' . $pformreturn_id;
					}
					
					$this->data ['previous_url'] = $this->url->link ( 'services/form', $url14, true );
				} else {
					$this->data ['previous_url'] = $this->url->link ( 'services/form', $urlf2, true );
				}
			} else {
				$this->data ['previous_url'] = $this->url->link ( 'services/form', $urlf2, true );
			}
			
			foreach ( $childforms as $childform ) {
				
				$urlc = "";
				if ($childform ['forms_id'] != null && $childform ['forms_id'] != "") {
					$urlc .= '&forms_design_id=' . $childform ['forms_id'];
				}
				if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
					$urlc .= '&parent_id=' . $childform ['parent_id'];
				}
				if ($childform ['page_number'] != null && $childform ['page_number'] != "") {
					$urlc .= '&page_number=' . $childform ['page_number'];
				}
				if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
					// $urlc .= '&form_parent_id=' . $this->request->get['forms_id'];
					
					if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
						
						$forms_id111 = $this->request->get ['form_parent_id'];
					} else {
						$forms_id111 = $this->request->get ['forms_id'];
					}
					
					// var_dump($forms_id111);
					// var_dump($childform['parent_id']);
					// var_dump($childform['forms_id']);
					
					$cdata = array ();
					$cdata ['form_design_parent_id'] = $childform ['parent_id'];
					$cdata ['form_parent_id'] = $forms_id111;
					$cdata ['custom_form_type'] = $childform ['forms_id'];
					$from_info_child1 = $this->model_form_form->getFormchild ( $cdata );
					
					// var_dump($from_info_child1);
					// echo "<hr>";
					
					if ($from_info_child1 != null && $from_info_child1 != "") {
						$urlc .= '&forms_id=' . $from_info_child1 ['forms_id'];
						$urlc .= '&form_parent_id=' . $from_info_child1 ['form_parent_id'];
					}
				}
				if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
					$urlc .= '&notes_id=' . $this->request->get ['notes_id'];
				}
				if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
					$urlc .= '&is_archive=' . $this->request->get ['is_archive'];
				}
				if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
					$urlc .= '&activeform_id=' . $this->request->get ['activeform_id'];
				}
				
				if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
					$urlc .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				}
				if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
					$urlc .= '&client_add_new=' . $this->request->get ['client_add_new'];
				}
				if ($this->request->get ['link_forms_id'] != null && $this->request->get ['link_forms_id'] != "") {
					$urlc .= '&link_forms_id=' . $this->request->get ['link_forms_id'];
				}
				
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$urlc .= '&searchdate=' . $this->request->get ['searchdate'];
				}
				if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
					$urlc .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				}
				
				if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
					$urlc .= '&task_id=' . $this->request->get ['task_id'];
				}
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$urlc .= '&tags_id=' . $this->request->get ['tags_id'];
				}
				if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
					$urlc .= '&last_notesID=' . $this->request->get ['last_notesID'];
				}
				
				if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
					$urlc .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				}
				if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
					$urlc .= '&is_html=' . $this->request->get ['is_html'];
				}
				if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
					$urlc .= '&facilities_id=' . $this->request->get ['facilities_id'];
				}
				if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
					$urlc .= '&case_file_id=' . $this->request->get ['case_file_id'];
				}
				if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
					$urlc .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				}
				if ($pformreturn_id != null && $pformreturn_id != "") {
					$urlc .= '&formreturn_id=' . $pformreturn_id;
				}
				
				$this->data ['childforms'] [] = array (
						'forms_id' => $childform ['forms_id'],
						'form_name' => $childform ['form_name'],
						'parent_id' => $childform ['parent_id'],
						'page_number' => $childform ['page_number'],
						'href' => $this->url->link ( 'services/form', $urlc, true ) 
				);
			}
		} else {
			
			$this->data ['totalchildforms'] = ($totalchildforms + 1);
			$this->data ['totalchildforms_step_submit'] = ($totalchildforms);
			$this->data ['totalchildforms_step'] = '12';
			
			if ($fromdatas ['forms_id'] != null && $fromdatas ['forms_id'] != "") {
				$urlf .= '&forms_design_id=' . $fromdatas ['forms_id'];
			}
			if ($fromdatas ['parent_id'] != null && $fromdatas ['parent_id'] != "") {
				$urlf .= '&parent_id=' . $fromdatas ['parent_id'];
			}
			if ($fromdatas ['page_number'] != null && $fromdatas ['page_number'] != "") {
				$urlf .= '&page_number=' . $fromdatas ['page_number'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$urlf .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				
				if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
					$urlf .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
					$urlf .= '&forms_id=' . $this->request->get ['form_parent_id'];
				} else {
					$urlf .= '&forms_id=' . $this->request->get ['forms_id'];
					$urlf .= '&form_parent_id=' . $this->request->get ['forms_id'];
				}
			}
			
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$urlf .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$urlf .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$urlf .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$urlf .= '&client_add_new=' . $this->request->get ['client_add_new'];
			}
			if ($this->request->get ['link_forms_id'] != null && $this->request->get ['link_forms_id'] != "") {
				$urlf .= '&link_forms_id=' . $this->request->get ['link_forms_id'];
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$urlf .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$urlf .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$urlf .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$urlf .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$urlf .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$urlf .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$urlf .= '&is_html=' . $this->request->get ['is_html'];
			}
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$urlf .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$urlf .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$urlf .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$urlf .= '&formreturn_id=' . $pformreturn_id;
			}
			
			$this->data ['previous_url'] = $this->url->link ( 'services/form', $urlf, true );
			
			$this->data ['childforms'] [] = array (
					'forms_id' => $fromdatas ['forms_id'],
					'form_name' => $fromdatas ['form_name'],
					'parent_id' => $fromdatas ['parent_id'],
					'page_number' => $fromdatas ['page_number'],
					'href' => $this->url->link ( 'services/form', $urlf, true ) 
			);
		}
		
		// var_dump($this->data['fields']);
		
		$this->data ['layouts'] = explode ( ",", $fromdatas ['form_layout'] );
		
		$this->data ['link_form_fieldall'] = $fromdatas ['link_form_fieldall'];
		
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_image'] = $fromdatas ['display_image'];
		$this->data ['display_signature'] = $fromdatas ['display_signature'];
		$this->data ['forms_setting'] = $fromdatas ['forms_setting'];
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_add_row'] = $fromdatas ['display_add_row'];
		$this->data ['display_content_postion'] = $fromdatas ['display_content_postion'];
		$this->data ['is_client_active'] = $fromdatas ['is_client_active'];
		$this->data ['form_type'] = $fromdatas ['form_type'];
		$this->data ['db_table_name'] = $fromdatas ['db_table_name'];
		$this->data ['client_reqired'] = $fromdatas ['client_reqired'];
		
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/timezone' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		
		$this->data ['facility_name'] = $facilities_info ['facility'];
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		
		date_default_timezone_set ( $timezone_info ['timezone_value'] );
		
		if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
			
			if ($fromdatas ['parent_id'] > 0) {
				$parent_id = $fromdatas ['parent_id'];
				
				$cdata = array ();
				$cdata ['form_design_parent_id'] = $parent_id;
				$cdata ['form_parent_id'] = $this->request->get ['form_parent_id'];
				$cdata ['custom_form_type'] = $fromdatas ['forms_id'];
				$from_info_child = $this->model_form_form->getFormchild ( $cdata );
				
				$fforms_id = $from_info_child ['forms_id'];
			} else {
				$fforms_id = $this->request->get ['forms_id'];
			}
			
			// var_dump($fforms_id);
			
			if ($this->request->get ['is_archive'] == "4") {
				$results = $this->model_form_form->getFormDatas3 ( $fforms_id, $this->request->get ['notes_id'] );
				
				if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
					$this->load->model ( 'notes/notes' );
					$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
					
					$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
					$this->data ['is_archive'] = $this->request->get ['is_archive'];
				}
			} else {
				$results = $this->model_form_form->getFormDatas ( $fforms_id );
			}
			
			$this->data ['custom_form_type'] = $results ['custom_form_type'];
			$this->data ['is_discharge'] = $results ['is_discharge'];
			
			// var_dump($this->data['is_discharge']);
			
			/*
			 * if($results['parent_id'] > 0 ){
			 * $this->load->model('notes/notes');
			 * $this->load->model('notes/tags');
			 * $this->load->model('user/user');
			 *
			 * $notesresults = $this->model_notes_notes->getnotesbyparent($results['parent_id']);
			 *
			 * foreach($notesresults as $result){
			 *
			 * if($result['notes_pin'] != null && $result['notes_pin'] != ""){
			 * $userPin = $result['notes_pin'];
			 * }else{
			 * $userPin = '';
			 * }
			 *
			 *
			 *
			 * if ($config_tag_status == '1') {
			 *
			 * $alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
			 *
			 *
			 * if($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != ""){
			 * $tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
			 * $privacy = $tagdata['privacy'];
			 *
			 * $emp_tag_id = '';//$alltag['emp_tag_id'].': ';
			 *
			 * }else{
			 * $emp_tag_id = '';
			 * $privacy = '';
			 *
			 * }
			 * }
			 *
			 *
			 *
			 * $allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
			 * $noteskeywords = array();
			 *
			 * if($allkeywords){
			 * $keyImageSrc12 = array();
			 * $keyname = array();
			 * $keyImageSrc11 = "";
			 * foreach ($allkeywords as $keyword) {
			 *
			 * $keyImageSrc11 .= '<img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px">';
			 *
			 * $noteskeywords[]= array(
			 * 'keyword_file_url' =>$keyword['keyword_file_url'],
			 * );
			 * }
			 *
			 * $keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
			 * $notes_description = $emp_tag_id . $keyword_description;
			 *
			 *
			 * }
			 *
			 *
			 *
			 * $this->data['notess'][] = array(
			 * 'notes_id' => $result['notes_id'],
			 * 'task_type' => $result['task_type'],
			 * 'taskadded' => $result['taskadded'],
			 * 'assign_to' => $result['assign_to'],
			 * 'highlighter_value' => $highlighterData['highlighter_value'],
			 * 'notes_description' => $notes_description,
			 * 'notetime' => date('h:i A', strtotime($result['notetime'])),
			 * 'username' => $result['user_id'],
			 * 'notes_pin' => $userPin,
			 * 'signature' => $result['signature'],
			 * 'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
			 *
			 * );
			 * }
			 * }
			 *
			 */
		}
		
		// var_dump($this->data['formsimages']);
		// var_dump($this->data['formssigns']);
		
		if ($this->request->get ['forms_id'] == "" && $this->request->get ['forms_id'] == NULL) {
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
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
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$url2 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/insert', $url2, true ) );
		} else {
			$url2 = "";
			$url3 = "";
			$url3a = "";
			$url4 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
				$url3 .= '&searchdate=' . $this->request->get ['searchdate'];
				$url4 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $fforms_id;
				$url3 .= '&forms_id=' . $fforms_id;
				$this->data ['forms_id'] = $fforms_id;
				$url3a .= '&forms_id=' . $fforms_id;
				
				if ($this->request->get ['forms_design_id'] == '164') {
					$fddata = array ();
					$fddata ['form_parent_id'] = $fforms_id;
					$fddata ['custom_form_type'] = $this->request->get ['forms_design_id'];
					
					$presults = $this->model_form_form->getFormchildone ( $fddata );
					
					$url4 .= '&forms_id=' . $presults ['forms_id'];
					$url4 .= '&forms_design_id=' . $presults ['custom_form_type'];
				} else {
					if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
						if ($this->request->get ['form_parent_id'] == $fforms_id) {
							$url4 .= '&forms_id=' . $fforms_id;
						} else {
							if ($this->request->get ['forms_design_id'] == '165') {
								$url4 .= '&forms_id=' . $fforms_id;
							} else {
								$url4 .= '&forms_id=' . $this->request->get ['form_parent_id'];
							}
						}
					} else {
						$url4 .= '&forms_id=' . $fforms_id;
					}
				}
			}
			
			if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
				$url3 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url3a .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url3a .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url4 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
			} else {
				$url3 .= '&form_parent_id=' . $this->request->get ['forms_id'];
				$url2 .= '&form_parent_id=' . $this->request->get ['forms_id'];
				$url3a .= '&form_parent_id=' . $this->request->get ['forms_id'];
				$url4 .= '&form_parent_id=' . $this->request->get ['forms_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				$url3 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				$url3a .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				$url4 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				$url3 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				$url3a .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				$url4 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
				$url3 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
				
				if ($this->request->get ['forms_design_id'] == '164') {
				} else {
					if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
						if ($this->request->get ['form_parent_id'] == $fforms_id) {
							$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
						} else {
							if ($this->request->get ['forms_design_id'] == '165') {
								$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
							} else {
								$resultinfo = $this->model_form_form->getFormDatas ( $this->request->get ['form_parent_id'] );
								
								$url4 .= '&forms_design_id=' . $resultinfo ['custom_form_type'];
							}
						}
					} else {
						$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
					}
				}
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
				$url3a .= '&notes_id=' . $this->request->get ['notes_id'];
				$url4 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url3 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url3a .= '&tags_id=' . $this->request->get ['tags_id'];
				$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
				$url3 .= '&task_id=' . $this->request->get ['task_id'];
				$url3a .= '&task_id=' . $this->request->get ['task_id'];
				$url4 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
				$url3 .= '&facilities_id=' . $this->request->get ['facilities_id'];
				$url3a .= '&facilities_id=' . $this->request->get ['facilities_id'];
				$url4 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
				$url3 .= '&case_file_id=' . $this->request->get ['case_file_id'];
				$url3a .= '&case_file_id=' . $this->request->get ['case_file_id'];
				$url4 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url3 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url3a .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url4 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url3 .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url3a .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url4 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				$url3 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				$url4 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			
			if ($this->request->get ['parent_id'] != null && $this->request->get ['parent_id'] != "") {
				$url2 .= '&parent_id=' . $this->request->get ['parent_id'];
				$url3 .= '&parent_id=' . $this->request->get ['parent_id'];
				$url4 .= '&parent_id=' . $this->request->get ['parent_id'];
			}
			if ($this->request->get ['page_number'] != null && $this->request->get ['page_number'] != "") {
				$url2 .= '&page_number=' . $this->request->get ['page_number'];
				$url3 .= '&page_number=' . $this->request->get ['page_number'];
				$url4 .= '&page_number=' . $this->request->get ['page_number'];
			}
			
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
				$url3a .= '&is_archive=' . $this->request->get ['is_archive'];
				$url4 .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
				$url3 .= '&is_html=' . $this->request->get ['is_html'];
				$url4 .= '&is_html=' . $this->request->get ['is_html'];
			}
			
			if ($this->request->get ['is_archive'] == "4") {
				$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
				$url3 .= '&notes_id=' . $form_info ['notes_id'];
			}
			
			$this->data ['archive_url'] = str_replace ( '&amp;', '&', $url3a );
			
			$this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/edit', $url2, true ) );
			
			$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/edit', '' . $url3, 'SSL' ) );
			
			/*
			 * if($this->request->get['forms_design_id'] == '13' ){
			 * $this->data['print_url'] = $this->url->link('form/form/printformfldjj', $url2, true);
			 * }else{
			 * $this->data['print_url'] = $this->url->link('form/form/printform', $url4, true);
			 * }
			 */
			
			if ($this->request->get ['forms_design_id'] == '13') {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printformfldjj', $url2, true );
			} elseif ($this->request->get ['forms_design_id'] == '150') {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printformfldjj', $url2, true );
			} else {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printform', $url4, true );
			}
		}
		if ($this->request->get ['previous'] == '1') {
			if ($this->request->get ['formreturn_id'] != "" && $this->request->get ['formreturn_id'] != NULL) {
				$dfforms_id = $this->request->get ['formreturn_id'];
				
				// var_dump($dfforms_id);
				
				$results = $this->model_form_form->getFormDatasexit ( $this->request->get ['forms_design_id'], $this->request->get ['formreturn_id'] );
				
				// var_dump($results);
				
				if (empty ( $results )) {
					$results = $this->model_form_form->getFormDatasparent ( $this->request->get ['forms_design_id'], $this->request->get ['formreturn_id'] );
					
					// var_dump($results);
					
					$dfforms_id = $results ['forms_id'];
				}
				
				// $results = $this->model_form_form->getFormDatas($dfforms_id);
				
				$this->data ['custom_form_type'] = $results ['custom_form_type'];
				$this->data ['is_discharge'] = $results ['is_discharge'];
			}
		}
		
		if ($fforms_id != null && $fforms_id != "") {
			$fforms_id = $fforms_id;
		} else {
			$fforms_id = $dfforms_id;
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		$this->data ['formdatas'] = array ();
		
		if (isset ( $this->request->post ['design_forms'] )) {
			$this->data ['formdatas'] = $this->request->post ['design_forms'];
		} elseif (! empty ( $results )) {
			$this->data ['formdatas'] = unserialize ( $results ['design_forms'] );
		}
		
		if ($this->request->get ['is_archive'] == "4") {
			$formmedias = $this->model_form_form->getFormmedia3 ( $fforms_id, $this->request->get ['notes_id'] );
		} else {
			$formmedias = $this->model_form_form->getFormmedia ( $fforms_id );
		}
		
		if ($formmedias != null && $formmedias != "") {
			$this->data ['formsimages'] = array ();
			$this->data ['formssigns'] = array ();
			
			foreach ( $formmedias as $formmedia ) {
				
				if ($formmedia ['media_type'] == '1') {
					$this->data ['formdatas'] [$formmedia ['media_name']] [] = $formmedia ['media_url'];
				}
				
				if ($formmedia ['media_type'] == '2') {
					$this->data ['formdatas'] [$formmedia ['media_name']] [] = $formmedia ['media_url'];
				}
			}
		}
		
		if (isset ( $this->request->post ['upload_file'] )) {
			$this->data ['upload_file'] = $this->request->post ['upload_file'];
		} elseif (! empty ( $results )) {
			$this->data ['upload_file'] = $results ['upload_file'];
		} else {
			$this->data ['upload_file'] = '';
		}
		
		if (isset ( $this->request->post ['form_signature'] )) {
			$this->data ['form_signature'] = $this->request->post ['form_signature'];
		} elseif (! empty ( $results )) {
			$this->data ['form_signature'] = $results ['form_signature'];
		} else {
			$this->data ['form_signature'] = '';
		}
		
		if (isset ( $this->request->post ['is_final'] )) {
			$this->data ['is_final'] = $this->request->post ['is_final'];
		} elseif (! empty ( $results )) {
			$this->data ['is_final'] = $results ['is_final'];
		} else {
			$this->data ['is_final'] = '';
		}
		
		if (isset ( $this->request->post ['is_approval_required'] )) {
			$this->data ['is_approval_required'] = $this->request->post ['is_approval_required'];
		} elseif (! empty ( $results )) {
			$this->data ['is_approval_required'] = $results ['is_approval_required'];
		} else {
			$this->data ['is_approval_required'] = '';
		}
		$userids = array ();
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'user/user' );
		
		$tagids = array ();
		if ($results ['notes_id'] > 0) {
			$alltags = $this->model_notes_notes->getNotesTagsmultiple ( $results ['notes_id'] );
			
			$notes_info = $this->model_notes_notes->getNote ( $results ['notes_id'] );
			$userinfo = $this->model_user_user->getUserByUsernamebynotes ( $notes_info ['user_id'], $notes_info ['facilities_id'] );
			$userids [] = $userinfo ['user_id'];
			
			if (count ( $alltags ) == 1) {
				$tags_id = $alltags [0] ['tags_id'];
				foreach ( $alltags as $alltag ) {
					$tagids [] = $alltag ['tags_id'];
				}
			} else {
				foreach ( $alltags as $alltag ) {
					$tagids [] = $alltag ['tags_id'];
				}
			}
			
			$tagids = array_unique ( $tagids );
		} else if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$alltags = $this->model_notes_notes->getNotesTagsmultiple ( $this->request->get ['updatenotes_id'] );
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['updatenotes_id'] );
			$userinfo = $this->model_user_user->getUserByUsernamebynotes ( $notes_info ['user_id'], $notes_info ['facilities_id'] );
			$userids [] = $userinfo ['user_id'];
			if (count ( $alltags ) == 1) {
				$tags_id = $alltags [0] ['tags_id'];
				foreach ( $alltags as $alltag ) {
					$tagids [] = $alltag ['tags_id'];
				}
			} else {
				foreach ( $alltags as $alltag ) {
					$tagids [] = $alltag ['tags_id'];
				}
			}
			
			$tagids = array_unique ( $tagids );
		}
		
		$this->data ['tagids'] = $tagids;
		$this->data ['userids'] = $userids;
		// $this->data['tagid'] = $tagid;
		// var_dump($tagid);
		// var_dump($this->data['tagids']);
		
		$this->data ['userinfo'] = $userinfo;
		
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/timezone' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
		
		$this->data ['facility_name'] = $facilities_info ['facility'];
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		
		date_default_timezone_set ( $timezone_info ['timezone_value'] );
		
		date_default_timezone_set ( $timezone_name );
		$ctime = time ();
		$stime = date ( 'H:i:s', strtotime ( $ctime ) );
		
		$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $this->request->get ['facilities_id'] );
		$this->data ['shift_info'] = $shift_info;
		
		if ($this->request->get ['formreturn_id'] > 0) {
			$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['formreturn_id'] );
			$formdata = unserialize ( $form_info ['design_forms'] );
			
			foreach ( $formdata as $design_forms ) {
				foreach ( $design_forms as $key => $design_form ) {
					foreach ( $design_form as $key2 => $b ) {
						
						$arrss = explode ( "_1_", $key2 );
						if ($arrss [1] == 'tags_id') {
							if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
									$form_tags_id = $design_form [$arrss [0] . '_1_' . $arrss [1]];
								}
							}
						}
					}
				}
			}
		}
		
		if ($form_tags_id > 0) {
			$tags_id = $form_tags_id;
			
			$this->data ['search_tags_id'] = $tags_id;
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTagbyEMPID ( $tags_id );
			$this->data ['tagdetails'] = $tag_info;
		} else 

		if ($this->request->get ['search_tags_id']) {
			$tags_id = $this->request->get ['search_tags_id'];
			$this->data ['search_tags_id'] = $this->request->get ['search_tags_id'];
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTagbyEMPID ( $tags_id );
			$this->data ['tagdetails'] = $tag_info;
		} else {
			if ($this->request->get ['tags_id']) {
				$tags_id = $this->request->get ['tags_id'];
			} elseif ($this->request->post ['emp_tag_id']) {
				$tags_id = $this->request->post ['emp_tag_id'];
			} elseif (! empty ( $results )) {
				$tags_id = $results ['tags_id'];
			}
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			$this->data ['tagdetails'] = $tag_info;
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
			$this->data ['emp_tag_id1'] = $tag_info ['emp_last_name'] . ' ' . $tag_info ['emp_first_name'];
		} else {
			$this->data ['emp_tag_id1'] = '';
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
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->request->post ['allclients'] )) {
			$this->data ['allclients'] = $this->request->post ['allclients'];
		} else {
			$this->data ['allclients'] = '1';
		}
		
		if (isset ( $this->request->post ['exittags_id'] )) {
			$this->data ['exittags_id'] = $this->request->post ['exittags_id'];
		} else {
			$this->data ['exittags_id'] = '';
		}
		if (isset ( $this->request->post ['client_add_new'] )) {
			$this->data ['client_add_new'] = $this->request->post ['client_add_new'];
		} else {
			$this->data ['client_add_new'] = '';
		}
		
		$url31 = "";
		
		if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''] != "") {
			$url31 .= '&emp_extid=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''];
		}
		
		if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''] != "") {
			$url31 .= '&ssn=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''];
		}
		
		if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_FNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_FNAME . ''] != "") {
			$url31 .= '&emp_first_name=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_FNAME . ''];
		}
		
		if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_LNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_LNAME . ''] != "") {
			$url31 .= '&emp_last_name=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_LNAME . ''];
		}
		
		if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_DOB . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_DOB . ''] != "") {
			$url31 .= '&dob=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_DOB . ''];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url31 .= '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		$this->data ['redirect_url_2'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/exittags', '' . $url31, 'SSL' ) );
		
		if (isset ( $this->error ['exit_error'] )) {
			$this->data ['exit_error'] = $this->error ['exit_error'];
		} else {
			$this->data ['exit_error'] = '';
		}
		
		if ($this->request->get ['forms_design_id'] == CUSTOME_I_INTAKEID) {
			$url31 = "";
			
			if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_EXTID . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_EXTID . ''] != "") {
				$url31 .= '&emp_extid=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_EXTID . ''];
			}
			
			if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_SSN . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_SSN . ''] != "") {
				$url31 .= '&ssn=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_SSN . ''];
			}
			
			if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''] != "") {
				$url31 .= '&emp_first_name=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''];
			}
			
			if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''] != "") {
				$url31 .= '&emp_last_name=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''];
			}
			
			if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_DOB . ''] != "" && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_DOB . ''] != null) {
				$url31 .= '&dob=' . $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_DOB . ''];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url31 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			$url31 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			
			$this->data ['redirect_url_2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/exitscreening', '' . $url31, 'SSL' ) );
			
			if (isset ( $this->error ['exit_error'] )) {
				$this->data ['exit_error'] = $this->error ['exit_error'];
			} else {
				$this->data ['exit_error'] = '';
			}
			
			if (isset ( $this->request->post ['client_add_new'] )) {
				$this->data ['client_add_new'] = $this->request->post ['client_add_new'];
			} else {
				$this->data ['client_add_new'] = '';
			}
			if (isset ( $this->request->post ['link_forms_id'] )) {
				$this->data ['link_forms_id'] = $this->request->post ['link_forms_id'];
			} else {
				$this->data ['link_forms_id'] = '';
			}
			if (isset ( $this->request->post ['link_screening'] )) {
				$this->data ['link_screening'] = $this->request->post ['link_screening'];
			} else {
				$this->data ['link_screening'] = '';
			}
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->data ['facilities_id'] = $this->request->get ['facilities_id'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$s = array ();
		$s ['facilities_id'] = $this->request->get ['facilities_id'];
		$s ['sbfacility'] = 1;
		$this->data ['sfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $s );
		
		$this->data ['dnotess'] = array ();
		$searchdate = date ( 'm-d-Y' );
		
		$data31 = array (
				'status' => 1,
				'discharge' => 1,
				'role_call' => '1',
				'sort' => 'emp_first_name',
				'facilities_id' => $this->request->get ['facilities_id'] 
		)
		;
		$this->load->model ( 'setting/tags' );
		
		$this->data ['allTagsclients'] = $this->model_setting_tags->getTags ( $data31 );
		
		$this->load->model ( 'user/user' );
		$this->data ['allcusers'] = $this->model_user_user->getUsersByFacility ( $this->request->get ['facilities_id'] );
		
		if (isset ( $this->request->post ['top_submit'] )) {
			$this->data ['top_submit'] = $this->request->post ['top_submit'];
		} else {
			$this->data ['top_submit'] = '';
		}
		
		// $formvals = $this->request->post['design_forms'];
		
		// var_dump($fromdatas['relation_keyword_id']);
		
		// var_dump($formvals);
		/*
		 * $searchval = array();
		 * foreach($this->data['formdatas'] as $key1=>$vals){
		 *
		 * //var_dump($key1);
		 *
		 * foreach($vals as $key2=>$v){
		 * foreach($v as $key3=>$v3){
		 * $arrss = explode("_1_", $key3);
		 * if($arrss[1] == 'facilities_id'){
		 * if($v[$arrss[0]] != null && $v[$arrss[0]] != ""){
		 * $search_facilities_id = $v[$arrss[0]];
		 * }
		 * }
		 *
		 * if($arrss[1] == 'tags_id'){
		 * if($v[$arrss[0]] != null && $v[$arrss[0]] != ""){
		 * if($v[$arrss[0].'_1_'.$arrss[1]] != null && $v[$arrss[0].'_1_'.$arrss[1]] != ""){
		 *
		 * $search_emp_tag_id = $v[$arrss[0].'_1_'.$arrss[1]];
		 *
		 * }
		 * }
		 * }
		 *
		 * if($arrss[1] == 'user_id'){
		 * if($v[$arrss[0]] != null && $v[$arrss[0]] != ""){
		 * $search_user_id = $v[$arrss[0]];
		 * }
		 * }
		 *
		 *
		 * }
		 * }
		 * }
		 *
		 * if($fromdatas['relation_keyword_id'] > 0){
		 * $search_keyword_id = $fromdatas['relation_keyword_id'];
		 * }
		 *
		 *
		 *
		 * if($search_facilities_id != null && $search_facilities_id != ""){
		 * $search_facilities_id1 = $search_facilities_id;
		 * }else{
		 * $search_facilities_id1 = $this->request->get['facilities_id'];
		 * }
		 * $this->language->load('notes/notes');
		 * $this->load->model('notes/notes');
		 * $this->load->model('setting/tags');
		 * $this->load->model('setting/highlighter');
		 * $this->load->model('facilities/facilities');
		 * $this->load->model('notes/tags');
		 *
		 * if ($this->request->post['top_submit'] == '6') {
		 *
		 *
		 *
		 *
		 * if($this->data['db_table_name'] == 'notestable'){
		 * $this->data['dnotess'] = array();
		 *
		 * $ffdata = array(
		 * 'sort' => $sort,
		 * 'order' => $order,
		 * //'searchdate' => $searchdate,
		 * 'advance_searchapp' => '1',
		 * 'facilities_id' => $search_facilities_id1,
		 * 'note_date_from' => date('Y-m-d'),
		 * 'note_date_to' => date('Y-m-d'),
		 * 'emp_tag_id' => $search_emp_tag_id,
		 * 'user_id' => $search_user_id,
		 * 'activenote' => $search_keyword_id,
		 * 'start' => 0,
		 * 'limit' => 500
		 * );
		 *
		 * //var_dump($ffdata);
		 *
		 * $nnotes = $this->model_notes_notes->getnotess($ffdata);
		 *
		 * //var_dump($nnotes);
		 * foreach($nnotes as $nnote){
		 * $result_info = $this->model_facilities_facilities->getfacilities($nnote['facilities_id']);
		 * $emp_tag_id = "";
		 * if ($nnote['emp_tag_id'] == '1') {
		 * $alltags = $this->model_setting_tags->getTagsbyNotesID2($nnote['notes_id']);
		 * foreach($alltags as $alltag){
		 * $emp_tag_id = "";
		 * $tag_info = $this->model_setting_tags->getTag($alltag['tags_id']);
		 * $emp_tag_id .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] .', ';
		 * }
		 *
		 * }
		 *
		 * $keyImageSrc11 = "";
		 * if ($nnote['keyword_file'] == '1') {
		 * $allkeywords = $this->model_notes_notes->getnoteskeywors($nnote['notes_id']);
		 * foreach ($allkeywords as $keyword) {
		 * $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
		 * }
		 * }
		 *
		 * if ($nnote['highlighter_id'] > 0) {
		 * $highlighterData = $this->model_setting_highlighter->gethighlighter($nnote['highlighter_id']);
		 * } else {
		 * $highlighterData = array();
		 * }
		 *
		 * $this->data['dnotess'][] = array(
		 * 'notes_id' => $nnote['notes_id'],
		 * 'emp_tag_id' => $emp_tag_id,
		 * 'facilities_id' => $result_info['facility'],
		 * 'notes_description' =>$keyImageSrc11.' '. $nnote['notes_description'],
		 * 'notetime' => date('h:i A', strtotime($nnote['notetime'])),
		 * 'user_id' => $nnote['user_id'],
		 * 'signature' => $nnote['signature'],
		 * 'highlighter_value' => $highlighterData['highlighter_value'],
		 * 'text_color' => $nnote['text_color'],
		 * //'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
		 * 'date_added' => date('m-d-Y', strtotime($nnote['date_added'])),
		 * 'note_date' => date('m-d-Y h:i A', strtotime($nnote['date_added'])),
		 *
		 * );
		 * }
		 * }
		 *
		 * if($this->data['db_table_name'] == 'clienttable'){
		 * $cffdata = array(
		 * 'status' => 1,
		 * 'discharge' => 1,
		 * 'role_call' => '1',
		 * 'sort' => 'emp_first_name',
		 * //'searchdate' => $searchdate,
		 * 'facilities_id' => $search_facilities_id1,
		 * 'emp_tag_id' => '',
		 * 'all_record' => '1'
		 *
		 * );
		 *
		 *
		 *
		 * $tnnotes = $this->model_setting_tags->getTags($cffdata);
		 *
		 * //var_dump($tnnotes);
		 * foreach($tnnotes as $stag){
		 * $result_info = $this->model_facilities_facilities->getfacilities($stag['facilities_id']);
		 * $this->data['dtnnotess'][] = array(
		 * 'name' => $stag['emp_first_name'] . ' ' . $stag['emp_last_name'],
		 * 'facilities_id' => $result_info['facility'],
		 * 'emp_first_name' => $stag['emp_first_name'],
		 * 'emp_last_name' => $stag['emp_last_name'],
		 * 'emp_tag_id' => $stag['emp_tag_id'],
		 * 'tags_id' => $stag['tags_id'],
		 * 'gender' => $stag['gender'],
		 * 'emp_extid' => $stag['emp_extid'],
		 * 'emergency_contact' => $stag['emergency_contact'],
		 * 'location_address' => $stag['location_address'],
		 * 'ssn' => $stag['ssn'],
		 * 'note_date' => date($this->language->get('date_format_short_2'), strtotime($stag['note_date'])),
		 *
		 * );
		 * }
		 * }
		 *
		 * }
		 *
		 * if($this->request->get['forms_id'] != "" && $this->request->get['forms_id'] != NULL){
		 *
		 * if($this->data['db_table_name'] == 'notestable'){
		 *
		 * if($this->data['form_type'] == "Database"){
		 *
		 * $ffdata = array(
		 * 'sort' => $sort,
		 * 'order' => $order,
		 * //'searchdate' => $searchdate,
		 * 'advance_searchapp' => '1',
		 * 'facilities_id' => $search_facilities_id1,
		 * 'note_date_from' => date('Y-m-d'),
		 * 'note_date_to' => date('Y-m-d'),
		 * 'emp_tag_id' => $search_emp_tag_id,
		 * 'user_id' => $search_user_id,
		 * 'activenote' => $search_keyword_id,
		 * 'start' => 0,
		 * 'limit' => 500
		 * );
		 *
		 * //var_dump($ffdata);
		 *
		 * $nnotes = $this->model_notes_notes->getnotess($ffdata);
		 *
		 * //var_dump($nnotes);
		 * foreach($nnotes as $nnote){
		 * $result_info = $this->model_facilities_facilities->getfacilities($nnote['facilities_id']);
		 *
		 * $emp_tag_id = "";
		 * if ($nnote['emp_tag_id'] == '1') {
		 * $alltags = $this->model_setting_tags->getTagsbyNotesID2($nnote['notes_id']);
		 * foreach($alltags as $alltag){
		 * $emp_tag_id = "";
		 * $tag_info = $this->model_setting_tags->getTag($alltag['tags_id']);
		 * $emp_tag_id .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] .', ';
		 * }
		 *
		 * }
		 *
		 * $keyImageSrc11 = "";
		 * if ($nnote['keyword_file'] == '1') {
		 * $allkeywords = $this->model_notes_notes->getnoteskeywors($nnote['notes_id']);
		 * foreach ($allkeywords as $keyword) {
		 * $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
		 * }
		 * }
		 *
		 * if ($nnote['highlighter_id'] > 0) {
		 * $highlighterData = $this->model_setting_highlighter->gethighlighter($nnote['highlighter_id']);
		 * } else {
		 * $highlighterData = array();
		 * }
		 *
		 * $this->data['dnotess'][] = array(
		 * 'notes_id' => $nnote['notes_id'],
		 * 'emp_tag_id' => $emp_tag_id,
		 * 'notes_description' => $keyImageSrc11.' '. $nnote['notes_description'],
		 * 'facilities_id' => $result_info['facility'],
		 * 'highlighter_value' => $highlighterData['highlighter_value'],
		 * 'text_color' => $nnote['text_color'],
		 * 'notetime' => date('h:i A', strtotime($nnote['notetime'])),
		 * 'user_id' => $nnote['user_id'],
		 * 'signature' => $nnote['signature'],
		 * //'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
		 * 'date_added' => date('m-d-Y', strtotime($nnote['date_added'])),
		 * 'note_date' => date('m-d-Y h:i A', strtotime($nnote['date_added'])),
		 *
		 * );
		 * }
		 * }
		 * }
		 * if($this->data['db_table_name'] == 'clienttable'){
		 *
		 * $cffdata = array(
		 * 'status' => 1,
		 * 'discharge' => 1,
		 * 'role_call' => '1',
		 * 'sort' => 'emp_first_name',
		 * //'searchdate' => $searchdate,
		 * 'facilities_id' => $search_facilities_id1,
		 * 'emp_tag_id' => '',
		 * 'all_record' => '1'
		 *
		 * );
		 *
		 *
		 *
		 *
		 * $tnnotes = $this->model_setting_tags->getTags($cffdata);
		 *
		 * //var_dump($tnnotes);
		 * foreach($tnnotes as $stag){
		 * $result_info = $this->model_facilities_facilities->getfacilities($stag['facilities_id']);
		 * $this->data['dtnnotess'][] = array(
		 * 'name' => $stag['emp_first_name'] . ' ' . $stag['emp_last_name'],
		 * 'facilities_id' => $result_info['facility'],
		 * 'emp_first_name' => $stag['emp_first_name'],
		 * 'emp_last_name' => $stag['emp_last_name'],
		 * 'emp_tag_id' => $stag['emp_tag_id'],
		 * 'tags_id' => $stag['tags_id'],
		 * 'gender' => $stag['gender'],
		 * 'emp_extid' => $stag['emp_extid'],
		 * 'emergency_contact' => $stag['emergency_contact'],
		 * 'location_address' => $stag['location_address'],
		 * 'ssn' => $stag['ssn'],
		 * 'note_date' => date($this->language->get('date_format_short_2'), strtotime($stag['note_date'])),
		 *
		 * );
		 * }
		 * }
		 *
		 * }
		 */
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/form/form.php';
		
		$this->children = array (
				'common/headerform' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm() {
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			if ($this->request->get ['forms_design_id'] == CUSTOME_I_INTAKEID) {
				
				$this->error ['warning'] = $this->language->get ( 'error_intake_form22' );
				
				$this->load->model ( 'form/form' );
				$form_info = $this->model_form_form->getFormwithNotes ( $this->request->get ['updatenotes_id'], CUSTOME_I_INTAKEID );
				
				if ($form_info != null && $form_info != "") {
					$this->error ['warning'] = $this->language->get ( 'error_intake_form' );
				}
			}
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			if ($this->request->get ['forms_design_id'] == CUSTOME_INTAKEID) {
				
				$this->load->model ( 'form/form' );
				$form_info = $this->model_form_form->getFormwithNotes ( $this->request->get ['updatenotes_id'], CUSTOME_INTAKEID );
				
				if ($form_info != null && $form_info != "") {
					$this->error ['warning'] = $this->language->get ( 'error_screening_form' );
				}
			}
		}
		
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->get ['forms_design_id'] == CUSTOME_HOMEVISIT || $this->request->get ['forms_design_id'] == CUSTOME_DISCHARGE) {
			if ($this->request->post ['emp_tag_id1'] == "" && $this->request->post ['emp_tag_id1'] == "") {
				$this->error ['warning'] = $this->language->get ( 'error_client' );
			}
			
			if ($this->request->post ['emp_tag_id'] == null && $this->request->post ['emp_tag_id'] == "") {
				$this->error ['warning'] = $this->language->get ( 'error_client' );
			}
		}
		
		// var_dump($this->session->data['formreturn_id']);
		if ($this->session->data ['formreturn_id'] != null && $this->session->data ['formreturn_id'] != "") {
		}
		
		// var_dump($this->request->post);
		// die;
		
		/*
		 * if ($this->request->post['emp_tag_id'] == null && $this->request->post['emp_tag_id'] == "") {
		 *
		 * if($this->request->get['forms_id'] == null && $this->request->get['forms_id'] == ""){
		 * if(($this->request->post['exittags_id'] == null && $this->request->post['exittags_id'] == "") && ($this->request->post['client_add_new'] == null && $this->request->post['client_add_new'] == "")){
		 * if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
		 *
		 * $this->load->model('setting/tags');
		 *
		 * $dob111 = $this->request->post['design_forms'][0][0][''.TAG_DOB.''] ;
		 *
		 * $date = str_replace('-', '/', $dob111);
		 *
		 * $res = explode("/", $date);
		 * $createdate1 = $res[2]."-".$res[0]."-".$res[1];
		 *
		 * $dob = date('Y-m-d',strtotime($createdate1));
		 *
		 * $data = array(
		 * 'facilities_id' => $this->request->get['facilities_id'],
		 * 'exits_emp_extid' => $this->request->post['design_forms'][0][0][''.TAG_EXTID.''],
		 * 'exits_ssn' => $this->request->post['design_forms'][0][0][''.TAG_SSN.''],
		 * 'exits_emp_first_name' => $this->request->post['design_forms'][0][0][''.TAG_FNAME.''],
		 * 'exits_emp_last_name' => $this->request->post['design_forms'][0][0][''.TAG_LNAME.''],
		 * 'exits_dob' => $dob,
		 * 'tags_exits' => '1',
		 * 'status' => '1',
		 * 'sort' => 'emp_tag_id',
		 * 'order' => 'ASC',
		 * );
		 *
		 * //var_dump($data);
		 *
		 * $results = $this->model_setting_tags->getTags($data);
		 * //var_dump($results);
		 *
		 * foreach($results as $tresult){
		 * $addtags_info = $this->model_form_form->gettagsforma($tresult['tags_id']);
		 * //var_dump($addtags_info);
		 *
		 * if(!empty($addtags_info)){
		 * $this->error['warning'] = $this->language->get('error_exist_client_added');
		 * }else{
		 * $this->error['warning'] = $this->language->get('error_exist_client');
		 * if($this->request->post['design_forms'][0][0][''.TAG_FNAME.''] != null && $this->request->post['design_forms'][0][0][''.TAG_FNAME.''] != ""){
		 * $this->error['exit_error'] = '1';
		 * }
		 * }
		 * }
		 * }
		 * }
		 * }
		 * }
		 */
		
		/*
		 * if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
		 *
		 * $this->load->model('setting/tags');
		 *
		 * if($this->request->post['exittags_id'] != null && $this->request->post['exittags_id'] != ""){
		 * $addtags_info = $this->model_form_form->gettagsforma($this->request->post['exittags_id']);
		 *
		 * //var_dump($addtags_info);
		 *
		 * if($addtags_info['forms_id'] != null && $addtags_info['forms_id'] != ""){
		 * //var_dump($ssn_total);echo "<hr>";
		 * $url2 .= '&forms_id=' . $addtags_info['forms_id'];
		 * $url2 .= '&forms_design_id=' . $addtags_info['custom_form_type'];
		 * $url2 .= '&tags_id=' . $addtags_info['tags_id'];
		 * $url2 .= '&notes_id=' . $addtags_info['notes_id'];
		 * $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		 * $action211 = $this->url->link('services/form/edit', '' . $url2, 'SSL');
		 *
		 * if (! isset($this->request->get['forms_id'])) {
		 *
		 * if ($addtags_info) {
		 * $this->error['warning'] = sprintf($this->language->get('error_client_already'), $action211);
		 *
		 * }
		 * } else {
		 *
		 * if ($addtags_info && ($this->request->get['forms_id'] != $addtags_info['forms_id'])) {
		 * $this->error['warning'] = sprintf($this->language->get('error_client_already'), $action211);
		 * }
		 * }
		 * }
		 * }
		 *
		 * if($this->request->post['client_add_new'] != null && $this->request->post['client_add_new'] != ""){
		 * //var_dump($this->request->post);
		 *
		 * if($this->request->post['design_forms'][0][0][''.TAG_EXTID.''] != null && $this->request->post['design_forms'][0][0][''.TAG_EXTID.''] != ""){
		 * $emp_extid_info = $this->model_setting_tags->getTagsbyAllName(array('emp_extid'=>$this->request->post['design_forms'][0][0][''.TAG_EXTID.'']));
		 * $addtags_info = $this->model_form_form->gettagsforma($emp_extid_info['tags_id']);
		 *
		 * if($addtags_info['forms_id'] != null && $addtags_info['forms_id'] != ""){
		 * //var_dump($emp_extid_total);echo "<hr>";
		 * $this->error['warning'] = $this->language->get('error_exist_client_emp_extid');
		 * }
		 * }
		 *
		 *
		 * if($this->request->post['design_forms'][0][0][''.TAG_SSN.''] != null && $this->request->post['design_forms'][0][0][''.TAG_SSN.''] != ""){
		 * $ssn_info = $this->model_setting_tags->getTagsbyAllName(array('ssn'=>$this->request->post['design_forms'][0][0][''.TAG_SSN.'']));
		 *
		 * $addtags_info = $this->model_form_form->gettagsforma($ssn_info['tags_id']);
		 *
		 * if($addtags_info['forms_id'] != null && $addtags_info['forms_id'] != ""){
		 * //var_dump($ssn_total);echo "<hr>";
		 * $this->error['warning'] = $this->language->get('error_exist_client_ssn');
		 * }
		 *
		 * }
		 * }
		 * }
		 */
		/*
		 * if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
		 * if(($this->request->post['design_forms'][0][0][''.TAG_FNAME.''] == null && $this->request->post['design_forms'][0][0][''.TAG_FNAME.''] == "") && ($this->request->post['design_forms'][0][0][''.TAG_LNAME.''] == null && $this->request->post['design_forms'][0][0][''.TAG_LNAME.''] == "")){
		 * $this->error['warning'] = $this->language->get('error_client_name');
		 * }
		 * }
		 */
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		if ($this->request->get ['forms_design_id'] != CUSTOME_INTAKEID && $this->request->get ['forms_design_id'] != CUSTOME_I_INTAKEID) {
			foreach ( $this->request->post ['design_forms'] as $design_forms ) {
				foreach ( $design_forms as $key => $design_form ) {
					foreach ( $design_form as $key2 => $b ) {
						
						$arrss = explode ( "_1_", $key2 );
						// var_dump($arrss);
						// echo "<hr>";
						
						if ($fromdatas ['client_reqired'] == '1') {
							if ($arrss [1] == 'tags_id') {
								// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
								// var_dump($design_form[$arrss[0]]);
								// echo "<hr>";
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									if ($design_form [$arrss [0] . '_1_' . $arrss [1]] == null && $design_form [$arrss [0] . '_1_' . $arrss [1]] == "") {
										$this->error ['warning'] = $this->language->get ( 'error_invalid_client' );
									}
								}
							}
						}
						
						if ($arrss [1] == 'user_id') {
							if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] == null && $design_form [$arrss [0] . '_1_' . $arrss [1]] == "") {
									// $this->error['warning'] = $this->language->get('error_invalid_user');
								}
							}
						}
						
						if ($arrss [1] == 'shift_id') {
							if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] == null && $design_form [$arrss [0] . '_1_' . $arrss [1]] == "") {
									$this->error ['warning'] = $this->language->get ( 'error_invalid_shift' );
								}
							}
						}
						
						if ($arrss [1] == 'locations_id') {
							if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] == null && $design_form [$arrss [0] . '_1_' . $arrss [1]] == "") {
									$this->error ['warning'] = $this->language->get ( 'error_invalid_location' );
								}
							}
						}
						
						if ($arrss [1] == 'require') {
							
							if ($design_form [$arrss [0] . '_1_' . $arrss [1]] == '1') {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] == null && $design_form [$arrss [0] . '_1_' . $arrss [1]] == "") {
									$this->error ['warning'] = $this->language->get ( 'error_required' );
								}
							}
						}
						
						if ($arrss [1] == 'user_ids') {
							// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
							// var_dump($design_form[$arrss[0]]);
							// echo "<hr>";
						}
						
						if ($arrss [1] == 'tags_ids') {
							// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
							// var_dump($design_form[$arrss[0]]);
							// echo "<hr>";
						}
					}
				}
			}
		}
		
		/*
		 * if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
		 * if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
		 * $addtags_info = $this->model_form_form->gettagsforma($this->request->post['emp_tag_id']);
		 *
		 * if($addtags_info['forms_id'] != null && $addtags_info['forms_id'] != ""){
		 * //var_dump($emp_extid_total);echo "<hr>";
		 *
		 * $url2 .= '&forms_id=' . $addtags_info['forms_id'];
		 * $url2 .= '&forms_design_id=' . $addtags_info['custom_form_type'];
		 * $url2 .= '&tags_id=' . $addtags_info['tags_id'];
		 * $url2 .= '&notes_id=' . $addtags_info['notes_id'];
		 * $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		 * $action211 = $this->url->link('services/form/edit', '' . $url2, 'SSL');
		 *
		 * if (! isset($this->request->get['forms_id'])) {
		 *
		 * if ($addtags_info) {
		 * $this->error['warning'] = sprintf($this->language->get('error_client_already'), $action211);
		 *
		 * }
		 * } else {
		 *
		 * if ($addtags_info && ($this->request->get['forms_id'] != $addtags_info['forms_id'])) {
		 * $this->error['warning'] = sprintf($this->language->get('error_client_already'), $action211);
		 * }
		 * }
		 *
		 * }
		 * }
		 * }
		 */
		/*
		 * if($this->request->get['forms_design_id'] == CUSTOME_I_INTAKEID ){
		 *
		 * $dob111 = $this->request->post['design_forms'][0][0][''.TAG_I_DOB.''] ;
		 *
		 * $date = str_replace('-', '/', $dob111);
		 *
		 * $res = explode("/", $date);
		 * $createdate1 = $res[2]."-".$res[0]."-".$res[1];
		 *
		 * $dob = date('Y-m-d',strtotime($createdate1));
		 *
		 * $existclient = array();
		 * $existclient['emp_extid'] = $this->request->post['design_forms'][0][0][''.TAG_I_EXTID.''];
		 * $existclient['ssn'] = $this->request->post['design_forms'][0][0][''.TAG_I_SSN.''];
		 * $existclient['dob'] = $dob;
		 * $existclient['emp_first_name'] = $this->request->post['design_forms'][0][0][''.TAG_I_FNAME.''];
		 * $existclient['emp_last_name'] = $this->request->post['design_forms'][0][0][''.TAG_I_LNAME.''];
		 *
		 * $this->load->model('setting/tags');
		 * if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
		 * $tag_exist_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
		 * }else{
		 * $tag_exist_info = $this->model_setting_tags->getTagsbyAllName($existclient);
		 * }
		 *
		 *
		 * $addtags_iffnfo = $this->model_form_form->gettagsformaintake($tag_exist_info['tags_id']);
		 *
		 * $url2 .= '&forms_id=' . $addtags_iffnfo['forms_id'];
		 * $url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
		 *
		 * $url2 .= '&tags_id=' . $tag_exist_info['tags_id'];
		 * $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		 * $action2 = $this->url->link('services/form/edit', '' . $url2, 'SSL');
		 *
		 *
		 *
		 * if (($this->request->post['design_forms'][0][0][''.TAG_I_FNAME.''] != null && $this->request->post['design_forms'][0][0][''.TAG_I_FNAME.''] != "") && ($this->request->post['design_forms'][0][0][''.TAG_I_LNAME.''] != null && $this->request->post['design_forms'][0][0][''.TAG_I_LNAME.''] != "")) {
		 * if ($this->request->get['forms_id'] == null && $this->request->get['forms_id'] == "") {
		 * if ($this->request->post['client_add_new'] == null && $this->request->post['client_add_new'] == "") {
		 * if ($this->request->post['link_forms_id'] == null && $this->request->post['link_forms_id'] == "") {
		 *
		 * $this->load->model('form/form');
		 *
		 * $fdata = array();
		 *
		 * $fdata['forms_fields_values'] = array(
		 * '' . TAG_EXTID . '' => $this->request->post['design_forms'][0][0][''.TAG_I_EXTID.''],
		 * '' . TAG_SSN . '' => $this->request->post['design_forms'][0][0][''.TAG_I_SSN.''],
		 * '' . TAG_FNAME . '' => $this->request->post['design_forms'][0][0][''.TAG_I_FNAME.''],
		 * '' . TAG_LNAME . '' => $this->request->post['design_forms'][0][0][''.TAG_I_LNAME.'']
		 * );
		 * // 'date_70767270' => $dob111,
		 *
		 * // var_dump($fdata);
		 *
		 * $client_form_info = $this->model_form_form->getscrnneningFormdata($fdata, $this->customer->getId());
		 *
		 * if (! empty($client_form_info)) {
		 * $this->error['warning'] = "Screening list";
		 * $this->error['exit_error'] = '1';
		 * }
		 * }
		 * }
		 * }
		 * }
		 *
		 * }
		 */
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function insert2() {
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'insert2', $this->request->post, 'request' );
		
		$this->load->model ( 'api/encrypt' );
		$cre_array = array ();
		$cre_array ['phone_device_id'] = $this->request->get ['phone_device_id'];
		$cre_array ['facilities_id'] = $this->request->get ['facilities_id'];
		
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
		
		$this->data ['facilitiess'] = array ();
		
		$json = array ();
		
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );
		
		if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
				$json ['warning'] = 'User Pin not valid!';
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
			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
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
		
		if ($this->request->post ['perpetual_checkbox'] == '1') {
			if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
				$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
			}
			if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!';
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
				
				$this->load->model ( 'user/user_group' );
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
				
				$perpetual_task = $user_role_info ['perpetual_task'];
				
				if ($perpetual_task != '1') {
					$json ['warning'] = "You are not authorized to end the task!";
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
		}
		
		if ($this->request->post ['current_enroll_image1'] == "1") {
			$this->load->model ( 'api/facerekognition' );
			$fre_array = array ();
			$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
			$fre_array ['facilities_id'] = $this->request->get ['facilities_id'];
			$fre_array ['user_id'] = $this->request->post ['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
			
			$json ['warning'] = $facerekognition_response ['warning1'];
			
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
			
			$this->load->model ( 'form/form' );
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$formreturn_id = $this->request->get ['formreturn_id'];
			} else {
				$formreturn_id = $this->request->get ['form_parent_id'];
			}
			if($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != ""){
				$this->load->model ( 'api/temporary' );
				$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_forms_id'] );
				
				$tempdata = array ();
				$tempdata = unserialize ( $temporary_info ['data'] );
				
				$editdata = array ();
				$editdata ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$editdata ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$editdata ['is_android'] = $this->request->post ['is_android'];
				} else {
					$editdata ['is_android'] = '1';
				}
				
				
				$archive_forms_id = $this->model_form_form->editFormdata ( $tempdata ['design_forms'], $formreturn_id, $tempdata ['upload_file'], $tempdata ['image'], $tempdata ['signature'], $tempdata ['form_signature'], $tempdata ['is_final'], '', $editdata );
				
				// }
				
				$temporaryinfos = $this->model_api_temporary->gettemporaryparent ( $this->request->get ['archive_forms_id'] );
				$archive_forms_ids = array ();
				if (! empty ( $temporaryinfos )) {
					foreach ( $temporaryinfos as $temporaryinfo ) {
						$tempdata2 = array ();
						$tempdata2 = unserialize ( $temporaryinfo ['data'] );
						
						$editdata = array ();
						$archive_forms_ids = $this->model_form_form->editFormdata ( $tempdata2 ['design_forms'], $temporaryinfo ['id'], $tempdata2 ['upload_file'], $tempdata2 ['image'], $tempdata2 ['signature'], $tempdata2 ['form_signature'], $tempdata2 ['is_final'], '', $editdata );
					}
				}
			}
			
			
			
			
			if ($formreturn_id > 0) {
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				$formdata = unserialize ( $form_info ['design_forms'] );
				
				// var_dump($formdata);
				
				foreach ( $formdata as $key1 => $vals ) {
					foreach ( $vals as $key2 => $v ) {
						foreach ( $v as $key3 => $v3 ) {
							$arrss = explode ( "_1_", $key3 );
							if ($v [$arrss [0] . '_1_add_in_facility'] == '1') {
								if ($arrss [1] == 'facilities_id') {
									
									if ($v [$arrss [0]] != null && $v [$arrss [0]] != "") {
										$form_facilities_id = $v [$arrss [0]];
									}
								}
							}
						}
					}
				}
			}
			
			if ($form_facilities_id != null && $form_facilities_id != "") {
				$facilities_id = $form_facilities_id;
			} else {
				$facilities_id = $this->request->get ['facilities_id'];
			}
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			$this->load->model ( 'setting/timezone' );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
			
			$form_date_added = ( string ) $noteDate;
			
			$pform_info = $this->model_form_form->getFormDatasparent ( $this->request->get ['forms_design_id'], $formreturn_id );
			
			if (! empty ( $pform_info )) {
				if ($pform_info ['form_design_parent_id'] > 0) {
					$forms_design_id = $pform_info ['form_design_parent_id'];
				} else {
					$forms_design_id = $this->request->get ['forms_design_id'];
				}
			} else {
				$forms_design_id = $this->request->get ['forms_design_id'];
			}
			
			// var_dump($forms_design_id);die;
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				
				$this->load->model ( 'createtask/createtask' );
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				
				$formusername = "";
				
				$fromdatas = $this->model_form_form->getFormdata ( $forms_design_id );
				if ($fromdatas ['client_reqired'] == '0') {
					$formdata = unserialize ( $form_info ['design_forms'] );
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							foreach ( $design_form as $key2 => $b ) {
								
								$arrss = explode ( "_1_", $key2 );
								
								if ($arrss [1] == 'tags_id') {
									if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
										$formusername .= ' | ' . $design_form [$arrss [0]];
									}
								}
							}
						}
					}
				}
				
				$formdata = unserialize ( $form_info ['design_forms'] );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							if ($design_form [$arrss [0] . '_1_secure_data'] != '1') {
								if ($arrss [1] == 'add_in_note') {
									if ($b == "1") {
										if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
											$formusername .= ' | ' . $design_form [$arrss [0]];
										}
									}
								}
							}
						}
						if ($design_form [$arrss [0] . '_1_add_in_facility'] == '1') {
							if ($arrss [1] == 'tags_id') {
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$this->load->model ( 'setting/tags' );
										$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
										
										$facilities_id = $tag_info ['facilities_id'];
									}
								}
							}
						}
					}
				}
				
				$formdata = unserialize ( $form_info ['design_forms'] );
				/*
				 * foreach($formdata as $design_forms){
				 * foreach($design_forms as $key=>$design_form){
				 * foreach($design_form as $key2=>$b){
				 *
				 * $arrss = explode("_1_", $key2);
				 * //var_dump($arrss);
				 * //echo "<hr>";
				 *
				 * if($arrss[1] == 'user_id'){
				 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
				 * $formusername .= ' | '.$design_form[$arrss[0]];
				 * }
				 * }
				 *
				 *
				 * if($arrss[1] == 'user_ids'){
				 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
				 * $this->load->model('user/user');
				 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
				 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idsu){
				 *
				 * $user_info = $this->model_user_user->getUserbyupdate($idsu);
				 * $formusername .= ' | '.$user_info['username'];
				 * }
				 * }
				 * //echo "<hr>";
				 * }
				 *
				 *
				 * }
				 * }
				 * }
				 */
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				if (($facilities_info ['is_discharge_form_enable'] == '1') && ($this->request->get ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
					// var_dump($this->request->get['tags_id']);
					
					if (isset ( $this->request->get ['tags_id'] )) {
						$tags_id = $this->request->get ['tags_id'];
					} else {
						$tags_id = $this->request->get ['emp_tag_id'];
					}
					
					$data ['keyword_file'] = DISCHARGE_ICON;
					
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
					
					$notes_description = $keywordData2 ['keyword_name'] . ' | ' . $form_info ['incident_number'] . ' has been added ';
					
					$this->load->model ( 'createtask/createtask' );
					$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tags_id );
					
					if ($alldatas != NULL && $alldatas != "") {
						foreach ( $alldatas as $alldata ) {
							$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
							
							$facilities_idt = $result ['facilityId'];
							$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_idt, '1' );
							
							
							$this->model_createtask_createtask->deteteTask ( $alldata ['id']);
						}
					}
				} else {
					
					if ($forms_design_id == CUSTOME_INTAKEID) {
						
						if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
							
							$this->load->model ( 'setting/tags' );
							$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
							$emp_first_name = $tag_info ['emp_first_name'];
							$emp_tag_id = $tag_info ['emp_tag_id'];
							
							$client_tage = $emp_tag_id . ":" . $emp_first_name;
						} elseif ($forms_design_id == CUSTOME_INTAKEID) {
							
							$formdata = unserialize ( $form_info ['design_forms'] );
							$emp_first_name = $formdata [0] [0] ['' . TAG_FNAME . ''];
							
							$emp_last_name = mb_substr ( $formdata [0] [0] ['' . TAG_LNAME . ''], 0, 1 );
							
							$client_tage = $emp_first_name . ":" . $emp_last_name;
						} elseif ($form_info ['tags_id'] != null && $form_info ['tags_id'] != "0") {
							$this->load->model ( 'setting/tags' );
							$tag_info = $this->model_setting_tags->getTag ( $form_info ['tags_id'] );
							$emp_first_name = $tag_info ['emp_first_name'];
							$emp_tag_id = $tag_info ['emp_tag_id'];
							
							$client_tage = $emp_tag_id . ":" . $emp_first_name;
						}
						
						$notes_description = $client_tage . ' | ' . $form_info ['incident_number'] . ' has been added ' . $formusername;
					} else {
						$notes_description = '' . $formusername;
					}
				}
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$this->request->post ['comments'] = $notes_description . ' ' . $this->request->post ['comments'];
				} else {
					$this->request->post ['comments'] = $notes_description;
				}
				
				$this->request->post ['imgOutput'] = $this->request->post ['signature'];
				
				$result2 = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->get ['task_id'] );
				
				$facilities_idt = $result2 ['facilityId'];
				
				$notesId = $this->model_createtask_createtask->inserttask ( $result2, $this->request->post, $facilities_idt, '' );
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					$this->load->model ( 'notes/notes' );
					
					date_default_timezone_set ( $timezone_info ['timezone_value'] );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
					
					$data = array ();
					
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					$data ['imgOutput'] = $this->request->post ['signature'];
					
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
					$data ['user_id'] = $this->request->post ['user_id'];
					
					$data ['notetime'] = $notetime;
					$data ['note_date'] = $date_added;
					
					$this->load->model ( 'createtask/createtask' );
					
					$this->load->model ( 'setting/keywords' );
					
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $result ['facilityId'] );
					
					$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
					$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
					
					$data ['keyword_file'] = $keywordData13 ['keyword_image'];
					
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
					
					$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
					
					$data ['date_added'] = $date_added;
					$data ['linked_id'] = $result ['linked_id'];
					
					$notesida = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_idt );
					
					$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesida );
					
					if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
						
						date_default_timezone_set ( $timezone_info ['timezone_value'] );
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$this->load->model ( 'notes/tags' );
						$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
						$tadata = array ();
						$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesida, $taginfo ['tags_id'], $update_date, $tadata );
					}
				}
				
				
				
				$this->model_createtask_createtask->deteteTask ( $this->request->get ['task_id']);
				// var_dump($notesId);
				
				if ($form_info ['custom_form_type'] > 0) {
					
					// var_dump($form_info['custom_form_type']);
					
					$this->load->model ( 'setting/activeforms' );
					$formexist_info = $this->model_setting_activeforms->getactiveform2id ( $form_info ['custom_form_type'] );
					
					// var_dump($formexist_info);
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $formexist_info ['keyword_id'] );
					
					$formdesign_info = $this->model_form_form->getFormDatadesign ( $form_info ['custom_form_type'] );
					$relation_keyword_id = $formdesign_info ['relation_keyword_id'];
					
					$this->load->model ( 'setting/keywords' );
					$keyword_infof = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					if ($keyword_info ['relation_keyword_id'] > 0) {
						$keyword_info2 = $this->model_setting_keywords->getkeywordDetail ( $keyword_info ['relation_keyword_id'] );
						
						$this->load->model ( 'notes/notes' );
						$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
						
						$data3 = array ();
						$data3 ['keyword_file'] = $keyword_info2 ['keyword_image'] . ',' . $keyword_infof ['keyword_image'];
						$data3 ['notes_description'] = $noteDetails ['notes_description'];
						
						$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
						
						// var_dump($keyword_info2);
					}
					
					$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET parent_id = '" . $this->request->get ['unotes_id'] . "' WHERE forms_id = '" . ( int ) $formreturn_id . "' ";
					$this->db->query ( $sql122 );
				}
				
				$form_design_info = $this->model_form_form->getFormdata ( $forms_design_id );
				if ($form_design_info ['form_type'] == "Database") {
					if ($formreturn_id != null && $formreturn_id != "") {
						$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $formreturn_id . "'";
						$this->db->query ( $slq1 );
					}
				}
				
				$ttstatus = "1";
				// $timezone_name = $this->request->get['facilities_id'];
				
				$this->load->model ( 'facilities/facilities' );
				
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$this->load->model ( 'setting/timezone' );
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$this->model_createtask_createtask->updateForm ( $notesId, $checklist_status, $ttstatus, $update_date );
				
				$notes_id = $notesId;
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $facilities_id;
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				if (($facilities_info ['is_discharge_form_enable'] == '1') && ($this->request->get ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
					$this->load->model ( 'setting/tags' );
					$this->load->model ( 'resident/resident' );
					$this->model_setting_tags->addcurrentTagarchive ( $tags_id );
					$this->model_setting_tags->updatecurrentTagarchive ( $tags_id, $notesId );
					
					$this->model_resident_resident->updateDischargeTag ( $tags_id, $notesId );
				}
				
				$this->load->model ( 'setting/tags' );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							// var_dump($arrss);
							// echo "<hr>";
							if ($arrss [1] == 'tags_id') {
								// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
								// var_dump($design_form[$arrss[0]]);
								// echo "<hr>";
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										
										$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
										
										// var_dump($tag_info);
										// echo "<hr>";
										$tadata = array ();
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
							}
							
							if ($arrss [1] == 'tags_ids') {
								// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
								if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
									foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$tag_info = $this->model_setting_tags->getTag ( $idst );
										// var_dump($tag_info);
										// echo "<hr>";
										$tadata = array ();
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
								// echo "<hr>";
							}
							
							if ($arrss [1] == 'tagsids') {
								
								if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
									foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$tag_info = $this->model_setting_tags->getTag ( $idst );
										
										$tadata = array ();
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
								// echo "<hr>";
							}
						}
					}
				}
				
				$fdata3 = array ();
				$fdata3 ['notes_id'] = $notes_id;
				$fdata3 ['form_date_added'] = $date_added;
				$fdata3 ['date_added'] = $date_added;
				$fdata3 ['date_updated'] = $date_added;
				$fdata3 ['forms_id'] = $formreturn_id;
				
				$this->model_form_form->updatetaskformnotes ( $fdata3 );
				
				$this->model_form_form->updateformnotes33 ( $fdata3 );
				
				if ($this->request->get ['unotes_id'] != null && $this->request->get ['unotes_id'] != "") {
					
					$this->model_notes_notes->updatenotesparentnotification ( $this->request->get ['unotes_id'], $notes_id );
				}
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				if ($form_info ['is_approval_required'] == '1') {
					if ($form_info ['is_final'] == '0') {
						$ftdata = array ();
						$ftdata ['forms_id'] = $formreturn_id;
						$ftdata ['incident_number'] = $form_info ['incident_number'];
						$ftdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
						$ftdata ['facilities_id'] = $facilities_id;
						
						$this->load->model ( 'createtask/createtask' );
						$this->model_createtask_createtask->createapprovalTak ( $ftdata );
					}
				}
			} else if (($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") && $this->request->get ['new_form'] != "2") {
				
				$this->load->model ( 'setting/tags' );
				
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$data = array ();
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$data ['notetime'] = $notetime;
				$data ['note_date'] = $date_added;
				$data ['facilitytimezone'] = $timezone_name;
				
				$form_data = $this->model_form_form->getFormdata ( $forms_design_id );
				$form_name = $form_data ['form_name'];
				
				$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				
				$formusername = "";
				
				$formdata = unserialize ( $form_info ['design_forms'] );
				
				if ($forms_design_id == CUSTOME_I_INTAKEID) {
					// var_dump($form_info);
					// $formdata = unserialize($form_info['design_forms']);
					
					$date = str_replace ( '-', '/', $formdata [0] [0] ['' . TAG_I_DOB . ''] );
					
					$res22 = explode ( "/", $date );
					
					$fcdata1i = array ();
					$fcdata1i ['emp_first_name'] = $formdata [0] [0] ['' . TAG_I_FNAME . ''];
					$fcdata1i ['emp_middle_name'] = $formdata [0] [0] ['' . TAG_I_MNAME . ''];
					$fcdata1i ['emp_last_name'] = $formdata [0] [0] ['' . TAG_I_LNAME . ''];
					$fcdata1i ['emergency_contact'] = $formdata [0] [0] ['' . TAG_I_PHONE . ''];
					$fcdata1i ['month_1'] = $res22 [0];
					$fcdata1i ['day_1'] = $res22 [1];
					$fcdata1i ['year_1'] = $res22 [2];
					$fcdata1i ['gender'] = $formdata [0] [0] ['' . TAG_I_GENDER . ''];
					$fcdata1i ['emp_extid'] = $formdata [0] [0] ['' . TAG_I_EXTID . ''];
					$fcdata1i ['ssn'] = $formdata [0] [0] ['' . TAG_I_SSN . ''];
					$fcdata1i ['location_address'] = $formdata [0] [0] ['' . TAG_I_ADDRESS . ''];
					$fcdata1i ['date_of_screening'] = $formdata [0] [0] ['' . TAG_I_SCREENING . ''];
					$fcdata1i ['room_id'] = $formdata [0] [0] ['' . TAG_I_ROOM . ''];
					$fcdata1i ['tags_status_in'] = 'Admitted';
					$fcdata1i ['forms_id'] = $this->request->get ['link_forms_id'];
					
					$this->load->model ( 'setting/tags' );
					$this->load->model ( 'setting/tags' );
					if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
						
						$this->model_setting_tags->updatexittag ( $fcdata1i, $facilities_id );
						
						$this->model_setting_tags->editTags ( $this->request->get ['tags_id'], $fcdata1i, $facilities_id );
						
						$tags_id = $this->request->get ['tags_id'];
					} else {
						$tags_id = $this->model_setting_tags->addTags ( $fcdata1i, $facilities_id );
					}
				}
				
				$fromdatas = $this->model_form_form->getFormdata ( $forms_design_id );
				if ($fromdatas ['client_reqired'] == '0') {
					$formdata = unserialize ( $form_info ['design_forms'] );
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							foreach ( $design_form as $key2 => $b ) {
								
								$arrss = explode ( "_1_", $key2 );
								
								if ($arrss [1] == 'tags_id') {
									if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
										$formusername .= ' | ' . $design_form [$arrss [0]];
									}
								}
							}
						}
					}
				}
				
				$formdata = unserialize ( $form_info ['design_forms'] );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							if ($design_form [$arrss [0] . '_1_secure_data'] != '1') {
								if ($arrss [1] == 'add_in_note') {
									if ($b == "1") {
										if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
											$formusername .= ' | ' . $design_form [$arrss [0]];
										}
									}
								}
							}
						}
					}
				}
				/*
				 * foreach($formdata as $design_forms){
				 * foreach($design_forms as $key=>$design_form){
				 * foreach($design_form as $key2=>$b){
				 *
				 * $arrss = explode("_1_", $key2);
				 * //var_dump($arrss);
				 * //echo "<hr>";
				 *
				 * if($arrss[1] == 'user_id'){
				 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
				 * $formusername .= ' | '.$design_form[$arrss[0]];
				 * }
				 * }
				 *
				 *
				 * if($arrss[1] == 'user_ids'){
				 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
				 * $this->load->model('user/user');
				 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
				 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idsu){
				 *
				 * $user_info = $this->model_user_user->getUserbyupdate($idsu);
				 * $formusername .= ' | '.$user_info['username'];
				 * }
				 * }
				 * //echo "<hr>";
				 * }
				 *
				 *
				 * }
				 * }
				 * }
				 */
				if ($forms_design_id == CUSTOME_I_INTAKEID) {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $tags_id );
					$emp_first_name = $tag_info ['emp_first_name'];
					$emp_tag_id = $tag_info ['emp_tag_id'];
					$client_tage = $emp_tag_id . ":" . $emp_first_name;
				} else if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
					$emp_first_name = $tag_info ['emp_first_name'];
					$emp_tag_id = $tag_info ['emp_tag_id'];
					
					$client_tage = $emp_tag_id . ":" . $emp_first_name;
				} elseif ($forms_design_id == CUSTOME_INTAKEID) {
					
					$formdata = unserialize ( $form_info ['design_forms'] );
					$emp_first_name = $formdata [0] [0] ['' . TAG_FNAME . ''];
					
					$emp_last_name = mb_substr ( $formdata [0] [0] ['' . TAG_LNAME . ''], 0, 1 );
					
					$client_tage = $emp_first_name . ":" . $emp_last_name;
				} elseif ($form_info ['tags_id'] != null && $form_info ['tags_id'] != "0") {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $form_info ['tags_id'] );
					$emp_first_name = $tag_info ['emp_first_name'];
					$emp_tag_id = $tag_info ['emp_tag_id'];
					
					$client_tage = $emp_tag_id . ":" . $emp_first_name;
				}
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$comments = ' | ' . $this->request->post ['comments'];
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
						
					$this->load->model ( 'resident/resident' );
				
				
					if ($forms_design_id == CUSTOME_I_INTAKEID) {
						$tags_id = $tags_id;
					} else if (isset ( $this->request->get ['tags_id'] )) {
						$tags_id = $this->request->get ['tags_id'];
					} else {
						$tags_id = $this->request->get ['emp_tag_id'];
					}
				
					$isdata = array();
					$isdata['tag_status_id'] = $this->request->get ['tag_status_id'];
					$isdata['tags_id'] =$tags_id;
					$isdata['facilities_id'] = $facilities_id;
					$isdata['facilitytimezone'] = $timezone_info ['timezone_value'];
					$isdata ['date_added'] = $date_added;
					
					
					$clientstatusinfo = $this->model_resident_resident->updateformstatus ( $isdata );
					
					
					$this->load->model ( 'setting/tags' );
						
					$tag_info = $this->model_setting_tags->getTag ( $tags_id );
						
					$currentstatus_info = $this->model_setting_tags->getTagspreviousstatus ( $tag_info ['role_call'], $date_added, $tag_info ['tags_id'] );
						
					if (! empty ( $currentstatus_info )) {
						if ($currentstatus_info ['parent_date_added'] != null && $currentstatus_info ['parent_date_added'] != "0000-00-00 00:00:00") {
							$data ['parent_date_added'] = $currentstatus_info ['parent_date_added'];
						} else {
							$data ['parent_date_added'] = $date_added;
						}
					} else {
						$data ['parent_date_added'] = $date_added;
					}
					
					$this->load->model ( 'notes/clientstatus' );
					$client_statuses_value = $this->model_notes_clientstatus->getclientstatus ( $this->request->get ['tag_status_id']);
					$data ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
					$data ['denied_forms_id'] = $formreturn_id;
					$data ['tag_status_id'] = $tag_info ['role_call'];
				
				}
				
				if (($facilities_info ['is_discharge_form_enable'] == '1') && ($this->request->get ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
					// var_dump($this->request->get['tags_id']);
					
					if (isset ( $this->request->get ['tags_id'] )) {
						$tags_id = $this->request->get ['tags_id'];
					} else {
						$tags_id = $this->request->get ['emp_tag_id'];
					}
					
					$data ['keyword_file'] = DISCHARGE_ICON;
					
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $form_info ['incident_number'] . ' has been added ';
					
					$this->load->model ( 'createtask/createtask' );
					$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tags_id );
					
					if ($alldatas != NULL && $alldatas != "") {
						foreach ( $alldatas as $alldata ) {
							$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
							$facilities_idt = $result ['facilityId'];
							$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_idt, '1' );
							
							
							$this->model_createtask_createtask->deteteTask ( $alldata ['id'] );
						}
					}
				} else {
					if ($forms_design_id == CUSTOME_I_INTAKEID) {
						$data ['keyword_file'] = INTAKE_ICON;
						
						$this->load->model ( 'setting/keywords' );
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
						
						$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | Admitted -' . $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' has been admitted to ' . $facilities_info ['facility'] . ' ' . $comments;
					} else {
						$data ['notes_description'] = $client_tage . ' | ' . $form_name . ' has been added' . $comments . $formusername . $clientstatusinfo;
					}
				}
				
				$data ['date_added'] = $date_added;
				
				$data ['phone_device_id'] = $this->request->get ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->get ['device_unique_id'];
				$data ['fixed_status_id'] = $this->request->get ['tag_status_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
				
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						if($forms_design_id == '144' || $forms_design_id == '145'){
							
							if($design_form['select_98705590'] == 'Revoked'){
								
								if($tag_info['tags_id'] > 0){
									$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0',fixed_status_id = '0',tag_status_ids='',comments='',multiple_status='0', modify_date = '" . $this->db->escape ( $date_added ) . "' WHERE tags_id = '" . ( int ) $tag_info['tags_id'] . "'" );
								}
							}
						}
					}
				}
				
				$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_facilities_id = '" . $parent_facilities_id . "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
				
				$form_design_info = $this->model_form_form->getFormdata ( $forms_design_id );
				if ($form_design_info ['form_type'] == "Database") {
					if ($formreturn_id != null && $formreturn_id != "") {
						$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $formreturn_id . "'";
						$this->db->query ( $slq1 );
					}
				}
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $facilities_id;
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				if ($forms_design_id == CUSTOME_I_INTAKEID) {
					$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . ( int ) $tags_id . "'" );
					
					if ($tag_info ['forms_id'] > 0) {
						$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $tag_info ['forms_id'] . "'" );
					}
					
					$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $formreturn_id . "'" );
					
					$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . ( int ) $tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . ( int ) $notes_id . "'" );
				}
				
				if (($facilities_info ['is_discharge_form_enable'] == '1') && ($this->request->get ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
					$this->load->model ( 'setting/tags' );
					$this->load->model ( 'resident/resident' );
					$this->model_setting_tags->addcurrentTagarchive ( $tags_id );
					$this->model_setting_tags->updatecurrentTagarchive ( $tags_id, $notes_id );
					
					$this->model_resident_resident->updateDischargeTag ( $tags_id, $notes_id );
				}
				
				$tags_ids_arr = array ();
				$tags_ids_arr [] = $tags_id;
				
				$this->load->model ( 'setting/tags' );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							// var_dump($arrss);
							// echo "<hr>";
							if ($arrss [1] == 'tags_id') {
								// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
								// var_dump($design_form[$arrss[0]]);
								// echo "<hr>";
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										
										$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
										$tags_ids_arr [] = $tag_info ['tags_id'];
										
										// var_dump($tag_info);
										// echo "<hr>";
										$tadata = array ();
										$tadata ['substatus_idscomment'] = '';
										$tadata ['fixed_status_id'] = $this->request->get ['tag_status_id'];
										$tadata ['parent_date_added'] = $data ['parent_date_added'];
										
										$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
										$tadata ['denied_forms_id'] = $formreturn_id;
										$tadata ['tag_status_id'] = $tag_info ['role_call'];
										
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
										
										
										
									}
								}
							}
							
							
							if ($arrss [1] == 'tags_ids') {
								// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
								if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
									foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$tag_info = $this->model_setting_tags->getTag ( $idst );
										$tags_ids_arr [] = $tag_info ['tags_id'];
										// var_dump($tag_info);
										// echo "<hr>";
										$tadata = array ();
										$tadata ['substatus_idscomment'] = '';
										$tadata ['fixed_status_id'] = $this->request->get ['tag_status_id'];
										$tadata ['parent_date_added'] = $data ['parent_date_added'];
										$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
										$tadata ['denied_forms_id'] = $formreturn_id;
										$tadata ['tag_status_id'] = $tag_info ['role_call'];
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
								// echo "<hr>";
							}
							
							if ($arrss [1] == 'tagsids') {
								
								if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
									foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$tag_info = $this->model_setting_tags->getTag ( $idst );
										$tags_ids_arr [] = $tag_info ['tags_id'];
										$tadata = array ();
										$tadata ['substatus_idscomment'] = '';
										$tadata ['fixed_status_id'] = $this->request->get ['tag_status_id'];
										$tadata ['parent_date_added'] = $data ['parent_date_added'];
										$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
										$tadata ['denied_forms_id'] = $formreturn_id;
										$tadata ['tag_status_id'] = $tag_info ['role_call'];
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
								// echo "<hr>";
							}
						}
					}
				}
				
				if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
					
					$this->load->model ( 'user/user' );
					
					if ($data ['user_id'] != null && $data ['user_id'] != "") {
						$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
					} else {
						$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
					}
					$cdata = array (
							'case_file_id' => $this->request->get ['case_file_id'] 
					);
					$this->load->model ( 'resident/casefile' );
					$case_info = $this->model_resident_casefile->getCaseNumber ( $cdata );
					
					// $this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET case_number = '" . $this->session->data ['case_number'] . "' WHERE forms_id = '" . ( int ) $fdata ['formreturn_id'] . "'" );
					$casedata ['case_number'] = $case_info ['case_number'];
					$casedata ['case_status'] = 0;
					$casedata ['forms_ids'] = $formreturn_id;
					$casedata ['notes_id'] = $notes_id;
					// $casedata['tags_ids'] = implode(',',$tags_ids_arr);
					$casedata ['tags_ids'] = $tags_ids_arr ['0']; // implode(',',$tags_ids_arr);
					$casedata ['facilities_id'] = $facilities_id;
					$casedata ['signature'] = $data ['imgOutput'];
					$casedata ['notes_pin'] = $data ['notes_pin'];
					$casedata ['user_id'] = $user_info ['username'];
					
					$this->load->model ( 'resident/casefile' );
					$allforms = $this->model_resident_casefile->insertCasefile ( $casedata );
				}
				
				
				
				
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				$formdesign_info = $this->model_form_form->getFormDatadesign ( $form_info ['custom_form_type'] );
				$relation_keyword_id = $formdesign_info ['relation_keyword_id'];
				
				if ($form_info ['is_approval_required'] == '1') {
					if ($form_info ['is_final'] == '0') {
						$ftdata = array ();
						$ftdata ['forms_id'] = $formreturn_id;
						$ftdata ['incident_number'] = $form_info ['incident_number'];
						$ftdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
						$ftdata ['facilities_id'] = $facilities_id;
						
						$this->load->model ( 'createtask/createtask' );
						$this->model_createtask_createtask->createapprovalTak ( $ftdata );
					}
				}
				
				
				
				if ($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != "") {
					$this->load->model ( 'notes/notes' );
						
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->get['emp_tag_id']);
					$tadata = array ();
						
					$tadata ['substatus_idscomment'] = '';
					$tadata ['fixed_status_id'] = $this->request->get['tag_status_id'];
					$tadata ['parent_date_added'] = $data ['parent_date_added'];
					$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
					$tadata ['denied_forms_id'] = $formreturn_id;
					$tadata ['tag_status_id'] = $tag_info ['role_call'];
					$this->model_notes_notes->updateNotesTag ( $tag_info['emp_tag_id'], $notes_id, $tag_info['tags_id'], $update_date, $tadata );
					
				}
				
				/*
				 * if($relation_keyword_id){
				 * $this->load->model('notes/notes');
				 * $noteDetails = $this->model_notes_notes->getnotes($notes_id);
				 *
				 * $this->load->model('setting/keywords');
				 * $keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
				 *
				 * $data3 = array();
				 * $data3['keyword_file'] = $keyword_info['keyword_image'];
				 * $data3['notes_description'] = $noteDetails['notes_description'];
				 *
				 * $this->model_notes_notes->addactiveNote($data3, $notes_id);
				 * }
				 */
			} elseif ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$notes_id = $this->request->get ['updatenotes_id'];
			} else {
				$notes_id = $this->request->get ['unotes_id'];
			}
			
			
			
			$this->load->model ( 'notes/notes' );
			$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
			$date_added1 = $noteDetails ['date_added'];
			
			if ($this->request->get ['new_form'] == '1') {
				
				if ($notes_id == null && $notes_id == "") {
					
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
					
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					$data ['imgOutput'] = $this->request->post ['signature'];
					
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
					$data ['user_id'] = $this->request->post ['user_id'];
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					$this->load->model ( 'form/form' );
					
					// var_dump($formreturn_id);
					$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
					$custom_form_type = $form_info ['custom_form_type'];
					
					$form_data = $this->model_form_form->getFormdata ( $custom_form_type );
					
					$formusername = "";
					
					$formdata = unserialize ( $form_info ['design_forms'] );
					
					if ($forms_design_id == CUSTOME_I_INTAKEID) {
						// var_dump($form_info);
						// $formdata = unserialize($form_info['design_forms']);
						
						$date = str_replace ( '-', '/', $formdata [0] [0] ['' . TAG_I_DOB . ''] );
						
						$res22 = explode ( "/", $date );
						
						$fcdata1i = array ();
						$fcdata1i ['emp_first_name'] = $formdata [0] [0] ['' . TAG_I_FNAME . ''];
						$fcdata1i ['emp_middle_name'] = $formdata [0] [0] ['' . TAG_I_MNAME . ''];
						$fcdata1i ['emp_last_name'] = $formdata [0] [0] ['' . TAG_I_LNAME . ''];
						$fcdata1i ['emergency_contact'] = $formdata [0] [0] ['' . TAG_I_PHONE . ''];
						$fcdata1i ['month_1'] = $res22 [0];
						$fcdata1i ['day_1'] = $res22 [1];
						$fcdata1i ['year_1'] = $res22 [2];
						$fcdata1i ['gender'] = $formdata [0] [0] ['' . TAG_I_GENDER . ''];
						$fcdata1i ['emp_extid'] = $formdata [0] [0] ['' . TAG_I_EXTID . ''];
						$fcdata1i ['ssn'] = $formdata [0] [0] ['' . TAG_I_SSN . ''];
						$fcdata1i ['location_address'] = $formdata [0] [0] ['' . TAG_I_ADDRESS . ''];
						$fcdata1i ['date_of_screening'] = $formdata [0] [0] ['' . TAG_I_SCREENING . ''];
						$fcdata1i ['room_id'] = $formdata [0] [0] ['' . TAG_I_ROOM . ''];
						$fcdata1i ['tags_status_in'] = 'Admitted';
						$fcdata1i ['forms_id'] = $this->request->get ['link_forms_id'];
						
						$this->load->model ( 'setting/tags' );
						$this->load->model ( 'setting/tags' );
						if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
							
							$this->model_setting_tags->updatexittag ( $fcdata1i, $facilities_id );
							
							$this->model_setting_tags->editTags ( $this->request->get ['tags_id'], $fcdata1i, $facilities_id );
							
							$tags_id = $this->request->get ['tags_id'];
						} else {
							$tags_id = $this->model_setting_tags->addTags ( $fcdata1i, $facilities_id );
						}
					}
					
					$fromdatas = $this->model_form_form->getFormdata ( $forms_design_id );
					if ($fromdatas ['client_reqired'] == '0') {
						$formdata = unserialize ( $form_info ['design_forms'] );
						foreach ( $formdata as $design_forms ) {
							foreach ( $design_forms as $key => $design_form ) {
								foreach ( $design_form as $key2 => $b ) {
									
									$arrss = explode ( "_1_", $key2 );
									
									if ($arrss [1] == 'tags_id') {
										if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
											$formusername .= ' | ' . $design_form [$arrss [0]];
										}
									}
								}
							}
						}
					}
					
					$formdata = unserialize ( $form_info ['design_forms'] );
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							foreach ( $design_form as $key2 => $b ) {
								
								$arrss = explode ( "_1_", $key2 );
								if ($design_form [$arrss [0] . '_1_secure_data'] != '1') {
									if ($arrss [1] == 'add_in_note') {
										if ($b == "1") {
											if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
												$formusername .= ' | ' . $design_form [$arrss [0]];
											}
										}
									}
								}
							}
						}
					}
					/*
					 * foreach($formdata as $design_forms){
					 * foreach($design_forms as $key=>$design_form){
					 * foreach($design_form as $key2=>$b){
					 *
					 * $arrss = explode("_1_", $key2);
					 * //var_dump($arrss);
					 * //echo "<hr>";
					 *
					 * if($arrss[1] == 'user_id'){
					 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
					 * $formusername .= ' | '.$design_form[$arrss[0]];
					 * }
					 * }
					 *
					 *
					 * if($arrss[1] == 'user_ids'){
					 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
					 * $this->load->model('user/user');
					 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
					 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idsu){
					 *
					 * $user_info = $this->model_user_user->getUserbyupdate($idsu);
					 * $formusername .= ' | '.$user_info['username'];
					 * }
					 * }
					 * //echo "<hr>";
					 * }
					 *
					 *
					 * }
					 * }
					 * }
					 */
					
					// var_dump($formusername);
					if ($forms_design_id == CUSTOME_I_INTAKEID) {
						$this->load->model ( 'setting/tags' );
						$tag_info = $this->model_setting_tags->getTag ( $tags_id );
						$emp_first_name = $tag_info ['emp_first_name'];
						$emp_tag_id = $tag_info ['emp_tag_id'];
						$client_tage = $emp_tag_id . ":" . $emp_first_name;
					} else if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
						
						$this->load->model ( 'setting/tags' );
						$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
						$emp_first_name = $tag_info ['emp_first_name'];
						$emp_tag_id = $tag_info ['emp_tag_id'];
						
						$client_tage = $emp_tag_id . ":" . $emp_first_name;
					} elseif ($forms_design_id == CUSTOME_INTAKEID) {
						// $form_info = $this->model_form_form->getFormDatas($formreturn_id);
						$formdata = unserialize ( $form_info ['design_forms'] );
						$emp_first_name = $formdata [0] [0] ['' . TAG_FNAME . ''];
						
						$emp_last_name = mb_substr ( $formdata [0] [0] ['' . TAG_LNAME . ''], 0, 1 );
						
						$client_tage = $emp_first_name . ":" . $emp_last_name;
					} elseif ($form_info ['tags_id'] != null && $form_info ['tags_id'] != "0") {
						$this->load->model ( 'setting/tags' );
						$tag_info = $this->model_setting_tags->getTag ( $form_info ['tags_id'] );
						$emp_first_name = $tag_info ['emp_first_name'];
						$emp_tag_id = $tag_info ['emp_tag_id'];
						
						$client_tage = $emp_tag_id . ":" . $emp_first_name;
					}
					
					$this->load->model ( 'facilities/facilities' );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					
					
					if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
					
						$this->load->model ( 'resident/resident' );
						
						
						if ($forms_design_id == CUSTOME_I_INTAKEID) {
							$tags_id = $tags_id;
						} else if (isset ( $this->request->get ['tags_id'] )) {
							$tags_id = $this->request->get ['tags_id'];
						} else {
							$tags_id = $this->request->get ['emp_tag_id'];
						}
				
						$isdata = array();
						$isdata['tag_status_id'] = $this->request->get ['tag_status_id'];
						$isdata['tags_id'] =$tags_id;
						$isdata['facilities_id'] = $facilities_id;
						$isdata['facilitytimezone'] = $timezone_info ['timezone_value'];
						$isdata ['date_added'] = $date_added;
						$clientstatusinfo = $this->model_resident_resident->updateformstatus ( $isdata );
						
						$this->load->model ( 'setting/tags' );
							
						$tag_info = $this->model_setting_tags->getTag ($tags_id);
							
						$currentstatus_info = $this->model_setting_tags->getTagspreviousstatus ( $tag_info ['role_call'], $date_added, $tag_info ['tags_id'] );
							
						if (! empty ( $currentstatus_info )) {
							if ($currentstatus_info ['parent_date_added'] != null && $currentstatus_info ['parent_date_added'] != "0000-00-00 00:00:00") {
								$data ['parent_date_added'] = $currentstatus_info ['parent_date_added'];
							} else {
								$data ['parent_date_added'] = $date_added;
							}
						} else {
							$data ['parent_date_added'] = $date_added;
						}
						
						$this->load->model ( 'notes/clientstatus' );
						$client_statuses_value = $this->model_notes_clientstatus->getclientstatus ( $this->request->get ['tag_status_id']);
						$data ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
						$data ['denied_forms_id'] = $formreturn_id;
						$data ['tag_status_id'] = $tag_info ['role_call'];
						
					}
					
					if (($facilities_info ['is_discharge_form_enable'] == '1') && ($this->request->get ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
						// var_dump($this->request->get['tags_id']);
						
						if ($forms_design_id == CUSTOME_I_INTAKEID) {
							$tags_id = $tags_id;
						} else if (isset ( $this->request->get ['tags_id'] )) {
							$tags_id = $this->request->get ['tags_id'];
						} else {
							$tags_id = $this->request->get ['emp_tag_id'];
						}
						
						$data ['keyword_file'] = DISCHARGE_ICON;
						
						$this->load->model ( 'setting/keywords' );
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
						
						$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $form_info ['incident_number'] . ' has been added ' . $clientstatusinfo;
						
						$this->load->model ( 'createtask/createtask' );
						$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tags_id );
						
						if ($alldatas != NULL && $alldatas != "") {
							foreach ( $alldatas as $alldata ) {
								$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
								$facilities_idt = $result ['facilityId'];
								$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_idt, '1' );
								$this->model_createtask_createtask->deteteTask ( $alldata ['id'] );
							}
						}
					} else {
						
						if ($forms_design_id == CUSTOME_I_INTAKEID) {
							$data ['keyword_file'] = INTAKE_ICON;
							
							$this->load->model ( 'setting/keywords' );
							$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
							
							$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | Admitted -' . $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' has been admitted to ' . $facilities_info ['facility'] . ' ' . $comments;
						} else {
							$data ['notes_description'] = $client_tage . ' | ' . $form_data ['form_name'] . ' has been added ' . $comments . $formusername . $clientstatusinfo;
						}
					}
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					$data ['fixed_status_id'] = $this->request->get ['tag_status_id'];
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					
					// var_dump($data);
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
					
					$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_facilities_id = '" . $parent_facilities_id . "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
					
					$form_design_info = $this->model_form_form->getFormdata ( $forms_design_id );
					if ($form_design_info ['form_type'] == "Database") {
						if ($formreturn_id != null && $formreturn_id != "") {
							$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $formreturn_id . "'";
							$this->db->query ( $slq1 );
						}
					}
					
					
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							if($forms_design_id == '144' || $forms_design_id == '145'){
								
								if($design_form['select_98705590'] == 'Revoked'){
									
									if($tag_info['tags_id'] > 0){
										$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0',fixed_status_id = '0',tag_status_ids='',comments='',multiple_status='0' modify_date = '" . $this->db->escape ( $date_added ) . "' WHERE tags_id = '" . ( int ) $tag_info['tags_id'] . "'" );
									}
								}
							}
						}
					}
					
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 = array ();
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $facilities_id;
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					// var_dump($fre_array2);
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
					
					if ($forms_design_id == CUSTOME_I_INTAKEID) {
						$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . ( int ) $tags_id . "'" );
						
						if ($tag_info ['forms_id'] > 0) {
							$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $tag_info ['forms_id'] . "'" );
						}
						
						$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $formreturn_id . "'" );
						
						$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . ( int ) $tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . ( int ) $notes_id . "'" );
					}
					
					if (($facilities_info ['is_discharge_form_enable'] == '1') && ($this->request->get ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
						$this->load->model ( 'setting/tags' );
						$this->load->model ( 'resident/resident' );
						$this->model_setting_tags->addcurrentTagarchive ( $tags_id );
						$this->model_setting_tags->updatecurrentTagarchive ( $tags_id, $notes_id );
						
						$this->model_resident_resident->updateDischargeTag ( $tags_id, $notes_id );
					}
					
					$this->load->model ( 'setting/tags' );
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							foreach ( $design_form as $key2 => $b ) {
								
								$arrss = explode ( "_1_", $key2 );
								// var_dump($arrss);
								// echo "<hr>";
								if ($arrss [1] == 'tags_id') {
									// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
									// var_dump($design_form[$arrss[0]]);
									// echo "<hr>";
									if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
										if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
											
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											
											$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
											
											// var_dump($tag_info);
											// echo "<hr>";
											$tadata = array ();
											$tadata ['substatus_idscomment'] = '';
											$tadata ['fixed_status_id'] = $this->request->get['tag_status_id'];
											$tadata ['parent_date_added'] = $data ['parent_date_added'];
											$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
											$tadata ['denied_forms_id'] = $formreturn_id;
											$tadata ['tag_status_id'] = $tag_info ['role_call'];
											$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
											
											
										}
									}
								}
								
								
								
								if ($arrss [1] == 'tags_ids') {
									// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
									if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
										foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
											
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											$tag_info = $this->model_setting_tags->getTag ( $idst );
											// var_dump($tag_info);
											// echo "<hr>";
											$tadata = array ();
											$tadata ['substatus_idscomment'] = '';
											$tadata ['fixed_status_id'] = $this->request->get['tag_status_id'];
											$tadata ['parent_date_added'] = $data ['parent_date_added'];
											$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
											$tadata ['denied_forms_id'] = $formreturn_id;
											$tadata ['tag_status_id'] = $tag_info ['role_call'];
											$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
										}
									}
									// echo "<hr>";
								}
								
								if ($arrss [1] == 'tagsids') {
									
									if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
										foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
											
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											$tag_info = $this->model_setting_tags->getTag ( $idst );
											
											$tadata = array ();
											$tadata ['substatus_idscomment'] = '';
											$tadata ['fixed_status_id'] = $this->request->get['tag_status_id'];
											$tadata ['parent_date_added'] = $data ['parent_date_added'];
											$tadata ['out_from_cell'] = $client_statuses_value ['out_from_cell'];
											$tadata ['denied_forms_id'] = $formreturn_id;
											$tadata ['tag_status_id'] = $tag_info ['role_call'];
											$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
										}
									}
									// echo "<hr>";
								}
							}
						}
					}
					
					if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
						
						$this->load->model ( 'user/user' );
						
						if ($data ['user_id'] != null && $data ['user_id'] != "") {
							$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
						} else {
							$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
						}
						$cdata = array (
								'case_file_id' => $this->request->get ['case_file_id'] 
						);
						$this->load->model ( 'resident/casefile' );
						$case_info = $this->model_resident_casefile->getCaseNumber ( $cdata );
						
						// $this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET case_number = '" . $this->session->data ['case_number'] . "' WHERE forms_id = '" . ( int ) $fdata ['formreturn_id'] . "'" );
						$casedata ['case_number'] = $case_info ['case_number'];
						$casedata ['case_status'] = 0;
						$casedata ['forms_ids'] = $formreturn_id;
						$casedata ['notes_id'] = $notes_id;
						// $casedata['tags_ids'] = implode(',',$tags_ids_arr);
						$casedata ['tags_ids'] = $tags_ids_arr ['0']; // implode(',',$tags_ids_arr);
						$casedata ['facilities_id'] = $facilities_id;
						$casedata ['signature'] = $data ['imgOutput'];
						$casedata ['notes_pin'] = $data ['notes_pin'];
						$casedata ['user_id'] = $user_info ['username'];
						
						$this->load->model ( 'resident/casefile' );
						$allforms = $this->model_resident_casefile->insertCasefile ( $casedata );
					}
					if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
						
						
					}
					
					if ($form_info ['is_approval_required'] == '1') {
						if ($form_info ['is_final'] == '0') {
							$ftdata = array ();
							$ftdata ['forms_id'] = $formreturn_id;
							$ftdata ['incident_number'] = $form_info ['incident_number'];
							$ftdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
							$ftdata ['facilities_id'] = $facilities_id;
							
							$this->load->model ( 'createtask/createtask' );
							$this->model_createtask_createtask->createapprovalTak ( $ftdata );
						}
					}
				}
				
				$fdata3 = array ();
				$fdata3 ['notes_id'] = $notes_id;
				$fdata3 ['form_date_added'] = $date_added;
				$fdata3 ['date_added'] = $date_added;
				$fdata3 ['date_updated'] = $date_added;
				$fdata3 ['forms_id'] = $formreturn_id;
				
				$fdata3 ['user_id'] = $this->request->post ['user_id'];
				$fdata3 ['signature'] = $this->request->post ['signature'];
				$fdata3 ['notes_pin'] = $this->request->post ['notes_pin'];
				$fdata3 ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->model_form_form->updatetaskformnotes ( $fdata3 );
				
				$this->model_form_form->updateformnotes33 ( $fdata3 );
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$this->model_form_form->updateformstags ( $this->request->get ['tags_id'], $formreturn_id );
				}
			} else {
				
				$fdata3 = array ();
				
				$fdata3 ['user_id'] = $this->request->post ['user_id'];
				$fdata3 ['signature'] = $this->request->post ['signature'];
				$fdata3 ['notes_pin'] = $this->request->post ['notes_pin'];
				$fdata3 ['notes_type'] = $this->request->post ['notes_type'];
				
				$fdata3 ['form_date_added'] = $date_added;
				$fdata3 ['date_added'] = $date_added;
				$fdata3 ['date_updated'] = $date_added;
				$fdata3 ['forms_id'] = $formreturn_id;
				
				$this->model_form_form->updatetaskformnotes ( $fdata3 );
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					
					$this->model_form_form->updateformstags ( $this->request->get ['tags_id'], $formreturn_id );
				}
				
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$comments = ' | ' . $this->request->post ['comments'];
				}
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				
				$formusername = "";
				$formdata = unserialize ( $form_info ['design_forms'] );
				
				if ($forms_design_id == CUSTOME_I_INTAKEID) {
					
					// $formdata = unserialize($form_info['design_forms']);
					
					$date = str_replace ( '-', '/', $formdata [0] [0] ['' . TAG_I_DOB . ''] );
					
					$res22 = explode ( "/", $date );
					
					$fcdata1i = array ();
					$fcdata1i ['emp_first_name'] = $formdata [0] [0] ['' . TAG_I_FNAME . ''];
					$fcdata1i ['emp_middle_name'] = $formdata [0] [0] ['' . TAG_I_MNAME . ''];
					$fcdata1i ['emp_last_name'] = $formdata [0] [0] ['' . TAG_I_LNAME . ''];
					$fcdata1i ['emergency_contact'] = $formdata [0] [0] ['' . TAG_I_PHONE . ''];
					$fcdata1i ['month_1'] = $res22 [0];
					$fcdata1i ['day_1'] = $res22 [1];
					$fcdata1i ['year_1'] = $res22 [2];
					$fcdata1i ['gender'] = $formdata [0] [0] ['' . TAG_I_GENDER . ''];
					$fcdata1i ['emp_extid'] = $formdata [0] [0] ['' . TAG_I_EXTID . ''];
					$fcdata1i ['ssn'] = $formdata [0] [0] ['' . TAG_I_SSN . ''];
					$fcdata1i ['location_address'] = $formdata [0] [0] ['' . TAG_I_ADDRESS . ''];
					$fcdata1i ['date_of_screening'] = $formdata [0] [0] ['' . TAG_I_SCREENING . ''];
					$fcdata1i ['room_id'] = $formdata [0] [0] ['' . TAG_I_ROOM . ''];
					
					$this->load->model ( 'setting/tags' );
					$this->model_setting_tags->editTags ( $form_info ['tags_id'], $fcdata1i, $facilities_id );
				}
				
				$fromdatas = $this->model_form_form->getFormdata ( $forms_design_id );
				if ($fromdatas ['client_reqired'] == '0') {
					$formdata = unserialize ( $form_info ['design_forms'] );
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							foreach ( $design_form as $key2 => $b ) {
								
								$arrss = explode ( "_1_", $key2 );
								
								if ($arrss [1] == 'tags_id') {
									if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
										$formusername .= ' | ' . $design_form [$arrss [0]];
									}
								}
							}
						}
					}
				}
				
				$formdata = unserialize ( $form_info ['design_forms'] );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							if ($design_form [$arrss [0] . '_1_secure_data'] != '1') {
								if ($arrss [1] == 'add_in_note') {
									if ($b == "1") {
										if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
											$formusername .= ' | ' . $design_form [$arrss [0]];
										}
									}
								}
							}
						}
					}
				}
				
				/*
				 * foreach($formdata as $design_forms){
				 * foreach($design_forms as $key=>$design_form){
				 * foreach($design_form as $key2=>$b){
				 *
				 * $arrss = explode("_1_", $key2);
				 * //var_dump($arrss);
				 * //echo "<hr>";
				 *
				 * if($arrss[1] == 'user_id'){
				 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
				 * $formusername .= ' | '.$design_form[$arrss[0]];
				 * }
				 * }
				 *
				 *
				 * if($arrss[1] == 'user_ids'){
				 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
				 * $this->load->model('user/user');
				 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
				 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idsu){
				 *
				 * $user_info = $this->model_user_user->getUserbyupdate($idsu);
				 * $formusername .= ' | '.$user_info['username'];
				 * }
				 * }
				 * //echo "<hr>";
				 * }
				 *
				 *
				 * }
				 * }
				 * }
				 */
				
				$custom_form_type = $form_info ['custom_form_type'];
				
				$form_data = $this->model_form_form->getFormdata ( $custom_form_type );
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
					$emp_first_name = $tag_info ['emp_first_name'];
					$emp_tag_id = $tag_info ['emp_tag_id'];
					
					$client_tage = $emp_tag_id . ":" . $emp_first_name;
				} elseif ($forms_design_id == CUSTOME_INTAKEID) {
					// $form_info = $this->model_form_form->getFormDatas($formreturn_id);
					$formdata = unserialize ( $form_info ['design_forms'] );
					$emp_first_name = $formdata [0] [0] ['' . TAG_FNAME . ''];
					
					$emp_last_name = mb_substr ( $formdata [0] [0] ['' . TAG_LNAME . ''], 0, 1 );
					
					$client_tage = $emp_first_name . ":" . $emp_last_name;
				} elseif ($form_info ['tags_id'] != null && $form_info ['tags_id'] != "0") {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $form_info ['tags_id'] );
					$emp_first_name = $tag_info ['emp_first_name'];
					$emp_tag_id = $tag_info ['emp_tag_id'];
					
					$client_tage = $emp_tag_id . ":" . $emp_first_name;
				}
				
				$this->load->model ( 'createtask/createtask' );
				$result2 = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->get ['task_id'] );
				
				if ($result2 ['linked_id'] > 0) {
					$this->load->model ( 'notes/notes' );
					$noteDetail = $this->model_notes_notes->getnotes ( $result2 ['linked_id'] );
					
					$start_date = new DateTime ( $noteDetail ['date_added'] );
					$since_start = $start_date->diff ( new DateTime ( $date_added ) );
					
					$caltime = "";
					$status_total_time = 0;
					
					if ($since_start->y > 0) {
						$caltime .= $since_start->y . ' years ';
						$status_total_time = 60 * 24 * 365 * $since_start->y;
					}
					
					if ($since_start->m > 0) {
						$caltime .= $since_start->m . ' months ';
						$status_total_time += 60 * 24 * 30 * $since_start->m;
					}
					
					if ($since_start->d > 0) {
						$caltime .= $since_start->d . ' days ';
						$status_total_time += 60 * 24 * $since_start->d;
					}
					
					if ($since_start->h > 0) {
						$caltime .= $since_start->h . ' hours ';
						$status_total_time += 60 * $since_start->h;
					}
					
					if ($since_start->i > 0) {
						$caltime .= $since_start->i . ' minutes ';
						$status_total_time += $since_start->i;
					}
					
					$this->load->model ( 'facilities/facilities' );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $result2 ['target_facilities_id'] );
					
					foreach ( $formdata as $design_forms ) {
						foreach ( $design_forms as $key => $design_form ) {
							foreach ( $design_form as $key2 => $b ) {
								
								$arrss = explode ( "_1_", $key2 );
								if ($design_form [$arrss [0] . '_1_add_in_facility'] == '1') {
									if ($arrss [1] == 'facilities_id') {
										if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
											$form_facilities_id = $design_form [$arrss [0]];
											
											$formfacilities_info = $this->model_facilities_facilities->getfacilities ( $form_facilities_id );
											
											$formfacilities_name = $formfacilities_info ['facility'];
										}
									}
								}
							}
						}
					}
					
					$frmname = $facilities_info ['facility'] . ' to ' . $formfacilities_name;
					
					$form_name = $client_tage . ' | ' . $form_data ['form_name'] . ' Completed in ' . $caltime . ' ' . $frmname . ' ' . $formusername;
					
					$data ['notes_description'] = $form_name . $comments;
				} else {
					$data ['notes_description'] = $client_tage . ' | ' . $form_data ['form_name'] . ' updated ' . $comments . $formusername;
				}
				
				$data ['status_total_time'] = $status_total_time;
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
				
				
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						if($forms_design_id == '144' || $forms_design_id == '145'){
							
							if($design_form['select_98705590'] == 'Revoked'){
								
								if($tag_info['tags_id'] > 0){
									$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0',fixed_status_id = '0',tag_status_ids='',comments='',multiple_status='0', modify_date = '" . $this->db->escape ( $date_added ) . "' WHERE tags_id = '" . ( int ) $tag_info['tags_id'] . "'" );
								}
							}
						}
					}
				}
				
				if ($form_info ['custom_form_type'] > 0) {
					
					// var_dump($form_info['custom_form_type']);
					
					$this->load->model ( 'setting/activeforms' );
					$formexist_info = $this->model_setting_activeforms->getactiveform2id ( $form_info ['custom_form_type'] );
					
					// var_dump($formexist_info);
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $formexist_info ['keyword_id'] );
					
					$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['form_parent_id'] );
					$formdesign_info = $this->model_form_form->getFormDatadesign ( $form_info ['custom_form_type'] );
					$relation_keyword_id = $formdesign_info ['relation_keyword_id'];
					
					$this->load->model ( 'setting/keywords' );
					$keyword_infof = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					if ($keyword_info ['relation_keyword_id'] > 0) {
						$keyword_info2 = $this->model_setting_keywords->getkeywordDetail ( $keyword_info ['relation_keyword_id'] );
						
						$this->load->model ( 'notes/notes' );
						$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
						
						$data3 = array ();
						$data3 ['keyword_file'] = $keyword_info2 ['keyword_image'] . ',' . $keyword_infof ['keyword_image'];
						$data3 ['notes_description'] = $noteDetails ['notes_description'];
						
						$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
						
						// var_dump($keyword_info2);
					}
					
					$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET parent_id = '" . $fdata ['notes_id'] . "' WHERE forms_id = '" . ( int ) $this->request->get ['form_parent_id'] . "' ";
					$this->db->query ( $sql122 );
				}
				
				$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_facilities_id = '" . $parent_facilities_id . "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $facilities_id;
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->model_notes_notes->updatenotesparentnotification ( $this->request->get ['unotes_id'], $notes_id );
				
				$this->load->model ( 'setting/tags' );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							// var_dump($arrss);
							// echo "<hr>";
							if ($arrss [1] == 'tags_id') {
								
								// var_dump($design_form[$arrss[0]]);
								// echo "<hr>";
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										
										$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
										
										// var_dump($tag_info);
										// echo "<hr>";
										$tadata = array ();
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
										
										
										
									}
								}
							}
							
							
							
							
							if ($arrss [1] == 'tags_ids') {
								// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
								if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
									foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$tag_info = $this->model_setting_tags->getTag ( $idst );
										// var_dump($tag_info);
										// echo "<hr>";
										$tadata = array ();
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
								// echo "<hr>";
							}
							
							if ($arrss [1] == 'tagsids') {
								
								if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
									foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
										
										$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$tag_info = $this->model_setting_tags->getTag ( $idst );
										
										$tadata = array ();
										$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
									}
								}
								// echo "<hr>";
							}
						}
					}
				}
				
				/*
				 * if($forms_design_id == CUSTOME_INTAKEID){
				 * $archive_forms_id = $this->request->get['archive_forms_id'];
				 *
				 * //var_dump($archive_forms_id);
				 * }else{
				 */
				
				
				
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$fdata34 = array ();
				$fdata34 ['notes_id'] = $notes_id;
				$fdata34 ['archive_notes_id'] = $form_info ['notes_id'];
				$fdata34 ['archive_forms_id'] = $archive_forms_id;
				$fdata34 ['archive_forms_ids'] = $archive_forms_ids;
				$fdata34 ['forms_id'] = $formreturn_id;
				$fdata34 ['update_date'] = $update_date;
				
				$this->model_form_form->updateformnotesinfo ( $fdata34 );
				
				$this->model_form_form->updateformnotesinfo2 ( $fdata34 );
				if ($forms_design_id != CUSTOME_INTAKEID) {
					$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_forms_id'] );
				}
				// var_dump($form_info);
				
				$this->load->model ( 'notes/notes' );
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				/*
				 * $fdata2 = array();
				 * $fdata2['forms_id'] = $formreturn_id;
				 * $fdata2['emp_tag_id'] = $this->request->post['emp_tag_id'];
				 * $fdata2['tags_id'] = $this->request->post['tags_id'];
				 * $fdata2['update_date'] = $update_date;
				 * $fdata2['notes_id'] = $notes_id;
				 *
				 * $this->model_form_form->updateform2($form_info, $fdata2);
				 */
			}
			
			if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
				
				$this->model_form_form->updateformstags ( $this->request->get ['emp_tag_id'], $formreturn_id );
			}
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			$update_date2 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->model_notes_notes->updatedatecount ( $notes_id, $update_date2 );
			
			$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
			
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$this->load->model ( 'notes/notes' );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->load->model ( 'notes/tags' );
				$taginfo = $this->model_notes_tags->getTag ( $this->request->post ['tags_id'] );
				
				$tadata = array ();
				$tadata ['substatus_idscomment'] = '';
				$tadata ['fixed_status_id'] = $this->request->get['tag_status_id'];
				$tadata ['parent_date_added'] = $data ['parent_date_added'];
				$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date, $tadata );
				
				$fdata = array ();
				$fdata ['forms_id'] = $formreturn_id;
				$fdata ['emp_tag_id'] = $taginfo ['emp_tag_id'];
				$fdata ['tags_id'] = $taginfo ['tags_id'];
				$fdata ['update_date'] = $update_date;
				
				$this->load->model ( 'form/form' );
				$this->model_form_form->updateformTag ( $fdata );
				
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						if($forms_design_id == '144' || $forms_design_id == '145'){
							
							if($design_form['select_98705590'] == 'Revoked'){
								
								if($taginfo['tags_id'] > 0){
									$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0',fixed_status_id = '0',tag_status_ids='',comments='',multiple_status='0', modify_date = '" . $this->db->escape ( $update_date ) . "' WHERE tags_id = '" . ( int ) $taginfo['tags_id'] . "'" );
								}
							}
						}
					}
				}
				
			} else if ($form_info ['tags_id']) {
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->load->model ( 'notes/tags' );
				$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
				$tadata = array ();
				$tadata ['substatus_idscomment'] = '';
				$tadata ['fixed_status_id'] = $this->request->get['tag_status_id'];
				$tadata ['parent_date_added'] = $data ['parent_date_added'];
				$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date, $tadata );
				
				
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						if($forms_design_id == '144' || $forms_design_id == '145'){
							
							if($design_form['select_98705590'] == 'Revoked'){
								
								if($taginfo['tags_id'] > 0){
									$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0',fixed_status_id = '0',tag_status_ids='',comments='',multiple_status='0', modify_date = '" . $this->db->escape ( $update_date ) . "' WHERE tags_id = '" . ( int ) $taginfo['tags_id'] . "'" );
								}
							}
						}
					}
				}
			}
			
			if ($this->request->get ['new_form'] != '2') {
				$this->load->model ( 'form/form' );
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				$formdesign_info = $this->model_form_form->getFormDatadesign ( $form_info ['custom_form_type'] );
				$relation_keyword_id = $formdesign_info ['relation_keyword_id'];
				
				if ($relation_keyword_id) {
					$this->load->model ( 'notes/notes' );
					$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					$data3 = array ();
					$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
					$data3 ['notes_description'] = $noteDetails ['notes_description'];
					
					$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
				}
			}
			
			$this->model_notes_notes->updatenoteform ( $notes_id );
			
			if ($forms_design_id == CUSTOME_INTAKEID) {
				
				// $notes_id = $this->request->get['notes_id'];
				/*
				 * $this->load->model('resident/resident');
				 * $tags_form_info = $this->model_resident_resident->get_formbynotesid($notes_id);
				 *
				 * $tags_id = $tags_form_info['tags_id'];
				 */
				
				$form_info = $this->model_form_form->getFormDatas ( $formreturn_id );
				if ($form_info ['tags_id'] != null && $form_info ['tags_id'] != "0") {
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$fdata1 = array ();
					$fdata1 ['design_forms'] = $form_info ['design_forms'];
					$fdata1 ['form_description'] = $form_info ['form_description'];
					$fdata1 ['rules_form_description'] = $form_info ['rules_form_description'];
					$fdata1 ['date_updated'] = $date_added;
					$fdata1 ['upload_file'] = $form_info ['upload_file'];
					$fdata1 ['form_signature'] = $form_info ['form_signature'];
					$fdata1 ['tags_id'] = $form_info ['tags_id'];
					
					$this->model_form_form->updateforminfo ( $fdata1 );
					
					$tags_id = $form_info ['tags_id'];
					$formdata = unserialize ( $form_info ['design_forms'] );
					
					$emp_first_name = $formdata [0] [0] ['' . TAG_FNAME . ''];
					$emp_middle_name = $formdata [0] [0] ['' . TAG_MNAME . ''];
					$emp_last_name = $formdata [0] [0] ['' . TAG_LNAME . ''];
					
					$privacy = '';
					$sort_order = '0';
					$status = '1';
					$doctor_name = '';
					$emergency_contact = $formdata [0] [0] ['' . TAG_PHONE . ''];
					
					$date = str_replace ( '-', '/', $formdata [0] [0] ['' . TAG_DOB . ''] );
					
					$res = explode ( "/", $date );
					$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
					
					$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
					
					if ($formdata [0] [0] ['' . TAG_AGE . '']) {
						$age = $formdata [0] [0] ['' . TAG_AGE . ''];
					} else {
						$age = (date ( 'Y' ) - date ( 'Y', strtotime ( $dob ) ));
					}
					$medication = '';
					$locations_id = '';
					$facilities_id = $facilities_id;
					$upload_file = $form_info ['upload_file'];
					$tags_pin = '';
					
					/*
					 * if($formdata[0][0][''.TAG_GENDER.''] == 'Male'){
					 * $gender = '1';
					 * }
					 * if($formdata[0][0][''.TAG_GENDER.''] == 'Female'){
					 * $gender = '2';
					 * }
					 *
					 * if($formdata[0][0][''.TAG_GENDER.''] == 'Inmate'){
					 * $gender = '1';
					 * }
					 *
					 * if($formdata[0][0][''.TAG_GENDER.''] == 'Patient'){
					 * $gender = '1';
					 * }
					 * if($formdata[0][0][''.TAG_GENDER.''] == 'Other'){
					 * $gender = '1';
					 * }
					 *
					 *
					 * if($formdata[''.TAG_GENDER.''] == ''){
					 * $gender = '1';
					 * }
					 */
					
					$emp_extid = $formdata [0] [0] ['' . TAG_EXTID . ''];
					$ssn = $formdata [0] [0] ['' . TAG_SSN . ''];
					$location_address = $formdata [0] [0] ['' . TAG_ADDRESS . ''];
					// $city = $formdata[0][0]['text_36668004'];
					// $state = $formdata[0][0]['text_49932949'];
					// $zipcode = $formdata[0][0]['text_64928499'];
					
					$fcdata1 = array ();
					$fcdata1 ['emp_first_name'] = $emp_first_name;
					$fcdata1 ['emp_middle_name'] = $emp_middle_name;
					$fcdata1 ['emp_last_name'] = $emp_last_name;
					$fcdata1 ['privacy'] = $privacy;
					$fcdata1 ['sort_order'] = $sort_order;
					$fcdata1 ['status'] = $status;
					$fcdata1 ['doctor_name'] = $doctor_name;
					$fcdata1 ['emergency_contact'] = $emergency_contact;
					$fcdata1 ['dob'] = $dob;
					$fcdata1 ['medication'] = $medication;
					$fcdata1 ['locations_id'] = $locations_id;
					$fcdata1 ['facilities_id'] = $facilities_id;
					$fcdata1 ['upload_file'] = $upload_file;
					$fcdata1 ['tags_pin'] = $tags_pin;
					$fcdata1 ['gender'] = $formdata [0] [0] ['' . TAG_GENDER . ''];
					$fcdata1 ['age'] = $age;
					$fcdata1 ['emp_extid'] = $emp_extid;
					$fcdata1 ['ssn'] = $ssn;
					$fcdata1 ['location_address'] = $location_address;
					$fcdata1 ['city'] = $city;
					$fcdata1 ['state'] = $state;
					$fcdata1 ['zipcode'] = $zipcode;
					$fcdata1 ['tags_id'] = $tags_id;
					
					$this->load->model ( 'setting/tags' );
					$this->model_setting_tags->updatetagsinfo ( $fcdata1 );
				}
			}
			
			$this->data ['facilitiess'] [] = array (
					'warning' => '1',
					'formreturn_id' => $formreturn_id,
					'notes_id' => $notes_id,
					'facilities_id' => $facilities_id 
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
	}
	public function edit() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['forms_id'] = $this->request->get ['forms_id'];
		if ($this->request->post ['form_submit'] == '1' && $this->validateForm ()) {
			$data2 = array ();
			$data2 ['forms_design_id'] = $this->request->get ['forms_design_id'];
			// $data2['notes_id'] = $this->request->get['notes_id'];
			$data2 ['facilities_id'] = $this->request->get ['facilities_id'];
			
			if ($this->request->post ['forms_design_id'] == CUSTOME_INTAKEID) {
				$editdata = array ();
				$editdata ['phone_device_id'] = $this->request->get ['phone_device_id'];
				$editdata ['device_unique_id'] = $this->request->get ['device_unique_id'];
				
				if ($this->request->get ['is_android'] != null && $this->request->get ['is_android'] != "") {
					$editdata ['is_android'] = $this->request->get ['is_android'];
				} else {
					$editdata ['is_android'] = '1';
				}
				
				$archive_forms_id = $this->model_form_form->editFormdata ( $this->request->post ['design_forms'], $this->request->get ['forms_id'], $this->request->post ['upload_file'], $this->request->post ['image'], $this->request->post ['signature'], $this->request->post ['form_signature'], $this->request->post ['is_final'], '', $editdata );
			} else {
				$this->load->model ( 'api/temporary' );
				
				$tdata = array ();
				$tdata ['id'] = $this->request->get ['forms_id'];
				$tdata ['phone_device_id'] = $this->request->get ['phone_device_id'];
				$tdata ['facilities_id'] = $this->request->get ['facilities_id'];
				$tdata ['parent_archive_forms_id'] = $this->request->get ['archive_forms_id'];
				$tdata ['type'] = 'updateform';
				$archive_forms_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			}
			
			/*
			 */
			
			$url2 = "";
			$url4 = "";
			
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
				$url4 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
			}
			/*
			 * if ($archive_forms_id != null && $archive_forms_id != "") {
			 * $url2 .= '&archive_forms_id=' . $archive_forms_id;
			 *
			 * }
			 */
			
			if ($this->request->post ['is_final'] != null && $this->request->post ['is_final'] != "") {
				$url2 .= '&is_final=' . $this->request->post ['is_final'];
				$url4 .= '&is_final=' . $this->request->post ['is_final'];
				$is_final = $this->request->post ['is_final'];
			} else {
				$is_final = '';
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				$url4 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			} else {
				$url2 .= '&archive_forms_id=' . $archive_forms_id;
				$url4 .= '&archive_forms_id=' . $archive_forms_id;
			}
			
			if ($this->request->post ['jump_page_number'] == 0) {
				$url2 .= '&forms_id=' . $this->request->get ['form_parent_id'];
				$url4 .= '&forms_id=' . $this->request->get ['form_parent_id'];
			} else {
				
				if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
					$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
					$url4 .= '&forms_id=' . $this->request->get ['forms_id'];
				}
			}
			
			/*
			 * if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
			 * $url2 .= '&formreturn_id=' . $this->request->get['forms_id'];
			 * $url4 .= '&formreturn_id=' . $this->request->get['forms_id'];
			 *
			 * }
			 */
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
				$url4 .= '&notes_id=' . $this->request->get ['notes_id'];
				
				$url2 .= '&new_form=2';
				$url4 .= '&new_form=2';
			} else {
				
				$url2 .= '&new_form=2';
				$url4 .= '&new_form=2';
			}
			
			if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
				$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url4 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
				$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
				$url4 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			}
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
				$url4 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['is_html'] != null && $this->request->get ['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get ['is_html'];
				$url4 .= '&is_html=' . $this->request->get ['is_html'];
			}
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
				$url4 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url4 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				
				$formdata = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
				
				// var_dump($formdata);
				
				if ($this->request->get ['page_number'] > 0) {
					$cpage_number = $this->request->get ['page_number'];
				} else {
					$cpage_number = $formdata ['page_number'];
				}
				
				if ($this->request->get ['parent_id'] > 0) {
					$cparent_id = $this->request->get ['parent_id'];
				} else {
					$cparent_id = $this->request->get ['forms_design_id'];
				}
				
				$childform = $this->model_form_form->getFormByLimit ( $cparent_id, $cpage_number );
				// var_dump($childform);
				
				if ($this->request->post ['bottom_submit'] != null && $this->request->post ['bottom_submit'] != "") {
					if (! empty ( $childform )) {
						
						if ($formdata ['parent_id'] > 0) {
							
							if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
								$url4 .= '&parent_id=' . $childform ['parent_id'];
							}
							
							if ($childform ['page_number'] != null && $childform ['page_number'] != "") {
								$url4 .= '&page_number=' . $childform ['page_number'];
							}
							
							if ($childform ['forms_id'] != null && $childform ['forms_id'] != "") {
								$url4 .= '&forms_design_id=' . $childform ['forms_id'];
							}
							
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
						} else {
							
							if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
								$url4 .= '&parent_id=' . $childform ['parent_id'];
							}
							
							if ($childform ['page_number'] != null && $childform ['page_number'] != "") {
								$url4 .= '&page_number=' . $childform ['page_number'];
							}
							
							if ($childform ['forms_id'] != null && $childform ['forms_id'] != "") {
								$url4 .= '&forms_design_id=' . $childform ['forms_id'];
							}
							
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
						}
					} else {
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form/jsoncustomsForm', '' . $url2, 'SSL' ) ) );
					}
				} else {
					
					if ($this->request->post ['jump_forms_id'] != null && $this->request->post ['jump_forms_id'] != "") {
						
						$formdata = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
						
						if ($this->request->get ['page_number'] > 0) {
							$cpage_number = $this->request->get ['page_number'];
						} else {
							$cpage_number = $formdata ['page_number'];
						}
						
						if ($this->request->get ['parent_id'] > 0) {
							$cparent_id = $this->request->get ['parent_id'];
						} else {
							$cparent_id = $this->request->get ['forms_design_id'];
						}
						
						$childform = $this->model_form_form->getFormByLimit ( $cparent_id, $cpage_number );
						
						if (! empty ( $childform )) {
							
							if ($formdata ['parent_id'] > 0) {
								
								if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
									$url4 .= '&parent_id=' . $childform ['parent_id'];
								}
								
								$url4 .= '&page_number=' . $this->request->post ['jump_page_number'];
								$url4 .= '&forms_design_id=' . $this->request->post ['jump_forms_id'];
								$url4 .= '&formreturn_id=' . $this->request->post ['jump_formreturn_id'];
								
								$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
							} else {
								
								if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
									$url4 .= '&parent_id=' . $childform ['parent_id'];
								}
								
								$url4 .= '&page_number=' . $this->request->post ['jump_page_number'];
								$url4 .= '&forms_design_id=' . $this->request->post ['jump_forms_id'];
								$url4 .= '&formreturn_id=' . $this->request->post ['jump_formreturn_id'];
								
								$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url4, 'SSL' ) ) );
							}
						}
					} else {
						
						$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'services/form/jsoncustomsForm', '' . $url2, 'SSL' ) ) );
					}
				}
			}
		}
		
		$this->getForm ();
	}
	public function jsoncustomsForm() {
		$url2 = "";
		
		if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
			$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			$formreturn_id = $this->request->get ['formreturn_id'];
		} else {
			$formreturn_id = '';
		}
		if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
			$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
			$form_parent_id = $this->request->get ['form_parent_id'];
		} else {
			$form_parent_id = '';
		}
		
		if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
			$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
			
			$forms_id = $this->request->get ['forms_id'];
		} else {
			$forms_id = '';
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&unotes_id=' . $this->request->get ['notes_id'];
			$new_form = '2';
			$notes_id = $this->request->get ['notes_id'];
			$url2 .= '&new_form=2';
		} else {
			$new_form = '1';
			$notes_id = '';
			$url2 .= '&new_form=1';
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			$forms_design_id = $this->request->get ['forms_design_id'];
		} else {
			$forms_design_id = '';
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get ['facilities_id'];
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = '';
		}
		if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
			$url2 .= '&case_file_id=' . $this->request->get ['case_file_id'];
			$case_file_id = $this->request->get ['case_file_id'];
		} else {
			$case_file_id = '';
		}
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			$tag_status_id = $this->request->get ['tag_status_id'];
		} else {
			$tag_status_id = '';
		}
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get ['task_id'];
			$task_id = $this->request->get ['task_id'];
		} else {
			$task_id = '';
		}
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			
			if ($this->request->get ['client_add_new'] == null && $this->request->get ['client_add_new'] == "") {
				
				if ($this->request->get ['exittags_id'] != null && $this->request->get ['exittags_id'] != "") {
					$url2 .= '&emp_tag_id=' . $this->request->get ['exittags_id'];
					$emp_tag_id = $this->request->get ['exittags_id'];
				} else {
					$url2 .= '&emp_tag_id=' . $this->request->get ['emp_tag_id'];
					$emp_tag_id = $this->request->get ['emp_tag_id'];
				}
			} else {
				$emp_tag_id = '';
			}
		} else {
			$emp_tag_id = '';
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			if ($this->request->get ['client_add_new'] == null && $this->request->get ['client_add_new'] == "") {
				if ($this->request->get ['exittags_id'] != null && $this->request->get ['exittags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get ['exittags_id'];
					$tags_id = $this->request->get ['exittags_id'];
				} else {
					$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
					$tags_id = $this->request->get ['tags_id'];
				}
			} else {
				$tags_id = '';
			}
		} else {
			$tags_id = '';
		}
		if ($this->request->get ['exittags_id'] != null && $this->request->get ['exittags_id'] != "") {
			$url2 .= '&exittags_id=' . $this->request->get ['exittags_id'];
			$exittags_id = $this->request->get ['exittags_id'];
		} else {
			$exittags_id = '';
		}
		if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
			$url2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
			$client_add_new = $this->request->get ['client_add_new'];
		} else {
			$client_add_new = '';
		}
		
		if ($this->request->get ['link_forms_id'] != null && $this->request->get ['link_forms_id'] != "") {
			$url2 .= '&link_forms_id=' . $this->request->get ['link_forms_id'];
			
			$link_forms_id = $this->request->get ['link_forms_id'];
		} else {
			$link_forms_id = '';
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			$updatenotes_id = $this->request->get ['updatenotes_id'];
		} else {
			$updatenotes_id = '';
		}
		if ($this->request->get ['is_final'] != null && $this->request->get ['is_final'] != "") {
			$url2 .= '&is_final=' . $this->request->get ['is_final'];
			$is_final = $this->request->get ['is_final'];
		} else {
			$is_final = '';
		}
		
		if ($this->request->get ['client_add_new'] == null && $this->request->get ['client_add_new'] == "") {
			if ($this->request->get ['exittags_id'] != null && $this->request->get ['exittags_id'] != "") {
				$tags_id = $this->request->get ['exittags_id'];
			} else {
				if ($this->request->get ['forms_design_id'] != CUSTOME_INTAKEID) {
					if ($this->request->get ['tags_id']) {
						$tags_id = $this->request->get ['tags_id'];
					} elseif ($this->request->get ['emp_tag_id']) {
						$tags_id = $this->request->get ['emp_tag_id'];
					}
				}
			}
		}
		
		if ($tags_id != null && $tags_id != "") {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			$name = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$name = '';
		}
		
		if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
			$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			
			$archive_forms_id = $this->request->get ['archive_forms_id'];
		} else {
			$archive_forms_id = '';
		}
		
		if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
			
			$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			
			$signature_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/activeformsign', '' . $url2, 'SSL' ) );
		} else {
			$signature_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/insert2', '' . $url2, 'SSL' ) );
		}
		
		if ($new_form == '1') {
			$cancel_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form&previous=1', '' . $url2, 'SSL' ) );
		}
		
		if ($new_form == '2') {
			$cancel_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['facilitiess'] [] = array (
				'task_form' => '',
				'archive_forms_id' => $archive_forms_id,
				'formreturn_id' => $formreturn_id,
				'form_parent_id' => $form_parent_id,
				'task_id' => $task_id,
				'emp_tag_id' => $emp_tag_id,
				'name' => $name,
				'tags_id' => $tags_id,
				'new_form' => $new_form,
				'forms_id' => $forms_id,
				'notes_id' => $notes_id,
				'updatenotes_id' => $updatenotes_id,
				'facilities_id' => $facilities_id,
				'forms_design_id' => $forms_design_id,
				'exittags_id' => $exittags_id,
				'client_add_new' => $client_add_new,
				// 'link_forms_id' => $link_forms_id,
				'signature_url' => $signature_url,
				'cancel_url' => $cancel_url,
				'is_final' => $is_final,
				'case_file_id' => $case_file_id 
		);
		
		//$formdata = json_encode ($this->data ['facilitiess']);
		
		//echo '<script>var datas = '.$formdata.'; localStorage.setItem("formsignaturedata",JSON.stringify(datas));</script>';
		
		
		$this->load->model ( 'api/temporary' );
		
		$this->load->model ( 'form/form' );
		$forminfo = $this->model_form_form->getFormDatas ( $formreturn_id);
		
		$tdata = array ();
		$tdata ['id'] = $formreturn_id;
		$tdata ['parent_id'] = $form_parent_id;
		$tdata ['parent_archive_forms_id'] = $forminfo['iframevalue'];
		$tdata ['facilities_id'] = $facilities_id;
		$tdata ['type'] = 'addformresponse';
		
		
		$archive_forms_id = $this->model_api_temporary->addtemporary ( $this->data ['facilitiess'], $tdata );
		
		
		if ($this->request->get ['is_html'] == '1') {
			
			// $this->data['signature_url'] = str_replace('&amp;', '&',$this->url->link('services/form/insert2', '' . $url2, 'SSL'));
			$this->template = $this->config->get ( 'config_template' ) . '/template/form/jsoncustom.php';
			
			$this->response->setOutput ( $this->render () );
		} else {
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		}
	}
	public function activeformsign() {
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'activeformsign', $this->request->post, 'request' );
		
		$this->load->model ( 'api/encrypt' );
		$cre_array = array ();
		$cre_array ['phone_device_id'] = $this->request->get ['phone_device_id'];
		$cre_array ['facilities_id'] = $this->request->get ['facilities_id'];
		
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
		
		$this->data ['facilitiess'] = array ();
		
		$json = array ();
		
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );
		
		if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
				$json ['warning'] = 'User Pin not valid!';
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
			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
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
		
		if ($this->request->post ['perpetual_checkbox'] == '1') {
			if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
				$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
			}
			if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!';
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
				
				$this->load->model ( 'user/user_group' );
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
				
				$perpetual_task = $user_role_info ['perpetual_task'];
				
				if ($perpetual_task != '1') {
					$json ['warning'] = "You are not authorized to end the task!";
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
		}
		
		if ($this->request->post ['current_enroll_image1'] == "1") {
			$this->load->model ( 'api/facerekognition' );
			$fre_array = array ();
			$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
			$fre_array ['facilities_id'] = $this->request->get ['facilities_id'];
			$fre_array ['user_id'] = $this->request->post ['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
			
			$json ['warning'] = $facerekognition_response ['warning1'];
			
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
			
			$this->load->model ( 'form/form' );
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
			
			$this->load->model ( 'setting/timezone' );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
			
			$form_date_added = ( string ) $noteDate;
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$formreturn_id = $this->request->get ['formreturn_id'];
			} else {
				$formreturn_id = $this->request->get ['form_parent_id'];
			}
			
			// $this->model_form_form->updateformfacility($facilities_id, $formreturn_id);
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			}
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['emp_tag_id'] = $tag_info ['emp_tag_id'];
			$tdata ['formreturn_id'] = $formreturn_id;
			$tdata ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$tdata ['activeform_id'] = $this->request->get ['activeform_id'];
			$tdata ['facilities_id'] = $this->request->get ['facilities_id'];
			$tdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
			
			$notes_id = $this->model_form_form->activeformsign ( $this->request->post, $tdata );
			
			$this->load->model ( 'api/facerekognition' );
			$fre_array2 = array ();
			$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
			$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
			$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
			$fre_array2 ['facilities_id'] = $this->request->get ['facilities_id'];
			$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
			$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
			$fre_array2 ['notes_id'] = $notes_id;
			
			// var_dump($fre_array2);
			
			$s3_url = $this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
			
			$this->data ['facilitiess'] [] = array (
					'warning' => '1',
					'formreturn_id' => $formreturn_id,
					'notes_id' => $notes_id,
					'facilities_id' => $this->request->get ['facilities_id'],
					'tags_id' => $this->request->get ['tags_id'] 
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
	}
	public function jsongetiframeform() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			/*
			 * $cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			 * $cre_array['facilities_id'] = $this->request->post['facilities_id'];
			 *
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
			
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			
			$a212 = array ();
			
			$a212 ['facilities_id'] = $this->request->post ['facilities_id'];
			$a212 ['iframevalue'] = $this->request->post ['iframevalue'];
			
			
			
			$this->load->model ( 'api/temporary' );
			$temporary_info = $this->model_api_temporary->gettemporaryparentrow ( $this->request->post ['iframevalue'] );
			
			$tempdata = array ();
			
			if(!empty($temporary_info)){
				$tempdata = unserialize ( $temporary_info ['data'] );
				$this->data ['facilitiess'] = $tempdata;
				$this->model_api_temporary->deletetemporary3 ( $this->request->post ['iframevalue'] );
				
				
				$status = true;
			}else{
				$this->data ['facilitiess'] [] = array (
						'warning' => "No result found" 
				);
				$status = false;
			}
			
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $status 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in form jsongetiframeform ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsongetiframeform', $activity_data2 );
		}
	}
}