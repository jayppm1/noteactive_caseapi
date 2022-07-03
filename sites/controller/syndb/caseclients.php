<?php  
class Controllersyndbcaseclients extends Controller {
	public function index() {
		
		$this->load->model('syndb/syndb');
		$this->load->model('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('setting/timezone');
		$this->load->model('notes/case');
		$this->load->model('facilities/facilities');
			
		
		

		//var_dump($previousDate);
		//var_dump($current_date);
		//die;
		if ($this->request->get['startDate'] != '' && $this->request->get['startDate'] != null) {
			$previousDate = $this->request->get['startDate'];
		}else{
			$previousDate = date('Y-m-d', strtotime('-100 day', strtotime('now')));
		}
		
		if ($this->request->get['endDate'] != '' && $this->request->get['endDate'] != null) {
			$current_date = $this->request->get['endDate'];
		}else{
			$current_date = date('Y-m-d', strtotime('-1 day', strtotime('now')));
		}
		
		if ($this->request->get['tags_id'] != '' && $this->request->get['tags_id'] != null) {
			$tags_id = $this->request->get['tags_id'];
		}else{
			$tags_id = '0';
		}
		
		$begin = new DateTime($previousDate);
		$end = new DateTime($current_date);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);

		if($tags_id > 0){
			foreach ($period as $dt) {
				//echo $dt->format("l Y Y-m-d H:i:s\n");
				$todaydate =  $dt->format("Y-m-d");
				
				/*$data = array(
					'status'        => 1,
					'discharge' => 1,  
					'all_record' => 1,  
					'sort' => 'emp_tag_id',
					'order' => 'ASC',
					
				);
				
				$tags = $this->model_setting_tags->getTags($data);
				*/
				
				$tag = $this->model_setting_tags->getTag($tags_id);
				
				//var_dump($tag);
				
				//echo "<hr>";
			
				//foreach($tags as $tag){
				$facility = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
				$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
				
				date_default_timezone_set($timezone_info['timezone_value']);
				
				$startDate = date('Y-m-d', strtotime('-1 day', strtotime('now')));
				$endDate = date('Y-m-d', strtotime('-1 day', strtotime('now')));
				
				$start_date = date('Y-m-d', strtotime('-1 day', strtotime('now')));
				$current_date = date('Y-m-d H:i:s', strtotime('-1 day', strtotime('now')));
				$current_time = date('H:i:s',strtotime('now'));
				
				$todaydate11 = $todaydate.' '.$current_time;
				//var_dump($tag['tags_id']);
				$data2 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				$ttotalnotes = $this->model_notes_case->getTotalnotess($data2); 
			
			
			
				$data12 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'emp_tag_id' => $tag['tags_id'],
					'form_search' => 'all',
					'facilities_id' => $tag['facilities_id'],
				);
				$ttotalforms = $this->model_notes_case->getTotalnotess($data12); 
				
			
				$data1dd2 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'emp_tag_id' => $tag['tags_id'],
					'task_search' => 'all',
					'facilities_id' => $tag['facilities_id'],
				);
				
				$ttotaltasks = $this->model_notes_case->getTotalnotess($data1dd2); 
			
			
				$data3 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					//'discharge' => '1',
					'tags_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				$intakecount = $this->model_setting_tags->getTotalTags($data3);
			
		
			
				$data4 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'discharge' => '2',
					'tags_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
			
				$dischargecount = $this->model_setting_tags->getTotalTags($data4); 
		
			
				$data5 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'activenote' => '44',
					'keyword' => 'incident',
					'search_acitvenote_with_keyword' => '1',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				
				$incidentcount = $this->model_notes_case->getTotalnotess($data5); 
			
				$data11 = array(
						'note_date_from' => $todaydate,
						'note_date_to' => $todaydate,
						'activenote' => '38',
						'keyword' => 'medication',
						'search_acitvenote_with_keyword' => '1',
						'emp_tag_id' => $tag['tags_id'],
						'facilities_id' => $tag['facilities_id'],
				);
			
				$pillcallcount = $this->model_notes_case->getTotalnotess($data11);
			
				$data6 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'tasktype'  => '25',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				
				$sightandsoundcount = $this->model_notes_case->getTotalnotess($data6); 
				
				$data7 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'highlighter'  => 'all',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				
				$highlightercount = $this->model_notes_case->getTotalnotess($data7); 
				
				//var_dump($highlightercount);
				//echo "<hr>";
			
				$data8 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'text_color'  => '1',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				
				$colorcount = $this->model_notes_case->getTotalnotess($data8); 
			
				$data9 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'review_notes'  => '1',  
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
				
				$reviewcount = $this->model_notes_case->getTotalnotess($data9); 
			
			
				$data10 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'activenote'  => 'all',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
				);
			
				$activenotecount = $this->model_notes_case->getTotalnotess($data10); 
			
			
				$data11 = array(
					'note_date_from' => $todaydate,
					'note_date_to' => $todaydate,
					'tasktype'  => '11',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' =>$tag['facilities_id'],
				);
				
				$becdcheckcount = $this->model_notes_case->getTotalnotess($data11); 
				
				
			 
				$casedata = array(
					'ttotaltasks' => $ttotaltasks,
					'ttotalnotes' => $ttotalnotes,
					'ttotalforms' => $ttotalforms,
					'intakecount' => $intakecount,
					'sightandsoundcount' => $sightandsoundcount,
					'incidentcount' => $incidentcount,
					'highlightercount' => $highlightercount,
					'colorcount' => $colorcount,
					'activenotecount' => $activenotecount,
					'medicationcount' => $pillcallcount,
					'bedcheckcount' => $becdcheckcount,
					'facilities_id' => $tag['facilities_id'],
					'intake_date' => $tag['date_added'],
					'discharge_date' => $tag['discharge_date'],
					'roll_call' => $tag['role_call'],
					'tags_id' => $tag['tags_id'],
					'discharge' => $tag['discharge'],
					'date_added' => $todaydate11,
					'date_updated' => $todaydate11,
					'start_date' => $todaydate,
					'reviewcount' => $reviewcount,
				
				);	

			
				//$this->model_notes_case->insertTotal($casedata);
				
				//$sql = "UPDATE `" . DB_PREFIX . "notes`  SET  is_casecount = '1' where tags_id = '".$tag['tags_id']."' ";
				//$query = $this->db->query($sql);
			
			//}
				
			}
		}
			
		echo "Success";	 
	}
	
	}