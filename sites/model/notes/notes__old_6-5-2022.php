<?php
class Modelnotesnotes extends Model {
	public function addnotes($data, $facilities_id) {
		if ($this->session->data ['ssincedentform'] ['update_form'] == '1') {
			unset ( $this->session->data ['ssincedentform'] );
		}
		
		if ($this->session->data ['ssbedcheckform'] ['update_form'] == '1') {
			unset ( $this->session->data ['ssbedcheckform'] );
		}
		
		// $notes_description1 = rtrim(rtrim($data['notes_description']));
		// $notes_description2 = ucfirst($notes_description1);
		$notes_description2 = $data ['notes_description'];
		
		$timezone_name = $this->customer->isTimezone ();
		// var_dump($timezone_name);
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		/*
		 * if($this->config->get('config_time_picker') == '0'){
		 * $noteTime = date('H:i:s', strtotime('now'));
		 * }else{
		 */
		
		$notetime1 = explode ( ":", $data ['notetime'] );
		if ($notetime1 [0] == "00") {
			$notetime2 = $data ['notetime'];
		} else {
			$notetime2 = $data ['notetime'];
		}
		$noteTime = date ( 'H:i:s', strtotime ( $notetime2 ) );
		/* } */
		
		// foreach($data['notesdatas'] as $notesdata){
		
		/* date_default_timezone_set($this->session->data['time_zone_1']); */
		
		$createdate1 = date ( 'Y-m-d', strtotime ( $data ['note_date'] ) );
		
		$createtime1 = date ( 'H:i:s' );
		// var_dump($createtime1);
		// $createDate2 = $createdate1 . $createtime1;
		$createDate2 = $createdate1 . $noteTime;
		
		$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
		
		/* $createDate = $createDate; */
		// date('Y-m-d H:i:s',strtotime('now'));
		
		// date_default_timezone_get();
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		/*
		 * if($data['keyword_file'] != null && $data['keyword_file'] != ""){
		 * $this->load->model('setting/image');
		 *
		 * $file16 = 'icon/'.$data['keyword_file'];
		 *
		 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
		 * $newfile216 = DIR_IMAGE . $newfile84;
		 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
		 * $imageData132 = base64_encode(file_get_contents($newfile216));
		 *
		 * if($newfile84 != null && $newfile84 != ""){
		 * $keyword_icon =
		 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
		 * }else{
		 * $keyword_icon = '';
		 * }
		 * $keyword_file = $data['keyword_file'];
		 *
		 * $this->load->model('setting/keywords');
		 * $keywordData2 =
		 * $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
		 * //var_dump($keywordData2['keyword_name']);
		 * //echo "<hr>";
		 * $notes_description2 =
		 * str_replace($keywordData2['keyword_name'],$keywordData2['keyword_name'],$notes_description2);
		 *
		 * //var_dump($sstext);
		 *
		 * //$notes_description2 = $keywordData2['keyword_name'];
		 * }
		 */
		
		if ($data ['text_color'] != null && $data ['text_color'] != "") {
			$text_color = '#' . $data ['text_color'];
		} else {
			$text_color = "";
		}
		
		/*
		 * if($data['keyword_file'] == null && $data['keyword_file'] == ""){
		 * $this->load->model('setting/keywords');
		 *
		 * $data3 = array(
		 * 'facilities_id' => $this->customer->getId(),
		 * );
		 *
		 * $keywords = $this->model_setting_keywords->getkeywords($data3);
		 * $keyarray = array();
		 * $keyarray2 = array();
		 * foreach($keywords as $keyword){
		 * $keyarray[] = $keyword['active_tag'];
		 * $keyarray2[] = $keyword['keyword_name'];
		 * }
		 *
		 * $matchData = $this->arrayInString( $keyarray , $notes_description2);
		 * //$matchData2 = $this->arrayInString2( $keyarray2 ,
		 * $notes_description2);
		 *
		 *
		 * if ($matchData != null && $matchData != "") {
		 * $keywordData =
		 * $this->model_setting_keywords->getkeywordByTag($matchData);
		 * }
		 *
		 *
		 *
		 *
		 * if ($keywordData['keyword_image'] != null &&
		 * $keywordData['keyword_image'] != "") {
		 * $this->load->model('setting/image');
		 *
		 * $file16 = 'icon/'.$keywordData['keyword_image'];
		 *
		 * //var_dump($file16);
		 * //echo "<hr>";
		 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
		 *
		 * //var_dump($file16);
		 * //echo "<hr>";
		 *
		 * $newfile216 = DIR_IMAGE . $newfile84;
		 *
		 * //var_dump($newfile216);
		 * //echo "<hr>";
		 * $file124 = HTTP_SERVER . 'image/'.$newfile84;
		 *
		 * //var_dump($file124);
		 * //echo "<hr>";
		 * $imageData132 = base64_encode(file_get_contents($newfile216));
		 *
		 * if($newfile84 != null && $newfile84 != ""){
		 * $keyword_icon =
		 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
		 * }else{
		 * $keyword_icon = '';
		 * }
		 *
		 * $keyword_file = $keywordData['keyword_image'];
		 *
		 *
		 * $notes_description2 = str_ireplace($keywordData['active_tag'],
		 * $keywordData['keyword_name'],$notes_description2);
		 *
		 *
		 * }
		 *
		 * }
		 */
		
		// $notes_description = str_replace("'","&#039;",
		// html_entity_decode($notes_description2, ENT_QUOTES));
		$notes_description = $notes_description2;
		
		$pcode = "";
		/*
		 * if($this->session->data['ssincedentform']['program_code'] == '1'){
		 * $pcode = incident_severity1;
		 * }
		 *
		 * if($this->session->data['ssincedentform']['program_code'] == '2'){
		 * $pcode = incident_severity2;
		 * }
		 *
		 * if($this->session->data['ssincedentform']['program_code'] == '3'){
		 * $pcode = incident_severity3;
		 * }
		 * if($this->session->data['ssincedentform']['program_code'] == '4'){
		 * $pcode = incident_severity4;
		 * }
		 * if($this->session->data['ssincedentform']['program_code'] == '5'){
		 * $pcode = incident_severity5;
		 * }
		 */
		
		if ($pcode) {
			$notes_description = $notes_description . ' | ' . $pcode;
		} else {
			$notes_description = $notes_description;
		}
		
		if ($data ['highlighter_id'] == '21') {
			$highlighter = '0';
			$highlighter_value1 = '';
		} else {
			$highlighter = $data ['highlighter_id'];
			$highlighter_value1 = $data ['highlighter_value'];
		}
		
		if ($this->session->data ['isPrivate'] == '1') {
			$status = "0";
			$user_id = $this->session->data ['username'];
			$is_private = "1";
		} else {
			$status = "0";
			$user_id = $data ['user_id'];
			$is_private = "0";
		}
		
		/*
		 * $sql = "INSERT INTO `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $this->db->escape($highlighter) . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "', notes_file = '" . $this->db->escape($data['notes_file']) . "', user_id = '" . $this->db->escape($user_id) .
		 * "', status = '" . $status . "', notetime = '" . $noteTime . "', signature = '', signature_image = '" . $fileName . "', text_color_cut = '" . $this->db->escape($data['text_color_cut']) . "', text_color = '" . $this->db->escape($text_color) . "', date_added = '" . $createDate . "', note_date = '" . $noteDate . "', global_utc_timezone = UTC_TIMESTAMP( ), keyword_file = '" .
		 * $this->db->escape($keyword_file) . "', keyword_file_url = '" . $this->db->escape($keyword_icon) . "', highlighter_value = '" . $this->db->escape($highlighter_value1) . "', is_private = '" . $is_private . "' ";
		 *
		 *
		 * $this->db->query($sql);
		 * $notes_id = $this->db->getLastId();
		 */
		
		$sql = "Call insertNotes('" . $facilities_id . "', '" . $this->db->escape ( $notes_description ) . "', '" . $highlighter . "', '" . $noteTime . "', '" . $createDate . "', '" . $noteDate . "', '" . $this->db->escape ( $text_color ) . "', '" . $this->db->escape ( $data ['notes_file'] ) . "', '" . $status . "', '" . $this->db->escape ( $highlighter_value1 ) . "','" . $is_private . "')";
		
		$lastId = $this->db->query ( $sql );
		
		$notes_id = $lastId->row ['notes_id'];
		
		if ($facilities_id) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			$sql121 = "UPDATE `" . DB_PREFIX . "notes` SET unique_id = '" . $this->db->escape ( $unique_id ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql121 );
		}
		
		$jsonData2 = stripslashes ( $data ['multi_keyword_file'] );
		$mutikeywords = json_decode ( $jsonData2, true );
		
		// var_dump($data['multi_keyword_file']);
		$multi_keyword_file = unserialize ( $data ['multi_keyword_file'] );
		
		if (! empty ( $this->session->data ['multi_keyword_file'] )) {
			foreach ( $this->session->data ['multi_keyword_file'] as $key => $multivalue ) {
				
				foreach ( $multivalue as $v ) {
					
					if ($v ['chkvalue'] != NULL && $v ['chkvalue'] != "") {
						
						if ($v ['multivalue'] != NULL && $v ['multivalue'] != '') {
							$multi_value = $v ['multivalue'];
							$autocompletetype = 'multivalue';
						} else {
							$multi_value = @implode ( ',', $v ['autocomplete'] );
							$autocompletetype = $v ['autocomplete_type'];
							$default_value = $v ['default_value'];
						}
						
						$sqla = "INSERT INTO `" . DB_PREFIX . "notes_by_multikeyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $key . "', facilities_id = '" . $facilities_id . "', date_added = '" . $noteDate . "', date_updated = '" . $noteDate . "', action = '" . $this->db->escape ( $v ['action'] ) . "', name = '" . $this->db->escape ( $v ['name'] ) . "', type = '" . $this->db->escape ( $v ['measurement'] ) . "', value = '" . $this->db->escape ( $multi_value ) . "', autocomplete_type = '" . $autocompletetype . "', default_value = '" . $default_value . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
						$this->db->query ( $sqla );
					}
				}
			}
		}
		
		unset ( $this->session->data ['multi_keyword_file'] );
		
		if (! empty ( $data ['tagsids'] )) {
			$sssssddss2 = explode ( ",", $data ['tagsids'] );
			$aabdcds = array_unique ( $sssssddss2 );
			$this->load->model ( 'setting/tags' );
			foreach ( $aabdcds as $tagid ) {
				$tag_info = $this->model_setting_tags->getTag ( $tagid );
				$tadata = array ();
				// $this->updateNotesTag22 ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
			}
		}
		
		if (! empty ( $data ['tags_id_list1'] )) {
			$this->load->model ( 'setting/tags' );
			foreach ( $data ['tags_id_list1'] as $tagid ) {
				$tag_info = $this->model_setting_tags->getTag ( $tagid );
				$tadata = array ();
				// $this->updateNotesTag22 ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
			}
		}
		
		if (! empty ( $data ['locationsid'] )) {
			$this->load->model ( 'setting/locations' );
			foreach ( $data ['locationsid'] as $locationsid ) {
				$location_info12 = $this->model_setting_locations->getlocation ( $locationsid );
				
				$sqll = "INSERT INTO `" . DB_PREFIX . "notes_by_location` SET notes_id = '" . $notes_id . "', location_id = '" . $this->db->escape ( $locationsid ) . "', location_name = '" . $this->db->escape ( $location_info12 ['location_name'] ) . "', facilities_id = '" . $facilities_id . "', date_added = '" . $noteDate . "', date_updated = '" . $noteDate . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
				$this->db->query ( $sqll );
			}
		}
		
		/*
		 * $this->load->model('licence/licence');
		 *
		 * $ddd = array();
		 * $ddd['facility'] = $facilities_id;
		 * $ddd['notes_description'] = $notes_description;
		 * $ddd['date_added'] = $createDate;
		 * $ddd['notes_id'] = $notes_id;
		 * $ddd['user_id'] = 'admin';
		 * $this->model_licence_licence->addpowerbinotes($ddd);
		 */
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$ctime = time ();
		$stime = date ( 'H:i:s', strtotime ( $ctime ) );
		
		// $sqlshift = "SELECT * FROM `" . DB_PREFIX . "shift` where shift_starttime > '".$stime."' and shift_endtime < '".$stime."' ";
		// $shifts = $this->db->query($sqlshift);
		
		$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $facilities_id );
		if (! empty ( $shift_info ['shift_id'] )) {
			$id = $shift_info ['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $updateshift );
		}
		
		if ($data ['tags_id'] != null && $data ['tags_id'] != "") {
			
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$this->load->model ( 'setting/tags' );
			$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );
			
			$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
			$tadata = array ();
			// $this->updateNotesTag22 ( $data ['emp_tag_id'], $notes_id, $data ['tags_id'], $update_date, $tadata );
			
			// $this->session->data ['tags_id_1'] = $data ['tags_id'];
		}
		
		/*
		 * if(!empty($this->session->data['formsids'])){
		 * $this->load->model('form/form');
		 * foreach($this->session->data['formsids'] as $formsid){
		 * $this->model_form_form->updatenote($notes_id, $formsid);
		 * }
		 * unset($this->session->data['formsids']);
		 * }
		 */
		
		if ($data ['keyword_file'] != null && $data ['keyword_file'] != "") {
			$this->load->model ( 'setting/image' );
			
			$keywords = explode ( ",", $data ['keyword_file'] );
			
			foreach ( $keywords as $keyword ) {
				
				/*
				 * $file16 = 'icon/'.$keyword;
				 *
				 * $newfile84 = $this->model_setting_image->resize($file16, 50,
				 * 50);
				 * $newfile216 = DIR_IMAGE . $newfile84;
				 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
				 * $imageData132 =
				 * base64_encode(file_get_contents($newfile216));
				 *
				 * if($newfile84 != null && $newfile84 != ""){
				 * $keyword_icon =
				 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
				 * }else{
				 * $keyword_icon = '';
				 * }
				 */
				$keyword_icon = '';
				
				$keyword_file = $keyword;
				
				$this->load->model ( 'setting/keywords' );
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $keyword, $facilities_id );
				
				// var_dump($keywordData2['keyword_name']);
				
				if ($keywordData2 ['monitor_time'] == '11') {
					$notes_description2 = $keywordData2 ['keyword_name'] . ' ' . $notes_description2;
				} else {
					$keyword_name1 = str_replace ( array (
							"\r",
							"\n" 
					), '', $keywordData2 ['keyword_name'] );
					// echo "<hr>";
					$notes_description2 = str_replace ( $keyword_name1, $keywordData2 ['keyword_name'], $notes_description2 );
				}
				
				// var_dump($notes_description2);
				// echo "<hr>";
				// var_dump($sstext);
				
				// $notes_description2 = $keywordData2['keyword_name'];
				
				$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $this->db->escape ( $keywordData2 ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData2 ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape ( $unique_id ) . "', date_added = '" . $createDate . "' ";
				$this->db->query ( $sqlm );
				
				if ($keywordData2 ['monitor_time'] == '11') {
					$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql12 );
				}
			}
			
			$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', keyword_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['keyword_file'] == null && $data ['keyword_file'] == "") {
			$this->load->model ( 'setting/keywords' );
			
			preg_match_all ( '/#([^\s]+)/', $notes_description2, $matches );
			
			// var_dump($matches[1]);
			
			$config_multiple_activenote = $this->customer->isMactivenote ();
			
			// var_dump($config_multiple_activenote);
			if (! empty ( $matches [1] )) {
				foreach ( $matches [1] as $hashtag ) {
					$active_tag = '#' . $hashtag;
					$keywordData = $this->model_setting_keywords->getkeywordsbyhashtag ( $active_tag, $this->customer->getId () );
					
					if ($keywordData ['keyword_image'] != null && $keywordData ['keyword_image'] != "") {
						/*
						 * $this->load->model('setting/image');
						 *
						 * $file16 = 'icon/'.$keywordData['keyword_image'];
						 * $newfile84 =
						 * $this->model_setting_image->resize($file16, 50, 50);
						 * $newfile216 = DIR_IMAGE . $newfile84;
						 * $file124 = HTTP_SERVER . 'image/'.$newfile84;
						 *
						 * $imageData132 =
						 * base64_encode(file_get_contents($newfile216));
						 *
						 * if($newfile84 != null && $newfile84 != ""){
						 * $keyword_icon =
						 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
						 * }else{
						 * $keyword_icon = '';
						 * }
						 */
						$keyword_icon = '';
						
						$keyword_file = $keywordData ['keyword_image'];
						
						$notes_description2 = str_ireplace ( $keywordData ['active_tag'], $keywordData ['keyword_name'], $notes_description2 );
						
						/*
						 * if($active_tag == $keywordData['relation_hastag']){
						 * $notes_description2 =
						 * str_ireplace($keywordData['relation_hastag'],
						 * $keywordData['keyword_name'],$notes_description2);
						 * }
						 */
						
						$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $this->db->escape ( $keywordData ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', active_tag = '" . $this->db->escape ( $active_tag ) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape ( $unique_id ) . "', date_added = '" . $createDate . "' ";
						$this->db->query ( $sqlm );
						
						if ($keywordData ['monitor_time'] == '11') {
							$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
							$this->db->query ( $sql12 );
						}
						
						if ($config_multiple_activenote != '1') {
							break;
						}
					}
				}
				
				$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', keyword_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql1 );
			}
		}
		
		if ($data ['notes_file'] != null && $data ['notes_file'] != "") {
			$sql = "INSERT INTO `" . DB_PREFIX . "notes_media` SET notes_id = '" . $notes_id . "', notes_file = '" . $this->db->escape ( $data ['notes_file'] ) . "', notes_media_extention = '" . $this->db->escape ( $data ['notes_media_extention'] ) . "', status = '1',facilities_id = '" . $facilities_id . "', media_date_added = '" . $createDate . "' ";
			$this->db->query ( $sql );
			
			$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
			$this->db->query ( $sql12 );
		}
		
		unset ( $this->session->data ['ssincedentform'] );
		unset ( $this->session->data ['ssbedcheckform'] );
		
		// $this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$adata ['notes_file'] = $data ['notes_file'];
		$adata ['date_added'] = $createDate;
		$adata ['note_date'] = $noteDate;
		$adata ['facilities_id'] = $facilities_id;
		$adata ['keyword_file'] = $data ['keyword_file'];
		// $this->model_activity_activity->addActivitySave ( 'addnotes', $adata, 'query' );
		
		return $notes_id;
		// }
	}
	public function updatereviewnotes($notes_id) {
		$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
		$this->db->query ( $sql1 );
	}
	public function arrayInString2($inArray, $inString) {
		$inString = rtrim ( rtrim ( $inString ) );
		// $inString = str_replace(" ","", $inString);
		$inString = ucwords ( $inString );
		
		// var_dump($inString); //die;
		
		if (is_array ( $inArray )) {
			foreach ( $inArray as $e ) {
				
				if (stripos ( $inString, $e ) !== false)
					return $e;
			}
			return "";
		} else {
			return (stripos ( $inString, $inArray ) !== false);
		}
	}
	public function arrayInString($inArray, $inString) {
		$inString = rtrim ( rtrim ( $inString ) );
		// $inString = str_replace(" ","", $inString);
		$inString = ucwords ( $inString );
		// var_dump($inString);
		// $inString = str_replace(' ', ' | ', $inString);
		// var_dump($inString); //die;
		
		if (is_array ( $inArray )) {
			foreach ( $inArray as $e ) {
				
				if (stripos ( $inString, $e ) !== false)
					return $e;
			}
			return "";
		} else {
			return (stripos ( $inString, $inArray ) !== false);
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
		} 

		elseif (function_exists ( 'finfo_open' )) {
			
			$finfo = finfo_open ( FILEINFO_MIME );
			
			$mimetype = finfo_file ( $finfo, $filename );
			
			finfo_close ( $finfo );
			
			return $mimetype;
		} 

		else {
			
			return 'application/octet-stream';
		}
	}
	public function updateNotesContent($notes_id, $data) {
		
		// $notes_description1 = rtrim(rtrim($data['notes_description']));
		// $notes_description2 = ucfirst($notes_description1);
		$notes_description2 = $data ['notes_description'];
		/*
		 * $data3 = array(
		 * 'facilities_id' => $facilities_id,
		 * );
		 * $keywords = $this->model_setting_keywords->getkeywords($data3);
		 * $keyarray = array();
		 * $keyarray2 = array();
		 * foreach($keywords as $keyword){
		 * $keyarray[] = $keyword['active_tag'];
		 * $keyarray2[] = $keyword['keyword_name'];
		 * }
		 *
		 *
		 * $matchData = $this->arrayInString( $keyarray , $notes_description2);
		 * //$matchData2 = $this->arrayInString2( $keyarray2 ,
		 * $notes_description2);
		 *
		 * //var_dump($matchData2);
		 * if ($matchData != null && $matchData != "") {
		 * $keywordData =
		 * $this->model_setting_keywords->getkeywordByTag($matchData);
		 * }
		 */
		
		/*
		 * if ($keywordData['keyword_image'] && file_exists(DIR_IMAGE .
		 * 'icon/'.$keywordData['keyword_image'])) {
		 * $this->load->model('setting/image');
		 *
		 * $file16 = 'icon/'.$keywordData['keyword_image'];
		 *
		 * //var_dump($file16);
		 * //echo "<hr>";
		 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
		 *
		 * //var_dump($file16);
		 * //echo "<hr>";
		 *
		 * $newfile216 = DIR_IMAGE . $newfile84;
		 *
		 * //var_dump($newfile216);
		 * //echo "<hr>";
		 * $file124 = HTTP_SERVER . 'image/'.$newfile84;
		 *
		 * //var_dump($file124);
		 * //echo "<hr>";
		 * $imageData132 = base64_encode(file_get_contents($newfile216));
		 *
		 * if($newfile84 != null && $newfile84 != ""){
		 * $keyword_icon =
		 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
		 * }else{
		 * $keyword_icon = '';
		 * }
		 *
		 *
		 * $keyword_file = $keywordData['keyword_image'];
		 * $notes_description2 =
		 * str_replace($keywordData['active_tag'],$keywordData['keyword_name'],$notes_description2);
		 * }
		 */
		
		// $notes_description = str_replace("'","&#039;",
		// html_entity_decode($notes_description2, ENT_QUOTES));
		
		// $notes_description = str_replace("'","&#039;",
		// html_entity_decode($notes_description2, ENT_QUOTES));
		$notes_description = $notes_description2;
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		$config_multiple_activenote = $facilities_info ['config_multiple_activenote'];
		
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		
		date_default_timezone_set ( $timezone_info ['timezone_value'] );
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description ) . "', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description;
		$adata ['update_date'] = $update_date;
		$adata ['facilities_id'] = $data ['facilities_id'];
		$this->model_activity_activity->addActivitySave ( 'updateNotesContent', $adata, 'query' );
		/*
		 * $this->load->model('setting/keywords');
		 *
		 * preg_match_all('/#([^\s]+)/', $notes_description, $matches);
		 *
		 * if(!empty($matches[1])){
		 * foreach($matches[1] as $hashtag){
		 * $active_tag = '#'.$hashtag;
		 * $keywordData =
		 * $this->model_setting_keywords->getkeywordsbyhashtag($active_tag,
		 * $data['facilities_id']);
		 *
		 * //var_dump($keywordData);
		 * //echo "<hr>";
		 *
		 * if ($keywordData['keyword_image'] != null &&
		 * $keywordData['keyword_image'] != "") {
		 * $this->load->model('setting/image');
		 *
		 * $file16 = 'icon/'.$keywordData['keyword_image'];
		 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
		 * $newfile216 = DIR_IMAGE . $newfile84;
		 * $file124 = HTTP_SERVER . 'image/'.$newfile84;
		 *
		 * $imageData132 = base64_encode(file_get_contents($newfile216));
		 *
		 * if($newfile84 != null && $newfile84 != ""){
		 * $keyword_icon =
		 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
		 * }else{
		 * $keyword_icon = '';
		 * }
		 *
		 * $keyword_file = $keywordData['keyword_image'];
		 *
		 *
		 * $notes_description2 = str_ireplace($keywordData['active_tag'],
		 * $keywordData['keyword_name'],$notes_description2);
		 *
		 *
		 *
		 * $sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id
		 * = '" . $notes_id . "', keyword_id = '" .
		 * $this->db->escape($keywordData['keyword_id']) . "', keyword_name = '"
		 * . $this->db->escape($keywordData['keyword_name']) . "', keyword_file
		 * = '" . $this->db->escape($keyword_file) . "', keyword_file_url = '" .
		 * $this->db->escape($keyword_icon) . "', active_tag = '" .
		 * $this->db->escape($active_tag) . "', keyword_status = '1',
		 * facilities_id = '" . $data['facilities_id'] . "', date_added =
		 * '".$data['date_added']."' ";
		 * $this->db->query($sqlm);
		 *
		 * if($keyword_file == CONFIG_REVIEW_NOTES){
		 * $sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1'
		 * WHERE notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql12);
		 * }
		 *
		 *
		 * if($config_multiple_activenote != '1'){
		 * break;
		 * }
		 *
		 *
		 * }
		 *
		 * }
		 *
		 *
		 * $sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" .
		 * $notes_description2 . "' WHERE notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql1);
		 * }
		 */
	}
	public function updatenotes($data, $facilities_id, $notes_id) {
		$updatefac = "UPDATE `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $updatefac );
		
		// if($data['notes_pin'] != null && $data['notes_pin'] != ""){
		$notes_pin = $data ['notes_pin'];
		// $signature = "";
		// }else{
		// $signature = $data ['imgOutput'];
		$signature = "";
		if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
			$this->load->model ( 'api/savesignature' );
			$sigdata = array ();
			$sigdata ['upload_file'] = $data ['imgOutput'];
			$sigdata ['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
			
			$signature = $signaturestatus;
		}
		
		// $notes_pin = '';
		// }
		$this->load->model ( 'notes/notes' );
		$notes_info = $this->getnotes ( $notes_id );
		
		if ($notes_info ['highlighter_id'] == "0") {
			$this->load->model ( 'user/user' );
			if ($data ['user_id'] != null && $data ['user_id'] != "") {
				$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
			} else {
				$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
			}
			
			if ($user_info ['default_highlighter_id'] != "0") {
				$highlighter_id = $user_info ['default_highlighter_id'];
				
				$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '" . $highlighter_id . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
				$this->db->query ( $sql1 );
			}
		}
		
		if ($notes_info ['text_color'] == null && $notes_info ['text_color'] == "") {
			$this->load->model ( 'user/user' );
			// $user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
			
			if ($data ['user_id'] != null && $data ['user_id'] != "") {
				$user_info1 = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
			} else {
				$user_info1 = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
			}
			
			if ($user_info1 ['default_color'] != null && $user_info1 ['default_color'] != "") {
				$default_color = $user_info1 ['default_color'];
				
				$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '" . $default_color . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
				$this->db->query ( $sql1 );
			}
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
			$this->db->query ( $sql13 );
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
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$a2 = array ();
		$a2 ['notes_id'] = $notes_id;
		$a2 ['facilities_id'] = $facilities_id;
		$active_note_info_actives = $this->getNotebyactivenotes ( $a2 );
		
		$this->load->model ( 'user/user' );
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		// var_dump($active_note_info_actives);
		
		if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
			$description22 = ' | ' . $this->db->escape ( $data ['comments'] );
			
			$notes_description2 = $notes_info ['notes_description'];
			
			$notes_description2 = $notes_description2 . $description22;
			
			$sqlssd = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
			
			$this->db->query ( $sqlssd );
		}
		
		if ($active_note_info_actives != null && $active_note_info_actives != "") {
			
			foreach ( $active_note_info_actives as $active_note_info ) {
				
				if ($active_note_info ['keyword_id'] != null && $active_note_info ['keyword_id'] != "") {
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info ['keyword_id'] );
					
					$keyword_name1 = str_replace ( array (
							"\r",
							"\n" 
					), '', $keywordData2 ['keyword_name'] );
					
					$notes_description2 = $notes_info ['notes_description'];
					
					if ($keywordData2 ['monitor_time'] == '1') {
						
						if ($keywordData2 ['end_relation_keyword'] == '1') {
							$a3 = array ();
							$a3 ['keyword_id'] = $keywordData2 ['relation_keyword_id'];
							$a3 ['user_id'] = $user_info ['username'];
							$a3 ['facilities_id'] = $facilities_id;
							$a3 ['is_monitor_time'] = '1';
							
							$active_note_info2 = $this->model_notes_notes->getNotebyactivenote ( $a3 );
							
							if ($data ['override_monitor_time_user_id_checkbox'] == '1') {
								$note_info = $this->model_notes_notes->getNote ( $this->request->post ['override_monitor_time_user_id'] );
								
								$a3e = array ();
								$a3e ['notes_id'] = $note_info ['notes_id'];
								$a3e ['facilities_id'] = $facilities_id;
								$a3e ['is_monitor_time'] = '1';
								$active_note_info2e = $this->getNotebyactivenote ( $a3e );
								
								$keywordData21 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info2e ['keyword_id'] );
								
								$keywordData212 = $this->model_setting_keywords->getkeywordDetail ( $keywordData21 ['relation_keyword_id'] );
								
								if ($active_note_info2e ['keyword_id'] != "" && $active_note_info2e ['keyword_id'] != null) {
									
									$sqlda2d = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2e ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2e ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2e ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									
									$this->db->query ( $sqlda2d );
									
									$start_date = new DateTime ( $note_info ['date_added'] );
									$since_start = $start_date->diff ( new DateTime ( $update_date ) );
									
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
									
									$keyword_name441 = $keywordData212 ['keyword_name'] . ' | ENDED | ' . $caltime . ' | Originally Started by ' . $note_info ['user_id'] . ' at | ' . date ( 'm-d-Y h:i A', strtotime ( $note_info ['date_added'] ) );
									$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
									
									$notes_description2 = $notes_description2 . $description22;
									
									$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', status_total_time ='" . $status_total_time . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									
									$this->db->query ( $sqld );
									
									$sqldaff = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET override_monitor_time_user_id = '" . $this->db->escape ( $note_info ['user_id'] ) . "', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' ";
									
									$this->db->query ( $sqldaff );
									
									$keywordData23 = $this->model_setting_keywords->getkeywordDetail ( $keywordData212 ['keyword_id'] );
									
									/*
									 * $this->load->model('setting/image');
									 *
									 * $file16 =
									 * 'icon/'.$keywordData23['keyword_image'];
									 * $newfile84 =
									 * $this->model_setting_image->resize($file16,
									 * 50, 50);
									 * $newfile216 = DIR_IMAGE . $newfile84;
									 * $file124 = HTTP_SERVER .
									 * 'image/'.$newfile84;
									 *
									 * $imageData132 =
									 * base64_encode(file_get_contents($newfile216));
									 *
									 * if($newfile84 != null && $newfile84 !=
									 * ""){
									 * $keyword_icon =
									 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
									 * }else{
									 * $keyword_icon = '';
									 * }
									 */
									$keyword_icon = '';
									
									$keyword_file = $keywordData23 ['keyword_image'];
									
									$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $keywordData23 ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData23 ['keyword_name'] ) . "', active_tag = '" . $this->db->escape ( $keywordData23 ['active_tag'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "',keyword_file_url='" . $this->db->escape ( $keyword_icon ) . "', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									
									$this->db->query ( $sqld22a2 );
									
									$sqldaffn = "UPDATE `" . DB_PREFIX . "notes` SET assign_to = '" . $this->db->escape ( $note_info ['user_id'] ) . "', notes_conut ='0', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									$this->db->query ( $sqldaffn );
								}
							} else {
								$keywordData21 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info2 ['keyword_id'] );
								
								$keywordData212 = $this->model_setting_keywords->getkeywordDetail ( $keywordData21 ['relation_keyword_id'] );
								
								$sqlda2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2 ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2 ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2 ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								
								$this->db->query ( $sqlda2 );
								
								$start_date = new DateTime ( $active_note_info2 ['date_added'] );
								$since_start = $start_date->diff ( new DateTime ( $update_date ) );
								
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
								
								$keyword_name441 = $keywordData212 ['keyword_name'] . ' | ENDED | ' . $caltime . ' | ';
								$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
								
								$notes_description2 = $notes_description2 . $description22;
								
								$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								
								$this->db->query ( $sqld );
								
								$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '0', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $active_note_info ['keyword_id'] . "' ";
								
								$this->db->query ( $sqlda );
								
								$sqldaffn = "UPDATE `" . DB_PREFIX . "notes` SET assign_to = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								$this->db->query ( $sqldaffn );
							}
						} else {
							
							$a3 = array ();
							$a3 ['keyword_id'] = $active_note_info ['keyword_id'];
							$a3 ['user_id'] = $user_info ['username'];
							$a3 ['is_monitor_time'] = '1';
							$a3 ['facilities_id'] = $facilities_id;
							$active_note_info2 = $this->getNotebyactivenote ( $a3 );
							
							// var_dump($active_note_info2);
							// echo "<hr>";
							
							if ($data ['override_monitor_time_user_id_checkbox'] == '1') {
								
								$note_info = $this->model_notes_notes->getNote ( $this->request->post ['override_monitor_time_user_id'] );
								
								$a3e = array ();
								$a3e ['notes_id'] = $note_info ['notes_id'];
								$a3e ['facilities_id'] = $facilities_id;
								$a3e ['is_monitor_time'] = '1';
								$active_note_info2e = $this->getNotebyactivenote ( $a3e );
								// var_dump($active_note_info2e);
								// echo "<hr>";
								if ($active_note_info2e ['keyword_id'] != "" && $active_note_info2e ['keyword_id'] != null) {
									
									$sqlda2d = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2e ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2e ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2e ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									
									$this->db->query ( $sqlda2d );
									
									$start_date = new DateTime ( $note_info ['date_added'] );
									$since_start = $start_date->diff ( new DateTime ( $update_date ) );
									
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
									
									$keyword_name441 = $active_note_info2e ['keyword_name'] . ' | ENDED | ' . $caltime . ' | Originally Started by ' . $note_info ['user_id'] . ' at | ' . date ( 'm-d-Y h:i A', strtotime ( $note_info ['date_added'] ) );
									$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
									
									$notes_description2 = $notes_description2 . $description22;
									
									$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									
									$this->db->query ( $sqld );
									
									$sqldaff = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET override_monitor_time_user_id = '" . $this->db->escape ( $note_info ['user_id'] ) . "', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $active_note_info2e ['keyword_id'] . "' ";
									
									$this->db->query ( $sqldaff );
									
									$sqldaffn = "UPDATE `" . DB_PREFIX . "notes` SET assign_to = '" . $this->db->escape ( $note_info ['user_id'] ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									$this->db->query ( $sqldaffn );
									
									$keywordData23 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info2e ['keyword_id'] );
									
									// var_dump($keywordData23);
									// echo "<hr>";
									
									if ($keywordData23 ['relation_keyword_id'] != null && $keywordData23 ['relation_keyword_id'] != "0") {
										
										$keywordData23r = $this->model_setting_keywords->getkeywordDetail ( $keywordData23 ['relation_keyword_id'] );
										
										// var_dump($keywordData23r);
										/*
										 * $this->load->model('setting/image');
										 *
										 * $file16 =
										 * 'icon/'.$keywordData23r['keyword_image'];
										 * $newfile84 =
										 * $this->model_setting_image->resize($file16,
										 * 50, 50);
										 * $newfile216 = DIR_IMAGE . $newfile84;
										 * $file124 = HTTP_SERVER .
										 * 'image/'.$newfile84;
										 *
										 * $imageData132 =
										 * base64_encode(file_get_contents($newfile216));
										 *
										 * if($newfile84 != null && $newfile84
										 * != ""){
										 * $keyword_icon =
										 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
										 * }else{
										 * $keyword_icon = '';
										 * }
										 */
										$keyword_icon = '';
										
										$keyword_file = $keywordData23r ['keyword_image'];
										
										$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $keywordData23r ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData23r ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "',keyword_file_url='" . $this->db->escape ( $keyword_icon ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
										
										$this->db->query ( $sqld22a2 );
										
										$note_infou = $this->model_notes_notes->getNote ( $notes_id );
										
										// var_dump($note_infou);
										
										$notes_description2 = $note_infou ['notes_description'];
										
										$notes_description2 = str_ireplace ( $keywordData23 ['keyword_name'], $keywordData23r ['keyword_name'], $notes_description2 );
										
										// var_dump($notes_description2);
										
										$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
										$this->db->query ( $sql1 );
									}
								}
							} else if ($active_note_info2 ['keyword_id'] != null && $active_note_info2 ['keyword_id'] != "") {
								
								$sqlda2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2 ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2 ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2 ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								
								$this->db->query ( $sqlda2 );
								
								$start_date = new DateTime ( $active_note_info2 ['date_added'] );
								$since_start = $start_date->diff ( new DateTime ( $update_date ) );
								
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
								
								$keyword_name441 = $keywordData2 ['keyword_name'] . ' | ENDED | ' . $caltime . ' | ';
								$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
								
								$notes_description2 = $notes_description2 . $description22;
								
								$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								
								$this->db->query ( $sqld );
								
								$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '0', status_total_time ='" . $status_total_time . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $active_note_info ['keyword_id'] . "' ";
								
								$this->db->query ( $sqlda );
								
								$sqldaffn = "UPDATE `" . DB_PREFIX . "notes` SET assign_to = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								$this->db->query ( $sqldaffn );
								
								if ($keywordData2 ['relation_keyword_id'] != null && $keywordData2 ['relation_keyword_id'] != "0") {
									$this->load->model ( 'setting/image' );
									
									$keywordData23rs = $this->model_setting_keywords->getkeywordDetail ( $keywordData2 ['relation_keyword_id'] );
									
									/*
									 * $file16 =
									 * 'icon/'.$keywordData23rs['keyword_image'];
									 * $newfile84 =
									 * $this->model_setting_image->resize($file16,
									 * 50, 50);
									 * $newfile216 = DIR_IMAGE . $newfile84;
									 * $file124 = HTTP_SERVER .
									 * 'image/'.$newfile84;
									 *
									 * $imageData132 =
									 * base64_encode(file_get_contents($newfile216));
									 *
									 * if($newfile84 != null && $newfile84
									 * != ""){
									 * $keyword_icon =
									 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
									 * }else{
									 * $keyword_icon = '';
									 * }
									 */
									$keyword_icon = '';
									
									$keyword_file = $keywordData23rs ['keyword_image'];
									
									$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $keywordData23rs ['keyword_id'] ) . "',keyword_name = '" . $this->db->escape ( $keywordData23rs ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "',keyword_file_url='" . $this->db->escape ( $keyword_icon ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_id = '" . ( int ) $active_note_info2 ['keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
									
									$this->db->query ( $sqld22a2 );
									
									$note_infou = $this->model_notes_notes->getNote ( $notes_id );
									
									// var_dump($note_infou);
									
									$notes_description2 = $note_infou ['notes_description'];
									
									$notes_description2 = str_ireplace ( $keywordData2 ['keyword_name'], $keywordData23rs ['keyword_name'], $notes_description2 );
									
									// var_dump($notes_description2);
									
									$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
									$this->db->query ( $sql1 );
								}
							} else {
								
								$keyword_name441 = $keywordData2 ['keyword_name'] . ' | STARTED | ';
								$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
								
								$notes_description2 = $notes_description2 . $description22;
								
								$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								
								$this->db->query ( $sqld );
								
								$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '1' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' ";
								
								$this->db->query ( $sqlda );
								
								$sqldaffn = "UPDATE `" . DB_PREFIX . "notes` SET assign_to = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
								$this->db->query ( $sqldaffn );
							}
						}
					}
				}
			}
		}
		
		// var_dump($notes_description2);
		
		// die;
		
		// $this->load->model('api/encrypt');
		
		// $esign = $this->model_api_encrypt->encrypt3($signature);
		
		// $esign = strtoupper(md5($signature.'Username20Jun96'));
		// var_dump($esign);
		
		// echo "<hr>";
		
		// $esignss = $this->model_api_encrypt->decrypt3($esign);
		
		// var_dump($esignss);
		/*
		 * $sql = "UPDATE `" . DB_PREFIX . "notes` SET user_id = '" . $this->db->escape($data['user_id']) . "', status = '1', signature = '" . $signature . "', signature_image = '" . $fileName . "', notes_pin = '" . $this->db->escape($notes_pin) . "', emp_tag_id = '" . $this->db->escape($data['emp_tag_id']) . "',tags_id = '" . $this->db->escape($data['tags_id']) . "',update_date = '" . $update_date .
		 * "', notes_conut='0' WHERE notes_id = '" . (int) $notes_id . "' and facilities_id = '" . (int) $facilities_id . "' ";
		 */
		
		$sql = "CALL
          updateNotes('" . $this->db->escape ( $user_info ['username'] ) . "','" . $this->db->escape ( $signature ) . "','" . $this->db->escape ( $notes_pin ) . "','" . $update_date . "','" . $notes_id . "','" . $facilities_id . "')";
		
		$this->db->query ( $sql );
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "") {
			$tadata = array ();
			$this->updateNotesTag22 ( $data ['emp_tag_id'], $notes_id, $data ['tags_id'], $update_date, $tadata );
		}
		
		$this->load->model ( 'setting/tags' );
		if (! empty ( $data ['tagides'] )) {
			foreach ( $data ['tagides'] as $tagid ) {
				$tag_info = $this->model_setting_tags->getTag ( $tagid );
				$tadata = array ();
				$this->updateNotesTag22 ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['notes_file'] = $data ['notes_file'];
		$adata ['tagides'] = $data ['tagides'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'updatenotes', $adata, 'query' );
		
		if ($this->config->get ( 'config_realtime_data' ) == '1') {
			$this->load->model ( 'api/realtime' );
			$realdata = array ();
			$realdata ['facilities_id'] = $facilities_id;
			$realdata ['notes_id'] = $notes_id;
			$this->model_api_realtime->addrealtime ( $realdata );
		}
	}
	public function getforms($notes_id) {
		$notes_info = $this->getNote ( $notes_id );
		if ($notes_info ['is_archive'] == '4') {
			$sql = "SELECT archive_forms_id,forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,archive_notes_id,is_final FROM `" . DB_PREFIX . "archive_forms` WHERE archive_notes_id = '" . ( int ) $notes_id . "' and form_parent_id = 0 ";
			$query = $this->db->query ( $sql );
		} else {
			$sql = "SELECT forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,is_final,image_url,image_name FROM `" . DB_PREFIX . "forms` WHERE notes_id = '" . ( int ) $notes_id . "' and form_parent_id = 0 ";
			$query = $this->db->query ( $sql );
		}
		return $query->rows;
	}
	public function getformsa($notes_id) {
		$sql = "SELECT archive_forms_id,forms_id,form_type_id,form_type,form_description,rules_form_description,date_added,notes_id,user_id,signature,notes_pin,form_date_added,incident_number,facilities_id,notes_type,form_signature,assessment_id,custom_form_type,design_forms,date_updated,upload_file,tags_id,parent_id,is_discharge,tagstatus_id,is_archive,archive_notes_id,is_final FROM `" . DB_PREFIX . "archive_forms` WHERE archive_notes_id = '" . ( int ) $notes_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getnotesBytasks($notes_id, $type) {
		$sql = "SELECT notes_by_task_id,notes_id,locations_id,task_type, task_content,	user_id,date_added,signature,notes_pin,notes_type,task_time,media_url,capacity,location_name,location_type,	notes_task_type,tags_id,drug_name,dose,drug_type,quantity,frequency,instructions,count,createtask_by_group_id,task_comments,medication_attach_url,medication_file_upload,facilities_id,tags_medication_id,tags_medication_details_id,task_customlistvalues_id,tags_ids,room_current_date_time,out_tags_ids,role_call,out_capacity,refuse FROM `" . DB_PREFIX . "notes_by_task` WHERE notes_id = '" . ( int ) $notes_id . "' and notes_task_type = '" . $type . "' ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function addreview($data, $facilities_id, $add_date) {
		// date_default_timezone_set($this->session->data['time_zone_1']);
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$date = str_replace ( '-', '/', $add_date );
		
		$res = explode ( "/", $date );
		$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
		/* $createdate1 = $add_date; */
		$createtime1 = date ( 'H:i:s' );
		$createDate2 = $createdate1 . $createtime1;
		
		if ($add_date != null && $add_date != "") {
			$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
		} else {
			$createDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		}
		
		if ($add_date != null && $add_date != "") {
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
		} else {
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		}
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$signature = "";
		if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
			$this->load->model ( 'api/savesignature' );
			$sigdata = array ();
			$sigdata ['upload_file'] = $data ['imgOutput'];
			$sigdata ['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
			
			$signature = $signaturestatus;
		}
		
		$this->db->query ( "INSERT INTO `" . DB_PREFIX . "reviewed_by` SET facilities_id = '" . $facilities_id . "', user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_type = '" . $this->db->escape ( $data ['notes_type'] ) . "', signature = '" . $signature . "', notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "',  note_date = '" . $noteDate . "', signature_image = '" . $fileName . "', date_added = '" . $createDate . "' " );
		
		$this->load->model ( 'activity/activity' );
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['date_added'] = $createDate;
		$adata ['note_date'] = $noteDate;
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'addreview', $adata, 'query' );
	}
	public function updateNoteColor($notes_id, $text_color, $update_date) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '#" . $text_color . "', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['update_date'] = $update_date;
		$adata ['text_color'] = $text_color;
		$this->model_activity_activity->addActivitySave ( 'updateNoteColor', $adata, 'query' );
	}
	public function updateNoteHigh($notes_id, $highlighter_id, $update_date) {
		$this->load->model ( 'setting/highlighter' );
		$highlighterData = $this->model_setting_highlighter->gethighlighter ( $highlighter_id );
		$highlighter_value = $highlighterData ['highlighter_value'];
		
		if ($highlighter_id == '21') {
			$highlighter = '0';
			$highlighter_value1 = '';
		} else {
			$highlighter = $highlighter_id;
			$highlighter_value1 = $highlighter_value;
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '" . $highlighter . "', highlighter_value = '" . $highlighter_value1 . "', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['update_date'] = $update_date;
		$adata ['highlighter_id'] = $highlighter;
		$adata ['highlighter_value'] = $highlighter_value1;
		$this->model_activity_activity->addActivitySave ( 'updateNoteHigh', $adata, 'query' );
	}
	public function updateStrikeNotes($data, $notes_id, $facilities_id, $update_date) {
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$signature = "";
		if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
			$this->load->model ( 'api/savesignature' );
			$sigdata = array ();
			$sigdata ['upload_file'] = $data ['imgOutput'];
			$sigdata ['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
			
			$signature = $signaturestatus;
		}
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET text_color_cut = '1', strike_user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', strike_note_type = '" . $this->db->escape ( $data ['strike_note_type'] ) . "', strike_signature = '" . $signature . "', strike_signature_image = '" . $fileName . "', strike_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', strike_date_added = '" . $noteDate . "', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . $facilities_id . "' " );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET strike_note_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		if ($facility ['is_enable_add_notes_by'] == '3') {
			$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET strike_note_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql13 );
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['strike_date_added'] = $noteDate;
		$adata ['update_date'] = $update_date;
		$adata ['strike_user_id'] = $data ['user_id'];
		$adata ['strike_username'] = $data ['username'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'updateStrikeNotes', $adata, 'query' );
	}
	public function updateStrikeNotesPrivate($data, $notes_id, $facilities_id, $update_date) {
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET text_color_cut = '1', strike_user_id = '" . $this->db->escape ( $this->session->data ['username'] ) . "', strike_note_type = '', strike_signature = '', strike_signature_image = '', strike_pin = '', strike_date_added = '" . $noteDate . "', is_private_strike = '1', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . $facilities_id . "' " );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['strike_date_added'] = $noteDate;
		$adata ['update_date'] = $update_date;
		$adata ['strike_user_id'] = $data ['username'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'updateStrikeNotesPrivate', $adata, 'query' );
	}
	public function editnotes($notes_id, $data, $facilities_id, $update_date) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape ( $data ['notes_description'] ) . "', highlighter_id = '" . $this->db->escape ( $data ['highlighter_id'] ) . "', notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', notes_file = '" . $this->db->escape ( $data ['notes_file'] ) . "', user_id = '" . $this->db->escape ( $data ['user_id'] ) . "', status = '1', notetime = '" . $data ['notetime'] . "', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $data ['notes_description'];
		$adata ['update_date'] = $update_date;
		$adata ['highlighter_id'] = $data ['highlighter_id'];
		$adata ['notes_file'] = $data ['notes_file'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'editnotes', $adata, 'query' );
	}
	public function deletenotes($notes_id) {
		// $this->db->query("DELETE FROM `" . DB_PREFIX . "notes` WHERE notes_id = '" . (int) $notes_id . "'");
	}
	public function getnotes($notes_id) {
		$query = $this->db->query ( "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,is_user_face,user_file,is_approval_required_forms_id,linked_id,parent_facilities_id,is_comment,in_total,out_total,manual_total,shift_id FROM `" . DB_PREFIX . "notes` WHERE notes_id = '" . ( int ) $notes_id . "'" );
		
		return $query->row;
	}
	public function getnotesbyparent($parent_id) {
		$query = $this->db->query ( "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,is_approval_required_forms_id,linked_id,in_total,out_total,manual_total,shift_id FROM `" . DB_PREFIX . "notes` WHERE parent_id = '" . ( int ) $parent_id . "'" );
		
		return $query->rows;
	}
	public function getnotesbyparent2($parent_id) {
		$query = $this->db->query ( "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,is_approval_required_forms_id,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE parent_id = '" . ( int ) $parent_id . "' GROUP BY facilities_id " );
		
		return $query->rows;
	}
	public function getnotesbyparent3($parent_id, $facilities_id, $notes_id) {
		$query = $this->db->query ( "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,is_approval_required_forms_id,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE parent_id = '" . ( int ) $parent_id . "' and facilities_id = '" . ( int ) $facilities_id . "'" );
		
		return $query->rows;
	}
	
	public function getnotessx($data = array()) {
		// var_dump($data);
		// date_default_timezone_set($this->session->data['time_zone_1']);
		$status = '1';
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		if($this->session->data ['search_facilities_all'] == 'All'){
		
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				if ($data ['search_facilities_id'] == $data ['facilities_id']) {
					$ddss [] = $facilityinfo ['notes_facilities_ids'];
					$ddss [] = $data ['facilities_id'];
					
					$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
					
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['notes_facilities_ids'];
						}
					}
					
					$sssssdd = implode ( ",", $ddss );
					$faculities_ids = $sssssdd;
				} else {
					if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
						$facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
					} else {
						$ddss [] = $facilityinfo ['notes_facilities_ids'];
						$ddss [] = $data ['facilities_id'];
						
						$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
						
						$abdcg = array_unique ( $sssssddsg );
						$cids = array ();
						foreach ( $abdcg as $fid ) {
							$cids [] = $fid;
						}
						$abdcgs = array_unique ( $cids );
						foreach ( $abdcgs as $fid2 ) {
							$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
							if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
								$ddss [] = $facilityinfo ['notes_facilities_ids'];
							}
						}
						
						$sssssdd = implode ( ",", $ddss );
						$faculities_ids = $sssssdd;
					}
				}
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					$facilities_id = $this->db->escape ( $data ['facilities_id'] );
				}
			}
		}else{
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$facilities_id = $this->db->escape ( $data ['facilities_id'] );
			}
		}
		
		/*
		 * if ( $data['notes_facilities_ids'] != null && $data['notes_facilities_ids'] != "" ) {
		 *
		 * if($data['search_facilities_id'] == $data['facilities_id']){
		 * $faculities_ids = $data['notes_facilities_ids'];
		 *
		 * }else{
		 * if ($data['search_facilities_id'] != null && $data['search_facilities_id'] != "") {
		 * $facilities_id = $this->db->escape($data['search_facilities_id']);
		 * }else{
		 * $faculities_ids = $data['notes_facilities_ids'];
		 * }
		 *
		 * }
		 *
		 * }else{
		 * if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		 * $facilities_id = $this->db->escape($data['facilities_id']);
		 * }
		 * }
		 */
		
		/*
		 * if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		 * $facilities_id = $data['facilities_id'];
		 * }
		 */
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_id = $this->db->escape ( $data ['user_id'] );
		}
		
		if ($data ['customer_key'] != null && $data ['customer_key'] != "") {
			// $sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
			
			$unique_id = $this->db->escape ( $data ['customer_key'] );
		}
		
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$n_notes_id = $data ['notes_id'];
		}
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "0") {
			$n_tags_id = $this->db->escape ( $data ['emp_tag_id'] );
		}
		
		if ($data ['tasktype'] != null && $data ['tasktype'] != "") {
			$n_tasktype = $this->db->escape ( $data ['tasktype'] );
		}
		
		if ($data ['tagstatus_id'] != null && $data ['tagstatus_id'] != "") {
			$n_tagstatus_id = "1";
		}
		if ($data ['manual_movement'] != null && $data ['manual_movement'] != "") {
			$n_manual_movement = "1";
		}
		
		/*
		 * if ($data['is_bedchk'] != null && $data['is_bedchk'] != "") {
		 * $sql .= " and n.tasktype = '11'";
		 * }
		 */
		
		if ($data ['notesid'] != null && $data ['notesid'] != "") {
			$n_notesid = $data ['notesid'];
		}
		
		if ($this->session->data ['isPrivate'] == '1') {
			// $sql .= " and n.user_id =
			// '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole ();
			// var_dump($useIds);
			// echo "<hr>";
			
			if ($useIds != null && $useIds != "") {
				$n_useIds = $useIds;
			}
		}
		
		if ($data ['highlighter'] != null && $data ['highlighter'] != "") {
			
			if ($data ['highlighter'] == 'all') {
				$data3 = array ();
				$this->load->model ( 'setting/highlighter' );
				$results = $this->model_setting_highlighter->gethighlighters ( $data3 );
				$userIds = array ();
				foreach ( $results as $result ) {
					if ($result ['highlighter_id'] != null && $result ['highlighter_id'] != "") {
						$userIds [] = $result ['highlighter_id'];
					}
				}
				$userIds1 = array_unique ( $userIds );
				
				$userIds2 = implode ( ",", $userIds1 );
				
				if ($userIds2 != null && $userIds2 != "") {
					$userIds2 = str_replace ( "all,", "", $userIds2 );
					$n_highlighter_ids = $userIds2;
				}
			} else {
				$n_highlighter_id = $data ['highlighter'];
			}
		}
		
		if ($data ['activenote'] != null && $data ['activenote'] != "") {
			
			if ($data ['activenote'] == 'all') {
				
				$query2 = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "keyword " );
				
				$results = $query2->rows;
				
				$userIds2 = array ();
				foreach ( $results as $result ) {
					if ($result ['keyword_image'] != null && $result ['keyword_image'] != "") {
						$userIds2 [] = $result ['keyword_image'];
					}
				}
				$userIds12 = array_unique ( $userIds2 );
				
				$userIds21 = implode ( ',', $userIds12 );
				
				if ($userIds21 != null && $userIds21 != "") {
					$userIds21 = str_replace ( "all,", "", $userIds21 );
					
					$n_keyword_files = $userIds21;
				}
			} else {
				
				if ($data ['relation_search'] == '1') {
					
					$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
					
					$keydata = $query2->row;
					
					// var_dump($keydata);
					// echo "<hr>";
					
					$query21 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $keydata ['relation_keyword_id'] . "'" );
					
					$keydata2 = $query21->row;
					
					// var_dump($keydata2);
					// echo "<hr>";
					
					$aaa = array ();
					if ($keydata2 ['keyword_image'] != null && $keydata2 ['keyword_image'] != "") {
						$sql .= " and ( nk.keyword_file = '" . $keydata ['keyword_image'] . "'";
						$sql .= " or nk.keyword_file = '" . $keydata2 ['keyword_image'] . "') ";
						
						$n_keyword_file_monitor_1 = $keydata ['keyword_image'];
						$n_keyword_file_monitor_2 = $keydata2 ['keyword_image'];
						$aaa [] = $keydata ['keyword_image'];
						$aaa [] = $keydata2 ['keyword_image'];
						// var_dump($aaa);
						// echo "<hr>";
						$userIds21 = implode ( ',', $aaa );
						$n_keyword_files = $userIds21;
						// var_dump($n_keyword_files);
						// echo "<hr>";
					} else {
						$sql .= " and nk.keyword_file = '" . $keydata ['keyword_image'] . "'";
						
						$n_keyword_file = $keydata ['keyword_image'];
					}
				} else {
					$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
					
					$keydata = $query2->row;
					
					$n_keyword_file = $keydata ['keyword_image'];
				}
			}
		}
		
		if ($data ['form_search']) {
			if ($data ['form_search'] == 'IncidentForm') {
				$n_form_type = " 1";
			}
			if ($data ['form_search'] == 'ChecklistForm') {
				$n_form_type = " 2";
			}
			
			if ($data ['form_search'] == 'BedCheckForm') {
				$n_task_type = " 1";
			}
			
			if ($data ['form_search'] == 'MedicationForm') {
				$n_task_type = " 2";
			}
			
			if ($data ['form_search'] == 'TransportationForm') {
				$n_task_type = " 3";
			}
			if ($data ['form_search'] == 'Intake') {
				$n_is_tag = " 2 ";
			}
			if ($data ['form_search'] == 'HealthForm') {
				$n_is_tag = " 1";
			}
			if ($data ['form_search'] == 'Case') {
				$n_is_tag = " 9";
			}
			
			if (is_numeric ( $data ['form_search'] )) {
				$n_custom_form_type = $data ['form_search'];
			}
		}
		
		$n_keyword = "";
		if ($data ['keyword'] != null && $data ['keyword'] != "") {
			$n_keyword = $this->db->escape ( strtolower ( $data ['keyword'] ) );
		}
		
		if ($data ['case_number'] != null && $data ['case_number'] != "") {
			$n_keyword .= ' ' . $this->db->escape ( strtolower ( $data ['case_number'] ) );
		}
		
		if ($data ['tag_status_id'] != null && $data ['tag_status_id'] != "") {
			
			$n_tag_status_id = $data ['tag_status_id'];
		}
		
		if ($data ['is_form'] != null && $data ['is_form'] != "") {
			
			$n_is_form = $data ['is_form'];
		}
		
		if ($data ['is_tasks'] != null && $data ['is_tasks'] != "") {
			
			$n_is_tasks = $data ['is_tasks'];
		}
		
		
		
		
		
		if ($data ['tag_classification_id'] != null && $data ['tag_classification_id'] != "") {
			
			$n_tag_classification_id = $data ['tag_classification_id'];
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
				
				$startTimeFrom = date ( 'H:i:s', strtotime ( $data ['search_time_start'] ) );
				$startTimeTo = date ( 'H:i:s', strtotime ( $data ['search_time_to'] ) );
				
				$startTimeFrom_o = '00:00:00';
				$startTimeTo_o = '23:59:59';
				
				$n_note_date_from_t = $startDate . " " . $startTimeFrom_o;
				$n_note_date_to_t = $endDate . " " . $startTimeTo_o;
				$n_note_date_from_time = $startTimeFrom;
				$n_note_date_to_time = $startTimeTo;
				
				// $sql .= " and (n.`notetime` BETWEEN '".$startTimeFrom."' AND
				// '".$startTimeTo."') ";
			} else {
				$startTimeFrom = '00:00:00';
				$startTimeTo = '23:59:59';
				
				$n_note_date_from = $startDate . " " . $startTimeFrom;
				$n_note_date_to = $endDate . " " . $startTimeTo;
			}
			
			// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
			// ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' or
			// f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND
			// '".$endDate." ".$startTimeTo."' ) ";
		}
		
		if ($data ['searchdate_app'] == '1') {
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				$date = str_replace ( '-', '/', $data ['searchdate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$startDate = $changedDate; /*
				                            * date('Y-m-d',
				                            * strtotime($data['searchdate']));
				                            */
				/* $endDate = date('Y-m-d'); */
				$endDate = $changedDate; /*
				                          * date('Y-m-d',
				                          * strtotime($data['searchdate']));
				                          */
				
				// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00'
				// AND '".$endDate." 23:59:59')";
				// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
				// 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added`
				// BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate."
				// 23:59:59' ) ";
				
				$n_note_date_from = $startDate . " 00:00:00";
				$n_note_date_to = $startDate . " 23:59:59";
			} else {
				if ($data ['advance_searchapp'] != '1' && $data ['advance_search'] != '1') {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					$startDate = date ( 'Y-m-d' );
					$endDate = date ( 'Y-m-d' );
					
					if ($data ['case_detail'] == '2') {
						// $sql .= " and `date_added` BETWEEN '".$startDate."
						// 00:00:00' AND '".$endDate." 23:59:59' ";
						// $sql .= " and ( n.`date_added` BETWEEN
						// '".$startDate." 00:00:00 ' AND '".$endDate."
						// 23:59:59' or f.`date_added` BETWEEN '".$startDate."
						// 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
						$n_note_date_from = $startDate . " 00:00:00";
						$n_note_date_to = $endDate . " 23:59:59";
					}
				}
			}
		}
		
		if ($data ['searchdate_app'] != "1") {
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				
				$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
				
				$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
				
				// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00'
				// AND '".$endDate." 23:59:59')";
				// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
				// 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added`
				// BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate."
				// 23:59:59' ) ";
				if ($data ['notetime'] == null && $data ['notetime'] == "") {
					$n_note_date_from = $startDate . " 00:00:00";
					$n_note_date_to = $endDate . " 23:59:59";
				}
			}
		}
		
		// var_dump($data['sync_data']);
		if ($data ['sync_data'] == "2") {
			
			date_default_timezone_set ( $data ['facilities_timezone'] );
			
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				// $startDate = date('Y-m-d', strtotime($data['searchdate']));
				// $endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$time = date ( 'H:i:s' );
				
				if ($data ['notetime'] != null && $data ['notetime'] != "") {
					$notetime = date ( 'H:i:s', strtotime ( "+10 seconds", strtotime ( $data ['notetime'] ) ) );
				} else {
					$notetime = '00:00:00';
				}
				
				$date_end = $endDate . ' ' . $time;
				$date_start = $endDate . ' ' . $notetime;
				// $sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND
				// '".$date_end."') ";
				
				$n_update_date_s = $date_start;
				$n_update_date_e = $date_end;
			}
		}
		
		if ($data ['sync_data'] == "3") {
			
			date_default_timezone_set ( $data ['facilities_timezone'] );
			
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				// $startDate = date('Y-m-d', strtotime($data['searchdate']));
				// $endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				// $time = date ( 'H:i:s' );
				$time = date ( 'H:i:s', strtotime ( "+60 minutes" ) );
				
				if ($data ['notetime'] != null && $data ['notetime'] != "") {
					$notetime = date ( 'H:i:s', strtotime ( "+10 seconds", strtotime ( $data ['notetime'] ) ) );
				} else {
					$notetime = '00:00:00';
				}
				
				$date_end = $endDate . ' ' . $time;
				$date_start = $endDate . ' ' . $notetime;
				// $sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND
				// '".$date_end."') ";
				
				$n_note_date_from = $date_start;
				$n_note_date_to = $date_end;
			}
		}
		
		if ($data ['group'] == '1') {
			// $n_group = " 1 ";
			// $sql .= " ORDER BY n.date_added DESC";
		}
		
		if ($data ['sync_data'] != "2") {
			if ($data ['searchdate_app'] == "1") {
				if ($data ['advance_searchapp'] == "1") {
					$n_orderby = " n.date_added ASC";
				} else {
					if ($data ['is_ajax_load'] == "1") {
						$n_orderby = " n.notetime ASC";
					} else {
						$n_orderby = " n.notetime ASC";
					}
				}
			} else {
				if ($data ['advance_search'] == "1") {
					
					if ($data ['advance_date_desc'] == "1") {
						$n_orderby = " n.date_added DESC";
					} else {
						$n_orderby = "  n.date_added ASC";
					}
				} else {
					if ($data ['is_ajax_load'] == "1") {
						$n_orderby = " n.notetime ASC";
					} else {
						$n_orderby = " n.notetime ASC";
					}
				}
			}
		} else {
			
			$n_orderby = " n.update_date ASC";
		}
		
		if ($data ['current_date'] != '1') {
			if ($data ['advance_searchapp'] == '1') {
				if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
					if ($data ['start'] < 0) {
						$data ['start'] = 0;
					}
					
					if ($data ['limit'] < 1) {
						$data ['limit'] = 20;
					}
					
					$n_start = $data ['start'];
					$n_limit = $data ['limit'];
				}
			}
			
			if ($data ['advance_searchapp'] != '1') {
				if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
					if ($data ['start'] < 0) {
						$data ['start'] = 0;
					}
					
					if ($data ['limit'] < 1) {
						$data ['limit'] = 20;
					}
					
					$n_start = $data ['start'];
					$n_limit = $data ['limit'];
				}
			}
		}
		
		if ($data ['advance_searchapp'] == '1' || $data ['advance_search'] == '1') {
			
			$sql = " CALL getNotes('" . $status . "', '" . $facilities_id . "', '" . $user_id . "', '" . $n_notes_id . "', '" . $n_tags_id . "', '" . $n_tasktype . "', '" . $n_tagstatus_id . "','" . $n_notesid . "','" . $n_useIds . "','" . $n_highlighter_ids . "','" . $n_highlighter_id . "','" . $n_keyword_files . "', '" . $n_keyword_file . "', '" . $n_form_type . "', '" . $n_task_type . "', '" . $n_is_tag . "', '" . $n_custom_form_type . "','" . $n_keyword . "','" . $n_note_date_from . "','" . $n_note_date_to . "','" . $n_update_date_s . "','" . $n_update_date_e . "', '" . $n_note_date_from_t . "', '" . $n_note_date_to_t . "', '" . $n_note_date_from_time . "', '" . $n_note_date_to_time . "', '" . $faculities_ids . "', '" . $n_group . "','" . $n_orderby . "','" . $n_start . "','" . $n_limit . "', '" . $n_tag_status_id . "', '" . $n_tag_classification_id . "', '" . $n_manual_movement . "', '" . $n_is_form . "', '" . $n_is_tasks . "') ";
			
		} else {
			$sql = " CALL getNotesbyOneDate('" . $status . "', '" . $facilities_id . "', '" . $user_id . "', '" . $n_notes_id . "', '" . $n_tags_id . "', '" . $n_tasktype . "', '" . $n_tagstatus_id . "','" . $n_notesid . "','" . $n_useIds . "','" . $n_highlighter_ids . "','" . $n_highlighter_id . "','" . $n_keyword_files . "', '" . $n_keyword_file . "', '" . $n_form_type . "', '" . $n_task_type . "', '" . $n_is_tag . "', '" . $n_custom_form_type . "','" . $n_keyword . "','" . $n_note_date_from . "','" . $n_note_date_to . "','" . $n_update_date_s . "','" . $n_update_date_e . "', '" . $n_note_date_from_t . "', '" . $n_note_date_to_t . "', '" . $n_note_date_from_time . "', '" . $n_note_date_to_time . "', '" . $faculities_ids . "', '" . $n_group . "','" . $n_orderby . "','" . $n_start . "','" . $n_limit . "') ";
		}
		
		//echo "<hr>";
		//echo $sql;
		//echo "<hr>";
		//die;
		$query = $this->db->query ( $sql );
		
		// var_dump(count($query->rows));
		
		return $query->rows;
	}
	
	
	public function getnotess($data = array()) {
		// var_dump($data);
		// date_default_timezone_set($this->session->data['time_zone_1']);
		$status = '1';
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		$unique_id = $facilityinfo ['customer_key'];
		
		// var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
		}
		
		if ($data ['is_app'] != '1') {
			
			if ($customers ['all_notes_setting'] != '0') {
				
				if ($this->session->data ['search_facilities_all'] == 'All') {
					
					if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
						if ($data ['search_facilities_id'] == $data ['facilities_id']) {
							$ddss [] = $facilityinfo ['notes_facilities_ids'];
							$ddss [] = $data ['facilities_id'];
							
							$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
							
							$abdcg = array_unique ( $sssssddsg );
							$cids = array ();
							foreach ( $abdcg as $fid ) {
								$cids [] = $fid;
							}
							$abdcgs = array_unique ( $cids );
							foreach ( $abdcgs as $fid2 ) {
								$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
								if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
									$ddss [] = $facilityinfo ['notes_facilities_ids'];
								}
							}
							
							$sssssdd = implode ( ",", $ddss );
							$faculities_ids = $sssssdd;
						} else {
							if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
								$facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
							} else {
								$ddss [] = $facilityinfo ['notes_facilities_ids'];
								$ddss [] = $data ['facilities_id'];
								
								$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
								
								$abdcg = array_unique ( $sssssddsg );
								$cids = array ();
								foreach ( $abdcg as $fid ) {
									$cids [] = $fid;
								}
								$abdcgs = array_unique ( $cids );
								foreach ( $abdcgs as $fid2 ) {
									$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
									if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
										$ddss [] = $facilityinfo ['notes_facilities_ids'];
									}
								}
								
								$sssssdd = implode ( ",", $ddss );
								$faculities_ids = $sssssdd;
							}
						}
					} else {
						if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
							$facilities_id = $this->db->escape ( $data ['facilities_id'] );
						}
					}
				} else {
					
					if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
						$facilities_id = $this->db->escape ( $data ['facilities_id'] );
					}
				}
			} else {
				
				if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
					
					if ($data ['search_facilities_id'] == $data ['facilities_id']) {
						$ddss [] = $facilityinfo ['notes_facilities_ids'];
						$ddss [] = $data ['facilities_id'];
						
						$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
						
						$abdcg = array_unique ( $sssssddsg );
						$cids = array ();
						foreach ( $abdcg as $fid ) {
							$cids [] = $fid;
						}
						$abdcgs = array_unique ( $cids );
						foreach ( $abdcgs as $fid2 ) {
							$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
							if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
								$ddss [] = $facilityinfo ['notes_facilities_ids'];
							}
						}
						
						$sssssdd = implode ( ",", $ddss );
						$faculities_ids = $sssssdd;
					} else {
						if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
							$facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
						} else {
							
							$ddss [] = $facilityinfo ['notes_facilities_ids'];
							$ddss [] = $data ['facilities_id'];
							
							$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
							
							$abdcg = array_unique ( $sssssddsg );
							$cids = array ();
							foreach ( $abdcg as $fid ) {
								$cids [] = $fid;
							}
							$abdcgs = array_unique ( $cids );
							foreach ( $abdcgs as $fid2 ) {
								$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
								if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
									$ddss [] = $facilityinfo ['notes_facilities_ids'];
								}
							}
							
							$sssssdd = implode ( ",", $ddss );
							$faculities_ids = $sssssdd;
						}
					}
				} else {
					
					if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
						$facilities_id = $this->db->escape ( $data ['facilities_id'] );
					}
				}
			}
		} else if ($data ['is_app'] == '1') {
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				if ($data ['search_facilities_id'] == $data ['facilities_id']) {
					$ddss [] = $facilityinfo ['notes_facilities_ids'];
					$ddss [] = $data ['facilities_id'];
					
					$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
					
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['notes_facilities_ids'];
						}
					}
					
					$sssssdd = implode ( ",", $ddss );
					$faculities_ids = $sssssdd;
				} else {
					if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
						$facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
					} else {
						$ddss [] = $facilityinfo ['notes_facilities_ids'];
						$ddss [] = $data ['facilities_id'];
						
						$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
						
						$abdcg = array_unique ( $sssssddsg );
						$cids = array ();
						foreach ( $abdcg as $fid ) {
							$cids [] = $fid;
						}
						$abdcgs = array_unique ( $cids );
						foreach ( $abdcgs as $fid2 ) {
							$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
							if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
								$ddss [] = $facilityinfo ['notes_facilities_ids'];
							}
						}
						
						$sssssdd = implode ( ",", $ddss );
						$faculities_ids = $sssssdd;
					}
				}
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					$facilities_id = $this->db->escape ( $data ['facilities_id'] );
				}
			}
		} else {
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$facilities_id = $this->db->escape ( $data ['facilities_id'] );
			}
		}
		
		/*
		 * if ( $data['notes_facilities_ids'] != null && $data['notes_facilities_ids'] != "" ) {
		 *
		 * if($data['search_facilities_id'] == $data['facilities_id']){
		 * $faculities_ids = $data['notes_facilities_ids'];
		 *
		 * }else{
		 * if ($data['search_facilities_id'] != null && $data['search_facilities_id'] != "") {
		 * $facilities_id = $this->db->escape($data['search_facilities_id']);
		 * }else{
		 * $faculities_ids = $data['notes_facilities_ids'];
		 * }
		 *
		 * }
		 *
		 * }else{
		 * if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		 * $facilities_id = $this->db->escape($data['facilities_id']);
		 * }
		 * }
		 */
		
		/*
		 * if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		 * $facilities_id = $data['facilities_id'];
		 * }
		 */
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_id = $this->db->escape ( $data ['user_id'] );
		}
		
		if ($data ['customer_key'] != null && $data ['customer_key'] != "") {
			// $sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
			
			$unique_id = $this->db->escape ( $data ['customer_key'] );
		}
		
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$n_notes_id = $data ['notes_id'];
		}
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "0") {
			$n_tags_id = $this->db->escape ( $data ['emp_tag_id'] );
		}
		
		if ($data ['tasktype'] != null && $data ['tasktype'] != "") {
			$n_tasktype = $this->db->escape ( $data ['tasktype'] );
		}
		
		if ($data ['tagstatus_id'] != null && $data ['tagstatus_id'] != "") {
			$n_tagstatus_id = "1";
		}
		if ($data ['manual_movement'] != null && $data ['manual_movement'] != "") {
			$n_manual_movement = "1";
		}
		
		/*
		 * if ($data['is_bedchk'] != null && $data['is_bedchk'] != "") {
		 * $sql .= " and n.tasktype = '11'";
		 * }
		 */
		
		if ($data ['notesid'] != null && $data ['notesid'] != "") {
			$n_notesid = $data ['notesid'];
		}
		
		if ($this->session->data ['isPrivate'] == '1') {
			// $sql .= " and n.user_id =
			// '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole ();
			// var_dump($useIds);
			// echo "<hr>";
			
			if ($useIds != null && $useIds != "") {
				$n_useIds = $useIds;
			}
		}
		
		if ($data ['highlighter'] != null && $data ['highlighter'] != "") {
			
			if ($data ['highlighter'] == 'all') {
				$data3 = array ();
				$this->load->model ( 'setting/highlighter' );
				$results = $this->model_setting_highlighter->gethighlighters ( $data3 );
				$userIds = array ();
				foreach ( $results as $result ) {
					if ($result ['highlighter_id'] != null && $result ['highlighter_id'] != "") {
						$userIds [] = $result ['highlighter_id'];
					}
				}
				$userIds1 = array_unique ( $userIds );
				
				$userIds2 = implode ( ",", $userIds1 );
				
				if ($userIds2 != null && $userIds2 != "") {
					$userIds2 = str_replace ( "all,", "", $userIds2 );
					$n_highlighter_ids = $userIds2;
				}
			} else {
				$n_highlighter_id = $data ['highlighter'];
			}
		}
		
		if ($data ['activenote'] != null && $data ['activenote'] != "") {
			
			if ($data ['activenote'] == 'all') {
				
				$query2 = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "keyword " );
				
				$results = $query2->rows;
				
				$userIds2 = array ();
				foreach ( $results as $result ) {
					if ($result ['keyword_image'] != null && $result ['keyword_image'] != "") {
						$userIds2 [] = $result ['keyword_image'];
					}
				}
				$userIds12 = array_unique ( $userIds2 );
				
				$userIds21 = implode ( ',', $userIds12 );
				
				if ($userIds21 != null && $userIds21 != "") {
					$userIds21 = str_replace ( "all,", "", $userIds21 );
					
					$n_keyword_files = $userIds21;
				}
			} else {
				
				if ($data ['relation_search'] == '1') {
					
					$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
					
					$keydata = $query2->row;
					
					// var_dump($keydata);
					// echo "<hr>";
					
					$query21 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $keydata ['relation_keyword_id'] . "'" );
					
					$keydata2 = $query21->row;
					
					// var_dump($keydata2);
					// echo "<hr>";
					
					$aaa = array ();
					if ($keydata2 ['keyword_image'] != null && $keydata2 ['keyword_image'] != "") {
						$sql .= " and ( nk.keyword_file = '" . $keydata ['keyword_image'] . "'";
						$sql .= " or nk.keyword_file = '" . $keydata2 ['keyword_image'] . "') ";
						
						$n_keyword_file_monitor_1 = $keydata ['keyword_image'];
						$n_keyword_file_monitor_2 = $keydata2 ['keyword_image'];
						$aaa [] = $keydata ['keyword_image'];
						$aaa [] = $keydata2 ['keyword_image'];
						// var_dump($aaa);
						// echo "<hr>";
						$userIds21 = implode ( ',', $aaa );
						$n_keyword_files = $userIds21;
						// var_dump($n_keyword_files);
						// echo "<hr>";
					} else {
						$sql .= " and nk.keyword_file = '" . $keydata ['keyword_image'] . "'";
						
						$n_keyword_file = $keydata ['keyword_image'];
					}
				} else {
					$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
					
					$keydata = $query2->row;
					
					$n_keyword_file = $keydata ['keyword_image'];
				}
			}
		}
		
		if ($data ['form_search']) {
			if ($data ['form_search'] == 'IncidentForm') {
				$n_form_type = " 1";
			}
			if ($data ['form_search'] == 'ChecklistForm') {
				$n_form_type = " 2";
			}
			
			if ($data ['form_search'] == 'BedCheckForm') {
				$n_task_type = " 1";
			}
			
			if ($data ['form_search'] == 'MedicationForm') {
				$n_task_type = " 2";
			}
			
			if ($data ['form_search'] == 'TransportationForm') {
				$n_task_type = " 3";
			}
			if ($data ['form_search'] == 'Intake') {
				$n_is_tag = " 2 ";
			}
			if ($data ['form_search'] == 'HealthForm') {
				$n_is_tag = " 1";
			}
			if ($data ['form_search'] == 'Case') {
				$n_is_tag = " 9";
			}
			
			if (is_numeric ( $data ['form_search'] )) {
				$n_custom_form_type = $data ['form_search'];
			}
		}
		
		$n_keyword = "";
		if ($data ['keyword'] != null && $data ['keyword'] != "") {
			$n_keyword = $this->db->escape ( strtolower ( $data ['keyword'] ) );
		}
		
		if ($data ['case_number'] != null && $data ['case_number'] != "") {
			$n_keyword .= ' ' . $this->db->escape ( strtolower ( $data ['case_number'] ) );
		}
		
		if ($data ['tag_status_id'] != null && $data ['tag_status_id'] != "") {
			
			$n_tag_status_id = $data ['tag_status_id'];
		}
		
		if ($data ['tag_classification_id'] != null && $data ['tag_classification_id'] != "") {
			
			$n_tag_classification_id = $data ['tag_classification_id'];
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
				
				$startTimeFrom = date ( 'H:i:s', strtotime ( $data ['search_time_start'] ) );
				$startTimeTo = date ( 'H:i:s', strtotime ( $data ['search_time_to'] ) );
				
				$startTimeFrom_o = '00:00:00';
				$startTimeTo_o = '23:59:59';
				
				$n_note_date_from_t = $startDate . " " . $startTimeFrom_o;
				$n_note_date_to_t = $endDate . " " . $startTimeTo_o;
				$n_note_date_from_time = $startTimeFrom;
				$n_note_date_to_time = $startTimeTo;
				
				// $sql .= " and (n.`notetime` BETWEEN '".$startTimeFrom."' AND
				// '".$startTimeTo."') ";
			} else {
				$startTimeFrom = '00:00:00';
				$startTimeTo = '23:59:59';
				
				$n_note_date_from = $startDate . " " . $startTimeFrom;
				$n_note_date_to = $endDate . " " . $startTimeTo;
			}
			
			// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
			// ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' or
			// f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND
			// '".$endDate." ".$startTimeTo."' ) ";
		}
		
		if ($data ['searchdate_app'] == '1') {
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				$date = str_replace ( '-', '/', $data ['searchdate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$startDate = $changedDate; /*
				                            * date('Y-m-d',
				                            * strtotime($data['searchdate']));
				                            */
				/* $endDate = date('Y-m-d'); */
				$endDate = $changedDate; /*
				                          * date('Y-m-d',
				                          * strtotime($data['searchdate']));
				                          */
				
				// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00'
				// AND '".$endDate." 23:59:59')";
				// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
				// 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added`
				// BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate."
				// 23:59:59' ) ";
				
				$n_note_date_from = $startDate . " 00:00:00";
				$n_note_date_to = $startDate . " 23:59:59";
			} else {
				if ($data ['advance_searchapp'] != '1' && $data ['advance_search'] != '1') {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					$startDate = date ( 'Y-m-d' );
					$endDate = date ( 'Y-m-d' );
					
					if ($data ['case_detail'] == '2') {
						// $sql .= " and `date_added` BETWEEN '".$startDate."
						// 00:00:00' AND '".$endDate." 23:59:59' ";
						// $sql .= " and ( n.`date_added` BETWEEN
						// '".$startDate." 00:00:00 ' AND '".$endDate."
						// 23:59:59' or f.`date_added` BETWEEN '".$startDate."
						// 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
						$n_note_date_from = $startDate . " 00:00:00";
						$n_note_date_to = $endDate . " 23:59:59";
					}
				}
			}
		}
		
		if ($data ['searchdate_app'] != "1") {
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				
				$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
				
				$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
				
				// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00'
				// AND '".$endDate." 23:59:59')";
				// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
				// 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added`
				// BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate."
				// 23:59:59' ) ";
				if ($data ['notetime'] == null && $data ['notetime'] == "") {
					$n_note_date_from = $startDate . " 00:00:00";
					$n_note_date_to = $endDate . " 23:59:59";
				}
			}
		}
		
		// var_dump($data['sync_data']);
		if ($data ['sync_data'] == "2") {
			
			date_default_timezone_set ( $data ['facilities_timezone'] );
			
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				// $startDate = date('Y-m-d', strtotime($data['searchdate']));
				// $endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$time = date ( 'H:i:s' );
				
				if ($data ['notetime'] != null && $data ['notetime'] != "") {
					$notetime = date ( 'H:i:s', strtotime ( "+10 seconds", strtotime ( $data ['notetime'] ) ) );
				} else {
					$notetime = '00:00:00';
				}
				
				$date_end = $endDate . ' ' . $time;
				$date_start = $endDate . ' ' . $notetime;
				// $sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND
				// '".$date_end."') ";
				
				$n_update_date_s = $date_start;
				$n_update_date_e = $date_end;
			}
		}
		
		if ($data ['sync_data'] == "3") {
			
			date_default_timezone_set ( $data ['facilities_timezone'] );
			
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				// $startDate = date('Y-m-d', strtotime($data['searchdate']));
				// $endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				// $time = date ( 'H:i:s' );
				$time = date ( 'H:i:s', strtotime ( "+60 minutes" ) );
				
				if ($data ['notetime'] != null && $data ['notetime'] != "") {
					$notetime = date ( 'H:i:s', strtotime ( "+3 seconds", strtotime ( $data ['notetime'] ) ) );
				} else {
					$notetime = '00:00:00';
				}
				
				$date_end = $endDate . ' ' . $time;
				$date_start = $endDate . ' ' . $notetime;
				// $sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND
				// '".$date_end."') ";
				
				$n_note_date_from = $date_start;
				$n_note_date_to = $date_end;
			}
		}
		
		if ($data ['group'] == '1') {
			// $n_group = " 1 ";
			// $sql .= " ORDER BY n.date_added DESC";
		}
		
		if ($data ['sync_data'] != "2") {
			if ($data ['searchdate_app'] == "1") {
				if ($data ['advance_searchapp'] == "1") {
					$n_orderby = " n.date_added ASC";
				} else {
					if ($data ['is_ajax_load'] == "1") {
						$n_orderby = " n.notetime ASC";
					} else {
						$n_orderby = " n.notetime ASC";
					}
				}
			} else {
				if ($data ['advance_search'] == "1") {
					
					if ($data ['advance_date_desc'] == "1") {
						$n_orderby = " n.date_added ASC";
					} else {
						$n_orderby = "  n.date_added DESC";
					}
				} else {
					if ($data ['is_ajax_load'] == "1") {
						$n_orderby = " n.notetime ASC";
					} else {
						$n_orderby = " n.notetime ASC";
					}
				}
			}
		} else {
			$n_orderby = " n.update_date ASC";
		}
		
		if ($data ['current_date'] != '1') {
			if ($data ['advance_searchapp'] == '1') {
				if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
					if ($data ['start'] < 0) {
						$data ['start'] = 0;
					}
					
					if ($data ['limit'] < 1) {
						$data ['limit'] = 20;
					}
					
					$n_start = $data ['start'];
					$n_limit = $data ['limit'];
				}
			}
			
			if ($data ['advance_searchapp'] != '1') {
				if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
					if ($data ['start'] < 0) {
						$data ['start'] = 0;
					}
					
					if ($data ['limit'] < 1) {
						$data ['limit'] = 20;
					}
					
					$n_start = $data ['start'];
					$n_limit = $data ['limit'];
				}
			}
		}
		
		if ($data ['advance_searchapp'] == '1' || $data ['advance_search'] == '1') {
			
			$sql = " CALL getNotes('" . $status . "', '" . $facilities_id . "', '" . $user_id . "', '" . $n_notes_id . "', '" . $n_tags_id . "', '" . $n_tasktype . "', '" . $n_tagstatus_id . "','" . $n_notesid . "','" . $n_useIds . "','" . $n_highlighter_ids . "','" . $n_highlighter_id . "','" . $n_keyword_files . "', '" . $n_keyword_file . "', '" . $n_form_type . "', '" . $n_task_type . "', '" . $n_is_tag . "', '" . $n_custom_form_type . "','" . $n_keyword . "','" . $n_note_date_from . "','" . $n_note_date_to . "','" . $n_update_date_s . "','" . $n_update_date_e . "', '" . $n_note_date_from_t . "', '" . $n_note_date_to_t . "', '" . $n_note_date_from_time . "', '" . $n_note_date_to_time . "', '" . $faculities_ids . "', '" . $n_group . "','" . $n_orderby . "','" . $n_start . "','" . $n_limit . "', '" . $n_tag_status_id . "', '" . $n_tag_classification_id . "', '" . $n_manual_movement . "') ";
		} else {
			$sql = " CALL getNotesbyOneDate('" . $status . "', '" . $facilities_id . "', '" . $user_id . "', '" . $n_notes_id . "', '" . $n_tags_id . "', '" . $n_tasktype . "', '" . $n_tagstatus_id . "','" . $n_notesid . "','" . $n_useIds . "','" . $n_highlighter_ids . "','" . $n_highlighter_id . "','" . $n_keyword_files . "', '" . $n_keyword_file . "', '" . $n_form_type . "', '" . $n_task_type . "', '" . $n_is_tag . "', '" . $n_custom_form_type . "','" . $n_keyword . "','" . $n_note_date_from . "','" . $n_note_date_to . "','" . $n_update_date_s . "','" . $n_update_date_e . "', '" . $n_note_date_from_t . "', '" . $n_note_date_to_t . "', '" . $n_note_date_from_time . "', '" . $n_note_date_to_time . "', '" . $faculities_ids . "', '" . $n_group . "','" . $n_orderby . "','" . $n_start . "','" . $n_limit . "') ";
		}
		
		// echo "<hr>";
		// echo $sql;
		// echo "<hr>";
		// die;
		$query = $this->db->query ( $sql );
		
		// var_dump(count($query->rows));
		
		return $query->rows;
	}
	
	
	public function getNote($notes_id) {
		$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,user_file,is_approval_required_forms_id,unique_id,linked_id,parent_facilities_id,is_comment,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE notes_id = '" . ( int ) $notes_id . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getTotalnotess($data = array()) {
		$status = '1';
		
		if($this->session->data ['search_facilities_all'] == 'All'){
			$this->load->model ( 'facilities/facilities' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				if ($data ['search_facilities_id'] == $data ['facilities_id']) {
					$ddss [] = $facilityinfo ['notes_facilities_ids'];
					$ddss [] = $data ['facilities_id'];
					
					$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
					
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['notes_facilities_ids'];
						}
					}
					
					$sssssdd = implode ( ",", $ddss );
					$faculities_ids = $sssssdd;
				} else {
					if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
						$facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
					} else {
						$ddss [] = $facilityinfo ['notes_facilities_ids'];
						$ddss [] = $data ['facilities_id'];
						
						$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
						
						$abdcg = array_unique ( $sssssddsg );
						$cids = array ();
						foreach ( $abdcg as $fid ) {
							$cids [] = $fid;
						}
						$abdcgs = array_unique ( $cids );
						foreach ( $abdcgs as $fid2 ) {
							$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
							if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
								$ddss [] = $facilityinfo ['notes_facilities_ids'];
							}
						}
						
						$sssssdd = implode ( ",", $ddss );
						$faculities_ids = $sssssdd;
					}
				}
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					$facilities_id = $this->db->escape ( $data ['facilities_id'] );
				}
			}
		}else{
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$facilities_id = $this->db->escape ( $data ['facilities_id'] );
			}
		}
		
		/*
		 * if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
		 * if ($data ['search_facilities_id'] == $data ['facilities_id']) {
		 * $ddss [] = $facilityinfo ['notes_facilities_ids'];
		 * $ddss [] = $data ['facilities_id'];
		 * $sssssdd = implode ( ",", $ddss );
		 * $faculities_ids = $sssssdd;
		 * } else {
		 * if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
		 * $facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
		 * } else {
		 * $ddss [] = $facilityinfo ['notes_facilities_ids'];
		 * $ddss [] = $data ['facilities_id'];
		 * $sssssdd = implode ( ",", $ddss );
		 * $faculities_ids = $sssssdd;
		 * }
		 * }
		 * } else {
		 * if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
		 * $facilities_id = $this->db->escape ( $data ['facilities_id'] );
		 * }
		 * }
		 */
		
		/*
		 * if ( $data['notes_facilities_ids'] != null && $data['notes_facilities_ids'] != "" ) {
		 * if($data['search_facilities_id'] == $data['facilities_id']){
		 * $faculities_ids = $data['notes_facilities_ids'];
		 *
		 * }else{
		 * if ($data['search_facilities_id'] != null && $data['search_facilities_id'] != "") {
		 * $facilities_id = $this->db->escape($data['search_facilities_id']);
		 * }else{
		 * $faculities_ids = $data['notes_facilities_ids'];
		 * }
		 *
		 * }
		 * }else{
		 * if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		 * $facilities_id = $this->db->escape($data['facilities_id']);
		 * }
		 * }
		 */
		/*
		 * if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		 * $facilities_id = $data['facilities_id'];
		 * }
		 */
		
		if ($data ['customer_key'] != null && $data ['customer_key'] != "") {
			// $sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
			
			$unique_id = $this->db->escape ( $data ['customer_key'] );
		}
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_id = $this->db->escape ( $data ['user_id'] );
		}
		
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$n_notes_id = $data ['notes_id'];
		}
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "0") {
			$n_tags_id = $this->db->escape ( $data ['emp_tag_id'] );
		}
		
		if ($data ['tasktype'] != null && $data ['tasktype'] != "") {
			$n_tasktype = $this->db->escape ( $data ['tasktype'] );
		}
		
		if ($data ['tagstatus_id'] != null && $data ['tagstatus_id'] != "") {
			$n_tagstatus_id = "1";
		}
		
		if ($data ['manual_movement'] != null && $data ['manual_movement'] != "") {
			$n_manual_movement = "1";
		}
		
		/*
		 * if ($data['is_bedchk'] != null && $data['is_bedchk'] != "") {
		 * $sql .= " and n.tasktype = '11'";
		 * }
		 */
		
		if ($data ['notesid'] != null && $data ['notesid'] != "") {
			$n_notesid = $data ['notesid'];
		}
		
		if ($this->session->data ['isPrivate'] == '1') {
			// $sql .= " and n.user_id =
			// '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole ();
			// var_dump($useIds);
			// echo "<hr>";
			
			if ($useIds != null && $useIds != "") {
				$n_useIds = $useIds;
			}
		}
		
		if ($data ['highlighter'] != null && $data ['highlighter'] != "") {
			
			if ($data ['highlighter'] == 'all') {
				$data3 = array ();
				$this->load->model ( 'setting/highlighter' );
				$results = $this->model_setting_highlighter->gethighlighters ( $data3 );
				$userIds = array ();
				foreach ( $results as $result ) {
					if ($result ['highlighter_id'] != null && $result ['highlighter_id'] != "") {
						$userIds [] = $result ['highlighter_id'];
					}
				}
				$userIds1 = array_unique ( $userIds );
				
				$userIds2 = implode ( ",", $userIds1 );
				
				if ($userIds2 != null && $userIds2 != "") {
					$userIds2 = str_replace ( "all,", "", $userIds2 );
					$n_highlighter_ids = $userIds2;
				}
			} else {
				$n_highlighter_id = $data ['highlighter'];
			}
		}
		
		if ($data ['activenote'] != null && $data ['activenote'] != "") {
			
			if ($data ['activenote'] == 'all') {
				
				$query2 = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "keyword " );
				
				$results = $query2->rows;
				
				$userIds2 = array ();
				foreach ( $results as $result ) {
					if ($result ['keyword_image'] != null && $result ['keyword_image'] != "") {
						$userIds2 [] = $result ['keyword_image'];
					}
				}
				$userIds12 = array_unique ( $userIds2 );
				
				// $userIds21 = implode('\',\'',$userIds12);
				$userIds21 = implode ( ',', $userIds12 );
				
				if ($userIds21 != null && $userIds21 != "") {
					$userIds21 = str_replace ( "all,", "", $userIds21 );
					
					$n_keyword_files = $userIds21;
				}
			} else {
				$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
				
				$keydata = $query2->row;
				
				$n_keyword_file = $keydata ['keyword_image'];
			}
		}
		
		if ($data ['form_search']) {
			if ($data ['form_search'] == 'IncidentForm') {
				$n_form_type = " 1";
			}
			if ($data ['form_search'] == 'ChecklistForm') {
				$n_form_type = " 2";
			}
			
			if ($data ['form_search'] == 'BedCheckForm') {
				$n_task_type = " 1";
			}
			
			if ($data ['form_search'] == 'MedicationForm') {
				$n_task_type = " 2";
			}
			
			if ($data ['form_search'] == 'TransportationForm') {
				$n_task_type = " 3";
			}
			if ($data ['form_search'] == 'Intake') {
				$n_is_tag = " 2 ";
			}
			if ($data ['form_search'] == 'HealthForm') {
				$n_is_tag = " 1";
			}
			
			if ($data ['form_search'] == 'Case') {
				$n_is_tag = " 9";
			}
			
			if (is_numeric ( $data ['form_search'] )) {
				$n_custom_form_type = $data ['form_search'];
			}
		}
		
		$n_keyword = "";
		
		if ($data ['keyword'] != null && $data ['keyword'] != "") {
			$n_keyword = $this->db->escape ( strtolower ( $data ['keyword'] ) );
		}
		if ($data ['case_number'] != null && $data ['case_number'] != "") {
			$n_keyword .= ' ' . $this->db->escape ( strtolower ( $data ['case_number'] ) );
		}
		
		if ($data ['tag_status_id'] != null && $data ['tag_status_id'] != "") {
			
			$n_tag_status_id = $data ['tag_status_id'];
		}
		
		if ($data ['tag_classification_id'] != null && $data ['tag_classification_id'] != "") {
			
			$n_tag_classification_id = $data ['tag_classification_id'];
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
				
				$startTimeFrom = date ( 'H:i:s', strtotime ( $data ['search_time_start'] ) );
				$startTimeTo = date ( 'H:i:s', strtotime ( $data ['search_time_to'] ) );
				
				// $sql .= " and (n.`notetime` BETWEEN '".$startTimeFrom."' AND
				// '".$startTimeTo."') ";
				
				$startTimeFrom_o = '00:00:00';
				$startTimeTo_o = '23:59:59';
				
				$n_note_date_from_t = $startDate . " " . $startTimeFrom_o;
				$n_note_date_to_t = $endDate . " " . $startTimeTo_o;
				$n_note_date_from_time = $startTimeFrom;
				$n_note_date_to_time = $startTimeTo;
			} else {
				$startTimeFrom = '00:00:00';
				$startTimeTo = '23:59:59';
				
				$n_note_date_from = $startDate . " " . $startTimeFrom;
				$n_note_date_to = $endDate . " " . $startTimeTo;
			}
			
			// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
			// ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' or
			// f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND
			// '".$endDate." ".$startTimeTo."' ) ";
		}
		
		if ($data ['searchdate_app'] == '1') {
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				$date = str_replace ( '-', '/', $data ['searchdate'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$startDate = $changedDate; /*
				                            * date('Y-m-d',
				                            * strtotime($data['searchdate']));
				                            */
				/* $endDate = date('Y-m-d'); */
				$endDate = $changedDate; /*
				                          * date('Y-m-d',
				                          * strtotime($data['searchdate']));
				                          */
				
				// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00'
				// AND '".$endDate." 23:59:59')";
				// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
				// 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added`
				// BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate."
				// 23:59:59' ) ";
				
				$n_note_date_from = $startDate . " 00:00:00";
				$n_note_date_to = $startDate . " 23:59:59";
			} else {
				if ($data ['advance_searchapp'] != '1' && $data ['advance_search'] != '1') {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					$startDate = date ( 'Y-m-d' );
					$endDate = date ( 'Y-m-d' );
					
					if ($data ['case_detail'] == '2') {
						// $sql .= " and `date_added` BETWEEN '".$startDate."
						// 00:00:00' AND '".$endDate." 23:59:59' ";
						// $sql .= " and ( n.`date_added` BETWEEN
						// '".$startDate." 00:00:00 ' AND '".$endDate."
						// 23:59:59' or f.`date_added` BETWEEN '".$startDate."
						// 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
						$n_note_date_from = $startDate . " 00:00:00";
						$n_note_date_to = $endDate . " 23:59:59";
					}
				}
			}
		}
		
		if ($data ['searchdate_app'] != "1") {
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				
				$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
				
				$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
				
				// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00'
				// AND '".$endDate." 23:59:59')";
				// $sql .= " and ( n.`date_added` BETWEEN '".$startDate."
				// 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added`
				// BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate."
				// 23:59:59' ) ";
				if ($data ['notetime'] == null && $data ['notetime'] == "") {
					$n_note_date_from = $startDate . " 00:00:00";
					$n_note_date_to = $endDate . " 23:59:59";
				}
			}
		}
		
		// var_dump($data['sync_data']);
		if ($data ['sync_data'] == "2") {
			
			date_default_timezone_set ( $data ['facilities_timezone'] );
			
			if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
				// $startDate = date('Y-m-d', strtotime($data['searchdate']));
				// $endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$time = date ( 'H:i:s' );
				
				if ($data ['notetime'] != null && $data ['notetime'] != "") {
					$notetime = date ( 'H:i:s', strtotime ( "+10 seconds", strtotime ( $data ['notetime'] ) ) );
				} else {
					$notetime = '00:00:00';
				}
				
				$date_end = $endDate . ' ' . $time;
				$date_start = $endDate . ' ' . $notetime;
				// $sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND
				// '".$date_end."') ";
				
				$n_update_date_s = $date_start;
				$n_update_date_e = $date_end;
			}
		}
		
		if ($data ['advance_searchapp'] == '1' || $data ['advance_search'] == '1') {
			$sql = " CALL getTotalNotes('" . $status . "', '" . $facilities_id . "', '" . $user_id . "', '" . $n_notes_id . "', '" . $n_tags_id . "', '" . $n_tasktype . "', '" . $n_tagstatus_id . "','" . $n_notesid . "','" . $n_useIds . "','" . $n_highlighter_ids . "','" . $n_highlighter_id . "','" . $n_keyword_files . "', '" . $n_keyword_file . "', '" . $n_form_type . "', '" . $n_task_type . "', '" . $n_is_tag . "', '" . $n_custom_form_type . "','" . $n_keyword . "','" . $n_note_date_from . "','" . $n_note_date_to . "','" . $n_update_date_s . "','" . $n_update_date_e . "', '" . $n_note_date_from_t . "', '" . $n_note_date_to_t . "', '" . $n_note_date_from_time . "', '" . $n_note_date_to_time . "', '" . $faculities_ids . "', '" . $n_tag_status_id . "', '" . $n_tag_classification_id . "', '" . $n_manual_movement . "') ";
		} else {
			$sql = " CALL getTotalNotesByOneDate('" . $status . "', '" . $facilities_id . "', '" . $user_id . "', '" . $n_notes_id . "', '" . $n_tags_id . "', '" . $n_tasktype . "', '" . $n_tagstatus_id . "','" . $n_notesid . "','" . $n_useIds . "','" . $n_highlighter_ids . "','" . $n_highlighter_id . "','" . $n_keyword_files . "', '" . $n_keyword_file . "', '" . $n_form_type . "', '" . $n_task_type . "', '" . $n_is_tag . "', '" . $n_custom_form_type . "','" . $n_keyword . "','" . $n_note_date_from . "','" . $n_note_date_to . "','" . $n_update_date_s . "','" . $n_update_date_e . "', '" . $n_note_date_from_t . "', '" . $n_note_date_to_t . "', '" . $n_note_date_from_time . "', '" . $n_note_date_to_time . "', '" . $faculities_ids . "') ";
		}
		
		// echo "<hr>";
		// echo $sql;
		// echo "<hr>";
		
		$query = $this->db->query ( $sql );
		
		return $query->row ['total'];
	}
	public function jsonaddnotes($data, $facilities_id) {
		$notes_descriptionstr = str_replace ( ' ', '', $data ['notes_description'] );
		
		if ($notes_descriptionstr != null && $notes_descriptionstr != "") {
			if ($facilities_id != null && $facilities_id != "") {
				if ($facilities_id != "0") {
					$createdate1 = $data ['note_date'];
					$createtime1 = date ( 'H:i:s' );
					$createDate2 = $createdate1 . $createtime1;
					$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
					
					$timezone_name = $data ['facilitytimezone'];
					
					date_default_timezone_set ( $timezone_name );
					
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					/*
					 * if ($data['offline'] == '1') {
					 * $notetime1 = explode(":", $data['notetime']);
					 * if ($notetime1[0] == "00") {
					 * // $notetime2 = '12:'.$notetime1[1];
					 * $notetime2 = $data['notetime'];
					 * } else {
					 * $notetime2 = $data['notetime'];
					 * }
					 * $noteTime = date('H:i:s', strtotime($notetime2));
					 * } else {
					 */
					
					/*
					 * if($this->config->get('config_time_picker') == '0'){
					 * $noteTime = date('H:i:s', strtotime('now'));
					 * }else{
					 */
					$notetime1 = explode ( ":", $data ['notetime'] );
					if ($notetime1 [0] == "00") {
						// $notetime2 = '12:'.$notetime1[1];
						$notetime2 = $data ['notetime'];
					} else {
						$notetime2 = $data ['notetime'];
					}
					$noteTime = date ( 'H:i:s', strtotime ( $notetime2 ) );
					/* } */
					// }
					
					if ($data ['offline_csv'] == '1') {
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( $data ['date_added'] ) );
					} else {
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					}
					
					/*
					 * if ($data['offline'] == '1') {
					 * $createDate = date('Y-m-d H:i:s', strtotime($data['note_date']));
					 * } else {
					 */
					$date_added = $data ['date_added'];
					
					$createdate1 = date ( 'Y-m-d', strtotime ( $data ['note_date'] ) );
					$createtime1 = date ( 'H:i:s' );
					// $createDate2 = $createdate1 . $createtime1;
					$createDate2 = $createdate1 . $noteTime;
					$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
					// }
					// $note_date = $data['note_date'];
					// $createDate = date('Y-m-d H:i:s',strtotime($note_date));
					
					/*
					 * if($data['keyword_file'] != null && $data['keyword_file'] != ""){
					 * $this->load->model('setting/image');
					 *
					 * $file16 = 'icon/'.$data['keyword_file'];
					 *
					 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
					 * $newfile216 = DIR_IMAGE . $newfile84;
					 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
					 * $imageData132 = base64_encode(file_get_contents($newfile216));
					 *
					 * if($newfile84 != null && $newfile84 != ""){
					 * $keyword_icon =
					 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
					 * }else{
					 * $keyword_icon = '';
					 * }
					 *
					 *
					 * $keyword_file = $data['keyword_file'];
					 * }
					 */
					
					/*
					 * if($data['notes_pin'] != null && $data['notes_pin'] != ""){
					 * $notes_pin = $data['notes_pin'];
					 * $signature = "";
					 * }else{
					 * $signature = $data['imgOutput'];
					 * $notes_pin = '';
					 * }
					 */
					$notes_pin = $data ['notes_pin'];
					// $signature = $data ['imgOutput'];
					
					$signature = '';
					if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
						$this->load->model ( 'api/savesignature' );
						$sigdata = array ();
						$sigdata ['upload_file'] = $data ['imgOutput'];
						$sigdata ['facilities_id'] = $facilities_id;
						$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
						
						$signature = $signaturestatus;
					}
					
					if ($data ['text_color'] != null && $data ['text_color'] != "") {
						$text_color = '#' . $data ['text_color'];
					} else {
						$text_color = "";
					}
					
					// $notes_description1 = rtrim(rtrim($data['notes_description']));
					// $notes_description2 = ucfirst($notes_description1);
					$notes_description2 = $data ['notes_description'];
					
					/*
					 * if($data['keyword_file'] == null && $data['keyword_file'] == ""){
					 * $this->load->model('setting/keywords');
					 * $data3 = array(
					 * 'facilities_id' => $facilities_id,
					 * );
					 * $keywords = $this->model_setting_keywords->getkeywords($data3);
					 * $keyarray = array();
					 * foreach($keywords as $keyword){
					 * $keyarray[] = $keyword['active_tag'];
					 * }
					 *
					 *
					 * $matchData = $this->arrayInString( $keyarray , $notes_description2);
					 * //$matchData2 = $this->arrayInString2( $keyarray2 ,
					 * $notes_description2);
					 * //var_dump($matchData);
					 *
					 * if ($matchData != null && $matchData != "") {
					 * $keywordData =
					 * $this->model_setting_keywords->getkeywordByTag($matchData);
					 * }
					 *
					 *
					 *
					 * if ($keywordData['keyword_image'] && file_exists(DIR_IMAGE .
					 * 'icon/'.$keywordData['keyword_image'])) {
					 * $this->load->model('setting/image');
					 *
					 * $file16 = 'icon/'.$keywordData['keyword_image'];
					 *
					 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
					 * $newfile216 = DIR_IMAGE . $newfile84;
					 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
					 * $imageData132 = base64_encode(file_get_contents($newfile216));
					 *
					 * if($newfile84 != null && $newfile84 != ""){
					 * $keyword_icon =
					 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
					 * }else{
					 * $keyword_icon = '';
					 * }
					 *
					 *
					 * $keyword_file = $keywordData['keyword_image'];
					 * $notes_description2 =
					 * str_ireplace($keywordData['active_tag'],$keywordData['keyword_name'],$notes_description2);
					 * }
					 *
					 * }
					 */
					
					$this->load->model ( 'setting/highlighter' );
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $data ['highlighter_id'] );
					$highlighter_value = $highlighterData ['highlighter_value'];
					
					// $notes_description = str_replace("'","&#039;",
					// html_entity_decode($notes_description2, ENT_QUOTES));
					
					// $notes_description = str_replace("'","&#039;",
					// html_entity_decode($notes_description2, ENT_QUOTES));
					$notes_description = $notes_description2;
					
					if ($data ['highlighter_id'] == '21') {
						$highlighter = '0';
						$highlighter_value1 = '';
					} else {
						$highlighter = $data ['highlighter_id'];
						$highlighter_value1 = $highlighter_value;
					}
					/*
					 * $sql = "INSERT INTO `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $this->db->escape($highlighter) . "', notes_pin = '" . $this->db->escape($notes_pin) . "', notes_file = '" . $this->db->escape($data['notes_file']) . "', user_id = '" . $this->db->escape($data['user_id']) .
					 * "', status = '1', notetime = '" . $noteTime . "', signature = '" . $signature . "', signature_image = '" . $fileName . "', text_color_cut = '" . $this->db->escape($data['text_color_cut']) . "', text_color = '" . $this->db->escape($text_color) . "', date_added = '" . $createDate . "', note_date = '" . $noteDate . "', global_utc_timezone = UTC_TIMESTAMP( ), keyword_file = '" .
					 * $this->db->escape($keyword_file) . "', keyword_file_url = '" . $this->db->escape($keyword_icon) . "', highlighter_value = '" . $this->db->escape($highlighter_value1) . "', emp_tag_id = '" . $this->db->escape($data['emp_tag_id']) . "', tags_id = '" . $this->db->escape($data['tags_id']) . "', notes_type = '" . $this->db->escape($data['notes_type']) . "', update_date = '" .
					 * $update_date . "', is_offline = '" . $data['offline'] . "', notes_conut='0', is_android='" . $data['is_android'] . "', phone_device_id='" . $this->db->escape($data['phone_device_id']) . "', device_unique_id='" . $this->db->escape($data['device_unique_id']) . "' ";
					 * $this->db->query($sql);
					 * $notes_id = $this->db->getLastId();
					 */
					
					$this->load->model ( 'user/user' );
					// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
					
					if ($data ['user_id'] != null && $data ['user_id'] != "") {
						$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
					} else {
						$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
					}
					
					if ($user_info ['username'] != null && $user_info ['username'] != "") {
						$username = $user_info ['username'];
					} else {
						$username = $data ['user_id'];
					}
					
					$sql = "CALL
          insertNotesapp('" . $this->db->escape ( $notes_description ) . "','" . $highlighter . "','" . $this->db->escape ( $notes_pin ) . "','" . $data ['notes_file'] . "','" . $this->db->escape ( $username ) . "','" . $noteTime . "','" . $signature . "','" . $fileName . "','" . $this->db->escape ( $data ['text_color_cut'] ) . "','" . $this->db->escape ( $text_color ) . "','" . $createDate . "','" . $noteDate . "','" . $keyword_file . "','" . $keyword_icon . "','" . $highlighter_value1 . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $data ['tags_id'] . "','" . $this->db->escape ( $data ['notes_type'] ) . "','" . $update_date . "','" . $data ['offline'] . "','" . $data ['is_android'] . "', '" . $this->db->escape ( $data ['phone_device_id'] ) . "', '" . $this->db->escape ( $data ['device_unique_id'] ) . "','" . $facilities_id . "')";
					
					$lastId = $this->db->query ( $sql );
					
					$notes_id = $lastId->row ['notes_id'];
					
					$sql12t = "UPDATE `" . DB_PREFIX . "notes` SET in_total = '" . $data ['in_total'] . "', out_total ='" . $data ['out_total'] . "', manual_total ='" . $data ['manual_total'] . "', status_total_time ='" . $data ['status_total_time'] . "' WHERE notes_id = '" . ( int ) $notes_id . "'";
					$this->db->query ( $sql12t );
					
					if ($data ['is_inventory_checkout_id'] != null && $data ['is_inventory_checkout_id'] != "") {
						$sqlinventory = "UPDATE `" . DB_PREFIX . "notes` SET  form_type = '" . $data ['form_type'] . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sqlinventory );
					}
					
					if ($data ['addmedication'] != null && $data ['addmedication'] != "") {
						$sqlUpdateMedication = "UPDATE `" . DB_PREFIX . "notes` SET  is_forms = '" . $data ['addmedication'] . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sqlUpdateMedication );
					}
					
					if ($data ['is_inventory_checkin_id'] != null && $data ['is_inventory_checkin_id'] != "") {
						$sqlinventory = "UPDATE `" . DB_PREFIX . "notes` SET  form_type = '" . $data ['form_type'] . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sqlinventory );
					}
					
					$sql1e = "UPDATE `" . DB_PREFIX . "notes` SET linked_id = '" . $data ['linked_id'] . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql1e );
					
					if ($data ['customlistvalues_ids'] != null && $data ['customlistvalues_ids'] != "") {
						$customlistvalues_id = implode ( ',', $data ['customlistvalues_ids'] );
						
						$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET customlistvalues_id = '" . $customlistvalues_id . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql1 );
					}
					
					if ($facilities_id) {
						$this->load->model ( 'facilities/facilities' );
						$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
						$unique_id = $facility ['customer_key'];
						$sql121 = "UPDATE `" . DB_PREFIX . "notes` SET unique_id = '" . $this->db->escape ( $unique_id ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql121 );
					}
					
					if (! empty ( $data ['multipleimages'] )) {
						foreach ( $data ['multipleimages'] as $multipleimage ) {
							$sqla = "INSERT INTO `" . DB_PREFIX . "notes_media` SET notes_id = '" . $notes_id . "', notes_file = '" . $this->db->escape ( $multipleimage ) . "', notes_media_extention = 'jpg', status = '1', facilities_id = '" . $facilities_id . "', media_date_added = '" . $noteDate . "' ";
							$this->db->query ( $sqla );
							
							$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
							$this->db->query ( $sql12 );
						}
					}
					
					// var_dump($data['mutikeywords']);
					
					$jsonData2 = stripslashes ( html_entity_decode ( $data ['mutikeywords'] ) );
					$mutikeywords = json_decode ( $jsonData2, true );
					
					if (! empty ( $mutikeywords )) {
						foreach ( $mutikeywords as $key => $mutikeyword ) {
							$sqla = "INSERT INTO `" . DB_PREFIX . "notes_by_multikeyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $mutikeyword ['keyword_id'] . "', facilities_id = '" . $facilities_id . "', date_added = '" . $noteDate . "', date_updated = '" . $noteDate . "', action = '" . $this->db->escape ( $mutikeyword ['action'] ) . "', name = '" . $this->db->escape ( $mutikeyword ['name'] ) . "', type = '" . $this->db->escape ( $mutikeyword ['measurement'] ) . "', value = '" . $this->db->escape ( $mutikeyword ['value'] ) . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
							$this->db->query ( $sqla );
						}
					}
					
					$jsonData2m = stripslashes ( html_entity_decode ( $data ['multifacilities'] ) );
					$mmultifacilities = json_decode ( $jsonData2m, true );
					
					if (! empty ( $mmultifacilities )) {
						foreach ( $mmultifacilities as $key => $mutikeyword1 ) {
							if ($facilities_id == $mutikeyword1 ['facilities_id']) {
								$sqla = "INSERT INTO `" . DB_PREFIX . "notes_by_multikeyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $mutikeyword1 ['keyword_id'] . "', facilities_id = '" . $facilities_id . "', date_added = '" . $noteDate . "', date_updated = '" . $noteDate . "', action = '" . $this->db->escape ( $mutikeyword1 ['action'] ) . "', name = '" . $this->db->escape ( $mutikeyword1 ['name'] ) . "', type = '" . $this->db->escape ( $mutikeyword1 ['measurement'] ) . "', value = '" . $this->db->escape ( $mutikeyword1 ['value'] ) . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
								$this->db->query ( $sqla );
							}
						}
					}
					
					if ($data ['is_private'] == "1") {
						$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET is_private = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql1 );
					} else {
						if ($this->session->data ['isPrivate'] == '1') {
							$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET is_private = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
							$this->db->query ( $sql1 );
						}
					}
					
					$ctime = time ();
					$stime = date ( 'H:i:s', strtotime ( $ctime ) );
					
					/*
					 * //$sqlshift = "SELECT * FROM `" . DB_PREFIX . "shift` where shift_starttime > '".$stime."' and shift_endtime < '".$stime."' ";
					 * //$shifts = $this->db->query($sqlshift);
					 *
					 * if(!empty($shifts->row['shift_id'])){
					 * $id = $shifts->row['shift_id'];
					 *
					 * $updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . (int) $notes_id . "' ";
					 * $this->db->query($updateshift);
					 * }
					 */
					$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $facilities_id );
					if (! empty ( $shift_info ['shift_id'] )) {
						$id = $shift_info ['shift_id'];
						
						$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $updateshift );
					}
					
					/*
					 * if($data['formreturn_id'] != null && $data['formreturn_id'] != ""){
					 * $this->load->model('form/form');
					 * $this->model_form_form->updatenote($notes_id,
					 * $data['formreturn_id']);
					 * }
					 */
					// var_dump($data['formsids']);
					if (! empty ( $data ['formsids'] )) {
						$this->load->model ( 'form/form' );
						
						$formsids = explode ( ",", $data ['formsids'] );
						foreach ( $formsids as $formsid ) {
							$this->model_form_form->updatenote ( $notes_id, $formsid );
						}
					}
					
					if ($data ['keyword_file'] != null && $data ['keyword_file'] != "") {
						$this->load->model ( 'setting/image' );
						$keywords = explode ( ",", $data ['keyword_file'] );
						
						foreach ( $keywords as $keyword ) {
							
							/*
							 * $file16 = 'icon/'.$keyword;
							 *
							 * $newfile84 = $this->model_setting_image->resize($file16, 50,
							 * 50);
							 * $newfile216 = DIR_IMAGE . $newfile84;
							 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							 * $imageData132 =
							 * base64_encode(file_get_contents($newfile216));
							 *
							 * if($newfile84 != null && $newfile84 != ""){
							 * $keyword_icon =
							 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
							 * }else{
							 * $keyword_icon = '';
							 * }
							 */
							$keyword_icon = '';
							$keyword_file = $keyword;
							
							$this->load->model ( 'setting/keywords' );
							$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $keyword, $facilities_id );
							// var_dump($keywordData2['keyword_name']);
							// echo "<hr>";
							if ($keywordData2 ['monitor_time'] == '11') {
								$notes_description2 = $keywordData2 ['keyword_name'] . '' . $notes_description2;
							} else {
								
								$notes_description2 = str_replace ( $keywordData2 ['keyword_name'], $keywordData2 ['keyword_name'], $notes_description2 );
							}
							
							// var_dump($sstext);
							
							// $notes_description2 = $keywordData2['keyword_name'];
							
							if ($keywordData2 ['keyword_name'] != null && $keywordData2 ['keyword_name'] != "") {
								$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $this->db->escape ( $keywordData2 ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData2 ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape ( $unique_id ) . "', date_added = '" . $createDate . "', status_total_time ='" . $data ['status_total_time'] . "' ";
								$this->db->query ( $sqlm );
								
								if ($keywordData2 ['monitor_time'] == '11') {
									$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
									$this->db->query ( $sql12 );
								}
							}
						}
						
						$sql1233 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', keyword_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
						$this->db->query ( $sql1233 );
					}
					
					if ($data ['keyword_file1'] != null && $data ['keyword_file1'] != "") {
						$this->load->model ( 'setting/image' );
						$keywords = explode ( ",", $data ['keyword_file1'] );
						
						foreach ( $keywords as $keyword ) {
							
							$keyword_icon = '';
							$keyword_file = $keyword;
							
							$this->load->model ( 'setting/keywords' );
							$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $keyword, $facilities_id );
							// var_dump($keywordData2['keyword_name']);
							// echo "<hr>";
							
							$query2 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` WHERE keyword_id = '" . $keywordData2 ['keyword_id'] . "' and notes_id = '" . $notes_id . "' " );
							
							// if ($query2->num_rows == 0) {
							
							if ($keywordData2 ['monitor_time'] == '11') {
								$notes_description2 = $keywordData2 ['keyword_name'] . '' . $notes_description2;
							} else {
								
								$notes_description2 = str_replace ( $keywordData2 ['keyword_name'], $keywordData2 ['keyword_name'], $notes_description2 );
							}
							
							// var_dump($sstext);
							
							// $notes_description2 = $keywordData2['keyword_name'];
							if ($keywordData2 ['keyword_name'] != null && $keywordData2 ['keyword_name'] != "") {
								$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $this->db->escape ( $keywordData2 ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData2 ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape ( $unique_id ) . "', date_added = '" . $createDate . "', status_total_time ='" . $data ['status_total_time'] . "' ";
								$this->db->query ( $sqlm );
								
								if ($keywordData2 ['monitor_time'] == '11') {
									$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
									$this->db->query ( $sql12 );
								}
							}
							// }
						}
						
						$sql1233 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', keyword_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
						$this->db->query ( $sql1233 );
					}
					
					if ($data ['keyword_file'] == null && $data ['keyword_file'] == "") {
						$this->load->model ( 'setting/keywords' );
						
						preg_match_all ( '/#([^\s]+)/', $notes_description2, $matches );
						
						// var_dump($matches[1]);
						
						if (! empty ( $matches [1] )) {
							foreach ( $matches [1] as $hashtag ) {
								$active_tag = '#' . $hashtag;
								$keywordData = $this->model_setting_keywords->getkeywordsbyhashtag ( $active_tag, $facilities_id );
								
								if ($keywordData ['keyword_image'] != null && $keywordData ['keyword_image'] != "") {
									/*
									 * $this->load->model('setting/image');
									 *
									 * $file16 = 'icon/'.$keywordData['keyword_image'];
									 * $newfile84 =
									 * $this->model_setting_image->resize($file16, 50, 50);
									 * $newfile216 = DIR_IMAGE . $newfile84;
									 * $file124 = HTTP_SERVER . 'image/'.$newfile84;
									 *
									 * $imageData132 =
									 * base64_encode(file_get_contents($newfile216));
									 *
									 * if($newfile84 != null && $newfile84 != ""){
									 * $keyword_icon =
									 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
									 * }else{
									 * $keyword_icon = '';
									 * }
									 */
									$keyword_icon = '';
									
									$keyword_file = $keywordData ['keyword_image'];
									
									$notes_description2 = str_ireplace ( $keywordData ['active_tag'], $keywordData ['keyword_name'], $notes_description2 );
									
									/*
									 * if($active_tag == $keywordData['relation_hastag']){
									 * $notes_description2 =
									 * str_ireplace($keywordData['relation_hastag'],
									 * $keywordData['keyword_name'],$notes_description2);
									 * }
									 */
									if ($keywordData ['keyword_name'] != null && $keywordData ['keyword_name'] != "") {
										$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $this->db->escape ( $keywordData ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', active_tag = '" . $this->db->escape ( $active_tag ) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape ( $unique_id ) . "', date_added = '" . $createDate . "', status_total_time ='" . $data ['status_total_time'] . "' ";
										$this->db->query ( $sqlm );
										
										if ($keywordData ['monitor_time'] == '11') {
											$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
											$this->db->query ( $sql12 );
										}
									}
									
									if ($data ['config_multiple_activenote'] != '1') {
										break;
									}
								}
							}
							
							$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "',keyword_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
							$this->db->query ( $sql1 );
						}
					}
					
					if (! empty ( $data ['transcripts'] )) {
						
						$jsonData = stripslashes ( html_entity_decode ( $data ['transcripts'] ) );
						$transcripts = json_decode ( $jsonData, true );
						
						foreach ( $transcripts as $transcript ) {
							
							$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_transcript` SET
					source_transcript = '" . $this->db->escape ( $transcript ['source_transcript'] ) . "',
					source_language = '" . $this->db->escape ( $transcript ['source_language'] ) . "',
					target_transcript = '" . $this->db->escape ( $transcript ['target_transcript'] ) . "',
					target_language = '" . $this->db->escape ( $transcript ['target_language'] ) . "',
					notes_id = '" . $notes_id . "',
					date_added = '" . $update_date . "',	
					date_updated = '" . $update_date . "',
					facilities_id = '" . $facilities_id . "'
				";
							
							$this->db->query ( $sql );
						}
						
						$sqlt = "UPDATE `" . DB_PREFIX . "notes` SET is_comment = '2' where notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sqlt );
					}
					
					if ($data ['notes_file'] != null && $data ['notes_file'] != "") {
						$sql = "INSERT INTO `" . DB_PREFIX . "notes_media` SET notes_id = '" . $notes_id . "', notes_file = '" . $this->db->escape ( $data ['notes_file'] ) . "', notes_media_extention = '" . $this->db->escape ( $data ['notes_media_extention'] ) . "', status = '1', facilities_id = '" . $facilities_id . "', media_date_added = '" . $createDate . "' ";
						$this->db->query ( $sql );
						
						$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
						$this->db->query ( $sql12 );
					}
					
					if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "") {
						$tadata = array ();
						$tadata ['status_total_time'] = $data ['status_total_time'];
						$tadata ['manual_movement'] = $data ['manual_movement'];
						$tadata ['tag_status_ids'] = $data ['substatus_ids'];
						$tadata ['substatus_idscomment'] = $data ['substatus_idscomment'];
						$tadata ['fixed_status_id'] = $data ['fixed_status_id'];
						
						$notes_tags_id = $this->updateNotesTag ( $data ['emp_tag_id'], $notes_id, $data ['tags_id'], $update_date, $tadata );
						
						$sql = "update `" . DB_PREFIX . "notes_tags` set tag_status_id = '" . $data ['tag_status_id'] . "' ,tag_classification_id = '" . $data ['tag_classification_id'] . "',move_notes_id = '" . $data ['move_notes_id'] . "',manual_movement = '" . $data ['manual_movement'] . "',tag_status_ids = '" . $data ['substatus_ids'] . "',comments = '" . $this->db->escape ($data ['substatus_idscomment']) . "',fixed_status_id = '" . (int)$data ['fixed_status_id'] . "' where notes_tags_id='" . $notes_tags_id . "'";
						$this->db->query ( $sql );
						
						if ($data ['tag_status_id'] != null && $data ['tag_status_id'] != "") {
							$this->load->model ( 'resident/resident' );
							$cdata = array ();
							$cdata ['tag_status_id'] = $data ['tag_status_id'];
							$cdata ['tags_id'] = $data ['tags_id'];
							$cdata ['notes_id'] = $notes_id;
							$cdata ['facilities_id'] = $facilities_id;
							$this->model_resident_resident->addrolecallreport ( $cdata );
						}
					}
					
					if (! empty ( $data ['tags_id_list'] )) {
						$this->load->model ( 'setting/tags' );
						foreach ( $data ['tags_id_list'] as $tagid ) {
							$tag_info = $this->model_setting_tags->getTag ( $tagid );
							$tadata = array ();
							$tadata ['status_total_time'] = $data ['status_total_time'];
							$tadata ['manual_movement'] = $data ['manual_movement'];
							$notes_tags_id = $this->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
						}
					}
					
					if (! empty ( $data ['tags_id_list2'] )) {
						$this->load->model ( 'setting/tags' );
						$this->load->model ( 'resident/resident' );
						foreach ( $data ['tags_id_list2'] as $tagid ) {
							$tag_info = $this->model_setting_tags->getTag ( $tagid ['valueId'] );
							$tadata = array ();
							$tadata ['add_type'] = $tagid ['add_type'];
							$tadata ['rollcall'] = $tagid ['rollcall'];
							$tadata ['status_total_time'] = $tagid ['status_total_time'];
							$tadata ['manual_movement'] = $tagid ['manual_movement'];
							$notes_tags_id = $this->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
							
							if ($data ['clienttype'] == '1') {
								$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '1', $update_date );
							}
							if ($data ['role_call'] != null && $data ['role_call'] != "") {
								$this->load->model ( 'resident/resident' );
								$this->model_resident_resident->updatetagrolecall ( $tag_info ['tags_id'], $data ['role_call'] );
								
								$sql = "update `" . DB_PREFIX . "notes_tags` set tag_status_id = '" . $data ['role_call'] . "',move_notes_id = '" . $tag_info ['move_notes_id'] . "' where notes_tags_id='" . $notes_tags_id . "'";
								$this->db->query ( $sql );
								
								$this->load->model ( 'resident/resident' );
								$cdata = array ();
								$cdata ['tag_status_id'] = $data ['role_call'];
								$cdata ['tags_id'] = $tag_info ['tags_id'];
								$cdata ['notes_id'] = $notes_id;
								$cdata ['facilities_id'] = $facilities_id;
								$this->model_resident_resident->addrolecallreport ( $cdata );
							}
							if ($data ['clienttype'] == '2') {
								$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '0', $update_date );
							}
							if ($data ['clienttype'] == '3') {
								$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '1', $update_date );
								$this->load->model ( 'createtask/createtask' );
								$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tag_info ['tags_id'] );
								
								if ($alldatas != NULL && $alldatas != "") {
									foreach ( $alldatas as $alldata ) {
										$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
										$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
										$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
										$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
									}
								}
								
								$this->model_setting_tags->addcurrentTagarchive ( $tag_info ['tags_id'] );
								$this->model_setting_tags->updatecurrentTagarchive ( $tag_info ['tags_id'], $notes_id );
								
								$this->model_resident_resident->updateDischargeTag ( $tag_info ['tags_id'], $update_date );
							}
						}
					}
					if (! empty ( $data ['tags_id_list1'] )) {
						$this->load->model ( 'setting/tags' );
						$this->load->model ( 'resident/resident' );
						foreach ( $data ['tags_id_list1'] as $tagid ) {
							$tag_info = $this->model_setting_tags->getTag ( $tagid );
							$tadata = array ();
							$notes_tags_id = $this->updateNotesTag ( $tag_info ['emp_tag_id'], $notes_id, $tag_info ['tags_id'], $update_date, $tadata );
							
							if ($data ['clienttype'] == '1') {
								$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '1', $update_date );
							}
							if ($data ['role_call'] != null && $data ['role_call'] != "") {
								$this->load->model ( 'resident/resident' );
								$this->model_resident_resident->updatetagrolecall ( $tag_info ['tags_id'], $data ['role_call'] );
								
								$sql = "update `" . DB_PREFIX . "notes_tags` set tag_status_id = '" . $data ['role_call'] . "',move_notes_id = '" . $tag_info ['move_notes_id'] . "' where notes_tags_id='" . $notes_tags_id . "'";
								$this->db->query ( $sql );
								
								$this->load->model ( 'resident/resident' );
								$cdata = array ();
								$cdata ['tag_status_id'] = $data ['role_call'];
								$cdata ['tags_id'] = $tag_info ['tags_id'];
								$cdata ['notes_id'] = $notes_id;
								$cdata ['facilities_id'] = $facilities_id;
								$this->model_resident_resident->addrolecallreport ( $cdata );
							}
							if ($data ['clienttype'] == '2') {
								$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '0', $update_date );
							}
							if ($data ['clienttype'] == '3') {
								$this->model_setting_tags->updatetagmed ( $tag_info ['tags_id'], '1', $update_date );
								$this->load->model ( 'createtask/createtask' );
								$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tag_info ['tags_id'] );
								
								if ($alldatas != NULL && $alldatas != "") {
									foreach ( $alldatas as $alldata ) {
										$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
										$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
										$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
										$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
									}
								}
								
								$this->model_setting_tags->addcurrentTagarchive ( $tag_info ['tags_id'] );
								$this->model_setting_tags->updatecurrentTagarchive ( $tag_info ['tags_id'], $notes_id );
								
								$this->model_resident_resident->updateDischargeTag ( $tag_info ['tags_id'], $update_date );
							}
						}
					}
					
					if (! empty ( $data ['locationsid'] )) {
						$this->load->model ( 'setting/locations' );
						foreach ( $data ['locationsid'] as $locationsid ) {
							$location_info12 = $this->model_setting_locations->getlocation ( $locationsid );
							
							$sqll = "INSERT INTO `" . DB_PREFIX . "notes_by_location` SET notes_id = '" . $notes_id . "', location_id = '" . $this->db->escape ( $locationsid ) . "', location_name = '" . $this->db->escape ( $location_info12 ['location_name'] ) . "', facilities_id = '" . $facilities_id . "', date_added = '" . $createDate . "', date_updated = '" . $createDate . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
							$this->db->query ( $sqll );
						}
					}
					
					$notes_info = $this->getnotes ( $notes_id );
					
					if ($notes_info ['highlighter_id'] == "0") {
						$this->load->model ( 'user/user' );
						$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
						
						if ($user_info ['default_highlighter_id'] != "0") {
							$highlighter_id = $user_info ['default_highlighter_id'];
							
							$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '" . $highlighter_id . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
							$this->db->query ( $sql1 );
						}
					}
					
					if ($notes_info ['text_color'] == null && $notes_info ['text_color'] == "") {
						$this->load->model ( 'user/user' );
						$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
						
						if ($user_info ['default_color'] != null && $user_info ['default_color'] != "") {
							$default_color = $user_info ['default_color'];
							
							$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '" . $default_color . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
							$this->db->query ( $sql1 );
						}
					}
					
					if ($data ['monitor_time_1'] == "2") {
						$a2 = array ();
						$a2 ['notes_id'] = $notes_id;
						$a2 ['facilities_id'] = $facilities_id;
						$active_note_info_actives = $this->getNotebyactivenotes ( $a2 );
						
						// var_dump($active_note_info_actives);
						
						if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
							$description22 = ' | ' . $this->db->escape ( $data ['comments'] );
							
							$notes_description2 = $notes_info ['notes_description'];
							
							$notes_description2 = $notes_description2 . $description22;
							
							$sqlssd = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
							
							$this->db->query ( $sqlssd );
						}
						
						if ($active_note_info_actives != null && $active_note_info_actives != "") {
							
							foreach ( $active_note_info_actives as $active_note_info ) {
								
								if ($active_note_info ['keyword_id'] != null && $active_note_info ['keyword_id'] != "") {
									$this->load->model ( 'setting/keywords' );
									$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info ['keyword_id'] );
									
									$keyword_name1 = str_replace ( array (
											"\r",
											"\n" 
									), '', $keywordData2 ['keyword_name'] );
									
									$notes_description2 = $notes_info ['notes_description'];
									
									if ($keywordData2 ['monitor_time'] == '1') {
										
										if ($keywordData2 ['end_relation_keyword'] == '1') {
											$a3 = array ();
											$a3 ['keyword_id'] = $keywordData2 ['relation_keyword_id'];
											$a3 ['user_id'] = $user_info ['username'];
											$a3 ['facilities_id'] = $facilities_id;
											$a3 ['is_monitor_time'] = '1';
											$active_note_info2 = $this->model_notes_notes->getNotebyactivenote ( $a3 );
											
											if ($data ['override_monitor_time_user_id_checkbox'] == '1') {
												$note_info = $this->model_notes_notes->getNote ( $this->request->post ['override_monitor_time_user_id'] );
												
												$a3e = array ();
												$a3e ['notes_id'] = $note_info ['notes_id'];
												$a3e ['facilities_id'] = $facilities_id;
												$a3e ['is_monitor_time'] = '1';
												$active_note_info2e = $this->getNotebyactivenote ( $a3e );
												
												$keywordData21 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info2e ['keyword_id'] );
												
												$keywordData212 = $this->model_setting_keywords->getkeywordDetail ( $keywordData21 ['relation_keyword_id'] );
												
												if ($active_note_info2e ['keyword_id'] != "" && $active_note_info2e ['keyword_id'] != null) {
													
													$sqlda2d = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2e ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2e ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2e ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
													
													$this->db->query ( $sqlda2d );
													
													$start_date = new DateTime ( $note_info ['date_added'] );
													$since_start = $start_date->diff ( new DateTime ( $update_date ) );
													
													$caltime = "";
													
													if ($since_start->y > 0) {
														$caltime .= $since_start->y . ' years ';
													}
													if ($since_start->m > 0) {
														$caltime .= $since_start->m . ' months ';
													}
													if ($since_start->d > 0) {
														$caltime .= $since_start->d . ' days ';
													}
													if ($since_start->h > 0) {
														$caltime .= $since_start->h . ' hours ';
													}
													if ($since_start->i > 0) {
														$caltime .= $since_start->i . ' minutes ';
													}
													
													$keyword_name441 = $keywordData212 ['keyword_name'] . ' | ENDED | ' . $caltime . ' | Originally Started by ' . $note_info ['user_id'] . ' at | ' . date ( 'm-d-Y h:i A', strtotime ( $note_info ['date_added'] ) );
													$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
													
													$notes_description2 = $notes_description2 . $description22;
													
													$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
													
													$this->db->query ( $sqld );
													
													$sqldaff = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET override_monitor_time_user_id = '" . $this->db->escape ( $note_info ['user_id'] ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' ";
													
													$this->db->query ( $sqldaff );
													
													$keywordData23 = $this->model_setting_keywords->getkeywordDetail ( $keywordData212 ['keyword_id'] );
													
													/*
													 * $this->load->model('setting/image');
													 *
													 * $file16 =
													 * 'icon/'.$keywordData23['keyword_image'];
													 * $newfile84 =
													 * $this->model_setting_image->resize($file16,
													 * 50, 50);
													 * $newfile216 = DIR_IMAGE . $newfile84;
													 * $file124 = HTTP_SERVER .
													 * 'image/'.$newfile84;
													 *
													 * $imageData132 =
													 * base64_encode(file_get_contents($newfile216));
													 *
													 * if($newfile84 != null && $newfile84
													 * != ""){
													 * $keyword_icon =
													 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
													 * }else{
													 * $keyword_icon = '';
													 * }
													 */
													$keyword_icon = '';
													$keyword_file = $keywordData23 ['keyword_image'];
													
													$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $keywordData23 ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData23 ['keyword_name'] ) . "', active_tag = '" . $this->db->escape ( $keywordData23 ['active_tag'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "',keyword_file_url='" . $this->db->escape ( $keyword_icon ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
													
													$this->db->query ( $sqld22a2 );
												}
											} else {
												$keywordData21 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info2 ['keyword_id'] );
												
												$keywordData212 = $this->model_setting_keywords->getkeywordDetail ( $keywordData21 ['relation_keyword_id'] );
												
												$sqlda2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2 ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2 ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2 ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
												
												$this->db->query ( $sqlda2 );
												
												$start_date = new DateTime ( $active_note_info2 ['date_added'] );
												$since_start = $start_date->diff ( new DateTime ( $update_date ) );
												
												$caltime = "";
												
												if ($since_start->y > 0) {
													$caltime .= $since_start->y . ' years ';
												}
												if ($since_start->m > 0) {
													$caltime .= $since_start->m . ' months ';
												}
												if ($since_start->d > 0) {
													$caltime .= $since_start->d . ' days ';
												}
												if ($since_start->h > 0) {
													$caltime .= $since_start->h . ' hours ';
												}
												if ($since_start->i > 0) {
													$caltime .= $since_start->i . ' minutes ';
												}
												
												$keyword_name441 = $keywordData212 ['keyword_name'] . ' | ENDED | ' . $caltime . ' | ';
												$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
												
												$notes_description2 = $notes_description2 . $description22;
												
												$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
												
												$this->db->query ( $sqld );
												
												$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $active_note_info ['keyword_id'] . "' ";
												
												$this->db->query ( $sqlda );
											}
										} else {
											
											$a3 = array ();
											$a3 ['keyword_id'] = $active_note_info ['keyword_id'];
											$a3 ['user_id'] = $user_info ['username'];
											$a3 ['is_monitor_time'] = '1';
											$a3 ['facilities_id'] = $facilities_id;
											$active_note_info2 = $this->getNotebyactivenote ( $a3 );
											
											// var_dump($active_note_info2);
											// echo "<hr>";
											
											if ($data ['override_monitor_time_user_id_checkbox'] == '1') {
												
												$note_info = $this->model_notes_notes->getNote ( $this->request->post ['override_monitor_time_user_id'] );
												
												$a3e = array ();
												$a3e ['notes_id'] = $note_info ['notes_id'];
												$a3e ['facilities_id'] = $facilities_id;
												$a3e ['is_monitor_time'] = '1';
												$active_note_info2e = $this->getNotebyactivenote ( $a3e );
												// var_dump($active_note_info2e);
												// echo "<hr>";
												if ($active_note_info2e ['keyword_id'] != "" && $active_note_info2e ['keyword_id'] != null) {
													
													$sqlda2d = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2e ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2e ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2e ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
													
													$this->db->query ( $sqlda2d );
													
													$start_date = new DateTime ( $note_info ['date_added'] );
													$since_start = $start_date->diff ( new DateTime ( $update_date ) );
													
													$caltime = "";
													
													if ($since_start->y > 0) {
														$caltime .= $since_start->y . ' years ';
													}
													if ($since_start->m > 0) {
														$caltime .= $since_start->m . ' months ';
													}
													if ($since_start->d > 0) {
														$caltime .= $since_start->d . ' days ';
													}
													if ($since_start->h > 0) {
														$caltime .= $since_start->h . ' hours ';
													}
													if ($since_start->i > 0) {
														$caltime .= $since_start->i . ' minutes ';
													}
													
													$keyword_name441 = $active_note_info2e ['keyword_name'] . ' | ENDED | ' . $caltime . ' | Originally Started by ' . $note_info ['user_id'] . ' at | ' . date ( 'm-d-Y h:i A', strtotime ( $note_info ['date_added'] ) );
													$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
													
													$notes_description2 = $notes_description2 . $description22;
													
													$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
													
													$this->db->query ( $sqld );
													
													$sqldaff = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET override_monitor_time_user_id = '" . $this->db->escape ( $note_info ['user_id'] ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $active_note_info2e ['keyword_id'] . "' ";
													
													$this->db->query ( $sqldaff );
													
													$keywordData23 = $this->model_setting_keywords->getkeywordDetail ( $active_note_info2e ['keyword_id'] );
													
													// var_dump($keywordData23);
													// echo "<hr>";
													
													if ($keywordData23 ['relation_keyword_id'] != null && $keywordData23 ['relation_keyword_id'] != "0") {
														
														$keywordData23r = $this->model_setting_keywords->getkeywordDetail ( $keywordData23 ['relation_keyword_id'] );
														
														// var_dump($keywordData23r);
														
														/*
														 * $this->load->model('setting/image');
														 *
														 * $file16 =
														 * 'icon/'.$keywordData23r['keyword_image'];
														 * $newfile84 =
														 * $this->model_setting_image->resize($file16,
														 * 50, 50);
														 * $newfile216 = DIR_IMAGE .
														 * $newfile84;
														 * $file124 = HTTP_SERVER .
														 * 'image/'.$newfile84;
														 *
														 * $imageData132 =
														 * base64_encode(file_get_contents($newfile216));
														 *
														 * if($newfile84 != null &&
														 * $newfile84 != ""){
														 * $keyword_icon =
														 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
														 * }else{
														 * $keyword_icon = '';
														 * }
														 */
														$keyword_icon = '';
														
														$keyword_file = $keywordData23r ['keyword_image'];
														
														$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $keywordData23r ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData23r ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "',keyword_file_url='" . $this->db->escape ( $keyword_icon ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
														
														$this->db->query ( $sqld22a2 );
														
														$note_infou = $this->model_notes_notes->getNote ( $notes_id );
														
														// var_dump($note_infou);
														
														$notes_description2 = $note_infou ['notes_description'];
														
														$notes_description2 = str_ireplace ( $keywordData23 ['keyword_name'], $keywordData23r ['keyword_name'], $notes_description2 );
														
														// var_dump($notes_description2);
														
														$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
														$this->db->query ( $sql1 );
													}
												}
											} else if ($active_note_info2 ['keyword_id'] != null && $active_note_info2 ['keyword_id'] != "") {
												
												$sqlda2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $active_note_info2 ['notes_id'] . "' and keyword_id = '" . ( int ) $active_note_info2 ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $active_note_info2 ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
												
												$this->db->query ( $sqlda2 );
												
												$start_date = new DateTime ( $active_note_info2 ['date_added'] );
												$since_start = $start_date->diff ( new DateTime ( $update_date ) );
												
												$caltime = "";
												
												if ($since_start->y > 0) {
													$caltime .= $since_start->y . ' years ';
												}
												if ($since_start->m > 0) {
													$caltime .= $since_start->m . ' months ';
												}
												if ($since_start->d > 0) {
													$caltime .= $since_start->d . ' days ';
												}
												if ($since_start->h > 0) {
													$caltime .= $since_start->h . ' hours ';
												}
												if ($since_start->i > 0) {
													$caltime .= $since_start->i . ' minutes ';
												}
												
												$keyword_name441 = $keywordData2 ['keyword_name'] . ' | ENDED | ' . $caltime . ' | ';
												$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
												
												$notes_description2 = $notes_description2 . $description22;
												
												$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
												
												$this->db->query ( $sqld );
												
												$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $active_note_info ['keyword_id'] . "' ";
												
												$this->db->query ( $sqlda );
												
												if ($keywordData2 ['relation_keyword_id'] != null && $keywordData2 ['relation_keyword_id'] != "0") {
													$this->load->model ( 'setting/image' );
													
													$keywordData23rs = $this->model_setting_keywords->getkeywordDetail ( $keywordData2 ['relation_keyword_id'] );
													
													/*
													 * $file16 =
													 * 'icon/'.$keywordData23rs['keyword_image'];
													 * $newfile84 =
													 * $this->model_setting_image->resize($file16,
													 * 50, 50);
													 * $newfile216 = DIR_IMAGE .
													 * $newfile84;
													 * $file124 = HTTP_SERVER .
													 * 'image/'.$newfile84;
													 *
													 * $imageData132 =
													 * base64_encode(file_get_contents($newfile216));
													 *
													 * if($newfile84 != null &&
													 * $newfile84 != ""){
													 * $keyword_icon =
													 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
													 * }else{
													 * $keyword_icon = '';
													 * }
													 */
													$keyword_icon = '';
													
													$keyword_file = $keywordData23rs ['keyword_image'];
													
													$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $keywordData23rs ['keyword_id'] ) . "',keyword_name = '" . $this->db->escape ( $keywordData23rs ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "',keyword_file_url='" . $this->db->escape ( $keyword_icon ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_id = '" . ( int ) $active_note_info2 ['keyword_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
													
													$this->db->query ( $sqld22a2 );
													
													$note_infou = $this->model_notes_notes->getNote ( $notes_id );
													
													// var_dump($note_infou);
													
													$notes_description2 = $note_infou ['notes_description'];
													
													$notes_description2 = str_ireplace ( $keywordData2 ['keyword_name'], $keywordData23rs ['keyword_name'], $notes_description2 );
													
													// var_dump($notes_description2);
													
													$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
													$this->db->query ( $sql1 );
												}
											} else {
												
												$keyword_name441 = $keywordData2 ['keyword_name'] . ' | STARTED | ';
												$notes_description2 = str_replace ( $keyword_name1, $keyword_name441, $notes_description2 );
												
												$notes_description2 = $notes_description2 . $description22;
												
												$sqld = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
												
												$this->db->query ( $sqld );
												
												$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '1' WHERE notes_id = '" . ( int ) $notes_id . "' and facilities_id = '" . ( int ) $facilities_id . "' and keyword_id = '" . ( int ) $keywordData2 ['keyword_id'] . "' ";
												
												$this->db->query ( $sqlda );
											}
										}
									}
								}
							}
						}
					}
					
					$this->load->model ( 'activity/activity' );
					$adata ['notes_id'] = $notes_id;
					$adata ['notes_description'] = $data ['notes_description'] . ' ' . $notes_description2;
					$adata ['user_id'] = $data ['user_id'];
					$adata ['username'] = $data ['username'];
					$adata ['notes_file'] = $data ['notes_file'];
					$adata ['date_added'] = $data ['note_date'];
					$adata ['note_date'] = $noteDate;
					$adata ['update_date'] = $update_date;
					$adata ['is_offline'] = $data ['is_offline'];
					$adata ['facilities_id'] = $facilities_id;
					$adata ['keyword_file'] = $data ['keyword_file'];
					$adata ['tags_id_list'] = $data ['tags_id_list'];
					$adata ['phone_device_id'] = $data ['phone_device_id'];
					$adata ['is_android'] = $data ['is_android'];
					$adata ['device_unique_id'] = $data ['device_unique_id'];
					$this->model_activity_activity->addActivitySave ( 'jsonaddnotes', $adata, 'query' );
					
					if ($this->config->get ( 'config_realtime_data' ) == '1') {
						$this->load->model ( 'api/realtime' );
						$realdata = array ();
						$realdata ['facilities_id'] = $facilities_id;
						$realdata ['notes_id'] = $notes_id;
						$this->model_api_realtime->addrealtime ( $realdata );
					}
					
					return $notes_id;
				}
			}
		} else {
			$this->load->model ( 'activity/activity' );
			$adata = array ();
			$adata ['data'] = $data;
			$adata ['facilities_id'] = $facilities_id;
			$this->model_activity_activity->addActivitySave ( 'jsonaddnotesblankdesc', $adata, 'query' );
		}
	}
	public function updatenotessignature($notes_id, $data, $update_date) {
		
		// if($data['notes_pin'] != null && $data['notes_pin'] != ""){
		$notes_pin = $data ['notes_pin'];
		// $signature = "";
		// }else{
		$signature = $data ['signature'];
		// $notes_pin = '';
		// }
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$note_info = $this->getnotes ( $notes_id );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $note_info ['facilities_id'] );
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_type = '" . $this->db->escape ( $data ['notes_type'] ) . "', status = '1', signature = '" . $signature . "', signature_image = '" . $fileName . "', notes_pin = '" . $this->db->escape ( $notes_pin ) . "', emp_tag_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "', tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "', update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['emp_tag_id'] = $data ['emp_tag_id'];
		$adata ['tags_id'] = $data ['tags_id'];
		$adata ['update_date'] = $update_date;
		$this->model_activity_activity->addActivitySave ( 'updatenotessignature', $adata, 'query' );
	}
	public function jsonupdateStrikeNotes($data, $facilities_id) {
		$timezone_name = $data ['facilitytimezone'];
		
		date_default_timezone_set ( $timezone_name );
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$note_date = $data ['note_date'];
		
		$createDate = date ( 'Y-m-d H:i:s', strtotime ( $note_date ) );
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$signature = "";
		if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
			$this->load->model ( 'api/savesignature' );
			$sigdata = array ();
			$sigdata ['upload_file'] = $data ['imgOutput'];
			$sigdata ['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
			
			$signature = $signaturestatus;
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET text_color_cut = '1', strike_user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', strike_note_type = '" . $this->db->escape ( $data ['strike_note_type'] ) . "', strike_signature = '" . $signature . "', strike_signature_image = '" . $fileName . "', strike_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', strike_date_added = '" . $noteDate . "', update_date = '" . $update_date . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and facilities_id = '" . $facilities_id . "' ";
		
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['update_date'] = $update_date;
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'jsonupdateStrikeNotes', $adata, 'query' );
	}
	public function jsonaddreview($data, $facilities_id) {
		$timezone_name = $data ['facilitytimezone'];
		
		date_default_timezone_set ( $timezone_name );
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		/* $date_added = $data['date_added']; */
		
		$node_date = $data ['note_date'];
		
		$createDate2 = date ( 'Y-m-d', strtotime ( $node_date ) );
		$createtime = date ( 'H:i:s', strtotime ( 'now' ) );
		$createDate = $createDate2 . ' ' . $createtime;
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$signature = "";
		if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
			$this->load->model ( 'api/savesignature' );
			$sigdata = array ();
			$sigdata ['upload_file'] = $data ['imgOutput'];
			$sigdata ['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
			
			$signature = $signaturestatus;
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "reviewed_by` SET facilities_id = '" . $facilities_id . "', user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_type = '" . $this->db->escape ( $data ['notes_type'] ) . "', signature = '" . $signature . "', notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', date_added = '" . $createDate . "', signature_image = '" . $fileName . "', note_date = '" . $noteDate . "' ";
		
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['date_added'] = $createDate;
		$adata ['noteDate'] = $noteDate;
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'jsonaddreview', $adata, 'query' );
	}
	public function jsonaddreviewbyID($data, $facilities_id) {
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$signature = "";
		if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
			$this->load->model ( 'api/savesignature' );
			$sigdata = array ();
			$sigdata ['upload_file'] = $data ['imgOutput'];
			$sigdata ['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
			
			$signature = $signaturestatus;
		}
		
		$this->db->query ( "INSERT INTO `" . DB_PREFIX . "reviewed_by` SET facilities_id = '" . $facilities_id . "', user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', notes_type = '" . $this->db->escape ( $data ['notes_type'] ) . "', signature = '" . $signature . "', notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "', signature_image = '" . $fileName . "', notes_id = '" . $data ['notes_id'] . "', date_added = NOW() " );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'jsonaddreviewbyID', $adata, 'query' );
	}
	public function jsongetreviews($data = array()) {
		$sql = "SELECT reviewed_by_id,user_id,signature,signature_image,date_added,facilities_id,notes_pin,notes_id,note_date,notes_type FROM `" . DB_PREFIX . "reviewed_by`";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getReview($searchdate) {
		if ($searchdate != null && $searchdate != "") {
			$startDate = date ( 'Y-m-d', strtotime ( $searchdate ) );
			/* $endDate = date('Y-m-d'); */
			$endDate = date ( 'Y-m-d', strtotime ( $searchdate ) );
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59')";
		}
		
		$query = $this->db->query ( "SELECT reviewed_by_id,user_id,signature,signature_image,date_added,facilities_id,notes_pin,notes_id,note_date,notes_type FROM `" . DB_PREFIX . "reviewed_by` WHERE (`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59') " );
		
		return $query->row;
	}
	public function jsongetReviewModel($data = array()) {
		$sql = "SELECT reviewed_by_id,user_id,signature,signature_image,date_added,facilities_id,notes_pin,notes_id,note_date,notes_type FROM `" . DB_PREFIX . "reviewed_by`";
		
		$sql .= 'where 1 = 1 ';
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
		}
		if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			/* $endDate = date('Y-m-d'); */
			$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59')";
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getreviews($data = array()) {
		$sql = "SELECT reviewed_by_id,user_id,signature,signature_image,date_added,facilities_id,notes_pin,notes_id,note_date,notes_type FROM `" . DB_PREFIX . "reviewed_by`";
		
		$sql .= 'where 1 = 1 ';
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
		}
		if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
			/* $startDate = date('Y-m-d', strtotime($data['searchdate'])); */
			/* $endDate = date('Y-m-d'); */
			/*
			 * $endDate = date('Y-m-d', strtotime($data['searchdate']));
			 */
			$date = str_replace ( '-', '/', $data ['searchdate'] );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$startDate = $changedDate; /*
			                            * date('Y-m-d',
			                            * strtotime($data['searchdate']));
			                            */
			/* $endDate = date('Y-m-d'); */
			$endDate = $changedDate; /*
			                          * date('Y-m-d',
			                          * strtotime($data['searchdate']));
			                          */
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59')";
		} else {
			$startDate = date ( 'Y-m-d' );
			$endDate = date ( 'Y-m-d' );
			$sql .= " and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59' ";
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function jsonaddReminder($data, $facilities_id) {
		$date_added = $data ['date_added'];
		
		$createDate = date ( 'Y-m-d H:i:s', strtotime ( $date_added ) );
		
		$sql = "INSERT INTO `" . DB_PREFIX . "reminder` SET facilities_id = '" . $facilities_id . "', notes_id = '" . $data ['notes_id'] . "', reminder_time = '" . $this->db->escape ( $data ['reminder_time'] ) . "', date_added = '" . $createDate . "' ";
		$this->db->query ( $sql );
		
		$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET is_reminder = '1', update_date = '" . $createDate . "', notes_conut='0' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "'";
		$this->db->query ( $sql12 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['reminder_time'] = $data ['reminder_time'];
		$adata ['date_added'] = $createDate;
		$adata ['update_date'] = $createDate;
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'jsonaddReminder', $adata, 'query' );
	}
	public function jsonDeleteReminder($data) {
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "reminder` WHERE notes_id = '" . $data ['notes_id'] . "' and facilities_id = '" . $data ['facilities_id'] . "' " );
	}
	public function addReminderModel($data, $facilities_id) {
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "reminder` WHERE notes_id = '" . $data ['notes_id'] . "' and facilities_id = '" . $facilities_id . "' " );
		
		$sql = "INSERT INTO `" . DB_PREFIX . "reminder` SET facilities_id = '" . $facilities_id . "', notes_id = '" . $data ['notes_id'] . "', reminder_time = '" . $this->db->escape ( $data ['reminder_time'] ) . "', reminder_title = '" . $this->db->escape ( $data ['reminder_title'] ) . "', date_added = NOW() ";
		$this->db->query ( $sql );
		
		$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET is_reminder = '1', update_date = '" . $data ['update_date'] . "', notes_conut='0' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "'";
		$this->db->query ( $sql12 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['reminder_time'] = $data ['reminder_time'];
		$adata ['reminder_title'] = $data ['reminder_title'];
		$adata ['date_added'] = $data ['update_date'];
		$adata ['update_date'] = $data ['update_date'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'addReminderModel', $adata, 'query' );
	}
	public function updateReminderModel($data, $facilities_id) {
		$sql = "UPDATE `" . DB_PREFIX . "reminder` SET reminder_time = '" . $data ['reminder_time'] . "' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
		$this->db->query ( $sql );
		
		$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET is_reminder = '1', update_date = '" . $data ['update_date'] . "', notes_conut='0' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "'";
		$this->db->query ( $sql12 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['reminder_time'] = $data ['reminder_time'];
		$adata ['date_added'] = $data ['update_date'];
		$adata ['update_date'] = $data ['update_date'];
		$adata ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'updateReminderModel', $adata, 'query' );
	}
	public function getReminder($notes_id) {
		$sql = "SELECT reminder_id,notes_id,reminder_time,	reminder_title,	date_added,	facilities_id FROM `" . DB_PREFIX . "reminder` WHERE notes_id = '" . ( int ) $notes_id . "'";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function getImages($notes_id) {
		$sql = "SELECT notes_media_id,notes_file,notes_id,deleted,status,notes_media_extention,	media_user_id,media_date_added,media_signature,	media_signature_image,	media_pin,	update_media,notes_type,	audio_attach_url,	audio_attach_type,	audio_upload_file,	facilities_id FROM `" . DB_PREFIX . "notes_media` WHERE notes_id = '" . ( int ) $notes_id . "' and status = '1' ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getImage($notes_media_id) {
		$sql = "SELECT notes_media_id,notes_file,notes_id,deleted,status,notes_media_extention,	media_user_id,media_date_added,media_signature,	media_signature_image,	media_pin,	update_media,notes_type,	audio_attach_url,	audio_attach_type,	audio_upload_file,	facilities_id FROM `" . DB_PREFIX . "notes_media` WHERE notes_media_id = '" . ( int ) $notes_media_id . "' and status = '1' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function updateNoteFile($notes_id, $notes_file, $notes_media_extention, $data) {
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		if ($data ['media_user_id'] != null && $data ['media_user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['media_user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $data ['facilities_id'] );
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_media` SET notes_id = '" . $notes_id . "', notes_file = '" . $this->db->escape ( $notes_file ) . "', notes_media_extention = '" . $notes_media_extention . "', status = '1', media_user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', media_signature = '" . $data ['media_signature'] . "', notes_type = '" . $data ['notes_type'] . "', media_signature_image = '', media_pin = '" . $this->db->escape ( $data ['media_pin'] ) . "', media_date_added = '" . $data ['noteDate'] . "', facilities_id = '" . $data ['facilities_id'] . "',phone_device_id = '" . $this->db->escape ( $data ['phone_device_id'] ) . "',is_android = '" . $this->db->escape ( $data ['is_android'] ) . "' ";
		$this->db->query ( $sql );
		
		$notes_media_id = $this->db->getLastId ();
		
		$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '1',phone_device_id = '" . $this->db->escape ( $data2 ['phone_device_id'] ) . "',is_android = '" . $this->db->escape ( $data2 ['is_android'] ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
		$this->db->query ( $sql12 );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes_media` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_media_id = '" . ( int ) $notes_media_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes_media` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_media_id = '" . ( int ) $notes_media_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					$facilities_id = $data ['facilities_id'];
					
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					$this->model_notes_notes->updateuserpicturenotesmedia ( $s3file, $notes_id, $notes_media_id );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverifiednotesmedia ( '2', $notes_id, $notes_media_id );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverifiednotesmedia ( '1', $notes_id, $notes_media_id );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_file'] = $notes_file;
		$adata ['media_user_id'] = $data ['media_user_id'];
		$adata ['phone_device_id'] = $data2 ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$adata ['date_added'] = $data ['noteDate'];
		$this->model_activity_activity->addActivitySave ( 'updateNoteFile', $adata, 'query' );
		
		/*
		 * $sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_file =
		 * '".$notes_file."' WHERE notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql);
		 */
		return $notes_media_id;
	}
	public function updateNoteaudioFile($notes_id, $audio_attach_url, $notes_media_extention, $data) {
		$notes_info = $this->getNote ( $notes_id );
		$facilities_id = $notes_info ['facilities_id'];
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		if ($data ['media_user_id'] != null && $data ['media_user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['media_user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_media` SET notes_id = '" . $notes_id . "', audio_attach_url = '" . $this->db->escape ( $audio_attach_url ) . "', notes_media_extention = '" . $notes_media_extention . "', status = '1', media_user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', media_signature = '" . $data ['media_signature'] . "', notes_type = '" . $data ['notes_type'] . "', media_signature_image = '', media_pin = '" . $this->db->escape ( $data ['media_pin'] ) . "', media_date_added = '" . $data ['noteDate'] . "', audio_upload_file = '" . $data ['audio_upload_file'] . "', audio_attach_type = '" . $data ['audio_attach_type'] . "', facilities_id = '" . $facilities_id . "' ";
		$this->db->query ( $sql );
		
		$notes_media_id = $this->db->getLastId ();
		
		$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
		$this->db->query ( $sql12 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['audio_attach_url'] = $audio_attach_url;
		$adata ['audio_upload_file'] = $data ['audio_upload_file'];
		$adata ['media_user_id'] = $data ['media_user_id'];
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$adata ['date_added'] = $data ['noteDate'];
		$this->model_activity_activity->addActivitySave ( 'updateNoteaudioFile', $adata, 'query' );
		
		return $notes_media_id;
		
		/*
		 * $sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_file =
		 * '".$notes_file."' WHERE notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql);
		 */
	}
	public function getadvancednotess($data = array()) {
		
		// date_default_timezone_set($this->session->data['time_zone_1']);
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes`";
		
		$sql .= 'where 1 = 1 ';
		if ($data ['keyword'] != null && $data ['keyword'] != "") {
			$sql .= " and LOWER(notes_description) like '%" . strtolower ( $data ['keyword'] ) . "%'";
		}
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$sql .= " and user_id = '" . $data ['user_id'] . "'";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			$sql .= " and date_added BETWEEN '" . $startDate . " 00:00:00' AND  '" . $startDate . " 23:59:59'";
		}
		
		$sql .= " and status = '1'";
		
		$sql .= " ORDER BY date_added ASC";
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		
		/*
		 * $boteData = array();
		 * if($query->rows){
		 * foreach($query->rows as $note){
		 * $boteData[] = array(
		 * 'notes_id' => $note['notes_id'],
		 * 'user_id' => $note['user_id'],
		 * 'notetime' => $note['notetime'],
		 * 'text_color_cut' => $note['text_color_cut'],
		 * 'text_color' => $note['text_color'],
		 * 'notes_description' => $note['notes_description']
		 * );
		 * }
		 * }
		 */
		return $query->rows;
	}
	public function gettagassigns($facilities_id) {
		// $sql = "SELECT DISTINCT emp_tag_id,tags_id FROM `" . DB_PREFIX .
		// "notes` WHERE status = '1' and facilities_id = '" . $facilities_id .
		// "' and emp_tag_id != '' ";
		$sql = "SELECT DISTINCT emp_tag_id,tags_id FROM `" . DB_PREFIX . "notes_tags` WHERE emp_tag_id != '' ORDER BY emp_tag_id ASC ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function jsonaddWatchnotes($data, $facilities_id) {
		// $facilities_id = '21';
		$createdate1 = '29-05-2016'; // $data['note_date'];
		$createtime1 = date ( 'H:i:s' );
		$createDate2 = $createdate1 . $createtime1;
		$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
		
		$timezone_name = $data ['facilitytimezone'];
		
		date_default_timezone_set ( 'US/Eastern' );
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$date_added = $data ['date_added'];
		$note_date = '29-05-2016'; // $data['note_date'];
		
		$createDate = date ( 'Y-m-d H:i:s', strtotime ( $note_date ) );
		
		$notetime = date ( 'h:i A' );
		
		if ($this->config->get ( 'config_time_picker' ) == '0') {
			$noteTime = date ( 'H:i:s', strtotime ( 'now' ) );
		} else {
			$notetime1 = explode ( ":", $notetime );
			if ($notetime1 [0] == "00") {
				// $notetime2 = '12:'.$notetime1[1];
				$notetime2 = $notetime;
			} else {
				$notetime2 = $notetime;
			}
			$noteTime = date ( 'H:i:s', strtotime ( $notetime2 ) );
		}
		
		if ($data ['keyword_file'] != null && $data ['keyword_file'] != "") {
			/*
			 * $this->load->model('setting/image');
			 *
			 * $file16 = '/icon/'.$data['keyword_file'];
			 *
			 * $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
			 * $newfile216 = DIR_IMAGE . $newfile84;
			 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
			 * $imageData132 = base64_encode(file_get_contents($newfile216));
			 *
			 * if($newfile84 != null && $newfile84 != ""){
			 * $keyword_icon =
			 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
			 * }else{
			 * $keyword_icon = '';
			 * }
			 */
			
			$keyword_icon = '';
		}
		
		if ($data ['notes_pin'] != null && $data ['notes_pin'] != "") {
			$notes_pin = $data ['notes_pin'];
			$signature = "";
		} else {
			// $signature = $data ['imgOutput'];
			
			$signature = "";
			if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
				$this->load->model ( 'api/savesignature' );
				$sigdata = array ();
				$sigdata ['upload_file'] = $data ['imgOutput'];
				$sigdata ['facilities_id'] = $facilities_id;
				$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
				
				$signature = $signaturestatus;
			}
			
			$notes_pin = '';
		}
		$notes_pin = '12345';
		
		if ($data ['text_color'] != null && $data ['text_color'] != "") {
			$text_color = '#' . $data ['text_color'];
		} else {
			$text_color = "";
		}
		
		$this->load->model ( 'setting/highlighter' );
		$highlighterData = $this->model_setting_highlighter->gethighlighter ( $data ['highlighter_id'] );
		$highlighter_value = $highlighterData ['highlighter_value'];
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape ( $data ['notes_description'] ) . "', highlighter_id = '" . $this->db->escape ( $data ['highlighter_id'] ) . "', notes_pin = '" . $this->db->escape ( $notes_pin ) . "', notes_file = '" . $this->db->escape ( $data ['notes_file'] ) . "', user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', status = '1', notetime = '" . $noteTime . "', signature = '" . $signature . "', signature_image = '" . $fileName . "', text_color_cut = '0', text_color = '" . $this->db->escape ( $text_color ) . "', date_added = '" . $createDate . "',  note_date = '" . $noteDate . "',  global_utc_timezone = UTC_TIMESTAMP( ), keyword_file = '" . $this->db->escape ( $data ['keyword_file'] ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', highlighter_value = '" . $this->db->escape ( $highlighter_value ) . "' ";
		
		$this->db->query ( $sql );
	}
	public function getnotesbyUser($data, $facilities_id, $timezone_name) {
		$sql = "select n.* from `" . DB_PREFIX . "notes` n ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and n.status = '1' ";
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '" . $data ['facilities_id'] . "'";
		}
		
		if ($data ['reviewed_by'] != "3") {
			if ($data ['user_id'] != null && $data ['user_id'] != "") {
				$sql .= " and n.user_id = '" . $data ['user_id'] . "'";
			}
		}
		
		if ($data ['activenote'] != null && $data ['activenote'] != "") {
			$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
			$keydata = $query2->row;
			// $sql .= " and nk.keyword_file = '" . $keydata ['keyword_image'] . "'";
		}
		
		if ($data ['reviewed_by'] != null && $data ['reviewed_by'] != "") {
			
			if ($data ['reviewed_by'] == '1') {
				$sql .= " and n.review_notes = '1'";
			}
			
			if ($data ['reviewed_by'] == '3') {
				
				date_default_timezone_set ( $timezone_name );
				
				$date = str_replace ( '-', '/', $data ['date_from'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$startDate = $changedDate;
				// $endDate = date('Y-m-d');
				$endDate = $changedDate;
				
				$sql .= " and (n.`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
			}
		}
		
		$sql .= " ORDER BY notes_id DESC LIMIT 1 ";
		
		// $sql = "SELECT * FROM `" . DB_PREFIX . "notes` WHERE status = '1' and
		// facilities_id = '" . $facilities_id . "' ".$sql2." ORDER BY notes_id
		// DESC LIMIT 1; ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function updatenotesTags($data, $notes_id, $timezone_name) {
		$notes_info = $this->getNote ( $notes_id );
		$notes_description = $notes_info ['notes_description'];
		$facilities_id = $notes_info ['facilities_id'];
		$unique_id = $notes_info ['unique_id'];
		
		if ($data ['notes_pin'] != null && $data ['notes_pin'] != "") {
			$notes_pin = $data ['notes_pin'];
			$signature = "";
		} else {
			// $signature = $data ['imgOutput'];
			
			$signature = "";
			if ($data ['imgOutput'] != '' && $data ['imgOutput'] != null) {
				$this->load->model ( 'api/savesignature' );
				$sigdata = array ();
				$sigdata ['upload_file'] = $data ['imgOutput'];
				$sigdata ['facilities_id'] = $facilities_id;
				$signaturestatus = $this->model_api_savesignature->savesignature ( $sigdata );
				
				$signature = $signaturestatus;
			}
			
			$notes_pin = '';
		}
		
		date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		/*
		 * $sql1 = "SELECT * FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id =
		 * '" . (int)$notes_id . "' ";
		 * $query = $this->db->query($sql1);
		 *
		 * if($query->num_rows > 0){
		 * $sql = "UPDATE `" . DB_PREFIX . "notes_tags` SET user_id = '" .
		 * $this->db->escape($data['user_id']) . "', signature = '" . $signature
		 * . "', signature_image = '" . $fileName . "', notes_pin = '" .
		 * $this->db->escape($notes_pin) . "', emp_tag_id = '" .
		 * $this->db->escape($data['emp_tag_id']) . "',tags_id = '" .
		 * $this->db->escape($data['tags_id']) . "', date_added = '" .
		 * $date_added . "', notes_type = '" . $data['notes_type'] . "' where
		 * notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql);
		 *
		 * }else{
		 */
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );
		$emp_first_name = $tag_info ['emp_first_name'];
		$emp_last_name = $tag_info ['emp_last_name'];
		$emp_tag_id = $tag_info ['emp_tag_id'];
		
		$this->load->model ( 'user/user' );
		// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
		} else {
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $facilities_id );
		}
		
		$sql = "insert INTO `" . DB_PREFIX . "notes_tags` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', signature = '" . $signature . "', signature_image = '" . $fileName . "', notes_pin = '" . $this->db->escape ( $notes_pin ) . "', emp_tag_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "',tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "',phone_device_id = '" . $this->db->escape ( $data ['phone_device_id'] ) . "',is_android = '" . $this->db->escape ( $data ['is_android'] ) . "', notes_id = '" . ( int ) $notes_id . "', facilities_id = '" . ( int ) $facilities_id . "', date_added = '" . $date_added . "', unique_id = '" . $unique_id . "', notes_type = '" . $data ['notes_type'] . "' ";
		
		$this->db->query ( $sql );
		
		$notes_tags_id = $this->db->getLastId ();
		
		$this->load->model ( 'setting/locations' );
		$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
		
		// $keyval = $emp_tag_id . ':' . $emp_first_name;
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id, $tag_info );
		
		$cname = $clientinfo ['name'];
		
		$keyval = $cname;
		
		$pos = strpos ( $notes_description, $keyval );
		
		if ($pos === false) {
			// $notes_description2 = $notes_description . ' | ' . $emp_tag_id . ':' . $emp_first_name;
			
			$notes_description2 = $notes_description . ' ' . $cname;
		} else {
			$notes_description2 = $notes_description;
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0', emp_tag_id ='1', update_date = '" . $date_added . "' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes_tags` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes_tags` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					
					// $facilities_id = $facilities_id;
					
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					$this->model_notes_notes->updateuserpicturenotestag ( $s3file, $notes_id, $notes_tags_id );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverifiednotestag ( '2', $notes_id, $notes_tags_id );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverifiednotestag ( '1', $notes_id, $notes_tags_id );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$adata ['emp_tag_id'] = $data ['emp_tag_id'];
		$adata ['tags_id'] = $data ['tags_id'];
		$adata ['media_user_id'] = $data ['user_id'];
		$adata ['media_username'] = $data ['username'];
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['facilities_id'] = $facilities_id;
		$adata ['date_added'] = $date_added;
		$adata ['notes_tags_id'] = $notes_tags_id;
		$this->model_activity_activity->addActivitySave ( 'updatenotesTags', $adata, 'query' );
		
		return $notes_tags_id;
		
		/*
		 * $queryf = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms
		 * WHERE notes_id = '" . $notes_id . "' ");
		 *
		 * if($queryf->num_rows > 0){
		 * $sqlf = "UPDATE `" . DB_PREFIX . "notes` SET tags_id = '" .
		 * $this->db->escape($tags_id) . "' where notes_id = '" . (int)$notes_id
		 * . "' ";
		 * $this->db->query($sqlf);
		 * }
		 */
		
		/* } */
	}
	public function getNotesTags($notes_id) {
		$sql = "SELECT notes_tags_id,	emp_tag_id,	tags_id,	notes_id,	user_id,	date_added,	signature,	signature_image,	notes_pin,	notes_type,	facilities_id,	is_census,	lunch,	dinner,	breakfast,	refused,tag_status_id,tag_classification_id,forms_id,move_notes_id,forms_id,status_total_time,manual_movement FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . ( int ) $notes_id . "' order by date_added DESC ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getNotesTagsmultiple($notes_id) {
		$sql = "SELECT notes_tags_id,	emp_tag_id,	tags_id,	notes_id,	user_id,	date_added,	signature,	signature_image,	notes_pin,	notes_type,	facilities_id,	is_census,	lunch,	dinner,	breakfast,	refused,tag_status_id,tag_classification_id,forms_id,move_notes_id,forms_id,status_total_time FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . ( int ) $notes_id . "' order by date_added DESC ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function updateNotesTag22($emp_tag_id, $notes_id, $tags_id, $date_added, $tadata) {
		// $sql = "UPDATE `" . DB_PREFIX . "notes` SET emp_tag_id =
		// '".$emp_tag_id."', tags_id = '".$tags_id."', update_date =
		// '".$update_date."' WHERE notes_id = '" . (int)$notes_id . "' ";
		// $this->db->query($sql);
		
		/*
		 * $sql1 = "SELECT * FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id =
		 * '" . (int)$notes_id . "' ";
		 * $query = $this->db->query($sql1);
		 *
		 * if($query->num_rows > 0){
		 * $sql = "UPDATE `" . DB_PREFIX . "notes_tags` SET user_id = '',
		 * signature = '', signature_image = '', notes_pin = '', emp_tag_id = '"
		 * . $this->db->escape($emp_tag_id) . "',tags_id = '" .
		 * $this->db->escape($tags_id) . "', date_added = '" . $date_added . "',
		 * notes_type = '' where notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql);
		 *
		 * }else{
		 */
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		$emp_first_name = $tag_info ['emp_first_name'];
		$emp_last_name = $tag_info ['emp_last_name'];
		$emp_tag_id = $tag_info ['emp_tag_id'];
		
		$notes_info = $this->getNote ( $notes_id );
		$notes_description = $notes_info ['notes_description'];
		// $facilities_id = $notes_info ['facilities_id'];
		$facilities_id = $tag_info ['facilities_id'];
		$unique_id = $notes_info ['unique_id'];
		
		if ($notes_info ['facilities_id'] == $tag_info ['facilities_id']) {
			$sql = "insert INTO `" . DB_PREFIX . "notes_tags` SET user_id = '', signature = '', signature_image = '', notes_pin = '', emp_tag_id = '" . $this->db->escape ( $emp_tag_id ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "', notes_id = '" . ( int ) $notes_id . "', facilities_id = '" . ( int ) $facilities_id . "', date_added = '" . $date_added . "', unique_id = '" . $unique_id . "', notes_type = '', status_total_time ='" . $tadata ['status_total_time'] . "' ";
			
			$this->db->query ( $sql );
			
			$notes_tags_id = $this->db->getLastId ();
			
			/*
			 * $keyval = $emp_tag_id . ':' . $emp_first_name;
			 *
			 * $pos = strpos ( $notes_description, $keyval );
			 *
			 * if ($pos === false) {
			 * $notes_description2 = $notes_description . ' | ' . $emp_tag_id . ':' . $emp_first_name;
			 * } else {
			 *
			 * $notes_description2 = $notes_description;
			 * }
			 */
			
			$this->load->model ( 'setting/locations' );
			$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
			
			$this->load->model ( 'api/permision' );
			$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id, $tag_info );
			$cname = $clientinfo ['name'];
			
			// $keyval = $emp_tag_id . ':' . $emp_first_name;
			// $keyval = $emp_last_name . ', ' . $emp_first_name .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'];
			
			$keyval = $cname;
			
			$pos = strpos ( $notes_description, $keyval );
			
			if ($pos === false) {
				// $notes_description2 = $notes_description . ' | ' . $emp_tag_id . ':' . $emp_first_name;
				// $notes_description2 = $notes_description . ' | ' . $emp_last_name . ', ' . $emp_first_name .' | '.$tag_info['ssn'].' | '.$location_info['location_name'];
				$notes_description2 = $notes_description . ' ' . $cname;
			} else {
				$notes_description2 = $notes_description;
			}
			/*
			 * if(!empty($tag_info)){
			 * $this->load->model('api/permision');
			 * $clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
			 *
			 * $cname = $clientinfo['name'];
			 * $notes_description2 = $notes_description . ' | ' . $cname;
			 * }else{
			 * $notes_description2 = $notes_description;
			 * }
			 */
			
			// var_dump($notes_description2);
			if ($tadata ['rollcall'] == "" && $tadata ['rollcall'] == null) {
				$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0', emp_tag_id ='1', update_date = '" . $date_added . "' where notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql );
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
				if ($facility ['is_enable_add_notes_by'] == '1') {
					$sql122 = "UPDATE `" . DB_PREFIX . "notes_tags` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
					$this->db->query ( $sql122 );
				}
				if ($facility ['is_enable_add_notes_by'] == '3') {
					$sql13 = "UPDATE `" . DB_PREFIX . "notes_tags` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
					$this->db->query ( $sql13 );
				}
				
				if ($facility ['is_enable_add_notes_by'] == '1') {
					if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
						
						$notes_file = $this->session->data ['local_notes_file'];
						$outputFolder = $this->session->data ['local_image_dir'];
						
						// $facilities_id = $facilities_id;
						
						require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						$this->load->model ( 'notes/notes' );
						$this->model_notes_notes->updateuserpicturenotestag ( $s3file, $notes_id, $notes_tags_id );
						
						if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
							$this->model_notes_notes->updateuserverifiednotestag ( '2', $notes_id, $notes_tags_id );
						}
						
						if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
							$this->model_notes_notes->updateuserverifiednotestag ( '1', $notes_id, $notes_tags_id );
						}
						
						unlink ( $this->session->data ['local_image_dir'] );
						unset ( $this->session->data ['username_confirm'] );
						unset ( $this->session->data ['local_image_dir'] );
						unset ( $this->session->data ['local_image_url'] );
						unset ( $this->session->data ['local_notes_file'] );
					}
				}
			}
			
			if ($this->request->post ['outputFolder'] != null && $this->request->post ['outputFolder'] != null) {
				
				$notes_file = $this->request->post ['face_notes_file'];
				$outputFolder = $this->request->post ['outputFolder'];
				
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicturenotestag ( $s3file, $notes_id, $notes_tags_id );
				
				// $this->model_notes_notes->updateuserverifiednotestag('1', $notes_id, $notes_tags_id);
			}
			
			$this->load->model ( 'activity/activity' );
			$adata ['notes_id'] = $notes_id;
			$adata ['notes_description'] = $notes_description2;
			$adata ['emp_tag_id'] = $emp_tag_id;
			$adata ['tags_id'] = $tags_id;
			$adata ['phone_device_id'] = $data ['phone_device_id'];
			$adata ['is_android'] = $data ['is_android'];
			$adata ['facilities_id'] = $facilities_id;
			$adata ['date_added'] = $date_added;
			$adata ['notes_tags_id'] = $notes_tags_id;
			$this->model_activity_activity->addActivitySave ( 'updateNotesTag22', $adata, 'query' );
		}
		
		return $notes_tags_id;
		
		/*
		 * $queryf = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms
		 * WHERE notes_id = '" . $notes_id . "' ");
		 *
		 * if($queryf->num_rows > 0){
		 * $sqlf = "UPDATE `" . DB_PREFIX . "notes` SET tags_id = '" .
		 * $this->db->escape($tags_id) . "' where notes_id = '" . (int)$notes_id
		 * . "' ";
		 * $this->db->query($sqlf);
		 * }
		 */
		
		/* } */
	}
	public function updateNotesTag($emp_tag_id, $notes_id, $tags_id, $date_added, $tadata) {
		// $sql = "UPDATE `" . DB_PREFIX . "notes` SET emp_tag_id =
		// '".$emp_tag_id."', tags_id = '".$tags_id."', update_date =
		// '".$update_date."' WHERE notes_id = '" . (int)$notes_id . "' ";
		// $this->db->query($sql);
		
		/*
		 * $sql1 = "SELECT * FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id =
		 * '" . (int)$notes_id . "' ";
		 * $query = $this->db->query($sql1);
		 *
		 * if($query->num_rows > 0){
		 * $sql = "UPDATE `" . DB_PREFIX . "notes_tags` SET user_id = '',
		 * signature = '', signature_image = '', notes_pin = '', emp_tag_id = '"
		 * . $this->db->escape($emp_tag_id) . "',tags_id = '" .
		 * $this->db->escape($tags_id) . "', date_added = '" . $date_added . "',
		 * notes_type = '' where notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql);
		 *
		 * }else{
		 */
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		$emp_first_name = $tag_info ['emp_first_name'];
		$emp_last_name = $tag_info ['emp_last_name'];
		$emp_tag_id = $tag_info ['emp_tag_id'];
		
		$notes_info = $this->getNote ( $notes_id );
		$notes_description = $notes_info ['notes_description'];
		// $facilities_id = $notes_info ['facilities_id'];
		$facilities_id = $tag_info ['facilities_id'];
		$unique_id = $notes_info ['unique_id'];
		
		// if($notes_info ['facilities_id'] == $tag_info ['facilities_id']){
			
		$sql1 = "SELECT * FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . (int)$notes_id . "' and tags_id = '" . (int)$tags_id . "' and facilities_id = '" . ( int ) $facilities_id . "' ";
		$query = $this->db->query($sql1);
		 
		if($query->num_rows == 0){
			$sql = "insert INTO `" . DB_PREFIX . "notes_tags` SET user_id = '', signature = '', signature_image = '', notes_pin = '', emp_tag_id = '" . $this->db->escape ( $emp_tag_id ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "', notes_id = '" . ( int ) $notes_id . "', facilities_id = '" . ( int ) $facilities_id . "', date_added = '" . $date_added . "', unique_id = '" . $unique_id . "', notes_type = '', status_total_time ='" . $tadata ['status_total_time'] . "', manual_movement ='" . $tadata ['manual_movement'] . "',comments = '" . $this->db->escape ($tadata ['substatus_idscomment']) . "',fixed_status_id = '" . (int)$tadata ['fixed_status_id'] . "' ";
			
			$this->db->query ( $sql );
			
			$notes_tags_id = $this->db->getLastId ();
			
		}
		
		/*
		 * $keyval = $emp_tag_id . ':' . $emp_first_name;
		 *
		 * $pos = strpos ( $notes_description, $keyval );
		 *
		 * if ($pos === false) {
		 * $notes_description2 = $notes_description . ' | ' . $emp_tag_id . ':' . $emp_first_name;
		 * } else {
		 *
		 * $notes_description2 = $notes_description;
		 * }
		 */
		
		$this->load->model ( 'setting/locations' );
		$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
		
		$this->load->model ( 'api/permision' );
		$clientinfo = $this->model_api_permision->getclientinfo ( $facilities_id, $tag_info );
		$cname = $clientinfo ['name'];
		
		// $keyval = $emp_tag_id . ':' . $emp_first_name;
		// $keyval = $emp_last_name . ', ' . $emp_first_name .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'];
		
		$keyval = $cname;
		
		$pos = strpos ( $notes_description, $keyval );
		
		if ($pos === false) {
			// $notes_description2 = $notes_description . ' | ' . $emp_tag_id . ':' . $emp_first_name;
			// $notes_description2 = $notes_description . ' | ' . $emp_last_name . ', ' . $emp_first_name .' | '.$tag_info['ssn'].' | '.$location_info['location_name'];
			$notes_description2 = $notes_description . ' ' . $cname;
		} else {
			$notes_description2 = $notes_description;
		}
		/*
		 * if(!empty($tag_info)){
		 * $this->load->model('api/permision');
		 * $clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
		 *
		 * $cname = $clientinfo['name'];
		 * $notes_description2 = $notes_description . ' | ' . $cname;
		 * }else{
		 * $notes_description2 = $notes_description;
		 * }
		 */
		
		// var_dump($notes_description2);
		if ($tadata ['rollcall'] == "" && $tadata ['rollcall'] == null) {
			$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0', emp_tag_id ='1', update_date = '" . $date_added . "' where notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql );
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes_tags` SET notes_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes_tags` SET notes_type = '5' WHERE notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					
					// $facilities_id = $facilities_id;
					
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					$this->model_notes_notes->updateuserpicturenotestag ( $s3file, $notes_id, $notes_tags_id );
					
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverifiednotestag ( '2', $notes_id, $notes_tags_id );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverifiednotestag ( '1', $notes_id, $notes_tags_id );
					}
					
					unlink ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['username_confirm'] );
					unset ( $this->session->data ['local_image_dir'] );
					unset ( $this->session->data ['local_image_url'] );
					unset ( $this->session->data ['local_notes_file'] );
				}
			}
		}
		
		if ($this->request->post ['outputFolder'] != null && $this->request->post ['outputFolder'] != null) {
			
			$notes_file = $this->request->post ['face_notes_file'];
			$outputFolder = $this->request->post ['outputFolder'];
			
			require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
			$this->load->model ( 'notes/notes' );
			$this->model_notes_notes->updateuserpicturenotestag ( $s3file, $notes_id, $notes_tags_id );
			
			// $this->model_notes_notes->updateuserverifiednotestag('1', $notes_id, $notes_tags_id);
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$adata ['emp_tag_id'] = $emp_tag_id;
		$adata ['tags_id'] = $tags_id;
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['facilities_id'] = $facilities_id;
		$adata ['date_added'] = $date_added;
		$adata ['notes_tags_id'] = $notes_tags_id;
		$this->model_activity_activity->addActivitySave ( 'updateNotesTag', $adata, 'query' );
		
		// }
		
		return $notes_tags_id;
		
		/*
		 * $queryf = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms
		 * WHERE notes_id = '" . $notes_id . "' ");
		 *
		 * if($queryf->num_rows > 0){
		 * $sqlf = "UPDATE `" . DB_PREFIX . "notes` SET tags_id = '" .
		 * $this->db->escape($tags_id) . "' where notes_id = '" . (int)$notes_id
		 * . "' ";
		 * $this->db->query($sqlf);
		 * }
		 */
		
		/* } */
	}
	public function getnoteskeywors($notes_id) {
		$notes_keywords = array ();
		$this->load->model ( 'notes/image' );
		
		$keyword_image = "";
		
		$sql = "SELECT notes_by_keyword_id,notes_id,keyword_id,keyword_name,keyword_file,keyword_file_url,keyword_status,active_tag,facilities_id,date_added,is_monitor_time,user_id,override_monitor_time_user_id FROM `" . DB_PREFIX . "notes_by_keyword` WHERE notes_id = '" . ( int ) $notes_id . "' and keyword_status = '1' and comment_id = '0' ";
		
		$query = $this->db->query ( $sql );
		
		foreach ( $query->rows as $result ) {
			
			$sql1 = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $result ['keyword_file'] . "' and FIND_IN_SET('" . $result ['facilities_id'] . "', facilities_id) ";
			$queryd = $this->db->query ( $sql1 );
			
			$keyword_info = $queryd->row;
			
			$image = "";
			if ($keyword_info ['keyword_image'] != null && $keyword_info ['keyword_image'] != "") {
				
				// $image = $this->model_notes_image->resize('icon/'.$keyword_info['keyword_image'], 54, 54);
				// $image = HTTP_SERVER . 'image/icon/' . $keyword_info['keyword_image'];
				
				$keyword_image = $keyword_info ['keyword_image'];
				$image = $keyword_info ['keyword_image'];
			} else {
				
				$sql1 = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $result ['keyword_id'] . "'";
				$queryd = $this->db->query ( $sql1 );
				
				$keyword_info = $queryd->row;
				
				if ($keyword_info ['keyword_image'] != null && $keyword_info ['keyword_image'] != "") {
					// $image = $this->model_notes_image->resize('icon/'.$keyword_info['keyword_image'], 54, 54);
					// $image = HTTP_SERVER . 'image/icon/' . $keyword_info['keyword_image'];
					
					$keyword_image = $keyword_info ['keyword_image'];
					$image = $keyword_info ['keyword_image'];
				}
			}
			
			if ($keyword_info ['keyword_name'] != null && $keyword_info ['keyword_name'] != "") {
				$notes_keywords [] = array (
						'notes_by_keyword_id' => $result ['notes_by_keyword_id'],
						'notes_id' => $result ['notes_id'],
						'keyword_id' => $keyword_info ['keyword_id'],
						'keyword_name' => $keyword_info ['keyword_name'],
						'keyword_file' => $keyword_info ['keyword_file'],
						'keyword_image' => $keyword_image,
						'keyword_file_url' => $image,
						'keyword_status' => $result ['keyword_status'],
						'active_tag' => $result ['active_tag'],
						'facilities_id' => $result ['facilities_id'],
						'date_added' => $result ['date_added'],
						'is_monitor_time' => $result ['is_monitor_time'],
						'user_id' => $result ['user_id'],
						'override_monitor_time_user_id' => $result ['override_monitor_time_user_id'] 
				);
			}
		}
		
		return $notes_keywords;
	}
	public function addactiveNote($data, $notes_id) {
		if ($data ['keyword_file'] != null && $data ['keyword_file'] != "") {
			$this->load->model ( 'setting/image' );
			
			$keywords = explode ( ",", $data ['keyword_file'] );
			
			foreach ( $keywords as $keyword ) {
				
				/*
				 * $file16 = 'icon/'.$keyword;
				 *
				 * $newfile84 = $this->model_setting_image->resize($file16, 50,
				 * 50);
				 * $newfile216 = DIR_IMAGE . $newfile84;
				 * $file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
				 * $imageData132 =
				 * base64_encode(file_get_contents($newfile216));
				 *
				 * if($newfile84 != null && $newfile84 != ""){
				 * $keyword_icon =
				 * 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
				 * }else{
				 * $keyword_icon = '';
				 * }
				 */
				$keyword_icon = '';
				
				$keyword_file = $keyword;
				
				$notes_info = $this->getNote ( $notes_id );
				$notes_description = $notes_info ['notes_description'];
				$facilities_id = $notes_info ['facilities_id'];
				$date_added = $notes_info ['date_added'];
				$unique_id = $notes_info ['unique_id'];
				
				$this->load->model ( 'setting/keywords' );
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $keyword, $facilities_id );
				// var_dump($keywordData2);
				
				$notes_description222 .= $keywordData2 ['keyword_name'] . ' ';
				
				// $notes_description2 =
				// str_replace($keywordData2['keyword_name'],$keywordData2['keyword_name'],$data['notes_description']);
				
				// $notes_description2 = $keywordData2['keyword_name'];
				
				$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "', keyword_id = '" . $this->db->escape ( $keywordData2 ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $keywordData2 ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $keyword_file ) . "', keyword_file_url = '" . $this->db->escape ( $keyword_icon ) . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $date_added ) . "', unique_id = '" . $this->db->escape ( $unique_id ) . "', keyword_status = '1' ";
				$this->db->query ( $sqlm );
				
				if ($keywordData2 ['monitor_time'] == '11') {
					$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET review_notes = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
					$this->db->query ( $sql12 );
				}
			}
			
			if ($notes_id > 0) {
				$notes_description2 = $notes_description222 . ' ' . $data ['notes_description'];
				
				$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0',keyword_file = '1' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql1 );
			}
			
			$this->load->model ( 'activity/activity' );
			$adata ['notes_id'] = $notes_id;
			$adata ['notes_description'] = $notes_description2;
			$adata ['keyword_id'] = $keywordData2 ['keyword_id'];
			$adata ['keyword_name'] = $keywordData2 ['keyword_name'];
			$adata ['phone_device_id'] = $data ['phone_device_id'];
			$adata ['is_android'] = $data ['is_android'];
			$adata ['facilities_id'] = $facilities_id;
			$adata ['date_added'] = $date_added;
			$this->model_activity_activity->addActivitySave ( 'addactiveNote', $adata, 'query' );
		}
	}
	public function getajaxnote($notes_id) {
		$html = "";
		if (is_array ( $notes_id )) {
			
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'notes/image' );
			$this->load->model ( 'setting/highlighter' );
			$this->load->model ( 'user/user' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'facilities/facilities' );
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'notes/tags' );
			$this->load->model ( 'notes/notescomment' );
			
			$config_tag_status = $this->customer->isTag ();
			$this->data ['config_tag_status'] = $this->customer->isTag ();
			
			$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
			$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
			$this->data ['config_rules_status'] = $this->customer->isRule ();
			$this->data ['config_share_notes'] = $this->customer->isNotesShare ();
			$this->data ['config_multiple_activenote'] = $this->customer->isMactivenote ();
			
			$custom_form_form_url = $this->url->link ( 'form/form', '' . $url, 'SSL' );
			
			$custom_printform_form_url = $this->url->link ( 'form/form/printform', '' . $url, 'SSL' );
			$form_url = $this->url->link ( 'notes/noteform/forminsert', '' . $url, 'SSL' );
			$check_list_form_url = $this->url->link ( 'notes/createtask/noteschecklistform', '' . $url, 'SSL' );
			
			$customIntake_url = $this->url->link ( 'notes/tags/updateclient', '' . $url2, 'SSL' );
			$censusdetail_url = $this->url->link ( 'resident/dailycensus/censusdetail', '' . $url2, 'SSL' );
			
			$medication_url = $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' );
			
			$update_medication_url = $this->url->link ( 'notes/common/authorization&addmedication=1', '' . $url2, 'SSL' );
			
			$bedcheck_url = $this->url->link ( 'notes/printbedcheck&is_bedchk=1', '' . $url2, 'SSL' );
			
			$assignteam_url = $this->url->link ( 'resident/assignteam', '' . $url2, 'SSL' );
			
			$approval_url = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
			
			$routemap_url = $this->url->link ( 'notes/routemap', '' . $url2, 'SSL' );
			
			$discharge_href = $this->url->link ( 'notes/case', '' . $url2, 'SSL' );
			
			$inventory_check_in_url = $this->url->link ( 'notes/addInventory/CheckInInventoryForm', '' . $url2, 'SSL' );
			
			$inventory_check_out_url = $this->url->link ( 'notes/addInventory/CheckOutInventoryForm', '' . $url2, 'SSL' );
			
			$bedcheckurl = $this->url->link ( 'resident/resident/movementreport', '' . $url2, 'SSL' );
			
			$add_case_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/viewcase&addcase=1', '', 'SSL' ) );
			
			$this->load->model ( 'facilities/facilities' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url .= '&update_notetime=1';
				// $update_note_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url, 'SSL' ) );
			} else {
				// $update_note_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updatenotetime', '' . $url, 'SSL' ) );
			}
			
			$update_note_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updatenotetimecomment', '' . $url, 'SSL' ) );
			
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize ( $customer_info ['client_info_notes'] );
			$customers = unserialize ( $customer_info ['setting_data'] );
			
			if ($customers ['date_format'] != null && $customers ['date_format'] != "") {
				$date_format = $customers ['date_format'];
			} else {
				$date_format = $this->language->get ( 'date_format_short_2' );
			}
			
			if ($customers ['time_format'] != null && $customers ['time_format'] != "") {
				$time_format = $customers ['time_format'];
			} else {
				$time_format = 'h:i A';
			}
			
			$notes_id2 = $notes_id;
			foreach ( $notes_id2 as $notes_row ) {
				$result = $this->model_notes_notes->getnotes ( $notes_row );
				$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				// $this->data['is_master_facility'] = $facilityinfo['is_master_facility'] ;
				if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
					$is_master_facility = '1';
				} else {
					$is_master_facility = '2';
				}
				
				if ($result) {
					
					// echo '<pre>'; print_r($result); echo '</pre>'; die;
					
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
									'media_date_added' => date ( $date_format, strtotime ( $image ['media_date_added'] ) ),
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
						$task_time = date ( $time_format, strtotime ( $result ['task_time'] ) );
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
							$this->load->model ( 'form/form' );
							foreach ( $allforms as $allform ) {
								$formdata_i = $this->model_form_form->getFormDatadesign ( $allform ['custom_form_type'] );
								$forms [] = array (
										'form_type_id' => $allform ['form_type_id'],
										'form_design_type' => $formdata_i ['form_type'],
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
										'form_date_added' => date ( $date_format, strtotime ( $allform ['form_date_added'] ) ) 
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
							
							if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
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
									// 'medication_attach_url' => $alltask['medication_attach_url'],
									'medication_attach_url' => $medication_attach_url,
									'date_added' => date ( $date_format, strtotime ( $alltask ['date_added'] ) ),
									'room_current_date_time' => date ( $time_format, strtotime ( $alltask ['room_current_date_time'] ) ),
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
								$taskTime = date ( $time_format, strtotime ( $alltmask ['task_time'] ) );
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
									'media_url' => $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' ),
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
									'date_added' => date ( $date_format, strtotime ( $alltmask ['date_added'] ) ) 
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
						$original_task_time = date ( $time_format, strtotime ( $result ['original_task_time'] ) );
					} else {
						$original_task_time = "";
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
					
					$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'], $result ['facilities_id'] );
					
					$uptimes = array ();
					$uptimes = $this->model_notes_notes->getupdatetime ( $result ['notes_id'] );
					
					$case_file_id = '';
					$tags_id = '';
					
					if ($result ['notes_id'] != '') {
						$cdata ['notes_id'] = $result ['notes_id'];
						$this->load->model ( 'resident/casefile' );
						$case_info = $this->model_resident_casefile->getcasefilesbynotesid ( $cdata );
						$case_file_id = $case_info ['notes_by_case_file_id'];
						$tags_id = $case_info ['tags_ids'];
						$case_number = $case_info ['case_number'];
					} else {
						$case_file_id = '';
						$tags_id = '';
						$case_number = '';
					}
					
					$note = array (
							'notes_id' => $result ['notes_id'],
							'shift_color_value' => $shift_time_color ['shift_color_value'],
							'is_comment' => $result ['is_comment'],
							'in_total' => $result ['in_total'],
							'case_file_id' => $case_file_id,
							'tags_id' => $tags_id,
							'case_number' => $case_number,
							'out_total' => $result ['out_total'],
							'manual_total' => $result ['manual_total'],
							'printtranscript' => $printtranscript,
							'uptimes' => $uptimes,
							'notescomments' => $notescomments,
							'ooout' => $ooout,
							'is_user_face' => $result ['is_user_face'],
							'is_approval_required_forms_id' => $result ['is_approval_required_forms_id'],
							// 'user_file' => $result['user_file'],
							'user_file' => $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result ['notes_id'], 'SSL' ),
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
							'notetime' => date ( $time_format, strtotime ( $result ['notetime'] ) ),
							'username' => $result ['user_id'],
							'notes_pin' => $userPin,
							'signature' => $result ['signature'],
							'text_color_cut' => $result ['text_color_cut'],
							'text_color' => $result ['text_color'],
							'note_date' => date ( $date_format, strtotime ( $result ['note_date'] ) ),
							'status' => ($result ['status'] ? $this->language->get ( 'text_enabled' ) : $this->language->get ( 'text_disabled' )),
							'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
							'strike_user_name' => $result ['strike_user_id'],
							'strike_pin' => $result ['strike_pin'],
							'strike_signature' => $result ['strike_signature'],
							'strike_date_added' => date ( $date_format, strtotime ( $result ['strike_date_added'] ) ),
							'reminder_time' => $reminder_time,
							'reminder_title' => $reminder_title,
							'facilityname' => $facilityname,
							'href' => $this->url->link ( 'notes/notes/insert', '' . '&reset=1&searchdate=' . date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ) . $url, 'SSL' ) 
					);
				}
				
				$html .= '<tr class="" id="note_' . $note ['notes_id'] . '">';
				$html .= '<div id="player' . $note ['notes_id'] . '">';
				$html .= '<div id="jquery_jplayer' . $note ['notes_id'] . '"></div>';
				$html .= '</div>';
				
				/*
				 * if($note['remdata'] == '1'){
				 *
				 * $html.='<';
				 * $html.='script>';
				 * $html.='$(document).ready(function() {';
				 * $html.=' (function poll() {';
				 * $html.='setTimeout(function() {';
				 * $html.='$.ajax({';
				 * $html.='url:
				 * "index.php?route=notes/notes/getReminderTime&notes_id='.$note['notes_id'].'",';
				 * $html.='type: "GET",';
				 * $html.='success: function(data) {';
				 *
				 * $html.="if(data.checkTime == '1'){";
				 * $html.='$(\'<audio id="chatAudio'.$note['notes_id'].'"><source
				 * src="\'+tmpTone+\'"
				 * type="audio/mpeg"></audio>\').appendTo(\'body\');';
				 * $html.='$(\'#chatAudio'.$note['notes_id'].'\')[0].play();';
				 *
				 * $html.="$.colorbox({innerWidth:450,innerHeight:345,html:'<iframe
				 * width=450 height=325
				 * src=index.php?route=notes/notes/getReminderPopup&notes_id=".$note['notes_id']."
				 * frameborder=0 allowfullscreen></iframe>'});";
				 * $html.='}';
				 * $html.='},';
				 * $html.='dataType: "json",';
				 * $html.='complete: poll,';
				 * $html.='timeout: 5000';
				 * $html.='}) ';
				 * $html.='}, 10000); ';
				 * $html.=' })();';
				 * $html.='});';
				 *
				 * $html.='<\/script>' ;
				 *
				 *
				 * }
				 */
				
				$html .= '<td class="center" style="width: 80px;height:30px;vertical-align: middle;">';
				
				if ($note ['shift_color_value']) {
					$display_color = "";
					$shift_color = "color :" . $note ['shift_color_value'];
				} else {
					$display_color = "display:none";
					$shift_color = "";
				}
				
				if ($note ['form_type'] == '7') {
					$notetime = date ( $time_format, strtotime ( $note ['uptimes'] ['notetime'] ) );
				} else {
					$notetime = $note ['notetime'];
				}
				
				$html .= '<span><b style="' . $shift_color . ';' . $display_color . '">|</b>';
				$html .= '<input type="text" name="notetime" id="notetime_picker_' . $note ['notes_id'] . '" class="form-control note_time_11 notetime_picker" value="' . $notetime . '" style="color: #000; text-align: center; background: transparent;margin-left: -15px!important;">';
				
				if ($time_format == "H:i") {
					// $html .= '<';
					// $html .= 'script src="sites/view/javascript/mddatetimepicker/mdtimepicker.min2.js"><\/script>';
					// $html .= '<link rel="stylesheet" href="sites/view/javascript/mddatetimepicker/mdtimepicker.min2.css">';
					$html .= '<';
					$html .= 'script>';
					
					$html .= '$("#notetime_picker_' . $note ['notes_id'] . '").mdtimepicker({ readOnly: false,is24hour: true }).on("timechanged", function(e){';
					$html .= '$.colorbox({iframe:true, width:"70%", height:"70%",href:"' . $update_note_url . '&notes_id=' . $note ['notes_id'] . '&notetime="+e.time});';
					$html .= '});';
					
					$html .= '<\/script>';
				} else {
					$html .= '<';
					$html .= 'script>';
					
					$html .= '$("#notetime_picker_' . $note ['notes_id'] . '").mdtimepicker().on("timechanged", function(e){';
					$html .= '$.colorbox({iframe:true, width:"70%", height:"70%",href:"' . $update_note_url . '&notes_id=' . $note ['notes_id'] . '&notetime="+e.time});';
					$html .= '});';
					
					$html .= '<\/script>';
				}
				
				if ($note ['form_type'] == '7') {
					$html .= '<span style="color: red;">' . $note ['notetime'] . '</span>';
				}
				
				$html .= '</span>';
				
				$html .= '</td>';
				
				$bgColor = "";
				$bgColor2 = "";
				$bgColor3 = "";
				if ($note ['highlighter_value'] != null && $note ['highlighter_value'] != "") {
					$bgColor3 .= 'background-color:' . $note ['highlighter_value'] . ';';
				}
				if ($note ['text_color_cut'] == "1") {
					$bgColor2 .= 'text-decoration: line-through;';
				}
				
				if ($note ['text_color'] != null && $note ['text_color'] != "") {
					$bgColor .= 'color:' . $note ['text_color'] . ';';
				}
				
				if (($note ['highlighter_value'] != null && $note ['highlighter_value'] != "") && ($note ['text_color'] == null && $note ['text_color'] == "")) {
					if ($note ['highlighter_value'] == '#ffff00') {
						$bgColor3 .= 'color:#000;';
					} else if ($note ['highlighter_value'] == '#ffffff') {
						$bgColor3 .= 'color:#666;';
					} else {
						$bgColor3 .= 'color:#FFF;';
					}
				}
				
				$html .= '<td class="left border_right" style="padding-top:7px;padding-bottom:7px;height:30px;">';
				$html .= '<div id="notes_text' . $note ['notes_id'] . '" class="_ta2" style=" border: none;">';
				$html .= '<div class="div_hr" style="' . $bgColor3 . '">';
				
				if ($note ['visitor_log'] == "1") {
					$html .= '<img src="sites/view/digitalnotebook/image/Visitor-Icons.png" width="35px" height="35px">';
				}
				
				if ($note ['form_type'] == "7") {
					$html .= '<img src="sites/view/digitalnotebook/image/late.png" width="30px" height="30px">';
				}
				if ($note ['form_type'] == "9") {
					$html .= '<img src="sites/view/digitalnotebook/image/case-54-54.png" width="30px" height="30px">';
				}
				
				if ($note ['visitor_log'] == "2") {
					$html .= '<img src="sites/view/digitalnotebook/image/Visitor-Icons-grey.jpg" width="35px" height="35px">	';
				}
				
				if ($note ['text_color_cut'] == "0") {
					if ($note ['tag_privacy'] == "2") {
						if ($this->session->data ['unloack_success'] == '1') {
							
							$oncl = "onclick=\"selectText(\'" . $note ['notes_id'] . "\',\'" . $note ['taskadded'] . "\',\'" . $note ['tag_privacy'] . "\')\" ";
						}
					} else {
						$oncl = "onclick=\"selectText(\'" . $note ['notes_id'] . "\',\'" . $note ['taskadded'] . "\',\'" . $note ['tag_privacy'] . "\')\" ";
					}
				}
				
				$html .= '<span ' . $oncl . ' style="line-height: 2;' . $bgColor . '' . $bgColor2 . '" data-autoresize' . $note ['notes_id'] . ' rows="1" cols="5" id="notes_description' . $note ['notes_id'] . '" class="form-control1 notes_description_cl_list " >';
				
				if ($is_master_facility == "1") {
					$html .= $note ['facilityname'] . ' | ';
				}
				
				if ($note ['form_type'] == '5') {
					
					$html .= '<img src="sites/view/digitalnotebook/image/Inventroy-Check-out.png" width="35px" height="35px" style=" ' . $csspadding . '">';
				}
				
				if ($note ['form_type'] == '6') {
					
					$html .= '<img src="sites/view/digitalnotebook/image/Inventroy-Check-in.png" width="35px" height="35px" style=" ' . $csspadding . '">';
				}
				
				if ($note ['generate_report'] == "2") {
					$html .= '<img src="sites/view/digitalnotebook/image/generate-Report.png" width="45px" height="45px">';
				}
				
				if ($note ['generate_report'] == "3") {
					$html .= '<img src="sites/view/digitalnotebook/image/generate-Report.png" width="45px" height="45px">';
				}
				
				if ($note ['is_census'] == "1") {
					$html .= '<img src="sites/view/digitalnotebook/image/census.png" width="45px" height="45px">';
				}
				
				if ($note ['is_offline'] == "1") {
					$html .= '<img src="sites/view/digitalnotebook/image/wifi.png" width="45px" height="45px">';
				}
				
				if ($note ['checklist_status'] == "1") {
					
					if ($note ['taskadded'] == "2") {
						$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
					}
					
					if ($note ['taskadded'] == "3") {
						$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
					}
					
					if ($note ['taskadded'] == "4") {
						$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Missed Task:  ';
					}
				} elseif ($note ['checklist_status'] == "2") {
					$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px">';
				} else {
					
					if ($note ['taskadded'] == "1") {
						$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Deleted:  ';
					}
					
					if ($note ['taskadded'] == "2") {
						$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
					}
					
					if ($note ['taskadded'] == "3") {
						$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
					}
					
					if ($note ['taskadded'] == "4") {
						$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Missed Task:   ';
					}
				}
				
				if ($note ['noteskeywords']) {
					foreach ( $note ['noteskeywords'] as $noteskeyword ) {
						
						$html .= '<img src="' . $noteskeyword ['keyword_file_url'] . ' " width="35px" height="35px">';
					}
				}
				
				/*
				 * if($note['task_type'] == "1"){
				 * $html.='Bed Check for ';
				 * }
				 */
				
				/*
				 * if($note['task_type'] == "2"){
				 * $html.='Medications given at ';
				 * }
				 */
				
				if ($note ['task_type'] != "1" && $note ['task_type'] != "2" && $note ['task_type'] != "4" && $note ['task_type'] != "5") {
					if ($note ['task_time'] != null && $note ['task_time'] != "") {
						$html .= $note ['task_time'];
					}
				}
				
				/*
				 * if($note['task_type'] == "2"){
				 * $html.=' to the following Resident: ';
				 * }
				 */
				/*
				 * if($note['task_type'] == "1"){
				 * $html.='Completed. The following details were noted: ';
				 * }
				 */
				
				/*
				 * if($note['assign_to'] != null && $note['assign_to'] != ""){
				 * <b>Assign To:</b> echo $note['assign_to'];
				 * }
				 */
				
				/*
				 * if($note['review_notes'] == "1"){
				 * <img src="sites/view/digitalnotebook/image/rev.png" width="35px"
				 * height="35px">
				 * }
				 */
				
				$html .= str_replace ( array (
						"\r",
						"\n" 
				), array (
						"<br>",
						"" 
				), $note ['notes_description'] );
				// $html.= nl2br($note['notes_description']);
				
				if ($note ['notesmedicationtasks'] != null && $note ['notesmedicationtasks'] != "") {
					
					foreach ( $note ['notesmedicationtasks'] as $notesmedicationtask ) {
						
						if ($note ['tag_privacy'] == "2") {
							if ($this->session->data ['unloack_success'] == '1') {
								$html .= '<br>  ';
								// $html.='<br> '.$notesmedicationtask['task_content'].'
								// | '.$notesmedicationtask['drug_name'].' | ';
								
								if ($notesmedicationtask ['refuse'] == 1) {
									$html .= 'Refused Medicine: ';
								}
								$html .= $notesmedicationtask ['drug_name'] . ' ';
								
								if ($notesmedicationtask ['signature'] != null && $notesmedicationtask ['signature'] != "") {
									// $html.='<img width="98px" height="29px"
									// src="'.$notesmedicationtask['signature'].'"> |
									// Given at '.$notesmedicationtask['task_time'].' by
									// '.$note['user_id'].'';
									$html .= ' | <img width="98px" height="29px" src="' . $notesmedicationtask ['signature'] . '"> ';
								}
								
								if ($notesmedicationtask ['medication_file_upload'] == "0") {
									if ($notesmedicationtask ['media_url'] != null && $notesmedicationtask ['media_url'] != "") {
										$html .= '<a target="_blank" class="" href="' . $notesmedicationtask ['media_url'] . '">';
										$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
										$html .= '</a>';
									}
								}
							} else {
							}
						} else {
							$html .= '<br>  ';
							// $html.='<br> '.$notesmedicationtask['task_content'].' |
							// '.$notesmedicationtask['drug_name'].' | ';
							
							$html .= $notesmedicationtask ['drug_name'] . ' ';
							
							if ($notesmedicationtask ['signature'] != null && $notesmedicationtask ['signature'] != "") {
								// $html.='<img width="98px" height="29px"
								// src="'.$notesmedicationtask['signature'].'"> | Given
								// at '.$notesmedicationtask['task_time'].' by
								// '.$note['user_id'].'';
								$html .= ' | <img width="98px" height="29px" src="' . $notesmedicationtask ['signature'] . '"> ';
							}
							
							if ($notesmedicationtask ['medication_file_upload'] == "0") {
								if ($notesmedicationtask ['media_url'] != null && $notesmedicationtask ['media_url'] != "") {
									$html .= '<a target="_blank" class="" href="' . $notesmedicationtask ['media_url'] . '">';
									$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
									$html .= '</a>';
								}
							}
						}
					}
					$html .= '<br>';
					$html .= 'Completed by ';
				}
				
				if ($note ['notestasks'] != null && $note ['notestasks'] != "") {
					
					if ($note ['grandtotal'] != null && $note ['grandtotal'] != "0") {
						$html .= '<br>IN - Total :' . $note ['grandtotal'] . '';
					}
					foreach ( $note ['notestasks'] as $notestask ) {
						$html .= ' <br> ' . $notestask ['location_name'] . ' | ' . date ( 'h:i A', strtotime ( $notestask ['task_time'] ) ) . ' | ' . $notestask ['tags_ids_names'];
						
						if ($notestask ['task_comments'] != null && $notestask ['task_comments'] != "") {
							$html .= $notestask ['task_comments'];
						}
						
						// $html.='<br>Total Clients IN : '.$notestask['capacity'].'';
						if ($notestask ['medication_attach_url'] != null && $notestask ['medication_attach_url'] != "") {
							
							$html .= '<a target="_blank" class="" href="' . $notestask ['medication_attach_url'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="30px" height="30px" alt="" />';
							$html .= '</a>';
						}
					}
					
					if ($note ['ooout'] == "1") {
						$html .= '<br>OUT ';
					}
					
					foreach ( $note ['notestasks'] as $notestask ) {
						
						if ($notestask ['out_tags_ids_names'] != null && $notestask ['out_tags_ids_names'] != "") {
							$html .= ' <br> ' . $notestask ['out_tags_ids_names'];
							
							if ($notestask ['task_comments'] != null && $notestask ['task_comments'] != "") {
								$html .= $notestask ['task_comments'];
							}
						}
						
						// $html.='<br>Total Clients IN : '.$notestask['capacity'].'';
						if ($notestask ['out_tags_ids_names'] != null && $notestask ['out_tags_ids_names'] != "") {
							if ($notestask ['medication_attach_url'] != null && $notestask ['medication_attach_url'] != "") {
								$html .= '<a target="_blank" class="" href="' . $notestask ['medication_attach_url'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="30px" height="30px" alt="" />';
								$html .= '</a>';
							}
						}
					}
					
					/*
					 * if($note['boytotals'][0] != null && $note['boytotals'][0] != ""){
					 * $html.='<br>Total '.$note['boytotals'][0]['loc_name'].':
					 * '.$note['boytotals'][0]['total'].'';
					 *
					 * }
					 * if($note['girltotals'][0] != null && $note['girltotals'][0] !=
					 * ""){
					 * $html.='<br>Total '.$note['girltotals'][0]['loc_name'].':
					 * '.$note['girltotals'][0]['total'].'';
					 *
					 * }
					 * if($note['generaltotals'][0] != null && $note['generaltotals'][0]
					 * != ""){
					 * $html.='<br>Total '.$note['generaltotals'][0]['loc_name'].':
					 * '.$note['generaltotals'][0]['total'].'';
					 *
					 * }
					 */
					/*
					 * if($note['grandtotal'] != null && $note['grandtotal'] != "0"){
					 * $html.='<br>Grand Total number of Clients IN :'.
					 * $note['grandtotal'].'';
					 * }
					 */
				}
				
				if ($config_tag_status == '1') {
					
					if ($note ['tag_privacy'] == "2") {
						if ($this->session->data ['unloack_success'] == '1') {
							$html .= '<img src="sites/view/digitalnotebook/image/web140x40.png" width="35px" height="35px">';
						} else {
							$html .= '<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
						}
					}
				}
				
				$html .= '</span> ';
				$html .= '<span class="user_deatil" > ';
				/*
				 * if ($note['is_private'] == '1') {
				 * $html .= $note['username'];
				 * $html .= '<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
				 * $html .= '( ' . $note['note_date'] . ' )';
				 * } else {
				 */
				
				// if ($note ['username'] != null && $note ['username'] != "0") {
				$html .= $note ['username'];
				
				if ($note ['notes_type'] == "2") {
					$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
				} elseif ($note ['notes_type'] == "1") {
					
					$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
				} elseif ($note ['username'] == SYSTEM_GENERATED) {
					
					$html .= '<img src="sites/view/digitalnotebook/image/Logo-36x36.png" width="35px" height="35px">';
				} elseif ($note ['notes_pin'] != null && $note ['notes_pin'] != "") {
					
					$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
				} else {
					if ($note ['signature'] != null && $note ['signature'] != "") {
						$html .= '<img src=" ' . $note ['signature'] . ' " width="98px" height="29px">';
					}
					
					if ($note ['notes_type'] == "5") {
						$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
					}
				}
				
				$html .= '( ' . $note ['note_date'] . ' )';
				// }
				// }
				
				if ($note ['is_comment'] == "2") {
					$html .= '<a target="_blank" href="' . $note ['printtranscript'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/transcript.png" width="35px" height="35px">';
					$html .= '</a>';
				}
				
				/*
				 * if ($note['is_private_strike'] == '1') {
				 * $html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
				 * $html .= $note['strike_user_name'];
				 * $html .= ' <img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
				 * $html .= '( ' . $note['strike_date_added'] . ' )';
				 * } else {
				 */
				
				if ($note ['strike_user_name'] != null && $note ['strike_user_name'] != "0") {
					$html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
					$html .= $note ['strike_user_name'];
					
					if ($note ['strike_note_type'] == "1") {
						$html .= '	<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
					} else {
						if ($note ['strike_pin'] != null && $note ['strike_pin'] != "") {
							$html .= '	<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
						} else {
							if ($note ['strike_signature'] != null && $note ['strike_signature'] != "") {
								$html .= '	<img src=" ' . $note ['strike_signature'] . ' " height="29px"> ';
							}
						}
					}
					$html .= '( ' . $note ['strike_date_added'] . ' )';
				}
				// }
				
				if ($note ['reminder_time'] != null && $note ['reminder_time'] != "") {
					$html .= '&nbsp;&nbsp;';
					$html .= '<img src="sites/view/digitalnotebook/image/Add-Alarm.png" width="28px" height="28px">';
					$html .= '&nbsp;&nbsp;';
					$html .= $note ['reminder_title'];
					$html .= '&nbsp;&nbsp; ' . $note ['reminder_time'] . '';
				}
				
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						
						if ($note ['incidentforms'] != null && $note ['incidentforms'] != "") {
							$i = 0;
							foreach ( $note ['incidentforms'] as $incidentform ) {
								if ($i != 0) {
									$csspadding = "margin-left:4px;";
								} else {
									$csspadding = '';
								}
								
								if ($incidentform ['form_design_type'] == 'Database') {
									$html .= '<a target="_blank"  href="' . $custom_printform_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
									$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
									$html .= '</a> ';
								} else {
									
									if ($note ['is_archive'] == '4') {
										$html .= '<a class="form_insert1" id="form1_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
										$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
										$html .= '</a> ';
									} else {
										if ($incidentform ['form_type'] == '3') {
											if ($incidentform ['image_url'] == null && $incidentform ['image_url'] == "") {
												$html .= '<a class="form_insert1" id="form_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . ' ">';
												$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
												$html .= '</a> ';
											}
										}
										
										// var_dump($incidentform);
										if ($incidentform ['form_type'] == '1') {
											$html .= '<a class="form_insert"  href="' . $form_url . '&incidentform_id=' . $incidentform ['form_type_id'] . '">';
											$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="' . $csspadding . '">';
											$html .= '</a> ';
										}
										
										if ($incidentform ['form_type'] == '2') {
											$html .= '<a class="form_insert"  href="' . $check_list_form_url . '&checklist_id=' . $incidentform ['form_type_id'] . '&notes_id=' . $incidentform ['notes_id'] . '">';
											$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px" style="' . $csspadding . '"> ';
											$html .= '	</a> ';
										}
									}
								}
								if ($incidentform ['image_url'] == null && $incidentform ['image_url'] == "") {
									if ($incidentform ['incident_number'] != null && $incidentform ['incident_number'] != "") {
										$html .= '&nbsp;&nbsp;';
										$html .= $incidentform ['incident_number'];
									}
								}
								
								if ($incidentform ['user_id'] != null && $incidentform ['user_id'] != "0") {
									$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;';
									$html .= $incidentform ['user_id'];
									
									if ($incidentform ['notes_type'] == "1") {
										$html .= '	<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
									} else {
										
										if ($incidentform ['notes_pin'] != null && $incidentform ['notes_pin'] != "") {
											$html .= '	<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
										} else {
											if ($incidentform ['signature'] != null && $incidentform ['signature'] != "") {
												$html .= '	<img src=" ' . $incidentform ['signature'] . ' " width="98px" height="29px"> ';
											}
										}
									}
									if ($incidentform ['form_date_added'] != "" && $incidentform ['form_date_added'] != "") {
										$html .= '( ' . $incidentform ['form_date_added'] . ' )';
									}
								}
								
								$i ++;
							}
						}
					}
				} else {
					
					if ($note ['incidentforms'] != null && $note ['incidentforms'] != "") {
						$i = 0;
						foreach ( $note ['incidentforms'] as $incidentform ) {
							if ($i != 0) {
								$csspadding = "margin-left:4px;";
							} else {
								$csspadding = '';
							}
							
							// var_dump($incidentform);
							
							if ($incidentform ['form_design_type'] == 'Database') {
								$html .= '<a target="_blank"  href="' . $custom_printform_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
								$html .= '</a> ';
							} else {
								if ($note ['is_archive'] == '4') {
									$html .= '<a class="form_insert1" id="form1_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
									$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
									$html .= '</a> ';
								} else {
									if ($incidentform ['form_type'] == '3') {
										if ($incidentform ['image_url'] == null && $incidentform ['image_url'] == "") {
											$html .= '<a class="form_insert1" id="form_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . ' ">';
											$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
											$html .= '</a> ';
										}
									}
									
									if ($incidentform ['form_type'] == '1') {
										$html .= '<a class="form_insert"  href="' . $form_url . '&incidentform_id=' . $incidentform ['form_type_id'] . '">';
										$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="' . $csspadding . '">';
										$html .= '</a> ';
									}
									
									if ($incidentform ['form_type'] == '2') {
										$html .= '<a class="form_insert"  href="' . $check_list_form_url . '&checklist_id=' . $incidentform ['form_type_id'] . '&notes_id=' . $incidentform ['notes_id'] . '">';
										$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px" style="' . $csspadding . '"> ';
										$html .= '	</a> ';
									}
								}
							}
							if ($incidentform ['image_url'] == null && $incidentform ['image_url'] == "") {
								if ($incidentform ['incident_number'] != null && $incidentform ['incident_number'] != "") {
									$html .= '&nbsp;&nbsp;';
									$html .= $incidentform ['incident_number'];
								}
							}
							
							if ($incidentform ['user_id'] != null && $incidentform ['user_id'] != "0") {
								$html .= '&nbsp;&nbsp;';
								
								$html .= $incidentform ['user_id'];
								if ($incidentform ['notes_type'] == "1") {
									;
									$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								} else {
									if ($incidentform ['notes_pin'] != null && $incidentform ['notes_pin'] != "") {
										$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									} else {
										if ($incidentform ['signature'] != null && $incidentform ['signature'] != "") {
											$html .= '<img src=" ' . $incidentform ['signature'] . ' " width="98px" height="29px"> ';
										}
									}
								}
								if ($incidentform ['form_date_added'] != "" && $incidentform ['form_date_added'] != "") {
									$html .= '( ' . $incidentform ['form_date_added'] . ' )';
								}
							}
							
							$i ++;
						}
					}
				}
				
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						if ($note ['is_tag'] != null && $note ['is_tag'] != "0") {
							if ($note ['form_type'] == '2') {
								$html .= '<a class="form_insert1" id="is_tag_' . $note ['notes_id'] . '" href="' . $customIntake_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
								$html .= '</a> ';
							}
							
							if ($note ['form_type'] == '1') {
								$html .= '<a class="form_insert1" id="is1_tag_' . $note ['notes_id'] . '" href="' . $medication_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
								$html .= '</a> ';
							}
							if ($note ['form_type'] == '3') {
								$html .= '<a class="form_insert1" id="is11_tag_' . $note ['notes_id'] . '" href="' . $assignteam_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
								$html .= '</a> ';
							}
							
							if ($note ['form_type'] == '4') {
								$html .= '<a target="_blank" href="' . $discharge_href . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/case-54-54.png" width="35px" height="35px" style="">';
								$html .= '</a> ';
							}
						}
						
						if ($note ['form_type'] == '9') {
							$html .= '<a  href="' . $add_case_url . '&tags_id=' . $note ['tags_id'] . '&notes_id=' . $note ['notes_id'] . '&case_number=' . $note ['case_number'] . '&case_file_id=' . $note ['case_file_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['is_census'] == "1") {
							$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $censusdetail_url . '&notes_id=' . $note ['notes_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['generate_report'] == "3") {
							$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheck_url . '&notes_id=' . $note ['notes_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['generate_report'] == "6") {
							$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheckurl . '&notes_id=' . $note ['notes_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
					}
				} else {
					if ($note ['is_tag'] != null && $note ['is_tag'] != "0") {
						if ($note ['form_type'] == '2') {
							$html .= '<a class="form_insert1" id="is_tag_' . $note ['notes_id'] . '" href="' . $customIntake_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['form_type'] == '1') {
							$html .= '<a class="form_insert1" id="is1_tag_' . $note ['notes_id'] . '" href="' . $medication_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['form_type'] == '3') {
							$html .= '<a class="form_insert1" id="is11_tag_' . $note ['notes_id'] . '" href="' . $assignteam_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['form_type'] == '4') {
							$html .= '<a target="_blank" href="' . $discharge_href . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/case-54-54.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
					}
					if ($note ['form_type'] == '9') {
						$html .= '<a  href="' . $add_case_url . '&tags_id=' . $note ['tags_id'] . '&notes_id=' . $note ['notes_id'] . '&case_number=' . $note ['case_number'] . '&case_file_id=' . $note ['case_file_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					if ($note ['is_census'] == "1") {
						$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $censusdetail_url . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					
					if ($note ['generate_report'] == "3") {
						$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheck_url . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					if ($note ['generate_report'] == "6") {
						$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheckurl . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
				}
				
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						
						if ($note ['images'] != null && $note ['images'] != "") {
							foreach ( $note ['images'] as $image ) {
								$html .= '<a target="_blank" class="open_file2" href=" ' . $image ['notes_file_url'] . '">';
								$html .= $image ['keyImageSrc'];
								$html .= '</a>';
								if ($image ['media_user_id'] != null && $image ['media_user_id'] != "0") {
									$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;';
									$html .= $image ['media_user_id'];
									if ($image ['notes_type'] == "1") {
										$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
									} else {
										if ($image ['media_pin'] != null && $image ['media_pin'] != "") {
											$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
										} else {
											if ($image ['media_signature'] != null && $image ['media_signature'] != "") {
												$html .= '<img src=" ' . $image ['media_signature'] . '" width="98px" height="29px"> ';
											}
										}
									}
									$html .= '( ' . $image ['media_date_added'] . ' )';
								}
							}
						}
					}
				} else {
					
					if ($note ['images'] != null && $note ['images'] != "") {
						foreach ( $note ['images'] as $image ) {
							$html .= '<a target="_blank" class="open_file2" href=" ' . $image ['notes_file_url'] . '">';
							$html .= $image ['keyImageSrc'];
							$html .= '</a>';
							if ($image ['media_user_id'] != null && $image ['media_user_id'] != "0") {
								$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;';
								$html .= $image ['media_user_id'];
								if ($image ['notes_type'] == "1") {
									$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								} else {
									if ($image ['media_pin'] != null && $image ['media_pin'] != "") {
										$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									} else {
										if ($image ['media_signature'] != null && $image ['media_signature'] != "") {
											$html .= '<img src=" ' . $image ['media_signature'] . '" width="98px" height="29px"> ';
										}
									}
								}
								$html .= '( ' . $image ['media_date_added'] . ' )';
							}
						}
					}
				}
				
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						if ($note ['geolocation_info'] ['travel_state'] == "0") {
							if ($note ['geolocation_info'] ['waypoint_google_url'] == null && $note ['geolocation_info'] ['waypoint_google_url'] == "") {
								if ($note ['geolocation_info'] ['google_url'] != null && $note ['geolocation_info'] ['google_url'] != "") {
									$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['google_url'] . '">';
									$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
									$html .= '</a>';
								}
							}
						}
						
						if ($note ['geolocation_info'] ['current_google_url'] != null && $note ['geolocation_info'] ['current_google_url'] != "") {
							$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['current_google_url'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/Current-Location-54-54.png" width="35px" height="35px">';
							$html .= '</a>';
						}
						
						if ($note ['geolocation_info'] ['travel_state'] == "0") {
							if ($note ['geolocation_info'] ['waypoint_google_url'] != null && $note ['geolocation_info'] ['waypoint_google_url'] != "") {
								$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['waypoint_google_url'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
								$html .= '</a>';
							}
						}
						
						if ($note ['geolocation_info'] ['travel_state'] == "1") {
							if ($note ['geolocation_info'] ['location_tracking_route'] != null && $note ['geolocation_info'] ['location_tracking_route'] != "") {
								$html .= '<a target="_blank" href="' . $routemap_url . '&notes_id=' . $note ['notes_id'] . '&travel_task_id=' . $note ['geolocation_info'] ['travel_task_id'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
								$html .= '</a>';
							}
							/*
							 * if($note['geolocation_info']['google_map_image_url'] !=
							 * null && $note['geolocation_info']['google_map_image_url']
							 * != ""){
							 * $html.='<a target="_blank"
							 * href="'.$note['geolocation_info']['google_map_image_url'].'">';
							 * $html.= '<img
							 * src="sites/view/digitalnotebook/image/map.png"
							 * width="35px" height="35px">';
							 * $html.='</a>';
							 * }
							 */
						}
					}
				} else {
					if ($note ['geolocation_info'] ['travel_state'] == "0") {
						if ($note ['geolocation_info'] ['waypoint_google_url'] == null && $note ['geolocation_info'] ['waypoint_google_url'] == "") {
							if ($note ['geolocation_info'] ['google_url'] != null && $note ['geolocation_info'] ['google_url'] != "") {
								$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['google_url'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
								$html .= '</a>';
							}
						}
					}
					
					if ($note ['geolocation_info'] ['current_google_url'] != null && $note ['geolocation_info'] ['current_google_url'] != "") {
						$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['current_google_url'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/Current-Location-54-54.png" width="35px" height="35px">';
						$html .= '</a>';
					}
					
					if ($note ['geolocation_info'] ['travel_state'] == "0") {
						if ($note ['geolocation_info'] ['waypoint_google_url'] != null && $note ['geolocation_info'] ['waypoint_google_url'] != "") {
							$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['waypoint_google_url'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
							$html .= '</a>';
						}
					}
					
					if ($note ['geolocation_info'] ['travel_state'] == "1") {
						if ($note ['geolocation_info'] ['location_tracking_route'] != null && $note ['geolocation_info'] ['location_tracking_route'] != "") {
							$html .= '<a target="_blank" href="' . $routemap_url . '&notes_id=' . $note ['notes_id'] . '&travel_task_id=' . e ['geolocation_info'] ['travel_task_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
							$html .= '</a>';
						}
						
						/*
						 * if($note['geolocation_info']['google_map_image_url'] != null
						 * && $note['geolocation_info']['google_map_image_url'] != ""){
						 * $html.='<a target="_blank"
						 * href="'.$note['geolocation_info']['google_map_image_url'].'">';
						 * $html.= '<img src="sites/view/digitalnotebook/image/map.png"
						 * width="35px" height="35px">';
						 * $html.='</a>';
						 * }
						 */
					}
				}
				
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						
						if ($note ['approvaltask'] != null && $note ['approvaltask'] != "") {
							if ($note ['approvaltask'] ['approval_taskid'] != null && $note ['approvaltask'] ['approval_taskid'] != "") {
								$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&task_id=' . $note ['approvaltask'] ['approval_taskid'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
								$html .= '</a> ';
							}
						}
						
						if ($note ['is_approval_required_forms_id'] > 0) {
							
							$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&is_approval_required_forms_id=' . $note ['is_approval_required_forms_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
					}
				} else {
					
					if ($note ['approvaltask'] != null && $note ['approvaltask'] != "") {
						if ($note ['approvaltask'] ['approval_taskid'] != null && $note ['approvaltask'] ['approval_taskid'] != "") {
							$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&task_id=' . $note ['approvaltask'] ['approval_taskid'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
					}
					
					if ($note ['is_approval_required_forms_id'] > 0) {
						
						$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&is_approval_required_forms_id=' . $note ['is_approval_required_forms_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
				}
				
				if ($note ['alltag'] ['user_id'] != null && $note ['alltag'] ['user_id'] != "") {
					$html .= '| Tags Updated By ';
					$html .= $note ['alltag'] ['user_id'];
					
					if ($note ['alltag'] ['notes_type'] == "2") {
						$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
					} elseif ($note ['alltag'] ['notes_type'] == "1") {
						
						$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
					} elseif ($note ['alltag'] ['notes_pin'] != null && $note ['alltag'] ['notes_pin'] != "") {
						
						$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
					} else {
						if ($note ['alltag'] ['signature'] != null && $note ['alltag'] ['signature'] != "") {
							$html .= '<img src="' . $note ['alltag'] ['signature'] . ' " width="98px" height="29px">';
						}
					}
					
					$html .= '( ' . date ( $date_format, strtotime ( $note ['alltag'] ['date_added'] ) ) . ' )';
				}
				
				if ($note ['form_type'] == '5') {
					
					$html .= '<a target="_blank"  href="' . $inventory_check_out_url . /*'&user_id=' . $note['is_inventory_checkout_id'] .*/ '&notes_id=' . $note ['notes_id'] . ' ">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
					$html .= '</a> ';
				}
				
				if ($note ['form_type'] == '6') {
					
					$html .= '<a target="_blank" href="' . $inventory_check_in_url . /*'&user_id=' . $note['is_inventory_checkin_id'] .*/ '&notes_id=' . $note ['notes_id'] . ' ">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
					$html .= '</a> ';
				}
				
				if ($note ['is_user_face'] == "2") {
					$html .= '<a target="_blank" href="' . $note ['user_file'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/face-recognization-1.png" width="35px" height="35px"> ';
					$html .= '</a>';
				}
				
				if ($note ['is_user_face'] == "1") {
					$html .= '<a target="_blank" href="' . $note ['user_file'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/face-recognization-0.png" width="35px" height="35px"> ';
					$html .= '</a>';
				}
				
				/*
				 * if ($this->customer->isdisplay_attachment() == "1") {
				 * if ($note['user_file'] != null && $note['user_file'] != "") {
				 * $html .= '<a target="_blank" href="' . $note['user_file'] . '">';
				 * $html .= '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px">';
				 * $html .= '</a>';
				 * }
				 * }
				 */
				
				$html .= '<div class="process_bar" id="progressbar' . $note ['notes_id'] . '"><div id="status' . $note ['notes_id'] . '" style="line-height: 15px;color: #fff;">0%</div></div>';
				
				$html .= '</span>';
				
				if (! empty ( $note ['notescomments'] )) {
					$html .= '<div class="comts">
							<hr>
							<span class="ss"><img src="sites/view/digitalnotebook/image/Comments-54-54.png" width="35px" height="35px">Comment</span>
							<br>';
					
					foreach ( $note ['notescomments'] as $comment ) {
						foreach ( $comment ['commentskeywords'] as $commentkeyword ) {
							$html .= '<img src="' . $noteskeyword ['keyword_file_url'] . ' " width="35px" height="35px">';
						}
						$html .= $comment ['comment'];
						
						if ($comment ['user_id'] != null && $comment ['user_id'] != "0") {
							$html .= $comment ['user_id'];
							
							if ($comment ['notes_type'] == "2") {
								$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
							} elseif ($comment ['notes_type'] == "1") {
								
								$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							} elseif ($comment ['user_id'] == SYSTEM_GENERATED) {
								
								$html .= '<img src="sites/view/digitalnotebook/image/Logo-36x36.png" width="35px" height="35px">';
							} elseif ($comment ['notes_pin'] != null && $comment ['notes_pin'] != "") {
								
								$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
							} else {
								if ($comment ['signature'] != null && $comment ['signature'] != "") {
									$html .= '<img src=" ' . $comment ['signature'] . ' " width="98px" height="29px">';
								}
							}
							
							$html .= '( ' . $comment ['date_added'] . ' )';
						}
					}
					
					$html .= '</div>';
				}
				
				$html .= '</div>';
				
				$html .= '</div>';
				$html .= '</td>';
				
				$html .= '<';
				$html .= 'script>';
				
				$html .= '$("#form1333_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				$html .= '$("#form1_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				$html .= '$("#form_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				
				$html .= '$("#is_tag_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				$html .= '$("#is1_tag_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				$html .= '$("#is11_tag_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				$html .= '$("#is_census_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
				
				$html .= " $(\'#notes_text" . $note ['notes_id'] . "\').on( \'keyup\', \'textarea\', function (e){ ";
				$html .= "     $(this).css(\'height\', \'auto\' );";
				
				$html .= '  });';
				$html .= " $(\'#notes_text" . $note ['notes_id'] . "\').find( \'textarea\' ).keyup();";
				$html .= "  $(\'#notes_description" . $note ['notes_id'] . "\').attr(\'readonly\',\'readonly\');";
				
				$html .= '<\/script>';
				
				$html .= '</tr>';
			}
		} else {
			
			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'notes/image' );
			$this->load->model ( 'setting/highlighter' );
			$this->load->model ( 'user/user' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'facilities/facilities' );
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'notes/tags' );
			$this->load->model ( 'notes/notescomment' );
			
			$config_tag_status = $this->customer->isTag ();
			$this->data ['config_tag_status'] = $this->customer->isTag ();
			
			$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
			$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
			$this->data ['config_rules_status'] = $this->customer->isRule ();
			$this->data ['config_share_notes'] = $this->customer->isNotesShare ();
			$this->data ['config_multiple_activenote'] = $this->customer->isMactivenote ();
			
			$custom_form_form_url = $this->url->link ( 'form/form', '' . $url, 'SSL' );
			$custom_printform_form_url = $this->url->link ( 'form/form/printform', '' . $url, 'SSL' );
			$form_url = $this->url->link ( 'notes/noteform/forminsert', '' . $url, 'SSL' );
			$check_list_form_url = $this->url->link ( 'notes/createtask/noteschecklistform', '' . $url, 'SSL' );
			
			$customIntake_url = $this->url->link ( 'notes/tags/updateclient', '' . $url2, 'SSL' );
			$censusdetail_url = $this->url->link ( 'resident/dailycensus/censusdetail', '' . $url2, 'SSL' );
			
			$medication_url = $this->url->link ( 'resident/resident/tagsmedication', '' . $url2, 'SSL' );
			
			$bedcheckurl = $this->url->link ( 'resident/resident/movementreport', '' . $url2, 'SSL' );
			
			$update_medication_url = $this->url->link ( 'notes/common/authorization&addmedication=1', '' . $url2, 'SSL' );
			
			$bedcheck_url = $this->url->link ( 'notes/printbedcheck&is_bedchk=1', '' . $url2, 'SSL' );
			
			$assignteam_url = $this->url->link ( 'resident/assignteam', '' . $url2, 'SSL' );
			
			$approval_url = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
			
			$routemap_url = $this->url->link ( 'notes/routemap', '' . $url2, 'SSL' );
			
			$discharge_href = $this->url->link ( 'notes/case', '' . $url2, 'SSL' );
			
			$inventory_check_in_url = $this->url->link ( 'notes/addInventory/CheckInInventoryForm', '' . $url2, 'SSL' );
			
			$inventory_check_out_url = $this->url->link ( 'notes/addInventory/CheckOutInventoryForm', '' . $url2, 'SSL' );
			
			$add_case_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/formcase/viewcase&addcase=1', '', 'SSL' ) );
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
				$url .= '&update_notetime=1';
				// $update_note_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url, 'SSL' ) );
			} else {
				// $update_note_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updatenotetime', '' . $url, 'SSL' ) );
			}
			
			$update_note_url = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/updatenotetimecomment', '' . $url, 'SSL' ) );
			
			$result = $this->model_notes_notes->getnotes ( $notes_id );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			// $this->data['is_master_facility'] = $facilityinfo['is_master_facility'] ;
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				$is_master_facility = '1';
			} else {
				$is_master_facility = '2';
			}
			
			$unique_id = $facilityinfo ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize ( $customer_info ['client_info_notes'] );
			$customers = unserialize ( $customer_info ['setting_data'] );
			
			if ($customers ['date_format'] != null && $customers ['date_format'] != "") {
				$date_format = $customers ['date_format'];
			} else {
				$date_format = $this->language->get ( 'date_format_short_2' );
			}
			
			if ($customers ['time_format'] != null && $customers ['time_format'] != "") {
				$time_format = $customers ['time_format'];
			} else {
				$time_format = 'h:i A';
			}
			$this->data ['date_format'] = $date_format;
			$this->data ['time_format'] = $time_format;
			
			if ($result) {
				
				// echo '<pre>'; print_r($result); echo '</pre>'; die;
				
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
								'media_date_added' => date ( $date_format, strtotime ( $image ['media_date_added'] ) ),
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
					$task_time = date ( $time_format, strtotime ( $result ['task_time'] ) );
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
						$this->load->model ( 'form/form' );
						foreach ( $allforms as $allform ) {
							$formdata_i = $this->model_form_form->getFormDatadesign ( $allform ['custom_form_type'] );
							$forms [] = array (
									'form_type_id' => $allform ['form_type_id'],
									'form_design_type' => $formdata_i ['form_type'],
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
									'form_date_added' => date ( $date_format, strtotime ( $allform ['form_date_added'] ) ) 
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
						
						if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
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
								// 'medication_attach_url' => $alltask['medication_attach_url'],
								'medication_attach_url' => $medication_attach_url,
								'date_added' => date ( $date_format, strtotime ( $alltask ['date_added'] ) ),
								'room_current_date_time' => date ( $time_format, strtotime ( $alltask ['room_current_date_time'] ) ),
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
							$taskTime = date ( $time_format, strtotime ( $alltmask ['task_time'] ) );
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
								'media_url' => $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' ),
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
								
								'medication_file_upload' => $alltmask ['medication_file_upload'],
								'date_added' => date ( $date_format, strtotime ( $alltmask ['date_added'] ) ) 
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
					$original_task_time = date ( $time_format, strtotime ( $result ['original_task_time'] ) );
				} else {
					$original_task_time = "";
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
				
				$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'], $result ['facilities_id'] );
				
				$uptimes = array ();
				$uptimes = $this->model_notes_notes->getupdatetime ( $result ['notes_id'] );
				
				$case_file_id = '';
				$tags_id = '';
				
				if ($result ['notes_id'] != '') {
					$cdata ['notes_id'] = $result ['notes_id'];
					$this->load->model ( 'resident/casefile' );
					$case_info = $this->model_resident_casefile->getcasefilesbynotesid ( $cdata );
					$case_file_id = $case_info ['notes_by_case_file_id'];
					$tags_id = $case_info ['tags_ids'];
					$case_number = $case_info ['case_number'];
				} else {
					$case_file_id = '';
					$tags_id = '';
					$case_number = '';
				}
				
				$note = array (
						'notes_id' => $result ['notes_id'],
						'shift_color_value' => $shift_time_color ['shift_color_value'],
						'is_comment' => $result ['is_comment'],
						'case_file_id' => $case_file_id,
						'tags_id' => $tags_id,
						'case_number' => $case_number,
						'printtranscript' => $printtranscript,
						'uptimes' => $uptimes,
						'notescomments' => $notescomments,
						'ooout' => $ooout,
						'is_user_face' => $result ['is_user_face'],
						'is_approval_required_forms_id' => $result ['is_approval_required_forms_id'],
						// 'user_file' => $result['user_file'],
						'user_file' => $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result ['notes_id'], 'SSL' ),
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
						'notetime' => date ( $time_format, strtotime ( $result ['notetime'] ) ),
						'username' => $result ['user_id'],
						'notes_pin' => $userPin,
						'signature' => $result ['signature'],
						'text_color_cut' => $result ['text_color_cut'],
						'text_color' => $result ['text_color'],
						'note_date' => date ( $date_format, strtotime ( $result ['note_date'] ) ),
						'status' => ($result ['status'] ? $this->language->get ( 'text_enabled' ) : $this->language->get ( 'text_disabled' )),
						'date_added' => date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ),
						'strike_user_name' => $result ['strike_user_id'],
						'strike_pin' => $result ['strike_pin'],
						'strike_signature' => $result ['strike_signature'],
						'strike_date_added' => date ( $date_format, strtotime ( $result ['strike_date_added'] ) ),
						'reminder_time' => $reminder_time,
						'reminder_title' => $reminder_title,
						'facilityname' => $facilityname,
						'href' => $this->url->link ( 'notes/notes/insert', '' . '&reset=1&searchdate=' . date ( 'm-d-Y', strtotime ( $result ['date_added'] ) ) . $url, 'SSL' ) 
				);
			}
			
			$html = "";
			$html .= '<tr class="" id="note_' . $note ['notes_id'] . '">';
			$html .= '<div id="player' . $note ['notes_id'] . '">';
			$html .= '<div id="jquery_jplayer' . $note ['notes_id'] . '"></div>';
			$html .= '</div>';
			
			/*
			 * if($note['remdata'] == '1'){
			 *
			 * $html.='<';
			 * $html.='script>';
			 * $html.='$(document).ready(function() {';
			 * $html.=' (function poll() {';
			 * $html.='setTimeout(function() {';
			 * $html.='$.ajax({';
			 * $html.='url:
			 * "index.php?route=notes/notes/getReminderTime&notes_id='.$note['notes_id'].'",';
			 * $html.='type: "GET",';
			 * $html.='success: function(data) {';
			 *
			 * $html.="if(data.checkTime == '1'){";
			 * $html.='$(\'<audio id="chatAudio'.$note['notes_id'].'"><source
			 * src="\'+tmpTone+\'"
			 * type="audio/mpeg"></audio>\').appendTo(\'body\');';
			 * $html.='$(\'#chatAudio'.$note['notes_id'].'\')[0].play();';
			 *
			 * $html.="$.colorbox({innerWidth:450,innerHeight:345,html:'<iframe
			 * width=450 height=325
			 * src=index.php?route=notes/notes/getReminderPopup&notes_id=".$note['notes_id']."
			 * frameborder=0 allowfullscreen></iframe>'});";
			 * $html.='}';
			 * $html.='},';
			 * $html.='dataType: "json",';
			 * $html.='complete: poll,';
			 * $html.='timeout: 5000';
			 * $html.='}) ';
			 * $html.='}, 10000); ';
			 * $html.=' })();';
			 * $html.='});';
			 *
			 * $html.='<\/script>' ;
			 *
			 *
			 * }
			 */
			
			$html .= '<td class="center" style="width: 80px;height:30px;vertical-align: middle;">';
			
			if ($note ['shift_color_value']) {
				$display_color = "";
				$shift_color = "color :" . $note ['shift_color_value'];
			} else {
				$display_color = "display:none";
				$shift_color = "";
			}
			
			if ($note ['form_type'] == '7') {
				$notetime = date ( $time_format, strtotime ( $note ['uptimes'] ['notetime'] ) );
			} else {
				$notetime = $note ['notetime'];
			}
			
			$html .= '<span><b style="' . $shift_color . ';' . $display_color . '">|</b>';
			$html .= '<input type="text" name="notetime" id="notetime_picker_' . $note ['notes_id'] . '" class="form-control note_time_11 notetime_picker" value="' . $notetime . '" style="color: #000; text-align: center; background: transparent;margin-left: -15px!important;">';
			
			if ($time_format == "H:i") {
				// $html .= '<';
				// $html .= 'script src="sites/view/javascript/mddatetimepicker/mdtimepicker.min2.js"><\/script>';
				// $html .= '<link rel="stylesheet" href="sites/view/javascript/mddatetimepicker/mdtimepicker.min2.css">';
				$html .= '<';
				$html .= 'script>';
				
				$html .= '$("#notetime_picker_' . $note ['notes_id'] . '").mdtimepicker({ readOnly: false,is24hour: true }).on("timechanged", function(e){';
				$html .= '$.colorbox({iframe:true, width:"70%", height:"70%",href:"' . $update_note_url . '&notes_id=' . $note ['notes_id'] . '&notetime="+e.time});';
				$html .= '});';
				
				$html .= '<\/script>';
			} else {
				$html .= '<';
				$html .= 'script>';
				
				$html .= '$("#notetime_picker_' . $note ['notes_id'] . '").mdtimepicker().on("timechanged", function(e){';
				$html .= '$.colorbox({iframe:true, width:"70%", height:"70%",href:"' . $update_note_url . '&notes_id=' . $note ['notes_id'] . '&notetime="+e.time});';
				$html .= '});';
				
				$html .= '<\/script>';
			}
			
			/*
			 * $html .= '<';
			 * $html .= 'script>';
			 *
			 * $html .= '$("#notetime_picker_' . $note ['notes_id'] . '").mdtimepicker().on("timechanged", function(e){';
			 * $html .= '$.colorbox({iframe:true, width:"70%", height:"70%",href:"' . $update_note_url . '&notes_id=' . $note ['notes_id'] . '&notetime="+e.time});';
			 * $html .= '});';
			 *
			 * $html .= '<\/script>';
			 */
			
			if ($note ['form_type'] == '7') {
				$html .= '<span style="color: red;">' . $note ['notetime'] . '</span>';
			}
			
			$html .= '</span>';
			
			$html .= '</td>';
			
			$bgColor = "";
			$bgColor2 = "";
			$bgColor3 = "";
			if ($note ['highlighter_value'] != null && $note ['highlighter_value'] != "") {
				$bgColor3 .= 'background-color:' . $note ['highlighter_value'] . ';';
			}
			if ($note ['text_color_cut'] == "1") {
				$bgColor2 .= 'text-decoration: line-through;';
			}
			
			if ($note ['text_color'] != null && $note ['text_color'] != "") {
				$bgColor .= 'color:' . $note ['text_color'] . ';';
			}
			
			if (($note ['highlighter_value'] != null && $note ['highlighter_value'] != "") && ($note ['text_color'] == null && $note ['text_color'] == "")) {
				if ($note ['highlighter_value'] == '#ffff00') {
					$bgColor3 .= 'color:#000;';
				} else if ($note ['highlighter_value'] == '#ffffff') {
					$bgColor3 .= 'color:#666;';
				} else {
					$bgColor3 .= 'color:#FFF;';
				}
			}
			
			$html .= '<td class="left border_right" style="padding-top:7px;padding-bottom:7px;height:30px;">';
			$html .= '<div id="notes_text' . $note ['notes_id'] . '" class="_ta2" style=" border: none;">';
			$html .= '<div class="div_hr" style="' . $bgColor3 . '">';
			
			if ($note ['visitor_log'] == "1") {
				$html .= '<img src="sites/view/digitalnotebook/image/Visitor-Icons.png" width="35px" height="35px">';
			}
			
			if ($note ['form_type'] == "7") {
				$html .= '<img src="sites/view/digitalnotebook/image/late.png" width="30px" height="30px">';
			}
			
			if ($note ['visitor_log'] == "2") {
				$html .= '<img src="sites/view/digitalnotebook/image/Visitor-Icons-grey.jpg" width="35px" height="35px">	';
			}
			
			if ($note ['text_color_cut'] == "0") {
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						
						$oncl = "onclick=\"selectText(\'" . $note ['notes_id'] . "\',\'" . $note ['taskadded'] . "\',\'" . $note ['tag_privacy'] . "\')\" ";
					}
				} else {
					$oncl = "onclick=\"selectText(\'" . $note ['notes_id'] . "\',\'" . $note ['taskadded'] . "\',\'" . $note ['tag_privacy'] . "\')\" ";
				}
			}
			
			$html .= '<span ' . $oncl . ' style="line-height: 2;' . $bgColor . '' . $bgColor2 . '" data-autoresize' . $note ['notes_id'] . ' rows="1" cols="5" id="notes_description' . $note ['notes_id'] . '" class="form-control1 notes_description_cl_list " >';
			
			if ($is_master_facility == "1") {
				$html .= $note ['facilityname'] . ' | ';
			}
			
			if ($note ['form_type'] == '5') {
				
				$html .= '<img src="sites/view/digitalnotebook/image/Inventroy-Check-out.png" width="35px" height="35px" style=" ' . $csspadding . '">';
			}
			
			if ($note ['form_type'] == "9") {
				$html .= '<img src="sites/view/digitalnotebook/image/case-54-54.png" width="30px" height="30px">';
			}
			if ($note ['form_type'] == '6') {
				
				$html .= '<img src="sites/view/digitalnotebook/image/Inventroy-Check-in.png" width="35px" height="35px" style=" ' . $csspadding . '">';
			}
			
			if ($note ['generate_report'] == "2") {
				$html .= '<img src="sites/view/digitalnotebook/image/generate-Report.png" width="45px" height="45px">';
			}
			
			if ($note ['generate_report'] == "3") {
				$html .= '<img src="sites/view/digitalnotebook/image/generate-Report.png" width="45px" height="45px">';
			}
			
			if ($note ['is_census'] == "1") {
				$html .= '<img src="sites/view/digitalnotebook/image/census.png" width="45px" height="45px">';
			}
			
			if ($note ['is_offline'] == "1") {
				$html .= '<img src="sites/view/digitalnotebook/image/wifi.png" width="45px" height="45px">';
			}
			
			if ($note ['checklist_status'] == "1") {
				
				if ($note ['taskadded'] == "2") {
					$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
				}
				
				if ($note ['taskadded'] == "3") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
				}
				
				if ($note ['taskadded'] == "4") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Missed Task: ';
				}
			} elseif ($note ['checklist_status'] == "2") {
				$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px">';
			} else {
				
				if ($note ['taskadded'] == "1") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Deleted:  ';
				}
				
				if ($note ['taskadded'] == "2") {
					$html .= '<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
				}
				
				if ($note ['taskadded'] == "3") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
				}
				
				if ($note ['taskadded'] == "4") {
					$html .= '<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Missed Task:  ';
				}
			}
			
			if ($note ['noteskeywords']) {
				foreach ( $note ['noteskeywords'] as $noteskeyword ) {
					
					$html .= '<img src="' . $noteskeyword ['keyword_file_url'] . ' " width="35px" height="35px">';
				}
			}
			
			/*
			 * if($note['task_type'] == "1"){
			 * $html.='Bed Check for ';
			 * }
			 */
			
			/*
			 * if($note['task_type'] == "2"){
			 * $html.='Medications given at ';
			 * }
			 */
			
			if ($note ['task_type'] != "1" && $note ['task_type'] != "2" && $note ['task_type'] != "4" && $note ['task_type'] != "5") {
				if ($note ['task_time'] != null && $note ['task_time'] != "") {
					$html .= $note ['task_time'];
				}
			}
			
			/*
			 * if($note['task_type'] == "2"){
			 * $html.=' to the following Resident: ';
			 * }
			 */
			/*
			 * if($note['task_type'] == "1"){
			 * $html.='Completed. The following details were noted: ';
			 * }
			 */
			
			/*
			 * if($note['assign_to'] != null && $note['assign_to'] != ""){
			 * <b>Assign To:</b> echo $note['assign_to'];
			 * }
			 */
			
			/*
			 * if($note['review_notes'] == "1"){
			 * <img src="sites/view/digitalnotebook/image/rev.png" width="35px"
			 * height="35px">
			 * }
			 */
			
			$html .= str_replace ( array (
					"\r",
					"\n" 
			), array (
					"<br>",
					"" 
			), $note ['notes_description'] );
			// $html.= nl2br($note['notes_description']);
			
			if ($note ['notesmedicationtasks'] != null && $note ['notesmedicationtasks'] != "") {
				
				foreach ( $note ['notesmedicationtasks'] as $notesmedicationtask ) {
					
					if ($note ['tag_privacy'] == "2") {
						if ($this->session->data ['unloack_success'] == '1') {
							$html .= '<br>  ';
							// $html.='<br> '.$notesmedicationtask['task_content'].'
							// | '.$notesmedicationtask['drug_name'].' | ';
							$html .= $notesmedicationtask ['drug_name'] . ' ';
							
							if ($notesmedicationtask ['signature'] != null && $notesmedicationtask ['signature'] != "") {
								// $html.='<img width="98px" height="29px"
								// src="'.$notesmedicationtask['signature'].'"> |
								// Given at '.$notesmedicationtask['task_time'].' by
								// '.$note['user_id'].'';
								$html .= ' | <img width="98px" height="29px" src="' . $notesmedicationtask ['signature'] . '"> ';
							}
							
							if ($notesmedicationtask ['medication_file_upload'] == "0") {
								if ($notesmedicationtask ['media_url'] != null && $notesmedicationtask ['media_url'] != "") {
									$html .= '<a target="_blank" class="" href="' . $notesmedicationtask ['media_url'] . '">';
									$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
									$html .= '</a>';
								}
							}
						} else {
						}
					} else {
						$html .= '<br>  ';
						// $html.='<br> '.$notesmedicationtask['task_content'].' |
						// '.$notesmedicationtask['drug_name'].' | ';
						
						$html .= $notesmedicationtask ['drug_name'] . ' ';
						
						if ($notesmedicationtask ['signature'] != null && $notesmedicationtask ['signature'] != "") {
							// $html.='<img width="98px" height="29px"
							// src="'.$notesmedicationtask['signature'].'"> | Given
							// at '.$notesmedicationtask['task_time'].' by
							// '.$note['user_id'].'';
							$html .= ' | <img width="98px" height="29px" src="' . $notesmedicationtask ['signature'] . '"> ';
						}
						
						if ($notesmedicationtask ['medication_file_upload'] == "0") {
							if ($notesmedicationtask ['media_url'] != null && $notesmedicationtask ['media_url'] != "") {
								$html .= '<a target="_blank" class="" href="' . $notesmedicationtask ['media_url'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
								$html .= '</a>';
							}
						}
					}
				}
				$html .= '<br>';
				$html .= 'Completed by ';
			}
			
			if ($note ['notestasks'] != null && $note ['notestasks'] != "") {
				
				if ($note ['grandtotal'] != null && $note ['grandtotal'] != "0") {
					$html .= '<br>IN - Total :' . $note ['grandtotal'] . '';
				}
				foreach ( $note ['notestasks'] as $notestask ) {
					$html .= ' <br> ' . $notestask ['location_name'] . ' | ' . $notestask ['room_current_date_time'] . ' | ' . $notestask ['tags_ids_names'];
					
					if ($notestask ['task_comments'] != null && $notestask ['task_comments'] != "") {
						$html .= $notestask ['task_comments'];
					}
					
					// $html.='<br>Total Clients IN : '.$notestask['capacity'].'';
					if ($notestask ['medication_attach_url'] != null && $notestask ['medication_attach_url'] != "") {
						
						$html .= '<a target="_blank" class="" href="' . $notestask ['medication_attach_url'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="30px" height="30px" alt="" />';
						$html .= '</a>';
					}
				}
				
				if ($note ['ooout'] == "1") {
					$html .= '<br>OUT ';
				}
				
				foreach ( $note ['notestasks'] as $notestask ) {
					
					if ($notestask ['out_tags_ids_names'] != null && $notestask ['out_tags_ids_names'] != "") {
						$html .= ' <br> ' . $notestask ['out_tags_ids_names'];
						
						if ($notestask ['task_comments'] != null && $notestask ['task_comments'] != "") {
							$html .= $notestask ['task_comments'];
						}
					}
					
					// $html.='<br>Total Clients IN : '.$notestask['capacity'].'';
					if ($notestask ['out_tags_ids_names'] != null && $notestask ['out_tags_ids_names'] != "") {
						if ($notestask ['medication_attach_url'] != null && $notestask ['medication_attach_url'] != "") {
							$html .= '<a target="_blank" class="" href="' . $notestask ['medication_attach_url'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/attachment.png" width="30px" height="30px" alt="" />';
							$html .= '</a>';
						}
					}
				}
				
				/*
				 * if($note['boytotals'][0] != null && $note['boytotals'][0] != ""){
				 * $html.='<br>Total '.$note['boytotals'][0]['loc_name'].':
				 * '.$note['boytotals'][0]['total'].'';
				 *
				 * }
				 * if($note['girltotals'][0] != null && $note['girltotals'][0] !=
				 * ""){
				 * $html.='<br>Total '.$note['girltotals'][0]['loc_name'].':
				 * '.$note['girltotals'][0]['total'].'';
				 *
				 * }
				 * if($note['generaltotals'][0] != null && $note['generaltotals'][0]
				 * != ""){
				 * $html.='<br>Total '.$note['generaltotals'][0]['loc_name'].':
				 * '.$note['generaltotals'][0]['total'].'';
				 *
				 * }
				 */
				/*
				 * if($note['grandtotal'] != null && $note['grandtotal'] != "0"){
				 * $html.='<br>Grand Total number of Clients IN :'.
				 * $note['grandtotal'].'';
				 * }
				 */
			}
			
			if ($config_tag_status == '1') {
				
				if ($note ['tag_privacy'] == "2") {
					if ($this->session->data ['unloack_success'] == '1') {
						$html .= '<img src="sites/view/digitalnotebook/image/web140x40.png" width="35px" height="35px">';
					} else {
						$html .= '<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
					}
				}
			}
			
			$html .= '</span> ';
			$html .= '<span class="user_deatil" > ';
			/*
			 * if ($note['is_private'] == '1') {
			 * $html .= $note['username'];
			 * $html .= '<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
			 * $html .= '( ' . $note['note_date'] . ' )';
			 * } else {
			 */
			
			if ($note ['username'] != null && $note ['username'] != "0") {
				$html .= $note ['username'];
				
				if ($note ['notes_type'] == "2") {
					$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
				} elseif ($note ['notes_type'] == "1") {
					
					$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
				} elseif ($note ['username'] == SYSTEM_GENERATED) {
					
					$html .= '<img src="sites/view/digitalnotebook/image/Logo-36x36.png" width="35px" height="35px">';
				} elseif ($note ['notes_pin'] != null && $note ['notes_pin'] != "") {
					
					$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
				} else {
					if ($note ['signature'] != null && $note ['signature'] != "") {
						$html .= '<img src=" ' . $note ['signature'] . ' " width="98px" height="29px">';
					}
					
					if ($note ['notes_type'] == "5") {
						$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
					}
				}
				
				$html .= '( ' . $note ['note_date'] . ' )';
			}
			// }
			
			if ($note ['is_comment'] == "2") {
				$html .= '<a target="_blank" href="' . $note ['printtranscript'] . '">';
				$html .= '<img src="sites/view/digitalnotebook/image/transcript.png" width="35px" height="35px">';
				$html .= '</a>';
			}
			
			/*
			 * if ($note['is_private_strike'] == '1') {
			 * $html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			 * $html .= $note['strike_user_name'];
			 * $html .= ' <img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
			 * $html .= '( ' . $note['strike_date_added'] . ' )';
			 * } else {
			 */
			
			if ($note ['strike_user_name'] != null && $note ['strike_user_name'] != "0") {
				$html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
				$html .= $note ['strike_user_name'];
				
				if ($note ['strike_note_type'] == "1") {
					$html .= '	<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
				} else {
					if ($note ['strike_pin'] != null && $note ['strike_pin'] != "") {
						$html .= '	<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
					} else {
						if ($note ['strike_signature'] != null && $note ['strike_signature'] != "") {
							$html .= '	<img src=" ' . $note ['strike_signature'] . ' " height="29px"> ';
						}
					}
				}
				$html .= '( ' . $note ['strike_date_added'] . ' )';
			}
			// }
			
			if ($note ['reminder_time'] != null && $note ['reminder_time'] != "") {
				$html .= '&nbsp;&nbsp;';
				$html .= '<img src="sites/view/digitalnotebook/image/Add-Alarm.png" width="28px" height="28px">';
				$html .= '&nbsp;&nbsp;';
				$html .= $note ['reminder_title'];
				$html .= '&nbsp;&nbsp; ' . $note ['reminder_time'] . '';
			}
			
			if ($note ['tag_privacy'] == "2") {
				if ($this->session->data ['unloack_success'] == '1') {
					
					if ($note ['incidentforms'] != null && $note ['incidentforms'] != "") {
						$i = 0;
						foreach ( $note ['incidentforms'] as $incidentform ) {
							if ($i != 0) {
								$csspadding = "margin-left:4px;";
							} else {
								$csspadding = '';
							}
							if ($incidentform ['form_design_type'] == 'Database') {
								$html .= '<a target="_blank"  href="' . $custom_printform_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
								$html .= '</a> ';
							} else {
								if ($note ['is_archive'] == '4') {
									$html .= '<a class="form_insert1" id="form1_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
									$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
									$html .= '</a> ';
								} else {
									if ($incidentform ['form_type'] == '3') {
										$html .= '<a class="form_insert1" id="form_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . ' ">';
										$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
										$html .= '</a> ';
									}
									
									// var_dump($incidentform);
									if ($incidentform ['form_type'] == '1') {
										$html .= '<a class="form_insert"  href="' . $form_url . '&incidentform_id=' . $incidentform ['form_type_id'] . '">';
										$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="' . $csspadding . '">';
										$html .= '</a> ';
									}
									
									if ($incidentform ['form_type'] == '2') {
										$html .= '<a class="form_insert"  href="' . $check_list_form_url . '&checklist_id=' . $incidentform ['form_type_id'] . '&notes_id=' . $incidentform ['notes_id'] . '">';
										$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px" style="' . $csspadding . '"> ';
										$html .= '	</a> ';
									}
								}
							}
							if ($incidentform ['incident_number'] != null && $incidentform ['incident_number'] != "") {
								$html .= '&nbsp;&nbsp;';
								$html .= $incidentform ['incident_number'];
							}
							
							if ($incidentform ['user_id'] != null && $incidentform ['user_id'] != "0") {
								$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;';
								$html .= $incidentform ['user_id'];
								
								if ($incidentform ['notes_type'] == "1") {
									$html .= '	<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								} else {
									
									if ($incidentform ['notes_pin'] != null && $incidentform ['notes_pin'] != "") {
										$html .= '	<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									} else {
										if ($incidentform ['signature'] != null && $incidentform ['signature'] != "") {
											$html .= '	<img src=" ' . $incidentform ['signature'] . ' " width="98px" height="29px"> ';
										}
									}
								}
								if ($incidentform ['form_date_added'] != "" && $incidentform ['form_date_added'] != "") {
									$html .= '( ' . $incidentform ['form_date_added'] . ' )';
								}
							}
							
							$i ++;
						}
					}
				}
			} else {
				
				if ($note ['incidentforms'] != null && $note ['incidentforms'] != "") {
					$i = 0;
					foreach ( $note ['incidentforms'] as $incidentform ) {
						if ($i != 0) {
							$csspadding = "margin-left:4px;";
						} else {
							$csspadding = '';
						}
						
						// var_dump($incidentform);
						if ($incidentform ['form_design_type'] == 'Database') {
							$html .= '<a target="_blank"  href="' . $custom_printform_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
							$html .= '</a> ';
						} else {
							if ($note ['is_archive'] == '4') {
								$html .= '<a class="form_insert1" id="form1_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
								$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
								$html .= '</a> ';
							} else {
								if ($incidentform ['form_type'] == '3') {
									$html .= '<a class="form_insert1" id="form_' . $note ['notes_id'] . '"  href="' . $custom_form_form_url . '&forms_design_id=' . $incidentform ['custom_form_type'] . '&forms_id=' . $incidentform ['forms_id'] . '&notes_id=' . $incidentform ['notes_id'] . ' ">';
									$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
									$html .= '</a> ';
								}
								
								if ($incidentform ['form_type'] == '1') {
									$html .= '<a class="form_insert"  href="' . $form_url . '&incidentform_id=' . $incidentform ['form_type_id'] . '">';
									$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="' . $csspadding . '">';
									$html .= '</a> ';
								}
								
								if ($incidentform ['form_type'] == '2') {
									$html .= '<a class="form_insert"  href="' . $check_list_form_url . '&checklist_id=' . $incidentform ['form_type_id'] . '&notes_id=' . $incidentform ['notes_id'] . '">';
									$html .= '<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px" style="' . $csspadding . '"> ';
									$html .= '	</a> ';
								}
							}
						}
						
						if ($incidentform ['incident_number'] != null && $incidentform ['incident_number'] != "") {
							$html .= '&nbsp;&nbsp;';
							$html .= $incidentform ['incident_number'];
						}
						
						if ($incidentform ['user_id'] != null && $incidentform ['user_id'] != "0") {
							$html .= '&nbsp;&nbsp;';
							
							$html .= $incidentform ['user_id'];
							if ($incidentform ['notes_type'] == "1") {
								;
								$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							} else {
								if ($incidentform ['notes_pin'] != null && $incidentform ['notes_pin'] != "") {
									$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
								} else {
									if ($incidentform ['signature'] != null && $incidentform ['signature'] != "") {
										$html .= '<img src=" ' . $incidentform ['signature'] . ' " width="98px" height="29px"> ';
									}
								}
							}
							if ($incidentform ['form_date_added'] != "" && $incidentform ['form_date_added'] != "") {
								$html .= '( ' . $incidentform ['form_date_added'] . ' )';
							}
						}
						
						$i ++;
					}
				}
			}
			
			if ($note ['tag_privacy'] == "2") {
				if ($this->session->data ['unloack_success'] == '1') {
					if ($note ['is_tag'] != null && $note ['is_tag'] != "0") {
						if ($note ['form_type'] == '2') {
							$html .= '<a class="form_insert1" id="is_tag_' . $note ['notes_id'] . '" href="' . $customIntake_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . ' ">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['form_type'] == '1' && $note ['is_forms'] == "1") {
							
							$final_medication_url = $update_medication_url;
							$form_insert = "form_insert1";
						} else {
							
							$final_medication_url = $medication_url;
							$form_insert = "";
						}
						
						if ($note ['form_type'] == '1') {
							$html .= '<a class="' . $form_insert . '" id="is1_tag_' . $note ['notes_id'] . '" href="' . $final_medication_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						if ($note ['form_type'] == '3') {
							$html .= '<a class="form_insert1" id="is11_tag_' . $note ['notes_id'] . '" href="' . $assignteam_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
						
						if ($note ['form_type'] == '4') {
							$html .= '<a target="_blank" href="' . $discharge_href . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/case-54-54.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
					}
					
					if ($note ['form_type'] == '9') {
						$html .= '<a  href="' . $add_case_url . '&tags_id=' . $note ['tags_id'] . '&notes_id=' . $note ['notes_id'] . '&case_number=' . $note ['case_number'] . '&case_file_id=' . $note ['case_file_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					
					if ($note ['is_census'] == "1") {
						$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $censusdetail_url . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					
					if ($note ['generate_report'] == "3") {
						$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheck_url . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					if ($note ['generate_report'] == "6") {
						$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheckurl . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
				}
			} else {
				if ($note ['is_tag'] != null && $note ['is_tag'] != "0") {
					if ($note ['form_type'] == '2') {
						$html .= '<a class="form_insert1" id="is_tag_' . $note ['notes_id'] . '" href="' . $customIntake_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					
					if ($note ['form_type'] == '1' && $note ['is_forms'] == "1") {
						
						$final_medication_url = $update_medication_url;
						
						$form_insert = "form_insert1";
					} else {
						
						$final_medication_url = $medication_url;
						$form_insert = "";
					}
					
					if ($note ['form_type'] == '1') {
						$html .= '<a class="' . $form_insert . '" id="is1_tag_' . $note ['notes_id'] . '" href="' . $final_medication_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					
					if ($note ['form_type'] == '3') {
						$html .= '<a class="form_insert1" id="is11_tag_' . $note ['notes_id'] . '" href="' . $assignteam_url . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '&is_archive=' . $note ['is_archive'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
					
					if ($note ['form_type'] == '4') {
						$html .= '<a target="_blank" href="' . $discharge_href . '&tags_id=' . $note ['is_tag'] . '&notes_id=' . $note ['notes_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/case-54-54.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
				}
				
				if ($note ['form_type'] == '9') {
					$html .= '<a  href="' . $add_case_url . '&tags_id=' . $note ['tags_id'] . '&notes_id=' . $note ['notes_id'] . '&case_number=' . $note ['case_number'] . '&case_file_id=' . $note ['case_file_id'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
					$html .= '</a> ';
				}
				
				if ($note ['is_census'] == "1") {
					$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $censusdetail_url . '&notes_id=' . $note ['notes_id'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
					$html .= '</a> ';
				}
				
				if ($note ['generate_report'] == "3") {
					$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheck_url . '&notes_id=' . $note ['notes_id'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
					$html .= '</a> ';
				}
				if ($note ['generate_report'] == "6") {
					$html .= '<a class="form_insert1" id="is_census_' . $note ['notes_id'] . '" href="' . $bedcheckurl . '&notes_id=' . $note ['notes_id'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
					$html .= '</a> ';
				}
			}
			
			if ($note ['tag_privacy'] == "2") {
				if ($this->session->data ['unloack_success'] == '1') {
					
					if ($note ['images'] != null && $note ['images'] != "") {
						foreach ( $note ['images'] as $image ) {
							$html .= '<a target="_blank" class="open_file2" href=" ' . $image ['notes_file_url'] . '">';
							$html .= $image ['keyImageSrc'];
							$html .= '</a>';
							if ($image ['media_user_id'] != null && $image ['media_user_id'] != "0") {
								$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;';
								$html .= $image ['media_user_id'];
								if ($image ['notes_type'] == "1") {
									$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								} else {
									if ($image ['media_pin'] != null && $image ['media_pin'] != "") {
										$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									} else {
										if ($image ['media_signature'] != null && $image ['media_signature'] != "") {
											$html .= '<img src=" ' . $image ['media_signature'] . '" width="98px" height="29px"> ';
										}
									}
								}
								$html .= '( ' . $image ['media_date_added'] . ' )';
							}
						}
					}
				}
			} else {
				
				if ($note ['images'] != null && $note ['images'] != "") {
					foreach ( $note ['images'] as $image ) {
						$html .= '<a target="_blank" class="open_file2" href=" ' . $image ['notes_file_url'] . '">';
						$html .= $image ['keyImageSrc'];
						$html .= '</a>';
						if ($image ['media_user_id'] != null && $image ['media_user_id'] != "0") {
							$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;';
							$html .= $image ['media_user_id'];
							if ($image ['notes_type'] == "1") {
								$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							} else {
								if ($image ['media_pin'] != null && $image ['media_pin'] != "") {
									$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
								} else {
									if ($image ['media_signature'] != null && $image ['media_signature'] != "") {
										$html .= '<img src=" ' . $image ['media_signature'] . '" width="98px" height="29px"> ';
									}
								}
							}
							$html .= '( ' . $image ['media_date_added'] . ' )';
						}
					}
				}
			}
			
			if ($note ['tag_privacy'] == "2") {
				if ($this->session->data ['unloack_success'] == '1') {
					if ($note ['geolocation_info'] ['travel_state'] == "0") {
						if ($note ['geolocation_info'] ['waypoint_google_url'] == null && $note ['geolocation_info'] ['waypoint_google_url'] == "") {
							if ($note ['geolocation_info'] ['google_url'] != null && $note ['geolocation_info'] ['google_url'] != "") {
								$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['google_url'] . '">';
								$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
								$html .= '</a>';
							}
						}
					}
					
					if ($note ['geolocation_info'] ['current_google_url'] != null && $note ['geolocation_info'] ['current_google_url'] != "") {
						$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['current_google_url'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/Current-Location-54-54.png" width="35px" height="35px">';
						$html .= '</a>';
					}
					
					if ($note ['geolocation_info'] ['travel_state'] == "0") {
						if ($note ['geolocation_info'] ['waypoint_google_url'] != null && $note ['geolocation_info'] ['waypoint_google_url'] != "") {
							$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['waypoint_google_url'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
							$html .= '</a>';
						}
					}
					
					if ($note ['geolocation_info'] ['travel_state'] == "1") {
						if ($note ['geolocation_info'] ['location_tracking_route'] != null && $note ['geolocation_info'] ['location_tracking_route'] != "") {
							$html .= '<a target="_blank" href="' . $routemap_url . '&notes_id=' . $note ['notes_id'] . '&travel_task_id=' . $note ['geolocation_info'] ['travel_task_id'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
							$html .= '</a>';
						}
						/*
						 * if($note['geolocation_info']['google_map_image_url'] !=
						 * null && $note['geolocation_info']['google_map_image_url']
						 * != ""){
						 * $html.='<a target="_blank"
						 * href="'.$note['geolocation_info']['google_map_image_url'].'">';
						 * $html.= '<img
						 * src="sites/view/digitalnotebook/image/map.png"
						 * width="35px" height="35px">';
						 * $html.='</a>';
						 * }
						 */
					}
				}
			} else {
				if ($note ['geolocation_info'] ['travel_state'] == "0") {
					if ($note ['geolocation_info'] ['waypoint_google_url'] == null && $note ['geolocation_info'] ['waypoint_google_url'] == "") {
						if ($note ['geolocation_info'] ['google_url'] != null && $note ['geolocation_info'] ['google_url'] != "") {
							$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['google_url'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
							$html .= '</a>';
						}
					}
				}
				
				if ($note ['geolocation_info'] ['current_google_url'] != null && $note ['geolocation_info'] ['current_google_url'] != "") {
					$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['current_google_url'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/Current-Location-54-54.png" width="35px" height="35px">';
					$html .= '</a>';
				}
				
				if ($note ['geolocation_info'] ['travel_state'] == "0") {
					if ($note ['geolocation_info'] ['waypoint_google_url'] != null && $note ['geolocation_info'] ['waypoint_google_url'] != "") {
						$html .= '<a target="_blank" href=" ' . $note ['geolocation_info'] ['waypoint_google_url'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
						$html .= '</a>';
					}
				}
				
				if ($note ['geolocation_info'] ['travel_state'] == "1") {
					if ($note ['geolocation_info'] ['location_tracking_route'] != null && $note ['geolocation_info'] ['location_tracking_route'] != "") {
						$html .= '<a target="_blank" href="' . $routemap_url . '&notes_id=' . $note ['notes_id'] . '&travel_task_id=' . e ['geolocation_info'] ['travel_task_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/map.png" width="35px" height="35px">';
						$html .= '</a>';
					}
					
					/*
					 * if($note['geolocation_info']['google_map_image_url'] != null
					 * && $note['geolocation_info']['google_map_image_url'] != ""){
					 * $html.='<a target="_blank"
					 * href="'.$note['geolocation_info']['google_map_image_url'].'">';
					 * $html.= '<img src="sites/view/digitalnotebook/image/map.png"
					 * width="35px" height="35px">';
					 * $html.='</a>';
					 * }
					 */
				}
			}
			
			if ($note ['tag_privacy'] == "2") {
				if ($this->session->data ['unloack_success'] == '1') {
					
					if ($note ['approvaltask'] != null && $note ['approvaltask'] != "") {
						if ($note ['approvaltask'] ['approval_taskid'] != null && $note ['approvaltask'] ['approval_taskid'] != "") {
							$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&task_id=' . $note ['approvaltask'] ['approval_taskid'] . '">';
							$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
							$html .= '</a> ';
						}
					}
					
					if ($note ['is_approval_required_forms_id'] > 0) {
						
						$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&is_approval_required_forms_id=' . $note ['is_approval_required_forms_id'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
				}
			} else {
				
				if ($note ['approvaltask'] != null && $note ['approvaltask'] != "") {
					if ($note ['approvaltask'] ['approval_taskid'] != null && $note ['approvaltask'] ['approval_taskid'] != "") {
						$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&task_id=' . $note ['approvaltask'] ['approval_taskid'] . '">';
						$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
						$html .= '</a> ';
					}
				}
				
				if ($note ['is_approval_required_forms_id'] > 0) {
					
					$html .= '<a class="form_insert1" id="form1333_' . $note ['notes_id'] . '" href="' . $approval_url . '&notes_id=' . $note ['notes_id'] . '&is_approval_required_forms_id=' . $note ['is_approval_required_forms_id'] . '">';
					$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="">';
					$html .= '</a> ';
				}
			}
			
			if ($note ['alltag'] ['user_id'] != null && $note ['alltag'] ['user_id'] != "") {
				$html .= '| Tags Updated By ';
				$html .= $note ['alltag'] ['user_id'];
				
				if ($note ['alltag'] ['notes_type'] == "2") {
					$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
				} elseif ($note ['alltag'] ['notes_type'] == "1") {
					
					$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
				} elseif ($note ['alltag'] ['notes_pin'] != null && $note ['alltag'] ['notes_pin'] != "") {
					
					$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
				} else {
					if ($note ['alltag'] ['signature'] != null && $note ['alltag'] ['signature'] != "") {
						$html .= '<img src="' . $note ['alltag'] ['signature'] . ' " width="98px" height="29px">';
					}
				}
				
				$html .= '( ' . date ( $date_format, strtotime ( $note ['alltag'] ['date_added'] ) ) . ' )';
			}
			
			if ($note ['form_type'] == '5') {
				
				$html .= '<a target="_blank"  href="' . $inventory_check_out_url . /*'&user_id=' . $note['is_inventory_checkout_id'] .*/ '&notes_id=' . $note ['notes_id'] . ' ">';
				$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
				$html .= '</a> ';
			}
			
			if ($note ['form_type'] == '6') {
				
				$html .= '<a  target="_blank" href="' . $inventory_check_in_url . /*'&user_id=' . $note['is_inventory_checkin_id'] .*/ '&notes_id=' . $note ['notes_id'] . ' ">';
				$html .= '<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=" ' . $csspadding . '">';
				$html .= '</a> ';
			}
			
			if ($note ['is_user_face'] == "2") {
				$html .= '<a target="_blank" href="' . $note ['user_file'] . '">';
				$html .= '<img src="sites/view/digitalnotebook/image/face-recognization-1.png" width="35px" height="35px"> ';
				$html .= '</a>';
			}
			
			if ($note ['is_user_face'] == "1") {
				$html .= '<a target="_blank" href="' . $note ['user_file'] . '">';
				$html .= '<img src="sites/view/digitalnotebook/image/face-recognization-0.png" width="35px" height="35px"> ';
				$html .= '</a>';
			}
			
			/*
			 * if ($this->customer->isdisplay_attachment() == "1") {
			 * if ($note['user_file'] != null && $note['user_file'] != "") {
			 * $html .= '<a target="_blank" href="' . $note['user_file'] . '">';
			 * $html .= '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px">';
			 * $html .= '</a>';
			 * }
			 * }
			 */
			
			$html .= '<div class="process_bar" id="progressbar' . $note ['notes_id'] . '"><div id="status' . $note ['notes_id'] . '" style="line-height: 15px;color: #fff;">0%</div></div>';
			
			$html .= '</span>';
			
			if (! empty ( $note ['notescomments'] )) {
				$html .= '<div class="comts">
						<hr>
						<span class="ss"><img src="sites/view/digitalnotebook/image/Comments-54-54.png" width="35px" height="35px">Comment</span>
						<br>';
				
				foreach ( $note ['notescomments'] as $comment ) {
					foreach ( $comment ['commentskeywords'] as $commentkeyword ) {
						$html .= '<img src="' . $noteskeyword ['keyword_file_url'] . ' " width="35px" height="35px">';
					}
					$html .= $comment ['comment'];
					
					if ($comment ['user_id'] != null && $comment ['user_id'] != "0") {
						$html .= $comment ['user_id'];
						
						if ($comment ['notes_type'] == "2") {
							$html .= '<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
						} elseif ($comment ['notes_type'] == "1") {
							
							$html .= '<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
						} elseif ($comment ['user_id'] == SYSTEM_GENERATED) {
							
							$html .= '<img src="sites/view/digitalnotebook/image/Logo-36x36.png" width="35px" height="35px">';
						} elseif ($comment ['notes_pin'] != null && $comment ['notes_pin'] != "") {
							
							$html .= '<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
						} else {
							if ($comment ['signature'] != null && $comment ['signature'] != "") {
								$html .= '<img src=" ' . $comment ['signature'] . ' " width="98px" height="29px">';
							}
						}
						
						$html .= '( ' . $comment ['date_added'] . ' )';
					}
				}
				
				$html .= '</div>';
			}
			
			$html .= '</div>';
			
			$html .= '</div>';
			$html .= '</td>';
			
			$html .= '<';
			$html .= 'script>';
			
			$html .= '$("#form1333_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			$html .= '$("#form1_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			$html .= '$("#form_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			
			$html .= '$("#is_tag_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			$html .= '$("#is1_tag_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			$html .= '$("#is11_tag_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			$html .= '$("#is_census_' . $note ['notes_id'] . '").colorbox({iframe:true, width:"85%", height:"90%", overlayClose: false});';
			
			$html .= " $(\'#notes_text" . $note ['notes_id'] . "\').on( \'keyup\', \'textarea\', function (e){ ";
			$html .= "     $(this).css(\'height\', \'auto\' );";
			
			$html .= '  });';
			$html .= " $(\'#notes_text" . $note ['notes_id'] . "\').find( \'textarea\' ).keyup();";
			$html .= "  $(\'#notes_description" . $note ['notes_id'] . "\').attr(\'readonly\',\'readonly\');";
			
			$html .= '<\/script>';
			
			$html .= '</tr>';
		}
		
		return $html;
	}
	public function getLastNotesID($facilities_id, $searchdate) {
		if ($searchdate != null && $searchdate != "") {
			
			$date = str_replace ( '-', '/', $searchdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			$startDate = $changedDate; /*
			                            * date('Y-m-d',
			                            * strtotime($data['searchdate']));
			                            */
			/* $endDate = date('Y-m-d'); */
			$endDate = $changedDate; /*
			                          * date('Y-m-d',
			                          * strtotime($data['searchdate']));
			                          */
			
			$sql .= " and ( `date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
		} else {
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$startDate = date ( 'Y-m-d' );
			$endDate = date ( 'Y-m-d' );
			
			$sql .= " and `date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59' ";
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$sql = "SELECT  * from `" . DB_PREFIX . "notes` WHERE status=1 and unique_id = '" . $unique_id . "' " . $sql . " ORDER BY notes_id DESC limit 0,1  ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function updatedate($notes_id, $update_date) {
		$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql12 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['update_date'] = $update_date;
		$this->model_activity_activity->addActivitySave ( 'updatedate', $adata, 'query' );
	}
	public function getcustomlists($data = array()) {
		$sql = "SELECT customlist_id,customlist_name,is_gender FROM " . DB_PREFIX . "customlist";
		
		$sql .= " where 1 = 1 and status = '1' ";
		
		if ($data ['customlist_id'] != null && $data ['customlist_id'] != "") {
			$sql .= " and customlist_id in (" . $data ['customlist_id'] . ") ";
		}
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getcustomlistvalues($data = array()) {
		$sql = "SELECT customlistvalues_id,customlistvalues_name,customlist_id,relation_keyword_id,number,gender FROM " . DB_PREFIX . "customlistvalues";
		
		$sql .= " where 1 = 1 and status = '1' ";
		
		if ($data ['customlistvalueids'] != null && $data ['customlistvalueids'] != "") {
			$sql .= " and customlist_id in (" . $data ['customlistvalueids'] . ") ";
		}
		
		if ($data ['customlistvalues_id'] != null && $data ['customlistvalues_id'] != "") {
			$sql .= " and customlistvalues_id in (" . $data ['customlistvalues_id'] . ") ";
		}
		
		if ($data ['customlist_id'] != null && $data ['customlist_id'] != "") {
			$sql .= " and customlist_id = '" . $data ['customlist_id'] . "' ";
		}
		if ($data ['customlist_name'] != null && $data ['customlist_name'] != "") {
			$sql .= " and customlistvalues_name LIKE '%" . $data ['customlist_name'] . "%' ";
		}
		if ($data ['current_date_user'] != null && $data ['current_date_user'] != "") {
			$sql2 .= " and `date_updated` BETWEEN  '" . $data ['current_date_user'] . " 00:00:00' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
		//var_dump($sql);die;
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getcustomlistvaluesReplica($data = array()) {
		$sql = "SELECT customlistvalues_id,customlistvalues_name AS gender_name,gender FROM " . DB_PREFIX . "customlistvalues";
		
		$sql .= " where 1 = 1 and status = '1' ";
		
		if ($data ['customlistvalueids'] != null && $data ['customlistvalueids'] != "") {
			$sql .= " and customlist_id in (" . $data ['customlistvalueids'] . ") ";
		}
		
		if ($data ['customlistvalues_id'] != null && $data ['customlistvalues_id'] != "") {
			$sql .= " and customlistvalues_id in (" . $data ['customlistvalues_id'] . ") ";
		}
		
		if ($data ['customlist_id'] != null && $data ['customlist_id'] != "") {
			$sql .= " and customlist_id = '" . $data ['customlist_id'] . "' ";
		}
		if ($data ['customlist_name'] != null && $data ['customlist_name'] != "") {
			$sql .= " and customlistvalues_name LIKE '%" . $data ['customlist_name'] . "%' ";
		}
		if ($data ['current_date_user'] != null && $data ['current_date_user'] != "") {
			$sql .= " and `date_updated` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getcustomlistvalue($customlistvalues_id) {
		$sql = "SELECT customlistvalues_id,customlistvalues_name,customlist_id,relation_keyword_id,number,gender FROM `" . DB_PREFIX . "customlistvalues` WHERE customlistvalues_id = '" . $customlistvalues_id . "'  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getcustomlist($customlist_id) {
		$sql = "SELECT customlist_id,customlist_name,is_gender FROM `" . DB_PREFIX . "customlist` WHERE customlist_id = '" . $customlist_id . "'  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getnotecustomlistvalue($customlistvalues_id, $parent_id) {
		// $sql = "SELECT * FROM `" . DB_PREFIX . "notes` WHERE
		// customlistvalues_id = '".$customlistvalues_id."' and parent_id =
		// '".$parent_id."' ";
		$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE parent_id = '" . $parent_id . "'  ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getnotecustomlistvalue2($customlistvalues_id, $parent_id, $start, $limit) {
		// $sql = "SELECT * FROM `" . DB_PREFIX . "notes` WHERE
		// customlistvalues_id = '".$customlistvalues_id."' and parent_id =
		// '".$parent_id."' ";
		$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE parent_id = '" . $parent_id . "'  LIMIT " . ( int ) $start . ', ' . ( int ) $limit . " ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getNoteform($data = array()) {
		$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE is_tag = '" . $data ['tags_id'] . "' and form_type = '" . $data ['form_type'] . "'  order by date_added DESC LIMIT 0,1  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getNotebyactivenote($data) {
		$sql = "SELECT notes_by_keyword_id,notes_id,keyword_id,keyword_name,keyword_file,keyword_file_url,keyword_status,active_tag,facilities_id,date_added,is_monitor_time,user_id,override_monitor_time_user_id FROM `" . DB_PREFIX . "notes_by_keyword` WHERE 1=1  ";
		if ($data ['keyword_id'] != null && $data ['keyword_id'] != "") {
			$sql .= " and keyword_id = '" . $data ['keyword_id'] . "' ";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and facilities_id = '" . $data ['facilities_id'] . "' ";
		}
		
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$sql .= " and notes_id = '" . $data ['notes_id'] . "' ";
		}
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$sql .= " and user_id = '" . $data ['user_id'] . "' ";
		}
		if ($data ['is_monitor_time'] == "1") {
			$sql .= " and is_monitor_time = '1' ";
		}
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getNotebyactivenotes($data) {
		$sql = "SELECT notes_by_keyword_id,notes_id,keyword_id,keyword_name,keyword_file,keyword_file_url,keyword_status,active_tag,facilities_id,date_added,is_monitor_time,user_id,override_monitor_time_user_id FROM `" . DB_PREFIX . "notes_by_keyword` WHERE 1=1  ";
		
		if ($data ['keyword_id'] != null && $data ['keyword_id'] != "") {
			$sql .= " and keyword_id = '" . $data ['keyword_id'] . "' ";
		}
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and facilities_id = '" . $data ['facilities_id'] . "' ";
		}
		
		if ($data ['keyword_ids'] != null && $data ['keyword_ids'] != "") {
			$sql .= " and keyword_id in ('" . $data ['keyword_ids'] . "')";
		}
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$sql .= " and notes_id = '" . $data ['notes_id'] . "' ";
		}
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$sql .= " and user_id = '" . $data ['user_id'] . "' ";
		}
		if ($data ['is_monitor_time'] == "1") {
			$sql .= " and is_monitor_time = '1' ";
		}
		if ($data ['date_added'] != null && $data ['date_added'] != "") {
			$sql .= " and `date_added` BETWEEN  '" . $data ['date_added'] . " 00:00:00' AND  '" . $data ['date_added'] . " 23:59:59' ";
		}
		
		// echo $sql;
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getGeolocation($notes_id) {
		$sql = "SELECT travel_task_id,notes_id,keyword_id,pickup_locations_address,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_latitude,dropoff_locations_longitude,current_locations_address,current_locations_latitude,current_locations_longitude,facilities_id,date_added,type,google_url,current_google_url,tags_id,travel_state,location_tracking_url,location_tracking_route,waypoint_google_url,google_map_image_url,pickup_locations_address_2,pickup_locations_latitude_2,pickup_locations_longitude_2,dropoff_locations_address_2,dropoff_locations_latitude_2,dropoff_locations_longitude_2,pick_up_tags_id,is_pick_up,is_drop_off FROM `" . DB_PREFIX . "notes_by_travel_task` WHERE notes_id = '" . $notes_id . "'  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getGeolocationbyid($notes_id, $travel_task_id) {
		$sql = "SELECT travel_task_id,notes_id,keyword_id,pickup_locations_address,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_latitude,dropoff_locations_longitude,current_locations_address,current_locations_latitude,current_locations_longitude,facilities_id,date_added,type,google_url,current_google_url,tags_id,travel_state,location_tracking_url,location_tracking_route,waypoint_google_url,google_map_image_url,pickup_locations_address_2,pickup_locations_latitude_2,pickup_locations_longitude_2,dropoff_locations_address_2,dropoff_locations_latitude_2,dropoff_locations_longitude_2,pick_up_tags_id,is_pick_up,is_drop_off FROM `" . DB_PREFIX . "notes_by_travel_task` WHERE notes_id = '" . $notes_id . "'  and travel_task_id = '" . $travel_task_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getapprovaltask($task_id) {
		$sql = "SELECT approval_taskid FROM `" . DB_PREFIX . "notes_by_approval_task` WHERE status='1' and approval_taskid = '" . $task_id . "'  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getApprovaltasklist($task_id) {
		$sql = "SELECT id,facilities_id,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_facilities_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_facilities_id,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,device_id,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,notes_id,status,iswaypoint,original_task_time,bed_check_location_ids from `" . DB_PREFIX . "notes_by_approval_task` WHERE 1 = 1 and status='1' ";
		$sql .= " and ( approval_taskid = '" . $task_id . "' ) ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function updatenoteform($notes_id) {
		$sql12ff = "UPDATE `" . DB_PREFIX . "notes` SET is_forms = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'";
		$this->db->query ( $sql12ff );
	}
	public function updatenotecontent($notes_description2, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$this->model_activity_activity->addActivitySave ( 'updatenotecontent', $adata, 'query' );
	}
	public function updatenotesparentnotification($parent_id, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET parent_id = '" . $this->db->escape ( $parent_id ) . "', snooze_dismiss = '2', form_snooze_dismiss = '2', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['parent_id'] = $parent_id;
		$this->model_activity_activity->addActivitySave ( 'updatenotesparentnotification', $adata, 'query' );
	}
	public function updatedatecount($notes_id, $update_date) {
		$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET update_date = '" . $update_date . "', notes_conut='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql1 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['update_date'] = $update_date;
		$this->model_activity_activity->addActivitySave ( 'updatedatecount', $adata, 'query' );
	}
	public function updatetnotes_by_keywordm($data = array()) {
		$sqlda2d = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET is_monitor_time = '0' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and keyword_id = '" . ( int ) $data ['keyword_id'] . "' and notes_by_keyword_id = '" . ( int ) $data ['notes_by_keyword_id'] . "' and facilities_id = '" . ( int ) $data ['facilities_id'] . "' ";
		
		$this->db->query ( $sqlda2d );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['keyword_id'] = $data ['keyword_id'];
		$adata ['notes_by_keyword_id'] = $data ['notes_by_keyword_id'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$this->model_activity_activity->addActivitySave ( 'updatetnotes_by_keywordm', $adata, 'query' );
	}
	public function updatetnotes_by_keywordo($data = array()) {
		if ($data ['override_monitor_time_user_id'] != null && $data ['override_monitor_time_user_id'] != "") {
			$sqldaff = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET override_monitor_time_user_id = '" . $this->db->escape ( $data ['override_monitor_time_user_id'] ) . "' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and facilities_id = '" . ( int ) $data ['facilities_id'] . "' and keyword_id = '" . ( int ) $data ['keyword_id'] . "' ";
			
			$this->db->query ( $sqldaff );
		}
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			
			$this->load->model ( 'user/user' );
			// $user_info = $this->model_user_user->getUser ( $data ['user_id'] );
			if ($data ['user_id'] != null && $data ['user_id'] != "") {
				$user_info = $this->model_user_user->getUserByUsername ( $data ['user_id'] );
			} else {
				$user_info = $this->model_user_user->getUserByUsernamebynotes ( $data ['username'], $data ['facilities_id'] );
			}
			
			$sqlda = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET user_id = '" . $this->db->escape ( $user_info ['username'] ) . "', is_monitor_time = '1' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and facilities_id = '" . ( int ) $data ['facilities_id'] . "' and keyword_id = '" . ( int ) $data ['keyword_id'] . "' ";
			
			$this->db->query ( $sqlda );
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['keyword_id'] = $data ['keyword_id'];
		$adata ['override_monitor_time_user_id'] = $data ['override_monitor_time_user_id'];
		$adata ['user_id'] = $data ['user_id'];
		$adata ['username'] = $data ['username'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$this->model_activity_activity->addActivitySave ( 'updatetnotes_by_keywordo', $adata, 'query' );
	}
	public function updatetnotesassign($data = array()) {
		$sqldaffn = "UPDATE `" . DB_PREFIX . "notes` SET assign_to = '" . $this->db->escape ( $data ['assign_to'] ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and facilities_id = '" . ( int ) $data ['facilities_id'] . "' ";
		$this->db->query ( $sqldaffn );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['assign_to'] = $data ['assign_to'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$this->model_activity_activity->addActivitySave ( 'updatetnotesassign', $adata, 'query' );
	}
	public function updatenotes_by_keyword_detail($data = array()) {
		$sqld22a2 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET keyword_id = '" . $this->db->escape ( $data ['keyword_id'] ) . "', keyword_name = '" . $this->db->escape ( $data ['keyword_name'] ) . "', keyword_file = '" . $this->db->escape ( $data ['keyword_file'] ) . "',keyword_file_url='" . $this->db->escape ( $data ['keyword_file_url'] ) . "' WHERE notes_id = '" . ( int ) $data ['notes_id'] . "' and keyword_id = '" . ( int ) $data ['keyword_id_2'] . "' and facilities_id = '" . ( int ) $data ['facilities_id'] . "' ";
		
		$this->db->query ( $sqld22a2 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['keyword_id'] = $data ['keyword_id'];
		$adata ['keyword_name'] = $data ['keyword_name'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$this->model_activity_activity->addActivitySave ( 'updatenotes_by_keyword_detail', $adata, 'query' );
	}
	public function updatenotetask($parent_id, $notes_id) {
		$qsll = "UPDATE `" . DB_PREFIX . "notes` SET parent_id = '" . $parent_id . "', task_type = '5', notes_conut ='0' where notes_id = '" . $notes_id . "'";
		$this->db->query ( $qsll );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['parent_id'] = $parent_id;
		$this->model_activity_activity->addActivitySave ( 'updatenotetask', $adata, 'query' );
	}
	public function updatenotetaskbedcheck($notes_task_type, $update_date, $notes_id) {
		$sqlu = "UPDATE `" . DB_PREFIX . "notes` SET task_type = '" . $notes_task_type . "', update_date = '" . $update_date . "', notes_conut='0' where notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sqlu );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['update_date'] = $update_date;
		$adata ['notes_task_type'] = $notes_task_type;
		$this->model_activity_activity->addActivitySave ( 'updatenotetaskbedcheck', $adata, 'query' );
	}
	public function updatetnotestravel($result, $data) {
		$sqlc1 = "UPDATE `" . DB_PREFIX . "notes` SET task_type = '3', notes_conut ='0'
		, task_id = '" . $result ['id'] . "'
		, task_date = '" . $result ['task_date'] . "'
		, parent_id = '" . $result ['parent_id'] . "'
		, tasktype= '" . $data ['task_id'] . "'
		, assign_to= '" . $this->db->escape ( $result ['assign_to'] ) . "'
		, task_time = '" . $data ['notetasktime'] . "'
		, snooze_dismiss = '2'
		, form_snooze_dismiss = '2'
		, taskadded = '" . $data ['taskDuration'] . "'
		, customlistvalues_id = '" . $data ['customlistvalues_id'] . "'
		, recurrence= '" . $this->db->escape ( $result ['recurrence'] ) . "'
		, task_date = '" . $this->db->escape ( $result ['date_added'] ) . "'
		, task_group_by = '" . $this->db->escape ( $result ['task_group_by'] ) . "'
		, end_task = '" . $this->db->escape ( $result ['end_task'] ) . "' 
		  where notes_id = '" . ( int ) $data ['notes_id'] . "' ";
		$this->db->query ( $sqlc1 );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['task_id'] = $result ['id'];
		$adata ['tasktype'] = $result ['task_id'];
		$adata ['parent_id'] = $result ['parent_id'];
		$adata ['assign_to'] = $result ['assign_to'];
		$adata ['notetasktime'] = $result ['notetasktime'];
		$adata ['customlistvalues_id'] = $result ['customlistvalues_id'];
		$adata ['date_added'] = $result ['date_added'];
		$adata ['task_group_by'] = $result ['task_group_by'];
		$adata ['end_task'] = $result ['end_task'];
		$this->model_activity_activity->addActivitySave ( 'updatetnotestravel', $adata, 'query' );
	}
	public function updatetnotestravel_location($result, $data) {
		if ($data ['facilities_id']) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			$unique_id = $facility ['customer_key'];
		}
		
		$sqll = "INSERT INTO `" . DB_PREFIX . "notes_by_travel_task` SET 
		facilities_id = '" . $data ['facilities_id'] . "',
		notes_id = '" . $data ['notes_id'] . "',
		type = '" . $data ['task_id'] . "',
		travel_state = '1',
		pickup_locations_address = '" . $this->db->escape ( $data ['pickup_locations_address'] ) . "',
		pickup_locations_latitude = '" . $this->db->escape ( $data ['pickup_locations_latitude'] ) . "',
		pickup_locations_longitude = '" . $this->db->escape ( $data ['pickup_locations_longitude'] ) . "',
		
		dropoff_locations_address = '" . $this->db->escape ( $data ['dropoff_locations_address'] ) . "',
		dropoff_locations_latitude= '" . $this->db->escape ( $data ['dropoff_locations_latitude'] ) . "',
		dropoff_locations_longitude = '" . $this->db->escape ( $data ['dropoff_locations_longitude'] ) . "',
		
		google_url = '" . $this->db->escape ( $data ['google_url'] ) . "',
		
		current_locations_address = '" . $this->db->escape ( $current_locations_address ) . "',
		current_locations_latitude = '" . $this->db->escape ( $data ['current_lat'] ) . "',
		current_locations_longitude = '" . $this->db->escape ( $data ['current_log'] ) . "',
		
		current_google_url = '" . $this->db->escape ( $data ['current_google_url'] ) . "',
		
		location_tracking_url = '" . $this->db->escape ( $data ['location_tracking_url'] ) . "',
		location_tracking_route = '" . $this->db->escape ( $data ['location_tracking_route'] ) . "',
		location_tracking_time_start = '" . $this->db->escape ( $data ['location_tracking_time_start'] ) . "',
		location_tracking_time_end = '" . $this->db->escape ( $data ['location_tracking_time_end'] ) . "',
		
		date_added = '" . $this->db->escape ( $data ['date_added'] ) . "',
		tags_id = '" . $this->db->escape ( $data ['tags_ids11'] ) . "',
		waypoint_google_url = '" . $this->db->escape ( $data ['waypoint_google_url'] ) . "',
		google_map_image_url = '" . $this->db->escape ( $data ['google_map_image_url'] ) . "',
		keyword_id = '" . $this->db->escape ( $data ['keyword_id'] ) . "',
		
		pickup_locations_address_2 = '" . $this->db->escape ( $data ['pickup_locations_address_2'] ) . "',
		pickup_locations_latitude_2 = '" . $this->db->escape ( $data ['pickup_locations_latitude_2'] ) . "',
		pickup_locations_longitude_2 = '" . $this->db->escape ( $data ['pickup_locations_longitude_2'] ) . "',
		
		dropoff_locations_address_2 = '" . $this->db->escape ( $data ['dropoff_locations_address_2'] ) . "',
		dropoff_locations_latitude_2= '" . $this->db->escape ( $data ['dropoff_locations_latitude_2'] ) . "',
		dropoff_locations_longitude_2 = '" . $this->db->escape ( $data ['dropoff_locations_longitude_2'] ) . "',
		pick_up_tags_id = '" . $this->db->escape ( $data ['pick_up_tags_id'] ) . "',
		is_drop_off = '" . $this->db->escape ( $data ['is_drop_off'] ) . "',
		is_pick_up = '" . $this->db->escape ( $data ['is_pick_up'] ) . "',
		unique_id = '" . $this->db->escape ( $unique_id ) . "'
		
		";
		
		$this->db->query ( $sqll );
		
		$travel_task_id = $this->db->getLastId ();
		
		$sqlc = "UPDATE `" . DB_PREFIX . "notes_by_travel_task_coordinates` SET travel_task_id = '" . $this->db->escape ( $travel_task_id ) . "', notes_id = '" . $data ['notes_id'] . "' where task_id = '" . ( int ) $result ['id'] . "' ";
		$this->db->query ( $sqlc );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['travel_task_id'] = $travel_task_id;
		$adata ['tasktype'] = $data ['task_id'];
		$adata ['pickup_locations_address'] = $data ['pickup_locations_address'];
		$adata ['dropoff_locations_address'] = $data ['dropoff_locations_address'];
		$adata ['google_url'] = $data ['google_url'];
		$adata ['current_locations_address'] = $current_locations_address;
		$adata ['current_google_url'] = $data ['current_google_url'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$adata ['date_added'] = $data ['date_added'];
		$adata ['tags_ids11'] = $data ['tags_ids11'];
		$adata ['pick_up_tags_id'] = $data ['pick_up_tags_id'];
		$adata ['is_drop_off'] = $data ['is_drop_off'];
		$adata ['is_pick_up'] = $data ['is_pick_up'];
		$this->model_activity_activity->addActivitySave ( 'updatetnotestravel_location', $adata, 'query' );
	}
	public function updatetagsassign1($tags_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET is_archive = '2',notes_conut='0' where is_tag = '" . ( int ) $tags_id . "' and form_type = '3' and is_archive = '0' ";
		$this->db->query ( $sql );
	}
	public function updatetagsassign23($tags_id, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET form_type = '3', notes_conut ='0', is_tag = '" . ( int ) $tags_id . "' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
	}
	public function updatenotetags_med($notes_id) {
		$sql = "update `" . DB_PREFIX . "notes` set task_type = '2', notes_conut ='0' where notes_id='" . $notes_id . "'";
		$this->db->query ( $sql );
	}
	public function updatetagsmedicinearchive1($tags_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET is_archive = '1',notes_conut='0' where is_tag = '" . ( int ) $tags_id . "' and form_type = '1' and is_archive = '0' ";
		$this->db->query ( $sql );
	}
	public function updatetagsmedicinearchive2($data = array()) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET form_type = '1', notes_conut ='0', is_tag = '" . ( int ) $data ['tags_id'] . "' where notes_id = '" . ( int ) $data ['notes_id'] . "' ";
		$this->db->query ( $sql );
		
		$sqla = "UPDATE `" . DB_PREFIX . "archive_tags_medication` SET notes_id = '" . $data ['notes_id'] . "' where archive_tags_medication_id = '" . ( int ) $data ['archive_tags_medication_id'] . "' ";
		$this->db->query ( $sqla );
		
		$sqla22 = "UPDATE `" . DB_PREFIX . "archive_tags_medication_details` SET notes_id = '" . $data ['notes_id'] . "' where archive_tags_medication_id = '" . ( int ) $data ['archive_tags_medication_id'] . "' ";
		$this->db->query ( $sqla22 );
		
		$sqla22s = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET status = '1' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
		$this->db->query ( $sqla22s );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['tags_id'] = $data ['tags_id'];
		$adata ['archive_tags_medication_id'] = $data ['archive_tags_medication_id'];
		$this->model_activity_activity->addActivitySave ( 'updatetagsmedicinearchive2', $adata, 'query' );
	}
	public function updateclient_status($notes_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '1', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'" );
	}
	public function updatetagscences($notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET is_census = '1', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
	}
	public function updatetagscences2($notes_description2, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', is_census = '1', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['notes_description'] = $notes_description2;
		$this->model_activity_activity->addActivitySave ( 'updatetagscences2', $adata, 'query' );
	}
	public function updatenotesrule($notes_id) {
		$sqlw = "update `" . DB_PREFIX . "notes` set form_trigger_snooze_dismiss = '2',snooze_dismiss = '2', form_create_task = '1', notes_conut ='0' where notes_id ='" . $notes_id . "'";
		$this->db->query ( $sqlw );
	}
	public function updatenotesruletask($notes_id) {
		$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', snooze_dismiss = '2', form_create_task = '1', notes_conut ='0' where notes_id ='" . $notes_id . "'";
		$this->db->query ( $sqlw );
	}
	public function getnotes_by_form($notes_id) {
		$sqls2 = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes`";
		$sqls2 .= 'where 1 = 1 ';
		$sqls2 .= " and notes_id = '" . $allnotesId ['notes_id'] . "'";
		$sqls2 .= " and form_send_sms = '0'";
		
		$query = $this->db->query ( $sqls2 );
		
		return $query->row;
	}
	public function getnotes_by_form2($notes_id) {
		$sqls2 = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,is_approval_required_forms_id,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes`";
		$sqls2 .= 'where 1 = 1 ';
		$sqls2 .= " and notes_id = '" . $allnotesId ['notes_id'] . "'";
		$sqls2 .= " and form_send_email = '0'";
		
		$query = $this->db->query ( $sqls2 );
		
		return $query->row;
	}
	public function updateuserpicture($user_file, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET user_file = '" . $this->db->escape ( $user_file ) . "', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['user_file'] = $user_file;
		$this->model_activity_activity->addActivitySave ( 'updateuserpicture', $adata, 'query' );
	}
	public function updateuserverified($is_user_face, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET is_user_face = '" . $this->db->escape ( $is_user_face ) . "', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['is_user_face'] = $is_user_face;
		$this->model_activity_activity->addActivitySave ( 'updateuserverified', $adata, 'query' );
	}
	public function updateuserpicturestrick($user_file, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET strike_user_file = '" . $this->db->escape ( $user_file ) . "', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['strike_user_file'] = $user_file;
		$this->model_activity_activity->addActivitySave ( 'updateuserpicturestrick', $adata, 'query' );
	}
	public function updateuserverifiedstrick($is_user_face, $notes_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET strike_is_user_face = '" . $this->db->escape ( $is_user_face ) . "', notes_conut ='0' where notes_id = '" . ( int ) $notes_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['strike_is_user_face'] = $is_user_face;
		$this->model_activity_activity->addActivitySave ( 'updateuserverifiedstrick', $adata, 'query' );
	}
	public function getTagpickup($tags_id, $is_pick_up, $task_group_by) {
		$query2 = $this->db->query ( "SELECT notes_id FROM " . DB_PREFIX . "notes where task_group_by = '" . $task_group_by . "' " );
		
		$results = $query2->rows;
		
		$userIds2 = array ();
		foreach ( $results as $result ) {
			if ($result ['notes_id'] != null && $result ['notes_id'] != "") {
				$userIds2 [] = $result ['notes_id'];
			}
		}
		$userIds12 = array_unique ( $userIds2 );
		
		$userIds21 = implode ( ',', $userIds12 );
		
		$sqls21 = "SELECT travel_task_id,notes_id,keyword_id,pickup_locations_address,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_latitude,dropoff_locations_longitude,current_locations_address,current_locations_latitude,current_locations_longitude,facilities_id,date_added,type,google_url,current_google_url,tags_id,travel_state,pick_up_tags_id,is_pick_up,is_drop_off FROM `" . DB_PREFIX . "notes_by_travel_task` ";
		$sqls21 .= " where 1 = 1 ";
		$sqls21 .= " and FIND_IN_SET('" . $tags_id . "', pick_up_tags_id) ";
		$sqls21 .= " and is_pick_up = '" . $is_pick_up . "' ";
		
		$sqls21 .= " and notes_id in (" . $userIds21 . ") ";
		
		$query = $this->db->query ( $sqls21 );
		
		return $query->row;
	}
	public function getexistnotes($data, $facilities_id) {
		$sqle = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,is_user_face,user_file,is_approval_required_forms_id,device_unique_id,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE facilities_id = '" . ( int ) $facilities_id . "' and phone_device_id = '" . $this->db->escape ( $data ['phone_device_id'] ) . "' and device_unique_id = '" . $this->db->escape ( $data ['device_unique_id'] ) . "' ";
		
		// echo $sqle;
		$query = $this->db->query ( $sqle );
		
		return $query->row;
	}
	public function updateuserpicturenotestag($user_file, $notes_id, $notes_tags_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes_tags` SET user_file = '" . $this->db->escape ( $user_file ) . "' where notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['user_file'] = $user_file;
		$adata ['notes_tags_id'] = $notes_tags_id;
		$this->model_activity_activity->addActivitySave ( 'updateuserpicturenotestag', $adata, 'query' );
	}
	public function updateuserverifiednotestag($is_user_face, $notes_id, $notes_tags_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes_tags` SET is_user_face = '" . $this->db->escape ( $is_user_face ) . "' where notes_id = '" . ( int ) $notes_id . "' and notes_tags_id = '" . ( int ) $notes_tags_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['is_user_face'] = $is_user_face;
		$adata ['notes_tags_id'] = $notes_tags_id;
		$this->model_activity_activity->addActivitySave ( 'updateuserverifiednotestag', $adata, 'query' );
	}
	public function updateuserpicturenotesform($user_file, $notes_id, $forms_id) {
		$sql = "UPDATE `" . DB_PREFIX . "forms` SET user_file = '" . $this->db->escape ( $user_file ) . "' where notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $forms_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['user_file'] = $user_file;
		$adata ['forms_id'] = $forms_id;
		$this->model_activity_activity->addActivitySave ( 'updateuserpicturenotesform', $adata, 'query' );
	}
	public function updateuserverifiednotesform($is_user_face, $notes_id, $forms_id) {
		$sql = "UPDATE `" . DB_PREFIX . "forms` SET is_user_face = '" . $this->db->escape ( $is_user_face ) . "' where notes_id = '" . ( int ) $notes_id . "' and forms_id = '" . ( int ) $forms_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['is_user_face'] = $is_user_face;
		$adata ['forms_id'] = $forms_id;
		$this->model_activity_activity->addActivitySave ( 'updateuserverifiednotesform', $adata, 'query' );
	}
	public function updateuserpicturenotesmedia($user_file, $notes_id, $notes_media_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes_media` SET user_file = '" . $this->db->escape ( $user_file ) . "' where notes_id = '" . ( int ) $notes_id . "' and notes_media_id = '" . ( int ) $notes_media_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['user_file'] = $user_file;
		$adata ['notes_media_id'] = $notes_media_id;
		$this->model_activity_activity->addActivitySave ( 'updateuserpicturenotesmedia', $adata, 'query' );
	}
	public function updateuserverifiednotesmedia($is_user_face, $notes_id, $notes_media_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes_media` SET is_user_face = '" . $this->db->escape ( $is_user_face ) . "' where notes_id = '" . ( int ) $notes_id . "' and notes_media_id = '" . ( int ) $notes_media_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['is_user_face'] = $is_user_face;
		$adata ['notes_media_id'] = $notes_media_id;
		$this->model_activity_activity->addActivitySave ( 'updateuserverifiednotesmedia', $adata, 'query' );
	}
	public function getnotesBytask($notes_by_task_id) {
		$sql = "SELECT notes_by_task_id,notes_id,locations_id,task_type, task_content,	user_id,date_added,signature,notes_pin,notes_type,task_time,media_url,capacity,location_name,location_type,	notes_task_type,tags_id,drug_name,dose,drug_type,quantity,frequency,instructions,count,createtask_by_group_id,task_comments,medication_attach_url,medication_file_upload,facilities_id,tags_medication_id,tags_medication_details_id,task_customlistvalues_id,tags_ids,room_current_date_time,out_tags_ids,role_call,out_capacity,refuse FROM `" . DB_PREFIX . "notes_by_task` WHERE notes_by_task_id = '" . ( int ) $notes_by_task_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getformnotesbyUser($data, $facilities_id, $timezone_name) {
		$sql = "select n.* from `" . DB_PREFIX . "notes` n ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and n.status = '1' ";
		
		$sql .= " and n.user_id = '" . $data ['user_id'] . "'";
		
		$sql .= " and n.is_forms = '1' ";
		
		if ($facilities_id != null && $facilities_id != "") {
			$sql .= " and n.facilities_id = '" . $facilities_id . "'";
		}
		
		/*
		 * if ($data['reviewed_by'] != "3") {
		 * if ($data['user_id'] != null && $data['user_id'] != "") {
		 * $sql .= " and n.user_id = '" . $data['user_id'] . "'";
		 * }
		 * }
		 */
		
		if ($data ['activenote'] != null && $data ['activenote'] != "") {
			$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
			$keydata = $query2->row;
			
			$sql .= " and nk.keyword_file = '" . $keydata ['keyword_image'] . "'";
		}
		
		if ($data ['reviewed_by'] != null && $data ['reviewed_by'] != "") {
			
			if ($data ['reviewed_by'] == '1') {
				$sql .= " and n.review_notes    = '0'";
			}
			
			if ($data ['reviewed_by'] == '3') {
				
				date_default_timezone_set ( $timezone_name );
				
				$date = str_replace ( '-', '/', $data ['date_from'] );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$startDate = $changedDate;
				// $endDate = date('Y-m-d');
				$endDate = $changedDate;
				
				$sql .= " and (n.`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
			}
		}
		
		// $sql = "SELECT * FROM `" . DB_PREFIX . "notes` WHERE status = '1' and
		// facilities_id = '" . $facilities_id . "' ".$sql2." ORDER BY notes_id
		// DESC LIMIT 1; ";
		
		$sql .= " ORDER BY notes_id DESC LIMIT 1";
		
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function getShiftColor($notetime, $facilities_id) {
		
		// $sql.="SELECT * FROM `" . DB_PREFIX . "shift` WHERE '".$notetime."' BETWEEN `shift_starttime` AND `shift_endtime`";
		$sql1 = "";
		if ($facilities_id != null && $facilities_id != "") {
			
			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $facility_info ['customer_key'] );
			$customer_key = $customer_info ['activecustomer_id'];
			$sql1 = "AND customer_key='" . $customer_key . "'";
		}
		
		$sql .= "SELECT * FROM `" . DB_PREFIX . "shift` WHERE (`shift_starttime` < `shift_endtime` AND '" . $notetime . "' BETWEEN `shift_starttime` AND `shift_endtime`) OR (`shift_endtime` < `shift_starttime` AND '" . $notetime . "' <= `shift_starttime` AND '" . $notetime . "' <= `shift_endtime`) OR (`shift_endtime` < `shift_starttime` AND '" . $notetime . "' > `shift_starttime`) " . $sql1 . "";
		
		// $sqlshift = "SELECT * FROM `" . DB_PREFIX . "shift` where shift_starttime > '".$notetime."' and shift_endtime < '".$notetime."' ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getupdatetime($notes_id) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes_by_time` WHERE notes_id = '" . $notes_id . "' order by date_added DESC limit 0,1 ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getupdatetimes($notes_id) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes_by_time` WHERE notes_id = '" . $notes_id . "' ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getupdatetimesa($notes_id) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes_by_time` WHERE notes_id = '" . $notes_id . "' order by date_added ASC limit 0,1 ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getTagcasses() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "case` WHERE status = '1' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getFormcaseId($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "case`";
		
		$sql .= 'where 1 = 1 ';
		
		if ($data ['from_id'] != null && $data ['from_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['from_id'] . "', forms) ";
		}
		if ($data ['case_id'] != null && $data ['case_id'] != "") {
			$sql .= " and case_id = '" . $data ['case_id'] . "' ";
		}
		
		$q = $this->db->query ( $sql );
		return $q->row;
	}
	public function getshift($shift_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "shift`";
		
		$sql .= 'where 1 = 1 ';
		$sql .= " and shift_id = '" . $shift_id . "' ";
		
		$q = $this->db->query ( $sql );
		return $q->row;
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
	public function getAllShiftNotes($data) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "shift` WHERE '" . $data ['notetime'] . "' BETWEEN `shift_starttime` AND `shift_endtime`";
		
		$query = $this->db->query ( $sql );
		
		$shift_time = $query->row;
		
		if ($shift_time) {
			
			$sql1 .= "SELECT * FROM `" . DB_PREFIX . "notes` WHERE `notetime` >= '" . $shift_time ['shift_starttime'] . "' AND `notetime` <= '" . $shift_time ['shift_endtime'] . "' AND form_type = '0' AND DATE(`date_added`) = CURDATE() AND facilities_id='" . $data ['facilities_id'] . "' ";
		}
		
		$q = $this->db->query ( $sql1 );
		return $q->rows;
	}
	public function getcaseCategories() {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "case_category` WHERE status=1 ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getcases($case_category_id) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "case` WHERE status=1 and case_category_id = '" . $case_category_id . "' ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getnotesmultiKey($notes_id) {
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes_by_multikeyword` WHERE notes_id = '" . $notes_id . "' ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getAllFormNotes($data = array()) {
		if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
			
			$startTimeFrom = date ( 'Y-m-d H:i:s', strtotime ( $data ['search_time_start'] ) );
			$startTimeTo = date ( 'Y-m-d H:i:s', strtotime ( $data ['search_time_to'] ) );
		}
		
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes` WHERE `date_added`>='" . $startTimeFrom . "' AND `date_added`<='" . $startTimeTo . "'";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getInventoryNoteform($data = array()) {
		$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,notes_pin,notes_file,date_added,user_id,signature,signature_image,notetime,note_date,text_color_cut,text_color,strike_user_id,strike_date_added,strike_signature,strike_signature_image,strike_pin,keyword_file,highlighter_value,keyword_file_url,taskadded,task_time,assign_to,emp_tag_id,notes_type,checklist_status,snooze_time,snooze_dismiss,send_sms,send_email,notes_search_keword,tags_id,strike_note_type,audio_attach_url,task_type,medication_attach_url,update_date,is_private,is_private_strike,assessment_id,review_notes,share_notes,rule_highlighter_task,rule_activenote_task,rule_color_task,rule_keyword_task,is_offline,notes_conut,tasktype,visitor_log,task_id,task_date,parent_id,end_perpetual_task,recurrence,customlistvalues_id,generate_report,is_android,is_census,is_tag,form_type,tagstatus_id,task_group_by,end_task,form_snooze_dismiss,form_send_sms,form_send_email,form_snooze_time,form_create_task,form_alert_send_email,form_alert_send_sms,sync_records,is_archive,original_task_time,is_forms,is_reminder,linked_id,in_total,out_total,manual_total FROM `" . DB_PREFIX . "notes` WHERE is_tag = '" . $data ['tags_id'] . "' and form_type = '" . $data ['form_type'] . "'  order by date_added DESC  ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getNotesTagscallls($data = array()) {
		$sql = 'SELECT DISTINCT
		n.* 
		FROM
		`dg_notes` n 
		
		LEFT JOIN dg_notes_tags nt 
		ON nt.notes_id = n.notes_id 
		LEFT JOIN dg_notes_by_keyword nk 
		ON nk.notes_id = n.notes_id 
		WHERE 1 = 1 AND n.status = 1 ';
		
		if ($data ['movecount'] == null && $data ['movecount'] == "") {
			$sql .= " and nt.move_notes_id=0  ";
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
			if ($data ['search_facilities_id'] == $data ['facilities_id']) {
				$ddss [] = $facilityinfo ['notes_facilities_ids'];
				$ddss [] = $data ['facilities_id'];
				
				$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
				
				$abdcg = array_unique ( $sssssddsg );
				$cids = array ();
				foreach ( $abdcg as $fid ) {
					$cids [] = $fid;
				}
				$abdcgs = array_unique ( $cids );
				foreach ( $abdcgs as $fid2 ) {
					$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
					if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
						$ddss [] = $facilityinfo ['notes_facilities_ids'];
					}
				}
				
				$sssssdd = implode ( ",", $ddss );
				$faculities_ids = $sssssdd;
				$sql .= ' AND FIND_IN_SET(n.facilities_id, "' . $faculities_ids . '")';
			} else {
				if ($data ['search_facilities_id'] != null && $data ['search_facilities_id'] != "") {
					$facilities_id = $this->db->escape ( $data ['search_facilities_id'] );
				} else {
					$ddss [] = $facilityinfo ['notes_facilities_ids'];
					$ddss [] = $data ['facilities_id'];
					
					$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
					
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['notes_facilities_ids'];
						}
					}
					
					$sssssdd = implode ( ",", $ddss );
					$faculities_ids = $sssssdd;
					$sql .= ' AND FIND_IN_SET(n.facilities_id, "' . $faculities_ids . '")';
				}
			}
		} else {
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$facilities_id = $this->db->escape ( $data ['facilities_id'] );
				$sql .= ' AND FIND_IN_SET(n.facilities_id, "' . $facilities_id . '")';
			}
		}
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$sql .= " AND n.user_id = '" . $this->db->escape ( $data ['user_id'] ) . "' ";
		}
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "") {
			$sql .= " AND nt.tags_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "' ";
		}
		
		if ($data ['movecount'] != null && $data ['movecount'] != "") {
			$sql .= " and nt.tag_status_id IN (" . $data ['movecount'] . ") ";
		}
		
		if ($data ['activenote'] != null && $data ['activenote'] != "") {
			$query2 = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . ( int ) $data ['activenote'] . "'" );
			
			$keydata = $query2->row;
			
			$n_keyword_file = $keydata ['keyword_image'];
			
			$sql .= " AND nk.keyword_file = '" . $this->db->escape ( $n_keyword_file ) . "' ";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			
			if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
				
				$startTimeFrom = date ( 'H:i:s', strtotime ( $data ['search_time_start'] ) );
				$startTimeTo = date ( 'H:i:s', strtotime ( $data ['search_time_to'] ) );
				
				$startTimeFrom_o = '00:00:00';
				$startTimeTo_o = '23:59:59';
				
				$n_note_date_from_t = $startDate . " " . $startTimeFrom_o;
				$n_note_date_to_t = $endDate . " " . $startTimeTo_o;
				$n_note_date_from_time = $startTimeFrom;
				$n_note_date_to_time = $startTimeTo;
				
				// $sql .= " and (n.`notetime` BETWEEN '".$startTimeFrom."' AND
				// '".$startTimeTo."') ";
			} else {
				$startTimeFrom = '00:00:00';
				$startTimeTo = '23:59:59';
				
				$n_note_date_from = $startDate . " " . $startTimeFrom;
				$n_note_date_to = $endDate . " " . $startTimeTo;
			}
			
			$sql .= " and ( n.`date_added` BETWEEN '" . $startDate . " " . $startTimeFrom . "' AND '" . $endDate . " " . $startTimeTo . "' ) ";
		}
		
		// $sql .= 'and ( n.`date_added` BETWEEN "'.$data['note_date_from'].' 00:00:00" AND "'.$data['note_date_to'].' 23:59:59" )';
		
		$sql .= '  ORDER BY  n.date_added ASC LIMIT 0, 1000';
		// echo $sql;
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getNotesTags222($tags_id) {
		$sql = "SELECT notes_tags_id,	emp_tag_id,	tags_id,	notes_id,	user_id,	date_added,	signature,	signature_image,	notes_pin,	notes_type,	facilities_id,	is_census,	lunch,	dinner,	breakfast,	refused,tag_status_id,tag_classification_id,forms_id,move_notes_id,forms_id,status_total_time,manual_movement FROM `" . DB_PREFIX . "notes_tags` WHERE tags_id = '" . ( int ) $tags_id . "'  and move_notes_id>0 order by date_added DESC ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getNotesTagsin($tags_id, $defaultrole_call) {
		$sql = "SELECT notes_tags_id,	emp_tag_id,	tags_id,	notes_id,	user_id,	date_added,	signature,	signature_image,	notes_pin,	notes_type,	facilities_id,	is_census,	lunch,	dinner,	breakfast,	refused,tag_status_id,tag_classification_id,forms_id,move_notes_id,forms_id,status_total_time,manual_movement FROM `" . DB_PREFIX . "notes_tags` WHERE tags_id = '" . ( int ) $tags_id . "' and tag_status_id = '" . ( int ) $defaultrole_call . "'  order by date_added DESC ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	
	public function getAllShift($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "shift` WHERE customer_key = '" . $data ['customer_key'] . "' and facilities_id = '" . $data ['facilities_id'] . "'  order by date_added ASC";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	
	public function getNotesTags2($notes_id) {
		$sql = "SELECT notes_tags_id,	emp_tag_id,	tags_id,	notes_id,	user_id,	date_added,	signature,	signature_image,	notes_pin,	notes_type,	facilities_id,	is_census,	lunch,	dinner,	breakfast,	refused,tag_status_id,tag_classification_id,forms_id,move_notes_id,forms_id,status_total_time,manual_movement FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . ( int ) $notes_id . "' order by date_added DESC ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
}
?>