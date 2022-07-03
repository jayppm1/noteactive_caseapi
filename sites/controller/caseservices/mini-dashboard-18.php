<?php
class ControllercaseservicesminiDashboard extends Controller {
	private $error = array ();
	
	public function getTaskCounts() {
		try{
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			if($this->request->post['activecustomer_id']!=''){
				$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			}else{
				$datamd['activecustomer_id'] = '';
			}
			$datamd['username'] = $this->request->post['username'];
			$datamd['details_of'] = 'task';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	
	public function getToDoTaskList() {
		try{
			
			//$unique_id = $this->request->post['customer_key'];
			//$this->load->model ( 'customer/customer' );
			//$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['details_of'] = 'todotasklist';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getToDoTaskList ( $datamd );
			$toto_arr =array();
			if(count($datamd ['total_data'])>0){
				foreach($datamd ['total_data'] AS $row){
					$toto_arr[] = $row['description'];
				}
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'Fail';
			}
			
			$value = array('results'=>$toto_arr,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getNotesCounts() {
		try{
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $customer_info['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['customer_key'] = $customer_info['customer_key'];
			$datamd['details_of'] = 'notes';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getNotesCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getNotesCounts', $activity_data);
		}
	}
	
	public function getIncidentCounts() {
		try{
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['customer_key'] = $customer_info['customer_key'];
			$datamd['details_of'] = 'incident';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getInmetCounts() {
		try{
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByUsernamebysaml($this->request->post['username']);
				
			//echo '<pre>'; print_r($user_info); echo '</pre>';
			
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['user_id'] = $user_info['user_id'];
			$datamd['customer_key'] = $customer_info['customer_key'];
			$datamd['details_of'] = 'inmate';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	
	public function getACACounts() {
		try{
			$this->load->model('notes/acarules');
			$this->load->model('notes/notes');
			$this->load->model ( 'setting/keywords' );
			$datamd = array ();
			$aca_data = array();
			$this->load->model('user/user');
			$this->load->model ( 'customer/customer' );
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			$customer_key = $customer_info['customer_key'];
			$user_id = $this->request->post['username'];
			$datamd['activecustomer_id'] = $activecustomer_id;
			$acas = $this->model_notes_acarules->getAcarules($datamd);
			$i=0;
			foreach ( $acas as $aca ) {
				//echo '<pre>'; print_r($aca['keyword_id']); echo '</pre>';
				$data = array(
					//'group' => '1',
					//'searchdate_app' => '1',
					'keyword_id' => $aca['keyword_id'],
					'user_id' => $user_id,
					'activecustomer_id' => $customer_key
					//'emp_tag_id' => $search_emp_tag_id,
					//'advance_searchapp' => $advance_search,
					//'tasktype' => $tasktype,
					//'start' => ($page - 1) * $config_admin_limit,
					//'limit' => $config_admin_limit
				);
        
				$results = $this->model_notes_notes->getAllNotebyactivenotes($data);
				$total_notes[] = $results['total'];
				
				$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
				if($keywordinfo['keyword_image'] != ''){
					$keyword_file = $keywordinfo['keyword_image'];
				}else{
					$keyword_file ='';
				}
				
				$aca_data2 [] = array(
					'rules_name' => $aca['rules_name'],
					'keyword_file' => $keyword_file
				);
				$i++;
			}
			
			arsort($total_notes);
			foreach($total_notes AS $each_notes){
				$total_notes2[] = $each_notes;
			}
			//print_r($total_notes2);
			if(count($aca_data2)>0){
				$i=0;
				foreach($aca_data2 AS $ddd){
					$aca_data [] = array(
						'rules_name' => $ddd['rules_name'],
						'keyword_file' => $ddd['keyword_file'],
						'total_notes'=> $total_notes2[$i]
					);	
					$i++;
				}
			}
			
		
			if(count($aca_data)>0){
				$status = true;
				$message  = 'ACA Standard Listed';
			}else{
				$status = false;
				$message  = 'Data not found';
			}
			
			$newArray = array_slice($aca_data, 0, 6, true);
			$value = array('results'=>$newArray,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getACACounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getACACounts', $activity_data);
		}
	}
	
	
	public function getACACounts_old2() {
		try{
			$this->load->model('notes/acarules');
			$this->load->model ( 'setting/keywords' );
			$datamd = array ();
			$aca_data = array();
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByUsernamebysaml($this->request->post['username']);
			$user_id = $user_info['user_id'];
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$acas = $this->model_notes_acarules->getAcarules($datamd);
		
			foreach ( $acas as $aca ) {
			
				$rac =  unserialize($aca['rule_action_content']);
				if(in_array($user_id,$rac['auserids'])){
					//echo '<pre>'; print_r($rac['auserids']); echo '</pre>';
					$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
					if($keywordinfo['keyword_image'] != ''){
						$keyword_file = $keywordinfo['keyword_image'];
					}else{
						$keyword_file ='';
					}
					$aca_data [] = array(
						'rules_name' => $aca['rules_name'],
						'keyword_file' => $keyword_file,
						'total_notes'=> 5,
					);
				}
			}
		
			if(count($aca_data)>0){
				$status = true;
				$message  = 'ACA Standard Listed';
			}else{
				$status = false;
				$message  = 'Data not found';
			}
			
			$newArray = array_slice($aca_data, 0, 5, true);
			$value = array('results'=>$newArray,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getACACounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getACACounts', $activity_data);
		}
	}
	
	
	
	public function getACACounts_old() {
		try{
			$this->load->model('notes/acarules');
			$this->load->model ( 'setting/keywords' );
			$acas = $this->model_notes_acarules->getAcarules();
			
			foreach ( $acas as $aca ) {
				
				//echo '<pre>'; print_r($aca); echo '</pre>';
				
				$ddd =  unserialize($aca['rule_action_content']);
				
				//echo '<pre>'; print_r($ddd); echo '</pre>';
				
				$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
				if($keywordinfo['keyword_image'] != ''){
					$keyword_file = $keywordinfo['keyword_image'];
				}else{
					$keyword_file ='';
				}
				
				$aca_data [] = array(
					'rules_name' => $aca['rules_name'],
					'keyword_file' => $keyword_file,
					'total_notes'=> 5,
				);		
			}
		
		
			if(count($aca_data)>0){
				$status = true;
				$message  = 'ACA Standard Listed';
			}else{
				$status = false;
				$message  = 'Data not found';
			}
			
			$value = array('results'=>$aca_data,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getACACounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getACACounts', $activity_data);
		}
	}
	
	
	
	
	
		
}

/*caseservices/mini-dashboard/getTaskCounts
caseservices/mini-dashboard/getToDoTaskList
caseservices/mini-dashboard/getNotesCounts
caseservices/mini-dashboard/getIncidentCounts
caseservices/mini-dashboard/getACACounts
caseservices/mini-dashboard/getInmetCounts*/