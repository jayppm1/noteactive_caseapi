<?php
class Modelcustomerapiurl extends Model {
	
	public function getcustomerid($unique_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "api_url WHERE unique_id = '" . $unique_id . "'");
		
		return $query->row;
	}
	
	public function getcustomerid1($keyname, $customer_key) {
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($customer_key);
		
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "api_url WHERE keyname = '" . $this->db->escape($keyname) . "' and customer_key = '".$customer_info['activecustomer_id']."' ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getapiurl($api_url_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "api_url WHERE api_url_id = '" . (int)$api_url_id . "'");
		
		return $query->row;
	}
	
	public function getapiurls($customer_key) {
		
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($customer_key);
			
			
		$sql = "SELECT * FROM " . DB_PREFIX . "api_url where status = 1 ";
		
		$sql .= " and customer_key = '".$customer_info['activecustomer_id']."'";
		
		$sql .= " ORDER BY keyname";	
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;	
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalapiurls() {
		$sql = " where 1 = 1 ";
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "api_url ".$sql." ");
		
		return $query->row['total'];
	}	
}
?>