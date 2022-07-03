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
		
		
		//if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			//$path = '';
			
			/*$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			} */
			$pathToImage = DIR_IMAGE . $filename;
			$pathToImage2 = DIR_IMAGE .'cache/'. $filename;
			
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
			
			$thumbWidth = $width;

            $width = imagesx($img);
            $height = imagesy($img);

            // calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($height * ( $thumbWidth / $width ));

            // create a new temporary image
            $tmp_img = imagecreatetruecolor($new_width, $new_height);

			//$color = imagecolorallocate($tmp_img, 255, 255, 255);
		
			// fill entire image
			$transparency = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
			// fill entire image
			imagefill($tmp_img, 0, 0, $transparency);
            // copy and resize old image into new image
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                $pathToImage = $pathToImage2 . '.thumb.' . $extension;
            // save thumbnail into a file
			
            imagejpeg($tmp_img, "{$pathToImage}");
			$pathToImage1 = 'cache/'.$filename . '.thumb.' . $extension;
            $result = $pathToImage1;

			/*list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			} */
		//}
		
		/*if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}*/	
		
		return $result;
	}
}
?>