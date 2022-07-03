<?php
class Controllernotescase extends Controller {
	private $error = array();
	public function index() {
		$this->language->load('common/home');
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->data['form_outputkey'] = $this->formkey->outputKey();
		$this->data['heading_title'] = $this->config->get('config_title');
		/*Cases*/
		$this->load->model('setting/tags');
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
			$facilities_id = $this->request->get['facilities_id'];
		}else{
			$facilities_id = $this->customer->getId();
			
			if (!$this->customer->isLogged()) {
				$this->redirect($this->url->link('common/login', '', 'SSL'));
			}
		}
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		date_default_timezone_set($timezone_info['timezone_value']);
		$this->data['searchUlr'] = $this->url->link('notes/notes/search', '', 'SSL');
		$this->data['add_notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1', 'SSL');
		

		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$this->data['tag'] = $this->model_setting_tags->getTag($this->request->get['tags_id']);
			//var_dump($tagdata['emp_first_name']);
			//var_dump($tagdata['emp_last_name']);
			$this->load->model('setting/tags');
			$get_img = $this->model_setting_tags->getImage($this->request->get['tags_id']);
			$this->data['enroll_image'] = $get_img['enroll_image'];
			$this->load->model('setting/image');
			//$this->data['check_img'] = $this->model_setting_image->checkresize($get_img['enroll_image']);

			$this->load->model('notes/case');

			$case_dshboard = $this->model_notes_case->getcasedetailbytagid($this->request->get['tags_id']);
			//echo '<pre>'; print_r($case_dshboard); echo '</pre>';
			
			$end_date='';
			$j=0;
			
			foreach($case_dshboard AS $cdrow){
				
				$end_date  = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $cdrow['intake_date'] ) ) . " -1 day" ) );
				if($j==0){
					$aechive = '0';
				}else{
					$archive = '1';
				}
				
				$this->data['case_dshboard'][] = array(
                    'intake_date' => date('F d, Y',strtotime($cdrow['intake_date'])),
                    'select'=> date('Y-m-d',strtotime($cdrow['intake_date'])),
                    'href' => $this->url->link('notes/case&archive='.$archive.'&tags_id='.$this->request->get['tags_id'] . '&note_date_from=' . date('Y-m-d',strtotime($cdrow['intake_date'])). '&note_date_to=' . $end_date),

           	 	);
           	 	$j++;
			}


			$this->data['onchange_url'] = $this->url->link('notes/case&tags_id='.$this->request->get['tags_id'], '' . '', 'SSL');

			//echo '<pre>'; print_r($this->data['case_dshboard']); echo '</pre>'; die;
		}
		
		/*Cases*/
		
		$url2 = '';
		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get['tags_id'];
		}
		if ($this->request->get['isandroid'] != null && $this->request->get['isandroid'] != "") {
			$url2 .= '&isandroid=' . $this->request->get['isandroid'];
			$this->data['isandroid'] = $this->request->get['isandroid'];
		}
		
		$this->data['logout'] = $this->url->link('common/logout', '' , 'SSL');
		
		$this->data['home'] = $this->url->link('notes/notes');
		$this->data['home1'] = $this->url->link('common/home', '' . '&reset=1', 'SSL');
		

		$this->data['facility_url'] = $this->url->link('facilities/facilities/update', '', 'SSL');
		$this->data['resident_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
		//$this->data['notes_url'] = $this->url->link('notes/notes', '', 'SSL');
		$this->data['add_notes_url'] = $this->url->link('common/home', '' . '&reset=1', 'SSL');
		
		$this->data['notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
		$this->data['notes_url_close'] = $this->url->link('notes/notes/insert', '' . '&reset=1' , 'SSL');
		$this->data['support_url'] = $this->url->link('notes/support', '', 'SSL');
		$this->data['searchUlr'] = $this->url->link('notes/notes/search', '', 'SSL');
		
		$this->data['createtask_url'] = $this->url->link('notes/createtask', '', 'SSL');
		$this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '', 'SSL');

		$this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '', 'SSL');
		$this->data['addtasktask_url'] = $this->url->link('notes/notes/index', '', 'SSL');
		$this->data['inserttask_url'] = $this->url->link('notes/createtask/inserttask', '', 'SSL');
		
		
		$this->data['checklist_url'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/checklistform', '' . $url2, 'SSL'));
		$this->data['incident_url'] = str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert', '' . $url2, 'SSL'));
		
		
		$this->data['reviewnoted_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/reviewNotes', '' . $url2, 'SSL'));
		
		$this->data['custom_form_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
		
		$this->data['case_url'] = str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard', '', 'SSL'));
		
		$this->data['notes_url_other'] = str_replace('&amp;', '&', $this->url->link('syndb/syndbother', '', 'SSL'));
		
		
		$this->data['update_strike_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrike', '' . $url2, 'SSL'));
		$this->data['update_strike_url_private'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrikeprivate', '' . $url2, 'SSL'));
		$this->data['alarm_url'] = $this->url->link('notes/notes/setAlarm', '', 'SSL');
		
		
		$this->data['dailycensus'] = $this->url->link('resident/dailycensus', '', 'SSL');
		
		$this->data['unloackUrl'] = $this->url->link('notes/notes/unlockUser', ''. $url, 'SSL');
		
		$url2 = '';
		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get['tags_id'];
		}

		if ($this->request->get['isandroid'] != null && $this->request->get['isandroid'] != "") {
			$url2 .= '&isandroid=' . $this->request->get['isandroid'];
			$this->data['isandroid'] = $this->request->get['isandroid'];
		}

		$this->data['s_url'] = $url2;
		$this->data['tags_id'] = $this->request->get['tags_id'];
		$this->template = $this->config->get('config_template') . '/template/notes/case.php';
		$this->children = array(
			'case/header',
			'case/searchresult',
		);
		$this->response->setOutput($this->render());
	}
}