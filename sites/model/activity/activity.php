<?php
class ModelActivityActivity extends Model {
	public function addActivity($key, $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "log_activity` SET `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
	
	public function addActivitySave($key, $data, $type) {
		
		if($data['facilities_id'] != null && $data['facilities_id'] != ""){
			$facilities_id = $data['facilities_id'];
		}else{
			$facilities_id = $this->customer->getId ();
		}
		$this->load->model ( 'facilities/facilities' );
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$unique_id = $facilityinfo ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		if($customer_info['log_table'] != null && $customer_info['log_table'] != ""){
			try {
				$adb = new DB(ADB_DRIVER, ADB_HOSTNAME, ADB_USERNAME, ADB_PASSWORD, ADB_DATABASE);
				$this->registry->set('adb', $adb );
				
				$log_table = $customer_info['log_table'];
			
				$this->adb->query("INSERT INTO `" . DB_PREFIX . "".$log_table."` SET `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',`type` = '" . $this->db->escape($type) . "', `date_added` = NOW()", MYSQLI_ASYNC);
			
			} catch ( Exception $e ) {
				echo 'Caught exception: ', $e->getMessage(), "\n";
				die;
				$this->load->model ( 'api/emailapi' );
				
				$message33 = "";
				$message33 .= "Activity LOG ERROR ".$e->getMessage();
				
				$edata = array ();
				$edata ['message'] = $message33;
				$edata ['subject'] = 'Activity LOG ERROR';
				$edata ['user_email'] = 'note-sync@noteactive.com';
				$this->load->model ( 'api/emailapi' );
				//$email_status = $this->model_api_emailapi->sendmail ( $edata );
			}
		}
	}
	
}