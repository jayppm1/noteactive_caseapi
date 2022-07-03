<?php
class Controllernotesnotes extends Controller {
	private $error = array ();
	public function savecontent() {
		if ($this->request->post ['notes_description']) {
			//$this->session->data ['session_notes_description'] = $this->request->post ['notes_description'];
		}
	}
	public function index() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			
			if ($this->customer->isLogged ()) {
				
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '', 'SSL' ) );
			}
			
			unset ( $this->session->data ['notesdatas'] );
			unset ( $this->session->data ['text_color_cut'] );
			unset ( $this->session->data ['highlighter_id'] );
			unset ( $this->session->data ['text_color'] );
			unset ( $this->session->data ['note_date'] );
			unset ( $this->session->data ['notes_file'] );
			
			if ($this->request->get ['reset'] == '1') {
				unset ( $this->session->data ['note_date_search'] );
				unset ( $this->session->data ['note_date_from'] );
				unset ( $this->session->data ['note_date_to'] );
				unset ( $this->session->data ['keyword'] );
				// unset($this->session->data['user_id']);
				unset ( $this->session->data ['emp_tag_id'] );
				unset ( $this->session->data ['keyword_file'] );
				$this->redirect ( $this->url->link ( 'notes/notes', '' . $url, 'SSL' ) );
			}
			
			$this->data ['rediectUlr'] = $this->url->link ( 'notes/notes', '' . $url, 'SSL' );
			$this->data ['resetUrl'] = $this->url->link ( 'notes/notes', '' . '&reset=1' . $url, 'SSL' );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/search', '' . $url2, 'SSL' );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Notes List' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNoteslist', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function insert() {
		try {
			
			unset ( $this->session->data ['timeout'] );
			$this->language->load ( 'notes/notes' );
			
			$this->data ['showLoader'] = "1";
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			
			if (! $this->customer->isLogged ()) {
				
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			
			if ($this->request->get ['search_facilities_id'] > 0) {
				$this->session->data ['search_facilities_id'] = $this->request->get ['search_facilities_id'];
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
			}
			if ($this->request->get ['searchall'] == '1') {
				unset ( $this->session->data ['search_facilities_id'] );
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
			}
			
			if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm () && $this->request->post ['advance_search'] != '1') {
				
				/*
				 * $this->session->data['notesdatas'] =
				 * $this->request->post['arraynotes'];
				 * //$this->session->data['notesfiles'] = $this->request->files;
				 * $this->session->data['highlighter_id'] =
				 * $this->request->post['highlighter_id'];
				 * $this->session->data['text_color_cut'] =
				 * $this->request->post['text_color_cut'];
				 * $this->session->data['text_color'] =
				 * $this->request->post['text_color'];
				 * $this->session->data['note_date'] =
				 * $this->request->post['note_date'];
				 *
				 * $this->session->data['keyword_file'] =
				 * $this->request->post['keyword_file'];
				 *
				 * $this->session->data['notes_file'] =
				 * $this->request->post['notes_file'];
				 */
				$notes_id = $this->model_notes_notes->addnotes ( $this->request->post, $this->customer->getId () );
				
				$this->session->data ['notes_id'] = $notes_id;
				
				if ($this->session->data ['isPrivate'] == '1') {
					$this->session->data ['success3'] = $this->language->get ( 'text_success' );
				} else {
					$this->session->data ['success2'] = $this->language->get ( 'text_success' );
				}
				$url2 = "";
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$url2 = '&searchdate=' . $this->request->get ['searchdate'];
				}
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
			}
			
			if ($this->request->post ['advance_search'] == '1') {
				
				$sres = explode ( "-", $this->request->post ['note_date_from'] );
				
				// var_dump($sres);
				
				$createdate1 = $sres [2] . "-" . $sres [0] . "-" . $sres [1];
				// var_dump($createdate1);
				
				$sres2 = explode ( "-", $this->request->post ['note_date_to'] );
				
				$createdate12 = $sres2 [2] . "-" . $sres2 [0] . "-" . $sres2 [1];
				// var_dump($createdate12);
				
				// echo rand();
				
				// echo "<br>";
				
				$diff = date_diff ( $createdate1, $createdate12 );
				
				// echo rand();
				// var_dump($diff->format("%R%a"));
				// die;
				if ($createdate1 > $createdate12) {
					$url2 .= '&error2=1';
					$this->redirect ( $this->url->link ( 'notes/notes/search', '' . $url2, 'SSL' ) );
					return false;
				}
				
				// var_dump($this->request->post);
				
				if ($this->request->post ['search_time_start'] != null && $this->request->post ['search_time_start'] != "") {
					
					if ($this->request->post ['search_time_to'] == null && $this->request->post ['search_time_to'] == "") {
						$url2 .= '&error2=2';
						$this->redirect ( $this->url->link ( 'notes/notes/search', '' . $url2, 'SSL' ) );
						return false;
					}
				}
				
				if ($this->request->post ['search_time_to'] != null && $this->request->post ['search_time_to'] != "") {
					
					if ($this->request->post ['search_time_start'] == null && $this->request->post ['search_time_start'] == "") {
						$url2 .= '&error2=2';
						$this->redirect ( $this->url->link ( 'notes/notes/search', '' . $url2, 'SSL' ) );
						return false;
					}
				}
				
				// die;
			}
			
			$this->getForm ();
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Notes Insert' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNotesinsert', $activity_data2 );
		}
	}
	
	public function savenotes() {
		// var_dump($this->request->post);
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		unset ( $this->session->data ['show_hidden_info'] );
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		
		if ((utf8_strlen ( trim ( $this->request->post ['notes_description'] ) ) < 1)) {
			$json ['error'] ['notes_description'] = $this->language->get ( 'error_required' );
		}
		
		if ((utf8_strlen ( trim ( $this->request->post ['notetime'] ) ) < 1)) {
			$json ['error'] ['notetime'] = 'Please select note time';
		}
		// var_dump($this->request->post);
		
		if ($this->request->post ['note_date'] != null && $this->request->post ['note_date'] != "") {
			$note_date = date ( 'm-d-Y', strtotime ( $this->request->post ['note_date'] ) );
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
			
			if ($current_date < $note_date) {
				$json ['error'] ['warning'] = "You can not add future notes";
			}
		}
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {

			$this->load->model ('setting/tags');

			$tags_info1 = $this->model_setting_tags->getTag ($this->request->get ['tags_id']);

			if($tags_info1){

				$facilities_id=$tags_info1['facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];

			}else{

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

		}else{
		
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
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_required_activenote'] == '1') {
			if ($this->request->post ['keyword_file'] == '' && $this->request->post ['keyword_file'] == '') {
				$json ['error'] ['warning_active'] = 'An ActiveNote required before saving a note';
			}
		}
		
		if (! $json) {
			
			$this->load->model ( 'notes/notes' );
			
			$aids = array();
			
			$alocationids = array();
			
			$notes_description = $this->request->post['notes_description'];
			
			if($this->request->post['locationids'] != null && $this->request->post['locationids'] != ""){
				$sssssdds2 = explode(",",$this->request->post['locationids']);
				$abdcds = array_unique($sssssdds2);
				$this->load->model('setting/locations');
				
				foreach($abdcds as $locationid){
					$location_info12 = $this->model_setting_locations->getlocation($locationid);
					$locationname = '|'.$location_info12['location_name'];
					$notes_description = str_ireplace($locationname,"",$notes_description);
					
					$locationname = '| '.$location_info12['location_name'];
					$notes_description = str_ireplace($locationname,"",$notes_description);
					
					/*$alocationids[] = array(
						'locations_id'=>$locationid,
						'location_name'=>$location_info12['location_name'],
						'facilities_id'=>$location_info12['facilities_id'],
					);*/
					
					$aids[$location_info12['facilities_id']]['locations'][] = array (
						'valueId' => $locationid,
					);
				}
			}
			
			
			$atagsids = array();
			if($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != ""){
				$this->load->model('setting/tags');
				$sssssddsd = explode(",",$this->request->post['tagsids']);
				$abdca = array_unique($sssssddsd);
				
				foreach($abdca as $tagsid){
					$tag_info = $this->model_setting_tags->getTag($tagsid);
					$empfirst_name = '|'.$tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
					$notes_description = str_ireplace($empfirst_name,"", $notes_description);
					
					$empfirst_name = '| '.$tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
					$notes_description = str_ireplace($empfirst_name,"", $notes_description);
					/*$atagsids[] = array(
						'tags_id'=>$tagsid,
						'facilities_id'=>$tag_info['facilities_id'],
					);*/
					
					$aids[$tag_info['facilities_id']]['clients'][] = array (
						'valueId' => $tagsid,
					);
				}
			}
			
			if($this->request->post['facilityids'] != null && $this->request->post['facilityids'] != ""){
				$this->load->model('facilities/facilities');
				$sssssddsg = explode(",",$this->request->post['facilityids']);
				$abdcg = array_unique($sssssddsg);
				foreach($abdcg as $fid){
					
					$facilityinfo = $this->model_facilities_facilities->getfacilities($fid);
					
					$notes_description = str_ireplace('|'.$facilityinfo['facility'],"", $notes_description);
					$notes_description = str_ireplace('| '.$facilityinfo['facility'],"", $notes_description);
					
					$aids[$facilityinfo['facilities_id']]['facilitiesids'][] = array (
						'valueId' => $fid,
					);
				}
				
			}
			
			if($this->request->post['userids'] != null && $this->request->post['userids'] != ""){
				$this->load->model('user/user');
				$ssssssuser = explode(",",$this->request->post['userids']);
				$ssabdcg = array_unique($ssssssuser);
			
				foreach($ssabdcg as $usid){
					
					$userinfo = $this->model_user_user->getUser($usid);
					$notes_description = str_ireplace('|'.$userinfo['username'],"", $notes_description);
					$notes_description = str_ireplace('| '.$userinfo['username'],"", $notes_description);
					$aids[$facilities_id]['usersids'][] = array (
						'valueId' => $usid,
					);
				}
				
			}
			
			$notesids = array();
			
			/*
			if($resulsst['no_distribution'] == '1'){
				foreach($aids as  $aid){
					$this->request->post['keyword_file1'] = array();
					$this->request->post['tags_id_list1'] = array();
					$this->request->post ['locationsid'] = array();
					$aidsss = array();
					$aidsss1 = '';
					$locationname1 = "";
					if($aid['clients'] != null && $aid['clients'] != ""){
						$tags_id_list = array();
						foreach($aid['clients'] as $clid){
							$tags_id_list[] = $clid['valueId'];
						}
						
						$this->request->post['tags_id_list1'] = $tags_id_list;
						
						$this->request->post['notes_description'] = $notes_description;
					}
					
					if($aid['locations'] != null && $aid['locations'] != ""){
						$locationsid = array();
						foreach($aid['locations'] as $locid){
							
							$location_info12 = $this->model_setting_locations->getlocation($locid['valueId']);
							$locationname1 .= $location_info12['location_name'].' | ';
					
							$locationsid[] = $locid['valueId'];
						}
						$this->request->post['locationsid'] = $locationsid;
						
						$this->request->post['notes_description'] = $locationname1 .' '. $notes_description;
					}
					
					//var_dump($this->request->post['notes_description']);
					
					$notes_id = $this->model_notes_notes->addnotes ( $this->request->post, $facilities_id );
					$notesids[] = $notes_id;
					$notesids1 = implode(",",$notesids);
					$url2 = '&notes_ids=' . $notesids1;
				}
				
			}*/
			
			if(!empty($aids)){
				foreach($aids as $facilities_id =>$aid){
					$this->request->post['keyword_file1'] = array();
					$this->request->post['tags_id_list1'] = array();
					$this->request->post ['locationsid'] = array();
					$aidsss = array();
					$aidsss1 = '';
					$locationname1 = "";
					if($aid['clients'] != null && $aid['clients'] != ""){
						$tags_id_list = array();
						foreach($aid['clients'] as $clid){
							$tags_id_list[] = $clid['valueId'];
						}
						
						$this->request->post['tags_id_list1'] = $tags_id_list;
						
						$this->request->post['notes_description'] = $notes_description;
					}
					
					if($aid['locations'] != null && $aid['locations'] != ""){
						$locationsid = array();
						foreach($aid['locations'] as $locid){
							
							$location_info12 = $this->model_setting_locations->getlocation($locid['valueId']);
							$locationname1 .= $location_info12['location_name'].' | ';
					
							$locationsid[] = $locid['valueId'];
						}
						$this->request->post['locationsid'] = $locationsid;
						
						$this->request->post['notes_description'] = $locationname1 .' '. $notes_description;
					}
					
					if($aid['usersids'] != null && $aid['usersids'] != ""){
						$usid = array();
						foreach($aid['usersids'] as $usercid){
							
							$user_info12 = $this->model_user_user->getUser($usercid['valueId']);
							$username1 .= $user_info12['username'].' | ';
					
							$usid[] = $usercid['valueId'];
						}
						$this->request->post['usid'] = $usid;
						
						$this->request->post['notes_description'] = $username1 .' '. $notes_description;
					}
					
					//var_dump($this->request->post['notes_description']);
					
					$notes_id = $this->model_notes_notes->addnotes ( $this->request->post, $facilities_id );
					$notesids[] = $notes_id;
					$notesids1 = implode(",",$notesids);
					$url2 = '&notes_ids=' . $notesids1;
				}
			}else
			
			if($this->request->post['facilityids'] != null && $this->request->post['facilityids'] != ""){
			
				$sssssdds = explode(",",$this->request->post['facilityids']);
				
				$abdc = array_unique($sssssdds);
				
				$this->request->post['notes_description'] = $notes_description;
				foreach($abdc as $sssssd){
					
					$notes_id = $this->model_notes_notes->addnotes ( $this->request->post, $sssssd );
					$notesids[] = $notes_id;

				}
				
				$notesids1 = implode(",",$notesids);
				$url2 = '&notes_ids=' . $notesids1;
				
			}else{
				$notes_id = $this->model_notes_notes->addnotes ( $this->request->post, $this->customer->getId () );
				$url2 = '&notes_id=' . $notes_id;
			}
			//die;
			/*
			 * if($this->config->get('config_face_recognition') == '1'){
			 * $this->load->model('notes/facerekognition');
			 * $this->model_notes_facerekognition->getfacerekognition($this->request->post,
			 * $notes_id);
			 * }
			*/
			
			if($this->request->post['facilityids'] != null && $this->request->post['facilityids'] != ""){
				$url2 .= '&facilityids=' . $this->request->post ['facilityids'];
			}
			
			if($this->request->post['locationids'] != null && $this->request->post['locationids'] != ""){
				$url2 .= '&locationids=' . $this->request->post ['locationids'];
			}
			
			if($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != ""){
				$url2 .= '&tagsids=' . $this->request->post ['tagsids'];
			}
			
			if($this->request->get['tags_ids'] != null && $this->request->get['tags_ids'] != ""){
				$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
				$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&savenotes=1';
				$action2 = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$action2 = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert2', '' . $url2, 'SSL' ) );
			}
			
			$json ['action'] = $action2;
			$json ['success'] = '1';
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function setTimeIntervalnotes() {
		// var_dump($this->request->post);
		$this->load->model ( 'notes/notes' );
		$json = array ();
		
		unset($this->session->data['username_confirm']);
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$timezone_name = $this->customer->isTimezone ();
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$noteTime = date ( 'H:i:s' );
			
			$date = str_replace ( '-', '/', $this->request->get ['searchdate'] );
			$res = explode ( "/", $date );
			$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
			$changedDate2 = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$this->data ['note_date'] = $changedDate . ' ' . $noteTime;
			$searchdate = $changedDate2;
		} else {
			$searchdate = date ( 'Y-m-d', strtotime ( 'now' ) );
		}
		
		/*
		 * $nkey = $this->session->data['session_cache_key'];
		 * //$notes_data = $this->cache->get('notes'.$nkey);
		 *
		 * //var_dump($notes_data);
		 *
		 * if ($notes_data) {
		 * $notes2 = array();
		 * foreach($notes_data as $note){
		 * //if($this->request->get['notes_id'] != $note['notes_id']){
		 * $notes2[] = array(
		 * 'notes_id' => $note['notes_id']
		 * );
		 * //}
		 * }
		 *
		 * }else{
		 */
		$endTime = date ( 'H:i:s', strtotime ( "-2 minutes", strtotime ( 'now' ) ) );
		$startTime = date ( 'H:i:s', strtotime ( 'now' ) );
		if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
			$notes_info = $this->model_notes_notes->getnotes ( $this->request->get ['last_notesID'] );
		} /*
		   * elseif($this->request->get['notes_id'] != null &&
		   * $this->request->get['notes_id'] != ""){
		   * $notes_info =
		   * $this->model_notes_notes->getnotes($this->request->get['notes_id']);
		   * }
		   */
		
		// var_dump($notes_info);
		
		if ($notes_info != null && $notes_info != "") {
			$notetime = date ( 'H:i:s', strtotime ( "+0 minutes", strtotime ( $notes_info ['update_date'] ) ) );
		} else {
			$notetime = date ( 'H:i:s', strtotime ( "-2 minutes", strtotime ( 'now' ) ) );
		}
		
		$timeinterval = array ();
		
		if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
			$search_facilities_id = $this->session->data ['search_facilities_id'];
		}
		
		if ($this->customer->getId () != null && $this->customer->getId () != "") {
			$facilities_id = $this->customer->getId ();
			
			$timeinterval = array (
					'searchdate' => $searchdate,
					// 'note_date_from' => $searchdate,
					// 'note_date_to' => $searchdate,
					// 'search_time_start' => $endTime,
					// 'search_time_to' => $startTime,
					// 'notes_id' => $this->request->get['notes_id']
					'sync_data' => '2',
					'facilities_timezone' => $this->customer->isTimezone (),
					'search_facilities_id' => $search_facilities_id,
					'notetime' => $notetime,
					'facilities_id' => $facilities_id,
					'customer_key' => $this->session->data['webcustomer_key'],
					'start' => 0,
					'limit' => 500 
			);
			// var_dump($timeinterval);
			// var_dump($timeinterval);
			
			$notes = $this->model_notes_notes->getnotess ( $timeinterval );
			$notes2 = array ();
			
			foreach ( $notes as $note ) {
				// if($this->request->get['notes_id'] != $note['notes_id']){
				$notes2 [] = array (
						'notes_id' => $note ['notes_id'] 
				);
				// }
			}
		}
		/* } */
		
		// $this->cache->delete('notes');
		
		$json ['success'] = '1';
		$json ['notes'] = $notes2;
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function ajaxSavedata() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$notes_id = $this->request->get ['notes_id'];
		$this->load->model ( 'notes/notes' );
		$this->model_notes_notes->updatenotes ( $this->request->post, $this->customer->getId (), $this->request->get ['notes_id'] );
		$this->data ['note'] = $this->model_notes_notes->getnotes ( $this->request->get ['notes_id'] );
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/ajaxnotes.php';
		$this->response->setOutput ( $this->render () );
	}
	public function getNoteData() {
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'notes/image' );
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'notes/notescomment' );
		
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
		$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
		$this->data ['config_rules_status'] = $this->customer->isRule ();
		$this->data ['config_share_notes'] = $this->customer->isNotesShare ();
		$this->data ['config_multiple_activenote'] = $this->customer->isMactivenote ();
		
		$this->data ['custom_form_form_url'] = $this->url->link ( 'form/form', '' . $url, 'SSL' );
		$this->data ['form_url'] = $this->url->link ( 'notes/noteform/forminsert', '' . $url, 'SSL' );
		$this->data ['check_list_form_url'] = $this->url->link ( 'notes/createtask/noteschecklistform', '' . $url, 'SSL' );
		
		$this->data ['customIntake_url'] = $this->url->link ( 'notes/tags/updateclient', '' . $url2, 'SSL' );
		$this->data ['censusdetail_url'] = $this->url->link ( 'resident/dailycensus/censusdetail', '' . $url2, 'SSL' );
		$this->data ['formpop_url'] = $this->url->link ( 'notes/notes/allforms', '' . $url, 'SSL' );
		
		$this->data ['medication_url'] = $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' );
		
		                $this->data ['update_medication_url'] = $this->url->link ( 'notes/addInventory/verifyInventory&medication_url=1', '' . $url2, 'SSL' );

		$this->data ['assignteam_url'] = $this->url->link ( 'resident/assignteam', '' . $url2, 'SSL' );
		
		$this->data ['bedcheck_url'] = $this->url->link ( 'notes/printbedcheck&is_bedchk=1', '' . $url2, 'SSL' );
		
		$this->data ['bedcheckurl'] = $this->url->link ( 'resident/resident/movementreport', '' . $url2, 'SSL' );
		
		$this->data ['approval_url'] = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
		
		$this->data ['routemap_url'] = $this->url->link ( 'notes/routemap', '' . $url2, 'SSL' );
		
		$this->data ['discharge_href'] = $this->url->link ( 'notes/case', '' . $url2, 'SSL' );
		
		$this->data ['inventory_check_in_url'] = $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' );
		
		$this->data ['inventory_check_out_url'] = $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url2, 'SSL' );
		
		
		$this->load->model('facilities/facilities');
		$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
			$url .= '&update_notetime=1';
			$this->data['update_note_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url, 'SSL'));
		} else {
			$this->data['update_note_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updatenotetime', '' . $url, 'SSL'));
			
		}
		
		$this->data['notetimeurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/allnotetime', '' . $url, 'SSL'));
		
		$result = $this->model_notes_notes->getnotes ( $this->request->get ['notes_id'] );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		if ($result) {
			
			$facilitynames = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
			$facilityname = $facilitynames ['facility'];
			
			if ($result ['highlighter_id'] > 0) {
				$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
			} else {
				$highlighterData = array ();
			}
			
			if ($result ['is_reminder'] == '1') {
				$reminder_info = $this->model_notes_notes->getReminder ( $result ['notes_id'] );
				$reminder_time = $reminder_info ['reminder_time'];
				$reminder_title = $reminder_info ['reminder_title'];
			} else {
				$reminder_time = "";
				$reminder_title = "";
			}
			
			$remdata = "";
			if ($reminder_info != null && $reminder_info != "") {
				$remdata = "1";
			} else {
				$remdata = "2";
			}
			
			$images = array ();
			
			if ($result ['notes_file'] == '1') {
				$allimages = $this->model_notes_notes->getImages ( $result ['notes_id'] );
				
				foreach ( $allimages as $image ) {
					
					$extension = $image ['notes_media_extention'];
					if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
					} else if ($extension == 'doc' || $extension == 'docx') {
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
					} else if ($extension == 'ppt' || $extension == 'pptx') {
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
					} else if ($extension == 'xls' || $extension == 'xlsx') {
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
					} else if ($extension == 'pdf') {
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
					} else {
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
					}
					
					$images [] = array (
							'keyImageSrc' => $keyImageSrc, // '<img
							                               // src="sites/view/digitalnotebook/image/attachment.png"
							                               // width="35px"
							                               // height="35px"
							                               // alt=""
							                               // style="margin-left:
							                               // 4px;" />',
							'media_user_id' => $image ['media_user_id'],
							'notes_type' => $image ['notes_type'],
							'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
							'media_signature' => $image ['media_signature'],
							'media_pin' => $image ['media_pin'],
							'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
					);
				}
			}
			
			/*
			 * if ($result['keyword_file'] != null && $result['keyword_file'] !=
			 * "") {
			 * $keyImageSrc1 = '<img src="'.$result['keyword_file_url'].'"
			 * wisth="35px" height="35px">';
			 *
			 * }else{
			 * $keyImageSrc1 = "";
			 * }
			 */
			
			/*
			 * if($result['notes_file'] != null && $result['notes_file'] != ""){
			 * $keyImageSrc = '<img
			 * src="sites/view/digitalnotebook/image/attachment.png"
			 * width="35px" height="35px" alt="" style="margin-left: 4px;" />';
			 *
			 * //$fileOpen = $this->url->link('notes/notes/openFile', '' .
			 * '&openfile='.$result['notes_file'] . $url, 'SSL');
			 * $fileOpen = HTTP_SERVER .'image/files/'. $result['notes_file'];
			 *
			 * }else{
			 * $keyImageSrc = '';
			 * $fileOpen = "";
			 *
			 * }
			 */
			
			if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
				$userPin = $result ['notes_pin'];
			} else {
				$userPin = '';
			}
			
			if ($result ['task_time'] != null && $result ['task_time'] != "00:00:00") {
				$task_time = date ( 'h:i A', strtotime ( $result ['task_time'] ) );
			} else {
				$task_time = "";
			}
			
			if ($config_tag_status == '1') {
				
				if ($result ['emp_tag_id'] == '1') {
					$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
				} else {
					$alltag = array ();
				}
				
				if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
					$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
					$privacy = $tagdata ['privacy'];
					
					if ($tagdata ['privacy'] == '2') {
						if ($this->session->data ['unloack_success'] != '1') {
							$emp_tag_id = $alltag ['emp_tag_id'] . ':' . $tagdata ['emp_first_name'];
						} else {
							$emp_tag_id = '';
						}
					} else {
						$emp_tag_id = '';
					}
				} else {
					$emp_tag_id = '';
					$privacy = '';
				}
			}
			
			// var_dump($result['keyword_file']);
			
			$noteskeywords = array ();
			
			if ($result ['keyword_file'] == '1') {
				$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
			} else {
				$allkeywords = array ();
			}
			
			if ($privacy == '2') {
				if ($this->session->data ['unloack_success'] == '1') {
					// $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id
					// . $result['notes_description'];
					
					if ($allkeywords) {
						$keyImageSrc12 = array ();
						$keyname = array ();
						$keyImageSrc11 = "";
						foreach ( $allkeywords as $keyword ) {
							$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
							// $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
							// $keyword['keyword_name'];
							// $keyname[] = $keyword['keyword_name'];
							// $keyname = array_unique($keyname);
							$noteskeywords [] = array (
									'keyword_file_url' => $keyword ['keyword_file_url'] 
							);
						}
						
						// $keyword_description = str_replace($keyname,
						// $keyImageSrc12, $result['notes_description']);
						// $keyword_description =
						// $keyImageSrc11.'&nbsp;'.$result['notes_description'];
						$keyword_description = $result ['notes_description'];
						
						$notes_description = $emp_tag_id . $keyword_description;
					} else {
						$notes_description = $emp_tag_id . $result ['notes_description'];
					}
				} else {
					$notes_description = $emp_tag_id;
				}
			} else {
				// $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id .
				// $result['notes_description'];
				
				if ($allkeywords) {
					$keyImageSrc12 = array ();
					$keyname = array ();
					$keyImageSrc11 = "";
					foreach ( $allkeywords as $keyword ) {
						
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
						// $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
						// $keyword['keyword_name'];
						// $keyname[] = $keyword['keyword_name'];
						// $keyname = array_unique($keyname);
						
						$noteskeywords [] = array (
								'keyword_file_url' => $keyword ['keyword_file_url'] 
						);
					}
					
					// $keyword_description = str_replace($keyname,
					// $keyImageSrc12, $result['notes_description']);
					// $keyword_description =
					// $keyImageSrc11.'&nbsp;'.$result['notes_description'];
					$keyword_description = $result ['notes_description'];
					
					$notes_description = $emp_tag_id . $keyword_description;
				} else {
					$notes_description = $emp_tag_id . $result ['notes_description'];
				}
			}
			
			/*
			 * if($result['notes_id'] != null && $result['notes_id'] != ""){
			 * $notesID = (string) $result['notes_id'];
			 *
			 * $response = $dynamodb->scan([
			 * 'TableName' => 'incidentform',
			 * 'ProjectionExpression' => 'incidentform_id, notes_id, user_id,
			 * signature, notes_pin, form_date_added ',
			 * 'ExpressionAttributeValues' => [
			 * ':val1' => ['N' => $notesID]] ,
			 * 'FilterExpression' => 'notes_id = :val1',
			 * ]);
			 *
			 *
			 * //$response = $dynamodb->scan($params);
			 *
			 * //var_dump($response['Items']);
			 * //echo '<hr> ';
			 *
			 * $forms = array();
			 * foreach($response['Items'] as $item){
			 * $form_date_added1 =
			 * str_replace("&nbsp;","",$item['form_date_added']['S']);
			 * if($form_date_added1 != null && $form_date_added1 != ""){
			 * $form_date_added =
			 * date($this->language->get('date_format_short_2'),
			 * strtotime($item['form_date_added']['S']));
			 * }else{
			 * $form_date_added = "";
			 * }
			 * $forms[] = array(
			 * 'incidentform_id' => $item['incidentform_id']['N'],
			 * 'notes_id' => $item['notes_id']['N'],
			 * 'user_id' => str_replace("&nbsp;","",$item['user_id']['S']),
			 * 'signature' => str_replace("&nbsp;","",$item['signature']['S']),
			 * 'notes_pin' => str_replace("&nbsp;","",$item['notes_pin']['S']),
			 * 'form_date_added' => $form_date_added,
			 *
			 * );
			 * }
			 * }else{
			 * $forms = array();
			 * }
			 */
			
			$forms = array ();
			if ($result ['is_forms'] == '1') {
				if ($facilityinfo ['config_noteform_status'] == '1') {
					$allforms = $this->model_notes_notes->getforms ( $result ['notes_id'] );
					
					foreach ( $allforms as $allform ) {
						
						$forms [] = array (
								'form_type_id' => $allform ['form_type_id'],
								'forms_id' => $allform ['forms_id'],
								'design_forms' => $allform ['design_forms'],
								'custom_form_type' => $allform ['custom_form_type'],
								'notes_id' => $allform ['notes_id'],
								'form_type' => $allform ['form_type'],
								'notes_type' => $allform ['notes_type'],
								'user_id' => $allform ['user_id'],
								'signature' => $allform ['signature'],
								'notes_pin' => $allform ['notes_pin'],
								'image_url' => $allform ['image_url'],
								'image_name' => $allform ['image_name'],
								'incident_number' => $allform ['incident_number'],
								'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ) 
						);
					}
				}
			}
			
			$notestasks = array ();
			$grandtotal = 0;
			$ograndtotal = 0;
			if ($result ['task_type'] == '1') {
				$alltasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '1' );
				foreach ( $alltasks as $alltask ) {
					$grandtotal = $grandtotal + $alltask ['capacity'];
					$tags_ids_names = '';
					if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
						$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
						
						foreach ( $tags_ids1 as $tag1 ) {
							$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
							
							if ($tags_info1 ['emp_first_name']) {
								$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
							} else {
								$emp_tag_id = $tags_info1 ['emp_tag_id'];
							}
							
							if ($tags_info1) {
								$tags_ids_names .= $emp_tag_id . ', ';
							}
						}
					}
					$out_tags_ids_names = "";
					
					$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
					
					if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
						$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
						$i = 0;
						$ooout = '1';
						foreach ( $tags_ids1 as $tag1 ) {
							
							$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
							
							if ($tags_info12 ['emp_first_name']) {
								$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
							} else {
								$emp_tag_id = $tags_info12 ['emp_tag_id'];
							}
							
							if ($tags_info12) {
								$out_tags_ids_names .= $emp_tag_id . ', ';
							}
							$i ++;
						}
						
						// $ograndtotal = $i;
					} else {
						$ooout = '2';
					}
					
					if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
						$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
					} else {
						$media_url = "";
					}
					
					if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
						$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
					} else {
						$medication_attach_url = "";
					}
					
					$notestasks [] = array (
							'notes_by_task_id' => $alltask ['notes_by_task_id'],
							'locations_id' => $alltask ['locations_id'],
							'task_type' => $alltask ['task_type'],
							'task_content' => $alltask ['task_content'],
							'user_id' => $alltask ['user_id'],
							'signature' => $alltask ['signature'],
							'notes_pin' => $alltask ['notes_pin'],
							'task_time' => $alltask ['task_time'],
							// 'media_url' => $alltask['media_url'],
							'media_url' => $media_url,
							'capacity' => $alltask ['capacity'],
							'location_name' => $alltask ['location_name'],
							'location_type' => $alltask ['location_type'],
							'notes_task_type' => $alltask ['notes_task_type'],
							'task_comments' => $alltask ['task_comments'],
							'role_call' => $alltask ['role_call'],
							'medication_attach_url' => $medication_attach_urlss,
							'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
							'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
							'tags_ids_names' => $tags_ids_names,
							'out_tags_ids_names' => $out_tags_ids_names 
					);
				}
			}
			
			$notesmedicationtasks = array ();
			if ($result ['task_type'] == '2') {
				$alltmasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '2' );
				
				foreach ( $alltmasks as $alltmask ) {
					
					if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
						$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
					}
					
					if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
						$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
					} else {
						$media_url = "";
					}
					
					if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
						$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
					} else {
						$medication_attach_url = "";
					}
					
					$notesmedicationtasks [] = array (
							'notes_by_task_id' => $alltmask ['notes_by_task_id'],
							'locations_id' => $alltmask ['locations_id'],
							'task_type' => $alltmask ['task_type'],
							'task_content' => $alltmask ['task_content'],
							'user_id' => $alltmask ['user_id'],
							'signature' => $alltmask ['signature'],
							'notes_pin' => $alltmask ['notes_pin'],
							'task_time' => $taskTime,
							// 'media_url' => $alltmask['media_url'],
							'media_url' => $media_url,
							'capacity' => $alltmask ['capacity'],
							'location_name' => $alltmask ['location_name'],
							'location_type' => $alltmask ['location_type'],
							'notes_task_type' => $alltmask ['notes_task_type'],
							'tags_id' => $alltmask ['tags_id'],
							'drug_name' => $alltmask ['drug_name'],
							'dose' => $alltmask ['dose'],
							'drug_type' => $alltmask ['drug_type'],
							'quantity' => $alltmask ['quantity'],
							'frequency' => $alltmask ['frequency'],
							'instructions' => $alltmask ['instructions'],
							'count' => $alltmask ['count'],
							'refuse' => $alltmask ['refuse'],
							'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
							'task_comments' => $alltmask ['task_comments'],
							'role_call' => $alltask ['role_call'],
							'medication_file_upload' => $alltmask ['medication_file_upload'],
							'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
					);
				}
			}
			
			if ($result ['task_type'] == '6') {
				$approvaltask = $this->model_notes_notes->getapprovaltask ( $result ['task_id'] );
			} else {
				$approvaltask = array ();
			}
			
			if ($result ['task_type'] == '3') {
				$geolocation_info = $this->model_notes_notes->getGeolocation ( $result ['notes_id'] );
			} else {
				$geolocation_info = array ();
			}
			
			if ($result ['original_task_time'] != null && $result ['original_task_time'] != "00:00:00") {
				$original_task_time = date ( 'h:i A', strtotime ( $result ['original_task_time'] ) );
			} else {
				$original_task_time = "";
			}
			
			if ($result ['user_file'] != null && $result ['user_file'] != "") {
				$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result ['notes_id'], 'SSL' );
			} else {
				$user_file = "";
			}
			
			$notescomments = array ();
			if ($result ['is_comment'] == '1') {
				$allcomments = $this->model_notes_notescomment->getcomments ( $result ['notes_id'] );
			} else {
				$allcomments = array ();
			}
			
			if ($allcomments) {
				foreach ( $allcomments as $allcomment ) {
					$commentskeywords = array ();
					if ($allcomment ['keyword_file'] == '1') {
						$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
					} else {
						$aallkeywords = array ();
					}
					
					if ($aallkeywords) {
						$keyImageSrc12 = array ();
						$keyname = array ();
						foreach ( $aallkeywords as $callkeyword ) {
							$commentskeywords [] = array (
									'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
									'notes_id' => $callkeyword ['notes_id'],
									'comment_id' => $callkeyword ['comment_id'],
									'keyword_id' => $callkeyword ['keyword_id'],
									'keyword_name' => $callkeyword ['keyword_name'],
									'keyword_file_url' => $callkeyword ['keyword_file_url'],
									'keyword_image' => $callkeyword ['keyword_image'],
									'img_icon' => $callkeyword ['keyword_file_url'] 
							);
						}
					}
					$notescomments [] = array (
							'comment_id' => $allcomment ['comment_id'],
							'notes_id' => $allcomment ['notes_id'],
							'facilities_id' => $allcomment ['facilities_id'],
							'comment' => $allcomment ['comment'],
							'user_id' => $allcomment ['user_id'],
							'notes_pin' => $allcomment ['notes_pin'],
							'signature' => $allcomment ['signature'],
							'user_file' => $allcomment ['user_file'],
							'is_user_face' => $allcomment ['is_user_face'],
							'date_added' => $allcomment ['date_added'],
							'comment_date' => $allcomment ['comment_date'],
							'notes_type' => $allcomment ['notes_type'],
							'commentskeywords' => $commentskeywords 
					);
				}
			}
			
			if ($result ['is_comment'] == '2') {
				$printtranscript = $this->url->link ( 'notes/transcript/printtranscript', '' . '&notes_id=' . $result ['notes_id'] . $url, 'SSL' );
			} else {
				$printtranscript = '';
			}
			
			$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'], $this->customer->getId () );
			
			$uptimes = array();
			$uptimes = $this->model_notes_notes->getupdatetime ($result ['notes_id']);
			
			$this->data ['note'] = array (
					'notes_id' => $result ['notes_id'],
					'shift_color_value'=>$shift_time_color['shift_color_value'],
					'is_comment' => $result ['is_comment'],
					'in_total' => $result ['in_total'],
					'out_total' => $result ['out_total'],
					'manual_total' => $result ['manual_total'],
					'notescomments' => $notescomments,
					'uptimes' => $uptimes,
					'printtranscript' => $printtranscript,
					'ooout' => $ooout,
					'is_user_face' => $result ['is_user_face'],
					'is_approval_required_forms_id' => $result ['is_approval_required_forms_id'],
					// 'user_file' => $result['user_file'],
					'user_file' => $user_file,
					'geolocation_info' => $geolocation_info,
					'original_task_time' => $original_task_time,
					'approvaltask' => $approvaltask,
					'notes_file' => $result ['notes_file'],
					'keyword_file' => $result ['keyword_file'],
					'emp_tag_id' => $result ['emp_tag_id'],
					'is_forms' => $result ['is_forms'],
					'is_reminder' => $result ['is_reminder'],
					'task_type' => $result ['task_type'],
					'visitor_log' => $result ['visitor_log'],
					'is_tag' => $result ['is_tag'],
					'is_archive' => $result ['is_archive'],
					'form_type' => $result ['form_type'],
					'generate_report' => $result ['generate_report'],
					'is_census' => $result ['is_census'],
					'is_android' => $result ['is_android'],
					'alltag' => $alltag,
					'remdata' => $remdata,
					'noteskeywords' => $noteskeywords,
					'is_private' => $result ['is_private'],
					'share_notes' => $result ['share_notes'],
					'is_offline' => $result ['is_offline'],
					'review_notes' => $result ['review_notes'],
					'is_private_strike' => $result ['is_private_strike'],
					'checklist_status' => $result ['checklist_status'],
					'notes_type' => $result ['notes_type'],
					'strike_note_type' => $result ['strike_note_type'],
					'task_time' => $task_time,
					'tag_privacy' => $privacy,
					'incidentforms' => $forms,
					'notestasks' => $notestasks,
					'grandtotal' => $grandtotal,
					'ograndtotal' => $ograndtotal,
					'boytotals' => $boytotals,
					'girltotals' => $girltotals,
					'generaltotals' => $generaltotals,
					'residentstotals' => $residentstotals,
					'notesmedicationtasks' => $notesmedicationtasks,
					'task_type' => $result ['task_type'],
					'taskadded' => $result ['taskadded'],
					'assign_to' => $result ['assign_to'],
					'highlighter_value' => $highlighterData ['highlighter_value'],
					'notes_description' => str_replace ( "'", "&#039;", html_entity_decode ( '&nbsp;' . $notes_description, ENT_QUOTES ) ),
					// 'keyImageSrc' => $keyImageSrc,
					// 'fileOpen' => $fileOpen,
					'images' => $images,
					'notetime' => date ( 'h:i A', strtotime ( $result ['notetime'] ) ),
					'username' => $result ['user_id'],
					'notes_pin' => $userPin,
					'signature' => $result ['signature'],
					'text_color_cut' => $result ['text_color_cut'],
					'text_color' => $result ['text_color'],
					'note_date' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $result ['note_date'] ) ),
					'status' => ($result ['status'] ? $this->language->get ( 'text_enabled' ) : $this->language->get ( 'text_disabled' )),
					'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
					'strike_user_name' => $result ['strike_user_id'],
					'strike_pin' => $result ['strike_pin'],
					'strike_signature' => $result ['strike_signature'],
					'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $result ['strike_date_added'] ) ),
					'reminder_time' => $reminder_time,
					'reminder_title' => $reminder_title,
					'facilityname' => $facilityname,
					'href' => str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . '&reset=1&searchdate=' . date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ) . $url, 'SSL' ) ) 
			);
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/ajaxnotes.php';
		
		$this->response->setOutput ( $this->render () );
	}
	protected function getForm() {
		try {
			
			$this->load->model ( 'notes/image' );
			$this->load->model ( 'setting/highlighter' );
			$this->load->model ( 'user/user' );
			$this->load->model ( 'notes/tags' );
			
			$this->load->model ( 'notes/notescomment' );
			
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			unset ( $this->session->data ['media_user_id'] );
			unset ( $this->session->data ['media_signature'] );
			unset ( $this->session->data ['media_pin'] );
			unset ( $this->session->data ['emp_tag_id'] );
			unset ( $this->session->data ['tags_id'] );
			unset ( $this->session->data ['facility'] );
			unset ( $this->session->data ['show_hidden_info'] );
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			/*
			 * $this->load->model('licence/licence');
			 * //$resulta = $this->model_licence_licence->checkloginlicence();
			 * //var_dump($resulta);
			 * if($resulta == 0){
			 *
			 * $this->customer->logout();
			 * unset($this->session->data['time_zone_1']);
			 * //unset($this->session->data['token']);
			 *
			 * unset($this->session->data['note_date_search']);
			 * unset($this->session->data['note_date_from']);
			 *
			 * unset($this->session->data['note_date_to']);
			 *
			 * unset($this->session->data['search_time_start']);
			 * unset($this->session->data['search_time_to']);
			 *
			 * unset($this->session->data['keyword']);
			 * unset($this->session->data['sms_user_id']);
			 * unset($this->session->data['search_user_id']);
			 * unset($this->session->data['search_emp_tag_id']);
			 * unset($this->session->data['notesdatas']);
			 * unset($this->session->data['advance_search']);
			 * unset($this->session->data['update_reminder']);
			 * unset($this->session->data['pagenumber']);
			 * unset($this->session->data['pagenumber_all']);
			 * unset($this->session->data['activationkey']);
			 * unset($this->session->data['username']);
			 * unset($this->session->data['session_key']);
			 * unset($this->session->data['unloack_success']);
			 * unset($this->session->data['ssincedentform']);
			 * unset($this->session->data['ssbedcheckform']);
			 * unset($this->session->data['form_search']);
			 * unset($this->session->data['highlighter']);
			 * unset($this->session->data['activenote']);
			 * unset($this->session->data['isPrivate']);
			 * unset($this->session->data['review_user_id']);
			 *
			 * unset($this->session->data['formreturn_id']);
			 * unset($this->session->data['design_forms']);
			 * unset($this->session->data['formsids']);
			 * unset($this->session->data['session_notes_description']);
			 * unset($this->session->data['tasktype']);
			 * unset($this->session->data['webuser_id']);
			 *
			 * $this->redirect($this->url->link('common/login', '', 'SSL'));
			 * }else{
			 * $this->load->model('facilities/facilities');
			 * $this->load->model('licence/licence');
			 *
			 * $data = array();
			 * $data['activationkey'] = $this->session->data['activationkey'];
			 * $data['ip'] = $this->request->server['REMOTE_ADDR'];
			 *
			 * //$ipresults = $this->model_facilities_facilities->resetFacilityLogin($data);
			 *
			 * }
			 */
			
			// date_default_timezone_set($this->session->data['time_zone_1']);
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->post ['advance_search'] != '1') {
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
						// $url2 .= '&page=' . $pagenumber_all;
					}
				}
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
				
				$this->data ['tags_id_url'] = '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->data ['rediectUlr'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
			$this->data ['resetUrl'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert&searchall=1', '' . '&reset=1' . $url2, 'SSL' ) );
			$this->data ['resetUrl_private'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . '&reset=1' . $url2, 'SSL' ) );
			
			$this->data ['form_url'] = $this->url->link ( 'notes/noteform/forminsert', '' . $url2, 'SSL' );
			$this->data ['customIntake_url'] = $this->url->link ( 'notes/tags/addclient', '' . $url2, 'SSL' );
			
			$this->data ['record_url'] = $this->url->link ( 'notes/recordingnote/recordnote', '' . $url2, 'SSL' );
			
			$this->data ['sharenote_url'] = $this->url->link ( 'notes/sharenote/addnote', '' . $url2, 'SSL' );
			
			$this->data ['inventory_check_in_url'] = $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' );
			
			$this->data ['inventory_check_out_url'] = $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url2, 'SSL' );
			
			$this->data ['attachment_url'] = $this->url->link ( 'notes/notes/attachment', '' . $url, 'SSL' );
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
				$url .= '&update_notetime=1';
				$this->data['update_note_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url, 'SSL'));
			} else {
				$this->data['update_note_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updatenotetime', '' . $url, 'SSL'));
			}
			
			$this->data['notetimeurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/allnotetime', '' . $url, 'SSL'));
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				
				$this->data ['naotes_tags_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&updateTags=1', '' . $url2, 'SSL' ) );
				$this->data ['attachment_sign_url'] = $this->url->link ( 'common/authorization&attachmentSign=1', '' . $url2, 'SSL' );
			} else {
				$this->data ['naotes_tags_url'] = $this->url->link ( 'notes/notes/updateTags', '' . $url2, 'SSL' );
				$this->data ['attachment_sign_url'] = $this->url->link ( 'notes/notes/attachmentSign', '' . $url2, 'SSL' );
			}
			
			$this->data ['approval_url'] = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
			
			$this->data ['routemap_url'] = $this->url->link ( 'notes/routemap', '' . $url2, 'SSL' );
			$this->data ['discharge_href'] = $this->url->link ( 'notes/case', '' . $url2, 'SSL' );
			
			$this->data ['medication_url'] = $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' );
			$this->data ['update_medication_url'] = $this->url->link ( 'notes/addInventory/verifyInventory&medication_url=1', '' . $url2, 'SSL' );

			
			$this->data ['censusdetail_url'] = $this->url->link ( 'resident/dailycensus/censusdetail', '' . $url2, 'SSL' );
			$this->data ['updatetag_url'] = $this->url->link ( 'notes/tags/updateclient', '' . $url2, 'SSL' );
			$this->data ['bedcheck_url'] = $this->url->link ( 'notes/printbedcheck&is_bedchk=1', '' . $url2, 'SSL' );
			$this->data ['bedcheckurl'] = $this->url->link ( 'resident/resident/movementreport', '' . $url2, 'SSL' );
			
			$this->data ['assignteam_url'] = $this->url->link ( 'resident/assignteam', '' . $url2, 'SSL' );
			
			
			$this->data ['location_url'] = $this->url->link ( 'notes/notes/allocations', '' . $url, 'SSL' );
			$this->data ['facility_url'] = $this->url->link ( 'notes/notes/allfacilities', '' . $url, 'SSL' );
			$this->data ['alltags_url'] = $this->url->link ( 'notes/notes/alltags', '' . $url, 'SSL' );
			$this->data ['user_url'] = $this->url->link ( 'notes/notes/allusers', '' . $url, 'SSL' );
			
			$this->data ['heading_title'] = $this->language->get ( 'heading_title' );
			
			$this->data ['entry_facility'] = $this->language->get ( 'entry_facility' );
			$this->data ['entry_time'] = $this->language->get ( 'entry_time' );
			$this->data ['entry_notes_description'] = $this->language->get ( 'entry_notes_description' );
			$this->data ['entry_highliter'] = $this->language->get ( 'entry_highliter' );
			$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
			$this->data ['entry_upload_file'] = $this->language->get ( 'entry_upload_file' );
			$this->data ['entry_timezone'] = $this->language->get ( 'entry_timezone' );
			
			$this->data ['button_save'] = $this->language->get ( 'button_save' );
			$this->data ['button_cancel'] = $this->language->get ( 'button_cancel' );
			$this->data ['text_select'] = $this->language->get ( 'text_select' );
			
			$this->data ['review'] = $this->request->get ['review'];
			
			if ($this->request->get ['reset'] == '1') {
				unset ( $this->session->data ['note_date_search'] );
				unset ( $this->session->data ['note_date_from'] );
				unset ( $this->session->data ['note_date_to'] );
				
				unset ( $this->session->data ['search_time_start'] );
				unset ( $this->session->data ['search_time_to'] );
				
				unset ( $this->session->data ['keyword'] );
				unset ( $this->session->data ['sms_user_id'] );
				unset ( $this->session->data ['search_user_id'] );
				
				unset ( $this->session->data ['search_emp_tag_id'] );
				unset ( $this->session->data ['notesdatas'] );
				unset ( $this->session->data ['advance_search'] );
				unset ( $this->session->data ['update_reminder'] );
				unset ( $this->session->data ['keyword_file'] );
				unset ( $this->session->data ['notes_id'] );
				unset ( $this->session->data ['pagenumber'] );
				unset ( $this->session->data ['unloack_success'] );
				unset ( $this->session->data ['ssincedentform'] );
				unset ( $this->session->data ['ssbedcheckform'] );
				unset ( $this->session->data ['form_search'] );
				unset ( $this->session->data ['highlighter'] );
				unset ( $this->session->data ['activenote'] );
				unset ( $this->session->data ['review_user_id'] );
				
				unset ( $this->session->data ['formreturn_id'] );
				unset ( $this->session->data ['design_forms'] );
				unset ( $this->session->data ['formsids'] );
				unset ( $this->session->data ['session_notes_description'] );
				unset ( $this->session->data ['tasktype'] );
				unset ( $this->session->data ['group'] );
				unset ( $this->session->data ['search_facilities_id'] );
				
				$url = "";
				
				if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
					$url .= '&searchdate=' . $this->request->get ['searchdate'];
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
						$url .= '&page=' . $pagenumber_all;
					}
				}
				
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '' . $url, 'SSL' ) );
			}
			
			$this->data ['resetUrl'] = $this->url->link ( 'notes/notes/insert', '' . '&reset=1' . $url, 'SSL' );
			$this->data ['form_url'] = $this->url->link ( 'notes/noteform/forminsert', '' . $url, 'SSL' );
			
			$this->data ['record_url'] = $this->url->link ( 'notes/recordingnote/recordnote', '' . $url, 'SSL' );
			$this->data ['sharenote_url'] = $this->url->link ( 'notes/sharenote/addnote', '' . $url, 'SSL' );
			
			$this->data ['check_list_form_url'] = $this->url->link ( 'notes/createtask/noteschecklistform', '' . $url, 'SSL' );
			
			$this->data ['custom_form_form_url'] = $this->url->link ( 'form/form', '' . $url, 'SSL' );
			
			$this->data ['comment_url'] = $this->url->link ( 'notes/comment', '' . $url, 'SSL' );
			$this->data ['activepop_url'] = $this->url->link ( 'notes/notes/activenote', '' . $url, 'SSL' );
			$this->data ['formpop_url'] = $this->url->link ( 'notes/notes/allforms', '' . $url, 'SSL' );
			
			
			
			
			$this->data ['notess'] = array ();
			
			 if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
				$this->data ['tags_ids'] = $this->request->get ['tags_ids'];          
               
			}
			
			if (isset ( $this->session->data ['update_reminder'] )) {
				$this->data ['update_reminder'] = $this->session->data ['update_reminder'];
			}
			
			if (isset ( $this->request->post ['advance_search'] )) {
				$this->session->data ['advance_search'] = $this->request->post ['advance_search'];
				
				$this->session->data ['group'] = '1';
			}
			
			if (isset ( $this->request->post ['note_date_search'] )) {
				$this->data ['note_date_search'] = $this->request->post ['note_date_search'];
				$this->session->data ['note_date_search'] = $this->request->post ['note_date_search'];
			} else {
				$this->data ['note_date_search'] = '';
			}
			
			if (isset ( $this->request->post ['highlighter'] )) {
				$this->data ['highlighter'] = $this->request->post ['highlighter'];
				$this->session->data ['highlighter'] = $this->request->post ['highlighter'];
			} else {
				$this->data ['highlighter'] = '';
			}
			
			if (isset ( $this->request->post ['activenote'] )) {
				$this->data ['activenote'] = $this->request->post ['activenote'];
				$this->session->data ['activenote'] = $this->request->post ['activenote'];
			} else {
				$this->data ['activenote'] = '';
			}
			
			if (isset ( $this->request->post ['tasktype'] )) {
				$this->data ['tasktype'] = $this->request->post ['tasktype'];
				$this->session->data ['tasktype'] = $this->request->post ['tasktype'];
			} else {
				$this->data ['tasktype'] = '';
			}
			
			if (isset ( $this->request->post ['note_date_from'] )) {
				$this->data ['note_date_from'] = $this->request->post ['note_date_from'];
				$this->session->data ['note_date_from'] = $this->request->post ['note_date_from'];
			} else {
				$this->data ['note_date_from'] = '';
			}
			
			if (isset ( $this->request->post ['note_date_to'] )) {
				$this->data ['note_date_to'] = $this->request->post ['note_date_to'];
				$this->session->data ['note_date_to'] = $this->request->post ['note_date_to'];
			} else {
				$this->data ['note_date_to'] = '';
			}
			
			if (isset ( $this->request->post ['search_time_start'] )) {
				$this->data ['search_time_start'] = $this->request->post ['search_time_start'];
				$this->session->data ['search_time_start'] = $this->request->post ['search_time_start'];
			} else {
				$this->data ['search_time_start'] = '';
			}
			
			if (isset ( $this->request->post ['search_time_to'] )) {
				$this->data ['search_time_to'] = $this->request->post ['search_time_to'];
				$this->session->data ['search_time_to'] = $this->request->post ['search_time_to'];
			} else {
				$this->data ['search_time_to'] = '';
			}
			
			if (isset ( $this->request->post ['keyword'] )) {
				$this->data ['keyword'] = $this->request->post ['keyword'];
				$this->session->data ['keyword'] = $this->request->post ['keyword'];
			} else {
				$this->data ['keyword'] = '';
			}
			
			if (isset ( $this->request->post ['form_search'] )) {
				$this->data ['form_search'] = $this->request->post ['form_search'];
				$this->session->data ['form_search'] = $this->request->post ['form_search'];
			} else {
				$this->data ['form_search'] = '';
			}
			
			if (isset ( $this->request->post ['user_id'] )) {
				$this->data ['user_id'] = $this->request->post ['user_id'];
				$this->session->data ['search_user_id'] = $this->request->post ['user_id'];
			} else {
				$this->data ['user_id'] = '';
			}
			
			if (isset ( $this->request->post ['search_emp_tag_id'] )) {
				$this->data ['search_emp_tag_id'] = $this->request->post ['search_emp_tag_id'];
				$this->session->data ['search_emp_tag_id'] = $this->request->post ['search_emp_tag_id'];
			} else {
				$this->data ['search_emp_tag_id'] = '';
			}
			
			if ($this->session->data ['note_date_from'] != null && $this->session->data ['note_date_from'] != "") {
				
				$date = str_replace ( '-', '/', $this->session->data ['note_date_from'] );
				$res = explode ( "/", $date );
				$note_date_from = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				// $note_date_from = date('Y-m-d',
				// strtotime($this->session->data['note_date_from']));
			}
			if ($this->session->data ['note_date_to'] != null && $this->session->data ['note_date_to'] != "") {
				$date = str_replace ( '-', '/', $this->session->data ['note_date_to'] );
				$res = explode ( "/", $date );
				$note_date_to = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				// $note_date_to = date('Y-m-d',
				// strtotime($this->session->data['note_date_to']));
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
					date_default_timezone_set ( $timezone_name );
				} else {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
				}
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
			}
			
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$noteTime = date ( 'H:i:s' );
				
				$date = str_replace ( '-', '/', $this->request->get ['searchdate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
				
				$this->data ['note_datenew'] = $changedDate . ' ' . $noteTime;
				$searchdate = $this->request->get ['searchdate'];
				$this->data ['searchdate'] = $this->request->get ['searchdate'];
				
				if (($searchdate) >= (date ( 'm-d-Y' ))) {
					$this->data ['back_date_check'] = "1";
				} else {
					$this->data ['back_date_check'] = "2";
				}
			} else {
				$this->data ['note_datenew'] = date ( 'Y-m-d H:i:s' );
				$this->data ['searchdate'] = date ( 'm-d-Y' );
			}
			
			if ($this->request->get ['fromdate'] != null && $this->request->get ['fromdate'] != "") {
				
				$noteTime = date ( 'H:i:s' );
				
				$date = str_replace ( '-', '/', $this->request->get ['fromdate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
				
				$note_date_from = date ( 'Y-m-d', strtotime ( $changedDate ) );
				
				$note_date_to = date ( 'Y-m-d' );
				$this->session->data ['advance_search'] = '1';
				
				if ($this->request->get ['highlighter'] != null && $this->request->get ['highlighter'] != "") {
					$this->session->data ['highlighter'] = $this->request->get ['highlighter'];
				}
				
				if ($this->request->get ['activenote'] != null && $this->request->get ['activenote'] != "") {
					$this->session->data ['activenote'] = $this->request->get ['activenote'];
				}
				
				$this->session->data ['group'] = '1';
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$page = $this->request->get ['page'];
			} else {
				$page = 1;
			}
			
			$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
			if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
				$config_admin_limit = $config_admin_limit1;
			} else {
				$config_admin_limit = "50";
			}
			
			if ($this->request->get ['route'] == "resident/cases/dashboard2") {
				$this->data ['case_detail'] = "1";
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
					$tags_id = $this->request->get ['tags_id'];
				}
				
				$search_emp_tag_id = $tags_id;
				$case_detail = '1';
			} else {
				$case_detail = '2';
				$search_emp_tag_id = $this->session->data ['search_emp_tag_id'];
				
				$this->data ['case_detail'] = "2";
			}
			
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			$ddss = array ();
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				$this->data ['is_master_facility'] = '1';
				$ddss [] = $facilityinfo ['notes_facilities_ids'];
				
				$ddss [] = $this->customer->getId ();
				$sssssdd = implode ( ",", $ddss );
			} else {
				$this->data ['is_master_facility'] = '2';
			}
			
			$data = array (
					'sort' => $sort,
					'case_detail' => $case_detail,
					'order' => $order,
					'searchdate' => $searchdate,
					'searchdate_app' => '1',
					'facilities_id' => $this->customer->getId (),
					'note_date_from' => $note_date_from,
					'note_date_to' => $note_date_to,
					'group' => $this->session->data ['group'],
					'search_facilities_id' => $this->session->data ['search_facilities_id'],
					
					'search_time_start' => $this->session->data ['search_time_start'],
					'search_time_to' => $this->session->data ['search_time_to'],
					
					'keyword' => $this->session->data ['keyword'],
					'form_search' => $this->session->data ['form_search'],
					'user_id' => $this->session->data ['search_user_id'],
					'highlighter' => $this->session->data ['highlighter'],
					'activenote' => $this->session->data ['activenote'],
					'emp_tag_id' => $search_emp_tag_id,
					'advance_searchapp' => $this->session->data ['advance_search'],
					'tasktype' => $this->session->data ['tasktype'],
					'customer_key' => $this->session->data['webcustomer_key'],
					'notes_facilities_ids' => $sssssdd,
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
			);
			
			// var_dump($data);
			// if($this->session->data['advance_search'] == '1'){
			$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
			// }
			
			// var_dump($notes_total);
			
			$this->load->model ( 'notes/notes' );
			$last_notesID = $this->model_notes_notes->getLastNotesID ( $this->customer->getId (), $searchdate );
			
			$this->data ['last_notesID'] = $last_notesID ['notes_id'];
			
			if ($this->session->data ['notes_id'] == null && $this->session->data ['notes_id'] == "") {
				$results = $this->model_notes_notes->getnotess ( $data );
				
				$this->load->model ( 'notes/tags' );
				$this->load->model ( 'setting/tags' );
				
				$config_tag_status = $this->customer->isTag ();
				$this->data ['config_tag_status'] = $this->customer->isTag ();
				
				$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
				$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
				$this->data ['config_rules_status'] = $this->customer->isRule ();
				$this->data ['config_share_notes'] = $this->customer->isNotesShare ();
				$this->data ['config_multiple_activenote'] = $this->customer->isMactivenote ();
				
				$this->data ['unloack_success'] = $this->session->data ['unloack_success'];
				// require_once(DIR_APPLICATION . 'aws/getItem.php');
				
				// var_dump($facilityinfo);
				
				// $nkey = $this->session->data['session_cache_key'];
				// $this->cache->delete('notes'.$nkey);
				
				foreach ( $results as $result ) {
					
					$facilitynames = $this->model_facilities_facilities->getfacilities ( $result ['facilities_id'] );
					$facilityname = $facilitynames ['facility'];
					
					// $this->cache->delete('note'.$result['notes_id']);
					
					if ($result ['highlighter_id'] > 0) {
						$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
					} else {
						$highlighterData = array ();
					}
					
					if ($result ['is_reminder'] == '1') {
						$reminder_info = $this->model_notes_notes->getReminder ( $result ['notes_id'] );
						$reminder_time = $reminder_info ['reminder_time'];
						$reminder_title = $reminder_info ['reminder_title'];
					} else {
						$reminder_time = "";
						$reminder_title = "";
					}
					
					$remdata = "";
					if ($reminder_info != null && $reminder_info != "") {
						$remdata = "1";
					} else {
						$remdata = "2";
					}
					
					$images = array ();
					
					if ($result ['notes_file'] == '1') {
						$allimages = $this->model_notes_notes->getImages ( $result ['notes_id'] );
						
						foreach ( $allimages as $image ) {
							
							$extension = $image ['notes_media_extention'];
							if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
								$keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
							} else if ($extension == 'doc' || $extension == 'docx') {
								$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
							} else if ($extension == 'ppt' || $extension == 'pptx') {
								$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
							} else if ($extension == 'xls' || $extension == 'xlsx') {
								$keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
							} else if ($extension == 'pdf') {
								$keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
							} else {
								$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
							}
							
							$images [] = array (
									'keyImageSrc' => $keyImageSrc, // '<img
									                               // src="sites/view/digitalnotebook/image/attachment.png"
									                               // width="35px"
									                               // height="35px"
									                               // alt=""
									                               // style="margin-left:
									                               // 4px;" />',
									'media_user_id' => $image ['media_user_id'],
									'notes_type' => $image ['notes_type'],
									'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
									'media_signature' => $image ['media_signature'],
									'media_pin' => $image ['media_pin'],
									'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
							);
						}
					}
					
					/*
					 * if ($result['keyword_file'] != null &&
					 * $result['keyword_file'] != "") {
					 * $keyImageSrc1 = '<img
					 * src="'.$result['keyword_file_url'].'" wisth="35px"
					 * height="35px">';
					 *
					 * }else{
					 * $keyImageSrc1 = "";
					 * }
					 */
					
					/*
					 * if($result['notes_file'] != null && $result['notes_file']
					 * != ""){
					 * $keyImageSrc = '<img
					 * src="sites/view/digitalnotebook/image/attachment.png"
					 * width="35px" height="35px" alt="" style="margin-left:
					 * 4px;" />';
					 *
					 * //$fileOpen = $this->url->link('notes/notes/openFile', ''
					 * . '&openfile='.$result['notes_file'] . $url, 'SSL');
					 * $fileOpen = HTTP_SERVER .'image/files/'.
					 * $result['notes_file'];
					 *
					 * }else{
					 * $keyImageSrc = '';
					 * $fileOpen = "";
					 *
					 * }
					 */
					
					if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
						$userPin = $result ['notes_pin'];
					} else {
						$userPin = '';
					}
					
					if ($result ['task_time'] != null && $result ['task_time'] != "00:00:00") {
						$task_time = date ( 'h:i A', strtotime ( $result ['task_time'] ) );
					} else {
						$task_time = "";
					}
					
					if ($config_tag_status == '1') {
						
						if ($result ['emp_tag_id'] == '1') {
							$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
						} else {
							$alltag = array ();
						}
						
						if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
							$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
							$privacy = $tagdata ['privacy'];
							
							if ($tagdata ['privacy'] == '2') {
								if ($this->session->data ['unloack_success'] != '1') {
									$emp_tag_id = $alltag ['emp_tag_id'] . ':' . $tagdata ['emp_first_name'];
								} else {
									$emp_tag_id = '';
								}
							} else {
								$emp_tag_id = '';
							}
						} else {
							$emp_tag_id = '';
							$privacy = '';
						}
					}
					
					// var_dump($result['keyword_file']);
					
					$noteskeywords = array ();
					
					if ($result ['keyword_file'] == '1') {
						$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
					} else {
						$allkeywords = array ();
					}
					
					if ($privacy == '2') {
						if ($this->session->data ['unloack_success'] == '1') {
							// $notes_description = $keyImageSrc1 .'&nbsp;'.
							// $emp_tag_id . $result['notes_description'];
							
							if ($allkeywords) {
								$keyImageSrc12 = array ();
								$keyname = array ();
								$keyImageSrc11 = "";
								foreach ( $allkeywords as $keyword ) {
									$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
									// $keyImageSrc12[] = $keyImageSrc11
									// .'&nbsp;' . $keyword['keyword_name'];
									// $keyname[] = $keyword['keyword_name'];
									// $keyname = array_unique($keyname);
									$noteskeywords [] = array (
											'keyword_file_url' => $keyword ['keyword_file_url'] 
									);
								}
								
								// $keyword_description = str_replace($keyname,
								// $keyImageSrc12,
								// $result['notes_description']);
								// $keyword_description =
								// $keyImageSrc11.'&nbsp;'.$result['notes_description'];
								$keyword_description = $result ['notes_description'];
								
								$notes_description = $emp_tag_id . $keyword_description;
							} else {
								$notes_description = $emp_tag_id . $result ['notes_description'];
							}
						} else {
							$notes_description = $emp_tag_id;
						}
					} else {
						// $notes_description = $keyImageSrc1 .'&nbsp;'.
						// $emp_tag_id . $result['notes_description'];
						
						if ($allkeywords) {
							$keyImageSrc12 = array ();
							$keyname = array ();
							$keyImageSrc11 = "";
							foreach ( $allkeywords as $keyword ) {
								
								$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
								// $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
								// $keyword['keyword_name'];
								// $keyname[] = $keyword['keyword_name'];
								// $keyname = array_unique($keyname);
								
								$noteskeywords [] = array (
										'keyword_file_url' => $keyword ['keyword_file_url'] 
								);
							}
							
							// $keyword_description = str_replace($keyname,
							// $keyImageSrc12, $result['notes_description']);
							// $keyword_description =
							// $keyImageSrc11.'&nbsp;'.$result['notes_description'];
							$keyword_description = $result ['notes_description'];
							
							$notes_description = $emp_tag_id . $keyword_description;
						} else {
							$notes_description = $emp_tag_id . $result ['notes_description'];
						}
					}
					
					/*
					 * if($result['notes_id'] != null && $result['notes_id'] !=
					 * ""){
					 * $notesID = (string) $result['notes_id'];
					 *
					 * $response = $dynamodb->scan([
					 * 'TableName' => 'incidentform',
					 * 'ProjectionExpression' => 'incidentform_id, notes_id,
					 * user_id, signature, notes_pin, form_date_added ',
					 * 'ExpressionAttributeValues' => [
					 * ':val1' => ['N' => $notesID]] ,
					 * 'FilterExpression' => 'notes_id = :val1',
					 * ]);
					 *
					 *
					 * //$response = $dynamodb->scan($params);
					 *
					 * //var_dump($response['Items']);
					 * //echo '<hr> ';
					 *
					 * $forms = array();
					 * foreach($response['Items'] as $item){
					 * $form_date_added1 =
					 * str_replace("&nbsp;","",$item['form_date_added']['S']);
					 * if($form_date_added1 != null && $form_date_added1 != ""){
					 * $form_date_added =
					 * date($this->language->get('date_format_short_2'),
					 * strtotime($item['form_date_added']['S']));
					 * }else{
					 * $form_date_added = "";
					 * }
					 * $forms[] = array(
					 * 'incidentform_id' => $item['incidentform_id']['N'],
					 * 'notes_id' => $item['notes_id']['N'],
					 * 'user_id' =>
					 * str_replace("&nbsp;","",$item['user_id']['S']),
					 * 'signature' =>
					 * str_replace("&nbsp;","",$item['signature']['S']),
					 * 'notes_pin' =>
					 * str_replace("&nbsp;","",$item['notes_pin']['S']),
					 * 'form_date_added' => $form_date_added,
					 *
					 * );
					 * }
					 * }else{
					 * $forms = array();
					 * }
					 */
					
					$forms = array ();
					
					if ($result ['is_forms'] == '1') {
						if ($facilityinfo ['config_noteform_status'] == '1') {
							$allforms = $this->model_notes_notes->getforms ( $result ['notes_id'] );
							
							foreach ( $allforms as $allform ) {
								
								$forms [] = array (
										'form_type_id' => $allform ['form_type_id'],
										'forms_id' => $allform ['forms_id'],
										'design_forms' => $allform ['design_forms'],
										'custom_form_type' => $allform ['custom_form_type'],
										'notes_id' => $allform ['notes_id'],
										'form_type' => $allform ['form_type'],
										'notes_type' => $allform ['notes_type'],
										'user_id' => $allform ['user_id'],
										'signature' => $allform ['signature'],
										'notes_pin' => $allform ['notes_pin'],
										'image_url' => $allform ['image_url'],
										'image_name' => $allform ['image_name'],
										'incident_number' => $allform ['incident_number'],
										'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ) 
								);
							}
						}
					}
					
					$notestasks = array ();
					$grandtotal = 0;
					
					$ograndtotal = 0;
					
					if ($result ['task_type'] == '1') {
						$alltasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '1' );
						
						foreach ( $alltasks as $alltask ) {
							$grandtotal = $grandtotal + $alltask ['capacity'];
							$tags_ids_names = '';
							
							if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
								$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
								
								foreach ( $tags_ids1 as $tag1 ) {
									$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$tags_ids_names .= $emp_tag_id . ', ';
									}
								}
							}
							
							$out_tags_ids_names = "";
							$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
							
							if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
								$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
								$i = 0;
								
								$ooout = '1';
								// var_dump($tags_ids1);
								
								foreach ( $tags_ids1 as $tag1 ) {
									
									$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
									
									if ($tags_info12 ['emp_first_name']) {
										$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info12 ['emp_tag_id'];
									}
									
									if ($tags_info12) {
										$out_tags_ids_names .= $emp_tag_id . ', ';
									}
									
									$i ++;
								}
								
								// $ograndtotal = $i;
							} else {
								$ooout = '2';
							}
							
							// var_dump($ograndtotal);
							
							if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
								$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
							} else {
								$media_url = "";
							}
							
							if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
								$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
							} else {
								$medication_attach_url = "";
							}
							
							$notestasks [] = array (
									'notes_by_task_id' => $alltask ['notes_by_task_id'],
									'locations_id' => $alltask ['locations_id'],
									'task_type' => $alltask ['task_type'],
									'task_content' => $alltask ['task_content'],
									'user_id' => $alltask ['user_id'],
									'signature' => $alltask ['signature'],
									'notes_pin' => $alltask ['notes_pin'],
									'task_time' => $alltask ['task_time'],
									// 'media_url' => $alltask['media_url'],
									'media_url' => $media_url,
									'capacity' => $alltask ['capacity'],
									'location_name' => $alltask ['location_name'],
									'location_type' => $alltask ['location_type'],
									'notes_task_type' => $alltask ['notes_task_type'],
									'task_comments' => $alltask ['task_comments'],
									'role_call' => $alltask ['role_call'],
									'medication_attach_url' => $medication_attach_url,
									'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
									'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
									'tags_ids_names' => $tags_ids_names,
									'out_tags_ids_names' => $out_tags_ids_names 
							);
						}
					}
					
					$notesmedicationtasks = array ();
					if ($result ['task_type'] == '2') {
						$alltmasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '2' );
						
						foreach ( $alltmasks as $alltmask ) {
							
							if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
								$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
							}
							
							if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
								$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
							} else {
								$media_url = "";
							}
							
							if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
								$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
							} else {
								$medication_attach_url = "";
							}
							
							$notesmedicationtasks [] = array (
									'notes_by_task_id' => $alltmask ['notes_by_task_id'],
									'locations_id' => $alltmask ['locations_id'],
									'task_type' => $alltmask ['task_type'],
									'task_content' => $alltmask ['task_content'],
									'user_id' => $alltmask ['user_id'],
									'signature' => $alltmask ['signature'],
									'notes_pin' => $alltmask ['notes_pin'],
									'task_time' => $taskTime,
									// 'media_url' => $alltmask['media_url'],
									'media_url' => $media_url,
									'capacity' => $alltmask ['capacity'],
									'location_name' => $alltmask ['location_name'],
									'location_type' => $alltmask ['location_type'],
									'notes_task_type' => $alltmask ['notes_task_type'],
									'tags_id' => $alltmask ['tags_id'],
									'drug_name' => $alltmask ['drug_name'],
									'dose' => $alltmask ['dose'],
									'drug_type' => $alltmask ['drug_type'],
									'quantity' => $alltmask ['quantity'],
									'frequency' => $alltmask ['frequency'],
									'instructions' => $alltmask ['instructions'],
									'count' => $alltmask ['count'],
									'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
									'task_comments' => $alltmask ['task_comments'],
									'role_call' => $alltmask ['role_call'],
									'refuse' => $alltmask ['refuse'],
									'medication_file_upload' => $alltmask ['medication_file_upload'],
									'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
							);
						}
					}
					
					if ($result ['task_type'] == '6') {
						$approvaltask = $this->model_notes_notes->getapprovaltask ( $result ['task_id'] );
					} else {
						$approvaltask = array ();
					}
					
					if ($result ['task_type'] == '3') {
						$geolocation_info = $this->model_notes_notes->getGeolocation ( $result ['notes_id'] );
					} else {
						$geolocation_info = array ();
					}
					
					if ($result ['original_task_time'] != null && $result ['original_task_time'] != "00:00:00") {
						$original_task_time = date ( 'h:i A', strtotime ( $result ['original_task_time'] ) );
					} else {
						$original_task_time = "";
					}
					
					if ($result ['user_file'] != null && $result ['user_file'] != "") {
						$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result ['notes_id'], 'SSL' );
					} else {
						$user_file = "";
					}
					
					$notescomments = array ();
					if ($result ['is_comment'] == '1') {
						$allcomments = $this->model_notes_notescomment->getcomments ( $result ['notes_id'] );
					} else {
						$allcomments = array ();
					}
					
					if ($allcomments) {
						foreach ( $allcomments as $allcomment ) {
							$commentskeywords = array ();
							
							// var_dump($allcomment['keyword_file']);
							if ($allcomment ['keyword_file'] == '1') {
								$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
							} else {
								$aallkeywords = array ();
							}
							// var_dump($aallkeywords);
							if ($aallkeywords) {
								$keyImageSrc12 = array ();
								$keyname = array ();
								foreach ( $aallkeywords as $callkeyword ) {
									$commentskeywords [] = array (
											'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
											'notes_id' => $callkeyword ['notes_id'],
											'comment_id' => $callkeyword ['comment_id'],
											'keyword_id' => $callkeyword ['keyword_id'],
											'keyword_name' => $callkeyword ['keyword_name'],
											'keyword_file_url' => $callkeyword ['keyword_file_url'],
											'keyword_image' => $callkeyword ['keyword_image'],
											'img_icon' => $callkeyword ['keyword_file_url'] 
									);
								}
							}
							
							// var_dump($commentskeywords);
							$notescomments [] = array (
									'comment_id' => $allcomment ['comment_id'],
									'notes_id' => $allcomment ['notes_id'],
									'facilities_id' => $allcomment ['facilities_id'],
									'comment' => $allcomment ['comment'],
									'user_id' => $allcomment ['user_id'],
									'notes_pin' => $allcomment ['notes_pin'],
									'signature' => $allcomment ['signature'],
									'user_file' => $allcomment ['user_file'],
									'is_user_face' => $allcomment ['is_user_face'],
									'date_added' => $allcomment ['date_added'],
									'comment_date' => $allcomment ['comment_date'],
									'notes_type' => $allcomment ['notes_type'],
									'commentskeywords' => $commentskeywords 
							);
						}
					}
					
					/*
					 * $notestranscriptions = array();
					 * if($result['is_comment'] == '2'){
					 * $alltranscriptions = $this->model_notes_notescomment->gettranscriptions($result['notes_id']);
					 * }else{
					 * $alltranscriptions = array();
					 * }
					 *
					 * if($alltranscriptions){
					 * foreach ($alltranscriptions as $alltranscription) {
					 * $notestranscriptions[] = array(
					 * 'comment_id' => $alltranscription['comment_id'],
					 * 'notes_id' => $alltranscription['notes_id'],
					 * 'facilities_id' => $alltranscription['facilities_id'],
					 * 'source_transcript' => $alltranscription['source_transcript'],
					 * 'source_language' => $alltranscription['source_language'],
					 * 'target_transcript' => $alltranscription['target_transcript'],
					 * 'target_language' => $alltranscription['target_language'],
					 * 'user_id' => $alltranscription['user_id'],
					 * 'notes_pin' => $alltranscription['notes_pin'],
					 * 'signature' => $alltranscription['signature'],
					 * 'user_file' => $alltranscription['user_file'],
					 * 'is_user_face' => $alltranscription['is_user_face'],
					 * 'date_added' => $alltranscription['date_added'],
					 * 'comment_date' => $alltranscription['comment_date'],
					 * 'notes_type' => $alltranscription['notes_type'],
					 * );
					 * }
					 * }
					 */
					
					if ($result ['is_comment'] == '2') {
						$printtranscript = $this->url->link ( 'notes/transcript/printtranscript', '' . '&notes_id=' . $result ['notes_id'] . $url, 'SSL' );
					} else {
						$printtranscript = '';
					}
					
					$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'],$this->customer->getId () );
					
					$uptimes = array();
					$uptimes = $this->model_notes_notes->getupdatetime ($result ['notes_id']);
					
					
					$this->data ['notess'] [] = array (
							'notes_id' => $result ['notes_id'],
							'shift_color_value'=>$shift_time_color['shift_color_value'],
							'is_comment' => $result ['is_comment'],
							'in_total' => $result ['in_total'],
							'out_total' => $result ['out_total'],
							'manual_total' => $result ['manual_total'],
							'facilities_id' => $result ['facilities_id'],
							'notescomments' => $notescomments,
							'uptimes' => $uptimes,
							'printtranscript' => $printtranscript,
							'ooout' => $ooout,
							// 'user_file' => $result['user_file'],
							'user_file' => $user_file,
							'is_user_face' => $result ['is_user_face'],
							'is_approval_required_forms_id' => $result ['is_approval_required_forms_id'],
							'original_task_time' => $original_task_time,
							'geolocation_info' => $geolocation_info,
							'approvaltask' => $approvaltask,
							'notes_file' => $result ['notes_file'],
							'keyword_file' => $result ['keyword_file'],
							'emp_tag_id' => $result ['emp_tag_id'],
							'is_forms' => $result ['is_forms'],
							'is_reminder' => $result ['is_reminder'],
							'task_type' => $result ['task_type'],
							'visitor_log' => $result ['visitor_log'],
							'is_tag' => $result ['is_tag'],
							'is_archive' => $result ['is_archive'],
							'form_type' => $result ['form_type'],
							'generate_report' => $result ['generate_report'],
							'is_census' => $result ['is_census'],
							'is_android' => $result ['is_android'],
							'alltag' => $alltag,
							'remdata' => $remdata,
							'noteskeywords' => $noteskeywords,
							'is_private' => $result ['is_private'],
							'share_notes' => $result ['share_notes'],
							'is_offline' => $result ['is_offline'],
							'review_notes' => $result ['review_notes'],
							'is_private_strike' => $result ['is_private_strike'],
							'checklist_status' => $result ['checklist_status'],
							'notes_type' => $result ['notes_type'],
							'strike_note_type' => $result ['strike_note_type'],
							'task_time' => $task_time,
							'tag_privacy' => $privacy,
							'incidentforms' => $forms,
							'notestasks' => $notestasks,
							'grandtotal' => $grandtotal,
							'ograndtotal' => $ograndtotal,
							'boytotals' => $boytotals,
							'girltotals' => $girltotals,
							'generaltotals' => $generaltotals,
							'residentstotals' => $residentstotals,
							'notesmedicationtasks' => $notesmedicationtasks,
							'task_type' => $result ['task_type'],
							'taskadded' => $result ['taskadded'],
							'assign_to' => $result ['assign_to'],
							'highlighter_value' => $highlighterData ['highlighter_value'],
							'notes_description' => $notes_description,
							// 'keyImageSrc' => $keyImageSrc,
							// 'fileOpen' => $fileOpen,
							'images' => $images,
							'notetime' => date ( 'h:i A', strtotime ( $result ['notetime'] ) ),
							'username' => $result ['user_id'],
							'notes_pin' => $userPin,
							'signature' => $result ['signature'],
							'text_color_cut' => $result ['text_color_cut'],
							'text_color' => $result ['text_color'],
							'note_date' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $result ['note_date'] ) ),
							'status' => ($result ['status'] ? $this->language->get ( 'text_enabled' ) : $this->language->get ( 'text_disabled' )),
							'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
							'date_added2' => date ( 'D F j, Y', strtotime ( $result ['date_added'] ) ),
							'strike_user_name' => $result ['strike_user_id'],
							'strike_pin' => $result ['strike_pin'],
							'strike_signature' => $result ['strike_signature'],
							'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $result ['strike_date_added'] ) ),
							'reminder_time' => $reminder_time,
							'reminder_title' => $reminder_title,
							'facilityname' => $facilityname,
							'href' => $this->url->link ( 'notes/notes/insert', '' . '&reset=1&searchdate=' . date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ) . $url, 'SSL' ) 
					);
				}
				
				$this->data ['reviews'] = array ();
				
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
				
				$data2 = array (
						'searchdate' => $searchdate,
						'facilities_id' => $facilities_id 
				);
				
				$reviewsresults = $this->model_notes_notes->getreviews ( $data2 );
				
				foreach ( $reviewsresults as $review_info ) {
					if ($review_info ['user_id'] != null && $review_info ['user_id'] != "") {
						$reviewuser_info = $this->model_user_user->getUser ( $review_info ['user_id'] );
						$reviewusername = $reviewuser_info ['username'];
					} else {
						$reviewusername = '';
					}
					
					if ($review_info ['date_added'] != null && $review_info ['date_added'] != "0000-00-00 00:00:00") {
						$reviewDate = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $review_info ['date_added'] ) );
					} else {
						$reviewDate = '';
					}
					
					if ($review_info ['note_date'] != null && $review_info ['note_date'] != "0000-00-00 00:00:00") {
						$reviewnote_date = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $review_info ['note_date'] ) );
					} else {
						$reviewnote_date = '';
					}
					
					if ($review_info ['signature'] != null && $review_info ['signature'] != "") {
						
						$review_signature = $review_info ['signature'];
					} else {
						$review_signature = '';
					}
					
					$this->data ['reviews'] [] = array (
							'review_date' => $reviewDate,
							'review_note_date' => $reviewnote_date,
							'review_username' => $reviewusername,
							'review_signature' => $review_signature,
							'notes_pin' => $review_info ['notes_pin'],
							'notes_type' => $review_info ['notes_type'] 
					);
				}
			}
			
			if (isset ( $this->error ['warning'] )) {
				$this->data ['error_warning'] = $this->error ['warning'];
			} else {
				$this->data ['error_warning'] = '';
			}
			
			if (isset ( $this->session->data ['success_attachment'] )) {
				$this->data ['success_attachment'] = $this->session->data ['success_attachment'];
				
				unset ( $this->session->data ['success_attachment'] );
			} else {
				$this->data ['success_attachment'] = '';
			}
			
			if (isset ( $this->session->data ['success'] )) {
				$this->data ['success'] = $this->session->data ['success'];
				
				unset ( $this->session->data ['success'] );
			} else {
				$this->data ['success'] = '';
			}
			
			if (isset ( $this->session->data ['success2'] )) {
				$this->data ['success2'] = $this->session->data ['success2'];
				
				unset ( $this->session->data ['success2'] );
			} else {
				$this->data ['success2'] = '';
			}
			if (isset ( $this->session->data ['success3'] )) {
				$this->data ['success3'] = $this->session->data ['success3'];
				
				unset ( $this->session->data ['success3'] );
			} else {
				$this->data ['success3'] = '';
			}
			
			if (isset ( $this->error ['notes_description'] )) {
				$this->data ['error_notes_description'] = $this->error ['notes_description'];
			} else {
				$this->data ['error_notes_description'] = '';
			}
			
			if (isset ( $this->error ['notetime'] )) {
				$this->data ['error_notetime'] = $this->error ['notetime'];
			} else {
				$this->data ['error_notetime'] = '';
			}
			
			if (isset ( $this->error ['notes_file'] )) {
				$this->data ['error_notes_file'] = $this->error ['notes_file'];
			} else {
				$this->data ['error_notes_file'] = '';
			}
			
			$this->data ['currentTime'] = date ( 'm-d-Y', strtotime ( 'now' ) );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if (! isset ( $this->request->get ['notes_id'] )) {
				$this->data ['action'] = $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' );
			} else {
				$this->data ['action'] = $this->url->link ( 'notes/notes/update', '' . '&notes_id=' . $this->request->get ['notes_id'] . $url, 'SSL' );
			}
			
			$this->data ['cancel'] = $this->url->link ( 'notes/notes/insert', '' . '&reset=1' . $url, 'SSL' );
			
			$this->data ['addNotes'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert2', '' . $url2, 'SSL' ) );
			
			$this->data ['logout'] = $this->url->link ( 'common/logout', '', 'SSL' );
			
			$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/search', '' . $url, 'SSL' );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['fromdate'] != null && $this->request->get ['fromdate'] != "") {
				$url2 .= '&fromdate=' . $this->request->get ['fromdate'];
			}
			if ($this->request->get ['highlighter'] != null && $this->request->get ['highlighter'] != "") {
				$url2 .= '&highlighter=' . $this->request->get ['highlighter'];
			}
			$this->data ['reviewUrl'] = $this->url->link ( 'notes/notes/review', '' . '&review=1' . $url2, 'SSL' );
			
			if ($this->session->data ['notes_id'] == null && $this->session->data ['notes_id'] == "") {
				$notes_info = $this->model_notes_notes->getnotes ( $this->session->data ['notes_id'] );
			}
			
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				
				$this->load->model ( 'notes/tags' );
				$taginfo = $this->model_notes_tags->getTag ( $this->request->get ['tags_id'] );
				
			}
			
			if (isset ( $this->request->post ['notes_description'] )) {
				$this->data ['notes_description'] = $this->request->post ['notes_description'];
			} elseif (! empty ( $taginfo )) {
				$this->data ['notes_description'] = $taginfo ['emp_first_name'] . ' ' . $taginfo ['emp_last_name'] . ' | ';
			
			}else if($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != ""){

                $this->data ['tagsids'] = $this->request->get ['tags_ids'];
				$this->load->model ( 'notes/tags' );

            	$ids=array();

            	$tags_ids = $this->request->get ['tags_ids']; 
                $all_tag_ids = explode (",", $tags_ids);

                foreach($all_tag_ids as $mytags) {

                	$taginfo = $this->model_notes_tags->getTag ($mytags );                 

					if (! empty ( $taginfo )) {

						//array_push($ids,$taginfo ['emp_first_name'] . ' ' . $taginfo ['emp_last_name'] . ' | ');                	
				    
					}             	
                }              

                //$this->data ['notes_description']= implode(" ",$ids);;	
				$this->data ['tag_icon']= '<span style="float: left;"><img src="sites/view/digitalnotebook/image/udrule.png" width="30px" height="30px" alt="" /></span>' ;	
                $this->data ['notes_description']= '' ;	
			} else {
				$this->data ['notes_description'] = '';
			}
			
			if (isset ( $this->request->post ['keyword_file'] )) {
				$this->data ['keyword_file'] = $this->request->post ['keyword_file'];
				if ($this->request->post ['keyword_file'] && file_exists ( DIR_IMAGE . 'icon/' . $this->request->post ['keyword_file'] )) {
					$keyword_file = $this->model_notes_image->resize ( 'icon/' . $this->request->post ['keyword_file'], 20, 20 );
					
					$this->data ['keyword_file_img'] = '<img src="' . $keyword_file . '">';
				} else {
					$this->data ['keyword_file_img'] = "";
				}
			} elseif (! empty ( $notes_info ['keyword_file'] )) {
				$this->data ['keyword_file'] = $notes_info ['keyword_file'];
				
				if ($notes_info ['keyword_file'] && file_exists ( DIR_IMAGE . 'icon/' . $notes_info ['keyword_file'] )) {
					$keyword_file = $this->model_notes_image->resize ( 'icon/' . $notes_info ['keyword_file'], 20, 20 );
					
					$this->data ['keyword_file_img'] = '<img src="' . $keyword_file . '">';
				} else {
					$this->data ['keyword_file_img'] = "";
				}
			} else {
				$this->data ['keyword_file'] = '';
			}
			
			if (isset ( $this->request->post ['highlighter_id'] )) {
				$this->data ['highlighter_id'] = $this->request->post ['highlighter_id'];
			} elseif (! empty ( $notes_info )) {
				$this->data ['highlighter_id'] = $notes_info ['highlighter_id'];
			} else {
				$this->data ['highlighter_id'] = '';
			}
			
			if (isset ( $this->request->post ['tags_id'] )) {
				$this->data ['tags_id'] = $this->request->post ['tags_id'];
			} elseif (! empty ( $taginfo )) {
				$this->data ['tags_id'] = $taginfo ['tags_id'];
			} else { 
				$this->data ['tags_id'] = '';
			}
			if (isset ( $this->request->post ['userids'] )) {
				$this->data ['userids'] = $this->request->post ['userids'];
			} else {
				$this->data ['userids'] = '';
			}
			
			if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
				$this->data ['configUrl'] = $this->config->get ( 'config_ssl' );
			} else {
				$this->data ['configUrl'] = HTTP_SERVER;
			}
			
			$this->load->model ( 'setting/highlighter' );
			
			$this->data ['highlighters'] = $this->model_setting_highlighter->gethighlighters ( $data );
			
			$this->load->model ( 'setting/keywords' );
			
			$this->data ['keywords'] = array ();
			
			$data3 = array (
					'facilities_id' => $facilities_id,
					'monitor_time' => '6' 
			);
			
			$keywords = $this->model_setting_keywords->getkeywords ( $data3 );
			
			$url2 = "";
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			foreach ( $keywords as $keyword ) {
				
				if ($keyword ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $keyword ['keyword_image'] )) {
					// $image = $this->model_notes_image->resize('icon/' . $keyword['keyword_image'], 35, 35);
				}
				
				$image = $keyword ['keyword_image'];
				
				$lines_arr = preg_split ( '/\n|\r/', $keyword ['keyword_name'] );
				$num_newlines = count ( $lines_arr );
				
				// var_dump($num_newlines);
				
				if ($keyword ['monitor_time'] == '1') {
					
					if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
						$url2 .= '&notesactivenote=1';
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					} else {
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					}
				} else {
					$activenote_url = '';
				}
				
				$this->data ['keywords'] [] = array (
						'keyword_id' => $keyword ['keyword_id'],
						'keyword_name' => $keyword ['keyword_name'],
						// 'keyword_name2' => str_replace(array("\r", "\n"), '',
						// $keyword['keyword_name']),
						'keyword_name2' => str_replace ( array (
								"\r",
								"\n" 
						), '\n', $keyword ['keyword_name'] ),
						'keyword_image' => $keyword ['keyword_image'],
						'monitor_time' => $keyword ['monitor_time'],
						'activenote_url' => $activenote_url,
						'img_icon' => $image,
						'num_newlines' => $num_newlines 
				);
			}
			
			$this->load->model ( 'setting/activeforms' );
			$dataforms = array (
					'facilities_id' => $facilities_id,
					'monitor_time' => '3' 
			);
			
			$activefroms = $this->model_setting_activeforms->getActivekeywords ( $dataforms );
			
			$url2 = "";
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->load->model ( 'form/form' );
			
			foreach ( $activefroms as $activefrom ) {
				
				$keydetail = $this->model_setting_keywords->getkeywordDetail ( $activefrom ['keyword_id'] );
				$formdetails = $this->model_form_form->getFormdata ( $activefrom ['forms_id'] );
				
				if ($keydetail ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $keydetail ['keyword_image'] )) {
					$image = $this->model_notes_image->resize ( 'icon/' . $keydetail ['keyword_image'], 35, 35 );
				}
				$lines_arr = preg_split ( '/\n|\r/', $keydetail ['keyword_name'] );
				$num_newlines = count ( $lines_arr );
				
				if ($formdetails ['open_search'] == '1') {
					
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/linkedform', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
				} else {
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
				}
				
				// $activenote_url = str_replace('&amp;', '&', $this->url->link('form/form', '' . '&forms_design_id=' . $activefrom['forms_id'] . '&keyword_id=' . $activefrom['keyword_id'] . '&activeform_id=' . $activefrom['activeform_id'] . $url2, 'SSL'));
				
				$this->data ['activefroms'] [] = array (
						'keyword_id' => $keydetail ['keyword_id'],
						'keyword_image' => $keydetail ['keyword_image'],
						'activeform_name' => $activefrom ['activeform_name'],
						'activenote_url' => $activenote_url,
						'img_icon' => $image,
						'num_newlines' => $num_newlines 
				);
			}
			
			$this->load->model ( 'facilities/facilities' );
			$results = $this->model_facilities_facilities->getfacilitiess ( $data );
			
			foreach ( $results as $result ) {
				
				$this->data ['facilitiess'] [] = array (
						'facilities_id' => $result ['facilities_id'],
						'facility' => $result ['facility'] 
				);
			}
			
			$this->load->model ( 'form/form' );
			
			$data3 = array ();
			$data3 ['status'] = '1';
			// $data3['order'] = 'sort_order';
			$data3 ['is_parent'] = '1';
			$data3 ['facilities_id'] = $facilities_id;
			
			$custom_forms = $this->model_form_form->getforms ( $data3 );
			
			$this->data ['custom_forms'] = array ();
			foreach ( $custom_forms as $custom_form ) {
				
				if ($custom_form ['open_search'] == '1') {
					$href = $this->url->link ( 'form/linkedform', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' );
				} else {
					$href = $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' );
				}
				
				$this->data ['custom_forms'] [] = array (
						'forms_id' => $custom_form ['forms_id'],
						'form_name' => $custom_form ['form_name'],
						'form_href' => $href 
				);
				/*
				 * $this->data['custom_forms'][] = array(
				 * 'forms_id' => $custom_form['forms_id'],
				 * 'form_name' => $custom_form['form_name'],
				 * 'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
				 * );
				 */
			}
			
			/*
			 * $this->load->model('user/user');
			 * $this->data['users'] =
			 * $this->model_user_user->getUsersByFacility($this->customer->getId());
			 */
			$this->data ['note_time'] = date ( 'h:i A' );
			
			$this->data ['notetime'] = date ( 'h:i A' );
			
			$this->load->model ( 'notes/notes' );
			if ($this->request->get ['notes_id']) {
				$notes_id = $this->request->get ['notes_id'];
			} else {
				$notes_id = $this->request->get ['updatenotes_id'];
			}
			
			$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
			
			// $this->data['url_load'] =
			// $this->getChild('notes/notes/getNoteData', $url2);
			
			$this->data ['notes_id'] = $this->request->get ['notes_id'];
			$this->data ['updatenotes_id'] = $this->request->get ['updatenotes_id'];
			
			// if($this->session->data['advance_search'] == '1'){
			
			$url = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			if ($this->request->get ['review'] != null && $this->request->get ['review'] != "") {
				$url .= '&review=' . $this->request->get ['review'];
			}
			if ($this->request->get ['fromdate'] != null && $this->request->get ['fromdate'] != "") {
				$url .= '&fromdate=' . $this->request->get ['fromdate'];
			}
			if ($this->request->get ['highlighter'] != null && $this->request->get ['highlighter'] != "") {
				$url .= '&highlighter=' . $this->request->get ['highlighter'];
			}
			if ($this->request->get ['activenote'] != null && $this->request->get ['activenote'] != "") {
				$url .= '&activenote=' . $this->request->get ['activenote'];
			}
			
			$this->session->data ['pagenumber'] = ceil ( $notes_total / $config_admin_limit );
			
			if ($this->session->data ['pagenumber'] > 0) {
				$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
			} else {
				$this->data ['pagenumber'] = 1;
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$this->data ['hide_text'] = $this->request->get ['page'];
			} else {
				$this->data ['hide_text'] = 1;
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$this->data ['pagination_review'] = $this->request->get ['page'];
			} else {
				$this->data ['pagination_review'] = 1;
			}
			
			$count = ceil ( $notes_total / 200 );
			
			if ($count > 1) {
				$this->data ['sharenotes_Url'] = $this->url->link ( 'notes/sharenote/searchnotepage', '' . $url, 'SSL' );
			} else {
				$this->data ['sharenotes_Url'] = $this->url->link ( 'notes/sharenote/searchnoteshare', '' . $url, 'SSL' );
			}
			
			$pagination = new Pagination ();
			$pagination->total = $notes_total;
			$pagination->page = $page;
			$pagination->limit = $config_admin_limit;
			
			$pagination->text = ''; // $this->language->get('text_pagination');
			$pagination->url = $this->url->link ( 'notes/notes/insert', '' . $url . '&page={page}', 'SSL' );
			
			$this->data ['pagination'] = $pagination->render ();
			// }
			
			$this->data ['showLoaderafter'] = "2";
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form.php';
			
			if ($this->request->get ['route'] != "resident/cases/dashboard2") {
				$this->children = array (
						'common/header',
						'common/footer' 
				);
			}
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Notes getform' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNotesgetform', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	protected function validateForm() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ((utf8_strlen ( trim ( $this->request->post ['notes_description'] ) ) < 1)) {
			$this->error ['notes_description'] = $this->language->get ( 'error_required' );
		}
		
		if ((utf8_strlen ( trim ( $this->request->post ['notetime'] ) ) < 1)) {
			$this->error ['notetime'] = 'Please select note time';
		}
		
		if ($this->request->post ['note_date'] != null && $this->request->post ['note_date'] != "") {
			$note_date = date ( 'm-d-Y', strtotime ( $this->request->post ['note_date'] ) );
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
			
			if ($current_date < $note_date) {
				$this->error ['warning'] = "You can not add future notes";
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function insert2() {
		$this->language->load ( 'notes/notes' );

		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );

		$this->load->model ( 'notes/notes' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];

		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );


		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );

		$dataaaa = array ();

		$ddss = array ();
		$ddss1 = array ();
		if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
		$ddss [] = $resulsst ['notes_facilities_ids'];

		$ddss [] = $this->customer->getId ();
		$sssssdd = implode ( ",", $ddss );
		}

		$dataaaa ['facilities'] = $sssssdd;
		$this->data ['masterfacilities'] = $this->model_facilities_facilities->getfacilitiess ( $dataaaa );

		$this->data ['is_master_facility'] = $resulsst ['is_master_facility'];



		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {

		if ($resulsst ['is_master_facility'] == '1') {
		if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
		$facilities_id = $this->session->data ['search_facilities_id'];
		} else {
		$facilities_id = $this->customer->getId ();
		}
		} else {
		$facilities_id = $this->customer->getId ();
		}

		if ($this->session->data ['attachments'] != null && $this->session->data ['attachments'] != "") {
			$attachemnets = $this->session->data ['attachments'];
  
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$notetime = date('H:i:s', strtotime('now'));
        
			if ($data['imgOutput']) {
				$data['imgOutput'] = $this->request->post['imgOutput'];
			} else {
				$data['imgOutput'] = $this->request->post['signature'];
			}

			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
				if($attachemnets['tags_id'] !=NULL && $attachemnets['tags_id'] !=""){
					$tags = explode(',',$attachemnets['tags_id']);
					//$data ['tags_id_list1'] = $tags;

					foreach($tags as $tag){

						$this->load->model('setting/tags');
						$tag_info = $this->model_setting_tags->getTag( $tag);
						
						$data['emp_tag_id'] = $tag_info['emp_tag_id'];
						$data['tags_id'] = $tag_info['tags_id'];
						

						//$tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | ';
						
						$this->load->model('setting/keywords');
						$keywordData2 = $this->model_setting_keywords->getkeywordDetail($fdata['keyword_id']);
					
						$data['keyword_file'] = $keywordData2['keyword_image'];
						

						//if ($this->request->post['comments'] != null && $this->request->post['comments']) {
							//$comments = ' | ' . $this->request->post['comments'];
						//}


						if ($attachemnets['file_name'] != null && $attachemnets['file_name']) {
							$file_name = ' | ' . $attachemnets['file_name'];
						}

						if ($attachemnets['classification'] != null && $attachemnets['classification']) {
							$fdata = array(
								'case_id' => $attachemnets['classification'],
							);
							$classid_info = $this->model_notes_notes->getFormcaseId($fdata);
							$description = ' | ' . $classid_info['name'];
							$forms_id = $classid_info['forms'];
							
						}
						if ($attachemnets['description'] != null && $attachemnets['description']) {
							$description .= ' | ' . $attachemnets['description'];
						}


						if($attachemnets['file_type'] == 'Form'){
							$this->load->model('form/form');
							$form_info = $this->model_form_form->getFormdata($attachemnets['form']);
							
							$fdata = array(
							'from_id' => $attachemnets['form'],
							);
							$classid = $this->model_notes_notes->getFormcaseId($fdata);
							$description .= ' | ' . $classid['name'];
							$case_id = $classid['case_id'];
							$forms_id = $attachemnets['form'];
							
							$data['notes_description'] = 'Form ' . $form_info['form_name'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
						}


						if($attachemnets['file_type'] == 'Document'){
							$data['notes_description'] = 'Document ' . $form_info['form_name'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
						}

						if($attachemnets['file_type'] == 'Picture'){
							$data['notes_description'] = 'Image ' . $form_info['form_name'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
						}

						if($attachemnets['file_type'] == 'Other'){
							$data['notes_description'] = 'Other ' . $attachemnets['other'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
						}

						
						$data['date_added'] = $date_added;
						$data['note_date'] = $date_added;
						$data['notetime'] = $notetime;
						

						$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id);
						
						if($case_id !=NULL && $case_id !="" ){
							$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '".$case_id."'  where notes_id = '".$notes_id."'";
							$this->db->query($slq1case);
						}else{
							$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '".$attachemnets['classification']."'  where notes_id = '".$notes_id."'";
							$this->db->query($slq1case);
						}
						
						if($attachemnets['image'] != null && $attachemnets['image'] != ""){
							$notes_media_extention = $attachemnets['img_extension'];
							$notes_file_url = $attachemnets['image'];
							$formData = array ();
							$formData ['media_user_id'] = '';
							$formData ['media_signature'] = '';
							$formData ['media_pin'] = '';
							$formData ['facilities_id'] = $facilities_id;
							$formData ['noteDate'] = $date_added;

							$notes_media_id = $this->model_notes_notes->updateNoteFile ( $notes_id, $notes_file_url, $notes_media_extention, $formData );
							
							$slq12pp = "UPDATE " . DB_PREFIX . "notes_media SET forms_id = '".$forms_id."' where notes_media_id = '".$notes_media_id."'";
							$this->db->query($slq12pp);
						}


						if ($forms_id) {
							$this->load->model('form/form');
							$formdatai = $this->model_form_form->getFormdata($forms_id);
								
								$data23 = array();
								$data23['forms_design_id'] = $forms_id;
								$data23['notes_id'] = $notes_id;
								$data23['tags_id'] = $tag_info['tags_id'];
								$data23['facilities_id'] = $facilities_id;
								$this->load->model('form/form');
								$formreturn_id = $this->model_form_form->addFormdata($formdatai, $data23);	
								
								$slq12pp = "UPDATE " . DB_PREFIX . "forms SET parent_id = '".$notes_id."', image_url = '".$attachemnets['image']."', image_name = '".$attachemnets['file_name']."' where forms_id = '".$formreturn_id."'";
								$this->db->query($slq12pp);
								
								$slq12pp = "UPDATE " . DB_PREFIX . "notes SET is_forms = '1' where notes_id = '".$notes_id."'";
								$this->db->query($slq12pp);

							   // $this->model_form_form->updatenote($notes_id, $formreturn_id );
						}

					}

				}else{

					$this->load->model('setting/tags');
					//$tag_info = $this->model_setting_tags->getTag( $attachemnets['tags_id']);
					
					//$data['emp_tag_id'] = $tag_info['emp_tag_id'];
					//$data['tags_id'] = $tag_info['tags_id'];

					//$tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | ';
					
					$this->load->model('setting/keywords');
					$keywordData2 = $this->model_setting_keywords->getkeywordDetail($fdata['keyword_id']);
				
					$data['keyword_file'] = $keywordData2['keyword_image'];
					

					//if ($this->request->post['comments'] != null && $this->request->post['comments']) {
						//$comments = ' | ' . $this->request->post['comments'];
					//}

					if ($attachemnets['file_name'] != null && $attachemnets['file_name']) {
						$file_name = ' | ' . $attachemnets['file_name'];
					}

					if ($attachemnets['classification'] != null && $attachemnets['classification']) {
						$fdata = array(
							'case_id' => $attachemnets['classification'],
						);
						$classid_info = $this->model_notes_notes->getFormcaseId($fdata);
						$description = ' | ' . $classid_info['name'];
						$forms_id = $classid_info['forms'];
					}
					
					
					if ($attachemnets['description'] != null && $attachemnets['description']) {
						$description .= ' | ' . $attachemnets['description'];
					}


				if($attachemnets['file_type'] == 'Form'){
					$this->load->model('form/form');
					$form_info = $this->model_form_form->getFormdata($attachemnets['form']);
					$fdata = array(
					'from_id' => $attachemnets['form'],
					);
					$classid = $this->model_notes_notes->getFormcaseId($fdata);
					$description .= ' | ' . $classid['name'];
					$case_id = $classid['case_id'];
					
					$forms_id = $attachemnets['form'];
					$data['notes_description'] = 'Form ' . $form_info['form_name'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
				}


				if($attachemnets['file_type'] == 'Document'){
					$data['notes_description'] = 'Document has been added | ' . $description . '' . $comments . '' . $file_name;
				}


				if($attachemnets['file_type'] == 'Picture'){

					$data['notes_description'] = 'Image has been added | ' . $description . '' . $comments . '' . $file_name;
				}

				if($attachemnets['file_type'] == 'Other'){
					$data['notes_description'] = 'Other Data - ' . $attachemnets['other'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
				}

				
				$data['date_added'] = $date_added;
				$data['note_date'] = $date_added;
				$data['notetime'] = $notetime;
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id);
				
				if($case_id !=NULL && $case_id !="" ){
					$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '".$case_id."'  where notes_id = '".$notes_id."'";
					$this->db->query($slq1case);
				}else{
					$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '".$attachemnets['classification']."'  where notes_id = '".$notes_id."'";
					$this->db->query($slq1case);
				}
				
				if($attachemnets['image'] != null && $attachemnets['image'] != ""){
					$notes_media_extention = $attachemnets['img_extension'];
					$notes_file_url = $attachemnets['image'];
					$formData = array ();
					$formData ['media_user_id'] = '';
					$formData ['media_signature'] = '';
					$formData ['media_pin'] = '';
					$formData ['facilities_id'] = $facilities_id;
					$formData ['noteDate'] = $date_added;

					$notes_media_id = $this->model_notes_notes->updateNoteFile ( $notes_id, $notes_file_url, $notes_media_extention, $formData );
					
					$slq12pp = "UPDATE " . DB_PREFIX . "notes_media SET forms_id = '".$forms_id."' where notes_media_id = '".$notes_media_id."'";
					$this->db->query($slq12pp);
				}

				if ($forms_id) {
						$this->load->model('form/form');
						$formdatai = $this->model_form_form->getFormdata($forms_id);
						
						$data23 = array();
						$data23['forms_design_id'] = $forms_id;
						$data23['notes_id'] = $notes_id;
						$data23['tags_id'] = $tag_info['tags_id'];
						$data23['facilities_id'] = $facilities_id;
						$this->load->model('form/form');
						$formreturn_id = $this->model_form_form->addFormdata($formdatai, $data23);	
						
						$slq12pp = "UPDATE " . DB_PREFIX . "forms SET parent_id = '".$notes_id."', image_url = '".$attachemnets['image']."' , image_name = '".$attachemnets['file_name']."' where forms_id = '".$formreturn_id."'";
						$this->db->query($slq12pp);
						
						$slq12pp = "UPDATE " . DB_PREFIX . "notes SET is_forms = '1' where notes_id = '".$notes_id."'";
						$this->db->query($slq12pp);

					   // $this->model_form_form->updatenote($notes_id, $formreturn_id );
				}


			}
			
			unset($this->session->data['attachments']);



			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
				
			
			if ($facility['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
				$this->db->query($sql122);
			}
			if ($facility['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
				$this->db->query($sql13);
			}
			
			if ($facility['is_enable_add_notes_by'] == '1') {
            if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
               
        
                $notes_file = $this->session->data['local_notes_file'];
                $outputFolder = $this->session->data['local_image_dir'];
                require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                $this->load->model('notes/notes');
                $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
                    $this->model_notes_notes->updateuserverified('2', $notes_id);
                }
        
                if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
                    $this->model_notes_notes->updateuserverified('1', $notes_id);
                }
        
                unlink($this->session->data['local_image_dir']);
                unset($this->session->data['username_confirm']);
                unset($this->session->data['local_image_dir']);
                unset($this->session->data['local_image_url']);
                unset($this->session->data['local_notes_file']);
            }
        }


		}else{	

			if($this->request->get ['notes_ids'] != null && $this->request->get ['notes_ids'] != ""){
				$sssssdds = explode(",",$this->request->get['notes_ids']);
				
				foreach($sssssdds as $sssssd){

					$result = $this->model_notes_notes->getnotes ( $sssssd );
					$this->model_notes_notes->updatenotes ( $this->request->post, $result['facilities_id'], $sssssd );

					//$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $sssssd );
				}
				
				$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $sssssdds );

			}else{
				
				if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {

					$this->load->model ('setting/tags');

					$tags_info1 = $this->model_setting_tags->getTag ($this->request->get ['tags_id']);

					if($tags_info1){

						$facilities_id1 = $tags_info1['facilities_id'];
						
					}else{
						$facilities_id1 = $facilities_id;
					}
				
				}else{
					$facilities_id1 = $facilities_id;
				}
				
				
				
				
				$this->model_notes_notes->updatenotes ( $this->request->post, $facilities_id1, $this->request->get ['notes_id'] );
			}
		}



		/*
		* $this->load->model('notes/notes');
		*
		* $timezone_name = $this->customer->isTimezone();
		* $timeZone = date_default_timezone_set($timezone_name);
		*
		*
		* if ($this->request->get['searchdate'] != null &&
		* $this->request->get['searchdate'] != "") {
		* $noteTime = date('H:i:s');
		*
		* $date = str_replace('-', '/', $this->request->get['searchdate']);
		* $res = explode("/", $date);
		* $changedDate = $res[1]."-".$res[0]."-".$res[2];
		* $changedDate2 = $res[2]."-".$res[0]."-".$res[1];
		*
		* $this->data['note_date'] = $changedDate.' '.$noteTime;
		* $searchdate = $changedDate2;
		*
		*
		* } else {
		* $searchdate = date('Y-m-d',strtotime('now'));
		* }
		*
		* $endTime = date('H:i:s',strtotime("-2 minutes",
		* strtotime('now')));
		* $startTime = date('H:i:s',strtotime('now'));
		* if($this->request->get['last_notesID'] != null &&
		* $this->request->get['last_notesID'] != ""){
		* $notes_info =
		* $this->model_notes_notes->getnotes($this->request->get['last_notesID']);
		* }
		*
		* if($notes_info != null && $notes_info != ""){
		* $notetime = date('H:i:s', strtotime("+0 minutes",
		* strtotime($notes_info['update_date'])));
		* }else{
		* $notetime = date('H:i:s',strtotime("-2 minutes",
		* strtotime('now')));
		* }
		*
		* $timeinterval = array();
		*
		* $timeinterval = array(
		* 'searchdate' => $searchdate,
		* //'note_date_from' => $searchdate,
		* //'note_date_to' => $searchdate,
		* //'search_time_start' => $endTime,
		* //'search_time_to' => $startTime,
		* //'notes_id' => $this->request->get['notes_id']
		* 'sync_data'=>'2',
		* 'facilities_timezone'=>$this->customer->isTimezone(),
		* 'notetime'=>$notetime,
		*
		* );
		*
		*
		*
		* $notes = $this->model_notes_notes->getnotess($timeinterval);
		* $notes2 = array();
		*
		* foreach($notes as $note){
		*
		* //if($this->request->get['notes_id'] != $note['notes_id']){
		* $notes2[] = array(
		* 'notes_id' => $note['notes_id']
		* );
		*
		* //$notes2[] = $note['notes_id'];
		* //}
		*
		* }
		*
		* $nkey = $this->session->data['session_cache_key'];
		* $notes_data = $this->cache->get('notes'.$nkey);
		*
		* if ($notes_data) {
		* foreach($notes_data as $note){
		* //if($this->request->get['notes_id'] != $note['notes_id']){
		* $notes2[] = array(
		* 'notes_id' => $note['notes_id']
		* );
		* //$notes23[] = $note['notes_id'];
		* //}
		* }
		*
		* }
		*
		* //var_dump($notes2);
		* //echo "<hr>";
		* //var_dump($notes23);
		* //echo "<hr>";
		* //$fnotesids = array_merge($notes2,$notes23);
		* //array_unique($fnotesids);
		*
		*
		* //echo "<hr>";
		*
		* //$noteid = $this->session->data['session_cache_key'];
		* $nkey = $this->session->data['session_cache_key'];
		* $this->cache->set('notes'.$nkey, $notes2);
		*
		* /// var_dump($notes2);
		*/

		// $this->data['notes_info'] =
		// $this->model_notes_notes->getnotes($this->request->get['notes_id']);

		$this->language->load ( 'notes/notes' );

		$url2 = "";
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
		$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}

		// $this->data['url_load'] =
		// $this->getChild('notes/notes/getNoteData',$this->request->get['notes_id']);
		$this->data ['notes_id'] = $this->request->get ['notes_id'];
		if($this->request->get ['notes_ids'] == null && $this->request->get ['notes_ids'] == ""){
		$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $this->request->get ['notes_id'] );
		}

		$timezone_name = $this->customer->isTimezone ();
		$timeZone = date_default_timezone_set ( $timezone_name );
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
		$noteTime = date ( 'H:i:s' );

		$date = str_replace ( '-', '/', $this->request->get ['searchdate'] );
		$res = explode ( "/", $date );
		$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];

		$this->data ['note_date'] = $changedDate . ' ' . $noteTime;
		$searchdate = $this->request->get ['searchdate'];
		$this->data ['searchdate'] = $this->request->get ['searchdate'];

		if (($searchdate) >= (date ( 'm-d-Y' ))) {
		$this->data ['back_date_check'] = "1";
		} else {
		$this->data ['back_date_check'] = "2";
		}
		} else {
		$this->data ['note_date'] = date ( 'Y-m-d H:i:s' );
		$this->data ['searchdate'] = date ( 'm-d-Y' );
		}

		// var_dump($this->data['url_load']);
		// var_dump($this->data['url_load2']);

		// die;

		$this->session->data ['success'] = $this->language->get ( 'text_success' );

		unset ( $this->session->data ['notesdatas'] );
		unset ( $this->session->data ['highlighter_id'] );
		unset ( $this->session->data ['notes_id'] );
		unset ( $this->session->data ['text_color_cut'] );
		unset ( $this->session->data ['text_color'] );
		unset ( $this->session->data ['note_date'] );
		unset ( $this->session->data ['notes_file'] );
		unset ( $this->session->data ['update_reminder'] );

		unset ( $this->session->data ['ssincedentform'] );

		unset ( $this->session->data ['keyword_file'] );
		// unset($this->session->data['pagenumber']);

		unlink ( $this->session->data ['local_image_dir'] );
		unset ( $this->session->data ['username_confirm'] );
		unset ( $this->session->data ['local_image_dir'] );
		unset ( $this->session->data ['local_image_url'] );
		unset ( $this->session->data ['local_notes_file'] );

		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
		$url2 = '&searchdate=' . $this->request->get ['searchdate'];
		}
		/*
		* if ($this->session->data['pagenumber'] != null &&
		* $this->session->data['pagenumber'] != "") {
		* $url2. = '&page=' . $this->session->data['pagenumber'];
		* }
		*/

		// $this->redirect(str_replace('&amp;', '&',
		// $this->url->link('notes/notes/insert', '' . $url2, 'SSL')));
		}

		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );

		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );


		$url2 = "";

		$this->data ['config_tag_status'] = $this->customer->isTag ();

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
		//$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );

		if ($pagenumber_all != null && $pagenumber_all != "") {
		if ($pagenumber_all > 1) {
		$url2 .= '&page=' . $pagenumber_all;
		}
		}

		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
		$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}

		if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
		$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
		}

		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
		$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['notes_ids'] != null && $this->request->get ['notes_ids'] != "") {
		$url2 .= '&notes_ids=' . $this->request->get ['notes_ids'];
		}
		if($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		if($this->request->get['tags_ids'] != null && $this->request->get['tags_ids'] != ""){
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		
		if($this->request->get['locationids'] != null && $this->request->get['locationids'] != ""){
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
		}
		
		if($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != ""){
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
		}

		$this->data ['action2'] = $this->url->link ( 'notes/notes/insert2', '' . $url2, 'SSL' );	

		
		if(($this->request->get['tags_id']!="" && $this->request->get['tags_id']!=null) || ($this->request->get['tags_ids']!=null && $this->request->get['tags_ids']!="") ){
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '',  'SSL' ) );
		}/*else{

			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		}*/

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
		
		
		
		
		if (isset($this->request->post['comments'])) {
			$this->data['comments'] = $this->request->post['comments'];
		} else {
			$this->data['comments'] = '';
		}


		// var_dump($this->session->data['username_confirm']);

		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];

		if (isset ( $this->request->post ['user_id'] )) {
		$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
		$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
		$this->data ['user_id'] = $this->session->data ['username_confirm'];
		}  /*
		  * elseif (!empty($this->session->data['user_enroll_confirm'])) {
		  * $this->data['user_id'] =
		  * $this->session->data['user_enroll_confirm'];
		  * }
		  */
		else {
		$this->data ['user_id'] = '';
		}

		if ($this->request->get ['tags_id']) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
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

		/* monitor time */
		if (isset ( $this->error ['override_monitor_time_user_id_checkbox'] )) {
		$this->data ['error_override_monitor_time_user_id_checkbox'] = $this->error ['override_monitor_time_user_id_checkbox'];
		} else {
		$this->data ['error_override_monitor_time_user_id_checkbox'] = '';
		}

		if (isset ( $this->error ['override_monitor_time_user_id'] )) {
		$this->data ['error_override_monitor_time_user_id'] = $this->error ['override_monitor_time_user_id'];
		} else {
		$this->data ['error_override_monitor_time_user_id'] = '';
		}

		if (isset ( $this->request->post ['override_monitor_time_user_id_checkbox'] )) {
		$this->data ['override_monitor_time_user_id_checkbox'] = $this->request->post ['override_monitor_time_user_id_checkbox'];
		} else {
		$this->data ['override_monitor_time_user_id_checkbox'] = '';
		}

		if (isset ( $this->request->post ['override_monitor_time_user_id'] )) {
		$this->data ['override_monitor_time_user_id'] = $this->request->post ['override_monitor_time_user_id'];
		} else {
		$this->data ['override_monitor_time_user_id'] = '';
		}

		$this->data ['createtask'] = '1';

		if($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != ""){
		$a212 = array ();
		$a212 ['notes_id'] = $this->request->get ['notes_id'];
		$a212 ['facilities_id'] = $this->customer->getId ();

		$active_note_info_actives = $this->model_notes_notes->getNotebyactivenotes ( $a212 );
		// var_dump($active_note_info_actives);

		if ($active_note_info_actives != null && $active_note_info_actives != "") {
		foreach ( $active_note_info_actives as $active_note_info_active ) {

		if ($active_note_info_active ['keyword_id'] != null && $active_note_info_active ['keyword_id'] != "") {

		$this->load->model ( 'setting/keywords' );
		$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info_active ['keyword_id'] );
		$this->data ['monitor_time'] [] = $keywordData2 ['monitor_time'];

		if ($keywordData2 ['monitor_time'] == '1') {

		// var_dump($keywordData2['monitor_time']);

		$a21 = array ();
		// $a21['notes_id'] = $this->request->get['notes_id'];
		$a21 ['is_monitor_time'] = '1';
		$a21 ['facilities_id'] = $this->customer->getId ();

		$active_note_infos = $this->model_notes_notes->getNotebyactivenotes ( $a21 );

		// var_dump($active_note_infos);

		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );

		foreach ( $active_note_infos as $active_note_info ) {

		$note_info = $this->model_notes_notes->getNote ( $active_note_info ['notes_id'] );

		$this->data ['monitortimes'] [] = array (
			'keyword_name' => $active_note_info ['keyword_name'],
			'user_id' => $note_info ['user_id'],
			'notes_id' => $note_info ['notes_id'],
			'caltime' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['date_added'] ) )
			);
		}
		}
		}
		}
		}
		}
		
		$this->data ['hidetagsids'] = 0;
		if($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
			$url2 .= '&tagsids=' . $this->request->get ['tagsids'];
		}
		
		if($this->request->get['locationids'] != null && $this->request->get['locationids'] != ""){
			$url2 .= '&locationids=' . $this->request->get ['locationids'];
			$this->data ['hidetagsids'] = 1;
		}
		
		if($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != ""){
			$url2 .= '&facilityids=' . $this->request->get ['facilityids'];
			$this->data ['hidetagsids'] = 1;
		}

		if (isset ( $this->request->post ['tagides'] )) {
			$tagides1 = $this->request->post ['tagides'];
		} elseif (! empty ( $this->request->get ['tags_id'] )) {
			$tagides1 = explode ( ',', $this->request->get ['tags_id'] );
		} elseif($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
			$tagides1 = explode ( ',', $this->request->get['tagsids'] );
			$this->data ['tagsids'] = $this->request->get['tagsids'];
			$this->data ['hidetagsids'] = 1;
			
		} else {
			$tagides1 = array ();
		}

		$this->data ['tagides'] = array ();
		$this->load->model ( 'setting/tags' );

		foreach ( $tagides1 as $tagsid ) {

			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
					'tags_id' => $tagsid,
					'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name']
				);
			}
		}
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;



		// var_dump($this->data['monitor_time']);
		// var_dump($this->data['monitortimes']);

		/*
		* if($this->config->get('config_face_recognition') == '1'){
		* $this->data['facerekognition_1'] = '1';
		* }
		*/

		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';

		$this->children = array (
		'common/headerpopup',
		'common/usercamera'
		);

		/*
		* $this->children = array(
		* 'notes/notes/getNoteData'
		* );
		*/

		$this->response->setOutput ( $this->render () );
		}
	protected function validateForm2() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		/*
		 * if($this->config->get('config_face_recognition') == '1'){
		 * if($this->customer->isallowface_without_verified() != '1'){
		 * $this->load->model('notes/facerekognition');
		 * $facematch =
		 * $this->model_notes_facerekognition->getfacerekognitioncompare($this->request->post,
		 * $this->request->get['notes_id']);
		 *
		 * if ($facematch == '2') {
		 * $this->error['warning'] = 'Sorry i am having trouble in recognizing
		 * you. Lets try again!!';
		 * }
		 *
		 * if ($facematch == '3') {
		 * $this->error['warning'] = 'Please contact your admin to enroll your
		 * picture!';
		 * }
		 * }
		 * }
		*/
		
		
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
		
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
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
				
				//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
			}
		}
		
		if ($this->request->post ['override_monitor_time_user_id_checkbox'] == '1') {
			if ($this->request->post ['override_monitor_time_user_id'] == '') {
				$this->error ['override_monitor_time_user_id'] = $this->language->get ( 'error_required' );
			}
		}
		
		if ($this->request->post ['override_monitor_time_user_id'] != null && $this->request->post ['override_monitor_time_user_id'] != '') {
			if ($this->request->post ['override_monitor_time_user_id_checkbox'] == '') {
				$this->error ['override_monitor_time_user_id_checkbox'] = $this->language->get ( 'error_required' );
			}
		}
		
		if ($this->request->get ['notes_id'] > 0) {
			if ($this->request->post ['override_monitor_time_user_id_checkbox'] != '1') {
				$a2 = array ();
				$a2 ['notes_id'] = $this->request->get ['notes_id'];
				$a2 ['facilities_id'] = $this->customer->getId ();
				$active_note_info_actives = $this->model_notes_notes->getNotebyactivenotes ( $a2 );
				
				if ($active_note_info_actives != null && $active_note_info_actives != "") {
					
					foreach ( $active_note_info_actives as $active_note_info ) {
						$this->load->model ( 'setting/keywords' );
						$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info ['keyword_id'] );
						
						// var_dump($keywordData2);
						
						if ($keywordData2 ['end_relation_keyword'] == '1') {
							$a3 = array ();
							$a3 ['keyword_id'] = $keywordData2 ['relation_keyword_id'];
							$a3 ['user_id'] = $this->request->post ['user_id'];
							$a3 ['facilities_id'] = $this->customer->getId ();
							$a3 ['is_monitor_time'] = '1';
							
							$active_note_info2 = $this->model_notes_notes->getNotebyactivenote ( $a3 );
							
							// var_dump($active_note_info2);
							
							if (empty ( $active_note_info2 )) {
								$this->error ['warning'] = 'End ActiveNote does not exit!';
							}
						}
					}
				}
			}
		}
		
		/*
		 * if(($this->request->post['notes_pin'] == null &&
		 * $this->request->post['notes_pin'] == "") &&
		 * ($this->request->post['imgOutput'] == null &&
		 * $this->request->post['imgOutput'] == "")){
		 * $this->error['warning'] = 'Please insert at least one required!';
		 *
		 * }
		 */
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function search() {
		try {
			unset ( $this->session->data ['timeout'] );
			$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/insert', '' . $url, 'SSL' );
			
			$this->data ['error2'] = $this->request->get ['error2'];
			$this->load->model ( 'user/user' );
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
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
			date_default_timezone_set ( $timezone_name );
			
			$data = array (
					'status' => '1',
					'facilities_id' => $facilities_id 
			);
			
			$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
			
			$this->load->model ( 'notes/notes' );
			$this->data ['tagassignotes'] = $this->model_notes_notes->gettagassigns ( $facilities_id );
			
			$this->load->model ( 'setting/highlighter' );
			$this->data ['highlighters'] = $this->model_setting_highlighter->gethighlighters ();
			
			// var_dump($this->data['highlighters']);
			
			$this->data ['note_date_from'] = date ( 'm-d-Y', strtotime ( 'now' ) );
			$this->data ['note_date_to'] = date ( 'm-d-Y', strtotime ( 'now' ) );
			
			$this->load->model ( 'createtask/createtask' );
			$this->data ['tasktypes'] = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
			
			$this->load->model ( 'setting/keywords' );
			
			$data3 = array (
					'facilities_id' => $facilities_id,
					'sort' => 'keyword_name' 
			);
			
			$this->data ['activenotes'] = $this->model_setting_keywords->getkeywords ( $data3 );
			
			// var_dump($this->data['activenotes']);
			
			unset ( $this->session->data ['note_date_search'] );
			unset ( $this->session->data ['note_date_from'] );
			unset ( $this->session->data ['note_date_to'] );
			unset ( $this->session->data ['keyword'] );
			unset ( $this->session->data ['search_user_id'] );
			unset ( $this->session->data ['search_emp_tag_id'] );
			unset ( $this->session->data ['ssincedentform'] );
			unset ( $this->session->data ['highlighter'] );
			unset ( $this->session->data ['activenote'] );
			
			$this->load->model ( 'form/form' );
			$data3 = array ();
			$data3 ['status'] = '1';
			// $data3['order'] = 'sort_order';
			$data3 ['is_parent'] = '1';
			$data3 ['facilities_id'] = $facilities_id;
			
			$custom_forms = $this->model_form_form->getforms ( $data3 );
			
			$this->data ['custom_forms'] = array ();
			foreach ( $custom_forms as $custom_form ) {
				
				$this->data ['custom_forms'] [] = array (
						'forms_id' => $custom_form ['forms_id'],
						'form_name' => $custom_form ['form_name'],
						'form_href' => $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
				);
			}
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_search.php';
			
			$this->children = array (
					'common/headerpopup' 
			);
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Notes Search' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNotessearch', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function getNoteTime() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$timezone_name = $this->customer->isTimezone ();
		
		date_default_timezone_set ( $timezone_name );
		
		$this->data ['note_time'] = date ( 'H:i', strtotime ( 'now' ) );
		
		$this->data ['notetime'] = date ( 'H:i', strtotime ( 'now' ) );
		
		$json = array ();
		
		$json ['number'] = $this->request->get ['number'];
		
		$json ['time'] = $this->data ['notetime'];
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function review() {
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		unset ( $this->session->data ['timeout'] );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {
			
			$add_date = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$add_date = $this->request->get ['searchdate'];
			}
			
			// $timezone_name = $this->customer->isTimezone();
			// var_dump($timezone_name);
			
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
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			
			$timeZone = date_default_timezone_set ( $timezone_name );
			
			$reviewdata = array ();
			
			if ($this->request->get ['fromdate'] != null && $this->request->get ['fromdate'] != "") {
				$reviewdate = $this->request->get ['fromdate'] . ' To ' . date ( 'm-d-Y' );
			}
			
			//$reviewdata ['keyword_file'] = CONFIG_REVIEW_NOTES;
			
			$this->load->model ( 'setting/keywords' );
			
			$keywordData_a = $this->model_setting_keywords->getkeywordDetailbyidreview ( $facilities_id );
			$reviewdata ['keyword_file'] = $keywordData_a['keyword_image'];
			
			
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $reviewdata ['keyword_file'], $facilities_id );
			
			$reviewdata ['notes_description'] = ' | ' . $reviewdate . ' ' . $this->request->post ['comments'];
			
			$reviewdata ['note_date'] = date ( 'Y-m-d H:i:s' );
			$reviewdata ['notetime'] = date ( 'h:i A' );
			
			// $this->model_notes_notes->addreview($this->request->post,
			// $this->customer->getId(),$add_date);
			
			$notes_id = $this->model_notes_notes->addnotes ( $reviewdata, $facilities_id );
			$this->model_notes_notes->updatenotes ( $this->request->post, $facilities_id, $notes_id );
			$this->model_notes_notes->updatereviewnotes ( $notes_id );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url2 = "&reset=1";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get ['searchdate'];
			}
			$this->redirect ( $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->data ['createtask'] = '1';
		$this->data ['fromdate'] = $this->request->get ['fromdate'];
		
		$this->data ['review'] = $this->request->get ['review'];
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
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
		
		if (isset ( $this->request->post ['notes_pin'] )) {
			$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['notes_pin'] = $notes_info ['notes_pin'];
		} else {
			$this->data ['notes_pin'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
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
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} else {
			$this->data ['user_id'] = $this->session->data ['review_user_id'];
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['review'] != null && $this->request->get ['review'] != "") {
			$url2 .= '&review=' . $this->request->get ['review'];
		}
		if ($this->request->get ['fromdate'] != null && $this->request->get ['fromdate'] != "") {
			$url2 .= '&fromdate=' . $this->request->get ['fromdate'];
		}
		if ($this->request->get ['highlighter'] != null && $this->request->get ['highlighter'] != "") {
			$url2 .= '&highlighter=' . $this->request->get ['highlighter'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/review', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function uploadFile() {
		unset ( $this->session->data ['timeout'] );
		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->files ["file"] != null && $this->request->files ["file"] != "") {
			
			
			$imagename = explode ( ".", $this->request->files ["file"] ["name"] );
			$extension = end ( explode ( ".", $this->request->files ["file"] ["name"] ) );
			
			if ($this->request->files ["file"] ["size"] < 42214400) {
				$neextension = strtolower ( $extension );
				// if($neextension != 'mp4' && $neextension != 'mp3' &&
				// $neextension != 'flv' && $neextension != '3gp' &&
				// $neextension != 'wav' && $neextension != 'mkv' &&
				// $neextension != 'avi'){
				
				$notes_file = 'devbolb' . rand () . '.' . $extension;
				$outputFolder = $this->request->files ["file"] ["tmp_name"];
				
				// var_dump($outputFolder);
				// var_dump($outputFolder);
				
				if ($this->config->get ( 'enable_storage' ) == '1') {
					/* AWS */
					// require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->customer->getId () );
				}
				
				if ($this->config->get ( 'enable_storage' ) == '2') {
					/* AZURE */
					
					require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
					// uploadBlobSample($blobClient, $outputFolder,
					// $notes_file);
					$s3file = AZURE_URL . $notes_file;
				}
				
				if ($this->config->get ( 'enable_storage' ) == '3') {
					/* LOCAL */
					$outputFolder = DIR_IMAGE . 'storage/' . $notes_file;
					move_uploaded_file ( $this->request->files ["file"] ["tmp_name"], $outputFolder );
					$s3file = HTTPS_SERVER . 'image/storage/' . $notes_file;
				}
				
				if ($this->request->get ["resize"] == '1') {
					$outputFolder11 = DIR_IMAGE . 'files/' . $notes_file;
					move_uploaded_file ( $this->request->files ["file"] ["tmp_name"], $outputFolder11 );
				}
				if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
					$keyImageSrc = $s3file;
				} else if ($extension == 'doc' || $extension == 'docx') {
					$keyImageSrc = 'sites/view/digitalnotebook/image/ms_word_DOC_icon.png';
				} else if ($extension == 'ppt' || $extension == 'pptx') {
					$keyImageSrc = 'sites/view/digitalnotebook/image/ppt.png';
				} else if ($extension == 'xls' || $extension == 'xlsx') {
					$keyImageSrc = 'sites/view/digitalnotebook/image/excel-icon.png';
				} else if ($extension == 'pdf') {
					$keyImageSrc = 'sites/view/digitalnotebook/image/pdf.png';
				} else {
					$keyImageSrc = 'sites/view/digitalnotebook/image/attachment.png';
				}
				
				$json ['success'] = '1';
				$json ['notes_media_extention'] = $extension;
				$json ['notes_file'] = $s3file;
				$json ['keyImageSrc'] = $keyImageSrc;
				$json ['name'] =  $imagename[0];
				
				/*
				 * }else{
				 * $json['error'] = 'video or audio file not valid!';
				 * }
				 */
			} else {
				$json ['error'] = 'Maximum size file upload!';
			}
		} else {
			$json ['error'] = 'Please select file!';
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function updateStrike() {
		$this->language->load ( 'notes/notes' );
		
		unset ( $this->session->data ['timeout'] );
		
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
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
					date_default_timezone_set ( $timezone_name );
					$facilities_id = $this->session->data ['search_facilities_id'];
				} else {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					$facilities_id = $this->customer->getId ();
				}
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$facilities_id = $this->customer->getId ();
			}
			
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->model_notes_notes->updateStrikeNotes ( $this->request->post, $this->request->get ['notes_id'], $facilities_id, $update_date );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url2 = "";
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
			
			$url2 .= '&updatenotes_id=' . $this->request->get ['notes_id'];
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
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
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/updateStrike', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function updatenote() {
		try {
			$this->load->model ( 'notes/notes' );
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$json = array ();
			if ($this->request->get ['notes_id'] != null && $this->request->get ['type'] == 'text') {
				
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->model_notes_notes->updateNoteColor ( $this->request->get ['notes_id'], $this->request->get ['text_color'], $update_date );
				$json ['success'] = '1';
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['type'] == 'highliter') {
				
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->model_notes_notes->updateNoteHigh ( $this->request->get ['notes_id'], $this->request->get ['highlighter_id'], $update_date );
				$json ['success'] = '1';
			}
			
			$json ['url_load'] = $this->getChild ( 'notes/notes/getNoteData', $this->request->get ['notes_id'] );
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Createtask updatenote' 
			);
			$this->model_activity_activity->addActivity ( 'sitesupdatenote', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function addReminder() {
		$this->load->model ( 'notes/notes' );
		unset ( $this->session->data ['timeout'] );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$data ['notes_id'] = $this->request->get ['notes_id'];
			$data ['reminder_time'] = $this->request->get ['reminder_time'];
			$data ['reminder_title'] = $this->request->post ['reminder_title'];
			$data ['update_date'] = $update_date;
			
			$this->model_notes_notes->addReminderModel ( $data, $this->customer->getId () );
			$json ['success'] = 1;
			$json ['url_load'] = $this->getChild ( 'notes/notes/getNoteData', $this->request->get ['notes_id'] );
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function arrayInString($inArray, $inString) {
		if (is_array ( $inArray )) {
			foreach ( $inArray as $e ) {
				if (strpos ( $inString, $e ) !== false)
					return $e;
			}
			return "";
		} else {
			return (strpos ( $inString, $inArray ) !== false);
		}
	}
	public function jsondeleteReminder() {
		$json = array ();
		unset ( $this->session->data ['timeout'] );
		$this->load->model ( 'notes/notes' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$data ['notes_id'] = $this->request->get ['notes_id'];
			$data ['facilities_id'] = $this->customer->getId ();
			
			$this->model_notes_notes->jsonDeleteReminder ( $data );
			$json ['success'] = 1;
			
			$json ['url_load'] = $this->getChild ( 'notes/notes/getNoteData', $this->request->get ['notes_id'] );
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function openFile() {
		$openfile = HTTP_SERVER . 'image/files/' . $this->request->get ['openfile'];
		
		$extension = strtolower ( end ( explode ( ".", $this->request->get ['openfile'] ) ) );
		
		// var_dump($extension);die;
		
		/*
		 * $file = DIR_IMAGE . '/files/Application_1.doc';
		 * $filename = 'Application_1.doc';
		 * header('Content-type: application/docs');
		 * header('Content-Disposition: inline; filename="' . $filename . '"');
		 * header('Content-Transfer-Encoding: binary');
		 * header('Accept-Ranges: bytes');
		 * @readfile($file);
		 */
		
		if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
			
			$file = DIR_IMAGE . '/files/' . $this->request->get ['openfile'];
			$file1 = HTTP_SERVER . 'image/files/' . $this->request->get ['openfile'];
			$filename = $this->request->get ['openfile'];
			
			header ( 'Content-Disposition: inline; filename="' . $filename . '"' );
			$imageData = base64_encode ( file_get_contents ( $file ) );
			$src = 'data: ' . $this->mime_content_type ( $file1 ) . ';base64,' . $imageData;
			echo '<img src="' . $src . '">';
		} else {
			
			echo '<iframe class="doc" src="https://docs.google.com/gview?url=' . $openfile . '&embedded=true" height="100%" width="100%"></iframe>';
		}
	}
	public function mime_content_type($filename) {
		$mime_types = array (
				
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',
				
				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
				
				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',
				
				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
				
				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',
				
				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
				
				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet' 
		);
		
		$ext = strtolower ( array_pop ( explode ( '.', $filename ) ) );
		if (array_key_exists ( $ext, $mime_types )) {
			return $mime_types [$ext];
		} elseif (function_exists ( 'finfo_open' )) {
			$finfo = finfo_open ( FILEINFO_MIME );
			$mimetype = finfo_file ( $finfo, $filename );
			finfo_close ( $finfo );
			return $mimetype;
		} else {
			return 'application/octet-stream';
		}
	}
	public function updateFile() {
		$this->load->model ( 'notes/notes' );
		$json = array ();
		unset ( $this->session->data ['timeout'] );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->files ["file"] != null && $this->request->files ["file"] != "") {
			
			$extension = end ( explode ( ".", $this->request->files ["file"] ["name"] ) );
			
			if ($this->request->files ["file"] ["size"] < 42214400) {
				$neextension = strtolower ( $extension );
				// if($neextension != 'mp4' && $neextension != 'mp3' &&
				// $neextension != 'flv' && $neextension != '3gp' &&
				// $neextension != 'wav' && $neextension != 'mkv' &&
				// $neextension != 'avi'){
				/*
				 * $notes_file = uniqid( ) . "." . $extension;
				 * $outputFolder = DIR_IMAGE.'files/' . $notes_file;
				 * move_uploaded_file($this->request->files["file"]["tmp_name"],
				 * $outputFolder);
				 */
				$notes_file = 'devbolb' . rand () . '.' . $extension;
				$outputFolder = $this->request->files ["file"] ["tmp_name"];
				
				// require_once(DIR_SYSTEM .
				// 'library/azure_storage/config.php');
				
				// uploadBlobSample($blobClient, $outputFolder, $notes_file);
				
				// move_uploaded_file($this->request->files["file"]["tmp_name"],
				// $outputFolder);
				
				// require_once(DIR_SYSTEM .
				// 'library/awsstorage/s3_config.php');
				
				if ($this->config->get ( 'enable_storage' ) == '1') {
					/* AWS */
					$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->customer->getId () );
					// require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				}
				
				if ($this->config->get ( 'enable_storage' ) == '2') {
					/* AZURE */
					
					require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
					// uploadBlobSample($blobClient, $outputFolder,
					// $notes_file);
					$s3file = AZURE_URL . $notes_file;
				}
				
				if ($this->config->get ( 'enable_storage' ) == '3') {
					/* LOCAL */
					$outputFolder = DIR_IMAGE . 'storage/' . $notes_file;
					move_uploaded_file ( $this->request->files ["file"] ["tmp_name"], $outputFolder );
					$s3file = HTTPS_SERVER . 'image/storage/' . $notes_file;
				}
				
				$this->load->model ( 'facilities/facilities' );
				$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				/*
				 * if($resulsst['is_master_facility'] == '1'){
				 * $facilities_id = $this->session->data['facility'];
				 * }else{
				 * $facilities_id = $this->customer->getId();
				 * }
				 */
				
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
				
				$notes_media_extention = $extension;
				$notes_file_url = $s3file;
				$formData = array ();
				$formData ['media_user_id'] = $this->session->data ['media_user_id'];
				$formData ['media_signature'] = $this->session->data ['media_signature'];
				$formData ['media_pin'] = $this->session->data ['media_pin'];
				$formData ['facilities_id'] = $facilities_id;
				
				date_default_timezone_set ( $timezone_name );
				$formData ['noteDate'] = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				if ($this->session->data ['emp_tag_id'] != null && $this->session->data ['emp_tag_id'] != "") {
					$this->load->model ( 'notes/notes' );
					
					date_default_timezone_set ( $timezone_name );
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$tadata = array();
					$this->model_notes_notes->updateNotesTag ( $this->session->data ['emp_tag_id'], $this->request->get ['notes_id'], $this->session->data ['tags_id'], $update_date,$tadata );
				}
				
				$this->model_notes_notes->updateNoteFile ( $this->request->get ['notes_id'], $notes_file_url, $notes_media_extention, $formData );
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$this->model_notes_notes->updatedate ( $this->request->get ['notes_id'], $update_date );
				
				$json ['success'] = '1';
				
				$json ['url_load'] = $this->getChild ( 'notes/notes/getNoteData', $this->request->get ['notes_id'] );
				
				/*
				 * }else{
				 *
				 * $json['error'] = 'video or audio file not valid!';
				 * }
				 */
			} else {
				
				$json ['error'] = 'Maximum size file upload!';
			}
		} else {
			$json ['error'] = 'Please select file!';
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getReminderTime() {
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/image' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$json = array ();
		
		$notes_id = $this->request->get ['notes_id'];
		$reminder_info = $this->model_notes_notes->getReminder ( $notes_id );
		
		$notes_info = $this->model_notes_notes->getnotes ( $notes_id );
		$user_info = $this->model_user_user->getUser ( $notes_info ['user_id'] );
		if ($reminder_info != null && $reminder_info != "") {
			
			$json ['success'] = '1';
			$json ['reminder_time'] = $reminder_info ['reminder_time'];
			$json ['reminder_title'] = $reminder_info ['reminder_title'];
			
			$json ['notes_pin'] = $notes_info ['reminder_title'];
			
			if ($notes_info ['notes_pin'] != null && $notes_info ['notes_pin'] != "") {
				$json ['notes_pin'] = $notes_info ['notes_pin'];
			} else {
				$json ['notes_pin'] = '';
			}
			
			$json ['note_date'] = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $notes_info ['note_date'] ) );
			$json ['signature'] = $notes_info ['signature']; // $this->model_notes_image->resize('signature/'.$notes_info['signature_image'],
			                                                 // 98, 28);
			$json ['notes_description'] = $notes_info ['notes_description'];
			$json ['username'] = $user_info ['username'];
			
			$time = $reminder_info ['reminder_time']; /* '10:22 PM'; */
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$curtime = date ( 'h:i A' );
			
			if ($curtime == $time) {
				$json ['checkTime'] = '1';
			} else {
				$json ['checkTime'] = '2';
			}
		} else {
			$json ['success'] = '0';
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getReminderPopup() {
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/image' );
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			$data ['notes_id'] = $this->request->post ['notes_id'];
			
			$data ['reminder_time'] = $this->request->post ['hourtrue'] . ':' . $this->request->post ['minutetrue'] . ' ' . $this->request->post ['amPm'];
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$data ['update_date'] = $update_date;
			
			$this->model_notes_notes->updateReminderModel ( $data, $this->customer->getId () );
			
			$this->session->data ['update_reminder'] = '2';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) ) );
		}
		
		$notes_id = $this->request->get ['notes_id'];
		$reminder_info = $this->model_notes_notes->getReminder ( $notes_id );
		
		$notes_info = $this->model_notes_notes->getnotes ( $notes_id );
		$user_info = $this->model_user_user->getUser ( $notes_info ['user_id'] );
		
		$this->data ['notes_id'] = $notes_id;
		
		if ($reminder_info != null && $reminder_info != "") {
			$this->data ['reminder_time'] = $reminder_info ['reminder_time'];
			$this->data ['reminder_title'] = $reminder_info ['reminder_title'];
			
			$this->data ['notes_pin'] = $notes_info ['reminder_title'];
			
			if ($notes_info ['notes_pin'] != null && $notes_info ['notes_pin'] != "") {
				$this->data ['notes_pin'] = $notes_info ['notes_pin'];
			} else {
				$this->data ['notes_pin'] = '';
			}
			
			$this->data ['note_date'] = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $notes_info ['note_date'] ) );
			$this->data ['signature'] = $notes_info ['signature']; // $this->model_notes_image->resize('signature/'.$notes_info['signature_image'],
			                                                       // 98, 28);
			$this->data ['notes_description'] = $notes_info ['notes_description'];
			$this->data ['username'] = $user_info ['username'];
			
			$this->data ['hour'] = date ( 'h', strtotime ( $reminder_info ['reminder_time'] ) );
			
			$this->data ['minutes'] = date ( 'i', strtotime ( $reminder_info ['reminder_time'] ) );
			
			$this->data ['sedule'] = date ( 'A', strtotime ( $reminder_info ['reminder_time'] ) );
			
			$time = date ( 'h:i A', $reminder_info ['reminder_time'] );
			
			$this->data ['checkTime'] = $time;
			// date_default_timezone_set($this->session->data['time_zone_1']);
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
		}
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 = '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		$this->data ['action'] = $this->url->link ( 'notes/notes/getReminderPopup', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/set_alarm.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function getTime() {
		$json = array ();
		unset ( $this->session->data ['timeout'] );
		// date_default_timezone_set($this->session->data['time_zone_1']);
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		$json ['notetime'] = date ( 'h:i A' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $facilities_id;
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$noteTime = date ( 'H:i:s' );
			
			$date = str_replace ( '-', '/', $this->request->get ['searchdate'] );
			$res = explode ( "/", $date );
			$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
			
			$json ['note_date'] = $changedDate . ' ' . $noteTime;
			$searchdate = $this->request->get ['searchdate'];
			
			if (($searchdate) >= (date ( 'm-d-Y' ))) {
				$json ['back_date_check'] = "1";
			} else {
				$json ['back_date_check'] = "2";
			}
		} else {
			$json ['note_date'] = date ( 'Y-m-d H:i:s' );
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getTimeout() {
		unset ( $this->session->data ['timeout'] );
		$json = array ();
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$noteTime = date ( 'H:i:s' );
			
			$date = str_replace ( '-', '/', $this->request->get ['searchdate'] );
			$res = explode ( "/", $date );
			$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
			
			$json ['note_date'] = $changedDate . ' ' . $noteTime;
			$searchdate = $this->request->get ['searchdate'];
			
			if (($searchdate) >= (date ( 'm-d-Y' ))) {
				$json ['back_date_check'] = "1";
			} else {
				$json ['back_date_check'] = "2";
			}
		} else {
			$json ['note_date'] = date ( 'Y-m-d H:i:s' );
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function searchUser() {
		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		// if($this->request->get['user_id'] != null &&
		// $this->request->get['user_id'] != "") {
		$this->load->model ( 'user/user' );
		
		if (isset ( $this->request->get ['user_id'] )) {
			$user_id = $this->request->get ['user_id'];
		} else {
			$user_id = '';
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			// $facilities_id = $this->customer->getId();
			// var_dump($this->customer->getId());
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
		}
		
		// var_dump($facilities_id);
		
		$data = array (
				'user_id' => $user_id,
				'facilities_id' => $facilities_id,
				'allusers' => $this->request->get ['allusers'],
				'user_group_id' => $this->request->get ['user_group_id'],
				'start' => 0,
				'limit' => $limit 
		);
		
		$users = $this->model_user_user->getUsersByFacilityUser ( $data );
		
		foreach ( $users as $user ) {
			$json [] = array (
					'username' => $user ['username'],
					'user_id' => $user ['user_id'],
					'email' => $user ['email'] 
			);
		}
		// }
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function displayFile() {
		if (isset ( $this->request->get ['notes_media_id'] )) {
			$notes_media_id = $this->request->get ['notes_media_id'];
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->model ( 'notes/notes' );
		$image_info = $this->model_notes_notes->getImage ( $notes_media_id );
		
		if ($image_info != null && $image_info != "") {
			$this->data ['notes_file'] = $image_info ['notes_file'];
			$this->data ['audio_attach_url'] = $image_info ['audio_attach_url'];
			$this->data ['notes_media_extention'] = $image_info ['notes_media_extention'];
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/display_media.php';
		
		$this->response->setOutput ( $this->render () );
	}
	public function attachmentSign() {
		$this->language->load ( 'notes/notes' );
		
		unset ( $this->session->data ['timeout'] );
		
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
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {
			
			$this->session->data ['media_user_id'] = $this->request->post ['user_id'];
			$this->session->data ['media_signature'] = $this->request->post ['imgOutput'];
			$this->session->data ['media_pin'] = $this->request->post ['notes_pin'];
			$this->session->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
			$this->session->data ['tags_id'] = $this->request->post ['tags_id'];
			// $this->session->data['facility'] = $this->request->post['facility'];
			
			/*
			 * $this->data['media_user_id'] = $this->request->post['user_id'];
			 * $this->data['media_signature'] =
			 * $this->request->post['imgOutput'];
			 * $this->data['media_pin'] = $this->request->post['notes_pin'];
			 */
			$this->session->data ['success_attachment'] = 'a';
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		if (isset ( $this->session->data ['success_attachment'] )) {
			$this->data ['success_attachment'] = $this->session->data ['success_attachment'];
			
			unset ( $this->session->data ['success_attachment'] );
		} else {
			$this->data ['success_attachment'] = '';
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
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['emp_tag_id'] = $notes_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['tags_id'] = $notes_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function searchTags() {
		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		// if($this->request->get['emp_tag_id'] != null &&
		// $this->request->get['emp_tag_id'] != "") {
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		
		if (isset ( $this->request->get ['emp_tag_id'] )) {
			$emp_tag_id = $this->request->get ['emp_tag_id'];
		} else {
			$emp_tag_id = '';
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		$filter_name = explode ( ':', $emp_tag_id );
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
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
			// $facilities_id = $this->customer->getId();
		}
		
		$data = array (
				'emp_tag_id_all' => trim ( $filter_name [0] ),
				'facilities_id' => $facilities_id,
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				'is_master' => 1,
				'sort' => 'emp_last_name',
				'order' => 'ASC',
				'start' => 0,
				'limit' => $limit 
		);
		
		
		$tags = $this->model_setting_tags->getTags ( $data );
		
		$this->load->model('setting/locations');       
		$this->load->model('resident/resident');       
		$this->load->model('notes/clientstatus');       
		
		foreach ( $tags as $result ) {
			
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
			
			if ($result ['dob'] != "0000-00-00") {
				$dobm = date ( 'm', strtotime ( $result ['dob'] ) );
			} else {
				$dobm = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$dobd = date ( 'd', strtotime ( $result ['dob'] ) );
			} else {
				$dobd = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$doby = date ( 'Y', strtotime ( $result ['dob'] ) );
			} else {
				$doby = '';
			}
			
			/*
			 * if ($result['gender'] == '1') {
			 * $gender = '33';
			 * }
			 * if ($result['gender'] == '2') {
			 * $gender = '34';
			 * }
			 */
			
			$get_img = $this->model_setting_tags->getImage($result['tags_id']);			
			
			if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $get_img['enroll_image'];
			}
			
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
			
			$json [] = array (
					'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
					'fullname' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
					'tags_id' => $result ['tags_id'],
					'classification_name' => $classification_name,
					'role_call' => $role_callname,
					'location_name' => $location_name,
					'emp_tag_id2' => $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'],
					'emp_tag_id' => $result ['emp_tag_id'],
					'emp_first_name' => $result ['emp_first_name'],
					'emp_middle_name' => $result ['emp_middle_name'],
					'emp_last_name' => $result ['emp_last_name'],
					'location_address' => $result ['location_address'],
					'discharge' => $result ['discharge'],
					'ccn' => $result ['ccn'],
					'age' => $result['age'],
					'dob' => $dob,
					'month' => $dobm,
					'date' => $dobd,
					'year' => $doby,
					'medication' => $result ['medication'],
					// 'gender'=> $result['gender'],
					'gender' => $result ['customlistvalues_id'],
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
					'stickynote' => $result ['stickynote'],
					'referred_facility' => $result ['referred_facility'],
					'emergency_contact' => $result ['emergency_contact'],
					'upload_file' => $upload_file_thumb_1,
					'image_url1' => $upload_file_thumb_1,
					'screening_update_url' => $action211 
			);
		}
		// }
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function unlockUser() {
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
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2Lock ()) {
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->session->data ['unloack_success'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get ['searchdate'];
			}
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$url2 = "";
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		/*
		 * $config_admin_limit1 = $this->config->get('config_front_limit');
		 * if($config_admin_limit1 != null && $config_admin_limit1 != ""){
		 * $config_admin_limit = $config_admin_limit1;
		 * }else{
		 * $config_admin_limit = "50";
		 * }
		 *
		 * $timezone_name = $this->customer->isTimezone();
		 * date_default_timezone_set($timezone_name);
		 *
		 * $data = array(
		 * 'searchdate' => date('m-d-Y'),
		 * 'searchdate_app' => '1',
		 * 'facilities_id' => $this->customer->getId(),
		 * );
		 *
		 * $this->load->model('notes/notes');
		 * $notes_total = $this->model_notes_notes->getTotalnotess($data);
		 * $pagenumber_all = ceil($notes_total/$config_admin_limit);
		 *
		 * if ($pagenumber_all != null && $pagenumber_all != "") {
		 * if($pagenumber_all > 1){
		 * $url2 .= '&page=' . $pagenumber_all;
		 * }
		 * }
		 */
		
		if ($this->request->get ['client'] != null && $this->request->get ['client'] != "") {
			$url2 .= '&client=' . $this->request->get ['client'];
		}
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$this->data ['facilities_id_url'] = '&facilities_id=' . $this->request->get ['facilities_id'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/unlockUser', '' . $url2, 'SSL' );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
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
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		
		if (isset ( $this->request->post ['notes_pin'] )) {
			$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['notes_pin'] = $notes_info ['notes_pin'];
		} else {
			$this->data ['notes_pin'] = '';
		}
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_userlock.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm2Lock() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		/*if ($this->request->post ['user_id'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}*/
		
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
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
			
			if ($this->request->get ['client'] == "1") {
				
				if (! empty ( $user_info ['user_group_id'] )) {
					$this->load->model ( 'user/user_group' );
					$user_group_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					if (! empty ( $user_group_info )) {
						$this->session->data ['show_hidden_info'] = $user_group_info ['show_hidden_info'];
					}
				}
			}
		}
		
		if ($this->request->post ['notes_pin'] == '') {
			$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
		}
		if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
			$this->load->model ( 'user/user' );
			
			//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
				$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
			}else{
				$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
			}
			
			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
				$this->error ['notes_pin'] = $this->language->get ( 'error_exists' );
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	protected function validateForm2Review() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['reviewed_by'] != '3') {
			
			/*if ($this->request->post ['user_id'] == '') {
				$this->error ['user_id'] = $this->language->get ( 'error_required' );
			}*/
			
			if ($this->request->post ['username'] == '') {
				$this->error ['user_id'] = $this->language->get ( 'error_required' );
			}
			
			if ($this->request->post ['username'] != '') {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
				if (empty ( $user_info )) {
					$this->error ['user_id'] = "Enter a valid user.";
				}
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
		
		if ($this->request->post ['reviewed_by'] == '3') {
			if ($this->request->post ['date_from'] == '') {
				$this->error ['date_from'] = $this->language->get ( 'error_required' );
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function reviewNotes() {
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
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2Review ()) {
			
			$timezone_name = $this->customer->isTimezone ();
			$notes_info = $this->model_notes_notes->getnotesbyUser ( $this->request->post, $this->customer->getId (), $timezone_name );
			// $this->session->data['success'] =
			// $this->language->get('text_success');
			
			$this->session->data ['review_success'] = '1';
			
			if ($this->request->post ['reviewed_by'] != "3") {
				$this->session->data ['review_user_id'] = $this->request->post ['user_id'];
			}
			$url3 = '';
			if ($this->request->post ['highlighter'] != null && $this->request->post ['highlighter'] != "") {
				$url3 .= '&highlighter=' . $this->request->post ['highlighter'];
			}
			
			if ($this->request->post['activenote'] != "") {
				$url3 .= '&activenote=' . $this->request->post['activenote'];
			}
			
			
			/*
			 * if($notes_info != null && $notes_info != "" ){
			 * $notes_info1 = $notes_info;
			 * if ($this->request->post['activenote'] != null &&
			 * $this->request->post['activenote'] != "") {
			 * //$url3 .= '&activenote=' . $this->request->post['activenote'];
			 * }
			 * }else{
			 *
			 * $notes_info3 =
			 * $this->model_notes_notes->getnotesbyUser($this->request->post['user_id'],
			 * $this->customer->getId(), '');
			 *
			 * //var_dump($notes_info3);
			 * $notes_info1 = $notes_info3;
			 * }
			 */
			
			if ($notes_info ['date_added'] != null && $notes_info ['date_added'] != "") {
				$date_added = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
			} else if($this->request->post['date_from'] != null && $this->request->post['date_from'] != "") {
				$date = str_replace ( '-', '/', $this->request->post['date_from'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$date_added = $changedDate;
			}else{
				$date_added = date ( 'm-d-Y' );
			}
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . '&review=1&fromdate=' . $date_added . $url3, 'SSL' ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$url2 = "";
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/reviewNotes', '' . $url2, 'SSL' );
		
		$this->load->model ( 'setting/highlighter' );
		$this->data ['highlighters'] = $this->model_setting_highlighter->gethighlighters ();
		
		$data3 = array (
				'facilities_id' => $this->customer->getId (),
				'sort' => 'keyword_name' 
		);
		
		$this->load->model ( 'setting/keywords' );
		$this->data ['activenotes'] = $this->model_setting_keywords->getkeywords ( $data3 );
		
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
		if (isset ( $this->session->data ['review_success'] )) {
			$this->data ['review_success'] = $this->session->data ['review_success'];
			
			unset ( $this->session->data ['review_success'] );
		} else {
			$this->data ['review_success'] = '';
		}
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		
		if (isset ( $this->error ['date_from'] )) {
			$this->data ['error_date_from'] = $this->error ['date_from'];
		} else {
			$this->data ['error_date_from'] = '';
		}
		
		if (isset ( $this->request->post ['reviewed_by'] )) {
			$this->data ['reviewed_by'] = $this->request->post ['reviewed_by'];
		} else {
			$this->data ['reviewed_by'] = '';
		}
		
		if (isset ( $this->request->post ['date_from'] )) {
			$this->data ['date_from'] = $this->request->post ['date_from'];
		} else {
			$this->data ['date_from'] = '';
		}
		
		if (isset ( $this->request->post ['notes_pin'] )) {
			$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
		} else {
			$this->data ['notes_pin'] = '';
		}
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		if (isset ( $this->request->post ['highlighter'] )) {
			$this->data ['highlighter'] = $this->request->post ['highlighter'];
		} else {
			$this->data ['highlighter'] = '';
		}
		
		if (isset ( $this->request->post ['activenote'] )) {
			$this->data ['activenote'] = $this->request->post ['activenote'];
		} else {
			$this->data ['activenote'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/review_notes.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateFormp2() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['user_id'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		/*
		 * if ($this->request->post['emp_tag_id'] == '') {
		 * $this->error['warning'] = $this->language->get('error_required');
		 * }
		 */
		
		if (empty ( $this->request->post ['tagides'] )) {
			$this->error ['warning'] = $this->language->get ( 'error_required' );
		}
		
		if (! empty ( $this->request->post ['tagides'] )) {
			$this->load->model ( 'setting/tags' );
			$tagsname = "";
			foreach ( $this->request->post ['tagides'] as $tagid ) {
				$stag_info = $this->model_setting_tags->getTagsbyNotesIDTagsrow ( $tagid, $this->request->get ['notes_id'] );
				
				if (! empty ( $stag_info )) {
					$tagsname .= $stag_info ['emp_tag_id'] . ' | ';
				}
			}
			
			if (! empty ( $tagsname )) {
				$this->error ['warning'] = $tagsname . " already added in this notes ";
			}
		}
		
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
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
				
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}
				//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
			}
		}
		
		/*
		 * if(($this->request->post['notes_pin'] == null &&
		 * $this->request->post['notes_pin'] == "") &&
		 * ($this->request->post['imgOutput'] == null &&
		 * $this->request->post['imgOutput'] == "")){
		 * $this->error['warning'] = 'Please insert at least one required!';
		 *
		 * }
		 */
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function updateTags() {
		$this->language->load ( 'notes/notes' );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateFormp2 ()) {
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$timezone_name = $this->customer->isTimezone ();
				
				$formData = array ();
				$formData ['user_id'] = $this->request->post ['user_id'];
				$formData ['imgOutput'] = $this->request->post ['imgOutput'];
				$formData ['notes_pin'] = $this->request->post ['notes_pin'];
				$formData ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->load->model ( 'setting/tags' );
				
				$atagsids = array();
				
				$this->load->model ( 'notes/notes' );
				$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
				$notefacilities_id = $notes_info ['facilities_id'];
				
				
				if($this->request->post['tagides'] != null && $this->request->post['tagides'] != ""){
					$this->load->model('setting/tags');
					//$sssssddsd = explode(",",$this->request->post['tagides']);
					$abdca = array_unique($this->request->post['tagides']);
					foreach($abdca as $tagsid){
						$tag_info = $this->model_setting_tags->getTag($tagsid);
						$empfirst_name = '|'.$tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
						$notes_description = str_ireplace($empfirst_name,"", $notes_description);
						
						$empfirst_name = '| '.$tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
						$notes_description = str_ireplace($empfirst_name,"", $notes_description);
						/*$atagsids[] = array(
							'tags_id'=>$tagsid,
							'facilities_id'=>$tag_info['facilities_id'],
						);*/
						
						$aids[$tag_info['facilities_id']]['clients'][] = array (
							'valueId' => $tagsid,
						);
					}
				}
				
				
				
				
				if(!empty($aids)){
					foreach($aids as $facilities_id =>$aid){
						//var_dump($facilities_id);
						
						if($aid['clients'] != null && $aid['clients'] != ""){
							$tags_id_list = array();
							
							if($notefacilities_id == $facilities_id){
								foreach($aid['clients'] as $clid){
									$tag_info = $this->model_setting_tags->getTag ( $clid['valueId'] );
									if (! empty ( $tag_info )) {
										$formData ['tags_id'] = $tag_info ['tags_id'];
										$formData ['emp_tag_id'] = $tag_info ['emp_tag_id'];
										//var_dump($formData);
										//echo "<hr>";
										$this->model_notes_notes->updatenotesTags ( $formData, $this->request->get ['notes_id'], $timezone_name );
									}
								}									
							}else{
								foreach($aid['clients'] as $clid){
									$tags_id_list[] = $clid['valueId'];
								}
								
								$data['notes_pin'] = $this->request->post['notes_pin'];
								$data['user_id'] = $this->request->post['user_id'];
								$data['imgOutput'] = $this->request->post ['imgOutput'];
								
								if ($notes_info ['emp_tag_id'] == '1') {
									$data['notes_description'] = ' ';
								}else{
									$data['notes_description'] = $notes_info['notes_description'];
								}
								
        
								$data['date_added'] = $notes_info ['date_added'];
								$data['note_date'] = $notes_info ['note_date'];
								$data['notetime'] = $notes_info ['notetime'];
								$data['tags_id_list1'] = $tags_id_list;
								
								$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
								
								if ($notes_info ['notes_file'] == '1') {
									$allimages = $this->model_notes_notes->getImages ($notes_info['notes_id']);
									
									foreach ( $allimages as $image ) {
										$notes_media_extention = $image['notes_media_extention'];
										$notes_file_url = $image['notes_file'];
										$formData = array ();
										$formData ['media_user_id'] = $image ['media_user_id'];
										$formData ['media_signature'] = $image ['media_signature'];
										$formData ['media_pin'] = $image ['media_pin'];
										$formData ['facilities_id'] = $facilities_id;
										$formData ['noteDate'] = $image ['media_date_added'];

										$this->model_notes_notes->updateNoteFile ( $notes_id, $notes_file_url, $notes_media_extention, $formData );
									}
								}
								if ($notes_info ['keyword_file'] == '1') {
									$allkeywords = $this->model_notes_notes->getnoteskeywors ( $notes_info ['notes_id'] );
									
									foreach ( $allkeywords as $keyword ) {
										$data3 = array();
										$data3['keyword_file'] = $keyword['keyword_file'];
										$data3['notes_description'] = $notes_info['notes_description'];
										$this->model_notes_notes->addactiveNote($data3, $notes_id);
									}
								}
								
								
								if ($notes_info ['is_forms'] == '1') {
									$allforms = $this->model_notes_notes->getforms ( $notes_info ['notes_id'] );
						
									foreach ( $allforms as $allform ) {
										
										$data23 = array();
										$data23['notes_id'] = $notes_id;
										$data23['facilities_id'] = $facilities_id;
										$this->load->model('form/form');
										$formreturn_id = $this->model_form_form->addformexisting($allform, $data23);	
										
										$slq12pp = "UPDATE " . DB_PREFIX . "forms SET parent_id = '".$notes_id."' where forms_id = '".$formreturn_id."'";
										$this->db->query($slq12pp);
										
										$slq12pp = "UPDATE " . DB_PREFIX . "notes SET is_forms = '1' where notes_id = '".$notes_id."'";
										$this->db->query($slq12pp);

									}
								}
								
								
							}
								
						}
					}
				
				}
				
				
				/*foreach ( $this->request->post ['tagides'] as $tagid ) {
					$tag_info = $this->model_setting_tags->getTag ( $tagid );
					if (! empty ( $tag_info )) {
						$formData ['tags_id'] = $tag_info ['tags_id'];
						$formData ['emp_tag_id'] = $tag_info ['emp_tag_id'];
						// var_dump($formData);
						// echo "<hr>";
						$this->model_notes_notes->updatenotesTags ( $formData, $this->request->get ['notes_id'], $timezone_name );
					}
				}*/
				
				$this->session->data ['success'] = $this->language->get ( 'text_success' );
			} else {
				$this->error ['warning'] = "You can not add client new note";
			}
			
			unset ( $this->session->data ['notesdatas'] );
			unset ( $this->session->data ['highlighter_id'] );
			unset ( $this->session->data ['notes_id'] );
			unset ( $this->session->data ['text_color_cut'] );
			unset ( $this->session->data ['text_color'] );
			unset ( $this->session->data ['note_date'] );
			unset ( $this->session->data ['notes_file'] );
			unset ( $this->session->data ['update_reminder'] );
			
			unset ( $this->session->data ['ssincedentform'] );
			
			unset ( $this->session->data ['keyword_file'] );
			// unset($this->session->data['pagenumber']);
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			}
			
			/*
			 * if ($this->session->data['pagenumber'] != null &&
			 * $this->session->data['pagenumber'] != "") {
			 * $url2. = '&page=' . $this->session->data['pagenumber'];
			 * }
			 */
			
			// $this->redirect(str_replace('&amp;', '&',
			// $this->url->link('notes/notes/insert', '' . $url2, 'SSL')));
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$url2 = "";
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
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
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/updateTags', '' . $url2, 'SSL' );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
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
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		}else {
			$this->data ['user_id'] = '';
		}
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['emp_tag_id'] = $notes_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['tags_id'] = $notes_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['tagides'] )) {
			$tagides1 = $this->request->post ['tagides'];
		} else if (isset ( $this->request->get ['tags_ids'] )) {
			$tagides1 = explode(',',$this->request->get ['tags_ids']);
		} else {
			$tagides1 = array ();
		}
		
		$this->data ['tagides'] = array ();
		$this->load->model ( 'setting/tags' );
		
		foreach ( $tagides1 as $tagsid ) {
			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
						'tags_id' => $tagsid,
						'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'] 
				);
			}
		}
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['url_load'] = $this->getChild('notes/notes/getNoteData',
		// $url2);
		
		// $this->data['notes_id'] = $this->request->get['notes_id'];
		if ($this->request->get ['newnotes'] != '1') {
			$this->data ['updatenotes_id'] = $notes_id;
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function updateStrikeprivate() {
		$this->language->load ( 'notes/notes' );
		
		unset ( $this->session->data ['timeout'] );
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->model_notes_notes->updateStrikeNotesPrivate ( $this->request->post, $this->request->get ['notes_id'], $this->customer->getId (), $update_date );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url2 = "";
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
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) ) );
		}
	}
	public function generatePdf() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$noteurl = $this->url->link ( 'form/form/printintakeform', '' . $url, 'SSL' );
		$printnoteurl = $this->url->link ( 'form/form/printform', '' . $url, 'SSL' );
		$firedrillnoteurl = $this->url->link ( 'form/form/printmonthly_firredrill', '' . $url, 'SSL' );
		$incidentnoteurl = $this->url->link ( 'form/form/printincidentform', '' . $url, 'SSL' );
		$innoteurl = $this->url->link ( 'form/form/printintakeform', '' . $url, 'SSL' );
		
		$this->language->load ( 'notes/notes' );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'notes/image' );
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'notes/tags' );
		
		$this->document->setTitle ( 'Generate PDF' );
		
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'facilities/facilities' );
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$facilities_id = $this->request->get ['searchdate'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		$this->data ['reports'] = array ();
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$searchdate = $this->request->get ['searchdate'];
			;
		} else {
			$searchdate = date ( 'm-d-Y', strtotime ( 'now' ) );
		}
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$notes_id = $this->request->get ['notes_id'];
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getnotes ( $notes_id );
			$notesid = $notes_info ['parent_id'];
		} else {
			$notesid = '';
		}
		
		$data = array (
				'sort' => $sort,
				'searchdate' => $searchdate,
				'notesid' => $notesid,
				'searchdate_app' => '1',
				'facilities_id' => $facilities_id,
				'is_bedchk' => $this->request->get ['is_bedchk'],
				'customer_key' => $this->session->data['webcustomer_key'],
				'order' => $order,
				'start' => ($page - 1) * 200,
				'limit' => 200 
		);
		
		$this->load->model ( 'notes/notes' );
		$results = $this->model_notes_notes->getnotess ( $data );
		
		$journals = array ();
		
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/keywords' );
		
		$keywords = $this->model_setting_keywords->getkeywords ();
		
		$keyarray = array ();
		foreach ( $keywords as $keyword ) {
			$keyarray [] = $keyword ['keyword_name'];
		}
		$this->load->model ( 'setting/image' );
		
		$j = 0;
		foreach ( $results as $result ) {
			
			$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
			
			$reminder_info = $this->model_notes_notes->getReminder ( $result ['notes_id'] );
			
			$allimages = $this->model_notes_notes->getImages ( $result ['notes_id'] );
			$images = array ();
			foreach ( $allimages as $image ) {
				
				$extension = $image ['notes_media_extention'];
				if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
				} else if ($extension == 'doc' || $extension == 'docx') {
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
				} else if ($extension == 'ppt' || $extension == 'pptx') {
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
				} else if ($extension == 'xls' || $extension == 'xlsx') {
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
				} else if ($extension == 'pdf') {
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
				} else {
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
				}
				
				$images [] = array (
						'keyImageSrc' => $keyImageSrc, // '<img
						                               // src="sites/view/digitalnotebook/image/attachment.png"
						                               // width="35px"
						                               // height="35px" alt=""
						                               // style="margin-left:
						                               // 4px;" />',
						'media_user_id' => $image ['media_user_id'],
						'notes_type' => $image ['notes_type'],
						'notes_file' => $image ['notes_file'],
						'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
						'media_signature' => $image ['media_signature'],
						'media_pin' => $image ['media_pin'],
						'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
				);
			}
			
			$reminder_time = $reminder_info ['reminder_time'];
			$reminder_title = $reminder_info ['reminder_title'];
			
			if ($result ['keyword_file'] != null && $result ['keyword_file'] != "") {
				$keyImageSrc1 = '<img src="' . $result ['keyword_file_url'] . '" wisth="35px" height="35px">';
			} else {
				$keyImageSrc1 = "";
			}
			
			if ($result ['notes_pin'] != null && $result ['notes_pin'] != "") {
				$userPin = $result ['notes_pin'];
			} else {
				$userPin = '';
			}
			
			if ($result ['task_time'] != null && $result ['task_time'] != "00:00:00") {
				$task_time = date ( 'h:i A', strtotime ( $result ['task_time'] ) );
			} else {
				$task_time = "";
			}
			
			if ($config_tag_status == '1') {
				
				$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
				
				if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
					$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
					$privacy = $tagdata ['privacy'];
					
					if ($tagdata ['privacy'] == '2') {
						if ($this->session->data ['unloack_success'] != '1') {
							$emp_tag_id = $alltag ['emp_tag_id'] . ':' . $tagdata ['emp_first_name'];
						} else {
							$emp_tag_id = '';
						}
					} else {
						$emp_tag_id = '';
					}
				} else {
					$emp_tag_id = '';
					$privacy = '';
				}
			}
			
			$allkeywords = $this->model_notes_notes->getnoteskeywors ( $result ['notes_id'] );
			$noteskeywords = array ();
			
			if ($privacy == '2') {
				if ($this->session->data ['unloack_success'] == '1') {
					// $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id
					// . $result['notes_description'];
					
					if ($allkeywords) {
						$keyImageSrc12 = array ();
						$keyname = array ();
						$keyImageSrc11 = "";
						foreach ( $allkeywords as $keyword ) {
							$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
							// $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
							// $keyword['keyword_name'];
							// $keyname[] = $keyword['keyword_name'];
							// $keyname = array_unique($keyname);
							$noteskeywords [] = array (
									'keyword_file_url' => $keyword ['keyword_file_url'] 
							);
						}
						
						// $keyword_description = str_replace($keyname,
						// $keyImageSrc12, $result['notes_description']);
						// $keyword_description =
						// $keyImageSrc11.'&nbsp;'.$result['notes_description'];
						$keyword_description = $result ['notes_description'];
						
						$notes_description = $emp_tag_id . $keyword_description;
					} else {
						$notes_description = $emp_tag_id . $result ['notes_description'];
					}
				} else {
					$notes_description = $emp_tag_id;
				}
			} else {
				// $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id .
				// $result['notes_description'];
				
				if ($allkeywords) {
					$keyImageSrc12 = array ();
					$keyname = array ();
					$keyImageSrc11 = "";
					foreach ( $allkeywords as $keyword ) {
						
						$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" wisth="35px" height="35px">';
						// $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
						// $keyword['keyword_name'];
						// $keyname[] = $keyword['keyword_name'];
						// $keyname = array_unique($keyname);
						
						$noteskeywords [] = array (
								'keyword_file_url' => $keyword ['keyword_file_url'] 
						);
					}
					
					// $keyword_description = str_replace($keyname,
					// $keyImageSrc12, $result['notes_description']);
					// $keyword_description =
					// $keyImageSrc11.'&nbsp;'.$result['notes_description'];
					$keyword_description = $result ['notes_description'];
					
					$notes_description = $emp_tag_id . $keyword_description;
				} else {
					$notes_description = $emp_tag_id . $result ['notes_description'];
				}
			}
			
			$allforms = $this->model_notes_notes->getforms ( $result ['notes_id'] );
			$forms = array ();
			foreach ( $allforms as $allform ) {
				
				$forms [] = array (
						'form_type_id' => $allform ['form_type_id'],
						'forms_id' => $allform ['forms_id'],
						'design_forms' => $allform ['design_forms'],
						'custom_form_type' => $allform ['custom_form_type'],
						'notes_id' => $allform ['notes_id'],
						'form_type' => $allform ['form_type'],
						'notes_type' => $allform ['notes_type'],
						'user_id' => $allform ['user_id'],
						'signature' => $allform ['signature'],
						'notes_pin' => $allform ['notes_pin'],
						'incident_number' => $allform ['incident_number'],
						'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ) 
				);
			}
			
			$notestasks = array ();
			if ($result ['task_type'] == '1') {
				$alltasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '1' );
				
				$boytotal = 0;
				$girltotal = 0;
				$generaltotal = 0;
				$residencetotal = 0;
				foreach ( $alltasks as $alltask ) {
					
					if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
						$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
					} else {
						$media_url = "";
					}
					
					$notestasks [] = array (
							'notes_by_task_id' => $alltask ['notes_by_task_id'],
							'locations_id' => $alltask ['locations_id'],
							'task_type' => $alltask ['task_type'],
							'task_content' => $alltask ['task_content'],
							'user_id' => $alltask ['user_id'],
							'signature' => $alltask ['signature'],
							'notes_pin' => $alltask ['notes_pin'],
							'task_time' => $alltask ['task_time'],
							// 'media_url' => $alltask['media_url'],
							'media_url' => $media_url,
							'capacity' => $alltask ['capacity'],
							'location_name' => $alltask ['location_name'],
							'location_type' => $alltask ['location_type'],
							'notes_task_type' => $alltask ['notes_task_type'],
							'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ) 
					);
					
					if ($alltask ['location_type'] == 'Boys') {
						$boytotal = $boytotal + $alltask ['capacity'];
					}
					
					if ($alltask ['location_type'] == 'Girls') {
						$girltotal = $girltotal + $alltask ['capacity'];
					}
					
					if ($alltask ['location_type'] == 'Inmates') {
						$generaltotal = $generaltotal + $alltask ['capacity'];
					}
				}
				
				$residencetotal = $boytotal + $girltotal + $generaltotal;
				
				$boytotals = array ();
				if ($boytotal > 0) {
					$boytotals [] = array (
							'total' => $boytotal,
							'loc_name' => 'Boys' 
					);
				}
				
				$girltotals = array ();
				if ($girltotal > 0) {
					$girltotals [] = array (
							'total' => $girltotal,
							'loc_name' => 'Girls' 
					);
				}
				
				$generaltotals = array ();
				if ($generaltotal > 0) {
					$generaltotals [] = array (
							'total' => $generaltotal,
							'loc_name' => 'Inmates' 
					);
				}
				
				$residentstotals = array ();
				if ($residencetotal > 0) {
					$residentstotals [] = array (
							'total' => $residencetotal,
							'loc_name' => 'Count' 
					);
				}
			}
			
			$notesmedicationtasks = array ();
			if ($result ['task_type'] == '2') {
				$alltmasks = $this->model_notes_notes->getnotesBytasks ( $result ['notes_id'], '2' );
				
				foreach ( $alltmasks as $alltmask ) {
					
					if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
						$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
					}
					
					if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
						$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
					} else {
						$media_url = "";
					}
					
					$notesmedicationtasks [] = array (
							'notes_by_task_id' => $alltmask ['notes_by_task_id'],
							'locations_id' => $alltmask ['locations_id'],
							'task_type' => $alltmask ['task_type'],
							'task_content' => $alltmask ['task_content'],
							'user_id' => $alltmask ['user_id'],
							'signature' => $alltmask ['signature'],
							'notes_pin' => $alltmask ['notes_pin'],
							'task_time' => $taskTime,
							// 'media_url' => $alltmask['media_url'],
							'media_url' => $media_url,
							'capacity' => $alltmask ['capacity'],
							'location_name' => $alltmask ['location_name'],
							'location_type' => $alltmask ['location_type'],
							'notes_task_type' => $alltmask ['notes_task_type'],
							'tags_id' => $alltmask ['tags_id'],
							'drug_name' => $alltmask ['drug_name'],
							'dose' => $alltmask ['dose'],
							'drug_type' => $alltmask ['drug_type'],
							'quantity' => $alltmask ['quantity'],
							'frequency' => $alltmask ['frequency'],
							'instructions' => $alltmask ['instructions'],
							'count' => $alltmask ['count'],
							'refuse' => $alltmask ['refuse'],
							'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
							'task_comments' => $alltmask ['task_comments'],
							'medication_file_upload' => $alltmask ['medication_file_upload'],
							'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
					);
				}
			}
			
			$reminder_info = $this->model_notes_notes->getReminder ( $result ['notes_id'] );
			
			$remdata = "";
			if ($reminder_info != null && $reminder_info != "") {
				$remdata = "1";
			} else {
				$remdata = "2";
			}
			
			$journals [] = array (
					'notes_id' => $result ['notes_id'],
					'visitor_log' => $result ['visitor_log'],
					'is_tag' => $result ['is_tag'],
					'is_archive' => $result ['is_archive'],
					'form_type' => $result ['form_type'],
					'generate_report' => $result ['generate_report'],
					'is_census' => $result ['is_census'],
					'is_android' => $result ['is_android'],
					'alltag' => $alltag,
					'remdata' => $remdata,
					'noteskeywords' => $noteskeywords,
					'is_private' => $result ['is_private'],
					'share_notes' => $result ['share_notes'],
					'is_offline' => $result ['is_offline'],
					'review_notes' => $result ['review_notes'],
					'is_private_strike' => $result ['is_private_strike'],
					'checklist_status' => $result ['checklist_status'],
					'notes_type' => $result ['notes_type'],
					'strike_note_type' => $result ['strike_note_type'],
					'task_time' => $task_time,
					'tag_privacy' => $privacy,
					'incidentforms' => $forms,
					'notestasks' => $notestasks,
					'boytotals' => $boytotals,
					'girltotals' => $girltotals,
					'generaltotals' => $generaltotals,
					'residentstotals' => $residentstotals,
					'notesmedicationtasks' => $notesmedicationtasks,
					'task_type' => $result ['task_type'],
					'taskadded' => $result ['taskadded'],
					'assign_to' => $result ['assign_to'],
					'highlighter_value' => $highlighterData ['highlighter_value'],
					'notes_description' => $notes_description,
					// 'keyImageSrc' => $keyImageSrc,
					// 'fileOpen' => $fileOpen,
					'images' => $images,
					'notetime' => date ( 'h:i A', strtotime ( $result ['notetime'] ) ),
					'username' => $result ['user_id'],
					'notes_pin' => $userPin,
					'signature' => $result ['signature'],
					'text_color_cut' => $result ['text_color_cut'],
					'text_color' => $result ['text_color'],
					'note_date' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $result ['note_date'] ) ),
					'status' => ($result ['status'] ? $this->language->get ( 'text_enabled' ) : $this->language->get ( 'text_disabled' )),
					'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
					'strike_user_name' => $result ['strike_user_id'],
					'strike_pin' => $result ['strike_pin'],
					'strike_signature' => $result ['strike_signature'],
					'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $result ['strike_date_added'] ) ),
					'reminder_time' => $reminder_time,
					'reminder_title' => $reminder_title,
					'href' => $this->url->link ( 'notes/notes/insert', '' . '&reset=1&searchdate=' . date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ) . $url, 'SSL' ) 
			);
		}
		
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		if ($this->config->get ( 'pdf_report_image' ) && file_exists ( DIR_SYSTEM . 'library/pdf_class/' . $this->config->get ( 'pdf_report_image' ) )) {
			$imageLogo = $this->config->get ( 'pdf_report_image' );
			$PDF_HEADER_LOGO_WIDTH = "30";
		} else {
			$imageLogo = '4F-logo.png';
			$PDF_HEADER_LOGO_WIDTH = "30";
			$headerString = "";
		}
		
		$date = str_replace ( '-', '/', $searchdate );
		$res = explode ( "/", $date );
		$searchdate2 = $res [1] . "-" . $res [0] . "-" . $res [2];
		
		$PDF_HEADER_TITLE = "Custom Report";
		// $headerString = 'Report Date: '. date('m/d/Y',
		// strtotime($searchdate2)) .' '. $facilities_info['facility'];
		$headerString = $facilities_info ['facility'];
		
		$pdf->SetHeaderData ( $imageLogo, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $headerString );
		
		// $mytcpdfObject->setHtmlHeader('<table>...</table>');
		// set header and footer fonts
		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		
		// set margins
		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
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
	</style>
	';
		
		$html .= '<table width="100%" style="boder:1px solid #000" cellpadding="2" cellspacing="0" align="center">';
		
		$html .= '<thead>';
		$html .= '  <tr>';
		$html .= '    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
		$html .= '    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Time</td>';
		$html .= '    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:50%">Note</td>';
		
		$html .= '    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Username</td>';
		$html .= '    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:10%">Signature/Pin</td>';
		
		$html .= '    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Download Link</td>';
		
		$html .= '  </tr>';
		$html .= ' </thead>';
		
		foreach ( $journals as $journal ) {
			$html .= '<tr>';
			$html .= '<td style="text-align:left;width:10%; line-height:20.2px;">' . $journal ['date_added'] . '</td>';
			$html .= '<td style="text-align:left;width:10%; line-height:20.2px;">' . $journal ['notetime'] . '</td>';
			
			$cssStyle = "";
			if ($journal ['highlighter_value'] != null && $journal ['highlighter_value'] != "") {
				$cssStyle .= 'background-color:' . $journal ['highlighter_value'] . '; ';
			}
			
			if ($journal ['text_color_cut'] == "1") {
				$cssStyle .= 'text-decoration: line-through;';
			}
			
			if ($journal ['text_color'] != null && $journal ['text_color'] != "") {
				$cssStyle .= 'color:' . $journal ['text_color'] . ';';
			}
			
			if (($journal ['highlighter_value'] != null && $journal ['highlighter_value'] != "") && ($journal ['text_color'] == null && $journal ['text_color'] == "")) {
				// $cssStyle .= ';color:#FFF';
				/*
				 * if($journal['highlighter_value'] !='#ffff00'){
				 * $cssStyle .= ';color:#FFF;';
				 * }else{
				 * $cssStyle .= ';color:#000;';
				 * }
				 */
				
				if ($journal ['highlighter_value'] == '#ffff00') {
					$cssStyle .= 'color:#000;';
				} else if ($journal ['highlighter_value'] == '#ffffff') {
					$cssStyle .= 'color:#666;';
				} else {
					$cssStyle .= 'color:#FFF;';
				}
			}
			
			$html .= '<td style="line-height:20.2px;width:50%;text-align:left;' . $cssStyle . '">';
			
			if ($journal ['generate_report'] == "3") {
				$html .= '<img src="sites/view/digitalnotebook/image/generate-Report.png" width="35px" height="35px">';
			}
			if ($journal ['generate_report'] == "2") {
				$html .= '<img src="sites/view/digitalnotebook/image/generate-Report.png" width="35px" height="35px">';
			}
			
			if ($journal ['is_census'] == "1") {
				$html .= '<img src="sites/view/digitalnotebook/image/census.png" width="35px" height="35px">';
			}
			
			if ($journal ['is_offline'] == "1") {
				$html .= '<img src="sites/view/digitalnotebook/image/wifi.png" width="35px" height="35px">';
			}
			
			if ($journal ['checklist_status'] == "1") {
				
				if ($journal ['taskadded'] == "2") {
					$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
				}
				
				if ($journal ['taskadded'] == "3") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
				}
				
				if ($journal ['taskadded'] == "4") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Incomplete: ';
				}
			} elseif ($journal ['checklist_status'] == "2") {
				$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px">';
			} else {
				
				if ($journal ['taskadded'] == "1") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Deleted: ';
				}
				
				if ($journal ['taskadded'] == "2") {
					$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
				}
				
				if ($journal ['taskadded'] == "3") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
				}
				
				if ($journal ['taskadded'] == "4") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Incomplete: ';
				}
			}
			
			if ($journal ['noteskeywords']) {
				foreach ( $journal ['noteskeywords'] as $noteskeyword ) {
					
					$html .= '<img src="' . $noteskeyword ['keyword_file_url'] . ' " width="35px" height="35px">';
				}
			}
			
			$html .= $journal ['notes_description'];
			
			if ($journal ['notestasks'] != null && $journal ['notestasks'] != "") {
				
				foreach ( $journal ['notestasks'] as $notestask ) {
					$html .= '<br> ' . $notestask ['task_content'] . '';
				}
				
				// $html .='<br>';
				if ($journal ['boytotals'] [0] != null && $journal ['boytotals'] [0] != "") {
					$html .= 'Total  ' . $journal ['boytotals'] [0] ['loc_name'] . ': ' . $journal ['boytotals'] [0] ['total'] . ' ';
					$html .= '<br>';
				}
				
				if ($journal ['girltotals'] [0] != null && $journal ['girltotals'] [0] != "") {
					$html .= 'Total  ' . $journal ['girltotals'] [0] ['loc_name'] . ': ' . $journal ['girltotals'] [0] ['total'] . ' ';
					$html .= '<br>';
				}
				
				if ($journal ['generaltotals'] [0] != null && $journal ['generaltotals'] [0] != "") {
					$html .= 'Total  ' . $journal ['generaltotals'] [0] ['loc_name'] . ': ' . $journal ['generaltotals'] [0] ['total'] . ' ';
					$html .= '<br>';
				}
				
				if ($journal ['residentstotals'] [0] != null && $journal ['residentstotals'] [0] != "") {
					$html .= 'Total  ' . $journal ['residentstotals'] [0] ['loc_name'] . ': ' . $journal ['residentstotals'] [0] ['total'] . ' ';
					$html .= '<br>';
				}
			}
			
			$html .= '</td>';
			
			$html .= '<td style="text-align:right;width:10%; line-height:20.2px;">' . $journal ['username'] . '  </td>';
			$html .= '<td style="text-align:center;width:10%; line-height:20.2px;">';
			
			if ($journal ['username'] != null && $journal ['username'] != "0") {
				
				if ($journal ['notes_type'] == "2") {
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} elseif ($journal ['notes_type'] == "1") {
					
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} elseif ($journal ['notes_pin'] != null && $journal ['notes_pin'] != "") {
					
					$html .= '<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
				} else {
					// var_dump($journal['signature']);
					// echo "<hr>";
					$html .= '<img style="text-align: center;" src="' . $journal ['signature'] . '" width="98px" height="29px" style="    vertical-align: bottom;">';
				}
			}
			
			$html .= '</td>';
			
			$html .= '<td style="text-align:right;width:10%; line-height:20.2px;">';
			
			if ($journal ['images']) {
				foreach ( $journal ['images'] as $images ) {
					$html .= '<a target="_blank" href="' . $images ['notes_file'] . '" target="_blank"><img src="sites/view/digitalnotebook/image/attachment_icons.png" width="35px" height="35px" ></a> ';
				}
			}
			
			if ($journal ['incidentforms']) {
				foreach ( $journal ['incidentforms'] as $forms ) {
					if ($forms ['custom_form_type'] == '9') {
						$html .= '<a target="_blank" href="' . $firedrillnoteurl . '&notes_id=' . $result ['notes_id'] . '&forms_design_id=' . $forms ['custom_form_type'] . '&forms_id=' . $forms ['forms_id'] . '" target="_blank"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
					if ($forms ['custom_form_type'] == '13') {
						$html .= '<a target="_blank" href="' . $printnoteurl . '&notes_id=' . $result ['notes_id'] . '&forms_design_id=' . $forms ['custom_form_type'] . '&forms_id=' . $forms ['forms_id'] . '" target="_blank"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
					if ($forms ['custom_form_type'] == '10') {
						$html .= '<a target="_blank" href="' . $incidentnoteurl . '&notes_id=' . $result ['notes_id'] . '&forms_design_id=' . $forms ['custom_form_type'] . '&forms_id=' . $forms ['forms_id'] . '" target="_blank"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
					if ($forms ['custom_form_type'] == '2') {
						$html .= '<a target="_blank" href="' . $innoteurl . '&notes_id=' . $result ['notes_id'] . '&forms_design_id=' . $forms ['custom_form_type'] . '&forms_id=' . $forms ['forms_id'] . '" target="_blank"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
				}
			}
			
			$html .= '</td>';
			
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		
		// var_dump($html);
		// die;
		
		$pdf->writeHTML ( $html, true, 0, true, 0 );
		
		$pdf->lastPage ();
		
		$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
		exit ();
	}
	public function displayFilemedia() {
		if (isset ( $this->request->get ['notes_by_task_id'] )) {
			$notes_by_task_id = $this->request->get ['notes_by_task_id'];
			$media = $this->request->get ['media'];
			
			$this->load->model ( 'notes/notes' );
			$task_info = $this->model_notes_notes->getnotesBytask ( $notes_by_task_id );
			
			if ($task_info != null && $task_info != "") {
				
				if ($media == '1') {
					$this->data ['notes_file'] = $task_info ['media_url'];
				} else {
					$this->data ['notes_file'] = $task_info ['medication_attach_url'];
				}
				
				$this->data ['notes_media_extention'] = ".jpg";
			}
		}
		
		if (isset ( $this->request->get ['notes_id'] )) {
			$notes_id = $this->request->get ['notes_id'];
			$media = $this->request->get ['media'];
			
			$this->load->model ( 'notes/notes' );
			$note_info = $this->model_notes_notes->getNote ( $notes_id );
			
			if ($note_info != null && $note_info != "") {
				$this->data ['notes_file'] = $note_info ['user_file'];
				$this->data ['notes_media_extention'] = ".jpg";
			}
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/display_media_img.php';
		
		$this->response->setOutput ( $this->render () );
	}
	public function activenote() {
		$facilities_id = $this->customer->getId ();
		
		$this->load->model ( 'setting/keywords' );
		$this->load->model ( 'notes/image' );
		$this->load->model ( 'setting/activeforms' );
		$this->load->model ( 'form/form' );
		
		$this->data ['config_multiple_activenote'] = $this->customer->isMactivenote ();
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateactivenote ()) {
			if (isset ( $this->request->post ['keyword_file'] )) {
				$this->data ['keyword_file'] = $this->request->post ['keyword_file'];
				
				$sssssddss2 = explode(",",$this->request->post ['keyword_file']);
				
				$newhtml = "";
				foreach($sssssddss2 as $sssssddss){
					$newhtml .= '<img src="'.$sssssddss.'" width="20px" height="20px" alt="" />'; 
				}
				$this->data ['newhtml'] = $newhtml;
			}
			if (isset ( $this->request->post ['notes_description'] )) {
				$this->data ['notes_description'] = $this->request->post ['notes_description'];
			}
			
			if (isset ( $this->request->post ['multi_keyword_file'] )) {
				$this->data ['multi_keyword_file'] = $this->request->post ['multi_keyword_file'];
			}
			if (isset ( $this->request->post ['facilityids'] )) {
				$this->data ['facilityids'] = $this->request->post ['facilityids'];
			}
			
			
			if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
				$this->data ['notes_id'] = $this->request->get ['notes_id'];
			}
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->request->post ['keyword_file'] )) {
			$this->data ['keyword_file'] = $this->request->post ['keyword_file'];
		} else {
			$this->data ['keyword_file'] = '';
		}
		
		if (isset ( $this->request->post ['multi_keyword_file'] )) {
			$this->data ['multi_keyword_file'] = $this->request->post ['multi_keyword_file'];
		} else {
			$this->data ['multi_keyword_file'] = '';
		}
		if (isset ( $this->request->post ['facilityids'] )) {
			$this->data ['facilityids'] = $this->request->post ['facilityids'];
		} else {
			$this->data ['facilityids'] = '';
		}
		
		if (isset ( $this->request->post ['notes_description'] )) {
			$this->data ['notes_description'] = $this->request->post ['notes_description'];
		} else {
			$this->data ['notes_description'] = '';
		}
		if (isset ( $this->request->get ['notes_id'] )) {
			$this->data ['notes_id'] = $this->request->get ['notes_id'];
		} else {
			$this->data ['notes_id'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		$this->data ['keywords'] = array ();
		
		if ($this->request->get ['notes_id'] == null && $this->request->get ['notes_id'] == "") {
			$monitor_time = '1';
		} else {
			$monitor_time = '6';
		}
		$data3 = array (
				'facilities_id' => $facilities_id,
				'monitor_time' => $monitor_time 
		);

		//var_dump($data3);die;
		
		$keywords = $this->model_setting_keywords->getkeywords ( $data3 );


		
		$url2 = "";
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		$this->data ['action2'] = $this->url->link ( 'notes/notes/activenote', '' . $url2, 'SSL' );
		
		foreach ( $keywords as $keyword ) {
			
			if ($keyword ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $keyword ['keyword_image'] )) {
				// $image = $this->model_notes_image->resize('icon/' . $keyword['keyword_image'], 35, 35);
			}
			$image = $keyword ['keyword_image'];
			$lines_arr = preg_split ( '/\n|\r/', $keyword ['keyword_name'] );
			$num_newlines = count ( $lines_arr );
			
			// var_dump($num_newlines);
			
			if ($keyword ['monitor_time'] == '1') {
				
				if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
					$url2 .= '&notesactivenote=1';
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
				} else {
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
				}
			} else if ($keyword ['monitor_time'] == '4') {
				
				$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/multiple', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
			} else {
				$activenote_url = '';
			}
			
			$this->data ['keywords'] [] = array (
					'keyword_id' => $keyword ['keyword_id'],
					'keyword_name' => $keyword ['keyword_name'],
					// 'keyword_name2' => str_replace(array("\r", "\n"), '',
					// $keyword['keyword_name']),
					'keyword_name2' => str_replace ( array (
							"\r",
							"\n" 
					), '\n', $keyword ['keyword_name'] ),
					'keyword_image' => $keyword ['keyword_image'],
					'monitor_time' => $keyword ['monitor_time'],
					'activenote_url' => $activenote_url,
					'img_icon' => $image,
					'num_newlines' => $num_newlines 
			);
		}
		
		if ($this->request->get ['notes_id'] == null && $this->request->get ['notes_id'] == "") {
			
			$data3 = array (
					'facilities_id' => $facilities_id,
					'monitor_time' => '2' 
			);
			
			$keywords = $this->model_setting_keywords->getkeywords ( $data3 );


			
			$url2 = "";
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			foreach ( $keywords as $keyword ) {
				
				if ($keyword ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $keyword ['keyword_image'] )) {
					// $image = $this->model_notes_image->resize('icon/' . $keyword['keyword_image'], 35, 35);
				}
				$image = $keyword ['keyword_image'];
				$lines_arr = preg_split ( '/\n|\r/', $keyword ['keyword_name'] );
				$num_newlines = count ( $lines_arr );
				
				// var_dump($keyword ['monitor_time']);
				
				if ($keyword ['monitor_time'] == '2') {
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/timeselection', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					
					$keyword_name = $keyword ['keyword_name'];
				} else if ($keyword ['monitor_time'] == '1') {
					
					if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
						$url2 .= '&notesactivenote=1';
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					} else {
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					}
					
					$keyword_name = $keyword ['keyword_name'];
				} else if ($keyword ['monitor_time'] == '3') {
					
					$activefrom_info = $this->model_setting_activeforms->getActiveForm23 ( $keyword ['keyword_id'], $facilities_id );
					
					$formdetails = $this->model_form_form->getFormdata ( $activefrom_info ['forms_id'] );
					
					$keyword_name = $activefrom_info ['activeform_name'];
					
					if ($formdetails ['open_search'] == '1') {
						
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/linkedform', '' . '&forms_design_id=' . $activefrom_info ['forms_id'] . '&keyword_id=' . $keyword ['keyword_id'] . '&activeform_id=' . $activefrom_info ['activeform_id'] . $url2, 'SSL' ) );
					} else {
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . '&forms_design_id=' . $activefrom_info ['forms_id'] . '&keyword_id=' . $keyword ['keyword_id'] . '&activeform_id=' . $activefrom_info ['activeform_id'] . $url2, 'SSL' ) );
					}
				} else if ($keyword ['monitor_time'] == '7') {
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/facilitysection', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					
					$keyword_name = $keyword ['keyword_name'];
				} else if ($keyword ['monitor_time'] == '8') {
					if($keyword ['client_status'] == '1'){
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsin', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					}
					if($keyword ['client_status'] == '2'){
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsout', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					}
					
					if($keyword ['client_status'] == '3'){
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/activenote/clientsdischarge', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					}
					
					if($keyword ['client_status'] == '4'){
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient&addclient=1', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					}
					
					$keyword_name = $keyword ['keyword_name'];
				}else if ($keyword ['monitor_time'] == '10') {
					$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/alltags', '' . '&keyword_id=' . $keyword ['keyword_id'] . $url2, 'SSL' ) );
					
					$keyword_name = $keyword ['keyword_name'];
				}else {
					$activenote_url = '';
					$keyword_name = $keyword ['keyword_name'];
				}
				
				$this->data ['activefroms'] [] = array (
						'keyword_id' => $keyword ['keyword_id'],
						'keyword_name' => $keyword ['keyword_name'],
						
						'keyword_image' => $keyword ['keyword_image'],
						'monitor_time' => $keyword ['monitor_time'],
						'activenote_url' => $activenote_url,
						'img_icon' => $image,
						'num_newlines' => $num_newlines 
				);
			}
		}
		
		/*
		 * $dataforms = array(
		 * 'facilities_id' => $facilities_id,
		 * 'monitor_time' => '3'
		 * );
		 *
		 * $activefroms = $this->model_setting_activeforms->getActivekeywords($dataforms);
		 *
		 * $url2 = "";
		 * if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
		 * $url2 .= '&tags_id=' . $this->request->get['tags_id'];
		 * }
		 *
		 *
		 *
		 * foreach ($activefroms as $activefrom) {
		 *
		 * $keydetail = $this->model_setting_keywords->getkeywordDetail($activefrom['keyword_id']);
		 * $formdetails = $this->model_form_form->getFormdata($activefrom['forms_id']);
		 *
		 * if ($keydetail['keyword_image'] && file_exists(DIR_IMAGE . 'icon/' . $keydetail['keyword_image'])) {
		 * $image = $this->model_notes_image->resize('icon/' . $keydetail['keyword_image'], 35, 35);
		 * }
		 * $lines_arr = preg_split('/\n|\r/', $keydetail['keyword_name']);
		 * $num_newlines = count($lines_arr);
		 *
		 *
		 * if($formdetails['open_search'] == '1'){
		 *
		 * $activenote_url = str_replace('&amp;', '&', $this->url->link('form/linkedform', '' . '&forms_design_id=' . $activefrom['forms_id'] . '&keyword_id=' . $activefrom['keyword_id'] . '&activeform_id=' . $activefrom['activeform_id'] . $url2, 'SSL'));
		 *
		 * }else{
		 * $activenote_url = str_replace('&amp;', '&', $this->url->link('form/form', '' . '&forms_design_id=' . $activefrom['forms_id'] . '&keyword_id=' . $activefrom['keyword_id'] . '&activeform_id=' . $activefrom['activeform_id'] . $url2, 'SSL'));
		 * }
		 *
		 * //$activenote_url = str_replace('&amp;', '&', $this->url->link('form/form', '' . '&forms_design_id=' . $activefrom['forms_id'] . '&keyword_id=' . $activefrom['keyword_id'] . '&activeform_id=' . $activefrom['activeform_id'] . $url2, 'SSL'));
		 *
		 *
		 *
		 * $this->data['activefroms'][] = array(
		 * 'keyword_id' => $keydetail['keyword_id'],
		 * 'keyword_image' => $keydetail['keyword_image'],
		 * 'activeform_name' => $activefrom['activeform_name'],
		 * 'activenote_url' => $activenote_url,
		 * 'img_icon' => $image,
		 * 'num_newlines' => $num_newlines
		 * );
		 * }
		 */
		
		/*
		 * $this->load->model('facilities/facilities');
		 * $results = $this->model_facilities_facilities->getfacilitiess($data);
		 *
		 * foreach ($results as $result) {
		 *
		 * $this->data['facilitiess'][] = array(
		 * 'facilities_id' => $result['facilities_id'],
		 * 'facility' => $result['facility']
		 * );
		 * }
		 *
		 * $this->load->model('form/form');
		 *
		 * $data3 = array();
		 * $data3['status'] = '1';
		 * // $data3['order'] = 'sort_order';
		 * $data3['is_parent'] = '1';
		 * $data3['facilities_id'] = $facilities_id;
		 *
		 * $custom_forms = $this->model_form_form->getforms($data3);
		 *
		 * $this->data['custom_forms'] = array();
		 * foreach ($custom_forms as $custom_form) {
		 *
		 * if($custom_form['open_search'] == '1'){
		 * $href = $this->url->link('form/linkedform', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL');
		 * }else{
		 * $href = $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL');
		 * }
		 *
		 * $this->data['custom_forms'][] = array(
		 * 'forms_id' => $custom_form['forms_id'],
		 * 'form_name' => $custom_form['form_name'],
		 * 'form_href' => $href,
		 * );
		 *
		 * }
		 */
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/activenote.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	protected function validateactivenote() {
		if ($this->request->post ['keyword_file'] == "" && $this->request->post ['keyword_file'] == NULL) {
			$this->error ['warning'] = "Select Activenote";
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function allforms() {
		
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}
		
		if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get['userids'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facilities_info ['approval_required'] == 3 || $facilities_info ['approval_required'] == 1 || $facilities_info ['approval_required'] == 4 || $facilities_info ['approval_required'] == 5) {
			
			$url2 .= '&is_formsecurity='.$facilities_info ['approval_required'];
			$this->data ['medication_url'] = $this->url->link ( 'common/authorization&staticform=1', '' . $url2, 'SSL' );
			
			$this->data ['inventory_check_in_url'] = $this->url->link ( 'common/authorization&staticform=2', '' . $url2, 'SSL' );
			
			$this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&staticform=3', '' . '&addclient=1'. $url2, 'SSL'));
			
			$this->data ['inventory_check_out_url'] = $this->url->link ( 'common/authorization&staticform=4', '' . $url2, 'SSL' );
		}else{
			$this->data ['medication_url'] = $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' );
			
			$this->data ['inventory_check_in_url'] = $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' );
			
			$this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '' . '&addclient=1'. $url2, 'SSL'));
			
			$this->data ['inventory_check_out_url'] = $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url2, 'SSL' );
		}
		
		$config_tag_status = $this->customer->isTag ();
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		//$facilities_id = $this->customer->getId ();
		
		$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
		$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		
		
		
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$this->data['customers'] = array();
		if (! empty ( $customer_info ['setting_data'])) {
			$customers = unserialize($customer_info ['setting_data']);
			$this->data['customerinfo'] = $customers;
		}
		
		$data3 = array ();
		$data3 ['status'] = '1';
		// $data3['order'] = 'sort_order';
		$data3 ['is_parent'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		
		$custom_forms = $this->model_form_form->getforms ( $data3 );
		
		$this->data ['custom_forms'] = array ();
		foreach ( $custom_forms as $custom_form ) {
			
			if ($custom_form ['approval_required'] == 3 || $custom_form ['approval_required'] == 1 || $custom_form ['approval_required'] == 4 || $custom_form ['approval_required'] == 5) {
				$url23 = "";
				$url23 .= '&is_formsecurity='.$custom_form ['approval_required'];
				$url23 .= '&forms_design_id='.$custom_form ['forms_id'];
                $href = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2.$url23, 'SSL'));
				
			} else {
				if ($custom_form ['open_search'] == '1') {
					$href = $this->url->link ( 'form/linkedform', '' . '&forms_design_id=' . $custom_form ['forms_id'].$url2, 'SSL' );
				} else {
					$href = $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'].$url2, 'SSL' );
				}
			}
			
			$this->data ['custom_forms'] [] = array (
					'forms_id' => $custom_form ['forms_id'],
					'form_name' => $custom_form ['form_name'],
					'form_href' => $href 
			);
			/*
			 * $this->data['custom_forms'][] = array(
			 * 'forms_id' => $custom_form['forms_id'],
			 * 'form_name' => $custom_form['form_name'],
			 * 'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
			 * );
			 */
		}
		
		$result = $this->model_notes_notes->getnotes ( $this->request->get ['notes_id'] );
		
		if ($config_tag_status == '1') {
			
			if ($result ['emp_tag_id'] == '1') {
				$alltag = $this->model_notes_notes->getNotesTags ( $result ['notes_id'] );
			} else {
				$alltag = array ();
			}
			
			if ($alltag ['emp_tag_id'] != null && $alltag ['emp_tag_id'] != "") {
				$tagdata = $this->model_notes_tags->getTagbyEMPID ( $alltag ['emp_tag_id'] );
				$privacy = $tagdata ['privacy'];
				
				if ($tagdata ['privacy'] == '2') {
					if ($this->session->data ['unloack_success'] != '1') {
						$emp_tag_id = $alltag ['emp_tag_id'] . ':' . $tagdata ['emp_first_name'];
					} else {
						$emp_tag_id = '';
					}
				} else {
					$emp_tag_id = '';
				}
			} else {
				$emp_tag_id = '';
				$privacy = '';
			}
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/allforms.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	public function allocations() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}

			$this->data ['reseturl'] = str_replace('&amp;', '&',$this->url->link ( 'notes/notes/alltags', '' , 'SSL' ));
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/allocations', '' . $url2, 'SSL' );
		//$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
		//$facilities_id = $this->customer->getId ();

			$this->data['ajaxlocationurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxAllLocations', '' . $url2, 'SSL'));
		
		
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'setting/locations' );
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		
		
		if ($this->request->get ['locationids'] != '' && $this->request->get ['locationids'] != null) {
			$locationids = $this->request->get ['locationids'];
			$this->data ['locationids']=$locationids;
		}
		
		
		$this->load->model('facilities/facilities');
		$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$ddss = array();
		
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				
				if ($facilities_info2 ['is_master_facility'] == '1') {
					if($facilities_info2['notes_facilities_ids'] != null && $facilities_info2['notes_facilities_ids'] != ""){
						$this->data['is_master_facility']  =  '1' ; 
						$ddss[] = $facilities_info2['notes_facilities_ids'];
					}else{
						$this->data['is_master_facility']  =  '2' ; 
					}
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
				if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
					$this->data['is_master_facility']  =  '1' ; 
					$ddss[] = $result['notes_facilities_ids'];
				}else{
					$this->data['is_master_facility']  =  '2' ; 
				}
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
			if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
				$ddss[] = $result['notes_facilities_ids'];
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
		}
		
		$ddss[] = $facilities_id;
		$sssssdd = implode(",",$ddss);
		
		$dataaaa = array();
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$dataaaa['facilities'] = $this->request->get ['facilityids'];
		} else {
			$dataaaa['facilities'] = $sssssdd;
		}
		//var_dump($dataaaa);
		
		$allocations = $this->model_setting_locations->getlocations ( $dataaaa );
		$this->data ['locations'] = array ();
		foreach ( $allocations as $location ) {
			
			$facility_info = $this->model_facilities_facilities->getfacilities($location ['facilities_id']);
			$this->data ['locations'] [] = array (
					'locations_id' => $location ['locations_id'],
					'facilities_id' => $location ['facilities_id'],
					'facility' =>$facility_info['facility'],
					'location_name' => $location ['location_name'],
					'form_href' => $this->url->link ( 'notes/notes/allocations', '' . '&locations_id=' . $location ['locations_id'], 'SSL' ),
			);
			
		}
	
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/allocations.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}

	public function ajaxAllLocations() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/allocations', '' . $url2, 'SSL' );
		//$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
		//$facilities_id = $this->customer->getId ();

		$this->data['ajaxlocationurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxAllLocations', '' . $url2, 'SSL'));

		if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }	
		
		
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'setting/locations' );
		
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		
		
		if ($this->request->get ['locationids'] != '' && $this->request->get ['locationids'] != null) {
			$locationids = $this->request->get ['locationids'];
			$this->data ['locationids']=$locationids;
		}
		
		
		$this->load->model('facilities/facilities');
		$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());	
		
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$ddss = array();
		
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				
				if ($facilities_info2 ['is_master_facility'] == '1') {
					if($facilities_info2['notes_facilities_ids'] != null && $facilities_info2['notes_facilities_ids'] != ""){
						$this->data['is_master_facility']  =  '1' ; 
						$ddss[] = $facilities_info2['notes_facilities_ids'];
					}else{
						$this->data['is_master_facility']  =  '2' ; 
					}
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
				if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
					$this->data['is_master_facility']  =  '1' ; 
					$ddss[] = $result['notes_facilities_ids'];
				}else{
					$this->data['is_master_facility']  =  '2' ; 
				}
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
			if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
				$ddss[] = $result['notes_facilities_ids'];
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
		}

		
		
		$ddss[] = $facilities_id;
		$sssssdd = implode(",",$ddss);
		
		$dataaaa = array();
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$facilities = $this->request->get ['facilityids'];
		} else {
			$facilities = $sssssdd;
		}

		$config_admin_limit = 40;
		
		
			$dataaaa = array (
				'facilities' => $facilities,
				'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
				'status' => 1,				
				'all_record' => 1,
				//'role_call' => '1',
				//'is_master' => 1,
				'sort' => 'location_name',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);

		//var_dump($dataaaa);
		
		$allocations = $this->model_setting_locations->getlocations ( $dataaaa );
		
		foreach ( $allocations as $location ) {
			
			$facility_info = $this->model_facilities_facilities->getfacilities($location ['facilities_id']);
			$json [] = array (
					'locations_id' => $location ['locations_id'],
					'facilities_id' => $location ['facilities_id'],
					'facility' =>$facility_info['facility'],
					'location_name' => $location ['location_name'],
					'form_href' => $this->url->link ( 'notes/notes/allocations', '' . '&locations_id=' . $location ['locations_id'], 'SSL' ),
			);
			
		}
	
	$template = new Template();
		$template->data['locations'] = $json;
		$template->data['config_task_deleted_time'] = $this->config->get('config_task_deleted_time');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/notification/ajaxalllocations.php')) {
			$html = $template->fetch($this->config->get('config_template') . '/template/notification/ajaxalllocations.php');
		} 
		
		//var_dump($html);
		$ajax_status = 1;
		if(empty($allocations)){
			$ajax_status = 2;
		}
		
		$json1 = array();
		$json1['ajax_status'] = $ajax_status;
		$json1['html'] = $html;
		
		$this->response->setOutput(json_encode($json1));
	}

	public function allfacilities() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$url2 = "";
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/allfacilities', '' . $url2, 'SSL' );

		$this->data['ajaxfacilitiesurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxAllfacilities', '' . $url2, 'SSL'));
		
		//$facilities_id = $this->customer->getId ();
		
		
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'facilities/facilities' );
		
		/*$data = array ();
		$data ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		
		//$alfacilities = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		//var_dump($alfacilities);
		*/
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$facilityids = $this->request->get ['facilityids'];
			$this->data['facilityids']=$facilityids;
			
		}
		
		$this->load->model('facilities/facilities');
		$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
		$ddss = array();
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				
				if ($facilities_info2 ['is_master_facility'] == '1') {
					if($facilities_info2['notes_facilities_ids'] != null && $facilities_info2['notes_facilities_ids'] != ""){
						$this->data['is_master_facility']  =  '1' ; 
						$ddss[] = $facilities_info2['notes_facilities_ids'];
					}else{
						$this->data['is_master_facility']  =  '2' ; 
					}
				}
				
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
				if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
					$this->data['is_master_facility']  =  '1' ; 
					$ddss[] = $result['notes_facilities_ids'];
				}else{
					$this->data['is_master_facility']  =  '2' ; 
				}
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
			if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
				$ddss[] = $result['notes_facilities_ids'];
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
		}
		
		
		
		$ddss[] = $facilities_id;
		$sssssdd = implode(",",$ddss);
			
		$dataaaa = array();
		$dataaaa['facilities'] = $sssssdd;
		
		
		$alfacilities =  $this->model_facilities_facilities->getfacilitiess($dataaaa);
		
		$this->data ['facilities'] = array ();
		foreach ( $alfacilities as $facility ) {
			$this->data ['facilities'] [] = array (
					'facilities_id' => $facility ['facilities_id'],
					'facility' => $facility ['facility'],
					'form_href' => $this->url->link ( 'notes/notes/allocations', '' . '&locations_id=' . $facility ['locations_id'], 'SSL' ),
			);
			
		}
	
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/alfacilities.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}

	public function ajaxAllfacilities() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$url2 = "";
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/allfacilities', '' . $url2, 'SSL' );

			$this->data ['reseturl'] = str_replace('&amp;', '&',$this->url->link ( 'notes/notes/allfacilities', '' , 'SSL' ));

		$this->data['ajaxfacilitiesurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxAllfacilities', '' . $url2, 'SSL'));

		if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }	
		
		//$facilities_id = $this->customer->getId ();
		
		
		
		$this->load->model ( 'form/form' );
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'facilities/facilities' );
		
		/*$data = array ();
		$data ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		
		//$alfacilities = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		//var_dump($alfacilities);
		*/
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$facilityids = $this->request->get ['facilityids'];
			$this->data['facilityids']=$facilityids;
			
		}
		
		$this->load->model('facilities/facilities');
		$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
		$ddss = array();
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $this->session->data ['search_facilities_id'] );
				
				if ($facilities_info2 ['is_master_facility'] == '1') {
					if($facilities_info2['notes_facilities_ids'] != null && $facilities_info2['notes_facilities_ids'] != ""){
						$this->data['is_master_facility']  =  '1' ; 
						$ddss[] = $facilities_info2['notes_facilities_ids'];
					}else{
						$this->data['is_master_facility']  =  '2' ; 
					}
				}
				
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
				$timeZone = date_default_timezone_set ( $timezone_name );
				if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
					$this->data['is_master_facility']  =  '1' ; 
					$ddss[] = $result['notes_facilities_ids'];
				}else{
					$this->data['is_master_facility']  =  '2' ; 
				}
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
			if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
				$ddss[] = $result['notes_facilities_ids'];
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
		}
		
		
		
		
		$ddss[] = $facilities_id;
		$sssssdd = implode(",",$ddss);
			
		//$dataaaa = array();
		//$dataaaa['facilities'] = $sssssdd;

		$config_admin_limit='40';


		$dataaaa = array (
				'facilities' => $sssssdd,
				'status' => 1,				
				'sort' => 'facility',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);
		
		
		$alfacilities =  $this->model_facilities_facilities->getfacilitiess($dataaaa);
		
	
		foreach ( $alfacilities as $facility ) {
			$json [] = array (
					'facilities_id' => $facility ['facilities_id'],
					'facility' => $facility ['facility'],
					'form_href' => $this->url->link ( 'notes/notes/allocations', '' . '&locations_id=' . $facility ['locations_id'], 'SSL' ),
			);
			
		}
	
		$template = new Template();
		$template->data['facilities'] = $json;
		$template->data['config_task_deleted_time'] = $this->config->get('config_task_deleted_time');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/notification/ajaxfacilities.php')) {
			$html = $template->fetch($this->config->get('config_template') . '/template/notification/ajaxfacilities.php');
		} 
		
		//var_dump($html);
		$ajax_status = 1;
		if(empty($alfacilities)){
			$ajax_status = 2;
		}
		
		$json1 = array();
		$json1['ajax_status'] = $ajax_status;
		$json1['html'] = $html;
		
		$this->response->setOutput(json_encode($json1));
	}








	
	
	
	
	
	public function alltags() {
		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$url2 = "";
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}
		if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
		}
		
		
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		$this->data ['action2'] = str_replace('&amp;', '&',$this->url->link ( 'notes/notes/alltags', '' . $url2, 'SSL' ));
		$this->data ['reseturl'] = str_replace('&amp;', '&',$this->url->link ( 'notes/notes/alltags', '' , 'SSL' ));
		
		$this->data['ajaxresidenturl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxresident', '' . $url2, 'SSL'));
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
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
			// $facilities_id = $this->customer->getId();
		}
		
		
		if ($this->request->get ['tagsids'] != '' && $this->request->get ['tagsids'] != null) {
			$tagsids = $this->request->get ['tagsids'];
			$this->data ['tagsids']=$tagsids;

			
		}

		if ($this->request->get ['keyword_id'] != '' && $this->request->get ['keyword_id'] != null) {
			$keyword_id = $this->request->get ['keyword_id'];
			$this->data ['keyword_id']=$keyword_id;
			$this->data ['keyword_id_url']=1;

			$this->data['clientstatuses_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allclientstatuses', '' . $url2, 'SSL'));	

			
		}
		

		if ($this->request->get ['notes_id'] != '' && $this->request->get ['notes_id'] != null) {
			$notes_id = $this->request->get ['notes_id'];
			$this->data ['notes_id']=$notes_id;
			$this->data ['keyword_id_url2']=1;
			//$this->load->model ( 'notes/notes' );
			//$notes_info = $this->model_notes_notes->getNote ( $notes_id );
			//$facilities_id = $notes_info ['facilities_id'];

			$this->data['clientstatuses_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateTags', '' . $url2, 'SSL'));	

			
		}
		
		$config_admin_limit = 40;
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$data = array (
				'facilities' => $this->request->get ['facilityids'],
				'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				//'role_call' => '1',
				//'is_master' => 1,
				'sort' => 'emp_last_name',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);

		} else {
			$data = array (
				'facilities_id' => $facilities_id,
				'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				'is_master' => 1,
				//'role_call' => '1',
				'sort' => 'emp_last_name',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);
		}
		
		//var_dump($data);
		
		$tags = $this->model_setting_tags->getTags ( $data );
		
		foreach ( $tags as $result ) {
			
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
			
			if ($result ['dob'] != "0000-00-00") {
				$dobm = date ( 'm', strtotime ( $result ['dob'] ) );
			} else {
				$dobm = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$dobd = date ( 'd', strtotime ( $result ['dob'] ) );
			} else {
				$dobd = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$doby = date ( 'Y', strtotime ( $result ['dob'] ) );
			} else {
				$doby = '';
			}
			
			
			$get_img = $this->model_setting_tags->getImage($result['tags_id']);			
				
			if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $get_img['enroll_image'];
			}
			
			$image_url1 = $upload_file_thumb_1;
			$upload_file = $upload_file_thumb_1;
				 
			
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
			
			$facility_info = $this->model_facilities_facilities->getfacilities($result ['facilities_id']);
			
			$this->data ['tags'] [] = array (
					'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
					'tags_id' => $result ['tags_id'],
					'emp_tag_id2' => $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'],
					'emp_tag_id' => $result ['emp_tag_id'],
					'emp_first_name' => $result ['emp_first_name'],
					'emp_middle_name' => $result ['emp_middle_name'],
					'emp_last_name' => $result ['emp_last_name'],
					'location_address' => $result ['location_address'],
					'facilities_id' => $result ['facilities_id'],
					'discharge' => $result ['discharge'],
					'facility' =>$facility_info['facility'],
					'dob' => $dob,
					'month' => $dobm,
					'date' => $dobd,
					'year' => $doby,
					'medication' => $result ['medication'],
					// 'gender'=> $result['gender'],
					'gender' => $result ['customlistvalues_id'],
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
					'stickynote' => $result ['stickynote'],
					'referred_facility' => $result ['referred_facility'],
					'emergency_contact' => $result ['emergency_contact'],
					'upload_file' => $upload_file,
					'image_url1' => $image_url1,
					'screening_update_url' => $action211 
			);
		}
	
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/alltags.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	public function ajaxresident() {
		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$url2 = "";
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
		}
		if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
		}

		if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
		
		
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		$this->data ['action2'] = str_replace('&amp;', '&', $this->url->link ( 'notes/notes/alltags', '' . $url2, 'SSL' ));
		
		$this->data['ajaxresidenturl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxresident', '' . $url2, 'SSL'));
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
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
			// $facilities_id = $this->customer->getId();
		}
		
		
		if ($this->request->get ['tagsids'] != '' && $this->request->get ['tagsids'] != null) {
			$tagsids = $this->request->get ['tagsids'];
			$this->data ['tagsids']=$tagsids;

			
		}

		if ($this->request->get ['keyword_id'] != '' && $this->request->get ['keyword_id'] != null) {
			$keyword_id = $this->request->get ['keyword_id'];
			$this->data ['keyword_id']=$keyword_id;
			$this->data ['keyword_id_url']=1;

			$this->data['clientstatuses_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allclientstatuses', '' . $url2, 'SSL'));	

			
		}
		

		if ($this->request->get ['notes_id'] != '' && $this->request->get ['notes_id'] != null) {
			$notes_id = $this->request->get ['notes_id'];
			$this->data ['notes_id']=$notes_id;
			$this->data ['keyword_id_url2']=1;
			//$this->load->model ( 'notes/notes' );
			//$notes_info = $this->model_notes_notes->getNote ( $notes_id );
			//$facilities_id = $notes_info ['facilities_id'];

			$this->data['clientstatuses_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateTags', '' . $url2, 'SSL'));	

			
		}
		
		$config_admin_limit = 40;
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$data = array (
				'facilities' => $this->request->get ['facilityids'],
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				//'role_call' => '1',
				//'is_master' => 1,
				'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
				'sort' => 'emp_last_name',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);

		} else {
			$data = array (
				'facilities_id' => $facilities_id,
				'status' => 1,
				'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
				'discharge' => 1,
				'all_record' => 1,
				'is_master' => 1,
				//'role_call' => '1',
				'sort' => 'emp_last_name',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);
		}
		
		
		$tags = $this->model_setting_tags->getTags ( $data );
		
		foreach ( $tags as $result ) {
			
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
			
			if ($result ['dob'] != "0000-00-00") {
				$dobm = date ( 'm', strtotime ( $result ['dob'] ) );
			} else {
				$dobm = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$dobd = date ( 'd', strtotime ( $result ['dob'] ) );
			} else {
				$dobd = '';
			}
			if ($result ['dob'] != "0000-00-00") {
				$doby = date ( 'Y', strtotime ( $result ['dob'] ) );
			} else {
				$doby = '';
			}
			
			
			$get_img = $this->model_setting_tags->getImage($result['tags_id']);			
				
			if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $get_img['enroll_image'];
			}
			
			$image_url1 = $upload_file_thumb_1;
			$upload_file = $upload_file_thumb_1;
				 
			
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
			
			$facility_info = $this->model_facilities_facilities->getfacilities($result ['facilities_id']);
			
			$json [] = array (
					'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
					'tags_id' => $result ['tags_id'],
					'emp_tag_id2' => $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'],
					'emp_tag_id' => $result ['emp_tag_id'],
					'emp_first_name' => $result ['emp_first_name'],
					'emp_middle_name' => $result ['emp_middle_name'],
					'emp_last_name' => $result ['emp_last_name'],
					'location_address' => $result ['location_address'],
					'facilities_id' => $result ['facilities_id'],
					'discharge' => $result ['discharge'],
					'facility' =>$facility_info['facility'],
					'dob' => $dob,
					'month' => $dobm,
					'date' => $dobd,
					'year' => $doby,
					'medication' => $result ['medication'],
					// 'gender'=> $result['gender'],
					'gender' => $result ['customlistvalues_id'],
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
					'stickynote' => $result ['stickynote'],
					'referred_facility' => $result ['referred_facility'],
					'emergency_contact' => $result ['emergency_contact'],
					'upload_file' => $upload_file,
					'image_url1' => $image_url1,
					'screening_update_url' => $action211 
			);
		}
	
		
		
		$template = new Template();
		$template->data['tags'] = $json;
		$template->data['config_task_deleted_time'] = $this->config->get('config_task_deleted_time');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/notification/ajaxalltags.php')) {
			$html = $template->fetch($this->config->get('config_template') . '/template/notification/ajaxalltags.php');
		} 
		
		//var_dump($html);
		$ajax_status = 1;
		if(empty($tags)){
			$ajax_status = 2;
		}
		
		$json1 = array();
		$json1['ajax_status'] = $ajax_status;
		$json1['html'] = $html;
		
		$this->response->setOutput(json_encode($json1));
		
	}
	
	public function updatenotetime(){
		
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('notes/updatetime');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm2()) {
            
            $tdata = array();
			
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			if($facilities_info['is_master_facility'] == '1'){
				if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
				$facilities_id  = $this->session->data['search_facilities_id']; 
				$facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
				$this->load->model('setting/timezone');
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
				$timezone_name = $timezone_info['timezone_value'];
				}else{
					$facilities_id = $this->customer->getId(); 
					$timezone_name = $this->customer->isTimezone();
				}
			}else{
				$facilities_id = $this->customer->getId(); 
				$timezone_name = $this->customer->isTimezone();
			}
            
			
            $tdata['notes_id'] = $this->request->get['notes_id'];
            $tdata['notetime'] = date('H:i:s',strtotime($this->request->get['notetime']));
            $tdata['facilities_id'] = $facilities_id;
            $tdata['facilitytimezone'] = $timezone_name;
			
			
			$time_id = $this->model_notes_updatetime->updatetime($this->request->post, $tdata);
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
				
			
			if ($facility['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
				$this->db->query($sql122);
			}
			if ($facility['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
				$this->db->query($sql13);
			}
			
			if ($facility['is_enable_add_notes_by'] == '1') {
				if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
			
					$notes_file = $this->session->data['local_notes_file'];
					$outputFolder = $this->session->data['local_image_dir'];
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model('notes/notes');
					$this->model_notes_notes->updateuserpicture($s3file, $notes_id);
					if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverified('2', $notes_id);
					}
			
					if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverified('1', $notes_id);
					}
			
					unlink($this->session->data['local_image_dir']);
					unset($this->session->data['username_confirm']);
					unset($this->session->data['local_image_dir']);
					unset($this->session->data['local_image_url']);
					unset($this->session->data['local_notes_file']);
				}
			}
          
            $this->session->data['success_update_form_2'] = 'Note time updated';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
           
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
        
        
        $url2 = "";
        
        if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
            $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        $config_admin_limit1 = $this->config->get('config_front_limit');
        if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
            $config_admin_limit = $config_admin_limit1;
        } else {
            $config_admin_limit = "50";
        }
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        $data = array(
                'searchdate' => date('m-d-Y'),
                'searchdate_app' => '1',
                'facilities_id' => $this->customer->getId()
        );
        
        $this->load->model('notes/notes');
        $notes_total = $this->model_notes_notes->getTotalnotess($data);
        $pagenumber_all = ceil($notes_total / $config_admin_limit);
        
        if ($pagenumber_all != null && $pagenumber_all != "") {
            if ($pagenumber_all > 1) {
                $url2 .= '&page=' . $pagenumber_all;
            }
        }
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        if ($this->request->get['update_notetime'] != null && $this->request->get['update_notetime'] != "") {
			$url2 .= '&update_notetime=' . $this->request->get['update_notetime'];
		}
		if ($this->request->get['notetime'] != null && $this->request->get['notetime'] != "") {
			$url2 .= '&notetime=' . $this->request->get['notetime'];
		}
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updatenotetime', '' . $url2, 'SSL'));
       
        
        if (isset($this->session->data['pagenumber'])) {
            $this->data['pagenumber'] = $this->session->data['pagenumber'];
        } else {
            $this->data['pagenumber'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->session->data['success_update_form_2'])) {
            $this->data['success_update_form_2'] = $this->session->data['success_update_form_2'];
            
            unset($this->session->data['success_update_form_2']);
        } else {
            $this->data['success_update_form_2'] = '';
        }
        
        if (isset($this->error['select_one'])) {
            $this->data['error_select_one'] = $this->error['select_one'];
        } else {
            $this->data['error_select_one'] = '';
        }
        
        if (isset($this->error['notes_pin'])) {
            $this->data['error_notes_pin'] = $this->error['notes_pin'];
        } else {
            $this->data['error_notes_pin'] = '';
        }
        
        if (isset($this->error['highlighter_id'])) {
            $this->data['error_highlighter_id'] = $this->error['highlighter_id'];
        } else {
            $this->data['error_highlighter_id'] = '';
        }
        
        if (isset($this->error['user_id'])) {
            $this->data['error_user_id'] = $this->error['user_id'];
        } else {
            $this->data['error_user_id'] = '';
        }
        
        if (isset($this->request->post['select_one'])) {
            $this->data['select_one'] = $this->request->post['select_one'];
        } else {
            if ($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != "") {
                $config_default_sign = '1'; // $this->config->get('config_default_sign');
            } else {
                $config_default_sign = '2';
            }
            $this->data['select_one'] = $config_default_sign;
        }
        
        if ($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != "") {
            $this->data['default_sign'] = '1'; // $this->config->get('config_default_sign');
        } else {
            $this->data['default_sign'] = '2';
        }
        
        if (isset($this->request->post['notes_pin'])) {
            $this->data['notes_pin'] = $this->request->post['notes_pin'];
        } elseif (! empty($notes_info)) {
            $this->data['notes_pin'] = $notes_info['notes_pin'];
        } else {
            $this->data['notes_pin'] = '';
        }
        
        $this->data['local_image_url'] = $this->session->data['local_image_url'];
        
        if (isset($this->request->post['user_id'])) {
            $this->data['user_id'] = $this->request->post['user_id'];
        } elseif (! empty($notes_info)) {
            $this->data['user_id'] = $notes_info['user_id'];
        } elseif (! empty($this->session->data['username_confirm'])) {
            $this->data['user_id'] = $this->session->data['username_confirm'];
        }else {
            $this->data['user_id'] = '';
        }
        
        // $this->load->model('setting/tags');
        // $tag_info =
        // $this->model_setting_tags->getTag($this->request->get['tags_id']);
        
        if (isset($this->request->post['emp_tag_id'])) {
            $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id'] = $tag_info['emp_tag_id'];
        } else {
            $this->data['emp_tag_id'] = '';
        }
        
        if (isset($this->request->post['tags_id'])) {
            $this->data['tags_id'] = $this->request->post['tags_id'];
        } elseif (! empty($tag_info)) {
            $this->data['tags_id'] = $tag_info['tags_id'];
        } else {
            $this->data['tags_id'] = '';
        }
        
        if (isset($this->request->post['emp_tag_id_2'])) {
            $this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id_2'] = $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['emp_tag_id_2'] = '';
        }
        
        if (isset($this->request->post['comments'])) {
            $this->data['comments'] = $this->request->post['comments'];
        } else {
            $this->data['comments'] = '';
        }
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
	}
	
	
	public function allnotetime(){
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		if ($this->request->get ['notes_id'] != '' && $this->request->get ['notes_id'] != null) {
			$notes_id = $this->request->get ['notes_id'];
		}
		
		$uptimes = array();
		$this->load->model ( 'notes/notes' );
		$uptimes = $this->model_notes_notes->getupdatetimes ($notes_id);
		
		
		$uptime_original = $this->model_notes_notes->getupdatetimesa ($notes_id);
		if(!empty($uptime_original)){
			$this->data['original_notetime'] = date ( 'h:i A', strtotime ($uptime_original['original_notetime']));
		}
		
		
		
		$this->data ['alltimes'] = array ();
		foreach ( $uptimes as $uptime ) {
			
			$this->data ['alltimes'] [] = array (
					'time_id' => $uptime ['time_id'],
					'notetime' => date ( 'h:i A', strtotime ( $uptime ['notetime'] ) ),
					'user_id' => $uptime ['user_id'],
					'notes_pin' => $uptime ['notes_pin'],
					'user_file' => $uptime ['user_file'],
					'is_user_face' => $uptime ['is_user_face'],
					'signature' => $uptime ['signature'],
					'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $uptime ['date_added'] ) ),
			);
			
		}
	
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/alltimes_list.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function allusers() {
		$json = array ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get['userids'];
		}
		
		
		$this->load->model ( 'user/user' );
		if ($this->request->get ['userids'] != '' && $this->request->get ['userids'] != null) {
			$userids = $this->request->get ['userids'];
			$this->data['userids']=$userids;
			
		}
		
		
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
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
			// $facilities_id = $this->customer->getId();
		}
		

	
		$this->load->model ( 'user/user' );
		$alUsers = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['users'] = array ();
		foreach ( $alUsers as $user ) {
				$this->data ['users'] [] = array (
					'user_id' => $user ['user_id'],
					'username' => $user ['username'],
					'firstname' => $user ['firstname'],
					'lastname' => $user ['lastname'],
					'user_href' => $this->url->link ( 'notes/notes/allusers', '' . '&user_id=' . $user ['user_id'], 'SSL' ),
				);
				
			}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/allusers', '' . $url2, 'SSL' );

		$this->data['ajaxreusersurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxAllusers', '' . $url2, 'SSL'));	

			$this->data ['reseturl'] = str_replace('&amp;', '&',$this->url->link ( 'notes/notes/allusers', '' , 'SSL' ));
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/allusers.php';
		$this->children = array (
				'common/headerpopup' 
		);


		$this->response->setOutput ( $this->render () );
		
	}

	public function ajaxAllusers() {
		$json = array ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
			$url2 .= '&userids=' . $this->request->get['userids'];
		}
		
		
		$this->load->model ( 'user/user' );
		if ($this->request->get ['userids'] != '' && $this->request->get ['userids'] != null) {
			$userids = $this->request->get ['userids'];
			$this->data['userids']=$userids;
			
		}
		
		
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
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
			// $facilities_id = $this->customer->getId();
		}

		if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


        $config_admin_limit = 40;
		
		
			$dataaaa = array (
				'facilities' => $this->customer->getId (),
				'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
				'status' => 1,				
				'all_record' => 1,
				//'role_call' => '1',
				//'is_master' => 1,
				'sort' => 'firstname',
				'order' => 'ASC',
				'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
			);	
		

	
		$this->load->model ( 'user/user' );
		$alUsers = $this->model_user_user->getAjaxUsersByFacility ( $dataaaa);
		
		
		foreach ( $alUsers as $user ) {
				$json [] = array (
					'user_id' => $user ['user_id'],
					'username' => $user ['username'],
					'firstname' => $user ['firstname'],
					'lastname' => $user ['lastname'],
					'user_href' => $this->url->link ( 'notes/notes/allusers', '' . '&user_id=' . $user ['user_id'], 'SSL' ),
				);
				
			}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/allusers', '' . $url2, 'SSL' );


		$this->data['ajaxreusersurl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/ajaxAllusers', '' . $url2, 'SSL'));	

		
		
	$template = new Template();
		$template->data['users'] = $json;
		$template->data['config_task_deleted_time'] = $this->config->get('config_task_deleted_time');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/notification/ajaxallusers.php')) {
			$html = $template->fetch($this->config->get('config_template') . '/template/notification/ajaxallusers.php');
		} 
		
		//var_dump($html);
		$ajax_status = 1;
		if(empty($alUsers)){
			$ajax_status = 2;
		}
		
		$json1 = array();
		$json1['ajax_status'] = $ajax_status;
		$json1['html'] = $html;
		
		$this->response->setOutput(json_encode($json1));
		
	}




	//Search Facility

    public function searchFacility() {


		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );		
		
		$this->load->model ( 'user/user' );			
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} 
		
		$data = array (				
				'facilities_id' => $facilities_id,			
				'start' => 0,
				'limit' => $limit 
		);
		
		$totalfacilities = $this->model_facilities_facilities->getfacilitiess ( $data );


		//var_dump($totalfacilities);
		//die;
		
		foreach ($totalfacilities as $facility ) {
			$json [] = array (
					'facility' => $facility ['facility'],
					'facilities_id' => $facility ['facilities_id']
					
			);
		}
		
		
		$this->response->setOutput ( json_encode ( $json ) );
	}


	//search sub facility

	 public function searchSubFacility() {


		$json = array ();
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );		
		
		$this->load->model ( 'user/user' );			
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = CONFIG_LIMIT;
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		}else{

             $facilities_id=$this->customer->getId ();

		} 

		$this->load->model ( 'facilities/facilities' );
		
		$totalfacilities = $this->model_facilities_facilities->getSubfacilitiess ( $facilities_id );
		
		
		foreach ($totalfacilities as $facility ) {
			$json [] = array (
					'facility' => $facility ['facility'],
					'facilities_id' => $facility ['facilities_id']
					
			);
		}
		
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	
	public function attachment(){
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );

		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/notes/attachment', '' . $url2, 'SSL' );
		$this->data ['alltags_url'] = $this->url->link ( 'notes/notes/alltags', '' . $url2, 'SSL' );
	

		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm3 ()) {
			
			
			$this->session->data ['attachments'] =  $this->request->post['attachments'];
			//$this->session->data ['attachment_files'] = $this->request->files;
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			

			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );


			 if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url2 .= '&savenotes=1';
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
			} else {
				$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert2', '' . $url2, 'SSL' ) );
			}
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
		
		if (isset ( $this->error ['file_name'] )) {
			$this->data ['error_file_name'] = $this->error ['file_name'];
		} else {
			$this->data ['error_file_name'] = '';
		}
		if (isset ( $this->error ['file_form'] )) {
			$this->data ['error_file_form'] = $this->error ['file_form'];
		} else {
			$this->data ['error_file_form'] = '';
		}
		
		if (isset ( $this->request->post ['attachments'] )) {
			$this->data ['attachments'] = $this->request->post ['attachments'];
		} else {
			$this->data ['attachments'] = array();
		}

		$this->load->model ('notes/notes');
		$casess = $this->model_notes_notes->getTagcasses();

		foreach($casess as $case){

				$this->data ['classifications'][]  = array(
					'case_id' => $case['case_id'],
					'name' => $case['name'],
				);		

		}


		$this->load->model ('form/form');
			
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $this->customer->getId ();
		
		$custom_forms = $this->model_form_form->getforms ( $data3 );
		$allfroms = ARRAY();

		foreach($custom_forms as $forms){

				$this->data ['allfroms'][]  = array(
					'forms_id' => $forms['forms_id'],
					'form_name' => $forms['form_name']
				);		

		}

		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/attachment.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
		
	}

		protected function validateForm3() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}

 		if ($this->request->post ['attachments']['file_name'] == null && $this->request->post  ['attachments']['file_name'] == "") {
			$this->error ['file_name'] = "File Name is required";
		}
		
		if ($this->request->post ['attachments']['file_type'] == "Form") {
			if ($this->request->post ['attachments']['form'] == null && $this->request->post  ['attachments']['form'] == "") {
			$this->error ['file_form'] = "Form Type is required";
			}
		}

		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
}
?>