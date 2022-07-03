<?php 
header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-type: application/json');
header('Content-Type: text/html; charset=utf-8');

class Controllerservicesfacerekognition extends Controller {

	public function jsondetectfaces(){
		try{
			$this->data['facilitiess'] = array();
			
			
			$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
			
			if($this->config->get('config_face_recognition') == '1'){
				
				if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){

					$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

					if($this->request->files["upload_file"]["size"] < 42214400){
						$neextension  = strtolower($extension);
						if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
							
							$notes_file = uniqid( ) . "." . $extension;
							$outputFolder = DIR_IMAGE.'facerecognition/' . $notes_file;
							move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
							
							$outputFolderUrl = HTTP_SERVER.'image/facerecognition/' . $notes_file;
							
							
							//require_once(DIR_APPLICATION_AWS . 'facerecognition_config_detectfaces.php');
							require_once(DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config_app.php');
							
							if($this->request->post["image_not_delete"] != '1'){
								unlink($outputFolder);
							}
							
							if($FaceId != null && $FaceId != ""){
								$this->load->model('user/user'); 
								$user_info = $this->model_user_user->geteuser_info_byfaceid($FaceId);
							}
							
						
							if($similarity>90){
							
								$error = true;
							
								$this->data['facilitiess'][] = array(
									'success'  => '1',
									'similar'  => $similarity,
									'username'  => $user_info['username'],
									'match_user_id'  => $user_info['user_id'],
									'face_notes_file'  => $notes_file,
									'outputFolder'  => $outputFolder,
									'outputFolderUrl'  => $outputFolderUrl,
									
								);
								
							}
							
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
			
			}else{
				$this->data['facilitiess'][] = array(
					'warning'  => 'Please activate face recognition setting!',
				);
				$error = false;
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			
			$this->response->setOutput(json_encode($value));
			
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in wearservice jsondetectfaces',
				);
				$this->model_activity_activity->addActivity('wear_jsondetectfaces', $activity_data2);
		}
		
		}
		
	
}


