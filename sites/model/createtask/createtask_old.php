<?php
class Modelcreatetaskcreatetask extends Model {
	public function addcreatetask($data, $facilities_id) {
		if ($data ['task_alert'] == 1) {
			if ($data ['alert_type_email'] == 1) {
				$this->load->model ( 'api/emailapi' );
			}
			if ($data ['alert_type_sms'] == 1) {
				$this->load->model ( 'api/smsapi' );
			}
		}
		
		// var_dump($data);die;
		// $taskTiming = date('Y-m-d H:i:s', strtotime($data['taskDate']));
		
		/*
		 * if($data['app_add'] != '1'){
		 * $task_date = strtotime($data['taskDate']);
		 * if($task_date == false){
		 * $newData = $data['taskDate'];
		 * }else{
		 *
		 * $newData = date('m-d-Y', $task_date);
		 * }
		 * $date = str_replace('-', '/', $newData);
		 * $res = explode("/", $date);
		 * $dateRange = $res[2]."-".$res[0]."-".$res[1];
		 *
		 * $time = date('H:i:s');
		 * $taskDate = $dateRange.' '.$time;
		 * }else{
		 */
		
		// $newData = date('m-d-Y', strtotime($data['taskDate']));
		
		$date = str_replace ( '-', '/', $data ['taskDate'] );
		$res = explode ( "/", $date );
		$dateRange = $res [2] . "-" . $res [0] . "-" . $res [1];
		
		if ($data ['app_add'] == '1') {
			$timeZone = date_default_timezone_set ( $data ['facilitytimezone'] );
		} else {
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		$time = date ( 'H:i:s' );
		
		$currentdate = date ( 'Y-m-d H:i:s' );
		$currentdate_only = date ( 'Y-m-d' );
		
		$taskDate = $dateRange . ' ' . $time;
		$taskDate221 = $dateRange . ' ' . $time;
		
		// $current_time = date("H:i:s");
		$current_time1 = date ( "H:i:s" );
		
		$snooze_time71 = 3;
		$thestime61 = date ( 'H:i:s' );
		$current_time = date ( "H:i:s", strtotime ( "-" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
		// }
		
		// if($data['recurrence'] !="none"){
		
		/*
		 * if($data['app_add'] != '1'){
		 *
		 * $end_recurrence_date1 = strtotime($data['end_recurrence_date']);
		 * if($end_recurrence_date1 == false){
		 * $newData1 = $data['end_recurrence_date'];
		 * }else{
		 * $newData1 = date('m-d-Y', $end_recurrence_date1);
		 * }
		 *
		 *
		 * $date1 = str_replace('-', '/', $newData1);
		 * $res1 = explode("/", $date1);
		 * $dateRange1 = $res1[2]."-".$res1[0]."-".$res1[1];
		 *
		 * $time1 = date('H:i:s');
		 * $end_recurrence_date = $dateRange1.' '.$time1;
		 * }else{
		 */
		
		$newData1 = date ( 'm-d-Y', strtotime ( $data ['end_recurrence_date'] ) );
		
		$date1 = str_replace ( '-', '/', $data ['end_recurrence_date'] );
		$res1 = explode ( "/", $date1 );
		$dateRange1 = $res1 [2] . "-" . $res1 [0] . "-" . $res1 [1];
		
		$time1 = date ( 'H:i:s' );
		$end_recurrence_date = $dateRange1 . ' ' . $time1;
		$end_recurrence_date1 = $dateRange1 . ' ' . $time1;
		// }
		// }
		
		// $assign = implode(',',$data['assignto']);
		
		$endtime1 = explode ( ":", $data ['endtime'] );
		if ($endtime1 [0] == "00") {
			// $endtime2 = '12:'.$endtime1[1];
			$endtime2 = $data ['endtime'];
		} else {
			$endtime2 = $data ['endtime'];
		}
		
		$endtime = date ( 'H:i:s', strtotime ( $data ['endtime'] ) );
		/* $recurnce_hrly = $data['recurnce_hrly']; */
		
		if ($data ['recurrence'] == "weekly") {
			$recurnce_day = '';
			$recurnce_month = '';
			$recurnce_week = implode ( ',', $data ['recurnce_week'] );
			$recurnce_hrly2 = "";
			$taskeTiming = "";
		}
		
		if ($data ['recurrence'] == "yearly") {
			$recurnce_day = '';
			$recurnce_month = $data ['recurnce_month'];
			$recurnce_week = '';
			$recurnce_hrly2 = "";
			$taskeTiming = "";
		}
		
		if ($data ['recurrence'] == "monthly") {
			$recurnce_day = $data ['recurnce_day'];
			$recurnce_month = '';
			$recurnce_week = '';
			$recurnce_hrly2 = "";
			$taskeTiming = "";
		}
		
		if ($data ['recurrence'] == "none") {
			$recurnce_day = '';
			$recurnce_month = '';
			$recurnce_week = '';
			// $end_recurrence_date = "";
			$recurnce_hrly2 = "";
			$taskeTiming = "";
		}
		
		if ($data ['recurrence'] == "daily") {
			$recurnce_day = '';
			$recurnce_month = '';
			$recurnce_week = '';
			$recurnce_hrly2 = '';
			$taskeTiming = date ( 'H:i:s', strtotime ( $endtime ) );
		}
		
		if ($data ['recurrence'] == "hourly") {
			$recurnce_day = '';
			$recurnce_month = '';
			$recurnce_week = '';
			$recurnce_hrly2 = $data ['recurnce_hrly'];
			$taskeTiming = date ( 'H:i:s', strtotime ( $endtime ) );
		}
		
		$task_form_id = $data ['task_form_id'];
		$tags_id = $data ['tags_id'];
		$pickup_locations_address = $data ['pickup_locations_address'];
		
		$pickup_locations_time1 = explode ( ":", $data ['pickup_locations_time'] );
		if ($pickup_locations_time1 [0] == "00") {
			// $pickup_locations_time2 = '12:'.$pickup_locations_time1[1];
			$pickup_locations_time2 = $data ['pickup_locations_time'];
		} else {
			$pickup_locations_time2 = $data ['pickup_locations_time'];
		}
		$pickup_locations_time = date ( 'H:i:s', strtotime ( $pickup_locations_time2 ) );
		
		// $pickup_locations_time = date('H:i:s',
		// strtotime($data['pickup_locations_time']));
		
		$pickup_facilities_id = $data ['pickup_facilities_id'];
		$dropoff_facilities_id = $data ['dropoff_facilities_id'];
		
		if ($data ['pickup_locations_latitude'] != null && $data ['pickup_locations_latitude'] != "") {
			$pickup_locations_address_latitude = $data ['pickup_locations_latitude'];
			$pickup_locations_address_longitude = $data ['pickup_locations_longitude'];
		} else {
			
			// Get JSON results from this request
			$geo = file_get_contents ( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode ( $pickup_locations_address ) . '&sensor=false' );
			
			// Convert the JSON to an array
			$geo = json_decode ( $geo, true );
			
			if ($geo ['status'] == 'OK') {
				// Get Lat & Long
				$pickup_locations_address_latitude = $geo ['results'] [0] ['geometry'] ['location'] ['lat'];
				$pickup_locations_address_longitude = $geo ['results'] [0] ['geometry'] ['location'] ['lng'];
			}
		}
		
		$this->load->model ( 'createtask/createtask' );
		$tasktype_info = $this->model_createtask_createtask->gettasktyperow ( $data ['tasktype'] );
		
		$data ['tasktype'] = $tasktype_info ['tasktype_name'];
		$tasktypetype = $tasktype_info ['type'];
		
		if ($tasktypetype == '5') {
			
			$snooze_time7 = '30';
			$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $time ) ) );
		}
		
		// die;
		
		$dropoff_locations_address = $data ['dropoff_locations_address'];
		
		$dropoff_locations_time1 = explode ( ":", $data ['dropoff_locations_time'] );
		if ($dropoff_locations_time1 [0] == "00") {
			// $dropoff_locations_time2 = '12:'.$dropoff_locations_time1[1];
			$dropoff_locations_time2 = $data ['dropoff_locations_time'];
		} else {
			$dropoff_locations_time2 = $data ['dropoff_locations_time'];
		}
		
		// $dropoff_locations_time = date('H:i:s',
		// strtotime($dropoff_locations_time2));
		
		// $dropoff_locations_time = date('H:i:s',
		// strtotime($data['dropoff_locations_time']));
		
		if ($data ['dropoff_locations_latitude'] != null && $data ['dropoff_locations_latitude'] != "") {
			$dropoff_locations_address_latitude = $data ['dropoff_locations_latitude'];
			$dropoff_locations_address_longitude = $data ['dropoff_locations_longitude'];
		} else {
			// Get JSON results from this request
			$geo1 = file_get_contents ( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode ( $dropoff_locations_address ) . '&sensor=false' );
			
			// Convert the JSON to an array
			$geo2 = json_decode ( $geo1, true );
			
			if ($geo2 ['status'] == 'OK') {
				// Get Lat & Long
				$dropoff_locations_address_latitude = $geo2 ['results'] [0] ['geometry'] ['location'] ['lat'];
				$dropoff_locations_address_longitude = $geo2 ['results'] [0] ['geometry'] ['location'] ['lng'];
			}
		}
		
		$transport_tags = implode ( ',', $data ['transport_tags'] );
		
		$visitation_tags = implode ( ',', $data ['visitation_tags'] );
		
		$visitation_start_address = $data ['visitation_start_address'];
		$visitation_start_time = date ( 'H:i:s', strtotime ( $data ['visitation_start_time'] ) );
		$visitation_start_facilities_id = $data ['visitation_start_facilities_id'];
		
		if ($data ['visitation_start_address'] != null && $data ['visitation_start_address'] != "") {
			// Get JSON results from this request
			$geov = file_get_contents ( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode ( $visitation_start_address ) . '&sensor=false' );
			
			// Convert the JSON to an array
			$geov = json_decode ( $geov, true );
			
			if ($geov ['status'] == 'OK') {
				// Get Lat & Long
				$visitation_start_address_latitude = $geov ['results'] [0] ['geometry'] ['location'] ['lat'];
				$visitation_start_address_longitude = $geov ['results'] [0] ['geometry'] ['location'] ['lng'];
			}
		}
		
		$visitation_appoitment_address = $data ['visitation_appoitment_address'];
		$visitation_appoitment_time = date ( 'H:i:s', strtotime ( $data ['visitation_appoitment_time'] ) );
		$visitation_appoitment_facilities_id = $data ['visitation_appoitment_facilities_id'];
		
		if ($data ['visitation_appoitment_address'] != null && $data ['visitation_appoitment_address'] != "") {
			// Get JSON results from this request
			$geo1v = file_get_contents ( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode ( $visitation_appoitment_address ) . '&sensor=false' );
			
			// Convert the JSON to an array
			$geo1v = json_decode ( $geo1v, true );
			
			if ($geo1v ['status'] == 'OK') {
				// Get Lat & Long
				$visitation_appoitment_address_latitude = $geo1v ['results'] [0] ['geometry'] ['location'] ['lat'];
				$visitation_appoitment_address_longitude = $geo1v ['results'] [0] ['geometry'] ['location'] ['lng'];
			}
		}
		
		$medication_tags = implode ( ',', $data ['medication_tags'] );
		$user_roles = implode ( ',', $data ['user_roles'] );
		$userids = implode ( ',', $data ['userids'] );
		$weekly_interval = implode ( ',', $data ['weekly_interval'] );
		
		$bed_check_location_ids = implode ( ',', $data ['bed_check_location_ids'] );
		$user_role_assign_ids = implode ( ',', $data ['user_role_assign_ids'] );
		$assign_to = implode ( ',', $data ['assign_to'] );
		// $assign_to = $data['assignto'];
		
		if ($tasktype_info ['is_custom_offset'] > 0) {
			$recurnce_perpetual = $tasktype_info ['is_custom_offset'];
			$is_task_time = date ( 'H:i:s' );
			$tasksTiming = date ( 'H:i:s', strtotime ( ' +' . $recurnce_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
		} else {
			$tasksTiming = date ( 'H:i:s', strtotime ( $data ['taskTime'] ) );
		}
		
		if ($tasktype_info ['client_required'] == '0') {
			$description = $data ['description'] . ' ' . $data ['emp_tag_id1'];
			$emp_tag_id = $data ['emp_tag_id1'];
		} else {
			$description = $data ['description'];
			$emp_tag_id = $data ['emp_tag_id'];
		}
		
		// $completed_times1 = date('H:i:s',
		// strtotime($data['completed_times']));
		$completed_times = implode ( ',', $data ['completed_times'] );
		
		$task_group_by = rand ();
		
		if ($data ['linked_id'] != null && $data ['linked_id'] != "") {
			// $this->db->query("UPDATE `" . DB_PREFIX . "notes` SET task_group_by = '".$task_group_by."' WHERE notes_id = '" . (int)$data['linked_id'] . "'");
		}
		
		if ($facilities_id) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$activecustomer_id = $customer_info ['activecustomer_id'];
		}
		
		if (trim ( $data ['description'] ) != null && trim ( $data ['description'] ) != "") {
			
			/* if ($tasktype_info['enable_requires_approval'] == '1') { */
			if ($data ['required_approval'] == '1') {
				
				$message111 = substr ( $description, 0, 100 ) . ((strlen ( $description ) > 100) ? '..' : '');
				
				$enable_desc = $tasktype_info ['tasktype_name'] . ' | APPROVAL REQUIRED | ' . $message111;
				
				$sql1 = "CALL insertapprovalTasks('" . $facilities_id . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $time ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $enable_desc ) . "','" . $currentdate . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $task_group_by ) . "','" . $this->db->escape ( $unique_id ) . "','" . $this->db->escape ( $activecustomer_id ) . "' )";
				
				$lastId = $this->db->query ( $sql1 );
				
				$approval_taskid = $lastId->row ['task_id'];
				
				$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "' WHERE id = '" . ( int ) $approval_taskid . "' ";
				$this->db->query ( $sql12 );
				
				$enable_requires_approval = '1';
			}
			
			switch ($data ['recurrence']) {
				case "hourly" :
					if ($tasktype_info ['is_custom_offset'] > 0) {
						$recurnce_perpetual = $tasktype_info ['is_custom_offset'];
						$is_task_time = date ( 'H:i:s' );
						$taskTime = date ( 'H:i:s', strtotime ( ' +' . $recurnce_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
					} else {
						$taskTime = date ( 'H:i:s', strtotime ( $data ['taskTime'] ) );
					}
					
					$endtime = date ( 'H:i:s', strtotime ( $data ['endtime'] ) );
					
					$time1 = strtotime ( $taskTime );
					$time2 = strtotime ( $endtime );
					$difference = round ( abs ( $time2 - $time1 ) / 3600, 2 );
					// echo $difference;
					// echo "<hr>";
					
					if ($current_time1 > $endtime) {
						$total_hour = 24 - $difference;
						$recData = $total_hour * 60;
						$taskinterval = round ( $recData / $data ['recurnce_hrly'] ) + 1;
					} else {
						if ($taskTime > $endtime) {
							$total_hour = 24 - $difference;
							$recData = $total_hour * 60;
							$taskinterval = round ( $recData / $data ['recurnce_hrly'] ) + 1;
						} else {
							
							$total_hour = $difference;
							
							$interval = abs ( strtotime ( $endtime ) - strtotime ( $taskTime ) );
							$recData = round ( $interval / 60 );
							$taskinterval = round ( $recData / $data ['recurnce_hrly'] ) + 1;
						}
					}
					
					
					//var_dump($taskTime);
					
					
					$tasktimearray = array ();
					
					if ($data ['recurnce_hrly_recurnce'] == "Daily" && empty ( $data ['weekly_interval'] )) {
						
						$taskstarttime = $tasksTiming;
						$tasktimearray [] = $taskstarttime;
						$int_time1 = date ( 'H:i:s', strtotime ( $taskstarttime ) );
							
						
						if ($current_time > $int_time1) {
							$form_due_date_after = '1';
						
							$ddd_date = $dateRange . ' ' . $taskstarttime;
							// var_dump($ddd_date);
							$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $ddd_date ) ) . " +" . $form_due_date_after . " day" ) );
							// $taskDate1 = date("Y-m-d
							// H:i:s",strtotime($ddd_date));
							// $taskDate = $taskDate1;
							// var_dump($taskDate1);
							if ($dateRange1 == $dateRange) {
								$end_recurrence_date = $taskDate1;
							} else {
								$end_recurrence_date = $end_recurrence_date1;
							}
						} else {
						
							$taskDate1 = $taskDate;
							$end_recurrence_date = $end_recurrence_date1;
						}
						
						
							
						$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $taskstarttime ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $endtime ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tags ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "','" . $this->db->escape ( $activecustomer_id ) . "'
						)";
						// echo $sql1;
						// echo "<hr>";
							
						$lastId = $this->db->query ( $sql1 );
							
						$task_id = $lastId->row['task_id'];
							
						$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "',form_task_creation = '" . $data ['form_task_creation'] . "' WHERE id = '" . ( int ) $task_id . "' ";
						$this->db->query ( $sql12 );
							
						if ($data ['reminderplus']) {
							foreach ( $data ['reminderplus'] as $reminder ) {
								$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
								$this->db->query ( $sqlr );
							}
						}
						if ($data ['reminderminus']) {
							foreach ( $data ['reminderminus'] as $reminder ) {
								$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
								$this->db->query ( $sqlrm );
							}
						}
							
						if ($data ['locations']) {
							foreach ( $data ['locations'] as $location ) {
									
								$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
								$lastIdt = $this->db->query ( $sql1 );
									
								$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
							}
						}
							
						if ($data ['tags_medication_details_ids']) {
							$i = 0;
							foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
								foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
						
									$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
						
									$lastIdtm = $this->db->query ( $sql1 );
						
									$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
						
									$i ++;
								}
							}
						}
						
						if ($data ['task_alert'] == 1) {
							$this->load->model ( 'api/notification' );
							$ndata = array ();
							$ndata ['dateRange'] = $dateRange;
							$ndata ['tasktimearray'] = $tasktimearray;
							$ndata ['description'] = $description;
							$ndata ['facilities_id'] = $facilities_id;
							$ndata ['task_id'] = $task_id;
							$this->model_api_notification->sendnotification ( $data, $ndata );
						}
						
					} elseif ($data ['recurnce_hrly_recurnce'] == "") {
						$taskstarttime = $tasksTiming;
						
						$tasktimearray [] = $taskstarttime;
							
						$int_time1 = date ( 'H:i:s', strtotime ( $taskstarttime ) );
							
						// var_dump($data['recurnce_hrly_recurnce']);
						// var_dump($data['weekly_interval']);
							
						if ($current_time > $int_time1) {
						
							$form_due_date_after = '1';
						
							$ddd_date = $dateRange . ' ' . $taskstarttime;
							// var_dump($ddd_date);
							$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $ddd_date ) ) . " +" . $form_due_date_after . " day" ) );
							// $taskDate1 = date("Y-m-d
							// H:i:s",strtotime($ddd_date));
							// $taskDate = $taskDate1;
							// var_dump($taskDate1);
							if ($dateRange1 == $dateRange) {
								$end_recurrence_date = $taskDate1;
							} else {
								$end_recurrence_date = $end_recurrence_date1;
							}
						} else {
						
							$taskDate1 = $taskDate;
							$end_recurrence_date = $end_recurrence_date1;
						}
							
						$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $taskstarttime ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $endtime ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tags ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "','" . $this->db->escape ( $activecustomer_id ) . "'
						)";
						// echo $sql1;
						// echo "<hr>";
							
						$lastId = $this->db->query ( $sql1 );
							
						$task_id = $lastId->row ['task_id'];
							
						$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "',form_task_creation = '" . $data ['form_task_creation'] . "' WHERE id = '" . ( int ) $task_id . "' ";
						$this->db->query ( $sql12 );
							
						if ($data ['reminderplus']) {
							foreach ( $data ['reminderplus'] as $reminder ) {
								$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
								$this->db->query ( $sqlr );
							}
						}
						if ($data ['reminderminus']) {
							foreach ( $data ['reminderminus'] as $reminder ) {
								$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
								$this->db->query ( $sqlrm );
							}
						}
							
						if ($data ['locations']) {
							foreach ( $data ['locations'] as $location ) {
									
								$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
								$lastIdt = $this->db->query ( $sql1 );
									
								$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
							}
						}
							
						if ($data ['tags_medication_details_ids']) {
							$i = 0;
							foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
								foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
						
									$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
						
									$lastIdtm = $this->db->query ( $sql1 );
						
									$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
						
									$i ++;
								}
							}
						}
						
						if ($data ['task_alert'] == 1) {
							$this->load->model ( 'api/notification' );
							$ndata = array ();
							$ndata ['dateRange'] = $dateRange;
							$ndata ['tasktimearray'] = $tasktimearray;
							$ndata ['description'] = $description;
							$ndata ['facilities_id'] = $facilities_id;
							$ndata ['task_id'] = $task_id;
							$this->model_api_notification->sendnotification ( $data, $ndata );
						}
						
					} else if (! empty ( $data ['weekly_interval'] )) {
						foreach ( $data ['weekly_interval'] as $day ) {
							$daydates [] = date ( 'Y-m-d', strtotime ( $day ) );
						}
						
						sort ( $daydates );
						
						
						$taskstarttime = $tasksTiming;
						
						$tasktimearray [] = $taskstarttime;
						$int_time1 = date ( 'H:i:s', strtotime ( $taskstarttime ) );
						
						$taskdate_curren = $dateRange . ' ' . $taskstarttime;
						
						$enddate = date ( "H:i:s", strtotime ( $data ['endtime'] ) );
						
						$taskdate_end = $dateRange . ' ' . $enddate;
						
						if ($daydates[0] == $dateRange) {
								
							if ($taskstarttime <= "00:00:00") {
						
								$taskDate122 = $daydates[0] . ' ' . $taskstarttime;
						
								$form_due_date_after = '1';
								$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate122 ) ) . " +" . $form_due_date_after . " day" ) );
							} else {
								$taskDate1 = $daydates[0] . ' ' . $taskstarttime;
							}
						} else {
							$taskDate122 = $daydates[0] . ' ' . $taskstarttime;
								
							$form_due_date_after = '1';
							$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate122 ) ) . " +" . $form_due_date_after . " day" ) );
						}
						
						$end_recurrence_date = $end_recurrence_date1;
						
						
						
						$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $taskstarttime ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $endtime ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tags ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "','" . $this->db->escape ( $activecustomer_id ) . "'
							)";
						// echo $sql1;
						// echo "<hr>";
						
						$lastId = $this->db->query ( $sql1 );
						
						$task_id = $lastId->row ['task_id'];
						
						$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',form_task_creation = '" . $data ['form_task_creation'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
						$this->db->query ( $sql12 );
						
						if ($data ['reminderplus']) {
							foreach ( $data ['reminderplus'] as $reminder ) {
								$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
								$this->db->query ( $sqlr );
							}
						}
						if ($data ['reminderminus']) {
							foreach ( $data ['reminderminus'] as $reminder ) {
								$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
								$this->db->query ( $sqlrm );
							}
						}
						
						if ($data ['locations']) {
							foreach ( $data ['locations'] as $location ) {
						
								$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
						
								$lastIdt = $this->db->query ( $sql1 );
						
								$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
							}
						}
						
						if ($data ['tags_medication_details_ids']) {
							$i = 0;
							foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
						
								foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
										
									$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
										
									$lastIdtm = $this->db->query ( $sql1 );
										
									$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
										
									$i ++;
								}
							}
						}
						
						if ($data ['task_alert'] == 1) {
							$this->load->model ( 'api/notification' );
							$ndata = array ();
							$ndata ['dateRange'] = $dateRange;
							$ndata ['tasktimearray'] = $tasktimearray;
							$ndata ['description'] = $description;
							$ndata ['facilities_id'] = $facilities_id;
							$ndata ['task_id'] = $task_id;
							$this->model_api_notification->sendnotification ( $data, $ndata );
						}
					}
					
					break;
				case "daily" :
					if ($data ['daily_times']) {
						
						foreach ( $data ['daily_times'] as $dailytime ) {
							$tasksTiming = date ( 'H:i:s', strtotime ( $dailytime ) );
							
							$daily_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
							
							// var_dump($current_time);
							// var_dump($daily_time1);
							
							if ($current_time > $daily_time1) {
								
								$form_due_date_after = '1';
								$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate ) ) . " +" . $form_due_date_after . " day" ) );
								
								$taskDate = $taskDate1;
								
								if ($dateRange1 == $dateRange) {
									$end_recurrence_date = $taskDate1;
								} else {
									$end_recurrence_date = $end_recurrence_date1;
								}
								
								// var_dump($end_recurrence_date);
							} else {
								
								$taskDate = $taskDate221;
								
								$end_recurrence_date = $end_recurrence_date1;
							}
							
							if ($data ['medication_tags']) {
								foreach ( $data ['medication_tags'] as $medication_tag ) {
									
									$this->load->model ( 'setting/tags' );
									$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
									
									if ($tags_info_m ['emp_first_name']) {
										$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
									} else {
										$description2 = $description;
									}
									
									if ($drop_time == '1') {
										$snooze_time7 = '30';
										$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
									}
									
									$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
								,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
								'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
								'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
								'" . $this->db->escape ( $visitation_start_time ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address ) . "',
								'" . $this->db->escape ( $visitation_appoitment_time ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
								'" . $this->db->escape ( $visitation_tags ) . "',
								'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
								'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
								'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
								'" . $this->db->escape ( $completed_times ) . "',
								'" . $this->db->escape ( $data ['completed_alert'] ) . "',
								'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
								'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
								'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
								'" . $this->db->escape ( $data ['attachement_form'] ) . "',
								'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
								'" . $this->db->escape ( $task_group_by ) . "',
								'" . $this->db->escape ( $enable_requires_approval ) . "',
								'" . $this->db->escape ( $approval_taskid ) . "',
								'" . $this->db->escape ( serialize ( $response_all ) ) . "',
								'" . $this->db->escape ( $distance ) . "',
								'" . $this->db->escape ( $distancev ) . "',
								'" . $this->db->escape ( $duration ) . "',
								'" . $this->db->escape ( $durationv ) . "',
								'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
								'" . $this->db->escape ( $bed_check_location_ids ) . "',
								'" . $this->db->escape ( $data ['complete_status'] ) . "',
								'" . $this->db->escape ( $weekly_interval ) . "',
								'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
								'" . $this->db->escape ( $data ['is_android'] ) . "',
								'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
								)";
									
									$lastId = $this->db->query ( $sql1 );
									
									$task_id = $lastId->row ['task_id'];
									
									$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "',form_task_creation = '" . $data ['form_task_creation'] . "' WHERE id = '" . ( int ) $task_id . "' ";
									$this->db->query ( $sql12 );
									
									if ($data ['reminderplus']) {
										foreach ( $data ['reminderplus'] as $reminder ) {
											$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
											$this->db->query ( $sqlr );
										}
									}
									if ($data ['reminderminus']) {
										foreach ( $data ['reminderminus'] as $reminder ) {
											$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
											$this->db->query ( $sqlrm );
										}
									}
									
									if ($data ['locations']) {
										foreach ( $data ['locations'] as $location ) {
											
											$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
											
											$lastIdt = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
										}
									}
									
									if ($data ['tags_medication_details_ids']) {
										$i = 0;
										foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
											
											foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
												
												$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
												
												$lastIdtm = $this->db->query ( $sql1 );
												
												$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
												
												$i ++;
											}
										}
									}
								}
							} else {
								
								if ($drop_time == '1') {
									$snooze_time7 = '30';
									$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
								}
								
								$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tags ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
								
								$lastId = $this->db->query ( $sql1 );
								
								$task_id = $lastId->row ['task_id'];
								
								$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',form_task_creation = '" . $data ['form_task_creation'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
								$this->db->query ( $sql12 );
								
								if ($data ['reminderplus']) {
									foreach ( $data ['reminderplus'] as $reminder ) {
										$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
										$this->db->query ( $sqlr );
									}
								}
								if ($data ['reminderminus']) {
									foreach ( $data ['reminderminus'] as $reminder ) {
										$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
										$this->db->query ( $sqlrm );
									}
								}
								
								if ($data ['locations']) {
									foreach ( $data ['locations'] as $location ) {
										
										$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
										
										$lastIdt = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
									}
								}
								
								if ($data ['tags_medication_details_ids']) {
									$i = 0;
									foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
										
										foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
											
											$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
											
											$lastIdtm = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
											
											$i ++;
										}
									}
								}
							}
							
							if ($data ['task_alert'] == 1) {
								$this->load->model ( 'api/notification' );
								$ndata = array ();
								$ndata ['dateRange'] = $dateRange;
								$ndata ['tasksTiming'] = $tasksTiming;
								$ndata ['description'] = $description;
								$ndata ['facilities_id'] = $facilities_id;
								$ndata ['task_id'] = $task_id;
								$this->model_api_notification->sendnotification ( $data, $ndata );
							}
						}
					} else {
						
						if ($tasktype_info ['is_custom_offset'] > 0) {
							$recurnce_perpetual = $tasktype_info ['is_custom_offset'];
							$is_task_time = date ( 'H:i:s' );
							$tasksTiming = date ( 'H:i:s', strtotime ( ' +' . $recurnce_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
						} else {
							$tasksTiming = date ( 'H:i:s', strtotime ( $data ['daily_endtime'] ) );
						}
						
						$daily_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
						// var_dump($tasksTiming);
						// var_dump($daily_time1);
						
						if ($current_time > $daily_time1) {
							$form_due_date_after = '1';
							$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate ) ) . " +" . $form_due_date_after . " day" ) );
							
							// $taskDate = $taskDate1;
							
							if ($dateRange1 == $dateRange) {
								$end_recurrence_date = $taskDate1;
							} else {
								$end_recurrence_date = $end_recurrence_date1;
							}
						} else {
							$taskDate1 = $taskDate;
							$end_recurrence_date2 = $end_recurrence_date;
						}
						
						if ($data ['medication_tags']) {
							foreach ( $data ['medication_tags'] as $medication_tag ) {
								
								$this->load->model ( 'setting/tags' );
								$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
								
								if ($tags_info_m ['emp_first_name']) {
									$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
								} else {
									$description2 = $description;
								}
								
								if ($drop_time == '1') {
									$snooze_time7 = '30';
									$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
								}
								
								$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
								
								$lastId = $this->db->query ( $sql1 );
								
								$task_id = $lastId->row ['task_id'];
								
								$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',form_task_creation = '" . $data ['form_task_creation'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
								$this->db->query ( $sql12 );
								
								if ($data ['reminderplus']) {
									foreach ( $data ['reminderplus'] as $reminder ) {
										$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
										$this->db->query ( $sqlr );
									}
								}
								if ($data ['reminderminus']) {
									foreach ( $data ['reminderminus'] as $reminder ) {
										$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
										$this->db->query ( $sqlrm );
									}
								}
								
								if ($data ['locations']) {
									foreach ( $data ['locations'] as $location ) {
										
										$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
										
										$lastIdt = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
									}
								}
								
								if ($data ['tags_medication_details_ids']) {
									$i = 0;
									foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
										
										foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
											
											$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
											
											$lastIdtm = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
											
											$i ++;
										}
									}
								}
							}
						} else {
							
							if ($drop_time == '1') {
								$snooze_time7 = '30';
								$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
							}
							
							$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
						)";
							
							$lastId = $this->db->query ( $sql1 );
							
							$task_id = $lastId->row ['task_id'];
							
							$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',form_task_creation = '" . $data ['form_task_creation'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
							$this->db->query ( $sql12 );
							
							if ($data ['reminderplus']) {
								foreach ( $data ['reminderplus'] as $reminder ) {
									$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
									$this->db->query ( $sqlr );
								}
							}
							if ($data ['reminderminus']) {
								foreach ( $data ['reminderminus'] as $reminder ) {
									$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
									$this->db->query ( $sqlrm );
								}
							}
							
							if ($data ['locations']) {
								foreach ( $data ['locations'] as $location ) {
									
									$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
									$lastIdt = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
								}
							}
							
							if ($data ['tags_medication_details_ids']) {
								$i = 0;
								foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
									foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
										
										$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
										
										$lastIdtm = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
										
										$i ++;
									}
								}
							}
						}
						// die;
						
						if ($data ['task_alert'] == 1) {
							$this->load->model ( 'api/notification' );
							$ndata = array ();
							$ndata ['dateRange'] = $dateRange;
							$ndata ['tasksTiming'] = $tasksTiming;
							$ndata ['description'] = $description;
							$ndata ['facilities_id'] = $facilities_id;
							$ndata ['task_id'] = $task_id;
							$this->model_api_notification->sendnotification ( $data, $ndata );
						}
					}
					break;
				case "weekly" :
					if ($data ['recurnce_week']) {
						
						foreach ( $data ['recurnce_week'] as $weekd ) {
							// var_dump($taskDate);
							$dayName = date ( 'l', strtotime ( $taskDate ) );
							
							// var_dump($dayName);
							// echo "<hr>";
							
							// var_dump($taskDate);
							$d = strtotime ( $taskDate );
							
							// var_dump($weekd);
							
							$week_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
							// var_dump($week_time1);
							
							if ($dayName == $weekd) {
								if ($current_time > $week_time1) {
									$end_week = strtotime ( "next " . $weekd, $d );
								} else {
									$end_week = strtotime ( $weekd, $d );
								}
							} else {
								$end_week = strtotime ( "next " . $weekd, $d );
							}
							$end = date ( "Y-m-d", $end_week );
							
							$taskDate1 = $end . ' ' . $time;
							
							$taskDate12 = strtotime ( $taskDate1 );
							$end_recurrence_date2 = strtotime ( $end_recurrence_date );
							
							if ($taskDate12 > $end_recurrence_date2) {
								$end_recurrence_date2 = $taskDate1;
							} else {
								$end_recurrence_date2 = $end_recurrence_date;
							}
							
							if ($data ['medication_tags']) {
								foreach ( $data ['medication_tags'] as $medication_tag ) {
									
									$this->load->model ( 'setting/tags' );
									$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
									
									if ($tags_info_m ['emp_first_name']) {
										$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
									} else {
										$description2 = $description;
									}
									
									if ($drop_time == '1') {
										$snooze_time7 = '30';
										$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
									}
									
									$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
								,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
								'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
								'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
								'" . $this->db->escape ( $visitation_start_time ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address ) . "',
								'" . $this->db->escape ( $visitation_appoitment_time ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
								'" . $this->db->escape ( $visitation_tags ) . "',
								'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
								'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
								'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
								'" . $this->db->escape ( $completed_times ) . "',
								'" . $this->db->escape ( $data ['completed_alert'] ) . "',
								'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
								'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
								'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
								'" . $this->db->escape ( $data ['attachement_form'] ) . "',
								'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
								'" . $this->db->escape ( $task_group_by ) . "',
								'" . $this->db->escape ( $enable_requires_approval ) . "',
								'" . $this->db->escape ( $approval_taskid ) . "',
								'" . $this->db->escape ( serialize ( $response_all ) ) . "',
								'" . $this->db->escape ( $distance ) . "',
								'" . $this->db->escape ( $distancev ) . "',
								'" . $this->db->escape ( $duration ) . "',
								'" . $this->db->escape ( $durationv ) . "',
								'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
								'" . $this->db->escape ( $bed_check_location_ids ) . "',
								'" . $this->db->escape ( $data ['complete_status'] ) . "',
								'" . $this->db->escape ( $weekly_interval ) . "',
								'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
								'" . $this->db->escape ( $data ['is_android'] ) . "',
								'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
								)";
									
									$lastId = $this->db->query ( $sql1 );
									
									$task_id = $lastId->row ['task_id'];
									
									$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
									$this->db->query ( $sql12 );
									
									if ($data ['reminderplus']) {
										foreach ( $data ['reminderplus'] as $reminder ) {
											$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
											$this->db->query ( $sqlr );
										}
									}
									if ($data ['reminderminus']) {
										foreach ( $data ['reminderminus'] as $reminder ) {
											$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
											$this->db->query ( $sqlrm );
										}
									}
									
									if ($data ['locations']) {
										foreach ( $data ['locations'] as $location ) {
											
											$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
											
											$lastIdt = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
										}
									}
									
									if ($data ['tags_medication_details_ids']) {
										$i = 0;
										foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
											
											foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
												
												$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
												
												$lastIdtm = $this->db->query ( $sql1 );
												
												$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
												
												$i ++;
											}
										}
									}
								}
							} else {
								
								if ($drop_time == '1') {
									$snooze_time7 = '30';
									$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
								}
								
								$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tags ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
								
								$lastId = $this->db->query ( $sql1 );
								
								$task_id = $lastId->row ['task_id'];
								
								$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
								$this->db->query ( $sql12 );
								
								if ($data ['reminderplus']) {
									foreach ( $data ['reminderplus'] as $reminder ) {
										$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
										$this->db->query ( $sqlr );
									}
								}
								if ($data ['reminderminus']) {
									foreach ( $data ['reminderminus'] as $reminder ) {
										$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
										$this->db->query ( $sqlrm );
									}
								}
								
								if ($data ['locations']) {
									foreach ( $data ['locations'] as $location ) {
										
										$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
										
										$lastIdt = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
									}
								}
								
								if ($data ['tags_medication_details_ids']) {
									$i = 0;
									foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
										
										foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
											
											$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
											
											$lastIdtm = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
											
											$i ++;
										}
									}
								}
							}
							
							if ($data ['task_alert'] == 1) {
								$this->load->model ( 'api/notification' );
								$ndata = array ();
								$ndata ['dateRange'] = $dateRange;
								$ndata ['tasksTiming'] = $tasksTiming;
								$ndata ['weekd'] = $weekd;
								$ndata ['description'] = $description;
								$ndata ['facilities_id'] = $facilities_id;
								$ndata ['task_id'] = $task_id;
								$this->model_api_notification->sendnotification ( $data, $ndata );
							}
						}
					}
					break;
				case "monthly" :
					if ($data ['recurrence'] == "monthly") {
						
						// var_dump($data['recurnce_day']);
						
						$dayName = date ( 'd', strtotime ( $taskDate ) );
						
						// var_dump($dayName);
						
						$monthly_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
						
						if ($dayName > $data ['recurnce_day']) {
							$daymonth = '1';
						} else {
							
							if ($current_time > $monthly_time1) {
								$daymonth = '1';
							} else {
								$daymonth = '0';
							}
						}
						
						// var_dump($daymonth);
						// var_dump($taskDate);
						// echo "<br>";
						
						$tm = date ( 'm', strtotime ( '+' . $daymonth . ' month', strtotime ( $taskDate ) ) );
						$ty = date ( 'Y', strtotime ( $taskDate ) );
						
						// var_dump($tdate);
						
						$end = $ty . '-' . $tm . '-' . $data ['recurnce_day'];
						
						$taskDate1 = $end . ' ' . $time;
						
						$taskDate12 = strtotime ( $taskDate1 );
						$end_recurrence_date2 = strtotime ( $end_recurrence_date );
						
						if ($taskDate12 > $end_recurrence_date2) {
							$end_recurrence_date2 = $taskDate1;
						} else {
							$end_recurrence_date2 = $end_recurrence_date;
						}
						
						// $recurnce_day1 = date('Y-m-d' strtotime());
						
						if ($data ['medication_tags']) {
							foreach ( $data ['medication_tags'] as $medication_tag ) {
								
								$this->load->model ( 'setting/tags' );
								$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
								
								if ($tags_info_m ['emp_first_name']) {
									$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
								} else {
									$description2 = $description;
								}
								
								if ($drop_time == '1') {
									$snooze_time7 = '30';
									$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
								}
								
								$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
								
								$lastId = $this->db->query ( $sql1 );
								
								$task_id = $lastId->row ['task_id'];
								
								$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
								$this->db->query ( $sql12 );
								
								if ($data ['reminderplus']) {
									foreach ( $data ['reminderplus'] as $reminder ) {
										$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
										$this->db->query ( $sqlr );
									}
								}
								if ($data ['reminderminus']) {
									foreach ( $data ['reminderminus'] as $reminder ) {
										$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
										$this->db->query ( $sqlrm );
									}
								}
								
								if ($data ['locations']) {
									foreach ( $data ['locations'] as $location ) {
										
										$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
										
										$lastIdt = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
									}
								}
								
								if ($data ['tags_medication_details_ids']) {
									$i = 0;
									foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
										
										foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
											
											$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
											
											$lastIdtm = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
											
											$i ++;
										}
									}
								}
							}
						} else {
							
							if ($drop_time == '1') {
								$snooze_time7 = '30';
								$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
							}
							
							$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
						)";
							
							$lastId = $this->db->query ( $sql1 );
							
							$task_id = $lastId->row ['task_id'];
							
							$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',form_task_creation = '" . $data ['form_task_creation'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
							$this->db->query ( $sql12 );
							
							if ($data ['reminderplus']) {
								foreach ( $data ['reminderplus'] as $reminder ) {
									$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
									$this->db->query ( $sqlr );
								}
							}
							if ($data ['reminderminus']) {
								foreach ( $data ['reminderminus'] as $reminder ) {
									$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
									$this->db->query ( $sqlrm );
								}
							}
							
							if ($data ['locations']) {
								foreach ( $data ['locations'] as $location ) {
									
									$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
									$lastIdt = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
								}
							}
							
							if ($data ['tags_medication_details_ids']) {
								$i = 0;
								foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
									foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
										
										$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
										
										$lastIdtm = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
										
										$i ++;
									}
								}
							}
						}
						// die;
						
						if ($data ['task_alert'] == 1) {
							$this->load->model ( 'api/notification' );
							$ndata = array ();
							$ndata ['dateRange'] = $dateRange;
							$ndata ['tasksTiming'] = $tasksTiming;
							$ndata ['description'] = $description;
							$ndata ['facilities_id'] = $facilities_id;
							$ndata ['task_id'] = $task_id;
							$this->model_api_notification->sendnotification ( $data, $ndata );
						}
					}
					break;
				case "yearly" :
					$daily_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
					
					if ($current_time > $daily_time1) {
						// $form_due_date_after = '1';
						// $taskDate1 = date("Y-m-d
						// H:i:s",strtotime(date("Y-m-d H:i:s",
						// strtotime($taskDate)) . "
						// +".$form_due_date_after." day"));
						
						$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 years', strtotime ( $taskDate ) ) );
					} else {
						
						$taskDate1 = $taskDate;
					}
					
					$end_recurrence_date2 = $end_recurrence_date;
					
					if ($data ['medication_tags']) {
						foreach ( $data ['medication_tags'] as $medication_tag ) {
							
							$this->load->model ( 'setting/tags' );
							$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
							
							if ($tags_info_m ['emp_first_name']) {
								$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
							} else {
								$description2 = $description;
							}
							
							if ($drop_time == '1') {
								$snooze_time7 = '30';
								$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
							}
							
							$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
							
							$lastId = $this->db->query ( $sql1 );
							
							$task_id = $lastId->row ['task_id'];
							
							$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',form_task_creation = '" . $data ['form_task_creation'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
							$this->db->query ( $sql12 );
							
							if ($data ['reminderplus']) {
								foreach ( $data ['reminderplus'] as $reminder ) {
									$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
									$this->db->query ( $sqlr );
								}
							}
							if ($data ['reminderminus']) {
								foreach ( $data ['reminderminus'] as $reminder ) {
									$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
									$this->db->query ( $sqlrm );
								}
							}
							
							if ($data ['locations']) {
								foreach ( $data ['locations'] as $location ) {
									
									$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
									$lastIdt = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
								}
							}
							
							if ($data ['tags_medication_details_ids']) {
								$i = 0;
								foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
									foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
										
										$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
										
										$lastIdtm = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
										
										$i ++;
									}
								}
							}
						}
					} else {
						
						if ($drop_time == '1') {
							$snooze_time7 = '30';
							$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
						}
						
						$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
						)";
						
						$lastId = $this->db->query ( $sql1 );
						
						$task_id = $lastId->row ['task_id'];
						
						$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
						$this->db->query ( $sql12 );
						
						if ($data ['reminderplus']) {
							foreach ( $data ['reminderplus'] as $reminder ) {
								$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
								$this->db->query ( $sqlr );
							}
						}
						if ($data ['reminderminus']) {
							foreach ( $data ['reminderminus'] as $reminder ) {
								$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
								$this->db->query ( $sqlrm );
							}
						}
						
						if ($data ['locations']) {
							foreach ( $data ['locations'] as $location ) {
								
								$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
								
								$lastIdt = $this->db->query ( $sql1 );
								
								$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
							}
						}
						
						if ($data ['tags_medication_details_ids']) {
							$i = 0;
							foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
								
								foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
									
									$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
									
									$lastIdtm = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
									
									$i ++;
								}
							}
						}
					}
					// die;
					
					if ($data ['task_alert'] == 1) {
						$this->load->model ( 'api/notification' );
						$ndata = array ();
						$ndata ['dateRange'] = $dateRange;
						$ndata ['tasksTiming'] = $tasksTiming;
						$ndata ['description'] = $description;
						$ndata ['facilities_id'] = $facilities_id;
						$ndata ['task_id'] = $task_id;
						$this->model_api_notification->sendnotification ( $data, $ndata );
					}
					
					break;
				case "Perpetual" :
					$daily_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
					
					if ($current_time > $daily_time1) {
						$form_due_date_after = '1';
						$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate ) ) . " +" . $form_due_date_after . " day" ) );
					} else {
						
						$taskDate1 = $taskDate;
					}
					$newData1 = date ( 'm-d-Y', strtotime ( "+5 years", strtotime ( $dateRange ) ) );
					
					$date1 = str_replace ( '-', '/', $newData1 );
					$res1 = explode ( "/", $date1 );
					$dateRange1 = $res1 [2] . "-" . $res1 [0] . "-" . $res1 [1];
					
					$time1 = date ( 'H:i:s' );
					$end_recurrence_date = $dateRange1 . ' ' . $time1;
					
					$end_recurrence_date2 = $end_recurrence_date;
					
					if ($data ['medication_tags']) {
						foreach ( $data ['medication_tags'] as $medication_tag ) {
							
							$this->load->model ( 'setting/tags' );
							$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
							
							if ($tags_info_m ['emp_first_name']) {
								$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
							} else {
								$description2 = $description;
							}
							
							if ($drop_time == '1') {
								$snooze_time7 = '30';
								$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
							}
							
							$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
							
							$lastId = $this->db->query ( $sql1 );
							
							$task_id = $lastId->row ['task_id'];
							
							$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
							$this->db->query ( $sql12 );
							
							if ($data ['reminderplus']) {
								foreach ( $data ['reminderplus'] as $reminder ) {
									$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
									$this->db->query ( $sqlr );
								}
							}
							if ($data ['reminderminus']) {
								foreach ( $data ['reminderminus'] as $reminder ) {
									$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
									$this->db->query ( $sqlrm );
								}
							}
							
							if ($data ['locations']) {
								foreach ( $data ['locations'] as $location ) {
									
									$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
									$lastIdt = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
								}
							}
							
							if ($data ['tags_medication_details_ids']) {
								$i = 0;
								foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
									foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
										
										$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
										
										$lastIdtm = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
										
										$i ++;
									}
								}
							}
						}
					} else {
						
						if ($drop_time == '1') {
							$snooze_time7 = '30';
							$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
						}
						
						$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
						)";
						
						$lastId = $this->db->query ( $sql1 );
						
						$task_id = $lastId->row ['task_id'];
						
						$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
						$this->db->query ( $sql12 );
						
						if ($data ['reminderplus']) {
							foreach ( $data ['reminderplus'] as $reminder ) {
								$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
								$this->db->query ( $sqlr );
							}
						}
						if ($data ['reminderminus']) {
							foreach ( $data ['reminderminus'] as $reminder ) {
								$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
								$this->db->query ( $sqlrm );
							}
						}
						
						if ($data ['locations']) {
							foreach ( $data ['locations'] as $location ) {
								
								$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
								
								$lastIdt = $this->db->query ( $sql1 );
								
								$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
							}
						}
						
						if ($data ['tags_medication_details_ids']) {
							$i = 0;
							foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
								
								foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
									
									$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
									
									$lastIdtm = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
									
									$i ++;
								}
							}
						}
					}
					// die;
					
					if ($data ['task_alert'] == 1) {
						$this->load->model ( 'api/notification' );
						$ndata = array ();
						$ndata ['dateRange'] = $dateRange;
						$ndata ['tasksTiming'] = $tasksTiming;
						$ndata ['description'] = $description;
						$ndata ['facilities_id'] = $facilities_id;
						$ndata ['task_id'] = $task_id;
						$this->model_api_notification->sendnotification ( $data, $ndata );
					}
					
					break;
				default :
					
					if ($data ['daily_times']) {
						
						foreach ( $data ['daily_times'] as $dailytime ) {
							$tasksTiming = date ( 'H:i:s', strtotime ( $dailytime ) );
							
							$daily_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
							
							// var_dump($current_time);
							// var_dump($daily_time1);
							
							if ($current_time > $daily_time1) {
								
								$form_due_date_after = '1';
								$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate ) ) . " +" . $form_due_date_after . " day" ) );
								
								$taskDate = $taskDate1;
								
								if ($dateRange1 == $dateRange) {
									$end_recurrence_date = $taskDate1;
								} else {
									$end_recurrence_date = $end_recurrence_date1;
								}
								
								// var_dump($end_recurrence_date);
							} else {
								
								$taskDate = $taskDate221;
								
								$end_recurrence_date = $end_recurrence_date1;
							}
							
							if ($data ['medication_tags']) {
								foreach ( $data ['medication_tags'] as $medication_tag ) {
									
									$this->load->model ( 'setting/tags' );
									$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
									
									if ($tags_info_m ['emp_first_name']) {
										$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
									} else {
										$description2 = $description;
									}
									
									if ($drop_time == '1') {
										$snooze_time7 = '30';
										$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
									}
									
									$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
								,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
								'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
								'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
								'" . $this->db->escape ( $visitation_start_time ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address ) . "',
								'" . $this->db->escape ( $visitation_appoitment_time ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
								'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
								'" . $this->db->escape ( $visitation_tags ) . "',
								'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
								'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
								'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
								'" . $this->db->escape ( $completed_times ) . "',
								'" . $this->db->escape ( $data ['completed_alert'] ) . "',
								'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
								'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
								'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
								'" . $this->db->escape ( $data ['attachement_form'] ) . "',
								'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
								'" . $this->db->escape ( $task_group_by ) . "',
								'" . $this->db->escape ( $enable_requires_approval ) . "',
								'" . $this->db->escape ( $approval_taskid ) . "',
								'" . $this->db->escape ( serialize ( $response_all ) ) . "',
								'" . $this->db->escape ( $distance ) . "',
								'" . $this->db->escape ( $distancev ) . "',
								'" . $this->db->escape ( $duration ) . "',
								'" . $this->db->escape ( $durationv ) . "',
								'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
								'" . $this->db->escape ( $bed_check_location_ids ) . "',
								'" . $this->db->escape ( $data ['complete_status'] ) . "',
								'" . $this->db->escape ( $weekly_interval ) . "',
								'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
								'" . $this->db->escape ( $data ['is_android'] ) . "',
								'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
								)";
									
									$lastId = $this->db->query ( $sql1 );
									
									$task_id = $lastId->row ['task_id'];
									
									$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',assign_to = '" . $assign_to . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
									$this->db->query ( $sql12 );
									
									if ($data ['reminderplus']) {
										foreach ( $data ['reminderplus'] as $reminder ) {
											$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
											$this->db->query ( $sqlr );
										}
									}
									if ($data ['reminderminus']) {
										foreach ( $data ['reminderminus'] as $reminder ) {
											$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
											$this->db->query ( $sqlrm );
										}
									}
									
									if ($data ['locations']) {
										foreach ( $data ['locations'] as $location ) {
											
											$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
											
											$lastIdt = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
										}
									}
									
									if ($data ['tags_medication_details_ids']) {
										$i = 0;
										foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
											
											foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
												
												$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
												
												$lastIdtm = $this->db->query ( $sql1 );
												
												$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
												
												$i ++;
											}
										}
									}
								}
							} else {
								
								if ($drop_time == '1') {
									$snooze_time7 = '30';
									$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
								}
								
								$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tags ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
								
								$lastId = $this->db->query ( $sql1 );
								
								$task_id = $lastId->row ['task_id'];
								
								$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
								$this->db->query ( $sql12 );
								
								if ($data ['reminderplus']) {
									foreach ( $data ['reminderplus'] as $reminder ) {
										$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
										$this->db->query ( $sqlr );
									}
								}
								if ($data ['reminderminus']) {
									foreach ( $data ['reminderminus'] as $reminder ) {
										$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
										$this->db->query ( $sqlrm );
									}
								}
								
								if ($data ['locations']) {
									foreach ( $data ['locations'] as $location ) {
										
										$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
										
										$lastIdt = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
									}
								}
								
								if ($data ['tags_medication_details_ids']) {
									$i = 0;
									foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
										
										foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
											
											$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
											
											$lastIdtm = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
											
											$i ++;
										}
									}
								}
							}
							
							if ($data ['task_alert'] == 1) {
								$this->load->model ( 'api/notification' );
								$ndata = array ();
								$ndata ['dateRange'] = $dateRange;
								$ndata ['tasksTiming'] = $tasksTiming;
								$ndata ['description'] = $description;
								$ndata ['facilities_id'] = $facilities_id;
								$ndata ['task_id'] = $task_id;
								$this->model_api_notification->sendnotification ( $data, $ndata );
							}
						}
					} else {
						
						$daily_time1 = date ( 'H:i:s', strtotime ( $tasksTiming ) );
						
						if ($current_time > $daily_time1) {
							$form_due_date_after = '1';
							$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $taskDate ) ) . " +" . $form_due_date_after . " day" ) );
							
							if ($dateRange1 == $dateRange) {
								$end_recurrence_date = $taskDate1;
							} else {
								$end_recurrence_date = $end_recurrence_date1;
							}
						} else {
							
							$taskDate1 = $taskDate;
							$end_recurrence_date2 = $end_recurrence_date;
						}
						
						if ($data ['medication_tags']) {
							foreach ( $data ['medication_tags'] as $medication_tag ) {
								
								$this->load->model ( 'setting/tags' );
								$tags_info_m = $this->model_setting_tags->getTag ( $medication_tag );
								
								if ($tags_info_m ['emp_first_name']) {
									$description2 = $tags_info_m ['emp_first_name'] . ' | ' . $description;
								} else {
									$description2 = $description;
								}
								
								if ($drop_time == '1') {
									$snooze_time7 = '30';
									$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
								}
								
								$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description2 ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
							,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
							'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_start_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address ) . "',
							'" . $this->db->escape ( $visitation_appoitment_time ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
							'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
							'" . $this->db->escape ( $visitation_tags ) . "',
							'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
							'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
							'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
							'" . $this->db->escape ( $completed_times ) . "',
							'" . $this->db->escape ( $data ['completed_alert'] ) . "',
							'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
							'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
							'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
							'" . $this->db->escape ( $data ['attachement_form'] ) . "',
							'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
							'" . $this->db->escape ( $task_group_by ) . "',
							'" . $this->db->escape ( $enable_requires_approval ) . "',
							'" . $this->db->escape ( $approval_taskid ) . "',
							'" . $this->db->escape ( serialize ( $response_all ) ) . "',
							'" . $this->db->escape ( $distance ) . "',
							'" . $this->db->escape ( $distancev ) . "',
							'" . $this->db->escape ( $duration ) . "',
							'" . $this->db->escape ( $durationv ) . "',
							'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
							'" . $this->db->escape ( $bed_check_location_ids ) . "',
							'" . $this->db->escape ( $data ['complete_status'] ) . "',
							'" . $this->db->escape ( $weekly_interval ) . "',
							'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
							'" . $this->db->escape ( $data ['is_android'] ) . "',
							'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
							)";
								
								$lastId = $this->db->query ( $sql1 );
								
								$task_id = $lastId->row ['task_id'];
								
								$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
								$this->db->query ( $sql12 );
								
								if ($data ['reminderplus']) {
									foreach ( $data ['reminderplus'] as $reminder ) {
										$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
										$this->db->query ( $sqlr );
									}
								}
								if ($data ['reminderminus']) {
									foreach ( $data ['reminderminus'] as $reminder ) {
										echo $sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
										$this->db->query ( $sqlrm );
									}
								}
								
								if ($data ['locations']) {
									foreach ( $data ['locations'] as $location ) {
										
										$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
										
										$lastIdt = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
									}
								}
								
								if ($data ['tags_medication_details_ids']) {
									$i = 0;
									foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
										
										foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
											
											$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
											
											$lastIdtm = $this->db->query ( $sql1 );
											
											$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
											
											$i ++;
										}
									}
								}
							}
						} else {
							
							if ($drop_time == '1') {
								$snooze_time7 = '30';
								$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $tasksTiming ) ) );
							}
							
							$sql1 = "CALL insertTasks('" . $facilities_id . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $tasksTiming ) . "','" . $this->db->escape ( $taskDate1 ) . "','" . $this->db->escape ( $description ) . "','" . $this->db->escape ( $data ['assignto'] ) . "','" . $this->db->escape ( $data ['recurrence'] ) . "','" . $this->db->escape ( $recurnce_hrly2 ) . "','" . $end_recurrence_date2 . "','" . $this->db->escape ( $taskeTiming ) . "','" . $this->db->escape ( $data ['tasktype'] ) . "','" . $this->db->escape ( $recurnce_day ) . "','" . $this->db->escape ( $recurnce_month ) . "','" . $this->db->escape ( $recurnce_week ) . "','" . $this->db->escape ( $data ['task_alert'] ) . "','" . $this->db->escape ( $data ['alert_type_sms'] ) . "','" . $this->db->escape ( $data ['alert_type_notification'] ) . "','" . $this->db->escape ( $data ['alert_type_email'] ) . "','" . $this->db->escape ( $data ['numChecklist'] ) . "','" . $this->db->escape ( $data ['rules_task'] ) . "','" . $this->db->escape ( $task_form_id ) . "','" . $this->db->escape ( $tags_id ) . "','" . $this->db->escape ( $pickup_locations_address ) . "','" . $this->db->escape ( $pickup_locations_address_latitude ) . "','" . $this->db->escape ( $pickup_locations_address_longitude ) . "','" . $this->db->escape ( $pickup_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address ) . "','" . $this->db->escape ( $dropoff_locations_time ) . "','" . $this->db->escape ( $dropoff_locations_address_latitude ) . "','" . $this->db->escape ( $dropoff_locations_address_longitude ) . "','" . $this->db->escape ( $transport_tags ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "','" . $this->db->escape ( $medication_tag ) . "','" . $this->db->escape ( $data ['completion_alert'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "','" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "'
						,'" . $this->db->escape ( $user_roles ) . "','" . $this->db->escape ( $userids ) . "','" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "','" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $due_date_time ) . "','" . $this->db->escape ( $visitation_start_address ) . "' ,
						'" . $this->db->escape ( $visitation_start_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_start_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_start_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address ) . "',
						'" . $this->db->escape ( $visitation_appoitment_time ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_latitude ) . "',
						'" . $this->db->escape ( $visitation_appoitment_address_longitude ) . "',
						'" . $this->db->escape ( $visitation_tags ) . "',
						'" . $this->db->escape ( $visitation_start_facilities_id ) . "',
						'" . $this->db->escape ( $visitation_appoitment_facilities_id ) . "',
						'" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
						'" . $this->db->escape ( $completed_times ) . "',
						'" . $this->db->escape ( $data ['completed_alert'] ) . "',
						'" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
						'" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
						'" . $this->db->escape ( $data ['deleted_alert'] ) . "',
						'" . $this->db->escape ( $data ['attachement_form'] ) . "',
						'" . $this->db->escape ( $data ['tasktype_form_id'] ) . "',
						'" . $this->db->escape ( $task_group_by ) . "',
						'" . $this->db->escape ( $enable_requires_approval ) . "',
						'" . $this->db->escape ( $approval_taskid ) . "',
						'" . $this->db->escape ( serialize ( $response_all ) ) . "',
						'" . $this->db->escape ( $distance ) . "',
						'" . $this->db->escape ( $distancev ) . "',
						'" . $this->db->escape ( $duration ) . "',
						'" . $this->db->escape ( $durationv ) . "',
						'" . $this->db->escape ( $data ['iswaypoint'] ) . "',
						'" . $this->db->escape ( $bed_check_location_ids ) . "',
						'" . $this->db->escape ( $data ['complete_status'] ) . "',
						'" . $this->db->escape ( $weekly_interval ) . "',
						'" . $this->db->escape ( $data ['phone_device_id'] ) . "',
						'" . $this->db->escape ( $data ['is_android'] ) . "',
						'" . $this->db->escape ( $unique_id ) . "' ,'" . $this->db->escape ( $activecustomer_id ) . "'
						)";
							
							$lastId = $this->db->query ( $sql1 );
							
							$task_id = $lastId->row ['task_id'];
							
							$sql12 = "UPDATE `" . DB_PREFIX . "createtask` SET required_approval = '" . $data ['required_approval'] . "',linked_id = '" . $data ['linked_id'] . "',formreturn_id = '" . $data ['formreturn_id'] . "',target_facilities_id = '" . $data ['target_facilities_id'] . "',user_role_assign_ids = '" . $user_role_assign_ids . "',form_task_creation = '" . $data ['form_task_creation'] . "',assign_to = '" . $assign_to . "',assign_to_type = '" . $data ['assign_to_type'] . "',reminder_alert = '" . $data ['reminder_alert'] . "' WHERE id = '" . ( int ) $task_id . "' ";
							$this->db->query ( $sql12 );
							
							if ($data ['reminderplus']) {
								foreach ( $data ['reminderplus'] as $reminder ) {
									$sqlr = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'plus' ";
									$this->db->query ( $sqlr );
								}
							}
							if ($data ['reminderminus']) {
								foreach ( $data ['reminderminus'] as $reminder ) {
									
									$sqlrm = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', minute = '" . $this->db->escape ( $reminder ) . "', action = 'minus' ";
									$this->db->query ( $sqlrm );
								}
							}
							
							if ($data ['locations']) {
								foreach ( $data ['locations'] as $location ) {
									
									$sql1 = "CALL insertTaskstransport('" . $task_id . "','" . $this->db->escape ( $location ['locations_address'] ) . "','" . $this->db->escape ( $location ['latitude'] ) . "','" . $this->db->escape ( $location ['longitude'] ) . "','" . $this->db->escape ( $location ['place_id'] ) . "' )";
									
									$lastIdt = $this->db->query ( $sql1 );
									
									$createtask_by_transport_id = $lastIdt->row ['createtask_by_transport_id'];
								}
							}
							
							if ($data ['tags_medication_details_ids']) {
								$i = 0;
								foreach ( $data ['tags_medication_details_ids'] as $key => $tags_medication_details_ids ) {
									
									foreach ( $tags_medication_details_ids as $tags_medication_details_id ) {
										
										$sql1 = "CALL insertTasksmedications('" . $task_id . "','" . $this->db->escape ( $facilities_id ) . "','" . $this->db->escape ( $key ) . "','" . $this->db->escape ( $tags_medication_details_id ) . "','" . $this->db->escape ( $currentdate ) . "','" . $this->db->escape ( $data ['complete_status'] ) . "' )";
										
										$lastIdtm = $this->db->query ( $sql1 );
										
										$createtask_by_transport_id = $lastIdtm->row ['createtask_by_transport_id'];
										
										$i ++;
									}
								}
							}
						}
						// die;
						
						if ($data ['task_alert'] == 1) {
							$this->load->model ( 'api/notification' );
							$ndata = array ();
							$ndata ['dateRange'] = $dateRange;
							$ndata ['tasksTiming'] = $tasksTiming;
							$ndata ['description'] = $description;
							$ndata ['facilities_id'] = $facilities_id;
							$ndata ['task_id'] = $task_id;
							$this->model_api_notification->sendnotification ( $data, $ndata );
						}
					}
			}
			
			/*
			 * if($data['rules_task'] != null && $data['rules_task'] != ""){
			 * $sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2'
			 * where notes_id ='".$data['rules_task']."'";
			 * $this->db->query($sqlw);
			 * }
			 */
		}
		// die;
		
		if ($data ['assignto_email'] != NULL && $data ['assignto_email'] != "") {
			
			$sql1 = "UPDATE `" . DB_PREFIX . "user` SET email = '" . $data ['assignto_email'] . "' WHERE username = '" . $data ['assignto'] . "'";
			$query = $this->db->query ( $sql1 );
		}
		
		if ($data ['assignto_sms'] != NULL && $data ['assignto_sms'] != "") {
			
			$sql2 = "UPDATE `" . DB_PREFIX . "user` SET phone_number = '" . $data ['assignto_sms'] . "' WHERE username = '" . $data ['assignto'] . "'";
			$query = $this->db->query ( $sql2 );
		}
		
		$this->load->model ( 'createtask/createtask' );
		$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $data ['tasktype'], $facilities_id );
		$tasktype_id = $tasktype_info ['task_id'];
		$tasktypetype = $tasktype_info ['type'];
		
		if ($tasktypetype == '3') {
			$sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $task_id . "', forms_id = '0', notes_id = '0',  status = 'high', tags_id = '" . $data ['emp_tag_id'] . "', parent_id = '0' ";
			$this->db->query ( $sql );
		}
		
		$this->load->model ( 'activity/activity' );
		$data ['task_id'] = $task_id;
		$data ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'addcreatetask', $data, 'query' );
		
		return $task_id;
	}
	public function gettasktyperow($task_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "tasktype WHERE task_id = '" . ( int ) $task_id . "'" );
		return $query->row;
	}
	public function gettaskrow($task_id) {
		$query = $this->db->query ( "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,weekly_interval,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM " . DB_PREFIX . "createtask WHERE id = '" . ( int ) $task_id . "'" );
		return $query->row;
	}
	public function gettasktyperowByName($tasktype_name, $facilities_id) {
		$this->load->model ( 'api/permision' );
		if (ALLTASKTYPE == '1') {
			$current_customer = $this->model_api_permision->getcustomerid ( $facilities_id );
			$sqln = "";
			if ($current_customer != null && $current_customer != "") {
				$sqln .= " and customer_key = '" . $current_customer . "' ";
			}
		}
		
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "tasktype WHERE tasktype_name = '" . $this->db->escape ( $tasktype_name ) . "' " . $sqln . " " );
		return $query->row;
	}
	public function getNotification($facilities_id) {
		$sqlquery = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE facilityId = '" . $facilities_id . "'";
		$query = $this->db->query ( $sqlquery );
		return $query->rows;
	}
	public function getTasklist($facilities_id, $searchdate, $top, $tags_id) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE facilityId = '" . $facilities_id . "' and taskadded = '0' and enable_requires_approval !=1  ";
		
		$date = str_replace ( '-', '/', $searchdate );
		$res = explode ( "/", $date );
		$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
		
		$startDate = $changedDate; /*
		                            * date('Y-m-d',
		                            * strtotime($data['searchdate']));
		                            */
		/* $endDate = date('Y-m-d'); */
		$endDate = $changedDate; /*
		                          * date('Y-m-d',
		                          * strtotime($data['searchdate']));
		                          */
		
		$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59' or enable_requires_approval = 2 ) ";
		
		if ($tags_id != "" && $tags_id != null) {
			// $sql .= " and emp_tag_id = '".$tags_id."' ";
			$sql .= " and ( emp_tag_id = '" . $tags_id . "' or medication_tags = '" . $tags_id . "' or visitation_tag_id = '" . $tags_id . "' or FIND_IN_SET('" . $tags_id . "', transport_tags) ) ";
		}
		
		/*
		 * if($top == '1'){
		 * $thestime = date('h:i:s');
		 * $stime = date("H:i:s",strtotime("-15 minutes",strtotime($thestime)));
		 * $endtime2 = date('h:i:s')+strtotime("+30 minutes");
		 *
		 * $endtime = date('H:i:s',$endtime2);
		 *
		 * $sql .= " and (`task_time` BETWEEN '".$stime."' AND '".$endtime." ')
		 * ";
		 * }
		 */
		
		$sql .= "ORDER BY task_time ASC";
		
		// $sql = "CALL gettasklists('" . $facilities_id . "', '0', '1', '" . $startDate . " 00:00:00', '" . $endDate . " 23:59:59', '" . $tags_id . "', '', 'task_time ASC', '0', '200' )";
		
		// echo $sql;
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function gettaskLists($searchdate, $facilities_id) {
		$taskLists = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE taskadded = '0' and enable_requires_approval != '1' and facilityId = '" . $facilities_id . "' ";
		
		if ($searchdate != null && $searchdate != "") {
			$date = str_replace ( '-', '/', $searchdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$startDate = $changedDate;
			/*
			 * date('Y-m-d',
			 * strtotime($data['searchdate']));
			 */
			/* $endDate = date('Y-m-d'); */
			
			$endDate = $changedDate;
			/*
			 * date('Y-m-d',
			 * strtotime($data['searchdate']));
			 */
			
			$taskLists .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59') ";
		}
		
		$query = $this->db->query ( $taskLists );
		return $query->rows;
	}
	public function gettaskListsdeleted($searchdate, $end_date, $facilities_id) {
		$taskLists = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE taskadded = '0' and enable_requires_approval != '1' and facilityId = '" . $facilities_id . "' ";
		
		if ($searchdate != null && $searchdate != "") {
			$date = str_replace ( '-', '/', $searchdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$startDate = $searchdate;
			
			$endDate = $end_date;
			
			$taskLists .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59') ";
		}
		
		$query = $this->db->query ( $taskLists );
		return $query->rows;
	}
	public function getCountTasklist($facilities_id, $searchdate, $top, $facilities_timezone, $tags_id, $tasktype_id) {
		
		// var_dump($tasktype_id);
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "createtask` ";
		
		$sql .= 'where 1 = 1  and enable_requires_approval !=1 ';
		$sql .= " and facilityId = '" . $facilities_id . "' ";
		
		if ($searchdate != null && $searchdate != "") {
			$date = str_replace ( '-', '/', $searchdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$startDate = $changedDate; /*
			                            * date('Y-m-d',
			                            * strtotime($data['searchdate']));
			                            */
			/* $endDate = date('Y-m-d'); */
			$endDate = $changedDate; /*
			                          * date('Y-m-d',
			                          * strtotime($data['searchdate']));
			                          */
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59') ";
		}
		
		if ($tags_id != "" && $tags_id != null) {
			// $sql .= " and emp_tag_id = '".$tags_id."' ";
			$sql .= " and ( emp_tag_id = '" . $tags_id . "' or medication_tags = '" . $tags_id . "' or visitation_tag_id = '" . $tags_id . "' or FIND_IN_SET('" . $tags_id . "', transport_tags) ) ";
		}
		
		if ($tasktype_id != null && $tasktype_id != "") {
			
			$this->load->model ( 'createtask/createtask' );
			$tasktype_info = $this->model_createtask_createtask->gettasktyperow ( $tasktype_id );
			
			if ($tasktype_info ['custom_completion_rule'] == '1') {
				$startaddTime = $tasktype_info ['config_task_minandmax_time'];
				// var_dump($startaddTime);
				$endaddTime = $tasktype_info ['config_task_minandmax_after_time'];
				// var_dump($endaddTime);
				$sql .= " and `tasktype` =  '" . $tasktype_info ['tasktype_name'] . "' ";
			} else {
				$startaddTime = $this->config->get ( 'config_task_minandmax_time' );
				$endaddTime = $this->config->get ( 'config_task_minandmax_after_time' );
				
				$sql .= " and `tasktype` =  '" . $tasktype_info ['tasktype_name'] . "' ";
			}
		} else {
			$startaddTime = $this->config->get ( 'config_task_minandmax_time' );
			$endaddTime = $this->config->get ( 'config_task_minandmax_after_time' );
		}
		
		// $startaddTime = $this->config->get('config_task_minandmax_time');
		// $endaddTime = $this->config->get('config_task_minandmax_after_time');
		
		if ($top == '1') {
			// date_default_timezone_set($this->session->data['time_zone_1']);
			
			$timezone_name = $this->customer->isTimezone ();
			
			$timeZone = date_default_timezone_set ( $timezone_name );
			
			$thestime = date ( 'H:i:s' );
			
			$stime = date ( "H:i:s", strtotime ( "-" . $startaddTime . " minutes", strtotime ( $thestime ) ) );
			$endtime2 = date ( 'h:i:s' ) + strtotime ( "+" . $endaddTime . " minutes" );
			
			$endtime = date ( 'H:i:s', $endtime2 );
			
			$sql .= " and (`task_time` BETWEEN  '" . $stime . "' AND  '" . $endtime . "') ";
		}
		
		if ($top == '2') {
			date_default_timezone_set ( $facilities_timezone );
			
			$thestime = date ( 'H:i:s' );
			
			$stime = date ( "H:i:s", strtotime ( "-" . $startaddTime . " minutes", strtotime ( $thestime ) ) );
			$endtime2 = date ( 'h:i:s' ) + strtotime ( "+" . $endaddTime . " minutes" );
			
			$endtime = date ( 'H:i:s', $endtime2 );
			
			$sql .= " and (`task_time` BETWEEN  '" . $stime . "' AND  '" . $endtime . " ') ";
		}
		
		$sql .= " and taskadded = '0' ";
		// echo $sql;
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function getUsername($assign_to) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $assign_to . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getnotesInfo($task_id) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE id = '" . $task_id . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function updatetaskStrike($task_id) {
		$sql = "update `" . DB_PREFIX . "createtask` set taskadded = '1' where id='" . $task_id . "'";
		$this->db->query ( $sql );
	}
	public function updatetaskNote($task_id) {
		$sql = "update `" . DB_PREFIX . "createtask` set taskadded = '2' where id='" . $task_id . "'";
		$this->db->query ( $sql );
	}
	public function getStrikedatadetails($task_id) {
		$sql = "SELECT  id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` where id='" . $task_id . "' and taskadded = '0' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function insertDatadetails($result, $data, $facilities_id, $requires_approval) {
		// if($data['perpetual_checkbox'] != NULL && $data['perpetual_checkbox'] != '' ){
		$sql2 = "update `" . DB_PREFIX . "createtask` set task_action = '" . $data ['perpetual_checkbox'] . "' where id ='" . $result ['id'] . "'";
		$this->db->query ( $sql2 );
		// }
		
		if ($data ['perpetual_checkbox'] == '4') {
			if ($result ['recurrence'] == 'hourly') {
				$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET recurnce_hrly = '" . $data ['acttion_interval_id'] . "' where id= '" . $result ['id'] . "' ";
				$this->db->query ( $sql2 );
			} else {
				$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET recurnce_hrly_perpetual = '" . $data ['acttion_interval_id'] . "' where id = '" . $result ['id'] . "' ";
				$this->db->query ( $sql2 );
			}
		}
		
		/* if($data['hiddenData'] == '2'){ */
		if ($data ['perpetual_checkbox'] == '1') {
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$end_recurrence_date12 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$end_recurrence_date12 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET end_recurrence_date = '" . $end_recurrence_date12 . "', end_perpetual_task = '2' where id= '" . $result ['id'] . "' ";
			$this->db->query ( $sql2 );
			
			$end_perpetual_task = '2';
			
			$sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $result ['id'] . "',forms_id = '0',notes_id = '0',  status = 'normal', tags_id = '" . $result ['emp_tag_id'] . "', parent_id = '0' ";
			$this->db->query ( $sql );
		}
		
		if ($result ['deleted_alert'] == '1') {
			$this->load->model ( 'api/emailapi' );
			$this->load->model ( 'api/smsapi' );
			
			if ($result ['user_roles'] != null && $result ['user_roles'] != "") {
				$user_roles1 = explode ( ',', $result ['user_roles'] );
				
				$this->load->model ( 'user/user_group' );
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				foreach ( $user_roles1 as $user_role ) {
					
					$urole = array ();
					$urole ['user_group_id'] = $user_role;
					$tusers = $this->model_user_user->getUsers ( $urole );
					
					if ($tusers) {
						foreach ( $tusers as $tuser ) {
							
							if ($tuser ['phone_number']) {
								if ($result ['completion_alert_type_sms'] == '1') {
									$message = "TASK ALERT | Task was marked DELETED " . date ( 'h:i A', strtotime ( $result ['task_time'] ) ) . "...\n";
									$message .= "Task Type: " . $result ['tasktype'] . "\n";
									
									if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									
									if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
										
										$transport_tags1 = explode ( ',', $result ['transport_tags'] );
										
										$transport_tags = '';
										foreach ( $transport_tags1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$transport_tags .= $emp_tag_id . ', ';
											}
										}
										
										$message .= "Client Name: " . $transport_tags . "\n";
									}
									$message .= "Description: " . substr ( $result ['description'], 0, 150 ) . ((strlen ( $result ['description'] ) > 150) ? '..' : '') . "\n";
									// $message .= "Description:
									// ".$result['description']."\n";
									
									$sdata = array ();
									$sdata ['message'] = $message;
									$sdata ['phone_number'] = $tuser ['phone_number'];
									$sdata ['facilities_id'] = $facilities_id;
									// $sdata['is_task'] = 1;
									$response = $this->model_api_smsapi->sendsms ( $sdata );
								}
							}
							
							if ($tuser ['email']) {
								if ($result ['completion_alert_type_email'] == '1') {
									
									$message33 = "";
									$messagebody = 'TASK ALERT | Task was marked DELETED';
									$messagebody1 = 'The following task has been marked deleted.';
									$message33 .= $this->completeemailtemplate ( $result, $result ['date_added'], $result ['task_time'], $messagebody, $messagebody1 );
									
									// var_dump($message33);
									// die;
									
									$edata = array ();
									$edata ['message'] = $message33;
									$edata ['subject'] = 'TASK ALERT | Task was marked DELETED';
									$edata ['user_email'] = $tuser ['email'];
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata );
								}
							}
						}
					}
				}
			}
			
			if ($result ['userids'] != null && $result ['userids'] != "") {
				$userids1 = explode ( ',', $result ['userids'] );
				
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				foreach ( $userids1 as $userid ) {
					
					$user_info = $this->model_user_user->getUserbyupdate ( $userid );
					
					if ($user_info) {
						
						if ($user_info ['phone_number']) {
							if ($result ['completion_alert_type_sms'] == '1') {
								$message = "TASK ALERT | Task was marked DELETED " . date ( 'h:i A', strtotime ( $result ['task_time'] ) ) . "...\n";
								$message .= "Task Type: " . $result ['tasktype'] . "\n";
								
								if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
									$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$message .= "Client Name: " . $emp_tag_id . "\n";
									}
								}
								
								if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
									$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$message .= "Client Name: " . $emp_tag_id . "\n";
									}
								}
								if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
									$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$message .= "Client Name: " . $emp_tag_id . "\n";
									}
								}
								if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
									
									$transport_tags1 = explode ( ',', $result ['transport_tags'] );
									
									$transport_tags = '';
									foreach ( $transport_tags1 as $tag1 ) {
										$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$transport_tags .= $emp_tag_id . ', ';
										}
									}
									
									$message .= "Client Name: " . $transport_tags . "\n";
								}
								$message .= "Description: " . substr ( $result ['description'], 0, 150 ) . ((strlen ( $result ['description'] ) > 150) ? '..' : '') . "\n";
								// $message .= "Description:
								// ".$result['description']."\n";
								
								$sdata = array ();
								$sdata ['message'] = $message;
								$sdata ['phone_number'] = $user_info ['phone_number'];
								$sdata ['facilities_id'] = $facilities_id;
								// $sdata['is_task'] = 1;
								
								$response = $this->model_api_smsapi->sendsms ( $sdata );
							}
						}
						
						if ($user_info ['email']) {
							if ($result ['completion_alert_type_email'] == '1') {
								
								$message33 = "";
								$messagebody = 'TASK ALERT | Task was marked DELETED';
								$messagebody1 = 'The following task has been marked deleted.';
								$message33 .= $this->completeemailtemplate ( $result, $result ['date_added'], $result ['task_time'], $messagebody, $messagebody1 );
								
								// var_dump($message33);
								// die;
								
								$edata = array ();
								$edata ['message'] = $message33;
								$edata ['subject'] = 'TASK ALERT | Task was marked DELETED';
								$edata ['user_email'] = $user_info ['email'];
								
								$email_status = $this->model_api_emailapi->sendmail ( $edata );
							}
						}
					}
				}
			}
		}
		
		if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
			date_default_timezone_set ( $data ['facilitytimezone'] );
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$noteDateTime = date ( 'H:i:s', strtotime ( 'now' ) );
			$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
			
			// date_default_timezone_set($timezone_name);
			// $noteTime = date('h:i:s', strtotime('now'));
		} else {
			$timezone_name = $this->customer->isTimezone ();
			
			date_default_timezone_set ( $timezone_name );
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$noteDateTime = date ( 'H:i:s', strtotime ( 'now' ) );
			$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
		}
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		/*
		 * if($this->db->escape($data['comments'])!=NULL &&
		 * $this->db->escape($data['comments'])!="" ){
		 * $description = $this->db->escape($result['description']).' ,
		 * '.$this->db->escape($data['comments']);
		 * } else{
		 * $description = $this->db->escape($result['description']).'
		 * '.$this->db->escape($data['comments']);
		 * }
		 */
		
		$this->load->model ( 'createtask/createtask' );
		$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $facilities_id );
		$tasktype_id = $tasktype_info ['task_id'];
		$tasktypetype = $tasktype_info ['type'];
		
		if ($data ['customlistvalues_id']) {
			
			$this->load->model ( 'notes/notes' );
			$custom_info = $this->model_notes_notes->getcustomlistvalue ( $data ['customlistvalues_id'] );
			
			$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
			
			$customlistvalues_id = $data ['customlistvalues_id'];
		}
		
		if ($data ['customlistvalues_ids']) {
			
			$this->load->model ( 'notes/notes' );
			
			foreach ( $data ['customlistvalues_ids'] as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				$customlistvalues_name1 = $custom_info ['customlistvalues_name'];
				
				$customlistvalues_name .= ' | ' . $customlistvalues_name1;
			}
			
			$customlistvalues_id = implode ( ',', $data ['customlistvalues_ids'] );
		}
		
		if ($requires_approval == 'decline') {
			$sql = "SELECT tasktype from `" . DB_PREFIX . "createtask` where approval_taskid = '" . $result ['id'] . "'  ";
			$t_info = $this->db->query ( $sql );
			
			$tasktype_r = $t_info->row ['tasktype'];
		}
		
		if ($tasktypetype == '5') {
			
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
			$this->load->model ( 'setting/tags' );
			$transport_tags = '';
			foreach ( $transport_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ':' . $tags_info ['emp_first_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$transport_tags .= $emp_tag_id . ', ';
				}
			}
			
			$description = '';
			$description .= ' | ';
			
			if ($requires_approval == 'decline') {
				$description .= $tasktype_r . " TASK | DECLINED ";
				$task_type = '6';
			} else {
				
				if ($result ['is_transport'] == '1') {
					
					if ($result ['parent_id'] > 0) {
						$this->load->model ( 'notes/notes' );
						$notes_info = $this->model_notes_notes->getNote ( $result ['parent_id'] );
						
						$start_date = new DateTime ( $notes_info ['date_added'] );
						$since_start = $start_date->diff ( new DateTime ( $noteDate ) );
						
						$caltime = "";
						$caltime1 = "";
						
						if ($since_start->y > 0) {
							$caltime .= $since_start->y . ' years ';
						}
						if ($since_start->m > 0) {
							$caltime .= $since_start->m . ' months ';
						}
						if ($since_start->d > 0) {
							$caltime .= $since_start->d . ' days ';
						}
						if ($since_start->h > 0) {
							$caltime .= $since_start->h . ' hours ';
						}
						if ($since_start->i > 0) {
							$caltime .= $since_start->i . ' minutes ';
						}
						
						$caltime1 = ' | Total Travel Time ' . $caltime;
					}
					
					$description .= ' Travel completed at | ' . $result ['dropoff_locations_address'];
					$description .= ' started at | ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . $caltime1;
					$description .= ' for | ' . $transport_tags;
				} else {
					$description .= ' Travel Started from | ' . $result ['pickup_locations_address'];
					$description .= ' at | ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) );
					$description .= ' for the following | ' . $transport_tags;
				}
				
				$task_type = '3';
				$description .= ' | ' . $result ['description'];
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
		} elseif ($tasktypetype == '4') {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
			$this->load->model ( 'setting/tags' );
			$medication_tags = '';
			foreach ( $medication_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ':' . $tags_info ['emp_first_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$medication_tags .= $emp_tag_id . ', ';
				}
			}
			
			$description = '';
			$description .= ' | ';
			
			if ($requires_approval == 'decline') {
				$description .= $tasktype_r . " TASK | DECLINED ";
				$task_type = '6';
			} else {
				
				$description .= ' Deleted | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ';
				$description .= ' Medication given to | ';
				$description .= ' ' . $medication_tags;
				// $description .= ' the following details were noted: | ';
				
				$description .= ' ' . $result ['description'];
				$task_type = '2';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
			$description .= ' | ';
		} elseif ($tasktype_id == '6') {
			
			$description = '';
			// $description .= ' | ';
			
			// $description .= ' Bed Check for | '.date('h:i A',
			// strtotime($notetasktime)) .' Completed.';
			// $description .= ' The following details were noted: ';
			if ($requires_approval == 'decline') {
				$description .= $tasktype_r . " TASK | DECLINED ";
				$task_type = '6';
			} else {
				
				$description .= ' ' . $result ['description'];
				$task_type = '1';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
		} elseif ($tasktypetype == '3') {
			
			$description = '';
			
			if ($requires_approval == 'decline') {
				$description .= $tasktype_r . " TASK | DECLINED ";
				$task_type = '6';
			} else {
				
				$description .= ' | STARTED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' | ';
				
				$description .= ' ' . $result ['description'];
				$task_type = '4';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
		} else {
			
			if ($requires_approval == 'decline') {
				$description = "";
				$description .= $tasktype_r . " TASK | DECLINED ";
				$task_type = '6';
				
				if ($customlistvalues_name) {
					$description1 = '  ' . $customlistvalues_name;
				}
				
				if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
					$description .= $description1 . ' , ' . $data ['comments'];
				} else {
					$description .= $description1 . ' ' . $data ['comments'];
				}
			} else {
				
				if ($customlistvalues_name) {
					$description1 = '  ' . $customlistvalues_name;
				}
				
				if ($result ['linked_id'] > 0) {
					
					if ($tasktype_info ['client_required'] == '0') {
						$description = $result ['description'] . $description1 . ' , ' . $data ['comments'];
					} else {
						$description = $description1 . ' ' . $data ['comments'];
					}
				} else {
					
					if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
						$description = $result ['description'] . $description1 . ' , ' . $data ['comments'];
					} else {
						$description = $result ['description'] . $description1 . ' ' . $data ['comments'];
					}
				}
				
				/*
				 * if ($this->db->escape($data['comments']) != NULL && $this->db->escape($data['comments']) != "") {
				 * $description = $result['description'] . $description1 . ' , ' . $data['comments'];
				 * } else {
				 * $description = $result['description'] . $description1 . ' ' . $data['comments'];
				 * }
				 */
			}
		}
		
		$date_added1 = date ( 'Y-m-d', strtotime ( $result ['date_added'] ) );
		$date_added2 = $date_added1 . ' ' . $noteDateTime;
		$date_added3 = date ( 'Y-m-d H:i:s', strtotime ( $date_added2 ) );
		
		if ($result ['enable_requires_approval'] == '2') {
			$date_added3 = $noteDate;
		} else {
			$date_added3 = $date_added3;
		}
		
		
		if ($data ['perpetual_checkbox'] == '1') {
			$end_task = 1;
		}else{
			$end_task = $result ['end_task'];
		}
		
		$this->load->model ( 'user/user' );
		$user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes` SET 
			
			facilities_id = '" . $facilities_id . "',
			highlighter_id ='0',
			status= '1',	
			user_id = '',
			signature= '',
			signature_image= '',
			notes_pin = '',
			
			notes_description = '" . $this->db->escape ( $description ) . "',
			notetime = '" . $noteDateTime . "',
			task_time = '" . $notetasktime . "',
			
			date_added = '" . $this->db->escape ( $date_added3 ) . "',
			note_date = '" . $noteDate . "',
			
			strike_user_id = '" . $this->db->escape ( $user_info ['username'] ) . "',
			strike_note_type = '" . $this->db->escape ( $data ['strike_note_type'] ) . "',
			notes_type = '" . $this->db->escape ( $data ['notes_type'] ) . "',
			strike_date_added= '" . $noteDate . "',
			strike_signature= '" . $data ['imgOutput'] . "',
			strike_signature_image= '" . $fileName . "',
			strike_pin= '" . $this->db->escape ( $data ['notes_pin'] ) . "',
			phone_device_id= '" . $this->db->escape ( $data ['phone_device_id'] ) . "',
			text_color_cut = '1',
			taskadded = '1',
			snooze_dismiss = '2',
			form_snooze_dismiss = '2',
			assign_to= '" . $this->db->escape ( $result ['assign_to'] ) . "',
			tasktype= '" . $tasktype_id . "',
			update_date= '" . $update_date . "'
			, notes_conut='0',
			task_id= '" . $result ['id'] . "',
			task_type= '" . $task_type . "',
			end_perpetual_task= '" . $end_perpetual_task . "',
			customlistvalues_id = '" . $customlistvalues_id . "',
			is_android = '" . $data ['is_android'] . "',
			recurrence= '" . $this->db->escape ( $result ['recurrence'] ) . "',
			task_date = '" . $this->db->escape ( $result ['date_added'] ) . "',
			task_group_by = '" . $this->db->escape ( $result ['task_group_by'] ) . "',
			end_task = '" . $this->db->escape ( $end_task ) . "',
			linked_id = '" . $this->db->escape ( $result ['linked_id'] ) . "',
			parent_facilities_id = '" . $this->db->escape ( $result ['target_facilities_id'] ) . "',
			original_task_time = '" . $this->db->escape ( $result ['original_task_time'] ) . "',
			is_approval_required_forms_id = '" . $this->db->escape ( $result ['is_approval_required_forms_id'] ) . "',
			task_form_id = '" . $this->db->escape ( $result ['task_form_id'] ) . "',
			parent_id = '" . $this->db->escape ( $result ['parent_id'] ) . "'
			";
		
		$this->db->query ( $sql );
		
		$notes_id = $this->db->getLastId ();
		
		if ($data ['perpetual_checkbox'] == '1') {
			if ($result ['linked_id'] == '0') {
				$sql122f = "UPDATE `" . DB_PREFIX . "notes` SET linked_id = '" . $notes_id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122f );
			}
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_id) {
			$unique_id = $facility ['customer_key'];
			$sql121 = "UPDATE `" . DB_PREFIX . "notes` SET unique_id = '" . $this->db->escape ( $unique_id ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql121 );
		}
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		if ($requires_approval == 'decline') {
			
			$sql1 = "UPDATE `" . DB_PREFIX . "createtask` set parent_id = '" . $notes_id . "', enable_requires_approval = '0' where approval_taskid = '" . $result ['id'] . "' ";
			$this->db->query ( $sql1 );
			
			$sql1n = "UPDATE `" . DB_PREFIX . "notes_by_approval_task` set parent_id = '" . $notes_id . "', notes_id = '" . $notes_id . "', enable_requires_approval = '0', status = '1' where approval_taskid = '" . $result ['id'] . "' ";
			$this->db->query ( $sql1n );
			
			if ($result ['is_approval_required_forms_id'] > 0) {
				$sql1f = "UPDATE `" . DB_PREFIX . "forms` set is_final = '1' where forms_id = '" . $result ['is_approval_required_forms_id'] . "' ";
				$this->db->query ( $sql1f );
			}
		}
		
		if ($result ['transport_tags']) {
			$this->load->model ( 'createtask/createtask' );
			$travelWaypoints = $this->model_createtask_createtask->gettravelWaypoints ( $result ['id'] );
			if ($travelWaypoints != null && $travelWaypoints != "") {
				foreach ( $travelWaypoints as $travelWaypoint ) {
					$sql2233 = "INSERT INTO `" . DB_PREFIX . "notes_createtask_by_transport` SET id = '" . $result ['id'] . "', locations_address = '" . $this->db->escape ( $travelWaypoint ['locations_address'] ) . "', latitude = '" . $this->db->escape ( $travelWaypoint ['latitude'] ) . "', longitude = '" . $this->db->escape ( $travelWaypoint ['longitude'] ) . "', place_id = '" . $this->db->escape ( $travelWaypoint ['place_id'] ) . "', notes_id = '" . $this->db->escape ( $notes_id ) . "' ";
					$this->db->query ( $sql2233 );
				}
			}
		}
		
		/**
		 * ***************************************
		 */
		
		if ($tasktype_info ['enable_location'] == '1') {
			
			if ($tasktype_info ['task_id'] == '10') {
				$tage_id1 = $result ['transport_tags'];
			} elseif ($tasktype_info ['task_id'] == '2') {
				$tage_id1 = $result ['medication_tags'];
			} elseif ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
				$tage_id1 = $result ['emp_tag_id'];
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $tage_id1 );
			$tags_id = $taginfo ['tags_id'];
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				$google_url = "https://www.google.com/maps/dir/" . $result ['pickup_locations_address'] . '/' . $result ['dropoff_locations_address'];
			}
			
			if (($data ['current_lat'] != null && $data ['current_lat'] != "") && ($data ['current_log'] != null && $data ['current_log'] != "")) {
				
				if ($result ['is_transport'] == '0') {
					$latitude = $result ['pickup_locations_latitude'];
					$longitude = $result ['pickup_locations_longitude'];
				} else {
					$latitude = $result ['dropoff_locations_latitude'];
					$longitude = $result ['dropoff_locations_longitude'];
				}
				
				$distanced = $this->distance ( $data ['current_lat'], $data ['current_log'], $latitude, $longitude, "K" );
				
				if (($distanced >= '5')) {
					$keyword_id = $tasktype_info ['relation_keyword_id'];
				}
				
				if ($data ['current_locations_address'] != null && $data ['current_locations_address'] != "") {
					$current_locations_address = $data ['current_locations_address'];
				} else {
					
					$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $data ['current_lat'] ) . ',' . trim ( $data ['current_log'] ) . '&sensor=false';
					$json = @file_get_contents ( $url );
					$ldata = json_decode ( $json );
					$status = $ldata->status;
					if ($status == "OK") {
						$current_locations_address = $ldata->results [0]->formatted_address;
					}
				}
				
				$current_google_url = "https://www.google.com/maps/place/" . $current_locations_address . '/' . $data ['current_lat'] . ',' . $data ['current_log'];
			}
			
			$this->load->model ( 'createtask/createtask' );
			$waypoints = $this->model_createtask_createtask->gettravelWaypoints ( $result ['id'] );
			
			if ($waypoints != "" && $waypoints != null) {
				$waypoint_google_url1 = "";
				foreach ( $waypoints as $waypoint ) {
					$waypoint_google_url1 .= '/' . $waypoint ['locations_address'];
				}
				
				$waypoint_google_url = "https://www.google.com/maps/dir/" . $result ['pickup_locations_address'] . $waypoint_google_url1 . '/' . $result ['dropoff_locations_address'];
			}
			
			$sqll = "INSERT INTO `" . DB_PREFIX . "notes_by_travel_task` SET 
			facilities_id = '" . $facilities_id . "',
			notes_id = '" . $notes_id . "',
			type = '" . $tasktype_info ['task_id'] . "',
			travel_state = '" . $this->db->escape ( $result ['is_transport'] ) . "',
			pickup_locations_address = '" . $this->db->escape ( $result ['pickup_locations_address'] ) . "',
			pickup_locations_latitude = '" . $this->db->escape ( $result ['pickup_locations_latitude'] ) . "',
			pickup_locations_longitude = '" . $this->db->escape ( $result ['pickup_locations_longitude'] ) . "',
			
			dropoff_locations_address = '" . $this->db->escape ( $result ['dropoff_locations_address'] ) . "',
			dropoff_locations_latitude= '" . $this->db->escape ( $result ['dropoff_locations_latitude'] ) . "',
			dropoff_locations_longitude = '" . $this->db->escape ( $result ['dropoff_locations_longitude'] ) . "',
			
			google_url = '" . $this->db->escape ( $google_url ) . "',
			
			current_locations_address = '" . $this->db->escape ( $current_locations_address ) . "',
			current_locations_latitude = '" . $this->db->escape ( $data ['current_lat'] ) . "',
			current_locations_longitude = '" . $this->db->escape ( $data ['current_log'] ) . "',
			
			current_google_url = '" . $this->db->escape ( $current_google_url ) . "',
			
			location_tracking_url = '" . $this->db->escape ( $data ['location_tracking_url'] ) . "',
			location_tracking_route = '" . $this->db->escape ( $data ['location_tracking_route'] ) . "',
			location_tracking_time_start = '" . $this->db->escape ( $data ['location_tracking_time_start'] ) . "',
			location_tracking_time_end = '" . $this->db->escape ( $data ['location_tracking_time_end'] ) . "',
			google_map_image_url = '" . $this->db->escape ( $data ['google_map_image_url'] ) . "',
			
			date_added = '" . $this->db->escape ( $date_added3 ) . "',
			tags_id = '" . $this->db->escape ( $tags_id ) . "',
			waypoint_google_url = '" . $this->db->escape ( $waypoint_google_url ) . "',
			unique_id = '" . $this->db->escape ( $unique_id ) . "',
			keyword_id = '" . $this->db->escape ( $keyword_id ) . "'
			";
			
			$this->db->query ( $sqll );
			
			$travel_task_id = $this->db->getLastId ();
			
			$sqlc = "UPDATE `" . DB_PREFIX . "notes_by_travel_task_coordinates` SET travel_task_id = '" . $this->db->escape ( $travel_task_id ) . "', notes_id = '" . $notes_id . "' where task_id = '" . ( int ) $result ['id'] . "' ";
			$this->db->query ( $sqlc );
			
			$barray1 = array ();
			$barray1 ['travel_task_id'] = $travel_task_id;
			$barray1 ['is_transport'] = $result ['is_transport'];
			$barray1 ['pickup_locations_address'] = $result ['pickup_locations_address'];
			$barray1 ['dropoff_locations_address'] = $result ['dropoff_locations_address'];
			$barray1 ['google_url'] = $google_url;
			$barray1 ['current_locations_address'] = $current_locations_address;
			$barray1 ['current_google_url'] = $current_google_url;
			$barray1 ['facilities_id'] = $facilities_id;
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'insertDatadetailsnotes_by_travel_task', $barray1, 'query' );
		}
		
		/**
		 * ****************************************
		 */
		// die;
		
		if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
			
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
		}
		
		if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "0") {
			$this->load->model ( 'notes/notes' );
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $result ['visitation_tag_id'] );
			
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
		}
		
		if ($tasktypetype == '5') {
			
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'notes/notes' );
			
			$transport_tags = '';
			foreach ( $transport_tags1 as $tag1 ) {
				$taginfo = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
					date_default_timezone_set ( $data ['facilitytimezone'] );
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				} else {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				}
				
				$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
			}
		}
		
		$ctime = time ();
		$stime = date ( 'H:i:s', strtotime ( $ctime ) );
		
		/*$sqlshift = "SELECT  * FROM `" . DB_PREFIX . "shift` where shift_starttime > '" . $stime . "' and shift_endtime < '" . $stime . "' ";
		$shifts = $this->db->query ( $sqlshift );
		
		if (! empty ( $shifts->row ['shift_id'] )) {
			$id = $shifts->row ['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $updateshift );
		}*/
		
		$this->load->model ( 'notes/notes' );
		$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $facilities_id );
		if(!empty($shift_info['shift_id'])){
			$id = $shift_info['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . (int) $notes_id . "' ";
			$this->db->query($updateshift);
		}
		
		/*
		 * $this->load->model('createtask/createtask');
		 * $tasktype_info =
		 * $this->model_createtask_createtask->gettasktyperowByName($result['tasktype']);
		 * $tasktype_id = $tasktype_info['task_id'];
		 *
		 * if($tasktype_id == '11'){
		 * if($result['task_form_id'] != null && $result['task_form_id'] !=
		 * "0"){
		 * $this->load->model('setting/bedchecktaskform');
		 * $this->load->model('setting/tags');
		 *
		 * $bedcheckinfos =
		 * $this->model_setting_bedchecktaskform->getruleModule($result['task_form_id']);
		 *
		 * if($bedcheckinfos['bctf_module'] != null &&
		 * $bedcheckinfos['bctf_module'] != ""){
		 * foreach($bedcheckinfos['bctf_module'] as $bedcheckinfo){
		 * $tag_info =
		 * $this->model_setting_tags->getTagsbyNotescurrencyid($bedcheckinfo['locations_id']);
		 * //var_dump($tag_info);
		 *
		 * $this->load->model('notes/notes');
		 * if($data['facilitytimezone'] != null && $data['facilitytimezone'] !=
		 * ""){
		 * date_default_timezone_set($data['facilitytimezone']);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }else{
		 * $timezone_name = $this->customer->isTimezone();
		 * date_default_timezone_set($timezone_name);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }
		 *
		 * $this->model_notes_notes->updateNotesTag($tag_info['emp_tag_id'],
		 * $notes_id,$tag_info['tags_id'], $update_date);
		 * }
		 * }
		 *
		 * }
		 *
		 * }
		 */
		
		// if($result['recurrence'] == 'hourly'){
		
		if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
			date_default_timezone_set ( $data ['facilitytimezone'] );
			$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		} else {
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		}
		
		$details1 = "SELECT parent_id FROM `" . DB_PREFIX . "createtask` where parent_id = '0' and recurrence = '" . $result ['recurrence'] . "' and facilityId = '" . $facilities_id . "' and description = '" . $this->db->escape ( $result ['description'] ) . "' ";
		$queryt = $this->db->query ( $details1 );
		
		if ($queryt->row ['parent_id'] == '0' && $queryt->row ['parent_id'] != null) {
			$sql213 = "update `" . DB_PREFIX . "createtask` set parent_id = '" . $notes_id . "' where recurrence = '" . $result ['recurrence'] . "' and facilityId = '" . $facilities_id . "' and description = '" . $this->db->escape ( $result ['description'] ) . "' ";
			$this->db->query ( $sql213 );
		}
		
		// }
		
		$details = "SELECT notes_id, parent_id FROM `" . DB_PREFIX . "notes` where notes_id = '" . $result ['parent_id'] . "'  ";
		$query = $this->db->query ( $details );
		// echo "<hr>";
		
		if ($query->row) {
			if ($query->row ['parent_id'] < 0) {
				$parent_id = $query->row ['parent_id'];
			} else {
				$parent_id = $query->row ['notes_id'];
			}
		} else {
			
			$parent_id = $notes_id;
			
			// $sql2 = "update `" . DB_PREFIX . "notes` set parent_id =
			// '".$parent_id."' where notes_id='".$parent_id."' ";
			// $this->db->query($sql2);
		}
		
		$sql21 = "update `" . DB_PREFIX . "notes` set parent_id = '" . $parent_id . "', notes_conut ='0' where notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sql21 );
		
		/*
		 * $details = "SELECT notes_id, parent_id FROM `" . DB_PREFIX . "notes`
		 * where notes_id = '".$result['parent_id']."' ";
		 * $query = $this->db->query($details);
		 *
		 * if($query->row){
		 * if ($query->row['parent_id'] < 0 ) {
		 * $parent_id = $query->row['parent_id'];
		 * }else{
		 * $parent_id = $query->row['notes_id'];
		 * }
		 * }else{
		 * $parent_id = $notes_id;
		 *
		 * echo $sql2 = "update `" . DB_PREFIX . "notes` set parent_id =
		 * '".$parent_id."' where notes_id='".$parent_id."' ";
		 * $this->db->query($sql2);
		 * }
		 */
		
		$barray = array ();
		
		$barray ['notes_id'] = $notes_id;
		$barray ['task_id'] = $result ['id'];
		$barray ['date_added'] = $date_added3;
		$barray ['note_date'] = $noteDate;
		
		$barray ['strike_user_id'] = $data ['user_id'];
		$barray ['strike_note_type'] = $data ['strike_note_type'];
		$barray ['notes_type'] = $data ['notes_type'];
		$barray ['strike_pin'] = $data ['notes_pin'];
		$barray ['phone_device_id'] = $data ['phone_device_id'];
		$barray ['is_android'] = $data ['is_android'];
		
		$barray ['task_type'] = $task_type;
		$barray ['notetime'] = $noteDateTime;
		$barray ['task_time'] = $notetasktime;
		$barray ['notes_description'] = $description;
		$barray ['facilities_id'] = $facilities_id;
		$barray ['customlistvalues_id'] = $customlistvalues_id;
		$barray ['recurrence'] = $result ['recurrence'];
		$barray ['task_date'] = $result ['date_added'];
		$barray ['task_group_by'] = $result ['task_group_by'];
		$barray ['parent_id'] = $result ['parent_id'];
		$barray ['assign_to'] = $result ['assign_to'];
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'insertDatadetails', $barray, 'query' );
		
		if ($data ['perpetual_checkbox'] != '1') {
			$this->checkNotification ( $result, $facilities_id, $parent_id );
		}
		
		if ($this->config->get ( 'config_realtime_data' ) == '1') {
			$this->load->model ( 'api/realtime' );
			$realdata = array ();
			$realdata ['facilities_id'] = $facilities_id;
			$realdata ['notes_id'] = $notes_id;
			$this->model_api_realtime->addrealtime ( $realdata );
		}
		
		return $notes_id;
	}
	public function getTaskdetails($facilities_id) {
		$this->load->model ( 'api/permision' );
		$current_permission = $this->model_api_permision->getpermision ( $facilities_id );
		$sqln = "";
		if ($current_permission == "notes_only" || $current_permission == "note_plus") {
			$sqln .= " and task_id not in (8,10,11,24,25,2)";
		}
		
		if (ALLTASKTYPE == '1') {
			$current_customer = $this->model_api_permision->getcustomerid ( $facilities_id );
			if ($current_customer != null && $current_customer != "") {
				$sqln .= " and customer_key = '" . $current_customer . "' ";
			}
		}
		
		$details = "SELECT * FROM `" . DB_PREFIX . "tasktype` where status = '1' " . $sqln . " order by sort_order ASC ";
		
		/*
		 * $cacheid = 'getTaskdetails';
		 *
		 * $this->load->model('api/cache');
		 * $rtasktypes = $this->model_api_cache->getcache($cacheid);
		 *
		 * if (!$rtasktypes) {
		 * $query = $this->db->query($details);
		 * $rtasktypes = $query->rows;
		 * $this->model_api_cache->setcache($cacheid,$rtasktypes);
		 * }
		 *
		 * return $rtasktypes;
		 */
		$query = $this->db->query ( $details );
		return $query->rows;
	}
	public function getTaskintervals($facilities_id) {
		$this->load->model ( 'api/permision' );
		if (ALLTASKTYPE == '1') {
			$current_customer = $this->model_api_permision->getcustomerid ( $facilities_id );
			$sqln = "";
			if ($current_customer != null && $current_customer != "") {
				$sqln .= " and customer_key = '" . $current_customer . "' ";
			}
		}
		
		$intervals = "SELECT interval_id,interval_name,interval_value FROM `" . DB_PREFIX . "interval` where status = '1' " . $sqln . " order by sort_order ASC ";
		
		// echo $intervals;
		
		/*
		 * $cacheid = 'getTaskintervals';
		 *
		 * $this->load->model('api/cache');
		 * $rtaskintervals = $this->model_api_cache->getcache($cacheid);
		 *
		 * if (!$rtaskintervals) {
		 * $query = $this->db->query($intervals);
		 * $rtaskintervals = $query->rows;
		 *
		 * $this->model_api_cache->setcache($cacheid,$rtaskintervals);
		 * }
		 * return $rtaskintervals;
		 */
		
		$query = $this->db->query ( $intervals );
		return $query->rows;
	}
	public function inserttask($result, $data, $facilities_id, $requires_approval) {
		// var_dump($data);
		// die;
		// if($data['perpetual_checkbox'] != NULL && $data['perpetual_checkbox'] != '' ){
		$sql2 = "update `" . DB_PREFIX . "createtask` set task_action = '" . $data ['perpetual_checkbox'] . "' where id ='" . $result ['id'] . "'";
		$this->db->query ( $sql2 );
		// }
		
		if ($data ['perpetual_checkbox'] == '4') {
			if ($result ['recurrence'] == 'hourly') {
				$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET recurnce_hrly = '" . $data ['acttion_interval_id'] . "' where id= '" . $result ['id'] . "' ";
				$this->db->query ( $sql2 );
			} else {
				$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET recurnce_hrly_perpetual = '" . $data ['acttion_interval_id'] . "' where id = '" . $result ['id'] . "' ";
				$this->db->query ( $sql2 );
			}
		}
		
		/* if($data['hiddenData'] == '2'){ */
		if ($data ['perpetual_checkbox'] == '1') {
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$end_recurrence_date12 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$end_recurrence_date12 = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET end_recurrence_date = '" . $end_recurrence_date12 . "', end_perpetual_task = '2' where id= '" . $result ['id'] . "' ";
			$this->db->query ( $sql2 );
			
			$end_perpetual_task = '2';
			
			$sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $result ['id'] . "',forms_id = '0',notes_id = '0',  status = 'normal', tags_id = '" . $result ['emp_tag_id'] . "', parent_id = '0' ";
			$this->db->query ( $sql );
		}
		
		if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
			
			date_default_timezone_set ( $data ['facilitytimezone'] );
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$noteDateTime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			if ($data ['perpetual_checkbox'] == '2') {
				$notetasktime = date ( 'H:i:s', strtotime ( $data ['pause_time'] ) );
			} else {
				$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
			}
		} else {
			$timezone_name = $this->customer->isTimezone ();
			
			date_default_timezone_set ( $timezone_name );
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$noteDateTime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			if ($data ['perpetual_checkbox'] == '2') {
				$notetasktime = date ( 'H:i:s', strtotime ( $data ['pause_time'] ) );
			} else {
				
				$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
			}
		}
		
		if ($data ['perpetual_checkbox'] == '2') {
			
			$res = explode ( "-", $data ['pause_date'] );
			$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
			$pause_date = date ( 'Y-m-d', strtotime ( $createdate1 ) );
			
			$pause_time = date ( 'H:i:s', strtotime ( $data ['pause_time'] ) );
			$notetasktime = date ( 'H:i:s', strtotime ( 'now' ) );
			
			$sql = "update `" . DB_PREFIX . "createtask` set is_pause = '1', task_time = '" . $noteDateTime . "', pause_time = '" . $pause_time . "', pause_date = '" . $pause_date . "' where id ='" . $result ['id'] . "'";
			
			$this->db->query ( $sql );
		}
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->load->model ( 'createtask/createtask' );
		$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $facilities_id );
		
		$tasktype_id = $tasktype_info ['task_id'];
		$tasktypetype = $tasktype_info ['type'];
		
		if ($tasktype_info ['custom_completion_rule'] == '1') {
			$addTime = $tasktype_info ['config_task_after_complete'];
			$end_time = $tasktype_info ['config_task_complete_max'];
		} else {
			$addTime = $this->config->get ( 'config_task_after_complete' );
			$end_time = $this->config->get ( 'config_task_complete_max' );
		}
		
		// var_dump($addTime);
		// var_dump($end_time);
		
		if ($result ['recurrence'] == 'hourly') {
			if ($result ['recurnce_hrly'] >= $addTime) {
				
				$addTime22 = $addTime;
			} else {
				$addTime22 = $result ['recurnce_hrly'];
			}
		} else if ($result ['recurrence'] == 'Perpetual') {
			
			if ($result ['recurnce_hrly_perpetual'] >= $addTime) {
				
				$addTime22 = $addTime;
			} else {
				$addTime22 = $result ['recurnce_hrly_perpetual'];
			}
		} else {
			$addTime22 = $addTime;
		}
		
		// var_dump($addTime22);
		
		/*
		 * if($config_task_after_complete == '5min'){
		 * $addTime = '5';
		 * }else
		 * if($config_task_after_complete == '10min'){
		 * $addTime = '10';
		 * }
		 * else
		 * if($config_task_after_complete == '15min'){
		 * $addTime = '15';
		 * }else
		 * if($config_task_after_complete == '20min'){
		 * $addTime = '20';
		 * }else
		 * if($config_task_after_complete == '25min'){
		 * $addTime = '25';
		 * }else
		 * if($config_task_after_complete == '30min'){
		 * $addTime = '30';
		 * }else
		 * if($config_task_after_complete == '45min'){
		 * $addTime = '45';
		 * }
		 */
		
		$taskstarttime = date ( 'H:i:s', strtotime ( ' -' . $addTime22 . ' minutes', strtotime ( $result ['task_time'] ) ) );
		$taskstarttimeafter = date ( 'H:i:s', strtotime ( ' +' . $end_time . ' minutes', strtotime ( $result ['task_time'] ) ) );
		
		if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
			date_default_timezone_set ( $data ['facilitytimezone'] );
		} else {
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$timezone_name = $this->customer->isTimezone ();
			}
			
			date_default_timezone_set ( $timezone_name );
		}
		$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		// var_dump($taskstarttime);
		// var_dump($taskstarttimeafter);
		
		// var_dump($currenttime);
		
		if (($currenttime > $taskstarttime) && ($currenttime < $taskstarttimeafter)) {
			$taskDuration = '3';
			
			if ($result ['completed_late_alert'] == '1') {
				
				$this->load->model ( 'api/emailapi' );
				$this->load->model ( 'api/smsapi' );
				
				if ($result ['user_roles'] != null && $result ['user_roles'] != "") {
					$user_roles1 = explode ( ',', $result ['user_roles'] );
					
					$this->load->model ( 'user/user_group' );
					$this->load->model ( 'user/user' );
					$this->load->model ( 'setting/tags' );
					
					foreach ( $user_roles1 as $user_role ) {
						
						$urole = array ();
						$urole ['user_group_id'] = $user_role;
						$tusers = $this->model_user_user->getUsers ( $urole );
						
						if ($tusers) {
							foreach ( $tusers as $tuser ) {
								
								if ($tuser ['phone_number']) {
									if ($result ['completion_alert_type_sms'] == '1') {
										$message = "TASK ALERT | Task was marked LATE " . date ( 'h:i A', strtotime ( $result ['task_time'] ) ) . "...\n";
										$message .= "Task Type: " . $result ['tasktype'] . "\n";
										if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
											$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$message .= "Client Name: " . $emp_tag_id . "\n";
											}
										}
										
										if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
											$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$message .= "Client Name: " . $emp_tag_id . "\n";
											}
										}
										if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
											$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$message .= "Client Name: " . $emp_tag_id . "\n";
											}
										}
										if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
											
											$transport_tags1 = explode ( ',', $result ['transport_tags'] );
											
											$transport_tags = '';
											foreach ( $transport_tags1 as $tag1 ) {
												$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
												
												if ($tags_info1 ['emp_first_name']) {
													$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
												} else {
													$emp_tag_id = $tags_info1 ['emp_tag_id'];
												}
												
												if ($tags_info1) {
													$transport_tags .= $emp_tag_id . ', ';
												}
											}
											
											$message .= "Client Name: " . $transport_tags . "\n";
										}
										// $message .= "Description:
										// ".$result['description']."\n";
										$message .= "Description: " . substr ( $result ['description'], 0, 150 ) . ((strlen ( $result ['description'] ) > 150) ? '..' : '') . "\n";
										
										$sdata = array ();
										$sdata ['message'] = $message;
										$sdata ['phone_number'] = $tuser ['phone_number'];
										$sdata ['facilities_id'] = $facilities_id;
										// $sdata['is_task'] = 1;
										$response = $this->model_api_smsapi->sendsms ( $sdata );
									}
								}
								
								if ($tuser ['email']) {
									if ($result ['completion_alert_type_email'] == '1') {
										
										$message33 = "";
										$messagebody = 'TASK ALERT | Task was marked LATE';
										$messagebody1 = 'The following task has been marked completed late.';
										
										$message33 .= $this->completeemailtemplate ( $result, $result ['date_added'], $result ['task_time'], $messagebody, $messagebody1 );
										
										$edata = array ();
										$edata ['message'] = $message33;
										$edata ['subject'] = 'TASK ALERT | Task was marked LATE';
										$edata ['user_email'] = $tuser ['email'];
										
										$email_status = $this->model_api_emailapi->sendmail ( $edata );
									}
								}
							}
						}
					}
				}
				
				if ($result ['userids'] != null && $result ['userids'] != "") {
					$userids1 = explode ( ',', $result ['userids'] );
					
					$this->load->model ( 'user/user' );
					$this->load->model ( 'setting/tags' );
					
					foreach ( $userids1 as $userid ) {
						
						$user_info = $this->model_user_user->getUserbyupdate ( $userid );
						
						if ($user_info) {
							
							if ($user_info ['phone_number']) {
								if ($result ['completion_alert_type_sms'] == '1') {
									$message = "TASK ALERT | Task was marked LATE " . date ( 'h:i A', strtotime ( $result ['task_time'] ) ) . "...\n";
									$message .= "Task Type: " . $result ['tasktype'] . "\n";
									if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									
									if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
										
										$transport_tags1 = explode ( ',', $result ['transport_tags'] );
										
										$transport_tags = '';
										foreach ( $transport_tags1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$transport_tags .= $emp_tag_id . ', ';
											}
										}
										
										$message .= "Client Name: " . $transport_tags . "\n";
									}
									$message .= "Description: " . $result ['description'] . "\n";
									
									$sdata = array ();
									$sdata ['message'] = $message;
									$sdata ['phone_number'] = $user_info ['phone_number'];
									$sdata ['facilities_id'] = $facilities_id;
									// $sdata['is_task'] = 1;
									$response = $this->model_api_smsapi->sendsms ( $sdata );
								}
							}
							
							if ($user_info ['email']) {
								if ($result ['completion_alert_type_email'] == '1') {
									
									$message33 = "";
									$messagebody = 'TASK ALERT | Task was marked LATE';
									$messagebody1 = 'The following task has been marked completed late.';
									$message33 .= $this->completeemailtemplate ( $result, $result ['date_added'], $result ['task_time'], $messagebody, $messagebody1 );
									
									$edata = array ();
									$edata ['message'] = $message33;
									$edata ['subject'] = 'TASK ALERT | Task was marked LATE';
									$edata ['user_email'] = $user_info ['email'];
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata );
								}
							}
						}
					}
				}
			}
		} else {
			// echo 2222;
			$taskDuration = '2';
			
			if ($result ['completed_alert'] == '1') {
				
				$this->load->model ( 'api/emailapi' );
				$this->load->model ( 'api/smsapi' );
				
				if ($result ['user_roles'] != null && $result ['user_roles'] != "") {
					$user_roles1 = explode ( ',', $result ['user_roles'] );
					
					$this->load->model ( 'user/user_group' );
					$this->load->model ( 'user/user' );
					$this->load->model ( 'setting/tags' );
					
					foreach ( $user_roles1 as $user_role ) {
						
						$urole = array ();
						$urole ['user_group_id'] = $user_role;
						$tusers = $this->model_user_user->getUsers ( $urole );
						
						if ($tusers) {
							foreach ( $tusers as $tuser ) {
								
								if ($tuser ['phone_number']) {
									if ($result ['completion_alert_type_sms'] == '1') {
										
										$message = "TASK ALERT | Task was marked COMPLETED " . date ( 'h:i A', strtotime ( $result ['task_time'] ) ) . "...\n";
										$message .= "Task Type: " . $result ['tasktype'] . "\n";
										
										if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
											$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$message .= "Client Name: " . $emp_tag_id . "\n";
											}
										}
										
										if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
											$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$message .= "Client Name: " . $emp_tag_id . "\n";
											}
										}
										if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
											$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$message .= "Client Name: " . $emp_tag_id . "\n";
											}
										}
										if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
											
											$transport_tags1 = explode ( ',', $result ['transport_tags'] );
											
											$transport_tags = '';
											foreach ( $transport_tags1 as $tag1 ) {
												$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
												
												if ($tags_info1 ['emp_first_name']) {
													$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
												} else {
													$emp_tag_id = $tags_info1 ['emp_tag_id'];
												}
												
												if ($tags_info1) {
													$transport_tags .= $emp_tag_id . ', ';
												}
											}
											
											$message .= "Client Name: " . $transport_tags . "\n";
										}
										
										$message .= "Description: " . substr ( $result ['description'], 0, 150 ) . ((strlen ( $result ['description'] ) > 150) ? '..' : '') . "\n";
										// $message .= "Description:
										// ".$result['description']."\n";
										
										$sdata = array ();
										$sdata ['message'] = $message;
										$sdata ['phone_number'] = $tuser ['phone_number'];
										$sdata ['facilities_id'] = $facilities_id;
										// $sdata['is_task'] = 1;
										$response = $this->model_api_smsapi->sendsms ( $sdata );
									}
								}
								
								if ($tuser ['email']) {
									if ($result ['completion_alert_type_email'] == '1') {
										
										$message33 = "";
										$messagebody = 'TASK ALERT | Task was marked COMPLETED';
										$messagebody1 = 'The following task has been marked completed.';
										$message33 .= $this->completeemailtemplate ( $result, $result ['date_added'], $result ['task_time'], $messagebody, $messagebody1 );
										
										$edata = array ();
										$edata ['message'] = $message33;
										$edata ['subject'] = 'TASK ALERT | Task was marked COMPLETED';
										$edata ['user_email'] = $tuser ['email'];
										
										$email_status = $this->model_api_emailapi->sendmail ( $edata );
									}
								}
							}
						}
					}
				}
				
				if ($result ['userids'] != null && $result ['userids'] != "") {
					$userids1 = explode ( ',', $result ['userids'] );
					$this->load->model ( 'user/user' );
					$this->load->model ( 'setting/tags' );
					
					foreach ( $userids1 as $userid ) {
						
						$user_info = $this->model_user_user->getUserbyupdate ( $userid );
						
						if ($user_info) {
							
							if ($user_info ['phone_number']) {
								if ($result ['completion_alert_type_sms'] == '1') {
									$message = "TASK ALERT | Task was marked COMPLETED " . date ( 'h:i A', strtotime ( $result ['task_time'] ) ) . "...\n";
									$message .= "Task Type: " . $result ['tasktype'] . "\n";
									
									if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									
									if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
										
										$transport_tags1 = explode ( ',', $result ['transport_tags'] );
										
										$transport_tags = '';
										foreach ( $transport_tags1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$transport_tags .= $emp_tag_id . ', ';
											}
										}
										
										$message .= "Client Name: " . $transport_tags . "\n";
									}
									$message .= "Description: " . substr ( $result ['description'], 0, 150 ) . ((strlen ( $result ['description'] ) > 150) ? '..' : '') . "\n";
									// $message .= "Description:
									// ".$result['description']."\n";
									
									$sdata = array ();
									$sdata ['message'] = $message;
									$sdata ['phone_number'] = $user_info ['phone_number'];
									$sdata ['facilities_id'] = $facilities_id;
									// $sdata['is_task'] = 1;
									$response = $this->model_api_smsapi->sendsms ( $sdata );
								}
							}
							
							if ($user_info ['email']) {
								if ($result ['completion_alert_type_email'] == '1') {
									
									$message33 = "";
									$messagebody = 'TASK ALERT | Task was marked COMPLETED';
									$messagebody1 = 'The following task has been marked completed.';
									$message33 .= $this->completeemailtemplate ( $result, $result ['date_added'], $result ['task_time'], $messagebody );
									
									$edata = array ();
									$edata ['message'] = $message33;
									$edata ['subject'] = 'TASK ALERT | Task was marked COMPLETED';
									$edata ['user_email'] = $user_info ['email'];
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata );
								}
							}
						}
					}
				}
			}
		}
		
		// die;
		
		if ($data ['customlistvalues_id']) {
			
			$this->load->model ( 'notes/notes' );
			$custom_info = $this->model_notes_notes->getcustomlistvalue ( $data ['customlistvalues_id'] );
			
			$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
			
			$customlistvalues_id = $data ['customlistvalues_id'];
		}
		
		if ($data ['customlistvalues_ids']) {
			
			$this->load->model ( 'notes/notes' );
			
			foreach ( $data ['customlistvalues_ids'] as $customlistvalues_id ) {
				
				$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
				
				$customlistvalues_name1 = $custom_info ['customlistvalues_name'];
				
				$customlistvalues_name .= ' | ' . $customlistvalues_name1;
			}
			
			$customlistvalues_id = implode ( ',', $data ['customlistvalues_ids'] );
		}
		
		if ($requires_approval == 'approve') {
			$sql = "SELECT tasktype from `" . DB_PREFIX . "createtask` where approval_taskid = '" . $result ['id'] . "'  ";
			$t_info = $this->db->query ( $sql );
			
			$tasktype_r = $t_info->row ['tasktype'];
		}
		
		if ($tasktypetype == '5') {
			
			if ($data ['tags_ids'] != null && $data ['tags_ids'] != "") {
				$transport_tags1 = $data ['tags_ids'];
				
				$is_drop_off = '1';
				
				$tage_id1 = $result ['transport_tags'];
				$tags_id = $tage_id1;
			} elseif ($data ['pickup_tags_ids'] != null && $data ['pickup_tags_ids'] != "") {
				$transport_tags1 = $data ['pickup_tags_ids'];
				
				$transport_tags_pi = implode ( ',', $data ['pickup_tags_ids'] );
				
				$is_pick_up = '1';
				
				$pick_up_tags_id = $transport_tags_pi;
				/*
				 * $sql2p = "UPDATE " . DB_PREFIX . "createtask SET
				 * transport_tags = '" . $this->db->escape($transport_tags_pi) .
				 * "' where id= '".$result['id']."' ";
				 * $this->db->query($sql2p);
				 */
			} elseif ($data ['pick_up_tags_id'] != null && $data ['pick_up_tags_id'] != "") {
				$transport_tags1 = $data ['pick_up_tags_id'];
				
				$transport_tags_pi = implode ( ',', $data ['pick_up_tags_id'] );
				
				$is_pick_up = '1';
				$pick_up_tags_id = $transport_tags_pi;
			} else {
				
				// $transport_tags = $result['transport_tags'];
				
				if ($data ['travel_blank_start'] == '1') {
					$transport_tags1 = '';
				} else {
					$transport_tags1 = explode ( ',', $result ['transport_tags'] );
					
					if ($result ['is_transport'] == '1') {
						$is_drop_off = '1';
						
						$tage_id1 = $result ['transport_tags'];
						$tags_id = $tage_id1;
					} else {
						$is_pick_up = '1';
						
						$pick_up_tags_id = $result ['transport_tags'];
					}
				}
			}
			
			$this->load->model ( 'setting/tags' );
			$transport_tags = '';
			
			if ($data ['travel_blank_start'] != '1') {
				foreach ( $transport_tags1 as $tag1 ) {
					$tags_info = $this->model_setting_tags->getTag ( $tag1 );
					
					if ($tags_info ['emp_first_name']) {
						$emp_tag_id = $tags_info ['emp_tag_id'] . ':' . $tags_info ['emp_first_name'];
					} else {
						$emp_tag_id = $tags_info ['emp_tag_id'];
					}
					
					if ($tags_info) {
						$transport_tags .= $emp_tag_id . ', ';
					}
				}
			}
			
			$description = '';
			$description .= ' | ';
			
			if ($requires_approval == 'approve') {
				
				$description .= $tasktype_r . " TASK | APPROVED ";
				
				$task_type = '6';
			} else {
				
				if ($result ['is_transport'] == '1') {
					
					if ($result ['parent_id'] > 0) {
						$this->load->model ( 'notes/notes' );
						$notes_info = $this->model_notes_notes->getNote ( $result ['parent_id'] );
						
						$start_date = new DateTime ( $notes_info ['date_added'] );
						$since_start = $start_date->diff ( new DateTime ( $noteDate ) );
						
						$caltime = "";
						$caltime1 = "";
						
						if ($since_start->y > 0) {
							$caltime .= $since_start->y . ' years ';
						}
						if ($since_start->m > 0) {
							$caltime .= $since_start->m . ' months ';
						}
						if ($since_start->d > 0) {
							$caltime .= $since_start->d . ' days ';
						}
						if ($since_start->h > 0) {
							$caltime .= $since_start->h . ' hours ';
						}
						if ($since_start->i > 0) {
							$caltime .= $since_start->i . ' minutes ';
						}
						
						$caltime1 = ' | Total Travel Time ' . $caltime;
					}
					
					$description .= ' Travel completed at | ' . $result ['dropoff_locations_address'];
					// $description .= ' started at | '. date('h:i A',
					// strtotime($result['dropoff_locations_time'])) .
					// $caltime1;
					$description .= $caltime1;
					$description .= ' for | ' . $transport_tags;
				} else {
					$description .= ' Travel Started from | ' . $result ['pickup_locations_address'];
					$description .= ' at | ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) );
					if ($data ['travel_blank_start'] == '1') {
					} else {
						$description .= ' for the following | ' . $transport_tags;
					}
				}
				
				$description .= ' | ' . $result ['description'];
				$task_type = '3';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
		} elseif ($tasktypetype == '4') {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
			$this->load->model ( 'setting/tags' );
			$medication_tags = '';
			foreach ( $medication_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ':' . $tags_info ['emp_first_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$medication_tags .= $emp_tag_id . ', ';
				}
			}
			
			/*
			 * $description = '';
			 * $description .= ' | ';
			 *
			 * //$description .= ' Medications given at | '.date('h:i A',
			 * strtotime($notetasktime));
			 * $description .= ' Medication for | '.date('h:i A',
			 * strtotime($notetasktime)) .' Completed.';
			 * $description .= ' to the following Resident | ';
			 * $description .= ' Medication for | '. $medication_tags;
			 * $description .= ' the following details were noted: | ';
			 */
			$description = '';
			$description .= ' | ';
			
			if ($requires_approval == 'approve') {
				$description .= $tasktype_r . " TASK | APPROVED ";
				
				$task_type = '6';
			} else {
				$description .= ' Completed | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ';
				$description .= ' Medication given to | ';
				$description .= ' ' . $medication_tags;
				// $description .= ' the following details were noted: | ';
				
				$description .= ' ' . $result ['description'];
				$task_type = '2';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
			$description .= ' | ';
		} elseif ($tasktypetype == '6') {
			
			$description = '';
			// $description .= ' | ';
			
			// $description .= ' Bed Check for | '.date('h:i A',
			// strtotime($notetasktime)) .' Completed.';
			// $description .= ' The following details were noted: ';
			
			if ($requires_approval == 'approve') {
				$description .= $tasktype_r . " TASK | APPROVED ";
				
				$task_type = '6';
			} else {
				
				$description .= ' ' . $result ['description'];
				$task_type = '1';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
		} elseif ($tasktypetype == '3') {
			
			$description = '';
			
			if ($requires_approval == 'approve') {
				$description .= $tasktype_r . " TASK | APPROVED ";
				
				$task_type = '6';
			} else {
				$description .= ' | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' | ';
				
				$description .= ' ' . $result ['description'];
				$task_type = '4';
			}
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
		} else {
			
			if ($requires_approval == 'approve') {
				$description = "";
				$description .= $tasktype_r . " TASK | APPROVED ";
				
				$task_type = '6';
				
				if ($customlistvalues_name) {
					$description1 = ' ' . $customlistvalues_name;
				}
				
				if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
					$description .= $description1 . ' , ' . $data ['comments'];
				} else {
					$description .= $description1 . ' ' . $data ['comments'];
				}
			} else {
				
				if ($customlistvalues_name) {
					$description1 = ' ' . $customlistvalues_name;
				}
				
				if ($result ['linked_id'] > 0) {
					
					if ($tasktype_info ['client_required'] == '0') {
						$description = $result ['description'] . $description1 . ' , ' . $data ['comments'];
					} else {
						$description = $description1 . ' ' . $data ['comments'];
					}
				} else {
					
					if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
						$description = $result ['description'] . $description1 . ' , ' . $data ['comments'];
					} else {
						$description = $result ['description'] . $description1 . ' ' . $data ['comments'];
					}
				}
			}
		}
		
		$date_added1 = date ( 'Y-m-d', strtotime ( $result ['date_added'] ) );
		$date_added2 = $date_added1 . ' ' . $noteDateTime;
		$date_added3 = date ( 'Y-m-d H:i:s', strtotime ( $date_added2 ) );
		
		if ($result ['enable_requires_approval'] == '2') {
			$date_added3 = $noteDate;
		} else {
			$date_added3 = $date_added3;
		}
		
		if ($result ['original_task_time'] != null && $result ['original_task_time'] != "00:00:00") {
			$taskadded = '3';
		} else {
			$taskadded = $taskDuration;
		}
		
		if ($data ['perpetual_checkbox'] == '3') {
			if ($data ['facilitydrop'] != NULL && $data ['facilitydrop'] != "") {
				
				$this->load->model ( 'facilities/facilities' );
				$this->load->model ( 'form/form' );
				$form_info = $this->model_form_form->getFormDatas ( $result ['formreturn_id'] );
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $data ['facilitydrop'] );
				
				$formdata = unserialize ( $form_info ['design_forms'] );
				foreach ( $formdata as $design_forms ) {
					foreach ( $design_forms as $key => $design_form ) {
						foreach ( $design_form as $key2 => $b ) {
							
							$arrss = explode ( "_1_", $key2 );
							
							if ($arrss [1] == 'tags_id') {
								if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
									$formusername = ' | ' . $design_form [$arrss [0]];
								}
							}
						}
					}
				}
				
				if ($form_info ['incident_number'] != null && $form_info ['incident_number'] != "") {
					$description = $form_info ['incident_number'] . " for " . $formusername . " has been moved to " . $facility ['facility'] . ' ' . $data ['comments'];
				} else {
					$description = $result ['description'] . " has been moved to " . $facility ['facility'] . ' ' . $data ['comments'];
				}
				
				if ($customlistvalues_name) {
					$description .= ' ' . $customlistvalues_name;
				}
			} else {
				
				if ($data ['perpetual_checkbox'] == '2') {
					$description = " Paused " . $data ['pause_time'] . ' ' . $result ['description'] . ' | ' . $data ['comments'];
				} else {
					$description = $description;
				}
				
				if ($customlistvalues_name) {
					$description .= ' ' . $customlistvalues_name;
				}
			}
		} else {
			
			if ($data ['perpetual_checkbox'] == '2') {
				$description = " Paused " . $data ['pause_time'] . ' ' . $result ['description'] . ' | ' . $data ['comments'];
			} elseif ($data ['perpetual_checkbox'] == '4') {
				// $description = $description;
				$description = " Task Interval has been updated " . $data ['acttion_interval_id'] . ' Mintues ' . $result ['description'] . ' | ' . $data ['comments'];
			} else {
				$description = $description;
			}
			
			if ($customlistvalues_name) {
				// $description .= ' ' . $customlistvalues_name;
			}
		}
		
		
		
		$this->load->model ( 'user/user' );
		$user_info = $this->model_user_user->getUser ( $data ['user_id'] );
		
		if ($data ['perpetual_checkbox'] == '1') {
			$end_task = 1;
		}else{
			$end_task = $result ['end_task'];
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes` SET 
			facilities_id = '" . $facilities_id . "',
			highlighter_id ='0',
			status= '1',	
			user_id = '" . $this->db->escape ( $user_info ['username'] ) . "',
			signature= '" . $data ['imgOutput'] . "',
			signature_image= '" . $fileName . "',
			notes_pin = '" . $this->db->escape ( $data ['notes_pin'] ) . "',
			notes_type = '" . $this->db->escape ( $data ['notes_type'] ) . "',
			phone_device_id = '" . $this->db->escape ( $data ['phone_device_id'] ) . "',
			notes_description = '" . $this->db->escape ( $description ) . "' ,
			notetime = '" . $this->db->escape ( $noteDateTime ) . "',
			task_time = '" . $notetasktime . "',
			date_added = '" . $this->db->escape ( $date_added3 ) . "',
			note_date = '" . $noteDate . "',
			snooze_dismiss = '2',
			form_snooze_dismiss = '2',
			taskadded = '" . $taskadded . "',
			assign_to= '" . $this->db->escape ( $result ['assign_to'] ) . "',
			tasktype= '" . $tasktype_id . "',
			update_date= '" . $update_date . "', 
			notes_conut='0' ,
			task_id= '" . $result ['id'] . "',
			customlistvalues_id = '" . $customlistvalues_id . "',
			is_android = '" . $data ['is_android'] . "',
			task_type= '" . $task_type . "',
			end_perpetual_task= '" . $end_perpetual_task . "',
			recurrence= '" . $this->db->escape ( $result ['recurrence'] ) . "',
			task_date = '" . $this->db->escape ( $result ['date_added'] ) . "',
			task_group_by = '" . $this->db->escape ( $result ['task_group_by'] ) . "',
			end_task = '" . $this->db->escape ( $end_task ) . "',
			linked_id = '" . $this->db->escape ( $result ['linked_id'] ) . "',
			parent_facilities_id = '" . $this->db->escape ( $result ['target_facilities_id'] ) . "',
			original_task_time = '" . $this->db->escape ( $result ['original_task_time'] ) . "',
			is_approval_required_forms_id = '" . $this->db->escape ( $result ['is_approval_required_forms_id'] ) . "',
			task_form_id = '" . $this->db->escape ( $result ['task_form_id'] ) . "',
			parent_id = '" . $this->db->escape ( $result ['parent_id'] ) . "'
			
			";
		
		$this->db->query ( $sql );
		
		$notes_id = $this->db->getLastId ();
		
		if ($data ['perpetual_checkbox'] == '1') {
			if ($result ['linked_id'] == '0') {
				$sql122f = "UPDATE `" . DB_PREFIX . "notes` SET linked_id = '" . $notes_id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122f );
			}
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_id) {
			$unique_id = $facility ['customer_key'];
			$sql121 = "UPDATE `" . DB_PREFIX . "notes` SET unique_id = '" . $this->db->escape ( $unique_id ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql121 );
		}
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		if ($requires_approval == 'approve') {
			
			$sql1 = "UPDATE `" . DB_PREFIX . "createtask` set enable_requires_approval = '0' where approval_taskid = '" . $result ['id'] . "' ";
			$this->db->query ( $sql1 );
			
			$sql1n = "UPDATE `" . DB_PREFIX . "notes_by_approval_task` set parent_id = '" . $notes_id . "', notes_id = '" . $notes_id . "', enable_requires_approval = '0', status = '1' where approval_taskid = '" . $result ['id'] . "' ";
			$this->db->query ( $sql1n );
			
			if ($result ['is_approval_required_forms_id'] > 0) {
				$sql1f = "UPDATE `" . DB_PREFIX . "forms` set is_approved = '1' where forms_id = '" . $result ['is_approval_required_forms_id'] . "' ";
				$this->db->query ( $sql1f );
			}
		}
		
		if ($result ['transport_tags']) {
			$this->load->model ( 'createtask/createtask' );
			$travelWaypoints = $this->model_createtask_createtask->gettravelWaypoints ( $result ['id'] );
			if ($travelWaypoints != null && $travelWaypoints != "") {
				foreach ( $travelWaypoints as $travelWaypoint ) {
					$sql2233 = "INSERT INTO `" . DB_PREFIX . "notes_createtask_by_transport` SET id = '" . $result ['id'] . "', locations_address = '" . $this->db->escape ( $travelWaypoint ['locations_address'] ) . "', latitude = '" . $this->db->escape ( $travelWaypoint ['latitude'] ) . "', longitude = '" . $this->db->escape ( $travelWaypoint ['longitude'] ) . "', place_id = '" . $this->db->escape ( $travelWaypoint ['place_id'] ) . "', notes_id = '" . $this->db->escape ( $notes_id ) . "' ";
					$this->db->query ( $sql2233 );
				}
			}
		}
		
		/**
		 * ***************************************
		 */
		
		if ($tasktype_info ['enable_location'] == '1') {
			/*
			 * if($tasktype_info['task_id'] == '10'){
			 * $tage_id1 = $result['transport_tags'];
			 * }elseif($tasktype_info['task_id'] == '2'){
			 * $tage_id1 = $result['medication_tags'];
			 * }elseif($result['emp_tag_id'] != null && $result['emp_tag_id'] !=
			 * ""){
			 * $tage_id1 = $result['emp_tag_id'];
			 * }
			 *
			 *
			 * if($is_pick_up == '1'){
			 * $pick_up_tags_id = $tage_id1;
			 * }
			 *
			 * if($is_drop_off == '1'){
			 * $tags_id = $tage_id1;
			 * }
			 */
			// var_dump($tage_id1);
			
			/*
			 * $this->load->model('notes/tags');
			 * $taginfo = $this->model_notes_tags->getTag($tage_id1);
			 *
			 * $tags_id = $taginfo['tags_id'];
			 */
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				$google_url = "https://www.google.com/maps/dir/" . $result ['pickup_locations_address'] . '/' . $result ['dropoff_locations_address'];
			}
			
			if (($data ['current_lat'] != null && $data ['current_lat'] != "") && ($data ['current_log'] != null && $data ['current_log'] != "")) {
				
				if ($result ['is_transport'] == '0') {
					$latitude = $result ['pickup_locations_latitude'];
					$longitude = $result ['pickup_locations_longitude'];
				} else {
					$latitude = $result ['dropoff_locations_latitude'];
					$longitude = $result ['dropoff_locations_longitude'];
				}
				
				$distanced = $this->distance ( $data ['current_lat'], $data ['current_log'], $latitude, $longitude, "K" );
				
				if (($distanced >= '5')) {
					$keyword_id = $tasktype_info ['relation_keyword_id'];
				}
				
				if ($data ['current_locations_address'] != null && $data ['current_locations_address'] != "") {
					$current_locations_address = $data ['current_locations_address'];
				} else {
					
					$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $data ['current_lat'] ) . ',' . trim ( $data ['current_log'] ) . '&sensor=false';
					$json = @file_get_contents ( $url );
					$ldata = json_decode ( $json );
					$status = $ldata->status;
					if ($status == "OK") {
						$current_locations_address = $ldata->results [0]->formatted_address;
					}
				}
				
				$current_google_url = "https://www.google.com/maps/place/" . $current_locations_address . '/' . $data ['current_lat'] . ',' . $data ['current_log'];
			}
			
			$this->load->model ( 'createtask/createtask' );
			$waypoints = $this->model_createtask_createtask->gettravelWaypoints ( $result ['id'] );
			
			if ($waypoints != "" && $waypoints != null) {
				$waypoint_google_url1 = "";
				foreach ( $waypoints as $waypoint ) {
					$waypoint_google_url1 .= '/' . $waypoint ['locations_address'];
				}
				
				$waypoint_google_url = "https://www.google.com/maps/dir/" . $result ['pickup_locations_address'] . $waypoint_google_url1 . '/' . $result ['dropoff_locations_address'];
			}
			
			$sqll = "INSERT INTO `" . DB_PREFIX . "notes_by_travel_task` SET 
			facilities_id = '" . $facilities_id . "',
			notes_id = '" . $notes_id . "',
			type = '" . $tasktype_info ['task_id'] . "',
			travel_state = '" . $this->db->escape ( $result ['is_transport'] ) . "',
			pickup_locations_address = '" . $this->db->escape ( $result ['pickup_locations_address'] ) . "',
			pickup_locations_latitude = '" . $this->db->escape ( $result ['pickup_locations_latitude'] ) . "',
			pickup_locations_longitude = '" . $this->db->escape ( $result ['pickup_locations_longitude'] ) . "',
			
			dropoff_locations_address = '" . $this->db->escape ( $result ['dropoff_locations_address'] ) . "',
			dropoff_locations_latitude= '" . $this->db->escape ( $result ['dropoff_locations_latitude'] ) . "',
			dropoff_locations_longitude = '" . $this->db->escape ( $result ['dropoff_locations_longitude'] ) . "',
			
			google_url = '" . $this->db->escape ( $google_url ) . "',
			
			current_locations_address = '" . $this->db->escape ( $current_locations_address ) . "',
			current_locations_latitude = '" . $this->db->escape ( $data ['current_lat'] ) . "',
			current_locations_longitude = '" . $this->db->escape ( $data ['current_log'] ) . "',
			
			current_google_url = '" . $this->db->escape ( $current_google_url ) . "',
			
			location_tracking_url = '" . $this->db->escape ( $data ['location_tracking_url'] ) . "',
			location_tracking_route = '" . $this->db->escape ( $data ['location_tracking_route'] ) . "',
			location_tracking_time_start = '" . $this->db->escape ( $data ['location_tracking_time_start'] ) . "',
			location_tracking_time_end = '" . $this->db->escape ( $data ['location_tracking_time_end'] ) . "',
			google_map_image_url = '" . $this->db->escape ( $data ['google_map_image_url'] ) . "',
			
			date_added = '" . $this->db->escape ( $date_added3 ) . "',
			tags_id = '" . $this->db->escape ( $tags_id ) . "',
			is_drop_off = '" . $this->db->escape ( $is_drop_off ) . "',
			waypoint_google_url = '" . $this->db->escape ( $waypoint_google_url ) . "',
			keyword_id = '" . $this->db->escape ( $keyword_id ) . "',
			is_pick_up = '" . $this->db->escape ( $is_pick_up ) . "',
			unique_id = '" . $this->db->escape ( $unique_id ) . "',
			pick_up_tags_id = '" . $this->db->escape ( $pick_up_tags_id ) . "'
			
			";
			
			$this->db->query ( $sqll );
			
			$travel_task_id = $this->db->getLastId ();
			
			$sqlc = "UPDATE `" . DB_PREFIX . "notes_by_travel_task_coordinates` SET travel_task_id = '" . $this->db->escape ( $travel_task_id ) . "', notes_id = '" . $notes_id . "' where task_id = '" . ( int ) $result ['id'] . "' ";
			$this->db->query ( $sqlc );
			
			$barray1 = array ();
			$barray1 ['travel_task_id'] = $travel_task_id;
			$barray1 ['is_transport'] = $result ['is_transport'];
			$barray1 ['pickup_locations_address'] = $result ['pickup_locations_address'];
			$barray1 ['dropoff_locations_address'] = $result ['dropoff_locations_address'];
			$barray1 ['google_url'] = $google_url;
			$barray1 ['current_locations_address'] = $current_locations_address;
			$barray1 ['current_google_url'] = $current_google_url;
			$barray1 ['facilities_id'] = $facilities_id;
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'inserttasknotes_by_travel_task', $barray1, 'query' );
		}
		
		/**
		 * ****************************************
		 */
		
		if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
			
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
		}
		if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "0") {
			$this->load->model ( 'notes/notes' );
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $result ['visitation_tag_id'] );
			
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
		}
		
		$ctime = time ();
		$stime = date ( 'H:i:s', strtotime ( $ctime ) );
		$this->load->model ( 'notes/notes' );
		$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $facilities_id );
		if(!empty($shift_info['shift_id'])){
			$id = $shift_info['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . (int) $notes_id . "' ";
			$this->db->query($updateshift);
		}
		
		/*$sqlshift = "SELECT  * FROM `" . DB_PREFIX . "shift` where shift_starttime > '" . $stime . "' and shift_endtime < '" . $stime . "' ";
		$shifts = $this->db->query ( $sqlshift );
		
		if (! empty ( $shifts->row ['shift_id'] )) {
			$id = $shifts->row ['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $updateshift );
		}*/
		
		/*
		 * if($tasktype_id == '10'){
		 *
		 * $transport_tags1 = explode(',',$result['transport_tags']);
		 *
		 *
		 * $this->load->model('setting/tags');
		 * $this->load->model('notes/notes');
		 *
		 * $transport_tags = '';
		 * foreach ($transport_tags1 as $tag1) {
		 * $taginfo = $this->model_setting_tags->getTag($tag1);
		 *
		 * if($data['facilitytimezone'] != null && $data['facilitytimezone'] !=
		 * ""){
		 * date_default_timezone_set($data['facilitytimezone']);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }else{
		 * $timezone_name = $this->customer->isTimezone();
		 * date_default_timezone_set($timezone_name);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }
		 *
		 * $this->model_notes_notes->updateNotesTag($taginfo['emp_tag_id'],
		 * $notes_id, $taginfo['tags_id'], $update_date);
		 * }
		 *
		 * }
		 */
		
		/*
		 * $this->load->model('createtask/createtask');
		 * $tasktype_info =
		 * $this->model_createtask_createtask->gettasktyperowByName($result['tasktype']);
		 * $tasktype_id = $tasktype_info['task_id'];
		 *
		 * if($tasktype_id == '11'){
		 * if($result['task_form_id'] != null && $result['task_form_id'] !=
		 * "0"){
		 * $this->load->model('setting/bedchecktaskform');
		 * $this->load->model('setting/tags');
		 *
		 * $bedcheckinfos =
		 * $this->model_setting_bedchecktaskform->getruleModule($result['task_form_id']);
		 *
		 * if($bedcheckinfos['bctf_module'] != null &&
		 * $bedcheckinfos['bctf_module'] != ""){
		 * foreach($bedcheckinfos['bctf_module'] as $bedcheckinfo){
		 * $tag_info =
		 * $this->model_setting_tags->getTagsbyNotescurrencyid($bedcheckinfo['locations_id']);
		 * //var_dump($tag_info);
		 *
		 * $this->load->model('notes/notes');
		 * if($data['facilitytimezone'] != null && $data['facilitytimezone'] !=
		 * ""){
		 * date_default_timezone_set($data['facilitytimezone']);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }else{
		 * $timezone_name = $this->customer->isTimezone();
		 * date_default_timezone_set($timezone_name);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }
		 *
		 * $this->model_notes_notes->updateNotesTag($tag_info['emp_tag_id'],
		 * $notes_id,$tag_info['tags_id'], $update_date);
		 * }
		 * }
		 *
		 * }
		 *
		 * }
		 */
		
		// if($result['recurrence'] == 'hourly'){
		
		if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
			date_default_timezone_set ( $data ['facilitytimezone'] );
			$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		} else {
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		}
		
		$details1 = "SELECT parent_id FROM `" . DB_PREFIX . "createtask` where parent_id = '0' and recurrence = '" . $result ['recurrence'] . "' and facilityId = '" . $facilities_id . "' and description = '" . $this->db->escape ( $result ['description'] ) . "' ";
		$queryt = $this->db->query ( $details1 );
		
		if ($queryt->row ['parent_id'] == '0' && $queryt->row ['parent_id'] != null) {
			
			if ($requires_approval == 'approve') {
				$notes_idss = 0;
			} else {
				$notes_idss = $notes_id;
			}
			
			$sql213 = "update `" . DB_PREFIX . "createtask` set parent_id = '" . $notes_idss . "' where recurrence = '" . $result ['recurrence'] . "' and facilityId = '" . $facilities_id . "' and description = '" . $this->db->escape ( $result ['description'] ) . "' ";
			$this->db->query ( $sql213 );
		}
		
		// }
		
		$details = "SELECT notes_id, parent_id FROM `" . DB_PREFIX . "notes` where notes_id = '" . $result ['parent_id'] . "'  ";
		$query = $this->db->query ( $details );
		// echo "<hr>";
		
		if ($query->row) {
			if ($query->row ['parent_id'] < 0) {
				$parent_id = $query->row ['parent_id'];
			} else {
				$parent_id = $query->row ['notes_id'];
			}
		} else {
			
			if ($requires_approval == 'approve') {
				$parent_id = 0;
			} else {
				$parent_id = $notes_id;
			}
			
			// $sql2 = "update `" . DB_PREFIX . "notes` set parent_id =
			// '".$parent_id."' where notes_id='".$parent_id."' ";
			// $this->db->query($sql2);
		}
		
		$sql21 = "update `" . DB_PREFIX . "notes` set parent_id = '" . $parent_id . "', notes_conut ='0' where notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sql21 );
		
		$barray = array ();
		
		$barray ['notes_id'] = $notes_id;
		$barray ['task_id'] = $result ['id'];
		$barray ['date_added'] = $date_added3;
		$barray ['note_date'] = $noteDate;
		
		$barray ['user_id'] = $data ['user_id'];
		$barray ['note_type'] = $data ['strike_note_type'];
		$barray ['notes_type'] = $data ['notes_type'];
		$barray ['notes_pin'] = $data ['notes_pin'];
		$barray ['phone_device_id'] = $data ['phone_device_id'];
		$barray ['is_android'] = $data ['is_android'];
		
		$barray ['task_type'] = $task_type;
		$barray ['notetime'] = $noteDateTime;
		$barray ['task_time'] = $notetasktime;
		$barray ['notes_description'] = $description;
		$barray ['facilities_id'] = $facilities_id;
		$barray ['customlistvalues_id'] = $customlistvalues_id;
		$barray ['recurrence'] = $result ['recurrence'];
		$barray ['task_date'] = $result ['date_added'];
		$barray ['task_group_by'] = $result ['task_group_by'];
		$barray ['parent_id'] = $result ['parent_id'];
		$barray ['assign_to'] = $result ['assign_to'];
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'inserttask', $barray, 'query' );
		
		if ($data ['perpetual_checkbox'] == '3') {
			if ($data ['facilitydrop'] != NULL && $data ['facilitydrop'] != "") {
				$facilities_id1 = $data ['facilitydrop'];
				
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "notes_by_facility` SET move_facilities_id = '" . $facilities_id1 . "', facilities_id = '" . $facilities_id . "', notes_id = '" . $notes_id . "', parent_id = '" . $parent_id . "', date_added = '" . $date_added3 . "' " );
			} else {
				$facilities_id1 = $facilities_id;
			}
		} else {
			$facilities_id1 = $facilities_id;
		}
		
		if ($data ['perpetual_checkbox'] != '1') {
			$this->checkNotification ( $result, $facilities_id1, $parent_id );
		}
		
		if ($this->config->get ( 'config_realtime_data' ) == '1') {
			$this->load->model ( 'api/realtime' );
			$realdata = array ();
			$realdata ['facilities_id'] = $facilities_id;
			$realdata ['notes_id'] = $notes_id;
			$this->model_api_realtime->addrealtime ( $realdata );
		}
		
		return $notes_id;
	}
	public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin ( deg2rad ( $lat1 ) ) * sin ( deg2rad ( $lat2 ) ) + cos ( deg2rad ( $lat1 ) ) * cos ( deg2rad ( $lat2 ) ) * cos ( deg2rad ( $theta ) );
		$dist = acos ( $dist );
		$dist = rad2deg ( $dist );
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper ( $unit );
		
		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	public function insertTaskLists($result, $facilities_id, $client_discharge) {
		$task_info = $this->gettaskrow ( $result ['id'] );
		
		if ($task_info ['incomplete_alert'] == '1') {
			
			$this->load->model ( 'api/emailapi' );
			$this->load->model ( 'api/smsapi' );
			
			if ($task_info ['user_roles'] != null && $task_info ['user_roles'] != "") {
				$user_roles1 = explode ( ',', $task_info ['user_roles'] );
				
				$this->load->model ( 'user/user_group' );
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				foreach ( $user_roles1 as $user_role ) {
					
					$urole = array ();
					$urole ['user_group_id'] = $user_role;
					$tusers = $this->model_user_user->getUsers ( $urole );
					
					if ($tusers) {
						foreach ( $tusers as $tuser ) {
							
							if ($tuser ['phone_number']) {
								if ($task_info ['completion_alert_type_sms'] == '1') {
									$message = "TASK ALERT | Task was marked INCOMPLETE " . date ( 'h:i A', strtotime ( $task_info ['task_time'] ) ) . "...\n";
									$message .= "Task Type: " . $task_info ['tasktype'] . "\n";
									
									if ($task_info ['emp_tag_id'] != null && $task_info ['emp_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $task_info ['emp_tag_id'] );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									
									if ($task_info ['medication_tags'] != null && $task_info ['medication_tags'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $task_info ['medication_tags'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($task_info ['visitation_tag_id'] != null && $task_info ['visitation_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $task_info ['visitation_tag_id'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($task_info ['transport_tags'] != null && $task_info ['transport_tags'] != "") {
										
										$transport_tags1 = explode ( ',', $task_info ['transport_tags'] );
										
										$transport_tags = '';
										foreach ( $transport_tags1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$transport_tags .= $emp_tag_id . ', ';
											}
										}
										
										$message .= "Client Name: " . $transport_tags . "\n";
									}
									$message .= "Description: " . substr ( $task_info ['description'], 0, 150 ) . ((strlen ( $task_info ['description'] ) > 150) ? '..' : '') . "\n";
									// $message .= "Description:
									// ".$task_info['description']."\n";
									
									$sdata = array ();
									$sdata ['message'] = $message;
									$sdata ['phone_number'] = $tuser ['phone_number'];
									$sdata ['facilities_id'] = $facilities_id;
									// $sdata['is_task'] = 1;
									$response = $this->model_api_smsapi->sendsms ( $sdata );
								}
							}
							
							if ($tuser ['email']) {
								if ($task_info ['completion_alert_type_email'] == '1') {
									
									$message33 = "";
									$messagebody = 'TASK ALERT | Task was marked INCOMPLETE';
									$messagebody1 = 'The following task has been marked incomplete.';
									$message33 .= $this->completeemailtemplate ( $task_info, $task_info ['date_added'], $task_info ['task_time'], $messagebody, $messagebody1 );
									
									$edata = array ();
									$edata ['message'] = $message33;
									$edata ['subject'] = 'TASK ALERT | Task was marked INCOMPLETE';
									$edata ['user_email'] = $tuser ['email'];
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata );
								}
							}
						}
					}
				}
			}
			
			if ($task_info ['userids'] != null && $task_info ['userids'] != "") {
				$userids1 = explode ( ',', $task_info ['userids'] );
				
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				foreach ( $userids1 as $userid ) {
					
					$user_info = $this->model_user_user->getUserbyupdate ( $userid );
					
					if ($user_info) {
						
						if ($user_info ['phone_number']) {
							if ($task_info ['completion_alert_type_sms'] == '1') {
								$message = "TASK ALERT | Task was marked INCOMPLETE " . date ( 'h:i A', strtotime ( $task_info ['task_time'] ) ) . "...\n";
								$message .= "Task Type: " . $task_info ['tasktype'] . "\n";
								
								if ($task_info ['emp_tag_id'] != null && $task_info ['emp_tag_id'] != "") {
									$tags_info1 = $this->model_setting_tags->getTag ( $task_info ['emp_tag_id'] );
									
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$message .= "Client Name: " . $emp_tag_id . "\n";
									}
								}
								
								if ($task_info ['medication_tags'] != null && $task_info ['medication_tags'] != "") {
									$tags_info1 = $this->model_setting_tags->getTag ( $task_info ['medication_tags'] );
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$message .= "Client Name: " . $emp_tag_id . "\n";
									}
								}
								if ($task_info ['visitation_tag_id'] != null && $task_info ['visitation_tag_id'] != "") {
									$tags_info1 = $this->model_setting_tags->getTag ( $task_info ['visitation_tag_id'] );
									if ($tags_info1 ['emp_first_name']) {
										$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1 ['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$message .= "Client Name: " . $emp_tag_id . "\n";
									}
								}
								if ($task_info ['transport_tags'] != null && $task_info ['transport_tags'] != "") {
									
									$transport_tags1 = explode ( ',', $task_info ['transport_tags'] );
									
									$transport_tags = '';
									foreach ( $transport_tags1 as $tag1 ) {
										$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$transport_tags .= $emp_tag_id . ', ';
										}
									}
									
									$message .= "Client Name: " . $transport_tags . "\n";
								}
								$message .= "Description: " . substr ( $task_info ['description'], 0, 150 ) . ((strlen ( $task_info ['description'] ) > 150) ? '..' : '') . "\n";
								// $message .= "Description:
								// ".$task_info['description']."\n";
								
								$sdata = array ();
								$sdata ['message'] = $message;
								$sdata ['phone_number'] = $user_info ['phone_number'];
								$sdata ['facilities_id'] = $facilities_id;
								// $sdata['is_task'] = 1;
								$response = $this->model_api_smsapi->sendsms ( $sdata );
							}
						}
						
						if ($user_info ['email']) {
							if ($task_info ['completion_alert_type_email'] == '1') {
								
								$message33 = "";
								$messagebody = 'TASK ALERT | Task was marked INCOMPLETE';
								$messagebody1 = 'The following task has been marked incomplete.';
								$message33 .= $this->completeemailtemplate ( $task_info, $task_info ['date_added'], $task_info ['task_time'], $messagebody, $messagebody1 );
								
								$edata = array ();
								$edata ['message'] = $message33;
								$edata ['subject'] = 'TASK ALERT | Task was marked INCOMPLETE';
								$edata ['user_email'] = $user_info ['email'];
								
								$email_status = $this->model_api_emailapi->sendmail ( $edata );
							}
						}
					}
				}
			}
		}
		
		if ($result ['facilitytimezone'] != null && $result ['facilitytimezone'] != "") {
			date_default_timezone_set ( $result ['facilitytimezone'] );
		} else {
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
		}
		
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$noteDateTime = date ( 'H:i:s', strtotime ( 'now' ) );
		$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
		
		// $noteTime = date('h:i:s', strtotime('now'));
		
		$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->load->model ( 'createtask/createtask' );
		$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $facilities_id );
		$tasktype_id = $tasktype_info ['task_id'];
		$tasktypetype = $tasktype_info ['type'];
		
		if ($tasktypetype == '5') {
			
			$transport_tags1 = explode ( ',', $task_info ['transport_tags'] );
			$this->load->model ( 'setting/tags' );
			$transport_tags = '';
			foreach ( $transport_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ':' . $tags_info ['emp_first_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$transport_tags .= $emp_tag_id . ', ';
				}
			}
			
			$description = '';
			$description .= ' | ';
			
			if ($task_info ['is_transport'] == '1') {
				
				if ($result ['parent_id'] > 0) {
					$this->load->model ( 'notes/notes' );
					$notes_info = $this->model_notes_notes->getNote ( $result ['parent_id'] );
					
					$start_date = new DateTime ( $notes_info ['date_added'] );
					$since_start = $start_date->diff ( new DateTime ( $noteDate ) );
					
					$caltime = "";
					$caltime1 = "";
					
					if ($since_start->y > 0) {
						$caltime .= $since_start->y . ' years ';
					}
					if ($since_start->m > 0) {
						$caltime .= $since_start->m . ' months ';
					}
					if ($since_start->d > 0) {
						$caltime .= $since_start->d . ' days ';
					}
					if ($since_start->h > 0) {
						$caltime .= $since_start->h . ' hours ';
					}
					if ($since_start->i > 0) {
						$caltime .= $since_start->i . ' minutes ';
					}
					
					$caltime1 = ' | Total Travel Time ' . $caltime;
				}
				
				$description .= ' Travel completed at | ' . $task_info ['dropoff_locations_address'];
				$description .= ' started at | ' . date ( 'h:i A', strtotime ( $task_info ['dropoff_locations_time'] ) ) . $caltime1;
				$description .= ' for | ' . $transport_tags;
			} else {
				$description .= ' Travel Started from | ' . $task_info ['pickup_locations_address'];
				$description .= ' at | ' . date ( 'h:i A', strtotime ( $task_info ['pickup_locations_time'] ) );
				$description .= ' for the following | ' . $transport_tags;
			}
			
			$description .= ' | ' . $task_info ['description'];
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
			$task_type = '3';
		} elseif ($tasktypetype == '4') {
			$medication_tags1 = explode ( ',', $task_info ['medication_tags'] );
			$this->load->model ( 'setting/tags' );
			$medication_tags = '';
			foreach ( $medication_tags1 as $tag1 ) {
				$tags_info = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($tags_info ['emp_first_name']) {
					$emp_tag_id = $tags_info ['emp_tag_id'] . ':' . $tags_info ['emp_first_name'];
				} else {
					$emp_tag_id = $tags_info ['emp_tag_id'];
				}
				
				if ($tags_info) {
					$medication_tags .= $emp_tag_id . ', ';
				}
			}
			
			/*
			 * $description = '';
			 * $description .= ' | ';
			 *
			 * //$description .= ' Medications given at | '.date('h:i A',
			 * strtotime($notetasktime));
			 * $description .= ' Medication for | '.date('h:i A',
			 * strtotime($notetasktime)) .' Completed.';
			 * $description .= ' to the following Resident | ';
			 * $description .= ' Medication for | '. $medication_tags;
			 * $description .= ' the following details were noted: ';
			 */
			
			$description = '';
			$description .= ' | ';
			
			$description .= ' Incomplete | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ';
			$description .= ' Medication given to | ';
			$description .= ' ' . $medication_tags;
			// $description .= ' the following details were noted: | ';
			
			$description .= ' ' . $task_info ['description'];
			$description .= ' | ';
			
			$task_type = '2';
		} elseif ($tasktypetype == '6') {
			
			$description = '';
			// $description .= ' | ';
			
			// $description .= ' Bed Check for | '.date('h:i A',
			// strtotime($notetasktime)) .' Completed.';
			// $description .= ' The following details were noted: ';
			
			$description .= ' ' . $task_info ['description'];
			
			if ($customlistvalues_name) {
				$description .= ' | ' . $customlistvalues_name;
			}
			
			$task_type = '1';
		} elseif ($tasktypetype == '3') {
			
			$description = '';
			
			$description .= ' | STARTED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' | ';
			
			$description .= ' ' . $result ['description'];
			
			if ($customlistvalues_name) {
				$description .= ' ' . $customlistvalues_name;
			}
			
			if ($this->db->escape ( $data ['comments'] ) != NULL && $this->db->escape ( $data ['comments'] ) != "") {
				$description .= ' | ' . $data ['comments'];
			}
			$task_type = '4';
		} else {
			
			$description = $task_info ['description'];
		}
		$date_added1 = date ( 'Y-m-d', strtotime ( $result ['date_added'] ) );
		$date_added2 = $date_added1 . ' ' . $noteDateTime;
		$date_added3 = date ( 'Y-m-d H:i:s', strtotime ( $date_added2 ) );
		
		if ($task_info ['enable_requires_approval'] == '2') {
			$date_added3 = $noteDate;
		} else {
			$date_added3 = $date_added3;
		}
		
		if ($task_info ['enable_requires_approval'] == '2') {
			$task_type = '6';
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes` SET 
			facilities_id = '" . $facilities_id . "',
			highlighter_id ='0',
			status= '1',	
			user_id = '" . SYSTEM_GENERATED . "',
			signature= '',
			signature_image= '',
			notes_pin = '" . SYSTEM_GENERATED_PIN . "',
			
			notes_description = '" . $this->db->escape ( $description ) . "',
			notetime = '" . $noteDateTime . "',
			task_time = '" . $notetasktime . "',
			date_added = '" . $this->db->escape ( $date_added3 ) . "',
			note_date = '" . $noteDate . "',
			
			strike_user_id = '',
			strike_date_added= '" . $noteDate . "',
			strike_signature= '',
			strike_signature_image= '',
			strike_pin= '',
			text_color_cut = '0',
			taskadded = '4',
			snooze_dismiss = '2',
			form_snooze_dismiss = '2',
			assign_to= '" . $this->db->escape ( $result ['assign_to'] ) . "',
			tasktype= '" . $tasktype_id . "',
			update_date= '" . $update_date . "', notes_conut='0',
			task_id= '" . $result ['id'] . "',
			task_type= '" . $task_type . "',
			end_perpetual_task= '" . $end_perpetual_task . "',
			recurrence= '" . $this->db->escape ( $task_info ['recurrence'] ) . "',
			task_date = '" . $this->db->escape ( $task_info ['date_added'] ) . "',
			task_group_by = '" . $this->db->escape ( $task_info ['task_group_by'] ) . "',
			end_task = '" . $this->db->escape ( $task_info ['end_task'] ) . "',
			linked_id = '" . $this->db->escape ( $task_info ['linked_id'] ) . "',
			parent_facilities_id = '" . $this->db->escape ( $task_info ['target_facilities_id'] ) . "',
			original_task_time = '" . $this->db->escape ( $task_info ['original_task_time'] ) . "',
			is_approval_required_forms_id = '" . $this->db->escape ( $task_info ['is_approval_required_forms_id'] ) . "',
			task_form_id = '" . $this->db->escape ( $task_info ['task_form_id'] ) . "',
			parent_id = '" . $this->db->escape ( $task_info ['parent_id'] ) . "'
			";
		
		$this->db->query ( $sql );
		$notes_id = $this->db->getLastId ();
		
		if ($facilities_id) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
			$sql121 = "UPDATE `" . DB_PREFIX . "notes` SET unique_id = '" . $this->db->escape ( $unique_id ) . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql121 );
		}
		
		if ($task_info ['transport_tags']) {
			$this->load->model ( 'createtask/createtask' );
			$travelWaypoints = $this->model_createtask_createtask->gettravelWaypoints ( $result ['id'] );
			if ($travelWaypoints != null && $travelWaypoints != "") {
				foreach ( $travelWaypoints as $travelWaypoint ) {
					$sql2233 = "INSERT INTO `" . DB_PREFIX . "notes_createtask_by_transport` SET id = '" . $result ['id'] . "', locations_address = '" . $this->db->escape ( $travelWaypoint ['locations_address'] ) . "', latitude = '" . $this->db->escape ( $travelWaypoint ['latitude'] ) . "', longitude = '" . $this->db->escape ( $travelWaypoint ['longitude'] ) . "', place_id = '" . $this->db->escape ( $travelWaypoint ['place_id'] ) . "', notes_id = '" . $this->db->escape ( $notes_id ) . "' ";
					$this->db->query ( $sql2233 );
				}
			}
		}
		
		/**
		 * ***************************************
		 */
		
		if ($tasktype_info ['enable_location'] == '1') {
			
			if ($tasktype_info ['task_id'] == '10') {
				$tage_id1 = $task_info ['transport_tags'];
			} elseif ($tasktype_info ['task_id'] == '2') {
				$tage_id1 = $task_info ['medication_tags'];
			} elseif ($task_info ['emp_tag_id'] != null && $task_info ['emp_tag_id'] != "") {
				$tage_id1 = $task_info ['emp_tag_id'];
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $tage_id1 );
			$tags_id = $taginfo ['tags_id'];
			
			if ($task_info ['pickup_locations_address'] != null && $task_info ['pickup_locations_address'] != "") {
				$google_url = "https://www.google.com/maps/dir/" . $task_info ['pickup_locations_address'] . '/' . $task_info ['dropoff_locations_address'];
			}
			
			if (($data ['current_lat'] != null && $data ['current_lat'] != "") && ($data ['current_log'] != null && $data ['current_log'] != "")) {
				
				if ($task_info ['is_transport'] == '0') {
					$latitude = $task_info ['pickup_locations_latitude'];
					$longitude = $task_info ['pickup_locations_longitude'];
				} else {
					$latitude = $task_info ['dropoff_locations_latitude'];
					$longitude = $task_info ['dropoff_locations_longitude'];
				}
				
				$distanced = $this->distance ( $data ['current_lat'], $data ['current_log'], $latitude, $longitude, "K" );
				
				if (($distanced >= '5')) {
					$keyword_id = $tasktype_info ['relation_keyword_id'];
				}
				
				if ($data ['current_locations_address'] != null && $data ['current_locations_address'] != "") {
					$current_locations_address = $data ['current_locations_address'];
				} else {
					
					$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $data ['current_lat'] ) . ',' . trim ( $data ['current_log'] ) . '&sensor=false';
					$json = @file_get_contents ( $url );
					$ldata = json_decode ( $json );
					$status = $ldata->status;
					if ($status == "OK") {
						$current_locations_address = $ldata->results [0]->formatted_address;
					}
				}
				
				$current_google_url = "https://www.google.com/maps/place/" . $current_locations_address . '/' . $data ['current_lat'] . ',' . $data ['current_log'];
				
				// $current_google_url =
				// "https://www.google.com/maps/dir/".$task_info['pickup_locations_address'].'/'.$data['current_lat'].','.$data['current_log'];
			}
			
			$this->load->model ( 'createtask/createtask' );
			$waypoints = $this->model_createtask_createtask->gettravelWaypoints ( $result ['id'] );
			
			if ($waypoints != "" && $waypoints != null) {
				$waypoint_google_url1 = "";
				foreach ( $waypoints as $waypoint ) {
					$waypoint_google_url1 .= '/' . $waypoint ['locations_address'];
				}
				
				$waypoint_google_url = "https://www.google.com/maps/dir/" . $result ['pickup_locations_address'] . $waypoint_google_url1 . '/' . $result ['dropoff_locations_address'];
			}
			
			$sqll = "INSERT INTO `" . DB_PREFIX . "notes_by_travel_task` SET 
			facilities_id = '" . $facilities_id . "',
			notes_id = '" . $notes_id . "',
			type = '" . $tasktype_info ['task_id'] . "',
			travel_state = '" . $this->db->escape ( $task_info ['is_transport'] ) . "',
			pickup_locations_address = '" . $this->db->escape ( $task_info ['pickup_locations_address'] ) . "',
			pickup_locations_latitude = '" . $this->db->escape ( $task_info ['pickup_locations_latitude'] ) . "',
			pickup_locations_longitude = '" . $this->db->escape ( $task_info ['pickup_locations_longitude'] ) . "',
			
			dropoff_locations_address = '" . $this->db->escape ( $task_info ['dropoff_locations_address'] ) . "',
			dropoff_locations_latitude= '" . $this->db->escape ( $task_info ['dropoff_locations_latitude'] ) . "',
			dropoff_locations_longitude = '" . $this->db->escape ( $task_info ['dropoff_locations_longitude'] ) . "',
			
			google_url = '" . $this->db->escape ( $google_url ) . "',
			
			current_locations_address = '" . $this->db->escape ( $current_locations_address ) . "',
			current_locations_latitude = '" . $this->db->escape ( $data ['current_lat'] ) . "',
			current_locations_longitude = '" . $this->db->escape ( $data ['current_log'] ) . "',
			
			current_google_url = '" . $this->db->escape ( $current_google_url ) . "',
			
			date_added = '" . $this->db->escape ( $date_added3 ) . "',
			tags_id = '" . $this->db->escape ( $tags_id ) . "',
			waypoint_google_url = '" . $this->db->escape ( $waypoint_google_url ) . "',
			unique_id = '" . $this->db->escape ( $unique_id ) . "',
			keyword_id = '" . $this->db->escape ( $keyword_id ) . "'
			";
			
			$this->db->query ( $sqll );
			
			$travel_task_id = $this->db->getLastId ();
			
			$barray1 = array ();
			$barray1 ['travel_task_id'] = $travel_task_id;
			$barray1 ['is_transport'] = $task_info ['is_transport'];
			$barray1 ['pickup_locations_address'] = $task_info ['pickup_locations_address'];
			$barray1 ['dropoff_locations_address'] = $task_info ['dropoff_locations_address'];
			$barray1 ['google_url'] = $google_url;
			$barray1 ['current_locations_address'] = $current_locations_address;
			$barray1 ['current_google_url'] = $current_google_url;
			$barray1 ['facilities_id'] = $facilities_id;
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'notes_by_travel_task', $barray1, 'query' );
		}
		
		/**
		 * ****************************************
		 */
		
		if ($task_info ['emp_tag_id'] != null && $task_info ['emp_tag_id'] != "") {
			$this->load->model ( 'notes/notes' );
			
			if ($result ['facilitytimezone'] != null && $result ['facilitytimezone'] != "") {
				date_default_timezone_set ( $result ['facilitytimezone'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $task_info ['emp_tag_id'] );
			
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
		}
		
		if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "0") {
			$this->load->model ( 'notes/notes' );
			
			if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
				date_default_timezone_set ( $data ['facilitytimezone'] );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			} else {
				$timezone_name = $this->customer->isTimezone ();
				date_default_timezone_set ( $timezone_name );
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			}
			
			$this->load->model ( 'notes/tags' );
			$taginfo = $this->model_notes_tags->getTag ( $result ['visitation_tag_id'] );
			
			$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
		}
		
		if ($tasktypetype == '5') {
			
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'notes/notes' );
			
			$transport_tags = '';
			foreach ( $transport_tags1 as $tag1 ) {
				$taginfo = $this->model_setting_tags->getTag ( $tag1 );
				
				if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
					date_default_timezone_set ( $data ['facilitytimezone'] );
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				} else {
					$timezone_name = $this->customer->isTimezone ();
					date_default_timezone_set ( $timezone_name );
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				}
				
				$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notes_id, $taginfo ['tags_id'], $update_date );
			}
		}
		
		$ctime = time ();
		$stime = date ( 'H:i:s', strtotime ( $ctime ) );
		
		/*$sqlshift = "SELECT  * FROM `" . DB_PREFIX . "shift` where shift_starttime > '" . $stime . "' and shift_endtime < '" . $stime . "' ";
		$shifts = $this->db->query ( $sqlshift );
		
		if (! empty ( $shifts->row ['shift_id'] )) {
			$id = $shifts->row ['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $updateshift );
		}*/
		$this->load->model ( 'notes/notes' );
		$shift_info = $this->model_notes_notes->getShiftColor ( $stime, $facilities_id );
		if(!empty($shift_info['shift_id'])){
			$id = $shift_info['shift_id'];
			
			$updateshift = "UPDATE `" . DB_PREFIX . "notes` SET shift_id = '" . $id . "' WHERE notes_id = '" . (int) $notes_id . "' ";
			$this->db->query($updateshift);
		}
		
		/*
		 * $this->load->model('createtask/createtask');
		 * $tasktype_info =
		 * $this->model_createtask_createtask->gettasktyperowByName($result['tasktype']);
		 * $tasktype_id = $tasktype_info['task_id'];
		 *
		 * if($tasktype_id == '11'){
		 * if($result['task_form_id'] != null && $result['task_form_id'] !=
		 * "0"){
		 * $this->load->model('setting/bedchecktaskform');
		 * $this->load->model('setting/tags');
		 *
		 * $bedcheckinfos =
		 * $this->model_setting_bedchecktaskform->getruleModule($result['task_form_id']);
		 *
		 * if($bedcheckinfos['bctf_module'] != null &&
		 * $bedcheckinfos['bctf_module'] != ""){
		 * foreach($bedcheckinfos['bctf_module'] as $bedcheckinfo){
		 * $tag_info =
		 * $this->model_setting_tags->getTagsbyNotescurrencyid($bedcheckinfo['locations_id']);
		 * //var_dump($tag_info);
		 *
		 * $this->load->model('notes/notes');
		 * if($data['facilitytimezone'] != null && $data['facilitytimezone'] !=
		 * ""){
		 * date_default_timezone_set($data['facilitytimezone']);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }else{
		 * $timezone_name = $this->customer->isTimezone();
		 * date_default_timezone_set($timezone_name);
		 * $update_date = date('Y-m-d H:i:s', strtotime('now'));
		 * }
		 *
		 * $this->model_notes_notes->updateNotesTag($tag_info['emp_tag_id'],
		 * $notes_id,$tag_info['tags_id'], $update_date);
		 * }
		 * }
		 *
		 * }
		 *
		 * }
		 */
		
		// if($result['recurrence'] == 'hourly'){
		
		if ($data ['facilitytimezone'] != null && $data ['facilitytimezone'] != "") {
			date_default_timezone_set ( $data ['facilitytimezone'] );
			$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		} else {
			$timezone_name = $this->customer->isTimezone ();
			date_default_timezone_set ( $timezone_name );
			$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		}
		
		$details1 = "SELECT parent_id FROM `" . DB_PREFIX . "createtask` where parent_id = '0' and recurrence = '" . $result ['recurrence'] . "' and facilityId = '" . $facilities_id . "' and description = '" . $this->db->escape ( $result ['description'] ) . "' ";
		$queryt = $this->db->query ( $details1 );
		
		if ($queryt->row ['parent_id'] == '0' && $queryt->row ['parent_id'] != null) {
			$sql213 = "update `" . DB_PREFIX . "createtask` set parent_id = '" . $notes_id . "' where recurrence = '" . $result ['recurrence'] . "' and facilityId = '" . $facilities_id . "' and description = '" . $this->db->escape ( $result ['description'] ) . "' ";
			$this->db->query ( $sql213 );
		}
		
		// }
		
		$details = "SELECT notes_id, parent_id FROM `" . DB_PREFIX . "notes` where notes_id = '" . $result ['parent_id'] . "'  ";
		$query = $this->db->query ( $details );
		// echo "<hr>";
		
		if ($query->row) {
			if ($query->row ['parent_id'] < 0) {
				$parent_id = $query->row ['parent_id'];
			} else {
				$parent_id = $query->row ['notes_id'];
			}
		} else {
			$parent_id = $notes_id;
			
			// $sql2 = "update `" . DB_PREFIX . "notes` set parent_id =
			// '".$parent_id."' where notes_id='".$parent_id."' ";
			// $this->db->query($sql2);
		}
		
		$sql21 = "update `" . DB_PREFIX . "notes` set parent_id = '" . $parent_id . "', notes_conut ='0' where notes_id = '" . $notes_id . "' ";
		$this->db->query ( $sql21 );
		
		/*
		 * $details = "SELECT notes_id, parent_id FROM `" . DB_PREFIX . "notes`
		 * where notes_id = '".$task_info['parent_id']."' ";
		 * $query = $this->db->query($details);
		 *
		 * if($query->row){
		 * if ($query->row['parent_id'] < 0 ) {
		 * $parent_id = $query->row['parent_id'];
		 * }else{
		 * $parent_id = $query->row['notes_id'];
		 * }
		 * }else{
		 * $parent_id = $notes_id;
		 *
		 * $sql2 = "update `" . DB_PREFIX . "notes` set parent_id =
		 * '".$parent_id."' where notes_id='".$parent_id."' ";
		 * $this->db->query($sql2);
		 * }
		 */
		
		$barray = array ();
		
		$barray ['notes_id'] = $notes_id;
		$barray ['task_id'] = $result ['id'];
		$barray ['date_added'] = $date_added3;
		$barray ['note_date'] = $noteDate;
		
		$barray ['user_id'] = SYSTEM_GENERATED;
		$barray ['note_type'] = $data ['note_type'];
		$barray ['notes_type'] = $data ['notes_type'];
		$barray ['notes_pin'] = SYSTEM_GENERATED_PIN;
		$barray ['phone_device_id'] = $data ['phone_device_id'];
		$barray ['is_android'] = $data ['is_android'];
		
		$barray ['task_type'] = $task_type;
		$barray ['notetime'] = $noteDateTime;
		$barray ['task_time'] = $notetasktime;
		$barray ['notes_description'] = $description;
		$barray ['facilities_id'] = $facilities_id;
		$barray ['customlistvalues_id'] = $customlistvalues_id;
		$barray ['recurrence'] = $task_info ['recurrence'];
		$barray ['task_date'] = $task_info ['date_added'];
		$barray ['task_group_by'] = $task_info ['task_group_by'];
		$barray ['parent_id'] = $task_info ['parent_id'];
		$barray ['assign_to'] = $task_info ['assign_to'];
		
		$task_info ['is_back_date'] = $result ['is_back_date'];
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'insertTaskLists', $barray, 'query' );
		if ($result ['task_action'] == '0' || $result ['task_action'] == '2' || $result ['task_action'] == '3' || $result ['task_action'] == '4') {
			
			$this->checkNotification ( $task_info, $facilities_id, $parent_id );
		}
		
		if ($this->config->get ( 'config_realtime_data' ) == '1') {
			$this->load->model ( 'api/realtime' );
			$realdata = array ();
			$realdata ['facilities_id'] = $facilities_id;
			$realdata ['notes_id'] = $notes_id;
			$this->model_api_realtime->addrealtime ( $realdata );
		}
		
		return $notes_id;
	}
	public function updateIncomtaskNote($task_id) {
		$sql = "update `" . DB_PREFIX . "createtask` set taskadded = '4' where id='" . $task_id . "'";
		$this->db->query ( $sql );
	}
	public function updateForm($notes_id, $checklist_status, $ttstatus, $update_date) {
		$sql = "update `" . DB_PREFIX . "notes` set checklist_status = '" . $checklist_status . "',status= '" . $ttstatus . "',update_date= '" . $update_date . "', notes_conut='0' where notes_id='" . $notes_id . "'";
		$this->db->query ( $sql );
	}
	public function getnotesInfo2($task_id) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE id = '" . $task_id . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function deteteIncomTask($facilities_id) {
		// $sql = "DELETE FROM`" . DB_PREFIX . "createtask` where taskadded !=
		// '0' and facilityId = '".$facilities_id."' and rules_task = '0' ";
		$sql = "DELETE FROM`" . DB_PREFIX . "createtask` where taskadded != '0' and facilityId = '" . $facilities_id . "' ";
		$this->db->query ( $sql );
	}
	public function checkNotification($data, $facilities_id, $notes_id) {
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		date_default_timezone_set ( $timezone_info ['timezone_value'] );
		
		// var_dump($data['is_back_date']);
		
		$is_back_date = $data ['is_back_date'];
		
		// die;
		
		if ($data ['tasktype'] != '' && $data ['tasktype'] != "") {
			$this->load->model ( 'createtask/createtask' );
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $data ['tasktype'], $facilities_id );
			$tasktype_id = $tasktype_info ['task_id'];
			$tasktypetype = $tasktype_info ['type'];
		}
		// var_dump($tasktype_info['is_buffer']);
		
		if ($tasktypetype == '5') {
			
			$date_d = date ( 'Y-m-d' );
			$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
			
			$this->load->model ( 'createtask/createtask' );
			$travelWaypoint1s = $this->model_createtask_createtask->gettravelWaypoints ( $data ['id'] );
			if ($travelWaypoint1s != null && $travelWaypoint1s != "") {
				
				$newadd = '&waypoints=optimize:true|';
				$newadd22 = "";
				$numItems = count ( $travelWaypoint1s ) - 1;
				
				$ik = 0;
				
				foreach ( $travelWaypoint1s as $location ) {
					if ($ik == $numItems) {
						$newadd .= str_replace ( ' ', '+', $location ['locations_address'] );
						$newadd22 .= $location ['latitude'] . ',' . $location ['longitude'] . '|';
					} else {
						$newadd .= str_replace ( ' ', '+', $location ['locations_address'] ) . '|';
						$newadd22 .= $location ['latitude'] . ',' . $location ['longitude'] . '|';
					}
					
					$ik ++;
				}
			}
			
			$url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . str_replace ( ' ', '+', $data ['pickup_locations_address'] ) . "&destination=" . str_replace ( ' ', '+', $data ['dropoff_locations_address'] ) . "" . $newadd . "&key=" . GOOGLE_API_KEY . "";
			
			// var_dump($url);
			
			/*
			 * $ch = curl_init();
			 * curl_setopt($ch, CURLOPT_URL, $url);
			 * curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 * curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			 * curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			 * curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			 * $response = curl_exec($ch);
			 * curl_close($ch);
			 * $response_all = json_decode($response);
			 *
			 * //var_dump($response_all);
			 * $distance = $response_all->routes[0]->legs[0]->distance->text;
			 * $distancev = $response_all->routes[0]->legs[0]->distance->value;
			 * //var_dump($distance);
			 * //var_dump($distancev);
			 *
			 * //$duration = $response_all->routes[0]->legs[0]->duration->text;
			 * //$durationv =
			 * $response_all->routes[0]->legs[0]->duration->value;
			 * //var_dump($duration);
			 * //var_dump($durationv);
			 * //echo "<hr>";
			 *
			 * //var_dump($response_all);
			 * $durationv = 0;
			 * foreach($response_all->routes[0]->legs as $a){
			 * //var_dump($a->duration->text);
			 *
			 * $duration .= $a->duration->text.',';
			 * $durationv = $durationv + $a->duration->value;
			 * //echo "<hr>";
			 * }
			 */
			
			$geo = file_get_contents ( $url );
			
			$response_all = json_decode ( $geo, true );
			
			// echo "<hr>";
			
			// var_dump($response_all['routes'][0]['legs'][0]['distance']);
			$distance = $response_all ['routes'] [0] ['legs'] [0] ['distance'] ['text'];
			$distancev = $response_all ['routes'] [0] ['legs'] [0] ['distance'] ['value'];
			// var_dump($distance);
			// var_dump($distancev);
			
			$durationv = 0;
			$durationv1 = 0;
			
			foreach ( $response_all ['routes'] [0] ['legs'] as $a ) {
				
				// var_dump($i);
				
				// var_dump($a['steps']);
				// var_dump($a['duration']);
				
				/*
				 * foreach($a['steps'] as $b){
				 * $duration .= $b['duration']['text'].',';
				 * $durationv1 = $durationv1 + $b['duration']['value'];
				 * }
				 */
				
				$duration .= $a ['duration'] ['text'] . ',';
				$durationv = $durationv + $a ['duration'] ['value'] + $durationv1;
				$durationv = $durationv + $durationv1;
				// echo "<hr>";
			}
			
			if ($durationv > 0) {
				
				if ($tasktype_info ['is_buffer'] != null && $tasktype_info ['is_buffer'] != "0") {
					$is_buffer = $tasktype_info ['is_buffer'];
				} else {
					$is_buffer = '30';
				}
				
				$snooze_time7 = ceil ( $durationv / 60 );
				$snooze_time722 = $snooze_time7 + $is_buffer;
				
				$time = date ( 'H:i:s' );
				$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time722 . " minutes", strtotime ( $time ) ) );
				
				$time2 = date ( "H:i:s", strtotime ( $dropoff_locations_time ) );
				$taskDateend = $taskDate1w . ' ' . $time2;
			} else {
				
				$time2 = date ( "H:i:s", strtotime ( $data ['dropoff_locations_time'] ) );
				$taskDateend = $taskDate1w . ' ' . $time2;
				
				$dropoff_locations_time = $data ['dropoff_locations_time'];
			}
			
			$taskcontent = 'End ' . $data ['description'];
			
			$sqls23d = "SELECT * FROM `" . DB_PREFIX . "createtask` where is_transport = '1' and id= '" . $data ['id'] . "' ";
			$query4d = $this->db->query ( $sqls23d );
			
			if ($query4d->num_rows == 0) {
				
				$sql2 = "UPDATE  " . DB_PREFIX . "createtask SET end_recurrence_date = '" . $taskDateend . "', description = '" . $this->db->escape ( $taskcontent ) . "', task_time = '" . $this->db->escape ( $dropoff_locations_time ) . "', is_transport = '1', response = '" . $this->db->escape ( serialize ( $response_all ) ) . "', distance_text = '" . $this->db->escape ( $distance ) . "', distance_value = '" . $this->db->escape ( $distancev ) . "', duration_text = '" . $this->db->escape ( $duration ) . "', duration_value = '" . $this->db->escape ( $durationv ) . "' where id= '" . $data ['id'] . "' ";
				$this->db->query ( $sql2 );
			}
			
			$num_rowsp = $query4d->num_rows;
		} else {
			$num_rowsp = '2';
		}
		
		// var_dump($data['end_recurrence_date']);
		
		$data = $this->gettaskrow ( $data ['id'] );
		
		// var_dump($data);
		
		 //var_dump($data['end_recurrence_date']);
		
		if ($data ['end_recurrence_date'] != "0000-00-00 00:00:00") {
			// var_dump($data['end_recurrence_date']);
			// echo "<hr>";
			
			$end_recurrence_date = strtotime ( $data ['end_recurrence_date'] );
			
			// var_dump($end_recurrence_date);
			
			$currentdate = strtotime ( date ( 'd-m-Y' ) );
			
			$secs = $end_recurrence_date - $currentdate; // == <seconds between
			                                             // the two times>
			$days = ( int ) ($secs / 86400);
			
			if ($data ['task_action'] != '1' && $data ['task_action'] != '0') {
				$days = '1';
			}
			
			if ($data ['end_perpetual_task'] != '2') {
				
				// var_dump($num_rowsp);
				if ($num_rowsp == 0) {
					$days = '1';
				} elseif ($data ['formrules_id'] > 0) {
					
					$sqlf = "SELECT * FROM `" . DB_PREFIX . "formrules` WHERE rules_id = '" . $data ['formrules_id'] . "'";
					$query = $this->db->query ( $sqlf );
					$form_rule_info = $query->row;
					if ($form_rule_info != null && $form_rule_info != "") {
						if ($form_rule_info ['rules_operation_recurrence'] == '1') {
							$recurnce_m = $form_rule_info ['recurnce_m'];
							
							if ($recurnce_m > $data ['recurnce_m']) {
								$days = '1';
							}
						}
						if ($form_rule_info ['rules_operation_recurrence'] == '3') {
							$fend_recurrence_date = $form_rule_info ['end_recurrence_date'];
							
							$fend_recurrence_date111 = date ( 'Y-m-d', strtotime ( $fend_recurrence_date ) );
							$end_recurrence_date111 = date ( 'Y-m-d', strtotime ( $data ['end_recurrence_date'] ) );
							
							if ($fend_recurrence_date111 != $end_recurrence_date111) {
								$days = '1';
							}
						}
					}
				} else if($data ['recurrence'] == 'hourly'){
					
					if($data ['task_time'] < $data ['endtime']){
						$days = 1;
					}else{
						$days = $days;
					}
					
					
				} else {
					$days = $days;
				}
				
				if ($days > 0) {
					// $data = array();
					
					// var_dump($tasktype_info['is_task_rule']);
					
					if ($tasktype_info ['is_task_rule'] == '1') {
						$is_task_time = date ( 'H:i:s', strtotime ( 'now' ) );
					} else {
						
						$is_task_time = $data ['task_time'];
						
						
					}
					if ($data ['recurrence'] == 'weekly') {
						
						// var_dump($data['task_date']);
						
						$dayName = date ( 'l', strtotime ( $data ['task_date'] ) );
						
						// var_dump($dayName);
						// echo "<hr>";
						
						// var_dump($taskDate);
						$d = strtotime ( $data ['task_date'] );
						
						// var_dump($weekd);
						
						$end_week = strtotime ( "next " . $data ['recurnce_week'], $d );
						
						$end = date ( "Y-m-d", $end_week );
						$time = date ( "H:i:s", strtotime ( $data ['task_date'] ) );
						
						$taskDate1 = $end . ' ' . $time;
						
						$taskDate12 = strtotime ( $taskDate1 );
						
						$end_recurrence_date = $data ['end_recurrence_date'];
						
						$end_recurrence_date2 = strtotime ( $end_recurrence_date );
						
						if ($taskDate12 > $end_recurrence_date2) {
							$end_recurrence_date2 = $taskDate1;
						} else {
							$end_recurrence_date2 = $end_recurrence_date;
						}
						
						$task_time = $is_task_time;
					} else 

					if ($data ['recurrence'] == 'monthly') {
						
						$dayName = date ( 'd', strtotime ( $data ['task_date'] ) );
						
						// var_dump($dayName);
						
						$daymonth = '1';
						
						// var_dump($daymonth);
						// var_dump($taskDate);
						// echo "<br>";
						
						$tm = date ( 'm', strtotime ( '+' . $daymonth . ' month', strtotime ( $data ['task_date'] ) ) );
						$ty = date ( 'Y', strtotime ( $data ['task_date'] ) );
						
						// var_dump($tdate);
						
						$end = $ty . '-' . $tm . '-' . $data ['recurnce_day'];
						$time = date ( "H:i:s", strtotime ( $data ['task_date'] ) );
						$taskDate1 = $end . ' ' . $time;
						
						$taskDate12 = strtotime ( $taskDate1 );
						
						$end_recurrence_date = $data ['end_recurrence_date'];
						
						$end_recurrence_date2 = strtotime ( $end_recurrence_date );
						
						if ($taskDate12 > $end_recurrence_date2) {
							$end_recurrence_date2 = $taskDate1;
						} else {
							$end_recurrence_date2 = $end_recurrence_date;
						}
						
						// $task_date = date('Y-m-d H:i:s', strtotime(' +1
						// years',strtotime($data['task_date'])));
						
						$task_time = $is_task_time;
					} elseif ($data ['recurrence'] == 'Perpetual') {
						
						if ($data ['is_pause'] == '1') {
							
							if ($data ['pause_time'] > date ( 'H:i:s' )) {
								
								$end_recurrence_date2 = $data ['end_recurrence_date'];
								$task_time = $data ['pause_time'];
								$taskDate1 = $data ['pause_date'] . ' ' . $data ['pause_time'];
							} else {
								
								$end_recurrence_date2 = $data ['end_recurrence_date'];
								
								$recurnce_hrly_perpetual = $data ['recurnce_hrly_perpetual'];
								
								// var_dump($recurnce_hrly_perpetual);
								
								$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $recurnce_hrly_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
								
								$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
								
								$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( $taskstarttime ) );
								
								$date_d = date ( 'Y-m-d' );
								/*
								 * $taskstarttime = date('H', strtotime(' +' . $recurnce_hrly_perpetual . ' minutes', strtotime($data['task_time'])));
								 *
								 * if ($taskstarttime >= "00:00:00") {
								 *
								 * $taskDate1w = date('Y-m-d', strtotime(' +1 days', strtotime($date_d)));
								 * $task_time = date('H:i:s', strtotime(' +' . $recurnce_hrly_perpetual . ' minutes', strtotime($data['task_time'])));
								 *
								 * $taskDate1 = $taskDate1w . ' ' . $task_time;
								 * }else{
								 *
								 * $taskDate1w = date('Y-m-d', strtotime(' +0 days', strtotime($date_d)));
								 * $task_time = date('H:i:s', strtotime(' +' . $recurnce_hrly_perpetual . ' minutes', strtotime($data['task_time'])));
								 *
								 * $taskDate1 = $taskDate1w . ' ' . $task_time;
								 * }
								 */
							}
						} else {
							
							// var_dump($is_back_date);
							
							$end_recurrence_date2 = $data ['end_recurrence_date'];
							
							$recurnce_hrly_perpetual = $data ['recurnce_hrly_perpetual'];
							
							// var_dump($recurnce_hrly_perpetual);
							
							$date_d = date ( 'Y-m-d' );
							// var_dump($data['task_time']);
							$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $recurnce_hrly_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
							
							// /var_dump($taskstarttime);
							
							$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
							
							if ($is_back_date == '1') {
								$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
								$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly_perpetual . ' minutes', strtotime ( $is_task_time ) ) );
								
								$taskDate1 = $taskDate1w . ' ' . $task_time;
							} else {
								$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( $taskstarttime ) );
							}
							
							// var_dump($taskstarttime);
							/*
							 * if ($taskstarttime <= "00:00:00") {
							 * echo 222;
							 * $taskDate1w = date('Y-m-d', strtotime(' +1 days', strtotime($date_d)));
							 * $task_time = date('H:i:s', strtotime(' +' . $recurnce_hrly_perpetual . ' minutes', strtotime($data['task_time'])));
							 *
							 * $taskDate1 = $taskDate1w . ' ' . $task_time;
							 * }else{
							 * echo 333;
							 * $taskDate1w = date('Y-m-d', strtotime(' +0 days', strtotime($date_d)));
							 * $task_time = date('H:i:s', strtotime(' +' . $recurnce_hrly_perpetual . ' minutes', strtotime($data['task_time'])));
							 *
							 * $taskDate1 = $taskDate1w . ' ' . $task_time;
							 * }
							 */
						}
					} else if ($data ['recurrence'] == 'yearly') {
						$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 years', strtotime ( $data ['task_date'] ) ) );
						
						$end_recurrence_date2 = $data ['end_recurrence_date'];
						
						$task_time = $is_task_time;
					} else if ($data ['recurrence'] == 'hourly') {
						
						if ($data ['recurnce_hrly_recurnce'] == "Daily") {
							
							if ($data ['weekly_interval'] != null && $data ['weekly_interval'] != "") {
								$intervalday = explode ( ',', $data ['weekly_interval'] );
								
								//var_dump($intervalday);
								
								$current_day = date ( 'l' );
								
								$task_date1 = date('Y-m-d',strtotime($data ['task_date'])); 
								$end_recurrence_date1 = date('Y-m-d',strtotime($data ['end_recurrence_date'])); 
								
								
								$newtask = $task_date1.' '.$data ['task_time'];
								$newtaskend = $end_recurrence_date1.' '.$data ['endtime'];
								
								//var_dump($newtask);
								//var_dump($newtaskend);
								
								if (in_array ( $current_day, $intervalday )) {
									if($newtask < $newtaskend){
								
										$recurnce_hrly = $data ['recurnce_hrly'];
										
										$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $is_task_time ) ) );
										
										$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $is_task_time ) ) );
										
										$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( $taskstarttime ) );
										
										$end_recurrence_date2 = $data ['end_recurrence_date'];
										
									}else{
										
									
										$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 days', strtotime ( $data ['task_date'] ) ) );
											
										$end_recurrence_date2 = $data ['end_recurrence_date'];
											
										$task_time = $is_task_time;
									}
								}else{
									if($data ['task_time'] < $data ['endtime']){
								
										$recurnce_hrly = $data ['recurnce_hrly'];
										
										$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $is_task_time ) ) );
										
										$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $is_task_time ) ) );
										
										$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( $taskstarttime ) );
										
										$end_recurrence_date2 = $data ['end_recurrence_date'];
										
									}else{
										
									
										$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 days', strtotime ( $data ['task_date'] ) ) );
											
										$end_recurrence_date2 = $data ['end_recurrence_date'];
											
										$task_time = $is_task_time;
									}
								}
								
								/*if (in_array ( $current_day, $intervalday )) {
									
									$next_week_date = date ( 'Y-m-d', strtotime ( "next " . $current_day . "" ) );
									
									$taskDate1_time = date ( 'H:i:s', strtotime ( $data ['task_date'] ) );
									$taskDate1 = $next_week_date . ' ' . $taskDate1_time;
									
									$end_recurrence_date2 = $data ['end_recurrence_date'];
									
									$task_time = $is_task_time;
								} else {
									$current_day = date ( 'l' );
									$next_week_date = date ( 'Y-m-d', strtotime ( "next " . $current_day . "" ) );
									$taskDate1_time = date ( 'H:i:s', strtotime ( $data ['task_date'] ) );
									$taskDate1 = $next_week_date . ' ' . $taskDate1_time;
									
									$end_recurrence_date2 = $data ['end_recurrence_date'];
									
									$task_time = $is_task_time;
								}*/
							} else {
								$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 days', strtotime ( $data ['task_date'] ) ) );
								
								$end_recurrence_date2 = $data ['end_recurrence_date'];
								
								$task_time = $is_task_time;
							}
						} else {
							
							if($data ['task_time'] < $data ['endtime']){
								
								$recurnce_hrly = $data ['recurnce_hrly'];
								
								
								if ($data ['is_pause'] == '1') {
									$is_task_time = $data ['pause_time'];
									$recurnce_hrly1 = 0;
									$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $recurnce_hrly1 . ' minutes', strtotime ( $is_task_time ) ) );
								
									$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly1 . ' minutes', strtotime ( $is_task_time ) ) );
								}else{
									$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $is_task_time ) ) );
								
									$task_time = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $is_task_time ) ) );
								}
								
								
								
								
								$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( $taskstarttime ) );
								
								$end_recurrence_date2 = $data ['end_recurrence_date'];
								
							}else{
								
							
								$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 days', strtotime ( $data ['task_date'] ) ) );
									
								$end_recurrence_date2 = $data ['end_recurrence_date'];
									
								$task_time = $is_task_time;
							}
							
							
							
						}
					} else if ($data ['recurrence'] == 'daily') {
						
						if ($data ['weekly_interval'] != null && $data ['weekly_interval'] != "") {
							$intervalday = explode ( ',', $data ['weekly_interval'] );
							
							$current_day = date ( 'l' );
							
							if (in_array ( $current_day, $intervalday )) {
								
								$next_week_date = date ( 'Y-m-d', strtotime ( "next " . $current_day . "" ) );
								
								$taskDate1_time = date ( 'H:i:s', strtotime ( $data ['task_date'] ) );
								$taskDate1 = $next_week_date . ' ' . $taskDate1_time;
								
								$end_recurrence_date2 = $data ['end_recurrence_date'];
								
								$task_time = $is_task_time;
							} else {
								$current_day = date ( 'l' );
								$next_week_date = date ( 'Y-m-d', strtotime ( "next " . $current_day . "" ) );
								$taskDate1_time = date ( 'H:i:s', strtotime ( $data ['task_date'] ) );
								$taskDate1 = $next_week_date . ' ' . $taskDate1_time;
								
								$end_recurrence_date2 = $data ['end_recurrence_date'];
								
								$task_time = $is_task_time;
							}
						} else {
							
							$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 days', strtotime ( $data ['task_date'] ) ) );
							
							$end_recurrence_date2 = $data ['end_recurrence_date'];
							
							$task_time = $is_task_time;
						}
					} else {
						
						if ($tasktypetype == '5') {
							$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +0 days', strtotime ( $data ['task_date'] ) ) );
						} else {
							
							if ($result ['task_action'] == '2' || $result ['task_action'] == '3' || $result ['task_action'] == '4') {
								
								if ($data ['is_pause'] == '1') {
									
									if ($data ['pause_time'] > date ( 'H:i:s' )) {
										
										$end_recurrence_date2 = $data ['end_recurrence_date'];
										$is_task_time = $data ['pause_time'];
										$taskDate1 = date ( 'Y-m-d', strtotime ( $data ['pause_date'] ) ) . ' ' . $data ['pause_time'];
									} else {
										$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +0 days', strtotime ( $data ['task_date'] ) ) );
									}
								} else {
									$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +0 days', strtotime ( $data ['task_date'] ) ) );
								}
							} else {
								$taskDate1 = date ( 'Y-m-d H:i:s', strtotime ( ' +1 days', strtotime ( $data ['task_date'] ) ) );
							}
						}
						
						$end_recurrence_date2 = $data ['end_recurrence_date'];
						
						$task_time = $is_task_time;
						
						if ($data ['formrules_id'] > 0) {
							// echo '222';
							
							$sqlf = "SELECT * FROM `" . DB_PREFIX . "formrules` WHERE rules_id = '" . $data ['formrules_id'] . "'";
							$query = $this->db->query ( $sqlf );
							$form_rule_info = $query->row;
							if ($form_rule_info != null && $form_rule_info != "") {
								
								if ($form_rule_info ['rules_operation_recurrence'] == '1') {
									$recurnce_m = $form_rule_info ['recurnce_m'];
									
									if ($recurnce_m > $data ['recurnce_m']) {
										// var_dump($recurnce_m);
										
										$recurnce_m1 = $data ['recurnce_m'] + 2;
										
										$sqlfa = "SELECT * FROM `" . DB_PREFIX . "formrules_alert` WHERE rules_id = '" . $data ['formrules_id'] . "' and task_random_id = '" . $data ['task_random_id'] . "'";
										$query1 = $this->db->query ( $sqlfa );
										$form_rulea_info = $query1->row;
										
										if ($form_rulea_info != null && $form_rulea_info != "") {
											
											if ($form_rulea_info ['form_due_date'] == 'Month') {
												
												$task_date = $data ['task_date'];
												$form_due_date_after = $form_rulea_info ['form_due_date_after'];
												
												$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
												
												$task_time = $is_task_time;
											}
											if ($form_rulea_info ['form_due_date'] == 'Days') {
												
												$task_date = $data ['task_date'];
												$form_due_date_after = $form_rulea_info ['form_due_date_after'];
												
												$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $task_date ) ) . " +" . $form_due_date_after . " day" ) );
												
												$task_time = $is_task_time;
											}
											if ($form_rulea_info ['form_due_date'] == 'Hours') {
												
												$date_d = date ( 'Y-m-d' );
												
												$form_due_date_after = $form_rulea_info ['form_due_date_after'] * 60;
												
												$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
												$task_time = date ( 'H:i:s', strtotime ( ' +' . $form_due_date_after . ' minutes', strtotime ( $is_task_time ) ) );
												
												$taskDate1 = $taskDate1w . ' ' . $task_time;
											}
											if ($form_rulea_info ['form_due_date'] == 'Minutes') {
												
												$date_d = date ( 'Y-m-d' );
												$form_due_date_after = $form_rulea_info ['form_due_date_after'];
												
												$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
												$task_time = date ( 'H:i:s', strtotime ( ' +' . $form_due_date_after . ' minutes', strtotime ( $is_task_time ) ) );
												$taskDate1 = $taskDate1w . ' ' . $task_time;
											}
											if ($form_rulea_info ['form_due_date'] == 'is submitted') {
												$date_d = date ( 'Y-m-d' );
												$form_due_date_after = $form_rulea_info ['form_due_date_after'];
												
												$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
												$task_time = date ( 'H:i:s', strtotime ( ' +' . $form_due_date_after . ' minutes', strtotime ( $is_task_time ) ) );
												
												$taskDate1 = $taskDate1w . ' ' . $task_time;
											}
											
											$end_recurrence_date2 = $taskDate1;
										}
									}
								}
								if ($form_rule_info ['rules_operation_recurrence'] == '3') {
									$end_recurrence_date = $form_rule_info ['end_recurrence_date'];
									$end_recurrence_date2 = $end_recurrence_date;
									
									$sqlfa = "SELECT * FROM `" . DB_PREFIX . "formrules_alert` WHERE rules_id = '" . $data ['formrules_id'] . "' and task_random_id = '" . $data ['task_random_id'] . "'";
									$query1 = $this->db->query ( $sqlfa );
									$form_rulea_info = $query1->row;
									
									if ($form_rulea_info != null && $form_rulea_info != "") {
										
										if ($form_rulea_info ['form_due_date'] == 'Month') {
											
											$task_date = $data ['task_date'];
											$form_due_date_after = $form_rulea_info ['form_due_date_after'];
											
											$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
											
											$task_time = $is_task_time;
										}
										if ($form_rulea_info ['form_due_date'] == 'Days') {
											
											$task_date = $data ['task_date'];
											$form_due_date_after = $form_rulea_info ['form_due_date_after'];
											
											$taskDate1 = date ( "Y-m-d H:i:s", strtotime ( date ( "Y-m-d H:i:s", strtotime ( $task_date ) ) . " +" . $form_due_date_after . " day" ) );
											
											$task_time = $is_task_time;
										}
										if ($form_rulea_info ['form_due_date'] == 'Hours') {
											
											$date_d = date ( 'Y-m-d' );
											
											$form_due_date_after = $form_rulea_info ['form_due_date_after'] * 60;
											
											$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
											$task_time = date ( 'H:i:s', strtotime ( ' +' . $form_due_date_after . ' minutes', strtotime ( $is_task_time ) ) );
											
											$taskDate1 = $taskDate1w . ' ' . $task_time;
										}
										if ($form_rulea_info ['form_due_date'] == 'Minutes') {
											
											$date_d = date ( 'Y-m-d' );
											$form_due_date_after = $form_rulea_info ['form_due_date_after'];
											
											$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
											$task_time = date ( 'H:i:s', strtotime ( ' +' . $form_due_date_after . ' minutes', strtotime ( $is_task_time ) ) );
											$taskDate1 = $taskDate1w . ' ' . $task_time;
										}
										if ($form_rulea_info ['form_due_date'] == 'is submitted') {
											$date_d = date ( 'Y-m-d' );
											$form_due_date_after = $form_rulea_info ['form_due_date_after'];
											
											$taskDate1w = date ( 'Y-m-d', strtotime ( ' +0 days', strtotime ( $date_d ) ) );
											$task_time = date ( 'H:i:s', strtotime ( ' +' . $form_due_date_after . ' minutes', strtotime ( $is_task_time ) ) );
											
											$taskDate1 = $taskDate1w . ' ' . $task_time;
										}
									}
									// var_dump($data['task_time']);
								}
							}
						}
					}
					
					if ($data ['form_task_creation'] == '1') {
						$checklist = '';
						$attachement_form = '0';
						$tasktype_form_id = '0';
					} else {
						$checklist = $data ['checklist'];
						$attachement_form = $data ['attachement_form'];
						$tasktype_form_id = $data ['tasktype_form_id'];
					}
					
					if ($data ['linked_id'] > 0) {
						$linked_id = $data ['linked_id'];
					} else {
						$linked_id = $notes_id;
					}
					
					$sql = "INSERT INTO `" . DB_PREFIX . "createtask` SET facilityId = '" . $facilities_id . "', task_date = '" . $this->db->escape ( $taskDate1 ) . "', task_time = '" . $this->db->escape ( $task_time ) . "', date_added = '" . $this->db->escape ( $taskDate1 ) . "', description = '" . $this->db->escape ( $data ['description'] ) . "', assign_to = '" . $this->db->escape ( $data ['assign_to'] ) . "', recurrence = '" . $this->db->escape ( $data ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $data ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $end_recurrence_date2 . "', endtime = '" . $data ['endtime'] . "', tasktype = '" . $this->db->escape ( $data ['tasktype'] ) . "', recurnce_day = '" . $this->db->escape ( $data ['recurnce_day'] ) . "', recurnce_month = '" . $this->db->escape ( $data ['recurnce_month'] ) . "', recurnce_week = '" . $this->db->escape ( $data ['recurnce_week'] ) . "',taskadded = '0', task_alert = '" . $this->db->escape ( $data ['task_alert'] ) . "', alert_type_sms = '" . $this->db->escape ( $data ['alert_type_sms'] ) . "', alert_type_notification = '" . $this->db->escape ( $data ['alert_type_notification'] ) . "', alert_type_email = '" . $this->db->escape ( $data ['alert_type_email'] ) . "', checklist = '" . $this->db->escape ( $checklist ) . "'

					, snooze_time = '" . $this->db->escape ( $data ['snooze_time'] ) . "', snooze_dismiss = '0', rules_task = '0', message_sid = '', send_sms = '0', send_email = '0', send_notification = '0', task_form_id = '" . $this->db->escape ( $data ['task_form_id'] ) . "', tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "', pickup_facilities_id = '" . $this->db->escape ( $data ['pickup_facilities_id'] ) . "', pickup_locations_address = '" . $this->db->escape ( $data ['pickup_locations_address'] ) . "', pickup_locations_time = '" . $this->db->escape ( $data ['pickup_locations_time'] ) . "', pickup_locations_latitude = '" . $this->db->escape ( $data ['pickup_locations_latitude'] ) . "', pickup_locations_longitude = '" . $this->db->escape ( $data ['pickup_locations_longitude'] ) . "', dropoff_facilities_id = '" . $this->db->escape ( $data ['dropoff_facilities_id'] ) . "', dropoff_locations_address = '" . $this->db->escape ( $data ['dropoff_locations_address'] ) . "', dropoff_locations_time = '" . $this->db->escape ( $data ['dropoff_locations_time'] ) . "', dropoff_locations_latitude = '" . $this->db->escape ( $data ['dropoff_locations_latitude'] ) . "', dropoff_locations_longitude = '" . $this->db->escape ( $data ['dropoff_locations_longitude'] ) . "', transport_tags = '" . $this->db->escape ( $data ['transport_tags'] ) . "', locations_id = '" . $this->db->escape ( $data ['locations_id'] ) . "', task_complettion = '0', device_id = '', customs_forms_id = '" . $this->db->escape ( $data ['customs_forms_id'] ) . "', emp_tag_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "', medication_tags = '" . $this->db->escape ( $data ['medication_tags'] ) . "', completion_alert = '" . $this->db->escape ( $data ['completion_alert'] ) . "', completion_alert_type_sms = '" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "', completion_alert_type_email = '" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "', user_roles = '" . $this->db->escape ( $data ['user_roles'] ) . "', userids = '" . $this->db->escape ( $data ['userids'] ) . "', recurnce_hrly_perpetual = '" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "', due_date_time = '" . $this->db->escape ( $data ['due_date_time'] ) . "', task_status = '2', task_completed = '0', recurnce_hrly_recurnce = '" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "'
					
					, visitation_tags = '" . $this->db->escape ( $data ['visitation_tags'] ) . "'
					, visitation_tag_id = '" . $this->db->escape ( $data ['visitation_tag_id'] ) . "'
					, visitation_start_facilities_id = '" . $this->db->escape ( $data ['visitation_start_facilities_id'] ) . "'
					, visitation_start_address = '" . $this->db->escape ( $data ['visitation_start_address'] ) . "'
					, visitation_start_time = '" . $this->db->escape ( $data ['visitation_start_time'] ) . "'
					, visitation_start_address_latitude = '" . $this->db->escape ( $data ['visitation_start_address_latitude'] ) . "'
					, visitation_start_address_longitude = '" . $this->db->escape ( $data ['visitation_start_address_longitude'] ) . "'
					, visitation_appoitment_facilities_id = '" . $this->db->escape ( $data ['visitation_appoitment_facilities_id'] ) . "'
					, visitation_appoitment_address = '" . $this->db->escape ( $data ['visitation_appoitment_address'] ) . "'
					, visitation_appoitment_time = '" . $this->db->escape ( $data ['visitation_appoitment_time'] ) . "'
					, visitation_appoitment_address_latitude = '" . $this->db->escape ( $data ['visitation_appoitment_address_latitude'] ) . "'
					, visitation_appoitment_address_longitude = '" . $this->db->escape ( $data ['visitation_appoitment_address_longitude'] ) . "'
					, completed_times = '" . $this->db->escape ( $data ['completed_times'] ) . "'
					, completed_alert = '" . $this->db->escape ( $data ['completed_alert'] ) . "'
					, completed_late_alert = '" . $this->db->escape ( $data ['completed_late_alert'] ) . "'
					, incomplete_alert = '" . $this->db->escape ( $data ['incomplete_alert'] ) . "'
					, deleted_alert = '" . $this->db->escape ( $data ['deleted_alert'] ) . "'
					, is_transport = '1'
					, parent_id = '" . $this->db->escape ( $notes_id ) . "'
					
					, is_send_reminder = '" . $this->db->escape ( $data ['is_send_reminder'] ) . "'
					, attachement_form = '" . $this->db->escape ( $attachement_form ) . "'
					, tasktype_form_id = '" . $this->db->escape ( $tasktype_form_id ) . "'
					, task_group_by = '" . $this->db->escape ( $data ['task_group_by'] ) . "'
					, end_task = '" . $this->db->escape ( $data ['end_task'] ) . "'
					, formrules_id = '" . $this->db->escape ( $data ['formrules_id'] ) . "'
					, task_random_id = '" . $this->db->escape ( $data ['task_random_id'] ) . "'
					, form_due_date = '" . $this->db->escape ( $data ['form_due_date'] ) . "'
					, form_due_date_after = '" . $this->db->escape ( $data ['form_due_date_after'] ) . "'
					, recurnce_m = '" . $this->db->escape ( $recurnce_m1 ) . "'
					
					
					, phone_device_id = '" . $this->db->escape ( $data ['phone_device_id'] ) . "'
					, enable_requires_approval = '" . $this->db->escape ( $data ['enable_requires_approval'] ) . "'
					, approval_taskid = '" . $this->db->escape ( $data ['approval_taskid'] ) . "'
					, response = '" . $this->db->escape ( $data ['response'] ) . "'
					, distance_text = '" . $this->db->escape ( $data ['distance_text'] ) . "'
					, distance_value = '" . $this->db->escape ( $data ['distance_value'] ) . "'
					, duration_text = '" . $this->db->escape ( $data ['duration_text'] ) . "'
					
					, duration_value = '" . $this->db->escape ( $data ['duration_value'] ) . "'
					, iswaypoint = '" . $this->db->escape ( $data ['iswaypoint'] ) . "'
					, original_task_time = ''
					, form_rules_operation = '" . $this->db->escape ( $data ['form_rules_operation'] ) . "'
					, is_approval_required_forms_id = '" . $this->db->escape ( $data ['is_approval_required_forms_id'] ) . "'
					, is_approval_required_tags_id = '" . $this->db->escape ( $data ['is_approval_required_tags_id'] ) . "'
					, bed_check_location_ids = '" . $this->db->escape ( $data ['bed_check_location_ids'] ) . "'
					, is_android = '" . $this->db->escape ( $data ['is_android'] ) . "'
					, complete_status = '" . $this->db->escape ( $data ['complete_status'] ) . "'
					, weekly_interval = '" . $this->db->escape ( $data ['weekly_interval'] ) . "'
					, is_create_task = '" . $this->db->escape ( $data ['is_create_task'] ) . "'
					, unique_id = '" . $this->db->escape ( $data ['unique_id'] ) . "'
					, customer_key = '" . $this->db->escape ( $data ['customer_key'] ) . "'
					, required_approval = '" . $this->db->escape ( $data ['required_approval'] ) . "'
					, linked_id = '" . $this->db->escape ( $linked_id ) . "'
					, formreturn_id = '" . $this->db->escape ( $data ['formreturn_id'] ) . "'
					
					, target_facilities_id = '" . $this->db->escape ( $data ['target_facilities_id'] ) . "'
					, is_pause = '0'
					, pause_date = '" . $this->db->escape ( $data ['pause_date'] ) . "'
					, pause_time = '" . $this->db->escape ( $data ['pause_time'] ) . "'
					, user_role_assign_ids = '" . $this->db->escape ( $data ['user_role_assign_ids'] ) . "'
					, assign_to_type = '" . $this->db->escape ( $data ['assign_to_type'] ) . "'
					, reminder_alert = '" . $this->db->escape ( $data ['reminder_alert'] ) . "'
					, task_action = '" . $this->db->escape ( $data ['task_action'] ) . "'
					
					";
					
					//die;
					
					$this->db->query ( $sql );
					
					$task_id = $this->db->getLastId ();
					
					if ($tasktypetype == '5') {
						$sqls23dss = "UPDATE `" . DB_PREFIX . "createtask` SET task_complettion = '" . $this->db->escape ( $data ['task_complettion'] ) . "', device_id = '" . $this->db->escape ( $data ['device_id'] ) . "' where id= '" . $task_id . "' ";
						$this->db->query ( $sqls23dss );
						
						if ($durationv > 0) {
							$sql2dd = "UPDATE  " . DB_PREFIX . "createtask SET task_time = '" . $dropoff_locations_time . "',dropoff_locations_time = '" . $dropoff_locations_time . "', response = '" . $this->db->escape ( serialize ( $response_all ) ) . "', distance_text = '" . $this->db->escape ( $distance ) . "', distance_value = '" . $this->db->escape ( $distancev ) . "', duration_text = '" . $this->db->escape ( $duration ) . "', duration_value = '" . $this->db->escape ( $durationv ) . "' where id= '" . $task_id . "' ";
							$this->db->query ( $sql2dd );
						} else {
							
							$time = date ( 'H:i:s' );
							$snooze_time7 = '30';
							$dropoff_locations_time = date ( "H:i:s", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $time ) ) );
							
							$sql2dd = "UPDATE  " . DB_PREFIX . "createtask SET task_time = '" . $dropoff_locations_time . "', dropoff_locations_time = '" . $dropoff_locations_time . "', response = '" . $this->db->escape ( serialize ( $response_all ) ) . "', distance_text = '" . $this->db->escape ( $distance ) . "', distance_value = '" . $this->db->escape ( $distancev ) . "', duration_text = '" . $this->db->escape ( $duration ) . "', duration_value = '" . $this->db->escape ( $durationv ) . "' where id= '" . $task_id . "' ";
							$this->db->query ( $sql2dd );
						}
					}
					
					if ($data ['medication_tags']) {
						
						$this->load->model ( 'setting/tags' );
						
						$medication_tags1 = explode ( ',', $data ['medication_tags'] );
						
						foreach ( $medication_tags1 as $medicationtag ) {
							$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $data ['id'], $medicationtag );
							
							foreach ( $mdrugs as $mdrug ) {
								$sql22 = "INSERT INTO `" . DB_PREFIX . "createtask_medications` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $mdrug ['facilities_id'] ) . "', tags_id = '" . $this->db->escape ( $mdrug ['tags_id'] ) . "', tags_medication_details_id = '" . $this->db->escape ( $mdrug ['tags_medication_details_id'] ) . "' ";
								$this->db->query ( $sql22 );
							}
						}
						
						// $sql = "DELETE FROM`" . DB_PREFIX .
						// "createtask_medications` where id = '".$data['id']."'
						// ";
						// $this->db->query($sql);
					}
					
					if ($data ['transport_tags']) {
						
						$this->load->model ( 'createtask/createtask' );
						$travelWaypoints = $this->model_createtask_createtask->gettravelWaypoints ( $data ['id'] );
						if ($travelWaypoints != null && $travelWaypoints != "") {
							foreach ( $travelWaypoints as $travelWaypoint ) {
								$sql2233 = "INSERT INTO `" . DB_PREFIX . "createtask_by_transport` SET id = '" . $task_id . "', locations_address = '" . $this->db->escape ( $travelWaypoint ['locations_address'] ) . "', latitude = '" . $this->db->escape ( $travelWaypoint ['latitude'] ) . "', longitude = '" . $this->db->escape ( $travelWaypoint ['longitude'] ) . "', place_id = '" . $this->db->escape ( $travelWaypoint ['place_id'] ) . "' ";
								$this->db->query ( $sql2233 );
							}
						}
					}
					
					$reminders = $this->model_createtask_createtask->getreminders ( $data ['id'] );
					if ($reminders) {
						foreach ( $reminders as $reminder ) {
							$sqlrs = "INSERT INTO `" . DB_PREFIX . "createtask_reminder` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $reminder ['facilities_id'] ) . "', date_added = '" . $this->db->escape ( $taskDate1 ) . "', minute = '" . $this->db->escape ( $reminder ['minute'] ) . "', action = '" . $reminder ['action'] . "' ";
							$this->db->query ( $sqlrs );
						}
					}
				}
			}
		}
	}
	public function addNotificationtask($data, $facilities_id) {
		$sql = "INSERT INTO `" . DB_PREFIX . "createtask` SET facilityId = '" . $facilities_id . "', task_date = '" . $this->db->escape ( $data ['task_date'] ) . "', task_time = '" . $this->db->escape ( $data ['task_time'] ) . "', date_added = '" . $data ['task_date'] . "', description = '" . $this->db->escape ( $data ['description'] ) . "', assign_to = '" . $this->db->escape ( $data ['assign_to'] ) . "', recurrence = '" . $this->db->escape ( $data ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $data ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $data ['end_recurrence_date'] . "', endtime = '" . $data ['endtime'] . "', tasktype = '" . $this->db->escape ( $data ['tasktype'] ) . "', recurnce_day = '" . $this->db->escape ( $data ['recurnce_day'] ) . "', recurnce_month = '" . $this->db->escape ( $data ['recurnce_month'] ) . "', recurnce_week = '" . $this->db->escape ( $data ['recurnce_week'] ) . "',taskadded = '0', task_alert = '" . $this->db->escape ( $data ['task_alert'] ) . "', alert_type_sms = '" . $this->db->escape ( $data ['alert_type_sms'] ) . "', alert_type_notification = '" . $this->db->escape ( $data ['alert_type_notification'] ) . "', alert_type_email = '" . $this->db->escape ( $data ['alert_type_email'] ) . "', checklist = '" . $this->db->escape ( $data ['checklist'] ) . "'

		, snooze_time = '" . $this->db->escape ( $data ['snooze_time'] ) . "', snooze_dismiss = '0', rules_task = '0', message_sid = '', send_sms = '0', send_email = '0', send_notification = '0', task_form_id = '" . $this->db->escape ( $data ['task_form_id'] ) . "', tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "', pickup_facilities_id = '" . $this->db->escape ( $data ['pickup_facilities_id'] ) . "', pickup_locations_address = '" . $this->db->escape ( $data ['pickup_locations_address'] ) . "', pickup_locations_time = '" . $this->db->escape ( $data ['pickup_locations_time'] ) . "', pickup_locations_latitude = '" . $this->db->escape ( $data ['pickup_locations_latitude'] ) . "', pickup_locations_longitude = '" . $this->db->escape ( $data ['pickup_locations_longitude'] ) . "', dropoff_facilities_id = '" . $this->db->escape ( $data ['dropoff_facilities_id'] ) . "', dropoff_locations_address = '" . $this->db->escape ( $data ['dropoff_locations_address'] ) . "', dropoff_locations_time = '" . $this->db->escape ( $data ['dropoff_locations_time'] ) . "', dropoff_locations_latitude = '" . $this->db->escape ( $data ['dropoff_locations_latitude'] ) . "', dropoff_locations_longitude = '" . $this->db->escape ( $data ['dropoff_locations_longitude'] ) . "', transport_tags = '" . $this->db->escape ( $data ['transport_tags'] ) . "', locations_id = '" . $this->db->escape ( $data ['locations_id'] ) . "', task_complettion = '0', device_id = '', customs_forms_id = '" . $this->db->escape ( $data ['customs_forms_id'] ) . "', emp_tag_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "', medication_tags = '" . $this->db->escape ( $data ['medication_tags'] ) . "', completion_alert = '" . $this->db->escape ( $data ['completion_alert'] ) . "', completion_alert_type_sms = '" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "', completion_alert_type_email = '" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "', user_roles = '" . $this->db->escape ( $data ['user_roles'] ) . "', userids = '" . $this->db->escape ( $data ['userids'] ) . "', recurnce_hrly_perpetual = '" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "', due_date_time = '" . $this->db->escape ( $data ['due_date_time'] ) . "', task_status = '2', task_completed = '0', recurnce_hrly_recurnce = '" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "'
		";
		
		$this->db->query ( $sql );
	}
	public function getallTaskLists($data = array()) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,send_sms,send_email,is_approval_required_forms_id,bed_check_location_ids,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE taskadded = '0' and enable_requires_approval !=1 ";
		
		if ($data ['subfacilities_id'] == "1") {
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
					$ddss [] = $resulsst ['notes_facilities_ids'];
					$ddss [] = $data ['facilities_id'];
					$sssssdd = implode ( ",", $ddss );
					$faculities_ids = $sssssdd;
					
					$sql .= " and `facilityId` in  (" . $faculities_ids . ") ";
				}
			} else {
				$sql .= " and `facilityId` =  '" . $data ['facilities_id'] . "' ";
			}
		} else {
			$sql .= " and `facilityId` =  '" . $data ['facilities_id'] . "' ";
		}
		
		if ($data ['currentdate'] != null && $data ['currentdate'] != "") {
			$date = str_replace ( '-', '/', $data ['currentdate'] );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$startDate = $changedDate; /*
			                            * date('Y-m-d',
			                            * strtotime($data['searchdate']));
			                            */
			/* $endDate = date('Y-m-d'); */
			$endDate = $changedDate; /*
			                          * date('Y-m-d',
			                          * strtotime($data['searchdate']));
			                          */
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59') ";
		}
		
		if ($data ['notification'] != null && $data ['notification'] != "") {
			$sql .= " and `alert_type_notification` = '1' ";
		}
		
		if ($data ['alert_type_sms'] != null && $data ['alert_type_sms'] != "") {
			$sql .= " and `alert_type_sms` = '1' ";
		}
		if ($data ['alert_type_email'] != null && $data ['alert_type_email'] != "") {
			$sql .= " and `alert_type_email` = '1' ";
		}
		
		if ($data ['snooze_dismiss'] != null && $data ['snooze_dismiss'] != "") {
			$sql .= " and `snooze_dismiss` != '2' ";
		}
		
		if ($data ['send_notification'] != null && $data ['send_notification'] != "") {
			$sql .= " and `send_notification` = '0' ";
		}
		
		if ($data ['task_id'] != null && $data ['task_id'] != "") {
			
			$this->load->model ( 'createtask/createtask' );
			$tasktype_info = $this->model_createtask_createtask->gettasktyperow ( $data ['task_id'] );
			
			if ($tasktype_info ['custom_completion_rule'] == '1') {
				$startaddTime = $tasktype_info ['config_task_minandmax_time'];
				$endaddTime = $tasktype_info ['config_task_minandmax_after_time'];
				
				$sql .= " and `tasktype` =  '" . $tasktype_info ['tasktype_name'] . "' ";
			} else {
				$startaddTime = $this->config->get ( 'config_task_minandmax_time' );
				$endaddTime = $this->config->get ( 'config_task_minandmax_after_time' );
				
				$sql .= " and `tasktype` =  '" . $tasktype_info ['tasktype_name'] . "' ";
			}
		} else {
			$startaddTime = $this->config->get ( 'config_task_minandmax_time' );
			$endaddTime = $this->config->get ( 'config_task_minandmax_after_time' );
		}
		
		if ($data ['top'] == '1') {
			// date_default_timezone_set($this->session->data['time_zone_1']);
			
			$timezone_name = $this->customer->isTimezone ();
			
			$timeZone = date_default_timezone_set ( $timezone_name );
			
			$thestime = date ( 'H:i:s' );
			
			$stime = date ( "H:i:s", strtotime ( "-" . $startaddTime . " minutes", strtotime ( $thestime ) ) );
			$endtime2 = date ( 'h:i:s' ) + strtotime ( "+" . $endaddTime . " minutes" );
			
			$endtime = date ( 'H:i:s', $endtime2 );
			
			$sql .= " and (`task_time` BETWEEN  '" . $stime . "' AND  '" . $endtime . " ') ";
		}
		
		if ($data ['top'] == '2') {
			// date_default_timezone_set($this->session->data['time_zone_1']);
			$this->load->model ( 'setting/timezone' );
			$this->load->model ( 'facilities/facilities' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			$thestime = date ( 'H:i:s' );
			
			$stime = date ( "H:i:s", strtotime ( "-" . $startaddTime . " minutes", strtotime ( $thestime ) ) );
			$endtime2 = date ( 'h:i:s' ) + strtotime ( "+" . $endaddTime . " minutes" );
			
			$endtime = date ( 'H:i:s', $endtime2 );
			
			$sql .= " and (`task_time` BETWEEN  '" . $stime . "' AND  '" . $endtime . " ') ";
		}
		
		// echo $sql;
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getCountallTaskLists($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "createtask` WHERE taskadded = '0' and enable_requires_approval !=1 ";
		
		if ($data ['subfacilities_id'] == "1") {
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($resulsst ['notes_facilities_ids'] != null && $resulsst ['notes_facilities_ids'] != "") {
					$ddss [] = $resulsst ['notes_facilities_ids'];
					$ddss [] = $data ['facilities_id'];
					$sssssdd = implode ( ",", $ddss );
					$faculities_ids = $sssssdd;
					
					$sql .= " and `facilityId` in  (" . $faculities_ids . ") ";
				}
			} else {
				$sql .= " and `facilityId` =  '" . $data ['facilities_id'] . "' ";
			}
		} else {
			$sql .= " and `facilityId` =  '" . $data ['facilities_id'] . "' ";
		}
		
		if ($data ['currentdate'] != null && $data ['currentdate'] != "") {
			$date = str_replace ( '-', '/', $data ['currentdate'] );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			
			$startDate = $changedDate; /*
			                            * date('Y-m-d',
			                            * strtotime($data['searchdate']));
			                            */
			/* $endDate = date('Y-m-d'); */
			$endDate = $changedDate; /*
			                          * date('Y-m-d',
			                          * strtotime($data['searchdate']));
			                          */
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59') ";
		}
		
		if ($data ['notification'] != null && $data ['notification'] != "") {
			$sql .= " and `alert_type_notification` = '1' ";
		}
		
		if ($data ['alert_type_sms'] != null && $data ['alert_type_sms'] != "") {
			$sql .= " and `alert_type_sms` = '1' ";
		}
		if ($data ['alert_type_email'] != null && $data ['alert_type_email'] != "") {
			$sql .= " and `alert_type_email` = '1' ";
		}
		
		if ($data ['snooze_dismiss'] != null && $data ['snooze_dismiss'] != "") {
			$sql .= " and `snooze_dismiss` != '2' ";
		}
		
		if ($data ['send_notification'] != null && $data ['send_notification'] != "") {
			$sql .= " and `send_notification` = '0' ";
		}
		
		if ($data ['task_id'] != null && $data ['task_id'] != "") {
			
			$this->load->model ( 'createtask/createtask' );
			$tasktype_info = $this->model_createtask_createtask->gettasktyperow ( $data ['task_id'] );
			
			if ($tasktype_info ['custom_completion_rule'] == '1') {
				$startaddTime = $tasktype_info ['config_task_minandmax_time'];
				$endaddTime = $tasktype_info ['config_task_minandmax_after_time'];
				
				$sql .= " and `tasktype` =  '" . $tasktype_info ['tasktype_name'] . "' ";
			} else {
				$startaddTime = $this->config->get ( 'config_task_minandmax_time' );
				$endaddTime = $this->config->get ( 'config_task_minandmax_after_time' );
				
				$sql .= " and `tasktype` =  '" . $tasktype_info ['tasktype_name'] . "' ";
			}
		} else {
			$startaddTime = $this->config->get ( 'config_task_minandmax_time' );
			$endaddTime = $this->config->get ( 'config_task_minandmax_after_time' );
		}
		
		// $startaddTime = $this->config->get('config_task_minandmax_time');
		// $endaddTime = $this->config->get('config_task_minandmax_after_time');
		
		if ($data ['top'] == '1') {
			// date_default_timezone_set($this->session->data['time_zone_1']);
			
			$timezone_name = $this->customer->isTimezone ();
			
			$timeZone = date_default_timezone_set ( $timezone_name );
			
			$thestime = date ( 'H:i:s' );
			
			$stime = date ( "H:i:s", strtotime ( "-" . $startaddTime . " minutes", strtotime ( $thestime ) ) );
			$endtime2 = date ( 'h:i:s' ) + strtotime ( "+" . $endaddTime . " minutes" );
			
			$endtime = date ( 'H:i:s', $endtime2 );
			
			$sql .= " and (`task_time` BETWEEN  '" . $stime . "' AND  '" . $endtime . " ') ";
		}
		
		if ($data ['top'] == '2') {
			
			$this->load->model ( 'setting/timezone' );
			$this->load->model ( 'facilities/facilities' );
			
			$facility = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			$thestime = date ( 'H:i:s' );
			
			$stime = date ( "H:i:s", strtotime ( "-" . $startaddTime . " minutes", strtotime ( $thestime ) ) );
			$endtime2 = date ( 'h:i:s' ) + strtotime ( "+" . $endaddTime . " minutes" );
			
			$endtime = date ( 'H:i:s', $endtime2 );
			
			$sql .= " and (`task_time` BETWEEN  '" . $stime . "' AND  '" . $endtime . " ') ";
		}
		
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function emailtemplateinterval($result, $taskDate, $tasktimearray) {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Task has been assigned to you</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Task has been assigned to you</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['assignto'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Task has been assigned to you ' . $result ['tasktype'] . '. Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $taskDate ) ) . ' <br> ';
		
		foreach ( $tasktimearray as $taskeTiming ) {
			
			$html .= date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
		}
		
		$html .= '</p>
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
	public function emailtemplate($result, $taskDate, $taskeTiming, $weekd) {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Task has been assigned to you</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Task has been assigned to you</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['assignto'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Task has been assigned to you ' . $result ['tasktype'] . '. Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . $weekd . '&nbsp;' . date ( 'j, F Y', strtotime ( $taskDate ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '
						</p>
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
	public function createClient($client_name) {
		$sql = "INSERT INTO `" . DB_PREFIX . "client` SET client_name = '" . $client_name . "' ";
		
		$this->db->query ( $sql );
		$client_id = $this->db->getLastId ();
		return $client_id;
	}
	public function getClient($client_name) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "client` where client_name = '" . $client_name . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getClientbyid($client_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "client WHERE client_id = '" . ( int ) $client_id . "'" );
		return $query->row;
	}
	public function getclients($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "client`";
		
		$sql .= 'where 1 = 1 ';
		
		if ($data ['client_name'] != null && $data ['client_name'] != "") {
			$sql .= " and client_name like '%" . $data ['client_name'] . "%'";
		}
		
		$sort_data = array (
				'client_name' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY client_name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gettaskstatuss($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "taskstatus";
		$sql .= ' where 1 = 1 ';
		
		if ($data ['taskstatus_name'] != null && $data ['taskstatus_name'] != "") {
			$sql .= " and taskstatus_name like '%" . $data ['taskstatus_name'] . "%'";
		}
		
		if ($data ['status'] != null && $data ['status'] != "") {
			$sql .= " and status = '" . $data ['status'] . "'";
		}
		
		if ($data ['sort']) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function completeemailtemplate($result, $taskDate, $taskeTiming, $headerbody, $messagebody1) {
		$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData =
			 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
			 *
			 * foreach($taskformData as $frmData){
			 * $taskformsData[] = array(
			 * 'task_form_name' =>$frmData['task_form_name'],
			 * 'facilities_id' =>$frmData['facilities_id'],
			 * 'form_type' =>$frmData['form_type']
			 * );
			 * }
			 */
		}
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $headerbody . '</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $headerbody . '</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['assign_to'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $headerbody . ' - ' . $messagebody1 . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name:
						// '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $taskDate ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '
						</p>
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
	public function getcustomlistByTasktype($tasktype) {
		$query = $this->db->query ( "SELECT task_id,tasktype_name,alert_type,relation_keyword_id,config_task_complete,custom_completion_rule,config_task_after_complete,config_task_deleted_time,generate_report,forms_id,display_custom_list,customlist_id,customlistvalueids,config_task_minandmax_time,config_task_minandmax_after_time,monitor_time,enable_location,enable_location_tracking,enable_requires_approval,auto_extend,auto_extend_time FROM " . DB_PREFIX . "tasktype WHERE task_id = '" . $tasktype . "' " );
		return $query->row;
	}
	public function getTaskas($tags_id, $currentdate) {
		$date = str_replace ( '-', '/', $currentdate );
		$res = explode ( "/", $date );
		$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
		
		$startDate = $changedDate; /*
		                            * date('Y-m-d',
		                            * strtotime($data['searchdate']));
		                            */
		/* $endDate = date('Y-m-d'); */
		$endDate = $changedDate; /*
		                          * date('Y-m-d',
		                          * strtotime($data['searchdate']));
		                          */
		
		$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59') ";
		
		$sql = "SELECT count(*) as total FROM `" . DB_PREFIX . "createtask` WHERE emp_tag_id = '" . $tags_id . "' and recurrence = 'Perpetual' " . $sql . " ";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function getrecentTaskdetails($data = array()) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,complete_status,is_approval_required_tags_id,bed_check_location_ids,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation FROM `" . DB_PREFIX . "createtask` WHERE emp_tag_id = '" . $data ['emp_tag_id'] . "' and facilityId = '" . $data ['facilities_id'] . "' ";
		
		$date = str_replace ( '-', '/', $data ['searchdate'] );
		$res = explode ( "/", $date );
		$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
		
		$startDate = $changedDate; /*
		                            * date('Y-m-d',
		                            * strtotime($data['searchdate']));
		                            */
		/* $endDate = date('Y-m-d'); */
		$endDate = $changedDate; /*
		                          * date('Y-m-d',
		                          * strtotime($data['searchdate']));
		                          */
		
		// $sql .= " and (`date_added` BETWEEN '".$startDate." 00:00:00' AND
		// '".$endDate." 23:59:59') ";
		
		$sql .= " ORDER BY `date_added` ASC limit 0,1 ";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getalltaskbyid($tags_id) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,reminder_alert,send_notification,task_action,form_task_creation from `" . DB_PREFIX . "createtask` WHERE 1 = 1 ";
		$sql .= " and ( emp_tag_id = '" . $tags_id . "' or medication_tags = '" . $tags_id . "' or visitation_tag_id = '" . $tags_id . "' or FIND_IN_SET('" . $tags_id . "', transport_tags) ) ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function addcoordinates($data, $task_id) {
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_travel_task_coordinates` SET task_id = '" . $this->db->escape ( $data ['task_id'] ) . "',notes_id = '" . $this->db->escape ( $data ['notes_id'] ) . "', latitude = '" . $this->db->escape ( $data ['latitude'] ) . "', longitude = '" . $data ['longitude'] . "', speed = '" . $this->db->escape ( $data ['speed'] ) . "', sort_order = '" . $this->db->escape ( $data ['sort_order'] ) . "'	";
		
		$this->db->query ( $sql );
	}
	public function getcoordinates($task_id) {
		$sql = "SELECT notes_by_travel_task_coordinates_id,notes_id,travel_task_id,task_id,latitude,longitude,sort_order,speed from `" . DB_PREFIX . "notes_by_travel_task_coordinates` WHERE 1 = 1 ";
		$sql .= " and ( task_id = '" . $task_id . "' ) ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getApprovaltasklist($task_id) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids ,complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation from `" . DB_PREFIX . "createtask` WHERE 1 = 1 ";
		$sql .= " and ( approval_taskid = '" . $task_id . "' ) ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getdeclinetasksLists($task_id) {
		$sql = "SELECT id,facilityId,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,iswaypoint,original_task_time,device_id,is_approval_required_forms_id,bed_check_location_ids, complete_status,is_create_task,unique_id,customer_key,required_approval,linked_id,formreturn_id,target_facilities_id,pause_date,pause_time,is_pause,user_role_assign_ids,assign_to_type,reminder_alert,send_notification,task_action,form_task_creation from `" . DB_PREFIX . "createtask` WHERE 1 = 1 ";
		$sql .= " and ( approval_taskid = '" . $task_id . "' ) ";
		$sql .= " ORDER BY `date_added` ASC limit 0,1 ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function addapproveTask($data) {
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_approval_task` SET id = '" . $data ['id'] . "',facilities_id = '" . $data ['facilityId'] . "', task_date = '" . $this->db->escape ( $data ['task_date'] ) . "', task_time = '" . $this->db->escape ( $data ['task_time'] ) . "', date_added = '" . $this->db->escape ( $data ['date_added'] ) . "', description = '" . $this->db->escape ( $data ['description'] ) . "', assign_to = '" . $this->db->escape ( $data ['assign_to'] ) . "', recurrence = '" . $this->db->escape ( $data ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $data ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $data ['end_recurrence_date'] . "', endtime = '" . $this->db->escape ( $data ['endtime'] ) . "', tasktype = '" . $this->db->escape ( $data ['tasktype'] ) . "', recurnce_day = '" . $this->db->escape ( $data ['recurnce_day'] ) . "', recurnce_month = '" . $this->db->escape ( $data ['recurnce_month'] ) . "', recurnce_week = '" . $this->db->escape ( $data ['recurnce_week'] ) . "',taskadded = '0', task_alert = '" . $this->db->escape ( $data ['task_alert'] ) . "', alert_type_sms = '" . $this->db->escape ( $data ['alert_type_sms'] ) . "', alert_type_notification = '" . $this->db->escape ( $data ['alert_type_notification'] ) . "', alert_type_email = '" . $this->db->escape ( $data ['alert_type_email'] ) . "', checklist = '" . $this->db->escape ( $data ['checklist'] ) . "', rules_task = '" . $this->db->escape ( $data ['rules_task'] ) . "', task_form_id = '" . $this->db->escape ( $data ['task_form_id'] ) . "', tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "', pickup_locations_address = '" . $this->db->escape ( $data ['pickup_locations_address'] ) . "', pickup_locations_latitude = '" . $this->db->escape ( $data ['pickup_locations_latitude'] ) . "', pickup_locations_longitude = '" . $this->db->escape ( $data ['pickup_locations_longitude'] ) . "', pickup_locations_time = '" . $this->db->escape ( $data ['pickup_locations_time'] ) . "', dropoff_locations_address = '" . $this->db->escape ( $data ['dropoff_locations_address'] ) . "', dropoff_locations_time = '" . $this->db->escape ( $data ['dropoff_locations_time'] ) . "', dropoff_locations_latitude = '" . $this->db->escape ( $data ['dropoff_locations_latitude'] ) . "', dropoff_locations_longitude = '" . $this->db->escape ( $data ['dropoff_locations_longitude'] ) . "', transport_tags = '" . $this->db->escape ( $data ['transport_tags'] ) . "', locations_id = '" . $this->db->escape ( $data ['locations_id'] ) . "', recurnce_hrly_recurnce = '" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "', medication_tags = '" . $this->db->escape ( $data ['medication_tags'] ) . "', completion_alert = '" . $this->db->escape ( $data ['completion_alert'] ) . "', completion_alert_type_sms = '" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "', completion_alert_type_email = '" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "', user_roles = '" . $this->db->escape ( $data ['user_roles'] ) . "', userids = '" . $this->db->escape ( $data ['userids'] ) . "', recurnce_hrly_perpetual = '" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "', emp_tag_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "', due_date_time = '" . $this->db->escape ( $data ['due_date_time'] ) . "', task_status = '" . $this->db->escape ( $data ['task_status'] ) . "', visitation_start_address = '" . $this->db->escape ( $data ['visitation_start_address'] ) . "', visitation_start_address_latitude = '" . $this->db->escape ( $data ['visitation_start_address_latitude'] ) . "', visitation_start_address_longitude = '" . $this->db->escape ( $data ['visitation_start_address_longitude'] ) . "', visitation_start_time = '" . $this->db->escape ( $data ['visitation_start_time'] ) . "', visitation_appoitment_address = '" . $this->db->escape ( $data ['visitation_appoitment_address'] ) . "', visitation_appoitment_time = '" . $this->db->escape ( $data ['visitation_appoitment_time'] ) . "', visitation_appoitment_address_latitude = '" . $this->db->escape ( $data ['visitation_appoitment_address_latitude'] ) . "', visitation_appoitment_address_longitude = '" . $this->db->escape ( $data ['visitation_appoitment_address_longitude'] ) . "', visitation_tags = '" . $this->db->escape ( $data ['visitation_tags'] ) . "', visitation_start_facilities_id = '" . $this->db->escape ( $data ['visitation_start_facilities_id'] ) . "', visitation_appoitment_facilities_id = '" . $this->db->escape ( $data ['visitation_appoitment_facilities_id'] ) . "', visitation_tag_id = '" . $this->db->escape ( $data ['visitation_tag_id'] ) . "', completed_times = '" . $this->db->escape ( $data ['completed_times'] ) . "', completed_alert = '" . $this->db->escape ( $data ['completed_alert'] ) . "', completed_late_alert = '" . $this->db->escape ( $data ['completed_late_alert'] ) . "', incomplete_alert = '" . $this->db->escape ( $data ['incomplete_alert'] ) . "', deleted_alert = '" . $this->db->escape ( $data ['deleted_alert'] ) . "', attachement_form = '" . $this->db->escape ( $data ['attachement_form'] ) . "', tasktype_form_id = '" . $this->db->escape ( $data ['tasktype_form_id'] ) . "' , task_group_by = '" . $this->db->escape ( $data ['task_group_by'] ) . "' , enable_requires_approval = '" . $this->db->escape ( $data ['enable_requires_approval'] ) . "', approval_taskid = '" . $this->db->escape ( $data ['approval_taskid'] ) . "', iswaypoint = '" . $this->db->escape ( $data ['iswaypoint'] ) . "', original_task_time = '" . $this->db->escape ( $data ['original_task_time'] ) . "', status = '0', response = '" . $this->db->escape ( $data ['response'] ) . "', distance_text = '" . $this->db->escape ( $data ['distance_text'] ) . "', distance_value = '" . $this->db->escape ( $data ['distance_value'] ) . "', duration_text = '" . $this->db->escape ( $data ['duration_text'] ) . "', duration_value = '" . $this->db->escape ( $data ['duration_value'] ) . "', is_approval_required_forms_id = '" . $this->db->escape ( $data ['is_approval_required_forms_id'] ) . "', is_approval_required_tags_id = '" . $this->db->escape ( $data ['is_approval_required_tags_id'] ) . "', is_android = '" . $this->db->escape ( $data ['is_android'] ) . "', unique_id = '" . $this->db->escape ( $data ['unique_id'] ) . "', customer_key = '" . $this->db->escape ( $data ['customer_key'] ) . "', complete_status = '" . $this->db->escape ( $data ['complete_status'] ) . "', weekly_interval = '" . $this->db->escape ( $data ['weekly_interval'] ) . "', is_create_task = '" . $this->db->escape ( $data ['is_create_task'] ) . "', required_approval = '" . $this->db->escape ( $data ['required_approval'] ) . "', linked_id = '" . $this->db->escape ( $data ['linked_id'] ) . "' , formreturn_id = '" . $this->db->escape ( $data ['formreturn_id'] ) . "', target_facilities_id = '" . $this->db->escape ( $data ['target_facilities_id'] ) . "', pause_date = '" . $this->db->escape ( $data ['pause_date'] ) . "', pause_time = '" . $this->db->escape ( $data ['pause_time'] ) . "', is_pause = '" . $this->db->escape ( $data ['is_pause'] ) . "', user_role_assign_ids = '" . $this->db->escape ( $data ['user_role_assign_ids'] ) . "', assign_to_type = '" . $this->db->escape ( $data ['assign_to_type'] ) . "', task_action = '" . $this->db->escape ( $data ['task_action'] ) . "', form_task_creation = '" . $this->db->escape ( $data ['form_task_creation'] ) . "' ";
		
		// echo "<hr>";
		$this->db->query ( $sql );
		
		$task_id = $this->db->getLastId ();
		
		$this->load->model ( 'activity/activity' );
		$data ['task_id'] = $task_id;
		// $data['facilities_id'] = $data['facilityId'];
		$this->model_activity_activity->addActivitySave ( 'addapproveTask', $data, 'query' );
	}
	public function getNBYTaksLists($task_id) {
		$sql = "SELECT id,facilities_id,task_date,task_time,date_added,tasktype,description,assign_to,recurrence,end_recurrence_date,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,taskadded,endtime,task_alert,alert_type_none,alert_type_sms,alert_type_notification,alert_type_email,checklist,snooze_time,snooze_dismiss,rules_task,task_form_id,tags_id,pickup_facilities_id,pickup_locations_address,pickup_locations_time,pickup_locations_latitude,pickup_locations_longitude,dropoff_facilities_id,dropoff_locations_address,dropoff_locations_time,dropoff_locations_latitude,dropoff_locations_longitude,transport_tags,locations_id,task_complettion,device_id,customs_forms_id,emp_tag_id,medication_tags,completion_alert,completion_alert_type_sms,completion_alert_type_email,user_roles,userids,recurnce_hrly_perpetual,due_date_time,task_status,task_completed,recurnce_hrly_recurnce,completed_times,completed_alert,completed_late_alert,incomplete_alert,deleted_alert,end_perpetual_task,is_transport,parent_id,is_send_reminder,attachement_form,tasktype_form_id,tagstatus_id,task_group_by,end_task,formrules_id,task_random_id,form_due_date,form_due_date_after,recurnce_m,enable_requires_approval,approval_taskid,notes_id,status,iswaypoint,original_task_time,device_id,bed_check_location_ids,is_create_task,unique_id,customer_key,required_approval,linked_id,complete_status,send_notification,task_action,form_task_creation from `" . DB_PREFIX . "notes_by_approval_task` WHERE 1 = 1 ";
		$sql .= " and  id = '" . $task_id . "'  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function deleteNBYTaksLists($task_id) {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "notes_by_approval_task WHERE id = '" . ( int ) $task_id . "'" );
	}
	public function gettravelWaypoints($task_id) {
		$query = $this->db->query ( "SELECT createtask_by_transport_id,locations_address,latitude,longitude,id,complete_status FROM " . DB_PREFIX . "createtask_by_transport WHERE id = '" . $task_id . "' " );
		return $query->rows;
	}
	public function getreminders($task_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "createtask_reminder WHERE id = '" . $task_id . "' " );
		return $query->rows;
	}
	public function insertTaskmedicine($tasklocation, $postdata, $data = array()) {
		$this->load->model ( 'user/user' );
		$user_info = $this->model_user_user->getUser ( $tasklocation ['medication_user_id'] );
		
		$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
		notes_id = '" . $data ['notes_id'] . "', locations_id ='" . $tasklocation ['locations_id'] . "', task_type= '" . $data ['task_type'] . "', task_content = '" . $this->db->escape ( $data ['task_content'] ) . "', signature= '" . $tasklocation ['medication_signature'] . "', user_id= '" . $this->db->escape ( $user_info ['username'] ) . "', date_added = '" . $data ['date_added'] . "', notes_pin = '" . $this->db->escape ( $tasklocation ['medication_notes_pin'] ) . "', notes_type = '" . $postdata ['notes_type'] . "', task_time = '" . $tasklocation ['task_time'] . "' , media_url = '" . $tasklocation ['media_url'] . "', capacity = '" . $tasklocation ['capacity'] . "', location_name = '" . $tasklocation ['location_name'] . "', location_type = '" . $tasklocation ['location_type'] . "', notes_task_type = '2', tags_id = '" . $data ['tags_id'] . "', drug_name = '" . $this->db->escape ( $data ['drug_name'] ) . "', dose = '" . $this->db->escape ( $data ['dose'] ) . "', drug_type = '" . $data ['drug_type'] . "', quantity = '" . $tasklocation ['quantity'] . "', frequency = '" . $data ['frequency'] . "', instructions = '" . $data ['instructions'] . "', count = '" . $data ['count'] . "', createtask_by_group_id = '" . $tasklocation ['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape ( $tasklocation ['task_comments'] ) . "', medication_attach_url = '" . $tasklocation ['medication_attach_url'] . "',medication_file_upload='1' , tags_medication_details_id = '" . $tasklocation ['tags_medication_details_id'] . "' , tags_medication_id = '" . $tasklocation ['tags_medication_id'] . "' , facilities_id = '" . $data ['facilities_id'] . "', complete_status = '" . $data ['complete_status'] . "', role_call = '" . $tasklocation ['role_call'] . "' ";
		
		$this->db->query ( $sql2 );
		$notes_by_task_id = $this->db->getLastId ();
		
		$this->load->model ( 'activity/activity' );
		$data2212 = array ();
		$data2212 ['notes_by_task_id'] = $notes_by_task_id;
		$data2212 ['locations_id'] = $tasklocation ['locations_id'];
		$data2212 ['location_name'] = $tasklocation ['location_name'];
		$data2212 ['tags_ids'] = $tasklocation ['tags_ids'];
		$data2212 ['out_tags_ids'] = $tasklocation ['out_tags_ids'];
		$data2212 ['media_url'] = $tasklocation ['media_url'];
		$data2212 ['role_call'] = $tasklocation ['role_call'];
		$data2212 ['tags_medication_details_id'] = $tasklocation ['tags_medication_details_id'];
		$data2212 ['facilities_id'] = $data ['facilities_id'];
		$data2212 ['task_comments'] = $data ['task_comments'];
		$data2212 ['location_type'] = $data ['location_type'];
		$data2212 ['task_type'] = $data ['task_type'];
		$data2212 ['date_added'] = $data ['date_added'];
		$data2212 ['tags_id'] = $data ['tags_id'];
		$data2212 ['drug_name'] = $data ['drug_name'];
		$data2212 ['instructions'] = $data ['instructions'];
		
		$data2212 ['user_id'] = $postdata ['medication_user_id'];
		$data2212 ['notes_pin'] = $postdata ['medication_notes_pin'];
		$data2212 ['notes_type'] = $postdata ['notes_type'];
		$this->model_activity_activity->addActivitySave ( 'insertTaskmedicine', $data2212, 'query' );
		// var_dump($notes_by_task_id);
		return $notes_by_task_id;
	}
	public function insertTaskbedcheck($tasklocation, $postdata, $data = array()) {
		$this->load->model ( 'user/user' );
		$user_info = $this->model_user_user->getUser ( $postdata ['user_id'] );
		
		$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
			notes_id = '" . $data ['notes_id'] . "', locations_id ='" . $tasklocation ['locations_id'] . "', task_type= '" . $tasklocation ['task_type'] . "', task_content = '" . $this->db->escape ( $data ['task_content'] ) . "', signature= '" . $postdata ['signature'] . "', user_id= '" . $this->db->escape ( $user_info ['username'] ) . "', date_added = '" . $data ['date_added'] . "', notes_pin = '" . $this->db->escape ( $postdata ['notes_pin'] ) . "', notes_type = '" . $postdata ['notes_type'] . "', task_time = '" . $tasklocation ['task_time'] . "' , media_url = '" . $tasklocation ['media_url'] . "', capacity = '" . $tasklocation ['capacity'] . "', location_name = '" . $this->db->escape ( $tasklocation ['location_name'] ) . "', location_type = '" . $this->db->escape ( $data ['location_type'] ) . "',notes_task_type = '" . $postdata ['notes_task_type'] . "', task_comments = '" . $this->db->escape ( $data ['task_comments'] ) . "', task_customlistvalues_id = '" . $this->db->escape ( $data ['customlistvalues_id'] ) . "', tags_ids = '" . $this->db->escape ( $tasklocation ['tags_ids'] ) . "', room_current_date_time = '" . $this->db->escape ( $tasklocation ['room_current_date_time'] ) . "', facilities_id = '" . $data ['facilities_id'] . "', complete_status = '" . $data ['complete_status'] . "', role_call = '" . $tasklocation ['role_call'] . "', out_tags_ids = '" . $tasklocation ['out_tags_ids'] . "', out_capacity = '" . $tasklocation ['out_capacity'] . "', medication_attach_url = '" . $data ['medication_attach_url'] . "' ";
		
		$this->db->query ( $sql2 );
		$notes_by_task_id = $this->db->getLastId ();
		
		$this->load->model ( 'activity/activity' );
		$data2212 = array ();
		$data2212 ['notes_by_task_id'] = $notes_by_task_id;
		$data2212 ['locations_id'] = $tasklocation ['locations_id'];
		$data2212 ['location_name'] = $tasklocation ['location_name'];
		$data2212 ['tags_ids'] = $tasklocation ['tags_ids'];
		$data2212 ['out_tags_ids'] = $tasklocation ['out_tags_ids'];
		$data2212 ['media_url'] = $tasklocation ['media_url'];
		$data2212 ['role_call'] = $tasklocation ['role_call'];
		$data2212 ['facilities_id'] = $data ['facilities_id'];
		$data2212 ['task_customlistvalues_id'] = $data ['customlistvalues_id'];
		$data2212 ['task_comments'] = $data ['task_comments'];
		$data2212 ['location_type'] = $data ['location_type'];
		$data2212 ['task_type'] = $data ['task_type'];
		
		$data2212 ['user_id'] = $postdata ['user_id'];
		$data2212 ['notes_pin'] = $postdata ['notes_pin'];
		$data2212 ['notes_type'] = $postdata ['notes_type'];
		$this->model_activity_activity->addActivitySave ( 'insertTaskbedcheck', $data2212, 'query' );
	}
	public function updateformruletask($data = array()) {
		$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '" . $data ['rules_id'] . "', form_due_date = '" . $data ['form_due_date'] . "', form_due_date_after = '" . $data ['form_due_date_after'] . "', form_rules_operation = '" . $data ['form_rules_operation'] . "' where id ='" . $data ['task_id'] . "'";
		$this->db->query ( $sqlw2 );
	}
	public function updateformruletask2($data = array()) {
		$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '" . $data ['rules_id'] . "', task_random_id = '" . $data ['task_random_id'] . "', form_rules_operation = '" . $data ['form_rules_operation'] . "' where id ='" . $data ['task_id'] . "'";
		$this->db->query ( $sqlw2 );
		
		if ($data ['rules_operation_recurrence'] == '1') {
			$sqlw24 = "update `" . DB_PREFIX . "createtask` set recurnce_m = '1' where id ='" . $data ['task_id'] . "'";
			$this->db->query ( $sqlw24 );
		}
	}
	public function deteteIncomTaskbyid($task_id) {
		$sql = "DELETE FROM`" . DB_PREFIX . "createtask` where id = '" . $task_id . "' ";
		$this->db->query ( $sql );
	}
	public function deleteApprovaltasklist($task_id) {
		$sql = "DELETE FROM `" . DB_PREFIX . "createtask` WHERE 1 = 1 ";
		$sql .= " and ( approval_taskid = '" . $task_id . "' ) ";
		$this->db->query ( $sql );
	}
	public function gettaskmedicationdetail($task_id, $tags_medication_details_id) {
		$query = $this->db->query ( "SELECT complete_status,date_added,tags_medication_details_id,tags_medication_id,id,tags_id,createtask_medications_id FROM " . DB_PREFIX . "createtask_medications WHERE id = '" . $task_id . "' and tags_medication_details_id = '" . $tags_medication_details_id . "' " );
		return $query->row;
	}
	public function createapprovalTak($data) {
		$task_group_by = rand ();
		
		$timeZone = date_default_timezone_set ( $data ['facilitytimezone'] );
		
		$time = date ( 'H:i:s' );
		
		$currentdate = date ( 'Y-m-d H:i:s' );
		
		$message111 = substr ( $data ['incident_number'], 0, 100 ) . ((strlen ( $data ['incident_number'] ) > 100) ? '..' : '');
		
		$enable_desc = 'Form | APPROVAL REQUIRED | ' . $message111;
		
		$sql = "INSERT INTO `" . DB_PREFIX . "createtask` SET facilityId = '" . $data ['facilities_id'] . "', task_date = '" . $this->db->escape ( $currentdate ) . "', task_time = '" . $this->db->escape ( $time ) . "', date_added = '" . $this->db->escape ( $currentdate ) . "', description = '" . $this->db->escape ( $enable_desc ) . "', assign_to = '', recurrence = 'none', end_recurrence_date = '" . $currentdate . "', endtime = '" . $this->db->escape ( $time ) . "', tasktype = 'none', task_alert = '1', alert_type_sms = '0', alert_type_notification = '1', alert_type_email = '0' , task_group_by = '" . $this->db->escape ( $task_group_by ) . "' , enable_requires_approval = '2' , is_approval_required_forms_id = '" . $this->db->escape ( $data ['forms_id'] ) . "', required_approval = '1'  ";
		
		// echo "<hr>";
		$this->db->query ( $sql );
		
		$approval_taskid = $this->db->getLastId ();
		
		$this->load->model ( 'activity/activity' );
		$data2212 = array ();
		$data2212 ['approval_taskid'] = $approval_taskid;
		$data2212 ['task_group_by'] = $task_group_by;
		$data2212 ['facilities_id'] = $data ['facilities_id'];
		$data2212 ['task_date'] = $currentdate;
		$data2212 ['task_time'] = $time;
		$this->model_activity_activity->addActivitySave ( 'createapprovalTak', $data2212, 'query' );
	}
	public function addcreatetask2($data, $facilities_id) {
		$sql = "INSERT INTO `" . DB_PREFIX . "createtask` SET facilityId = '" . $facilities_id . "', task_date = '" . $this->db->escape ( $data ['task_date'] ) . "', task_time = '" . $this->db->escape ( $data ['task_time'] ) . "', date_added = '" . $this->db->escape ( $data ['date_added'] ) . "', description = '" . $this->db->escape ( $data ['description'] ) . "', assign_to = '" . $this->db->escape ( $data ['assign_to'] ) . "', recurrence = '" . $this->db->escape ( $data ['recurrence'] ) . "', recurnce_hrly = '" . $this->db->escape ( $data ['recurnce_hrly'] ) . "', end_recurrence_date = '" . $data ['end_recurrence_date'] . "', endtime = '" . $this->db->escape ( $data ['endtime'] ) . "', tasktype = '" . $this->db->escape ( $data ['tasktype'] ) . "', recurnce_day = '" . $this->db->escape ( $data ['recurnce_day'] ) . "', recurnce_month = '" . $this->db->escape ( $data ['recurnce_month'] ) . "', recurnce_week = '" . $this->db->escape ( $data ['recurnce_week'] ) . "',taskadded = '0', task_alert = '" . $this->db->escape ( $data ['task_alert'] ) . "', alert_type_sms = '" . $this->db->escape ( $data ['alert_type_sms'] ) . "', alert_type_notification = '" . $this->db->escape ( $data ['alert_type_notification'] ) . "', alert_type_email = '" . $this->db->escape ( $data ['alert_type_email'] ) . "', checklist = '" . $this->db->escape ( $data ['numChecklist'] ) . "', 
		
		rules_task = '" . $this->db->escape ( $data ['rules_task'] ) . "', 
		task_form_id = '" . $this->db->escape ( $data ['task_form_id'] ) . "', 
		tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "',
		pickup_locations_address = '" . $this->db->escape ( $data ['pickup_locations_address'] ) . "',
		pickup_locations_latitude = '" . $this->db->escape ( $data ['pickup_locations_latitude'] ) . "',
		pickup_locations_longitude = '" . $this->db->escape ( $pickup_locations_longitude ) . "',

		pickup_locations_time = '" . $this->db->escape ( $data ['pickup_locations_time'] ) . "',
		dropoff_locations_address = '" . $this->db->escape ( $data ['dropoff_locations_address'] ) . "', 
		dropoff_locations_time = '" . $this->db->escape ( $data ['dropoff_locations_time'] ) . "',
		dropoff_locations_latitude = '" . $this->db->escape ( $data ['dropoff_locations_latitude'] ) . "',
		dropoff_locations_longitude = '" . $this->db->escape ( $data ['dropoff_locations_longitude'] ) . "',

		transport_tags = '" . $this->db->escape ( $data ['transport_tags'] ) . "', 
		locations_id = '" . $this->db->escape ( $data ['locations_id'] ) . "',
		recurnce_hrly_recurnce = '" . $this->db->escape ( $data ['recurnce_hrly_recurnce'] ) . "',
		
		
		medication_tags = '" . $this->db->escape ( $data ['medication_tags'] ) . "',
		completion_alert = '" . $this->db->escape ( $data ['completion_alert'] ) . "',
		completion_alert_type_sms = '" . $this->db->escape ( $data ['completion_alert_type_sms'] ) . "',
		completion_alert_type_email = '" . $this->db->escape ( $data ['completion_alert_type_email'] ) . "',
		user_roles = '" . $this->db->escape ( $data ['user_roles'] ) . "',
		userids = '" . $this->db->escape ( $data ['userids'] ) . "',
		recurnce_hrly_perpetual = '" . $this->db->escape ( $data ['recurnce_hrly_perpetual'] ) . "',
		emp_tag_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "',
		due_date_time = '" . $this->db->escape ( $data ['due_date_time'] ) . "',
		task_status = '2',
		visitation_start_address = '" . $this->db->escape ( $data ['visitation_start_address'] ) . "',
		visitation_start_address_latitude = '" . $this->db->escape ( $data ['visitation_start_address_latitude'] ) . "', visitation_start_address_longitude = '" . $this->db->escape ( $data ['visitation_start_address_longitude'] ) . "', 
		visitation_start_time = '" . $this->db->escape ( $data ['visitation_start_time'] ) . "', 
		visitation_appoitment_address = '" . $this->db->escape ( $data ['visitation_appoitment_address'] ) . "',
		visitation_appoitment_time = '" . $this->db->escape ( $data ['visitation_appoitment_time'] ) . "', 
		visitation_appoitment_address_latitude = '" . $this->db->escape ( $data ['visitation_appoitment_address_latitude'] ) . "', visitation_appoitment_address_longitude = '" . $this->db->escape ( $data ['visitation_appoitment_address_longitude'] ) . "',
		visitation_tags = '" . $this->db->escape ( $data ['visitation_tags'] ) . "',
		visitation_start_facilities_id = '" . $this->db->escape ( $data ['visitation_start_facilities_id'] ) . "', 
		visitation_appoitment_facilities_id = '" . $this->db->escape ( $data ['visitation_appoitment_facilities_id'] ) . "',
		visitation_tag_id = '" . $this->db->escape ( $data ['visitation_tag_id'] ) . "',
		completed_times = '" . $this->db->escape ( $data ['completed_times'] ) . "',
		completed_alert = '" . $this->db->escape ( $data ['completed_alert'] ) . "',
		completed_late_alert = '" . $this->db->escape ( $data ['completed_late_alert'] ) . "',
		incomplete_alert = '" . $this->db->escape ( $data ['incomplete_alert'] ) . "',
		deleted_alert = '" . $this->db->escape ( $data ['deleted_alert'] ) . "',
		attachement_form = '" . $this->db->escape ( $data ['attachement_form'] ) . "',
		tasktype_form_id = '" . $this->db->escape ( $data ['tasktype_form_id'] ) . "' ,
		task_group_by = '" . $this->db->escape ( $data ['due_date_time'] ) . "', 
		enable_requires_approval = '" . $this->db->escape ( $data ['enable_requires_approval'] ) . "',
		approval_taskid = '" . $this->db->escape ( $data ['approval_taskid'] ) . "',
		response = '" . $this->db->escape ( $data ['response'] ) . "',
		distance_text = '" . $this->db->escape ( $data ['distance_text'] ) . "',
		distance_value = '" . $this->db->escape ( $data ['distance_value'] ) . "', 
		duration_text = '" . $this->db->escape ( $data ['duration_text'] ) . "',
		duration_value = '" . $this->db->escape ( $data ['duration_value'] ) . "', 
		
		
		iswaypoint = '" . $this->db->escape ( $data ['iswaypoint'] ) . "' ,
		bed_check_location_ids = '" . $this->db->escape ( $data ['bed_check_location_ids'] ) . "',
		
		is_create_task = '" . $data ['id'] . "',
		unique_id = '" . $this->db->escape ( $data ['unique_id'] ) . "',
		complete_status = '" . $this->db->escape ( $data ['complete_status'] ) . "'  ";
		
		// echo "<hr>";
		$this->db->query ( $sql );
		
		$task_id = $this->db->getLastId ();
		
		$this->load->model ( 'activity/activity' );
		$data ['task_id'] = $task_id;
		$data ['facilities_id'] = $facilities_id;
		$this->model_activity_activity->addActivitySave ( 'addcreatetask2', $data, 'query' );
		
		if ($data ['medication_tags']) {
			
			$this->load->model ( 'setting/tags' );
			
			$medication_tags1 = explode ( ',', $data ['medication_tags'] );
			
			foreach ( $medication_tags1 as $medicationtag ) {
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $data ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					$sql22 = "INSERT INTO `" . DB_PREFIX . "createtask_medications` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape ( $mdrug ['facilities_id'] ) . "', tags_id = '" . $this->db->escape ( $mdrug ['tags_id'] ) . "', tags_medication_details_id = '" . $this->db->escape ( $mdrug ['tags_medication_details_id'] ) . "' ";
					$this->db->query ( $sql22 );
				}
			}
		}
		
		if ($data ['transport_tags']) {
			
			$this->load->model ( 'createtask/createtask' );
			$travelWaypoints = $this->model_createtask_createtask->gettravelWaypoints ( $data ['id'] );
			if ($travelWaypoints != null && $travelWaypoints != "") {
				foreach ( $travelWaypoints as $travelWaypoint ) {
					$sql2233 = "INSERT INTO `" . DB_PREFIX . "createtask_by_transport` SET id = '" . $task_id . "', locations_address = '" . $this->db->escape ( $travelWaypoint ['locations_address'] ) . "', latitude = '" . $this->db->escape ( $travelWaypoint ['latitude'] ) . "', longitude = '" . $this->db->escape ( $travelWaypoint ['longitude'] ) . "', place_id = '" . $this->db->escape ( $travelWaypoint ['place_id'] ) . "' ";
					$this->db->query ( $sql2233 );
				}
			}
		}
	}
}