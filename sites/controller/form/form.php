<?php
class Controllerformform extends Controller {
	private $error = array ();
	public function index() {
		unset ( $this->session->data ['show_hidden_info'] );
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['url_load'] = $this->getChild('notes/notes/getNoteData', $url2);
		
		// $this->data['notes_id'] = $this->request->get['notes_id'];
		if ($this->request->get ['newnotes'] != '1') {
			$this->data ['updatenotes_id'] = $notes_id;
		}
		
		$this->getForm ();
	}
	public function printform() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/clientstatus' );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'facilities/facilities' );
		
		// $this->data['facility_name'] = $this->customer->getfacility();
		if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
			
			$results = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
			
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			/*
			 * $notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			 *
			 * if ($notes_info) {
			 *
			 * $data = array ();
			 * $data ['facilities_id'] = $notes_info ['facilities_id'];
			 * $data ['notetime'] = $notes_info ['notetime'];
			 *
			 * $get_shift_time_notes = $this->model_notes_notes->getMovementNoteData ( $data );
			 *
			 * if ($get_shift_time_notes) {
			 *
			 * foreach ( $get_shift_time_notes as $times_notes ) {
			 *
			 * $this->load->model ( 'setting/highlighter' );
			 * $this->load->model ( 'user/user' );
			 * $this->load->model ( 'facilities/facilities' );
			 *
			 * $this->load->model ( 'setting/keywords' );
			 * $this->load->model ( 'setting/tags' );
			 *
			 * $this->load->model ( 'notes/clientstatus' );
			 * $this->load->model ( 'setting/image' );
			 *
			 * $notes_data = array ();
			 * $notes_data ['facilities_id'] = $times_notes ['facilities_id'];
			 * $notes_data ['notes_id'] = $times_notes ['notes_id'];
			 *
			 * $alltag = $this->model_notes_notes->getNotesTags ( $times_notes ['notes_id'] );
			 *
			 * $emp_tag_id22 = "";
			 * $roleCall = "";
			 *
			 * $shift_name = "";
			 *
			 * $date_added = date ( 'm-d-Y', strtotime ( $times_notes ['date_added'] ) );
			 * $notetime2 = date ( 'h:i A', strtotime ( $times_notes ['date_added'] ) );
			 *
			 * if ($times_notes ['shift_id'] > 0) {
			 * $shift_info = $this->model_notes_notes->getshift ( $times_notes ['shift_id'] );
			 * $shift_name = $shift_info ['shift_name'];
			 * }
			 *
			 * if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
			 * $this->load->model ( 'api/permision' );
			 * $tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
			 * $clientinfo = $this->model_api_permision->getclientinfo ( $times_notes ['facilities_id'], $tag_info );
			 * $emp_tag_id22 = $clientinfo ['name'];
			 *
			 * $clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $alltag ['tag_status_id'] );
			 *
			 * if ($clientstatus_info ['type'] == '2') {
			 * $roleCall = $clientstatus_info ['name'];
			 * if ($times_notes ['notes_pin'] != null && $times_notes ['notes_pin'] != "") {
			 * $outnotes_pin = $times_notes ['notes_pin'];
			 * } else {
			 * $outnotes_pin = '';
			 * }
			 *
			 * $outuser_id = $times_notes ['user_id'];
			 * $outsignature = $times_notes ['signature'];
			 * $outnotes_type = $times_notes ['notes_type'];
			 *
			 * $outnotetime = date ( 'h:i A', strtotime ( $times_notes ['notetime'] ) );
			 *
			 * }
			 *
			 * if ($alltag ['move_notes_id'] > 0) {
			 * $result1 = $this->model_notes_notes->getnotes ( $alltag ['move_notes_id'] );
			 * $alltag1 = $this->model_notes_notes->getNotesTags ( $result1 ['notes_id'] );
			 *
			 * if ($alltag1 ['emp_tag_id'] != null && $alltag1 ['emp_tag_id'] != "") {
			 * $clientstatus_info1 = $this->model_notes_clientstatus->getclientstatus ( $alltag1 ['tag_status_id'] );
			 * if ($clientstatus_info1 ['type'] == '3') {
			 * $roleCall = $clientstatus_info1 ['name'];
			 * if ($result1 ['notes_pin'] != null && $result1 ['notes_pin'] != "") {
			 * $notes_pin = $result1 ['notes_pin'];
			 * } else {
			 * $notes_pin = '';
			 * }
			 *
			 * $user_id = $result1 ['user_id'];
			 * $signature = $result1 ['signature'];
			 * $notes_type = $result1 ['notes_type'];
			 *
			 * $notetime = date ( 'h:i A', strtotime ( $result1 ['notetime'] ) );
			 *
			 * $description1 = "";
			 * if ($result1 ['customlistvalues_id'] != null && $result1 ['customlistvalues_id'] != "") {
			 * $ids = explode ( ",", $result1 ['customlistvalues_id'] );
			 * foreach ( $ids as $customlistvalues_id ) {
			 * $custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
			 * $description1 .= $custom_info ['customlistvalues_name'] . ' | ';
			 * }
			 * }
			 * }
			 * }
			 * }
			 * }
			 *
			 * // $tag_data=$this->model_notes_notes->getNoteTagData($notes_data);
			 *
			 * $time_notes [] = array (
			 * 'emp_tag_id22' => $emp_tag_id22,
			 * 'roleCall' => $roleCall,
			 * 'description1' => $description1,
			 * 'notetime' => $notetime,
			 * 'outnotetime' => $outnotetime,
			 * 'user_id' => $user_id,
			 * 'notes_type' => $notes_type,
			 * 'notes_pin' => $notes_pin,
			 * 'signature' => $signature,
			 * 'outuser_id' => $outuser_id,
			 * 'outnotes_type' => $outnotes_type,
			 * 'outnotes_pin' => $outnotes_pin,
			 * 'outsignature' => $outsignature
			 * );
			 * }
			 *
			 * // var_dump($this->data ['time_notes']);die;
			 * }
			 * }
			 */
			
			if (! empty ( $results ['design_forms'] )) {
				$formdatas = unserialize ( $results ['design_forms'] );
			}
			
			$this->data ['upload_file'] = $results ['upload_file'];
			$this->data ['form_signature'] = $results ['form_signature'];
			$formmedias = $this->model_form_form->getFormmedia ( $this->request->get ['forms_id'] );
			
			$this->data ['formsimages'] = array ();
			$this->data ['formssigns'] = array ();
			
			/*
			 * foreach ( $formmedias as $formmedia ) {
			 * if ($formmedia ['media_type'] == '1') {
			 * $formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
			 * }
			 *
			 * if ($formmedia ['media_type'] == '2') {
			 * $formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
			 * }
			 * }
			 */
			
			foreach ( $formmedias as $formmedia ) {
				$media_name [] = $formmedia ['media_name'];
			}
			$pnotes = array ();
			
			$dups = array ();
			foreach ( array_count_values ( $media_name ) as $val => $c )
				if ($c > 1)
					$dups [] = $val;
			
			foreach ( $formmedias as $formmedia ) {
				if ($formmedia ['media_type'] == '1') {
					if (in_array ( $formmedia ['media_name'], $dups )) {
						$formdatas [$formmedia ['media_name']] [] = $formmedia ['media_url'];
					} else {
						$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
					}
				}
				
				if ($formmedia ['media_type'] == '2') {
					
					if (in_array ( $formmedia ['media_name'], $dups )) {
						$formdatas [$formmedia ['media_name']] [] = $formmedia ['media_url'];
					} else {
						$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
					}
				}
			}
			
			$pnotes = array ();
		}
		
		$result_parents = $this->model_form_form->getFormDatasparents ( $results ['forms_id'] );
		
		$formparents = array ();
		
		foreach ( $result_parents as $result_parent ) {
			if (! empty ( $result_parent ['design_forms'] )) {
				$formparents [] = unserialize ( $result_parent ['design_forms'] );
			}
			$formmediass = $this->model_form_form->getFormmedia ( $result_parent ['forms_id'] );
			
			foreach ( $formmediass as $formmedia1 ) {
				if ($formmedia1 ['media_type'] == '1') {
					$formparents [$formmedia1 ['media_name']] = $formmedia1 ['media_url'];
				}
				
				if ($formmedia1 ['media_type'] == '2') {
					$formparents [$formmedia1 ['media_name']] = $formmedia1 ['media_url'];
				}
			}
		}
		
		$this->load->model ( 'api/permision' );
		$timeinfo = $this->model_api_permision->getcustomerdatetime ( $this->customer->getId () );
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		if ($results ['parent_id'] > 0) {
			
			$this->load->model ( 'notes/notes' );
			
			if ($results ['parent_id'] != null && $results ['parent_id'] != "") {
				$this->load->model ( 'setting/tags' );
				$notes_data = $this->model_notes_notes->getnotesbyparent ( $results ['parent_id'] );
				
				$tags_data = $this->model_setting_tags->getTag ( $notes_info ['tags_id'] );
				
				$observation_name = $tags_data ['emp_first_name'] . " " . $tags_data ['emp_last_name'];
				$observation_ssn = $tags_data ['ssn'];
				$observation_date = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
				$observation_userid = $notes_info ['user_id'];
				
				foreach ( $notes_data as $nnote ) {
					
					$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
					
					$emp_tag_id = "";
					
					if ($nnote ['emp_tag_id'] == '1') {
						$alltags = $this->model_setting_tags->getTagsbyNotesID2 ( $nnote ['notes_id'] );
						foreach ( $alltags as $alltag ) {
							$emp_tag_id = "";
							$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
							$emp_tag_id .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
						}
					}
					
					if ($emp_tag_id != null && $emp_tag_id != "") {
						$emp_tag_id = $emp_tag_id;
					} else {
						$emp_tag_id = $name;
					}
					
					$keyImageSrc11 = "";
					if ($nnote ['keyword_file'] == '1') {
						$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
						foreach ( $allkeywords as $keyword ) {
							$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
						}
					}
					
					if ($nnote ['highlighter_id'] > 0) {
						$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
					} else {
						$highlighterData = array ();
					}
					$notetime1 = "";
					
					if ($nnote ['parent_id'] > 0) {
						$note_info1 = $this->model_notes_notes->getnotes ( $nnote ['parent_id'] );
						$notetime1 = date ( 'h:i A', strtotime ( $note_info1 ['notetime'] ) );
					}
					
					$notess [] = array (
							'notes_id' => $nnote ['notes_id'],
							'emp_tag_id' => $emp_tag_id,
							'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
							'facilities_id' => $result_info ['facility'],
							'highlighter_value' => $highlighterData ['highlighter_value'],
							'text_color' => $nnote ['text_color'],
							'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
							'notetime1' => $notetime1,
							'user_id' => $nnote ['user_id'],
							'signature' => $nnote ['signature'],
							// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
							'date_added' => date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) ),
							'note_date' => date ( 'm-d-Y h:i A', strtotime ( $nnote ['date_added'] ) ) 
					);
				}
			}
			
			if ($this->request->get ['forms_design_id'] == '42') {
				$snotess = $this->model_notes_notes->getnotesbyparent2 ( $results ['parent_id'] );
				
				foreach ( $snotess as $nnote2 ) {
					
					// var_dump($nnote2['notes_id']);
					$result_info2 = $this->model_facilities_facilities->getfacilities ( $nnote2 ['facilities_id'] );
					$subnotess = $this->model_notes_notes->getnotesbyparent3 ( $results ['parent_id'], $nnote2 ['facilities_id'], $nnote2 ['notes_id'] );
					
					$subgnotesss = array ();
					foreach ( $subnotess as $nnote ) {
						
						$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
						
						$emp_tag_id = "";
						
						if ($nnote ['emp_tag_id'] == '1') {
							$alltags = $this->model_setting_tags->getTagsbyNotesID2 ( $nnote ['notes_id'] );
							foreach ( $alltags as $alltag ) {
								$emp_tag_id = "";
								$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
								$emp_tag_id .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
							}
						}
						
						if ($emp_tag_id != null && $emp_tag_id != "") {
							$emp_tag_id = $emp_tag_id;
						} else {
							$emp_tag_id = $name;
						}
						
						$keyImageSrc11 = "";
						if ($nnote ['keyword_file'] == '1') {
							$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
							foreach ( $allkeywords as $keyword ) {
								$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
							}
						}
						
						if ($nnote ['highlighter_id'] > 0) {
							$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
						} else {
							$highlighterData = array ();
						}
						$notetime1 = "";
						
						if ($nnote ['parent_id'] > 0) {
							$note_info1 = $this->model_notes_notes->getnotes ( $nnote ['parent_id'] );
							$notetime1 = date ( $timeinfo ['time_format'], strtotime ( $note_info1 ['notetime'] ) );
						}
						
						$subgnotesss [] = array (
								'notes_id' => $nnote ['notes_id'],
								'emp_tag_id' => $emp_tag_id,
								'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
								'facilities_id' => $result_info ['facility'],
								'highlighter_value' => $highlighterData ['highlighter_value'],
								'text_color' => $nnote ['text_color'],
								'notetime' => date ( $timeinfo ['time_format'], strtotime ( $nnote ['notetime'] ) ),
								'notetime1' => $notetime1,
								'user_id' => $nnote ['user_id'],
								'signature' => $nnote ['signature'],
								// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
								'date_added' => date ( 'm-d-Y', strtotime ( $nnote ['date_added'] ) ),
								'note_date' => date ( $timeinfo ['date_format'], strtotime ( $nnote ['date_added'] ) ) 
						);
					}
					
					$subgnotess [] = array (
							'notes_id' => $nnote2 ['notes_id'],
							'facilities_id' => $result_info2 ['facility'],
							'notetime' => date ( $timeinfo ['time_format'], strtotime ( $nnote2 ['notetime'] ) ),
							'date_added' => date ( 'm-d-Y', strtotime ( $nnote2 ['date_added'] ) ),
							'subgnotesss' => $subgnotesss 
					);
				}
			} else if ($this->request->get ['forms_design_id'] == '116') {
				$snotess = $this->model_notes_notes->getnotesbyparent2 ( $results ['parent_id'] );
				
				foreach ( $snotess as $nnote2 ) {
					
					// var_dump($nnote2['notes_id']);
					$result_info2 = $this->model_facilities_facilities->getfacilities ( $nnote2 ['facilities_id'] );
					$subnotess = $this->model_notes_notes->getnotesbyparent3 ( $results ['parent_id'], $nnote2 ['facilities_id'], $nnote2 ['notes_id'] );
					
					$subgnotesss = array ();
					foreach ( $subnotess as $nnote ) {
						
						$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
						
						$emp_tag_id = "";
						
						if ($nnote ['emp_tag_id'] == '1') {
							$alltags = $this->model_setting_tags->getTagsbyNotesID2 ( $nnote ['notes_id'] );
							foreach ( $alltags as $alltag ) {
								$emp_tag_id = "";
								$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
								$emp_tag_id .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
							}
						}
						
						if ($emp_tag_id != null && $emp_tag_id != "") {
							$emp_tag_id = $emp_tag_id;
						} else {
							$emp_tag_id = $name;
						}
						
						$keyImageSrc11 = "";
						if ($nnote ['keyword_file'] == '1') {
							$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
							foreach ( $allkeywords as $keyword ) {
								$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
							}
						}
						
						if ($nnote ['highlighter_id'] > 0) {
							$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
						} else {
							$highlighterData = array ();
						}
						$notetime1 = "";
						
						if ($nnote ['parent_id'] > 0) {
							$note_info1 = $this->model_notes_notes->getnotes ( $nnote ['parent_id'] );
							$notetime1 = date ( $timeinfo ['time_format'], strtotime ( $note_info1 ['notetime'] ) );
						}
						
						$subgnotesss [] = array (
								'notes_id' => $nnote ['notes_id'],
								'emp_tag_id' => $emp_tag_id,
								'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
								'facilities_id' => $result_info ['facility'],
								'highlighter_value' => $highlighterData ['highlighter_value'],
								'text_color' => $nnote ['text_color'],
								'notetime' => date ( $timeinfo ['time_format'], strtotime ( $nnote ['notetime'] ) ),
								'notetime1' => $notetime1,
								'user_id' => $nnote ['user_id'],
								'signature' => $nnote ['signature'],
								// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
								'date_added' => date ( 'm-d-Y', strtotime ( $nnote ['date_added'] ) ),
								'note_date' => date ( $timeinfo ['date_format'], strtotime ( $nnote ['date_added'] ) ) 
						);
					}
					
					$subgnotess [] = array (
							'notes_id' => $nnote2 ['notes_id'],
							'facilities_id' => $result_info2 ['facility'],
							'subgnotesss' => $subgnotesss 
					);
				}
			} else {
				/*
				 * $notes_data = $this->model_notes_notes->getnotesbyparent ( $results ['parent_id'] );
				 *
				 * foreach ( $notes_data as $nnote ) {
				 *
				 * $result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
				 *
				 * $emp_tag_id = "";
				 *
				 * if ($nnote ['emp_tag_id'] == '1') {
				 * $alltags = $this->model_setting_tags->getTagsbyNotesID2 ( $nnote ['notes_id'] );
				 * foreach ( $alltags as $alltag ) {
				 * $emp_tag_id = "";
				 * $tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
				 * $emp_tag_id .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
				 * }
				 * }
				 *
				 * if ($emp_tag_id != null && $emp_tag_id != "") {
				 * $emp_tag_id = $emp_tag_id;
				 * } else {
				 * $emp_tag_id = $name;
				 * }
				 *
				 * $keyImageSrc11 = "";
				 * if ($nnote ['keyword_file'] == '1') {
				 * $allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
				 * foreach ( $allkeywords as $keyword ) {
				 * $keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
				 * }
				 * }
				 *
				 * if ($nnote ['highlighter_id'] > 0) {
				 * $highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
				 * } else {
				 * $highlighterData = array ();
				 * }
				 * $notetime1 = "";
				 *
				 * if ($nnote ['parent_id'] > 0) {
				 * $note_info1 = $this->model_notes_notes->getnotes ( $nnote ['parent_id'] );
				 * $notetime1 = date ( 'h:i A', strtotime ( $note_info1 ['notetime'] ) );
				 * }
				 *
				 * $notess [] = array (
				 * 'notes_id' => $nnote ['notes_id'],
				 * 'emp_tag_id' => $emp_tag_id,
				 * 'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
				 * 'facilities_id' => $result_info ['facility'],
				 * 'highlighter_value' => $highlighterData ['highlighter_value'],
				 * 'text_color' => $nnote ['text_color'],
				 * 'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
				 * 'notetime1' => $notetime1,
				 * 'user_id' => $nnote ['user_id'],
				 * 'signature' => $nnote ['signature'],
				 * // 'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
				 * 'date_added' => date ( 'm-d-Y', strtotime ( $nnote ['date_added'] ) ),
				 * 'note_date' => date ( 'm-d-Y h:i A', strtotime ( $nnote ['date_added'] ) )
				 * );
				 * }
				 *
				 */
			}
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $results ['tags_id'] );
			
			// $name = $tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
			$name = $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'];
		}
		
		foreach ( $formdatas as $key1 => $vals ) {
			
			foreach ( $vals as $key2 => $v ) {
				foreach ( $v as $key3 => $v3 ) {
					$arrss = explode ( "_1_", $key3 );
					if ($arrss [1] == 'facilities_id') {
						if ($v [$arrss [0]] != null && $v [$arrss [0]] != "") {
							
							$facilities_info = $this->model_facilities_facilities->getfacilities ( $v [$arrss [0]] );
							
							$facility = $facilities_info ['facility'];
							$search_facilities_id = $facilities_info ['facilities_id'];
						}
					}
					
					if ($arrss [1] == 'tags_id') {
						
						if ($fromdatas ['client_reqired'] == '0') {
							$name = $v [$arrss [0]];
						} else {
							if ($v [$arrss [0]] != null && $v [$arrss [0]] != "") {
								if ($v [$arrss [0] . '_1_' . $arrss [1]] != null && $v [$arrss [0] . '_1_' . $arrss [1]] != "") {
									$search_emp_tag_id = $v [$arrss [0] . '_1_' . $arrss [1]];
									$this->load->model ( 'setting/tags' );
									$tag_info = $this->model_setting_tags->getTag ( $search_emp_tag_id );
									
									// $name = $tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
									$name = $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'];
								}
							}
						}
					}
					
					if ($arrss [1] == 'user_id') {
						if ($v [$arrss [0]] != null && $v [$arrss [0]] != "") {
							$search_user_id = $v [$arrss [0]];
						}
					}
				}
			}
		}
		
		if ($fromdatas ['relation_keyword_id'] > 0) {
			$search_keyword_id = $fromdatas ['relation_keyword_id'];
			
			$this->load->model ( 'setting/keywords' );
			$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $search_keyword_id );
			
			if ($keyword_info ['relation_keyword_id'] > 0) {
				$relation_search = '1';
			}
		}
		
		// var_dump($search_keyword_id);
		
		if ($search_facilities_id != null && $search_facilities_id != "") {
			$search_facilities_id1 = $search_facilities_id;
		} else {
			if ($this->request->get ['facilities_id'] != NULL && $this->request->get ['facilities_id'] != "") {
				$search_facilities_id1 = $this->request->get ['facilities_id'];
			} else {
				
				$this->load->model ( 'facilities/facilities' );
				$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				if ($resulsst ['is_master_facility'] == '1') {
					if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
						$search_facilities_id1 = $this->session->data ['search_facilities_id'];
					} else {
						$search_facilities_id1 = $this->customer->getId ();
					}
				} else {
					$search_facilities_id1 = $this->customer->getId ();
				}
			}
		}
		
		$this->load->model ( 'setting/timezone' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $search_facilities_id1 );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		
		date_default_timezone_set ( $timezone_info ['timezone_value'] );
		
		if ($results ['parent_id'] == $notes_info ['parent_id']) {
			
			$parent_id = $notes_info ['parent_id'];
		} else {
			
			$parent_id = "0";
		}
		
		if ($fromdatas ['db_table_name'] == 'notestable' && empty ( $notess )) {
			
			// var_dump($notess);die;
			
			$ffdata = array (
					'sort' => $sort,
					'order' => $order,
					// 'searchdate' => $searchdate,
					'advance_searchapp' => '1',
					'facilities_id' => $search_facilities_id1,
					'note_date_from' => date ( 'Y-m-d' ),
					'note_date_to' => date ( 'Y-m-d' ),
					'customer_key' => $this->session->data ['webcustomer_key'],
					'emp_tag_id' => $search_emp_tag_id,
					'user_id' => $search_user_id,
					'activenote' => $search_keyword_id,
					'relation_search' => $relation_search,
					'start' => 0,
					'parent_id' => $parent_id,
					'limit' => 500 
			);
			
			if ($notes_info ['task_id'] > "0" && $parent_id != "") {
				$nnotes = $this->model_notes_notes->getnotesbyparent ( $parent_id );
			} else if ($fromdatas ['relation_keyword_id'] != "0" && $fromdatas ['relation_keyword_id'] != NULL) {
				
				$report_time = date ( 'h:i A', strtotime ( $notes_info ['date_added'] ) );
				
				$report_date = date ( 'Y-m-d', strtotime ( $notes_info ['date_added'] ) );
				
				$date_added = date ( 'Y-m-d', strtotime ( $notes_info ['date_added'] ) );
				
				$notetime = $notes_info ['notetime'];
				$facilities_id = $notes_info ['facilities_id'];
				
				$shift_data = $this->model_notes_notes->getShiftColor ( $notetime, $facilities_id );
				
				$shift_name = $shift_data ['shift_name'];
				
				$data2 = array (
						'activenote' => $fromdatas ['relation_keyword_id'],
						'sort' => $sort,
						'order' => $order,
						'note_date_from' => $date_added,
						'note_date_to' => $date_added,
						'advance_searchapp' => '1',
						'facilities_id' => $search_facilities_id1,
						'customer_key' => $this->session->data ['webcustomer_key'],
						'relation_search' => $relation_search,
						'start' => 0,
						'limit' => 500 
				);
				
				$nnotes = $this->model_notes_notes->getnotess ( $data2 );
			} else {
				
				// $data ['facilities_id'] = $notes_info ['facilities_id'];
				// $data ['notetime'] = $notes_info ['notetime'];
				// $nnotes = $this->model_notes_notes->getAllShiftNotes ( $data );
				
				/*
				 * $date_added = date('Y-m-d');
				 * $data3['notetime'] = date('H:i:s');
				 * $data3['facilities_id'] = '';
				 *
				 * $sql.="SELECT * FROM `" . DB_PREFIX . "shift` WHERE '".$data3['notetime']."' BETWEEN `shift_starttime` AND `shift_endtime`";
				 * $query = $this->db->query($sql);
				 * $shift_time = $query->row;
				 * $shift_name = $shift_time;
				 *
				 *
				 * $data2 = array(
				 * 'note_date_from' => $date_added,
				 * 'note_date_to' => $date_added,
				 * 'search_time_start' => $shift_time['shift_starttime'],
				 * 'search_time_to' => $shift_time['shift_endtime'],
				 * 'note_date' => '',
				 * 'advance_search' => '1',
				 * 'start' => '1',
				 * 'limit' => '500',
				 * );
				 *
				 * $snotess = $this->model_notes_notes->getnotess($data2);
				 */
				
				$report_time = date ( 'h:i A', strtotime ( $notes_info ['date_added'] ) );
				
				$report_date = date ( 'Y-m-d', strtotime ( $notes_info ['date_added'] ) );
				
				$date_added = date ( 'Y-m-d', strtotime ( $notes_info ['date_added'] ) );
				
				$note_date = $notes_info ['note_date'];
				$data3 ['notetime'] = strtotime ( date ( 'h:i' ) );
				
				// var_dump($notes_info);die;
				
				$notetime = $notes_info ['notetime'];
				$facilities_id = $notes_info ['facilities_id'];
				
				$shift_data = $this->model_notes_notes->getShiftColor ( $notetime, $facilities_id );
				
				$shift_name = $shift_data ['shift_name'];
				
				$data2 = array (
						'note_date_from' => $date_added,
						'note_date_to' => $date_added,
						'search_time_start' => $shift_data ['shift_starttime'],
						'search_time_to' => $shift_data ['shift_endtime'],
						'note_date' => $note_date,
						'advance_search' => '1',
						'start' => '1',
						'group' => '5',
						'limit' => '500',
						'facilities_id' => $data3 ['facilities_id'] 
				)
				;
				
				$snotess = $this->model_notes_notes->getnotess ( $data2 );
				
				$nnotes = $this->model_notes_notes->getnotess ( $data2 );
			}
			
			/* added movement report code */
			
			foreach ( $snotess as $nnote ) {
				
				$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
				
				$emp_tag_id22 = "";
				$roleCall = "";
				
				$date_added = date ( 'm-d-Y', strtotime ( $nnote ['date_added'] ) );
				$notetime2 = date ( 'h:i A', strtotime ( $nnote ['date_added'] ) );
				
				if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
					$this->load->model ( 'api/permision' );
					$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
					
					$data4 = array (
							
							'facilities_id' => $tag_info ['facilities_id'],
							'locations_id' => $tag_info ['room'] 
					);
					
					$location_details = $this->model_notes_tags->getLocationNamebyLocationId ( $data4 );
					
					$room_name = $location_details ['location_name'];
					
					$clientinfo = $this->model_api_permision->getclientinfo ( $nnote ['facilities_id'], $tag_info );
					$emp_tag_id22 = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
					
					$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $alltag ['tag_status_id'] );
					
					if ($alltag ['tag_status_id'] != "" && $alltag ['tag_status_id'] != null) {
						
						$alltag2 = $this->model_notes_notes->getNotesTags ( $alltag ['move_notes_id'] );
						
						$clientstatus_info2 = $this->model_notes_clientstatus->getclientstatus ( $alltag2 ['tag_status_id'] );
						
						$out_notes_info = $this->model_notes_notes->getNote ( $alltag2 ['notes_id'] );
						
						$in_notes_info = $this->model_notes_notes->getNote ( $nnote ['notes_id'] );
						
						if ($clientstatus_info2 ['type'] == $clientstatus_info ['type']) {
							
							if ($clientstatus_info ['status_type'] == '0') {
								
								$roleCall = $clientstatus_info2 ['name'];
								
								$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
								
								$emp_tag_id22 = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
								
								$outnotetime = date ( $timeinfo ['time_format'], strtotime ( $in_notes_info ['notetime'] ) );
								
								$notetime = date ( $timeinfo ['time_format'], strtotime ( $out_notes_info ['notetime'] ) );
								
								$outuser_id = $in_notes_info ['user_id'];
								$outsignature = $in_notes_info ['signature'];
								$outnotes_type = $in_notes_info ['notes_type'];
								
								if ($in_notes_info ['notes_pin'] != null && $in_notes_info ['notes_pin'] != "") {
									$outnotes_pin = $in_notes_info ['notes_pin'];
								} else {
									$outnotes_pin = '';
								}
								
								/* */
								
								$user_id = $out_notes_info ['user_id'];
								$signature = $out_notes_info ['signature'];
								$notes_type = $out_notes_info ['notes_type'];
								
								if ($out_notes_info ['notes_pin'] != null && $out_notes_info ['notes_pin'] != "") {
									$notes_pin = $out_notes_info ['notes_pin'];
								} else {
									$notes_pin = '';
								}
								
								if (($nnote ['generate_report'] == '6') && ($roleCall != "" && $roleCall != null)) {
									
									$inmovementnotes [] = array (
											
											'notetime' => $notetime,
											'emp_tag_id22' => $emp_tag_id22,
											'outnotetime' => $outnotetime,
											'user_id' => $user_id,
											'roleCall' => $roleCall,
											'description1' => $description1,
											'notes_type' => $notes_type,
											'notes_pin' => $notes_pin,
											'room_name' => $room_name,
											'signature' => $signature,
											'outuser_id' => $outuser_id,
											'outnotes_type' => $outnotes_type,
											'outnotes_pin' => $outnotes_pin,
											'outsignature' => $outsignature 
									);
								}
							}
						} else if ($clientstatus_info2 ['type'] != $clientstatus_info ['type']) {
							
							if ($clientstatus_info2 ['type'] == '3') {
								
								$roleCall = $clientstatus_info2 ['name'];
								
								$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
								
								$emp_tag_id22 = $tag_info ['emp_first_name'] . " " . $tag_info ['emp_last_name'];
								
								$outnotetime = date ( $timeinfo ['time_format'], strtotime ( $in_notes_info ['notetime'] ) );
								
								$notetime = date ( $timeinfo ['time_format'], strtotime ( $out_notes_info ['notetime'] ) );
								
								$outuser_id = $in_notes_info ['user_id'];
								$outsignature = $in_notes_info ['signature'];
								$outnotes_type = $in_notes_info ['notes_type'];
								
								if ($in_notes_info ['notes_pin'] != null && $in_notes_info ['notes_pin'] != "") {
									$outnotes_pin = $in_notes_info ['notes_pin'];
								} else {
									$outnotes_pin = '';
								}
								
								/* */
								
								$user_id = $out_notes_info ['user_id'];
								$signature = $out_notes_info ['signature'];
								$notes_type = $out_notes_info ['notes_type'];
								
								if ($out_notes_info ['notes_pin'] != null && $out_notes_info ['notes_pin'] != "") {
									$notes_pin = $out_notes_info ['notes_pin'];
								} else {
									$notes_pin = '';
								}
								
								if (($nnote ['generate_report'] == '6') && ($roleCall != "" && $roleCall != null)) {
									
									$outmovementnotes [] = array (
											
											'notetime' => $notetime,
											'emp_tag_id22' => $emp_tag_id22,
											'outnotetime' => $outnotetime,
											'user_id' => $user_id,
											'roleCall' => $roleCall,
											'description1' => $description1,
											'notes_type' => $notes_type,
											'notes_pin' => $notes_pin,
											'signature' => $signature,
											'outuser_id' => $outuser_id,
											'outnotes_type' => $outnotes_type,
											'outnotes_pin' => $outnotes_pin,
											'outsignature' => $outsignature 
									);
								}
							}
						}
					}
					
					if ($clientstatus_info ['type'] == '2') {
						$roleCall = $clientstatus_info ['name'];
						$inroleCall = $clientstatus_info ['name'];
						if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
							$outnotes_pin = $nnote ['notes_pin'];
						} else {
							$outnotes_pin = '';
						}
						
						$outuser_id = $nnote ['user_id'];
						$outsignature = $nnote ['signature'];
						$outnotes_type = $nnote ['notes_type'];
						
						$outnotetime = date ( $timeinfo ['time_format'], strtotime ( $nnote ['notetime'] ) );
					}
					if ($alltag ['move_notes_id'] > 0) {
						$result1 = $this->model_notes_notes->getnotes ( $alltag ['move_notes_id'] );
						$alltag1 = $this->model_notes_notes->getNotesTags ( $result1 ['notes_id'] );
						
						if ($alltag1 ['emp_tag_id'] != null && $alltag1 ['emp_tag_id'] != "") {
							$clientstatus_info1 = $this->model_notes_clientstatus->getclientstatus ( $alltag1 ['tag_status_id'] );
							
							if ($clientstatus_info1 ['type'] == '3') {
								$roleCall = $clientstatus_info1 ['name'];
								$outroleCall = $clientstatus_info1 ['name'];
								if ($result1 ['notes_pin'] != null && $result1 ['notes_pin'] != "") {
									$notes_pin = $result1 ['notes_pin'];
								} else {
									$notes_pin = '';
								}
								
								$user_id = $result1 ['user_id'];
								$signature = $result1 ['signature'];
								$notes_type = $result1 ['notes_type'];
								
								$notetime = date ( $timeinfo ['time_format'], strtotime ( $result1 ['notetime'] ) );
								
								$description1 = "";
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
				}
				
				if ($inmovementnotes == null) {
					
					$inmovementnotes = array ();
				}
				
				if ($outmovementnotes == null) {
					
					$outmovementnotes = array ();
				}
				
				$movementnotes = array_merge ( $inmovementnotes, $outmovementnotes );
			}
			
			/* added movement report code */
			
			/*
			 * $date_added = date('Y-m-d');
			 * $data3['notetime'] = date('H:i:s');
			 * $data3['facilities_id'] = '';
			 *
			 * $sql.="SELECT * FROM `" . DB_PREFIX . "shift` WHERE '".$data3['notetime']."' BETWEEN `shift_starttime` AND `shift_endtime`";
			 * $query = $this->db->query($sql);
			 * $shift_time = $query->row;
			 * $shift_name = $shift_time['shift_name'];
			 */
			
			foreach ( $nnotes as $nnote ) {
				
				$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
				
				$emp_tag_id = "";
				$emp_extID = "";
				$emp_extID = "";
				$location_name = "";
				
				if ($nnote ['emp_tag_id'] == '1') {
					$alltags = $this->model_setting_tags->getTagsbyNotesID2 ( $nnote ['notes_id'] );
					foreach ( $alltags as $alltag ) {
						$emp_tag_id = "";
						$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
						$emp_tag_id .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
						$emp_extID .= $tag_info ['emp_extid'];
						
						$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
						$location_name = $location_info ['location_name'];
					}
				}
				
				if ($emp_tag_id != null && $emp_tag_id != "") {
					$emp_tag_id = $emp_tag_id;
				} else {
					// $emp_tag_id = $name;
					$emp_tag_id = '';
				}
				
				$keyImageSrc11 = "";
				if ($nnote ['keyword_file'] == '1') {
					$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
					foreach ( $allkeywords as $keyword ) {
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
					}
				}
				
				$issued = '';
				$collected = '';
				$date_issued = '';
				
				$allmultikeywords = $this->model_notes_notes->getnotesmultiKey ( $nnote ['notes_id'] );
				
				foreach ( $allmultikeywords as $allmultikeyword ) {
					if (trim ( $allmultikeyword ['name'] ) == trim ( "Issued |" )) {
						
						$date_issued = date ( 'h:i A', strtotime ( $allmultikeyword ['date_added'] ) );
					} elseif (trim ( $allmultikeyword ['name'] ) == trim ( "Collected |" )) {
						
						$date_collected = date ( 'h:i A', strtotime ( $allmultikeyword ['date_added'] ) );
					} else {
						$date_issued = '';
						$date_collected = '';
					}
					
					// $keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
				}
				
				if ($nnote ['highlighter_id'] > 0) {
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
				} else {
					$highlighterData = array ();
				}
				$notetime1 = "";
				
				if ($nnote ['parent_id'] > 0) {
					$note_info1 = $this->model_notes_notes->getnotes ( $nnote ['parent_id'] );
					$notetime1 = date ( 'h:i A', strtotime ( $note_info1 ['notetime'] ) );
				}
				
				$notetime2 = date ( 'h:i A', strtotime ( $nnote ['notetime'] ) );
				$facility = $result_info ['facility'];
				
				// var_dump($notess);
				
				/*
				 * if(!empty($notess)){
				 * unset($notess);
				 * }
				 */
				
				$notess [] = array (
						'notes_id' => $nnote ['notes_id'],
						'emp_tag_id' => $emp_tag_id,
						'arrest' => $emp_extID,
						'issued' => $issued,
						'date_collected' => $date_collected,
						'date_issued' => $date_issued,
						
						'location_name' => $location_name,
						'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
						'facilities_id' => $result_info ['facility'],
						'highlighter_value' => $highlighterData ['highlighter_value'],
						'text_color' => $nnote ['text_color'],
						'notetime' => date ( $timeinfo ['time_format'], strtotime ( $nnote ['notetime'] ) ),
						'notetime1' => $notetime1,
						'user_id' => $nnote ['user_id'],
						'signature' => $nnote ['signature'],
						// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
						'date_added' => date ( 'm-d-Y', strtotime ( $nnote ['date_added'] ) ),
						'note_date' => date ( $timeinfo ['date_format'], strtotime ( $nnote ['date_added'] ) ) 
				);
			}
		}
		
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
		
		if ($results ['custom_form_type'] == '52') {
			
			$this->load->model ( 'notes/notes' );
			$note_info = $this->model_notes_notes->getnotes ( $results ['notes_id'] );
			
			$result_info = $this->model_facilities_facilities->getfacilities ( $note_info ['facilities_id'] );
			$facility = $result_info ['facility'];
			
			$t2facility = "";
			if ($note_info ['parent_facilities_id'] > 0) {
				$r2essult_info = $this->model_facilities_facilities->getfacilities ( $note_info ['parent_facilities_id'] );
				$t2facility = $r2essult_info ['facility'];
			}
			
			$resultsforms = $this->model_form_form->getArcheiveFormDatas ( $results ['forms_id'] );
			
			foreach ( $resultsforms as $resultsform ) {
				$nnote = $this->model_notes_notes->getnotes ( $resultsform ['notes_id'] );
				
				$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
				
				$emp_tag_id = "";
				
				if ($fromdatas ['client_reqired'] == '0') {
					$emp_tag_id = $name;
				} else {
					if ($nnote ['emp_tag_id'] == '1') {
						$alltags = $this->model_setting_tags->getTagsbyNotesID2 ( $nnote ['notes_id'] );
						foreach ( $alltags as $alltag ) {
							$emp_tag_id = "";
							$tag_info = $this->model_setting_tags->getTag ( $alltag ['tags_id'] );
							$emp_tag_id .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' ';
						}
					}
				}
				
				// var_dump($emp_tag_id);
				
				$keyImageSrc11 = "";
				if ($nnote ['keyword_file'] == '1') {
					$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
					foreach ( $allkeywords as $keyword ) {
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
					}
				}
				
				if ($nnote ['highlighter_id'] > 0) {
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
				} else {
					$highlighterData = array ();
				}
				
				$tfacility = "";
				if ($nnote ['parent_facilities_id'] > 0) {
					$r2esult_info = $this->model_facilities_facilities->getfacilities ( $nnote ['parent_facilities_id'] );
					$tfacility = $r2esult_info ['facility'];
				}
				
				// var_dump($nnote);
				
				// var_dump($nnote['notes_id']);
				
				$dtnnotess [] = array (
						'notes_id' => $nnote ['notes_id'],
						'emp_tag_id' => $emp_tag_id,
						'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
						'facilities_id' => $result_info ['facility'],
						'tfacility' => $tfacility,
						'highlighter_value' => $highlighterData ['highlighter_value'],
						'text_color' => $nnote ['text_color'],
						'notetime' => date ( $timeinfo ['time_format'], strtotime ( $nnote ['notetime'] ) ),
						'user_id' => $nnote ['user_id'],
						'signature' => $nnote ['signature'],
						// 'note_date' => date($this->language->get('date_format_short_2'), strtotime($nnote['note_date'])),
						'date_added' => date ( 'm-d-Y', strtotime ( $nnote ['date_added'] ) ),
						'note_date' => date ( $timeinfo ['date_format'], strtotime ( $nnote ['date_added'] ) ) 
				);
			}
		}
		
		$this->document->setTitle ( 'Reprot' );
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
		
		$customlist_values = $this->model_notes_notes->getcustomlistvalues ();
		
		$s = array ();
		$s ['facilities_id'] = $search_facilities_id1;
		$s ['sbfacility'] = 1;
		$sfacilities = $this->model_facilities_facilities->getfacilitiess ( $s );
		
		$this->load->model ( 'user/user' );
		
		$allcusers = $this->model_user_user->getUsersByFacility ( $search_facilities_id1 );
		
		// echo '<pre>'; print_r($allcusers); echo '</pre>'; die;
		
		$template = new Template ();
		$template->data ['formparents'] = $formparents;
		$template->data ['formdatas'] = $formdatas;
		$template->data ['customlists1'] = $customlists1;
		$template->data ['parent_id'] = $results ['parent_id'];
		$template->data ['movementnotes'] = $movementnotes;
		$template->data ['inmovementnotes'] = $inmovementnotes;
		$template->data ['outmovementnotes'] = $outmovementnotes;
		$template->data ['notess'] = $notess;
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
		$template->data ['report_date'] = $report_date;
		$template->data ['sallcusers'] = $allcusers;
		$template->data ['emp_extid'] = $emp_extid;
		$template->data ['report_time'] = $report_time;
		
		$template->data ['observation_name'] = $tags_data ['emp_first_name'] . " " . $tags_data ['emp_last_name'];
		$template->data ['observation_ssn'] = $tags_data ['ssn'];
		$template->data ['observation_date'] = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
		$template->data ['observation_userid'] = $notes_info ['user_id'];
		$template->data ['observation_notess'] = $observation_notess;
		
		$print_type = $fromdatas ['pdf_setting'] ['print_type'];
		$template->data ['print_type'] = $print_type;
		
		if ($results ['is_final'] == 1) {
			$template->data ['supervisor_name'] = $notes_info ['user_id'];
		} else {
			$template->data ['supervisor_name'] = 'N/A';
		}
		
		if ($fromdatas ['pdf_setting'] ['print_type'] != '' && $fromdatas ['pdf_setting'] ['print_type'] != null) {
			if ($fromdatas ['pdf_setting'] ['print_type'] == '2') {
				
				/*
				 * $temp_file_name = 'report_pdf'.date('YmdHis').'.zip';
				 * $zip = new ZipArchive();
				 * $file = $temp_file_name;
				 * $zip->open($file, ZipArchive::CREATE);
				 *
				 * //$pdf->AddPage ();
				 *
				 * foreach($formdatas as $form_data){
				 *
				 *
				 * if($form_data['add_row'] > '1'){
				 *
				 * for($i=0;$i <= $form_data['add_row']; $i++) {
				 *
				 * if($form_data['add_row']!='d'){
				 *
				 *
				 *
				 * $pdf->SetCreator ( PDF_CREATOR );
				 * $pdf->SetAuthor ( '' );
				 * $pdf->SetTitle ( 'REPORT' );
				 * $pdf->SetSubject ( 'REPORT' );
				 * $pdf->SetKeywords ( 'REPORT' );
				 *
				 *
				 *
				 * // set auto page breaks
				 * $pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
				 *
				 * // set image scale factor
				 * $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
				 * if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
				 * require_once (dirname ( __FILE__ ) . '/lang/eng.php');
				 * $pdf->setLanguageArray ( $l );
				 * }
				 *
				 *
				 * $pdf->SetFont ( 'helvetica', '', 9 );
				 *
				 *
				 * $template->data ['addrow_data'] = $i;
				 *
				 *
				 *
				 * //$pdf->AddPage ();
				 *
				 * $reportfilename = $this->request->get ['forms_design_id'];
				 * if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
				 * $html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
				 * }
				 *
				 *
				 *
				 *
				 * // output the HTML content
				 * $pdf->writeHTML ( $html, true, 0, true, 0 );
				 * //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
				 *
				 * // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				 * // reset pointer to the last page
				 * //$pdf->lastPage ();
				 * // ---------------------------------------------------------
				 * $pdf->Output('report_'.$i.'.pdf', 'F');
				 *
				 * // ZIP the PDF file
				 * $zip->addFile('report_'.$i.'.pdf');
				 * $delete_files[]='report_'.$i.'.pdf';
				 * }
				 *
				 * }
				 *
				 *
				 * }else{
				 *
				 * /*
				 * if($fromdatas){
				 *
				 * $form_name=$fromdatas['form_name'];
				 *
				 * }else{
				 *
				 * $form_name='';
				 *
				 * }
				 *
				 * $pdf->SetCreator ( PDF_CREATOR );
				 * $pdf->SetAuthor ( '' );
				 * $pdf->SetTitle ( 'REPORT' );
				 * $pdf->SetSubject ( 'REPORT' );
				 * $pdf->SetKeywords ( 'REPORT' );
				 *
				 * // set auto page breaks
				 * $pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
				 *
				 * // set image scale factor
				 * $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
				 * if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
				 * require_once (dirname ( __FILE__ ) . '/lang/eng.php');
				 * $pdf->setLanguageArray ( $l );
				 * }
				 *
				 * $pdf->SetFont ( 'helvetica', '', 9 );
				 *
				 * $pdf->AddPage ();
				 * $reportfilename = $this->request->get ['forms_design_id'];
				 * if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
				 * $html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
				 * }
				 *
				 *
				 * $pdf->writeHTML ( $html, true, 0, true, 0 );
				 *
				 * $pdf->lastPage ();
				 *
				 * // $pdf->Output('example_049.pdf', 'D');
				 *
				 * $pdf->Output ( 'report_' . $form_name .'.pdf', 'F' );
				 *
				 * $zip->addFile('report_' . $form_name .'.pdf');
				 *
				 * $delete_files[]='report_' . $form_name .'.pdf';
				 */
				
				/*
				 * }
				 *
				 * }//die;
				 *
				 * //die;
				 * $zip->close();
				 *
				 * header('Content-type: application/zip');
				 * header('Content-Disposition: attachment; filename="'.$temp_file_name.'"');
				 * readfile($temp_file_name);
				 *
				 * foreach($delete_files as $delete_file){
				 * unlink($delete_file);
				 * }
				 * unlink($temp_file_name);
				 */
				$temp_file_name = 'report_pdf' . date ( 'YmdHis' ) . '.zip';
				$zip = new ZipArchive ();
				$file = $temp_file_name;
				$zip->open ( $file, ZipArchive::CREATE );
				
				foreach ( $formdatas as $form_data ) {
					
					if ($form_data ['add_row'] >= '1') {
						
						for($i = 0; $i <= $form_data ['add_row']; $i ++) {
							
							if ($form_data ['add_row'] != 'd') {
								
								// create new PDF document
								$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
								
								// set document information
								$pdf->SetCreator ( PDF_CREATOR );
								$pdf->SetAuthor ( '' );
								$pdf->SetTitle ( 'REPORT' );
								$pdf->SetSubject ( 'REPORT' );
								$pdf->SetKeywords ( 'REPORT' );
								
								// set auto page breaks
								$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
								
								// set image scale factor
								$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
								if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
									require_once (dirname ( __FILE__ ) . '/lang/eng.php');
									$pdf->setLanguageArray ( $l );
								}
								
								$pdf->SetFont ( 'helvetica', '', 9 );
								
								$customlist_values = $this->model_notes_notes->getcustomlistvalues ();
								
								$s = array ();
								$s ['facilities_id'] = $search_facilities_id1;
								$s ['sbfacility'] = 1;
								$sfacilities = $this->model_facilities_facilities->getfacilitiess ( $s );
								
								$this->load->model ( 'user/user' );
								
								$allcusers = $this->model_user_user->getUsersByFacility ( $search_facilities_id1 );
								
								$pdf->AddPage ();
								$template = new Template ();
								$template->data ['formparents'] = $formparents;
								$template->data ['formdatas'] = $formdatas;
								$template->data ['addrow_data'] = $i;
								$template->data ['customlists1'] = $customlists1;
								$template->data ['parent_id'] = $results ['parent_id'];
								$template->data ['movementnotes'] = $movementnotes;
								$template->data ['inmovementnotes'] = $inmovementnotes;
								$template->data ['outmovementnotes'] = $outmovementnotes;
								$template->data ['notess'] = $notess;
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
								$template->data ['report_date'] = $report_date;
								$template->data ['sallcusers'] = $allcusers;
								$template->data ['emp_extid'] = $emp_extid;
								$template->data ['report_time'] = $report_time;
								
								$template->data ['observation_name'] = $tags_data ['emp_first_name'] . " " . $tags_data ['emp_last_name'];
								$template->data ['observation_ssn'] = $tags_data ['ssn'];
								$template->data ['observation_date'] = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
								$template->data ['observation_userid'] = $notes_info ['user_id'];
								$template->data ['observation_notess'] = $observation_notess;
								
								if ($results ['is_final'] == 1) {
									$template->data ['supervisor_name'] = $notes_info ['user_id'];
								} else {
									$template->data ['supervisor_name'] = 'N/A';
								}
								
								$reportfilename = $this->request->get ['forms_design_id'];
								if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
								}
								
								// output the HTML content
								// $pdf->writeHTML ( $html, true, 0, true, 0 );
								$pdf->writeHTMLCell ( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
								
								// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
								// reset pointer to the last page
								// $pdf->lastPage ();
								// ---------------------------------------------------------
								$pdf->Output ( 'report_' . $i . '.pdf', 'F' );
								
								// ZIP the PDF file
								$zip->addFile ( 'report_' . $i . '.pdf' );
								$delete_files [] = 'report_' . $i . '.pdf';
								
								// $pdf->Output ( 'report_' . rand () . '.pdf', 'F' );
								
								// $zip->addFromString("print".$i.".txt",'xkjasdlksajdlksdasas');
								// $zip->addFile('report_' . rand () . '.pdf');
							}
						}
					} else {
						
						for($i = 0; $i <= $form_data ['add_row']; $i ++) {
							
							if ($form_data ['add_row'] != 'd') {
								
								// create new PDF document
								$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
								
								// set document information
								$pdf->SetCreator ( PDF_CREATOR );
								$pdf->SetAuthor ( '' );
								$pdf->SetTitle ( 'REPORT' );
								$pdf->SetSubject ( 'REPORT' );
								$pdf->SetKeywords ( 'REPORT' );
								
								// set auto page breaks
								$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
								
								// set image scale factor
								$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
								if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
									require_once (dirname ( __FILE__ ) . '/lang/eng.php');
									$pdf->setLanguageArray ( $l );
								}
								
								$pdf->SetFont ( 'helvetica', '', 9 );
								
								$customlist_values = $this->model_notes_notes->getcustomlistvalues ();
								
								$s = array ();
								$s ['facilities_id'] = $search_facilities_id1;
								$s ['sbfacility'] = 1;
								$sfacilities = $this->model_facilities_facilities->getfacilitiess ( $s );
								
								$this->load->model ( 'user/user' );
								
								$allcusers = $this->model_user_user->getUsersByFacility ( $search_facilities_id1 );
								
								$pdf->AddPage ();
								$template = new Template ();
								$template->data ['formparents'] = $formparents;
								$template->data ['formdatas'] = $formdatas;
								$template->data ['addrow_data'] = $i;
								$template->data ['customlists1'] = $customlists1;
								$template->data ['parent_id'] = $results ['parent_id'];
								$template->data ['movementnotes'] = $movementnotes;
								$template->data ['inmovementnotes'] = $inmovementnotes;
								$template->data ['outmovementnotes'] = $outmovementnotes;
								$template->data ['notess'] = $notess;
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
								$template->data ['report_date'] = $report_date;
								$template->data ['sallcusers'] = $allcusers;
								$template->data ['emp_extid'] = $emp_extid;
								$template->data ['report_time'] = $report_time;
								
								$template->data ['observation_name'] = $tags_data ['emp_first_name'] . " " . $tags_data ['emp_last_name'];
								$template->data ['observation_ssn'] = $tags_data ['ssn'];
								$template->data ['observation_date'] = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
								$template->data ['observation_userid'] = $notes_info ['user_id'];
								$template->data ['observation_notess'] = $observation_notess;
								
								if ($results ['is_final'] == 1) {
									$template->data ['supervisor_name'] = $notes_info ['user_id'];
								} else {
									$template->data ['supervisor_name'] = 'N/A';
								}
								
								$reportfilename = $this->request->get ['forms_design_id'];
								if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
								}
								
								// output the HTML content
								// $pdf->writeHTML ( $html, true, 0, true, 0 );
								$pdf->writeHTMLCell ( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
								
								// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
								// reset pointer to the last page
								// $pdf->lastPage ();
								// ---------------------------------------------------------
								$pdf->Output ( 'report_' . $i . '.pdf', 'F' );
								
								// ZIP the PDF file
								$zip->addFile ( 'report_' . $i . '.pdf' );
								$delete_files [] = 'report_' . $i . '.pdf';
								
								// $pdf->Output ( 'report_' . rand () . '.pdf', 'F' );
								
								// $zip->addFromString("print".$i.".txt",'xkjasdlksajdlksdasas');
								// $zip->addFile('report_' . rand () . '.pdf');
							}
						}
					}
				}
				
				$zip->close ();
				
				header ( 'Content-type: application/zip' );
				header ( 'Content-Disposition: attachment; filename="' . $temp_file_name . '"' );
				readfile ( $temp_file_name );
				
				foreach ( $delete_files as $delete_file ) {
					unlink ( $delete_file );
				}
				unlink ( $temp_file_name );
				
				exit ();
			} else {
				// set document information
				$pdf->SetCreator ( PDF_CREATOR );
				$pdf->SetAuthor ( '' );
				$pdf->SetTitle ( 'REPORT' );
				$pdf->SetSubject ( 'REPORT' );
				$pdf->SetKeywords ( 'REPORT' );
				
				// set auto page breaks
				$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
				
				// set image scale factor
				$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
				if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
					require_once (dirname ( __FILE__ ) . '/lang/eng.php');
					$pdf->setLanguageArray ( $l );
				}
				
				$pdf->SetFont ( 'helvetica', '', 9 );
				$pdf->AddPage ();
				$reportfilename = $this->request->get ['forms_design_id'];
				if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
					$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
				}
				
				$pdf->writeHTML ( $html, true, 0, true, 0 );
				
				$pdf->lastPage ();
				
				// $pdf->Output('example_049.pdf', 'D');
				
				$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
			}
		} else {
			$pdf->SetCreator ( PDF_CREATOR );
			$pdf->SetAuthor ( '' );
			$pdf->SetTitle ( 'REPORT' );
			$pdf->SetSubject ( 'REPORT' );
			$pdf->SetKeywords ( 'REPORT' );
			
			// set auto page breaks
			$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
			
			// set image scale factor
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
				require_once (dirname ( __FILE__ ) . '/lang/eng.php');
				$pdf->setLanguageArray ( $l );
			}
			
			$pdf->SetFont ( 'helvetica', '', 9 );
			$pdf->AddPage ();
			$reportfilename = $this->request->get ['forms_design_id'];
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' )) {
				$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/' . $reportfilename . '.php' );
			}
			
			$pdf->writeHTML ( $html, true, 0, true, 0 );
			
			$pdf->lastPage ();
			
			// $pdf->Output('example_049.pdf', 'D');
			
			$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		}
		
		exit ();
	}
	
	public function printformfldjj() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		$this->load->model('api/permision');
		$this->language->load ( 'notes/notes' );
		$timeinfo = $this->model_api_permision->getcustomerdatetime($this->customer->getId ());
		if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
			
			$results = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
			$parent_form_parent_id=$results['parent_id'];
			
			
			
			if (! empty ( $results ['design_forms'] )) {
				$formdatas = unserialize ( $results ['design_forms'] );
			}
			
			$this->data ['upload_file'] = $results ['upload_file'];
			$this->data ['form_signature'] = $results ['form_signature'];
			
			$fforms_id = $this->request->get ['forms_id'];
			
			if ($fforms_id != null && $fforms_id != "") {
			
			if($parent_form_parent_id){
				
				$formsdata = $this->model_form_form->getFormparentnotesId ( $parent_form_parent_id );
				if($formsdata){

				  $fforms_id= $formsdata['forms_id'];

				}		  	
			
			}else{
				
				$fforms_id = $fforms_id;
			}
			
			
			
		} else {
			$fforms_id = $dfforms_id;
		}
			
			
			
			$formmedias = $this->model_form_form->getFormmedia ( $fforms_id );
			
			
			
			$this->data ['formsimages'] = array ();
			$this->data ['formssigns'] = array ();
			
			foreach ( $formmedias as $formmedia ) {
				
				if ($formmedia ['media_type'] == '1') {
					$formdatas [$formmedia ['media_name']] [] = $formmedia ['media_url'];
				}
				
				if ($formmedia ['media_type'] == '2') {
					$formdatas [$formmedia ['media_name']] [] = $formmedia ['media_url'];
				}
			}
			
			$pnotes = array ();
			
			if ($results ['parent_id'] > 0) {
				$sql2 = "SELECT * from " . DB_PREFIX . "notes where parent_id = '" . $results ['parent_id'] . "'";
				//var_dump($sql2);die;
				
				$q22 = $this->db->query ( $sql2 );
				
				
				
				if ($q22->row ['tasktype'] > 0) {
					$this->load->model ( 'createtask/createtask' );
					
					$tasktype_info = $this->model_createtask_createtask->getcustomlistByTasktype ( $q22->row ['tasktype'] );
					
					
					$this->load->model ( 'notes/notes' );
					if ($tasktype_info ['customlist_id']) {
						
						$d = array ();
						$d ['customlist_id'] = $tasktype_info ['customlist_id'];
						$customlists = $this->model_notes_notes->getcustomlists ( $d );
						
						if ($customlists) {
							foreach ( $customlists as $customlist ) {
								$d2 = array ();
								$d3 = array ();
								$d2 ['customlist_id'] = $customlist ['customlist_id'];
								$d2 ['parent_id'] = $results ['parent_id'];
								
								$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
								
								foreach ( $customlistvalues as $customlistvalue ) {
									/*
									 * $noteinfo2 = $this->model_notes_notes->getnotecustomlistvalue2($customlistvalue['customlistvalues_id'], $results['parent_id']);
									 *
									 * //var_dump($noteinfo2);
									 * if($noteinfo2 != ""){
									 * $noteinfo3 = $noteinfo2;
									 * }else{
									 * $noteinfo3 = '';
									 * }
									 */
									
									$d3 [] = array (
											'customlistvalues_id' => $customlistvalue ['customlistvalues_id'],
											'customlistvalues_name' => $customlistvalue ['customlistvalues_name'],
											'number' => $customlistvalue ['number'],
											'noteinfo2' => $noteinfo3 
									);
								}
								
								$customlists1 [] = array (
										'customlist_id' => $customlist ['customlist_id'],
										'customlist_name' => $customlist ['customlist_name'],
										'customlistvalues' => $customlistvalues 
								);
							}
						}
					}
				}
			}
			
			if ($results ['parent_id'] > 0) {
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'notes/tags' );
				$this->load->model ( 'user/user' );
				$this->language->load ( 'notes/notes' );
				$notess = array ();
				$notesresults = $this->model_notes_notes->getnotesbyparent ( $results ['parent_id'] );
				
				//var_dump($notesresults);die;
				
				foreach ( $notesresults as $result ) {
					
					
				$notetime = $result['notetime'];
				$facilities_id = $result['facilities_id'];

				$shift_data = $this->model_notes_notes->getShiftColor($notetime,$facilities_id);


				$shift_name=$shift_data['shift_name'];
				
				
					
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$userPin = $result ['notes_pin'];
					} else {
						$userPin = '';
					}
					
					if ($config_tag_status == '1') {
						
						$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
						
						if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
							$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
							$privacy = $tagdata ['privacy'];
							
							$emp_tag_id = ''; // $alltag['emp_tag_id'].': ';
						} else {
							$emp_tag_id = '';
							$privacy = '';
						}
					}
					
					$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					$noteskeywords = array ();
					
					if ($allkeywords) {
						$keyImageSrc12 = array ();
						$keyname = array ();
						$keyImageSrc11 = "";
						foreach ( $allkeywords as $keyword ) {
							
							$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
							
							$noteskeywords [] = array (
									'keyword_file_url' => $keyword ['keyword_file_url'] 
							);
						}
						
						$keyword_description = $keyImageSrc11 . '&nbsp;' . $result ['notes_description'];
						$notes_description = $emp_tag_id . $keyword_description;
					}
					
					if($notes_description!=null && $notes_description!=''){
						$note_description=$notes_description;	
						
					}else{
						$note_description=$result['notes_description'];
					}
					
					$notess [] = array (
							'notes_id' => $result ['notes_id'],
							'task_type' => $result ['task_type'],
							'taskadded' => $result ['taskadded'],
							'assign_to' => $result ['assign_to'],
							'highlighter_value' => $highlighterData ['highlighter_value'],
							'notes_description' => $notes_description,
							'notetime' => date ( $timeinfo['time_format'], strtotime ( $result ['notetime'] ) ),
							'username' => $result ['user_id'],
							'notes_pin' => $userPin,
							'signature' => $result ['signature'],
							'note_date' => date (  $timeinfo['date_format'], strtotime ( $result ['note_date'] ) ) 
					);
				}
			}
		}
		
		$this->document->setTitle ( 'Florida Department Of Juvenile Justice' );
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		/*
		 * if ($this->config->get('pdf_report_image') && file_exists(DIR_SYSTEM . 'library/pdf_class/'.$this->config->get('pdf_report_image'))) {
		 * $imageLogo = $this->config->get('pdf_report_image');
		 * $PDF_HEADER_LOGO_WIDTH = "30";
		 *
		 * }else{
		 * $imageLogo = '4F-logo.png';
		 * $PDF_HEADER_LOGO_WIDTH = "30";
		 * $headerString = "";
		 * }
		 */
		
		/*
		 * $imageLogo = '4F-logo.png';
		 * $PDF_HEADER_LOGO_WIDTH = "30";
		 * $headerString = "Florida Department Of Juvenile Justice";
		 *
		 * $PDF_HEADER_TITLE = "";
		 * $pdf->SetHeaderData($PDF_HEADER_TITLE);
		 * $pdf->SetHeaderData($imageLogo, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE.'', $headerString);
		 * $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		 */
		// set margins
		$pdf->SetMargins ( '5', '5', '5' );
		$pdf->SetHeaderMargin ( '5' );
		$pdf->SetFooterMargin ( '5' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';
		$html .= '<style>

		td {
			margin: 1px;
		   border: 1px solid #B8b8b8;
		   line-height:18.2px;
		   display:table-cell;
			padding:1px;
		}
		</style>';
		
		/*
		 * $html.='<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		 * $html.='<tr>
		 * <td style="padding:20px;text-align:center;width:20%;" > </td>
		 * <td style="padding:20px;width:80%;" colspan="2"><h1 style="font-size:18px">'.$this->data['form_name'].'</h1></td>
		 *
		 * </tr>';
		 * $html.='</table>';
		 */
		
		$html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		
		$html .= '<tr><td colspan="3" style="text-align:center;padding:10px;background-color:#ccc;line-heigh:30px;height;70px;"><h2>Sucide Precautions and Observation Log </h2> </td></tr>';
		
		$html .= '<tr><td colspan="2" style="align:center;font-size:13px;">
		<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
		<tr>
		<td style="width:10%;border:none;">';
		if ($formdatas [0] [0] ['select_93830432']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
		<td style="width:90%;border:none;"><b>Precautionary Observation Log</b></td>
		</tr>
		</table>
		</td>
		
		<td> 
		<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
		<tr>
		<td style="border:none;">Date: ' . $formdatas [0] [0] ['date_93638826'] . '</td>
		<td style="border:none;">Time:' . $formdatas [0] [0] ['time_33135211'] . '</td>
		</tr>
		</table>
		</td></tr>';
		
		$html .= '<tr>  
					<td style="padding:20px;">
					<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
					<tr>
					<td style="width:10%;border:none;">';
		
		if ($formdatas [0] [0] ['select_35510589']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		
		$html .= '</td>
					<td style="width:90%;border:none;">Yes </td>
					</tr>
					</table>
					</td>
					<td style="padding:20px;" colspan="2">
					
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
							<tr>
							<td style="width:5%;border:none;">';
						if ($formdatas ['select_35510589']) {
						$html .= '<img src="image/printform/box_cross.png"> ';
						} else {
						$html .= '<img src="image/printform/box.png"> ';
						}
		      $html .= '</td> 
							<td style="width:90%;border:none;">';
							if($this->request->get ['forms_design_id']=='252'){
								
                            $html .='No &nbsp;&nbsp;&nbsp; Constant Sight and Sound Supervision Implemented and Maintained';         						
						
							}else{
								
							$html .='No &nbsp;&nbsp;&nbsp; Constant Supervision Implemented and Maintained';         						
								
							}								
						    $html .='</td>
						</tr>
						</table>
					</td>
					
				</tr>';
				if($this->request->get ['forms_design_id']=='252'){
				
				$html .='<tr>  
					<td style="padding:20px;">
					<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
					<tr>
					<td style="width:10%;border:none;">';
		
		if ($formdatas [0] [0] ['select_8316672']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		
		$html .= '</td>
					<td style="width:90%;border:none;">Yes </td>
					</tr>
					</table>
					</td>
					<td style="padding:20px;" colspan="2">
					
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
							<tr>
							<td style="width:5%;border:none;">';
						if ($formdatas ['select_8316672']) {
						$html .= '<img src="image/printform/box_cross.png"> ';
						} else {
						$html .= '<img src="image/printform/box.png"> ';
						}
		      $html .= '</td> 
							<td style="width:90%;border:none;">';							
								
							$html .='No &nbsp;&nbsp;&nbsp; One to one supervision implemented and maintained';         						
								
														
						    $html .='</td>
						</tr>
						</table>
					</td>
					
				</tr>';
				}
				
				
				$html .='<tr>
					<td colspan="2" style="">Youths Name   ' . $formdatas [0] [0] ['text_61453229'] . '</td>
					<td colspan="2" style="">Date of Birth ' . $formdatas [0] [0] ['date_82208178'] . '</td>
					
				</tr>';
				if($this->request->get ['forms_design_id']!='252'){
				
				$html .='<tr>
					<td style="">JJIS Number  ' . $formdatas [0] [0] ['text_11305284'] . '</td>
					<td style="">Race ' . $formdatas [0] [0] ['text_34329160'] . '</td>
					<td style="">Gender  ' . $formdatas [0] [0] ['select_82298274'] . '</td>
					
				</tr>
				
				<tr>
					<td colspan="2" style="">Facility/Program ' . $formdatas [0] [0] ['text_64107947'] . '</td>
					<td colspan="1" style="">Provider ' . $formdatas [0] [0] ['text_45233759'] . ' </td>
					
				</tr>';
				}
				
				$html .='<tr>
				<td colspan="3" style="line-heigh:30px;">
				<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
				
						<tr>
							<td colspan="3" ><b>Alert System Check (check the alerts that currently apply to the youth)</b>  </td></tr>
						<tr><td colspan="3"style="line-heigh:30px;">
							<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
							<tr>
							
							<td style="vertical-align:center;">
								<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
										<td style="width:20%;border:none;">';
		if ($formdatas [0] [0] ['0'] ['checkbox_45658071']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:80%;border:none;">Sucide Risk</td>
									</tr>
								</table>
							</td>
							<td style="vertical-align:center;">
								<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
										<td style="width:20%;border:none;">';
		if ($formdatas [0] [0] ['1'] ['checkbox_45658071']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= ' </td>
										<td style="width:80%;border:none;">Medical</td>
									</tr>
								</table>
							</td>
							<td style="vertical-align:center;">
								<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
										<td style="width:20%;border:none;">';
		if ($formdatas [0] [0] ['2'] ['checkbox_45658071']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:80%;border:none;">Mental Health</td>
									</tr>
								</table>	
							</td>
							<td style="vertical-align:center;">
								<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
										<td style="width:20%;border:none;">';
		if ($formdatas [0] [0] ['3'] ['checkbox_45658071']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:80%;border:none;">Substance Abuse</td>
									</tr>
								</table>	
							</td>
							<td style="vertical-align:center;">
								<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
										<td style="width:20%;border:none;">';
		if ($formdatas [0] [0] ['4'] ['checkbox_45658071']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= ' </td>
										<td style="width:80%;border:none;">Security</td>
									</tr>
								</table>	
							</td>
							</tr>
							</table>
							</td>
						</tr>	
					</table>
					</td>
				</tr>
				
				<tr>
					<td colspan="3" style="font-size:13px;"><b>INSTRUCTIONS:</b> This checklist is used to document staffâ€™s observation of youth who are placed on <b>PRECAUTIONARY OBSERVATION OR SECURE OBSERVATION. PRECAUTIONARY OBSERVATION AND SECURE OBSERVATION REQUIRE CONTINUOUS UNINTERUPTED OBSERVATION (CONSTANT SUPERVISION)</b>. Also, documentation of time and behavioral observation codes on this checklist is required at 10 minutes. Staff must record behavior not listed on the form as â€œOther Behavlors Observedâ€�, and document the number code and time these behaviors are observed. Code and staff initials are requested for each documentation. More than one code may be used to document multiple behavior (#1 for walking or sitting caimly, #5 for acting out, disturbing others), <b>If â€œwarning signsâ€� are observed, the facility superintendent program director or his/her designee and mental health staff must be notified and documented below. Youths who present and imminent threat of suicide must be treated as an emergency and the Bank Act Initiated</b>.</td>
					
				</tr>';
		
		$html .= '<tr>
					<td colspan="3" style="border:none;">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:30%;">CODE</td>
							<td style="width:70%;">Shift: '.$shift_name.'</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		
		$i = 0;
	
		
		//var_dump($customlists1);die;
		foreach ( $customlists1 as $customlist ) {
			$html .= '<tr>
					<td colspan="3" style="border:none;">
						<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
						<tr>
							<td style="width:30%;border:none;"><b>' . $customlist ['customlist_name'] . '</b></td>
							<td style="width:70%;border:none;">';
			if ($i == 0) {
				$html .= '
										<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
										<tr>
											<td style="width:30%;border-top: none;border-bottom: none;">Time</td>
											<td style="width:40%;border-top: none;border-bottom: none;">Observations</td>
											<td style="width:30%;border-top: none;border-bottom: none;">Initials</td>
										</tr>
										</table>
									';
			} else {
				$html .= '<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
										<tr>
											<td style="width:30%;"></td>
											<td style="width:40%;"></td>
											<td style="width:30%;"></td>
										</tr>
										</table>
									';
			}
			$html .= '</td>';
			
			$html .= '</tr>';
			$html .= '</table>';
			$html .= '</td>';
			$html .= '</tr>';
			$start = 0;
			$limit = 1;
			foreach ( $customlist ['customlistvalues'] as $listvalues ) {
				
				if ($i == '0') {
					$noteinfo = $this->model_notes_notes->getnotecustomlistvalue2 ( $listvalues ['customlistvalues_id'], $results ['parent_id'], $start, $limit );
				} else {
					$noteinfo = array ();
				}
				
				$html .= '<tr>
					<td colspan="3" style="border:none;">
						<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
						<tr>
							<td style="width:30%;border:none;"><b>' . $listvalues ['customlistvalues_name'] . '</b></td>
							<td style="width:70%;border: none;">';
				
				$html .= '<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" >
										<tr>';
				$html .= '<td style="width:30%;text-align:center;border-top: none;border-bottom: none;">';
				if ($noteinfo ['notetime']) {
					$html .= date ( $timeinfo['time_format'], strtotime ( $noteinfo ['notetime'] ) );
				}
				$html .= '</td>';
				$html .= '<td style="width:40%;text-align:center;border-top: none;border-bottom: none;">';
				
				if ($noteinfo ['task_type'] == '5') {
					$html .= 'Ended ';
				}
				if ($noteinfo ['task_type'] == '4') {
					$html .= '';
				}				
				
				if ($noteinfo ['customlistvalues_id']) {
					
					$customlist_array = explode (",", $noteinfo ['customlistvalues_id']); 
                    
					
					$d = array ();
					$d ['customlistvalues_id'] = $noteinfo ['customlistvalues_id'];					
					$customlist_values = $this->model_notes_notes->getcustomlistvalues ( $d );
								
					$i1 = 0;
					$numItems1 = count ( $customlist_values ) - 1;
					foreach ( $customlist_values as $customlist_value ) {
						
						
						if ($i1 == $numItems1) {
							$html .= $customlist_value ['number'];
						} else {
							$html .= $customlist_value ['number'] . ',';
						}
						
						$i1 ++;
					}
				}
				
				$html .= '</td>';
				$html .= '<td style="width:30%;text-align:center;border-top: none;border-bottom: none;">';
				if ($noteinfo ['notes_pin'] != null && $noteinfo ['notes_pin'] != "") {
					$html .= $noteinfo ['user_id'];
					$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="10px" height="15px">';
				} else if ($noteinfo ['signature']) {
					$html .= $noteinfo ['user_id'];
					$html .= '<img style="text-align: center;" src="' . $noteinfo ['signature'] . '" width="80px" height="25px" style="vertical-align: bottom;">';
				}
				$html .= '</td>';
				$html .= '</tr></table>';
				
				$html .= '</td>';
				
				$html .= '</tr>';
				$html .= '</table>';
				$html .= '</td>';
				$html .= '</tr>';
				
				$start ++;
				$limit ++;
			}
			$i ++;
		}
	
		
		$html .= '<tr>
					<td colspan="3">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:30%;">Other Behavlors Observed: 21.</td>
							<td style="width:70%;text-align:lrft;">';
		$html .= $formdatas [0] [0] ['textarea_81411392'];
		
		$html .= '</td> </tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		if($this->request->get ['forms_design_id']=='252'){
			$html .= '<tr>
			<td colspan="3">
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
			<tr>
			<td style="width:50%;">Shift Supervisor\'s Signature</td>
			<td style="width:20%;text-align:center;">';
		
		if ($formdatas ['signature_41708534'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_41708534'] [0] . '" style="width:98px;height:20px;">';
		}
		    $html .= '</td>
							
							<td style="width:30%;">
								<table border="0">
								
								<tr>
									<td style="border:none;">Date ' . $formdatas [0] [0] ['date_48860525'] . '  </td>
									<td style="border:none;">Time ' . $formdatas [0] [0] ['time_41789102'] . ' </td>
								</tr>
								</table>
							</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';


       $html .= '<tr>
			<td colspan="3">
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
			<tr>
			<td style="width:50%;">Shift Supervisor\'s Signature</td>
			<td style="width:20%;text-align:center;">';
		
		if ($formdatas ['signature_91797709'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_91797709'] [0] . '" style="width:98px;height:20px;">';
		}
		    $html .= '</td>
							
							<td style="width:30%;">
								<table border="0">
								
								<tr>
									<td style="border:none;">Date ' . $formdatas [0] [0] ['date_21302741'] . '  </td>
									<td style="border:none;">Time ' . $formdatas [0] [0] ['time_57605979'] . ' </td>
								</tr>
								</table>
							</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		
		
		$html .= '<tr>
			<td colspan="3">
			<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
			<tr>
			<td style="width:50%;">Shift Supervisor\'s Signature</td>
			<td style="width:20%;text-align:center;">';
		
		if ($formdatas ['signature_30855570'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_30855570'] [0] . '" style="width:98px;height:20px;">';
		}
		    $html .= '</td>
							
							<td style="width:30%;">
								<table border="0">
								
								<tr>
									<td style="border:none;">Date ' . $formdatas [0] [0] ['date_1702720'] . '  </td>
									<td style="border:none;">Time ' . $formdatas [0] [0] ['time_63435261'] . ' </td>
								</tr>
								</table>
							</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';	
			
		}else{
		$html .= '<tr>
					<td colspan="3">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:30%;">Shift Supervisors Signature</td>
							<td style="width:70%;text-align:center;">';
		if ($formdatas ['signature_41708534'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_41708534'] [0] . '" style="width:98px;height:20px;">';
		}
		$html .= '</td>	</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		}
		
		$html .= '<tr>
					<td colspan="3">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:50%;">Mental Health Clinical Staff Person\'s Signature</td>
							<td style="width:20%;text-align:center;">';
		
		if ($formdatas ['signature_91980587'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_91980587'] [0] . '" style="width:98px;height:20px;">';
		}
		$html .= '
							</td>
							
							<td style="width:30%;">
								<table border="0">
								
								<tr>
									<td style="border:none;">Date ' . $formdatas [0] [0] ['date_48860525'] . '  </td>
									<td style="border:none;">Time ' . $formdatas [0] [0] ['time_41789102'] . ' </td>
								</tr>
								</table>
							</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		if($this->request->get ['forms_design_id']=='252'){
			
				
		
		$html .= '<tr><td colspan="3" style="text-align:left;padding:10px;line-heigh:30px;height;70px;"><h4>NOTIFICATION OF WEARING SIGNS</h4> </td></tr>';
		
        $html .= '<tr>
					<td colspan="3">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:100%;">(Document notification of wearing signs and mental health clinical staff person\'s instructions below)</td></tr>
							<tr><td style="width:100%;text-align:left;">';
		$html .= $formdatas [0] [0] ['textarea_3384821'];
		
		$html .= '</td> </tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		
		
		$html .= '<tr>
					<td colspan="3">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:50%;">Staff Signature</td>
							<td style="width:20%;text-align:center;">';
		
		if ($formdatas ['signature_79022780'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_79022780'] [0] . '" style="width:98px;height:20px;">';
		}
		$html .= '
							</td>
							
							<td style="width:30%;">
								<table border="0">
								
								<tr>
									<td style="border:none;">Date ' . $formdatas [0] [0] ['date_13776186'] . '  </td>
									<td style="border:none;">Time ' . $formdatas [0] [0] ['time_87563691'] . ' </td>
								</tr>
								</table>
							</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';
		
		
		$html .= '<tr>
					<td colspan="3">
						<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>
							<td style="width:50%;">Shift Supervisor</td>
							<td style="width:20%;text-align:center;">';
		
		if ($formdatas ['signature_76774634'] [0]) {
			
			$html .= '<img id="upload_icon" src="' . $formdatas ['signature_76774634'] [0] . '" style="width:98px;height:20px;">';
		}
		$html .= '
							</td>
							
							<td style="width:30%;">
								<table border="0">
								
								<tr>
									<td style="border:none;">Date ' . $formdatas [0] [0] ['date_42370453'] . '  </td>
									<td style="border:none;">Time ' . $formdatas [0] [0] ['time_52549513'] . ' </td>
								</tr>
								</table>
							</td>
						
						</tr>';
		$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';

		$html .='<tr>
			<td colspan="3" style="text-align:center;"><h1>Sucide Precautions-Observation Log</h1></td> 
		</tr>';
			
		}else {	
		$html .= '<tr>
					<td colspan="2">Youth\'s Name ' . $formdatas [0] [0] ['text_61453229'] . ' </td>
					<td>JJIS# ' . $formdatas [0] [0] ['text_11305284'] . ' </td>
					
				</tr>';
		$html .='<tr>
					<td colspan="3" style="text-align:center;"><h1>Sucide Precautions-Observation Log</h1></td> 
				</tr>';		
		}


		
		
		
		if ($notess) {
			foreach ( $notess as $note ) {
				$html .= '<tr>
						<td colspan="3">
							<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
							<tr>
								<td style="width:10%;">';
				if ($note ['taskadded'] == "2") {
					$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">';
				}
				
				if ($note ['taskadded'] == "3") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
				}
				
				if ($note ['taskadded'] == "4") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Incomplete:';
				}
				
				$html .= '</td>';
				
				$html .= '<td style="width:70%;text-align:left;">';
				$html .= $note ['notes_description'];
				$html .= '</td>';
				
				$html .= '<td style="width:20%;text-align:left;">';
				if ($note ['username'] != null && $note ['username'] != "0") {
					
					$html .= $note ['username'];
					
					if ($note ['notes_type'] == "2") {
						$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
					} elseif ($note ['notes_type'] == "1") {
						$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
					} elseif ($note ['notes_pin'] != null && $note ['notes_pin'] != "") {
						$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
					} else {
						$html .= '<img src="' . $note ['signature'] . '" style="width:98px;height:20px;">';
					}
					
					$html .= ' <br> ( ' . $note ['note_date'] . ' )';
				}
				$html .= '</td>
							
							</tr>';
				$html .= '</table>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		}
		
		$html .= '</table>';
		
		// echo $html;
		
		// var_dump($html);
		// die;
		// output the HTML content
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// reset pointer to the last page
		$pdf->lastPage ();
		
		// ---------------------------------------------------------
		
		// Close and output PDF document
		// $pdf->Output('example_049.pdf', 'D');
		
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
	}
	
	
	
	public function printmonthly_firredrill() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		$this->load->model ( 'api/permision' );
		$timeinfo = $this->model_api_permision->getcustomerdatetime ( $this->customer->getId () );
		
		$this->data ['fields'] = unserialize ( $fromdatas ['forms_fields'] );
		$this->data ['layouts'] = explode ( ",", $fromdatas ['form_layout'] );
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_image'] = $fromdatas ['display_image'];
		$this->data ['display_signature'] = $fromdatas ['display_signature'];
		$this->data ['forms_setting'] = $fromdatas ['forms_setting'];
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_add_row'] = $fromdatas ['display_add_row'];
		$this->data ['display_content_postion'] = $fromdatas ['display_content_postion'];
		
		$this->data ['facility_name'] = $this->customer->getfacility ();
		
		if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
			
			$results = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
			
			if (! empty ( $results ['design_forms'] )) {
				$formdatas = unserialize ( $results ['design_forms'] );
			}
			
			$this->data ['upload_file'] = $results ['upload_file'];
			$this->data ['form_signature'] = $results ['form_signature'];
			
			$formmedias = $this->model_form_form->getFormmedia ( $this->request->get ['forms_id'] );
			
			$this->data ['formsimages'] = array ();
			$this->data ['formssigns'] = array ();
			
			foreach ( $formmedias as $formmedia ) {
				
				if ($formmedia ['media_type'] == '1') {
					$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
				}
				
				if ($formmedia ['media_type'] == '2') {
					$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
				}
			}
		}
		
		// var_dump($formdatas );
		// die;
		
		$this->document->setTitle ( 'Monthly Fire Drill' );
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		$pdf->SetMargins ( '5', '5', '5' );
		$pdf->SetHeaderMargin ( '5' );
		$pdf->SetFooterMargin ( '5' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';
		$html .= '<style>

		td {
			padding: 10px;
			margin: 10px;
		   border: 1px solid #B8b8b8;
		   line-height:20.2px;
		   display:table-cell;
			padding:5px;
		}
		</style>';
		
		$html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		$html .= '<tr>  
					<td style="padding:20px;text-align:center;width:20%;" ><img src="image/printform/CCYS-Square.png" width="100" height="80"> </td>
					<td style="padding:20px;width:80%;"text-align:center;colspan="2"><h1 style="font-size:18px">' . $this->data ['form_name'] . '</h1></td>
					
				</tr>';
		$html .= '</table>';
		
		$html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		$html .= '<tr>
				<td ></td>
				<td>Date ' . $formdatas ['0'] ['0'] ['date_47631578'] . '</td>
				<td>Time ' . $formdatas ['0'] ['0'] ['text_97600873'] . '</td>
				</tr>
		
		<tr>
			<td colspan="3"><b>Staff Involved :</b> ' . $formdatas ['0'] ['0'] ['text_53509909'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Youth Involved : </b>' . $formdatas ['0'] ['0'] ['text_20586296'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Visitors Involved :</b>  ' . $formdatas ['0'] ['0'] ['text_95290678'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Where was the fire located? :</b>  ' . $formdatas ['0'] ['0'] ['text_13917948'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>General intensity and extent of fire? :</b>  ' . $formdatas ['0'] ['0'] ['text_94415800'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Was the building evacuated in an orderly manner? : </b> ' . $formdatas ['0'] ['0'] ['text_44010338'] . '</td>
				
		</tr>
		
		
		<tr>
			<td colspan="3"><b>What exits were used? :</b>  ' . $formdatas ['0'] ['0'] ['text_86433391'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Were Staff/Visitors/clients in the other building ? : </b>' . $formdatas ['0'] ['0'] ['text_22083547'] . '</td>
				
		</tr>
		
		
		<tr>
			<td colspan="3"><b>If yes, were they notified in a timely manner ? :</b> ' . $formdatas ['0'] ['0'] ['text_2159379'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Time Drill was initiated :</b> ' . $formdatas ['0'] ['0'] ['text_95913805'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Time at completion of evacuation:</b> ' . $formdatas ['0'] ['0'] ['text_51668543'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Were Fire alarm accessible?:</b> ' . $formdatas ['0'] ['0'] ['text_70852716'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Time Drill was completed:</b> ' . $formdatas ['0'] ['0'] ['text_51636855'] . '</td>
				
		</tr>
		
		<tr>
			<td colspan="3"><b>Monitored By: </b>' . $formdatas ['0'] ['0'] ['text_18017922'] . '</td>
				
		</tr>
		
		
		<tr>
			<td colspan="3"><h3 style="text-align:center;">****Pleace completed drill in the Program Services Directors Box****</h3></td>
				
		</tr>
		
		<tr>
			<td colspan="3">
			
				1) Call Georgia/Florida Alarm Company at 224-7900 to notify them of the drill .<br>
				2) Go to a pull station located at one of the exits.<br>
				3) When the alarm sounds, announce that it is a fire drill. Make sure you speak loud enough for every one to hear.<br>
				4) One staff will be responsible for leading the youth outside to a safe location (the parking lot in front of the Family Place building). The staff will carry a fire extinguisher, first-aid kit, blanket and a cell phone.<br>
				5) One staff will be responsible for gathering all the youth files, the log book, and youth medications. All of this is to be placed black briefcases underneath the staff station.<br>
				6) One staff will be responsible for checking all rooms to ensure that all the youths have been safely evacuated.<br>
				7) Once everyone is outside, staff will be conduct a head count and visually account for each youth.<br>
				8) Once the head count is completed, the drill is over and everyone may return to the building<br>
				9) Reset the alarm system by re locking the pull station (key is on the staff key rings) and disarming them arming the fire systems (code is 2407)<br>
				10) Call Georgia/Florida Alarm and let them know the drill is over<br>
				11) Make a note in the log book<br>
				12) Fill out the reverse side of this form and place it in the Program Services Directors mailbox in the FP building.<br>
			
			
			
			</td>
				
		</tr>
		
		';
		
		$html .= '</table>';
		// echo $html;
		
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		$pdf->lastPage ();
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
	}
	public function printincidentform() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		$this->load->model ( 'api/permision' );
		$timeinfo = $this->model_api_permision->getcustomerdatetime ( $this->customer->getId () );
		
		$this->data ['fields'] = unserialize ( $fromdatas ['forms_fields'] );
		$this->data ['layouts'] = explode ( ",", $fromdatas ['form_layout'] );
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_image'] = $fromdatas ['display_image'];
		$this->data ['display_signature'] = $fromdatas ['display_signature'];
		$this->data ['forms_setting'] = $fromdatas ['forms_setting'];
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_add_row'] = $fromdatas ['display_add_row'];
		$this->data ['display_content_postion'] = $fromdatas ['display_content_postion'];
		
		$this->data ['facility_name'] = $this->customer->getfacility ();
		
		if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
			
			$results = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
			
			if (! empty ( $results ['design_forms'] )) {
				$formdatas = unserialize ( $results ['design_forms'] );
			}
			
			$this->data ['upload_file'] = $results ['upload_file'];
			$this->data ['form_signature'] = $results ['form_signature'];
			
			$formmedias = $this->model_form_form->getFormmedia ( $this->request->get ['forms_id'] );
			
			$this->data ['formsimages'] = array ();
			$this->data ['formssigns'] = array ();
			
			foreach ( $formmedias as $formmedia ) {
				
				if ($formmedia ['media_type'] == '1') {
					$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
				}
				
				if ($formmedia ['media_type'] == '2') {
					$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
				}
			}
		}
		
		// var_dump($formdatas );
		// die;
		
		$this->document->setTitle ( 'Incident Form' );
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		$pdf->SetMargins ( '5', '5', '5' );
		$pdf->SetHeaderMargin ( '5' );
		$pdf->SetFooterMargin ( '5' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';
		$html .= '<style>

		td {
			padding: 10px;
			margin: 10px;
		   border: 1px solid #B8b8b8;
		   line-height:20.2px;
		   display:table-cell;
			padding:5px;
		}
		</style>';
		
		// for($current_row=0;$i<;$i++){
		
		$html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		$html .= '<tr>  
					<td style="padding:20px;text-align:center;width:20%;" ><img src="image/printform/FNYFS.jpg" width="100" height="80"> </td>
					<td style="padding:20px;width:80%;"text-align:center;colspan="2">
					<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
					<tr>
					
					<td>Capital City Youth Services</td>
					<td>Date of Incident ' . $formdatas ['0'] ['0'] ['date_60257844'] . '</td>
					</tr>
				
					<tr>
						<td>Internal Incident Report</td>
						<td>Time of Incident ' . $formdatas ['0'] ['0'] ['time_19401882'] . '</td>
					</tr>
					
					<tr>
						<td></td>
						<td>Staff Reporting ' . $formdatas ['0'] ['0'] ['text_92330461'] . '</td>
					</tr>
					
					</table>
					</td>
					
				</tr>';
		$html .= '</table>';
		
		$html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		$html .= '<tr><td colspan="4"><h3>Type Of Incident</h3></td></tr>';
		$html .= '			
			<tr>
				<td>Client Conduct</td>
				<td>Staff Conduct</td>
				<td>Health and Safety</td>
				<td>CCYS Property</td>
			</tr>
			
			
			<tr>
				<td>';
		
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Runaway<br>';
		
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Property destruction<br>';
		
		if ($formdatas ['0'] ['0'] ['2'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Physical Voilence<br>';
		
		if ($formdatas ['0'] ['0'] ['3'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Sexual Activity<br>';
		
		if ($formdatas ['0'] ['0'] ['4'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Drug/Alcohol possession<br>';
		
		if ($formdatas ['0'] ['0'] ['5'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Weapon possession<br>';
		
		if ($formdatas ['0'] ['0'] ['6'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'L/E Assistance Needed<br>';
		
		if ($formdatas ['0'] ['0'] ['7'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Client arrested / detained<br>';
		
		if ($formdatas ['0'] ['0'] ['8'] ['checkbox_57769606']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Other<br>
				</td>
				
				
				
				<td>';
		
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_99829183']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'On-duty staff aggression<br>';
		
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_99829183']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'On-duty alcohol/drug-use<br>';
		
		if ($formdatas ['0'] ['0'] ['2'] ['checkbox_99829183']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Staff-on-client abuse /allegation<br>';
		
		if ($formdatas ['0'] ['0'] ['3'] ['checkbox_99829183']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Other<br>';
		
		$html .= '</td><td>';
		
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Baker Act assessment<br>';
		
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Baker Act admission<br>';
		
		if ($formdatas ['0'] ['0'] ['2'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Abuse report<br>';
		if ($formdatas ['0'] ['0'] ['3'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Client injury/illness<br>';
		if ($formdatas ['0'] ['0'] ['4'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Staff injury/illness<br>';
		if ($formdatas ['0'] ['0'] ['5'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Infectious disease<br>';
		if ($formdatas ['0'] ['0'] ['6'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'CCYS facility Fire<br>';
		if ($formdatas ['0'] ['0'] ['7'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Vehicle accident<br>';
		if ($formdatas ['0'] ['0'] ['8'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Disaster/evacuation<br>';
		if ($formdatas ['0'] ['0'] ['9'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= '	Death of client<br>';
		
		if ($formdatas ['0'] ['0'] ['10'] ['checkbox_62185093']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Death of staff on duty<br></td>
				
				<td>';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_10325757']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Furnishings damaged<br>';
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_10325757']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Structural damaged<br>';
		
		if ($formdatas ['0'] ['0'] ['2'] ['checkbox_10325757']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Suspected theft<br>
				</td>
				
			</tr>
			
			<tr><td colspan="4"><h3>Notifications(See appropriate procedure for required notifications)</h3></td></tr>
			
				<tr>
				<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
				<tr>
					<td>CCYS</td>
					<td>Date</td>
					<td>Time</td>
					<td>Staff Name</td>
					<td>Notes</td>
					
				</tr>
				
				<tr>
					<td>';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_80865693']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= ' On Call</td>
					<td>' . $formdatas ['0'] ['0'] ['date_19352986'] . '</td>
					<td>' . $formdatas ['0'] ['0'] ['time_30039377'] . '</td>
					<td>' . $formdatas ['0'] ['0'] ['text_90219582'] . ' </td>
					<td>' . $formdatas ['0'] ['0'] ['textarea_10368426'] . '</td>
					
				</tr>
				
				<tr>
					<td>';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_76533485']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= ' Supervisor/Back Up</td>
					<td>' . $formdatas ['0'] ['0'] ['date_75383936`'] . '</td>
					<td>' . $formdatas ['0'] ['0'] ['time_7297816'] . '</td>
					<td>' . $formdatas ['0'] ['0'] ['text_49734228'] . ' </td>
					<td>' . $formdatas ['0'] ['0'] ['textarea_55649387'] . '</td>
					
				</tr>
				
				
				<tr>
					<td>';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_21263350']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= ' COO/CEO</td>
					<td>' . $formdatas ['0'] ['0'] ['date_45008963'] . '</td>
					<td>' . $formdatas ['0'] ['0'] ['time_59738951'] . '</td>
					<td>' . $formdatas ['0'] ['0'] ['text_89978452'] . ' </td>
					<td>' . $formdatas ['0'] ['0'] ['textarea_10141029'] . '</td>
					
				</tr>
				</table>
			</tr>
			
			<tr>
					
					<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
					<tr>
						<td>Law Enf.</td>
						<td>Date</td>
						<td>Time</td>
						<td>Staff Name</td>
						<td>Notes</td>
						
					</tr>
					
					<tr>
						<td>';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_8986278']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= ' TPD</td>
						<td>' . $formdatas ['0'] ['0'] ['date_43719764'] . '</td>
						<td>' . $formdatas ['0'] ['0'] ['time_41796639'] . '</td>
						<td>' . $formdatas ['0'] ['0'] ['text_68170638'] . ' </td>
						<td></td>
						
					</tr>
					
					<tr>
						<td>';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_47380733']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= ' LCSO</td>
						<td>' . $formdatas ['0'] ['0'] ['date_26624553'] . '</td>
						<td>' . $formdatas ['0'] ['0'] ['time_6202752'] . '</td>
						<td>' . $formdatas ['0'] ['0'] ['text_52342571'] . ' </td>
						<td></td>
						
					</tr>
					
					<tr>
						<td colspan="5" >Case # :' . $formdatas ['0'] ['0'] ['text_61686664'] . ' </td>
						
					</tr>
					
					
					</table>
				</tr>
			
			<tr><td colspan="5"><h3>Parent/Guardian/Case manager </h3></td></tr>
			
				<tr>
						<td colspan="2">Name : ' . $formdatas ['0'] ['0'] ['text_88290508'] . '</td>
						<td>Date :' . $formdatas ['0'] ['0'] ['date_72049024'] . ' </td>
						<td>Time :' . $formdatas ['0'] ['0'] ['time_65862881'] . ' </td>
						<td>Staff : ' . $formdatas ['0'] ['0'] ['text_2396592'] . '</td>
						
				</tr>
				
				<tr>
						<td colspan="2">Name : ' . $formdatas ['0'] ['0'] ['text_50651097'] . '</td>
						<td>Date :' . $formdatas ['0'] ['0'] ['date_25785616'] . ' </td>
						<td>Time :' . $formdatas ['0'] ['0'] ['time_54440642'] . ' </td>
						<td>Staff : ' . $formdatas ['0'] ['0'] ['text_11048413'] . '</td>
						
				</tr>
				
				
				<tr>
						<td colspan="2">Name : ' . $formdatas ['0'] ['0'] ['text_92187629'] . '</td>
						<td>Date :' . $formdatas ['0'] ['0'] ['text_45790400'] . ' </td>
						<td>Time :' . $formdatas ['0'] ['0'] ['text_10281815'] . ' </td>
						<td>Staff : ' . $formdatas ['0'] ['0'] ['text_36680579'] . '</td>
						
				</tr>
				<tr>
						<td colspan="2">Name : </td>
						<td>Date :</td>
						<td>Time : </td>
						<td>Staff : </td>
						
				</tr>
				
				<tr>
						<td colspan="2">Name : </td>
						<td>Date : </td>
						<td>Time : </td>
						<td>Staff : </td>
						
				</tr>
				
				<tr><td  colspan="5" style="height:50px"></td></tr>
			
			<tr><td colspan="5"><h3>External Agency Report Check if no external report is required. <img src="image/printform/box.png" height="15" width="15"> </h3></td></tr>
			
			
			<tr>
				<td colspan="2">';
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_4938407']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'CCC</td>
				<td colspan="2">';
		
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_4938407']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'DCF Abuse Hotline </td>
				<td>';
		
		if ($formdatas ['0'] ['0'] ['2'] ['checkbox_4938407']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Other </td>
			</tr>
			
			<tr>
				<td colspan="2">Phone Notification Date/Time </td>
				<td colspan="2">Phone Notification Date/Time </td>
				<td>Phone Notification Date/Time </td>
			</tr>	

			<tr>
				<td colspan="2">With Whom did you speak </td>
				<td colspan="2">With Whom did you speak </td>
				<td>With Whom did you speak(if applicable) </td>
			</tr>	
			
			<tr>
				<td colspan="2">Staff Making Contact : </td>
				<td colspan="2">Staff Making Contact : </td>
				<td>Staff Making Contact : </td>
			</tr>	
			
			
			<tr>
				<td colspan="2">Case # : </td>
				<td colspan="2">Fax Notification Date (if applicable) : </td>
				<td>Written Notification Date (if applicable) : </td>
			</tr>


			<tr>
				<td colspan="2"> </td>
				<td colspan="2">Case # : </td>
				<td> </td>
			</tr>			
			
			
			<tr><td colspan="5"><h3>Persons Involved (Y = Youth; S=Staff; O=Other) </h3></td></tr>
			<tr>
				<td colspan="2"> Name:</td>
				<td> Type(Y,S or O): </td>
				<td> Name:</td>
				<td> Type(Y,S or O): </td>
			</tr>
			
			<tr>
				<td colspan="2">' . $formdatas ['0'] ['0'] ['text_3288992'] . '</td>
				<td> ' . $formdatas ['0'] ['0'] ['select_22645875'] . ' </td>
				<td> ' . $formdatas ['0'] ['0'] ['text_35125013'] . '</td>
				<td> ' . $formdatas ['0'] ['0'] ['select_6360437'] . '</td>
			</tr>
			
			
			
			<tr><td colspan="5"><h3>Description Of Incident: (Place signature and job title after narrative) </h3></td></tr>
			<tr><td colspan="5">' . $formdatas ['0'] ['0'] ['textarea_74157311'] . '</td></tr>
			
				
			<tr><td colspan="5">';
		
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_34145360']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15">';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= ' Check here if narrative continues on another page</td></tr>
			<tr><td colspan="5">';
		
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_34145360']) {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		
		$html .= 'Check here if additional information is attached</td></tr>
			
			<tr><td colspan="5"><h3>Reviewed By </h3></td></tr>
			
			<tr><td colspan="2">Clinical Director ' . $formdatas ['0'] ['0'] ['text_77749804'] . '</td><td colspan="2">Date ' . $formdatas ['date_93680381'] . ' </td><td >Time  ' . $formdatas ['0'] ['0'] ['time_95838365'] . '</td></tr>
			<tr><td colspan="2">COO/CEO: ' . $formdatas ['0'] ['0'] ['text_77915647'] . '</td><td colspan="2">Date ' . $formdatas ['0'] ['0'] ['date_34199915'] . ' </td><td >Time ' . $formdatas ['0'] ['0'] ['time_40614768'] . ' </td></tr>
			
			
			
			<tr><td colspan="5"><h3>Corrective Action</h3><img src="image/printform/box.png" height="15" width="15"> Check if no corrective action is needed </td></tr>
			<tr><td colspan="5">' . $formdatas ['0'] ['0'] ['textarea73'] . ' </td></tr>
			';
		
		$html .= '</table>';
		
		// echo $html;
		
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		$pdf->lastPage ();
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
	}
	public function printintakeform() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		$this->load->model ( 'api/permision' );
		$timeinfo = $this->model_api_permision->getcustomerdatetime ( $this->customer->getId () );
		
		$this->data ['fields'] = unserialize ( $fromdatas ['forms_fields'] );
		$this->data ['layouts'] = explode ( ",", $fromdatas ['form_layout'] );
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_image'] = $fromdatas ['display_image'];
		$this->data ['display_signature'] = $fromdatas ['display_signature'];
		$this->data ['forms_setting'] = $fromdatas ['forms_setting'];
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_add_row'] = $fromdatas ['display_add_row'];
		$this->data ['display_content_postion'] = $fromdatas ['display_content_postion'];
		
		$this->data ['facility_name'] = $this->customer->getfacility ();
		
		if ($this->request->get ['forms_id'] != "" && $this->request->get ['forms_id'] != NULL) {
			
			$results = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
			
			if (! empty ( $results ['design_forms'] )) {
				$formdatas = unserialize ( $results ['design_forms'] );
			}
			
			$this->data ['upload_file'] = $results ['upload_file'];
			$this->data ['form_signature'] = $results ['form_signature'];
			$date_added = date ( 'm-d-Y', strtotime ( $results ['date_added'] ) );
			
			$formmedias = $this->model_form_form->getFormmedia ( $this->request->get ['forms_id'] );
			
			$this->data ['formsimages'] = array ();
			$this->data ['formssigns'] = array ();
			
			foreach ( $formmedias as $formmedia ) {
				
				if ($formmedia ['media_type'] == '1') {
					$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
				}
				
				if ($formmedia ['media_type'] == '2') {
					$formdatas [$formmedia ['media_name']] = $formmedia ['media_url'];
				}
			}
		}
		
		// var_dump($formdatas );
		// die;
		
		$this->document->setTitle ( 'Incident Form' );
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		$pdf->SetMargins ( '5', '5', '5' );
		$pdf->SetHeaderMargin ( '5' );
		$pdf->SetFooterMargin ( '5' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';
		$html .= '<style>

		td {
			padding: 10px;
			margin: 10px;
		   border: 1px solid #B8b8b8;
		   line-height:20.2px;
		   display:table-cell;
			padding:5px;
		}
		</style>';
		
		$html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
		$html .= '<tr>  
					<td style="padding:20px;text-align:center;width:100%;" > ARNETTE HOUSE, INC.</td>
				</tr>
				
				<tr>  
					<td style="padding:20px;text-align:center;width:100%;" > CENTRAL INTAKE REFERRAL/SCREENING/ASSESSMENT FORM</td>
				</tr>
				
				<tr>  
					
					<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr>  
							<td colspan="2">Person Completing Screening: ' . $formdatas ['0'] ['0'] ['text_59815482'] . ' ' . $formdatas ['0'] ['0'] ['text_2637670'] . '</td>
							<td>NETMIS# ' . $formdatas ['0'] ['0'] ['text_92710969'] . '</td>
						</tr>
						
						<tr>  
							<td>Date of Screening: ' . $date_added . '</td>
							<td>Time: ' . $formdatas ['0'] ['0'] ['time_62234190'] . '</td>
							<td>Length of Contact (Minutes): ' . $formdatas ['0'] ['0'] ['text_83763747'] . '</td>
						</tr>
						
						<tr>  
							<td colspan="2">Location: ' . $formdatas ['0'] ['0'] ['text_75675662'] . '</td>
							<td>Type of Contact: ' . $formdatas ['0'] ['0'] ['text_55744670'] . '</td>
						</tr>
					
						<tr>  
							<td>Name of Initial Contact: ' . $formdatas ['0'] ['0'] ['text_53729098'] . '</td>
							<td>Phone: ' . $formdatas ['0'] ['0'] ['text_84980038'] . '</td>
							<td>Relationship to Youth: ' . $formdatas ['0'] ['0'] ['text_46958046'] . '</td>
						</tr>
						
						<tr>  
							<td>Full Name: ' . $formdatas ['0'] ['0'] ['text_59815482'] . ' ' . $formdatas ['0'] ['0'] ['text_2637670'] . '</td>
							<td>DOB: ' . $formdatas ['0'] ['0'] ['date_70767270'] . '</td>
							<td>Age: ' . $formdatas ['0'] ['0'] ['text_50839890'] . '</td>
						</tr>
						
						<tr>  
							<td colspan="2">Address: ' . $formdatas ['0'] ['0'] ['text_67156164'] . '</td>
							<td>City: ' . $formdatas ['0'] ['0'] ['text_36668004'] . '</td>
						</tr>
						
						<tr>  
							<td>County:  ' . $formdatas ['0'] ['0'] ['text_21009644'] . '</td>
							<td>State:  ' . $formdatas ['0'] ['0'] ['text_49932949'] . '</td>
							<td>Zip:   ' . $formdatas ['0'] ['0'] ['text_64928499'] . '</td>
							<td>Home Phone:  ' . $formdatas ['0'] ['0'] ['text_84543006'] . '</td>
						</tr>
						
						
						<tr>  
							<td>SSN:  ' . $formdatas ['0'] ['0'] ['text_59058963'] . ' </td>
							<td>Gender:  ' . $formdatas ['0'] ['0'] ['select_40322663'] . '</td>
							<td>Cell Phone: ' . $formdatas ['0'] ['0'] ['text_51535050'] . '</td>
						</tr>
						
						<tr>  
							
							<td colspan="4">Work Phone: ' . $formdatas ['0'] ['0'] ['text_42773086'] . '</td>
						</tr>
						
						<tr><td colspan="5"><h3>Check ONE of each( Race, Ethnicity): </h3></td></tr>
						
						<tr>  
							<td colspan="2">Race: ' . $formdatas ['0'] ['0'] ['text_51895652'] . '</td>
							<td>Religion: ' . $formdatas ['0'] ['0'] ['text_42470829'] . ' </td>
						</tr>
						
						<tr>  
							<td colspan="4">Ethnicity: ' . $formdatas ['0'] ['0'] ['text_17812904'] . '</td>
						</tr>
						
						<tr> 
							<td >Has the youth ever been arrested? </td>
							<td >
							<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
										<td style="width:10%;border:none;">';
		if ($formdatas ['0'] ['0'] ['select_95836820'] == 'Yes') {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:15%;border:none;">Yes</td>
										<td style="width:10%;border:none;">';
		if ($formdatas ['0'] ['0'] ['select_95836820'] == 'No') {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:15%;border:none;">No</td>
									</tr>
								</table>
								
							</td>
							<td >Charges: ' . $formdatas ['0'] ['0'] ['text_20130138'] . '</td>
						</tr>
						
						
						<tr>  
							<td colspan="2">Gang Affiliation: ' . $formdatas ['0'] ['0'] ['text_32356098'] . '</td>
							<td>Free/Reduced or full Price Meals @ School ' . $formdatas ['0'] ['0'] ['text_82597587'] . '</td>
						</tr>
						
										
						<tr>  
							<td >School: ' . $formdatas ['0'] ['0'] ['text_56208729'] . '</td>
							<td >School Status/Program: ' . $formdatas ['0'] ['0'] ['text_6675508'] . '</td>
							<td >Grade:' . $formdatas ['0'] ['0'] ['text_17832586'] . '</td>
						</tr>
						
						<tr>  
							<td colspan="2">Language Spoken: ' . $formdatas ['0'] ['0'] ['text_100145759'] . '</td>
							<td>
							<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
									<tr>
									<td style="width:50%;border:none;">Youth Employed?</td>
										<td style="width:10%;border:none;">';
		if ($formdatas ['0'] ['0'] ['select_84653087'] == 'Yes') {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:15%;border:none;">Yes</td>
										<td style="width:10%;border:none;">';
		if ($formdatas ['0'] ['0'] ['select_84653087'] == 'No') {
			$html .= '<img src="image/printform/box_cross.png"> ';
		} else {
			$html .= '<img src="image/printform/box.png"> ';
		}
		$html .= '</td>
										<td style="width:15%;border:none;">No</td>
									</tr>
								</table>
							</td>
						</tr>
						
						
						<tr>  
							<td colspan="2">Place of Birth:</td>
							<td>(City, County and State) ' . $formdatas ['0'] ['0'] ['text_78360494'] . '</td>
						</tr>
						
						<tr>  
							<td colspan="4">Legal Guardians Name ' . $formdatas ['0'] ['0'] ['text_435952'] . '</td>
							
						</tr>
						
						<tr>  
							<td colspan="4">Relationship to Youth:' . $formdatas ['0'] ['0'] ['text_16374924'] . '</td>
							
						</tr>
						
						
						<tr>  
							<td colspan="2">Emergency Contact: (If DCF Supv. Name) ' . $formdatas ['0'] ['0'] ['text_27971973'] . '</td>
							<td>Phone# ' . $formdatas ['0'] ['0'] ['text_8807453'] . '</td>
						</tr>
					</table>
					
					
					
					
				</tr>
				
				<tr><td colspan="5"><h3>PRESENTING PROBLEMS:</h3></td></tr>
				
				
				<tr>
				
					<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >
						<tr><td colspan="2">';
		
		if ($formdatas ['0'] ['0'] ['0'] ['checkbox_87737950']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Runaway/Potential Runaway</td>

						
						<td>';
		
		if ($formdatas ['0'] ['0'] ['1'] ['checkbox_87737950']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Truancy</td>
						
						<td>';
		
		if ($formdatas ['0'] ['0'] ['2'] ['checkbox_87737950']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'Anger</td></tr>
						
						<tr><td colspan="2">';
		
		if ($formdatas ['0'] ['0'] ['3'] ['checkbox_87737950']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Depression</td><td>';
		
		if ($formdatas ['0'] ['0'] ['4'] ['checkbox_87737950']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		
		$html .= 'School Related Issues</td>
						
						<td>';
		if ($formdatas ['0'] ['0'] ['5'] ['checkbox_87737950']) {
			$html .= '<img src="image/printform/box_cross.png" width="15" height="15"> ';
		} else {
			$html .= '<img src="image/printform/box.png" width="15" height="15"> ';
		}
		$html .= 'Fire Starting</td></tr>
						
						
						
						
					</table>
				</tr>
				
				<tr><td colspan="5"><h3>DESCRIPTION OF PROBLEMS:</h3></td></tr>
				
				<tr>
				
				 ' . $formdatas ['0'] ['0'] ['textarea_29326672'] . '
				
				</tr>
				
				</table>	
				</tr>';
		
		$html .= '</table>';
		
		// var_dump($html);die;
		// echo $html;
		
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		$pdf->lastPage ();
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
	}
	public function insert() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			
			if ($this->request->post ['top_submit'] != '6') {
				
				if ($this->request->post ['link_forms_id'] != null && $this->request->post ['link_forms_id'] != "") {
					$this->session->data ['link_forms_id'] = $this->request->post ['link_forms_id'];
				}
				
				// var_dump($this->request->post);
				
				// die;
				// echo "<hr>";
				$results = $this->model_form_form->getFormDatasexit ( $this->request->get ['forms_design_id'], $this->request->get ['formreturn_id'] );
				
				$this->data ['formdatas'] = $this->request->post ['design_forms'];
				
				foreach ( $this->data ['formdatas'] as $key1 => $vals ) {
					
					// var_dump($key1);
					
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
				// var_dump($search_facilities_id);die;
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				if ($form_facilities_id != null && $form_facilities_id != "") {
					$facilities_id = $form_facilities_id;
				} else {
					if ($facilities_info ['is_master_facility'] == '1') {
						if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
							$facilities_id = $this->session->data ['search_facilities_id'];
						} else {
							$facilities_id = $this->customer->getId ();
						}
					} else {
						$facilities_id = $this->customer->getId ();
					}
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
							$this->model_resident_resident->addformmedicine ( $addmed, $this->request->get ['tags_id'] );
						}
					}
					
					$this->load->model ( 'setting/tags' );
					
					if ($vals ['linktype'] == '1') {
						
						$tags_info12 = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
						
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
						$addtaskw ['emp_tag_id'] = $this->request->get ['tags_id'];
						
						$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskw, $facilities_id );
					}
				}
				
				$editdata = array ();
				
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
						$data2 ['facilities_id'] = $facilities_id;
						
						$formreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
					}
				}
				
				if ($this->request->get ['updatenotes_id'] == "" && $this->request->get ['updatenotes_id'] == null) {
					// $this->session->data['formsids'][] = $formreturn_id;
					
					$url2 = "";
					$url4 = '';
					
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
					}
					if ($this->request->post ['client_add_new'] != null && $this->request->post ['client_add_new'] != "") {
						$url2 .= '&client_add_new=' . $this->request->post ['client_add_new'];
						$url4 .= '&client_add_new=' . $this->request->post ['client_add_new'];
					}
					
					if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
						$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
					}
					
					if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
						$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
						$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
					}
					if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
						$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
						$url4 .= '&last_notesID=' . $this->request->get ['last_notesID'];
					}
					if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
						$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
						$url4 .= '&searchdate=' . $this->request->get ['searchdate'];
					}
					
					if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
						$url2 .= '&task_id=' . $this->request->get ['task_id'];
						$url4 .= '&task_id=' . $this->request->get ['task_id'];
					}
					
					if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
						$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
						$url4 .= '&activeform_id=' . $this->request->get ['activeform_id'];
					}
					
					if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
						$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
						$url4 .= '&tagsids=' . $this->request->get ['tagsids'];
					}
					
					if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
						$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
						$url4 .= '&facilityids=' . $this->request->get ['facilityids'];
					}
					if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
						$url2 .= '&locationids=' . $this->request->get ['locationids'];
						$url4 .= '&locationids=' . $this->request->get ['locationids'];
					}
					
					if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
						$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
						$url4 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
					}
					
					// var_dump($this->request->get);
					
					if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
						
						$formdata = $this->model_form_form->getFormDatadesign ( $this->request->get ['forms_design_id'] );
						
						// var_dump($this->request->get['page_number']);
						// var_dump($formdata['page_number']);
						
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
									$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
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
									
									$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
									
									// $this->session->data['success2'] = 'Form Created successfully!';
									
									// die;
								}
							} else {
								$this->session->data ['success2'] = 'Form Created successfully!';
								
								if (isset ( $this->request->post ['is_final'] ) && $this->request->post ['is_final'] == 1) {
									
									$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
									
									if ($fromdatas ['mark_final'] == 3 || $fromdatas ['mark_final'] == 1 || $fromdatas ['mark_final'] == 4 || $fromdatas ['mark_final'] == 5) {
										
										$url2 .= '&is_formsecurity=' . $fromdatas ['mark_final'];
										$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
										$url2 .= '&is_mark_final=1';
										$url2 .= '&forms=1';
										
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										
										if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
											$url2 .= '&forms=1';
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
										} else {
											// $this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('form/form/newformsign', '' . $url2, 'SSL'));
											
											$this->load->model ( 'setting/activeforms' );
											$formexist = $this->model_setting_activeforms->getactiveform ( $this->request->get ['activeform_id'] );
											
											if (empty ( $formexist )) {
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/newformsign', '' . $url2, 'SSL' ) );
											} else {
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/activeformsign', '' . $url2, 'SSL' ) );
											}
										}
									}
								} else {
									
									if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
										$url2 .= '&forms=1';
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										// $this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('form/form/newformsign', '' . $url2, 'SSL'));
										
										$this->load->model ( 'setting/activeforms' );
										$formexist = $this->model_setting_activeforms->getactiveform ( $this->request->get ['activeform_id'] );
										
										if (empty ( $formexist )) {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/newformsign', '' . $url2, 'SSL' ) );
										} else {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/activeformsign', '' . $url2, 'SSL' ) );
										}
									}
								}
							}
						} else {
							// var_dump($this->request->post['jump_forms_id']);
							// var_dump($this->request->post['jump_page_number']);
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
										
										$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
									} else {
										
										if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
											$url4 .= '&parent_id=' . $childform ['parent_id'];
										}
										
										$url4 .= '&page_number=' . $this->request->post ['jump_page_number'];
										$url4 .= '&forms_design_id=' . $this->request->post ['jump_forms_id'];
										$url4 .= '&formreturn_id=' . $this->request->post ['jump_formreturn_id'];
										
										$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
									}
								}
							} else {
								$this->session->data ['success2'] = 'Form Created successfully!';
								
								if (isset ( $this->request->post ['is_final'] ) && $this->request->post ['is_final'] == 1) {
									
									$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
									
									if ($fromdatas ['mark_final'] == 3 || $fromdatas ['mark_final'] == 1 || $fromdatas ['mark_final'] == 4 || $fromdatas ['mark_final'] == 5) {
										
										$url2 .= '&is_formsecurity=' . $fromdatas ['mark_final'];
										$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
										$url2 .= '&is_mark_final=1';
										$url2 .= '&forms=1';
										
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
											$url2 .= '&forms=1';
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
										} else {
											// $this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('form/form/newformsign', '' . $url2, 'SSL'));
											
											$this->load->model ( 'setting/activeforms' );
											$formexist = $this->model_setting_activeforms->getactiveform ( $this->request->get ['activeform_id'] );
											
											if (empty ( $formexist )) {
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/newformsign', '' . $url2, 'SSL' ) );
											} else {
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/activeformsign', '' . $url2, 'SSL' ) );
											}
										}
									}
								} else {
									if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
										$url2 .= '&forms=1';
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										// $this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('form/form/newformsign', '' . $url2, 'SSL'));
										
										$this->load->model ( 'setting/activeforms' );
										$formexist = $this->model_setting_activeforms->getactiveform ( $this->request->get ['activeform_id'] );
										
										if (empty ( $formexist )) {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/newformsign', '' . $url2, 'SSL' ) );
										} else {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/activeformsign', '' . $url2, 'SSL' ) );
										}
									}
								}
							}
						}
					}
				} else {
					
					$url2 = "";
					$url4 = "";
					
					if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
						$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
						$url4 .= '&forms_id=' . $this->request->get ['forms_id'];
					}
					
					if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
						$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
						$url4 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
					}
					
					if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
						$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
					}
					
					if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
						$url2 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
						$url4 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
					}
					
					if ($this->request->post ['exittags_id'] != null && $this->request->post ['exittags_id'] != "") {
						$url2 .= '&exittags_id=' . $this->request->post ['exittags_id'];
						$url4 .= '&exittags_id=' . $this->request->post ['exittags_id'];
					}
					
					if ($this->request->post ['client_add_new'] != null && $this->request->post ['client_add_new'] != "") {
						$url2 .= '&client_add_new=' . $this->request->post ['client_add_new'];
						$url4 .= '&client_add_new=' . $this->request->post ['client_add_new'];
					}
					
					if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
						$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
						$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
					}
					
					if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
						$url2 .= '&task_id=' . $this->request->get ['task_id'];
						$url4 .= '&task_id=' . $this->request->get ['task_id'];
					}
					if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
						$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
						$url4 .= '&last_notesID=' . $this->request->get ['last_notesID'];
					}
					if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
						$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
						$url4 .= '&searchdate=' . $this->request->get ['searchdate'];
					}
					
					if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
						$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
						$url4 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
					} else {
						$url2 .= '&formreturn_id=' . $formreturn_id;
						$url4 .= '&formreturn_id=' . $formreturn_id;
					}
					
					if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
						$urlf2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
						$url4 .= '&activeform_id=' . $this->request->get ['activeform_id'];
					}
					
					if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
						$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
						$url4 .= '&tagsids=' . $this->request->get ['tagsids'];
					}
					
					if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
						$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
						$url4 .= '&facilityids=' . $this->request->get ['facilityids'];
					}
					if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
						$url2 .= '&locationids=' . $this->request->get ['locationids'];
						$url4 .= '&locationids=' . $this->request->get ['locationids'];
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
						// var_dump($formdata['parent_id']);
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
									
									$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
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
									
									$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
								}
							} else {
								
								$this->session->data ['success2'] = 'Form Created successfully!';
								
								if (isset ( $this->request->post ['is_final'] ) && $this->request->post ['is_final'] == 1) {
									
									$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
									
									if ($fromdatas ['mark_final'] == 3 || $fromdatas ['mark_final'] == 1 || $fromdatas ['mark_final'] == 4 || $fromdatas ['mark_final'] == 5) {
										
										$url2 .= '&is_formsecurity=' . $fromdatas ['mark_final'];
										$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
										$url2 .= '&is_mark_final=1';
										$url2 .= '&forms=2';
										
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
											if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
												$url2 .= '&forms=2';
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
											} else {
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
											}
										} else {
											// $url2 .= '&formreturn_id=' . $formreturn_id;
											if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
												$url2 .= '&forms=3';
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
											} else {
												$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert3', '' . $url2, 'SSL' ) );
											}
										}
									}
								} else {
									if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
										if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
											$url2 .= '&forms=2';
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
										} else {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
										}
									} else {
										// $url2 .= '&formreturn_id=' . $formreturn_id;
										if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
											$url2 .= '&forms=3';
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
										} else {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert3', '' . $url2, 'SSL' ) );
										}
									}
								}
							}
						} else {
							$this->session->data ['success2'] = 'Form Created successfully!';
							
							if (isset ( $this->request->post ['is_final'] ) && $this->request->post ['is_final'] == 1) {
								
								$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
								
								if ($fromdatas ['mark_final'] == 3 || $fromdatas ['mark_final'] == 1 || $fromdatas ['mark_final'] == 4 || $fromdatas ['mark_final'] == 5) {
									
									$url2 .= '&is_formsecurity=' . $fromdatas ['mark_final'];
									$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
									$url2 .= '&is_mark_final=1';
									$url2 .= '&forms=2';
									
									$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
								} else {
									if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
										if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
											$url2 .= '&forms=2';
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
										} else {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
										}
									} else {
										// $url2 .= '&formreturn_id=' . $formreturn_id;
										if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
											$url2 .= '&forms=3';
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
										} else {
											$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert3', '' . $url2, 'SSL' ) );
										}
									}
								}
							} else {
								if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
									if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
										$url2 .= '&forms=2';
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
									}
								} else {
									// $url2 .= '&formreturn_id=' . $formreturn_id;
									if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
										$url2 .= '&forms=3';
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
									} else {
										$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert3', '' . $url2, 'SSL' ) );
									}
								}
							}
						}
					}
					
					/*
					 * if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
					 *
					 * $this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('form/form/insert2', '' . $url2, 'SSL'));
					 *
					 * }else{
					 *
					 * //$this->session->data['formreturn_id'] = $formreturn_id;
					 *
					 * //if ($this->session->data['formreturn_id'] != null && $this->session->data['formreturn_id'] != "") {
					 * $url2 .= '&formreturn_id=' . $formreturn_id;
					 * //}
					 *
					 * $this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('form/form/insert3', '' . $url2, 'SSL'));
					 * }
					 */
				}
			}
		}
		
		$this->getForm ();
	}
	public function edit() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST' && $this->validateForm ()) {
			/*
			 * $this->session->data['design_forms'] = $this->request->post['design_forms'];
			 * $this->session->data['upload_file'] = $this->request->post['upload_file'];
			 * $this->session->data['ffile'] = $this->request->post['file'];
			 * $this->session->data['fsignature'] = $this->request->post['signature'];
			 * $this->session->data['form_signature'] = $this->request->post['form_signature'];
			 * $this->session->data['is_final'] = $this->request->post['is_final'];
			 */
			
			if ($this->request->post ['forms_design_id'] == CUSTOME_INTAKEID) {
				$editdata = array ();
				$archive_forms_id = $this->model_form_form->editFormdata ( $this->request->post ['design_forms'], $this->request->get ['forms_id'], $this->request->post ['upload_file'], $this->request->post ['image'], $this->request->post ['signature'], $this->request->post ['form_signature'], $this->request->post ['is_final'], '', $editdata );
			} else {
				
				$this->load->model ( 'api/temporary' );
				
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
				
				$tdata = array ();
				$tdata ['id'] = $this->request->get ['forms_id'];
				$tdata ['parent_id'] = $this->request->get ['form_parent_id'];
				$tdata ['parent_archive_forms_id'] = $this->request->get ['archive_forms_id'];
				$tdata ['facilities_id'] = $facilities_id;
				$tdata ['type'] = 'updateform';
				$archive_forms_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			}
			
			$url2 = "";
			$url4 = "";
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				$url4 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			} else {
				$url2 .= '&archive_forms_id=' . $archive_forms_id;
				$url4 .= '&archive_forms_id=' . $archive_forms_id;
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
				$url4 .= '&searchdate=' . $this->request->get ['searchdate'];
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
			if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
				$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url4 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				$url4 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
				$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
				$url4 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
				$url4 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
				$url4 .= '&task_id=' . $this->request->get ['task_id'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
				$url4 .= '&last_notesID=' . $this->request->get ['last_notesID'];
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
							
							// $this->session->data['success2'] = 'Form Created successfully!';
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
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
							
							$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
							
							// $this->session->data['success2'] = 'Form Created successfully!';
							
							// die;
						}
					} else {
						$this->session->data ['success2'] = 'Form Updated successfully!';
						
						if (isset ( $this->request->post ['is_final'] ) && $this->request->post ['is_final'] == 1) {
							
							$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
							
							if ($fromdatas ['mark_final'] == 3 || $fromdatas ['mark_final'] == 1 || $fromdatas ['mark_final'] == 4 || $fromdatas ['mark_final'] == 5) {
								
								$url2 .= '&is_formsecurity=' . $fromdatas ['mark_final'];
								$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
								$url2 .= '&is_mark_final=1';
								$url2 .= '&forms=2';
								
								$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
							} else {
								if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
									$url2 .= '&forms=2';
									$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
								} else {
									$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
								}
							}
						} else {
							
							if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
								$url2 .= '&forms=2';
								$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
							} else {
								$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
							}
						}
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
								// var_dump($url4 );
								
								$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
							} else {
								
								if ($childform ['parent_id'] != null && $childform ['parent_id'] != "") {
									$url4 .= '&parent_id=' . $childform ['parent_id'];
								}
								
								$url4 .= '&page_number=' . $this->request->post ['jump_page_number'];
								$url4 .= '&forms_design_id=' . $this->request->post ['jump_forms_id'];
								$url4 .= '&formreturn_id=' . $this->request->post ['jump_formreturn_id'];
								
								$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url4, 'SSL' ) ) );
							}
						}
					} else {
						
						$this->session->data ['success2'] = 'Form Updated successfully!';
						if (isset ( $this->request->post ['is_final'] ) && $this->request->post ['is_final'] == 1) {
							
							$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
							
							if ($fromdatas ['mark_final'] == 3 || $fromdatas ['mark_final'] == 1 || $fromdatas ['mark_final'] == 4 || $fromdatas ['mark_final'] == 5) {
								
								$url2 .= '&is_formsecurity=' . $fromdatas ['mark_final'];
								$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
								$url2 .= '&is_mark_final=1';
								$url2 .= '&forms=2';
								
								$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
							} else {
								if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
									$url2 .= '&forms=2';
									$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
								} else {
									$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
								}
							}
						} else {
							
							if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
								$url2 .= '&forms=2';
								$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
							} else {
								$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
							}
						}
					}
				}
			}
		}
		// die;
		$this->getForm ();
	}
	protected function getForm() {
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->language->load ( 'notes/notes' );
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['forms_id'] = $this->request->get ['forms_id'];
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$this->data ['task_id_url'] = '&task_id=' . $this->request->get ['task_id'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
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
					$data2 ['form_design_parent_id'] = $formdata_i ['parent_id'];
					$data2 ['page_number'] = $formdata_i ['page_number'];
					$data2 ['form_parent_id'] = '0';
					
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
					
					$data2 ['facilities_id'] = $facilities_id;
					
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
						$data2 ['facilities_id'] = $facilities_id;
						
						$formreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
					}
				}
			}
		}
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
		if ($fromdatas ['parent_id'] > 0) {
			$parent_id = $fromdatas ['parent_id'];
			$fromdatas2 = $this->model_form_form->getFormdata ( $parent_id );
		} else {
			$parent_id = $this->request->get ['forms_design_id'];
			$fromdatas2 = $this->model_form_form->getFormdata ( $parent_id );
		}
		
		$this->data ['current_forms_parent_id'] = $parent_id;
		$this->data ['current_forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['is_form_open'] = 1;
		
		if ($pformreturn_id != null && $pformreturn_id != "") {
			$this->data ['jump_formreturn_id'] = $pformreturn_id;
		} else {
			$this->data ['jump_formreturn_id'] = $this->request->get ['formreturn_id'];
		}
		
		if ($this->request->get ['page_number'] > 0) {
			$this->data ['current_page_number'] = $this->request->get ['page_number'];
		} else {
			$this->data ['current_page_number'] = 0;
		}
		
		$data2 = array (
				'is_parent_child' => '1',
				'forms_id' => $parent_id,
				'sort' => 'page_number' 
		);
		
		$childforms = $this->model_form_form->getforms ( $data2 );
		$totalchildforms = $this->model_form_form->getTotalforms ( $data2 );
		// var_dump($totalchildforms);
		
		if ($this->request->get ['page_number'] > 0) {
			$cpage_numbersss = $this->request->get ['page_number'];
		} else {
			$cpage_numbersss = $fromdatas ['page_number'];
		}
		
		$this->data ['last_clild_form'] = $this->model_form_form->getFormByLimit ( $parent_id, $cpage_numbersss );
		// var_dump($childform);
		// echo"<hr>";
		
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
			
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$urlf2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
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
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$urlf2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$urlf2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
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
			
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$urlf2 .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$urlf2 .= '&locationids=' . $this->request->get ['locationids'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$urlf2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$urlf2 .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$urlf2 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			$this->data ['childforms'] [] = array (
					'forms_id' => $fromdatas2 ['forms_id'],
					'form_name' => $fromdatas2 ['form_name'],
					'parent_id' => $fromdatas2 ['parent_id'],
					'page_number' => $fromdatas2 ['page_number'],
					'href' => $this->url->link ( 'form/form', $urlf2, true ) 
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
					if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
						$url14 .= '&activeform_id=' . $this->request->get ['activeform_id'];
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
					
					if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
						$url14 .= '&notes_id=' . $this->request->get ['notes_id'];
					}
					
					if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
						$url14 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
					}
					if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
						$url14 .= '&client_add_new=' . $this->request->get ['client_add_new'];
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
					
					if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
						$url14 .= '&facilityids=' . $this->request->get ['facilityids'];
					}
					if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
						$url14 .= '&locationids=' . $this->request->get ['locationids'];
					}
					if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
						$url14 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
					}
					
					if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
						$url14 .= '&tagsids=' . $this->request->get ['tagsids'];
					}
					
					if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
						$url14 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
					}
					
					if ($pformreturn_id != null && $pformreturn_id != "") {
						$url14 .= '&formreturn_id=' . $pformreturn_id;
					}
					
					$this->data ['previous_url'] = $this->url->link ( 'form/form', $url14, true );
				} else {
					$this->data ['previous_url'] = $this->url->link ( 'form/form', $urlf2, true );
				}
			} else {
				$this->data ['previous_url'] = $this->url->link ( 'form/form', $urlf2, true );
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
				
				if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
					$urlc .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				}
				if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
					$urlc .= '&client_add_new=' . $this->request->get ['client_add_new'];
				}
				
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$urlc .= '&searchdate=' . $this->request->get ['searchdate'];
				}
				if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
					$urlc .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				}
				
				if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
					$urlc .= '&activeform_id=' . $this->request->get ['activeform_id'];
				}
				
				if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
					$urlc .= '&facilityids=' . $this->request->get ['facilityids'];
				}
				if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
					$urlc .= '&locationids=' . $this->request->get ['locationids'];
				}
				if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
					$urlc .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				}
				
				if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
					$urlc .= '&tagsids=' . $this->request->get ['tagsids'];
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
				
				if ($pformreturn_id != null && $pformreturn_id != "") {
					$urlc .= '&formreturn_id=' . $pformreturn_id;
				}
				
				$this->data ['childforms'] [] = array (
						'forms_id' => $childform ['forms_id'],
						'form_name' => $childform ['form_name'],
						'parent_id' => $childform ['parent_id'],
						'page_number' => $childform ['page_number'],
						'href' => $this->url->link ( 'form/form', $urlc, true ) 
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
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$urlf .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$urlf .= '&client_add_new=' . $this->request->get ['client_add_new'];
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$urlf .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$urlf .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$urlf .= '&activeform_id=' . $this->request->get ['activeform_id'];
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
			
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$urlf .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$urlf .= '&locationids=' . $this->request->get ['locationids'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$urlf .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$urlf .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$urlf .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$urlf .= '&formreturn_id=' . $pformreturn_id;
			}
			
			$this->data ['previous_url'] = $this->url->link ( 'form/form', $urlf, true );
			
			$this->data ['childforms'] [] = array (
					'forms_id' => $fromdatas ['forms_id'],
					'form_name' => $fromdatas ['form_name'],
					'parent_id' => $fromdatas ['parent_id'],
					'page_number' => $fromdatas ['page_number'],
					'href' => $this->url->link ( 'form/form', $urlf, true ) 
			);
		}
		// var_dump($this->data['totalchildforms_step_submit']);
		// var_dump($this->data['current_forms_design_id']);
		// var_dump($this->data['current_forms_id']);
		
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
		
		$this->data ['link_form_fieldall'] = $fromdatas ['link_form_fieldall'];
		
		// var_dump($this->data['link_form_fieldall']);
		
		// if($fromdatas['display_observation'] == '1'){
		// $this->load->model('notes/notes');
		// $this->data['observationdatas'] = $this->model_notes_notes->getcustomlists();
		// }
		
		// var_dump($this->data['fields']);
		
		$this->data ['layouts'] = explode ( ",", $fromdatas ['form_layout'] );
		
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
		
		$this->data ['facility_name'] = $this->customer->getfacility ();
		
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
			 *
			 * $this->load->model('notes/notes');
			 * $this->load->model('notes/tags');
			 * $this->load->model('user/user');
			 *
			 * //var_dump($results['parent_id']);
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
			 *
			 * $this->data['notess'][] = array(
			 * 'notes_id' => $result['notes_id'],
			 * 'task_type' => $result['task_type'],
			 * 'taskadded' => $result['taskadded'],
			 * 'assign_to' => $result['assign_to'],
			 * 'highlighter_value' => $highlighterData['highlighter_value'],
			 * 'notes_description' => $result['notes_description'],
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
		
		// if($this->request->get['previous'] == '1'){
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
		
		// var_dump($this->data['formssigns']);
		
		if ($this->request->get ['forms_id'] == "" && $this->request->get ['forms_id'] == NULL) {
			
			$url2 = "";
			$url4 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
				$url4 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
				$url4 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
				$url4 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
				$url4 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url4 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
				$url4 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
				$url4 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
				$url4 .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
				$url4 .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				$url4 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['parent_id'] != null && $this->request->get ['parent_id'] != "") {
				$url2 .= '&parent_id=' . $this->request->get ['parent_id'];
				$url4 .= '&parent_id=' . $this->request->get ['parent_id'];
			}
			if ($this->request->get ['page_number'] != null && $this->request->get ['page_number'] != "") {
				$url2 .= '&page_number=' . $this->request->get ['page_number'];
				$url4 .= '&page_number=' . $this->request->get ['page_number'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				$url4 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$url2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
				$url4 .= '&client_add_new=' . $this->request->get ['client_add_new'];
			}
			
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
				$url4 .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$url2 .= '&locationids=' . $this->request->get ['locationids'];
				$url4 .= '&locationids=' . $this->request->get ['locationids'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url4 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
				$url4 .= '&tagsids=' . $this->request->get ['tagsids'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$url2 .= '&formreturn_id=' . $pformreturn_id;
				$url4 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			/*
			 * if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			 * $this->data['action'] = $this->url->link('form/form/taskforminsert', $url2, true);
			 * }else{
			 */
			$this->data ['action'] = $this->url->link ( 'form/form/insert', $url2, true );
			/* } */
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
				
				$this->data ['forms_id'] = $fforms_id;
			}
			
			if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
				$url3 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
				$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
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
			
			if ($this->request->get ['facilityids'] != null && $this->request->get ['facilityids'] != "") {
				$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
				$url4 .= '&facilityids=' . $this->request->get ['facilityids'];
				$url3 .= '&facilityids=' . $this->request->get ['facilityids'];
				$url3a .= '&facilityids=' . $this->request->get ['facilityids'];
			}
			if ($this->request->get ['locationids'] != null && $this->request->get ['locationids'] != "") {
				$url2 .= '&locationids=' . $this->request->get ['locationids'];
				$url4 .= '&locationids=' . $this->request->get ['locationids'];
				$url3 .= '&locationids=' . $this->request->get ['locationids'];
				$url3a .= '&locationids=' . $this->request->get ['locationids'];
			}
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url4 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url3 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				$url3a .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['tagsids'] != null && $this->request->get ['tagsids'] != "") {
				$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
				$url4 .= '&tagsids=' . $this->request->get ['tagsids'];
				$url3 .= '&tagsids=' . $this->request->get ['tagsids'];
				$url3a .= '&tagsids=' . $this->request->get ['tagsids'];
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
			
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url3 .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url3a .= '&activeform_id=' . $this->request->get ['activeform_id'];
				$url4 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
				$url3 .= '&last_notesID=' . $this->request->get ['last_notesID'];
				$url4 .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			
			if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
				$url3a .= '&is_archive=' . $this->request->get ['is_archive'];
				$url4 .= '&is_archive=' . $this->request->get ['is_archive'];
			}
			
			if ($this->request->get ['is_archive'] == "4") {
				$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['forms_id'] );
				$url3 .= '&notes_id=' . $form_info ['notes_id'];
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
			
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$url2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
				$url3 .= '&client_add_new=' . $this->request->get ['client_add_new'];
				$url4 .= '&client_add_new=' . $this->request->get ['client_add_new'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$url2 .= '&formreturn_id=' . $pformreturn_id;
				$url3 .= '&formreturn_id=' . $pformreturn_id;
				$url4 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			$this->data ['archive_url'] = str_replace ( '&amp;', '&', $url3a );
			
			// var_dump($url4);
			
			$this->data ['action'] = $this->url->link ( 'form/form/edit', $url2, true );
			$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/edit', '' . $url3, 'SSL' ) );
			
			if ($this->request->get ['forms_design_id'] == '13') {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printformfldjj', $url2, true );
			} elseif ($this->request->get ['forms_design_id'] == '150') {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printformfldjj', $url2, true );
			}elseif ($this->request->get ['forms_design_id'] == '252') {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printformfldjj', $url2, true );
			} else {
				$this->data ['print_url'] = $this->url->link ( 'form/form/printform', $url4, true );
			}
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		// var_dump(unserialize($results['design_forms']));
		// echo "<hr>";
		
		$this->data ['formdatas'] = array ();
		
		if (isset ( $this->request->post ['design_forms'] )) {
			$this->data ['formdatas'] = $this->request->post ['design_forms'];
		} elseif (! empty ( $results )) {
			$this->data ['formdatas'] = unserialize ( $results ['design_forms'] );
		}
		
		/*
		 * foreach($formdatas as $form_detail){
		 * var_dump($form_detail['add_row']);
		 * echo "<hr>";
		 * }
		 */
		
		if ($fforms_id != null && $fforms_id != "") {
			$fforms_id = $fforms_id;
		} else {
			$fforms_id = $dfforms_id;
		}
		// var_dump($fforms_id);
		
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
		// var_dump($this->data['formdatas']);
		
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
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$this->load->model ( 'createtask/createtask' );
			
			$task_info = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->get ['task_id'] );
		}
		
		$tagids = array ();
		
		$userids = array ();
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'user/user' );
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
		
		$this->data ['userinfo'] = $userinfo;
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$ctime = time ();
		$stime = date ( 'H:i:s', strtotime ( $ctime ) );
		
		$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $facilities_id );
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
		} else if ($this->request->get ['tags_id']) {
			$tags_id = $this->request->get ['tags_id'];
		} elseif ($this->request->post ['emp_tag_id']) {
			$tags_id = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $results )) {
			$tags_id = $results ['tags_id'];
		} elseif (! empty ( $task_info ['emp_tag_id'] > 0 )) {
			$tags_id = $task_info ['emp_tag_id'];
		} elseif ($this->request->get ['tagsids']) {
			$tags_id = $this->request->get ['tagsids'];
		}
		
		if (isset ( $this->session->data ['case_number'] )) {
			$this->data ['case_number'] = $this->session->data ['case_number'];
		} else {
			$this->data ['case_number'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->data ['tagdetails'] = $tag_info;
		
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
				$this->data ['link_forms_id'] = $this->request->get ['link_forms_id'];
			}
			
			if ($this->data ['link_forms_id'] != null && $this->data ['link_forms_id'] != "") {
				
				$this->load->model ( 'form/form' );
				$tag_info11 = $this->model_form_form->getFormDatas ( $this->data ['link_forms_id'] );
				
				$design_forms = unserialize ( $tag_info11 ['design_forms'] );
				
				// var_dump($design_forms);
				// var_dump($tag_info);
				
				$clientname = "";
				if ($design_forms [0] [0] ['' . TAG_FNAME . ''] != null && $design_forms [0] [0] ['' . TAG_FNAME . ''] != "") {
					$clientname = $design_forms [0] [0] ['' . TAG_FNAME . ''] . ' ' . $design_forms [0] [0] ['' . TAG_MNAME . ''] . ' ' . $design_forms [0] [0] ['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms [0] [0] ['' . TAG_DOB . ''] . ' | Screening ' . $design_forms [0] [0] ['' . TAG_SCREENING . ''];
				} else {
					$clientname = $tag_info11 ['incident_number'] . ' ' . date ( 'm-d-Y', strtotime ( $tag_info11 ['date_added'] ) );
				}
				
				if ($design_forms [0] [0] ['' . TAG_SCREENING . ''] != "0000-00-00") {
					$date_of_screening = $design_forms [0] [0] ['' . TAG_SCREENING . ''];
				} else {
					$date_of_screening = date ( 'm-d-Y' );
				}
				if ($design_forms [0] [0] ['' . TAG_DOB . ''] != "0000-00-00") {
					$dob = $design_forms [0] [0] ['' . TAG_DOB . ''];
					
					$res2 = explode ( "-", $design_forms [0] [0] ['' . TAG_DOB . ''] );
					$dob222 = $res2 [2] . "-" . $res2 [0] . "-" . $res2 [1];
					
					$dobm = $res2 [0];
					$dobd = $res2 [1];
					$doby = $res2 [2];
				} else {
					$dob = '';
					$dobm = '';
					$dobd = '';
					$doby = '';
				}
				
				$this->data ['tagdetails'] = array (
						'incident_number' => $clientname,
						'custom_form_type' => $tag_info11 ['custom_form_type'],
						'forms_id' => $tag_info11 ['forms_id'],
						'emp_first_name' => $design_forms [0] [0] ['' . TAG_FNAME . ''],
						'emp_middle_name' => $design_forms [0] [0] ['' . TAG_MNAME . ''],
						'emp_last_name' => $design_forms [0] [0] ['' . TAG_LNAME . ''],
						'emergency_contact' => $design_forms [0] [0] ['' . TAG_PHONE . ''],
						'dob' => $dob,
						'month' => $dobm,
						'date' => $dobd,
						'year' => $doby,
						'age' => $design_forms [0] [0] ['' . TAG_AGE . ''],
						'gender' => $design_forms [0] [0] ['' . TAG_GENDER . ''],
						'location_address' => $design_forms [0] [0] ['' . TAG_ADDRESS . ''],
						'address_street2' => '', // $design_forms[0][0]['text_75675662'],
						'person_screening' => $notes_info ['user_id'],
						'date_of_screening' => $date_of_screening,
						'ssn' => $design_forms [0] [0] ['' . TAG_SSN . ''],
						'emp_extid' => $design_forms [0] [0] ['' . TAG_EXTID . ''],
						'upload_file' => $upload_file,
						'image_url1' => $image_url1,
						'form_date_added' => date ( 'm-d-Y', strtotime ( $tag_info11 ['date_added'] ) ) 
				);
			}
			
			if (isset ( $this->request->post ['link_screening'] )) {
				$this->data ['link_screening'] = $this->request->post ['link_screening'];
			} else {
				$this->data ['link_screening'] = $clientname;
			}
		}
		
		if (isset ( $this->request->post ['top_submit'] )) {
			$this->data ['top_submit'] = $this->request->post ['top_submit'];
		} else {
			$this->data ['top_submit'] = '';
		}
		
		// var_dump($this->data['exit_error']);
		
		$this->data ['facilities_id'] = $this->customer->getId ();
		
		$this->load->model ( 'facilities/facilities' );
		
		$s = array ();
		$s ['facilities_id'] = $this->customer->getId ();
		$s ['sbfacility'] = 1;
		
		
		$this->data ['sfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $s );
		
		$timezone_name = $this->customer->isTimezone ();
		$timeZone = date_default_timezone_set ( $timezone_name );
		$this->data ['dnotess'] = array ();
		$searchdate = date ( 'm-d-Y' );
		
		$data31 = array (
				'status' => 1,
				'discharge' => 1,
				'role_call' => '1',
				'sort' => 'emp_first_name',
				'facilities_id' => $this->customer->getId () 
		);
		$this->load->model ( 'setting/tags' );
		
		$this->data ['allTagsclients'] = $this->model_setting_tags->getTags ( $data31 );
		
		$this->load->model ( 'user/user' );
		$this->data ['allcusers'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
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
		 * //var_dump($search_keyword_id);
		 *
		 * if($search_facilities_id != null && $search_facilities_id != ""){
		 * $search_facilities_id1 = $search_facilities_id;
		 * }else{
		 *
		 * $this->load->model('facilities/facilities');
		 * $resulsst = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		 *
		 * if($resulsst['is_master_facility'] == '1'){
		 * if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
		 * $search_facilities_id1 = $this->session->data['search_facilities_id'];
		 * }else{
		 * $search_facilities_id1 = $this->customer->getId();
		 * }
		 * }else{
		 * $search_facilities_id1 = $this->customer->getId();
		 * }
		 *
		 * }
		 *
		 * //var_dump($search_facilities_id1);
		 *
		 * $this->language->load('notes/notes');
		 * $this->load->model('notes/notes');
		 * $this->load->model('setting/tags');
		 * $this->load->model('facilities/facilities');
		 * $this->load->model('notes/tags');
		 *
		 * $this->load->model('setting/highlighter');
		 *
		 * if ($this->request->post['top_submit'] == '6') {
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
	public function insert2() {
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		/*
		 * $this->load->model('facilities/facilities');
		 * $resulsst = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		 *
		 * $dataaaa = array();
		 *
		 * $ddss = array();
		 * $ddss1 = array();
		 * if($resulsst['notes_facilities_ids'] != null && $resulsst['notes_facilities_ids'] != ""){
		 * $ddss[] = $resulsst['notes_facilities_ids'];
		 * }
		 * $ddss[] = $this->customer->getId();
		 * $sssssdd = implode(",",$ddss);
		 *
		 * $dataaaa['facilities'] = $sssssdd;
		 * $this->data['masterfacilities'] = $this->model_facilities_facilities->getfacilitiess($dataaaa);
		 *
		 * $this->data['is_master_facility'] = $resulsst['is_master_facility'];
		 */
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			/*
			 * $archive_forms_id = $this->model_form_form->editFormdata($this->session->data['design_forms'], $this->request->get['forms_id'], $this->session->data['upload_file'], $this->session->data['ffile'], $this->session->data['fsignature'], $this->session->data['form_signature'], $this->session->data['is_final']);
			 */
			
			/*
			 * if($resulsst['is_master_facility'] == '1'){
			 * $facilities_id = $this->request->post['facility'];
			 * }else{
			 * $facilities_id = $this->customer->getId();
			 * }
			 * $this->model_form_form->updateformfacility($facilities_id, $this->request->get['formreturn_id']);
			 */
			
			if ($this->request->post ['forms_design_id'] == CUSTOME_INTAKEID) {
				$archive_forms_id = $this->request->get ['archive_forms_id'];
			} else {
				$this->load->model ( 'api/temporary' );
				$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_forms_id'] );
				
				$tempdata = array ();
				$tempdata = unserialize ( $temporary_info ['data'] );
				
				$editdata = array ();
				$archive_forms_id = $this->model_form_form->editFormdata ( $tempdata ['design_forms'], $this->request->get ['forms_id'], $tempdata ['upload_file'], $tempdata ['image'], $tempdata ['signature'], $tempdata ['form_signature'], $tempdata ['is_final'], '', $editdata );
				
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
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
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
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['emp_tag_id'] = $this->request->get ['emp_tag_id'];
			$tdata ['notes_id'] = $this->request->get ['notes_id'];
			$tdata ['forms_id'] = $this->request->get ['forms_id'];
			$tdata ['formreturn_id'] = $this->request->get ['formreturn_id'];
			$tdata ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$tdata ['form_parent_id'] = $this->request->get ['form_parent_id'];
			$tdata ['archive_forms_id'] = $archive_forms_id;
			$tdata ['task_id'] = $this->request->get ['task_id'];
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $timezone_name;
			$tdata ['parent_facilities_id'] = $this->customer->getId ();
			
			$notes_id = $this->model_form_form->insert2 ( $this->request->post, $tdata );
			
			if ($this->request->post ['forms_design_id'] != CUSTOME_INTAKEID) {
				$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_forms_id'] );
			}
			
			unset ( $this->session->data ['design_forms'] );
			unset ( $this->session->data ['upload_file'] );
			unset ( $this->session->data ['ffile'] );
			unset ( $this->session->data ['fsignature'] );
			unset ( $this->session->data ['form_signature'] );
			unset ( $this->session->data ['is_final'] );
			
			$this->session->data ['success_add_form'] = $this->language->get ( 'text_success' );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
				$url2 .= '&page=' . $this->request->get ['page'];
			}
			if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
			}
			if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
				$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
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
				'facilities_id' => $this->customer->getId () 
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
		if ($this->request->get ['forms_id'] != null && $this->request->get ['forms_id'] != "") {
			$url2 .= '&forms_id=' . $this->request->get ['forms_id'];
		}
		if ($this->request->get ['form_parent_id'] != null && $this->request->get ['form_parent_id'] != "") {
			$url2 .= '&form_parent_id=' . $this->request->get ['form_parent_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			$url2 .= '&emp_tag_id=' . $this->request->get ['emp_tag_id'];
		}
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get ['task_id'];
		}
		if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
			$url2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
		}
		if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert2', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			
			$this->load->model ( 'createtask/createtask' );
			$result = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
					$this->data ['enable_facility'] = '1';
					$this->load->model ( 'facilities/facilities' );
					
					$s = array ();
					$s ['facilities_id'] = $this->customer->getId ();
					$this->data ['facilities'] = $this->model_facilities_facilities->getfacilitiess ( $s );
				}
			}
			/*
			 * //if($result['linked_id'] > 0 ){
			 * $this->data['enable_facility'] = '1';
			 * $this->load->model('facilities/facilities');
			 *
			 * $s = array();
			 * $s['facilities_id'] = $this->customer->getId();
			 * $this->data['facilities'] = $this->model_facilities_facilities->getfacilitiess($s);
			 * //}
			 */
		}
		
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
		
		if ($this->request->get ['emp_tag_id']) {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['emp_tag_id'] );
		} elseif ($this->request->get ['notes_id']) {
			
			$this->load->model ( 'setting/tags' );
			
			$notetag_info = $this->model_setting_tags->getTagsbyNotesIDrow ( $this->request->get ['notes_id'] );
			
			if ($notetag_info ['tags_id'] != null && $notetag_info ['tags_id'] != "") {
				$tag_info = $this->model_setting_tags->getTag ( $notetag_info ['tags_id'] );
			}
		}
		
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
		
		$this->data ['createtask'] = 1;
		
		if (isset ( $this->request->post ['is_move'] )) {
			$this->data ['is_move'] = $this->request->post ['is_move'];
		} else {
			$this->data ['is_move'] = '';
		}
		
		if (isset ( $this->request->post ['facilitydrop'] )) {
			$this->data ['facilitydrop'] = $this->request->post ['facilitydrop'];
		} else {
			$this->data ['facilitydrop'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function insert3() {
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		/*
		 * $this->load->model('facilities/facilities');
		 * $resulsst = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		 *
		 * $dataaaa = array();
		 *
		 * $ddss = array();
		 * $ddss1 = array();
		 * if($resulsst['notes_facilities_ids'] != null && $resulsst['notes_facilities_ids'] != ""){
		 * $ddss[] = $resulsst['notes_facilities_ids'];
		 * }
		 * $ddss[] = $this->customer->getId();
		 * $sssssdd = implode(",",$ddss);
		 *
		 * $dataaaa['facilities'] = $sssssdd;
		 * $this->data['masterfacilities'] = $this->model_facilities_facilities->getfacilitiess($dataaaa);
		 *
		 * $this->data['is_master_facility'] = $resulsst['is_master_facility'];
		 */
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			// $this->model_form_form->editFormdata($this->session->data['design_forms'], $this->request->get['forms_id']);
			
			// if($resulsst['is_master_facility'] == '1'){
			// $facilities_id = $this->request->post['facility'];
			// }else{
			// $facilities_id = $this->customer->getId();
			// }
			// $this->model_form_form->updateformfacility($facilities_id, $this->request->get['formreturn_id']);
			
			if ($this->request->get ['formreturn_id'] > 0) {
				$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['formreturn_id'] );
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
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($form_facilities_id != null && $form_facilities_id != "") {
				$facilities_id = $form_facilities_id;
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $form_facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				
				if ($facilities_info ['is_master_facility'] == '1') {
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
			}
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['emp_tag_id'] = $this->request->get ['emp_tag_id'];
			$tdata ['updatenotes_id'] = $this->request->get ['updatenotes_id'];
			$tdata ['formreturn_id'] = $this->request->get ['formreturn_id'];
			$tdata ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $timezone_name;
			$tdata ['parent_facilities_id'] = $this->customer->getId ();
			
			$notesId = $this->model_form_form->insert3 ( $this->request->post, $tdata );
			
			unset ( $this->session->data ['formreturn_id'] );
			
			$this->session->data ['success_add_form'] = $this->language->get ( 'text_success' );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
				$url2 .= '&page=' . $this->request->get ['page'];
			}
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
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
				'facilities_id' => $this->customer->getId () 
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
		if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
			$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get ['task_id'];
		}
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			$url2 .= '&emp_tag_id=' . $this->request->get ['emp_tag_id'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		$this->data ['url_load'] = $this->getChild ( 'notes/notes/getNoteData', $url2 );
		
		$this->data ['notes_id'] = $this->request->get ['notes_id'];
		$this->data ['updatenotes_id'] = $this->request->get ['updatenotes_id'];
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/insert3', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );
		
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
		
		/*
		 * if($this->request->get['emp_tag_id']){
		 * $this->load->model('setting/tags');
		 * $tag_info = $this->model_setting_tags->getTag($this->request->get['emp_tag_id']);
		 * }
		 */
		
		/* if($this->request->get['forms_design_id'] == CUSTOME_HOMEVISIT || $this->request->get['forms_design_id'] == CUSTOME_DISCHARGE || $this->request->get['forms_design_id'] == CUSTOME_INTAKEID){ */
		
		if ($this->request->get ['emp_tag_id']) {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['emp_tag_id'] );
		} elseif ($this->request->get ['updatenotes_id']) {
			
			$this->load->model ( 'setting/tags' );
			
			$notetag_info = $this->model_setting_tags->getTagsbyNotesIDrow ( $this->request->get ['updatenotes_id'] );
			
			if ($notetag_info ['tags_id'] != null && $notetag_info ['tags_id'] != "") {
				$tag_info = $this->model_setting_tags->getTag ( $notetag_info ['tags_id'] );
			}
		}
		
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
		
		/*
		 * }else{
		 * $form_info = $this->model_form_form->getFormDatas($this->request->get['formreturn_id']);
		 * $formdata = unserialize($form_info['design_forms']);
		 * //var_dump($formdata);
		 * $this->load->model('setting/tags');
		 *
		 * $clientayy = array();
		 *
		 * foreach($formdata as $design_forms){
		 * foreach($design_forms as $key=>$design_form){
		 * foreach($design_form as $key2=>$b){
		 *
		 * $arrss = explode("_1_", $key2);
		 *
		 * if($arrss[1] == 'tags_id'){
		 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
		 * if($design_form[$arrss[0].'_1_'.$arrss[1]] != null && $design_form[$arrss[0].'_1_'.$arrss[1]] != ""){
		 * $clientayy[] = $design_form[$arrss[0].'_1_'.$arrss[1]];
		 * }
		 * }
		 * }
		 *
		 * if($arrss[1] == 'tags_ids'){
		 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
		 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idst){
		 * $clientayy[] = $idst;
		 * }
		 * }
		 * }
		 * }
		 * }
		 * }
		 *
		 * $clientayy = array_unique($clientayy);
		 *
		 * if (isset($this->request->post['tagides'])) {
		 * $tagides1 = $this->request->post['tagides'];
		 * } elseif (! empty($clientayy)) {
		 * $tagides1 = $clientayy;
		 * } else {
		 * $tagides1 = array();
		 * }
		 *
		 * $this->data['tagides'] = array();
		 * $this->load->model('setting/tags');
		 *
		 * foreach ($tagides1 as $tagsid) {
		 *
		 * $tag_info = $this->model_setting_tags->getTag($tagsid);
		 * if ($tag_info) {
		 * $this->data['tagides'][] = array(
		 * 'tags_id' => $tagsid,
		 * 'emp_tag_id' => $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name']
		 * );
		 * }
		 * }
		 * $this->data['is_multiple_tags'] = IS_MAUTIPLE;
		 *
		 * }
		 */
		
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
	}
	protected function validateForm23() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		
		
		if ($this->request->get ['tag_status_id'] != '' && $this->request->get ['tag_status_id'] != null) {
			$this->load->model ( 'notes/clientstatus' );
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ($this->request->get ['tag_status_id']);
			$ruleaction_content = unserialize ( $clientstatus_info ['rule_action_content'] );
			
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			
			if(!empty($ruleaction_content['status_user_roleids'])){
				$newuids = array();
				foreach($ruleaction_content['status_user_roleids'] as $userroleid){
					if($ruleaction_content[$userroleid]['add_status'] == '1'){
						$newuids[] = $userroleid;
					}
				}
				
				if (!in_array($user_info ['user_group_id'], $newuids)) {
					$this->error ['warning'] = "You are not authorized to add Denial form!";
				}
			}
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
		
		if ($this->request->post ['perpetual_checkbox'] == '1') {
			if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
				$this->error ['perpetual_checkbox_notes_pin'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
				
				$this->load->model ( 'user/user_group' );
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
				
				$perpetual_task = $user_role_info ['perpetual_task'];
				
				if ($perpetual_task != '1') {
					$this->error ['warning'] = "You are not authorized to end the task!";
				}
			}
		}
		
		if (($this->request->post ['perpetual_checkbox'] == "3")) {
			if (($this->request->post ['facilitydrop'] == null && $this->request->post ['facilitydrop'] == "")) {
				$this->error ['facilitydrop'] = 'Please select facility!';
			}
		}
		
		if (($this->request->post ['perpetual_checkbox'] == "4")) {
			if (($this->request->post ['acttion_interval_id'] == null && $this->request->post ['acttion_interval_id'] == "")) {
				$this->error ['warning'] = 'Please select Interval!';
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function taskforminsert() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->language ( 'form/form' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST' && $this->validateForm ()) {
			$data2 = array ();
			$data2 ['forms_design_id'] = $this->request->get ['forms_design_id'];
			// $data2['notes_id'] = $this->request->get['updatenotes_id'];
			
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
			$data2 ['facilities_id'] = $facilities_id;
			
			$formreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
			
			$this->session->data ['success2'] = 'Form Created successfully!';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get ['task_id'];
			}
			
			$url2 .= '&formreturn_id=' . $formreturn_id;
			
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post ['emp_tag_id'];
				$url2 .= '&tags_id=' . $this->request->post ['emp_tag_id'];
			}
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&forms=4';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/taskforminsertsign', '' . $url2, 'SSL' ) );
			}
		}
		
		$this->getForm ();
	}
	public function taskforminsertsign() {
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$dataaaa = array ();
		
		$ddss = array ();
		$ddss1 = array ();
		if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
			$ddss [] = $resulsst ['notes_facilities_ids'];
		}
		$ddss [] = $this->customer->getId ();
		$sssssdd = implode ( ",", $ddss );
		
		$dataaaa ['facilities'] = $sssssdd;
		$this->data ['masterfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );
		
		$this->data ['is_master_facility'] = $resulsst ['is_master_facility'];
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			if ($this->request->get ['formreturn_id'] > 0) {
				$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['formreturn_id'] );
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
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $form_facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
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
			}
			
			$this->model_form_form->updateformfacility ( $facilities_id, $this->request->get ['formreturn_id'] );
			
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['emp_tag_id'] = $this->request->get ['emp_tag_id'];
			$tdata ['task_id'] = $this->request->get ['task_id'];
			$tdata ['formreturn_id'] = $this->request->get ['formreturn_id'];
			$tdata ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $timezone_name;
			$tdata ['parent_facilities_id'] = $this->customer->getId ();
			
			$notesId = $this->model_form_form->taskforminsertsign ( $this->request->post, $tdata );
			
			$this->session->data ['success_add_form'] = $this->language->get ( 'text_success' );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
				$url2 .= '&page=' . $this->request->get ['page'];
			}
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			if ($notesId != null && $notesId != "") {
				$url2 .= '&notes_id=' . $notesId;
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
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
				'facilities_id' => $this->customer->getId () 
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
		if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
			$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get ['task_id'];
		}
		
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			$url2 .= '&emp_tag_id=' . $this->request->get ['emp_tag_id'];
		}
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		$this->data ['createtask'] = 1;
		
		$this->load->model ( 'createtask/createtask' );
		$result = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
		
		$this->data ['completetask'] = '1';
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($resulsst ['is_master_facility'] == '1') {
			if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
				$this->data ['enable_facility'] = '1';
				$this->load->model ( 'facilities/facilities' );
				
				$s = array ();
				$s ['facilities_id'] = $this->customer->getId ();
				$this->data ['facilities'] = $this->model_facilities_facilities->getfacilitiess ( $s );
			}
		}
		
		/*
		 * if($result['linked_id'] > 0 ){
		 * $this->data['enable_facility'] = '1';
		 * $this->load->model('facilities/facilities');
		 *
		 * $s = array();
		 * $s['facilities_id'] = $this->customer->getId();
		 * $this->data['facilities'] = $this->model_facilities_facilities->getfacilitiess($s);
		 * }
		 */
		
		$assign_to = $result ['assign_to'];
		
		$this->data ['recurrence_save_1'] = $result ['recurrence'];
		$this->data ['task_info'] = $result;
		
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
		
		$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
		
		$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $facilities_id );
		$tasktype_id = $tasktype_info ['task_id'];
		
		if ($tasktype_info ['enable_location'] == '1') {
			$this->data ['enable_location'] = '1';
		} else {
			$this->data ['enable_location'] = '2';
		}
		
		$this->load->model ( 'notes/notes' );
		
		/*
		 * if($tasktype_info['customlistvalueids']){
		 *
		 * $d = array();
		 * $d['customlistvalueids'] = $tasktype_info['customlistvalueids'];
		 * $customlistvalues = $this->model_notes_notes->getcustomlistvalues($d);
		 *
		 * if($customlistvalues){
		 * foreach($customlistvalues as $customlistvalue){
		 * $this->data['customlistvalues'][] = array(
		 * 'customlistvalues_id' => $customlistvalue['customlistvalues_id'],
		 * 'customlistvalues_name' => $customlistvalue['customlistvalues_name'],
		 * 'relation_keyword_id' => $customlistvalue['relation_keyword_id']
		 * );
		 * }
		 * }
		 *
		 * }
		 */
		
		if ($tasktype_info ['customlist_id']) {
			
			$d = array ();
			$d ['customlist_id'] = $tasktype_info ['customlist_id'];
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
		
		if ($assign_to) {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsername ( $assign_to );
			
			if ($user_info != null && $user_info != "") {
				
				$this->load->model ( 'user/user_group' );
				$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
				
				$perpetual_task = $user_role_info ['perpetual_task'];
				
				if ($perpetual_task == '1') {
					$this->data ['recurrence_save'] = $result ['recurrence'];
				} else {
					$this->data ['recurrence_save'] = '1';
				}
			}
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/taskforminsertsign', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/taskforminsert', '' . $url2, 'SSL' ) );
		
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
		
		if (isset ( $this->error ['perpetual_checkbox_notes_pin'] )) {
			$this->data ['error_perpetual_checkbox_notes_pin'] = $this->error ['perpetual_checkbox_notes_pin'];
		} else {
			$this->data ['error_perpetual_checkbox_notes_pin'] = '';
		}
		
		if (isset ( $this->request->post ['current_locations_address'] )) {
			$this->data ['current_locations_address'] = $this->request->post ['current_locations_address'];
		} else {
			$this->data ['current_locations_address'] = '';
		}
		
		if (isset ( $this->request->post ['current_lat'] )) {
			$this->data ['current_lat'] = $this->request->post ['current_lat'];
		} else {
			$this->data ['current_lat'] = '';
		}
		
		if (isset ( $this->request->post ['current_log'] )) {
			$this->data ['current_log'] = $this->request->post ['current_log'];
		} else {
			$this->data ['current_log'] = '';
		}
		
		if (isset ( $this->request->post ['perpetual_checkbox'] )) {
			$this->data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
		} else {
			$this->data ['perpetual_checkbox'] = '';
		}
		if (isset ( $this->request->post ['perpetual_checkbox_notes_pin'] )) {
			$this->data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
		} else {
			$this->data ['perpetual_checkbox_notes_pin'] = '';
		}
		
		if (isset ( $this->request->post ['observation_id'] )) {
			$this->data ['observation_id'] = $this->request->post ['observation_id'];
		} else {
			$this->data ['observation_id'] = '';
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
		
		/* if($this->request->get['forms_design_id'] == CUSTOME_HOMEVISIT || $this->request->get['forms_design_id'] == CUSTOME_DISCHARGE || $this->request->get['forms_design_id'] == CUSTOME_INTAKEID){ */
		
		if ($result ['emp_tag_id']) {
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
		} elseif ($this->request->get ['emp_tag_id']) {
			$this->load->model ( 'setting/tags' );
			$taginfo = $this->model_setting_tags->getTag ( $this->request->get ['emp_tag_id'] );
		}
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emp_tag_id'] = $taginfo ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['tags_id'] = $taginfo ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['emp_tag_id_2'] = $taginfo ['emp_tag_id'] . ': ' . $taginfo ['emp_first_name'] . ' ' . $taginfo ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['facilitydrop'] )) {
			$this->data ['facilitydrop'] = $this->request->post ['facilitydrop'];
		} else {
			$this->data ['facilitydrop'] = '';
		}
		
		/*
		 * }else{
		 * $form_info = $this->model_form_form->getFormDatas($this->request->get['formreturn_id']);
		 * $formdata = unserialize($form_info['design_forms']);
		 * //var_dump($formdata);
		 * $this->load->model('setting/tags');
		 *
		 * $clientayy = array();
		 *
		 * foreach($formdata as $design_forms){
		 * foreach($design_forms as $key=>$design_form){
		 * foreach($design_form as $key2=>$b){
		 *
		 * $arrss = explode("_1_", $key2);
		 *
		 * if($arrss[1] == 'tags_id'){
		 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
		 * if($design_form[$arrss[0].'_1_'.$arrss[1]] != null && $design_form[$arrss[0].'_1_'.$arrss[1]] != ""){
		 * $clientayy[] = $design_form[$arrss[0].'_1_'.$arrss[1]];
		 * }
		 * }
		 * }
		 *
		 * if($arrss[1] == 'tags_ids'){
		 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
		 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idst){
		 * $clientayy[] = $idst;
		 * }
		 * }
		 * }
		 * }
		 * }
		 * }
		 *
		 * $clientayy = array_unique($clientayy);
		 *
		 * if (isset($this->request->post['tagides'])) {
		 * $tagides1 = $this->request->post['tagides'];
		 * } elseif (! empty($clientayy)) {
		 * $tagides1 = $clientayy;
		 * } else {
		 * $tagides1 = array();
		 * }
		 *
		 * $this->data['tagides'] = array();
		 * $this->load->model('setting/tags');
		 *
		 * foreach ($tagides1 as $tagsid) {
		 *
		 * $tag_info = $this->model_setting_tags->getTag($tagsid);
		 * if ($tag_info) {
		 * $this->data['tagides'][] = array(
		 * 'tags_id' => $tagsid,
		 * 'emp_tag_id' => $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name']
		 * );
		 * }
		 * }
		 * $this->data['is_multiple_tags'] = IS_MAUTIPLE;
		 *
		 * }
		 */
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm() {
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		
		
		
		
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
		
		if ($this->request->post ['emp_tag_id'] == null && $this->request->post ['emp_tag_id'] == "") {
			
			if ($this->request->get ['forms_id'] == null && $this->request->get ['forms_id'] == "") {
				if (($this->request->post ['exittags_id'] == null && $this->request->post ['exittags_id'] == "") && ($this->request->post ['client_add_new'] == null && $this->request->post ['client_add_new'] == "")) {
					if ($this->request->get ['forms_design_id'] == CUSTOME_INTAKEID) {
						
						$this->load->model ( 'setting/tags' );
						
						$dob111 = $this->request->post ['design_forms'] [0] [0] ['' . TAG_DOB . ''];
						
						$date = str_replace ( '-', '/', $dob111 );
						
						$res = explode ( "/", $date );
						$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
						
						$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
						
						$data = array (
								'facilities_id' => $this->customer->getId (),
								'exits_emp_extid' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''],
								'exits_ssn' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''],
								'exits_emp_first_name' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_FNAME . ''],
								'exits_emp_last_name' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_LNAME . ''],
								'exits_dob' => $dob,
								'tags_exits' => '1',
								'status' => '1',
								'sort' => 'emp_tag_id',
								'order' => 'ASC' 
						);
						
						// var_dump($data);
						
						$results = $this->model_setting_tags->getTags ( $data );
						// var_dump($results);
						
						foreach ( $results as $tresult ) {
							$addtags_info = $this->model_form_form->gettagsforma ( $tresult ['tags_id'] );
							// var_dump($addtags_info);
							
							if (! empty ( $addtags_info )) {
								$this->error ['warning'] = $this->language->get ( 'error_exist_client_added' );
							} else {
								$this->error ['warning'] = $this->language->get ( 'error_exist_client' );
								
								if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_FNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_FNAME . ''] != "") {
									$this->error ['exit_error'] = '1';
								}
							}
						}
					}
				}
			}
		}
		
		if ($this->request->get ['forms_design_id'] == CUSTOME_INTAKEID) {
			
			$this->load->model ( 'setting/tags' );
			
			if ($this->request->post ['exittags_id'] != null && $this->request->post ['exittags_id'] != "") {
				$addtags_info = $this->model_form_form->gettagsforma ( $this->request->post ['exittags_id'] );
				
				// var_dump($addtags_info);
				
				if ($addtags_info ['forms_id'] != null && $addtags_info ['forms_id'] != "") {
					// var_dump($ssn_total);echo "<hr>";
					$url2 .= '&forms_id=' . $addtags_info ['forms_id'];
					$url2 .= '&forms_design_id=' . $addtags_info ['custom_form_type'];
					$url2 .= '&tags_id=' . $addtags_info ['tags_id'];
					$url2 .= '&notes_id=' . $addtags_info ['notes_id'];
					$action211 = $this->url->link ( 'form/form/edit', '' . $url2, 'SSL' );
					
					if (! isset ( $this->request->get ['forms_id'] )) {
						
						if ($addtags_info) {
							$this->error ['warning'] = sprintf ( $this->language->get ( 'error_client_already' ), $action211 );
						}
					} else {
						
						if ($addtags_info && ($this->request->get ['forms_id'] != $addtags_info ['forms_id'])) {
							$this->error ['warning'] = sprintf ( $this->language->get ( 'error_client_already' ), $action211 );
						}
					}
				}
			}
			
			if ($this->request->post ['client_add_new'] != null && $this->request->post ['client_add_new'] != "") {
				// var_dump($this->request->post);
				
				if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''] != "") {
					$emp_extid_info = $this->model_setting_tags->getTagsbyAllName ( array (
							'emp_extid' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_EXTID . ''] 
					) );
					$addtags_info = $this->model_form_form->gettagsforma ( $emp_extid_info ['tags_id'] );
					
					if ($addtags_info ['forms_id'] != null && $addtags_info ['forms_id'] != "") {
						// var_dump($emp_extid_total);echo "<hr>";
						$this->error ['warning'] = $this->language->get ( 'error_exist_client_emp_extid' );
					}
				}
				
				if ($this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''] != "") {
					$ssn_info = $this->model_setting_tags->getTagsbyAllName ( array (
							'ssn' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_SSN . ''] 
					) );
					
					$addtags_info = $this->model_form_form->gettagsforma ( $ssn_info ['tags_id'] );
					
					if ($addtags_info ['forms_id'] != null && $addtags_info ['forms_id'] != "") {
						// var_dump($ssn_total);echo "<hr>";
						$this->error ['warning'] = $this->language->get ( 'error_exist_client_ssn' );
					}
				}
			}
		}
		
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
		
		if ($this->request->get ['forms_design_id'] == CUSTOME_INTAKEID) {
			if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
				$addtags_info = $this->model_form_form->gettagsforma ( $this->request->post ['emp_tag_id'] );
				
				if ($addtags_info ['forms_id'] != null && $addtags_info ['forms_id'] != "") {
					// var_dump($emp_extid_total);echo "<hr>";
					
					$url2 .= '&forms_id=' . $addtags_info ['forms_id'];
					$url2 .= '&forms_design_id=' . $addtags_info ['custom_form_type'];
					$url2 .= '&tags_id=' . $addtags_info ['tags_id'];
					$url2 .= '&notes_id=' . $addtags_info ['notes_id'];
					$action211 = $this->url->link ( 'form/form/edit', '' . $url2, 'SSL' );
					
					if (! isset ( $this->request->get ['forms_id'] )) {
						
						if ($addtags_info) {
							$this->error ['warning'] = sprintf ( $this->language->get ( 'error_client_already' ), $action211 );
						}
					} else {
						
						if ($addtags_info && ($this->request->get ['forms_id'] != $addtags_info ['forms_id'])) {
							$this->error ['warning'] = sprintf ( $this->language->get ( 'error_client_already' ), $action211 );
						}
					}
				}
			}
		}
		
		if ($this->request->get ['forms_design_id'] == CUSTOME_I_INTAKEID) {
			
			$dob111 = $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_DOB . ''];
			
			$date = str_replace ( '-', '/', $dob111 );
			
			$res = explode ( "/", $date );
			$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
			
			$existclient = array ();
			$existclient ['emp_extid'] = $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_EXTID . ''];
			$existclient ['ssn'] = $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_SSN . ''];
			$existclient ['dob'] = $dob;
			$existclient ['emp_first_name'] = $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''];
			$existclient ['emp_last_name'] = $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''];
			
			$this->load->model ( 'setting/tags' );
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$tag_exist_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
			} else {
				$tag_exist_info = $this->model_setting_tags->getTagsbyAllName ( $existclient );
			}
			
			// var_dump($tag_exist_info['tags_id']);
			// die;
			$addtags_iffnfo = $this->model_form_form->gettagsformaintake ( $tag_exist_info ['tags_id'] );
			
			// var_dump($addtags_iffnfo['forms_id']);
			$url2 .= '&forms_id=' . $addtags_iffnfo ['forms_id'];
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			
			$url2 .= '&tags_id=' . $tag_exist_info ['tags_id'];
			$action2 = $this->url->link ( 'form/form/edit', '' . $url2, 'SSL' );
			
			if (! isset ( $this->request->get ['forms_id'] )) {
				if ($addtags_iffnfo) {
					$this->error ['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake? ';
				}
			} else {
				if ($addtags_iffnfo && ($this->request->get ['forms_id'] != $addtags_iffnfo ['forms_id'])) {
					$this->error ['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake? ';
				}
			}
			
			if (($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''] != "") && ($this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''] != null && $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''] != "")) {
				if ($this->request->get ['forms_id'] == null && $this->request->get ['forms_id'] == "") {
					if ($this->request->post ['client_add_new'] == null && $this->request->post ['client_add_new'] == "") {
						if ($this->request->post ['link_forms_id'] == null && $this->request->post ['link_forms_id'] == "") {
							
							$this->load->model ( 'form/form' );
							
							$fdata = array ();
							
							$fdata ['forms_fields_values'] = array (
									'' . TAG_EXTID . '' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_EXTID . ''],
									'' . TAG_SSN . '' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_SSN . ''],
									'' . TAG_FNAME . '' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_FNAME . ''],
									'' . TAG_LNAME . '' => $this->request->post ['design_forms'] [0] [0] ['' . TAG_I_LNAME . ''] 
							);
							// 'date_70767270' => $dob111,
							
							// var_dump($fdata);
							
							$client_form_info = $this->model_form_form->getscrnneningFormdata ( $fdata, $this->customer->getId () );
							
							if (! empty ( $client_form_info )) {
								$this->error ['warning'] = "Screening list";
								$this->error ['exit_error'] = '1';
							}
						}
					}
				}
			}
		}
		
		// echo "<hr>";
		// var_dump($this->error);
		// die;
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function newformsign() {
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$dataaaa = array ();
		
		$ddss = array ();
		$ddss1 = array ();
		if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
			$ddss [] = $resulsst ['notes_facilities_ids'];
		}
		$ddss [] = $this->customer->getId ();
		$sssssdd = implode ( ",", $ddss );
		
		$dataaaa ['facilities'] = $sssssdd;
		$this->data ['masterfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );
		
		$this->data ['is_master_facility'] = $resulsst ['is_master_facility'];
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			// var_dump($this->request->get['formreturn_id']);
			/*
			 * if($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != ""){
			 * if($this->request->get['formreturn_id'] > 0){
			 * $form_info = $this->model_form_form->getFormDatas($this->request->get['formreturn_id']);
			 * $formdata = unserialize($form_info['design_forms']);
			 *
			 * //var_dump($formdata);
			 *
			 *
			 *
			 * foreach($formdata as $key1=>$vals){
			 * foreach($vals as $key2=>$v){
			 * foreach($v as $key3=>$v3){
			 * $arrss = explode("_1_", $key3);
			 *
			 * if($arrss[1] == 'facilities_id'){
			 *
			 * if($v[$arrss[0]] != null && $v[$arrss[0]] != ""){
			 * $form_facilities_id = $v[$arrss[0]];
			 * }
			 * }
			 * }
			 * }
			 * }
			 * }
			 *
			 *
			 * if($form_facilities_id != null && $form_facilities_id != ""){
			 * $facilities_id = $form_facilities_id;
			 * $facilities_info2 = $this->model_facilities_facilities->getfacilities($form_facilities_id);
			 * $this->load->model('setting/timezone');
			 * $timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			 * $timezone_name = $timezone_info['timezone_value'];
			 *
			 * }else{
			 * if($resulsst['is_master_facility'] == '1'){
			 * if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 * $facilities_id = $this->session->data['search_facilities_id'];
			 *
			 * $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			 * $this->load->model('setting/timezone');
			 * $timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			 * $timezone_name = $timezone_info['timezone_value'];
			 * }else{
			 * $facilities_id = $this->customer->getId();
			 * $timezone_name = $this->customer->isTimezone();
			 * }
			 *
			 * }else{
			 * $facilities_id = $this->customer->getId();
			 * $timezone_name = $this->customer->isTimezone();
			 * }
			 * }
			 *
			 *
			 *
			 * $this->model_form_form->updateformfacility($facilities_id, $this->request->get['formreturn_id']);
			 * $tdata = array();
			 * $tdata['tags_id'] = $this->request->get['tags_id'];
			 * $tdata['emp_tag_id'] = $this->request->get['emp_tag_id'];
			 * $tdata['formreturn_id'] = $this->request->get['formreturn_id'];
			 * $tdata['forms_design_id'] = $this->request->get['forms_design_id'];
			 * $tdata['searchdate'] = $this->request->get['searchdate'];
			 * $tdata['facilityids'] = $this->request->get['facilityids'];
			 * $tdata['locationids'] = $this->request->get['locationids'];
			 * $tdata['task_id'] = $this->request->get['task_id'];
			 * $tdata['tagsids'] = $this->request->get['tagsids'];
			 * $tdata['facilities_id'] = $facilities_id;
			 * $tdata['facilitytimezone'] = $timezone_name;
			 * $tdata['parent_facilities_id'] = $this->customer->getId();
			 *
			 *
			 * $notes_id = $this->model_form_form->newformsign($this->request->post, $tdata);
			 * }else{
			 */
			
			if ($this->request->get ['formreturn_id'] > 0) {
				$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['formreturn_id'] );
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
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $form_facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
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
			}
			
			$this->model_form_form->updateformfacility ( $facilities_id, $this->request->get ['formreturn_id'] );
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['emp_tag_id'] = $this->request->get ['emp_tag_id'];
			$tdata ['formreturn_id'] = $this->request->get ['formreturn_id'];
			$tdata ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$tdata ['searchdate'] = $this->request->get ['searchdate'];
			$tdata ['facilityids'] = $this->request->get ['facilityids'];
			$tdata ['locationids'] = $this->request->get ['locationids'];
			$tdata ['tagsids'] = $this->request->get ['tagsids'];
			$tdata ['task_id'] = $this->request->get ['task_id'];
			$tdata ['tag_status_id'] = $this->request->get ['tag_status_id'];
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $timezone_name;
			$tdata ['parent_facilities_id'] = $this->customer->getId ();
			
		
			if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
				$notes_id = $this->model_form_form->taskforminsertsign ( $this->request->post, $tdata );
			} else {
				$notes_id = $this->model_form_form->newformsign ( $this->request->post, $tdata );
			}
			
			// }
			
			unset ( $this->session->data ['session_notes_description'] );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
				$url2 .= '&page=' . $this->request->get ['page'];
			}
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
				$url2 .= '&newnotes=1';
			}
			
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$urlf2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
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
				'facilities_id' => $this->customer->getId () 
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
		
		if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
			$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			$url2 .= '&emp_tag_id=' . $this->request->get ['emp_tag_id'];
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
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		if ($notes_id != null && $notes_id != "") {
			$url2 .= '&notes_id=' . $notes_id;
			$url2 .= '&newnotes=1';
		}
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get ['task_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/newformsign', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );
		
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
		
		/* if($this->request->get['forms_design_id'] == CUSTOME_HOMEVISIT || $this->request->get['forms_design_id'] == CUSTOME_DISCHARGE || $this->request->get['forms_design_id'] == CUSTOME_INTAKEID){ */
		
		if ($this->request->post ['client_add_new'] == null && $this->request->post ['client_add_new'] == "") {
			
			if ($this->request->get ['exittags_id'] != null && $this->request->get ['exittags_id'] != "") {
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['exittags_id'] );
			} else {
				
				// if($this->request->get['forms_design_id'] != CUSTOME_INTAKEID){
				if ($this->request->get ['emp_tag_id']) {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['emp_tag_id'] );
				}
				
				if ($this->request->get ['tags_id']) {
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
				}
				// }
			}
		}
		
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
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$this->data ['createtask'] = 1;
			$this->load->model ( 'createtask/createtask' );
			$result = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
					$this->data ['enable_facility'] = '1';
					$this->load->model ( 'facilities/facilities' );
					
					$s = array ();
					$s ['facilities_id'] = $this->customer->getId ();
					$this->data ['facilities'] = $this->model_facilities_facilities->getfacilitiess ( $s );
				}
			}
			
			$assign_to = $result ['assign_to'];
			
			$this->data ['recurrence_save_1'] = $result ['recurrence'];
			$this->data ['task_info'] = $result;
			
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
			
			$this->data ['taskintervals'] = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $result ['facilityId'] );
			$tasktype_id = $tasktype_info ['task_id'];
			
			if ($tasktype_info ['enable_location'] == '1') {
				$this->data ['enable_location'] = '1';
			} else {
				$this->data ['enable_location'] = '2';
			}
			
			if ($result ['enable_requires_approval'] == '2') {
				$this->data ['enable_requires_approval'] = '1';
			} else {
				$this->data ['enable_requires_approval'] = '2';
			}
			
			$this->load->model ( 'notes/notes' );
			
			if ($tasktype_info ['customlist_id']) {
				
				$d = array ();
				$d ['customlist_id'] = $tasktype_info ['customlist_id'];
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
						
						foreach ( $customlistvalues as $value ) {
							
							$customlistvalues_ids [] = array (
									'user_id' => $value ['customlistvalues_id'],
									'customlistvalues_name' => $value ['customlistvalues_name'],
									'required' => $value ['required'] 
							);
						}
					}
				}
				
				if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
					$this->data ['id_url'] .= '&task_id=' . $this->request->get ['task_id'];
				}
			}
			
			if ($assign_to) {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUserByUsername ( $assign_to );
				
				if ($user_info != null && $user_info != "") {
					
					$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task == '1') {
						$this->data ['recurrence_save'] = $result ['recurrence'];
					} else {
						$this->data ['recurrence_save'] = '1';
					}
				}
			}
		}
		
		$this->data ['customlistvalues_ids'] = array ();
		$this->load->model ( 'notes/notes' );
		if (isset ( $this->request->post ['customlistvalues_ids'] )) {
			$customlistvalues_ids1 = $this->request->post ['customlistvalues_ids'];
			
			foreach ( $customlistvalues_ids1 as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				if ($custom_info) {
					$this->data ['customlistvalues_ids'] [] = array (
							'user_id' => $customlistvalues_id,
							'customlistvalues_name' => $custom_info ['customlistvalues_name'] 
					);
				}
			}
		} else {
			$this->data ['customlistvalues_ids'] = $customlistvalues_ids;
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		/*
		 * }else{
		 * $form_info = $this->model_form_form->getFormDatas($this->request->get['formreturn_id']);
		 * $formdata = unserialize($form_info['design_forms']);
		 * //var_dump($formdata);
		 * $this->load->model('setting/tags');
		 *
		 * $clientayy = array();
		 *
		 * foreach($formdata as $design_forms){
		 * foreach($design_forms as $key=>$design_form){
		 * foreach($design_form as $key2=>$b){
		 *
		 * $arrss = explode("_1_", $key2);
		 *
		 * if($arrss[1] == 'tags_id'){
		 * if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
		 * if($design_form[$arrss[0].'_1_'.$arrss[1]] != null && $design_form[$arrss[0].'_1_'.$arrss[1]] != ""){
		 * $clientayy[] = $design_form[$arrss[0].'_1_'.$arrss[1]];
		 * }
		 * }
		 * }
		 *
		 * if($arrss[1] == 'tags_ids'){
		 * if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
		 * foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idst){
		 * $clientayy[] = $idst;
		 * }
		 * }
		 * }
		 * }
		 * }
		 * }
		 *
		 * $clientayy = array_unique($clientayy);
		 *
		 * if (isset($this->request->post['tagides'])) {
		 * $tagides1 = $this->request->post['tagides'];
		 * } elseif (! empty($clientayy)) {
		 * $tagides1 = $clientayy;
		 * } else {
		 * $tagides1 = array();
		 * }
		 *
		 * $this->data['tagides'] = array();
		 * $this->load->model('setting/tags');
		 *
		 * foreach ($tagides1 as $tagsid) {
		 *
		 * $tag_info = $this->model_setting_tags->getTag($tagsid);
		 * if ($tag_info) {
		 * $this->data['tagides'][] = array(
		 * 'tags_id' => $tagsid,
		 * 'emp_tag_id' => $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name']
		 * );
		 * }
		 * }
		 * $this->data['is_multiple_tags'] = IS_MAUTIPLE;
		 *
		 * }
		 */
		
		// var_dump($this->session->data['session_notes_description']);
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} elseif (! empty ( $this->session->data ['session_notes_description'] )) {
			$this->data ['comments'] = $this->session->data ['session_notes_description'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function getcustomlistvalues() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->request->get ['facilities_id'] );
			$this->load->model ( 'notes/notes' );
			
			if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
				
				$d = array ();
				
				$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
				
				// var_dump($facilityinfo['config_rolecall_customlist_id']);
				
				// $customlists = $this->model_notes_notes->getcustomlists($d);
				$d2 = array ();
				$d2 ['customlistvalueids'] = $facilityinfo ['config_rolecall_customlist_id'];
				$d2 ['customlist_name'] = $this->request->get ['customlistvalues_id'];
				$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
				if ($customlistvalues) {
					foreach ( $customlistvalues as $customlistvalue ) {
						/*
						 * $d2 = array();
						 * $d2['customlist_id'] = $customlist['customlist_id'];
						 * $d2['customlist_name'] = $this->request->get['customlistvalues_id'];
						 * $customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						 */
						$json [] = array (
								'customlistvalues_id' => $customlistvalue ['customlistvalues_id'],
								'customlistvalues_name' => $customlistvalue ['customlistvalues_name'] 
						);
						// 'customlistvalues' => $customlistvalues,
					}
				}
			}
		}
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$this->load->model ( 'createtask/createtask' );
			$result = $this->model_createtask_createtask->gettaskrow ( $this->request->get ['task_id'] );
			
			$assign_to = $result ['assign_to'];
			
			$this->data ['recurrence_save_1'] = $result ['recurrence'];
			$this->data ['task_info'] = $result;
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $result ['facilityId'] );
			$tasktype_id = $tasktype_info ['task_id'];
			
			$this->load->model ( 'notes/notes' );
			
			if ($tasktype_info ['customlist_id']) {
				
				$d = array ();
				$d ['customlist_id'] = $tasktype_info ['customlist_id'];
				// $customlists = $this->model_notes_notes->getcustomlists($d);
				
				$d2 = array ();
				$d2 ['customlistvalueids'] = $tasktype_info ['customlist_id'];
				$d2 ['customlist_name'] = $this->request->get ['customlistvalues_id'];
				$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
				if ($customlistvalues) {
					foreach ( $customlistvalues as $customlistvalue ) {
						/*
						 * $d2 = array();
						 * $d2['customlist_id'] = $customlist['customlist_id'];
						 * $d2['customlist_name'] = $this->request->get['customlistvalues_id'];
						 * $customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						 */
						$json [] = array (
								'customlistvalues_id' => $customlistvalue ['customlistvalues_id'],
								'customlistvalues_name' => $customlistvalue ['customlistvalues_name'] 
						);
						// 'customlistvalues' => $customlistvalues,
					}
				}
			}
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getsforms() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		$this->load->model ( 'form/form' );
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$data = array (
				'facilities_id' => $facilities_id,
				'filter_name' => $this->request->get ['filter_name'],
				'start' => 0,
				'limit' => $limit 
		);
		
		$users = $this->model_form_form->getssforms ( $data );
		
		$this->load->model ( 'notes/notes' );
		
		foreach ( $users as $result ) {
			$design_forms = unserialize ( $result ['design_forms'] );
			// var_dump($design_forms);
			
			$notes_info = $this->model_notes_notes->getnotes ( $result ['notes_id'] );
			
			// echo "<hr>";
			
			$clientname = "";
			if ($design_forms [0] [0] ['' . TAG_FNAME . ''] != null && $design_forms [0] [0] ['' . TAG_FNAME . ''] != "") {
				$clientname = $design_forms [0] [0] ['' . TAG_FNAME . ''] . ' ' . $design_forms [0] [0] ['' . TAG_MNAME . ''] . ' ' . $design_forms [0] [0] ['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms [0] [0] ['' . TAG_DOB . ''] . ' | Screening ' . $design_forms [0] [0] ['' . TAG_SCREENING . ''];
			} else {
				$clientname = $result ['incident_number'] . ' ' . date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
			}
			
			if ($design_forms [0] [0] ['' . TAG_SCREENING . ''] != "0000-00-00") {
				$date_of_screening = $design_forms [0] [0] ['' . TAG_SCREENING . ''];
			} else {
				$date_of_screening = date ( 'm-d-Y' );
			}
			if ($design_forms [0] [0] ['' . TAG_DOB . ''] != "0000-00-00") {
				$dob = $design_forms [0] [0] ['' . TAG_DOB . ''];
			} else {
				$dob = '';
			}
			
			if ($design_forms [0] [0] ['' . TAG_DOB . ''] != "0000-00-00") {
				
				$dob111 = $design_forms [0] [0] ['' . TAG_DOB . ''];
				
				$date = str_replace ( '-', '/', $dob111 );
				
				$res = explode ( "/", $date );
				$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
				
				$dobm = date ( 'm', strtotime ( $dob ) );
			} else {
				$dobm = '';
			}
			
			if ($design_forms [0] [0] ['' . TAG_DOB . ''] != "0000-00-00") {
				$dob111 = $design_forms [0] [0] ['' . TAG_DOB . ''];
				
				$date = str_replace ( '-', '/', $dob111 );
				
				$res = explode ( "/", $date );
				$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
				$dobd = date ( 'd', strtotime ( $dob ) );
			} else {
				$dobd = '';
			}
			
			if ($design_forms [0] [0] ['' . TAG_DOB . ''] != "0000-00-00") {
				$dob111 = $design_forms [0] [0] ['' . TAG_DOB . ''];
				
				$date = str_replace ( '-', '/', $dob111 );
				
				$res = explode ( "/", $date );
				$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
				$doby = date ( 'Y', strtotime ( $dob ) );
			} else {
				$doby = '';
			}
			
			/*
			 * if($design_forms[0][0][''.TAG_GENDER.''] == 'Male'){
			 * $gender = '33';
			 * }
			 * if($design_forms[0][0][''.TAG_GENDER.''] == 'Female'){
			 * $gender = '34';
			 * }
			 *
			 * if($design_forms[0][0][''.TAG_GENDER.''] == 'Inmate'){
			 * $gender = '35';
			 * }
			 *
			 * if($design_forms[0][0][''.TAG_GENDER.''] == 'Patient'){
			 * $gender = '49';
			 * }
			 */
			
			if ($result ['upload_file']) {
				$upload_file = $result ['upload_file'];
				
				$image_url = file_get_contents ( $upload_file );
				$image_url1 = 'data:image/jpg;base64,' . base64_encode ( $image_url );
			} else {
				$upload_file = '';
				$image_url1 = '';
			}
			
			$dob1111 = date ( 'm-d-Y', strtotime ( $dob ) );
			
			$json [] = array (
					'incident_number' => $clientname,
					'custom_form_type' => $result ['custom_form_type'],
					'forms_id' => $result ['forms_id'],
					'emp_first_name' => $design_forms [0] [0] ['' . TAG_FNAME . ''],
					'emp_middle_name' => $design_forms [0] [0] ['' . TAG_MNAME . ''],
					'emp_last_name' => $design_forms [0] [0] ['' . TAG_LNAME . ''],
					'emergency_contact' => $design_forms [0] [0] ['' . TAG_PHONE . ''],
					'dob' => $dob1111,
					'month' => $dobm,
					'date' => $dobd,
					'year' => $doby,
					'age' => $design_forms [0] [0] ['' . TAG_AGE . ''],
					'gender' => $design_forms [0] [0] ['' . TAG_GENDER . ''],
					'location_address' => $design_forms [0] [0] ['' . TAG_ADDRESS . ''],
					// 'address_street2'=> '',//$design_forms[0][0]['text_75675662'],
					'person_screening' => $notes_info ['user_id'],
					'date_of_screening' => $date_of_screening,
					'ssn' => $design_forms [0] [0] ['' . TAG_SSN . ''],
					// 'state'=> $design_forms[0][0]['text_49932949'],
					// 'city'=> $design_forms[0][0]['text_36668004'],
					// 'zipcode'=> $design_forms[0][0]['text_64928499'],
					'emp_extid' => $design_forms [0] [0] ['' . TAG_EXTID . ''],
					
					'f_firstname' => $design_forms [3] [0] ['text_52533749'],
					'f_lastname' => $design_forms [3] [0] ['text_38512534'],
					'f_relationship' => $design_forms [3] [0] ['text_55964523'],
					'f_address' => $design_forms [3] [0] ['text_56311191'],
					'f_home_phone' => $design_forms [3] [0] ['text_85226934'],
					'f_cell_phone' => $design_forms [3] [0] ['text_99743446'],
					'f_work_phone' => $design_forms [3] [0] ['text_79479558'],
					'f_other_home' => $design_forms [3] [0] ['textarea_79546948'],
					'f_emergency_contact' => $design_forms [3] [0] ['text_86161965'],
					'f_phone' => $design_forms [3] [0] ['text_56618482'],
					'age11' => $design_forms [1] [0] ['text_68109996'],
					'upload_file' => $upload_file,
					'image_url1' => $image_url1 
			);
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getformsection() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$forms_section_key = $this->request->get ['forms_section_key'];
		$add_row1 = $this->request->get ['add_row'];
		$add_v2 = $this->request->get ['add_v2'];
		$current_row = $this->request->get ['current_row'];
		if ($add_v2 != null && $add_v2 != "") {
			$add_v2a = explode ( " ", $add_v2 );
			
			$add_v2am = max ( $add_v2a );
		} else {
			$add_v2a = array ();
		}
		
		if ($add_v2am != null && $add_v2am != "") {
			$add_row = $add_row1 + $add_v2am;
		} else {
			$add_row = $add_row1;
		}
		
		// var_dump($add_row);
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$section_info = $this->model_form_form->getformsectiondata ( $forms_section_key );
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$this->load->model ( 'facilities/facilities' );
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		$this->data ['layouts'] = explode ( ",", $fromdatas ['form_layout'] );
		$this->data ['link_form_fieldall'] = $fromdatas ['link_form_fieldall'];
		
		$this->data ['form_name'] = $fromdatas ['form_name'];
		$this->data ['display_image'] = $fromdatas ['display_image'];
		$this->data ['display_signature'] = $fromdatas ['display_signature'];
		$this->data ['forms_setting'] = $fromdatas ['forms_setting'];
		$this->data ['display_add_row'] = $fromdatas ['display_add_row'];
		$this->data ['display_content_postion'] = $fromdatas ['display_content_postion'];
		$this->data ['is_client_active'] = $fromdatas ['is_client_active'];
		$this->data ['form_type'] = $fromdatas ['form_type'];
		$this->data ['db_table_name'] = $fromdatas ['db_table_name'];
		$this->data ['client_reqired'] = $fromdatas ['client_reqired'];
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$this->data ['facility_name'] = $facilities_info ['facility'];
		
		$s = array ();
		$s ['facilities_id'] = $facilities_id;
		$s ['sbfacility'] = 1;
		
		$sfacilities = $this->model_facilities_facilities->getfacilitiess ( $s );
		
		if ($this->request->get ['task_id'] != null && $this->request->get ['task_id'] != "") {
			$task_id_url = '&task_id=' . $this->request->get ['task_id'];
		}
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$facilities_id_url = '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		$json = array ();
		$html = '';
		if ($section_info != NULL && $section_info != "") {
			
			/*
			 * $json[] = array(
			 *
			 * 'forms_design_section_id' => $section_info['forms_design_section_id'],
			 * 'forms_section_key' => $section_info['forms_section_key'],
			 * 'forms_id' => $section_info['forms_id'],
			 * 'forms_design_section_fields_id' => $section_info['forms_design_section_fields_id'],
			 * 'forms_fields' => unserialize($section_info['forms_fields']),
			 * );
			 */
			
			$forms_fields = unserialize ( $section_info ['forms_fields'] );
			
			$randcolor = $this->generateRandomColor ();
			
			$add_row_name = $add_row;
			
			$template = new Template ();
			$template->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$template->data ['forms_section_key'] = $forms_section_key;
			$template->data ['load'] = $this->load;
			$template->data ['forms_fields'] = $forms_fields;
			$template->data ['sfacilities'] = $sfacilities;
			$template->data ['randcolor'] = $randcolor;
			$template->data ['current_row'] = $current_row;
			$template->data ['i'] = $add_row_name;
			$template->data ['layouts'] = $this->data ['layouts'];
			$template->data ['display_image'] = $this->data ['display_image'];
			$template->data ['display_signature'] = $this->data ['display_signature'];
			$template->data ['forms_setting'] = $this->data ['forms_setting'];
			// $template->data['display_add_row'] = $this->data['display_add_row'];
			$template->data ['display_content_postion'] = $this->data ['display_content_postion'];
			$template->data ['is_client_active'] = $this->data ['is_client_active'];
			$template->data ['form_type'] = $this->data ['form_type'];
			$template->data ['db_table_name'] = $this->data ['db_table_name'];
			$template->data ['client_reqired'] = $this->data ['client_reqired'];
			$template->data ['link_form_fieldall'] = $this->data ['link_form_fieldall'];
			$template->data ['facilities_id_url'] = $facilities_id_url;
			$template->data ['task_id_url'] = $task_id_url;
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/form/add_section.php' )) {
				$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/form/add_section.php' );
			}
			
			// $html .= '<div class="col-sm-12">';
			
			// $html .= '<button type="button" onclick="deletesectionrow(\''.$forms_section_key.'\',\''.$add_row.'\');" title="" class="btn btn-primary" style="margin-top: 15px;">Delete Row</button>';
			
			// $html .= '</div>';
		}
		
		$this->response->setOutput ( json_encode ( $html ) );
	}
	public function generateRandomColor($count = 1) {
		/*
		 * if($count > 1){
		 * $color = array();
		 * for($i=0; $count > $i; $i++)
		 * $color[count($color)] = generateRandomColor();
		 * }else{
		 * $rand = array_merge(range(0, 9), range('a', 'f'));
		 * $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
		 * }
		 */
		$input = array (
				"#bdbdbd",
				"#a8a8a8",
				"#e9e9e9",
				"#ded9c0",
				"#cfe3e4",
				"#dfd5d6",
				"#d5ced5",
				"#cfdce2",
				"#e7d5d2",
				"#c7c1d0",
				"#e6e6e6",
				"#d3d3d3",
				"#aaaaaa",
				"#cccccc",
				"#e0d7e4",
				"#ffffff",
				"#e9e2eb",
				"#d1c4d7",
				"#baa6c2",
				"#edd3ce",
				"#d5bdb9",
				"#bda8a4",
				"#d8c396" 
		);
		
		$rand_keys = array_rand ( $input, 2 );
		$color = $input [$rand_keys [0]];
		return $color;
	}
	public function autocomplete() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$this->data ['forms_design_id'] = $this->request->get ['forms_design_id'];
		$this->data ['forms_id'] = $this->request->get ['forms_id'];
		
		$json = array ();
		
		$this->load->model ( 'form/form' );
		
		$fromdatas = $this->model_form_form->getFormdata ( $this->request->get ['forms_design_id'] );
		
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
		
		$data2 = array (
				'is_parent_child' => '1',
				'forms_id' => $parent_id,
				'sort' => 'page_number' 
		);
		
		$childforms = $this->model_form_form->getforms ( $data2 );
		$totalchildforms = $this->model_form_form->getTotalforms ( $data2 );
		
		if (! empty ( $childforms )) {
			
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
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$urlf2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$urlf2 .= '&client_add_new=' . $this->request->get ['client_add_new'];
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
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$urlf2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$urlf2 .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$urlf2 .= '&formreturn_id=' . $pformreturn_id;
			}
			
			if ($this->request->get ['is_form_open'] == "1") {
				$furl = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/edit', $urlf2, true ) );
			} else {
				$furl = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/edit', $urlf2, true ) );
			}
			
			$json [] = array (
					'forms_id' => $fromdatas2 ['forms_id'],
					'form_name' => $fromdatas2 ['form_name'],
					'parent_id' => $fromdatas2 ['parent_id'],
					'page_number' => $fromdatas2 ['page_number'],
					'href' => $furl 
			);
			
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
					// echo "<hr>";
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
				
				if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
					$urlc .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
				}
				if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
					$urlc .= '&client_add_new=' . $this->request->get ['client_add_new'];
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
				if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
					$urlc .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
				}
				if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
					$urlc .= '&last_notesID=' . $this->request->get ['last_notesID'];
				}
				
				if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
					$urlc .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
				}
				
				if ($pformreturn_id != null && $pformreturn_id != "") {
					$urlc .= '&formreturn_id=' . $pformreturn_id;
				}
				
				if ($this->request->get ['is_form_open'] == "1") {
					$furl2 = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/edit', $urlc, true ) );
				} else {
					$furl2 = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/edit', $urlc, true ) );
				}
				$json [] = array (
						'forms_id' => $childform ['forms_id'],
						'form_name' => $childform ['form_name'],
						'parent_id' => $childform ['parent_id'],
						'page_number' => $childform ['page_number'],
						'href' => $furl2 
				);
			}
		} else {
			
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
			
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$urlf .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			if ($this->request->get ['client_add_new'] != null && $this->request->get ['client_add_new'] != "") {
				$urlf .= '&client_add_new=' . $this->request->get ['client_add_new'];
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
			if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
				$urlf .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$urlf .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			
			if ($this->request->get ['archive_forms_id'] != null && $this->request->get ['archive_forms_id'] != "") {
				$urlf .= '&archive_forms_id=' . $this->request->get ['archive_forms_id'];
			}
			
			if ($pformreturn_id != null && $pformreturn_id != "") {
				$urlf .= '&formreturn_id=' . $pformreturn_id;
			}
			if ($this->request->get ['is_form_open'] == "1") {
				$furl3 = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/edit', $urlf, true ) );
			} else {
				$furl3 = str_replace ( '&amp;', '&', $this->url->link ( 'services/form/edit', $urlf, true ) );
			}
			
			$json [] = array (
					'forms_id' => $fromdatas ['forms_id'],
					'form_name' => $fromdatas ['form_name'],
					'parent_id' => $fromdatas ['parent_id'],
					'page_number' => $fromdatas ['page_number'],
					'href' => $furl3 
			);
		}
		
		// var_dump($json);
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getShift() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		
		$this->load->model ( 'setting/shift' );
		
		$adata = array ();
		$adata ['status'] = 1;
		$fromdatas = $this->model_setting_shift->getshifts ( $adata );
		
		foreach ( $fromdatas as $fields ) {
			$json [] = array (
					'shift_id' => $fields ['shift_id'],
					'shift_name' => $fields ['shift_name'] 
			);
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function exittags() {
		$url31 = "";
		
		if ($this->request->get ['emp_extid'] != null && $this->request->get ['emp_extid'] != "") {
			$url31 .= '&emp_extid=' . $this->request->get ['emp_extid'];
		}
		if ($this->request->get ['ssn'] != null && $this->request->get ['ssn'] != "") {
			$url31 .= '&ssn=' . $this->request->get ['ssn'];
		}
		if ($this->request->get ['emp_first_name'] != null && $this->request->get ['emp_first_name'] != "") {
			$url31 .= '&emp_first_name=' . $this->request->get ['emp_first_name'];
		}
		if ($this->request->get ['emp_last_name'] != null && $this->request->get ['emp_last_name'] != "") {
			$url31 .= '&emp_last_name=' . $this->request->get ['emp_last_name'];
		}
		if ($this->request->get ['dob'] != null && $this->request->get ['dob'] != "") {
			
			$dob111 = $this->request->get ['dob'];
			
			$date = str_replace ( '-', '/', $dob111 );
			
			$res = explode ( "/", $date );
			$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
			
			$url31 .= '&dob=' . $this->request->get ['dob'];
		}
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateexitstags ()) {
			
			$this->data ['exittags_id'] = $this->request->post ['exittags_id'];
			$this->data ['select_exittags_id'] = '1';
			
			$this->load->model ( 'setting/tags' );
			$this->data ['tag_info'] = $this->model_setting_tags->getTag ( $this->request->post ['exittags_id'] );
		}
		
		if ($this->request->get ['client_add_new'] == '1') {
			$this->data ['client_add_new'] = $this->request->get ['client_add_new'];
		}
		
		$this->load->model ( 'setting/tags' );
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$this->load->model ( 'form/form' );
		$this->data ['action'] = $this->url->link ( 'form/form/exittags', $url31, true );
		$this->data ['add_new_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/exittags&client_add_new=1', '' . $url31, 'SSL' ) );
		
		$data = array (
				'facilities_id' => $facilities_id,
				'exits_emp_extid' => $this->request->get ['emp_extid'],
				'exits_ssn' => $this->request->get ['ssn'],
				'exits_emp_first_name' => $this->request->get ['emp_first_name'],
				'exits_emp_last_name' => $this->request->get ['emp_last_name'],
				'exits_dob' => $dob,
				'tags_exits' => '1',
				'status' => '1',
				'sort' => 'emp_tag_id',
				'order' => 'ASC' 
		);
		
		// var_dump($data);
		
		$results = $this->model_setting_tags->getTags ( $data );
		
		// var_dump($results);
		
		foreach ( $results as $result ) {
			
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
			
			/*
			 * if($result['gender'] == '1'){
			 * $gender = 'Male';
			 * }
			 * if($result['gender'] == '2'){
			 * $gender = 'Female';
			 * }
			 */
			
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
			
			$fullname = $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'] . ' ' . $result ['emp_last_name'] . $ssn . $emp_extid . $dob;
			
			$addtags_info = $this->model_form_form->gettagsforma ( $result ['tags_id'] );
			
			$this->data ['tags'] [] = array (
					'tags_id' => $result ['tags_id'],
					'screening_tags_id' => $addtags_info ['tags_id'],
					'emp_tag_id' => $result ['emp_tag_id'],
					'emp_first_name' => $result ['emp_first_name'],
					'emp_last_name' => $result ['emp_last_name'],
					'location_address' => $result ['location_address'],
					'dob' => $dob,
					'medication' => $result ['medication'],
					'gender' => $result ['gender'],
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
					'upload_file' => $upload_file,
					'image_url1' => $image_url1 
			);
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
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->request->post ['exittags_id'] )) {
			$this->data ['exittags_id'] = $this->request->post ['exittags_id'];
		} else {
			$this->data ['exittags_id'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/tags_exitform.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateexitstags() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['exittags_id'] == null && $this->request->post ['exittags_id'] == "") {
			$this->error ['warning'] = $this->language->get ( 'error_select' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function activeformsign() {
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$dataaaa = array ();
		
		$ddss = array ();
		$ddss1 = array ();
		if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
			$ddss [] = $resulsst ['notes_facilities_ids'];
		}
		$ddss [] = $this->customer->getId ();
		$sssssdd = implode ( ",", $ddss );
		
		$dataaaa ['facilities'] = $sssssdd;
		$this->data ['masterfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );
		
		$this->data ['is_master_facility'] = $resulsst ['is_master_facility'];
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
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
			
			// var_dump($facilities_id);
			
			$this->model_form_form->updateformfacility ( $facilities_id, $this->request->get ['formreturn_id'] );
			$tdata = array ();
			$tdata ['tags_id'] = $this->request->get ['tags_id'];
			$tdata ['tag_status_id'] = $this->request->get ['tag_status_id'];
			$tdata ['emp_tag_id'] = $this->request->get ['emp_tag_id'];
			$tdata ['formreturn_id'] = $this->request->get ['formreturn_id'];
			$tdata ['forms_design_id'] = $this->request->get ['forms_design_id'];
			$tdata ['searchdate'] = $this->request->get ['searchdate'];
			$tdata ['activeform_id'] = $this->request->get ['activeform_id'];
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $timezone_name;
			$tdata ['parent_facilities_id'] = $this->customer->getId ();
			
			$notes_id = $this->model_form_form->activeformsign ( $this->request->post, $tdata );
			
			unset ( $this->session->data ['session_notes_description'] );
			
			$this->session->data ['success_add_form'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['page'] != null && $this->request->get ['page'] != "") {
				$url2 .= '&page=' . $this->request->get ['page'];
			}
			if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
			}
			
			if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
			}
			
			if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
			}
			if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
				$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
			}
			
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
				$url2 .= '&newnotes=1';
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
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
				'facilities_id' => $this->customer->getId () 
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
		
		if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
			$url2 .= '&formreturn_id=' . $this->request->get ['formreturn_id'];
		}
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['forms_design_id'] != null && $this->request->get ['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get ['forms_design_id'];
		}
		
		if ($this->request->get ['activeform_id'] != null && $this->request->get ['activeform_id'] != "") {
			$url2 .= '&activeform_id=' . $this->request->get ['activeform_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		if ($this->request->get ['emp_tag_id'] != null && $this->request->get ['emp_tag_id'] != "") {
			$url2 .= '&emp_tag_id=' . $this->request->get ['emp_tag_id'];
		}
		
		if ($notes_id != null && $notes_id != "") {
			$url2 .= '&notes_id=' . $notes_id;
			$url2 .= '&newnotes=1';
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form/activeformsign', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . $url2, 'SSL' ) );
		
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
		$this->load->model ( 'form/form' );
		
		if ($this->request->get ['emp_tag_id']) {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['emp_tag_id'] );
		} else 

		if ($this->request->get ['tags_id']) {
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		} else 

		if ($this->request->get ['formreturn_id'] != null && $this->request->get ['formreturn_id'] != "") {
			$form_info = $this->model_form_form->getFormDatas ( $this->request->get ['formreturn_id'] );
			$formdata = unserialize ( $form_info ['design_forms'] );
			foreach ( $formdata as $design_forms ) {
				foreach ( $design_forms as $key => $design_form ) {
					foreach ( $design_form as $key2 => $b ) {
						$arrss = explode ( "_1_", $key2 );
						if ($design_form [$arrss [0] . '_1_add_in_facility'] == '1') {
							if ($arrss [1] == 'facilities_id') {
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									$form_facilities_id = $design_form [$arrss [0]];
								}
							}
						}
						
						if ($arrss [1] == 'tags_id') {
							if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
								if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
									$tag_info = $this->model_setting_tags->getTag ( $design_form [$arrss [0] . '_1_' . $arrss [1]] );
								}
							}
						}
						
						if ($arrss [1] == 'tags_ids') {
							if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
								foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
									$tag_info = $this->model_setting_tags->getTag ( $idst );
								}
							}
						}
						
						if ($arrss [1] == 'tagsids') {
							if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
								foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
									$tag_info = $this->model_setting_tags->getTag ( $idst );
								}
							}
						}
					}
				}
			}
		}
		
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
		} elseif (! empty ( $this->session->data ['session_notes_description'] )) {
			$this->data ['comments'] = $this->session->data ['session_notes_description'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
}