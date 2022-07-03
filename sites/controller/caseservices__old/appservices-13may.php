<?php 
class Controllerservicesappservices extends Controller {  

	public function jsonFacilities(){
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$results = $this->model_facilities_facilities->getfacilitiess($data);
    	
		foreach ($results as $result) {
					
      		$this->data['facilitiess'][] = array(
				'facilities_id'    => $result['facilities_id'],
				'facility'   => $result['facility'],
				'firstname'   => $result['firstname'],
				'lastname'   => $result['lastname'],
				'email'   => $result['email'],
			);
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
	
	}
	
	
	public function jsonUsers(){
		$this->data['facilitiess'] = array();
		
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
		$this->load->model('user/user'); 
		$users = $this->model_user_user->getUsersByFacility($this->request->post['facilities_id']);
    	
		foreach ($users as $user) {
					
      		$this->data['facilitiess'][] = array(
				'user_id'    => $user['user_id'],
				'username'   => $user['username'],
				'firstname'   => $user['firstname'],
				'lastname'   => $user['lastname'],
				'user_pin'   => $user['user_pin'],
				'email'   => $user['email'],
			);
		}
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>"No User Found",'status'=>false);
		}
		
		$this->response->setOutput(json_encode($value));
	
	}
	
	public function jsonhighlighters(){ 
		$this->data['facilitiess'] = array();
		$this->load->model('setting/highlighter');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		$highlighters = $this->model_setting_highlighter->gethighlighters();
    	
		foreach ($highlighters as $highlighter) {
			
		if ($highlighter['highlighter_icon'] && file_exists(DIR_IMAGE . 'highlighter/'.$highlighter['highlighter_icon'])) {
				$file1 = '/highlighter/'.$highlighter['highlighter_icon'];
				$newfile4 = $this->model_setting_image->resize($file1, 70, 70);
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/highlighter/'.$newfile4;
						
				$imageData1 = base64_encode(file_get_contents($newfile21));
				$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
			}else{
				$strike_signature = '';
			}
					
      		$this->data['facilitiess'][] = array(
				'highlighter_id'    => $highlighter['highlighter_id'],
				'highlighter_name'   => $highlighter['highlighter_name'],
				'highlighter_value'   => $highlighter['highlighter_value'],
				'highlighter_icon'   => $strike_signature,
			);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
	
	}
	
	public function jsonhoursFunction() {
		$this->load->model('setting/hoursminutes');
		$this->data['facilitiess'] = array();
		$results = $this->model_setting_hoursminutes->hoursFunction();
		
		foreach($results as $key=>$result){
			$this->data['facilitiess'][] = array(
					'key_id'  => $key,
					'value'       => $result,
				);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
	}

	public function jsonminutesFunction() {
		$this->load->model('setting/hoursminutes');
		$results = $this->model_setting_hoursminutes->minutesFunction();
		$this->data['facilitiess'] = array();
		foreach($results as $key=>$result){
			$this->data['facilitiess'][] = array(
					'key_id'  => $key,
					'value'       => $result,
				);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
	}
	
	public function jsonFacilityLogin() {
		
		$this->data['facilitiess'] = array();
		
		/*$this->request->post['facility'] = 'test';
		$this->request->post['password'] = '123456';
		$this->request->post['ipaddress'] = '125';
		$this->request->post['http_host'] = 'servitium.com';
		$this->request->post['http_referer'] = 'servitium.com';
		*/
		
		$json = array();
		
		if (!$this->customer->apploginlogin($this->request->post['facility'], $this->request->post['password'], $this->request->post['ipaddress'])) {
			$json['warning'] = 'No match for Facility and/or Password.';
		}
		
		$this->load->model('facilities/facilities');
		
		$facility_info = $this->model_facilities_facilities->getfacilitiesByfacility($this->request->post['facility']);
			
		if ($facility_info && !$facility_info['status']) {
			$json['warning'] = 'Your account requires approval before you can login.';
		}
		
		if($facility_info['facilities_id'] != null && $facility_info['facilities_id'] != ""){
			$uquery = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . (int)$facility_info['facilities_id'] . "'");
		
			$facilityResult = $uquery->row;
			
			$users = $facilityResult['users'];
			
			/*if($users == null && $users == ""){
				$json['warning'] = 'You have not users Please create user';
			}else{ */
				$sql = "SELECT * FROM `" . DB_PREFIX . "user` ";
				$sql .= 'where 1 = 1 ';
				if ($facility_info['facilities_id'] != null && $facility_info['facilities_id'] != "") {
					$sql .= " and FIND_IN_SET('". $facility_info['facilities_id']."', facilities) ";
				}
				$query = $this->db->query($sql);
				$results = $query->rows;
				
				if((empty($results)) && ($users == null && $users == "")){
					$json['warning'] = 'You have not users Please create user';
				}
			/*}*/
		}
			
			
		if($json['warning'] == null && $json['warning'] == ""){
			
			if ($this->config->get('config_facility_online')) {
				$this->load->model('facilities/online');

				if (isset($this->request->post['ipaddress'])) {
					$ip = $this->request->post['ipaddress'];	
				} else {
					$ip = ''; 
				}

				if (isset($this->request->post['http_host'])) {
					$url = 'http://' . $this->request->post['http_host'];	
				} else {
					$url = ''; 
				}
				
				if (isset($this->request->post['http_referer'])) {
					$referer = $this->request->post['http_referer'];	
				} else {
					$referer = ''; 
				}

				
				$userId = $facility_info['facilities_id'];

				$this->model_facilities_online->whosonline($ip, $userId, $url, $referer);
			}
			
			$error = true;
			/*
			//$this->data['facilitiess'][]['facility'] = $facility_info['facility'];
			$this->data['facilitiess'][]['facilities_id'] = $facility_info['facilities_id'];
			*/
			
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facility_info['timezone_id']);
			
			if($this->config->get('config_date_picker') != null && $this->config->get('config_date_picker') != ""){
				$config_date_picker = $this->config->get('config_date_picker');
			}else{
				$config_date_picker = '0';
			}
			
			if($this->config->get('config_time_picker') != null && $this->config->get('config_time_picker') != ""){
				$config_time_picker = $this->config->get('config_time_picker');
			}else{
				$config_time_picker = '0';
			}
			
			$this->data['facilitiess'][] = array(
				'facility'  => $facility_info['facility'],
				'timezone_value'  => $timezone_info['timezone_value'],
				'facilities_id'       => $facility_info['facilities_id'],
				'config_date_picker'  => $config_date_picker,
				'config_time_picker'  => $config_time_picker,
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));		
	}
	
	public function jsonGetFacility(){
		$this->data['facilitiess'] = array();
		
		$this->load->model('facilities/facilities');
		/*$facilities_id = '5';*/
		$facilities_id = $this->request->get['facilities_id'];
		$facility_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		if($facility_info != null && $facility_info != ""){
			
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facility_info['timezone_id']);
			
			$this->load->model('setting/country');
			$country_info = $this->model_setting_country->getCountry($facility_info['country_id']);
			
			$this->load->model('setting/zone');
			$zone_info = $this->model_setting_zone->getZone($facility_info['country_id']);
			
			$error = true;
			$this->data['facilitiess'][] = array(
				'facility'  => $facility_info['facility'],
				'firstname'  => $facility_info['firstname'],
				'lastname'  => $facility_info['lastname'],
				'email'  => $facility_info['email'],
				'description'  => $facility_info['description'],
				'address'  => $facility_info['address'],
				'location'  => $facility_info['location'],
				'zipcode'  => $facility_info['zipcode'],
				'timezone_name'  => $facility_info['timezone_name'],
				'timezone_value'  => $facility_info['timezone_value'],
				'country_name'  => $facility_info['country_name'],
				'iso_code_2'  => $facility_info['iso_code_2'],
				'zone_name'  => $facility_info['zone_name'],
				'code'  => $facility_info['code'],
				'facilities_id'       => $facility_info['facilities_id'],
			);
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Not valid id',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
	}

	public function jsongetNotes() { 
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	} 

		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
		);
		
		$results = $this->model_notes_notes->getnotess($data);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		
		$keywords = $this->model_setting_keywords->getkeywords();
		$keyarray = array();
		foreach($keywords as $keyword){
			$keyarray[] = $keyword['keyword_name'];
		}
    	
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$user_info = $this->model_user_user->getUser($result['user_id']);
				$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
				
				if($highlighterData['highlighter_value'] != null && $highlighterData['highlighter_value'] != ""){
					$highlighter_value = $highlighterData['highlighter_value'];
				}else{
					$highlighter_value = '';
				}
				
				if($strikeuser_info['username'] != null && $strikeuser_info['username'] != ""){
					$strikeusername = $strikeuser_info['username'];
				}else{
					$strikeusername = '';
				}
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
				/************** for signature and password key icon image size 300, 55 **************/
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = '';
					
				}else				
				if($result['signature'] != null && $result['signature'] != ""){
					
					$file5 = DIR_IMAGE . '/signature/'.$result['signature_image'];
					$file = 'signature/'.$result['signature_image'];
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					//$newImage = $this->createThumbnail($file5);
					//var_dump($file1);
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = 'data: '.$this->mime_content_type($file1).';base64,'.$imageData;
					
					/*$signature = $result['signature'];*/
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$file13 = '/key.gif';
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = '';
				}else
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					
					$file13 = '/signature/'.$result['strike_signature_image'];
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
					/*$strikesignature = $result['strike_signature'];*/
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = $result['strike_pin'];
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = $result['notes_pin'];
				}else{
					$notesPin = '';
				}
				
				
				
				
				$matchData = $this->arrayInString( $keyarray , $result['notes_description']);
				if ($matchData != null && $matchData != "") {
					$dataKeyword = $matchData;
					$keywordData = $this->model_setting_keywords->getkeyword($dataKeyword);
				}else{
					$keywordData = "";
				}
					
				if ($keywordData['keyword_image'] && file_exists(DIR_IMAGE . 'icon/'.$keywordData['keyword_image'])) {
					
					$file16 = '/icon/'.$keywordData['keyword_image'];
					$newfile84 = $this->model_setting_image->resize($file16, 30, 30);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							
					$imageData132 = base64_encode(file_get_contents($newfile216));
					$keyword_icon = 'data: '.$this->mime_content_type($file124).';base64,'.$imageData132;
						
				} elseif($result['notes_file'] != null && $result['notes_file'] != ""){
					$extension = strtolower(end(explode(".", $result['notes_file'])));
					if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
						$keyImageSrc = 'img';
					}else
					if($extension == 'doc' || $extension == 'docx'){
						$keyImageSrc = 'doc';
					}else
					if($extension == 'ppt' || $extension == 'pptx'){
						$keyImageSrc = 'ppt';
					}else
					if($extension == 'xls' || $extension == 'xlsx'){
						$keyImageSrc = 'xls';
					}else
					if($extension == 'pdf'){
						$keyImageSrc = 'pdf';
					}else
					if($extension == 'txt'){
						$keyImageSrc = 'txt';
					}else{
						$keyImageSrc = '';
					}
					$keyword_icon = '';
				}else{
					$keyword_icon = '';
					$keyImageSrc = '';
				}
				
				
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$extension = strtolower(end(explode(".", $result['notes_file'])));
					if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
						$keyImageSrc = 'img';
					}else
					if($extension == 'doc' || $extension == 'docx'){
						$keyImageSrc = 'doc';
					}else
					if($extension == 'ppt' || $extension == 'pptx'){
						$keyImageSrc = 'ppt';
					}else
					if($extension == 'xls' || $extension == 'xlsx'){
						$keyImageSrc = 'xls';
					}else
					if($extension == 'pdf'){
						$keyImageSrc = 'pdf';
					}else
					if($extension == 'txt'){
						$keyImageSrc = 'txt';
					}else{
						$keyImageSrc = '';
					}
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = "";
				}
				
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				$this->data['facilitiess'][] = array(
					'notes_id'    => $result['notes_id'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => $result['notes_description'],
					'attachment_icon'   => $keyImageSrc,
					'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $user_info['username'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $result['text_color'],
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'strike_user_name'   => $strikeusername,
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => $reminderTitle,
					'reminder_time'   => $reminderTime,
					
					
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
	}
	
	public function jsongetColor(){
		$colors = array(
			"Black"  => "#000000",
			"Red" 	 => "#FF0000",
			"Green"  => "#008000",
			"Blue" 	 => "#0000FF",
		);
		$this->data['facilitiess'] = array();
		foreach($colors as $key=>$result){
			$this->data['facilitiess'][] = array(
					'key_id'  => $key,
					'value'       => $result,
				);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
	}
	
	public function mime_content_type($filename) {

        $mime_types = array(

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
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

	public function jsonAddNotes(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		
		if (!$this->request->post['notes_description']) {
			$json['warning'] = 'Please insert required!.';
		}
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['highlighter_id'] = $this->request->post['highlighter_id'];
			$data['notes_description'] = $this->request->post['notes_description'];
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $this->request->post['notetime'];
			$data['text_color'] = $this->request->post['text_color'];
			$data['note_date'] = $this->request->post['note_date'];

			$data['notes_file'] = $this->request->post['notes_file'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			$data['date_added'] = $this->request->post['date_added'];
			$this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
			);
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function jsonuploadFile(){

		$json = array();
		$this->data['facilitiess'] = array();
		
		if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){

			$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

			if($this->request->files["upload_file"]["size"] < 5002284){
				$neextension  = strtolower($extension);
				if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
					
					$notes_file = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
					
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $notes_file;
					
					$error = true;
					
					$this->data['facilitiess'][] = array(
						'success'  => '1',
						'notes_file'  => $notes_file,
						'notes_file_url'  => $outputFolderUrl,
					);
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'video or audio file not valid!',
					);
					$error = false;
				}
			}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'Maximum size file upload!',
					);
					$error = false;
				}

		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select file!',
			);
			$error = false;
		}

		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	public function jsonupdateText(){
		
		$this->load->model('notes/notes');
		
		$json = array();
		$this->data['facilitiess'] = array();
		if ($this->request->post['notes_id'] != null && $this->request->post['type'] == 'text') {
			
		
		$this->model_notes_notes->updateNoteColor($this->request->post['notes_id'], $this->request->post['text_color']);
		
		$error = true;
			
		$this->data['facilitiess'][] = array(
			'success'  => '1',
		);
		
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
			
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));

	}
	
	public function jsonupdateHighliter(){
		
		$this->load->model('notes/notes');
		
		$json = array();
		$this->data['facilitiess'] = array();
		
		if ($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != "") {
				
			$this->model_notes_notes->updateNoteHigh($this->request->post['notes_id'], $this->request->post['highlighter_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
			
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));

	}
	
	public function jsonUpdateStrike(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		
		
		if (!$this->request->post['notes_id']) {
			$json['warning'] = 'Please select text!.';
		}
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_id'] = $this->request->post['notes_id'];
			
			$data['note_date'] = $this->request->post['note_date'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			$this->model_notes_notes->jsonupdateStrikeNotes($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function jsonAddreview(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['note_date'] = $this->request->post['note_date'];
			$data['date_added'] = $this->request->post['date_added'];
			
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			$this->model_notes_notes->jsonaddreview($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function jsonAddreviewbyNoteID(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_id'] = $this->request->post['notes_id'];
			
			$this->model_notes_notes->jsonaddreviewbyID($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}

	public function jsongetreviews(){ 
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('setting/image'); 
		
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('setting/highlighter');
			$this->load->model('user/user');
			
			$this->load->model('facilities/facilities');
			
			
			if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
				$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
			} 

			$data = array(
				'facilities_id' => $this->request->post['facilities_id'],
				'searchdate' => $searchdate,
			);
			
			$highlighters = $this->model_notes_notes->jsongetReviewModel($data);
			
			foreach ($highlighters as $highlighter) {
				
				$user_info = $this->model_user_user->getUser($highlighter['user_id']);
				$facility_info = $this->model_facilities_facilities->getfacilities($data);
				
				if($highlighter['date_added'] != null && $highlighter['date_added'] != "0000-00-00 00:00:00"){
						$date_added = date($this->language->get('date_format_short'), strtotime($highlighter['date_added']));
					}else{
						$date_added = '';
					}
					
					if($highlighter['note_date'] != null && $highlighter['note_date'] != "0000-00-00 00:00:00"){
					$reviewnote_date = date($this->language->get('date_format_short'), strtotime($highlighter['note_date']));
				}else{
					$reviewnote_date = '';
				}
					if($highlighter['notes_pin'] != null && $highlighter['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$strike_signature = '';
				}else	
					if($highlighter['signature'] != null && $highlighter['signature'] != ""){
						
						$file1 = '/signature/'.$highlighter['signature_image'];
						/*$file12 = HTTP_SERVER . 'image/signature/'.$highlighter['signature_image'];*/
						
						$newfile4 = $this->model_setting_image->resize($file1, 300, 55);
						$newfile21 = DIR_IMAGE . $newfile4;
						$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
						
						$imageData1 = base64_encode(file_get_contents($newfile21));
						$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
						/*$strikesignature = $result['strike_signature'];*/
					}else{
						$strike_signature = '';
					}
				
				$this->data['facilitiess'][] = array(
					/*'reviewed_by_id'    => $highlighter['reviewed_by_id'],*/
					'username'      => $user_info['username'],
					'notes_pin'   => $highlighter['notes_pin'],
					'reviewnote_date'   => $reviewnote_date,
					'date_added' => $date_added,
					'signature'   => $strike_signature,
				);
			}
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'results'  => "No reviews Found",
			);
			$error = false;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	
	}
	
	public function jsonAddReminder(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		if($this->request->post['notes_id'] == null && $this->request->post['notes_id'] == ""){
			$json['warning'] = 'Note id is required!.';
		}
		if($this->request->post['reminder_time'] == null && $this->request->post['reminder_time'] == ""){
			$json['warning'] = 'Reminder is required!.';
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			$data['notes_id'] = $this->request->post['notes_id'];
			$data['reminder_time'] = $this->request->post['reminder_time'];
			$data['date_added'] = $this->request->post['date_added'];
			
			$this->model_notes_notes->jsonaddReminder($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function jsondeleteReminder(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		if($this->request->post['notes_id'] == null && $this->request->post['notes_id'] == ""){
			$json['warning'] = 'Note id is required!.';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			$data['notes_id'] = $this->request->post['notes_id'];
			$data['facilities_id'] = $this->request->post['facilities_id'];
			
			$this->model_notes_notes->jsonDeleteReminder($data);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function jsonKeywords(){
		$this->data['facilitiess'] = array();
		$this->load->model('setting/keywords');
		$this->load->model('setting/image'); 
		$results = $this->model_setting_keywords->getkeywords();
    	
		foreach ($results as $result) {
			
			if($result['keyword_image'] != null && $result['keyword_image'] != ""){
				$file1 = '/icon/'.$result['keyword_image'];
				$newfile4 = $this->model_setting_image->resize($file1, 70, 70);
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/icon/'.$newfile4;
						
				$imageData1 = base64_encode(file_get_contents($newfile21));
				$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
			}else{
				$strike_signature = '';
			}
			
      		$this->data['facilitiess'][] = array(
				'keyword_id'    => $result['keyword_id'],
				'keyword_name'   => $result['keyword_name'],
				'img_icon'   => $strike_signature,
			);
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
	
	}
	
	public function arrayInString( $inArray , $inString ){
	  if( is_array( $inArray ) ){
		foreach( $inArray as $e ){
		  if( strpos( $inString , $e )!==false )
			return $e;
		}
		return "";
	  }else{
		return ( strpos( $inString , $inArray )!==false );
	  }
	}
	
	
	public function jsongetdefault(){
		
		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$config_default_sign = $this->config->get('config_default_sign');
		}else{
			$config_default_sign = '2';
		}
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
				'config_default_sign'  => $config_default_sign,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
	}

	public function jsonGetActiveNoteDetail(){
		$this->data['facilitiess'] = array();
		
		$this->load->model('setting/keywords');
		$this->load->model('setting/image');
		
		$keyword_id = $this->request->post['keyword_id'];
		$keyword_info = $this->model_setting_keywords->getkeywordDetail($keyword_id);
		
		if($keyword_info != null && $keyword_info != ""){
			$error = true;
			
			if($keyword_info['keyword_image'] != null && $keyword_info['keyword_image'] != ""){
				$file1 = '/icon/'.$keyword_info['keyword_image'];
				$newfile4 = $this->model_setting_image->resize($file1, 70, 70);
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/icon/'.$newfile4;
						
				$imageData1 = base64_encode(file_get_contents($newfile21));
				$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
			}else{
				$strike_signature = '';
			}
			
			$this->data['facilitiess'][] = array(
				'img_icon'   => $strike_signature,
			);
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Not valid id',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
	}
	
	public function jsonupdateFile(){
		$this->load->model('notes/notes');
		$json = array();
		
		if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){

			if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
				$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

				
				if($this->request->files["upload_file"]["size"] < 5002284){
				$neextension  = strtolower($extension);
				if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){

					$notes_file = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);

					$this->model_notes_notes->updateNoteFile($this->request->post['notes_id'], $notes_file);
					
					$error = true;
					
					$this->data['facilitiess'][] = array(
						'success'   => '1',
					);
				
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'video or audio file not valid!',
					);
					$error = false;
				}
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'Maximum size file upload!',
					);
					$error = false;
				}

			}else{
				$this->data['facilitiess'][] = array(
				'warning'  => 'Note not update please update again',
			);
				$error = false;
			}
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select file!',
			);
			$error = false;
		}

		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function jsongetNotesByPage() { 
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	} 

		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
	
		$config_admin_limit = "20";
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		
		$results = $this->model_notes_notes->getnotess($data);
		
		$notes_total = $this->model_notes_notes->getTotalnotess($data);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		
		$keywords = $this->model_setting_keywords->getkeywords();
		$keyarray = array();
		foreach($keywords as $keyword){
			$keyarray[] = $keyword['keyword_name'];
		}
    	
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$user_info = $this->model_user_user->getUser($result['user_id']);
				$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
				
				if($highlighterData['highlighter_value'] != null && $highlighterData['highlighter_value'] != ""){
					$highlighter_value = $highlighterData['highlighter_value'];
				}else{
					$highlighter_value = '';
				}
				
				if($strikeuser_info['username'] != null && $strikeuser_info['username'] != ""){
					$strikeusername = $strikeuser_info['username'];
				}else{
					$strikeusername = '';
				}
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = '';
					
				}else				
				if($result['signature'] != null && $result['signature'] != ""){
					
					//$file = DIR_IMAGE . '/signature/'.$result['signature_image'];
					$file = '/signature/'.$result['signature_image'];
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/signature/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = 'data: '.$this->mime_content_type($file1).';base64,'.$imageData;
					
					/*$signature = $result['signature'];*/
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$file13 = '/key.gif';
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = '';
				}else
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					
					$file13 = '/signature/'.$result['strike_signature_image'];
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
					/*$strikesignature = $result['strike_signature'];*/
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = $result['strike_pin'];
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = $result['notes_pin'];
				}else{
					$notesPin = '';
				}
				
				
				if ($result['notes_file'] && file_exists(DIR_IMAGE . 'icon/'.$result['notes_file'])) {
					
					$file16 = '/icon/'.$result['notes_file'];
					$newfile84 = $this->model_setting_image->resize($file16, 30, 30);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							
					$imageData132 = base64_encode(file_get_contents($newfile216));
					$keyword_icon = 'data: '.$this->mime_content_type($file124).';base64,'.$imageData132;
						
				} else{
					$keyword_icon = '';
				}
					
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$keyImageSrc = 'img';
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = "";
				}
				
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				$this->data['facilitiess'][] = array(
					'notes_id'    => $result['notes_id'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => $result['notes_description'],
					'attachment_icon'   => $keyImageSrc,
					'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $user_info['username'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $result['text_color'],
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'strike_user_name'   => $strikeusername,
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => $reminderTitle,
					'reminder_time'   => $reminderTime,
					
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error,'total_note'=>$notes_total);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
	}
	
	public function imageResize($imageUrl){
		
		$img = imagecreatefrompng("http://www.servitium.com/digitalnotebook/image/cache/signature/56e9226765028.png");

		//find the size of the borders
		$b_top = 0;
		$b_btm = 0;
		$b_lft = 0;
		$b_rt = 0;

		//top
		for(; $b_top < imagesy($img); ++$b_top) {
		  for($x = 0; $x < imagesx($img); ++$x) {
			if(imagecolorat($img, $x, $b_top) != 0xFFFFFF) {
			   break 2; //out of the 'top' loop
			}
		  }
		}

		//bottom
		for(; $b_btm < imagesy($img); ++$b_btm) {
		  for($x = 0; $x < imagesx($img); ++$x) {
			if(imagecolorat($img, $x, imagesy($img) - $b_btm-1) != 0xFFFFFF) {
			   break 2; //out of the 'bottom' loop
			}
		  }
		}

		//left
		for(; $b_lft < imagesx($img); ++$b_lft) {
		  for($y = 0; $y < imagesy($img); ++$y) {
			if(imagecolorat($img, $b_lft, $y) != 0xFFFFFF) {
			   break 2; //out of the 'left' loop
			}
		  }
		}

		//right
		for(; $b_rt < imagesx($img); ++$b_rt) {
		  for($y = 0; $y < imagesy($img); ++$y) {
			if(imagecolorat($img, imagesx($img) - $b_rt-1, $y) != 0xFFFFFF) {
			   break 2; //out of the 'right' loop
			}
		  }
		}

		//copy the contents, excluding the border
		$newimg = imagecreatetruecolor(
			imagesx($img)-($b_lft+$b_rt), imagesy($img)-($b_top+$b_btm));

		imagecopy($newimg, $img, 0, 0, $b_lft, $b_top, imagesx($newimg), imagesy($newimg));

		//finally, output the image
		header("Content-Type: image/png");
		return imagejpeg($newimg);
	}
	
	public function createThumbnail($pathToImage, $thumbWidth = 180) {
    
    if (is_file($pathToImage)) {
        $info = pathinfo($pathToImage);

        $extension = strtolower($info['extension']);
        if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {

            switch ($extension) {
                case 'jpg':
                    $img = imagecreatefromjpeg("{$pathToImage}");
                    break;
                case 'jpeg':
                    $img = imagecreatefromjpeg("{$pathToImage}");
                    break;
                case 'png':
                    $img = imagecreatefrompng("{$pathToImage}");
                    break;
                case 'gif':
                    $img = imagecreatefromgif("{$pathToImage}");
                    break;
                default:
                    $img = imagecreatefromjpeg("{$pathToImage}");
            }
            // load image and get image size

            $width = imagesx($img);
            $height = imagesy($img);

            // calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($height * ( $thumbWidth / $width ));

            // create a new temporary image
            $tmp_img = imagecreatetruecolor($new_width, $new_height);

            // copy and resize old image into new image
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                $pathToImage = $pathToImage . '.thumb.' . $extension;
            // save thumbnail into a file
            imagejpeg($tmp_img, "{$pathToImage}");
            $result = $pathToImage;
        } else {
            $result = 'Failed|Not an accepted image type (JPG, PNG, GIF).';
        }
    } else {
        $result = 'Failed|Image file does not exist.';
    }
    return $result;
}

	public function jsongetNotesByApp() { 
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	} 

		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
		);
		
		$results = $this->model_notes_notes->getnotess($data);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$user_info = $this->model_user_user->getUser($result['user_id']);
				$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
				
				if($highlighterData['highlighter_value'] != null && $highlighterData['highlighter_value'] != ""){
					$highlighter_value = $highlighterData['highlighter_value'];
				}else{
					$highlighter_value = '';
				}
				
				if($strikeuser_info['username'] != null && $strikeuser_info['username'] != ""){
					$strikeusername = $strikeuser_info['username'];
				}else{
					$strikeusername = '';
				}
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = '';
					
				}else				
				if($result['signature'] != null && $result['signature'] != ""){
					
					$file5 = DIR_IMAGE . '/signature/'.$result['signature_image'];
					$file = 'signature/'.$result['signature_image'];
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					//$newImage = $this->createThumbnail($file5);
					//var_dump($newfile2);
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = 'data: '.$this->mime_content_type($file1).';base64,'.$imageData;
					
					/*$signature = $result['signature'];*/
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$file13 = '/key.gif';
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = '';
				}else
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					
					$file13 = '/signature/'.$result['strike_signature_image'];
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
					/*$strikesignature = $result['strike_signature'];*/
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = $result['strike_pin'];
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = $result['notes_pin'];
				}else{
					$notesPin = '';
				}
				
				if ($result['keyword_file'] && file_exists(DIR_IMAGE . 'icon/'.$result['keyword_file'])) {
					
					$file16 = '/icon/'.$result['keyword_file'];
					$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							
					$imageData132 = base64_encode(file_get_contents($newfile216));
					$keyword_icon = 'data: '.$this->mime_content_type($file124).';base64,'.$imageData132;
						
				} else{
					$keyword_icon = '';
				}
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$keyImageSrc = 'img';
					
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = '';
				}
				
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				$this->data['facilitiess'][] = array(
					'notes_id'  => $result['notes_id'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => $result['notes_description'],
					'attachment_icon'   => $keyImageSrc,
					'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $user_info['username'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $result['text_color'],
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'strike_user_name'   => $strikeusername,
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => $reminderTitle,
					'reminder_time'   => $reminderTime,
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
	}

	
	public function jsongetNotesByPageByApp() { 
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$date = str_replace('-', '/', $this->request->post['note_date_from']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
				
			$note_date_from = $changedDate; //date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$date1 = str_replace('-', '/', $this->request->post['note_date_to']);
			$res1 = explode("/", $date1);
			$changedDate1 = $res1[2]."-".$res1[1]."-".$res1[0];
			
			$note_date_to = $changedDate1; //date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	} 

		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
	
		$config_admin_limit = "50";
		
		$dataform = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		
		$results = $this->model_notes_notes->getnotess($dataform);
		
		$notes_total = $this->model_notes_notes->getTotalnotess($dataform);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		
    	
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$user_info = $this->model_user_user->getUser($result['user_id']);
				$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
				
				if($highlighterData['highlighter_value'] != null && $highlighterData['highlighter_value'] != ""){
					$highlighter_value = $highlighterData['highlighter_value'];
				}else{
					$highlighter_value = '';
				}
				
				if($strikeuser_info['username'] != null && $strikeuser_info['username'] != ""){
					$strikeusername = $strikeuser_info['username'];
				}else{
					$strikeusername = '';
				}
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = '';
					
				}else				
				if($result['signature'] != null && $result['signature'] != ""){
					
					//$file = DIR_IMAGE . '/signature/'.$result['signature_image'];
					$file = '/signature/'.$result['signature_image'];
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/signature/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = 'data: '.$this->mime_content_type($file1).';base64,'.$imageData;
					
					/*$signature = $result['signature'];*/
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$file13 = '/key.gif';
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = '';
				}else
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					
					$file13 = '/signature/'.$result['strike_signature_image'];
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = 'data: '.$this->mime_content_type($file12).';base64,'.$imageData1;
					/*$strikesignature = $result['strike_signature'];*/
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = $result['strike_pin'];
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = $result['notes_pin'];
				}else{
					$notesPin = '';
				}
				
				if ($result['keyword_file'] && file_exists(DIR_IMAGE . 'icon/'.$result['keyword_file'])) {
					
					$file16 = '/icon/'.$result['keyword_file'];
					$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							
					$imageData132 = base64_encode(file_get_contents($newfile216));
					$keyword_icon = 'data: '.$this->mime_content_type($file124).';base64,'.$imageData132;
						
				}else{
					$keyword_icon = '';
				}
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$keyImageSrc = 'img';
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = "";
				}
				
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				
				/*if(strlen(unserialize($result['notes_description'])) > 300){
					$neDesc = substr(unserialize($result['notes_description']),0,300);
					
					//$moreUrl = str_replace('&amp;', '&', $this->url->link('services/appservices/jsongetNoteDescription', 'notes_id=' . $result['notes_id']));
					
				}else{
					$neDesc = unserialize($result['notes_description']);
					$moreUrl =  "";
				}*/
				
				$neDesc = $result['notes_description'];
				
				$this->data['facilitiess'][] = array(
					'notes_id'    => $result['notes_id'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => $neDesc,
					//'more_url'   => $moreUrl,
					'attachment_icon'   => $keyImageSrc,
					'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $user_info['username'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $result['text_color'],
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'strike_user_name'   => $strikeusername,
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => $reminderTitle,
					'reminder_time'   => $reminderTime,
					
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error,'total_note'=>$notes_total);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
	}
	
	public function jsongetNoteDescription(){
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		if (isset($this->request->post['notes_id'])) {
      		$notes_id = $this->request->post['notes_id'];
    	} 
		
		$notesData = $this->model_notes_notes->getnotes($notes_id);
		
		if($notesData != null && $notesData != ""){
			$error = true;
			$this->data['facilitiess'][] = array(
				'notes_description'  => $notesData['notes_description'],
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Not valid id',
			);
			$error = false;
		}	
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}

	public function jsongetdefaultDate(){
		
		if($this->config->get('config_date_picker') != null && $this->config->get('config_date_picker') != ""){
			$config_date_picker = $this->config->get('config_date_picker');
		}else{
			$config_date_picker = '0';
		}
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
				'config_date_picker'  => $config_date_picker,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
	}
	
	public function jsongetdefaultTime(){
		
		if($this->config->get('config_time_picker') != null && $this->config->get('config_time_picker') != ""){
			$config_time_picker = $this->config->get('config_time_picker');
		}else{
			$config_time_picker = '0';
		}
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
				'config_time_picker'  => $config_time_picker,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
	}
	
}	