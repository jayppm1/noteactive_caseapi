<?php
class Controllernotesacarules extends Controller {
	private $error = array ();

	public function index() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			
			if (! $this->customer->isLogged ()) {
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			
			$timezone_name = $this->customer->isTimezone ();
			
			
			date_default_timezone_set ( $timezone_name );
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$this->data ['customers'] = array ();
			$setting_data = unserialize($customer_info['setting_data']);
			$this->data['enable_standards'] = $setting_data['enable_standards'];
			
			$activecustomer_id = $customer_info ['activecustomer_id'];
			
			$this->load->model('notes/acarules');
			$data ['facilities_id'] = $facilities_id;
			$data ['activecustomer_id'] = $activecustomer_id;
			
			$acas = $this->model_notes_acarules->getacarule($data);
			
			$facilities_id_arr2 = array();
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/keywords' );
			$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
				
				$ddss [] = $facilityinfo ['notes_facilities_ids'];
				$ddss [] = $facilities_id;
				
				$sssssddsg = explode ( ",", $facilityinfo ['notes_facilities_ids'] );
				
				$abdcg = array_unique ( $sssssddsg );
				$cids = array ();
				
				foreach ( $abdcg as $fid ) {
					$cids [] = $fid;
				}
				
				$abdcgs = array_unique ( $cids );
				
				//echo '<pre>aaa'; print_r($ddss); echo '</pre>';
				
				foreach ( $abdcgs as $fid2 ) {
					$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
					if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
						$ddss [] = $facilityinfo ['notes_facilities_ids'];
					}
				}	
				
				$sssssdd = implode ( ",", array_unique($ddss ));
				$facilities_id_arr2 = explode(',',$sssssdd);
			}
			
			//echo 'Current Date - '.date('Y-m-d h:i:s A');
			
			$current_time = strtotime(date('Y-m-d H:i'));
			$missed_time_arr=array();	
		    $this->data ['acas']=array();
			$k=1;
			$count_arr = array();
			foreach ( $acas as $aca ) {
				
				//echo '<pre>'; print_r($aca); echo '</pre>';
				$upcomming_notes_time=0;
				$upcomming_notes_time_label = '';
				$count_arr = array();
				$missed_time_arr =array();
				if(!empty($facilities_id_arr2)){
					$facilities_id_arr = explode(',',$aca['facilities_id']);
					$facilities_id_arr3 = array_intersect($facilities_id_arr,$facilities_id_arr2);
				}else{
					$facilities_id_arr3 = explode(',',$aca['facilities_id']);
				}
				
				$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
				
				//echo '<pre>keywordinfo-'; print_r($keywordinfo); echo '</pre>';
				
				if($keywordinfo['keyword_image'] != ''){
					$keyword_file = $keywordinfo['keyword_image'];
				}else{
					$keyword_file ='';
				}
				
				$data = array();
				$data['keyword_id'] = $aca['keyword_id'];
				$data['rules_type'] = $aca['rules_type'];
				$data['facilities_id'] = $facilities_id;
				$data['rules_start_time'] = $aca['rules_start_time'];
				$data['rules_end_time'] = $aca['rules_end_time'];
				$data['rules_operation'] = $aca['rules_operation'];
				$data['recurnce_week_from'] = $aca['recurnce_week_from'];
				$data['recurnce_week_to'] = $aca['recurnce_week_to'];
				$data['recurnce_day_from'] = $aca['recurnce_day_from'];
				$data['recurnce_day_to'] = $aca['recurnce_day_to'];
				$data['shift_id'] = $aca['shift_id'];
				
				$notesData = $this->model_notes_acarules->getotalnote($data);
				
				$missed_count = $this->model_notes_acarules->get_missed_count($data);
				
				if($missed_count['total']!=''){
					$missed_total = $missed_count['total'];
				}else{
					$missed_total = 0;
				}				
				
				$data['keyword_id'] = $aca['keyword_id'];
				$data['rules_type'] = $aca['rules_type'];
				$data['facilities_id'] = $facilities_id;
				$data['rules_start_time'] = $aca['rules_start_time'];
				$data['rules_end_time'] = $aca['rules_end_time'];
				$data['rules_operation'] = $aca['rules_operation'];
				$data['recurnce_week_from'] = $aca['recurnce_week_from'];
				$data['recurnce_week_to'] = $aca['recurnce_week_to'];
				$data['recurnce_day_from'] = $aca['recurnce_day_from'];
				$data['recurnce_day_to'] = $aca['recurnce_day_to'];
				$data['shift_id'] = $aca['shift_id'];
				$nactive = $this->model_notes_acarules->getnoteactivedetails($data); 
				//echo '<pre>AAA-'.$aca['keyword_name']; print_r($nactive); echo '</pre>';	
				$missed_count = $this->model_notes_acarules->get_missed_count($data);
				if($missed_count['total']!=''){
					$missed_total = $missed_count['total'];
				}else{
					$missed_total = 0;
				}
				
				$date1 = strtotime(date('Y-m-d', strtotime($nactive['date_added'])));
				$date2 = strtotime(date('Y-m-d'));
				
				$current_date = date('Y-m-d');
				$start_time = $aca['rules_start_time'];
				
				if($date1 < $date2){
					$db_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				}else{
					$db_time = $nactive['date_added'];
				}
				
				$db_time2 = strtotime($db_time);
				
				$date_added = $db_time2;
				
				$missed_time_interval=0;
				
				if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==1){
					$missed_time_interval = $aca['missed_time'];
				}else if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==2){
					$missed_time_interval = $aca['missed_time']*60;
				}else if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==3){
					$missed_time_interval = $aca['missed_time']*60*24;
				}
				
				if($missed_time_interval!=0){
					$missed_time_interval = $missed_time_interval*60;
				}
				
				$interval=0;
				if(isset($aca['duration_type']) && $aca['duration_type']==1){
					$interval = $aca['interval'];
				}else if(isset($aca['duration_type']) && $aca['duration_type']==2){
					$interval = $aca['interval']*60;
				}else if(isset($aca['duration_type']) && $aca['duration_type']==3){
					$interval = $aca['interval']*60*24;
				}
				
				if($interval!=0){
					$interval = $interval*60;
				}
				
				$is_custom_offset_interval=0;
				if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==1){
					$is_custom_offset_interval = $aca['is_custom_offset'];
				}else if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==2){
					$is_custom_offset_interval = $aca['is_custom_offset']*60;
				}else if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==3){
					$is_custom_offset_interval = $aca['is_custom_offset']*60*24;
				}
				
				if($is_custom_offset_interval!=0){
					$is_custom_offset_interval = $is_custom_offset_interval*60;
				}
				
				
				$j=1;
				
				$rules_end_time = strtotime($aca['rules_end_time']);
				if($aca['no_of_recurrence']!='' && $aca['missed_count']>0){
					$count_arr = array();
					$missed_time_arr=array();
					$recurrence_end=1;
				}else{
					$recurrence_end=0;
					$current_date = date('Y-m-d');
					$start_time = $aca['rules_start_time'];
					$end_time = $aca['rules_end_time'];
					$rules_start_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
					$rules_end_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
					
					if($notesData['total'] > 0){
						for($i=$current_time; $i<$rules_end_time; $i++){
							$date_added = $date_added+$interval;
							$missed_time2 = $date_added+$missed_time_interval;
							$missed_time = date('Y-m-d h:i A',$missed_time2);
							$upcomming_time = date('Y-m-d h:i A',$date_added);
							$upcomming_arr[] = $upcomming_time;
							$due_arr[] = $missed_time;
							$count_arr[$upcomming_time] = $missed_time;
							if(($current_time<=$date_added) || ($date_added > $rules_end_time) || ($missed_time > $current_time	)){
								break;
							}
						}
					}
				}
				
				//echo '<pre>AAA-'.$aca['keyword_name']; print_r($count_arr); echo '</pre>';	
				
				$missed_arr = array();
				$flag = 0;	
				foreach($count_arr AS $key=>$val){
					if(strtotime($key) <= $current_time && $current_time < strtotime($val) && $flag==0){
						$flag=1;
						$upcomming_notes_time1 = $key;
					}
				}
				
				$iii=1;
				$number_of_missed_arr = array();
				foreach($count_arr AS $key=>$val){
					if(strtotime($val) < $current_time){
						$number_of_missed_arr[] = $iii;
					}	
					$iii++;	
				}
				
				$flag2=0;
				foreach($count_arr AS $key=>$val){
					if($current_time < strtotime($key) && $flag2==0){
						$flag2=1;
						$upcomming_notes_time2 = $key;	
					}	
				}
				
				
				
				$current_date = date('Y-m-d');
				$start_time2 = $aca['rules_start_time'];
				$end_time2 = $aca['rules_end_time'];
				
				
				$rules_start_time3 = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time2));
				$rules_end_time3 = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time2));
				
				if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){	
					if($current_time < (strtotime($rules_start_time3)+180)){
						$upcomming_notes_time= date('h:i A',strtotime($db_time)+180);
						$upcomming_notes_time_label = 'Upcoming';
					}else if($current_time > (strtotime($rules_end_time3)+180)){
						$upcomming_notes_time= date('h:i A',strtotime($db_time)+180);
						$upcomming_notes_time_label = 'Finished';
					}else{
						$upcomming_notes_time= date('h:i A',strtotime($db_time)+180);
						$upcomming_notes_time_label = 'Due';
					}
				}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!=""){	
					if($current_time < strtotime($rules_start_time3)){	
						$upcomming_notes_time= date('h:i A',strtotime($db_time)+$is_custom_offset_interval);
						$upcomming_notes_time_label = 'Upcoming';
					}else if($current_time > strtotime($rules_end_time3)){
						$upcomming_notes_time= date('h:i A',strtotime($db_time)+$is_custom_offset_interval);
						$upcomming_notes_time_label = 'Finished';
					}else{
						$upcomming_notes_time= date('h:i A',strtotime($db_time)+$is_custom_offset_interval);
						$upcomming_notes_time_label = 'Due';
					}
				}else{
					if($flag==1){
						$upcomming_notes_time=date('h:i A',strtotime($upcomming_notes_time1));
						$upcomming_notes_time_label = 'Due';
					}elseif($flag2==1){
						$upcomming_notes_time=date('h:i A',strtotime($upcomming_notes_time2));
						$upcomming_notes_time_label = 'Upcoming';
					}else{
						$upcomming_notes_time_label='Upcoming';
					}
				}
					
				if($aca['no_of_recurrence']!="" && $aca['no_of_recurrence'] <= $notesData['total']){ 
					
					$data['keyword_id'] = $aca['keyword_id'];
					$data['rules_type'] = $aca['rules_type'];
					$data['facilities_id'] = $facilities_id;
					$data['rules_start_time'] = $aca['rules_start_time'];
					$data['rules_end_time'] = $aca['rules_end_time'];
					$data['rules_operation'] = $aca['rules_operation'];
					$data['recurnce_week_from'] = $aca['recurnce_week_from'];
					$data['recurnce_week_to'] = $aca['recurnce_week_to'];
					$data['recurnce_day_from'] = $aca['recurnce_day_from'];
					$data['recurnce_day_to'] = $aca['recurnce_day_to'];
					$data['shift_id'] = $aca['shift_id'];
					$data['is_recurrence_check'] =1;
				
					$missed_count2 = $this->model_notes_acarules->get_missed_count($data);
					
					if($missed_count2['total']!=''){
						$missed_count2 = $missed_count2['total'];
					}else{
						$missed_count2 = 0;
					}
				
					$data2['rules_id'] = $aca['rules_id'];
					$data2['missed_count'] = $missed_count2;
					$this->model_notes_acarules->update_missed_count($data2); 	
				}
				
				if(count($count_arr)>1){
					$count_missed_without_note = count($count_arr)-1;
				}else{
					$count_missed_without_note = count($count_arr);
				}
				
				
				$missed_total = $missed_total + count($number_of_missed_arr);
				
				$rules_start_time = 0;
				
				if($aca['rules_start_time']!=''){
					$rules_start_timexx = strtotime(date('Y-m-d'). $aca['rules_start_time']);
					$rules_start_time333 = date('h:i A',$rules_start_timexx+$interval);
				}
				
				$not_anchor_tag = 0;
				if($upcomming_notes_time==0){
					$upcomming_notes_time = $rules_start_time333;
					$not_anchor_tag = 1;
				}
				
				if($recurrence_end){
					$upcomming_notes_time = 'Recurrence End';
					$not_anchor_tag = 1;
				}
				
				if($current_time > strtotime($aca['rules_end_time'])){
					$upcomming_notes_time = date('h:i A',strtotime($aca['rules_end_time']));
					$upcomming_notes_time_label='Finished';
					$not_anchor_tag = 1;
				}
				
				if(($current_time+$missed_time_interval+$interval) < strtotime($upcomming_notes_time)){
					$not_anchor_tag = 1;
				}
				
					
				$duration_type=0;
				
				if(isset($aca['duration_type']) && $aca['duration_type']==3){
					$duration_type=1;
				}
					
				$keyword_id = $aca['keyword_id'];
				$popup_url = 'notes/notes/activenote&activenoteids='.$keyword_id.'&action=activenote&acarule=1';
				$keyword_name = $aca['keyword_name'];
			
				$facilityinfo2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					
					$this->data ['acas'] [] = array(
						'date_added' => $end,
						'upcomming_notes_time' => $upcomming_notes_time,
						'upcomming_notes_time_label' => $upcomming_notes_time_label,
						'missed' => $missed_total,
						'not_anchor_tag' => $not_anchor_tag,
						'rules_start_time' => $rules_start_time,
						'no_of_recurrence_meet'=> $no_of_recurrence_meet,
						'rules_name' => $aca['rules_name'],
						'rule_type' => $aca['rules_type'],
						'keyword_name' => $keyword_name,
						'keyword_id'=> $keyword_id,
						'no_of_recurrence' => $aca['no_of_recurrence'],
						'facilities_id' => $aca['facilities_id'],
						'facility' => $facilityinfo2['facility'],
						'keyword_file' => $keyword_file,
						'total'=> ($notesData['total']) ? $notesData['total'] : '0',
						'hints' => $aca ['hints'],
						'duration_type'=> $duration_type,
						'upcomming_date'=> $upcomming_date,
						'popup_url' => $this->url->link ( $popup_url, '', 'SSL' )
					);
					
					$count_arr=array();
				$k++;	
			}
			
			//echo '<pre>acas-'; print_r($this->data ['acas']); echo '</pre>'; 
			//die;
			
			$url2 = "";
			if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			}
			
			$this->data ['action'] = $this->url->link ( 'notes/createtask', '' . $url2, 'SSL' );
			$this->data['noteactivepop_url'] = $this->url->link ( 'notes/notes/activenote', '', 'SSL' );
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/acarules.php';
			$this->children = array (
				'common/headerclient',
				'common/footerclient' 
			);
			$this->response->setOutput ( $this->render () );
			
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array ('data' => 'Error in Sites acarules List'.$e->getMessage());
			$this->model_activity_activity->addActivity ( 'acarules', $activity_data2 );	
		}
	}
	
	public function acastandarsign(){
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'setting/keywords' );
		$this->load->model ( 'setting/locations' );
		$facilities_id = $this->customer->getId ();
		
		if ($this->request->get ['facilities_id'] != "" && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $facilities_id;
		}
		
		if (! empty ( $this->session->data ['username_confirm'] )) {
			$user_id = $this->session->data ['username_confirm'];
		} else {
			$user_id='';
		}
		
		$this->data ['user_id'] = $user_id;
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $facilities_id;
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );

		$this->language->load ( 'notes/notes' );

		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );

		$this->load->model ( 'notes/notes' );
		
		if (($this->request->post ['form_submit']==1) && $this->validateacarule ()) {
			
			if (isset ( $this->request->post ['select_one'] )) {
				$this->data ['select_one'] = $this->request->post ['select_one'];
			} else {
				if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
					$config_default_sign = '1'; // $this->config->get('config_default_sign');
				} else {
					$config_default_sign = '2';
				}
				$this->data ['select_one'] = $config_default_sign;
			}
			
			$data = array();
			
			if($this->request->post ['action_type'] && $this->request->post ['action_type']=='location'){
				
				$locationids = explode(',',$this->request->post ['locationids']);
				
				if ($locationids != null && $locationids != "") {
					$locationsid = array ();
					foreach ( $locationids as $locid ) {
						
						$location_info12 = $this->model_setting_locations->getlocation ( $locid );
						
						$locationname1 .= ' '.$location_info12 ['location_name'] . ' | ';
						
						$locationsid [] = $location_info12 ['locations_id'];
					}
					
					$this->request->post ['locationsid'] = $locationsid;
					$notesdesc .= $locationname1;
					$data = array();
					$data ['form_key'] = $this->request->post ['form_key'];
					$data ['note_date'] = date('d-m-Y H:i:s');
					$data ['locationsid'] = $locationsid; //$this->request->post ['locationids'];
					$data ['notetime'] = date('h:i A');
					$data ['notes_description'] = $notesdesc;
					$data ['note_date'] = date('d-m-Y H:i:s');
								
					if ($this->request->post ['imgOutput']!='') {
						$data ['imgOutput'] = $this->request->post ['imgOutput'];
					}
					
					if ($this->request->post ['notes_pin']!='') {
						$data ['notes_pin'] = $this->request->post ['notes_pin'];
					}
					
					if ($this->request->post ['username']!='') {
						$data ['user_id'] = $this->request->post ['username'];
					}
				}
				
				
			}else if($this->request->post ['action_type'] && $this->request->post ['action_type']=='activenote'){
				
				$activenoteids = explode(',',$this->request->post ['activenoteids']); 
				
				//echo '<pre>'; print_r($activenoteids); echo '</pre>'; //die;
				
				foreach($activenoteids AS $row){	
					$keywords = $this->model_setting_keywords->getkeywordDetail ( $row );
					$keyword_name_arr[] = $keywords['keyword_name'];
					$img_files_arr[] = $keywords['keyword_image'];
				} 
						
				$data ['form_key'] = $this->request->post ['form_key'];
				$data ['note_date'] = date('d-m-Y H:i:s');
				$data ['keyword_file'] = implode(',',$img_files_arr);
				$data ['multi_keyword_file'] = implode(',',$img_files_arr);
				$data ['activenoteids'] = $this->request->post ['activenoteids'];
				$data ['notetime'] = date('h:i A');
				//$data ['active_note_description'] = 'Assessment Completed |  Baker Acts Bed Check with Sight and Sound Completed |'; //implode(',',$keyword_name_arr);
				$data ['notes_description'] = implode(' ',$keyword_name_arr);
				$data ['note_date'] = date('d-m-Y H:i:s');
							
				if ($this->request->post ['imgOutput']!='') {
					$data ['imgOutput'] = $this->request->post ['imgOutput'];
				}
				
				if ($this->request->post ['notes_pin']!='') {
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
				}
				
				if ($this->request->post ['username']!='') {
					$data ['user_id'] = $this->request->post ['username'];
				}
			}
			
			$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
							
			$this->load->model ( 'facilities/facilities' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122 );
			}
			
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
					
					$notes_file = $this->session->data ['local_notes_file'];
					$outputFolder = $this->session->data ['local_image_dir'];
					require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$this->load->model ( 'notes/notes' );
					$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
					if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
						$this->model_notes_notes->updateuserverified ( '2', $notes_id );
					}
					
					if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
						$this->model_notes_notes->updateuserverified ( '1', $notes_id );
					}
				}
			}
						
			
			$this->session->data ['success_acarule']=' ACA standards notes added successfully .';
			
			
			
		}	
			
		if (isset ( $this->request->post ['select_one'] )) {
			$this->data ['select_one'] = $this->request->post ['select_one'];
		} else {
			if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
				$config_default_sign = '1';
			} else {
				$config_default_sign = '2';
			}
			$this->data ['select_one'] = $config_default_sign;
		}
		
		

	
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/acarules/acastandarsign', '' . $url2, 'SSL' ) );

		$this->data ['aca_redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/acarules', '' . $url2, 'SSL' ) );

		if($this->request->get ['activenoteids']){
			$this->data ['activenoteids'] = $this->request->get ['activenoteids'];
		}else{
			$this->data ['activenoteids'] = '';
		}
		
		if($this->request->get ['locationids']){
			$this->data ['locationids'] = $this->request->get ['locationids'];
		}else{
			$this->data ['locationids'] = '';
		}
		
		if($this->request->get ['acarule']==1){
			$this->data ['acarule'] = $this->request->get ['acarule'];
		}else{
			$this->data ['acarule'] = '';
		}
		
		if($this->request->get ['action']!=''){
			$this->data ['action_type'] = $this->request->get ['action'];
		}else{
			$this->data ['action_type'] = '';
		}


		if (isset ( $this->session->data ['success_acarule'] )) {
			$this->data ['success_acarule'] = $this->session->data ['success_acarule'];
			unset ( $this->session->data ['success_acarule'] );
		} else {
			$this->data ['success_acarule'] = '';
		}

		if (isset ( $this->session->data ['session_notes_description'] )) {
			$this->data ['comments'] = $this->session->data ['session_notes_description'];
			unset ( $this->session->data ['session_notes_description'] );
		} else if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}

		if (isset ( $this->session->data ['success_update_form_2'] )) {
			$this->data ['success_update_form_2'] = $this->session->data ['success_update_form_2'];
			unset ( $this->session->data ['success_update_form_2'] );
		} else {
			$this->data ['success_update_form_2'] = '';
		}


		
		

		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}

		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';

		$this->children = array ('common/headerpopup');

		$this->response->setOutput ( $this->render () );
		
	}
	
	protected function validateacarule() {
		
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
	
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
	
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = $this->language->get ( 'error_required' );
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
				$this->error ['user_id'] = $this->language->get ( 'error_customer' );
			}
			
			if ($this->request->get ['client'] == "1") {
				
				if (! empty ( $user_info ['user_group_id'] )) {
					$this->load->model ( 'user/user_group' );
					$user_group_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					if (! empty ( $user_group_info )) {
						$this->session->data ['show_hidden_info'] = $user_group_info ['show_hidden_info'];
					}
				}
			}
		}
		
		if ($this->request->post ['notes_pin'] == '') {
			$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
			$this->load->model ( 'user/user' );
			
			// $user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$user_info = $this->model_user_user->getUserByUsername ( $this->request->post ['user_id'] );
			} else {
				$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'], $this->customer->getId () );
			}
			
			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
				$this->error ['notes_pin'] = $this->language->get ( 'error_exists' );
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}


	public function acaemailtemplate($result){
		
		//echo '<pre>tttt'; print_r($result); echo '</pre>'; //die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>This is an Automated Alert Email</title>

		<style>
		@media screen and (max-width:500px) {
		   h6 {
				font-size: 12px !important;
			}
		}
		</style>
		</head>
 
		<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

		<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
			<tr>
				<td></td>
				<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
					

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
						<table style="width: 100%;">
							<tr>
								<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
								<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">This is an Automated Alert Email</h6></td>
							</tr>
						</table>
					</div>
					
				</td>
				<td></td>
			</tr>
		</table>

		<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
			<tr>
				<td></td>
				<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
						<table>
							<tr>
								<td>
									
									<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello User!</h1>
									<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$result['rule_name'].'! Please review the details below for further information or actions:</p>
									
								</td>
							</tr>
						</table>
					</div>
					<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
						
						<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
							<tr>
								<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></td>
								<td>
									<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['rule_name'].'</small></h4>
									<div style="float: left;"><img src="'.$result['keyword_file'].'" style="width:30px;" /><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;"></div><div style="margin-top: 8px;">'.$result['notes_description'].'</p></div>
								</td>
							</tr>
						</table>
					
					</div>
					
					
					<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;">
							<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></td>
							<td>
								<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								'.$result['date_added'].'&nbsp;'.$result['notetime'].'
								</p>
							</td>
						</tr>
					</table></div>
					
					
					
					<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'.$result['where'].'</p>
					</td>
				</tr>
			</table></div>
					
					
					
					
					
				</td>
				<td></td>
			</tr>
		</table>

		</body>
		</html>';
		return $html;
	}
	
	
	public function persecondScript(){
		
		echo '<script> setTimeout(function(){ window.location.reload(1);}, 60000); </script>';
		
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'customer/customer' );
		$this->load->model ( 'setting/keywords' );
		$this->load->model ( 'setting/locations' );
		$this->load->model ( 'notes/notes' );
		$this->language->load ( 'notes/notes' );	
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/timezone' );	
		//$facilities_id = $this->customer->getId ();
		$this->load->model('notes/acarules');
		
		$acas = $this->model_notes_acarules->getacaruleautonote();
		$count=1;
		foreach ( $acas as $aca ) {
			//echo '<pre>aaa'; print_r($aca); echo '</pre>'; //die;	
		
			$faci_arr = explode(',',$aca['facilities_id']);
			
			
			
			foreach($faci_arr AS $frow){
				
				//echo '<pre>ttt-'; print_r($frow); echo '</pre>'; //die;	

			$data = array();
			$data['keyword_id'] = $aca['keyword_id'];
			$data['rules_type'] = $aca['rules_type'];
			$data['rules_start_time'] = $aca['rules_start_time'];
			$data['rules_end_time'] = $aca['rules_end_time'];
			$data['rules_operation'] = $aca['rules_operation'];
			$data['recurnce_week_from'] = $aca['recurnce_week_from'];
			$data['recurnce_week_to'] = $aca['recurnce_week_to'];
			$data['recurnce_day_from'] = $aca['recurnce_day_from'];
			$data['recurnce_day_to'] = $aca['recurnce_day_to'];
			$data['facilities_id'] = $frow;
			
			
			
			$notesData = $this->model_notes_acarules->getotalnote($data);
			
			$data['keyword_id'] = $aca['keyword_id'];
			$data['rules_type'] = $aca['rules_type'];
			$data['rules_start_time'] = $aca['rules_start_time'];
			$data['rules_end_time'] = $aca['rules_end_time'];
			$data['rules_operation'] = $aca['rules_operation'];
			$data['recurnce_week_from'] = $aca['recurnce_week_from'];
			$data['recurnce_week_to'] = $aca['recurnce_week_to'];
			$data['recurnce_day_from'] = $aca['recurnce_day_from'];
			$data['recurnce_day_to'] = $aca['recurnce_day_to'];
			//$data['facilities_ids'] = $aca['facilities_id'];
			$data['facilities_id'] = $frow;
			
			$nactive = $this->model_notes_acarules->getnoteactivedetails($data);
			//echo '<pre>'.$aca ['keyword_name'].'-'; print_r($nactive); echo '</pre>'; //die;
			$missed_time_arr = array();
			$facilities_id = $nactive['facilities_id'];
			
			
			
			
			
			
			$facility = $this->model_facilities_facilities->getfacilities ( $frow );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
			$unique_id = $facility ['customer_key'];
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			$setting_data = unserialize($customer_info ['setting_data']);
			$setting_data['time_format'];
			if($setting_data['time_format']!=''){
				$time_format = $setting_data['time_format'];
			}else{
				$time_format = 'h:i A';
			}
			
			
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			//echo '<br>AAA-'.$timezone_info ['timezone_value'];
			
			//echo '<br>Current Time-'.date('Y-m-d h:i:s A');
			
			
			
			
			$current_time = strtotime(date('Y-m-d H:i'));
			$missed_time_interval=0;
			if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==1){
				$missed_time_interval = $aca['missed_time'];
			}else if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==2){
				$missed_time_interval = $aca['missed_time']*60;
			}else if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==3){
				$missed_time_interval = $aca['missed_time']*60*24;
			}
			
			if($missed_time_interval!=0){
				$missed_time_interval = $missed_time_interval*60;
			}
			
			$interval=0;
			
			if(isset($aca['duration_type']) && $aca['duration_type']==1){
				$interval = $aca['interval'];
			}else if(isset($aca['duration_type']) && $aca['duration_type']==2){
				$interval = $aca['interval']*60;
			}else if(isset($aca['duration_type']) && $aca['duration_type']==3){
				$interval = $aca['interval']*60*24;
			}
			
			if($interval!=0){
				$interval = $interval*60;
			}
			
			$notification_interval=0;
			
			if(isset($aca['notification_duration_type']) && $aca['notification_duration_type']==1){
				$notification_interval = $aca['notification_interval'];
			}else if(isset($aca['notification_duration_type']) && $aca['notification_duration_type']==2){
				$notification_interval = $aca['notification_interval']*60;
			}else if(isset($aca['notification_duration_type']) && $aca['notification_duration_type']==3){
				$notification_interval = $aca['notification_interval']*60*24;
			}
			
			if($notification_interval!=0){
				$notification_interval = $notification_interval*60;
			}
			
			$is_custom_offset_interval=0;
			if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==1){
				$is_custom_offset_interval = $aca['is_custom_offset'];
			}else if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==2){
				$is_custom_offset_interval = $aca['is_custom_offset']*60;
			}else if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==3){
				$is_custom_offset_interval = $aca['is_custom_offset']*60*24;
			}
			
			if($is_custom_offset_interval!=0){
				$is_custom_offset_interval = $is_custom_offset_interval*60;
			}
			
			$current_date = date('Y-m-d');
			$start_time = $aca['rules_start_time'];
			$end_time = $aca['rules_end_time'];
			$rules_start_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
			$rules_end_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));		
			$rules_end_time = strtotime($aca['rules_end_time']);
			
			$date1 = strtotime(date('Y-m-d', strtotime($nactive['date_added'])));
			$date2 = strtotime(date('Y-m-d'));
			
			$current_date = date('Y-m-d');
			$start_time = $aca['rules_start_time'];
			
			if($date1 < $date2){
				$note_added_date = strtotime($current_date.' '.$start_time);
				$note_added_date2 = strtotime($current_date.' '.$start_time);
				$note_added_date3 = strtotime($current_date.' '.$start_time);
			}else{
				$note_added_date = strtotime($nactive['date_added']);
				$note_added_date2 = strtotime($nactive['date_added']);
				$note_added_date3 = strtotime($nactive['date_added']);
			}
			
			if($setting_data['enable_standards']==1){
			
				if($aca['no_of_recurrence']!='' && $aca['missed_count']>0){
					$count_arr = array();
					$missed_time_arr=array();
				}else if(is_array($nactive) && $nactive!=0){
		
					if($notesData['total'] > 0){
						for($i=$current_time; $i<$rules_end_time; $i++){
							
							if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){
								$note_added_date = $note_added_date+180;
							}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!="" && $is_custom_offset_interval!=0){
								$note_added_date = $note_added_date + $is_custom_offset_interval;	
							}else{
								$note_added_date = $note_added_date+$interval;
								$note_added_date = $note_added_date+$missed_time_interval;
							}
							
							$missed_time = date('Y-m-d h:i:s A',$note_added_date);
							
							$missed_time_arr[] = $missed_time;
							
							if(($current_time <= $note_added_date) || ($note_added_date > $rules_end_time)){
								break;
							}
						}
					}
					
					//echo '<pre>missed_arr-'.$aca ['keyword_name'].'----'.$frow; print_r($missed_time_arr); echo '</pre>'; //die;	
					
					$data2 = array();
					if(isset($aca['is_missed']) && $aca['is_missed']==1){
						
						if(!empty($missed_time_arr)){
						
							foreach($missed_time_arr AS $mrow){
								
								$plus_mrow  = $current_time+30;
								
								if($current_time <= strtotime($mrow) && strtotime($mrow) <= $plus_mrow){
									echo 'missed inner-'.$mrow;
									if($aca['no_of_recurrence']!='' && $aca['missed_count']>0){
										return false;
									}else{
										
										$data2 ['imgOutput'] = '';
										$data2 ['notes_pin'] = SYSTEM_GENERATED_PIN;
										$data2 ['user_id'] = SYSTEM_GENERATED;
										$data2 ['note_date'] = date('d-m-Y H:i:s');
										$data2 ['keyword_file'] = $notesData['keyword_file'];
										$data2 ['multi_keyword_file'] = $notesData['keyword_file'];
										$data2 ['activenoteids'] = $aca['keyword_id'];
										$data2 ['notetime'] = date('h:i A');
										$data2 ['notes_description'] = $aca['keyword_name'].' missed at '.date('h:i A',strtotime($mrow));
										
										if(!empty($data2)){
											
											//$fids = explode(',',$aca['facilities_id']);
				
											//foreach($fids AS $fid){
												
												$facilities_id = $frow;
												$notes_id = $this->model_notes_notes->jsonaddnotes ( $data2, $facilities_id );
												$mfdata=array();
												$mfdata['notes_id'] = $notes_id;
												$mfdata['is_comment'] = 4;
												$this->model_notes_acarules->missed_flag_insert($mfdata);
							
											//}
										}
										
										if($aca['is_missed_notification']!="" && $aca['is_missed_notification']==1){
					
											$rule_action_arr = explode(',',$aca['rule_action']);
								
											if(in_array('1',$rule_action_arr)){ //sms
											
											
												//$fids = explode(',',$aca['facilities_id']);
				
												//foreach($fids AS $fid){
													
													
													$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
													
													$where = $facilities['facility'];
											
												
													$notes_description = str_replace('I','',$aca['keyword_name']).' missed at '.date('h:i A',strtotime($mrow)) .' on '.$where;
													
													if($aca['user_roles']!=""){
														$user_roles_arr = explode(',',$aca['user_roles']);
														foreach($user_roles_arr as $row){
															
															$urole = array();
															$urole['user_group_id'] = $row;
															$tusers = $this->model_user_user->getUsers($urole);
															if($tusers){
																foreach ($tusers as $tuser) {
														
																	if($tuser['phone_number'] != null && $tuser['phone_number'] != '0'){
																		$message = "Role - Standards\n";
																		$message .= $notes_description;
																		$sdata = array();
																		$sdata['message'] = $message;
																		$sdata['phone_number'] = $user_info['phone_number'];
																		$sdata['facilities_id'] = $fid;	
																		$response = $this->model_api_smsapi->sendsms($sdata);
																	}
																}
															}
														}
													}
													

													if($aca['auserids']!=""){
														$auserids_arr = explode(',',$aca['auserids']);
														foreach($auserids_arr as $row){
															
															$user_info = $this->model_user_user->getUserbyupdate($row);	
															if(!empty($user_info)){
																
																if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
																	$message = "Role - Standards\n";
																	$message .= $notes_description;
																	$sdata = array();
																	$sdata['message'] = $message;
																	$sdata['phone_number'] = $user_info['phone_number'];
																	$sdata['facilities_id'] = $fid;	
																	$response = $this->model_api_smsapi->sendsms($sdata);
																}
															}
														}
													}
												
												//}
											
											}
											
											if(in_array('2',$rule_action_arr)){ //email
												
												//$fids = explode(',',$aca['facilities_id']);
				
												//foreach($fids AS $fid){
													
													$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
													
													$where = $facilities['facility'];
													$emailData = array();
													$useremailids = array();
													$emailData ['keyword_file'] = $notesData['keyword_file'];
													$emailData ['notes_description'] = str_replace('I','',$aca['keyword_name']).' missed at '.date('h:i A',strtotime($mrow));
													$emailData ['date_added'] = date('m-d-Y');
													$emailData ['notetime'] = date('h:i A');
													$emailData['keyword_name'] = $aca ['keyword_name'];
													$emailData['facilities_id'] = $facilities_id;
													$emailData['rule_name'] = 'Standards';
													$emailData['where'] = $where;
												
													if($aca['user_roles']!=''){
														
														$user_roles_arr = explode(',',$aca['user_roles']);
														
														foreach($user_roles_arr as $row){
															$urole = array();
															$urole['user_group_id'] = $row;
															$tusers = $this->model_user_user->getUsers($urole);
															
															if($tusers){
																foreach ($tusers as $tuser) {
																	if($tuser['email'] != null && $tuser['email'] != ""){
																		$useremailids[] = $tuser['email'];
																	}
																}
															}
														}	
													}
												
													if($aca['auserids']!=''){
														$user_ids_arr = explode(',',$aca['auserids']);
														foreach($user_ids_arr as $row){
															$user_info = $this->model_user_user->getUserbyupdate($row);
															if ($user_info) {
																if($user_info['email']){
																	$useremailids[] = $user_info['email'];
																}
															}
														}		
													}
												
													echo $message33 = $this->acaemailtemplate($emailData);	
														
													$edata = array();
													$edata['message'] = $message33;
													$edata['subject'] = 'This is an Automated Alert Email.';
													$edata['useremailids'] = $useremailids;
													$edata['user_email'] = $user_email;
													//echo '<pre>'; print_r($edata); echo '</pre>'; //die;
													if(!empty($useremailids)){
														$email_status = $this->model_api_emailapi->sendmail($edata);
													}
												//}						
											}
										}	
									}
								}
							} 
						}
					}
					
				
					$is_custom_offset_interval2 = $is_custom_offset_interval;
					
					if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){
						$note_added_date2 = $note_added_date2-180;
					}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!=""){
						$note_added_date2 = $note_added_date2 - $is_custom_offset_interval2;
					}else{
						$note_added_date2 = $note_added_date2-$interval;
					}
					
					
					for($i=$current_time; $i<$rules_end_time; $i++){
						
						if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){
							$note_added_date2x = $note_added_date2;
							$note_added_date2 = $note_added_date2+180;
							
							$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x+60);
						
						}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!=""){
							$note_added_date2x = $note_added_date2;
							$note_added_date2 = $note_added_date2+$is_custom_offset_interval;
							$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x+60);	
						
						}else{
							
							$note_added_date2x = $note_added_date2;
							$note_added_date2 = $note_added_date2 + $interval;
							$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x+$notification_interval);
						}
						
						$notification_time_arr[$missed_note_time] = $notification_time2;
						
						//echo '<br>'.'('.date('H:i:s',$current_time).'<='.date('H:i:s',$note_added_date2).') || ('.date('H:i:s',$note_added_date2) .'>'. date('H:i:s',$rules_end_time).')';
						
						if(($current_time<=$note_added_date2) || ($note_added_date2 > $rules_end_time)){
							break;
						}
					}
					
					//echo '<pre>notification_arr'.date('H:i:s',$note_added_date2+600).$aca ['keyword_name']; 
					
					//print_r($notification_time_arr); echo '</pre>'; //die;
					
					
					
					
					$notification_data = array();
					
					if(!empty($notification_time_arr)){
					
						foreach($notification_time_arr AS $key=>$nrow){
							
							$plus_nrow  = $current_time+30;
							
							if($current_time <= strtotime($nrow) && strtotime($nrow) <= $plus_nrow  && $key <= $rules_end_time){
								
								
								
								$rule_action_arr = explode(',',$aca['rule_action']);

								if(in_array('1',$rule_action_arr)){ //sms
									
									
									$fids = explode(',',$aca['facilities_id']);
	
									foreach($fids AS $fid){
										
										
										$facilities = $this->model_facilities_facilities->getfacilities ( $fid );
										
										$where = $facilities['facility'];
								
									
										$notes_description = str_replace('I','',$aca['keyword_name']).' - scheduled at '.date('h:i A',strtotime($key)) . ' on '.$where;
									
										if($aca['user_roles']!=""){
											$user_roles_arr = explode(',',$aca['user_roles']);
											foreach($user_roles_arr as $row){
												
												$urole = array();
												$urole['user_group_id'] = $row;
												$tusers = $this->model_user_user->getUsers($urole);
												if($tusers){
													foreach ($tusers as $tuser) {
											
														if($tuser['phone_number'] != null && $tuser['phone_number'] != '0'){
															$message = "Role - Standards\n";
															$message .= $notes_description;
															$sdata = array();
															$sdata['message'] = $message;
															$sdata['phone_number'] = $user_info['phone_number'];
															$sdata['facilities_id'] = $fid;	
															$response = $this->model_api_smsapi->sendsms($sdata);
														}
													}
												}
											}
										}
									

										if($aca['auserids']!=""){
											$auserids_arr = explode(',',$aca['auserids']);
											foreach($auserids_arr as $row){
												
												$user_info = $this->model_user_user->getUserbyupdate($row);	
												if(!empty($user_info)){
													
													if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
														$message = "Role - Standards\n";
														$message .= $notes_description;
														$sdata = array();
														$sdata['message'] = $message;
														$sdata['phone_number'] = $user_info['phone_number'];
														$sdata['facilities_id'] = $fid;	
														$response = $this->model_api_smsapi->sendsms($sdata);
														//echo '<pre>ttt'; count($sdata); print_r($sdata); echo '</pre>'; //die;
													}
												}
											}
										}
									}	
								}
								
								if(in_array('2',$rule_action_arr)){ //email
									
								
					
								$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
								
								$where = $facilities['facility'];
								$emailData = array();
								$useremailids = array();
								
								if($notesData['keyword_file']!=''){
									$emailData ['keyword_file'] = $notesData['keyword_file'];
								}else{
									$emailData ['keyword_file']='';
								}
								
								$emailData ['notes_description'] = str_replace('I','',$aca['keyword_name']).' - scheduled at '.date('h:i A',strtotime($key));
								$emailData ['date_added'] = date('m-d-Y',strtotime($key));
								$emailData ['notetime'] = date('h:i A',strtotime($key));
								$emailData['keyword_name'] = $aca ['keyword_name'];
								$emailData['facilities_id'] = $facilities_id;
								$emailData['rule_name'] = 'Standards';
								$emailData['where'] = $where;
								
								if($aca['user_roles']!=''){
									
									$user_roles_arr = explode(',',$aca['user_roles']);
									
									foreach($user_roles_arr as $row){
										$urole = array();
										$urole['user_group_id'] = $row;
										$tusers = $this->model_user_user->getUsers($urole);
										
										if($tusers){
											foreach ($tusers as $tuser) {
												if($tuser['email'] != null && $tuser['email'] != ""){
													$useremailids[] = $tuser['email'];
												}
											}
										}
									}	
								}
								
								if($aca['auserids']!=''){
									$user_ids_arr = explode(',',$aca['auserids']);
									foreach($user_ids_arr as $row){
										$user_info = $this->model_user_user->getUserbyupdate($row);
										if ($user_info) {
											if($user_info['email']){
												$useremailids[] = $user_info['email'];
											}
										}
									}		
								}
								
								echo $message33 = $this->acaemailtemplate($emailData);	
										
								$edata = array();
								$edata['message'] = $message33;
								$edata['subject'] = 'This is an Automated Alert Email.';
								$edata['useremailids'] = $useremailids;
								$edata['user_email'] = $user_email;
								//echo '<pre>'; print_r($edata); echo '</pre>'; //die;
								if(!empty($useremailids)){
									$email_status = $this->model_api_emailapi->sendmail($edata);
								}
												
								}
								
							}
						}
					}
					$missed_time_arr = array();	
					$notification_time_arr = array();
				}
			}
		}
		
		}
	
	
	
	
	
	}
}
?>