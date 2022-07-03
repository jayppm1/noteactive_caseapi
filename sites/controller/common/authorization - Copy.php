<?php

class Controllercommonauthorization extends Controller
{

    private $error = array();

    public function index ()
    {
        try {
            
            $url2 = "";
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
                $url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
            }
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$url2 .= '&notesids=' . $this->request->get['notes_id'];
            }
            
            if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
                $url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
            }
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
                $url2 .= '&forms_id=' . $this->request->get['forms_id'];
            }
            if ($this->request->get['form_parent_id'] != null && $this->request->get['form_parent_id'] != "") {
                $url2 .= '&form_parent_id=' . $this->request->get['form_parent_id'];
            }
            
            if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
                $url2 .= '&task_id=' . $this->request->get['task_id'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
                $url2 .= '&is_archive=' . $this->request->get['is_archive'];
            }
            if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
                $url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
            }
            if ($this->request->get['formreturn_id'] != null && $this->request->get['formreturn_id'] != "") {
                $url2 .= '&formreturn_id=' . $this->request->get['formreturn_id'];
            }
            if ($this->request->get['parent_id'] != null && $this->request->get['parent_id'] != "") {
                $url2 .= '&parent_id=' . $this->request->get['parent_id'];
            }
            if ($this->request->get['page_number'] != null && $this->request->get['page_number'] != "") {
                $url2 .= '&page_number=' . $this->request->get['page_number'];
            }
            
            if ($this->request->get['archive_forms_id'] != null && $this->request->get['archive_forms_id'] != "") {
                $url2 .= '&archive_forms_id=' . $this->request->get['archive_forms_id'];
            }
            if ($this->request->get['client_add_new'] != null && $this->request->get['client_add_new'] != "") {
                $url2 .= '&client_add_new=' . $this->request->get['client_add_new'];
            }
            
            if ($pformreturn_id != null && $pformreturn_id != "") {
                $url2 .= '&formreturn_id=' . $pformreturn_id;
            }
            
            if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
                $url2 .= '&emp_tag_id=' . $this->request->post['emp_tag_id'];
                $url2 .= '&tags_id=' . $this->request->post['emp_tag_id'];
            }
            
            if ($this->request->get['archive_tags_id'] != null && $this->request->get['archive_tags_id'] != "") {
                $url2 .= '&archive_tags_id=' . $this->request->get['archive_tags_id'];
            }
            
            if ($this->request->get['user_roles'] != null && $this->request->get['user_roles'] != "") {
                $url2 .= '&user_roles=' . $this->request->get['user_roles'];
            }
            
            if ($this->request->get['userids'] != null && $this->request->get['userids'] != "") {
                $url2 .= '&userids=' . $this->request->get['userids'];
            }
            
            if ($this->request->get['discharge'] != null && $this->request->get['discharge'] != "") {
                $url2 .= '&discharge=' . $this->request->get['discharge'];
            }
            
            if ($this->request->get['rolecall2'] != null && $this->request->get['rolecall2'] != "") {
                $url2 .= '&rolecall2=' . $this->request->get['rolecall2'];
            }
            
            if ($this->request->get['role_call'] != null && $this->request->get['role_call'] != "") {
                $url2 .= '&role_call=' . $this->request->get['role_call'];
            }
            
            if ($this->request->get['medication_tags'] != null && $this->request->get['medication_tags'] != "") {
                $url2 .= '&medication_tags=' . $this->request->get['medication_tags'];
            }
            if ($this->request->get['archive_tags_medication_id'] != null && $this->request->get['archive_tags_medication_id'] != "") {
                $url2 .= '&archive_tags_medication_id=' . $this->request->get['archive_tags_medication_id'];
            }
           
            if ($this->request->get['all_roll_call'] != null && $this->request->get['all_roll_call'] != "") {
                $url2 .= '&all_roll_call=' . $this->request->get['all_roll_call'];
            }
            
            if ($this->request->get['all_roll_call1'] != null && $this->request->get['all_roll_call1'] != "") {
                $url2 .= '&all_roll_call1=' . $this->request->get['all_roll_call1'];
            }
            
            if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
                $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
            }
            
            if (isset($this->request->get['taskid'])) {
                $url2 .= '&taskids=' . $this->request->get['taskid'];
            }
            
            if (isset($this->request->get['formid'])) {
                $url2 .= '&formids=' . $this->request->get['formid'];
            }
			// if (isset($this->request->get['notes_id'])) {
               // $url2 .= '&notesids=' . $this->request->get['notes_id'];
            //}
            
            if (isset($this->request->get['childstatus'])) {
                $url2 .= '&childstatus=' . $this->request->get['childstatus'];
            }
            if (isset($this->request->get['tags_id'])) {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if (isset($this->request->get['keyword_id'])) {
                $url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
            }
            if ($this->request->get['requires_approval'] != null && $this->request->get['requires_approval'] != "") {
                $url2 .= '&requires_approval=' . $this->request->get['requires_approval'];
            }
            if ($this->request->get['activeform_id'] != null && $this->request->get['activeform_id'] != "") {
                $url2 .= '&activeform_id=' . $this->request->get['activeform_id'];
            }
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			
			if ($facility['is_enable_add_notes_by'] == '1') {
                $this->data['auth_by_face'] = '1';
				
			}
            
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateUserpin()) {
                
                if ($this->request->get['allclientstatus'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/updateclientstatussign', '' . $url2, 'SSL')));
                }

                if ($this->request->get['allclientstatuses'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/updateclientstatussigns', '' . $url2, 'SSL')));

                    if ($this->request->get['clienttype'] == "3" && $this->request->get['page'] != "resident") {                   
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL')));
                }
                }

                if ($this->request->get['clienttype'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL')));
                }
				if ($this->request->get['clienttype'] == "2") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL')));
                }
				if ($this->request->get['clienttype'] == "3") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/activenote/clientsinsignature', '' . $url2, 'SSL')));
                }
                if ($this->request->get['update_notetime'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/updatenotetime', '' . $url2, 'SSL')));
                }
                if ($this->request->get['comment'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/comment/insert2', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['savenotes'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/insert2', '' . $url2, 'SSL')));
                }
                
				
				if ($this->request->get['activeform_id'] != null && $this->request->get['activeform_id'] != "") {
					if ($this->request->get['forms'] == "1") {
						$this->redirect(str_replace('&amp;', '&', $this->url->link('form/form/activeformsign', '' . $url2, 'SSL')));
					}
				}else{
					if ($this->request->get['forms'] == "1") {
						$this->redirect(str_replace('&amp;', '&', $this->url->link('form/form/newformsign', '' . $url2, 'SSL')));
					}
					
				}
                
                if ($this->request->get['forms'] == "2") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('form/form/insert2', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['forms'] == "3") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('form/form/insert3', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['forms'] == "4") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('form/form/taskforminsertsign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['client'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/tags/addclientsign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['updateclient'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/tags/updateclientsign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['census'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/dailycensus/insert2', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['strike'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrike', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['residentstatussign'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatussign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['allrolecallsign'] == "1") {
                    
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/allrolecallsign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['savetask'] == "2") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/createtask/inserttask', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['savetask'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/createtask/updateStriketask', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['savetask'] == "3") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/createtask/approvalurl', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['updateTags'] == "1") {
                    
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/updateTags', '' . $url2, 'SSL')));
                }
                if ($this->request->get['assignteam'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/assignteam', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['rolecallsign'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/rolecallsign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['tagmedicine'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedicationsign', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['tagmedicine'] == "2") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedicationsign2', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['clientactivenote'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/resident/activenote', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['notesactivenote'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/activenote', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['update_strike'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrike', '' . $url2, 'SSL')));
                }
                
                if ($this->request->get['attachmentSign'] == "1") {
                    $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/attachmentSign', '' . $url2, 'SSL')));
                }
                
            }
            
            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            } else {
                $this->data['error_warning'] = '';
            }
            
            $this->template = $this->config->get('config_template') . '/template/common/authorization.php';
            
            $this->children = array(
                    'common/headerpopup'
            );
            
            $this->response->setOutput($this->render());
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Pin verification'
            );
            $this->model_activity_activity->addActivity('SitesNotesverifypin', $activity_data2);
        }
    }

    protected function validateUserpin ()
    {
         if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
        
		
        if ($facility['is_enable_add_notes_by'] == '3') {
        	
        	if ($this->request->post['userpin'] == null && $this->request->post['userpin'] == ""){
        		$this->error['warning'] = "Pin cannot be empty";
        	}
            
            if ($this->request->post['userpin'] != null && $this->request->post['userpin'] != "") {
                $this->load->model('user/user');
                $userdetail = $this->model_user_user->getUserdetailuserpin($this->request->post['userpin']);
				
				
                if (empty($userdetail)) {
                    $this->error['warning'] = "Pin not recognized, please try again ";
                } else {
                    $this->session->data['username_confirm'] = $userdetail['user_id'];
                }
            }
        }
		
		if ($facility['is_enable_add_notes_by'] == '1') {
			$this->data['auth_by_face'] = '1';
			
			$outputFolder = $this->session->data['local_image_dir'];
			$outputFolderUrl = $this->session->data['local_image_url'];
			$notes_file = $this->session->data['local_notes_file'];
			
			$facilities_id = $this->customer->getId(); 
			
			if ($this->request->get['update_strike'] == "1") {
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model('notes/notes');
					$this->model_notes_notes->updateuserpicturestrick($s3file, $this->request->get['notes_id']);
					// unlink($file);
				}
			}
			
			if ($this->request->get['savenotes'] == "1") {
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model('notes/notes');
					$this->model_notes_notes->updateuserpicture($s3file, $this->request->get['notes_id']);
					// unlink($file);
				}
			}
			
			if($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != ""){
				if ($this->request->get['update_strike'] == "1") {
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$this->model_notes_notes->updateuserverifiedstrick('2', $this->request->get['notes_id']);
					}
				}
				
				if ($this->request->get['savenotes'] == "1") {
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$this->model_notes_notes->updateuserverified('2', $this->request->get['notes_id']);
					}
				}
			}else{
				if ($this->request->get['update_strike'] == "1") {
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$this->model_notes_notes->updateuserverifiedstrick('1', $this->request->get['notes_id']);
					}
				}
				
				if ($this->request->get['savenotes'] == "1") {
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$this->model_notes_notes->updateuserverified('1', $this->request->get['notes_id']);
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
	
	public function checkuser(){
		$json = array();
		//$imagesrc = $_POST["image"];
		 if ($this->request->post['current_enroll_image'] != "" && $this->request->post['current_enroll_image'] != NULL) {
			/*$img = $this->request->post['current_enroll_image'];
			$img = str_replace('data:image/jpeg;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$Imgdata = base64_decode($img);
			$notes_file = uniqid() . '.jpeg';
			$file = DIR_IMAGE . '/facerecognition/' . $notes_file;
			$outputFolder = $file;
			$success = file_put_contents($file, $Imgdata);
			$imageUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
			$outputFolderUrl = $imageUrl;
			*/
			//require_once (DIR_APPLICATION_AWS . 'facerecognition_searchbyfaces_config.php');
			
			$apiurl = "https://p4kbd8jj6a.execute-api.us-east-1.amazonaws.com/facialrekognition/facialrekognition";
			//$result_inser_user_img22 = $this->awsimageconfig->apigateway($apiurl, $this->request->post['current_enroll_image']);
			$result_inser_user_img22 = $this->awsimageconfig->searchFacesByImagebyuser($this->request->post['current_enroll_image']);
						   
			foreach($result_inser_user_img22['FaceMatches'] as $c){
				$similarity = $c['Similarity'];
				$FaceId[] = $c['Face']['FaceId'];
				$ImageId[] = $c['Face']['ImageId'];
				$ExternalImageId = $c['Face']['ExternalImageId'];
				
			}
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			if ($facility['face_similar_percent'] != null && $facility['face_similar_percent'] != "0") {
				$face_similar_percent = $facility['face_similar_percent'];
			} else {
				$face_similar_percent = '90';
			}
			
			$this->session->data['local_image_dir'] = $this->request->post['current_enroll_image'];
			$this->session->data['local_image_url'] = $outputFolderUrl;
			$this->session->data['local_notes_file'] = $notes_file;
			
			if ($similarity > $face_similar_percent) {
				
				if ($this->session->data['isPrivate'] == '1') {
					if($this->session->data['username'] == $ExternalImageId){
						$this->session->data['username_confirm'] = $ExternalImageId;
						$json['success'] = '1';
						$json['username_confirm'] = $ExternalImageId;
					}else{
						$json['success'] = '0';
					}
				}else{
					$this->session->data['username_confirm'] = $ExternalImageId;
					$json['success'] = '1';
					$json['username_confirm'] = $ExternalImageId;
				}
			}else{
				
				if ($facility['allow_face_without_verified'] == '1') {
					$json['success'] = '1';
				}else{
					$json['success'] = '0';
				}
				
			}
					
		} 
      
        
        $this->response->setOutput(json_encode($json));
	}
}
