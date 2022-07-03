<?php

class Controllercasecreatetask extends Controller
{

    private $error = array();

    public function headertasklist ()
    {
        if ($this->request->get['tags_id'] != '') {
            $this->load->model('setting/tags');
            $distag_info = $this->model_setting_tags->getTagbycheck($this->request->get['tags_id']);
            
            $this->data['distag_info'] = $distag_info;
            
            // var_dump($this->data['distag_info']);
        }
        
        // if($this->request->get['route'] != "resident/cases/dashboard2" &&
        // $this->request->get['route'] != "notes/notes/insert" ){
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            $this->data['tags_id'] = $this->request->get['tags_id'];
            
            $this->load->model('setting/tags');
            
            if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != "") {
                
                $taginfo = $this->model_setting_tags->getTag($this->request->get['tags_id']);
                
                $this->data['tag_info'] = $taginfo['emp_tag_id'] . ' : ' . $taginfo['emp_first_name'] . ' ' . $taginfo['emp_last_name'];
                
                $this->data['taginfo'] = $taginfo;
            }
        }
        // }
        
        if (($this->request->post['note_date_search'] == '1')) {
            $url = "";
            if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
                $url .= '&searchdate=' . $this->request->post['searchdate'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            $this->redirect($this->url->link('common/home', '' . $url, 'SSL'));
        }
        
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        $this->data['notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url2, 'SSL');
        $this->data['notes_url_close'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url2, 'SSL');
        $this->data['support_url'] = $this->url->link('notes/support', '' . $url2, 'SSL');
        $this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url2, 'SSL');
        
        $this->data['taskrefress'] = $this->request->get['taskrefress'];
        
        $this->data['createtask_url'] = $this->url->link('notes/createtask', '' . $url2, 'SSL');
        $this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '' . $url2, 'SSL');
        
        $this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '' . $url2, 'SSL');
        $this->data['addtasktask_url'] = $this->url->link('notes/notes/index', '' . $url2, 'SSL');
        $this->data['inserttask_url'] = $this->url->link('notes/createtask/inserttask', '' . $url2, 'SSL');
        
        $this->data['checklist_url'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/checklistform', '' . $url2, 'SSL'));
        $this->data['incident_url'] = str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert', '' . $url2, 'SSL'));
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL'));
        
        $this->data['reviewnoted_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/reviewNotes', '' . $url2, 'SSL'));
        
        $this->data['custom_form_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        $this->data['update_strike_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrike', '' . $url2, 'SSL'));
        $this->data['update_strike_url_private'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrikeprivate', '' . $url2, 'SSL'));
        $this->data['alarm_url'] = $this->url->link('notes/notes/setAlarm', '', 'SSL');
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        if (isset($this->request->get['searchdate'])) {
            $res = explode("-", $this->request->get['searchdate']);
            $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
            $currentdate = $createdate1;
            
            $this->data['searchdate'] = $this->request->get['searchdate'];
        } else {
            $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
            
            $currentdate = date('d-m-Y');
            $this->data['searchdate'] = date('m-d-Y');
        }
        
        // $addTime = $this->config->get('config_task_complete');
        
        /*
         * if($config_task_complete == '5min'){
         * $addTime = '5';
         * }else
         * if($config_task_complete == '10min'){
         * $addTime = '10';
         * }
         * else
         * if($config_task_complete == '15min'){
         * $addTime = '15';
         * }else
         * if($config_task_complete == '20min'){
         * $addTime = '20';
         * }else
         * if($config_task_complete == '25min'){
         * $addTime = '25';
         * }else
         * if($config_task_complete == '30min'){
         * $addTime = '30';
         * }else
         * if($config_task_complete == '45min'){
         * $addTime = '45';
         * }else
         * if($config_task_complete == '45min'){
         * $addTime = '45';
         * }
         */
        
        $this->data['deleteTime'] = $deleteTime;
        
        $this->load->model('createtask/createtask');
        $top = '1';
        $listtasks = $this->model_createtask_createtask->getTasklist($this->customer->getUId(), $currentdate, $top, $this->request->get['tags_id']);
        
        // $this->data['taskTotal'] =
        // $this->model_createtask_createtask->getCountTasklist($this->customer->getUId(),
        // $currentdate, $top, '', $this->request->get['tags_id']);
        
        // var_dump($this->data['taskTotal']);
        
        // date_default_timezone_set($this->session->data['time_zone_1']);
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        $currenttime = date('H:i:s', strtotime('now'));
        
        $currentdate = date('Y-m-d', strtotime('now'));
        /*
         * var_dump($currenttime);
         * echo "<hr>";
         * var_dump($currenttimePlus);
         * echo "<hr>";
         */
        
        $this->load->model('setting/locations');
        $this->load->model('setting/tags');
        
        foreach ($listtasks as $list) {
            
            $taskstarttime = date('H:i:s', strtotime($list['task_time']));
            
            $tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($list['tasktype'],$list['facilityId']);
            
            if ($tasktype_info['custom_completion_rule'] == '1') {
                $addTime = $tasktype_info['config_task_complete'];
            } else {
                $addTime = $this->config->get('config_task_complete');
            }
            
            $currenttimePlus = date('H:i:s', strtotime(' +' . $addTime . ' minutes', strtotime('now')));
            
            // var_dump($currenttimePlus);
            // echo "<hr>=====";
            
            // echo $currenttimePlus .' >= '. $taskstarttime ;
            
            if ($currenttimePlus >= $taskstarttime) {
                $taskDuration = '1';
            } else {
                $taskDuration = '2';
            }
            
            $bedcheckdata = array();
            
            if ($list['task_form_id'] != 0 && $list['task_form_id'] != NULL) {
                
                $formDatas = $this->model_setting_locations->getformid($list['task_form_id']);
                
                foreach ($formDatas as $formData) {
                    
                    $locData = $this->model_setting_locations->getlocation($formData['locations_id']);
                    
                    $locationDatab = array();
                    $location_type = "";
                    
                    $location_typea = $locData['location_type'];
                    if ($location_typea == '1') {
                        $location_type .= "Boys";
                    }
                    
                    if ($location_typea == '2') {
                        $location_type .= "Girls";
                    }
                    
                    if ($location_typea == '3') {
                        $location_type .= "Inmates";
                    }
                    
                    if ($locData['upload_file'] != null && $locData['upload_file'] != "") {
                        $upload_file = $locData['upload_file'];
                    } else {
                        $upload_file = "";
                    }
                    $locationDatab[] = array(
                            'locations_id' => $locData['locations_id'],
                            'location_name' => $locData['location_name'],
                            'location_address' => $locData['location_address'],
                            'location_detail' => $locData['location_detail'],
                            'capacity' => $locData['capacity'],
                            'location_type' => $location_type,
                            'upload_file' => $upload_file,
                            'nfc_location_tag' => $locData['nfc_location_tag'],
                            'nfc_location_tag_required' => $locData['nfc_location_tag_required'],
                            'gps_location_tag' => $locData['gps_location_tag'],
                            'gps_location_tag_required' => $locData['gps_location_tag_required'],
                            'latitude' => $locData['latitude'],
                            'longitude' => $locData['longitude'],
                            'other_location_tag' => $locData['other_location_tag'],
                            'other_location_tag_required' => $locData['other_location_tag_required'],
                            'other_type_id' => $locData['other_type_id'],
                            'facilities_id' => $locData['facilities_id']
                    )
                    ;
                    
                    $bedcheckdata[] = array(
                            'task_form_location_id' => $formData['task_form_location_id'],
                            'location_name' => $formData['location_name'],
                            'location_detail' => $formData['location_detail'],
                            'current_occupency' => $formData['current_occupency'],
                            'bedcheck_locations' => $locationDatab
                    );
                }
                
                /*
                 * $this->load->model('setting/bedchecktaskform');
                 * $taskformData =
                 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
                 *
                 * foreach($taskformData as $frmData){
                 * $taskformsData[] = array(
                 * 'task_form_name' =>$frmData['task_form_name'],
                 * 'facilities_id' =>$frmData['facilities_id'],
                 * 'form_type' =>$frmData['form_type']
                 * );
                 * }
                 */
            }
            
            $medications = array();
            
            /*
             * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
             * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
             * $locationData = array();
             * $locData =
             * $this->model_setting_locations->getlocation($tags_info['locations_id']);
             *
             * $locationData[] = array(
             * 'locations_id' =>$locData['locations_id'],
             * 'location_name' =>$locData['location_name'],
             * 'location_address' =>$locData['location_address'],
             * 'location_detail' =>$locData['location_detail'],
             * 'capacity' =>$locData['capacity'],
             * 'location_type' =>$locData['location_type'],
             * 'nfc_location_tag' =>$locData['nfc_location_tag'],
             * 'nfc_location_tag_required'
             * =>$locData['nfc_location_tag_required'],
             * 'gps_location_tag' =>$locData['gps_location_tag'],
             * 'gps_location_tag_required'
             * =>$locData['gps_location_tag_required'],
             * 'latitude' =>$locData['latitude'],
             * 'longitude' =>$locData['longitude'],
             * 'other_location_tag' =>$locData['other_location_tag'],
             * 'other_location_tag_required'
             * =>$locData['other_location_tag_required'],
             * 'other_type_id' =>$locData['other_type_id'],
             * 'facilities_id' =>$locData['facilities_id']
             *
             * );
             *
             *
             * if($tags_info['upload_file'] != null && $tags_info['upload_file']
             * != ""){
             * $upload_file2 = $tags_info['upload_file'];
             * }else{
             * $upload_file2 = "";
             * }
             *
             *
             *
             * $drugDatas = $this->model_setting_tags->getDrugs($list['id']);
             * $drugaData = array();
             * foreach($drugDatas as $drugData){
             * $drugaData[] = array(
             * 'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
             * 'facilities_id' =>$drugData['facilities_id'],
             * 'locations_id' =>$drugData['locations_id'],
             * 'tags_id' =>$drugData['tags_id'],
             * 'medication_id' =>$drugData['medication_id'],
             * 'drug_name' =>$drugData['drug_name'],
             * 'dose' =>$drugData['dose'],
             * 'drug_type' =>$drugData['drug_type'],
             * 'quantity' =>$drugData['quantity'],
             * 'frequency' =>$drugData['frequency'],
             * 'start_time' =>$drugData['start_time'],
             * 'instructions' =>$drugData['instructions'],
             * 'count' =>$drugData['count'],
             * 'complete_status' =>$drugData['complete_status'],
             * 'upload_file' =>$upload_file2,
             * );
             * }
             *
             *
             * $medications[] = array(
             * 'tags_id' =>$tags_info['tags_id'],
             * 'upload_file' =>$upload_file2,
             * 'emp_tag_id' =>$tags_info['emp_tag_id'],
             * 'emp_first_name' =>$tags_info['emp_first_name'],
             * 'emp_last_name' =>$tags_info['emp_last_name'],
             * 'doctor_name' =>$tags_info['doctor_name'],
             * 'emergency_contact' =>$tags_info['emergency_contact'],
             * 'dob' =>$tags_info['dob'],
             * 'medications_locations' =>$locationData,
             * 'medications_drugs' =>$drugaData
             * );
             *
             *
             *
             *
             * }
             */
            
            $this->data['transport_tags'] = array();
            $this->load->model('setting/tags');
            
            if (! empty($list['transport_tags'])) {
                $transport_tags1 = explode(',', $list['transport_tags']);
            } else {
                $transport_tags1 = array();
            }
            
            foreach ($transport_tags1 as $tag1) {
                $tags_info = $this->model_setting_tags->getTag($tag1);
                
                if ($tags_info['emp_first_name']) {
                    $emp_tag_id = $tags_info['emp_tag_id'] . ': ' . $tags_info['emp_first_name'] . ' ' . $tags_info['emp_last_name'];
                } else {
                    $emp_tag_id = $tags_info['emp_tag_id'];
                }
                
                if ($tags_info) {
                    $transport_tags[] = array(
                            'tags_id' => $tags_info['tags_id'],
                            'emp_tag_id' => $emp_tag_id
                    );
                }
            }
            
            $medication_tags = array();
            $this->data['medication_tags'] = array();
            $this->load->model('setting/tags');
            
            if (! empty($list['medication_tags'])) {
                $medication_tags1 = explode(',', $list['medication_tags']);
            } else {
                $medication_tags1 = array();
            }
            
            foreach ($medication_tags1 as $medicationtag) {
                $tags_info1 = $this->model_setting_tags->getTag($medicationtag);
                
                if ($tags_info1['emp_first_name']) {
                    $emp_tag_id = $tags_info1['emp_tag_id'] . ': ' . $tags_info1['emp_first_name'] . ' ' . $tags_info1['emp_last_name'];
                } else {
                    $emp_tag_id = $tags_info1['emp_tag_id'];
                }
                
                if ($tags_info1) {
                    
                    $drugs = array();
                    
                    $mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID($list['id'], $medicationtag);
                    
                    foreach ($mdrugs as $mdrug) {
                        
                        $mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID($mdrug['tags_medication_details_id']);
                        
                        $drugs[] = array(
                                'drug_name' => $mdrug_info['drug_name']
                        );
                    }
                    
                    $medication_tags[] = array(
                            'tags_id' => $tags_info1['tags_id'],
                            'emp_tag_id' => $emp_tag_id,
                            'tagsmedications' => $drugs
                    );
                }
            }
            
            if ($list['visitation_tag_id']) {
                $visitation_tag = $this->model_setting_tags->getTag($list['visitation_tag_id']);
                
                if ($visitation_tag['emp_first_name']) {
                    $visitation_tag_id = $visitation_tag['emp_tag_id'] . ': ' . $visitation_tag['emp_first_name'] . ' ' . $visitation_tag['emp_last_name'];
                } else {
                    $visitation_tag_id = $visitation_tag['emp_tag_id'];
                }
            } else {
                $visitation_tag_id = "";
            }
            
            $this->data['listtask'][] = array(
                    'assign_to' => $list['assign_to'],
                    'attachement_form' => $list['attachement_form'],
                    'tasktype_form_id' => $list['tasktype_form_id'],
                    'tasktype' => $list['tasktype'],
                    'send_notification' => $list['send_notification'],
                    'checklist' => $list['checklist'],
                    'date' => date('j, M Y', strtotime($list['task_date'])),
                    'end_recurrence_date' => date('j, M Y', strtotime($list['end_recurrence_date'])),
                    'id' => $list['id'],
                    'description' => $list['description'],
                    'taskDuration' => $taskDuration,
                    'task_time' => date('h:i A', strtotime($list['task_time'])),
                    'task_form_id' => $list['task_form_id'],
                    'tags_id' => $list['tags_id'],
                    'pickup_facilities_id' => $list['pickup_facilities_id'],
                    'pickup_locations_address' => $list['pickup_locations_address'],
                    'pickup_locations_time' => date('h:i A', strtotime($list['pickup_locations_time'])),
                    'pickup_locations_latitude' => $list['pickup_locations_latitude'],
                    'pickup_locations_longitude' => $list['pickup_locations_longitude'],
                    'dropoff_facilities_id' => $list['dropoff_facilities_id'],
                    'dropoff_locations_address' => $list['dropoff_locations_address'],
                    'dropoff_locations_time' => date('h:i A', strtotime($list['dropoff_locations_time'])),
                    'dropoff_locations_latitude' => $list['dropoff_locations_latitude'],
                    'dropoff_locations_longitude' => $list['dropoff_locations_longitude'],
                    'transport_tags' => $transport_tags,
                    'medications' => $medications,
                    'bedchecks' => $bedcheckdata,
                    'medication_tags' => $medication_tags,
                    
                    'visitation_tags' => $list['visitation_tags'],
                    'visitation_tag_id' => $visitation_tag_id,
                    'visitation_start_facilities_id' => $list['visitation_start_facilities_id'],
                    'visitation_start_address' => $list['visitation_start_address'],
                    'visitation_start_time' => date('h:i A', strtotime($list['visitation_start_time'])),
                    'visitation_start_address_latitude' => $list['visitation_start_address_latitude'],
                    'visitation_start_address_longitude' => $list['visitation_start_address_longitude'],
                    'visitation_appoitment_facilities_id' => $list['visitation_appoitment_facilities_id'],
                    'visitation_appoitment_address' => $list['visitation_appoitment_address'],
                    'visitation_appoitment_time' => date('h:i A', strtotime($list['visitation_appoitment_time'])),
                    'visitation_appoitment_address_latitude' => $list['visitation_appoitment_address_latitude'],
                    'visitation_appoitment_address_longitude' => $list['visitation_appoitment_address_longitude']
            )
            ;
        }
        
        $this->template = $this->config->get('config_template') . '/template/case/tasklist.php';
        $this->response->setOutput($this->render());
    }
} 