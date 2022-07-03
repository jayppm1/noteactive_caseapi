<?php
class Controllernotesaddinventory extends Controller {
	private $error = array ();
	public function index() {
		try {
			
			$this->language->load ( 'notes/notes' );
			
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
			
			$this->load->model ( 'notes/notes' );
			
			if ($this->customer->isLogged ()) {
				
				$this->redirect ( $this->url->link ( 'notes/notes/insert', '', 'SSL' ) );
			}

			unset ( $this->session->data ['notesdatas'] );
			unset ( $this->session->data ['text_color_cut'] );
			unset ( $this->session->data ['highlighter_id'] );
			unset ( $this->session->data ['text_color'] );
			unset ( $this->session->data ['note_date'] );
			unset ( $this->session->data ['notes_file'] );
			
			if ($this->request->get ['reset'] == '1') {
				unset ( $this->session->data ['note_date_search'] );
				unset ( $this->session->data ['note_date_from'] );
				unset ( $this->session->data ['note_date_to'] );
				unset ( $this->session->data ['keyword'] );
				// unset($this->session->data['user_id']);
				unset ( $this->session->data ['emp_tag_id'] );
				unset ( $this->session->data ['keyword_file'] );
				$this->redirect ( $this->url->link ( 'notes/notes', '' . $url, 'SSL' ) );
			}
			
			$this->data ['rediectUlr'] = $this->url->link ( 'notes/notes', '' . $url, 'SSL' );
			$this->data ['resetUrl'] = $this->url->link ( 'notes/notes', '' . '&reset=1' . $url, 'SSL' );
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/search', '' . $url2, 'SSL' );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Notes List' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNoteslist', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function autocomplete() {
		try {
			$json = array ();
			
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['username'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->load->model ( 'inventory/inventory' );

			if (isset ( $this->request->get ['limit'] )) {
			 $limit = $this->request->get ['limit'];
		    } else {
			 $limit = CONFIG_LIMIT;
		    }
			
			$data = array (
					'name' => $this->request->get ['name'],
					'start' => 0,
					'facilities_id' => $this->customer->getId (),
					'limit' => $limit 
			);
			
			$results = $this->model_inventory_inventory->getInventoryByName ( $data );
			
			
			
			
			if ($results != null && $results != "") {
				foreach ( $results as $result ) {
					
					
					$multiple_inventory=$result['multiple_inventory'];	    


                   // var_dump($multiple_inventory);	die;				

					$measurementtype = $this->model_inventory_inventory->getMeasurementValuesById ($result ); 

					$json [] = array (
							'inventory_id' => $result ['inventory_id'],
							'name' => $result ['name'],
							'description' => $result ['description'],
							'quantity' => $result ['quantity'],
							'return_type' => $result ['return_type'],
							'measurement_type' => $measurementtype ['s_name'],
							'inventorytype_id' => $result ['inventorytype_id'],
                            'multiple_inventory' => $multiple_inventory						
					);
				}
			}//die;
			
			$this->response->setOutput ( json_encode ( $json ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error inventory name  autocomplete' 
			);
			$this->model_activity_activity->addActivity ( 'userrole_autocomplete', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	protected function validateInventoryForm() {      



		if ($this->request->post ['new_module'] != null && $this->request->post ['new_module'] != "") {             

			
			foreach ( $this->request->post ['new_module'] as $key => $new_module ) {

				$this->load->model ( 'inventory/inventory' );
				$results = $this->model_inventory_inventory->getinventory ($new_module['inventory_id']);				


				if($results['return_type']!=$new_module['return_type']){
					
					$checkout = $this->model_inventory_inventory->getinventory ($new_module['inventory_id']);

					
                    if($checkout['return_type']=='3' && $checkout['checkout_quantity']!='0'){
                     
					 $this->error ['return_type'] [$key] = 'please checkin first';

					}

                   //var_dump($checkout['return_type'].' '.$checkout['checkout_quantity']);die;					
				}

				if ($new_module ['name'] == "" && $new_module ['name'] == null) {
					$this->error ['name'] [$key] = 'Required';
				}

				if ($new_module ['quantity'] == "" && $new_module ['quantity'] == null) {
					$this->error ['quantity'] [$key] = 'Required';
				}

				if ($new_module ['inventorytype_id'] == "" && $new_module ['inventorytype_id'] == null) {
					$this->error ['inventorytype_id'] [$key] = 'Required';
				}
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}

   protected function validateCheckOutInventoryForm() {
	   
	   

   	if ($this->request->post ['test_module'] == null || $this->request->post ['test_module'] == "") {  

         $this->error ['empty_check_out']  = 'There is no quantity to checkout';
   	}


   		$this->load->model ( 'facilities/online' );
     
   	   $facilityId = $this->customer->getId ();   	  

   	   $is_inventory_allow= $this->model_facilities_facilities->getfacilities($facilityId);

       if ($this->request->post ['test_module'] != null && $this->request->post ['test_module'] != "") {  	   

   	   foreach ( $this->request->post ['test_module'] as $key => $new_module ) {  	             

                 if ($new_module ['sub_quantity'] == "" || $new_module ['sub_quantity'] == "0") {			

					$this->error ['empty_sub_quantity'] [$key] = 'Please add Checkout Quantity';
					
				}

			} 
		}  	   

       //if($is_inventory_allow['config_inventory_allow']==1){
		   
		  

       	if ($this->request->post ['test_module'] != null && $this->request->post ['test_module'] != "") {			
			
			$tagsids = explode (",", $this->request->post['checkout_tags']);
		    $tdata = array ();
			
			$Selected_user=count($tagsids);				
			
			if(empty($tagsids)){
				
				if ($new_module ['quantity'] < $new_module['sub_quantity']) {			

					$this->error ['sub_quantity'] [$key] = 'Check out quantity is more than current';
					
				}
				
			}else{	
			
		          			   
					
			foreach ( $this->request->post ['test_module'] as $key => $new_module ) {                
				
				 $sub_quantity= $Selected_user * (int) $new_module ['sub_quantity'];
			    				
				if ($new_module ['quantity'] < $sub_quantity) {			

					$this->error ['sub_quantity'] [$key] = 'Check out quantity is more than current';
					
				}
				
				
			} 
		
		  }
		}
		
		
		
		
		

       //}    


		
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}


	 protected function validateCheckInInventoryForm() {   


	   if ($this->request->post ['new_module'] == null || $this->request->post ['new_module'] == "") {  
         $this->error ['empty_check_in'] = 'There is no quantity to checkin';
   	}	   	   

		if ($this->request->post ['new_module'] != null && $this->request->post ['new_module'] != "") {	

		   // $count = array_count_values($this->request->post ['new_module']);

			//$arr=array();

			
			foreach ( $this->request->post ['new_module'] as  $key => $new_module ) {


				$arr['checkin'][]=array (

				"checkin"=>$new_module ['checkin']);

                 
				if ($new_module ['checkin'] ==1) {						

				 $datas = $this->model_inventory_inventory->getCheckOutInventoryByInventoryId ($new_module['inventory_id'],$this->request->post);



				/* if($new_module['reason']=="" && ($new_module['returning_less']!="" || $new_module['not_return']!="")){

				   $this->error ['reason'] [$key] = 'Please add comment';	

				}	*/


				/* if($datas['is_minus_quantity']!=0){

				 	if($new_module['sub_quantity'] > $datas["is_minus_quantity"]){

				$this->error ['max_quantity'] [$key] = 'Check in Quantity is more ';	

				}	

				 }	else{

				 	if($new_module['sub_quantity'] > $datas["SUM(`sub_quantity`)"]){

				$this->error ['max_quantity'] [$key] = 'Check in Quantity is more ';	

				}	

				 }		*/
				

				}
				 
			}

			//var_dump($arr['checkin']);          
            //die;


		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}






	protected function validateSearchForm() {
		if (($this->request->post ['names'] == null && $this->request->post ['names'] == "") && ($this->request->post ['inventorytypeid'] == null && $this->request->post ['inventorytypeid'] == "")) {
			$this->error ['names'] = "Name is empty";
		}
		
		if ($this->request->post ['inventorytypeid'] == null && $this->request->post ['inventorytypeid'] == "") {
			$this->error ['inventorytypeid'] = "Select Inventory Type";
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function verifyInventory() {
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateVerifyInventory ()) {
			
			$url2="";

         if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
		 $url2 .= '&tags_id=' . $this->request->get['tags_id'];
		  }
		   


			if($this->request->get['medication_url']!='1'){

				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addInventory', '', 'SSL' ) ) );
			}else{			

				$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/updateMedication', '' . $url2, 'SSL' ) ) );
			}
			
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		  if($this->request->get['tags_id']!=null && $this->request->get['tags_id']!=""){

        	$this->data ['inventory_title'] = "Medical Inventory";

        }
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId (), '' );
		
		/*
		 * $url2 = "";
		 *
		 * $this->data['config_tag_status'] = $this->customer->isTag();
		 *
		 * if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
		 * $url2 = '&searchdate=' . $this->request->get['searchdate'];
		 * }
		 *
		 *
		 * if ($this->request->get['client'] != null && $this->request->get['client'] != "") {
		 * $url2 .= '&client=' . $this->request->get['client'];
		 * }
		 * if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
		 * $this->data['facilities_id_url'] = '&facilities_id=' . $this->request->get['facilities_id'];
		 * }
		 */
		
		// $this->data['action2'] = $this->url->link('notes/notes/unlockUser', '' . $url2, 'SSL');
		
		// $this->redirect();
		
		// $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/addInventory', '' . $url2, 'SSL'));
		
		// var_dump($this->data['redirect_url']);
		// die;
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
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
		
		if (isset ( $this->error ['notes_pin'] )) {
			$this->data ['error_notes_pin'] = $this->error ['notes_pin'];
		} else {
			$this->data ['error_notes_pin'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		
		if (isset ( $this->request->post ['notes_pin'] )) {
			$this->data ['notes_pin'] = $this->request->post ['notes_pin'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['notes_pin'] = $notes_info ['notes_pin'];
		} else {
			$this->data ['notes_pin'] = '';
		}
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} elseif (! empty ( $notes_info )) {
			$this->data ['user_id'] = $notes_info ['user_id'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		 $url2="";

          if ($this->request->get['tags_medication_details_id'] != null && $this->request->get['tags_medication_details_id'] != "") {
		 $url2 .= '&tags_medication_details_id=' . $this->request->get['tags_medication_details_id'];
		  }

    if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
		 $url2 .= '&tags_id=' . $this->request->get['tags_id'];
		  }  
         
		 

		$this->data ['action'] = $this->url->link ( 'notes/addInventory/verifyInventory', $url2, true );
		
		
		
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/verify_inventory.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function validateVerifyInventory() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
		
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$this->load->model ( 'user/user_group' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			$user_group_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = $this->language->get ('error_required' );
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
				$this->error ['user_id'] = $this->language->get ( 'error_customer' );
			}
			
			if ($this->request->get ['client'] == "1") {
				
				if (! empty ( $user_info ['user_group_id'] )) {
					$this->load->model ( 'user/user_group' );
					$user_group_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					if (! empty ( $user_group_info )) {
						$this->session->data ['show_hidden_info'] = $user_group_info ['show_hidden_info'];
					}
				}
			}
		}
		
		if ($this->request->post ['notes_pin'] == '') {
			$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
		}
		if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
			$this->load->model ( 'user/user' );
			
			//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
				$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
			}else{
				$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
			}
			
			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
				$this->error ['notes_pin'] = $this->language->get ( 'error_exists' );
			}
			
			if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
				$this->error ['notes_pin'] = $this->language->get ( 'error_exists' );
			}
			if (($this->request->post ['notes_pin'] == $user_info ['user_pin'])) {
				if (($user_group_info ['inventory_permission'] != "1")) {
					$this->error ['notes_pin'] = $this->language->get ( 'error_authorization' );
				}
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function addInventory() {    	
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();

		$this->document->setTitle ( 'Inventory' );
		
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'inventory/inventorytype' );	
		$this->load->model ( 'inventory/inventory' );
		
		
		
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
		
		
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];

		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );

        


		$this->data ['inventorytypes'] = $this->model_inventory_inventorytype->getinventorys ( $data2 );

		$measurementtypes= $this->model_inventory_inventory->getMeasurementValues ($customer_info['activecustomer_id']);


		foreach ($measurementtypes as $measurementtype) {
			$this->data ['measurementtypes'] []  = array(
				'customlistvalues_id' => $measurementtype['n_measurement_id'],
				'customlistvalues_name' => $measurementtype['s_name']);
		}

		
		
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$this->data ['current_time'] = date ( 'h:i A' );
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			
			$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
		}
		
		/*
		 * $this->load->model('setting/tags');
		 * $taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
		 */
		
		if (isset ( $this->request->post ['type'] )) {
			$this->data ['type'] = $this->request->post ['type'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['type'] = $taginfo ['type'];
		} else {
			$this->data ['type'] = '';
		}
		
		
		
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
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateInventoryForm ()) {			
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $tags_id;
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['type'] = 'UpdateInvntoryForm';
			
			$archive_inventory_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			
			$url2 = "";
			
			$this->session->data ['success_add_form'] = 'Inventory added successfully!';
			
			
			
			$url2 .= '&archive_inventory_id=' . $archive_inventory_id;

			if ($this->request->get ['searchfilter'] != null && $this->request->get ['searchfilter'] != "") {
			$this->data ['searchfilter']= $this->request->get ['searchfilter'];
			 $url2 .='&searchfilter=' . $this->request->get ['searchfilter'];
		    }

		    if ($this->request->get ['inventorytypeid'] != null && $this->request->get ['inventorytypeid'] != "") {
			$url2 .= '&inventorytypeid=' . $this->request->get ['inventorytypeid'];
		    }

		    
			
			// $url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			
			$this->redirect ( $this->url->link ( 'notes/addInventory/addInventory', '' . $url2, 'SSL' ) );
		}

        $url4 = '';     
		
		
		
		if ($this->session->data['inventory_username'] != null && $this->session->data['inventory_username'] != "") {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser($this->session->data['inventory_username']);
			$url4 .= '&user_id=' . $user_info ['username'];
			$url4 .= '&user_id1=' . $user_info ['user_id'];
			
		
		 
		 if ($user_info ['user_group_id'] != null && $user_info ['user_group_id'] != "") {
		    $this->load->model ( 'user/user_group' );
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId ()  );
			$unique_id = $facility ['customer_key'];

			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
           
            $inventory_data=unserialize($customer_info['setting_data']);
			$activecustomer_id = $customer_info ['activecustomer_id'];		
			
			$checkin_inv_user_role_array = $inventory_data['checkin_inv_user_role'];
	  
	        $add_inv_user_role_array = $inventory_data['add_inv_user_role'];
			
			$delete_inv_user_role_array = $inventory_data['delete_inv_user_role'];
			
			$checkout_inv_user_role_array = $inventory_data['checkout_inv_user_role'];
	   
	       // $user_group_detail = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );   
	     }	   
		 
		 
		   if(in_array($user_info ['user_group_id'], $checkin_inv_user_role_array)){
			
            $checkin_inv='1';			
				
			}else{
				
			$checkin_inv='0';

			}
            if(in_array($user_info ['user_group_id'], $add_inv_user_role_array)){
			
            $add_inv='1';			
				
			}else{
				
			$add_inv='0';

			}
            if(in_array($user_info ['user_group_id'], $delete_inv_user_role_array)){
			
            $delete_inv='1';			
				
			}else{
				
			$delete_inv='0';

			}
            if(in_array($user_info ['user_group_id'], $checkout_inv_user_role_array)){
			
            $checkout_inv='1';			
				
			}else{
				
			$checkout_inv='0';

			}
			
		   if($checkout_inv=='0' && $delete_inv=='0' &&  $add_inv=='0' && $checkin_inv=='0'){
            
			$this->data ['checkin_inventory']='1';
			$this->data ['add_inventory']='1';
			$this->data ['delete_inventory']='1';
			$this->data ['checkout_inventory']='1';
			
			
			}else{


          				
		 
		 
		    if(in_array($user_info ['user_group_id'], $checkin_inv_user_role_array)){
			
            $this->data ['checkin_inventory']='1';			
				
			}
			
			
            if(in_array($user_info ['user_group_id'], $add_inv_user_role_array)){
			
            $this->data ['add_inventory']='1';		
				
			}
            if(in_array($user_info ['user_group_id'], $delete_inv_user_role_array)){
			
             $this->data ['delete_inventory']='1';				
				
			}
			
            if(in_array($user_info ['user_group_id'], $checkout_inv_user_role_array)){
			
             $this->data ['checkout_inventory']='1';   		
				
			}
			}
			
		} 		
		
		$this->data ['checkinurl']=$this->url->link ( 'notes/addInventory/checkInInventory&openinventory=3', ''.$url4, 'SSL' );
		
		$this->data ['checkouturl']=$this->url->link ( 'notes/addInventory/checkOutInventory&openinventory=2', ''.$url4, 'SSL' );
		
		
		$url2 = "";
		
		if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
			$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
		}

		if ($this->request->get ['inventorytypeid'] != null && $this->request->get ['inventorytypeid'] != "") {
			$url2 .= '&inventorytypeid=' . $this->request->get ['inventorytypeid'];
		}

		if ($this->request->get ['searchfilter'] != null && $this->request->get ['searchfilter'] != "") {
			$this->data ['searchfilter']=$this->request->get ['searchfilter'];
			 $url2 .='&searchfilter=' . $this->request->get ['searchfilter'];
		}
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {

			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addinventorySign2', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addinventorySign2', '' . $url2, 'SSL' ) );
			
			/*$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&addinventory=1', '' . $url2, 'SSL' ) );
			
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&addinventory=2', '' . $url2, 'SSL' ) );*/
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addinventorySign2', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addinventorySign2', '' . $url2, 'SSL' ) );
		}
		//var_dump($this->data['searchfilter']);die;
		
		if (isset ( $this->request->post ['new_module'] )) {			
			
			$this->data ['inventorys'] = $this->request->post ['new_module'];
		} else {
			
			$data = array ();
			$data = array (
					'status' => 1,
					'facilities_id' => $facilities_id,
					'inventorytype_id' => $this->request->get ['inventorytypeid'],
					'inventory_name' => $this->request->get ['inventory_name'] 
			);
			
			
			
			$results = $this->model_inventory_inventory->getinventorys ( $data );		

			$checkout_quantity="";
			foreach ( $results as $result )

	 {

            $requre_return_checkout_quantity=$this->model_inventory_inventory->getTotalSubQuantity($result ['inventory_id']); 

            //var_dump($requre_return_checkout_quantity); 		        


			if($requre_return_checkout_quantity['returning_less']!=""){



			if($requre_return_checkout_quantity['COUNT(user_id)']==1){
			    $checkout_quantity=$requre_return_checkout_quantity['returning_less'];
			}else{

			    $checkout_quantity=$requre_return_checkout_quantity["MAX(`sub_quantity`)"];

			}

           

             }else{

             
             	$checkout_quantity=$requre_return_checkout_quantity['SUM(`sub_quantity`)'];

             }

           

             if($result['return_type']=="3"){
             	$set_quantity=$result['return_type_quantity'];
             }else{
                $set_quantity=$result ['quantity'];
             }           
				
				$this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],					
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],
						'status' => $result ['status'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],
						'check_out_quantity' => $result['checkout_quantity'],
						'description' => $result ['description'],
						'measurement_type' => $result ['measurement_type'] 
				);
				
			}
				 
		}	
	
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		if (isset ( $this->request->get ['inventorytypeid'] )) {
			$url .= '&inventorytypeid=' . $this->request->get ['inventorytypeid'];
		}
		if (isset ( $this->request->get ['inventory_name'] )) {
			$url .= '&inventory_name=' . $this->request->get ['inventory_name'];
		}
		
		$this->data ['inventorytypeid'] = $this->request->get ['inventorytypeid'];
		$this->data ['inventory_name'] = $this->request->get ['inventory_name'];
		
		$Total_inventory = count ( $this->data ['inventorys'] );
		// var_dump( $customer_total);
		// die;
		
		$pagination = new Pagination ();
		$pagination->total = $Total_inventory;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_admin_limit' );
		// $pagination->limit = '6';
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'notes/addinventory/addinventory', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		$this->data ['pagination'] = $pagination->render ();
		
		// var_dump($this->data['pagination']);
		// die;
		
		if (isset ( $this->request->post ['name'] )) {
			$this->data ['name'] = $this->request->post ['name'];
		} else {
			$this->data ['name'] = '';
		}
		
		if (isset ( $this->request->post ['deleteids'] )) {
			$this->data ['deleteids'] = $this->request->post ['deleteids'];
		} else {
			$this->data ['deleteids'] = '';
		}
		
		
		
		if (isset ( $this->request->post ['inventorytype_id'] )) {
			$this->data ['inventorytype_id'] = $this->request->post ['inventorytype_id'];
		} else {
			$this->data ['inventorytype_id'] = '';
		}
		
		if (isset ( $this->request->post ['type'] )) {
			$this->data ['type'] = $this->request->post ['type'];
		} else {
			$this->data ['type'] = '';
		}
		
		if (isset ( $this->request->post ['maintenance'] )) {
			$this->data ['maintenance'] = $this->request->post ['maintenance'];
		} else {
			$this->data ['maintenance'] = '';
		}
		
		if (isset ( $this->request->post ['status'] )) {
			$this->data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $customer_info )) {
			$this->data ['status'] = $customer_info ['status'];
		} else {
			$this->data ['status'] = 1;
		}
		
		if (isset ( $this->request->post ['description'] )) {
			$this->data ['description'] = $this->request->post ['description'];
		} else {
			$this->data ['description'] = '';
		}
		
		if (isset ( $this->request->post ['quantity'] )) {
			$this->data ['quantity'] = $this->request->post ['quantity'];
		} else {
			$this->data ['quantity'] = 0;
		}
		
		if (isset ( $this->request->post ['return_type'] )) {
			$this->data ['return_type'] = $this->request->post ['return_type'];
		} else {
			$this->data ['return_type'] = '';
		}

		/*if (isset ( $this->request->post ['inventorytypeid'] )) {
			$this->data ['inventorytypeid'] = $this->request->post ['inventorytypeid'];
		} else {
			$this->data ['inventorytypeid'] = '';
		}*/
		
		if (isset ( $this->request->post ['measurement_type'] )) {
			$this->data ['measurement_type'] = $this->request->post ['measurement_type'];
		} else {
			$this->data ['measurement_type'] = '';
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		if (isset ( $this->session->data ['success_add_form1'] )) {
			$this->data ['success_msg'] = $this->session->data ['success_add_form1'];
			
			unset ( $this->session->data ['success_add_form1'] );
		} else {
			$this->data ['success_msg'] = '';
		}
		
		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['name'] )) {
			$this->data ['error_name'] = $this->error ['name'];
		} else {
			$this->data ['error_name'] = array ();
			;
		}

		if (isset ( $this->error ['inventorytype_id'] )) {
			$this->data ['error_inventorytype_id'] = $this->error ['inventorytype_id'];
		} else {
			$this->data ['error_inventorytype_id'] = array ();
			;
		}

		if (isset ( $this->error ['quantity'] )) {
			$this->data ['error_quantity'] = $this->error ['quantity'];
		} else {
			$this->data ['error_quantity'] = array ();
			;
		}


      if (isset ( $this->error ['return_type'] )) {
			$this->data ['error_return_type'] = $this->error ['return_type'];
		} else {
			$this->data ['error_return_type'] = array ();
			;
		}

		
		if (isset ( $this->error ['names'] )) {
			$this->data ['error_names'] = $this->error ['names'];
		} else {
			$this->data ['error_names'] = array ();
			;
		}
		
		
		
		$url2 = "";
		$url3 = "";
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			$this->data ['is_archive'] = $this->request->get ['is_archive'];
		}
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		//$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['updatenotes_id'] = $notes_id;

		$this->data ['reset_url'] = $this->url->link ( 'notes/addInventory/addInventory','' , true );
		
		$this->data ['action'] = $this->url->link ( 'notes/addInventory/addInventory', $url2, true );

		$this->data ['action2'] = $this->url->link ( 'notes/addInventory/addInventory', $url2, true );
		
		$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' , 'SSL' ) );
		
		$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addInventory', '' . $url3, 'SSL' ) );
		
		// $this->data['autosearch'] = $this->request->get['autosearch'];
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/addInventory.php';
		
		$this->children = array (
				'common/headerclient' 
		);
		$this->response->setOutput ( $this->render () );
		// var_dump($this->data);
		// die;
	}
	public function addinventorySign2() {      		


		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			
				
			
			$this->load->model ( 'api/temporary' );
			$this->load->model ( 'inventory/inventory' );
			$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_inventory_id'] );
			
			$tempdata = array ();
			$tempdata = unserialize ( $temporary_info ['data'] );

			
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
			
			$archive_inventory_id = $this->model_inventory_inventory->addAllInventory ( $tempdata, $facilities_id );
			
			$this->load->model ( 'inventory/inventory' );
			
			$tdata = array ();			
			$tdata ['archive_inventory_id'] = $this->request->get ['archive_inventory_id'];
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();

			
			
			$notes_id = $this->model_inventory_inventory->addInventorynote ( $this->request->post, $tdata );
			$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_inventory_id'] );

			$this->session->data ['success2'] = '1';
			$this->session->data ['success_add_form1'] = 'Inventory added successfully!';
			
			
			
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			
			
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
			}			

			$url2.='&success_add_form=1';
			$url2.='&success_add_form1=1';
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addInventory', '' . $url2, 'SSL' ) ) );


		}

		 
			
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId (), '' );
		
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
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		
		if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
			$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addinventorySign2', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/addInventory', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
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
		
		
		if (isset ( $this->session->data ['username_confirm'] )) {
			
			$this->session->data ['username_confirm'] = $this->session->data ['username_confirm'];			
			
		} else {
			$this->session->data ['username_confirm'] = '';
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
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
		} elseif (! empty ( $this->session->data ['inventory_username'] )) {
			$this->data ['user_id'] = $this->session->data ['inventory_username'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		
		
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
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
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	
	protected function validateForm23() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
		
		if ($this->request->post ['user_id'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
			
			if (empty ( $user_info )) {
				$this->error ['user_id'] = $this->language->get ( 'error_required' );
			}
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
				$this->error ['user_id'] = $this->language->get ( 'error_customer' );
			}
		}
		
		if ($this->request->post ['select_one'] == '') {
			$this->error ['select_one'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['select_one'] == '1') {
			if ($this->request->post ['notes_pin'] == '') {
				$this->error ['notes_pin'] = $this->language->get ( 'error_required' );
			}
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$this->error ['warning'] = $this->language->get ( 'error_exists' );
				}
			}
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}	
	
      public function CheckOutInventory() {	   
	   
		$this->load->model ( 'facilities/online' );
		$datafa = array ();	

		$this->document->setTitle ( 'Check out' );
		
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ($datafa );
		
		$this->language->load ( 'notes/notes' );
		$this->language->load ( 'user/user' );
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'inventory/inventorytype' );
		
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
		$data = array ();
		$data = array (
				'status' => 1,
				'facilities_id' => $facilities_id 
		);		 

		
		$this->load->model ( 'inventory/inventory' );
		$tempdata = "";
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && ($_POST ['set_user'] == 'set_user') && $this->checkinValidation ()) {
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'inventory/inventory' );
			$notes_info = "";
			
			$timezone_name = $this->customer->isTimezone ();
			$notes_info = $this->model_notes_notes->getformnotesbyUser ( $this->request->post, $this->customer->getId (), $timezone_name );
			
			// var_dump($notes_info['notes_id']);
			
			$form_info = $this->model_form_form->getformdesign ( $notes_info ['notes_id'] );
			
			$tempdata = unserialize ( $form_info ['design_forms'] );
		}
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$this->data ['current_time'] = date ( 'h:i A' );


		$user_id="";
		
		if ($this->session->data['inventory_username'] != null && $this->session->data['inventory_username'] != "") {
			
			$user_info = $this->model_user_user->getUser($this->session->data['inventory_username']);
			$this->data ['user_id'] = $user_info['username'];
		} else if ($user_id!="" && $user_id!="") {
			$this->data ['user_id'] = $user_id;
		}else {
			$this->data ['user_id'] = '';
		}	
		
		
		if ($this->request->get ['user_id1'] != null && $this->request->get ['user_id1'] != "") {		
			
			$this->data ['user_id1'] = $this->request->get ['user_id1'];
		}else if($this->session->data['inventory_username']!=null && $this->session->data['inventory_username']!=""){
			
			$user_info = $this->model_user_user->getUser($this->session->data['inventory_username']);
			
			$this->data ['user_id1'] = $user_info ['user_id'];
			
		}

		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {

			
			$notesData = $this->model_inventory_inventory->getInventoryByNote ($this->request->get,$datafa );


			
			foreach ( $notesData as $result ) {							

				$user_info = $this->model_user_user->getUser($result['user_id']);

					$tag_info = $this->model_setting_tags->getTag ( $result ['tags_id'] );

					if($tag_info){

						$client_name=$tag_info['emp_first_name']." ".$tag_info['emp_last_name'];

					}

					if($result['checkouttpye_id']!="" && $result['checkouttpye_id']!=null){

					$this->data ['checkouttpye_id']=$result['checkouttpye_id'];

				}

				if($result['tags_id']!="" && $result['tags_id']!=null){

					$this->data ['tags_id']=$result['tags_id'];

					$this->data ['client_name']=$client_name;

				}



				$user_id=$user_info['username'];
			

				$this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],	
						'status' => $result ['status'],
						'sub_quantity' => $result ['note_quantity'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],						
						'description' => $result ['description'],						
						'measurement_type' => $result ['measurement_type'],
						'user_id' => $user_id
				);				
				
			}

			
		}

		if (isset ( $this->request->post ['test_module'] )) {		
	     		
	      $this->data ['inventorys'] = $this->request->post ['test_module'];
		} else if($this->request->get ['checkinventorys'] !="" && $this->request->get ['checkinventorys'] !=null){
			
			$checkinventorys=$this->request->get ['checkinventorys'];
			
			$inventoryArray = explode(',', $checkinventorys);			
			
			foreach($inventoryArray as $inventory){
				
				$datas[]=$inventory;
				
				$result=$this->model_inventory_inventory->getinventory($inventory);
				$measurementtype = $this->model_inventory_inventory->getMeasurementValuesById ($result  ); 
				$multiple_inventory[]=$result['multiple_inventory'];
				
				/*$this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],	
						'status' => $result ['status'],
						'sub_quantity' => $result ['sub_quantity'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],						
						'description' => $result ['description'],						
						'measurement_type' => $measurementtype ['customlistvalues_name'],
						'user_id' => $user_id
				);	*/
				
			}
			
		
            foreach($multiple_inventory as $inventory){
				
			$multiple_inventory_array=explode(",",$inventory);
			
            foreach($multiple_inventory_array as $inventory_data){
				
				$datas[]=$inventory_data;
				
			if($inventory_data!=""){	
				
			$result=$this->model_inventory_inventory->getinventory($inventory_data);
            $measurementtype = $this->model_inventory_inventory->getMeasurementValuesById ($result  ); 
             
			 /*$this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],	
						'status' => $result ['status'],
						'sub_quantity' => $result ['sub_quantity'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],						
						'description' => $result ['description'],						
						'measurement_type' => $measurementtype ['customlistvalues_name'],
						'user_id' => $user_id
				);*/
			}
			}		 			
				
			}

              $final_inventory=array_unique($datas);			
		  
			foreach($final_inventory as $inventory_data){
				
				
				
			 if($inventory_data!=""){	
				
			$result=$this->model_inventory_inventory->getinventory($inventory_data);
            $measurementtype = $this->model_inventory_inventory->getMeasurementValuesById ($result  ); 
             
			 $this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],	
						'status' => $result ['status'],
						'sub_quantity' => $result ['sub_quantity'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],						
						'description' => $result ['description'],						
						'measurement_type' => $measurementtype ['name'],
						'user_id' => $user_id
				);
			}
		}
			
		}			


		if (isset ( $this->request->get ['notes_id'] )) {
			$this->data ['show_name'] = $this->request->get ['notes_id'];
		}  else {
			$this->data ['show_name'] = '';
		}

		if (isset ( $this->request->post ['inventory_name'] )) {

			$this->data ['inventory_name'] = $this->request->post ['inventory_name'];
		}  else {
			$this->data ['inventory_name'] = '';
		}
		 
		 
		
		if (isset ( $this->request->post ['checkout_tags'] )) {
			
			
			$this->load->model ( 'setting/tags' );
			
			$checkout_tags=$this->request->post ['checkout_tags'];

			$this->data ['checkout_tags'] = $this->request->post ['checkout_tags'];		
			
		    $tags_array= explode(',', $checkout_tags);	
			
		   
		   foreach($tags_array as $tags_id){
			   
			   $tagsdata = $this->model_setting_tags->getTag ($tags_id );		   
			   
			   $this->data ['selected_tags'] [] = array (
						'tags_id' => $tagsdata ['tags_id'],
						'name' => $tagsdata ['emp_first_name'].' '.$tagsdata ['emp_last_name']
						
				);	
			   
			   
		   }	
           
           		   
		   
			
			
		}  else {
			$this->data ['checkout_tags'] = '';
		}

		if (isset ( $this->request->post ['checkouttpye_id'] )) {

			$this->data ['checkouttpye_id'] = $this->request->post ['checkouttpye_id'];
		}  else if($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {

			$notesData = $this->model_inventory_inventory->getInventoryByNote ($this->request->get,$datafa );

				foreach ( $notesData as $result ) {

				if($result['checkouttpye_id']!="" && $result['checkouttpye_id']!=null){

					$this->data ['checkouttpye_id']=$result['checkouttpye_id'];
				}	
			
		}
		}else{

			$this->data ['checkouttpye_id'] = '';

		}


		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );

		if (isset ( $this->request->post ['tags_id'] )) {

			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} else if($this->request->get ['tags_id']!=null && $this->request->get ['tags_id']!=""){

			$this->data ['tags_id'] = $tag_info ['tags_id'];

		}  else {
			$this->data ['tags_id'] = '';
		}		



		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			
			$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
		}		
		
		/*
		 * $this->load->model('setting/tags');
		 * $taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
		 */
		
		if (isset ( $this->request->post ['type'] )) {
			$this->data ['type'] = $this->request->post ['type'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['type'] = $taginfo ['type'];
		} else {
			$this->data ['type'] = '';
		}        

		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST' && $_POST ['search'] != 'search' && $_POST ['set_user'] != 'set_user' && $_POST ['reset'] != 'reset') && $this->validateCheckOutInventoryForm()) {
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $tags_id;
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['type'] = 'checkoutInventoryForm';

			//print_r(json_encode($this->request->post));
			//die;
			
			$archive_inventory_id = $this->model_api_temporary->addtemporary ( $this->request->post, $tdata );
			
			$url2 = "";
			
			$this->session->data ['success_add_form'] = 'Inventory added successfully!';
			
			$url2 .= '&archive_inventory_id=' . $archive_inventory_id;
			
			// $url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			
			$this->redirect ( $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url2, 'SSL' ) );
		}

		if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		
		$url2 = "";
		
		if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
			$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
		}
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		if ($this->request->post ['checkout_tags'] != null && $this->request->post ['checkout_tags'] != "") {
			$url2 .= '&getAlltagsids=' . $this->request->post ['checkout_tags'];
		}
		
		$this->data ['alltags_url'] = $this->url->link ( 'notes/notes/alltags', '' . $url2, 'SSL' );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {
			
		/*	$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&CheckOutInventory=1', '' . $url2, 'SSL' ) );
			
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&CheckOutInventory=2', '' . $url2, 'SSL' ) );*/

			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventorySign2&CheckOutInventory=1', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventorySign2&CheckOutInventory=2', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventorySign2&CheckOutInventory=1', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventorySign2&CheckOutInventory=2', '' . $url2, 'SSL' ) );
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if (isset ( $this->request->get ['inventorytypeid'] )) {
			$url .= '&inventorytypeid=' . $this->request->get ['inventorytypeid'];
		}
		if (isset ( $this->request->get ['inventory_name'] )) {
			$url .= '&inventory_name=' . $this->request->get ['inventory_name'];
		}
		
		$this->data ['inventorytypeid'] = $this->request->get ['inventorytypeid'];
		$this->data ['inventory_name'] = $this->request->get ['inventory_name'];
		
		$Total_inventory = count ( $this->data ['inventorys'] );
		// var_dump( $customer_total);
		// die;
		
		$pagination = new Pagination ();
		$pagination->total = $Total_inventory;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_admin_limit' );
		// $pagination->limit = '6';
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'notes/addinventory/addinventory', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		$this->data ['pagination'] = $pagination->render ();
		
		// var_dump($this->data['pagination']);
		// die;
		
		if (isset ( $this->request->post ['name'] )) {
			$this->data ['name'] = $this->request->post ['name'];
		} else {
			$this->data ['name'] = '';
		}

		if (isset ( $this->request->post ['inventory_id'] )) {
			$this->data ['inventory_id'] = $this->request->post ['inventory_id'];
		} else {
			$this->data ['inventory_id'] = '';
		}

		if (isset ( $this->request->post ['description'] )) {
			$this->data ['description'] = $this->request->post ['description'];
		} else {
			$this->data ['description'] = '';
		}
		
		if ($user_id!="" && $user_id!="") {
			$this->data ['user_id'] = $user_id;
		} else {
			$this->data ['user_id'] = '';
		}

		if (isset ( $this->request->post ['hidden_user_id'] )) {
			$this->data ['hidden_user_id'] = $this->request->post ['hidden_user_id'];
		} else {
			$this->data ['hidden_user_id'] = '';
		}
		
		if (isset ( $this->request->post ['measurement_type'] )) {
			$this->data ['measurement_type'] = $this->request->post ['measurement_type'];
		} else {
			$this->data ['measurement_type'] = '';
		}
		
		/*if (isset ( $this->request->post ['type'] )) {
			$this->data ['type'] = $this->request->post ['type'];
		} else {
			$this->data ['type'] = '';
		}*/

		if (isset ( $this->request->post ['quantity'] )) {
			$this->data ['quantity'] = $this->request->post ['quantity'];
		} else {
			$this->data ['quantity'] = '';
		}


		if (isset ( $this->request->post ['sub_quantity'] )) {
			$this->data ['sub_quantity'] = $this->request->post ['sub_quantity'];
		} else {
			$this->data ['sub_quantity'] = '';
		}

       
		
		/*if (isset ( $this->request->post ['status'] )) {
			$this->data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $customer_info )) {
			$this->data ['status'] = $customer_info ['status'];
		} else {
			$this->data ['status'] = 1;
		}
		*/	
		
		if (isset ( $this->request->post ['measurement_type'] )) {
			$this->data ['measurement_type'] = $this->request->post ['measurement_type'];
		} else {
			$this->data ['measurement_type'] = '';
		}

		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		
		
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = array ();
			;
		}

		if (isset ( $this->error ['empty_check_out'] )) {
			$this->data ['error_empty_check_out'] = $this->error ['empty_check_out'];
		} else {
			$this->data ['error_empty_check_out'] = "";
			;
		}

			
		
      
        if (isset ( $this->error ['sub_quantity'] )) {
			$this->data ['error_sub_quantity'] = $this->error ['sub_quantity'];			
		} else {
			$this->data ['error_sub_quantity'] =  array ();
		}

		if (isset ( $this->error ['empty_sub_quantity'] )) {

			$this->data ['error_empty_quantity'] = $this->error ['empty_sub_quantity'];			
		} else {
			$this->data ['error_empty_quantity'] =  array ();
		}


		
		$url2 = "";
		$url3 = "";
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			$this->data ['is_archive'] = $this->request->get ['is_archive'];
		}
		
		if ($this->request->get ['user_id'] != null && $this->request->get ['user_id'] != "") {
			$url2 .= '&user_id=' . $this->request->get ['user_id'];
			$this->data ['user_id'] = $this->request->get ['user_id'];
		}

		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		
		}
		if ($this->request->get ['user_id1'] != null && $this->request->get ['user_id1'] != "") {
			$url2 .= '&user_id1=' . $this->request->get ['user_id1'];
			$this->data ['user_id1'] = $this->request->get ['user_id1'];
		}
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}


		
		//$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );
		
		// $this->data['updatenotes_id'] = $notes_id;
		
		$this->data ['action'] = $this->url->link ( 'notes/addInventory/CheckOutInventory', $url2, true );
		
		$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
		$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url3, 'SSL' ) );
		
		// $this->data['autosearch'] = $this->request->get['autosearch'];
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/CheckOutInventory.php';
		
		$this->children = array (
				'common/headerclient' 
		);
		$this->response->setOutput ( $this->render () );
		// var_dump($this->data);
		// die;
	}
	public function CheckInInventorySign2() { 



		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		$this->load->model ( 'inventory/inventory' );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$this->load->model ( 'api/temporary' );
			$this->load->model ( 'inventory/inventory' );
			$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_inventory_id'] );
			
			$tempdata = array ();
			$tempdata = unserialize ( $temporary_info ['data'] );
			
			foreach ( $tempdata ['new_module'] as $inventorydata ) {
				
			  $tags_id[]=$inventorydata['tags_id'];         		
				
			}
			
			foreach ( $tempdata ['new_module'] as $inventorydata ) {
				
			 if($inventorydata['checkin']=='1'){


                   $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);
                    $pre_notes_quantity=$this->model_inventory_inventory->getinventoryByInventoryNotesId ( $inventorydata['inventory_notes_id']);					
								 
					
					$updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];	
                    
					$update_inventory_quantity=( int ) $pre_notes_quantity['sub_quantity'] - ( int ) $inventorydata['sub_quantity'] - ( int ) $inventorydata['not_return'];
	                
					$archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $tempdata , $updated_quantity,$checkless);

					$archive_inventory_id = $this->model_inventory_inventory->updateSubQuantity ( $inventorydata,$update_inventory_quantity);
					
					
					$results = $this->model_inventory_inventory->getinventory ($inventorydata['inventory_id']); 				

					$pre_checkout_quantity=$results['checkout_quantity'];

					$checkout_quantity= (int) $pre_checkout_quantity - (int) $inventorydata['sub_quantity'];

					$this->db->query ("UPDATE `" . DB_PREFIX . "inventory` SET checkout_quantity = '".$checkout_quantity."' WHERE  inventory_id = '" . $this->db->escape ( $inventorydata ['inventory_id'] ) . "' ");
									 
				
				$this->load->model ( 'inventory/inventory' );
			
			   $tdata = array ();
			   $tdata ['tags_id'] = $inventorydata['tags_id'];
			   $tdata ['archive_inventory_id'] = $archive_inventory_id;
			   $tdata ['facilities_id'] = $this->customer->getId ();
			   $tdata ['facilitytimezone'] = $this->customer->isTimezone ();
               $tdata['inventory']=' | '.$inventorydata['name']." ".$inventorydata['sub_quantity']." ".$inventorydata['measurement_type'];			   

			
			    $notes_id = $this->model_inventory_inventory->checkInInventoyNote ( $this->request->post, $tdata, $tempdata );
			
			
			if ($facilities_id) {
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $facility ['customer_key'] );
				
				$customer_key = $customer_info ['activecustomer_id'];
				$unique_id = $customer_info ['customer_key'];
			}
			
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		    $date_added = ( string ) $noteDate;
			
			
			
			
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "notes_by_inventory` SET name = '" . $this->db->escape ( $inventorydata ['name'] ) . "',type = '" . $this->db->escape ( $inventorydata ['checkin'] ) . "', inventorytype_id = '" . $this->db->escape ( $inventorydata ['inventorytype_id'] ) . "', inventory_id = '" . $this->db->escape ( $inventorydata ['inventory_id'] ) . "', status = '" . $this->db->escape ( $inventorydata ['status'] ) . "', maintenance = '" . $this->db->escape ( $inventorydata ['maintenance'] ) . "', description = '" . $this->db->escape ( $inventorydata ['description'] ) . "', quantity = '" . $this->db->escape ( $inventorydata ['quantity'] ) . "',return_type = '" . $this->db->escape ( $inventorydata ['return_type'] ) . "', measurement_type = '" . $this->db->escape ( $inventorydata ['measurement_type'] ) . "',user_id = '" . $this->db->escape ( $tempdata ['hidden_user_id'] ) . "',tags_id = '" . $this->db->escape ( $inventorydata ['tags_id'] ) . "',	is_minus_quantity = '" . $is_returning_less  . "',notes_id = '" . $this->db->escape ( $notes_id ) . "',customer_key = '" . $customer_key . "',unique_id = '" . $unique_id . "' ,not_return = '" . $is_not_return . "',returning_less = '" . $is_returning_less . "',reason = '" . $reason . "' ,sub_quantity = '" . $this->db->escape ( $inventorydata ['sub_quantity'] ) . "', note_quantity = '" . $this->db->escape ( $inventorydata ['sub_quantity'] ) . "', date_added = '" . $date_added . "',  date_updated = '" . $date_added . "',  facilities_id = '" . $tdata ['facilities_id'] . "' " );
			 
			 }
				
			}

           // die;			
			 	
			
			/*$tagsssss=array_unique($tags_id);	

            
			foreach($tagsssss as $tags_id){
			
			foreach ( $tempdata ['new_module'] as $inventorydata ) {			 
             
				
				if ($inventorydata ['checkin'] == '1') {
					
				if ($inventorydata ['tags_id'] == $tags_id) {	

					$pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);
                    $pre_notes_quantity=$this->model_inventory_inventory->getinventoryByInventoryNotesId ( $inventorydata['inventory_notes_id']);					
								 
					
					$updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];	
                    
					$update_inventory_quantity=( int ) $pre_notes_quantity['sub_quantity'] - ( int ) $inventorydata['sub_quantity'] - ( int ) $inventorydata['not_return'];
	                
					$archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $tempdata , $updated_quantity,$checkless);

					$archive_inventory_id = $this->model_inventory_inventory->updateSubQuantity ( $inventorydata,$update_inventory_quantity);
					
					
					$results = $this->model_inventory_inventory->getinventory ($inventorydata['inventory_id']); 				

					$pre_checkout_quantity=$results['checkout_quantity'];

					$checkout_quantity= (int) $pre_checkout_quantity - (int) $inventorydata['sub_quantity'];

					$this->db->query ("UPDATE `" . DB_PREFIX . "inventory` SET checkout_quantity = '".$checkout_quantity."' WHERE  inventory_id = '" . $this->db->escape ( $inventorydata ['inventory_id'] ) . "' ");
					
				}
                  
				}
			}
			
			 $this->load->model ( 'inventory/inventory' );
			
			$tdata = array ();
			$tdata ['tags_id'] = $tags_id;
			$tdata ['archive_inventory_id'] = $archive_inventory_id;
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();	

			
			$notes_id = $this->model_inventory_inventory->checkInInventoyNote ( $this->request->post, $tdata, $tempdata );
			
			} */
			
			
			$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_inventory_id'] );
			
			$this->session->data ['success2'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
			}
			
			// $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' ) ) );
		}

		   	
		

		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId (), '' );
		
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
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		
		if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
			$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventorySign2', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
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
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
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
		} elseif (! empty ( $this->session->data ['inventory_username'] )) {
			$this->data ['user_id'] = $this->session->data ['inventory_username'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
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
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	protected function checkinValidation() {
		if (($this->request->post ['user_id'] == null && $this->request->post ['user_id'] == "")) {
			$this->error ['user_id'] = "Username is empty";
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function CheckOutInventorySign2() {
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'inventory/inventory' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->post ['form_submit'] == '1') && $this->validateForm23 ()) {
			
			$this->load->model ( 'api/temporary' );
			
			
			
			
			$this->load->model ( 'inventory/inventory' );
			$temporary_info = $this->model_api_temporary->gettemporary ( $this->request->get ['archive_inventory_id'] );
			
			$tempdata = array ();
			$tempdata = unserialize ( $temporary_info ['data'] );
			
			$tagsids = explode (",", $tempdata['checkout_tags']);
			$this->load->model ( 'inventory/inventory' );		
			
			$tdata = array ();
				foreach($tagsids as $tags_id){
				 
				 foreach ( $tempdata ['test_module'] as $inventorydata ) { 

				 $inventory_details=$this->model_inventory_inventory->getinventory($inventorydata['inventory_id']);	 			

                if($inventorydata ['quantity']==$inventory_details ['quantity']){
                	$main_quantity=$inventorydata ['quantity'];
					$updated_quantity = $inventorydata ['quantity'] - $inventorydata ['sub_quantity'];
					
				}else{

					$main_quantity=$inventory_details ['quantity'];
					
					$updated_quantity = $inventory_details ['quantity'] - $inventorydata ['sub_quantity'];

				}
				$archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $tempdata, $updated_quantity,"" );
			
			    $results = $this->model_inventory_inventory->getinventory ($inventorydata['inventory_id']); 				
                
				$pre_checkout_quantity=$results['checkout_quantity'];
							
				$checkout_quantity= (int) $pre_checkout_quantity+ (int) $inventorydata['sub_quantity'];
							
				if($inventorydata['return_type']=='2'){
                 $checkedout_quantity = 0;				
				
				}else{
                  $checkedout_quantity=$checkout_quantity;
				}

                 //var_dump($checkedout_quantity);die;				

                				
				
				$this->db->query ("UPDATE `" . DB_PREFIX . "inventory` SET checkout_quantity = '".$checkedout_quantity."' WHERE  inventory_id = '" . $this->db->escape ( $inventorydata ['inventory_id'] ) . "' ");
			   	
			
			}
			
			

			$tdata ['tags_id'] = $tags_id;
			$tdata ['archive_inventory_id'] = $archive_inventory_id;
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['facilitytimezone'] = $this->customer->isTimezone ();
			$notes_id = $this->model_inventory_inventory->checkOutInventoyNote ( $this->request->post, $tdata, $tempdata ['hidden_user_id'], $tempdata,$main_quantity );
				
			}	
			
			$this->model_api_temporary->deletetemporary ( $this->request->get ['archive_inventory_id'] );
			
			$this->session->data ['success_add_form1'] = '1';

			$this->session->data ['success2'] = '1';
			
			$url2 = "";
			if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get ['searchdate'];
			}
			
			if ($notes_id != null && $notes_id != "") {
				$url2 .= '&notes_id=' . $notes_id;
			}
			
			// $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
			
			$this->redirect ( str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/checkOutInventory', '' . $url2, 'SSL' ) ) );
		}
		
		$this->data ['entry_pin'] = $this->language->get ( 'entry_pin' );
		$this->data ['button_save'] = $this->language->get ( 'button_save' );
		$this->data ['text_select'] = $this->language->get ( 'text_select' );
		
		$this->load->model ( 'user/user' );
		$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $this->customer->getId (), '' );
		
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
		
		if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
		}
		
		if ($this->request->get ['medication_tags'] != null && $this->request->get ['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get ['medication_tags'];
		}
		
		if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
			$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
		}
		
		$this->data ['action2'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventorySign2', '' . $url2, 'SSL' ) );
		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckOutInventory', '' . $url2, 'SSL' ) );
		
		if (isset ( $this->session->data ['pagenumber'] )) {
			$this->data ['pagenumber'] = $this->session->data ['pagenumber'];
		} else {
			$this->data ['pagenumber'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
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
		} elseif (! empty ( $this->session->data ['inventory_username'] )) {
			$this->data ['user_id'] = $this->session->data ['inventory_username'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );
		
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
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/notes_form2.php';
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->response->setOutput ( $this->render () );
	}
	public function CheckInInventory() {	

		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$this->document->setTitle ( 'Check in' );
		
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->language->load ('notes/notes');
		$this->load->model ( 'setting/tags');
		$this->load->model ( 'form/form' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/notes' );
		
		$this->load->model ( 'inventory/inventorytype' );
		
		$this->load->model ( 'facilities/facilities' );
		$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId ());
		
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
		$data = array ();
		$data = array (
				'status' => 1,
				'facilities_id' => $facilities_id 
		);
		
		$this->data ['inventorytypes'] = $this->model_inventory_inventorytype->getinventorys ( $data2 );
		
		$this->load->model ( 'inventory/inventory' );
		$check_in_data = "";		
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && ($_POST ['search'] == 'search')) {			
			
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'inventory/inventory' );
			$notes_info = "";
			
			$timezone_name = $this->customer->isTimezone ();
			$notes_info = $this->model_inventory_inventory->getCheckOutInventoryByUser ( $this->request->post );		
		
		} 

		    $user_id="";	           


			if($this->request->get['notes_id']!=null && $this->request->get['notes_id']!=""){
				$notes_info = $this->model_inventory_inventory->getCheckOutInventoryByUserNotes ( $this->request->get);	

				
			
			foreach ( $notes_info as $data ) {

				   $user_info = $this->model_user_user->getUser($data['user_id']);

				   $user_id=$user_info['username'];

					$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );

                   


					if($tag_info){

						$client_name=$tag_info['emp_first_name']." ".$tag_info['emp_last_name'];

					}

					if($data['checkouttpye_id']!="" && $data['checkouttpye_id']!=null){

					$this->data ['checkouttpye_id']=$data['checkouttpye_id'];

				}

				if($data['tags_id']!="" && $data['tags_id']!=null){

					$this->data ['tags_id']=$data['tags_id'];

					$this->data ['client_name']=$client_name;

				}


				
				$this->data ['inventorys'] [] = array (
						'inventory_id' => $data ['inventory_id'],
						'name' => $data ['name'],
						'inventorytype_id' => $data ['inventorytype_id'],
						'maintenance' => $data ['maintenance'],
						'type' => $data ['type'],
						'status' => $data ['status'],
						'return_type' => $data ['return_type'],
						'reason' => $data ['reason'],
						'returning_less' => $data ['returning_less'],
						'not_return' => $data ['not_return'],
						'is_minus_quantity' => $data ['is_minus_quantity'],
						'quantity' => $data ['quantity'],
						'description' => $data ['description'],
						'measurement_type' => $data ['measurement_type'],
						'sub_quantity' => $data ['note_quantity'] 
				);
			}				

			}
			else if(($this->request->get['user_id']!=null && $this->request->get['user_id']!="")||($this->request->get['tags_id']!=null && $this->request->get['tags_id']!="")){             

				$notes_info = $this->model_inventory_inventory->getCheckOutInventoryByUser ($this->request->get,$facilities_id);				
			
			
			
			
				$show=false;

				$checkin_final="";

				foreach ( $notes_info as $data ) {
					
				$results = $this->model_inventory_inventory->getinventory ($data['inventory_id']); 				
				
                 //var_dump($results['return_type']);				
			     if($results['return_type']=='3'){
					 
					 $sub_quantity=$data['sub_quantity'];
					 
				 }else{
					
                    $sub_quantity='0';					
					 
				 }


			   // var_dump($results['return_type']);
					
				if($data['sub_quantity']!='0'){		
					
					
				$note_data_info = $this->model_notes_notes->getNote ( $data['notes_id']);	
					
									
				
				if($data['tags_id']!='0'){
					
				$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );
                $checkout_by=$tag_info['emp_first_name'].' '.$tag_info['emp_last_name'];

				}else{

                 $checkout_by='';

				}					

               $date_added=date ( 'm-d-Y h:i A', strtotime ( $data ['date_added'] ) );				
					
						

			if($data['inventory_notes_id']!=null && $data['inventory_notes_id']!=""){

			  	$this->data['inventory_notes_id']=$data['inventory_notes_id'];

			}

             if($data['return_type']=="2"){
			   	 $checkin_final=	"0";	
			   }else{		   		
			  	

			   if($data['returning_less']!=""){

			   	$show=true;

			   	if($data['COUNT(user_id)']==1){
			   		$checkin_final=$data['returning_less'];
			   	}else{
			   	$show=true;	
			   	$last_record = $this->model_inventory_inventory->getLastSubQuantity ($data);

			   		$checkin_final=$data['returning_less']+$last_record['sub_quantity'];
			   	
			   	}
			     
			   }else{

			   	$show=true;
			   		
			   	$checkin_final=$data ['SUM(`sub_quantity`)'];
			   }

			   }
			
               if($sub_quantity!='0'){			   
				
				$this->data ['inventorys'] [] = array (
						'inventory_id' => $data ['inventory_id'],
						'name' => $data ['name'],				
						'inventorytype_id' => $data ['inventorytype_id'],
						'maintenance' => $data ['maintenance'],
						'type' => $data ['type'],
						'status' => $data ['status'],
						'returning_less' => $data ['returning_less'],
						'return_type' => $data ['return_type'],
						'quantity' => $data ['MAX(`quantity`)'],
						'description' => $data ['description'],
						'date_added' => $date_added,
						'inventory_notes_id' => $data['inventory_notes_id'],
						'checkout_by' => $checkout_by,
						'user_id' => $note_data_info['user_id'],
						'tags_id' => $data['tags_id'],
						'notes_type' => $note_data_info['notes_type'],
						'notes_pin' => $note_data_info['notes_pin'],
						'signature' => $note_data_info['signature'],
						'measurement_type' => $data ['measurement_type'],
						'sub_quantity' => $sub_quantity,
						'show' => $show

				);
			   }
			 }
				
				
			}//die;
          
			}


			if (isset ( $this->request->get ['notes_id'] )) {
			$this->data ['show_name'] = $this->request->get ['notes_id'];
			}  else {
			$this->data ['show_name'] = '';
			}


            if ($user_id!="" && $user_id!=null) {            	
				
			$this->data ['user_id'] = $user_id;
				
			} else {
				$this->data ['user_id'] = '';
			}

			if (isset ( $this->request->post ['new_module'] )) {
			$this->data ['inventorys'] = $this->request->post ['new_module'];

			//var_dump($this->data ['inventorys']);
			//	die;
		    }		
	
		
		$timezone_name = $this->customer->isTimezone ();
		date_default_timezone_set ( $timezone_name );
		$this->data ['current_time'] = date ( 'h:i A' );
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$this->load->model ( 'notes/notes' );
			$notes_info = $this->model_notes_notes->getNote ( $this->request->get ['notes_id'] );
			
			$this->data ['note_date_added'] = date ( 'm-d-Y h:i A', strtotime ( $notes_info ['date_added'] ) );
		}
		
		/*
		 * $this->load->model('setting/tags');
		 * $taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
		 */
		
		if (isset ( $this->request->post ['type'] )) {
			$this->data ['type'] = $this->request->post ['type'];
		} elseif (! empty ( $taginfo )) {
			$this->data ['type'] = $taginfo ['type'];
		} else {
			$this->data ['type'] = '';
		}
		
		if ($this->request->get ['facilities_id'] != '' && $this->request->get ['facilities_id'] != null) {
			$facilities_id = $this->request->get ['facilities_id'];
		} else {
			$facilities_id = $this->customer->getId ();
		}
		
		if ($this->request->get ['openinventory'] != '' && $this->request->get ['openinventory'] != null) {
			$this->data ['openinventory'] = $this->request->get ['openinventory'];
		} else {
			$this->data ['openinventory'] = '';
		}
		
		
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST' && $_POST ['search'] != 'search') && $this->validateCheckInInventoryForm()) {
			
			$this->load->model ( 'api/temporary' );
			$tdata = array ();
			$tdata ['id'] = $tags_id;
			$tdata ['facilities_id'] = $this->customer->getId ();
			$tdata ['type'] = 'checkinInventoryForm';	


			
			
			$archive_inventory_id = $this->model_api_temporary->addtemporary ($this->request->post, $tdata );
			
			$url2 = "";
			
			$this->session->data ['success_add_form'] = 'Inventory added successfully!';
			
			$url2 .= '&archive_inventory_id=' . $archive_inventory_id;
			
			// $url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			
			$this->redirect ( $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url2, 'SSL' ) );
		}


	   
	    if (isset ( $this->session->data ['success2'] )) {
			$this->data ['success2'] = $this->session->data ['success2'];
			
			unset ( $this->session->data ['success2'] );
		} else {
			$this->data ['success2'] = '';
		}
		 




		$url2 = "";
		
		if ($this->request->get ['archive_inventory_id'] != null && $this->request->get ['archive_inventory_id'] != "") {
			$url2 .= '&archive_inventory_id=' . $this->request->get ['archive_inventory_id'];
		}
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
		}
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		/*if ($this->request->get ['user_id1'] != null && $this->request->get ['user_id1'] != "") {
			$url2 .= '&user_id1=' . $this->request->get ['user_id1'];
		
		}else if($this->session->data['inventory_username']!='' && $this->session->data['inventory_username']!=null){
            $user_info = $this->model_user_user->getUser($this->session->data['inventory_username']);
			$url2 .= '&user_id1=' . $user_info['user_id'];
	 
	    }*/
		
		/*if ($this->request->get ['user_id'] != null && $this->request->get ['user_id'] != "") {
			$url2 .= '&user_id=' . $this->request->get ['user_id'];
			
		}else if($this->session->data['inventory_username']!='' && $this->session->data['inventory_username']!=null){
            $user_info = $this->model_user_user->getUser($this->session->data['inventory_username']);
			$url2 .= '&user_id=' . $user_info ['username'];
	 
	    } */
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
		
		if ($facility ['is_enable_add_notes_by'] == '1' || $facility ['is_enable_add_notes_by'] == '3') {

			//var_dump($facility ['is_enable_add_notes_by']);
			//die;
			/*
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&CheckInInventory=1', '' . $url2, 'SSL' ) );
			
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'common/authorization&CheckInInventory=2', '' . $url2, 'SSL' ) );*/
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventorySign2&CheckInInventory=1', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventorySign2&CheckInInventory=2', '' . $url2, 'SSL' ) );
		} else {
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventorySign2&CheckInInventory=1', '' . $url2, 'SSL' ) );
			$this->data ['updateredirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventorySign2&CheckInInventory=2', '' . $url2, 'SSL' ) );
		}
		
		
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$Total_inventory = count ( $this->data ['inventorys'] );
		// var_dump( $customer_total);
		// die;
		
		$pagination = new Pagination ();
		$pagination->total = $Total_inventory;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_admin_limit' );
		// $pagination->limit = '6';
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'notes/addinventory/addinventory', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		$this->data ['pagination'] = $pagination->render ();
		
		
		if (isset ( $this->request->post ['name'] )) {
			$this->data ['name'] = $this->request->post ['name'];
		} else {
			$this->data ['name'] = '';
		}

		if (isset ( $this->request->post ['inventory_notes_id'] )) {
			$this->data ['inventory_notes_id'] = $this->request->post ['inventory_notes_id'];
		} 

		if ( $this->request->get ['user_id1']!=null &&  $this->request->get ['user_id1']!="") {
			$this->data ['user_id1'] = $this->request->get ['user_id1'];
		} 

		
        
		$this->load->model ( 'user/user' );
		$user_info = $this->model_user_user->getUser($this->request->get['user_id1']);


		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $this->request->get ['tags_id'] );

		/*if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];

			$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
			$this->data ['client_name'] = $tag_info['emp_first_name']." ".$tag_info['emp_last_name'];
		} else*/if (! empty ( $tag_info )) {

			$this->data ['tags_id'] = $tag_info ['tags_id'];
			$this->data ['client_name'] = $tag_info['emp_first_name']." ".$tag_info['emp_last_name'];
		} else {
			$this->data ['tags_id'] = '';
		}

		if(!empty($user_info)){
         
          $this->data ['user_id'] = $user_info ['username'];

		}
				
		
		if (isset ( $this->request->post ['inventorytype_id'] )) {
			$this->data ['inventorytype_id'] = $this->request->post ['inventorytype_id'];
		} else {
			$this->data ['inventorytype_id'] = '';
		}
		
		if (isset ( $this->request->post ['type'] )) {
			$this->data ['type'] = $this->request->post ['type'];
		} else {
			$this->data ['type'] = '';
		}


        if (isset ( $this->request->post ['not_return'] )) {
			$this->data ['not_return'] = $this->request->post ['not_return'];
		} else {
			$this->data ['not_return'] = '';
		}

		 if (isset ( $this->request->post ['checkin'] )) {
			$this->data ['checkin'] = $this->request->post ['checkin'];
		} else {
			$this->data ['checkin'] = '';
		}


         if (isset ( $this->request->post ['return_type'] )) {
			$this->data ['return_type'] = $this->request->post ['return_type'];
		} else {
			$this->data ['return_type'] = '';
		}



		 if (isset ( $this->request->post ['returning_less'] )) {
			$this->data ['returning_less'] = $this->request->post ['returning_less'];
		} else {
			$this->data ['returning_less'] = '';
		}

		
		if (isset ( $this->request->post ['maintenance'] )) {
			$this->data ['maintenance'] = $this->request->post ['maintenance'];
		} else {
			$this->data ['maintenance'] = '';
		}
		
		if (isset ( $this->request->post ['status'] )) {
			$this->data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $customer_info )) {
			$this->data ['status'] = $customer_info ['status'];
		} else {
			$this->data ['status'] = 1;
		}
		
		if (isset ( $this->request->post ['description'] )) {
			$this->data ['description'] = $this->request->post ['description'];
		} else {
			$this->data ['description'] = '';
		}
		
		if (isset ( $this->request->post ['quantity'] )) {
			$this->data ['quantity'] = $this->request->post ['quantity'];
		} else {
			$this->data ['quantity'] = 0;
		}
		
		if (isset ( $this->request->post ['return_type'] )) {
			$this->data ['return_type'] = $this->request->post ['return_type'];
		} else {
			$this->data ['return_type'] = '';
		}
		
		if (isset ( $this->request->post ['measurement_type'] )) {
			$this->data ['measurement_type'] = $this->request->post ['measurement_type'];
		} else {
			$this->data ['measurement_type'] = '';
		}
		
		if (isset ( $this->session->data ['success_add_form'] )) {
			$this->data ['success_add_form'] = $this->session->data ['success_add_form'];
			
			unset ( $this->session->data ['success_add_form'] );
		} else {
			$this->data ['success_add_form'] = '';
		}
		
		
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = array ();
			;
		}
		
       if (isset ( $this->error ['empty_check_in'] )) {
			$this->data ['error_empty_check_in'] = $this->error ['empty_check_in'];
		} else {
			$this->data ['error_empty_check_in'] = "";
			;
		}



		if (isset ( $this->error ['max_quantity'] )) {
			$this->data ['error_max_quantity'] = $this->error ['max_quantity'];
		} else {
			$this->data ['error_max_quantity'] = '';
		}


        if (isset ( $this->error ['inventory_error'] )) {
			$this->data ['error_inventory_error'] = $this->error ['inventory_error'];
		} else {
			$this->data ['error_inventory_error'] = '';
		}

		 if (isset ( $this->error ['reason'] )) {
			$this->data ['error_reason_error'] = $this->error ['reason'];
		} else {
			$this->data ['error_reason_error'] = '';
		}



	
		$url2 = "";
		$url3 = "";
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get ['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get ['notes_id'];
		}
		
		if ($this->request->get ['is_archive'] != null && $this->request->get ['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get ['is_archive'];
			$this->data ['is_archive'] = $this->request->get ['is_archive'];
		}
		/*if ($this->request->get ['user_id'] != null && $this->request->get ['user_id'] != "") {
			$url2 .= '&user_id=' . $this->request->get ['user_id'];
			$this->data ['user_id'] = $this->request->get ['user_id'];
		}*/

		/*if ($this->request->get ['tags_id'] != null && $this->request->get ['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get ['tags_id'];
			$this->data ['tags_id'] = $this->request->get ['tags_id'];
		}
		
		/*if ($this->request->get ['user_id1'] != null && $this->request->get ['user_id1'] != "") {
			$url2 .= '&user_id1=' . $this->request->get ['user_id1'];
			$this->data ['user_id1'] = $this->request->get ['user_id1'];
		}*/
		
		$this->load->model ( 'notes/notes' );
		if ($this->request->get ['notes_id']) {
			$notes_id = $this->request->get ['notes_id'];
		} else {
			$notes_id = $this->request->get ['updatenotes_id'];
		}
		
		//$this->data ['url_load2'] = $this->model_notes_notes->getajaxnote ( $notes_id );

		$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' , 'SSL' ) );

		//var_dump($this->data ['inventorys']);die;


		
		// $this->data['updatenotes_id'] = $notes_id;
		
		$this->data ['action'] = $this->url->link ( 'notes/addInventory/CheckInInventory', $url2, true );
		
		$this->data ['back_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/notes/insert', '' . $url2, 'SSL' ) );
		
		$this->data ['currentt_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/addInventory/CheckInInventory', '' . $url3, 'SSL' ) );
		
		// $this->data['autosearch'] = $this->request->get['autosearch'];


		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/CheckInInventory.php';
		
		$this->children = array (
				'common/headerclient' 
		);
		$this->response->setOutput ( $this->render () );
		// var_dump($this->data);
		// die;
	}
	
	public function CheckOutInventoryForm() { 
	
	    $datafa = array ();	
		
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
	
	    $this->load->model ( 'notes/notes' );
		$this->load->model ( 'inventory/inventory' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'setting/tags' );
	
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {

			
		$notesData = $this->model_inventory_inventory->getInventoryByNote ($this->request->get,$datafa );
		
		foreach ( $notesData as $result ) {							

				$user_info = $this->model_user_user->getUser($result['user_id']);

				$tag_info = $this->model_setting_tags->getTag ( $result ['tags_id'] );
                     
				if($user_info){

					$user_name=$user_info['firstname']." ".$user_info['lastname'];

				}				
				if($tag_info){

					$client_name=$tag_info['emp_first_name']." ".$tag_info['emp_last_name'];

				}

					if($result['checkouttpye_id']!="" && $result['checkouttpye_id']!=null){

					$this->data ['checkouttpye_id']=$result['checkouttpye_id'];

				}

				if($result['tags_id']!="" && $result['tags_id']!=null){

                 				
					$birthdate = date('m-d-Y', strtotime($tag_info['dob']));
					
					$admissiontimestamp = strtotime($tag_info['date_added']);
					$admition_date =date("m-d-Y", $admissiontimestamp);
					
					$emp_first_initial = $tag_info['emp_first_name'][0];
					$emp_last_initial = $tag_info['emp_last_name'][0];

                    $client_initial=$emp_last_initial.". ".$emp_first_initial.".";                  
	
	               
					$admissiontimestamp = strtotime($tag_info['date_added']);
					$admition_date =date("m-d-Y", $admissiontimestamp);
					

					$this->data ['tags_id']=$result['tags_id'];

					
					$this->data ['birthdate']=$birthdate;
					$this->data ['client_id']=$tag_info['ccn'];
					$this->data ['admition_date']=$admition_date;

				}
				
				if($tag_info){
					
					$this->data ['client_name']=$client_name;
				}else{
					
					$this->data ['user_name']=$user_name;

				}				
				 $first_initial = $user_info['firstname'][0];
				 $last_initial = $user_info['lastname'][0];
				 $staff_initial= $last_initial.". ".$first_initial.".";
				 
				 $dateaddedtimestamp = strtotime($result['date_added']);
				 $date_given =date("m-d-Y", $dateaddedtimestamp);
					
					
				$user_id=$user_info['username'];
			

				$this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],	
						'status' => $result ['status'],
						'sub_quantity' => $result ['note_quantity'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],
						'client_initial' => $client_initial,
						'staff_initial' => $staff_initial,
						'description' => $result ['description'],						
						'measurement_type' => $result ['measurement_type'],
						'user_id' => $user_id,
						'date_given' => $date_given,
						
				);				
				
			}			
		}

        require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';	


	    $html.='<style type="text/css">

		
		p{	
		
		  font-weight: bold;		
			
		}
		
		th {
		vertical-align: middle,	
		padding: 20px;
		margin: 10px;		
		font-size:14px;
		font-weight: bold;
	    border: 1px solid #B8b8b8;
	    line-height:40px;
	    display:table-cell; 
			
		}
   
	.heading_style{
		text-align:right;
		margin-right:1%;
		
	}	
	hr {      
        height: 15px;       
        background-color: black;
        border: 0 none;
		width: 330px;
		float:right;
    }  
    td {
		padding: 20px;
		margin: 10px;		
		font-size:12px;
	   border: 1px solid #B8b8b8;
	   line-height:30px;
	   display:table-cell;
		
	}
	</style>
	<style>
		
		.sticky + .content {
		  padding-top: 102px;
		}
		
		</style>
		<style type="text/css" media="print">
		@page 
		{
			size:  auto;   /* auto is the initial value */
			margin: 0mm;  /* this affects the margin in the printer settings */
		}		
		@media print {
			a[href]:after {
				content: none !important;
			}
		}
  </style>';
  $html.='<div class="heading_style">';	
  $html.='<h1 style="margin-bottom : 0px!important">Checkout Inventory Log </h1>';
  $html.='</div>';
  $html.='<div >';
  $html.='<h1 style="margin-bottom : 0px!important">Identifying Information</h1>';
 
  $html.='</div>';		
  $html.='<div style="margin-top : 40px;">';	
		
  if($client_name !="" && $client_name !=null){
	$html.='<p> Client Name : '.$client_name.' </p> ';} 
		 
	if($client_name =="" && $client_name ==null){
	$html.='<p> Staff Name : '. $user_name.'</p>'; } 
	if($client_name !="" && $client_name!=null){		
	$html.='<p>D.O.B : '.$birthdate.'</p>';
	$html.='<p> A # : '.$this->data ['client_id'].'</p>';
	$html.='<p>Admission Date : '.$admition_date.'</p>';
	}
	$html.='</div>';
	$html.='<table width="100%" >';
	$html.='<tr>';  
    $html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;vertical-align: middle">Item</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">How many?</td>';
	$html.='<td width="35%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Description</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Client Initial</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Staff Initial</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Date Given to UC</td>';
	$html.='</tr>';
	foreach($this->data ['inventorys'] as $inventory){
	$html.='<tr >';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['name'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['sub_quantity'].'</td>';
	$html.='<td width="35%" style="text-align:center;">'.$inventory['description'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['client_initial'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['staff_initial'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['date_given'].'</td>';
	$html.='</tr>';
	 }
	$html.='</table>';				
	$pdf->writeHTML ( $html, true, 0, true, 0 );
	$pdf->lastPage ();
	$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
	exit ();
	//$this->template = $this->config->get ( 'config_template' ) . '/template/form/checkoutinventory_form.php';
	//$this->response->setOutput ( $this->render () );
	// var_dump($this->data);
	// die;
	}
	
	
	public function CheckInInventoryForm() { 
	
	    $datafa = array ();	
		
		$datafa ['username'] = $this->session->data ['username'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
	
	    $this->load->model ( 'notes/notes' );
		$this->load->model ( 'inventory/inventory' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'setting/tags' );
	
		
		if ($this->request->get ['notes_id'] != null && $this->request->get ['notes_id'] != "") {

			
		$notesData = $this->model_inventory_inventory->getCheckOutInventoryByUserNotes  ($this->request->get,$datafa );
		
		
		
		
		
		foreach ( $notesData as $result ) {	

                $inventoryinfo = $this->model_inventory_inventory->getinventory ($result['inventory_id']); 	

				$user_info = $this->model_user_user->getUser($result['user_id']);

				$tag_info = $this->model_setting_tags->getTag ( $result ['tags_id'] );
                     
				if($user_info){

					$user_name=$user_info['firstname']." ".$user_info['lastname'];

				}				
				if($tag_info){

					$client_name=$tag_info['emp_first_name']." ".$tag_info['emp_last_name'];

				}

					if($result['checkouttpye_id']!="" && $result['checkouttpye_id']!=null){

					$this->data ['checkouttpye_id']=$result['checkouttpye_id'];

				}

				if($result['tags_id']!="" && $result['tags_id']!=null){

                 				
					$birthdate = date('m-d-Y', strtotime($tag_info['dob']));
					
					$admissiontimestamp = strtotime($tag_info['date_added']);
					$admition_date =date("m-d-Y", $admissiontimestamp);
					
					$emp_first_initial = $tag_info['emp_first_name'][0];
					$emp_last_initial = $tag_info['emp_last_name'][0];

                    $client_initial=$emp_last_initial.". ".$emp_first_initial.".";                  
	
	               
					$admissiontimestamp = strtotime($tag_info['date_added']);
					$admition_date =date("m-d-Y", $admissiontimestamp);
					

					$this->data ['tags_id']=$result['tags_id'];

					
					$this->data ['birthdate']=$birthdate;
					$this->data ['client_id']=$tag_info['ccn'];
					$this->data ['admition_date']=$admition_date;

				}
				
				if($tag_info){
					
					$this->data ['client_name']=$client_name;
				}else{
					
					$this->data ['user_name']=$user_name;

				}				
				 $first_initial = $user_info['firstname'][0];
				 $last_initial = $user_info['lastname'][0];
				 $staff_initial= $last_initial.". ".$first_initial.".";
				 
				 $dateaddedtimestamp = strtotime($result['date_updated']);
				 
				 
				 
				 $date_given =date("m-d-Y", $dateaddedtimestamp);
					
					
				$user_id=$user_info['username'];
			

				$this->data ['inventorys'] [] = array (
						'inventory_id' => $result ['inventory_id'],
						'name' => $result ['name'],
						'inventorytype_id' => $result ['inventorytype_id'],
						'maintenance' => $result ['maintenance'],
						'type' => $result ['type'],	
						'status' => $result ['status'],
						'sub_quantity' => $result ['note_quantity'],
						'return_type' => $result ['return_type'],
						'quantity' => $result ['quantity'],
						'client_initial' => $client_initial,
						'staff_initial' => $staff_initial,
						'description' => $inventoryinfo ['description'],						
						'measurement_type' => $result ['measurement_type'],
						'user_id' => $user_id,
						'date_given' => $date_given,
						
				);				
				
			}

      			
		}

        require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		
		$html = '';	


	    $html.='<style type="text/css">

		
		p{	
		
		  font-weight: bold;		
			
		}
		
		th {
		vertical-align: middle,	
		padding: 20px;
		margin: 10px;		
		font-size:14px;
		font-weight: bold;
	    border: 1px solid #B8b8b8;
	    line-height:40px;
	    display:table-cell; 
			
		}
   
	.heading_style{
		text-align:right;
		margin-right:1%;
		
	}	
	hr {      
        height: 15px;       
        background-color: black;
        border: 0 none;
		width: 330px;
		float:right;
    }  
    td {
		padding: 20px;
		margin: 10px;		
		font-size:12px;
	   border: 1px solid #B8b8b8;
	   line-height:30px;
	   display:table-cell;
		
	}
	</style>
	<style>
		
		.sticky + .content {
		  padding-top: 102px;
		}
		
		</style>
		<style type="text/css" media="print">
		@page 
		{
			size:  auto;   /* auto is the initial value */
			margin: 0mm;  /* this affects the margin in the printer settings */
		}		
		@media print {
			a[href]:after {
				content: none !important;
			}
		}
  </style>';
  $html.='<div class="heading_style">';	
  $html.='<h1 style="margin-bottom : 0px!important">Checkin Inventory Log </h1>';
  $html.='</div>';
  $html.='<div >';
  $html.='<h1 style="margin-bottom : 0px!important">Identifying Information</h1>';
 
  $html.='</div>';		
  $html.='<div style="margin-top : 40px;">';	
		
  if($client_name !="" && $client_name !=null){
	$html.='<p> Client Name : '.$client_name.' </p> ';} 
		 
	if($client_name =="" && $client_name ==null){
	$html.='<p> Staff Name : '. $user_name.'</p>'; } 
	if($client_name !="" && $client_name!=null){		
	$html.='<p>D.O.B : '.$birthdate.'</p>';
	$html.='<p> A # : '.$this->data ['client_id'].'</p>';
	$html.='<p>Admission Date : '.$admition_date.'</p>';
	}
	$html.='</div>';
	$html.='<table width="100%" >';
	$html.='<tr>';  
    $html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;vertical-align: middle">Item</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">How many?</td>';
	$html.='<td width="35%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Description</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Client Initial</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Staff Initial</td>';
	$html.='<td width="13%" style="font-size:14px!important;font-weight:bold!important;text-align:center;">Date Given to UC</td>';
	$html.='</tr>';
	foreach($this->data ['inventorys'] as $inventory){
	$html.='<tr >';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['name'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['sub_quantity'].'</td>';
	$html.='<td width="35%" style="text-align:center;">'.$inventory['description'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['client_initial'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['staff_initial'].'</td>';
	$html.='<td width="13%" style="text-align:center;">'.$inventory['date_given'].'</td>';
	$html.='</tr>';
	 }
	$html.='</table>';				
	$pdf->writeHTML ( $html, true, 0, true, 0 );
	$pdf->lastPage ();
	$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
	exit ();
	//$this->template = $this->config->get ( 'config_template' ) . '/template/form/checkoutinventory_form.php';
	//$this->response->setOutput ( $this->render () );
	// var_dump($this->data);
	// die;
	}
	
	
	
	

}
?>