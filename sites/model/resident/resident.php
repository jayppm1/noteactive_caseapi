<?php
class Modelresidentresident extends Model {
	public function tagmedication($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		$data ['keyword_file'] = MEDICATION_ICON;
		
		$this->load->model ( 'setting/keywords' );
		
		$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
		
		/*
		 * $medicationf = "";
		 * foreach($this->session->data['medication'] as $key=>$medication){
		 *
		 * $medication_info =
		 * $this->model_resident_resident->get_medication($medication);
		 * $medicationf .= $medication_info['drug_name'].', ';
		 *
		 * }
		 */
		
		/*
		 * if($this->request->post['comments'] != null &&
		 * $this->request->post['comments']){
		 * $comments = ' | '.$this->request->post['comments'];
		 * }
		 */
		
		$medication_tags_array = explode ( ",", $fdata ['medication_tags'] );
		
		if (! empty ( $medication_tags_array )) {
			
			$this->load->model ( 'resident/resident' );
			$this->load->model ( 'setting/tags' );
			$med_data = array ();
			foreach ( $medication_tags_array as $medication_tag ) {
				
				$med_data = array (
						'tags_medication_details_id' => $medication_tag,
						'tags_id' => $fdata ['tags_id'] 
				);
				
				$medication_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $medication_tag );
				
				$drug_quantity = $medication_info ['drug_mg'] - $medication_info ['drug_alertnate'];
				
				$this->model_setting_tags->updateQuantityMedication ( $medication_tag, $drug_quantity );
			}
		}
		
		if ($tag_info ['emp_first_name']) {
			// $emp_tag_id = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
			
			$this->load->model ( 'setting/locations' );
			$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
			
			$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
		} else {
			$emp_tag_id = $tag_info ['emp_tag_id'];
		}
		
		if ($tag_info) {
			$medication_tags .= $emp_tag_id . ', ';
		}
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$cname = $clientinfo ['name'];
		
		$description = '';
		$description .= $keywordData2 ['keyword_name'];
		$description .= ' | ';
		$description .= ' Completed | ' . date ( 'h:i A', strtotime ( $notetime ) ) . ' ';
		$description .= ' Medication given to | ';
		$description .= ' ' . $cname;
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$description .= ' | ' . $this->db->escape ( $pdata ['comments'] );
		}
		// $description .= ' | ';
		
		// $data['notes_description'] = $keywordData2['keyword_name'].' | '.
		// $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' |
		// '.$medicationf . $comments;
		
		$data ['notes_description'] = $description;
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		// var_dump($data);
		
		// die;
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		if ($fdata ['medication_tags']) {
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'createtask/createtask' );
			
			// var_dump($this->request->get['medication_tags']);
			
			$medication_tags1 = explode ( ',', $fdata ['medication_tags'] );
			
			$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			foreach ( $medication_tags1 as $medicationtag ) {
				$drugs = array ();
				$mdrug_info = $this->model_resident_resident->get_medication ( $medicationtag );
				
				if ($mdrug_info) {
					
					// $task_content = 'Resident ' . $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$task_content = 'Resident ' . $cname;
					
					$tdata1 = array ();
					$tdata1 ['notes_id'] = $notes_id;
					$tdata1 ['task_content'] = $task_content;
					$tdata1 ['date_added'] = $date_added;
					$tdata1 ['tags_id'] = $tag_info ['tags_id'];
					$tdata1 ['drug_name'] = $mdrug_info ['drug_name'];
					$tdata1 ['dose'] = $mdrug_info ['dose'];
					$tdata1 ['drug_type'] = $mdrug_info ['drug_type'];
					$tdata1 ['frequency'] = $mdrug_info ['frequency'];
					$tdata1 ['instructions'] = $mdrug_info ['instructions'];
					$tdata1 ['count'] = $mdrug_info ['count'];
					$tdata1 ['task_type'] = '2';
					
					$this->model_createtask_createtask->insertTaskmedicine ( $mdrug_info, $data, $tdata1 );
				}
			}
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			if ($tag_info ['emp_tag_id'] != null && $tag_info ['emp_tag_id'] != "") {
				$this->load->model ( 'notes/notes' );
				$tadata = array ();
				$this->model_notes_notes->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
			}
		}
		
		$this->model_notes_notes->updatenotetags_med ( $notes_id );
		return $notes_id;
	}
	public function tagmedication2($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		// $data['keyword_file'] = MEDICATION_ICON;
		
		// $this->load->model('setting/keywords');
		
		// $keywordData2 =
		// $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
		
		/*
		 * $medicationf = "";
		 * foreach($this->session->data['medication'] as $key=>$medication){
		 *
		 * $medication_info =
		 * $this->model_resident_resident->get_medication($medication);
		 * $medicationf .= $medication_info['drug_name'].', ';
		 *
		 * }
		 */
		
		/*
		 * if($this->request->post['comments'] != null &&
		 * $this->request->post['comments']){
		 * $comments = ' | '.$this->request->post['comments'];
		 * }
		 */
		
		if ($tag_info ['emp_first_name']) {
			$emp_tag_id = $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'];
			
			$this->load->model ( 'setting/locations' );
			$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
			
			$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
		} else {
			$emp_tag_id = $tag_info ['emp_tag_id'];
		}
		
		if ($tag_info) {
			$medication_tags .= $emp_tag_id . ' ';
		}
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$cname = $clientinfo ['name'];
		
		$description = '';
		// $description .= $keywordData2['keyword_name'];
		// $description .= ' | ';
		// $description .= ' Completed for | '.date('h:i A',
		// strtotime($notetime)) .' ';
		$description .= ' Health Form updated | ';
		$description .= ' ' . $cname;
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$description .= ' | ' . $this->db->escape ( $pdata ['comments'] );
		}
		
		// $description .= ' | ';
		
		// $data['notes_description'] = $keywordData2['keyword_name'].' | '.
		// $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' |
		// '.$medicationf . $comments;
		
		$data ['notes_description'] = $description;
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		// var_dump($data);
		
		// die;
		
		$this->model_notes_notes->updatetagsmedicinearchive1 ( $fdata ['tags_id'] );
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		$archive_tags_medication_id = $fdata ['archive_tags_medication_id'];
		
		$mdata2 = array ();
		$mdata2 ['notes_id'] = $notes_id;
		$mdata2 ['tags_id'] = $fdata ['tags_id'];
		$mdata2 ['archive_tags_medication_id'] = $archive_tags_medication_id;
		
		$this->model_notes_notes->updatetagsmedicinearchive2 ( $mdata2 );
		return $notes_id;
	}
	public function allrolecallsign($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		
		// var_dump($this->session->data['tagsids']);
		// var_dump($this->session->data['role_calls']);
		
		/*
		 * if(empty($this->session->data['tagsids'])){
		 * $girl1 = 0;
		 * $boy1 = 0;
		 * $nboy1 = 0;
		 * $total1 = 0;
		 *
		 * $outtags = array();
		 *
		 * //var_dump($this->session->data['role_calls']);
		 * //echo "<hr>";
		 * $tagname111 = array();
		 * foreach($this->session->data['role_calls'] as $key=>$rolecall){
		 * $outtags[$key] = $rolecall['role_call'];
		 * $tag_info = $this->model_setting_tags->getTag($key);
		 *
		 * $this->model_resident_resident->updatetagrolecall($key, '1');
		 *
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 *
		 * $tagname .=
		 * $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
		 *
		 * }
		 *
		 * //$tagname .= implode(", ",$tagname111);
		 *
		 * $outtags2 = $this->model_setting_tags->getrolecallby($outtags,
		 * $this->customer->getId());
		 *
		 * if($outtags2){
		 * foreach($outtags2 as $outtag){
		 * $this->model_resident_resident->updatetagrolecall($outtag['tags_id'],
		 * '2');
		 * }
		 * }
		 *
		 *
		 *
		 * //$data['emp_tag_id'] = $emp_tag_id;
		 * //$data['tags_id'] = $tags_id;
		 *
		 * $data['keyword_file'] = HEADCOUNT_ICON;
		 *
		 * $this->load->model('setting/keywords');
		 * $keywordData2 =
		 * $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
		 *
		 *
		 * if($this->request->post['comments'] != null &&
		 * $this->request->post['comments']){
		 * $comments = ' | '.$this->request->post['comments'];
		 * }
		 *
		 *
		 *
		 * if($this->request->post['customlistvalues_ids']){
		 *
		 * $this->load->model('notes/notes');
		 *
		 * foreach($this->request->post['customlistvalues_ids'] as
		 * $customlistvalues_id){
		 *
		 * $custom_info =
		 * $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
		 *
		 * $customlistvalues_name = $custom_info['customlistvalues_name'];
		 *
		 * $description1 .= ' | '.$customlistvalues_name;
		 *
		 * }
		 *
		 * $data['customlistvalues_ids'] =
		 * $this->request->post['customlistvalues_ids'];
		 *
		 * }
		 *
		 *
		 * $this->load->model('facilities/facilities');
		 * $facilityinfo =
		 * $this->model_facilities_facilities->getfacilities($this->customer->getId());
		 * $this->load->model('notes/notes');
		 *
		 * if($facilityinfo['config_tags_customlist_id'] !=NULL &&
		 * $facilityinfo['config_tags_customlist_id'] !=""){
		 * $d2 = array();
		 * $d2['customlistvalueids'] =
		 * $facilityinfo['config_tags_customlist_id'];
		 * $customlistvalues =
		 * $this->model_notes_notes->getcustomlistvalues($d2);
		 * if($customlistvalues){
		 *
		 * foreach($customlistvalues as $customlistvalue){
		 *
		 * $customlistvalues_total =
		 * $this->model_setting_tags->gettotalcustomlistvaluebyid($customlistvalue['customlistvalues_id'],
		 * $customlistvalue['gender'] ,'1', $this->customer->getId());
		 *
		 * if($customlistvalues_total > 0 ){
		 * $total1 = $total1 + $customlistvalues_total;
		 * $boygirl .= $customlistvalues_total .'
		 * '.$customlistvalue['customlistvalues_name'].' ';
		 *
		 * $boygirl .= 'and ';
		 * }
		 *
		 * }
		 * }
		 * }
		 *
		 * $boygirl .= $total1.' Total ';
		 *
		 *
		 * $intag = $tagname .' | '.$boygirl.' are IN the facility';
		 *
		 *
		 * $outtags2 = $this->model_setting_tags->getrolecallby($outtags,
		 * $this->customer->getId());
		 *
		 * //var_dump($outtags2);
		 *
		 * $girl12 = 0;
		 * $boy12 = 0;
		 * $tagname211 = array();
		 * if($outtags2){
		 *
		 * foreach($outtags2 as $outtag){
		 * $tag_info = $this->model_setting_tags->getTag($outtag['tags_id']);
		 *
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 *
		 * $tagname2 .=
		 * $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
		 *
		 * //var_dump($tag_info['gender']);
		 *
		 *
		 * }
		 * //$tagname2 .= implode(", ",$tagname211);
		 *
		 *
		 * $total12 = 0;
		 * if($facilityinfo['config_tags_customlist_id'] !=NULL &&
		 * $facilityinfo['config_tags_customlist_id'] !=""){
		 * $d2 = array();
		 * $d2['customlistvalueids'] =
		 * $facilityinfo['config_tags_customlist_id'];
		 * $customlistvalues =
		 * $this->model_notes_notes->getcustomlistvalues($d2);
		 * if($customlistvalues){
		 *
		 * foreach($customlistvalues as $customlistvalue){
		 *
		 * $customlistvalues_total =
		 * $this->model_setting_tags->gettotalcustomlistvaluebyid($customlistvalue['customlistvalues_id'],
		 * $customlistvalue['gender'] ,'2', $this->customer->getId());
		 *
		 * if($customlistvalues_total > 0 ){
		 * $total12 = $total12 + $customlistvalues_total;
		 * $boygirl2 .= $customlistvalues_total .'
		 * '.$customlistvalue['customlistvalues_name'].' ';
		 *
		 * $boygirl2 .= 'and ';
		 * }
		 *
		 * }
		 * }
		 * }
		 *
		 * $boygirl2 .= $total12.' Total ';
		 *
		 *
		 * $outtag = ' | '. $tagname2.$boygirl2.' Clients are OUT of the
		 * facility';
		 * }
		 *
		 * $tag_content = $intag .' '. $outtag;
		 *
		 *
		 * $fdataa = array();
		 * $fdataa['is_monitor_time'] = '1';
		 * $fdataa['facilities_id'] = $this->customer->getId();
		 * $fdataa['date_added'] = date('Y-m-d', strtotime('now'));
		 *
		 * $signnotes_infos =
		 * $this->model_notes_notes->getNotebyactivenotes($fdataa);
		 *
		 * $sign_users = "";
		 * $sign_users1 = array();
		 * if($signnotes_infos != null && $signnotes_infos != ""){
		 * $sign_users .= " | STAFF ";
		 * foreach($signnotes_infos as $signnotes_info){
		 * $sign_users .= $signnotes_info['user_id'].',';
		 * }
		 *
		 * //$sign_users .= implode(", ",$sign_users1);
		 * }
		 *
		 * $data['notes_description'] = $keywordData2['keyword_name'].' | ' .
		 * $tag_content .$description1. $comments .$sign_users ;
		 *
		 * }else{
		 */
		
		// var_dump($this->session->data['tagsids']);
		// echo "<hr>";
		// var_dump($this->session->data['role_calls']);
		
		/*
		 * $afacilities = array();
		 * foreach($fdata['tagsids'] as $key1 => $tagsid){
		 * $tag_info = $this->model_setting_tags->getTag($key1);
		 * $afacilities[] = array(
		 * 'tags_id'=>$key1,
		 * 'role_call'=>$tagsid,
		 * 'facilities_id'=>$tag_info['facilities_id'],
		 * );
		 *
		 * }
		 *
		 * $role_calltagsids = $this->groupArray($afacilities, "facilities_id", false, true);
		 * $abc = array();
		 * $tagnamesss = "";
		 *
		 *
		 * $this->load->model ( 'facilities/facilities' );
		 * $facilities_info = $this->model_facilities_facilities->getfacilities ($facilities_id);
		 */
		/*
		 * if($facilities_info['no_distribution'] == '1'){
		 * foreach ($role_calltagsids as $rolecalls) {
		 *
		 * $tagname = "";
		 * $tagname2 = "";
		 * $tagnamesss_out = "";
		 * foreach($rolecalls as $rolecall){
		 * foreach ($fdata['role_calls'] as $key => $role) {
		 * if ($rolecall['tags_id'] == $key) {
		 *
		 * $abc[] = $key;
		 * $tag_info = $this->model_setting_tags->getTag($key);
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * //$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
		 *
		 * $this->load->model('setting/locations');
		 * $location_info = $this->model_setting_locations->getlocation($tag_info['room']);
		 *
		 * $tagname .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
		 *
		 * $tagnamesss = 1;
		 *
		 * $this->model_resident_resident->updatetagrolecall($key, '1');
		 * }
		 * }
		 *
		 * if (! in_array($rolecall['tags_id'], $abc)) {
		 * // var_dump($tags_id);
		 * $tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * //$tagname2 .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
		 *
		 * $this->load->model('setting/locations');
		 * $location_info = $this->model_setting_locations->getlocation($tag_info['room']);
		 *
		 * $tagname2 .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
		 *
		 * // var_dump($tag_info['role_call']);
		 * if ($rolecall['role_call'] == $tag_info['role_call']) {
		 * $tagnamesss_out = 1;
		 * } else {
		 * $tagnamesss_out = 2;
		 * }
		 *
		 * $this->model_resident_resident->updatetagrolecall($tags_id, '2');
		 * }
		 * }
		 *
		 *
		 * $this->load->model ( 'facilities/facilities' );
		 * $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		 * $unique_id = $facility ['customer_key'];
		 *
		 * $this->load->model ( 'customer/customer' );
		 * $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		 *
		 * if (! empty ( $customer_info ['setting_data'])) {
		 * $customers = unserialize($customer_info ['setting_data']);
		 *
		 * if($customers['in_name'] != null && $customers['in_name'] != ""){
		 * $in_name = $customers['in_name'].' ';
		 * }else{
		 * $in_name = ' returned to the Cell ';
		 * }
		 *
		 * if($customers['out_name'] != null && $customers['out_name'] != ""){
		 * $out_name = $customers['out_name'].' ';
		 * }else{
		 * $out_name = ' left the Cell ';
		 * }
		 *
		 * }else{
		 * $in_name = ' returned to the Cell ';
		 * $out_name = ' left the Cell ';
		 * }
		 *
		 * $inname = "";
		 * if ($tagnamesss == 1) {
		 * if ($tagname != null && $tagname != "") {
		 * $inname = $tagname . $in_name;
		 * }
		 * } else {
		 * if ($tagname != null && $tagname != "") {
		 * $inname = $tagname;
		 * }
		 * }
		 *
		 * $outname = "";
		 *
		 * if ($tagnamesss_out == 1) {
		 * if ($tagname2 != null && $tagname2 != "") {
		 * $outname = ' | ' . $tagname2 . $out_name;
		 * }
		 * } else {
		 * if ($tagname2 != null && $tagname2 != "") {
		 * $outname = $tagname2;
		 * }
		 * }
		 *
		 * if ($pdata['new_module']) {
		 * $description1 = "";
		 * $this->load->model('notes/notes');
		 *
		 * foreach ($pdata['new_module'] as $customlistvalues_id) {
		 *
		 * if($customlistvalues_id['checkin']=="1"){
		 *
		 * $description1 .= ' | ' . $customlistvalues_id['customlistvalues_name'];
		 *
		 * }
		 *
		 *
		 * }
		 *
		 * $data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
		 * }
		 *
		 * if ($pdata['comments'] != null && $pdata['comments']) {
		 * $comments = ' | ' . $pdata['comments'];
		 * }
		 *
		 *
		 * $data['notes_description'] = $inname . $outname . $description1 . $comments;
		 *
		 * $data['date_added'] = $date_added;
		 * $data['note_date'] = $date_added;
		 * $data['notetime'] = $notetime;
		 *
		 * $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
		 *
		 * }
		 *
		 * }
		 */
		/*
		 * foreach ($role_calltagsids as $facilities_id1 => $rolecalls)
		 * {
		 *
		 * $tagname = "";
		 * $tagname2 = "";
		 * $tagnamesss_out = "";
		 */
		/*
		 * foreach($rolecalls as $rolecall){
		 * foreach ($fdata['role_calls'] as $key => $role) {
		 * if ($rolecall['tags_id'] == $key) {
		 *
		 * $abc[] = $key;
		 * $tag_info = $this->model_setting_tags->getTag($key);
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * //$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
		 *
		 * $this->load->model('setting/locations');
		 * $location_info = $this->model_setting_locations->getlocation($tag_info['room']);
		 *
		 * $tagname .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
		 *
		 * $tagnamesss = 1;
		 *
		 * $this->model_resident_resident->updatetagrolecall($key, $role);
		 * }
		 * }
		 *
		 * if (! in_array($rolecall['tags_id'], $abc)) {
		 * // var_dump($tags_id);
		 * $tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * //$tagname2 .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
		 *
		 * $this->load->model('setting/locations');
		 * $location_info = $this->model_setting_locations->getlocation($tag_info['room']);
		 *
		 * $tagname2 .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
		 *
		 * // var_dump($tag_info['role_call']);
		 * if ($rolecall['role_call'] == $tag_info['role_call']) {
		 * $tagnamesss_out = 1;
		 * } else {
		 * $tagnamesss_out = 2;
		 * }
		 *
		 * $this->model_resident_resident->updatetagrolecall($tags_id, $role);
		 * }
		 * }
		 */
		
		/*
		 * if($rolecall['role_call'] == '1'){
		 * if($rolecall['role_call'] == $tag_info['role_call']){
		 * $tagnamesss = 1;
		 * }else{
		 * $tagnamesss = 2;
		 * }
		 *
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * $tagname .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].' | ';
		 * $this->model_resident_resident->updatetagrolecall($rolecall['tags_id'], $rolecall['role_call']);
		 * }
		 *
		 * if($rolecall['role_call'] == '2'){
		 *
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * $tagname2 .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].' | ';
		 *
		 * if($rolecall['role_call'] == $tag_info['role_call']){
		 * $tagnamesss_out = 1;
		 * }else{
		 * $tagnamesss_out = 2;
		 * }
		 *
		 * $this->model_resident_resident->updatetagrolecall($rolecall['tags_id'], $rolecall['role_call']);
		 * }
		 */
		
		/*
		 * foreach ($fdata['tagsids'] as $key1 => $tagsid) {
		 *
		 * foreach ($fdata['role_calls'] as $key => $rolecall) {
		 * if ($key1 == $key) {
		 *
		 * $abc[] = $key;
		 * $tag_info = $this->model_setting_tags->getTag($key);
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * $tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
		 *
		 * // var_dump($tag_info['role_call']);
		 * if ($tagsid == $tag_info['role_call']) {
		 * $tagnamesss = 1;
		 * } else {
		 * $tagnamesss = 2;
		 * }
		 *
		 * $this->model_resident_resident->updatetagrolecall($key, '1');
		 * }
		 * }
		 *
		 * if (! in_array($key1, $abc)) {
		 * // var_dump($tags_id);
		 * $tag_info = $this->model_setting_tags->getTag($key1);
		 * $emp_tag_id = $tag_info['emp_tag_id'];
		 * $tags_id = $tag_info['tags_id'];
		 * $tagname2 .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
		 *
		 * // var_dump($tag_info['role_call']);
		 * if ($tagsid == $tag_info['role_call']) {
		 * $tagnamesss_out = 1;
		 * } else {
		 * $tagnamesss_out = 2;
		 * }
		 *
		 * $this->model_resident_resident->updatetagrolecall($tags_id, '2');
		 * }
		 * }
		 */
		
		$facility_outs = preg_split ( "/\,/", $fdata ['tags_ids'] );
		
		foreach ( $facility_outs as $facility_inout ) {
			
			$tag_info = $this->model_setting_tags->getTag ( $facility_inout );
			
			/*
			 * $this->load->model ( 'facilities/facilities' );
			 * $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id1 );
			 * $unique_id = $facility ['customer_key'];
			 *
			 * $this->load->model ( 'customer/customer' );
			 * $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			 *
			 * if (! empty ( $customer_info ['setting_data'])) {
			 * $customers = unserialize($customer_info ['setting_data']);
			 *
			 * if($customers['in_name'] != null && $customers['in_name'] != ""){
			 * $in_name = $customers['in_name'].' ';
			 * }else{
			 * $in_name = ' returned to the Cell ';
			 * }
			 *
			 * if($customers['out_name'] != null && $customers['out_name'] != ""){
			 * $out_name = $customers['out_name'].' ';
			 * }else{
			 * $out_name = ' left the Cell ';
			 * }
			 *
			 * }else{
			 * $in_name = ' returned to the Cell ';
			 * $out_name = ' left the Cell ';
			 * }
			 *
			 * $inname = "";
			 * if ($tagnamesss == 1) {
			 * if ($tagname != null && $tagname != "") {
			 * $inname = $tagname . $in_name;
			 * }
			 * } else {
			 * if ($tagname != null && $tagname != "") {
			 * $inname = $tagname;
			 * }
			 * }
			 *
			 * $outname = "";
			 *
			 * if ($tagnamesss_out == 1) {
			 * if ($tagname2 != null && $tagname2 != "") {
			 * $outname = ' | ' . $tagname2 . $out_name;
			 * }
			 * } else {
			 * if ($tagname2 != null && $tagname2 != "") {
			 * $outname = $tagname2;
			 * }
			 * }
			 */
			
			$ids = array ();
			if ($pdata ['new_module']) {
				$description1 = "";
				$this->load->model ( 'notes/notes' );
				
				foreach ( $pdata ['new_module'] as $customlistvalues_id ) {
					
					if ($customlistvalues_id ['checkin'] == "1") {
						
						$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
						$ids [] = $customlistvalues_id ['customlistvalues_id'];
					}
					
					// //$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
					
					// $customlistvalues_name = $custom_info['customlistvalues_name'];
				}
				
				// $data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
				$data ['customlistvalues_ids'] = $ids;
			}
			
			/*
			 * if ($pdata['customlistvalues_ids']) {
			 *
			 * $this->load->model('notes/notes');
			 *
			 * foreach ($pdata['customlistvalues_ids'] as $customlistvalues_id) {
			 *
			 * $custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
			 *
			 * $customlistvalues_name = $custom_info['customlistvalues_name'];
			 *
			 * $description1 .= ' | ' . $customlistvalues_name;
			 * }
			 *
			 * $data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
			 * }
			 */
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			if (! empty ( $customer_info ['setting_data'] )) {
				$customers = unserialize ( $customer_info ['setting_data'] );
				
				if ($fdata ['facility_inout'] == '0') {
					if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
						$roleCall = $customers ['in_name'];
					} else {
						$roleCall = ' returned to the Cell ';
					}
				}
				
				if ($fdata ['facility_inout'] == '1') {
					if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
						$roleCall = $customers ['out_name'];
					} else {
						$roleCall = ' left the Cell ';
					}
				}
			} else {
				if ($fdata ['facility_inout'] == '0') {
					$roleCall = ' returned to the Cell ';
				}
				
				if ($fdata ['facility_inout'] == '1') {
					$roleCall = ' left the Cell ';
				}
			}
			
			/*
			 * if ($fdata['role_call'] == '1') {
			 * $roleCall = "returned to ";
			 * }
			 *
			 * if ($fdata['role_call'] == '2') {
			 * $roleCall = "left ";f
			 * }
			 */
			
			if ($fdata ['discharge'] == "1") {
				$roleCall = "Discharged to";
			}
			
			if ($pdata ['comments'] != null && $pdata ['comments']) {
				$comments = ' | ' . $pdata ['comments'];
			}
			
			$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $tag_info ['role_call'] );
			
			// $data['notes_description'] = $inname . $outname . $description1 . $comments;
			
			if ($pdata ['escort_user_ids'] != null && $pdata ['escort_user_ids'] != "") {
				$user_names = rtrim ( implode ( ',', $pdata ['escort_user_ids'] ), ' , ' );
				$escorted = ' | escorted by ' . $user_names;
			}
			
			$this->load->model ( 'api/permision' );
			$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
			$cname = $clientinfo ['name'];
			
			// $user_names = rtrim(implode(',', $pdata['escort_user_ids']), ' , ');
			
			/* $data['notes_description'] = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' of '.$facility['facility'].' '.$facility_message.' ' . $form_name . ' ' . $roleCall . $description1 . $comments; */
			
			/*
			 * if($fdata['facility_inout']=='0'){
			 *
			 * $facility_text='has return to';
			 *
			 * }else{
			 *
			 * $facility_text='has left';
			 * }
			 */
			
			$data ['notes_description'] = $cname . ' status changed to | ' . $roleCall . $escorted . ' | ' . $description1 . $comments;
			
			$data ['date_added'] = $date_added;
			$data ['note_date'] = $date_added;
			$data ['notetime'] = $notetime;
			
			$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
			
			if ($tag_info ['facilities_id'] != $facilities_id) {
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $tag_info ['facilities_id'] );
			}
			
			$cdata2 = array ();
			$cdata2 ['modify_date'] = $date_added;
			$cdata2 ['notes_id'] = $notes_id;
			$cdata2 ['tags_id'] = $tag_info ['tags_id'];
			$cdata2 ['facility_inout'] = $fdata ['facility_inout'];
			
			$this->model_resident_resident->updatetagrolecall2 ( $tag_info ['tags_id'], $cdata2 );
		}
		
		return $notes_id;
	}
	public function allclientstatussigns($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		if($fdata ['keyword_id']!=null && $fdata['keyword_id']!=""){
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $fdata ['keyword_id'] );
					
			$data ['keyword_file'] = $keywordData2 ['keyword_image'];	
			
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
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
			
			if ($fdata ['in_out_input'] == '0') {
				if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
					$roleCall = $customers ['in_name'];
				} else {
					$roleCall = ' returned to the Cell ';
				}
			}
			
			if ($fdata ['in_out_input'] == '1') {
				if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
					$roleCall = $customers ['out_name'];
				} else {
					$roleCall = ' left the Cell ';
				}
			}
		} else {
			if ($fdata ['in_out_input'] == '0') {
				$roleCall = ' returned to the Cell ';
			}
			
			if ($fdata ['in_out_input'] == '1') {
				$roleCall = ' left the Cell ';
			}
		}
		
		/*
		 * if ($fdata['role_call'] == '1') {
		 * $roleCall = "returned to ";
		 * }
		 *
		 * if ($fdata['role_call'] == '2') {
		 * $roleCall = "left ";
		 * }
		 */
		
		if ($fdata ['discharge'] == "1") {
			$roleCall = "Discharged to";
		}
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		$afacilities = array ();
		
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$facilities_id3 = $tag_info ['facilities_id'];
		
		$afacilities [] = array (
				'tags_id' => $fdata ['tags_id'],
				'facilities_id' => $tag_info ['facilities_id'] 
		);
		
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $fdata ['tag_status_id'] );
		
		$rule_action_content = unserialize ( $client_statuses_value ['rule_action_content'] );
		
		if ($rule_action_content ['custom_description'] != null && $rule_action_content ['custom_description'] != "") {
			$comments .= ' | ' . nl2br ( $rule_action_content ['custom_description'] );
		}
		
		$facility_type = 0;
		$facilitym = "";
		if ($client_statuses_value ['status_type'] == "1") {
			if ($client_statuses_value ['is_facility'] == "1") {
				if ($client_statuses_value ['facility_type'] != null && $client_statuses_value ['facility_type'] != "") {
					$facility_type = $client_statuses_value ['facility_type'];
					
					if ($facility_type > 0) {
						$mfacility = $this->model_facilities_facilities->getfacilities ( $facility_type );
						$facilitym = $mfacility ['facility'];
					} else {
						$facilitym = "";
					}
				}
			}
		}
		
		if ($fdata ['tag_status_id'] != $tag_info ['role_call']) {
			
			$this->load->model ( 'notes/clientstatus' );
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag_info ['role_call'] );
			$roleCall = $clientstatus_info ['name'];
			
			$caltime = " | ";
			
			// echo '<pre>'; print_r($clientstatus_info); echo '</pre>';
			$status_total_time = 0;
			if ($clientstatus_info ['track_time'] == 1) {
				$this->load->model ( 'notes/notes' );
				$notes_data = $this->model_notes_notes->getnotes ( $tag_info ['notes_id'] );
				// echo '<pre>'; print_r($notes_data); echo '</pre>';
				$current_date = date ( 'Y-m-d H:i:s' );
				$start_date = new DateTime ( $notes_data ['date_added'] );
				$since_start = $start_date->diff ( new DateTime ( $current_date ) );
				
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
				
				$caltime .= ' | ';
			}
			
			$this->load->model ( 'api/permision' );
			$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id3, $tag_info );
			$cname = $clientinfo ['name'];
			
			$taskcontent = $cname;
			
			$status_name = array ();
			$multivalue = array ();
			$statusname1 = array ();
			$statusname = "";
			$substatus_ids_arr = explode ( ',', $fdata ['substatus_ids'] );
			
			
			foreach ( $this->session->data ['statusmulti'] as $key => $val ) {
				// var_dump($key);
				if($val['chkvalue'] == 1){
					if($val['multivalue'] != null && $val['multivalue'] != ""){
						$status_name [] = $val['name'].' - '.$val['multivalue'];
					}else{
						$status_name [] = $val['name'];
					}
					$multivalue [] = $val['multivalue'];
					$statusname1 [] = $val['name'];
			
				}
			}
			
			foreach ( $substatus_ids_arr as $val ) {
				// $sdata = $this->model_setting_tags->getTagStatus($val);
				//$status_name [] = $val;
			}
			
			if (! empty ( $status_name )) {
				$statusname = ' | ' . implode ( ' | ', $status_name );
				$multivalue1 = ' | ' . implode ( ' | ', $multivalue );
				
				
				$substatus_ids = implode ( ',', $statusname1 );
				$substatus_idscomment = implode ( ',', $multivalue );
			}
			
			if ($pdata ['escort_user_ids'] != null && $pdata ['escort_user_ids'] != "") {
				$user_names = rtrim ( implode ( ',', $pdata ['escort_user_ids'] ), ' , ' );
				$escorted = ' | escorted by ' . $user_names;
			}
			
			if ($client_statuses_value ['type'] == '4') {
				
				if ($this->session->data ['movement_room'] != null && $this->session->data ['movement_room'] != "") {
					
					$this->load->model ( 'setting/locations' );
					
					$roominfo = $this->model_setting_locations->getlocation ( $this->session->data ['movement_room'] );
				} else {
					$this->load->model ( 'setting/locations' );
					$roominfo = $this->model_setting_locations->getlocation ( $tag_info ['movement_room'] );
				}
				
				if ($this->session->data ['mfacilities_id'] != null && $this->session->data ['mfacilities_id'] != "") {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['mfacilities_id'] );
					
					$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
				} else {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facility_move_id'] );
					
					$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
				}
				
				$data ['notes_description'] = $client_statuses_value ['name'] . " completed | " . $taskcontent . ' ' . $fname . ' | ' . $roominfo ['location_name'] . ' ' . $caltime . $escorted . $description1 . $comments . $statusname;
			} else {
				$data ['notes_description'] = $taskcontent . ' status changed ' . $roleCall . ' to | ' . $client_statuses_value ['name'] . ' ' . $caltime . $escorted . $description1 . $comments . $statusname;
			}
			
			
			if($rule_action_content['exclude_in_inmate_status'] != null && $rule_action_content['exclude_in_inmate_status'] != ""){
				$tag_status_id = $rule_action_content['exclude_in_inmate_status'];
					
				$fixed_status_id = $fdata ['tag_status_id'];
			}else{
				$tag_status_id = $fdata ['tag_status_id'];
					
				$fixed_status_id = 0;
			}
			
			if ($client_statuses_value ['type'] == '2') {
				$data ['status_total_time'] = $status_total_time;
			}else{
				$data ['status_total_time'] = 0;
			}
			$data ['date_added'] = $date_added;
			$data ['note_date'] = $date_added;
			$data ['notetime'] = $notetime;
			//$data ['tag_status_id'] = $fdata ['tag_status_id'];
			$data ['tag_status_id'] = $tag_status_id;
			$data ['substatus_ids'] = $substatus_ids;
			$data ['substatus_idscomment'] = $substatus_idscomment;
			$data ['fixed_status_id'] = $fixed_status_id;
			
			$data ['move_notes_id'] = $tag_info ['notes_id'];
			$data ['substatus_ids'] = $fdata ['substatus_ids'];
			
			$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
			$data ['tags_id'] = $tag_info ['tags_id'];
			
			if ($this->session->data ['movement_room'] != null && $this->session->data ['movement_room'] != "") {
				$movement_room1 = $this->session->data ['movement_room'];
			} else {
				if ($tag_info ['movement_room'] != 0) {
					$movement_room1 = $tag_info ['movement_room'];
				}
			}
			if ($movement_room1 != $tag_info ['movement_room']) {
				$data ['manual_movement'] = 1;
			}
			
			$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id3 );
			
			
			if($tag_info ['notes_id'] > 0){
				$cdatam = array ();
				$cdatam ['notes_id'] = $tag_info ['notes_id'];
				$cdatam ['move_notes_id'] = $notes_id;
				$cdatam ['tags_id'] = $fdata ['tags_id'];
				
				//if ($client_statuses_value ['type'] == '2') {
					$cdatam ['status_total_time'] = $status_total_time;
				//}else{
				//	$cdatam ['status_total_time'] = 0;
				//}
				
				
				$this->model_resident_resident->updateclientStatusnotes ( $cdatam );
			}
			
			if ($client_statuses_value ['type'] == '4') {
				$movement_room = "";
				$facility_move_id = "";
				
				if ($this->session->data ['movement_room'] != null && $this->session->data ['movement_room'] != "") {
					$movement_room = $this->session->data ['movement_room'];
				} else {
					if ($tag_info ['movement_room'] != 0) {
						$movement_room = $tag_info ['movement_room'];
					}
				}
				if ($this->session->data ['mfacilities_id'] != null && $this->session->data ['mfacilities_id'] != "") {
					$facility_move_id = $this->session->data ['mfacilities_id'];
				} else {
					if ($tag_info ['facility_move_id'] != 0) {
						$facility_move_id = $tag_info ['facility_move_id'];
					}
				}
				// $movement_room = $tag_info ['movement_room'];
				// $facility_move_id = $tag_info ['facility_move_id'];
				
				$scdata = array ();
				$scdata ['tags_id'] = $tag_info ['tags_id'];
				$scdata ['facilities_id'] = $facilities_id3;
				$scdata ['modify_date'] = $noteDate;
				$scdata ['notes_id'] = $notes_id;
				$scdata ['facility_move_id'] = $facility_move_id;
				$scdata ['movement_room'] = $movement_room;
				$this->model_resident_resident->updateclientmovement ( $scdata );
			}
			
			$cdata = array ();
			$cdata ['tag_status_id'] = $tag_status_id;
			$cdata ['fixed_status_id'] = $fixed_status_id;
			$cdata ['substatus_ids'] = $substatus_ids;
			$cdata ['comments'] = $substatus_idscomment;
			$cdata ['tags_id'] = $fdata ['tags_id'];
			$cdata ['facilities_id'] = $tag_info ['facilities_id'];
			$cdata ['modify_date'] = $noteDate;
			$cdata ['facility_move_id'] = $facility_type;
			
			$this->model_resident_resident->updateclientStatus ( $cdata );
			
			$cdata = array ();
			$cdata ['tag_status_id'] = $tag_status_id;
			$cdata ['tags_id'] = $fdata ['tags_id'];
			$cdata ['facilities_id'] = $facilities_id3;
			$cdata ['modify_date'] = $noteDate;
			$cdata ['notes_id'] = $notes_id;
			$cdata ['facility_move_id'] = $facility_type;
			
			$this->model_resident_resident->updateclientnotes ( $cdata );
			
			
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id3 );
			
			if ($facility ['enable_facilityinout'] == '1') {
				if ($facility_type > 0) {
					$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout='1',modify_date='" . $date_added . "'  where  tags_id = '" . ( int ) $fdata ['tags_id'] . "'";
					$this->db->query ( $sql );
				} else {
					$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout='0',modify_date='" . $date_added . "'  where  tags_id = '" . ( int ) $fdata ['tags_id'] . "'";
					$this->db->query ( $sql );
				}
			} else {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout='0',modify_date='" . $date_added . "'  where  tags_id = '" . ( int ) $fdata ['tags_id'] . "'";
				$this->db->query ( $sql );
			}
			
			
			if($tag_info ['notes_id'] > 0){
				$move_notes_id = $tag_info ['notes_id'];
			}else{
				$move_notes_id = $notes_id;
			}
			
			//if ($clientstatus_info ['track_time'] == 1) {
				$tmdata = array ();
				$tmdata ['notes_id'] = $notes_id;
				$tmdata ['facilities_id'] = $tag_info ['facilities_id'];
				$tmdata ['unique_id'] = $facilities_info ['customer_key'];
				$tmdata ['tags_id'] = $tag_info ['tags_id'];
				$tmdata ['tag_status_id'] = $clientstatus_info ['tag_status_id'];
				$tmdata ['new_tag_status_id'] = $tag_status_id;
				$tmdata ['fixed_status_id'] = $fixed_status_id;
				$tmdata ['comments'] = $substatus_idscomment;
				$tmdata ['move_notes_id'] = $move_notes_id;
				
				$tmdata ['keyword_id'] = '';
				$tmdata ['types'] = 1;
				
				$tmdata ['years'] = $since_start->y;
				$tmdata ['months'] = $since_start->m;
				$tmdata ['days'] = $since_start->d;
				$tmdata ['hours'] = $since_start->h;
				$tmdata ['minutes'] = $since_start->i;
				
				$tmdata ['date_added'] = date ( 'Y-m-d H:i:s' );
				$this->addtracktime ( $tmdata );
			//}
		}
		
		// }
		
		return $notes_id;
	}
	public function activenote($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		$this->load->model ( 'setting/locations' );
		$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
		
		// $tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$tagname = $clientinfo ['name'];
		
		$this->load->model ( 'setting/keywords' );
		$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $fdata ['keyword_id'] );
		
		$data ['keyword_file'] = $keywordData2 ['keyword_image'];
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		// $data['notes_description'] = $keywordData2['keyword_name'] . ' | ' . $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . '' . $comments;
		$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $tagname . '' . $comments;
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		return $notes_id;
	}
	public function rolecallsign($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		if ($fdata ['discharge'] == "1") {
			$data ['keyword_file'] = DISCHARGE_ICON;
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
			
			if ($fdata ['in_out_input'] == '0') {
				if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
					$roleCall = $customers ['in_name'];
				} else {
					$roleCall = ' returned to the Cell ';
				}
			}
			
			if ($fdata ['in_out_input'] == '1') {
				if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
					$roleCall = $customers ['out_name'];
				} else {
					$roleCall = ' left the Cell ';
				}
			}
		} else {
			if ($fdata ['in_out_input'] == '0') {
				$roleCall = ' returned to the Cell ';
			}
			
			if ($fdata ['in_out_input'] == '1') {
				$roleCall = ' left the Cell ';
			}
		}
		
		/*
		 * if ($fdata['role_call'] == '1') {
		 * $roleCall = "returned to ";
		 * }
		 *
		 * if ($fdata['role_call'] == '2') {
		 * $roleCall = "left ";
		 * }
		 */
		
		if ($fdata ['discharge'] == "1") {
			$roleCall = "Discharged to";
		}
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		$ids = array ();
		if ($pdata ['new_module']) {
			
			$this->load->model ( 'notes/notes' );
			
			foreach ( $pdata ['new_module'] as $customlistvalues_id ) {
				
				if ($customlistvalues_id ['checkin'] == "1") {
					
					$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
					$ids [] = $customlistvalues_id ['customlistvalues_id'];
				}
				
				// //$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
				
				// $customlistvalues_name = $custom_info['customlistvalues_name'];
			}
			
			$data ['customlistvalues_ids'] = $ids;
		}
		
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $tag_info ['role_call'] );
		
		if ($pdata ['escort_user_ids'] != null && $pdata ['escort_user_ids'] != "") {
			$user_names = rtrim ( implode ( ',', $pdata ['escort_user_ids'] ), ' , ' );
			$escorted = ' | escorted by ' . $user_names;
		}
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $tag_info ['facilities_id'], $tag_info );
		$cname = $clientinfo ['name'];
		
		if ($fdata ['discharge'] == "1") {
			
			$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $cname . '' . $description1 . $comments;
			
			$this->load->model ( 'createtask/createtask' );
			$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $fdata ['tags_id'] );
			
			if ($alldatas != NULL && $alldatas != "") {
				foreach ( $alldatas as $alldata ) {
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
					$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
					$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
				}
			}
		} else {
			
			$data ['notes_description'] = $cname . ' | status changed to | ' . $roleCall . $escorted . $description1 . $comments;
		}
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $tag_info ['role_call'] );
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		if ($facilities_id != $tag_info ['facilities_id']) {
			$notes_id1 = $this->model_notes_notes->jsonaddnotes ( $data, $tag_info ['facilities_id'] );
		}
		
		if ($fdata ['discharge'] == "1") {
			$this->load->model ( 'setting/tags' );
			$this->model_setting_tags->addcurrentTagarchive ( $fdata ['tags_id'] );
			$this->model_setting_tags->updatecurrentTagarchive ( $fdata ['tags_id'], $notes_id );
			
			$this->model_resident_resident->updateDischargeTag ( $fdata ['tags_id'], $date_added );
		} else {
			
			$cdata2 = array ();
			$cdata2 ['modify_date'] = $date_added;
			$cdata2 ['notes_id'] = $notes_id;
			$cdata2 ['tags_id'] = $fdata ['tags_id'];
			$cdata2 ['facility_inout'] = $fdata ['in_out_input'];
			
			$this->model_resident_resident->updatetagrolecall2 ( $fdata ['tags_id'], $cdata2 );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		return $notes_id;
	}
	public function updateclientstatussign($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		if ($fdata ['discharge'] == "1") {
			$data ['keyword_file'] = DISCHARGE_ICON;
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
			
			if ($fdata ['role_call'] == '1') {
				if ($customers ['in_name'] != null && $customers ['in_name'] != "") {
					$roleCall = $customers ['in_name'];
				} else {
					$roleCall = ' returned to the Cell ';
				}
			}
			
			if ($fdata ['role_call'] == '2') {
				if ($customers ['out_name'] != null && $customers ['out_name'] != "") {
					$roleCall = $customers ['out_name'];
				} else {
					$roleCall = ' left the Cell ';
				}
			}
		} else {
			if ($fdata ['role_call'] == '1') {
				$roleCall = ' returned to the Cell ';
			}
			
			if ($fdata ['role_call'] == '2') {
				$roleCall = ' left the Cell ';
			}
		}
		
		if ($fdata ['role_call'] == '1') {
			$roleCall = "returned to ";
		}
		
		if ($fdata ['role_call'] == '2') {
			$roleCall = "left ";
		}
		
		if ($fdata ['discharge'] == "1") {
			$roleCall = "Discharged to";
		}
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		$ids = array ();
		if ($pdata ['new_module']) {
			
			$this->load->model ( 'notes/notes' );
			
			foreach ( $pdata ['new_module'] as $customlistvalues_id ) {
				
				if ($customlistvalues_id ['checkin'] == "1") {
					
					$description1 .= ' | ' . $customlistvalues_id ['customlistvalues_name'];
					$ids [] = $customlistvalues_id ['customlistvalues_id'];
				}
				
				// //$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
				
				// $customlistvalues_name = $custom_info['customlistvalues_name'];
			}
			
			// $data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
			$data ['customlistvalues_ids'] = $ids;
		}
		
		/*
		 * if ($fdata['discharge'] == "1") {
		 *
		 * $data['notes_description'] = $keywordData2['keyword_name'] . ' | ' . $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . '' . $description1 . $comments;
		 *
		 * $this->load->model('createtask/createtask');
		 * $alldatas = $this->model_createtask_createtask->getalltaskbyid($fdata['tags_id']);
		 *
		 * if ($alldatas != NULL && $alldatas != "") {
		 * foreach ($alldatas as $alldata) {
		 * $result = $this->model_createtask_createtask->getStrikedatadetails($alldata['id']);
		 * $taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists($result, $facilities_id, '1');
		 * $this->model_createtask_createtask->updatetaskStrike($alldata['id']);
		 * $this->model_createtask_createtask->deteteIncomTask($facilities_id);
		 * }
		 * }
		 * } else {
		 * $data['notes_description'] = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ' . $form_name . ' has changed status to ' . $fdata['name'] . $description1 . $comments;
		 * }
		 */
		
		$this->load->model ( 'notes/clientstatus' );
		$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag_info ['role_call'] );
		$roleCall = $clientstatus_info ['name'];
		
		$caltime = " | ";
		$status_total_time = 0;
		// echo '<pre>'; print_r($clientstatus_info); echo '</pre>';
		
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		$facilities_id3 = $tag_info ['facilities_id'];
		
		if ($clientstatus_info ['track_time'] == 1) {
			$this->load->model ( 'notes/notes' );
			$notes_data = $this->model_notes_notes->getnotes ( $tag_info ['notes_id'] );
			// echo '<pre>'; print_r($notes_data); echo '</pre>';
			$current_date = date ( 'Y-m-d H:i:s' );
			$start_date = new DateTime ( $notes_data ['date_added'] );
			$since_start = $start_date->diff ( new DateTime ( $current_date ) );
			
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
			
			$caltime .= ' ';
		}
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id3, $tag_info );
		$cname = $clientinfo ['name'];
		
		$taskcontent = $cname;
		
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $fdata ['tag_status_id'] );
		
		
		
		$rule_action_content = unserialize ( $client_statuses_value ['rule_action_content'] );
		
		
		if ($rule_action_content ['custom_description'] != null && $rule_action_content ['custom_description'] != "") {
			$comments .= ' | ' . nl2br ( $rule_action_content ['custom_description'] );
		}
		
		$facility_type = 0;
		$facilitym = "";
		if ($client_statuses_value ['status_type'] == "1") {
			if ($client_statuses_value ['is_facility'] == "1") {
				if ($client_statuses_value ['facility_type'] != null && $client_statuses_value ['facility_type'] != "") {
					$facility_type = $client_statuses_value ['facility_type'];
					
					if ($facility_type > 0) {
						$mfacility = $this->model_facilities_facilities->getfacilities ( $facility_type );
						$facilitym = $mfacility ['facility'];
					} else {
						$facilitym = "";
					}
				}
			}
		}
		
		$this->load->model ( 'notes/notes' );
		$notes_data = $this->model_notes_notes->getnotes ( $tag_info ['notes_id'] );
		
		if ($pdata ['escort_user_ids'] != null && $pdata ['escort_user_ids'] != "") {
			$user_names = rtrim ( implode ( ',', $pdata ['escort_user_ids'] ), ' , ' );
			$escorted = ' | escorted by ' . $user_names;
		}
		
		
		
		$status_name = array ();
		$multivalue = array ();
		$statusname1 = array ();
		$statusname = "";
		$substatus_ids_arr = explode ( ',', $fdata ['substatus_ids'] );
		
		
		foreach ( $this->session->data ['statusmulti'] as $key => $val ) {
			// var_dump($key);
			if($val['chkvalue'] == 1){
				if($val['multivalue'] != null && $val['multivalue'] != ""){
					$status_name [] = $val['name'].' - '.$val['multivalue'];
				}else{
					$status_name [] = $val['name'];
				}
				$multivalue [] = $val['multivalue'];
				$statusname1 [] = $val['name'];
				
			}
		}
		
		
		foreach ( $substatus_ids_arr as $val ) {
			//$sdata = $this->model_setting_tags->getTagStatus($val);
			//$status_name [] = $val;
		}
		
		if (! empty ( $status_name )) {
			$statusname = ' | ' . implode ( ' | ', $status_name );
			$multivalue1 = ' | ' . implode ( ' | ', $multivalue );
			
			
			$substatus_ids = implode ( ',', $statusname1 );
			$substatus_idscomment = implode ( ',', $multivalue );
		}
		
		
		//var_dump($multivalue);
		
		if ($client_statuses_value ['type'] == '4') {
			
			if ($this->session->data ['movement_room'] != null && $this->session->data ['movement_room'] != "") {
				
				$this->load->model ( 'setting/locations' );
				
				$roominfo = $this->model_setting_locations->getlocation ( $this->session->data ['movement_room'] );
			} else {
				$this->load->model ( 'setting/locations' );
				
				$roominfo = $this->model_setting_locations->getlocation ( $tag_info ['movement_room'] );
			}
			
			if ($this->session->data ['mfacilities_id'] != null && $this->session->data ['mfacilities_id'] != "") {
				$this->load->model ( 'facilities/facilities' );
				
				$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['mfacilities_id'] );
				
				$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
			} else {
				$this->load->model ( 'facilities/facilities' );
				
				$facilities_info1 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $tag_info ['facility_move_id'] );
				
				$fname = ' | ' . $facilities_info1 ['facility'] . ' to ' . $facilities_info2 ['facility'];
			}
			
			$data ['notes_description'] = $client_statuses_value ['name'] . " completed | " . $taskcontent . ' ' . $fname . ' | ' . $roominfo ['location_name'] . ' ' . $caltime . $escorted . $description1 . $comments . $statusname;
		} else {
			$data ['notes_description'] = $taskcontent . ' status changed ' . $roleCall . ' to | ' . $client_statuses_value ['name'] . ' ' . $caltime . $escorted . $description1 . $comments . $statusname;
		}
		
		/* $data['notes_description'] = $taskcontent . ' status changed | ' . $fdata['name'] .$escorted .' | '.$caltime. ' ' .$description1 . $comments; */
		/*
		 * $user_names = rtrim(implode(',', $user_ids), ' , '); s
		 *
		 * $data['notes_description'] = $taskcontent . ' '.$caltime. ' Status changed to | ' . $fdata['name'] . ' | escorted by '.$user_names .' | '.$description1 . $comments;
		 */
		
		
		if($rule_action_content['exclude_in_inmate_status'] != null && $rule_action_content['exclude_in_inmate_status'] != ""){
			$tag_status_id = $rule_action_content['exclude_in_inmate_status'];
			
			$fixed_status_id = $fdata ['tag_status_id'];
		}else{
			$tag_status_id = $fdata ['tag_status_id'];
			
			$fixed_status_id = 0;
		}
		
		if ($client_statuses_value ['type'] == '2') {
			$data ['status_total_time'] = $status_total_time;
		}else{
			$data ['status_total_time'] = 0;
		}
		
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		$data ['tag_status_id'] = $tag_status_id;
		//$data ['substatus_ids'] = $fdata ['substatus_ids'];
		$data ['substatus_ids'] = $substatus_ids;
		$data ['substatus_idscomment'] = $substatus_idscomment;
		$data ['fixed_status_id'] = $fixed_status_id;
		$data ['move_notes_id'] = $tag_info ['notes_id'];
		
		if ($this->session->data ['movement_room'] != null && $this->session->data ['movement_room'] != "") {
			$movement_room1 = $this->session->data ['movement_room'];
		} else {
			if ($tag_info ['movement_room'] != 0) {
				$movement_room1 = $tag_info ['movement_room'];
			}
		}
		if ($movement_room1 != $tag_info ['movement_room']) {
			$data ['manual_movement'] = 1;
		}
		
		
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id3 );
		
		if($tag_info ['notes_id'] > 0){
			$cdatam = array ();
			$cdatam ['notes_id'] = $tag_info ['notes_id'];
			$cdatam ['move_notes_id'] = $notes_id;
			$cdatam ['tags_id'] = $fdata ['tags_id'];
			//if ($client_statuses_value ['type'] == '2') {
				$cdatam ['status_total_time'] = $status_total_time;
			//}else{
			//	$cdatam ['status_total_time'] = 0;
			//}
			
			$this->model_resident_resident->updateclientStatusnotes ( $cdatam );
		}
		
		if ($client_statuses_value ['type'] == '4') {
			$movement_room = "";
			$facility_move_id = "";
			if ($this->session->data ['movement_room'] != null && $this->session->data ['movement_room'] != "") {
				
				$movement_room = $this->session->data ['movement_room'];
			} else {
				// $movement_room = $tag_info ['movement_room'];
				
				if ($tag_info ['movement_room'] != 0) {
					$movement_room = $tag_info ['movement_room'];
				}
			}
			
			if ($this->session->data ['mfacilities_id'] != null && $this->session->data ['mfacilities_id'] != "") {
				
				$facility_move_id = $this->session->data ['mfacilities_id'];
			} else {
				// $facility_move_id = $tag_info ['facility_move_id'];
				
				if ($tag_info ['facility_move_id'] != 0) {
					$facility_move_id = $tag_info ['facility_move_id'];
				}
			}
			
			$scdata = array ();
			$scdata ['tags_id'] = $tag_info ['tags_id'];
			$scdata ['facilities_id'] = $facilities_id3;
			$scdata ['modify_date'] = $noteDate;
			$scdata ['notes_id'] = $notes_id;
			$scdata ['facility_move_id'] = $facility_move_id;
			$scdata ['movement_room'] = $movement_room;
			$this->model_resident_resident->updateclientmovement ( $scdata );
		}
		
		unset ( $this->session->data ['movement_room'] );
		unset ( $this->session->data ['mfacilities_id'] );
		
		
		
		$cdata = array ();
		$cdata ['tag_status_id'] = $tag_status_id;
		$cdata ['fixed_status_id'] = $fixed_status_id;
		$cdata ['substatus_ids'] = $substatus_ids;
		$cdata ['comments'] = $substatus_idscomment;
		$cdata ['tags_id'] = $fdata ['tags_id'];
		$cdata ['facilities_id'] = $facilities_id3;
		$cdata ['modify_date'] = $noteDate;
		$cdata ['facility_move_id'] = $facility_type;
		$this->model_resident_resident->updateclientStatus ( $cdata );
		
		unset ( $this->session->data ['statusmulti'] );
		
		$cdata = array ();
		$cdata ['tag_status_id'] = $tag_status_id;
		$cdata ['tags_id'] = $fdata ['tags_id'];
		$cdata ['facilities_id'] = $facilities_id3;
		$cdata ['modify_date'] = $noteDate;
		$cdata ['notes_id'] = $notes_id;
		$cdata ['facility_move_id'] = $facility_type;
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $fdata ['tag_status_id'] );
		
		if ($facilities_info ['enable_facilityinout'] == '1') {
			
			if (($facilities_id == $tag_info ['facilities_id']) && ($client_statuses_value ['status_type'] == '1' && $client_statuses_value ['is_facility'] == '1')) {
				
				$facility_inout = '1';
			} else {
				
				$facility_inout = $tag_info ['facility_inout'];
			}
			
			$cdata2 = array ();
			$cdata2 ['modify_date'] = $date_added;
			$cdata2 ['notes_id'] = $notes_id;
			$cdata2 ['tags_id'] = $fdata ['tags_id'];
			$cdata2 ['facility_inout'] = $facility_inout;
			
			$this->model_resident_resident->updatetagrolecall2 ( $fdata ['tags_id'], $cdata2 );
		}
		
		/*
		 * if(($facilities_id3==$tag_info['facilities_id']) && ($client_statuses_value['status_type']=='1' && $client_statuses_value['is_facility']=='1')){
		 *
		 * if($client_statuses_value['facility_type']!=null && $client_statuses_value['facility_type']!="" || $clientstatus_info['facility_type']!="0"){
		 *
		 *
		 *
		 * $intake_facility_id=$client_statuses_value['facility_type'];
		 *
		 *
		 *
		 * $intake_notes_id = $this->model_notes_notes->jsonaddnotes($data, $intake_facility_id);
		 *
		 *
		 * }
		 */
		
		/*
		 * if($clientstatus_info){
		 *
		 * $intake_facility_id=$client_statuses_value['facility_type'];
		 *
		 *
		 * $intake_notes_id = $this->model_notes_notes->jsonaddnotes($data, $intake_facility_id);
		 *
		 *
		 * }
		 */
		
		/* } */
		
		$this->model_resident_resident->updateclientnotes ( $cdata );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		if ($facility ['enable_facilityinout'] == '1') {
			if ($facility_type > 0) {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout='1',modify_date='" . $date_added . "'  where  tags_id = '" . ( int ) $fdata ['tags_id'] . "'";
				$this->db->query ( $sql );
			} else {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout='0',modify_date='" . $date_added . "'  where  tags_id = '" . ( int ) $fdata ['tags_id'] . "'";
				$this->db->query ( $sql );
			}
		} else {
			$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout='0',modify_date='" . $date_added . "'  where  tags_id = '" . ( int ) $fdata ['tags_id'] . "'";
			$this->db->query ( $sql );
		}
		
		
		if($tag_info ['notes_id'] > 0){
			$move_notes_id = $tag_info ['notes_id'];
		}else{
			$move_notes_id = $notes_id;
		}
		
		//if ($clientstatus_info ['track_time'] == 1) {
			$tmdata = array ();
			$tmdata ['notes_id'] = $notes_id;
			$tmdata ['facilities_id'] = $tag_info ['facilities_id'];
			$tmdata ['unique_id'] = $facilities_info ['customer_key'];
			$tmdata ['tags_id'] = $tag_info ['tags_id'];
			$tmdata ['tag_status_id'] = $clientstatus_info ['tag_status_id'];
			$tmdata ['new_tag_status_id'] = $tag_status_id;
			$tmdata ['fixed_status_id'] = $fixed_status_id;
			$tmdata ['comments'] = $substatus_idscomment;
			$tmdata ['move_notes_id'] = $move_notes_id;
			
			$tmdata ['keyword_id'] = '';
			$tmdata ['types'] = 1;
			
			
				$tmdata ['years'] = $since_start->y;
				$tmdata ['months'] = $since_start->m;
				$tmdata ['days'] = $since_start->d;
				$tmdata ['hours'] = $since_start->h;
				$tmdata ['minutes'] = $since_start->i;
			
			$tmdata ['date_added'] = date ( 'Y-m-d H:i:s' );
			$this->addtracktime ( $tmdata );
		//}
		
		/*
		 * if ($fdata['discharge'] == "1") {
		 * $this->load->model('setting/tags');
		 * $this->model_setting_tags->addcurrentTagarchive($fdata['tags_id']);
		 * $this->model_setting_tags->updatecurrentTagarchive($fdata['tags_id'], $notes_id);
		 *
		 * $this->model_resident_resident->updateDischargeTag($fdata['tags_id'], $date_added);
		 * } else {
		 * // $this->model_resident_resident->updatetagrolecall($fdata['tags_id'], $fdata['role_call']);
		 * }
		 */
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		return $notes_id;
	}
	public function residentstatussign($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		
		$data ['notetime'] = $notetime;
		$data ['note_date'] = $date_added;
		$data ['facilitytimezone'] = $timezone_name;
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		$tagstatus = array ();
		
		$currentdate = date ( 'Y-m-d' );
		
		$data2 = array (
				'currentdate' => $currentdate,
				'tags_id' => $fdata ['tags_id'] 
		);
		
		$this->load->model ( 'resident/resident' );
		$task_infos = $this->model_resident_resident->getResidentstatus ( $data2 );
		
		$totaltask_infos = $this->model_resident_resident->getTotalResidentstatus ( $data2 );
		
		foreach ( $task_infos as $taskinfo ) {
			$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $taskinfo ['tagstatus_id'] );
			$tagstatus [] = array (
					'task_id' => $taskinfo ['id'] 
			);
		}
		
		$this->load->model ( 'form/form' );
		$form_infos = $this->model_form_form->getformstatus ( $data2 );
		$totalform_infos = $this->model_form_form->gettotalformstatus ( $data2s );
		
		foreach ( $form_infos as $formdata ) {
			$tagstatus_info = $this->model_resident_resident->getTagstatusbyId ( $formdata ['tagstatus_id'] );
			
			$tagstatus [] = array (
					'forms_id' => $formdata ['forms_id'] 
			);
		}
		
		if ($fdata ['childstatus'] == 'high') {
			$childstatus = 'High';
		}
		
		if ($fdata ['childstatus'] == 'moderate') {
			$childstatus = 'Moderate';
		}
		if ($fdata ['childstatus'] == 'normal') {
			$childstatus = 'Normal';
		}
		
		$data ['notes_description'] = ' Client Status turned to ' . $childstatus . ' ' . $comments;
		$data ['date_added'] = $date_added;
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		if ($tagstatus != NULL && $tagstatus != "") {
			$this->load->model ( 'resident/resident' );
			$tagstatus_id = $this->model_resident_resident->addTagstatus ( $tagstatus, $fdata ['childstatus'], $fdata ['tags_id'], $notes_id );
		} else {
			$this->load->model ( 'resident/resident' );
			$tagstatus_id = $this->model_resident_resident->addTagstatus2 ( $fdata ['childstatus'], $fdata ['tags_id'], $notes_id );
		}
		
		$this->model_notes_notes->updateclient_status ( $notes_id );
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notes_id, $pdata ['tags_id'], $update_date, $tadata );
		}
		
		if ($fdata ['tags_id'] != null && $fdata ['tags_id'] != "") {
			
			$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '" . $date_added . "' where  tags_id = '" . $fdata ['tags_id'] . "'";
			$sql = $this->db->query ( $sql1 );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		return $notes_id;
	}
	public function gettagsFormDatas($tags_forms_id) {
		$query = $this->db->query ( "SELECT tags_forms_id,tags_id,notes_id,forms_design_id,forms_id,facilities_id,design_forms,form_description,rules_form_description,user_id,signature,notes_pin,form_date_added,notes_type,type,date_added,date_updated,status,form_signature,upload_file,is_discharge FROM " . DB_PREFIX . "tags_forms WHERE tags_forms_id = '" . $tags_forms_id . "' " );
		return $query->row;
	}
	function get_formbynotesid($notes_id) {
		$sql = "SELECT tags_forms_id,tags_id,notes_id,forms_design_id,forms_id,facilities_id,design_forms,form_description,rules_form_description,user_id,signature,notes_pin,form_date_added,notes_type,type,date_added,date_updated,status,form_signature,upload_file,is_discharge FROM `" . DB_PREFIX . "tags_forms` WHERE notes_id = '" . $notes_id . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function gettagsforms($tags_id) {
		$sql = "SELECT tags_forms_id,tags_id,notes_id,forms_design_id,forms_id,facilities_id,design_forms,form_description,rules_form_description,user_id,signature,notes_pin,form_date_added,notes_type,type,date_added,date_updated,status,form_signature,upload_file,is_discharge FROM " . DB_PREFIX . "tags_forms";
		
		$sql .= " where 1 = 1 and status = '1' and is_discharge = '0' and tags_id = '" . $tags_id . "' ";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function updateDischargeTag($tags_id, $date_added) {
		$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET discharge = '1', discharge_date = '" . $date_added . "', modify_date = '" . $date_added . "' where  tags_id = '" . $tags_id . "'" );
		
		$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication` SET is_discharge = '1' where  tags_id = '" . $tags_id . "'" );
		$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication_details` SET is_discharge = '1', is_schedule_medication = '0' where  tags_id = '" . $tags_id . "'" );
		
		$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET is_discharge = '1' where  tags_id = '" . $tags_id . "' " );
		$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "tagstatus` SET is_discharge = '1' where  tags_id = '" . $tags_id . "' " );
		// $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags_forms` SET
		// is_discharge = '1' where tags_id = '".$tags_id."' and forms_design_id =
		// '".CUSTOME_INTAKEID."' ");
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['date_added'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'updateDischargeTag', $data, 'query' );
	}
	public function updatetagrolecall($tags_id, $role_call) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->load->model ( 'facilities/facilities' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET role_call = '" . $role_call . "', modify_date = '" . $date_added . "' where  tags_id = '" . $tags_id . "'";
		$sql = $this->db->query ( $sql1 );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['role_call'] = $role_call;
		$this->model_activity_activity->addActivitySave ( 'updatetagrolecall', $data, 'query' );
	}
	public function updatetagcolor($tags_id, $highliter_id, $text_highliter_div_cl) {
		$query = $this->db->query ( "SELECT color_id FROM `" . DB_PREFIX . "tags_color` WHERE color_id = '" . $text_highliter_div_cl . "'" );
		
		if ($query->num_rows > 0) {
			$sql = $this->db->query ( "UPDATE `" . DB_PREFIX . "tags_color` SET color_id = '#" . $highliter_id . "',tags_id = '" . $tags_id . "',date_updated = NOW()  where text_highliter_div_cl= '" . $text_highliter_div_cl . "' " );
		} else {
			$sql = $this->db->query ( "INSERT INTO  `" . DB_PREFIX . "tags_color` SET color_id = '#" . $highliter_id . "', tags_id = '" . $tags_id . "', text_highliter_div_cl = '" . $text_highliter_div_cl . "',date_added = NOW(),date_updated = NOW() " );
		}
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['highliter_id'] = $highliter_id;
		$data ['text_highliter_div_cl'] = $text_highliter_div_cl;
		$this->model_activity_activity->addActivitySave ( 'updatetagcolor', $data, 'query' );
	}
	public function getagsColors($tags_id) {
		$query = $this->db->query ( "SELECT tags_color_id,tags_id,color_id,text_highliter_div_cl,date_added,date_updated FROM " . DB_PREFIX . "tags_color WHERE tags_id = '" . $tags_id . "' " );
		return $query->rows;
	}
	public function getFormDatadesign($forms_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $forms_id . "' " );
		return $query->row;
	}
	public function gettagmedicine($tags_id, $is_archive, $notes_id) {
		if ($is_archive == '1') {
			$sql = "SELECT archive_tags_medication_id,tags_medication_id,tags_id,medication_fields,is_schedule,is_discharge,is_archive,notes_id FROM " . DB_PREFIX . "archive_tags_medication";
		} else {
			$sql = "SELECT tags_medication_id,tags_id,medication_fields,is_schedule,is_discharge FROM " . DB_PREFIX . "tags_medication";
		}
		// $sql = "SELECT * FROM " . DB_PREFIX . "tags_medication";
		
		$sql .= " where 1 = 1 and status = '1' and is_discharge = '0' and tags_id = '" . $tags_id . "' ";
		
		if ($is_archive == '1') {
			$sql .= " and notes_id = '" . $this->db->escape ( $notes_id ) . "'";
		}
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function gettagModule($tags_id, $is_archive, $notes_id) {
		if ($is_archive == '1') {
			$sql1 = "SELECT archive_tags_medication_details_id,archive_tags_medication_id,tags_medication_details_id	,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime	,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,is_archive,notes_id,drug_mg,drug_alertnate,drug_prn,tags_medication_details_ids,route,doctors,reasons,type,type_name,drug_am,drug_pm,image FROM `" . DB_PREFIX . "archive_tags_medication_details` WHERE tags_id = '" . $tags_id . "' and notes_id = '" . $notes_id . "' and is_discharge = '0' ";
			$query = $this->db->query ( $sql1 );
		} else {
			$query = $this->db->query ( "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,drug_mg,drug_alertnate,drug_prn,tags_medication_details_ids,type,route,doctors,reasons,type_name,drug_am,drug_pm,image FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' and status = '1' " );
		}
		
		$new_module = array ();
		
		if ($query->num_rows) {
			foreach ( $query->rows as $rows ) {
				
				$sql = "SELECT tags_medication_details_time_id,start_time,tags_medication_details_id,tags_medication_id,tags_id,create_task FROM `" . DB_PREFIX . "tags_medication_details_time` WHERE tags_medication_details_id = '" . $rows ['tags_medication_details_id'] . "'";
				
				$queryrow = $this->db->query ( $sql );
				
				$dates = array ();
				
				foreach ( $queryrow->rows as $startdates ) {
					$dates [] = array (
							'start_time' => date ( 'h:i A', strtotime ( $startdates ['start_time'] ) ) 
					);
				}
				
				if ($rows ['end_recurrence_date'] != null && $rows ['end_recurrence_date'] != "0000-00-00 00:00:00") {
					$end_recurrence_date = date ( 'm-d-Y', strtotime ( $rows ['end_recurrence_date'] ) );
				}
				
				if ($rows ['daily_endtime'] != null && $rows ['daily_endtime'] != "19:00:00") {
					$daily_endtime = date ( 'h:i A', strtotime ( $rows ['daily_endtime'] ) );
				}
				
				if ($rows ['daily_times']) {
					$daily_times = explode ( ',', $rows ['daily_times'] );
				} else {
					$daily_times = array ();
				}
				
				if ($rows ['recurnce_week']) {
					$recurnce_week = explode ( ',', $rows ['recurnce_week'] );
				}
				
				if ($rows ['date_from'] != null && $rows ['date_from'] != "0000-00-00") {
					$date_from = date ( 'm-d-Y', strtotime ( $rows ['date_from'] ) );
				} else {
					$date_from = date ( 'm-d-Y' );
				}
				
				if ($rows ['date_to'] != null && $rows ['date_to'] != "0000-00-00") {
					$date_to = date ( 'm-d-Y', strtotime ( $rows ['date_to'] ) );
				} else {
					$date_to = date ( 'm-d-Y', strtotime ( "+1 days" ) );
				}
				
				if ($rows ['tags_medication_details_ids']) {
					$tags_medication_details_ids = explode ( ',', $rows ['tags_medication_details_ids'] );
				} else {
					$tags_medication_details_ids = array ();
				}
				
				$new_module ['new_module'] [] = array (
						'tags_medication_details_id' => $rows ['tags_medication_details_id'],
						'tags_medication_id' => $rows ['tags_medication_id'],
						'drug_name' => $rows ['drug_name'],
						'drug_mg' => $rows ['drug_mg'],
						'drug_am' => $rows ['drug_am'],
						'drug_pm' => $rows ['drug_pm'],
						'drug_alertnate' => $rows ['drug_alertnate'],
						'drug_prn' => $rows ['drug_prn'],
						'instructions' => $rows ['instructions'],
						'status' => $rows ['status'],
						'image' => $rows ['image'],
						
						'recurrence' => $rows ['recurrence'],
						'recurnce_hrly_recurnce' => $rows ['recurnce_hrly_recurnce'],
						'end_recurrence_date' => $end_recurrence_date,
						'daily_endtime' => $daily_endtime,
						'daily_times' => $daily_times,
						'recurnce_hrly' => $rows ['recurnce_hrly'],
						'recurnce_week' => $recurnce_week,
						'recurnce_month' => $rows ['recurnce_month'],
						'recurnce_day' => $rows ['recurnce_day'],
						'date_from' => $date_from,
						'date_to' => $date_to,
						'is_schedule_medication' => $rows ['is_schedule_medication'],
						'type' => $rows ['type'],
						'route' => $rows ['route'],
						'doctors' => $rows ['doctors'],
						'reasons' => $rows ['reasons'],
						'type_name' => $rows ['type_name'],
						
						'start_time' => $dates,
						'tags_medication_details_ids' => $tags_medication_details_ids 
				);
			}
		}
		return $new_module;
	}
	function addTagsMedication($data, $tags_id, $updateMedication, $facilities_id, $addmedication) {
		$deledeids = array ();
		
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'user/user' );
		
		if (($data ['user_role_assign_ids'] != null && $data ['user_role_assign_ids'] != "") || ($data ['assign_to'] != null && $data ['assign_to'] != "")) {
			
			if ($data ['user_role_assign_ids'] != null && $data ['user_role_assign_ids'] != "") {
				$user_role_assign_ids = implode ( ',', $data ['user_role_assign_ids'] );
			}
			
			if ($data ['assign_to'] != null && $data ['assign_to'] != "") {
				$aid = array ();
				foreach ( $data ['assign_to'] as $asid ) {
					$user_info = $this->model_user_user->getUserByUsername ( $asid );
					$aid [] = $user_info ['user_id'];
				}
				$assign_to = implode ( ',', $aid );
			}
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET user_role_assign_ids = '" . $user_role_assign_ids . "', assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "' where tags_id = '" . $tags_id . "' AND status = 1 AND discharge ='0'" );
		}
		
		$query1 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
		
		if ($query1->num_rows > 0) {
			
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_medication` SET tags_medication_id = '" . $this->db->escape ( $query1->row ['tags_medication_id'] ) . "' , medication_fields = '" . $this->db->escape ( $query1->row ['medication_fields'] ) . "', is_schedule = '" . $this->db->escape ( $query1->row ['is_schedule'] ) . "' , status = '1', tags_id = '" . $query1->row ['tags_id'] . "', is_archive = '2' " );
			$archive_tags_medication_id = $this->db->getLastId ();
		}
		
		if ($query1->num_rows > 0) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "', status = '1' where tags_id = '" . $tags_id . "' " );
			
			$tags_medication_id = $query1->row ['tags_medication_id'];
		} else {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "' , status = '1', tags_id = '" . $tags_id . "'" );
			
			$tags_medication_id = $this->db->getLastId ();
		}
		
		if ($data ['refill_percentage'] != "" && $data ['refill_percentage'] != null) {
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET refill_percentage = '" . $this->db->escape ( $data ['refill_percentage'] ) . "', status = '1' where tags_id = '" . $tags_id . "' AND discharge ='0'" );
			
			$tag_info = $this->model_setting_tags->getTag ( $tags_id );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			if ($tag_info ['refill_percentage'] != 0) {
				
				switch ($tag_info ['refill_percentage']) {
					
					case 1 :
						$refill_percentage = 10;
						break;
					case 2 :
						$refill_percentage = 20;
						break;
					case 3 :
						$refill_percentage = 30;
						break;
					case 4 :
						$refill_percentage = 40;
						break;
					case 5 :
						$refill_percentage = 50;
						break;
					case 6 :
						$refill_percentage = 60;
						break;
					case 7 :
						$refill_percentage = 70;
						break;
					case 8 :
						$refill_percentage = 80;
						break;
					case 9 :
						$refill_percentage = 90;
						break;
					case 10 :
						$refill_percentage = 100;
						break;
				}
				
				if ($data ['new_module']) {
					foreach ( $data ['new_module'] as $mediactiondata ) {
						
						$query1 = $this->db->query ( "SELECT initial_quantity FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '" . $mediactiondata ['tags_medication_details_id'] . "' and is_discharge = '0' " );
						
						$pre_value = $query1->row ['initial_quantity'];
						$min_value = (($refill_percentage / 100) * ( int ) $pre_value);
						
						if (( float ) $mediactiondata ['drug_mg'] <= ( float ) $min_value) {
							$percentage_values [] = "1";
						} else {
							
							$percentage_values [] = "";
						}
					}
				}
				
				if (in_array ( "1", $percentage_values )) {
					
					$this->load->model ( 'api/emailapi' );
					
					$usermeails = array ();
					if ($tag_info ['assign_to'] != "" && $tag_info ['assign_to'] != NULL) {
						
						if (is_array ( $tag_info ['assign_to'] )) {
							
							foreach ( $tag_info ['assign_to'] as $userid ) {
								
								$user_info = $this->model_user_user->getUserbyupdate ( $userid );
								if ($user_info ['email'] != null && $user_info ['email'] != "") {
									$usermeails [] = $user_info ['email'];
								}
							}
						} else {
							
							$user_info = $this->model_user_user->getUserbyupdate ( $tag_info ['assign_to'] );
							
							$email = $user_info ['email'];
						}
						
						if ($email != null) {
							
							$emailData = array ();
							$emailData ['facility'] = $facility ['facility'];
							$emailData ['username'] = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
							$emailData ['user_email'] = $email;
							$emailData ['type'] = '26';
							
							$email_status = $this->model_resident_resident->createMails ( $emailData );
						} else {
							
							if (! empty ( $usermeails )) {
								
								$emailData = array ();
								$emailData ['facility'] = $facility ['facility'];
								$emailData ['username'] = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
								$emailData ['useremailids'] = $usermeails;
								$emailData ['type'] = '26';
								$email_status = $this->model_resident_resident->createMails ( $emailData );
							}
						}
					}
					
					if ($tag_info ['user_role_assign_ids'] != "" && $tag_info ['user_role_assign_ids'] != NULL) {
						
						if ($data ['user_role_assign_ids'] != null && $data ['user_role_assign_ids'] != "") {
							$user_role_assign_ids = implode ( ',', $data ['user_role_assign_ids'] );
						}
						
						if (is_array ( $tag_info ['user_role_assign_ids'] )) {
							
							foreach ( $tag_info ['user_role_assign_ids'] as $user_role ) {
								$urole = array ();
								$urole ['user_group_id'] = $user_role;
								$tusers = $this->model_user_user->getUsers ( $urole );
								
								if ($tusers) {
									foreach ( $tusers as $userid ) {
										if ($userid ['email'] != null && $userid ['email'] != "") {
											$usermeails [] = $userid ['email'];
										}
									}
								}
							}
						} else {
							
							$urole = array ();
							$urole ['user_group_id'] = $tag_info ['user_role_assign_ids'];
							
							$tusers = $this->model_user_user->getUsers ( $urole );
							
							foreach ( $tusers as $value ) {
								
								$usermeails [] = $value ['email'];
							}
						}
						
						/*
						 * }else{
						 *
						 * echo "single user"; var_dump($tusers);
						 *
						 * $tusers = $this->model_user_user->getUsers($tag_info['user_role_assign_ids']);
						 *
						 * $email = $tusers['email'];
						 *
						 *
						 * }
						 */
						
						if ($email != null) {
							
							$emailData = array ();
							$emailData ['facility'] = $facility ['facility'];
							$emailData ['username'] = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
							$emailData ['user_email'] = $email;
							$emailData ['type'] = '26';
							
							$email_status = $this->model_resident_resident->createMails ( $emailData );
						} else {
							
							if ($usermeails != null) {
								
								$emailData = array ();
								$emailData ['facility'] = $facility ['facility'];
								$emailData ['username'] = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
								$emailData ['useremailids'] = $usermeails;
								$emailData ['type'] = '26';
								$email_status = $this->model_resident_resident->createMails ( $emailData );
							}
						}
					}
				}
			}
		}
		
		// var_dump($assign_to);die;
		
		if ($data ['new_module']) {
			foreach ( $data ['new_module'] as $mediactiondata ) {
				
				$deledeids [] = $mediactiondata ['tags_medication_details_id'];
			}
			
			if (! empty ( $deledeids )) {
				// $userIds12 = array_unique ( $deledeids );
				// $userIds21 = implode('\',\'',$userIds12);
				
				// $sql33 = "delete FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and tags_medication_id NOTIN ('".$userIds21."') ";
				// $this->db->query($sql33);
			}
		}
		
		// die;
		// $this->db->query("delete FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' ");
		// $this->db->query("delete FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and status = '0' ");
		
		$query1 = $this->db->query ( "SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
		
		if ($query1->num_rows > 0) {
			
			if ($updateMedication != 0) {
				
				$is_archive = "0";
			} else {
				
				$is_archive = "1";
			}
			
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_medication` SET tags_medication_id = '" . $this->db->escape ( $query1->row ['tags_medication_id'] ) . "' , medication_fields = '" . $this->db->escape ( $query1->row ['medication_fields'] ) . "', is_schedule = '" . $this->db->escape ( $query1->row ['is_schedule'] ) . "' , status = '1', tags_id = '" . $query1->row ['tags_id'] . "', is_archive = '" . $is_archive . "' " );
			$archive_tags_medication_id = $this->db->getLastId ();
			
			$query12 = $this->db->query ( "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,tags_medication_details_ids,route,doctors,reasons,is_updated,is_schedule_id FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
			
			$dbids = array ();
			if ($query12->num_rows > 0) {
				
				foreach ( $query12->rows as $mrow ) {
					$dbids [] = $mrow ['tags_medication_details_id'];
					$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_medication_details` SET tags_medication_details_id = '" . $this->db->escape ( $mrow ['tags_medication_details_id'] ) . "', drug_name = '" . $this->db->escape ( $mrow ['drug_name'] ) . "', drug_mg = '" . $this->db->escape ( $mrow ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $mrow ['drug_am'] ) . "', drug_pm = '" . $this->db->escape ( $mrow ['drug_pm'] ) . "', drug_alertnate = '" . $this->db->escape ( $mrow ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $mrow ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $mrow ['instructions'] ) . "', status = '" . $mrow ['status'] . "', tags_id = '" . $mrow ['tags_id'] . "', tags_medication_id = '" . $mrow ['tags_medication_id'] . "', recurrence = '" . $this->db->escape ( $mrow ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $mrow ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $mrow ['end_recurrence_date'] . "', recurnce_day = '" . $this->db->escape ( $mrow ['recurnce_day'] ) . "', recurnce_month = '" . $this->db->escape ( $mrow ['recurnce_month'] ) . "', recurnce_week = '" . $this->db->escape ( $mrow ['recurnce_week'] ) . "', recurnce_hrly_recurnce = '" . $mrow ['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $mrow ['daily_endtime'] . "', daily_times = '" . $mrow ['daily_times'] . "', date_from = '" . $mrow ['date_from'] . "', date_to = '" . $mrow ['date_to'] . "', is_schedule_medication = '" . $mrow ['is_schedule_medication'] . "', tags_medication_details_ids = '" . $mrow ['tags_medication_details_ids'] . "', is_updated = '" . $mrow ['is_updated'] . "', type_name = '" . $mrow ['type_name'] . "', type = '" . $mrow ['type'] . "', image = '" . $mrow ['image'] . "',route = '" . $mrow ['route'] . "',reasons = '" . $mrow ['reasons'] . "',doctors = '" . $mrow ['doctors'] . "', is_schedule_id = '" . $mrow ['is_schedule_id'] . "', archive_tags_medication_id = '" . $archive_tags_medication_id . "', is_archive = '" . $is_archive . "' " );
				}
			}
		}
		
		$result22 = array_diff ( $dbids, $deledeids );
		
		if (! empty ( $result22 )) {
			foreach ( $result22 as $dbid ) {
				$this->db->query ( "delete FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and tags_medication_details_id = '" . $dbid . "' " );
				// $this->db->query("delete FROM `" . DB_PREFIX . "createtask_medications` WHERE tags_id = '" . $tags_id . "' and tags_medication_details_id = '".$dbid."' ");
			}
		}
		
		if ($query1->num_rows > 0) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "', status = '1' where tags_id = '" . $tags_id . "' " );
			
			$tags_medication_id = $query1->row ['tags_medication_id'];
		} else {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "' , status = '1', tags_id = '" . $tags_id . "'" );
			
			$tags_medication_id = $this->db->getLastId ();
		}
		
		/*
		 * if(empty($this->request->post['medication'])){
		 * $this->db->query("DELETE FROM `" . DB_PREFIX .
		 * "tags_medication_details` WHERE tags_id = '" . (int)$tags_id . "' and
		 * is_discharge = '0' ");
		 *
		 * $this->db->query("DELETE FROM `" . DB_PREFIX .
		 * "tags_medication_details_time` WHERE tags_id = '" . (int)$tags_id .
		 * "' ");
		 * }
		 */
		
		if ($data ['new_module']) {
			foreach ( $data ['new_module'] as $mediactiondata ) {
				$drug_am = $mediactiondata ['drug_am'];
				$drug_pm = $mediactiondata ['drug_pm'];
				
				$date1 = str_replace ( '-', '/', $mediactiondata ['end_recurrence_date'] );
				$res1 = explode ( "/", $date1 );
				$dateRange1 = $res1 [2] . "-" . $res1 [0] . "-" . $res1 [1];
				
				$time1 = date ( 'H:i:s' );
				$end_recurrence_date = $dateRange1 . ' ' . $time1;
				
				if ($mediactiondata ['is_schedule_medication'] == '1') {
					$date21 = str_replace ( '-', '/', $mediactiondata ['date_from'] );
					$res12 = explode ( "/", $date21 );
					$date_from = $res12 [2] . "-" . $res12 [0] . "-" . $res12 [1];
					
					$date21q = str_replace ( '-', '/', $mediactiondata ['date_to'] );
					$res122 = explode ( "/", $date21q );
					$date_to = $res122 [2] . "-" . $res122 [0] . "-" . $res122 [1];
					
					$daily_times_1 = implode ( ',', $mediactiondata ['daily_times'] );
				} else {
					$daily_times_1 = '';
					$date_from = '';
					$date_to = '';
				}
				
				$recurnce_week = implode ( ',', $mediactiondata ['recurnce_week'] );
				
				$daily_endtime = date ( 'H:i:s', strtotime ( $mediactiondata ['daily_endtime'] ) );
				
				$tags_medication_details_ids = implode ( ',', $mediactiondata ['tags_medication_details_ids'] );
				
				$sssql = "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,is_updated,route,reasons,doctors,is_schedule_id,initial_quantity,tags_medication_details_ids FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '" . $mediactiondata ['tags_medication_details_id'] . "'";
				$query = $this->db->query ( $sssql );
				
				if ($query->num_rows > 0) {
					
					$this->load->model ( 'setting/tags' );
					$this->load->model ( 'setting/timezone' );
					$this->load->model ( 'facilities/facilities' );
					$this->load->model ( 'createtask/createtask' );
					$tag_info = $this->model_setting_tags->getTag ( $tags_id );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					
					date_default_timezone_set ( $timezone_info ['timezone_value'] );
					$current_date = date ( 'Y-m-d', strtotime ( 'now' ) );
					
					$query = $this->db->query ( "SELECT initial_quantity FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '" . $mediactiondata ['tags_medication_details_id'] . "' and is_discharge = '0' AND status='1' " );
					
					if ((( int ) $mediactiondata ['drug_mg']) > ($query->row ['initial_quantity'])) {
						
						$updated_initial = $mediactiondata ['drug_mg'];
					} else {
						
						$updated_initial = $query->row ['initial_quantity'];
					}
					
					if ($mediactiondata ['is_schedule_medication'] == '1') {
						$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication_details` SET  daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times_1 . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata ['is_schedule_medication'] . "', is_updated = '" . $is_updated . "',create_task = '" . $create_task . "', tags_medication_details_ids = '" . $tags_medication_details_ids . "' where tags_medication_details_id = '" . $mediactiondata ['tags_medication_details_id'] . "' " );
					} else {
						
						$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape ( $mediactiondata ['drug_name'] ) . "', drug_mg = '" . $this->db->escape ( $mediactiondata ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $drug_am ) . "', drug_pm = '" . $this->db->escape ( $drug_pm ) . "', initial_quantity = '" . $this->db->escape ( $updated_initial ) . "', drug_alertnate = '" . $this->db->escape ( $mediactiondata ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $mediactiondata ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $mediactiondata ['instructions'] ) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape ( $mediactiondata ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $mediactiondata ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape ( $recurnce_day ) . "', recurnce_month = '" . $this->db->escape ( $recurnce_month ) . "', recurnce_week = '" . $this->db->escape ( $recurnce_week ) . "', recurnce_hrly_recurnce = '" . $mediactiondata ['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times_1 . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata ['is_schedule_medication'] . "', type = '" . $mediactiondata ['type'] . "', type_name = '" . $mediactiondata ['type_name'] . "', image = '" . $mediactiondata ['image'] . "',route = '" . $mediactiondata ['route'] . "',reasons = '" . $mediactiondata ['reasons'] . "',doctors = '" . $mediactiondata ['doctors'] . "', is_updated = '" . $is_updated . "',create_task = '" . $create_task . "', tags_medication_details_ids = '" . $tags_medication_details_ids . "' where tags_medication_details_id = '" . $mediactiondata ['tags_medication_details_id'] . "' " );
					}
					if ($mediactiondata ['is_schedule_medication'] == '1') {
						
						$sqlt2 = "SELECT count(*) as total from " . DB_PREFIX . "createtask_medications where tags_medication_details_id = '" . $mediactiondata ['tags_medication_details_id'] . "' and tags_id = '" . $tags_id . "' ";
						$qt2 = $this->db->query ( $sqlt2 );
						
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = date ( 'Y-m-d', strtotime ( 'now' ) );
						
						// $taskTime = date('h:i A', strtotime('now'));
						
						$snooze_time71 = 3;
						$thestime61 = date ( 'H:i:s' );
						$taskTime = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
						
						$current_time = date ( "H:i:s" );
						
						$time1 = date ( 'H:i:s' );
						
						$daily_times = array ();
						// $daily_times1 = explode ( ",", $daily_times );
						// var_dump($daily_times1);
						if ($mediactiondata ['daily_times'] != NULL && $mediactiondata ['daily_times'] != "") {
							foreach ( $mediactiondata ['daily_times'] as $daily_time1 ) {
								
								$int_time1 = date ( 'H:i:s', strtotime ( $daily_time1 ) );
								
								if ($current_time <= $int_time1) {
									
									$daily_times [] = $daily_time1;
								} else {
									$daily_times [] = $daily_time1;
								}
							}
						} else {
							$daily_times [] = $taskTime;
						}
						
						$end_recurrence_date = date ( 'm-d-Y', strtotime ( $date_to ) );
						
						// if ($qt2->row['total'] > 0) {
						// update
						
						// }else{
						
						if ($mediactiondata ['is_schedule_medication'] == '1' && $addmedication != "1") {
							
							$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							$date_added = date ( 'Y-m-d', strtotime ( 'now' ) );
							
							// $taskTime = date('h:i A', strtotime('now'));
							
							$snooze_time71 = 3;
							$thestime61 = date ( 'H:i:s' );
							$taskTime = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
							
							$current_time = date ( "H:i:s" );
							
							$time1 = date ( 'H:i:s' );
							
							$daily_times = array ();
							// $daily_times1 = explode ( ",", $daily_times );
							// var_dump($daily_times1);
							if ($mediactiondata ['daily_times'] != NULL && $mediactiondata ['daily_times'] != "") {
								foreach ( $mediactiondata ['daily_times'] as $daily_time1 ) {
									
									$int_time1 = date ( 'H:i:s', strtotime ( $daily_time1 ) );
									
									if ($current_time <= $int_time1) {
										
										$daily_times [] = $daily_time1;
									} else {
										$daily_times [] = $daily_time1;
									}
								}
							} else {
								$daily_times [] = $taskTime;
							}
							
							$end_recurrence_date = date ( 'm-d-Y', strtotime ( $date_to ) );
							
							$addtaskw ['taskDate'] = date ( 'm-d-Y', strtotime ( $noteDate ) );
							// $addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($noteDate));
							$addtaskw ['end_recurrence_date'] = $end_recurrence_date;
							$addtaskw ['recurrence'] = 'none';
							$addtaskw ['recurnce_week'] = '';
							$addtaskw ['recurnce_hrly'] = '';
							$addtaskw ['recurnce_month'] = '';
							$addtaskw ['recurnce_day'] = '';
							$addtaskw ['taskTime'] = $taskTime; // date('H:i:s');
							$addtaskw ['endtime'] = $taskTime;
							$addtaskw ['description'] = 'Medication for ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
							$addtaskw ['assignto'] = '';
							$addtaskw ['tasktype'] = '2';
							$addtaskw ['numChecklist'] = '';
							$addtaskw ['task_alert'] = '1';
							$addtaskw ['alert_type_sms'] = '';
							$addtaskw ['alert_type_notification'] = '1';
							$addtaskw ['alert_type_email'] = '';
							$addtaskw ['rules_task'] = '';
							
							$addtaskw ['locations_id'] = '';
							$addtaskw ['facilities_id'] = $tag_info ['facilities_id'];
							$addtaskw ['medication_tags'] = explode ( ",", $tags_id );
							// $addtaskw['daily_times'] = explode(",",$rowm['daily_times']);
							$addtaskw ['daily_times'] = $daily_times;
							
							$sss = array ();
							$sssaa = array ();
							
							$sssaa [] = $mediactiondata ['tags_medication_details_id'];
							
							$arrr_m = array ();
							
							if ($mediactiondata ['tags_medication_details_ids'] != null && $mediactiondata ['tags_medication_details_ids'] != "") {
								$arrr_m = array_merge ( $sssaa, $mediactiondata ['tags_medication_details_ids'] );
							} else {
								$arrr_m = $sssaa;
							}
							
							$sss = array_unique ( $arrr_m );
							
							$tags_medication_details_ids1 = array ();
							$tags_medication_details_ids1 [$tags_id] = $sss;
							
							$addtaskw ['tags_medication_details_ids'] = $tags_medication_details_ids1;
							
							if ($mediactiondata ['complete_status'] > 0) {
								$complete_status = $mediactiondata ['complete_status'];
							} else {
								$complete_status = rand ();
							}
							
							$addtaskw ['complete_status'] = $complete_status;
							
							$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskw, $tag_info ['facilities_id'] );
							
							// }
						}
					}
					
					if ($mediactiondata ['start_time']) {
						foreach ( $mediactiondata ['start_time'] as $time ) {
							
							$tasksTiming = date ( 'H:i:s', strtotime ( $time ) );
							
							$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication_details_time` SET start_time = '" . $this->db->escape ( $tasksTiming ) . "', tags_medication_id = '" . $tags_medication_id . "', tags_medication_details_id = '" . $tags_medication_details_id . "', tags_id = '" . $tags_id . "' " );
						}
					}
				} else {
					
					if ($mediactiondata ['drug_name'] != null && $mediactiondata ['drug_name'] != "") {
						$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape ( $mediactiondata ['drug_name'] ) . "', drug_mg = '" . $this->db->escape ( $mediactiondata ['drug_mg'] ) . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "', initial_quantity = '" . $this->db->escape ( $mediactiondata ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $drug_am ) . "', drug_pm = '" . $this->db->escape ( $drug_pm ) . "', drug_alertnate = '" . $this->db->escape ( $mediactiondata ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $mediactiondata ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $mediactiondata ['instructions'] ) . "', status = '0', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape ( $mediactiondata ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $mediactiondata ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape ( $recurnce_day ) . "', recurnce_month = '" . $this->db->escape ( $recurnce_month ) . "', recurnce_week = '" . $this->db->escape ( $recurnce_week ) . "', recurnce_hrly_recurnce = '" . $mediactiondata ['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times_1 . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata ['is_schedule_medication'] . "', type = '" . $mediactiondata ['type'] . "', type_name = '" . $mediactiondata ['type_name'] . "', image = '" . $mediactiondata ['image'] . "',route = '" . $mediactiondata ['route'] . "',reasons = '" . $mediactiondata ['reasons'] . "',doctors = '" . $mediactiondata ['doctors'] . "', is_updated = '" . $mediactiondata ['is_updated'] . "', tags_medication_details_ids = '" . $tags_medication_details_ids . "' " );
						
						$tags_medication_details_id = $this->db->getLastId ();
						
						if ($mediactiondata ['is_schedule_medication'] == '1' && $addmedication != "1") {
							
							$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							$date_added = date ( 'Y-m-d', strtotime ( 'now' ) );
							
							// $taskTime = date('h:i A', strtotime('now'));
							
							$snooze_time71 = 3;
							$thestime61 = date ( 'H:i:s' );
							$taskTime = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
							
							$current_time = date ( "H:i:s" );
							
							$time1 = date ( 'H:i:s' );
							
							$daily_times = array ();
							// $daily_times1 = explode ( ",", $daily_times );
							// var_dump($daily_times1);
							if ($mediactiondata ['daily_times'] != NULL && $mediactiondata ['daily_times'] != "") {
								foreach ( $mediactiondata ['daily_times'] as $daily_time1 ) {
									
									$int_time1 = date ( 'H:i:s', strtotime ( $daily_time1 ) );
									
									if ($current_time <= $int_time1) {
										
										$daily_times [] = $daily_time1;
									} else {
										$daily_times [] = $daily_time1;
									}
								}
							} else {
								$daily_times [] = $taskTime;
							}
							
							$end_recurrence_date = date ( 'm-d-Y', strtotime ( $mediactiondata ['date_to'] ) );
							
							$addtaskw ['taskDate'] = date ( 'm-d-Y', strtotime ( $noteDate ) );
							// $addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($noteDate));
							$addtaskw ['end_recurrence_date'] = $end_recurrence_date;
							$addtaskw ['recurrence'] = 'none';
							$addtaskw ['recurnce_week'] = '';
							$addtaskw ['recurnce_hrly'] = '';
							$addtaskw ['recurnce_month'] = '';
							$addtaskw ['recurnce_day'] = '';
							$addtaskw ['taskTime'] = $taskTime; // date('H:i:s');
							$addtaskw ['endtime'] = $taskTime;
							$addtaskw ['description'] = 'Medication for ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
							$addtaskw ['assignto'] = '';
							$addtaskw ['tasktype'] = '2';
							$addtaskw ['numChecklist'] = '';
							$addtaskw ['task_alert'] = '1';
							$addtaskw ['alert_type_sms'] = '';
							$addtaskw ['alert_type_notification'] = '1';
							$addtaskw ['alert_type_email'] = '';
							$addtaskw ['rules_task'] = '';
							
							$addtaskw ['locations_id'] = '';
							$addtaskw ['facilities_id'] = $tag_info ['facilities_id'];
							$addtaskw ['medication_tags'] = explode ( ",", $tags_id );
							// $addtaskw['daily_times'] = explode(",",$rowm['daily_times']);
							$addtaskw ['daily_times'] = $daily_times;
							
							$sss = array ();
							$sssaa = array ();
							$sssaa [] = $tags_medication_details_id;
							
							$arrr_m = array ();
							if ($mediactiondata ['tags_medication_details_ids'] != null && $mediactiondata ['tags_medication_details_ids'] != "") {
								$arrr_m = array_merge ( $sssaa, $mediactiondata ['tags_medication_details_ids'] );
							} else {
								$arrr_m = $sssaa;
							}
							
							$sss = array_unique ( $arrr_m );
							
							$tags_medication_details_ids1 = array ();
							// $tags_medication_details_ids[$rowm['tags_id']][] = $rowm['tags_medication_details_id'];
							$tags_medication_details_ids1 [$tags_id] = $sss;
							
							$addtaskw ['tags_medication_details_ids'] = $tags_medication_details_ids1;
							
							if ($mediactiondata ['complete_status'] > 0) {
								$complete_status = $mediactiondata ['complete_status'];
							} else {
								$complete_status = rand ();
							}
							
							$addtaskw ['complete_status'] = $complete_status;
							$this->load->model ( 'createtask/createtask' );
							
							$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskw, $tag_info ['facilities_id'] );
						}
						
						if ($mediactiondata ['start_time']) {
							foreach ( $mediactiondata ['start_time'] as $time ) {
								
								$tasksTiming = date ( 'H:i:s', strtotime ( $time ) );
								
								$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication_details_time` SET start_time = '" . $this->db->escape ( $tasksTiming ) . "', tags_medication_id = '" . $tags_medication_id . "', tags_medication_details_id = '" . $tags_medication_details_id . "', tags_id = '" . $tags_id . "' " );
							}
						}
					}
				}
			}
		}
		
		if ($data ['drug_name']) {
			
			// $drug_am = date('H:i:s', strtotime($data['drug_am']));
			// $drug_pm = date('H:i:s', strtotime($data['drug_pm']));
			
			$drug_am = $data ['drug_am'];
			$drug_pm = $data ['drug_pm'];
			
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape ( $data ['drug_name'] ) . "', drug_mg = '" . $this->db->escape ( $data ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $drug_am ) . "', drug_pm = '" . $this->db->escape ( $drug_pm ) . "', drug_alertnate = '" . $this->db->escape ( $data ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $data ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $data ['instructions'] ) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "' " );
			
			$tags_medication_details_id = $this->db->getLastId ();
			
			if ($data ['new_module2']) {
				foreach ( $data ['new_module2'] as $mediactiondata ) {
					
					if ($mediactiondata ['start_time']) {
						foreach ( $mediactiondata ['start_time'] as $time ) {
							
							$tasksTiming = date ( 'H:i:s', strtotime ( $time ) );
							$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication_details_time` SET start_time = '" . $this->db->escape ( $tasksTiming ) . "', tags_medication_id = '" . $tags_medication_id . "', tags_medication_details_id = '" . $tags_medication_details_id . "', tags_id = '" . $tags_id . "' " );
						}
					}
				}
			}
		}
		
		$query1add = $this->db->query ( "SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
		
		if ($query1add->num_rows == 1) {
			
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_medication` SET tags_medication_id = '" . $this->db->escape ( $query1add->row ['tags_medication_id'] ) . "' , medication_fields = '" . $this->db->escape ( $query1add->row ['medication_fields'] ) . "', is_schedule = '" . $this->db->escape ( $query1add->row ['is_schedule'] ) . "' , status = '1', tags_id = '" . $query1add->row ['tags_id'] . "', is_archive = '" . $is_archive . "' " );
			$archive_tags_medication_id = $this->db->getLastId ();
			
			$query12 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
			
			if ($query12->num_rows > 0) {
				
				foreach ( $query12->rows as $mrow ) {
					
					$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_medication_details` SET tags_medication_details_id = '" . $this->db->escape ( $mrow ['tags_medication_details_id'] ) . "', drug_name = '" . $this->db->escape ( $mrow ['drug_name'] ) . "', drug_mg = '" . $this->db->escape ( $mrow ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $mrow ['drug_am'] ) . "', drug_pm = '" . $this->db->escape ( $mrow ['drug_pm'] ) . "', drug_alertnate = '" . $this->db->escape ( $mrow ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $mrow ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $mrow ['instructions'] ) . "', status = '" . $mrow ['status'] . "', tags_id = '" . $mrow ['tags_id'] . "', tags_medication_id = '" . $mrow ['tags_medication_id'] . "', recurrence = '" . $this->db->escape ( $mrow ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $mrow ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $mrow ['end_recurrence_date'] . "', recurnce_day = '" . $this->db->escape ( $mrow ['recurnce_day'] ) . "', recurnce_month = '" . $this->db->escape ( $mrow ['recurnce_month'] ) . "', recurnce_week = '" . $this->db->escape ( $mrow ['recurnce_week'] ) . "', recurnce_hrly_recurnce = '" . $mrow ['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $mrow ['daily_endtime'] . "', daily_times = '" . $mrow ['daily_times'] . "', date_from = '" . $mrow ['date_from'] . "', date_to = '" . $mrow ['date_to'] . "', is_schedule_medication = '" . $mrow ['is_schedule_medication'] . "', type = '" . $mediactiondata ['type'] . "', type_name = '" . $mediactiondata ['type_name'] . "', image = '" . $mediactiondata ['image'] . "',route = '" . $mediactiondata ['route'] . "',reasons = '" . $mediactiondata ['reasons'] . "',doctors = '" . $mediactiondata ['doctors'] . "', tags_medication_details_ids = '" . $mrow ['tags_medication_details_ids'] . "', is_updated = '" . $mrow ['is_updated'] . "', is_schedule_id = '" . $mrow ['is_schedule_id'] . "', archive_tags_medication_id = '" . $archive_tags_medication_id . "', is_archive = '" . $is_archive . "' " );
				}
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$this->model_activity_activity->addActivitySave ( 'addTagsMedication', $data, 'query' );
		
		return $archive_tags_medication_id;
	}
	function get_medication($tags_medication_details_id) {
		$sql = "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '" . $tags_medication_details_id . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	function get_medicationyname($drug_name, $tags_id) {
		$sql = "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge FROM `" . DB_PREFIX . "tags_medication_details` WHERE drug_name = '" . $drug_name . "' and tags_id = '" . $tags_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getFormmedia($tags_forms_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "tags_forms_media WHERE tags_forms_id = '" . $tags_forms_id . "' " );
		return $query->rows;
	}
	public function getResidentstatus($data) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "createtask WHERE emp_tag_id = '" . $data ['tags_id'] . "' and recurrence = 'Perpetual' and date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' " );
		return $query->rows;
	}
	public function getTotalResidentstatus($data) {
		$query = $this->db->query ( "SELECT COUNT(*) as total FROM " . DB_PREFIX . "createtask WHERE emp_tag_id = '" . $data ['tags_id'] . "' and recurrence = 'Perpetual' and date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' " );
		return $query->row ['total'];
	}
	public function addTagstatus($tagstatus, $status, $tags_id, $notes_id) {
		$sql = "SELECT tagstatus_id,tags_id,task_id,forms_id,parent_id,notes_id,status,is_discharge FROM `" . DB_PREFIX . "tagstatus` where tags_id = '" . $tags_id . "'";
		$this->db->query ( $sql );
		
		// if($query->row == null && $query->row == ""){
		
		foreach ( $tagstatus as $tstatus ) {
			
			$sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $tstatus ['task_id'] . "',forms_id = '" . $tstatus ['forms_id'] . "',notes_id = '" . $tstatus ['notes_id'] . "',  status = '" . $status . "', tags_id = '" . $tags_id . "', parent_id = '" . $notes_id . "' ";
			$this->db->query ( $sql );
			
			$tagstatus_id = $this->db->getLastId ();
			
			if ($tstatus ['task_id']) {
				$sql = "UPDATE `" . DB_PREFIX . "createtask` SET tagstatus_id = '" . $tagstatus_id . "' WHERE id = '" . $tstatus ['task_id'] . "'";
				$query = $this->db->query ( $sql );
			}
			
			if ($tstatus ['forms_id']) {
				$sql = "UPDATE `" . DB_PREFIX . "forms` SET tagstatus_id = '" . $tagstatus_id . "' WHERE forms_id = '" . $tstatus ['forms_id'] . "'";
				$query = $this->db->query ( $sql );
			}
			
			if ($tstatus ['notes_id']) {
				$sql = "UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '" . $tagstatus_id . "', notes_conut ='0' WHERE notes_id = '" . $tstatus ['notes_id'] . "'";
				$query = $this->db->query ( $sql );
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$datan = array ();
		
		$datan ['tagstatus'] = $tagstatus;
		$datan ['status'] = $status;
		$datan ['tags_id'] = $tags_id;
		$datan ['notes_id'] = $notes_id;
		$this->model_activity_activity->addActivitySave ( 'addTagstatus', $datan, 'query' );
		
		return $tagstatus_id;
	}
	public function addTagstatus2($status, $tags_id, $notes_id) {
		$sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $tstatus ['task_id'] . "',forms_id = '" . $tstatus ['forms_id'] . "',notes_id = '" . $tstatus ['notes_id'] . "',  status = '" . $status . "', tags_id = '" . $tags_id . "', parent_id = '" . $notes_id . "' ";
		$this->db->query ( $sql );
		
		$tagstatus_id = $this->db->getLastId ();
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '" . $tagstatus_id . "', notes_conut ='0' WHERE notes_id = '" . $tstatus ['notes_id'] . "'";
		$query = $this->db->query ( $sql );
		
		$datan = array ();
		$datan ['status'] = $status;
		$datan ['tags_id'] = $tags_id;
		$datan ['notes_id'] = $notes_id;
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'addTagstatus2', $datan, 'query' );
		
		return $tagstatus_id;
	}
	public function getTagstatusbyId($tags_id) {
		$sql = "SELECT tagstatus_id,tags_id,task_id,forms_id,parent_id,notes_id,status,is_discharge FROM `" . DB_PREFIX . "tagstatus` where tags_id = '" . $tags_id . "' and is_discharge = '0' order by tagstatus_id DESC limit 0, 1 ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	
	public function addassignteam($data2, $data) {
		$this->load->model ( 'user/user' );
		// var_dump($data['userids']);
		
		// die;
		
		// $user_roles = implode(',',$data['user_roles']);
		// $userids = implode(',',$data['userids']);
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $data2 ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $data2 ['facilities_id'];
			}
		} else {
			$facilities_id = $data2 ['facilities_id'];
		}
		
		$query = $this->db->query ( "SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and facilities_id = '" . $facilities_id . "' " );
		
		if ($query->num_rows > 0) {
			foreach ( $query->rows as $mrow ) {
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_assign_team` SET tags_assign_team_id = '" . $this->db->escape ( $mrow ['tags_assign_team_id'] ) . "', tags_id = '" . $this->db->escape ( $mrow ['tags_id'] ) . "', emp_tag_id = '" . $this->db->escape ( $mrow ['emp_tag_id'] ) . "', facilities_id = '" . $this->db->escape ( $mrow ['facilities_id'] ) . "',user_roles = '" . $this->db->escape ( $mrow ['user_roles'] ) . "', userids = '" . $this->db->escape ( $mrow ['userids'] ) . "', date_added = '" . $this->db->escape ( $mrow ['date_added'] ) . "' , status = '" . $mrow ['status'] . "' , is_case = '" . $mrow ['is_case'] . "', is_archive = '1', notes_id = '" . $data ['notes_id'] . "' " );
				$archive_tags_assign_team_id = $this->db->getLastId ();
			}
		}
		
		$query11 = $this->db->query ( "SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and facilities_id = '" . $facilities_id . "' " );
		
		if ($query11->num_rows == 0) {
			
			// var_dump($query11->num_rows);
			
			foreach ( $data ['userids'] as $userids ) {
				
				$user_info = $this->model_user_user->getUserbyupdate ( $userids );
				
				$sqla = "INSERT INTO " . DB_PREFIX . "archive_tags_assign_team SET tags_id = '" . $this->db->escape ( $data2 ['tags_id'] ) . "',facilities_id = '" . $this->db->escape ( $facilities_id ) . "',user_roles = '" . $this->db->escape ( $user_info ['user_group_id'] ) . "', userids = '" . $this->db->escape ( $userids ) . "', date_added = '" . $this->db->escape ( $data2 ['date_added'] ) . "' , status = '1' , is_case = '1', is_archive = '1', notes_id = '" . $data ['notes_id'] . "' ";
				
				$this->db->query ( $sqla );
			}
		}
		
		// die;
		
		$queryu = $this->db->query ( "DELETE FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and facilities_id = '" . $facilities_id . "' " );
		
		foreach ( $data ['userids'] as $userids ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $userids );
			
			$sql = "INSERT INTO " . DB_PREFIX . "tags_assign_team SET tags_id = '" . $this->db->escape ( $data2 ['tags_id'] ) . "',facilities_id = '" . $this->db->escape ( $facilities_id ) . "',user_roles = '" . $this->db->escape ( $user_info ['user_group_id'] ) . "', userids = '" . $this->db->escape ( $userids ) . "', date_added = '" . $this->db->escape ( $data2 ['date_added'] ) . "' , status = '1' , is_case = '1' ";
			
			$this->db->query ( $sql );
			$tags_assign_team_id = $this->db->getLastId ();
		}
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '" . $data2 ['date_added'] . "' WHERE tags_id = '" . $this->db->escape ( $data2 ['tags_id'] ) . "'" );
		
		$datan = array ();
		$datan ['data2'] = $data2;
		$datan ['data'] = $data;
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'addassignteam', $datan, 'query' );
	}
	public function getassignteam($data2) {
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $data2 ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $data2 ['facilities_id'];
			}
		} else {
			$facilities_id = $data2 ['facilities_id'];
		}
		
		if ($data2 ['is_archive'] == '2') {
			$query = $this->db->query ( "SELECT archive_tags_assign_team_id,tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case,is_archive,notes_id FROM `" . DB_PREFIX . "archive_tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and notes_id = '" . $data2 ['notes_id'] . "' and facilities_id = '" . $facilities_id . "' group by user_roles " );
		} else {
			$sql = "SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and facilities_id = '" . $facilities_id . "' group by user_roles ";
			$query = $this->db->query ( $sql );
		}
		
		return $query->rows;
	}
	public function getassignteamUsers($data2) {
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $data2 ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $data2 ['facilities_id'];
			}
		} else {
			$facilities_id = $data2 ['facilities_id'];
		}
		
		if ($data2 ['is_archive'] == '2') {
			$query = $this->db->query ( "SELECT archive_tags_assign_team_id,tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case,is_archive,notes_id FROM `" . DB_PREFIX . "archive_tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and notes_id = '" . $data2 ['notes_id'] . "' and facilities_id = '" . $facilities_id . "' and user_roles = '" . $data2 ['user_roles'] . "' " );
		} else {
			$sql = "SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2 ['tags_id'] . "' and facilities_id = '" . $facilities_id . "' and user_roles = '" . $data2 ['user_roles'] . "' ";
			$query = $this->db->query ( $sql );
		}
		
		
		
		return $query->rows;
	}
	public function tagsassign($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		$tag_info = $this->model_setting_tags->getTag ( $fdata ['tags_id'] );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		if ($tag_info ['emp_first_name']) {
			// $emp_tag_id = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
			$this->load->model ( 'setting/locations' );
			$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
			
			$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
		} else {
			$emp_tag_id = $tag_info ['emp_tag_id'];
		}
		
		if ($tag_info) {
			$medication_tags .= $emp_tag_id . ' ';
		}
		
		$description = '';
		
		$description .= ' Team Assignment Updated. | ';
		$description .= ' ' . $medication_tags;
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$description .= ' | ' . $this->db->escape ( $pdata ['comments'] );
		}
		
		$data ['notes_description'] = $description;
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		$this->model_notes_notes->updatetagsassign1 ( $fdata ['tags_id'] );
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		$data2 = array ();
		$data2 ['tags_id'] = $fdata ['tags_id'];
		$data2 ['date_added'] = $date_added;
		$data2 ['facilities_id'] = $facilities_id;
		
		$data3 = array ();
		$data3 ['user_roles'] = explode ( ',', $fdata ['user_roles'] );
		$data3 ['userids'] = explode ( ',', $fdata ['userids'] );
		$data3 ['notes_id'] = $notes_id;
		
		$this->model_resident_resident->addassignteam ( $data2, $data3 );
		
		$this->model_notes_notes->updatetagsassign23 ( $fdata ['tags_id'], $notes_id );
		
		return $notes_id;
	}
	public function dailycensus($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		
		$data ['notetime'] = $notetime;
		$data ['note_date'] = $date_added;
		$data ['facilitytimezone'] = $timezone_name;
		
		$data ['notes_description'] = 'Daily Census has been added';
		
		$data ['date_added'] = $date_added;
		
		$notes_id = $this->model_notes_notes->addnotes ( $data, $facilities_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		$this->load->model ( 'setting/tags' );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$this->model_setting_tags->addCensus ( $pdata, $notes_id, $date_added, $facilities_id, $timezone_name );
		
		return $notes_id;
	}
	public function clientfile($pdata, $fdata) {
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
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
		
		$data ['notetime'] = $notetime;
		$data ['note_date'] = $date_added;
		$data ['facilitytimezone'] = $timezone_name;
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$comments = ' | ' . $pdata ['comments'];
		}
		
		$data ['notes_description'] = ' New File upload  ' . $comments;
		$data ['date_added'] = $date_added;
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$formData = array ();
		$formData ['media_user_id'] = $pdata ['user_id'];
		if ($pdata ['imgOutput']) {
			$formData ['media_signature'] = $pdata ['imgOutput'];
		} else {
			$formData ['media_signature'] = $pdata ['signature'];
		}
		$formData ['media_pin'] = $pdata ['notes_pin'];
		$formData ['facilities_id'] = $facilities_id;
		
		$formData ['noteDate'] = $date_added;
		
		$this->model_notes_notes->updateNoteFile ( $notes_id, $fdata ['notes_file'], $fdata ['extention'], $formData );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '" . $date_added . "' WHERE tags_id = '" . $this->db->escape ( $fdata ['tags_id'] ) . "'" );
		
		if ($pdata ['emp_tag_id'] != null && $pdata ['emp_tag_id'] != "") {
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			$tadata = array ();
			$this->model_notes_notes->updateNotesTag ( $pdata ['emp_tag_id'], $notes_id, $pdata ['tags_id'], $update_date, $tadata );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		return $notes_id;
	}
	public function addformmedicine($mediactiondata, $tags_id) {
		$query2 = $this->db->query ( "SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
		
		if ($query2->num_rows > 0) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "', status = '1' where tags_id = '" . $tags_id . "' and is_discharge = '0' " );
			
			$tags_medication_id = $query2->row ['tags_medication_id'];
		} else {
			
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "' , status = '1', tags_id = '" . $tags_id . "'" );
			
			$tags_medication_id = $this->db->getLastId ();
		}
		
		$query1 = $this->db->query ( "SELECT tags_medication_details_id FROM `" . DB_PREFIX . "tags_medication_details` WHERE drug_name = '" . $mediactiondata ['drug_name'] . "' and tags_id = '" . $tags_id . "' " );
		
		if ($query1->num_rows > 0) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication_details` SET drug_mg = '" . $this->db->escape ( $mediactiondata ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $drug_am ) . "', drug_pm = '" . $this->db->escape ( $drug_pm ) . "', drug_alertnate = '" . $this->db->escape ( $mediactiondata ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $mediactiondata ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $mediactiondata ['instructions'] ) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape ( $mediactiondata ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $mediactiondata ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape ( $recurnce_day ) . "', recurnce_month = '" . $this->db->escape ( $recurnce_month ) . "', recurnce_week = '" . $this->db->escape ( $recurnce_week ) . "', recurnce_hrly_recurnce = '" . $mediactiondata ['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata ['is_schedule_medication'] . "', is_updated = '" . $mediactiondata ['is_updated'] . "', tags_medication_details_ids = '" . $tags_medication_details_ids . "' where tags_medication_details_id = '" . $query1->row ['tags_medication_details_id'] . "' " );
			
			$tags_medication_details_id = $query1->row ['tags_medication_details_id'];
		} else {
			
			$sql = "INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape ( $mediactiondata ['drug_name'] ) . "', drug_mg = '" . $this->db->escape ( $mediactiondata ['drug_mg'] ) . "', drug_am = '" . $this->db->escape ( $drug_am ) . "', drug_pm = '" . $this->db->escape ( $drug_pm ) . "', drug_alertnate = '" . $this->db->escape ( $mediactiondata ['drug_alertnate'] ) . "', drug_prn = '" . $this->db->escape ( $mediactiondata ['drug_prn'] ) . "', instructions = '" . $this->db->escape ( $mediactiondata ['instructions'] ) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape ( $mediactiondata ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $mediactiondata ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape ( $recurnce_day ) . "', recurnce_month = '" . $this->db->escape ( $recurnce_month ) . "', recurnce_week = '" . $this->db->escape ( $recurnce_week ) . "', recurnce_hrly_recurnce = '" . $mediactiondata ['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata ['is_schedule_medication'] . "', is_updated = '" . $mediactiondata ['is_updated'] . "', tags_medication_details_ids = '" . $tags_medication_details_ids . "' ";
			$this->db->query ( $sql );
			$tags_medication_details_id = $this->db->getLastId ();
		}
	}
	public function get_medicationyname22($emp_tag_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "createtask WHERE medication_tags = '" . $emp_tag_id . "' " );
		return $query->rows;
	}
	public function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
		$temp = array ();
		foreach ( $arr as $key => $value ) {
			$groupValue = $value [$group];
			if (! $preserveGroupKey) {
				unset ( $arr [$key] [$group] );
			}
			if (! array_key_exists ( $groupValue, $temp )) {
				$temp [$groupValue] = array ();
			}
			
			if (! $preserveSubArrays) {
				$data = count ( $arr [$key] ) == 1 ? array_pop ( $arr [$key] ) : $arr [$key];
			} else {
				$data = $arr [$key];
			}
			$temp [$groupValue] [] = $data;
		}
		return $temp;
	}
	public function getClientStatus($data = array()) {
		$sql = "SELECT tag_status_id,name,display_client,facilities_id, customer_key,date_added,date_updated,status,image,type,color_code,parent_ids,rule_action_content FROM " . DB_PREFIX . "tag_status";
		
		$sql .= " where 1 = 1 and status = '1'";
		
		if ($data ['out_from_cell'] == "1") {
			$sql .= " and out_from_cell = '1'";
		}
		
		if ($data ['out_from_cell'] == "2") {
			$sql .= " and out_from_cell = '0'";
		}
		
		if ($data ['role_call'] != null && $data ['role_call'] != "") {
			$sql .= " and tag_status_id = '" . $data ['role_call'] . "'";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities_id)";
		}
		
		if ($data ['sort']) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY name";
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
	public function getClientClassification($data = array()) {
		$sql = "SELECT tag_classification_id,classification_name,customer_key,status,color_code FROM " . DB_PREFIX . "tag_classification";
		
		$sql .= " where 1 = 1 and status = '1'";
		
		$sql .= "and customer_key = '" . $data ['customer_key'] . "'";
		
		if ($data ['sort']) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY classification_name";
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
	}
	public function updateclientStatus($data) {
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET role_call = '" . ( int ) $data ['tag_status_id'] . "',facility_move_id = '" . ( int ) $data ['facility_move_id'] . "', modify_date = '" . $data ['modify_date'] . "' , tag_status_ids = '" . $this->db->escape ($data ['substatus_ids']) . "',comments='".$this->db->escape ($data['comments'])."',fixed_status_id = '" . (int)$data ['fixed_status_id'] . "'  where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
		
		$this->db->query ( $sql );
	}
	public function updateclientStatuses($data) {
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET role_call = '" . ( int ) $data ['tag_status_id'] . "',facility_move_id = '" . ( int ) $data ['facility_move_id'] . "', modify_date = '" . $data ['modify_date'] . "'  where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
		
		$this->db->query ( $sql );
	}
	public function getClientStatusById($tag_status_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tag_status` WHERE tag_status_id = '" . $tag_status_id . "'";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getClassificationValue($classification_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tag_classification` WHERE tag_classification_id = '" . $classification_id . "'";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function updateclientnotes($data) {
		if ($data ['update_client'] == 1) {
			$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_move_id = '" . $data ['facility_move_id'] . "', modify_date = '" . $data ['modify_date'] . "' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
			$this->db->query ( $sql );
		} else {
			// $sql = "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '" . $data ['notes_id'] . "',facility_move_id = '" . $data ['facility_move_id'] . "', modify_date = '" . $data ['modify_date'] . "' where tags_id = '" . ( int ) $data ['tags_id'] . "'";
			// $this->db->query ( $sql );
			
			$sql = "UPDATE `" . DB_PREFIX . "tags` SET facility_move_id = '" . $data ['facility_move_id'] . "', modify_date = '" . $data ['modify_date'] . "' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
			$this->db->query ( $sql );
			
			$this->load->model ( 'notes/clientstatus' );
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $data ['tag_status_id'] );
			
			if ($clientstatus_info ['type'] == 3 || $clientstatus_info ['type'] == 4) {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '" . $data ['notes_id'] . "' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
				$this->db->query ( $sql );
			} else {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
				$this->db->query ( $sql );
			}
		}
	}
	public function addtracktime($data) {
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_tracktime` SET notes_id = '" . $this->db->escape ( $data ['notes_id'] ) . "', facilities_id = '" . $this->db->escape ( $data ['facilities_id'] ) . "', unique_id = '" . $this->db->escape ( $data ['unique_id'] ) . "', tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "', tag_status_id='" . $this->db->escape ( $data ['tag_status_id'] ) . "', keyword_id = '" . $this->db->escape ( $data ['keyword_id'] ) . "', types = '" . $this->db->escape ( $data ['types'] ) . "', years = '" . $this->db->escape ( $data ['years'] ) . "', months = '" . $this->db->escape ( $data ['months'] ) . "', days = '" . $this->db->escape ( $data ['days'] ) . "', hours = '" . $this->db->escape ( $data ['hours'] ) . "', minutes = '" . $this->db->escape ( $data ['minutes'] ) . "', date_added = '" . $data ['date_added'] . "', new_tag_status_id = '" . $data ['new_tag_status_id'] . "',comments = '" . $this->db->escape ($data ['comments']) . "',fixed_status_id = '" . (int)$data ['fixed_status_id'] . "',move_notes_id = '" . (int)$data ['move_notes_id'] . "' ";
		// echo $sql; die;
		$this->db->query ( $sql );
		// $notes_id = $this->db->getLastId();
	}
	public function getMedicationInfo($data) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "archive_tags_medication_details WHERE tags_id = '" . $data ['tags_id'] . "' AND tags_medication_details_id = '" . $data ['tags_medication_details_id'] . "'" );
		return $query->row;
	}
	public function updateMedication($tags_id, $tags_medication_details_id, $temdata = array(), $facilities_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication` SET drug_name = '" . $this->db->escape ( serialize ( $temdata ['drug_name'] ) ) . "', drug_prn = '" . $this->db->escape ( $temdata ['drug_prn'] ) . "', drug_pm = '" . $this->db->escape ( $temdata ['drug_pm'] ) . "', drug_mg = '" . $this->db->escape ( $temdata ['drug_mg'] ) . "', instructions = '" . $this->db->escape ( $temdata ['instructions'] ) . "',status = '1' where tags_id = '" . $tags_id . "' AND tags_medication_details_id = '" . $tags_medication_details_id . "' AND facilities_id = '" . $facilities_id . "' " );
	}
	public function createMails($data) {
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Refill Medication Email</title> 

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
                        <td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Refill Medication Email</h6></td>
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
                            
                            <h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
		
		$html .= $data ['username'];
		
		$html .= '!</h1>
                            <p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
                            
                        </td>
                    </tr>
                </table>
            </div>';
		
		/* if($emailData['what_check']=="1"){ */
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
                
                <table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
                    <tr>
                        <td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $data ['href'] . '">
                        <img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
                        <td>
                            <h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Medication is going to empty</small></h4>';
		
		$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                                Please refill the medication of ' . $data ['username'] . '
                                </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                            ' . $emailData ['what_message'] . '
                            </p>
                        </td>
                        
                    </tr>
                </table>
            
            </div>';
		/*
		 * }
		 *
		 * if($emailData['when_check']=="1"){
		 */
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
            <table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
                <tr>
                    <td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $data ['href'] . '">
                    <img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
                    <td>
                        <h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
                        <p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                        ' . date ( "l jS \of F Y h:i A" ) . '</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                            ' . $data ['when_message'] . '
                            </p>
                    </td>
                    
                </tr>
            </table></div>';
		/*
		 * }
		 *
		 *
		 *
		 * if($emailData['who_check']=="1"){
		 */
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
            <table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
                <tr>
                    <td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
                    <img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
                    <td>
                        <h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
                        <p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
		$html .= $data ['username'];
		$html .= '</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                            ' . $emailData ['who_message'] . '
                            </p>
                    </td>

                    
                    <td></td>
                </tr>
            </table></div>';
		
		/* } if($emailData['where_check']=="1"){ */
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
                
                <table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
                    <tr>
                        <td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $data ['href'] . '">
                        <img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
                        <td>
                            <h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">' . $data ['facility'] . '</small></h4>
                            <p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                            ' . $emailData ['where_message'] . '
                            </p>
                        </td>
                    </tr>
                </table>
            
            </div>';
		/* } */
		
		$html .= '<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
    <tr>
        <td></td>

        
        <td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
            

            <div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
                <table style="width: 100%;">
                    <tr>
                        
                        <td align="left" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Thank You !</h6></td>
                    </tr>
                </table>
            </div>
            
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>';
		
		$this->load->model ( 'api/emailapi' );
		
		if ($data ['user_email'] != null) {
			
			$emailData = array ();
			$emailData ['facility'] = $data ['facility'];
			$emailData ['username'] = $data ['username'];
			$emailData ['message'] = $html;
			$emailData ['subject'] = "Medication Refill Email";
			$emailData ['user_email'] = $data ['user_email'];
			$emailData ['type'] = '26';
			$email_status = $this->model_api_emailapi->sendmail ( $emailData );
		} else {
			
			$emailData = array ();
			$emailData ['facility'] = $data ['facility'];
			$emailData ['username'] = $data ['username'];
			$emailData ['message'] = $html;
			$emailData ['subject'] = "Medication Refill Email";
			$emailData ['useremailids'] = $data ['useremailids'];
			$emailData ['type'] = '26';
			$email_status = $this->model_api_emailapi->sendmail ( $emailData );
		}
	}
	public function addrolecallreport($data) {
		$this->load->model ( 'notes/clientstatus' );
		$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $data ['tag_status_id'] );
		
		if ($clientstatus_info ['type'] == '2') {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET generate_report = '6' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "'" );
		}
	}
	public function updatetagrolecall2($tags_id, $data2) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->load->model ( 'facilities/facilities' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET facility_inout = '" . $data2 ['facility_inout'] . "', modify_date = '" . $date_added . "' where  tags_id = '" . $tags_id . "'";
		
		$sql = $this->db->query ( $sql1 );
		
		if ($data2 ['substatus_ids'] != null && $data2 ['substatus_ids'] != "") {
			$sql12 = "UPDATE `" . DB_PREFIX . "tags` SET tag_status_ids = '" . $this->db->escape ( $data2 ['substatus_ids'] ) . "', modify_date = '" . $date_added . "' where  tags_id = '" . $tags_id . "'";
			$sql = $this->db->query ( $sql12 );
		}
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['facility_inout'] = $data2 ['facility_inout'];
		$this->model_activity_activity->addActivitySave ( 'updatetagrolecall2', $data, 'query' );
	}
	public function getClientStatusByFacilityId($facility_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tag_status` WHERE facility_type = '" . $facility_id . "'";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function updateclientmovement($data = array()) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );
		
		if ($data ['movement_room'] != null && $data ['movement_room'] != "") {
			if ($data ['movement_room'] != $tag_info ['movement_room']) {
				$sql2m = "UPDATE `" . DB_PREFIX . "tags` SET manual_movement = '1' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
				$this->db->query ( $sql2m );
			}
		}
		if ($data ['movement_room'] == $tag_info ['movement_room']) {
			$sql2m = "UPDATE `" . DB_PREFIX . "tags` SET manual_movement = '0' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
			$this->db->query ( $sql2m );
		}
		
		$sql2 = "UPDATE `" . DB_PREFIX . "tags` SET old_facilities_id = '" . ( int ) $tag_info ['facilities_id'] . "' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
		$this->db->query ( $sql2 );
		
		if ($data ['facility_move_id'] != "0") {
			if ($data ['facility_move_id'] != "" && $data ['facility_move_id'] != null) {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET facilities_id = '" . ( int ) $data ['facility_move_id'] . "' where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
				$this->db->query ( $sql );
			}
		}
		
		if ($data ['movement_room'] != "0") {
			if ($data ['movement_room'] != "" && $data ['movement_room'] != null) {
				$sql = "UPDATE `" . DB_PREFIX . "tags` SET room = '" . ( int ) $data ['movement_room'] . "'  where  tags_id = '" . ( int ) $data ['tags_id'] . "'";
				$this->db->query ( $sql );
			}
		}
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '" . $data ['modify_date'] . "', is_movement='0', movement_room='0', facility_move_id='0'  where tags_id = '" . ( int ) $data ['tags_id'] . "'";
		$this->db->query ( $sql );
	}
	
	public function updateformstatus($insdata = array()){
		
		
		$tag_info = $this->model_setting_tags->getTag ( $insdata ['tags_id'] );
		
		$this->load->model ( 'notes/clientstatus' );
		$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag_info ['role_call'] );
		$roleCall = $clientstatus_info ['name'];
		
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ( $insdata ['tag_status_id'] );
		
		$rule_action_content = unserialize ( $client_statuses_value ['rule_action_content'] );
		
		
		$tagstatuscomment = ' status changed ' . $roleCall . ' to | ' . $client_statuses_value ['name'];
		
		
		if($rule_action_content['exclude_in_inmate_status'] != null && $rule_action_content['exclude_in_inmate_status'] != ""){
			$tag_status_id = $rule_action_content['exclude_in_inmate_status'];
				
			$fixed_status_id = $insdata ['tag_status_id'];
		}else{
			$tag_status_id = $insdata ['tag_status_id'];
				
			$fixed_status_id = 0;
		}
		
		
		$cdata = array ();
		$cdata ['tag_status_id'] = $tag_status_id;
		$cdata ['fixed_status_id'] = $fixed_status_id;
		$cdata ['substatus_ids'] = '';
		$cdata ['comments'] = '';
		$cdata ['tags_id'] = $insdata ['tags_id'];
		$cdata ['facilities_id'] = $insdata ['facilities_id'];
		$cdata ['modify_date'] = $insdata ['tag_status_id'];
		$cdata ['facility_move_id'] = 0;
		$this->model_resident_resident->updateclientStatus ( $cdata );
		
		return $tagstatuscomment;
	}
	
	
	public function updateclientStatusnotes($data = array()){
		$sql = "UPDATE `" . DB_PREFIX . "notes_tags` SET move_notes_id = '" . $data ['move_notes_id'] . "', status_total_time = '" . $data ['status_total_time'] . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' and notes_id = '" . ( int ) $data ['notes_id'] . "'";
		$this->db->query ( $sql );
	}

	public function gettagsbyassigneduser($data = array()){
		
		
		$sql ="SELECT * FROM `dg_tags_assign_team` WHERE userids = '" . $data['user_id'] . "' and facilities_id = '" . $data['facilities_id'] . "'  ORDER BY `dg_tags_assign_team`.`userids` ASC ";
		$query = $this->db->query ( $sql );
		return $query->rows;
		
		
		/*
		$sql = "CALL
          case_inmate_gettotalinmate ('" . $this->db->escape ( $data ['activecustomer_id'] ) . "',
									  '" . $this->db->escape ( $data ['user_id'] ) ."')";
		
		$query = $this->db->query ( $sql );
		return $query->row;
		*/
	}
}

