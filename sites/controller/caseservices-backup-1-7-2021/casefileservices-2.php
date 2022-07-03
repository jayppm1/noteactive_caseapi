<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllerservicescasefileservices extends Controller {
	
	
	
	
	public function jsonCaseList() {
		
		try {
			
			$tags_id = $this->request->post['tags_id'];
			
			$this->load->model('notes/notes');
			$Categories = $this->model_notes_notes->getcaseCategories();

			
			$cdata = array (
				'case_number' => 1,
				'tags_id' => $tags_id
			);
			
			//echo '<pre>fff'; print_r($data); echo '</pre>'; //die;
			
			$this->load->model ( 'resident/casefile' );
			$case_info = $this->model_resident_casefile->getcasefiles ( $cdata );

			//echo '<pre>'; print_r($case_info); echo '</pre>'; die;
			
			$this->data ['tagsforms'] = array ();
			
			foreach ( $case_info as $allform ) {
				
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
					$user_id = $note_info ['user_id'];
					$signature = $allform ['signature'];
					$notes_pin = $allform ['notes_pin'];
					$notes_type = $allform ['notes_type'];
					
					if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
						$form_date_added = $allform ['date_added'];
					} else {
						$form_date_added = '';
					}
					// echo 'ssss';
				}
				
				if ($allform ['case_status'] == '0') {
					$client_status = 'Open';
				} else if ($allform ['case_status'] == '1') {
					$client_status = 'Closed';
				} else if ($allform ['case_status'] == '2') {
					$client_status = 'Marked Final';
				}
				
				$this->data ['tagsforms'] [] = array (
						'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
						'forms_id' => $allform ['forms_ids'],
						'notes_type' => $notes_type,
						'user_id' => $user_id,
						'tags_id' => $allform ['tags_ids'],
						'signature' => $signature,
						'notes_id' => $allform ['notes_id'],
						'case_number' => $allform ['case_number'],
						'case_status' => $allform ['case_status'],
						'client_status2' => $client_status,
						'notes_pin' => $notes_pin,
						'form_date_added' => $form_date_added 
				);
			}





			
			$this->response->setOutput ( json_encode ( $this->data ['tagsforms'] ) );
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}
	
	
}
 
 