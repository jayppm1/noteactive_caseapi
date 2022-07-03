<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservices2notescomment extends Controller { 
	private $error = array();
	
	public function index(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('notescommentindex', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		/*if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}*/
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
			$unique_id = $facility['customer_key'];
			
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			if($user_info['customer_key'] != $customer_info['activecustomer_id']){
				$json['warning'] = $this->language->get('error_customer');
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
		}
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');

			$this->load->model('notes/notes');
			$this->load->model('notes/notescomment');

			
			if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
				$this->load->model('setting/timezone');
					
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				$facilitytimezone = $timezone_info['timezone_value'];
			}
			
			$timezone_name = $facilitytimezone;
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			
			$facilities_id = $this->request->post['facilities_id'];
			
			$notetime = date('H:i:s', strtotime('now'));
			$data['imgOutput'] = $this->request->post['signature'];
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_type'] = $this->request->post['notes_type'];
			
			if($this->request->post['tags_id'] != null && $this->request->post['tags_id'] != ""){
				$this->load->model('setting/tags');
				$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
				
				$data['emp_tag_id'] = $tag_info['emp_tag_id'];
				$data['tags_id'] = $tag_info['tags_id'];
			}
			
			
			$this->load->model('setting/keywords');
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail($this->request->post['keyword_id']);
			
			if($this->request->post['comments'] != null && $this->request->post['comments']){
				$comments = ' | '.$this->request->post['comments'];
				
			}
			
			
			$comment = $this->request->post['comment'];
			$notes_id = $this->request->post['notes_id'];
			$active_note_id = $this->request->post['active_note_id'];
			
			$data['notes_description'] = $comment.''.$comments;
			
			$data['date_added'] = $date_added;
			$data['comment_date'] = $this->request->post['notes_id'];
			$data['notes_id'] = $notes_id;
			$data['active_note_id'] = $active_note_id;
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
			$data['device_unique_id'] = $this->request->post['device_unique_id'];
					
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			
			//var_dump($data);
			if($this->request->post['device_unique_id'] != null && $this->request->post['device_unique_id'] != ""){
				$exist_note_info = $this->model_notes_notescomment->getexistnotes($data, $facilities_id);
			
				if(!empty($exist_note_info)){
					$comment_id = $exist_note_info['comment_id'];
					$device_unique_id = $exist_note_info['device_unique_id'];
				}else{
					
					$comment_id = $this->model_notes_notescomment->addnotescomment($data, $facilities_id);
					$device_unique_id = $this->request->post['device_unique_id'];
				}
			}else{
				$comment_id = $this->model_notes_notescomment->addnotescomment($data, $facilities_id);
				$device_unique_id = $this->request->post['device_unique_id'];
			}
			
			$this->load->model('api/facerekognition');
			$fre_array2 = array();
			$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
			$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
			$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
			$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
			$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
			$fre_array2['notes_id'] = $notes_id;
			$fre_array2['comment_id'] = $comment_id;
			$s3_url = $this->model_api_facerekognition->savefacerekognitioncomment($fre_array2);
			
			if($s3_url != null && $s3_url != ""){
				$s3urlss = $s3_url;
			}else{
				$s3urlss = "";
			}
			
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
				'device_unique_id'  => $device_unique_id,
				's3_url'  => $s3urlss,
				'comment_id'  => $comment_id,
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in notescomment '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_notescomment', $activity_data2);
		
		
		} 
	}
	
	public function mime_content_type($filename) {

		try{
		
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
		
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices mime_content_type '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_mime_content_type', $activity_data2);
		
		
		} 
    }
	
}