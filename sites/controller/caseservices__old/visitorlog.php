<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
 
 class Controllercaseservicesvisitorlog extends Controller { 
	
	public function index() {
		
		$this->data['facilitiess'] = array();
		$json = array();
		
		$this->load->model('facilities/facilities');
		$data = array (
				'facilities_id' => $this->request->post['facilities_id']
		);
		$facilities = $this->model_facilities_facilities->getfacilitiess($data );
		
		foreach($facilities as $facility){
			$json['facilitiess'][] = array(
			'facilities_id' => $facility['facilities_id'],
			'facility' => $facility['facility'],
			);
		}
		
		$value = array('results'=>$json['facilitiess'],'status'=>true);
		$this->response->setOutput(json_encode($value));
		
		
	}
	
	public function searchVisitor(){
		$this->data['facilitiess'] = array();
		$json = array();
		
		$this->load->model('activitylog/activitylog');
		
		$json = array();

		//if($this->request->get['activitylog_id'] != null && $this->request->get['activitylog_id'] != "") {

			if (isset($this->request->post['activitylog_id'])) {
				$activitylog_id = $this->request->post['activitylog_id'];
			} else {
				$activitylog_id = '';
			}

			
			//$limit = 20;	
					

			$data = array(
				'activitylog_id'  => $activitylog_id,
				'status'  => '3',
				'facilities_id'  => $this->request->post['facilities_id'],
				//'start'        => 0,
				//'limit'        => $limit
			);
			
			$users = $this->model_activitylog_activitylog->getLogs($data);
			if($users){
			foreach ($users as $user) {
				
				if($user['picture']){
					$imagdata = $user['picture'];
				}
				
				$json['facilitiess'][] = array(
					'full_name' => $user['first_name'] .' '.$user['last_name'],
					'first_name' => $user['first_name'],
					'last_name' => $user['last_name'],
					'activitylog_id' => $user['activitylog_id'],
					'personvisiting' => $user['personvisiting'],
					'company_name' => $user['company_name'],
					'reason' => $user['reason'],
					'state_id' => $user['state_id'],
					'driving_lic' => $user['driving_lic'],
					'type' => $user['type'],
					'imgOutput' => $imagdata,
					'date_added' =>  date("D j, F Y", strtotime($user['date_added'])),
					'checkouttime' =>  date("h:i A", strtotime($user['date_added']))
					
				);	
			}
			}else{
				$json['facilitiess'][] = array(
				'success' => '2',
				);	
			}
		//}

		
		$value = array('results'=>$json['facilitiess'],'status'=>true);
		$this->response->setOutput(json_encode($value));
	}	
	
	public function state() {
		
		$this->data['facilitiess'] = array();
		$json = array();
		
		$this->load->model('setting/zone');
		$state_infos = $this->model_setting_zone->getZonesByCountryId('223');
		
		foreach($state_infos as $state_info){
			$json['facilitiess'][] = array(
			'name' => $state_info['name'],
			'zone_id' => $state_info['zone_id'],
			);
		}
		
		$value = array('results'=>$json['facilitiess'],'status'=>true);
		$this->response->setOutput(json_encode($value));
		
		
	}
	
	
	public function listing() {
			$this->data['facilitiess'] = array();
			$json = array();
	
			$this->load->model('activitylog/activitylog');
			

			$this->load->model('setting/zone');
			$this->data['results'] = array();
			
			if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			} else {
				$sort = 'first_name';
			}

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			if(isset($this->request->post['filter_date_start']) && isset($this->request->post['filter_date_end'])){
				$filter_date_start = $this->request->post['filter_date_start'];
				$this->data['filter_date_start'] = $this->request->post['filter_date_start'];
				$filter_date_end = $this->request->post['filter_date_end'];
				$this->data['filter_date_end'] = $this->request->post['filter_date_end'];
			
			}
			
			if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
				$noteTime =  date('H:i:s');
				
				$date = str_replace('-', '/', $this->request->post['searchdate']);
				$res = explode("/", $date);
				$createdate1 = $res[1]."-".$res[0]."-".$res[2];
				
				$searchdate = $this->request->post['searchdate'];
				
				$this->data['searchdate'] = date('D j, F Y', strtotime($createdate1));
				
			} else {
				$this->data['searchdate'] =  date('D j, F Y');
				$searchdate =  date('m-d-Y');
			}
			
			
			if($this->request->post['filterval'] != NULL && $this->request->post['filterval'] != ""){
				$interval = $this->request->post['filterval'];
				$this->data['filterval'] = $this->request->post['filterval'];
			}else{
				$interval = '';
			}
			
			$data = array(
				'sort'  => $sort,
				'filter_date_end' => $filter_date_end,
				'filter_date_start' => $filter_date_start,
				'searchdate' => $searchdate,
				'facilities_id' => $this->request->post['facilities_id'],
				'status'  => $interval,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_front_limit'),
				'limit' => $this->config->get('config_front_limit')
				);
			
			
			$visitor_total = $this->model_activitylog_activitylog->getTotalLogs($data);
			$alllogs = $this->model_activitylog_activitylog->getLogs($data);
			
			if($alllogs){
			foreach($alllogs as $allog){
				
				$stateinfo = $this->model_setting_zone->getZone($allog['state_id']);
				if($allog['date_updated'] != null && $allog['date_updated'] != '0000-00-00 00:00:00'){
					
					$checkout = date("D j, F Y", strtotime($allog['date_updated']));
					$ckhtime = date("h:i A", strtotime($allog['date_updated']));
				}else{
					$checkout = '-';
					$ckhtime = '-';
				}
				
				if($stateinfo['name']){
					$sta = $stateinfo['name'];
				}else{
					$sta = '3';
				}
				
				
				$json['facilitiess'][] = array(
				'activitylog_id' => $allog['activitylog_id'],
				'first_name' => $allog['first_name'],
				'last_name' => $allog['last_name'],
				'company_name' => $allog['company_name'],
				'personvisiting' => $allog['personvisiting'],
				'reason' => $allog['reason'],
				'state' => $sta,
				'picture' => $allog['picture'],
				'type' => $allog['type'],
				'status' => $allog['status'],
				'checkin' =>  date("D j, F Y", strtotime($allog['date_added'])),
				'checkintime' =>  date("h:i A", strtotime($allog['date_added'])),
				
				'checkout' =>  $checkout,
				'checkouttime' =>  $ckhtime,
				);	
				
			}
			}else{
				$json['facilitiess'][] = array(
				'success' => '2',
				);	
			}
	
		$value = array('results'=>$json['facilitiess'],'status'=>true, 'total'=> $visitor_total);
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function users(){
		
		$this->data['facilitiess'] = array();
		
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
		$this->load->model('user/user'); 
		$users = $this->model_user_user->getUsersByFacility($this->request->post['facilities_id']);
    	
		foreach ($users as $user) {
					
      		$this->data['facilitiess'][] = array(
				'user_id'    => $user['user_id'],
				'username'   => $user['username'],
				'firstname'   => $user['firstname'],
				'lastname'   => $user['lastname'],
				'user_pin'   => $user['user_pin'],
				'phone_number'   => $user['phone_number'],
				'email'   => $user['email'],
			);
		}
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>"No User Found",'status'=>false);
		}
		
		$this->response->setOutput(json_encode($value));
		
	}
	
	
	
	public function addvisitorlog(){
	
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
		
		
		date_default_timezone_set($this->request->post['facilitytimezone'] );
		$noteDate = date('Y-m-d H:i:s', strtotime('now'));
					
		$this->load->model('activitylog/activitylog');
		
		if($this->request->post['activitylog_id']){ 
					
			$this->model_activitylog_activitylog->edit($this->request->post, $this->request->post['activitylog_id']);
			$activitylog_id = $this->request->post['activitylog_id'];
		}else{
			
			$activitylog_id = $this->model_activitylog_activitylog->add($this->request->post, $this->request->post['facilities_id'], $noteDate);
		}
				
		 
		
		$this->data['facilitiess'][] = array(
				'success'  => '1',
				'activitylog_id'  => $activitylog_id, 
			);
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	public function editvisitorlog(){
		
		
		if($this->request->post['activitylog_id'] != null && $this->request->post['activitylog_id'] != ""){
			
		$this->load->model('activitylog/activitylog');
		
		$this->model_activitylog_activitylog->update($this->request->post['activitylog_id'], $this->request->post);
		
		$activitylog_id = $this->request->post['activitylog_id'];
		
		
		$this->data['facilitiess'][] = array(
				'success'  => '1',
				'activitylog_id'  => $activitylog_id,
			);
			
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
	}
	
	public function checkout(){
		
		if($this->request->post['activitylog_id'] != null && $this->request->post['activitylog_id'] != ""){
		 
			$this->load->model('activitylog/activitylog');
			
			date_default_timezone_set($this->request->post['facilitytimezone']);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$logdata = $this->model_activitylog_activitylog->confirm($this->request->post['activitylog_id'], $date_added);
		
			$allData = array();
			$alllogs = $this->model_activitylog_activitylog->getLog($this->request->post['activitylog_id']);
			
			
			$first_name = $alllogs['first_name'];
			$last_name = $alllogs['last_name'];
			$company_name = $alllogs['company_name'];
			$personvisiting = $alllogs['personvisiting'];
			$reason = $alllogs['reason'];
			
			
			date_default_timezone_set($this->request->post['facilitytimezone']);
			
			//$date_added = date("Y-m-d H:i:s", strtotime($alllogs['date_added']));
			$checkintime = date("Y-m-d H:i:s", strtotime($alllogs['date_added']));
			$checkouttime = date("Y-m-d H:i:s", strtotime($alllogs['date_updated']));
			
			$start_date = new DateTime($alllogs['date_added']);
			$since_start = $start_date->diff(new DateTime($alllogs['date_updated']));
			
			$caltime = "";
			/*
			if($since_start->days > 0){
			$caltime .=  $since_start->days.' days total | ';
			}
			*/
			$status_total_time = 0;
						
			if($since_start->y > 0){
				$caltime .= $since_start->y.' years ';
				$status_total_time = 60*24*365*$since_start->y;
			}

			if($since_start->m > 0){
				$caltime .= $since_start->m.' months ';
				$status_total_time += 60*24*30*$since_start->m;
			}

			if($since_start->d > 0){
				$caltime .= $since_start->d.' days ';
				$status_total_time += 60*24*$since_start->d;
			}

			if($since_start->h > 0){
				$caltime .= $since_start->h.' hours ';
				$status_total_time += 60*$since_start->h;
			}

			if($since_start->i > 0){
				$caltime .= $since_start->i.' minutes ';
				$status_total_time += $since_start->i;
			}
			/*if($since_start->s > 0){
			$caltime .= $since_start->s.' seconds ';
			}*/
			
			$notetime = date('H:i:s', strtotime('now'));
			
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$timezone_name = $this->request->post['facilitytimezone'];
			
			$allData['status_total_time']  = $status_total_time;
			$allData['date_added']  = $date_added;
			$allData['note_date']  = $date_added;
			$allData['notetime']  = $notetime;
			$allData['facilitytimezone']  = $timezone_name;
			
			$allData['user_id']  = 'admin';
			$allData['notes_pin']  = '123';
			$allData['visitor_log']  = '2';
			
			$allData['notes_description']  = 'Visitor Log Entry | '.$first_name.'&nbsp;'.$last_name.' | has CHECKED OUT TIME | '.$caltime.' | of the Facility';
			
			
			$this->load->model('notes/notes');
			$this->model_notes_notes->jsonaddnotes($allData, $this->request->post['facilities_id']);
		
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
	}
	
	public function visitordeatil(){
		
		 if($this->request->post['activitylog_id'] != null && $this->request->post['activitylog_id'] != ""){
		 
		 $this->load->model('activitylog/activitylog');
	 	 $logdata = $this->model_activitylog_activitylog->getLog($this->request->post['activitylog_id']);
		
		
		$this->data['facilitiess'][] = array(
				'success'  => '1',
				'visitorinfo'  => $logdata,
			);
			
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}
	
	public function complete(){
		
		if($this->request->post['activitylog_id'] != null && $this->request->post['activitylog_id'] != ""){
		 
			$allData = array();
		
			date_default_timezone_set($this->request->post['facilitytimezone']);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
					
			$this->load->model('activitylog/activitylog');
			$alllogs = $this->model_activitylog_activitylog->getLog($this->request->post['activitylog_id']);
			
			
			$first_name = $alllogs['first_name'];
			$last_name = $alllogs['last_name'];
			$company_name = $alllogs['company_name'];
			$personvisiting = $alllogs['personvisiting'];
			$reason = $alllogs['reason'];
			
			
			$picture = $alllogs['picture'];
			$imgOutput = $alllogs['imgOutput'];
			
			$date_added = date("Y-m-d H:i:s", strtotime($alllogs['date_added']));
			$checkintime = date("h:i A", strtotime($alllogs['date_added']));
			
			$notetime = date('H:i:s', strtotime('now'));
			
			$allData['date_added']  = $date_added;
			$allData['note_date']  = $date_added;
			$allData['notetime']  = $notetime;
			$allData['facilitytimezone']  = $timezone_name;
			
			$allData['user_id']  = 'admin';
			$allData['notes_pin']  = '123';
			$allData['visitor_log']  = '1';
			
			if($picture){
				$allData['notes_file']  = $picture;
				$allData['notes_media_extention']  = 'jpg';
			}else{
				$allData['notes_file']  = $imgOutput;
				$allData['notes_media_extention']  = 'jpg';
			}
			
			$allData['notes_description']  = 'Visitor Log Entry | '.$first_name.'&nbsp;'.$last_name.' | has checked in to the facility at |'.$checkintime.' | to meet with | '.$personvisiting.' | for |'.$reason.' | ';

			
			$this->load->model('notes/notes');
			
			$this->model_notes_notes->jsonaddnotes($allData, $this->request->post['facilities_id']);
			
			
		
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
	}
	
 }