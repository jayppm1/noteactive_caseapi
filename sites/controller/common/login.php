<?php

class ControllerCommonLogin extends Controller
{

    private $error = array();

    public function index ()
    {
        try {
            $this->language->load('common/login');
            
            $this->document->setTitle($this->language->get('heading_title'));
            $this->data['form_outputkey'] = $this->formkey->outputKey();
			
			if($this->session->data['share_note_otp'] != null && $this->session->data['share_note_otp'] != ""){
				unset($this->session->data['time_zone']);
                unset($this->session->data['share_note_otp']);
                unset($this->session->data['time_zone_1']);
                // unset($this->session->data['token']);
                
                unset($this->session->data['note_date_search']);
                unset($this->session->data['note_date_from']);
                unset($this->session->data['note_date_to']);
                unset($this->session->data['keyword']);
                
                unset($this->session->data['sms_user_id']);
                unset($this->session->data['search_user_id']);
                
                unset($this->session->data['notesdatas']);
                unset($this->session->data['advance_search']);
                unset($this->session->data['update_reminder']);
                unset($this->session->data['pagenumber']);
                unset($this->session->data['pagenumber_all']);
                unset($this->session->data['activationkey']);
                unset($this->session->data['username']);
                unset($this->session->data['session_key']);
                
                unset($this->session->data['licfacilities']);
                unset($this->session->data['session_cache_key']);
                unset($this->session->data['user_enroll_confirm']);
                unset($this->session->data['username_confirm']);
                unset($this->session->data['webuser_id']);
                unset ( $this->session->data ['webcustomer_key'] );
				
				unset ( $this->session->data ['search_facilities_id'] );
				
				unset ( $this->session->data ['tagstatusid'] );
				unset ( $this->session->data ['tagclassificationid'] );
				
				unset ( $this->session->data ['facilityids222'] );
				unset ( $this->session->data ['locations222'] );
				unset ( $this->session->data ['tagsids222'] );
				unset ( $this->session->data ['userids222'] );
				
				unset($this->session->data ['late_entrycomments']);
				unset($this->session->data ['manual_movement']);
			}
            
            if ($this->customer->isLogged()) {
                // $this->redirect($this->url->link('notes/notes/insert','',
                // 'SSL'));
                $this->load->model('facilities/facilities');
                
                $data = array();
                $data['activationkey'] = $this->session->data['activationkey'];
                $data['username'] = $this->session->data['webuser_id'];
                $data['facilities_id'] = $this->customer->getId();
                $data['ip'] = $this->request->server['REMOTE_ADDR'];
                $this->model_facilities_facilities->updateFacilityLogout($data);
                
                $this->customer->logout();
                
                $this->load->model('licence/licence');
                $this->model_licence_licence->closeuseractivation();
                
                unset($this->session->data['time_zone']);
                unset($this->session->data['share_note_otp']);
                unset($this->session->data['time_zone_1']);
                // unset($this->session->data['token']);
                
                unset($this->session->data['note_date_search']);
                unset($this->session->data['note_date_from']);
                unset($this->session->data['note_date_to']);
                unset($this->session->data['keyword']);
                
                unset($this->session->data['sms_user_id']);
                unset($this->session->data['search_user_id']);
                
                unset($this->session->data['notesdatas']);
                unset($this->session->data['advance_search']);
                unset($this->session->data['update_reminder']);
                unset($this->session->data['pagenumber']);
                unset($this->session->data['pagenumber_all']);
                unset($this->session->data['activationkey']);
                unset($this->session->data['username']);
                unset($this->session->data['session_key']);
                
                unset($this->session->data['licfacilities']);
                unset($this->session->data['session_cache_key']);
                unset($this->session->data['user_enroll_confirm']);
                unset($this->session->data['username_confirm']);
                unset($this->session->data['webuser_id']);
				
				unset ( $this->session->data ['tagstatusid'] );
				unset ( $this->session->data ['tagclassificationid'] );
				
				unset ( $this->session->data ['facilityids222'] );
				unset ( $this->session->data ['locations222'] );
				unset ( $this->session->data ['tagsids222'] );
				unset ( $this->session->data ['userids222'] );
				
				unset($this->session->data ['late_entrycomments']);
				
				unset ( $this->session->data ['search_facilities_id'] );
                $this->redirect($this->url->link('common/login', '', 'SSL'));
                // $this->redirect($this->url->link('common/licence/activation','',
            // 'SSL'));
            }
            
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                
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
                        $datal['username'] = $this->session->data['webuser_id'];
                        $datal['activationkey'] = $this->session->data['activationkey'];
                        $datal['ip'] = $ip;
                        $datal['type'] = '2';
                        
                        $this->model_facilities_online->whosonline($datal);
                    }
                }
                
                $data = array();
                $data['username'] = $this->session->data['webuser_id'];
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
                //$notes_total = $this->model_notes_notes->getTotalnotess($data);
                
                /*
                 * $this->session->data['pagenumber_all'] =
                 * ceil($notes_total/$config_admin_limit);
                 *
                 * $url3 = "";
                 * if ($this->session->data['pagenumber_all'] != null &&
                 * $this->session->data['pagenumber_all'] != "") {
                 * if($this->session->data['pagenumber_all'] > 1){
                 * $url3 .= '&page=' . $this->session->data['pagenumber_all'];
                 * }
                 * }
                 */
                //$pagenumber_all = ceil($notes_total / $config_admin_limit);
                
                /*if ($pagenumber_all != null && $pagenumber_all != "") {
                    if ($pagenumber_all > 1) {
                        //$url3 .= '&page=' . $pagenumber_all;
                    }
                }*/
                
                /* START */
                
                // $this->session->data['session_key'] = rand(0,100000);
                
                $this->load->model('licence/licence');
                $this->model_licence_licence->checkuseractivation();
                
                /* END */
                
                $this->redirect($this->url->link('notes/notes/insert', '' . $url3, 'SSL'));
            }
            
            $this->load->model('facilities/facilities');
            
            // var_dump($this->session->data['username']);
            
            $data2 = array();
            
            $this->load->model('user/user');
            
            // var_dump($this->session->data['username']);
            $getUserdetail = $this->model_user_user->getUserByUsername($this->session->data['webuser_id']);
            
            // var_dump($getUserdetail['facilities']);
            
            if ($this->session->data['licfacilities']) {
                $data2['facilities'] = $this->session->data['licfacilities'];
            } else {
                $data2['facilities'] = $getUserdetail['facilities'];
            }
            
            
            $results = $this->model_facilities_facilities->getfacilitiess($data2);
           $this->data['facilitiess'] = array();
            foreach ($results as $result) {
                
                $this->data['facilitiess'][] = array(
                        'facilities_id' => $result['facilities_id'],
                        'facility' => $result['facility']
                );
            }
			
            
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
            
            //if($this->config->get('config_saml') == '0'){
				
				if($_SERVER['HTTP_HOST']!="localhost"){
					if($this->session->data['activationkey'] == NULL && $this->session->data['activationkey'] ==""){
						$this->redirect($this->url->link('common/licence/activation','', 'SSL'));
					}
				}
            //}	
            
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
            
            $this->data['action'] = $this->url->link('common/login', '', 'SSL');
            
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
            
            if (isset($this->request->post['lat'])) {
                $this->data['lat'] = $this->request->post['lat'];
            } else {
                $this->data['lat'] = '';
            }
            
            if (isset($this->request->post['log'])) {
                $this->data['log'] = $this->request->post['log'];
            } else {
                $this->data['log'] = '';
            }
            
            if ($this->config->get('config_password')) {
                $this->data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
            } else {
                $this->data['forgotten'] = '';
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
			
            if($user_ip_id == null && $user_ip_id == ""){
				$this->template = $this->config->get('config_template') . '/template/common/restrict.php';
			}else{
				$this->template = $this->config->get('config_template') . '/template/common/login.php';
				$this->children = array(
					'common/headerlogin',
					'common/footerlogin'
				);
			}
            
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
        
        /*
         * $this->load->model('licence/licence');
         * $resulta = $this->model_licence_licence->checkloginlicence();
         *
         * if($resulta != "0"){
         * unset($this->session->data['activationkey']);
         * unset($this->session->data['username']);
         * }
         */
        /*
         * if ($this->customer->isLogged()) {
         * unset($this->session->data['activationkey']);
         * unset($this->session->data['username']);
         * }
         */
        $this->load->model('facilities/facilities');
        $ip_info = $this->model_facilities_facilities->getips($this->request->server['REMOTE_ADDR']);
        
        if ($ip_info != null && $ip_info != "") {
            $this->error['warning'] = 'Your IP has been blocked. Please contact your administrator.';
        }
        
        $ip2_info = $this->model_facilities_facilities->getip($this->request->server['REMOTE_ADDR']);
        
        /*
         * if($ip2_info != null && $ip2_info != ""){
         * $this->error['warning'] = 'Your IP does not match.';
         * }
         */
        
        // die;
        $login_info = $this->model_facilities_facilities->getLoginAttempts($this->request->post['username']);
        
        $config_login_attempts = '10';
        
        if ($login_info && ($login_info['total'] >= $config_login_attempts) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning_2'] = 'Your account has exceeded allowed number of login attempts. Please try again in 1 hour.';
        }
        
        if (! $this->customer->login($this->request->post['username'], $this->request->post['password'])) {
            $this->error['warning'] = 'Incorrect password please try again';
            $this->model_facilities_facilities->addLoginAttempt($this->request->post['username']);
        }
        
        if ($this->config->get('config_current_location') == '1') {
            
            $facilityResult1 = $this->model_facilities_facilities->getfacilities($this->customer->getId());
            
            if ($facilityResult1['config_current_location'] == '1') {
                if (($this->request->post['lat'] == null && $this->request->post['lat'] == "") && ($this->request->post['log'] == null && $this->request->post['log'] == "")) {
                    $this->error['warning'] = 'Please allow access to your current location.';
                }
                
                /*
                 * if(($this->request->post['lat'] != null &&
                 * $this->request->post['lat'] != "") &&
                 * ($this->request->post['log'] != null &&
                 * $this->request->post['log'] != "")){
                 * $data2 = array();
                 * $data2['lat'] = $this->request->post['lat'];
                 * $data2['log'] = $this->request->post['log'];
                 * $iplocation =
                 * $this->model_facilities_facilities->getLocations($data2);
                 *
                 * if(empty($iplocation)){
                 * $this->model_facilities_facilities->addLocations($this->request->post['lat'],$this->request->post['log']);
                 * }
                 * }
                 */
                
                if (($this->request->post['lat'] != null && $this->request->post['lat'] != "") && ($this->request->post['log'] != null && $this->request->post['log'] != "")) {
                    
                    $facilityResult1 = $this->model_facilities_facilities->getfacilities($this->customer->getId());
                    
                    $latitude = $facilityResult1['latitude'];
                    $longitude = $facilityResult1['longitude'];
                    
                    $distanced = $this->distance($this->request->post['lat'], $this->request->post['log'], $latitude, $longitude, "K");
                    
                    $data2 = array();
                    $data2['lat'] = $this->request->post['lat'];
                    $data2['log'] = $this->request->post['log'];
                    $data2['login_allow'] = '1';
                    $data2['facilities_id'] = $this->customer->getId();
                    $checkiplocation = $this->model_facilities_facilities->getLocations($data2);
                    
                    if (($distanced >= '5')) {
                        if (empty($checkiplocation)) {
                            $this->error['warning'] = 'You are not authorised to access from this location';
                        }
                    }
                }
            }
        }
        
        if (! $this->error) {
            if ($this->customer->getId() != null && $this->customer->getId() != "") {
                
                $this->model_facilities_facilities->deleteLoginAttempts($this->request->post['username']);
                
                $facilityResult = $this->model_facilities_facilities->getfacilities($this->customer->getId());
                
                $users = $facilityResult['users'];
                
                /*
                 * if($users == null && $users == ""){
                 * $this->customer->logout();
                 * unset($this->session->data['time_zone_1']);
                 * unset($this->session->data['token']);
                 * $this->error['warning'] = 'You have not users Please create
                 * user';
                 * }else{
                 */
                /*
                 * $sql = "SELECT * FROM `" . DB_PREFIX . "user` ";
                 * $sql .= 'where 1 = 1 ';
                 * if ($this->customer->getId() != null &&
                 * $this->customer->getId() != "") {
                 * $sql .= " and FIND_IN_SET('". $this->customer->getId()."',
                 * facilities) ";
                 * }
                 * $query = $this->db->query($sql);
                 * $results = $query->rows;
                 */
                
                $udata = array();
                $udata['facilities_id'] = $this->customer->getId();
                
                $this->load->model('user/user');
                $results = $this->model_user_user->getUsers($udata);
                
                if ((empty($results)) && ($users == null && $users == "")) {
                    $this->customer->logout();
                    unset($this->session->data['time_zone_1']);
                    // unset($this->session->data['token']);
                    $this->error['warning'] = 'Facility Login Error - No users in this facility, please create/Add user fisrt.';
                }
                // }
            }
        } else {
            $this->customer->logout();
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