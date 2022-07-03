<?php
class Modelcustomercustomer extends Model {
	
	
	public function getcustomerid($customer_key) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "activecustomer WHERE customer_key = '" . $customer_key . "'");
		
		return $query->row;
	}
	
	public function getcustomer($activecustomer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "activecustomer WHERE activecustomer_id = '" . (int)$activecustomer_id . "'");
		
		return $query->row;
	}
	
	public function getcustomers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "activecustomer";
		
		/*$sql.= " where 1 = 1 ";
		if ($this->session->data['customer_key'] != null && $this->session->data['customer_key'] != "") {
					
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomer($this->session->data['customer_key']);
			
			$sql .= " and customer_key = '".$customer_info['customer_key']."'";
			
		}*/
		
		$sql .= " ORDER BY customer_key";	
			
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
			
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalcustomers() {
		$sql = " where 1 = 1 ";
		/*if ($this->session->data['customer_key'] != null && $this->session->data['customer_key'] != "") {
					
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomer($this->session->data['customer_key']);
			
			$sql .= " and customer_key = '".$customer_info['customer_key']."'";
			
		}*/
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "activecustomer ".$sql." ");
		
		return $query->row['total'];
	}	
}
?>