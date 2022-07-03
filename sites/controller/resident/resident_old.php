<?php

class Controllerresidentresident extends Controller
{

    private $error = array();

    public function index ()
    {  


        if (! $this->customer->isLogged()) {
            $this->redirect($this->url->link('common/login', '', 'SSL'));
        }
		
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		/*
			$this->load->model('licence/licence');
              $resulta = $this->model_licence_licence->checkloginlicence();
              //var_dump($resulta);
              if($resulta == 0){
             
              $this->customer->logout();
              unset($this->session->data['time_zone_1']);
              //unset($this->session->data['token']);
             
              unset($this->session->data['note_date_search']);
              unset($this->session->data['note_date_from']);
             
              unset($this->session->data['note_date_to']);
             
              unset($this->session->data['search_time_start']);
              unset($this->session->data['search_time_to']);
             
              unset($this->session->data['keyword']);
              unset($this->session->data['sms_user_id']);
              unset($this->session->data['search_user_id']);
              unset($this->session->data['search_emp_tag_id']);
              unset($this->session->data['notesdatas']);
              unset($this->session->data['advance_search']);
              unset($this->session->data['update_reminder']);
              unset($this->session->data['pagenumber']);
              unset($this->session->data['pagenumber_all']);
              unset($this->session->data['activationkey']);
              unset($this->session->data['username']);
              unset($this->session->data['session_key']);
              unset($this->session->data['unloack_success']);
              unset($this->session->data['ssincedentform']);
              unset($this->session->data['ssbedcheckform']);
              unset($this->session->data['form_search']);
              unset($this->session->data['highlighter']);
              unset($this->session->data['activenote']);
              unset($this->session->data['isPrivate']);
              unset($this->session->data['review_user_id']);
             
              unset($this->session->data['formreturn_id']);
              unset($this->session->data['design_forms']);
              unset($this->session->data['formsids']);
              unset($this->session->data['session_notes_description']);
              unset($this->session->data['tasktype']);
              unset($this->session->data['webuser_id']);
            
             $this->redirect($this->url->link('common/login', '', 'SSL'));
            }else{
				$this->load->model('facilities/facilities');
				$this->load->model('licence/licence');
            
				$data = array();
				$data['activationkey'] = $this->session->data['activationkey'];
				$data['ip'] = $this->request->server['REMOTE_ADDR'];
            
				//$ipresults = $this->model_facilities_facilities->resetFacilityLogin($data);
            
            }*/
        
        unset($this->session->data['show_hidden_info']);
		
        $this->language->load('notes/notes');
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->document->setTitle('Clients');
		
		if ($this->request->get['search_facilities_id'] > 0 ) {
			$this->session->data['search_facilities_id'] = $this->request->get['search_facilities_id'];
			$this->redirect($this->url->link('resident/resident', '' . $url2, 'SSL'));
		 }
		 if ($this->request->get['searchall'] == '1' ) {
			unset($this->session->data['search_facilities_id']);
			$this->redirect($this->url->link('resident/resident', '' . $url2, 'SSL'));
		 }
        
        $this->data['facilityname'] = $this->customer->getfacility();
        
        $this->load->model('facilities/facilities');
        $facilities_info = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        
        if ($facilities_info['is_discharge_form_enable'] == '1') {
            $this->data['dis_form'] = '1';
        } else {
            $this->data['dis_form'] = '2';
        }
        
        if ($this->request->get['gender'] != null && $this->request->get['gender'] != "") {
            $this->data['add_role_call_check'] = '1';
        }
        $this->data['add_role_call'] = $this->request->get['add_role_call'];
        
        if (($this->request->get['searchtag'] == '1')) {
            $url = "";
            if ($this->request->post['search_tags'] != null && $this->request->post['search_tags'] != "") {
                $url .= '&search_tags=' . $this->request->post['search_tags'];
            }
            if ($this->request->post['wait_list'] != null && $this->request->post['wait_list'] != "") {
                $url .= '&wait_list=' . $this->request->post['wait_list'];
            }
            if ($this->request->post['search_tags_tag_id'] != null && $this->request->post['search_tags_tag_id'] != "") {
                $url .= '&search_tags_tag_id=' . $this->request->post['search_tags_tag_id'];
            }
			
            
            $this->redirect($this->url->link('resident/resident', '' . $url, 'SSL'));
        }
        
        if (($this->request->get['searchtag'] == '2')) {
            $url = "";
            if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
                $url .= '&search_tags=' . $this->request->get['search_tags'];
            }
            if ($this->request->get['wait_list'] != null && $this->request->get['wait_list'] != "") {
                $url .= '&wait_list=' . $this->request->get['wait_list'];
            }
            if ($this->request->get['search_tags_tag_id'] != null && $this->request->get['search_tags_tag_id'] != "") {
                $url .= '&search_tags_tag_id=' . $this->request->get['search_tags_tag_id'];
            }
			
			
            $this->redirect($this->url->link('resident/resident', '' . $url, 'SSL'));
        }
        
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            $this->data['search_tags'] = $this->request->get['search_tags'];
        }
        if ($this->request->get['wait_list'] != null && $this->request->get['wait_list'] != "") {
            $this->data['wait_list'] = $this->request->get['wait_list'];
        }
        if ($this->request->get['search_tags_tag_id'] != null && $this->request->get['search_tags_tag_id'] != "") {
            $this->data['search_tags_tag_id'] = $this->request->get['search_tags_tag_id'];
            $search_tags = '';
        } else {
            $search_tags = $this->request->get['search_tags'];
        }
		    

        
       //// $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
       // $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');

        $this->data['total_in_url'] = $this->url->link('resident/resident&role_call=1', '' . $url1, 'SSL');
		$this->data['total_out_url'] = $this->url->link('resident/resident&role_call=2', '' . $url1, 'SSL');
        $this->data['non_url'] = $this->url->link('resident/resident&gender=3', '' . $url1, 'SSL');
		
        $this->data['total_url'] = $this->url->link('resident/resident', '', 'SSL');
        
        $this->data['notes_url'] = $this->url->link('notes/notes/insert', '', 'SSL');
        
        $this->data['sticky_note'] = $this->url->link('resident/resident/getstickynote&close=1', '', 'SSL');
        
        $this->data['dailycensus'] = $this->url->link('resident/dailycensus', '', 'SSL');
		
        $this->data['clientfile'] = $this->url->link('resident/resident/clientfile', '', 'SSL');
		
        $this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
        
        $this->data['task_lists'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/headertasklist', '' . $url1, 'SSL'));
        
        $this->data['task_lists2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatus', '' . $url1, 'SSL'));
        
        $this->data['case_url'] = str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard', '', 'SSL'));
        
        $this->data['add_client_url1'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '', 'SSL'));
        // $this->data['add_client_url3'] = str_replace('&amp;',
        // '&',$this->url->link('form/form', '' .
        // '&forms_design_id='.CUSTOME_INTAKEID, 'SSL'));
        
        $this->data['assignteam'] = str_replace('&amp;', '&', $this->url->link('resident/assignteam', '', 'SSL'));
        
        $this->load->model('setting/tags');
        $this->load->model('setting/image');
		
		$this->load->model('notes/clientstatus');
		
		$ddss = array();
		if($facilities_info['client_facilities_ids'] != null && $facilities_info['client_facilities_ids'] != ""){
			$this->data['is_master_facility']  =  '1' ; 
			$ddss[] = $facilities_info['client_facilities_ids'];
			
			$ddss[] = $this->customer->getId();
			$sssssdd = implode(",",$ddss);
		
		}else{
			$this->data['is_master_facility']  =  '2' ; 
		}
        
		
		if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
			$facilities_id = $this->session->data['search_facilities_id'];
		}else{
			$facilities_id = $this->customer->getId();
		}
		
		 // var_dump($config_admin_limit);
        $facilities_is_master = $this->model_facilities_facilities->getfacilities($facilities_id);    

		
		if($facilities_is_master['is_master_facility'] == 0){
			$is_master_facility = 1;
		}else{
			$is_master_facility = $facilities_is_master['is_master_facility'];
		}
		
        $data3 = array();
        $data3 = array(
                'status' => 1,
                'discharge' => 1,
                'role_call' => '1',
				'is_master'=>$is_master_facility,
                // 'gender2' => $this->request->get['gender'],
                'sort' => 'emp_last_name',
                'facilities_id' => $facilities_id,
                // 'emp_tag_id_2' => $this->request->get['search_tags'],
                'wait_list' => $this->request->get['wait_list'],
                'all_record' => '1'
        );
        
        $this->data['tags_total'] = $this->model_setting_tags->getTotalTags($data3);

        $data31333 = array();
        $data31333 = array(
                'status' => 1,
                'discharge' => 1,
                // 'role_call' => '1',
                'gender2' => $this->request->get['gender'],
                'sort' => 'emp_last_name',
                'facilities_id' => $facilities_id,
				'is_master'=>$is_master_facility,
                'emp_tag_id_2' => $search_tags,
                'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
                'wait_list' => $this->request->get['wait_list'],
                'all_record' => '1',
                'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
        );
        
        $tags_total_2 = $this->model_setting_tags->getTotalTags($data31333);
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $config_admin_limit1 = $this->config->get('config_front_limit');
        if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
            $config_admin_limit = $config_admin_limit1;
        } else {
            $config_admin_limit = "50";
        }
        
       
        
        $data31 = array();

        $rolecall="";

        if($this->request->get['role_call']!=""||$this->request->get['role_call']!=null){

          $rolecall=$this->request->get['role_call'];

        }else{

           $rolecall='';

        }


       //// var_dump($this->request->get);
       // die;

        
        if ($this->request->get['add_role_call'] == '1') {
            $data31 = array(
                    'status' => 1,
                    'discharge' => 1,
                    'role_call' => $rolecall,
                    'gender2' => $this->request->get['gender'],
                    'sort' => 'emp_last_name',
                    'is_master'=>$is_master_facility,
                    'facilities_id' => $facilities_id,
                    'emp_tag_id_2' => $search_tags,
                    'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
                    'wait_list' => $this->request->get['wait_list'],
                    'all_record' => '1'
            );
        } else {
            $data31 = array(
                    'status' => 1,
                    'discharge' => 1,
                    'role_call' =>$rolecall,
                    'is_master'=> $is_master_facility,
                    'gender2' => $this->request->get['gender'],
                    'sort' => 'emp_last_name',
                    'facilities_id' => $facilities_id,
                    'emp_tag_id_2' => $search_tags,
                    'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],
                    'wait_list' => $this->request->get['wait_list'],
                    'all_record' => '1',
                    'start' => ($page - 1) * $config_admin_limit,
                    'limit' => $config_admin_limit
            );
        }

       //// var_dump($data31);
       // die;
	   
	    $this->load->model ( 'facilities/facilities' );

        $facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
        
        $unique_id = $facility ['customer_key'];

       // var_dump($unique_id); die;
        
        $this->load->model ( 'customer/customer' );
        
        $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
        
       
        $client_info = unserialize($customer_info['client_info_notes']);

        $client_view_options2 = $client_info["client_view_options"]; 

		//echo '<pre>'; print_r($client_info); echo '</pre>';  die;

		// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
        $this->data['show_client_image'] = $client_info["show_client_image"];
        $this->data['show_form_tag'] = $client_info["show_form_tag"];
        $this->data['show_task'] = $client_info["show_task"];
        $this->data['show_case'] = $client_info["show_case"];
        

        $tags = $this->model_setting_tags->getTags($data31);

        $this->load->model('resident/resident');        
        $this->load->model('createtask/createtask');
        $this->load->model('notes/notes');
        $this->load->model('form/form');
		$this->load->model('setting/locations');
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $currentdate = date('d-m-Y');
		
		$this->load->model('facilities/facilities');	
        
        foreach ($tags as $tag) 
		{
            $result_info =  $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
            $allform_info = $this->model_form_form->gettagsforma($tag['tags_id']);
            
            if ($allform_info != null && $allform_info != "") {
                $screenig_url = $this->url->link('form/form', '' . '&tags_forms_id=' . $allform_info['tags_forms_id'] . '&tags_id=' . $allform_info['tags_id'] . '&notes_id=' . $allform_info['notes_id'] . '&forms_design_id=' . $allform_info['custom_form_type'] . '&forms_id=' . $allform_info['forms_id'], 'SSL');
            } else {
                $screenig_url = '';
            }
            
            /*
             * $alltagcolors =
             * $this->model_resident_resident->getagsColors($tag['tags_id']);
             * $tagcolors = array();
             * foreach ($alltagcolors as $alltagcolor) {
             *
             * $tagcolors[] = array(
             * 'color_id' => $alltagcolor['color_id'],
             * 'text_highliter_div_cl' => $alltagcolor['text_highliter_div_cl'],
             * );
             * }
             * $role_call = array();
             */
             /*
             * if (isset($this->request->post['role_call'])) {
             * $role_call[] = $this->request->post['role_call'];
             * } elseif($tag['role_call']) {
             * $role_call[] = $tag['role_call'];
             * }else{
             * $role_call[] = array();
             * }
             */
            
            if ($tag['role_call']) {
                $role_call[] = $tag['role_call'];
            } else {
                $role_call[] = array();
            }
            
            // var_dump($role_call);
            
            $tasksinfo = $this->model_createtask_createtask->getTaskas($tag['tags_id'], $currentdate);
            
            $tasksinfo1 = $tasksinfo * 100;
            
            // var_dump($tasksinfo1);
            
            if ($tag['privacy'] == '2') {
                $upload_file_thumb_1 = '';
                $enroll_image = '';
                $emp_last_name = mb_substr($tag['emp_last_name'], 0, 1);
            } else {
				
				$get_img = $this->model_setting_tags->getImage($tag['tags_id']);			
				
				
                if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                    $upload_file_thumb_1 = $get_img['upload_file_thumb'];
                } else {
                    $upload_file_thumb_1 = $get_img['enroll_image'];
                }
                
                $emp_last_name = $tag['emp_last_name'];
                
                $check_img = $this->model_setting_image->checkresize($get_img['enroll_image']);
				
				     $enroll_image = $get_img['enroll_image'];
            }
            
            $addTime = $this->config->get('config_task_complete');
            
            // $this->data['deleteTime'] = $deleteTime;
            
            $top = '1';
            
            $tasktypes = $this->model_createtask_createtask->getTaskdetails($this->customer->getId());
            
            foreach ($tasktypes as $tasktype) {
                $taskTotal1 = 0;
                $taskTotal = 0;
                
                $taskTotal1 = $this->model_createtask_createtask->getCountTasklist($this->customer->getId(), $currentdate, $top, '', $tag['tags_id'],'');
                
                // var_dump($taskTotal1 );
                
                $taskTotal = $taskTotal + $taskTotal1;
            }
            
            // var_dump($taskTotal);
            
            $d = array();
            $d['emp_tag_id'] = $tag['tags_id'];
            $d['searchdate'] = $currentdate;
            $d['start'] = 0;
            $d['limit'] = 1;
            $d['advance_search'] = 1;
            $d['advance_date_desc'] = 1;
            $d['facilities_id'] = $this->customer->getId();
		      	$d['customer_key'] = $this->session->data['webcustomer_key'];
            
            // $lastnotesinfo = $this->model_notes_notes->getnotess($d);
            
            // var_dump($lastnotesinfo[0]['notes_description']);
            // echo "<hr>";
            
            $recenttasksinfos = $this->model_createtask_createtask->getrecentTaskdetails($d);
            
            $form_info = $this->model_form_form->gettagsformav($tag['tags_id']);
            if ($form_info) {
                $ndate_added = date('D F j, Y', strtotime($form_info['date_added'] . ' +90 day'));
            } else {
                $ndate_added = '';
            }
            
            $client_medicine = $this->model_resident_resident->gettagModule($tag['tags_id'],'','');
            
            // $client_status =
            // $this->model_resident_resident->gettagstatsus($tag['tags_id']);
            
            $tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($tag['tags_id']);
            
            if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
                
                $status = $tagstatusinfo['status'];
            } else {
                $status = '';
            }

            $emp_first_name = $tag['emp_first_name'];

            $this->load->model('setting/locations');       
        
            $rresults = $this->model_setting_locations->getlocation($tag['room']);

            $role_callname = "";
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus($tag['role_call']);
			if($clientstatus_info['name'] != null && $clientstatus_info['name'] != ""){
				$role_callname = $clientstatus_info['name'];
			}
			
			 $client_view_options = $client_view_options2;

			 if(isset($tag['emp_first_name']) && $tag['emp_first_name']!=''){
				$client_view_options = str_replace('[emp_first_name]', $tag['emp_first_name'], $client_view_options); 
			  }else{
				$client_view_options = str_replace('[emp_first_name]', '', $client_view_options); 
			  }


			  if(isset($tag['emp_middle_name']) && $tag['emp_middle_name']!=''){
				$client_view_options = str_replace('[emp_middle_name]', $tag['emp_middle_name'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[emp_middle_name]', '', $client_view_options);
			  } 

			  if(isset($tag['emp_last_name']) && $tag['emp_last_name']!=''){
				$client_view_options = str_replace('[emp_last_name]', $tag['emp_last_name'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[emp_last_name]', '', $client_view_options);
			  } 

			  if(isset($tag['emergency_contact']) && $tag['emergency_contact']!=''){
				$client_view_options = str_replace('[emergency_contact]', $tag['emergency_contact'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[emergency_contact]', '', $client_view_options);
			  } 

			  if(isset($tag['facilities_id']) && $tag['facilities_id']!=''){
				  $result_info = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
				$client_view_options = str_replace('[facilities_id]', $result_info['facility'], $client_view_options); 
			  } else{
				$client_view_options = str_replace('[facilities_id]', '', $client_view_options); 
			  } 

			  if(isset($tag['room']) && $tag['room']!=''){
				  
				  $rresults = $this->model_setting_locations->getlocation($tag['room']);
				$client_view_options = str_replace('[room]', $rresults['location_name'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[room]', '', $client_view_options);
			  } 

			  if(isset($tag['dob']) && $tag['dob']!=''){
				$client_view_options = str_replace('[dob]', $tag['dob'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[dob]', '', $client_view_options);
			  }
			  
			  if(isset($tag['gender']) && $tag['gender']!=''){  
				$client_view_options = str_replace('[gender]', $tag['gender'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[gender]', '', $client_view_options);
			  }
			   
			  if(isset($tag['age']) && $tag['age']!=''){  
				$client_view_options = str_replace('[age]', $tag['age'], $client_view_options); 
			  } else{
				$client_view_options = str_replace('[age]', '', $client_view_options); 
			  }
				
			  if(isset($tag['ssn']) && $tag['ssn']!=NULL){  
				$client_view_options = str_replace('[ssn]', $tag['ssn'], $client_view_options);
			  }else{
				$client_view_options = str_replace('[ssn]', '', $client_view_options);
			  } 
			  
			  if(isset($tag['emp_tag_id']) && $tag['emp_tag_id']!=''){
				$client_view_options = str_replace('[emp_tag_id]', $tag['emp_tag_id'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[emp_tag_id]', '', $client_view_options);
			  }

			  if(isset($tag['emp_extid']) && $tag['emp_extid']!=''){
				$client_view_options = str_replace('[emp_extid]', $tag['emp_extid'], $client_view_options);
			  } else{
				$client_view_options = str_replace('[emp_extid]', '', $client_view_options);
			  }
			  
			 
			  if($client_view_options != "" && $client_view_options != null){
				  $client_view_options_flag = 1;
				}else{
				  $client_view_options_flag = 0;
				}
				
				
				
            $this->data['tags'][] = array(
                    'name' => $tag['emp_first_name'] . ' ' . $emp_last_name,
					'facility' => $result_info['facility'],
					
					'name2' => nl2br($client_view_options),
                    'client_view_flag'=> $client_view_options_flag,
					
                    'facilities_id' => $result_info['facilities_id'],
                    'emp_first_name' => $tag['emp_first_name'],
                    'medication_inout' => $tag['medication_inout'],
                    'room' => $rresults['location_name'],
                    'emp_extid'=>$tag['emp_extid'],
                    'ssn'=>$tag['ssn'],
                    'location_address'=>$tag['location_address'],
                    'first_initial'=> $emp_first_name[0],
                    'emp_last_name' => $tag['emp_last_name'],
                    'emp_tag_id' => $tag['emp_tag_id'],
                    'tags_id' => $tag['tags_id'],
                    'age' => $tag['age'],
					'date_added' => date('m-d-Y',strtotime($tag['date_added'])),
                    'gender' => $tag['gender'],
                    'upload_file' => $enroll_image,
                    'upload_file_thumb' => $get_img['upload_file_thumb'],
                    'upload_file_thumb_1' => $upload_file_thumb_1,
                    'check_img' => $check_img,
                    'privacy' => $tag['privacy'],
                    'role_call' => $tag['role_call'],
					'role_callname' => $role_callname,
                    'stickynote' => $tag['stickynote'],
                   // 'role_call' => $role_call,
                    'tagallforms' => $forms,
                    'tagcolors' => $tagcolors,
                    'tasksinfo' => $tasksinfo1,
                    'taskTotal' => $taskTotal,
                    'recentnote' => $lastnotesinfo[0]['notes_description'],
                    'recenttasks' => $recenttasksinfos['description'],
                    'ndate_added' => $ndate_added,
                    'client_medicine' => $client_medicine,
                    'tagstatus_info' => $status,
                    'screenig_url' => $screenig_url,
                    'tag_href' => $this->url->link('resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag['tags_id']. $url122, 'SSL'),
                    'assignteam_href' => $this->url->link('resident/assignteam', '' . '&tags_id=' . $tag['tags_id']. $url122, 'SSL'),
                    'discharge_href' => $this->url->link('notes/case', '' . '&tags_id=' . $tag['tags_id'] . $url122, 'SSL')
            );
        }

        
       
        
        // var_dump($this->data['tags']);
      
        
        $this->load->model('form/form');
        $data3 = array();
        $data3['status'] = '1';
        // $data3['order'] = 'sort_order';
        $data3['is_parent'] = '1';
        $data3['facilities_id'] = $facilities_id;
        $custom_forms = $this->model_form_form->getforms($data3);
        
        $this->data['custom_forms'] = array();
        foreach ($custom_forms as $custom_form) {
            
            $this->data['custom_forms'][] = array(
                    'forms_id' => $custom_form['forms_id'],
                    'form_name' => $custom_form['form_name'],
                    // 'form_href' =>
                    // $this->url->link('resident/resident/tagform', '' .
                    // '&forms_design_id='.$custom_form['forms_id'], 'SSL'),
                    'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
            );
        }
		
		
		
		$this->load->model('notes/clientstatus');
        $data3 = array();
        $data3['facilities_id'] = $facilities_id;
        $customforms = $this->model_notes_clientstatus->getclientstatuss($data3);
        
        $this->data['clientstatuss'] = array();
        foreach ($customforms as $customform) {
            
            $this->data['clientstatuss'][] = array(
				'tag_status_id' => $customform['tag_status_id'],
				'name' => $customform['name'],
				'facilities_id' => $customform['facilities_id'],
				'display_client' => $customform['display_client'],
				'image' => $customform['image'],
				'type' => $customform['type'],
            );
        }
       
        
        if (isset($this->session->data['success_add_form'])) {
            $this->data['success_add_form'] = $this->session->data['success_add_form'];
            
            unset($this->session->data['success_add_form']);
        } else {
            $this->data['success_add_form'] = '';
        }
        
        $this->data['close'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        $this->data['tag_forms'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagforms', '' . $url2, 'SSL'));
        
		$this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '' . '&addclient=1', 'SSL'));
        //$this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . '&addclient=1&forms_design_id=' . CUSTOME_I_INTAKEID, 'SSL'));
		
		//var_dump($this->data['add_client_url']);
        
        $this->data['add_tag_medication_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '', 'SSL'));
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '', 'SSL'));
        
        $this->data['activenote_url'] = $this->url->link('resident/resident/activenote', '', 'SSL');
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        
        if (($this->request->post['all_roll_call'] == '1')) {
            
            $url2 = "";
            if ($this->request->post['all_roll_call'] != null && $this->request->post['all_roll_call'] != "") {
                $url2 .= '&all_roll_call=' . $this->request->post['all_roll_call'];
            }
            
            $this->session->data['role_calls'] = $this->request->post['role_call'];
            
            $this->session->data['success2'] = 'Head Ciount updated Successfully! ';
            
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&allrolecallsign=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allrolecallsign', '' . $url2, 'SSL'));
            }
        }
        
        if (($this->request->post['all_roll_call1'] == '1')) {
            
            $url2 = "";
            
            if ($this->request->post['all_roll_call1'] != null && $this->request->post['all_roll_call1'] != "") {
                $url2 .= '&all_roll_call1=' . $this->request->post['all_roll_call1'];
            }
		
            
            $this->session->data['tagsids'] = $this->request->post['tagsids'];
            $this->session->data['role_calls'] = $this->request->post['role_call'];
            
            $this->session->data['success2'] = 'Head Count updated Successfully! ';
            
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&allrolecallsign=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allrolecallsign', '' . $url2, 'SSL'));
            }
        }
        
        if (isset($this->request->post['all_roll_call'])) {
            $this->data['all_roll_call'] = $this->request->post['all_roll_call'];
        } else {
            $this->data['all_roll_call'] = '';
        }
        
        if (isset($this->session->data['success2'])) {
            $this->data['success2'] = $this->session->data['success2'];
            
            unset($this->session->data['success2']);
        } else {
            $this->data['success2'] = '';
        }
        
        $url = "";
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            $url .= '&search_tags=' . $this->request->get['search_tags'];
        }
        if ($this->request->get['wait_list'] != null && $this->request->get['wait_list'] != "") {
            $url .= '&wait_list=' . $this->request->get['wait_list'];
        }
        
        if ($this->request->get['wait_list'] != null && $this->request->get['wait_list'] != "") {
            $url .= '&wait_list=' . $this->request->get['wait_list'];
        }
        if ($this->request->get['search_tags_tag_id'] != null && $this->request->get['search_tags_tag_id'] != "") {
            $url .= '&search_tags_tag_id=' . $this->request->get['search_tags_tag_id'];
        }
        
        if ($this->request->get['gender'] != null && $this->request->get['gender'] != "") {
            $url .= '&gender=' . $this->request->get['gender'];
        }
        
        if ($this->request->get['add_role_call'] != null && $this->request->get['add_role_call'] != "") {
            $url .= '&add_role_call=' . $this->request->get['add_role_call'];
        }
		
        
        $this->data['tags_total_2'] = $tags_total_2;
        // var_dump($url);
        
        // var_dump($tags_total_2);
        if ($this->request->get['add_role_call'] != '1') {
            $pagination = new Pagination();
            $pagination->total = count($tags);
            $pagination->page = $page;
            $pagination->limit = $config_admin_limit;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('resident/resident', 'page={page}' . $url, 'SSL');
            
            $this->data['pagination'] = $pagination->render();
        }
        
        $this->template = $this->config->get('config_template') . '/template/resident/resident.php';
        $this->children = array(
                'common/headerclient',
                'common/footerclient'
        );
        $this->response->setOutput($this->render());
    }

    public function updateclient ()
    {   


         $facilities_id=""; 

        if($this->request->get['facilities_id']!=null && $this->request->get['facilities_id']!=""){

        if($this->request->get['facilities_id']==$this->customer->getId()){

          $facilities_id=$this->customer->getId();

        }else{

           $facilities_id=$this->request->get['facilities_id'];

        }
      
        }   

        

        $json = array();
        $this->load->model('resident/resident');
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $facilities_id;
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if ($this->request->get['tags_id'] != "" && $this->request->get['tags_id'] != null) {
            if ($this->request->get['discharge'] == '1') {
                // $this->model_resident_resident->updateDischargeTag($this->request->get['tags_id']);
                
                $url2 = "";
                
                if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                    $url2 .= '&tags_id=' . $this->request->get['tags_id'];
                }
                if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
                    $url2 .= '&discharge=' . $this->request->get['discharge'];
                }

                 if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                    $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
                }
               // $url2.='&facilities_id='. $facilities_id;
                
                $this->load->model('facilities/facilities');
                $facilities_info = $this->model_facilities_facilities->getfacilities($datafa['facilities_id']);
                
                if ($facilities_info['is_discharge_form_enable'] == '1') {
                    $url2 .= '&forms_design_id=' . $facilities_info['discharge_form_id'];
                    $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
                } else {
                    
                    if ($facilities_info['is_enable_add_notes_by'] == '1' || $facilities_info['is_enable_add_notes_by'] == '3') {
                        $url2 .= '&rolecallsign=1';
                        $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
                    } else {
                        $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/rolecallsign', '' . $url2, 'SSL'));
                    }
                }
                
                $json['success'] = '1';
            }
            
            if ($this->request->get['viewnotes'] == '1') {
                $this->load->model('setting/tags');
                $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
                
                $this->session->data['keyword'] = $tag_info['emp_first_name'];
                // $this->session->data['search_emp_tag_id'] =
                // $this->request->get['tags_id'];
                $this->session->data['advance_search'] = '1';
                
                $this->session->data['group'] = '1';
                
                $json['success'] = '1';
            }
            
            if ($this->request->get['highliter'] == '1') {
                $this->model_resident_resident->updatetagcolor($this->request->get['tags_id'], $this->request->get['highliter_id'], $this->request->get['text_highliter_div_cl']);
                $json['success'] = '1';
            }




            
            if ($this->request->get['rolecall2'] == '1') {
                
                $url2 = "";                    

                if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
                    $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
                }
                if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                    $url2 .= '&tags_id=' . $this->request->get['tags_id'];
                }
                if ($this->request->get['role_call'] != null && $this->request->get['role_call'] != "") {
                    $url2 .= '&role_call=' . $this->request->get['role_call'];
                }

                if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                    $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
                }

                   // $url2.='&facilities_id='.$this->request->get['facilities_id'];

                   // var_dump($facilities_info);
                   // die;
                
                if ($facilities_info['is_enable_add_notes_by'] == '1' || $facilities_info['is_enable_add_notes_by'] == '3') {
                    $url2 .= '&rolecallsign=1';
                    $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
                } else {
                    $json['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/rolecallsign', '' . $url2, 'SSL'));
                }
                
                $json['success'] = '1';
            }
        }


       // var_dump($url2);
        //die;
        
        $this->response->setOutput(json_encode($json));


    }

    public function tagforms ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->language->load('notes/notes');
        $this->load->model('setting/tags');
        $this->load->model('form/form');
        
        $this->load->model('notes/notes');
        
        $tags_id = $this->request->get['tags_id'];
         $this->data['facilities_id'] = $this->request->get['facilities_id'];
        $this->data['tags_id'] = $this->request->get['tags_id'];
        
        $this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '', 'SSL'));
        $this->data['add_tag_medication_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '', 'SSL'));
        
        $tag_info = $this->model_setting_tags->getTag($tags_id);
        
        $this->data['name'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        
        $d1 = array();
        $d1['tags_id'] = $tags_id;
        $d1['form_type'] = '2';
        $client_info_sign = $this->model_notes_notes->getNoteform($d1);
        // var_dump($client_info_sign);
        
        $this->data['client_user_id'] = $client_info_sign['user_id'];
        $this->data['client_signature'] = $client_info_sign['signature'];
        $this->data['client_notes_pin'] = $client_info_sign['notes_pin'];
        $this->data['client_notes_type'] = $client_info_sign['notes_type'];
        
        if ($client_info_sign['note_date'] != null && $client_info_sign['note_date'] != "0000-00-00 00:00:00") {
            $this->data['client_form_date_added'] = date($this->language->get('date_format_short_2'), strtotime($client_info_sign['note_date']));
        } else {
            $this->data['client_form_date_added'] = '';
        }
        
        $d12 = array();
        $d12['tags_id'] = $tags_id;
        $d12['form_type'] = '1';
        $healthforn_info_sign = $this->model_notes_notes->getNoteform($d12);
        
        $this->data['health_user_id'] = $healthforn_info_sign['user_id'];
        $this->data['health_signature'] = $healthforn_info_sign['signature'];
        $this->data['health_notes_pin'] = $healthforn_info_sign['notes_pin'];
        $this->data['health_notes_type'] = $healthforn_info_sign['notes_type'];
        
        if ($healthforn_info_sign['note_date'] != null && $healthforn_info_sign['note_date'] != "0000-00-00 00:00:00") {
            $this->data['health_form_date_added'] = date($this->language->get('date_format_short_2'), strtotime($healthforn_info_sign['note_date']));
        } else {
            $this->data['health_form_date_added'] = '';
        }
        
        $this->load->model('resident/resident');
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $config_admin_limit1 = $this->config->get('config_front_limit');
        if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
            $config_admin_limit = $config_admin_limit1;
        } else {
            $config_admin_limit = "50";
        }
        
        $data = array(
                'sort' => $sort,
                'order' => $order,
                'group' => '1',
        		'group' => '1',
                'tags_id' => $tags_id,
                'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
        );
        
        $form_total = $this->model_form_form->getTotalforms2($data);
        
        $allforms = $this->model_form_form->gettagsforms($data);
        $this->data['tagsforms'] = array();
        
        foreach ($allforms as $allform) {
            
            $form_info = $this->model_form_form->getFormdata($allform['custom_form_type']);
            
            if ($allform['user_id'] != null && $allform['user_id'] != "") {
                $user_id = $allform['user_id'];
                $signature = $allform['signature'];
                $notes_pin = $allform['notes_pin'];
                $notes_type = $allform['notes_type'];
                
                if ($allform['form_date_added'] != null && $allform['form_date_added'] != "0000-00-00 00:00:00") {
                    $form_date_added = date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']));
                } else {
                    $form_date_added = '';
                }
            } else {
                
                $note_info = $this->model_notes_notes->getNote($allform['notes_id']);
                
                // var_dump($note_info);
                $user_id = $note_info['user_id'];
                $signature = $note_info['signature'];
                $notes_pin = $note_info['notes_pin'];
                $notes_type = $note_info['notes_type'];
                
                if ($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00") {
                    $form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
                } else {
                    $form_date_added = '';
                }
            }
            
            $this->data['tagsforms'][] = array(
                    'forms_id' => $allform['forms_id'],
                    'form_name' => $form_info['form_name'],
                    'notes_type' => $notes_type,
                    'user_id' => $user_id,
                    'signature' => $signature,
                    'notes_pin' => $notes_pin,
                    'form_date_added' => $form_date_added,
                    'date_added2' => date('D F j, Y', strtotime($allform['date_added'])),
                    'form_href' => $this->url->link('form/form', '' . '&forms_id=' . $allform['forms_id'] . '&tags_id=' . $allform['tags_id'] . '&notes_id=' . $allform['notes_id'] . '&forms_design_id=' . $allform['custom_form_type'] . '&forms_id=' . $allform['forms_id'], 'SSL')
            );
        }
        // var_dump($this->data['tagsforms']);
        
        $data2 = array(
        		'sort' => $sort,
        		'order' => $order,
        		'group' => '1',
        		'archivedform' => '1',
        		'tags_id' => $tags_id,
        		
        );
        $aallforms = $this->model_form_form->gettagsforms($data2);
        $this->data['atagsforms'] = array();
        
        foreach ($aallforms as $aallform) {
        
        	$form_info = $this->model_form_form->getFormdata($aallform['custom_form_type']);
        
        	if ($aallform['user_id'] != null && $aallform['user_id'] != "") {
        		$user_id = $aallform['user_id'];
        		$signature = $aallform['signature'];
        		$notes_pin = $aallform['notes_pin'];
        		$notes_type = $aallform['notes_type'];
        
        		if ($aallform['form_date_added'] != null && $aallform['form_date_added'] != "0000-00-00 00:00:00") {
        			$form_date_added = date($this->language->get('date_format_short_2'), strtotime($aallform['form_date_added']));
        		} else {
        			$form_date_added = '';
        		}
        	} else {
        
        		$note_info = $this->model_notes_notes->getNote($aallform['notes_id']);
        
        		// var_dump($note_info);
        		$user_id = $note_info['user_id'];
        		$signature = $note_info['signature'];
        		$notes_pin = $note_info['notes_pin'];
        		$notes_type = $note_info['notes_type'];
        
        		if ($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00") {
        			$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
        		} else {
        			$form_date_added = '';
        		}
        	}
        	
        	
        	$this->data['atagsforms'][] = array(
        			'forms_id' => $aallform['forms_id'],
        			'form_name' => $form_info['form_name'],
        			'notes_type' => $notes_type,
        			'user_id' => $user_id,
        			'signature' => $signature,
        			'notes_pin' => $notes_pin,
        			'form_date_added' => $form_date_added,
        			'date_added2' => date('D F j, Y', strtotime($aallform['date_added'])),
        			'form_href' => $this->url->link('form/form&is_archive=4', '' . '&forms_id=' . $aallform['forms_id'] . '&tags_id=' . $aallform['tags_id'] . '&notes_id=' . $aallform['notes_id'] . '&forms_design_id=' . $aallform['custom_form_type'] . '&forms_id=' . $aallform['forms_id'], 'SSL')
        	);
        }
        
        
        if ($tags_id != "" && $tags_id != NULL) {
            $url = '&tags_id=' . $tags_id;
        }
        
        $pagination = new Pagination();
        $pagination->total = $form_total;
        $pagination->page = $page;
        $pagination->limit = $config_admin_limit;
        
        $pagination->text = ''; // $this->language->get('text_pagination');
        $pagination->url = $this->url->link('resident/resident/tagforms', '' . $url . '&page={page}', 'SSL');
        
        $this->data['pagination'] = $pagination->render();
        
        $this->data['back_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        $this->template = $this->config->get('config_template') . '/template/resident/tags_form.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    public function tagsmedication ()
    {


        //var_dump($this->request->get);
        //die;
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->language->load('notes/notes');
        $this->load->model('setting/tags');
        $this->load->model('form/form');
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $this->data['current_time'] = date('h:i A');
        
        if ($this->request->get['tags_id']) {
            $tags_id = $this->request->get['tags_id'];
        } elseif ($this->request->post['emp_tag_id']) {
            $tags_id = $this->request->post['emp_tag_id'];
        }
        
        $this->data['tags_id'] = $this->request->get['tags_id'];
         $this->data['facilities_id'] = $this->request->get['facilities_id'];
        
        $tag_info = $this->model_setting_tags->getTag($tags_id);
        
        if ($tags_id) {
            $this->data['name'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        }
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $this->load->model('notes/notes');
            $notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
            
            $this->data['note_date_added'] = date('m-d-Y h:i A', strtotime($notes_info['date_added']));
        }
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->request->get['facilities_id'] );
		
		if ($resulsst ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $this->request->get['facilities_id'];
				$timezone_name = $this->customer->isTimezone ();
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
		}
        
        $this->load->model('createtask/createtask');
        $this->data['taskintervals'] = $this->model_createtask_createtask->getTaskintervals($facilities_id);
        
        $this->load->model('resident/resident');


       //// var_dump($this->request->get['facilities_id']);
       // die;
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {


        //// var_dump($this->request->get);
        // die;
            
			
			$this->load->model('api/temporary');
			$tdata = array();
			$tdata['id'] = $tags_id;
			$tdata['facilities_id'] = $facilities_id;
			$tdata['type'] = 'updatehealthform';
			$archive_tags_medication_id = $this->model_api_temporary->addtemporary($this->request->post, $tdata);
      
      $url2 = "";
            
            if (! empty($this->request->post['medication'])) {
                // $this->session->data['medication'] =
                // $this->request->post['medication'];
                
                $medication_tags = implode(',', $this->request->post['medication']);
                
                if ($medication_tags != null && $medication_tags != "") {
                    $url2 .= '&medication_tags=' . $medication_tags;
                }
                
                $this->session->data['success2'] = 'Medication added successfully!';
            } else {
                $this->session->data['success_add_form'] = 'Medication added successfully!';
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }

            if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            }
            if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->post['emp_tag_id'];
            }
            
            $url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
            
            $this->redirect($this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL'));
        }
        
        $url2 = "";
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
         if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
        }
        
        if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->post['emp_tag_id'];
        }
        if ($this->request->get['medication_tags'] != null && $this->request->get['medication_tags'] != "") {
            $url2 .= '&medication_tags=' . $this->request->get['medication_tags'];
        }
        if ($this->request->get['archive_tags_medication_id'] != null && $this->request->get['archive_tags_medication_id'] != "") {
            $url2 .= '&archive_tags_medication_id=' . $this->request->get['archive_tags_medication_id'];
        }
        if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
            $url2 .= '&is_archive=' . $this->request->get['is_archive'];
        }
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        
        if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
            
            $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&tagmedicine=1', '' . $url2, 'SSL'));
            $this->data['updateredirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization&tagmedicine=2', '' . $url2, 'SSL'));
        } else {
            $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedicationsign', '' . $url2, 'SSL'));
            $this->data['updateredirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedicationsign2', '' . $url2, 'SSL'));
        }
        
       //// var_dump($this->data['redirect_url']);
      //  die;     


        $this->data['printaction'] = str_replace('&amp;', '&', $this->url->link('notes/tags/printmedicationform', '' . $url2, 'SSL'));
		
		
		if (isset($this->request->post['room_id'])) {
            $this->data['room_id'] = $this->request->post['room_id'];
        } elseif (! empty($tag_info)) {
            $this->data['room_id'] = $tag_info['room'];
        } else {
            $this->data['room_id'] = '';
        }
		
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            $facilities_id = $this->customer->getId();
        }
        
        $this->load->model('setting/locations');
        $data = array(
                'location_name' => $this->request->get['filter_name'],
                'facilities_id' => $facilities_id,
                'status' => '1',
                'type' => 'bedcheck',
                'sort' => 'task_form_name',
                'order' => 'ASC'
        );
        
        $rresults = $this->model_setting_locations->getlocations($data);
		foreach ($rresults as $result) {
			$this->data['rooms'][] = array(
					'locations_id' => $result['locations_id'],
					'location_name' => $result['location_name'],
			);
		}
		$data2 = array(
                'location_name' => $this->request->get['filter_name'],
                'facilities_id' => $facilities_id,
                'status' => '1',
                'type' => 'medication',
                'sort' => 'task_form_name',
                'order' => 'ASC'
        );
        
        $rresult6s = $this->model_setting_locations->getlocations($data2);
		
        
        foreach ($rresult6s as $result1) {
            
            $this->data['medications'][] = array(
                    'locations_id' => $result1['locations_id'],
                    'location_name' => $result1['location_name'],
            );
        }
		
		$this->load->model('medicationtype/medicationtype');
        $results = $this->model_medicationtype_medicationtype->getmedicationtypes($data);
            
		foreach ($results as $result) {               
			
			$this->data['medication_types'][] = array(
				'medicationtype_id' => $result['medicationtype_id'],
				'type_name' => $result['type_name'],
				'type' => $result['type'],
				'measurement_type' => $result['measurement_type'],
				'status' => $result['status'],
			);
		}
        
        if (isset($this->request->post['emp_tag_id'])) {
            $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id'] = $tag_info['tags_id'];
        } else {
            $this->data['emp_tag_id'] = '';
        }
        
        if (isset($this->request->post['emp_tag_id1'])) {
            $this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id1'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['emp_tag_id1'] = '';
        }
        
        if (isset($this->request->post['new_module'])) {
            $this->data['modules'] = $this->request->post['new_module'];
        } elseif ($this->request->get['tags_id']) {
            
            $muduled = $this->model_resident_resident->gettagModule($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $this->data['modules'] = $muduled['new_module'];
        } elseif ($this->request->post['emp_tag_id']) {
            
            $muduled = $this->model_resident_resident->gettagModule($this->request->post['emp_tag_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $this->data['modules'] = $muduled['new_module'];
        } else {
            $this->data['modules'] = array();
        }
        
        // var_dump($this->data['modules']);
        
        if (isset($this->request->post['medication_fields'])) {
            $this->data['medication_fields'] = $this->request->post['medication_fields'];
        } elseif ($this->request->get['tags_id']) {
            
            $medicine_info = $this->model_resident_resident->gettagmedicine($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $this->data['medication_fields'] = unserialize($medicine_info['medication_fields']);
        } elseif ($this->request->post['emp_tag_id']) {
            
            $medicine_info = $this->model_resident_resident->gettagmedicine($this->request->post['emp_tag_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $this->data['medication_fields'] = unserialize($medicine_info['medication_fields']);
        } else {
            $this->data['medication_fields'] = array();
        }
        
        if (isset($this->request->post['is_schedule'])) {
            $this->data['is_schedule'] = $this->request->post['is_schedule'];
        } elseif ($medicine_info) {
            $this->data['is_schedule'] = $medicine_info['is_schedule'];
        } else {
            $this->data['is_schedule'] = '0';
        }
        
        if (isset($this->request->post['drug_name'])) {
            $this->data['drug_name'] = $this->request->post['drug_name'];
        } else {
            $this->data['drug_name'] = '';
        }
        
        if (isset($this->request->post['drug_mg'])) {
            $this->data['drug_mg'] = $this->request->post['drug_mg'];
        } else {
            $this->data['drug_mg'] = '';
        }
        
        if (isset($this->request->post['drug_am'])) {
            $this->data['drug_am'] = $this->request->post['drug_am'];
        } else {
            $this->data['drug_am'] = date('h:i A');
        }
        
        if (isset($this->request->post['drug_pm'])) {
            $this->data['drug_pm'] = $this->request->post['drug_pm'];
        } else {
            $this->data['drug_pm'] = '';
        }
        
        if (isset($this->request->post['drug_alertnate'])) {
            $this->data['drug_alertnate'] = $this->request->post['drug_alertnate'];
        } else {
            $this->data['drug_alertnate'] = '';
        }
       
        
        if (isset($this->request->post['drug_prn'])) {
            $this->data['drug_prn'] = $this->request->post['drug_prn'];
        } else {
            $this->data['drug_prn'] = '';
        }
        
        if (isset($this->request->post['instructions'])) {
            $this->data['instructions'] = $this->request->post['instructions'];
        } else {
            $this->data['instructions'] = '';
        }
        
        if (isset($this->request->post['medication'])) {
            $this->data['medication'] = $this->request->post['medication'];
        } else {
            $this->data['medication'] = array();
        }
        
        if (isset($this->session->data['success_add_form'])) {
            $this->data['success_add_form'] = $this->session->data['success_add_form'];
            
            unset($this->session->data['success_add_form']);
        } else {
            $this->data['success_add_form'] = '';
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
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['drug_name'])) {
            $this->data['error_drug_name'] = $this->error['drug_name'];
        } else {
            $this->data['error_drug_name'] = array();
            ;
        }
        
        if (isset($this->error['date_from'])) {
            $this->data['error_date_from'] = $this->error['date_from'];
        } else {
            $this->data['error_date_from'] = array();
            ;
        }
        if (isset($this->error['date_to'])) {
            $this->data['error_date_to'] = $this->error['date_to'];
        } else {
            $this->data['error_date_to'] = array();
            ;
        }
        if (isset($this->error['daily_times'])) {
            $this->data['error_daily_times'] = $this->error['daily_times'];
        } else {
            $this->data['error_daily_times'] = array();
            ;
        }
		if (isset($this->error['drug_mg'])) {
            $this->data['error_drug_mg'] = $this->error['drug_mg'];
        } else {
            $this->data['error_drug_mg'] = array();
        }
		
		if (isset($this->error['drug_pm'])) {
            $this->data['error_drug_pm'] = $this->error['drug_pm'];
        } else {
            $this->data['error_drug_pm'] = array();
        }

         if (isset($this->error['drug_alertnate'])) {
            $this->data['error_drug_alternate'] = $this->error['drug_alertnate'];
        } else {
            $this->data['error_drug_alternate'] = array();
            ;
        }
        
        $url2 = "";
        $url3 = "";
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            $url3 .= '&tags_id=' . $this->request->get['tags_id'];
             $url3 .= '&facilities_id=' . $this->request->get['facilities_id'];
           $this->data['tags_id'] = $this->request->get['tags_id'];
        }
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            $url3 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
        if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
            $url2 .= '&is_archive=' . $this->request->get['is_archive'];
            $this->data['is_archive'] = $this->request->get['is_archive'];
        }
        
        $this->load->model('notes/notes');
        if ($this->request->get['notes_id']) {
            $notes_id = $this->request->get['notes_id'];
        } else {
            $notes_id = $this->request->get['updatenotes_id'];
        }
        
        $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
        
        // $this->data['updatenotes_id'] = $notes_id;
        
        $this->data['action'] = $this->url->link('resident/resident/tagsmedication', $url2, true);
        
        $this->data['back_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        $this->data['currentt_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '' . $url3, 'SSL'));
		
		$this->data['autosearch'] = $this->request->get['autosearch'];
		
        
        $this->template = $this->config->get('config_template') . '/template/resident/medication.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validateForm ()
    {
        
        /*
         * if($this->request->post['new_module'] == null &&
         * $this->request->post['new_module'] == ""){
         * $this->error['warning'] = 'Warning: Medication is required';
         * }
         */
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['new_module'] != null && $this->request->post['new_module'] != "") {
            foreach ($this->request->post['new_module'] as $key => $new_module) {
                if ($new_module['drug_name'] == "" && $new_module['drug_name'] == null) {
                    $this->error['drug_name'][$key] = 'Warning: Medication is required';
                }
                
                if ($new_module['is_schedule_medication'] == '1') {
                    if ($new_module['date_from'] == "" && $new_module['date_from'] == null) {
                        $this->error['date_from'][$key] = 'Date From is required';
                    }
                    if ($new_module['date_to'] == "" && $new_module['date_to'] == null) {
                        $this->error['date_to'][$key] = 'Date To is required';
                    }
                    if ($new_module['daily_times'] == "" && $new_module['daily_times'] == null) {
                        $this->error['daily_times'][$key] = 'Time is required';
                    }
                }
				
				
				if ($new_module['drug_pm'] == "" && $new_module['drug_pm'] == null) {
                    $this->error['drug_pm'][$key] = 'Type is required';
                }
                if ($new_module['drug_mg'] == "" && $new_module['drug_mg'] == null) {
                    $this->error['drug_mg'][$key] = 'Quantity is required';
                }
                if ($new_module['drug_alertnate'] == "" && $new_module['drug_alertnate'] == null) {
                    $this->error['drug_alertnate'][$key] = 'Dosage is required';
                }
            }
        }
       
        if ($this->request->post['emp_tag_id1'] == "" && $this->request->post['emp_tag_id1'] == null) {
            $this->error['warning'] = 'Warning: Client is required';
        }
        
        if ($this->request->post['drug_name'] != "" && $this->request->post['drug_name'] != null) {
            $medication_info = $this->model_resident_resident->get_medicationyname($this->request->post['drug_name'], $this->request->get['tags_id']);
            
            if ($medication_info) {
                $this->error['warning'] = 'Warning: Medication is already in enter!';
            }
        }
        
		
		if ($this->request->post['emp_tag_id'] != "" && $this->request->post['emp_tag_id'] != null) {
			$medication_task = $this->model_resident_resident->get_medicationyname22($this->request->post['emp_tag_id']);
            
            if (!empty($medication_task)) {
                $this->error['warning'] = 'Warning: Please complete Medication task!';
            }
		}
		
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function tagsmedicationsign ()
    {

      // var_dump($this->request->get);die;

        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        
        $this->load->model('resident/resident');
        $this->load->model('setting/tags');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $tdata = array();
            $tdata['tags_id'] = $this->request->get['tags_id'];
            $tdata['medication_tags'] = $this->request->get['medication_tags'];
			
			$taginfo =  $this->model_setting_tags->getTag($this->request->get['tags_id']);
			$result =  $this->model_facilities_facilities->getfacilities($taginfo['facilities_id']);
			if($result['is_master_facility'] == '1'){
				
				$tdata['facilities_id'] = $result['facilities_id'];
				
			}else{
				$tdata['facilities_id'] = $this->customer->getId();
			}
			

         // var_dump($tdata);
         // die;
            //$tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
            $this->model_resident_resident->tagmedication($this->request->post, $tdata);
            
            unset($this->session->data['medication']);
            
            $this->session->data['success_add_form1'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            }
            
            if ($notes_id != null && $notes_id != "") {
                $url2 .= '&notes_id=' . $notes_id;
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->request->get['facilities_id']);
        
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

         if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
        }
        
        if ($this->request->get['medication_tags'] != null && $this->request->get['medication_tags'] != "") {
            $url2 .= '&medication_tags=' . $this->request->get['medication_tags'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedicationsign', '' . $url2, 'SSL'));
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
        } /*elseif (! empty($this->session->data['username_confirm'])) {
            $this->data['user_id'] = $this->session->data['username_confirm'];
        }*/else {
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

    public function tagsmedicationsign2 ()
    {

      ////  var_dump($this->request->get);
       // die;


        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
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
            
			$this->load->model('api/temporary');
			$temporary_info = $this->model_api_temporary->gettemporary($this->request->get['archive_tags_medication_id']);
			
			
			
			$tempdata = array();
			$tempdata = unserialize($temporary_info['data']);
			
			if($tempdata['room_id'] > 0){
				$this->load->model('setting/tags');
				$this->model_setting_tags->updatetagroom($tempdata['room_id'], $this->request->get['tags_id']);
			}


      ////var_dump($this->request->get);
     // die;
			
			
			$archive_tags_medication_id = $this->model_resident_resident->addTagsMedication($tempdata, $this->request->get['tags_id']);
			
            $tdata = array();
            $tdata['tags_id'] = $this->request->get['tags_id'];
            $tdata['archive_tags_medication_id'] = $archive_tags_medication_id;
            $tdata['facilities_id'] = $this->request->get['facilities_id'];
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
            $this->model_resident_resident->tagmedication2($this->request->post, $tdata);
			
			$this->model_api_temporary->deletetemporary($this->request->get['archive_tags_medication_id']);
            
            $this->session->data['success_add_form1'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }

             if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            }
            
            if ($notes_id != null && $notes_id != "") {
                $url2 .= '&notes_id=' . $notes_id;
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL')));
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

        if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
        }
        
        if ($this->request->get['medication_tags'] != null && $this->request->get['medication_tags'] != "") {
            $url2 .= '&medication_tags=' . $this->request->get['medication_tags'];
        }
        
        if ($this->request->get['archive_tags_medication_id'] != null && $this->request->get['archive_tags_medication_id'] != "") {
            $url2 .= '&archive_tags_medication_id=' . $this->request->get['archive_tags_medication_id'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedicationsign2', '' . $url2, 'SSL'));
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
        }  elseif (! empty($this->session->data['username_confirm'])) {
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

    public function medicineautocomplete ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if (utf8_strlen($this->request->get['medicine_filter_name']) > 3) {
            
            // $medicineUrl =
            // 'https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search='.$this->request->get['medicine_filter_name'].'&limit=1';Albuterol%20Sulfate
            // $json_url =
            // "https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search=brand_name:".$this->request->get['medicine_filter_name'];
            $json_url = "https://dailymed.nlm.nih.gov/dailymed/autocomplete.cfm?key=search&returntype=json&term=" . $this->request->get['medicine_filter_name'];
            $json = file_get_contents($json_url);
            $data = json_decode($json, TRUE);
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            
            $json = array();
            foreach ($data as $obj) {
                foreach ($obj as $a) {
                    $json[] = array(
                            'generic_name' => '',
                            'brand_name' => $a
                    );
                }
            }
            
            /*
             * foreach($data as $obj){
             * foreach($obj[0]['patient']['drug'] as $a){
             * $json[] = array(
             * 'generic_name' =>'',
             * 'brand_name' => implode(",",$a['openfda']['brand_name']),
             * );
             * }
             * }
             */
        }
        
        $this->response->setOutput(json_encode($json));
    }

    public function activenote ()
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
            $tdata['keyword_id'] = $this->request->get['keyword_id'];
            $tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
            $this->model_resident_resident->activenote($this->request->post, $tdata);
            
            $this->session->data['success_add_form'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
                $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
            }
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
                $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL')));
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
        if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
            $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
        }
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
            $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
        }
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        
        if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
            $url2 .= '&clientactivenote=1';
            $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
        } else {
            $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/activenote', '' . $url2, 'SSL'));
        }
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
        }  elseif (! empty($this->session->data['username_confirm'])) {
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

    public function allrolecallsign ()
    {

      //var_dump($this->request->post);
     // die;



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
        
        $this->load->model('resident/resident');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $tdata = array();
            
            $tdata['tagsids'] = $this->session->data['tagsids'];
            $tdata['role_calls'] = $this->session->data['role_calls'];
            $tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
			
            $this->model_resident_resident->allrolecallsign($this->request->post, $tdata);
            
            unset($this->session->data['role_calls']);
            unset($this->session->data['tagsids']);
            
            $this->session->data['success_update_form_2'] = 'Role call updated';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            /*
             * if ($this->request->get['all_roll_call'] != null &&
             * $this->request->get['all_roll_call'] != "") {
             * $url2 .= '&all_roll_call=' .
             * $this->request->get['all_roll_call'];
             * }
             */
            // $this->redirect(str_replace('&amp;', '&',
            // $this->url->link('resident/resident', '', 'SSL')));
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
        if ($this->request->get['all_roll_call'] != null && $this->request->get['all_roll_call'] != "") {
            $url2 .= '&all_roll_call=' . $this->request->get['all_roll_call'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allrolecallsign', '' . $url2, 'SSL'));
        //$this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
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
        
        if (isset($this->session->data['success_update_form_2'])) {
            $this->data['success_update_form_2'] = $this->session->data['success_update_form_2'];
            
            unset($this->session->data['success_update_form_2']);
        } else {
            $this->data['success_update_form_2'] = '';
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
        
        // $this->load->model('setting/tags');
        // $tag_info =
        // $this->model_setting_tags->getTag($this->request->get['tags_id']);
        
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
        
        $this->load->model('facilities/facilities');
        $facilityinfo = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        $this->load->model('notes/notes');
        
        if (isset($this->request->post['customlistvalues_ids'])) {
            $customlistvalues_ids1 = $this->request->post['customlistvalues_ids'];
        } else {
            $customlistvalues_ids1 = array();
        }
        
        $this->data['customlistvalues_ids'] = array();
        $this->load->model('notes/notes');
        
        foreach ($customlistvalues_ids1 as $customlistvalues_id) {
            
            $custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
            
            if ($custom_info) {
                $this->data['customlistvalues_ids'][] = array(
                        'user_id' => $customlistvalues_id,
                        'customlistvalues_name' => $custom_info['customlistvalues_name'],
                         'required' => $custom_info['required']
                );
            }
        }
        
        if ($facilityinfo['config_rolecall_customlist_id'] != NULL && $facilityinfo['config_rolecall_customlist_id'] != "") {
            
            $d = array();
            
            $d['customlist_id'] = $facilityinfo['config_rolecall_customlist_id'];
            
            $customlists = $this->model_notes_notes->getcustomlists($d);
            
            if ($customlists) {
                foreach ($customlists as $customlist) {
                    $d2 = array();
                    $d2['customlist_id'] = $customlist['customlist_id'];
                    $customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
                    $this->data['customlists'][] = array(
                            'customlist_id' => $customlist['customlist_id'],
                            'customlist_name' => $customlist['customlist_name'],
                            'customlistvalues' => $customlistvalues
                    );

                     foreach ($customlistvalues as $value) {

                      $this->data['customlistvalues_ids'][] = array(
                        'user_id' => $value['customlistvalues_id'],
                        'customlistvalues_name' => $value['customlistvalues_name'],
                        'required' => $value['required']
                );
                      
                    }
                }
            }
            
            $this->data['id_url'] .= '&facilities_id=' . $this->customer->getId();
        }
        
        if (isset($this->request->post['tagides'])) {
            $tagides1 = $this->request->post['tagides'];
        } elseif (! empty($this->session->data['tagsids'])) {
            $tagides1 = $this->session->data['tagsids'];
            $this->data['is_multiple_tags_count'] = '1';
        } else {
            $tagides1 = array();
        }
        
        $this->data['tagides'] = array();
        $this->load->model('setting/tags');
        
        foreach ($tagides1 as $key => $tagsid) {
            
            $tag_info = $this->model_setting_tags->getTag($key);
            if ($tag_info) {
                $this->data['tagides'][] = array(
                        'tags_id' => $key,
                        'emp_tag_id' => $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name']
                );
            }
        }
        $this->data['is_multiple_tags'] = IS_MAUTIPLE;
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    public function rolecallsign ()
    {

          $facilities_id=""; 

        if($this->request->get['facilities_id']!=null && $this->request->get['facilities_id']!=""){

        if($this->request->get['facilities_id']==$this->customer->getId()){

          $facilities_id=$this->customer->getId();

        }else{

           $facilities_id=$this->request->get['facilities_id'];

        }
      }


        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->language->load('notes/notes');


       //// var_dump($this->$request->get['role_call']);
       // dir;
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        
        $this->load->model('notes/notes');
        $this->load->model('resident/resident');

		//// var_dump($datafa['facilities_id']);
		// die;


        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $tdata = array();
            $tdata['tags_id'] = $this->request->get['tags_id'];
            $tdata['discharge'] = $this->request->get['discharge'];
            $tdata['role_call'] = $this->request->get['role_call'];
            $tdata['facilities_id'] =$this->request->get['facilities_id'];
            $tdata['facilitytimezone'] = $this->customer->isTimezone();

           //var_dump($this->request->post);
         // die;


            $this->model_resident_resident->rolecallsign($this->request->post, $tdata);
            
            $this->session->data['success_add_form'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
                $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
            }
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            if ($this->request->get['role_call'] != null && $this->request->get['role_call'] != "") {
                $url2 .= '&role_call=' . $this->request->get['role_call'];
            }
            if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
                $url2 .= '&discharge=' . $this->request->get['discharge'];
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->$request->get['facilities_id']);
        
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
                'facilities_id' => $facilities_id
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
        if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
            $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
        }
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        if ($this->request->get['role_call'] != null && $this->request->get['role_call'] != "") {
            $url2 .= '&role_call=' . $this->request->get['role_call'];
        }

         if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
        }
        
        if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
            $url2 .= '&discharge=' . $this->request->get['discharge'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/rolecallsign', '' . $url2, 'SSL'));
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
         

        if (isset($this->error['comments'])) {
            $this->data['error_comments'] = $this->error['comments'];
        } else {
            $this->data['error_comments'] = '';
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

        if (isset($this->request->post['new_module'])) {
            $this->data['new_module'] = $this->request->post['new_module'];
        } elseif (! empty($notes_info)) {
            $this->data['new_module'] = $notes_info['new_module'];
        } else {
            $this->data['new_module'] = '';
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
        
       /* if (isset($this->request->post['customlistvalues_ids'])) {
            $customlistvalues_ids1 = $this->request->post['customlistvalues_ids'];
        } else {
            $customlistvalues_ids1 = array();
        } 
*/
         if (isset($this->request->post['new_module'])) {
            $customlistvalues_ids1 = $this->request->post['new_module'];
        } else {
            $customlistvalues_ids1 = array();
        } 



        /*$this->data['customlistvalues_ids'] = array();
        $this->load->model('notes/notes');
        
        foreach ($customlistvalues_ids1 as $customlistvalues_id) {
            
            $custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
            
            if ($custom_info) {
                $this->data['customlistvalues_ids'][] = array(
                        'user_id' => $customlistvalues_id,
                        'customlistvalues_name' => $custom_info['customlistvalues_name'],
                         'required' => $custom_info['required']
                );
            }
        }*/
        
        $this->load->model('facilities/facilities');
        $facilityinfo = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);      


        $this->load->model('notes/notes');
        
        if ($facilityinfo['config_rolecall_customlist_id'] != NULL && $facilityinfo['config_rolecall_customlist_id'] != "") {
            
            $d = array();
            
            $d['customlist_id'] = $facilityinfo['config_rolecall_customlist_id'];
            
            $customlists = $this->model_notes_notes->getcustomlists($d);   

         // var_dump($customlists);
         // die;       
            
            if ($customlists) {
                foreach ($customlists as $customlist) {
                    $d2 = array();
                    $d2['customlist_id'] = $customlist['customlist_id'];
                    $customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
                    $this->data['customlists'][] = array(
                            'customlist_id' => $customlist['customlist_id'],
                            'customlist_name' => $customlist['customlist_name'],
                            'customlistvalues' => $customlistvalues
                    );

                    foreach ($customlistvalues as $value) {

                      $this->data['customlistvalues_ids'][] = array(
                        'user_id' => $value['customlistvalues_id'],
                        'customlistvalues_name' => $value['customlistvalues_name'],
                         'required' => $value['required']
                );
                      
                    }

                }
            }

              //var_dump($this->data['customlists']);
            //die;
            
            $this->data['id_url'] .= '&facilities_id=' . $facilities_id;
        }
        
        if ($this->request->get['discharge'] == "1") {
            
            $this->load->model('createtask/createtask');
            $alldata = $this->model_createtask_createtask->getalltaskbyid($this->request->get['tags_id']);
            if ($alldata != NULL && $alldata != "") {
                $this->data['error_message'] = "All data related to this client will be deleted! Are you sure you want to discharge this client?";
                $this->data['confirm_alert'] = "1";
                $this->data['confirm_alert2'] = "1";
            }
        }
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    public function getstickynote ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        $this->data['tags_id'] = $this->request->get['tags_id'];
        $this->data['close'] = $this->request->get['close'];
        
        if ($this->request->post['tags_id'] != NULL && $this->request->post['tags_id'] != "") {
            
            $this->load->model('setting/tags');
            $this->model_setting_tags->updateSticky($this->request->post);
        }
        
        if ($this->request->get['clear'] == "1") {
            $this->load->model('setting/tags');
            $this->model_setting_tags->updateStickyclear($this->request->get['tags_id']);
        }
        
        $this->load->model('setting/tags');
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $stickyinfo = $this->model_setting_tags->getTag($this->request->get['tags_id']);
            $this->data['stickyinfo'] = $stickyinfo['stickynote'];
        }
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('resident/resident/getstickynote', '' . $url2, 'SSL'));
        $this->template = $this->config->get('config_template') . '/template/resident/stickynote.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        $this->response->setOutput($this->render());
    }

    public function residentstatus ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForms()) {
            
            $url2 = "";
            if (isset($this->request->post['taskid'])) {
                $url2 .= '&taskids=' . implode(",", $this->request->post['taskid']);
            }
            
            if (isset($this->request->post['formid'])) {
                $url2 .= '&formids=' . implode(",", $this->request->post['formid']);
            }
            if (isset($this->request->post['notes_id'])) {
                $url2 .= '&notesids=' . implode(",", $this->request->post['notes_id']);
            }
            
            if (isset($this->request->post['childstatus'])) {
                $url2 .= '&childstatus=' . $this->request->post['childstatus'];
            }
            if (isset($this->request->get['tags_id'])) {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            $this->session->data['success2'] = 'Status updated Successfully! ';
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
            
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&residentstatussign=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatussign', '' . $url2, 'SSL'));
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
        
        if (isset($this->request->post['taskid'])) {
            $this->data['taskid'] = $this->request->post['taskid'];
        } else {
            $this->data['taskid'] = array();
        }
        
        if (isset($this->request->post['formid'])) {
            $this->data['formid'] = $this->request->post['formid'];
        } else {
            $this->data['formid'] = array();
        }
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $currentdate = date('Y-m-d');
        
        $data = array(
                'currentdate' => $currentdate,
                'tags_id' => $this->request->get['tags_id']
        );
        
        $this->load->model('resident/resident');
        $task_infos = $this->model_resident_resident->getResidentstatus($data);
        
        $totaltask_infos = $this->model_resident_resident->getTotalResidentstatus($data);
        
        foreach ($task_infos as $taskinfo) {
            
            $tagstatus_info = $this->model_resident_resident->getTagstatusbyId($taskinfo['tagstatus_id']);
            $this->data['task_info'][] = array(
                    'tasktype' => $taskinfo['tasktype'],
                    'date_added' => date('m-d-Y', strtotime($taskinfo['date_added'])),
                    'description' => $taskinfo['description'],
                    'assign_to' => $taskinfo['assign_to'],
                    'task_time' => date('h:i A', strtotime($taskinfo['task_time'])),
                    'task_date' => date('m-d-Y', strtotime($taskinfo['task_date'])),
                    'count' => $totaltask_infos,
                    'taskid' => $taskinfo['id'],
                    'tagstatus_id' => $tagstatus_info['status']
            );
        }
        
        $this->load->model('form/form');
        $form_infos = $this->model_form_form->getformstatus($data);
        $totalform_infos = $this->model_form_form->gettotalformstatus($data);
        
        foreach ($form_infos as $formdata) {
            $tagstatus_info = $this->model_resident_resident->getTagstatusbyId($formdata['tagstatus_id']);
            
            $this->data['form_info'][] = array(
                    'form_description' => $formdata['form_description'],
                    'date_added' => date('m-d-Y', strtotime($formdata['date_added'])),
                    'count' => $totalform_infos,
                    'forms_id' => $formdata['forms_id'],
                    'tagstatus_id' => $tagstatus_info['status']
            );
        }
        
        $tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($this->request->get['tags_id']);
        
        // var_dump($tagstatusinfo);
        
        $this->data['tagstatus_info'] = $tagstatusinfo['status'];
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $currentdate2 = date('d-m-Y');
        
        $this->load->model('createtask/createtask');
        $tasksinfo = $this->model_createtask_createtask->getTaskas($this->request->get['tags_id'], $currentdate2);
        
        $this->data['tasksinfo1'] = $tasksinfo * 100;
        
        // var_dump($tasksinfo1);
        
        $this->load->model('setting/tags');
        $this->data['taginfo'] = $this->model_setting_tags->getTag($this->request->get['tags_id']);
        
        $data = array(
                'sort' => $sort,
                'order' => $order,
                'searchdate' => $searchdate,
                'searchdate_app' => '1',
                'tagstatus_id' => '1',
                'emp_tag_id' => $this->request->get['tags_id'],
                'facilities_id' => $this->customer->getId(),
				'customer_key' => $this->session->data['webcustomer_key'],
                'start' => 0,
                'limit' => 500
        );
        
        $this->load->model('notes/notes');
        $this->language->load('notes/notes');
        
        $this->load->model('user/user');
        $this->load->model('facilities/facilities');
        
        $this->load->model('notes/tags');
        
        $notes_total = $this->model_notes_notes->getTotalnotess($data);
        
        // var_dump($notes_total);
        
        $this->load->model('notes/notes');
        $last_notesID = $this->model_notes_notes->getLastNotesID($this->customer->getId(), $searchdate);
        
        $this->data['last_notesID'] = $last_notesID['notes_id'];
        
        // var_dump($data);
        
        $results = $this->model_notes_notes->getnotess($data);
        
        $facilityinfo = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        // var_dump($facilityinfo);
        
        foreach ($results as $result) {
            
            if ($result['notes_pin'] != null && $result['notes_pin'] != "") {
                $userPin = $result['notes_pin'];
            } else {
                $userPin = '';
            }
            
            $this->data['notess'][] = array(
                    'notes_id' => $result['notes_id'],
                    'notes_description' => $result['notes_description'],
                    'notetime' => date('h:i A', strtotime($result['notetime'])),
                    'username' => $result['user_id'],
                    'notes_pin' => $userPin,
                    'signature' => $result['signature'],
                    'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date']))
            );
        }
        
        $this->data['redirect_url2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatussign', '' . $url2, 'SSL'));
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatus', '' . $url2, 'SSL'));
        $this->template = $this->config->get('config_template') . '/template/resident/residentstatus.php';
        
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
        if ($this->request->post['user_id'] == '') {
            $this->error['user_id'] = $this->language->get('error_required');
        }

        if ($this->request->post ['new_module'] != null && $this->request->post ['new_module'] != "" && $this->request->post ['comments'] == "") {

          

            foreach ( $this->request->post ['new_module'] as  $key => $new_module ) {

              if ($new_module ['checkin'] =='1') {
                // var_dump($new_module['required']);
         
              if($new_module ['required']=="1"){
                 $this->error['comments'] = $this->language->get('error_required');
              }            

              }

            }

            //die;

        }





        
        if ($this->request->post['user_id'] != '') {
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($this->request->post['user_id']);
            
            if (empty($user_info)) {
                $this->error['user_id'] = $this->language->get('error_required');
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
        
        if ($this->request->post['select_one'] == '') {
            $this->error['select_one'] = $this->language->get('error_required');
        }
        
        if ($this->request->post['select_one'] == '1') {
            if ($this->request->post['notes_pin'] == '') {
                $this->error['notes_pin'] = $this->language->get('error_required');
            }
            if ($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != "") {
                $this->load->model('user/user');
                
                $user_info = $this->model_user_user->getUser($this->request->post['user_id']);
                
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

    protected function validateForms ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if (($this->request->post['formid'] == "" && $this->request->post['formid'] == "") && ($this->request->post['taskid'] == "" && $this->request->post['taskid'] == "")) {
            $this->error['warning'] = "This is required field!";
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function residentstatussign ()
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
        
        $this->load->model('resident/resident');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $tdata = array();
            $tdata['tags_id'] = $this->request->get['tags_id'];
            $tdata['childstatus'] = $this->request->get['childstatus'];
            $tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
            $this->model_resident_resident->residentstatussign($this->request->post, $tdata);
            
            $this->session->data['success_add_form'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
                $url2 .= '&forms_id=' . $this->request->get['forms_id'];
            }
            
            if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
                $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
            }
            
            if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
                $url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
            }
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if ($this->request->get['tags_forms_id'] != null && $this->request->get['tags_forms_id'] != "") {
                $url2 .= '&tags_forms_id=' . $this->request->get['tags_forms_id'];
            }
            if ($this->request->get['notesids'] != null && $this->request->get['notesids'] != "") {
                $url2 .= '&notesids=' . $this->request->get['notesids'];
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL')));
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
        if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
            $url2 .= '&forms_id=' . $this->request->get['forms_id'];
        }
        
        if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
            $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
        }
        
        if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
            $url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if ($this->request->get['tags_forms_id'] != null && $this->request->get['tags_forms_id'] != "") {
            $url2 .= '&tags_forms_id=' . $this->request->get['tags_forms_id'];
        }
        
        if ($this->request->get['taskids'] != null && $this->request->get['taskids'] != "") {
            $url2 .= '&taskids=' . $this->request->get['taskids'];
        }
        
        if ($this->request->get['formids'] != null && $this->request->get['formids'] != "") {
            $url2 .= '&formids=' . $this->request->get['formids'];
        }
        
        if ($this->request->get['childstatus'] != null && $this->request->get['childstatus'] != "") {
            $url2 .= '&childstatus=' . $this->request->get['childstatus'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatussign', '' . $url2, 'SSL'));
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
	
	public function masterfacility(){
		$this->load->model('facilities/facilities');
			$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			$ddss = array();
			if($result['client_facilities_ids'] != null && $result['client_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
				$ddss[] = $result['client_facilities_ids'];
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
			
			$ddss[] = $this->customer->getId();
			$sssssdd = implode(",",$ddss);
				
			$dataaaa = array();
			$dataaaa['facilities'] = $sssssdd;
			$mfacilities =  $this->model_facilities_facilities->getfacilitiess($dataaaa);
				
			$masterfacilities = array();
			foreach($mfacilities as $mfacility){
				$masterfacilities[] = array(
				  'name' => $mfacility['facility'],
				  'facilities_id' => $mfacility['facilities_id'],
				  'href' => str_replace('&amp;', '&', $this->url->link('resident/resident&search_facilities_id='.$mfacility['facilities_id'], '', 'SSL')),
				);
				
			}
				
			$this->data['masterfacilities'] = $masterfacilities;
		
			$this->data['reseturl'] = str_replace('&amp;', '&', $this->url->link('resident/resident&searchall=1', '', 'SSL'));
		
		$this->template = $this->config->get('config_template') . '/template/resident/master.php';
        
        $this->children = array(
            'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
	}

	 public function clientfile ()
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
        
        $this->load->model('resident/resident');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $tdata = array();
            $tdata['tags_id'] = $this->request->get['tags_id'];
            $tdata['notes_file'] = $this->request->get['notes_file'];
            $tdata['extention'] = $this->request->get['extention'];
            $tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
			
			
            $this->model_resident_resident->clientfile($this->request->post, $tdata);
            
            $this->session->data['success_add_form'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
                $url2 .= '&forms_id=' . $this->request->get['forms_id'];
            }
            
            if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
                $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
            }
            
            if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
                $url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
            }
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if ($this->request->get['tags_forms_id'] != null && $this->request->get['tags_forms_id'] != "") {
                $url2 .= '&tags_forms_id=' . $this->request->get['tags_forms_id'];
            }
            if ($this->request->get['notesids'] != null && $this->request->get['notesids'] != "") {
                $url2 .= '&notesids=' . $this->request->get['notesids'];
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL')));
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
        if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
            $url2 .= '&forms_id=' . $this->request->get['forms_id'];
        }
        
        if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
            $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
        }
        
        if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
            $url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if ($this->request->get['tags_forms_id'] != null && $this->request->get['tags_forms_id'] != "") {
            $url2 .= '&tags_forms_id=' . $this->request->get['tags_forms_id'];
        }
        
        if ($this->request->get['taskids'] != null && $this->request->get['taskids'] != "") {
            $url2 .= '&taskids=' . $this->request->get['taskids'];
        }
        
        if ($this->request->get['formids'] != null && $this->request->get['formids'] != "") {
            $url2 .= '&formids=' . $this->request->get['formids'];
        }
        
        if ($this->request->get['notes_file'] != null && $this->request->get['notes_file'] != "") {
            $url2 .= '&notes_file=' . $this->request->get['notes_file'];
        }
        if ($this->request->get['extention'] != null && $this->request->get['extention'] != "") {
            $url2 .= '&extention=' . $this->request->get['extention'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/clientfile', '' . $url2, 'SSL'));
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
	
	
	public function getMedicationtype(){
        
         $json = array();
         $this->load->model('medicationtype/medicationtype');
          $results = $this->model_medicationtype_medicationtype->getmedicationtype($this->request->get['medicationtype_id']);       
             if($results['measurement_type']!=null && $results['measurement_type']!=''){
                 $measurement_type=explode(',', $results['measurement_type']);
             }else{
                $measurement_type=array();
             }
            $json = array(

                'measurement_type' => $measurement_type,
                'type' => $results['type']
            );
        
        $this->response->setOutput(json_encode($json));
        
    }
	
	public function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
		$temp = array();
		foreach($arr as $key => $value) {
			$groupValue = $value[$group];
			if(!$preserveGroupKey)
			{
				unset($arr[$key][$group]);
			}
			if(!array_key_exists($groupValue, $temp)) {
				$temp[$groupValue] = array();
			}

			if(!$preserveSubArrays){
				$data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
			} else {
				$data = $arr[$key];
			}
			$temp[$groupValue][] = $data;
		}
		return $temp;
	}
}