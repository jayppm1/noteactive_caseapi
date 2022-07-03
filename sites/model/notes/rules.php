<?php
class Modelnotesrules extends Model {
	
	public function getRules($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "rules r LEFT JOIN " . DB_PREFIX . "rules_tigger rt ON (r.rules_id = rt.rules_id) where r.status='1' and FIND_IN_SET('". $data['facilities_id']."', r.facilities_id) ";
		$query = $this->db->query($sql);
		$rules_data = array();
		
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$rules_data[$result['rules_tigger_id']] = array( 
					'rules_id'      => $result['rules_id'],
					'rules_operator'      => $result['rules_operator'],
					'snooze_dismiss'      => $result['snooze_dismiss'],
					'facilities_id'      => $result['facilities_id'],
					'recurnce_week'      => $result['recurnce_week'],
					'recurnce_day'      => $result['recurnce_day'],
					'rules_name'      => $result['rules_name'],
					'rules_operation'      => $result['rules_operation'],
					'rules_operation_recurrence'      => $result['rules_operation_recurrence'],
					'rules_operation_time'      => $result['rules_operation_time'],
					'rules_tigger_id'      => $result['rules_tigger_id'],
					'rules_module'       => unserialize($result['rules_module']),
					'onschedule_rules_module'       => unserialize($result['onschedule_rules_module']),
					
					
					'rule_action'       => unserialize($result['rule_action']),
					'rule_action_content'       => unserialize($result['rule_action_content']),
				);
			}
			
			return $rules_data;
		} else {
			return false;	
		}
		
	}
	
}
?>