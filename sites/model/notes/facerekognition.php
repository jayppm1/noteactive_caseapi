<?php

class Modelnotesfacerekognition extends Model
{

    public function getfacerekognition ($data, $notes_id)
    {
        if ($this->config->get('config_face_recognition') == '1') {
            if ($data['current_enroll_image1'] == '1') {
                if ($data['current_enroll_image'] != '' && $data['current_enroll_image'] != null) {
                    
                    /*$img = $data['current_enroll_image'];
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $Imgdata = base64_decode($img);
                    
                    $notes_file = uniqid() . '.jpeg';
                    
                    $file = DIR_IMAGE . '/facerecognition/' . $notes_file;
                    $success = file_put_contents($file, $Imgdata);
                    
                    $outputFolder = $file;
                    
                    $outputFolderUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
					*/
                    
                    // $face_similar_percent =
                    // $this->customer->isface_similar_percent();
                    
                    if ($this->config->get('face_similar_percent') != null && $this->config->get('face_similar_percent') != "") {
                        $face_similar_percent = $this->config->get('face_similar_percent');
                    } else {
                        $face_similar_percent = '90';
                    }
                    
                    $web_app = '1';
                    //require_once (DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config.php');
					
					$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser($data['current_enroll_image']);
						   
					foreach($result_inser_user_img22['FaceMatches'] as $c){
						$similarity = $c['Similarity'];
						$FaceId[] = $c['Face']['FaceId'];
						$ImageId[] = $c['Face']['ImageId'];
						$ExternalImageId = $c['Face']['ExternalImageId'];
						
					}
                    
                    // if($this->customer->isallowface_without_verified() ==
                    // '1'){
                    //require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                    $this->load->model('notes/notes');
                    $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                    // }
                    
                    if ($similarity > $face_similar_percent) {
                        
                        $this->model_notes_notes->updateuserverified('1', $notes_id);
                        
                        // $this->load->model('user/user');
                        // $user_info =
                        // $this->model_user_user->geteuser_info_byfaceid($FaceId);
                        
                        // $user_result =
                        // $this->model_user_user->getUserbyupdate($user_info['user_id']);
                        /*
                         * $udata = array();
                         * $udata['outputFolderUrl'] = $outputFolderUrl;
                         * $udata['user_id'] = $user_info['user_id'];
                         * $udata['enroll_image'] = $s3file;
                         * $udata['notes_file'] = $notes_file;
                         * $udata['facilities_id'] = $this->customer->getId();
                         * $this->updateuser_enroll_info($udata);
                         */
                        
                        $this->session->data['username_confirm'] = $ExternalImageId;
                        $this->session->data['local_image_dir'] = $outputFolder;
                        $this->session->data['local_image_url'] = $outputFolderUrl;
                        
                        // unlink($file);
                    } else {
                        unlink($this->session->data['local_image_dir']);
                        unset($this->session->data['username_confirm']);
                        unset($this->session->data['local_image_dir']);
                        unset($this->session->data['local_image_url']);
                        
                        $this->model_notes_notes->updateuserverified('2', $notes_id);
                    }
                }
            }
        }
    }

    public function getfacerekognitioncompare ($data, $notes_id)
    {
        if ($this->config->get('config_face_recognition') == '1') {
            if ($data['current_enroll_image1'] == '1') {
                if ($data['current_enroll_image'] != '' && $data['current_enroll_image'] != null) {
                    
                    /*$img = $data['current_enroll_image'];
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $Imgdata = base64_decode($img);
                    
                    $notes_file = uniqid() . '.jpeg';
                    
                    $file = DIR_IMAGE . '/facerecognition/' . $notes_file;
                    $success = file_put_contents($file, $Imgdata);
                    
                    $outputFolder = $file;
                    
                    $outputFolderUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
					*/
                    
                    $usercurrentimage = $outputFolderUrl;
                    
                    $this->session->data['local_image_dir'] = $outputFolder;
                    $this->session->data['local_image_url'] = $outputFolderUrl;
                    
                    $this->load->model('user/user');
                    
                    // var_dump($data['user_id']);
                    
                    $user_info = $this->model_user_user->getUserByUsername($data['user_id']);
                    // var_dump($user_info['user_id']);
                    
                    $user_result = $this->model_user_user->getenroll_images($user_info['user_id']);
                    // var_dump($user_result);
                    
                    // $useroriginal = $user_result['enroll_image'];
                    
                    if (empty($user_result)) {
                        $facematch = '3';
                        return $facematch;
                    }
                    
                    $web_app = '1';
                    //require_once (DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config.php');
					
					$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser($data['current_enroll_image']);
						   
					foreach($result_inser_user_img22['FaceMatches'] as $c){
						$similarity = $c['Similarity'];
						$FaceId[] = $c['Face']['FaceId'];
						$ImageId[] = $c['Face']['ImageId'];
						$ExternalImageId = $c['Face']['ExternalImageId'];
						
					}
                    // require_once(DIR_APPLICATION_AWS .
                    // 'facerecognition_config.php');
                    
                    // $face_similar_percent =
                    // $this->customer->isface_similar_percent();
                    
                    if ($this->config->get('face_similar_percent') != null && $this->config->get('face_similar_percent') != "") {
                        $face_similar_percent = $this->config->get('face_similar_percent');
                    } else {
                        $face_similar_percent = '90';
                    }
                    
                    if ($similarity > $face_similar_percent) {
                        
                        // $user_info1 =
                        // $this->model_user_user->geteuser_info_byfaceid($FaceId);
                        
                        // $user_result1 =
                        // $this->model_user_user->getUserbyupdate($user_info1['user_id']);
                        
                        if ($ExternalImageId == $data['user_id']) {
                            
                            // if($this->customer->isallowface_without_verified()
                            // == '1'){
								
								$facilities_id = $this->customer->getId(); 
                            require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                            
                            $this->load->model('notes/notes');
                            $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                            // }
                            unlink($file);
                            $facematch = '1';
                        } else {
                            $facematch = '2';
                        }
                    } else {
                        $facematch = '2';
                    }
                }
            }
        }
        
        return $facematch;
    }

    public function jsongetfacerekognitioncompare ($user_result, $face_similar_percent, $imagedata, $username)
    {
        $outputFolder = $imagedata['outputFolder'];
        $outputFolderUrl = $imagedata['outputFolderUrl'];
        
        $usercurrentimage = $outputFolderUrl;
        
        // $useroriginal = $user_result['enroll_image'];
        
        if ($this->config->get('face_similar_percent') != null && $this->config->get('face_similar_percent') != "") {
            $face_similar_percent = $this->config->get('face_similar_percent');
        } else {
            $face_similar_percent = $face_similar_percent;
        }
        
        $web_app = '2';
        // require_once(DIR_APPLICATION_AWS . 'facerecognition_config.php');
        //require_once (DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config.php');
		
		$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser($outputFolderUrl);
						   
		foreach($result_inser_user_img22['FaceMatches'] as $c){
			$similarity = $c['Similarity'];
			$FaceId[] = $c['Face']['FaceId'];
			$ImageId[] = $c['Face']['ImageId'];
			$ExternalImageId = $c['Face']['ExternalImageId'];
			
		}
        
        if ($similarity > $face_similar_percent) {
            // $this->load->model('user/user');
            // $user_info1 =
            // $this->model_user_user->geteuser_info_byfaceid($FaceId);
            
            // $user_result1 =
            // $this->model_user_user->getUserbyupdate($user_info1['user_id']);
            
            if ($ExternalImageId == $username) {
                $facematch = '1';
            } else {
                $facematch = '2';
            }
        } else {
            $facematch = '2';
        }
        
        return $facematch;
    }

    public function jsonuploadfacerekognitioncompare ($data)
    {
        $imagedata = array();
        
        if ($data["upload_file"] != null && $data["upload_file"] != "") {
            
            /*$extension = end(explode(".", $data["upload_file"]["name"]));
            
            if ($data["upload_file"]["size"] < 42214400) {
                $neextension = strtolower($extension);
                if ($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi') {
                    
                    $notes_file = uniqid() . "." . $extension;
                    $outputFolder = DIR_IMAGE . 'facerecognition/' . $notes_file;
                    move_uploaded_file($data["upload_file"]["tmp_name"], $outputFolder);
                    
                    $outputFolderUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
                    
                    $imagedata['notes_file'] = $notes_file;
                    $imagedata['outputFolder'] = $outputFolder;
                    $imagedata['outputFolderUrl'] = $outputFolderUrl;
                } else {
                    $imagedata['warning_e'] = 'video or audio file not valid!';
                }
            } else {
                $imagedata['warning_e'] = 'Maximum size file upload!';
            }*/
			
			$image_parts = explode(";base64,", $data["upload_file"]);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$notes_file = uniqid() . '.'.$image_type;
			
			$imagedata['notes_file'] = $notes_file;
			$imagedata['outputFolder'] = $data["upload_file"];
			$imagedata['outputFolderUrl'] = $outputFolderUrl;
			
			
        } else {
            $imagedata['warning_e'] = 'Please select image!';
        }
        
        return $imagedata;
    }

    public function updateuser_enroll_info ($data)
    {
        $this->load->model('setting/timezone');
        $this->load->model('facilities/facilities');
        
        $facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
        
        $timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
        
        date_default_timezone_set($timezone_info['timezone_value']);
        
        $onemonth = date('Y-m-d', strtotime('-1 Months'));
        
        $last_date = date('Y-m-d', strtotime($onemonth));
        
        $sql1 = "select COUNT(DISTINCT user_enroll_id) AS total FROM `" . DB_PREFIX . "user_enroll` where `date_updated` <= '" . $last_date . " 23:59:59' ";
        
        $query33 = $this->db->query($sql1);
        
        if ($query33->row['total'] > 0) {
            
            $outputFolder = $data['outputFolder'];
            $notes_file = $data['notes_file'];
            $user_id = $data['user_id'];
            
            //require_once (DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
			$this->load->model('user/user');
			$user_info2 = $this->model_user_user->getUserbyupdate($user_id);
			
			$metadata = array();
			$metadata['username'] = $user_info2['username'];
			$metadata['user_id'] = $user_info2['user_id'];
			$metadata['firstname'] = $user_info2['firstname'];
			$metadata['lastname'] = $user_info2['lastname'];
			$metadata['facilities'] = $user_info2['facilities'];
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomer($user_info2['customer_key']);
			$customer_bucket = $customer_info['bucket'];
			
			$s3file = $this->awsimageconfig->uploadFile2($customer_bucket, $notes_file, $outputFolder, $metadata);
            
            //require_once (DIR_APPLICATION_AWS . 'facerecognition_insert_user_config.php');
            
            $sqlt = "SELECT * FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '" . $data['user_id'] . "' order by RAND() LIMIT 1 ";
            $bedtu = $this->db->query($sqlt);
            
            $user_enroll_id = $bedtu->row['user_enroll_id'];
            
            $date_added = date('Y-m-d H:i:s', strtotime('now'));
            
            $usql2 = "UPDATE " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape($data['enroll_image']) . "',user_id = '" . $this->db->escape($data['user_id']) . "',FaceId = '" . $this->db->escape($FaceId) . "', ImageId = '" . $this->db->escape($ImageId) . "', date_updated = '" . $date_added . "' WHERE user_enroll_id = '" . $user_enroll_id . "' ";
            
            $this->db->query($usql2);
        } else {
            
            $susql = "SELECT COUNT(DISTINCT user_enroll_id) AS total FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '" . $data['user_id'] . "' ";
            $query = $this->db->query($susql);
            
            if ($query->row['total'] < CUSTOM_USERPIC) {
                
                $outputFolder = $data['outputFolder'];
                $notes_file = $data['notes_file'];
                $user_id = $data['user_id'];
                
				
				$s3file = $this->awsimageconfig->uploadFile($notes_file, $outputFolder, $data['facilities_id']);
				
                //require_once (DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
                //require_once (DIR_APPLICATION_AWS . 'facerecognition_insert_user_config.php');
                
                $date_added = date('Y-m-d H:i:s', strtotime('now'));
                
                $usql = "INSERT INTO " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape($data['enroll_image']) . "',user_id = '" . $this->db->escape($data['user_id']) . "',FaceId = '" . $this->db->escape($FaceId) . "', ImageId = '" . $this->db->escape($ImageId) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
                
                $this->db->query($usql);
            }
        }
    }
}
?>