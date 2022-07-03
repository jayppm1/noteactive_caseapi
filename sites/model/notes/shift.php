<?php
class Modelnotesshift extends Model {
	
	public function getshift($shift_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "shift WHERE shift_id = '" . (int)$shift_id . "'");
		
		return $query->row;
	}
	
	public function getAllShift($data = array()) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "shift` WHERE customer_key = '" . $data ['customer_key'] . "' and facilities_id = '" . $data ['facilities_id'] . "'  order by date_added ASC";
			$query = $this->db->query ( $sql );
			return $query->rows;
	}
	
	public function getshifts($customer_key) {
		
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($customer_key);
			
			
		$sql = "SELECT * FROM " . DB_PREFIX . "shift where status = 1 ";
		
		$sql .= " and customer_key = '".$customer_info['activecustomer_id']."'";
		
		$sql .= " ORDER BY sort_order";	
			
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
	
	public function getTotalshifts() {
		$sql = " where 1 = 1 ";
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "shift ".$sql." ");
		
		return $query->row['total'];
	}	
}
?>