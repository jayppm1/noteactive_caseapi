<?php

class ControllerCommonLicence extends Controller
{

    private $error = array();

    public function index ()
    {}

    public function checkactivation ()
    {}

    public function activation ()
    {
        $this->language->load('common/licence');
        $this->document->setTitle('Access Key');
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['heading_title'] = 'Access Key';
        
        $this->load->model('licence/licence');
        $data = array();
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if ($this->customer->isLogged()) {
            $this->redirect($this->url->link('common/logout', '', 'SSL'));
        }
		$this->data['action'] = $this->url->link('common/licence/activation', '' . $url, 'SSL');
		
		//var_dump($this->config->get('config_saml'));
		//var_dump($this->request->post);
		
        unset($this->session->data['session_key']);
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
			if($this->config->get('config_saml') == '0'){
            if ($this->request->post['access'] == 'accessKeyInput') {
                
                $result = $this->model_licence_licence->insert_activationkey($this->request->post);
			
                if ($result == null && $result == "") {
                    $url3 = "";
                    
                    if ($this->request->post['activationkey'] != null && $this->request->post['activationkey'] != "") {
                        // $url3 .= '&activationkey=' .
                        // $this->request->post['activationkey'];
                        $this->session->data['reset_activationkey'] = $this->request->post['activationkey'];
                        
                        if ($this->request->post['isPrivate'] == '1') {
                            
                            $this->load->model('user/user');
                            $accessResult = $this->model_user_user->getUserByAccessKey($this->request->post['activationkey']);
                            
                            $this->load->model('user/user_group');
                            $userPrivate = $this->model_user_user_group->getUserGroup($accessResult['user_group_id']);
                            
                            if ($userPrivate['is_private'] == '0') {
                                $this->session->data['error'] = "You are not authorized as private member";
                                
                                $url4 = "";
                                $url4 .= '&is_private=' . $this->request->post['isPrivate'];
                                $this->redirect($this->url->link('common/licence/activation', '' . $url4, 'SSL'));
                            }
                        }
                    }
                    $this->session->data['isPrivate'] = $this->request->post['isPrivate'];
                    $setUrl = $this->url->link('common/licence/resetSession', '' . $url3, 'SSL');
                     $this->data['errorinvalid'] = 'Your total number of
                     concurrent licenses has been exceeded. You can ask others
                     to log-off or ask your administrator to reset the
                     sessions. To Reset Session <a href="'.$setUrl.'">Click
                     Here</a>';
						//$this->data['errorinvalid'] = 'Your total number of concurrent licenses has been exceeded. You can ask others to log-off or ask your administrator to reset the sessions.';
                } else {
                    if ($this->request->post['isPrivate'] == '1') {
                        
                        $this->load->model('user/user');
                        $accessResult = $this->model_user_user->getUserByAccessKey($this->request->post['activationkey']);
                        
                        $this->load->model('user/user_group');
                        $userPrivate = $this->model_user_user_group->getUserGroup($accessResult['user_group_id']);
                        
                        if ($userPrivate['is_private'] == '0') {
                            $this->session->data['error'] = "You are not authorized as private member";
                            $url4 = "";
                            $url4 .= '&is_private=' . $this->request->post['isPrivate'];
                            $this->redirect($this->url->link('common/licence/activation', '' . $url4, 'SSL'));
                        }
                    }
                    
                    $this->session->data['isPrivate'] = $this->request->post['isPrivate'];
                    
                    $this->session->data['username'] = $result->username;
					$this->session->data['webuser_id'] = $result->user_id;
                    $this->session->data['activationkey'] = $result->Activitation_key;
                    $this->session->data['licfacilities'] = $result->facilities;
                    
                    
                    
                    
                    $this->load->model('user/user');
                    $user_info = $this->model_user_user->getUser($result->user_id);
                    
                    $this->load->model ( 'customer/customer' );
                    $customer_info = $this->model_customer_customer->getcustomer ( $user_info ['customer_key']);
                    
                    $this->session->data['webcustomer_key'] = $customer_info['customer_key'];
                    
                    
                    // var_dump($result->check_user_key);
                    
                    if ($result->check_user_key == '0' || $result->check_user_key == '1') {
                        $this->session->data['session_key'] = rand(0, 100000);
                    } else {
                        $this->session->data['session_key'] = $result->check_user_key;
                    }
                    // var_dump($this->session->data['session_key']);
					
					if(USERDEMO == 0){
						
					$this->load->model('facilities/facilities');
					$this->load->model('user/user');
					$userdetail = $this->model_user_user->getUserByAccessKey($this->request->post['activationkey']);
			
					$phone_number = $userdetail['phone_number'];
					$randomNum = mt_rand(100000, 999999);
					$message = 'Your OTP for activation is '.$randomNum;
					$this->load->model('api/smsapi');
					$sdata = array();
					$sdata['message'] = $message;
					$sdata['phone_number'] = $phone_number;
					
					if ($userdetail['facilities'] != null && $userdetail['facilities'] != "") {
                        $facilities = explode(',', $userdetail['facilities']);
                        
                        
                        foreach ($facilities as $facility) {
                            $facilities_info = $this->model_facilities_facilities->getfacilities($facility);
                            
                            if ($facilities_info['sms_number'] != null && $facilities_info['sms_number'] != "") {
                                $facilities_id = $facilities_info['facilities_id'];
                                break;
                            }
                        }
                    }
                    
                    $sdata['facilities_id'] = $facilities_id;
						
					if($userdetail['phone_number'] != null && $userdetail['phone_number'] != ""){	
						$response = $this->model_api_smsapi->sendsms($sdata);
					}
					
					
					$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$timezone_name = $timezone_info['timezone_value'];
					$timeZone = date_default_timezone_set($timezone_name);
					
					$date_added11 = date('Y-m-d H:i:s', strtotime('now'));
					
					$share_note_otp = rand(0,100000);
					
					$dataotp = array(
						'user_id' => $userdetail['user_id'],
						'otp' => $randomNum,
						'date_added' => $date_added11,
						'response' => $response,
						'facilities_id' => $facilities_id,
						'notes_id' => '0',
						'alternate_email' => $userdetail['email'],
						'share_note_otp' => $share_note_otp,
						'status' => '1',
						'otp_type' => 'dual_note',
					);
					
					$this->model_user_user->insertUserOTP($dataotp);
					
					if($userdetail['email'] != null && $userdetail['email'] != ""){
						
						$this->load->model('api/emailapi');
					
						/*$edata = array();
						$edata['message'] = $message;
						$edata['subject'] = $facilities_info['facility'].' | '.'Your verification code';
						$edata['user_email'] = $userdetail['email'];
							
					$email_status = $this->model_api_emailapi->sendmail($edata);*/

					$edata = array();
						$edata['message'] = $message;
						$edata['facility'] = $facilities_info['facility'];
						$edata['user_email'] = $userdetail['email'];
						$edata['when_date']=date("l");
						$edata['who_user']=$userdetail['username'];
						$edata['type']="8";
						$edata['notes_description']=$message;
						$edata['subject'] = $facilities_info['facility'].' | '.'Your verification code';
						$email_status = $this->model_api_emailapi->sendmail($edata);
						//$email_status=$this->model_api_emailapi->createMails($edata);	
					}
					
					$this->session->data['sms_user_id'] = $userdetail['user_id'];
					$this->session->data['time_zone'] = $timezone_name;
					$this->session->data['share_note_otp'] = $share_note_otp;
                    
                    // die;
                    if ($result->username == "") {
                        //$this->redirect($this->url->link('common/licence/activationdetail&step=2', '', 'SSL'));
						$this->redirect($this->url->link('common/verifyotp', '', 'SSL'));
                    } else {
						
						if ($this->request->post['isPrivate'] == '1') {
							$this->redirect($this->url->link('common/verifyuser', '', 'SSL'));
						}else{
							//$this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
							
							$this->redirect($this->url->link('common/verifyotp', '', 'SSL'));
						}
						
                    }
					}else{
						$this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
					}
                }
            } else {
                
                $this->session->data['isPrivate'] = $this->request->post['isPrivate'];
                
                $this->redirect($this->url->link('common/verifyotp', '', 'SSL'));
            }
			
			}else if($this->config->get('config_saml') == '1'){
				$username = $this->request->post['username'];
				$password = $this->request->post['password'];
				
				require_once(DIR_SYSTEM . 'library/aws/cognitologin.php');	
				
				$this->data['samlerror'] = $samlerror;
				
				
				$UserAttributes = $userResult['UserAttributes'];
				$email = $UserAttributes[2]['Value'];
				
				//$userAdmin['UserStatus'] == 'FORCE_CHANGE_PASSWORD'
				
				//var_dump($userResult);
				
				if( $userAdmin['UserStatus'] == 'FORCE_CHANGE_PASSWORD' ){
					
					$this->redirect($this->url->link('common/licence/forceresetpassword','', 'SSL'));
					
				}else{
				
					$this->load->model('user/user');
					$userdata = $this->model_user_user->getUserByUsernamebysaml($userResult['Username']);
					
					if( $userdata == NULL || $userdata == ""){
						$data = array();
						$data['username'] = $userResult['Username'];
						$data['email'] = $email;
						$data['firstname'] = '';
						$data['lastname'] = '';
						$data['phone_number'] = '';
						$data['activationKey'] = $userResult['Username'].rand();
						
						
						$this->load->model('licence/licence');
				
						$user_id = $this->model_user_user->insertUser($data);
						
						$key = str_replace(' ', '', $data['activationKey']);
						
						$this->model_licence_licence->addKeyactivation($key, $userResult['Username'], $user_id);
						
						
					}
					
					if($userResult !=NULL && $userResult != ""){
						
						$this->session->data['activationkey'] = $userdata['activationKey'];
						$this->session->data['session_key'] = rand(0, 100000);
						
						$this->session->data['username'] = $userdata['Username'];
						$this->session->data['webuser_id'] = $user_id;
						$this->redirect($this->url->link('common/login&step=2','', 'SSL'));
					}
				
				}
			}
        }
		
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->session->data['error'])) {
            $this->data['error'] = $this->session->data['error'];
            
            unset($this->session->data['error']);
        } else {
            $this->data['error'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
		
		if($this->config->get('config_saml') == '0'){
			
			$opencamera = 0;
			if (isset($this->request->post['activationkey'])) {
				$this->data['activationkey'] = $this->request->post['activationkey'];
				
				$this->load->model('user/user');
				$userinfo = $this->model_user_user->getUserByAccessKey($this->request->get['accessKey']);
				if ($userinfo['facilities'] != null && $userinfo['facilities'] != "") {
					$facilities = explode(',', $userinfo['facilities']);
					
					$this->load->model('facilities/facilities');
					foreach ($facilities as $facility) {
						$facilities_info = $this->model_facilities_facilities->getfacilities($facility);
						
						if ($facilities_info['is_enable_add_notes_by'] == "1") {
							$opencamera = $facilities_info['is_enable_add_notes_by'];
						}
					}
				}
				
				$this->data['opencamera'] = $opencamera;
			} else {
				$this->data['activationkey'] = '';
			}
        }
		
		$this->data['config_saml'] = $this->config->get('config_saml');
        
        if (isset($this->request->post['access'])) {
            $this->data['access'] = $this->request->post['access'];
        } else {
            $this->data['access'] = 'accessKeyInput';
        }
        
        if (isset($this->request->post['isPrivate'])) {
            $this->data['isPrivate'] = $this->request->post['isPrivate'];
        } else {
            $this->data['isPrivate'] = $this->request->get['is_private'];
        }
        
        if (isset($this->request->post['current_enroll_image'])) {
            $this->data['current_enroll_image'] = $this->request->post['current_enroll_image'];
        } else {
            $this->data['current_enroll_image'] = '';
        }
        
        if (isset($this->request->post['current_enroll_image1'])) {
            $this->data['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
        } else {
            $this->data['current_enroll_image1'] = '';
        }
		if (isset($this->request->post['is_userpin'])) {
            $this->data['is_userpin'] = $this->request->post['is_userpin'];
        } else {
            $this->data['is_userpin'] = '';
        }
		if (isset($this->request->post['user_pin'])) {
            $this->data['user_pin'] = $this->request->post['user_pin'];
        } else {
            $this->data['user_pin'] = '';
        }
		
		if (isset($this->request->post['activationkey'])) {
            $this->data['activationkey'] = $this->request->post['activationkey'];
        } else {
            $this->data['activationkey'] = '';
        }
		
		
			if($this->config->get('config_block_ip') == '1'){
				
				if (!empty($this->request->server['HTTP_CLIENT_IP']))   
				{
				  $ip_a = $this->request->server['HTTP_CLIENT_IP'];
				}
				elseif (!empty($this->request->server['HTTP_X_FORWARDED_FOR']))  
				{
				  $ip_a = $this->request->server['HTTP_X_FORWARDED_FOR'];
				}
				else
				{
				  $ip_a = $this->request->server['REMOTE_ADDR'];
				}
				
				$this->load->model('user/user');
         		$result_info = $this->model_user_user->getuseip($ip_a);
				$user_ip_id = $result_info['user_ip_id'];
			}else{
				$user_ip_id = 2;
			}
			
            if($user_ip_id == null && $user_ip_id == ""){
				$this->template = $this->config->get('config_template') . '/template/common/restrict.php';
			}else{
				$this->template = $this->config->get('config_template') . '/template/common/licence_activation.php';
				$this->children = array(
					'common/headerlogin',
					'common/footerlogin'
				);
			}
        
        $this->response->setOutput($this->render());
    }

    protected function validate ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
		
		if($this->config->get('config_saml') == '0'){
			
        if ($this->request->post['activationkey'] != "" && $this->request->post['activationkey'] != NULL) {
			
			
			$this->load->model('user/user');
			$user_result = $this->model_user_user->getUserByAccessKey($this->request->post['activationkey']);
				
			if(empty($user_result)){
				$this->error['warning'] = "Please enter valid activation key";
			}
            
			if ($this->request->post['is_userpin'] != "1"){
				
				
				$opencamera = 0;
				if ($user_result['facilities'] != null && $user_result['facilities'] != "") {
					$facilities = explode(',', $user_result['facilities']);
					
					$this->load->model('facilities/facilities');
					foreach ($facilities as $facility) {
						$facilities_info = $this->model_facilities_facilities->getfacilities($facility);
						
						if ($facilities_info['is_enable_add_notes_by'] == "1") {
							$opencamera = $facilities_info['is_enable_add_notes_by'];
							$facilities_id = $facilities_info['facilities_id'];
						}
					}
				}
				
				if ($opencamera == '1') {
					$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
				}
				
				if ($facilities_info['is_enable_add_notes_by'] == '1') {
					if ($user_result['user_id'] != null && $user_result['user_id'] != "") {
						
						$user_result_inroll = $this->model_user_user->getenroll_image($user_result['user_id']);
						
						/*$img = $this->request->post['current_enroll_image'];
						$img = str_replace('data:image/jpeg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$Imgdata = base64_decode($img);
						
						$notes_file = uniqid() . '.jpeg';
						
						$file = DIR_IMAGE . '/facerecognition/' . $notes_file;
						$success = file_put_contents($file, $Imgdata);
						
						$imageUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
						*/
						
						$outputFolderUrl = $this->request->post['current_enroll_image'];
						
						$outputFolder = $file;
						
						$useroriginal = $user_result_inroll['enroll_image'];
						
						#$usercurrentimage = $imageUrl;
						#$outputFolderUrl = $imageUrl;
						
						// var_dump($usercurrentimage);
						// var_dump($useroriginal);
						
						if ($facilities_info['face_similar_percent'] != null && $facilities_info['face_similar_percent'] != "0") {
							$face_similar_percent = $facilities_info['face_similar_percent'];
						} else {
							$face_similar_percent = '90';
						}
						
						// require_once(DIR_APPLICATION_AWS .
						// 'facerecognition_config.php');
						
						$web_app = '1';
						//require_once (DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config.php');
						
						$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser($outputFolderUrl, $facilities_id);
							   
						foreach($result_inser_user_img22['FaceMatches'] as $c){
							$similarity = $c['Similarity'];
							$FaceId[] = $c['Face']['FaceId'];
							$ImageId[] = $c['Face']['ImageId'];
							$ExternalImageId = $c['Face']['ExternalImageId'];
							
						}
						
						
						
						if($ExternalImageId == $user_result['user_id']){
							if ($similarity > $face_similar_percent) {
								// echo "Matching faces!!";
								
								if ($ExternalImageId != null && $ExternalImageId != "") {
									$username11 = $ExternalImageId;
								} else {
									$username11 = $user_result['user_id'];
								}
								$this->session->data['user_enroll_confirm'] = $username11;
							} else {
								$this->error['warning'] = 'Sorry i am having trouble in recognizing you. Lets try again!!';
							}
						}else{
							$this->error['warning'] = 'Sorry i am having trouble in recognizing you. Lets try again!!';
						}
					} else {
						
						$setUrl = $this->url->link('common/resetpassword', '' . $url3, 'SSL');
						$this->error['warning'] = 'Unable to verify your access. Please try again. If you continue to have issues, please contact the administrator for enrollment.';
					}
				}
			}
        }
		
		if ($this->request->post['is_userpin'] == "1"){
			
			if ($this->request->post['user_pin'] == null && $this->request->post['user_pin'] == "") {
				 $this->error['warning'] = 'Please enter a User Pin.';
			}
			$this->load->model('user/user');
			$user_result = $this->model_user_user->getUserByAccessKey($this->request->post['activationkey']);
				
				if ($this->request->post['user_pin'] != null && $this->request->post['user_pin'] != "") {
					$user_result_1 = $this->model_user_user->getUsersByPin($user_result['user_id'], $this->request->post['user_pin']);
				
					if(empty($user_result_1)){
						$this->error['warning'] = 'Please enter a valid User Pin.';
					}
				}
			
		}
        
		
        if ($this->request->post['access'] == "accessKeyInput") {
            if ($this->request->post['activationkey'] == null && $this->request->post['activationkey'] == "") {
                $this->error['warning'] = 'Please enter a valid key.';
            }
        }
        
        if ($this->request->post['access'] == "smsInput") {
            if ($this->request->post['phonenumber'] == null && $this->request->post['phonenumber'] == "") {
                $this->error['warning'] = 'Please enter phone number';
            }
            
            $this->load->model('user/user');
            $getUserdetail = $this->model_user_user->getUserByPhonenumber($this->request->post['phonenumber']);
            
            if ($this->request->post['isPrivate'] == '1') {
                
                $this->load->model('user/user_group');
                $userPrivate = $this->model_user_user_group->getUserGroup($getUserdetail['user_group_id']);
                
                if ($userPrivate['is_private'] == '0') {
                    $this->error['warning'] = "You are not authorized as private member";
                }
            }
            
            if ($getUserdetail != NULL && $getUserdetail != "") {
                
                $data = array();
                $data['activationkey'] = $getUserdetail['activationKey'];
                $result = $this->model_licence_licence->webdualCheckActivation($data);
                
                if ($result == null && $result == "") {
                    $this->error['warning'] = "You are not valid number.";
                }
            }
            
            if ($this->error['warning'] == null && $this->error['warning'] == "") {
                if ($getUserdetail != NULL && $getUserdetail != "") {
                    
                    $phone_number = $this->request->post['phonenumber'];
                    $randomNum = mt_rand(100000, 999999);
                    
                    $message = 'Your OTP for activation is ' . $randomNum;
                    
                    $this->load->model('api/smsapi');
                    
                    $sdata = array();
                    $sdata['message'] = $message;
                    $sdata['phone_number'] = $phone_number;
                    
                    // var_dump($getUserdetail['facilities']);
                    
                    if ($getUserdetail['facilities'] != null && $getUserdetail['facilities'] != "") {
                        $facilities = explode(',', $getUserdetail['facilities']);
                        
                        $this->load->model('facilities/facilities');
                        foreach ($facilities as $facility) {
                            $facilities_info = $this->model_facilities_facilities->getfacilities($facility);
                            
                            if ($facilities_info['sms_number'] != null && $facilities_info['sms_number'] != "") {
                                $facilities_id = $facilities_info['facilities_id'];
                                break;
                            }
                        }
                    }
                    
                    $sdata['facilities_id'] = $facilities_id;
                    
                    $response = $this->model_api_smsapi->sendsms($sdata);
                    
                    $sql = "UPDATE `" . DB_PREFIX . "user` SET message_sid = '" . $response->sid . "', user_otp = '" . $randomNum . "'  WHERE user_id = '" . $getUserdetail['user_id'] . "'";
                    
                    $query = $this->db->query($sql);
                    $this->session->data['sms_user_id'] = $getUserdetail['user_id'];
                } else {
                    
                    $this->error['warning'] = 'Phone number does not exist';
                }
            }
        }
		
		}else if($this->config->get('config_saml') == '1'){
			if ($this->request->post['username'] == null && $this->request->post['username'] == "") {
                $this->error['warning'] = 'Please enter username';
            }
			if ($this->request->post['password'] == null && $this->request->post['password'] == "") {
                $this->error['warning'] = 'Please enter password';
            }
		}
		
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function activationdetail ()
    {
        $this->language->load('common/licence');
        $this->document->setTitle('Activation');
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['heading_title'] = '';
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('licence/licence');
        // $data =array();
        
        if ($this->request->get['step'] == '2') {
            if ($this->session->data['activationkey'] == NULL && $this->session->data['activationkey'] == "") {
                $this->redirect($this->url->link('common/licence/activation', '', 'SSL'));
            }
        } else {
            $this->load->model('facilities/facilities');
            
            $data = array();
            $data['activationkey'] = $this->session->data['activationkey'];
            $data['username'] = $this->session->data['webuser_id'];
            $data['facilities_id'] = $this->customer->getId();
            $data['ip'] = $this->request->server['REMOTE_ADDR'];
            $this->model_facilities_facilities->updateFacilityLogout($data);
            
            $this->customer->logout();
            
            /**
             * **************
             */
            $this->load->model('licence/licence');
            $this->model_licence_licence->closeuseractivation();
            /**
             * *************
             */
            
            unset($this->session->data['time_zone_1']);
            unset($this->session->data['token']);
            
            unset($this->session->data['note_date_search']);
            unset($this->session->data['note_date_from']);
            unset($this->session->data['note_date_to']);
            unset($this->session->data['keyword']);
            
            unset($this->session->data['search_user_id']);
            
            unset($this->session->data['notesdatas']);
            unset($this->session->data['advance_search']);
            unset($this->session->data['update_reminder']);
            unset($this->session->data['pagenumber']);
            unset($this->session->data['pagenumber_all']);
            unset($this->session->data['activationkey']);
            unset($this->session->data['username']);
            unset($this->session->data['isPrivate']);
            unset($this->session->data['webuser_id']);
            $this->redirect($this->url->link('common/licence/activation', '', 'SSL'));
        }
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate2()) {
            
            $this->load->model('user/user');
            $this->model_user_user->editUserByKey($this->request->post);
            
            $result2 = $this->model_licence_licence->submit_activation_details($this->request->post);
            
            if ($result2->status == '1') {
				if ($this->session->data['isPrivate'] == '1') {
					$this->redirect($this->url->link('common/verifyuser', '', 'SSL'));
				}else{
					$this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
				}
                
            }
        }
        
        if (isset($this->error['fname'])) {
            $this->data['error_fnamewarning'] = $this->error['fname'];
        } else {
            $this->data['error_fnamewarning'] = '';
        }
        
        if (isset($this->error['lname'])) {
            $this->data['error_lnamewarning'] = $this->error['lname'];
        } else {
            $this->data['error_lnamewarning'] = '';
        }
        
        if (isset($this->error['email'])) {
            $this->data['error_emailwarning'] = $this->error['email'];
        } else {
            $this->data['error_emailwarning'] = '';
        }
        
        /*
         * if (isset($this->error['company'])) {
         * $this->data['error_companywarning'] = $this->error['company'];
         * } else {
         * $this->data['error_companywarning'] = '';
         * }
         *
         *
         * if (isset($this->error['add'])) {
         * $this->data['error_addwarning'] = $this->error['add'];
         * } else {
         * $this->data['error_addwarning'] = '';
         * }
         *
         *
         * if (isset($this->error['contact'])) {
         * $this->data['error_contactwarning'] = $this->error['contact'];
         * } else {
         * $this->data['error_contactwarning'] = '';
         * }
         *
         * if (isset($this->error['message'])) {
         * $this->data['error_messagewarning'] = $this->error['message'];
         * } else {
         * $this->data['error_messagewarning'] = '';
         * }
         *
         */
        
        $this->load->model('user/user');
        $userinfo = $this->model_user_user->getUser($this->session->data['webuser_id']);
        
        $this->data['user_id'] = $userinfo['user_id'];
        
        if (isset($this->request->post['fname'])) {
            $this->data['fname'] = $this->request->post['fname'];
        } elseif ($userinfo) {
            $this->data['fname'] = $userinfo['firstname'];
        } else {
            $this->data['fname'] = '';
        }
        
        if (isset($this->request->post['lname'])) {
            $this->data['lname'] = $this->request->post['lname'];
        } elseif ($userinfo) {
            $this->data['lname'] = $userinfo['lastname'];
        } else {
            $this->data['lname'] = '';
        }
        
        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif ($userinfo) {
            $this->data['email'] = $userinfo['email'];
        } else {
            $this->data['email'] = '';
        }
        
        if (isset($this->request->post['contact'])) {
            $this->data['contact'] = $this->request->post['contact'];
        } elseif ($userinfo) {
            $this->data['contact'] = $userinfo['phone_number'];
        } else {
            $this->data['contact'] = '';
        }
        
        if (isset($this->request->post['user_pin'])) {
            $this->data['user_pin'] = $this->request->post['user_pin'];
        } elseif ($userinfo) {
            $this->data['user_pin'] = $userinfo['user_pin'];
        } else {
            $this->data['user_pin'] = '';
        }
        
        $this->load->model('licence/licence');
        // $this->model_licence_licence->insert_activationkey($this->request->post);
        
        $this->template = $this->config->get('config_template') . '/template/common/activation_detail.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        
        $this->response->setOutput($this->render());
    }

    public function validate2 ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['fname'] == null && $this->request->post['fname'] == "") {
            $this->error['fname'] = 'Invalid First Name';
        }
        
        if ($this->request->post['lname'] == null && $this->request->post['lname'] == "") {
            $this->error['lname'] = 'Invalid Last Name';
        }
        
        if ($this->request->post['email'] == null && $this->request->post['email'] == "") {
            $this->error['email'] = 'Invalid Email';
        }
        
        /*
         * if ($this->request->post['company'] == null &&
         * $this->request->post['company'] == "") {
         * $this->error['company'] = 'Invalid Key';
         * }
         *
         *
         * if ($this->request->post['add'] == null &&
         * $this->request->post['add'] == "") {
         * $this->error['add'] = 'Invalid Key';
         * }
         *
         * if ($this->request->post['contact'] == null &&
         * $this->request->post['contact'] == "") {
         * $this->error['contact'] = 'Invalid Key';
         * }
         *
         * if ($this->request->post['message'] == null &&
         * $this->request->post['message'] == "") {
         * $this->error['message'] = 'Invalid Key';
         * }
         */
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function resetSession ()
    {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate2ac()) {
            // if($this->request->get['activationkey'] != null &&
            // $this->request->get['activationkey'] !=""){
            
            $this->load->model('facilities/facilities');
            $this->load->model('licence/licence');
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $resultdata = $this->model_licence_licence->webgetUseernameactivationkey($this->request->post['activationkey']);
            
            if ($resultdata->Activitation_key != null && $resultdata->Activitation_key != "") {
                
                if ($resultdata->username != null && $resultdata->username != "") {
                    
                    $this->load->model('user/user');
                    $user_info = $this->model_user_user->getUserByUsername($resultdata->user_id);
                    
                    if ($user_info['user_pin'] == $this->request->post['user_pin']) {
                        
                        $data = array();
                        $data['activationkey'] = $this->request->post['activationkey'];
                        $data['ip'] = $this->request->server['REMOTE_ADDR'];
                        
                        $results = $this->model_facilities_facilities->resetFacilityLogin($data);
                        
                        if ($results != null && $results != "") {
                            foreach ($results as $result) {
                                
                                $data2 = array();
                                $data2['activationkey'] = $this->request->post['activationkey'];
                                $data2['username'] = $resultdata->user_id;
                                $data2['facilities_id'] = $result['facilities_id'];
                                $data2['ip'] = $result['ip'];
                                $this->model_facilities_facilities->updateFacilityLogout($data2);
                            }
                        }
                        
                        $this->session->data['session_key'] = rand(0, 100000);
                        
                        $result2 = $this->model_licence_licence->webresetactivationkey($this->request->post['activationkey']);
                        
                        $this->session->data['success'] = "Reset Session successfully!";
                        // $this->redirect($this->url->link('common/licence/activation&step=2','',
                        // 'SSL'));
                        
                        $this->session->data['username'] = $resultdata->username;
                        $this->session->data['webuser_id'] = $resultdata->user_id;
                        $this->session->data['activationkey'] = $resultdata->Activitation_key;
                        $this->session->data['licfacilities'] = $resultdata->facilities;
                        
                        // var_dump($result->check_user_key);
                        
                        /*
                         * if($resultdata->check_user_key == '0' ||
                         * $resultdata->check_user_key == '1'){
                         * $this->session->data['session_key'] = rand(0,100000);
                         * }else{
                         * $this->session->data['session_key'] =
                         * $resultdata->check_user_key;
                         * }
                         */
                        // var_dump($this->session->data['session_key']);
                        
                        // die;
                        if ($resultdata->username == "") {
                            //$this->redirect($this->url->link('common/licence/activationdetail&step=2', '', 'SSL'));
							$this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
                        } else {
                            $this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
                        }
                    } else {
                        $this->session->data['error'] = "Incorrect user pin";
                    }
                }
            } else {
                // $this->session->data['error'] = "Please enter valid Key!";
                // $this->redirect($this->url->link('common/licence/activation&step=2','',
                // 'SSL'));
                
                $this->session->data['error'] = "Please enter valid Key!";
            }
            // }
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->session->data['error'])) {
            $this->data['error'] = $this->session->data['error'];
            
            unset($this->session->data['error']);
        } else {
            $this->data['error'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['activationkey'])) {
            $this->data['error_activationkey'] = $this->error['activationkey'];
        } else {
            $this->data['error_activationkey'] = '';
        }
        
        if (isset($this->error['user_pin'])) {
            $this->data['error_user_pin'] = $this->error['user_pin'];
        } else {
            $this->data['error_user_pin'] = '';
        }
        
        if (isset($this->request->post['activationkey'])) {
            $this->data['activationkey'] = $this->request->post['activationkey'];
        } else {
            $this->data['activationkey'] = $this->session->data['reset_activationkey'];
        }
        
        if (isset($this->request->post['user_pin'])) {
            $this->data['user_pin'] = $this->request->post['user_pin'];
        } else {
            $this->data['user_pin'] = '';
        }
        
        $this->data['action'] = $this->url->link('common/licence/resetSession', '' . $url, 'SSL');
        
        $this->template = $this->config->get('config_template') . '/template/common/licence_activation_validate.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        
        $this->response->setOutput($this->render());
    }

    public function validate2ac ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['activationkey'] == null && $this->request->post['activationkey'] == "") {
            $this->error['activationkey'] = 'Please enter key';
        }
        
        if ($this->request->post['user_pin'] == null && $this->request->post['user_pin'] == "") {
            $this->error['user_pin'] = 'Please enter user pin';
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function checkuserface ()
    {
        $json = array();
        
        $this->load->model('notes/tags');
        
        if (isset($this->request->get['accessKey'])) {
            $accessKey = $this->request->get['accessKey'];
        } else {
            $accessKey = '';
        }
        
        $this->load->model('user/user');
        $user = $this->model_user_user->getUserByAccessKey($accessKey);
        
        $opencamera = 0;
        if ($user['facilities'] != null && $user['facilities'] != "") {
            $facilities = explode(',', $user['facilities']);
            
            $this->load->model('facilities/facilities');
            foreach ($facilities as $facility) {
                $facilities_info = $this->model_facilities_facilities->getfacilities($facility);
                
                if ($facilities_info['is_enable_add_notes_by'] == "1") {
                    $opencamera = $facilities_info['is_enable_add_notes_by'];
                }
            }
        }
        
        $json[] = array(
                'opencamera' => $opencamera
        );
        
        $this->response->setOutput(json_encode($json));
    }
	
	public function forceresetpassword(){

		$this->data['action'] = $this->url->link('common/licence/forceresetpassword', '', 'SSL');
		
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate3()) {
		
			$oldpassword = $this->request->post['oldpassword'];
			$password = $this->request->post['password'];
			$confirm = $this->request->post['confirm'];
			
			$forgotsession = $this->session->data['forgotsession'];
			$forgotusername = $this->session->data['forgotusername'];
			$forgotemail = $this->session->data['forgotemail'];
			require_once(DIR_SYSTEM . 'library/aws/cognitoresetpass.php');
			
			$this->session->data['success'] = 'Success: You have updated password!';
			
			$this->redirect($this->url->link('common/licence/activation','', 'SSL'));
			
		}
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	 
		$this->template = $this->config->get('config_template') . '/template/common/forceresetpassword.php';
		$this->children = array(
				'common/headerlogin',
				'common/footerlogin'
		);
		
		$this->response->setOutput($this->render());	
		  
	}
	
	protected function validate3 (){
	  
	    if ($this->request->post['password'] == null && $this->request->post['password'] == "") {
		   $this->error['warning'] = 'Password Required';
	    }
		
		if ($this->request->post['password'] != $this->request->post['confirm']) {
		   $this->error['warning'] = 'Password does not match';
	    }

	   if (! $this->error) {
            return true;
        } else {
            return false;
        }
	}
}