<?php

class Controllerresidentassignteam extends Controller
{

    private $error = array();

    public function index ()
    {
        if (! $this->customer->isLogged()) {
            $this->redirect($this->url->link('common/login', '', 'SSL'));
        }
        
        $this->load->model('resident/resident');
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('setting/tags');
        
        $tags_id = $this->request->get['tags_id'];
        $tag_info = $this->model_setting_tags->getTag($tags_id);
        
        $this->data['name'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $this->load->model('notes/notes');
            $notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
            
            $this->data['note_date_added'] = date('m-d-Y h:i A', strtotime($notes_info['date_added']));
        }
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            // var_dump($this->request->get['tags_id']);
            // var_dump($this->request->post);
            
            $timezone_name = $this->customer->isTimezone();
            $timeZone = date_default_timezone_set($timezone_name);
            $noteDate = date('Y-m-d H:i:s', strtotime('now'));
            
            $data2 = array();
            $data2['tags_id'] = $this->request->get['tags_id'];
            $data2['date_added'] = $noteDate;
            $data2['facilities_id'] = $this->customer->getId();
            // $this->model_resident_resident->addassignteam($data2,
            // $this->request->post);
            
            if ($this->request->post['user_roles'] != null && $this->request->post['user_roles'] != "") {
                $roles = array_unique($this->request->post['user_roles']);
                $user_roles = implode(',', $roles);
                $url2 .= '&user_roles=' . $user_roles;
            }
            
            if ($this->request->post['userids'] != null && $this->request->post['userids'] != "") {
                $ids = array_unique($this->request->post['userids']);
                $userids = implode(',', $ids);
                $url2 .= '&userids=' . $userids;
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->post['emp_tag_id'];
            }
            
            $this->session->data['success2'] = 'Team updated successfully!';
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
            
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&assignteam=1';
                $this->redirect($this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                
                $this->redirect($this->url->link('resident/assignteam', '' . $url2, 'SSL'));
            }
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->session->data['success2'])) {
            $this->data['success2'] = $this->session->data['success2'];
            
            unset($this->session->data['success2']);
        } else {
            $this->data['success2'] = '';
        }
        
        if (isset($this->session->data['success_add_form1'])) {
            $this->data['success_add_form1'] = $this->session->data['success_add_form1'];
            
            unset($this->session->data['success_add_form1']);
        } else {
            $this->data['success_add_form1'] = '';
        }
        
        $data3 = array();
        $data3['tags_id'] = $this->request->get['tags_id'];
        $data3['is_archive'] = $this->request->get['is_archive'];
        $data3['notes_id'] = $this->request->get['notes_id'];
        $data3['facilities_id'] = $this->customer->getId();
        $team_infos = $this->model_resident_resident->getassignteam($data3);
        
        $this->data['user_roles'] = array();
        $this->load->model('user/user_group');
        $this->load->model('user/user');
        
        if ($team_infos != null && $team_infos != "") {
            foreach ($team_infos as $team_info) {
                // var_dump($team_info);
                $user_role_info = $this->model_user_user_group->getUserGroup($team_info['user_roles']);
                
                if ($user_role_info) {
                    $users = array();
                    
                    $data3u = array();
                    $data3u['tags_id'] = $this->request->get['tags_id'];
                    $data3u['is_archive'] = $this->request->get['is_archive'];
                    $data3u['notes_id'] = $this->request->get['notes_id'];
                    $data3u['user_roles'] = $team_info['user_roles'];
                    $data3u['facilities_id'] = $this->customer->getId();
                    
                    $uresults = $this->model_resident_resident->getassignteamUsers($data3u);
                    
                    if ($uresults != null && $uresults != "") {
                        foreach ($uresults as $user) {
                            $user_info = $this->model_user_user->getUserbyupdate($user['userids']);
                            if ($user_info['user_id']) {
                                $users[] = array(
                                        'user_id' => $user_info['user_id'],
                                        'username' => strip_tags(html_entity_decode($user_info['username'], ENT_QUOTES, 'UTF-8'))
                                );
                            }
                        }
                    }
                    
                    $this->data['user_roles'][] = array(
                            'user_group_id' => $team_info['user_roles'],
                            'name' => $user_role_info['name'],
                            'users' => $users
                    );
                }
            }
        }
        
        $url2 = "";
        $url3 = "";
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            $url3 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->post['emp_tag_id'];
            $url3 .= '&tags_id=' . $this->request->post['emp_tag_id'];
        }
        
        if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
            $url2 .= '&is_archive=' . $this->request->get['is_archive'];
            $this->data['is_archive'] = $this->request->get['is_archive'];
        }
        if ($this->request->get['user_roles'] != null && $this->request->get['user_roles'] != "") {
            $url2 .= '&user_roles=' . $this->request->get['user_roles'];
        }
        if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
            $url2 .= '&userids=' . $this->request->get['userids'];
        }
        
        $this->data['updateredirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/assignteam/tagsassign', '' . $url2, 'SSL'));
        $this->data['currentt_url'] = str_replace('&amp;', '&', $this->url->link('resident/assignteam', '' . $url3, 'SSL'));
        
        $this->template = $this->config->get('config_template') . '/template/resident/assignteam.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validate ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if (($this->request->post['user_roles'] == NULL && $this->request->post['user_roles'] == "") && ($this->request->post['userids'] == NULL && $this->request->post['userids'] == "")) {
            
            $this->error['warning'] = 'Please select user or role.';
        }
        
        if ($this->request->post['userid'] != NULL && $this->request->post['userid'] != "") {
            
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUserByUsername($this->request->post['userid']);
            
            if ($user_info['user_id'] == NULL && $user_info['user_id'] == "") {
                $this->error['warning'] = 'User does not exists';
            }
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			$unique_id = $facility['customer_key'];
			
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			if($user_info['customer_key'] != $customer_info['activecustomer_id']){
				$this->error['user_id'] = $this->language->get('error_customer');
			}
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function tagsassign ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        
        $this->load->model('notes/notes');
        $this->load->model('resident/resident');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $tdata = array();
            $tdata['tags_id'] = $this->request->get['tags_id'];
            $tdata['user_roles'] = $this->request->get['user_roles'];
            $tdata['userids'] = $this->request->get['userids'];
            $tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
            $this->model_resident_resident->tagsassign($this->request->post, $tdata);
            
            $this->session->data['success_add_form1'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if ($notes_id != null && $notes_id != "") {
                $url2 .= '&notes_id=' . $notes_id;
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/assignteam', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
        
        $this->data['config_tag_status'] = $this->customer->isTag();
        
        $url2 = "";
        
        if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
            $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        $config_admin_limit1 = $this->config->get('config_front_limit');
        if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
            $config_admin_limit = $config_admin_limit1;
        } else {
            $config_admin_limit = "50";
        }
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
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
                $url2 .= '&page=' . $pagenumber_all;
            }
        }
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
            $url2 .= '&is_archive=' . $this->request->get['is_archive'];
        }
        if ($this->request->get['user_roles'] != null && $this->request->get['user_roles'] != "") {
            $url2 .= '&user_roles=' . $this->request->get['user_roles'];
        }
        if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
            $url2 .= '&userids=' . $this->request->get['userids'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/assignteam/tagsassign', '' . $url2, 'SSL'));
        $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        if (isset($this->session->data['pagenumber'])) {
            $this->data['pagenumber'] = $this->session->data['pagenumber'];
        } else {
            $this->data['pagenumber'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->error['select_one'])) {
            $this->data['error_select_one'] = $this->error['select_one'];
        } else {
            $this->data['error_select_one'] = '';
        }
        
        if (isset($this->error['notes_pin'])) {
            $this->data['error_notes_pin'] = $this->error['notes_pin'];
        } else {
            $this->data['error_notes_pin'] = '';
        }
        
        if (isset($this->error['highlighter_id'])) {
            $this->data['error_highlighter_id'] = $this->error['highlighter_id'];
        } else {
            $this->data['error_highlighter_id'] = '';
        }
        
        if (isset($this->error['user_id'])) {
            $this->data['error_user_id'] = $this->error['user_id'];
        } else {
            $this->data['error_user_id'] = '';
        }
        
        if (isset($this->request->post['select_one'])) {
            $this->data['select_one'] = $this->request->post['select_one'];
        } else {
            if ($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != "") {
                $config_default_sign = '1'; // $this->config->get('config_default_sign');
            } else {
                $config_default_sign = '2';
            }
            $this->data['select_one'] = $config_default_sign;
        }
        
        if ($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != "") {
            $this->data['default_sign'] = '1'; // $this->config->get('config_default_sign');
        } else {
            $this->data['default_sign'] = '2';
        }
        
        if (isset($this->request->post['notes_pin'])) {
            $this->data['notes_pin'] = $this->request->post['notes_pin'];
        } elseif (! empty($notes_info)) {
            $this->data['notes_pin'] = $notes_info['notes_pin'];
        } else {
            $this->data['notes_pin'] = '';
        }
        
        $this->data['local_image_url'] = $this->session->data['local_image_url'];
        if (isset($this->request->post['user_id'])) {
            $this->data['user_id'] = $this->request->post['user_id'];
        } elseif (! empty($notes_info)) {
            $this->data['user_id'] = $notes_info['user_id'];
        } elseif (! empty($this->session->data['username_confirm'])) {
            $this->data['user_id'] = $this->session->data['username_confirm'];
        }else {
            $this->data['user_id'] = '';
        }
        
        $this->load->model('setting/tags');
        $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
        
        if (isset($this->request->post['emp_tag_id'])) {
            $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id'] = $tag_info['emp_tag_id'];
        } else {
            $this->data['emp_tag_id'] = '';
        }
        
        if (isset($this->request->post['tags_id'])) {
            $this->data['tags_id'] = $this->request->post['tags_id'];
        } elseif (! empty($tag_info)) {
            $this->data['tags_id'] = $tag_info['tags_id'];
        } else {
            $this->data['tags_id'] = '';
        }
        
        if (isset($this->request->post['emp_tag_id_2'])) {
            $this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id_2'] = $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['emp_tag_id_2'] = '';
        }
        
        if (isset($this->request->post['comments'])) {
            $this->data['comments'] = $this->request->post['comments'];
        } else {
            $this->data['comments'] = '';
        }
        
        $this->data['createtask'] = 1;
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validateForm23 ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
        
        if ($this->request->post['user_id'] != '') {
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($this->request->post['user_id']);
            
            if (empty($user_info)) {
                $this->error['user_id'] = $this->language->get('error_required');
            }
        }
        
        if ($this->request->post['select_one'] == '') {
            $this->error['select_one'] = $this->language->get('error_required');
        }
        
        if ($this->request->post['select_one'] == '1') {
            if ($this->request->post['notes_pin'] == '') {
                $this->error['notes_pin'] = $this->language->get('error_required');
            }
            if ($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != "") {
                $this->load->model('user/user');
                
               if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}
                
                if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
                    $this->error['warning'] = $this->language->get('error_exists');
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