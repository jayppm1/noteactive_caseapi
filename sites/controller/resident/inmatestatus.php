<?php
class Controllerresidentinmatestatus extends Controller {
	private $error = array ();
	
	public function index() {
		
		try{
			
			if (! $this->customer->isLogged ()) {
				$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
			}
			$this->load->model ( 'notes/notes' );
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->document->setTitle ( 'Inmate Status' );
			
			$facilities_id = $this->customer->getId ();
			
			if ($facilities_id != null && $facilities_id != "") {
				$this->data ['facilities_id'] = $facilities_id;
			}
			
			
			$this->data ['action'] = $this->url->link ( 'resident/inmatestatus/index', '', true );
			
			
			
			if (isset ( $this->request->get ['page'] )) {
				$page = $this->request->get ['page'];
			} else {
				$page = 1;
			}
			
			$config_admin_limit = "2000";
			
			$this->data ['facilityname'] = $this->customer->getfacility ();
			
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if(!$facilities_info){
				throw new Exception('Facilities not found');
			}
			
			$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			if ($facilities_is_master ['is_master_facility'] == 0) {
				$is_master_facility = 1;
			} else {
				$is_master_facility = $facilities_is_master ['is_master_facility'];
			}
			
			
			
			if ($this->request->get ['sort'] != null && $this->request->get ['sort'] != "") {
				$this->data ['sort'] = $this->request->get ['sort'];
				$sort = $this->request->get ['sort'];
				
			} else {
				$sort = 'emp_last_name';
			}
			if ($this->request->get ['order'] != null && $this->request->get ['order'] != "") {
				$this->data ['order'] = $this->request->get ['order'];
				$order = $this->request->get ['order'];
			} else {
				$order = 'ASC';
			}
			
			$data31 = array (
				'sort' => $sort,
				'order' => $order,
				'status' => 1,
				//'discharge' => $discharge,
				// 'role_call' =>$rolecall,
				//'rolecalls' => $rolecalls,
				//'emp_tag_id_all' => $data_tags,
				'is_master' => $is_master_facility,
				'gender2' => $this->request->get ['gender'],
				'client_status' => $this->request->get ['client_status'],
				//'sort' => 'emp_last_name',
				'facilities_id' => $facilities_id,
				'is_client_screen' => $is_client_screen,
				'room_id'=>$this->request->get['room_id'],
				'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
				'wait_list' => $this->request->get ['wait_list'],
				'all_record' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
			);
			
			//echo '<pre>'; print_r($data31); echo '</pre>';
			
			$this->load->model ( 'setting/tags' );
			$tags = $this->model_setting_tags->getTags ( $data31 );
			
			//$this->data ['tags'] = $tags;
			
			$this->load->model ( 'setting/locations' );
			$data = array (
				'location_name' => $this->request->get ['filter_name'],
				'facilities_id' => $facilities_id,
				'status' => '1',
				'sort' => 'task_form_name',
				'order' => 'ASC' 
			);
			
			$rresults = $this->model_setting_locations->getlocations ( $data );
			
			foreach ( $rresults as $result ) {
				
				$this->data ['rooms'] [] = array (
					'locations_id' => $result ['locations_id'],
					'location_name' => $result ['location_name'],
					'date_added' => $result ['date_added'] 
				);
			}
			
			$this->load->model('facilities/facilities');
			$data = array();
			$data['status'] = '1';
			$this->data['allfacilities'] = $this->model_facilities_facilities->getfacilitiess($data);
			
			$url2 = "";
			if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
				$facilities_id = $this->request->get ['facilities_id'];
			} else {
				$url2 .= "&facilities_id=" . $facilities_id;
				$facilities_id = $this->customer->getId ();
			}
			
			$url2 .= '&inmatestatus=1';
			
			
			
			if ($this->request->post ['form_submit'] == '1' && $this->validateForms ()) {
			
				$this->session->data ['inmate_status_detail'] = $this->request->post; 
				
				//echo '<pre>'; print_r($this->request->post); echo '</pre>'; die;
				
				$this->load->model ( 'facilities/facilities' );
		
				$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				
				if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
					
					$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
				} else {
					$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/inmatestatus/updateclientstatussigns', '' . $url2, 'SSL' ) );
				}
				
			}	
			
			
			
			//echo '<pre>'; print_r($this->data ['rooms']); echo '</pre>';
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/resident/inmatestatus.php';
			$this->children = array (
				'common/headerclient',
				'common/footerclient' 
			);
			$this->response->setOutput ( $this->render () );
		
		} catch ( Throwable $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array ('data' => 'Error in clients list : '.$e->getMessage());
			//echo '<pre>rrrr'; print_r($activity_data2); echo '</pre>'; //die;
			$this->model_activity_activity->addActivity ( 'clients', $activity_data2 ); //die;
		}
	}
	
	protected function validateForms() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		
		//if ($this->request->post ['formid'] == "") {
		//	$this->error ['warning'] = "This is required field!";
		//}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function ajax(){
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$this->load->model ( 'notes/notes' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		$this->document->setTitle ( 'Inmate Status' );
		$facilities_id = $this->customer->getId ();
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$config_admin_limit = "200";
		
		$this->data ['facilityname'] = $this->customer->getfacility ();
		
		$this->load->model ( 'facilities/facilities' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if(!$facilities_info){
			throw new Exception('Facilities not found');
		}
		
		$status_url = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allclientstatus', '', 'SSL' ) );
		
		$facilities_is_master = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_is_master ['is_master_facility'] == 0) {
			$is_master_facility = 1;
		} else {
			$is_master_facility = $facilities_is_master ['is_master_facility'];
		}
		
		if ($this->request->get ['sort'] != null && $this->request->get ['sort'] != "") {
			$this->data ['sort'] = $this->request->get ['sort'];
			$sort = $this->request->get ['sort'];
			
		} else {
			$sort = 'emp_last_name';
		}
		if ($this->request->get ['order'] != null && $this->request->get ['order'] != "") {
			$this->data ['order'] = $this->request->get ['order'];
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		
		if($this->request->get ['facilities_id']!='' || $this->request->get ['facilities_id']!=null){
			$facilities_id2 = $this->request->get ['facilities_id'];
		}else{
			$facilities_id2 = $this->customer->getId ();
		}
		
		$rolecalls='';
		if ($this->request->get ['in_out_cell'] != "" && $this->request->get ['in_out_cell'] != null) {
			$data3 = array ();
			$data3 ['facilities_id'] = $facilities_id;
			//$data3 ['role_call'] = $this->request->get ['role_call'];
			
			$this->load->model ( 'notes/clientstatus' );
			$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
			
			foreach ( $customforms as $customform ) {
				
				
				
				if ($customform ['type'] == "0" || $customform ['type'] == "2") {
					$inclint [] = $customform ['tag_status_id'];
				}
				
				if ($facilities_is_master ['enable_facilityinout'] == '1') {
					if ($customform ['type'] == "3") {
						$inclint [] = $customform ['tag_status_id'];
					}
				} else {
					if ($customform ['type'] == "3") {
						$outcount [] = $customform ['tag_status_id'];
					}
				}
				
				if ($customform ['type'] == "4") {
					$movecount [] = $customform ['tag_status_id'];
				}
				
			}
			
			if($this->request->get ['in_out_cell']!='' && $this->request->get ['in_out_cell']=='out_the_cell'){
				$rolecalls = implode(',',$outcount);
			}
			
			if($this->request->get ['in_out_cell']!='' && $this->request->get ['in_out_cell']=='in_the_cell'){
				$rolecalls = implode(',',$inclint);
			}
			
			
			
		}
		
		
		
		
		
		//echo '<pre>movecount'; print_r($movecount); echo '</pre>';
		//echo '<pre>inclint'; print_r($inclint); echo '</pre>';
		//echo '<pre>outcount'; print_r($outcount); echo '</pre>';
		
		$data31 = array (
			'sort' => $sort,
			'order' => $order,
			'status' => 1,
			//'discharge' => $discharge,
			'rolecalls' => $rolecalls,
			//'emp_tag_id_all' => $data_tags,
			'emp_tag_id_2' => $this->request->get ['searchbox'],
			'is_master' => $is_master_facility,
			'gender2' => $this->request->get ['gender'],
			'client_status' => $this->request->get ['client_status'],
			//'sort' => 'emp_last_name',
			'facilities_id' => $facilities_id2,
			'is_client_screen' => $is_client_screen,
			'room_id'=>$this->request->get['room_id'],
			'search_tags_tag_id' => $this->request->get ['search_tags_tag_id'],
			'wait_list' => $this->request->get ['wait_list'],
			'all_record' => '1',
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		
		//echo '<pre>'; print_r($data31); echo '</pre>';
		
		$this->load->model ( 'setting/tags' );
		$tags = $this->model_setting_tags->getTags ( $data31 );
		$data3 = array ();
		$data3 ['status'] = '1';
		$data3 ['facilities_id'] = $facilities_id;
		$data3 ['display_client'] = "";
		$this->load->model ( 'resident/resident' );
		$client_statuses = $this->model_resident_resident->getClientStatus ( $data3 );
		//echo '<pre>'; print_r($tags); echo '</pre>';	
		
		
		$html='';
		
		if(!empty($tags)){
			
			$html.='<script>
			$(".status_page_url").colorbox({iframe:true, width:"90%", height:"90%"});$(".status_url").colorbox({iframe:true, width:"90%", height:"90%"});</script><table width="100%" class="scroll table table-bordered table-hover" cellpadding="10" cellspacing="10">
                <thead>
					<tr style="background:#F26B21;">
						<th style="width: 5%;">
							<input type="checkbox" id="checkAll">
						</th>
						<th style="width: 30%;">Name</th>
						<th style="width: 30%;">
						<a class="status_page_url" href="'.$status_url.'&atype=inmatestatus&type=global">
						<img  src="sites/view/digitalnotebook/image/Status.png" width="30px" height="30px" alt="Status" title="Status" /> Status</a>
						
						 
						</th>
						<th style="width: 35%;">Comment</th>
					</tr>
                </thead>
                <tbody>';
				
				
			$increment = 501;
			$status_image_url='';
			$tag_status_ids_arr=array();
			$substatus_name_str='';
	
			foreach($tags AS $tag){
				
				$rule_action_content = unserialize($tag['rule_action_content']);
				$name = $tag['emp_last_name'].','.$tag['emp_first_name'];
				$edetail='';
				
				if($tag['ssn']!=''){
					$edetail .= 'SID#: '.$tag['ssn'].'<br>';
				}
				
				if($tag['emp_extid']!=''){
					$edetail .= 'ATN#: '.$tag['emp_extid'].'<br>';
				}
				
				if($tag['emp_extid']!=''){
					$edetail .= 'CCN#: '.$tag['ccn'].'<br>';
				}
				
				if($tag['location_name']!=''){
					$edetail .= 'LOCATION#: '.$tag['location_name'];
				}
				
				$html.='<tr class="product-options">';
				$checked='';
				if($tag['type']==3 || $tag['type']==4){
					$checked = 'checked="checked"';
				}
				
				
				$tags_id = $tag['tags_id'];
				
				if($tag['tag_status_id']!=''){
					$tag_status_id = $tag['tag_status_id'];
				}
				
				
				if($tag['tag_status_ids']!=''){
					$tag_status_ids = $tag['tag_status_ids'];
				}
				
				
				$status_image='';
				if($tag['tag_status_id']!=''){
					$client_img = $this->model_resident_resident->getClientStatusById ( $tag['tag_status_id'] );
					$status_image = '<div id="img_'.$increment.'"><img src='.$client_img['image'].'> '.$client_img['name'].' </div>';
				}else{
					$status_image = '<div id="img_'.$increment.'"><img  src="sites/view/digitalnotebook/image/Status.png" width="20px" height="20px" alt="Status" title="Status" /> Status..</div>';
				}
			
				if($tag['tag_status_ids']!=''){
					$substatus_name = array();
					$substatus_name2 = array();
					$substatus_arr = array();
					$tag_status_ids_arr = explode(',',$tag['tag_status_ids']);
					foreach($tag_status_ids_arr AS $ids){
				
						$client_status = $this->model_resident_resident->getClientStatusById ( $ids );
						
						if($client_status['name']!=''){	
							$substatus_name[] = '<span>'.$client_status['name'].'</span>';
							$substatus_name2[] = $client_status['name'];
							$substatus_arr[] = $ids; 
						}	
					}
					
					$substatus_ids_str = $tag_status_id.'-'.implode(',',$substatus_arr);
					
					$substatus_name_str = implode(' | ',$substatus_name);
					
					$substatus_name2_str = implode(',',$substatus_name2);
					
					$substatus='<span>'.$substatus_name_str.'</span>';
					
				}
				
				
				
				
				$html.='
			
				<td class="test" style="width:5%;">
					<input type="hidden" id="tags_id_'.$increment.'" name="inmate_status['.$increment.'][tags_id]" value="'.$tags_id.'">
					
					<input type="hidden" class="tags_status_id" id="tags_status_id_'.$increment.'" name="inmate_status['.$increment.'][tags_status_id]" value="'.$tag_status_id.'">
					
					<input type="hidden" class="tags_status_name" id="tags_status_name_'.$increment.'" name="inmate_status['.$increment.'][tags_status_name]" value="'.$substatus_name2_str.'">
					
					<input id="is_changed_'.$increment.'" class="checkbox" '.$checked.' name="multipleaction[]" type="checkbox" '.$client_status_type.' value="'.$tags_id.'">
					
					
				</td>';
				
				$html.='<td class="test2" style="width: 30%;"><b>'.$name.'</b><br>'.nl2br($edetail).'</td>';
				
				$status_image_url = '<a id="status_'.$increment.'" class="status_url" href="'.$status_url.'&tags_id='.$tags_id.'&tag_status_id='.$tag['tag_status_id'].'&facilities_id='.$tag['facilities_id'].'&atype=inmatestatus&increment='.$increment.'">'.$status_image.'<div id="substatus_list_'.$increment.'" style="display: block;overflow-x: auto; white-space: nowrap;scrollbar-width: none;"><span>'.$substatus.'</span></div></a>';
				
					
				
				
				$html.='<td align="" valign="middle"  style="border-collapse: collapse; width: 30%;">'.$status_image_url.'</td>';
				$required='';
				if($rule_action_content['comment_required']){
					$required= ' required';
				}
				
				//$html.='<td style="padding:0px; width: 33.5%;"><textarea name="inmate_status['.$tags_id.']['.$tag_status_id.'][comment]" style="height: 50px;width: 385px;" '.$required.'></textarea></td>';
				
				$html.='<td style="padding:0px; width: 33.5%;"><textarea name="inmate_status['.$increment.'][comment]" style="height: 50px;width: 385px;" '.$required.'></textarea></td>';
				
						
				$html.='</tr>';
				$increment++;
				
			}
		}else{
			$html.='<p style="text-align: center;">There is no any client</p>';
		}
		
		$html.='</tbody></table>';
		
		echo $html;
	}
	
	public function status_list($client_statuses=null, $tag_status_id=null){
		$image = '';
		foreach($client_statuses AS $row){
			//echo '<pre>'; print_r($row['image']); echo '</pre>';
			if($row['tag_status_id']==$tag_status_id){
				$image = $row['image'];
			}else{
				$image ='';
			}
			return $image;	
		}	
	}
	
	public function substatus_list($client_statuses=null,$tag_status_ids=null){
		$tag_status_ids_arr =  explode(',',$tag_status_ids);
		foreach($client_statuses AS $row){
			if(in_array($row['tag_status_id'],$tag_status_ids_arr)){
				
				$xxx = '<div class="form-check">
				  <label class="form-check-label">
					<input type="checkbox" name="substatus[]" class="form-check-input" value="'.$row['tag_status_id'].'">'.$row['name'].'
				  </label>
				</div>';
				
				
				$stname[] = $xxx;
			}	
		}
		return implode(' ', $stname);
	}
	
	public function status_type($client_statuses=null,$tag_status_id=null){
		
		foreach($client_statuses AS $row){
			
			if(($row['type']==3 || $row['type']==4) && $row['tag_status_id']==$tag_status_id){
				$checked = 'checked="checked"';
			}else{
				$checked ='';
			}
		}										
		
		return $checked;	
		
	}
	
	public function get_location(){
		$this->load->model ( 'setting/locations' );
		$data = array (
			//'location_name' => $this->request->get ['filter_name'],
			'facilities_id' => $this->request->get ['facilities_id'],
			'status' => '1',
			'sort' => 'task_form_name',
			'order' => 'ASC' 
		);
		
		$rresults = $this->model_setting_locations->getlocations ( $data );
		
		$html='';
		if(!empty($rresults)){
			$html.='<option value="">Select location</option>';
			
			foreach ( $rresults as $result ) {
				
				$html.='<option value="'.$result ['locations_id'].'">'.$result ['location_name'].'</option>';
			}
		}else{
			$html.='<option value="">Not Available</option>';
		}

		echo $html;	
	}
	
	public function multipleaction() {
		$this->load->model ( 'resident/resident' );
		$facilities_id = $this->customer->getId ();
		$this->load->model ( 'notes/notes' );
		if($this->request->get ['inmate_verified']==1){
			
			$inmate_status_detail = $this->session->data ['inmate_status_detail'];
			
			//echo '<pre>ffff'; print_r($inmate_status_detail); echo '</pre>'; //die;
			
			foreach($inmate_status_detail['multipleaction'] AS $row){
				$tag_id_arr[] = $row;				
			}
			
			$tag_ids_str = implode(',',$tag_id_arr);
			
			foreach($inmate_status_detail AS $key=>$val){
				
				if($key=='inmate_status'){
					
					if(is_array($val)){
						
						foreach($val AS $tags_detail){
							
							$tag_status_id = $tags_detail['tags_status_id']; 
							$tag_status = $this->model_resident_resident->getClientStatusById ( $tag_status_id );
							$tdata ['tagsids'] = $tag_ids_str;
							$tdata ['tags_id'] = $tags_detail['tags_id']; 
							$tdata ['role_calls'] = "";
							$tdata ['tag_status_id'] = $tags_detail['tags_status_id'];
							$tdata ['substatus_ids'] = $tags_detail['tags_status_name'];
							$tdata ['name'] = $tag_status['name'];
							$tdata ['facilities_id'] = $facilities_id;
							$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
							$tdata ['image_icon'] = $tag_status['image'];
							$tdata ['comment'] = $tags_detail['comment'];
							
							echo '<pre>'; print_r($tdata); echo '</pre>';
							
							
							
							$notes_id = $this->model_resident_resident->allclientstatussigns2 ( $this->request->post, $tdata );
							
							//unset($this->session->data ['mfacilities_id']);
							
							
							$notes_ids [] = $notes_id;
							
							
							/*
							$data ['form_key'] = $inmate_status_detail ['form_key'];
							$data ['note_date'] = date('d-m-Y H:i:s');
							$data ['keyword_file'] = $tag_status['image'];
							$data ['multi_keyword_file'] = '';
							
							$data ['notetime'] = date('h:i A');
							
							$data ['notes_description'] = 'Status changed status name 1 to status name 2 '. $tag_status_ids_arr['comment'];
							

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
							
							*/
								
						}	
					}
				}
			}
			
			/*foreach ( $notes_ids as $notes_id ) {
				
				if ($keyword_id != null || $keyword_id != "") {
					
					$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_ids );
				}
			}*/
			
			die;	
		}
		
		
		
		
		
		
		
	
		$this->load->model ( 'facilities/facilities' );
		
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($facilities_info ['is_master_facility'] == '1') {
			
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		} else {
			$facilities_id = $this->customer->getId ();
			$timezone_name = $this->customer->isTimezone ();
			$timeZone = date_default_timezone_set ( $timezone_name );
		}
		
		
		$this->load->model ( 'facilities/facilities' );
        $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
        $unique_id = $facility ['customer_key'];
        
        $this->load->model ( 'customer/customer' );
        $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
        $this->data['customers'] = array();
        if (! empty ( $customer_info ['setting_data'])) {
            $customers = unserialize($customer_info ['setting_data']);
            $this->data['customerinfo'] = $customers;
        }
		
		
		
		$url2 = "";
		
		if ($this->request->get ['facilities_id'] != null && $this->request->get ['facilities_id'] != "") {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$url2 .= "&facilities_id=" . $facilities_id;
			$facilities_id = $this->customer->getId ();
		}
		
		$url2 .= '&inmatestatus=1';
		
		echo $url2;
		
		
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		
		//echo '<pre>'; print_r($facility); echo '</pre>'; //die;
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/inmatestatus', '' . $url2, 'SSL' ) );
		}
		
		
		
		
		
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/inmatestatus.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function updateinmatestatus() {
		
		// var_dump($this->session->data);die;
		
		
		if ($this->request->get ['facilities_id'] != "" && $this->request->get ['facilities_id'] != null) {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		if ($this->request->get ['keyword_id'] != "" && $this->request->get ['keyword_id'] != null) {
			
			$keyword_id = $this->request->get ['keyword_id'];
		} else {
			
			$keyword_id = "";
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			
			$sssssdd = explode ( ",", $this->request->get ['tags_ids'] );
			
			foreach ( $sssssdd as $tags_id ) {
				
				$tdata ['tagsids'] = $this->request->get ['tags_ids'];
				$tdata ['tags_id'] = $tags_id;
				$tdata ['role_calls'] = "";
				$tdata ['tag_status_id'] = $this->request->get ['tag_status_id'];
				$tdata ['substatus_ids'] = $this->request->get ['substatus_ids'];
				$tdata ['name'] = $this->request->get ['name'];
				$tdata ['facilities_id'] = $facilities_id;
				$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
				
				$notes_id = $this->model_resident_resident->allclientstatussigns ( $this->request->post, $tdata );
				
				// var_dump($notes_id);
				
				$notes_ids [] = $notes_id;
				
			}
			
			
			unset($this->session->data ['mfacilities_id']);
			unset($this->session->data ['movement_room']);
			
			$this->session->data ['success_update_form_2'] = 'Status updated';
			
			foreach ( $notes_ids as $notes_id ) {
				
				if ($keyword_id != null || $keyword_id != "") {
					
					$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_ids );
				}
			}
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			/*
			 * if ($this->request->get['all_roll_call'] != null &&
			 * $this->request->get['all_roll_call'] != "") {
			 * $url2 .= '&all_roll_call=' .
			 * $this->request->get['all_roll_call'];
			 * }
			 */
			// $this->redirect(str_replace('&amp;', '&',
			// $this->url->link('resident/resident', '', 'SSL')));
		}
		
		// die;
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['all_roll_call'] != null && $this->request->get ['all_roll_call'] != "") {
			$url2 .= '&all_roll_call=' . $this->request->get ['all_roll_call'];
		}
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
			$url2 .= '&name=' . $this->request->get ['name'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		if ($this->request->get ['substatus_ids'] != null && $this->request->get ['substatus_ids'] != "") {
			$url2 .= '&substatus_ids=' . $this->request->get ['substatus_ids'];
		}
		if ($this->request->get ['substatus'] != null && $this->request->get ['substatus'] != "") {
			$url2 .= '&substatus=' . $this->request->get ['substatus'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussigns', '' . $url2, 'SSL' ) );
		
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if ($keyword_id != null || $keyword_id != "") {
			$this->data ['keyword_id'] = $keyword_id;
		} else {
			$this->data ['keyword_id'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
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
		
		
		$this->load->model ( 'notes/clientstatus' );
		$tag_status_info = $this->model_notes_clientstatus->getclientstatus ($this->request->get ['tag_status_id']);
		
		if ($tag_status_info['disabled_escorted'] == '1') {
			$this->data ['show_escort'] = "0";
		} else {
			$this->data ['show_escort'] = "1";
		}
		
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			$escort_user_ids = $this->request->post ['escort_user_ids'];
		} else {
			$escort_user_ids = array ();
		}
		
		$this->data ['totalusers'] = array ();
		$this->load->model ( 'user/user' );
		
		foreach ( $escort_user_ids as $user_id ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $user_id );
			
			if ($user_info) {
				$this->data ['totalusers'] [] = array (
						'username' => $user_info ['username'],
						'user_id' => $user_info ['user_id'] 
				);
			}
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['escort_user_id'] )) {
			$this->data ['error_escort_user_id'] = $this->error ['escort_user_id'];
		} else {
			$this->data ['error_escort_user_id'] = '';
		}
		
		if (isset ( $this->error ['highlighter_id'] )) {
			$this->data ['error_highlighter_id'] = $this->error ['highlighter_id'];
		} else {
			$this->data ['error_highlighter_id'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		
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
		
		if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
			$this->data ['default_sign'] = '1'; // $this->config->get('config_default_sign');
		} else {
			$this->data ['default_sign'] = '2';
		}
		
		if (isset ( $this->request->post ['notes_pin'] )) {
			$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['notes_pin'] = $notes_info ['notes_pin'];
		} else {
			$this->data ['notes_pin'] = '';
		}
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		// $this->load->model('setting/tags');
		// $tag_info =
		// $this->model_setting_tags->getTag($this->request->get['tags_id']);
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		$this->load->model ( 'notes/notes' );
		
		if (isset ( $this->request->post ['customlistvalues_ids'] )) {
			$customlistvalues_ids1 = $this->request->post ['customlistvalues_ids'];
		} else {
			$customlistvalues_ids1 = array ();
		}
		
		$this->data ['customlistvalues_ids'] = array ();
		$this->load->model ( 'notes/notes' );
		
		foreach ( $customlistvalues_ids1 as $customlistvalues_id ) {
			
			$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
			
			if ($custom_info) {
				$this->data ['customlistvalues_ids'] [] = array (
						'user_id' => $customlistvalues_id,
						'customlistvalues_name' => $custom_info ['customlistvalues_name'],
						'required' => $custom_info ['required'] 
				);
			}
		}
		
		if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
			
			$d = array ();
			
			$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
			
			$customlists = $this->model_notes_notes->getcustomlists ( $d );
			
			if ($customlists) {
				foreach ( $customlists as $customlist ) {
					$d2 = array ();
					$d2 ['customlist_id'] = $customlist ['customlist_id'];
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
					$this->data ['customlists'] [] = array (
							'customlist_id' => $customlist ['customlist_id'],
							'customlist_name' => $customlist ['customlist_name'],
							'customlistvalues' => $customlistvalues 
					);
					
					foreach ( $customlistvalues as $value ) {
						
						$this->data ['customlistvalues_ids'] [] = array (
								'user_id' => $value ['customlistvalues_id'],
								'customlistvalues_name' => $value ['customlistvalues_name'],
								'required' => $value ['required'] 
						);
					}
				}
			}
			
			$this->data ['id_url'] .= '&facilities_id=' . $this->customer->getId ();
		}
		
		if (isset ( $this->request->post ['tags_ids'] )) {
			$tagides1 = $this->request->post ['tags_ids'];
		} elseif (! empty ( $this->request->get ['tags_ids'] )) {
			$tagides1 = $this->request->get ['tags_ids'];
			$this->data ['is_multiple_tags_count'] = '1';
		} else {
			$tagides1 = array ();
		}
		
		$sssssdd = explode ( ",", $tagides1 );
		
		$this->data ['tags_ids'] = array ();
		$this->load->model ( 'setting/tags' );
		
		foreach ( $sssssdd as $tagsid ) {
			
			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
						'tags_id' => $tagsid,
						'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'] 
				);
			}
		}
		
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	
	public function updateclientstatussigns() {
		
		// var_dump($this->session->data);die;
		if ($this->request->get ['facilities_id'] != "" && $this->request->get ['facilities_id'] != null) {
			
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			
			$facilities_id = $this->customer->getId ();
		}
		
		if ($this->request->get ['keyword_id'] != "" && $this->request->get ['keyword_id'] != null) {
			
			$keyword_id = $this->request->get ['keyword_id'];
		} else {
			
			$keyword_id = "";
		}
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'resident/resident' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$tdata = array ();
			
			$sssssdd = explode ( ",", $this->request->get ['tags_ids'] );
			
			foreach ( $sssssdd as $tags_id ) {
				
				$tdata ['tagsids'] = $this->request->get ['tags_ids'];
				$tdata ['tags_id'] = $tags_id;
				$tdata ['role_calls'] = "";
				$tdata ['tag_status_id'] = $this->request->get ['tag_status_id'];
				$tdata ['substatus_ids'] = $this->request->get ['substatus_ids'];
				$tdata ['name'] = $this->request->get ['name'];
				$tdata ['facilities_id'] = $facilities_id;
				$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
				
				$notes_id = $this->model_resident_resident->allclientstatussigns ( $this->request->post, $tdata );
				
				$notes_ids [] = $notes_id;
				
			}
			
			//echo '<pre>'; print_r($tdata); echo '</pre>';
			
			
			unset($this->session->data ['mfacilities_id']);
			unset($this->session->data ['movement_room']);
			
			$this->session->data ['success_update_form_2'] = 'Status updated';
			
			foreach ( $notes_ids as $notes_id ) {
				
				if ($keyword_id != null || $keyword_id != "") {
					
					$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_ids );
				}
			}
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			/*
			 * if ($this->request->get['all_roll_call'] != null &&
			 * $this->request->get['all_roll_call'] != "") {
			 * $url2 .= '&all_roll_call=' .
			 * $this->request->get['all_roll_call'];
			 * }
			 */
			// $this->redirect(str_replace('&amp;', '&',
			// $this->url->link('resident/resident', '', 'SSL')));
		}
		
		// die;
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId () );
		
		$this->data ['config_tag_status'] = $this->customer->isTag ();
		
		$url2 = "";
		
		if ($this->request->get ['updatenotes_id'] != null && $this->request->get ['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get ['updatenotes_id'];
		}
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		
		$data = array (
				'searchdate' => date ( 'm-d-Y' ),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId () 
		);
		
		$this->load->model ( 'notes/notes' );
		$notes_total = $this->model_notes_notes->getTotalnotess ( $data );
		$pagenumber_all = ceil ( $notes_total / $config_admin_limit );
		
		if ($pagenumber_all != null && $pagenumber_all != "") {
			if ($pagenumber_all > 1) {
				$url2 .= '&page=' . $pagenumber_all;
			}
		}
		
		$url2 = "";
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
		}
		if ($this->request->get ['all_roll_call'] != null && $this->request->get ['all_roll_call'] != "") {
			$url2 .= '&all_roll_call=' . $this->request->get ['all_roll_call'];
		}
		
		if ($this->request->get ['tags_ids'] != null && $this->request->get ['tags_ids'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_ids'];
		}
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_ids=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['tag_status_id'] != null && $this->request->get ['tag_status_id'] != "") {
			$url2 .= '&tag_status_id=' . $this->request->get ['tag_status_id'];
		}
		
		if ($this->request->get ['name'] != null && $this->request->get ['name'] != "") {
			$url2 .= '&name=' . $this->request->get ['name'];
		}
		if ($this->request->get ['keyword_id'] != null && $this->request->get ['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get ['keyword_id'];
		}
		if ($this->request->get ['substatus_ids'] != null && $this->request->get ['substatus_ids'] != "") {
			$url2 .= '&substatus_ids=' . $this->request->get ['substatus_ids'];
		}
		if ($this->request->get ['substatus'] != null && $this->request->get ['substatus'] != "") {
			$url2 .= '&substatus=' . $this->request->get ['substatus'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateclientstatussigns', '' . $url2, 'SSL' ) );
		
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if ($keyword_id != null || $keyword_id != "") {
			$this->data ['keyword_id'] = $keyword_id;
		} else {
			$this->data ['keyword_id'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
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
		
		
		$this->load->model ( 'notes/clientstatus' );
		$tag_status_info = $this->model_notes_clientstatus->getclientstatus ($this->request->get ['tag_status_id']);
		
		if ($tag_status_info['disabled_escorted'] == '1') {
			$this->data ['show_escort'] = "0";
		} else {
			$this->data ['show_escort'] = "1";
		}
		
		
		if (isset ( $this->request->post ['escort_user_ids'] )) {
			$escort_user_ids = $this->request->post ['escort_user_ids'];
		} else {
			$escort_user_ids = array ();
		}
		
		$this->data ['totalusers'] = array ();
		$this->load->model ( 'user/user' );
		
		foreach ( $escort_user_ids as $user_id ) {
			
			$user_info = $this->model_user_user->getUserbyupdate ( $user_id );
			
			if ($user_info) {
				$this->data ['totalusers'] [] = array (
						'username' => $user_info ['username'],
						'user_id' => $user_info ['user_id'] 
				);
			}
		}
		
		if (isset ( $this->error ['select_one'] )) {
			$this->data ['error_select_one'] = $this->error ['select_one'];
		} else {
			$this->data ['error_select_one'] = '';
		}
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['escort_user_id'] )) {
			$this->data ['error_escort_user_id'] = $this->error ['escort_user_id'];
		} else {
			$this->data ['error_escort_user_id'] = '';
		}
		
		if (isset ( $this->error ['highlighter_id'] )) {
			$this->data ['error_highlighter_id'] = $this->error ['highlighter_id'];
		} else {
			$this->data ['error_highlighter_id'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		
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
		
		if ($this->config->get ( 'config_default_sign' ) != null && $this->config->get ( 'config_default_sign' ) != "") {
			$this->data ['default_sign'] = '1'; // $this->config->get('config_default_sign');
		} else {
			$this->data ['default_sign'] = '2';
		}
		
		if (isset ( $this->request->post ['notes_pin'] )) {
			$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['notes_pin'] = $notes_info ['notes_pin'];
		} else {
			$this->data ['notes_pin'] = '';
		}
		
		$this->data ['local_image_url'] = $this->session->data ['local_image_url'];
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} elseif (! empty ( $this->session->data ['username_confirm'] )) {
			$this->data ['user_id'] = $this->session->data ['username_confirm'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		// $this->load->model('setting/tags');
		// $tag_info =
		// $this->model_setting_tags->getTag($this->request->get['tags_id']);
		
		if (isset ( $this->request->post ['emp_tag_id'] )) {
			$this->data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		} else {
			$this->data ['emp_tag_id'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['tags_id'] = $tag_info ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		
		if (isset ( $this->request->post ['emp_tag_id_2'] )) {
			$this->data ['emp_tag_id_2'] = $this->request->post ['emp_tag_id_2'];
		} elseif (! empty ( $tag_info )) {
			$this->data ['emp_tag_id_2'] = $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		} else {
			$this->data ['emp_tag_id_2'] = '';
		}
		
		if (isset ( $this->request->post ['comments'] )) {
			$this->data ['comments'] = $this->request->post ['comments'];
		} else {
			$this->data ['comments'] = '';
		}
		
		$this->data ['createtask'] = 1;
		
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		$this->load->model ( 'notes/notes' );
		
		if (isset ( $this->request->post ['customlistvalues_ids'] )) {
			$customlistvalues_ids1 = $this->request->post ['customlistvalues_ids'];
		} else {
			$customlistvalues_ids1 = array ();
		}
		
		$this->data ['customlistvalues_ids'] = array ();
		$this->load->model ( 'notes/notes' );
		
		foreach ( $customlistvalues_ids1 as $customlistvalues_id ) {
			
			$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
			
			if ($custom_info) {
				$this->data ['customlistvalues_ids'] [] = array (
						'user_id' => $customlistvalues_id,
						'customlistvalues_name' => $custom_info ['customlistvalues_name'],
						'required' => $custom_info ['required'] 
				);
			}
		}
		
		if ($facilityinfo ['config_rolecall_customlist_id'] != NULL && $facilityinfo ['config_rolecall_customlist_id'] != "") {
			
			$d = array ();
			
			$d ['customlist_id'] = $facilityinfo ['config_rolecall_customlist_id'];
			
			$customlists = $this->model_notes_notes->getcustomlists ( $d );
			
			if ($customlists) {
				foreach ( $customlists as $customlist ) {
					$d2 = array ();
					$d2 ['customlist_id'] = $customlist ['customlist_id'];
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
					$this->data ['customlists'] [] = array (
							'customlist_id' => $customlist ['customlist_id'],
							'customlist_name' => $customlist ['customlist_name'],
							'customlistvalues' => $customlistvalues 
					);
					
					foreach ( $customlistvalues as $value ) {
						
						$this->data ['customlistvalues_ids'] [] = array (
								'user_id' => $value ['customlistvalues_id'],
								'customlistvalues_name' => $value ['customlistvalues_name'],
								'required' => $value ['required'] 
						);
					}
				}
			}
			
			$this->data ['id_url'] .= '&facilities_id=' . $this->customer->getId ();
		}
		
		if (isset ( $this->request->post ['tags_ids'] )) {
			$tagides1 = $this->request->post ['tags_ids'];
		} elseif (! empty ( $this->request->get ['tags_ids'] )) {
			$tagides1 = $this->request->get ['tags_ids'];
			$this->data ['is_multiple_tags_count'] = '1';
		} else {
			$tagides1 = array ();
		}
		
		$sssssdd = explode ( ",", $tagides1 );
		
		$this->data ['tags_ids'] = array ();
		$this->load->model ( 'setting/tags' );
		
		foreach ( $sssssdd as $tagsid ) {
			
			$tag_info = $this->model_setting_tags->getTag ( $tagsid );
			
			if ($tag_info) {
				$this->data ['tagides'] [] = array (
						'tags_id' => $tagsid,
						'emp_tag_id' => $tag_info ['emp_tag_id'] . ': ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'] 
				);
			}
		}
		
		$this->data ['is_multiple_tags'] = IS_MAUTIPLE;
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	
	
}