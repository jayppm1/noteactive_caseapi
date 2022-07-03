<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservicesform extends Controller { 
	private $error = array();
	public function index() {
			$this->load->language('form/form');
			$this->load->model('form/form');
			
			$this->data['forms_design_id'] = $this->request->get['forms_design_id'];
			$this->data['forms_id'] = $this->request->get['forms_id'];
			
			$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);

			$this->data['fields'] = unserialize($fromdatas['forms_fields']);
			
			$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
			
			$this->data['form_name'] = $fromdatas['form_name'];
			$this->data['display_image'] = $fromdatas['display_image'];
			$this->data['display_signature'] = $fromdatas['display_signature'];
			$this->data['forms_setting'] = $fromdatas['forms_setting'];
			
			$this->data['form_name'] = $fromdatas['form_name'];
			
			$this->data['display_add_row'] = $fromdatas['display_add_row'];
			$this->data['display_content_postion'] = $fromdatas['display_content_postion'];
			
			
			$this->load->model('facilities/facilities');
				
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
			$this->data['facility_name'] = $facilities_info['facility'];
			
			if($this->request->get['forms_id'] != "" && $this->request->get['forms_id'] != NULL){
				
				$results = $this->model_form_form->getFormDatas($this->request->get['forms_id']);	
				
				if(!empty($results['design_forms'])){
					$this->data['formdatas'] = unserialize($results['design_forms']);
				}
				
				$this->data['upload_file'] = $results['upload_file'];
				$this->data['form_signature'] = $results['form_signature'];
				
				$tags_id = $results['tags_id'];
			
				if($tags_id){
					$this->load->model('setting/tags');
					$tag_info = $this->model_setting_tags->getTag($tags_id);
				}
				
				if (isset($this->request->post['emp_tag_id'])) {
					$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
				} elseif (!empty($tag_info)) {
					$this->data['emp_tag_id'] = $tag_info['tags_id'];
				} else {
					$this->data['emp_tag_id'] = '';
				}
				
				
				if (isset($this->request->post['tags_id'])) {
					$this->data['tags_id'] = $this->request->post['tags_id'];
				} elseif (!empty($tag_info)) {
					$this->data['tags_id'] = $tag_info['tags_id'];
				} else {
					$this->data['tags_id'] = '';
				}
				
				if (isset($this->request->post['emp_tag_id1'])) {
					$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
				} elseif (!empty($tag_info)) {
					$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].': '.$tag_info['emp_first_name'].' '.$tag_info['emp_last_name'];
				}else {
					$this->data['emp_tag_id1'] = '';
				}
				
				$formmedias = $this->model_form_form->getFormmedia($this->request->get['forms_id']);

				$this->data['formsimages'] = array();
				$this->data['formssigns'] = array();
				
				foreach($formmedias as $formmedia){
					/*if($formmedia['media_type'] == '1'){
						$this->data['formsimages'][] = array(
							'media_url' =>$formmedia['media_url'],
							'media_name' =>$formmedia['media_name'],
						);
					}*/
					
					/*if($formmedia['media_type'] == '2'){
						$this->data['formssigns'][] = array(
							'media_url' =>$formmedia['media_url'],
							'media_name' =>$formmedia['media_name'],
						);
					}*/
					
					if($formmedia['media_type'] == '1'){
						$this->data['formdatas'][$formmedia['media_name']] = $formmedia['media_url'];
					}
					
					if($formmedia['media_type'] == '2'){
						$this->data['formdatas'][$formmedia['media_name']] = $formmedia['media_url'];
					}
				}
				
				
				if($results['parent_id'] > 0 ){
			$sql2 = "SELECT * from " . DB_PREFIX . "notes where parent_id = '".$results['parent_id']."'";
			$q22 = $this->db->query($sql2);
					
					//var_dump($q22->rows);
			$this->load->model('notes/tags');
			$this->load->model('notes/notes');			
			$this->load->model('notes/image');
			$this->load->model('setting/highlighter');
			$this->load->model('user/user');
			$this->load->model('notes/tags');
		
			foreach($q22->rows as $result){
				
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
			
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
				
				$allimages = $this->model_notes_notes->getImages($result['notes_id']);
				$images = array();
				foreach ($allimages as $image) {
					
					$extension = $image['notes_media_extention'];
						if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
							$keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
						}else
						if($extension == 'doc' || $extension == 'docx'){
							$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
						}else
						if($extension == 'ppt' || $extension == 'pptx'){
							$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
						}else
						if($extension == 'xls' || $extension == 'xlsx'){
							$keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
						}else
						if($extension == 'pdf'){
							$keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
						}else{
							$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
						}
					
					$images[] = array(
						'keyImageSrc' =>$keyImageSrc,// '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />',
						'media_user_id' => $image['media_user_id'],
						'notes_type' => $image['notes_type'],
						'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
						'media_signature' => $image['media_signature'],
						'media_pin' => $image['media_pin'],
						'notes_file_url' => $this->url->link('notes/notes/displayFile', '' . '&notes_media_id='.$image['notes_media_id'], 'SSL')
						
					);
				}
				
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];

					if ($result['keyword_file'] != null && $result['keyword_file'] != "") {
						$keyImageSrc1 = '<img src="'.$result['keyword_file_url'].'" wisth="35px" height="35px">';
							
					}else{
						$keyImageSrc1 = "";
					}
					
					
					/*if($result['notes_file'] != null && $result['notes_file'] != ""){
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />';
						
						//$fileOpen = $this->url->link('notes/notes/openFile', '' . '&openfile='.$result['notes_file'] . $url, 'SSL');
						$fileOpen = HTTP_SERVER .'image/files/'. $result['notes_file'];
						
					}else{
						$keyImageSrc = '';
						$fileOpen = "";
						
					}*/
					
					if($result['notes_pin'] != null && $result['notes_pin'] != ""){
						$userPin = $result['notes_pin'];
					}else{
						$userPin = '';
					}
					
					
					if($result['task_time'] != null && $result['task_time'] != "00:00:00"){ 
						$task_time = date('h:i A', strtotime($result['task_time']));
					}else{
						$task_time = "";
					}
					
					
					if ($config_tag_status == '1') {
						
						$alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
						
						
						if($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != ""){
							$tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
							$privacy = $tagdata['privacy'];
							
							$emp_tag_id = '';//$alltag['emp_tag_id'].': ';
							
						}else{
							$emp_tag_id = '';
							$privacy = '';
							
						}
					}
					
					
					
					$allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
					$noteskeywords = array();
					
					if($privacy == '2'){
						if($this->session->data['unloack_success'] == '1'){
							//$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
							
							if($allkeywords){
								$keyImageSrc12 = array();
								$keyname = array();
								$keyImageSrc11 = "";
								foreach ($allkeywords as $keyword) {
									$keyImageSrc11 .= '<img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px">';
									//$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $keyword['keyword_name'];
									//$keyname[] = $keyword['keyword_name'];
									//$keyname = array_unique($keyname);
									$noteskeywords[]= array(
										'keyword_file_url' =>$keyword['keyword_file_url'],
									);
								}
								
								//$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
								$keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
								//$keyword_description = $result['notes_description'];
								
								$notes_description = $emp_tag_id . $keyword_description;
							}else{
								$notes_description = $emp_tag_id . $result['notes_description'];
							}
							
						}else{
							$notes_description = $emp_tag_id;
						}
					}else{
						//$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
						
						if($allkeywords){
								$keyImageSrc12 = array();
								$keyname = array();
								$keyImageSrc11 = "";
								foreach ($allkeywords as $keyword) {

									$keyImageSrc11 .= '<img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px">';
									//$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $keyword['keyword_name'];
									//$keyname[] = $keyword['keyword_name'];
									//$keyname = array_unique($keyname);
									
									$noteskeywords[]= array(
										'keyword_file_url' =>$keyword['keyword_file_url'],
									);
								}
								
								//$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
								$keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
								//$keyword_description = $result['notes_description'];
								
								$notes_description = $emp_tag_id . $keyword_description;
						}else{
							$notes_description = $emp_tag_id . $result['notes_description']; 
						} 
						
					}
					
					
					/*if($result['notes_id'] != null && $result['notes_id'] != ""){
						$notesID = (string) $result['notes_id'];
						
						$response = $dynamodb->scan([
						'TableName' => 'incidentform',
						'ProjectionExpression' => 'incidentform_id, notes_id, user_id, signature, notes_pin, form_date_added ',
						'ExpressionAttributeValues' => [
							':val1' => ['N' =>  $notesID]] ,
						'FilterExpression' => 'notes_id = :val1',
						]);
						 
						
						//$response = $dynamodb->scan($params);
						
						//var_dump($response['Items']);
						//echo '<hr>  ';
						
						$forms = array();
						foreach($response['Items'] as $item){
							$form_date_added1 = str_replace("&nbsp;","",$item['form_date_added']['S']);
							if($form_date_added1 != null && $form_date_added1 != ""){
								$form_date_added = date($this->language->get('date_format_short_2'), strtotime($item['form_date_added']['S']));
							}else{
								$form_date_added = ""; 
							}
							$forms[] = array(
								'incidentform_id' => $item['incidentform_id']['N'],
								'notes_id' => $item['notes_id']['N'],
								'user_id' => str_replace("&nbsp;","",$item['user_id']['S']),
								'signature' => str_replace("&nbsp;","",$item['signature']['S']),
								'notes_pin' => str_replace("&nbsp;","",$item['notes_pin']['S']),
								'form_date_added' => $form_date_added,
								
							); 
						}
					}else{
						$forms = array();
					}*/
					
					if($facilityinfo['config_noteform_status'] == '1'){
						$allforms = $this->model_notes_notes->getforms($result['notes_id']);
						$forms = array();
						foreach ($allforms as $allform) {
							
							$forms[] = array(
									'form_type_id' => $allform['form_type_id'],
									'forms_id' => $allform['forms_id'],
									'design_forms' => $allform['design_forms'],
									'custom_form_type' => $allform['custom_form_type'],
									'notes_id' => $allform['notes_id'],
									'form_type' => $allform['form_type'],
									'notes_type' => $allform['notes_type'],
									'user_id' => $allform['user_id'],
									'signature' => $allform['signature'],
									'notes_pin' => $allform['notes_pin'],
									'incident_number' => $allform['incident_number'],
									'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added'])),
									
								); 
						}
					
					}
					
					$notestasks = array();
					if($result['task_type'] == '1'){
						$alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
						
						$boytotal =0;
						$girltotal =0;
						$generaltotal =0;
						$residencetotal =0;
						foreach ($alltasks as $alltask) {
							
							$notestasks[] = array(
									'notes_by_task_id' => $alltask['notes_by_task_id'],
									'locations_id' => $alltask['locations_id'],
									'task_type' => $alltask['task_type'],
									'task_content' => $alltask['task_content'],
									'user_id' => $alltask['user_id'],
									'signature' => $alltask['signature'],
									'notes_pin' => $alltask['notes_pin'],
									'task_time' => $alltask['task_time'],
									'media_url' => $alltask['media_url'],
									'capacity' => $alltask['capacity'],
									'location_name' => $alltask['location_name'],
									'location_type' => $alltask['location_type'],
									'notes_task_type' => $alltask['notes_task_type'],
									'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added'])),
									
								); 
								
								
								if($alltask['location_type'] == 'Boys'){
									$boytotal = $boytotal + $alltask['capacity'];
								}
								
								if($alltask['location_type'] == 'Girls'){
									$girltotal = $girltotal + $alltask['capacity'];
								}
								
								if($alltask['location_type'] == 'General'){
									$generaltotal = $generaltotal + $alltask['capacity'];
								}
								
								
								
						}
						
						$residencetotal = $boytotal + $girltotal + $generaltotal;
						
						$boytotals = array();
						if($boytotal > 0){
						$boytotals[] = array(
							'total'=>$boytotal,
							'loc_name'=>'Boys',
						);
						}

						$girltotals = array();
						if($girltotal > 0){
						$girltotals[] = array(
							'total'=>$girltotal,
							'loc_name'=>'Girls',
						); 
						}
						
						$generaltotals = array();
						if($generaltotal > 0){
						$generaltotals[] = array(
							'total'=>$generaltotal,
							'loc_name'=>'General',
						); 
						}
						
						$residentstotals = array();
						if($residencetotal > 0){
						$residentstotals[] = array(
							'total'=>$residencetotal,
							'loc_name'=>'Residents',
						); 
						}
					
					
					}
					
					
					$notesmedicationtasks = array();
					if($result['task_type'] == '2'){
						$alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
						
						foreach ($alltmasks as $alltmask) {
							
							if($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00'){
								$taskTime = date('h:i A', strtotime($alltmask['task_time']));
							}
							
							$notesmedicationtasks[] = array(
									'notes_by_task_id' => $alltmask['notes_by_task_id'],
									'locations_id' => $alltmask['locations_id'],
									'task_type' => $alltmask['task_type'],
									'task_content' => $alltmask['task_content'],
									'user_id' => $alltmask['user_id'],
									'signature' => $alltmask['signature'],
									'notes_pin' => $alltmask['notes_pin'],
									'task_time' => $taskTime,
									'media_url' => $alltmask['media_url'],
									'capacity' => $alltmask['capacity'],
									'location_name' => $alltmask['location_name'],
									'location_type' => $alltmask['location_type'],
									'notes_task_type' => $alltmask['notes_task_type'],
									'tags_id' => $alltmask['tags_id'],
									'drug_name' => $alltmask['drug_name'],
									'dose' => $alltmask['dose'],
									'drug_type' => $alltmask['drug_type'],
									'quantity' => $alltmask['quantity'],
									'frequency' => $alltmask['frequency'],
									'instructions' => $alltmask['instructions'],
									'count' => $alltmask['count'],
									'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
									'task_comments' => $alltmask['task_comments'],
									'medication_file_upload' => $alltmask['medication_file_upload'],
									'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added'])),
									
									
								); 
								
								
						}
					
					
					}
					
					 
					$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
					
					$remdata = "";
					if($reminder_info != null && $reminder_info != ""){
						$remdata = "1";
					}else{
						$remdata = "2";
					}
					 
				
					$this->data['notess'][] = array(
						'notes_id'    => $result['notes_id'],
						'visitor_log'    => $result['visitor_log'],
						'alltag'    => $alltag,
						'remdata'    => $remdata,
						'noteskeywords'    => $noteskeywords,
						'is_private'    => $result['is_private'],
						'share_notes'    => $result['share_notes'],
						'is_offline'    => $result['is_offline'],
						'review_notes'    => $result['review_notes'],
						'is_private_strike'    => $result['is_private_strike'],
						'checklist_status'    => $result['checklist_status'],
						'notes_type'    => $result['notes_type'],
						'strike_note_type'    => $result['strike_note_type'],
						'task_time'    => $task_time,
						'tag_privacy'    => $privacy,
						'incidentforms'    => $forms, 
						'notestasks'    => $notestasks, 
						'boytotals'    => $boytotals, 
						'girltotals'    => $girltotals, 
						'generaltotals'    => $generaltotals, 
						'residentstotals'    => $residentstotals, 
						'notesmedicationtasks'    => $notesmedicationtasks, 
						'task_type'    => $result['task_type'],
						'taskadded'    => $result['taskadded'],
						'assign_to'    => $result['assign_to'],
						'highlighter_value'   => $highlighterData['highlighter_value'],
						'notes_description'   => $notes_description,
						//'keyImageSrc'   => $keyImageSrc,
						//'fileOpen'   => $fileOpen,
						'images'   => $images,
						'notetime'   => date('h:i A', strtotime($result['notetime'])),
						'username'      => $result['user_id'],
						'notes_pin'      => $userPin,
						'signature'   => $result['signature'],
						'text_color_cut'   => $result['text_color_cut'],
						'text_color'   => $result['text_color'],
						'note_date'   => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
						'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
						'date_added' => date('m-d-Y', strtotime($result['date_added'])),
						'strike_user_name'   => $result['strike_user_id'],
						'strike_pin'   => $result['strike_pin'],
						'strike_signature'   => $result['strike_signature'],
						'strike_date_added'   => date($this->language->get('date_format_short_2'), strtotime($result['strike_date_added'])),
						'reminder_time'      => $reminder_time,
						'reminder_title'      => $reminder_title,
						'href'=>$this->url->link('notes/notes/insert', '' . '&reset=1&searchdate='.date('m-d-Y', strtotime($result['date_added'])) . $url, 'SSL'), 
						
					);
				}
			}
			
			}
			
			//var_dump($this->data['formdatas']);
			
			if($this->request->get['forms_id'] == "" && $this->request->get['forms_id'] == NULL){
				
					$url2 = "";
					
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$url2 .= '&notes_id=' . $this->request->get['notes_id'];
					}
					
					if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
						$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
					}
					
					if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
						$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
					}
					
					if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
						$url2 .= '&task_id=' . $this->request->get['task_id'];
					}
					
					if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
						$url2 .= '&tags_id=' . $this->request->get['tags_id'];
					}
					
					$this->data['action'] = $this->url->link('services/form/insert', $url2, true);
			}else{
					$url2 = "";
					
					if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
						$url2 .= '&forms_id=' . $this->request->get['forms_id'];
						$this->data['forms_id'] = $this->request->get['forms_id'];
					}
					
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$url2 .= '&notes_id=' . $this->request->get['notes_id'];
					}
					
					if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
						$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
					}
					if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
						$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
					}
					
					if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
						$url2 .= '&tags_id=' . $this->request->get['tags_id'];
					}
			
				$this->data['action'] = $this->url->link('services/form/edit', $url2, true);
				
				
				if($this->request->get['forms_design_id'] == '13' ){
					$this->data['print_url'] = $this->url->link('form/form/printform', $url2, true);
				}
				
				if($this->request->get['forms_design_id'] == '9' ){
					$this->data['print_url'] = $this->url->link('form/form/printmonthly_firredrill', $url2, true);
				}
					
				if($this->request->get['forms_design_id'] == '10' ){
					$this->data['print_url'] = $this->url->link('form/form/printincidentform', $url2, true);
				}
				
				
				if($this->request->get['forms_design_id'] == '2' ){
					$this->data['print_url'] = $this->url->link('form/form/printintakeform', $url2, true);
				}
			}
			
			
			if (isset($this->session->data['success_add_form'])) {
				$this->data['success_add_form'] = $this->session->data['success_add_form'];

				unset($this->session->data['success_add_form']);
			} else {
				$this->data['success_add_form'] = '';
			}
			
			
			$this->template = $this->config->get('config_template') . '/template/form/form.tpl';
			$this->response->setOutput($this->render());
			
			
	}
	
	
	public function insert(){
		
		$this->load->language('form/form'); 
		$this->load->model('form/form');
		$this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		
		if ($this->request->post['form_submit'] == '1' && $this->validateForm() ) {
			$data2 = array();
			$data2['forms_design_id'] = $this->request->get['forms_design_id'];
			//$data2['notes_id'] = $this->request->get['notes_id'];
			$data2['facilities_id'] = $this->request->get['facilities_id'];
			
			
 			$formreturn_id = $this->model_form_form->addFormdata($this->request->post, $data2);	
			
			$url2 = "";
			
			if ($formreturn_id != null && $formreturn_id != "") {
				$url2 .= '&formreturn_id=' . $formreturn_id;
			}
			
			if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post['emp_tag_id'];
			}
					
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get['forms_id'];
				
				$forms_id = $this->request->get['forms_id'];
			}else{
				$forms_id = '';
			}
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$url2 .= '&new_form=1';
				$new_form = '1';
				$notes_id = $this->request->get['notes_id'];
			}else{
				$new_form = '2';
				$notes_id = '';
				$url2 .= '&new_form=2';
			}
				
			if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				$forms_design_id = $this->request->get['forms_design_id'];
			}else{
				$forms_design_id = '';
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
			
			
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
				$task_id = $this->request->get['task_id'];
			}else{
				$task_id = '';
			}
			
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$tags_id = $this->request->get['tags_id'];
			}else{
				$tags_id = '';
			}
			
			
			
			
			$this->redirect($this->url->link('services/form/jsoncustomsForm', '' . $url2, 'SSL'));
			
		}
		
		
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);

		$this->data['fields'] = unserialize($fromdatas['forms_fields']);
		
		$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
		
		
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['display_image'] = $fromdatas['display_image'];
		$this->data['display_signature'] = $fromdatas['display_signature'];
		$this->data['forms_setting'] = $fromdatas['forms_setting'];
		
		$url2 = "";
				if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get['searchdate'];
				}
				if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				}
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				}
				
				if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				}
				
				
				if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				}
				
				
				$this->data['action'] = $this->url->link('services/form/insert', $url2, true);
				
		
		if (isset($this->request->post['design_forms'])) {
			$this->data['formdatas'] = $this->request->post['design_forms'];
		} else {
			$this->data['formdatas'] = array();
		}
		
		if (isset($this->request->post['upload_file'])) {
			$this->data['upload_file'] = $this->request->post['upload_file'];
		} else {
			$this->data['upload_file'] ='';
		}
		if (isset($this->request->post['form_signature'])) {
			$this->data['form_signature'] = $this->request->post['form_signature'];
		} else {
			$this->data['form_signature'] = '';
		}
		
		if($this->request->get['tags_id']){
			$tags_id = $this->request->get['tags_id'];
		}elseif($this->request->post['emp_tag_id']){
			$tags_id = $this->request->post['emp_tag_id'];
		}
		
		$this->load->model('setting/tags');
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id'] = $tag_info['tags_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id1'])) {
			$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		} else {
			$this->data['emp_tag_id1'] = '';
		}
		
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		
		
		$this->template = $this->config->get('config_template') . '/template/form/form.tpl';
		$this->response->setOutput($this->render());

	}
	
	
	protected function validateForm() {
		
		/*
		if($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != ""){
			if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
				
				$this->load->model('form/form');
				$form_info = $this->model_form_form->getFormwithNotes($this->request->get['updatenotes_id'], CUSTOME_INTAKEID);	
			
				if ($form_info != null && $form_info != "") {
					$this->error['warning'] = 'Intake form already added in this note';
				}
				
				
			}
		}
		*/
		
		
		
		if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
			if($this->request->post['emp_tag_id1'] == "" && $this->request->post['emp_tag_id1'] == ""){
				$this->error['warning'] = 'Client is required!';
			}
		}
		//var_dump($this->session->data['formreturn_id']);
		if($this->session->data['formreturn_id'] != null && $this->session->data['formreturn_id'] != ""){
			
		}
		
		if ($this->request->post['design_forms']['ssn'] != '') {
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTagsbySSN($this->request->post['design_forms']['ssn']);
						
			if (!isset($this->request->post['emp_tag_id'])) {
				
				if($tag_info){
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake? Yes/No';
				}
			} else {
				
				if ($tag_info && ($this->request->post['emp_tag_id'] != $tag_info['tags_id'])) {
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  Yes/No';
				}
			}
		}
		
		
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function insert2() {
		
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		
		if ($this->request->post['perpetual_checkbox'] == '1') {
			if ($this->request->post['perpetual_checkbox_notes_pin'] == '') {
				$json['perpetual_checkbox_notes_pin'] = 'This is required field!';
			}
			if($this->request->post['perpetual_checkbox_notes_pin'] != null && $this->request->post['perpetual_checkbox_notes_pin'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['perpetual_checkbox_notes_pin'] != $user_info['user_pin'])) {
					$json['warning'] = 'User Pin not valid!';
				}
				
				
				$this->load->model('user/user_group');
				$user_role_info = $this->model_user_user_group->getUserGroup($user_info['user_group_id']);
					
				$perpetual_task = $user_role_info['perpetual_task'];
				
				if($perpetual_task != '1'){
					$json['warning'] =  "You are not authorized to end the task!";
				}
				
				
			}
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
				$this->load->model('facilities/facilities');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
				
				$this->load->model('setting/timezone');
				
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				
				date_default_timezone_set($timezone_info['timezone_value']);
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
			
				$form_date_added = (string) $noteDate;
				
					$formreturn_id = (string) $this->request->get['formreturn_id'];
				
				
					if($this->request->get['task_id'] !=null && $this->request->get['task_id']!=""){
						$this->load->model('createtask/createtask');
						if($this->request->post['comments'] != null && $this->request->post['comments']){
							$this->request->post['comments'] = $this->request->post['comments'];
						}else{
							$this->request->post['comments'] =  '';
						}
						
						$this->request->post['imgOutput'] =  $this->request->post['signature'];
						
						
						
						$result2 = $this->model_createtask_createtask->getStrikedatadetails($this->request->get['task_id']);
						
						$notesId = $this->model_createtask_createtask->inserttask($result2, $this->request->post, $this->request->get['facilities_id']);
						
						$this->model_createtask_createtask->updatetaskNote($this->request->get['task_id']);
						$this->model_createtask_createtask->deteteIncomTask($this->request->get['facilities_id']);
						//var_dump($notesId);
						
						$ttstatus = "1";
						//$timezone_name = $this->request->get['facilities_id'];
						
						$this->load->model('facilities/facilities');
				
						$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
						
						
						$this->load->model('setting/timezone');
						
						$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
						
						date_default_timezone_set($timezone_info['timezone_value']);
						
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
						$this->model_createtask_createtask->updateForm($notesId, $checklist_status, $ttstatus,$update_date);
						
						$notes_id = $notesId;
					}else					
					if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
						
						$this->load->model('notes/notes');
						$this->load->model('form/form');
						$this->load->model('setting/tags');
						
						$noteDate = date('Y-m-d H:i:s', strtotime('now'));
						$date_added = (string) $noteDate;
						
						
						$data = array();
						
						$notetime = date('H:i:s', strtotime('now'));
						$data['imgOutput'] = $this->request->post['signature'];
						
						$data['notes_pin'] = $this->request->post['notes_pin'];
						$data['user_id'] = $this->request->post['user_id'];
						$data['notes_type'] = $this->request->post['notes_type'];
						
						$data['notetime'] = $notetime;
						$data['note_date'] = $date_added;
						$data['facilitytimezone'] = $timezone_name;
						
						
						$form_data = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);
						$form_name = $form_data['form_name'];
						
						$this->load->model('setting/tags');
						$tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
						
						$data['emp_tag_id'] = $tag_info['emp_tag_id'];
						$data['tags_id'] = $tag_info['tags_id'];
						
						
						if($this->request->post['comments'] != null && $this->request->post['comments']){
							$comments = ' | '.$this->request->post['comments'];
						}
						
						$data['notes_description'] = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$form_name.' has been added' . $comments;
						
						$data['date_added'] = $date_added;
						
						$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->get['facilities_id']);
						
						
						$this->load->model('form/form');
			
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);
						$formdesign_info = $this->model_form_form->getFormDatadesign($form_info['custom_form_type']);
						$relation_keyword_id = $formdesign_info['relation_keyword_id'];
								
									
						if($relation_keyword_id){
							$this->load->model('notes/notes');
							$noteDetails = $this->model_notes_notes->getnotes($notes_id);
										
							$this->load->model('setting/keywords');
							$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
										
							$data3 = array();
							$data3['keyword_file'] = $keyword_info['keyword_image'];
							$data3['notes_description'] = $noteDetails['notes_description'];
										
							$this->model_notes_notes->addactiveNote($data3, $notes_id);
						}
					
					}else{
						$notes_id = $this->request->get['notes_id'];
					}
					
					
					$this->load->model('notes/notes');
					$noteDetails = $this->model_notes_notes->getnotes($notes_id);
					$date_added1 = $noteDetails['date_added']; 
					
					if( $this->request->get['new_form'] == '1'){
						
						$fsql = "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . $notes_id . "', user_id = '', notes_type = '', signature = '', notes_pin = '', date_added = '" . $date_added1 . "', form_date_added = '" . $form_date_added . "', incident_number = '', form_signature = '', date_updated = '".$date_added."' WHERE forms_id = '" . $formreturn_id . "' ";
		
						$this->db->query($fsql);
						
						if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
							$fsqlt = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->request->get['tags_id'] . "' WHERE forms_id = '" . $formreturn_id . "' ";
		
							$this->db->query($fsqlt);
						}
						
					}else{
						$fsql = "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . $notes_id . "', user_id = '" . $this->request->post['user_id'] . "', notes_type = '" . $this->request->post['notes_type'] . "', signature = '" . $this->request->post['signature'] . "', notes_pin = '" . $this->request->post['notes_pin'] . "', form_date_added = '" . $form_date_added . "', date_added = '" . $date_added1 . "', date_updated = '".$date_added."' WHERE forms_id = '" . $formreturn_id . "' ";
		
						$this->db->query($fsql); 
						
						if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
							$fsqlt = "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->request->get['tags_id'] . "' WHERE forms_id = '" . $formreturn_id . "' ";
		
							$this->db->query($fsqlt);
						}
					
					} 
					
					date_default_timezone_set($timezone_info['timezone_value']);
					$update_date2 = date('Y-m-d H:i:s', strtotime('now'));
						
					$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET update_date = '" . $update_date2 . "', notes_conut='0' WHERE notes_id = '" . (int)$notes_id . "' ";
		
					$this->db->query($sql1);
					
					
					if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
						$this->load->model('notes/notes');
						
						date_default_timezone_set($timezone_info['timezone_value']);
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
						
						$this->model_notes_notes->updateNotesTag($this->request->post['emp_tag_id'], $notes_id,$this->request->post['tags_id'], $update_date);
						
						$fdata = array();
						$fdata['forms_id'] = $formreturn_id;
						$fdata['emp_tag_id'] = $this->request->post['emp_tag_id'];
						$fdata['tags_id'] = $this->request->post['tags_id'];
						$fdata['update_date'] = $update_date;
						
						$this->load->model('form/form');
						$this->model_form_form->updateformTag($fdata);
						
					}
					
					
					$this->load->model('form/form');
			
					$form_info = $this->model_form_form->getFormDatas($formreturn_id);
					$formdesign_info = $this->model_form_form->getFormDatadesign($form_info['custom_form_type']);
					$relation_keyword_id = $formdesign_info['relation_keyword_id'];
							
								
					if($relation_keyword_id){
						$this->load->model('notes/notes');
						$noteDetails = $this->model_notes_notes->getnotes($notes_id);
									
						$this->load->model('setting/keywords');
						$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
									
						$data3 = array();
						$data3['keyword_file'] = $keyword_info['keyword_image'];
						$data3['notes_description'] = $noteDetails['notes_description'];
									
						$this->model_notes_notes->addactiveNote($data3, $notes_id);
					}
					
				
				$this->data['facilitiess'][] = array(
					'warning'  => '1',
					'formreturn_id'  => $formreturn_id,
					'notes_id'  => $notes_id,
					'facilities_id'  => $this->request->get['facilities_id'],
				);
				$error = true;
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));

	}
	
	
	public function edit(){
		$this->load->language('form/form');
		$this->load->model('form/form');
		 
		 $this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		 $this->data['forms_id'] = $this->request->get['forms_id'];
		if ($this->request->post['form_submit'] == '1'  && $this->validateForm()) {
			$data2 = array();
			$data2['forms_design_id'] = $this->request->get['forms_design_id'];
			//$data2['notes_id'] = $this->request->get['notes_id'];
			$data2['facilities_id'] = $this->request->get['facilities_id'];
			
			$this->model_form_form->editFormdata($this->request->post['design_forms'], $this->request->get['forms_id'], $this->request->post['upload_file'], $this->request->post['file'] , $this->request->post['signature']);
			 
			$url2 = "";
			
			if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post['emp_tag_id'];
			}
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get['forms_id'];
				
				$forms_id = $this->request->get['forms_id'];
			}else{
				$forms_id = '';
			}
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get['forms_id'];
				
				$formreturn_id = $this->request->get['forms_id'];
			}else{
				$formreturn_id = '';
			}
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$new_form = '2';
				$notes_id = $this->request->get['notes_id'];
				$url2 .= '&new_form=2';
			}else{
				$new_form = '2';
				$notes_id = '';
				$url2 .= '&new_form=2';
			}
				
			if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				$forms_design_id = $this->request->get['forms_design_id'];
			}else{
				$forms_design_id = '';
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$tags_id = $this->request->get['tags_id'];
			}else{
				$tags_id = '';
			}
			
			$this->redirect($this->url->link('services/form/jsoncustomsForm', '' . $url2, 'SSL'));
		}
		
		
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);

		$this->data['fields'] = unserialize($fromdatas['forms_fields']);
		
		$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
		
		
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['display_image'] = $fromdatas['display_image'];
		$this->data['display_signature'] = $fromdatas['display_signature'];
		$this->data['forms_setting'] = $fromdatas['forms_setting'];
		
		$url2 = "";
				if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get['searchdate'];
				}
				if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				}
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				}
				
				if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				}
				
				
				if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				}
				
				
				$this->data['action'] = $this->url->link('services/form/edit', $url2, true);
				
		
		if (isset($this->request->post['design_forms'])) {
			$this->data['formdatas'] = $this->request->post['design_forms'];
		} else {
			$this->data['formdatas'] = array();
		}
		
		if (isset($this->request->post['upload_file'])) {
			$this->data['upload_file'] = $this->request->post['upload_file'];
		} else {
			$this->data['upload_file'] ='';
		}
		if (isset($this->request->post['form_signature'])) {
			$this->data['form_signature'] = $this->request->post['form_signature'];
		} else {
			$this->data['form_signature'] = '';
		}
		
		if($this->request->get['tags_id']){
			$tags_id = $this->request->get['tags_id'];
		}elseif($this->request->post['emp_tag_id']){
			$tags_id = $this->request->post['emp_tag_id'];
		}
		
		$this->load->model('setting/tags');
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id'] = $tag_info['tags_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id1'])) {
			$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		} else {
			$this->data['emp_tag_id1'] = '';
		}
		
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		
		
		$this->template = $this->config->get('config_template') . '/template/form/form.tpl';
		$this->response->setOutput($this->render());

	
	}
	
	public function jsoncustomsForm(){
		
		$url2 = "";
			
			if ($this->request->get['formreturn_id'] != null && $this->request->get['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get['formreturn_id'];
				$formreturn_id = $this->request->get['formreturn_id'];
			}else{
				$formreturn_id = '';
			}
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get['forms_id'];
				
				$forms_id = $this->request->get['forms_id'];
			}else{
				$forms_id = '';
			}
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$new_form = '2';
				$notes_id = $this->request->get['notes_id'];
				$url2 .= '&new_form=2';
			}else{
				$new_form = '1';
				$notes_id = '';
				$url2 .= '&new_form=1';
			}
				
			if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				$forms_design_id = $this->request->get['forms_design_id'];
			}else{
				$forms_design_id = '';
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
		
		
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
				$task_id = $this->request->get['task_id'];
			}else{
				$task_id = '';
			}
			if ($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->get['emp_tag_id'];
				$emp_tag_id = $this->request->get['emp_tag_id'];
			}else{
				$emp_tag_id = '';
			}
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$tags_id = $this->request->get['tags_id'];
			}else{
				$tags_id = '';
			}
			
			
		$this->data['facilitiess'][] = array(
			'task_form'    => '',
			'formreturn_id'    => $formreturn_id,
			'task_id'    => $task_id,
			'emp_tag_id'    => $emp_tag_id,
			'tags_id'    => $tags_id,
			'new_form'    => $new_form,
			'forms_id'    => $forms_id,
			'notes_id'    => $notes_id,
			'facilities_id'    => $facilities_id,
			'forms_design_id'    => $forms_design_id,
			'signature_url'    => $this->url->link('services/form/insert2', '' . $url2, 'SSL'),
		);
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
	}
}