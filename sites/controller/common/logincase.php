<?php

class ControllerCommonLogincase extends Controller
{
	
    private $error = array();

    public function index ()
    {
        try {
			
			
            $this->language->load('common/login');
            
            $this->document->setTitle($this->language->get('heading_title'));
            $this->data['form_outputkey'] = $this->formkey->outputKey();
			
            
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				if($this->config->get('config_saml') == '0'){
                $this->session->data['session_cache_key'] = rand(0, 100000);
                
                if ($this->session->data['session_key'] == '' && $this->session->data['session_key'] == null) {
                    $this->session->data['session_key'] = rand(0, 100000);
                }
                
                $this->session->data['time_zone_1'] = $this->request->post['time_zone_1'];
                
                $this->load->model('facilities/facilities');
                
                if ($this->customer->isLogged()) {
                    
                    if ($this->config->get('config_facility_online')) {
                        $this->load->model('facilities/online');
                        
                        if (isset($this->request->server['REMOTE_ADDR'])) {
                            $ip = $this->request->server['REMOTE_ADDR'];
                        } else {
                            $ip = '';
                        }
                        
                        if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
                            $url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
                        } else {
                            $url = '';
                        }
                        
                        if (isset($this->request->server['HTTP_REFERER'])) {
                            $referer = $this->request->server['HTTP_REFERER'];
                        } else {
                            $referer = '';
                        }
                        
                        $userId = $this->customer->isLogged();
                        
                        $datal = array();
                        $datal['facilities_id'] = $userId;
                        $datal['url'] = $url;
                        $datal['referer'] = $referer;
                        $datal['username'] = $this->session->data['username'];
                        $datal['activationkey'] = $this->session->data['activationkey'];
                        $datal['ip'] = $ip;
                        $datal['type'] = '2';
                        
                        $this->model_facilities_online->whosonline($datal);
                    }
                }
                
                $data = array();
                $data['username'] = $this->session->data['username'];
                $data['activationkey'] = $this->session->data['activationkey'];
                $data['facilities_id'] = $this->customer->getId();
                $data['ip'] = $this->request->server['REMOTE_ADDR'];
                
                // $this->model_facilities_facilities->updateFacilityLogin($data);
                
                $config_admin_limit1 = $this->config->get('config_front_limit');
                if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
                    $config_admin_limit = $config_admin_limit1;
                } else {
                    $config_admin_limit = "50";
                }
                
                $data = array(
                        'searchdate' => date('m-d-Y'),
                        'searchdate_app' => '1',
                        'facilities_id' => $this->customer->getId()
                );
                
                $this->load->model('notes/notes');
                $notes_total = $this->model_notes_notes->getTotalnotess($data);
              
                $pagenumber_all = ceil($notes_total / $config_admin_limit);
                
                if ($pagenumber_all != null && $pagenumber_all != "") {
                    if ($pagenumber_all > 1) {
                        $url3 .= '&page=' . $pagenumber_all;
                    }
                }
                
                /* START */
                
                // $this->session->data['session_key'] = rand(0,100000);
                
                $this->load->model('licence/licence');
                $this->model_licence_licence->checkuseractivation();

					$this->redirect($this->url->link('case/clients', '' . $url3, 'SSL'));
                
                /* END */
				}elseif($this->config->get('config_saml') == '1'){
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
					$userdata = $this->model_user_user->getUserByUsername($userResult['Username']);
					

					if( $userdata == NULL || $userdata == ""){
						$data = array();
						$data['username'] = $userResult['Username'];
						$data['email'] = $email;
						$data['firstname'] = '';
						$data['lastname'] = '';
						$data['phone_number'] = '';
						$data['activationKey'] = $userResult['Username'].rand();
						
						
						$this->load->model('licence/licence');
                
						$key = str_replace(' ', '', $data['activationKey']);
						$user_id = $userResult['user_id'];
						//$this->model_licence_licence->addKeyactivation($key, $userResult['Username'],$user_id);
				
						//$userdetails = $this->model_user_user->insertUser($data);
						
						
					}

					
					if($userResult !=NULL && $userResult != ""){
						
						$this->session->data['activationkey'] = $userdata['activationKey'];
						$this->session->data['session_key'] = rand(0, 100000);
						
						$this->session->data['username'] = $userdata['Username'];
						$this->redirect($this->url->link('case/clients','', 'SSL'));
					}
				
				}
				}
				
                
                
            }
            
            $this->load->model('facilities/facilities');
            
            // var_dump($this->session->data['username']);
            
            $data2 = array();
            

            
         
            
            $this->data['heading_title'] = $this->language->get('heading_title');
            
            $this->data['text_new_customer'] = $this->language->get('text_new_customer');
            $this->data['text_register'] = $this->language->get('text_register');
            $this->data['text_register_account'] = $this->language->get('text_register_account');
            $this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
            $this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
            $this->data['text_forgotten'] = $this->language->get('text_forgotten');
            
            $this->data['entry_email'] = $this->language->get('entry_email');
            $this->data['entry_password'] = $this->language->get('entry_password');
            
            $this->data['button_continue'] = $this->language->get('button_continue');
            $this->data['button_login'] = $this->language->get('button_login');
            
       
            
            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            } else {
                $this->data['error_warning'] = '';
            }
            
            if (isset($this->error['warning_2'])) {
                $this->data['error_warning_2'] = $this->error['warning_2'];
            } else {
                $this->data['error_warning_2'] = '';
            }
            
            if (isset($this->session->data['success'])) {
                $this->data['success'] = $this->session->data['success'];
                
                unset($this->session->data['success']);
            } else {
                $this->data['success'] = '';
            }
            
            $this->data['action'] = $this->url->link('common/logincase', '', 'SSL');
            
            if (isset($this->request->post['username'])) {
                $this->data['username'] = $this->request->post['username'];
            } else {
                $this->data['username'] = '';
            }
            
            if (isset($this->request->post['password'])) {
                $this->data['password'] = $this->request->post['password'];
            } else {
                $this->data['password'] = '';
            }
            
           
            
            unset($this->session->data['sms_user_id']);
			
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
			
			/*
            if($user_ip_id == null && $user_ip_id == ""){
				$this->template = $this->config->get('config_template') . '/template/common/restrict.php';
			}else{
				
			}
			
			*/
			
			$this->template = $this->config->get('config_template') . '/template/common/logincase.php';
				$this->children = array(
					'common/headerlogin',
					'common/footerlogin'
				);
            
            $this->response->setOutput($this->render());
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Common Login'
            );
            $this->model_activity_activity->addActivity('sitescommonlogin', $activity_data2);
            
            // echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    protected function validate ()
    {
        
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
		
		if($this->config->get('config_saml') == '0'){
				if (! $this->customer->login($this->request->post['username'], $this->request->post['password'])) {
					
					$this->load->model('user/user');
					$this->load->model('user/user_group');
					$getUserdetail = $this->model_user_user->getuserbynamenpass($this->request->post['username'],$this->request->post['password']);
					
					$Userinfo = $this->model_user_user_group->getUserGroup($getUserdetail['user_group_id']);
					
					
					if(!empty($getUserdetail)){
						$this->session->data['username'] = $this->request->post['username'];
						
					}else{
						$this->error['warning'] = 'Incorrect password please try again';
					}
				
				
					//$this->error['warning'] = 'Incorrect password please try again';
					//$this->model_facilities_facilities->addLoginAttempt($this->request->post['username']);
				}
		}else{
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

    public function distance ($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else 
            if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
    }
}
?>