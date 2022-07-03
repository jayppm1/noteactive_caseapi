<?php

class ControllerCaseCalendar extends Controller
{

    public function index ()
    {
        $this->language->load('common/home');
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        $this->data['heading_title'] = $this->config->get('config_title');
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $date = str_replace('-', '/', $this->request->get['note_date_from']);
            $res = explode("/", $date);
            
            $note_date_from = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            $this->data['note_date_from'] = $note_date_from;
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            
            $this->data['tagdata'] = $this->model_setting_tags->getTag($this->request->get['tags_id']);
            // var_dump($tagdata['emp_first_name']);
            // var_dump($tagdata['emp_last_name']);
        }
        
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $date = str_replace('-', '/', $this->request->get['note_date_to']);
            $res = explode("/", $date);
            $note_date_to = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            $this->data['note_date_to'] = $note_date_to;
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $noteTime = date('H:i:s');
            
            $date = str_replace('-', '/', $this->request->get['searchdate']);
            $res = explode("/", $date);
            $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date1'] = $changedDate . ' ' . $noteTime;
            $searchdate = $this->request->get['searchdate'];
            $this->data['searchdate'] = $this->request->get['searchdate'];
            
            $currentdate = $changedDate;
            
            if (($searchdate) >= (date('m-d-Y'))) {
                $this->data['back_date_check'] = "1";
            } else {
                $this->data['back_date_check'] = "2";
            }
        } else {
            $this->data['note_date1'] = date('Y-m-d H:i:s');
            
            if ($this->data['note_date_from'] != null && $this->data['note_date_from'] != "") {
                $this->data['searchdate'] = date('m-d-Y', strtotime($this->data['note_date_from']));
            } else {
                $this->data['searchdate'] = date('m-d-Y');
            }
            
            $searchdate = date('m-d-Y');
            
            $currentdate = date('d-m-Y');
        }
        
        // var_dump($this->data['searchdate']);
        
        if (isset($this->request->get['searchdate'])) {
            $res = explode("-", $this->request->get['searchdate']);
            $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
            $currentdate = $createdate1;
        } else {
            // $this->data['note_date'] = date('D F j, Y');//date('m-d-Y');
            
            $currentdate = date('d-m-Y');
        }
        
        /**
         * **********************
         */
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            $this->data['tags_id'] = $this->request->get['tags_id'];
            
            $this->load->model('setting/tags');
            
            if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != "") {
                
                $taginfo = $this->model_setting_tags->getTag($this->request->get['tags_id']);
                
                $this->data['tag_info'] = $taginfo['emp_tag_id'] . ' : ' . $taginfo['emp_first_name'] . ' ' . $taginfo['emp_last_name'];
            }
            
            if (($this->request->post['note_date_search'] == '1')) {
                $url = "";
                if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
                    $url .= '&searchdate=' . $this->request->post['searchdate'];
                }
                $url .= '&tags_id=' . $this->request->get['tags_id'];
                
                $this->redirect($this->url->link('notes/createtask/headertasklist', '' . $url, 'SSL'));
            }
        }
        
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            // $url2 .= '&search_tags=' . $this->request->get['search_tags'];
        }
        
        $this->data['notes_url_home'] = str_replace('&amp;', '&', $this->url->link('common/home', '' . $url2, 'SSL'));
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
            
            if ($this->data['note_date_from'] != null && $this->data['note_date_from'] != "") {
                $this->data['note_date'] = date('D F j, Y', strtotime($this->data['note_date_from']));
            } else {
                $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
            }
            
            $currentdate = date('d-m-Y');
            // $this->data['searchdate'] = date('m-d-Y');
        }
        
        $this->data['deleteTime'] = $deleteTime;
        
        $this->load->model('createtask/createtask');
        $top = '1';
        $listtasks = $this->model_createtask_createtask->getTasklist($this->customer->getId(), $currentdate1, $top, $this->request->get['tags_id1']);
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        $currenttime = date('H:i:s', strtotime('now'));
        
        $currentdate = date('Y-m-d', strtotime('now'));
        
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
            }
            
            $medications = array();
            
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
                    'date' => date('Y-m-d H:i:s', strtotime($list['task_date'])),
                    'end_recurrence_date' => date('Y-m-d H:i:s', strtotime($list['end_recurrence_date'])),
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
                    'visitation_appoitment_address_longitude' => $list['visitation_appoitment_address_longitude'],
                    
                    'updateStriketask_urla' => str_replace('&amp;', '&', $this->url->link('notes/createtask/updateStriketask', '' . '&task_id=' . $list['id'] . $url2, 'SSL')),
                    'inserttask_urla' => str_replace('&amp;', '&', $this->url->link('notes/createtask/inserttask', '' . '&task_id=' . $list['id'] . $url2, 'SSL'))
            )
            ;
        }
        
        /**
         * **********************
         */
        
        $this->template = $this->config->get('config_template') . '/template/case/calendar.php';
        $this->response->setOutput($this->render());
    }
}