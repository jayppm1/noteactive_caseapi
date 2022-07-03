<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllerservicesfacerekognition extends Controller {
	public function jsondetectfaces() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			// var_dump($facilities_info['is_enable_add_notes_by']);
			if ($facilities_info ['is_enable_add_notes_by'] == '1') {
				if ($this->request->post ["upload_file"] != null && $this->request->post ["upload_file"] != "") {
					if ($facilities_info ['face_similar_percent'] != null && $facilities_info ['face_similar_percent'] != "0") {
						$face_similar_percent = $facilities_info ['face_similar_percent'];
					} else {
						$face_similar_percent = '90';
					}
					
					$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser ( $this->request->post ["upload_file"] );
					
					foreach ( $result_inser_user_img22 ['FaceMatches'] as $c ) {
						$similarity = $c ['Similarity'];
						$FaceId [] = $c ['Face'] ['FaceId'];
						$ImageId [] = $c ['Face'] ['ImageId'];
						$ExternalImageId = $c ['Face'] ['ExternalImageId'];
					}
					
					if ($ExternalImageId != null && $ExternalImageId != "") {
						if ($similarity > $face_similar_percent) {
							$this->load->model ( 'user/user' );
							$user_result = $this->model_user_user->getUserbyupdatefacility ( $ExternalImageId, $this->request->post ['facilities_id'] );
							
							if ($user_result ['username'] != null && $user_result ['username'] != "") {
								$error = true;
								$this->data ['facilitiess'] [] = array (
										'success' => '1',
										// 'similar' => $similarity,
										// 'username' =>
										// $user_result['username'],
										'username' => $ExternalImageId,
										// 'match_user_id' => $ExternalImageId,
										'face_notes_file' => $notes_file,
										'outputFolder' => $outputFolder 
								);
								// 'outputFolderUrl' => $outputFolderUrl
							} else {
								$error = false;
								$this->data ['facilitiess'] [] = array (
										'success' => '2',
										'warning' => 'Sorry i am having trouble recognizing you. Lets try again!!',
										// 'similar' => $similarity,
										'username' => '',
										// 'match_user_id' => $ExternalImageId,
										'face_notes_file' => $notes_file,
										'outputFolder' => $outputFolder 
								);
								// 'outputFolderUrl' => $outputFolderUrl
							}
						}
					} else {
						$error = false;
						$this->data ['facilitiess'] [] = array (
								'success' => '2',
								'warning' => 'Sorry i am having trouble recognizing you. Lets try again!!',
								// 'similar' => $similarity,
								// 'username' =>
								// $user_result['username'],
								'username' => '',
								// 'match_user_id' => $ExternalImageId,
								'face_notes_file' => $notes_file,
								'outputFolder' => $outputFolder 
						);
						// 'outputFolderUrl' => $outputFolderUrl
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Please send image!' 
					);
					$error = false;
				}
			} else {
				if ($this->request->post ["upload_file"] != null && $this->request->post ["upload_file"] != "") {
					
					if ($facilities_info ['face_similar_percent'] != null && $facilities_info ['face_similar_percent'] != "0") {
						$face_similar_percent = $facilities_info ['face_similar_percent'];
					} else {
						$face_similar_percent = '90';
					}
					
					$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser ( $outputFolderUrl );
					// var_dump($result_inser_user_img22);
					foreach ( $result_inser_user_img22 ['FaceMatches'] as $c ) {
						$similarity = $c ['Similarity'];
						$FaceId [] = $c ['Face'] ['FaceId'];
						$ImageId [] = $c ['Face'] ['ImageId'];
						$ExternalImageId = $c ['Face'] ['ExternalImageId'];
					}
					
					if ($ExternalImageId != null && $ExternalImageId != "") {
						if ($similarity > $face_similar_percent) {
							$this->load->model ( 'user/user' );
							$user_result = $this->model_user_user->getUserbyupdatefacility ( $ExternalImageId, $this->request->post ['facilities_id'] );
							
							if ($user_result ['username'] != null && $user_result ['username'] != "") {
								$error = true;
								$this->data ['facilitiess'] [] = array (
										'success' => '1',
										// 'similar' => $similarity,
										// 'username' =>
										// $user_result['username'],
										'username' => $ExternalImageId,
										// 'match_user_id' => $ExternalImageId,
										'face_notes_file' => $notes_file,
										'outputFolder' => $outputFolder 
								);
								// 'outputFolderUrl' => $outputFolderUrl
							} else {
								$error = false;
								$this->data ['facilitiess'] [] = array (
										'success' => '2',
										'warning' => 'Sorry i am having trouble recognizing you. Lets try again!!',
										// 'similar' => $similarity,
										'username' => '',
										// 'match_user_id' => $ExternalImageId,
										'face_notes_file' => $notes_file,
										'outputFolder' => $outputFolder 
								);
								// 'outputFolderUrl' => $outputFolderUrl
							}
						}
					} else {
						$error = false;
						$this->data ['facilitiess'] [] = array (
								'success' => '2',
								'warning' => 'Sorry i am having trouble recognizing you. Lets try again!!',
								// 'similar' => $similarity,
								// 'username' =>
								// $user_result['username'],
								'username' => '',
								// 'match_user_id' => $ExternalImageId,
								'face_notes_file' => $notes_file,
								'outputFolder' => $outputFolder 
						);
						// 'outputFolderUrl' => $outputFolderUrl
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Please send image!' 
					);
					$error = false;
				}
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error,
					'is_enable_add_notes_by' => $facilities_info ['is_enable_add_notes_by'] 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in wearservice jsondetectfaces ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'wear_jsondetectfaces', $activity_data2 );
		}
	}
	public function jsonclientsdetectfaces() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			 *
			 * if ($api_device_info == false) {
			 * $errorMessage = $this->model_api_encrypt->errorMessage ();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1 ();
			 *
			 * if ($api_header_value == false) {
			 * $errorMessage = $this->model_api_encrypt->errorMessage ();
			 * return $errorMessage;
			 * }
			 */
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			if ($facilities_info ['is_client_facial'] == '1') {
				
				if ($this->request->files ["upload_file"] != null && $this->request->files ["upload_file"] != "") {
					
					$extension = end ( explode ( ".", $this->request->files ["upload_file"] ["name"] ) );
					
					if ($this->request->files ["upload_file"] ["size"] < 42214400) {
						$neextension = strtolower ( $extension );
						if ($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi') {
							
							$notes_file = uniqid () . "." . $extension;
							// $outputFolder = DIR_IMAGE . 'facerecognition/' . $notes_file;
							
							$outputFolder = $this->request->files ["upload_file"] ["tmp_name"];
							// move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
							
							// $outputFolderUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
							
							$facilities_id = $this->request->post ['facilities_id'];
							
							// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->request->post ['facilities_id'] );
							
							$outputFolderUrl = $s3file;
							
							if ($facilities_info ['face_similar_percent'] != null && $facilities_info ['face_similar_percent'] != "0") {
								$face_similar_percent = $facilities_info ['face_similar_percent'];
							} else {
								$face_similar_percent = '90';
							}
							
							$picture_filename = pathinfo ( $notes_file, PATHINFO_FILENAME );
							
							$path_to_thumbs_directory = DIR_IMAGE . 'facerecognition/';
							
							list ( $width, $height, $type, $attr ) = getimagesize ( $outputFolder );
							// var_dump($width);
							// var_dump($height);
							
							if ($this->request->post ['face_detect'] == '1') {
								
								$result_inser_user_img22 = $this->awsimageconfig->DetectFaces ( $outputFolderUrl );
								
								foreach ( $result_inser_user_img22 ['FaceDetails'] as $d ) {
									$similar = $d ['BoundingBox'];
									// var_dump($similar);
									$new_image_1 = uniqid () . "." . $extension;
									// var_dump($d);
									// $image = new Image($outputFolder);
									// $image->resize($width, $height, "h");
									// $image->save($path_to_thumbs_directory. $new_image_1);
									
									// $cpssxx = round(($similar['Left'] + ($similar['Width'] / 2)) * 100);
									// $cpssyy = round(($similar['Top'] + ($similar['Height'] / 2)) * 100);
									
									// $cpossxx = round($width * ($cpssxx / 100));
									// $cpossyy = round($height * ($cpssyy / 100));
									
									$cpossxx = round ( $width * $similar ['Left'] );
									$cpssyy = round ( $height * $similar ['Top'] );
									
									$newwidth = '60' + round ( $width * $similar ['Width'] );
									$newheight = "30" + round ( $height * $similar ['Height'] );
									
									$im = imagecreatefromjpeg ( $outputFolderUrl );
									
									$size = min ( imagesx ( $im ), imagesy ( $im ) );
									$im2 = imagecrop ( $im, [ 
											'x' => $cpossxx,
											'y' => $cpssyy,
											'width' => $newwidth,
											'height' => $newheight 
									] );
									
									if ($im2 !== FALSE) {
										imagepng ( $im2, $path_to_thumbs_directory . $new_image_1 );
										imagedestroy ( $im2 );
									}
									imagedestroy ( $im );
									
									$newimagename = $new_image_1;
									$newimage = $path_to_thumbs_directory . $new_image_1;
									$outputFolderUrl1 = HTTP_SERVER . 'image/facerecognition/' . $new_image_1;
									
									$result_inser_user_img1 = $this->awsimageconfig->searchFacesByImage ( $outputFolderUrl1, $this->request->post ['facilities_id'] );
									
									// var_dump($result_inser_user_img1);
									
									foreach ( $result_inser_user_img1 ['FaceMatches'] as $c ) {
										$similarity = $c ['Similarity'];
										$FaceId [] = $c ['Face'] ['FaceId'];
										$ImageId [] = $c ['Face'] ['ImageId'];
										$ExternalImageId = $c ['Face'] ['ExternalImageId'];
										
										// var_dump($ExternalImageId);
										if ($ExternalImageId != null && $ExternalImageId != "") {
											
											$taginfo_a = $this->model_setting_tags->getTagbyEMPID ( $ExternalImageId );
											if ($taginfo_a ['facilities_id'] != null && $taginfo_a ['facilities_id'] != "" ) {
												if ($similarity > $face_similar_percent) {
													
													$get_img = $this->model_setting_tags->getImage ( $taginfo_a ['tags_id'] );
													
													if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
														$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
													} else {
														$upload_file_thumb_1 = $get_img ['enroll_image'];
													}
													
													$error = true;
													$this->data ['facilitiess'] [] = array (
															'success' => '1',
															'emp_tag_id' => $taginfo_a ['emp_tag_id'],
															'tags_id' => $taginfo_a ['tags_id'],
															'enroll_image' => $upload_file_thumb_1,
															'face_notes_file' => $notes_file,
															'outputFolder' => $outputFolder ,
															'facilities_id' => $this->request->post ['facilities_id'] ,
													);
													// 'client_face_notes_file_small' => $newimagename,
													// 'client_outputFolder_small' => $newimage,
													// 'outputFolderUrl1_small' => $outputFolderUrl1,
												}
											}
										} /*
										   * else{
										   * $error = true;
										   * $this->data['facilitiess'][] = array(
										   * 'success' => '2',
										   * 'warning' => 'Sorry i am having trouble in recognizing you. Lets try again!!',
										   * 'emp_tag_id' => '',
										   * 'client_face_notes_file' => $newimagename,
										   * 'client_outputFolder' => $newimage,
										   * 'outputFolderUrl1' => $outputFolderUrl1,
										   * );
										   * }
										   */
									}
									
									unlink ( $newimage );
								}
							}
							// var_dump($this->data['facilitiess']);
							
							if ($this->request->post ["image_not_delete"] != '1') {
								// unlink($outputFolder);
							}
							
							$error = true;
							$client_face_notes_file = $notes_file;
							$client_outputFolder = $outputFolder;
							$client_outputFolderUrl = $outputFolderUrl;
						} else {
							$this->data ['facilitiess'] [] = array (
									'warning' => 'video or audio file not valid!' 
							);
							$error = false;
							$client_face_notes_file = '';
							$client_outputFolder = '';
							$client_outputFolderUrl = '';
						}
					} else {
						$this->data ['facilitiess'] [] = array (
								'warning' => 'Maximum size file upload!' 
						);
						$error = false;
						$client_face_notes_file = '';
						$client_outputFolder = '';
						$client_outputFolderUrl = '';
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Please select file!' 
					);
					$error = false;
					$client_face_notes_file = '';
					$client_outputFolder = '';
					$client_outputFolderUrl = '';
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please activate face recognition setting!' 
				);
				$error = false;
				$client_face_notes_file = '';
				$client_outputFolder = '';
				$client_outputFolderUrl = '';
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error,
					'client_face_notes_file' => $client_face_notes_file,
					'client_outputFolder' => $client_outputFolder,
					'client_outputFolderUrl' => $client_outputFolderUrl,
					'is_enable_add_notes_by' => $facilities_info ['is_enable_add_notes_by'] 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in wearservice jsonclientsdetectfaces ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'wear_jsonclientsdetectfaces', $activity_data2 );
		}
	}
	public function getkinisesdata() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			if ($this->request->post ['videokinesisarn'] != null && $this->request->post ['videokinesisarn'] != "") {
				
				if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
					$this->load->model ( 'user/user' );
					
					$users = $this->model_user_user->getkinisesdatas ( 'user' );
					
					$videokinesisarn = $this->request->post ['videokinesisarn'];
					
					// var_dump($videokinesisarn);
					
					foreach ( $users as $user ) {
						
						$res = json_decode ( $user ['data'] );
						// var_dump($res->InputInformation->KinesisVideo->StreamArn);
						// var_dump($res->InputInformation);
						
						if ($res->InputInformation->KinesisVideo->StreamArn == $videokinesisarn) {
							
							foreach ( $res->FaceSearchResponse as $c ) {
								
								foreach ( $c->MatchedFaces as $b ) {
									// var_dump($b->Similarity);
									// var_dump($b->Face->ExternalImageId);
									$ExternalImageId = $b->Face->ExternalImageId;
									$similarity = $b->Similarity;
								}
							}
							
							// var_dump($ExternalImageId);
							// var_dump($this->request->post ['facilities_id']);
							$this->load->model ( 'user/user' );
							$user_result = $this->model_user_user->getUserbyupdatefacility ( $ExternalImageId, $this->request->post ['facilities_id'] );
							
							if ($user_result ['username'] != null && $user_result ['username'] != "") {
								$facilities = explode ( ',', $user_result ['facilities'] );
								
								if (in_array ( $this->request->post ['facilities_id'], $facilities )) {
									$user_img = $this->model_user_user->getenroll_image ( $user_result ['user_id'] );
									
									$this->data ['facilitiess'] [] = array (
											'user_id' => $ExternalImageId,
											'username' => $user_result ['username'],
											'enroll_image' => $user_img ['enroll_image'],
											'similarity' => $similarity 
									);
									
									$this->db->query ( "UPDATE `" . DB_PREFIX . "kinesis_data` SET status = '2' WHERE kinesis_data_id = '" . $user ['kinesis_data_id'] . "'" );
								} else {
									$this->data ['facilitiess'] [] = array (
											'warning' => "no user found" 
									);
								}
							} else {
								$this->data ['facilitiess'] [] = array (
										'warning' => "no user found" 
								);
							}
						}
					}
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => true 
					);
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "facility id is required" 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => $error 
					);
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "videokinesisarn is required" 
				);
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in getkinisesdata ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'getkinisesdata', $activity_data2 );
		}
	}
	public function getkinisesclientdata() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			 *
			 * if ($api_device_info == false) {
			 * $errorMessage = $this->model_api_encrypt->errorMessage ();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1 ();
			 *
			 * if ($api_header_value == false) {
			 * $errorMessage = $this->model_api_encrypt->errorMessage ();
			 * return $errorMessage;
			 * }
			 */
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
			
			if ($this->request->post ['videokinesisarn'] != null && $this->request->post ['videokinesisarn'] != "") {
				
				if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
					$this->load->model ( 'user/user' );
					
					$users = $this->model_user_user->getkinisesdatas ( 'client' );
					
					$videokinesisarn = $this->request->post ['videokinesisarn'];
					
					// var_dump($videokinesisarn);
					
					foreach ( $users as $user ) {
						
						$res = json_decode ( $user ['data'] );
						// var_dump($res);
						// var_dump($res->InputInformation->KinesisVideo->StreamArn);
						// var_dump($res->InputInformation);
						
						if ($res->InputInformation->KinesisVideo->StreamArn == $videokinesisarn) {
							
							foreach ( $res->FaceSearchResponse as $c ) {
								
								foreach ( $c->MatchedFaces as $b ) {
									// var_dump($b);
									// var_dump($b->Face->ExternalImageId);
									$ExternalImageId = $b->Face->ExternalImageId;
									$similarity = $b->Similarity;
								}
							}
							
							// var_dump($ExternalImageId);
							// var_dump($this->request->post ['facilities_id']);
							
							$taginfo_a = $this->model_setting_tags->getTagbyEMPID ( $ExternalImageId );
							
							if ($taginfo_a ['tags_id'] != null && $taginfo_a ['tags_id'] != "") {
								if ($this->request->post ['facilities_id'] == $taginfo_a ['facilities_id']) {
									$get_img = $this->model_setting_tags->getImage ( $taginfo_a ['tags_id'] );
									
									if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
										$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
									} else {
										if ($get_img ['enroll_image'] != null && $get_img ['enroll_image'] != "") {
											$upload_file_thumb_1 = $get_img ['enroll_image'];
										} else {
											$upload_file_thumb_1 = '';
										}
									}
									
									$this->data ['facilitiess'] [] = array (
											'emp_tag_id' => $taginfo_a ['emp_tag_id'],
											'tags_id' => $taginfo_a ['tags_id'],
											'enroll_image' => $upload_file_thumb_1,
											'similarity' => $similarity 
									);
									$this->db->query ( "UPDATE `" . DB_PREFIX . "kinesis_data` SET status = '2' WHERE kinesis_data_id = '" . $user ['kinesis_data_id'] . "'" );
								} else {
									$this->data ['facilitiess'] [] = array (
											'warning' => "no client found" 
									);
								}
							} else {
								$this->data ['facilitiess'] [] = array (
										'warning' => "no client found" 
								);
							}
						}
					}
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => true 
					);
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "facility id is required" 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => $error 
					);
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "videokinesisarn is required" 
				);
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in getkinisesdata ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'getkinisesdata', $activity_data2 );
		}
	}
	public function jsonusersdetectfaces() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
			
			if ($facilities_info ['is_client_facial'] == '1') {
				
				if ($this->request->files ["upload_file"] != null && $this->request->files ["upload_file"] != "") {
					
					$extension = end ( explode ( ".", $this->request->files ["upload_file"] ["name"] ) );
					
					if ($this->request->files ["upload_file"] ["size"] < 42214400) {
						$neextension = strtolower ( $extension );
						if ($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi') {
							
							$notes_file = uniqid () . "." . $extension;
							// $outputFolder = DIR_IMAGE . 'facerecognition/' . $notes_file;
							
							$outputFolder = $this->request->files ["upload_file"] ["tmp_name"];
							// move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
							
							// $outputFolderUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
							
							$facilities_id = $this->request->post ['facilities_id'];
							
							// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->request->post ['facilities_id'] );
							
							$outputFolderUrl = $s3file;
							
							if ($facilities_info ['face_similar_percent'] != null && $facilities_info ['face_similar_percent'] != "0") {
								$face_similar_percent = $facilities_info ['face_similar_percent'];
							} else {
								$face_similar_percent = '90';
							}
							
							$picture_filename = pathinfo ( $notes_file, PATHINFO_FILENAME );
							
							$path_to_thumbs_directory = DIR_IMAGE . 'facerecognition/';
							
							list ( $width, $height, $type, $attr ) = getimagesize ( $outputFolder );
							// var_dump($width);
							// var_dump($height);
							
							if ($this->request->post ['face_detect'] == '1') {
								
								$result_inser_user_img22 = $this->awsimageconfig->DetectFaces ( $outputFolderUrl );
								
								// var_dump($result_inser_user_img22);
								
								foreach ( $result_inser_user_img22 ['FaceDetails'] as $d ) {
									$similar = $d ['BoundingBox'];
									// var_dump($similar);
									$new_image_1 = uniqid () . "." . $extension;
									// var_dump($d);
									// $image = new Image($outputFolder);
									// $image->resize($width, $height, "h");
									// $image->save($path_to_thumbs_directory. $new_image_1);
									
									// $cpssxx = round(($similar['Left'] + ($similar['Width'] / 2)) * 100);
									// $cpssyy = round(($similar['Top'] + ($similar['Height'] / 2)) * 100);
									
									// $cpossxx = round($width * ($cpssxx / 100));
									// $cpossyy = round($height * ($cpssyy / 100));
									
									$cpossxx = round ( $width * $similar ['Left'] );
									$cpssyy = round ( $height * $similar ['Top'] );
									
									$newwidth = '60' + round ( $width * $similar ['Width'] );
									$newheight = "30" + round ( $height * $similar ['Height'] );
									
									$im = imagecreatefromjpeg ( $outputFolderUrl );
									
									$size = min ( imagesx ( $im ), imagesy ( $im ) );
									$im2 = imagecrop ( $im, [ 
											'x' => $cpossxx,
											'y' => $cpssyy,
											'width' => $newwidth,
											'height' => $newheight 
									] );
									
									if ($im2 !== FALSE) {
										imagepng ( $im2, $path_to_thumbs_directory . $new_image_1 );
										imagedestroy ( $im2 );
									}
									imagedestroy ( $im );
									
									$newimagename = $new_image_1;
									$newimage = $path_to_thumbs_directory . $new_image_1;
									$outputFolderUrl1 = HTTP_SERVER . 'image/facerecognition/' . $new_image_1;
									
									$result_inser_user_img1 = $this->awsimageconfig->searchFacesByImagebyuser ( $outputFolderUrl1 );
									
									// var_dump($result_inser_user_img1);
									
									foreach ( $result_inser_user_img1 ['FaceMatches'] as $c ) {
										$similarity = $c ['Similarity'];
										$FaceId [] = $c ['Face'] ['FaceId'];
										$ImageId [] = $c ['Face'] ['ImageId'];
										$ExternalImageId = $c ['Face'] ['ExternalImageId'];
										
										// var_dump($ExternalImageId);
										if ($ExternalImageId != null && $ExternalImageId != "") {
											
											$this->load->model ( 'user/user' );
											$user_result = $this->model_user_user->getUserbyupdatefacility ( $ExternalImageId, $this->request->post ['facilities_id'] );
											
											if ($user_result ['username'] != null && $user_result ['username'] != "") {
												$error = true;
												$user_img = $this->model_user_user->getenroll_image ( $user_result ['user_id'] );
												
												$this->data ['facilitiess'] [] = array (
														'success' => '1',
														// 'similar' => $similarity,
														// 'username' =>
														// $user_result['username'],
														'username' => $user_result ['username'],
														'enroll_image' => $user_img ['enroll_image'],
														// 'match_user_id' => $ExternalImageId,
														'face_notes_file' => $notes_file,
														'outputFolder' => $outputFolder 
												);
												// 'outputFolderUrl' => $outputFolderUrl
											} else {
												$error = false;
												$this->data ['facilitiess'] [] = array (
														'success' => '2',
														'warning' => 'Sorry i am having trouble recognizing you. Lets try again!!',
														// 'similar' => $similarity,
														'username' => '',
														// 'match_user_id' => $ExternalImageId,
														'face_notes_file' => $notes_file,
														'outputFolder' => $outputFolder 
												);
												// 'outputFolderUrl' => $outputFolderUrl
											}
											
											$taginfo_a = $this->model_setting_tags->getTagbyEMPID ( $ExternalImageId );
										}
									}
									
									unlink ( $newimage );
								}
							}
							// var_dump($this->data['facilitiess']);
							
							if ($this->request->post ["image_not_delete"] != '1') {
								// unlink($outputFolder);
							}
							
							$error = true;
							$client_face_notes_file = $notes_file;
							$client_outputFolder = $outputFolder;
							$client_outputFolderUrl = $outputFolderUrl;
						} else {
							$this->data ['facilitiess'] [] = array (
									'warning' => 'video or audio file not valid!' 
							);
							$error = false;
							$client_face_notes_file = '';
							$client_outputFolder = '';
							$client_outputFolderUrl = '';
						}
					} else {
						$this->data ['facilitiess'] [] = array (
								'warning' => 'Maximum size file upload!' 
						);
						$error = false;
						$client_face_notes_file = '';
						$client_outputFolder = '';
						$client_outputFolderUrl = '';
					}
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Please select file!' 
					);
					$error = false;
					$client_face_notes_file = '';
					$client_outputFolder = '';
					$client_outputFolderUrl = '';
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please activate face recognition setting!' 
				);
				$error = false;
				$client_face_notes_file = '';
				$client_outputFolder = '';
				$client_outputFolderUrl = '';
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error,
					'client_face_notes_file' => $client_face_notes_file,
					'client_outputFolder' => $client_outputFolder,
					'client_outputFolderUrl' => $client_outputFolderUrl,
					'is_enable_add_notes_by' => $facilities_info ['is_enable_add_notes_by'] 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in wearservice jsonusersdetectfaces ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonusersdetectfaces', $activity_data2 );
		}
	}
}


