<?php  
	header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controlleruploadfileuploadfile extends Controller {
	
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
	
}
?>