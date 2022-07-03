<?php
class Modelsettingactiveforms extends Model {
	
	public function getActivekeywords($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "activeform r LEFT JOIN " . DB_PREFIX . "activeform_trigger rt ON (r.activeform_id = rt.activeform_id) where r.status='1' and r.facilities_id = '". $data['facilities_id']."' ";
		$query = $this->db->query($sql);
		$rules_data = array();
		
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$rules_data[$result['activeform_trigger_id']] = array( 
					'activeform_id'      => $result['activeform_id'],
					'activeform_name'      => $result['activeform_name'],
					'forms_id'      => $result['forms_id'],
					'facilities_id'      => $result['facilities_id'],
					'forms_ids'      => $result['forms_ids'],
					'customer_key'      => $result['customer_key'],
					'keyword_id'      => $result['keyword_id'],
					'activeform_trigger_id'      => $result['activeform_trigger_id'],
					'rules_module'       => unserialize($result['rules_module']),
					'rule_action'       => unserialize($result['rule_action']),
					'rule_action_content'       => unserialize($result['rule_action_content']),
				);
			}
			
			return $rules_data;
		} else {
			return false;	
		}
		
	}
	
	public function getactiveform($activeform_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "activeform WHERE activeform_id = '" . (int)$activeform_id . "'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getActiveForm2($activeform_id, $facilities_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "activeform r LEFT JOIN " . DB_PREFIX . "activeform_trigger rt ON (r.activeform_id = rt.activeform_id) where r.status='1' and r.activeform_id = '". $activeform_id."' ";
		$query = $this->db->query($sql);
		return $query->row;
		
	}
	
	public function getActiveForm23($keyword_id, $facilities_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "activeform r LEFT JOIN " . DB_PREFIX . "activeform_trigger rt ON (r.activeform_id = rt.activeform_id) where r.status='1' and r.keyword_id = '". $keyword_id."' and r.facilities_id = '". $facilities_id."'   ";
		$query = $this->db->query($sql);
		return $query->row;
		
	}
	
	public function getactiveform2id($forms_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "activeform WHERE forms_id = '" . (int)$forms_id . "'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	
}
?>