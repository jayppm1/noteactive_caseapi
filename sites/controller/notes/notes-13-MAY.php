<?php
class Controllernotesnotes extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if ($this->customer->isLogged()) {

			$this->redirect($this->url->link('notes/notes/insert', '', 'SSL'));
		}

		unset($this->session->data['notesdatas']);
		unset($this->session->data['text_color_cut']);
		unset($this->session->data['highlighter_id']);
		unset($this->session->data['text_color']);
		unset($this->session->data['note_date']);
		unset($this->session->data['notes_file']);


		if($this->request->get['reset'] == '1'){
			unset($this->session->data['note_date_search']);
			unset($this->session->data['note_date_from']);
			unset($this->session->data['note_date_to']);
			unset($this->session->data['keyword']);
			unset($this->session->data['user_id']);
			unset($this->session->data['keyword_file']);
			$this->redirect($this->url->link('notes/notes', '' . $url, 'SSL'));
		}


		$this->data['rediectUlr'] = $this->url->link('notes/notes', '' . $url, 'SSL');
		$this->data['resetUrl'] = $this->url->link('notes/notes', '' . '&reset=1' . $url, 'SSL');

		$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
		
		$this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url2, 'SSL');

		
	}

	public function insert() {
		unset($this->session->data['timeout']);
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if (!$this->customer->isLogged()) {

			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && $this->request->post['advance_search'] != '1') {


			$this->session->data['notesdatas'] = $this->request->post['arraynotes'];
			//$this->session->data['notesfiles'] = $this->request->files;
			$this->session->data['highlighter_id'] = $this->request->post['highlighter_id'];
			$this->session->data['text_color_cut'] = $this->request->post['text_color_cut'];
			$this->session->data['text_color'] = $this->request->post['text_color'];
			$this->session->data['note_date'] = $this->request->post['note_date'];
			
			$this->session->data['keyword_file'] = $this->request->post['keyword_file'];

			$this->session->data['notes_file'] = $this->request->post['notes_file'];

			//$this->model_notes_notes->addnotes($this->request->post, $this->customer->getId());

			$this->session->data['success2'] = $this->language->get('text_success');

			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			$this->redirect($this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		}
		
		if ($this->request->post['advance_search'] == '1') {
			
			if($this->request->post['note_date_from'] > $this->request->post['note_date_to']){
				$url2 .= '&error2=1';
				$this->redirect($this->url->link('notes/notes/search', '' . $url2, 'SSL'));
				return false;
			}
			
		}



		$this->getForm();
	}

	
	protected function getForm() {
		
		$this->load->model('notes/image');
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		
		date_default_timezone_set($this->session->data['time_zone_1']);
		
		$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
			
		$this->data['rediectUlr'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		$this->data['resetUrl'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_facility'] = $this->language->get('entry_facility');
		$this->data['entry_time'] = $this->language->get('entry_time');
		$this->data['entry_notes_description'] = $this->language->get('entry_notes_description');
		$this->data['entry_highliter'] = $this->language->get('entry_highliter');
		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['entry_upload_file'] = $this->language->get('entry_upload_file');
		$this->data['entry_timezone'] = $this->language->get('entry_timezone');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_select'] = $this->language->get('text_select');

		if($this->request->get['reset'] == '1'){
			unset($this->session->data['note_date_search']);
			unset($this->session->data['note_date_from']);
			unset($this->session->data['note_date_to']);
			unset($this->session->data['keyword']);
			unset($this->session->data['user_id']);
			unset($this->session->data['notesdatas']);
			unset($this->session->data['advance_search']);
			unset($this->session->data['update_reminder']);
			unset($this->session->data['keyword_file']);
			
			$this->redirect($this->url->link('notes/notes/insert', '' . $url, 'SSL'));
		}

		$this->data['resetUrl'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');

		$this->data['notess'] = array();

		
		if (isset($this->session->data['update_reminder'])) {
			$this->data['update_reminder'] = $this->session->data['update_reminder'];
		} 
		
		if (isset($this->request->post['advance_search'])) {
			$this->session->data['advance_search'] = $this->request->post['advance_search'];
		} 

		if (isset($this->request->post['note_date_search'])) {
			$this->data['note_date_search'] = $this->request->post['note_date_search'];
			$this->session->data['note_date_search'] = $this->request->post['note_date_search'];
		} else {
			$this->data['note_date_search'] = '';
		}

		if (isset($this->request->post['note_date_from'])) {
			$this->data['note_date_from'] = $this->request->post['note_date_from'];
			$this->session->data['note_date_from'] = $this->request->post['note_date_from'];
		} else {
			$this->data['note_date_from'] = '';
		}

		if (isset($this->request->post['note_date_to'])) {
			$this->data['note_date_to'] = $this->request->post['note_date_to'];
			$this->session->data['note_date_to'] = $this->request->post['note_date_to'];
		} else {
			$this->data['note_date_to'] = '';
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
			$this->session->data['keyword'] = $this->request->post['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
			$this->session->data['user_id'] = $this->request->post['user_id'];
		} else {
			$this->data['user_id'] = '';
		}

		if($this->session->data['note_date_from'] != null && $this->session->data['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->session->data['note_date_from']));
		}
		if($this->session->data['note_date_to'] != null && $this->session->data['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->session->data['note_date_to']));
		}


		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$noteTime =  date('H:i:s');
			
			$date = str_replace('-', '/', $this->request->get['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[1]."-".$res[0]."-".$res[2];
			
			$this->data['note_date'] = $changedDate.' '.$noteTime;
			$searchdate = $this->request->get['searchdate'];
			
			
			if( strtotime($searchdate) >= strtotime(date('m-d-Y')) ) {
				$this->data['back_date_check'] = "1";
			}else{
				$this->data['back_date_check'] = "2";
			}
		} else {
			$this->data['note_date'] =  date('Y-m-d H:i:s');
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$config_admin_limit = "100";
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'searchdate' => $searchdate,
			'searchdate_app' => '1',
			'facilities_id' => $this->customer->getId(),
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $this->session->data['keyword'],
			'user_id' => $this->session->data['user_id'],
			'advance_searchapp' => $this->session->data['advance_search'],
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
 
		if($this->session->data['advance_search'] == '1'){
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
		}
		
		$results = $this->model_notes_notes->getnotess($data);
		
		
		foreach ($results as $result) {
			
			$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
			$user_info = $this->model_user_user->getUser($result['user_id']);
			
			$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
			
			$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
			$reminder_time = $reminder_info['reminder_time'];
			$reminder_title = $reminder_info['reminder_title'];
				
				if ($result['keyword_file'] && file_exists(DIR_IMAGE . 'icon/'.$result['keyword_file'])) {
					$keyimage = $this->model_notes_image->resize('icon/'.$result['keyword_file'], 35, 35);
						$keyImageSrc1 = '<img src="'.$keyimage.'">';
						
				}else{
					$keyImageSrc1 = "";
				}
				
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />';
					
					$fileOpen = $this->url->link('notes/notes/openFile', '' . '&openfile='.$result['notes_file'] . $url, 'SSL');
				}else{
					$keyImageSrc = '';
					$fileOpen = "";
					
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$userPin = $result['notes_pin'];
				}else{
					$userPin = '';
				}
		
			
			$this->data['notess'][] = array(
				'notes_id'    => $result['notes_id'],
				'highlighter_value'   => $highlighterData['highlighter_value'],
				'notes_description'   => $keyImageSrc1 .'&nbsp;'. $result['notes_description'],
				'keyImageSrc1'   => $keyImageSrc1,
				'keyImageSrc'   => $keyImageSrc,
				'fileOpen'   => $fileOpen,
				'notetime'   => date('h:i A', strtotime($result['notetime'])),
				'username'      => $user_info['username'],
				'notes_pin'      => $userPin,
				'signature'   => $this->model_notes_image->resize('signature/'.$result['signature_image'], 98, 28),
				'text_color_cut'   => $result['text_color_cut'],
				'text_color'   => $result['text_color'],
				'note_date'   => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date('m-d-y', strtotime($result['date_added'])),
				'strike_user_name'   => $strikeuser_info['username'],
				'strike_signature'   => $this->model_notes_image->resize('signature/'.$result['strike_signature_image'], 98, 28),
				'strike_date_added'   => date($this->language->get('date_format_short_2'), strtotime($result['strike_date_added'])),
				'reminder_time'      => $reminder_time,
				'reminder_title'      => $reminder_title,
				
			);
		}
		
		
		$this->data['reviews'] = array();
		
		$data2 = array(
			'searchdate' => $searchdate,
			'facilities_id' => $this->customer->getId(),
		);
		
		$reviewsresults = $this->model_notes_notes->getreviews($data2);
		foreach ($reviewsresults as $review_info) {
				if($review_info['user_id'] != null && $review_info['user_id'] != ""){
					$reviewuser_info = $this->model_user_user->getUser($review_info['user_id']);
					$reviewusername = $reviewuser_info['username'];
				}else{
					$reviewusername = '';
				}
				
				if($review_info['date_added'] != null && $review_info['date_added'] != "0000-00-00 00:00:00"){
					$reviewDate = date($this->language->get('date_format_short_2'), strtotime($review_info['date_added']));
				}else{
					$reviewDate = '';
				}
				
				if($review_info['note_date'] != null && $review_info['note_date'] != "0000-00-00 00:00:00"){
					$reviewnote_date = date($this->language->get('date_format_short_2'), strtotime($review_info['note_date']));
				}else{
					$reviewnote_date = '';
				}
				
				if($review_info['signature'] != null && $review_info['signature'] != ""){
					
					$review_signature = $review_info['signature'];
				}else{
					$review_signature = '';
				}
			
			$this->data['reviews'][] = array(
				'review_date'   => $reviewDate,
				'review_note_date'   => $reviewnote_date,
				'review_username'   => $reviewusername,
				'review_signature'   => $review_signature,
				'notes_pin'   => $review_info['notes_pin']
			);
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}

		if (isset($this->error['notes_description'])) {
			$this->data['error_notes_description'] = $this->error['notes_description'];
		} else {
			$this->data['error_notes_description'] = '';
		}
		
		if (isset($this->error['notetime'])) {
			$this->data['error_notetime'] = $this->error['notetime'];
		} else {
			$this->data['error_notetime'] = '';
		}
		

		if (isset($this->error['notes_file'])) {
			$this->data['error_notes_file'] = $this->error['notes_file'];
		} else {
			$this->data['error_notes_file'] = '';
		}
		 
		$this->data['currentTime'] =  date('m-d-Y', strtotime('now'));
		
		$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
		 
		if (!isset($this->request->get['notes_id'])) {
			$this->data['action'] = $this->url->link('notes/notes/insert', '' . $url2, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('notes/notes/update', '' . '&notes_id=' . $this->request->get['notes_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');

		$this->data['addNotes'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert2', '' . $url2, 'SSL'));

		$this->data['logout'] = $this->url->link('common/logout', '' , 'SSL');

		$this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url, 'SSL');


		$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
		$this->data['reviewUrl'] = $this->url->link('notes/notes/review', '' . '&review=1' . $url2, 'SSL');

		if (isset($this->request->get['notes_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$notes_info = $this->model_notes_notes->getnotes($this->request->get['notes_id']);
		}

		if (isset($this->request->post['notes_description'])) {
			$this->data['notes_description'] = $this->request->post['notes_description'];
		} elseif (!empty($this->session->data['notesdatas'][0]['notes_description'])) {
			$this->data['notes_description'] = $this->session->data['notesdatas'][0]['notes_description'];
		} else {
			$this->data['notes_description'] = '';
		}
		
		if (isset($this->request->post['keyword_file'])) {
			$this->data['keyword_file'] = $this->request->post['keyword_file'];
			if ($this->request->post['keyword_file'] && file_exists(DIR_IMAGE . 'icon/'.$this->request->post['keyword_file'])) {
				$keyword_file = $this->model_notes_image->resize('icon/'.$this->request->post['keyword_file'], 20, 20);
				
				$this->data['keyword_file_img'] = '<img src="'.$keyword_file.'">';
			}else{
				$this->data['keyword_file_img'] = "";
			}
		} elseif (!empty($this->session->data['keyword_file'])) {
			$this->data['keyword_file'] = $this->session->data['keyword_file'];
			
			if ($this->session->data['keyword_file'] && file_exists(DIR_IMAGE . 'icon/'.$this->session->data['keyword_file'])) {
				$keyword_file = $this->model_notes_image->resize('icon/'.$this->session->data['keyword_file'], 20, 20);
				
				$this->data['keyword_file_img'] = '<img src="'.$keyword_file.'">';
			}else{
				$this->data['keyword_file_img'] = "";
			}
			
		} else {
			$this->data['keyword_file'] = '';
		}

		if (isset($this->request->post['highlighter_id'])) {
			$this->data['highlighter_id'] = $this->request->post['highlighter_id'];
		} elseif (!empty($notes_info)) {
			$this->data['highlighter_id'] = $notes_info['highlighter_id'];
		} else {
			$this->data['highlighter_id'] = '';
		}

		$this->load->model('setting/highlighter');

		$this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters($data);
		
		$this->load->model('setting/keywords');

		$this->data['keywords'] = array();
		$keywords = $this->model_setting_keywords->getkeywords();

		
		foreach ($keywords as $keyword) {
			if ($keyword['keyword_image'] && file_exists(DIR_IMAGE . 'icon/'.$keyword['keyword_image'])) {
				$image = $this->model_notes_image->resize('icon/'.$keyword['keyword_image'], 35, 35);
			} 
			$this->data['keywords'][] = array(
				'keyword_id'    => $keyword['keyword_id'],
				'keyword_name'   => $keyword['keyword_name'],
				'keyword_image'   => $keyword['keyword_image'],
				'img_icon'   => $image,
			);
		}
		
		$this->load->model('facilities/facilities');
		$results = $this->model_facilities_facilities->getfacilitiess($data);
			
		foreach ($results as $result) {

			$this->data['facilitiess'][] = array(
				'facilities_id'    => $result['facilities_id'],
				'facility'   => $result['facility']
			);
		}
		
		/*
		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
		*/
		$this->data['note_time'] =  date('h:i A');

		$this->data['notetime'] =  date('h:i A');
		
		if($this->session->data['advance_search'] == '1'){
			$pagination = new Pagination();
			$pagination->total = $notes_total;
			$pagination->page = $page;
			$pagination->limit = $config_admin_limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('notes/notes/insert', '' . $url . '&page={page}', 'SSL');
				
			$this->data['pagination'] = $pagination->render();
		}


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
			);

			$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		
		if ((utf8_strlen(trim($this->request->post[arraynotes][0][notes_description])) < 1)) {
			$this->error['notes_description'] = $this->language->get('error_required');
		}
			
		if ((utf8_strlen(trim($this->request->post[arraynotes][0][notetime])) < 1)) {
			$this->error['notetime'] = 'Please select note time';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function insert2() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {

		
			$this->request->post['highlighter_id'] = $this->session->data['highlighter_id'];
			$this->request->post['notesdatas'] = $this->session->data['notesdatas'];
			$this->request->post['notes_id'] = $this->session->data['notes_id'];
			$this->request->post['text_color_cut'] = $this->session->data['text_color_cut'];

			$this->request->post['text_color'] = $this->session->data['text_color'];
			$this->request->post['note_date'] = $this->session->data['note_date'];

			$this->request->post['notes_file'] = $this->session->data['notes_file'];
			
			$this->request->post['keyword_file'] = $this->session->data['keyword_file'];


			
			$this->model_notes_notes->addnotes($this->request->post, $this->customer->getId());

			$this->session->data['success'] = $this->language->get('text_success');

			unset($this->session->data['notesdatas']);
			unset($this->session->data['highlighter_id']);
			unset($this->session->data['notes_id']);
			unset($this->session->data['text_color_cut']);
			unset($this->session->data['text_color']);
			unset($this->session->data['note_date']);
			unset($this->session->data['notes_file']);
			unset($this->session->data['update_reminder']);
			
			unset($this->session->data['keyword_file']);
			
			
			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			
			//$this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL')));
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		
		$url2 = "";
		
		
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
		 
		$this->data['action2'] = $this->url->link('notes/notes/insert2', '' . $url2, 'SSL');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		if (isset($this->error['select_one'])) {
			$this->data['error_select_one'] = $this->error['select_one'];
		} else {
			$this->data['error_select_one'] = '';
		}
		
		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['highlighter_id'])) {
			$this->data['error_highlighter_id'] = $this->error['highlighter_id'];
		} else {
			$this->data['error_highlighter_id'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}
		
		if (isset($this->request->post['select_one'])) {
			$this->data['select_one'] = $this->request->post['select_one'];
		}  else {
			if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
		}else{
			$this->data['default_sign'] = '2';
		}

		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}

	protected function validateForm2() {
			

		if ($this->request->post['user_id'] == '') {
			$this->error['user_id'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['select_one'] == '') {
			$this->error['select_one'] = $this->language->get('error_required');
		}
		 
		if ($this->request->post['select_one'] == '1') {
			if ($this->request->post['notes_pin'] == '') {
				$this->error['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
		}


		/*if(($this->request->post['notes_pin'] == null && $this->request->post['notes_pin'] == "") && ($this->request->post['imgOutput'] == null && $this->request->post['imgOutput'] == "")){
			$this->error['warning'] = 'Please insert at least one required!';

			}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function search(){
		unset($this->session->data['timeout']);
		$this->data['searchUlr'] = $this->url->link('notes/notes/insert', '' . $url, 'SSL');

		$this->data['error2'] = $this->request->get['error2'];
		$this->load->model('user/user');
		
		$data = array(
			'status'  => '1',
			'facilities_id' =>$this->customer->getId(),
		);
		
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		unset($this->session->data['note_date_search']);
		unset($this->session->data['note_date_from']);
		unset($this->session->data['note_date_to']);
		unset($this->session->data['keyword']);
		unset($this->session->data['user_id']);
		unset($this->session->data['user_id']);

		$this->template = $this->config->get('config_template') . '/template/notes/notes_search.tpl';

		$this->response->setOutput($this->render());
	}

	public function getNoteTime(){
		$timezone_name = $this->customer->isTimezone();

		date_default_timezone_set($timezone_name);

		$this->data['note_time'] =  date('H:i', strtotime('now'));

		$this->data['notetime'] =  date('H:i', strtotime('now'));

		$json = array();

		$json['number'] = $this->request->get['number'];

		$json['time'] = $this->data['notetime'];
		$this->response->setOutput(json_encode($json));
	}

	public function review() {
		$this->language->load('notes/notes');

		unset($this->session->data['timeout']);
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {
			
			$add_date = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$add_date = $this->request->get['searchdate'];
			}

			$this->model_notes_notes->addreview($this->request->post, $this->customer->getId(),$add_date);

			$this->session->data['success'] = $this->language->get('text_success');

			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			//$this->redirect($this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());


		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['select_one'])) {
			$this->data['error_select_one'] = $this->error['select_one'];
		} else {
			$this->data['error_select_one'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['highlighter_id'])) {
			$this->data['error_highlighter_id'] = $this->error['highlighter_id'];
		} else {
			$this->data['error_highlighter_id'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}


		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}
		
		if (isset($this->request->post['select_one'])) {
			$this->data['select_one'] = $this->request->post['select_one'];
		}  else {
			if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}
		
		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
		}else{
			$this->data['default_sign'] = '2';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
		 
		$this->data['action2'] = $this->url->link('notes/notes/review', '' . $url2, 'SSL');


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}

	public function uploadFile(){
		unset($this->session->data['timeout']);
		$json = array();
		if($this->request->files["file"] != null && $this->request->files["file"] != ""){

			$extension = end(explode(".", $this->request->files["file"]["name"]));

			if($this->request->files["file"]["size"] < 5002284){
				$neextension  = strtolower($extension);
				if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
					
					$notes_file = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);

					$json['success'] = '1';
					$json['notes_file'] = $notes_file;
			
				}else{
					$json['error'] = 'video or audio file not valid!';
				}
			}else{
					$json['error'] = 'Maximum size file upload!';
			}

		}else{
			$json['error'] = 'Please select file!';
		}


		$this->response->setOutput(json_encode($json));
	}


	public function updateStrike() {
		$this->language->load('notes/notes');

		unset($this->session->data['timeout']);
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {

			$this->model_notes_notes->updateStrikeNotes($this->request->post, $this->request->get['notes_id'], $this->customer->getId());

			$this->session->data['success'] = $this->language->get('text_success');

			$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
			
		$this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL')));
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());


		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['select_one'])) {
			$this->data['error_select_one'] = $this->error['select_one'];
		} else {
			$this->data['error_select_one'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['highlighter_id'])) {
			$this->data['error_highlighter_id'] = $this->error['highlighter_id'];
		} else {
			$this->data['error_highlighter_id'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}
		
		if (isset($this->request->post['select_one'])) {
			$this->data['select_one'] = $this->request->post['select_one'];
		}  else {
			if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}
		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
		}else{
			$this->data['default_sign'] = '2';
		}

		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}

	
	public function updatenote(){
		
		$this->load->model('notes/notes');
		
		$json = array();
		if ($this->request->get['notes_id'] != null && $this->request->get['type'] == 'text') {
			
		$this->model_notes_notes->updateNoteColor($this->request->get['notes_id'], $this->request->get['text_color']);
		$json['success'] = '1';
		
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['type'] == 'highliter') {
				
			$this->model_notes_notes->updateNoteHigh($this->request->get['notes_id'], $this->request->get['highlighter_id']);
			$json['success'] = '1';
			
			}
			
			$this->response->setOutput(json_encode($json));

	}
	
	public function addReminder(){
		$this->load->model('notes/notes');
		unset($this->session->data['timeout']);
		
		$json = array();
		if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
			$data['notes_id'] = $this->request->get['notes_id'];
			$data['reminder_time'] = $this->request->get['reminder_time'];
			$data['reminder_title'] = $this->request->post['reminder_title'];
				
			$this->model_notes_notes->addReminderModel($data, $this->customer->getId());
			$json['success'] = 1;
		}
		
		$this->response->setOutput(json_encode($json));
	}

	public function arrayInString( $inArray , $inString ){
	
	  if( is_array( $inArray ) ){
		foreach( $inArray as $e ){
		  if( strpos( $inString , $e )!==false )
			return $e;
		}
		return "";
	  }else{
		return ( strpos( $inString , $inArray )!==false );
	  }
	}

	public function jsondeleteReminder(){
		$json = array();
		unset($this->session->data['timeout']);
		$this->load->model('notes/notes');
		
		if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
			$data['notes_id'] = $this->request->get['notes_id'];
			$data['facilities_id'] = $this->customer->getId();
				
			$this->model_notes_notes->jsonDeleteReminder($data);
			$json['success'] = 1;
		}
		
		$this->response->setOutput(json_encode($json));
		
	}
	
	
	public function openFile(){
		$openfile = HTTP_SERVER .'image/files/'. $this->request->get['openfile'];
		
		$extension = strtolower(end(explode(".", $this->request->get['openfile'])));
		
		//var_dump($extension);die; 
		
		/*$file = DIR_IMAGE . '/files/Application_1.doc';
		$filename = 'Application_1.doc';
		header('Content-type: application/docs');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		@readfile($file);*/
		
		if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
			
			$file = DIR_IMAGE . '/files/'.$this->request->get['openfile'];
			$file1 = HTTP_SERVER . 'image/files/'.$this->request->get['openfile'];
			$filename = $this->request->get['openfile'];
			
			header('Content-Disposition: inline; filename="' . $filename . '"');
			$imageData = base64_encode(file_get_contents($file));
			$src = 'data: '.$this->mime_content_type($file1).';base64,'.$imageData;
			echo '<img src="' . $src . '">';
			
		}else{
			
			echo '<iframe class="doc" src="https://docs.google.com/gview?url='.$openfile.'&embedded=true" height="100%" width="100%"></iframe>';
		}
	}
	
	public function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
	
	
	public function updateFile(){
		$this->load->model('notes/notes');
		$json = array();
		unset($this->session->data['timeout']);
		if($this->request->files["file"] != null && $this->request->files["file"] != ""){

			$extension = end(explode(".", $this->request->files["file"]["name"]));

				if($this->request->files["file"]["size"] < 5002284){
				$neextension  = strtolower($extension);
					if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
						$notes_file = uniqid( ) . "." . $extension;
						$outputFolder = DIR_IMAGE.'files/' . $notes_file;
						move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);

						$this->model_notes_notes->updateNoteFile($this->request->get['notes_id'], $notes_file);
						$json['success'] = '1';
				
					}else{
						
						$json['error'] = 'video or audio file not valid!';
					}
				}else{
					
					$json['error'] = 'Maximum size file upload!';
				}

		}else{
			$json['error'] = 'Please select file!';
		}


		$this->response->setOutput(json_encode($json));
	}
	
	public function getReminderTime(){
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		$this->load->model('user/user');
		$this->load->model('notes/image');
		$json = array();
		
		$notes_id = $this->request->get['notes_id'];
		$reminder_info = $this->model_notes_notes->getReminder($notes_id);
		
		$notes_info = $this->model_notes_notes->getnotes($notes_id);
		$user_info = $this->model_user_user->getUser($notes_info['user_id']);
		if($reminder_info != null && $reminder_info != ""){
			
			$json['success'] = '1';
			$json['reminder_time'] = $reminder_info['reminder_time'];
			$json['reminder_title'] = $reminder_info['reminder_title'];
			
			$json['notes_pin'] = $notes_info['reminder_title'];
			
			if($notes_info['notes_pin'] != null && $notes_info['notes_pin'] != ""){
				$json['notes_pin'] = $notes_info['notes_pin'];
			}else{
				$json['notes_pin'] = '';
			}
			
			
			$json['note_date'] = date($this->language->get('date_format_short_2'), strtotime($notes_info['note_date']));
			$json['signature'] = $this->model_notes_image->resize('signature/'.$notes_info['signature_image'], 98, 28);
			$json['notes_description'] = $notes_info['notes_description'];
			$json['username'] = $user_info['username'];
			
			$time = $reminder_info['reminder_time']; /* '10:22 PM';*/

			date_default_timezone_set($this->session->data['time_zone_1']);
			$curtime = date('h:i A');
			
			if($curtime == $time) {     
				$json['checkTime'] = '1';
			}else{
				$json['checkTime'] = '2';
			}
			
		}else{
			$json['success'] = '0';
		}
		
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function getReminderPopup(){
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		$this->load->model('user/user');
		$this->load->model('notes/image');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$data['notes_id'] = $this->request->post['notes_id'];
			
			
			$data['reminder_time'] = $this->request->post['hourtrue'].':'.$this->request->post['minutetrue'].' '.$this->request->post['amPm'];
			
			$this->model_notes_notes->updateReminderModel($data, $this->customer->getId());
			
			$this->session->data['update_reminder'] = '2';
			
			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			
			$this->redirect(str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL')));
		}
		
		$notes_id = $this->request->get['notes_id'];
		$reminder_info = $this->model_notes_notes->getReminder($notes_id);
		
		$notes_info = $this->model_notes_notes->getnotes($notes_id);
		$user_info = $this->model_user_user->getUser($notes_info['user_id']);
		
		$this->data['notes_id'] = $notes_id;
		
		if($reminder_info != null && $reminder_info != ""){
			$this->data['reminder_time'] = $reminder_info['reminder_time'];
			$this->data['reminder_title'] = $reminder_info['reminder_title'];
			
			$this->data['notes_pin'] = $notes_info['reminder_title'];
			
			if($notes_info['notes_pin'] != null && $notes_info['notes_pin'] != ""){
				$this->data['notes_pin'] = $notes_info['notes_pin'];
			}else{
				$this->data['notes_pin'] = '';
			}
			
			$this->data['note_date'] = date($this->language->get('date_format_short_2'), strtotime($notes_info['note_date']));
			$this->data['signature'] = $this->model_notes_image->resize('signature/'.$notes_info['signature_image'], 98, 28);
			$this->data['notes_description'] = $notes_info['notes_description'];
			$this->data['username'] = $user_info['username'];
			
			$time = date('h:i A', $reminder_info['reminder_time']);
			
			$this->data['checkTime'] = $time;
			date_default_timezone_set($this->session->data['time_zone_1']);
		}
		$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get['searchdate'];
		}
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 = '&notes_id=' . $this->request->get['notes_id'];
		}
			
		$this->data['action'] = $this->url->link('notes/notes/getReminderPopup', '' . $url2, 'SSL');
		
		$this->template = $this->config->get('config_template') . '/template/notes/set_alarm.tpl';

		$this->response->setOutput($this->render());
	}

	public function getTime(){
		$json = array();
		unset($this->session->data['timeout']);
		date_default_timezone_set($this->session->data['time_zone_1']);
		$json['notetime'] =  date('h:i A');
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function getTimeout(){
		unset($this->session->data['timeout']);
		$json = array();
		$this->response->setOutput(json_encode($json));
	}
	
	
}
?>