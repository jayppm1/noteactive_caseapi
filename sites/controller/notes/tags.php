<?php
class ControllernotesTags extends Controller
{

    private $error = array();

    private $additional_enc = 'noteactive';

    public function autocomplete ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $json = array();
        
        if (isset($this->request->get['filter_name'])) {
            $this->load->model('setting/tags');
            $this->load->model('resident/resident');
            $this->load->model('form/form');
           
            if ($this->request->get['allclients'] != '1') {
                //$discharge = '1';
                //$all_record = '1';
            }
			
            if ($this->request->get['allclients'] == '1') {
                $is_master = '1';
            }else if ($this->request->get['allclients'] == '0') {
				$is_master = '1';
				$discharge = '1';
			}else{
				$discharge = '1';
				$is_master = '1';
			}
            
            if ($this->request->get['wait_list'] == '1') {
                $wait_list = '1';
            }
            
            if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
                $facilities_id = $this->request->get['facilities_id'];
            } else {
				
				$this->load->model('facilities/facilities');
				$resulsst =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
				
				if($resulsst['is_master_facility'] == '1'){
					if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
					$facilities_id  = $this->session->data['search_facilities_id']; 
					$facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
					$this->load->model('setting/timezone');
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
					$timezone_name = $timezone_info['timezone_value'];
					}else{
						$facilities_id = $this->customer->getId(); 
						$timezone_name = $this->customer->isTimezone();
					}
					 
				}else{
					$facilities_id = $this->customer->getId(); 
					$timezone_name = $this->customer->isTimezone();
				}
                //$facilities_id = $this->customer->getId();
            }
            
            $filter_name = explode(':', $this->request->get['filter_name']);
			
			
            $data = array(
                    'emp_tag_id_all' => trim($filter_name[0]),
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'discharge' => $discharge,
                    'all_record' => $all_record,
                    'wait_list' => $wait_list,
                    'is_master' => $is_master,
                    'sort' => 'emp_tag_id',
                    'order' => 'ASC',
                    'start' => 0,
                    'limit' => CONFIG_LIMIT
            );
			
			
			
            $this->load->model ( 'api/permision' );
            $results = $this->model_setting_tags->getTags($data);
           
		   
            foreach ($results as $result) {
                
                if ($result['date_of_screening'] != "0000-00-00") {
                    $date_of_screening = date('m-d-Y', strtotime($result['date_of_screening']));
                } else {
                    $date_of_screening = date('m-d-Y');
                }
                if ($result['dob'] != "0000-00-00") {
                    $dob = date('m-d-Y', strtotime($result['dob']));
                } else {
                    $dob = '';
                }
                if ($result['dob'] != "0000-00-00") {
                    $dobm = date('m', strtotime($result['dob']));
                } else {
                    $dobm = '';
                }
                if ($result['dob'] != "0000-00-00") {
                    $dobd = date('d', strtotime($result['dob']));
                } else {
                    $dobd = '';
                }
                if ($result['dob'] != "0000-00-00") {
                    $doby = date('Y', strtotime($result['dob']));
                } else {
                    $doby = '';
                }
                
                /*if ($result['gender'] == '1') {
                    $gender = '33';
                }
                if ($result['gender'] == '2') {
                    $gender = '34';
                }*/
                
                if ($result['upload_file']) {
                    $image_url1 = $result['upload_file'];
                    
                    // $image_url = file_get_contents($upload_file);
                    // $image_url1 =
                    // 'data:image/jpg;base64,'.base64_encode($image_url);
                } else {
                    $upload_file = '';
                    $image_url1 = '';
                }
                
                $tagmedication = $this->model_setting_tags->getTagsMedications($result['tags_id']);
                
                $alltagmeddetails = array();
                $tagmeddetails = $this->model_resident_resident->gettagModule($result['tags_id'],'','');
                
                $tags_form_info = $this->model_form_form->gettagsforma($result['tags_id']);
                // var_dump($tags_form_info);
                
                $url2 = "";
                $tags_form_url = "";
                if ($tags_form_info != null && $tags_form_info != "") {
                    $url2 .= '&forms_design_id=' . $tags_form_info['custom_form_type'];
                    $url2 .= '&forms_id=' . $tags_form_info['forms_id'];
                    $url2 .= '&notes_id=' . $tags_form_info['notes_id'];
                    $url2 .= '&facilities_id=' . $tags_form_info['facilities_id'];
                    if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
                        $url2 .= '&task_id=' . $this->request->get['task_id'];
                    }
                    
                    if ($this->request->get['serviceforms_id'] == '1') {
                        $action2 = str_replace('&amp;', '&', $this->url->link('services/form', '' . $url2, 'SSL'));
                    } else {
                        $action2 = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
                    }
                    
                    $tags_form_url = $action2;
                }
                
				$clientinfo = $this->model_api_permision->getclientinfo ( $result['facilities_id'], $result );
				
                $json[] = array(
                        'tags_id' => $result['tags_id'],
                        //'name' => $result['emp_tag_id'] . ': ' . $result['emp_first_name'] . ' ' . $result['emp_last_name'],
                        'name' => $clientinfo ['name'],
                        //'emp_tag_id' => $result['emp_tag_id'] . ': ' . $result['emp_first_name'] . ' ' . $result['emp_last_name'],
                        'emp_tag_id' => $clientinfo ['name'],
                        'emp_tag_id_1' => $result['emp_tag_id'] . ':' . $result['emp_first_name'],
                        'emp_first_name' => $result['emp_first_name'],
                        'emp_last_name' => $result['emp_last_name'],
                        'ccn' => $result['ccn'],
                        'emergency_contact' => $result['emergency_contact'],
                        'dob' => $dob,
                        'month' => $dobm,
                        'date' => $dobd,
                        'year' => $doby,
                        'age' => $result['age'],
                        'gender' => $result['customlistvalues_id'],
                        'location_address' => $result['location_address'],
                        'address_street2' => $result['address_street2'],
                        'person_screening' => $result['person_screening'],
                        'date_of_screening' => $date_of_screening,
                        'ssn' => $result['ssn'],
                        'state' => $result['state'],
                        'city' => $result['city'],
                        'zipcode' => $result['zipcode'],
                        'emp_extid' => $result['emp_extid'],
                        'date_added' => date('m-d-Y', strtotime($result['date_added'])),
                        'upload_file' => $upload_file,
                        'image_url1' => $image_url1,
						
                        'country' => 'US',
                        'tagmedication' => unserialize($tagmedication['medication_fields']),
                        'tagmeddetails' => $tagmeddetails['new_module'],
                        'tags_form_url' => $tags_form_url
                );
            }
            
            /*
             * $data = array(
             * 'client_name' => $this->request->get['filter_name'],
             * 'status' => '1',
             * 'sort' => 'client_name',
             * 'order' => 'ASC',
             * 'start' => 0,
             * 'limit' => CONFIG_LIMIT
             * );
             *
             * $this->load->model('createtask/createtask');
             *
             * $cresults =
             * $this->model_createtask_createtask->getclients($data);
             *
             * foreach ($cresults as $cresult) {
             * $json[] = array(
             * 'tags_id' => $cresult['client_id'],
             * 'emp_tag_id' => $cresult['client_name'],
             * 'tags_display' => $cresult['client_name'],
             * );
             * }
             */
        }
        
        /*
         * $sort_order = array();
         *
         * foreach ($json as $key => $value) {
         * $sort_order[$key] = $value['emp_tag_id'];
         * }
         *
         * array_multisort($sort_order, SORT_ASC, $json);
         */
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete2 ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $json = array();
		
		if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
			$facilities_id = $this->session->data['search_facilities_id'];
		}else{
			$facilities_id = $this->customer->getId();
		}  
        
        $this->load->model('setting/tags');
        $data = array(
                'emp_tag_id_all' => $this->request->get['filter_name'],
                'status' => '1',
                'discharge' => '1',
				'all_record' => '1',
				'is_master' => '1',
                'sort' => 'emp_tag_id',
                'facilities_id' => $facilities_id,
                'order' => 'ASC',
                'start' => 0,
                'limit' => CONFIG_LIMIT
        );
        
        $results = $this->model_setting_tags->getTags($data);
       
	   $this->load->model ( 'api/permision' );
        foreach ($results as $result) {
            
            $tagsmedications = $this->model_setting_tags->getTagsMedicationdetails($result['tags_id']);
            // var_dump($tagsmedications);
			
			$clientinfo = $this->model_api_permision->getclientinfo ( $result['facilities_id'], $result );
			
            if (! empty($tagsmedications)) {
                $json[] = array(
                        'tags_id' => $result['tags_id'],
                       // 'emp_tag_id' => $result['emp_tag_id'] . ': ' . $result['emp_first_name'] . ' ' . $result['emp_last_name'],
                        'emp_tag_id' => $clientinfo ['name'],
                        'emp_first_name' => $result['emp_first_name'],
                        'emp_last_name' => $result['emp_last_name'],
                        'tagsmedications' => $this->model_setting_tags->getTagsMedicationdetails($result['tags_id'])
                );
            }
        }
        
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete3 ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $json = array();
        
        if (isset($this->request->get['filter_name'])) {
            $this->load->model('setting/tags');
            $this->load->model('form/form');
            
            // $filter_name = str_replace('___-__-____', ' ',
            // $this->request->get['filter_name']);
            
            if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
                $facilities_id = $this->request->get['facilities_id'];
            } else {
                $facilities_id = $this->customer->getId();
            }
			
			if ($this->request->get['allclients'] == '1') {
                $discharge = '2';
                $is_master = '1';
            }
            
            $data = array(
                    'emp_tag_id_all' => $this->request->get['filter_name'],
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'discharge' => $discharge,
					'is_master' => $is_master,
                    'sort' => 'emp_tag_id',
                    'order' => 'ASC',
                    'start' => 0,
                    'limit' => CONFIG_LIMIT
            );
            
            $results = $this->model_setting_tags->getTags($data);
              
			
			$this->load->model('setting/locations');       
			$this->load->model('resident/resident');       
			$this->load->model('notes/clientstatus');    
			$this->load->model ( 'api/permision' );
            foreach ($results as $result) {
               
				$addtags_info = $this->model_form_form->gettagsforma($result['tags_id']);
				$url22 = "";
				if(!empty($addtags_info)){
					$url22 .= '&forms_id=' . $addtags_info['forms_id'];
					$url22 .= '&forms_design_id=' . $addtags_info['custom_form_type'];
					$url22 .= '&tags_id=' . $addtags_info['tags_id'];
					$url22 .= '&notes_id=' . $addtags_info['notes_id'];
					$url2 .= '&facilities_id=' . $addtags_info['facilities_id'];
					$action211 = str_replace('&amp;', '&', $this->url->link('form/form/edit', '' . $url22, 'SSL'));
				}else{
					$action211 = "";
				}
					// $tag_info =
					// $this->model_setting_tags->getTag($result['tags_id']);
					
					if ($result['date_of_screening'] != "0000-00-00") {
						$date_of_screening = date('m-d-Y', strtotime($result['date_of_screening']));
					} else {
						$date_of_screening = date('m-d-Y');
					}
					if ($result['dob'] != "0000-00-00") {
						$dob = date('m-d-Y', strtotime($result['dob']));
					} else {
						$dob = '';
					}
					
					if ($result['dob'] != "0000-00-00") {
						$dobm = date('m', strtotime($result['dob']));
					} else {
						$dobm = '';
					}
					if ($result['dob'] != "0000-00-00") {
						$dobd = date('d', strtotime($result['dob']));
					} else {
						$dobd = '';
					}
					if ($result['dob'] != "0000-00-00") {
						$doby = date('Y', strtotime($result['dob']));
					} else {
						$doby = '';
					}
					
					/*if ($result['gender'] == '1') {
						$gender = '33';
					}
					if ($result['gender'] == '2') {
						$gender = '34';
					}*/
					
					$get_img = $this->model_setting_tags->getImage($result['tags_id']);			
			
					if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
						$upload_file_thumb_1 = $get_img['upload_file_thumb'];
					} else {
						$upload_file_thumb_1 = $get_img['enroll_image'];
					}
					
					if ($result['ssn']) {
						$ssn = $result['ssn'] . ' ';
					} else {
						$ssn = '';
					}
					if ($result['emp_extid']) {
						$emp_extid = $result['emp_extid'] . ' ';
					} else {
						$emp_extid = '';
					}
					
					
					$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($result['tags_id']);
			
					if($tagstatusinfo !=NULL && $tagstatusinfo !=""){
						$status = $tagstatusinfo['status'];
						
						$classification_value = $this->model_resident_resident->getClassificationValue ($tagstatusinfo['status']); 
						$classification_name = $classification_value['classification_name'];
					}else{
						$classification_name = '';
					}
					
					$clientstatus_info = $this->model_notes_clientstatus->getclientstatus($result['role_call']);
					if($clientstatus_info['name'] != null && $clientstatus_info['name'] != ""){
						$role_callname = $clientstatus_info['name'];
						$color_code = $clientstatus_info['color_code'];
						$role_type = $clientstatus_info['type'];
					}
					if($result['room'] != null && $result['room'] != ""){
						$rresults = $this->model_setting_locations->getlocation($result['room']);
						$location_name = $rresults['location_name'];
					}else{
						$location_name = '';
					}
					
					$clientinfo = $this->model_api_permision->getclientinfo ( $result['facilities_id'], $result );
					
					$json[] = array(
							'tags_id' => $result['tags_id'],
							//'fullname' => $result['emp_last_name'] . ' ' . $result['emp_first_name'],
							'fullname' => $clientinfo ['name'],
							'classification_name' => $classification_name,
							'role_call' => $role_callname,
							'location_name' => $location_name,
							'emp_tag_id' => $result['emp_tag_id'],
							'emp_first_name' => $result['emp_first_name'],
							'emp_middle_name' => $result['emp_middle_name'],
							'emp_last_name' => $result['emp_last_name'],
							'location_address' => $result['location_address'],
							'discharge' => $result['discharge'],
							'age' => $result['age'],
							'dob' => $dob,
							'month' => $dobm,
							'date' => $dobd,
							'year' => $doby,
							'medication' => $result['medication'],
							// 'gender'=> $result['gender'],
							'gender' => $result['customlistvalues_id'],
							'person_screening' => $result['person_screening'],
							'date_of_screening' => $date_of_screening,
							'ssn' => $result['ssn'],
							'state' => $result['state'],
							'city' => $result['city'],
							'zipcode' => $result['zipcode'],
							'room' => $result['room'],
							'restriction_notes' => $result['restriction_notes'],
							'prescription' => $result['prescription'],
							'constant_sight' => $result['constant_sight'],
							'alert_info' => $result['alert_info'],
							'med_mental_health' => $result['med_mental_health'],
							'tagstatus' => $result['tagstatus'],
							'emp_extid' => $result['emp_extid'],
							'stickynote' => $result['stickynote'],
							'referred_facility' => $result['referred_facility'],
							'emergency_contact' => $result['emergency_contact'],
							'date_added' => date('m-d-Y', strtotime($result['date_added'])),
							'upload_file' => $upload_file_thumb_1,
							'image_url1' => $upload_file_thumb_1,
							'screening_update_url' => $action211
					);
				
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    public function autocompleteroom ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $json = array();
        
        if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            $facilities_id = $this->customer->getId();
        }
        
        if (isset($this->request->get['filter_name'])) {
            $this->load->model('setting/locations');
            $data = array(
                    'location_name' => $this->request->get['filter_name'],
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'sort' => 'task_form_name',
                    'order' => 'ASC',
                    'start' => 0,
                    'limit' => CONFIG_LIMIT
            );
            
            $results = $this->model_setting_locations->getlocations($data);
            
            $json[] = array(
                    'locations_id' => '0',
                    'location_name' => '-None-'
            );
            
            foreach ($results as $result) {
                
                $json[] = array(
                        'locations_id' => $result['locations_id'],
                        'location_name' => $result['location_name'],
                        'date_added' => $result['date_added']
                );
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    public function addclient (){
       
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            
            $this->load->model('setting/tags');
			
			$emp_extid = preg_replace('/\s+/', '', $this->request->post['emp_extid']);
			$ssn = preg_replace('/\s+/', '', $this->request->post['ssn']);
			
			$month_1 = $this->request->post['month_1'];
			$day_1 = $this->request->post['day_1'];
			$year_1 = $this->request->post['year_1'];
			
			$dob111 = $month_1 . '-' . $day_1 . '-' . $year_1;
			$date = str_replace('-', '/', $dob111);
			$res = explode("/", $date);
			$createdatess1 = $res[2] . "-" . $res[0] . "-" . $res[1];
			$dob = date('Y-m-d', strtotime($createdatess1));
			
			$existclient = array();
			$existclient['emp_extid'] = $emp_extid;
			$existclient['ssn'] = $ssn;
			$existclient['dob'] = $dob;
			$existclient['emp_first_name'] = $this->request->post['emp_first_name'];
			$existclient['emp_last_name'] = $this->request->post['emp_last_name'];
			
			$tag_exist_info = $this->model_setting_tags->getTagsbyAllNamedischage($existclient);
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			if ($facilities_info ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
				} else {
					if($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != ""){
						$facilities_id = $this->request->get['facilities_id'];
					}else{
						$facilities_id = $this->customer->getId ();
					}
				}
			} else {
				if($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != ""){
					$facilities_id = $this->request->get['facilities_id'];
				}else{
					$facilities_id = $this->customer->getId ();
				}
				
			}
			
            if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
                
                $this->model_setting_tags->updatexittag($this->request->post, $facilities_id);
                
                $this->model_setting_tags->editTags($this->request->post['emp_tag_id'],$this->request->post, $facilities_id);
                
                $tags_id = $this->request->post['emp_tag_id'];
            } else {
				if($tag_exist_info['tags_id'] != null && $tag_exist_info['tags_id'] != ""){
					$this->model_setting_tags->updatexittag($this->request->post, $facilities_id);
                
					$this->model_setting_tags->editTags($tag_exist_info['tags_id'], $this->request->post, $facilities_id);
					
					$tags_id = $tag_exist_info['tags_id'];
				}else{
					$tags_id = $this->model_setting_tags->addTags($this->request->post, $facilities_id);
				}
				
                
            }
            
            $url2 = "";
            $url2 .= '&tags_id=' . $tags_id;
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
            }
			
			if ($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->post['facilities_id'];
				
				$facilities_id = $this->request->post['facilities_id'];
            }

            if($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != ""){
                $url2 .= '&facilityids=' . $this->request->get ['facilityids'];
            }
            
            if($this->request->get['locationids'] != null && $this->request->get['locationids'] != ""){
                $url2 .= '&locationids=' . $this->request->get ['locationids'];
            }

            if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
            $url2 .= '&userids=' . $this->request->get['tagsids'];
        }
            
            if($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != ""){
                $url2 .= '&tagsids=' . $this->request->get ['tagsids'];
            }
			
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
                $url2 .= '&page=' . $this->request->get['page'];
            }

            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&client=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclientsign', $url2, true));
            }
            
            $this->data['clientcreated'] = '1';
        }
        
        $this->getForm();
    }

    public function updateclient () {
       
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {            
             
            $this->load->model('setting/tags');
			$this->load->model('api/temporary');
           
            $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
            
            if ($tag_info['tags_status_in'] == $this->request->post['tags_status_in']) {
                $tags_status_in_change = '1';
            } else {
                $tags_status_in_change = '2';
            }
            /* 
            $archive_tags_id = $this->model_setting_tags->editTags($this->request->get['tags_id'], $this->request->post, $this->customer->getId());
			*/
			
			$tdata = array();
			$tdata['id'] = $this->request->get['tags_id'];
			$tdata['facilities_id'] = $this->request->get['facilities_id'];
			$tdata['type'] = 'updateclient';

           // var_dump($tdata);
           // die;
			
			$archive_tags_id = $this->model_api_temporary->addtemporary($this->request->post, $tdata);
            
            $url2 = "";
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            $url2  .= '&facilities_id=' . $this->request->get['facilities_id'];
            $url2 .= '&tags_status_in_change=' . $tags_status_in_change;
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
                $url2 .= '&page=' . $this->request->get['page'];
            }
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            $url2 .= '&archive_tags_id=' . $archive_tags_id;    

         
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&updateclient=1';
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/updateclientsign', $url2, true));
            }
           //// var_dump($this->data['redirect_url']);
            //die;
            
            $this->data['clientcreated'] = '1';
        }
        
        $this->getForm();
    }

    protected function getForm ()
    {



        /* var_dump($this->request->get);die;*/


        $this->load->model('facilities/online');
        $this->load->model('resident/resident'); 
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            
            $this->load->model('setting/tags');
            $taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
             
            
            $this->data['archive_is_archive'] = $taginfo['is_archive'];
        }


        if ($this->request->get['tag_classification_id'] != null && $this->request->get['tag_classification_id'] != "") {
                $url2 .= '&tag_classification_id=' . $this->request->get['tag_classification_id'];
                  $this->data['tag_classification_id'] = $this->request->get['tag_classification_id'];
            }

            if ($this->request->get['classification_name'] != null && $this->request->get['classification_name'] != "") {
                $url2 .= '&classification_name=' . $this->request->get['classification_name'];
                  $this->data['classification_name'] = $this->request->get['classification_name'];
            }

             if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
            $url2 .= '&facilityids=' . $this->request->get['facilityids'];
        }
        if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
            $url2 .= '&locationids=' . $this->request->get['locationids'];
        }
        
        if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
            $url2 .= '&tagsids=' . $this->request->get['tagsids'];
        }

        if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
            $url2 .= '&userids=' . $this->request->get['tagsids'];
        }


        
        if (! isset($this->request->get['tags_id'])) {
            $this->data['action'] = $this->url->link('notes/tags/addclient', '' . $url2, 'SSL');
        } else {

            ////var_dump($this->request->get['facilities_id']);
           // die;
            
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];             
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            $url3 .= '&tags_id=' . $this->request->get['tags_id'];
             $url3 .= '&facilities_id=' . $this->request->get['facilities_id'];




            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
                $url2 .= '&is_archive=' . $this->request->get['is_archive'];
                $this->data['is_archive'] = $this->request->get['is_archive'];
            }
            
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $this->load->model('notes/notes');
                $notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
                
                $this->data['note_date_added'] = date('m-d-Y h:i A', strtotime($notes_info['date_added']));
            }
            
            $this->data['currentt_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/updateclient', '' . $url3, 'SSL'));
            
            $this->data['action'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
            $this->data['printaction'] = $this->url->link('notes/tags/printform', '' . $url2, 'SSL');

             $this->data['residentstatus_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatus', '', 'SSL'));


             $this->data['clientstatus_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allclientstatus&profile=1', '' . $url2, 'SSL'));
			 

            $this->data['clientclassification_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/multipleclassification', '' . $url2, 'SSL'));
            
            $this->data['unlock_client'] = $this->url->link('notes/notes/unlockUser&client=1', '' . $url2, 'SSL');
            
            $this->data['viewallpicture'] = $this->url->link('notes/tags/viewallpicture', '' . $url2, 'SSL');
        }
        
        $this->load->model('notes/notes');
        if ($this->request->get['notes_id']) {
            $notes_id = $this->request->get['notes_id'];
        } else {
            $notes_id = $this->request->get['updatenotes_id'];
        }
        
        $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
        
        // $this->data['notes_id'] = $this->request->get['notes_id'];
        
        if ($this->request->get['saveclient'] != '1') {
            $this->data['updatenotes_id'] = $notes_id;
        }
        
        $this->data['tags_id'] = $this->request->get['tags_id'];
        
        if (isset($this->session->data['success2'])) {
            $this->data['success2'] = $this->session->data['success2'];
            
            unset($this->session->data['success2']);
        } else {
            $this->data['success2'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->session->data['success_add_form'])) {
            $this->data['success_add_form'] = $this->session->data['success_add_form'];
            
            unset($this->session->data['success_add_form']);
        } else {
            $this->data['success_add_form'] = '';
        }
        
        if (isset($this->error['ssn'])) {
            $this->data['error_ssn'] = $this->error['ssn'];
        } else {
            $this->data['error_ssn'] = '';
        }
        if (isset($this->error['postal_code'])) {
            $this->data['error_postal_code'] = $this->error['postal_code'];
        } else {
            $this->data['error_postal_code'] = '';
        }
        
        if (isset($this->error['dob'])) {
            $this->data['error_dob'] = $this->error['dob'];
        } else {
            $this->data['error_dob'] = '';
        }
        
        if (isset($this->error['emp_first_name'])) {
            $this->data['error_emp_first_name'] = $this->error['emp_first_name'];
        } else {
            $this->data['error_emp_first_name'] = '';
        }
        
        // var_dump($this->data['error_emp_first_name']);
        
        if (isset($this->error['emp_last_name'])) {
            $this->data['error_emp_last_name'] = $this->error['emp_last_name'];
        } else {
            $this->data['error_emp_last_name'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['location_address'])) {
            $this->data['error_location_address'] = $this->error['location_address'];
        } else {
            $this->data['error_location_address'] = '';
        }
        if (isset($this->error['gender'])) {
            $this->data['error_gender'] = $this->error['gender'];
        } else {
            $this->data['error_gender'] = '';
        }
        
        if (isset($this->request->post['imageName_url'])) {
            $this->data['imageName_url'] = $this->request->post['imageName_url'];
        } elseif (! empty($taginfo)) {
            $this->load->model('setting/tags');
            $get_img = $this->model_setting_tags->getImage($this->request->get['tags_id']);
            
            $this->data['upload_file'] = $get_img['enroll_image'];
            $this->data['upload_file_thumb'] = $get_img['upload_file_thumb'];
            
            if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                $this->data['imageName_url'] = $get_img['upload_file_thumb'];
            } else {
                $this->data['imageName_url'] = $get_img['enroll_image'];
            }
            $this->load->model('setting/image');
            $this->data['check_img'] = $this->model_setting_image->checkresize($get_img['enroll_image']);
        } else {
            $this->data['imageName_url'] = '';
        }
        
        if (isset($this->request->post['imageName_path'])) {
            $this->data['imageName_path'] = $this->request->post['imageName_path'];
        } else {
            $this->data['imageName_path'] = '';
        }
        
        if (isset($this->request->post['imageName'])) {
            $this->data['imageName'] = $this->request->post['imageName'];
        } else {
            $this->data['imageName'] = '';
        }

        /* if (isset($this->request->post['client_status_color'])) {
            $this->data['client_status_color'] = $this->request->post['client_status_color'];
        } else {
            $this->data['client_status_color'] = '';
        }*/

         if (isset($this->request->post['client_status_image'])) {
            $this->data['client_status_image'] = $this->request->post['client_status_image'];
        } else {
            $this->data['client_status_image'] = '';
        }

        if (isset($this->request->post['tag_status_id'])) {
            $this->data['tag_status_id'] = $this->request->post['tag_status_id'];
        } else {
            $this->data['tag_status_id'] = '';
        }

       /* if (isset($this->request->post['tag_classification_id'])) {
            $this->data['tag_classification_id'] = $this->request->post['tag_classification_id'];
        } else {
            $this->data['tag_classification_id'] = '';
        }

        if (isset($this->request->post['client_classification_color'])) {
            $this->data['client_classification_color'] = $this->request->post['client_classification_color'];
        } else {
            $this->data['client_classification_color'] = '';
        }*/

         if (isset($this->request->get['role_call'])) {
            $this->data['role_call'] = $this->request->get['role_call'];
        } else {
            $this->data['role_call'] = '';
        }
        /*if (isset($this->request->post['classification_name'])) {
            $this->data['classification_name'] = $this->request->post['classification_name'];
        } else {
            $this->data['classification_name'] = '';
        }
        if (isset($this->request->post['client_clssification_color'])) {
            $this->data['client_clssification_color'] = $this->request->post['client_clssification_color'];
        } else {
            $this->data['client_clssification_color'] = '';
        }*/

        if($taginfo['tags_id']!='0' && $taginfo['tags_id']!=null){

             $status_value = $this->model_resident_resident->getTagstatusbyId ($taginfo['tags_id']);

        if (isset($this->request->post['tag_classification_id'])) {

            $tag_classification_id=$this->request->post['tag_classification_id'];
                            
                
                $tag_classification_ids=explode(",",$tag_classification_id);
        

            foreach($tag_classification_ids as $classification_id){

                $classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
                $classification_ids [] =$classification_value['tag_classification_id'];

                $classification_names [] =$classification_value['classification_name'];
               

                 $cassification_datas[]=array(
                'classification_name'=>$classification_value['classification_name'],
                'color_code'=>$classification_value['color_code'],
                'tag_classification_id'=>$classification_value['tag_classification_id']
                 );

            }



        }else if($taginfo ['classification_id']!="" && $taginfo ['classification_id']!=null){

                    $tag_classification_id=$taginfo ['classification_id'];
                            
                
                $tag_classification_ids=explode(",",$tag_classification_id);
        

            foreach($tag_classification_ids as $classification_id){

                $classification_value = $this->model_resident_resident->getClassificationValue ( $classification_id );
                $classification_ids [] =$classification_value['tag_classification_id'];

                $classification_names [] =$classification_value['classification_name'];
               

                 $cassification_datas[]=array(
                'classification_name'=>$classification_value['classification_name'],
                'color_code'=>$classification_value['color_code'],
                'tag_classification_id'=>$classification_value['tag_classification_id']
                 );

            }

            }else{

                $classification_names = [];
                $classification_ids = [];       
                $cassification_datas=[];
            }

           

          $classification_value = $this->model_resident_resident->getClassificationValue ($status_value['status']); 

          // var_dump($classification_value['color_code']);die;

           }   

          /* $this->data['tag_classification_id']=$classification_value['tag_classification_id'];
          $this->data['classification_name']=$classification_value['classification_name'];
           $this->data['client_classification_color']=$classification_value['color_code'];*/

         

         if($taginfo['role_call']!='0' && $taginfo['role_call']!=null){

          $client_statuses_value = $this->model_resident_resident->getClientStatusById ($taginfo['role_call']);
          
        ////  $this->data['client_status_color']=$client_statuses_value['color_code'];
         // $this->data['client_status_image']=$client_statuses_value['image'];

          }

          
            if (isset($this->request->post['tag_classification_id'])) {
            $this->data['tag_classification_id'] = $this->request->post['tag_classification_id'];
            } else if($taginfo ['classification_id']!="" && $taginfo['classification_id']!=null){
            $this->data['tag_classification_id']=$taginfo['classification_id'];
            } else {
            $this->data['tag_classification_id'] = '';
            }


        if (isset($this->request->post['classification_name'])) {
            $this->data['classification_name'] = $this->request->post['classification_name'];
        }else if($cassification_datas!="" && $cassification_datas!=null){
           $this->data['classification_name']=$cassification_datas;
        } else {
            $this->data['classification_name'] = '';
        }


        if (isset($this->request->post['client_classification_color'])) {
            $this->data['client_classification_color'] = $this->request->post['client_classification_color'];
        }else if($classification_colors!="" && $classification_colors!=null){
           $this->data['client_classification_color']=$classification_colors;
        } else {
            $this->data['client_classification_color'] = '';
        }      

         

         if($taginfo['role_call']!='0' && $taginfo['role_call']!=null){

          $client_statuses_value = $this->model_resident_resident->getClientStatusById ($taginfo['role_call']);           

          }

          if (isset($this->request->post['client_status_color'])) {
            $this->data['client_status_color'] = $this->request->post['client_status_color'];
        } else if ($client_statuses_value['color_code']!="" && $client_statuses_value['color_code']!=null){
              $this->data['client_status_color']=$client_statuses_value['color_code'];
        }else {
            $this->data['client_status_color'] = '';
        }

         if (isset($this->request->post['client_status_image'])) {
            $this->data['client_status_image'] = $this->request->post['client_status_image'];
        }else if ($client_statuses_value['image']!="" && $client_statuses_value['image']!=null){
              $this->data['client_status_image']=$client_statuses_value['image'];
        } else {
            $this->data['client_status_image'] = '';
        }

         if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        }else if ($client_statuses_value['name']!="" && $client_statuses_value['name']!=null){
              $this->data['name']=$client_statuses_value['name'];
        } else {
            $this->data['name'] = '';
        }









          /*if($this->request->get['tag_classification_id']!='0' && $this->request->get['tag_classification_id']!=null){

             $classification_value = $this->model_resident_resident->getClassificationValue ($this->request->get['tag_classification_id']);

          $this->data['client_classification_color']=$classification_value['color_code'];
          $this->data['client_classification_name']=$classification_value['name'];
          $this->data['tag_classification_id']=$this->request->get['tag_classification_id'];
          }*/



        
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        
        $this->data['emp_tag_id'] = $taginfo['emp_tag_id'];
        
        if (isset($this->request->post['upload_file'])) {
            $this->data['upload_file'] = $this->request->post['upload_file'];
        } elseif (! empty($taginfo)) {
            
            $this->load->model('setting/tags');
            $get_img = $this->model_setting_tags->getImage($this->request->get['tags_id']);
            
            $this->data['upload_file'] = $get_img['upload_file'];
            $this->data['upload_file_thumb'] = $get_img['upload_file_thumb'];
            
            if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                $this->data['image_url1'] = $get_img['upload_file_thumb'];
            } else {
                $this->data['image_url1'] = $get_img['upload_file'];
            }
            $this->load->model('setting/image');
            $this->data['check_img'] = $this->model_setting_image->checkresize($get_img['upload_file']);
        } else {
            $this->data['upload_file'] = '';
        }
        
        if ($this->data['upload_file'] != null && $this->data['upload_file'] != "") {
            // $image_url = file_get_contents($this->data['upload_file']);
            
            // $this->data['image_url1'] =
            // 'data:image/jpg;base64,'.base64_encode($image_url);
        }
        
        // var_dump($this->data['image_url1']);
        
        $this->load->model('setting/tags');
        $masked_fields = $this->model_setting_tags->getHiddenMaskedFields();
        $hidden_fields = $this->model_setting_tags->getHiddenFields();
        
        if (isset($this->request->post['emp_extid'])) {
            $this->data['emp_extid'] = $this->request->post['emp_extid'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_extid'] = $taginfo['emp_extid'];
        } else {
            $this->data['emp_extid'] = '';
        }

        /*if (isset($this->request->get['tag_classification_id'])) {
            $this->data['tag_classification_id'] = $this->request->get['emp_extid'];
        } elseif (! empty($taginfo)) {
            $this->data['tag_classification_id'] = $taginfo['tag_classification_id'];
        } else {
            $this->data['tag_classification_id'] = '';
        }*/

        /*if (isset($this->request->get['classification_name'])) {
            $this->data['classification_name'] = $this->request->get['classification_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_extid'] = $taginfo['emp_extid'];
        } else {
            $this->data['classification_name'] = '';
        }*/
        
        if (isset($this->request->post['referred_facility'])) {
            $this->data['referred_facility'] = $this->request->post['referred_facility'];
        } elseif (! empty($taginfo)) {
            $this->data['referred_facility'] = $taginfo['referred_facility'];
        } else {
            $this->data['referred_facility'] = '';
        }
        if (isset($this->request->post['tags_status_in'])) {
            $this->data['tags_status_in'] = $this->request->post['tags_status_in'];
        } elseif (! empty($taginfo)) {
            $this->data['tags_status_in'] = $taginfo['tags_status_in'];
            
            $this->load->model('setting/tags');
            $taginfo_a = $this->model_setting_tags->gettotalarchivetags($this->request->get['tags_id']);
            
            $this->data['taginfo_a'] = $taginfo_a;
        } else {
            $this->data['tags_status_in'] = '';
        }
        
        if (isset($this->request->post['facilities_id'])) {
            $this->data['facilities_id'] = $this->request->post['facilities_id'];
        } elseif (! empty($taginfo)) {
            $this->data['facilities_id'] = $taginfo['facilities_id'];
        } else {
            $this->data['facilities_id'] = $this->customer->getId();
        }
        
        if (isset($this->request->post['location_address'])) {
            $this->data['location_address'] = $this->request->post['location_address'];
        } elseif (! empty($taginfo)) {
            $this->data['location_address'] = $taginfo['location_address'];
        } else {
            $this->data['location_address'] = '';
        }
        
        if (isset($this->request->post['latitude'])) {
            $this->data['latitude'] = $this->request->post['latitude'];
        } elseif (! empty($taginfo)) {
            $this->data['latitude'] = $taginfo['latitude'];
        } else {
            $this->data['latitude'] = '';
        }
        if (isset($this->request->post['longitude'])) {
            $this->data['longitude'] = $this->request->post['longitude'];
        } elseif (! empty($taginfo)) {
            $this->data['longitude'] = $taginfo['longitude'];
        } else {
            $this->data['longitude'] = '';
        }
        
        if (isset($this->request->post['address_street2'])) {
            $this->data['address_street2'] = $this->request->post['address_street2'];
        } elseif (! empty($taginfo)) {
            $this->data['address_street2'] = $taginfo['address_street2'];
        } else {
            $this->data['address_street2'] = '';
        }
        
        if (isset($this->request->post['state'])) {
            $this->data['state'] = $this->request->post['state'];
        } elseif (! empty($taginfo)) {
            $this->data['state'] = $taginfo['state'];
        } else {
            $this->data['state'] = '';
        }
        
        if (isset($this->request->post['city'])) {
            $this->data['city'] = $this->request->post['city'];
        } elseif (! empty($taginfo)) {
            $this->data['city'] = $taginfo['city'];
        } else {
            $this->data['city'] = '';
        }
        
        if (isset($this->request->post['zipcode'])) {
            $this->data['zipcode'] = $this->request->post['zipcode'];
        } elseif (! empty($taginfo)) {
            $this->data['zipcode'] = $taginfo['zipcode'];
        } else {
            $this->data['zipcode'] = '';
        }
        
        // var_dump($masked_fields);
        if (isset($this->request->post['ssn'])) {
            $this->data['ssn'] = $this->request->post['ssn'];
        } elseif (! empty($taginfo)) {
            $this->data['ssn'] = $taginfo['ssn'];
        } else {
            $this->data['ssn'] = '';
        }
        
        // var_dump($this->data['ssn']);
        
        $timezone_name = $this->customer->isTimezone();
        
        date_default_timezone_set($timezone_name);
        
        if (isset($this->request->post['date_of_screening'])) {
            $this->data['date_of_screening'] = $this->request->post['date_of_screening'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['date_of_screening'] != "0000-00-00") {
                $this->data['date_of_screening'] = date('m-d-Y', strtotime($taginfo['date_of_screening']));
            } else {
                $this->data['date_of_screening'] = date('m-d-Y');
            }
        } else {
            $this->data['date_of_screening'] = date('m-d-Y');
        }
        
        if (isset($this->request->post['person_screening'])) {
            $this->data['person_screening'] = $this->request->post['person_screening'];
        } elseif (! empty($taginfo)) {
            $this->data['person_screening'] = $taginfo['person_screening'];
        } else {
            $this->data['person_screening'] = '';
        }
        
        if (isset($this->request->post['gender'])) {
            $this->data['gender'] = $this->request->post['gender'];
        } elseif (! empty($taginfo)) {
            $this->data['gender'] = $taginfo['customlistvalues_id'];
        } else {
            $this->data['gender'] = '';
        }
        
        if (isset($this->request->post['dob'])) {
            $this->data['dob'] = $this->request->post['dob'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['dob'] = date('m-d-Y', strtotime($taginfo['dob']));
            } else {
                $this->data['dob'] = '';
            }
        } else {
            $this->data['dob'] = '';
        }
        
        if (isset($this->request->post['month_1'])) {
            $this->data['month_1'] = $this->request->post['month_1'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['month_1'] = date('m', strtotime($taginfo['dob']));
            } else {
                $this->data['month_1'] = date('m');
            }
        } else {
            $this->data['month_1'] = date('m');
        }
        
        if (isset($this->request->post['day_1'])) {
            $this->data['day_1'] = $this->request->post['day_1'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['day_1'] = date('d', strtotime($taginfo['dob']));
            } else {
                $this->data['day_1'] = date('d');
            }
        } else {
            $this->data['day_1'] = date('d');
        }
        
        if (isset($this->request->post['year_1'])) {
            $this->data['year_1'] = $this->request->post['year_1'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['year_1'] = date('Y', strtotime($taginfo['dob']));
            } else {
                $this->data['year_1'] = date('Y');
            }
        } else {
            $this->data['year_1'] = date('Y');
        }
        
        $this->data['current_date'] = date('m-d-Y');
        $this->data['current_y'] = date("Y");
        
        if (isset($this->request->post['emp_first_name'])) {
            $this->data['emp_first_name'] = $this->request->post['emp_first_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_first_name'] = $taginfo['emp_first_name'];
        } else {
            $this->data['emp_first_name'] = '';
        }
        if (isset($this->request->post['emp_middle_name'])) {
            $this->data['emp_middle_name'] = $this->request->post['emp_middle_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_middle_name'] = $taginfo['emp_middle_name'];
        } else {
            $this->data['emp_middle_name'] = '';
        }
        
        if (isset($this->request->post['emp_last_name'])) {
            $this->data['emp_last_name'] = $this->request->post['emp_last_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_last_name'] = $taginfo['emp_last_name'];
        } else {
            $this->data['emp_last_name'] = '';
        }
        
        if (isset($this->request->post['emergency_contact'])) {
            $this->data['emergency_contact'] = $this->request->post['emergency_contact'];
        } elseif (! empty($taginfo)) {
            $this->data['emergency_contact'] = $taginfo['emergency_contact'];
        } else {
            $this->data['emergency_contact'] = '';
        }
        
        
        
        if (isset($this->request->post['room_id'])) {
            $this->data['room_id'] = $this->request->post['room_id'];
        } elseif (! empty($taginfo)) {
            $this->data['room_id'] = $taginfo['room'];
        } else {
            $this->data['room_id'] = '';
        }
         if (isset($this->request->post['bed_number'])) {
            $this->data['bed_number'] = $this->request->post['bed_number'];
        } elseif (! empty($taginfo)) {
            $this->data['bed_number'] = $taginfo['bed_number'];
        } else {
            $this->data['bed_number'] = '';
        }
        
        
        if (isset($this->request->post['date_added'])) {
            $this->data['date_added'] = $this->request->post['date_added'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['date_added'] != "0000-00-00 00:00:00") {
                $this->data['date_added'] = date('m-d-Y', strtotime($taginfo['date_added']));
            } else {
                $this->data['date_added'] = date('m-d-Y');
            }
        } else {
            $this->data['date_added'] = date('m-d-Y');
        }

        if (isset($this->request->get['stickynote'])) {
            $this->data['stickynote'] = $this->request->get['stickynote'];
        }  else {
            $this->data['stickynote'] = '';
        }

        $this->data['sticky_note'] = $this->url->link('resident/resident/getstickynote&close=1', '', 'SSL');
        
        /*
         * if (isset($this->request->post['room'])) {
         * $this->data['room'] = $this->request->post['room'];
         * }elseif (!empty($taginfo)) {
         * $this->load->model('setting/locations');
         * $tags_info12 =
         * $this->model_setting_locations->getlocation($taginfo['room']);
         *
         * $this->data['room'] = $tags_info12['location_name'];
         * } else {
         * $this->data['room'] = '';
         * }
         */
        
        if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
                $facilities_id = $this->session->data['search_facilities_id'];
            }else{
                $facilities_id = $this->customer->getId();
            } 
            //$facilities_id = $this->customer->getId();
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
        
        $this->load->model('setting/locations');
        $data = array(
                'location_name' => $this->request->get['filter_name'],
                'facilities_id' => $facilities_id,
                'status' => '1',
                'sort' => 'task_form_name',
                'order' => 'ASC'
        );
        
        $rresults = $this->model_setting_locations->getlocations($data);
        
        foreach ($rresults as $result) {
            
            $this->data['rooms'][] = array(
                    'locations_id' => $result['locations_id'],
                    'location_name' => $result['location_name'],
                    'date_added' => $result['date_added']
            );
        }
        
        if (isset($this->request->post['ccn'])) {
            $this->data['ccn'] = $this->request->post['ccn'];
        } elseif (! empty($taginfo)) {
            $this->data['ccn'] = $taginfo['ccn'];
        } else {
            $this->data['ccn'] = '';
        }
        if (isset($this->request->post['tagstatus'])) {
            $this->data['tagstatus'] = $this->request->post['tagstatus'];
        } elseif (! empty($taginfo)) {
            $this->data['tagstatus'] = $taginfo['tagstatus'];
        } else {
            $this->data['tagstatus'] = '';
        }
        
        if (isset($this->request->post['med_mental_health'])) {
            $this->data['med_mental_health'] = $this->request->post['med_mental_health'];
        } elseif (! empty($taginfo)) {
            $this->data['med_mental_health'] = $taginfo['med_mental_health'];
        } else {
            $this->data['med_mental_health'] = '';
        }
        
        if (isset($this->request->post['constant_sight'])) {
            $this->data['constant_sight'] = $this->request->post['constant_sight'];
        } elseif (! empty($taginfo)) {
            $this->data['constant_sight'] = $taginfo['constant_sight'];
        } else {
            $this->data['constant_sight'] = ''; 
        }
        
        if (isset($this->request->post['alert_info'])) {
            $this->data['alert_info'] = $this->request->post['alert_info'];
        } elseif (! empty($taginfo)) {
            $this->data['alert_info'] = $taginfo['alert_info'];
        } else {
            $this->data['alert_info'] = '';
        }
        
        if (isset($this->request->post['prescription'])) {
            $this->data['prescription'] = $this->request->post['prescription'];
        } elseif (! empty($taginfo)) {
            $this->data['prescription'] = $taginfo['prescription'];
        } else {
            $this->data['prescription'] = '';
        }
        
        if (isset($this->request->post['restriction_notes'])) {
            $this->data['restriction_notes'] = $this->request->post['restriction_notes'];
        } elseif (! empty($taginfo)) {
            $this->data['restriction_notes'] = $taginfo['restriction_notes'];
        } else {
            $this->data['restriction_notes'] = '';
        }
        if (isset($this->request->post['allclients'])) {
            $this->data['allclients'] = $this->request->post['allclients'];
        } else {
            $this->data['allclients'] = '1';
        }
        
        if (isset($this->request->post['is_discharge'])) {
            $this->data['is_discharge'] = $this->request->post['is_discharge'];
        } else {
            $this->data['is_discharge'] = '';
        }
        
        
        $this->load->model('resident/resident');
        
        if (isset($this->request->post['medication_fields'])) {
            $this->data['medication_fields'] = $this->request->post['medication_fields'];
        } elseif ($this->request->get['tags_id']) {
            
            $medicine_info = $this->model_resident_resident->gettagmedicine($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $this->data['medication_fields'] = unserialize($medicine_info['medication_fields']);
        } else {
            $this->data['medication_fields'] = array();
        }
        
        if (isset($this->request->post['forms_id'])) {
            $this->data['forms_id'] = $this->request->post['forms_id'];
        } elseif (! empty($taginfo)) {
            
            $this->load->model('form/form');
            
            if ($taginfo['is_archive'] == '3') {
                $tags_info121 = $this->model_form_form->gettagsforma3($this->request->get['tags_id']);
            } else {
                $tags_info121 = $this->model_form_form->gettagsforma($this->request->get['tags_id']);
            }
            
            $this->data['forms_id'] = $tags_info121['forms_id'];
        } else {
            $this->data['forms_id'] = '';
        }
        
        if (isset($this->request->post['link_screening'])) {
            $this->data['link_screening'] = $this->request->post['link_screening'];
        } elseif (! empty($tags_info121)) {
            $this->load->model('form/form');
            
            $tags_info12 = $this->model_form_form->getFormDatas($tags_info121['forms_id']);
            
            $design_forms = unserialize($tags_info12['design_forms']);
            
            $clientname = "";
            if ($design_forms[0][0]['' . TAG_FNAME . ''] != null && $design_forms[0][0]['' . TAG_FNAME . ''] != "") {
                $clientname = $design_forms[0][0]['' . TAG_FNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_MNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms[0][0]['' . TAG_DOB . ''] . ' | Screening ' . $design_forms[0][0]['' . TAG_SCREENING . ''];
            } else {
                $clientname = $tags_info12['incident_number'] . ' ' . date('m-d-Y', strtotime($tags_info12['date_added']));
            }
            
            $this->data['link_screening'] = $clientname;
        } else {
            $this->data['link_screening'] = '';
        }
        
        /*if (isset($this->request->post['emp_tag_id'])) {
            $this->load->model('setting/tags');
            $tag_info = $this->model_setting_tags->getTag($this->request->post['emp_tag_id']);
        }
        
        if (isset($this->request->post['emp_tag_id'])) {
            $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id'] = $tag_info['tags_id'];
        } else {
            $this->data['emp_tag_id'] = '';
        }*/
        
        if (isset($this->request->post['emp_tag_id1'])) {
            $this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id1'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['emp_tag_id1'] = '';
        }
        
        if (isset($this->request->post['reminder_time'])) {
            $this->data['reminder_time'] = $this->request->post['reminder_time'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['reminder_time'] != "00:00:00") {
                $this->data['reminder_time'] = date('h:i A', strtotime($taginfo['reminder_time']));
            } else {
                $this->data['reminder_time'] = '';
            }
        } else {
            $this->data['reminder_time'] = '';
        }
        
        if (isset($this->request->post['reminder_date'])) {
            $this->data['reminder_date'] = $this->request->post['reminder_date'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['reminder_date'] != "0000-00-00 00:00:00") {
                $this->data['reminder_date'] = date('m-d-Y', strtotime($taginfo['reminder_date']));
            } else {
                $this->data['reminder_date'] = '';
            }
        } else {
            $this->data['reminder_date'] = '';
        }
        
        $this->load->model('facilities/facilities');
        $facilityinfo = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        $this->load->model('notes/notes');
        
        if ($facilityinfo['config_tags_customlist_id'] != NULL && $facilityinfo['config_tags_customlist_id'] != "") {
            
            $d = array();
            $d['customlist_id'] = $facilityinfo['config_tags_customlist_id'];
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
                }
            }
        }
        
        $this->load->model('facilities/facilities');
        $dataaaa ['facilities_id'] = $this->customer->getId ();
        
        $this->data['sfacilities'] = $this->model_facilities_facilities->getfacilitiess($dataaaa);
        
        $url31 = "";
        
        if ($this->request->post['emp_extid'] != null && $this->request->post['emp_extid'] != "") {
            $url31 .= '&emp_extid=' . $this->request->post['emp_extid'];
        }
        
        if ($this->request->post['ssn'] != null && $this->request->post['ssn'] != "") {
            $url31 .= '&ssn=' . $this->request->post['ssn'];
        }
        
        if ($this->request->post['emp_first_name'] != null && $this->request->post['emp_first_name'] != "") {
            $url31 .= '&emp_first_name=' . $this->request->post['emp_first_name'];
        }
        
        if ($this->request->post['emp_last_name'] != null && $this->request->post['emp_last_name'] != "") {
            $url31 .= '&emp_last_name=' . $this->request->post['emp_last_name'];
        }
        
        if ($this->request->post['month_1'] != null && $this->request->post['month_1'] != "") {
            
            $dob111 = $this->request->post['month_1'] . '-' . $this->request->post['day_1'] . '-' . $this->request->post['year_1'];
            
            $url31 .= '&dob=' . $dob111;
        }
        
        $this->data['redirect_url_2'] = str_replace('&amp;', '&', $this->url->link('notes/tags/exitscreening', '' . $url31, 'SSL'));
        
        if (isset($this->error['exit_error'])) {
            $this->data['exit_error'] = $this->error['exit_error'];
        } else {
            $this->data['exit_error'] = '';
        }
        
        if (isset($this->request->post['client_add_new'])) {
            $this->data['client_add_new'] = $this->request->post['client_add_new'];
        } else {
            $this->data['client_add_new'] = '';
        }

        if (isset($this->session->data['success_tag_add_form'])) {
            $this->data['success_tag_add_form'] = $this->session->data['success_tag_add_form'];
            
            unset($this->session->data['success_tag_add_form']);
        } else {
            $this->data['success_tag_add_form'] = '';
        }
        
        $this->template = $this->config->get('config_template') . '/template/notes/tagform.php';
        
        $this->children = array(
                'common/headerpopup',
                'common/headerform'
        );
        
        $this->response->setOutput($this->render());
    }



    /*protected function getForm ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->request->get['facilities_id'];
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            
            $this->load->model('setting/tags');
            $taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            // var_dump($taginfo);
            
            $this->data['archive_is_archive'] = $taginfo['is_archive'];
        }
        
        if (! isset($this->request->get['tags_id'])) {
            $this->data['action'] = $this->url->link('notes/tags/addclient', '' . $url2, 'SSL');
        } else {

            ////var_dump($this->request->get['facilities_id']);
           // die;
            
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];             
            $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            $url3 .= '&tags_id=' . $this->request->get['tags_id'];
             $url3 .= '&facilities_id=' . $this->request->get['facilities_id'];
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
                $url2 .= '&is_archive=' . $this->request->get['is_archive'];
                $this->data['is_archive'] = $this->request->get['is_archive'];
            }
            
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $this->load->model('notes/notes');
                $notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
                
                $this->data['note_date_added'] = date('m-d-Y h:i A', strtotime($notes_info['date_added']));
            }
            
            $this->data['currentt_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/updateclient', '' . $url3, 'SSL'));
            
            $this->data['action'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
            $this->data['printaction'] = $this->url->link('notes/tags/printform', '' . $url2, 'SSL');
            
            $this->data['unlock_client'] = $this->url->link('notes/notes/unlockUser&client=1', '' . $url2, 'SSL');
			
			$this->data['viewallpicture'] = $this->url->link('notes/tags/viewallpicture', '' . $url2, 'SSL');
        }
        
        $this->load->model('notes/notes');
        if ($this->request->get['notes_id']) {
            $notes_id = $this->request->get['notes_id'];
        } else {
            $notes_id = $this->request->get['updatenotes_id'];
        }
        
        $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
        
        // $this->data['notes_id'] = $this->request->get['notes_id'];
        
        if ($this->request->get['saveclient'] != '1') {
            $this->data['updatenotes_id'] = $notes_id;
        }
        
        $this->data['tags_id'] = $this->request->get['tags_id'];
        
        if (isset($this->session->data['success2'])) {
            $this->data['success2'] = $this->session->data['success2'];
            
            unset($this->session->data['success2']);
        } else {
            $this->data['success2'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->session->data['success_add_form'])) {
            $this->data['success_add_form'] = $this->session->data['success_add_form'];
            
            unset($this->session->data['success_add_form']);
        } else {
            $this->data['success_add_form'] = '';
        }
        
        if (isset($this->error['ssn'])) {
            $this->data['error_ssn'] = $this->error['ssn'];
        } else {
            $this->data['error_ssn'] = '';
        }
        if (isset($this->error['postal_code'])) {
            $this->data['error_postal_code'] = $this->error['postal_code'];
        } else {
            $this->data['error_postal_code'] = '';
        }
        
        if (isset($this->error['dob'])) {
            $this->data['error_dob'] = $this->error['dob'];
        } else {
            $this->data['error_dob'] = '';
        }
        
        if (isset($this->error['emp_first_name'])) {
            $this->data['error_emp_first_name'] = $this->error['emp_first_name'];
        } else {
            $this->data['error_emp_first_name'] = '';
        }
        
        // var_dump($this->data['error_emp_first_name']);
        
        if (isset($this->error['emp_last_name'])) {
            $this->data['error_emp_last_name'] = $this->error['emp_last_name'];
        } else {
            $this->data['error_emp_last_name'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['location_address'])) {
            $this->data['error_location_address'] = $this->error['location_address'];
        } else {
            $this->data['error_location_address'] = '';
        }
        if (isset($this->error['gender'])) {
            $this->data['error_gender'] = $this->error['gender'];
        } else {
            $this->data['error_gender'] = '';
        }
        
        if (isset($this->request->post['imageName_url'])) {
            $this->data['imageName_url'] = $this->request->post['imageName_url'];
        } elseif (! empty($taginfo)) {
			$this->load->model('setting/tags');
			$get_img = $this->model_setting_tags->getImage($this->request->get['tags_id']);
            
            $this->data['upload_file'] = $get_img['enroll_image'];
            $this->data['upload_file_thumb'] = $get_img['upload_file_thumb'];
            
            if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                $this->data['imageName_url'] = $get_img['upload_file_thumb'];
            } else {
                $this->data['imageName_url'] = $get_img['enroll_image'];
            }
            $this->load->model('setting/image');
            $this->data['check_img'] = $this->model_setting_image->checkresize($get_img['enroll_image']);
        } else {
            $this->data['imageName_url'] = '';
        }
        
        if (isset($this->request->post['imageName_path'])) {
            $this->data['imageName_path'] = $this->request->post['imageName_path'];
        } else {
            $this->data['imageName_path'] = '';
        }
        
        if (isset($this->request->post['imageName'])) {
            $this->data['imageName'] = $this->request->post['imageName'];
        } else {
            $this->data['imageName'] = '';
        }
        
        $this->data['form_outputkey'] = $this->formkey->outputKey();
		
		
		$this->data['emp_tag_id'] = $taginfo['emp_tag_id'];
        
        if (isset($this->request->post['upload_file'])) {
            $this->data['upload_file'] = $this->request->post['upload_file'];
        } elseif (! empty($taginfo)) {
			
			$this->load->model('setting/tags');
			$get_img = $this->model_setting_tags->getImage($this->request->get['tags_id']);
			
            $this->data['upload_file'] = $get_img['upload_file'];
            $this->data['upload_file_thumb'] = $get_img['upload_file_thumb'];
            
            if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                $this->data['image_url1'] = $get_img['upload_file_thumb'];
            } else {
                $this->data['image_url1'] = $get_img['upload_file'];
            }
            $this->load->model('setting/image');
            $this->data['check_img'] = $this->model_setting_image->checkresize($get_img['upload_file']);
        } else {
            $this->data['upload_file'] = '';
        }
        
        if ($this->data['upload_file'] != null && $this->data['upload_file'] != "") {
            // $image_url = file_get_contents($this->data['upload_file']);
            
            // $this->data['image_url1'] =
            // 'data:image/jpg;base64,'.base64_encode($image_url);
        }
        
        // var_dump($this->data['image_url1']);
        
        $this->load->model('setting/tags');
        $masked_fields = $this->model_setting_tags->getHiddenMaskedFields();
        $hidden_fields = $this->model_setting_tags->getHiddenFields();
        
        if (isset($this->request->post['emp_extid'])) {
            $this->data['emp_extid'] = $this->request->post['emp_extid'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_extid'] = $taginfo['emp_extid'];
        } else {
            $this->data['emp_extid'] = '';
        }
        
        if (isset($this->request->post['referred_facility'])) {
            $this->data['referred_facility'] = $this->request->post['referred_facility'];
        } elseif (! empty($taginfo)) {
            $this->data['referred_facility'] = $taginfo['referred_facility'];
        } else {
            $this->data['referred_facility'] = '';
        }
        if (isset($this->request->post['tags_status_in'])) {
            $this->data['tags_status_in'] = $this->request->post['tags_status_in'];
        } elseif (! empty($taginfo)) {
            $this->data['tags_status_in'] = $taginfo['tags_status_in'];
            
            $this->load->model('setting/tags');
            $taginfo_a = $this->model_setting_tags->gettotalarchivetags($this->request->get['tags_id']);
            
            $this->data['taginfo_a'] = $taginfo_a;
        } else {
            $this->data['tags_status_in'] = '';
        }
        
        if (isset($this->request->post['facilities_id'])) {
            $this->data['facilities_id'] = $this->request->post['facilities_id'];
        } elseif (! empty($taginfo)) {
            $this->data['facilities_id'] = $taginfo['facilities_id'];
        } else {
            $this->data['facilities_id'] = $this->customer->getId();
        }
        
        if (isset($this->request->post['location_address'])) {
            $this->data['location_address'] = $this->request->post['location_address'];
        } elseif (! empty($taginfo)) {
            $this->data['location_address'] = $taginfo['location_address'];
        } else {
            $this->data['location_address'] = '';
        }
        
        if (isset($this->request->post['latitude'])) {
            $this->data['latitude'] = $this->request->post['latitude'];
        } elseif (! empty($taginfo)) {
            $this->data['latitude'] = $taginfo['latitude'];
        } else {
            $this->data['latitude'] = '';
        }
        if (isset($this->request->post['longitude'])) {
            $this->data['longitude'] = $this->request->post['longitude'];
        } elseif (! empty($taginfo)) {
            $this->data['longitude'] = $taginfo['longitude'];
        } else {
            $this->data['longitude'] = '';
        }
        
        if (isset($this->request->post['address_street2'])) {
            $this->data['address_street2'] = $this->request->post['address_street2'];
        } elseif (! empty($taginfo)) {
            $this->data['address_street2'] = $taginfo['address_street2'];
        } else {
            $this->data['address_street2'] = '';
        }
        
        if (isset($this->request->post['state'])) {
            $this->data['state'] = $this->request->post['state'];
        } elseif (! empty($taginfo)) {
            $this->data['state'] = $taginfo['state'];
        } else {
            $this->data['state'] = '';
        }
        
        if (isset($this->request->post['city'])) {
            $this->data['city'] = $this->request->post['city'];
        } elseif (! empty($taginfo)) {
            $this->data['city'] = $taginfo['city'];
        } else {
            $this->data['city'] = '';
        }
        
        if (isset($this->request->post['zipcode'])) {
            $this->data['zipcode'] = $this->request->post['zipcode'];
        } elseif (! empty($taginfo)) {
            $this->data['zipcode'] = $taginfo['zipcode'];
        } else {
            $this->data['zipcode'] = '';
        }
        
        // var_dump($masked_fields);
        if (isset($this->request->post['ssn'])) {
            $this->data['ssn'] = $this->request->post['ssn'];
        } elseif (! empty($taginfo)) {
            $this->data['ssn'] = $taginfo['ssn'];
        } else {
            $this->data['ssn'] = '';
        }
        
        // var_dump($this->data['ssn']);
        
        $timezone_name = $this->customer->isTimezone();
        
        date_default_timezone_set($timezone_name);
        
        if (isset($this->request->post['date_of_screening'])) {
            $this->data['date_of_screening'] = $this->request->post['date_of_screening'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['date_of_screening'] != "0000-00-00") {
                $this->data['date_of_screening'] = date('m-d-Y', strtotime($taginfo['date_of_screening']));
            } else {
                $this->data['date_of_screening'] = date('m-d-Y');
            }
        } else {
            $this->data['date_of_screening'] = date('m-d-Y');
        }
        
        if (isset($this->request->post['person_screening'])) {
            $this->data['person_screening'] = $this->request->post['person_screening'];
        } elseif (! empty($taginfo)) {
            $this->data['person_screening'] = $taginfo['person_screening'];
        } else {
            $this->data['person_screening'] = '';
        }
        
        if (isset($this->request->post['gender'])) {
            $this->data['gender'] = $this->request->post['gender'];
        } elseif (! empty($taginfo)) {
            $this->data['gender'] = $taginfo['customlistvalues_id'];
        } else {
            $this->data['gender'] = '';
        }
        
        if (isset($this->request->post['dob'])) {
            $this->data['dob'] = $this->request->post['dob'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['dob'] = date('m-d-Y', strtotime($taginfo['dob']));
            } else {
                $this->data['dob'] = '';
            }
        } else {
            $this->data['dob'] = '';
        }
        
        if (isset($this->request->post['month_1'])) {
            $this->data['month_1'] = $this->request->post['month_1'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['month_1'] = date('m', strtotime($taginfo['dob']));
            } else {
                $this->data['month_1'] = date('m');
            }
        } else {
            $this->data['month_1'] = date('m');
        }
        
        if (isset($this->request->post['day_1'])) {
            $this->data['day_1'] = $this->request->post['day_1'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['day_1'] = date('d', strtotime($taginfo['dob']));
            } else {
                $this->data['day_1'] = date('d');
            }
        } else {
            $this->data['day_1'] = date('d');
        }
        
        if (isset($this->request->post['year_1'])) {
            $this->data['year_1'] = $this->request->post['year_1'];
        } elseif (! empty($taginfo)) {
            if ($taginfo['dob'] != "0000-00-00") {
                $this->data['year_1'] = date('Y', strtotime($taginfo['dob']));
            } else {
                $this->data['year_1'] = date('Y');
            }
        } else {
            $this->data['year_1'] = date('Y');
        }
        
        $this->data['current_date'] = date('m-d-Y');
        $this->data['current_y'] = date("Y");
        
        if (isset($this->request->post['emp_first_name'])) {
            $this->data['emp_first_name'] = $this->request->post['emp_first_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_first_name'] = $taginfo['emp_first_name'];
        } else {
            $this->data['emp_first_name'] = '';
        }
        if (isset($this->request->post['emp_middle_name'])) {
            $this->data['emp_middle_name'] = $this->request->post['emp_middle_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_middle_name'] = $taginfo['emp_middle_name'];
        } else {
            $this->data['emp_middle_name'] = '';
        }
        
        if (isset($this->request->post['emp_last_name'])) {
            $this->data['emp_last_name'] = $this->request->post['emp_last_name'];
        } elseif (! empty($taginfo)) {
            $this->data['emp_last_name'] = $taginfo['emp_last_name'];
        } else {
            $this->data['emp_last_name'] = '';
        }
        
        if (isset($this->request->post['emergency_contact'])) {
            $this->data['emergency_contact'] = $this->request->post['emergency_contact'];
        } elseif (! empty($taginfo)) {
            $this->data['emergency_contact'] = $taginfo['emergency_contact'];
        } else {
            $this->data['emergency_contact'] = '';
        }
        
		
		
        if (isset($this->request->post['room_id'])) {
            $this->data['room_id'] = $this->request->post['room_id'];
        } elseif (! empty($taginfo)) {
            $this->data['room_id'] = $taginfo['room'];
        } else {
            $this->data['room_id'] = '';
        }
		
		

        if (isset($this->request->get['stickynote'])) {
            $this->data['stickynote'] = $this->request->get['stickynote'];
        }  else {
            $this->data['stickynote'] = '';
        }

        $this->data['sticky_note'] = $this->url->link('resident/resident/getstickynote&close=1', '', 'SSL');
        
        /*
         * if (isset($this->request->post['room'])) {
         * $this->data['room'] = $this->request->post['room'];
         * }elseif (!empty($taginfo)) {
         * $this->load->model('setting/locations');
         * $tags_info12 =
         * $this->model_setting_locations->getlocation($taginfo['room']);
         *
         * $this->data['room'] = $tags_info12['location_name'];
         * } else {
         * $this->data['room'] = '';
         * }
         */
        
   /* if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
		$facilities_id = $this->request->get['facilities_id'];
	} else {
		$facilities_id = $this->customer->getId();
	}
	
	$this->load->model('setting/locations');
	$data = array(
			'location_name' => $this->request->get['filter_name'],
			'facilities_id' => $facilities_id,
			'status' => '1',
			'sort' => 'task_form_name',
			'order' => 'ASC'
	);
	
	$rresults = $this->model_setting_locations->getlocations($data);
	
	foreach ($rresults as $result) {
		
		$this->data['rooms'][] = array(
				'locations_id' => $result['locations_id'],
				'location_name' => $result['location_name'],
				'date_added' => $result['date_added']
		);
	}
	
	if (isset($this->request->post['tagstatus'])) {
		$this->data['tagstatus'] = $this->request->post['tagstatus'];
	} elseif (! empty($taginfo)) {
		$this->data['tagstatus'] = $taginfo['tagstatus'];
	} else {
		$this->data['tagstatus'] = '';
	}
	
	if (isset($this->request->post['med_mental_health'])) {
		$this->data['med_mental_health'] = $this->request->post['med_mental_health'];
	} elseif (! empty($taginfo)) {
		$this->data['med_mental_health'] = $taginfo['med_mental_health'];
	} else {
		$this->data['med_mental_health'] = '';
	}
	
	if (isset($this->request->post['constant_sight'])) {
		$this->data['constant_sight'] = $this->request->post['constant_sight'];
	} elseif (! empty($taginfo)) {
		$this->data['constant_sight'] = $taginfo['constant_sight'];
	} else {
		$this->data['constant_sight'] = '';
	}
	
	if (isset($this->request->post['alert_info'])) {
		$this->data['alert_info'] = $this->request->post['alert_info'];
	} elseif (! empty($taginfo)) {
		$this->data['alert_info'] = $taginfo['alert_info'];
	} else {
		$this->data['alert_info'] = '';
	}
	
	if (isset($this->request->post['prescription'])) {
		$this->data['prescription'] = $this->request->post['prescription'];
	} elseif (! empty($taginfo)) {
		$this->data['prescription'] = $taginfo['prescription'];
	} else {
		$this->data['prescription'] = '';
	}
	
	if (isset($this->request->post['restriction_notes'])) {
		$this->data['restriction_notes'] = $this->request->post['restriction_notes'];
	} elseif (! empty($taginfo)) {
		$this->data['restriction_notes'] = $taginfo['restriction_notes'];
	} else {
		$this->data['restriction_notes'] = '';
	}
	if (isset($this->request->post['allclients'])) {
		$this->data['allclients'] = $this->request->post['allclients'];
	} else {
		$this->data['allclients'] = '1';
	}
	
	if (isset($this->request->post['is_discharge'])) {
		$this->data['is_discharge'] = $this->request->post['is_discharge'];
	} else {
		$this->data['is_discharge'] = '';
	}
	
	
	$this->load->model('resident/resident');
	
	if (isset($this->request->post['medication_fields'])) {
		$this->data['medication_fields'] = $this->request->post['medication_fields'];
	} elseif ($this->request->get['tags_id']) {
		
		$medicine_info = $this->model_resident_resident->gettagmedicine($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
		
		$this->data['medication_fields'] = unserialize($medicine_info['medication_fields']);
	} else {
		$this->data['medication_fields'] = array();
	}
	
	if (isset($this->request->post['forms_id'])) {
		$this->data['forms_id'] = $this->request->post['forms_id'];
	} elseif (! empty($taginfo)) {
		
		$this->load->model('form/form');
		
		if ($taginfo['is_archive'] == '3') {
			$tags_info121 = $this->model_form_form->gettagsforma3($this->request->get['tags_id']);
		} else {
			$tags_info121 = $this->model_form_form->gettagsforma($this->request->get['tags_id']);
		}
		
		$this->data['forms_id'] = $tags_info121['forms_id'];
	} else {
		$this->data['forms_id'] = '';
	}
	
	if (isset($this->request->post['link_screening'])) {
		$this->data['link_screening'] = $this->request->post['link_screening'];
	} elseif (! empty($tags_info121)) {
		$this->load->model('form/form');
		
		$tags_info12 = $this->model_form_form->getFormDatas($tags_info121['forms_id']);
		
		$design_forms = unserialize($tags_info12['design_forms']);
		
		$clientname = "";
		if ($design_forms[0][0]['' . TAG_FNAME . ''] != null && $design_forms[0][0]['' . TAG_FNAME . ''] != "") {
			$clientname = $design_forms[0][0]['' . TAG_FNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_MNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms[0][0]['' . TAG_DOB . ''] . ' | Screening ' . $design_forms[0][0]['' . TAG_SCREENING . ''];
		} else {
			$clientname = $tags_info12['incident_number'] . ' ' . date('m-d-Y', strtotime($tags_info12['date_added']));
		}
		
		$this->data['link_screening'] = $clientname;
	} else {
		$this->data['link_screening'] = '';
	}*/
	
	/*if (isset($this->request->post['emp_tag_id'])) {
		$this->load->model('setting/tags');
		$tag_info = $this->model_setting_tags->getTag($this->request->post['emp_tag_id']);
	}
	
	if (isset($this->request->post['emp_tag_id'])) {
		$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
	} elseif (! empty($tag_info)) {
		$this->data['emp_tag_id'] = $tag_info['tags_id'];
	} else {
		$this->data['emp_tag_id'] = '';
	}*/
	
	/*if (isset($this->request->post['emp_tag_id1'])) {
		$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
	} elseif (! empty($tag_info)) {
		$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
	} else {
		$this->data['emp_tag_id1'] = '';
	}
	
	if (isset($this->request->post['reminder_time'])) {
		$this->data['reminder_time'] = $this->request->post['reminder_time'];
	} elseif (! empty($taginfo)) {
		if ($taginfo['reminder_time'] != "00:00:00") {
			$this->data['reminder_time'] = date('h:i A', strtotime($taginfo['reminder_time']));
		} else {
			$this->data['reminder_time'] = '';
		}
	} else {
		$this->data['reminder_time'] = '';
	}
	
	if (isset($this->request->post['reminder_date'])) {
		$this->data['reminder_date'] = $this->request->post['reminder_date'];
	} elseif (! empty($taginfo)) {
		if ($taginfo['reminder_date'] != "0000-00-00 00:00:00") {
			$this->data['reminder_date'] = date('m-d-Y', strtotime($taginfo['reminder_date']));
		} else {
			$this->data['reminder_date'] = '';
		}
	} else {
		$this->data['reminder_date'] = '';
	}
	
	$this->load->model('facilities/facilities');
	$facilityinfo = $this->model_facilities_facilities->getfacilities($this->customer->getId());
	$this->load->model('notes/notes');
	
	if ($facilityinfo['config_tags_customlist_id'] != NULL && $facilityinfo['config_tags_customlist_id'] != "") {
		
		$d = array();
		$d['customlist_id'] = $facilityinfo['config_tags_customlist_id'];
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
			}
		}
	}
	
	$this->load->model('facilities/facilities');
	$dataaaa ['facilities_id'] = $this->customer->getId ();
	
	$this->data['sfacilities'] = $this->model_facilities_facilities->getfacilitiess($dataaaa);
	
	$url31 = "";
	
	if ($this->request->post['emp_extid'] != null && $this->request->post['emp_extid'] != "") {
		$url31 .= '&emp_extid=' . $this->request->post['emp_extid'];
	}
	
	if ($this->request->post['ssn'] != null && $this->request->post['ssn'] != "") {
		$url31 .= '&ssn=' . $this->request->post['ssn'];
	}
	
	if ($this->request->post['emp_first_name'] != null && $this->request->post['emp_first_name'] != "") {
		$url31 .= '&emp_first_name=' . $this->request->post['emp_first_name'];
	}
	
	if ($this->request->post['emp_last_name'] != null && $this->request->post['emp_last_name'] != "") {
		$url31 .= '&emp_last_name=' . $this->request->post['emp_last_name'];
	}
	
	if ($this->request->post['month_1'] != null && $this->request->post['month_1'] != "") {
		
		$dob111 = $this->request->post['month_1'] . '-' . $this->request->post['day_1'] . '-' . $this->request->post['year_1'];
		
		$url31 .= '&dob=' . $dob111;
	}
	
	$this->data['redirect_url_2'] = str_replace('&amp;', '&', $this->url->link('notes/tags/exitscreening', '' . $url31, 'SSL'));
	
	if (isset($this->error['exit_error'])) {
		$this->data['exit_error'] = $this->error['exit_error'];
	} else {
		$this->data['exit_error'] = '';
	}
	
	if (isset($this->request->post['client_add_new'])) {
		$this->data['client_add_new'] = $this->request->post['client_add_new'];
	} else {
		$this->data['client_add_new'] = '';
	}
	
	$this->template = $this->config->get('config_template') . '/template/notes/tagform.php';
	
	$this->children = array(
			'common/headerpopup',
			'common/headerform'
	);
	
	$this->response->setOutput($this->render());
}*/

	protected function validateForm ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        $this->load->model('setting/tags');
        // var_dump($formkeyerror);
        
        // die;
		 if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
			if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
				$facilities_id = $this->session->data['search_facilities_id'];
			}else{
				$facilities_id = $this->customer->getId();
			} 
            //$facilities_id = $this->customer->getId();
        }
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$this->data['customers'] = array();
		if (! empty ( $customer_info ['setting_data'])) {
			$customers = unserialize($customer_info ['setting_data']);
			
		}
		
		
		if ($this->request->post ['ssn'] != null && $this->request->post ['ssn'] != "") {
			
			$taginfo = $this->model_setting_tags->gettagsSSN ( $this->request->post ['ssn'] );
			
			
			if (! isset ( $this->request->get ['tags_id'] )) {
				if ($taginfo) {
					$this->error ['ssn'] = 'ssn already exist';
				}
			} else {
				if ($taginfo && ($this->request->get ['tags_id'] != $taginfo ['tags_id'])) {
					$this->error ['ssn'] = 'ssn already exist';
				}
			}
		}
		
        /*
		if($customers['firstname_required'] == '1'){
			$emp_first_name = preg_replace('/\s+/', '', $this->request->post['emp_first_name']);
			
			if ($emp_first_name == "" && $emp_first_name == null) {
				$this->error['emp_first_name'] = "This is required field!";
			}
        }
        
		if($customers['lastname_edit'] != '1'){
			$emp_last_name = preg_replace('/\s+/', '', $this->request->post['emp_last_name']);
			
			if ($emp_last_name == "" && $emp_last_name == null) {
				$this->error['emp_last_name'] = "This is required field!";
			}
        }
        
		if($customers['dob_edit'] != '1'){
			if (($this->request->post['month_1'] == "") || ($this->request->post['day_1'] == "") || ($this->request->post['year_1'] == "")) {
				$this->error['dob'] = "This is required field!";
			}
        }
        if($customers['gender_edit'] != '1'){
			if ($this->request->post['gender'] == "") {
				$this->error['gender'] = "This is required field!";
			}
        }
        */
        /*
         * if ($this->request->post['location_address'] == "") {
         * $this->error['location_address'] = "This is required field!";
         * }
         */
        
        $emp_extid = preg_replace('/\s+/', '', $this->request->post['emp_extid']);
        $ssn = preg_replace('/\s+/', '', $this->request->post['ssn']);
        
        $month_1 = $this->request->post['month_1'];
        $day_1 = $this->request->post['day_1'];
        $year_1 = $this->request->post['year_1'];
        
        $dob111 = $month_1 . '-' . $day_1 . '-' . $year_1;
        $date = str_replace('-', '/', $dob111);
        $res = explode("/", $date);
        $createdatess1 = $res[2] . "-" . $res[0] . "-" . $res[1];
        $dob = date('Y-m-d', strtotime($createdatess1));
        
        
        
        $current_date = date('Y-m-d');
        if($current_date < $dob){
            $this->error['dob'] = "You cannot enter a date in the future!";
        }
        
        
        
        $existclient = array();
        $existclient['emp_extid'] = $emp_extid;
        $existclient['ssn'] = $ssn;
        $existclient['dob'] = $dob;
        $existclient['emp_first_name'] = $this->request->post['emp_first_name'];
        $existclient['emp_last_name'] = $this->request->post['emp_last_name'];
        
        
        $tag_exist_info = $this->model_setting_tags->getTagsbyAllName($existclient);
        
        //var_dump($tag_exist_info);
        //die;
        
        $url2 .= '&tags_id=' . $tag_exist_info['tags_id'];
        $action2 = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
        
        if (! isset($this->request->get['tags_id'])) {
            if ($tag_exist_info) {
                $this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="' . $action2 . '">Yes</a>';
            }
        } else {
            if ($tag_exist_info && ($this->request->get['tags_id'] != $tag_exist_info['tags_id'])) {
                $this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="' . $action2 . '">Yes</a>';
            }
        }
        
        
        
        /*if (($this->request->post['emp_first_name'] != null && $this->request->post['emp_first_name'] != "") && ($this->request->post['emp_last_name'] != null && $this->request->post['emp_last_name'] != "")) {
            if ($this->request->get['tags_id'] == null && $this->request->get['tags_id'] == "") {
                if ($this->request->post['client_add_new'] == null && $this->request->post['client_add_new'] == "") {
                    if ($this->request->post['forms_id'] == null && $this->request->post['forms_id'] == "") {
                        
                        $this->load->model('form/form');
                        
                        $fdata = array();
                        
                        $dob111 = $this->request->post['month_1'] . '-' . $this->request->post['day_1'] . '-' . $this->request->post['year_1'];
                        
                        $fdata['forms_fields_values'] = array(
                                '' . TAG_EXTID . '' => $this->request->post['emp_extid'],
                                '' . TAG_SSN . '' => $this->request->post['ssn'],
                                '' . TAG_FNAME . '' => $this->request->post['emp_first_name'],
                                '' . TAG_LNAME . '' => $this->request->post['emp_last_name']
                        );
                        // 'date_70767270' => $dob111,
                        
                        // var_dump($fdata);
                        
                        $client_form_info = $this->model_form_form->getscrnneningFormdata($fdata, $this->customer->getId());
                        
                        if (! empty($client_form_info)) {
                            $this->error['warning'] = "Screening list";
                            $this->error['exit_error'] = '1';
                        }
                    }
                }
            }
        }*/
        
        /*
         * if ($this->request->post['room'] != "" &&
         * $this->request->post['room'] != null) {
         * if ($this->request->post['room_id'] == "0") {
         * $this->error['warning'] = "Select valid Room!";
         * }
         * }
         */
         
        if (!empty($this->request->post['imageName'])) {
            $facilities_id = $this->customer->getId();
            $result_inser_user_img22 = $this->awsimageconfig->searchFacesByImage($this->request->post['imageName_url'],$facilities_id);
            foreach($result_inser_user_img22['FaceMatches'] as $c){
                $similarity = $c['Similarity'];
                $FaceId[] = $c['Face']['FaceId'];
                $ImageId[] = $c['Face']['ImageId'];
                $ExternalImageId = $c['Face']['ExternalImageId'];
            }
            
            if($ExternalImageId != null && $ExternalImageId != ""){
                $this->load->model('setting/tags');
                $taginfo_a = $this->model_setting_tags->getTagbyEMPID($ExternalImageId);
                if($taginfo_a['emp_tag_id'] != $ExternalImageId){
                    $this->error['warning'] = 'This '.$taginfo_a['emp_tag_id'].' is already enrolled!';
                }
            }
        
            
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function updateclientsign ()
    {  


     // var_dump($this->request->get);
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
        $this->load->model('setting/tags');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $this->load->model('setting/tags');
			
			$this->load->model('api/temporary');
			$temporary_info = $this->model_api_temporary->gettemporary($this->request->get['archive_tags_id']);

            //var_dump($this->request->get['facilities_id']);
           //die;
			
			$tempdata = array();
			$tempdata = unserialize($temporary_info['data']);
			
			
			
			if($tempdata['facilities_id'] != null && $tempdata['facilities_id'] != ""){
				$facilities_id = $tempdata['facilities_id'];
			}else{
				$facilities_id = $this->request->get['facilities_id'];
			}
			
			$archive_tags_id = $this->model_setting_tags->editTags($this->request->get['tags_id'], $tempdata, $facilities_id);
            
            $data2 = array();
            $data2['tags_id'] = $this->request->get['tags_id'];
            $data2['notes_id'] = $this->request->get['notes_id'];
            $data2['archive_tags_id'] = $archive_tags_id;
            $data2['facilities_id'] = $facilities_id;
            $data2['facilitytimezone'] = $this->customer->isTimezone();
            
            $data2['tags_status_in_change'] = $this->request->get['tags_status_in_change'];
            $data2['tag_classification_id'] = $tempdata['tag_classification_id'];
            $data2['tag_status_id'] = $tempdata['tag_status_id'];
			
            
            $notes_id = $this->model_setting_tags->updateclientsign($this->request->post, $data2);
			
			$this->model_api_temporary->deletetemporary($this->request->get['archive_tags_id']);
            
            $this->session->data['success_add_form'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
                $url2 .= '&page=' . $this->request->get['page'];
            }
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            if ($this->request->get['tags_status_in_change'] != null && $this->request->get['tags_status_in_change'] != "") {
                $url2 .= '&tags_status_in_change=' . $this->request->get['tags_status_in_change'];
            }

             $this->session->data['success_tag_add_form'] = '1';
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
        
        $this->data['config_tag_status'] = $this->customer->isTag();
        
        $url2 = "";
        
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
        if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
            $url2 .= '&page=' . $this->request->get['page'];
        }
        
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        if ($this->request->get['archive_tags_id'] != null && $this->request->get['archive_tags_id'] != "") {
            $url2 .= '&archive_tags_id=' . $this->request->get['archive_tags_id'];
        }
        if ($this->request->get['tags_status_in_change'] != null && $this->request->get['tags_status_in_change'] != "") {
            $url2 .= '&tags_status_in_change=' . $this->request->get['tags_status_in_change'];
        }
        
        if ($this->request->get['notes_id']) {
            $notes_id = $this->request->get['notes_id'];
        } else {
            $notes_id = $this->request->get['updatenotes_id'];
        }
        $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
        
        // $this->data['notes_id'] = $this->request->get['notes_id'];
        $this->data['updatenotes_id'] = $notes_id;
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('notes/tags/updateclientsign', '' . $url2, 'SSL'));
        
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
        
        if ($this->request->get['tags_id']) {
            $this->load->model('setting/tags');
            $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
        }
        
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
        $this->data['createtask'] = 1;
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    public function addclientsign ()
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
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('setting/tags');
		
		
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            $this->load->model('setting/tags');
            
            $data2 = array();
            $data2['tags_id'] = $this->request->get['tags_id'];
            $data2['facilities_id'] = $this->request->get['facilities_id'];
            $data2['facilityids'] = $this->request->get['facilityids'];
            $data2['locationids'] = $this->request->get['locationids'];
            $data2['tagsids'] = $this->request->get['tagsids'];
            $data2['userids'] = $this->request->get['userids'];
            $data2['facilitytimezone'] = $this->customer->isTimezone();
            
            $notes_id = $this->model_setting_tags->addclientsign($this->request->post, $data2);

            $this->load->model('notes/notes');
            $this->load->model('setting/tags');
            
            $facilities_id = $data2['facilities_id']; 
            
            
            $facilitytimezone = $data2['facilitytimezone']; 
            $tags_id = $data2['tags_id']; 
        
            $timezone_name = $facilitytimezone;
            $timeZone = date_default_timezone_set($timezone_name);
            $noteDate = date('Y-m-d H:i:s', strtotime('now'));
            $date_added = (string) $noteDate;
            
            
            $notetime = date('H:i:s', strtotime('now'));
            
            if($this->request->post['imgOutput']){
                $data['imgOutput'] = $this->request->post['imgOutput'];
            }else{
                $data['imgOutput'] = $this->request->post['signature'];
            }
            
            
            $data['notes_pin'] = $this->request->post['notes_pin'];
            $data['user_id'] = $this->request->post['user_id'];
            $data['notes_type'] = $this->request->post['notes_type'];
            $data['phone_device_id'] = $this->request->post['phone_device_id'];
            $data['is_android'] = $this->request->post['is_android'];

            
            $tag_info = $this->model_setting_tags->getTag($tags_id);
            
            $data['emp_tag_id'] = $tag_info['emp_tag_id'];
            $data['tags_id'] = $tag_info['tags_id'];
            
            
            
            $data['keyword_file'] = INTAKE_ICON;
            
            $this->load->model('setting/keywords');
            $keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file'],$tag_info['facilities_id']);
            
            if($this->request->post['comments'] != null && $this->request->post['comments']){
                $comments = ' | '.$this->request->post['comments'];
            }
            
            $this->load->model('facilities/facilities');
            $facility_info = $this->model_facilities_facilities->getfacilities($tag_info['facilities_id']);
            
            //$data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .''.$comments;
            
            
            $client_t = "";
            if($tag_info['tags_status_in'] == 'Admitted'){
                $client_t = "admitted";
            }
            if($tag_info['tags_status_in'] == 'Wait listed'){
                $client_t = "wait listed";
            }
            if($tag_info['tags_status_in'] == 'Referred'){
                $client_t = "referred";
            }
            if($tag_info['tags_status_in'] == 'Closed'){
                $client_t = "closed";
            }
            
            $data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['tags_status_in']. '-' .$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' has been '.$client_t.' to '. $facility_info['facility'] .' ';
            
            
            $data['date_added'] = $date_added;
            $data['note_date'] = $date_added;
            $data['notetime'] = $notetime;


            $this->load->model ( 'notes/notes' );
            
            $aids = array();
            
            $alocationids = array();

            $notes_description=$data['notes_description'];          
        
            
            if($data2['locationids'] != null && $data2['locationids'] != ""){
                $sssssdds2 = explode(",",$data2['locationids']);
                $abdcds = array_unique($sssssdds2);
                $this->load->model('setting/locations');
                
                foreach($abdcds as $locationid){
                    $location_info12 = $this->model_setting_locations->getlocation($locationid);
                    $locationname = '|'.$location_info12['location_name'];
                    $notes_description = str_ireplace($locationname,"",$notes_description);
                    
                    $locationname = '| '.$location_info12['location_name'];
                    $notes_description = str_ireplace($locationname,"",$notes_description);
                    
                    
                    
                    $aids[$location_info12['facilities_id']]['locations'][] = array (
                        'valueId' => $locationid,
                    );
                }
            }
            
            
            $atagsids = array();
            if($data2['tagsids'] != null && $data2['tagsids'] != ""){
                $this->load->model('setting/tags');
                $sssssddsd = explode(",",$data2['tagsids']);
                $abdca = array_unique($sssssddsd);
                
                foreach($abdca as $tagsid){
                    $tag_info = $this->model_setting_tags->getTag($tagsid);
                    $empfirst_name = '|'.$tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
                    $notes_description = str_ireplace($empfirst_name,"", $notes_description);
                    
                    $empfirst_name = '| '.$tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
                    $notes_description = str_ireplace($empfirst_name,"", $notes_description);
                    /*$atagsids[] = array(
                        'tags_id'=>$tagsid,
                        'facilities_id'=>$tag_info['facilities_id'],
                    );*/
                    
                    $aids[$tag_info['facilities_id']]['clients'][] = array (
                        'valueId' => $tagsid,
                    );
                }
            }
            
            if($data2['facilityids'] != null && $data2['facilityids'] != ""){
                $this->load->model('facilities/facilities');
                $sssssddsg = explode(",",$data2['facilityids']);
                $abdcg = array_unique($sssssddsg);
                foreach($abdcg as $fid){
                    
                    $facilityinfo = $this->model_facilities_facilities->getfacilities($fid);
                    
                    $notes_description = str_ireplace('|'.$facilityinfo['facility'],"", $notes_description);
                    $notes_description = str_ireplace('| '.$facilityinfo['facility'],"", $notes_description);
                    
                    $aids[$facilityinfo['facilities_id']]['facilitiesids'][] = array (
                        'valueId' => $fid,
                    );
                }
                
            }
            
            if($data2['userids'] != null && $data2['userids'] != ""){
                $this->load->model('user/user');
                $ssssssuser = explode(",",$data2['userids']);
                $ssabdcg = array_unique($ssssssuser);
            
                foreach($ssabdcg as $usid){
                    
                    $userinfo = $this->model_user_user->getUser($usid);
                    $notes_description = str_ireplace('|'.$userinfo['username'],"", $notes_description);
                    $notes_description = str_ireplace('| '.$userinfo['username'],"", $notes_description);
                    $aids[$facilities_id]['usersids'][] = array (
                        'valueId' => $usid,
                    );
                }
                
            }
            
            $notesids = array();
            
            
            
            if(!empty($aids)){
                foreach($aids as $facilities_id =>$aid){
                    $data['keyword_file1'] = array();
                    $data['tags_id_list1'] = array();
                    $data ['locationsid'] = array();
                    $aidsss = array();
                    $aidsss1 = '';
                    $locationname1 = "";
                    if($aid['clients'] != null && $aid['clients'] != ""){
                        $tags_id_list = array();
                        foreach($aid['clients'] as $clid){
                            $tags_id_list[] = $clid['valueId'];
                        }
                        
                        $data['tags_id_list1'] = $tags_id_list;
                        
                        $data['notes_description'] = $notes_description;
                    }
                    
                    if($aid['locations'] != null && $aid['locations'] != ""){
                        $locationsid = array();
                        foreach($aid['locations'] as $locid){
                            
                            $location_info12 = $this->model_setting_locations->getlocation($locid['valueId']);
                            $locationname1 .= $location_info12['location_name'].' | ';
                    
                            $locationsid[] = $locid['valueId'];
                        }
                        $data['locationsid'] = $locationsid;
                        
                        $data['notes_description'] = $locationname1 .' '. $notes_description.' '.$comments;
                    }
                    
                    if($aid['usersids'] != null && $aid['usersids'] != ""){
                        $usid = array();
                        foreach($aid['usersids'] as $usercid){
                            
                            $user_info12 = $this->model_user_user->getUser($usercid['valueId']);
                            $username1 .= $user_info12['username'].' | ';
                    
                            $usid[] = $usercid['valueId'];
                        }
                        $data['usid'] = $usid;
                        
                        $data['notes_description'] = $username1 .' '. $notes_description.' '.$comments;
                    }                                   


                $data['date_added'] = $date_added;
                $data['note_date'] = $date_added;
                $data['notetime'] = $notetime; 
                $data['keyword_file']="";
                    
                    
                    
                $notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
                $user_array[]=$notes_id;

                $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . (int)$tags_id . "'");
            
            if($tag_info['forms_id'] > 0 ){
                $this->db->query("UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape($tags_id) . "', date_updated = '" . $this->db->escape($date_added) . "' WHERE forms_id = '" . (int)$tag_info['forms_id'] . "'");
            
            }
            
            
            $this->db->query("UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . (int)$tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . (int)$notes_id . "'");
            
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
                
            if($this->request->post['notes_type'] == null && $this->request->post['notes_type'] == ""){
            if ($facility['is_enable_add_notes_by'] == '1') {
                $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
                $this->db->query($sql122);
            }
            if ($facility['is_enable_add_notes_by'] == '3') {
                $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
                $this->db->query($sql13);
            }
            }
            
            if ($facility['is_enable_add_notes_by'] == '1') {
                if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
                    
            
                    $notes_file = $this->session->data['local_notes_file'];
                    $outputFolder = $this->session->data['local_image_dir'];
                    //$facilities_id = $facilities_id;
                    
                    require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                    $this->load->model('notes/notes');
                    $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                     
                    if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
                        $this->model_notes_notes->updateuserverified('2', $notes_id);
                    }
            
                    if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
                        $this->model_notes_notes->updateuserverified('1', $notes_id);
                    }
            
                    unlink($this->session->data['local_image_dir']);
                    unset($this->session->data['username_confirm']);
                    unset($this->session->data['local_image_dir']);
                    unset($this->session->data['local_image_url']);
                    unset($this->session->data['local_notes_file']);
                }
            }
             
        
        $this->load->model('activity/activity');
        $adata['notes_id'] = $notes_id;
        $adata['tags_id'] = $tags_id;
        $adata['phone_device_id'] = $this->request->post['phone_device_id'];
        $adata['is_android'] = $this->request->post['is_android'];
        $adata['user_id'] = $this->request->post['user_id'];
        $adata['archive_tags_id'] = $data2['archive_tags_id'];
        $adata['facilities_id'] = $facilities_id;
        $adata['comments'] = $comments;
        $adata['date_added'] = $date_added;
        $this->model_activity_activity->addActivitySave('addclientsign', $adata, 'query');
                    
                }
            }else
            
            if($data2['facilityids'] != null && $data2['facilityids'] != ""){
            
                $sssssdds = explode(",",$data2['facilityids']);
                
                $abdc = array_unique($sssssdds);
                
                $data['notes_description'] = $comments;
                $data['date_added'] = $date_added;
                $data['note_date'] = $date_added;
                $data['notetime'] = $notetime;
                $data['keyword_file']="";
                foreach($abdc as $sssssd){
                    
                    $notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $sssssd );
                    $location_array[] = $notes_id;

                    $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . (int)$tags_id . "'");
            
            if($tag_info['forms_id'] > 0 ){
                $this->db->query("UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape($tags_id) . "', date_updated = '" . $this->db->escape($date_added) . "' WHERE forms_id = '" . (int)$tag_info['forms_id'] . "'");
            
            }
            
            
            $this->db->query("UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . (int)$tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . (int)$notes_id . "'");
            
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
                
            if($this->request->post['notes_type'] == null && $this->request->post['notes_type'] == ""){
            if ($facility['is_enable_add_notes_by'] == '1') {
                $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
                $this->db->query($sql122);
            }
            if ($facility['is_enable_add_notes_by'] == '3') {
                $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
                $this->db->query($sql13);
            }
            }
            
            if ($facility['is_enable_add_notes_by'] == '1') {
                if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
                    
            
                    $notes_file = $this->session->data['local_notes_file'];
                    $outputFolder = $this->session->data['local_image_dir'];
                    //$facilities_id = $facilities_id;
                    
                    require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                    $this->load->model('notes/notes');
                    $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                     
                    if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
                        $this->model_notes_notes->updateuserverified('2', $notes_id);
                    }
            
                    if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
                        $this->model_notes_notes->updateuserverified('1', $notes_id);
                    }
            
                    unlink($this->session->data['local_image_dir']);
                    unset($this->session->data['username_confirm']);
                    unset($this->session->data['local_image_dir']);
                    unset($this->session->data['local_image_url']);
                    unset($this->session->data['local_notes_file']);
                }
            }
             
        
        $this->load->model('activity/activity');
        $adata['notes_id'] = $notes_id;
        $adata['tags_id'] = $tags_id;
        $adata['phone_device_id'] = $this->request->post['phone_device_id'];
        $adata['is_android'] = $this->request->post['is_android'];
        $adata['user_id'] = $this->request->post['user_id'];
        $adata['archive_tags_id'] = $data2['archive_tags_id'];
        $adata['facilities_id'] = $facilities_id;
        $adata['comments'] = $comments;
        $adata['date_added'] = $date_added;
        $this->model_activity_activity->addActivitySave('addclientsign', $adata, 'query');

                }
                
                $notesids1 = implode(",",$notesids);
                $url2 = '&notes_ids=' . $notesids1;
                
            }else{

                $data['notes_description'] = $comments;
                $data['date_added'] = $date_added;
                $data['note_date'] = $date_added;
                $data['notetime'] = $notetime;
                $data['keyword_file']="";  



                $notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->customer->getId () );

                $facility_array[]=$notes_id;
                
                $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . (int)$tags_id . "'");
            
            if($tag_info['forms_id'] > 0 ){
                $this->db->query("UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape($tags_id) . "', date_updated = '" . $this->db->escape($date_added) . "' WHERE forms_id = '" . (int)$tag_info['forms_id'] . "'");
            
            }
            
            
            $this->db->query("UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . (int)$tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . (int)$notes_id . "'");
            
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
                
            if($this->request->post['notes_type'] == null && $this->request->post['notes_type'] == ""){
            if ($facility['is_enable_add_notes_by'] == '1') {
                $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
                $this->db->query($sql122);
            }
            if ($facility['is_enable_add_notes_by'] == '3') {
                $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
                $this->db->query($sql13);
            }
            }
            
            if ($facility['is_enable_add_notes_by'] == '1') {
                if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
                    
            
                    $notes_file = $this->session->data['local_notes_file'];
                    $outputFolder = $this->session->data['local_image_dir'];
                    //$facilities_id = $facilities_id;
                    
                    require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                    $this->load->model('notes/notes');
                    $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                     
                    if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
                        $this->model_notes_notes->updateuserverified('2', $notes_id);
                    }
            
                    if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
                        $this->model_notes_notes->updateuserverified('1', $notes_id);
                    }
            
                    unlink($this->session->data['local_image_dir']);
                    unset($this->session->data['username_confirm']);
                    unset($this->session->data['local_image_dir']);
                    unset($this->session->data['local_image_url']);
                    unset($this->session->data['local_notes_file']);
                }
            }
             
        
        $this->load->model('activity/activity');
        $adata['notes_id'] = $notes_id;
        $adata['tags_id'] = $tags_id;
        $adata['phone_device_id'] = $this->request->post['phone_device_id'];
        $adata['is_android'] = $this->request->post['is_android'];
        $adata['user_id'] = $this->request->post['user_id'];
        $adata['archive_tags_id'] = $data2['archive_tags_id'];
        $adata['facilities_id'] = $facilities_id;
        $adata['comments'] = $comments;
        $adata['date_added'] = $date_added;
        $this->model_activity_activity->addActivitySave('addclientsign', $adata, 'query');


         if($facility_array!=null && $facility_array!=""){

            $result=array_merge($facility_array);

         }
         if($location_array!=null && $location_array!=""){

            $result=array_merge($location_array);

         }
         if($user_array!=null && $user_array!=""){

            $result=array_merge($user_array);

         }

        if($result!=null && $result!=""){


            foreach($result as $notes_id){

            $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($result);

            }
            
        }







        }
            
            $this->session->data['success_add_form'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
                $url2 .= '&page=' . $this->request->get['page'];
            }
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
             if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
                $url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
            }
            
            $url2 .= '&notes_id=' . $notes_id;
            $url2 .= '&saveclient=1';
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
        
        $this->data['config_tag_status'] = $this->customer->isTag();
        
        $url2 = "";
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
            $url2 .= '&page=' . $this->request->get['page'];
        }
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}

         if ($this->request->get['facilityids'] != null && $this->request->get['facilityids'] != "") {
            $url2 .= '&facilityids=' . $this->request->get['facilityids'];
        }
        if ($this->request->get['locationids'] != null && $this->request->get['locationids'] != "") {
            $url2 .= '&locationids=' . $this->request->get['locationids'];
        }
        
        if ($this->request->get['tagsids'] != null && $this->request->get['tagsids'] != "") {
            $url2 .= '&tagsids=' . $this->request->get['tagsids'];
        }

        if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
            $url2 .= '&userids=' . $this->request->get['tagsids'];
        }

        
        if ($this->request->get['notes_id']) {
            $notes_id = $this->request->get['notes_id'];
        } else {
            $notes_id = $this->request->get['updatenotes_id'];
        }
      //  $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
        
        // $this->data['notes_id'] = $this->request->get['notes_id'];
        $this->data['updatenotes_id'] = $notes_id;
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclientsign', '' . $url2, 'SSL'));
        
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
        
        if (isset($this->request->post['emp_tag_id'])) {
            $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
        } elseif (! empty($notes_info)) {
            $this->data['emp_tag_id'] = $notes_info['emp_tag_id'];
        } else {
            $this->data['emp_tag_id'] = '';
        }
        
        if (isset($this->request->post['tags_id'])) {
            $this->data['tags_id'] = $this->request->post['tags_id'];
        } elseif (! empty($notes_info)) {
            $this->data['tags_id'] = $notes_info['tags_id'];
        } else {
            $this->data['tags_id'] = '';
        }
        
        if (isset($this->request->post['emp_tag_id_2'])) {
            $this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
        } else {
            $this->data['emp_tag_id_2'] = '';
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

    public function printform ()
    {
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            
            $this->load->model('setting/tags');
            $taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            // var_dump($taginfo);
        }
        
        $tag_info = array();
        $tag_info['upload_file'] = $taginfo['upload_file'];
        $tag_info['ssn'] = $taginfo['ssn'];
        $tag_info['emp_extid'] = $taginfo['emp_extid'];
        $tag_info['emp_first_name'] = $taginfo['emp_first_name'];
        $tag_info['emp_last_name'] = $taginfo['emp_last_name'];
        $tag_info['emergency_contact'] = $taginfo['emergency_contact'];
        $tag_info['location_address'] = $taginfo['location_address'];
        $tag_info['address_street2'] = $taginfo['address_street2'];
        $tag_info['city'] = $taginfo['city'];
        $tag_info['state'] = $taginfo['state'];
        $tag_info['zipcode'] = $taginfo['zipcode'];
        $tag_info['person_screening'] = $taginfo['person_screening'];
        $tag_info['city'] = $taginfo['city'];
        $tag_info['tagstatus'] = $taginfo['tagstatus'];
        $tag_info['med_mental_health'] = $taginfo['med_mental_health'];
        $tag_info['constant_sight'] = $taginfo['constant_sight'];
        $tag_info['alert_info'] = $taginfo['alert_info'];
        $tag_info['prescription'] = $taginfo['prescription'];
        $tag_info['restriction_notes'] = $taginfo['restriction_notes'];
        $tag_info['tags_status_in'] = $taginfo['tags_status_in'];
        $tag_info['referred_facility'] = $taginfo['referred_facility'];
        
        if ($taginfo['date_of_screening'] != "0000-00-00") {
            $tag_info['date_of_screening'] = date('m-d-Y', strtotime($taginfo['date_of_screening']));
        }
        
        if ($taginfo['room']) {
            $this->load->model('setting/locations');
            $tags_info12 = $this->model_setting_locations->getlocation($taginfo['room']);
            
            $tag_info['room'] = $tags_info12['location_name'];
        }
        
        if ($taginfo['dob'] != "0000-00-00") {
            $tag_info['dob'] = date('m-d-Y', strtotime($taginfo['dob']));
        }
        
        $this->load->model('facilities/facilities');
        $facilityinfo = $this->model_facilities_facilities->getfacilities($taginfo['facilities_id']);
        
        $tag_info['faciility_name'] = $facilityinfo['facility'];
        
        if ($taginfo['gender']) {
            $this->load->model('notes/notes');
            
            $customlistvalue = $this->model_notes_notes->getcustomlistvalue($taginfo['customlistvalues_id']);
            
            $tag_info['customlistvalues_name'] = $customlistvalue['customlistvalues_name'];
        }
        
        $this->document->setTitle('Client Form');
        require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
        // create new PDF document
        $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('REPORT');
        $pdf->SetSubject('REPORT');
        $pdf->SetKeywords('REPORT');
        
        $pdf->SetMargins('5', '5', '5');
        $pdf->SetHeaderMargin('5');
        $pdf->SetFooterMargin('5');
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once (dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();
        
        $template = new Template();
        $template->data['formdatas'] = $tag_info;
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/form/tages_print.php')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/form/tages_print.php');
        }
        
		//echo $html;
        // var_dump($html);
        // die;
        // output the HTML content
        $pdf->writeHTML($html, true, 0, true, 0);
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
        // reset pointer to the last page
        $pdf->lastPage();
        
        // ---------------------------------------------------------
        
        // Close and output PDF document
        // $pdf->Output('example_049.pdf', 'D');
        
        $pdf->Output('report_' . rand() . '.pdf', 'I');
        exit();
		
    }

    public function printmedicationform ()
    {
        $this->language->load('notes/notes');
        $this->load->model('setting/tags');
        $this->load->model('form/form');
        $this->load->model('resident/resident');
        
        $medication_form = array();
        
        if ($this->request->get['tags_id']) {
            $tags_id = $this->request->get['tags_id'];
        } elseif ($this->request->post['emp_tag_id']) {
            $tags_id = $this->request->post['emp_tag_id'];
        }
        
        $tag_info = $this->model_setting_tags->getTag($tags_id);
        
        $medication_form['name'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        
        $medication_form['tag_info'] = $tag_info;
        
        if ($this->request->get['tags_id']) {
            
            $muduled = $this->model_resident_resident->gettagModule($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $medication_form['modules'] = $muduled['new_module'];
        } elseif ($this->request->post['emp_tag_id']) {
            
            $muduled = $this->model_resident_resident->gettagModule($this->request->post['emp_tag_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $medication_form['modules'] = $muduled['new_module'];
        } else {
            $medication_form['modules'] = array();
        }
        
        if ($this->request->get['tags_id']) {
            
            $medicine_info = $this->model_resident_resident->gettagmedicine($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $medication_form['medication_fields'] = unserialize($medicine_info['medication_fields']);
        } elseif ($this->request->post['emp_tag_id']) {
            
            $medicine_info = $this->model_resident_resident->gettagmedicine($this->request->post['emp_tag_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
            
            $medication_form['medication_fields'] = unserialize($medicine_info['medication_fields']);
        } else {
            $medication_form['medication_fields'] = array();
        }
        
        $this->document->setTitle('Health Form');
        require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
        // create new PDF document
        $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('REPORT');
        $pdf->SetSubject('REPORT');
        $pdf->SetKeywords('REPORT');
        
        $pdf->SetMargins('5', '5', '5');
        $pdf->SetHeaderMargin('5');
        $pdf->SetFooterMargin('5');
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once (dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();
        
        $template = new Template();
        $template->data['formdatas'] = $medication_form;
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/form/printmedication.php')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/form/printmedication.php');
        }
        //echo $html;
        // var_dump($html);
        // die;
        // output the HTML content
        $pdf->writeHTML($html, true, 0, true, 0);
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
        // reset pointer to the last page
        $pdf->lastPage();
        
        // ---------------------------------------------------------
        
        // Close and output PDF document
        // $pdf->Output('example_049.pdf', 'D');
        
        $pdf->Output('report_' . rand() . '.pdf', 'I');
        exit();
		
    }

    public function exitscreening ()
    {
        $url31 = "";
        
		if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
             $this->data['forms_design_id'] =  $this->request->get['forms_design_id'];
			$url31 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
        }

        if ($this->request->get['emp_extid'] != null && $this->request->get['emp_extid'] != "") {
            $url31 .= '&emp_extid=' . $this->request->get['emp_extid'];
        }
        if ($this->request->get['ssn'] != null && $this->request->get['ssn'] != "") {
            $url31 .= '&ssn=' . $this->request->get['ssn'];
        }
        if ($this->request->get['emp_first_name'] != null && $this->request->get['emp_first_name'] != "") {
            $url31 .= '&emp_first_name=' . $this->request->get['emp_first_name'];
        }
        if ($this->request->get['emp_last_name'] != null && $this->request->get['emp_last_name'] != "") {
            $url31 .= '&emp_last_name=' . $this->request->get['emp_last_name'];
        }
        if ($this->request->get['dob'] != null && $this->request->get['dob'] != "") {
            
            $dob111 = $this->request->get['dob'];
            
            $date = str_replace('-', '/', $dob111);
            
            $res = explode("/", $date);
            $createdate1 = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            $dob = date('Y-m-d', strtotime($createdate1));
            
            $url31 .= '&dob=' . $this->request->get['dob'];
        }
        
        if (($this->request->post['form_submit'] == '1') && $this->validateexitstags()) {
            
            $this->data['exittags_id'] = $this->request->post['exittags_id'];
            $this->data['incident_number'] = $this->request->post['incident_number'];
            $this->data['select_exittags_id'] = '1';
            
            $this->load->model('form/form');
            $tag_info11 = $this->model_form_form->getFormDatas($this->request->post['exittags_id']);
			
			$design_forms = unserialize($tag_info11['design_forms']);
			
			//var_dump($tag_info);
			
			$clientname = "";
            if ($design_forms[0][0]['' . TAG_FNAME . ''] != null && $design_forms[0][0]['' . TAG_FNAME . ''] != "") {
                $clientname = $design_forms[0][0]['' . TAG_FNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_MNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms[0][0]['' . TAG_DOB . ''] . ' | Screening ' . $design_forms[0][0]['' . TAG_SCREENING . ''];
            } else {
                $clientname = $tag_info11['incident_number'] . ' ' . date('m-d-Y', strtotime($tag_info11['date_added']));
            }
            
            if ($design_forms[0][0]['' . TAG_SCREENING . ''] != "0000-00-00") {
                $date_of_screening = $design_forms[0][0]['' . TAG_SCREENING . ''];
            } else {
                $date_of_screening = date('m-d-Y');
            }
            if ($design_forms[0][0]['' . TAG_DOB . ''] != "0000-00-00") {
                $dob = $design_forms[0][0]['' . TAG_DOB . ''];
				
				$res2 = explode("-", $design_forms[0][0]['' . TAG_DOB . '']);
				$dob222 = $res2[2]."-".$res2[0]."-".$res2[1];
				
				$dobm = $res2[0];
				$dobd = $res2[1];
				$doby = $res2[2];
		
            } else {
                $dob = '';
				$dobm = '';
				$dobd = '';
				$doby = '';
            }
            
           
            
            /*if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Male') {
                $gender = '33';
            }
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Female') {
                $gender = '34';
            }
            
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Inmate') {
                $gender = '35';
            }
            
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Patient') {
                $gender = '49';
            }
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Other') {
                $gender = '49';
            }*/
            
            $this->data['tag_info'] = array(
                    'incident_number' => $clientname,
                    'custom_form_type' => $tag_info11['custom_form_type'],
                    'forms_id' => $tag_info11['forms_id'],
                    'emp_first_name' => $design_forms[0][0]['' . TAG_FNAME . ''],
                    'emp_middle_name' => $design_forms[0][0]['' . TAG_MNAME . ''],
                    'emp_last_name' => $design_forms[0][0]['' . TAG_LNAME . ''],
                    'emergency_contact' => $design_forms[0][0]['' . TAG_PHONE . ''],
                    'dob' => $dob,
                    'month' => $dobm,
                    'date' => $dobd,
                    'year' => $doby,
                    'age' => $design_forms[0][0]['' . TAG_AGE . ''],
                    'gender' => $design_forms[0][0]['' . TAG_GENDER . ''],
                    'location_address' => $design_forms[0][0]['' . TAG_ADDRESS . ''],
                    'address_street2' => '', // $design_forms[0][0]['text_75675662'],
                    'person_screening' => $notes_info['user_id'],
                    'date_of_screening' => $date_of_screening,
                    'ssn' => $design_forms[0][0]['' . TAG_SSN . ''],
                    // 'state'=> $design_forms[0][0]['text_49932949'],
                    // 'city'=> $design_forms[0][0]['text_36668004'],
                    // 'zipcode'=> $design_forms[0][0]['text_64928499'],
                    'emp_extid' => $design_forms[0][0]['' . TAG_EXTID . ''],
                    'upload_file' => $upload_file,
                    'image_url1' => $image_url1,
                    'form_date_added' => date('m-d-Y', strtotime($tag_info11['date_added'])),
                    
                   
            );
			
			
        }
        
        if ($this->request->get['client_add_new'] == '1') {
            $this->data['client_add_new'] = $this->request->get['client_add_new'];
        }
        
        $this->load->model('form/form');
        $this->load->model('notes/notes');
        if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            $facilities_id = $this->customer->getId();
        }
        
        $this->data['action'] = $this->url->link('notes/tags/exitscreening', $url31, true);
        $this->data['add_new_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/exitscreening&client_add_new=1', '' . $url31, 'SSL'));
        
        $fdata['forms_fields_values'] = array(
                '' . TAG_EXTID . '' => $this->request->get['emp_extid'],
                '' . TAG_SSN . '' => $this->request->get['ssn'],
                '' . TAG_FNAME . '' => $this->request->get['emp_first_name'],
                '' . TAG_LNAME . '' => $this->request->get['emp_last_name']
        );
        // 'date_70767270' => $this->request->get['dob'],
        
        $results = $this->model_form_form->getscrnneningFormdata($fdata, $facilities_id);
        
        foreach ($results as $result) {
            $design_forms = unserialize($result['design_forms']);
            
            $notes_info = $this->model_notes_notes->getnotes($result['notes_id']);
            
            // echo "<hr>";
            
            $clientname = "";
            if ($design_forms[0][0]['' . TAG_FNAME . ''] != null && $design_forms[0][0]['' . TAG_FNAME . ''] != "") {
                $clientname = $design_forms[0][0]['' . TAG_FNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_MNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_LNAME . ''] . ' | DOB ' . $design_forms[0][0]['' . TAG_DOB . ''] . ' | Screening ' . $design_forms[0][0]['' . TAG_SCREENING . ''];
            } else {
                $clientname = $result['incident_number'] . ' ' . date('m-d-Y', strtotime($result['date_added']));
            }
            
            if ($design_forms[0][0]['' . TAG_SCREENING . ''] != "0000-00-00") {
                $date_of_screening = $design_forms[0][0]['' . TAG_SCREENING . ''];
            } else {
                $date_of_screening = date('m-d-Y');
            }
            if ($design_forms[0][0]['' . TAG_DOB . ''] != "0000-00-00") {
                $dob = $design_forms[0][0]['' . TAG_DOB . ''];
				
				$res2 = explode("-", $design_forms[0][0]['' . TAG_DOB . '']);
				$dob222 = $res2[2]."-".$res2[0]."-".$res2[1];
				
				$dobm = $res2[0];
				$dobd = $res2[1];
				$doby = $res2[2];
		
            } else {
                $dob = '';
				$dobm = '';
				$dobd = '';
				$doby = '';
            }
            
            /*if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Male') {
                $gender = '33';
            }
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Female') {
                $gender = '34';
            }
            
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Inmate') {
                $gender = '35';
            }
            
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Patient') {
                $gender = '49';
            }
            if ($design_forms[0][0]['' . TAG_GENDER . ''] == 'Other') {
                $gender = '49';
            }*/
            
            $this->data['forms'][] = array(
                    'incident_number' => $clientname,
                    'custom_form_type' => $result['custom_form_type'],
                    'forms_id' => $result['forms_id'],
                    'emp_first_name' => $design_forms[0][0]['' . TAG_FNAME . ''],
                    'emp_middle_name' => $design_forms[0][0]['' . TAG_MNAME . ''],
                    'emp_last_name' => $design_forms[0][0]['' . TAG_LNAME . ''],
                    'emergency_contact' => $design_forms[0][0]['' . TAG_PHONE . ''],
                    'dob' => $dob,
                    'month' => $dobm,
                    'date' => $dobd,
                    'year' => $doby,
                    'age' => $design_forms[0][0]['' . TAG_AGE . ''],
                    'gender' => $design_forms[0][0]['' . TAG_GENDER . ''],
                    'location_address' => $design_forms[0][0]['' . TAG_ADDRESS . ''],
                    'address_street2' => '', // $design_forms[0][0]['text_75675662'],
                    'person_screening' => $notes_info['user_id'],
                    'date_of_screening' => $date_of_screening,
                    'ssn' => $design_forms[0][0]['' . TAG_SSN . ''],
                    // 'state'=> $design_forms[0][0]['text_49932949'],
                    // 'city'=> $design_forms[0][0]['text_36668004'],
                    // 'zipcode'=> $design_forms[0][0]['text_64928499'],
                    'emp_extid' => $design_forms[0][0]['' . TAG_EXTID . ''],
                    'upload_file' => $upload_file,
                    'image_url1' => $image_url1,
                    'form_date_added' => date('m-d-Y', strtotime($result['date_added'])),
                    
                    'date_added' => date('m-d-Y', strtotime($notes_info['date_added'])),
                    'signature' => $notes_info['signature'],
                    'notes_pin' => $notes_info['notes_pin'],
                    'notes_type' => $notes_info['notes_type'],
                    'username' => $notes_info['user_id']
            );
        }
        
        if (isset($this->session->data['success2'])) {
            $this->data['success2'] = $this->session->data['success2'];
            
            unset($this->session->data['success2']);
        } else {
            $this->data['success2'] = '';
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
        
        if (isset($this->request->post['exittags_id'])) {
            $this->data['exittags_id'] = $this->request->post['exittags_id'];
        } else {
            $this->data['exittags_id'] = '';
        }
        if (isset($this->request->post['incident_number'])) {
            $this->data['incident_number'] = $this->request->post['incident_number'];
        } else {
            $this->data['incident_number'] = '';
        }
        
        $this->template = $this->config->get('config_template') . '/template/form/tags_exitform.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validateexitstags ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['exittags_id'] == null && $this->request->post['exittags_id'] == "") {
            $this->error['warning'] = 'Please select Form';
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
	
	public function viewallpicture ()
    {
		if($this->customer->getId()){
			$this->load->model('facilities/online');
			$datafa = array();
			$datafa['username'] = $this->session->data['webuser_id'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->customer->getId();
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);
        }
        
       
            $this->load->model('setting/tags');
            $this->load->model('resident/resident');
            $this->load->model('form/form');
            
			if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
                $facilities_id = $this->request->get['facilities_id'];
            } else {
                $facilities_id = $this->customer->getId();
            }
			
			if (($this->request->post['enroll_image1'] == '1') && $this->validatepictureForm() ){
				foreach($this->request->post['enroll_image'] as $img_a){
				
					$img = $img_a;
					$img = str_replace('data:image/jpeg;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$Imgdata = base64_decode($img);
					
					$notes_file = uniqid() . '.jpeg';
					
					$file = DIR_IMAGE .'facerecognition/' . $notes_file;
					$success = file_put_contents($file, $Imgdata);
					
					$outputFolder = $file;
					
					
					$outputFolderUrl = HTTP_SERVER.'image/facerecognition/' . $notes_file;
					
					$s3file = $this->awsimageconfig->uploadFile($notes_file, $outputFolder);
					
					
					$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
					
					if ($facilities_info['is_client_facial'] == '1') {
						
						
						if ($this->request->get['tags_id'] != '' && $this->request->get['tags_id'] != null) {
							$tags_id = $this->request->get['tags_id'];
							
							$this->load->model('setting/tags');
							$taginfo_a = $this->model_setting_tags->getTag($tags_id);
							
							if($taginfo_a['emp_tag_id'] != null && $taginfo_a['emp_tag_id'] != ""){
								$femp_tag_id = $taginfo_a['emp_tag_id'];
								
								$outputFolderUrl = $s3file;
								//require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_tags_config.php');
								
								$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag($outputFolderUrl, $femp_tag_id,$facilities_id);
						
								foreach($result_inser_user_img22['FaceRecords'] as $b){
									$FaceId = $b['Face']['FaceId'];
									$ImageId = $b['Face']['ImageId'];
								}
								
								$this->model_setting_tags->insertTagimageenroll($tags_id, $FaceId, $ImageId, $s3file, $facilities_id);
							}
						}
						
					}else{
						
						$tags_id = $this->request->get['tags_id'];
						
						$date_added = date('Y-m-d H:i:s', strtotime('now'));
						
						$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape($s3file) . "',tags_id = '" . $this->db->escape($tags_id) . "',FaceId = '" . $this->db->escape($FaceId) . "', ImageId = '" . $this->db->escape($ImageId) . "', date_added = '".$date_added."', date_updated = '".$date_added."' ";
				
						$this->db->query($tsql);
					}
					
					
				}
			}
           
            $data = array(
                'facilities_id' => $facilities_id,
                'tags_id' => $this->request->get['tags_id'],
            );
           
            $results = $this->model_setting_tags->getTagimages($this->request->get['tags_id']);
           
            foreach ($results as $result) {
                
                 $this->data['allpictures'][] = array(
                        'tags_id' => $result['tags_id'],
                        'enroll_image' => $result['enroll_image'],
                        'upload_file_thumb' => $result['upload_file_thumb'],
                        'FaceId' => $result['FaceId'],
                        'ImageId' => $result['ImageId'],
                        'tags_enroll_id' => $result['tags_enroll_id'],
						'delete_href' => $this->url->link('notes/tags/deletepicture', '' . '&tags_id=' . $result['tags_id'] . '&tags_enroll_id=' . $result['tags_enroll_id'], 'SSL')
                );
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
            
        $this->template = $this->config->get('config_template') . '/template/resident/tags_pictureform.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }
	
	public function validatepictureForm ()
    {
       $facilities_id = $this->customer->getId();
		if (!empty($this->request->post['enroll_image'])) {
			if ($this->request->post['enroll_image1'] == '1') {
				foreach($this->request->post['enroll_image'] as $img_a){
					$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImage($img_a,$facilities_id);
					foreach($result_inser_user_img22['FaceMatches'] as $c){
						$similarity = $c['Similarity'];
						$FaceId[] = $c['Face']['FaceId'];
						$ImageId[] = $c['Face']['ImageId'];
						$ExternalImageId = $c['Face']['ExternalImageId'];
					}
					
					if($ExternalImageId != null && $ExternalImageId != ""){
						$this->load->model('setting/tags');
						$taginfo_a = $this->model_setting_tags->getTagbyEMPID($ExternalImageId);
						if($taginfo_a['emp_tag_id'] != $ExternalImageId){
							$this->error['warning'] = 'This '.$taginfo_a['emp_tag_id'].' is already enrolled!';
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
	
	public function deletepicture(){
		if($this->request->get['tags_id']){
			 $this->load->model('setting/tags');
			$this->model_setting_tags->deleteTagimage($this->request->get['tags_enroll_id']);
			$this->session->data['success'] = "picture delete successfully!";
			$this->redirect($this->url->link('notes/tags/viewallpicture', '' . '&tags_id=' . $this->request->get['tags_id'] . '&tags_enroll_id=' . $this->request->get['tags_enroll_id'], 'SSL'));
		}
	}
	
	public function getLocationByFacility(){
     $json = array();
        $this->load->model('setting/locations');    
        $data = array(
                'location_name' => $this->request->get['filter_name'],
                'facilities_id' => $this->request->get['facilities_id'],
                'status' => '1',
                'sort' => 'task_form_name',
                'order' => 'ASC'
        );
        
        $rresults = $this->model_setting_locations->getlocations($data);      
        
        foreach ($rresults as $result) {
            
             $json[] = array(
                    'locations_id' => $result['locations_id'],
                    'location_name' => $result['location_name'],
                    'date_added' => $result['date_added']
            );
        }

      $this->response->setOutput(json_encode($json));
   }
   
}