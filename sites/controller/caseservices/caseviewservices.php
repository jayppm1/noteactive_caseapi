<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllercaseservicescaseviewservices extends Controller {
	private $error = array();
	
	public function jsonCaseNumber() {
		
		try {
			
			$tags_id = $this->request->post['tags_id'];
			
			$this->load->model('resident/casefile');
			$this->load->model('setting/tags');
					
			if($this->request->post['type'] !="" && $this->request->post['type'] !=NULL){
				
				//var_dump($this->request->post['type']);
				
				if($this->request->post['type'] == 'casenumber'){
					$data = array(
						'tags_id'=>$this->request->post['tags_id'],
						'facilities_id'=>$this->request->post['facilities_id'],
						'casenumber'=>$this->request->post['value'],
						);
				}
				
				if($this->request->post['type'] == 'status'){
					$data = array(
						'tags_id'=>$this->request->post['tags_id'],
						'facilities_id'=>$this->request->post['facilities_id'],
						'status'=>$this->request->post['value'],
						);
				}
				
				
				if($this->request->post['type'] == 'incidenttype'){
					$data = array(
						'tags_id'=>$this->request->post['tags_id'],
						'facilities_id'=>$this->request->post['facilities_id'],
						'incident_type'=>$this->request->post['value'],
						);
				}
				
				
				if($this->request->post['type'] == 'code'){
					$data = array(
						'tags_id'=>$this->request->post['tags_id'],
						'facilities_id'=>$this->request->post['facilities_id'],
						'code'=>$this->request->post['value'],
						);
				}
				
				
				
				
			}else{
			
				$data = array(
					'tags_id'=>$this->request->post['tags_id'],
					'facilities_id'=>$this->request->post['facilities_id'],
				);	
			}
			//var_dump($data);
			//var_dump($this->request->post['type'] );
				
			
			$Casenumbes = $this->model_resident_casefile->getcasefiles($data);
			
		
			if($Casenumbes){
	
				foreach($Casenumbes as $Casenumber){
					if($Casenumber['case_status']==0){
						$case_status='Open';
					}elseif($Casenumber['case_status']==1){
						$case_status='Closed';
					}else{
						$case_status='Maked Final';
					}
					
					if($Casenumber['tags_ids']){
						
						$inmate_arr=array();
						$tags_ids_arr = explode(',',$Casenumber ['tags_ids']);
						foreach($tags_ids_arr AS $tag_id){
							$tag_info = $this->model_setting_tags->getTag ( $tag_id );
							$inmate_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
						}
						
						$inmate_name = implode(',',$inmate_arr);
					}else{
						
						$inmate_name='';
					}
					
					
					
					$this->data['facilitiess'][] = array(
					  'case_status' => $case_status,
					  'case_number' => $Casenumber['case_number'],
					  'notes_by_case_file_id' => $Casenumber['notes_by_case_file_id'],
					  'user_id' => $Casenumber['user_id'],
					  'forms_ids' => $Casenumber['forms_ids'],
					  'tags_ids' => $Casenumber['tags_ids'],
					   'inmate_name' => $inmate_name,
					  'signature' => $Casenumber['signature'],
					  'notes_pin' => $Casenumber['notes_pin'],
					  'date_added' => $Casenumber['date_added'],
					);
				}
				
					
			$error = true;
			
			}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select an existing case or add new to start a new file',
			);
			$error = false;
		}			
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}
	
	public function jsonCaseForms(){
		try{
			
			$this->data['facilitiess'] = array();
			$this->language->load('notes/notes');
			$this->load->model('setting/tags');
			$this->load->model('form/form');
			$this->load->model('notes/notes'); 
		
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			} else {
				$page = 1;
			}
		
			$config_admin_limit1 = $this->config->get('config_android_front_limit');
			
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "25";
			}
		
			$this->load->model('resident/casefile');
			$tags_id = $this->request->post['tags_id'];
			
			$data1 = array(
				//'case_file_id' => $this->request->post['case_file_id'],
				'case_file_id' => $this->request->post['case_file_id'],
			);
			
			
			$Casefiles = $this->model_resident_casefile->getcasefile($data1);
			
			$data = array(
				'tags_id'=>$tags_id ,
				'facilities_id'=>$this->request->post['facilities_id'],
				//'custom_form_type'=>$this->request->post['forms_ids'],
				'forms_ids'=>$Casefiles['forms_ids'],
				'case_number'=>$this->request->post['case_number'],
				//'is_case'=>'1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit
				);
						
			$this->load->model('form/form');
			$Caseforms = $this->model_form_form->gettagsforms($data);
		
			if($Caseforms){
	
				foreach($Caseforms as $Caseform){
									
						$note_info = $this->model_notes_notes->getNote ( $Caseform ['notes_id'] );
						$user_id = $note_info ['user_id'];
						$signature = $note_info ['signature'];
						$notes_pin = $note_info ['notes_pin'];
						$notes_type = $note_info ['notes_type'];
						$notes_description = $note_info ['notes_description'];
						
						if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
						} else {
							$form_date_added = '';
						}
											
					$this->data['facilitiess'][] = array(
					  
					  'form_description' => $Caseform['form_description'],
					  'incident_number' => $Caseform['incident_number'],
					  'date_added' => $Caseform['date_added'],
					  'tags_id'=>$tags_id ,
					  'notes_description'=>$notes_description ,
					  'user_id'=>$user_id ,
					  'signature'=>$signature ,
					  'notes_pin'=>$notes_pin ,
					  'notes_type'=>$notes_type ,
					);
				}
			$error = true;
			
			}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'd',
			);
			$error = false;
		}			
			
				
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
	
	}
			
}
 
 