<?php
class Modelsettingimage extends Model {
	/**
	*	
	*	@param filename string
	*	@param width 
	*	@param height
	*	@param type char [default, w, h]
	*				default = scale with white space, 
	*				w = fill according to width, 
	*				h = fill according to height
	*	
	*/
	public function resize($filename, $width, $height, $type = "") {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}
		
		/*if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}*/	
		
		return $new_image;
	}
	
	public function checkresize($upload_file){
		
		/*if($upload_file != null && $upload_file != ""){
					
			$url_to_image = $upload_file;
			 
			$my_save_dir = DIR_IMAGE.'files/';
			$filename = basename($url_to_image);
			
			$extension = end(explode(".", $filename));
			
			$picture_filename = pathinfo($filename, PATHINFO_FILENAME);
			
			if($this->config->get('thumb_image_size') != null && $this->config->get('thumb_image_size') != ""){
				$final_width_of_image = $this->config->get('thumb_image_size');
			}else{
				$final_width_of_image = 50;
			}
			
			$path_to_image_directory = 'files/';
			$path_to_thumbs_directory = 'files/';
			
			$new_image_1= "";
			$new_image = $picture_filename.'-'.$final_width_of_image;
			$new_image_1 = $new_image . "." . $extension;
			$outputFolder = DIR_IMAGE .$path_to_thumbs_directory.$new_image_1;
			
			$complete_save_loc = $my_save_dir . $filename;
			
			if (!file_exists($outputFolder) || !is_file($outputFolder)) {
				return 2;
			}
		}*/
		
		return 2;
	}
}
?>