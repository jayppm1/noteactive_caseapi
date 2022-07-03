<?php   
class Controllercommonheadercasetask extends Controller {
	protected function index() {
		try{
			
		$this->load->model('setting/tags');
		$tags_id = $this->request->get['tags_id'];
        //$tag = $this->model_setting_tags->getTag($tags_id);
		$this->data['emp_tag_id'] = $tag['emp_tag_id'];
		$this->load->model('facilities/facilities');
		$tags_id = $this->request->get['tags_id'];
        $tag = $this->model_setting_tags->getTag($tags_id);
		
		$this->data['stickynote'] = $tag['stickynote'];
		$this->data['tags_id'] = $tag['tags_id'];
		$this->data['ssn'] = $tag['ssn'];
		$this->data['prescription'] = $tag['prescription'];
		$this->data['location_address'] = $tag['location_address'];
		$this->data['dob'] = $tag['dob'];
		$this->data['room'] = $tag['room'];
		
		$facilitynames = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
		$this->data['facilities_id'] = $facilitynames['facility']; 
			
		$this->data['route']  = $this->request->get['route'];
		
		$this->data['tag'] = $tag;
		
		$this->data['tagname'] = $tag['emp_first_name'] . ' ' . $tag['emp_last_name'];

		$url2 = "";
		$url3 = "";
            if ($this->request->get['search_time_start'] != null && $this->request->get['search_time_start'] != "") {
               $url2 .= '&search_time_start=' . $this->request->get['search_time_start'];
               $url3 .= '&search_time_start=' . $this->request->get['search_time_start'];
            }
            
            if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
                $url2 .= '&note_date_from=' . $this->request->get['note_date_from'];
                $url3 .= '&note_date_from=' . $this->request->get['note_date_from'];
            }
            if ($this->request->get['search_time_to'] != null && $this->request->get['search_time_to'] != "") {
                $url2 .= '&search_time_to=' . $this->request->get['search_time_to'];
                $url3 .= '&search_time_to=' . $this->request->get['search_time_to'];
            }
			if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
                $url2 .= '&note_date_to=' . $this->request->get['note_date_to'];
                $url3 .= '&note_date_to=' . $this->request->get['note_date_to'];
            }

			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
			if ($this->request->get['type'] != null && $this->request->get['type'] != "") {
                $url2 .= '&type=' . $this->request->get['type'];
                $url3 .= '&type=' . $this->request->get['type'];
               $this->data['type'] = $this->request->get['type'];
            }
			
			 if ($this->request->get['popup'] != null && $this->request->get['popup'] != "") {
           $url2 .= '&popup=' . $this->request->get['popup'];
           $url3 .= '&popup=' . $this->request->get['popup'];
			$this->data['popup'] =  $this->request->get['popup'];
        }
		
		if (isset($this->request->post['note_date_from'])) {
			$this->data['note_date_from'] = $this->request->post['note_date_from'];
			$date = str_replace('-', '/', $this->request->post['note_date_from']);
			$res = explode("/", $date);
			$note_date_from = $res[2] . "-" . $res[0] . "-" . $res[1];
			
		} else {
			$this->data['note_date_from'] = '';
		}
		
		if (isset($this->request->post['note_date_to'])) {
			$this->data['note_date_to'] = $this->request->post['note_date_to'];
			
			$date = str_replace('-', '/', $this->request->post['note_date_to']);
			$res = explode("/", $date);
			$note_date_to = $res[2] . "-" . $res[0] . "-" . $res[1];
		} else {
			$this->data['note_date_to'] = '';
		}
		
		if (isset($this->request->post['search_time_start'])) {
			$this->data['search_time_start'] = $this->request->post['search_time_start'];
			$search_time_start = $this->request->post['search_time_start'];
		} else {
			$this->data['search_time_start'] = '';
		}
		
		if (isset($this->request->post['search_time_to'])) {
			$this->data['search_time_to'] = $this->request->post['search_time_to'];
			$search_time_to = $this->request->post['search_time_to'];
		} else {
			$this->data['search_time_to'] = '';
		}
		
		//$this->data['href_event'] = $this->url->link('notes/notes/insert', '' . $url2 . '&tags_id=' . $tag['tags_id'], 'SSL');
		$this->data['add_task'] = $this->url->link('notes/createtask', '' . $url2, 'SSL');
		//$this->data['add_task'] = $this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL');
		
		$this->data['add_event'] = $this->url->link('notes/createtask', '' . $url2, 'SSL');
		//$this->data['add_event'] = $this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL');
		
		
		$route  = $this->request->get['route'];
		
		$this->data['complete_task'] = $this->url->link(''.$route.'&task_type=1', '' . $url2, 'SSL');
		$this->data['incomplete_task'] = $this->url->link(''.$route.'&task_type=2', '' . $url2, 'SSL');
		$this->data['pending_task'] = $this->url->link(''.$route.'&task_type=3', '' . $url2, 'SSL');

		
		if($this->request->get['type'] == '4'){
			$this->data['action'] = str_replace('&amp;', '&', $this->url->link('case/calendar', '' . $url2, 'SSL'));
			$this->data['action222'] = str_replace('&amp;', '&', $this->url->link('case/calendar', '' . $url3, 'SSL'));			
		}else{
			$this->data['action'] = str_replace('&amp;', '&', $this->url->link('case/clients/tasks', '' . $url2, 'SSL'));
			$this->data['action222'] = str_replace('&amp;', '&', $this->url->link('case/clients/tasks', '' . $url3, 'SSL'));
		}
		
		
		
		
		$this->template = $this->config->get('config_template') . '/template/common/headercasetask.php';

			$this->render();
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Common headercase',
			);
			$this->model_activity_activity->addActivity('headercase', $activity_data2);
		
		
		} 
	}

	
}
?>
