<?php

class ControllerReportReport extends Controller
{

    public function index ()
    {
		
		
        $this->language->load('common/home');
        $this->language->load('notes/notes');
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        $this->data['heading_title'] = "REPORT";
        
		$this->load->model('setting/highlighter');
        $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters();
        
		
		
		$url2 = "";
		if ($this->request->get['keyword'] != null && $this->request->get['keyword'] != "") {
		   $url2 .= '&keyword=' . $this->request->get['keyword'];
		}
		
		$this->data['action'] = $this->url->link('report/report', '' . $url2, 'SSL');
		
		$this->load->model('setting/keywords');
		 $data3 = array(
                    'facilities_id' => '47',
					'monitor_time' => '6'
            );
        $this->data['keywords'] = $this->model_setting_keywords->getkeywords($data3);	
        
		
		 if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $noteTime = date('H:i:s');
                
                $date = str_replace('-', '/', $this->request->get['searchdate']);
                $res = explode("/", $date);
                $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
                
                $this->data['note_datenew'] = $changedDate . ' ' . $noteTime;
                $searchdate = $this->request->get['searchdate'];
                $this->data['searchdate'] = $this->request->get['searchdate'];
                
                if (($searchdate) >= (date('m-d-Y'))) {
                    $this->data['back_date_check'] = "1";
                } else {
                    $this->data['back_date_check'] = "2";
                }
            } else {
                $this->data['note_datenew'] = date('Y-m-d H:i:s');
                $this->data['searchdate'] = date('m-d-Y');
            }


	
			if ($this->request->get['type'] == "3") {
				$tasktype = '2';
            }elseif(isset($this->request->post['task_type']) ){
				 $this->data['task_type'] = $this->request->post['task_type'];
                $tasktype = $this->request->post['task_type'];
			}else{
				$tasktype=$this->session->data['tasktype'];
			}

			$data = array(
                    'sort' => $sort,
                    'order' => $order,
                    'searchdate' => $searchdate,
                    'searchdate_app' => '1',
                    'facilities_id' => $this->customer->getId(),
                    'note_date_from' => $note_date_from,
                    'note_date_to' => $note_date_to,
                    'search_time_start' => $search_time_start,
                    'search_time_to' => $search_time_to,
					'keyword' => $this->request->get['keyword'],
                    'form_search' => $this->request->get['forms_id'],
                    'activenote' => $this->request->get['keyword_id'],
                    'tags_id' => $search_emp_tag_id,
                    'user_id' => $user_id,
                    'highlighter' => $highlighter,
					'emp_tag_id' => $tags_id,
                    'tasktype' => $tasktype,
                    'start' => ($page - 1) * $config_admin_limit,
                    'limit' => $config_admin_limit
            );

			//var_dump($data);
			
			
			$this->load->model('notes/notes');
               $results = $this->model_notes_notes->getnotess($data);
			   
			   $notes_total = $this->model_notes_notes->getTotalnotess($data);
                
                $this->load->model('notes/tags');
                $this->load->model('setting/tags');
                $this->load->model('setting/highlighter');
                
                $config_tag_status = $this->customer->isTag();
                $this->data['config_tag_status'] = $this->customer->isTag();
                
                $this->data['config_taskform_status'] = $this->customer->isTaskform();
                $this->data['config_noteform_status'] = $this->customer->isNoteform();
                $this->data['config_rules_status'] = $this->customer->isRule();
                $this->data['config_share_notes'] = $this->customer->isNotesShare();
                $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
                
                $this->data['unloack_success'] = $this->session->data['unloack_success'];
                foreach ($results as $result) {
                    
					$facilitynames = $this->model_facilities_facilities->getfacilities($result['facilities_id']);
					$facilityname = $facilitynames['facility']; 
			
                    // $this->cache->delete('note'.$result['notes_id']);
                    
                    if ($result['highlighter_id'] > 0) {
                        $highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
                    } else {
                        $highlighterData = array();
                    }
                    
                    if ($result['is_reminder'] == '1') {
                        $reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
                        $reminder_time = $reminder_info['reminder_time'];
                        $reminder_title = $reminder_info['reminder_title'];
                    } else {
                        $reminder_time = "";
                        $reminder_title = "";
                    }
                    
                    $remdata = "";
                    if ($reminder_info != null && $reminder_info != "") {
                        $remdata = "1";
                    } else {
                        $remdata = "2";
                    }
                    
                    $images = array();
                    
                    if ($result['notes_file'] == '1') {
                        $allimages = $this->model_notes_notes->getImages($result['notes_id']);
                        
                        foreach ($allimages as $image) {
                            
                            $extension = $image['notes_media_extention'];
                            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
                                $keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
                            } else 
                                if ($extension == 'doc' || $extension == 'docx') {
                                    $keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
                                } else 
                                    if ($extension == 'ppt' || $extension == 'pptx') {
                                        $keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
                                    } else 
                                        if ($extension == 'xls' || $extension == 'xlsx') {
                                            $keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
                                        } else 
                                            if ($extension == 'pdf') {
                                                $keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
                                            } else {
                                                $keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
                                            }
                            
                            $images[] = array(
                                    'keyImageSrc' => $keyImageSrc, // '<img
                                                                   // src="sites/view/digitalnotebook/image/attachment.png"
                                                                   // width="35px"
                                                                   // height="35px"
                                                                   // alt=""
                                                                   // style="margin-left:
                                                                   // 4px;" />',
                                    'media_user_id' => $image['media_user_id'],
                                    'notes_type' => $image['notes_type'],
                                    'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
                                    'media_signature' => $image['media_signature'],
                                    'media_pin' => $image['media_pin'],
                                    'notes_file_url' => $this->url->link('notes/notes/displayFile', '' . '&notes_media_id=' . $image['notes_media_id'], 'SSL')
                            );
                        }
                    }
                    
                    
                    if ($result['notes_pin'] != null && $result['notes_pin'] != "") {
                        $userPin = $result['notes_pin'];
                    } else {
                        $userPin = '';
                    }
                    
                    if ($result['task_time'] != null && $result['task_time'] != "00:00:00") {
                        $task_time = date('h:i A', strtotime($result['task_time']));
                    } else {
                        $task_time = "";
                    }
                    
                    if ($config_tag_status == '1') {
                        
                        if ($result['emp_tag_id'] == '1') {
                            $alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
                        } else {
                            $alltag = array();
                        }
                        
                        if ($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != "") {
                            $tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
                            $privacy = $tagdata['privacy'];
                            
                            if ($tagdata['privacy'] == '2') {
                                if ($this->session->data['unloack_success'] != '1') {
                                    $emp_tag_id = $alltag['emp_tag_id'] . ':' . $tagdata['emp_first_name'];
                                } else {
                                    $emp_tag_id = '';
                                }
                            } else {
                                $emp_tag_id = '';
                            }
                        } else {
                            $emp_tag_id = '';
                            $privacy = '';
                        }
                    }
                    
                    // var_dump($result['keyword_file']);
                    
                    $noteskeywords = array();
                    
                    if ($result['keyword_file'] == '1') {
                        $allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
                    } else {
                        $allkeywords = array();
                    }
                    
                    if ($privacy == '2') {
                        if ($this->session->data['unloack_success'] == '1') {
                            if ($allkeywords) {
                                $keyImageSrc12 = array();
                                $keyname = array();
                                $keyImageSrc11 = "";
                                foreach ($allkeywords as $keyword) {
                                    $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                                    $noteskeywords[] = array(
                                            'keyword_file_url' => $keyword['keyword_file_url']
                                    );
                                }
                                
                                $keyword_description = $result['notes_description'];
                                
                                $notes_description = $emp_tag_id . $keyword_description;
                            } else {
                                $notes_description = $emp_tag_id . $result['notes_description'];
                            }
                        } else {
                            $notes_description = $emp_tag_id;
                        }
                    } else {
                        if ($allkeywords) {
                            $keyImageSrc12 = array();
                            $keyname = array();
                            $keyImageSrc11 = "";
                            foreach ($allkeywords as $keyword) {
                                
                                $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                                $noteskeywords[] = array(
                                        'keyword_file_url' => $keyword['keyword_file_url']
                                );
                            }
                           
                            $keyword_description = $result['notes_description'];
                            
                            $notes_description = $emp_tag_id . $keyword_description;
                        } else {
                            $notes_description = $emp_tag_id . $result['notes_description'];
                        }
                    }
                    
                    $forms = array();
                    
                    if ($result['is_forms'] == '1') {
                            $allforms = $this->model_notes_notes->getforms($result['notes_id']);
                            foreach ($allforms as $allform) {
                                $forms[] = array(
                                        'form_type_id' => $allform['form_type_id'],
                                        'forms_id' => $allform['forms_id'],
                                        'design_forms' => $allform['design_forms'],
                                        'custom_form_type' => $allform['custom_form_type'],
                                        'notes_id' => $allform['notes_id'],
                                        'form_type' => $allform['form_type'],
                                        'notes_type' => $allform['notes_type'],
                                        'user_id' => $allform['user_id'],
                                        'signature' => $allform['signature'],
                                        'notes_pin' => $allform['notes_pin'],
                                        'incident_number' => $allform['incident_number'],
                                        'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']))
                                );
                            }
                       
                    }
                    
                    $notestasks = array();
                    $grandtotal = 0;
                    
                    $ograndtotal = 0;
                    
                    if ($result['task_type'] == '1') {
                        $alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
                        
                        foreach ($alltasks as $alltask) {
                            $grandtotal = $grandtotal + $alltask['capacity'];
                            $tags_ids_names = '';
                            
                            if ($alltask['tags_ids'] != null && $alltask['tags_ids'] != "") {
                                $tags_ids1 = explode(',', $alltask['tags_ids']);
                                
                                foreach ($tags_ids1 as $tag1) {
                                    $tags_info1 = $this->model_setting_tags->getTag($tag1);
                                    
                                    if ($tags_info1['emp_first_name']) {
                                        $emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
                                    } else {
                                        $emp_tag_id = $tags_info1['emp_tag_id'];
                                    }
                                    
                                    if ($tags_info1) {
                                        $tags_ids_names .= $emp_tag_id . ', ';
                                    }
                                }
                            }
                            
                            $out_tags_ids_names = "";
                            $ograndtotal = $ograndtotal + $alltask['out_capacity'];
                            
                            if ($alltask['out_tags_ids'] != null && $alltask['out_tags_ids'] != "") {
                                $tags_ids1 = explode(',', $alltask['out_tags_ids']);
                                $i = 0;
                                
								$ooout = '1';
                                // var_dump($tags_ids1);
                                
                                foreach ($tags_ids1 as $tag1) {
                                    
                                    $tags_info12 = $this->model_setting_tags->getTag($tag1);
                                    
                                    if ($tags_info12['emp_first_name']) {
                                        $emp_tag_id = $tags_info12['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
                                    } else {
                                        $emp_tag_id = $tags_info12['emp_tag_id'];
                                    }
                                    
                                    if ($tags_info12) {
                                        $out_tags_ids_names .= $emp_tag_id . ', ';
                                    }
                                    
                                    $i ++;
                                }
                                
                                // $ograndtotal = $i;
                            }else{
								$ooout = '2';
							}
                            
                            // var_dump($ograndtotal);
                            
                            $notestasks[] = array(
                                    'notes_by_task_id' => $alltask['notes_by_task_id'],
                                    'locations_id' => $alltask['locations_id'],
                                    'task_type' => $alltask['task_type'],
                                    'task_content' => $alltask['task_content'],
                                    'user_id' => $alltask['user_id'],
                                    'signature' => $alltask['signature'],
                                    'notes_pin' => $alltask['notes_pin'],
                                    'task_time' => $alltask['task_time'],
                                    'media_url' => $alltask['media_url'],
                                    'capacity' => $alltask['capacity'],
                                    'location_name' => $alltask['location_name'],
                                    'location_type' => $alltask['location_type'],
                                    'notes_task_type' => $alltask['notes_task_type'],
                                    'task_comments' => $alltask['task_comments'],
                                    'role_call' => $alltask['role_call'],
                                    'medication_attach_url' => $alltask['medication_attach_url'],
                                    'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added'])),
                                    'room_current_date_time' => date('h:i A', strtotime($alltask['room_current_date_time'])),
                                    'tags_ids_names' => $tags_ids_names,
                                    'out_tags_ids_names' => $out_tags_ids_names
                            );
                        }
                    }
                    
                    $notesmedicationtasks = array();
                    if ($result['task_type'] == '2') {
                        $alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
                        
                        foreach ($alltmasks as $alltmask) {
                            
                            if ($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00') {
                                $taskTime = date('h:i A', strtotime($alltmask['task_time']));
                            }
                            
                            $notesmedicationtasks[] = array(
                                    'notes_by_task_id' => $alltmask['notes_by_task_id'],
                                    'locations_id' => $alltmask['locations_id'],
                                    'task_type' => $alltmask['task_type'],
                                    'task_content' => $alltmask['task_content'],
                                    'user_id' => $alltmask['user_id'],
                                    'signature' => $alltmask['signature'],
                                    'notes_pin' => $alltmask['notes_pin'],
                                    'task_time' => $taskTime,
                                    'media_url' => $alltmask['media_url'],
                                    'capacity' => $alltmask['capacity'],
                                    'location_name' => $alltmask['location_name'],
                                    'location_type' => $alltmask['location_type'],
                                    'notes_task_type' => $alltmask['notes_task_type'],
                                    'tags_id' => $alltmask['tags_id'],
                                    'drug_name' => $alltmask['drug_name'],
                                    'dose' => $alltmask['dose'],
                                    'drug_type' => $alltmask['drug_type'],
                                    'quantity' => $alltmask['quantity'],
                                    'frequency' => $alltmask['frequency'],
                                    'instructions' => $alltmask['instructions'],
                                    'count' => $alltmask['count'],
                                    'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
                                    'task_comments' => $alltmask['task_comments'],
                                    'role_call' => $alltmask['role_call'],
                                    'medication_file_upload' => $alltmask['medication_file_upload'],
                                    'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added']))
                            );
                        }
                    }
                    
                    if ($result['task_type'] == '6') {
                        $approvaltask = $this->model_notes_notes->getapprovaltask($result['task_id']);
                    } else {
                        $approvaltask = array();
                    }
                    
                    if ($result['task_type'] == '3') {
                        $geolocation_info = $this->model_notes_notes->getGeolocation($result['notes_id']);
                    } else {
                        $geolocation_info = array();
                    }
                    
                    if ($result['original_task_time'] != null && $result['original_task_time'] != "00:00:00") {
                        $original_task_time = date('h:i A', strtotime($result['original_task_time']));
                    } else {
                        $original_task_time = "";
                    }
                    
                    $this->data['notess'][] = array(
                            'notes_id' => $result['notes_id'],
                            'ooout' => $ooout,
                            'user_file' => $result['user_file'],
                            'is_user_face' => $result['is_user_face'],
                            'is_approval_required_forms_id' => $result['is_approval_required_forms_id'],
                            'original_task_time' => $original_task_time,
                            'geolocation_info' => $geolocation_info,
                            'approvaltask' => $approvaltask,
                            'notes_file' => $result['notes_file'],
                            'keyword_file' => $result['keyword_file'],
                            'emp_tag_id' => $result['emp_tag_id'],
                            'is_forms' => $result['is_forms'],
                            'is_reminder' => $result['is_reminder'],
                            'task_type' => $result['task_type'],
                            'visitor_log' => $result['visitor_log'],
                            'is_tag' => $result['is_tag'],
                            'is_archive' => $result['is_archive'],
                            'form_type' => $result['form_type'],
                            'generate_report' => $result['generate_report'],
                            'is_census' => $result['is_census'],
                            'is_android' => $result['is_android'],
                            'alltag' => $alltag,
                            'remdata' => $remdata,
                            'noteskeywords' => $noteskeywords,
                            'is_private' => $result['is_private'],
                            'share_notes' => $result['share_notes'],
                            'is_offline' => $result['is_offline'],
                            'review_notes' => $result['review_notes'],
                            'is_private_strike' => $result['is_private_strike'],
                            'checklist_status' => $result['checklist_status'],
                            'notes_type' => $result['notes_type'],
                            'strike_note_type' => $result['strike_note_type'],
                            'task_time' => $task_time,
                            'tag_privacy' => $privacy,
                            'incidentforms' => $forms,
                            'notestasks' => $notestasks,
                            'grandtotal' => $grandtotal,
                            'ograndtotal' => $ograndtotal,
                            'boytotals' => $boytotals,
                            'girltotals' => $girltotals,
                            'generaltotals' => $generaltotals,
                            'residentstotals' => $residentstotals,
                            'notesmedicationtasks' => $notesmedicationtasks,
                            'task_type' => $result['task_type'],
                            'taskadded' => $result['taskadded'],
                            'assign_to' => $result['assign_to'],
                            'highlighter_value' => $highlighterData['highlighter_value'],
                            'notes_description' => $notes_description,
                            // 'keyImageSrc' => $keyImageSrc,
                            // 'fileOpen' => $fileOpen,
                            'images' => $images,
                            'notetime' => date('h:i A', strtotime($result['notetime'])),
                            'username' => $result['user_id'],
                            'notes_pin' => $userPin,
                            'signature' => $result['signature'],
                            'text_color_cut' => $result['text_color_cut'],
                            'text_color' => $result['text_color'],
                            'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
                            'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                            'date_added' => date('m-d-Y', strtotime($result['date_added'])),
                            'date_added2' => date('D F j, Y', strtotime($result['date_added'])),
                            'strike_user_name' => $result['strike_user_id'],
                            'strike_pin' => $result['strike_pin'],
                            'strike_signature' => $result['strike_signature'],
                            'strike_date_added' => date($this->language->get('date_format_short_2'), strtotime($result['strike_date_added'])),
                            'reminder_time' => $reminder_time,
                            'reminder_title' => $reminder_title,
							'facilityname' => $facilityname,
                            'href' => $this->url->link('notes/notes/insert', '' . '&reset=1&searchdate=' . date('m-d-Y', strtotime($result['date_added'])) . $url, 'SSL')
                    );
                }
		
		
		
        $this->template = $this->config->get('config_template') . '/template/report/report.php';
		$this->children = array(
                'common/footercase',
                'common/headercase',
                'common/headercasetask'
        );
        $this->response->setOutput($this->render());
    }
	

}