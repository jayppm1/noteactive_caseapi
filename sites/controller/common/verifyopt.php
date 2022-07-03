<?php

class ControllerCommonVerifyopt extends Controller
{

    private $error = array();

    public function index ()
    {
        if ($this->session->data['sms_user_id'] == null && $this->session->data['sms_user_id'] == "") {
            $this->redirect($this->url->link('common/licence/activation', '', 'SSL'));
        }
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if ($this->request->post['activationotp'] != NULL && $this->request->post['activationotp'] != "") {
            
            $user_id = $this->session->data['sms_user_id'];
            $this->load->model('user/user');
            $getUserdetail = $this->model_user_user->getUserbyupdate($user_id);
            
            if ($this->request->post['activationotp'] == $getUserdetail['user_otp']) {
                
                $this->session->data['username'] = $getUserdetail['username'];
                $this->session->data['webuser_id'] = $getUserdetail['user_id'];
                $this->session->data['activationkey'] = $getUserdetail['activationKey'];
                
                $this->load->model('licence/licence');
                
                $this->model_licence_licence->webresetactivationkey($this->request->post['activationkey']);
                
                $udata = array();
                $udata['activationkey'] = $getUserdetail['activationKey'];
                
                $result = $this->model_licence_licence->insert_activationkey($udata);
                
                $this->session->data['licfacilities'] = $result->facilities;
                
                $this->session->data['session_key'] = rand(0, 100000);
                
                $this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
            } else {
                
                $this->data['warning'] = "Please enter valid OTP ";
            }
        }
        
        if ($this->request->get['resend'] == "1") {
            
            $user_id = $this->session->data['sms_user_id'];
            $this->load->model('user/user');
            $getUserdetail = $this->model_user_user->getUserbyupdate($user_id);
            
            $phone_number = $getUserdetail['phone_number'];
            $randomNum = $getUserdetail['user_otp'];
            
            $message = 'Your OTP for activation is ' . $randomNum;
            
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
			
				$edata = array();
                $edata['message'] = $message;
                $edata['facility'] = $facilities_info['facility'];
                $edata['user_email'] = $getUserdetail['email'];
                $edata['when_date']=date("l");
                $edata['who_user']=$getUserdetail['username'];
                $edata['type']="10";
                $edata['notes_description']=$message;
				$edata['subject'] = $facilities_info['facility'].' | '.'Your verification code';
				$email_status = $this->model_api_emailapi->sendmail($edata);
                //$email_status=$this->model_api_emailapi->createMails($edata);
			}
            
            $this->session->data['success'] = "SMS sent successfully";
            
            $this->redirect($this->url->link('common/verifyopt', '', 'SSL'));
        }
        
        $this->data['resend_otp'] = $this->url->link('common/verifyopt&resend=1', '' . $url3, 'SSL');
        
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
        
        $this->document->setTitle('Verify OTP');
        
        $this->template = $this->config->get('config_template') . '/template/common/verifyopt.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        
        $this->response->setOutput($this->render());
    }

    public function verifyotp ()
    {
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {
            $this->session->data['otp_valid'] = '1';
            
            if ($this->request->get['otp_type'] == "shared_note") {
                
                $this->load->model('notes/sharenote');
                $this->load->model('user/user');
                $getUserdetail = $this->model_user_user->getUserbyupdate($this->request->get['user_id']);
                
                $timezone_name = $this->customer->isTimezone();
                $timeZone = date_default_timezone_set($timezone_name);
                $date_added11 = date('Y-m-d H:i:s', strtotime('now'));
                
                $date_added1221 = date('Y-m-d H:i:s', strtotime(' 0 minutes', strtotime($date_added11)));
                $current_date_plus = date('Y-m-d H:i:s', strtotime(' +15 minutes', strtotime($date_added11)));
                
                $data = array(
                        'user_id' => $this->request->get['user_id'],
                        'otp_type' => $this->request->get['otp_type'],
                        'date_added_from' => $date_added1221,
                        'date_added_to' => $current_date_plus,
                        'facilities_id' => $this->customer->getId(),
                        'notes_id' => $this->request->get['notes_id'],
                        'share_note_otp' => $this->request->get['share_note_otp']
                );
                $getUserdetail1 = $this->model_user_user->getuserOPT($data);
                
                $pfdata = array();
                $pfdata['user_id'] = $getUserdetail['user_id'];
                $pfdata['notes_id'] = $this->request->get['notes_id'];
                $pfdata['user_email'] = $getUserdetail1['alternate_email'];
                $this->model_notes_sharenote->sharePdf($pfdata);
            }
            
            $url2 = '';
            
            if ($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
                $url2 .= '&user_id=' . $this->request->get['user_id'];
            }
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            if ($this->request->get['otp_type'] != null && $this->request->get['otp_type'] != "") {
                $url2 .= '&otp_type=' . $this->request->get['otp_type'];
            }
            $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/verifyopt/verifyotp', '' . $url2, 'SSL'));
        }
        
        if ($this->request->get['resend'] == "1") {
            
            $accessKey = $this->request->get['user_id'];
            $this->load->model('user/user');
            $getUserdetail = $this->model_user_user->getUserbyupdate($accessKey);
            
            if ($getUserdetail['phone_number'] == null && $getUserdetail['phone_number'] == "") {
                $this->session->data['error'] = "Please contact your admin to enroll your picture!";
                
                $url2 = '';
                if ($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
                    $url2 .= '&user_id=' . $this->request->get['user_id'];
                }
                if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                    $url2 .= '&notes_id=' . $this->request->get['notes_id'];
                }
                if ($this->request->get['otp_type'] != null && $this->request->get['otp_type'] != "") {
                    $url2 .= '&otp_type=' . $this->request->get['otp_type'];
                }
                if ($this->request->get['share_note_otp'] != null && $this->request->get['share_note_otp'] != "") {
                    $url2 .= '&share_note_otp=' . $this->request->get['share_note_otp'];
                }
                
                $this->redirect($this->url->link('common/verifyopt/verifyotp', '' . $url2, 'SSL'));
            } else {
                
                $phone_number = $getUserdetail['phone_number'];
                
                $timezone_name = $this->customer->isTimezone();
                $timeZone = date_default_timezone_set($timezone_name);
                $date_added11 = date('Y-m-d H:i:s', strtotime('now'));
                
                $date_added1221 = date('Y-m-d H:i:s', strtotime(' 0 minutes', strtotime($date_added11)));
                $current_date_plus = date('Y-m-d H:i:s', strtotime(' +15 minutes', strtotime($date_added11)));
                
                $data = array(
                        'user_id' => $this->request->get['user_id'],
                        'otp_type' => $this->request->get['otp_type'],
                        'date_added_from' => $date_added1221,
                        'date_added_to' => $current_date_plus,
                        'facilities_id' => $this->customer->getId(),
                        'notes_id' => $this->request->get['notes_id'],
                        'share_note_otp' => $this->request->get['share_note_otp']
                );
                $getUserdetail1 = $this->model_user_user->getuserOPT($data);
                
                if ($getUserdetail1['otp'] != null && $getUserdetail1['otp'] != "") {
                    $randomNum = $getUserdetail1['otp'];
                    
                    if ($this->request->get['otp_type'] == "shared_note") {
                        $message = 'Your OTP for Share notes is ' . $randomNum;
                    }
                    
                    $this->load->model('api/smsapi');
                    $sdata = array();
                    $sdata['message'] = $message;
                    $sdata['phone_number'] = $phone_number;
                    $sdata['facilities_id'] = $this->customer->getId();
                    
                    $response = $this->model_api_smsapi->sendsms($sdata);
					
					if($getUserdetail1['email'] != null && $getUserdetail1['email'] != ""){
						
						$this->load->model('facilities/facilities');
						
						$facilities_info = $this->model_facilities_facilities->getfacilities($this->customer->getId());
						$this->load->model('api/emailapi');
					
						$edata = array();
                        $edata['message'] = $message;
                $edata['facility'] = $facilities_info['facility'];
                $edata['user_email'] = $getUserdetail['email'];
                $edata['when_date']=date("l");
                $edata['who_user']=$getUserdetail['username'];
                $edata['type']="10";
                $edata['notes_description']=$message;
				$edata['subject'] = $facilities_info['facility'].' | '.'Your verification code';
				
				$email_status = $this->model_api_emailapi->sendmail($edata);
               // $email_status=$this->model_api_emailapi->createMails($edata);
					}
                    
                    $this->session->data['success'] = "SMS sent successfully";
                } else {
                    $this->session->data['error'] = "Your OTP expire";
                }
                
                $url2 = '';
                if ($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
                    $url2 .= '&user_id=' . $this->request->get['user_id'];
                }
                if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                    $url2 .= '&notes_id=' . $this->request->get['notes_id'];
                }
                if ($this->request->get['otp_type'] != null && $this->request->get['otp_type'] != "") {
                    $url2 .= '&otp_type=' . $this->request->get['otp_type'];
                }
                if ($this->request->get['share_note_otp'] != null && $this->request->get['share_note_otp'] != "") {
                    $url2 .= '&share_note_otp=' . $this->request->get['share_note_otp'];
                }
                
                $this->redirect($this->url->link('common/verifyopt/verifyotp', '' . $url2, 'SSL'));
            }
        }
        
        $this->load->model('facilities/facilities');
        $facility_info = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        
        if ($facility_info['is_sms_enable'] == '1') {
            $this->data['is_verification'] = '1';
        } else {
            $this->data['is_verification'] = '2';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->session->data['otp_valid'])) {
            $this->data['otp_valid'] = $this->session->data['otp_valid'];
            
            unset($this->session->data['otp_valid']);
        } else {
            $this->data['otp_valid'] = '';
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
        if ($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
            $url2 .= '&user_id=' . $this->request->get['user_id'];
        }
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        if ($this->request->get['otp_type'] != null && $this->request->get['otp_type'] != "") {
            $url2 .= '&otp_type=' . $this->request->get['otp_type'];
        }
        if ($this->request->get['share_note_otp'] != null && $this->request->get['share_note_otp'] != "") {
            $url2 .= '&share_note_otp=' . $this->request->get['share_note_otp'];
        }
        $this->data['action'] = $this->url->link('common/verifyopt/verifyotp', '' . $url2, 'SSL');
        $this->data['resend_otp_page'] = $this->url->link('common/verifyopt/verifyotp&resend=1', '' . $url2, 'SSL');
        
        $this->document->setTitle('Verify OTP');
        
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
                $this->error['warning'] = 'OPT required';
            }
            
            if ($this->request->post['reset_password_otp'] != "" && $this->request->post['reset_password_otp'] != null) {
                $this->load->model('user/user');
                
                $timezone_name = $this->customer->isTimezone();
                $timeZone = date_default_timezone_set($timezone_name);
                $date_added11 = date('Y-m-d H:i:s', strtotime('now'));
                
                $date_added1221 = date('Y-m-d H:i:s', strtotime(' 0 minutes', strtotime($date_added11)));
                $current_date_plus = date('Y-m-d H:i:s', strtotime(' +15 minutes', strtotime($date_added11)));
                
                $data = array(
                        'user_id' => $this->request->get['user_id'],
                        'otp_type' => $this->request->get['otp_type'],
                        'date_added_from' => $date_added1221,
                        'date_added_to' => $current_date_plus,
                        'facilities_id' => $this->customer->getId(),
                        'notes_id' => $this->request->get['notes_id'],
                        'share_note_otp' => $this->request->get['share_note_otp']
                );
                $getUserdetail1 = $this->model_user_user->getuserOPT($data);
                
                if ($this->request->post['reset_password_otp'] != $getUserdetail1['otp']) {
                    $this->error['warning'] = 'Please enter valid OPT';
                }
            }
        } else {
            if ($this->request->post['userpin'] == "") {
                $this->error['warning'] = 'Userpin required';
            }
            if ($this->request->post['userpin'] != "" && $this->request->post['userpin'] != null) {
                
                $this->load->model('user/user');
                $userinfo = $this->model_user_user->getUserByAccessKey($this->request->get['accessKey']);
                if ($this->request->post['userpin'] != $userinfo['user_pin']) {
                    $this->error['warning'] = 'Please enter valid Userpin';
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