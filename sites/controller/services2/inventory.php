<?php
class Controllerservices2inventory extends Controller {
	private $error = array ();
	public function index() {
		try {
			$this->data ['facilitiess'] = array ();
			
			/*$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}*/
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'inventory/inventory' );
				
				$data = array ();
				$data = array (
						'status' => 1,
						'facilities_id' => $this->request->post ['facilities_id'],
						'inventorytype_id' => $this->request->get ['inventorytype_id'] 
				);
				$results = $this->model_inventory_inventory->getinventorys ( $data );

				$measurementtypes= $this->model_inventory_inventory->getMeasurementValues ();


		    foreach ($measurementtypes as $measurementtype) {
		     	$this->data ['measurementtypes'] []  = array(
				'customlistvalues_id' => $measurementtype['customlistvalues_id'],
				'customlistvalues_name' => $measurementtype['customlistvalues_name']);
		      }
				
				foreach ( $results as $result ) {
					
					$this->data ['inventorys'] [] = array (
							'inventory_id' => $result ['inventory_id'],
							'name' => $result ['name'],
							'inventorytype_id' => $result ['inventorytype_id'],
							'maintenance' => $result ['maintenance'],
							'type' => $result ['type'],
							'status' => $result ['status'],
							'return_type' => $result ['return_type'],
							'quantity' => $result ['quantity'],
							'description' => $result ['description'],
							'units' => $result ['measurement_type'] 
					);
				}
				
				$this->load->model ( 'inventory/inventorytype' );
				$data2 = array ();
				$data2 = array (
						'status' => 1,
						'facilities_id' => $this->request->post ['facilities_id'] 
				);
				$inventorytypes = $this->model_inventory_inventorytype->getinventorys ( $data2 );
				
				foreach ( $inventorytypes as $inventorytype ) {
					
					$this->data ['inventorytypes'] [] = array (
							'inventorytype_id' => $inventorytype ['inventorytype_id'],
							'name' => $inventorytype ['name'] 
					);
				}
				
				$this->data ['facilitiess'] [] = array (
						'inventorys' => $this->data ['inventorys'],
						'inventorytypes' => $this->data ['inventorytypes'],
						'units' => 	$this->data ['measurementtypes'] 
				);
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => true 
				);
			} else {
				$error = false;
				$value = array (
						'results' => "No inventory Found",
						'status' => false 
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites services inventory List' 
			);
			$this->model_activity_activity->addActivity ( 'inventorylist', $activity_data2 );
		}
	}

     public function checkoutInventory(){

		try {	



		/*$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}*/		

		if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "" && $this->request->post ['name']!=null && $this->request->post ['name']!="" && $this->request->post ['user_id']!=null && $this->request->post ['user_id']!="") {
			
			$this->load->model ( 'inventory/inventory' );

			$data = array ();
			
			$data = array (
					'name' => $this->request->post ['name'],
					'start' => 0,
					'facilities_id' => $this->request->post ['facilities_id'],
					'limit' => 20 
			);
			
			$results = $this->model_inventory_inventory->getInventoryByName ( $data );
			
			if ($results != null && $results != "") {
				foreach ( $results as $result ) {

					$measurementtype = $this->model_inventory_inventory->getMeasurementValuesById ($result ); 

					$this->data ['inventorys'] [] = array (
							'inventory_id' => $result ['inventory_id'],
							'name' => $result ['name'],
							'description' => $result ['description'],
							'quantity' => $result ['quantity'],
							'return_type' => $result ['return_type'],
							'measurement_type' => $measurementtype ['customlistvalues_name'],
							'inventorytype_id' => $result ['inventorytype_id'] 
					)
					;
				}
			}

			$value = array (
						'results' => $this->data ['inventorys'],
						'status' => true 
				);
		}else {
				$error = false;
				$value = array (
						'results' => "No Checkout Inventory Found",
						'status' => false 
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error inventory name  autocomplete' 
			);
			$this->model_activity_activity->addActivity ( 'userrole_autocomplete', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	
  }


  public function checkinInventory(){

    try{


    	/*$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}*/

      if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'inventory/inventory' );  

    	if($this->request->post['notes_id']!=null && $this->request->post['notes_id']!=""){

                $data = array ();
				$data = array (
						'status' => 1,
						'facilities_id' => $this->request->post ['facilities_id'],
						'notes_id' => $this->request->post ['notes_id'] 
				); 

			$notes_info = $this->model_inventory_inventory->getCheckOutInventoryByUserNotes ( $data);				
			
			foreach ( $notes_info as $data ) {

				$user_id=$data['user_id'];			
				
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
						'sub_quantity' => $data ['sub_quantity'] 
				)
				;
			}

			$value = array (
						'results' => $this->data ['inventorys'],
						'status' => true 
				);
			}
			else if($this->request->post['user_id']!=null && $this->request->post['user_id']!=""){

                $data = array ();
				$data = array (
						'status' => 1,
						'facilities_id' => $this->request->post ['facilities_id'],
						'user_id' => $this->request->post ['user_id'] 
				); 

				$notes_info = $this->model_inventory_inventory->getCheckOutInventoryByUser ( $data);

				$show=false;

				
				$checkin_final="";

				foreach ( $notes_info as $data ) {

				//var_dump($data);	


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
						'measurement_type' => $data ['measurement_type'],
						'sub_quantity' => $checkin_final,
						'show' => $show

				)
				;
			}


			$value = array (
						'results' => $this->data ['inventorys'],
						'status' => true 
				);


			//die;
			}else{

				$error = false;
				$value = array (
						'results' => "Somthing Went Wrong",
						'status' => false 
				);

			}

		}else {
				$error = false;
				$value = array (
						'results' => "No inventory Found",
						'status' => false 
				);
			}
			$this->response->setOutput ( json_encode ( $value ) );


    }catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error inventory name  autocomplete' 
			);
			$this->model_activity_activity->addActivity ( 'userrole_autocomplete', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}


  }

  public function getInventory()
 {

     try{

     	/*$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}*/

     	if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'inventory/inventory' );
				
				$data = array ();
				$data = array (
						'status' => 1,
						'facilities_id' => $this->request->post ['facilities_id']
						
				);
				$results = $this->model_inventory_inventory->getinventorys ( $data );
				
				foreach ( $results as $result ) {
					
					$this->data ['inventorys'] [] = array (
							'inventory_id' => $result ['inventory_id'],
							'name' => $result ['name'],
							'inventorytype_id' => $result ['inventorytype_id'],
							'maintenance' => $result ['maintenance'],
							'type' => $result ['type'],
							'status' => $result ['status'],
							'return_type' => $result ['return_type'],
							'quantity' => $result ['quantity'],
							'description' => $result ['description'],
							'measurement_type' => $result ['measurement_type'] 
					);
				}

				$value = array (
						'results' => $this->data ['inventorys'],
						'status' => true 
				);

			}else {
				$error = false;
				$value = array (
						'results' => "No inventory Found",
						'status' => false 
				);
			}
			$this->response->setOutput ( json_encode ( $value ) );
     }catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in inventory list' 
			);
			$this->model_activity_activity->addActivity ( 'Inventory List', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}


 }	


 public function addInventory(){
   
  try{

  	/*$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}*/

  	if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'inventory/inventory' );

    if (isset ( $this->request->post ['new_module'] )) {	
			
			$this->data ['inventorys'] = $this->request->post ['new_module'];
		} else 
		{
			
			$data = array ();
			$data = array (
					'status' => 1,
					'facilities_id' =>  $this->request->post ['facilities_id']
					 
			);		
			
			
			$results = $this->model_inventory_inventory->getinventorys ( $data );		

			$checkout_quantity="";
			foreach ( $results as $result ) {

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
						'check_out_quantity' => $checkout_quantity,
						'description' => $result ['description'],
						'measurement_type' => $result ['measurement_type'] 
				);
				
			}
				 
		}

			$value = array (
						'results' => $this->data ['inventorys'],
						'status' => true 
				);

   }else {
				$error = false;
				$value = array (
						'results' => "No inventory Found",
						'status' => false 
				);
			}

		$this->response->setOutput ( json_encode ( $value ) );

  }catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in add inventory' 
			);
			$this->model_activity_activity->addActivity ( 'error add inventory list', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}


 } 

	public function addInventorySign(){
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsonAddInventory', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];

		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);


		
		if($api_device_info == false){


			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
				$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				$unique_id = $facility['customer_key'];
				
				
				$this->load->model('customer/customer');
				$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
				
				if($user_info['customer_key'] != $customer_info['activecustomer_id']){
					$json['warning'] = $this->language->get('error_customer');
					$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
				}
		}
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
			}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');
            $this->load->model('inventory/inventory');
			$this->load->model('notes/notes');
			
			$timezone_name = $this->request->post['facilitytimezone'];
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			
			$notetime = date('H:i:s', strtotime('now'));
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $notetime;
			$data['note_date'] = $date_added;
			$data['facilitytimezone'] = $timezone_name;
			
			
		if($this->request->post['comments'] != null && $this->request->post['comments']){
				$comments = ' | '.$this->request->post['comments'];
			}
			
			$data['notes_description'] = 'Inventory has been updated | ' . $comments;
			
			$data['date_added'] = $date_added;
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
			$data['device_unique_id'] = $this->request->post['device_unique_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			if($this->request->post['device_unique_id'] != null && $this->request->post['device_unique_id'] != ""){
				$exist_note_info = $this->model_notes_notes->getexistnotes($data, $this->request->post['facilities_id']);
				
				if(!empty($exist_note_info)){
					$notes_id = $exist_note_info['notes_id'];
					$device_unique_id = $exist_note_info['device_unique_id'];
				}else{

				$jsonData = stripslashes ( html_entity_decode ( $_REQUEST ['addinventorys'] ) );
				$addinventorys = json_decode ( $jsonData, true );

					//$archive_inventory_id = $this->model_inventory_inventory->addAllInventory ($this->request->post, $this->request->post['facilities_id'] );

				if ($addinventorys != null && $addinventorys != "") {

				foreach ($addinventorys as $mediactiondata ) {				
			
				$tags_medication_details_ids = implode ( ',', $mediactiondata ['inventory_id'] );
				
				$sssql = "SELECT inventory_id,name,inventorytype_id,status,maintenance,description,facilities_id,quantity,return_type,measurement_type, type FROM `" . DB_PREFIX . "inventory` WHERE inventory_id = '" . $mediactiondata ['inventory_id'] . "'";
				$query = $this->db->query ( $sssql );
				
				if ($query->num_rows > 0) {

                   if($mediactiondata['return_type']=="3"){
                    	$requre_return_quantity=$mediactiondata['quantity'];
                    }else{
                    	$requre_return_quantity="";
                    }

					$this->db->query ( "UPDATE `" . DB_PREFIX . "inventory` SET name = '" . $this->db->escape ( $mediactiondata ['name'] ) . "', inventorytype_id = '" . $this->db->escape ( $mediactiondata ['inventorytype_id'] ) . "', status = '1', maintenance = '" . $this->db->escape ( $mediactiondata ['maintenance'] ) . "', description = '" . $this->db->escape ( $mediactiondata ['description'] ) . "', quantity = '" . $this->db->escape ( $mediactiondata ['quantity'] ) . "',	return_type_quantity = '" . $this->db->escape ( $requre_return_quantity) . "',return_type = '" . $this->db->escape ( $mediactiondata ['return_type'] ) . "', measurement_type = '" . $this->db->escape ( $mediactiondata ['measurement_type'] ) . "',customer_key = '" . $customer_key . "' ,type = '" . $this->db->escape ( $mediactiondata ['type'] ) . "' ,facilities_id = '" . $this->db->escape ( $facilities_id ) . "',date_updated = '" . $this->db->escape ( $noteDate ) . "' where inventory_id = '" . $mediactiondata ['inventory_id'] . "' " );
				} else {

                    if($mediactiondata['return_type']=="3"){
                    	$requre_return_quantity=$mediactiondata['quantity'];
                    }else{
                    	$requre_return_quantity="";
                    }	

					
					$this->db->query ( "INSERT INTO `" . DB_PREFIX . "inventory` SET name = '" . $this->db->escape ( $mediactiondata ['name'] ) . "', inventorytype_id = '" . $this->db->escape ( $mediactiondata ['inventorytype_id'] ) . "', status = '1', maintenance = '" . $this->db->escape ( $mediactiondata ['maintenance'] ) . "', description = '" . $this->db->escape ( $mediactiondata ['description'] ) . "', quantity = '" . $this->db->escape ( $mediactiondata ['quantity'] ) . "', return_type_quantity = '" . $this->db->escape ( $requre_return_quantity) . "',return_type = '" . $this->db->escape ( $mediactiondata ['return_type'] ) . "', measurement_type = '" . $this->db->escape ( $mediactiondata ['measurement_type'] ) . "',type = '" . $this->db->escape ( $mediactiondata ['type'] ) . "',customer_key = '" . $customer_key . "',facilities_id = '" . $this->db->escape ( $facilities_id ) . "',date_added = '" . $this->db->escape ( $noteDate ) . "',date_updated = '" . $this->db->escape ( $noteDate ) . "'  " );


				}
			}	
			}	
			
		
					$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);                



					$device_unique_id = $this->request->post['device_unique_id'];
				}
			}else{

				$archive_inventory_id = $this->model_inventory_inventory->addAllInventory ($this->request->post, $this->request->post['facilities_id'] );
			
		
				$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);

				// var_dump($notes_id);
                 //die;
				$device_unique_id = $this->request->post['device_unique_id'];
			}
			$this->load->model('api/facerekognition');
			$fre_array2 = array();
			$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
			$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
			$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
			$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
			$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
			$fre_array2['notes_id'] = $notes_id;
			$this->model_api_facerekognition->savefacerekognitionnotes($fre_array2);
			
			$this->load->model('setting/tags');
			$date_added = date('Y-m-d H:i:s', strtotime('now'));
				
			
						
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
				'device_unique_id'  => $device_unique_id,
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in add inventory '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_addinventory', $activity_data2);
		
		
		} 
	}

	public function checkInInventorySign(){
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsonCheckInInventory', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
				$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				$unique_id = $facility['customer_key'];
				
				
				$this->load->model('customer/customer');
				$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
				
				if($user_info['customer_key'] != $customer_info['activecustomer_id']){
					$json['warning'] = $this->language->get('error_customer');
					$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
				}
		}
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
			}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');
            $this->load->model('inventory/inventory');
			$this->load->model('notes/notes');
			
			$timezone_name = $this->request->post['facilitytimezone'];
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			
			$notetime = date('H:i:s', strtotime('now'));
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $notetime;
			$data['note_date'] = $date_added;
			$data['facilitytimezone'] = $timezone_name;

			$description = '';

			$description .= ' '.$this->request->post ['user_id'].' has Checked In Inventory | ';
		

		if($this->request->post ['tags_id']!=null && $this->request->post ['tags_id']!=""){
			 $this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ($this->request->post ['tags_id'] );

		$description.=' | ' .$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
		}

       
		
		$data ['form_type'] = "6";
		$data ['is_inventory_checkin_id'] = $this->request->post ['user_id'];
			
			
		if($this->request->post['comments'] != null && $this->request->post['comments']){
				$comments = ' | '.$this->request->post['comments'];
			}
			
			
			
			$data['date_added'] = $date_added;
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
			$data['device_unique_id'] = $this->request->post['device_unique_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			if($this->request->post['device_unique_id'] != null && $this->request->post['device_unique_id'] != ""){
				$exist_note_info = $this->model_notes_notes->getexistnotes($data, $this->request->post['facilities_id']);
				
				if(!empty($exist_note_info)){
					$notes_id = $exist_note_info['notes_id'];
					$device_unique_id = $exist_note_info['device_unique_id'];
				}else{	

					$jsonData = stripslashes ( html_entity_decode ( $_REQUEST ['checkininventorys'] ) );
				$checkininventorys = json_decode ( $jsonData, true );

				if ($checkininventorys != null && $checkininventorys != "") {


                  foreach ($checkininventorys as $inventorydata ) {

				if ($inventorydata ['checkin'] == '1') {

                   if($inventorydata['return_type']=="2"){

                   	 $checkless="0";

                   	  $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata); 

                   	  $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata ['sub_quantity'];	

                   	 $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);

                   }else{ 

                   	$datas = $this->model_inventory_inventory->getCheckOutInventoryByInventoryIds ($inventorydata['inventory_id'],$this->request->post);

                     
					if($datas['returning_less']!=""){

						if($inventorydata['sub_quantity'] < $datas["returning_less"]){

						   $checkless="1";

						   $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);

					}else{
                           $checkless="0";
                           $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);
					}


					}else{
						if($inventorydata['sub_quantity'] < $datas["SUM(`sub_quantity`)"]){


						$checkless="1";

							$pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);


					}else{
                           $checkless="0";

                           $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);
					}
					}                  

				      $checkless="";

				
                    $checkin_quantity="";

					 }
				}
			}
		
					$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);
					$device_unique_id = $this->request->post['device_unique_id'];
				}

			}
			}else{		

			if ($checkininventorys != null && $checkininventorys != "") {	

			 foreach ($checkininventorys as $inventorydata ) {

				if ($inventorydata ['checkin'] == '1') {

                   if($inventorydata['return_type']=="2"){

                   	 $checkless="0";

                   	  $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata); 

                   	  $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata ['sub_quantity'];	

                   	 $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);

                   }else{ 

                   	$datas = $this->model_inventory_inventory->getCheckOutInventoryByInventoryIds ($inventorydata['inventory_id'],$this->request->post);

                     
					if($datas['returning_less']!=""){

						if($inventorydata['sub_quantity'] < $datas["returning_less"]){

						   $checkless="1";

						   $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);

					}else{
                           $checkless="0";
                           $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);
					}


					}else{
						if($inventorydata['sub_quantity'] < $datas["SUM(`sub_quantity`)"]){


						$checkless="1";

							$pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);


					}else{
                           $checkless="0";

                           $pre_quantity=$this->model_inventory_inventory->getinventoryById ( $inventorydata);                 		
                   		             
					
		              $updated_quantity = ( int ) $pre_quantity['quantity'] + ( int ) $inventorydata['sub_quantity'];		                

		              $archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post , $updated_quantity,$checkless);
					}
					}                  

				      $checkless="";

				
                    $checkin_quantity="";

					 }
				}
			}	

			}	
		
				$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);
				$device_unique_id = $this->request->post['device_unique_id'];
			}
			$this->load->model('api/facerekognition');
			$fre_array2 = array();
			$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
			$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
			$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
			$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
			$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
			$fre_array2['notes_id'] = $notes_id;
			$this->model_api_facerekognition->savefacerekognitionnotes($fre_array2);
			
			$this->load->model('setting/tags');
			$date_added = date('Y-m-d H:i:s', strtotime('now'));
				
			
						
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
				'device_unique_id'  => $device_unique_id,
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in add inventory '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_addinventory', $activity_data2);
		
		
		} 
	}

	public function checkOutInventorySign(){
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsonCheckoutInventory', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
				$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				$unique_id = $facility['customer_key'];
				
				
				$this->load->model('customer/customer');
				$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
				
				if($user_info['customer_key'] != $customer_info['activecustomer_id']){
					$json['warning'] = $this->language->get('error_customer');
					$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
				}
		}
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
			}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');
             $this->load->model('inventory/inventory');
			$this->load->model('notes/notes');
			
			$timezone_name = $this->request->post['facilitytimezone'];
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			
			$notetime = date('H:i:s', strtotime('now'));
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $notetime;
			$data['note_date'] = $date_added;
			$data['facilitytimezone'] = $timezone_name;

			$data ['form_type'] = "5";
		    $data ['is_inventory_checkout_id'] = $this->request->post ['user_id'];


		
		$description = '';
		
		$description .= ' '.$this->request->post['user_id'].' has Checked Out Inventory | ';
		//$description .= ' ' . $check_out_user;

		if($pdata ['tags_id']!=null && $pdata ['tags_id']!=""){
			 $this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ($pdata ['tags_id'] );

		$description.=' | ' .$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
		}
		
			
			
		if($this->request->post['comments'] != null && $this->request->post['comments']){
				$comments = ' | '.$this->request->post['comments'];
			}
			
			
			
			$data['date_added'] = $date_added;
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
			$data['device_unique_id'] = $this->request->post['device_unique_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			if($this->request->post['device_unique_id'] != null && $this->request->post['device_unique_id'] != ""){
				$exist_note_info = $this->model_notes_notes->getexistnotes($data, $this->request->post['facilities_id']);
				
				if(!empty($exist_note_info)){
					$notes_id = $exist_note_info['notes_id'];
					$device_unique_id = $exist_note_info['device_unique_id'];
				}else{

				$jsonData = stripslashes ( html_entity_decode ( $_REQUEST ['checkoutinventorys'] ) );
				$checkoutinventorys = json_decode ( $jsonData, true );

					if ($checkininventorys != null && $checkininventorys != "") {

				foreach ($checkoutinventorys as $inventorydata ) {		
				
				$updated_quantity = $inventorydata ['quantity'] - $inventorydata ['sub_quantity'];

				
				$archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post, $hiupdated_quantity,"" );
				
				
			}		

		}
			
		
					$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);
					$device_unique_id = $this->request->post['device_unique_id'];
				}
			}else{

				if ($checkininventorys != null && $checkininventorys != "") {

				foreach ($checkoutinventorys as $inventorydata ) {		
				
				$updated_quantity = $inventorydata ['quantity'] - $inventorydata ['sub_quantity'];

				
				$archive_inventory_id = $this->model_inventory_inventory->updateQuantity ( $inventorydata, $this->request->post, $updated_quantity,"" );
				
				// var_dump($inventorydata['inventory_id']." ".$sum);
			}	}		
			
		
				$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);
				$device_unique_id = $this->request->post['device_unique_id'];
			}
			$this->load->model('api/facerekognition');
			$fre_array2 = array();
			$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
			$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
			$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
			$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
			$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
			$fre_array2['notes_id'] = $notes_id;
			$this->model_api_facerekognition->savefacerekognitionnotes($fre_array2);
			
			$this->load->model('setting/tags');
			$date_added = date('Y-m-d H:i:s', strtotime('now'));
				
			
						
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
				'device_unique_id'  => $device_unique_id,
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in add inventory '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_addinventory', $activity_data2);
		
		
		} 
	}



}