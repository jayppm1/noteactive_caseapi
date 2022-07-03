<?php

class ControllerNotesTranscript extends Controller{

    public function index(){
		
		$url2 = '';
		
		$this->language->load('notes/notes');
		
		$this->load->model('notes/transcription');	
			 
	    $this->data['form_outputkey'] = $this->formkey->outputKey();
		$this->load->model('facilities/online');
		$this->load->model('notes/notes');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->data['action'] = $this->url->link('notes/transcript', '' . $url2, 'SSL');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' ){
				
			$notes_by_transcript_id = $this->model_notes_transcription->addtranscription($this->request->post);
			
			if(!empty($notes_by_transcript_id)){
				$url2 .= '&notes_by_transcript_id=' . $notes_by_transcript_id;
			}
			
			$this->session->data['success2'] = 'transcript Added successfully!';
			
			$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('notes/transcript/insert2', '' . $url2, 'SSL'));
		  
		}
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if($this->request->get['notes_id']){
			$notes_id = $this->request->get['notes_id'];
		}else{
			$notes_id = $this->request->get['updatenotes_id'];
		}
		
		$this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
		$this->data['notes_id'] = $notes_id;
		
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
		
		if (isset($this->request->post['source_transcript'])) {
			$this->data['source_transcript'] = $this->request->post['source_transcript'];
		} else {
			$this->data['source_transcript'] = '';
		}
		if (isset($this->request->post['target_transcript'])) {
			$this->data['target_transcript'] = $this->request->post['target_transcript'];
		} else {
			$this->data['target_transcript'] = '';
		}
		
		
		 
		$this->template = $this->config->get('config_template') . '/template/notes/transcript.php';
		
		$this->children = array(
			'common/headerpopup',
		);
		$this->response->setOutput($this->render());	
	}
	
	public function transcribe(){
		
		 try {
            $json = array();
			
				if($this->request->post['transcript'] != NULL && $this->request->post['transcript'] !=""){
					
				$trans = $this->request->post['transcript'];
				
				$lang = explode('-',$this->request->post['language']);
				$language = $lang[0];
				$lang2 = explode('-',$this->request->post['language_target']);
				$language_target = $lang2[0];
				
				$data = array(
					'trans' => $trans,
					'language' => $language,
					'language_target' => $language_target,
					
				);
				
				$result = $this->awsimageconfig->translate($data);
				$json['success'] = $result;
				
				
				
				}else{
					$json['error'] = '1';
				}
				
				$this->response->setOutput(json_encode($json));
				  
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Checktask'
            );
            $this->model_activity_activity->addActivity('siteschecktask', $activity_data2);
            
            // echo 'Caught exception: ', $e->getMessage(), "\n";
        }
	}
	
	public function play(){
		try {
		$json = array();
		
		
		
		if($this->request->get['transcript'] != NULL && $this->request->get['transcript'] !=""){
			
			$result = $this->awsimageconfig->play($this->request->get['transcript']);
		
			//$json['success'] = $result;
		}else{
			//$json['error'] = '1';
		}
		
			//$this->response->setOutput(json_encode($json));
		
		
		} catch (Exception $e) {
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
					'data' => 'Error in Sites Checktask'
			);
			$this->model_activity_activity->addActivity('siteschecktask', $activity_data2);
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	
		
	}
	
	
	public function insert2 ()   
    {
		
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('notes/notes');
        $this->load->model('notes/transcription');
		
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
		
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
		$this->load->model('facilities/online');
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		
		$this->load->model('facilities/facilities');
		$resulsst =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {
            
            if($resulsst['is_master_facility'] == '1'){
				if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
					$facilities_id  = $this->session->data['search_facilities_id']; 
					$this->load->model('setting/timezone');
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
					
				}else{
					$facilities_id = $this->customer->getId(); 
					$facilitytimezone = $this->customer->isTimezone(); 
				}
			}else{
				 $facilities_id = $this->customer->getId(); 
				 $facilitytimezone = $this->customer->isTimezone(); 
			}
			
			
            $tdata = array();
            $tdata['notes_by_transcript_id'] = $this->request->get['notes_by_transcript_id'];
            $tdata['notes_id'] = $this->request->get['notes_id'];
            $tdata['facilities_id'] = $facilities_id;
            $tdata['facilitytimezone'] = $facilitytimezone;
		
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$this->model_notes_transcription->updatenotestranscription($this->request->post, $tdata); 
				$notes_id = $this->request->get['notes_id'];
			}else{
				$notes_id = $this->model_notes_transcription->updatetranscription($this->request->post, $tdata);
				
			}
			
           
            $this->language->load('notes/notes');
            
            $url2 = "";
            $url2 .= '&notes_id=' . $notes_id;
			
			if ($this->request->get['notes_by_transcript_id'] != null && $this->request->get['notes_by_transcript_id'] != "") {
                $url2 .= '&notes_by_transcript_id=' . $this->request->get['notes_by_transcript_id'];
            }
            
            //$this->data['notes_id'] = $notes_id;
            
            //$this->data['url_load2'] = $this->model_notes_notes->getajaxnote($notes_id);
            
            $this->session->data['success3'] = $this->language->get('text_success');
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('notes/transcript', '' . $url2, 'SSL')));
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
        
		if ($this->request->get['notes_by_transcript_id'] != null && $this->request->get['notes_by_transcript_id'] != "") {
            $url2 .= '&notes_by_transcript_id=' . $this->request->get['notes_by_transcript_id'];
        }
			
        if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
            $url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        $this->data['action2'] = $this->url->link('notes/transcript/insert2', '' . $url2, 'SSL');
		
		
        $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/transcript', '' . $url2, 'SSL'));
        
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
        
      
        
        $this->data['createtask'] = '1';
        
        
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
        
       
   
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
	
	
	public function printtranscript(){
		
		$notes_id = $this->request->get['notes_id'];
		$this->load->model('notes/transcription');	
		$this->load->model('notes/notes');	
		
		if($notes_id != null && $notes_id != ""){
			$alltranscriptions = $this->model_notes_transcription->gettranscriptions($notes_id);
			$notes_info = $this->model_notes_notes->getnotes($notes_id);
			//var_dump($notes_info);
			
			foreach ($alltranscriptions as $alltranscription1){
				$this->data['alltranscriptions'][] = array(
						'source_language'  => $alltranscription1['source_language'],
						'source_transcript'  => $alltranscription1['source_transcript'],
						'target_language'  => $alltranscription1['target_language'],
						'target_transcript'  => $alltranscription1['target_transcript'],
				);
			}
			
			//$this->data['alltranscription'] = $alltranscription;
			$this->data['notes_info'] = $notes_info;
			
		}
			
		$this->template = $this->config->get('config_template') . '/template/notes/printtranscript.php';
	
		$this->children = array(
			'common/headerpopup',
		);
		$this->response->setOutput($this->render());	
			
		
	}
}