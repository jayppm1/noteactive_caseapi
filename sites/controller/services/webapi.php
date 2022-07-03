<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
header ( "Content-type: bitmap; charset=utf-8" );
class Controllerserviceswebapi extends Controller {
	public function addattachments() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'addattachments', $this->request->post, 'request' );
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			/*
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			 *
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			if ($this->request->post ['facilities_id'] == null && $this->request->post ['facilities_id'] == "") {
				$json ['warning'] = 'Warning: Enter Facilities Id';
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				if ($this->request->post ['notes_id'] != null && $this->request->post ['notes_id'] != "") {
					$note_info = $this->model_notes_notes->getNote ( $this->request->post ['notes_id'] );
					$formData ['facilities_id'] = $note_info ['facilities_id'];
					$notes_id = $note_info ['notes_id'];
					
					$this->load->model ( 'facilities/facilities' );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $note_info ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
					
					// date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					date_default_timezone_set ( $facilitytimezone );
					$formData ['noteDate'] = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$formData ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$formData ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$formData ['is_android'] = $this->request->post ['is_android'];
					} else {
						$formData ['is_android'] = '1';
					}
					
					if ($this->request->post ['description'] != null && $this->request->post ['description']) {
						$description = ' | ' . $this->request->post ['description'];
					}
					
					if ($this->request->post ['file_name'] != null && $this->request->post ['file_name']) {
						$file_name = ' | ' . $this->request->post ['file_name'];
					}
					
					if ($this->request->post ['classification'] != null && $this->request->post ['classification']) {
						$fdata = array (
								'case_id' => $this->request->post ['classification'] 
						);
						$classid_info = $this->model_notes_notes->getFormcaseId ( $fdata );
						$description = ' | ' . $classid_info ['name'];
						$forms_id = $classid_info ['forms'];
					}
					
					if ($this->request->post ['file_type'] == 'Form') {
						$this->load->model ( 'form/form' );
						$form_info = $this->model_form_form->getFormdata ( $this->request->post ['form'] );
						$fdata = array (
								'from_id' => $this->request->post ['form'] 
						);
						$classid = $this->model_notes_notes->getFormcaseId ( $fdata );
						$description .= ' | ' . $classid ['name'];
						$case_id = $classid ['case_id'];
						
						$forms_id = $this->request->post ['form'];
						$notes_description = 'Form ' . $form_info ['form_name'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
						
						$notes_description2 = $note_info ['notes_description'] . ' ' . $notes_description;
						$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql1 );
					}
					
					if ($this->request->post ['file_type'] == 'Document') {
						$notes_description = 'Document ' . $form_info ['form_name'] . ' has been added | ' . $description . '' . $file_name;
						$notes_description2 = $note_info ['notes_description'] . ' ' . $notes_description;
						$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql1 );
					}
					
					if ($this->request->post ['file_type'] == 'Picture') {
						$notes_description = 'Image ' . $form_info ['form_name'] . ' has been added | ' . $description . '' . $file_name;
						$notes_description2 = $note_info ['notes_description'] . ' ' . $notes_description;
						$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql1 );
					}
					
					if ($this->request->post ['file_type'] == 'Other') {
						$notes_description = 'Other ' . $this->request->post ['other'] . ' has been added | ' . $description . '' . $file_name;
						$notes_description2 = $note_info ['notes_description'] . ' ' . $notes_description;
						$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape ( $notes_description2 ) . "', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
						$this->db->query ( $sql1 );
					}
					
					if ($case_id != NULL && $case_id != "") {
						$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '" . $case_id . "'  where notes_id = '" . $notes_id . "'";
						$this->db->query ( $slq1case );
					} else {
						
						if ($this->request->post ['classification'] != NULL && $this->request->post ['classification'] != "") {
							$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '" . $this->request->post ['classification'] . "'  where notes_id = '" . $notes_id . "'";
							$this->db->query ( $slq1case );
						}
					}
					
					$notes_file_url= $this->request->post ['upload_file'];
					$notes_media_extention= $this->request->post ['upload_file_ext'];
					
					
					$notes_media_id = $this->model_notes_notes->updateNoteFile ( $this->request->post ['notes_id'], $notes_file_url, $notes_media_extention, $formData );
					
					if ($this->request->post ['upload_file'] != null && $this->request->post ['upload_file'] != null) {
						
						
						$this->load->model ( 'notes/notes' );
						$this->model_notes_notes->updateuserpicturenotesmedia ( $notes_file_url, $this->request->post ['notes_id'], $notes_media_id );
						
						
					}
					
					// date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					date_default_timezone_set ( $facilitytimezone );
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$this->model_notes_notes->updatedate ( $this->request->post ['notes_id'], $update_date );
					
					if ($forms_id) {
						$slq12pp = "UPDATE " . DB_PREFIX . "notes_media SET forms_id = '" . $forms_id . "' where notes_media_id = '" . $notes_media_id . "'";
						$this->db->query ( $slq12pp );
						
						$this->load->model ( 'form/form' );
						$formdatai = $this->model_form_form->getFormdata ( $forms_id );
						
						$data23 = array ();
						$data23 ['forms_design_id'] = $forms_id;
						$data23 ['notes_id'] = $notes_id;
						// $data23['tags_id'] = $tag_info['tags_id'];
						$data23 ['facilities_id'] = $note_info ['facilities_id'];
						$this->load->model ( 'form/form' );
						$formreturn_id = $this->model_form_form->addFormdata ( $formdatai, $data23 );
						
						$slq12pp = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $notes_id . "', image_url = '" . $notes_file_url . "', image_name = '" . $notes_media_extention . "' where forms_id = '" . $formreturn_id . "'";
						$this->db->query ( $slq12pp );
						
						$slq12pp = "UPDATE " . DB_PREFIX . "notes SET is_forms = '1' where notes_id = '" . $notes_id . "'";
						$this->db->query ( $slq12pp );
						
						// $this->model_form_form->updatenote($notes_id, $formreturn_id );
					}
				} else {
					
					
					$timeZone = date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
					
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					
					$this->load->model ( 'setting/tags' );
					$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
					
					$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
					$data ['tags_id'] = $tag_info ['tags_id'];
					
					// $tagname = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | ';
					
					$this->load->model ( 'setting/keywords' );
					$keywordData2 = $this->model_setting_keywords->getkeywordDetail ( $this->request->post ['keyword_id'] );
					
					$data ['keyword_file'] = $keywordData2 ['keyword_image'];
					
					// if ($this->request->post['comments'] != null && $this->request->post['comments']) {
					// $comments = ' | ' . $this->request->post['comments'];
					// }
					
					if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
						$comments = ' | ' . $this->request->post ['comments'];
					}
					
					if ($this->request->post ['file_name'] != null && $this->request->post ['file_name']) {
						$file_name = ' | ' . $this->request->post ['file_name'];
					}
					
					if ($this->request->post ['classification'] != null && $this->request->post ['classification']) {
						$fdata = array (
								'case_id' => $this->request->post ['classification'] 
						);
						$classid_info = $this->model_notes_notes->getFormcaseId ( $fdata );
						$description = ' | ' . $classid_info ['name'];
						$forms_id = $classid_info ['forms'];
					}
					
					if ($this->request->post ['description'] != null && $this->request->post ['description']) {
						$description .= ' | ' . $this->request->post ['description'];
					}
					
					if ($this->request->post ['case_number'] != '' && $this->request->post ['case_number'] != null) {
						$description .= ' | ' . $this->request->post ['case_number'];
					}
					
					if ($this->request->post ['file_type'] == 'Form') {
						$this->load->model ( 'form/form' );
						$form_info = $this->model_form_form->getFormdata ( $this->request->post ['form'] );
						$fdata = array (
								'from_id' => $this->request->post ['form'] 
						);
						$classid = $this->model_notes_notes->getFormcaseId ( $fdata );
						$description .= ' | ' . $classid ['name'];
						$case_id = $classid ['case_id'];
						
						$forms_id = $this->request->post ['form'];
						$data ['notes_description'] = 'Form ' . $form_info ['form_name'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
					}
					
					if ($this->request->post ['file_type'] == 'Document') {
						$data ['notes_description'] = 'Document has been added | ' . $description . '' . $comments . '' . $file_name;
					}
					
					if ($this->request->post ['file_type'] == 'Picture') {
						
						$data ['notes_description'] = 'Image has been added | ' . $description . '' . $comments . '' . $file_name;
					}
					
					if ($this->request->post ['file_type'] == 'Other') {
						$data ['notes_description'] = 'Other Data - ' . $this->request->post ['other'] . ' has been added | ' . $description . '' . $comments . '' . $file_name;
					}
					
					$data ['date_added'] = $date_added;
					$data ['note_date'] = $date_added;
					$data ['notetime'] = $notetime;
					
					if ($this->request->post ['is_web'] == '1') {
						if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
							$data ['imgOutput'] = urldecode ( $this->request->post ['signature'] );
						}
					} else {
						if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
							$data ['imgOutput'] = $this->request->post ['signature'];
						}
					}
					
					
					$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
					
					
					if ($case_id != NULL && $case_id != "") {
						$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '" . $case_id . "'  where notes_id = '" . $notes_id . "'";
						$this->db->query ( $slq1case );
					} else {
						$slq1case = "UPDATE " . DB_PREFIX . "notes SET case_id = '" . $this->db->escape($this->request->post ['classification']) . "'  where notes_id = '" . $notes_id . "'";
						$this->db->query ( $slq1case );
					}
					
					if ($this->request->post ['upload_file'] != null && $this->request->post ['upload_file'] != "") {
						
						$media_notes_id = $notes_id;
						
						$notes_media_extention = $this->request->post ['upload_file_ext'];
						$notes_file_url = $this->request->post ['upload_file'];
						$formData = array ();
						$formData ['media_user_id'] = '';
						$formData ['media_signature'] = '';
						$formData ['media_pin'] = '';
						$formData ['facilities_id'] = $facilities_id;
						$formData ['noteDate'] = $date_added;
						
						$notes_media_id = $this->model_notes_notes->updateNoteFile ( $media_notes_id, $notes_file_url, $notes_media_extention, $formData );
						
						$slq12pp = "UPDATE " . DB_PREFIX . "notes_media SET forms_id = '" . $forms_id . "' where notes_media_id = '" . $notes_media_id . "'";
						$this->db->query ( $slq12pp );
					}
					
					if ($forms_id) {
						$this->load->model ( 'form/form' );
						$formdatai = $this->model_form_form->getFormdata ( $forms_id );
						
						$data23 = array ();
						$data23 ['forms_design_id'] = $forms_id;
						$data23 ['notes_id'] = $notes_id;
						$data23 ['tags_id'] = $tag_info ['tags_id'];
						$data23 ['facilities_id'] = $facilities_id;
						$this->load->model ( 'form/form' );
						$formreturn_id = $this->model_form_form->addFormdata ( $formdatai, $data23 );
						
						$slq12pp = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $notes_id . "', case_number = '" . $this->db->escape($this->request->post ['case_number']) . "', image_url = '" . $this->db->escape($this->request->post ['upload_file']) . "' , image_name = '" . $this->db->escape($this->request->post ['file_name']) . "' where forms_id = '" . $formreturn_id . "'";
						$this->db->query ( $slq12pp );
						
						$slq12pp = "UPDATE " . DB_PREFIX . "notes SET is_forms = '1', case_number = '" . $this->db->escape($this->request->post ['case_number']) . "' where notes_id = '" . $notes_id . "'";
						$this->db->query ( $slq12pp );
						
						// $this->model_form_form->updatenote($notes_id, $formreturn_id );
					}
				}
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1' 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices addattachments ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'addattachments', $activity_data2 );
		}
	}
	
	
	public function getclassifications() {
		try {
			$this->data ['facilitiess'] = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				
				$this->load->model ( 'notes/notes' );
				
				$ffdata = array (
					'status' => 1, 
					'facilities_id' => $this->request->post ['facilities_id'] 
				);
				
				$casess = $this->model_notes_notes->getTagcasses ($ffdata);
				
				if (! empty ( $casess )) {
					foreach ( $casess as $case ) {
						
						$this->data ['facilitiess'] [] = array (
							'case_id' => $case ['case_id'],
							'name' => $case ['name'] 
						);
					}
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => true 
					);
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "classifications not found" 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => $error 
					);
					
					$this->response->setOutput ( json_encode ( $value ) );
				}
			} else {
				$error = false;
				$value = array (
						'results' => "classifications not found",
						'status' => false 
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in getclassifications ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'getclassifications', $activity_data2 );
			
		}
	}
	
	
	public function autocomplete3 ()
    {
        
        
        $json = array();
        
        
            $this->load->model('setting/tags');
            $this->load->model('form/form');
            
            // $filter_name = str_replace('___-__-____', ' ',
            // $this->request->get['filter_name']);
            
            if ($this->request->post['facilities_id'] != '' && $this->request->post['facilities_id'] != null) {
                $facilities_id = $this->request->post['facilities_id'];
            } else {
                $facilities_id = 0;
            }
			
			if ($this->request->post['allclients'] == '1') {
                $discharge = '2';
                $is_master = '1';
            }
            
            $data = array(
                    //'emp_tag_id_all' => $this->request->post['filter_name'],
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'discharge' => $discharge,
					'is_master' => $is_master,
                    'sort' => 'emp_tag_id',
                    'order' => 'ASC',
                   // 'start' => 0,
                   // 'limit' => CONFIG_LIMIT
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
							'bed_number' => $result ['bed_number'],
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
        
        $this->response->setOutput(json_encode($json));
    }
	
	
	public function medicineautocomplete() {
		
		
		if (utf8_strlen ( $this->request->post ['medicine_filter_name'] ) > 3) {
			
			// $medicineUrl =
			// 'https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search='.$this->request->get['medicine_filter_name'].'&limit=1';Albuterol%20Sulfate
			// $json_url =
			// "https://api.fda.gov/drug/event.json?api_key=Ffl5hlFJHmHfA1eIqvniz4hoQDITFWr7j1CE07c8&search=brand_name:".$this->request->get['medicine_filter_name'];
			$json_url = "https://dailymed.nlm.nih.gov/dailymed/autocomplete.cfm?key=search&returntype=json&term=" . $this->request->post ['medicine_filter_name'];
			$json = file_get_contents ( $json_url );
			$data = json_decode ( $json, TRUE );
			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			
			$json = array ();
			foreach ( $data as $obj ) {
				foreach ( $obj as $a ) {
					$json [] = array (
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
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	
	public function autocompleteroom ()
    {
        
        
        $json = array();
        
        if ($this->request->post['facilities_id'] != '' && $this->request->post['facilities_id'] != null) {
            $facilities_id = $this->request->post['facilities_id'];
        } else {
            $facilities_id = 0;
        }
        
        //if (isset($this->request->get['filter_name'])) {
            $this->load->model('setting/locations');
            $data = array(
                   // 'location_name' => $this->request->get['filter_name'],
                    'facilities_id' => $facilities_id,
                    'status' => '1',
                    'sort' => 'task_form_name',
                    'order' => 'ASC',
                   
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
        //}
        $this->response->setOutput(json_encode($json));
    }
    
    
    public function addforms() {
    	try {
    			
    		$this->load->model ( 'activity/activity' );
    		$this->model_activity_activity->addActivitySave ( 'addforms', $this->request->post, 'request' );
    			
    		$this->data ['facilitiess'] = array ();
    		$this->load->model ( 'facilities/facilities' );
    		$this->load->model ( 'notes/notes' );
    		$this->load->model ( 'setting/tags' );
    		$this->load->model ( 'api/encrypt' );
    		$cre_array = array ();
    		$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
    		$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
    		/*
    		 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
    		 *
    		 * if($api_device_info == false){
    		 * $errorMessage = $this->model_api_encrypt->errorMessage();
    		 * return $errorMessage;
    		 * }
    		 *
    		 * $api_header_value = $this->model_api_encrypt->getallheaders1();
    		 *
    		 * if($api_header_value == false){
    		 * $errorMessage = $this->model_api_encrypt->errorMessage();
    		 * return $errorMessage;
    		 * }
    		 */
    			
    		if ($this->request->post ['facilities_id'] == null && $this->request->post ['facilities_id'] == "") {
    			$json ['warning'] = 'Warning: Enter Facilities Id';
    			$facilitiessee = array ();
    			$facilitiessee [] = array (
    					'warning' => $json ['warning']
    			);
    			$error = false;
    
    			$value = array (
    					'results' => $facilitiessee,
    					'status' => false
    			);
    
    			return $this->response->setOutput ( json_encode ( $value ) );
    		}
    			
    		if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
    			$this->load->model ( 'user/user' );
    			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
    
    			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
    				$json ['warning'] = 'User Pin not valid!.';
    				$facilitiessee = array ();
    				$facilitiessee [] = array (
    						'warning' => $json ['warning']
    				);
    				$error = false;
    					
    				$value = array (
    						'results' => $facilitiessee,
    						'status' => false
    				);
    					
    				return $this->response->setOutput ( json_encode ( $value ) );
    			}
    		}
    			
    		if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
    			$this->load->model ( 'user/user' );
    			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
    
    			if (($user_info ['status'] == '0')) {
    				$json ['warning'] = 'User not exit!';
    				$facilitiessee = array ();
    				$facilitiessee [] = array (
    						'warning' => $json ['warning']
    				);
    				$error = false;
    					
    				$value = array (
    						'results' => $facilitiessee,
    						'status' => false
    				);
    					
    				return $this->response->setOutput ( json_encode ( $value ) );
    			}
    
    			$this->load->model ( 'facilities/facilities' );
    			$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
    			$unique_id = $facility ['customer_key'];
    
    			$this->load->model ( 'customer/customer' );
    			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
    
    			if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
    				$json ['warning'] = $this->language->get ( 'error_customer' );
    				$facilitiessee = array ();
    				$facilitiessee [] = array (
    						'warning' => $json ['warning']
    				);
    				$error = false;
    					
    				$value = array (
    						'results' => $facilitiessee,
    						'status' => false
    				);
    					
    				return $this->response->setOutput ( json_encode ( $value ) );
    			}
    		}
    			
    		if ($json ['warning'] == null && $json ['warning'] == "") {
    			$facilities_id = $this->request->post ['facilities_id'];
	    			$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
	    				
	    			$this->load->model ( 'setting/timezone' );
	    				
	    			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
	    				
	    			date_default_timezone_set ( $timezone_info ['timezone_value'] );
    				
    				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
    				$date_added = ( string ) $noteDate;
    					
    				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
    					
    				$this->load->model ( 'setting/tags' );
    				$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
    					
    				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
    				$data ['tags_id'] = $tag_info ['tags_id'];
    					
    				
    					
    				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
    					$comments = ' | ' . $this->request->post ['comments'];
    				}
    					
    				
    				
    				
    					
    				
    				$data ['date_added'] = $date_added;
    				$data ['note_date'] = $date_added;
    				$data ['notetime'] = $notetime;
    					
    				if ($this->request->post ['is_web'] == '1') {
    					if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
    						$data ['imgOutput'] = urldecode ( $this->request->post ['signature'] );
    					}
    				} else {
    					if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
    						$data ['imgOutput'] = $this->request->post ['signature'];
    					}
    				}
    				
    				
    				$parent_id = $this->request->post ['forms_design_id'];
    				$this->load->model ( 'form/form' );
    				$formdata_i = $this->model_form_form->getFormDatadesign ( $this->request->post ['forms_design_id'] );
    					
    				$data2 = array ();
    				$data2 ['forms_design_id'] = $this->request->post ['forms_design_id'];
    				$data2 ['iframevalue'] = $this->request->post ['iframevalue'];
    				$data2 ['form_design_parent_id'] = $formdata_i ['parent_id'];
    				$data2 ['page_number'] = $formdata_i ['page_number'];
    				$data2 ['form_parent_id'] = '0';
    					
    					
    				$data2 ['facilities_id'] = $this->request->post ['facilities_id'];
    					
    				$pformreturn_id = $this->model_form_form->addFormdata ( $this->request->post, $data2 );
    				
    				
    				$data ['notes_description'] = 'Form ' . $formdata_i ['form_name'] . ' has been added | ' . $description . '' . $comments ;
    					
    				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
    					
    					
    			
    
    			$this->data ['facilitiess'] [] = array (
    					'warning' => '1'
    			);
    			$error = true;
    		} else {
    			$this->data ['facilitiess'] [] = array (
    					'warning' => $json ['warning']
    			);
    			$error = false;
    		}
    			
    		$value = array (
    				'results' => $this->data ['facilitiess'],
    				'status' => $error
    		);
    		$this->response->setOutput ( json_encode ( $value ) );
    	} catch ( Exception $e ) {
    			
    			
    		$this->load->model ( 'activity/activity' );
    		$activity_data2 = array (
    				'data' => 'Error in appservices addforms ' . $e->getMessage ()
    		);
    		$this->model_activity_activity->addActivity ( 'addforms', $activity_data2 );
    	}
    }
	
	
	
	public function userdashboard(){
		try{
		
		
		$this->data['facilitiess'] = array();
		$this->load->model('notes/caseservices'); 
		$this->load->model('setting/tags');
		$this->load->model('createtask/createtask');
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		
		
		$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
		
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		date_default_timezone_set($timezone_info['timezone_value']);
		
		if (isset ( $this->request->post ['tags_id'] )) {
			if ($this->request->post ['tags_id'] != 'undefined') {
				$tags_id = $this->request->post ['tags_id'];
			}
		}
		if (isset ( $this->request->post ['facilities_id'] )) {
			if ($this->request->post ['facilities_id'] != 'undefined') {
				$facilities_id = $this->request->post ['facilities_id'];
			}
		}
		if (isset ( $this->request->post ['search_facilities_id'] )) {
			if ($this->request->post ['search_facilities_id'] != 'undefined') {
				$search_facilities_id = $this->request->post ['search_facilities_id'];
			}
		}
		
		if ($this->request->post ['note_date_from'] != null && $this->request->post ['note_date_from'] != "") {
			if ($this->request->post ['note_date_from'] != 'undefined') {
			$date = str_replace ( '-', '/', $this->request->post ['note_date_from'] );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$note_date_from = $changedDate; // date('Y-m-d', strtotime($this->request->post['note_date_from']));
			}
		}
		if ($this->request->post ['note_date_to'] != null && $this->request->post ['note_date_to'] != "") {
			if ($this->request->post ['note_date_to'] != 'undefined') {
			$date1 = str_replace ( '-', '/', $this->request->post ['note_date_to'] );
			$res1 = explode ( "/", $date1 );
			$changedDate1 = $res1 [2] . "-" . $res1 [1] . "-" . $res1 [0];
			
			$note_date_to = $changedDate1; // date('Y-m-d', strtotime($this->request->post['note_date_to']));
			}
		}
		
		if ($this->request->post ['searchdate'] != null && $this->request->post ['searchdate'] != "") {
			if ($this->request->post ['searchdate'] != 'undefined') {
				$this->data ['note_date'] = $this->request->post ['searchdate'];
				$searchdate = date ( 'Y-m-d', strtotime ( $this->request->post ['searchdate'] ) );
				
				$date = str_replace ( '-', '/', $searchdate );
				$res = explode ( "/", $date );
				
				$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			}
		} 
		
		
		$unique_id = $facilities_info ['customer_key'];
			
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$data = array(
			'emp_tag_id'=>$tags_id,
			'facilities_id'=>$facilities_id,
			'customer_key'=>$facilities_info ['customer_key'],
			'activecustomer_id'=>$customer_info ['activecustomer_id'],
			'note_date_from'=>$note_date_from,
			'note_date_to'=>$note_date_to,
			'status'=>1,
			'discharge'=>1,
		
		);
	
		$totalnotes = $this->model_notes_caseservices->totalNotes($data);
		
		$top = '';
		$totaltasks = $this->model_createtask_createtask->getCountTasklist ( $facilities_id, $changedDate, $top, '', '', '' );
		
		$data2 = array(
			'emp_tag_id'=>$tags_id,
			'facilities_id'=>$facilities_id,
			'customer_key'=>$facilities_info ['customer_key'],
			'activecustomer_id'=>$customer_info ['activecustomer_id'],
			'status'=>1,
			'discharge'=>1,
		
		);
		
		$all_total = $this->model_setting_tags->getTotalTags ( $data2 );
	
		$totalnotifications = 0;
		$this->data['facilitiess'][] = array(
			'totalnotes'=>$totalnotes,
			'totaltasks'=>$totaltasks,
			'totalinmates'=>$all_total,
			'totalnotifications'=>$totalnotifications,
			
		);
		
		$value = array('results'=>$this->data['facilitiess']);
		
		$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask userdashboard '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('userdashboard', $activity_data2);
		}
		
		
	
	}
	
	
}