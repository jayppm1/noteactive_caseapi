<?php  
class Controllernotesnotes extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->language->load('notes/notes');

    	$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('notes/notes');
		
    	$this->getList();
  	}
   
  	public function insert() {
    	$this->language->load('notes/notes');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('notes/notes');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			
			
			$this->model_notes_notes->addnotes($this->request->post, $this->customer->getId());
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('notes/notes', '' . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->language->load('notes/notes');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('notes/notes');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_notes_notes->editnotes($this->request->get['notes_id'], $this->request->post, $this->customer->getId());
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('notes/notes', '' . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}
 
  	public function delete() { 
    	$this->language->load('notes/notes');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('notes/notes');
		//var_dump($this->request->post['selected']);die;
    	if (isset($this->request->post['selected'])) {
      		foreach ($this->request->post['selected'] as $notes_id) {
				$notes_info = $this->model_notes_notes->getnotes($notes_id);
				unlink(DIR_IMAGE.'files/'. $notes_info['notes_file']);
				$this->model_notes_notes->deletenotes($notes_id);	
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('notes/notes', '' . $url, 'SSL'));
    	}
	
    	$this->getList();
  	}

  	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
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
			
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('notes/notes', '' . $url, 'SSL'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->link('notes/notes/insert', '' . $url, 'SSL');
		$this->data['delete'] = $this->url->link('notes/notes/delete', '' . $url, 'SSL');			
			
    	$this->data['notess'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$notes_total = $this->model_notes_notes->getTotalnotess();
		
		$results = $this->model_notes_notes->getnotess($data);
    	
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('notes/notes/update', '' . '&notes_id=' . $result['notes_id'] . $url, 'SSL')
			);
					
      		$this->data['notess'][] = array(
				'notes_id'    => $result['notes_id'],
				'notes_description'   => $result['notes_description'],
				'shift_starttime_hour'   => $result['shift_starttime_hour'],
				'shift_starttime_minutes'   => $result['shift_starttime_minutes'],
				'shift_endtime_hour'   => $result['shift_endtime_hour'],
				'shift_endtime_minutes'   => $result['shift_endtime_minutes'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['notes_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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
		
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_notes_description'] = $this->url->link('notes/notes', '' . '&sort=notes_description' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('notes/notes', '' . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('notes/notes', '' . '&sort=date_added' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $notes_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('notes/notes', '' . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = $this->config->get('config_template') . '/template/notes/notes_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
	
	protected function getForm() {
		
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
		
		$this->data['logout'] = $this->url->link('common/logout', '' , 'SSL');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		if (isset($this->error['notes_file'])) {
			$this->data['error_notes_file'] = $this->error['notes_file'];
		} else {
			$this->data['error_notes_file'] = '';
		}

 		if (isset($this->error['notes_description'])) {
			$this->data['error_notes_description'] = $this->error['notes_description'];
		} else {
			$this->data['error_notes_description'] = '';
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
		
	 			
		if (isset($this->error['shift_starttime_hour'])) {
			$this->data['error_shift_starttime_hour'] = $this->error['shift_starttime_hour'];
		} else {
			$this->data['error_shift_starttime_hour'] = '';
		}
		
		if (isset($this->error['shift_starttime_minutes'])) {
			$this->data['error_shift_starttime_minutes'] = $this->error['shift_starttime_minutes'];
		} else {
			$this->data['error_shift_starttime_minutes'] = '';
		}
		
		if (isset($this->error['shift_endtime_hour'])) {
			$this->data['error_shift_endtime_hour'] = $this->error['shift_endtime_hour'];
		} else {
			$this->data['error_shift_endtime_hour'] = '';
		}
		
		if (isset($this->error['shift_endtime_minutes'])) {
			$this->data['error_shift_endtime_minutes'] = $this->error['shift_endtime_minutes'];
		} else {
			$this->data['error_shift_endtime_minutes'] = '';
		}
		$timezone_name = $this->customer->isTimezone();
		
		date_default_timezone_set($timezone_name);
		
		$this->data['note_time'] =  date('H:i:s a', strtotime('now'));
		
		$this->data['notetime'] =  date('H:i:s', strtotime('now'));
		
		date_default_timezone_set('US/Mountain');
		echo date('Y-m-d H:i:s', strtotime('now'))."<hr>";
		date_default_timezone_set('US/Eastern');
		echo date('Y-m-d H:i:s', strtotime('now'))."<hr>";
		
		if (!isset($this->request->get['notes_id'])) {
			$this->data['action'] = $this->url->link('notes/notes/insert', '' . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('notes/notes/update', '' . '&notes_id=' . $this->request->get['notes_id'] . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('notes/notes', '' . $url, 'SSL');

    	if (isset($this->request->get['notes_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$notes_info = $this->model_notes_notes->getnotes($this->request->get['notes_id']);
    	}

    	if (isset($this->request->post['facilities_id'])) {
      		$this->data['facilities_id'] = $this->request->post['facilities_id'];
    	} elseif (!empty($notes_info)) {
			$this->data['facilities_id'] = $notes_info['facilities_id'];
		} else {
      		$this->data['facilities_id'] = '';
    	}
		
		if (isset($this->request->post['shift_starttime_hour'])) {
      		$this->data['shift_starttime_hour'] = $this->request->post['shift_starttime_hour'];
    	} elseif (!empty($notes_info)) {
			$this->data['shift_starttime_hour'] = $notes_info['shift_starttime_hour'];
		} else {
      		$this->data['shift_starttime_hour'] = '';
    	}
		
		if (isset($this->request->post['shift_starttime_minutes'])) {
      		$this->data['shift_starttime_minutes'] = $this->request->post['shift_starttime_minutes'];
    	} elseif (!empty($notes_info)) {
			$this->data['shift_starttime_minutes'] = $notes_info['shift_starttime_minutes'];
		} else {
      		$this->data['shift_starttime_minutes'] = '';
    	}
		
		if (isset($this->request->post['shift_endtime_hour'])) {
      		$this->data['shift_endtime_hour'] = $this->request->post['shift_endtime_hour'];
    	} elseif (!empty($notes_info)) {
			$this->data['shift_endtime_hour'] = $notes_info['shift_endtime_hour'];
		} else {
      		$this->data['shift_endtime_hour'] = '';
    	}
		
		if (isset($this->request->post['shift_endtime_minutes'])) {
      		$this->data['shift_endtime_minutes'] = $this->request->post['shift_endtime_minutes'];
    	} elseif (!empty($notes_info)) {
			$this->data['shift_endtime_minutes'] = $notes_info['shift_endtime_minutes'];
		} else {
      		$this->data['shift_endtime_minutes'] = '';
    	}
		
  		  
    	if (isset($this->request->post['notes_description'])) {
      		$this->data['notes_description'] = $this->request->post['notes_description'];
    	} elseif (!empty($notes_info)) {
			$this->data['notes_description'] = $notes_info['notes_description'];
		} else {
      		$this->data['notes_description'] = '';
    	}

    	if (isset($this->request->post['highlighter_id'])) {
      		$this->data['highlighter_id'] = $this->request->post['highlighter_id'];
    	} elseif (!empty($notes_info)) {
			$this->data['highlighter_id'] = $notes_info['highlighter_id'];
		} else {
      		$this->data['highlighter_id'] = '';
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
		
		$this->load->model('setting/highlighter');
		
		$this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters($data);
		
		$this->load->model('facilities/facilities');
		$results = $this->model_facilities_facilities->getfacilitiess($data);
    	
		foreach ($results as $result) {
					
      		$this->data['facilitiess'][] = array(
				'facilities_id'    => $result['facilities_id'],
				'facility'   => $result['facility']
			);
		}
		
		$this->load->model('setting/hoursminutes');
		
		$this->data['hours'] = $this->model_setting_hoursminutes->hoursFunction();
		$this->data['minutes'] = $this->model_setting_hoursminutes->minutesFunction();
		
		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsers();
 
     	
		$this->template = $this->config->get('config_template') . '/template/notes/notes_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
  	
  	protected function validateForm() {
    	
		
		if ($this->request->post['user_id'] == '') {
			$this->error['user_id'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['highlighter_id'] == '') {
			$this->error['highlighter_id'] = $this->language->get('error_required');
		}
    
    	if ((utf8_strlen($this->request->post['notes_pin']) < 3) || (utf8_strlen($this->request->post['notes_pin']) > 20)) {
      		$this->error['notes_pin'] = $this->language->get('error_required');
    	}
		
		$this->load->model('facilities/facilities');
		$facility_info = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		if (($this->request->post['notes_pin'] != $facility_info['facility_pin'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}
		
		
    	if ((utf8_strlen($this->request->post['notes_description']) < 1)) {
			$this->error['notes_description'] = $this->language->get('error_required');
    	}
		
		if ($this->request->post['shift_starttime_hour'] == '0') {
			$this->error['shift_starttime_hour'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['shift_starttime_minutes'] == '0') {
			$this->error['shift_starttime_minutes'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['shift_endtime_hour'] == '0') {
			$this->error['shift_endtime_hour'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['shift_endtime_minutes'] == '0') {
			$this->error['shift_endtime_minutes'] = $this->language->get('error_required');
		}
		
		/*if ($this->request->files['notes_file'] == '') {
			$this->error['notes_file'] = $this->language->get('error_required');
		}*/
		
		

			if($this->request->files["notes_file"]['name'] != null && $this->request->files["notes_file"]['name'] != ""){
				$allowedExts = array( "pdf",  "doc", "docx"); 
				$extension = end(explode(".", $this->request->files["notes_file"]["name"]));

				$allowedMimeTypes = array( 'application/msword', 'text/pdf', 'image/gif', 'image/jpeg', 'image/png');
				if ( ! ( in_array($extension, $allowedExts ) ) ) {
				  $this->error['notes_file'] = 'Please insert valid type';
				}

				if ( in_array($this->request->files["notes_file"]["type"], $allowedMimeTypes ) ) { 
					if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
						$notes_info = $this->model_notes_notes->getnotes($this->request->get['notes_id']);
						unlink(DIR_IMAGE.'files/'. $notes_info['notes_file']);
					}

					$this->request->post['notes_file'] = uniqid( ) . "." . $extension;
					$outputFolder =DIR_IMAGE.'files/' . $this->request->post['notes_file'];
					move_uploaded_file($this->request->files["notes_file"]["tmp_name"], $outputFolder);
					
					
				}else{
					$this->error['notes_file'] = 'Please insert valid type';
				}
			}

	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

}
?>