<?php
class Controllernotescomment extends Controller {
	private $error = array();

	public function index() {
		
		$this->load->model('notes/image');
		 $this->language->load('notes/notes');
		
        $this->load->model('setting/highlighter');
        $this->load->model('user/user');
        $this->load->model('notes/tags');
		$this->load->model('facilities/facilities');	
			 
	    $this->data['form_outputkey'] = $this->formkey->outputKey();
		$this->load->model('facilities/online');
		$this->load->model('notes/notes');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		//$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		//$noteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
		//$printnoteurl = $this->url->link('form/form/printform', '' . $url, 'SSL');
		//$firedrillnoteurl = $this->url->link('form/form/printmonthly_firredrill', '' . $url, 'SSL');
		//$incidentnoteurl = $this->url->link('form/form/printincidentform', '' . $url, 'SSL');
		//$innoteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
		
		 $timezone_name = $this->customer->isTimezone();
         date_default_timezone_set($timezone_name);
		 
		  $this->data['keywords'] = array();
            
            $data3 = array(
                    'facilities_id' => $this->customer->getId(),
					'monitor_time' => '6'
            );
            $this->load->model('setting/keywords');
            $keywords = $this->model_setting_keywords->getkeywords($data3);
            
            $url2 = "";
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            foreach ($keywords as $keyword) {
                
                if ($keyword['keyword_image'] && file_exists(DIR_IMAGE . 'icon/' . $keyword['keyword_image'])) {
                    $image = $this->model_notes_image->resize('icon/' . $keyword['keyword_image'], 35, 35);
                }
                
                $lines_arr = preg_split('/\n|\r/', $keyword['keyword_name']);
                $num_newlines = count($lines_arr);
                
                // var_dump($num_newlines);
                
                if ($keyword['monitor_time'] == '1') {
                    
                    if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                        $url2 .= '&notesactivenote=1';
                        $activenote_url = str_replace('&amp;', '&', $this->url->link('common/authorization', ''. '&keyword_id=' . $keyword['keyword_id'] . $url2, 'SSL'));
                    } else {
                    $activenote_url = str_replace('&amp;', '&', $this->url->link('notes/activenote', '' . '&keyword_id=' . $keyword['keyword_id'] . $url2, 'SSL'));
                    }
                } else {
                    $activenote_url = '';
                }
                
                $this->data['keywords'][] = array(
                        'keyword_id' => $keyword['keyword_id'],
                        'keyword_name' => $keyword['keyword_name'],
                        // 'keyword_name2' => str_replace(array("\r", "\n"), '',
                        // $keyword['keyword_name']),
                        'keyword_name2' => str_replace(array(
                                "\r",
                                "\n"
                        ), '\n', $keyword['keyword_name']),
                        'keyword_image' => $keyword['keyword_image'],
                        'monitor_time' => $keyword['monitor_time'],
                        'activenote_url' => $activenote_url,
                        'img_icon' => $image,
                        'num_newlines' => $num_newlines
                );
            }
		 
		$notes_id = $this->request->get['notes_id'];
		
		$url2 = "";
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
			$url2 .= '&comment=1';
		}
		
		$this->load->model('notes/notescomment');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$comment_id = $this->model_notes_notescomment->addnotescomment($this->request->post, $this->customer->getId());
			
			if(!empty($comment_id)){
				$url2 .= '&comment_id=' . $comment_id;
			}
			
			$this->session->data['success2'] = 'Comment Added successfully!';
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
				$url2 .= '&comment=1';
				$this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
			} else {
				$this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/comment/insert2', '' . $url2, 'SSL'));
			}
			
			//$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('notes/comment/insert2', '' . $url2, 'SSL'));
			//die;
		}
		
		
		
		$this->data['notess'] = array();
		$url ="";
	
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			
			
				$result = $this->model_notes_notes->getnotes($this->request->get['notes_id']);
               
				$this->load->model('notes/tags');
                $this->load->model('setting/tags');
                
                $config_tag_status = $this->customer->isTag();
                $this->data['config_tag_status'] = $this->customer->isTag();
                
                $this->data['config_taskform_status'] = $this->customer->isTaskform();
                $this->data['config_noteform_status'] = $this->customer->isNoteform();
                $this->data['config_rules_status'] = $this->customer->isRule();
                $this->data['config_share_notes'] = $this->customer->isNotesShare();
                $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
                
                $this->data['unloack_success'] = $this->session->data['unloack_success'];
				
                //foreach ($results as $result) {
					
					
                    
					$facilitynames = $this->model_facilities_facilities->getfacilities($result['facilities_id']);
					$facilityname = $facilitynames['facility']; 
                    
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
                            // $notes_description = $keyImageSrc1 .'&nbsp;'.
                            // $emp_tag_id . $result['notes_description'];
                            
                            if ($allkeywords) {
                                $keyImageSrc12 = array();
                                $keyname = array();
                                $keyImageSrc11 = "";
                                foreach ($allkeywords as $keyword) {
                                    $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                                    // $keyImageSrc12[] = $keyImageSrc11
                                    // .'&nbsp;' . $keyword['keyword_name'];
                                    // $keyname[] = $keyword['keyword_name'];
                                    // $keyname = array_unique($keyname);
                                    $noteskeywords[] = array(
                                            'keyword_file_url' => $keyword['keyword_file_url']
                                    );
                                }
                                
                                // $keyword_description = str_replace($keyname,
                                // $keyImageSrc12,
                                // $result['notes_description']);
                                // $keyword_description =
                                // $keyImageSrc11.'&nbsp;'.$result['notes_description'];
                                $keyword_description = $result['notes_description'];
                                
                                $notes_description = $emp_tag_id . $keyword_description;
                            } else {
                                $notes_description = $emp_tag_id . $result['notes_description'];
                            }
                        } else {
                            $notes_description = $emp_tag_id;
                        }
                    } else {
                        // $notes_description = $keyImageSrc1 .'&nbsp;'.
                        // $emp_tag_id . $result['notes_description'];
                        
                        if ($allkeywords) {
                            $keyImageSrc12 = array();
                            $keyname = array();
                            $keyImageSrc11 = "";
                            foreach ($allkeywords as $keyword) {
                                
                                $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                                // $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
                                // $keyword['keyword_name'];
                                // $keyname[] = $keyword['keyword_name'];
                                // $keyname = array_unique($keyname);
                                
                                $noteskeywords[] = array(
                                        'keyword_file_url' => $keyword['keyword_file_url']
                                );
                            }
                            
                            // $keyword_description = str_replace($keyname,
                            // $keyImageSrc12, $result['notes_description']);
                            // $keyword_description =
                            // $keyImageSrc11.'&nbsp;'.$result['notes_description'];
                            $keyword_description = $result['notes_description'];
                            
                            $notes_description = $emp_tag_id . $keyword_description;
                        } else {
                            $notes_description = $emp_tag_id . $result['notes_description'];
                        }
                    }
                    
                    
                    $forms = array();
                    
                    if ($result['is_forms'] == '1') {
                        if ($facilityinfo['config_noteform_status'] == '1') {
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
							
							if($alltask['media_url'] != null && $alltask['media_url'] != ""){
								$media_url = $this->url->link('notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask['notes_by_task_id'], 'SSL');
							}else{
								$media_url = "";
							}
							
							if($alltask['medication_attach_url'] != null && $alltask['medication_attach_url'] != ""){
								$medication_attach_url = $this->url->link('notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask['notes_by_task_id'], 'SSL');
							}else{
								$medication_attach_url = "";
							}
                            
                            $notestasks[] = array(
                                    'notes_by_task_id' => $alltask['notes_by_task_id'],
                                    'locations_id' => $alltask['locations_id'],
                                    'task_type' => $alltask['task_type'],
                                    'task_content' => $alltask['task_content'],
                                    'user_id' => $alltask['user_id'],
                                    'signature' => $alltask['signature'],
                                    'notes_pin' => $alltask['notes_pin'],
                                    'task_time' => $alltask['task_time'],
                                    //'media_url' => $alltask['media_url'],
									'media_url' => $media_url,
                                    'capacity' => $alltask['capacity'],
                                    'location_name' => $alltask['location_name'],
                                    'location_type' => $alltask['location_type'],
                                    'notes_task_type' => $alltask['notes_task_type'],
                                    'task_comments' => $alltask['task_comments'],
                                    'role_call' => $alltask['role_call'],
                                    'medication_attach_url' => $medication_attach_url,
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
							
							if($alltmask['media_url'] != null && $alltmask['media_url'] != ""){
								$media_url =$this->url->link('notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask['notes_by_task_id'], 'SSL');
							}else{
								$media_url = "";
							}
							
							if($alltmask['medication_attach_url'] != null && $alltmask['medication_attach_url'] != ""){
								$medication_attach_url = $this->url->link('notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask['notes_by_task_id'], 'SSL');
							}else{
								$medication_attach_url = "";
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
                                    //'media_url' => $alltmask['media_url'],
									'media_url' => $media_url,
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
					
					if($result['user_file'] != null && $result['user_file'] != ""){
						$user_file = $this->url->link('notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $result['notes_id'], 'SSL');
					}else{
						$user_file = "";
					}
                    
                    $this->data['notess'][] = array(
                            'notes_id' => $result['notes_id'],
                            'ooout' => $ooout,
                            //'user_file' => $result['user_file'],
							'user_file' => $user_file,
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
				
				
				
            $url .= '&notes_id=' . $this->request->get['notes_id'];
        //}
	
			
		$this->data['action2'] = $this->url->link('notes/comment', '' . $url, 'SSL');
		$this->data['activenote_url'] = str_replace('&amp;', '&', $this->url->link('notes/activenote', '' . '&keyword_id=' . $keyword['keyword_id'] . $url2, 'SSL'));
	
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
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success3'])) {
			$this->data['success3'] = $this->session->data['success3'];

			unset($this->session->data['success3']);
		} else {
			$this->data['success3'] = '';
		}
		
	
		if (isset($this->request->post['notes_description'])) {
			$this->data['notes_description'] = $this->request->post['notes_description'];
		} else {
			$this->data['notes_description'] = '';
		}
		if (isset($this->request->post['keyword_file'])) {
			$this->data['keyword_file'] = $this->request->post['keyword_file'];
		} else {
			$this->data['keyword_file'] = '';
		}
		
		$this->data['activepop_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/activenote', '' . $url2, 'SSL'));
		//$this->data['config_share_notes'] = $this->customer->isNotesShare();
			
		$this->template = $this->config->get('config_template') . '/template/notes/comment.php';
		
		$this->children = array(
			'common/headerpopup',
		);
		$this->response->setOutput($this->render());
	
	}
	
	
	protected function validateForm(){
		if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
			$formkeyerror = $this->formkey->validate($this->request->post['form_key']);
		}
	
		if($this->request->post['notes_description'] == NULL && $this->request->post['notes_description'] == ""){
			$this->error['warning'] = "This is required field";
		}
		if($this->request->get['notes_id'] == NULL && $this->request->get['notes_id'] == ""){
			$this->error['warning'] = "comment can not add new notes";
		}
	
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
		
	}
	
	
	public function insert2 ()   
    {
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('notes/notescomment');
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		
		$this->load->model('facilities/facilities');
		$resulsst =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		$dataaaa = array();
		
		$ddss = array();
		$ddss1 = array();
		if($resulsst['notes_facilities_ids'] != null && $resulsst['notes_facilities_ids'] != ""){
			$ddss[] = $resulsst['notes_facilities_ids'];
			
			$ddss[] = $this->customer->getId();
			$sssssdd = implode(",",$ddss);
		
		}
		
		
		$dataaaa['facilities'] = $sssssdd;
		$this->data['masterfacilities'] =  $this->model_facilities_facilities->getfacilitiess($dataaaa);
		
		$this->data['is_master_facility'] = $resulsst['is_master_facility'];
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {
            
            if($resulsst['is_master_facility'] == '1'){
				if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
				 $facilities_id  = $this->session->data['search_facilities_id']; 
				}else{
					$facilities_id = $this->customer->getId(); 
				}
			}else{
				 $facilities_id = $this->customer->getId(); 
			}
			
		
			//$this->model_notes_notes->updatenotes($this->request->post, $facilities_id, $this->request->get['notes_id']);
			$this->model_notes_notescomment->updatecomment($this->request->post, $this->request->get['notes_id'], $this->request->get['comment_id']);
           
            $this->language->load('notes/notes');
            
            $url2 = "";
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
			
			 if ($this->request->get['comment'] != null && $this->request->get['comment'] != "") {
                $url2 .= '&comment=' . $this->request->get['comment'];
            }
            
            // $this->data['url_load'] =
            // $this->getChild('notes/notes/getNoteData',$this->request->get['notes_id']);
            $this->data['notes_id'] = $this->request->get['notes_id'];
            
            $this->data['url_load2'] = $this->model_notes_notes->getajaxnote($this->request->get['notes_id']);
            
          
            
            $this->session->data['success3'] = $this->language->get('text_success');
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/comment', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
        
        $url2 = "";
        
        $this->data['config_tag_status'] = $this->customer->isTag();
        
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
        
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
		if ($this->request->get['comment_id'] != null && $this->request->get['comment_id'] != "") {
            $url2 .= '&comment_id=' . $this->request->get['comment_id'];
        }
			
        if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
            $url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        $this->data['action2'] = $this->url->link('notes/comment/insert2', '' . $url2, 'SSL');
		
		
       // $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
        $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/comment', '' . $url2, 'SSL'));
        
		 
		
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
        
        // var_dump($this->session->data['username_confirm']);
        
        $this->data['local_image_url'] = $this->session->data['local_image_url'];
        
        if (isset($this->request->post['user_id'])) {
            $this->data['user_id'] = $this->request->post['user_id'];
        } elseif (! empty($notes_info)) {
            $this->data['user_id'] = $notes_info['user_id'];
        } elseif (! empty($this->session->data['username_confirm'])) {
            $this->data['user_id'] = $this->session->data['username_confirm'];
        }  /*
           * elseif (!empty($this->session->data['user_enroll_confirm'])) {
           * $this->data['user_id'] =
           * $this->session->data['user_enroll_confirm'];
           * }
           */
else {
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
        
        /* monitor time */
        if (isset($this->error['override_monitor_time_user_id_checkbox'])) {
            $this->data['error_override_monitor_time_user_id_checkbox'] = $this->error['override_monitor_time_user_id_checkbox'];
        } else {
            $this->data['error_override_monitor_time_user_id_checkbox'] = '';
        }
        
        if (isset($this->error['override_monitor_time_user_id'])) {
            $this->data['error_override_monitor_time_user_id'] = $this->error['override_monitor_time_user_id'];
        } else {
            $this->data['error_override_monitor_time_user_id'] = '';
        }
        
        if (isset($this->request->post['override_monitor_time_user_id_checkbox'])) {
            $this->data['override_monitor_time_user_id_checkbox'] = $this->request->post['override_monitor_time_user_id_checkbox'];
        } else {
            $this->data['override_monitor_time_user_id_checkbox'] = '';
        }
        
        if (isset($this->request->post['override_monitor_time_user_id'])) {
            $this->data['override_monitor_time_user_id'] = $this->request->post['override_monitor_time_user_id'];
        } else {
            $this->data['override_monitor_time_user_id'] = '';
        }
        
        $this->data['createtask'] = '1';
        
        $a212 = array();
        $a212['notes_id'] = $this->request->get['notes_id'];
        $a212['facilities_id'] = $this->customer->getId();
        
        $active_note_info_actives = $this->model_notes_notes->getNotebyactivenotes($a212);
        if ($active_note_info_actives != null && $active_note_info_actives != "") {
            foreach ($active_note_info_actives as $active_note_info_active) {
                
                if ($active_note_info_active['keyword_id'] != null && $active_note_info_active['keyword_id'] != "") {
                    
                    $this->load->model('setting/keywords');
                    $keywordData2 = $this->model_setting_keywords->getkeywordDetail($active_note_info_active['keyword_id']);
                    $this->data['monitor_time'][] = $keywordData2['monitor_time'];
                    
                    if ($keywordData2['monitor_time'] == '1') {
                        
                        // var_dump($keywordData2['monitor_time']);
                        
                        $a21 = array();
                        // $a21['notes_id'] = $this->request->get['notes_id'];
                        $a21['is_monitor_time'] = '1';
                        $a21['facilities_id'] = $this->customer->getId();
                        
                        $active_note_infos = $this->model_notes_notes->getNotebyactivenotes($a21);
                        
                        // var_dump($active_note_infos);
                        
                        $timezone_name = $this->customer->isTimezone();
                        date_default_timezone_set($timezone_name);
                        $update_date = date('Y-m-d H:i:s', strtotime('now'));
                        
                        foreach ($active_note_infos as $active_note_info) {
                            
                            $note_info = $this->model_notes_notes->getNote($active_note_info['notes_id']);
                            
                            $this->data['monitortimes'][] = array(
                                    'keyword_name' => $active_note_info['keyword_name'],
                                    'user_id' => $note_info['user_id'],
                                    'notes_id' => $note_info['notes_id'],
                                    'caltime' => date($this->language->get('date_format_short_2'), strtotime($note_info['date_added']))
                            );
                        }
                    }
                }
            }
        }
        
        if (isset($this->request->post['tagides'])) {
            $tagides1 = $this->request->post['tagides'];
        } elseif (! empty($this->request->get['tags_id'])) {
            $tagides1 = explode(',', $this->request->get['tags_id']);
        } else {
            $tagides1 = array();
        }
        
        $this->data['tagides'] = array();
        $this->load->model('setting/tags');
        
        foreach ($tagides1 as $tagsid) {
            
            $tag_info = $this->model_setting_tags->getTag($tagsid);
            if ($tag_info) {
                $this->data['tagides'][] = array(
                        'tags_id' => $tagsid,
                        'emp_tag_id' => $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name']
                );
            }
        }
        $this->data['is_multiple_tags'] = IS_MAUTIPLE;
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        
        $this->children = array(
                'common/headerpopup',
                'common/usercamera'
        );
        $this->response->setOutput($this->render());
    }
	

	protected function validateForm2 ()
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
                $this->error['user_id'] = "Enter a valid user.";
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
        
        if ($this->request->post['override_monitor_time_user_id_checkbox'] == '1') {
            if ($this->request->post['override_monitor_time_user_id'] == '') {
                $this->error['override_monitor_time_user_id'] = $this->language->get('error_required');
            }
        }
        
        if ($this->request->post['override_monitor_time_user_id'] != null && $this->request->post['override_monitor_time_user_id'] != '') {
            if ($this->request->post['override_monitor_time_user_id_checkbox'] == '') {
                $this->error['override_monitor_time_user_id_checkbox'] = $this->language->get('error_required');
            }
        }
        
		
		if($this->request->get['notes_id']> 0){
        if ($this->request->post['override_monitor_time_user_id_checkbox'] != '1') {
            $a2 = array();
            $a2['notes_id'] = $this->request->get['notes_id'];
            $a2['facilities_id'] = $this->customer->getId();
            $active_note_info_actives = $this->model_notes_notes->getNotebyactivenotes($a2);
            
            if ($active_note_info_actives != null && $active_note_info_actives != "") {
                
                foreach ($active_note_info_actives as $active_note_info) {
                    $this->load->model('setting/keywords');
                    $keywordData2 = $this->model_setting_keywords->getkeywordDetail($active_note_info['keyword_id']);
                    
                    // var_dump($keywordData2);
                    
                    if ($keywordData2['end_relation_keyword'] == '1') {
                        $a3 = array();
                        $a3['keyword_id'] = $keywordData2['relation_keyword_id'];
                        $a3['user_id'] = $this->request->post['user_id'];
                        $a3['facilities_id'] = $this->customer->getId();
                        $a3['is_monitor_time'] = '1';
                        
                        $active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
                        
                        // var_dump($active_note_info2);
                        
                        if (empty($active_note_info2)) {
                            $this->error['warning'] = 'End ActiveNote does not exit!';
                        }
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

	
}