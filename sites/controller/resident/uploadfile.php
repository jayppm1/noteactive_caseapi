<?php
class Controllerresidentuploadfile extends Controller {
	private $error = array();

	
	public function index(){
		$json = array();
		if(isset($this->request->post['image'])){
			
			$data = $this->request->post['image'];
			
			$image_array_1 = explode(";", $data);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);
			$imageName = time() . '.png';
			
			
			//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
			
			$imageName_path = DIR_IMAGE .'files/'.$imageName;
			$imageName_url = HTTPS_SERVER .'image/files/'. $imageName;
			
			file_put_contents($imageName_path, $data);
			
			//echo '<img src="'.$imageName_url.'" class="img-thumbnail" />';
			
			
			$notes_file = $imageName;
			$outputFolder = $imageName_path;
			
			
			if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
                $facilities_id = $this->request->get['facilities_id'];
            } else {
                $facilities_id = $this->customer->getId();
            }
			
			if($this->config->get('enable_storage') == '1'){
				/* AWS */
				//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$s3file = $this->awsimageconfig->uploadFile($notes_file, $outputFolder, $facilities_id);
				
				$this->load->model('facilities/facilities');
				$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
				
				if ($facilities_info['is_client_facial'] == '1') {
					
					
					if ($this->request->get['tags_id'] != '' && $this->request->get['tags_id'] != null) {
						$tags_id = $this->request->get['tags_id'];
						
						$this->load->model('setting/tags');
						$taginfo_a = $this->model_setting_tags->getTag($tags_id);
						
						if($taginfo_a['emp_tag_id'] != null && $taginfo_a['emp_tag_id'] != ""){
							$femp_tag_id = $taginfo_a['emp_tag_id'];
							
							
							$outputFolderUrl = $s3file;
							//require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_tags_config.php');
							
							$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag($outputFolderUrl, $femp_tag_id,$facilities_id);
					
							foreach($result_inser_user_img22['FaceRecords'] as $b){
								$FaceId = $b['Face']['FaceId'];
								$ImageId = $b['Face']['ImageId'];
							}
							
							
							//$this->model_setting_tags->insertTagimageenroll($tags_id, $FaceId, $ImageId, $s3file, $facilities_id);
						}
					}
					
				}
			}
			
			if($this->config->get('enable_storage') == '2'){
				/* AZURE */
				
				require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
				//uploadBlobSample($blobClient, $outputFolder, $notes_file);
				$s3file = AZURE_URL. $notes_file;
			}
			
			if($this->config->get('enable_storage') == '3'){
				/* LOCAL */
				//$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
				//move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);
				//$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
				$s3file = $imageName_url;
			}
			
			
			$json['success'] = '1';
			$json['imageName_url'] = $s3file;
			$json['imageName_path'] = $imageName_path;
			$json['imageName'] = $imageName;

		}else{
			$json['success'] = '2';
		}
			
		$this->response->setOutput(json_encode($json));
	}

		/*
	
		var_dump($this->request->files["file"]);
		
		if($this->request->files["file"] != null && $this->request->files["file"] != ""){

			$extension = end(explode(".", $this->request->files["file"]["name"]));

			if($this->request->files["file"]["size"] < 42214400){
				$neextension  = strtolower($extension);
					
				//$notes_file = 'devbolb16384.jpg';
				$notes_file = 'devbolb'.rand().'.'.$extension;
				
				$outputFolder = $this->request->files["file"]["tmp_name"];
		
				move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);

				require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
		
		
				echo '<img src="'.$s3file.'" class="img-thumbnail" />';
			}
		}	
		*/


}


