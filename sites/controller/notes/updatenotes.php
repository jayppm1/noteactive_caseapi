<?php  
class Controllernotesupdatenotes extends Controller {  
	private $error = array();
   
  	public function index() {
		
			
		$this->load->model('notes/notes');
		
		for ($x = 0; $x <= 50; $x++) {
			
			$data = array();
			if($x == 10){
				$data['highlighter_id'] = '15';
				$data['highlighter_value'] = '';
			}else if($x == 20){
				$data['highlighter_id'] = '13';
				$data['highlighter_value'] = '';
			}else if($x == 40){
				$data['highlighter_id'] = '11';
				$data['highlighter_value'] = '';
			}else{
				$data['highlighter_id'] = '0';
				$data['highlighter_value'] = '';
			}
			
			if($x%2==0){
				if($x == 12){
					$data['notes_description'] = 'Phone call';
				}elseif($x == 36){
					$data['notes_description'] = 'Bed check: Following Count was noted-';
				}else{
					$data['notes_description'] = 'To create the worlds leading enterprise digital log book platform that is innovative, adaptable and fundamentally transformative.';
				}
			}else{
				if($x == 19){
					$data['notes_description'] = 'Incident';
				}elseif($x == 47){
					$data['notes_description'] = 'Physically challenged';
				}else{
					$data['notes_description'] = 'The NoteActive Enterprise Log book platform is a commercial digital/electronic note capturing platform for businesses, organizations & enterprises.';
				}
			}
			//$data['notes_description'] = 'Our Cloud Connected Log Book Platform provides remote accessibility and increases connectivity of users from any computer or smart phone device anywhere in real-time.';
			
			/*
			$data['notes_description'] = 'Phone call';
			$data['notes_description'] = 'Bed check: Following Count was noted-';
			$data['notes_description'] = 'Emergency Disaster Drill';
			$data['notes_description'] = 'Incident';
			$data['notes_description'] = 'Law Enforcement';
			$data['notes_description'] = 'Physically challenged';
			  */
			
			$data['notes_pin'] = '';
			$data['user_id'] = 'admin';
			
			date_default_timezone_set('US/Eastern');
			$data['notetime'] = date('H:i:s');
			$data['text_color'] = '';
			$data['note_date'] = date('Y-m-d H:i:s');

			$data['notes_file'] = '';
			$data['facilitytimezone'] = 'US/Eastern';
			
			$data['keyword_file'] = '';
			
			
			$data['date_added'] = date('Y-m-d H:i:s');
			$data['imgOutput'] = 'data:image/png;base64,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';
			$data['facilities_id'] = '47';
			
			$this->model_notes_notes->jsonaddnotes($data,'47');
		}
		
    	/*$results = $this->model_notes_notes->getnotess($data);
    	
		foreach ($results as $result) {
			
			$notes_id = $result['notes_id'];
			*/
			//var_dump($this->mb_unserialize($result['notes_description']));
			//
			
			
			//$notes_description = $result['notes_description'];
			
			/*$this->load->model('setting/image');
			
			if($result['signature_image'] != null && $result['signature_image'] != ""){
				$file16 = '/signature/'.$result['signature_image'];

				var_dump($file16);
				echo "<hr>";
				//$newfile84 = $this->model_setting_image->resize($file16, 300, 28);
				$newfile216 = DIR_IMAGE . $file16;
				
				var_dump($newfile216);
				echo "<hr>";
				
				$file124 = HTTP_SERVER . 'image'.$file16;
				var_dump($file124);
				$imageData132 = base64_encode(file_get_contents($newfile216));
				$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
				
				var_dump($keyword_icon);
				echo "<hr>";
				$sql = "update `" . DB_PREFIX . "notes` SET signature = '".$this->db->escape($keyword_icon)."' where notes_id = '".$notes_id."' ";
				$this->db->query($sql);
				*/
			//}else{
				/*$notes_description = $this->mb_unserialize($result['notes_description']);
				
				if($notes_description != null && $notes_description != ""){
				var_dump($notes_description);
				echo "<hr>";
				$sql = "update `" . DB_PREFIX . "notes` SET notes_description = '".$this->db->escape($notes_description)."' where notes_id = '".$notes_id."' ";
				$this->db->query($sql);
				}*/
			//}
		/*}
		}*/
		
  	}
	
	
	public function allkillSession(){
		$this->load->model('licence/licence');
		
		$timeZone = date_default_timezone_set('US/Eastern');
		
		var_dump(date('Y-m-d H:i:s', strtotime('now')));
		
		echo "<hr>";
		$thestime = date('H:i:s');
		$stime = date("H:i:s",strtotime("-1 minutes",strtotime($thestime)));
		
		$noteDate2 = date('Y-m-d', strtotime('now'));
		$noteDate = $noteDate2.' '.$stime;
		
		var_dump($noteDate);
		
		echo "<hr>";
		$results = $this->model_licence_licence->getfacilitiesOnline();
		
		$webkey = array();
		foreach($results as $result){
			$date_added = date('Y-m-d H:i:s', strtotime($result['date_added']));
			//var_dump($date_added);
			//echo "<hr>";
			echo $date_added .'<'. $noteDate;
			echo "<hr>";
			if($date_added > $noteDate){
				$webkey[] = $result['facility_login'];
			}
		}
		//var_dump($webkey);
		$newKey = implode(',', $webkey);
		
		var_dump($newKey);
		echo "<hr>";
		$this->model_licence_licence->updateSession($newKey);
	}
	
	
	public function mb_unserialize($serial_str) {  
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );  
		$data = @unserialize($out);
		
		return $data;  
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
	
	public function getUsers(){
		$this->load->model('user/user');
		$this->load->model('notes/notes');
		$results = $this->model_notes_notes->getnotess($data);
		
		foreach ($results as $result) {
			
			$notes_id = $result['notes_id'];
			$user_id = $result['user_id'];
			var_dump($user_id);
			$user_info = $this->model_user_user->getUserbyupdate($user_id);
			
			var_dump($user_info['username']);
			echo "<hr>";
			if($user_info['username'] != null && $user_info['username'] != ""){
				$sql = "UPDATE `" . DB_PREFIX . "notes` SET user_id = '".$user_info['username']."' WHERE notes_id = '" . $notes_id . "' ";
				$this->db->query($sql);
			}
			
		}
	}
	
	public function getstrikeUsers(){
		$this->load->model('user/user');
		$this->load->model('notes/notes');
		$results = $this->model_notes_notes->getnotess($data);
		
		foreach ($results as $result) {
			
			$notes_id = $result['notes_id'];
			$user_id = $result['strike_user_id'];
			
			$user_info = $this->model_user_user->getUserbyupdate($user_id);
			var_dump($user_info['username']);
			echo "<hr>";
			if($user_info['username'] != null && $user_info['username'] != ""){
				$sql = "UPDATE `" . DB_PREFIX . "notes` SET strike_user_id = '".$user_info['username']."' WHERE notes_id = '" . $notes_id . "' ";
				$this->db->query($sql);
			}
			
		}
	}
	
	
	public function updateActivenote(){
		$this->load->model('user/user');
		$this->load->model('notes/notes');
		$this->load->model('setting/keywords');
		
		$keywords = $this->model_setting_keywords->getkeywords();
		
		
		$keyarray = array();
		foreach($keywords as $keyword){
			$keyarray[] = $keyword['keyword_name'];
		}
		
		$results = $this->model_notes_notes->getnotess($data);
		
		foreach ($results as $result) {
			
			$notes_id = $result['notes_id'];
			$matchData = $this->arrayInString( $keyarray , $result['notes_description']);
				
				
			if ($matchData != null && $matchData != "") {
				$dataKeyword = $matchData;
				$keywordData = $this->model_setting_keywords->getkeyword($dataKeyword);
			}else{
				$keywordData = "";
			}
			var_dump($keywordData['keyword_image']);
			echo "<hr>";
			if($keywordData['keyword_image'] != null && $keywordData['keyword_image'] != ""){
				$this->load->model('setting/image');
				
				$file16 = '/icon/'.$keywordData['keyword_image'];

				$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
				$newfile216 = DIR_IMAGE . $newfile84;
				$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
				$imageData132 = base64_encode(file_get_contents($newfile216));
				$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
				var_dump($keyword_icon);
			}
			if($keywordData['keyword_image'] != null && $keywordData['keyword_image'] != ""){
				 $sql = "UPDATE `" . DB_PREFIX . "notes` SET keyword_file = '".$keywordData['keyword_image']."', keyword_file_url = '".$this->db->escape($keyword_icon)."' WHERE notes_id = '" . $notes_id . "' ";
				$this->db->query($sql);
			}
			
		}
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
	

	public function updatenotesactivenote() {
		$this->load->model('notes/notes');
		$this->load->model('setting/keywords');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['limit'])) {
			$config_admin_limit = $this->request->get['limit'];
		} else {
			$config_admin_limit = "500";
		}
		 
		$data = array(
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		
		$results = $this->model_notes_notes->getnotess($data);
		$i=0;
		foreach ($results as $result) { 
			
			if($result['keyword_file']){
				
				$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_keyword WHERE keyword_file = '" . $result['keyword_file'] . "' and notes_id = '" . $result['notes_id'] . "' ";
				$query = $this->db->query($sql);
				
				//var_dump($query->row);
				//var_dump($query->row['notes_id']);
				//echo "<hr>";
				if($query->row['notes_id'] == null && $query->row['notes_id'] == "" ){
					//var_dump($result['keyword_file']);
					//echo "<hr>";
					$keyword_info = $this->model_setting_keywords->getkeywordDetaildesc($result['keyword_file'],$result['facilities_id']);
					//var_dump($keyword_info);
					//echo '<hr>';
					
					$sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $result['notes_id'] . "', keyword_id = '" . $this->db->escape($keyword_info['keyword_id']) . "', keyword_name = '" . $this->db->escape($keyword_info['keyword_name']) . "', keyword_file = '" . $this->db->escape($result['keyword_file']) . "', keyword_file_url = '" . $this->db->escape($result['keyword_file_url']) . "', active_tag = '', keyword_status = '1' ";
					$this->db->query($sqlm);
					$i++;
				}
			}
			
		}
		
		echo $i .' File uploaded of page '.$page ;
	}
	
	
	public function updateclients(){
		$sql = "SELECT * FROM " . DB_PREFIX . "tags ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0){
			foreach($query->rows as $row){
				$this->load->model('facilities/facilities');
				$facilities_info = $this->model_facilities_facilities->getfacilities($row['facilities_id']);
				$this->load->model('setting/timezone');
				
				if($row['room'] > 0){
					$this->load->model('setting/locations');
					$location_info = $this->model_setting_locations->getlocation($row['room']);
					$lname = $location_info ['location_name'];
				}
				
				if($row['customlistvalues_id'] != null && $row['customlistvalues_id'] != ""){
					
					
					$this->load->model('form/form');
					$customlistvalues_info = $this->model_form_form->getcustomlistvalues($row['customlistvalues_id']);
					
					if($customlistvalues_info['gender'] != null && $customlistvalues_info['gender'] != '0'){
						$gender = $customlistvalues_info['gender'];
						$gname = $customlistvalues_info['customlistvalues_name'];
					}else{
						$gender = '1';
						$gname = "Male";
					}
				}else{
					$gender = '1';
					$gname = "Male";
				}
				
				$alldata = "";
				foreach($row as $a){
					$alldata .= $a.' ';
				}
				$alldata .= $facilities_info['facility'].' ';
				$alldata .= $gname.' ';
				$alldata .= $lname.' ';
				
				
				echo $sql1 = "UPDATE `" . DB_PREFIX . "tags` SET tag_data = '".$this->db->escape($alldata)."'  WHERE tags_id = '" . (int)$row['tags_id'] . "'";
				$this->db->query($sql1);
				echo "<hr>";
				
			}
		}
	}
}
?>