<?php

class Controllercommonresetpassword extends Controller
{

    private $error = array();

    public function index ()
    {
        $this->load->model('user/user');
        
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if ($this->session->data['session_reset_password_otp'] == '1') {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                
                $this->model_user_user->editUserdetail($this->request->post['accessKey'], $this->request->post);
                
                unset($this->session->data['session_reset_password_otp']);
                
                $this->session->data['success'] = 'You have updated Details';
                
                $this->response->redirect($this->url->link('common/resetpassword'));
            }
        }
        
        if ($this->session->data['session_reset_password_otp'] == '' && $this->session->data['session_reset_password_otp'] == null) {
            
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                
                $user_info = $this->model_user_user->getUserByAccessKey($this->request->post['accessKey']);
                
                $this->load->model('facilities/facilities');
                $facilities = explode(',', $user_info['facilities']);
                
                $valuess = '0';
                foreach ($facilities as $facility) {
                    $facility_info = $this->model_facilities_facilities->getfacilities($facility);
                    
                    if ($facility_info['is_sms_enable'] == '1') {
                        $valuess = '1';
                    }
                }
                
                if ($valuess == '1') {
                    $this->session->data['success_45'] = 'SMS send successfully';
                } else {
                    $this->session->data['success_45'] = 'Verify User';
                }
                
                $url2 = '&accessKey=' . $this->request->post['accessKey'];
                
                $this->data['verify_otp'] = str_replace('&amp;', '&', $this->url->link('common/resetpassword/verifyotp', '' . $url2, 'SSL'));
            }
        }
        
        $this->document->setTitle('Reset Your Password');
        
        $data = array();
        
        if (isset($this->session->data['success_45'])) {
            $this->data['success_45'] = $this->session->data['success_45'];
            
            unset($this->session->data['success_45']);
        } else {
            $this->data['success_45'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['activationKey'])) {
            $this->data['error_activationKey'] = $this->error['activationKey'];
        } else {
            $this->data['error_activationKey'] = '';
        }
        
        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }
        
        if (isset($this->error['phone_number'])) {
            $this->data['error_phoneNum'] = $this->error['phone_number'];
        } else {
            $this->data['error_phoneNum'] = '';
        }
        
        if (isset($this->error['user_pin'])) {
            $this->data['error_userPin'] = $this->error['user_pin'];
        } else {
            $this->data['error_userPin'] = '';
        }
        
        if (isset($this->error['password'])) {
            $this->data['error_password'] = $this->error['password'];
        } else {
            $this->data['error_password'] = '';
        }
        
        if (isset($this->error['confirm'])) {
            $this->data['error_confirm'] = $this->error['confirm'];
        } else {
            $this->data['error_confirm'] = '';
        }
        if (isset($this->error['user_pinconfirm'])) {
            $this->data['error_user_pinconfirm'] = $this->error['user_pinconfirm'];
        } else {
            $this->data['error_user_pinconfirm'] = '';
        }
        
        if ($this->request->post['accessKey'] != null && $this->request->post['accessKey'] !== "") {
            $accessKey = $this->request->post['accessKey'];
        } else {
            $accessKey = $this->request->get['accessKey'];
        }
        $userinfo = $this->model_user_user->getUserByAccessKey($accessKey);
        // var_dump($userinfo);
        
        $opencamera = 0;
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
        
        if (isset($this->request->post['accessKey'])) {
            $this->data['accessKey'] = $this->request->post['accessKey'];
        } elseif ($userinfo) {
            $this->data['accessKey'] = $userinfo['activationKey'];
        } else {
            $this->data['accessKey'] = '';
        }
        
        if (isset($this->request->post['firstname'])) {
            $this->data['firstname'] = $this->request->post['firstname'];
        } elseif ($userinfo) {
            $this->data['firstname'] = $userinfo['firstname'];
        } else {
            $this->data['firstname'] = '';
        }
        
        if (isset($this->request->post['lastname'])) {
            $this->data['lastname'] = $this->request->post['lastname'];
        } elseif ($userinfo) {
            $this->data['lastname'] = $userinfo['lastname'];
        } else {
            $this->data['lastname'] = '';
        }
        
        if (isset($this->request->post['user_id'])) {
            $this->data['user_id'] = $this->request->post['user_id'];
        } elseif ($userinfo) {
            $this->data['user_id'] = $userinfo['user_id'];
        } else {
            $this->data['user_id'] = '';
        }
        
        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif ($userinfo) {
            $this->data['email'] = $userinfo['email'];
        } else {
            $this->data['email'] = '';
        }
        
        /*
         * if (isset($this->request->post['user_pin'])) {
         * $this->data['user_pin'] = $this->request->post['user_pin'];
         * } elseif($userinfo){
         * $this->data['user_pin'] = $userinfo['user_pin'];
         * }else {
         * $this->data['user_pin'] = '';
         * }
         */
        
        if (isset($this->request->post['user_pin'])) {
            
            //$randomChar = str_split($userinfo['user_pin'], 2);
           // $userpin = $randomChar[0];
            //$this->data['user_pin'] = $this->request->post['user_pin'];
            $this->data['user_pin'] = $this->request->post['user_pin'];
            //$this->data['prefix_userpin'] = $randomChar[0];
        } elseif ($userinfo) {
            
            //$newpin = substr($userinfo['user_pin'], 2);
            //$this->data['user_pin'] = $newpin;
            $this->data['user_pin'] = $userinfo['user_pin'];
            
            //$randomChar = str_split($userinfo['user_pin'], 2);
            //$this->data['prefix_userpin'] = $randomChar[0];
        } else {
            $this->data['user_pin'] = '';
        }
        
		
		if (isset($this->request->post['std_code'])) {
            $this->data['std_code'] = $this->request->post['std_code'];
			$std_code = $user['std_code'];
        } elseif ($userinfo) {
            $this->data['std_code'] = $userinfo['std_code'];
			$std_code = $user['std_code'];
			
			
			$number = $userinfo['phone_number'];
			$countrys = array(

			'1' => '1',
			'2' => '2',
			'3' => '3',
			'44' => '44',
			'123' => '123',
			'971' =>'972',
			'91' =>'91',
			'92' =>'92'
			);

			$i = 4;
			$country = "";
			while ($i > 0) {
				if (isset($countrys[substr($number, 0, $i)])) {
					$country = $countrys[substr($number, 0, $i)];
					break;
				} else {
					$i--;
				}
			}
			
			$std_code = $country;
			
        } else {
            $this->data['std_code'] = '';
			
        }
		
		
        if (isset($this->request->post['phone_number'])) {
            $this->data['phone_number'] = $this->request->post['phone_number'];
        } elseif ($userinfo) {
            //$this->data['phone_number'] = $userinfo['phone_number'];
			
			$country_code = $std_code;
			$phone_no = $userinfo['phone_number'];
			
			$this->data['phone_number'] = preg_replace('/^\+?'.$country_code.'|\|'.$country_code.'|\D/', '', ($phone_no));
			
        } else {
            $this->data['phone_number'] = '';
        }
        
        if (isset($this->request->post['enroll_image'])) {
            $this->data['enroll_image'] = $this->request->post['enroll_image'];
        } elseif ($userinfo) {
            $this->data['enroll_image'] = $userinfo['enroll_image'];
        } else {
            $this->data['enroll_image'] = '';
        }
        if (isset($this->request->post['enroll_image1'])) {
            $this->data['enroll_image1'] = $this->request->post['enroll_image1'];
        } else {
            $this->data['enroll_image1'] = '';
        }
        
        $this->data['action'] = $this->url->link('common/resetpassword', '', 'SSL');
        
        $this->template = $this->config->get('config_template') . '/template/common/resetpassword.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        $this->response->setOutput($this->render());
    }

    public function validateForm ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->session->data['session_reset_password_otp'] == '' && $this->session->data['session_reset_password_otp'] == null) {
            
            $user_info = $this->model_user_user->getUserByAccessKey($this->request->post['accessKey']);
            
            $this->load->model('facilities/facilities');
            $facilities = explode(',', $user_info['facilities']);
            
            $valuess = '0';
            foreach ($facilities as $facility) {
                $facility_info = $this->model_facilities_facilities->getfacilities($facility);
                
                if ($facility_info['is_sms_enable'] == '1') {
                    $valuess = '1';
                }
            }
            
            if ($valuess == '1') {
                if ($user_info['phone_number'] == null && $user_info['phone_number'] == "") {
                    $this->error['warning'] = "Please contact your admin to enroll your picture!";
                } else {
                    $phone_number = $user_info['phone_number'];
                    $randomNum = mt_rand(100000, 999999);
                    $message = 'Your OTP for Reset Your Password is ' . $randomNum;
                    $this->load->model('api/smsapi');
                    $sdata = array();
                    $sdata['message'] = $message;
                    $sdata['phone_number'] = $phone_number;
                    
                    if ($user_info['facilities'] != null && $user_info['facilities'] != "") {
                        $facilities = explode(',', $user_info['facilities']);
                        
                        $this->load->model('facilities/facilities');
                        foreach ($facilities as $facility) {
                            $facilities_info = $this->model_facilities_facilities->getfacilities($facility);
                            
                            if ($facilities_info['sms_number'] != null && $facilities_info['sms_number'] != "") {
                                $facilities_id = $facilities_info['facilities_id'];
                                $facility = $facilities_info['facility'];
                                break;
                            }
                        }
                    }
                    
                    $sdata['facilities_id'] = $facilities_id;
                    
                    $response = $this->model_api_smsapi->sendsms($sdata);
                    
                    $this->model_user_user->updateUserOTP($randomNum, $user_info['user_id']);
					
					if($user_info['email'] != null && $user_info['email'] != ""){
						
						$this->load->model('api/emailapi');
					
						/*$edata = array();
						$edata['message'] = $message;
						$edata['subject'] = $facility.' | '.'Your verification code';
						$edata['user_email'] = $user_info['email'];
							
						$email_status = $this->model_api_emailapi->sendmail($edata);*/

                        $edata = array();
                        $edata['message'] = $message;
                        $edata['facility'] = $facility_info['facility'];
                        $edata['user_email'] = $user_info['email'];
                        $edata['when_date']=date("l");
                        $edata['who_user']=$user_info['username'];
                        $edata['type']="9";
                        $edata['notes_description']=$message;
						$edata['subject'] = $facility.' | '.'Your verification code';
						$email_status = $this->model_api_emailapi->sendmail($edata);
                        //$email_status=$this->model_api_emailapi->createMails($edata);   
					}
                }
            }
        }
        
        $user_info = $this->model_user_user->getUserByAccessKey($this->request->post['accessKey']);
        
        if ($user_info['activationKey'] == null && $user_info['activationKey'] == "") {
            $this->error['activationKey'] = 'Please enter valid Key';
        }
        
        if ($this->session->data['session_reset_password_otp'] != '') {
            if ($this->request->post['user_pin'] == '') {
                // $pinresult =
                // $this->model_user_user->getUsersByPin($this->request->post['user_pin']);
                $this->error['user_pin'] = 'Please provide a valid PIN; This is your PIN number preceded by two alphabets. If you have forgotten the pin, please contact your administrator.';
                // var_dump($pinresult);
            }
        }
        
        if ($this->request->post['user_pin'] != '') {
            if ($this->request->post['user_pin'] != $this->request->post['confirm_user_pin']) {
                $this->error['user_pinconfirm'] = 'Userpin and user pin confirmation do not match!';
            }
        }
        
        if ($this->session->data['session_reset_password_otp'] != '') {
            if ($this->request->post['user_pin'] != null && $this->request->post['user_pin'] != "") {
                if ((utf8_strlen($this->request->post['user_pin']) >= 4) && (utf8_strlen($this->request->post['user_pin']) <= 15)) {
                    
                    // $firstchar =
                    // substr($this->request->post['activationKey'], 0, 1);
                    // $lastchar = substr($this->request->post['activationKey'],
                    // -1, 1);
                    
                    $randomChar = str_split($user_info['user_pin'], 2);
                    $prefix_userpin = $randomChar[0];
                    
                    //$user_prefix = $prefix_userpin . $this->request->post['user_pin'];
                    $user_prefix = $this->request->post['user_pin'];
                    
                    $this->load->model('user/user');
                    $qtotal_info = $this->model_user_user->getUserdetailuserpin($user_prefix);
                    if ($qtotal_info && ($this->request->post['accessKey'] != $qtotal_info['activationKey'])) {
						$this->error['user_pin'] = "User pin already exist";
						
					}
					 /*
                    if ($qtotal > 0) {
						
                       $activeKey = $this->request->post['activationKey'] . $this->request->post['lastname'];
                        $randomChar = str_split($activeKey, 2);
                        foreach ($randomChar as $characters) {
                            $user_prefix1 = $characters . $this->request->post['user_pin'];
                            
                            $q2total = $this->model_user_user->getDatabyuserpin($user_prefix1);
                            
                            if ($q2total > 1) {
                                
                                // $randomChar = $str[rand(0,
                                // strlen($data['activationKey'])-1)];
                                $characters = $activeKey . $this->request->post['username'];
                                $charactersLength = strlen($characters);
                                $randomString = '';
                                for ($i = 0; $i < 2; $i ++) {
                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                }
                                $user_prefix = $randomString . $this->request->post['user_pin'];
                                
                                $q3total = $this->model_user_user->getDatabyuserpin($user_prefix);
                                
                                if ($q3total > 1) {
                                    $this->error['user_pin'] = 'Please provide a valid PIN; This is your PIN number preceded by two alphabets. If you have forgotten the pin, please contact your administrator.';
                                }
                            }
                        }
                    }*/
                } else {
                    $this->error['user_pin'] = 'User pin must be between 4 and 15 length!';
                }
            }
        }
        
        if ($this->request->post['phone_number'] != '') {
            
            if ($this->request->post['phone_number'] != $user_info['phone_number']) {
                $phoneresults = $this->model_user_user->getUserByPhone($this->request->post['phone_number']);
                
                if ($phoneresults > 0) {
                    $this->error['phone_number'] = 'Phone Number already exist';
                }
            }
        }
        
        if (($this->request->post['email'] != '') || ! preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            if ($this->request->post['email'] != $user_info['email']) {
                $emailresult = $this->model_user_user->getTotalUsersByEmail($this->request->post['email']);
                // var_dump($emailresult);
                if ($phoneresults > 0) {
                    $this->error['email'] = 'Email already exist';
                }
            }
        }
        
        if ($this->request->post['password'] != '') {
            if ($this->config->get('config_password_protected') == '1') {
                
                $password = $this->request->post['password'];
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number = preg_match('@[0-9]@', $password);
                $length = preg_match("@^.{8,32}$@", $password);
                
                if (! $uppercase || ! $lowercase || ! $number || ! $length) {
                    $this->error['password'] = "The password must contain lower case characters, upper case characters and numbers. It's length should be between 8 and 32 characters.";
                }
            } else {
                if ($this->request->post['password'] == "" && $this->request->post['password'] == NULL) {
                    $this->error['password'] = "Password is Required.";
                }
            }
            
            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = 'Password and password confirmation do not match!';
            }
        }
		
		if (!empty($this->request->post['enroll_image'])) {
			if ($this->request->post['enroll_image1'] == '1') {
				foreach($this->request->post['enroll_image'] as $img_a){
					$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser($img_a);
					foreach($result_inser_user_img22['FaceMatches'] as $c){
						$similarity = $c['Similarity'];
						$FaceId[] = $c['Face']['FaceId'];
						$ImageId[] = $c['Face']['ImageId'];
						$ExternalImageId = $c['Face']['ExternalImageId'];
					}
					
					if($ExternalImageId != null && $ExternalImageId != ""){
						$this->load->model('user/user');
						$user_info = $this->model_user_user->getUserbyupdate2($ExternalImageId);
						if($user_info['user_id'] != $ExternalImageId){
							$this->error['warning'] = 'This '.$user_info['username'].' is already enrolled!';
						}
					}
				}
			}
		}
	
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function searchUserBykey ()
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
        
        $pin = str_split($user['user_pin'], 2);
        $newpin = substr($user['user_pin'], 2);
        
        if ($newpin) {
            $newpin11 = $newpin;
        } else {
            $newpin11 = "";
        }
		
		if($user['std_code'] != null && $user['std_code'] != ""){
			$std_code = $user['std_code'];
		}else{
			
			$number = $user['phone_number'];
			$countrys = array(

			'1' => '1',
			'2' => '2',
			'3' => '3',
			'44' => '44',
			'123' => '123',
			'971' =>'972',
			'91' =>'91',
			'92' =>'92'
			);

			$i = 4;
			$country = "";
			while ($i > 0) {
				if (isset($countrys[substr($number, 0, $i)])) {
					$country = $countrys[substr($number, 0, $i)];
					break;
				} else {
					$i--;
				}
			}
			
			$std_code = $country;
		}
		
		$phone_number = "";
		if($user['phone_number'] != null && $user['phone_number'] != ""){
			
			$country_code = $std_code;
			$phone_no = $user['phone_number'];
			$phone_number = preg_replace('/^\+?'.$country_code.'|\|'.$country_code.'|\D/', '', ($phone_no));
			
			//$phone_number = str_replace($user['std_code'],'',$user['phone_number']);
		}
        
        $json[] = array(
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'user_pin' => $user['user_pin'],
                //'user_pin' => $newpin11,
                //'prefix_userpin' => $pin[0],
                'phone_number' => $phone_number,
                'activationKey' => $user['activationKey'],
                'std_code' => $std_code,
                'enroll_image' => $user['enroll_image']
        );
        
        $this->response->setOutput(json_encode($json));
    }

    public function userenrollimages ()
    {
        $this->load->model('notes/tags');
        
        if (isset($this->request->get['user_id'])) {
            $accessKey = $this->request->get['accessKey'];
            $user_id = $this->request->get['user_id'];
            
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($user_id);
            
            // var_dump($user_info);
            
            if ($user_info != null && $user_info != "") {
                
                $user_enroll_images = $this->model_user_user->getenroll_images($user_id);
                // echo "<hr>";
                // var_dump($user_enroll_images);
                
                $url2 = "";
                if ($this->request->get['accessKey'] != null && $this->request->get['accessKey'] != "") {
                    $url2 .= '&accessKey=' . $this->request->get['accessKey'];
                }
                
                if ($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
                    $url2 .= '&user_id=' . $this->request->get['user_id'];
                }
                
                $this->data['action'] = $this->url->link('common/resetpassword/userenrollimages', '' . $url2, 'SSL');
            }
        }
        
        $this->template = $this->config->get('config_template') . '/template/common/userenrollimages.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        $this->response->setOutput($this->render());
    }

    public function verifyotp ()
    {
        $this->load->model('user/user');
        $userinfo = $this->model_user_user->getUserByAccessKey($this->request->get['accessKey']);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {
            $this->session->data['session_reset_password_otp'] = '1';
            $url2 = '';
            if ($this->request->get['accessKey'] != null && $this->request->get['accessKey'] != "") {
                $url2 .= '&accessKey=' . $this->request->get['accessKey'];
            }
            $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/resetpassword', '' . $url2, 'SSL'));
        }
        
        $this->load->model('facilities/facilities');
        $facilities = explode(',', $userinfo['facilities']);
        
        $valuess = '0';
        foreach ($facilities as $facility) {
            $facility_info = $this->model_facilities_facilities->getfacilities($facility);
            
            if ($facility_info['is_sms_enable'] == '1') {
                $valuess = '1';
            }
        }
        
        if ($valuess == '1') {
            $this->data['is_verification'] = '1';
            
            if ($this->request->get['resend'] == "1") {
                
                $accessKey = $this->request->get['accessKey'];
                $this->load->model('user/user');
                $getUserdetail = $this->model_user_user->getUserByAccessKey($accessKey);
                
                if ($getUserdetail['phone_number'] == null && $getUserdetail['phone_number'] == "") {
                    $this->session->data['warning'] = "Please contact your admin to enroll your picture!";
                    
                    $url2 = '';
                    if ($this->request->get['accessKey'] != null && $this->request->get['accessKey'] != "") {
                        $url2 .= '&accessKey=' . $this->request->get['accessKey'];
                    }
                    
                    $this->redirect($this->url->link('common/resetpassword/verifyotp', '' . $url2, 'SSL'));
                } else {
                    
                    $phone_number = $getUserdetail['phone_number'];
                    $randomNum = $getUserdetail['reset_password_otp'];
                    $message = 'Your OTP for Reset Your Password is ' . $randomNum;
                    $this->load->model('api/smsapi');
                    $sdata = array();
                    $sdata['message'] = $message;
                    $sdata['phone_number'] = $phone_number;
                    
                    if ($getUserdetail['facilities'] != null && $getUserdetail['facilities'] != "") {
                        $facilities = explode(',', $getUserdetail['facilities']);
                        
                        $this->load->model('facilities/facilities');
                        foreach ($facilities as $facility) {
                            $facilities_info = $this->model_facilities_facilities->getfacilities($facility);
                            
                            if ($facilities_info['sms_number'] != null && $facilities_info['sms_number'] != "") {
                                $facilities_id = $facilities_info['facilities_id'];
                                $facility = $facilities_info['facility'];
                                break;
                            }
                        }
                    }
                    
                    $sdata['facilities_id'] = $facilities_id;
                    
                    $response = $this->model_api_smsapi->sendsms($sdata);
					
					if($getUserdetail['email'] != null && $getUserdetail['email'] != ""){
						
						$this->load->model('api/emailapi');
					
						/*$edata = array();
						$edata['message'] = $message;
						$edata['subject'] = $facility.' | '.'Your verification code';
						$edata['user_email'] = $getUserdetail['email'];
							
						$email_status = $this->model_api_emailapi->sendmail($edata);*/

                        $edata = array();
                        $edata['message'] = $message;
                        $edata['facility'] = $facility;
                        $edata['user_email'] = $user_info['email'];
                        $edata['when_date']=date("l");
                        $edata['who_user']=$getUserdetail['username'];
                        $edata['type']="9";
                        $edata['notes_description']=$message;
						$edata['subject'] = $facility.' | '.'Your verification code';
						$email_status = $this->model_api_emailapi->sendmail($edata);
                        //$email_status=$this->model_api_emailapi->createMails($edata);   
					}
                    
                    $this->session->data['success'] = "SMS send successfully";
                    
                    $url2 = '';
                    if ($this->request->get['accessKey'] != null && $this->request->get['accessKey'] != "") {
                        $url2 .= '&accessKey=' . $this->request->get['accessKey'];
                    }
                    
                    $this->redirect($this->url->link('common/resetpassword/verifyotp', '' . $url2, 'SSL'));
                }
            }
        } else {
            $this->data['is_verification'] = '2';
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
        
        $url2 = '';
        if ($this->request->get['accessKey'] != null && $this->request->get['accessKey'] != "") {
            $url2 .= '&accessKey=' . $this->request->get['accessKey'];
        }
        $this->data['action'] = $this->url->link('common/resetpassword/verifyotp', '' . $url2, 'SSL');
        
        $this->data['resend_otp_page'] = $this->url->link('common/resetpassword/verifyotp&resend=1', '' . $url2, 'SSL');
        
        $this->template = $this->config->get('config_template') . '/template/common/resendotp.php';
        $this->children = array(
                'common/headerpopup'
        );
        $this->response->setOutput($this->render());
    }

    public function validateForm2 ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['is_verification'] == "1") {
            if ($this->request->post['reset_password_otp'] == "") {
                $this->error['warning'] = 'Please provide a valid OTP number. It will be sent to you each time on your email and a phone number if provided';
            }
            
            if ($this->request->post['reset_password_otp'] != "" && $this->request->post['reset_password_otp'] != null) {
                $this->load->model('user/user');
                
                $userinfo = $this->model_user_user->getUserByAccessKey($this->request->get['accessKey']);
                
                if ($this->request->post['reset_password_otp'] != $userinfo['reset_password_otp']) {
                    $this->error['warning'] = 'Please provide a valid OTP number. It will be sent to you each time on your email and a phone number if provided';
                }
            }
        } else {
            if ($this->request->post['userpin'] == "") {
                $this->error['warning'] = 'Please provide a valid PIN; This is your PIN number preceded by two alphabets. If you have forgotten the pin, please contact your administrator.';
            }
            if ($this->request->post['userpin'] != "" && $this->request->post['userpin'] != null) {
                
                $this->load->model('user/user');
                $userinfo = $this->model_user_user->getUserByAccessKey($this->request->get['accessKey']);
                if ($this->request->post['userpin'] != $userinfo['user_pin']) {
                    $this->error['warning'] = 'Please provide a valid PIN; This is your PIN number preceded by two alphabets. If you have forgotten the pin, please contact your administrator.';
                }
            }
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
}

