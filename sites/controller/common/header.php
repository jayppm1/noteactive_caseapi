<?php

class ControllerCommonHeader extends Controller
{

    protected function index ()
    {
        try {
            /*
             * if (!$this->customer->isLogged()) {
             * $this->redirect($this->url->link('common/login', '', 'SSL'));
             * }
             */
            
            $this->language->load('common/header');
            $this->data['title'] = $this->document->getTitle();
            
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }
            
            if (isset($this->session->data['error']) && ! empty($this->session->data['error'])) {
                $this->data['error'] = $this->session->data['error'];
                
                unset($this->session->data['error']);
            } else {
                $this->data['error'] = '';
            }
            
            $this->data['base'] = $server;
            
            if ($this->customer->isLogged()) {
                
                $this->data['privateusername'] = $this->session->data['username'];
                $this->data['isPrivate'] = $this->session->data['isPrivate'];
                $this->data['privaterole'] = $this->customer->getUserpRole();
                
                // $this->data['userPrivateGroups'] =
            // $this->customer->getPrivateUsersByRole();
            }
            
            $this->data['description'] = $this->document->getDescription();
            $this->data['keywords'] = $this->document->getKeywords();
            $this->data['links'] = $this->document->getLinks();
            $this->data['name'] = $this->config->get('config_name');
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $this->data['text_logout'] = $this->language->get('text_logout');
            
            if (! $this->customer->isLogged()) {
                $this->data['logged'] = '';
                
                $this->data['home'] = $this->url->link('common/login', '', 'SSL');
            } else {
                
                $this->data['loggeddata'] = sprintf($this->language->get('text_logged'), $this->customer->getfacility());
                $this->data['username'] = $this->customer->getfacility();
                
                $this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
                
                $this->data['home'] = $this->url->link('notes/notes');
                $this->data['facility_url'] = $this->url->link('facilities/facilities/update', '', 'SSL');
                
                $this->data['resident_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
                
                /*
                 * $url = "";
                 *
                 * if ($this->session->data['pagenumber_all'] != null &&
                 * $this->session->data['pagenumber_all'] != "") {
                 * $url .= '&page=' . $this->session->data['pagenumber_all'];
                 * }
                 */
                
                $this->data['config_tag_status'] = $this->customer->isTag();
				
				
                
                $this->data['config_taskform_status'] = $this->customer->isTaskform();
                $this->data['config_noteform_status'] = $this->customer->isNoteform();
                $this->data['config_rules_status'] = $this->customer->isRule();
                
                $url = "";
                if ($this->request->post['advance_search'] != '1') {
                    
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
                            $url .= '&page=' . $pagenumber_all;
                        }
                    }
                }
                
                if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                    $noteTime = date('H:i:s');
                    
                    $date = str_replace('-', '/', $this->request->get['searchdate']);
                    $res = explode("/", $date);
                    $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
                    
                    $this->data['note_date'] = $changedDate . ' ' . $noteTime;
                    $searchdate = $this->request->get['searchdate'];
                    $this->data['searchdate'] = $this->request->get['searchdate'];
                    
                    $currentdate = $changedDate;
                    
                    if (($searchdate) >= (date('m-d-Y'))) {
                        $this->data['back_date_check'] = "1";
                    } else {
                        $this->data['back_date_check'] = "2";
                    }
                } else {
                    $this->data['note_date'] = date('Y-m-d H:i:s');
                    $this->data['searchdate'] = date('m-d-Y');
                    $searchdate = date('m-d-Y');
                    
                    $currentdate = date('d-m-Y');
                }
                
                // var_dump($searchdate);
                
                if (($searchdate) == date('m-d-Y')) {
                    $this->data['cnt_date_check'] = "1";
                } else {
                    $this->data['cnt_date_check'] = "2";
                }
                
                // var_dump($this->data['cnt_date_check']);
                
                if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                    $url .= '&searchdate=' . $this->request->get['searchdate'];
                }
                
                $url2 = '';
                if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                    $url2 .= '&tags_id=' . $this->request->get['tags_id'];
                }
				
				if ($this->request->get['tags_ids'] != null && $this->request->get['tags_ids'] != "") {
                    $url2 .= '&tags_ids=' . $this->request->get['tags_ids'];
                }
                
                $this->data['s_url'] = $url2;
                
                $this->load->model('createtask/createtask');
                
                $this->data['complteteTaskLists'] = $this->model_createtask_createtask->gettaskLists($currentdate, $this->customer->getId());
                
                // $this->data['url_notificationload'] =
                // $this->getChild('notes/rules/notification');
                // var_dump($this->data['url_notificationload']);
                
                $this->data['unloackUrl'] = $this->url->link('notes/notes/unlockUser', '' . $url, 'SSL');
                $this->data['config_tag_status'] = $this->customer->isTag();
                
                $this->data['config_taskform_status'] = $this->customer->isTaskform();
                $this->data['config_noteform_status'] = $this->customer->isNoteform();
                $this->data['config_rules_status'] = $this->customer->isRule();
				
				
				$this->load->model('api/permision');
                $this->data['current_permission'] = $this->model_api_permision->getpermision($this->customer->getId());
				//var_dump($this->data['current_permission'] );
                
                // $this->data['notes_url'] = $this->url->link('notes/notes',
                // '', 'SSL');
                
                
                if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
                	$facilities_id = $this->session->data['search_facilities_id'];
                }else{
                	$facilities_id = $this->customer->getId();
                }
                	
                $this->load->model ( 'facilities/facilities' );
                $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
                $unique_id = $facility ['customer_key'];
                	
                $this->load->model ( 'customer/customer' );
                $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
                $this->data['customers'] = array();
                if (! empty ( $customer_info ['setting_data'])) {
                	$customers = unserialize($customer_info ['setting_data']);
                	$this->data['customerinfo'] = $customers;
                }
                
                $this->data['case_url1'] = $this->url->link('resident/formcase/cases&addcase=1', '' , 'SSL');
                $this->data['add_notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1', 'SSL');
                
                $this->data['notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1', 'SSL');
                $this->data['notes_url_close'] = $this->url->link('notes/notes/insert', '' . '&reset=1', 'SSL');
                $this->data['support_url'] = $this->url->link('notes/support', '', 'SSL');
                $this->data['searchUlr'] = $this->url->link('notes/notes/search', '', 'SSL');
                
                $this->data['createtask_url'] = $this->url->link('notes/createtask', '', 'SSL');
                $this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '', 'SSL');
                
                $this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '', 'SSL');
                $this->data['addtasktask_url'] = $this->url->link('notes/notes/index', '', 'SSL');
                $this->data['inserttask_url'] = $this->url->link('notes/createtask/inserttask', '', 'SSL');
                
                $this->data['checklist_url'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/checklistform', '' . $url2, 'SSL'));
                $this->data['incident_url'] = str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert', '' . $url2, 'SSL'));
                
                $this->data['reviewnoted_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/reviewNotes', '' . $url2, 'SSL'));
                
                $this->data['custom_form_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
                
                $this->data['case_url'] = str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard', '', 'SSL'));
                
                $this->data['notes_url_other'] = str_replace('&amp;', '&', $this->url->link('syndb/syndbother', '', 'SSL'));
				
				$this->data['custom_video_url'] = str_replace('&amp;', '&', $this->url->link('notes/video', '' . $url2, 'SSL'));
				$this->data['acaurl'] = str_replace('&amp;', '&', $this->url->link('notes/acarules', '' . $url2, 'SSL'));
				
				
				$this->data['custom_livevideo_url'] = str_replace('&amp;', '&', $this->url->link('notes/video/livevideo', '' . $url2, 'SSL'));
				
				
				$this->data['transcript'] = str_replace('&amp;', '&', $this->url->link('notes/transcript', '' . $url2, 'SSL'));
				$this->data['csv_pload_url'] = str_replace('&amp;', '&', $this->url->link('notes/uploadcsv', '' . $url2, 'SSL'));
				
				
				$this->data['addinventory_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/addInventory', '' . $url2, 'SSL'));

                  $this->data['addinventorys_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&openinventory=1', '' . $url2, 'SSL'));

                $this->data['checkoutinventorys_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&openinventory=2', '' . $url2, 'SSL'));

                $this->data['checkininventorys_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&openinventory=3', '' . $url2, 'SSL'));
				
				
				$this->data['generatereport_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&generatereport=1', '' . $url2, 'SSL'));
                
                $url2 = "";
                if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                    $url2 = '&searchdate=' . $this->request->get['searchdate'];
                }
                
                $this->load->model('facilities/facilities');
                $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
                if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                    $url2 .= '&update_strike=1';
                    $this->data['update_strike_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
                } else {
                    $this->data['update_strike_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrike', '' . $url2, 'SSL'));
                }
                
                $this->data['update_strike_url_private'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrikeprivate', '' . $url2, 'SSL'));
                $this->data['alarm_url'] = $this->url->link('notes/notes/setAlarm', '', 'SSL');
                
                $this->data['dailycensus'] = $this->url->link('resident/dailycensus', '', 'SSL');
				
				if($facility['notes_facilities_ids'] != null && $facility['notes_facilities_ids'] != ""){
					$this->data['is_master_facility']  =  '1' ; 
				}else{
					$this->data['is_master_facility']  =  '2' ; 
				}
				$this->data['masterUlr'] = $this->url->link('notes/master', '', 'SSL');
				
				
				if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !=""){
					//if($this->session->data['search_facilities_id'] != $this->customer->getId()){
						$this->data['search_facilities_id'] = $this->session->data['search_facilities_id'];
					 
						$searchf_name =  $this->model_facilities_facilities->getfacilities($this->session->data['search_facilities_id']);
						$this->data['searchf_name'] = $searchf_name['facility'];
					//}
				 
				}
				
                
                $this->data['logged'] = $this->customer->isLogged();
                
                $this->load->model('setting/highlighter');
                $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters($data);
                
                $route = $this->request->get['route'];
                if ($route == 'notes/notes') {
                    $this->data['urlanchor'] = '1';
                } else {
                    $this->data['urlanchor'] = '2';
                }
                
                $timezone_name = $this->customer->isTimezone();
                date_default_timezone_set($timezone_name);
                
                if (isset($this->request->get['searchdate'])) {
                    $res = explode("-", $this->request->get['searchdate']);
                    $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
                    
                    $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
                    $currentdate = $createdate1;
                } else {
                    $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
                    
                    $currentdate = date('d-m-Y');
                }
            }
			
			$this->load->model('notes/clientstatus');
			$movecount = array ();
			$data3 = array ();
			$data3 ['facilities_id'] = $facilities_id;
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
			foreach ( $customforms as $customform ) {
				
				$this->data ['clientstatuss'] [] = array (
						'tag_status_id' => $customform ['tag_status_id'],
						'name' => $customform ['name'],
						'facilities_id' => $customform ['facilities_id'],
						'display_client' => $customform ['display_client'] 
				);
				
				if ($customform ['type'] == "4") {
					$movecount [] = $customform ['tag_status_id'];
				}
				
			}
			if($movecount!=null && $movecount!=""){
				$movecount = implode(",",$movecount);
			}
			//if($movecount != null && $movecount != ""){
				$data312 = array(
						'status' => 1,
						'discharge' => 1,
						'is_movement' => 1,
						'movecount' => $movecount,
						//'role_call' =>$this->request->get['role_call'],
						'is_master'=> 1,
						'gender2' => $this->request->get['gender'],
						'sort' => 'emp_last_name',
						'facilities_id' => $facilities_id,
						//'emp_tag_id_all' => $search_tags,
						'is_client_screen' => $is_client_screen,
						'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
						'emp_tag_id_all' => $this->request->get['search_tags'],
						'wait_list' => $this->request->get['wait_list'],
						'room_id'=>$this->request->get['room_id'],
						'all_record' => '1',
				
				);
			//}
			$this->load->model('setting/tags');
			$this->data['movetotal_out_tags'] = $this->model_setting_tags->getTotalTags($data312);
			
			//var_dump($this->data['movetotal_out_tags']);
            
            $this->data['listtask'] = array();
            $this->data['listtask2'] = array();
            
            $config_task_status = $this->customer->isTask();
            
            $this->data['checkTask'] = $config_task_status;
            
            if ($config_task_status == '1') {
                
                // $this->data['headertasklist'] =
                // $this->load->controller('notes/createtask/headertasklist');
                
                $this->children = array(
                        'notes/createtask/headertasklist',
                        'notes/createtask/headertasklisttotal',
                        'common/usercamera'
                );
            }
            
            if ($this->config->get('active_notification') == '1') {
                $fcmd = array();
                // $fcmd['ajax_url'] = HTTP_SERVER .'send.php';
                $fcmd['ajax_url'] = str_replace('&amp;', '&', $this->url->link('notes/rules/notificationweb/', '' . $url2, 'SSL'));
                $fcmd['sw_path'] = HTTP_SERVER . 'sites/view/javascript/fcm/firebase-messaging-sw.js';
                $fcmd['notify_app_path'] = 'https://www.gstatic.com/firebasejs/5.1.0/firebase-app.js';
                $fcmd['notify_msg_path'] = 'https://www.gstatic.com/firebasejs/5.1.0/firebase-messaging.js';
                $fcmd['messagingSenderId'] = '558806439883';
                $fcmd['reg_url'] = HTTP_SERVER . '?regId=';
                $this->data['fcm_data'] = json_encode($fcmd);
                // var_dump(json_encode($fcmd));
                
                $this->data['ajax_url111'] = str_replace('&amp;', '&', $this->url->link('notes/rules/notificationweb/', '' . $url2, 'SSL'));
            }
            // var_dump($this->data['listtask']);
            
            /*
             * $this->load->model('api/encrypt');
             *
             *
             * $edevice_username =
             * $this->model_api_encrypt->encrypt($this->config->get('device_username'));
             * $ddevice_username =
             * $this->model_api_encrypt->decrypt($edevice_username);
             * var_dump($this->config->get('device_username'));
             * echo "<hr>";
             * var_dump($edevice_username);
             * echo "<hr>";
             * var_dump($ddevice_username);
             * echo "<hr>";
             *
             * var_dump($this->config->get('device_token'));
             * echo "<hr>";
             * $edevice_token =
             * $this->model_api_encrypt->encrypt($this->config->get('device_token'));
             * var_dump($edevice_token);
             * echo "<hr>";
             * $ddevice_token =
             * $this->model_api_encrypt->decrypt($edevice_token);
             * var_dump($ddevice_token);
             * echo "<hr>";
             */
            $this->template = $this->config->get('config_template') . '/template/common/header.php';
            
            $this->render();
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Common Header List'
            );
            $this->model_activity_activity->addActivity('sitesheader_list', $activity_data2);
            
            // echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
?>
