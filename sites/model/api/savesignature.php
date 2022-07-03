<?php
class Modelapisavesignature extends Model {
	
	public function savesignature($data = array()) {

		$imagedata = '';
        
        if ($data["upload_file"] != null && $data["upload_file"] != "") {
            
			$image_parts = explode(";base64,", $data["upload_file"]);
			
			$image_type_aux = explode("image/", $image_parts[0]);
			
			$image_type = $image_type_aux[1];

			$notes_file = uniqid() . '.'.$image_type;
			$outputFolder = DIR_IMAGE . 'signature/' . $notes_file;

			$s3file = $this->awsimageconfig->uploadsignature ($notes_file, $data["upload_file"], $data['facilities_id'] );

			if($s3file){
				$imagedata = $s3file;
			}else{
				$imagedata = '';
			}

			//var_dump($s3file); die;
			
			/*
            $outputFolderUrl = HTTP_SERVER . 'image/signature/' . $notes_file;
            
			if(file_put_contents($outputFolder, base64_decode(explode(',',$data["upload_file"])[1]))){
				$imagedata['status'] = 1;
				$imagedata['imageurl'] = $outputFolderUrl;
			}else{
				$imagedata['status'] = 0;
				$imagedata['imageurl'] = '';
			}*/
			//echo '<pre>'; print_r($imagedata); echo '</pre>'; die;
			
        } else {
			$imagedata = '';
        }
        return $imagedata;
	}
}