<?php
class Modelinventoryinventory extends Model {
	
	public function deleteinventory($inventory_id) {
		//$this->db->query ( "DELETE FROM " . DB_PREFIX . "inventory WHERE inventory_id = '" . ( int ) $inventory_id . "'" );
	
		$sql="CALL usp_DeleteInventoryById ('" . (int)$inventory_id . "')";
		$this->db->query($sql);
	
	
	}
	public function getInventoryByNote($data,$datafa) {    
    
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "notes_by_inventory WHERE  notes_id='" . $data ['notes_id'] . "' and facilities_id='".$datafa['facilities_id']."' and type='2' " );
		
		return $query->rows;
	}
	public function getinventory($inventory_id) {
		//$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "inventory WHERE inventory_id = '" . ( int ) $inventory_id . "'" );
		
		//return $query->row;

		$sql="CALL usp_getInventoryById('" . (int)$inventory_id . "')";
		$query = $this->db->query($sql);

		return $query->row;		
	}
	public function getinventorys($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "inventory";
		
		$sql .= " where 1 = 1 and status =1  ";
		
		if ($data ['inventorytype_id'] != null && $data ['inventorytype_id'] != "") {
			$sql .= " and inventorytype_id = '" . $data ['inventorytype_id'] . "'";
		}
		if ($data ['inventory_name'] != null && $data ['inventory_name'] != "") {
			$sql .= " and name like '%" . $this->db->escape ( $data ['inventory_name'] ) . "%'";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities_id) ";
		}
		
		$sql .= " ORDER BY inventory_id";
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getTotalinventorys($data = array()) {
		$sql = " where 1 = 1 and status =1  ";
		if ($data ['inventorytype_id'] != null && $data ['inventorytype_id'] != "") {
			$sql .= " and inventorytype_id = '" . $data ['inventorytype_id'] . "'";
		}
		if ($data ['inventory_name'] != null && $data ['inventory_name'] != "") {
			$sql .= " and name like '%" . $this->db->escape ( $data ['inventory_name'] ) . "%'";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities_id) ";
		}
		
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "inventory " . $sql . " " );
		
		return $query->row ['total'];
	}
	public function getInventoryColumn() {
		$tablefields = array ();
		$query = $this->db->query ( "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='" . DB_DATABASE . "' AND `TABLE_NAME`='" . DB_PREFIX . "inventory' AND `COLUMN_NAME` IN ('name','description','quantity','maintenance') ORDER BY `COLUMN_NAME`" );
		foreach ( $query->rows as $values ) {
			$tablefields [] = array (
					'title_en' => $values ['COLUMN_NAME'],
					'key' => $values ['COLUMN_NAME'] 
			);
		}
		return $tablefields;
	}
	function addAllInventory($data, $facilities_id) {	
		
		
		if ($facilities_id) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $facility ['customer_key'] );
			
			$customer_key = $customer_info ['activecustomer_id'];
			$unique_id = $customer_info ['customer_key'];
			
			$this->load->model ( 'setting/timezone' );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
			$timezone_name = $timezone_info ['timezone_value'];
			
			date_default_timezone_set ( $timezone_name );
			$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		}

		$this->load->model ( 'inventory/inventory' );

		$data2=array(
        'facilities_id'=>$facilities_id
		);


		$total_inventorys = $this->model_inventory_inventory->getinventorys ( $data2 );

		foreach ($total_inventorys as $inventory) {

			 $dbids[] = $inventory['inventory_id'];
			
		}

			if($data['deleteids']!=null && $data['deleteids']!=""){

			$deleteList = explode (',', $data['deleteids']);


			}else{

			$deleteList='';

			}  		
		
		if ($data ['new_module']) {

			//var_dump(json_decode($data['new_module']));
		    //die;

			foreach ( $data ['new_module'] as $mediactiondata ) {


			  $deledeids[] = $mediactiondata['inventory_id'];				
			
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

			//$result22 = array_diff($dbids, $deledeids);	

					

		if(!empty($deleteList)){
			foreach($deleteList as $dbid){
							
				$this->db->query("delete FROM `" . DB_PREFIX . "inventory` WHERE facilities_id IN (" . $facilities_id . ") AND inventory_id = '".$dbid."' ");				
			}
		}       
			
		}
		
		
		
		$this->load->model ( 'activity/activity' );
		// $data['tags_id'] = $tags_id;
		$this->model_activity_activity->addActivitySave ( 'addAllInventory', $data, 'query' );
		
		return $archive_tags_medication_id;
	}

	
	public function getInventoryByName($data = array()) {
	
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				
			$sql = "SELECT * FROM " . DB_PREFIX . "inventory where 1=1 ";
				
			if ($data ['name'] != null && $data ['name'] != "") {
				$sql .= "and name like '%" . $data ['name'] . "%'";
			}
			//$sql .= " and facilities_id='". $data['facilities_id']."' ";
	
			$sql .= "and FIND_IN_SET('". $data['facilities_id']."', facilities_id) ";

            $sql .= "order by name";


			if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			
			$n_start = $data['start'];
			$n_limit = $data['limit'];
			
			
		}
		    
		
		   
			$query = $this->db->query ( $sql );
				
			return $query->rows;
		}
	}
	public function addInventorynote($pdata, $fdata) {
		
		
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		
		
		
		$description = '';
		
		$description .= ' Inventory has been updated | ';
		$description .= ' ';
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$description .= ' | ' . $this->db->escape ( $pdata ['comments'] );
		}
		
		$data ['notes_description'] = $description;
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		// var_dump($notes_id);
		// die;
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		// var_dump($data['notes_description']);
		// var_dump($facility);
		// die;
		
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
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		// $archive_tags_medication_id = $fdata['archive_tags_medication_id'];
		
		// $mdata2 = array();
		// $mdata2['notes_id'] = $notes_id;
		// $mdata2['tags_id'] = $fdata['tags_id'];
		// $mdata2['archive_tags_medication_id'] = $archive_tags_medication_id;
		
		// $this->model_notes_notes->updateinventoryarchive2($mdata2);
		
		return $notes_id;
	}
	public function getFormInventory() {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "inventory" );
		
		return $query->rows;
	}
	
	public function getCheckOutInventoryByUser($pdata,$facilities_id) { 

	   $user_id=$pdata['user_id1']; 
	   $tags_id=$pdata['tags_id']; 
	   $openinventory=$pdata['openinventory'];        

        $sql= "SELECT * FROM `" . DB_PREFIX . "notes_by_inventory`";

         $sql.=" WHERE 1=1 AND type='2'";

          if($tags_id!=null && $tags_id!=""){			

			 $sql.=" AND `tags_id` ='" . $tags_id . "'";
            } 

         if((($user_id!=null && $user_id!="") && ($openinventory!=null && $openinventory!=""))||($tags_id=="")){
         	$sql.=" AND `user_id` ='" . $user_id . "'";
         	$sql.=" AND `tags_id` ='0'";
        
          } else if ($user_id!=null && $user_id!=""){

            $sql.=" AND `user_id` ='" . $user_id . "'";
          }    
		  
		
      /* if(($pdata['user_id1']!=null && $pdata['user_id1']!="") && 
		  ($pdata['tags_id']==null && $pdata['tags_id']=="")){			

			 $sql.=" AND `user_id` ='" . $user_id . "'";
             $sql.=" AND `tags_id` ='0'";			 

         } else{
			 
			 if($pdata['tags_id']!=null && $pdata['tags_id']!=""){			

			 $sql.=" AND `tags_id` ='" . $tags_id . "'";
            $sql.=" AND `user_id` ='" . $user_id . "'";			 

         } 
			 
			 
		 } */

        		 
		 
         if($facilities_id!=null || $facilities_id!=""){

         	$current_facilities_id=$facilities_id;
         	$sql.=" AND `facilities_id` ='" . $current_facilities_id . "'";

         }else{

         	$current_facilities_id=$pdata ['facilities_id'];
         	$sql.=" AND `facilities_id` ='" . $current_facilities_id . "'";
         } 


    //var_dump($sql);die;		 
      

		$query = $this->db->query ($sql);

		return $query->rows;

	}

	public function getLastSubQuantity($data) {

		//var_dump($data);
		
		$query = $this->db->query ( "SELECT `sub_quantity` FROM `" . DB_PREFIX . "notes_by_inventory` WHERE `inventory_id` ='" . $data ['inventory_id'] . "' and `user_id` ='" . $data ['user_id'] . "' and type='2' and is_checked_in = '0' and `inventory_notes_id`='".$data['MAX(`inventory_notes_id`)']."' " );
		
		return $query->row;
	}

    public function getCheckOutInventoryByInventoryId($inventory_id,$pdata) {
		
		$query = $this->db->query ("SELECT `name`,MAX(`inventory_notes_id`),is_minus_quantity,inventory_id,inventorytype_id,maintenance,type,measurement_type,description,return_type,MAX(`quantity`), SUM(`sub_quantity`) FROM `" . DB_PREFIX . "notes_by_inventory` WHERE `inventory_id` ='" . $inventory_id . "' and type='2' and `user_id` ='" . $pdata ['user_id'] . "' and is_checked_in = '0' GROUP BY name");
		
		return $query->row;
	}

	 public function getCheckoutInventory($inventory_id) {
		
		$query = $this->db->query ("SELECT `name`,MAX(`inventory_notes_id`),is_minus_quantity,inventory_id,inventorytype_id,maintenance,type,measurement_type,description,return_type,MAX(`quantity`), SUM(`sub_quantity`) FROM `" . DB_PREFIX . "notes_by_inventory` WHERE `inventory_id` ='" . $inventory_id . "' and type='2' and is_checked_in = '0' GROUP BY name");
		
		return $query->row;
	}



	public function getCheckOutInventoryByInventoryIds($inventory_id,$pdata) {

		$user_id="";

       if($pdata['hidden_user_id']!=null || $pdata['hidden_user_id']!=""){

       	 $user_id=$pdata['hidden_user_id'];

       }else if($pdata['check_in_user_id']!=null || $pdata['check_in_user_id']!=""){

       	 $user_id=$pdata['check_in_user_id'];

       }else{

       	$user_id=$pdata['user_id'];

       }

       if($pdata['tags_id']!=null && $pdata['tags_id']!=""){		
		
		$tags_id=$pdata['tags_id'];
		}else{
		$tags_id="";
		}   

		//var_dump($tags_id);die;

		$query = $this->db->query ("SELECT `name`,MAX(`inventory_notes_id`),is_minus_quantity,inventory_id,inventorytype_id,maintenance,type,measurement_type,returning_less,description,return_type,MAX(`quantity`), SUM(`sub_quantity`) FROM `" . DB_PREFIX . "notes_by_inventory` WHERE `inventory_id` ='" . $inventory_id . "' and type='2' and `user_id` ='" . $user_id . "' and `tags_id` ='" . $tags_id . "' and is_checked_in = '0' GROUP BY name");
		
		return $query->row;
	}


	public function checkInInventoyNote($pdata, $fdata, $tempdata) {
		
		
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];


         $this->load->model ( 'user/user' );


        // var_dump($tempdata ['user_id']);
        // die;
		$user_info = $this->model_user_user->getUser($tempdata ['hidden_user_id']);


		
		$description = '';
		
		$description .= ' '.$user_info ['username'].' has Checked In Inventory | ';
		//$description .= ' ' . $tempdata ['user_id'];
		
		
		/*foreach ($tempdata ['new_module'] as  $module) {


          if ($module ['checkin'] == "1") {			
        
            if($fdata['tags_id']==$module['tags_id']){
				
				$inventory=$module['name']." ".$module['sub_quantity']." ".$module['measurement_type'];
				
			}
		  }
        	
        	
        }*/
		
		
		
		$description .=" ".$fdata['inventory'];


		if($fdata ['tags_id']!=null && $fdata ['tags_id']!=""){
			 $this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ($fdata ['tags_id'] );

		$description.=' | ' .$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name']." | ";
		}      
		
		$data ['form_type'] = "6";
		$data ['is_inventory_checkin_id'] = $tempdata ['hidden_user_id'];
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$description .= ' | ' . $this->db->escape ( $pdata ['comments'] );
		}
		
		$data ['notes_description'] = $description;
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $fdata['tags_id'] );
		
		if($tag_info!=null && $tag_info!=""){
			
			$notes_facility_id=$tag_info['facilities_id'];
			
		}else{
			$notes_facility_id=$facilities_id;

		}	
		
		
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $notes_facility_id );
		
		// var_dump($notes_id);
		// die;
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		
		if ($fdata ['tags_id'] !=null && $fdata ['tags_id'] !="") {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET tags_id = '".( int ) $fdata['tags_id']."',is_tag = '".( int ) $fdata['tags_id']."', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		
		
		
		// var_dump($data['notes_description']);
		// var_dump($facility);
		// die;
		
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
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		if ($tempdata ['user_id'] != null && $tempdata ['user_id'] != "") {
			
			if ($facilities_id) {
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $facility ['customer_key'] );
				
				$customer_key = $customer_info ['activecustomer_id'];
				$unique_id = $customer_info ['customer_key'];
			}
			$this->load->model ( 'inventory/inventory' );

			
			foreach ( $tempdata ['new_module'] as $mediactiondata ) {
				
				
				
				

			 $data=array(
			 'tags_id'=>$tempdata['tags_id'],
			 'user_id'=>$tempdata['hidden_user_id'],
			 'facilities_id'=>$facilities_id		 

			 );			 	


			 $checkin_data = $this->model_inventory_inventory->checkischeckedin ($data);
				
				
			if ($mediactiondata ['checkin'] == "1") {			
					
			 if($fdata['tags_id']==$mediactiondata['tags_id']){	
				 
				 if($mediactiondata['return_type']=="3"){

                  $is_not_return=$mediactiondata['not_return'];

                   if($checkin_data['is_checked_in']=='1'){

                    	$reason="";
                    	$is_returning_less="";

                    	 }else{

                    	  $reason=$mediactiondata['reason'];
                    	  $is_returning_less=$mediactiondata['returning_less'];

                      }               	

                    }else{

						$is_not_return="";
						$reason="";
						$is_returning_less="";
                    	  
                    }	
					//$this->db->query ( "INSERT INTO `" . DB_PREFIX . "notes_by_inventory` SET name = '" . $this->db->escape ( $mediactiondata ['name'] ) . "',type = '" . $this->db->escape ( $mediactiondata ['checkin'] ) . "', inventorytype_id = '" . $this->db->escape ( $mediactiondata ['inventorytype_id'] ) . "', inventory_id = '" . $this->db->escape ( $mediactiondata ['inventory_id'] ) . "', status = '" . $this->db->escape ( $mediactiondata ['status'] ) . "', maintenance = '" . $this->db->escape ( $mediactiondata ['maintenance'] ) . "', description = '" . $this->db->escape ( $mediactiondata ['description'] ) . "', quantity = '" . $this->db->escape ( $mediactiondata ['quantity'] ) . "',return_type = '" . $this->db->escape ( $mediactiondata ['return_type'] ) . "', measurement_type = '" . $this->db->escape ( $mediactiondata ['measurement_type'] ) . "',user_id = '" . $this->db->escape ( $tempdata ['hidden_user_id'] ) . "',tags_id = '" . $this->db->escape ( $mediactiondata ['tags_id'] ) . "',	is_minus_quantity = '" . $is_returning_less  . "',notes_id = '" . $this->db->escape ( $notes_id ) . "',customer_key = '" . $customer_key . "',unique_id = '" . $unique_id . "' ,not_return = '" . $is_not_return . "',returning_less = '" . $is_returning_less . "',reason = '" . $reason . "' ,sub_quantity = '" . $this->db->escape ( $mediactiondata ['sub_quantity'] ) . "', note_quantity = '" . $this->db->escape ( $mediactiondata ['sub_quantity'] ) . "', date_added = '" . $date_added . "',  date_updated = '" . $date_added . "',  facilities_id = '" . $facilities_id . "' " );
				}
				
				}
			}
		}
		
		return $notes_id;
	}
	public function checkOutInventoyNote($pdata, $fdata, $check_out_user, $tempdata) {	
		
		
		$this->load->model ( 'notes/notes' );
		$data = array ();
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($pdata ['imgOutput']) {
			$data ['imgOutput'] = $pdata ['imgOutput'];
		} else {
			$data ['imgOutput'] = $pdata ['signature'];
		}
		
		$data ['notes_pin'] = $pdata ['notes_pin'];
		$data ['user_id'] = $pdata ['user_id'];
		$data ['form_type'] = "5";
		$data ['is_inventory_checkout_id'] = $tempdata ['user_id'];

		$user_info = $this->model_user_user->getUser($check_out_user);


		
		$description = '';
		
		$description .= ' '.$user_info['username'].' has Checked Out Inventory';
		
        foreach ($tempdata ['test_module'] as  $module) {

          	$inventory=$inventory ." | ".$module['name']." ".$module['sub_quantity']." ".$module['measurement_type'];
        	
        
		}

        $description .=" ".$inventory;

       
		if($fdata ['tags_id']!=null && $fdata ['tags_id']!=""){
			 $this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ($fdata ['tags_id'] );

		$description.=' | ' .$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name']." | ";
		}		
		
		if ($pdata ['comments'] != null && $pdata ['comments']) {
			$description .= ' | ' . $this->db->escape ( $pdata ['comments'] );
		}
		
		$data ['notes_description'] = $description;
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $fdata['tags_id'] );
		
		if($tag_info!=null && $tag_info!=""){
			
			$notes_facility_id=$tag_info['facilities_id'];
			
		}else{
			$notes_facility_id=$facilities_id;

		}			
		
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $notes_facility_id );
		
		// var_dump($notes_id);
		// die;
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		// var_dump($data['notes_description']);
		// var_dump($facility);
		// die;
		
		if ($fdata ['tags_id'] !=null && $fdata ['tags_id'] !="") {
			$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET tags_id = '".( int ) $fdata['tags_id']."',is_tag = '".( int ) $fdata['tags_id']."', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
			$this->db->query ( $sql122 );
		}
		
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
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		if ($tempdata ['user_id'] != null && $tempdata ['user_id'] != "") {
		
			
			if ($facilities_id) {
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $facility ['customer_key'] );
				
				$customer_key = $customer_info ['activecustomer_id'];
				$unique_id = $customer_info ['customer_key'];
			}
			
			
			foreach ( $tempdata ['test_module'] as $mediactiondata ) {

                //var_dump($mediactiondata['return_type']);
				
				if($mediactiondata['return_type']=='2'){
					$sub_quantity=0;
				}else{
					$sub_quantity=$mediactiondata['sub_quantity'];
					
				}			

				if($mediactiondata['sub_quantity']!="" && $mediactiondata['sub_quantity']!=null){				
				
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "notes_by_inventory` SET name = '" . $this->db->escape ( $mediactiondata ['name'] ) . "', inventorytype_id = '" . $this->db->escape ( $mediactiondata ['inventorytype_id'] ) . "', inventory_id = '" . $this->db->escape ( $mediactiondata ['inventory_id'] ) . "', status = '" . $this->db->escape ( $mediactiondata ['status'] ) . "', maintenance = '" . $this->db->escape ( $mediactiondata ['maintenance'] ) . "', description = '" . $this->db->escape ( $mediactiondata ['description'] ) . "', quantity = '" . $this->db->escape ( $mediactiondata['quantity'] ) . "',return_type = '" . $this->db->escape ( $mediactiondata ['return_type'] ) . "', measurement_type = '" . $this->db->escape ( $mediactiondata ['measurement_type'] ) . "',user_id = '" . $this->db->escape ( $tempdata ['hidden_user_id'] ) . "',tags_id = '" . $this->db->escape ( $fdata ['tags_id'] ) . "',checkouttpye_id = '" . $this->db->escape ( $tempdata ['checkouttpye_id'] ) . "',notes_id = '" . $this->db->escape ( $notes_id ) . "',customer_key = '" . $customer_key . "',unique_id = '" . $unique_id . "' ,sub_quantity = '" . $this->db->escape ( $sub_quantity ) . "',  date_added = '" . $date_added . "',  date_updated = '" . $date_added . "',  facilities_id = '" . $facilities_id . "',type='2',note_quantity = '" . $this->db->escape ( $mediactiondata ['sub_quantity'] ) . "'" );
				}

			}//die;
		}
		return $notes_id;
	}
	public function getCheckOutInventoryByUserNotes($pdata) {

		$query = $this->db->query ( "SELECT `name`,`user_id`,`note_quantity`,`inventory_notes_id`,`date_updated`,is_minus_quantity,is_checked_in,inventory_id,inventorytype_id,maintenance,type,measurement_type,description,return_type,not_return,returning_less,reason,`quantity`, `sub_quantity`,`checkouttpye_id`,`tags_id` FROM " . DB_PREFIX . "notes_by_inventory WHERE notes_id='" . $pdata ['notes_id'] . "' and type = '1' " );
		
		return $query->rows;
	}
	public function updateQuantity($inventorydata, $tempdata, $updated_quantity,$checkless) {

          $user_id="";
          $tags_id="";		
		  
	if($tempdata['hidden_user_id']!=null || $tempdata['hidden_user_id']!=""){

          	$user_id=$tempdata['hidden_user_id'];

          }else if($tempdata['check_in_user_id']!=null || $tempdata['check_in_user_id']!=""){

          	$user_id=$tempdata['check_in_user_id'];

          }else{

          	$user_id=$tempdata['user_id'];

          }

          if($tempdata['tags_id']!=null || $tempdata['tags_id']!=""){
         
          	$tags_id=$tempdata['tags_id'];

          }else{

          	$tags_id="";

          }
		  
		if($checkless==1){
			$datas = $this->model_inventory_inventory->getCheckOutInventoryByInventoryIds ($inventorydata['inventory_id'],$tempdata);


        if($datas['returning_less']!=""){         	

			if($inventorydata['sub_quantity'] < $datas["returning_less"]){

				  $updated_checkin = $datas["returning_less"] - $inventorydata['sub_quantity']-$inventorydata['not_return'];

			$sql2 = "UPDATE `" . DB_PREFIX . "notes_by_inventory` SET returning_less = '".$updated_checkin ."' WHERE inventory_id = '" .$inventorydata['inventory_id'] . "' AND  user_id= '".$user_id."' AND  tags_id= '".$tags_id."'";
				
			    $this->db->query($sql2);						

					}

					}else{							
						

						if($inventorydata['sub_quantity'] < $datas["SUM(`sub_quantity`)"]){
                         $updated_checkin = $datas["SUM(`sub_quantity`)"] - $inventorydata['sub_quantity']-$inventorydata['not_return'];

                       /*  if($updated_checkin==0){
                            $final_checkin="";

                         }else{

                         	 $final_checkin=$updated_checkin;

                         }*/

                       // var_dump($updated_checkin);
						//die;

		   $sql2 = "UPDATE `" . DB_PREFIX . "notes_by_inventory` SET returning_less = '".$updated_checkin ."' WHERE inventory_id = '" .$inventorydata['inventory_id'] . "' AND  user_id= '".$user_id."' AND  tags_id= '".$tags_id."'";

		   	
		    $this->db->query($sql2);
						

			}
		} 

		}	  




        /* if($checkless==1){       



         	   if($inventorydata['returning_less']!="" && $inventorydata['returning_less']!=null
         	   	||$inventorydata['not_return']!="" && $inventorydata['not_return']!=null){

						  $checkin_quantity=$inventorydata['returning_less'];
							 $not_return_quantity=$inventorydata['not_return'];

						 $sub_quantity = $datas["SUM(`sub_quantity`)"]-$checkin_quantity-$not_return_quantity;



					}		
		           



         		$sql2="UPDATE `" . DB_PREFIX . "notes_by_inventory` SET sub_quantity ='".$final_sub_quantity."' WHERE user_id='".$tempdata['user_id']."' AND inventory_id ='".$inventorydata['inventory_id']."' AND type='2'";

         }
*/


		  $sql = "UPDATE `" . DB_PREFIX . "inventory` SET quantity = '".$updated_quantity ."' WHERE inventory_id = '" .$inventorydata['inventory_id'] . "'";         

		  $this->db->query($sql);

		  if($checkless=="0"){
          

		  	$sql2="UPDATE `" . DB_PREFIX . "notes_by_inventory` SET returning_less='', is_checked_in ='1' WHERE user_id='".$user_id."' AND inventory_id ='".$inventorydata['inventory_id']."'  AND type='2' AND  tags_id= '".$tags_id."'";	

		   }




		   $this->db->query($sql2);	

	}

   public function getTotalSubQuantity($inventory_id){ 

  $query = $this->db->query ( "SELECT `name`,user_id,COUNT(user_id),MAX(`inventory_notes_id`),inventory_id,is_minus_quantity,inventorytype_id,maintenance,type,measurement_type,description,returning_less,return_type,MAX(`quantity`), SUM(`sub_quantity`),MAX(`sub_quantity`) FROM `" . DB_PREFIX . "notes_by_inventory` WHERE `inventory_id` ='" . $inventory_id . "' and type='2' AND return_type='3' and is_checked_in = '0' GROUP BY name" );   

   //$query=$this->db->query("SELECT  SUM(sub_quantity),inventory_notes_id,type,returning_less,is_checked_in FROM dg_notes_by_inventory where type='2' AND return_type='3' AND `is_checked_in`='0' AND `inventory_id`='".$inventory_id."'");    


   	 return $query->row;
   
   }



	public function getinventoryById($inventorydata) {

	
		$query = $this->db->query ("SELECT `quantity` FROM ". DB_PREFIX . "inventory WHERE `inventory_id`='".$inventorydata['inventory_id']."'");
		 return $query->row;
	 
	}
	
	public function getinventoryByInventoryNotesId($inventory_notes_id) {

	
		$query = $this->db->query ("SELECT `sub_quantity` FROM ". DB_PREFIX . "notes_by_inventory WHERE `inventory_notes_id`='".$inventory_notes_id."'");
		 return $query->row;
	 
	}
	
	public function updateSubQuantity($inventorydata,$updated_sub_quantity) {

	
	
		 
	$sql2="UPDATE `" . DB_PREFIX . "notes_by_inventory` SET sub_quantity='".$updated_sub_quantity."'  WHERE inventory_notes_id='".$inventorydata['inventory_notes_id']."'";	
	 
	$this->db->query($sql2);	
	}


public function getMeasurementValues($customer_key) {		
		
		//$query = $this->db->query ("SELECT * FROM ". DB_PREFIX . "measurement");
		 //return $query->rows;	

		$sql="CALL usp_getAll_Inv_MeasurementsByCustKey('".$customer_key."')";
		
		
		
		$query = $this->db->query ($sql);

		return $query->rows;		 
}


public function getMeasurementValuesById($data) {		
		
		//$query = $this->db->query ("SELECT * FROM ". DB_PREFIX . "measurement WHERE   measurement_id='".$data['measurement_type']."'");
		 //return $query->row;
		 
		$sql="CALL usp_getAll_InvMeasure_By_MeasureId('" .$data['measurement_type'] . "')";
		$query = $this->db->query($sql);		
		
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "measurement WHERE measurement_id = '" . (int)$measurement_id . "'");		
		return $query->row;

}

public function getInventoryCustomValues() {		
		
		$query = $this->db->query ("SELECT * FROM ". DB_PREFIX . "measurement");
		 return $query->rows;
	 
	}

	public function checkischeckedin($data=array()) {		
		
		$query = $this->db->query ("SELECT is_checked_in FROM ". DB_PREFIX . "notes_by_inventory WHERE `type`='2' AND `user_id`='".$data['user_id']."' AND `tags_id`='".$data['tags_id']."' and `facilities_id`='".$data['facilities_id']."'");
		 return $query->row;
	 
	}
	
	public function updateCheckin($notes_id,$fdata,$mediactiondata){
		
    $this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $fdata ['facilities_id'] );
		if ($facilities_info ['is_master_facility'] == '1') {
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
				
				$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
				$timezone_name = $timezone_info ['timezone_value'];
			} else {
				$facilities_id = $fdata ['facilities_id'];
				$timezone_name = $fdata ['facilitytimezone'];
			}
		} else {
			$facilities_id = $fdata ['facilities_id'];
			$timezone_name = $fdata ['facilitytimezone'];
		}
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;	

		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $facilities_info ['customer_key'] );

		$customer_key = $customer_info ['activecustomer_id'];
		$unique_id = $customer_info ['customer_key'];		
		

					$this->db->query ( "INSERT INTO `" . DB_PREFIX . "notes_by_inventory` SET name = '" . $this->db->escape ( $mediactiondata ['name'] ) . "',
					type = '" . $this->db->escape ( $mediactiondata ['checkin'] ) . "',
					inventorytype_id = '" . $this->db->escape ( $mediactiondata ['inventorytype_id'] ) . "',
					inventory_id = '" . $this->db->escape ( $mediactiondata ['inventory_id'] ) . "',
					status = '" . $this->db->escape ( $mediactiondata ['status'] ) . "', 
					maintenance = '" . $this->db->escape ( $mediactiondata ['maintenance'] ) . "',
					description = '" . $this->db->escape ( $mediactiondata ['description'] ) . "',
					quantity = '" . $this->db->escape ( $mediactiondata ['quantity'] ) . "',
					return_type = '" . $this->db->escape ( $mediactiondata ['return_type'] ) . "',
					measurement_type = '" . $this->db->escape ( $mediactiondata ['measurement_type'] ) . "',
					user_id = '" . $this->db->escape ( fdata['hidden_user_id'] ) . "',
					tags_id = '" . $this->db->escape ( fdata['tags_id'] ) . "',	
					is_minus_quantity = '" . $is_returning_less  . "',
					notes_id = '" . $this->db->escape ( $notes_id ) . "',
					customer_key = '" . $customer_key . "',unique_id = '" . $unique_id . "' ,
					not_return = '" . $is_not_return . "',returning_less = '" . $is_returning_less . "',
					reason = '" . $reason . "' ,sub_quantity = '" . $this->db->escape ( $mediactiondata ['sub_quantity'] ) . "',
					date_added = '" . $date_added . "', 
					date_updated = '" . $date_added . "', 
					facilities_id = '" . $fdata ['facilities_id'] . "',
					note_quantity = '" . $this->db->escape ( $mediactiondata ['sub_quantity'] ) . "'" );

	}		
}
?>