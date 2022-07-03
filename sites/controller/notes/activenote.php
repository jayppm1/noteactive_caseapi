<?php
class Controllernotesactivenote extends Controller {
	private $error = array ();
	public function index() {
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
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
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm2 ()) {
			// var_dump($this->request->post);
			// echo "<hr>";
			
			$facilities_id = $this->customer->getId ();
			$facilitytimezone = $this->customer->isTimezone ();
			
			$this->load->model ( 'notes/activenote' );
			$tdata = array ();
			$tdata ['keyword_id'] = $this->request->get ['keyword_id'];
			$tdata ['facilities_id'] = $facilities_id;
			$tdata ['facilitytimezone'] = $facilitytimezone;
			$notes_id = $this->model_notes_activenote->addactivenote ( $this->request->post, $tdata );
			
			// die;
			
			$this->data ['notes_id'] = $notes_id;
			
			$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
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
		
		if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
		}

		if($this->request->post['facilityids'] != null && $this->request->post['facilityids'] != ""){
				$url2 .= '&facilityids=' . $this->request->post ['facilityids'];
			}
			
			if($this->request->post['locationids'] != null && $this->request->post['locationids'] != ""){
				$url2 .= '&locationids=' . $this->request->post ['locationids'];
			}

			if ($this->request->post['userids'] != null && $this->request->post['userids'] != "") {
			$url2 .= '&userids=' . $this->request->post['tagsids'];
		}
			
			if($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != ""){
				$url2 .= '&tagsids=' . $this->request->post ['tagsids'];
			}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/activenote', '' . $url2, 'SSL' );
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
		
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			$this->data ['monitor_time'] [] = $keywordData2 ['monitor_time'];
			
			if ($keywordData2 ['monitor_time'] == '1') {
				
				$a21 = array ();
				$a21 ['is_monitor_time'] = '1';
				$a21 ['facilities_id'] = $this->customer->getId ();
				
				$active_note_infos = $this->model_notes_notes->getNotebyactivenotes ( $a21 );
				
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
		
		// var_dump($this->data['monitor_time']);
		// var_dump($this->data['monitortimes']);
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm2() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
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
		
		$this->load->model ( 'setting/keywords' );
		$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
		
		if ($keywordData2 ['monitor_time'] == '1') {
			if ($this->request->post ['override_monitor_time_user_id_checkbox'] != '1') {
				if ($keywordData2 ['end_relation_keyword'] == '1') {
					$a3 = array ();
					$a3 ['keyword_id'] = $keywordData2 ['relation_keyword_id'];
					$a3 ['user_id'] = $this->request->post ['user_id'];
					$a3 ['username'] = $this->request->post ['username'];
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
	public function mime_content_type($filename) {
		try {
			
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
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices mime_content_type' 
			);
			$this->model_activity_activity->addActivity ( 'app_mime_content_type', $activity_data2 );
		}
	}
	public function timeselection() {
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm23 ()) {
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			
			$this->data ['keyword_name'] = $keywordData2 ['keyword_name'];
			$this->data ['keyword_id'] = $keywordData2 ['keyword_id'];
			$this->data ['keyword_image'] = $keywordData2 ['keyword_image'];
			
			$this->data ['keywordimagetext'] = $keywordData2 ['keyword_name'] . $this->request->post ['taskTime'] . ' | ';
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
		}
		
		if (isset ( $this->request->post ['taskTime'] )) {
			$this->data ['taskTime'] = $this->request->post ['taskTime'];
		} else {
			$this->data ['taskTime'] = '';
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
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}

		if($this->request->post['facilityids'] != null && $this->request->post['facilityids'] != ""){
				$url2 .= '&facilityids=' . $this->request->post ['facilityids'];
			}
			
			if($this->request->post['locationids'] != null && $this->request->post['locationids'] != ""){
				$url2 .= '&locationids=' . $this->request->post ['locationids'];
			}

			if ($this->request->post['userids'] != null && $this->request->post['userids'] != "") {
			$url2 .= '&userids=' . $this->request->post['tagsids'];
		}
			
			if($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != ""){
				$url2 .= '&tagsids=' . $this->request->post ['tagsids'];
			}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/activenote/timeselection', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/timeselection.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateForm23() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['taskTime'] == '') {
			$this->error ['warning'] = "This is required field";
		}
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function facilitysection() {
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm234 ()) {
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			
			$this->data ['keyword_name'] = $keywordData2 ['keyword_name'];
			$this->data ['keyword_id'] = $keywordData2 ['keyword_id'];
			$this->data ['keyword_image'] = $keywordData2 ['keyword_image'];
			
			$multifield = '';
			$mchk = array();
			
			foreach ( $this->request->post ['facilityids'] as $value ) {
				foreach ( $value as $v ) {
					//if ($v ['multichk'] != NULL && $v ['multichk'] != "") {
					if($v['chkvalue'] != NULL && $v['chkvalue'] != ""){
						$multifield .= ' | ' . $v ['facility'];
						
						$mchk[] = $v ['facilities_id'];
					}
				}
			}
			
			$abdc = array_unique($mchk);
			
			$facilityid22s = implode(",",$abdc);
			
			
			$this->data ['facilityids111'] = $facilityid22s;
			
			$this->data ['keywordimagetext'] = $keywordData2 ['keyword_name']; 
			
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
		}
		
		
		if ($this->request->get ['keyword_id'] != NULL && $this->request->get ['keyword_id'] != "") {
			$this->load->model ( 'setting/keywords' );
			$keyworddetails = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			if($keyworddetails['monitor_time'] == '7'){
				if($keyworddetails['facility_type'] == '1'){
					$this->load->model('facilities/facilities');
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
					
					$ddss = array();
					if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
						$this->data['is_master_facility']  =  '1' ; 
						$ddss[] = $result['notes_facilities_ids'];
					}else{
						$this->data['is_master_facility']  =  '2' ; 
					}
					
					$ddss[] = $this->customer->getId();
					$sssssdd = implode(",",$ddss);
						
					$dataaaa = array();
					$dataaaa['facilities'] = $sssssdd;
					$mfacilities =  $this->model_facilities_facilities->getfacilitiess($dataaaa);
						
					$masterfacilities = array();
					foreach($mfacilities as $mfacility){
						$masterfacilities[] = array(
						  'facility' => $mfacility['facility'],
						  'facilities_id' => $mfacility['facilities_id'],
						);
						
					}
						
					$this->data['masterfacilities'] = $masterfacilities;
				}else{
					
					
					$this->load->model ( 'facilities/facilities' );
					
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
					
					$fdata = array();
					$fdata['status'] = 1;
					$fdata['customer_key'] =$result['customer_key'];
					$results = $this->model_facilities_facilities->getfacilitiess ( $fdata );
					
					foreach ( $results as $result ) {
						
						$this->data ['masterfacilities'] [] = array (
								'facilities_id' => $result ['facilities_id'],
								'facility' => $result ['facility'] 
						);
					}
					
				}
			}
			$this->data ['keyword_id'] = $this->request->get ['keyword_id'];
		}
		
		
		
		if (isset ( $this->request->post ['facilityids'] )) {
			$this->data ['facilityids'] = $this->request->post ['facilityids'];
		} else {
			$this->data ['facilityids'] = '';
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
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['last_notesID'] != null && $this->request->get ['last_notesID'] != "") {
			$url2 .= '&last_notesID=' . $this->request->get ['last_notesID'];
		}

		if($this->request->post['facilityids'] != null && $this->request->post['facilityids'] != ""){
				$url2 .= '&facilityids=' . $this->request->post ['facilityids'];
			}
			
			if($this->request->post['locationids'] != null && $this->request->post['locationids'] != ""){
				$url2 .= '&locationids=' . $this->request->post ['locationids'];
			}

			if ($this->request->post['userids'] != null && $this->request->post['userids'] != "") {
			$url2 .= '&userids=' . $this->request->post['tagsids'];
		}
			
			if($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != ""){
				$url2 .= '&tagsids=' . $this->request->post ['tagsids'];
			}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/activenote/facilitysection', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/facilitysection.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	
	protected function validateForm234() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}


	public function resetData() { 

		if(!isset($_GET['newvalue'])) { 
			unset($this->session->data [$_GET['name']]);
		} else { 
			if($_GET['newvalue']==''){ 
				unset($this->session->data [$_GET['name']]);
			} else { 
				$this->session->data [$_GET['name']] = $_GET['newvalue'];
			}
		}

		$this->session->data [$_GET['name']] .= $this->session->data [$_GET['tempname']];
		unset($this->session->data [$_GET['tempname']]);
		//echo $this->session->data [$_GET['name']].'-'.$this->session->data [$_GET['newvalue']].'-'.$this->session->data [$_GET['tempname']];
	}
	
	
	
	public function multiple() {

		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		unset($this->session->data ['multi_keyword_file']);
		unset($this->session->data ['facilityids222']);
		unset($this->session->data ['locations222']);
		unset($this->session->data ['tagsids222']);
		unset($this->session->data ['userids222']);
		
		$this->data['form_outputkey'] = $this->formkey->outputKey();

		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm24 ()) { 
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			
			$this->data ['keyword_name'] = $keywordData2 ['keyword_name'];
			$this->data ['keyword_id'] = $keywordData2 ['keyword_id'];
			$this->data ['keyword_image'] = $keywordData2 ['keyword_image'];
			
			$multifield = '';
			$multifieldId = '';
			$mchk = '';
			$mchk2 = '';

			//echo '<pre>'; print_r($this->request->post ['multi']); echo '</pre>'; // die;
			
			$userarr = array();

			foreach ( $this->request->post ['multi'] as $value ) {
				
				foreach ( $value as $v ) {
					
					
					
					if($v['chkvalue'] != NULL && $v['chkvalue'] != "") { 

						if($v['chkvalue'] && !empty($v['autocomplete']) || !empty($v['default_value'])) { 
							//if($v['name'] == "Users" || $v['name'] == "Shift" || empty($v['autocomplete'])) { 
							if(empty($v['autocomplete'])) { 
								$multifield .= ' | ' . $v ['name'];
							}
						}
						$multifieldId .= ' | ' . $v ['name'];
						
						if ($v ['action'] == '3') { 
							if($v['chkvalue'] && !empty($v['autocomplete'])) { 
								if($v['name'] == "Users" || $v['name'] == "Shift" || empty($v['autocomplete'])) { 
									$multifield .= ' - ' . $v ['multivalue'] . ' ' . $v ['measurement'];
								}
							}
							$multifieldId .= ' - ' . $v ['multivalue'] . ' ' . $v ['measurement'];
						}
					}

					if(!empty($v['default_value']) && $v['chkvalue'] != "") { 
						$multifield .= ' | '.$v['default_value'];
						$multifieldId .= ' | '.$v['default_value'];
					}

					
					if(!empty($v['autocomplete_type']) && $v['autocomplete_type']=='user' && !empty($v['autocomplete']) && $v['chkvalue']){
						//$userids222 = implode(',',$v['autocomplete']);
						//$useridsName = implode(',',$v['autocomplete']);
						$userids222 = '';
						$useridsInnerLoop = '';
						$useridsName = '';

						//Code to extract field name
						foreach($v['autocomplete'] as $dataKey=> $dataValue) { 
							$extractSelected = explode('#',$dataValue);
							if(COUNT($extractSelected)>0) { 
								$userids222 = isset($extractSelected[0]) ? $extractSelected[0] : '';
								$useridsName = isset($extractSelected[1]) ? ucwords(strtolower(str_replace("`", "", $extractSelected[1]))) : '';
								
								if($dataKey==0) { 
									$multifield .= ' | '.$useridsName;
									$multifieldId .= ' | '.$userids222;
									$useridsInnerLoop .= $userids222;
								} else {
									$multifield .= $useridsName;
									$multifieldId .= $userids222;
									$useridsInnerLoop .= $userids222;
								}
								
								if(COUNT($v['autocomplete'])!=($dataKey+1)) { 
									$multifield .= ', ';
									$multifieldId .= ',';
									$userids222 .= ',';
									$useridsInnerLoop .= ',';
								}
								//echo $dataKey.' - '.$useridsInnerLoop.' - '.$useridsName;
							} else {
								$multifield .= ' | '.$useridsName;
								$multifieldId .= ' | '.$userids222;
								$useridsInnerLoop .= $userids222;
							} 
							
						}
						$userids222 = $useridsInnerLoop;
						$this->session->data ['userids222'] = $userids222;
						//$multifield .= ' | '.$useridsName;
						if(!empty($this->session->data ['all_userids'])) { 
							$this->session->data ['all_userids'] .= ",";
						}
						$this->session->data ['all_userids'] .= $userids222.',';
						$this->data ['all_userids'] .= $userids222.',';
					}

					if(!empty($v['autocomplete_type']) && $v['autocomplete_type']=='client' && !empty($v['autocomplete']) && $v['chkvalue']){

						//$tagsids222 = implode(',',$v['autocomplete']);
						//$tagsidsName = implode(',',$v['autocomplete']);
						$tagsids222 = '';
						$tagsidsInnerLoop = '';
						$tagsidsName = '';

						//Code to extract field name
						foreach($v['autocomplete'] as $dataKey=> $dataValue) { 
							$extractSelected = explode('#',$dataValue);
							if(COUNT($extractSelected)>0) { 
								$tagsids222 = isset($extractSelected[0]) ? $extractSelected[0] : '';
								$tagsidsName = isset($extractSelected[1]) ? ucwords(strtolower(str_replace("`", "", $extractSelected[1]))) : '';
								
								if($dataKey==0) { 
									//$multifield .= ' | '.$tagsidsName;
									$multifieldId .= ' | '.$tagsids222;
									$tagsidsInnerLoop .= $tagsids222;
								} else {
									//$multifield .= $tagsidsName;
									$multifieldId .= $tagsids222;
									$tagsidsInnerLoop .= $tagsids222;
								}
								
								if(COUNT($v['autocomplete'])!=($dataKey+1)) { 
									//$multifield .= ', ';
									$multifieldId .= ',';
									$tagsids222 .= ',';
									$tagsidsInnerLoop .= ',';
								}
								//echo $dataKey.' - '.$tagsidsInnerLoop.' - '.$tagsidsName;
							} else {
								//$multifield .= ' | '.$tagsidsName;
								$multifieldId .= ' | '.$tagsids222;
								$tagsidsInnerLoop .= $tagsids222;
							} 
							
						}
						$tagsids222 = $tagsidsInnerLoop;
						
						$this->session->data ['tagsids222'] = $tagsids222;
						//$multifield .= ' | '.$tagsids222;
						if(!empty($this->session->data ['all_tagsids'])) {
							$this->session->data ['all_tagsids'] .= ",";
						}
						$this->session->data ['all_tagsids'] .= $tagsids222.',';
						$this->data ['all_tagsids'] .= $tagsids222.',';
					}

					if(!empty($v['autocomplete_type']) && $v['autocomplete_type']=='facility' && !empty($v['autocomplete']) && $v['chkvalue']){
						//$facilityids222 = implode(',',$v['autocomplete']);
						//$facilityidsName = implode(',',$v['autocomplete']);
						$facilityids222 = '';
						$facilityidsInnerLoop = '';
						$facilityidsName = '';

						//Code to extract field name
						foreach($v['autocomplete'] as $dataKey=> $dataValue) { 
							$extractSelected = explode('#',$dataValue);
							if(COUNT($extractSelected)>0) { 
								$facilityids222 = isset($extractSelected[0]) ? $extractSelected[0] : '';
								$facilityidsName = isset($extractSelected[1]) ? ucwords(strtolower(str_replace("`", "", $extractSelected[1]))) : '';
								
								if($dataKey==0) { 
									//$multifield .= ' | '.$facilityidsName;
									$multifieldId .= ' | '.$facilityids222;
									$facilityidsInnerLoop .= $facilityids222;
								} else {
									//$multifield .= $facilityidsName;
									$multifieldId .= $facilityids222;
									$facilityidsInnerLoop .= $facilityids222;
								}
								
								if(COUNT($v['autocomplete'])!=($dataKey+1)) { 
									//$multifield .= ', ';
									$multifieldId .= ',';
									$facilityids222 .= ',';
									$facilityidsInnerLoop .= ',';
								}
								//echo $dataKey.' - '.$facilityidsInnerLoop.' - '.$facilityidsName;
							} else {
								//$multifield .= ' | '.$facilityidsName;
								$multifieldId .= ' | '.$facilityids222;
								$facilityidsInnerLoop .= $facilityids222;
							} 
							
						}
						$facilityids222 = $facilityidsInnerLoop;
						
						$this->session->data ['facilityids222'] = $facilityids222;
						//$multifield .= ' | '.$facilityids222;
						if(!empty($this->session->data ['all_facilityids'])) {
							$this->session->data ['all_facilityids'] .= ",";
						}
						$this->session->data ['all_facilityids'] .= $facilityids222.',';
						$this->data ['all_facilityids'] .= $facilityids222.',';
					}

					if(!empty($v['autocomplete_type']) && $v['autocomplete_type']=='location' && !empty($v['autocomplete']) && $v['chkvalue']){
				        //$locations222 = implode(',',$v['autocomplete']);
						//$locationsName = implode(',',$v['autocomplete']);
						$locations222 = '';
						$locationsInnerLoop = '';
						$locationsName = '';

						//Code to extract field name
						foreach($v['autocomplete'] as $dataKey=> $dataValue) { 
							$extractSelected = explode('#',$dataValue);
							if(COUNT($extractSelected)>0) { 
								$locations222 = isset($extractSelected[0]) ? $extractSelected[0] : '';
								$locationsName = isset($extractSelected[1]) ? ucwords(strtolower(str_replace("`", "", $extractSelected[1]))) : '';
								
								if($dataKey==0) { 
									$multifield .= ' | '.$locationsName;
									$multifieldId .= ' | '.$locations222;
									$locationsInnerLoop .= $locations222;
								} else {
									$multifield .= $locationsName;
									$multifieldId .= $locations222;
									$locationsInnerLoop .= $locations222;
								}
								
								if(COUNT($v['autocomplete'])!=($dataKey+1)) { 
									$multifield .= ', ';
									$multifieldId .= ',';
									$locations222 .= ',';
									$locationsInnerLoop .= ',';
								}
								//echo $dataKey.' - '.$locationsInnerLoop.' - '.$locationsName;
							} else {
								$multifield .= ' | '.$locationsName;
								$multifieldId .= ' | '.$locations222;
								$locationsInnerLoop .= $locations222;
							} 
							
						}
						$locations222 = $locationsInnerLoop;

						$this->session->data ['locations222'] = $locations222;
						//$multifield .= ' | '.$locations222;
						if(!empty($this->session->data ['all_locations'])) {
							$this->session->data ['all_locations'] .= ",";
						}
						$this->session->data ['all_locations'] .= $locations222.',';
						$this->data ['all_locations'] .= $locations222.',';
					}

					if(!empty($v['autocomplete_type']) && $v['autocomplete_type']=='shift' && !empty($v['autocomplete']) && $v['chkvalue']){
				        $this->load->model ( 'setting/shift' );
				        $adata ['status'] = 1;
						$shifts = $this->model_setting_shift->getshifts ( $adata );
						//echo '<pre>'; print_r($fromdatas); echo '</pre>'; //die;

						foreach($shifts AS $shift_row){
							
							if(in_array($shift_row['shift_id'],$v['autocomplete'])){
								$shiftarr[] = $shift_row['shift_name'];
							}
						}
						//$multifield .= ' | '.@implode(',',$shiftarr);
						$shiftarr=array();

						$shifts222 = '';
						$shiftsInnerLoop = '';
						$shiftsName = '';

						//Code to extract field name
						foreach($v['autocomplete'] as $dataKey=> $dataValue) { 
							$extractSelected = explode('#',$dataValue);
							if(COUNT($extractSelected)>0) { 
								$shifts222 = isset($extractSelected[0]) ? $extractSelected[0] : '';
								$shiftsName = isset($extractSelected[1]) ? ucwords(strtolower(str_replace("`", "", $extractSelected[1]))) : '';
								
								if($dataKey==0) { 
									$multifield .= ' | '.$shiftsName;
									$multifieldId .= ' | '.$shifts222;
									$shiftsInnerLoop .= $shifts222;
								} else {
									$multifield .= $shiftsName;
									$multifieldId .= $shifts222;
									$shiftsInnerLoop .= $shifts222;
								}
								
								if(COUNT($v['autocomplete'])!=($dataKey+1)) { 
									$multifield .= ', ';
									$multifieldId .= ',';
									$shifts222 .= ',';
									$shiftsInnerLoop .= ',';
								}
								//echo $dataKey.' - '.$shiftsInnerLoop.' - '.$shiftsName;
							} else {
								$multifield .= ' | '.$shiftsName;
								$multifieldId .= ' | '.$shifts222;
								$shiftsInnerLoop .= $shifts222;
							} 
							
						}
						$shifts222 = $shiftsInnerLoop;

						$this->session->data ['shifts222'] = $shifts222;
						//$multifield .= ' | '.$shifts222;
						if(!empty($this->session->data ['all_shifts'])) {
							$this->session->data ['all_shifts'] .= ",";
						}
						$this->session->data ['all_shifts'] .= $shifts222.',';
						$this->data ['all_shifts'] .= $shifts222.',';
					}
				}
			}

			//echo  $multifield; die;


			
			$this->data ['multi_keyword_file'] = serialize ( $this->request->post ['multi'] );
			// var_dump($this->data['multi_keyword_file']);
			
			$this->session->data ['multi_keyword_file'] = $this->request->post ['multi'];

			if($this->session->data ['all_facilityids'][-1]==",") { 
				$this->session->data ['all_facilityids'] = substr($this->session->data ['all_facilityids'], 0, -1);
			}
			$this->data ['all_facilityids'] = $this->session->data ['all_facilityids'];

			if($this->session->data ['all_locations'][-1]==",") { 
				$this->session->data ['all_locations'] = substr($this->session->data ['all_locations'], 0, -1);
			}
			$this->data ['all_locations'] = $this->session->data ['all_locations'];

			if($this->session->data ['all_tagsids'][-1]==",") { 
				$this->session->data ['all_tagsids'] = substr($this->session->data ['all_tagsids'], 0, -1);
			}
			$this->data ['all_tagsids'] = $this->session->data ['all_tagsids'];

			if($this->session->data ['all_userids'][-1]==",") { 
				$this->session->data ['all_userids'] = substr($this->session->data ['all_userids'], 0, -1);
			}
			$this->data ['all_userids'] = $this->session->data ['all_userids'];

			if($this->session->data ['all_shifts'][-1]==",") { 
				$this->session->data ['all_shifts'] = substr($this->session->data ['all_shifts'], 0, -1);
			}
			$this->data ['all_shifts'] = $this->session->data ['all_shifts'];

			/*
			echo '<pre>'; print_r($this->session->data ['multi_keyword_file']); echo '</pre>'; //die;

			if (! empty ( $this->session->data ['multi_keyword_file'] )) {
				

				foreach ( $this->session->data ['multi_keyword_file'] as $key => $multivalue ) {
					
					foreach ( $multivalue as $v ) {
						
						if ($v ['chkvalue'] != NULL && $v ['chkvalue'] != "") {

							//echo '<pre>'; print_r($v); echo '</pre>'; //die;

							if($v ['multivalue'] != NULL && $v ['multivalue'] != ''){
								$multivalue = $v ['multivalue'];
								$autocomplete_type = 'multivalue';
							}else{
								$multivalue = @implode(',',$v ['autocomplete']);
								$autocomplete_type = $v ['autocomplete_type'];
							}

							$sqla = "INSERT INTO `" . DB_PREFIX . "notes_by_multikeyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $key . "', facilities_id = '" . $facilities_id . "', date_added = '" . $noteDate . "', date_updated = '" . $noteDate . "', action = '" . $this->db->escape ( $v ['action'] ) . "', name = '" . $this->db->escape ( $v ['name'] ) . "', type = '" . $this->db->escape ( $v ['measurement'] ) . "', value = '" . $this->db->escape ( $multivalue ) . "', autocomplete_type = '" . $autocomplete_type . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
							echo '<br><br>'.$sqla; 
							//$this->db->query ( $sqla );
						}
					}
				}
			}

			die;*/

			$multifieldId = !empty($multifieldId) ? trim($multifieldId) : '';
			$multifield = !empty($multifield) ? trim($multifield) : '';
			$this->data ['keywordimagetext'] = $keywordData2 ['keyword_name'] . ' | ' . $multifieldId;
			$this->data ['keywordForMethod'] = $keywordData2 ['keyword_name'] . ' | ' . $multifield;
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			//die;
		}
		
		if ($this->request->get ['keyword_id'] != NULL && $this->request->get ['keyword_id'] != "") {
			$this->load->model ( 'setting/keywords' );
			$keyworddetails = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			$this->data ['multiplesrow'] = unserialize ( $keyworddetails ['multiples_module'] );
			$this->data ['keyword_id'] = $this->request->get ['keyword_id'];
		}
		
		if (isset ( $this->request->post ['multi'] )) {
			$this->data ['multi'] = $this->request->post ['multi'];
		} else {
			$this->data ['multi'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['multierror'] )) {
			$this->data ['error_multierror'] = $this->error ['multierror'];
		} else {
			$this->data ['error_multierror'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
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
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}

		//echo '<pre>'; print_r($this->data ['facilityids111']); echo '</pre>'; //die;

		$this->data ['action2'] = $this->url->link ( 'notes/activenote/multiple', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/multiple.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	protected function validateForm24() {


		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		$mchk = '';
		foreach ( $this->request->post ['multi'] as $key=>$value ) {
			foreach ( $value as $key2=>$v ) {
				
				//var_dump($v['multivalue']);
				if($v['action'] == "3"){
					if($v ['chkvalue'] == '1'){
				//if($v['multivalue'] == "")

				$data=trim($v['multivalue']);

				if($data == "")	
				{
					$this->error ['multierror'][$key][$key2] = "Please fill the value.";
				}
				}
				}

				if($v ['chkvalue'] == '1' && array_key_exists('default_value', $v) && empty($v['default_value'])) { 
					$this->error ['warning'] = "Please enter ".$v ['name'];
					return false;
				} else { 
					//$this->error ['warning'] = '';
				}

				if($v ['chkvalue'] == '1' && array_key_exists('autocomplete_type', $v) && empty($v['autocomplete'])) { 
					$this->error ['warning'] = "Please select ".$v ['name'];
					return false;
				} else { 
					//$this->error ['warning'] = '';
				}
				
				$mchk .= $v ['chkvalue'];
			}
		}
		/*echo $this->error ['warning'];
		echo '<pre>';
		print_r($this->request->post ['multi']);
		echo $mchk; die;*/
		if ($mchk == "" || $mchk == NULL) {
			$this->error ['warning'] = "Please select any value.";
		}
		
		//die;
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	public function clientsin() {
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			
			$this->data ['keyword_name'] = $keywordData2 ['keyword_name'];
			$this->data ['keyword_id'] = $keywordData2 ['keyword_id'];
			$this->data ['keyword_image'] = $keywordData2 ['keyword_image'];
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			$url2 = "";
            if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
                $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
            }
			if ($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != "") {
                $url2 .= '&tagsids=' . $this->request->post['tagsids'];
            }
			
			if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&clienttype=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
				$url2 .= '&clienttype=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL'));
            }
			
			
			$this->session->data ['success2'] = "You have successfully selected clients";
		}
		
		
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		
		
		$this->data ['formsubmit'] = 1;
		
		if ($this->request->get ['tagsids'] != '' && $this->request->get ['tagsids'] != null) {
			$tagsids = $this->request->get ['tagsids'];
			$this->data ['tagsids']=$tagsids;

			
		}
		$ddss = array();
		if ($this->request->get ['keyword_id'] != NULL && $this->request->get ['keyword_id'] != "") {
			$this->load->model ( 'setting/keywords' );
			$keyworddetails = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			if($keyworddetails['monitor_time'] == '8'){
				if($keyworddetails['client_type'] == '1'){
					$this->load->model('facilities/facilities');
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
					if($result['client_facilities_ids'] != null && $result['client_facilities_ids'] != ""){
						$ddss[] = $result['client_facilities_ids'];
					}
					
					$ddss[] = $this->customer->getId();
					$sssssdd = implode(",",$ddss);
					
				}else{
					$this->load->model ( 'facilities/facilities' );
					
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
				
					$customer_key1 = $result['customer_key'];
					
					$this->load->model ( 'customer/customer' );
					$customer_info = $this->model_customer_customer->getcustomerid ( $customer_key1 );
					$customer_key = $customer_info ['activecustomer_id'];
				}
			}
			$this->data ['keyword_id'] = $this->request->get ['keyword_id'];
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
		}
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$data = array (
				'facilities' => $this->request->get ['facilityids'],
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				//'role_call' => '1',
				'medication_inout' => '2',
				//'is_master' => 1,
				'sort' => 'emp_last_name',
				'order' => 'ASC',
			);

		} else {
			
			if($sssssdd != null && $sssssdd != ""){
				$data = array (
					'facilities' => $sssssdd,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					//'role_call' => '1',
					'medication_inout' => '2',
					//'is_master' => 1,
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}else if($customer_key != null && $customer_key != ""){
				$data = array (
					'customer_key' => $customer_key,
					'facilities_id' => $facilities_id,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'is_master' => 1,
					//'role_call' => '1',
					'medication_inout' => '2',
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}else{
				$data = array (
					'facilities_id' => $facilities_id,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'is_master' => 1,
					//'role_call' => '1',
					'medication_inout' => '2',
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}
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
			
			
			$fullname = $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'] . ' ' . $result ['emp_last_name'] . $ssn . $dob;
			
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
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
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
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/activenote/clientsin', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/alltags.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function clientsout() {
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			
			$this->data ['keyword_name'] = $keywordData2 ['keyword_name'];
			$this->data ['keyword_id'] = $keywordData2 ['keyword_id'];
			$this->data ['keyword_image'] = $keywordData2 ['keyword_image'];
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			$url2 = "";
            if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
                $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
            }
			if ($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != "") {
                $url2 .= '&tagsids=' . $this->request->post['tagsids'];
            }
			
			if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&clienttype=2';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
				$url2 .= '&clienttype=2';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL'));
            }
			
			
			$this->session->data ['success2'] = "You have successfully selected clients";
		}
		
		
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		
		
		$this->data ['formsubmit'] = 1;
		
		if ($this->request->get ['tagsids'] != '' && $this->request->get ['tagsids'] != null) {
			$tagsids = $this->request->get ['tagsids'];
			$this->data ['tagsids']=$tagsids;

			
		}
		$ddss = array();
		if ($this->request->get ['keyword_id'] != NULL && $this->request->get ['keyword_id'] != "") {
			$this->load->model ( 'setting/keywords' );
			$keyworddetails = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			if($keyworddetails['monitor_time'] == '8'){
				if($keyworddetails['client_type'] == '1'){
					$this->load->model('facilities/facilities');
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
					if($result['client_facilities_ids'] != null && $result['client_facilities_ids'] != ""){
						$ddss[] = $result['client_facilities_ids'];
					}
					
					$ddss[] = $this->customer->getId();
					$sssssdd = implode(",",$ddss);
					
				}else{
					$this->load->model ( 'facilities/facilities' );
					
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
				
					$customer_key1 = $result['customer_key'];
					
					$this->load->model ( 'customer/customer' );
					$customer_info = $this->model_customer_customer->getcustomerid ( $customer_key1 );
					$customer_key = $customer_info ['activecustomer_id'];
				}
			}
			$this->data ['keyword_id'] = $this->request->get ['keyword_id'];
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
		}
		

		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$data = array (
				'facilities' => $this->request->get ['facilityids'],
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				//'role_call' => '1',
				'medication_inout' => '1',
				//'is_master' => 1,
				'sort' => 'emp_last_name',
				'order' => 'ASC',
			);

		} else {
			
			if($sssssdd != null && $sssssdd != ""){
				$data = array (
					'facilities' => $sssssdd,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					//'role_call' => '1',
					'medication_inout' => '1',
					//'is_master' => 1,
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}else if($customer_key != null && $customer_key != ""){
				$data = array (
					'customer_key' => $customer_key,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'is_master' => 1,
					//'role_call' => '1',
					'medication_inout' => '1',
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}else{
				$data = array (
					'facilities_id' => $facilities_id,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'is_master' => 1,
					'medication_inout' => '1',
					//'role_call' => '1',
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}
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
			
			
			$fullname = $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'] . ' ' . $result ['emp_last_name'] . $ssn . $dob;
			
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
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
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
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/activenote/clientsout', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/alltags.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function clientsdischarge() {
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			$this->load->model ( 'setting/keywords' );
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			
			$this->data ['keyword_name'] = $keywordData2 ['keyword_name'];
			$this->data ['keyword_id'] = $keywordData2 ['keyword_id'];
			$this->data ['keyword_image'] = $keywordData2 ['keyword_image'];
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			$url2 = "";
            if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
                $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
            }
			if ($this->request->post['tagsids'] != null && $this->request->post['tagsids'] != "") {
                $url2 .= '&tagsids=' . $this->request->post['tagsids'];
            }
			
			if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&clienttype=3';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
				$url2 .= '&clienttype=3';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL'));
            }
			
			
			$this->session->data ['success2'] = "You have successfully selected clients";
		}
		
		
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'setting/tags' );
		
		
		$this->data ['formsubmit'] = 1;
		
		if ($this->request->get ['tagsids'] != '' && $this->request->get ['tagsids'] != null) {
			$tagsids = $this->request->get ['tagsids'];
			$this->data ['tagsids']=$tagsids;

			
		}
		$ddss = array();
		if ($this->request->get ['keyword_id'] != NULL && $this->request->get ['keyword_id'] != "") {
			$this->load->model ( 'setting/keywords' );
			$keyworddetails = $this->model_setting_keywords->getkeywordDetail ( $this->request->get ['keyword_id'] );
			if($keyworddetails['monitor_time'] == '8'){
				if($keyworddetails['client_type'] == '1'){
					$this->load->model('facilities/facilities');
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
					if($result['client_facilities_ids'] != null && $result['client_facilities_ids'] != ""){
						$ddss[] = $result['client_facilities_ids'];
					}
					
					$ddss[] = $this->customer->getId();
					$sssssdd = implode(",",$ddss);
					
				}else{
					$this->load->model ( 'facilities/facilities' );
					
					$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
				
					$customer_key1 = $result['customer_key'];
					
					$this->load->model ( 'customer/customer' );
					$customer_info = $this->model_customer_customer->getcustomerid ( $customer_key1 );
					$customer_key = $customer_info ['activecustomer_id'];
				}
			}
			$this->data ['keyword_id'] = $this->request->get ['keyword_id'];
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
		}
		
		if ($this->request->get ['facilityids'] != '' && $this->request->get ['facilityids'] != null) {
			$data = array (
				'facilities' => $this->request->get ['facilityids'],
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				'role_call' => '1',
				//'is_master' => 1,
				'sort' => 'emp_last_name',
				'order' => 'ASC',
			);

		} else {
			
			if($sssssdd != null && $sssssdd != ""){
				$data = array (
					'facilities' => $sssssdd,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'role_call' => '1',
					//'is_master' => 1,
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}else if($customer_key != null && $customer_key != ""){
				$data = array (
					'customer_key' => $customer_key,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'is_master' => 1,
					'role_call' => '1',
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}else{
				$data = array (
					'facilities_id' => $facilities_id,
					'status' => 1,
					'discharge' => 1,
					'all_record' => 1,
					'is_master' => 1,
					'role_call' => '1',
					'sort' => 'emp_last_name',
					'order' => 'ASC',
				);
			}
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
			
			$fullname = $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'] . ' ' . $result ['emp_last_name'] . $ssn . $dob;
			
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
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
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
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		
		if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
			$url2 .= '&facilityids=' . $this->request->get['facilityids'];
		}
		if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
			$url2 .= '&locationids=' . $this->request->get['locationids'];
		}
		
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
		
		$this->data ['action2'] = $this->url->link ( 'notes/activenote/clientsdischarge', '' . $url2, 'SSL' );
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/alltags.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	public function clientsinsignature(){
		
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
            
			
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$notetime = date('H:i:s', strtotime('now'));
			
			if ($this->request->post['imgOutput']) {
				$data['imgOutput'] = $this->request->post['imgOutput'];
			} else {
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$this->load->model('setting/tags');
			$this->load->model('resident/resident');
			
			$this->load->model('setting/keywords');
			$keywordData2 = $this->model_setting_keywords->getkeywordDetailbyid($this->request->get['keyword_id'],$facilities_id);
			
			$data['keyword_file'] = $keywordData2['keyword_image'];
			$keyword_name = $keywordData2['keyword_name'];
		
			
			if ($this->request->post['comments'] != null && $this->request->post['comments']) {
				$comments = ' | ' . $this->request->post['comments'];
			}
			
			if ($this->request->post['new_module']) {
				
				$this->load->model('notes/notes');
				
				foreach ($this->request->post['new_module'] as $customlistvalues_id) {

					if($customlistvalues_id['checkin']=="1"){

						$description1 .= ' | ' . $customlistvalues_id['customlistvalues_name'];

					}
				}
				
				$data['customlistvalues_ids'] = $this->request->post['customlistvalues_ids'];
			}
			
			$afacilities = array();
			
			
			if($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
				$sssssdds2 = explode(",",$this->request->get['tagsids']);
				$abdcds = array_unique($sssssdds2);
				
				foreach($abdcds as $key1 => $tagsid){
					$tag_info = $this->model_setting_tags->getTag($tagsid);
					$afacilities[] = array(
						'tags_id'=>$tagsid,
						'facilities_id'=>$tag_info['facilities_id'],
					);
					
				}
			}
			
			
			$role_calltagsids = $this->groupArray($afacilities, "facilities_id", false, true);
			$abc = array();
			$tagnamesss = "";
			
			
			$data['date_added'] = $date_added;
			$data['note_date'] = $date_added;
			$data['notetime'] = $notetime;
			
			if($facilities_info['no_distribution'] == '1'){
				foreach ($role_calltagsids as $rolecalls) {
					
					$tagname = "";
					$tagname2 = "";
					$tagnamesss_out = "";
					if($this->request->get['clienttype'] == '1'){
						foreach($rolecalls as $rolecall){
						
							$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model('setting/locations');
							$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
							
							$tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
							
							$this->model_setting_tags->updatetagmed($rolecall['tags_id'], '1', $date_added);
						
						
						$data['emp_tag_id'] = $tag_info['emp_tag_id'];
						$data['tags_id'] = $tag_info['tags_id'];
						$data['notes_description'] = $keyword_name .' '.$tagname . ' | '  . $description1 . $comments;
						$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
							
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
						
								
							}
						}
						}
					}
					
					if($this->request->get['clienttype'] == '2'){
						foreach($rolecalls as $rolecall){
						
							$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model('setting/locations');
							$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
							
							$tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
							
							$this->model_setting_tags->updatetagmed($rolecall['tags_id'], '0', $date_added);
						
						
						$data['emp_tag_id'] = $tag_info['emp_tag_id'];
						$data['tags_id'] = $tag_info['tags_id'];
						$data['notes_description'] = $keyword_name .' '.$tagname . ' | '  . $description1 . $comments;
						
						$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
							
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
						
								
							}
						}
						}
					}
					
					if($this->request->get['clienttype'] == '3'){
						foreach($rolecalls as $rolecall){
						
							$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model('setting/locations');
							$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
							
							$tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
							
							$this->model_setting_tags->updatetagmed($rolecall['tags_id'], '1', $date_added);
							
							$this->load->model('createtask/createtask');
							$alldatas = $this->model_createtask_createtask->getalltaskbyid($rolecall['tags_id']);
							
							if ($alldatas != NULL && $alldatas != "") {
								foreach ($alldatas as $alldata) {
									$result = $this->model_createtask_createtask->getStrikedatadetails($alldata['id']);
									$facilities_idt = $result['facilityId'];
									$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists($result, $facilities_idt, '1');
									$this->model_createtask_createtask->updatetaskStrike($alldata['id']);
									$this->model_createtask_createtask->deteteIncomTask($facilities_idt);
								}
							}
						
						
							$data['emp_tag_id'] = $tag_info['emp_tag_id'];
							$data['tags_id'] = $tag_info['tags_id'];
							$data['notes_description'] = $keyword_name .' '.$tagname . ' | '  . $description1 . $comments;
							
							
							$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
							
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
							
									
								}
							}
							
							
							$this->model_setting_tags->addcurrentTagarchive($rolecall['tags_id']);
							$this->model_setting_tags->updatecurrentTagarchive($rolecall['tags_id'], $notes_id);
							
							$this->model_resident_resident->updateDischargeTag($rolecall['tags_id'], $date_added);
							
						}
						
					}
					
				}
			}
			
			
			foreach ($role_calltagsids as $facilities_id1 => $rolecalls) {
				
				$tagname = "";
				$tagname2 = "";
				$tagnamesss_out = "";
				$tags_id_list = array();
				if($this->request->get['clienttype'] == '1'){
					foreach($rolecalls as $rolecall){
					
						$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
						$emp_tag_id = $tag_info['emp_tag_id'];
						$tags_id = $tag_info['tags_id'];
						$tags_id_list[] = $tag_info['tags_id'];
						//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						
						$this->load->model('setting/locations');
						$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
						
						$tagname .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
						
						$this->model_setting_tags->updatetagmed($rolecall['tags_id'], '1', $date_added);
					
					}
					$data ['tags_id_list'] = $tags_id_list;
					$data['notes_description'] = $keyword_name .' '.$tagname . ' | '  . $description1 . $comments;
				}
				
				if($this->request->get['clienttype'] == '2'){
					foreach($rolecalls as $rolecall){
					
						$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
						$emp_tag_id = $tag_info['emp_tag_id'];
						$tags_id = $tag_info['tags_id'];
						//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						$tags_id_list[] = $tag_info['tags_id'];
						$this->load->model('setting/locations');
						$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
						
						$tagname .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
						
						$this->model_setting_tags->updatetagmed($rolecall['tags_id'], '0', $date_added);
					
					}
					$data ['tags_id_list'] = $tags_id_list;
					$data['notes_description'] = $keyword_name .' '.$tagname . ' | '  . $description1 . $comments;
				}
				
				if($this->request->get['clienttype'] == '3'){
					foreach($rolecalls as $rolecall){
					
						$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
						$emp_tag_id = $tag_info['emp_tag_id'];
						$tags_id = $tag_info['tags_id'];
						//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						$tags_id_list[] = $tag_info['tags_id'];
						$this->load->model('setting/locations');
						$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
						
						$tagname .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
						
						$this->model_setting_tags->updatetagmed($rolecall['tags_id'], '1', $date_added);
						
						$this->load->model('createtask/createtask');
						$alldatas = $this->model_createtask_createtask->getalltaskbyid($rolecall['tags_id']);
						
						if ($alldatas != NULL && $alldatas != "") {
							foreach ($alldatas as $alldata) {
								$result = $this->model_createtask_createtask->getStrikedatadetails($alldata['id']);
								$facilities_idt = $result['facilityId'];
								$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists($result, $facilities_idt, '1');
								$this->model_createtask_createtask->updatetaskStrike($alldata['id']);
								$this->model_createtask_createtask->deteteIncomTask($facilities_idt);
							}
						}
					
					}			                          

                    if($keyword_name!=null && $keyword_name!=""){

                    	$Released="";

                    }else{

                    	$Released="Released";
                        $data['keyword_file'] = DISCHARGE_ICON;
                        $html_design=' | ';
                    }

					$data ['tags_id_list'] = $tags_id_list;
					$data['notes_description'] = $Released. $html_design . $keyword_name .' '.$tagname . ' | '   . $description1 . $comments;						
					
				}
					
				
				if($facilities_id1 != null && $facilities_id1 != ""){
					$facilities_id2 = $facilities_id1;
				}else{
					$facilities_id2 = $facilities_id;
				}
				
				
				//var_dump($data);
				
				$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id2);
				
				if($this->request->get['clienttype'] == '3'){
					foreach ($role_calltagsids as $facilities_id1 => $rolecalls) {
						foreach($rolecalls as $rolecall){
						
							
							$this->model_setting_tags->addcurrentTagarchive($rolecall['tags_id']);
							$this->model_setting_tags->updatecurrentTagarchive($rolecall['tags_id'], $notes_id);
							
							$this->model_resident_resident->updateDischargeTag($rolecall['tags_id'], $date_added);
							
						}
					}
				}
				
				
				$this->load->model('facilities/facilities');
				$facility = $this->model_facilities_facilities->getfacilities($facilities_id2);
					
				
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
			}
          
            $this->session->data['success_update_form_2'] = 'Note Added ';
            
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
        
        $this->data['config_tag_status'] = $this->customer->isTag();
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
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
		if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
			$url2 .= '&tagsids=' . $this->request->get['tagsids'];
		}
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
        if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
		}
		if ($this->request->get['clienttype'] != null && $this->request->get['clienttype'] != "") {
			$url2 .= '&clienttype=' . $this->request->get['clienttype'];
		}
		
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL'));
       
        
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
        
        if (isset($this->request->post['emp_tag_id_2'])) {
            $this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id_2'] = $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['emp_tag_id_2'] = '';
        }
		
		if (isset ( $this->request->post ['tagides'] )) {
			$tagides1 = $this->request->post ['tagides'];
		} elseif (! empty ( $this->request->get ['tags_id'] )) {
			$tagides1 = explode ( ',', $this->request->get ['tags_id'] );
		} elseif($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
			$tagides1 = explode ( ',', $this->request->get['tagsids'] );
			$this->data ['tagsids'] = $this->request->get['tagsids'];
			
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
        
        if (isset($this->request->post['comments'])) {
            $this->data['comments'] = $this->request->post['comments'];
        } else {
            $this->data['comments'] = '';
        }
		
        
        $this->data['createtask'] = 1;
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
	}
	
	public function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
		$temp = array();
		foreach($arr as $key => $value) {
			$groupValue = $value[$group];
			if(!$preserveGroupKey)
			{
				unset($arr[$key][$group]);
			}
			if(!array_key_exists($groupValue, $temp)) {
				$temp[$groupValue] = array();
			}

			if(!$preserveSubArrays){
				$data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
			} else {
				$data = $arr[$key];
			}
			$temp[$groupValue][] = $data;
		}
		return $temp;
	}
}