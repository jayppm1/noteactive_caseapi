<?php
	class Controllerresidentdashboard extends Controller {
   	
	private $error = array ();
   
   	public function index() {
   		
   		$url2='';
   		$url3 = '';
   		
   		if (! $this->customer->isLogged ()) {
   			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
   		}
   		
   		$this->load->model ( 'facilities/facilities' );
   		
   		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
   		
   		$unique_id = $facility ['customer_key'];
   		$facility_id = $this->customer->getId ();
   		$this->load->model ( 'customer/customer' );
   		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
   		$activecustomer_id = $customer_info['activecustomer_id'];
   		$this->data ['reload_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/dashboard', '', 'SSL' ) );
   		$this->document->setTitle ( 'Mini Dashboard' );
   		$this->data['add_case_url'] = $this->url->link ('resident/formcase/addcase');
   		$this->data ['add_casecovepage_url'] = $this->url->link ( 'resident/formcase/addcasecovepage', $url, true );
   		
   		if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
   				
   			$tags_id = $this->request->get ['tags_id'];
   			
   			$url .= '&tags_id=' . $this->request->get ['tags_id'];
   			
   			$this->data ['tags_id'] = $this->request->get ['tags_id'];
   		}
   			
   		$tag_data = array ();
   		$tag_data ['tags_id'] = $tags_id;
   		
   	
   		$url2 .= '&tags_id=' . $tags_id;
   		
   		
   		
   		$url2 .= '&facility_id=' . $facility_id;
   		
   		$url2 .= '&min_dashboard=1';
   		
   		
   		$this->data ['createtask_url'] = $this->url->link ( 'notes/createtask', ''.$url2, 'SSL' );
   		
   		$this->data ['tag_forms'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url2, 'SSL' ) );
   		
   		$this->data ['add_client_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', ''.$url2, 'SSL' ) );
   		
   		$this->data ['facility_id'] = $facility_id;
   		
		$this->data ['change_status_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&change_status=1', '', 'SSL' ));
   		
		
		
   		
   		/*$this->load->model('setting/tags');
   		
   		$client_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
   		
   		
   		$get_clientimg = $this->model_setting_tags->getImage ( $client_info ['tags_id'] );
   			
   		if ($get_clientimg ['enroll_image'] != null && $get_clientimg ['enroll_image'] != "") {
   			$this->data ['upload_file_thumb_1'] = $get_clientimg ['enroll_image'];
   		} else {
   			$this->data ['upload_file_thumb_1'] = '';
   		}
   		
   		
   		
   		
   		//if($client_info ["show_client_image"]!=''){
   		$this->data ['show_client_image'] = $client_info ['show_client_image'];
   		//}
   		
   		
   		//echo '<pre>'; print_r($client_info); echo '</pre>';
   		
   		
   		if($client_info ['emp_first_name']!=''){
   			$this->data ['client_first_name'] = $client_info ['emp_first_name'];
   		}else{
   			$this->data ['client_first_name'] = 'N/A';
   		}
   		
   		if($client_info ['emp_last_name']!=''){
   			$this->data ['client_last_name'] = $client_info ['emp_last_name'];
   		}else{
   			$this->data ['client_last_name'] = 'N/A';
   		}
   		
   		if($client_info ['ssn']!='' && $client_info ['ssn']!='undefined'){
   			$this->data ['ssn'] = $client_info ['ssn'];
   		}else{
   			$this->data ['ssn'] = 'N/A';
   		}
   		
   		if($client_info ['emp_extid']!=''){
   			$this->data ['booking_id'] = $client_info ['emp_extid'];
   		}else{
   			$this->data ['booking_id'] = 'N/A';
   		}
   		
   		if($client_info ['date_added']!=''){
   			$this->data ['date_added'] = date('d-m-Y',strtotime($client_info ['date_added']));
   		}else{
   			$this->data ['date_added'] = 'N/A';
   		}*/
   		
   		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/dashboard3.php';
   		$this->children = array (
   			'common/headerclient',
   			'common/footerclient' 
   		);
   		
   		$this->response->setOutput ( $this->render () );
   	}

	public function getProfileDetails() { 
		$url2='';
		$url3 = '';
		
		if (! $this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->load->model ( 'facilities/facilities' );
		
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		$unique_id = $facility ['customer_key'];
		$facility_id = $this->customer->getId ();
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$activecustomer_id = $customer_info['activecustomer_id'];
		$this->data ['reload_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/dashboard', '', 'SSL' ) );
		$this->document->setTitle ( 'Mini Dashboard' );
		
		if ($this->request->post ['tags_id'] != "" && $this->request->post ['tags_id'] != null) {
				
			$tags_id = $this->request->post ['tags_id'];
			
			$url .= '&tags_id=' . $this->request->post ['tags_id'];
			
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		}
			
		$tag_data = array ();
		$tag_data ['tags_id'] = $tags_id;
	
		$url2 .= '&tags_id=' . trim($tags_id);
		
		$url2 .= '&facilities_id=' . $facility_id;
		
		$url2 .= '&min_dashboard=1';
		
		$this->data ['createtask_url'] = $this->url->link ( 'notes/createtask', ''.$url2, 'SSL' );
		
		$this->data ['tag_forms'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagforms', '' . $url2, 'SSL' ) );
		
		$this->data ['add_client_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', ''.$url2, 'SSL' ) );
		
		$this->load->model('setting/tags');
		
		$client_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
		if(COUNT($client_info)==0) { 
			echo '<center>
			<div class="description-block">
			<h6 class="description-header message">Invalid Tag Id</h6></div></center>';
			die;
		}
		$get_clientimg = $this->model_setting_tags->getImage ( $client_info ['tags_id'] );
			
		if ($get_clientimg ['enroll_image'] != null && $get_clientimg ['enroll_image'] != "") {
			$this->data ['upload_file_thumb_1'] = $get_clientimg ['enroll_image'];
		} else {
			$this->data ['upload_file_thumb_1'] = '';
		}
		
		//if($client_info ["show_client_image"]!=''){
		$this->data ['show_client_image'] = $client_info ['show_client_image'];
		//}
		
		//echo '<pre>'; print_r($client_info); echo '</pre>';
		
		if($client_info ['emp_first_name']!=''){
			$this->data ['client_first_name'] = ucfirst(strtolower($client_info ['emp_first_name']));
		}else{
			$this->data ['client_first_name'] = 'N/A';
		}
		
		if($client_info ['emp_last_name']!=''){
			$this->data ['client_last_name'] = ucfirst(strtolower($client_info ['emp_last_name']));
		}else{
			$this->data ['client_last_name'] = 'N/A';
		}
		
		if($client_info ['ssn']!='' && $client_info ['ssn']!='undefined'){
			$this->data ['ssn'] = $client_info ['ssn'];
		}else{
			$this->data ['ssn'] = 'N/A';
		}
		
		if($client_info ['emp_extid']!=''){
			$this->data ['booking_id'] = $client_info ['emp_extid'];
		} else { 
			$this->data ['booking_id'] = 'N/A';
		}
		
		if($client_info ['date_added']!='') { 
			$this->data ['date_added'] = date('m-d-Y',strtotime($client_info ['date_added']));
		} else { 
			$this->data ['date_added'] = 'N/A';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/resident/mini-dashboard/md-profile-block.php';
		
		$this->response->setOutput ( $this->render () );
	}
   	
   	public function casecategorylist(){
   		
   		$this->load->model ( 'facilities/facilities' );
   		
   		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
   		
   		$unique_id = $facility ['customer_key'];
   		$this->load->model ( 'customer/customer' );
   		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
   		$activecustomer_id = $customer_info['activecustomer_id'];
   		
   		$this->load->model('case/category');
   		$Categories = $this->model_case_category->getCasecategorybyuserid($activecustomer_id);
   		$this->load->model('setting/keywords');	
   		$this->load->model('form/form');	
   		$this->load->model('task/tasktype');	
   		$caseCategories = array();
   		$keywd = array();	
   		$datatask = array();
   		foreach($Categories as $category){
   			
   			$cases1 = array();
   			$this->load->model('notes/notes');
   			$cases = $this->model_notes_notes->getcases($category['case_category_id']);
   			
   			//echo '<pre>'; print_r($cases); echo '</pre>'; die;
   			
   			foreach($cases as $case){
   				//echo '<br>'.$case['tasks'].'-'.$category['case_category_id'];
   				
   				
   				$datatask = array(
   					'tasks_ids' => $case['tasks'],
   					'tags_id' => $this->request->post['tags_id'], 
					'from' => date('Y-m-d', strtotime($this->request->post['from'])),
					'to' => date('Y-m-d', strtotime($this->request->post['to'])),		
   				);
   				
   				$casetasks = $this->model_task_tasktype->gettasktype2($datatask);
   				$datakey = array(
   					'keyword_ids' => $case['keywords'],
   					'tags_id' => $this->request->post['tags_id'], 	
					'from' => date('Y-m-d', strtotime($this->request->post['from'])),
					'to' => date('Y-m-d', strtotime($this->request->post['to'])),	
   				);
   				
   				$casekeys = $this->model_setting_keywords->getkeywords2($datakey);
   				
   				$fdata = array(
   					'forms_ids' => $case['forms'],
   					'tags_id' => $this->request->post['tags_id'], 	
					'from' => date('Y-m-d', strtotime($this->request->post['from'])),
					'to' => date('Y-m-d', strtotime($this->request->post['to'])),	
   				);
   				
   				$caseforms = $this->model_form_form->getforms2($fdata);
   			
   				//$href = $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id']. $url2, 'SSL');
   			
   				//$href = $this->url->link('case/clients/detail', '' . '&case_id=' . $category['case_id']. $url2, 'SSL');
   				//$cat = $this->model_case_category->getCasecategory($category['case_category_id']); 
   				
   				$cases1[] = array(
   				  'case_id' => $case['case_id'],
   				  'name' => $case['name'],
   				  'casekeys' => $casekeys,
   				  'caseforms' => $caseforms,
   				  'casetasks' => $casetasks,
   				  'href' => $this->url->link('case/clients/detail', '' . '&case_id=' . $case['case_id']. $url2, 'SSL'),
   				);
   			}
   			
   			$caseCategories[] = array(
   			  'case_category_id' => $category['case_category_id'],
   			  'name' => $category['name'],
   			  'cases1' => $cases1,
   			  'cat_href' => $this->url->link('case/clients/detail', '' . '&case_category_id=' . $category['case_category_id']. $url2, 'SSL'),
   			);
   		}
   		
   		$html='';
   		
   		//echo '<pre>rrr'; print_r($caseCategories); echo '</pre>'; die;
   		
   		if($caseCategories) { 
			   $levelOneFlag = true;
   			//$html= '';
   
   				foreach($caseCategories AS $cat_row) { 
   
					if($cat_row['cases1'][0]['casekeys']!=NULL || $cat_row['cases1'][0]['caseforms']!=NULL || $cat_row['cases1'][0]['casetasks']!=NULL) { 
   					$html .='<li class="nav-item has-treeview '.($levelOneFlag ? 'menu-open' : '').'">
   						<a href="#" class="nav-link">
   						<i class="nav-icon fa fa-briefcase"></i>
   						<p class="case_cat">'.$cat_row['name'].'<i class="fas fa-angle-left right"></i></p></a><ul class="nav nav-treeview secondlabel">';
     
   					foreach($cat_row['cases1'] AS $sub_cat) { 
						   
						if($sub_cat['casekeys']!=NULL || $sub_cat['caseforms']!=NULL || $sub_cat['casetasks']!=NULL) { 
   					
							$html .='<li class="nav-item has-treeview '.($levelOneFlag ? 'menu-open' : '').'">
							<a href="#" class="nav-link">
							<i class="fas fa-dot-circle left"></i>
							<p>'.$sub_cat['name'].'<i class="right fas fa-angle-left"></i>
							</p>
							</a>
							<ul class="nav nav-treeview thirdlabel">';
						
							$i=1;
							foreach($sub_cat['casekeys'] AS $sub_sub_cat){
								if($i==1){
									$html .='<h5 class="thirdlabelheading"><i class="far fa-dot-circle left"></i> Keyword</h5>';
								}
		
								$html.='<li class="nav-item classificationFilter" data-type="keyword" data-for="'.$sub_sub_cat['keyword_name'].'">
								<a class="nav-link">
									<i class="far fa-dot-circle nav-icon" style="width: 1rem;margin-left: 20px;"></i> <p>'.substr(str_replace('|','',ucwords(strtolower($sub_sub_cat['keyword_name']))),0,23); 
						
								if(strlen($sub_sub_cat['keyword_name'])>23){ $html.='...';}
						
								$html.='</p></a></li>';
		
								$i++; 
							} 
		
		
							$i=1;
							foreach($sub_cat['caseforms'] AS $sub_sub_cat){
								if($i==1) { 
									$html.='<h5 class="thirdlabelheading"><i class="far fa-dot-circle left"></i> Form</h5>';
								}
			
								$html.='<li class="nav-item classificationFilter" data-type="form" data-for="'.$sub_sub_cat['forms_id'].'">
								<a class="nav-link">
								<i class="far fa-dot-circle nav-icon" style="width: 1rem;margin-left: 20px;"></i>
								<p>'.substr(str_replace('|','',ucwords(strtolower($sub_sub_cat['form_name']))),0,23);
									if(strlen($sub_sub_cat['form_name'])>23){ $html.='...';}
								
								$html.='</p>
								</a>
								</li>';
		
								$i++; 
							}
		
							$i=1;
							foreach($sub_cat['casetasks'] AS $sub_sub_cat) { 
								if($i==1) { 
								$html.='<h5 class="thirdlabelheading"><i class="far fa-dot-circle left"></i> Task</h5>';
								}
							
							$html.='<li class="nav-item classificationFilter" data-type="task" data-for="'.$sub_sub_cat['task_id'].'">
								<a class="nav-link">
								<i class="far fa-dot-circle nav-icon" style="width: 1rem;margin-left: 20px;"></i>
								<p>'.substr(str_replace('|','',ucwords(strtolower($sub_sub_cat['tasktype_name']))),0,23);
								
								if(strlen($sub_sub_cat['tasktype_name'])>23){ $html.='...';}
								
								$html.='</p>
								</a>
							</li>';
								$i++; 
							}
						
							$html.='</ul></li>';
						}
   					}
				
   					$html.='</ul></li>';
					$levelOneFlag = false;
				}
   
   				}
   				//$html.='';
   		}
		if(empty($html)) {
			echo '<center>
			<div class="description-block">
			<h6 class="description-header message">No Data</h6></div></center>';
		} else { 
			echo $html;
		}
   		
   	}
   		
   	public function autocomplete (){
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('setting/tags');
			$this->load->model('resident/resident');
			$this->load->model('form/form');
			if ($this->request->get['allclients'] != '1') {
			//$discharge = '1';
			//$all_record = '1';
			}

		   if ($this->request->get['allclients'] == '1') {
			   $is_master = '1';
		   }else if ($this->request->get['allclients'] == '0') {
			$is_master = '1';
			$discharge = '1';
		}else{
			$discharge = '1';
			$is_master = '1';
		}
               
               if ($this->request->get['wait_list'] == '1') {
                   $wait_list = '1';
               }
               
               if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
                   $facilities_id = $this->request->get['facilities_id'];
               } else {
   				
   				$this->load->model('facilities/facilities');
   				$resulsst =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
   				
   				if($resulsst['is_master_facility'] == '1'){
   					if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
   					$facilities_id  = $this->session->data['search_facilities_id']; 
   					$facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
   					$this->load->model('setting/timezone');
   					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
   					$timezone_name = $timezone_info['timezone_value'];
   					}else{
   						$facilities_id = $this->customer->getId(); 
   						$timezone_name = $this->customer->isTimezone();
   					}
   					 
   				}else{
   					$facilities_id = $this->customer->getId(); 
   					$timezone_name = $this->customer->isTimezone();
   				}
                   //$facilities_id = $this->customer->getId();
               }
               
               $filter_name = explode(':', $this->request->get['filter_name']);
   			
   			
               $data = array(
                       'emp_tag_id_all' => trim($filter_name[0]),
                       'facilities_id' => $facilities_id,
                       'status' => '1',
                       'discharge' => $discharge,
                       'all_record' => $all_record,
                       'wait_list' => $wait_list,
                       'is_master' => $is_master,
                       'sort' => 'emp_tag_id',
                       'order' => 'ASC',
                       'start' => 0,
                       'limit' => CONFIG_LIMIT
               );
   			
   			//var_dump( $data);
               $this->load->model ( 'api/permision' );
               $results = $this->model_setting_tags->getTags($data);
               
               foreach ($results as $result) {
                   
                   if ($result['date_of_screening'] != "0000-00-00") {
                       $date_of_screening = date('m-d-Y', strtotime($result['date_of_screening']));
                   } else {
                       $date_of_screening = date('m-d-Y');
                   }
                   if ($result['dob'] != "0000-00-00") {
                       $dob = date('m-d-Y', strtotime($result['dob']));
                   } else {
                       $dob = '';
                   }
                   if ($result['dob'] != "0000-00-00") {
                       $dobm = date('m', strtotime($result['dob']));
                   } else {
                       $dobm = '';
                   }
                   if ($result['dob'] != "0000-00-00") {
                       $dobd = date('d', strtotime($result['dob']));
                   } else {
                       $dobd = '';
                   }
                   if ($result['dob'] != "0000-00-00") {
                       $doby = date('Y', strtotime($result['dob']));
                   } else {
                       $doby = '';
                   }
                   
                   /*if ($result['gender'] == '1') {
                       $gender = '33';
                   }
                   if ($result['gender'] == '2') {
                       $gender = '34';
                   }*/
                   
                   if ($result['upload_file']) {
                       $image_url1 = $result['upload_file'];
                       
                       // $image_url = file_get_contents($upload_file);
                       // $image_url1 =
                       // 'data:image/jpg;base64,'.base64_encode($image_url);
                   } else {
                       $upload_file = '';
                       $image_url1 = '';
                   }
                   
                   $tagmedication = $this->model_setting_tags->getTagsMedications($result['tags_id']);
                   
                   $alltagmeddetails = array();
                   $tagmeddetails = $this->model_resident_resident->gettagModule($result['tags_id'],'','');
                   
                   $tags_form_info = $this->model_form_form->gettagsforma($result['tags_id']);
                   // var_dump($tags_form_info);
                   
                   $url2 = "";
                   $tags_form_url = "";
                   if ($tags_form_info != null && $tags_form_info != "") {
                       $url2 .= '&forms_design_id=' . $tags_form_info['custom_form_type'];
                       $url2 .= '&forms_id=' . $tags_form_info['forms_id'];
                       $url2 .= '&notes_id=' . $tags_form_info['notes_id'];
                       $url2 .= '&facilities_id=' . $tags_form_info['facilities_id'];
                       if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
                           $url2 .= '&task_id=' . $this->request->get['task_id'];
                       }
                       
                       if ($this->request->get['serviceforms_id'] == '1') {
                           $action2 = str_replace('&amp;', '&', $this->url->link('services/form', '' . $url2, 'SSL'));
                       } else {
                           $action2 = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
                       }
                       
                       $tags_form_url = $action2;
                   }
                   
   				$clientinfo = $this->model_api_permision->getclientinfo ( $result['facilities_id'], $result );
   				
                   $json[] = array(
                           'tags_id' => $result['tags_id'],
                           //'name' => $result['emp_tag_id'] . ': ' . $result['emp_first_name'] . ' ' . $result['emp_last_name'],
                           'name' => $clientinfo ['name'],
                           //'emp_tag_id' => $result['emp_tag_id'] . ': ' . $result['emp_first_name'] . ' ' . $result['emp_last_name'],
                           'emp_tag_id' => $clientinfo ['name'],
                           'emp_tag_id_1' => $result['emp_tag_id'] . ':' . $result['emp_first_name'],
                           'emp_first_name' => $result['emp_first_name'],
                           'emp_last_name' => $result['emp_last_name'],
                           'ccn' => $result['ccn'],
                           'emergency_contact' => $result['emergency_contact'],
                           'dob' => $dob,
                           'month' => $dobm,
                           'date' => $dobd,
                           'year' => $doby,
                           'age' => $result['age'],
                           'gender' => $result['customlistvalues_id'],
                           'location_address' => $result['location_address'],
                           'address_street2' => $result['address_street2'],
                           'person_screening' => $result['person_screening'],
                           'date_of_screening' => $date_of_screening,
                           'ssn' => $result['ssn'],
                           'state' => $result['state'],
                           'city' => $result['city'],
                           'zipcode' => $result['zipcode'],
                           'emp_extid' => $result['emp_extid'],
                           'date_added' => date('m-d-Y', strtotime($result['date_added'])),
                           'upload_file' => $upload_file,
                           'image_url1' => $image_url1,
   						
                           'country' => 'US',
                           'tagmedication' => unserialize($tagmedication['medication_fields']),
                           'tagmeddetails' => $tagmeddetails['new_module'],
                           'tags_form_url' => $tags_form_url
                   );
               }
               
               /*
                * $data = array(
                * 'client_name' => $this->request->get['filter_name'],
                * 'status' => '1',
                * 'sort' => 'client_name',
                * 'order' => 'ASC',
                * 'start' => 0,
                * 'limit' => CONFIG_LIMIT
                * );
                *
                * $this->load->model('createtask/createtask');
                *
                * $cresults =
                * $this->model_createtask_createtask->getclients($data);
                *
                * foreach ($cresults as $cresult) {
                * $json[] = array(
                * 'tags_id' => $cresult['client_id'],
                * 'emp_tag_id' => $cresult['client_name'],
                * 'tags_display' => $cresult['client_name'],
                * );
                * }
                */
           }
           
           /*
            * $sort_order = array();
            *
            * foreach ($json as $key => $value) {
            * $sort_order[$key] = $value['emp_tag_id'];
            * }
            *
            * array_multisort($sort_order, SORT_ASC, $json);
            */
           $this->response->setOutput(json_encode($json));
       }
   
   	public function searchTags() {
   		$json = array ();
   		
   		$this->load->model ( 'facilities/online' );
   		$datafa = array ();
   		$datafa ['username'] = $this->session->data ['webuser_id'];
   		$datafa ['activationkey'] = $this->session->data ['activationkey'];
   		$datafa ['facilities_id'] = $this->customer->getId ();
   		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
   		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
   		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
   		
   		// if($this->request->get['emp_tag_id'] != null &&
   		// $this->request->get['emp_tag_id'] != "") {
   		$this->load->model ( 'notes/tags' );
   		$this->load->model ( 'setting/tags' );
   		
   		//$this->request->get ['emp_tag_id'] ='';
   		
   		if (isset ( $this->request->get ['emp_tag_id'] )) {
   			$emp_tag_id = $this->request->get ['emp_tag_id'];
   		} else {
   			$emp_tag_id = '';
   		}
   
   		if (isset ( $this->request->get ['q'] )) {
   			$q = $this->request->get ['q'];
   		} else {
   			$q = '';
   		}
   		
   		if (isset ( $this->request->get ['limit'] )) {
   			$limit = $this->request->get ['limit'];
   		} else {
   			$limit = CONFIG_LIMIT;
   		}
   		
   		$filter_name = explode ( ':', $emp_tag_id );
   		
   		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
   			$facilities_id = $this->request->get ['facilities_id'];
   		} else {
   			$this->load->model ( 'facilities/facilities' );
   			$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
   			if ($facilities_info ['is_master_facility'] == '1') {
   				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
   					$facilities_id = $this->session->data ['search_facilities_id'];
   				} else {
   					$facilities_id = $this->customer->getId ();
   				}
   			} else {
   				$facilities_id = $this->customer->getId ();
   			}
   			// $facilities_id = $this->customer->getId();
   		}
   		
   		$data = array (
   			'q' => $q,
   			'emp_tag_id_all' => trim ( $filter_name [0] ),
   			'facilities_id' => $facilities_id,
   			'status' => 1,
   			'discharge' => 1,
   			'all_record' => 1,
   			'is_master' => 1,
   			'sort' => 'emp_last_name',
   			'order' => 'ASC',
   			'start' => 0,
   			'limit' => $limit
   		);
   		
   		$tags = $this->model_setting_tags->getTags ( $data );
   		
   		//echo '<pre>'; print_r($tags); echo '</pre>';
   		
   		
   		$this->load->model ( 'setting/locations' );
   		$this->load->model ( 'resident/resident' );
   		$this->load->model ( 'notes/clientstatus' );
   		$this->load->model ( 'form/form' );
   		
   		foreach ( $tags as $result ) {
   			
   			if ($result ['date_of_screening'] != "0000-00-00") {
   				$date_of_screening = date ( 'm-d-Y', strtotime ( $result ['date_of_screening'] ) );
   			} else {
   				$date_of_screening = date ( 'm-d-Y' );
   			}
   			if ($result ['dob'] != "0000-00-00") {
   				$dob = date ( 'm-d-Y', strtotime ( $result ['dob'] ) );
   			} else {
   				$dob = '';
   			}
   			
   			if ($result ['dob'] != "0000-00-00") {
   				$dobm = date ( 'm', strtotime ( $result ['dob'] ) );
   			} else {
   				$dobm = '';
   			}
   			if ($result ['dob'] != "0000-00-00") {
   				$dobd = date ( 'd', strtotime ( $result ['dob'] ) );
   			} else {
   				$dobd = '';
   			}
   			if ($result ['dob'] != "0000-00-00") {
   				$doby = date ( 'Y', strtotime ( $result ['dob'] ) );
   			} else {
   				$doby = '';
   			}
   			
   			/*
   			 * if ($result['gender'] == '1') {
   			 * $gender = '33';
   			 * }
   			 * if ($result['gender'] == '2') {
   			 * $gender = '34';
   			 * }
   			 */
   			
   			$get_img = $this->model_setting_tags->getImage ( $result ['tags_id'] );
   			
   			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
   				$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
   			} else {
   				$upload_file_thumb_1 = $get_img ['enroll_image'];
   			}
   			
   			if ($result ['ssn']) {
   				$ssn = $result ['ssn'] . ' ';
   			} else {
   				$ssn = '';
   			}
   			if ($result ['emp_extid']) {
   				$emp_extid = $result ['emp_extid'] . ' ';
   			} else {
   				$emp_extid = '';
   			}
   			
   			$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $result ['tags_id'] );
   			
   			if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
   				$status = $tagstatusinfo ['status'];
   				
   				$classification_value = $this->model_resident_resident->getClassificationValue ( $tagstatusinfo ['status'] );
   				$classification_name = $classification_value ['classification_name'];
   			} else {
   				$classification_name = '';
   			}
   			
   			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $result ['role_call'] );
   			if ($clientstatus_info ['name'] != null && $clientstatus_info ['name'] != "") {
   				$role_callname = $clientstatus_info ['name'];
   				$color_code = $clientstatus_info ['color_code'];
   				$role_type = $clientstatus_info ['type'];
   			}
   			if ($result ['room'] != null && $result ['room'] != "") {
   				$rresults = $this->model_setting_locations->getlocation ( $result ['room'] );
   				$location_name = $rresults ['location_name'];
   			} else {
   				$location_name = '';
   			}
   			
   			if ($result ['date_added'] != "0000-00-00") {
   				$date_added = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
   			}
   			
   			$datsa = array();
   			$datsa['forms_design_id'] = $this->request->get ['forms_design_id'];
   			$datsa['facilities_id'] = $result ['facilities_id'];
   			$datsa['tags_id'] = $result ['tags_id'];
   			$cseinfo = $this->model_form_form->getFormscase ( $datsa );
   			
   			
   			$json [] = array (
   					'name' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
   					'fullname' => $result ['emp_last_name'] . ' ' . $result ['emp_first_name'],
   					'tags_id' => $result ['tags_id'],
   					'case_number' => $cseinfo,
   					'date_added' => $date_added,
   					'classification_name' => $classification_name,
   					'role_call' => $role_callname,
   					'location_name' => $location_name,
   					'emp_tag_id2' => $result ['emp_tag_id'] . ': ' . $result ['emp_first_name'],
   					'emp_tag_id' => $result ['emp_tag_id'],
   					'emp_first_name' => $result ['emp_first_name'],
   					'emp_middle_name' => $result ['emp_middle_name'],
   					'emp_last_name' => $result ['emp_last_name'],
   					'location_address' => $result ['location_address'],
   					'discharge' => $result ['discharge'],
   					'ccn' => $result ['ccn'],
   					'age' => $result ['age'],
   					'race' => $result ['race'],
   					'dob' => $dob,
   					'month' => $dobm,
   					'date' => $dobd,
   					'year' => $doby,
   					'medication' => $result ['medication'],
   					// 'gender'=> $result['gender'],
   					'gender' => $result ['customlistvalues_id'],
   					'person_screening' => $result ['person_screening'],
   					'date_of_screening' => $date_of_screening,
   					'ssn' => $result ['ssn'],
   					'state' => $result ['state'],
   					'city' => $result ['city'],
   					'zipcode' => $result ['zipcode'],
   					'room' => $result ['room'],
   					'restriction_notes' => $result ['restriction_notes'],
   					'prescription' => $result ['prescription'],
   					'constant_sight' => $result ['constant_sight'],
   					'alert_info' => $result ['alert_info'],
   					'med_mental_health' => $result ['med_mental_health'],
   					'tagstatus' => $result ['tagstatus'],
   					'emp_extid' => $result ['emp_extid'],
   					'stickynote' => $result ['stickynote'],
   					'referred_facility' => $result ['referred_facility'],
   					'emergency_contact' => $result ['emergency_contact'],
   					'upload_file' => $upload_file_thumb_1,
   					'image_url1' => $upload_file_thumb_1,
   					'screening_update_url' => $action211 
   			);
   		}
   		// }
   		
   		//echo '<pre>'; print_r($json); echo '</pre>';
   		
   		$this->response->setOutput ( json_encode ( $json ) );
   	}
   	
   	public function headertasklist() {
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		
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
		
		date_default_timezone_set ( $timezone_name );
		
		if (isset ( $this->request->get ['searchdate'] )) {
			$res = explode ( "-", $this->request->get ['searchdate'] );
			$createdate1 = $res [1] . "-" . $res [0] . "-" . $res [2];
			
			$this->data ['note_date'] = date ( 'D F j, Y', strtotime ( $createdate1 ) );
			$currentdate = $createdate1;
			
			$this->data ['searchdate'] = $this->request->get ['searchdate'];
		} else {
			$this->data ['note_date'] = date ( 'D F j, Y' ); // date('m-d-Y');
			
			$currentdate = date ( 'd-m-Y' );
			$this->data ['searchdate'] = date ( 'm-d-Y' );
		}
		
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$updatestriketask_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=1', '' . $url2, 'SSL' ) );
			
			$inserttask_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=2', '' . $url2, 'SSL' ) );
			
		} else {
			
			$updatestriketask_url = $this->url->link ( 'notes/createtask/updateStriketask', '' . $url2, 'SSL' );
			$inserttask_url = $this->url->link ( 'notes/createtask/inserttask', '' . $url2, 'SSL' );
		}
		
		
		
		$this->data ['deleteTime'] = $deleteTime;
		
		$this->load->model ( 'createtask/createtask' );
		$top = '1';
		$tags_id = $this->request->post ['tags_id'];
		$tasktype = $this->request->post ['tasktype'];
		
		$listtasks = $this->model_createtask_createtask->getTasklistByTaskType ( $facilities_id, $currentdate, $top, $tags_id ,$tasktype );
		
		//echo '<pre>'; print_r($listtasks); echo '</pre>';

		$approval_url = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
		
		$html='';
		
		$html ='<script>
			$(".removeData").colorbox({iframe:true, width:"70%", height:"70%", opacity:0.9, onClosed: function () { if(currentTab=="task_action") {$(".task_action").trigger("click");} }});$(".saveData").colorbox({iframe:true, width:"70%", height:"70%", opacity:0.9, onClosed: function () { if(currentTab=="task_action") {$(".task_action").trigger("click");} }});</script><div class="tag_form_table2">
					
				<div class="col-md-2">Time</div>
				
				<div class="col-md-9">Description</div>
				
				<div class="col-md-1">Action</div></div>';
				
		
		$this->load->model('api/permision');
		$timeinfo = $this->model_api_permision->getcustomerdatetime($this->customer->getId ());
		
		if($listtasks!=''){
			foreach ( $listtasks as $list ) {
				
				$task_time = date ( $timeinfo['time_format'], strtotime ( $list ['task_time'] ) );
				$tags_id = $list ['emp_tag_id'];
				$task_id = $list ['id'];
				$diffHours='';
				$diffHours2='';
				$minutes='';
				
				$full_description =$list['description'];
				$description = substr($full_description,0,18);
				$description2 = substr($full_description,18);
				$task_time = date('H:i:s',strtotime($task_time));
				
				$start_date = new DateTime(date("H:i:s"));
				$since_start = $start_date->diff(new DateTime($task_time));
				
				//echo '<pre>'; print_r($since_start);  echo '<pre>';
				//echo $since_start->days.' days total<br>';
				//echo $since_start->y.' years<br>';
				//echo $since_start->m.' months<br>';
				//echo $since_start->d.' days<br>';
				
				if($since_start->invert==0){
					$hours = $since_start->h.'h';
					$minutes = $since_start->i.'m';
					$time = $hours.' '.$minutes;
				}else{
					$time = '0 mins';
				}
				
				
				$time = date('H:i A',strtotime($task_time));
				
				if($minutes>0){
					$diffHours2=$minutes;
				}else{
					$diffHours2=0;
				}
			  
				$html .='<div class="row drow">
						<div class="col-md-2"><small style="font-size: 100%;" class="badge badge-info"><i class="far fa-clock"></i> '.$time.'</small></div>
						<div class="col-md-9">'.$full_description.'</div>
						<div class="col-md-1">
						
						
						
						<a class="removeData" href="'.$updatestriketask_url.'&tags_id='.$tags_id.'&task_id='.$task_id.'"><i class="fa fa-times-circle fa-2x text-danger" aria-hidden="true"></i></a> 
						&nbsp;&nbsp;';
				
				if($list['enable_requires_approval'] == "2") { 
					$html .='<a class="saveData" href="'.$approval_url.'&tags_id='.$tags_id.'&task_id='.$task_id.'"><i class="fas fa-check-circle fa-2x text-success"></i></a>';
				} else { 
					$html .='<a class="saveData" href="'.$inserttask_url.'&tags_id='.$tags_id.'&task_id='.$task_id.'"><i class="fas fa-check-circle fa-2x text-success"></i></a>';
				}
						
				$html .= '</div></div>';
			}
		
		}else{
			$html='<div class="row drow"><div class="col-md-12"><h6 class="message">No Data</h6></div></div>';
		}
		
		
		$plus_url = $this->url->link ( 'notes/createtask', 'tags_id='.$tags_id, 'SSL' );
		
		$data['html']= $html;
		
		$data['form_name'] = strtoupper($tasktype);
		
		$data['plus_url'] = $plus_url;

		$this->response->setOutput ( json_encode ( $data ) );
			
		//echo $html;
		
	}

	public function tasktypelist() { 
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info['timezone_value'];
		
		$timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        if (isset($this->request->get['searchdate'])) { 
            $res = explode("-", $this->request->get['searchdate']);
            $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
            $currentdate = $createdate1;
            
            $this->data['searchdate'] = $this->request->get['searchdate'];
        } else { 
            $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
            
            $currentdate = date('d-m-Y');
            $this->data['searchdate'] = date('m-d-Y');
        }
		
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
			$updatestriketask_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=1', '' . $url2, 'SSL' ) );
			
			$inserttask_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&savetask=2', '' . $url2, 'SSL' ) );
			
		} else {
			
			$updatestriketask_url = $this->url->link ( 'notes/createtask/updateStriketask', '' . $url2, 'SSL' );
			$inserttask_url = $this->url->link ( 'notes/createtask/inserttask', '' . $url2, 'SSL' );
		}
		
		
		$this->load->model('createtask/createtask');
        $top = '1';
		$tags_id = trim($this->request->post['tags_id']);
		$facilities_id = $this->customer->getId ();
		
		$approval_url = $this->url->link ( 'notes/createtask/approvalurl', '' . $url2, 'SSL' );
		
		$html='';
		
		$html ='<script>
			$(".removeData").colorbox({iframe:true, width:"70%", height:"70%", opacity:0.9, onClosed: function () { if(currentTab=="task_action") {$(".task_action").trigger("click");} }});$(".saveData").colorbox({iframe:true, width:"70%", height:"70%", opacity:0.9, onClosed: function () { if(currentTab=="task_action") {$(".task_action").trigger("click");} }});</script><div class="tag_form_table2">
					
				<div class="col-md-2">Time</div>
				
				<div class="col-md-9">Description</div>
				
				<div class="col-md-1">Action</div></div>';
		
	   
	   $listtasks = $this->model_createtask_createtask->getTaskTypeListByTagId($facilities_id, $currentdate, $top, $tags_id);
		
		$navigation='';
		
		if($listtasks){
			$i=1;
			foreach ( $listtasks as $task ) {
				$tasktype = trim($task['tasktype']);
				$tasktyperow = $this->model_createtask_createtask->gettasktyperowByName($tasktype, $facilities_id);
				
				$tasktype_id  = $tasktyperow['task_id'];
				
				
				$taskTotal = $this->model_createtask_createtask->getCountTasklist ( $facilities_id, $currentdate, $top='', $facilitytimezone='', $tags_id, $tasktype_id );
				
				$navigation .='<li  data-tasktype="'.$tasktype.'" class="tasktype"><span class="text" style="width: 71%;text-align: justify;">'.ucwords($tasktype).' ('.$taskTotal.')</span></li>';
				$i++;
			}
			
		}else{ 
			$navigation= '<center>
			<div class="description-block">
			<h6 class="description-header message">No Data</h6></div></center>';
		}
		
		/*--------------------------------------------------------------------------------------------*/
		
		$this->load->model ( 'createtask/createtask' );
		$top = '1';
		$tags_id = $this->request->post ['tags_id'];
		$tasktype = $this->request->post ['tasktype'];
		
		$listtaskdata = $this->model_createtask_createtask->getTasklistByTaskType ( $facilities_id, $currentdate, $top, $tags_id ,$tasktype='' );
		
		//echo '<pre>'; print_r($listtasks); echo '</pre>';
		
		$this->load->model('api/permision');
		$timeinfo = $this->model_api_permision->getcustomerdatetime($this->customer->getId ());
		
		if(!empty($listtaskdata)){
			foreach ( $listtaskdata as $list ) {
				
				$task_time = date ( $timeinfo['time_format'], strtotime ( $list ['task_time'] ) );
				//$tags_id = $list ['emp_tag_id'];
				$task_id = $list ['id'];
				$diffHours='';
				$diffHours2='';
				$minutes='';
				
				$full_description =$list['description'];
				$description = substr($full_description,0,18);
				$description2 = substr($full_description,18);
				$task_time = date('H:i:s',strtotime($task_time));
				
				$start_date = new DateTime(date("H:i:s"));
				$since_start = $start_date->diff(new DateTime($task_time));
				
				//echo '<pre>'; print_r($since_start);  echo '<pre>';
				//echo $since_start->days.' days total<br>';
				//echo $since_start->y.' years<br>';
				//echo $since_start->m.' months<br>';
				//echo $since_start->d.' days<br>';
				
				if($since_start->invert==0){
					$hours = $since_start->h.'h';
					$minutes = $since_start->i.'m';
					$time = $hours.' '.$minutes;
				}else{
					$time = '0 mins';
				}
				
				$time = date('H:i A',strtotime($task_time));
				
				
				if($minutes>0){
					$diffHours2=$minutes;
				}else{
					$diffHours2=0;
				}
			  
				$html .='<div class="row drow">
						<div class="col-md-2"><small style="font-size: 100%;" class="badge badge-info"><i class="far fa-clock"></i> '.$time.'</small></div>
						<div class="col-md-9">'.$full_description.'</div>
						<div class="col-md-1">
						
						
						
						<a class="removeData" href="'.$updatestriketask_url.'&tags_id='.$tags_id.'&task_id='.$task_id.'"><i class="fa fa-times-circle fa-2x text-danger" aria-hidden="true"></i></a> 
						&nbsp;&nbsp;';
				
				if($list['enable_requires_approval'] == "2") { 
					$html .='<a class="saveData" href="'.$approval_url.'&tags_id='.$tags_id.'&task_id='.$task_id.'"><i class="fas fa-check-circle fa-2x text-success"></i></a>';
				} else {
					$html .='<a class="saveData" href="'.$inserttask_url.'&tags_id='.$tags_id.'&task_id='.$task_id.'"><i class="fas fa-check-circle fa-2x text-success"></i></a>';
				}
				
				$html .='</div></div>';
			}
		
		}else{
			$html='<div class="row drow"><div class="col-md-12"><h6 class="message">No Data</h6></div></div>';
		}
		
		$plus_url = $this->url->link ( 'notes/createtask', 'tags_id='.$tags_id, 'SSL' );
		
		$response['html']= $html;
		
		$response['form_name'] = 'ALL TASK';
		
		$response['plus_url'] = $plus_url;

		$response['navigation'] = $navigation;
		
		
		//echo '<pre>'; print_r($response); echo '</pre>';
		
		
		$this->response->setOutput ( json_encode ( $response ) );
		
		
		//echo $html;  
	}
   	
   	public function form_list() {
		$this->load->model ( 'notes/notes' ); 
   		$this->load->model ( 'setting/tags' ); 
   		$this->load->model ( 'facilities/online' );
   		$datafa = array ();
   		$datafa ['username'] = $this->session->data ['webuser_id'];
   		$datafa ['activationkey'] = $this->session->data ['activationkey'];
   		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
   		$facilities_id = $this->request->get ['facilities_id'];
   		
   		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
   		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
   		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$this->data ['customers'] = array ();
		
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
			$this->data ['customerinfo'] = $customers;
		}
		
		
		
		
		if($customers['date_format'] != null && $customers['date_format'] != ""){
			$date_format = $customers['date_format'];
		}else{
			$date_format = $this->language->get ( 'date_format_short_2' );
		}
		
		if($customers['time_format'] != null && $customers['time_format'] != ""){
			$time_format = $customers['time_format'];
		}else{
			$time_format = 'h:i A';
		}
		
		$navigation='';
		$html='';
   		$flag=0;
   		if (!$this->customer->isLogged ()) { 
   			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
   		}
   		
   		if($this->request->get ['tags_id']) {
			
			$tags_id= $this->request->get ['tags_id'];
			
			$d12 = array ();
			$d12 ['tags_id'] = $tags_id;
			$d12 ['form_type'] = '5';
			$checkout_form_sign = $this->model_notes_notes->getInventoryNoteform ( $d12 );
			
			
			//echo '<pre>checkout_form_sign->'; print_r($checkout_form_sign); echo '</pre>';
			
			
			$d12 = array ();
			$d12 ['tags_id'] = $tags_id;
			$d12 ['form_type'] = '6';
			$checkin_form_sign = $this->model_notes_notes->getInventoryNoteform ( $d12 );
			
			//echo '<pre>checkin_form_sign->'; print_r($checkin_form_sign); echo '</pre>';
			
			
			$attdata = array (
				'sort' => $sort,
				'order' => $order,
				'tags_id' => $tags_id 
			);
			
			$aallattas = $this->model_setting_tags->gettagsattachmets ( $attdata );
			
   			//echo '<pre>checkin_form_sign->'; print_r($aallattas); echo '</pre>';
			
			$data = array (
   				'sort' => $sort,
   				'order' => $order,
   				'group' => '1',
   				'groupby' => '1',
   				'tags_id' => $tags_id
   			);
   			
			$this->load->model ( 'form/form' );
   			$aallforms = $this->model_form_form->gettagsforms ( $data );
			
   			//echo '<pre>'; print_r($aallforms); echo '</pre>';
			
			$html ='<script>$(".items li").on("click", function () { $("ul li").removeClass("selected"); $(this).attr("class", "selected");}); $(".tag_forms2").colorbox({iframe:true, width:"90%", height:"90%"});</script><div class="table-responsive">
					
				<table width="100%" class="current ">
				<thead>
				<tr class="tag_form_table">
					<th class="tag_form_left_td">Name</th>
					<th class="tag_form_left_td">Description</th>
					<th class="tag_form_right_td">Signature</th>
				</tr></thead>';
			
			

   			if(($aallforms) || ($checkout_form_sign) || ($checkin_form_sign) || ($aallattas)){
				
   				$i=1;
				
   				foreach ( $aallforms as $allform ) {
					
					/*
					
					// for all forms
					
					$data2 = array (
						'sort' => $sort,
						'order' => $order,
						'group' => '1',
						'tags_id' => $tags_id,
						'custom_form_type' => $allform ['custom_form_type'],
						'start' => ($page - 1) * $config_admin_limit,
						'limit' => $config_admin_limit 
					);
			
					//$form_total = $this->model_form_form->getTotalforms2 ( $datax );
					
					$allformslist = $this->model_form_form->gettagsforms ( $data2 );
					
					
					
					
				
				
					if($allformslist){
				
						$if = 0;
						$old = null;
						foreach($allformslist AS $tag){
						
							$resultsforms = $this->model_form_form->getArcheiveFormDatas ( $tag ['forms_id'] );
							$archivedforms = array ();
							foreach ( $resultsforms as $resultsform ) {
								$nnote = $this->model_notes_notes->getnotes ( $resultsform ['notes_id'] );
							
								$archivedforms [] = array (
										'forms_id' => $resultsform ['forms_id'],
										'form_name' => $resultsform ['incident_number'],
										'notes_type' => $nnote ['notes_type'],
										'notes_description' => $nnote ['notes_description'],
										'user_id' => $nnote ['user_id'],
										'signature' => $nnote ['signature'],
										'notes_pin' => $nnote ['notes_pin'],
										'form_date_added' => date ( $date_format, strtotime ( $nnote ['note_date'] ) ),
										'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
										'form_href' => $this->url->link ( 'form/form&is_archive=4', '' . '&forms_id=' . $resultsform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $resultsform ['notes_id'] . '&forms_design_id=' . $resultsform ['custom_form_type'] . '&forms_id=' . $resultsform ['forms_id'], 'SSL' ) 
								);
							}
						
						
							$form_info = $this->model_form_form->getFormdata ( $tag ['custom_form_type'] );
							$this->load->model ( 'notes/notes' );
							$note_info = $this->model_notes_notes->getNote ( $tag ['notes_id'] );
		
							if ($tag ['user_id'] != null && $tag ['user_id'] != "") {
								$user_id = $tag ['user_id'];
								$signature = $tag ['signature'];
								$notes_pin = $tag ['notes_pin'];
								$notes_type = $tag ['notes_type'];
								$notes_description = $note_info ['notes_description'];
								
								if ($tag ['form_date_added'] != null && $tag ['form_date_added'] != "0000-00-00 00:00:00") {
									$form_date_added = date ( $date_format, strtotime ( $tag ['form_date_added'] ) );
								} else {
									$form_date_added = '';
								}
							} else {
								
								$user_id = $note_info ['user_id'];
								$signature = $note_info ['signature'];
								$notes_pin = $note_info ['notes_pin'];
								$notes_type = $note_info ['notes_type'];
								$notes_description = $note_info ['notes_description'];
								
								if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
									$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
								} else {
									$form_date_added = '';
								}
							}
							
							if ($tag ['image_url'] != null && $tag ['image_url'] != "") {
								$mediainfo = $this->model_form_form->getformmediabyid ($tag ['notes_id'], $tag ['custom_form_type']);
								$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $mediainfo ['notes_media_id'] , 'SSL' );
								$form_name = $tag ['image_name'];
							} else {
								$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $tag ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $tag ['notes_id'] . '&forms_design_id=' . $tag ['custom_form_type'] . '&forms_id=' . $tag ['forms_id'], 'SSL' );
								$form_name = $tag ['incident_number'];
							}
						
						
							$this->data ['tagsforms'] [] = array (
								'forms_id' => $allform ['forms_id'],
								'image_url' => $allform ['image_url'],
								'form_name' => $form_name,
								'notes_type' => $notes_type,
								'notes_description' => $notes_description,
								'user_id' => $user_id,
								'signature' => $signature,
								'notes_pin' => $notes_pin,
								'form_date_added' => $form_date_added,
								'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
								'archivedforms' => $archivedforms,
								'form_href' => $hrurl 
							);
						
							if($checkout_form_signx){
							
								foreach ( $checkout_form_sign as $checkout_form ) {
							
									$checkout_url = $this->url->link ( 'notes/addInventory/CheckOutInventoryForm', '' . '&notes_id=' . $checkout_form ['notes_id'], 'SSL' );
									
									if ($checkout_form ['note_date'] != null && $checkout_form ['note_date'] != "0000-00-00 00:00:00") {
										$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $checkout_form ['note_date'] ) );
									} else {
										$form_date_added = '';
									}
									
									$this->data ['tagsforms'] [] = array (
											'forms_id' => '',
											'image_url' => 'checkout',
											'form_name' => 'Checkout Inventory',
											'notes_type' => $checkout_form ['notes_type'],
											'notes_description' => $checkout_form ['notes_description'],
											'user_id' => $checkout_form ['user_id'],
											'signature' => $checkout_form ['signature'],
											'notes_pin' => $checkout_form ['notes_pin'],
											'form_date_added' => $form_date_added,
											'date_added2' => date ( 'D F j, Y', strtotime ( $checkout_form ['date_added'] ) ),
											'archivedforms' => '',
											'form_href' => $checkout_url 
									);
									
									
								}
								
							}
						
						
						
							if($checkin_form_signx){
					
								foreach($checkin_form_sign as $checkin_form){		
									
									$checkin_url = $this->url->link ( 'notes/addInventory/CheckInInventoryForm', '' . '&notes_id=' . $checkin_form['notes_id'] , 'SSL' );

									if ($checkin_form ['note_date'] != null && $checkin_form ['note_date'] != "0000-00-00 00:00:00") {
										$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $checkin_form ['note_date'] ) );
									} else {
										$form_date_added = '';
									}
									
									
									$this->data ['tagsforms'] [] = array (
											'forms_id' => '',
											'image_url' => 'checkin',
											'form_name' => 'Checkin Inventory',
											'notes_type' => $checkin_form['notes_type'],
											'notes_description' => $checkin_form['notes_description'],
											'user_id' => $checkin_form['user_id'],
											'signature' => $checkin_form['signature'],
											'notes_pin' => $checkin_form['notes_pin'],
											'form_date_added' => $form_date_added,
											'date_added2' => date ( 'D F j, Y', strtotime ( $checkin_form ['date_added'] ) ),
											'archivedforms' => '',
											'form_href' => $checkin_url 
									);
								}
							}
						
						
						
						
							
							
							/*$date_added2 = date ( 'D F j, Y', strtotime ( $tag ['date_added'] ) );
							
							if ($if % 2 == 0) {
								$classf = "even";
							} else {
								$classf = 'odd';
							}
							
							if ($old != $date_added2) {
								$old = $date_added2;
								$html.='<tr class="clickable"><td colspan="3"><h3 style="text-align:left;" class="bgborder"><a style="color: #fa801b;">'.$old.'</a></h3></td></tr>';
							}
								
							$html.='<tr class="'.$classf.'"><td style="width: 20%; text-align: left;">';
							if($tag['image_url'] != null && $tag['image_url'] != null ){ 
							
								$html.='<a target="_blank" href="'.$hrurl.'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />'.$form_name.'</a>';
							}else{
							
								$html.='<a class="tag_forms2" href="'.$hrurl.'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />'.$form_name.'</a>';
							}
							
							if(!empty($archivedforms)){
								$html.='<a id="hide_archived'.$tag['forms_id'].'"><img src="sites/view/digitalnotebook/image/down-arrow.png" class=" dots" style="float:right"></a>';
							}
							
							$html.='</td> <td style="width: 50%; text-align:left;">'.$notes_description.'</td>
							
							<td style="width: 30%;">';
							
							if($tag['image_url'] != null && $tag['image_url'] != null ){ 
								$html.='<a target="_blank" href="'.$tag['form_href'].'">';
							}else{
								$html.='<a class="tag_forms2" href="'.$tag['form_href'].'">';
							}
										
							if($user_id != null && $user_id != "0"){
								$html.=$user_id;
								if($notes_type == "1"){
									$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								}else{
									if($notes_type != null && $notes_type != ""){
										$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									}else{
										if($signature != null && $signature != ""){
											$html.='<img src="'.$signature.'" width="98px" height="29px">';
										}
									}
								}
								
								if($form_date_added != "" && $form_date_added != ""){
									$html.='('.$form_date_added.')';
								}
							}	
										
							$html.='</a></td></tr>';
							
							
							//echo '<pre>AAA-'; print_r($archivedforms); echo '</pre>';
							
							foreach ($archivedforms as $tag2) {
								$html.='<tr class="showpanel1 show_archived'.$tag['forms_id'].'" style="background: #f1f1f1;display:none; ">
								<td style="width: 20%;    text-align: right;"><a class="tag_forms2" href="'.$tag2['form_href'].'"><img src="sites/view/digitalnotebook/image/archived2.jpg" width="35px" height="35px" alt="" style="margin-left: 4px;" />'.$tag2['form_name'].'</a></td>
								<td style="width: 50%;  text-align:left;">'.$tag2['notes_description'].'</td>
									
								<td style="width: 30%;"><a class="tag_forms2" href="'.$tag2['form_href'].'">';
								if($tag2['user_id'] != null && $tag2['user_id'] != "0"){ 
									$html.=$tag2['user_id'];
									if($tag2['notes_type'] == "1"){
										$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
									}else{
										if($tag2['notes_pin'] != null && $tag2['notes_pin'] != ""){
											$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
										}else{
											if($tag2['signature'] != null && $tag2['signature'] != ""){
												$html.='<img src="'.$tag2['signature'].'" width="98px" height="29px">';
											}
										}
									}
										
									if($tag2['form_date_added'] != "" && $tag2['form_date_added'] != ""){
										$html.='('.$tag2['form_date_added'].')';
									}
								}	
									
								$html.='</a></td></tr>';
							}
									
									
							$html.='<script> $(document).ready(function(){
								$("#hide_archived'.$tag['forms_id'].'").click(function(){
									$(".show_archived'.$tag['forms_id'].'").slideToggle("slow");
								});
							});
							</script>';
							
							$if++;
							
							
						
						
						
						} // all form list loop
					
					
						
					
						// for attchment row 
						/*$data = array (
							'sort' => $sort,
							'order' => $order,
							'tags_id' => $tags_id 
						);
					
						$aallattas = $this->model_setting_tags->gettagsattachmets ( $data );
					
						if(!empty($aallattas) && $flag==0){
							
							if($flag==0){
								$flag=1;
							}
							
							foreach ( $aallattas as $aallatta ) {
							
								if ($aallatta ['notes_file'] != null && $aallatta ['notes_file'] != "") {
									$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $aallatta ['notes_media_id'] , 'SSL' );
									$form_name = $aallatta ['image_name'];
								}
								
								$note_info = $this->model_notes_notes->getNote ( $aallatta ['notes_id'] );
								
								$user_id = $note_info ['user_id'];
								$signature = $note_info ['signature'];
								$notes_pin = $note_info ['notes_pin'];
								$notes_type = $note_info ['notes_type'];
								$notes_description = $note_info ['notes_description'];
								
								if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
									$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
								} else {
									$form_date_added = '';
								}
								
								$date_added2 = date ( 'D F j, Y', strtotime ($note_info ['date_added']));
								
								
								$old2 = null; 
										
								if ($old != $date_added2) {
									$old2 = $date_added2;
									$html.='<tr class="clickable"><td colspan="3"><h3 class="bgborder" style="text-align: left;"><a  style="color: #fa801b;">'.$old2.'</a></h3></td></tr>';
								}
								
								$html.='<tr  style="background: #f1f1f1; ">
									<td style="width: 20%; text-align: left;"><a target="_blank" href="'.$hrurl.'">
									<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />Attachment</a></td>
									
									<td style="width: 50%; text-align: left;">'.$notes_description.'</td>
										
									<td style="width: 30%; text-align: left;"><a target="_blank" href="'.$hrurl.'">';
									if($user_id != null && $user_id != "0"){
										$html.=$user_id;
										if($notes_type == "1"){
											$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
										}else{
											if($notes_pin != null && $notes_pin != ""){
												$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
											}else{
												if($signature != null && $signature != ""){
													$html.='<img src="'.$signature.'" width="98px" height="29px">';
												}
											}
										}
										if($form_date_added != "" && $form_date_added != ""){
											$html.='('.$form_date_added.')';
										}
									}
									
								$html.='</a></td></tr>';
							}
						}
					
					
					
					
					}else{
						$html.='<tr><td colspan="3">No Data</td></tr>';
					}
				
				
					$html.='<tr><td colspan="3">';
						if($pagination != null && $pagination != ""){
						$html.='<div class="pagination">'.$pagination.'</div>';
					}
			
					$html.='</td></tr>';
				
				
					*/
					
					
				

					// count all form
					
					$data2 = array (
						'sort' => $sort,
						'order' => $order,
						'group' => '1',
						'tags_id' => $tags_id,
						'custom_form_type' => $allform ['custom_form_type'],
						'start' => ($page - 1) * $config_admin_limit,
						'limit' => $config_admin_limit 
					);
			
					
					$allformslist = $this->model_form_form->gettagsforms ( $data2 );
							
					
					
   					$navigation .='<li class="formtype" data-forms_id="'.$allform ['forms_id'].'" data-tags_id="'.$tags_id.'" data-notes_id="'.$allform ['notes_id'].'" data-custom_form_type="'.$allform ['custom_form_type'].'" data-facilities_id="'.$facilities_id.'"><span class="text" style="width: 71%;text-align: justify;">'.$allform ['incident_number'].' ('.count($allformslist).')</span></li>';
					$i++;
				}
				
				
				
				
				
				/*
				$tagsforms = $this->data ['tagsforms'];
			
				$attachments = $this->data ['attachments'];
			
				//echo '<pre>'; print_r($tagsforms); echo '</pre>';
			
				if(($tagsforms) || ($attachments)){
				
					foreach($tagsforms AS $tag){
					
						if ($if % 2 == 0) {
							$classf = "even";
						} else {
							$classf = 'odd';
						}
						
						$form_name = $tag['form_name'];
						

						if ($old != $tag['date_added2']) {
							$old = $tag['date_added2'];
							$html.='<tr class="clickable"><td colspan="3"><h3 style="text-align:left;" class="bgborder"><a style="color: #fa801b;">'.$old.'</a></h3></td></tr>';
						}

						
						$html.='<tr class="'.$classf.'"><td style="width: 20%; text-align: left;">';
						if($tag['image_url'] != null && $tag['image_url'] != null ){ 
							$html.='<a target="_blank" href="'.$tag['form_href'].'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />'.$tag['form_name'].'</a>';
						}else{
						
							$html.='<a class="tag_forms2" href="'.$tag['form_href'].'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />'.$tag['form_name'].'</a>';
						}
						
						if(!empty($tag['archivedforms'])){
							$html.='<a id="hide_archived'.$tag['forms_id'].'"><img src="sites/view/digitalnotebook/image/down-arrow.png" class=" dots" style="float:right"></a>';
						}
					
						$html.='</td> <td style="width: 50%; text-align:left;">'.$tag['notes_description'].'</td>
						
						<td style="width: 30%;">';
						
						if($tag['image_url'] != null && $tag['image_url'] != null ){ 
							$html.='<a target="_blank" href="'.$tag['form_href'].'">';
						}else{
							$html.='<a class="tag_forms2" href="'.$tag['form_href'].'">';
						}
									
						if($tag['user_id'] != null && $tag['user_id'] != "0"){
							$html.=$tag['user_id'];
							if($tag['notes_type'] == "1"){
								$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							}else{
								if($tag['notes_pin'] != null && $tag['notes_pin'] != ""){
									$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
								}else{
									if($tag['signature'] != null && $tag['signature'] != ""){
										$html.='<img src="'.$tag['signature'].'" width="98px" height="29px">';
									}
								}
							}
							
							if($tag['form_date_added'] != "" && $tag['form_date_added'] != ""){
								$html.='('.$tag['form_date_added'].')';
							}
						}	
									
						$html.='</a></td></tr>';
						
						foreach ($tag['archivedforms'] as $tag2) {
							$html.='<tr class="showpanel1 show_archived'.$tag['forms_id'].'" style="background: #f1f1f1;display:none; ">
							<td style="width: 20%;    text-align: right;"><a class="tag_forms2" href="'.$tag2['form_href'].'"><img src="sites/view/digitalnotebook/image/archived2.jpg" width="35px" height="35px" alt="" style="margin-left: 4px;" />'.$tag2['form_name'].'</a></td>
							<td style="width: 50%;  text-align:left;">'.$tag2['notes_description'].'</td>
								
							<td style="width: 30%;"><a class="tag_forms2" href="'.$tag2['form_href'].'">';
							if($tag2['user_id'] != null && $tag2['user_id'] != "0"){ 
								$html.=$tag2['user_id'];
								if($tag2['notes_type'] == "1"){
									$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								}else{
									if($tag2['notes_pin'] != null && $tag2['notes_pin'] != ""){
										$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									}else{
										if($tag2['signature'] != null && $tag2['signature'] != ""){
											$html.='<img src="'.$tag2['signature'].'" width="98px" height="29px">';
										}
									}
								}
									
								if($tag2['form_date_added'] != "" && $tag2['form_date_added'] != ""){
									$html.='('.$tag2['form_date_added'].')';
								}
							}	
								
							$html.='</a></td></tr>';
						}
							
							
						$html.='<script> $(document).ready(function(){
							$("#hide_archived'.$tag['forms_id'].'").click(function(){
								$(".show_archived'.$tag['forms_id'].'").slideToggle("slow");
							});
						});
						</script>';
						$if++;
					
					} // Main Loop
				}
					
				*/	
				
				
				
				if(count($checkout_form_sign)>0){
					$navigation .='<li class="formtype" data-forms_design_id="1" data-tags_id="'.$tags_id.'" data-checkout="1" ><span class="text" style="width: 71%;text-align: justify;">Checkout Inventory ('.count($checkout_form_sign).')</span></li>';
				}
			
				if(count($checkin_form_sign)>0){
					$navigation .='<li class="formtype" data-forms_design_id="1" data-tags_id="'.$tags_id.'" data-checkin="1" ><span class="text" style="width: 71%;text-align: justify;">Checkin Inventory ('.count($checkin_form_sign).')</span></li>';
				}
			
				if(count($aallattas)>0){
					$navigation .='<li class="formtype" data-forms_design_id="1" data-tags_id="'.$tags_id.'" data-attachment="1" ><span class="text" style="width: 71%;text-align: justify;">Attachments ('.count($aallattas).')</span></li>';
				}
				
   			}else{
   				$navigation='<h6 style="text-align:center;">No Data</h6>';
   			}
			
			
			

			
			$html.='<tr><td colspan="3"><h6 class="message">Select name from menu to display data.</h6></td></tr></div></table>';
				
			
	
			
			$plus_url = $this->url->link ( 'notes/notes/allforms', 'tags_id='.$tags_id, 'SSL' );
			
			$response['html']= $html; 
			
			$response['form_name'] = 'All FORMS';
			
			$response['plus_url'] = $plus_url;
			
			$response['navigation'] = $navigation;
			
			//echo $response['html']= $html; die;
			
			$response['html']= $html; 
			
			//print_r($response);
			
			$this->response->setOutput ( json_encode ( $response ) );
			
			
			
			
			
   			//echo $navigation;	
   		
   		}
   	}
   
   	public function tagforms() {
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->request->get ['facilities_id'];
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		if (!$this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		
		$tags_id = $this->request->get ['tags_id'];
		$this->data ['facilities_id'] = $this->request->get ['facilities_id'];
		$this->data ['tags_id'] = $this->request->get ['tags_id'];
		
		$this->data ['add_client_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/tags/addclient', '', 'SSL' ) );
		$this->data ['add_tag_medication_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/tagsmedication', '', 'SSL' ) );
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->data ['name'] = $tag_info ['emp_tag_id'] . ' : ' . $tag_info ['emp_first_name'] . ' ' . $tag_info ['emp_last_name'];
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			if ($this->session->data ['search_facilities_id'] != NULL && $this->session->data ['search_facilities_id'] != '') {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
			// $facilities_id = $this->customer->getId();
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		$this->data ['customers'] = array ();
		if (! empty ( $customer_info ['setting_data'] )) {
			$customers = unserialize ( $customer_info ['setting_data'] );
			$this->data ['customerinfo'] = $customers;
		}
		
		if($customers['date_format'] != null && $customers['date_format'] != ""){
			$date_format = $customers['date_format'];
		}else{
			$date_format = $this->language->get ( 'date_format_short_2' );
		}
		
		if($customers['time_format'] != null && $customers['time_format'] != ""){
			$time_format = $customers['time_format'];
		}else{
			$time_format = 'h:i A';
		}
		
		$this->data ['date_format'] = $date_format;
		$this->data ['time_format'] = $time_format;
		
		$d1 = array ();
		$d1 ['tags_id'] = $tags_id;
		$d1 ['form_type'] = '2';
		$client_info_sign = $this->model_notes_notes->getNoteform ( $d1 );
		// var_dump($client_info_sign);
		
		$this->data ['client_user_id'] = $client_info_sign ['user_id'];
		$this->data ['client_signature'] = $client_info_sign ['signature'];
		$this->data ['client_notes_pin'] = $client_info_sign ['notes_pin'];
		$this->data ['client_notes_type'] = $client_info_sign ['notes_type'];
		
		if ($client_info_sign ['note_date'] != null && $client_info_sign ['note_date'] != "0000-00-00 00:00:00") {
			$this->data ['client_form_date_added'] = date ( $date_format, strtotime ( $client_info_sign ['note_date'] ) );
		} else {
			$this->data ['client_form_date_added'] = '';
		}
		
		$d12 = array ();
		$d12 ['tags_id'] = $tags_id;
		$d12 ['form_type'] = '1';
		$healthforn_info_sign = $this->model_notes_notes->getNoteform ( $d12 );
		
		$this->data ['health_user_id'] = $healthforn_info_sign ['user_id'];
		$this->data ['health_signature'] = $healthforn_info_sign ['signature'];
		$this->data ['health_notes_pin'] = $healthforn_info_sign ['notes_pin'];
		$this->data ['health_notes_type'] = $healthforn_info_sign ['notes_type'];
		
		if ($healthforn_info_sign ['note_date'] != null && $healthforn_info_sign ['note_date'] != "0000-00-00 00:00:00") {
			$this->data ['health_form_date_added'] = date ( $date_format, strtotime ( $healthforn_info_sign ['note_date'] ) );
		} else {
			$this->data ['health_form_date_added'] = '';
		}
		
		$this->load->model ( 'resident/resident' );
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		if (isset ( $this->request->get ['forms_design_id'] )) {
			$forms_design_id = $this->request->get ['forms_design_id'];
			$this->data ['forms_design_id'] = $forms_design_id;
		} else {
			$forms_design_id = '';
		}
		
		
		if (isset ( $this->request->get ['checkout'] )) {
			$checkout = $this->request->get ['checkout'];
			$this->data ['checkout'] = $checkout;
		} else {
			$checkout = '';
		}
		
		if (isset ( $this->request->get ['checkin'] )) {
			$checkin = $this->request->get ['checkin'];
			$this->data ['checkin'] = $checkin;
		} else {
			$checkin = '';
		}
		
		if (isset ( $this->request->get ['attachment'] )) {
			$attachment = $this->request->get ['attachment'];
			$this->data ['attachment'] = $attachment;
		} else {
			$attachment = '';
		}
		
		$config_admin_limit1 = $this->config->get ( 'config_front_limit' );
		
		if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
			$config_admin_limit = $config_admin_limit1;
		} else {
			$config_admin_limit = "50";
		}
		
		
		if($this->request->get ['forms_design_id'] == "" && $this->request->get ['forms_design_id'] == null && $this->request->get['checkout']!='1' && $this->request->get['checkin']!='1'){
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'tags_id' => $tags_id 
			);
			
			$aallattas = $this->model_setting_tags->gettagsattachmets ( $data );
			
			
			$this->data ['attachments'] = array ();
			foreach ( $aallattas as $aallatta ) {
				if ($aallatta ['notes_file'] != null && $aallatta ['notes_file'] != "") {
					$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $aallatta ['notes_media_id'] , 'SSL' );
					$form_name = $aallatta ['image_name'];
				}
				$note_info = $this->model_notes_notes->getNote ( $aallatta ['notes_id'] );
				
				$user_id = $note_info ['user_id'];
				$signature = $note_info ['signature'];
				$notes_pin = $note_info ['notes_pin'];
				$notes_type = $note_info ['notes_type'];
				$notes_description = $note_info ['notes_description'];
				
				if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
					$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
				} else {
					$form_date_added = '';
				}
				
				
				$this->data ['attachments'] [] = array (
					'notes_media_id' => $aallatta ['notes_media_id'],
					'name' => "Attachment",
					'form_href' => $hrurl,
					'notes_type' => $notes_type,
					'notes_description' => $notes_description,
					'user_id' => $user_id,
					'signature' => $signature,
					'notes_pin' => $notes_pin,
					'form_date_added' => $form_date_added,
					'date_added2' => date ( 'D F j, Y', strtotime ($note_info ['date_added'])),
				);
			}
		}
		
		$data = array (
			'sort' => $sort,
			'order' => $order,
			'group' => '1',
			'groupby' => '1',
			'tags_id' => $tags_id 
		);
		
		$aallforms = $this->model_form_form->gettagsforms ( $data );
		$this->data ['displayforms'] = array ();
		foreach ( $aallforms as $allform ) {
			
			$this->data ['displayforms'] [] = array (
				'forms_design_id' => $allform ['custom_form_type'],
				'form_name' => $allform ['incident_number'],
				'form_href' => $this->url->link ( 'resident/resident/tagforms', '' . '&forms_id=' . $allform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $this->request->get ['facilities_id'], 'SSL' ) 
			);
		}
		
		if($this->request->get ['attachment'] == "" && $this->request->get ['attachment'] == null){
			
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'group' => '1',
				'tags_id' => $tags_id,
				'custom_form_type' => $forms_design_id,
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
			);
			
			$form_total = $this->model_form_form->getTotalforms2 ( $data );
			
			$allforms = $this->model_form_form->gettagsforms ( $data );
			
			//echo '<pre>form_total-'; print_r(count($allforms)); echo '</pre>'; //die;
			
			//echo '<pre>data-'; print_r($data); echo '</pre>'; //die;
			
			//echo '<pre>allforms-'; print_r($allforms); echo '</pre>'; //die;
			
			
			$this->data ['tagsforms'] = array ();
			
			if($this->request->get['checkout']!='1' && $this->request->get['checkin']!='1'){
				foreach ( $allforms as $allform ) {
					
					$resultsforms = $this->model_form_form->getArcheiveFormDatas ( $allform ['forms_id'] );
					
					$archivedforms = array ();
					foreach ( $resultsforms as $resultsform ) {
						$nnote = $this->model_notes_notes->getnotes ( $resultsform ['notes_id'] );
						
						$archivedforms [] = array (
								'forms_id' => $resultsform ['forms_id'],
								'form_name' => $resultsform ['incident_number'],
								'notes_type' => $nnote ['notes_type'],
								'notes_description' => $nnote ['notes_description'],
								'user_id' => $nnote ['user_id'],
								'signature' => $nnote ['signature'],
								'notes_pin' => $nnote ['notes_pin'],
								'form_date_added' => date ( $date_format, strtotime ( $nnote ['note_date'] ) ),
								'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
								'form_href' => $this->url->link ( 'form/form&is_archive=4', '' . '&forms_id=' . $resultsform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $resultsform ['notes_id'] . '&forms_design_id=' . $resultsform ['custom_form_type'] . '&forms_id=' . $resultsform ['forms_id'], 'SSL' ) 
						);
					}
					
					$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
					
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
					
					if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
						$user_id = $allform ['user_id'];
						$signature = $allform ['signature'];
						$notes_pin = $allform ['notes_pin'];
						$notes_type = $allform ['notes_type'];
						$notes_description = $note_info ['notes_description'];
						
						if ($allform ['form_date_added'] != null && $allform ['form_date_added'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $date_format, strtotime ( $allform ['form_date_added'] ) );
						} else {
							$form_date_added = '';
						}
					} else {
						
						// var_dump($note_info);
						$user_id = $note_info ['user_id'];
						$signature = $note_info ['signature'];
						$notes_pin = $note_info ['notes_pin'];
						$notes_type = $note_info ['notes_type'];
						$notes_description = $note_info ['notes_description'];
						
						if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
						} else {
							$form_date_added = '';
						}
					}
					
					if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
						
						$mediainfo = $this->model_form_form->getformmediabyid ($allform ['notes_id'], $allform ['custom_form_type']);
						
						$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $mediainfo ['notes_media_id'] , 'SSL' );
						//$hrurl = $aallform ['image_url'];
						$form_name = $allform ['image_name'];
					} else {
						$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $allform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&forms_id=' . $allform ['forms_id'], 'SSL' );
						
						$form_name = $allform ['incident_number'];
					}
					
					$this->data ['tagsforms'] [] = array (
							'forms_id' => $allform ['forms_id'],
							'image_url' => $allform ['image_url'],
							'form_name' => $form_name,
							'notes_type' => $notes_type,
							'notes_description' => $notes_description,
							'user_id' => $user_id,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'form_date_added' => $form_date_added,
							'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
							'archivedforms' => $archivedforms,
							'form_href' => $hrurl 
					);
				}
				
				//echo '<pre>AAAA-'; print_r($this->data ['tagsforms']); echo '</pre>';	
			}
		
			$d12 = array ();
			$d12 ['tags_id'] = $tags_id;
			$d12 ['form_type'] = '5';
			$checkout_form_sign = $this->model_notes_notes->getInventoryNoteform ( $d12 );
				
			$d12 = array ();
			$d12 ['tags_id'] = $tags_id;
			$d12 ['form_type'] = '6';
			$checkin_form_sign = $this->model_notes_notes->getInventoryNoteform ( $d12 );
			
			if($checkout_form_sign && $this->request->get['forms_design_id']=='' && $this->request->get['checkin']!='1' && $this->request->get['attachment']==''){
				
				$this->data ['displayforms'] [] = array (
					'forms_design_id' => '01',
					'form_name' => 'Checkout Inventory',
					'form_href' => $this->url->link ( 'resident/resident/tagforms', '&checkout=1'.'&tags_id='.$this->request->get['tags_id'], 'SSL' ) 
				);
				
				foreach($checkout_form_sign as $checkout_form){				
				
					$checkout_url = $this->url->link ( 'notes/addInventory/CheckOutInventoryForm', '' . '&notes_id=' . $checkout_form['notes_id'] , 'SSL' );

					if ($checkout_form ['note_date'] != null && $checkout_form ['note_date'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $checkout_form ['note_date'] ) );
						} else {
							$form_date_added = '';
						}

					$this->data ['tagsforms'] [] = array (
						'forms_id' => '',
						'image_url' => 'checkout',
						'form_name' => 'Checkout Inventory',
						'notes_type' => $checkout_form['notes_type'],
						'notes_description' => $checkout_form['notes_description'],
						'user_id' => $checkout_form['user_id'],
						'signature' => $checkout_form['signature'],
						'notes_pin' => $checkout_form['notes_pin'],
						'form_date_added' => $form_date_added,
						'date_added2' => date ( 'D F j, Y', strtotime ( $checkout_form ['date_added'] ) ),
						'archivedforms' => '',
						'form_href' => $checkout_url 
					);	
				}
				
			}
		
			if($checkin_form_sign && $this->request->get['forms_design_id']=='' && $this->request->get['checkout']!='1' && $this->request->get['attachment']==''){
				
				$this->data ['displayforms'] [] = array (
						'forms_design_id' => '02',
						'form_name' => 'Checkin Inventory',
						'form_href' => $this->url->link ( 'resident/resident/tagforms', '&checkin=1'.'&tags_id='.$this->request->get['tags_id'] , 'SSL' ) 
				);


				foreach($checkin_form_sign as $checkin_form){		
					
					$checkin_url = $this->url->link ( 'notes/addInventory/CheckInInventoryForm', '' . '&notes_id=' . $checkin_form['notes_id'] , 'SSL' );

					if ($checkin_form ['note_date'] != null && $checkin_form ['note_date'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $checkin_form ['note_date'] ) );
					} else {
						$form_date_added = '';
					}
					
					
					$this->data ['tagsforms'] [] = array (
							'forms_id' => '',
							'image_url' => 'checkin',
							'form_name' => 'Checkin Inventory',
							'notes_type' => $checkin_form['notes_type'],
							'notes_description' => $checkin_form['notes_description'],
							'user_id' => $checkin_form['user_id'],
							'signature' => $checkin_form['signature'],
							'notes_pin' => $checkin_form['notes_pin'],
							'form_date_added' => $form_date_added,
							'date_added2' => date ( 'D F j, Y', strtotime ( $checkin_form ['date_added'] ) ),
							'archivedforms' => '',
							'form_href' => $checkin_url 
					);
				}
			}
			
			$data2 = array (
				'sort' => $sort,
				'order' => $order,
				'group' => '1',
				'archivedform' => '1',
				'tags_id' => $tags_id 
			);
			
			$aallforms = $this->model_form_form->gettagsforms ( $data2 );
			$this->data ['atagsforms'] = array ();
			
			foreach ( $aallforms as $aallform ) {
				
				$form_info = $this->model_form_form->getFormdata ( $aallform ['custom_form_type'] );
				
				if ($aallform ['user_id'] != null && $aallform ['user_id'] != "") {
					$user_id = $aallform ['user_id'];
					$signature = $aallform ['signature'];
					$notes_pin = $aallform ['notes_pin'];
					$notes_type = $aallform ['notes_type'];
					
					if ($aallform ['form_date_added'] != null && $aallform ['form_date_added'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $date_format, strtotime ( $aallform ['form_date_added'] ) );
					} else {
						$form_date_added = '';
					}
				} else {
					
					$note_info = $this->model_notes_notes->getNote ( $aallform ['notes_id'] );
					
					// var_dump($note_info);
					$user_id = $note_info ['user_id'];
					$signature = $note_info ['signature'];
					$notes_pin = $note_info ['notes_pin'];
					$notes_type = $note_info ['notes_type'];
					$notes_description = $note_info ['notes_description'];
					
					if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $date_format, strtotime ( $note_info ['note_date'] ) );
					} else {
						$form_date_added = '';
					}
				}
				
				if ($aallform ['image_url'] != null && $aallform ['image_url'] != "") {
					$mediainfo = $this->model_form_form->getformmediabyid ($allform ['notes_id'], $allform ['custom_form_type']);
					
					$hrurl = $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $mediainfo ['notes_media_id'] , 'SSL' );
					//$hrurl = $aallform ['image_url'];
					$form_name = $aallform ['image_name'];
				} else {
					$hrurl = $this->url->link ( 'form/form&is_archive=4', '' . '&forms_id=' . $aallform ['forms_id'] . '&tags_id=' . $tags_id . '&notes_id=' . $aallform ['notes_id'] . '&forms_design_id=' . $aallform ['custom_form_type'] . '&forms_id=' . $aallform ['forms_id'], 'SSL' );
					
					$form_name = $aallform ['incident_number'];
				}
				
				$this->data ['atagsforms'] [] = array (
						'forms_id' => $aallform ['forms_id'],
						'image_url' => $aallform ['image_url'],
						'image_name' => $aallform ['image_name'],
						'form_name' => $form_name,
						'notes_type' => $notes_type,
						'notes_description' => $notes_description,
						'user_id' => $user_id,
						'signature' => $signature,
						'notes_pin' => $notes_pin,
						'form_date_added' => $form_date_added,
						'date_added2' => date ( 'D F j, Y', strtotime ( $aallform ['date_added'] ) ),
						'form_href' => $hrurl 
				);
			}
			
			
			
			 //echo '<pre>DDDD-'; print_r($this->data ['tagsforms']); echo '</pre>';
		}
		
		
		
		
		$tagsforms = $this->data ['tagsforms'];
		
		
		
		
		
		$attachments = $this->data ['attachments'];
		$html='';
		$html ='<script>$(".items li").on("click", function () { alert("TTTT"); $("ul li").removeClass("selected"); $(this).attr("class", "selected");}); $(".tag_forms2").colorbox({iframe:true, width:"90%", height:"90%"});</script><div class="table-responsive">
					
				<table width="100%" class="current ">
				<thead>
				<tr class="tag_form_table">
					<th class="tag_form_left_td">Name</th>
					<th class="tag_form_left_td">Description</th>
					<th class="tag_form_right_td">Signature</th>
				</tr></thead>';
				
			if(($tagsforms) || ($attachments)){
			
				foreach($tagsforms AS $tag){
					if ($if % 2 == 0) {
						$classf = "even";
					} else {
						$classf = 'odd';
					}
					
					$form_name = strtoupper($tag['form_name']);
							
					
					if ($old != $tag['date_added2']) {
						$old = $tag['date_added2'];
						$html.='<tr class="clickable"><td colspan="3"><h3 style="text-align:left;" class="bgborder"><a style="color: #fa801b;">&nbsp;'.$old.'</a></h3></td></tr>';
					}
						
					$html.='<tr class="'.$classf.'"><td style="width: 20%; text-align: left;padding: 5px;">';
					if($tag['image_url'] != null && $tag['image_url'] != null ){ 
						
						if($tag['image_url']=='checkout'){
							$attr = 'target="_blank"';
						}else{
							$attr = 'class="tag_forms2"';
						}
						
						
						$html.='<a target="_blank"  href="'.$tag['form_href'].'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" /> '.$tag['form_name'].'</a>';
					}else{
						
						
					
						$html.='<a class="tag_forms2" href="'.$tag['form_href'].'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" /> '.$tag['form_name'].'</a>';
					}
					
					if(!empty($tag['archivedforms'])){
						$html.='<a id="hide_archived'.$tag['forms_id'].'"><img src="sites/view/digitalnotebook/image/down-arrow.png" class=" dots" style="float:right"></a>';
					}
					
					$html.='</td> <td style="width: 50%; text-align:left; line-height: 25px;padding: 5px;">'.$tag['notes_description'].'</td>
					
					<td style="width: 30%;line-height: 25px;text-align: left;padding: 5px;">';
					
					if($tag['image_url'] != null && $tag['image_url'] != null ){ 
						$html.='<a class="tag_forms2" href="'.$tag['form_href'].'">';
					}else{
						$html.='<a class="tag_forms2" href="'.$tag['form_href'].'">';
					}
								
					if($tag['user_id'] != null && $tag['user_id'] != "0"){
						$html.=$tag['user_id'];
						if($tag['notes_type'] == "1"){
							$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
						}else{
							if($tag['notes_pin'] != null && $tag['notes_pin'] != ""){
								$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
							}else{
								if($tag['signature'] != null && $tag['signature'] != ""){
									$html.='<img src="'.$tag['signature'].'" width="98px" height="29px">';
								}
							}
						}
						
						if($tag['form_date_added'] != "" && $tag['form_date_added'] != ""){
							$html.='('.$tag['form_date_added'].')';
						}
					}	
								
					$html.='</a></td></tr>';
						
					foreach ($tag['archivedforms'] as $tag2) {
						$html.='<tr class="showpanel1 show_archived'.$tag['forms_id'].'" style="background: #f1f1f1;display:none; ">
						<td style="width: 20%;"><a class="tag_forms2" href="'.$tag2['form_href'].'"><img src="sites/view/digitalnotebook/image/archived2.jpg" width="35px" height="35px" alt="" style="margin-left: 4px;" /> '.$tag2['form_name'].'</a></td>
						<td style="width: 50%; text-align:left; line-height: 25px; padding: 5px;">'.$tag2['notes_description'].'</td>
							
						<td style="width: 30%;padding: 5px;"><a class="tag_forms2" href="'.$tag2['form_href'].'">';
						if($tag2['user_id'] != null && $tag2['user_id'] != "0"){ 
							$html.=$tag2['user_id'];
							if($tag2['notes_type'] == "1"){
								$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							}else{
								if($tag2['notes_pin'] != null && $tag2['notes_pin'] != ""){
									$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
								}else{
									if($tag2['signature'] != null && $tag2['signature'] != ""){
										$html.='<img src="'.$tag2['signature'].'" width="98px" height="29px">';
									}
								}
							}
								
							if($tag2['form_date_added'] != "" && $tag2['form_date_added'] != ""){
								$html.='('.$tag2['form_date_added'].')';
							}
						}	
							
						$html.='</a></td></tr>';
					}
							
							
					$html.='<script> $(document).ready(function(){
						$("#hide_archived'.$tag['forms_id'].'").click(function(){
							$(".show_archived'.$tag['forms_id'].'").slideToggle("slow");
						});
					});
					</script>';
					$if++;
					
					
					
					
				} // Main Loop
			
			
			
				$old2 = null; 
				if(!empty($attachments)){
					foreach ($attachments as $attachment) {
						
						if ($old != $attachment['date_added2']) {
							$old2 = $attachment['date_added2'];
							$html.='<tr class="clickable"><td colspan="3"><h3 class="bgborder" style="text-align: left;"><a  style="color: #fa801b;">'.$old2.'</a></h3></td></tr>';
						}
						
						$html.='<tr  style="background: #f1f1f1; ">
							<td style="width: 20%; text-align: left;padding: 5px;"><a target="_blank" href="'.$attachment['form_href'].'">
							<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" /> '.$attachment['name'].'</a></td>
							
							<td style="width: 50%; text-align:left; line-height: 25px;padding: 5px;">'.$attachment['notes_description'].'</td>
								
							<td style="width: 30%;line-height: 25px;text-align: left;padding: 5px;"><a target="_blank" href="'.$attachment['form_href'].'">';
							if($attachment['user_id'] != null && $attachment['user_id'] != "0"){
								$html.=$attachment['user_id'];
								if($attachment['notes_type'] == "1"){
									$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								}else{
									if($attachment['notes_pin'] != null && $attachment['notes_pin'] != ""){
										$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									}else{
										if($attachment['signature'] != null && $attachment['signature'] != ""){
											$html.='<img src="'.$attachment['signature'].'" width="98px" height="29px">';
										}
									}
								}
								if($attachment['form_date_added'] != "" && $attachment['form_date_added'] != ""){
									$html.='('.$attachment['form_date_added'].')';
								}
							}
							
						$html.='</a></td></tr>';
					} 	
				}
			
			}else{
				$html.='<tr><td colspan="3">No Data</td></tr>';
			}
		
		
			$html.='<tr><td colspan="3">';
			if($pagination != null && $pagination != ""){
				$html.='<div class="pagination">'.$pagination.'</div>';
			}
			
			$html.='</td></tr>';
			
			$html.='</div></table>';
			
			$plus_url = $this->url->link ( 'notes/notes/allforms', 'tags_id='.$tags_id, 'SSL' );
			
			$data['html']= $html; 
			
			$data['form_name'] = $form_name;
			
			$data['plus_url'] = $plus_url;
			
			//print_r($data);
	
			$this->response->setOutput ( json_encode ( $data ) );
			
			//echo $html;
	}	//end tagsforms function	

	public function getCases() {
		$tags_id = $this->request->get['tags_id'];
		$this->load->model('case/category');
		$this->load->model ( 'facilities/online' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'resident/casefile' );
		$this->language->load ( 'notes/notes' );
		$caseno='';
		$html='';
		$cover='';
		
		$html ='<script>$(".form_open_url").colorbox({iframe:true, width:"90%", height:"90%", overlayClose: false});</script><div class="tag_form_table2">
						
				<div class="col-md-4">Case Number</div>
				
				<div class="col-md-4">Status</div>
				
				<div class="col-md-4">Date</div></div>';
		$cases = $this->model_case_category->getCases($tags_id);
		
		//echo '<pre>'; print_r($cases); echo '</pre>'; //die;
		
		if($cases){
			$i=1;
			foreach ( $cases as $case ) {
				
				$data = array (
					'case_number' => $case ['case_number'],
					'facilities_id' => $this->customer->getId () 
				);
				
				$case_info = $this->model_resident_casefile->getcasefileByCasenumber ( $data );
				
				
				if($case_info ['forms_ids']!=''){
					$forms_ids_arr = explode ( ',', $case_info ['forms_ids'] );
					$forms_ids_arr = array_filter($forms_ids_arr);
				}else{
					$forms_ids_arr =array();
				}
				
				
			
				if ($case_info ['case_status'] == '0') {
					$client_status = 'Open';
				} else if ($case_info ['case_status'] == '1') {
					$client_status = 'Closed';
				} else if ($case_info ['case_status'] == '2') {
					$client_status = 'Marked Final';
				}
				
				
				if($case_info ['case_status']==1){
				  $case_status_message = '<span style="font-style: italic; color: red;"><sup>Closed</sup></span>';
				}else if($case_info ['case_status']==2){
				  $case_status_message = '<span style="font-style: italic; color: red; font-size: 12px"><sup>Marked Final</sup></span>';
				}else if($case_info ['case_status']==0){
				  $case_status_message = '<span style="font-style: italic;  color: green;"><sup>Open</sup></span>';
				}
				
				
			
			
				$caseno .='<li id="'.$case ['case_number'].'" class="casedetail" data-tags_id="'.$tags_id.'" data-case_number="'.$case ['case_number'].'" data-notes_by_case_file_id="'.$case ['notes_by_case_file_id'].'" data-case_status="'.$client_status.'"><span class="text" style="width: 71%;text-align: justify;">'.$case ['case_number'].' ('.count($forms_ids_arr).') '.$case_status_message.'</span></li>';
				$i++;
			}
		}else{
			$caseno='<li class="casedetail" data-tags_id="'.$tags_id.'" data-case_number="'.$case ['case_number'].'"><span class="text message" style="width: 71%;text-align: justify;">No Data</span></li>';
		}
		
		
		if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
		
			/*------------------------------------Case List by case status-------------------------------*/
		
			if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
				
				$tags_id = $this->request->get ['tags_id'];
				
				$url .= '&tags_id=' . $this->request->get ['tags_id'];
				
				$this->data ['tags_id'] = $this->request->get ['tags_id'];
			}
			
			$tag_data = array ();
			$tag_data ['tags_id'] = $tags_id;
			
		
		
			if ($this->request->get ['case_status'] != null && $this->request->get ['case_status'] != "") {
				$case_status .= $this->request->get ['case_status'];
			}
			
			
			$data = array (
				'sort' => $sort,
				'order' => $order,
				'is_case' => '1',
				'status' => $case_status,
				'case_number' => 1,
				'tags_id' => $tags_id,
				'add_case' => '1',
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
			);
			
			//echo '<pre>fff'; print_r($data); echo '</pre>'; //die;
			
		
			
			$this->load->model ( 'resident/casefile' );
			$allforms = $this->model_resident_casefile->getcasefiles ( $data );
			
			//echo '<pre>GGG'; print_r($allforms); echo '</pre>'; //die;
			
			$add_case_url = $this->url->link ( 'resident/formcase/addcase', $url, 'SSL' );
				
			$case_url = $this->url->link ( 'resident/formcase/cases', $url, 'SSL' );
			
			$view_case_url = $this->url->link ( 'resident/formcase/viewcase', '' . $url, 'SSL' );
			//echo 'allforms->'.count($allforms);
			
			if(count($allforms)>0){
				//echo '<pre>HHH'; echo count($allforms); echo '</pre>'; //die;	
				
				
				
				foreach ( $allforms as $allform ) {
					
					
				
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
					if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
						$user_id = $note_info ['user_id'];
						$signature = $allform ['signature'];
						$notes_pin = $allform ['notes_pin'];
						$notes_type = $allform ['notes_type'];
						
						if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['date_added'] ) );
						} else {
							$form_date_added = '';
						}
						// echo 'ssss';
					}
				
					if ($allform ['case_status'] == '0') {
						$client_status = 'Open';
					} else if ($allform ['case_status'] == '1') {
						$client_status = 'Closed';
					} else if ($allform ['case_status'] == '2') {
						$client_status = 'Marked Final';
					}
				
				
						$html .='<div class="row drow">
						<div class="col-md-4">';
						
						$datax = array (
							'case_number' => $allform['case_number']
							//'facilities_id' => $this->customer->getId () 
						);
						
						$case_info = $this->model_resident_casefile->getcasefileByCasenumber ( $datax );
						
						//echo '<pre>GGG'; print_r($case_info); echo '</pre>'; //die;
				
						if($case_info ['forms_ids']!=''){
							$forms_ids_arr = explode ( ',', $case_info ['forms_ids'] );
						}else{
							$forms_ids_arr =array();
						}
						
						
						
						
						$html.='<a id="view_case_url" href="'.$view_case_url.'&case_file_id='.$allform ['notes_by_case_file_id'].'&case_number='.$allform['case_number'].'" >'.$allform['case_number'].' ('.count($forms_ids_arr).') '.'</a>';
						$html.='</div>
						
						<div class="col-md-4">'.$client_status.'</div>
						
						<div class="col-md-4">';
						
							if($user_id != null && $user_id != "0"){
								$html.=$user_id;
								if($notes_type == "1"){
									$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								}else{
									if($notes_pin != null && $notes_pin != ""){
										$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									}else{
										if($signature != null && $signature != ""){
											$html.='<img src="'.$signature.'" width="98px" height="29px">'; 
										}
									}
								}
								
								if($form_date_added != "" && $form_date_added != ""){
									$html.='('.$form_date_added.')';
								}
							}
							
						$html.='</div>
						
						</div>';
				}
			}else{
				$html='<div class="row drow" style="color: #fa801b;"><div class="col-md-12"><h6 class="message">No Data</h6></div></div>';
			} 	

		}else{	
			$html.='<div class="row drow"><div class="col-md-12"><h6 class="message">Select case number from menu to display data.</h6></div></div>';
		}
		
		
		/*$cover='<div class="row" style="padding: 20px;">
	<div class="col-md-12"><h4 class="message">AARON LANGLEY</h4></div>	
</div>

<div class="row" style="padding: 20px;">
	<div class="col-md-12">			
		
		<div class="row">
			<div class="col-md-12">
				<h3 class="card-title message" style="margin-left: 19px;">1.Case Type</h3>
			</div>	
		</div>
		
		
		<div class="row" style="margin: 15px 0px 15px 0px;">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<div class="form-check">
					<input class="form-check-input" type="checkbox">
					<label class="form-check-label" style="width: 53%;">Incident Report</label>
				</div>
			</div>

			<div class="col-md-7">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" checked="">
					<label class="form-check-label" style="width: 48%;">Disciplinary Packet selection</label>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" style="padding: 20px;">
	<div class="col-md-12">				
		<div class="card">
		<div class="card-header border-transparent" style="background-color: #eaeaea;">
		  <h3 class="card-title message">2.Incident Type</h3>
		</div>
		
		<div class="card-body-task">
		<ul class="todo-list" data-widget="todo-list">
			<li><span class="text">A. Assault - Offender on Offender</span></li>
			
			<li><span class="text">B. Assault - Offender on Staff</span></li>
			
			<li><span class="text">C. Assault with Weapon - Offender on Offender</span></li>
			
			<li><span class="text">D. Assault with Weapon- Offender on Staff</span></li>
			
			<li><span class="text">E. Cell Extraction</span></li>
			
			<li><span class="text">F. CERT Team Utilized</span></li>
			
			<li><span class="text">G. Contraband</span></li>
			
			<li><span class="text">H. Death</span></li>
			
			<li><span class="text">I. Deputy Assistance</span></li>
			
			<li><span class="text">J. Disturbance</span></li>
			
			<li><span class="text">K. Equipment</span></li>

			<li><span class="text">L. Escape/Attempted Escape</span></li>
			
			<li><span class="text">M. Fire</span></li>
			
			<li><span class="text">N. Injury - Offender</span></li>
			
			<li><span class="text">O. Injury - Offender (Work Related)</span></li>
			
			<li><span class="text">P. Injury - Staff</span></li>
			
			<li><span class="text">Q. Medical</span></li>
			
			<li><span class="text">R.  Non-compliance</span></li>
			
			<li><span class="text">S. Other</span></li>
			
			<li><span class="text">T. PREA</span></li>
			
			<li><span class="text">U. Program Rule Violation</span></li>
			
			<li><span class="text">V. Program Termination</span></li>
			
			<li><span class="text">W. Property Destruction</span></li>
			
			<li><span class="text">X. Suicide</span></li>
			
			<li><span class="text">Y. Suicide - Attempted</span></li>
			
			<li><span class="text">Z. Theft</span></li>
			
			<li><span class="text">AA. Use of Force</span></li>
			
			<li><span class="text">BB. Watch - 15-minute</span></li>
			
			<li><span class="text">CC. Watch - Constan</span></li>
			
		</ul>
		</div>
		
		</div>
	</div>
</div>

<div class="row" style="padding: 20px;">
	<div class="col-md-12">				
		<div class="card">
		<div class="card-header border-transparent" style="background-color: #eaeaea;">
			<h3 class="card-title message">3. Code – Selected if there is a Code involved with the Disciplinary or Incident Report</h3>
		</div>
		
		<div class="card-body-task">
		<ul class="todo-list" data-widget="todo-list">
			<li><span class="text">A.27-33 – Attempted Suicide/Suicide</span></li>
			<li><span class="text">B.36 – Medical Emergency</span></li>
			<li><span class="text">C.52 – Fire</span></li>
			<li><span class="text">D.103F – Offender-on-offender assault</span></li>
			<li><span class="text">E.110 = Escape/Attempted Escape</span></li>
			<li><span class="text">F.Bravo – Staff Emergency Back-up</span></li>
			<li><span class="text">G.Echo – Evacuation</span></li>
			<li><span class="text">H.None</span></li>
		</ul>
	
		</div>
		</div>
	</div>
</div>

<div class="row" style="padding: 20px;">
	
	<div class="col-md-12">			
		
		<div class="row">
			<div class="col-md-12">
				<h3 class="card-title message" style="margin-left: 19px;">4.Use of Force</h3>
			</div>	
		</div>
		
		
		<div class="row" style="margin: 15px 0px 15px 0px;">
			<div class="col-md-12">
				<div class="form-group">
					
					<select class="form-control" style="margin-left: 30px;padding: 0;">
					  <option value="Yes">Yes</option>
					  <option value="No">No</option>
					  
					</select>
				</div>
			</div>
			
		</div>
	</div>
</div>

<div class="row" style="padding: 20px;">
	
	<div class="col-md-12">			
		
		<div class="row">
			
			<div class="col-md-12">
				<h3 class="card-title message" style="margin-left: 19px;">5.Criminal Charges Filed</h3>
			</div>	
		</div>
		
		
		<div class="row" style="margin: 15px 0px 15px 0px;">
			<div class="col-md-12">
				<div class="form-group">
					<select class="form-control" style="margin-left: 30px;padding: 0;">
					  <option value="Yes">Yes</option>
					  <option value="No">No</option>
					</select>
				</div>
			</div>
			
		</div>
	</div>
</div>';*/
		
		
		
		
			
		$plus_url = $this->url->link ( 'resident/formcase/addcase', 'tags_id='.$tags_id, 'SSL' );
		
		$response['caseno']= $caseno;
		
		//$response['html']= $html;
		
		$response['html'] =$cover; 
		
		$response['form_name'] = ''; ucwords($this->request->get ['case_number']);
		
		$response['plus_url'] = $plus_url;
		
		$response['add_case_url'] = $add_case_url;
		
		$response['attachment_url'] = $attachment_url;
		
		//echo '<pre>'; print_r($response); echo '</pre>';
		
		$this->response->setOutput ( json_encode ( $response ) );
	
		
		/*-------------------------------------------------------------------------------------------------------------------*/
		
		
		//echo $html;	
		//echo '<pre>'; print_r($cases); echo '</pre>'; die;	
	}
	
	public function casedetails(){
		
		$this->load->model ( 'facilities/online' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'resident/casefile' );
		$this->language->load ( 'notes/notes' );
		$this->document->setTitle ( 'Case Detail' );
		if (! $this->customer->isLogged ()) {
			$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		$url = "";
		if ($this->request->get ['case_number'] != null && $this->request->get ['case_number'] != "") {
			
			$data = array (
				'case_number' => $this->request->get ['case_number'],
				'facilities_id' => $this->customer->getId () 
			);
			
			$case_info = $this->model_resident_casefile->getcasefileByCasenumber ( $data );
			$this->request->get ['case_file_id'] = $case_info ['notes_by_case_file_id'];
			$this->request->get ['tags_id'] = $case_info ['tags_ids'];
			$url .= '&case_number=' . $this->request->get ['case_number'];
			$url .= '&tags_id=' . $this->request->get ['tags_id'];
		} else {
			$case_file_id = '';
		}
		//echo '<pre>AAA'; print_r($case_info); echo '</pre>'; //die;
		if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
			$this->data ['case_file_id'] = $this->request->get ['case_file_id'];
			$case_file_id = $this->request->get ['case_file_id'];
			$url .= '&case_file_id=' . $this->request->get ['case_file_id'];
		} else {
			$case_file_id = '';
		}
		
		if ($this->request->get ['tags_id'] != "" && $this->request->get ['tags_id'] != null) {
			$tags_id = $this->request->get ['tags_id'];
			$url .= '&tags_id=' . $this->request->get ['tags_id'];
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['case_number'] != "" && $this->request->get ['case_number'] != null) {
			//$tags_id = $this->request->get ['case_number'];
			$url .= '&case_number=' . $this->request->get ['case_number'];
			$this->data ['case_number'] = $this->request->get ['case_number'];
		}
		
		$tag_data = array ();
		$tag_data ['tags_id'] = $tags_id;
		$form_info = $this->model_setting_tags->getTag ( $tag_data ['tags_id'] );
		//echo '<pre>fff'; print_r($form_info); echo '</pre>'; //die;
		if (! empty ( $form_info )) {
			$this->data ['client_name'] = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
			$client_name = $form_info ['emp_first_name'] . ' ' . $form_info ['emp_last_name'];
		} else {
			$this->data ['client_name'] = '';
			$client_name = '';
		}

		if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
			
			$data = array (
				'case_file_id' => $this->request->get ['case_file_id'],
				'facilities_id' => $this->customer->getId () 
			);
			
			//$case_info = $this->model_resident_casefile->getCaseNumber ( $data );
			
			//echo '<pre>'; print_r($case_info); echo '</pre>'; //die;
			
			//$this->data ['case_number'] = $this->data ['case_number'];
			
		} else {
			
			$case_number_prefix = '';
			if ($client_name != '') {
				foreach ( preg_split ( '#[^a-z]+#i', $client_name, - 1, PREG_SPLIT_NO_EMPTY ) as $word ) {
					$case_number_prefix .= $word [0];
				}
			} else {
				$case_number_prefix = '';
			}

			$this->session->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );
			$this->data ['case_number'] = $case_number_prefix . date ( 'YmdHis' );

		}
		
		
		if($this->request->get ['status'] != null && $this->request->get ['status'] != null){
			$this->data ['status'] = $this->request->get ['status'];
			$this->data ['status2'] = $this->request->get ['status'];
		}else{

			if(isset($case_info['case_status']) && $case_info['case_status']!=''){
				$this->data ['status'] = $case_info['case_status'];
			}else{
				$this->data ['status'] = '';
			}
		}

		//$change_status_url = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&change_status=1', '' . $url, 'SSL' ));
		$case_delete_url = $this->url->link ( 'resident/formcase/deletecase', $url, true );	
		$form_open_url = $this->url->link ( 'form/form', '' . $url, true );
		$add_case_url = $this->url->link ( 'resident/formcase/addcase', $url, true );
		$attachment_url = $this->url->link ( 'notes/notes/attachment', $url, true );		
		$add_casecovepage_url = $this->url->link ( 'resident/formcase/addcasecovepage',''.$url, true );
		
		//echo '<pre>'; print_r($this->request->get); echo '</pre>'; 
		
		
		
			
		$data = array (
			'sort' => $sort,
			'order' => $order,
			'is_case' => 0,
			'page_name' => 'viewcase',
			//'status' => $status,
			'case_number' => 1,
			'case_file_id' => $case_file_id,
			//'tags_id' => $tags_id,
			'add_case' => '1',
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit 
		);
		
		
		
		$this->load->model ( 'resident/casefile' );
		
		$allform1 = $this->model_resident_casefile->getcasefile ( $data );

			$this->data ['case_status'] = $allform1 ['case_status'];
			
			if($allform1 ['forms_ids']!=''){
				$forms_ids_arr = explode ( ',', $allform1 ['forms_ids'] );
			}else{
				$forms_ids_arr =array();
			}
			
			$forms_ids_arr = array_filter($forms_ids_arr);

			//echo '<pre>bbb'; print_r($forms_ids_arr); echo '</pre>'; //die;
			
			
			$html ='';
			$this->request->get ['case_file_id'];
			if ($this->request->get ['case_file_id'] != null && $this->request->get ['case_file_id'] != "") {
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
				$unique_id = $facility ['customer_key'];
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				
				
				
				$this->data['customerinfo'] = array();
				if (! empty ( $customer_info ['setting_data'])) {
					
					$customers = unserialize($customer_info ['setting_data']);
					
					//echo '<pre>'; print_r($customers); echo '</pre>'; 
					
					$html .= '<div class="tag_form_table3">';	
					$html .= '<div class="col-md-3">'; 
						if($customers['case_type_name']!=''){ 
							$html .= $customers['case_type_name']; 
						}else{ 
							$html .= 'Case Type'; 
						} 
					$html .='</div>';					
					$html .= '<div class="col-md-3">'; 
						if($customers['incident_type_name']!=''){ 
							$html .= $customers['incident_type_name'];
						}else{ 
							$html .= 'Incident Type'; 
						}
					$html .='</div>';
					$html .= '<div class="col-md-3">'; 
						if($customers['code_name']!=''){
							$html .= $customers['code_name'];
						}else{ 
							$html .= 'Code'; 
						} 
					$html .='</div>';
					$html .= '<div class="col-md-3" style="font-size: 12px;">'; 
					
						if($customers['user_of_force_name']!=''){
							$html .= $customers['user_of_force_name'];
						}else{ 
							$html .= 'User of Force'; 
						}
						
						if($customers['charges_name']!=''){
							$html .= '/'.$customers['charges_name'];
						}else{ 
							$html .= '/Criminal Charges Filed'; 
						}
					
					
					$html .='</div>';
					$html .= '</div>';
					
					$cdata = array (
						'case_file_id' => $this->request->get ['case_file_id'],
						'facilities_id' => $this->customer->getId () 
					);
					
					//echo '<pre>'; print_r($cdata); echo '</pre>'; //die;
					
					$casecover_info = $this->model_resident_casefile->getcasefileforviewcase ( $cdata );
					
					//echo '<pre>'; print_r($casecover_info); echo '</pre>';
					
					$this->data ['is_row']=0;
					if($casecover_info){
						$this->data ['is_row'] = 1;
						$case_type = '';
						if($casecover_info['case_type']!=''){
							$case_type_arr = explode(',',$casecover_info['case_type']);
							foreach($case_type_arr AS $case_type){
								$res = $this->model_notes_notes->getcustomlistvalue($case_type);
								$case_type_name_arr[] = $res['customlistvalues_name'];
							}
							$case_type = str_replace(',','<br>',implode(',',$case_type_name_arr));
						}
						
						$incident_type = '';
						if($casecover_info['incident_type']!=''){
							$incident_type_arr = explode(',',$casecover_info['incident_type']);
							foreach($incident_type_arr AS $incident_type){
								$res = $this->model_notes_notes->getcustomlistvalue($incident_type);
								$incident_type_name_arr[] = $res['customlistvalues_name'];
							}
							$incident_type = str_replace(',','<br>',implode(',',$incident_type_name_arr));
						}
						
						$code = '';
						if($casecover_info['code']!=''){
							$code_arr = explode(',',$casecover_info['code']);
							foreach($code_arr AS $code){
								$res = $this->model_notes_notes->getcustomlistvalue($code);
								$code_name_arr[] = $res['customlistvalues_name'];
							}
							$code = str_replace(',','<br>',implode(',',$code_name_arr));
						}
						
						$options2 = '';
						$options =array();
						if($casecover_info['user_of_force_name']!=''){
							$options[] = 'Use of Force Name - <span style="font-weight: 600;font-size: 13px;">'.$casecover_info['user_of_force_name'].'</span>';
						}else{
							$options[] = 'No use of force name';
						}
						
						$this->data ['criminal_charges_filed'] = '';
						if($casecover_info['criminal_charges_filed']!=''){
							$options[] = 'Criminal Charges Filed - <span style="font-weight: 600;font-size: 13px;">'.$casecover_info['criminal_charges_filed'].'</span>';
						}else{
							$options[] = 'No criminal charges filed';
						}
						
						$options2 = str_replace(',','<br>',implode(',',$options));
						
						$html .= '<div class="row drow2">';			
						$html .= '<div class="col-md-3">'; if($case_type!=''){$html .= $case_type;} $html .='</div>';
						$html .= '<div class="col-md-3">'; if($incident_type!=''){$html .= $incident_type;} $html .='</div>';
						$html .= '<div class="col-md-3">'; if($code!=''){$html .= $code;} $html .='</div>';
						$html .= '<div class="col-md-3">';  
						
							$case_file_id = $this->request->get ['case_file_id'];
							$case_number = $this->request->get ['case_number'];
							$tags_id = $this->request->get ['tags_id'];
							
							$html.='<div style="float:left; width:70%;">';
								if($options2!=''){$html .= $options2;}
							$html.='</div>
							<div style="float:right;width:30%;margin-top: 5px;">
								<a id="editcasecoverpage" data-case_file_id1="'.$case_file_id.'" data-case_number1="'.$case_number.'" data-tags_id1="'.$tags_id.'"  onclick="addcase_validation(1);" class="editcp">
									<i class="fa fa-edit"></i> Edit
								</a>
							</div>';
						$html .='</div>';
						$html .= '</div>';
					
					}else{
						$html .= '<div class="row drow"><div class="col-md-3">No data found</div></div>';	
					}	
				}		
			}
			
			//echo 'YYYY-'.$html; die;
			
			
			
		
			$html .='<script>$(".form_open_url").colorbox({iframe:true, width:"90%", height:"90%", overlayClose: false});</script><div class="tag_form_table2">
					
				<div class="col-md-2">Form Name </div>
				
				<div class="col-md-2">Inmate Name</div>
				
				<div class="col-md-5">Description</div>
				
				<div class="col-md-2">Date</div>
				
				<div class="col-md-1">Action</div></div>';
				
				
			$this->data ['tagsforms'] = array ();
			
			if(count($forms_ids_arr)>0){
				foreach ( $forms_ids_arr as $form_id ) {
					
					
					
					$allform = $this->model_form_form->getFormDatas ( $form_id );
				
				
					//echo '<pre>'; print_r($form_info); echo '</pre>'; //die;
					
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
					
					// echo '<pre>'; print_r($note_info); echo '</pre>'; //die;
					
					
					if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
						$user_id = $note_info ['user_id'];
						$signature = $allform ['signature'];
						$notes_pin = $allform ['notes_pin'];
						$notes_type = $allform ['notes_type'];
						$notes_description = $note_info ['notes_description'];
						
						if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['date_added'] ) );
						} else {
							$form_date_added = '';
						}
					} else {
						// var_dump($note_info);
						$user_id = $note_info ['user_id'];
						$signature = $note_info ['signature'];
						$notes_pin = $note_info ['notes_pin'];
						$notes_type = $note_info ['notes_type'];
						$notes_description = $note_info ['notes_description'];
						
						if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
							$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
						} else {
							$form_date_added = '';
						}
					}
					
					if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
						$hrurl = $allform ['image_url'];
						$form_name = $allform ['image_name'];
						$fileOpen = $this->url->link('notes/notes/displayFile', '' . '&notes_media_id='.$hrurl . $url, 'SSL');
					} else {
						$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $form_id . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], 'SSL' );
						$form_name = $allform ['incident_number'];
					}
					
					if ($allform ['case_status'] == '0') {
						$client_status = 'Open';
					} else if ($allform ['case_status'] == '1') {
						$client_status = 'Closed';
					} else if ($allform ['case_status'] == '2') {
						$client_status = 'Marked Final';
					}
					
					
					if($allform1 ['tags_ids']!=''){
						$inmate_arr=array();
						$tags_ids_arr = explode(',',$allform1 ['tags_ids']);
						foreach($tags_ids_arr AS $tag_id){
							$tag_info = $this->model_setting_tags->getTag ( $tag_id );
							//echo '<pre>'; print_r($tag_info['emp_first_name']); echo '</pre>';
							//echo '<pre>'; print_r($tag_info['emp_last_name']); echo '</pre>';
							$inmate_arr[] = $tag_info['emp_last_name'].' '.$tag_info['emp_first_name'];
						}
						
						$inmate_name = implode(',',$inmate_arr);
					}else{
						$inmate_name = '';
					}
					
					
					//echo '<pre>sssss'; print_r($allform); echo '</pre>';
					
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
					$this->data ['tagsforms'] [] = array (
							'case_number' => $allform ['case_number'],
							'forms_id' => $form_id,
							'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
							'image_url' => $allform ['image_url'],
							'notes_id' => $allform ['notes_id'],
							'assign_case' => $allform ['assign_case'],
							'forms_design_id' => $allform ['custom_form_type'],
							'form_name' => $form_name,
							'notes_type' => $notes_type,
							'notes_description' => $notes_description,
							'user_id' => $user_id,
							'case_status' => $client_status,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'form_date_added' => $form_date_added,
							'note_date' => $note_info ['note_date'],
							'date_added' => date ( 'm-d-Y', strtotime ( $allform ['date_added'] ) ),
							'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
							'archivedforms' => $archivedforms,
							'form_href' => $hrurl, //fileOpen,
							'case_status2' => $allform ['case_status']
					);
					
					
					// &delete_case=1&case_file_id=44&tags_id=11374&case_number=AL20210916032748&addcase=1&forms_id=19597
					
					$html .='<div class="row drow">
							<div class="col-md-2">';
							
							if($allform ['image_url'] != null && $allform ['image_url'] != ""){
								$html.='<a target="_blank" href="'.$hrurl.'" style="font-size: 14px;"><img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="25px" height="25px" alt="" style="margin-left: 4px;"/> '.$form_name.'</a>';
							}else{   
								$html.='<a class="form_open_url" href="'.$hrurl.'" style="font-size: 14px;"><img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="25px" height="25px" alt="" style="margin-left: 4px;"/> '.$form_name.'</a>';
							}
							
							if($allform1 ['case_status']==1){
								$html .='<span style="color: red;"><sup>Closed</sup></span>';
							}
							
							if($allform1 ['case_status']==2){
								$html .='<span style="color: red;"><sup>Marked Final</sup></span>';
							}
							
							
							$html.='</div>
							
							<div class="col-md-2">'.$inmate_name.'</div>
							
							<div class="col-md-5" style="padding: 10px;">'.$notes_description.'</div>
							
							<div class="col-md-2">';
							
							if($user_id != null && $user_id != "0"){
                    
								$html.=$user_id;
                    
								if($notes_type == "1"){
									$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
								}else{
									if($notes_pin != null && $notes_pin != ""){
										$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
									}else{
										if($tag['signature'] != null && $tag['signature'] != ""){
											$html.='<img src="'.$signature.'" width="98px" height="29px">';
										}
									}
								}
								
								if($form_date_added != "" && $form_date_added != ""){
									$html.='('.$form_date_added.')';
								}
							}
							
							
							
							$html.='</div>
							
							<div class="col-md-1">';
							
							
							if($allform1 ['case_status']==0){
								$html.='<a class="form_open_url" href="'.$case_delete_url.'&addcase=1&forms_id='.$form_id.'" >';
								$html.='<i class="fa fa-trash fa-1x text-danger" aria-hidden="true"></i>';
								$html.='</a>';
								
							}else if($allform1 ['case_status']==1){
								$html .='<span style="color: red;"><sup>Closed</sup></span>';
							}else if($allform1 ['case_status']==2){
								$html .='<span style="color: red;"><sup>Marked Final</sup></span>';
							}else{
								$html.=$case_status_message;
							}
							
							
							
                 
							$html.='&nbsp;&nbsp;&nbsp;<a class="form_open_url" href="'.$form_open_url.'&update_notetime=1&forms_design_id='.$allform ['custom_form_type'].'&forms_id='.$form_id.'&notes_id='.$allform ['notes_id'].'&isreload=1"><i class="fa fa-edit fa-1x text-primary" aria-hidden="true"></i>
							 </a></div></div>';
				}

			}else{
				$html .='<div class="row drow"><div class="col-md-12"><h6 class="message">No Data</h6></div></div>';
			}	
			
			if($this->request->get ['is_reload']==1){
				echo $html;
			}else{
				$plus_url = $this->url->link ( 'resident/formcase/addcase', 'tags_id='.$tags_id, 'SSL' );
				
				$response['html']= $html; ///die;
				
				$response['case_file_id'] = $case_file_id;
				
				$response['form_name'] = ucwords($this->request->get ['case_number']);
				
				$response['plus_url'] = $plus_url;
				
				$response['add_case_url'] = $add_case_url;
				
				$response['attachment_url'] = $attachment_url;
				
				$response['case_file_id'] = $case_file_id;
				
				//echo '<pre>'; print_r($this->data ['tagsforms']); echo '</pre>';
				
				$this->response->setOutput ( json_encode ( $response ) );
			}
		
	}

	public function report () { 
        $this->language->load('common/home');
        
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->data['heading_title'] = $this->config->get('config_title');
        
        $this->data['error2'] = $this->request->get['error2'];
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 = '&searchdate=' . $this->request->get['searchdate'];
        }
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $url2 .= '&note_date_from=' . $this->request->get['note_date_from'];
        }
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $url2 .= '&note_date_to=' . $this->request->get['note_date_to'];
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
            $url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        $this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url2, 'SSL');
        $this->data['printUlr'] = $this->url->link('notes/notes/generatePdf', '' . $url2, 'SSL');
        
        if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            $facilities_id = $this->customer->getId();
        }

        $this->load->model('setting/tags');
        $this->load->model('facilities/facilities');
   		
   		if($this->request->get['tags_id']!="" && $this->request->get['tags_id']!=null) { 
			$client_info = $this->model_setting_tags->getTag ( $this->request->get['tags_id']);	
			
			if($client_info) { 
				$tag_facilities_id= $client_info['facilities_id'];
				$facilityinfo = $this->model_facilities_facilities->getfacilities($tag_facilities_id); 
				$facility=$facilityinfo['facility'];
			}
   		}
        
        $this->data['config_taskform_status'] = $this->customer->isTaskform();
        $this->data['config_noteform_status'] = $this->customer->isNoteform();
        $this->data['config_rules_status'] = $this->customer->isRule();
        $this->data['config_share_notes'] = $this->customer->isNotesShare();
        $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
        
        $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url2, 'SSL');
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL');
        $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url2, 'SSL');
        
        $this->data['customIntake_url'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
        $this->data['censusdetail_url'] = $this->url->link('resident/dailycensus/censusdetail', '' . $url2, 'SSL');
        
        $this->data['medication_url'] = $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL');
        
        $this->data['bedcheck_url'] = $this->url->link('notes/notes/generatePdf&is_bedchk=1', '' . $url2, 'SSL');
        
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL');
        $this->data['customIntake_url'] = $this->url->link('notes/tags/addclient', '' . $url2, 'SSL');
        
        $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url2, 'SSL');
        
        $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url2, 'SSL');
        
        $this->data['attachment_sign_url'] = $this->url->link('notes/notes/attachmentSign', '' . $url2, 'SSL');
        
        $this->data['naotes_tags_url'] = $this->url->link('notes/notes/updateTags', '' . $url2, 'SSL');
        
        $this->data['medication_url'] = $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL');
        
        $this->data['censusdetail_url'] = $this->url->link('resident/dailycensus/censusdetail', '' . $url2, 'SSL');
        $this->data['updatetag_url'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
        $this->data['bedcheck_url'] = $this->url->link('notes/notes/generatePdf&is_bedchk=1', '' . $url2, 'SSL');
        
        $this->data['assignteam_url'] = $this->url->link('resident/assignteam', '' . $url2, 'SSL');
        
        $this->data['resetUrl'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url, 'SSL');
        
        $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url, 'SSL');
        $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url, 'SSL');
        
        $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url, 'SSL');    
       
        $this->data['sharenotes_Url'] = $this->url->link('notes/sharenote/searchnoteshare', '' . $url2, 'SSL');
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        $this->load->model('createtask/createtask');
        
        $this->data['tagassignotes'] = $this->model_notes_notes->gettagassigns($facilities_id);
        
        $this->load->model('setting/highlighter');
        $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters();
        
        $this->data['note_date_from'] = date('m-d-Y', strtotime('now'));
        $this->data['note_date_to'] = date('m-d-Y', strtotime('now'));
        
        $this->load->model('createtask/createtask');
        $this->data['tasktypes'] = $this->model_createtask_createtask->getTaskdetails($facilities_id);
        
        $this->load->model('setting/keywords');
        
        $data3 = array(
                'facilities_id' => $facilities_id,
                'sort' => 'keyword_name'
        );
        
        $this->data['activenotes'] = $this->model_setting_keywords->getkeywords($data3);
        
        // var_dump($this->data['activenotes']);
        
        $this->load->model('form/form');
        $data3 = array();
        $data3['status'] = '1';
        // $data3['order'] = 'sort_order';
        $data3['is_parent'] = '1';
        $data3['facilities_id'] = $facilities_id;
        
        $custom_forms = $this->model_form_form->getforms($data3);
        
        $this->data['custom_forms'] = array();
        foreach ($custom_forms as $custom_form) {
            
            $this->data['custom_forms'][] = array(
                    'forms_id' => $custom_form['forms_id'],
                    'form_name' => $custom_form['form_name'],
                    'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
            );
        }
        
        // $this->load->model('resident/report');
        // $this->data['assigntos'] =
        // $this->model_resident_report->getassigns();
        
        $this->load->model('notes/image');
        $this->load->model('setting/highlighter');
        $this->load->model('user/user');
        $this->load->model('notes/tags');
        
        unset($this->session->data['media_user_id']);
        unset($this->session->data['media_signature']);
        unset($this->session->data['media_pin']);
        unset($this->session->data['emp_tag_id']);
        unset($this->session->data['tags_id']);
        
        $this->data['notess'] = array();
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('common/home', '' . $url2, 'SSL'));
        // $this->data['rediectUlr'] = str_replace('&amp;', '&',
        // $this->url->link('common/home', '' . $url2, 'SSL'));
        
        if (isset($this->session->data['update_reminder'])) {
            $this->data['update_reminder'] = $this->session->data['update_reminder'];
        }
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $date = str_replace('-', '/', $this->request->get['note_date_from']);
            $res = explode("/", $date);
            
            $note_date_from = $res[2] . "-" . $res[1] . "-" . $res[0];
        }
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $date = str_replace('-', '/', $this->request->get['note_date_to']);
            $res = explode("/", $date);
            $note_date_to = $res[2] . "-" . $res[1] . "-" . $res[0];

			$changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
            $note_date_to = date('Y-m-d', strtotime($changedDate));
        }
        
        if ($this->session->data['note_date_from'] != null && $this->session->data['note_date_from'] != "") {
            
            $date = str_replace('-', '/', $this->session->data['note_date_from']);
            $res = explode("/", $date);
            $note_date_from = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            // $note_date_from = date('Y-m-d',
        // strtotime($this->session->data['note_date_from']));
        }
        if ($this->session->data['note_date_to'] != null && $this->session->data['note_date_to'] != "") {
            $date = str_replace('-', '/', $this->session->data['note_date_to']);
            $res = explode("/", $date);
            $note_date_to = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            // $note_date_to = date('Y-m-d',
        // strtotime($this->session->data['note_date_to']));
        }
        
        $timezone_name = $this->customer->isTimezone();
        $timeZone = date_default_timezone_set($timezone_name);
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $noteTime = date('H:i:s');
            
            $date = str_replace('-', '/', $this->request->get['searchdate']);
            $res = explode("/", $date);
            $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_datenew'] = $changedDate . ' ' . $noteTime;
            $searchdate = $this->request->get['searchdate'];
            $this->data['searchdate'] = $this->request->get['searchdate'];
            
            if (($searchdate) >= (date('m-d-Y'))) {
                $this->data['back_date_check'] = "1";
            } else {
                $this->data['back_date_check'] = "2";
            }
        } else {
            $this->data['note_datenew'] = date('Y-m-d H:i:s');
            $this->data['searchdate'] = date('m-d-Y');
        }
        
        if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
            $noteTime = date('H:i:s');
            
            $date = str_replace('-', '/', $this->request->get['fromdate']);
            $res = explode("/", $date);
            $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $note_date_from = date('Y-m-d', strtotime($changedDate));
            
            //$note_date_to = date('Y-m-d');
            //$this->session->data['advance_search'] = '1';
            
            if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
                $this->session->data['highlighter'] = $this->request->get['highlighter'];
            }
            
            if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
                $this->session->data['activenote'] = $this->request->get['activenote'];
            }
        }
        
        if (isset($this->request->get['page'])) { 
            $page = $this->request->get['page'];
        } else { 
            $page = 1;
        }
        
        $config_admin_limit1 = $this->config->get('config_front_limit');
        if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
            $config_admin_limit = $config_admin_limit1;
        } else { 
            $config_admin_limit = "50";
        }
            
		$this->data['case_detail'] = "1";
		
		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$tags_id = $this->request->get['tags_id'];
			$search_emp_tag_id = $tags_id;
			$case_detail = '1';
		}
        
        if ($this->request->get['form'] == "1") { 
            $form_search = 'all';
        } else { 
            $form_search = $this->session->data['form_search'];
        }
        
        if ($this->request->get['sightandsound'] == "1") { 
            $tasktype = '25';
        } else { 
            $tasktype = $this->session->data['tasktype'];
        }
        
        if ($this->request->get['task'] == "1") { 
            $task_search = 'all';
        }
        
        if ($this->request->get['highlighter'] == "1") { 
            $highlighter = 'all';
        } else { 
            $highlighter = $this->session->data['highlighter'];
        }
        
        if ($this->request->get['search_user_id'] == "1") { 
            $search_user_id = 'all';
        } else { 
            $search_user_id = $this->session->data['search_user_id'];
        }
        
        if ($this->session->data['advance_search'] != null && $this->session->data['advance_search'] != "") { 
            $advance_search = $this->session->data['advance_search'];
        } else { 
            $advance_search = '1';
        }
        
        if ($this->request->get['review'] == '1') { 
            $review_notes = '1';
        }
        
        if ($this->request->get['color'] == '1') { 
            $text_color = '1';
        }
        
        if ($this->request->get['incident'] == "1") { 
            $keyword = 'incident';
            $activenote = '44';
            $search_acitvenote_with_keyword = '1';
        } elseif ($this->request->get['pillcall'] == "1") { 
            $activenote = '38';
            $keyword = 'medication';
            $search_acitvenote_with_keyword = '1';
        } elseif ($this->request->get['activenote'] == "1") { 
            $activenote = 'all';
        } else {
            $keyword = $this->session->data['keyword'];
            $activenote = $this->session->data['activenote'];
        }
		
		$url = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url .= '&searchdate=' . $this->request->get['searchdate'];
        }
        if ($this->request->get['review'] != null && $this->request->get['review'] != "") {
            $url .= '&review=' . $this->request->get['review'];
        }
        if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
            $url .= '&fromdate=' . $this->request->get['fromdate'];
        }
        if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
            $url .= '&highlighter=' . $this->request->get['highlighter'];
        }
        if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
            $url .= '&activenote=' . $this->request->get['activenote'];
        }
        if ($this->request->get['clpage'] != null && $this->request->get['clpage'] != "") {
            $url .= '&clpage=' . $this->request->get['clpage'];
        }
        
        if ($this->request->get['fpage'] != null && $this->request->get['fpage'] != "") {
            $url .= '&fpage=' . $this->request->get['fpage'];
        }
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $url .= '&note_date_from=' . $this->request->get['note_date_from'];
        }
        
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $url .= '&note_date_to=' . $this->request->get['note_date_to'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url .= '&tags_id=' . $this->request->get['tags_id'];
        }
        if ($this->request->get['form'] != null && $this->request->get['form'] != "") {
            $url .= '&form=' . $this->request->get['form'];
        }
        if ($this->request->get['sightandsound'] != null && $this->request->get['sightandsound'] != "") {
            $url .= '&sightandsound=' . $this->request->get['sightandsound'];
        }
        if ($this->request->get['incident'] != null && $this->request->get['incident'] != "") {
            $url .= '&incident=' . $this->request->get['incident'];
        }
        if ($this->request->get['task'] != null && $this->request->get['task'] != "") {
            $url .= '&task=' . $this->request->get['task'];
        }
        if ($this->request->get['reporthighlighter'] != null && $this->request->get['reporthighlighter'] != "") {
            $url .= '&reporthighlighter=' . $this->request->get['reporthighlighter'];
        }
        if ($this->request->get['reportactivenote'] != null && $this->request->get['reportactivenote'] != "") {
            $url .= '&reportactivenote=' . $this->request->get['reportactivenote'];
        }
        if ($this->request->get['search_user_id'] != null && $this->request->get['search_user_id'] != "") {
            $url .= '&search_user_id=' . $this->request->get['search_user_id'];
        }
        if ($this->request->get['review'] != null && $this->request->get['review'] != "") {
            $url .= '&review=' . $this->request->get['review'];
        }
        if ($this->request->get['color'] != null && $this->request->get['color'] != "") {
            $url .= '&color=' . $this->request->get['color'];
        }
        
        $count = ceil($notes_total / 200);
        
        if ($count > 1) {
            $this->data['sharenotes_Url'] = $this->url->link('notes/sharenote/searchnotepage', '' . $url, 'SSL');
        } else {
            $this->data['sharenotes_Url'] = $this->url->link('notes/sharenote/searchnoteshare', '' . $url, 'SSL');
        }
        
        $pagination = new Pagination();
        $pagination->total = $notes_total;
        $pagination->page = $page;
        $pagination->limit = $config_admin_limit;
        
        $pagination->text = ''; // $this->language->get('text_pagination');
        $pagination->url = $this->url->link('case/searchresult/report', '' . $url . '&page={page}', 'SSL');
        
        $this->data['pagination'] = $pagination->render();
		
		if ($this->request->get['reqType'] != null && $this->request->get['reqType'] != "") {
            if($this->request->get['reqType']=='task') { 
				$tasktype = $this->request->get['reqValue'];
			}

			if($this->request->get['reqType']=='keyword') { 
				$keyword = $this->request->get['reqValue'];
			}

			if($this->request->get['reqType']=='form') { 
				$form_search = $this->request->get['reqValue'];
			}
        }
		
        $data = array(
			'sort' => $sort,
			'case_detail' => $case_detail,
			'search_acitvenote_with_keyword' => $search_acitvenote_with_keyword,
			'order' => $order,
			'group' => '1',
			'searchdate' => $searchdate,
			//'searchdate_app' => '1',
			'advance_search' => '1',
			'advance_date_desc' => '1',
			//'facilities_id' => $tag_facilities_id,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'task_search' => $task_search,
			
			'search_time_start' => $this->session->data['search_time_start'],
			'search_time_to' => $this->session->data['search_time_to'],
			'customer_key' => $this->session->data['webcustomer_key'],
			
			'keyword' => $keyword,
			'text_color' => $text_color,
			'review_notes' => $review_notes,
			'form_search' => $form_search,
			'user_id' => $search_user_id,
			'highlighter' => $highlighter,
			'activenote' => $activenote,
			'emp_tag_id' => $search_emp_tag_id,
			'advance_searchapp' => $advance_search,
			'tasktype' => $tasktype,
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
        );
        
        //echo '<pre>'; print_r($data); echo '</pre>';
        
        $this->load->model('notes/case');
        $notes_total = $this->model_notes_notes->getnotess($data);
      
        
        $this->load->model('notes/notes');
        $this->load->model('facilities/facilities');
        $last_notesID = $this->model_notes_notes->getLastNotesID($facilities_id, $searchdate);
        
        $this->data['last_notesID'] = $last_notesID['notes_id'];
        
        $results = $this->model_notes_notes->getnotess($data);
        
     
        $this->load->model('notes/tags');
        
        $config_tag_status = $this->customer->isTag();
        $this->data['config_tag_status'] = $this->customer->isTag();
        
        $this->data['config_taskform_status'] = $this->customer->isTaskform();
        $this->data['config_noteform_status'] = $this->customer->isNoteform();
        $this->data['config_rules_status'] = $this->customer->isRule();
        $this->data['config_share_notes'] = $this->customer->isNotesShare();
        $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
        
        $this->data['unloack_success'] = $this->session->data['unloack_success'];
        // require_once(DIR_APPLICATION . 'aws/getItem.php');
        
        $facilityinfo = $this->model_facilities_facilities->getfacilities($facilities_id);
        
        $html='';
        
		foreach ($results as $result) { 
            
            $this->cache->delete('note' . $result['notes_id']);
            
            $highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
            
            $reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
            
            $allimages = $this->model_notes_notes->getImages($result['notes_id']);
            $images = array();
            foreach ($allimages as $image) {
                
                $extension = $image['notes_media_extention'];
                if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
                    $keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
                } else 
                    if ($extension == 'doc' || $extension == 'docx') {
                        $keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
                    } else 
                        if ($extension == 'ppt' || $extension == 'pptx') {
                            $keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
                        } else 
                            if ($extension == 'xls' || $extension == 'xlsx') {
                                $keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
                            } else 
                                if ($extension == 'pdf') {
                                    $keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
                                } else {
                                    $keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
                                }
                
						$images[] = array(
                        'keyImageSrc' => $keyImageSrc, // '<img
                                                      // src="sites/view/digitalnotebook/image/attachment.png"
                                                      // width="35px"
                                                      // height="35px" alt=""
                                                      // style="margin-left:
                                                      // 4px;" />',
                        'media_user_id' => $image['media_user_id'],
                        'notes_type' => $image['notes_type'],
                        'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
                        'media_signature' => $image['media_signature'],
                        'media_pin' => $image['media_pin'],
                        'notes_file_url' => $this->url->link('notes/notes/displayFile', '' . '&notes_media_id=' . $image['notes_media_id'], 'SSL')
                )
                ;
            }
            
            $reminder_time = $reminder_info['reminder_time'];
            $reminder_title = $reminder_info['reminder_title'];
            
            if ($result['keyword_file'] != null && $result['keyword_file'] != "") {
                $keyImageSrc1 = '<img src="' . $result['keyword_file_url'] . '" wisth="35px" height="35px">';
            } else {
                $keyImageSrc1 = "";
            }
            
            if ($result['notes_pin'] != null && $result['notes_pin'] != "") {
                $userPin = $result['notes_pin'];
            } else {
                $userPin = '';
            }
            
            if ($result['task_time'] != null && $result['task_time'] != "00:00:00") {
                $task_time = date('h:i A', strtotime($result['task_time']));
            } else {
                $task_time = "";
            }
            
            // if ($config_tag_status == '1') {
            
            $alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
            
            if ($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != "") {
                $tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
                $privacy = $tagdata['privacy'];
                
                if ($tagdata['privacy'] == '2') {
                    if ($this->session->data['unloack_success'] != '1') {
                        $emp_tag_id = $alltag['emp_tag_id'] . ':' . $tagdata['emp_first_name'];
                    } else {
                        $emp_tag_id = '';
                    }
                } else {
                    $emp_tag_id = '';
                }
            } else {
                $emp_tag_id = '';
                $privacy = '';
            }
            // }
            
            $allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
            $noteskeywords = array();
            
            if ($privacy == '2') {
                if ($this->session->data['unloack_success'] == '1') {
                    // $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id
                    // . $result['notes_description'];
                    
                    if ($allkeywords) {
                        $keyImageSrc12 = array();
                        $keyname = array();
                        $keyImageSrc11 = "";
                        foreach ($allkeywords as $keyword) {
                            $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                            // $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
                            // $keyword['keyword_name'];
                            // $keyname[] = $keyword['keyword_name'];
                            // $keyname = array_unique($keyname);
                            $noteskeywords[] = array(
                                    'keyword_file_url' => $keyword['keyword_file_url']
                            );
                        }
                        
                        // $keyword_description = str_replace($keyname,
                        // $keyImageSrc12, $result['notes_description']);
                        // $keyword_description =
                        // $keyImageSrc11.'&nbsp;'.$result['notes_description'];
                        $keyword_description = $result['notes_description'];
                        
                        $notes_description = $emp_tag_id . $keyword_description;
                    } else {
                        $notes_description = $emp_tag_id . $result['notes_description'];
                    }
                } else {
                    $notes_description = $emp_tag_id;
                }
            } else {
                // $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id .
                // $result['notes_description'];
                
                if ($allkeywords) {
                    $keyImageSrc12 = array();
                    $keyname = array();
                    $keyImageSrc11 = "";
                    foreach ($allkeywords as $keyword) {
                        
                        $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                        // $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
                        // $keyword['keyword_name'];
                        // $keyname[] = $keyword['keyword_name'];
                        // $keyname = array_unique($keyname);
                        
                        $noteskeywords[] = array(
                                'keyword_file_url' => $keyword['keyword_file_url']
                        );
                    }
                    
                    // $keyword_description = str_replace($keyname,
                    // $keyImageSrc12, $result['notes_description']);
                    // $keyword_description =
                    // $keyImageSrc11.'&nbsp;'.$result['notes_description'];
                    $keyword_description = $result['notes_description'];
                    
                    $notes_description = $emp_tag_id . $keyword_description;
                } else {
                    $notes_description = $emp_tag_id . $result['notes_description'];
                }
            }
            
            // if($facilityinfo['config_noteform_status'] == '1'){
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
                        'href' => $this->url->link('form/form', '' . '&forms_design_id=' . $allform['custom_form_type'] . '&forms_id=' . $allform['forms_id'] . '&notes_id=' . $result['notes_id'], 'SSL'),
                        'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']))
                )
                ;
            }
            
            // }
            
            $notestasks = array();
            if ($result['task_type'] == '1') {
                $alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
                
                $boytotal = 0;
                $girltotal = 0;
                $generaltotal = 0;
                $residencetotal = 0;
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
                            'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added']))
                    )
                    ;
                    
                    if ($alltask['location_type'] == 'Boys') {
                        $boytotal = $boytotal + $alltask['capacity'];
                    }
                    
                    if ($alltask['location_type'] == 'Girls') {
                        $girltotal = $girltotal + $alltask['capacity'];
                    }
                    
                    if ($alltask['location_type'] == 'Inmates') {
                        $generaltotal = $generaltotal + $alltask['capacity'];
                    }
                }
                
                $residencetotal = $boytotal + $girltotal + $generaltotal;
                
                $boytotals = array();
                if ($boytotal > 0) { 
                    $boytotals[] = array( 
                            'total' => $boytotal,
                            'loc_name' => 'Boys'
                    );
                }
                
                $girltotals = array();
                if ($girltotal > 0) {
                    $girltotals[] = array(
                            'total' => $girltotal,
                            'loc_name' => 'Girls'
                    );
                }
                
                $generaltotals = array();
                if ($generaltotal > 0) {
                    $generaltotals[] = array(
                            'total' => $generaltotal,
                            'loc_name' => 'Inmates'
                    );
                }
                
                $residentstotals = array();
                if ($residencetotal > 0) {
                    $residentstotals[] = array(
                            'total' => $residencetotal,
                            'loc_name' => 'Count'
                    );
                }
            }
            
            $notesmedicationtasks = array();
            if ($result['task_type'] == '2') {
                $alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
                
                foreach ($alltmasks as $alltmask) {
                    
                    if ($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00') {
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
                            'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added']))
                    )
                    ;
                }
            }
            
            $reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
            
            $remdata = "";
            if ($reminder_info != null && $reminder_info != "") {
                $remdata = "1";
            } else {
                $remdata = "2";
            }

            	$shift_time_color = $this->model_notes_notes->getShiftColor ( $result ['notetime'], $this->customer->getId () );
            

				
			$tempFacilityDetails = $this->model_facilities_facilities->getfacilities($result['facilities_id']);

            $this->data['notess'][] = array(
                    'notes_id' => $result['notes_id'],
					'facilities_name' => $tempFacilityDetails['facility'],
                    'visitor_log' => $result['visitor_log'],
                    'is_tag' => $result['is_tag'],
                    'shift_color_value' => $shift_time_color ['shift_color_value'],
                    'form_type' => $result['form_type'],
                    'generate_report' => $result['generate_report'],
                    'is_census' => $result['is_census'],
                    'is_android' => $result['is_android'],
                    'emp_tag_id' => $alltag['emp_tag_id'],
                    'alltag' => $alltag,
                    'remdata' => $remdata,
                    'noteskeywords' => $noteskeywords,
                    'is_private' => $result['is_private'],
                    'share_notes' => $result['share_notes'],
                    'is_offline' => $result['is_offline'],
                    'review_notes' => $result['review_notes'],
                    'is_private_strike' => $result['is_private_strike'],
                    'checklist_status' => $result['checklist_status'],
                    'notes_type' => $result['notes_type'],
                    'strike_note_type' => $result['strike_note_type'],
                    'task_time' => $task_time,
                    'tag_privacy' => $privacy,
                    'incidentforms' => $forms,
                    'notestasks' => $notestasks,
                    'boytotals' => $boytotals,
                    'girltotals' => $girltotals,
                    'generaltotals' => $generaltotals,
                    'residentstotals' => $residentstotals,
                    'notesmedicationtasks' => $notesmedicationtasks,
                    'task_type' => $result['task_type'],
                    'taskadded' => $result['taskadded'],
                    'assign_to' => $result['assign_to'],
                    'highlighter_value' => $highlighterData['highlighter_value'],
                    'notes_description' => $notes_description,
                    // 'keyImageSrc' => $keyImageSrc,
                    // 'fileOpen' => $fileOpen,
                    'images' => $images,
                    'notetime' => date('h:i A', strtotime($result['notetime'])),
                    'username' => $result['user_id'],
                    'notes_pin' => $userPin,
                    'signature' => $result['signature'],
                    'text_color_cut' => $result['text_color_cut'],
                    'text_color' => $result['text_color'],
                    'note_date' => date('m-d-Y h:i A', strtotime($result['note_date'])),
                    'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                    'date_added' => date('m-d-Y h:i A', strtotime($result['date_added'])),
					'date_added3' => date('h:i A', strtotime($result['date_added'])),
                    'date_added2' => date('D F j, Y', strtotime($result['date_added'])),
                    'strike_user_name' => $result['strike_user_id'],
                    'strike_pin' => $result['strike_pin'],
                    'strike_signature' => $result['strike_signature'],
                    'strike_date_added' => date($this->language->get('date_format_short_2'), strtotime($result['strike_date_added'])),
                    'reminder_time' => $reminder_time,
                    'reminder_title' => $reminder_title,
                    'href' => $this->url->link('notes/notes/insert', '' . '&reset=1&searchdate=' . date('m-d-Y', strtotime($result['date_added'])) . $url, 'SSL')
            );
			
			
			
        }




		
		
		
		/*-------------------------------------------------------------------------------------------------------------*/
		
		
		
		
			
		$notess = $this->data['notess'];

		$custom_form_form_url=$this->data['custom_form_form_url'];
		 $medication_url=$this->data['medication_url'];
		 $form_url=$this->data['form_url'];
		 $check_list_form_url=$this->data['check_list_form_url'];
		 $updatetag_url=$this->data['updatetag_url'];
		 $censusdetail_url=$this->data['censusdetail_url'];
		$html .='<script>$(".form_insert").colorbox({iframe:true, width:"90%", height:"90%", overlayClose: false});</script>';	
		$html .='<div class="timeline" style="padding: 21px;">';
		$old = null;
		$i=1;
		
		if(count($notess)>0){
			foreach ($notess as $note) {  

		           

				if ($old != $note['date_added2']) {
					$old = $note['date_added2'];
					$html .='<div class="time-label">
									<span style="border-radius: 11px; border: 1px solid #F26B21;">'.date('D M d, Y',strtotime($old)).'</span>
								</div>';
				}  
   
   
				$bgColor = "";
				$bgColor2 = "";
				$bgColor3 = "background: #f7f7f7;";
				if ($note['highlighter_value'] != null && $note['highlighter_value'] != "") {
					$bgColor3 .= 'background-color:' . $note['highlighter_value'] . ';';
				}
				if ($note['text_color_cut'] == "1") {
					$bgColor2 .= 'text-decoration: line-through;';
				}
				
				if ($note['text_color'] != null && $note['text_color'] != "") {
					$bgColor .= 'color:' . $note['text_color'] . ';';
				}
				
				if (($note['highlighter_value'] != null && $note['highlighter_value'] != "") && ($note['text_color'] == null && $note['text_color'] == "")) {
					if ($note['highlighter_value'] == '#ffff00') {
						$bgColor3 .= 'color:#000;';
					} else 
						if ($note['highlighter_value'] == '#ffffff') {
							$bgColor3 .= 'color:#666;';
						} else {
							$bgColor3 .= 'color:#FFF;';
						}
				} 


				$html .='<div>
							<img class="notes_img" src="sites/view/digitalnotebook/image/minidashboard/notes_select.png" width="25px" height="25px" alt="Notes" title="Notes">';

								if($note['shift_color_value']) { 
									$shift_color="color :".$note['shift_color_value'];
								}else { 
									$shift_color="";
								}
							
								$html .='<div class="timeline-item">
								<span class="time" ><i style="'.$shift_color.'" class="fas fa-clock"></i> ';
								
								if($this->session->data['advance_search'] != null && $this->session->data['advance_search'] != "") {

								
									
									$html.='<a href="'.$note['href'].'" style="color: #000;">'.$note['date_added3'].'</a>';
								}else{
									$html.=$note['notetime'];
								}
								
								
								$html.='</span>
								
								<h3 style="text-align:left" class="timeline-header"> '.$note['facilities_name'].' | '.$note['username'].' | ';
                                     
                                    if($note['signature']!="" && $note['signature']!=null) {
								   $html.=' <img src="'.$note['signature'].'" height="29px">'; 

								    }

								    $html.='</h3>
								
								
								 <div class="timeline-body" style="'.$bgColor3.'">
								
								
								<!------------------------------------------------------------------------------------------>
								
								
								
								<div id="notes_text'.$note['notes_id'].'" class="_ta2" style="border: none;">	
					
					
					<div class="div_hr">
					
					<span'; 
					
					if($note['text_color_cut'] == "0"){ 
						if($note['tag_privacy'] == "2"){ 
							if($this->session->data['unloack_success'] == '1'){ 
							$html .=' onclick="selectText("'.$note['notes_id'].'","'.$note['taskadded'].'","'.$note['tag_privacy'].'")"'; 
							}
						}else{  
							$html.=' onclick="selectText("'.$note['notes_id'].'","'.$note['taskadded'].'","'.$note['tag_privacy'].'")"'; 
						} 
					} 
					
					$html.=' style="line-height: 2;'.$bgColor.$bgColor2.'" data-autoresize'.$note['notes_id'].' rows="1" cols="5" id="notes_description'.$note['notes_id'].'" class="form-control1 notes_description_cl_list ">';

					$html .=nl2br(substr($note['notes_description'],0,100));

						$html.='<span class="user_deatil">';  
					if($note['is_private'] == '1'){
						$html.=$note['username'];
						$html.='<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">('.$note['note_date'].')';
					}else{
						if($note['username'] != null && $note['username'] != "0"){ 
							$html.=$note['username'];
							if($note['notes_type'] == "2"){
								$html.='<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
							}elseif($note['notes_type'] == "1"){
								$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							}elseif($note['notes_pin'] != null && $note['notes_pin'] != ""){
								$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
							}else{
								$html.='<img src="'.$note['signature'].'" width="98px" height="29px">';
							}

							$html.='('.$note['note_date'].')';
						}
					} 
					
					if($note['is_private_strike'] == '1'){
						$html.=' &nbsp;&nbsp;&nbsp;&nbsp;'.$note['strike_user_name'];
						$html.='<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
						$html.='('.$note['strike_date_added'].')';
					}else{
						if($note['strike_user_name'] != null && $note['strike_user_name'] != "0"){
							$html.='&nbsp;&nbsp;&nbsp;&nbsp;';
							$html.=$note['strike_user_name'];
							if($note['strike_note_type'] == "1"){ 
								$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
							}else{
								if($note['strike_pin'] != null && $note['strike_pin'] != ""){
									$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
								}else{
									$html.='<img src="'.$note['strike_signature'].'" height="29px">';
								}
							}
							$html.='('.$note['strike_date_added'].')';
						}
					}
					
					if($note['reminder_time'] != null && $note['reminder_time'] != ""){
						$html.='&nbsp;&nbsp;<img src="sites/view/digitalnotebook/image/Add-Alarm.png" width="28px" height="28px">&nbsp;&nbsp;';
						$html.=$note['reminder_title'].'&nbsp;&nbsp;'.$note['reminder_time'];
					} 
				
					if($note['tag_privacy'] == "2"){
						if($this->session->data['unloack_success'] == '1'){
							if ($note['incidentforms'] != null && $note['incidentforms'] != "") {
								$i = 0;
								foreach ($note['incidentforms'] as $incidentform) {
									if ($i != 0) {
										$csspadding = "margin-left:4px;";
									} else {
										$csspadding = '';
									}
					
									if($incidentform['form_type'] == '3'){
										$html.='<a class="form_insert" href="'.$custom_form_form_url.'&forms_design_id='.$incidentform['custom_form_type'].'&forms_id='.$incidentform['forms_id'].'&notes_id='.$incidentform['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="'.$csspadding.'"></a>';
									} 
									
									if($incidentform['form_type'] == '1'){
										$html.='<a class="form_insert" href="'.$form_url.'&incidentform_id='.$incidentform['form_type_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="'.$csspadding.'"></a>';
									}
									
									if($incidentform['form_type'] == '2'){
										$html.='<a class="form_insert" href="'.$check_list_form_url.'&checklist_id='.$incidentform['form_type_id'].'&notes_id='.$incidentform['notes_id'].'"><img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px" style="'.$csspadding.'"></a>';
									}
									
									if($incidentform['incident_number'] != null && $incidentform['incident_number'] != ""){
										$html.='&nbsp;&nbsp;'.$incidentform['incident_number'];
									}
                    
									if ($incidentform['user_id'] != null && $incidentform['user_id'] != "0") {
										$html.=' &nbsp;&nbsp;&nbsp;&nbsp;'.$incidentform['user_id'];
										if($incidentform['notes_type'] == "1"){
											$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
										}else{
											if($incidentform['notes_pin'] != null && $incidentform['notes_pin'] != ""){
												$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
											}else{
												$html.='<img src="'.$incidentform['signature'].'" width="98px" height="29px">';
											}
										}
										
										if($incidentform['form_date_added'] != "" && $incidentform['form_date_added'] != ""){
											$html.='('.$incidentform['form_date_added'].')';
										}
									}
								$i++; 
								}
							}
						}
					}else{
					
						if ($note['incidentforms'] != null && $note['incidentforms'] != "") {
							$i = 0;
							foreach ($note['incidentforms'] as $incidentform) {
								if ($i != 0) {
									$csspadding = "margin-left:4px;";
								} else {
									$csspadding = '';
								}
								
								if($incidentform['form_type'] == '3'){
									$html.='<a class="form_insert" href="'.$custom_form_form_url.'&forms_design_id='.$incidentform['custom_form_type'].'&forms_id='.$incidentform['forms_id'].'&notes_id='.$incidentform['notes_id'].'">
									<img class="ffff" src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="'.$csspadding.'"></a>';
								}
					
								if($incidentform['form_type'] == '1'){
									$html.='<a class="form_insert" href="'.$form_url.'&incidentform_id='.$incidentform['form_type_id'].'">
									<img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style="'.$csspadding.'"></a>';
									
								}
					
								if($incidentform['form_type'] == '2'){
									$html.='<a class="form_insert" href="'.$check_list_form_url.'&checklist_id='.$incidentform['form_type_id'].'&notes_id='.$incidentform['notes_id'].'"><img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px" style="'.$csspadding.'"></a>';
								}
								
								if($incidentform['incident_number'] != null && $incidentform['incident_number'] != ""){
									$html.='&nbsp;&nbsp;'.$incidentform['incident_number'];
								}
								
								if ($incidentform['user_id'] != null && $incidentform['user_id'] != "0") {
                   
									$html.='&nbsp;&nbsp;'.$incidentform['user_id'];
									if($incidentform['notes_type'] == "1"){
										$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
									}else{
										if($incidentform['notes_pin'] != null && $incidentform['notes_pin'] != ""){
											$thml.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
										}else{
											$html.='<img src="'.$incidentform['signature'].'" width="98px" height="29px">';
										}
									}
									
									if($incidentform['form_date_added'] != "" && $incidentform['form_date_added'] != ""){
										$html.='('.$incidentform['form_date_added'].')';
									}
								}
								
								$i++; 
							}
						}
					}
				
					if($note['tag_privacy'] == "2"){
						if($this->session->data['unloack_success'] == '1'){
							
							if($note['is_tag'] != null && $note['is_tag'] != "0"){
								if($note['form_type'] == '2'){
									$html.='<a class="form_insert" href="'.$updatetag_url.'&tags_id='.$note['is_tag'].'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
								}
						
								if($note['form_type'] == '1'){
									$html.='<a  href="'.$medication_url.'&tags_id='.$note['is_tag'].'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
								}
							}
					
							if($note['is_census'] == "1"){
								$html.'<a class="form_insert" href="'.$censusdetail_url.'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>'; 
							}
							
							if ($note['generate_report'] == "3") {
								$html.='<a target="_blank" href="'.$bedcheck_url.'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
							}
						}
					}else{
						
						if($note['is_tag'] != null && $note['is_tag'] != "0"){
						
							if($note['form_type'] == '2'){
								
								$html.='<a class="form_insert" href="'.$updatetag_url.'>&tags_id='.$note['is_tag'].'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
							}
							
							if($note['form_type'] == '1'){
								$html.='<a href="'. $medication_url.'&tags_id='.$note['is_tag'].'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
							}
						}
						
						if($note['is_census'] == "1"){
							$html.='<a class="form_insert" href="'.$censusdetail_url.'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
						}
					
					
						if ($note['generate_report'] == "3") {
							$html.='<a target="_blank" href="'.$bedcheck_url.'&notes_id='.$note['notes_id'].'"><img src="sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" style=""></a>';
						}
					}
					
					if($note['tag_privacy'] == "2"){
						if($this->session->data['unloack_success'] == '1'){
							if ($note['images'] != null && $note['images'] != "") {
								foreach($note['images'] as $image){
									$html.='<a target="_blank" class="open_file2" href="'.$image['notes_file_url'].'">'.$image['keyImageSrc'].'</a>';
									if($image['media_user_id'] != null && $image['media_user_id'] != "0"){
										$html.='&nbsp;&nbsp;&nbsp;&nbsp;'.$image['media_user_id'];
										if($image['notes_type'] == "1"){
											$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
										}else{
											if($image['media_pin'] != null && $image['media_pin'] != ""){
												$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
											}else{
												$html.='<img src="'.$image['media_signature'].'" width="98px" height="29px">';
											} 
										}
										$html.='('.$image['media_date_added'].')';
									}
								}
							}
						}
					}else{
						if ($note['images'] != null && $note['images'] != "") {
							foreach($note['images'] as $image){
								$html.='<a target="_blank" class="open_file2" href="'.$image['notes_file_url'].'">'.$image['keyImageSrc'].'</a>';
								if($image['media_user_id'] != null && $image['media_user_id'] != "0"){
									$html.='&nbsp;&nbsp;&nbsp;&nbsp;'.$image['media_user_id'];
									if($image['notes_type'] == "1"){
										$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
									}else{
										if($image['media_pin'] != null && $image['media_pin'] != ""){
											$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
										}else{
											$html.='<img src="'.$image['media_signature'].'" width="98px" height="29px">';
										}
									}
									$html.='('.$image['media_date_added'].')';
								}
							}
						}
					}
					
					if($note['alltag']['user_id'] != null && $note['alltag']['user_id'] != ""){
						$html.='| Tags Updated By '.$note['alltag']['user_id'];
						if($note['alltag']['notes_type'] == "2"){
							$html.='<img src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px">';
						}elseif($note['alltag']['notes_type'] == "1"){
							$html.='<img src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px">';
						}elseif($note['alltag']['notes_pin'] != null && $note['alltag']['notes_pin'] != ""){
							$html.='<img src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
						}else{
							$html.='<img src="'.$note['alltag']['signature'].'" width="98px"height="29px">';
						}

						$html.='('.date($this->language->get('date_format_short_2'),strtotime($note['alltag']['date_added'])).')';
					}
				
					$html.='<div class="process_bar" id="progressbar'.$note['notes_id'].'">
							<div id="status'.$note['notes_id'].'" style="line-height: 15px; color: #fff;">0%</div></div></span>';

					$html.='<br>';
				
					if ($note['generate_report'] == "3") {
						$html.='<img src="sites/view/digitalnotebook/image/generate-Report.png" width="30px" height="30px">';	
					}
					
					if ($note['generate_report'] == "2") {
						$html.='<img src="sites/view/digitalnotebook/image/generate-Report.png" width="30px" height="30px">';
					}
									
									
					if ($note['is_census'] == "1") {
						$html.='<img src="sites/view/digitalnotebook/image/census.png" width="35px" height="35px">';	
					}
								
								
					if ($note['visitor_log'] == "1") {
						$html.='<img src="sites/view/digitalnotebook/image/Visitor-Icons.png"width="35px" height="35px">';	
					}
									
					if($note['visitor_log'] == "2"){
						$html.='<img src="sites/view/digitalnotebook/image/Visitor-Icons-grey.jpg" width="35px" height="35px">';	
					}
									
									
					if ($note['is_offline'] == "1") {
						$html.='<img src="sites/view/digitalnotebook/image/wifi.png" width="45px" height="45px">';	
					}
								
					if($note['checklist_status'] == "1"){
						if ($note['taskadded'] == "2") {
							$html.='<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">';	
						}
						
						if ($note['taskadded'] == "3") {
							$html.='<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
						}
									
									
						if ($note['taskadded'] == "4") {
							$html.='<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px">';  
						}
									
					}elseif($note['checklist_status'] == "2"){
						$html.='<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px">';
					}else{
								
						if ($note['taskadded'] == "1") {
							$html.='<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px">';  
						}
								
								
						if ($note['taskadded'] == "2") {
							$html.='<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">';	
						}
						
						if ($note['taskadded'] == "3") {
							
							$html.='<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
						}
								
							
						if ($note['taskadded'] == "4") {
							$html.='<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px">';  
						}
					}
													
					if($note['noteskeywords']){
						foreach($note['noteskeywords'] as $noteskeyword){
							$html.='<img src="'.$noteskeyword['keyword_file_url'].'" width="40px" height="40px">';
						}
					}
								
					if ($note['task_type'] != "1" && $note['task_type'] != "2") {
						if ($note['task_time'] != null && $note['task_time'] != "") {
							$html.=$note['task_time'];
						}
					}
					
				
					//$html .=nl2br(substr($note['notes_description'],0,100));
			
					if ($note['notesmedicationtasks'] != null && $note['notesmedicationtasks'] != "") {
						foreach($note['notesmedicationtasks'] as $notesmedicationtask){
							if($note['tag_privacy'] == "2"){ 
								if($this->session->data['unloack_success'] == '1'){
									$html.='<br>';
									$notesmedicationtask['drug_name']; 
									if($notesmedicationtask['signature'] != null && $notesmedicationtask['signature'] != ""){ 
										$html.='| <img width="98px" height="29px" src="'.$notesmedicationtask['signature'].'">';
									}
									
									if($notesmedicationtask['medication_file_upload'] == "0"){
										if($notesmedicationtask['media_url'] != null && $notesmedicationtask['media_url'] != ""){
											$html.='<a target="_blank" class="" href="'.$notesmedicationtask['media_url'].'"><img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" /></a>';
										}
									}
								}else{}
							}else{
								$html.='<br>';
								$html.=$notesmedicationtask['drug_name'];
								if($notesmedicationtask['signature'] != null && $notesmedicationtask['signature'] != ""){
									$html.=' | <img width="98px" height="29px" src="'.$notesmedicationtask['signature'].'">';
								}
								
								if($notesmedicationtask['medication_file_upload'] == "0"){
									if($notesmedicationtask['media_url'] != null && $notesmedicationtask['media_url'] != ""){
										$html.='<a target="_blank" class="" href="'.$notesmedicationtask['media_url'].'"> <img
										src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" /></a>';
									}
								}
							}
						}
						$html.='<br>Completed by ';
					}
				
			
					if ($note['notestasks'] != null && $note['notestasks'] != "") {
      
					
						foreach($note['notestasks'] as $notestask){ 
							$html.='<br>'.$notestask['task_content'];
						}
						
						if($note['boytotals'][0] != null && $note['boytotals'][0] != ""){
							$html.='<br>Total '.$note['boytotals'][0]['loc_name'].' : '.$note['boytotals'][0]['total'];
						}
						
						if($note['girltotals'][0] != null && $note['girltotals'][0] != ""){
							$html.='<br>Total '.$note['girltotals'][0]['loc_name'].' : ' .$note['girltotals'][0]['total'];
						}
						
						if($note['generaltotals'][0] != null && $note['generaltotals'][0] != ""){
							$html.='<br>Total '.$note['generaltotals'][0]['loc_name'].' : '.$note['generaltotals'][0]['total'];
						}
						
						if($note['residentstotals'][0] != null && $note['residentstotals'][0] != ""){
							$html.='<br>Total '.$note['residentstotals'][0]['loc_name'].' : '.$note['residentstotals'][0]['total'];
						}
					}
				
					if ($config_tag_status == '1') {
						if ($note['tag_privacy'] == "2") {
							if($this->session->data['unloack_success'] == '1'){
								$html.='<img src="sites/view/digitalnotebook/image/web140x40.png" width="35px" height="35px">';
							}else{
								$html.='<img src="sites/view/digitalnotebook/image/40x40web.png" width="35px" height="35px">';
							}
						}
					}				
				
					$html.='</span>
					
					
					
					
					
					
				

							</div></div>
								
								
								
								
								
								
								
							<!------------------------------------------------------------------------------------------>	
								

									
								</div>
							</div>
						</div>';
	
			$i++;
	
		}
		
		}else{
			
			$html.='<h6 class="message">No Data</h6>';
		}
		
		
  
		$html.='</div>';

		

		//if($pagination != null && $pagination != ""){ $html.'<div class="pagination">'.$pagination.'</div>';}
		
			
		/*----------------------------------------------------------------------------------------------------------------*/	
		
        


		$plus_url = $this->url->link ( 'notes/notes/insert', 'tags_id='.$tags_id, 'SSL' );
		
		$response['html']= $html;
		
		$response['form_name'] = ucwords('Heading Section');
		
		$response['plus_url'] = $plus_url;
		//echo '<pre>'; print_r($response); echo '</pre>';
		$this->response->setOutput ( json_encode ( $response ) );

    }
	
	



}