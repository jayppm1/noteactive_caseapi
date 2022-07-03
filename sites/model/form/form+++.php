<?php
class ModelFormForm extends Model


 {
	public function getFormdata($form_id) {
		$query = $this->db->query ( "SELECT forms_id,form_name,forms_fields,form_layout,forms_setting,display_image,display_signature,display_add_row,display_content_postion,form_image,relation_keyword_id,facilities,parent_id,page_number,is_client_active,form_type,client_reqired,linked_form,open_search,required_approval,db_table_name,approval_required,mark_final,approval_user_roles,mark_final_user_roles,pdf_setting FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $form_id . "' " );
		
		$form_data = array ();
		
		if ($query->num_rows) {
			$result = $query->row;
			$fields = unserialize ( $result ['forms_fields'] );
			
			// $this->array_sort_by_column($fields, 'forms_section_sort_order');
			
			$sort_col = array ();
			
			// var_dump($fields);
			
			$link_form_fieldall = array ();
			
			foreach ( $fields as $key => $row ) {
				$sort_col [$key] = $row ['forms_section_sort_order'];
				
				foreach ( $row ['formfields'] as $aaa ) {
					// var_dump($aaa);
					if ($aaa ['link_form'] == '1') {
						$link_form_fieldall [$aaa ['key']] = $aaa ['link_form_field'];
					}
				}
				
				// var_dump($row['formfields']);
				// echo "<hr>";
				$sort_colf = array ();
				foreach ( $row ['formfields'] as $key1 => $rowfield ) {
					$sort_colf [$key1] = $rowfield ['sort_order'];
				}
				
				/*
				 * var_dump($sort_colf);
				 * echo "<hr>";
				 * var_dump($row['formfields']);
				 * echo "<hr>";
				*/
				array_multisort ( $sort_colf, SORT_ASC, $row ['formfields'] );
			}
			array_multisort ( $sort_col, SORT_ASC, $fields );
			
			
			if (! empty ( $result ['approval_user_roles'] )) {
				$approval_user_roles = explode ( ',', $result ['approval_user_roles'] );
			} else {
				$approval_user_roles = array ();
			}
			if (! empty ( $result ['mark_final_user_roles'] )) {
				$mark_final_user_roles = explode ( ',', $result ['mark_final_user_roles'] );
			} else {
				$mark_final_user_roles = array ();
			}
			
			if($result['pdf_setting'] != null && $result['pdf_setting'] != ""){
				$pdf_setting = unserialize($result['pdf_setting']);
			}else{
				$pdf_setting = "";
			}
			
			$form_data = array (
					'forms_id' => $result ['forms_id'],
					'display_image' => $result ['display_image'],
					'approval_required' => $result ['approval_required'],
					'form_type' => $result ['form_type'],
					'required_approval' => $result ['required_approval'],
					'mark_final' => $result ['mark_final'],
					'db_table_name' => $result ['db_table_name'],
					'is_final' => $result ['is_final'],
					'linked_form' => $result ['linked_form'],
					'open_search' => $result ['open_search'],
					'client_reqired' => $result ['client_reqired'],
					'display_signature' => $result ['display_signature'],
					'forms_setting' => $result ['forms_setting'],
					'form_name' => $result ['form_name'],
					'display_add_row' => $result ['display_add_row'],
					'display_content_postion' => $result ['display_content_postion'],
					'display_observation' => $result ['display_observation'],
					'exiting_client' => $result ['exiting_client'],
					'relation_keyword_id' => $result ['relation_keyword_id'],
					'form_image' => $result ['form_image'],
					'parent_id' => $result ['parent_id'],
					'page_number' => $result ['page_number'],
					'is_client_active' => $result ['is_client_active'],
					'pdf_setting' => $pdf_setting,
					'approval_user_roles' => $approval_user_roles, 
					'mark_final_user_roles' => $mark_final_user_roles, 
					'forms_fields' => $fields,
					'link_form_fieldall' => $link_form_fieldall ,
					
			);
			
			// echo "<hr>";
			// var_dump($link_form_fieldall);
			
			return $form_data;
		} else {
			return false;
		}
		
		return $form_data;
	}
	public function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array ();
		
		// var_dump($dir);
		
		foreach ( $arr as $key => $row ) {
			$sort_col [$key] = $row [$col];
			
			// var_dump($row['formfields']);
			// echo "<hr>";
			/*
			 * $sort_colf = array();
			 * foreach($row['formfields'] as $key1=> $rowfield){
			 * $sort_colf[$key1] = $rowfield['sort_order'];
			 * }
			 *
			 * var_dump($sort_colf);
			 * echo "<hr>";
			 * var_dump($row['formfields']);
			 * echo "<hr>";
			 * array_multisort($sort_colf, SORT_ASC, $row['formfields']);
			 */
		}
		array_multisort ( $sort_col, $dir, $arr );
	}
	public function addFormdata($formdata, $data2) {
		// var_dump($formdata);
		// echo "<hr>";
		$value = '';
		$value2 = '';
		$value3 = '';
		foreach ( $formdata ['design_forms'] as $key => $newdata ) {
			
			// var_dump($newdata);
			// echo "<hr>";
			
			if (is_array ( $newdata )) {
				foreach ( $newdata as $key2 => $b ) {
					
					if (is_array ( $b )) {
						foreach ( $b as $key3 => $d ) {
							
							if (is_array ( $d )) {
								foreach ( $d as $key4 => $e ) {
									$value .= $e;
									
									if ($e) {
										$value2 .= $key4 . ':' . $e;
									}
									if ($e) {
										$value .= ' ';
										$value2 .= ' ';
									}
								}
							} else {
								
								$value .= $d;
								
								if ($d) {
									$value2 .= $key3 . ':' . $d;
								}
								if ($d) {
									$value .= ' ';
									$value2 .= ' ';
								}
							}
						}
					} else {
						
						$value .= $b;
						
						if ($b) {
							$value2 .= $key2 . ':' . $b;
						}
						if ($b) {
							$value .= ' ';
							$value2 .= ' ';
						}
					}
					
					if ($b ['tags_id']) {
						// $value3 = $b['tags_id'];
					}
				}
			} else {
				$value .= $newdata;
				if ($newdata) {
					$value2 .= $key . ':' . $newdata;
				}
				if ($newdata) {
					$value .= ' ';
					$value2 .= ' ';
				}
			}
		}
		
		$form_data = $this->getFormdata ( $data2 ['forms_design_id'] );
		
		$form_name = $form_data ['form_name'];
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $data2 ['facilities_id'] );
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		// var_dump($this->request->get['searchdate']);
		
		$date_added22 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added11 = date ( 'Y-m-d', strtotime ( 'now' ) );
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			if ($this->request->get ['searchdate'] == $date_added11) {
				$date_added = $date_added22;
			} else {
				$date2 = str_replace ( '-', '/', $this->request->get ['searchdate'] );
				$res2 = explode ( "/", $date2 );
				$date_added = $res2 [2] . "-" . $res2 [0] . "-" . $res2 [1];
			}
		} else {
			$date_added = $date_added22;
		}
		
		if ($data2 ['facilities_id']) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $data2 ['facilities_id'] );
			$unique_id = $facility ['customer_key'];
		}
		
		/*
		 * $sql = "INSERT INTO " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape(serialize($formdata['design_forms'])) . "',form_description = '" . $this->db->escape($value) . "',rules_form_description = '" . $this->db->escape($value2) . "', notes_id = '" . $data2['notes_id'] . "', facilities_id = '" . $data2['facilities_id'] . "', form_type = '3', custom_form_type = '".$data2['forms_design_id']."', upload_file = '".$this->db->escape($formdata['upload_file'])."', form_signature = '".$this->db->escape($formdata['form_signature'])."', is_final = '".$this->db->escape($formdata['is_final'])."', incident_number='".$this->db->escape($form_name)."', date_added = '".$date_added."', date_updated = '".$date_added."', tags_id = '" . $value3 . "', is_approval_required = '".$this->db->escape($formdata['is_approval_required'])."', page_number = '".$data2['page_number']."', form_design_parent_id = '".$data2['form_design_parent_id']."', form_parent_id = '".$data2['form_parent_id']."', status = '0',phone_device_id = '" . $this->db->escape($data2['phone_device_id']) . "',is_android = '" . $this->db->escape($data2['is_android']) . "',unique_id = '" . $this->db->escape($unique_id) . "' ";
		 *
		 *
		 * $this->db->query($sql);
		 * $forms_id = $this->db->getLastId();
		 */
		
		$sql = "CALL insertForm('" . $this->db->escape ( serialize ( $formdata ['design_forms'] ) ) . "','" . $this->db->escape ( $value ) . "','" . $this->db->escape ( $value2 ) . "','" . $this->db->escape ( $data2 ['notes_id'] ) . "','" . $this->db->escape ( $data2 ['facilities_id'] ) . "','" . $this->db->escape ( $data2 ['forms_design_id'] ) . "','" . $this->db->escape ( $formdata ['upload_file'] ) . "','" . $this->db->escape ( $formdata ['form_signature'] ) . "','" . $this->db->escape ( $formdata ['is_final'] ) . "','" . $this->db->escape ( $form_name ) . "','" . $date_added . "','" . $date_added . "','" . $value3 . "','" . $this->db->escape ( $formdata ['is_approval_required'] ) . "','" . $data2 ['page_number'] . "','" . $data2 ['form_design_parent_id'] . "','" . $data2 ['form_parent_id'] . "','" . $this->db->escape ( $data2 ['phone_device_id'] ) . "','" . $this->db->escape ( $data2 ['is_android'] ) . "','" . $this->db->escape ( $unique_id ) . "')";
		
		$lastId = $this->db->query ( $sql );
		$forms_id = $lastId->row ['forms_id'];
		
		
		$updateshift = "UPDATE `" . DB_PREFIX . "forms` SET iframevalue = '" . $data2['iframevalue'] . "' WHERE forms_id = '" . ( int ) $forms_id . "' ";
		$this->db->query ( $updateshift );
		
		if ($formdata ['image']) {
			
			foreach ( $formdata ['image'] as $key => $upload_file ) {
				if (is_array ( $upload_file )) {
					foreach ( $upload_file as $key22 => $upload_file1 ) {
						
						if (is_array ( $upload_file1 )) {
							foreach ( $upload_file1 as $key222 => $upload_file2 ) {
								if ($upload_file2) {
									$sql111 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $upload_file2 ) . "', media_name = '" . $this->db->escape ( $key222 ) . "', status = '1', media_type = '1' ";
									$this->db->query ( $sql111 );
								}
							}
						} else {
							if ($upload_file1) {
								$sql111 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $upload_file1 ) . "', media_name = '" . $this->db->escape ( $key22 ) . "', status = '1', media_type = '1' ";
								$this->db->query ( $sql111 );
							}
						}
					}
				} else {
					if ($upload_file) {
						$sql1 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $upload_file ) . "', media_name = '" . $this->db->escape ( $key ) . "', status = '1', media_type = '1' ";
						$this->db->query ( $sql1 );
					}
				}
			}
		}
		
		if ($formdata ['signature']) {
			
			foreach ( $formdata ['signature'] as $key1 => $signature ) {
				
				if (is_array ( $signature )) {
					foreach ( $signature as $key222 => $signature1 ) {
						
						if (is_array ( $signature1 )) {
							foreach ( $signature1 as $key222d => $signature2 ) {
								if ($signature2) {
									$sql12 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $signature2 ) . "', media_name = '" . $this->db->escape ( $key222d ) . "', status = '1', media_type = '2' ";
									$this->db->query ( $sql12 );
								}
							}
						} else {
							
							if ($signature1) {
								$sql12 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $signature1 ) . "', media_name = '" . $this->db->escape ( $key222 ) . "', status = '1', media_type = '2' ";
								$this->db->query ( $sql12 );
							}
						}
					}
				} else {
					
					if ($signature) {
						$sql = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $signature ) . "', media_name = '" . $this->db->escape ( $key1 ) . "', status = '1', media_type = '2' ";
						$this->db->query ( $sql );
					}
				}
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$data ['forms_id'] = $forms_id;
		$data ['design_forms'] = $formdata ['design_forms'];
		$data ['upload_file'] = $formdata ['upload_file'];
		$data ['image'] = $formdata ['image'];
		$data ['phone_device_id'] = $data2 ['phone_device_id'];
		$data ['is_android'] = $data2 ['is_android'];
		$data ['facilities_id'] = $data2 ['facilities_id'];
		$data ['forms_design_id'] = $data2 ['forms_design_id'];
		$data ['form_design_parent_id'] = $data2 ['form_design_parent_id'];
		$data ['form_parent_id'] = $data2 ['form_parent_id'];
		$data ['date_added'] = $date_added;
		$data ['form_name'] = $form_name;
		$this->model_activity_activity->addActivitySave ( 'addFormdata', $data, 'query' );
		
		/*
		 * foreach($formdata['design_forms'] as $design_forms){
		 * foreach($design_forms as $key=>$design_form){
		 * foreach($design_form as $key2=>$b){
		 *
		 * $arrss = explode("_1_", $key2);
		 * //var_dump($arrss);
		 * //echo "<hr>";
		 * if($arrss[1] == 'tags_id'){
		 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
		 * //var_dump($design_form[$arrss[0]]);
		 * //echo "<hr>";
		 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
		 * if($design_form[$arrss[0].'_1_'.$arrss[1]] == null && $design_form[$arrss[0].'_1_'.$arrss[1]] == ""){
		 *
		 * }
		 * }
		 * }
		 *
		 * if($arrss[1] == 'user_id'){
		 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
		 * if($design_form[$arrss[0].'_1_'.$arrss[1]] == null && $design_form[$arrss[0].'_1_'.$arrss[1]] == ""){
		 *
		 * }
		 * }
		 * }
		 *
		 *
		 * if($arrss[1] == 'user_ids'){
		 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
		 *
		 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
		 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idsu){
		 *
		 * }
		 * }
		 * //echo "<hr>";
		 * }
		 *
		 * if($arrss[1] == 'tags_ids'){
		 * //var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
		 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
		 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idst){
		 *
		 * }
		 * }
		 * //echo "<hr>";
		 * }
		 * }
		 * }
		 * }
		 */
		
		return $forms_id;
	}
	public function editFormdata($formdata, $forms_id, $upload_file, $ffile, $fsignature, $form_signature, $is_final, $previous, $data2) {
		
		// var_dump($previous);
		if ($previous != '1') {
			$query12 = $this->db->query ( "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,form_parent_id,page_number,form_design_parent_id,case_number,case_status FROM `" . DB_PREFIX . "forms` WHERE forms_id = '" . $forms_id . "' " );
			
			if ($query12->num_rows > 0) {
				$mrow = $query12->row;
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_forms` SET 
				forms_id = '" . $this->db->escape ( $mrow ['forms_id'] ) . "'
				,form_type_id = '" . $this->db->escape ( $mrow ['form_type_id'] ) . "'
				, form_type = '" . $this->db->escape ( $mrow ['form_type'] ) . "'
				, form_description = '" . $this->db->escape ( $mrow ['form_description'] ) . "'
				, rules_form_description = '" . $this->db->escape ( $mrow ['rules_form_description'] ) . "'
				, date_added = '" . $mrow ['date_added'] . "'
				, notes_id = '" . $mrow ['notes_id'] . "'
				, user_id = '" . $this->db->escape ( $mrow ['user_id'] ) . "'
				, signature = '" . $this->db->escape ( $mrow ['signature'] ) . "'
				, notes_pin = '" . $this->db->escape ( $mrow ['notes_pin'] ) . "'
				, form_date_added = '" . $mrow ['form_date_added'] . "'
				, incident_number = '" . $mrow ['incident_number'] . "'
				, facilities_id = '" . $mrow ['facilities_id'] . "'
				, notes_type = '" . $this->db->escape ( $mrow ['notes_type'] ) . "'
				, form_signature = '" . $this->db->escape ( $mrow ['form_signature'] ) . "'
				, assessment_id = '" . $this->db->escape ( $mrow ['assessment_id'] ) . "'
				, custom_form_type = '" . $this->db->escape ( $mrow ['custom_form_type'] ) . "'
				, design_forms = '" . $this->db->escape ( $mrow ['design_forms'] ) . "'
				, date_updated = '" . $mrow ['date_updated'] . "'
				, upload_file = '" . $mrow ['upload_file'] . "'
				, tags_id = '" . $mrow ['tags_id'] . "'
				, parent_id = '" . $this->db->escape ( $mrow ['parent_id'] ) . "'
				, is_discharge = '" . $this->db->escape ( $mrow ['is_discharge'] ) . "'
				, tagstatus_id = '" . $this->db->escape ( $mrow ['tagstatus_id'] ) . "'
				, is_final = '" . $this->db->escape ( $mrow ['is_final'] ) . "'
				, form_parent_id = '" . $this->db->escape ( $mrow ['form_parent_id'] ) . "'
				, page_number = '" . $this->db->escape ( $mrow ['page_number'] ) . "'
				, form_design_parent_id = '" . $this->db->escape ( $mrow ['form_design_parent_id'] ) . "'
				, phone_device_id = '" . $this->db->escape ( $mrow ['phone_device_id'] ) . "'
				, is_android = '" . $this->db->escape ( $mrow ['is_android'] ) . "'
				, user_file = '" . $this->db->escape ( $mrow ['user_file'] ) . "'
				, is_user_face = '" . $this->db->escape ( $mrow ['is_user_face'] ) . "'
				, unique_id = '" . $this->db->escape ( $mrow ['unique_id'] ) . "'
				, destination_facilities_id = '" . $this->db->escape ( $mrow ['destination_facilities_id'] ) . "'
				, html_file_url = '" . $this->db->escape ( $mrow ['html_file_url'] ) . "'
				, is_archive = '1'
				
				" );
			}
			$archive_forms_id = $this->db->getLastId ();
			
			$querya = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "forms_media` WHERE forms_id = '" . $forms_id . "' " );
			
			if ($querya->num_rows > 0) {
				foreach ( $querya->rows as $mrow1 ) {
					$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_forms_media` SET 
					forms_media_id = '" . $this->db->escape ( $mrow1 ['forms_media_id'] ) . "'
					, media_url = '" . $this->db->escape ( $mrow1 ['media_url'] ) . "'
					, media_name = '" . $this->db->escape ( $mrow1 ['media_name'] ) . "'
					, status = '" . $mrow1 ['status'] . "'
					, forms_id = '" . $mrow1 ['forms_id'] . "'
					, media_type = '" . $mrow1 ['media_type'] . "'
					, archive_forms_id = '" . $archive_forms_id . "'
					, is_archive = '1'
					" );
				}
			}
		}
		
		$value = '';
		$value2 = '';
		
		foreach ( $formdata as $key => $newdata ) {
			
			// var_dump($newdata);
			// echo "<hr>";
			
			if (is_array ( $newdata )) {
				foreach ( $newdata as $key2 => $b ) {
					
					if (is_array ( $b )) {
						foreach ( $b as $key3 => $d ) {
							
							if (is_array ( $d )) {
								foreach ( $d as $key4 => $e ) {
									$value .= $e;
									
									if ($e) {
										$value2 .= $key4 . ':' . $e;
									}
									if ($e) {
										$value .= ' ';
										$value2 .= ' ';
									}
								}
							} else {
								// var_dump($key3);
								
								$arrss = explode ( "_1_", $key3 );
								if ($arrss [1] == 'secure_data_value') {
									// var_dump($b[$key3]);
								}
								
								$value .= $d;
								
								if ($d) {
									$value2 .= $key3 . ':' . $d;
								}
								if ($d) {
									$value .= ' ';
									$value2 .= ' ';
								}
							}
						}
					} else {
						
						$value .= $b;
						
						if ($b) {
							$value2 .= $key2 . ':' . $b;
						}
						if ($b) {
							$value .= ' ';
							$value2 .= ' ';
						}
					}
					
					if ($b ['tags_id']) {
						$value3 = $b ['tags_id'];
					}
				}
			} else {
				$value .= $newdata;
				if ($newdata) {
					$value2 .= $key . ':' . $newdata;
				}
				if ($newdata) {
					$value .= ' ';
					$value2 .= ' ';
				}
			}
		}
		
		/*
		 * $sql = "Update ". DB_PREFIX . "forms SET design_forms = '" . $this->db->escape(serialize($formdata)) . "',form_description = '" . $this->db->escape($value) . "',rules_form_description = '" . $this->db->escape($value2) . "',phone_device_id = '" . $this->db->escape($data2['phone_device_id']) . "',is_android = '" . $this->db->escape($data2['is_android']) . "' WHERE forms_id = '" . $forms_id . "' ";
		 */
		
		$sql = "CALL updateForm('" . $this->db->escape ( serialize ( $formdata ) ) . "','" . $this->db->escape ( $value ) . "','" . $this->db->escape ( $value2 ) . "','" . $this->db->escape ( $data2 ['phone_device_id'] ) . "','" . $this->db->escape ( $data2 ['is_android'] ) . "','" . $forms_id . "')";
		
		$this->db->query ( $sql );
		/*
		 * if($value3){
		 * $sqdl = "Update ". DB_PREFIX . "forms SET tags_id = '" . $value3 . "' WHERE forms_id = '" . $forms_id . "' ";
		 * $this->db->query($sqdl);
		 * }
		 */
		
		if ($upload_file) {
			$sql2 = "Update " . DB_PREFIX . "forms SET upload_file = '" . $this->db->escape ( $upload_file ) . "' WHERE forms_id = '" . $forms_id . "' ";
			$this->db->query ( $sql2 );
		}
		
		if ($is_final) {
			$sql2 = "Update " . DB_PREFIX . "forms SET is_final = '" . $this->db->escape ( $is_final ) . "' WHERE forms_id = '" . $forms_id . "' ";
			$this->db->query ( $sql2 );
		}
		
		if ($form_signature) {
			$sql2 = "Update " . DB_PREFIX . "forms SET form_signature = '" . $this->db->escape ( $form_signature ) . "' WHERE forms_id = '" . $forms_id . "' ";
			$this->db->query ( $sql2 );
		}
		
		if ($ffile) {
			$this->db->query ( "DELETE FROM `" . DB_PREFIX . "forms_media` WHERE forms_id = '" . ( int ) $forms_id . "' and media_type = '1' " );
			foreach ( $ffile as $key => $upload_file ) {
				if (is_array ( $upload_file )) {
					foreach ( $upload_file as $key22 => $upload_file1 ) {
						
						if (is_array ( $upload_file1 )) {
							foreach ( $upload_file1 as $key222 => $upload_file2 ) {
								if ($upload_file2) {
									$sql111 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $upload_file2 ) . "', media_name = '" . $this->db->escape ( $key222 ) . "', status = '1', media_type = '1' ";
									$this->db->query ( $sql111 );
								}
							}
						} else {
							if ($upload_file1) {
								$sql111 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $upload_file1 ) . "', media_name = '" . $this->db->escape ( $key22 ) . "', status = '1', media_type = '1' ";
								$this->db->query ( $sql111 );
							}
						}
					}
				} else {
					if ($upload_file) {
						$sql1 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $upload_file ) . "', media_name = '" . $this->db->escape ( $key ) . "', status = '1', media_type = '1' ";
						$this->db->query ( $sql1 );
					}
				}
			}
		}
		
		if ($fsignature) {
			$this->db->query ( "DELETE FROM `" . DB_PREFIX . "forms_media` WHERE forms_id = '" . ( int ) $forms_id . "' and media_type = '2' " );
			foreach ( $fsignature as $key1 => $signature ) {
				
				if (is_array ( $signature )) {
					foreach ( $signature as $key222 => $signature1 ) {
						
						if (is_array ( $signature1 )) {
							foreach ( $signature1 as $key222d => $signature2 ) {
								if ($signature2) {
									$sql12 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $signature2 ) . "', media_name = '" . $this->db->escape ( $key222d ) . "', status = '1', media_type = '2' ";
									$this->db->query ( $sql12 );
								}
							}
						} else {
							
							if ($signature1) {
								$sql12 = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $signature1 ) . "', media_name = '" . $this->db->escape ( $key222 ) . "', status = '1', media_type = '2' ";
								$this->db->query ( $sql12 );
							}
						}
					}
				} else {
					
					if ($signature) {
						$sql = "INSERT INTO " . DB_PREFIX . "forms_media SET forms_id = '" . ( int ) $forms_id . "', media_url = '" . $this->db->escape ( $signature ) . "', media_name = '" . $this->db->escape ( $key1 ) . "', status = '1', media_type = '2' ";
						$this->db->query ( $sql );
					}
				}
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$data ['forms_id'] = $forms_id;
		$data ['upload_file'] = $upload_file;
		$data ['design_forms'] = $formdata;
		$data ['image'] = $ffile;
		$data ['phone_device_id'] = $data2 ['phone_device_id'];
		$data ['is_android'] = $data2 ['is_android'];
		$data ['facilities_id'] = $data2 ['facilities_id'];
		$data ['forms_design_id'] = $data2 ['forms_design_id'];
		$data ['form_design_parent_id'] = $data2 ['form_design_parent_id'];
		$data ['form_parent_id'] = $data2 ['form_parent_id'];
		$data ['date_added'] = $date_added;
		$data ['form_name'] = $form_name;
		$this->model_activity_activity->addActivitySave ( 'editFormdata', $data, 'query' );
		
		return $archive_forms_id;
	}
	public function updatenote($notes_id, $formreturn_id) {
		$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . $notes_id . "' where  forms_id = '" . $formreturn_id . "'" );
		
		$form_info = $this->getFormDatas ( $formreturn_id );
		$formdesign_info = $this->getFormDatadesign ( $form_info ['custom_form_type'] );
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
	public function getFormDatadesign($forms_id) {
		$query = $this->db->query ( "SELECT forms_id,form_name,forms_fields,form_layout,forms_setting,display_image,display_signature,display_add_row,display_content_postion,form_image,relation_keyword_id,facilities,parent_id,page_number,is_client_active,form_type,approval_required FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $forms_id . "' " );
		return $query->row;
	}
	public function getFormDatas($forms_id) {
		$query = $this->db->query ( "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id,case_number,case_status,image_url,image_name,iframevalue FROM " . DB_PREFIX . "forms WHERE forms_id = '" . $forms_id . "' " );
		return $query->row;
	}
	public function getFormDatasparents($forms_id) {
		$query = $this->db->query ( "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id,case_number,case_status FROM " . DB_PREFIX . "forms WHERE form_parent_id = '" . $forms_id . "' " );
		return $query->rows;
	}
	public function getFormDatabynotesid($custom_form_type, $notes_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id,case_number,case_status FROM " . DB_PREFIX . "forms WHERE custom_form_type = '" . $custom_form_type . "' and notes_id = '" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getFormDatas3($forms_id, $notes_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "archive_forms WHERE forms_id = '" . $forms_id . "' and archive_notes_id = '" . $notes_id . "' " );
		return $query->row;
	}
	public function getforms($data = array()) {
		$sql = "SELECT forms_id,form_name,form_layout,display_image,display_signature,display_add_row,display_content_postion,form_image,relation_keyword_id,facilities,parent_id,page_number,is_client_active,form_type,client_reqired,linked_form,open_search,approval_required,approval_user_roles,mark_final,mark_final_user_roles,pdf_setting FROM " . DB_PREFIX . "forms_design";
		
		$sql .= " where 1 = 1 and status = '1' ";
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities)";
		}
		
		if ($data ['is_parent'] == '1') {
			$sql .= " and parent_id = '0' ";
		}
		
		if ($data ['is_parent_child'] == '1') {
			$sql .= " and parent_id != '0' ";
		}
		
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$sql .= " and parent_id = '" . $data ['forms_id'] . "'";
		}
		
		if ($data ['page_number'] != null && $data ['page_number'] != "") {
			$sql .= " and page_number = '" . $data ['page_number'] . "'";
		}
		
		if($data['form_type']!=null && $data['form_type']!=""){
			$sql.="and form_type = '".$data['form_type']."'";
		}else{
			$sql.="and form_type != 'Database'";
		}
		
		if ($data ['sort']) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY form_name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		return $query->rows;
		
		/*
		 * $cacheid = $data['facilities_id'].'.getforms';
		 *
		 * $this->load->model('api/cache');
		 * $rforms = $this->model_api_cache->getcache($cacheid);
		 *
		 * if (!$rforms) {
		 * $query = $this->db->query($sql);
		 * $rforms = $query->rows;
		 * $this->model_api_cache->setcache($cacheid,$rforms);
		 * }
		 *
		 *
		 * return $rforms;
		 */
	}
	public function getTotalforms($data = array()) {
		$sql .= 'where 1 = 1 and status = 1 ';
		
		if ($data ['is_parent'] == '1') {
			$sql .= " and parent_id = '0' ";
		}
		
		if ($data ['is_parent_child'] == '1') {
			$sql .= " and parent_id != '0' ";
		}
		
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$sql .= " and parent_id = '" . $data ['forms_id'] . "'";
		}
		
		$sql1 = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "forms_design " . $sql . " ";
		$query = $this->db->query ( $sql1 );
		
		return $query->row ['total'];
	}
	public function getFormwithNotes($notes_id, $custom_form_type) {
		$query = $this->db->query ( "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms WHERE notes_id = '" . $notes_id . "' and custom_form_type = '" . $custom_form_type . "' " );
		return $query->row;
	}
	public function getFormmedia($forms_id) {
		$query = $this->db->query ( "SELECT forms_media_id,media_url,media_name,status,forms_id,media_type FROM " . DB_PREFIX . "forms_media WHERE forms_id = '" . $forms_id . "' " );
		return $query->rows;
	}
	public function getFormmedia3($forms_id, $notes_id) {
		$query = $this->db->query ( "SELECT archive_forms_media_id,forms_media_id,media_url,media_name,status,forms_id,media_type,is_archive,archive_notes_id,archive_forms_id FROM " . DB_PREFIX . "archive_forms_media WHERE forms_id = '" . $forms_id . "' and archive_notes_id = '" . $notes_id . "' " );
		return $query->rows;
	}
	public function getRules($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "formrules r LEFT JOIN " . DB_PREFIX . "formrules_tigger rt ON (r.rules_id = rt.rules_id) where r.status='1' and FIND_IN_SET('" . $data ['facilities_id'] . "',r.facilities_id)  ";
		$query = $this->db->query ( $sql );
		$rules_data = array ();
		
		if ($query->num_rows) {
			foreach ( $query->rows as $result ) {
				$rules_data [$result ['rules_tigger_id']] = array (
						'rules_id' => $result ['rules_id'],
						'forms_id' => $result ['forms_id'],
						'rules_operator' => $result ['rules_operator'],
						'snooze_dismiss' => $result ['snooze_dismiss'],
						'facilities_id' => $result ['facilities_id'],
						'recurnce_week' => $result ['recurnce_week'],
						'recurnce_day' => $result ['recurnce_day'],
						'rules_name' => $result ['rules_name'],
						'rules_operation' => $result ['rules_operation'],
						'rules_operation_recurrence' => $result ['rules_operation_recurrence'],
						'end_recurrence_date' => $result ['end_recurrence_date'],
						'recurnce_m' => $result ['recurnce_m'],
						'rules_operation_time' => $result ['rules_operation_time'],
						'rules_tigger_id' => $result ['rules_tigger_id'],
						'rules_module' => unserialize ( $result ['rules_module'] ),
						'onschedule_rules_module' => unserialize ( $result ['onschedule_rules_module'] ),
						
						'rule_action' => unserialize ( $result ['rule_action'] ),
						'rule_action_content' => unserialize ( $result ['rule_action_content'] ) 
				);
			}
			
			return $rules_data;
		} else {
			return false;
		}
	}
	public function updateformTag($data = array()) {
		$sql = "Update " . DB_PREFIX . "forms SET tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "', date_updated = '" . $data ['update_date'] . "' WHERE forms_id = '" . $data ['forms_id'] . "' ";
		
		$this->db->query ( $sql );
	}
	public function gettagsforma($tags_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		
		$sql .= " where 1 = 1 and tags_id = '" . $tags_id . "' and custom_form_type = '" . CUSTOME_INTAKEID . "' and is_discharge = '0'  ";
		
		// echo $sql;
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function gettagsforma3($tags_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		
		$sql .= " where 1 = 1 and tags_id = '" . $tags_id . "' and custom_form_type = '" . CUSTOME_INTAKEID . "' and is_discharge = '1'  ";
		
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function gettagsformav($tags_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		
		$sql .= " where 1 = 1 and tags_id = '" . $tags_id . "' and custom_form_type = '12' ";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function updateform2($tags_form_info, $fdata2) {
		$sql = "INSERT INTO " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape ( $tags_form_info ['design_forms'] ) . "',form_description = '" . $this->db->escape ( $tags_form_info ['form_description'] ) . "',rules_form_description = '" . $this->db->escape ( $tags_form_info ['rules_form_description'] ) . "', notes_id = '" . $fdata2 ['notes_id'] . "', facilities_id = '" . $tags_form_info ['facilities_id'] . "', form_type = '3', custom_form_type = '" . $tags_form_info ['custom_form_type'] . "', incident_number ='" . $this->db->escape ( $tags_form_info ['incident_number'] ) . "', date_added = '" . $fdata2 ['update_date'] . "', date_updated = '" . $fdata2 ['update_date'] . "', upload_file = '" . $this->db->escape ( $tags_form_info ['upload_file'] ) . "', form_signature = '" . $this->db->escape ( $tags_form_info ['form_signature'] ) . "', tags_id = '" . $tags_form_info ['tags_id'] . "' ";
		
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_form_info'] = $tags_form_info;
		$data ['fdata2'] = $fdata2;
		$this->model_activity_activity->addActivitySave ( 'updateform2', $data, 'query' );
	}
	public function getformstatus($data) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		$sql .= " where 1 = 1 and tags_id = '" . $data ['tags_id'] . "' and custom_form_type = '10' ";
		$sql .= "and date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function gettotalformstatus($data) {
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "forms";
		$sql .= " where 1 = 1 and tags_id = '" . $data ['tags_id'] . "' and custom_form_type = '10' ";
		$sql .= "and date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
		$query = $this->db->query ( $sql );
		
		return $query->row ['total'];
	}
	public function getcustomlistvalues($customlistvalues_id) {
		$query = $this->db->query ( "SELECT customlistvalues_id,customlistvalues_name,customlist_id,relation_keyword_id,number,gender FROM " . DB_PREFIX . "customlistvalues WHERE customlistvalues_id = '" . ( int ) $customlistvalues_id . "'" );
		return $query->row;
	}
	public function getcustomlistvaluesbyname($gender) {
		$query = $this->db->query ( "SELECT customlistvalues_id,customlistvalues_name,customlist_id,relation_keyword_id,number,gender FROM " . DB_PREFIX . "customlistvalues WHERE gender = '" . $gender . "'" );
		return $query->row;
	}
	public function getcustomlistvaluebyid($customlistvalues_id, $gender) {
		$query = $this->db->query ( "SELECT customlistvalues_id,customlistvalues_name,customlist_id,relation_keyword_id,number,gender FROM " . DB_PREFIX . "customlistvalues WHERE customlistvalues_id = '" . ( int ) $customlistvalues_id . "' and gender = '" . $gender . "' " );
		return $query->row;
	}
	public function getssforms($data) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		
		$sql .= " where 1 = 1 and is_discharge = '0' and notes_id > '0' and tags_id = '0' and custom_form_type = '" . CUSTOME_INTAKEID . "' ";
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and facilities_id = '" . $data ['facilities_id'] . "' ";
		}
		
		if ($data ['filter_name'] != null && $data ['filter_name'] != "") {
			$sql .= " and form_description LIKE '%" . $data ['filter_name'] . "%' ";
		}
		
		$sql .= " order by date_added DESC ";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function updateformdate($data = array()) {
		$sql = "Update " . DB_PREFIX . "forms SET date_updated = '" . $date ['update_date'] . "' WHERE forms_id = '" . $data ['forms_id'] . "' ";
		
		$this->db->query ( $sql );
	}
	public function gettotalformstatussc($data) {
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "forms";
		$sql .= " where 1 = 1 and custom_form_type = '" . $data ['form_type'] . "' ";
		$sql .= "and date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql );
		
		return $query->row ['total'];
	}
	public function getformsectiondata($forms_section_key) {
		$query = $this->db->query ( "SELECT forms_design_section_fields_id,forms_id,forms_section_key,forms_design_section_id,forms_fields FROM " . DB_PREFIX . "forms_design_section_fields WHERE forms_section_key = '" . $forms_section_key . "' " );
		return $query->row;
	}
	
	
	
	public function gettagsforms($data) {
		
			
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			$sql = "SELECT DISTINCT f.* FROM " . DB_PREFIX . "forms f ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id  ";
			
			$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
			
			if ($data ['archivedform'] != "1") {
				$sql .= " and f.is_discharge = '0'  ";
			}
			
			if ($data ['archivedform'] == "1") {
				$sql .= " and f.is_discharge = '1' ";
			}
			
			if ($data ['is_case'] == "1") {
				$sql .= " and f.is_case = '0' ";
			}
			
			if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
				
				$sql .= " and f.`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59' ";
			}
			
			if ($data ['add_case'] != null && $data ['add_case'] != "") {
				if ($data ['case_number'] == "1") {
					$sql .= " and f.case_number != '' ";
				}
			}
			if ($data ['custom_form_type'] != "1" && $data ['custom_form_type'] != "") {
				$sql .= " and f.custom_form_type = '" . $data ['custom_form_type'] . "' ";
			}
			/*
			if ($data ['case_number'] != "1" && $data ['case_number'] != "") {
				$sql .= " and f.case_number = '" . $data ['case_number'] . "' ";
			}
			*/
			
			
			if ($data ['forms_ids'] != NULL && $data ['forms_ids'] != "") {
				$sql .= " and f.forms_id IN ( " . $data ['forms_ids'] . " ) ";
			}
			
			
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
			
			if (($data ['case_number'] != "1" && $data ['case_number'] != "") || ($data ['add_case'] != null && $data ['add_case'] != "")) {
				//$sql .= " GROUP BY case_number";
			}
			
			if($data['groupby'] == "1"){
				$sql .= " GROUP BY f.custom_form_type";	
			}
			
			$sql .= " ORDER BY f.date_added";
			$sql .= " DESC";
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			
			//echo $sql;
		
			$query = $this->db->query ( $sql );
			
			return $query->rows;
		}
	}
	public function getTotalforms2($data) {
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			$sql = "SELECT COUNT(DISTINCT f.forms_id) as total FROM " . DB_PREFIX . "forms f  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id  ";
			
			$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
			
			if ($data ['archivedform'] != "1") {
				$sql .= " and f.is_discharge = '0'  ";
			}
			
			if ($data ['archivedform'] == "1") {
				$sql .= " and f.is_discharge = '1' ";
			}
			
			if ($data ['archivedform'] != "1") {
				$sql .= " and f.is_discharge = '0'  ";
			}
			
			if ($data ['add_case'] != null && $data ['add_case'] != "") {
				if ($data ['case_number'] == "1") {
					$sql .= " and f.case_number != '' ";
				}
			}
			
			if ($data ['is_case'] == "1") {
				$sql .= " and f.is_case = '0' ";
			}
			
			if ($data ['case_number'] != "1" && $data ['case_number'] != "") {
				$sql .= " and f.case_number = '" . $data ['case_number'] . "' ";
			}
			
			if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
				
				$sql .= " and f.`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59' ";
			}
			
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
			
			if (($data ['case_number'] != "1" && $data ['case_number'] != "") || ($data ['add_case'] != null && $data ['add_case'] != "")) {
				//$sql .= " GROUP BY case_number";
			}
			
			$query = $this->db->query ( $sql );
			return $query->row ['total'];
		}
	}
	public function updateformnotes($data = array()) {
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$fsql = "Update `" . DB_PREFIX . "forms` SET notes_id = '" . $data ['notes_id'] . "', date_updated = '" . $data ['date_updated'] . "' WHERE forms_id = '" . $data ['forms_id'] . "' ";
			
			$this->db->query ( $fsql );
		}
	}
	public function updateformnotes33($data = array()) {
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$fsql = "Update `" . DB_PREFIX . "forms` SET notes_id = '" . $data ['notes_id'] . "' WHERE form_parent_id = '" . $data ['forms_id'] . "' ";
			
			$this->db->query ( $fsql );
		}
	}
	public function updateforminfo($data = array()) {
		$sqla = "Update " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape ( $data ['design_forms'] ) . "',form_description = '" . $this->db->escape ( $data ['form_description'] ) . "',rules_form_description = '" . $this->db->escape ( $data ['rules_form_description'] ) . "', date_updated = '" . $data ['date_updated'] . "', upload_file = '" . $this->db->escape ( $data ['upload_file'] ) . "', form_signature = '" . $this->db->escape ( $data ['form_signature'] ) . "' WHERE tags_id = '" . $data ['tags_id'] . "' and custom_form_type = '" . CUSTOME_INTAKEID . "' and is_discharge = '0' and is_final = '0' ";
		
		$this->db->query ( $sqla );
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updateforminfo', $data, 'query' );
	}
	public function updatetaskformnotes($data = array()) {
		$this->load->model ( 'user/user' );
		$user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		$fsql = "Update `" . DB_PREFIX . "forms` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', signature = '" . $this->db->escape ( $data ['signature'] ) . "', notes_type = '" . $data ['notes_type'] . "', notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', form_date_added = '" . $data ['form_date_added'] . "', date_added = '" . $data ['date_added'] . "', date_updated = '" . $data ['date_updated'] . "' WHERE forms_id = '" . $data ['forms_id'] . "' ";
		
		$this->db->query ( $fsql );
		
		$fsqlp = "Update `" . DB_PREFIX . "forms` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', signature = '" . $this->db->escape ( $data ['signature'] ) . "', notes_type = '" . $data ['notes_type'] . "', notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', form_date_added = '" . $data ['form_date_added'] . "', date_added = '" . $data ['date_added'] . "', date_updated = '" . $data ['date_updated'] . "' WHERE form_parent_id = '" . $data ['forms_id'] . "' ";
		
		$this->db->query ( $fsqlp );
		
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$fsql44 = "Update `" . DB_PREFIX . "forms` SET notes_id = '" . $data ['notes_id'] . "' WHERE forms_id = '" . $data ['forms_id'] . "' ";
			
			$this->db->query ( $fsql44 );
		}
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updatetaskformnotes', $data, 'query' );
	}
	public function updateformnotesinfo($data = array()) {
		$sqlar = "UPDATE `" . DB_PREFIX . "archive_forms` SET archive_notes_id = '" . $data ['archive_notes_id'] . "' where archive_forms_id = '" . ( int ) $data ['archive_forms_id'] . "' ";
		$this->db->query ( $sqlar );
		
		$sqlarm = "UPDATE `" . DB_PREFIX . "archive_forms_media` SET archive_notes_id = '" . $data ['archive_notes_id'] . "' where archive_forms_id = '" . ( int ) $data ['archive_forms_id'] . "' ";
		$this->db->query ( $sqlarm );
		
		if (! empty ( $data ['archive_forms_ids'] )) {
			foreach ( $data ['archive_forms_ids'] as $archive_forms_id ) {
				$sqlar = "UPDATE `" . DB_PREFIX . "archive_forms` SET archive_notes_id = '" . $data ['archive_notes_id'] . "' where archive_forms_id = '" . ( int ) $archive_forms_id . "' ";
				$this->db->query ( $sqlar );
				
				$sqlarm = "UPDATE `" . DB_PREFIX . "archive_forms_media` SET archive_notes_id = '" . $data ['archive_notes_id'] . "' where archive_forms_id = '" . ( int ) $archive_forms_id . "' ";
				$this->db->query ( $sqlarm );
			}
		}
		
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$sqlar = "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . $data ['notes_id'] . "' where forms_id = '" . ( int ) $data ['forms_id'] . "' ";
			$this->db->query ( $sqlar );
		}
		
		$sqlarn = "UPDATE `" . DB_PREFIX . "notes` SET is_archive = '4', update_date = '" . $data ['date_updated'] . "', notes_conut='0' where notes_id = '" . ( int ) $data ['archive_notes_id'] . "' ";
		$this->db->query ( $sqlarn );
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updateformnotesinfo', $data, 'query' );
	}
	public function updateformnotesinfo2($data = array()) {
		$fsql = "Update `" . DB_PREFIX . "archive_forms` SET archive_notes_id = '" . $data ['archive_notes_id'] . "' WHERE form_parent_id = '" . $data ['forms_id'] . "' and notes_id ='" . $data ['archive_notes_id'] . "' ";
		
		$this->db->query ( $fsql );
		
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$sqlar = "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . $data ['notes_id'] . "' where form_parent_id = '" . ( int ) $data ['forms_id'] . "' ";
			$this->db->query ( $sqlar );
		}
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updateformnotesinfo2', $data, 'query' );
	}
	public function updateformstags($tags_id, $formreturn_id) {
		$fsqlt = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tags_id . "' WHERE forms_id = '" . $formreturn_id . "' ";
		$this->db->query ( $fsqlt );
	}
	public function getFormByLimit($forms_design_id, $page_number) {
		$sql = "SELECT forms_id,form_name,forms_fields,form_layout,forms_setting,display_image,display_signature,display_add_row,display_content_postion,form_image,relation_keyword_id,facilities,parent_id,page_number,is_client_active,form_type FROM " . DB_PREFIX . "forms_design where parent_id = '" . $forms_design_id . "' and page_number > '" . $page_number . "' ORDER BY page_number LIMIT 1 ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getFormchild($data = array()) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms where form_design_parent_id = '" . $data ['form_design_parent_id'] . "' and form_parent_id = '" . $data ['form_parent_id'] . "' and custom_form_type = '" . $data ['custom_form_type'] . "' and notes_id > 0 ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getscrnneningFormdata($data = array(), $facilities_id) {
		$sqls = "select DISTINCT * from `" . DB_PREFIX . "forms` ";
		
		$sqls .= 'where 1 = 1 and notes_id > 0 and is_final = 0 and is_archive = 0 and is_discharge = 0 and form_parent_id = 0 and tags_id = 0 ';
		$sqls .= " and custom_form_type = '" . CUSTOME_INTAKEID . "' ";
		
		if ($facilities_id != null && $facilities_id != "") {
			$sqls .= " and facilities_id = '" . $facilities_id . "'";
		}
		$i = 0;
		$sqls .= " and ( ";
		foreach ( $data ['forms_fields_values'] as $key2 => $b ) {
			
			if ($b != null && $b != "") {
				if ($key2 != $facilities_id) {
					// $fkeyword = $key2.':'.$b;
					$fkeyword = $b;
					
					if ($i != '0') {
						$sqls .= ' or ';
					}
					
					$sqls .= "  LOWER(form_description) LIKE '%" . strtolower ( $this->db->escape ( $fkeyword ) ) . "%' ";
					$i ++;
				}
			}
		}
		
		$sqls .= " ) ";
		// echo $sqls;
		// echo "<hr>";
		
		$query = $this->db->query ( $sqls );
		
		return $query->rows;
	}
	public function getFormDatasparent($forms_design_id, $form_parent_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms WHERE custom_form_type = '" . $forms_design_id . "' and form_parent_id = '" . $form_parent_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getFormDatasexit($forms_design_id, $formreturn_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms WHERE custom_form_type = '" . $forms_design_id . "' and forms_id = '" . $formreturn_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getFormByLimit2($forms_design_id, $page_number) {
		$sql = "SELECT forms_id,form_name,forms_fields,form_layout,forms_setting,display_image,display_signature,display_add_row,display_content_postion,form_image,relation_keyword_id,facilities,parent_id,page_number,is_client_active,form_type,approval_required FROM " . DB_PREFIX . "forms_design where parent_id = '" . $forms_design_id . "' and page_number < '" . $page_number . "' ORDER BY page_number DESC LIMIT 1 ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function newformsign($pdata, $fdata) {
		$facilities_id = $fdata ['facilities_id'];
		
		$this->load->model ( 'notes/notes' );
		$data = array ();
		$timezone_name = $fdata ['facilitytimezone'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		$date_added11 = date ( 'Y-m-d', strtotime ( 'now' ) );
		
		if ($fdata ['searchdate'] != null && $fdata ['searchdate'] != "") {
			if ($fdata ['searchdate'] == $date_added11) {
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
			} else {
				$date2 = str_replace ( '-', '/', $fdata ['searchdate'] );
				$res2 = explode ( "/", $date2 );
				$noteDate = $res2 [2] . "-" . $res2 [0] . "-" . $res2 [1];
				$date_added = $noteDate;
			}
		} else {
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
		}
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		if ($this->session->data ['case_number'] != null && $this->session->data ['case_number'] != "") {
			$comments = ' | case number ' . $this->session->data ['case_number'];
		}
		
		// var_dump($fdata['formreturn_id']);
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
		
		$pform_info = $this->model_form_form->getFormDatasparent ( $fdata ['forms_design_id'], $fdata ['formreturn_id'] );
		
		if (! empty ( $pform_info )) {
			if ($pform_info ['form_design_parent_id'] > 0) {
				$forms_design_id = $pform_info ['form_design_parent_id'];
			} else {
				$forms_design_id = $fdata ['forms_design_id'];
			}
		} else {
			$forms_design_id = $fdata ['forms_design_id'];
		}
		// var_dump($forms_design_id);
		
		if ($forms_design_id == CUSTOME_I_INTAKEID) {
			// var_dump($form_info);
			$formdata = unserialize ( $form_info ['design_forms'] );
			
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
			$fcdata1i ['room_id'] = $formdata [0] [0] ['' . TAG_I_ROOM . '_1_locations_id'];
			$fcdata1i ['tags_status_in'] = 'Admitted';
			$fcdata1i ['forms_id'] = $this->session->data ['link_forms_id'];
			
			$this->load->model ( 'setting/tags' );
			
			if ($fdata ['tags_id'] != null && $fdata ['tags_id'] != "") {
				$ssemp_tag_id = $fdata ['tags_id'];
			} else if ($fdata ['emp_tag_id'] != null && $fdata ['emp_tag_id'] != "") {
				$ssemp_tag_id = $fdata ['emp_tag_id'];
			}
			
			if ($ssemp_tag_id != null && $ssemp_tag_id != "") {
				
				$this->model_setting_tags->updatexittag ( $fcdata1i, $fdata ['facilities_id'] );
				
				$this->model_setting_tags->editTags ( $ssemp_tag_id, $fcdata1i, $fdata ['facilities_id'] );
				
				$tags_id = $ssemp_tag_id;
			} else {
				$tags_id = $this->model_setting_tags->addTags ( $fcdata1i, $fdata ['facilities_id'] );
			}
			
			unset ( $this->session->data ['link_forms_id'] );
		}
		
		// var_dump($tags_id);
		if ($forms_design_id == CUSTOME_I_INTAKEID) {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			$emp_first_name = $tag_info ['emp_first_name'];
			$emp_tag_id = $tag_info ['emp_tag_id'];
			$client_tage = $emp_tag_id . ":" . $emp_first_name;
		} else if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $pdata ['tags_id'] );
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
		
		$this->load->model ( 'facilities/facilities' );
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
					
					if ($design_form [$arrss [0] . '_1_add_in_facility'] == '1') {
						if ($arrss [1] == 'tags_id') {
							// var_dump($design_form[$arrss[0]]);
							// var_dump($design_form[$arrss[0]]);
							// echo "<hr>";
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
		}
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		
		$clientstatusinfo = "";
		if($fdata ['tag_status_id'] != null && $fdata ['tag_status_id'] != ""){
			
			$this->load->model ( 'resident/resident' );
			
			$isdata = array();
			$isdata['tag_status_id'] = $fdata ['tag_status_id'];
			$isdata['tags_id'] = $fdata ['tags_id'];
			$isdata['facilities_id'] = $fdata ['facilities_id'];
			$isdata['facilitytimezone'] = $fdata ['facilitytimezone'];
			$isdata ['date_added'] = $date_added;
			$clientstatusinfo = $this->model_resident_resident->updateformstatus ( $isdata );
			
		}	
			
		
		if (($facilities_info ['is_discharge_form_enable'] == '1') && ($fdata ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
			// var_dump($fdata['forms_design_id']);
			
			if ($fdata ['tags_id'] != null && $fdata ['tags_id'] != "") {
				$sstagid = $fdata ['tags_id'];
			} elseif ($fdata ['emp_tag_id']) {
				$sstagid = $fdata ['emp_tag_id'];
			} else {
				$sstagid = $pdata ['tags_id'];
			}
			
			$data ['keyword_file'] = DISCHARGE_ICON;
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $fdata ['facilities_id'] );
			
			$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $form_info ['incident_number'] . ' has been added ' . $comments . $formusername;
			
			$this->load->model ( 'createtask/createtask' );
			$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $sstagid );
			
			if ($alldatas != NULL && $alldatas != "") {
				foreach ( $alldatas as $alldata ) {
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
					$facilities_idt = $result ['facilityId'];
					
					$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_idt, '1' );
					$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $facilities_idt );
				}
			}
		} else {
			
			if ($forms_design_id == CUSTOME_I_INTAKEID) {
				$data ['keyword_file'] = INTAKE_ICON;
				
				$this->load->model ( 'setting/keywords' );
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $fdata ['facilities_id'] );
				
				$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | Admitted -' . $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' has been admitted to ' . $facilities_info ['facility'] . ' ' . $comments;
			} else {
				
				$data ['notes_description'] = $client_tage . ' | ' . $form_info ['incident_number'] . ' has been added ' . $comments . $formusername . $clientstatusinfo;
			}
		}
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		if ($facilities_id != null && $facilities_id != "") {
			$facilities_id1 = $facilities_id;
		} else {
			$facilities_id1 = $fdata ['facilities_id'];
		}
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id1 );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_facilities_id = '" . $fdata ['parent_facilities_id'] . "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		
		
		
		if ($forms_design_id == CUSTOME_I_INTAKEID) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . ( int ) $tags_id . "'" );
			
			if ($tag_info ['forms_id'] > 0) {
				$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $tag_info ['forms_id'] . "'" );
			}
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $fdata ['formreturn_id'] . "'" );
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . ( int ) $tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		}
		
		if ($facilities_info ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
			$this->db->query ( $sql122 );
			
			$sql1221 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql1221 );
		}
		if ($facilities_info ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
			$this->db->query ( $sql13 );
			
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facilities_info ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				$this->model_notes_notes->updateuserpicturenotesform ( $s3file, $notes_id, $fdata ['formreturn_id'] );
				
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
					$this->model_notes_notes->updateuserverifiednotesform ( '2', $notes_id, $fdata ['formreturn_id'] );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
					$this->model_notes_notes->updateuserverifiednotesform ( '1', $notes_id, $fdata ['formreturn_id'] );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		if (($facilities_info ['is_discharge_form_enable'] == '1') && ($fdata ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'resident/resident' );
			$this->model_setting_tags->addcurrentTagarchive ( $sstagid );
			$this->model_setting_tags->updatecurrentTagarchive ( $sstagid, $notes_id );
			
			$this->model_resident_resident->updateDischargeTag ( $sstagid, $date_added );
		}
		
		$tags_ids_arr = array();
		
		
		
		$tags_ids_arr[] = $fdata['tags_id'];
		
		$this->load->model ( 'setting/tags' );
		foreach ( $formdata as $design_forms ) {
			foreach ( $design_forms as $key => $design_form ) {
				foreach ( $design_form as $key2 => $b ) {
					
					$arrss = explode ( "_1_", $key2 );
					
					if ($arrss [1] == 'tags_id') {
						// var_dump($design_form[$arrss[0]]);
						// var_dump($design_form[$arrss[0]]);
						// echo "<hr>";
						if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
							if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
								
								$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
								
								$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
								$tags_ids_arr[] = $tag_info ['tags_id'];
								$tadata = array ();
								$tadata ['substatus_idscomment'] = '';
								$tadata ['fixed_status_id'] = $fdata ['tag_status_id'];
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
								$tags_ids_arr[] = $tag_info ['tags_id'];
								// var_dump($tag_info);
								// echo "<hr>";
								$tadata = array ();
								$tadata ['substatus_idscomment'] = '';
								$tadata ['fixed_status_id'] = $fdata ['tag_status_id'];
								$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
								
								// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
								// $this->db->query($sql);
							}
						}
						// echo "<hr>";
					}
					
					if ($arrss [1] == 'tagsids') {
						
						if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
							foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
								
								$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
								$tag_info = $this->model_setting_tags->getTag ( $idst );
								
								$tags_ids_arr[] = $tag_info ['tags_id'];
								$tadata = array ();
								$tadata ['substatus_idscomment'] = '';
								$tadata ['fixed_status_id'] = $fdata ['tag_status_id'];
								$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
								
								// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
								// $this->db->query($sql);
							}
						}
						// echo "<hr>";
					}
				}
			}
		}
		
		
		
		
		if ($this->session->data ['case_number'] != null && $this->session->data ['case_number'] != "") {
			
			$this->load->model ( 'user/user' );
			
			if ($data ['user_id'] != null && $data ['user_id'] != "") {
				$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
			} else {
				$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id1 );
			}
			
			//$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET case_number = '" . $this->session->data ['case_number'] . "' WHERE forms_id = '" . ( int ) $fdata ['formreturn_id'] . "'" );
			$casedata['case_number'] = $this->session->data ['case_number'];
			$casedata['case_status'] = 0;
			$casedata['forms_ids'] = $fdata ['formreturn_id'];
			$casedata['notes_id'] = $notes_id;
			//$casedata['tags_ids'] = implode(',',$tags_ids_arr);
			$casedata['tags_ids'] = $tags_ids_arr['0']; //implode(',',$tags_ids_arr);
			$casedata['facilities_id'] = $facilities_id1;
			$casedata['signature'] = $data ['imgOutput'];
			$casedata['notes_pin'] = $data['notes_pin'];
			$casedata['user_id'] = $user_info ['username'];
			
			
			$this->load->model ( 'resident/casefile' );
			$allforms = $this->model_resident_casefile->insertCasefile ( $casedata );
		}
		
		unset ( $this->session->data ['case_number'] );
		
		$this->model_notes_notes->updatenoteform ( $notes_id );
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$this->load->model ( 'notes/notes' );
		$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
		$date_added1 = $noteDetails ['date_added'];
		
		$fdata3 = array ();
		$fdata3 ['notes_id'] = $notes_id;
		$fdata3 ['date_updated'] = $date_added;
		$fdata3 ['forms_id'] = $fdata ['formreturn_id'];
		
		$this->model_form_form->updateformnotes ( $fdata3 );
		$this->model_form_form->updateformnotes33 ( $fdata3 );
		
		$form_design_info = $this->model_form_form->getFormdata ( $forms_design_id );
		
		if ($form_design_info ['form_type'] == "Database") {
			if ($fdata ['formreturn_id'] != null && $fdata ['formreturn_id'] != "") {
				$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $fdata ['formreturn_id'] . "'";
				$this->db->query ( $slq1 );
			}
		}
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
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
		
		if ($form_info ['is_approval_required'] == '1') {
			if ($form_info ['is_final'] == '0') {
				$ftdata = array ();
				$ftdata ['forms_id'] = $fdata ['formreturn_id'];
				$ftdata ['incident_number'] = $form_info ['incident_number'];
				$ftdata ['facilitytimezone'] = $timezone_name;
				$ftdata ['facilities_id'] = $facilities_id1;
				
				$this->load->model ( 'createtask/createtask' );
				$this->model_createtask_createtask->createapprovalTak ( $ftdata );
			}
		}
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			
			$tadata ['substatus_idscomment'] = '';
			$tadata ['fixed_status_id'] = $fdata ['tag_status_id'];
			
			$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notes_id, $pdata ['tags_id'], $update_date, $tadata );
			
			$fdata22 = array ();
			$fdata22 ['forms_id'] = $fdata ['formreturn_id'];
			$fdata22 ['emp_tag_id'] = $pdata ['emp_tag_id'];
			$fdata22 ['tags_id'] = $pdata ['tags_id'];
			$fdata22 ['update_date'] = $update_date;
			
			$this->model_form_form->updateformTag ( $fdata22 );
			
			if ($forms_design_id == CUSTOME_INTAKEID) {
				
				$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
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
					$facilities_id = $facilities_id1;
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
					
					$this->load->model ( 'form/form' );
					$customlistvalues_info = $this->model_form_form->getcustomlistvaluesbyname ( $formdata [0] [0] ['' . TAG_GENDER . ''] );
					$fcdata1 ['gender'] = $customlistvalues_info ['customlistvalues_id'];
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
		} else if ($form_info ['tags_id']) {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date, $tadata );
		}
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$this->load->model ( 'notes/notes' );
		$this->model_notes_notes->updatedate ( $notes_id, $update_date );
		
		return $notes_id;
	}
	public function taskforminsertsign($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		$timezone_name = $fdata ['facilitytimezone'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		
		if ($fdata ['task_id'] != null && $fdata ['task_id'] != "") {
			$this->load->model ( 'createtask/createtask' );
			
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
			
			$pform_info = $this->model_form_form->getFormDatasparent ( $fdata ['forms_design_id'], $fdata ['formreturn_id'] );
			
			if (! empty ( $pform_info )) {
				if ($pform_info ['form_design_parent_id'] > 0) {
					$forms_design_id = $pform_info ['form_design_parent_id'];
				} else {
					$forms_design_id = $fdata ['forms_design_id'];
				}
			} else {
				$forms_design_id = $fdata ['forms_design_id'];
			}
			// var_dump($forms_design_id);
			
			if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $pdata ['tags_id'] );
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
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
			
			if (($facilities_info ['is_discharge_form_enable'] == '1') && ($fdata ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
				// var_dump($fdata['tags_id']);
				
				if (isset ( $fdata ['tags_id'] )) {
					$tags_id = $fdata ['tags_id'];
				} elseif ($fdata ['emp_tag_id']) {
					$tags_id = $fdata ['emp_tag_id'];
				} else {
					$tags_id = $pdata ['tags_id'];
				}
				
				$data ['keyword_file'] = DISCHARGE_ICON;
				
				$this->load->model ( 'setting/keywords' );
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $fdata ['facilities_id'] );
				
				$notes_description = $keywordData2 ['keyword_name'] . ' | ' . $form_info ['incident_number'] . ' has been added ';
				
				$this->load->model ( 'createtask/createtask' );
				$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tags_id );
				
				if ($alldatas != NULL && $alldatas != "") {
					foreach ( $alldatas as $alldata ) {
						$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
						$facilities_idt = $result ['facilityId'];
						$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_idt, '1' );
						$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
						$this->model_createtask_createtask->deteteIncomTask ( $facilities_idt );
					}
				}
			} else {
				if ($fdata ['forms_design_id'] == CUSTOME_INTAKEID) {
					
					$notes_description = $client_tage . ' | ' . $form_info ['incident_number'] . ' has been added ' . $formusername;
				} else {
					$notes_description = $client_tage . ' ' . $formusername;
				}
			}
			
			if ($pdata ['comments'] != null && $pdata ['comments']) {
				$pdata ['comments'] = $notes_description . ' ' . $pdata ['comments'];
			} else {
				$pdata ['comments'] = $notes_description;
			}
			
			if ($facilities_id != null && $facilities_id != "") {
				$facilities_id1 = $facilities_id;
			} else {
				$facilities_id1 = $fdata ['facilities_id'];
			}
			
			$result2 = $this->model_createtask_createtask->getStrikedatadetails ( $fdata ['task_id'] );
			$facilities_idt = $result2 ['facilityId'];
			$notesId = $this->model_createtask_createtask->inserttask ( $result2, $pdata, $facilities_idt, '' );
			
			if ($pdata ['perpetual_checkbox'] == '1') {
				
				$this->load->model ( 'notes/notes' );
				
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$datass = array ();
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				
				if ($pdata ['imgOutput']) {
					$datass ['imgOutput'] = $pdata ['imgOutput'];
				} else {
					$datass ['imgOutput'] = $pdata ['signature'];
				}
				
				$datass ['notes_pin'] = $pdata ['notes_pin'];
				$datass ['user_id'] = $pdata ['user_id'];
				
				$datass ['notetime'] = $notetime;
				$datass ['note_date'] = $date_added;
				
				$this->load->model ( 'createtask/createtask' );
				
				$this->load->model ( 'setting/keywords' );
				
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $facilities_idt );
				
				$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
				$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
				
				$datass ['keyword_file'] = $keywordData13 ['keyword_image'];
				
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $datass ['keyword_file'], $facilities_idt );
				
				$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
				
				$datass ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
				
				$datass ['date_added'] = $date_added;
				$datass ['linked_id'] = $result ['linked_id'];
				
				$notesida = $this->model_notes_notes->jsonaddnotes ( $datass, $facilities_idt );
				
				$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesida );
				
				if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
					$this->load->model ( 'notes/notes' );
					
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$this->load->model ( 'notes/tags' );
					$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
					$tadata = array ();
					$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesida, $taginfo ['tags_id'], $update_date, $tadata );
				}
			}
			
			$this->model_notes_notes->updatenoteform ( $notesId );
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notesId . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
				$this->db->query ( $sql122 );
				
				$sql1221 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notesId . "' ";
				$this->db->query ( $sql1221 );
			}
			if ($facilities_info ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notesId . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
				$this->db->query ( $sql13 );
				
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notesId . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					
					$this->model_notes_notes->updateuserpicture ( $s3file, $notesId );
					$this->model_notes_notes->updateuserpicturenotesform ( $s3file, $notesId, $fdata ['formreturn_id'] );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverified ( '2', $notesId );
						$this->model_notes_notes->updateuserverifiednotesform ( '2', $notesId, $fdata ['formreturn_id'] );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverified ( '1', $notesId );
						$this->model_notes_notes->updateuserverifiednotesform ( '1', $notesId, $fdata ['formreturn_id'] );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
			}
			
			if (($facilities_info ['is_discharge_form_enable'] == '1') && ($fdata ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
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
									$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notesId, $tag_info ['tags_id'], $update_date, $tadata );
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
									$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notesId, $tag_info ['tags_id'], $update_date, $tadata );
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
									
									// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
									// $this->db->query($sql);
								}
							}
							// echo "<hr>";
						}
					}
				}
			}
			
			$this->load->model ( 'createtask/createtask' );
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result2 ['tasktype'], $facilities_idt );
			$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
			
			if ($relation_keyword_id) {
				$this->load->model ( 'notes/notes' );
				$noteDetails = $this->model_notes_notes->getnotes ( $notesId );
				
				$this->load->model ( 'setting/keywords' );
				$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
				
				$data3 = array ();
				$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
				$data3 ['notes_description'] = $noteDetails ['notes_description'];
				
				$this->model_notes_notes->addactiveNote ( $data3, $notesId );
			}
			
			$this->model_createtask_createtask->updatetaskNote ( $fdata ['task_id'] );
			$this->model_createtask_createtask->deteteIncomTask ( $facilities_idt );
			// var_dump($notesId);
			
			$ttstatus = "1";
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$this->model_createtask_createtask->updateForm ( $notesId, $checklist_status, $ttstatus, $update_date );
			
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
			if ($form_info ['is_approval_required'] == '1') {
				if ($form_info ['is_final'] == '0') {
					$ftdata = array ();
					$ftdata ['forms_id'] = $fdata ['formreturn_id'];
					$ftdata ['incident_number'] = $form_info ['incident_number'];
					$ftdata ['facilitytimezone'] = $timezone_name;
					$ftdata ['facilities_id'] = $facilities_id1;
					
					$this->load->model ( 'createtask/createtask' );
					$this->model_createtask_createtask->createapprovalTak ( $ftdata );
				}
			}
			
			// die;
		}
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$this->load->model ( 'notes/notes' );
		$noteDetails = $this->model_notes_notes->getnotes ( $notesId );
		$date_added1 = $noteDetails ['date_added'];
		
		$fdata3 = array ();
		$fdata3 ['notes_id'] = $notesId;
		$fdata3 ['form_date_added'] = $date_added;
		$fdata3 ['date_added'] = $date_added1;
		$fdata3 ['form_date_added'] = $date_added1;
		$fdata3 ['date_updated'] = $date_added;
		$fdata3 ['forms_id'] = $fdata ['formreturn_id'];
		
		$this->model_form_form->updatetaskformnotes ( $fdata3 );
		
		$fdata3 = array ();
		$fdata3 ['notes_id'] = $notesId;
		$fdata3 ['date_updated'] = $date_added;
		$fdata3 ['forms_id'] = $fdata ['formreturn_id'];
		
		$this->model_form_form->updateformnotes ( $fdata3 );
		$this->model_form_form->updateformnotes33 ( $fdata3 );
		
		$form_design_info = $this->model_form_form->getFormdata ( fdata ['forms_design_id'] );
		
		if ($form_design_info ['form_type'] == "Database") {
			if ($fdata ['formreturn_id'] != null && $fdata ['formreturn_id'] != "") {
				$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $fdata ['formreturn_id'] . "'";
				$this->db->query ( $slq1 );
			}
		}
		
		if ($fdata ['forms_design_id'] == CUSTOME_INTAKEID) {
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
		}
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notesId, $pdata ['tags_id'], $update_date, $tadata );
			
			$fdata44 = array ();
			$fdata44 ['forms_id'] = $fdata ['formreturn_id'];
			$fdata44 ['emp_tag_id'] = $pdata ['emp_tag_id'];
			$fdata44 ['tags_id'] = $pdata ['tags_id'];
			$fdata44 ['update_date'] = $update_date;
			
			$this->model_form_form->updateformTag ( $fdata44 );
		} else if ($form_info ['tags_id']) {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesId, $taginfo ['tags_id'], $update_date, $tadata );
		}
		
		if ($fdata ['forms_design_id'] == CUSTOME_INTAKEID) {
			
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
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
				$facilities_id = $facilities_id1;
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
				
				$this->load->model ( 'form/form' );
				$customlistvalues_info = $this->model_form_form->getcustomlistvaluesbyname ( $formdata [0] [0] ['' . TAG_GENDER . ''] );
				$fcdata1 ['gender'] = $customlistvalues_info ['customlistvalues_id'];
				
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
		return $notesId;
	}
	public function insert3($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		$timezone_name = $fdata ['facilitytimezone'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$noteDetails = $this->model_notes_notes->getnotes ( $fdata ['updatenotes_id'] );
		$date_added1 = $noteDetails ['date_added'];
		
		$fdata3 = array ();
		$fdata3 ['notes_id'] = $fdata ['updatenotes_id'];
		
		$fdata3 ['user_id'] = $pdata ['user_id'];
		if ($pdata ['imgOutput']) {
			$fdata3 ['signature'] = $pdata ['imgOutput'];
		} else {
			$fdata3 ['signature'] = $pdata ['signature'];
		}
		
		$fdata3 ['notes_pin'] = $pdata ['notes_pin'];
		
		$fdata3 ['form_date_added'] = $date_added;
		$fdata3 ['date_added'] = $date_added1;
		$fdata3 ['form_date_added'] = $date_added1;
		$fdata3 ['date_updated'] = $date_added;
		$fdata3 ['forms_id'] = $fdata ['formreturn_id'];
		
		$this->model_form_form->updatetaskformnotes ( $fdata3 );
		
		$notes_id = $fdata ['updatenotes_id'];
		
		$this->model_notes_notes->updatenoteform ( $notes_id );
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
		
		$formusername = "";
		$fromdatas = $this->model_form_form->getFormdata ( $fdata ['forms_design_id'] );
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
		
		if ($formusername != null && $formusername != "") {
			$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
			$notes_description = $noteDetails ['notes_description'];
			
			if ($pdata ['comments'] != null && $pdata ['comments']) {
				$comments = ' | ' . $pdata ['comments'];
			}
			
			$notes_description2 = $notes_description . $formusername . $comments;
			
			$this->model_notes_notes->updatenotecontent ( $notes_description2, $notes_id );
			
			$update_date2 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$this->model_notes_notes->updatedatecount ( $notes_id, $update_date2 );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		
		if (($facilities_info ['is_discharge_form_enable'] == '1') && ($fdata ['forms_design_id'] == $facilities_info ['discharge_form_id'])) {
			// var_dump($fdata['tags_id']);
			
			if (isset ( $fdata ['tags_id'] )) {
				$tags_id = $fdata ['tags_id'];
			} elseif ($fdata ['emp_tag_id']) {
				$tags_id = $fdata ['emp_tag_id'];
			} else {
				$tags_id = $pdata ['tags_id'];
			}
			
			$data ['keyword_file'] = DISCHARGE_ICON;
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $fdata ['facilities_id'] );
			
			$notes_description2222 = ' | ' . $keywordData2 ['keyword_name'] . ' | ' . $form_info ['incident_number'] . ' has been added ';
			
			$this->load->model ( 'createtask/createtask' );
			$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tags_id );
			
			if ($alldatas != NULL && $alldatas != "") {
				foreach ( $alldatas as $alldata ) {
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
					$facilities_idt = $result ['facilityId'];
					$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $facilities_idt, '1' );
					$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $facilities_idt );
				}
			}
			
			$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
			$notes_description = $noteDetails ['notes_description'];
			
			if ($pdata ['comments'] != null && $pdata ['comments']) {
				$comments = ' | ' . $pdata ['comments'];
			}
			
			$notes_description2 = $notes_description . $notes_description2222 . $comments;
			
			$this->model_notes_notes->updatenotecontent ( $notes_description2, $notes_id );
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
				$this->db->query ( $sql122 );
				
				$sql1221 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql1221 );
			}
			if ($facilities_info ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
				$this->db->query ( $sql13 );
				
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					
					$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
					$this->model_notes_notes->updateuserpicturenotesform ( $s3file, $notes_id, $fdata ['formreturn_id'] );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverified ( '2', $notes_id );
						$this->model_notes_notes->updateuserverifiednotesform ( '2', $notes_id, $fdata ['formreturn_id'] );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverified ( '1', $notes_id );
						$this->model_notes_notes->updateuserverifiednotesform ( '1', $notes_id, $fdata ['formreturn_id'] );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
			}
			
			$data3 = array ();
			$data3 ['keyword_file'] = $keywordData2 ['keyword_image'];
			$data3 ['notes_description'] = $notes_description2;
			
			$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
			
			$update_date2 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$this->model_notes_notes->updatedatecount ( $notes_id, $update_date2 );
			
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
								
								// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
								// $this->db->query($sql);
							}
						}
						// echo "<hr>";
					}
				}
			}
		}
		
		$formdesign_info = $this->model_form_form->getFormDatadesign ( $form_info ['custom_form_type'] );
		$relation_keyword_id = $formdesign_info ['relation_keyword_id'];
		
		if ($relation_keyword_id) {
			
			$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
			
			$this->load->model ( 'setting/keywords' );
			$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
			
			$data3 = array ();
			$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
			$data3 ['notes_description'] = $noteDetails ['notes_description'];
			
			$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
		}
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $fdata ['updatenotes_id'], $pdata ['tags_id'], $update_date, $tadata );
			
			$fdata44 = array ();
			$fdata44 ['forms_id'] = $fdata ['formreturn_id'];
			$fdata44 ['emp_tag_id'] = $pdata ['emp_tag_id'];
			$fdata44 ['tags_id'] = $pdata ['tags_id'];
			$fdata44 ['update_date'] = $update_date;
			
			$this->model_form_form->updateformTag ( $fdata44 );
		} else if ($form_info ['tags_id']) {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $fdata ['updatenotes_id'], $taginfo ['tags_id'], $update_date, $tadata );
		}
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->model_notes_notes->updatedate ( $notes_id, $update_date );
		
		if ($form_info ['is_approval_required'] == '1') {
			if ($form_info ['is_final'] == '0') {
				$ftdata = array ();
				$ftdata ['forms_id'] = $fdata ['formreturn_id'];
				$ftdata ['incident_number'] = $form_info ['incident_number'];
				$ftdata ['facilitytimezone'] = $timezone_name;
				$ftdata ['facilities_id'] = $fdata ['facilities_id'];
				
				$this->load->model ( 'createtask/createtask' );
				$this->model_createtask_createtask->createapprovalTak ( $ftdata );
			}
		}
		
		$pform_info = $this->model_form_form->getFormDatasparent ( $fdata ['forms_design_id'], $fdata ['formreturn_id'] );
		
		// var_dump($pform_info);
		
		if (! empty ( $pform_info )) {
			if ($pform_info ['form_design_parent_id'] > 0) {
				$forms_design_id = $pform_info ['form_design_parent_id'];
			} else {
				$forms_design_id = $fdata ['forms_design_id'];
			}
		} else {
			$forms_design_id = $fdata ['forms_design_id'];
		}
		
		if ($forms_design_id == CUSTOME_INTAKEID) {
			
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
			
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
				$facilities_id = $this->customer->getId ();
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
				
				$this->load->model ( 'form/form' );
				$customlistvalues_info = $this->model_form_form->getcustomlistvaluesbyname ( $formdata [0] [0] ['' . TAG_GENDER . ''] );
				$fcdata1 ['gender'] = $customlistvalues_info ['customlistvalues_id'];
				
				// = $formdata[0][0][''.TAG_GENDER.''];
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
		
		$form_design_info = $this->model_form_form->getFormdata ( $forms_design_id );
		if ($form_design_info ['form_type'] == "Database") {
			if ($fdata ['formreturn_id'] != null && $fdata ['formreturn_id'] != "") {
				$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $fdata ['formreturn_id'] . "'";
				$this->db->query ( $slq1 );
			}
		}
		
		return $notes_id;
	}
	public function insert2($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		$timezone_name = $fdata ['facilitytimezone'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$fdata3 = array ();
		$fdata3 ['user_id'] = $pdata ['user_id'];
		
		if ($pdata ['imgOutput']) {
			$fdata3 ['signature'] = $pdata ['imgOutput'];
		} else {
			$fdata3 ['signature'] = $pdata ['signature'];
		}
		
		$fdata3 ['notes_pin'] = $pdata ['notes_pin'];
		
		$fdata3 ['form_date_added'] = $date_added;
		$fdata3 ['date_added'] = $date_added;
		$fdata3 ['form_date_added'] = $date_added;
		$fdata3 ['date_updated'] = $date_added;
		$fdata3 ['forms_id'] = $fdata ['form_parent_id'];
		
		$this->model_form_form->updatetaskformnotes ( $fdata3 );
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
			
			$notes_info = $this->model_notes_notes->getNote ( $fdata ['notes_id'] );
			$notes_description = $notes_info ['notes_description'];
			
			$notes_description2 = $notes_description . $comments;
			
			$this->model_notes_notes->updatenotecontent ( $notes_description2, $fdata ['notes_id'] );
		}
		
		$pform_info = $this->model_form_form->getFormDatasparent ( $fdata ['forms_design_id'], $fdata ['form_parent_id'] );
		
		if (! empty ( $pform_info )) {
			if ($pform_info ['form_design_parent_id'] > 0) {
				$forms_design_id = $pform_info ['form_design_parent_id'];
			} else {
				$forms_design_id = $fdata ['forms_design_id'];
			}
		} else {
			$forms_design_id = $fdata ['forms_design_id'];
		}
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['form_parent_id'] );
		$custom_form_type = $form_info ['custom_form_type'];
		// var_dump($form_info['tags_id']);
		
		if ($forms_design_id == CUSTOME_I_INTAKEID) {
			
			$formdata = unserialize ( $form_info ['design_forms'] );
			
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
			$this->model_setting_tags->editTags ( $form_info ['tags_id'], $fcdata1i, $fdata ['facilities_id'] );
		}
		
		if ($fdata ['task_id'] != null && $fdata ['task_id'] != "") {
			$this->load->model ( 'createtask/createtask' );
			
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['form_parent_id'] );
			$custom_form_type = $form_info ['custom_form_type'];
			
			$form_data = $this->model_form_form->getFormdata ( $custom_form_type );
			
			if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $pdata ['tags_id'] );
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
			
			$result2 = $this->model_createtask_createtask->getStrikedatadetails ( $fdata ['task_id'] );
			
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
				
				$frmname = $facilities_info ['facility'] . ' to ' . $formfacilities_name;
				
				$form_name = $client_tage . ' | ' . $form_data ['form_name'] . ' Completed in ' . $caltime . ' ' . $frmname . ' ' . $formusername;
			} else {
				$form_name = $client_tage . ' | ' . $form_data ['form_name'] . ' ' . $caltime . ' updated ' . $formusername;
			}
			
			if ($pdata ['comments'] != null && $pdata ['comments']) {
				$pdata ['comments'] = $form_name . $pdata ['comments'];
			} else {
				$pdata ['comments'] = $form_name;
			}
			
			if ($facilities_id != null && $facilities_id != "") {
				$facilities_id1 = $facilities_id;
			} else {
				$facilities_id1 = $fdata ['facilities_id'];
			}
			
			$facilities_idt = $result2 ['facilityId'];
			
			$notes_id = $this->model_createtask_createtask->inserttask ( $result2, $pdata, $facilities_idt, '' );
			
			if ($pdata ['perpetual_checkbox'] == '1') {
				
				$this->load->model ( 'notes/notes' );
				
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$datass = array ();
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				
				if ($pdata ['imgOutput']) {
					$datass ['imgOutput'] = $pdata ['imgOutput'];
				} else {
					$datass ['imgOutput'] = $pdata ['signature'];
				}
				
				$datass ['notes_pin'] = $pdata ['notes_pin'];
				$datass ['user_id'] = $pdata ['user_id'];
				
				$datass ['status_total_time'] = $status_total_time;
				$datass ['notetime'] = $notetime;
				$datass ['note_date'] = $date_added;
				
				$this->load->model ( 'createtask/createtask' );
				
				$this->load->model ( 'setting/keywords' );
				
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $facilities_idt );
				
				$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
				$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
				
				$datass ['keyword_file'] = $keywordData13 ['keyword_image'];
				
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $datass ['keyword_file'], $facilities_idt );
				
				$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
				
				$datass ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
				
				$datass ['date_added'] = $date_added;
				$datass ['linked_id'] = $result ['linked_id'];
				
				$notesida = $this->model_notes_notes->jsonaddnotes ( $datass, $facilities_idt );
				
				$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesida );
				
				if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
					$this->load->model ( 'notes/notes' );
					
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$this->load->model ( 'notes/tags' );
					$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
					$tadata = array ();
					$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesida, $taginfo ['tags_id'], $update_date, $tadata );
				}
			}
			
			if ($form_info ['custom_form_type'] > 0) {
				
				// var_dump($form_info['custom_form_type']);
				
				$this->load->model ( 'setting/activeforms' );
				$formexist_info = $this->model_setting_activeforms->getactiveform2id ( $form_info ['custom_form_type'] );
				
				// var_dump($formexist_info);
				
				$this->load->model ( 'setting/keywords' );
				$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $formexist_info ['keyword_id'] );
				
				$form_info = $this->model_form_form->getFormDatas ( $fdata ['form_parent_id'] );
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
				
				$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET parent_id = '" . $fdata ['notes_id'] . "' WHERE forms_id = '" . ( int ) $fdata ['form_parent_id'] . "' ";
				$this->db->query ( $sql122 );
				
				$sql12e2 = "UPDATE `" . DB_PREFIX . "notes` SET parent_id = '" . $fdata ['notes_id'] . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql12e2 );
			}
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['form_parent_id'] . "' ";
				$this->db->query ( $sql122 );
				
				$sql1221 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql1221 );
			}
			if ($facilities_info ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['form_parent_id'] . "' ";
				$this->db->query ( $sql13 );
				
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					
					$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
					$this->model_notes_notes->updateuserpicturenotesform ( $s3file, $notes_id, $fdata ['form_parent_id'] );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverified ( '2', $notes_id );
						$this->model_notes_notes->updateuserverifiednotesform ( '2', $notes_id, $fdata ['form_parent_id'] );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverified ( '1', $notes_id );
						$this->model_notes_notes->updateuserverifiednotesform ( '1', $notes_id, $fdata ['form_parent_id'] );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
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
									
									// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
									// $this->db->query($sql);
								}
							}
							// echo "<hr>";
						}
					}
				}
			}
			
			$this->model_createtask_createtask->updatetaskNote ( $fdata ['task_id'] );
			$this->model_createtask_createtask->deteteIncomTask ( $facilities_idt );
			// var_dump($notesId);
			
			$ttstatus = "1";
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$this->model_createtask_createtask->updateForm ( $notes_id, $checklist_status, $ttstatus, $update_date );
			
			$this->model_notes_notes->updatenoteform ( $notes_id );
			
			$this->model_notes_notes->updatenotesparentnotification ( $fdata ['notes_id'], $notes_id );
			
			/*
			 * $fdatad = array();
			 * $fdatad['forms_id'] = $fdata['forms_id'];
			 * $fdatad['update_date'] = $update_date;
			 * $this->model_form_form->updateformdate($fdatad);
			 */
			
			if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$tadata = array ();
				$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notes_id, $pdata ['tags_id'], $update_date, $tadata );
				
				$fdata2 = array ();
				$fdata2 ['forms_id'] = $fdata ['form_parent_id'];
				$fdata2 ['emp_tag_id'] = $pdata ['emp_tag_id'];
				$fdata2 ['tags_id'] = $pdata ['tags_id'];
				$fdata2 ['update_date'] = $update_date;
				
				$this->model_form_form->updateformTag ( $fdata2 );
			} else if ($form_info ['tags_id']) {
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->load->model ( 'notes/tags' );
				$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
				$tadata = array ();
				$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date, $tadata );
			}
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			/*
			 * $fdata233 = array();
			 * $fdata233['forms_id'] = $fdata['forms_id'];
			 * $fdata233['emp_tag_id'] = $pdata['emp_tag_id'];
			 * $fdata233['tags_id'] = $pdata['tags_id'];
			 * $fdata233['update_date'] = $update_date;
			 * $fdata233['notes_id'] = $notes_id;
			 *
			 * $this->model_form_form->updateform2($form_info, $fdata233);
			 */
		} else {
			
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$date_added = ( string ) $noteDate;
			
			$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			if ($pdata ['imgOutput']) {
				$data ['imgOutput'] = $pdata ['imgOutput'];
			} else {
				$data ['imgOutput'] = $pdata ['signature'];
			}
			
			$data ['notes_pin'] = $pdata ['notes_pin'];
			$data ['user_id'] = $pdata ['user_id'];
			
			if ($pdata ['comments'] != null && $pdata ['comments']) {
				$comments = ' | ' . $pdata ['comments'];
			}
			
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['form_parent_id'] );
			$custom_form_type = $form_info ['custom_form_type'];
			
			$form_data = $this->model_form_form->getFormdata ( $custom_form_type );
			
			if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $pdata ['tags_id'] );
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
			
			$formusername = "";
			
			$formdata = unserialize ( $form_info ['design_forms'] );
			
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
						
						if ($design_form [$arrss [0] . '_1_add_in_facility'] == '1') {
							if ($arrss [1] == 'tags_id') {
								// var_dump($design_form[$arrss[0]]);
								// var_dump($design_form[$arrss[0]]);
								// echo "<hr>";
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
			
			if ($form_info ['custom_form_type'] > 0) {
				
				// var_dump($form_info['custom_form_type']);
				
				$this->load->model ( 'setting/activeforms' );
				$formexist_info = $this->model_setting_activeforms->getactiveform2id ( $form_info ['custom_form_type'] );
				
				// var_dump($formexist_info['keyword_id']);
				
				$this->load->model ( 'setting/keywords' );
				$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $formexist_info ['keyword_id'] );
				
				// var_dump($keyword_info['relation_keyword_id']);
				
				if ($keyword_info ['relation_keyword_id'] > 0) {
					$keyword_info2 = $this->model_setting_keywords->getkeywordDetail ( $keyword_info ['relation_keyword_id'] );
					
					$keyword_file22 = $keyword_info2 ['keyword_image'];
					$keyword_name22 = $keyword_info2 ['keyword_name'];
					
					$data ['keyword_file'] = $keyword_file22;
					
					$keywordname = $keyword_name22 . ' | ';
					
					// var_dump($keyword_info2);
				}
				
				$form_name = $client_tage . ' | ' . $form_data ['form_name'] . ' | ' . $keywordname;
				
				// var_dump($form_name);
			} else {
				$form_name = $client_tage . ' | ' . $form_data ['form_name'] . ' updated ' . $comments . $formusername;
			}
			
			$data ['notes_description'] = $form_name;
			
			$data ['date_added'] = $date_added;
			$data ['note_date'] = $date_added;
			$data ['notetime'] = $notetime;
			
			if ($facilities_id != null && $facilities_id != "") {
				$facilities_id1 = $facilities_id;
			} else {
				$facilities_id1 = $fdata ['facilities_id'];
			}
			
			$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id1 );
			$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_facilities_id = '" . $fdata ['parent_facilities_id'] . "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['form_parent_id'] . "' ";
				$this->db->query ( $sql122 );
				
				$sql1221 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql1221 );
			}
			if ($facilities_info ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['form_parent_id'] . "' ";
				$this->db->query ( $sql13 );
				
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					
					$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
					$this->model_notes_notes->updateuserpicturenotesform ( $s3file, $notes_id, $fdata ['form_parent_id'] );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverified ( '2', $notes_id );
						$this->model_notes_notes->updateuserverifiednotesform ( '2', $notes_id, $fdata ['form_parent_id'] );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverified ( '1', $notes_id );
						$this->model_notes_notes->updateuserverifiednotesform ( '1', $notes_id, $fdata ['form_parent_id'] );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
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
									
									// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
									// $this->db->query($sql);
								}
							}
							// echo "<hr>";
						}
					}
				}
			}
			
			$this->model_notes_notes->updatenoteform ( $notes_id );
			$this->model_notes_notes->updatenotesparentnotification ( $fdata ['notes_id'], $notes_id );
			
			if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$tadata = array ();
				$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notes_id, $pdata ['tags_id'], $update_date, $tadata );
				
				$fdata2 = array ();
				$fdata2 ['forms_id'] = $fdata ['forms_id'];
				$fdata2 ['emp_tag_id'] = $pdata ['emp_tag_id'];
				$fdata2 ['tags_id'] = $pdata ['tags_id'];
				$fdata2 ['update_date'] = $update_date;
				
				$this->model_form_form->updateformTag ( $fdata2 );
			} else if ($form_info ['tags_id']) {
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->load->model ( 'notes/tags' );
				$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
				
				$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
			}
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			/*
			 * $fdata2 = array();
			 * $fdata2['forms_id'] = $fdata['forms_id'];
			 * $fdata2['emp_tag_id'] = $pdata['emp_tag_id'];
			 * $fdata2['tags_id'] = $pdata['tags_id'];
			 * $fdata2['update_date'] = $update_date;
			 * $fdata2['notes_id'] = $notes_id;
			 *
			 * $this->model_form_form->updateform2($form_info, $fdata2);
			 */
		}
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['form_parent_id'] );
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$fdata34 = array ();
		$fdata34 ['notes_id'] = $notes_id;
		$fdata34 ['archive_notes_id'] = $form_info ['notes_id'];
		// $fdata34['archive_forms_id'] = $archive_forms_id;
		$fdata34 ['archive_forms_id'] = $fdata ['archive_forms_id'];
		$fdata34 ['archive_forms_ids'] = $fdata ['archive_forms_ids'];
		$fdata34 ['forms_id'] = $fdata ['form_parent_id'];
		$fdata34 ['update_date'] = $update_date;
		
		$this->model_form_form->updateformnotesinfo ( $fdata34 );
		
		$this->model_form_form->updateformnotesinfo2 ( $fdata34 );
		
		/*
		 * $this->load->model('form/form');
		 *
		 * $notes_id = $fdata['notes_id'];
		 * $form_info = $this->model_form_form->getFormDatas($fdata['forms_id']);
		 * $formdesign_info = $this->model_form_form->getFormDatadesign($form_info['custom_form_type']);
		 * $relation_keyword_id = $formdesign_info['relation_keyword_id'];
		 *
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
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $fdata ['notes_id'], $pdata ['tags_id'], $update_date, $tadata );
			
			$fdat22a = array ();
			$fdat22a ['forms_id'] = $fdata ['form_parent_id'];
			$fdat22a ['emp_tag_id'] = $pdata ['emp_tag_id'];
			$fdat22a ['tags_id'] = $pdata ['tags_id'];
			$fdat22a ['update_date'] = $update_date;
			
			$this->model_form_form->updateformTag ( $fdat22a );
		}
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$this->load->model ( 'notes/notes' );
		$this->model_notes_notes->updatedate ( $fdata ['notes_id'], $update_date );
		
		if ($forms_design_id == CUSTOME_INTAKEID) {
			
			$this->load->model ( 'form/form' );
			$form_info = $this->model_form_form->getFormDatas ( $fdata ['form_parent_id'] );
			
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
				
				$notes_id = $fdata ['notes_id'];
				
				$this->load->model ( 'resident/resident' );
				$tags_form_info = $this->model_resident_resident->get_formbynotesid ( $notes_id );
				
				$tags_id = $form_info ['tags_id'];
				
				/*
				 * $emp_first_name =$this->session->data['design_forms'][0][0][''.TAG_FNAME.''];
				 * $emp_middle_name =$this->session->data['design_forms'][0][0][''.TAG_MNAME.''];
				 * $emp_last_name =$this->session->data['design_forms'][0][0][''.TAG_LNAME.''];
				 *
				 *
				 * $privacy = '';
				 * $sort_order = '0';
				 * $status = '1';
				 * $doctor_name = '';
				 * $emergency_contact = $this->session->data['design_forms'][0][0][''.TAG_PHONE.''];
				 *
				 * $date = str_replace('-', '/', $this->session->data['design_forms'][0][0][''.TAG_DOB.'']);
				 *
				 * $res = explode("/", $date);
				 * $createdate1 = $res[2]."-".$res[0]."-".$res[1];
				 *
				 * $dob = date('Y-m-d',strtotime($createdate1));
				 *
				 * if($this->session->data['design_forms'][0][0][''.TAG_AGE.'']){
				 * $age = $this->session->data['design_forms'][0][0][''.TAG_AGE.''];
				 * }else{
				 * $age = (date('Y') - date('Y',strtotime($dob)));
				 * }
				 * $medication = '';
				 * $locations_id = '';
				 * $facilities_id = $this->customer->getId();
				 * $upload_file = $this->session->data['upload_file'];
				 * $tags_pin = '';
				 *
				 * if($this->session->data['design_forms'][0][0][''.TAG_GENDER.''] == 'Male'){
				 * $gender = '1';
				 * }
				 * if($this->session->data['design_forms'][0][0][''.TAG_GENDER.''] == 'Female'){
				 * $gender = '2';
				 * }
				 *
				 * if($this->session->data['design_forms'][0][0][''.TAG_GENDER.''] == 'Inmate'){
				 * $gender = '1';
				 * }
				 * if($this->session->data['design_forms'][0][0][''.TAG_GENDER.''] == 'Patient'){
				 * $gender = '1';
				 * }
				 * if($this->session->data['design_forms'][0][0][''.TAG_GENDER.''] == 'Other'){
				 * $gender = '1';
				 * }
				 *
				 *
				 * if($this->session->data['design_forms'][0][0][''.TAG_GENDER.''] == ''){
				 * $gender = '1';
				 * }
				 *
				 * $emp_extid = $this->session->data['design_forms'][0][0][''.TAG_EXTID.''];
				 * $ssn = $this->session->data['design_forms'][0][0][''.TAG_SSN.''];
				 * $location_address = $this->session->data['design_forms'][0][0][''.TAG_ADDRESS.''];
				 * //$city = $this->session->data['design_forms'][0][0]['text_36668004'];
				 * //$state = $this->session->data['design_forms'][0][0]['text_49932949'];
				 * //$zipcode = $this->session->data['design_forms'][0][0]['text_64928499'];
				 */
				
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
				$facilities_id = $facilities_id1;
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
				$this->load->model ( 'form/form' );
				$customlistvalues_info = $this->model_form_form->getcustomlistvaluesbyname ( $formdata [0] [0] ['' . TAG_GENDER . ''] );
				$fcdata1 ['gender'] = $customlistvalues_info ['customlistvalues_id'];
				// $fcdata1['gender'] = $formdata[0][0][''.TAG_GENDER.''];
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
		
		return $notes_id;
	}
	public function saveCache($forms_id) {
		
		/*
		 * $form_info = $this->getFormdata($forms_id);
		 *
		 *
		 * $fields = $form_info['forms_fields'];
		 *
		 * $form_name = $form_info['form_name'];
		 * $display_image = $form_info['display_image'];
		 * $display_signature = $form_info['display_signature'];
		 * $forms_setting = $form_info['forms_setting'];
		 * $display_add_row = $form_info['display_add_row'];
		 * $display_content_postion = $form_info['display_content_postion'];
		 * $is_client_active = $form_info['is_client_active'];
		 *
		 *
		 *
		 * $template = new Template();
		 * $template->data['fields'] = $fields;
		 * $template->data['form_name'] = $form_name;
		 * $template->data['display_image'] = $display_image;
		 * $template->data['display_signature'] = $display_signature;
		 * $template->data['forms_setting'] = $forms_setting;
		 * $template->data['display_add_row'] = $display_add_row;
		 * $template->data['display_content_postion'] = $display_content_postion;
		 * $template->data['is_client_active'] = $is_client_active;
		 * $template->data['forms_design_id'] = $forms_id;
		 * $template->data['load'] = $this->load;
		 *
		 *
		 * //$html = $template->fetch($this->config->get('config_template') . '/template/form/formsave.php');
		 *
		 * //$sql1s = "UPDATE " . DB_PREFIX . "forms_design SET form_html = '" . $this->db->escape($html) . "' WHERE forms_id = '" . (int)$forms_id . "' ";
		 * //$this->db->query($sql1s);
		 *
		 * //$this->load->model('api/cache');
		 * //$this->model_api_cache->setcache($forms_id,$html);
		 */
	}
	public function updateformfacility($facilities_id, $forms_id) {
		$master = "UPDATE `" . DB_PREFIX . "forms` SET facilities_id = '" . $facilities_id . "' WHERE forms_id = '" . ( int ) $forms_id . "' ";
		$this->db->query ( $master );
	}
	public function gettagsformaintake($tags_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		
		$sql .= " where 1 = 1 and tags_id = '" . $tags_id . "' and custom_form_type = '" . CUSTOME_I_INTAKEID . "' and is_discharge = '0'  ";
		
		// echo $sql;
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function activeformsign($pdata, $fdata) {
		$facilities_id = $fdata ['facilities_id'];
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'api/smsapi' );
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'user/user' );
		$data = array ();
		$timezone_name = $fdata ['facilitytimezone'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		$date_added11 = date ( 'Y-m-d', strtotime ( 'now' ) );
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		// var_dump($fdata['formreturn_id']);
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
		$pform_info = $this->model_form_form->getFormDatasparent ( $fdata ['forms_design_id'], $fdata ['formreturn_id'] );
		
		if (! empty ( $pform_info )) {
			if ($pform_info ['form_design_parent_id'] > 0) {
				$forms_design_id = $pform_info ['form_design_parent_id'];
			} else {
				$forms_design_id = $fdata ['forms_design_id'];
			}
		} else {
			$forms_design_id = $fdata ['forms_design_id'];
		}
		// var_dump($forms_design_id);
		
		if ($forms_design_id == CUSTOME_I_INTAKEID) {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			$emp_first_name = $tag_info ['emp_first_name'];
			$emp_tag_id = $tag_info ['emp_tag_id'];
			$client_tage = $emp_tag_id . ":" . $emp_first_name;
		} else if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $pdata ['tags_id'] );
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
		
		$formusername = "";
		
		$formdata = unserialize ( $form_info ['design_forms'] );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		// var_dump($facilities_info['facility']);
		
		if ($fdata ['activeform_id'] != null && $fdata ['activeform_id'] != "") {
			$this->load->model ( 'setting/activeforms' );
			$activeform_info = $this->model_setting_activeforms->getActiveForm2 ( $fdata ['activeform_id'], $facilities_id );
			
			if ($activeform_info ['keyword_id'] != NULL && $activeform_info ['keyword_id'] != "") {
				$this->load->model ( 'setting/keywords' );
				$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $activeform_info ['keyword_id'] );
				
				$keyword_file11 = $keywordData2 ['keyword_image'];
				$keyword_name11 = $keywordData2 ['keyword_name'];
			}
		}
		
		$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
		$formdesign_info = $this->model_form_form->getFormDatadesign ( $form_info ['custom_form_type'] );
		$relation_keyword_id = $formdesign_info ['relation_keyword_id'];
		
		$sql="SELECT * FROM " . DB_PREFIX . "tasktype WHERE relation_keyword_id = '" . $activeform_info ['keyword_id']. "' and customer_key = '" . $activecustomer_id . "'";
		
		$query = $this->db->query ( $sql );
		$tasktypeinfo = $query->row;
		
		if($tasktypeinfo){
			
		 $tasktype=	$tasktypeinfo['task_id'];
			
		}			
		
		if ($relation_keyword_id) {
			$this->load->model ( 'setting/keywords' );
			$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
			
			$keyword_file22 = $keyword_info ['keyword_image'];
			$keyword_name22 = $keyword_info ['keyword_name'];
			
			$data ['keyword_file'] = $keyword_file11 . ',' . $keyword_file22;
			
			$keywordname = $keyword_name11 . ' | ';
			$keywordname11 = ' | ' . $keyword_name22 . ' FROM ';
		} else {
			
			$data ['keyword_file'] = $keyword_file11;
			
			$keywordname = $keyword_name11 . ' | ';
		}
		
		foreach ( $formdata as $design_forms ) {
			foreach ( $design_forms as $key => $design_form ) {
				foreach ( $design_form as $key2 => $b ) {
					
					$arrss = explode ( "_1_", $key2 );
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
					if ($design_form [$arrss [0] . '_1_add_in_facility'] == '1') {
						if ($arrss [1] == 'tags_id') {
							// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
							// var_dump($design_form[$arrss[0]]);
							// echo "<hr>";
							if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
									
									$this->load->model ( 'setting/tags' );
									$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
									$facilities_id = $tag_info ['facilities_id'];
								}
							}
						}
					}
				}
			}
		}
		
		if ($activeform_info ['is_formatted_notes'] == '1') {
			$data ['notes_description'] = $keywordname . $client_tage . $keywordname11 . ' ' . $facilities_info ['facility'] . ' to ' . $formfacilities_name . ' ' . $comments . $formusername;
		} else {
			$data ['notes_description'] = $keywordname . $client_tage . ' | ' . $form_info ['incident_number'] . ' has been added ' . $comments . $formusername;
		}
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		if ($facilities_id != null && $facilities_id != "") {
			$facilities_id1 = $facilities_id;
		} else {
			$facilities_id1 = $fdata ['facilities_id'];
		}
		
	
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id1 );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_id = '" . $notes_id . "',snooze_dismiss = '2',form_snooze_dismiss = '2' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		
		
		if($tasktype!="" && $tasktype!=null){
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET tasktype= '" .$tasktype. "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
			
		}		
		
		
		$form_design_info = $this->model_form_form->getFormdata ( $forms_design_id );
		if ($fdata ['formreturn_id'] != null && $fdata ['formreturn_id'] != "") {
			$slq12p = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $notes_id . "' where forms_id = '" . $fdata ['formreturn_id'] . "'";
			$this->db->query ( $slq12p );
		}
		
		if ($form_design_info ['form_type'] == "Database") {
			if ($fdata ['formreturn_id'] != null && $fdata ['formreturn_id'] != "") {
				$slq1 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $fdata ['formreturn_id'] . "'";
				$this->db->query ( $slq1 );
			}
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET generate_report = '5' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		}
		
		$this->load->model ( 'setting/tags' );
		
		$emptag = '';
		foreach ( $formdata as $design_forms ) {
			foreach ( $design_forms as $key => $design_form ) {
				foreach ( $design_form as $key2 => $b ) {
					
					$arrss = explode ( "_1_", $key2 );
					// var_dump($arrss);
					// echo "<hr>";
					
					if ($arrss [1] == 'facilities_id') {
						if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
							$form_facilities_id = $design_form [$arrss [0]];
							
							$sql1s = "UPDATE " . DB_PREFIX . "forms SET destination_facilities_id = '" . $form_facilities_id . "' WHERE forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
							$this->db->query ( $sql1s );
						}
					}
					
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
								
								$emptag = $tag_info ['tags_id'];
								
								$tadata = array ();
								$notes_tags_id = $this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
								
								$sql11s = "UPDATE " . DB_PREFIX . "notes_tags SET destination_id = '" . $form_facilities_id . "', destination_date = '" . $update_date . "', destination_status = 'Pending' WHERE notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
								$this->db->query ( $sql11s );
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
								$notes_tags_id = $this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
								
								$emptag = $tag_info ['tags_id'];
								
								$sql11s = "UPDATE " . DB_PREFIX . "notes_tags SET destination_id = '" . $form_facilities_id . "', destination_date = '" . $update_date . "', destination_status = 'Pending' WHERE notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
								$this->db->query ( $sql11s );
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
								$emptag = $tag_info ['tags_id'];
								
								// $sql = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $tag_info['tags_id'] . "' WHERE forms_id = '" . $fdata['formreturn_id'] . "'";
								// $this->db->query($sql);
							}
						}
						// echo "<hr>";
					}
				}
			}
		}
		
		if ($form_facilities_id != NULL && $form_facilities_id != "") {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "notes_by_facility` SET move_facilities_id = '" . $form_facilities_id . "', facilities_id = '" . $facilities_id1 . "', notes_id = '" . $notes_id . "', parent_id = '" . $notes_id . "', date_added = '" . $date_added . "' " );
		}
		
		// var_dump($fdata['activeform_id']);
		if ($fdata ['activeform_id'] != NULL && $fdata ['activeform_id'] != "") {
			
			$this->load->model ( 'setting/activeforms' );
			$activeform_info = $this->model_setting_activeforms->getActiveForm2 ( $fdata ['activeform_id'], $facilities_id );
			
			// var_dump($activeform_info);
			
			$thestime6 = date ( 'H:i:s' );
			// var_dump($thestime6);
			$snooze_time7 = 60;
			$stime8 = date ( "h:i A", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $thestime6 ) ) );
			// var_dump($stime8);
			$this->load->model ( 'createtask/createtask' );
			if ($activeform_info ['forms_ids'] != null && $activeform_info ['forms_ids'] != "") {
				$forms_ids = explode ( ',', $activeform_info ['forms_ids'] );
				
				foreach ( $forms_ids as $formsid ) {
					
					$formsinfo = $this->getFormdata ( $formsid );
					
					$data23 = array ();
					$data23 ['forms_design_id'] = $formsid;
					$data23 ['notes_id'] = $notes_id;
					$data23 ['tags_id'] = $tags_id;
					$data23 ['facilities_id'] = $facilities_id;
					$this->load->model ( 'form/form' );
					
					$formsinfo2 = array ();
					$formreturn_id = $this->model_form_form->addFormdata ( $formsinfo, $data23 );
					
					if ($formsinfo ['form_type'] == "Database") {
						if ($formreturn_id != null && $formreturn_id != "") {
							$slq12 = "UPDATE " . DB_PREFIX . "forms SET is_final = '1' where forms_id = '" . $formreturn_id . "'";
							$this->db->query ( $slq12 );
						}
					}
					
					$slq12pp = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $notes_id . "' where forms_id = '" . $formreturn_id . "'";
					$this->db->query ( $slq12pp );
					
					/*
					 * $sqls23sd = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$formsid."' and taskadded = '0' ";
					 * $query4ds = $this->db->query($sqls23sd);
					 *
					 * if($query4ds->num_rows == 0){
					 *
					 * $addtask = array();
					 *
					 * $snooze_time71 = 1;
					 * $thestime61 = date('H:i:s');
					 * $taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
					 *
					 * $addtask['taskDate'] = date('m-d-Y', strtotime($date_added));
					 * $addtask['end_recurrence_date'] = date('m-d-Y', strtotime($date_added));
					 * $addtask['recurrence'] = 'None';
					 * $addtask['recurnce_week'] = '';
					 * $addtask['recurnce_hrly'] = '';
					 * $addtask['recurnce_month'] = '';
					 * $addtask['recurnce_day'] = '';
					 * $addtask['taskTime'] = $taskTime; //date('H:i:s');
					 * $addtask['endtime'] = $stime8;
					 * $addtaskd['required_approval'] = $formsinfo['reqire_approval'];
					 *
					 * $addtask['description'] = $activeform_info['activeform_name'] .' | '. $formsinfo['form_name'];
					 * $addtask['assignto'] = $pdata['user_id'];
					 * $addtask['facilities_id'] = $facilities_id;
					 * $addtask['task_form_id'] = '';
					 * $addtask['pickup_facilities_id'] = '';
					 * $addtask['pickup_locations_address'] = '';
					 * $addtask['pickup_locations_time'] = '';
					 * $addtask['dropoff_facilities_id'] = '';
					 *
					 * $addtask['dropoff_locations_address'] = '';
					 * $addtask['dropoff_locations_time'] = '';
					 * $addtask['tasktype'] = '26';
					 * $addtask['numChecklist'] = '';
					 * $addtask['task_alert'] = '1';
					 *
					 * $addtask['alert_type_sms'] = '';
					 * $addtask['alert_type_notification'] = '1';
					 * $addtask['alert_type_email'] = '1';
					 * $addtask['rules_task'] = $formsid;
					 * $addtask['recurnce_hrly_recurnce'] = '';
					 * $addtask['daily_endtime'] = '';
					 *
					 *
					 * if($pdata['tags_id'] !=NULL && $pdata['tags_id'] !=""){
					 * $emp_tag_id = $pdata['tags_id'];
					 *
					 * }else if($form_info['tags_id'] != null && $form_info['tags_id'] != ""){
					 * $emp_tag_id = $form_info['tags_id'];
					 * }else{
					 * $emp_tag_id = $emptag;
					 * }
					 *
					 * $addtask['emp_tag_id'] = $emp_tag_id;
					 * $addtask['recurnce_hrly_perpetual'] = '';
					 * $addtask['completion_alert'] ='';
					 * $addtask['completion_alert_type_sms'] = '';
					 * $addtask['completion_alert_type_email'] = '';
					 * $addtask['task_status'] = '2';
					 * $addtask['visitation_tag_id'] = '';
					 * $addtask['visitation_start_facilities_id'] = '';
					 * $addtask['visitation_start_address'] = '';
					 * $addtask['visitation_start_time'] = '';
					 * $addtask['visitation_appoitment_facilities_id'] = '';
					 * $addtask['visitation_appoitment_address'] = '';
					 * $addtask['visitation_appoitment_time'] = '';
					 * $addtask['complete_endtime'] = '';
					 * $addtask['completed_alert'] = '';
					 * $addtask['completed_late_alert'] = '';
					 * $addtask['incomplete_alert'] = '';
					 * $addtask['deleted_alert'] = '';
					 *
					 *
					 * $addtask['attachement_form'] = '1';
					 * $addtask['tasktype_form_id'] = $formsid;
					 * $addtask['linked_id'] = $notes_id;
					 *
					 * //var_dump($addtask);
					 * $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
					 *
					 *
					 *
					 * }
					 */
				}
			}
			// die;
			
			// var_dump($activeform_info['onschedule_rules_module']);
			
			$onschedule_rules_modules = unserialize ( $activeform_info ['onschedule_rules_module'] );
			
			// var_dump($onschedule_rules_modules);
			// die;
			if (! empty ( $onschedule_rules_modules )) {
				foreach ( $onschedule_rules_modules as $onschedule_rules_module ) {
					
					if ($facilities_id1 != null && $facilities_id1 != "") {
						$new_facilities_id = $facilities_id1;
					} elseif ($form_facilities_id != null && $form_facilities_id != "") {
						$new_facilities_id = $form_facilities_id;
					} else {
						$new_facilities_id = $facilities_id;
					}
					
					$sqls23d = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $onschedule_rules_module ['task_random_id'] . "' and taskadded = '0' and `task_date` BETWEEN  '" . $date_added11 . " 00:00:00 ' AND  '" . $date_added11 . " 23:59:59' ";
					$query4d = $this->db->query ( $sqls23d );
					
					// if($query4d->num_rows == 0){
					
					$addtaskd = array ();
					
					$snooze_time71 = 5;
					$thestime61 = date ( 'H:i:s' );
					// var_dump($thestime6);
					
					$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
					
					$date = str_replace ( '-', '/', $onschedule_rules_module ['taskDate'] );
					$res = explode ( "/", $date );
					$taskDate = $res [1] . "-" . $res [0] . "-" . $res [2];
					
					$date2 = str_replace ( '-', '/', $onschedule_rules_module ['end_recurrence_date'] );
					$res2 = explode ( "/", $date2 );
					$end_recurrence_date = $res2 [1] . "-" . $res2 [0] . "-" . $res2 [2];
					$date_d = date ( 'Y-m-d' );
					
					// $addtaskd['taskDate'] = date('m-d-Y', strtotime($date_added));
					$addtaskd ['taskDate'] = date ( 'm-d-Y' );
					$addtaskd ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $end_recurrence_date ) );
					$addtaskd ['recurrence'] = $onschedule_rules_module ['recurrence'];
					$addtaskd ['recurnce_week'] = $onschedule_rules_module ['recurnce_week'];
					$addtaskd ['recurnce_hrly'] = $onschedule_rules_module ['recurnce_hrly'];
					$addtaskd ['recurnce_month'] = $onschedule_rules_module ['recurnce_month'];
					$addtaskd ['recurnce_day'] = $onschedule_rules_module ['recurnce_day'];
					$addtaskd ['taskTime'] = $taskTime; // date('H:i:s');
					$addtaskd ['endtime'] = $stime8;
					
					$onschedule_description11 = substr ( $formusername, 0, 150 ) . ((strlen ( $formusername ) > 150) ? '..' : '');
					
					$addtaskd ['description'] = $onschedule_rules_module ['description'] . ' | Type ' . $onschedule_description11;
					
					$addtaskd ['assignto'] = $onschedule_rules_module ['assign_to'];
					
					$addtaskd ['facilities_id'] = $new_facilities_id;
					$addtaskd ['task_form_id'] = $onschedule_rules_module ['task_form_id'];
					
					if ($onschedule_rules_module ['transport_tags'] != null && $onschedule_rules_module ['transport_tags'] != "") {
						$addtaskd ['transport_tags'] = explode ( ',', $onschedule_rules_module ['transport_tags'] );
					}
					
					$addtaskd ['pickup_facilities_id'] = $onschedule_rules_module ['pickup_facilities_id'];
					$addtaskd ['pickup_locations_address'] = $onschedule_rules_module ['pickup_locations_address'];
					$addtaskd ['pickup_locations_time'] = $onschedule_rules_module ['pickup_locations_time'];
					
					$addtaskd ['dropoff_facilities_id'] = $onschedule_rules_module ['dropoff_facilities_id'];
					$addtaskd ['dropoff_locations_address'] = $onschedule_rules_module ['dropoff_locations_address'];
					$addtaskd ['dropoff_locations_time'] = $onschedule_rules_module ['dropoff_locations_time'];
					
					$addtaskd ['tasktype'] = $onschedule_rules_module ['tasktype'];
					$addtaskd ['numChecklist'] = $onschedule_rules_module ['numChecklist'];
					$addtaskd ['task_alert'] = $onschedule_rules_module ['task_alert'];
					$addtaskd ['alert_type_sms'] = $onschedule_rules_module ['alert_type_sms'];
					$addtaskd ['alert_type_notification'] = $onschedule_rules_module ['alert_type_notification'];
					$addtaskd ['alert_type_email'] = $onschedule_rules_module ['alert_type_email'];
					$addtaskd ['rules_task'] = $onschedule_rules_module ['task_random_id'];
					
					$addtaskd ['recurnce_hrly_recurnce'] = $onschedule_rules_module ['recurnce_hrly_recurnce'];
					$addtaskd ['daily_endtime'] = $onschedule_rules_module ['daily_endtime'];
					
					if ($onschedule_rules_module ['daily_times'] != null && $onschedule_rules_module ['daily_times'] != "") {
						$addtaskd ['daily_times'] = explode ( ',', $onschedule_rules_module ['daily_times'] );
					}
					
					if ($onschedule_rules_module ['medication_tags'] != null && $onschedule_rules_module ['medication_tags'] != "") {
						$addtaskd ['medication_tags'] = explode ( ',', $onschedule_rules_module ['medication_tags'] );
						
						$aa = urldecode ( $onschedule_rules_module ['tags_medication_details_ids'] );
						$aa1 = unserialize ( $aa );
						
						$tags_medication_details_ids = array ();
						foreach ( $aa1 as $key => $mresult ) {
							$tags_medication_details_ids [$key] = $mresult;
						}
						$addtaskd ['tags_medication_details_ids'] = $tags_medication_details_ids;
					}
					
					if ($pdata ['tags_id'] != NULL && $pdata ['tags_id'] != "") {
						$emp_tag_id = $pdata ['tags_id'];
					} else if($emptag != null && $emptag != "") {
						$emp_tag_id = $emptag;
					}else{
						$emp_tag_id = $onschedule_rules_module ['emp_tag_id'];
					}
					
					$addtaskd ['emp_tag_id'] = $emp_tag_id;
					
					$addtaskd ['recurnce_hrly_perpetual'] = $onschedule_rules_module ['recurnce_hrly_perpetual'];
					$addtaskd ['completion_alert'] = $onschedule_rules_module ['completion_alert'];
					$addtaskd ['completion_alert_type_sms'] = $onschedule_rules_module ['completion_alert_type_sms'];
					$addtaskd ['completion_alert_type_email'] = $onschedule_rules_module ['completion_alert_type_email'];
					
					if ($onschedule_rules_module ['user_roles'] != null && $onschedule_rules_module ['user_roles'] != "") {
						$addtaskd ['user_roles'] = explode ( ',', $onschedule_rules_module ['user_roles'] );
					}
					
					if ($onschedule_rules_module ['userids'] != null && $onschedule_rules_module ['userids'] != "") {
						$addtaskd ['userids'] = explode ( ',', $onschedule_rules_module ['userids'] );
					}
					$addtaskd ['task_status'] = $onschedule_rules_module ['task_status'];
					
					$addtaskd ['visitation_tag_id'] = $onschedule_rules_module ['visitation_tag_id'];
					
					if ($onschedule_rules_module ['visitation_tags'] != null && $onschedule_rules_module ['visitation_tags'] != "") {
						$addtaskd ['visitation_tags'] = explode ( ',', $onschedule_rules_module ['visitation_tags'] );
					}
					$addtaskd ['visitation_start_facilities_id'] = $onschedule_rules_module ['visitation_start_facilities_id'];
					$addtaskd ['visitation_start_address'] = $onschedule_rules_module ['visitation_start_address'];
					$addtaskd ['visitation_start_time'] = $onschedule_rules_module ['visitation_start_time'];
					$addtaskd ['visitation_appoitment_facilities_id'] = $onschedule_rules_module ['visitation_appoitment_facilities_id'];
					$addtaskd ['visitation_appoitment_address'] = $onschedule_rules_module ['visitation_appoitment_address'];
					$addtaskd ['visitation_appoitment_time'] = $onschedule_rules_module ['visitation_appoitment_time'];
					$addtaskd ['complete_endtime'] = $onschedule_rules_module ['complete_endtime'];
					
					if ($onschedule_rules_module ['completed_times'] != null && $onschedule_rules_module ['completed_times'] != "") {
						$addtaskd ['completed_times'] = explode ( ',', $onschedule_rules_module ['completed_times'] );
					}
					$addtaskd ['completed_alert'] = $onschedule_rules_module ['completed_alert'];
					$addtaskd ['completed_late_alert'] = $onschedule_rules_module ['completed_late_alert'];
					$addtaskd ['incomplete_alert'] = $onschedule_rules_module ['incomplete_alert'];
					$addtaskd ['deleted_alert'] = $onschedule_rules_module ['deleted_alert'];
					$addtaskd ['attachement_form'] = $onschedule_rules_module ['attachement_form'];
					$addtaskd ['tasktype_form_id'] = $onschedule_rules_module ['tasktype_form_id'];
					$addtaskd ['required_approval'] = $onschedule_rules_module ['required_approval'];
					$addtaskd ['linked_id'] = $notes_id;
					$addtaskd ['formreturn_id'] = $fdata ['formreturn_id'];
					$addtaskd ['target_facilities_id'] = $facilities_id;
					
					$addtaskd ['reminder_alert'] = $onschedule_rules_module ['reminder_alert'];
					if ($onschedule_rules_module ['reminderminus'] != null && $onschedule_rules_module ['reminderminus'] != "") {
						$addtaskd ['reminderminus'] = explode ( ',', $onschedule_rules_module ['reminderminus'] );
					}
					
					if ($onschedule_rules_module ['reminderplus'] != null && $onschedule_rules_module ['reminderplus'] != "") {
						$addtaskd ['reminderplus'] = explode ( ',', $onschedule_rules_module ['reminderplus'] );
					}
					
					$this->load->model ( 'createtask/createtask' );
					$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskd, $new_facilities_id );
					
					$this->db->query ( "UPDATE `" . DB_PREFIX . "createtask` SET parent_id = '" . $notes_id . "' WHERE id = '" . ( int ) $task_id . "'" );
					// }
				}
			}
			
			if ($new_facilities_id != NULL && $new_facilities_id != "") {
				$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET parent_facilities_id = '" . $new_facilities_id . "' WHERE notes_id = '" . ( int ) $notes_id . "'" );
			}
			
			if ($activeform_info ['keyword_id'] != NULL && $activeform_info ['keyword_id'] != "") {
				
				$aaa = array ();
				
				$this->load->model ( 'notes/notes' );
				$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->load->model ( 'setting/keywords' );
				$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $activeform_info ['keyword_id'] );
				
				// var_dump($keyword_info['keyword_ids']);
				
				if ($keyword_info ['keyword_ids'] != NULL && $keyword_info ['keyword_ids'] != '') {
					$keyword_ids = explode ( ',', $keyword_info ['keyword_ids'] );
					foreach ( $keyword_ids as $kIdes ) {
						$keyword_info1 = $this->model_setting_keywords->getkeywordDetail ( $kIdes );
						$aaa [] = $keyword_info1 ['keyword_image'];
					}
					
					if (! empty ( $aaa )) {
						$data5 = array ();
						$data5 ['keyword_file'] = implode ( ',', $aaa );
						$data5 ['notes_description'] = $noteDetails ['notes_description'];
						$this->model_notes_notes->addactiveNote ( $data5, $notes_id );
					}
				}
			}
			
			$onschedule_action = unserialize ( $activeform_info ['rule_action'] );
			
			$rule_action_content = unserialize ( $activeform_info ['rule_action_content'] );
			
			if ($onschedule_action != null && $onschedule_action != "") {
				
				if (in_array ( '1', $onschedule_action )) {
					
					$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
					$sqls2 .= 'where 1 = 1 ';
					$sqls2 .= " and notes_id = '" . $notes_id . "'";
					$sqls2 .= " and send_sms = '0'";
					
					$query = $this->db->query ( $sqls2 );
					
					$note_info = $query->row;
					
					if ($query->num_rows) {
						$message = "Active Form Rule Created \n";
						$message .= date ( 'h:i A', strtotime ( $note_info ['notetime'] ) ) . "\n";
						$message .= $activeform_info ['activeform_name'] . "\n";
						$message .= substr ( $note_info ['notes_description'], 0, 150 ) . ((strlen ( $note_info ['notes_description'] ) > 150) ? '..' : '');
						
						// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
						
						$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
						
						if ($user_info ['phone_number'] != null && $user_info ['phone_number'] != '0') {
							$phone_number = $user_info ['phone_number'];
						}
						
						$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '" . $notes_id . "'";
						$query = $this->db->query ( $sql3e );
						
						$sdata = array ();
						$sdata ['message'] = $message;
						$sdata ['phone_number'] = $phone_number;
						$sdata ['facilities_id'] = $facilities_id;
						$response = $this->model_api_smsapi->sendsms ( $sdata );
						
						if ($rule_action_content ['auser_roles'] != null && $rule_action_content ['auser_roles'] != "") {
							
							$user_roles1 = $rule_action_content ['auser_roles'];
							
							foreach ( $user_roles1 as $user_role ) {
								$urole = array ();
								$urole ['user_group_id'] = $user_role;
								$tusers = $this->model_user_user->getUsers ( $urole );
								
								if ($tusers) {
									foreach ( $tusers as $tuser ) {
										if ($tuser ['phone_number'] != null && $tuser ['phone_number'] != "") {
											$number = $tuser ['phone_number'];
											
											$sdata = array ();
											$sdata ['message'] = $message;
											$sdata ['phone_number'] = $tuser ['phone_number'];
											$sdata ['facilities_id'] = $facilities_id;
											$response = $this->model_api_smsapi->sendsms ( $sdata );
										}
									}
								}
							}
						}
						
						if ($rule_action_content ['auserids'] != null && $rule_action_content ['auserids'] != "") {
							$userids1 = $rule_action_content ['auserids'];
							
							foreach ( $userids1 as $userid ) {
								$user_info = $this->model_user_user->getUserbyupdate ( $userid );
								if ($user_info) {
									if ($user_info ['phone_number'] != 0) {
										$number = $user_info ['phone_number'];
										
										$sdata = array ();
										$sdata ['message'] = $message;
										$sdata ['phone_number'] = $user_info ['phone_number'];
										$sdata ['facilities_id'] = $facilities_id;
										$response = $this->model_api_smsapi->sendsms ( $sdata );
									}
								}
							}
						}
					}
				}
				
				if (in_array ( '2', $onschedule_action )) {
					
					$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
					$sqls2 .= 'where 1 = 1 ';
					$sqls2 .= " and notes_id = '" . $notes_id . "'";
					$sqls2 .= " and send_email = '0'";
					
					$query = $this->db->query ( $sqls2 );
					
					$note_info = $query->row;
					
					if ($query->num_rows) {
						
						// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
						$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
						
						$facility = $this->model_facilities_facilities->getfacilities ( $note_info ['facilities_id'] );
						
						$facilityDetails ['username'] = $note_info ['user_id'];
						$facilityDetails ['email'] = $user_info ['email'];
						$facilityDetails ['phone_number'] = $user_info ['phone_number'];
						$facilityDetails ['sms_number'] = $facility ['sms_number'];
						$facilityDetails ['facility'] = $facility ['facility'];
						$facilityDetails ['address'] = $facility ['address'];
						$facilityDetails ['location'] = $facility ['location'];
						$facilityDetails ['zipcode'] = $facility ['zipcode'];
						$facilityDetails ['contry_name'] = $country_info ['name'];
						$facilityDetails ['zone_name'] = $zone_info ['name'];
						$facilityDetails ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
						$facilityDetails ['rules_name'] = $rule ['rules_name'];
						$facilityDetails ['rules_type'] = $allnotesId ['rules_type'];
						$facilityDetails ['rules_value'] = '';
						
						$message33 = "";
						
						$message33 .= $this->sendEmailtemplate ( $note_info, $activeform_info ['activeform_name'], '', '', $facilityDetails );
						
						$useremailids = array ();
						
						$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '" . $notes_id . "'";
						$query = $this->db->query ( $sql3e );
						
						if ($rule_action_content ['auser_roles'] != null && $rule_action_content ['auser_roles'] != "") {
							
							$user_roles1 = $rule_action_content ['auser_roles'];
							
							foreach ( $user_roles1 as $user_role ) {
								$urole = array ();
								$urole ['user_group_id'] = $user_role;
								$tusers = $this->model_user_user->getUsers ( $urole );
								
								if ($tusers) {
									foreach ( $tusers as $tuser ) {
										if ($tuser ['email'] != null && $tuser ['email'] != "") {
											
											$useremailids [] = $tuser ['email'];
										}
									}
								}
							}
						}
						
						if ($rule_action_content ['auserids'] != null && $rule_action_content ['auserids'] != "") {
							$userids1 = $rule_action_content ['auserids'];
							
							foreach ( $userids1 as $userid ) {
								$user_info = $this->model_user_user->getUserbyupdate ( $userid );
								if ($user_info) {
									if ($user_info ['email']) {
										$useremailids [] = $user_info ['email'];
									}
								}
							}
						}
						
						if ($user_info ['email'] != null && $user_info ['email'] != "") {
							$user_email = $user_info ['email'];
						}
						
						$edata = array ();
						$edata ['message'] = $message33;
						$edata ['subject'] = 'This is an Automated Alert Email.';
						$edata ['useremailids'] = $useremailids;
						$edata ['user_email'] = $user_email;
						
						$email_status = $this->model_api_emailapi->sendmail ( $edata );
					}
				}
				
				if (in_array ( '5', $onschedule_action )) {
					
					if ($rule_action_content ['highlighter_id'] != null && $rule_action_content ['highlighter_id'] != "") {
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$this->model_notes_notes->updateNoteHigh ( $notes_id, $rule_action_content ['highlighter_id'], $update_date );
					}
				}
				
				if (in_array ( '6', $onschedule_action )) {
					
					if ($rule_action_content ['color_id'] != null && $rule_action_content ['color_id'] != "") {
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$this->model_notes_notes->updateNoteColor ( $notes_id, $rule_action_content ['color_id'], $update_date );
					}
				}
			}
		}
		
		if ($facilities_info ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
			$this->db->query ( $sql122 );
			
			$sql1221 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql1221 );
		}
		if ($facilities_info ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "forms` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $fdata ['formreturn_id'] . "' ";
			$this->db->query ( $sql13 );
			
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facilities_info ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				$this->model_notes_notes->updateuserpicturenotesform ( $s3file, $notes_id, $fdata ['formreturn_id'] );
				
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
					$this->model_notes_notes->updateuserverifiednotesform ( '2', $notes_id, $fdata ['formreturn_id'] );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
					$this->model_notes_notes->updateuserverifiednotesform ( '1', $notes_id, $fdata ['formreturn_id'] );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		$this->model_notes_notes->updatenoteform ( $notes_id );
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$this->load->model ( 'notes/notes' );
		$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
		$date_added1 = $noteDetails ['date_added'];
		
		$fdata3 = array ();
		$fdata3 ['notes_id'] = $notes_id;
		$fdata3 ['date_updated'] = $date_added;
		$fdata3 ['forms_id'] = $fdata ['formreturn_id'];
		
		$this->model_form_form->updateformnotes ( $fdata3 );
		$this->model_form_form->updateformnotes33 ( $fdata3 );
		
		if ($form_info ['is_approval_required'] == '1') {
			if ($form_info ['is_final'] == '0') {
				$ftdata = array ();
				$ftdata ['forms_id'] = $fdata ['formreturn_id'];
				$ftdata ['incident_number'] = $form_info ['incident_number'];
				$ftdata ['facilitytimezone'] = $timezone_name;
				$ftdata ['facilities_id'] = $facilities_id;
				
				$this->load->model ( 'createtask/createtask' );
				$this->model_createtask_createtask->createapprovalTak ( $ftdata );
			}
		}
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			$notes_tags_id = $this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notes_id, $pdata ['tags_id'], $update_date, $tadata );
			
			$sql11s = "UPDATE " . DB_PREFIX . "notes_tags SET destination_id = '" . $form_facilities_id . "', destination_date = '" . $update_date . "', destination_status = 'Pending' WHERE notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
			$this->db->query ( $sql11s );
			
			$fdata22 = array ();
			$fdata22 ['forms_id'] = $fdata ['formreturn_id'];
			$fdata22 ['emp_tag_id'] = $pdata ['emp_tag_id'];
			$fdata22 ['tags_id'] = $pdata ['tags_id'];
			$fdata22 ['update_date'] = $update_date;
			
			$this->model_form_form->updateformTag ( $fdata22 );
			
			if ($forms_design_id == CUSTOME_INTAKEID) {
				
				$form_info = $this->model_form_form->getFormDatas ( $fdata ['formreturn_id'] );
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
					$this->load->model ( 'form/form' );
					$customlistvalues_info = $this->model_form_form->getcustomlistvaluesbyname ( $formdata [0] [0] ['' . TAG_GENDER . ''] );
					$fcdata1 ['gender'] = $customlistvalues_info ['customlistvalues_id'];
					// $fcdata1['gender'] = $formdata[0][0][''.TAG_GENDER.''];
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
		} else if ($form_info ['tags_id']) {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $form_info ['tags_id'] );
			$tadata = array ();
			$notes_tags_id = $this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date, $tadata );
			
			$sql11s = "UPDATE " . DB_PREFIX . "notes_tags SET destination_id = '" . $form_facilities_id . "', destination_date = '" . $update_date . "', destination_status = 'Pending' WHERE notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
			$this->db->query ( $sql11s );
		}
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$this->load->model ( 'notes/notes' );
		$this->model_notes_notes->updatedate ( $notes_id, $update_date );
		
		return $notes_id;
	}
	public function gettagsforma2($tags_id) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms";
		
		$sql .= " where 1 = 1 and tags_id = '" . $tags_id . "' and custom_form_type = '" . CUSTOME_I_INTAKEID . "' and is_discharge = '0'  ";
		
		// echo $sql;
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function sendEmailtemplate($result, $ruleName, $ruleType, $rulevalue, $facilityData) {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>This is an Automated Alert Email</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">This is an Automated Alert Email</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $facilityData ['username'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive ' . $ruleName . '! Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $facilityData ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">' . $ruleType . '- ' . $rulevalue . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . substr ( $result ['notes_description'], 0, 350 ) . ((strlen ( $result ['notes_description'] ) > 350) ? '..' : '') . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $result ['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result ['notetime'] ) ) . '
						</p>
					</td>
				</tr>
			</table></div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . $facilityData ['facility'] . '&nbsp;' . $facilityData ['address'] . '&nbsp;' . $facilityData ['location'] . '&nbsp;' . $facilityData ['zone_name'] . '&nbsp;' . $facilityData ['zipcode'] . ', ' . $facilityData ['contry_name'] . '
						</p>
					</td>
				</tr>
			</table></div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
		return $html;
	}
	public function getArcheiveFormDatas($forms_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "archive_forms WHERE forms_id = '" . $forms_id . "' " );
		return $query->rows;
	}
	public function getformdesign($notes_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "forms` WHERE notes_id = '" . $notes_id . "'";
		
		$query = $this->db->query ( $sql );
		
		$form_type = $query->row;
		
		// return $query->row;
		
		$sql2 = "SELECT * FROM `" . DB_PREFIX . "forms_design` WHERE 
         forms_id = '" . $form_type ['custom_form_type'] . "'";
		
		$query = $this->db->query ( $sql2 );
		
		$form_type = $query->row;
		
		if ($form_type ['form_type'] == 'Inventory') {
			
			$sql = "SELECT * FROM `" . DB_PREFIX . "forms` WHERE notes_id = '" . $notes_id . "'";
			
			$query = $this->db->query ( $sql );
			
			return $query->row;
		}
	}
	public function addformexisting($allform, $data23) {
		$sql = "INSERT INTO " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape ( serialize ( $allform ['design_forms'] ) ) . "',form_description = '" . $this->db->escape ( $allform ['form_description'] ) . "',rules_form_description = '" . $this->db->escape ( $allform ['rules_form_description'] ) . "', notes_id = '" . $data23 ['notes_id'] . "', facilities_id = '" . $data23 ['facilities_id'] . "', form_type = '3', custom_form_type = '" . $allform ['custom_form_type'] . "', upload_file = '" . $this->db->escape ( $allform ['upload_file'] ) . "', form_signature = '" . $this->db->escape ( $allform ['form_signature'] ) . "', is_final = '" . $this->db->escape ( $allform ['is_final'] ) . "', incident_number='" . $this->db->escape ( $allform ['incident_number'] ) . "', date_added = '" . $allform ['date_added'] . "', date_updated = '" . $allform ['date_updated'] . "', tags_id = '" . $allform ['tags_id'] . "', is_approval_required = '" . $this->db->escape ( $allform ['is_approval_required'] ) . "', page_number = '" . $allform ['page_number'] . "', form_design_parent_id = '" . $allform ['form_design_parent_id'] . "', form_parent_id = '" . $allform ['form_parent_id'] . "', status = '1',phone_device_id = '" . $this->db->escape ( $allform ['phone_device_id'] ) . "',is_android = '" . $this->db->escape ( $allform ['is_android'] ) . "',unique_id = '" . $this->db->escape ( $allform ['unique_id'] ) . "' ";
		
		$this->db->query ( $sql );
	}
	public function getMovementNoteData($data) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "shift` WHERE '" . $data ['notetime'] . "' BETWEEN `shift_starttime` AND `shift_endtime`";
		
		$query = $this->db->query ( $sql );
		
		$shift_time = $query->row;
		
		if ($shift_time) {
			
			$sql1 .= "SELECT * FROM `" . DB_PREFIX . "notes` WHERE `notetime` >= '" . $shift_time ['shift_starttime'] . "' AND `notetime` <= '" . $shift_time ['shift_endtime'] . "' AND DATE(`date_added`) = CURDATE() AND generate_report = '6' AND facilities_id='" . $data ['facilities_id'] . "' ";
		}
		
		$q = $this->db->query ( $sql1 );
		return $q->rows;
	}
	public function getNoteTagData($data) {
		$sql1 .= "SELECT * FROM `" . DB_PREFIX . "notes_tags` WHERE facilities_id='" . $data ['facilities_id'] . "' AND notes_id='" . $data ['notes_id'] . "' ";
		
		$query = $this->db->query ( $sql1 );
		return $query->rows;
	}
	public function getforms2($data = array()) {
		if ($data ['forms_ids'] != "0") {
			$sql = "SELECT f.incident_number as form_name, f.custom_form_type as forms_id FROM " . DB_PREFIX . "forms f LEFT JOIN dg_notes_tags nt ON nt.notes_id = f.notes_id ";
			
			$sql .= " where 1 = 1 and f.form_type != 'Database' ";
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
			
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				// $sql .= " and FIND_IN_SET('". $data['facilities_id']."', f.facilities_id)";
			}
			
			$sql .= " and f.custom_form_type IN (" . $data ['forms_ids'] . ") group by f.custom_form_type ";
			
			$query = $this->db->query ( $sql );
			
			return $query->rows;
		}
	}
	public function getFormchildone($data = array()) {
		$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,is_approval_required,form_parent_id,form_design_parent_id FROM " . DB_PREFIX . "forms where form_parent_id = '" . $data ['form_parent_id'] . "' and notes_id > 0 ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function gettagsforms2($data) {
		$sql = "SELECT f.* FROM " . DB_PREFIX . "forms f ";
		
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id ";
		
		$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
		
		if ($data ['archivedform'] != "1") {
			$sql .= " and f.is_discharge = '0' and f.is_final = '0' ";
		}
		
		if ($data ['archivedform'] == "1") {
			$sql .= " and f.is_discharge = '1' ";
		}
		
		if ($data ['add_case'] != null && $data ['add_case'] != "") {
			
			if ($data ['case_number'] == "1") {
				$sql .= " and f.case_number != '' ";
			}
		}
		
		if ($data ['case_number'] != "1" && $data ['case_number'] != "") {
			$sql .= " and f.case_number = '" . $data ['case_number'] . "' ";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			$sql .= " and f.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ";
		}
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			// if($data['add_case']!=null && $data['add_case']!=""){
			// $sql .= " and f.tags_id = '".$data['tags_id']."' ";
			// }else{
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
			// }
		}
		
		if ($data ['status'] != NULL && $data ['status'] != "") {
			$sql .= " and f.case_status = '" . $data ['status'] . "' ";
		}
		
		if (($data ['case_number'] != "1" && $data ['case_number'] != "") || ($data ['add_case'] != null && $data ['add_case'] != "")) {
			
			 //$sql .= " GROUP BY custom_form_type";
		}
		
		$sql .= " ORDER BY f.date_added";
		
		$sql .= " DESC";
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		 //echo '<br><br>GetTotalforms 2 -' . $sql; // die;
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gettagsforms3($data) {
		
		// echo '<pre>'; print_r($data); echo '</pre>';
		$sql = "SELECT f.* FROM " . DB_PREFIX . "forms f ";
		
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id ";
		
		$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
		
		if ($data ['archivedform'] != "1") {
			$sql .= " and f.is_discharge = '0' and f.is_final = '0' ";
		}
		
		if ($data ['archivedform'] == "1") {
			$sql .= " and f.is_discharge = '1' ";
		}
		
		/*
		 * if($data['add_case']!=null && $data['add_case']!=""){
		 * if($data['case_number'] =="1"){
		 * $sql .= " and f.case_number = '' ";
		 * }
		 * }
		 */
		
		if ($data ['add_case'] == "1") {
			$sql .= " and f.case_number = '' ";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			$sql .= " and f.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ";
		}
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
		}
		
		if ($data ['status'] != NULL && $data ['status'] != "") {
			$sql .= " and f.case_status = '" . $data ['status'] . "' ";
		}
		
		/*
		 * if(($data['case_number'] !="1" && $data['case_number'] !="")||($data['add_case']!=null && $data['add_case']!="")){
		 *
		 * $sql .= " GROUP BY case_number";
		 *
		 * }
		 */
		
		$sql .= " ORDER BY f.date_added";
		
		$sql .= " DESC";
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		// echo $sql;
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function updateFormWithCasenumber($forms_id, $data = array()) {
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$sql = "Update " . DB_PREFIX . "forms SET forms_id = '" . $forms_id . "', case_number = '" . $data ['case_number'] . "', case_status = '" . $data ['case_status'] . "', tags_id = '" . $data ['tags_id'] . "',date_added = '" . $date_added . "' WHERE forms_id = '" . $forms_id . "' ";
		
		$this->db->query ( $sql );
	}
	public function caseFormListSign($pdata, $fdata) {
		//echo '<pre>';  print_r($pdata); print_r($fdata); echo '</pre>'; die;	
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$facilities_id = $fdata ['facilities_id'];
		$timezone_name = $fdata ['facilitytimezone'];
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		
		$this->load->model ( 'setting/tags' );
		
		$tagname = "";
		
		$ids = array ();
		if ($pdata ['new_module']) {
			$description1 = "";
			$this->load->model ( 'notes/notes' );
			
			foreach ( $pdata ['new_module'] as $customlistvalues_id ) {
				
				if ($customlistvalues_id ['checkin'] == "1") {
					
					$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
					$ids [] = $customlistvalues_id ['customlistvalues_id'];
				}
			}
			
			$data ['customlistvalues_ids'] = $ids;
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$cname = $clientinfo ['name'];
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		$facilities_id3 = $fdata ['facilities_id'];
		
		$cdata = array ();
		$cdata ['facilities_id'] = $facilities_id3;
		$cdata ['tags_id'] = $fdata ['tags_id'];
		$cdata ['case_number'] = $fdata ['case_number'];
		
		$forminfo = $this->getCaseStatus ( $cdata );
		
		if ($forminfo ['forms_id'] != null && $forminfo ['forms_id'] != "") {
			$data ['notes_description'] = $cname . ' | case has been updated ' . $fdata ['form_name_list'] . ' Edited | case number ' . $fdata ['case_number'] . ' | ' . $comments;
		} else {
			$data ['notes_description'] = $cname . ' | case has been updated ' . $fdata ['form_name_list'] . ' Added | case number ' . $fdata ['case_number'] . ' | ' . $comments;
		}
		
		if ($fdata ['case_number'] != null && $fdata ['case_number'] != "") {
			$case_number = $fdata ['case_number'];
		} else {
			
			$case_number = "";
		}
		
		if ($fdata ['tags_id'] != null && $fdata ['tags_id'] != "") {
			
			$tags_id = $fdata ['tags_id'];
		} else {
			
			$tags_id = "";
		}
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		$data ['case_number'] = $case_number;
		$data ['form_type'] = '9';
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		// $data['tags_id'] = $tags_id;
		
		//echo '<pre>';  print_r($data); echo '</pre>'; //die;	
	
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id3 );
		
		$sql = "Update " . DB_PREFIX . "notes SET  case_number = '" . $case_number . "',is_tag = '" . $tag_info ['tags_id'] . "', form_type = '9' WHERE notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sql );
		
		
		$this->load->model ( 'user/user' );
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id3 );
		}
		
		$casedata = array();
		$casedata['case_number'] = $case_number;
		$casedata['case_status'] = $fdata['case_status'];
		$casedata['forms_ids'] = $fdata['forms_id'];
		$casedata['notes_id'] = $notes_id;
		$casedata['tags_ids'] = $data['tags_id'];
		$casedata['facilities_id'] = $facilities_id3;
		$casedata['notes_pin'] = $data['notes_pin'];
		$casedata['user_id'] = $user_info['username'];
		$casedata['signature'] = $data ['imgOutput'];
		$this->load->model ( 'resident/casefile' );
	
		$this->model_resident_casefile->insertCasefile ( $casedata );
		
		//$sql2 = "Update ". DB_PREFIX . "notes_tags SET notes_id = '" . $notes_id . "' WHERE case_number = '" . $case_number . "' ";
		//$this->db->query($sql2);
		
		return $notes_id;
	}
	public function getCaseStatus($data = array()) {
		$query = $this->db->query ( "SELECT forms_id,case_status FROM " . DB_PREFIX . "forms WHERE tags_id = '" . $data ['tags_id'] . "' AND  
    		case_number = '" . $data ['case_number'] . "' AND facilities_id = '" . $data ['facilities_id'] . "'" );
		
		return $query->row;
	}
	public function getCaseNumber($data = array()) {
		$sql1 .= "SELECT * FROM `" . DB_PREFIX . "forms` WHERE facilities_id='" . $data ['facilities_id'] . "' ";
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			$sql1 .= "AND tags_id='" . $data ['tags_id'] . "'";
		}
		
		if ($data['case_number'] != null && $data['case_number'] != "") {
			$sql1 .= " and LOWER(case_number like '%".strtolower($data['case_number'])."%' ) ";
			
		}
		
		$sql1 .= 'GROUP BY case_number';
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
		
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
		
			$sql1 .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				
		}
		
		$query = $this->db->query ( $sql1 );
		return $query->rows;
	}
	public function getFormsDatawithCaseNumber($data = array()) {
		$sql1 .= "SELECT * FROM `" . DB_PREFIX . "forms` WHERE facilities_id='" . $data ['facilities_id'] . "' AND case_number='" . $data ['case_number'] . "'";
		
		$query = $this->db->query ( $sql1 );
		return $query->rows;
	}
	public function deleteFormwithFormId($data = array()) {
		// var_dump($data);
		$sql = "UPDATE `" . DB_PREFIX . "forms`  SET tags_id= '0', case_number='' WHERE facilities_id='" . $data ['facilities_id'] . "' AND forms_id='" . $data ['forms_id'] . "'";
		
		// echo $sql; die;
		
		$response = $this->db->query ( $sql );
		
		return $response;
	}
	public function getTagsIdByCustomer($case_number) {
		$query = $this->db->query ( "SELECT tags_id FROM " . DB_PREFIX . "forms WHERE case_number = '" . $case_number . "' " );
		return $query->row;
	}
	public function getTotalformsadd($data) {
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			$sql = "SELECT COUNT(DISTINCT f.forms_id) as total FROM " . DB_PREFIX . "forms f  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id  ";
			
			$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
			
			if ($data ['archivedform'] != "1") {
				$sql .= " and f.is_discharge = '0' and f.is_final = '0' ";
			}
			
			if ($data ['archivedform'] == "1") {
				$sql .= " and f.is_discharge = '1' ";
			}
			
			/*
			 * if($data['add_case']!=null && $data['add_case']!=""){
			 * if($data['case_number'] =="1"){
			 * $sql .= " and f.case_number = '' ";
			 * }
			 * }
			 */
			
			if ($data ['add_case'] == "1") {
				$sql .= " and f.case_number = '' ";
			}
			
			if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
				
				$sql .= " and f.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ";
			}
			
			if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
				
				$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
			}
			
			if ($data ['status'] != NULL && $data ['status'] != "") {
				$sql .= " and f.case_status = '" . $data ['status'] . "' ";
			}
			
			$query = $this->db->query ( $sql );
			return $query->row ['total'];
		}
	}
	public function deletecase($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$facilities_id = $fdata ['facilities_id'];
		$timezone_name = $fdata ['facilitytimezone'];
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		
		$this->load->model ( 'setting/tags' );
		
		$tagname = "";
		
		$ids = array ();
		if ($pdata ['new_module']) {
			$description1 = "";
			$this->load->model ( 'notes/notes' );
			
			foreach ( $pdata ['new_module'] as $customlistvalues_id ) {
				
				if ($customlistvalues_id ['checkin'] == "1") {
					
					$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
					$ids [] = $customlistvalues_id ['customlistvalues_id'];
				}
			}
			
			$data ['customlistvalues_ids'] = $ids;
		}
		
		if ($pdata ['customlistvalues_ids']) {
						
			$this->load->model ( 'notes/notes' );
			
			foreach ( $pdata ['customlistvalues_ids'] as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				$customlistvalues_name = $custom_info ['customlistvalues_name'];
				
				$description1 .= ' | ' . $customlistvalues_name;
			}
			
			$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$cname = $clientinfo ['name'];
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		
		$this->load->model ( 'form/form' );
		$form_info = $this->model_form_form->getFormDatas ( $fdata['forms_id'] );
		

		$cdata['case_file_id'] = $fdata['case_file_id'];
		$this->load->model ( 'resident/casefile' );
		$case_info = $this->model_resident_casefile->getcasefile ( $cdata );
		
		
		$facilities_id3 = $fdata ['facilities_id'];
		



		$data ['notes_description'] = $cname . ' | ' . $form_info ['incident_number'] . ' has been deleted form case | case number ' . $case_info ['case_number'] . ' | ' . $comments;
		
		//echo '<pre>'; print_r($data); print_r($case_info); echo '</pre>'; die;
		
		if ($case_info ['case_number'] != null && $case_info ['case_number'] != "") {
			$case_number = $case_info ['case_number'];
		} else {
			
			$case_number = "";
		}

		if ($fdata ['tags_id'] != null && $fdata ['tags_id'] != "") {
			
			$tags_id = $fdata ['tags_id'];
		} else {
			
			$tags_id = "";
		}
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		$data ['case_number'] = $case_number;
		//$data ['form_type'] = '9';
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];

		//echo '<pre>'; print_r($data); print_r($case_info); echo '</pre>'; die;
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id3 );
		
		
		$sql = "Update " . DB_PREFIX . "notes SET  is_tag = '" . $tag_info ['tags_id'] . "', form_type = '9' WHERE notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sql );
		
		
		
		$sql = "UPDATE `" . DB_PREFIX . "forms`  SET is_case= '0' WHERE forms_id='" . $fdata['forms_id'] . "'";
		
		$this->db->query ( $sql );
		
		
		
		$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file WHERE notes_by_case_file_id = '" . $fdata['case_file_id'] . "' ";
		
		$query = $this->db->query ( $sql );
		
		
		if ($query->num_rows) {
			$result = $query->row;
			$forms_ids = @explode(',',$result['forms_ids']);
			$delete = $fdata['forms_id'];
				
			$index = @array_search($delete, $forms_ids);
		
			unset($forms_ids[$index]);
			$forms_ids_str = @implode(',', $forms_ids);
		
			$sql = "UPDATE `" . DB_PREFIX . "notes_by_case_file`  SET forms_ids= '". $forms_ids_str ."' WHERE notes_by_case_file_id='" . $fdata['case_file_id'] . "'";

			//echo $sql; die;
			$this->db->query ( $sql );
		
			$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET is_case = '0', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . $fdata['forms_id'] . "'" );
		}
		
		return $notes_id;
	}
	
	public function change_case_status($pdata, $fdata) {

		//echo '<pre>'; print_r($pdata); print_r($fdata); echo '</pre>'; //die;
	
		$this->load->model ( 'notes/notes' );
		$data = array ();
	
		$facilities_id = $fdata ['facilities_id'];
		$timezone_name = $fdata ['facilitytimezone'];
	
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
	
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
	
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
	
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
	
		$this->load->model ( 'setting/tags' );
	
		$tagname = "";
	
		$ids = array ();
		if ($pdata ['new_module']) {
			$description1 = "";
			$this->load->model ( 'notes/notes' );
				
			foreach ( $pdata ['new_module'] as $customlistvalues_id ) {
	
				if ($customlistvalues_id ['checkin'] == "1") {
						
					$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
					$ids [] = $customlistvalues_id ['customlistvalues_id'];
				}
			}
				
			$data ['customlistvalues_ids'] = $ids;
		}
		
		if ($pdata ['customlistvalues_ids']) {
						
			$this->load->model ( 'notes/notes' );
			
			foreach ( $pdata ['customlistvalues_ids'] as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				$customlistvalues_name = $custom_info ['customlistvalues_name'];
				
				$description1 .= ' | ' . $customlistvalues_name;
			}
			
			$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
		}
	
		
	
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
	
	
		$this->load->model ( 'form/form' );
		$form_info = $this->model_form_form->getFormDatas ( $fdata['forms_id'] );
	
	
		$cdata = array (
				'case_file_id' => $fdata ['case_file_id'],
				'facilities_id' => $this->customer->getId (),
				'limit' => CONFIG_LIMIT
		);

		//echo '<pre>'; print_r($cdata); echo '</pre>';// die;
	
	
		$this->load->model ( 'resident/casefile' );
			
		$case_info = $this->model_resident_casefile->getCaseNumber ( $cdata );
	
		//echo '<pre>'; print_r($case_info); echo '</pre>';// die;
	
	
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $case_info ['tags_ids'] );
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$cname = $clientinfo ['name'];
		
		

		$facilities_id3 = $fdata ['facilities_id'];
	
		
		if($fdata['case_status']==1){
			$case_status_name = ' Closed';
		} else if($fdata['case_status']==2){
			$case_status_name = ' Marked Final';
		}
		
		if ($case_info ['case_number'] != null && $case_info ['case_number'] != "") {
			$case_number = $case_info ['case_number'];
		} else {
				
			$case_number = "";
		}
	
		$data ['notes_description'] = $cname . ' | Case Number has been' . $case_status_name . ' | case number ' . $case_number . ' | ' . $comments;
		
	
		if ($tag_info ['tags_id'] != null && $tag_info ['tags_id'] != "") {
				
			$tags_id = $tag_info ['tags_id'];
		} else {
				
			$tags_id = "";
		}
	
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		$data ['case_number'] = $case_number;
		//$data ['form_type'] = '9';
	
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
	
		//echo '<pre>'; print_r($data); echo '</pre>'; die;
	
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id3 );
		
		$sql = "Update " . DB_PREFIX . "notes SET  is_tag = '" . $tag_info ['tags_id'] . "', form_type = '9' WHERE notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sql );
	
		//$sql = "Update " . DB_PREFIX . "notes SET  is_tag = '" . $tag_info ['tags_id'] . "' WHERE notes_id = '" . $notes_id . "' ";
		//$this->db->query ( $sql );
	
		$this->db->query ("UPDATE `" . DB_PREFIX . "notes_by_case_file`  SET case_status= '". $fdata['case_status'] ."' WHERE notes_by_case_file_id='" . $fdata['case_file_id'] . "'");
	
		//echo '<pre>'; print_r($fdata); echo '</pre>';
	
		if($fdata['case_status']!='' && $fdata['case_status']==2){
	
			$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file WHERE notes_by_case_file_id = '" . $fdata['case_file_id'] . "' ";
				
			$query = $this->db->query ( $sql );
				
			if ($query->num_rows) {
				$result = $query->row;
	
				$forms_ids = @explode(',',$result['forms_ids']);
				//echo '<pre>aaa'; print_r($forms_ids); echo '</pre>';
				foreach($forms_ids AS $val){
					$sql = "UPDATE `" . DB_PREFIX . "forms` SET is_final = '1', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . $val . "'";
					//echo $sql;
					$this->db->query ( $sql );
				}
			}
		}
	
	
		//die;
	
		return $notes_id;
	}
	
	
	public function getformmediabyid($notes_id, $forms_id) {
		$sql =  "SELECT notes_media_id FROM " . DB_PREFIX . "notes_media WHERE forms_id = '" . $forms_id . "' and notes_id = '" . $notes_id . "' ";
		$query = $this->db->query ( $sql);
		return $query->row;
	}
	
	
	public function getFormscase($data = array()) {
		//$sql1 .= "SELECT case_number FROM `" . DB_PREFIX . "forms` WHERE facilities_id='" . $data ['facilities_id'] . "' AND custom_form_type='" . $data ['forms_design_id'] . "' ";
		
		$sql = "SELECT f.case_number FROM " . DB_PREFIX . "notes_by_case_file f ";
		
		$sql .= " where 1 = 1 and f.facilities_id='" . $data ['facilities_id'] . "' and FIND_IN_SET('" . $data ['tags_id'] . "', f.tags_ids) ";
		
		
		$query = $this->db->query ( $sql );
		
		$case_number = array();
		
		$casenumber = "";
		
		foreach($query->rows as $row){
			$case_number[] = $row['case_number'];
		}
		
		if(!empty($case_number)){
		$casenumber = implode ( ',', $case_number );
		}
		return $casenumber;
	}
	
	
	public function getformreports($data = array()){
		$this->language->load('notes/notes');
		$formhtnl = "";
		
		$facilities_id = $data['facilities_id'];
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$unique_id = $facilityinfo ['customer_key'];
		$customer_key = $facilityinfo ['customer_key'];
		
		$facility = $facilityinfo ['facility'];
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$activecustomer_id = $customer_info ['activecustomer_id'];
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		$customers = unserialize ( $customer_info ['setting_data'] );
		
		if($customers['defaultrole_call'] != null && $customers['defaultrole_call'] != ""){
			$defaultrole_call = $customers['defaultrole_call'];
		}else{
			$defaultrole_call = 14;
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
		
		$config_admin_limit = 1000;	
		$page = 1;

		$form_search = $data['form_search'];
		$this->load->model('form/form');
		$this->load->model('notes/clientstatus');
		
		$fromdatas = $this->model_form_form->getFormdata ( $form_search);
		$relation_search = '';
		if($fromdatas['relation_keyword_id'] > 0){
			$activenote = $fromdatas['relation_keyword_id'];
			
			$this->load->model ( 'setting/keywords' );
			$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $activenote );
			
			if ($keyword_info ['relation_keyword_id'] > 0) {
				$relation_search = '1';
			}
			
		}else{
			
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "tasktype WHERE forms_id = '" . (int) $form_search. "' and customer_key = '" . (int)$activecustomer_id . "' and generate_report='1' " );
			$tasktypeinfo = $query->row;
			if ($tasktypeinfo ['relation_keyword_id'] > 0) {
				$activenote = $tasktypeinfo['relation_keyword_id'];
				
			}else{
				$activenote = '';
			}
			
		}
		
		$shift_name = $data['shift_name'];
		$date_added = $data['note_date_from'];
		$report_time = $data['search_time_start'];
		
		$data = array (
			'sort' => $sort,
			'case_detail' => '',
			'order' => $order,
			'searchdate' => '',
			'searchdate_app' => '1',
			'facilities_id' => $facilities_id,
			'note_date_from' => $data['note_date_from'],
			'note_date_to' => $data['note_date_to'],
			'group' => '',
			'search_facilities_id' => $data['search_facilities_id'],					
			'search_time_start' => $data['search_time_start'],
			'search_time_to' => $data['search_time_to'],					
			'keyword' => $data ['keyword'],
			//'form_search' => $this->request->get ['form_search'],
			'user_id' => $data ['user_id'],
			'highlighter' => '',
			'activenote' => $activenote,
			'relation_search' => $relation_search,
			'emp_tag_id' => $data['emp_tag_id'],
			'advance_searchapp' => $data['advance_search'],
			'tasktype' => '',
			'customer_key' => $customer_key,
			'case_number' => '',
			'advance_search'=>'1',
			'tag_classification_id' => '',
			'tag_status_id' => '',
			'manual_movement' => '',
			//'notes_facilities_ids' => $data['sssssdd'],
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit 
		);
		
		
		//var_dump($data);
		
		$results = $this->model_notes_notes->getnotess ( $data );
	
		if($form_search == '157'){
			foreach ( $results as $result ) {
				//var_dump($result['date_added']);
				if ($result ['emp_tag_id'] == '1') {
					$result_info = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
					
					if ($result ['emp_tag_id'] == '1') {
						$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
					} else {
						$alltag = array ();
					}
					
					if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
						if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
							$tagdata = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
							$privacy = $tagdata ['privacy'];
							
							$emp_tag_id = $tagdata ['emp_last_name'] . ' ' . $tagdata ['emp_first_name'];
							$emp_extid = $tagdata ['emp_extid'];
							
							$roominfo = $this->model_setting_locations->getlocation ( $tagdata ['room'] );
							$location_name = $roominfo ['location_name'];
						} else {
							$emp_tag_id = '';
							$privacy = '';
							$emp_extid = '';
							$location_name = '';
						}
						
						
						$keyImageSrc11 = "";
						if ($result ['keyword_file'] == '1') {
							$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
							foreach ( $allkeywords as $keyword ) {
								$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
							}
						}
						
						if ($result ['highlighter_id'] > 0) {
							$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
						} else {
							$highlighterData = array ();
						}
						$notetime1 = "";
						
						if ($result ['parent_id'] > 0) {
							$note_info1 = $this->model_notes_notes->getnotes ( $result ['parent_id'] );
							$notetime1 = date ( $time_format, strtotime ( $note_info1 ['notetime'] ) );
						}
						
						$mallkeywords = $this->model_notes_notes->getnotesmultiKey ( $result ['notes_id'] );
						
						
						$issue = "";
						$pickup = "";
						foreach($mallkeywords as $mallkeyword){
						
							if($mallkeyword['name'] == "Issued |"){
								$issue = $mallkeyword['value'];
							}
							
							if($mallkeyword['name'] == " Collected |"){
								$pickup = $mallkeyword['value'];
							}
						}
						$notess [] = array (
								'notes_id' => $result ['notes_id'],
								'issue' => $issue,
								'pickup' => $pickup,
								'emp_tag_id' => $emp_tag_id,
								'location_name' => $location_name,
								'emp_extid' => $emp_extid,
								'notes_description' => $keyImageSrc11 . ' ' . $result ['notes_description'],
								'facilities_id' => $result_info ['facility'],
								'highlighter_value' => $highlighterData ['highlighter_value'],
								'text_color' => $result ['text_color'],
								'notetime' => date ( $time_format, strtotime ( $result ['notetime'] ) ),
								'notetime1' => $notetime1,
								
								'user_id' => $result ['user_id'],
								'signature' => $result ['signature'],
								// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
								'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
								'note_date' => date ( $date_format, strtotime ( $result ['date_added'] ) ) 
						);
					}
				}
			}
		}elseif($form_search == '162' || $form_search == '154' || $form_search == '163'){
			$alltags = $this->model_notes_notes->getNotesTagscallls ( $data );
			
			$tagsid = 0;
			foreach($alltags as $result){
				
				$result_info = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
						
				if ($result ['emp_tag_id'] == '1') {
					$alltag = $this->model_notes_notes->getNotesTags( $result ['notes_id'] );
				} else {
					$alltag = array ();
				}
				
				
				//if($tagsid != $alltag ['tags_id']){
				
				if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
					$tagdata = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
					$privacy = $tagdata ['privacy'];
					
					$emp_tag_id = $tagdata ['emp_last_name'] . ' ' . $tagdata ['emp_first_name'];
					$emp_extid = $tagdata ['emp_extid'];
					
					$roominfo = $this->model_setting_locations->getlocation ( $tagdata ['room'] );
					$location_name = $roominfo ['location_name'];
				} else {
					$emp_tag_id = '';
					$privacy = '';
					$emp_extid = '';
					$location_name = '';
				}
				
				
				
				$keyImageSrc11 = "";
				if ($result ['keyword_file'] == '1') {
					$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					foreach ( $allkeywords as $keyword ) {
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
					}
				}
				
				if ($result ['highlighter_id'] > 0) {
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
				} else {
					$highlighterData = array ();
				}
				$notetime1 = "";
				
				if ($result ['parent_id'] > 0) {
					//$note_info1 = $this->model_notes_notes->getnotes ( $result ['parent_id'] );
					//$notetime1 = date ( $time_format, strtotime ( $note_info1 ['notetime'] ) );
				}
				
				
				//$query2 = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "notes_tags WHERE notes_id = '" . (int) $alltag['notes_id']. "' " );
				//$einfo = $query2->row;
				
				
				$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $alltag ['tag_status_id'] );
				
				
				$description1 = "";
				$notetime = "";
				
				$outuser_id = "";
				$outsignature = "";
				$outnotes_type = "";
				$outnotetime = "";
				
				$user_id = "";
				$signature = "";
				$notes_type = "";
				
				if(!empty($clientstatus_info)){
					
				if ($clientstatus_info ['type'] == '2' || $clientstatus_info ['type'] == '3') {
					$roleCall = $clientstatus_info ['name'];
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$outnotes_pin = $result ['notes_pin'];
					} else {
						$outnotes_pin = '';
					}
					
					$outuser_id = $result ['user_id'];
					$outsignature = $result ['signature'];
					$outnotes_type = $result ['notes_type'];
					
					$outnotetime = date ($time_format, strtotime ( $result ['notetime'] ) );
					
					
					
					$alltag2 = $this->model_notes_notes->getNotesTags222( $alltag ['tags_id'] );
					
					
					if ($alltag2 ['move_notes_id'] > 0) {
						$result1 = $this->model_notes_notes->getnotes ( $alltag2 ['move_notes_id'] );
						
						$alltag1 = $this->model_notes_notes->getNotesTags ( $result1 ['notes_id'] );
						
						if($alltag1 ['manual_movement'] == '1'){
							$manual_movement = 1;
						}
						
						
						if ($alltag1 ['emp_tag_id'] != null && $alltag1 ['emp_tag_id'] != "") {
							$clientstatus_info1 = $this->model_notes_clientstatus->getclientstatus ( $alltag1 ['tag_status_id'] );
							
							if ($clientstatus_info1 ['type'] == '3' || $clientstatus_info1 ['type'] == '4') {
								$roleCall = $clientstatus_info1 ['name'];
								
								$result2 = $this->model_notes_notes->getnotes ( $alltag2 ['notes_id'] );
						
								if ($result2 ['notes_pin'] != null && $result2 ['notes_pin'] != "") {
									$notes_pin = $result2 ['notes_pin'];
								} else {
									$notes_pin = '';
								}
								
								$user_id = $result2 ['user_id'];
								$signature = $result2 ['signature'];
								$notes_type = $result2 ['notes_type'];
								
								$notetime = date ( $time_format, strtotime ( $result2 ['notetime'] ) );
								
								
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
					
					
					$notess [] = array (
							'notes_id' => $result ['notes_id'],
							'emp_tag_id' => $emp_tag_id,
							'location_name' => $location_name,
							'emp_extid' => $emp_extid,
							'notes_description' => $keyImageSrc11 . ' ' . $result ['notes_description'],
							'facilities_id' => $result_info ['facility'],
							'highlighter_value' => $highlighterData ['highlighter_value'],
							'text_color' => $result ['text_color'],
							'notetime' => $outnotetime,
							'outnotetime' => $notetime,
							
							'roleCall' => $roleCall,
							
							'description' => $description1,
							'outuser_id' => $user_id,
							'outnotes_pin' => $notes_pin,
							'outsignature' => $signature,
							'outnotes_type' => $notes_type,
							
							'user_id' => $outuser_id,
							'signature' => $outsignature,
							'notes_type' => $outnotes_type,
							'notes_pin' => $outnotes_pin,
							// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
							'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
							'note_date' => date ( $date_format, strtotime ( $result ['date_added'] ) ) 
					);
					
					
				//}
				}
				}
				
				$tagsid = $alltag ['tags_id'];
			}
			
		}elseif($form_search == '53' || $form_search == '187'){
			$data3 = array ();
			$movecount = array ();
			$data3 ['facilities_id'] = $facilities_id;
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
			foreach ( $customforms as $customform ) {
				if ($customform ['type'] == "4") {
					$movecount [] = $customform ['tag_status_id'];
				}
			}
			
			$is_movement = 0;
			if ($movecount != null && $movecount != "") {
				
				$movecount = implode ( ",", $movecount );
				$is_movement = 1;
				//$movecount = $movecount;
			}
			
		
			
			$data['movecount'] = $movecount;
			
			$alltags = $this->model_notes_notes->getNotesTagscallls ( $data );
			
			foreach ( $alltags as $result ) {
				$result_info = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
						
				if ($result ['emp_tag_id'] == '1') {
					$alltag = $this->model_notes_notes->getNotesTags( $result ['notes_id'] );
				} else {
					$alltag = array ();
				}
				
				
				//if($tagsid != $alltag ['tags_id']){
				
				if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
					$tagdata = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
					$privacy = $tagdata ['privacy'];
					
					$emp_tag_id = $tagdata ['emp_last_name'] . ' ' . $tagdata ['emp_first_name'];
					$emp_extid = $tagdata ['emp_extid'];
					
					$roominfo = $this->model_setting_locations->getlocation ( $tagdata ['room'] );
					$location_name = $roominfo ['location_name'];
				} else {
					$emp_tag_id = '';
					$privacy = '';
					$emp_extid = '';
					$location_name = '';
				}
				
				
				
				$keyImageSrc11 = "";
				if ($result ['keyword_file'] == '1') {
					$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					foreach ( $allkeywords as $keyword ) {
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
					}
				}
				
				if ($result ['highlighter_id'] > 0) {
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
				} else {
					$highlighterData = array ();
				}
				
				$notetime1 = "";
				
				if ($result ['parent_id'] > 0) {
					//$note_info1 = $this->model_notes_notes->getnotes ( $result ['parent_id'] );
					//$notetime1 = date ( $time_format, strtotime ( $note_info1 ['notetime'] ) );
				}
				
				$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $alltag ['tag_status_id'] );
				
				
				$description1 = "";
				$notetime = "";
				
				$outuser_id = "";
				$outsignature = "";
				$outnotes_type = "";
				$outnotetime = "";
				
				$user_id = "";
				$signature = "";
				$notes_type = "";
				
				if(!empty($clientstatus_info)){
					if($alltag ['manual_movement'] == '1'){
						$manual_movement = 1;
						
					}
				
					$roleCall = $clientstatus_info ['name'];
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$outnotes_pin = $result ['notes_pin'];
					} else {
						$outnotes_pin = '';
					}
					
					$outuser_id = $result ['user_id'];
					$outsignature = $result ['signature'];
					$outnotes_type = $result ['notes_type'];
					
					$outnotetime = date ($time_format, strtotime ( $result ['notetime'] ) );
					
					
					
					$alltag2 = $this->model_notes_notes->getNotesTagsin( $alltag ['tags_id'],$defaultrole_call);
					
					
					if ($alltag2 ['move_notes_id'] > 0) {
						$result1 = $this->model_notes_notes->getnotes ( $alltag2 ['move_notes_id'] );
						
						$alltag1 = $this->model_notes_notes->getNotesTags ( $result1 ['notes_id'] );
						
						if($alltag1 ['manual_movement'] == '1'){
							$manual_movement = 1;
							
						}
						
						
						if ($alltag1 ['emp_tag_id'] != null && $alltag1 ['emp_tag_id'] != "") {
							$clientstatus_info1 = $this->model_notes_clientstatus->getclientstatus ( $alltag1 ['tag_status_id'] );
							
							if ($clientstatus_info1 ['type'] == '3' || $clientstatus_info1 ['type'] == '4') {
								$roleCall = $clientstatus_info1 ['name'];
								
								$result2 = $this->model_notes_notes->getnotes ( $alltag2 ['notes_id'] );
						
								if ($result2 ['notes_pin'] != null && $result2 ['notes_pin'] != "") {
									$notes_pin = $result2 ['notes_pin'];
								} else {
									$notes_pin = '';
								}
								
								$user_id = $result2 ['user_id'];
								$signature = $result2 ['signature'];
								$notes_type = $result2 ['notes_type'];
								
								$notetime = date ( $time_format, strtotime ( $result2 ['notetime'] ) );
								
								
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
					
					
					
					if($manual_movement == '1'){
						$description1 .=  ' <br> Manual Movement ';
					}
					
				
				$notess [] = array (
					'notes_id' => $result ['notes_id'],
					'emp_tag_id' => $emp_tag_id,
					'location_name' => $location_name,
					'emp_extid' => $emp_extid,
					'notes_description' => $keyImageSrc11 . ' ' . $result ['notes_description'],
					'facilities_id' => $result_info ['facility'],
					'highlighter_value' => $highlighterData ['highlighter_value'],
					'text_color' => $result ['text_color'],
					'notetime' => $outnotetime,
					'outnotetime' => $notetime,
					
					'roleCall' => $roleCall,
					
					'description' => $description1,
					'outuser_id' => $user_id,
					'outnotes_pin' => $notes_pin,
					'outsignature' => $signature,
					'outnotes_type' => $notes_type,
					
					'user_id' => $outuser_id,
					'signature' => $outsignature,
					'notes_type' => $outnotes_type,
					'notes_pin' => $outnotes_pin,
					// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
					'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
					'note_date' => date ( $date_format, strtotime ( $result ['date_added'] ) ) 
				);
				
				}
			}
			
		}else{
			foreach ( $results as $result ) {
			
				$result_info = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
					
				if ($result ['emp_tag_id'] == '1') {
					$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
				} else {
					$alltag = array ();
				}
				
				if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
					$tagdata = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
					$privacy = $tagdata ['privacy'];
					
					$emp_tag_id = $tagdata ['emp_last_name'] . ' ' . $tagdata ['emp_first_name'];
					$emp_extid = $tagdata ['emp_extid'];
					
					$roominfo = $this->model_setting_locations->getlocation ( $tagdata ['room'] );
					$location_name = $roominfo ['location_name'];
				} else {
					$emp_tag_id = '';
					$privacy = '';
					$emp_extid = '';
					$location_name = '';
				}
				
				
				$keyImageSrc11 = "";
				if ($result ['keyword_file'] == '1') {
					$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					foreach ( $allkeywords as $keyword ) {
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
					}
				}
				
				if ($result ['highlighter_id'] > 0) {
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
				} else {
					$highlighterData = array ();
				}
				$notetime1 = "";
				
				if ($result ['parent_id'] > 0) {
					$note_info1 = $this->model_notes_notes->getnotes ( $result ['parent_id'] );
					$notetime1 = date ( $time_format, strtotime ( $note_info1 ['notetime'] ) );
				}
				
				$notess [] = array (
					'notes_id' => $result ['notes_id'],
					'emp_tag_id' => $emp_tag_id,
					'location_name' => $location_name,
					'emp_extid' => $emp_extid,
					'notes_description' => $keyImageSrc11 . ' ' . $result ['notes_description'],
					'facilities_id' => $result_info ['facility'],
					'highlighter_value' => $highlighterData ['highlighter_value'],
					'text_color' => $result ['text_color'],
					'notetime' => date ( $time_format, strtotime ( $result ['notetime'] ) ),
					'notetime1' => $notetime1,
					
					'user_id' => $result ['user_id'],
					'signature' => $result ['signature'],
					// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
					'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
					'note_date' => date ( $date_format, strtotime ( $result ['date_added'] ) ) 
				);
			}
		}
	
	
	
		
		$customlist_values = $this->model_notes_notes->getcustomlistvalues();
		
		$s = array ();
		$s ['facilities_id'] = $facilities_id;
		$s ['sbfacility'] = 1;
		$sfacilities = $this->model_facilities_facilities->getfacilitiess ( $s );
		

		$this->load->model ( 'user/user' );
		
		$allcusers = $this->model_user_user->getUsersByFacility ( $facilities_id );
		
		$template = new Template ();
		$template->data ['formparents'] = $formparents;
		$template->data ['formdatas'] = $formdatas;
		$template->data ['customlists1'] = $customlists1;
		$template->data ['parent_id'] = $results ['parent_id'];
		$template->data['movementnotes'] = $movementnotes;	
		$template->data['inmovementnotes'] = $inmovementnotes;	
		$template->data['outmovementnotes'] = $outmovementnotes;
		$template->data ['notess'] = $notess;
		$template->data ['journals'] = $notess;		
		$template->data ['note_date_from'] = $note_date_from;
		$template->data ['note_date_to'] = $note_date_to;
		$template->data ['dtnnotess'] = $dtnnotess;
		$template->data ['subgnotess'] = $subgnotess;
		$template->data ['name'] = $name;
		$template->data ['shift_name'] = $shift_name;
		$template->data ['sfacilities'] = $sfacilities;
		$template->data ['facility'] = $facility;
		$template->data ['note_info'] = $note_info;
		$template->data ['t2facility'] = $t2facility;
		$template->data ['load'] = $this->load;
		$template->data ['shift_name'] = $shift_name;
		$template->data ['date_added'] = $date_added;
		$template->data ['notetime2'] = $notetime2;
		$template->data ['time_notes'] = $time_notes;
		$template->data ['sfacilities'] = $sfacilities;
		$template->data ['customlist'] = $customlist_values;
		$template->data ['deputy_name'] = $notes_info ['user_id'];
		$template->data ['report_date'] = $date_added;
		$template->data ['sallcusers'] = $allcusers;
		$template->data ['emp_extid'] = $emp_extid;
		$template->data ['report_time'] = $report_time;
		$template->data ['observation_name']=$tags_data['emp_first_name']." ".$tags_data['emp_last_name'];				
		$template->data ['observation_ssn']=$tags_data['ssn'];
		$template->data ['observation_date']=date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
		$template->data ['observation_userid']=$notes_info['user_id'];
		$template->data ['observation_notess'] = $observation_notess;
		
		if ($results ['is_final'] == 1) {
			$template->data ['supervisor_name'] = $notes_info ['user_id'];
		} else {
			$template->data ['supervisor_name'] = 'N/A';
		}

		if($form_search != null && $form_search != ""){
			$reportfilename = $form_search;
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
				$formhtnl = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
			}
		}else{
			$formhtnl = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
		}
		
		
		//var_dump($formhtnl);die;
		return $formhtnl;
		
	}
	
	
	public function jsongetiframeform($data = array()){
		$sql =  "SELECT * FROM " . DB_PREFIX . "forms WHERE iframevalue = '" . $data['iframevalue'] . "' order by forms_id DESC limit 0,1 ";
		$query = $this->db->query ( $sql);
		return $query->row;
	}
	
	public function getFormparentnotesId($parent_id) {
		$query = $this->db->query ( "SELECT forms_id from " . DB_PREFIX . "forms WHERE notes_id = '" . $parent_id . "' " );
		return $query->row;
	}
	
	
	public function gettagsformsforcase($data) {
		
		//echo '<pre>'; print_r($data); echo '</pre>'; //die;
		// if ($data ['tagsids'] != NULL && $data ['tagsids'] != "") {
		$sql = "SELECT DISTINCT f.* FROM " . DB_PREFIX . "forms f ";
		
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id  ";
		
		$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
		
		if ($data ['archivedform'] != "1") {
			$sql .= " and f.is_discharge = '0'  ";
		}
		
		if ($data ['archivedform'] == "1") {
			$sql .= " and f.is_discharge = '1' ";
		}
		
		if ($data ['is_case'] == "1") {
			$sql .= " and f.is_case = '0' ";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			$sql .= " and f.`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59' ";
		}
		
		if ($data ['add_case'] != null && $data ['add_case'] != "") {
			if ($data ['case_number'] == "1") {
				$sql .= " and f.case_number != '' ";
			}
		}
		if ($data ['custom_form_type'] != "1" && $data ['custom_form_type'] != "") {
			$sql .= " and f.custom_form_type = '" . $data ['custom_form_type'] . "' ";
		}
		if ($data ['case_number'] != "1" && $data ['case_number'] != "") {
			$sql .= " and f.case_number = '" . $data ['case_number'] . "' ";
		}
		
		if ($data ['page_name2'] == "addcase") {
			if ($data ['tagsids'] != "") {
				$sql .= " and nt.tags_id IN(" . $data ['tagsids'] . ")";
			} else if($data ['tags_id']!=""){
				$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
			}
		} else {
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
		}
		
		if (($data ['case_number'] != "1" && $data ['case_number'] != "") || ($data ['add_case'] != null && $data ['add_case'] != "")) {
			// $sql .= " GROUP BY case_number";
		}
		
		if ($data ['groupby'] == "1") {
			$sql .= " GROUP BY f.custom_form_type";
		}
		
		$sql .= " ORDER BY f.date_added";
		$sql .= " DESC";
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 200;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
		// }
	}
	
}