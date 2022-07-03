<?php
class Controllernotesroutemap extends Controller {
	private $error = array();

	public function index() {
		
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		$notes_id = $this->request->get['notes_id'];
		$travel_task_id = $this->request->get['travel_task_id'];
		
		if(($notes_id != null && $notes_id !="") && ($travel_task_id != null && $travel_task_id !="")){
			$geolocation_info = $this->model_notes_notes->getGeolocationbyid($notes_id, $travel_task_id);
			
			$note_info = $this->model_notes_notes->getNote($notes_id);
			
			//var_dump($geolocation_info['location_tracking_route']);
			/*
			$this->data['pickup_locations_address'] = $geolocation_info['pickup_locations_address'];
			$this->data['pickup_locations_latitude'] = $geolocation_info['pickup_locations_latitude'];
			$this->data['pickup_locations_longitude'] = $geolocation_info['pickup_locations_longitude'];
			*/
			
			//$this->data['dropoff_locations_address'] = $geolocation_info['dropoff_locations_address'];
			//$this->data['dropoff_locations_latitude'] = $geolocation_info['dropoff_locations_latitude'];
			//$this->data['dropoff_locations_longitude'] = $geolocation_info['dropoff_locations_longitude'];
			
			$this->data['current_locations_address'] = $geolocation_info['current_locations_address'];
			$this->data['current_locations_latitude'] = $geolocation_info['current_locations_latitude'];
			$this->data['current_locations_longitude'] = $geolocation_info['current_locations_longitude'];
			
			if($geolocation_info != null && $geolocation_info != ""){
				
				
				$this->data['is_pick_up'] = $geolocation_info['is_pick_up'];
				
				//var_dump($this->data['is_pick_up']);
				
				$this->data['is_drop_off'] = $geolocation_info['is_drop_off'];
				
				//var_dump($this->data['is_drop_off']);
				
				$this->data['drop_tags'] = array();
				if($geolocation_info['tags_id'] != null && $geolocation_info['tags_id'] != ""){
					$this->load->model('setting/tags');
					
					$this->load->model('notes/notes');
					
					$transport_tags1 = explode(',',$geolocation_info['tags_id']);
					
					$transport_tags = '';
					foreach ($transport_tags1 as $tag1) {
						$tags_info1 = $this->model_setting_tags->getTag($tag1);

						if($tags_info1['emp_first_name']){
							$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
						}else{
							$emp_tag_id = $tags_info1['emp_tag_id'];
						}
							
						if ($tags_info1) {
							$transport_tags .= $emp_tag_id.', ';

						}
						
						$pickup_info1 = $this->model_notes_notes->getTagpickup($tag1, '1', $note_info['task_group_by']);
						
						
						$this->data['drop_tags'][] = array(
							'emp_tag_id' => $emp_tag_id,
							'pickup_locations_address' => $pickup_info1['pickup_locations_address'],
							'pickup_locations_latitude' => $pickup_info1['pickup_locations_latitude'],
							'pickup_locations_longitude' => $pickup_info1['pickup_locations_longitude'],
							
							'dropoff_locations_address' => $geolocation_info['dropoff_locations_address'],
							'dropoff_locations_latitude' => $geolocation_info['dropoff_locations_latitude'],
							'dropoff_locations_longitude' => $geolocation_info['dropoff_locations_longitude'],
							
							'current_locations_address' => $pickup_info1['current_locations_address'],
							'current_locations_latitude' => $pickup_info1['current_locations_latitude'],
							'current_locations_longitude' => $pickup_info1['current_locations_longitude'],
						);
					}
					
					//$tags_id = $transport_tags;
				}
				//var_dump($this->data['drop_tags']);
				
				$pick_up_tags_id = "";
				if($geolocation_info['pick_up_tags_id'] != null && $geolocation_info['pick_up_tags_id'] != ""){
					$this->load->model('setting/tags');
					$transport_tags1 = explode(',',$geolocation_info['pick_up_tags_id']);
					
					$transport_tags13333 = '';
					foreach ($transport_tags1 as $tag1) {
						$tags_info12 = $this->model_setting_tags->getTag($tag1);

						if($tags_info12['emp_first_name']){
							$emp_tag_id = $tags_info12['emp_tag_id'].':'.$tags_info12['emp_first_name'];
						}else{
							$emp_tag_id = $tags_info12['emp_tag_id'];
						}
							
						if ($tags_info12) {
							$transport_tags13333 .= $emp_tag_id.', ';

						}
					}
					
					$this->data['pick_up_tags_id'] = $transport_tags13333;
				}
				
				//var_dump($this->data['pick_up_tags_id']);
				
				$geolocations = explode("|", $geolocation_info['location_tracking_url']);
			
				$a = "";
				
				//var_dump($geolocations);
				
				//var_dump(end($geolocations));
				//var_dump(current($geolocations));
				
				
				$coordinate_infaaassso = explode(",", current($geolocations));
				
				$this->data['pickup_locations_latitude'] = $coordinate_infaaassso[0];
				$this->data['pickup_locations_longitude'] = $coordinate_infaaassso[1];
				
				
				$coordinate_infaaao = explode(",", end($geolocations));
				
				$this->data['dropoff_locations_latitude'] = $coordinate_infaaao[0];
				$this->data['dropoff_locations_longitude'] = $coordinate_infaaao[1];
				
				
				$ddd = array();
				foreach($geolocations as $geolocation){
					
					$a .= $geolocation;
					$coordinate_info = explode(",", $geolocation);
					
					
					
					$this->data['coordinates'][] = array(
						'latitude'  => $coordinate_info[0],
						'longitude' => $coordinate_info[1],
					);
				}
				
			}
		}
		//die;
		
		$coordinates1 = array_chunk($this->data['coordinates'], 99);
		
		//var_dump($coordinates1);
		$this->data['coordinates1'] = $coordinates1;
		
		$this->data['location_tracking_url'] = $geolocation_info['location_tracking_url'];
		
		//var_dump($this->data['location_tracking_url']);

		$this->template = $this->config->get('config_template') . '/template/notes/routemap.php';
		
		$this->response->setOutput($this->render());
			
	}
	
	
	protected function validateForm2() {
			

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

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$this->error['user_id'] = "Enter a valid user.";
			}
		}
		
		
		if ($this->request->post['select_one'] == '') {
			$this->error['select_one'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['select_one'] == '1') {
			if ($this->request->post['notes_pin'] == '') {
				$this->error['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}

				
				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
		}
		
		if ($this->request->post['override_monitor_time_user_id_checkbox'] == '1') {
			if ($this->request->post['override_monitor_time_user_id'] == '') {
				$this->error['override_monitor_time_user_id'] = $this->language->get('error_required');
			}
		}
		
		if ($this->request->post['override_monitor_time_user_id'] != null && $this->request->post['override_monitor_time_user_id'] != '') {
			if ($this->request->post['override_monitor_time_user_id_checkbox'] == '') {
				$this->error['override_monitor_time_user_id_checkbox'] = $this->language->get('error_required');
			}
		}
		
		$this->load->model('setting/keywords');
		$keywordData2 = $this->model_setting_keywords->getkeywordDetail($this->request->get['keyword_id']);
		
		if($keywordData2['monitor_time'] == '1'){
			if($this->request->post['override_monitor_time_user_id_checkbox'] != '1'){
				if($keywordData2['end_relation_keyword'] == '1'){
					$a3 = array();
					$a3['keyword_id'] = $keywordData2['relation_keyword_id'];
					$a3['user_id'] = $this->request->post['user_id'];
					$a3['facilities_id'] = $this->customer->getId();
					$a3['is_monitor_time'] = '1';
					
					$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
					
					//var_dump($active_note_info2);	
					
					if(empty($active_note_info2)){
						$this->error['warning'] = 'End ActiveNote does not exit!';
					}
				}
			}
		}


		/*if(($this->request->post['notes_pin'] == null && $this->request->post['notes_pin'] == "") && ($this->request->post['imgOutput'] == null && $this->request->post['imgOutput'] == "")){
			$this->error['warning'] = 'Please insert at least one required!';

			}*/
		if (!$this->error) {
			return true;
		} else {
			return false;
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
				'data' => 'Error in appservices mime_content_type',
			);
			$this->model_activity_activity->addActivity('app_mime_content_type', $activity_data2);
		
		
		} 
    }
	
}