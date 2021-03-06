<?php
class Modelresidentminidashboard extends Model {
	public function getTotal($data = array()) {
		$details_of = trim($data ['details_of']);
		$total_for = trim($data ['total_for']);
		$from_date = trim($data ['from_date']);
		$to_date = trim($data ['to_date']);
		$queryString = "";
		switch ($details_of) {
			
			case 'notes':
				/*$queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags` 
				LEFT JOIN `" . DB_PREFIX . "notes` 
				ON `" . DB_PREFIX . "notes_tags`.`notes_id` = `" . DB_PREFIX . "notes`.`notes_id` 
				WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = " . $total_for . " 
				AND `" . DB_PREFIX . "notes_tags`.`date_added` >= '" . $from_date . " 00:00:00' AND `" . DB_PREFIX . "notes_tags`.`date_added`<= '" . $to_date . " 23:59:59'";*/
				//$queryString = " CALL GetNotesTotal(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				$queryString = " CALL getTotalNotes('1', '', '', '', '" . $total_for . "', '', '','','','','','', '', '', '', '', '','','" . $from_date . " 00:00:00','" . $to_date . " 23:59:59','','', '', '', '', '', '', '', '', '') ";
				break;
			
			case 'outofthecell':
				/*$queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags` 
				LEFT JOIN `" . DB_PREFIX . "tag_status` ON `" . DB_PREFIX . "notes_tags`.`tag_status_id` = `" . DB_PREFIX . "tag_status`.`tag_status_id` 
				WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = ".$total_for." AND `" . DB_PREFIX . "tag_status`.`out_from_cell` = 1 
				AND `" . DB_PREFIX . "tag_status`.`type` IN(3, 4)
				AND `" . DB_PREFIX . "notes_tags`.`date_added` >= '".$from_date." 00:00:00' AND `" . DB_PREFIX . "notes_tags`.`date_added`<= '".$to_date." 23:59:59'";*/
				//$queryString = "SELECT dt.emp_first_name,dt.emp_last_name,dt.ssn,dt.emp_extid,dt.room,dt.race, dl.location_name,df.facility, dnt.status_total_time , dnt.tag_status_id, dnt.comments, dnt.tag_status_ids, dt.tags_id, dt.facilities_id, dnt.fixed_status_id, (SELECT abc.name FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.tag_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.notes_id)) AS status_name1, (SELECT abc.name FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.tag_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.move_notes_id )) AS status_name2, (SELECT abc.name FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.fixed_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.notes_id )) AS Fix_Status_name, (SELECT abc.type FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.tag_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.move_notes_id )) AS TYPE, (SELECT abc.rule_action_content FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.fixed_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.notes_id )) AS Rule_Action_Content, (SELECT dgnt.date_added FROM dg_notes dgnt WHERE dgnt.notes_id=dnt.notes_id) AS Event_Date, (SELECT dgnt.notetime FROM dg_notes dgnt WHERE dgnt.notes_id=dnt.move_notes_id) AS NotesInTime, (SELECT dgnt.notetime FROM dg_notes dgnt WHERE dgnt.notes_id=dnt.notes_id) AS NotesOutTime , (SELECT dgnt.user_id FROM dg_notes dgnt WHERE dgnt.notes_id=dnt.move_notes_id) AS NotesInUserId, (SELECT dgnt.user_id FROM dg_notes dgnt WHERE dgnt.notes_id=dnt.notes_id) AS NotesOutUserId FROM dg_notes_tags dnt LEFT OUTER JOIN dg_tags dt ON dt.tags_id= dnt.tags_id LEFT OUTER JOIN dg_tag_status dts ON dnt.tag_status_id= dts.tag_status_id LEFT OUTER JOIN dg_notes dn ON dt.notes_id= dn.notes_id LEFT OUTER JOIN dg_locations dl ON dt.room= dl.locations_id LEFT OUTER JOIN dg_facilities df ON dt.facilities_id= df.facilities_id WHERE (dts.out_from_cell=1 OR dnt.fixed_status_id >0) AND dnt.facilities_id IN (49,50,55,47) AND dnt.date_added BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' AND dt.tags_id = " . $total_for;
				//$queryString = " CALL report_gettotaloutofcells('','" . $from_date . " 00:00:00','" . $to_date . " 23:59:59', '" . $total_for . "', '', '') ";
				$queryString = " CALL outofthecell_gettotaloutofthecell(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				//die;
				break;
			
			case 'incident':
				// Temp
				/*
				 * $queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags`
				 * LEFT JOIN `" . DB_PREFIX . "tag_status` ON `" . DB_PREFIX . "notes_tags`.`tag_status_id` = `" . DB_PREFIX . "tag_status`.`tag_status_id`
				 * WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = ".$total_for." AND `" . DB_PREFIX . "tag_status`.`out_from_cell` = 0
				 * AND `" . DB_PREFIX . "tag_status`.`type` IN(3, 4)
				 * AND `" . DB_PREFIX . "notes_tags`.`date_added` >= '".$from_date." 00:00:00' AND `" . DB_PREFIX . "notes_tags`.`date_added`<= '".$to_date." 23:59:59'";
				 */
				//$queryString = "SELECT DISTINCT f.forms_id FROM dg_forms f LEFT JOIN dg_notes_tags nt ON nt.notes_id=f.notes_id WHERE 1 = 1 AND form_design_parent_id = 0 AND f.is_discharge = '0' AND f.is_discharge = '0' AND f.custom_form_type = '10' AND f.date_added BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' AND nt.tags_id = '" . $total_for . "'";
				//"SELECT notes_description FROM dg_notes WHERE notes_description LIKE '%incident%'";
				/*$queryString = "SELECT * FROM dg_forms f LEFT JOIN dg_notes_tags nt ON nt.notes_id=f.notes_id 
				LEFT JOIN dg_notes dn ON dn.notes_id=nt.notes_id WHERE 1 = 1 AND 
				form_design_parent_id = 0 AND f.is_discharge = '0' AND f.is_discharge = '0' AND (f.custom_form_type = '10' OR LOWER(notes_description) LIKE '%incident%' ) AND f.date_added 
				BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' AND nt.tags_id = '" . $total_for . "'";*/
				$queryString = " CALL incident_gettotalincident(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				break;
			
			case 'activenotes':
				// Temp
				/*
				 * $queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags`
				 * LEFT JOIN `" . DB_PREFIX . "notes`
				 * ON `" . DB_PREFIX . "notes_tags`.`notes_id` = `" . DB_PREFIX . "notes`.`notes_id`
				 * WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = ".$total_for."
				 * AND `" . DB_PREFIX . "notes_tags`.`tag_status_id` <> 0
				 * AND `" . DB_PREFIX . "notes_tags`.`date_added` >= '".$from_date." 00:00:00' AND `" . DB_PREFIX . "notes_tags`.`date_added`<= '".$to_date." 23:59:59'";
				 */
				//$queryString = 'SELECT n.notes_id FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id LEFT JOIN dg_notes_by_keyword nk ON nk.notes_id = n.notes_id WHERE 1 = 1 AND n.status = 1 AND FIND_IN_SET(n.facilities_id, "49,50,55,47,61,61") and ( nt.tags_id = ' . $total_for . ' ) AND FIND_IN_SET(nk.keyword_id, "54") and ( n.`date_added` BETWEEN "' . $from_date . ' 00:00:00" AND "' . $to_date . ' 23:59:59" );';
				//$queryString = 'SELECT n.notes_id FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id LEFT JOIN dg_notes_by_keyword nk ON nk.notes_id = n.notes_id WHERE 1 = 1 AND n.status = 1 AND ( nt.tags_id = ' . $total_for . ' ) AND FIND_IN_SET(nk.keyword_id, "54") and ( n.`date_added` BETWEEN "' . $from_date . ' 00:00:00" AND "' . $to_date . ' 23:59:59" );';
				//$queryString = 'SELECT n.notes_id FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id LEFT JOIN dg_notes_by_keyword nk ON nk.notes_id = n.notes_id WHERE 1 = 1 AND n.status = 1 AND ( nt.tags_id = ' . $total_for . ' ) AND nk.keyword_id>0 and ( n.`date_added` BETWEEN "' . $from_date . ' 00:00:00" AND "' . $to_date . ' 23:59:59" );';
				$queryString = " CALL activenotes_gettotalactivenotes(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				break;
			
			case 'task':
				// Temp
				/*
				 * $queryString = "SELECT `" . DB_PREFIX . "notes_by_task`.`notes_by_task_id` FROM `" . DB_PREFIX . "notes_by_task`
				 * LEFT JOIN `" . DB_PREFIX . "notes` ON `" . DB_PREFIX . "notes_by_task`.`notes_id` = `" . DB_PREFIX . "notes`.`notes_id`
				 * WHERE `" . DB_PREFIX . "notes_by_task`.`tags_id` = ".$total_for."
				 * AND `" . DB_PREFIX . "notes_by_task`.`date_added` >= '".$from_date." 00:00:00' AND `" . DB_PREFIX . "notes_by_task`.`date_added`<= '".$to_date." 23:59:59'";
				 */
				//$queryString = "SELECT n.notes_id FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id WHERE 1 = 1 AND n.status = 1 AND n.task_id = '0' AND ( nt.tags_id = " . $total_for . " ) AND ( n.`date_added` BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' )";
				/*$queryString = "SELECT id FROM `dg_createtask` WHERE taskadded = '0' AND enable_requires_approval !=1 
				AND (`date_added` BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' or enable_requires_approval = 2 ) 
				AND ( emp_tag_id = '" . $total_for . "' or medication_tags = '" . $total_for . "' or visitation_tag_id = '" . $total_for . "' or FIND_IN_SET('" . $total_for . "', transport_tags) )";*/
				$queryString = " CALL task_gettotaltask(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				break;
			
			case 'attachment':
				/*$queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags` 
				LEFT JOIN `" . DB_PREFIX . "tag_status` ON `" . DB_PREFIX . "notes_tags`.`tag_status_id` = `" . DB_PREFIX . "tag_status`.`tag_status_id` 
				WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = ".$total_for." AND `" . DB_PREFIX . "tag_status`.`out_from_cell` = 0 
				AND `" . DB_PREFIX . "tag_status`.`type` IN(3, 4)
				AND `dg_notes_tags`.`date_added` >= '".$from_date." 00:00:00' AND `dg_notes_tags`.`date_added`<= '".$to_date." 23:59:59'";*/
				//$queryString = "SELECT notes_tags_id FROM dg_notes_tags n, dg_notes_media nm WHERE nm.notes_id=n.notes_id AND nm.notes_file IS NOT NULL AND n.tags_id = '" . $total_for . "' AND ( n.`date_added` BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' )";
				$queryString = " CALL attachment_gettotalattachment(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				break;
			
			case 'medicine':
				//$queryString = "SELECT count( n.notes_id) as total FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id WHERE 1 = 1 AND n.status = 1 and n.tasktype = '1' and ( nt.tags_id = '" . $total_for . "' ) AND ( n.`date_added` BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' )";
				//$queryString = "SELECT n.notes_id FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id WHERE 1 = 1 AND n.status = 1 and n.tasktype = '2' and ( nt.tags_id = '" . $total_for . "' ) AND ( n.`date_added` BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' )";
				/*$queryString = "SELECT n.notes_id FROM `dg_notes` n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id 
				LEFT JOIN dg_tasktype dnt ON dnt.task_id = n.tasktype WHERE 1 = 1 AND n.status = 1 
				AND dnt.type = '4' AND ( nt.tags_id = '" . $total_for . "' ) AND ( n.`date_added` BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' )";*/
				$queryString = " CALL medicine_gettotalmedicine(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				break;
			
			case 'case':
				/*$queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags` 
				LEFT JOIN `" . DB_PREFIX . "tag_status` ON `" . DB_PREFIX . "notes_tags`.`tag_status_id` = `" . DB_PREFIX . "tag_status`.`tag_status_id` 
				WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = ".$total_for." AND `" . DB_PREFIX . "tag_status`.`out_from_cell` = 0 
				AND `" . DB_PREFIX . "tag_status`.`type` IN(3, 4)
				AND `dg_notes_tags`.`date_added` >= '".$from_date." 00:00:00' AND `dg_notes_tags`.`date_added`<= '".$to_date." 23:59:59'";*/
				//$queryString = "SELECT tags_ids FROM dg_notes_by_case_file WHERE 1 = 1 AND tags_ids = '" . $total_for . "' AND case_number != '' AND ( date_added BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' )";
				$queryString = " CALL case_gettotalcase(" . $total_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
				break;
			
			/*case 'movement':
				$queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags` 
				LEFT JOIN `" . DB_PREFIX . "tag_status` ON `" . DB_PREFIX . "notes_tags`.`tag_status_id` = `" . DB_PREFIX . "tag_status`.`tag_status_id` 
				WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = " . $total_for . " AND `" . DB_PREFIX . "tag_status`.`out_from_cell` = 0 
				AND `" . DB_PREFIX . "tag_status`.`type` IN(3, 4)
				AND `dg_notes_tags`.`date_added` >= '" . $from_date . " 00:00:00' AND `dg_notes_tags`.`date_added`<= '" . $to_date . " 23:59:59'";
				break;*/
			
			default :
				/*$queryString = "SELECT `" . DB_PREFIX . "notes_tags`.`notes_tags_id` FROM `" . DB_PREFIX . "notes_tags` 
				LEFT JOIN `" . DB_PREFIX . "tag_status` ON `" . DB_PREFIX . "notes_tags`.`tag_status_id` = `" . DB_PREFIX . "tag_status`.`tag_status_id` 
				WHERE `" . DB_PREFIX . "notes_tags`.`tags_id` = " . $total_for . " AND `" . DB_PREFIX . "tag_status`.`out_from_cell` = 0 
				AND `" . DB_PREFIX . "tag_status`.`type` IN(3, 4)
				AND `dg_notes_tags`.`date_added` >= '" . $from_date . " 00:00:00' AND `dg_notes_tags`.`date_added`<= '" . $to_date . " 23:59:59'";*/
				$queryString = " CALL getTotalNotes('1', '', '', '', '" . $total_for . "', '', '','','','','','', '', '', '', '', '','','" . $from_date . " 00:00:00','" . $to_date . " 23:59:59','','', '', '', '', '', '', '', '', '') ";
				break;
		}
		
		$queryke = $this->db->query ( $queryString );
		return $queryke->rows[0]['total'];
		// return count ( $queryke->rows );
		// echo '<pre>';
		// print_r($queryke->rows);
	}
	
	

	public function getOTCRecords($data_for, $from_date, $to_date) { 
		/*$queryString = "SELECT dnt.notes_id,dt.emp_first_name,dt.emp_last_name,dt.ssn,dt.emp_extid, 
		dt.room,dt.race, dl.location_name, df.facility, dnt.status_total_time, dnt.tag_status_id, dnt.comments, dnt.tag_status_ids, 
		dt.tags_id, dt.facilities_id, dnt.fixed_status_id, (SELECT abc.name FROM dg_tag_status abc WHERE abc.tag_status_id IN 
		( SELECT dd.tag_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.notes_id)) AS status_name1, 
		(SELECT abc.name FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.tag_status_id FROM dg_notes_tags dd 
		WHERE dd.notes_id=dnt.move_notes_id )) AS status_name2, (SELECT abc.name FROM dg_tag_status abc WHERE 
		abc.tag_status_id IN ( SELECT dd.fixed_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.notes_id )) 
		AS Fix_Status_name, (SELECT abc.type FROM dg_tag_status abc WHERE abc.tag_status_id IN 
		( SELECT dd.tag_status_id FROM dg_notes_tags dd WHERE dd.notes_id=dnt.move_notes_id )) AS TYPE, 
		(SELECT abc.rule_action_content FROM dg_tag_status abc WHERE abc.tag_status_id IN ( SELECT dd.fixed_status_id 
		FROM dg_notes_tags dd WHERE dd.notes_id=dnt.notes_id )) AS Rule_Action_Content, (SELECT dgnt.date_added FROM 
		dg_notes dgnt WHERE dgnt.notes_id=dnt.notes_id) AS Event_Date, (SELECT dgnt.notetime FROM dg_notes dgnt WHERE 
		dgnt.notes_id=dnt.move_notes_id) AS NotesInTime, (SELECT dgnt.notetime FROM dg_notes dgnt WHERE 
		dgnt.notes_id=dnt.notes_id) AS NotesOutTime , (SELECT dgnt.user_id FROM dg_notes dgnt WHERE 
		dgnt.notes_id=dnt.move_notes_id) AS NotesInUserId, (SELECT dgnt.user_id FROM dg_notes dgnt WHERE 
		dgnt.notes_id=dnt.notes_id) AS NotesOutUserId FROM dg_notes_tags dnt LEFT OUTER JOIN dg_tags dt ON dt.tags_id= dnt.tags_id 
		LEFT OUTER JOIN dg_tag_status dts ON dnt.tag_status_id= dts.tag_status_id LEFT OUTER JOIN dg_notes dn ON 
		dt.notes_id= dn.notes_id LEFT OUTER JOIN dg_locations dl ON dt.room= dl.locations_id LEFT OUTER JOIN dg_facilities df 
		ON dt.facilities_id= df.facilities_id WHERE (dts.out_from_cell=1 OR dnt.fixed_status_id >0) 
		AND dnt.date_added BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' AND dt.tags_id=$data_for ORDER BY dnt.date_added DESC";*/
		$queryString = " CALL outofcelldata(" . $data_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";

		$queryOtc = $this->db->query ( $queryString );
		return $queryOtc->rows;
	}

	public function setTimeZone() { 
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
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
		}

		return $timezone_name;
	}

	public function getDifference($NotesOutTime='') { 
		date_default_timezone_set ( $this->setTimeZone() );
		// Creating DateTime objects
		$dateTimeObject1 = date_create($NotesOutTime); 
		$dateTimeObject2 = date_create(date('Y-m-d H:i:s')); 
		
		// Calculating the difference between DateTime objects
		$interval = date_diff($dateTimeObject1, $dateTimeObject2); 
		
		// Printing result in hours
		//echo ("Difference in hours is:");
		//echo $interval->h;
		//echo "\n<br/>";
		$minutes = $interval->days * 24 * 60;
		$minutes += $interval->h * 60;
		$minutes += $interval->i;
		
		//Printing result in minutes
		//echo("Difference in minutes is:");
		//echo $minutes.' minutes';
		return $minutes;
	}

	public function getOTCDetails($dataCntrl = array()) { 
		//echo '<pre>xxx'; print_r($dataCntrl); echo '</pre>';
		$data_for = $dataCntrl ['data_for'];
		$from_date = $dataCntrl ['from_date'];
		$to_date = $dataCntrl ['to_date'];
		$required_details = $dataCntrl ['required_details'];
		$facility_id = $dataCntrl ['facilities_id'];
		$totalAllowedHours = 0;
		//Total Out of the Cell Hours Starts
		$hourString = "SELECT `setting_data` FROM `dg_activecustomer` WHERE customer_key=(SELECT customer_key FROM dg_facilities 
		WHERE facilities_id = $facility_id)";
		$queryHour = $this->db->query ( $hourString );
		$hourArray = $queryHour->rows;

		if(COUNT($hourArray)>0) { 
			$hourArray = unserialize($hourArray[0]['setting_data']);

			if($hourArray['duration_type']==1) { 
				$totalAllowedHours = $hourArray['out_the_sell'] / 60;
			}

			if($hourArray['duration_type']==2) { 
				$totalAllowedHours = $hourArray['out_the_sell'];
			}

			if($hourArray['duration_type']==3) { 
				$totalAllowedHours = $hourArray['out_the_sell'] * 24;
			}
		}
		//Total Out of the Cell Hours Ends
		
		$data = array ();
		$data ['ref_deniel_dates'] = array ();
		$data ['complaint_dates'] = array ();
		$data ['non_complaint_dates'] = array ();
		$otcFilterData = array ();

		//Check for last 1 month record
		$otcTempData = $this->getOTCRecords($data_for, date('Y-m-d', strtotime('-1 month', strtotime($from_date))), $from_date);
		if(COUNT($otcTempData)>0) {
			foreach($otcTempData as $otcTempKey=>$otcTempVal) { 
				$from_date = date ( "Y/m/d", strtotime ( $otcTempVal ['Event_Date'] ) );
				break;
			}
		}
		
		$otcData = $this->getOTCRecords($data_for, $from_date, $to_date);
		
		/*echo '<pre>';
		print_r($otcData);
		die;*/
		
		foreach ( $otcData as $otcKey => $otcValue ) { 
			$Event_Date = date ( "n/j/Y", strtotime ( $otcValue ['Event_Date'] ) );
			$otcFilterData [$Event_Date] [] = $otcValue;
		}
	
		foreach ( $otcFilterData as $otcFKey => $otcFValue ) { 
			
			$totalTime = 0;
			$NotesOutTime = '';
			$fixedStatusIdFlag = 0;
			foreach ( $otcFValue as $otcTKey => $otcTValue ) { 
				$NotesOutTime = $otcTValue ['Event_Date'];
				$totalTime += $otcTValue ['status_total_time'] ? 0 : $this->getDifference($NotesOutTime);
				
				// if($otcFKey!='9/11/2021') {
				//$fixedStatusIdFlag = $otcTValue ['fixed_status_id'] > 0 && $fixedStatusIdFlag == 0 ? 1 : 0;
				// }
				if($otcTValue ['fixed_status_id'] > 0 && $fixedStatusIdFlag == 0) { 
					$fixedStatusIdFlag = 1;
				}
				
				if($otcFKey=='10/6/2021') { 
					//$totalTime += 676876;
				}
			}

			if($otcFKey=='10/20/2021') { 
				//$totalTime = 10.1;
			}
			
			if ($fixedStatusIdFlag) { 
				$data ['ref_deniel_dates'] [] = $otcFKey;
			} else { 
				if ($totalAllowedHours <= ($totalTime / 60)) { 
					$data ['complaint_dates'] [] = $otcFKey;
				} else { 
					$data ['non_complaint_dates'] [] = $otcFKey;
				}
			}

			if($otcFKey=='10/20/2021') { 
				//echo $this->getDifference($NotesOutTime);
				
				//echo $date_added11 = date('Y-m-d H:i:s');
				//echo $totalTime;
				//echo '<pre>';
				//print_r($otcFValue);
				//die;
				//$totalTime += 676876;
			}
		}

		$start = $from_date; //start date
		$end = $to_date; //end date

		$dates = array();
		$start = $current = strtotime($start);
		$end = strtotime($end);
		$skipDateFlag = false;
		$refFlag = false;
		$refDate = '';

		while ($current <= $end) { 
			$dates[] = date('n/j/Y', $current);
			if(in_array(date('n/j/Y', $current), $data ['ref_deniel_dates'])) { 
				$skipDateFlag = true;
				$refFlag = true;
				$refDate = date('n/j/Y', $current);
			}

			if(in_array(date('n/j/Y', $current), $data ['non_complaint_dates']) || in_array(date('n/j/Y', $current), $data ['complaint_dates'])) {
				$refFlag = false;
				$refDate = '';
			}

			if($refFlag && !$skipDateFlag) { 
				$data ['ref_deniel_dates'] [] = date('n/j/Y', $current);
				$otcFilterData [date('n/j/Y', $current)] = $otcFilterData [$refDate];
			}
			$skipDateFlag = false;
			$current = strtotime('+1 days', $current);
		}

		$data ['otcFilterData'] = $otcFilterData;
		//now $dates hold an array of all the dates within that date range
		/*echo '<pre>';
		print_r($dates);
		die;*/

		/*echo '<pre>';
		print_r($data);
		die;*/

		if ($required_details == 1) {
			$detailsBlockString = '';
			$eventDate = $from_date;
			$detailsBlock = $data ['otcFilterData'] [date ( 'n/j/Y', strtotime ( $from_date ) )];
			
			foreach ( $detailsBlock as $kBlock => $vBlock ) { 
				if($vBlock ['fixed_status_id'] > 0) { 
					$detailsBlockString .= ! empty ( $vBlock ['NotesOutTime'] ) ? '<b>Time : </b>' . $vBlock ['NotesOutTime'] . '<br>' : '<b>Time : </b>N.A.<br>';
					$detailsBlockString .= ! empty ( $vBlock ['Fix_Status_name'] ) ? '<span style="color:red"><b>Status Name : </b>' . $vBlock ['Fix_Status_name'] . '</span><br>' : '<span style="color:red"><b>Status Name : </b>N.A.<br></span>';
					$detailsBlockString .= ! empty ( $vBlock ['tag_status_ids'] ) ? '<b>Status Values : </b>' . $vBlock ['tag_status_ids'] . '<br>' : '<b>Status Values : </b>N.A.<br>';
					$detailsBlockString .= ! empty ( $vBlock ['comments'] ) ? '<b>Comments : </b>' . $vBlock ['comments'] . '<br>' : '<b>Comments : </b>N.A.<br>';
					$detailsBlockString .= "<br>";
					$eventDate = ! empty ( $vBlock ['Event_Date'] ) ? $vBlock ['Event_Date'] : $eventDate;
				}
			}
			$data ['detailsBlockString'] = !empty($detailsBlockString) ? $detailsBlockString : 'No Data Found!';
			$data ['eventDate'] = date ( 'n/j/Y', strtotime ( $eventDate ));
			// echo $detailsBlockString;
			// echo '<pre>';
			// print_r($data['otcFilterData'][date('n/j/Y', strtotime($from_date))]);
			// die;
			// $datamd ['total_data'] = $this->model_resident_minidashboard->getOTCDetails ( $datamd );
		}
		// $data ['non_complaint_dates'] = array('9/1/2021','9/2/2021','9/4/2021','9/19/2021','9/26/2021');
		
		return $data;
		
		
		
		return $responseArray = array (
				'9/1/2021',
				'9/2/2021',
				'9/4/2021',
				'9/19/2021',
				'9/26/2021' 
		);
	}
	
	public function getMovementDetails($dataCntrl = array()) {
		$data_for = $dataCntrl ['data_for'];
		$from_date = $dataCntrl ['from_date'];
		$to_date = $dataCntrl ['to_date'];
		$required_details = $dataCntrl ['required_details'];
		$totalAllowedHours = 7;
		$data = array ();
		$existingMovementIcons = array ();
		$data ['movement_block_string'] = '';
		
		/*$queryString = "SELECT DISTINCT t.*, te.enroll_image, ts.name,ts.tag_status_id,ts.type,ts.rule_action_content, ts.color_code, ts.image, ts.facility_type, 
		ts.is_facility, ts.status_type, ts.out_from_cell, ts.track_time, l.location_name, f.facility,c.customlistvalues_name 
		FROM `dg_tags` t LEFT JOIN dg_tags_enroll te ON te.tags_id = t.tags_id AND SUBSTR(te.enroll_image, 1,4)='http' LEFT JOIN 
		dg_tag_status ts ON ts.tag_status_id = t.role_call LEFT JOIN dg_locations l ON l.locations_id = t.room LEFT JOIN dg_facilities f 
		ON f.facilities_id = t.facilities_id LEFT JOIN dg_customlistvalues c ON c.customlistvalues_id = t.customlistvalues_id WHERE 1 = 1 
		AND t.discharge = '0' AND t.tags_status_in = 'Admitted'  
		AND t.date_added BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' AND t.tags_id = $data_for";*/

		/*$queryString = "SELECT * FROM dg_notes_tags nt 
		LEFT JOIN dg_tag_status ts ON ts.tag_status_id = nt.tag_status_id 
		WHERE ts.type = 3 AND nt.date_added BETWEEN '" . $from_date . " 00:00:00' AND '" . $to_date . " 23:59:59' AND nt.tags_id = $data_for";*/

		$queryString = " CALL movementdata(" . $data_for . ",'" . $from_date . " 00:00:00','" . $to_date . " 23:59:59')";
		
		$queryMovement = $this->db->query ( $queryString );
		
		$movementData = $queryMovement->rows;

		
		foreach ( $movementData as $movKey => $moveFValue ) {
			if (! in_array ( $moveFValue ['image'], $existingMovementIcons )) {
				$existingMovementIcons [] = $moveFValue ['image'];
				$data ['movement_block_string'] .= '<div class="col-md-3 mb-3 mb-md-3">
														<img class="center_image" src="' . $moveFValue ['image'] . '" title="' . $moveFValue ['name'] . '" alt="' . $moveFValue ['name'] . '" height="50px" width="50px">
													</div>';
			}
		}
		
		$data ['movement_block_string'] = ! empty ( $data ['movement_block_string'] ) ? $data ['movement_block_string'] : 'No Data Found!';
		
		return $data;
		
		
	}

	
	public function getcaseTotal($data = array()) {
		$details_of = trim($data ['details_of']);
		$total_for = trim($data ['total_for']);
		$startDate = trim($data ['startDate']);
		$endDate = trim($data ['endDate']);
		$facilities_id = trim($data ['facilities_id']);
		$username = trim($data ['username']);
		$user_id = trim($data ['user_id']);
		$activecustomer_id = trim($data ['activecustomer_id']);
		$customer_key = trim($data ['customer_key']);
		//code start for get parent and its child facility_id
		
		$this->load->model ( 'facilities/facilities' );
		$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$facilities_ids = '';
		if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
			$ddss [] = $facility_info ['client_facilities_ids'];
			$ddss [] = $data ['facilities_id'];
			$sssssddsg = explode ( ",", $facility_info ['client_facilities_ids'] );
			$abdcg = array_unique ( $sssssddsg );
			$cids = array ();
			foreach ( $abdcg as $fid ) {
				$cids [] = $fid;
			}
			$abdcgs = array_unique ( $cids );
			foreach ( $abdcgs as $fid2 ) {
				$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
				if ($facilityinfo ['client_facilities_ids'] != null && $facilityinfo ['client_facilities_ids'] != "") {
					$ddss [] = $facilityinfo ['client_facilities_ids'];
				}
			}
			
			$ddss = array_unique ( $ddss );
			$sssssdd = implode ( ",", $ddss );
			$facilities_ids =  $sssssdd;
		} else {
			if ($data ['facilities_id'] != NULL && $data ['facilities_id'] != "") {
				$facilities_ids = $data ['facilities_id'];
			}
		}
		
		//code end for get parent and its child facility_id
		
		$queryString = "";
		
		switch ($details_of) {
			case 'notes':
				$queryString = " CALL case_TotalNotes('1','','".$username."','". $customer_key ."') ";
				break;
			case 'incident':
				$queryString = " CALL case_incident_gettotalincident ('','','','','".$username."','". $customer_key ."')";
				break;
			case 'task':
				$queryString = " CALL case_task_gettotaltask('','".$startDate."','".$endDate."','','".$username."','".$activecustomer_id."')";
				break;
			case 'inmate':
				$queryString = " CALL case_inmate_gettotalinmate('".$activecustomer_id."','".$user_id."')";
				break;	
			case 'aca':
				//$queryString = " CALL case_aca_gettotalaca('".$activecustomer_id."','".$user_id."')";
				break;	
			default :
				$queryString = " CALL case_TotalNotes('1','','".$username."','". $customer_key ."') ";
				break;
		}
		//echo $queryString ;
		
		
		//CALL case_inmate_gettotalinmate(14,1421);
		$queryke = $this->db->query ( $queryString );
		return $queryke->rows[0]['total'];
	}
	
	public function getToDoTaskList($data = array()) {
		$details_of = trim($data ['details_of']);
		$total_for = trim($data ['total_for']);
		$from_date = trim($data ['from_date']);
		$to_date = trim($data ['to_date']);
		$facilities_id = trim($data ['facilities_id']);
		$username = trim($data ['username']);
		$activecustomer_id = trim($data ['activecustomer_id']);
		$customer_key = trim($data ['customer_key']);
		$emp_tag_id = trim($data ['emp_tag_id']);
		$queryString = "";
		$queryString = " CALL case_task_gettotaltask_list('".$emp_tag_id."','','','','".$username."','".$activecustomer_id."')";	
		$query = $this->db->query ( $queryString );
		return $query->rows;
	}
	
	public function case_task_gettotaltask_list_groupby($data = array()) {
		$details_of = trim($data ['details_of']);
		$total_for = trim($data ['total_for']);
		$startDate = trim($data ['startDate']);
		$endDate = trim($data ['endDate']);
		$facilities_id = trim($data ['facilities_id']);
		$username = trim($data ['username']);
		$activecustomer_id = trim($data ['activecustomer_id']);
		$customer_key = trim($data ['customer_key']);
		$queryString = "";
		$queryString = " CALL case_task_gettotaltask_list_groupby('','".$username."','','".$startDate."','".$endDate."','')";	
		$query = $this->db->query ( $queryString );
		return $query->rows;
	}
	
	
	
	


	

}


		