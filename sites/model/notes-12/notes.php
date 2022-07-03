<?php
class Modelnotesnotes extends Model {
	public function addnotes($data, $facilities_id) {
		
			/*date_default_timezone_set($this->session->data['time_zone_1']);*/
			
			$createdate1 = $data['note_date'];
			/*
			$createtime1 = date('H:i:s');
			$createDate2 = $createdate1 . $createtime1;
			*/
			$createDate = date('Y-m-d H:i:s',strtotime($createdate1));
			
			/*$createDate = $createDate;*///date('Y-m-d H:i:s',strtotime('now'));
			
			date_default_timezone_get();
			$timezone_name = $this->customer->isTimezone();
			
			$timeZone = date_default_timezone_set($timezone_name);
			
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
			 
			
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			
			
			if($this->config->get('config_time_picker') == '0'){
				$noteTime = date('H:i:s', strtotime('now'));
			}else{
				$noteTime = date('H:i:s', strtotime($notesdata['notetime']));
			}
			
		echo $sql = "INSERT INTO `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape($notesdata['notes_description']) . "', highlighter_id = '" . $this->db->escape($data['highlighter_id']) . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "', notes_file = '" . $this->db->escape($data['notes_file']) . "', user_id = '" . $this->db->escape($data['user_id']) . "', status = '1', notetime = '" . $noteTime . "', signature = '" . $imageUrl . "', signature_image = '" . $fileName . "', text_color_cut = '" . $this->db->escape($data['text_color_cut']) . "', text_color = '" . $this->db->escape($data['text_color']) . "', date_added = '".$createDate."',  note_date = '" .$noteDate. "',  global_utc_timezone = UTC_TIMESTAMP( ), keyword_file = '" .$this->db->escape($data['keyword_file']). "' ";
		
		
		
		$this->db->query($sql);
		
		$order_id = $this->db->getLastId();
	
		die;
	}
	
	
	
	
	public function addreview($data, $facilities_id, $add_date) {
			date_default_timezone_set($this->session->data['time_zone_1']);
			
			$date = str_replace('-', '/', $add_date);
			
			$res = explode("/", $date);
			$createdate1 = $res[2]."-".$res[0]."-".$res[1];
			/*$createdate1 = $add_date;*/
			$createtime1 = date('H:i:s');
			$createDate2 = $createdate1 . $createtime1;
		
		if($add_date!= null && $add_date!=""){
				$createDate = date('Y-m-d H:i:s',strtotime($createDate2));	
			}else{
				$createDate = date('Y-m-d H:i:s',strtotime('now'));
			}
			
			
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			
			$timezone_name = $this->customer->isTimezone();
			
			
			date_default_timezone_set($timezone_name);
			
			
			
			if($add_date!= null && $add_date!=""){
				$noteDate = date('Y-m-d H:i:s',strtotime($createDate2));	
			}else{
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			}
		
	
		$this->db->query("INSERT INTO `" . DB_PREFIX . "reviewed_by` SET facilities_id = '" . $facilities_id . "', user_id = '" . $this->db->escape($data['user_id']) . "', signature = '" . $imageUrl . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "',  note_date = '" .$noteDate. "', signature_image = '" . $fileName . "', date_added = '".$createDate."' ");
		
	}
	
	public function updateNoteColor($notes_id, $text_color){
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '#".$text_color."' WHERE notes_id = '" . (int)$notes_id . "' ";
		$this->db->query($sql);
	}
	
	public function updateNoteHigh($notes_id, $highlighter_id){
			$sql = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '".$highlighter_id."' WHERE notes_id = '" . (int)$notes_id . "' ";
			$this->db->query($sql);
		}
	
	
	public function updateStrikeNotes($data, $notes_id, $facilities_id){
		
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
			
			
		$this->db->query("UPDATE `" . DB_PREFIX . "notes` SET text_color_cut = '1', strike_user_id = '" . $this->db->escape($data['user_id']) . "', strike_signature = '" . $imageUrl . "', strike_signature_image = '" . $fileName . "', strike_pin = '" . $this->db->escape($data['notes_pin']) . "', strike_date_added = '".$noteDate."' WHERE notes_id = '" . (int)$notes_id . "' and facilities_id = '".$facilities_id."' ");
	}
	
	public function editnotes($notes_id, $data, $facilities_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape($data['notes_description']) . "', highlighter_id = '" . $this->db->escape($data['highlighter_id']) . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "', notes_file = '" . $this->db->escape($data['notes_file']) . "', user_id = '" . $this->db->escape($data['user_id']) . "', status = '1', notetime = '" . $data['notetime'] . "' WHERE notes_id = '" . (int)$notes_id . "'");
		
	}

			
	public function deletenotes($notes_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "notes` WHERE notes_id = '" . (int)$notes_id . "'");
	}
	
	public function getnotes($notes_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "notes` WHERE notes_id = '" . (int)$notes_id . "'");
	
		return $query->row;
	}
	
	public function getnotess($data = array()) {
		
		date_default_timezone_set($this->session->data['time_zone_1']);
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes`";
		
		
		$sql .= 'where 1 = 1 ';
		if ($data['keyword'] != null && $data['keyword'] != "") {
			$sql .= " and LOWER(notes_description) like '%".strtolower($data['keyword'])."%'";
			//$sql .= " or notes_description like '%".strtolower($data['keyword'])."%'";
			//$sql .= " or notes_description like '%".strtoupper($data['keyword'])."%'";
		}
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and user_id = '".$data['user_id']."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."'";
		}
		
		/*if (!empty($data['note_date_from'])) {
			$sql .= " AND DATE(notetime) >= '" . $this->db->escape($data['note_date_from']) . "'";
		}

		if (!empty($data['note_date_to'])) {
			$sql .= " AND DATE(notetime) <= '" . $this->db->escape($data['note_date_to']) . "'";
		}*/
		if (($data['note_date_from'] != null && $data['note_date_from'] != "") && ($data['note_date_to'] != null && $data['note_date_to'] != "")) {
			$startDate = date('Y-m-d', strtotime($data['note_date_from']));
			$endDate = date('Y-m-d', strtotime($data['note_date_to']));
			
			//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
			
			$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
		}
		
		
		/*if ($data['advance_search'] != "1" && $data['advance_searchapp'] != "1") {
			if(($data['note_date_from'] == null && $data['note_date_from'] == "") && ($data['note_date_to'] == null && $data['note_date_to'] == "") && ($data['user_id'] == null && $data['user_id'] == "") && ($data['keyword'] == null && $data['keyword'] == "") && ($data['searchdate'] == null && $data['searchdate'] == "")){
				
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				
				$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
			}
		}*/
		
		if($data['searchdate_app'] == '1'){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				$date = str_replace('-', '/', $data['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
				$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				/*$endDate = date('Y-m-d');*/
				$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				
				$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
			}else{
				if($data['advance_searchapp'] != '1' && $data['advance_search'] != '1'){
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				
				$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
				}
			}
		}
		
		if($data['searchdate_app'] != "1"){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
			
				$startDate = date('Y-m-d', strtotime($data['searchdate']));
				/*$endDate = date('Y-m-d');*/
				$endDate = date('Y-m-d', strtotime($data['searchdate']));
				
				$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
			}
		}
		
		
		
		$sql .= " and status = '1' ";
		
		
		if($data['searchdate_app'] == "1"){
			if ($data['advance_searchapp'] == "1") {
				$sql .= " ORDER BY date_added ASC";
			}else{
				$sql .= " ORDER BY notetime ASC";
			}
		}else{
			if ($data['advance_search'] == "1") {
				$sql .= " ORDER BY date_added ASC";
			}else{
				$sql .= " ORDER BY notetime ASC";
			}
		}
		
		if($data['advance_searchapp'] == '1'){
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		}
		
		if($data['advance_search'] == '1'){
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		}
		
		
		
		//echo "<hr>";
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function getTotalnotess($data = array()) {
		
		date_default_timezone_set($this->session->data['time_zone_1']);
		
		$sql .= 'where 1 = 1 ';
		if ($data['keyword'] != null && $data['keyword'] != "") {
			$sql .= " and LOWER(notes_description) like '%".strtolower($data['keyword'])."%'";
			//$sql .= " or notes_description like '%".strtolower($data['keyword'])."%'";
			//$sql .= " or notes_description like '%".strtoupper($data['keyword'])."%'";
		}
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and user_id = '".$data['user_id']."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."'";
		}
		
		/*if (!empty($data['note_date_from'])) {
			$sql .= " AND DATE(notetime) >= '" . $this->db->escape($data['note_date_from']) . "'";
		}

		if (!empty($data['note_date_to'])) {
			$sql .= " AND DATE(notetime) <= '" . $this->db->escape($data['note_date_to']) . "'";
		}*/
		if (($data['note_date_from'] != null && $data['note_date_from'] != "") && ($data['note_date_to'] != null && $data['note_date_to'] != "")) {
			$startDate = date('Y-m-d', strtotime($data['note_date_from']));
			$endDate = date('Y-m-d', strtotime($data['note_date_to']));
			
			$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
		}
		
		
		if ($data['advance_search'] != "1" && $data['advance_searchapp'] != "1") {
			if(($data['note_date_from'] == null && $data['note_date_from'] == "") && ($data['note_date_to'] == null && $data['note_date_to'] == "") && ($data['user_id'] == null && $data['user_id'] == "") && ($data['keyword'] == null && $data['keyword'] == "") && ($data['searchdate'] == null && $data['searchdate'] == "")){
				
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				
				$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
			}
		}
		
		if($data['searchdate_app'] == '1'){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				$date = str_replace('-', '/', $data['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
				$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				/*$endDate = date('Y-m-d');*/
				$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				
				$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
			}else{
				//$startDate = date('Y-m-d');
				//$endDate = date('Y-m-d');
				
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
			}
		}
		
		if($data['searchdate_app'] != "1"){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
			
				$startDate = date('Y-m-d', strtotime($data['searchdate']));
				/*$endDate = date('Y-m-d');*/
				$endDate = date('Y-m-d', strtotime($data['searchdate']));
				
				$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
			}
		}
		
		
		
		$sql .= " and status = '1' ";
		
		
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` ".$sql." ");
		
		return $query->row['total'];
	}

	
	public function jsonaddnotes($data, $facilities_id) {
			
			$createdate1 = $data['note_date'];
			$createtime1 = date('H:i:s');
			$createDate2 = $createdate1 . $createtime1;
			$createDate = date('Y-m-d H:i:s',strtotime($createDate2));
			
			
			$timezone_name = $data['facilitytimezone'];
			
			
			date_default_timezone_set($timezone_name);
			
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			/*$noteDate = $data['date_added'];*/
			
			if($data['imgOutput'] != null && $data['imgOutput'] != ""){
			
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			
			}
			
			$date_added = $data['date_added'];
			$note_date = $data['note_date'];
			
			$createDate = date('Y-m-d H:i:s',strtotime($note_date));
			
			if($this->config->get('config_time_picker') == '0'){
				$noteTime = date('H:i:s', strtotime('now'));
			}else{
				$noteTime = date('H:i:s', strtotime($data['notetime']));
			}
			
			
			  $sql = "INSERT INTO `" . DB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape($data['notes_description']) . "', highlighter_id = '" . $this->db->escape($data['highlighter_id']) . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "', notes_file = '" . $this->db->escape($data['notes_file']) . "', user_id = '" . $this->db->escape($data['user_id']) . "', status = '1', notetime = '" . $noteTime . "', signature = '" . $imageUrl . "', signature_image = '" . $fileName . "', text_color_cut = '" . $this->db->escape($data['text_color_cut']) . "', text_color = '" . $this->db->escape($data['text_color']) . "', date_added = '".$createDate."',  note_date = '" .$noteDate. "',  global_utc_timezone = UTC_TIMESTAMP( ) ";
			
			
			$this->db->query($sql);
	}
	
	
	public function jsonupdateStrikeNotes($data, $facilities_id){
		
			if($data['imgOutput'] != null && $data['imgOutput'] != ""){
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			}
			
			$timezone_name = $data['facilitytimezone'];
			
			
			date_default_timezone_set($timezone_name);
			
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
			$note_date = $data['note_date'];
			
			$createDate = date('Y-m-d H:i:s',strtotime($note_date));
			
			  $sql = "UPDATE `" . DB_PREFIX . "notes` SET text_color_cut = '1', strike_user_id = '" . $this->db->escape($data['user_id']) . "', strike_signature = '" . $imageUrl . "', strike_signature_image = '" . $fileName . "', strike_pin = '" . $this->db->escape($data['notes_pin']) . "', strike_date_added = '".$noteDate."' WHERE notes_id = '" . (int)$data['notes_id'] . "' and facilities_id = '".$facilities_id."' ";
			
		
		$this->db->query($sql);
	}
	
	public function jsonaddreview($data, $facilities_id) {
		
			if($data['imgOutput'] != null && $data['imgOutput'] != ""){
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			}
			
			$timezone_name = $data['facilitytimezone'];
			
			
			date_default_timezone_set($timezone_name);
			
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
			/*$date_added = $data['date_added'];*/
			
			$node_date = $data['note_date'];
			
			$createDate = date('Y-m-d H:i:s',strtotime($node_date));
			
			 $sql = "INSERT INTO `" . DB_PREFIX . "reviewed_by` SET facilities_id = '" . $facilities_id . "', user_id = '" . $this->db->escape($data['user_id']) . "', signature = '" . $imageUrl . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "', date_added = '" . $createDate . "', signature_image = '" . $fileName . "', note_date = '".$noteDate."' ";
			
		$this->db->query($sql);
		
	}
	
	
	public function jsonaddreviewbyID($data, $facilities_id) {
		
			if($data['imgOutput'] != null && $data['imgOutput'] != ""){
			$img = $data['imgOutput'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			
			$fileName = uniqid() . '.png';
			
			$file = DIR_IMAGE .'/signature/' . $fileName;
			$success = file_put_contents($file, $Imgdata);
			/*print $success ? $file : 'Unable to save the file.';*/
			
			$imageUrl = HTTP_SERVER .'image/signature/'. $fileName;
			}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "reviewed_by` SET facilities_id = '" . $facilities_id . "', user_id = '" . $this->db->escape($data['user_id']) . "', signature = '" . $imageUrl . "', notes_pin = '" . $this->db->escape($data['notes_pin']) . "', signature_image = '" . $fileName . "', notes_id = '" . $data['notes_id'] . "', date_added = NOW() ");
		
	}
	
	
	public function jsongetreviews($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "reviewed_by`";
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getReview($searchdate) {
		
		if ($searchdate != null && $searchdate != "") {
			$startDate = date('Y-m-d', strtotime($searchdate));
			/*$endDate = date('Y-m-d');*/
			$endDate = date('Y-m-d', strtotime($searchdate));
			
			$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59')";
		}
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "reviewed_by` WHERE (`date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59') ");
	
		return $query->row;
	}
	
	public function jsongetReviewModel($data = array()) {
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "reviewed_by`";
		
		$sql .= 'where 1 = 1 ';
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."'";
		}
		if ($data['searchdate'] != null && $data['searchdate'] != "") {
			$startDate = date('Y-m-d', strtotime($data['searchdate']));
			/*$endDate = date('Y-m-d');*/
			$endDate = date('Y-m-d', strtotime($data['searchdate']));
			
			$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59')";
		}
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	
	public function getreviews($data = array()) {
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "reviewed_by`";
		
		$sql .= 'where 1 = 1 ';
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."'";
		}
		if ($data['searchdate'] != null && $data['searchdate'] != "") {
			/*$startDate = date('Y-m-d', strtotime($data['searchdate']));*/
			/*$endDate = date('Y-m-d');*/
			/*$endDate = date('Y-m-d', strtotime($data['searchdate']));
			*/
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
			$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
			/*$endDate = date('Y-m-d');*/
			$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
			
			$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59')";
		}else{
			$startDate = date('Y-m-d');
			$endDate = date('Y-m-d');
			$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
		}
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	public function jsonaddReminder($data, $facilities_id){
		$date_added = $data['date_added'];
			
		$createDate = date('Y-m-d H:i:s',strtotime($date_added));
			
		$sql = "INSERT INTO `" . DB_PREFIX . "reminder` SET facilities_id = '" . $facilities_id . "', notes_id = '" . $data['notes_id'] . "', reminder_time = '" . $this->db->escape($data['reminder_time']) . "', date_added = '" . $createDate . "' ";
		$this->db->query($sql);
	}
	
	public function jsonDeleteReminder($data){
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "reminder` WHERE notes_id = '" . $data['notes_id'] . "' and facilities_id = '" . $data['facilities_id'] . "' ");
		
	}
	
	
	public function addReminderModel($data, $facilities_id){
			$this->db->query("DELETE FROM `" . DB_PREFIX . "reminder` WHERE notes_id = '" . $data['notes_id'] . "' and facilities_id = '" . $facilities_id . "' ");
		$sql = "INSERT INTO `" . DB_PREFIX . "reminder` SET facilities_id = '" . $facilities_id . "', notes_id = '" . $data['notes_id'] . "', reminder_time = '" . $this->db->escape($data['reminder_time']) . "', reminder_title = '" . $this->db->escape($data['reminder_title']) . "', date_added = NOW() ";
		$this->db->query($sql);
	}
	
	public function updateReminderModel($data, $facilities_id){
			$sql = "UPDATE `" . DB_PREFIX . "reminder` SET reminder_time = '".$data['reminder_time']."' WHERE notes_id = '" . (int)$data['notes_id'] . "' and facilities_id = '" . (int)$facilities_id . "' ";
			$this->db->query($sql);
		}
	
	public function getReminder($notes_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "reminder` WHERE notes_id = '" . (int)$notes_id . "'";
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function updateNoteFile($notes_id, $notes_file){
			$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '".$notes_file."' WHERE notes_id = '" . (int)$notes_id . "' ";
			$this->db->query($sql);
		}
		
	
	public function getadvancednotess($data = array()) {
		
		//date_default_timezone_set($this->session->data['time_zone_1']);
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes`";
		
		
		$sql .= 'where 1 = 1 ';
		if ($data['keyword'] != null && $data['keyword'] != "") {
			$sql .= " and LOWER(notes_description) like '%".strtolower($data['keyword'])."%'";
		}
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and user_id = '".$data['user_id']."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."'";
		}
		
		if (($data['note_date_from'] != null && $data['note_date_from'] != "") && ($data['note_date_to'] != null && $data['note_date_to'] != "")) {
			$startDate = date('Y-m-d', strtotime($data['note_date_from']));
			$endDate = date('Y-m-d', strtotime($data['note_date_to']));
			
			$sql .= " and date_added BETWEEN '".$startDate." 00:00:00' AND  '".$startDate." 23:59:59'";
		}
		
		$sql .= " and status = '1'";
		
		$sql .= " ORDER BY date_added ASC";
				
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			
				
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		
		
		//echo "<hr>";
		$query = $this->db->query($sql);
	
		/*$boteData = array();
		if($query->rows){
			foreach($query->rows as $note){
				$boteData[] = array(
					'notes_id' => $note['notes_id'],
					'user_id' => $note['user_id'],
					'notetime' => $note['notetime'],
					'text_color_cut' => $note['text_color_cut'],
					'text_color' => $note['text_color'],
					'notes_description' => $note['notes_description']
				);
			}
		}*/
		return $query->rows;
	}
}
?>