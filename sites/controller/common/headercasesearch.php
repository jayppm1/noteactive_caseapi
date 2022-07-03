<?php   
class Controllercommonheadercasesearch extends Controller {
	protected function index() {
		try{
			
		$this->load->model('setting/tags');
		$this->load->model('facilities/facilities');
		$tags_id = $this->request->get['tags_id'];
        $tag = $this->model_setting_tags->getTag($tags_id);
		

		$this->data['emp_tag_id'] = $tag['emp_tag_id'];
		$this->data['stickynote'] = $tag['stickynote'];
		$this->data['tags_id'] = $tag['tags_id'];
		$this->data['ssn'] = $tag['ssn'];
		$this->data['prescription'] = $tag['prescription'];
		$this->data['location_address'] = $tag['location_address'];
		$this->data['dob'] = $tag['dob'];
		$this->data['room'] = $tag['room'];
		$this->data['emp_extid'] = $tag['emp_extid'];

		
		$facilitynames = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
		$this->data['facilities_id'] = $facilitynames['facility']; 
			
		$this->load->model('createtask/createtask');
        $this->data['tasktypes'] = $this->model_createtask_createtask->getTaskdetails($facilitynames['facility']);
		

		$this->load->model('setting/highlighter');
        $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters();
			
			
		$this->load->model('notes/notes');
		
		$tagstatus = $this->model_setting_tags->getTagstatus($tags_id);
		
		$tagclassification = $this->model_setting_tags->getTagClassification($tagstatus['status']);
		if($tagclassification['classification_name'] !=NULL && $tagclassification['classification_name'] !=""){
			$this->data['Classification'] = $tagclassification['classification_name'];
			$this->data['color_code'] = $tagclassification['color_code'];
		}else{
			$this->data['Classification'] = "Classification";
			$this->data['color_code'] = "";
		}
		
		$this->load->model('resident/resident');
		$client_statuses_value = $this->model_resident_resident->getClientStatusById ($tag['role_call']); 
		
		
		if($client_statuses_value['name'] !=NULL && $client_statuses_value['name'] !=""){
			$this->data['client_status_name'] = $client_statuses_value['name'];
			$this->data['client_status_image'] = $client_statuses_value['image'];
			$this->data['client_status_color'] = $client_statuses_value['color_code'];
		}else{
			$this->data['client_status_name'] = "Status";
			$this->data['client_status_color'] = "";
		}	
                
                if (isset($this->request->get['searchdate'])) {
                    $res = explode("-", $this->request->get['searchdate']);
                    $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
                    
                    $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
                    $currentdate = $createdate1;
                } else {
                    $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
                    
                    $currentdate = date('d-m-Y');
                }

		
		$data = array(
                    'status' => '1',
                    'facilities_id' => $this->customer->getId()
            );
        $this->load->model('user/user');   
		$allusers = '';
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId(), $allusers);
		
		
		$this->data['tag'] = $tag;
		
		$this->data['tagname'] = $tag['emp_first_name'] . ' ' . $tag['emp_last_name'];
		
		if( $this->request->get['case'] !=NULL && $this->request->get['case'] !='' ){
			$this->data['case'] = $this->request->get['case'];
		}
		
		$url3 = "";
		$url4 = "";

		$url2 = "";
		$url5 = "";
		$url6 = "";
		if ($this->request->get['case'] != null && $this->request->get['case'] != "") {
		   $url2 .= '&case=' . $this->request->get['case'];
		   $url3 .= '&case=' . $this->request->get['case'];
		   $url5 .= '&case=' . $this->request->get['case'];
		}
		if ($this->request->get['search_time_start'] != null && $this->request->get['search_time_start'] != "") {
		   $url2 .= '&search_time_start=' . $this->request->get['search_time_start'];
		   $url3 .= '&search_time_start=' . $this->request->get['search_time_start'];
		   $url5 .= '&search_time_start=' . $this->request->get['search_time_start'];
		}
		
		if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
			$url2 .= '&note_date_from=' . $this->request->get['note_date_from'];
			$url3 .= '&note_date_from=' . $this->request->get['note_date_from'];
			$url5 .= '&note_date_from=' . $this->request->get['note_date_from'];
		}
		if ($this->request->get['search_time_to'] != null && $this->request->get['search_time_to'] != "") {
			$url2 .= '&search_time_to=' . $this->request->get['search_time_to'];
			$url3 .= '&search_time_to=' . $this->request->get['search_time_to'];
			$url5 .= '&search_time_to=' . $this->request->get['search_time_to'];
		}
		if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
			$url2 .= '&note_date_to=' . $this->request->get['note_date_to'];
			$url3 .= '&note_date_to=' . $this->request->get['note_date_to'];
			$url5 .= '&note_date_to=' . $this->request->get['note_date_to'];
		}
		
		if ($this->request->get['keyword'] != null && $this->request->get['keyword'] != "") {
			$url2 .= '&keyword=' . $this->request->get['keyword'];
			$url3 .= '&keyword=' . $this->request->get['keyword'];
			$url5 .= '&keyword=' . $this->request->get['keyword'];
		}
		
		if ($this->request->get['task_type'] != null && $this->request->get['task_type'] != "") {
			$url2 .= '&task_type=' . $this->request->get['task_type'];
			$url3 .= '&task_type=' . $this->request->get['task_type'];
			$url5 .= '&task_type=' . $this->request->get['task_type'];
		}
		
		
		if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
			$url2 .= '&highlighter=' . $this->request->get['highlighter'];
			$url3 .= '&highlighter=' . $this->request->get['highlighter'];
			$url5 .= '&highlighter=' . $this->request->get['highlighter'];
		}
		
		if ($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
			$url2 .= '&user_id=' . $this->request->get['user_id'];
			$url3 .= '&user_id=' . $this->request->get['user_id'];
			$url5 .= '&user_id=' . $this->request->get['user_id'];
		}


		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get['tags_id'];
			$url4 .= '&tags_id=' . $this->request->get['tags_id'];
			$url3 .= '&tags_id=' . $this->request->get['tags_id'];
			$url5 .= '&tags_id=' . $this->request->get['tags_id'];
			$url6 .= '&tags_id=' . $this->request->get['tags_id'];
		}
		if ($this->request->get['type'] != null && $this->request->get['type'] != "") {
			$url2 .= '&type=' . $this->request->get['type'];
			//$url3 .= '&type=' . $this->request->get['type'];
			$url6 .= '&type=' . $this->request->get['type'];
		   $this->data['type'] = $this->request->get['type'];
		}
		if ($this->request->get['task'] != null && $this->request->get['task'] != "") {
			$url2 .= '&task=' . $this->request->get['task'];
			//$url3 .= '&task=' . $this->request->get['task'];
			$url6 .= '&task=' . $this->request->get['task'];
		   $this->data['task'] = $this->request->get['task'];
		}
		
		$this->data['forms'] = $this->request->get['forms'];
		$this->data['task'] = $this->request->get['task'];
		$this->data['keyword_id'] = $this->request->get['keyword_id'];

		if($this->request->get['task'] == '1'){
				$this->data['type'] = '5';
		}
		 $this->data['clientstatus_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allclientstatus', '' . $url2, 'SSL'));
		
			$url6 .= '&facilities_id=' . $tag['facilities_id'];
			$url2 .= '&facilities_id=' . $tag['facilities_id'];
			$url3 .= '&facilities_id=' . $tag['facilities_id'];
			$url4 .= '&facilities_id=' . $tag['facilities_id'];
			$url5 .= '&facilities_id=' . $tag['facilities_id'];


		
			$route  = $this->request->get['route'];
				
			$this->data['complete_task'] = $this->url->link(''.$route.'&task_search=2', '' . $url2, 'SSL');
			$this->data['incomplete_task'] = $this->url->link(''.$route.'&task_search=3', '' . $url2, 'SSL');
			$this->data['pending_task'] = $this->url->link(''.$route.'&task_search=4', '' . $url2, 'SSL');
			


			if (isset($this->request->post['note_date_from'])) {
				$this->data['note_date_from'] = $this->request->post['note_date_from'];
				$date = str_replace('-', '/', $this->request->post['note_date_from']);
				$res = explode("/", $date);
				$note_date_from = $res[2] . "-" . $res[0] . "-" . $res[1];
				
			} else {
				$this->data['note_date_from'] = '';
			}
			
			if (isset($this->request->post['note_date_to'])) {
				$this->data['note_date_to'] = $this->request->post['note_date_to'];
				
				$date = str_replace('-', '/', $this->request->post['note_date_to']);
				$res = explode("/", $date);
				$note_date_to = $res[2] . "-" . $res[0] . "-" . $res[1];
			} else {
				$this->data['note_date_to'] = '';
			}
			
			if (isset($this->request->post['search_time_start'])) {
				$this->data['search_time_start'] = $this->request->post['search_time_start'];
				$search_time_start = $this->request->post['search_time_start'];
			} else {
				$this->data['search_time_start'] = '';
			}
			
			if (isset($this->request->post['search_time_to'])) {
				$this->data['search_time_to'] = $this->request->post['search_time_to'];
				$search_time_to = $this->request->post['search_time_to'];
			} else {
				$this->data['search_time_to'] = '';
			}
			
			
			if (isset($this->request->post['user_id'])) {
				$this->data['user_id'] = $this->request->post['user_id'];
				$user_id = $this->request->post['user_id'];
			} else {
				$this->data['user_id'] = '';
			}
		
		

		//$this->data['href_event'] = $this->url->link('notes/notes/insert', '' . $url2 . '&tags_id=' . $tag['tags_id'], 'SSL');
		$this->data['href_sticky'] = $this->url->link('resident/resident/getstickynote&close=1', '' . $url2, 'SSL');
		$this->data['screening_url'] = $this->url->link('resident/resident/getstickynote&close=1', '' . $url2, 'SSL');
		$this->data['form_url'] = $this->url->link('case/clients/detail&type=2', '' . $url4, 'SSL');
		$this->data['task_url'] = $this->url->link('case/clients/detail&task=1', '' . $url4, 'SSL');
		$this->data['add_task'] = $this->url->link('notes/createtask&type=5', '' . $url4, 'SSL');
		//$this->data['add_form'] = $this->url->link('notes/createtask&type=5', '' . $url4, 'SSL');
		$this->data ['formpop_url'] = $this->url->link ( 'notes/notes/allforms', '' . $url4, 'SSL' );
		 $this->data['clientclassification_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allclientclassification', '' . $url6, 'SSL'));

		$this->data['note_url'] = $this->url->link('notes/notes/insert', '' . $url2, 'SSL');
		
		$this->data['calendarpopup'] = $this->url->link('case/calendar&popup=1', '' . $url2, 'SSL');
		
		
			$this->data['fromsclient_url'] = $this->url->link('case/clients/detail&forms=all', '' . $url3, 'SSL');
			$this->data['taskclient_url'] = $this->url->link('case/clients/detail&task=1', '' . $url3, 'SSL');
			$this->data['keywordsclient_url'] = $this->url->link('case/clients/detail&keyword_id=all', '' . $url3, 'SSL');
			$this->data['all_url'] = $this->url->link('case/clients/detail', ''. $url6 , 'SSL');
	
		
			$this->data['resetUrl'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
            
            $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url, 'SSL');
            $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url, 'SSL');
            $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url, 'SSL');
            $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url, 'SSL');
             $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url, 'SSL');
			$this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url, 'SSL');
			
			$this->data['customIntake_url'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
			$this->data['censusdetail_url'] = $this->url->link('resident/dailycensus/censusdetail', '' . $url2, 'SSL');
			$this->data['medication_url'] = $this->url->link('resident/resident/tagsmedication', '' . $url5, 'SSL');
			
			
			$this->data['assignteam_url'] = $this->url->link('resident/assignteam', '' . $url2, 'SSL');
			
			$this->data['bedcheck_url'] = $this->url->link('notes/printbedcheck&is_bedchk=1', '' . $url2, 'SSL');
			
			$this->data['approval_url'] = $this->url->link('notes/createtask/approvalurl', '' . $url2, 'SSL');
			
			$this->data['routemap_url'] = $this->url->link('notes/routemap', '' . $url2, 'SSL');
			
			$this->data['discharge_href'] = $this->url->link('notes/case', '' . $url2, 'SSL');
			$this->data['allclient_url'] = $this->url->link('case/clients', '' . $url2, 'SSL');
			$this->data['waitclient_url'] = $this->url->link('case/clients&wait_list=1', '' . $url2, 'SSL');
			
		$this->data['search_url'] = str_replace('&amp;', '&', $this->url->link('case/clients/detail', '' . $url2, 'SSL'));
		
		$this->data['action'] = $this->url->link('case/clients/detail', '' . $url2, 'SSL');
		$this->data['actionurl'] = $this->url->link('case/clients/detail&type=1', '' . $url5, 'SSL');
		
		
		if($this->request->get['type'] == '4'){
			$this->data['action222'] = str_replace('&amp;', '&', $this->url->link('case/calendar', '' . $url2, 'SSL'));
		}else{
			$this->data['action222'] = str_replace('&amp;', '&', $this->url->link('case/clients/detail', '' . $url3, 'SSL'));
		}
		
		
		if (isset($this->request->get['note_date_search'])) {
                $this->data['note_date_search'] = $this->request->get['note_date_search'];
            } else {
                $this->data['note_date_search'] = '';
            }
           
            
            if (isset($this->request->get['note_date_from'])) {
                $this->data['note_date_from'] = $this->request->get['note_date_from'];
				/*
				$date = str_replace('-', '/', $this->request->post['note_date_from']);
                $res = explode("/", $date);
                $note_date_from = $res[2] . "-" . $res[0] . "-" . $res[1];
				
				*/
				
            } else {
                $this->data['note_date_from'] =  "";
            }
            
            if (isset($this->request->get['note_date_to'])) {
                $this->data['note_date_to'] = $this->request->get['note_date_to'];
				
				/*
				$date = str_replace('-', '/', $this->request->post['note_date_to']);
                $res = explode("/", $date);
                $note_date_to = $res[2] . "-" . $res[0] . "-" . $res[1];
				*/
            } else {
                $this->data['note_date_to'] = "";
            }
            
            if (isset($this->request->get['search_time_start'])) {
                $this->data['search_time_start'] = $this->request->get['search_time_start'];
                $search_time_start = $this->request->get['search_time_start'];
            } else {
                $this->data['search_time_start'] = '';
            }
            
            if (isset($this->request->get['search_time_to'])) {
                $this->data['search_time_to'] = $this->request->get['search_time_to'];
                $search_time_to = $this->request->get['search_time_to'];
            } else {
                $this->data['search_time_to'] = '';
            }
            
            if (isset($this->request->get['keyword'])) {
                $this->data['keyword'] = $this->request->get['keyword'];
                $keyword = $this->request->get['keyword'];
            } else {
                $this->data['keyword'] = '';
            }
           
		   
		    if (isset($this->request->get['highlighter'])) {
                $this->data['highlighter'] = $this->request->get['highlighter'];
                $highlighter = $this->request->get['highlighter'];
            } else {
                $this->data['highlighter'] = '';
            }
			
			 if (isset($this->request->get['user_id'])) {
                $this->data['user_id'] = $this->request->get['user_id'];
                $user_id = $this->request->get['user_id'];
            } else {
                $this->data['user_id'] = '';
            }
		
			 if (isset($this->request->get['task_type'])) {
                $this->data['task_type'] = $this->request->get['task_type'];
                $task_type = $this->request->get['task_type'];
            } else {
                $this->data['task_type'] = '';
            }
		
			
		$this->template = $this->config->get('config_template') . '/template/common/headercasesearch.php';

			$this->render();
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Common headercase',
			);
			$this->model_activity_activity->addActivity('headercase', $activity_data2);
		
		
		} 
	}

	protected function validateForm ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
       
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

 	
}
?>
