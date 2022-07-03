<?php
class Modelapifacerekognition extends Model {
	
	public function checkfacerekognition($data = array(), $files1) {
		
		$facilities_id = $data['facilities_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$warning11 = array();
		$this->load->model('notes/notes');
		if($facilities_info['is_enable_add_notes_by'] == '1'){
			
			if($data['current_enroll_image1'] == '1'){
				
				$this->load->model('notes/facerekognition');
					
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserByUsername($data['user_id']);
				
				$user_result = $this->model_user_user->getenroll_images($user_info['user_id']);
				
				if(!empty($user_result)){
					
					$imagedata = $this->model_notes_facerekognition->jsonuploadfacerekognitioncompare($files1);
					
					$warning11['imagedata'] = $imagedata;
					if($imagedata['notes_file'] != null && $imagedata['notes_file'] != ""){
						
						if($facilities_info['allow_face_without_verified'] != '1'){
							$facematch = $this->model_notes_facerekognition->jsongetfacerekognitioncompare($user_result, $facilities_info['face_similar_percent'], $imagedata, $data['user_id']);			
							
							if ($facematch == '2') {
								$warning11['warning1'] = 'Sorry i am having trouble recognizing you. Lets try again!!';
							}
						}
						
					}else{
						$warning11['warning1'] = $imagedata['warning_e'];
					}
					
					
				}else{
					$warning11['warning1'] = 'Please contact your admin to enroll your picture!';
				}
				
			}
		}
		
		return $warning11;
	}
	
	public function savefacerekognitionnotes($data = array()){
		
		$facilities_id = $data['facilities_id'];
		$notes_id = $data['notes_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$warning11 = array();
		
		$this->load->model('notes/notes');
		
		if($facilities_info['is_enable_add_notes_by'] == '1'){
			
			if($data['outputFolder'] != null && $data['outputFolder'] != ""){
					
				$notes_file = $data['face_notes_file'];
				$outputFolder = $data['outputFolder'];
				
				$image_parts = explode(";base64,", $outputFolder);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				//$image_base64 = $image_parts[1];
				//var_dump($image_base64);

				$notes_file = uniqid() . '.'.$image_type;
				
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');	
				$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder, $facilities_id);
				//var_dump($s3file);
				
				$this->model_notes_notes->updateuserpicture($s3file, $notes_id);
				$this->model_notes_notes->updateuserverified('2', $notes_id);
				
				unlink($outputFolder);
			}
			
			
			if($data['face_not_verify'] == "1"){
				$this->model_notes_notes->updateuserverified('1', $notes_id);
			}
			
			if($data['outputFolder_1'] != null && $data['outputFolder_1'] != ""){
			
				$notes_file = $data['notes_file'];
				$outputFolder = $data['outputFolder_1'];
				
				$image_parts = explode(";base64,", $outputFolder);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				//$image_base64 = $image_parts[1];
				//var_dump($image_base64);

				$notes_file = uniqid() . '.'.$image_type;
		
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');	
				$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder,$facilities_id);
				//var_dump($s3file);
				
				$this->model_notes_notes->updateuserpicture($s3file, $notes_id);
				
				if ($facematch == '1') {
					$this->model_notes_notes->updateuserverified('2', $notes_id);
				}else{
					$this->model_notes_notes->updateuserverified('1', $notes_id);
				}
				
				unlink($outputFolder);
			}
		}
		
		return $s3file;
	}
	
	
	public function savefacerekognitioncomment($data = array()){
		
		$facilities_id = $data['facilities_id'];
		$notes_id = $data['notes_id'];
		$comment_id = $data['comment_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$warning11 = array();
		
		$this->load->model('notes/notescomment');
		
		if($facilities_info['is_enable_add_notes_by'] == '1'){
			
			if($data['outputFolder'] != null && $data['outputFolder'] != ""){
					
				$notes_file = $data['face_notes_file'];
				$outputFolder = $data['outputFolder'];
				
				$image_parts = explode(";base64,", $outputFolder);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				//$image_base64 = $image_parts[1];
				//var_dump($image_base64);

				$notes_file = uniqid() . '.'.$image_type;
				
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');	
				$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder, $facilities_id);
				//var_dump($s3file);
				
				$this->model_notes_notescomment->updateuserpicture($s3file, $comment_id);
				$this->model_notes_notescomment->updateuserverified('2', $comment_id);
				
				unlink($outputFolder);
			}
			
			
			if($data['face_not_verify'] == "1"){
				$this->model_notes_notescomment->updateuserverified('1', $comment_id);
			}
			
			if($data['outputFolder_1'] != null && $data['outputFolder_1'] != ""){
			
				$notes_file = $data['notes_file'];
				$outputFolder = $data['outputFolder_1'];
				
				$image_parts = explode(";base64,", $outputFolder);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				//$image_base64 = $image_parts[1];
				//var_dump($image_base64);

				$notes_file = uniqid() . '.'.$image_type;
		
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');	
				$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder,$facilities_id);
				//var_dump($s3file);
				
				$this->model_notes_notescomment->updateuserpicture($s3file, $comment_id);
				
				if ($facematch == '1') {
					$this->model_notes_notescomment->updateuserverified('2', $comment_id);
				}else{
					$this->model_notes_notescomment->updateuserverified('1', $comment_id);
				}
				
				unlink($outputFolder);
			}
		}
		
		return $s3file;
	}
	
	
	public function savefacerekognitionnotestime($data = array()){
		
		$facilities_id = $data['facilities_id'];
		$notes_id = $data['notes_id'];
		$time_id = $data['time_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$warning11 = array();
		
		$this->load->model('notes/updatetime');
		
		if($facilities_info['is_enable_add_notes_by'] == '1'){
			
			if($data['outputFolder'] != null && $data['outputFolder'] != ""){
					
				$notes_file = $data['face_notes_file'];
				$outputFolder = $data['outputFolder'];
				
				$image_parts = explode(";base64,", $outputFolder);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				//$image_base64 = $image_parts[1];
				//var_dump($image_base64);

				$notes_file = uniqid() . '.'.$image_type;
				
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');	
				$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder, $facilities_id);
				//var_dump($s3file);
				
				$this->model_notes_updatetime->updateuserpicture($s3file, $time_id);
				$this->model_notes_updatetime->updateuserverified('2', $time_id);
				
				unlink($outputFolder);
			}
			
			
			if($data['face_not_verify'] == "1"){
				$this->model_notes_updatetime->updateuserverified('1', $time_id);
			}
			
			if($data['outputFolder_1'] != null && $data['outputFolder_1'] != ""){
			
				$notes_file = $data['notes_file'];
				$outputFolder = $data['outputFolder_1'];
				
				$image_parts = explode(";base64,", $outputFolder);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				//$image_base64 = $image_parts[1];
				//var_dump($image_base64);

				$notes_file = uniqid() . '.'.$image_type;
		
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');	
				$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder,$facilities_id);
				//var_dump($s3file);
				
				$this->model_notes_updatetime->updateuserpicture($s3file, $time_id);
				
				if ($facematch == '1') {
					$this->model_notes_updatetime->updateuserverified('2', $time_id);
				}else{
					$this->model_notes_updatetime->updateuserverified('1', $time_id);
				}
				
				unlink($outputFolder);
			}
		}
		
		return $s3file;
	}
	
	/*public function savefacerekognitiontask($data = array()){
		$facilities_id = $data['facilities_id'];
		$notes_id = $data['notes_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$warning11 = array();
		
		if($facilities_info['is_client_facial'] == '1'){
			
			if($data['client_face_notes_file'] != null && $data['client_face_notes_file'] != ""){
					
				$notes_file = $data['client_face_notes_file'];
				$outputFolder = $data['client_outputFolder'];
				
				require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');						
				
				unlink($outputFolder);
			}
		}
		return $s3file;
	}*/
	
}